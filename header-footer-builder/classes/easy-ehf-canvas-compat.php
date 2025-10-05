<?php
/**
 * Elementor Canvas Integration
 *
 * Handles custom header/footer output on Elementor Canvas templates.
 */
class EASY_EHF_Canvas_Compat {

	/**
     * Singleton instance.
     *
     * @var EASY_EHF_Canvas_Compat
     */
	private static $instance;

	/**
     * Initiate singleton
     */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new EASY_EHF_Canvas_Compat();

			add_action( 'wp', [ self::$instance, 'init_hooks' ] );
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function init_hooks() {

		// Header
		if ( ee_hfe_header_enabled() ) {

			// Action `elementor/page_templates/canvas/before_content` is introduced in Elementor Version 1.4.1.
			if ( version_compare( ELEMENTOR_VERSION, '1.4.1', '>=' ) ) {
				add_action( 'elementor/page_templates/canvas/before_content', [ $this, 'output_header' ] );
			} else {
				add_action( 'wp_head', [ $this, 'output_header' ] );
			}
		}

		// Footer
		if ( ee_hfe_footer_enabled() ) {

			// Action `elementor/page_templates/canvas/after_content` is introduced in Elementor Version 1.9.0.
			if ( version_compare( ELEMENTOR_VERSION, '1.9.0', '>=' ) ) {
				add_action( 'elementor/page_templates/canvas/after_content', [ $this, 'output_footer' ] );
			} else {
				add_action( 'wp_footer', [ $this, 'output_footer' ] );
			}
		}

		// Optional before-footer element
		if ( ee_hfe_is_before_footer_enabled() ) {

			if ( 'elementor_canvas' == get_page_template_slug() ) {
				$override_cannvas_template = get_post_meta( ee_hfe_get_before_footer_id(), 'display-on-canvas-template', true );
				if ( '1' == $override_cannvas_template ) {
					add_action( 'elementor/page_templates/canvas/after_content', 'ee_hfe_render_before_footer', 9 );
				}
			}
		}
	}

	 /**
     * Output header for Elementor Canvas
     */
	public function output_header() {

		if ( 'elementor_canvas' !== get_page_template_slug() ) {
			return;
		}

		$override_cannvas_template = get_post_meta( get_ee_hfe_header_id(), 'display-on-canvas-template', true );

		if ( '1' == $override_cannvas_template ) {
			ee_hfe_render_header();
		}
	}

	/**
     * Output footer for Elementor Canvas
     */
    public function output_footer() {

		if ( 'elementor_canvas' !== get_page_template_slug() ) {
			return;
		}

		$override_cannvas_template = get_post_meta( get_ee_hfe_footer_id(), 'display-on-canvas-template', true );

		if ( '1' == $override_cannvas_template ) {
			ee_hfe_render_footer();
		}
	}

}

// Instantiate
EASY_EHF_Canvas_Compat::instance();