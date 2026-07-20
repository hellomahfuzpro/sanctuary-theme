/* The Sanctuary — front-end behaviour (vanilla, no dependencies). */
( function () {
	'use strict';

	// Mobile nav toggle.
	document.addEventListener( 'click', function ( e ) {
		var toggle = e.target.closest( '.nav-toggle' );
		if ( ! toggle ) {
			return;
		}
		var links = document.getElementById( 'primary-nav' );
		if ( ! links ) {
			return;
		}
		var open = links.classList.toggle( 'is-open' );
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	} );
}() );
