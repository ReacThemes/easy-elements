<?php
/**
 * Theme Compatibility Class.
 *
 * Handles compatibility between Easy Header Footer plugin
 * and WordPress themes by overriding header and footer templates
 * and injecting custom Elementor-based templates when enabled.
 *
 * @package Easy_Header_Footer
 */
namespace EASY_EHF\Themes;

/**
 * Class EASY_EHF_Theme_Compatibility
 *
 * Provides hooks and compatibility logic for replacing
 * WordPress theme headers and footers with custom Elementor templates.
 */
class EASY_EHF_Theme_Compatibility {

	
	public function __construct() {
		add_action( 'wp', [ $this, 'init_wp_hooks' ] );
	}

	/**
	 * Constructor.
	 *
	 * Initializes the class and hooks into WordPress.
	 */
	public function init_wp_hooks() {
		if ( ee_easy_header_enabled() ) {
			// Replace header.php.
			add_action( 'get_header', [ $this, 'easyop_override_header' ] );

			add_action( 'wp_body_open', [ 'Easy_Header_Footer_Elementor', 'get_header_content' ] );
			add_action( 'hfe_fallback_header', [ 'Easy_Header_Footer_Elementor', 'get_header_content' ] );
		}

		if ( ee_easy_header_enabled() && hfe_is_before_header_enabled() ) {
			add_action( 'easy_header_before', [ 'Easy_Header_Footer_Elementor', 'get_before_header_content' ], 20 );
		}

		if ( ee_hfe_is_before_footer_enabled() ) {
			add_action( 'wp_footer', [ 'Easy_Header_Footer_Elementor', 'get_before_footer_content' ], 20 );
		}

		if ( ee_easy_footer_enabled() ) {
			add_action( 'wp_footer', [ 'Easy_Header_Footer_Elementor', 'get_footer_content' ], 50 );
		}

		if ( ee_easy_header_enabled() || ee_easy_footer_enabled() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'easy_force_fullwidth' ] );
		}
	}

	/**
	 * Force full-width layout for custom headers/footers.
	 *
	 * Injects inline CSS styles to make the custom templates stretch
	 * across the full viewport width and hide the default theme header/footer.
	 *
	 * @return void
	 */
	public function easy_force_fullwidth() {

		$handle = 'easy-ehf-style';

		$version = EASYELEMENTS_VER; 
		
		if ( ! wp_style_is( $handle, 'registered' ) ) {
			wp_register_style( $handle, false, [], $version ); 
		}

		// Enqueue the style so inline CSS attaches properly.
		wp_enqueue_style( $handle );
		$css = '
		.force-stretched-header {
			width: 100vw;
			position: relative;
			margin-left: -50vw;
			left: 50%;
		}';

		if ( true === ee_easy_header_enabled() ) {
			$css .= 'header#masthead {
				display: none;
			}';
		}

		if ( true === ee_easy_footer_enabled() ) {
			$css .= 'footer#colophon {
				display: none;
			}';
		}

		wp_add_inline_style( 'easy-ehf-style', $css );
	}

	/**
	 * Override the default theme header template.
	 *
	 * Loads the default `header.php` file and triggers fallback
	 * header content rendering if `wp_body_open` was not called.
	 *
	 * @return void
	 */
	public function easyop_override_header() {
		$templates   = [];
		$templates[] = 'header.php';
		locate_template( $templates, true );

		if ( ! did_action( 'wp_body_open' ) ) {
			echo '<div class="force-stretched-header">';
			do_action( 'hfe_fallback_header' );
			echo '</div>';
		}
	}
}

new EASY_EHF_Theme_Compatibility();
