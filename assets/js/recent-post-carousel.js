(function ($) {
	'use strict';

	function initAll() {
		$('.rpc-carousel').each(function () {
			var $carousel = $(this);
			if ($carousel.data('bs.carousel')) { return; }

			var $inner = $carousel.find('.carousel-inner');
			var maxHeight = 0;

			$carousel.find('.carousel-item').each(function () {
				var $item = $(this);
				$item.css({ display: 'block', visibility: 'hidden', position: 'relative' });
				maxHeight = Math.max( maxHeight, $item.outerHeight(true) );
				$item.css({ display: '', visibility: '', position: '' });
			});

			if ( maxHeight > 0 ) {
				$inner.css( 'min-height', maxHeight + 'px' );
			}

			var interval = $carousel.data('interval');
			$carousel.carousel({
				interval: interval === 'false' ? false : parseInt(interval, 10) || false,
				wrap: true,
				keyboard: true,
			});

			var id = $carousel.attr('id');
			var $indicators = $('[data-target="#' + id + '"]').closest('.rpc-indicators');

			$carousel.on('slide.bs.carousel', function (e) {
				$indicators.find('li').removeClass('active').eq(e.to).addClass('active');
			});
		});
	}

	$(document).ready(initAll);
	$(window).on('vc_js_load vc-full-screen-frontend-editor-load', initAll);

}(window.jQuery));
