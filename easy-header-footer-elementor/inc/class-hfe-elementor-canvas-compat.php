<?php
/**
 * EE_HFE_Elementor_Canvas_Compat setup
 */

/**
 * theme compatibility.
 */
class EE_HFE_Elementor_Canvas_Compat {

	/**
	 * Instance of EE_HFE_Elementor_Canvas_Compat.
	 *
	 * @var EE_HFE_Elementor_Canvas_Compat
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new EE_HFE_Elementor_Canvas_Compat();

			add_action( 'wp', [ self::$instance, 'hooks' ] );
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function hooks() {
		if ( ee_hfe_header_enabled() ) {

			// Action `elementor/page_templates/canvas/before_content` is introduced in Elementor Version 1.4.1.
			if ( version_compare( ELEMENTOR_VERSION, '1.4.1', '>=' ) ) {
				add_action( 'elementor/page_templates/canvas/before_content', [ $this, 'render_header' ] );
			} else {
				add_action( 'wp_head', [ $this, 'render_header' ] );
			}
		}

		if ( ee_hfe_footer_enabled() ) {

			// Action `elementor/page_templates/canvas/after_content` is introduced in Elementor Version 1.9.0.
			if ( version_compare( ELEMENTOR_VERSION, '1.9.0', '>=' ) ) {
				add_action( 'elementor/page_templates/canvas/after_content', [ $this, 'render_footer' ] );
			} else {
				add_action( 'wp_footer', [ $this, 'render_footer' ] );
			}
		}

		if ( ee_hfe_is_before_footer_enabled() ) {

			// check if current page template is Elemenntor Canvas.
			if ( 'elementor_canvas' == get_page_template_slug() ) {
				$override_cannvas_template = get_post_meta( ee_hfe_get_before_footer_id(), 'display-on-canvas-template', true );

				if ( '1' == $override_cannvas_template ) {
					add_action( 'elementor/page_templates/canvas/after_content', 'ee_hfe_render_before_footer', 9 );
				}
			}
		}
	}

	/**
	 * Render the header if display template on elementor canvas is enabled
	 * and current template is Elementor Canvas
	 */
	public function render_header() {

		// bail if current page template is not Elemenntor Canvas.
		if ( 'elementor_canvas' !== get_page_template_slug() ) {
			return;
		}

		$override_cannvas_template = get_post_meta( get_ee_hfe_header_id(), 'display-on-canvas-template', true );

		if ( '1' == $override_cannvas_template ) {
			ee_hfe_render_header();
		}
	}

	/**
	 * Render the footer if display template on elementor canvas is enabled
	 * and current template is Elementor Canvas
	 */
	public function render_footer() {

		// bail if current page template is not Elemenntor Canvas.
		if ( 'elementor_canvas' !== get_page_template_slug() ) {
			return;
		}

		$override_cannvas_template = get_post_meta( get_ee_hfe_footer_id(), 'display-on-canvas-template', true );

		if ( '1' == $override_cannvas_template ) {
			ee_hfe_render_footer();
		}
	}

}

EE_HFE_Elementor_Canvas_Compat::instance();
