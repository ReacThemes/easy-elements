<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Button_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
	    $handle = 'eel-button-style';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/button.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/button.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }
	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-button';
	}

	public function get_title() {
		return __( 'Easy Button', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	protected function register_controls() {

	    $this->start_controls_section(
	        'content_section',
	        [
	            'label' => esc_html__('Button Settings', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );

	    $this->add_control(
	        'button_text',
	        [
	            'label' => esc_html__('Button Text', 'easy-elements'),
	            'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
	            'default' => esc_html__('Click Here', 'easy-elements'),
	            'placeholder' => esc_html__('Enter button text', 'easy-elements'),
	        ]
	    );

		$this->add_control(
			'button_url',
			[
				'label' => esc_html__('Button URL', 'easy-elements'),
				'type' => Controls_Manager::URL,
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$this->add_control(
			'button_type',
			[
				'label' => esc_html__('Button Type', 'easy-elements'),
				'type' => Controls_Manager::SELECT,
				'default' => 'primary',
				'options' => [
					'primary' => esc_html__('Primary', 'easy-elements'),
					'outline' => esc_html__('Outline', 'easy-elements'),
					'icon_btn' => esc_html__('Icon', 'easy-elements'),
				],
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label' => esc_html__('Icon', 'easy-elements'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => '',
					'library' => '',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__('Icon Position', 'easy-elements'),
				'type' => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before' => esc_html__('Before Text', 'easy-elements'),
					'after' => esc_html__('After Text', 'easy-elements'),
				],
				'condition' => [
					'button_icon[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'button_min_width',
			[
				'label' => esc_html__('Minimum Width', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-button' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'show_gradient',
            [
                'label' => esc_html__( 'Gradient Button', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => '',
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
				'show_gradient' => 'yes',
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
				'show_gradient' => 'yes',
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
				'show_gradient' => 'yes',
				],
			'selectors' => [
				'{{WRAPPER}} .eel-button-gradient' => '--eel-gradient-3: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Button
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__('Button Style', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .eel-button',
			]
		);

		$this->start_controls_tabs('button_styles');

		$this->start_controls_tab(
			'button_normal',
			[
				'label' => esc_html__('Normal', 'easy-elements'),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__('Text Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eel-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__('Background Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eel-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .eel-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__('Border Radius', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .eel-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .eel-button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__('Padding', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .eel-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__('Margin', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .eel-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover',
			[
				'label' => esc_html__('Hover', 'easy-elements'),
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => esc_html__('Text Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eel-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label' => esc_html__('Background Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eel-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_hover_border',
				'selector' => '{{WRAPPER}} .eel-button:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		// Icon Style Section
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__('Icon Style', 'easy-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__('Icon Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eel-button i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-button svg path' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .eel-button .elementor-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button .elementor-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-button .elementor-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-before svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-after svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-before i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-after i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button .elementor-icon-wrapper' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button .elementor-icon-wrapper svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label' => esc_html__('Icon Hover Color', 'easy-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eel-button:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-button:hover svg path' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .eel-button:hover .elementor-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button:hover .elementor-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-button:hover .elementor-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button:hover .eel-button-icon-before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button:hover .eel-button-icon-after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button:hover .eel-button-icon-before svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-button:hover .eel-button-icon-after svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-before i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-after i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button .elementor-icon-wrapper' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-button .elementor-icon-wrapper svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__('Icon Size', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 50,
					],
					'em' => [
						'min' => 0.5,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-before' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-after' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-before svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-after svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-before i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-after i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => esc_html__('Icon Spacing', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-button .eel-button-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button .eel-button-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button i.eel-button-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button i.eel-button-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button svg.eel-button-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-button svg.eel-button-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_rotation',
			[
				'label' => esc_html__('Default Icon Rotation', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'deg' => [
						'min' => -360,
						'max' => 360,
					],
				],
				'default' => [
					'unit' => 'deg',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-button i' => 'transition: all 0.3s ease-in; transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .eel-button svg' => 'transition: all 0.3s ease-in; transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'hover_icon_rotation',
			[
				'label' => esc_html__('Hover Icon Rotation', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'deg' => [
						'min' => -360,
						'max' => 360,
					],
				],
				'default' => [
					'unit' => 'deg',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-button:hover i' => 'transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .eel-button:hover svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		// Get button URL
		$button_url = $settings['button_url']['url'] ?? '#';
		$is_external = $settings['button_url']['is_external'] ?? false;
		$nofollow = $settings['button_url']['nofollow'] ?? false;
		
		// Build target attribute
		$target = $is_external ? '_blank' : '_self';
		
		// Build rel attribute
		$rel = [];
		if ($is_external) {
			$rel[] = 'noopener';
		}
		if ($nofollow) {
			$rel[] = 'nofollow';
		}

		$rel_attr = !empty($rel) ? implode(' ', array_map('sanitize_html_class', $rel)) : '';

		
		// Get button classes
		$button_classes = ['eel-button'];
		if (!empty($settings['button_type'])) {
			$button_classes[] = 'eel-button-' . $settings['button_type'];
		}

		if (!empty( $settings['show_gradient']) && 'yes' === $settings['show_gradient'] ) {
			$button_classes[] = 'eel-button-gradient';
		}
		
		?>
		<a href="<?php echo esc_url( $button_url ); ?>"
			class="<?php echo esc_attr( implode(' ', $button_classes ) ); ?>"
			target="<?php echo esc_attr( $target ); ?>"
			<?php if ( $rel_attr ) : ?> rel="<?php echo esc_attr( $rel_attr ); ?> <?php endif; ?>">
			<?php if (!empty($settings['button_icon']['value']) && $settings['icon_position'] === 'before'): ?>
				<span class="eel-button-icon-before">
					<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php endif; ?>
			
			<span class="eel-button-text"><?php echo esc_html( $settings['button_text'] ); ?></span>
			
			<?php if (!empty( $settings['button_icon']['value'] ) && $settings['icon_position'] === 'after'): ?>
				<span class="eel-button-icon-after">
					<?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php endif; ?>
		</a>
		<?php
	}
}
