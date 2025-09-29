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


class Easyel_Elements_CSS_Loader_Helper {
    public static function easyel_elements_load_minified_inline_css( $handle, $file_path, $version = '1.0' ) {
        if ( wp_style_is( $handle, 'enqueued' ) ) {
            return;
        }
        if ( file_exists( $file_path ) ) {
            $css = file_get_contents( $file_path );
            $css = self::minify_css( $css );
            wp_register_style( $handle, false, [], $version );
            wp_enqueue_style( $handle );
            wp_add_inline_style( $handle, $css );
        }
    }
    private static function minify_css( $css ) {
        $css = preg_replace( '!/\*.*?\*/!s', '', $css ); 
        $css = preg_replace( '/\s*([:;,{}])\s*/', '$1', $css ); 
        $css = preg_replace( '/\s+/', ' ', $css ); 
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        return trim( $css );
    }
    private static function remove_duplicate_lines( $css ) {
        $lines = preg_split('/\r\n|\r|\n/', $css);
        $clean_lines = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line && !in_array($line, $clean_lines)) {
                $clean_lines[] = $line;
            }
        }
        return implode("\n", $clean_lines);
    }
}


class Easyel_Elements_JS_Loader_Helper {
    public static function easyel_elements_load_minified_inline_js( $handle, $file_path, $version = '1.0', $in_footer = true ) {
        if ( wp_script_is( $handle, 'enqueued' ) ) {
            return;
        }
        if ( file_exists( $file_path ) ) {
            $js = file_get_contents( $file_path );
            $js = self::minify_js( $js );
            $js = self::remove_duplicate_lines( $js );

            wp_register_script( $handle, '', [], $version, $in_footer );
            wp_enqueue_script( $handle );
            wp_add_inline_script( $handle, $js );
        }
    }
    private static function minify_js( $js ) {
        $js = preg_replace( '#/\*.*?\*/#s', '', $js );
        $js = preg_replace( '#^\s*//.*$#m', '', $js );
        $js = preg_replace( '/\s+/', ' ', $js );
        $js = preg_replace( '/\s*([=;:{}(),<>+\-\[\]])\s*/', '$1', $js );
        return trim($js);
    }
    private static function remove_duplicate_lines( $js ) {
        $lines = explode( "\n", $js );
        $unique_lines = array_unique(array_map( 'trim', $lines ));
        $filtered_lines = array_filter($unique_lines, function($line) {
            return !empty($line);
        });
        return implode( "\n", $filtered_lines );
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

add_action('deactivated_plugin', function($plugin) {
   
    if ($plugin === 'easy-elements-pro/easy-elements-pro.php') {
         $available_elements = Easyel_Elements::get_instance()->easyel_elements_get_available_widgets();

        foreach ($available_elements as $key => $widget) {
            if (isset($widget['is_pro']) && $widget['is_pro']) {
                update_option('easy_element_' . $key, '0'); 
            }
        }
    }
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

