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
        
        // AJAX handler for saving minify js option

        add_action('wp_ajax_easy_elements_save_widget_setting', array( $this, "easy_elements_save_widget_setting" ) );
        add_action('wp_ajax_easy_elements_bulk_action', array( $this, 'easy_elements_bulk_action') );
        add_action( 'admin_enqueue_scripts', array( $this, 'easyel_elements_enqueue_admin_hide_notices_css' ) );
        add_action('wp_ajax_easy_elements_toggle_widget', array( $this, 'easyel_elements_toggle_widget_callback') ) ;
        add_action('wp_ajax_easy_elements_save_global_extensions', array( $this, 'easy_elements_save_global_extensions') ) ;
        add_action('wp_ajax_easy_elements_save_global_extensions_bulk', array( $this, 'easy_elements_save_global_extensions_bulk') ) ;
        
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

    public function easy_elements_save_global_extensions_bulk() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Unauthorized', 'easy-elements' ) );
        }

        check_ajax_referer( 'easy_elements_widget_settings_nonce', 'nonce' );

        $tab        = isset( $_POST['tab'] ) ? sanitize_text_field( $_POST['tab'] ) : 'extensions';
        $keys       = isset( $_POST['keys'] ) ? (array) $_POST['keys'] : [];
        $status     = isset( $_POST['status'] ) ? intval( $_POST['status'] ) : 0;
        $group_slug = isset( $_POST['group'] ) ? sanitize_text_field( $_POST['group'] ) : '';

        if ( empty( $keys ) ) {
            wp_send_json_error( [ 'message' => __( 'No keys provided', 'easy-elements' ) ] );
        }

        $settings = get_option( 'easy_element_' . $tab, [] );
        foreach ( $keys as $key ) {
            $key = sanitize_text_field( $key );
            $settings[ $key ] = $status;
        }
        update_option( 'easy_element_' . $tab, $settings );

        if ( $group_slug ) {
            update_option( 'easy_element_group_' . $group_slug, $status );
        }

        wp_send_json_success( [ 'message' => __( 'Bulk settings updated', 'easy-elements' ) ] );
    }

    public function easy_elements_save_global_extensions() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }

        check_ajax_referer('easy_elements_widget_settings_nonce', 'nonce');

        $tab    = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : 'extensions';
        $key    = isset($_POST['key']) ? sanitize_text_field($_POST['key']) : '';
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0;

        if (!$key) {
            wp_send_json_error(['message' => __('Invalid key', 'easy-elements')]);
        }

        $settings = get_option('easy_element_' . $tab, []);
        $settings[$key] = $status;

        update_option('easy_element_' . $tab, $settings);

        wp_send_json_success(['message' => __('Settings updated', 'easy-elements')]);
    }


    // AJAX handler for saving individual widget settings
  
    public function easy_elements_save_widget_setting() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }
        check_ajax_referer('easy_elements_widget_settings_nonce', 'nonce');
        
        $widget_key = isset($_POST['widget_key']) ? sanitize_text_field(wp_unslash($_POST['widget_key'])) : '';
        $status = isset($_POST['status']) && $_POST['status'] === '1' ? '1' : '0';
        $tab_slug   = isset($_POST['tab']) ? sanitize_text_field(wp_unslash($_POST['tab'])) : 'widget';
        
        if (!empty( $widget_key ) ) {
            $option_name = 'easy_element_' . $tab_slug . '_' . $widget_key;

            update_option($option_name, $status);

            wp_send_json_success([
                'message' => __('Widget setting updated successfully', 'easy-elements'),
                'status'  => $status,
            ]);
        } else {
            wp_send_json_error(['message' => __('Invalid widget key', 'easy-elements')]);
        }
    }

    // AJAX handler for bulk actions
    public function easy_elements_bulk_action() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'easy-elements'));
        }
        check_ajax_referer('easy_elements_bulk_action_nonce', 'nonce');
        
        $bulk_action = isset($_POST['bulk_action']) ? sanitize_text_field(wp_unslash($_POST['bulk_action'])) : '';
        $tab = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : 'widget';
        $status = $bulk_action === 'activate_all' ? '1' : '0';
        
        $available_elements = $this->easyel_elements_get_available_widgets();
        $updated_count = 0;

        $is_pro_active = class_exists('Easy_Elements_Pro');

        foreach ($available_elements as $key => $widget) {
            if (isset($widget['tab']) && $widget['tab'] === $tab) {
                $option_name = 'easy_element_' . $tab . '_' . $key;

                if (!$is_pro_active && isset($widget['is_pro']) && $widget['is_pro']) {
                    update_option($option_name, '0'); 
                } else {
                    $status = $bulk_action === 'activate_all' ? '1' : '0';
                    update_option($option_name, $status);
                }
            }
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
            'widget'     => __('All Widgets', 'easy-elements'),
            'extensions' => __('All Extensions', 'easy-elements'),
            'advsettings' => __('Advanced Settings', 'easy-elements'),
        ];

        $easyel_tab_list = apply_filters("easyel_all_tab_list",  $easyel_tabs );

        ?>
        <div class="easyel-overview-header">
            <img src="<?php echo plugin_dir_url( __DIR__ ).'admin/img/easy-logo.png'; ?>" alt="logo">
        </div>
        <div class="wrap easyel-plugin-settings-wrapper">
            <div class="easyel-nav-tab-item">
                <div class="easyel-nav-tab-wrapper easyel-border-radius-20">
                    <a href="#overview" class="easyel-nav-tab easyel-nav-tab-active" data-tab="overview"><i class="easyelIcon-home"></i> <?php esc_html_e ('Overview','easy-elements'); ?></a>
                    <a href="#widget" class="easyel-nav-tab" data-tab="widget"><i class="easyelIcon-widgets"></i><?php esc_html_e('All Widgets','easy-elements'); ?></a>
                    <a href="#extensions" class="easyel-nav-tab" data-tab="extensions"><i class="easyelIcon-extension"></i><?php esc_html_e('All Extensions','easy-elements'); ?></a>
                    <div class="easyel-tab-pro-link">
                        <a href="#" class="easyel-nav-tab">
                            <i class="easyelIcon-crown"></i>
                            <?php esc_html_e('Go Premium','easy-elements'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Status Messages -->
            <div id="bulk-action-message" class="notice" style="display: none;"></div>

            <!-- Tab Content -->
            <div id="easyel-tab-content">
                <?php foreach ( $easyel_tabs as $tab_slug => $tab_label ) : ?>
                    <div id="tab-<?php echo esc_attr($tab_slug); ?>" 
                        class="easyel-tab-panel easyel-border-radius-20 <?php echo esc_attr($tab_slug); ?>" 
                        style="<?php echo $tab_slug === 'overview' ? '' : 'display:none;'; ?>">

                        <?php 
                        if ( $tab_slug === 'widget' ) : ?>
                            <div class="easyel-addon-search easyel-dflex easyel-justify-between">
                                <div class="easyel-widget-filter">
                                    <h1 class="easyel-dashboard-heading"><?php esc_html_e('Widgets','easy-elements');?></h1>
                                    <div class="easyel-widget-filter-button">
                                        <button type="button" id="easyel_all" class="easyel-action-btn active" data-filter="easyel_all"><?php esc_html_e('All', 'easy-elements'); ?></button>
                                        <button type="button" id="easyel_free" class="easyel-action-btn" data-filter="easyel_free"><?php esc_html_e('Free', 'easy-elements'); ?></button>
                                        <button type="button" id="easyel_pro" class="easyel-action-btn" data-filter="easyel_pro"><?php esc_html_e('Pro', 'easy-elements'); ?></button>
                                    </div>
                                </div>
                                <div class="easyel-widget-search-enable">
                                    <div class="easyel-widget-activeDeactivate-button">
                                        <button type="button" id="activate-all-btn"><?php esc_html_e('Activate All', 'easy-elements'); ?></button>
                                        <button type="button" id="deactivate-all-btn"><?php esc_html_e('Deactivate All', 'easy-elements'); ?></button>
                                    </div>
                                    <?php if ( $tab_slug === 'widget' ) { ?>
                                        <input type="text" id="element-search" placeholder="<?php esc_attr_e('Search widgets...', 'easy-elements'); ?>">
                                    <?php } ?>
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
                'icon'        => 'easyelIcon-heading',
                'title'       => 'Easy Heading',
                'description' => 'Add customizable headings with style options.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'clients_logo' => [
                'icon'        => 'easyelIcon-clients-logo-grid',
                'title'       => 'Easy Clients Logo Grid',
                'description' => 'Showcase client logos in a neat grid layout.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'clients_logo_slider' => [
                'icon'        => 'easyelIcon-clients-logo-slider',
                'title'       => 'Easy Clients Logo Slider',
                'description' => 'Display client logos in a slider format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'tab' => [
                'icon'        => 'easyelIcon-tab',
                'title'       => 'Easy Tab',
                'description' => 'Add simple tab content.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'tab_advance' => [
                'icon'        => 'easyelIcon-tab',
                'title'       => 'Easy Advanced Tab',
                'description' => 'Create advanced tab content.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'testimonials' => [
                'icon'        => 'easyelIcon-testimonials-grid',
                'title'       => 'Easy Testimonials Grid',
                'description' => 'Show testimonials in a grid format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'testimonials_slider' => [
                'icon'        => 'easyelIcon-testimonials-slider',
                'title'       => 'Easy Testimonials Slider',
                'description' => 'Display testimonials in a slider format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'image_carousel' => [
                'icon'        => 'easyelIcon-image-carousel',
                'title'       => 'Image Carousel',
                'description' => 'Create an image slider with multiple images.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'image_reveal' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Reveal Image',
                'description' => 'reveal Image Here',
                'demo_url'    => 'https://wpeasyelements.com/portfolio/',
                'docx_url'    => 'https://wpeasyelements.com/docs/portfolio/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'icon_box' => [
                'icon'        => 'easyelIcon-iconbox',
                'title'       => 'Easy Icon Box',
                'description' => 'Display content with an icon.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'process_grid' => [
                'icon'        => 'easyelIcon-process-grid',
                'title'       => 'Easy Process Grid',
                'description' => 'Show process steps in a grid format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'process_slider' => [
                'icon'        => 'easyelIcon-process-slider',
                'title'       => 'Easy Process Slider',
                'description' => 'Show process steps in a slider.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'gallery_filter' => [
                'icon'        => 'easyelIcon-controls-repeat',
                'title'       => 'Gallery Filter ',
                'description' => 'Gallery fiter widget enable.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'team_grid' => [
                'icon'        => 'easyelIcon-team-grid',
                'title'       => 'Easy Team Grid',
                'description' => 'Display team members in a grid format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'team_slider' => [
                'icon'        => 'easyelIcon-team-slider',
                'title'       => 'Easy Team Slider',
                'description' => 'Showcase team members in a slider format.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'contact_box' => [
                'icon'        => 'easyelIcon-contact-box',
                'title'       => 'Easy Contact Box',
                'description' => 'Easy Contact.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],        
            'icon_box_slider' => [
                'icon'        => 'easyelIcon-icon-box-slider',
                'title'       => 'Easy Icon Box Slider',
                'description' => 'Easy Icon Box Slider.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],  
            'timeline_slider' => [
                'icon'        => 'easyelIcon-timeline-slider',    
                'title'       => 'Easy Timeline Slider',
                'description' => 'Easy Timeline Slider.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],      
            'faq' => [
                'icon'        => 'easyelIcon-faq',
                'title'       => 'Easy FAQ',
                'description' => 'Easy FAQ.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],       
            'blog_grid' => [
                'icon'        => 'easyelIcon-post-grid',
                'title'       => 'Easy Post Grid',
                'description' => 'Easy Post.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],        
            'post_slider' => [
                'icon'        => 'easyelIcon-post-slider',
                'title'       => 'Easy Post Slider',
                'description' => 'Easy Post Slider.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],  
            'easy_cf7' => [
                'icon'        => 'easyelIcon-format-image',
                'title'       => 'Contact Form 7',
                'description' => 'Contact form 7.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],      
            'video' => [
                'icon'        => 'easyelIcon-video',
                'title'       => 'Easy Video',
                'description' => 'Easy Video.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'pricing_table' => [
                'icon'        => 'easyelIcon-pricing-table',
                'title'       => 'Easy Pricing Table',
                'description' => 'Easy Pricing Table.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],        
            'pricing_list' => [
                'icon'        => 'easyelIcon-pricing-list',
                'title'       => 'Easy Pricing List',
                'description' => 'Easy Pricing List.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],        
            'service_list' => [
                'icon'        => 'easyelIcon-service-list',
                'title'       => 'Easy Service List',
                'description' => 'Easy Service List.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'process_list' => [
                'icon'        => 'easyelIcon-process-list',
                'title'       => 'Easy Process List',
                'description' => 'Easy Process List.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'marquee_logo' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Easy Marquee Logo',
                'description' => 'Easy Marquee Logo.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'button' => [
                'icon'        => 'easyelIcon-button',
                'title'       => 'Easy Button',
                'description' => 'Easy Button.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],        
           
            'social_share' => [
                'icon'        => 'easyelIcon-social-share',
                'title'       => 'Easy Social Share',
                'description' => 'Easy Social Share.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'social_icon' => [
                'icon'        => 'easyelIcon-social-icon',
                'title'       => 'Easy Social Icon',
                'description' => 'Easy Social Icon.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
           
            'breadcrumb' => [
                'icon'        => 'easyelIcon-breadcumb',
                'title'       => 'Easy Breadcrumb',
                'description' => 'Easy Breadcrumb.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'easy_slider' => [
                'icon'        => 'easyelIcon-slider',
                'title'       => 'Easy Slider',
                'description' => 'Easy Slider.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'image_accordion' => [
                'icon'        => 'easyelIcon-image-accordion',
                'title'       => 'Easy Image Accordion',
                'description' => 'Easy Image Accordion',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'domain_search' => [
                'icon'        => 'easyelIcon-domain-search',    
                'title'       => 'Easy Domain Search',
                'description' => 'Easy Domain Search.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'featured_project' => [
                'icon'        => 'easyelIcon-custom-projects',    
                'title'       => 'Easy Custom Projects',
                'description' => 'Easy Custom Projects.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],
            'advance_button' => [
                'icon'        => 'easyelIcon-button',    
                'title'       => 'Easy Advance Button',
                'description' => 'Easy Advance Button.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],
            'hr_image_scroll' => [
                'icon'        => 'easyelIcon-image-horizontal-scroll',    
                'title'       => 'Easy Image Horizontal Scroll',
                'description' => 'Easy Image Horizontal Scroll.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'tab' => 'widget',
            ], 

            'easy_offcanvas' => [
                'icon'        => 'easyelIcon-canvas',    
                'title'       => 'Easy Offcanvas',
                'description' => 'Easy Offcanvas.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'site_logo' => [
                'icon'        => 'easyelIcon-site-logo',
                'title'       => 'Easy Site Logo',
                'description' => 'Display your website logo easily with this widget.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'search' => [
                'icon'        => 'easyelIcon-search',
                'title'       => 'Easy Search',
                'description' => 'Easy Search All Content.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'navigation_menu' => [
                'icon'        => 'easyelIcon-search',
                'title'       => 'Easy Navigation Menu',
                'description' => 'Easy Navigation Menu.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],
            'page_title' => [
                'icon'        => 'easyelIcon-page-title',
                'title'       => 'Easy Page Title',
                'description' => 'Easy Page Title.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Header & Footer Widget',
                'tab' => 'widget',
            ],

            
            'post_tags' => [
                'icon'        => 'easyelIcon-post-tag',    
                'title'       => 'Easy Current Post Tags',
                'description' => 'Easy Current Post Tags.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'post_author' => [
                'icon'        => 'easyelIcon-author',    
                'title'       => 'Easy Current Post Author',
                'description' => 'Easy Current Post Author.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'post_title' => [
                'icon'        => 'easyelIcon-post-title',
                'title'       => 'Easy Post Title',
                'description' => 'Easy Post Title.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'post_content' => [
                'icon'        => 'easyelIcon-post-content',
                'title'       => 'Easy Post Content',
                'description' => 'Easy Post Content.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],        
            'excerpt' => [
                'icon'        => 'easyelIcon-post-excerpt',
                'title'       => 'Easy Post Excerpt',
                'description' => 'Easy Post Excerpt.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],  
            'related_post' => [
                'icon'        => 'easyelIcon-related-post',
                'title'       => 'Easy Related Post',
                'description' => 'Easy Related Post.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ], 
            'post_pagination' => [
                'icon'        => 'easyelIcon-pagination',
                'title'       => 'Easy Post Pagination',
                'description' => 'Easy Post Pagination.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'post_meta' => [
                'icon'        => 'easyelIcon-meta',
                'title'       => 'Easy Post Meta',
                'description' => 'Easy Post Meta.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'post_comments' => [
                'icon'        => 'easyelIcon-comments',
                'title'       => 'Easy Post Comments',
                'description' => 'Easy Post Comments.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'featured_image' => [
                'icon'        => 'easyelIcon-image-carousel',
                'title'       => 'Easy Featured Image',
                'description' => 'Easy Featured Image.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget',
                'tab' => 'widget',
            ],
            'easy_scroll_to_top' => [
                'icon'        => 'easyelIcon-format-image',
                'title'       => 'Scroll To Top',
                'description' => 'Easy Scroll To Top.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
                'group'       => 'Theme Builder Widget'
            ], 
            'easy_table' => [
                'icon'        => 'easyelIcon-format-image',
                'title'       => 'Table',
                'description' => 'Easy Table.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => true,
            ],
            'easytypewriter' => [
                'icon'        => 'easyelIcon-format-image',
                'title'       => 'Typewriter',
                'description' => 'Animated typewriter text effect.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Animations'
            ],
            'animated_title' => [
                'icon'        => 'easyelIcon-format-image',
                'title'       => 'Animated Title',
                'description' => 'Animated title text effect.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Animations'
            ],
            'animated_heading' => [
                'icon'        => 'easyelIcon-format-image',
                'title'       => 'Animated Heading',
                'description' => 'Animated heading text effect.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Animations'
            ],
            'easytext_animation' => [
                'icon'        => 'easyelIcon-format-image',
                'title'       => 'Text Animation',
                'description' => 'Animated text animation effect.',
                'demo_url'    => 'https://easyelements.reactheme.com/',
                'is_pro'      => false,
                'group'       => 'Animations'
            ], 
            
            'easy_gallery' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Simple Gallery',
                'description' => 'Gallery',
                'demo_url'    => 'https://wpeasyelements.com/simple-gallery/',
                'docx_url'    => 'https://wpeasyelements.com/docs/simple-gallery/',
                'is_pro'      => false,
                'tab' => 'widget',
            ],

            'image_gallery_filter' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'filterable Gallery',
                'description' => 'filterable Gallery',
                'demo_url'    => 'https://wpeasyelements.com/simple-gallery/',
                'docx_url'    => 'https://wpeasyelements.com/docs/simple-gallery/',
                'is_pro'      => true,
                'tab' => 'widget',
            ],

            'portfolio_pro' => [
                'icon'        => 'easyelIcon-marquee-logo',
                'title'       => 'Portfolio',
                'description' => 'Portfolio',
                'demo_url'    => 'https://wpeasyelements.com/portfolio/',
                'docx_url'    => 'https://wpeasyelements.com/docs/portfolio/',
                'is_pro'      => true,
                'tab' => 'widget',
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