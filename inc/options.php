<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'easy_elements_admin_menu' );

function easy_elements_admin_menu() {
    add_menu_page(
        __( 'Easy Elements', 'easy-elements' ),
        __( 'Easy Elements', 'easy-elements' ),
        'manage_options',
        'easy-elements-dashboard',
        'Easyel_Elements_settings_callback',
        'dashicons-layout',
        59
    );
}


add_action( 'admin_menu', 'Easyel_Elements_header_footer_menu' );

function Easyel_Elements_header_footer_menu() {
    add_submenu_page(
        'easy-elements-dashboard',
        __( 'Header & Footer', 'easy-elements' ),
        __( 'Header & Footer', 'easy-elements' ),
        'manage_options',
        'edit.php?post_type=ee-elementor-hf'
    );
}


// All Extensions

add_action( 'admin_menu', 'easyel_elements_all_extensions_menu' );
function easyel_elements_all_extensions_menu() {
    add_submenu_page(
        'easy-elements-dashboard', // parent slug
        __( 'All Extensions', 'easy-elements' ), // page title
        __( 'All Extensions', 'easy-elements' ), // menu title
        'manage_options',
        'easy-elements-all-extensions',
        'easyel_elements_all_extensions_callback'
    );
}

add_action( 'admin_init', 'easyel_elements_register_settings' );
function easyel_elements_register_settings() {
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
add_action('wp_ajax_easyel_save_js_animation', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Unauthorized', 'easy-elements'));
    }
    check_ajax_referer('easyel_js_animation_nonce', 'nonce');
    $value = isset($_POST['value']) && $_POST['value'] == '1' ? 1 : 0;
    update_option('easyel_enable_js_animation', $value);
    wp_send_json_success();
});

add_action('wp_ajax_easyel_save_js_animation', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized']);
    }

    check_ajax_referer('easy_elements_nonce', 'nonce');

    $value = isset($_POST['value']) && $_POST['value'] === '1' ? '1' : '0';
    update_option('easyel_enable_js_animation', $value);

    wp_send_json_success(['message' => 'Saved']);
});

// AJAX handler for Cursor checkbox
add_action('wp_ajax_easyel_save_cursor', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Unauthorized', 'easy-elements'));
    }
    check_ajax_referer('easy_elements_nonce', 'nonce');
    $value = isset($_POST['value']) && $_POST['value'] === '1' ? '1' : '0';
    update_option('easyel_enable_cursor', $value);
    wp_send_json_success(['message' => 'Cursor setting saved']);
});


function easyel_elements_all_extensions_callback() {
    $checked_animation = get_option('easyel_enable_js_animation', 0);
    $checked_cursor    = get_option('easyel_enable_cursor', 0);
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'All Extensions', 'easy-elements' ); ?></h1>
        <table class="form-table">
            <tr valign="top">
                <td>
                    <?php esc_html_e( 'Enable Easy Animation', 'easy-elements' ); ?>
                    <label class="easy-toggle-switch">
                        <input type="checkbox" id="easyel_enable_js_animation" value="1" <?php checked( 1, $checked_animation ); ?> />
                        <span class="slider round"></span>
                    </label>
                </td>

                <td>
                    <?php esc_html_e( 'Enable Easy Cursor', 'easy-elements' ); ?>
                    <label class="easy-toggle-switch">
                        <input type="checkbox" id="easyel_enable_cursor" value="1" <?php checked( 1, $checked_cursor ); ?> />
                        <span class="slider round"></span>
                    </label>
                </td>
            </tr>
        </table>
    </div>
    <?php
}


add_action( 'admin_menu', 'Easyel_Elements_advance_settings_menu' );
function Easyel_Elements_advance_settings_menu() {
    add_submenu_page(
        'easy-elements-dashboard',
        __( 'Advance Settings', 'easy-elements' ),
        __( 'Advance Settings', 'easy-elements' ),
        'manage_options',
        'easy-elements-advance-settings',
        'Easyel_Elements_advance_settings_callback'
    );
}

