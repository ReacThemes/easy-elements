<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Clients_Logo__Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-clients-logo-grid-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/clients-logo.css';
        
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/clients-logo.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }
        return [ $handle ];
    }

    public function get_name() {
        return 'eel-clients-logo-grid';
    }

    public function get_title() {
        return esc_html__( 'Client Logo Grid', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'logo', 'clients', 'brand', 'partner', 'image' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_logo',
            [
                'label' => esc_html__( 'Logo Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        // Repeater
        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Logo', 'easy-elements'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'easy_logo_list',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ image.url }}}',
                'default' => array_fill(0, 4, [
                    'image' => ['url' => Utils::get_placeholder_image_src()],
                ]),
            ]
        );


        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', 
                'default' => 'full',
            ]
        );

        $this->add_control(
            'image_size_width',
            [
                'label' => esc_html__('Image Size', 'easy-elements'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 500 ],
                    'em' => [ 'min' => 0, 'max' => 30 ],
                    '%'  => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .e-e-grid-img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'fetchpriority',
            [
                'label' => __('Image Fetch Priority', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    ''      => __('Default', 'easy-elements'),
                    'high'  => __('High', 'easy-elements'),
                    'low'   => __('Low', 'easy-elements'),
                ],
                'default' => 'low',
            ]
        );


        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Select Columns', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'tablet_default' => '3',
                'mobile_default' => '2',
                'options' => [
                    '1' => '1 Column',
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                    '5' => '5 Columns',
                    '6' => '6 Columns',
                ],
                'selectors' => [
                    '{{WRAPPER}} .e-e-clients-logo .grid-item' => 'width: calc(100% / {{VALUE}});',
                ],
            ]
        );
        $this->end_controls_section();

        // STYLE 
        $this->start_controls_section(
            'section_item_style',
            [
                'label' => __( 'Style', 'easy-elements' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
         );
            $this->add_responsive_control(
                'item_width',
                [
                    'label' => esc_html__( 'Item Width', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );            
            $this->add_responsive_control(
                'item_height',
                [
                    'label' => esc_html__( 'Item Height', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img' => 'height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img .e-e-grid-img' => 'height: 100%;',
                    ],
                ]
            );            
            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => esc_html__( 'Space', 'easy-elements' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .e-e-clients-logo .grid-wrap .grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_padding_inner',
                [
                    'label' => esc_html__( 'Padding', 'easy-elements' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item__border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Item Normal and Hover Options
            $this->start_controls_tabs( 'item_normal_hover_style' );

                $this->start_controls_tab(
                    'item_normal_style',
                    [
                        'label' => esc_html__( 'Normal', 'easy-elements' ),
                    ]
                 );
                    $this->add_control(
                        'item_bg',
                        [
                            'label' => esc_html__( 'Item Background', 'easy-elements' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        \Elementor\Group_Control_Border::get_type(),
                        [
                            'name' => 'item_border',
                            'selector' => '{{WRAPPER}} .ee--logo-img',
                        ]
                    );
                    $this->add_group_control(
                        \Elementor\Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'item_box_shadow',
                            'selector' => '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img',
                        ]
                    );
                    $this->add_control(
                        'item_opacity',
                        [
                            'label' => esc_html__( 'Opacity', 'easy-elements' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0.1,
                                    'max' => 1,
                                    'step' => .1,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img img' => 'opacity: {{SIZE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'item_transform_scale',
                        [
                            'label' => esc_html__( 'Transform Scale', 'easy-elements' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0.1,
                                    'max' => 2,
                                    'step' => .1,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img img' => 'transform: scale({{SIZE}});',
                            ],
                        ]
                    );
                    $this->add_control(
                        'image_grayscale',
                        [
                            'label' => esc_html__( 'Image Grayscale', 'easy-elements' ),
                            'type' => Controls_Manager::SWITCHER,
                            'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                            'label_off' => esc_html__( 'No', 'easy-elements' ),
                            'return_value' => 'yes',
                        ]
                    );
                    $this->add_control(
                        'image_grayscale_option',
                        [
                            'label' => __('Grayscale', 'easy-elements'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'normal_grayscale',
                            'options' => [
                                'normal_grayscale'  => __('Default Grayscale', 'easy-elements'),
                                'hover_grayscale'   => __('Hover Grayscale', 'easy-elements'),
                                'hover_to_default'   => __('Hover to Default Image', 'easy-elements'),
                            ],                
                            'condition' => [
                                'image_grayscale' => 'yes',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        \Elementor\Group_Control_Css_Filter::get_type(),
                        [
                            'name' => 'item_css_filters',
                            'selector' => '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img img',
                            'condition' => [
                                'image_grayscale!' => 'yes',
                            ],
                        ]
                    );


                $this->end_controls_tab();

                // Hover Styele 
                $this->start_controls_tab(
                    'item_hover_style',
                    [
                        'label' => esc_html__( 'Hover', 'easy-elements' ),
                    ]
                 );
                    $this->add_control(
                        'item_hover_bg',
                        [
                            'label' => esc_html__( 'Item Hover BG', 'easy-elements' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        \Elementor\Group_Control_Border::get_type(),
                        [
                            'name' => 'item_hover_border',
                            'selector' => '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img:hover',
                        ]
                    );
                    $this->add_group_control(
                        \Elementor\Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'item_hover_box_shadow',
                            'selector' => '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img:hover',
                        ]
                    );
                    $this->add_control(
                        'item_hover_opacity',
                        [
                            'label' => esc_html__( 'Opacity', 'easy-elements' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0.1,
                                    'max' => 1,
                                    'step' => .1,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img:hover img' => 'opacity: {{SIZE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'item_hover_transform_scale',
                        [
                            'label' => esc_html__( 'Transform Scale', 'easy-elements' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0.1,
                                    'max' => 2,
                                    'step' => .1,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img:hover img' => 'transform: scale({{SIZE}});',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        \Elementor\Group_Control_Css_Filter::get_type(),
                        [
                            'name' => 'item_hover_css_filters',
                            'selector' => '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img:hover img',
                            'condition' => [
                                'image_grayscale!' => 'yes',
                            ],
                        ]
                    );
                    $this->add_control(
                        'item_hover_transition',
                        [
                            'label' => esc_html__( 'Transition', 'easy-elements' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0.1,
                                    'max' => 5,
                                    'step' => .1,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img, {{WRAPPER}} .e-e-clients-logo .grid-item .ee--logo-img .e-e-grid-img' => 'transition: all {{SIZE}}s ease;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['easy_logo_list'] ) ) {
            return;
        }

        ?>
        
        <div class="e-e-clients-logo grid-layout">
            <div class="grid-wrap">
                <?php foreach ( $settings['easy_logo_list'] as $item ) :
                    $image_id = $item['image']['id'] ?? '';
                    $image_size = isset( $settings['image_size'] ) ? $settings['image_size'] : 'full';
                    $image_data = $image_id ? wp_get_attachment_image_src( $image_id, $image_size ) : '';
                    $alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
                    $title = $image_id ? get_the_title( $image_id ) : '';

                    $link     = $item['link']['url'] ?? '';
                    $target   = ! empty( $item['link']['is_external'] ) ? '_blank' : '';
                    $nofollow = ! empty( $item['link']['nofollow'] ) ? 'nofollow' : '';
                    $fetchpriority = $settings['fetchpriority'] ?? '';

                    $image_grayscale = !empty( $settings['image_grayscale_option'] ) ? $settings['image_grayscale_option'] : '';
                    ?>
                    <div class="grid-item">
                        <div class="ee--logo-img <?php echo esc_attr($image_grayscale)?>">
                            <?php if ( $link ) : ?>
                                <a href="<?php echo esc_url( $link ); ?>"
                                   <?php if ( $target ) : ?>target="<?php echo esc_attr( $target ); ?>"<?php endif; ?>
                                   <?php if ( $nofollow ) : ?>rel="<?php echo esc_attr( $nofollow ); ?>"<?php endif; ?>>
                            <?php endif; ?>   

                            <?php if ( $image_data ) : ?>
                                <img class="e-e-grid-img"
                                src="<?php echo esc_url( $image_data[0] ); ?>"
                                width="<?php echo esc_attr( $image_data[1] ); ?>"
                                height="<?php echo esc_attr( $image_data[2] ); ?>"
                                alt="<?php echo esc_attr( $alt ); ?>"
                                title="<?php echo esc_attr( $title ); ?>"
                                loading="lazy"
                                decoding="async" fetchpriority="<?php echo esc_attr( $fetchpriority ); ?>">
                            <?php endif; ?>                          

                            <?php if ( $link ) : ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
} ?>