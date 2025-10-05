<?php
/**
 * Easy Elements Header Footer Builder Functions
 *
 * Custom helper functions for rendering dynamic header and footer templates.
 * 
 * @package Easy Elements
 * @since 1.0.0
 */

function ee_hfe_header_enabled() {
	$header_id = Easy_Header_Footer_Elementor::get_settings( 'type_header', '' );
	$status    = false;

	if ( '' !== $header_id ) {
		$status = true;
	}

	return apply_filters( 'ee_hfe_header_enabled', $status );
}

/**
 * Check if a footer layout is active.
 *
 * @since 1.0.0
 * @return bool True if footer is enabled, false otherwise.
 */
function ee_hfe_footer_enabled() {
	$footer_id = Easy_Header_Footer_Elementor::get_settings( 'type_footer', '' );
	$status    = false;

	if ( '' !== $footer_id ) {
		$status = true;
	}

	return apply_filters( 'ee_hfe_footer_enabled', $status );
}

/**
 * Get the active header template ID.
 *
 * @since 1.0.0
 * @return string|false Header ID if set, otherwise false.
 */
function get_ee_hfe_header_id() {
	$header_id = Easy_Header_Footer_Elementor::get_settings( 'type_header', '' );

	if ( '' === $header_id ) {
		$header_id = false;
	}

	return apply_filters( 'get_ee_hfe_header_id', $header_id );
}

/**
 * Get the active footer template ID.
 *
 * @since 1.0.0
 * @return string|false Footer ID if set, otherwise false.
 */
function get_ee_hfe_footer_id() {
	$footer_id = Easy_Header_Footer_Elementor::get_settings( 'type_footer', '' );

	if ( '' === $footer_id ) {
		$footer_id = false;
	}

	return apply_filters( 'get_ee_hfe_footer_id', $footer_id );
}

/**
 * Render the active header layout.
 *
 * @since 1.0.0
 * @return void
 */
function ee_hfe_render_header() {

	if ( false == apply_filters( 'enable_ee_hfe_render_header', true ) ) {
		return;
	}
	?>
	<header class="easy-site-header">
	<?php
		Easy_Header_Footer_Elementor::get_header_content();
	?>
	</header>
	<?php
}

/**
 * Render the active footer layout.
 *
 * @since 1.0.0
 * @return void
 */
function ee_hfe_render_footer() {

	if ( false == apply_filters( 'enable_ee_hfe_render_footer', true ) ) {
		return;
	}

	?>
		<footer id="colophon">
			<?php Easy_Header_Footer_Elementor::get_footer_content(); ?>
		</footer>
	<?php
}

/**
 * Get the active before-header template ID.
 *
 * @since 1.0.0
 * @return string|false Before-header ID if set, otherwise false.
 */
function hfe_get_before_header_id() {

	$before_header_id = Easy_Header_Footer_Elementor::get_settings( 'type_before_header', '' );

	if ( '' === $before_header_id ) {
		$before_header_id = false;
	}

	return apply_filters( 'get_hfe_before_header_id', $before_header_id );
}

/**
 * Get the active before-footer template ID.
 *
 * @since 1.0.0
 * @return string|false Before-footer ID if set, otherwise false.
 */
function ee_hfe_get_before_footer_id() {

	$before_footer_id = Easy_Header_Footer_Elementor::get_settings( 'type_before_footer', '' );

	if ( '' === $before_footer_id ) {
		$before_footer_id = false;
	}

	return apply_filters( 'get_hfe_before_footer_id', $before_footer_id );
}

/**
 * Check if the before-header layout is enabled.
 *
 * @since 1.0.0
 * @return bool True if before-header is enabled, false otherwise.
 */
function hfe_is_before_header_enabled() {

	$before_header_id = Easy_Header_Footer_Elementor::get_settings( 'type_before_header', '' );
	$status           = false;

	if ( '' !== $before_header_id ) {
		$status = true;
	}

	return apply_filters( 'hfe_before_header_enabled', $status );
}


function ee_hfe_is_before_footer_enabled() {

	$before_footer_id = Easy_Header_Footer_Elementor::get_settings( 'type_before_footer', '' );
	$status           = false;

	if ( '' !== $before_footer_id ) {
		$status = true;
	}

	return apply_filters( 'hfe_before_footer_enabled', $status );
}

/**
 * Render the before-header layout.
 *
 * @since 1.0.0
 * @return void
 */
function ee_render_before_header() {

	if ( false == apply_filters( 'enable_hfe_render_before_header', true ) ) {
		return;
	}

	?>
		<div class="hfe-before-header-wrap">
			<?php Easy_Header_Footer_Elementor::get_before_header_content(); ?>
		</div>
	<?php

}

/**
 * Render the before-footer layout.
 *
 * @since 1.0.0
 * @return void
 */
function ee_hfe_render_before_footer() {

	if ( false == apply_filters( 'enable_ee_hfe_render_before_footer', true ) ) {
		return;
	}

	?>
		<div class="hfe-before-footer-wrap">
			<?php Easy_Header_Footer_Elementor::get_before_footer_content(); ?>
		</div>
	<?php
}