( function () {
	'use strict';

	// ── Breaking news ticker ──
	var tickerTrack = document.querySelector( '.hs-ticker-track' );

	if ( tickerTrack && ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		var tickerItems = Array.from( tickerTrack.querySelectorAll( '.hs-ticker-item' ) );

		tickerItems.forEach( function ( item ) {
			var clone = item.cloneNode( true );
			clone.setAttribute( 'aria-hidden', 'true' );
			tickerTrack.appendChild( clone );
		} );
	}

	// ── Reading progress bar ──
	var progressBar = document.getElementById( 'hs-reading-progress' );

	if ( progressBar ) {
		function updateProgress() {
			var scrolled   = window.scrollY || document.documentElement.scrollTop;
			var docHeight  = document.documentElement.scrollHeight - window.innerHeight;
			progressBar.style.width = ( docHeight > 0 ? Math.min( 100, ( scrolled / docHeight ) * 100 ) : 0 ) + '%';
		}
		document.addEventListener( 'scroll', updateProgress, { passive: true } );
		updateProgress();
	}

	// ── Sticky header shrink ──
	var siteHeader = document.querySelector( '.hs-site-header' );

	if ( siteHeader ) {
		function onHeaderScroll() {
			siteHeader.classList.toggle( 'hs-header--scrolled', window.scrollY > 60 );
		}
		document.addEventListener( 'scroll', onHeaderScroll, { passive: true } );
		onHeaderScroll();
	}

	// ── Sticky share bar ──
	var shareBar   = document.querySelector( '.hs-share-sticky' );
	var siteFooter = document.querySelector( '.hs-site-footer' );

	if ( shareBar ) {
		function onShareScroll() {
			var pastStart  = window.scrollY > 200;
			var nearFooter = siteFooter
				? siteFooter.getBoundingClientRect().top < window.innerHeight + 80
				: false;
			shareBar.classList.toggle( 'is-visible', pastStart && ! nearFooter );
		}
		document.addEventListener( 'scroll', onShareScroll, { passive: true } );
		onShareScroll();

		var copyBtn = shareBar.querySelector( '.hs-share-copy' );

		if ( copyBtn && navigator.clipboard ) {
			copyBtn.addEventListener( 'click', function () {
				navigator.clipboard.writeText( window.location.href ).then( function () {
					copyBtn.dataset.copied = '1';
					copyBtn.setAttribute( 'aria-label', 'Enlace copiado' );
					setTimeout( function () {
						delete copyBtn.dataset.copied;
						copyBtn.setAttribute( 'aria-label', 'Copiar enlace' );
					}, 2000 );
				} );
			} );
		}
	}

	// ── Search panel ──
	var searchToggle = document.querySelector( '.hs-search-toggle' );
	var searchPanel  = document.getElementById( 'hs-search-panel' );
	var searchInput  = searchPanel ? searchPanel.querySelector( 'input[type="search"]' ) : null;
	var searchClose  = searchPanel ? searchPanel.querySelector( '.hs-search-close' ) : null;

	function closeSearch() {
		if ( ! searchToggle || ! searchPanel ) {
			return;
		}
		searchToggle.setAttribute( 'aria-expanded', 'false' );
		searchPanel.hidden = true;
	}

	function openSearch() {
		if ( ! searchToggle || ! searchPanel ) {
			return;
		}
		closeSections();
		searchToggle.setAttribute( 'aria-expanded', 'true' );
		searchPanel.hidden = false;
		if ( searchInput ) {
			searchInput.focus();
		}
	}

	if ( searchToggle && searchPanel ) {
		searchToggle.addEventListener( 'click', function () {
			if ( searchToggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				closeSearch();
			} else {
				openSearch();
			}
		} );

		if ( searchClose ) {
			searchClose.addEventListener( 'click', closeSearch );
		}

		document.addEventListener( 'click', function ( event ) {
			if ( searchPanel.hidden || searchPanel.contains( event.target ) || searchToggle.contains( event.target ) ) {
				return;
			}
			closeSearch();
		} );
	}

	// ── Sections drawer ──
	var sectionsToggle  = document.querySelector( '.hs-sections-toggle' );
	var sectionsPanel   = document.getElementById( 'hs-sections-panel' );
	var sectionsBackdrop = document.querySelector( '.hs-sections-backdrop' );
	var drawerClose     = sectionsPanel ? sectionsPanel.querySelector( '.hs-drawer-close' ) : null;

	function closeSections() {
		if ( ! sectionsToggle || ! sectionsPanel ) {
			return;
		}
		sectionsToggle.setAttribute( 'aria-expanded', 'false' );
		sectionsPanel.hidden = true;
		if ( sectionsBackdrop ) {
			sectionsBackdrop.classList.remove( 'is-visible' );
		}
		document.body.style.overflow = '';
	}

	function openSections() {
		if ( ! sectionsToggle || ! sectionsPanel ) {
			return;
		}
		closeSearch();
		sectionsToggle.setAttribute( 'aria-expanded', 'true' );
		sectionsPanel.hidden = false;
		if ( sectionsBackdrop ) {
			requestAnimationFrame( function () {
				sectionsBackdrop.classList.add( 'is-visible' );
			} );
		}
		document.body.style.overflow = 'hidden';
		if ( drawerClose ) {
			drawerClose.focus();
		}
	}

	if ( sectionsToggle && sectionsPanel ) {
		sectionsToggle.addEventListener( 'click', function () {
			if ( sectionsToggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				closeSections();
			} else {
				openSections();
			}
		} );

		if ( drawerClose ) {
			drawerClose.addEventListener( 'click', closeSections );
		}

		if ( sectionsBackdrop ) {
			sectionsBackdrop.addEventListener( 'click', closeSections );
		}
	}

	// ── Global Escape key ──
	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key !== 'Escape' ) {
			return;
		}
		if ( searchPanel && ! searchPanel.hidden ) {
			closeSearch();
			if ( searchToggle ) {
				searchToggle.focus();
			}
		}
		if ( sectionsPanel && ! sectionsPanel.hidden ) {
			closeSections();
			if ( sectionsToggle ) {
				sectionsToggle.focus();
			}
		}
	} );

}() );
