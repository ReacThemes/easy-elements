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

		function updatePaddingAndMargin(scrollDirection, scroll) {
			var headerHeight = header.outerHeight();
			var topbarHeight = topbar.length ? topbar.outerHeight() : 0;
			var isTopSticky = header.hasClass('eel-fixed-top-sticky');

			// page padding-top
			if (!isFixedHeader) {
				page.css('padding-top', headerHeight + 'px');
			} else {
				page.css('padding-top', '');
			}

			// fixed-top-sticky
			if (isTopSticky) {
				if (scroll <= 0) {
					header.css('margin-top', '0px');
				} else if (scrollDirection === 'down') {
					header.css('margin-top', `-${topbarHeight}px`);
				} else if (scrollDirection === 'up') {
					header.css('margin-top', `-${topbarHeight}px`);
				}
				return;
			}

			//  sticky behavior
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
			let isTopSticky = header.hasClass('eel-fixed-top-sticky');
			let scrollDirection = scroll > lastScroll ? 'down' : 'up';

			if (isTopSticky) {
				header.addClass('eel-sticky-header');
				updatePaddingAndMargin(scrollDirection, scroll);
				lastScroll = scroll;
				return;
			}

			if (scroll > stickyScrollThreshold) {
				header.addClass('eel-sticky-header');
				if (scroll > lastScroll) {
					header.removeClass('eel-up-scroll').addClass('eel-down-scroll');
				} else {
					header.removeClass('eel-down-scroll').addClass('eel-up-scroll');
				}
			} else {
				header.removeClass('eel-sticky-header eel-up-scroll eel-down-scroll');
			}

			updatePaddingAndMargin(scrollDirection, scroll);
			lastScroll = scroll;
		}

		$(document).ready(function() {
			updatePaddingAndMargin('up', 0);
			sticky_header();
		});

		$(window).on('scroll resize', function() {
			sticky_header();
		});
	}

	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/global', initStickyHeaderScript);
	});
})(jQuery);
