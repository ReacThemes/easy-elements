<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Easyel_Menu_Walker extends \Walker_Nav_Menu {

	/**
	 * Start element
	 *
	 * @since 1.3.0
	 * @param string $output Output HTML.
	 * @param object $item Individual Menu item.
	 * @param int    $depth Depth.
	 * @param array  $args Arguments array.
	 * @param int    $id Menu ID.
	 * @access public
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$args   = (object) $args;

		$class_names = '';
		$value       = '';
		$rel_xfn     = '';
		$rel_blank   = '';

		// Ensure $classes is always an array
		$classes = [];
		if ( ! empty( $item->classes ) && is_array( $item->classes ) ) {
			$classes = $item->classes;
		}
		$submenu = $args->has_children ? ' eel-has-submenu' : '';

		if ( 0 === $depth ) {
			array_push( $classes, 'parent' );
		}
		
		// Add mobile-friendly classes for better compatibility
		if ( $args->has_children ) {
			array_push( $classes, 'menu-item-has-children' );
		}
		
		// Check if this is the last menu item and if Button option is selected
		$is_last_item = false;
		$menu_last_item_setting = '';
		
		// Get the menu last item setting from the widget
		if ( isset( $args->menu_last_item ) ) {
			$menu_last_item_setting = $args->menu_last_item;
		}
		
		// Check if this is the last top-level menu item
		if ( $depth === 0 ) {
			// Get all top-level menu items to check if this is the last one
			$menu_items = wp_get_nav_menu_items( $args->menu );
			if ( $menu_items && is_array( $menu_items ) ) {
				$top_level_items = array_filter( $menu_items, function( $menu_item ) {
					return $menu_item->menu_item_parent == 0;
				} );
				
				$last_top_level_item = end( $top_level_items );
				if ( $last_top_level_item && $last_top_level_item->ID === $item->ID ) {
					$is_last_item = true;
				}
			}
		}
		
		// Add last-item class to li element if Button option is selected
		if ( $is_last_item && $menu_last_item_setting === 'cta' ) {
			array_push( $classes, 'last-item' );
		}
		
		// Ensure we have a valid array before applying filters
		$filtered_classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );
		if ( ! is_array( $filtered_classes ) ) {
			$filtered_classes = array_filter( $classes );
		}
		$class_names = join( ' ', $filtered_classes );
		$class_names = ' class="' . esc_attr( $class_names ) . $submenu . ' eel-creative-menu"';
		$value       = apply_filters( 'nav_menu_li_values', $value );

		$output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';

		if ( isset( $item->target ) && '_blank' === $item->target && isset( $item->xfn ) && false === strpos( $item->xfn, 'noopener' ) ) {
			$rel_xfn = ' noopener';
		}
		if ( isset( $item->target ) && '_blank' === $item->target && isset( $item->xfn ) && empty( $item->xfn ) ) {
			$rel_blank = 'rel="noopener"';
		}

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . $rel_xfn . '"' : '' . $rel_blank;
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_url( $item->url ) . '"' : '';

		// Enhanced accessibility: Add aria-haspopup and aria-expanded for menu items with children
		if ( $args->has_children ) {
			$attributes .= ' aria-haspopup="true" aria-expanded="false"';
		}

		$atts = apply_filters( 'Easyel_Menu_Walker_nav_menu_attrs', $attributes );

		$item_output  = $args->has_children ? '<div class="eel-has-submenu-container">' : '';
		$item_output .= $args->before;
		$item_output .= '<a' . $atts;
		if ( 0 === $depth ) {
			$anchor_class = 'eel-menu-item';
			// Add elementor-button class to anchor if it's the last item and Button option is selected
			if ( $is_last_item && $menu_last_item_setting === 'cta' ) {
				$anchor_class .= ' elementor-button';
			}
			$item_output .= ' class="' . $anchor_class . '"';
		} else {
			$item_output .= in_array( 'current-menu-item', $classes ) ? ' class="eel-sub-menu-item eel-sub-menu-item-active"' : ' class="eel-sub-menu-item"';
		}

		$item_output .= '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		if ( $args->has_children ) {
			$item_output .= sprintf(
				'<span class="eel-menu-toggle sub-arrow eel-menu-child-%1$d" tabindex="0" role="button" aria-label="%2$s" aria-expanded="false" data-menu-item-id="%1$d">
					<i class="unicon-chevron-down" aria-hidden="true"></i>
				</span>',
				$item->ID,
				esc_attr__('Toggle Submenu', 'your-textdomain')
			);
			
		}
		$item_output .= '</a>';

		$item_output .= $args->after;
		$item_output .= $args->has_children ? '</div>' : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Start level (for submenu containers)
	 *
	 * @since 1.3.0
	 * @param string $output Output HTML.
	 * @param int    $depth Depth.
	 * @param array  $args Arguments array.
	 * @access public
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = [] ) {
		$indent = str_repeat( "\t", $depth );
		$submenu_class = 'sub-menu';
		
		// Add mobile-friendly submenu classes
		if ( $depth > 0 ) {
			$submenu_class .= ' sub-sub-menu';
		}
		
		$output .= "\n" . $indent . '<ul class="' . $submenu_class . '">' . "\n";
	}

	/**
	 * End level (for submenu containers)
	 *
	 * @since 1.3.0
	 * @param string $output Output HTML.
	 * @param int    $depth Depth.
	 * @param array  $args Arguments array.
	 * @access public
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = [] ) {
		$indent = str_repeat( "\t", $depth );
		$output .= $indent . "</ul>\n";
	}

	/**
	 * Display element
	 *
	 * @since 1.3.0
	 * @param object $element Individual Menu element.
	 * @param object $children_elements Child Elements.
	 * @param int    $max_depth Maximum Depth.
	 * @param int    $depth Depth.
	 * @param array  $args Arguments array.
	 * @param string $output Output HTML.
	 * @access public
	 * @return (void | null)
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

		$id_field = $this->db_fields['id'];

		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}
