( function( $ ) {

	var WidgeteelNavMenuHandler = function( $scope, $ ) {

		if ( 'undefined' == typeof $scope )
			return;
		
		var id = $scope.data( 'id' );
		var wrapper = $scope.find('.elementor-widget-eel-navigation-menu');		
		var layout = $( '.elementor-element-' + id + ' .eel-nav-menu' ).data( 'layout' );
		var flyout_data = $( '.elementor-element-' + id + ' .eel-flyout-wrapper' ).data( 'flyout-class' );
		var last_item = $( '.elementor-element-' + id + ' .eel-nav-menu' ).data( 'last-item' );
		var last_item_flyout = $( '.elementor-element-' + id + ' .eel-flyout-wrapper' ).data( 'last-item' );

		var menu_items_links        = $( '.elementor-element-' + id + ' .eel-nav-menu nav li a' );
		var menu_items_links_flyout = $( '.elementor-element-' + id + ' .eel-flyout-wrapper li a' );
		if (menu_items_links.length > 0) {
			_handle_current_menu_item_class( menu_items_links );
		}

		if (menu_items_links_flyout.length > 0) {
			_handle_current_menu_item_class( menu_items_links_flyout );
		}

		$( 'div.eel-has-submenu-container' ).removeClass( 'sub-menu-active' );

		_toggleClick( id );

		_handlePolylangSwitcher( $scope );

		// Update eel-mobile class based on active media queries
		_updateMobileClass( id, layout );

		// Ensure chevron icons exist on parent items and bind submenu toggle (scoped per widget)
		var $root = $( '.elementor-element-' + id + ' ' );
		$root.find('.eel-nav-menu li.menu-item-has-children > a, .eel-nav-menu li.easyel-mega--current-active > a').each(function(){
			if ( $(this).find('i.unicon-chevron-down').length === 0 ) {
				$(this).append('<i class="unicon-chevron-down" aria-expanded="false"></i>');
			}
		});

		$root.find('.eel-nav-menu').off('click.eelChevron').on('click.eelChevron', '.unicon-chevron-down', function(e){
			e.preventDefault();
			e.stopPropagation();

			var $parentLi = $(this).closest('li.menu-item-has-children');
			var $submenu  = $parentLi.children('ul.sub-menu');

			if ($submenu.is(':visible')) {
				$submenu.slideUp(300);
				$parentLi.removeClass('submenu-open');
				$(this).removeClass('active').attr('aria-expanded', 'false');
			} else {
				$parentLi.siblings('li.menu-item-has-children')
					.children('ul.sub-menu').slideUp(300);
				$parentLi.siblings('li.menu-item-has-children')
					.removeClass('submenu-open')
					.find('> a > .unicon-chevron-down').removeClass('active').attr('aria-expanded', 'false');

				$submenu.slideDown(300);
				$parentLi.addClass('submenu-open');
				$(this).addClass('active').attr('aria-expanded', 'true');
			}
		});

		$( '.elementor-element-' + id + ' .eel-flyout-trigger .eel-nav-menu-icon' ).off( 'click keyup' ).on( 'click keyup', function() {
			console.log('Flyout trigger clicked for ID:', id);
			console.log('Event type:', event.type);
			_openMenu( id );
		} );

		$( '.elementor-element-' + id + ' .eel-flyout-close' ).off( 'click keyup' ).on( 'click keyup', function() {

			_closeMenu( id );
		} );

		$( '.elementor-element-' + id + ' .eel-flyout-overlay' ).off( 'click' ).on( 'click', function() {

			_closeMenu( id );
		} );	

		$scope.find( '.sub-menu' ).each( function() {

			var parent = $( this ).closest( '.menu-item' );

			$scope.find( parent ).addClass( 'parent-has-child' );
			$scope.find( parent ).removeClass( 'parent-has-no-child' );
		});

		if( ( 'cta' == last_item || 'cta' == last_item_flyout ) && 'expandible' != layout ){
			$( '.elementor-element-' + id + ' li.menu-item:last-child a.eel-menu-item' ).parent().addClass( 'eel-button-wrapper elementor-widget-button' );
			$( '.elementor-element-' + id + ' li.menu-item:last-child a.eel-menu-item' ).addClass( 'elementor-button' );			
		}

		_borderClass( id );	

		$( window ).on( 'resize', function(){ 
			// Keep eel-mobile in sync on resize
			_updateMobileClass( id, layout );

			if( 'horizontal' == layout && window.matchMedia( "( min-width: 977px )" ).matches){

				$( '.elementor-element-' + id + ' div.eel-has-submenu-container' ).next().css( 'position', 'absolute');	
			}

			if( 'expandible' == layout || 'flyout' == layout ){

				_toggleClick( id );
			}else if ( 'vertical' == layout || 'horizontal' == layout ) {
				if( window.matchMedia( "( max-width: 767px )" ).matches && ($( '.elementor-element-' + id ).hasClass('eel-nav-menu__breakpoint-tablet') || $( '.elementor-element-' + id ).hasClass('eel-nav-menu__breakpoint-mobile'))){

					_toggleClick( id );					
				}else if ( window.matchMedia( "( max-width: 1024px )" ).matches && $( '.elementor-element-' + id ).hasClass('eel-nav-menu__breakpoint-tablet') ) {
					
					_toggleClick( id );
				}else{
					// var $toggle = $( '.elementor-element-' + id + ' .eel-nav-menu__toggle' );
                    // var $nextElement= $toggle.next();
                    // var width = $nextElement.parent().width();
                    // if( $nextElement.length ){
					// 	$nextElement.css( 'width', width + 'px' );
					// 	$nextElement.css( 'left', '0' );
					// }
				}
			}
		});
	};

	function _handle_current_menu_item_class( layout_links ) {
		layout_links.each(function() {
			if ($(this).hasClass('current-menu-item') || $(this).hasClass('current_page_item')) {
				$(this).addClass('eel-current-menu-item');
			}
		});
	}

	function _openMenu( id ) {
		console.log('=== Opening flyout menu for ID:', id, '===');
		var $wrapper = $( '.elementor-element-' + id + ' .eel-flyout-wrapper' );
		var $overlay = $( '.elementor-element-' + id + ' .eel-flyout-overlay' );
		var $side = $( '.elementor-element-' + id + ' .eel-side' );
		
		console.log('Wrapper found:', $wrapper.length);
		console.log('Overlay found:', $overlay.length);
		console.log('Side found:', $side.length);
		
		if ($wrapper.length) {
			console.log('Wrapper classes before:', $wrapper.attr('class'));
			console.log('Wrapper data-flyout-type:', $wrapper.attr('data-flyout-type'));
		}
		
		if ($side.length) {
			console.log('Side classes before:', $side.attr('class'));
			console.log('Side computed transform:', $side.css('transform'));
			console.log('Side computed left:', $side.css('left'));
			console.log('Side computed right:', $side.css('right'));
		}
		
		$wrapper.addClass( 'eel-flyout-active' );
		$overlay.addClass( 'eel-flyout-overlay-active' );
		
		// Force slide effect for testing
		if ($side.length) {
			var flyoutType = $wrapper.attr('data-flyout-type');
			console.log('Flyout type detected:', flyoutType);
			
			if (flyoutType === 'normal' || !flyoutType) {
				console.log('Applying slide effect (transform)');
				$side.css({
					'transform': 'translateX(0)',
					'transition': 'transform 0.3s ease-in-out'
				});
			} else if (flyoutType === 'push') {
				console.log('Applying push effect (position)');
				var direction = $side.attr('class').match(/eel-flyout-(left|right|top|bottom)/);
				if (direction && direction[1]) {
					console.log('Direction detected:', direction[1]);
					if (direction[1] === 'left') {
						$side.css('left', '0');
					} else if (direction[1] === 'right') {
						$side.css('right', '0');
					} else if (direction[1] === 'top') {
						$side.css('top', '0');
					} else if (direction[1] === 'bottom') {
						$side.css('bottom', '0');
					}
				}
			}
		}
		
		console.log('Classes added. Wrapper classes after:', $wrapper.attr('class'));
		console.log('Overlay classes after:', $overlay.attr('class'));
		
		// Check after a short delay to see if transforms are applied
		setTimeout(function() {
			if ($side.length) {
				console.log('Side classes after delay:', $side.attr('class'));
				console.log('Side computed transform after delay:', $side.css('transform'));
				console.log('Side computed left after delay:', $side.css('left'));
				console.log('Side computed right after delay:', $side.css('right'));
				console.log('Side computed display after delay:', $side.css('display'));
				console.log('Side computed visibility after delay:', $side.css('visibility'));
			}
		}, 100);
	}

	function _closeMenu( id ) {
		console.log('Closing flyout menu for ID:', id);
		$( '.elementor-element-' + id + ' .eel-flyout-wrapper' ).removeClass( 'eel-flyout-active' );
		$( '.elementor-element-' + id + ' .eel-flyout-overlay' ).removeClass( 'eel-flyout-overlay-active' );
	}



	function _borderClass( id ){
		// Placeholder for border class logic if needed
	}

	function _updateMobileClass( id, layout ){
		var $root = $( '.elementor-element-' + id );
		if ( !$root.length ){
			return;
		}
		var isMobileBreakpoint = window.matchMedia( "( max-width: 767px )" ).matches || window.matchMedia( "( max-width: 1024px )" ).matches;
		var shouldBeMobile = ( 'horizontal' === layout ) && isMobileBreakpoint;
		$root.toggleClass( 'eel-mobile', shouldBeMobile );
		// Also toggle on the UL element as requested
		var $ul = $( '.elementor-element-' + id + ' ul.eel-nav-menu' );
		if ( $ul.length ) {
			$ul.toggleClass( 'eel-mobile', shouldBeMobile );
		}
	}

	function _toggleClick( id ){

		var $toggleElement = $('.elementor-element-' + id + ' .eel-nav-menu__toggle');

		if ( $toggleElement.hasClass( 'eel-active-menu-full-width' ) ){

			var $toggle = $( '.elementor-element-' + id + ' .eel-nav-menu__toggle' );
			var $nextElement= $toggle.next();

			if( $nextElement.length ){
				$nextElement.css( 'left', '0' );
				
				var $section = $( '.elementor-element-' + id ).closest('.elementor-section, .e-con-boxed.e-parent, .e-con-full.e-parent');
				if ( $section.length ) {
					var width = $section.outerWidth();
					var sec_pos = $section.offset().left - $toggle.next().offset().left;
					$nextElement.css( 'width', width + 'px' );
					$nextElement.css( 'left', sec_pos + 'px' );
				}
			}
		}

		$( '.elementor-element-' + id + ' .eel-nav-menu__toggle' ).off( 'click keyup' ).on( 'click keyup', function( event ) {
			
			var $this = $( this );
			var $selector = $this.next();
			var $menuContainer = $('.elementor-element-' + id + ' .eel-nav-menu');

			if ( $this.hasClass( 'eel-active-menu' ) ) {

				var layout = $( '.elementor-element-' + id + ' .eel-nav-menu' ).data( 'layout' );
				var full_width = $selector.data( 'full-width' );
				var toggle_icon = $( '.elementor-element-' + id + ' nav' ).data( 'toggle-icon' );

				$( '.elementor-element-' + id).find( '.eel-nav-menu-icon' ).html( toggle_icon );

				$this.removeClass( 'eel-active-menu' );
				$menuContainer.removeClass('eel-active-menu');
				$this.attr( 'aria-expanded', 'false' );
				
				if ( 'yes' == full_width ){
					$this.removeClass( 'eel-active-menu-full-width' );					
					$selector.css( 'width', 'auto' );
					$selector.css( 'left', '0' );
					$selector.css( 'z-index', '0' );
				}				
			} else {

				var layout = $( '.elementor-element-' + id + ' .eel-nav-menu' ).data( 'layout' );
				var full_width = $selector.data( 'full-width' );
				var close_icon = $( '.elementor-element-' + id + ' nav' ).data( 'close-icon' );

				$( '.elementor-element-' + id).find( '.eel-nav-menu-icon' ).html( close_icon );
				
				$this.addClass( 'eel-active-menu' );
				$menuContainer.addClass('eel-active-menu');
				$this.attr( 'aria-expanded', 'true' );

				if ( 'yes' == full_width ){

					$this.addClass( 'eel-active-menu-full-width' );

					var closestElement = $( '.elementor-element-' + id ).closest('.elementor-section, .e-con-boxed.e-parent, .e-con-full.e-parent');
					var width = closestElement.outerWidth();
					var sec_pos = closestElement.offset().left - $selector.offset().left;
					
					$selector.css( 'width', width + 'px' );
					$selector.css( 'left', sec_pos + 'px' );
					$selector.css( 'z-index', '9999' );
				}
			}

			if( $( '.elementor-element-' + id + ' nav' ).hasClass( 'menu-is-active' ) ) {

				$( '.elementor-element-' + id + ' nav' ).removeClass( 'menu-is-active' );
			}else {

				$( '.elementor-element-' + id + ' nav' ).addClass( 'menu-is-active' );
			}			
		} );
	}

	function _handlePolylangSwitcher( $scope ) {
		var polylangSwitcher = $scope.find( '.eel-nav-menu nav .pll-parent-menu-item a.eel-menu-item' );
		var hrefProperty     = polylangSwitcher.prop( 'href' );
		if ( undefined !== hrefProperty && hrefProperty.includes( '#' ) ) {
			var index = hrefProperty.indexOf( '#' );
			var value = hrefProperty.slice( index );
			if ( value === '#pll_switcher' ) {
				polylangSwitcher.prop( 'href', '#' );
			}
		}
	} 

	// Mega menu
	if ( $('.easyel--elementor-template-mega-menu').hasClass('easyel-mega--current') ) {
        $('.easyel--elementor-template-mega-menu').parent().addClass("easyel-mega--current-active menu-item-has-children");
    }

	// Mobile Menu Sidebar
	if ($('.sidebar-on-mobile').length) {
		// Add icon inside each .sub-arrow dynamically
		$('.sidebar-on-mobile ul > .menu-item-has-children a').each(function(){	
			var $a = $(this);
			var href = $a.attr('href');

			if (href === '#' || href === '' || href === 'javascript:void(0)') {
				$a.addClass('easyel-hash');
			}

			$a.append('<i class="unicon-chevron-down"></i>');
		});	
		
		// Sidebar toggle
		$('.eel-nav-menu-icon').on('click', function(e){
			e.stopPropagation();
			$('.sidebar-on-mobile').toggleClass('easyel-open');
			$('body').toggleClass('sidebar-on-mobile-active');
		});

		// Submenu toggle on click of .sub-arrow OR .easyel-hash link
		$('.sidebar-on-mobile').on('click', '.unicon-chevron-down, a.easyel-hash', function(e){
			e.preventDefault();
			e.stopPropagation();

			var $parentLi = $(this).closest('li.menu-item-has-children');
			var $submenu = $parentLi.children('ul.sub-menu');

			// Toggle current submenu
			$submenu.stop(true, true).slideToggle(300);

			// Close other submenus at same level
			$parentLi.siblings('li.menu-item-has-children').children('ul.sub-menu').slideUp(300);

			// Toggle aria-expanded
			var expanded = $(this).attr('aria-expanded') === 'true';
			$(this).attr('aria-expanded', !expanded);

			// Toggle active class for icon rotation
			$(this).toggleClass('active');
		});

		// Click outside to close sidebar
		$(document).on('click', function(){
			$('.sidebar-on-mobile').removeClass('easyel-open');
			$('body').removeClass('sidebar-on-mobile-active');
			$('.sidebar-on-mobile ul.sub-menu').slideUp(300);
			$('.sidebar-on-mobile .sub-arrow').removeClass('active').attr('aria-expanded', false);
		});

		$('.sidebar-on-mobile, .eel-nav-menu').on('click', 'li:not(.menu-item-has-children) a', function(e){
			$('.sidebar-on-mobile li a, .eel-nav-menu li a').removeClass('eel-active');
			$(this).addClass('eel-active');
			$('.sidebar-on-mobile').removeClass('easyel-open');
			$('body').removeClass('sidebar-on-mobile-active');
		});

		// Prevent closing when clicking inside sidebar
		$('.sidebar-on-mobile').on('click', function(e){
			e.stopPropagation();
		});
	}
	
	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/eel-navigation-menu.default', WidgeteelNavMenuHandler );
	});

} )( jQuery ); 
