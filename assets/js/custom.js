(function($) {
    "use strict";
  	function initBackPostSlider($scope) {
  	    var $slider = $scope.find('.eel-all-slider');
  	    if (!$slider.length) {
  	        return;
  	    }
  	    var loop = $slider.data('loop') === true || $slider.data('loop') === 'true';
  	    var autoplay = $slider.data('autoplay') === true || $slider.data('autoplay') === 'true';
  	    var autoplayDelay = parseInt($slider.data('autoplay-delay'), 10) || 3000;
  	    var slidesPerView = parseInt($slider.data('slides-per-view'), 10) || 3;
  	    var speed = parseInt($slider.data('speed'), 10) || 600;
  	    var centeredSlides = $slider.data('centered-slides') === true || $slider.data('centered-slides') === 'true';
  	    var freeMode = $slider.data('free-mode') === true || $slider.data('free-mode') === 'true';
  	    var spaceBetween = parseInt($slider.data('space-between'), 10) || 0;
  	    var slidesPerViewTablet = parseInt($slider.data('slides-per-view-tablet'), 10) || 2;
  	    var slidesPerViewMobile = parseInt($slider.data('slides-per-view-mobile'), 10) || 1;
  	    	    	    var paginationType = $slider.data('pagination') !== 'none' ? $slider.data('pagination') : false;
	    var navigation = $slider.data('navigation') === true || $slider.data('navigation') === 'true';
	    var effect = $slider.data('effect') || 'slide';
	    var cubeShadow = $slider.data('cube-shadow') === true || $slider.data('cube-shadow') === 'true';
	    var coverflowRotate = parseInt($slider.data('coverflow-rotate'), 10) || 50;
	    var coverflowStretch = parseInt($slider.data('coverflow-stretch'), 10) || 0;
	    
	    // Find navigation elements within the scope
	    var $nextButton = $scope.find('.swiper-next');
	    var $prevButton = $scope.find('.swiper-prev');
	    var $pagination = $scope.find('.swiper-pagination');
	    
	    // Check if progress bar pagination is enabled
	    var hasProgressBar = $pagination.hasClass('swiper-pagination-progressbar');
  	    
  	    // Prepare effect-specific options
  	    var effectOptions = {};
  	    
  	    if (effect === 'cube') {
  	        effectOptions = {
  	            cubeEffect: {
  	                shadow: cubeShadow,
  	                slideShadows: cubeShadow,
  	                shadowOffset: 20,
  	                shadowScale: 0.94,
  	            }
  	        };
  	    } else if (effect === 'coverflow') {
  	        effectOptions = {
  	            coverflowEffect: {
  	                rotate: coverflowRotate,
  	                stretch: coverflowStretch,
  	                depth: 100,
  	                modifier: 1,
  	                slideShadows: true,
  	            }
  	        };
  	    } else if (effect === 'flip') {
  	        effectOptions = {
  	            flipEffect: {
  	                slideShadows: true,
  	                limitRotation: true,
  	            }
  	        };
  	    } else if (effect === 'cards') {
  	        effectOptions = {
  	            cardsEffect: {
  	                slideShadows: true,
  	                rotate: true,
  	                perSlideOffset: 8,
  	            }
  	        };
  	    } else if (effect === 'creative') {
  	        effectOptions = {
  	            creativeEffect: {
  	                prev: {
  	                    shadow: true,
  	                    translate: [0, 0, -400],
  	                },
  	                next: {
  	                    translate: ['100%', 0, 0],
  	                },
  	            }
  	        };
  	    }
  	    
  	    new Swiper($slider[0], {
  	        loop: loop,
  	        speed: speed,
  	        effect: effect,
  	        simulateTouch: true,    
  	        touchRatio: 1, 
  	        grabCursor: true,
  	        mousewheel: hasProgressBar ? {
	            enabled: true,
	            sensitivity: 1,
	            thresholdDelta: 50,
	            thresholdTime: 200,
	            releaseOnEdges: true,
	            invert: false,
	            forceToAxis: false,
	            eventsTarget: 'container'
	        } : false,
  	        autoplay: autoplay ? {
  	            delay: autoplayDelay,
  	            disableOnInteraction: false,
  	        } : false,
  	        slidesPerView: slidesPerView,
  	        spaceBetween: spaceBetween,
  	        centeredSlides: centeredSlides,
  	        freeMode: freeMode,            
  	        pagination: paginationType && $pagination.length ? {
				el: $pagination[0],
				clickable: true,
				type: paginationType,
				renderBullet: function (index, className) {
					let num = (index + 1).toString().padStart(2, '0');
					return `<span class="${className}">${num}</span>`;
				}
			} : false,
			
        // Progress bar for mouse scroll - only when swiper-pagination-progressbar class is present
        progressbar: hasProgressBar ? {
            el: '.swiper-progressbar',
            type: 'progressbar',
        } : false,			
			
  	        navigation: navigation && ($nextButton.length || $prevButton.length) ? {
  	            nextEl: $nextButton.length ? $nextButton[0] : null,
  	            prevEl: $prevButton.length ? $prevButton[0] : null,
  	        } : false,
  	        breakpoints: {
  	            0: {
  	                slidesPerView: slidesPerViewMobile,
  	            },
  	            768: {
  	                slidesPerView: slidesPerViewTablet,
  	            },
  	            1024: {
  	                slidesPerView: slidesPerView,
  	            },
  	        },
  	        ...effectOptions,
  	    });
  	}

    function addWidgetTypeToBody($scope) {
        var widgetType = $scope.data('widget_type');
        if (widgetType) {
            var className = 'easy-eel-has-widget-' + widgetType.replace(/[^a-z0-9_-]/gi, '-').toLowerCase();
            document.body.classList.add(className);
        }
    }

	$(window).on('elementor/frontend/init', function () {

        function checkStickySection() {
            const stickySection = document.querySelector('.eel-sticky-section');
            if (stickySection) {
                document.body.classList.add('sticky-enabled-overlap');
            } else {
                document.body.classList.remove('sticky-enabled-overlap');
            }
        }

        // Run on page load
        checkStickySection();

        // Optional: Run on DOM changes (Elementor editor may load sections dynamically)
        const observer = new MutationObserver(checkStickySection);
        observer.observe(document.body, { childList: true, subtree: true });
    });

	 //  image reveal animatioln 
	function initReveal() {
		const revealElements = document.querySelectorAll(".eel-img-reveal-wrap");

		const revealAnimate = (el) => {
			const target = el.querySelector(".eel-reveal-img-main");
			if(target) target.classList.add("el-reveal-animate");
		};

		// Elementor editor detect
		const isEditor = window.elementorFrontend && elementorFrontend.isEditMode && elementorFrontend.isEditMode();

		if(isEditor){
			// Editor preview: immediate trigger
			revealElements.forEach(el => revealAnimate(el));
		} else {
			// Frontend: scroll-triggered
			const observer = new IntersectionObserver((entries, obs) => {
					entries.forEach(entry => {
						if(entry.isIntersecting){
							revealAnimate(entry.target);
							obs.unobserve(entry.target);
						}
					});
			}, { threshold: 0.3 });

			revealElements.forEach(el => observer.observe(el));
		}
	}

   $(window).on('elementor/frontend/init', function () {
      elementorFrontend.hooks.addAction('frontend/element_ready/global', initBackPostSlider);
      elementorFrontend.hooks.addAction('frontend/element_ready/global', addWidgetTypeToBody);
		elementorFrontend.hooks.addAction('frontend/element_ready/global', initReveal);
   });
	
})(jQuery);