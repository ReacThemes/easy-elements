<?php
defined('ABSPATH') || die();

use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

class Easyel_Rotate_Text__Widget extends \Elementor\Widget_Base
{
	public function get_style_depends() {
		$handle = 'eel-rotate-text';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/rotate-text.css';

		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style( $handle, plugins_url( 'css/rotate-text.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
		}

		return [ $handle ];
	}
	
    public function get_name() {
        return 'rt-rotate-text';
    }

    public function get_title() {
        return esc_html__( 'Easy Rotate Text', 'easy-elements' );
    }

	public function get_icon() {
      return 'easy-elements-icon';
   }

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
		return [ 'round', 'rotate' ];
	}

	protected function _register_controls() {

	/*--------------------------------------*/
	/* 🔹 CONTENT SECTION
	/*--------------------------------------*/
	$this->start_controls_section(
		'section_content',
		[
			'label' => esc_html__( 'Content', 'rtelements' ),
		]
	);

	$this->add_control(
		'text',
		[
			'label' => esc_html__( 'Text', 'rtelements' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'label_block' => true,	
			'default' => esc_html__( 'Transforming Smile • Transforming Smile', 'rtelements' ),
		]
	);

	$this->add_control(
		'icon',
		[
			'label' => esc_html__( 'Icon', 'rtelements' ),
			'type'  => \Elementor\Controls_Manager::ICONS,
		]
	);

	$this->end_controls_section();

	/*--------------------------------------*/
	/*  STYLE SECTION - TEXT AREA
	/*--------------------------------------*/
	$this->start_controls_section(
		'section_txt_style',
		[
			'label' => esc_html__( 'Text', 'rtelements' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_control(
		'text_color',
		[
			'label' => esc_html__( 'Text Color', 'rtelements' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'separator' => 'before',
			'selectors' => [
				'{{WRAPPER}} .rts_rotate_text text' => 'fill: {{VALUE}};',
			],
		]
	);

	$this->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		[
			'name' => 'text_typography',
			'selector' => '{{WRAPPER}} .rts_rotate_text text',
		]
	);
	$this->end_controls_section();

	$this->start_controls_section(
		'section_icon_style',
		[
			'label' => esc_html__( 'Icon', 'rtelements' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_control(
		'icon_color',
		[
			'label' => esc_html__( 'Color', 'rtelements' ),
			'type'  => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .rts_rotate_text .rts__icon svg' => 'fill: {{VALUE}};',
				'{{WRAPPER}} .rts_rotate_text .rts__icon svg path' => 'fill: {{VALUE}};',
			],
		]
	);

	$this->add_responsive_control(
		'icon_width',
		[
			'label' => esc_html__( 'Size', 'rtelements' ),
			'type'  => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .rts_rotate_text .rts__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		]
	);
	$this->end_controls_section();

	$this->start_controls_section(
		'section_text_style',
		[
			'label' => esc_html__( 'Wrapper', 'rtelements' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_control(
		'content_area_bg_color',
		[
			'label'     => esc_html__( 'Background Color', 'rtelements' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'separator' => 'before',
			'selectors' => [
				'{{WRAPPER}} .rts_rotate_text' => 'background: {{VALUE}};',
			],
		]
	);

	$this->add_responsive_control(
		'content_area_width',
		[
			'label' => esc_html__( 'Width', 'rtelements' ),
			'type'  => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 1000,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .rts_rotate_text' => 'width: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'content_area_height',
		[
			'label' => esc_html__( 'Height', 'rtelements' ),
			'type'  => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 1000,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .rts_rotate_text' => 'height: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'content_area_border_radius',
		[
			'label' => esc_html__( 'Border Radius', 'rtelements' ),
			'type'  => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .rts_rotate_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'content_area_padding',
		[
			'label' => esc_html__( 'Padding', 'rtelements' ),
			'type'  => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .rts_rotate_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	$this->end_controls_section();
	
}
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="rts_rotate_text" aria-label="Rotating Text Circle">
			<svg class="spinner" viewBox="0 0 100 100" role="img" focusable="false">
				<defs>
					<path id="circlePath" d="M50,50 m-37,0a37,37 0 1,1 74,0a37,37 0 1,1 -74,0"></path>
				</defs>
				<text>
					<textPath href="#circlePath">
						<?php echo esc_html( $settings['text'] ?? 'Creative Design Studio' ); ?>
					</textPath>
				</text>
			</svg>

			<span class="rts__icon" aria-hidden="true">
				<?php 
				if ( ! empty( $settings['icon'] ) && ! empty( $settings['icon']['value'] ) ) {
					\Elementor\Icons_Manager::render_icon(
						$settings['icon'],
						[
							'aria-hidden' => 'true',
							'class'       => 'rts-icon optimized-icon',
							'loading'     => 'lazy',
						]
					);
				} else {
					// lightweight fallback SVG icon
					echo '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" role="img" focusable="false">
							<path d="M13 16.17L18.36 10.81L19.78 12.22L12 20L4.22 12.22L5.64 10.81L11 16.17V4H13V16.17Z"/>
						</svg>';
				}
				?>
			</span>
		</div>


	<?php
	}
}