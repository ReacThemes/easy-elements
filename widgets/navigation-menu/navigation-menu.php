<?php			
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

// Include the Menu_Walker class
require_once plugin_dir_path( __FILE__ ) . 'menu-walker.php';

/**
 * Class Nav Menu.
 */
class Easyel_Navigation_Menu_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
		$handle = 'eel-navigation-menu-style';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/navigation-menu.css';
		
		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style( $handle, plugins_url( 'css/navigation-menu.css', __FILE__ ), [], filemtime( $css_path ) );
		}
		return [ $handle ];
	}	
	
	public function get_script_depends() {
		$handle = 'eel-navigation-menu-script';
		$js_path = plugin_dir_path( __FILE__ ) . 'js/navigation-menu.js';
	
		if ( ! wp_script_is( $handle, 'registered' ) && file_exists( $js_path ) ) {
			wp_register_script( $handle, plugins_url( 'js/navigation-menu.js', __FILE__ ), [ 'jquery' ], filemtime( $js_path ), true );
		}
	
		return [ $handle ];
	}	

	/**
	 * Menu index.
	 *
	 * @access protected
	 * @var int $nav_menu_index
	 */
	// phpcs:ignore
	protected int $nav_menu_index = 1;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	
	public function get_name() {
        return 'eel-navigation-menu';
    }

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Easy Navigation Menu', 'easy-elements' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
        return 'easy-elements-icon';
    }

	/**
	 * Retrieve the widget categories.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'easyelements_header_footer_category' ];
	}

	/**
	 * Retrieve the menu index.
	 *
	 * Used to get index of nav menu.
	 *
	 * @since 1.3.0
	 * @access protected
	 *
	 * @return int nav index.
	 */
	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	/**
	 * Retrieve the list of available menus.
	 *
	 * Used to get the list of available menus.
	 *
	 * @since 1.3.0
	 * @access private
	 *
	 * @return array get WordPress menus list.
	 */
	private function get_available_menus() {

		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Check if the Elementor is updated.
	 *
	 * @since 1.3.0
	 *
	 * @return boolean if Elementor updated.
	 */
	public static function is_elementor_updated() {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return true;
		} else {
			return false;
		}
	}
	

	/**
	 * Register Nav Menu controls.
	 *
	 * @since 1.5.7
	 * @access protected
	 * @return void
	 */
	protected function register_controls() {

		$this->register_general_content_controls();
		$this->register_style_content_controls();
		$this->register_dropdown_content_controls();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 * @return void
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_menu',
			[
				'label' => __( 'Menu Settings', 'easy-elements' ),
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label'        => __( 'Menu', 'easy-elements' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					/* translators: %s Nav menu URL */
					'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'easy-elements' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s Nav menu URL */
					'raw'             => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'easy-elements' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		

		$this->add_control(
			'menu_last_item',
			[
				'label'     => __( 'Last Menu Item', 'easy-elements' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none' => __( 'Default', 'easy-elements' ),
					'cta'  => __( 'Button', 'easy-elements' ),
				],
				'default'   => 'none',
				'condition' => [
					'layout!' => 'expandible',
				],
			]
		);

		$this->add_control(
			'schema_support',
			[
				'label'        => __( 'Enable Schema Support', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'render_type'  => 'template',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'enable_sticky_header',
			[
				'label'        => __( 'Enable Sticky Header', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'frontend_available' => true,
				'default'      => 'no',
			]
		);

		$this->add_control(
			'fixed_top_sticky',
			[
				'label'        => __( 'Fixed Top Sticky', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'frontend_available' => true,
				'default'      => 'no',
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);

		$this->add_control(
			'bg_color_sticky',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'header.eel-sticky-header-on.eel-up-scroll, header.eel-sticky-header-on.eel-up-scroll .eel-nav-menu__layout-horizontal .eel-nav-menu .sub-menu:not(.easyel--elementor-template-mega-menu)' => 'background-color: {{VALUE}} !important',
				],
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'text_color_sticky',
			[
				'label'     => __( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'header.eel-sticky-header-on.eel-up-scroll .eel-nav-menu a.eel-menu-item, header.eel-sticky-header-on.eel-up-scroll .eel-nav-menu a.eel-sub-menu-item, .header.eel-sticky-header-on.eel-up-scroll *' => 'color: {{VALUE}} !important',
				],
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => 'body header.eel-sticky-header-on.eel-up-scroll',
				'condition' => [
					'enable_sticky_header' => 'yes',
				],
			]
		);			

		$current_theme = wp_get_theme();

		if ( 'Twenty Twenty-One' === $current_theme->get( 'Name' ) ) {
			$this->add_control(
				'hide_theme_icons',
				[
					'label'        => __( 'Hide + & - Sign', 'easy-elements' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'easy-elements' ),
					'label_off'    => __( 'No', 'easy-elements' ),
					'return_value' => 'yes',
					'default'      => 'no',
					'prefix_class' => 'eel-nav-menu__theme-icon-',
				]
			);
		}

		$this->end_controls_section();

			$this->start_controls_section(
				'section_layout',
				[
					'label' => __( 'Layout Settings', 'easy-elements' ),
				]
			);

			$this->add_control(
			'layout',
			[
				'label'   => __( 'Layout', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => __( 'Horizontal', 'easy-elements' ),
					'vertical'   => __( 'Vertical', 'easy-elements' ),
				],
			]
		);

			$this->add_control(
				'navmenu_align',
				[
					'label'        => __( 'Alignment', 'easy-elements' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => [
						'left'    => [
							'title' => __( 'Left', 'easy-elements' ),
							'icon'  => 'eicon-h-align-left',
						],
						'center'  => [
							'title' => __( 'Center', 'easy-elements' ),
							'icon'  => 'eicon-h-align-center',
						],
						'right'   => [
							'title' => __( 'Right', 'easy-elements' ),
							'icon'  => 'eicon-h-align-right',
						],
						'justify' => [
							'title' => __( 'Justify', 'easy-elements' ),
							'icon'  => 'eicon-h-align-stretch',
						],
					],
					'default'      => 'left',
					'condition'    => [
						'layout' => [ 'horizontal' ],
					],
					'prefix_class' => 'eel-nav-menu__align-',
				]
			);

			$this->add_responsive_control(
				'navmenu_align_text',
				[
					'label'     => __( 'Alignment', 'easy-elements' ),
					'type'      => \Elementor\Controls_Manager::CHOOSE,
					'options'   => [
						'left' => [
							'title' => __( 'Left', 'easy-elements' ),
							'icon'  => 'eicon-h-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'easy-elements' ),
							'icon'  => 'eicon-h-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'easy-elements' ),
							'icon'  => 'eicon-h-align-right',
						],
						'justify' => [
							'title' => __( 'Justify', 'easy-elements' ),
							'icon'  => 'eicon-h-align-stretch',
						],
					],
					'default'   => 'left',
					'condition'    => [
						'layout' => [ 'vertical' ],
					],
					'selectors' => [
						'{{WRAPPER}} .eel-nav-menu li.menu-item' => 'text-align: {{VALUE}};',
						'{{WRAPPER}} .eel-nav-menu li.menu-item a' => 'max-width: 100%; width: auto; display: inline-block;',
					],
				]
			);			

			$this->add_control(
				'submenu_animation',
				[
					'label'        => __( 'Submenu Animation', 'easy-elements' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'none',
					'options'      => [
						'none'     => __( 'Default', 'easy-elements' ),
						'slide_up' => __( 'Slide Up', 'easy-elements' ),
					],
					'prefix_class' => 'eel-submenu-animation-',
					'condition'    => [
						'layout' => 'horizontal',
					],
				]
			);

			$this->add_control(
				'heading_responsive',
				[
					'type'      => Controls_Manager::HEADING,
					'label'     => __( 'Responsive', 'easy-elements' ),
					'separator' => 'before',
					'condition' => [
						'layout' => [ 'horizontal', 'vertical' ],
					],
				]
			);

		$this->add_control(
			'dropdown',
			[
				'label'        => __( 'Breakpoint', 'easy-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'tablet',
				'options'      => [
					'tablet' => __( 'Tablet (1025px >)', 'easy-elements' ),
					'none'   => __( 'None', 'easy-elements' ),
				],
				'prefix_class' => 'eel-nav-menu__breakpoint-',
				'condition'    => [
					'layout' => [ 'horizontal', 'vertical' ],
				],
				'render_type'  => 'template',
			]
		);


		$this->add_control(
			'show_mobile_on_sidebar',
			[
				'label'        => __( 'Show Mobile Menu On Sidebar', 'easy-elements' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'default'      => '',
			]
		);
	

		$this->start_controls_tabs( 'tabs_menu_item_style_mobile' );

		$this->start_controls_tab(
			'tab_menu_item_normal_m',
			[
				'label' => __( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_control(
			'color_menu_item_m',
			[
				'label'     => __( 'Text Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,		
				'selectors' => [
					'{{WRAPPER}} .eel-mobile .menu-item a.eel-menu-item, .sidebar-on-mobile li.menu-item a, {{WRAPPER}} .eel-mobile .sub-menu a.eel-sub-menu-item' => 'color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'color_menu_item_m_typography',
				'label' => esc_html__('Typography', 'easy-elements' ),
				'selector' => '{{WRAPPER}} body .eel-mobile .menu-item a.eel-menu-item, body .sidebar-on-mobile li.menu-item a',
			]
		); 

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover_m',
			[
				'label' => __( 'Hover', 'easy-elements' ),
			]
		);

			$this->add_control(
				'color_menu_item_hover_m',
				[
					'label'     => __( 'Text Color', 'easy-elements' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eel-mobile .menu-item > a.eel-menu-item:hover, .sidebar-on-mobile li.menu-item > a:hover,
						{{WRAPPER}} .eel-mobile .sub-menu a.eel-sub-menu-item:hover,
						{{WRAPPER}} .eel-mobile .menu-item.current-menu-item > a.eel-menu-item,
						{{WRAPPER}} .eel-mobile .menu-item a.eel-menu-item.highlighted' => 'color: {{VALUE}} !important;',
					],
				]
			);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active_m',
			[
				'label' => __( 'Active', 'easy-elements' ),
			]
		);

			$this->add_control(
				'color_menu_item_active_m',
				[
					'label'     => __( 'Text Color', 'easy-elements' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eel-mobile .menu-item.current-menu-item > a.eel-menu-item, 
						.sidebar-on-mobile li.menu-item.current-menu-item > a, .sidebar-on-mobile li.menu-item a.eel-active,
						{{WRAPPER}} .eel-mobile .menu-item.current-menu-ancestor > a.eel-menu-item' => 'color: {{VALUE}} !important;',
					],
				]
			);


		$this->end_controls_tab();

	$this->end_controls_tabs();

		$this->add_control(
			'resp_align',
			[
				'label'                => __( 'Alignment', 'easy-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'easy-elements' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'easy-elements' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'easy-elements' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'              => 'center',
				'description'          => __( 'This is the alignement of menu icon on selected responsive breakpoints.', 'easy-elements' ),
				'condition'            => [
					'layout'    => [ 'horizontal', 'vertical' ],
					'dropdown!' => 'none',
				],
				'selectors_dictionary' => [
					'left'   => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right'  => 'margin-left: auto',
				],
				'selectors'            => [
					'{{WRAPPER}} .eel-nav-menu__toggle' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'full_width_dropdown',
			[
				'label'        => __( 'Full Width', 'easy-elements' ),
				'description'  => __( 'Enable this option to stretch the Sub Menu to Full Width.', 'easy-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'easy-elements' ),
				'label_off'    => __( 'No', 'easy-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'dropdown!' => 'none',
				],
				'render_type'  => 'template',
			]
		);

	
		$this->add_control(
			'dropdown_icon',
			[
				'label'       => __( 'Menu Icon', 'easy-elements' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => 'true',
				'default'     => [
					'value'   => 'fas fa-align-justify',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'dropdown!' => 'none',
				],
			]
		);
		
		$this->add_control(
			'dropdown_close_icon',
			[
				'label'       => __( 'Close Icon', 'easy-elements' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => 'true',
				'default'     => [
					'value'   => 'far fa-window-close',
					'library' => 'fa-regular',
				],
				'condition'   => [
					'dropdown!' => 'none',
				],
				'condition'   => [
					'show_mobile_on_sidebar!' => 'yes',
				],
			]
		);
		

		$this->end_controls_section();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 * @return void
	 */
	protected function register_style_content_controls() {

		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label'     => __( 'Main Menu', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout!' => 'expandible',
				],
			]
		);



		$this->add_responsive_control(
			'padding_horizontal_menu_item',
			[
				'label'              => __( 'Horizontal Padding', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 50,
					],
				],
				'default'            => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors'          => [
					'{{WRAPPER}} .menu-item a.eel-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .menu-item a.eel-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 20px );padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-nav-menu__layout-vertical .menu-item ul ul a.eel-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 40px );padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-nav-menu__layout-vertical .menu-item ul ul ul a.eel-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 60px );padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-nav-menu__layout-vertical .menu-item ul ul ul ul a.eel-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 80px );padding-right: {{SIZE}}{{UNIT}};',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'padding_vertical_menu_item',
			[
				'label'              => __( 'Vertical Padding', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 50,
					],
				],
				'default'            => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors'          => [
					'{{WRAPPER}} .menu-item a.eel-menu-item, {{WRAPPER}} .menu-item a.eel-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'menu_space_between',
			[
				'label'              => __( 'Space Between', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'          => [
					'body:not(.rtl) {{WRAPPER}} .eel-nav-menu__layout-horizontal .eel-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .eel-nav-menu__layout-horizontal .eel-nav-menu > li.menu-item:not(:last-child)' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} nav:not(.eel-nav-menu__layout-horizontal) .eel-nav-menu > li.menu-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(tablet)body:not(.rtl) {{WRAPPER}}.eel-nav-menu__breakpoint-tablet .eel-nav-menu__layout-horizontal .eel-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: 0px',
					'(mobile)body:not(.rtl) {{WRAPPER}}.eel-nav-menu__breakpoint-mobile .eel-nav-menu__layout-horizontal .eel-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: 0px',
					'(tablet)body {{WRAPPER}} nav.eel-nav-menu__layout-vertical .eel-nav-menu > li.menu-item:not(:last-child)' => 'margin-bottom: 0px',
					'(mobile)body {{WRAPPER}} nav.eel-nav-menu__layout-vertical .eel-nav-menu > li.menu-item:not(:last-child)' => 'margin-bottom: 0px',
				],
				'condition'          => [
					'layout!' => 'expandible',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'menu_row_space',
			[
				'label'              => __( 'Row Spacing', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'          => [
					'body:not(.rtl) {{WRAPPER}} .eel-nav-menu__layout-horizontal .eel-nav-menu > li.menu-item' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'          => [
					'layout' => 'horizontal',
				],
				'frontend_available' => true,
			]
		);



		$this->add_control(
			'pointer',
			[
				'label'     => __( 'Link Hover Effect', 'easy-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => [
					'none'        => __( 'None', 'easy-elements' ),
					'underline'   => __( 'Underline', 'easy-elements' ),
					'overline'    => __( 'Overline', 'easy-elements' ),
					'double-line' => __( 'Double Line', 'easy-elements' ),
					'framed'      => __( 'Framed', 'easy-elements' ),
					'text'        => __( 'Text', 'easy-elements' ),
				],
				'condition' => [
					'layout' => [ 'horizontal' ],
				],
			]
		);

		$this->add_control(
			'pointer_line_height',
			[
				'label'     => __( 'Line Height', 'easy-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default'   => [
					'size' => 1,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .eel-pointer__double-line .menu-item.parent a.eel-menu-item:before,
					{{WRAPPER}} .eel-pointer__double-line .menu-item.parent a.eel-menu-item:after,
					{{WRAPPER}} .eel-pointer__underline .menu-item.parent a.eel-menu-item:before,
					{{WRAPPER}} .eel-pointer__underline .menu-item.parent a.eel-menu-item:after,
					{{WRAPPER}} .eel-pointer__overline .menu-item.parent a.eel-menu-item:before,
					{{WRAPPER}} .eel-pointer__overline .menu-item.parent a.eel-menu-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout'  => [ 'horizontal' ],
					'pointer' => [ 'underline', 'overline', 'double-line' ],
				],
			]
		);

		$this->add_control(
			'animation_line',
			[
				'label'     => __( 'Animation', 'easy-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => [
					'fade'     => 'Fade',
					'slide'    => 'Slide',
					'grow'     => 'Grow',
					'drop-in'  => 'Drop In',
					'drop-out' => 'Drop Out',
					'none'     => 'None',
				],
				'condition' => [
					'layout'  => [ 'horizontal' ],
					'pointer' => [ 'underline', 'overline', 'double-line' ],
				],
			]
		);

		$this->add_control(
			'animation_framed',
			[
				'label'     => __( 'Frame Animation', 'easy-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => [
					'fade'    => 'Fade',
					'grow'    => 'Grow',
					'shrink'  => 'Shrink',
					'draw'    => 'Draw',
					'corners' => 'Corners',
					'none'    => 'None',
				],
				'condition' => [
					'layout'  => [ 'horizontal' ],
					'pointer' => 'framed',
				],
			]
		);

		$this->add_control(
			'animation_text',
			[
				'label'     => __( 'Animation', 'easy-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'grow',
				'options'   => [
					'grow'   => 'Grow',
					'shrink' => 'Shrink',
					'sink'   => 'Sink',
					'float'  => 'Float',
					'skew'   => 'Skew',
					'rotate' => 'Rotate',
					'none'   => 'None',
				],
				'condition' => [
					'layout'  => [ 'horizontal' ],
					'pointer' => 'text',
				],
			]
		);

		$this->add_control(
			'style_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'menu_typography',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} a.eel-menu-item, {{WRAPPER}} a.eel-sub-menu-item',
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

				$this->start_controls_tab(
					'tab_menu_item_normal',
					[
						'label' => __( 'Normal', 'easy-elements' ),
					]
				);

					$this->add_control(
						'color_menu_item',
						[
							'label'     => __( 'Text Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item a.eel-menu-item, {{WRAPPER}} .sub-menu a.eel-sub-menu-item' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'bg_color_menu_item',
						[
							'label'     => __( 'Background Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item a.eel-menu-item, {{WRAPPER}} .sub-menu, {{WRAPPER}} nav.eel-dropdown' => 'background-color: {{VALUE}}',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_menu_item_hover',
					[
						'label' => __( 'Hover', 'easy-elements' ),
					]
				);

					$this->add_control(
						'color_menu_item_hover',
						[
							'label'     => __( 'Text Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .menu-item a.eel-menu-item:hover,
								{{WRAPPER}} .sub-menu a.eel-sub-menu-item:hover,
								{{WRAPPER}} .menu-item.current-menu-item a.eel-menu-item,
								{{WRAPPER}} .menu-item a.eel-menu-item.highlighted' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'bg_color_menu_item_hover',
						[
							'label'     => __( 'Background Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .menu-item a.eel-menu-item:hover,
								{{WRAPPER}} .sub-menu a.eel-sub-menu-item:hover,
								{{WRAPPER}} .menu-item.current-menu-item a.eel-menu-item,
								{{WRAPPER}} .menu-item a.eel-menu-item.highlighted' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'pointer_color_menu_item_hover',
						[
							'label'     => __( 'Link Hover Effect Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .eel-nav-menu-layout:not(.eel-pointer__framed) .menu-item.parent a.eel-menu-item:before,
								{{WRAPPER}} .eel-nav-menu-layout:not(.eel-pointer__framed) .menu-item.parent a.eel-menu-item:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .eel-nav-menu-layout:not(.eel-pointer__framed) .menu-item.parent .sub-menu .eel-has-submenu-container a:after' => 'background-color: unset',
								'{{WRAPPER}} .eel-pointer__framed .menu-item.parent a.eel-menu-item:before,
								{{WRAPPER}} .eel-pointer__framed .menu-item.parent a.eel-menu-item:after' => 'border-color: {{VALUE}}',
							],
							'condition' => [
								'pointer!' => [ 'none', 'text' ],
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_menu_item_active',
					[
						'label' => __( 'Active', 'easy-elements' ),
					]
				);

					$this->add_control(
						'color_menu_item_active',
						[
							'label'     => __( 'Text Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item.current-menu-item a.eel-menu-item,
								{{WRAPPER}} .menu-item a.eel-menu-item.eel-active,
								{{WRAPPER}} .menu-item.current-menu-ancestor a.eel-menu-item' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'bg_color_menu_item_active',
						[
							'label'     => __( 'Background Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item.current-menu-item a.eel-menu-item,
								{{WRAPPER}} .menu-item.current-menu-ancestor a.eel-menu-item' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'pointer_color_menu_item_active',
						[
							'label'     => __( 'Link Hover Effect Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .eel-nav-menu-layout:not(.eel-pointer__framed) .menu-item.parent.current-menu-item a.eel-menu-item:before,
								{{WRAPPER}} .eel-nav-menu-layout:not(.eel-pointer__framed) .menu-item.parent.current-menu-item a.eel-menu-item:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .eel-nav-menu:not(.eel-pointer__framed) .menu-item.parent .sub-menu .eel-has-submenu-container a.current-menu-item:after' => 'background-color: unset',
								'{{WRAPPER}} .eel-pointer__framed .menu-item.parent.current-menu-item a.eel-menu-item:before,
								{{WRAPPER}} .eel-pointer__framed .menu-item.parent.current-menu-item a.eel-menu-item:after' => 'border-color: {{VALUE}}',
							],
							'condition' => [
								'pointer!' => [ 'none', 'text' ],
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 * @return void
	 */
	protected function register_dropdown_content_controls() {

		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => __( 'Dropdown', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'dropdown_description',
				[
					'raw'             => __( '<b>Note:</b> On desktop, below style options will apply to the submenu. On mobile, this will apply to the entire menu.', 'easy-elements' ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-descriptor',
					'condition'       => [
						'layout!' => [
							'expandible',
						],
					],
				]
			);

			$this->start_controls_tabs( 'tabs_dropdown_item_style' );

				$this->start_controls_tab(
					'tab_dropdown_item_normal',
					[
						'label' => __( 'Normal', 'easy-elements' ),
					]
				);

					$this->add_control(
						'color_dropdown_item',
						[
							'label'     => __( 'Text Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .sub-menu a.eel-sub-menu-item,
								{{WRAPPER}} .elementor-menu-toggle,
								{{WRAPPER}} nav.eel-dropdown li a.eel-menu-item,
								{{WRAPPER}} nav.eel-dropdown li a.eel-sub-menu-item' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'background_color_dropdown_item',
						[
							'label'     => __( 'Background Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#fff',
							'selectors' => [
								'{{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu),
								{{WRAPPER}} nav.eel-dropdown,
								{{WRAPPER}} nav.eel-dropdown .menu-item a.eel-menu-item,
								{{WRAPPER}} nav.eel-dropdown .menu-item a.eel-sub-menu-item' => 'background-color: {{VALUE}}',
							],
							'separator' => 'after',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_dropdown_item_hover',
					[
						'label' => __( 'Hover', 'easy-elements' ),
					]
				);

					$this->add_control(
						'color_dropdown_item_hover',
						[
							'label'     => __( 'Text Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .sub-menu a.eel-sub-menu-item:hover,
								{{WRAPPER}} .elementor-menu-toggle:hover,
								{{WRAPPER}} nav.eel-dropdown li a.eel-menu-item:hover,
								{{WRAPPER}} nav.eel-dropdown li a.eel-sub-menu-item:hover' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'background_color_dropdown_item_hover',
						[
							'label'     => __( 'Background Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .sub-menu a.eel-sub-menu-item:hover,
								{{WRAPPER}} nav.eel-dropdown li a.eel-menu-item:hover,
								{{WRAPPER}} nav.eel-dropdown li a.eel-sub-menu-item:hover' => 'background-color: {{VALUE}}',
							],
							'separator' => 'after',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_dropdown_item_active',
					[
						'label' => __( 'Active', 'easy-elements' ),
					]
				);

				$this->add_control(
					'color_dropdown_item_active',
					[
						'label'     => __( 'Text Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .sub-menu .menu-item.current-menu-item a.eel-sub-menu-item.eel-sub-menu-item-active,
							{{WRAPPER}} nav.eel-dropdown .menu-item.current-menu-item a.eel-menu-item,
							{{WRAPPER}} nav.eel-dropdown .menu-item.current-menu-ancestor a.eel-menu-item,
							{{WRAPPER}} nav.eel-dropdown .sub-menu .menu-item.current-menu-item a.eel-sub-menu-item.eel-sub-menu-item-active
							' => 'color: {{VALUE}}',

						],
					]
				);

				$this->add_control(
					'background_color_dropdown_item_active',
					[
						'label'     => __( 'Background Color', 'easy-elements' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .sub-menu .menu-item.current-menu-item a.eel-sub-menu-item.eel-sub-menu-item-active,
							{{WRAPPER}} nav.eel-dropdown .menu-item.current-menu-item a.eel-menu-item,
							{{WRAPPER}} nav.eel-dropdown .menu-item.current-menu-ancestor a.eel-menu-item,
							{{WRAPPER}} nav.eel-dropdown .sub-menu .menu-item.current-menu-item a.eel-sub-menu-item.eel-sub-menu-item-active' => 'background-color: {{VALUE}}',
						],
						'separator' => 'after',

					]
				);

				$this->end_controls_tabs();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'dropdown_typography',
					'separator' => 'before',
					'selector'  => '
					{{WRAPPER}} .sub-menu li a.eel-sub-menu-item,
					{{WRAPPER}} nav.eel-dropdown li a.eel-sub-menu-item,
					{{WRAPPER}} nav.eel-dropdown li a.eel-menu-item',
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'dropdown_border',
					'selector' => '{{WRAPPER}} nav.eel-nav-menu__layout-horizontal .sub-menu:not(.easyel--elementor-template-mega-menu),
							{{WRAPPER}} nav:not(.eel-nav-menu__layout-horizontal) .sub-menu.sub-menu-open,
							{{WRAPPER}} nav.eel-dropdown .eel-nav-menu,
						 	{{WRAPPER}} nav.eel-dropdown .eel-nav-menu',
				]
			);

			$this->add_responsive_control(
				'dropdown_border_radius',
				[
					'label'              => __( 'Border Radius', 'easy-elements' ),
					'type'               => Controls_Manager::DIMENSIONS,
					'size_units'         => [ 'px', '%' ],
					'selectors'          => [
						'{{WRAPPER}} .sub-menu:not(.easyel--elementor-template-mega-menu)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .sub-menu li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden;',
						'{{WRAPPER}} .sub-menu li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.eel-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} nav.eel-dropdown li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.eel-dropdown li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.eel-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} nav.eel-dropdown li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.eel-dropdown li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
					],
					'frontend_available' => true,
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'      => 'dropdown_box_shadow',					
					'selector'  => '{{WRAPPER}} .eel-nav-menu .sub-menu:not(.easyel--elementor-template-mega-menu),
								{{WRAPPER}} nav.eel-dropdown,
						 		{{WRAPPER}} nav.eel-dropdown',
					'separator' => 'after',
				]
			);

			$this->add_responsive_control(
				'width_dropdown_item',
				[
					'label'              => __( 'Dropdown Width (px)', 'easy-elements' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 500,
						],
					],
					'default'            => [
						'size' => '220',
						'unit' => 'px',
					],
					'selectors'          => [
						'{{WRAPPER}} ul.sub-menu:not(.easyel--elementor-template-mega-menu)' => 'width: {{SIZE}}{{UNIT}}',
					],
					'condition'          => [
						'layout' => 'horizontal',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'padding_horizontal_dropdown_item',
				[
					'label'              => __( 'Horizontal Padding', 'easy-elements' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => [ 'px' ],
					'selectors'          => [
						'{{WRAPPER}} .sub-menu li a.eel-sub-menu-item,
						{{WRAPPER}} nav.eel-dropdown li a.eel-menu-item,
						{{WRAPPER}} nav.eel-dropdown li a.eel-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} nav.eel-dropdown a.eel-sub-menu-item,
						{{WRAPPER}} nav.eel-dropdown li a.eel-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 20px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .eel-dropdown .menu-item ul ul a.eel-sub-menu-item,
						{{WRAPPER}} .eel-dropdown .menu-item ul ul a.eel-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 40px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .eel-dropdown .menu-item ul ul ul a.eel-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 60px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .eel-dropdown .menu-item ul ul ul ul a.eel-sub-menu-item,
						{{WRAPPER}} .eel-dropdown .menu-item ul ul ul ul a.eel-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 80px );padding-right: {{SIZE}}{{UNIT}};',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'padding_vertical_dropdown_item',
				[
					'label'              => __( 'Vertical Padding', 'easy-elements' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => [ 'px' ],
					'default'            => [
						'size' => 15,
						'unit' => 'px',
					],
					'range'              => [
						'px' => [
							'max' => 50,
						],
					],
					'selectors'          => [
						'{{WRAPPER}} .sub-menu a.eel-sub-menu-item,
						 {{WRAPPER}} nav.eel-dropdown li a.eel-menu-item,
						 {{WRAPPER}} nav.eel-dropdown li a.eel-sub-menu-item,
						 {{WRAPPER}} nav.eel-dropdown li a.eel-menu-item,
						 {{WRAPPER}} nav.eel-dropdown li a.eel-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'distance_from_menu',
				[
					'label'              => __( 'Top Distance', 'easy-elements' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors'          => [
						'{{WRAPPER}} nav.eel-nav-menu__layout-horizontal:not(.eel-dropdown) ul.sub-menu, {{WRAPPER}} nav.eel-nav-menu__layout-expandible.menu-is-active, {{WRAPPER}} nav.eel-nav-menu__layout-vertical:not(.eel-dropdown) ul.sub-menu' => 'margin-top: {{SIZE}}px;',
						'{{WRAPPER}} .eel-dropdown.menu-is-active' => 'margin-top: {{SIZE}}px;',
					],
					'condition'          => [
						'layout' => [ 'horizontal', 'vertical', 'expandible' ],
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'icon_size',
				[
					'label'              => __( 'Icon Size', 'easy-elements' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => 15,
							'max' => 25,
						],
					],
					'selectors'          => [
						'{{WRAPPER}} .eel-nav-menu .menu-item-has-children a.eel-menu-item::before' => 'font-size: {{SIZE}}px;',
					],
					'condition'          => [
						'layout' => [ 'horizontal' ],
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'icon_distance_from_menu',
				[
					'label'              => __( 'Icon Top Distance', 'easy-elements' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors'          => [
						'{{WRAPPER}} .eel-nav-menu .menu-item-has-children a.eel-menu-item::before' => 'top: {{SIZE}}%;',
					],
					'condition'          => [
						'layout' => [ 'horizontal' ],
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'heading_dropdown_divider',
				[
					'label'     => __( 'Divider', 'easy-elements' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'dropdown_divider_border',
				[
					'label'       => __( 'Border Style', 'easy-elements' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'solid',
					'label_block' => false,
					'options'     => [
						'none'   => __( 'None', 'easy-elements' ),
						'solid'  => __( 'Solid', 'easy-elements' ),
						'double' => __( 'Double', 'easy-elements' ),
						'dotted' => __( 'Dotted', 'easy-elements' ),
						'dashed' => __( 'Dashed', 'easy-elements' ),
					],
					'selectors'   => [
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child),
						{{WRAPPER}} nav.eel-dropdown li.menu-item:not(:last-child),
						{{WRAPPER}} nav.eel-dropdown li.menu-item:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'divider_border_color',
				[
					'label'     => __( 'Border Color', 'easy-elements' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#c4c4c4',
					'selectors' => [
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child),
						{{WRAPPER}} nav.eel-dropdown li.menu-item:not(:last-child),
						{{WRAPPER}} nav.eel-dropdown li.menu-item:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
					],
					'condition' => [
						'dropdown_divider_border!' => 'none',
					],
				]
			);

			$this->add_control(
				'dropdown_divider_width',
				[
					'label'     => __( 'Border Width', 'easy-elements' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'default'   => [
						'size' => '1',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child),
						{{WRAPPER}} nav.eel-dropdown li.menu-item:not(:last-child),
						{{WRAPPER}} nav.eel-dropdown li.menu-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'dropdown_divider_border!' => 'none',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_toggle',
			[
				'label' => __( 'Menu Trigger & Close Icon', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'toggle_style_normal',
			[
				'label' => __( 'Normal', 'easy-elements' ),
			]
		);

		$this->add_control(
			'toggle_color',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} div.eel-nav-menu-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} div.eel-nav-menu-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_background_color',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-nav-menu-icon' => 'background-color: {{VALUE}}; padding: 0.35em;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_hover',
			[
				'label' => __( 'Hover', 'easy-elements' ),
			]
		);

		$this->add_control(
			'toggle_hover_color',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} div.eel-nav-menu-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} div.eel-nav-menu-icon:hover svg' => 'fill: {{VALUE}}',

				],
			]
		);

		$this->add_control(
			'toggle_hover_background_color',
			[
				'label'     => __( 'Background Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-nav-menu-icon:hover' => 'background-color: {{VALUE}}; padding: 0.35em;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'toggle_size',
			[
				'label'              => __( 'Icon Size', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min' => 15,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .eel-nav-menu-icon'     => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .eel-nav-menu-icon svg' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
				],
				'frontend_available' => true,
				'separator'          => 'before',
			]
		);

		$this->add_responsive_control(
			'toggle_border_width',
			[
				'label'              => __( 'Border Width', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'max' => 10,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .eel-nav-menu-icon' => 'border-width: {{SIZE}}{{UNIT}}; padding: 0.35em;',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'toggle_border_radius',
			[
				'label'              => __( 'Border Radius', 'easy-elements' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .eel-nav-menu-icon' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);



		$this->end_controls_section();
		$this->start_controls_section(
			'style_button',
			[
				'label'     => __( 'Button', 'easy-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'menu_last_item' => 'cta',
				],
			]
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'all_typography',
					'label'    => __( 'Typography', 'easy-elements' ),
					'selector' => '{{WRAPPER}} .menu-item a.eel-menu-item.elementor-button',
				]
			);
			$this->add_responsive_control(
				'padding',
				[
					'label'              => __( 'Padding', 'easy-elements' ),
					'type'               => Controls_Manager::DIMENSIONS,
					'size_units'         => [ 'px', 'em', '%' ],
					'selectors'          => [
						'{{WRAPPER}} .menu-item a.eel-menu-item.elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'frontend_available' => true,
				]
			);

			$this->start_controls_tabs( '_button_style' );

				$this->start_controls_tab(
					'_button_normal',
					[
						'label' => __( 'Normal', 'easy-elements' ),
					]
				);

					$this->add_control(
						'all_text_color',
						[
							'label'     => __( 'Text Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item a.eel-menu-item.elementor-button' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name'     => 'all_border',
							'label'    => __( 'Border', 'easy-elements' ),
							'selector' => '{{WRAPPER}} .menu-item a.eel-menu-item.elementor-button',
						]
					);

					$this->add_control(
						'all_border_radius',
						[
							'label'      => __( 'Border Radius', 'easy-elements' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%' ],
							'selectors'  => [
								'{{WRAPPER}} .menu-item a.eel-menu-item.elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name'     => 'all_button_box_shadow',
							'selector' => '{{WRAPPER}} .menu-item a.eel-menu-item.elementor-button',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'all_button_hover',
					[
						'label' => __( 'Hover', 'easy-elements' ),
					]
				);

					$this->add_control(
						'all_hover_color',
						[
							'label'     => __( 'Text Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .menu-item a.eel-menu-item.elementor-button:hover' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'all_border_hover_color',
						[
							'label'     => __( 'Border Hover Color', 'easy-elements' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item a.eel-menu-item.elementor-button:hover' => 'border-color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name'      => 'all_button_hover_box_shadow',
							'selector'  => '{{WRAPPER}} .menu-item a.eel-menu-item.elementor-button:hover',
							'separator' => 'after',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Add itemprop for Navigation Schema.
	 *
	 * @since 1.5.2
	 * @param string $atts link attributes.
	 * @access public
	 * @return string
	 */
	public function handle_link_attrs( $atts ) {

		$atts .= ' itemprop="url"';
		return $atts;
	}

	/**
	 * Add itemprop to the li tag of Navigation Schema.
	 *
	 * @since 1.6.0
	 * @param string $value link attributes.
	 * @access public
	 * @return string
	 */
	public function handle_li_values( $value ) {

		$value .= ' itemprop="name"';
		return $value;
	}


	/**
	 * Get the menu and close icon HTML.
	 *
	 * @since 1.5.2
	 * @param array $settings Widget settings array.
	 * @access public
	 * @return array
	 */
	public function get_menu_close_icon( $settings ) {
		$menu_icon     = '';
		$close_icon    = '';
		$icons         = [];
		$icon_settings = [
			$settings['dropdown_icon'],
			$settings['dropdown_close_icon'],
		];

		foreach ( $icon_settings as $icon ) {
			if ( $this->is_elementor_updated() ) {
				ob_start();
				\Elementor\Icons_Manager::render_icon(
					$icon,
					[
						'aria-hidden' => 'true',
						'tabindex'    => '0',
					]
				);
				$menu_icon = ob_get_clean();
			} else {
				$menu_icon = '<i class="' . esc_attr( $icon ) . '" aria-hidden="true" tabindex="0"></i>';
			}

			array_push( $icons, $menu_icon );
		}

		return $icons;
	}	

	/**
	 * Render Nav Menu output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 * @return (void | false)
	 */
	protected function render() {

		
		$menus = $this->get_available_menus();

		if ( empty( $menus ) ) {
			return false;
		}

		$settings = $this->get_settings_for_display();

		$menu_close_icons = [];
		$menu_close_icons = $this->get_menu_close_icon( $settings );	

		$args = [
			'echo'        => false,
			'menu'        => $settings['menu'],
			'menu_class'  => 'eel-nav-menu',
			'menu_id'     => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container'   => '',
			'walker'      => new Easyel_Menu_Walker(),
			'menu_last_item' => $settings['menu_last_item'], // Pass the menu_last_item setting to the walker
		];

		if ( 'yes' === $settings['schema_support'] ) {
			$this->add_render_attribute( 'eel-nav-menu', 'itemscope', 'itemscope' );
			$this->add_render_attribute( 'eel-nav-menu', 'itemtype', 'https://schema.org/SiteNavigationElement' );

			add_filter( 'Easyel_Menu_Walker_nav_menu_attrs', [ $this, 'handle_link_attrs' ] );
			add_filter( 'nav_menu_li_values', [ $this, 'handle_li_values' ] );
		}

		

			$this->add_render_attribute(
				'eel-main-menu',
				'class',
				[
					'eel-nav-menu',
					'eel-layout-' . $settings['layout'],					
				]
			);

			$this->add_render_attribute( 'eel-main-menu', 'class', 'eel-nav-menu-layout' );

			$this->add_render_attribute( 'eel-main-menu', 'class', $settings['layout'] );

			$this->add_render_attribute( 'eel-main-menu', 'data-layout', $settings['layout'] );
			if ( 'yes' === $settings['fixed_top_sticky'] ) {
				$this->add_render_attribute( 'eel-main-menu', 'class', 'eel-fixed-top-sticky' );			
				echo '<script>
					document.addEventListener("DOMContentLoaded", function() {
						const header = document.querySelector("header");
						if(header){
							header.classList.add("eel-fixed-top-sticky");
						}
					});
				</script>';
			}
			if ( 'cta' === $settings['menu_last_item'] ) {

				$this->add_render_attribute( 'eel-main-menu', 'data-last-item', $settings['menu_last_item'] );
			}

			if ( $settings['pointer'] ) {
				if ( 'horizontal' === $settings['layout'] || 'vertical' === $settings['layout'] ) {
					$this->add_render_attribute( 'eel-main-menu', 'class', 'eel-pointer__' . $settings['pointer'] );

					if ( in_array( $settings['pointer'], [ 'double-line', 'underline', 'overline' ], true ) ) {
						$key = 'animation_line';
						$this->add_render_attribute( 'eel-main-menu', 'class', 'eel-animation__' . $settings[ $key ] );
					} elseif ( 'framed' === $settings['pointer'] || 'text' === $settings['pointer'] ) {
						$key = 'animation_' . $settings['pointer'];
						$this->add_render_attribute( 'eel-main-menu', 'class', 'eel-animation__' . $settings[ $key ] );
					}
				}
			}

			if ( 'expandible' === $settings['layout'] ) {
				$this->add_render_attribute( 'eel-nav-menu', 'class', 'eel-dropdown-expandible' );
			}


			$this->add_render_attribute(
				'eel-nav-menu',
				'class',
				[
					'eel-nav-menu__layout-' . $settings['layout'],
				]
			);

			$this->add_render_attribute( 'eel-nav-menu', 'data-toggle-icon', $menu_close_icons[0] );

			$this->add_render_attribute( 'eel-nav-menu', 'data-close-icon', $menu_close_icons[1] );

			$this->add_render_attribute( 'eel-nav-menu', 'data-full-width', $settings['full_width_dropdown'] );

			?>
			
			<div <?php $this->print_render_attribute_string( 'eel-main-menu' ); ?>>
				<div role="button" class="eel-nav-menu__toggle elementor-clickable">
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'easy-elements' ); ?></span>
					<div class="eel-nav-menu-icon">
						<?php
						$menu_close_icons[0] = str_replace( 'tabindex="0"', '', $menu_close_icons[0] );
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe, output is trusted HTML/icon.
						echo isset( $menu_close_icons[0] ) ? $menu_close_icons[0] : '';
						?>
					</div>
				</div>

				<?php
					$show_mobile_on_sidebar = isset( $settings['show_mobile_on_sidebar'] ) && 'yes' === $settings['show_mobile_on_sidebar'];

					if ( $show_mobile_on_sidebar ) {
						$this->add_render_attribute( 'eel-nav-menu-mobile', 'class', 'sidebar-on-mobile' );

						
						$widget_instance = $this;

						add_action( 'wp_footer', function() use ( $args, $widget_instance ) {
							?>
							<nav <?php $widget_instance->print_render_attribute_string( 'eel-nav-menu-mobile' ); ?>>
								<span class="eel-nav-menu-icon">
								<i class="unicon-close"></i>
								</span>
								<?php
								echo wp_kses_post(
									wp_nav_menu(
										array_merge(
											$args,
											[ 'echo' => false ]
										)
									)
								);
								?>
							</nav>
							<?php
						});
					}
					?>

				<nav <?php $this->print_render_attribute_string( 'eel-nav-menu' ); ?>>
					<?php echo wp_nav_menu( $args ); ?> 
				</nav>						
			</div>
			<?php		
		
			if ( $settings['enable_sticky_header'] === 'yes' ) {
				wp_enqueue_script(
					'eel-sticky-header',
					plugin_dir_url(__FILE__) . 'js/eel-sticky-header.js',
					array('jquery'),
					'1.0',
					true
				);
			}						
	}
}
