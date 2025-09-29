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
define( 'EASYELEMENTS_FILE', __FILE__ );
define( 'EASYELEMENTS_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'EASYELEMENTS_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'EASYELEMENTS_ASSETS_URL', EASYELEMENTS_DIR_URL . 'assets/' );


$required_files = [
	'inc/helper.php',
	'inc/canvas-content.php',
	'base.php',
	'admin/settings.php',
	'easy-header-footer-elementor/easy-header-footer-elementor.php',
	'templates/theme-builder/easy-theme-builder-post-type.php',
	'templates/theme-builder/easyel-builder-frontend.php',
];

foreach ( $required_files as $file ) {
	$filepath = EASYELEMENTS_DIR_PATH . $file;
	if ( file_exists( $filepath ) ) {
		require_once $filepath;
	} else {
	}
}


register_activation_hook( __FILE__, 'easy_elements_activate' );
function easy_elements_activate() {
	// Register post types first
	if (function_exists('easy_elements_register_archive_template_post_type')) {
		easy_elements_register_archive_template_post_type();
	}
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
add_action( 'hfe_header', 'easyel_before_content_container_hfe', 20 );

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
add_action( 'hfe_footer', 'easyel_after_content_container_hfe', 5 );
