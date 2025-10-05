<?php
/**
 * EE_HFE_Default_Compat setup
 */

namespace EASY_EHF\Themes;

/**
 * theme compatibility.
 */
class EE_HFE_Default_Compat {

	/**
	 *  Initiator
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'init_wp_hooks' ] );
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function init_wp_hooks() {
		if ( ee_easy_header_enabled() ) {
			// Replace header.php template.
			add_action( 'get_header', [ $this, 'easy_override_header' ] );

			// Display HFE's header in the replaced header.
			add_action( 'easy_header', 'ee_hfe_render_header' );
		}

		if ( ee_easy_header_enabled() && hfe_is_before_header_enabled() ) {
			add_action( 'easy_header_before', [ 'Easy_Header_Footer_Elementor', 'get_before_header_content' ], 20 );
		}

		if ( ee_easy_footer_enabled() || ee_hfe_is_before_footer_enabled() ) {
			// Replace footer.php template.
			add_action( 'get_footer', [ $this, 'easy_override_footer' ] );
		}

		if ( ee_easy_footer_enabled() ) {
			// Display HFE's footer in the replaced header.
			add_action( 'easy_footer', 'ee_hfe_render_footer' );
		}

		if ( ee_hfe_is_before_footer_enabled() ) {
			add_action( 'easy_footer_before', [ 'Easy_Header_Footer_Elementor', 'get_before_footer_content' ] );
		}
	}

	/**
	 * Function for overriding the header in the elmentor way.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function easy_override_header() {
		require EASYELEMENTS_DIR_PATH . 'header-footer-builder/compat/theme/easy-header.php';
		$templates   = [];
		$templates[] = 'header.php';
		// Avoid running wp_head hooks again.
		remove_all_actions( 'wp_head' );
		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

	/**
	 * Function for overriding the footer in the elmentor way.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function easy_override_footer() {
		require EASYELEMENTS_DIR_PATH . 'header-footer-builder/compat/theme/easy-footer.php';
		$templates   = [];
		$templates[] = 'footer.php';
		// Avoid running wp_footer hooks again.
		remove_all_actions( 'wp_footer' );
		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

}

new EE_HFE_Default_Compat();
