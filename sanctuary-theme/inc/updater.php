<?php
/**
 * Over-the-air theme updates from GitHub releases.
 *
 * Uses the bundled Plugin Update Checker library (YahnisElsts, MIT) which
 * supports themes as well as plugins. Point it at the public GitHub repo; on a
 * new tagged release WordPress shows an update under Appearance → Themes and
 * installs it in place — no FTP, no extra plugin for the client.
 *
 * To cut a release: bump `Version:` in style.css, commit, then create a GitHub
 * release/tag (e.g. v0.2.0). The checker matches the highest semver tag.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * !! Set this to the real public repo URL before shipping to the client. !!
 * Filterable so staging/forks can override without editing the theme.
 */
function sanctuary_github_repo() {
	return apply_filters( 'sanctuary_github_repo', 'https://github.com/hellomahfuzpro/sanctuary-theme' );
}

function sanctuary_bootstrap_updater() {
	$lib = SANCTUARY_DIR . '/lib/plugin-update-checker/plugin-update-checker.php';

	if ( ! file_exists( $lib ) ) {
		// Library not vendored yet — fail quietly (see lib/README).
		return;
	}

	require_once $lib;

	$repo = sanctuary_github_repo();
	if ( false !== strpos( $repo, 'OWNER/' ) ) {
		return; // Placeholder not yet configured.
	}

	// Derive the slug from the actual installed directory rather than
	// hardcoding it. The release zip extracts to `sanctuary/`, but a theme
	// installed another way (repo download, manual folder upload) lands in a
	// differently-named directory — and if this slug doesn't match the real
	// folder, PUC finds no matching theme and updates silently never appear.
	$update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		$repo,
		SANCTUARY_DIR . '/style.css', // theme root file
		get_template()                 // theme directory slug (actual folder name)
	);

	// Use GitHub "Releases" as the source of truth for versions.
	if ( method_exists( $update_checker->getVcsApi(), 'enableReleaseAssets' ) ) {
		$update_checker->getVcsApi()->enableReleaseAssets();
	}

	// Public repo: no token needed. For a private repo you'd call:
	// $update_checker->setAuthentication( 'YOUR_TOKEN' );

	// Keep a handle so the diagnostics screen can force a check.
	$GLOBALS['sanctuary_update_checker'] = $update_checker;
}
add_action( 'after_setup_theme', 'sanctuary_bootstrap_updater' );

/**
 * Diagnostics screen — "no update showing" is otherwise invisible to debug:
 * WordPress caches update checks for up to 12 hours, and a slug/folder
 * mismatch fails silently. This shows what the updater actually sees and
 * lets you force a fresh check.
 */
add_action( 'admin_menu', function () {
	add_management_page(
		__( 'Sanctuary: Updates', 'sanctuary' ),
		__( 'Sanctuary: Updates', 'sanctuary' ),
		'manage_options',
		'sanctuary-updates',
		'sanctuary_updates_screen'
	);
} );

function sanctuary_updates_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$checker = isset( $GLOBALS['sanctuary_update_checker'] ) ? $GLOBALS['sanctuary_update_checker'] : null;

	echo '<div class="wrap"><h1>' . esc_html__( 'The Sanctuary — theme updates', 'sanctuary' ) . '</h1>';

	if ( ! $checker ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'The update checker did not start. Either the library in /lib is missing, or the repo URL is still a placeholder.', 'sanctuary' ) . '</p></div></div>';
		return;
	}

	$result = null;
	if ( isset( $_POST['snc_check'] ) && check_admin_referer( 'snc_check_updates' ) ) {
		delete_site_transient( 'update_themes' );
		$result = $checker->checkForUpdates();
	}

	$installed = wp_get_theme( get_template() )->get( 'Version' );
	$state     = $checker->getUpdateState();
	$update    = $state ? $state->getUpdate() : null;

	echo '<table class="widefat striped" style="max-width:720px;"><tbody>';
	printf( '<tr><td>%s</td><td><code>%s</code></td></tr>', esc_html__( 'Installed version', 'sanctuary' ), esc_html( $installed ) );
	printf( '<tr><td>%s</td><td><code>%s</code></td></tr>', esc_html__( 'Installed folder', 'sanctuary' ), esc_html( get_template() ) );
	printf( '<tr><td>%s</td><td><code>%s</code></td></tr>', esc_html__( 'Repository', 'sanctuary' ), esc_html( sanctuary_github_repo() ) );
	printf(
		'<tr><td>%s</td><td>%s</td></tr>',
		esc_html__( 'Latest version found', 'sanctuary' ),
		$update ? '<code>' . esc_html( $update->version ) . '</code>' : esc_html__( 'none detected yet — run a check below', 'sanctuary' )
	);
	echo '</tbody></table>';

	if ( $update && version_compare( $update->version, $installed, '>' ) ) {
		printf(
			'<div class="notice notice-success"><p>%s <a href="%s">%s</a></p></div>',
			esc_html__( 'An update is available.', 'sanctuary' ),
			esc_url( admin_url( 'themes.php' ) ),
			esc_html__( 'Go to Appearance → Themes to install it.', 'sanctuary' )
		);
	} elseif ( $result ) {
		echo '<div class="notice notice-info"><p>' . esc_html__( 'Checked — no newer release than the installed version.', 'sanctuary' ) . '</p></div>';
	}

	echo '<form method="post">';
	wp_nonce_field( 'snc_check_updates' );
	echo '<p><button class="button button-primary" name="snc_check" value="1">' . esc_html__( 'Check for updates now', 'sanctuary' ) . '</button></p>';
	echo '</form>';

	echo '<p style="color:#666;max-width:70ch;">' . esc_html__( 'If the latest version stays blank, the repo has no published Release with a zip attached, or this site cannot reach api.github.com.', 'sanctuary' ) . '</p>';
	echo '</div>';
}
