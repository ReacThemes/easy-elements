<?php 

add_action( 'elementor/element/eel-heading/content_section/before_section_end', function( $element, $args ) {

    $animation_options = [
        'default'      => 'Default',
        'split-text'   => 'Split Text (Letter)',
        'split-words'  => 'Split Words',
    ];

    $animation_options = apply_filters("easyel_animation_option", $animation_options );
    $element->add_control(
        'animation_type',
        [
            'label'   => esc_html__( 'Animation Type', 'easy-elements-pro' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'default',
            'options' => $animation_options,
        ]
    );

    $element->add_control(
        'eel_parallax_title',
        [
            'label'        => __('Enable Parallax', 'easy-elements-pro'),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'easy-elements-pro'),
            'label_off'    => __('No'),
            'return_value' => 'yes',
            'default'      => '',
        ]
    );

    // Direction: Left → Right / Right → Left
    $element->add_control(
        'eel_parallax_direction',
        [
            'label'     => __('Direction', 'easy-elements-pro'),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'options'   => [
                'left'  => __('Right to Left', 'easy-elements-pro'),
                'right' => __('Left to Right', 'easy-elements-pro'),
            ],
            'default'   => 'left',
            'condition' => [
                'eel_parallax_title' => 'yes',
            ],
        ]
    );

    // Movement percent
    $element->add_control(
        'eel_parallax_percent',
        [
            'label'     => __('Movement Percent', 'easy-elements-pro'),
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range'     => [
                '%' => [
                    'min' => 0,
                    'max' => 200,
                    'step'=> 1,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 36,
            ],
            'condition' => [
                'eel_parallax_title' => 'yes',
            ],
        ]
    );
    

}, 10, 2 );