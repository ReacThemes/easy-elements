<?php
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Icon_Box__Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-icon-box-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/icon-box.css';
        
        if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
            Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
            return [ $handle ];
        }
        
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/icon-box.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }
        return [ $handle ];
    }

    public function get_name() {
        return 'eel-icon-box';
    }

    public function get_title() {
        return esc_html__( 'Easy Icon Box', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'box', 'sevice', 'icon', 'icon-box', 'text' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_logo',
            [
                'label' => esc_html__( 'Icon Box Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );


        $this->add_control(
            'number_title',
            [
                'label' => esc_html__( 'Number Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'number_title_color',
            [
                'label' => esc_html__( 'Number Title Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-pro-number' => 'color: {{VALUE}};',

                ],
                'condition' => [
                    'number_gradeint' => '',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'number_title_background',
                'label' => esc_html__( 'Number Title Background', 'easy-elements' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eel-pro-number',
                'condition' => [
                    'number_gradeint' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_title_typography',
                'label' => esc_html__( 'Number Title Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-pro-number',
            ]
        );   

        $this->add_control(
            'number_gradeint',
            [
                'label' => esc_html__('Show Gradient', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'procs_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Manufacturing Industrial', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            '_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Optimizing production and supply chain operations and generational transitions', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );


        $this->add_control(
            'show_read_more',
            [
                'label' => esc_html__('Show Read More', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        
        $this->add_control(
            'read_more_type',
            [
                'label' => esc_html__('Read More Type', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'read_text' => esc_html__('Text', 'easy-elements'),
                    'read_icon' => esc_html__('Icon', 'easy-elements'),
                    'read_icon_to_text' => esc_html__('Icon Hover to Text Show', 'easy-elements'),
                ],
                'default' => 'read_text',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Read More Text', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Read More', 'easy-elements'),
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );
        
        $this->add_control(
            'read_more_icon',
            [
                'label' => esc_html__('Read More Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );

        $this->add_control(
            'read_more_text_icon',
            [
                'label' => esc_html__('Text Button Icon', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                    'read_more_text_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_text_icon_show',
            [
                'label' => esc_html__('Show Icon Next to Text', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'easy-elements'),
                'label_off' => esc_html__('Hide', 'easy-elements'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p'   => 'p',
                ],
            ]
        );

        $this->end_controls_section();        

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('background_tabs');

        $this->start_controls_tab('background_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'color: {{VALUE}}; fill-opacity: 1;',
                    '{{WRAPPER}} .ee--icon-box .eel-icon svg' => 'fill: {{VALUE}}; fill-opacity: 1;',
                    '{{WRAPPER}} .ee--icon-box .eel-icon svg path' => 'fill: {{VALUE}}; fill-opacity: 1;',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__( 'Background', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background_gradiant',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'icon_typography',
                'label' => esc_html__( 'Icon Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon',
            ]
        );


        $this->add_control(
            'icon__border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gradient_border',
            [
                'label' => esc_html__( 'Gradient Border', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        // gradient border color

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_box_size',
            [
                'label'      => esc_html__( 'Box Size', 'easy-elements' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--icon-box .eel-icon',
            ]
        );

        $this->add_control(
            'icon_rotate',
            [
                'label' => esc_html__( 'Rotate', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-icon svg, {{WRAPPER}} .ee--icon-box .eel-icon i' => 'transform: rotate({{SIZE}}deg);',
                ],
            ]
        );


        $this->end_controls_tab();


        $this->start_controls_tab('background_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .eel-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--icon-box:hover .eel-icon svg path' => 'fill: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'icon_hover_bg_color',
            [
                'label' => esc_html__( 'Background', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .eel-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__( 'Title', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('title_style');

        $this->start_controls_tab('title_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-box-title' => 'color: {{VALUE}}; transition: all 0.3s ease-in;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => '_title_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .icon-box-title',
            ]
        );  

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .icon-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        ); 

        $this->end_controls_tab();

        $this->start_controls_tab('title_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .icon-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();  
        
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__( 'Description', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('desc_style');

        $this->start_controls_tab('desc_normal', [
            'label' => __('Normal', 'easy-elements'),
        ]);

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'box_desc_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .icon-box-description',
            ]
        );
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .icon-box-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        ); 

        $this->end_controls_tab();

        $this->start_controls_tab('desc_hover', [
            'label' => __('Hover', 'easy-elements'),
        ]);

        $this->add_control(
            'desc_hover_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box:hover .icon-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'read_more_icon_color',
            [
                'label' => esc_html__('Icon Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-read-more-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-read-more-icon i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );

        $this->add_control(
            'read_more_icon_bg_color',
            [
                'label' => esc_html__('Icon Background Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-read-more-icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_icon_padding',
            [
                'label' => esc_html__('Padding', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-read-more-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );

        $this->add_control(
            'border_radius_icon',
            [
                'label' => __('Border Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-read-more-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'read_more_icon_size',
            [
                'label' => esc_html__('Icon Size', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-read-more-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => 'read_icon',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'read_m_read_mr_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-read-more-text',
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        ); 

        $this->add_control(
            'read_more_text_color',
            [
                'label' => esc_html__('Text Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-read-more-text svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eel-read-more-text svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );
        $this->add_control(
            'read_more_text_color_hover',
            [
                'label' => esc_html__('Text Hover Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box .eel-read-more-text:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-read-more-text:hover svg' => 'fill: {{VALUE}};',  
                    '{{WRAPPER}} .eel-read-more-text:hover svg path' => 'fill: {{VALUE}};',                   
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_control(
            'read_more_text_bg_color',
            [
                'label' => esc_html__('Background Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_text_border_radius',
            [
                'label' => esc_html__('Border Radius', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );
        $this->add_responsive_control(
            'read_more_text_padding',
            [
                'label' => esc_html__('Padding', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; display: inline-block;',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'read_more_text_border',
                'label' => esc_html__('Border', 'easy-elements'),
                'selector' => '{{WRAPPER}} .eel-read-more-text, {{WRAPPER}} .eel-read-more-icon',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'top_btm',
            [
                'label' => esc_html__( 'Top/Bottom', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text-icon' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                    'read_more_text_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_left_right',
            [
                'label' => esc_html__( 'Left/Right', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text-icon' => 'left: {{SIZE}}{{UNIT}}; position: relative;',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                    'read_more_text_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_text_icon_size',
            [
                'label' => esc_html__('Text Button Icon Size', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-read-more-text-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-read-more-text-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                    'read_more_type' => ['read_text','read_icon_to_text'],
                    'read_more_text_icon_show' => 'yes',
                ],
            ]
        );
    

        $this->add_control(
            'icon_direction',
            [
                'label' => esc_html__( 'Direction', 'easy-elements' ),
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

        // Vertical Alignment Control
        $this->add_responsive_control(
            'icon_vertical_alignment',
            [
                'label' => esc_html__( 'Vertical Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Middle', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box.right, {{WRAPPER}} .ee--icon-box.left' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'icon_direction' => ['left', 'right'],
                ],
            ]
        );


        $this->add_responsive_control(
            '_text_align',
            [
                'label' => esc_html__( 'Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'easy-elements' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ee--icon-box, {{WRAPPER}} .eel-pro-number' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $gradient_border = ( isset( $settings['gradient_border'] ) && $settings['gradient_border'] === 'yes' ) 
        ? 'ee--gradient-border' 
        : '';

        ?>
        <div class="eel-icon-box-wraps">
            <?php 
                $icon_direction = isset($settings['icon_direction']) ? esc_attr($settings['icon_direction']) : '';
                $link = isset($settings['link']['url']) ? esc_url($settings['link']['url']) : '';
                $target = ! empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';
                $nofollow = ! empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';
            ?>
            <div class="ee--icon-box <?php echo esc_attr($icon_direction); ?>">
                <?php if ( $link ) : ?>
                    <a href="<?php echo esc_url($link); ?>"<?php echo esc_attr($target) . esc_attr($nofollow); ?>>
                <?php endif; ?>

                <?php if ( isset( $settings['icon']['value'] ) && $settings['icon']['value'] ) : ?>
                    <span class="eel-icon">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( ! empty( $settings['number_title'] ) ) : ?>
                    <h6 class="eel-pro-number  easyel-gradeint-<?php echo esc_attr($settings['number_gradeint']) ?>"><?php echo esc_html( $settings['number_title'] ); ?></h6>
                <?php endif; ?>

                <?php if ( $settings['icon_direction'] === 'left' || $settings['icon_direction'] === 'right' ) : ?>
                    <div class="eel-title-content-wrap">
                <?php endif; ?>

                <?php if ( ! empty( $settings['procs_title'] ) ) :
                    $title_tag = isset( $settings['title_tag'] ) ? esc_attr( $settings['title_tag'] ) : 'h3'; ?>
                    <<?php echo tag_escape($title_tag); ?> class="icon-box-title">
                        <?php echo wp_kses_post( $settings['procs_title'] ); ?>
                    </<?php echo tag_escape($title_tag); ?>>
                <?php endif; ?>

                <?php if ( ! empty( $settings['_description'] ) ) : ?>
                    <div class="icon-box-description"><?php echo wp_kses_post( $settings['_description'] ); ?></div>
                <?php endif; ?>

                <?php if ( !empty($settings['show_read_more']) && $settings['show_read_more'] === 'yes' ) : ?>
                    <div class="eel-read-more">
                        <?php if ( $settings['read_more_type'] === 'read_icon' ) : ?>
                            <span class="eel-read-more-icon">
                                <?php
                                if ( !empty($settings['read_more_icon']['value']) ) {
                                    \Elementor\Icons_Manager::render_icon( $settings['read_more_icon'], [ 'aria-hidden' => 'true' ] );
                                } else {
                                    echo '<i class="unicon-arrow-up-right"></i>';
                                }
                                ?>
                            </span>
                        <?php elseif ( $settings['read_more_type'] === 'read_text' && !empty($settings['read_more_text']) ) : ?>
                            <span class="eel-read-more-text">
                                <?php echo esc_html( $settings['read_more_text'] ); ?>
                                <?php
                                if (
                                    !empty($settings['read_more_text_icon_show']) &&
                                    $settings['read_more_text_icon_show'] === 'yes'
                                ) {
                                    echo '<span class="eel-read-more-text-icon">';
                                    if (!empty($settings['read_more_text_icon']['value'])) {
                                        \Elementor\Icons_Manager::render_icon( $settings['read_more_text_icon'], [ 'aria-hidden' => 'true' ] );
                                    } else {
                                        echo '<i class="unicon-arrow-up-right"></i>';
                                    }
                                    echo '</span>';
                                }
                                ?>
                            </span>
                        <?php elseif ( $settings['read_more_type'] === 'read_icon_to_text' && !empty($settings['read_more_text']) ) : ?>
                            <span class="eel-read-more-text eel-icon-to-text">
                                <span class="eel-text"><?php echo esc_html( $settings['read_more_text'] ); ?></span>
                                <?php
                                if (
                                    !empty($settings['read_more_text_icon_show']) &&
                                    $settings['read_more_text_icon_show'] === 'yes'
                                ) {
                                    echo '<span class="eel-read-more-text-icon">';
                                    if (!empty($settings['read_more_text_icon']['value'])) {
                                        \Elementor\Icons_Manager::render_icon( $settings['read_more_text_icon'], [ 'aria-hidden' => 'true' ] );
                                    } else {
                                        echo '<i class="unicon-add"></i>';
                                    }
                                    echo '</span>';
                                }
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ( $settings['icon_direction'] === 'left' || $settings['icon_direction'] === 'right' ) : ?>
                    </div>
                <?php endif; ?>

                <?php if ( $link ) : ?>
                    </a>
                <?php endif; ?>
            </div>                         
        </div>
    <?php
    }
} ?>