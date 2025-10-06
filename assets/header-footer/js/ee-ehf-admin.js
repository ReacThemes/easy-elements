;( function( $ ) {

	'use strict';

	// Global settings access.
	var settings = {
		iconActivate: '<i class="fa fa-toggle-on fa-flip-horizontal" aria-hidden="true"></i>',
		iconDeactivate: '<i class="fa fa-toggle-on" aria-hidden="true"></i>',
		iconInstall: '<i class="fa fa-cloud-download" aria-hidden="true"></i>'
	};

	var EE_HFEAdmin = {

		/**
		 * Start the engine.
		 *
		 * @since 1.3.9
		 */
		_init: function() {

			var ehf_hide_shortcode_field = function() {
				var selected = $('#ehf_template_type').val() || 'none';
				$( '.easy-options-table-ehf' ).removeClass().addClass( 'easy-options-table-ehf widefat easy-selected-template-type-' + selected );
			}

			var $document = $( document );
		
			$document.on( 'change', '#ehf_template_type', function( e ) {
				ehf_hide_shortcode_field();
			});
		
			ehf_hide_shortcode_field();
		
			// Templates page modal popup.
			EE_HFEAdmin._display_modal();

			$( '.easy-subscribe-field' ).on( 'keyup', function( e ) {
				$( '.easy-subscribe-message' ).remove();
			});

			$document.on( 'focusout change', '.easy-subscribe-field', EE_HFEAdmin._validate_single_field );
			$document.on( 'click input', '.easy-subscribe-field', EE_HFEAdmin._animate_fields );

			$document.on( 'click', '.easy-guide-content .submit-1', EE_HFEAdmin._step_one_subscribe );
			$document.on( 'click', '.easy-guide-content .submit-2', EE_HFEAdmin._step_two_subscribe );

			$document.on('click', '.easy-guide-content .button-subscription-skip', EE_HFEAdmin._close_modal );

			// About us - addons functionality.
			if ( $( '.hfe-admin-addons' ).length ) {
	
				$document.on( 'click', '.hfe-admin-addons .addon-item button', function( event ) {
					event.preventDefault();
		
					if ( $( this ).hasClass( 'disabled' ) ) {
						return false;
					}
		
					EE_HFEAdmin._addons( $( this ) );

				} );
		
			}
		},

		_animate_fields: function ( event ) {
			event.preventDefault();
			event.stopPropagation();
			var parentWrapper = $( this ).parents( '.easy-input-container' );
			parentWrapper.addClass( 'subscription-anim' );
		},

		_validate_single_field: function ( event ) {
			event.preventDefault();
			event.stopPropagation();
			EE_HFEAdmin._validate_field( event.target );
		},

		_validate_field: function ( target ) {

			var field = $( target );
			var fieldValue = field.val() || '';
			var parentWrapper = field.parents( '.easy-input-container' );
			var fieldStatus = fieldValue.length ? true : false;

			if ( ( field.hasClass( 'easy-subscribe-email' ) && false === EE_HFEAdmin._is_valid_email( fieldValue ) )) {
				fieldStatus = false;
			}

			if ( fieldStatus ) {
				parentWrapper.removeClass( 'subscription-error' ).addClass( 'subscription-success' );
			} else {
				parentWrapper.removeClass( 'subscription-success subscription-anim' ).addClass( 'subscription-error' );

				if ( field.hasClass( 'easy-subscribe-email' ) && fieldValue.length ) {
					parentWrapper.addClass( 'subscription-anim' );
				}
			}

		},

		/**
		 * Subscribe Form Step One
		 *
		 */
		_step_one_subscribe: function( event ) {
			event.preventDefault();
			event.stopPropagation();

			var form_one_wrapper = $( '.hfe-subscription-step-1' );

			var first_name_field = form_one_wrapper.find( '.easy-subscribe-field[name="hfe_subscribe_name"]' );
			var email_field = form_one_wrapper.find( '.easy-subscribe-field[name="hfe_subscribe_email"]' );

			EE_HFEAdmin._validate_field( first_name_field );
			EE_HFEAdmin._validate_field( email_field );

			if ( form_one_wrapper.find( '.easy-input-container' ).hasClass( 'subscription-error' )) {
				return;
			}

			$( '.easy-guide-content' ).addClass( 'hfe-subscription-step-2-active' ).removeClass( 'hfe-subscription-step-1-active' );

		},

		/**
		 * Subscribe Form
		 *
		 */
		 _step_two_subscribe: function( event ) {

			event.preventDefault();
			event.stopPropagation();

			var submit_button = $(this);

			var is_modal = $( '.hfe-guide-modal-popup.easy-show' );

			var first_name_field = $('.easy-subscribe-field[name="hfe_subscribe_name"]');
			var email_field = $('.easy-subscribe-field[name="hfe_subscribe_email"]');
			var user_type_field = $('.easy-subscribe-field[name="wp_user_type"]');
			var build_for_field = $('.easy-subscribe-field[name="build_website_for"]');
			var accept_field = $('.hfe_subscribe_accept[name="hfe_subscribe_accept"]');

			var subscription_first_name = first_name_field.val() || '';
			var subscription_email = email_field.val() || '';
			var subscription_user_type = user_type_field.val() || '';
			var subscription_build_for = build_for_field.val() || '';
			var button_text = submit_button.find( '.hfe-submit-button-text' );
			var subscription_accept = accept_field.is( ':checked' ) ? '1' : '0';

			EE_HFEAdmin._validate_field( first_name_field );
			EE_HFEAdmin._validate_field( email_field );
			EE_HFEAdmin._validate_field( user_type_field );
			EE_HFEAdmin._validate_field( build_for_field );

			$( '.easy-subscribe-message' ).remove();

			if ( $( '.easy-input-container' ).hasClass( 'subscription-error' )) {
				return;
			}

			submit_button.removeClass( 'submitted' );

			if( ! submit_button.hasClass( 'submitting' ) ) {
				submit_button.addClass( 'submitting' );
			} else {
				return;
			}

			var subscription_fields = {
				EMAIL: subscription_email,
				FIRSTNAME: subscription_first_name,
				PAGE_BUILDER: "1",
				WP_USER_TYPE: subscription_user_type,
				BUILD_WEBSITE_FOR: subscription_build_for,
				OPT_IN: subscription_accept,
				SOURCE: EE_HFE_Admin_data.data_source
			};

			$.ajax({
				url  : EE_HFE_Admin_data.ajax_url,
				type : 'POST',
				data : {
					action : 'hfe-update-subscription',
					nonce : EE_HFE_Admin_data.nonce,
					data: JSON.stringify( subscription_fields ),
				},
				beforeSend: function() {
					console.groupCollapsed( 'Email Subscription' );

					button_text.append( '<span class="dashicons dashicons-update easy-loader"></span>' );

				},
			})
			.done( function ( response ) {

				$( '.easy-loader.dashicons-update' ).remove();

				submit_button.removeClass( 'submitting' ).addClass('submitted');

				if( response.success === true ) {
					$('.hfe-admin-about-section form').trigger( "reset" );
					$( '.easy-input-container' ).removeClass( 'subscription-success subscription-anim' );

					submit_button.after( '<span class="easy-subscribe-message success">' + EE_HFE_Admin_data.subscribe_success + '</span>' );
				} else {
					submit_button.after( '<span class="easy-subscribe-message error">' + EE_HFE_Admin_data.subscribe_error + '</span>' );
				}
				
				if( is_modal.length ) {
					window.setTimeout( function () {
						window.location = $( '.hfe-guide-modal-popup' ).data( 'new-page' );
					}, 3000 );
				}

			});

		},

		/**
		 * email Validation
		 *
		 */
		_is_valid_email: function(eMail) {
			if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test( eMail ) ) {
				return true;
			}
			
			return false;
		},

		/**
		 * Display the Modal Popup
		 *
		 */
		_display_modal: function() {
			var hf_new_post = $( '.post-type-ee-elementor-hf' ).find( '.page-title-action' );

			var modal_wrapper = $( '.hfe-guide-modal-popup' );
			var display_allow = EE_HFE_Admin_data.popup_dismiss;

			if( 'dismissed' !== display_allow[0] ) {
				// Display Modal Popup on click of Add new button.
				hf_new_post.on( 'click', function(e) {
					if( modal_wrapper.length && ! modal_wrapper.hasClass( 'easy-show' ) ) {
						e.preventDefault();
						e.stopPropagation();
						modal_wrapper.addClass( 'easy-show' );
					}
				});
			}
		},

		/**
		 * Close the Modal Popup
		 *
		 */
		 _close_modal: function() {
			var modal_wrapper = $( '.hfe-guide-modal-popup' );
			var new_page_link = modal_wrapper.data( 'new-page' );
				
			$.ajax({
				url: EE_HFE_Admin_data.ajax_url,
				type: 'POST',
				data: {
					action  : 'EE_HFE_Admin_modal',
					nonce   : EE_HFE_Admin_data.nonce,
				},
			});
		
			if( modal_wrapper.hasClass( 'easy-show' ) ) {
				modal_wrapper.removeClass( 'easy-show' );
			}

			window.location = new_page_link;
		},

		/**
		 * Toggle addon state.
		 */
		 _addons: function( $button ) {

			var $addon = $button.closest( '.addon-item' ),
				plugin = $button.attr( 'data-plugin' ),
				addonType = $button.attr( 'data-type' ),
				addonSlug = $button.attr( 'data-slug' ),
				addonFile = $button.attr( 'data-file' ),
				state,
				cssClass,
				stateText,
				buttonText,
				errorText,
				successText;
	
			if ( $button.hasClass( 'status-go-to-url' ) ) {
	
				// Open url in new tab.
				window.open( $button.attr( 'data-site' ), '_blank' );
				return;
			}
	
			$button.prop( 'disabled', true ).addClass( 'loading' );
			$button.html( '<span class="dashicons dashicons-update easy-loader"></span>' );
	
			if ( $button.hasClass( 'status-active' ) ) {
	
				// Deactivate.
				state = 'deactivate';
				cssClass = 'status-inactive';
				cssClass += ' button button-secondary';
				stateText = EE_HFE_Admin_data.addon_inactive;
				buttonText = EE_HFE_Admin_data.addon_activate;
				errorText  = EE_HFE_Admin_data.addon_deactivate;
	
			} else if ( $button.hasClass( 'status-inactive' ) ) {
	
				// Activate.
				state = 'activate';
				cssClass = 'status-active';
				cssClass += ' button button-secondary disabled';
				stateText = EE_HFE_Admin_data.addon_active;
				buttonText = EE_HFE_Admin_data.addon_deactivate;
				buttonText = EE_HFE_Admin_data.addon_activated;
				errorText  = EE_HFE_Admin_data.addon_activate;
	
			} else if ( $button.hasClass( 'status-download' ) ) {
	
				// Install & Activate.
				state = 'install';
				cssClass = 'status-active';
				cssClass += ' button disabled';
				stateText = EE_HFE_Admin_data.addon_active;
				buttonText = EE_HFE_Admin_data.addon_activated;
				errorText  = settings.iconInstall;
	
			} else {
				return;
			}
	
			EE_HFEAdmin._set_addon_state( plugin, state, addonType, addonSlug, function( res ) {
	
				if ( res.success ) {
					if ( 'install' === state ) {
						successText = res.msg;
						$button.attr( 'data-plugin', addonFile );
						
						stateText  = EE_HFE_Admin_data.addon_inactive;
						buttonText = ( addonType === 'theme' || addonType === 'plugin' ) ? EE_HFE_Admin_data.addon_activate : settings.iconActivate + EE_HFE_Admin_data.addon_activate;
						cssClass   = ( addonType === 'theme' || addonType === 'plugin' ) ? 'status-inactive button button-secondary' : 'status-inactive';
					} else {
						successText = res.data;
					}
					$addon.find( '.actions' ).append( '<div class="msg success">' + successText + '</div>' );
					$addon.find( 'span.status-label' )
						.removeClass( 'status-active status-inactive status-download' )
						.addClass( cssClass )
						.removeClass( 'button button-primary button-secondary disabled' )
						.text( stateText );
					$button
						.removeClass( 'status-active status-inactive status-download' )
						.removeClass( 'button button-primary button-secondary disabled' )
						.addClass( cssClass ).html( buttonText );
				} else {
					
					if ( 'install' === state && ( addonType === 'theme' || addonType === 'plugin' ) ) {
						$addon.find( '.actions' ).append( '<div class="msg error">' + res.msg + '</div>' );
						$button.addClass( 'status-go-to-url' ).removeClass( 'status-download' );
					} else {
						var error_msg = ( 'object' === typeof res.data ) ? EE_HFE_Admin_data.plugin_error : res.data;
						$addon.find( '.actions' ).append( '<div class="msg error">' + error_msg + '</div>' );
					}

					if( 'ultimate-elementor' === addonSlug ) {
						$button.addClass( 'status-go-to-url' );
						$button.html( EE_HFE_Admin_data.visit_site );
					} else {
						$button.html( EE_HFE_Admin_data.addon_download );
					}
				}
	
				$button.prop( 'disabled', false ).removeClass( 'loading' );
	
				// Automatically clear the messages after 3 seconds.
				setTimeout( function() {	
					$( '.addon-item .msg' ).remove();
				}, 3000 );
	
			} );
		},

		/**
		 * Change plugin/addon state.
		 *
		 * @since 1.6.0
		 *
		 * @param {string}   plugin     Plugin/Theme URL for download.
		 * @param {string}   state      State status activate|deactivate|install.
		 * @param {string}   addonType Plugin/Theme type addon or plugin.
		 * @param {string}   addonSlug Plugin/Theme slug addon or plugin.
		 * @param {Function} callback   Callback for get result from AJAX.
		 */
		 _set_addon_state: function( plugin, state, addonType, addonSlug, callback ) {

			var actions = {
					'activate': 'hfe_activate_addon',
					'install': '',
				},
				action = actions[ state ];

			if ( ! action && 'install' !== state ) {
				return;
			}

			var data_result = {
				success : false,
				msg : EE_HFE_Admin_data.subscribe_error,
			};

			if( 'install' === state ) {

				if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
					wp.updates.requestFilesystemCredentials();
				}

				if( 'theme' === addonType ) {

					wp.updates.installTheme ( {
						slug: addonSlug,
						success: function() {
							data_result.success = true;
							data_result.msg = EE_HFE_Admin_data.theme_installed;
							
						},
						error: function( xhr ) {
							console.log( xhr.errorCode );							
							if ( 'folder_exists' === xhr.errorCode ) {
								data_result.success = true;
								data_result.msg = EE_HFE_Admin_data.addon_exists;
							} else {
								data_result.success = false;
								data_result.msg = EE_HFE_Admin_data.plugin_error;
							}
						},
					}).always( function () {
						callback( data_result );
					});

				} else if( 'plugin' === addonType ) {
					
					wp.updates.installPlugin ( {
						slug: addonSlug,
						success: function() {
							data_result.success = true;
							data_result.msg = EE_HFE_Admin_data.plugin_installed;
						},
						error: function( xhr ) {
							console.log( xhr.errorCode );							
							if ( 'folder_exists' === xhr.errorCode ) {
								data_result.success = true;
								data_result.msg = EE_HFE_Admin_data.addon_exists;
							} else {
								data_result.success = false;
								data_result.msg = EE_HFE_Admin_data.plugin_error;
							}
						},
					}).always( function () {
						callback( data_result );
					});
				}

			} else if( 'activate' === state )  {

				var data = {
					action: action,
					nonce: EE_HFE_Admin_data.nonce,
					plugin: plugin,
					type: addonType,
					slug: addonSlug
				};
		
				$.post( EE_HFE_Admin_data.ajax_url, data, function( res ) {
					callback( res );
				} ).fail( function( xhr ) {
					console.log( xhr.responseText );
				} );
			}
		}
	};

	$( document ).ready( function( e ) {
		EE_HFEAdmin._init();
	});

	window.EE_HFEAdmin = EE_HFEAdmin;

} )( jQuery );
