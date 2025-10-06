<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php
	$meta_description = '';
	if ( is_singular() ) {
		global $post;
		$meta_description = get_the_excerpt( $post );
	} elseif ( is_home() || is_front_page() ) {
		$meta_description = get_bloginfo( 'description' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$meta_description = term_description();
	}
	if ( $meta_description ) {
		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $meta_description ) ) . '">';
	}
	?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div id="page" class="eel-hfeed eel-site">    
<?php if( !is_404() ) { do_action( 'easy_header' ); } ?>


