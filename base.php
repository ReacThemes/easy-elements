<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Easyel_Elements_Elementor_Extension {

	const VERSION = EASYELEMENTS_VER;

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
		wp_enqueue_style( 'easy-hfe-elementor', EASYELEMENTS_ASSETS_URL . 'header-footer/css/easy-hfe-elementor.css', [], self::VERSION  );

		
		wp_enqueue_style( 'eel-elements-plugins', $dir . 'assets/css/plugins.css', [], self::VERSION );		
		wp_enqueue_script( 'swiper', ELEMENTOR_ASSETS_URL . 'lib/swiper/swiper-bundle.min.js', [ 'jquery' ], '8.0.7', true );
		wp_enqueue_script( 'eel-plugins', $dir . 'assets/js/plugins.js', [ 'jquery' ], '2.2.1', true );
		wp_enqueue_script( 'eel-custom-js', $dir . 'assets/js/custom.js', [ 'jquery' ], self::VERSION, true );		
	}


	public function enqueue_admin_styles() {
		$dir = plugin_dir_url( __FILE__ );
		wp_enqueue_style( 'e-e-elements-admin', $dir . 'assets/css/admin/admin.css', [], self::VERSION );
		wp_enqueue_style( 'e-e-easy-custom-icons', $dir . 'admin/icons/css/easy-icons.css', [], self::VERSION );
		wp_enqueue_style('e-e-admin-fonts-inter','https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',false);
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
		$elements_manager->add_category( 'easyelements_category_form', [
			'title' => esc_html__( 'Easy Elements Form', 'easy-elements' ),
			'icon'  => 'fa fa-plug',
		] );
		$elements_manager->add_category( 'easyelements_category_pro', [
			'title' => esc_html__( 'Easy Elements Pro', 'easy-elements-pro' ),
			'icon'  => 'fa fa-plug',
		] );		
	}

	public function init_widgets() {
	    $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
	    $widgets = [
			'site_logo'     				=> [ 'class' => '\Easyel_Site_Logo_Widget',        		'file' => __DIR__ . '/widgets/site-logo/site-logo.php','tab' => 'widget' ],
			'heading'       				=> [ 'class' => '\Easyel_Heading_Widget',           		'file' => __DIR__ . '/widgets/heading/heading.php','tab' => 'widget'  ],
			'clients_logo'  				=> [ 'class' => '\Easyel_Clients_Logo__Widget',     		'file' => __DIR__ . '/widgets/clients-logo-grid/logo.php','tab' => 'widget' ],
			'icon_box'  					=> [ 'class' => '\Easyel_Icon_Box__Widget',    			'file' => __DIR__ . '/widgets/icon-box/icon.php','tab' => 'widget' ],
			'tab'           				=> [ 'class' => '\Easyel_Tab_Widget',               		'file' => __DIR__ . '/widgets/tab/tab.php','tab' => 'widget' ],
			'testimonials'  				=> [ 'class' => '\Easyel_Testimonials__Widget',     		'file' => __DIR__ . '/widgets/testimonials-grid/testimonials.php' ,'tab' => 'widget' ],
			'team_grid'  					=> [ 'class' => '\Easyel_Team_Grid__Widget',    			'file' => __DIR__ . '/widgets/team-grid/team-grid.php','tab' => 'widget' ],
			'search'  						=> [ 'class' => '\Easyel_Search_Widget',    				'file' => __DIR__ . '/widgets/search/search.php' ,'tab' => 'widget'],
			'contact_box'  					=> [ 'class' => '\Easyel_Contact_Box__Widget',    		'file' => __DIR__ . '/widgets/contact-box/contact.php','tab' => 'widget' ],
			'faq'  							=> [ 'class' => '\Easyel_FAQ_Accordion_Widget',    		'file' => __DIR__ . '/widgets/faq/faq.php' ,'tab' => 'widget'],
			'blog_grid'  					=> [ 'class' => '\Easyel_Blog_Grid__Widget',    			'file' => __DIR__ . '/widgets/blog-grid/blog-grid.php','tab' => 'widget' ],
			'video'  						=> [ 'class' => '\Easyel_Video_Popup_Widget',    			'file' => __DIR__ . '/widgets/video/video.php','tab' => 'widget' ],
			'pricing_table'  				=> [ 'class' => '\Easyel_Pricing_Table_Widget',    		'file' => __DIR__ . '/widgets/pricing-table/pricing.php' ,'tab' => 'widget'],
			'pricing_list'  				=> [ 'class' => '\Easyel_Pricing_Table_List_Widget',    	'file' => __DIR__ . '/widgets/pricing-list/pricing-list.php','tab' => 'widget' ],
			'service_list'  				=> [ 'class' => '\Easyel_Service_List_Widget',    		'file' => __DIR__ . '/widgets/service-list/service-list.php','tab' => 'widget' ],
			'navigation_menu'  				=> [ 'class' => '\Easyel_Navigation_Menu_Widget',    		'file' => __DIR__ . '/widgets/navigation-menu/navigation-menu.php','tab' => 'widget' ],
			'page_title'  					=> [ 'class' => '\Easyel_Page_Title_Widget',    			'file' => __DIR__ . '/widgets/page-title/page-title.php','tab' => 'widget' ],
			'button'  						=> [ 'class' => '\Easyel_Button_Widget',    				'file' => __DIR__ . '/widgets/button/button.php','tab' => 'widget' ],
			'social_share'  				=> [ 'class' => '\Easyel_Social_Share_Widget',    		'file' => __DIR__ . '/widgets/social-share/social-share.php','tab' => 'widget' ],
			'social_icon'  					=> [ 'class' => '\Easyel_Social_Icon_Widget',    			'file' => __DIR__ . '/widgets/social-icon/social.php','tab' => 'widget' ],
			'breadcrumb'  					=> [ 'class' => '\Easyel_Breadcrumb_Widget',    			'file' => __DIR__ . '/widgets/breadcrumb/breadcrumb.php','tab' => 'widget' ],
			'domain_search'  				=> [ 'class' => '\Easyel_Domain_Search_Widget',    		'file' => __DIR__ . '/widgets/domain-search/domain-search.php','tab' => 'widget' ],
			'easy_offcanvas'  				=> [ 'class' => '\Easyel_Offcanvas_Widget',    			'file' => __DIR__ . '/widgets/offcanvas/offcanvas.php' ,'tab' => 'widget'],
			'easy_scroll_to_top'  			=> [ 'class' => '\Easyel_Scroll_To_Top_Widget',    			'file' => __DIR__ . '/widgets/scroll-to-top/scroll.php' ,'tab' => 'widget'],
			'easy_table'  					=> [ 'class' => '\Easyel_Table_Elementor_Widget',    				'file' => __DIR__ . '/widgets/table/table-normal.php','tab' => 'widget' ],
			'easy_cf7'  					=> [ 'class' => '\easyel__CF7_Widget',    				'file' => __DIR__ . '/widgets/cf7/contact-cf7.php','tab' => 'widget' ],
			'easy_gallery'  					=> [ 'class' => '\Easyel__Gallery_Widget',    				'file' => __DIR__ . '/widgets/gallery/gallery.php','tab' => 'widget' ],
		];

	    foreach ( $widgets as $key => $data ) {
			$option_name = 'easy_element_' . $data['tab'] . '_' . $key;
			$enabled = get_option($option_name, '1');
			if ( $enabled !== '1' ) {
				continue; // Skip disabled
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