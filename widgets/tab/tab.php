<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Easyel_Tab_Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
	    $handle = 'eel-tab-style';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/tab.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/tab.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }
	    return [ $handle ];
	}
    

    public function get_script_depends() {
        $handle = 'eel-tab-script';
        $js_path = plugin_dir_path( __FILE__ ) . 'js/tab.js';

        if ( get_option( 'easyel_elements_minify_js', '0' ) === '1' && class_exists( 'Easyel_Elements_JS_Loader_Helper' ) ) {
            Easyel_Elements_JS_Loader_Helper::easyel_elements_load_minified_inline_js( $handle, $js_path );
            return [ $handle ];
        }

        if ( ! wp_script_is( $handle, 'registered' ) && file_exists( $js_path ) ) {
            wp_enqueue_script( $handle, plugins_url( 'js/tab.js', __FILE__ ), [ 'jquery' ], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $js_path ) : '1.0.0', true );
            
        }
        return [ $handle ];
    }

    public function get_name() {
        return 'eel-tab';
    }

    public function get_title() {
        return esc_html__( 'Easy Tabs', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'tabs_section',
            [
                'label' => esc_html__( 'Tabs Settings', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label' => esc_html__( 'Tab Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Tab Title', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'tab_title_icon_image_type',
            [
                'label' => esc_html__( 'Icon / Image', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'easy-elements' ),
                    'icon' => esc_html__( 'Icon', 'easy-elements' ),
                    'image' => esc_html__( 'Image', 'easy-elements' ),
                ],
                'default' => 'none',
            ]
        );

        $repeater->add_control(
            'tab_icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'tab_title_icon_image_type' => 'icon',
                ],
            ]
        );

        $repeater->add_control(
            'tab_image',
            [
                'label' => esc_html__( 'Image', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'tab_title_icon_image_type' => 'image',
                ],
            ]
        );


        $repeater->add_control(
            'content_title',
            [
                'label' => esc_html__( 'Content Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Your Title Here', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'content_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Your description here.', 'easy-elements' ),
            ]
        );

        $repeater->add_control(
            'read_more_text',
            [
                'label' => esc_html__( 'Button Text', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Let\'s find out', 'easy-elements' ),
            ]
        );

        $repeater->add_control(
            'read_more_link',
            [
                'label' => esc_html__( 'Button URL', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => esc_html__( 'Tabs Items', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'tab_title' => 'Tab 1',
                        'content_title' => 'Content Title 1',
                        'content_description' => 'Description for tab 1.',
                        'read_more_text' => 'Read More',
                        'read_more_link' => ['url' => '#'],
                    ],
                    [
                        'tab_title' => 'Tab 2',
                        'content_title' => 'Content Title 2',
                        'content_description' => 'Description for tab 2.',
                        'read_more_text' => 'Read More',
                        'read_more_link' => ['url' => '#'],
                    ],
                    [
                        'tab_title' => 'Tab 3',
                        'content_title' => 'Content Title 3',
                        'content_description' => 'Description for tab 3.',
                        'read_more_text' => 'Read More',
                        'read_more_link' => ['url' => '#'],
                    ],
                    [
                        'tab_title' => 'Tab 4',
                        'content_title' => 'Content Title 4',
                        'content_description' => 'Description for tab 4.',
                        'read_more_text' => 'Read More',
                        'read_more_link' => ['url' => '#'],
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );       

        $this->end_controls_section();


        $this->start_controls_section(
            'tabs_nav_section',
            [
                'label' => esc_html__( 'Tab Title Settings', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'tab_icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'top',
                'options' => [                    
                    'left' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => esc_html__( 'Top', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
            ]
        );



        $this->add_control(
            'tab_layout_direction',
            [
                'label' => esc_html__( 'Layout', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'top',
                'options' => [                    
                    'left' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => esc_html__( 'Top', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
            ]
        );

        $this->end_controls_section();

        // Style Tab - Tab Titles
        $this->start_controls_section(
            'tab_title_style_section',
            [
                'label' => esc_html__( 'Tab Titles', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tab_title_typography',
                'selector' => '{{WRAPPER}} .ee-tab-title-text',
            ]
        );

        $this->start_controls_tabs( 'tab_title_colors' );

        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'tab_title_color',
            [
                'label' => esc_html__( 'Text Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-title-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_title_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-titles li' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tab_title_border',
                'selector' => '{{WRAPPER}} .ee-tab-titles li',
            ]
        );

        $this->add_control(
            'tab_title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-titles li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_title_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-titles li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_title_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-titles li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'tab_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-titles i, {{WRAPPER}} .ee-tab-titles svg' => 'color: {{VALUE}}; fill: {{VALUE}}',
                ],
                'description' => esc_html__( 'Only works when "Icon" is selected as icon type. 😊', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'tab_icon_bg_color',
            [
                'label' => esc_html__( 'Icon/image Bg Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-image, {{WRAPPER}} .ee-tab-icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_title_space_title',
            [
                'label' => esc_html__( 'Titles Part', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_title_space',
            [
                'label' => esc_html__( 'Bottom Spacing', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee-tabs-wrapper[data-tab-direction="top"] .ee-tab-titles' => 'padding: 0 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tab_title_bottom_border',
                'selector' => '{{WRAPPER}} .ee-tabs-wrapper[data-tab-direction="top"] .ee-tab-titles',
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_active',
            [
                'label' => esc_html__( 'Active', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'tab_title_active_color',
            [
                'label' => esc_html__( 'Text Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-titles li.active .ee-tab-title-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_title_active_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-titles li.active' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tab_title_active_border_color',
            [
                'label' => esc_html__( 'Border Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-titles li.active' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'tab_title_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Tab - Tab Contents
        $this->start_controls_section(
            'tab_content_style_section',
            [
                'label' => esc_html__( 'Tab Contents', 'easy-elements' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_title_heading',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_title_typography',
                'selector' => '{{WRAPPER}} .ee-content-title',
            ]
        );

        $this->add_control(
            'content_title_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-content-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_title_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-content-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_description_heading',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_description_typography',
                'selector' => '{{WRAPPER}} .ee-content-description',
            ]
        );

        $this->add_control(
            'content_description_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-content-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_description_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-content-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'read_more_btn_heading',
            [
                'label' => esc_html__( 'Read More', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'read_more_btn_typography',
                'selector' => '{{WRAPPER}} .ee-read-more',
            ]
        );

        $this->start_controls_tabs( 'read_more_btn_colors' );

        $this->start_controls_tab(
            'read_more_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'read_more_btn_color',
            [
                'label' => esc_html__( 'Text Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-read-more' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'read_more_btn_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-read-more' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'read_more_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'read_more_btn_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-read-more:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'read_more_btn_hover_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-read-more:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'read_more_btn_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-read-more:hover' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'read_more_btn_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'read_more_btn_border',
                'selector' => '{{WRAPPER}} .ee-read-more',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'read_more_btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-read-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_btn_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_btn_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_area_heading',
            [
                'label' => esc_html__( 'Content Part', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_area_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-content' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_area_border',
                'selector' => '{{WRAPPER}} .ee-tab-content',
            ]
        );

        $this->add_control(
            'content_area_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_area_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_area_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee-tab-content, {{WRAPPER}} .ee-tabs-wrapper[data-tab-direction="top"] .ee-tab-contents .ee-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_eligible_templates() {
        $templates = [];

        $posts = get_posts([
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        foreach ( $posts as $post ) {
            $templates[ $post->ID ] = $post->post_title;
        }

        return $templates;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $direction = ! empty( $settings['tab_layout_direction'] ) ? $settings['tab_layout_direction'] : 'left';
        $icon_position = isset( $settings['tab_icon_position'] ) ? $settings['tab_icon_position'] : 'top';

        if ( ! empty( $settings['tabs'] ) ) {
            echo '<div class="ee-tabs-wrapper" data-tab-direction="' . esc_attr( $settings['tab_layout_direction'] ) . '" data-icon-position="' . esc_attr( $icon_position ) . '">';
            echo '<ul class="ee-tab-titles">';
            foreach ( $settings['tabs'] as $index => $item ) {
                echo '<li data-tab="ee-tab-' . esc_attr( $index ) . '">';
                if ( isset( $item['tab_title_icon_image_type'] ) ) {
                    if ( $item['tab_title_icon_image_type'] === 'icon' && ! empty( $item['tab_icon']['value'] ) ) {
                        echo '<span class="ee-tab-icon">';
                        \Elementor\Icons_Manager::render_icon( $item['tab_icon'], [ 'aria-hidden' => 'true' ] );
                        echo '</span>';
                    } elseif ( $item['tab_title_icon_image_type'] === 'image' && ! empty( $item['tab_image']['id'] ) ) {
                        echo wp_get_attachment_image( $item['tab_image']['id'], 'full' );
                    } elseif ( ! empty( $item['tab_image']['url'] ) ) {
                        echo wp_kses_post(
                            get_image_tag(
                                0,
                                '',
                                '',
                                '',
                                $item['tab_image']['url']
                            )
                        );
                    }
                }
                echo '<span class="ee-tab-title-text">' . esc_html( $item['tab_title'] ) . '</span>';
                echo '</li>';
            }
            echo '</ul>';
            echo '<div class="ee-tab-contents">';

            foreach ( $settings['tabs'] as $index => $item ) {
                echo '<div class="ee-tab-content" id="ee-tab-' . esc_attr( $index ) . '">';                

                    if ( ! empty( $item['content_title'] ) ) {
                        echo '<h4 class="ee-content-title">' . esc_html( $item['content_title'] ) . '</h4>';
                    }

                    if ( ! empty( $item['content_description'] ) ) {
                        echo '<p class="ee-content-description">' . esc_html( $item['content_description'] ) . '</p>';
                    }

                    if ( ! empty( $item['read_more_text'] ) && ! empty( $item['read_more_link']['url'] ) ) {
                        $target = ! empty( $item['read_more_link']['is_external'] ) ? '_blank' : '_self';
                        echo '<a class="ee-read-more" href="' . esc_url( $item['read_more_link']['url'] ) . '" target="' . esc_attr( $target ) . '">' . esc_html( $item['read_more_text'] ) . ' <i class="unicon-chevron-right"> </i> </a>';
                    }               

                echo '</div>';
            }

            echo '</div>';
            echo '</div>';
        }
    }
}
?>
