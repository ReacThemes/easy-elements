<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Easy_Wrapper_Link_Controls {

	public static function register_controls( $element ) {
        $element->add_control(
            'easyel_wrapper_link',
            [
                'label'        => __( 'Enable Wrapper Link', 'easy-elements' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $element->add_control(
            'easyel_wrapper_link_url',
            [
                'label'       => __( 'Link', 'easy-elements' ),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'easy-elements' ),
                'default'     => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'show_external' => true,
                'condition'   => [
                    'easyel_wrapper_link' => 'yes',
                ],
            ]
        );
    }
}