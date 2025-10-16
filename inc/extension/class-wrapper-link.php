<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class EasyEl_Wrapper_Link {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
            self::$instance->easyel_setup_hooks();
        }
        return self::$instance;
    }

    private function easyel_setup_hooks() {

        $tab_slug = 'extensions';
        $extensions_settings = get_option('easy_element_' . $tab_slug, [] );

        $enable_wrapper_link = isset( $extensions_settings['enable_wrapper_link'] ) ? $extensions_settings['enable_wrapper_link'] : 0;

        if(  (int) $enable_wrapper_link !== 1 ) {
            return;
        }
        
        add_action( 'plugins_loaded', [ $this, 'easyel_check_elementor' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'easyel_enqueue_styles' ] );
    }

    public function easyel_check_elementor() {
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        add_action('elementor/element/common/_section_style/after_section_end', [ $this, 'easy_wrapper_link_register_section'] );
		add_action('elementor/element/section/section_advanced/after_section_end', [ $this, 'easy_wrapper_link_register_section'] );

        add_action('elementor/element/common/easyel_wrapper_link_section/before_section_end', [ $this, 'easyel_add_wrapper_controls'], 10, 2 );
 		add_action('elementor/element/section/easyel_wrapper_link_section/before_section_end', [ $this, 'easyel_add_wrapper_controls'], 10, 2 );

        add_action('elementor/element/container/section_layout/after_section_end', [ $this, 'easy_wrapper_link_register_section'] );

        add_action('elementor/element/container/easyel_wrapper_link_section/before_section_end', [ $this, 'easyel_add_wrapper_controls'], 10, 2 );

        $render_hooks = [
            'elementor/frontend/widget/before_render'   => 'easyel_maybe_open_wrapper',
            'elementor/frontend/widget/after_render'    => 'easyel_maybe_close_wrapper',
            'elementor/frontend/section/before_render'  => 'easyel_maybe_open_wrapper',
            'elementor/frontend/section/after_render'   => 'easyel_maybe_close_wrapper',
            'elementor/frontend/column/before_render'   => 'easyel_maybe_open_wrapper',
            'elementor/frontend/column/after_render'    => 'easyel_maybe_close_wrapper',
            'elementor/frontend/container/before_render'=> 'easyel_maybe_open_wrapper',
            'elementor/frontend/container/after_render' => 'easyel_maybe_close_wrapper',
        ];

        foreach ( $render_hooks as $hook => $method ) {
            add_action( $hook, [ $this, $method ] );
        }

    }

    public function easy_wrapper_link_register_section( $element ) {

        $element->start_controls_section(
            'easyel_wrapper_link_section',
            [
                'label' => __( 'Easy Wrapper Link', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );
       
		$element->end_controls_section();
	}

    public function easyel_add_wrapper_controls( $element, $section_id ) {

        require_once EASYELEMENTS_DIR_PATH . 'inc/extension/wrapper-link/controls/wrapper-link-controls.php';
		Easy_Wrapper_Link_Controls::register_controls( $element );
    }

    public function easyel_enqueue_styles() {
       
        $version = defined('EASYELEMENTS_VER') ? EASYELEMENTS_VER : '1.0.0';

        wp_register_style( 
            'easyel-wrapper-link-style', 
            false, 
            array(), 
            $version 
        );

        wp_enqueue_style( 'easyel-wrapper-link-style' );

        $css = '
        .easyel-wrapper-link {
            display: block;
            color: inherit;
            text-decoration: none;
        }
        .easyel-wrapper-link:focus { outline: none; }
        .elementor-widget .easyel-wrapper-inner { height: 100%; }
        ';

        wp_add_inline_style( 'easyel-wrapper-link-style', $css );
    }


    public function easyel_maybe_open_wrapper( $widget ) {
        $settings = $widget->get_settings_for_display();

        if ( empty( $settings['easyel_wrapper_link'] ) || 'yes' !== $settings['easyel_wrapper_link'] ) {
            return;
        }

        $link = ! empty( $settings['easyel_wrapper_link_url'] ) ? $settings['easyel_wrapper_link_url'] : [];
        if ( empty( $link['url'] ) ) {
            return;
        }

        $target = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
        $rel    = ! empty( $link['nofollow'] ) ? ' rel="nofollow"' : '';

        echo sprintf(
            '<a class="easyel-wrapper-link" href="%s"%s%s><span class="easyel-wrapper-inner">',
            esc_url( $link['url'] ),
            esc_attr( $target ),
            esc_attr( $rel )
        );

        $this->easyel_push_open_wrapper( $widget );
    }

    public function easyel_maybe_close_wrapper( $widget ) {
        if ( isset( $this->open_wrappers[ $widget->get_id() ] ) ) {
            unset( $this->open_wrappers[ $widget->get_id() ] );
            echo '</span></a>';
        }
    }

     /**
     * Helper to track opened wrappers per widget instance to avoid mismatched closes.
     * We store widget unique id in a static property.
     */
    private $open_wrappers = [];

    private function easyel_push_open_wrapper( $widget ) {
        $key = $this->easyel_widget_unique_key( $widget );
        $this->open_wrappers[ $key ] = true;
    }

    private function easyel_widget_unique_key( $widget ) {
        
        if ( method_exists( $widget, 'get_id' ) ) {
            return $widget->get_id();
        }
      
        return spl_object_hash( $widget );
    }
}

EasyEl_Wrapper_Link::instance();
