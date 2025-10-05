<?php
use EASY_EHF\Lib\RSHF_Target_Rules_Fields;

class Easy_Header_Footer_Elementor {
	/**
	 * Current theme template
	 *
	 * @var String
	 */
	public $template;

	/**
	 * Instance of Elemenntor Frontend class.
	 */
	private static $elementor_instance;

	/**
	 * Instance of EE_HFE_Admin
	 *
	 * @var Easy_Header_Footer_Elementor
	 */
	private static $instance = null;

	/**
	 * Instance of Easy_Header_Footer_Elementor
	 *
	 * @return Easy_Header_Footer_Elementor Instance of Easy_Header_Footer_Elementor
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	function __construct() {

		$this->template = get_template();

		$is_elementor_callable = ( defined( 'ELEMENTOR_VERSION' ) && is_callable( 'Elementor\Plugin::instance' ) ) ? true : false;
		$required_elementor_version = '3.5.0';
		$is_elementor_outdated = ( $is_elementor_callable && ( ! version_compare( ELEMENTOR_VERSION, $required_elementor_version, '>=' ) ) ) ? true : false;

		if ( ! $is_elementor_callable || $is_elementor_outdated ) {
			return;
		}

		if ( $is_elementor_callable ) {
			self::$elementor_instance = Elementor\Plugin::instance();

			$this->file_includes();

			add_filter( 'easy_settings_tabs', [ $this, 'easy_unsupported_theme' ] );
			add_action( 'init', [ $this, 'easy_fallback_support' ] );

			add_filter( 'easy_body_class', [ $this, 'easy_body_class' ] );
			add_action( 'switch_theme', [ $this, 'easy_reset_unsupported_theme_notice' ] );

			add_shortcode( 'hfe_template', [ $this, 'easy_render_template' ] );
			add_shortcode( 'easyhfe_template', [ $this, 'easy_render_template' ] );

			// Ensure Elementor header/footer CSS is always loaded early in head
			add_action( 'wp_head', [ $this, 'easy_header_footer_css_early' ], 1 );

			add_action("wp_enqueue_scripts", array( $this, "easyel_enqueue_scripts" ) );
		}
	}

	/**
	 * Reset the Unsupported theme notice after a theme is switched.
	 */
	public function easy_reset_unsupported_theme_notice() {
		delete_user_meta( get_current_user_id(), 'unsupported-theme' );
	}


	/**
	 * Loads the globally required files for the plugin.
	 */
	public function file_includes() {
		
		require_once EASYELEMENTS_DIR_PATH . 'header-footer-builder/admin/easy-ehf-admin.php';
		require_once EASYELEMENTS_DIR_PATH . 'header-footer-builder/classes/easy-functions.php';
		require_once EASYELEMENTS_DIR_PATH . 'header-footer-builder/classes/easy-ehf-canvas-compat.php';
		require_once EASYELEMENTS_DIR_PATH . 'header-footer-builder/classes/core/conditions/easy-conditions-rules-fields.php';
	}

	/**
	 * Adds classes to the body tag conditionally.
	 */
	public function easy_body_class( $classes ) {
		if ( ee_hfe_header_enabled() ) {
			$classes[] = 'ehf-header';
		}

		if ( ee_hfe_footer_enabled() ) {
			$classes[] = 'ehf-footer';
		}

		return $classes;
	}

	/**
	 * Display Unsupported theme notice if the current theme does not add support.
	 */
	public function easy_unsupported_theme( $easy_settings_tabs = [] ) {
		if ( ! current_theme_supports( 'easy-elements' ) ) {
			$easy_settings_tabs['hfe_settings'] = [
				'name' => __( 'Theme Support', 'easy-elements' ),
				'url'  => admin_url( 'themes.php?page=hfe-settings' ),
			];
		}
		return $easy_settings_tabs;
	}

	/**
	 * Add support for theme if the current theme does not add support.
	 */
	public function easy_fallback_support() {
		if ( ! current_theme_supports( 'easy-elements' ) ) {
			$hfe_compatibility_option = get_option( 'hfe_compatibility_option', '1' );

			if ( '1' === $hfe_compatibility_option ) {
				if ( ! class_exists( 'EE_HFE_Default_Compat' ) ) {
					require_once EASYELEMENTS_DIR_PATH . 'header-footer-builder/compat/theme/easy-hfe-default-compat.php';
				}
			} elseif ( '2' === $hfe_compatibility_option ) {
				require EASYELEMENTS_DIR_PATH . 'header-footer-builder/compat/theme/easy-global-theme-compatibility.php';
			}
		}
	}

