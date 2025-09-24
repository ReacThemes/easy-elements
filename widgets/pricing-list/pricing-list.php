<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Pricing_Table_List_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
		$handle = 'eel-pricing-list-style';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/pricing-list.css';
		
		if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
			Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
			return [ $handle ];
		}
		
		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style( $handle, plugins_url( 'css/pricing-list.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
		}	
		return [ $handle ];
	}

	public function get_name() {
		return 'eel-pricing-list';
	}

	public function get_title() {
		return __( 'Easy Pricing List', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	protected function register_controls() {
		$this->start_controls_section('section_settings', [
			'label' => __('Pricing Settings', 'easy-elements'),
		]);

		$this->add_control('skin_style', [
			'label' => __('Pricing Skin', 'easy-elements'),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'skin1' => 'Skin 01',
				'skin2' => 'Skin 02',
			],
			'default' => 'skin1',
		]);

		$this->end_controls_section();

		// Title & Price
		$this->start_controls_section('section_title_price', [
			'label' => __('Title & Price Settings', 'easy-elements'),
		]);
		$this->add_control('title', [
			'label' => __('Title', 'easy-elements'),
			'type' => Controls_Manager::TEXT,
			'default' => 'Basic Plan',
		]);

		// On Sale Switcher
		$this->add_control('on_sale', [
			'label' => __('On Sale?', 'easy-elements'),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => __('Yes', 'easy-elements'),
			'label_off' => __('No', 'easy-elements'),
			'return_value' => 'yes',
			'default' => '',
		]);

		// Regular Price - visible only if On Sale = yes
		$this->add_control('regular_price', [
			'label' => __('Regular Price', 'easy-elements'),
			'type' => Controls_Manager::TEXT,
			'default' => '59',
			'condition' => [
				'on_sale' => 'yes',
			],
		]);

		// Sale Price - visible only if On Sale = yes
		$this->add_control('sale_price', [
			'label' => __('Sale Price', 'easy-elements'),
			'type' => Controls_Manager::TEXT,
			'default' => '49',
			'condition' => [
				'on_sale' => 'yes',
			],
		]);

		// Price (used if On Sale = no)
		$this->add_control('price', [
			'label' => __('Price', 'easy-elements'),
			'type' => Controls_Manager::TEXT,
			'default' => '59',
			'condition' => [
				'on_sale!' => 'yes',
			],
		]);

		$this->add_control('price_currency', [
			'label' => __('Currency', 'easy-elements'),
			'type' => Controls_Manager::TEXT,
			'default' => '$',
		]);
		$this->add_control('currency_placement', [
		    'label' => __('Currency Position', 'easy-elements'),
		    'type' => \Elementor\Controls_Manager::CHOOSE,
		    'default' => 'left',
		    'options' => [
		        'left' => [
		            'title' => __('Left', 'easy-elements'),
		            'icon'  => 'eicon-h-align-left',
		        ],
		        'right' => [
		            'title' => __('Right', 'easy-elements'),
		            'icon'  => 'eicon-h-align-right',
		        ],
		    ],
		    'toggle' => false,
		]);
		$this->add_control('price_period', [
			'label' => __('Period', 'easy-elements'),
			'type' => Controls_Manager::TEXT,
			'default' => 'month',
		]);
		$this->add_control('period_separator', [
			'label' => __('Separator', 'easy-elements'),
			'type' => Controls_Manager::TEXT,
			'default' => '/',
		]);

		$this->add_control('header_alignment', [
		    'label' => __('Alignment', 'easy-elements'),
		    'type' => \Elementor\Controls_Manager::CHOOSE,
		    'options' => [
		        'left' => [
		            'title' => __('Left', 'easy-elements'),
		            'icon' => 'eicon-text-align-left',
		        ],
		        'center' => [
		            'title' => __('Center', 'easy-elements'),
		            'icon' => 'eicon-text-align-center',
		        ],
		        'right' => [
		            'title' => __('Right', 'easy-elements'),
		            'icon' => 'eicon-text-align-right',
		        ],
		    ],
		    'default' => 'left',
		    'toggle' => true,
		    'selectors' => [
		        '{{WRAPPER}} .eel-price-title, {{WRAPPER}} .eel-price' => 'text-align: {{VALUE}};',
		    ],
		]);

		$this->end_controls_section();

		// Features
		$this->start_controls_section('section_features', [
			'label' => __('Features Settings', 'easy-elements'),
		]);
		$this->add_control('features_list', [
		    'label' => __('Features', 'easy-elements'),
		    'type' => Controls_Manager::REPEATER,
		    'fields' => [
		        [
		            'name' => 'icon',
		            'label' => __('Icon', 'easy-elements'),
		            'type' => Controls_Manager::ICONS,
		        ],
		        [
		            'name' => 'text',
		            'label' => __('Text', 'easy-elements'),
		            'type' => Controls_Manager::TEXT,
		            'default' => '99.9% Uptime Guarantee',
		        ],
		    ],
		    'default' => [
		        [
		            'text' => '99.9% Uptime Guarantee',
		        ],
		        [
		            'text' => 'Free SSL Certificate',
		        ],
		        [
		            'text' => '24/7 Expert Support',
		        ],
		        [
		            'text' => 'One-Click WordPress Install',
		        ],
		        [
		            'text' => 'Unlimited Bandwidth',
		        ],
		        [
		            'text' => 'SSD Storage',
		        ],
		        [
		            'text' => 'Free Daily Backups',
		        ],
		        [
		            'text' => 'Enhanced Security',
		        ],
		    ],
		]);

		$this->add_control('feature_icon_style', [
		    'label' => __('Icon Style', 'easy-elements'),
		    'type' => Controls_Manager::SELECT,
		    'options' => [
		        'icon-only' => __('Icon Only', 'easy-elements'),
		        'icon-bg' => __('Icon with Background', 'easy-elements'),
		        'icon-border' => __('Icon with Border', 'easy-elements'),
		    ],
		    'default' => 'icon-only',
		]);

		$this->add_group_control(
		    Group_Control_Background::get_type(),
		    [
		        'name' => 'feature_icon_bg',
		        'label' => __('Icon Background', 'easy-elements'),
		        'types' => ['classic', 'gradient'],
		        'selector' => '{{WRAPPER}} .eel-features .feature-icon.icon-bg',
		        'condition' => [
		            'feature_icon_style' => 'icon-bg',
		        ],
		    ]
		);

		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name' => 'feature_icon_border',
		        'label' => __('Icon Border', 'easy-elements'),
		        'condition' => ['feature_icon_style' => 'icon-border'],
		        'selector' => '{{WRAPPER}} .eel-features .feature-icon.icon-border',
		    ]
		);

		$this->add_control('feature_icon_color', [
		    'label' => __('Icon Color', 'easy-elements'),
		    'type' => Controls_Manager::COLOR,
		    'condition' => [
		        'feature_icon_style!' => '',
		    ],
		    'selectors' => [
		        '{{WRAPPER}} .eel-features .feature-icon' => 'color: {{VALUE}};',
		        '{{WRAPPER}} .eel-features .feature-icon svg' => 'fill: {{VALUE}};',
		    ],
		]);

		$this->add_control('feature_icon_size', [
		    'label' => __('Icon Size (px)', 'easy-elements'),
		    'type' => \Elementor\Controls_Manager::SLIDER,
		    'size_units' => ['px'],
		    'range' => [
		        'px' => [
		            'min' => 8,
		            'max' => 64,
		            'step' => 1,
		        ],
		    ],
		    'selectors' => [
		        '{{WRAPPER}} .eel-features svg.feature-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
		        '{{WRAPPER}} .eel-features i.feature-icon' => 'font-size: {{SIZE}}{{UNIT}};',
		    ],
		]);

		$this->add_responsive_control('feature_icon_padding', [
		    'label' => __('Icon Padding', 'easy-elements'),
		    'type' => \Elementor\Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%', 'em'],
		    'condition' => [
		        'feature_icon_style' => ['icon-border', 'icon-bg'],
		    ],
		    'selectors' => [
		        '{{WRAPPER}} .eel-features .feature-icon.icon-border' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        '{{WRAPPER}} .eel-features .feature-icon.icon-bg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
		]);

		$this->add_responsive_control('feature_icon_border_radius', [
		    'label' => __('Icon Border Radius', 'easy-elements'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%'],
		    'condition' => [
		        'feature_icon_style' => ['icon-border', 'icon-bg'],
		    ],
		    'selectors' => [
		        '{{WRAPPER}} .eel-features .feature-icon.icon-border' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        '{{WRAPPER}} .eel-features .feature-icon.icon-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
		]);

		$this->add_control('feature_text_alignment', [
		    'label' => __('Alignment', 'easy-elements'),
		    'type' => \Elementor\Controls_Manager::CHOOSE,
		    'options' => [
		        'left' => [
		            'title' => __('Left', 'easy-elements'),
		            'icon' => 'eicon-text-align-left',
		        ],
		        'center' => [
		            'title' => __('Center', 'easy-elements'),
		            'icon' => 'eicon-text-align-center',
		        ],
		        'right' => [
		            'title' => __('Right', 'easy-elements'),
		            'icon' => 'eicon-text-align-right',
		        ],
		    ],
		    'default' => 'left',
		    'toggle' => true,
		    'selectors' => [
		        '{{WRAPPER}} ul.eel-features' => 'text-align: {{VALUE}};',
		    ],
		]);

		$this->end_controls_section();

		$this->start_controls_section('section_ribbon', [
			'label' => __('Featured Settings', 'easy-elements'),
		]);
		$this->add_control('is_featured', [
			'label' => __('Featured', 'easy-elements'),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
		]);
		$this->add_control('ribbon_style', [
			'label' => __('Ribbon Style', 'easy-elements'),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'style1' => 'Style 1',
				'style2' => 'Style 2',
			],
			'default' => 'style1',
		]);
		$this->add_control('featured_tag_text', [
			'label' => __('Featured Text', 'easy-elements'),
			'type' => Controls_Manager::TEXT,
			'default' => 'Featured',
		]);
		$this->add_control('ribbon_alignment', [
			'label' => __('Alignment', 'easy-elements'),
			'type' => Controls_Manager::SELECT,
			'options' => [ 'left' => 'Left', 'right' => 'Right' ],
			'default' => 'right',
			'condition' => [
		        'ribbon_style' => 'style2',
		    ],
		]);

		$this->add_control(
		    'featured__text_color',
		    [
		        'label' => __('Text Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .featured.style2 .eel-ribbon, {{WRAPPER}} .eel-ribbon' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		$this->add_group_control(
		    Group_Control_Background::get_type(),
		    [
		        'name' => 'featured__bg',
		        'label' => __('Background', 'easy-elements'),
		        'types' => ['classic', 'gradient'],
		        'selector' => '{{WRAPPER}} .featured.style2 .eel-ribbon, {{WRAPPER}} .eel-ribbon',
			   'condition' => [
				   '!three_color_gradient' => '',
			   ],
		    ]
		);

		$this->add_control(
			'three_color_gradient',
			[
				'label' => __( 'Need 3 Color Gradient?', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'return_value' => 'gradient-bg',  
				'default'      => '',     
				
			]
		);

		$this->add_control(
			'gradient_position',
			[
				'label' => __( 'Gradient Position', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'gradient-left',
				'options' => [
					'gradient-left' => __( 'Left', 'easy-elements' ),
					'gradient-right' => __( 'Right', 'easy-elements' ),
				],
				'condition' => [
					'three_color_gradient' => 'gradient-bg',
				],
			]
		);

		// First color
		$this->add_control(
		'easy_gradient_color_1',
			[
			'label'     => esc_html__( 'Gradient 1', 'easy-elements' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#4750CC',
			'condition' => [
				'three_color_gradient' => 'gradient-bg',
				],
			'selectors' => [
				'{{WRAPPER}} .eel-button-gradient' => '--eel-gradient-1: {{VALUE}};',
				],
			]
		);

		// Second color
		$this->add_control(
			'easy_gradient_color_2',
			[
			'label'     => esc_html__( 'Gradient 2', 'easy-elements' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#EF5CE8',
			'condition' => [
				'three_color_gradient' => 'gradient-bg',
				],
			'selectors' => [
				'{{WRAPPER}} .eel-button-gradient' => '--eel-gradient-2: {{VALUE}};',
				],
			]
		);

		// Third color
		$this->add_control(
			'easy_gradient_color_3',
			[
			'label'     => esc_html__( 'Gradient 3', 'easy-elements' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#EFC7AE',
			'condition' => [
				'three_color_gradient' => 'gradient-bg',
				],
			'selectors' => [
				'{{WRAPPER}} .eel-button-gradient' => '--eel-gradient-3: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'featured__typography',
		        'label' => __('Typography', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .featured.style2 .eel-ribbon, {{WRAPPER}} .eel-ribbon',
		    ]
		);


		$this->add_responsive_control(
		    'fea_box_padding',
		    [
		        'label' => __('Padding', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%', 'em'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-ribbon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
	        	'condition' => [
	                'ribbon_style' => 'style1',
	            ],
		    ]
		);

		$this->add_control(
		    'fea_box_border_radius',
		    [
		        'label' => __('Border Radius', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-ribbon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
	        	'condition' => [
	                'ribbon_style' => 'style1',
	            ],
		    ]
		);

		$this->end_controls_section();

		// Button
		$this->start_controls_section('section_button', [
			'label' => __('Button Settings', 'easy-elements'),
		]);
		$this->add_control('show_button', [
			'label' => __('Show Button', 'easy-elements'),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default' => 'yes',
		]);
		$this->add_control(
			'gradient_hover',
			[
				'label' => __( 'Gradient Hover?', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'return_value' => 'gradient-hover',  
				'default'      => '',
				'condition' => [
					'show_button' => 'yes',
					'!eel-gradient-button' => '',
				],
			]	
		);
		$this->add_control(
			'eel-gradient-button',
			[
				'label' => __( 'Need Gradient Button?', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'return_value' => 'eel-button-gradient',  
				'default'      => '',
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		// First color
		$this->add_control(
		'easy_gradient_color_button_1',
			[
			'label'     => esc_html__( 'Gradient 1', 'easy-elements' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#4750CC',
			'condition' => [
				'show_button'         => 'yes',
            		'eel-gradient-button' => 'eel-button-gradient',
			],
			'selectors' => [
				'{{WRAPPER}} .eel-button-gradient' => '--eel-gradient-1: {{VALUE}};',
				],
			]
		);

		// Second color
		$this->add_control(
			'easy_gradient_color_button_2',
			[
			'label'     => esc_html__( 'Gradient 2', 'easy-elements' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#EF5CE8',
			'condition' => [
				'show_button'         => 'yes',
            		'eel-gradient-button' => 'eel-button-gradient',
				],
			'selectors' => [
				'{{WRAPPER}} .eel-button-gradient' => '--eel-gradient-2: {{VALUE}};',
				],
			]
		);

		// Third color
		$this->add_control(
			'easy_gradient_color_button_3',
			[
			'label'     => esc_html__( 'Gradient 3', 'easy-elements' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#EFC7AE',
			'condition' => [
				'show_button'         => 'yes',
            		'eel-gradient-button' => 'eel-button-gradient',
				],
			'selectors' => [
				'{{WRAPPER}} .eel-button-gradient' => '--eel-gradient-3: {{VALUE}};',
				],
			]
		);


		$this->add_control('button_text', [
			'label' => __('Text', 'easy-elements'),
			'type' => Controls_Manager::TEXT,
			'default' => 'Choose Plan',
		]);

		$this->add_control('button_subtext', [
		    'label' => __('Bottom Text', 'easy-elements'),
		    'type' => Controls_Manager::TEXT,
		    'default' => 'No credit card required!',
		    'placeholder' => __('Enter subtext below button', 'easy-elements'),
		]);

		$this->add_control('button_link', [
			'label' => __('Link', 'easy-elements'),
			'type' => Controls_Manager::URL,
			'default' => [ 'url' => '#' ],
		]);
		$this->add_control('button_icon', [
			'label' => __('Icon', 'easy-elements'),
			'type' => Controls_Manager::ICONS,
			'default' => [ 'value' => '', 'library' => 'solid' ],
		]);
		$this->add_control('button_icon_position', [
			'label' => __('Icon Position', 'easy-elements'),
			'type' => Controls_Manager::SELECT,
			'options' => [ 'before' => 'Before', 'after' => 'After' ],
			'default' => 'after',
		]);
		$this->add_responsive_control('button_icon_spacing', [
		    'label' => __('Icon Spacing', 'easy-elements'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
		        'px' => ['min' => 0, 'max' => 50],
		    ],
		    'default' => [],
		    'selectors' => [
		        '{{WRAPPER}} .eel-button .eel-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
		        '{{WRAPPER}} .eel-button .eel-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
		    ],
		]);

		$this->add_responsive_control('button_icon_spacing_vertical', [
		    'label' => __('Icon Vertical Spacing', 'easy-elements'),
		    'type' => \Elementor\Controls_Manager::SLIDER,
		    'range' => [
		        'px' => ['min' => 0, 'max' => 60],
		    ],
		    'selectors' => [
		        '{{WRAPPER}} .eel-button .eel-icon-before, {{WRAPPER}} .eel-button .eel-icon-after' => 'top: {{SIZE}}{{UNIT}}; position:relative;',
		    ],
		]);

		$this->add_responsive_control('button_position', [
		    'label' => __('Button Position', 'easy-elements'),
		    'type' => Controls_Manager::SELECT,
		    'options' => [
		        'after_features' => __('After Features', 'easy-elements'),
		        'in_features' => __('Before Features', 'easy-elements'),
		    ],
		    'default' => 'after_features',
		]);

		$this->add_responsive_control('btn_alignment', [
		    'label' => __('Alignment', 'easy-elements'),
		    'type' => \Elementor\Controls_Manager::CHOOSE,
		    'options' => [
		        'left' => [
		            'title' => __('Left', 'easy-elements'),
		            'icon' => 'eicon-text-align-left',
		        ],
		        'center' => [
		            'title' => __('Center', 'easy-elements'),
		            'icon' => 'eicon-text-align-center',
		        ],
		        'right' => [
		            'title' => __('Right', 'easy-elements'),
		            'icon' => 'eicon-text-align-right',
		        ],
		    ],
		    'default' => 'left',
		    'toggle' => true,
		    'selectors' => [
		        '{{WRAPPER}} .eel-btn-part' => 'text-align: {{VALUE}};',
		    ],
		]);

		$this->add_control('button_full_width', [
		    'label' => __('Enable Full Button Width', 'easy-elements'),
		    'type' => \Elementor\Controls_Manager::SWITCHER,
		    'label_on' => __('Yes', 'easy-elements'),
		    'label_off' => __('No', 'easy-elements'),
		    'return_value' => 'yes',
		    'default' => '',
		]);

		$this->end_controls_section();

		// Start Style Tab Section
		$this->start_controls_section(
		    'section_style_title_price',
		    [
		        'label' => __('Title & Price Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		    ]
		);

		// Title Style
		$this->add_control(
		    'title_heading',
		    [
		        'label' => __('Title', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::HEADING,
		        'separator' => 'before',
		    ]
		);
		$this->add_control(
		    'title_color',
		    [
		        'label' => __('Title Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-price-title' => 'color: {{VALUE}};',
		        ],
		    ]
		);
		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'title_typography',
		        'selector' => '{{WRAPPER}} .eel-price-title',
		    ]
		);

		$this->add_responsive_control(
		    'title_margin',
		    [
		        'label' => __('Margin', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', 'em', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-price-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		// Regular Price Style
		$this->add_control(
		    'price_heading',
		    [
		        'label' => __('Price', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::HEADING,
		        'separator' => 'before',
		    ]
		);
		$this->add_control(
		    'price_color',
		    [
		        'label' => __('Price Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-price .eel-amount' => 'color: {{VALUE}};',
		        ],
		    ]
		);
		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'price_typography',
		        'selector' => '{{WRAPPER}} .eel-price .eel-amount',
		    ]
		);

		$this->add_responsive_control(
		    'price__margin',
		    [
		        'label' => __('Margin', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', 'em', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		// Sale Price Del Style (optional)
		$this->add_control(
		    'sale_price_heading',
		    [
		        'label' => __('Regular (Sale) Price', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::HEADING,
		        'separator' => 'before',
		        'condition' => ['on_sale' => 'yes'],
		    ]
		);
		$this->add_control(
		    'regular_price_color',
		    [
		        'label' => __('Regular Price Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-sale-price' => 'color: {{VALUE}};',
		        ],
		        'condition' => ['on_sale' => 'yes'],
		    ]
		);
		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'regular_price_typography',
		        'selector' => '{{WRAPPER}} .eel-sale-price',
		        'condition' => ['on_sale' => 'yes'],
		    ]
		);

		// Period Style
		$this->add_control(
		    'period_heading',
		    [
		        'label' => __('Period', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::HEADING,
		        'separator' => 'before',
		    ]
		);
		$this->add_control(
		    'period_color',
		    [
		        'label' => __('Period Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-period' => 'color: {{VALUE}};',
		        ],
		    ]
		);
		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'period_typography',
		        'selector' => '{{WRAPPER}} .eel-period',
		    ]
		);

		$this->add_responsive_control(
		    'period_margin',
		    [
		        'label' => __('Margin', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', 'em', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-price .eel-period' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_control(
		    'currency_style_heading',
		    [
		        'label' => __('Currency Symbol', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::HEADING,
		        'separator' => 'before',
		    ]
		);

		$this->add_control(
		    'currency_symbol_color',
		    [
		        'label' => __('Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-currency' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'currency_symbol_typography',
		        'selector' => '{{WRAPPER}} .eel-currency',
		    ]
		);

		$this->add_responsive_control(
		    'currency_symbol_margin',
		    [
		        'label' => __('Currency Margin', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', 'em', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-currency' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_control(
		    'currency_symbol_vertical_position',
		    [
		        'label' => __('Vertical Position', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => ['px', '%'],
		        'range' => [
		            'px' => [
		                'min' => -50,
		                'max' => 50,
		            ],
		            '%' => [
		                'min' => -100,
		                'max' => 100,
		            ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-currency' => 'display: inline-block; transform: translateY({{SIZE}}{{UNIT}});',
		        ],
		    ]
		);

		$this->end_controls_section();


		$this->start_controls_section(
		    'section_features_style',
		    [
		        'label' => __('Features Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		    ]
		);

		// Text color
		$this->add_control(
		    'features_text_color',
		    [
		        'label' => __('Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-features li' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		// Text typography
		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'features_text_typography',
		        'selector' => '{{WRAPPER}} .eel-features li',
		    ]
		);

		// Features list border
		$this->add_group_control(
		    \Elementor\Group_Control_Border::get_type(),
		    [
		        'name' => 'features_border',
		        'label' => __('Border', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-features li',
		    ]
		);

		// Features list padding
		$this->add_responsive_control(
		    'features_padding',
		    [
		        'label' => __('Padding', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', 'em', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-features li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		// Features list margin
		$this->add_responsive_control(
		    'features_margin',
		    [
		        'label' => __('Margin', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', 'em', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-features li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
		    'section_button_style',
		    [
		        'label' => __('Button Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		    ]
		);

		$this->start_controls_tabs('button_style_tabs');

		// Normal Tab
		$this->start_controls_tab(
		    'button_style_normal',
		    [
		        'label' => __('Normal', 'easy-elements'),
		    ]
		);

		// Normal Text Color
		$this->add_control(
		    'button_text_color',
		    [
		        'label' => __('Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-button' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		$this->add_control(
		    'button_icon_color',
		    [
		        'label' => __('Icon Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-button .eel-icon-before, {{WRAPPER}} .eel-button .eel-icon-after' => 'color: {{VALUE}};',
		            '{{WRAPPER}} .eel-button .eel-icon-before svg, {{WRAPPER}} .eel-button .eel-icon-after svg' => 'fill: {{VALUE}};',
		        ],
		    ]
		);

		// Normal Background
		$this->add_group_control(
		    \Elementor\Group_Control_Background::get_type(),
		    [
		        'name' => 'button_background',
		        'label' => __('Background', 'easy-elements'),
		        'types' => ['classic', 'gradient'],
		        'selector' => '{{WRAPPER}} .eel-button',
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'button_typography',
		        'selector' => '{{WRAPPER}} .eel-button',
		    ]
		);

		// Normal Border
		$this->add_group_control(
		    \Elementor\Group_Control_Border::get_type(),
		    [
		        'name' => 'button_border',
		        'label' => __('Border', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-button',
		    ]
		);

		// Normal Box Shadow
		$this->add_group_control(
		    \Elementor\Group_Control_Box_Shadow::get_type(),
		    [
		        'name' => 'button_box_shadow',
		        'label' => __('Box Shadow', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-button',
		    ]
		);

		$this->end_controls_tab();

		// Hover Tab
		$this->start_controls_tab(
		    'button_style_hover',
		    [
		        'label' => __('Hover', 'easy-elements'),
		    ]
		);

		// Hover Text Color
		$this->add_control(
		    'button_text_color_hover',
		    [
		        'label' => __('Text Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-button:hover' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		// Hover Background
		$this->add_group_control(
		    \Elementor\Group_Control_Background::get_type(),
		    [
		        'name' => 'button_background_hover',
		        'label' => __('Background', 'easy-elements'),
		        'types' => ['classic', 'gradient'],
		        'selector' => '{{WRAPPER}} .eel-button:hover',
		    ]
		);

		// Hover Border
		$this->add_group_control(
		    \Elementor\Group_Control_Border::get_type(),
		    [
		        'name' => 'button_border_hover',
		        'label' => __('Border', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-button:hover',
		    ]
		);

		// Hover Box Shadow
		$this->add_group_control(
		    \Elementor\Group_Control_Box_Shadow::get_type(),
		    [
		        'name' => 'button_box_shadow_hover',
		        'label' => __('Box Shadow', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-button:hover',
		    ]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
		    'button_border_radius',
		    [
		        'label' => __('Border Radius', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%', 'em'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'button_padding',
		    [
		        'label' => __('Padding', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', 'em', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'button_margin',
		    [
		        'label' => __('Margin', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', 'em', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
		    'section_button_subtext_style',
		    [
		        'label' => __('Button Subtext Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		    ]
		);

		// Text Color
		$this->add_control(
		    'button_subtext_color',
		    [
		        'label' => __('Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-button-subtext' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		// Typography
		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'button_subtext_typography',
		        'label' => __('Typography', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-button-subtext',
		    ]
		);

		$this->add_responsive_control(
		    'button_margin_sub',
		    [
		        'label' => __('Margin', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', 'em', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-button-subtext' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->end_controls_section();

	}
	

	protected function render() {
	    $settings 		= $this->get_settings_for_display();
	    $currency      	= $settings['price_currency'];
	    $currency_pos  	= $settings['currency_placement'];
	    $period        	= $settings['price_period'];
	    $separator     	= $settings['period_separator'];
	    $is_featured   	= $settings['is_featured'] === 'yes';
	    $show_button   	= $settings['show_button'] === 'yes';
	    $button_pos    	= $settings['button_position'] ?? 'after_features';
	    $skin_style    	= $settings['skin_style'] ?? '';

	    $render_button = function() use ( $settings ) {
	        if ( $settings['show_button'] !== 'yes' ) {
	            return;
	        }
	        $full_width_class = ( !empty($settings['button_full_width']) && $settings['button_full_width'] === 'yes' ) ? 'eel--full-btn' : '';
	        ?>
	        <div class="eel-btn-part">
			   
				<?php if  ($settings['gradient_hover'] === 'gradient-hover' ) : ?>
				<!-- Gradient Hover Button -->
				<a href="<?php echo esc_url( $settings['button_link']['url'] ); ?>" class="eel-button eel-button-gradient-hover <?php echo esc_attr( $full_width_class ); ?>">
					
					<?php if ( $settings['button_icon_position'] === 'before' && ! empty( $settings['button_icon']['value'] ) ) : ?>
						<span class="eel-icon eel-icon-before elementor-icon">
							<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>

					<span><?php echo esc_html( $settings['button_text'] ); ?></span>
					<?php if ( $settings['button_icon_position'] === 'after' && ! empty( $settings['button_icon']['value'] ) ) : ?>
						<span class="eel-icon eel-icon-after elementor-icon">
							<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>
				</a>

				<?php elseif ( $settings['eel-gradient-button'] === 'eel-button-gradient' ) : ?>
				<!-- Gradient Button -->
				<a href="<?php echo esc_url( $settings['button_link']['url'] ); ?>" class="eel-button eel-button-gradient <?php echo esc_attr( $full_width_class ); ?>">
					
					<?php if ( $settings['button_icon_position'] === 'before' && ! empty( $settings['button_icon']['value'] ) ) : ?>
						<span class="eel-icon eel-icon-before elementor-icon">
							<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>

					<span><?php echo esc_html( $settings['button_text'] ); ?></span>

					<?php if ( $settings['button_icon_position'] === 'after' && ! empty( $settings['button_icon']['value'] ) ) : ?>
						<span class="eel-icon eel-icon-after elementor-icon">
							<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>
				</a>

				<?php else : ?>
				<!-- Default Button -->
				<a href="<?php echo esc_url( $settings['button_link']['url'] ); ?>" class="eel-button <?php echo esc_attr( $full_width_class ); ?>">
					
					<?php if ( $settings['button_icon_position'] === 'before' && ! empty( $settings['button_icon']['value'] ) ) : ?>
						<span class="eel-icon eel-icon-before elementor-icon">
							<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>

					<span><?php echo esc_html( $settings['button_text'] ); ?></span>

					<?php if ( $settings['button_icon_position'] === 'after' && ! empty( $settings['button_icon']['value'] ) ) : ?>
						<span class="eel-icon eel-icon-after elementor-icon">
							<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					<?php endif; ?>
				</a>
				<?php endif; ?>


				<?php if ( ! empty( $settings['button_subtext'] ) ) : ?>
				<div class="eel-button-subtext"><?php echo esc_html( $settings['button_subtext'] ); ?></div>
				<?php endif; ?>
		    </div>
	        <?php
	    };

	    ?>
	    <div class="eel-pricing-list <?php echo $is_featured ? 'featured ' . esc_attr($settings['ribbon_style']) : ''; ?> eel--<?php echo esc_attr($skin_style); ?>">
	        <?php if ($is_featured): ?>
	            <div class="eel-ribbon eel-<?php echo esc_attr($settings['ribbon_alignment']); ?> eel-<?php echo esc_attr($settings['three_color_gradient']); ?> eel-<?php echo esc_attr($settings['gradient_position']); ?>">
	                <?php echo esc_html($settings['featured_tag_text']); ?>
	            </div>
	        <?php endif; ?>

	        <div class="eel-price-wrap">
		        <h3 class="eel-price-title"><?php echo esc_html($settings['title']); ?></h3>
		        <div class="eel-price">
		            <?php if ( $settings['on_sale'] === 'yes' ) : ?>
		                <?php
		                $regular_price = $settings['regular_price'];
		                $sale_price    = $settings['sale_price'];
		                ?>

		                <span class="eel-old-price">
		                    <?php if ( $currency_pos === 'left' ) : ?>
		                        <span class="eel-currency"><?php echo esc_html( $currency ); ?></span><?php echo esc_html( $regular_price ); ?>
		                    <?php else : ?>
		                        <?php echo esc_html( $regular_price ); ?><span class="eel-currency"><?php echo esc_html( $currency ); ?></span>
		                    <?php endif; ?>
		                </span>

		                <span class="eel-sale-price">
		                    <?php if ( $currency_pos === 'left' ) : ?>
		                        <span class="eel-currency"><?php echo esc_html( $currency ); ?></span><?php echo esc_html( $sale_price ); ?>
		                    <?php else : ?>
		                        <?php echo esc_html( $sale_price ); ?><span class="eel-currency"><?php echo esc_html( $currency ); ?></span>
		                    <?php endif; ?>
		                </span>

		            <?php else : ?>
		                <span class="eel-amount">
		                    <?php if ( $currency_pos === 'left' ) : ?>
		                        <span class="eel-currency"><?php echo esc_html( $currency ); ?></span><?php echo esc_html( $settings['price'] ); ?>
		                    <?php else : ?>
		                        <?php echo esc_html( $settings['price'] ); ?><span class="eel-currency"><?php echo esc_html( $currency ); ?></span>
		                    <?php endif; ?>
		                </span>
		            <?php endif; ?>

		            <span class="eel-period"><?php echo esc_html( $separator . $period ); ?></span>
		        </div>
		    </div>

	        <?php 
	        	if ( $settings['button_position'] === 'in_features' ) {
    	            $render_button();
    	        }
	        ?>

	        <ul class="eel-features">
	            <?php foreach ( $settings['features_list'] as $feature ) : ?>
	                <li>
	                    <?php
	                    $icon_style_class = 'icon-only';
	                    if ( !empty( $settings['feature_icon_style'] ) ) {
	                        $icon_style_class = $settings['feature_icon_style'];
	                    }

	                    if ( !empty( $feature['icon']['value'] ) ) {
	                        Icons_Manager::render_icon( $feature['icon'], [
	                            'aria-hidden' => 'true',
	                            'class' => 'feature-icon ' . esc_attr( $icon_style_class ),
	                        ] );
	                    } else {
	                        echo '<i class="unicon-checkmark feature-icon ' . esc_attr( $icon_style_class ) . '" aria-hidden="true"></i>';
	                    }
	                    ?>
	                    <?php echo esc_html( $feature['text'] ); ?>
	                </li>
	            <?php endforeach; ?>
	        </ul>


	        <?php
	           if ( $settings['button_position'] === 'after_features' ) {
	               $render_button();
	           }
           	?>

	        
	    </div>
	    <?php
	}
}