function Easyel_Elements_advance_settings_callback() {
    $minify_css = get_option('easyel_elements_minify_css', '0');
    $minify_js = get_option('easyel_elements_minify_js', '0');
    ?>
    <div class="wrap">
        <h3><?php echo esc_html__( 'Advance Settings', 'easy-elements' ); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><?php echo esc_html__('Minify All CSS', 'easy-elements'); ?>
                <p class="description"><?php echo esc_html__('Enable to minify all plugin CSS output on frontend.', 'easy-elements'); ?></p></th>
                <td>
                    <label class="easy-toggle-switch">
                        <input type="checkbox" id="easyel_elements_minify_css" value="1" <?php checked($minify_css, '1'); ?> />
                        <span class="slider"></span>
                    </label>
                    
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo esc_html__('Minify All JS', 'easy-elements'); ?>
                <p class="description"><?php echo esc_html__('Enable to minify all plugin JS output on frontend.', 'easy-elements'); ?></p></th>
                <td>
                    <label class="easy-toggle-switch">
                        <input type="checkbox" id="easyel_elements_minify_js" value="1" <?php checked($minify_js, '1'); ?> />
                        <span class="slider"></span>
                    </label>
                    
                </td>
            </tr>
        </table>
    </div>

    <!-- JavaScript functionality is handled by admin.js -->
    
    <?php
}

// AJAX handler for saving minify css option
add_action('wp_ajax_easy_elements_save_minify_css', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Unauthorized', 'easy-elements'));
    }
    check_ajax_referer('easy_elements_save_advance_settings_nonce', 'nonce');
    $minify_css = isset($_POST['minify_css']) && $_POST['minify_css'] === '1' ? '1' : '0';
    update_option('easyel_elements_minify_css', $minify_css);
    wp_send_json_success();
});

// AJAX handler for saving minify js option
add_action('wp_ajax_easy_elements_save_minify_js', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Unauthorized', 'easy-elements'));
    }
    check_ajax_referer('easy_elements_save_advance_settings_nonce', 'nonce');
    $minify_js = isset($_POST['minify_js']) && $_POST['minify_js'] === '1' ? '1' : '0';
    update_option('easyel_elements_minify_js', $minify_js);
    wp_send_json_success();
});

// AJAX handler for saving individual widget settings
add_action('wp_ajax_easy_elements_save_widget_setting', function() {
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
});

// AJAX handler for bulk actions
add_action('wp_ajax_easy_elements_bulk_action', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Unauthorized', 'easy-elements'));
    }
    check_ajax_referer('easy_elements_bulk_action_nonce', 'nonce');
    
    $bulk_action = isset($_POST['bulk_action']) ? sanitize_text_field(wp_unslash($_POST['bulk_action'])) : '';
    $status = $bulk_action === 'activate_all' ? '1' : '0';
    
    $available_elements = Easyel_Elements_get_available_widgets();
    $updated_count = 0;
    
    foreach ($available_elements as $key => $widget) {
        update_option('easy_element_' . $key, $status);
        $updated_count++;
    }
    
    wp_send_json_success([
        'message' => sprintf('%d widgets %s successfully', $updated_count, $status ? 'activated' : 'deactivated'),
        'count' => $updated_count
    ]);
});


add_action( 'admin_enqueue_scripts', 'Easyel_Elements_enqueue_admin_hide_notices_css' );

