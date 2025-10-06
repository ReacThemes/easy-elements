<?php
/**
 * Easy Elements Social Icon Widget
 * 
 * A custom Elementor widget for displaying social media icons with links.
 * Features customizable colors, hover effects, and responsive design.
 * 
 * @package EasyElements
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

/**
 * 
 * Handles the social icon widget functionality for Elementor.
 */
class Easyel_Social_Icon_Widget extends \Elementor\Widget_Base {
	
	/**
	 * Get widget style dependencies
	 * 
	 * Loads the CSS file for the widget styling
	 * 
	 * @return array Array of style handles
	*/
	public function get_style_depends() {
		$handle = 'eel-social-icon';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/social.css';
		
		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style( $handle, plugins_url( 'css/social.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }
        return [ $handle ];
    }

	/**
	 * Get widget name
	 * 
	 * @return string Widget name
	 */
	public function get_name() {
		return 'eel-social-icon';
	}

	/**
	 * Get widget title
	 * 
	 * @return string Widget title
	 */
	public function get_title() {
		return __( 'Social Icon', 'easy-elements' );
	}

	/**
	 * Get widget icon
	 * 
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'easy-elements-icon';
	}

	/**
	 * Get widget categories
	 * 
	 * @return array Widget categories
	 */
	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	/**
	 * Register widget controls
	 * 
	 * Defines all the controls for the widget
	 */
	protected function register_controls() {
		// ========================================
		// CONTENT SECTION - Social Settings
		// ========================================
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Social Settings', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'social_links',
			[
				'label'       => esc_html__( 'Social Links', 'easy-elements' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => [
					// Link Title Field
					[
						'name'    => 'link_title',
						'label'   => esc_html__( 'Link Title', 'easy-elements' ),
						'type'    => Controls_Manager::TEXT,
						'default' => esc_html__( 'Social Link', 'easy-elements' ),
					],
					// Link URL Field
					[
						'name'    => 'link_url',
						'label'   => esc_html__( 'Link URL', 'easy-elements' ),
						'type'    => Controls_Manager::URL,
						'default' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => false,
						],
					],
					// Icon Field
					[
						'name'    => 'icon',
						'label'   => esc_html__( 'Icon', 'easy-elements' ),
						'type'    => Controls_Manager::ICONS,
						'default' => [
							'value'   => 'fab fa-facebook-f',
							'library' => 'fa-brands',
						],
					],
					// Background Color Field
					[
						'name'    => 'background_color',
						'label'   => esc_html__( 'Background Color', 'easy-elements' ),
						'type'    => Controls_Manager::COLOR,
						'default' => '#1877F2',
					],
					// Icon Color Field
					[
						'name'    => 'icon_color',
						'label'   => esc_html__( 'Icon Color', 'easy-elements' ),
						'type'    => Controls_Manager::COLOR,
						'default' => '#ffffff',
					],
					// Hover Background Color Field
					[
						'name'    => 'hover_background_color',
						'label'   => esc_html__( 'Hover Background Color', 'easy-elements' ),
						'type'    => Controls_Manager::COLOR,
						'default' => '#166fe5',
					],
					// Hover Icon Color Field
					[
						'name'    => 'hover_icon_color',
						'label'   => esc_html__( 'Hover Icon Color', 'easy-elements' ),
						'type'    => Controls_Manager::COLOR,
						'default' => '#ffffff',
					],
				],
				// Default social links
				'default'     => [
					[
						'link_title'              => esc_html__( 'Facebook', 'easy-elements' ),
						'link_url'                => [ 'url' => '#' ],
						'icon'                    => [ 'value' => 'fab fa-facebook-f' ],
						'background_color'        => '#1877F2',
						'icon_color'              => '#ffffff',
						'hover_background_color'  => '#166fe5',
						'hover_icon_color'        => '#ffffff',
					],
					[
						'link_title'              => esc_html__( 'Twitter', 'easy-elements' ),
						'link_url'                => [ 'url' => '#' ],
						'icon'                    => [ 'value' => 'fab fa-twitter' ],
						'background_color'        => '#1DA1F2',
						'icon_color'              => '#ffffff',
						'hover_background_color'  => '#1a91da',
						'hover_icon_color'        => '#ffffff',
					],
					[
						'link_title'              => esc_html__( 'Instagram', 'easy-elements' ),
						'link_url'                => [ 'url' => '#' ],
						'icon'                    => [ 'value' => 'fab fa-instagram' ],
						'background_color'        => '#E4405F',
						'icon_color'              => '#ffffff',
						'hover_background_color'  => '#d63384',
						'hover_icon_color'        => '#ffffff',
					],
				],
				'title_field' => '{{{ link_title }}}',
			]
		);

		// Color Mode Control
		$this->add_control(
			'color_mode',
			[
				'label'   => esc_html__( 'Color Mode', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom'  => esc_html__( 'Custom Colors (Per Item)', 'easy-elements' ),
					'global'  => esc_html__( 'Global Colors', 'easy-elements' ),
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// ========================================
		// STYLE SECTION - Buttons Style
		// ========================================
		$this->start_controls_section(
			'buttons_style_section',
			[
				'label' => esc_html__( 'Buttons Style', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Button Size Control
		$this->add_responsive_control(
			'button_size',
			[
				'label'      => esc_html__( 'Button Size', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min'  => 30,
						'max'  => 100,
						'step' => 1,
					],
					'em' => [
						'min'  => 2,
						'max'  => 6,
						'step' => 0.1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-social-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Button Spacing Control
		$this->add_responsive_control(
			'button_spacing',
			[
				'label'      => esc_html__( 'Button Spacing', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-social-buttons' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Border Radius Control
		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => '50',
					'right'    => '50',
					'bottom'   => '50',
					'left'     => '50',
					'unit'     => '%',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-social-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border Control
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .eel-social-button',
			]
		);

		// Box Shadow Control
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .eel-social-button',
			]
		);

		// Global Background Color Control (only when global mode is selected)
		$this->add_control(
			'button_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1877F2',
				'condition' => [
					'color_mode' => 'global',
				],
				'selectors' => [
					'{{WRAPPER}} .eel-social-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Global Hover Background Color Control
		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => esc_html__( 'Hover Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#166fe5',
				'condition' => [
					'color_mode' => 'global',
				],
				'selectors' => [
					'{{WRAPPER}} .eel-social-button:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		// Global Icon Color Control
		$this->add_control(
			'button_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'condition' => [
					'color_mode' => 'global',
				],
				'selectors' => [
					'{{WRAPPER}} .eel-social-button i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-social-button svg' => 'fill: {{VALUE}};',
				],
			]
		);

		// Global Hover Icon Color Control
		$this->add_control(
			'button_icon_hover_color',
			[
				'label'     => esc_html__( 'Hover Icon Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'condition' => [
					'color_mode' => 'global',
				],
				'selectors' => [
					'{{WRAPPER}} .eel-social-button:hover i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-social-button:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		// Icon Size Control
		$this->add_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'easy-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 50,
						'step' => 1,
					],
					'em' => [
						'min'  => 0.5,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors'  => [
					'{{WRAPPER}} .eel-social-button i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-social-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend
	 * 
	 * Generates the HTML markup for the social icons
	 */
	protected function render() {
    $settings = $this->get_settings_for_display();
    
    if ( empty( $settings['social_links'] ) ) {
        return;
    }

    $color_mode = $settings['color_mode'] ?? 'custom';

    echo '<div class="eel-social-share"><div class="eel-social-buttons">';

    foreach ( $settings['social_links'] as $index => $link ) {
        $link_title = $link['link_title'] ?? '';
        $link_url   = $link['link_url']['url'] ?? '#';
        $is_external = $link['link_url']['is_external'] ?? false;
        $nofollow    = $link['link_url']['nofollow'] ?? false;

        $target = $is_external ? '_blank' : '_self';
        $rel = '';
        if ( $is_external && $nofollow ) $rel = 'nofollow noopener';
        elseif ( $is_external ) $rel = 'noopener';
        elseif ( $nofollow ) $rel = 'nofollow';

        $unique_class = 'eel-social-custom-' . $index;
        $inline_style = '';
        $hover_bg = '#166fe5';
        $hover_icon = '#ffffff';
        $bg_color = '#1877F2';
        $icon_color = '#ffffff';

        if ( $color_mode === 'custom' ) {
            $bg_color = $link['background_color'] ?? $bg_color;
            $icon_color = $link['icon_color'] ?? $icon_color;
            $hover_bg = $link['hover_background_color'] ?? $hover_bg;
            $hover_icon = $link['hover_icon_color'] ?? $hover_icon;

            $inline_style = sprintf(
                'background-color: %s; color: %s;',
                esc_attr($bg_color),
                esc_attr($icon_color)
            );
        }

        echo '<a href="' . esc_url($link_url) . '" class="eel-social-button ' . esc_attr($unique_class) . '"';
        echo ' target="' . esc_attr($target) . '"';
        if ( $rel ) echo ' rel="' . esc_attr($rel) . '"';
        echo ' title="' . esc_attr($link_title) . '"';
        if ( $inline_style ) echo ' style="' . esc_attr($inline_style) . '"';
        echo '>';

        \Elementor\Icons_Manager::render_icon( $link['icon'], [
            'aria-hidden' => 'true',
            'style' => 'color:' . esc_attr($icon_color) . '; fill:' . esc_attr($icon_color) . ';',
        ]);

        echo '</a>';

        // Add hover styles dynamically
        if ( $color_mode === 'custom' ) {
            echo '<style>
                .' . esc_attr($unique_class) . ':hover {
                    background-color: ' . esc_attr($hover_bg) . ' !important;
                }
                .' . esc_attr($unique_class) . ':hover i,
                .' . esc_attr($unique_class) . ':hover svg {
                    color: ' . esc_attr($hover_icon) . ' !important;
                    fill: ' . esc_attr($hover_icon) . ' !important;
                }
            </style>';
        }
    }

    echo '</div></div>';
}
}