	/**
	 * Force enqueue Elementor CSS for a given post ID.
	 */
	public static function force_enqueue_css( $post_id ) {
		if ( ! $post_id ) return;

		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			$css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
		} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
			$css_file = new \Elementor\Post_CSS_File( $post_id );
		} else {
			return;
		}

		$upload_dir = wp_upload_dir();
		$path = $upload_dir['baseurl'] . '/elementor/css/post-' . $post_id . '.css';
		wp_enqueue_style( 'elementor-post-' . $post_id, $path, [], get_post_field( 'post_modified_gmt', $post_id ), 'all' );


		$css_file->enqueue();

		if ( class_exists( '\Elementor\Plugin' ) ) {
			$document = \Elementor\Plugin::instance()->documents->get( $post_id );
			if ( $document ) {
				$widgets = $document->get_elements_data();
				self::enqueue_elementor_widget_styles_recursive( $widgets );
			}
		}
	}

	// Helper: Recursively enqueue widget styles for all elements in the template
	private static function enqueue_elementor_widget_styles_recursive( $elements ) {
		if ( ! is_array( $elements ) ) return;
		foreach ( $elements as $element ) {
			if ( ! empty( $element['widgetType'] ) ) {
				$widget = \Elementor\Plugin::instance()->widgets_manager->get_widget_types( $element['widgetType'] );
				if ( $widget && method_exists( $widget, 'get_style_depends' ) ) {
					$styles = $widget->get_style_depends();
					if ( is_array( $styles ) ) {
						foreach ( $styles as $style ) {
							wp_enqueue_style( $style );
						}
					}
				}
			}
			if ( ! empty( $element['elements'] ) ) {
				self::enqueue_elementor_widget_styles_recursive( $element['elements'] );
			}
		}
	}

	/**
	 * Enqueue scripts for the plugin.
	 */
	public function easyel_enqueue_scripts() {

		if ( class_exists( '\Elementor\Plugin' ) ) {
			$elementor = \Elementor\Plugin::instance();
			$elementor->frontend->enqueue_styles();
		}

		self::force_enqueue_css( get_ee_hfe_header_id() );
		self::force_enqueue_css( get_ee_hfe_footer_id() );
		self::force_enqueue_css( ee_hfe_get_before_footer_id() );
		self::force_enqueue_css( hfe_get_before_header_id() );
	}

	/**
	 * Enqueue all Elementor header/footer CSS early in head.
	 */
	public function easy_header_footer_css_early() {
		self::force_enqueue_css( get_ee_hfe_header_id() );
		self::force_enqueue_css( get_ee_hfe_footer_id() );
		self::force_enqueue_css( ee_hfe_get_before_footer_id() );
		self::force_enqueue_css( hfe_get_before_header_id() );
	}

	/**
	 * Get the header content
	 */
	public static function get_header_content() {
		$header_id = get_ee_hfe_header_id();

		if ( function_exists( 'icl_object_id' ) ) {
			$header_id = icl_object_id( $header_id, 'elementor_library', true, ( defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : null ) );
		} elseif ( function_exists( 'pll_get_post' ) ) {
			$header_id = pll_get_post( $header_id, ( function_exists('pll_current_language') ? pll_current_language() : null ) );
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe because Elementor outputs prepared HTML.
		echo self::$elementor_instance->frontend->get_builder_content_for_display( $header_id );
	}

	/**
	 * Get the footer content
	 */
	public static function get_footer_content() {
		$footer_id = get_ee_hfe_footer_id();

		if ( function_exists( 'icl_object_id' ) ) {
			$footer_id = icl_object_id( $footer_id, 'elementor_library', true, ( defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : null ) );
		} elseif ( function_exists( 'pll_get_post' ) ) {
			$footer_id = pll_get_post( $footer_id, ( function_exists('pll_current_language') ? pll_current_language() : null ) );
		}

		echo "<div class='footer-width-fixer'>";
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe because Elementor outputs prepared HTML.
		echo self::$elementor_instance->frontend->get_builder_content_for_display( $footer_id );
		echo '</div>';
	}

	/**
	 * Get option for the plugin settings
	 */
	public static function get_settings( $setting = '', $default = '' ) {
		if ( 'type_header' == $setting || 'type_footer' == $setting) {
			$templates = self::get_template_id( $setting );

			$template = ! is_array( $templates ) ? $templates : $templates[0];
			$template = apply_filters( "hfe_get_settings_{$setting}", $template );

			return $template;
		}
	}

	/**
	 * Get header or footer template id based on the meta query.
	 */
	public static function get_template_id( $type ) {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return '';
		}		
		$option = [
			'location'  => 'ehf_target_include_locations',
			'exclusion' => 'ehf_target_exclude_locations',
			'users'     => 'ehf_target_user_roles',
		];

		$hfe_templates = RSHF_Target_Rules_Fields::get_instance()->get_posts_by_conditions( 'ee-elementor-hf', $option );

		foreach ( $hfe_templates as $template ) {
			if ( get_post_meta( absint( $template['id'] ), 'ehf_template_type', true ) === $type ) {
				return $template['id'];
			}
		}

		return '';
	}

	/**
	 * Callback to shortcode.
	 */
	public function easy_render_template( $atts ) {
		$atts = shortcode_atts(
			[
				'id' => '',
			],
			$atts,
			'rshfe_template'
		);

		$id = ! empty( $atts['id'] ) ? apply_filters( 'hfe_render_template_id', intval( $atts['id'] ) ) : '';

		if ( empty( $id ) ) {
			return '';
		}

		return self::$elementor_instance->frontend->get_builder_content_for_display( $id );
	}
}

/**
 * Is elementor plugin installed.
 */
if ( ! function_exists( '_is_elementor_installed' ) ) {
	function _is_elementor_installed() {
		return ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) ? true : false;
	}
}