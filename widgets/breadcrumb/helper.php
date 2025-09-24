<?php

if (!function_exists('get_easyel_breadcrumb')) {
function get_easyel_breadcrumb($custom_title='', $custom_path='', $custom_separator='', $custom_home_title='', $search_custom_title='', $search_result_title='', $show_category_path = true, $home_icon_url = '', $home_icon_picker = ''){    
    global $post;
    $post = get_queried_object();
    $object_id = get_queried_object_id();
    $home_title = !empty($custom_home_title) ? $custom_home_title : 'Home';

    $separator = '<span class="breadcrumb-separator">/</span>';
    if (!empty($custom_separator['value'])) {
        $icon_class = is_array($custom_separator['value']) ? $custom_separator['value']['value'] : $custom_separator['value'];
        $home_icon = '<span class="breadcrumb-separator"><i class="' . esc_attr($icon_class) . '"></i></span>';
        $separator = $home_icon;
    }

    // Icon handle
    $home_icon = '<span class="breadcrumb-home-icon"><i class="unicon-home"></i></span>';
    if (!empty($home_icon_url)) {
        $home_icon = '<span class="breadcrumb-home-icon"><img src="' . esc_url($home_icon_url) . '" alt="Home" style="width:16px;height:16px;vertical-align:middle;object-fit:contain;" /></span>';
    } elseif (!empty($home_icon_picker)) {
        if (is_array($home_icon_picker)) {
            // SVG Icon
            if (!empty($home_icon_picker['library']) && $home_icon_picker['library'] === 'svg') {
                if (!empty($home_icon_picker['value']['svg'])) {
                    $home_icon = '<span class="breadcrumb-home-icon">' . $home_icon_picker['value']['svg'] . '</span>';
                } elseif (!empty($home_icon_picker['value']['url'])) {
                    $home_icon = '<span class="breadcrumb-home-icon"><img src="' . esc_url($home_icon_picker['value']['url']) . '" alt="Home" style="width:16px;height:16px;vertical-align:middle;" /></span>';
                }
            }
            // FontAwesome Class
            elseif (!empty($home_icon_picker['value'])) {
                $icon_class = is_array($home_icon_picker['value']) ? $home_icon_picker['value']['value'] : $home_icon_picker['value'];
                $home_icon = '<span class="breadcrumb-home-icon"><i class="' . esc_attr($icon_class) . '"></i></span>';
            }
        } elseif (is_string($home_icon_picker)) {
            $home_icon = '<span class="breadcrumb-home-icon"><i class="' . esc_attr($home_icon_picker) . '"></i></span>';
        }
    }

    $home_link = '<a href="' . esc_url(home_url('/')) . '">' . $home_icon . ' <span class="breadcrumb-home-text">' . esc_html($home_title) . '</span></a>';
    $output = $home_link;

    if (is_single()) {
        $post_type = get_post_type();
        if ($post_type != 'post') {
            $post_type_object = get_post_type_object($post_type);
            if ($post_type_object && $post_type_object->has_archive) {
                $output .= $separator . '<a href="' . esc_url(get_post_type_archive_link($post_type)) . '">' . $post_type_object->labels->name . '</a>';
            }
            if ($show_category_path) {
                $taxonomies = get_object_taxonomies($post_type, 'objects');
                foreach ($taxonomies as $taxonomy) {
                    if ($taxonomy->hierarchical) {
                        $terms = get_the_terms($object_id, $taxonomy->name);
                        if ($terms && !is_wp_error($terms)) {
                            $main_term = $terms[0];
                            if ($main_term->parent != 0) {
                                $ancestors = get_ancestors($main_term->term_id, $taxonomy->name);
                                $ancestors = array_reverse($ancestors);
                                foreach ($ancestors as $ancestor) {
                                    $ancestor_term = get_term($ancestor, $taxonomy->name);
                                    $output .= $separator . '<a href="' . esc_url(get_term_link($ancestor_term)) . '">' . esc_html($ancestor_term->name) . '</a>';
                                }
                            }
                            $output .= $separator . '<a href="' . esc_url(get_term_link($main_term)) . '">' . esc_html($main_term->name) . '</a>';
                        }
                    }
                }
            }
            $output .= $separator;
        } else if ($show_category_path) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                $output .= $separator . get_category_parents($category, true, $separator);
            }
        } else if (!$show_category_path) {
            $output .= $separator . get_the_title();
        }
        $output .= get_the_title();
    } elseif (is_page()) {
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $output .= $separator . '<a href="' . esc_url(get_permalink($ancestor)) . '">' . get_the_title($ancestor) . '</a>';
            }
        }
        $output .= $separator . get_the_title();
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            if ($term->parent != 0) {
                $ancestors = get_ancestors($term->term_id, $term->taxonomy);
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor) {
                    $ancestor_term = get_term($ancestor, $term->taxonomy);
                    $output .= $separator . '<a href="' . esc_url(get_term_link($ancestor_term)) . '">' . esc_html($ancestor_term->name) . '</a>';
                }
            }
            $output .= $separator . esc_html(single_term_title('', false));
        }
    } elseif (is_post_type_archive()) {
        $output .= $separator . post_type_archive_title('', false);
    } elseif (is_home() && !is_front_page()) {
        $blog_title = get_the_title(get_option('page_for_posts', true));
        $output .= $separator . esc_html($blog_title);
    } elseif (is_search()) {
        $output .= $separator . __('Search Results for:', 'easy-elements') . ' ' . get_search_query();
    } elseif (is_404()) {
        $output .= $separator . __('404 Not Found', 'easy-elements');
    }
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe, $output contains trusted HTML
    echo '<div class="eel-breadcrumb"><div class="breadcrumb-path">' . $output . '</div></div>';
}
}
?>
