<?php

define( 'RTSHFE_VER', '1.0.0' );
define( 'RTSHFE_FILE', __FILE__ );
define( 'RTSHFE_DIR', plugin_dir_path( __FILE__ ) );
define( 'RTSHFE_URL', plugins_url( '/', __FILE__ ) );
define( 'RTSHFE_PATH', plugin_basename( __FILE__ ) );
define( 'RTSHFE_DOMAIN', trailingslashit( 'https://reacthemes.com' ) );
define( 'RTSHFE_DIR_URL_ADMIN', plugin_dir_url( __FILE__ ) );
define( 'RTSHFE_ASSETS_ADMIN', trailingslashit( RTSHFE_DIR_URL_ADMIN ) );


/**
 * Load the class loader.
 */
require_once RTSHFE_DIR . '/inc/class-header-footer-elementor.php';

/**
 * Load the Plugin Class.
 */
function rtshfe_plugin_activation() {	
	update_option( 'hfe_plugin_is_activated', 'yes' );
	update_option( 'rtshfe_addon_option', $footer_widget );
}
register_activation_hook( RTSHFE_FILE, 'rtshfe_plugin_activation' );

/**
 * Load the Plugin Class.
 */
function rtshfe_init() {
	EE_Header_Footer_Elementor::instance();
}
add_action( 'plugins_loaded', 'rtshfe_init' );