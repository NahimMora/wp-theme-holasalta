( function () {
	'use strict';

	var toggle = document.querySelector( '.hs-sections-toggle' );
	var panel = document.getElementById( 'hs-sections-panel' );

	if ( ! toggle || ! panel ) {
		return;
	}

	var closeButton = panel.querySelector( '.hs-sections-close' );

	function closePanel() {
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.setAttribute( 'aria-label', 'Abrir menú de secciones' );
		panel.hidden = true;
	}

	function openPanel() {
		toggle.setAttribute( 'aria-expanded', 'true' );
		toggle.setAttribute( 'aria-label', 'Cerrar menú de secciones' );
		panel.hidden = false;
	}

	toggle.addEventListener( 'click', function () {
		if ( toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
			closePanel();
			return;
		}

		openPanel();
	} );

	if ( closeButton ) {
		closeButton.addEventListener( 'click', closePanel );
	}

	document.addEventListener( 'click', function ( event ) {
		if ( panel.hidden || panel.contains( event.target ) || toggle.contains( event.target ) ) {
			return;
		}

		closePanel();
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' && ! panel.hidden ) {
			closePanel();
			toggle.focus();
		}
	} );
}() );
