<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Search_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
		$handle = 'eel-search-style';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/search.css';
		
		if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
			Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
			return [ $handle ];
		}
		
		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style( $handle, plugins_url( 'css/search.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
		}	
		return [ $handle ];
	}

	public function get_script_depends() {
	    $handle = 'eel-search-script';
		$js_path = plugin_dir_path( __FILE__ ) . 'js/search.js';
		
		if ( get_option( 'easyel_elements_minify_js', '0' ) === '1' && class_exists( 'Easyel_Elements_JS_Loader_Helper' ) ) {
			Easyel_Elements_JS_Loader_Helper::easyel_elements_load_minified_inline_js( $handle, $js_path );
			return [ $handle ];
		}
		
		if ( ! wp_script_is( $handle, 'registered' ) && file_exists( $js_path ) ) {
			wp_register_script( $handle, plugins_url( 'js/search.js', __FILE__ ), [ 'jquery' ], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $js_path ) : '1.0.0', true );
		}
		return [ $handle ];
	}

	public function get_name() {
		return 'eel-search';
	}

	public function get_title() {
		return __( 'Easy Search', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_header_footer_category' ];
	}

	protected function register_controls() {

	    $this->start_controls_section(
	        'content_section',
	        [
	            'label' => esc_html__('Search Settings', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );

	    $this->add_control(
	        'search_placeholder',
	        [
	            'label' => esc_html__('Search Placeholder', 'easy-elements'),
	            'type' => Controls_Manager::TEXT,
	            'default' => esc_html__('Type keywords here...', 'easy-elements'),
	        ]
	    );

	    $this->add_control(
	        'open_icon',
	        [
	            'label' => esc_html__('Search Icon', 'easy-elements'),
	            'type' => \Elementor\Controls_Manager::ICONS,
	            'default' => [
	                'value' => 'fas fa-search',
	                'library' => 'fa-solid',
	            ],
	        ]
	    );

		$this->add_control(
	        'search_icon_size',
	        [
	            'label' => esc_html__('Icon Size (px)', 'easy-elements'),
	            'type' => Controls_Manager::SLIDER,
	            'range' => [
	                'px' => [
	                    'min' => 10,
	                    'max' => 100,
	                ],
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .eel-search-open-btn i, {{WRAPPER}} .eel-search-open-btn svg' => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
	            ],
	        ]
	    );

	    $this->add_control(
	        'search_icon_vertical_position',
	        [
	            'label' => esc_html__('Icon Vertical Position (px)', 'easy-elements'),
	            'type' => Controls_Manager::SLIDER,
	            'range' => [
	                'px' => [
	                    'min' => -50,
	                    'max' => 50,
	                ],
	            ],
	            'default' => [
	                'unit' => 'px',
	                'size' => 0,
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .eel-search-open-btn' => 'transform: translateY({{SIZE}}{{UNIT}});',
	            ],
	        ]
	    );


	    $this->add_control(
	        'close_icon',
	        [
	            'label' => esc_html__('Close Icon', 'easy-elements'),
	            'type' => \Elementor\Controls_Manager::ICONS,
	            'default' => [
	                'value' => 'fas fa-arrow-up', 
	                'library' => 'fa-solid', 
	            ],
	        ]
	    );

	    $this->add_control(
	        'icon_size',
	        [
	            'label' => esc_html__('Close Icon Size (px)', 'easy-elements'),
	            'type' => Controls_Manager::SLIDER,
	            'range' => [
	                'px' => [
	                    'min' => 10,
	                    'max' => 100,
	                ],
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .eel-search-close-btn i, {{WRAPPER}} .eel-search-close-btn svg' => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
	            ],
	        ]
	    );


	    $this->add_control(
	        'overlay_background',
	        [
	            'label' => esc_html__('Overlay Background', 'easy-elements'),
	            'type' => Controls_Manager::COLOR,
	            'default' => '',
	            'selectors' => [
	                '{{WRAPPER}} .eel-search-lightbox, {{WRAPPER}} .eel-search-overlay' => 'background: {{VALUE}};',
	            ],
	        ]
	    );


	    $this->add_control(
	        'input_text_color',
	        [
	            'label' => esc_html__('Input Text Color', 'easy-elements'),
	            'type' => Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .eel-search-content .eel-search-field' => 'color: {{VALUE}};',
	            ],
	        ]
	    );

	    $this->add_control(
	        'input_bg_color',
	        [
	            'label' => esc_html__('Input Background Color', 'easy-elements'),
	            'type' => Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .eel-search-content .eel-search-field' => 'background-color: {{VALUE}};',
	            ],
	        ]
	    );

	    $this->add_control(
	        'submit_button_color',
	        [
	            'label' => esc_html__('Submit Button Background', 'easy-elements'),
	            'type' => Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .eel-search-content .eel-search-submit' => 'background-color: {{VALUE}};',
	            ],
	        ]
	    );

	    $this->end_controls_section();
	}


	protected function render() {
	    $settings = $this->get_settings_for_display();
	    ?>

	    <a href="#" role="button" class="eel-search-open-btn" aria-label="<?php esc_attr_e('Open Search', 'easy-elements'); ?>">
	        <?php \Elementor\Icons_Manager::render_icon( $settings['open_icon'], [ 'aria-hidden' => 'true' ] ); ?>
	    </a>

	    <div class="eel-search-lightbox">
	        <div class="eel-search-overlay">
	            <a href="#" role="button" class="eel-search-close-btn" aria-label="<?php esc_attr_e('Close Search', 'easy-elements'); ?>">
	                <?php \Elementor\Icons_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
	            </a>
	        </div>
	        <div class="eel-search-content">
				<h4><?php esc_html_e('What are you looking for?', 'easy-elements'); ?></h4>	            
	            <form role="search" method="get" class="eel-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	                <input type="search" class="eel-search-field" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ?? 'Type keywords here...' ); ?>" value="" name="s" />
	                <button type="submit" class="eel-search-submit" aria-label="Submit Search">
	                    <i class="eel-absl unicon-search"></i>
	                </button>
	            </form>
	        </div>
	    </div>

	    <?php
	}
}
