<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Service_List_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
		$handle = 'eel-service-list-style';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/service-list.css';
		
		if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
			Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
			return [ $handle ];
		}
		
		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style( $handle, plugins_url( 'css/service-list.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
		}	
		return [ $handle ];
	}

	public function get_name() {
		return 'eel-service-list';
	}

	public function get_title() {
		return __( 'Easy Service List', 'easy-elements' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'easy-elements' ),
			]
		);

		// service style
		$this->add_control(
			'skin_style',
			[
				'label' => __( 'Skin', 'easy-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'skin1' => __( 'Skin 1', 'easy-elements' ),
					'skin2' => __( 'Skin 2', 'easy-elements' ),
					'skin3' => __( 'Skin 3', 'easy-elements' ),
				],
				'default' => 'skin1',
				'toggle' => false,
				'prefix_class' => 'eel-service-list-wrapper-',
			]
		);
		
		$this->add_control(
		    'media_type',
		    [
		        'label' => __('Choose Media Type', 'easy-elements'),
		        'type' => Controls_Manager::CHOOSE,
		        'options' => [
		            'icon' => [
		                'title' => __('Icon Box', 'easy-elements'),
		                'icon' => 'eicon-star',
		            ],
		            'image' => [
		                'title' => __('Image Box', 'easy-elements'),
		                'icon' => 'eicon-image',
		            ],
		            'image_only' => [
		                'title' => __('Only Image', 'easy-elements'),
		                'icon' => 'eicon-image',
		            ],
		            'number' => [
		                'title' => __('Number', 'easy-elements'),
		                'icon' => 'eicon-number-field',
		            ],
		        ],
		        'default' => 'icon',
		        'toggle' => false,
		    ]
		);


		$this->add_control(
		    'service_icon',
		    [
		        'label' => __('Icon', 'easy-elements'),
		        'type' => Controls_Manager::ICONS,
		        'condition' => [
		            'media_type' => 'icon',
		        ],
		        'default' => [
		            'value' => 'fas fa-star',
		            'library' => 'fa-solid',
		        ],
		    ]
		);

		$this->add_control(
		    'service_image',
		    [
		        'label' => __('Image', 'easy-elements'),
		        'type' => Controls_Manager::MEDIA,
		        'default' => [
		            'url' => \Elementor\Utils::get_placeholder_image_src(),
		        ],
		        'condition' => [
		            'media_type' => ['image', 'image_only'],
		        ],
		    ]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Managed IT Services', 'easy-elements' ),
			]
		);

		$this->add_control(
		    'number',
		    [
		        'label' => __('Number', 'easy-elements'),
		        'type' => Controls_Manager::TEXT,
		        'condition' => [ 'media_type' => 'number' ],
		        'default' => '1',
		    ]
		);

		$this->add_control(
		    'title_tag',
		    [
		        'label' => __('Title HTML Tag', 'easy-elements'),
		        'type' => Controls_Manager::SELECT,
		        'options' => [
		            'h1'   => 'H1',
		            'h2'   => 'H2',
		            'h3'   => 'H3',
		            'h4'   => 'H4',
		            'h5'   => 'H5',
		            'h6'   => 'H6',
		            'div'  => 'div',
		            'span' => 'span',
		            'p'    => 'p',
		        ],
		        'default' => 'h3',
		    ]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Description', 'easy-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Professional monitoring and management of your IT systems 24/7/365, ensuring optimal performance and security.', 'easy-elements' ),
			]
		);

		$this->add_control(
			'readmore_type',
			[
				'label' => __( 'Read More Type', 'easy-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'readmore' => __( 'Read More', 'easy-elements' ),
					'icon' => __( 'Only Icon', 'easy-elements' ),
				],
				'default' => 'readmore',
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label' => __( 'Read More Text', 'easy-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Read More', 'easy-elements' ),
				'condition' => [ 'readmore_type' => 'readmore' ],
			]
		);

		$this->add_control(
			'readmore_icon',
			[
				'label' => __( 'Only Icon', 'easy-elements' ),
				'type' => Controls_Manager::ICONS,
				'condition' => [ 'readmore_type' => 'icon' ],
			]
		);

		$this->add_control(
			'readmore_icon_color',
			[
				'label' => __( 'Icon Color', 'easy-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-service-list .eel-only-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-service-list .eel-only-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-service-list .eel-only-icon svg path' => 'fill: {{VALUE}};',
				],
				'condition' => [ 'readmore_type' => 'icon' ],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'readmore_icon_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-service-list .eel-only-icon span',
				'condition' => [ 'readmore_type' => 'icon' ],
			]
		);

		$this->add_responsive_control(
			'circle_size_only_icon',
			[
				'label' => __('Circle Size', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .eel-only-icon span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [ 'readmore_type' => 'icon' ],
			]
		);

		$this->add_responsive_control(
			'circle_border_radius_only_icon',
			[
				'label' => __('Border Radius', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .eel-service-list .eel-only-icon span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [ 'readmore_type' => 'icon' ],
			]
		);



		$this->add_responsive_control(
			'circle_icon_size_only',
			[
				'label' => __('Icon Size', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0.5,
						'max' => 5,
						'step' => 0.1,
					],
					'rem' => [
						'min' => 0.5,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 25,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .eel-only-icon span i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-only-icon span svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-only-icon span svg path' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [ 'readmore_type' => 'icon' ],
			]
		);

		$this->add_control(
			'readmore_link',
			[
				'label' => __( 'Read More URL', 'easy-elements' ),
				'type' => Controls_Manager::URL,
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'show_external' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
		    'section_icon_style',
		    [
		        'label' => __('Icon Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		        'condition' => ['media_type' => 'icon'],
		    ]
		);

		$this->add_control(
		    'icon_color',
		    [
		        'label' => __('Icon Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel-service-icon i' => 'color: {{VALUE}};',
		            '{{WRAPPER}} .eel-service-list .eel-service-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-service-list .eel-service-icon svg path' => 'fill: {{VALUE}};',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Background::get_type(),
		    [
		        'name' => 'icon_background',
		        'label' => __('Background', 'easy-elements'),
		        'types' => ['classic', 'gradient'],
		        'selector' => '{{WRAPPER}} .eel-service-list .eel-icon-img-wrap',
		    ]
		);

		$this->add_responsive_control(
		    'icon_size',
		    [
		        'label' => __('Icon Size', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'range' => [
		            'px' => ['min' => 10, 'max' => 200],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel-service-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'icon_padding',
		    [
		        'label' => __('Icon Padding', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%', 'em'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel-icon-img-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'icon_border_radius',
		    [
		        'label' => __('Border Radius', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel-icon-img-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->end_controls_section();


		$this->start_controls_section(
		    'section_title_style',
		    [
		        'label' => __('Title Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		    ]
		);

		$this->add_control(
		    'title_color',
		    [
		        'label' => __('Title Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel-service-title' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'title_typography',
		        'label' => __('Title Typography', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-service-list .eel-service-title',
		    ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
		    'section_circle_style',
		    [
		        'label' => __('Circle Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		    ]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'circle_bg_group',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-service-list .eel-icon-img-wrap, {{WRAPPER}} .eel-service-list-skin2 .eel-icon-img-wrap',
			]
		);

		$this->add_control(
			'circle_size',
			[
				'label' => __('Circle Size', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eel-service-list .eel-icon-img-wrap, {{WRAPPER}} .eel-service-list-skin2 .eel-icon-img-wrap' => 'max-width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'circle_border_radius',
			[
				'label' => __('Border Radius', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .eel-service-list .eel-icon-img-wrap, {{WRAPPER}} .eel-service-list-skin2 .eel-icon-img-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'circle_padding',
			[
				'label' => __('Padding', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .eel-service-list .eel-icon-img-wrap, {{WRAPPER}} .eel-service-list-skin2 .eel-icon-img-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'circle_border',
				'selector' => '{{WRAPPER}} .eel-service-list .eel-icon-img-wrap, {{WRAPPER}} .eel-service-list-skin2 .eel-icon-img-wrap',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
		    'section_number_style',
		    [
		        'label' => __('Number Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		        'condition' => [ 'media_type' => 'number' ],
		    ]
		);

		$this->add_control(
		    'number_color',
		    [
		        'label' => __('Number Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel--number' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'number_typography',
				'label' => __('Number Typography', 'easy-elements'),
				'selector' => '{{WRAPPER}} .eel-service-list .eel--number',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
		    'section_description_style',
		    [
		        'label' => __('Description Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		    ]
		);

		$this->add_control(
		    'desc_color',
		    [
		        'label' => __('Description Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel-service-des .eel-des, {{WRAPPER}} .eel-service-list-skin2 .eel-des' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'desc_typography',
		        'label' => __('Description Typography', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-service-list .eel-service-des .eel-des, {{WRAPPER}} .eel-service-list-skin2 .eel-des',
		    ]
		);

		$this->add_responsive_control(
		    'description_margin',
		    [
		        'label' => __('Margin', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%', 'em'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list-skin2 .eel-des' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
		    'section_readmore_style',
		    [
		        'label' => __('Read More Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		    ]
		);

		$this->start_controls_tabs('tabs_readmore_style');

		/**
		 * Normal Tab
		 */
		$this->start_controls_tab(
		    'tab_readmore_normal',
		    [
		        'label' => __('Normal', 'easy-elements'),
		    ]
		);

		$this->add_control(
		    'readmore_color',
		    [
		        'label' => __('Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel-service-des .eel-readmore' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'readmore_typography',
		        'label' => __('Typography', 'easy-elements'),
		        'selector' => '{{WRAPPER}} .eel-service-list .eel-service-des .eel-readmore',
		    ]
		);

		$this->end_controls_tab();

		/**
		 * Hover Tab
		 */
		$this->start_controls_tab(
		    'tab_readmore_hover',
		    [
		        'label' => __('Hover', 'easy-elements'),
		    ]
		);

		$this->add_control(
		    'readmore_hover_color',
		    [
		        'label' => __('Color', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel-service-des .eel-readmore:hover' => 'color: {{VALUE}};',
		        ],
		    ]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
		    'section_image_style',
		    [
		        'label' => __('Image Style', 'easy-elements'),
		        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		        'condition' => ['media_type' => ['image', 'image_only']],
		    ]
		);

		$this->add_responsive_control(
		    'image_width',
		    [
		        'label' => __('Image Height', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'range' => [
		            'px' => ['min' => 10, 'max' => 500],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel-icon-img-wrap img' => 'height: {{SIZE}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'image_border_radius',
		    [
		        'label' => __('Border Radius', 'easy-elements'),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => ['px', '%'],
		        'selectors' => [
		            '{{WRAPPER}} .eel-service-list .eel-icon-img-wrap img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		    ]
		);

		$this->end_controls_section();

		// others style
		$this->start_controls_section(
			'section_others_style',
			[
				'label' => __('Others Style', 'easy-elements'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [ 'skin_style' => 'skin2' ],
			]
		);

		// Circle style
		$this->add_control(
			'circle_hover_color',
			[
				'label' => __('Circle Hover Bg Color', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-service-list:hover .eel-icon-img-wrap' => 'background-color: {{VALUE}} !important; border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'line_color',
			[
				'label' => __('Line Color Normal', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-service-list-skin2:before' => 'border-bottom-color: {{VALUE}};',		
				],
			]
		);

		$this->add_control(
			'line_color_hover',
			[
				'label' => __('Line Color Hover', 'easy-elements'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-service-list-skin2:after' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
	    $settings = $this->get_settings_for_display();
	    $title     = $settings['title'];
	    $desc      = $settings['description'];
	    $readmore  = $settings['readmore_text'];
	    $link      = $settings['readmore_link']['url'];
	    $is_external = !empty($settings['readmore_link']['is_external']) ? 'target="_blank"' : '';
	    $nofollow    = !empty($settings['readmore_link']['nofollow']) ? 'rel="nofollow"' : '';

	    $title_tag = $settings['title_tag'];
	    $title     = $settings['title'];
	    $image_only = ( isset($settings['media_type']) && $settings['media_type'] === 'image_only' ) ? 'eel-image-only' : '';

	    $skin = $settings['skin_style'] ?? 'skin1';
	    $template_path = plugin_dir_path(__FILE__) . 'skins/' . $skin . '.php';

	    if ( file_exists( $template_path ) ) {
			?>			
			<div class="eel-service-list eel-service-list-<?php echo esc_attr($skin); ?>">
				<?php include $template_path; ?>
			</div>
			<?php
	    }
	}
}
