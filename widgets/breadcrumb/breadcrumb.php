<?php
/**
 * Easy Elements Breadcrumb Widget
 *
 * @package EasyElements
 */
include 'helper.php';
use Elementor\Utils;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Easyel_Breadcrumb_Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-breadcrumb';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/breadcrumb.css';
        
        if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
            Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
            return [ $handle ];
        }

        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/breadcrumb.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */

    public function get_name() {
        return 'eel-breadcrumb';
    }   


    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Easy Breadcrumb', 'easy-elements' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'easy-elements-icon';
    }


    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'breadcrumb_section',
            [
                'label' => esc_html__( 'Content', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'home_icon_picker',
            [
                'label' => esc_html__( 'Home Icon (Icon Picker)', 'easy-elements' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
                'description' => esc_html__('Pick an icon for the home.', 'easy-elements'),
            ]
        );

        $this->add_control(
            'icon_size_',
            [
                'label' => esc_html__( 'Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-home-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .breadcrumb-home-icon svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_size_postition',
            [
                'label' => esc_html__( 'Top/Bottom Position', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-home-icon' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_size_postition_left',
            [
                'label' => esc_html__( 'Left/Right Position', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-home-icon' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'home_title',
            [
                'label' => esc_html__( 'Home Page Title', 'easy-elements' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Home', 'easy-elements' ),
                'placeholder' => esc_html__( 'Home', 'easy-elements' ),
            ]
        );

        $this->add_control(
            'show_category_path',
            [
                'label' => esc_html__( 'Show Category Path', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'separator_icon',
            [
                'label' => esc_html__( 'Separator Icon', 'easy-elements' ),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->end_controls_section();

        // Style Section for Colors
        $this->start_controls_section(
            'breadcrumb_style_section',
            [
                'label' => esc_html__( 'Style', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'breadcrumb_text_color',
            [
                'label' => esc_html__( 'Text Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb, {{WRAPPER}} .eel-breadcrumb a' => 'color: {{VALUE}};',
                ],
            ]
        );

         $this->add_control(
            'brea_text_active_color',
            [
                'label' => esc_html__( 'Active Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'breadcrumb_icon_color',
            [
                'label' => esc_html__( 'Home Icon Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-home-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-home-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'breadcrumb_typography',
                'selector' => '{{WRAPPER}} .eel-breadcrumb a, {{WRAPPER}} .eel-breadcrumb, {{WRAPPER}} .eel-breadcrumb span',        
            ]
        );
        $this->add_control(
            'breadcrumb_separator_color',
            [
                'label' => esc_html__( 'Separator Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-breadcrumb .breadcrumb-separator' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
			'separator_icon_gap',
			[
				'label'      => __( 'Separator Gap', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-breadcrumb .breadcrumb-separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $show_category_path = $settings['show_category_path'] === 'yes';
        $home_icon_picker = isset($settings['home_icon_picker']) ? $settings['home_icon_picker'] : '';
        $home_title = !empty($settings['home_title']) ? $settings['home_title'] : '';
        $custom_separator = isset($settings['separator_icon']) ? $settings['separator_icon'] : '';

        // Inline style for custom colors
        $text_color = !empty($settings['breadcrumb_text_color']) ? $settings['breadcrumb_text_color'] : '';
        $icon_color = !empty($settings['breadcrumb_icon_color']) ? $settings['breadcrumb_icon_color'] : '';
        $separator_color = !empty($settings['breadcrumb_separator_color']) ? $settings['breadcrumb_separator_color'] : '';
        $custom_css = '';
        if ($text_color) {
            $custom_css .= '.eel-breadcrumb { color: ' . esc_attr($text_color) . '; }';
        }
        if ($icon_color) {
            $custom_css .= '.eel-breadcrumb .breadcrumb-icon { color: ' . esc_attr($icon_color) . '; }';
        }
        if ($separator_color) {
            $custom_css .= '.eel-breadcrumb .breadcrumb-separator { color: ' . esc_attr($separator_color) . '; }';
        }
        if ($custom_css) {
            echo '<style>' . $custom_css . '</style>';
        }

        if ( function_exists( 'get_easyel_breadcrumb' ) ) {
            echo get_easyel_breadcrumb('', '', $custom_separator, $home_title, '', '', $show_category_path, '', $home_icon_picker);
        } else {
            echo '<!-- Breadcrumb function not found -->';
        }
    }
}
?>