function Easyel_Elements_enqueue_admin_hide_notices_css( $hook ) {
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


////////****************** Elements Settings Page ********************************
function Easyel_Elements_settings_callback() {
    $available_elements = Easyel_Elements_get_available_widgets();
    ?>
    <div class="wrap">
        <h3><strong><?php esc_html_e('Elements Settings', 'easy-elements'); ?></strong></h3>
        <p><?php esc_html_e('You can activate or deactivate all Elementor widgets at once, or control them individually using the toggles below.', 'easy-elements'); ?></p>

        <!-- Bulk Actions and Search -->
        <div class="eel-addon-search">
            <input type="text" id="element-search" placeholder="<?php esc_attr_e('Search widgets...', 'easy-elements'); ?>">
            <button type="button" id="activate-all-btn" class="button button-secondary"><?php esc_html_e('Activate All', 'easy-elements'); ?></button>
            <button type="button" id="deactivate-all-btn" class="button button-secondary"><?php esc_html_e('Deactivate All', 'easy-elements'); ?></button>
        </div>

        <!-- Status Messages -->
        <div id="bulk-action-message" class="notice" style="display: none;"></div>

        <!-- Widgets Grid -->
        <div class="easy-widgets-grid">
            <?php
            $counter = 0;
            foreach ($available_elements as $key => $widget) {
                $enabled = get_option('easy_element_' . $key, '1');
                ?>
                <div class="easy-widget-item" data-widget-key="<?php echo esc_attr($key); ?>">
                    <div class="widget-header">
                        <span class="dashicons <?php echo esc_attr($widget['icon']); ?>"></span>
                        <strong><?php echo esc_html($widget['title']); ?></strong>
                    </div>
                    
                    <p class="widget-description"><?php echo esc_html($widget['description']); ?></p>
                    
                    <p class="widget-demo">
                        <a href="<?php echo esc_url($widget['demo_url']); ?>" target="_blank" rel="noopener noreferrer">
                            <?php esc_html_e('View Demo', 'easy-elements'); ?>
                        </a>
                    </p>
                    
                    <div class="widget-toggle">
                        <label class="easy-toggle-switch">
                            <input type="checkbox" 
                                   class="widget-toggle-checkbox" 
                                   data-widget-key="<?php echo esc_attr($key); ?>"
                                   value="1" 
                                   <?php checked($enabled, '1'); ?> />
                            <span class="slider"></span>
                        </label>
                        <span class="toggle-status"></span>
                    </div>
                </div>
                <?php
                $counter++;
            }
            ?>
        </div>
    </div>

    <!-- JavaScript functionality is handled by admin.js -->
    <?php
}



////////****************** AJAX Toggle ********************************
add_action('wp_ajax_easy_elements_toggle_widget', 'Easyel_Elements_toggle_widget_callback');
function Easyel_Elements_toggle_widget_callback() {
    if (!current_user_can('manage_options') || !check_ajax_referer('easy_elements_nonce', 'nonce', false)) {
        wp_send_json_error('Unauthorized');
    }
    $key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
    $status = (isset($_POST['status']) && $_POST['status'] === '1') ? '1' : '0';
    update_option('easy_element_' . $key, $status);
    wp_send_json_success('Updated');
}

function Easyel_Elements_enqueue_admin_assets($hook) {
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

add_action('admin_enqueue_scripts', 'Easyel_Elements_enqueue_admin_assets');

function Easyel_Elements_get_available_widgets() {
    return [
        'site_logo' => [
            'icon'        => 'dashicons-format-image',
            'title'       => 'Easy Site Logo',
            'description' => 'Display your website logo easily with this widget.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'heading' => [
            'icon'        => 'dashicons-editor-textcolor',
            'title'       => 'Easy Heading',
            'description' => 'Add customizable headings with style options.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'clients_logo' => [
            'icon'        => 'dashicons-groups',
            'title'       => 'Easy Clients Logo Grid',
            'description' => 'Showcase client logos in a neat grid layout.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'clients_logo_slider' => [
            'icon'        => 'dashicons-format-gallery',
            'title'       => 'Easy Clients Logo Slider',
            'description' => 'Display client logos in a slider format.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'tab' => [
            'icon'        => 'dashicons-editor-insertmore',
            'title'       => 'Easy Tab',
            'description' => 'Add simple tab content.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'tab_advance' => [
            'icon'        => 'dashicons-editor-insertmore',
            'title'       => 'Easy Advanced Tab',
            'description' => 'Create advanced tab content.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'testimonials' => [
            'icon'        => 'dashicons-format-quote',
            'title'       => 'Easy Testimonials Grid',
            'description' => 'Show testimonials in a grid format.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'testimonials_slider' => [
            'icon'        => 'dashicons-format-gallery',
            'title'       => 'Easy Testimonials Slider',
            'description' => 'Display testimonials in a slider format.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'image_carousel' => [
            'icon'        => 'dashicons-format-gallery',
            'title'       => 'Image Carousel',
            'description' => 'Create an image slider with multiple images.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'icon_box' => [
            'icon'        => 'dashicons-smiley',
            'title'       => 'Easy Icon Box',
            'description' => 'Display content with an icon.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'process_grid' => [
            'icon'        => 'dashicons-screenoptions',
            'title'       => 'Easy Process Grid',
            'description' => 'Show process steps in a grid format.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'process_slider' => [
            'icon'        => 'dashicons-controls-repeat',
            'title'       => 'Easy Process Slider',
            'description' => 'Show process steps in a slider.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'team_grid' => [
            'icon'        => 'dashicons-groups',
            'title'       => 'Easy Team Grid',
            'description' => 'Display team members in a grid format.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'team_slider' => [
            'icon'        => 'dashicons-controls-play',
            'title'       => 'Easy Team Slider',
            'description' => 'Showcase team members in a slider format.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'search' => [
            'icon'        => 'dashicons-search',
            'title'       => 'Easy Search',
            'description' => 'Easy Search All Content.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'contact_box' => [
            'icon'        => 'dashicons-phone',
            'title'       => 'Easy Contact Box',
            'description' => 'Easy Contact.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],        
        'icon_box_slider' => [
            'icon'        => 'dashicons-format-gallery',
            'title'       => 'Easy Icon Box Slider',
            'description' => 'Easy Icon Box Slider.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],        
        'faq' => [
            'icon'        => 'dashicons-editor-help',
            'title'       => 'Easy FAQ',
            'description' => 'Easy FAQ.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],       
        'blog_grid' => [
            'icon'        => 'dashicons-format-gallery',
            'title'       => 'Easy Post Grid',
            'description' => 'Easy Post.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],        
        'post_slider' => [
            'icon'        => 'dashicons-format-gallery',
            'title'       => 'Easy Post Slider',
            'description' => 'Easy Post Slider.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],        
        'video' => [
            'icon'        => 'dashicons-format-video',
            'title'       => 'Easy Video',
            'description' => 'Easy Video.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'pricing_table' => [
            'icon'        => 'dashicons-editor-table',
            'title'       => 'Easy Pricing Table',
            'description' => 'Easy Pricing Table.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],        
        'pricing_list' => [
            'icon'        => 'dashicons-editor-table',
            'title'       => 'Easy Pricing List',
            'description' => 'Easy Pricing List.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],        
        'service_list' => [
            'icon'        => 'dashicons-editor-table',
            'title'       => 'Easy Service List',
            'description' => 'Easy Service List.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'process_list' => [
            'icon'        => 'dashicons-editor-table',
            'title'       => 'Easy Process List',
            'description' => 'Easy Process List.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'marquee_logo' => [
            'icon'        => 'dashicons-format-gallery',
            'title'       => 'Easy Marquee Logo',
            'description' => 'Easy Marquee Logo.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'button' => [
            'icon'        => 'dashicons-controls-buoy',
            'title'       => 'Easy Button',
            'description' => 'Easy Button.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],        
        'page_title' => [
            'icon'        => 'dashicons-admin-page',
            'title'       => 'Easy Page Title',
            'description' => 'Easy Page Title.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'social_share' => [
            'icon'        => 'dashicons-share',
            'title'       => 'Easy Social Share',
            'description' => 'Easy Social Share.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'social_icon' => [
            'icon'        => 'dashicons-share',
            'title'       => 'Easy Social Icon',
            'description' => 'Easy Social Icon.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'featured_image' => [
            'icon'        => 'dashicons-format-image',
            'title'       => 'Easy Featured Image',
            'description' => 'Easy Featured Image.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'post_title' => [
            'icon'        => 'dashicons-admin-post',
            'title'       => 'Easy Post Title',
            'description' => 'Easy Post Title.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'post_content' => [
            'icon'        => 'dashicons-admin-post',
            'title'       => 'Easy Post Content',
            'description' => 'Easy Post Content.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],        
        'excerpt' => [
            'icon'        => 'dashicons-admin-post',
            'title'       => 'Easy Post Excerpt',
            'description' => 'Easy Post Excerpt.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],  
        'related_post' => [
            'icon'        => 'dashicons-admin-post',
            'title'       => 'Easy Related Post',
            'description' => 'Easy Related Post.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ], 
        'post_pagination' => [
            'icon'        => 'dashicons-admin-post',
            'title'       => 'Easy Post Pagination',
            'description' => 'Easy Post Pagination.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'post_meta' => [
            'icon'        => 'dashicons-admin-post',
            'title'       => 'Easy Post Meta',
            'description' => 'Easy Post Meta.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'post_comments' => [
            'icon'        => 'dashicons-admin-comments',
            'title'       => 'Easy Post Comments',
            'description' => 'Easy Post Comments.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'breadcrumb' => [
            'icon'        => 'dashicons-admin-post',
            'title'       => 'Easy Breadcrumb',
            'description' => 'Easy Breadcrumb.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'easy_slider' => [
            'icon'        => 'dashicons-format-gallery',
            'title'       => 'Easy Slider',
            'description' => 'Easy Slider.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'image_accordion' => [
            'icon'        => 'dashicons-format-gallery',
            'title'       => 'Easy Image Accordion',
            'description' => 'Easy Image Accordion',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'domain_search' => [
            'icon'        => 'dashicons-admin-post',    
            'title'       => 'Easy Domain Search',
            'description' => 'Easy Domain Search.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'featured_project' => [
            'icon'        => 'dashicons-admin-post',    
            'title'       => 'Easy Custom Projects',
            'description' => 'Easy Custom Projects.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'advance_button' => [
            'icon'        => 'dashicons-admin-post',    
            'title'       => 'Easy Advance Button',
            'description' => 'Easy Advance Button.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'hr_image_scroll' => [
            'icon'        => 'dashicons-admin-post',    
            'title'       => 'Easy Image Horizontal Scroll',
            'description' => 'Easy Image Horizontal Scroll.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ], 
        'post_tags' => [
            'icon'        => 'dashicons-admin-post',    
            'title'       => 'Easy Current Post Tags',
            'description' => 'Easy Current Post Tags.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'post_author' => [
            'icon'        => 'dashicons-admin-post',    
            'title'       => 'Easy Current Post Author',
            'description' => 'Easy Current Post Author.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'timeline_slider' => [
            'icon'        => 'dashicons-admin-post',    
            'title'       => 'Easy Timeline Slider',
            'description' => 'Easy Timeline Slider.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
        'easy_offcanvas' => [
            'icon'        => 'dashicons-admin-post',    
            'title'       => 'Easy Offcanvas',
            'description' => 'Easy Offcanvas.',
            'demo_url'    => 'https://easyelements.reactheme.com/'
        ],
    ];
}

// Custom Font Load Free
add_action( 'admin_menu', function() {
    add_submenu_page(
        'easy-elements-dashboard',
        __( 'Custom Fonts', 'easy-elements' ),
        __( 'Custom Fonts', 'easy-elements' ),
        'manage_options',
        'easyel-custom-fonts',
        'easyel_custom_fonts_page_html'
    );
}, 21 );

/**
 * Admin page HTML
 */
function easyel_custom_fonts_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    if ( ! defined( 'EASY_ELEMENTS_PRO_ACTIVE' ) || ! EASY_ELEMENTS_PRO_ACTIVE ) {
        echo '<div class="wrap"><h1>' . esc_html__( 'Custom Fonts', 'easy-elements' ) . '</h1>';
        echo '<p style="font-size:16px;color:#cc0000;">' . esc_html__( 'This feature is available in Easy Elements Pro. Please install and activate the Pro version to use it.', 'easy-elements' ) . '</p>';
        echo '</div>';
        return;
    }

    easyel_pro_custom_fonts_page();
}