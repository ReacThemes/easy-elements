<?php
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || die();
class Easyel_Team_Grid__Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
		$handle = 'eel-team-grid';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/team-grid.css';		
		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style( $handle, plugins_url( 'css/team-grid.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : time() );
		}	
		return [ $handle ];
	}

	public function get_script_depends() {
	    $handle = 'eel-team-grid-script';
		$js_path = plugin_dir_path( __FILE__ ) . 'js/team.js';		
		if ( ! wp_script_is( $handle, 'registered' ) && file_exists( $js_path ) ) {
			wp_register_script( $handle, plugins_url( 'js/team.js', __FILE__ ), [ 'jquery' ], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $js_path ) : '1.0.0', true );
		}
		return [ $handle ];
	}

    public function get_name() {
        return 'eel-team-grid';
    }

    public function get_title() {
        return esc_html__( 'Team Grid', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'member', 'team', 'brand', 'partner', 'image' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            '_section_team',
            [
                'label' => esc_html__( 'Team Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
         );
            $this->add_control(
                'team_skin',
                [
                    'label'   => esc_html__('Skin Type', 'easy-elements'),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default'   => esc_html__('Default', 'easy-elements'),
                        'skin1' => esc_html__('Skin 01', 'easy-elements'),
                        'skin2' => esc_html__('Skin 02', 'easy-elements'),
                        'skin3' => esc_html__('Skin 03', 'easy-elements'),
                    ],
                ]
            );

            $this->add_control(
                'image',
                [
                    'label' => esc_html__('Image', 'easy-elements'),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_control(
                '_name',
                [
                    'label' => esc_html__( 'Name', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__( 'Harry Nelson', 'easy-elements' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'title_tag',
                [
                    'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'h4',
                    'options' => [
                        'h1' => 'H1',
                        'h2' => 'H2',
                        'h3' => 'H3',
                        'h4' => 'H4',
                        'h5' => 'H5',
                        'h6' => 'H6',
                        'div' => 'div',
                        'span' => 'span',
                        'p'   => 'p',
                    ],
                ]
            );

            $this->add_control(
                'designation',
                [
                    'label' => esc_html__('Designation', 'easy-elements'),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Head of Operations', 'easy-elements' ),
                    'label_block' => true,
                ]
            );
            $this->add_control(
                'link',
                [
                    'label' => esc_html__('Link', 'easy-elements'),
                    'type' => Controls_Manager::URL,
                    'placeholder' => 'https://example.com',
                    'description' => esc_html__('You can add a page link here, such as the team member\'s profile page.', 'easy-elements'),
                    'condition' => [
                        'action_type' => 'link',
                    ],
                ]
            );
            $this->add_control(
                'details',
                [
                    'label' => esc_html__('Details', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'default' => '',
                    'rows' => 4,
                    'placeholder' => esc_html__('Enter team member details here...', 'easy-elements'),
                    'label_block' => true,
                    'description' => esc_html__('This field is shown in the popup when Action Type is set to Popup.', 'easy-elements'),
                ]
            );
            $this->add_control(
                'action_type',
                [
                    'label' => esc_html__('Action Type', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'link' => esc_html__('Link', 'easy-elements'),
                        'popup' => esc_html__('Popup', 'easy-elements'),
                    ],
                    'default' => 'link',
                    'description' => esc_html__('Choose between link or popup action.', 'easy-elements'),
                ]
            );
            $this->add_control(
                'content_show',
                [
                    'label' => esc_html__('Content Show', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'inside',
                    'options' => [
                        'inside' => esc_html__('Inside Image', 'easy-elements'),
                        'normal' => esc_html__('Normal', 'easy-elements'),
                    ],
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

            $this->add_control(
                'image_overlay_note',
                [
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<div class="eel-popup-note">' . esc_html__('Note: The Image Overlay supports both classic (solid color) and gradient backgrounds.', 'easy-elements') . '</div>',
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'image_overlay',
                    'label' => esc_html__('Image Overlay', 'easy-elements'),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ee--team-img .eel-image-overlay',
                ]
            );
            $this->add_control(
                'show_social_icon',
                [
                    'label' => esc_html__( 'Show Social Icon', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'easy-elements' ),
                    'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

        $this->end_controls_section();

        // Social Item 
        $this->start_controls_section(
			'social_icon_section',
			[
				'label' => esc_html__( 'Social Settings', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
                'condition' => [ 
                    'show_social_icon' => 'yes',
                ],
			]
		 );
            $this->add_control(
                'social_icon_position',
                [
                    'label' => esc_html__('Icon Position', 'easy-elements'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => esc_html__('Default', 'easy-elements'),
                        't_left' => esc_html__('Top Left', 'easy-elements'),
                        't_right' => esc_html__('Top Right', 'easy-elements'),
                    ],
                    'seperatop' => 'before',
                ]
            );

            $this->add_control(
                'social_links',
                [
                    'label'       => esc_html__( 'Social Links', 'easy-elements' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => [
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
                        [
                            'name'    => 'icon',
                            'label'   => esc_html__( 'Icon', 'easy-elements' ),
                            'type'    => Controls_Manager::ICONS,
                            'default' => [
                                'value'   => 'fab fa-facebook-f',
                                'library' => 'fa-brands',
                            ],
                        ],
                    ],
                    'default'     => [
                        [
                            'link_url'                => [ 'url' => '#' ],
                            'icon'                    => [ 'value' => 'fab fa-facebook-f' ],
                        ],
                    ],
                    'title_field' => '<i class="{{ icon.value }}"></i> {{{ icon.value.replace(/^(fab fa-|fas fa-|far fa-|fa fa-)/, "") }}}',
                ]
            );

		$this->end_controls_section();

        // Tab STYLE 
        $this->start_controls_section(
            'section_item_per',
            [
                'label' => esc_html__( 'Team Item', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );
        
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name'     => 'team_wrap_background',
                    'label'    => __( 'Background', 'easy-elements' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ee--team-img',
                ]
            );

            $this->add_control(
                'item_padding',
                [
                    'label' => esc_html__( 'Padding', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--team-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'item_bdr_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--team-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();
        
        // Image
        $this->start_controls_section(
            'section_image',
            [
                'label' => esc_html__( 'Image', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );
            $this->add_responsive_control(
                'image_width',
                [
                    'label' => esc_html__( 'Image Width', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em', '%' ],
                    'range' => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--team-img .eel-team-img-box' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_height',
                [
                    'label' => esc_html__( 'Image Height', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em', '%' ],
                    'range' => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--team-img .eel-team-img-box img' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--team-img .eel-team-img-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Name & Designation 
        $this->start_controls_section(
            'section_item',
            [
                'label' => esc_html__( 'Name & Designation Area', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );

            $this->add_control(
                '_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ee--team-img .eel-name-deg-wrap' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'wrap_padding',
                [
                    'label' => esc_html__( 'Padding', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--team-img .eel-name-deg-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'wrap_margin',
                [
                    'label' => esc_html__( 'Margin', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--team-img .eel-name-deg-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                '_bdr_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--team-img .eel-name-deg-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'team_content_alignment',
                [
                    'label' => esc_html__( 'Alignment', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'easy-elements' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'easy-elements' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'easy-elements' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ee--team-img .eel-name-deg-wrap' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Name
        $this->start_controls_section(
            'section_name',
            [
                'label' => esc_html__( 'Name', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );

            $this->add_control(
                'name_color',
                [
                    'label' => esc_html__( 'Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-name' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'name_typography',
                    'label' => esc_html__( 'Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-name',
                ]
            );

            $this->add_control(
                'name_padding',
                [
                    'label' => esc_html__( 'Padding', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Designation
        $this->start_controls_section(
            'section_designation',
            [
                'label' => esc_html__( 'Designation', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );

            $this->add_control(
                'designation_color',
                [
                    'label' => esc_html__( 'Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-designation' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'designation_typography',
                    'label' => esc_html__( 'Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-designation',
                ]
            );

        $this->end_controls_section();

        // Social Item
        $this->start_controls_section(
            'section_social',
            [
                'label' => esc_html__( 'Social Icon', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
         );

            $this->add_control(
                's_icon_color',
                [
                    'label' => esc_html__( 'Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-social ul li a' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                's_icon_hover_color',
                [
                    'label' => esc_html__( 'Hover Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-social ul li a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                's_icon_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-social ul li a' => 'background: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                's_icon_hover_bg_color',
                [
                    'label' => esc_html__( 'Hover Background Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-social ul li a:hover' => 'background: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 's_icon_typography',
                    'label' => esc_html__( 'Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-team-social ul li a',
                ]
            );
            $this->add_responsive_control(
                's_icon_gap',
                [
                    'label' => esc_html__( 'Icon Gap', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-social ul' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                's_icon_width-height',
                [
                    'label' => esc_html__( 'Button Size', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-social ul li a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                's_icon_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-social ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                's_icon_area_margin',
                [
                    'label' => esc_html__( 'Area Margin', 'easy-elements' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .eel-team-social' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();
        
        // Popup
        $this->start_controls_section(
            'section_popup_style',
            [
                'label' => esc_html__( 'Popup Style', 'easy-elements' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'action_type' => 'popup',
                ],
            ]
         );

            $this->add_control(
                'popup_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-popup-content' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'popup_name_color',
                [
                    'label' => esc_html__( 'Name Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-popup-name' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'popup_name_typography',
                    'label' => esc_html__( 'Name Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-popup-name',
                ]
            );

            $this->add_control(
                'popup_designation_color',
                [
                    'label' => esc_html__( 'Designation Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-popup-designation' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'popup_designation_typography',
                    'label' => esc_html__( 'Designation Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-popup-designation',
                ]
            );

            $this->add_control(
                'popup_details_color',
                [
                    'label' => esc_html__( 'Details Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-popup-details' => 'color: {{VALUE}};',
                    ],
                ]
            );           
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'popup_details_typography',
                    'label' => esc_html__( 'Details Typography', 'easy-elements' ),
                    'selector' => '{{WRAPPER}} .eel-popup-details',
                ]
            );
            $this->add_control(
                'popup_close_color',
                [
                    'label' => esc_html__( 'Close Icon Color', 'easy-elements' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eel-popup-close' => 'color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $team_skin = $settings['team_skin'];
        ?>
        
        <div class="eel-team-wraps grid-layout layout-<?php echo esc_attr( $team_skin )?>">
            <div class="grid-wrap">
                <?php
                $image_id = $settings['image']['id'] ?? '';
                $image_size = $settings['image_size'] ?? 'full';
                if ( $image_id ) {
                    $image_data = wp_get_attachment_image_src( $image_id, $image_size );
                    $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                    $title = get_the_title( $image_id );
                } else {
                    $fallback_url = Utils::get_placeholder_image_src();
                    $image_data = [ $fallback_url, 600, 400 ];
                    $alt = esc_attr__( 'Team Image', 'easy-elements' );
                    $title = esc_attr__( 'Team Image', 'easy-elements' );
                }

                $action_type = $settings['action_type'] ?? 'link';
                $link     = $settings['link']['url'] ?? '';
                $target   = ! empty( $settings['link']['is_external'] ) ? '_blank' : '';
                $nofollow = ! empty( $settings['link']['nofollow'] ) ? 'nofollow' : '';
                $fetchpriority = $settings['fetchpriority'] ?? '';
                $unique_id = uniqid('eel_team_');

                $title_tag = isset( $settings['title_tag'] ) ? $settings['title_tag'] : 'h4';
                $name = sprintf( '<%1$s class="eel-name">%2$s</%1$s>', esc_attr( $title_tag ), esc_html( $settings['_name'] ));
                $designation = $settings['designation'] ?? '';
                $details = $settings['details'] ?? '';
                ?>
                <?php if ($team_skin == 'default'): ?>
                    <div class="grid-item">
                        <div class="ee--team-img">
                            <?php if ( $action_type === 'link' && $link ) : ?>
                                <a href="<?php echo esc_url( $link ); ?>"
                                <?php if ( $target ) : ?>target="<?php echo esc_attr( $target ); ?>"<?php endif; ?>
                                <?php if ( $nofollow ) : ?>rel="<?php echo esc_attr( $nofollow ); ?>"<?php endif; ?>>
                            <?php elseif ( $action_type === 'popup' ) : ?>
                                <a href="#<?php echo esc_attr($unique_id); ?>" class="eel-popup-trigger" data-popup-id="<?php echo esc_attr($unique_id); ?>">
                            <?php endif; ?>   

                            <?php if ( $image_data ) : ?>
                                <div class="eel-team-img-area">
                                    <div class="eel-team-img-box">
                                        <img class="eel-team-img"
                                        src="<?php echo esc_url( $image_data[0] ); ?>"
                                        width="<?php echo esc_attr( $image_data[1] ); ?>"
                                        height="<?php echo esc_attr( $image_data[2] ); ?>"
                                        alt="<?php echo esc_attr( $alt ); ?>"
                                        title="<?php echo esc_attr( $title ); ?>"
                                        loading="lazy"
                                        decoding="async" fetchpriority="<?php echo esc_attr( $fetchpriority ); ?>">
                                    </div>

                                    <?php if($settings['content_show'] == 'inside'):?>
                                        <div class="eel-name-deg-wrap <?php echo esc_attr( $settings['content_show'] )?>">
                                            <?php if ( ! empty( $name ) ) :
                                                echo wp_kses_post( $name );
                                            endif; ?>
                                            <?php if ( ! empty( $designation ) ) : ?>
                                                <div class="eel-designation"><?php echo esc_html( $designation ); ?></div>
                                            <?php endif; ?>                   
                                        </div>
                                    <?php endif; ?>  
                                </div>
                                <div class="eel-image-overlay"></div>
                            <?php endif; ?>
                            <?php if($settings['content_show'] != 'inside'):?>
                                <div class="eel-name-deg-wrap">
                                    <?php if ( ! empty( $name ) ) :
                                        echo wp_kses_post( $name );
                                    endif; ?>
                                    <?php if ( ! empty( $designation ) ) : ?>
                                        <div class="eel-designation"><?php echo esc_html( $designation ); ?></div>
                                    <?php endif; ?>                   
                                </div>
                            <?php endif; ?>  
                            <?php if ( $settings['social_links'] ): ?>
                                <div class="eel-team-social <?php echo esc_attr( $settings['social_icon_position'] )?>">
                                    <ul>
                                        <?php foreach (  $settings['social_links'] as $item ):?>
                                            <li>
                                                <a href="<?php echo esc_attr( $item['link_url']['url'] )?>">
                                                    <?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                        </ul>
                                </div>
                            <?php endif; ?>
                            <?php if ( ($action_type === 'link' && $link) || $action_type === 'popup' ) : ?>
                                </a>
                            <?php endif; ?>
                            <?php if ( $action_type === 'popup' ) : ?>
                                <div id="<?php echo esc_attr($unique_id); ?>" class="eel-popup-modal" style="display:none;">
                                    <div class="eel-popup-content">
                                        <span class="eel-popup-close">&times;</span>
                                        <div class="eel-popup-header">
                                            <?php if ( ! empty( $name ) ) : ?>
                                                <div class="eel-popup-name"><?php echo wp_kses_post( $name ); ?></div>
                                            <?php endif; ?>
                                            <?php if ( ! empty( $designation ) ) : ?>
                                                <div class="eel-popup-designation"><?php echo esc_html( $designation ); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="eel-popup-details">
                                            <?php if ( ! empty( $details ) ) : ?>
                                                <?php echo nl2br( esc_html( $details ) ); ?>
                                            <?php else : ?>
                                                <p><?php esc_html_e( 'No additional details available.', 'easy-elements' ); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                <? else:
                  include plugin_dir_path(__FILE__) . 'skins/' . $team_skin . '.php';
                 endif;
                ?>
            </div>
        </div>
        <?php
    }
}