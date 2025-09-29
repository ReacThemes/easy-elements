<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Scroll_To_Top_Widget extends \Elementor\Widget_Base {


	public function get_style_depends() {
        $handle = 'eel-scroll-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/scroll.css';
        
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/scroll.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
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
			'section_scroll_style',
			[
				'label' => __( 'Scroll Button', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		?>
		<?php
	}
}

add_action( 'wp_footer', function() {
	echo '<div id="easyel-top-to-bottom"><i class="unicon-arrow-up"></i></div>';
});
?>