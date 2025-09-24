(function($) {
	function initStickyHeaderScript($scope) {
		var header = $('header');
		var page = $('#page');
		var topbar = $('.entro-topbar-sticky-hide-yes');
		var isFixedHeader = $('body').hasClass('easyel-fixed-header');

		if (!header.length || !page.length) {
			return;
		}

		header.addClass("eel-sticky-header-on");

		function updatePaddingAndMargin() {
			var headerHeight = header.outerHeight();
			var topbarHeight = topbar.length ? topbar.outerHeight() : 0;

			if (!isFixedHeader) {
				page.css('padding-top', headerHeight + 'px');
			} else {
				page.css('padding-top', '');
			}

			if (header.hasClass('eel-up-scroll')) {
				header.css('margin-top', `-${topbarHeight}px`);
			} else {
				header.css('margin-top', '0px');
			}
		}

		let lastScroll = 0;
		const stickyScrollThreshold = 50; 

		function sticky_header() {
			let scroll = $(window).scrollTop();

			if (scroll > stickyScrollThreshold) {
				header.addClass('eel-sticky-header');
				if (scroll > lastScroll) {
					// স্ক্রল ডাউন → হেডার হাইড
					header.removeClass('eel-up-scroll').addClass('eel-down-scroll');
				} else {
					// স্ক্রল আপ → হেডার শো
					header.removeClass('eel-down-scroll').addClass('eel-up-scroll');
				}
			} else {
				// একেবারে টপ এ গেলে
				header.removeClass('eel-sticky-header eel-up-scroll eel-down-scroll');
			}
			lastScroll = scroll;

			updatePaddingAndMargin();
		}

		$(document).ready(function () {
			updatePaddingAndMargin();
			sticky_header();
		});

		$(window).on('scroll resize', function () {
			sticky_header();
		});
	}

	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/global', initStickyHeaderScript);
	});
})(jQuery);
