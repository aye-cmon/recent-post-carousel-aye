(function ($) {
	'use strict';

	//Sets up every RPC carousel on the page
	function initAll() {
		$('.rpc-carousel').each(function () {
			var $carousel = $(this);
			// Skip if Bootstrap has already initialized this carousel.
			// Prevents double-init when initAll runs more than once (e.g. editor reloads).
			if ($carousel.data('bs.carousel')) { return; }

			var $inner = $carousel.find('.carousel-inner');
			var maxHeight = 0;

			// Measure the tallest slide
			$carousel.find('.carousel-item').each(function () {
				var $item = $(this);
				
				// Temporarily force the slide to render (inactive slides are display:none,
				// which reports a height of 0). visibility:hidden keeps it invisible to the
				// user, and position:relative keeps it in normal flow so it can be measured.
				$item.css({ display: 'block', visibility: 'hidden', position: 'relative' });
				// Track the largest height seen (true = include margins)
				maxHeight = Math.max( maxHeight, $item.outerHeight(true) );
				// Remove the inline overrides so the stylesheet controls the slide again
				$item.css({ display: '', visibility: '', position: '' });
			});

			// Lock the container to the tallest slide's height so the page
			// doesn't shift when slides change. Skipped if nothing measured (e.g. hidden parent).
			if ( maxHeight > 0 ) {
				$inner.css( 'min-height', maxHeight + 'px' );
			}

			// --- Start the carousel ---
			// Read autoplay delay (ms) from data-interval="..."
			var interval = $carousel.data('interval');
			$carousel.carousel({
				interval: interval === 'false' ? false : parseInt(interval, 10) || false,
				wrap: true,
				keyboard: true,
			});

			// Sync custom indicators
			var id = $carousel.attr('id');
			// Find the indicator list targeting this carousel (via data-target="#id"),
			// then get its .rpc-indicators wrapper
			var $indicators = $('[data-target="#' + id + '"]').closest('.rpc-indicators');

			// When a slide transition starts, move the "active" class to the dot
			// matching the destination slide (e.to = index of the incoming slide)
			$carousel.on('slide.bs.carousel', function (e) {
				$indicators.find('li').removeClass('active').eq(e.to).addClass('active');
			});
		});
	}

	$(document).ready(initAll);
	$(window).on('vc_js_load vc-full-screen-frontend-editor-load', initAll);

}(window.jQuery));
