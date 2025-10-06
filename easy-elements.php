<?php
/**
 * Plugin Name: Easy Elements
 * Plugin URI:  https://easyelements.reactheme.com/
 * Description: Provides a set of custom Elementor widgets, shortcodes, and enhancements.
 * Version:     1.0.0
 * Author:      Themewant
 * Author URI:  http://themewant.com/
 * Text Domain: easy-elements
 * Domain Path: /languages
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Define constants
define( 'EASYELEMENTS_VER', '1.0.0' );
define( 'EASYELEMENTS_FILE', __FILE__ );
define( 'EASYELEMENTS_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'EASYELEMENTS_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'EASYELEMENTS_ASSETS_URL', EASYELEMENTS_DIR_URL . 'assets/' );

define( 'EASYELEMENTS_URL', plugins_url( '/', __FILE__ ) );
define( 'EASYELEMENTS_PATH', plugin_basename( __FILE__ ) );
define( 'EASYELEMENTS_DOMAIN', trailingslashit( 'https://reacthemes.com' ) );
define( 'EASYELEMENTS_URL_ADMIN', plugin_dir_url( __FILE__ ) );
define( 'EASYELEMENTS_ASSETS_ADMIN', trailingslashit( EASYELEMENTS_URL_ADMIN ) );


$includes = [
    'helpers' => [
        'inc/helper.php',
        'inc/canvas-content.php',
		'inc/extension/class-wrapper-link.php'
    ],
    'admin' => [
        'admin/settings.php',
    ],
    'frontend' => [
        'base.php',
        'templates/theme-builder/easyel-builder-frontend.php',
    ],
    'addons' => [
        'header-footer-builder/classes/easy-header-footer.php',
    ],
    'cpt' => [
        'templates/theme-builder/easy-theme-builder-post-type.php',
    ],
];


function easyelements_include_file( $file ) {

    $filepath = EASYELEMENTS_DIR_PATH . $file;
    if ( file_exists( $filepath ) ) {
        require_once $filepath;
    } else {
        if ( is_admin() ) {
            trigger_error( "EasyElements missing file: $file", E_USER_WARNING );
        }
    }
}

foreach ( $includes as $group ) {
    foreach ( $group as $file ) {
        easyelements_include_file( $file );
    }
}

register_activation_hook( __FILE__, 'easy_elements_activate' );
function easy_elements_activate() {
	flush_rewrite_rules();
}

add_filter( 'body_class', function( $classes ) {
    if ( is_plugin_active( 'easy-elements/easy-elements.php' ) ) {
        $classes[] = 'eel-easy-elements';
    }
    return $classes;
});


// Container Full site
function easyel_before_content_container_hfe() {
    if ( is_admin() ) return;
	if ( function_exists( 'elementor_theme_do_location' ) && \Elementor\Plugin::$instance->preview->is_preview() ) {
        return;
    }
    echo '<div class="easyel-content-container">';
}
add_action( 'easy_header', 'easyel_before_content_container_hfe', 20 );

/**
 * Close container before HFE Footer
 */
function easyel_after_content_container_hfe() {
    if ( is_admin() ) return;
	if ( function_exists( 'elementor_theme_do_location' ) && \Elementor\Plugin::$instance->preview->is_preview() ) {
        return;
    }
    echo '</div>';
}
add_action( 'easy_footer', 'easyel_after_content_container_hfe', 5 );

add_action( 'plugins_loaded', function() {
   Easy_Header_Footer_Elementor::instance();
});

