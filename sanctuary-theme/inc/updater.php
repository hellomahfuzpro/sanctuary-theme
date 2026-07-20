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
	return apply_filters( 'sanctuary_github_repo', 'https://github.com/OWNER/sanctuary-theme' );
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

	$update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		$repo,
		SANCTUARY_DIR . '/style.css', // theme root file
		'sanctuary'                    // theme directory slug
	);

	// Use GitHub "Releases" as the source of truth for versions.
	if ( method_exists( $update_checker->getVcsApi(), 'enableReleaseAssets' ) ) {
		$update_checker->getVcsApi()->enableReleaseAssets();
	}

	// Public repo: no token needed. For a private repo you'd call:
	// $update_checker->setAuthentication( 'YOUR_TOKEN' );
}
add_action( 'after_setup_theme', 'sanctuary_bootstrap_updater' );
