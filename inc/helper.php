<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'easyel_enable_svg_upload' ) ) {
    function easyel_enable_svg_upload( $mimes ) {
        $mimes['svg']  = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
        return $mimes;
    }
    add_filter( 'upload_mimes', 'easyel_enable_svg_upload' );
}

if ( ! function_exists( 'easyel_fix_svg_filetype' ) ) {
    function easyel_fix_svg_filetype( $data, $file, $filename, $mimes ) {
        $ext = pathinfo( $filename, PATHINFO_EXTENSION );
        if ( strtolower( $ext ) === 'svg' || strtolower( $ext ) === 'svgz' ) {
            $data['ext']  = 'svg';
            $data['type'] = 'image/svg+xml';
        }
        return $data;
    }
    add_filter( 'wp_check_filetype_and_ext', 'easyel_fix_svg_filetype', 10, 4 );
}

if ( ! function_exists( 'easyel_handle_sideload_svg' ) ) {
    function easyel_handle_sideload_svg( $file ) {
        $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
        if ( strtolower( $ext ) === 'svg' || strtolower( $ext ) === 'svgz' ) {
            $file['type'] = 'image/svg+xml';
        }
        return $file;
    }
    add_filter( 'wp_handle_sideload_prefilter', 'easyel_handle_sideload_svg' );
}

if ( ! function_exists( 'easyel_get_cf7_forms' ) ) {
    /**
     * Get a list of all CF7 forms
     *
     * @return array
     */
    function easyel_get_cf7_forms() {
        $forms = get_posts( [
            'post_type'      => 'wpcf7_contact_form',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        if ( ! empty( $forms ) ) {
            return wp_list_pluck( $forms, 'post_title', 'ID' );
        }
        return [];
    }
}


// Domain Search Code
add_action('template_redirect', function(){
    // phpcs:ignore WordPress.Security.NonceVerification.Missing
	if (isset($_POST['easyel_domain_redirect'])) {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$domain   = isset($_POST['domain']) ? sanitize_text_field(wp_unslash($_POST['domain'])) : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $base_url = isset($_POST['base_url']) ? esc_url_raw(wp_unslash($_POST['base_url'])) : '';
		
		if (!empty($domain) && !empty($base_url)) {
			$redirect_url = $base_url . urlencode($domain);
			wp_redirect($redirect_url);
			exit;
		}
	}
});

function easyel_get_extension_fields() {
    $fields = [
        'enable_js_animation' => [
            'label'   => __('Enable Easy Animation', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_cursor' => [
            'label'   => __('Enable Easy Cursor', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_sticky_elements' => [
            'label'   => __('Sticky Elements', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_image_animation' => [
            'label'   => __('Image Animation', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_cursor_hover_effect' => [
            'label'   => __('Cursor Hover Effect', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_cursor_move_effect' => [
            'label'   => __('Cursor Move Effect', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_parallax_image' => [
            'label'   => __('Parallax  Image', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_horizontal_scroll_trigger' => [
            'label'   => __('Horizontal ScrollTrigger', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_vertical_scroll_trigger' => [
            'label'   => __('Vertical ScrollTrigger', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_drawsvg' => [
            'label'   => __('DrawSVG', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_image_3d_effect' => [
            'label'   => __('Image 3D Effect', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],

        'enable_image_hover_effect' => [
            'label'   => __('Image Hover Effect', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'GSAP Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],

        'enable_wrapper_link' => [
            'label'   => __('Wrapper Link', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => false,
            'group' => 'General Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        'enable_visibility_control' => [
            'label'   => __('Visibility Control', 'easy-elements'),
            'icon'        => 'dashicons-format-gallery',
            'is_pro'  => true,
            'group' => 'General Extensions',
            'demo_url'    => 'https://easyelements.reactheme.com/',
        ],
        
    ];

    return apply_filters('easyel_extension_fields', $fields);
}

add_action('deactivated_plugin', function($plugin) {
   
    if ($plugin === 'easy-elements-pro/easy-elements-pro.php') {
         $available_elements = Easyel_Elements::get_instance()->easyel_elements_get_available_widgets();

        foreach ($available_elements as $key => $widget) {
            if (isset($widget['is_pro']) && $widget['is_pro']) {
                update_option('easy_element_widget_' . $key, '0'); 
            }
        }
    }

    $extensions_settings = get_option('easy_element_extensions', []);
    $fields = easyel_get_extension_fields();

    foreach ($fields as $ext_key => $data) {
        if (!empty($data['is_pro']) && $data['is_pro']) {
            $extensions_settings[$ext_key] = 0;
        }
    }

    update_option('easy_element_extensions', $extensions_settings);

});


function sanitize_conditions_array( $conditions ) {
    if ( ! is_array( $conditions ) ) {
        return [];
    }

    $sanitized = [];
    foreach ( $conditions as $cond ) {
        if ( ! is_array( $cond ) ) continue;

        $sanitized[] = [
            'include' => isset($cond['include']) ? sanitize_text_field($cond['include']) : 'include',
            'main'    => isset($cond['main']) ? sanitize_text_field($cond['main']) : '',
            'sub'     => isset($cond['sub']) ? sanitize_text_field($cond['sub']) : '',
        ];
    }

    return $sanitized;
}

/**
 * Safely decode JSON or return array as-is
 */
function safe_json_decode( $data ) {
    if ( is_string($data) ) {
        $decoded = json_decode($data, true);
        if ( json_last_error() === JSON_ERROR_NONE && is_array($decoded) ) {
            return $decoded;
        }
        return []; 
    } elseif ( is_array($data) ) {
        return $data; 
    } else {
        return []; 
    }
}

