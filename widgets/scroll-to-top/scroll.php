<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Scroll_To_Top_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
		$handle   = 'eel-scroll-style';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/scroll.css';

		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style(
				$handle,
				plugins_url( 'css/scroll.css', __FILE__ ),
				[],
				defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0'
			);
		}
		return [ $handle ];
	}

	public function get_script_depends() {
		$handle  = 'eel-scroll-script';
		$js_path = plugin_dir_path( __FILE__ ) . 'js/scroll.js';

		if ( ! wp_script_is( $handle, 'registered' ) && file_exists( $js_path ) ) {
			wp_register_script(
				$handle,
				plugins_url( 'js/scroll.js', __FILE__ ),
				[ 'jquery' ],
				defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $js_path ) : '1.0.0',
				true
			);
		}
		return [ $handle ];
	}

	public function get_name() {
		return 'eel-scroll-to-top';
	}

	public function get_title() {
		return __( 'Scroll to Top', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	protected function register_controls() {
		// Example: Style section for button
		$this->start_controls_section(
			'section_scroll_setting',
			[
				'label' => __( 'Scroll Button', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'scroll_btn_icon',
			[
				'label'   => __( 'Icon', 'easy-elements' ),
				'type'    => Controls_Manager::ICONS,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_scroll_style',
			[
				'label' => __( 'Scroll Button', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'scroll_btn_color',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #easyel-top-to-bottom i' => 'color: {{VALUE}};',
					'{{WRAPPER}} #easyel-top-to-bottom svg, {{WRAPPER}} #easyel-top-to-bottom svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'scroll_btn_bg',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #easyel-top-to-bottom' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div id="easyel-top-to-bottom">
			<?php
			if ( ! empty( $settings['scroll_btn_icon']['value'] ) ) {
				\Elementor\Icons_Manager::render_icon(
					$settings['scroll_btn_icon'],
					[ 'aria-hidden' => 'true' ]
				);
			} else {
				?>
				<i class="unicon-arrow-up"></i>
				<?php
			}
			?>
		</div>
		<?php
	}
}
?>