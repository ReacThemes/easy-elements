<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Elements {

    // Singleton instance
    private static $instance = null;

    private function __construct() {
        // Hooks
        
        // Custom Font Load Free

        add_action( 'admin_menu', array( $this, 'easyel_elements_settings_menu' ) );
        add_action( 'admin_init', array( $this, 'easyel_elements_register_settings' ) );

        $this->easyel_settings_ajax();
       
    }

    public function easyel_settings_ajax( ) {
        
        add_action('wp_ajax_easyel_save_js_animation', array ( $this, 'easyel_save_js_animation') ); 
        add_action('wp_ajax_easyel_save_cursor', array( $this, 'easyel_save_cursor') );
        // AJAX handler for saving minify js option
        add_action('wp_ajax_easy_elements_save_minify_js', array( $this, "easy_elements_save_minify_js") );
        add_action('wp_ajax_easy_elements_save_minify_css', array( $this, "easy_elements_save_minify_css") );

        add_action('wp_ajax_easy_elements_save_widget_setting', array( $this, "easy_elements_save_widget_setting" ) );
        add_action('wp_ajax_easy_elements_bulk_action', array( $this, 'easy_elements_bulk_action') );
        add_action( 'admin_enqueue_scripts', array( $this, 'easyel_elements_enqueue_admin_hide_notices_css' ) );
        add_action('wp_ajax_easy_elements_toggle_widget', array( $this, 'easyel_elements_toggle_widget_callback') ) ;
        add_action('admin_enqueue_scripts', array( $this, 'easyel_elements_enqueue_admin_assets') );
        add_action( 'admin_head', array( $this, 'easyel_hide_admin_notices' ) );
    }

    // Singleton get_instance
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function easyel_elements_settings_menu() {

        // Main Menu
        add_menu_page(
            __('Easy Elements', 'easy-elements'),
            __('Easy Elements', 'easy-elements'),
            'manage_options',
            'easy-elements-dashboard',
            array( $this, 'easyel_elements_settings_callback' ),
            'dashicons-layout',
            59
        );

        global $submenu;

        // Main slug
        $slug = 'easy-elements-dashboard';

        $submenu[$slug][] = [ __('Overview', 'easy-elements'), 'manage_options', 'admin.php?page='.$slug.'#overview' ];
        $submenu[$slug][] = [ __('Widgets', 'easy-elements'), 'manage_options', 'admin.php?page='.$slug.'#widget' ];
        $submenu[$slug][] = [ __('All Extensions', 'easy-elements'), 'manage_options', 'admin.php?page='.$slug.'#extensions' ];
        $submenu[$slug][] = [ __('Advance Settings', 'easy-elements'), 'manage_options', 'admin.php?page='.$slug.'#advsettings' ];

        // Header & Footer Submenu
        add_submenu_page(
            'easy-elements-dashboard',
            __('Header & Footer', 'easy-elements'),
            __('Header & Footer', 'easy-elements'),
            'manage_options',
            'edit.php?post_type=ee-elementor-hf'
        );

        // Upload Custom Fonts Submenu
        add_submenu_page(
            'easy-elements-dashboard',
            __('Upload Custom Fonts', 'easy-elements'),
            __('Upload Custom Fonts', 'easy-elements'),
            'manage_options',
            'easyel-custom-fonts',
            array( $this, 'easyel_custom_fonts_page_html' )
        );

    }

    public function easyel_elements_register_settings() {
        register_setting( 'easyel_elements_extensions_group', 'easyel_enable_js_animation', [
            'sanitize_callback' => 'absint',
            'default' => 0,
        ] );

        // Cursor setting register
        register_setting( 'easyel_elements_extensions_group', 'easyel_enable_cursor', [
            'sanitize_callback' => 'absint',
            'default' => 0,
        ] );
    }

    // AJAX handler for JS Animation checkbox
    public function easyel_save_js_animation() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }
        check_ajax_referer('easyel_js_animation_nonce', 'nonce');
        $value = isset($_POST['value']) && $_POST['value'] == '1' ? 1 : 0;
        update_option('easyel_enable_js_animation', $value);
        wp_send_json_success();
    }


    // AJAX handler for Cursor checkbox
    public function easyel_save_cursor() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }
        check_ajax_referer('easy_elements_nonce', 'nonce');
        $value = isset($_POST['value']) && $_POST['value'] === '1' ? '1' : '0';
        update_option('easyel_enable_cursor', $value);
        wp_send_json_success(['message' => 'Cursor setting saved']);
    }


    // AJAX handler for saving minify css option
    public function easy_elements_save_minify_css() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }
        check_ajax_referer('easy_elements_save_advance_settings_nonce', 'nonce');
        $minify_css = isset($_POST['minify_css']) && $_POST['minify_css'] === '1' ? '1' : '0';
        update_option('easyel_elements_minify_css', $minify_css);
        wp_send_json_success();
    }

    public function easy_elements_save_minify_js( ) {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }
        check_ajax_referer('easy_elements_save_advance_settings_nonce', 'nonce');
        $minify_js = isset($_POST['minify_js']) && $_POST['minify_js'] === '1' ? '1' : '0';
        update_option('easyel_elements_minify_js', $minify_js);
        wp_send_json_success();
    }

    // AJAX handler for saving individual widget settings
  
    public function easy_elements_save_widget_setting() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }
        check_ajax_referer('easy_elements_widget_settings_nonce', 'nonce');
        
        $widget_key = isset($_POST['widget_key']) ? sanitize_text_field(wp_unslash($_POST['widget_key'])) : '';
        $status = isset($_POST['status']) && $_POST['status'] === '1' ? '1' : '0';
        
        if (!empty($widget_key)) {
            update_option('easy_element_' . $widget_key, $status);
            wp_send_json_success(['message' => 'Widget setting updated successfully']);
        } else {
            wp_send_json_error(['message' => 'Invalid widget key']);
        }
    }

    // AJAX handler for bulk actions
    public function easy_elements_bulk_action() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }
        check_ajax_referer('easy_elements_bulk_action_nonce', 'nonce');
        
        $bulk_action = isset($_POST['bulk_action']) ? sanitize_text_field(wp_unslash($_POST['bulk_action'])) : '';
        $status = $bulk_action === 'activate_all' ? '1' : '0';
        
        $available_elements = $this->easyel_elements_get_available_widgets();
        $updated_count = 0;

        $is_pro_active = class_exists('Easy_Elements_Pro');

        foreach ($available_elements as $key => $widget) {
            if (!$is_pro_active && isset($widget['is_pro']) && $widget['is_pro']) {
                update_option('easy_element_' . $key, '0');
            } else {
                update_option('easy_element_' . $key, $status);
            }
            $updated_count++;
        }
        
        wp_send_json_success([
            'message' => sprintf('%d widgets %s successfully', $updated_count, $status ? 'activated' : 'deactivated'),
            'count' => $updated_count,
            'is_pro_active' => $is_pro_active
        ]);
    }


    public function easyel_elements_enqueue_admin_hide_notices_css( $hook ) {
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        $allowed_pages     = [ 'easy-elements-dashboard', 'easy-elements-settings' ];
        $allowed_post_type = 'ee-elementor-hf';
        // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
        $is_custom_page     = isset( $_GET['page'] ) && in_array( $_GET['page'], $allowed_pages, true );
        $is_custom_posttype = $screen && in_array( $screen->post_type, [ $allowed_post_type ], true );
        if ( $is_custom_page || $is_custom_posttype ) {
            // Check if optimize_css is enabled, if so, skip loading CSS
            if (get_option('easyel_elements_minify_css', '0') === '1') {
                return;
            }
            wp_enqueue_style(
                'ee-admin-hide-notices',
                plugin_dir_url(__DIR__) . 'assets/css/admin/admin-hide-notices.css',
                [],
                '1.0.0'
            );
        }
    }

    function easyel_elements_settings_callback() {
        $available_elements = $this->easyel_elements_get_available_widgets();

        $easyel_tabs = [
            'overview'   => __('Overview', 'easy-elements'),
            'widget'     => __('All Widget', 'easy-elements'),
            'extensions' => __('All Extensions', 'easy-elements'),
            'advsettings' => __('Advanced Settings', 'easy-elements'),
        ];

        $easyel_tab_list = apply_filters("easyel_all_tab_list",  $easyel_tabs );
        ?>
        <div class="wrap easyel-plugin-settings-wrapper">

            <div class="easyel-nav-tab-wrapper">
                <a href="#overview" class="easyel-nav-tab easyel-nav-tab-active" data-tab="overview"><?php esc_html_e ('Overview','easy-elements'); ?></a>
                <a href="#widget" class="easyel-nav-tab" data-tab="widget"><?php esc_html_e('All Widget','easy-elements'); ?></a>
                <a href="#extensions" class="easyel-nav-tab" data-tab="extensions"><?php esc_html_e('All Extensions','easy-elements'); ?></a>
                <a href="#advsettings" class="easyel-nav-tab" data-tab="advsettings"><?php esc_html_e('Advanced Settings','easy-elements'); ?></a>
            </div>
            <!-- Status Messages -->
            <div id="bulk-action-message" class="notice" style="display: none;"></div>

            <!-- Tab Content -->
            <div id="easyel-tab-content">
                <?php foreach ( $easyel_tabs as $tab_slug => $tab_label ) : ?>
                    <div id="tab-<?php echo esc_attr($tab_slug); ?>" 
                        class="easyel-tab-panel" 
                        style="<?php echo $tab_slug === 'overview' ? '' : 'display:none;'; ?>">

                        <?php 
                       
                        if ( $tab_slug === 'widget' ) : ?>
                            <div class="eel-addon-search">
                                <div class="easyel-widget-search-enable">
                                    <input type="text" id="element-search" placeholder="<?php esc_attr_e('Search widgets...', 'easy-elements'); ?>">
                                    <button type="button" id="activate-all-btn" class="button button-secondary"><?php esc_html_e('Activate All', 'easy-elements'); ?></button>
                                    <button type="button" id="deactivate-all-btn" class="button button-secondary"><?php esc_html_e('Deactivate All', 'easy-elements'); ?></button>
                                    
                                </div>
                                <div class="easyel-widget-filter">
                                    <button type="button" id="easyel_all" class="easyel-action-btn button button-secondary" data-filter="easyel_all"><?php esc_html_e('All', 'easy-elements'); ?></button>
                                    <button type="button" id="easyel_free" class="easyel-action-btn button button-secondary" data-filter="easyel_free"><?php esc_html_e('Free', 'easy-elements'); ?></button>
                                    <button type="button" id="easyel_pro" class="easyel-action-btn button button-secondary" data-filter="easyel_pro"><?php esc_html_e('Pro', 'easy-elements'); ?></button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php 
                        $tab_file = EASYELEMENTS_DIR_PATH . '/admin/settingstab/tab-' . $tab_slug . '.php';

                        if ( file_exists( $tab_file ) ) {
                            include $tab_file; 
                        } 
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- JavaScript functionality is handled by admin.js -->
        <?php
    }

    ////////****************** AJAX Toggle ********************************
   
    public function easyel_elements_toggle_widget_callback() {
        if (!current_user_can('manage_options') || !check_ajax_referer('easy_elements_nonce', 'nonce', false)) {
            wp_send_json_error('Unauthorized');
        }
        $key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
        $status = (isset($_POST['status']) && $_POST['status'] === '1') ? '1' : '0';
        update_option('easy_element_' . $key, $status);
        wp_send_json_success('Updated');
    }

    public function Easyel_Elements_enqueue_admin_assets($hook) {
        if (strpos($hook, 'easy-elements') === false) {
            return;
        }
        
        // Enqueue admin JavaScript
        wp_enqueue_script(
            'easy-elements-admin',
            plugin_dir_url(__DIR__) . 'assets/js/admin.js',
            ['jquery'],
            '1.0.0',
            true
        );
        
        // Localize script with all necessary data
        wp_localize_script('easy-elements-admin', 'easyElementsData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('easy_elements_nonce'),
            'widget_settings_nonce' => wp_create_nonce('easy_elements_widget_settings_nonce'),
            'bulk_action_nonce' => wp_create_nonce('easy_elements_bulk_action_nonce'),
            'advance_settings_nonce' => wp_create_nonce('easy_elements_save_advance_settings_nonce'),
            'js_animation_nonce' => wp_create_nonce('easyel_js_animation_nonce'),
            'strings' => [
                'confirm_activate_all' => __('Are you sure you want to activate all widgets?', 'easy-elements'),
                'confirm_deactivate_all' => __('Are you sure you want to deactivate all widgets?', 'easy-elements'),
                'processing' => __('Processing...', 'easy-elements'),
                'saving' => __('Saving...', 'easy-elements'),
                'saved' => __('Saved!', 'easy-elements'),
                'error' => __('Error!', 'easy-elements'),
                'updated' => __('Updated!', 'easy-elements'),
            ]
        ]);
    }

    function easyel_elements_get_available_widgets() {
        $widgets = [
           
            'heading' => [
                'icon'        => 'dashicons-editor-textcolor',
                'title'       => 'Easy Heading',
                'description' => 'Add customizable headings with style options.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'clients_logo' => [
                'icon'        => 'dashicons-groups',
                'title'       => 'Easy Clients Logo Grid',
                'description' => 'Showcase client logos in a neat grid layout.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'clients_logo_slider' => [
                'icon'        => 'dashicons-format-gallery',
                'title'       => 'Easy Clients Logo Slider',
                'description' => 'Display client logos in a slider format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'tab' => [
                'icon'        => 'dashicons-editor-insertmore',
                'title'       => 'Easy Tab',
                'description' => 'Add simple tab content.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'tab_advance' => [
                'icon'        => 'dashicons-editor-insertmore',
                'title'       => 'Easy Advanced Tab',
                'description' => 'Create advanced tab content.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'testimonials' => [
                'icon'        => 'dashicons-format-quote',
                'title'       => 'Easy Testimonials Grid',
                'description' => 'Show testimonials in a grid format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'testimonials_slider' => [
                'icon'        => 'dashicons-format-gallery',
                'title'       => 'Easy Testimonials Slider',
                'description' => 'Display testimonials in a slider format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'image_carousel' => [
                'icon'        => 'dashicons-format-gallery',
                'title'       => 'Image Carousel',
                'description' => 'Create an image slider with multiple images.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'icon_box' => [
                'icon'        => 'dashicons-smiley',
                'title'       => 'Easy Icon Box',
                'description' => 'Display content with an icon.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'process_grid' => [
                'icon'        => 'dashicons-screenoptions',
                'title'       => 'Easy Process Grid',
                'description' => 'Show process steps in a grid format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'process_slider' => [
                'icon'        => 'dashicons-controls-repeat',
                'title'       => 'Easy Process Slider',
                'description' => 'Show process steps in a slider.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'team_grid' => [
                'icon'        => 'dashicons-groups',
                'title'       => 'Easy Team Grid',
                'description' => 'Display team members in a grid format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'team_slider' => [
                'icon'        => 'dashicons-controls-play',
                'title'       => 'Easy Team Slider',
                'description' => 'Showcase team members in a slider format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'contact_box' => [
                'icon'        => 'dashicons-phone',
                'title'       => 'Easy Contact Box',
                'description' => 'Easy Contact.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],        
            'icon_box_slider' => [
                'icon'        => 'dashicons-format-gallery',
                'title'       => 'Easy Icon Box Slider',
                'description' => 'Easy Icon Box Slider.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],  
            'timeline_slider' => [
                'icon'        => 'dashicons-admin-post',    
                'title'       => 'Easy Timeline Slider',
                'description' => 'Easy Timeline Slider.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],      
            'faq' => [
                'icon'        => 'dashicons-editor-help',
                'title'       => 'Easy FAQ',
                'description' => 'Easy FAQ.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],       
            'blog_grid' => [
                'icon'        => 'dashicons-format-gallery',
                'title'       => 'Easy Post Grid',
                'description' => 'Easy Post.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],        
            'post_slider' => [
                'icon'        => 'dashicons-format-gallery',
                'title'       => 'Easy Post Slider',
                'description' => 'Easy Post Slider.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],        
            'video' => [
                'icon'        => 'dashicons-format-video',
                'title'       => 'Easy Video',
                'description' => 'Easy Video.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'pricing_table' => [
                'icon'        => 'dashicons-editor-table',
                'title'       => 'Easy Pricing Table',
                'description' => 'Easy Pricing Table.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],        
            'pricing_list' => [
                'icon'        => 'dashicons-editor-table',
                'title'       => 'Easy Pricing List',
                'description' => 'Easy Pricing List.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],        
            'service_list' => [
                'icon'        => 'dashicons-editor-table',
                'title'       => 'Easy Service List',
                'description' => 'Easy Service List.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'process_list' => [
                'icon'        => 'dashicons-editor-table',
                'title'       => 'Easy Process List',
                'description' => 'Easy Process List.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'marquee_logo' => [
                'icon'        => 'dashicons-format-gallery',
                'title'       => 'Easy Marquee Logo',
                'description' => 'Easy Marquee Logo.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'button' => [
                'icon'        => 'dashicons-controls-buoy',
                'title'       => 'Easy Button',
                'description' => 'Easy Button.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],        
           
            'social_share' => [
                'icon'        => 'dashicons-share',
                'title'       => 'Easy Social Share',
                'description' => 'Easy Social Share.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'social_icon' => [
                'icon'        => 'dashicons-share',
                'title'       => 'Easy Social Icon',
                'description' => 'Easy Social Icon.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
           
            'breadcrumb' => [
                'icon'        => 'dashicons-admin-post',
                'title'       => 'Easy Breadcrumb',
                'description' => 'Easy Breadcrumb.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'easy_slider' => [
                'icon'        => 'dashicons-format-gallery',
                'title'       => 'Easy Slider',
                'description' => 'Easy Slider.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'image_accordion' => [
                'icon'        => 'dashicons-format-gallery',
                'title'       => 'Easy Image Accordion',
                'description' => 'Easy Image Accordion',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'domain_search' => [
                'icon'        => 'dashicons-admin-post',    
                'title'       => 'Easy Domain Search',
                'description' => 'Easy Domain Search.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'featured_project' => [
                'icon'        => 'dashicons-admin-post',    
                'title'       => 'Easy Custom Projects',
                'description' => 'Easy Custom Projects.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false
            ],
            'advance_button' => [
                'icon'        => 'dashicons-admin-post',    
                'title'       => 'Easy Advance Button',
                'description' => 'Easy Advance Button.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ],
            'hr_image_scroll' => [
                'icon'        => 'dashicons-admin-post',    
                'title'       => 'Easy Image Horizontal Scroll',
                'description' => 'Easy Image Horizontal Scroll.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true
            ], 

            'easy_offcanvas' => [
                'icon'        => 'dashicons-admin-post',    
                'title'       => 'Easy Offcanvas',
                'description' => 'Easy Offcanvas.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget'
            ],
            'site_logo' => [
                'icon'        => 'dashicons-format-image',
                'title'       => 'Easy Site Logo',
                'description' => 'Display your website logo easily with this widget.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget'
            ],
            'search' => [
                'icon'        => 'dashicons-search',
                'title'       => 'Easy Search',
                'description' => 'Easy Search All Content.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget'
            ],
            'navigation_menu' => [
                'icon'        => 'dashicons-search',
                'title'       => 'Easy Navigation Menu',
                'description' => 'Easy Navigation Menu.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget'
            ],
            'page_title' => [
                'icon'        => 'dashicons-admin-page',
                'title'       => 'Easy Page Title',
                'description' => 'Easy Page Title.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget'
            ],


            'post_tags' => [
                'icon'        => 'dashicons-admin-post',    
                'title'       => 'Easy Current Post Tags',
                'description' => 'Easy Current Post Tags.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ],
            'post_author' => [
                'icon'        => 'dashicons-admin-post',    
                'title'       => 'Easy Current Post Author',
                'description' => 'Easy Current Post Author.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ],
            'post_title' => [
                'icon'        => 'dashicons-admin-post',
                'title'       => 'Easy Post Title',
                'description' => 'Easy Post Title.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ],
            'post_content' => [
                'icon'        => 'dashicons-admin-post',
                'title'       => 'Easy Post Content',
                'description' => 'Easy Post Content.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ],        
            'excerpt' => [
                'icon'        => 'dashicons-admin-post',
                'title'       => 'Easy Post Excerpt',
                'description' => 'Easy Post Excerpt.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ],  
            'related_post' => [
                'icon'        => 'dashicons-admin-post',
                'title'       => 'Easy Related Post',
                'description' => 'Easy Related Post.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ], 
            'post_pagination' => [
                'icon'        => 'dashicons-admin-post',
                'title'       => 'Easy Post Pagination',
                'description' => 'Easy Post Pagination.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ],
            'post_meta' => [
                'icon'        => 'dashicons-admin-post',
                'title'       => 'Easy Post Meta',
                'description' => 'Easy Post Meta.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ],
            'post_comments' => [
                'icon'        => 'dashicons-admin-comments',
                'title'       => 'Easy Post Comments',
                'description' => 'Easy Post Comments.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ],
            'featured_image' => [
                'icon'        => 'dashicons-format-image',
                'title'       => 'Easy Featured Image',
                'description' => 'Easy Featured Image.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ],
            
        ];

        return apply_filters( 'easyel_available_widgets', $widgets );
    }

    /**
     * Admin page HTML
     */
    function easyel_custom_fonts_page_html() {
        if ( ! current_user_can( 'manage_options' ) ) return;

        if ( ! defined( 'EASY_ELEMENTS_PRO_ACTIVE' ) || ! EASY_ELEMENTS_PRO_ACTIVE ) {
            echo '<div class="wrap"><h1>' . esc_html__( 'Upload Custom Fonts', 'easy-elements' ) . '</h1>';
            echo '<p style="font-size:16px;color:#cc0000;">' . esc_html__( 'This feature is available in Easy Elements Pro. Please install and activate the Pro version to use it.', 'easy-elements' ) . '</p>';
            echo '</div>';
            return;
        }

        easyel_pro_custom_fonts_page();
    }

    function easyel_hide_admin_notices() {
        $screen = get_current_screen();
        if ( $screen && $screen->id === 'toplevel_page_easy-elements-dashboard' ) {
            echo '<style>
                .notice, .updated, .error, .update-nag, .notice-success, .notice-error, .notice-warning {
                    display: none !important;
                }
            </style>';
        }
    }

}

// Initialize the plugin
Easyel_Elements::get_instance();