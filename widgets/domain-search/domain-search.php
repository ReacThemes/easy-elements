<?php
use Elementor\Utils;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Easyel_Domain_Search_Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-domain-search';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/domain-search.css';

        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/domain-search.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
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
        return 'eel-domain-search';
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
        return esc_html__( 'Domain Search', 'easy-elements' );
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
            'domain_section',
            [
                'label' => esc_html__( 'Content Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

       $this->add_control(
            'action_url',
            [
                'label'       => esc_html__( 'Action URL', 'easy-elements' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://example.com/search?domain=', 'easy-elements' ),
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        // Input Placeholder
        $this->add_control(
            'placeholder_text',
            [
                'label'       => esc_html__( 'Placeholder', 'easy-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Search domain...', 'easy-elements' ),
            ]
        );

        // Button Text
        $this->add_control(
            'button_text',
            [
                'label'       => esc_html__( 'Button Text', 'easy-elements' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Create my website', 'easy-elements' ),
            ]
        );

        // Input text color
        $this->add_control(
            'input_text_color',
            [
                'label' => __('Input Text Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Input background
        $this->add_control(
            'input_bg_color',
            [
                'label' => __('Input Background', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'input_border',
                'label' => __( 'Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-domain-search form input[type="text"]',
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label' => __( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => __( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form input[type="text"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        // Start Button Tabs
        $this->start_controls_tabs('button_style_tabs');

        // Normal tab
        $this->start_controls_tab(
            'button_normal_tab',
            [
                'label' => __('Normal', 'easy-elements'),
            ]
        );

        // Button background color (Normal)
        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Button text color (Normal)
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typography',
                'label'    => __( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-domain-search form button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => __( 'Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-domain-search form button',
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => __( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __( 'Padding', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover tab
        $this->start_controls_tab(
            'button_hover_tab',
            [
                'label' => __('Hover', 'easy-elements'),
            ]
        );

        // Button background color (Hover)
        $this->add_control(
            'button_bg_hover_color',
            [
                'label' => __('Background', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Button text color (Hover)
        $this->add_control(
            'button_text_hover_color',
            [
                'label' => __('Text Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-domain-search form button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_border_hover',
                'label' => __( 'Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-domain-search form button:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings    = $this->get_settings_for_display();
        $base_url    = ! empty( $settings['action_url']['url'] ) ? esc_url( $settings['action_url']['url'] ) : '#';
        $placeholder = ! empty( $settings['placeholder_text'] ) ? $settings['placeholder_text'] : 'Search domain...';
        $button_text = ! empty( $settings['button_text'] ) ? $settings['button_text'] : 'Create my website';
        ?>
        <div class="eel-domain-search">
            <form method="get" action="<?php echo esc_url( $base_url ); ?>" target="_blank">
                <i class="unicon-search"> </i>
                <input type="text" name="domain" placeholder="<?php echo esc_attr( $placeholder ); ?>" required> 
                <button type="submit"> <?php echo esc_html( $button_text ); ?></button>
            </form>
        </div>
        <?php
    }
}
?>