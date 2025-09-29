<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Easyel_Elements_Elementor_Extension {

	const VERSION = '1.0.0';

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}


	public function init() {
		// Safety checks

		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_categories' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

		// Assets
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );	
	}

	public function enqueue_frontend_assets() {
		$dir = plugin_dir_url( __FILE__ );
		if ( is_admin() ) return;
		wp_enqueue_style( 'swiper', ELEMENTOR_ASSETS_URL . 'lib/swiper/css/swiper-bundle.min.css', [], '8.0.7' );
		wp_enqueue_style( 'eel-elements-plugins', $dir . 'assets/css/plugins.css', [], '1.0.0' );		
		wp_enqueue_script( 'swiper', ELEMENTOR_ASSETS_URL . 'lib/swiper/swiper-bundle.min.js', [ 'jquery' ], '8.0.7', true );
		wp_enqueue_script( 'eel-plugins', $dir . 'assets/js/plugins.js', [ 'jquery' ], '2.2.1', true );
		wp_enqueue_script( 'eel-custom-js', $dir . 'assets/js/custom.js', [ 'jquery' ], '1.0.0', true );		
	}


	public function enqueue_admin_styles() {
		$dir = plugin_dir_url( __FILE__ );
		wp_enqueue_style( 'e-e-elements-admin', $dir . 'assets/css/admin/admin.css', [], self::VERSION );
	}

	public function enqueue_editor_scripts() {
		wp_enqueue_style( 'eel-elements-editor', plugin_dir_url( __FILE__ ) . 'assets/css/editor.css', [], self::VERSION );
	}


	public function add_elementor_categories( $elements_manager ) {
		$elements_manager->add_category( 'easyelements_category', [
			'title' => esc_html__( 'Easy Elements', 'easy-elements' ),
			'icon'  => 'fa fa-plug',
		] );
		$elements_manager->add_category( 'easyelements_post_category', [
			'title' => esc_html__( 'Easy Post Type Elements', 'easy-elements' ),
			'icon'  => 'fa fa-file-alt',
		] );
		$elements_manager->add_category( 'easyelements_header_footer_category', [
			'title' => esc_html__( 'Easy Header Footer Elements', 'easy-elements' ),
			'icon'  => 'fa fa-header',
		] );
		$elements_manager->add_category( 'easyelements_category_pro', [
			'title' => esc_html__( 'Easy Elements Pro', 'easy-elements-pro' ),
			'icon'  => 'fa fa-plug',
		] );
	}

	public function init_widgets() {
	    $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
	    $widgets = [
			'site_logo'     				=> [ 'class' => '\Easyel_Site_Logo_Widget',        		'file' => __DIR__ . '/widgets/site-logo/site-logo.php' ],
			'heading'       				=> [ 'class' => '\Easyel_Heading_Widget',           		'file' => __DIR__ . '/widgets/heading/heading.php' ],
			'clients_logo'  				=> [ 'class' => '\Easyel_Clients_Logo__Widget',     		'file' => __DIR__ . '/widgets/clients-logo-grid/logo.php' ],
			'icon_box'  					=> [ 'class' => '\Easyel_Icon_Box__Widget',    			'file' => __DIR__ . '/widgets/icon-box/icon.php' ],
			'tab'           				=> [ 'class' => '\Easyel_Tab_Widget',               		'file' => __DIR__ . '/widgets/tab/tab.php' ],
			'testimonials'  				=> [ 'class' => '\Easyel_Testimonials__Widget',     		'file' => __DIR__ . '/widgets/testimonials-grid/testimonials.php' ],
			'team_grid'  					=> [ 'class' => '\Easyel_Team_Grid__Widget',    			'file' => __DIR__ . '/widgets/team-grid/team-grid.php' ],
			'search'  						=> [ 'class' => '\Easyel_Search_Widget',    				'file' => __DIR__ . '/widgets/search/search.php' ],
			'contact_box'  					=> [ 'class' => '\Easyel_Contact_Box__Widget',    		'file' => __DIR__ . '/widgets/contact-box/contact.php' ],
			'faq'  							=> [ 'class' => '\Easyel_FAQ_Accordion_Widget',    		'file' => __DIR__ . '/widgets/faq/faq.php' ],
			'blog_grid'  					=> [ 'class' => '\Easyel_Blog_Grid__Widget',    			'file' => __DIR__ . '/widgets/blog-grid/blog-grid.php' ],
			'video'  						=> [ 'class' => '\Easyel_Video_Popup_Widget',    			'file' => __DIR__ . '/widgets/video/video.php' ],
			'pricing_table'  				=> [ 'class' => '\Easyel_Pricing_Table_Widget',    		'file' => __DIR__ . '/widgets/pricing-table/pricing.php' ],
			'pricing_list'  				=> [ 'class' => '\Easyel_Pricing_Table_List_Widget',    	'file' => __DIR__ . '/widgets/pricing-list/pricing-list.php' ],
			'service_list'  				=> [ 'class' => '\Easyel_Service_List_Widget',    		'file' => __DIR__ . '/widgets/service-list/service-list.php' ],
			'navigation_menu'  				=> [ 'class' => '\Easyel_Navigation_Menu_Widget',    		'file' => __DIR__ . '/widgets/navigation-menu/navigation-menu.php' ],
			'page_title'  					=> [ 'class' => '\Easyel_Page_Title_Widget',    			'file' => __DIR__ . '/widgets/page-title/page-title.php' ],
			'button'  						=> [ 'class' => '\Easyel_Button_Widget',    				'file' => __DIR__ . '/widgets/button/button.php' ],
			'social_share'  				=> [ 'class' => '\Easyel_Social_Share_Widget',    		'file' => __DIR__ . '/widgets/social-share/social-share.php' ],
			'social_icon'  					=> [ 'class' => '\Easyel_Social_Icon_Widget',    			'file' => __DIR__ . '/widgets/social-icon/social.php' ],
			'breadcrumb'  					=> [ 'class' => '\Easyel_Breadcrumb_Widget',    			'file' => __DIR__ . '/widgets/breadcrumb/breadcrumb.php' ],
			'domain_search'  				=> [ 'class' => '\Easyel_Domain_Search_Widget',    		'file' => __DIR__ . '/widgets/domain-search/domain-search.php' ],
			'easy_offcanvas'  				=> [ 'class' => '\Easyel_Offcanvas_Widget',    			'file' => __DIR__ . '/widgets/offcanvas/offcanvas.php' ],
			'easy_scroll_to_top'  			=> [ 'class' => '\Easyel_Scroll_To_Top_Widget',    			'file' => __DIR__ . '/widgets/scroll-to-top/scroll.php' ],
		];

	    foreach ( $widgets as $key => $data ) {
			if ( get_option( 'easy_element_' . $key, '1' ) !== '1' ) {
				continue;
			}
			if ( file_exists( $data['file'] ) ) {
				require_once $data['file'];
				if ( class_exists( $data['class'] ) ) {
					$widgets_manager->register( new $data['class']() );
				}
			}
		}
	}
}

Easyel_Elements_Elementor_Extension::instance();