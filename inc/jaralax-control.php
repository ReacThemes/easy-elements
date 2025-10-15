<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Elementor\Controls_Manager;
use Elementor\Element_Base;

class Easyel_custom_class {

    public static function init() {
        // inject_section_class
       add_action('elementor/element/image/section_style_image/after_section_end', [__CLASS__, 'Easyel_inject_section_class'], 1, 2);     
    }       

    public static function Easyel_inject_section_class( $element, $args ) {
        $element->start_controls_section(
            'rt_jarallax_section',
            [
                'label' => esc_html__( 'Image Parallax', 'easy-elements-pro' ),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'enable_jarallax',
            [
                'label' => esc_html__( 'Enable Parallax', 'easy-elements-pro' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements-pro' ),
                'label_off' => esc_html__( 'No', 'easy-elements-pro' ),
                'return_value' => 'has-jarallax-img',
                'prefix_class' => '',
            ]
        );

        $element->add_responsive_control(
            'jarallax_image_width',
            [
                'label' => esc_html__( 'Width', 'easy-elements-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .jarallax, {{WRAPPER}} .has-jarallax-img .jarallax' => 'width: {{SIZE}}{{UNIT}} !important;',                                 
                    '{{WRAPPER}} .elementor-widget-container img, {{WRAPPER}} .has-jarallax-img .jarallax img' => 'width: {{SIZE}}{{UNIT}};',                                 
                ],                  
            ]
        ); 

        $element->add_responsive_control(
            'jarallax_image_height',
            [
                'label' => esc_html__( 'Height', 'easy-elements-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .jarallax, {{WRAPPER}} .has-jarallax-img .jarallax' => 'height: {{SIZE}}{{UNIT}} !important;',                                   
                    '{{WRAPPER}} .elementor-widget-container img, {{WRAPPER}} .has-jarallax-img .jarallax img' => 'height: {{SIZE}}{{UNIT}};',                                   
                ],                  
            ]
        ); 

        $element->add_control(
            'object_fit_cover',
            [
                'label'   => esc_html__('Object Fit', 'easy-elements-pro'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'rtimg-contain',
                'options' => [
                    'rtimg-contain' => esc_html__('Contain', 'easy-elements-pro'),                    
                    'rtimg-cover' => esc_html__('Cover', 'easy-elements-pro'),                   
                ],
                'prefix_class' => '',
            ]
        );

        $element->add_responsive_control(
            'jarallax_image_border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'easy-elements-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .jarallax-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .jarallax-container img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; !important;',
                ],                  
            ]
        ); 

        $element->end_controls_section();
    }
    }

Easyel_custom_class::init();