<?php
defined('ABSPATH') || exit;

class Easyel_Theme_Builder_Front {

    const CPT = 'easy_theme_builder';

    public static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->init_hooks();
    }

    public function init_hooks() {
        add_filter('template_include', [$this, 'easyel_load_templates'], 999 );
        add_action('easyel_builder_archive_content', [$this, 'easyel_archive_content_elementor'], 999 );
        add_action('easyel_builder_singular_content', [$this, 'easyel_singular_content_elementor'], 999 );
        add_filter( 'body_class', [ $this, 'easyel_body_class' ] );
    }

    public function easyel_load_templates( $template ) {
        if (!class_exists('\Elementor\Plugin')) return $template;
        if ( is_embed() ) return $template;

        
        $template_id = $this->easyel_match_template();

        if ( $template_id ) {
            $page_template = get_page_template_slug( $template_id );

            if ( is_singular() ) {
                if ( 'elementor_canvas' === $page_template ) {
                    return EASYELEMENTS_DIR_PATH . 'templates/theme-builder/elementor-canvas/single-canvas.php';
                } else {
                    return EASYELEMENTS_DIR_PATH . 'templates/theme-builder/elementor-canvas/single.php';
                }
            }
            elseif ( is_archive() || is_search() || is_home() || is_date() || is_author() ) {
                if ( 'elementor_canvas' === $page_template ) {
                    return EASYELEMENTS_DIR_PATH . 'templates/theme-builder/elementor-canvas/archive-canvas.php';
                } else {
                    return EASYELEMENTS_DIR_PATH . 'templates/theme-builder/elementor-canvas/archive.php';
                }
            }
        }

        return $template;
    }

    /**
     * Template Condition Match System
     */
    public function easyel_match_template() {
        $templates = get_posts([
            'post_type'   => self::CPT,
            'post_status' => 'publish',
            'numberposts' => -1,
        ]);

        foreach ( $templates as $tmpl ) {
            $template_type = get_post_meta( $tmpl->ID, 'easyel_template_type', true );
            $conditions    = get_post_meta($tmpl->ID, 'easyel_conditions', true );
            $conditions    = !empty($conditions) ? json_decode($conditions, true ) : [];

            if ( $this->check_conditions( $template_type, $conditions) ) {
                return $tmpl->ID; 
            }
        }

        return false;
    }

    private function check_conditions( $type, $conditions ) {


        $include_match = false;

        // --------- INCLUDE CHECK ---------
        foreach ( $conditions as $cond ) {
            if ( ($cond['include'] ?? 'include') !== 'include' ) continue;

            $sub = $cond['sub'] ?? '';
            $id  = $cond['id'] ?? 0;
            $match = false;

            // Archive
            if ( $type === 'archive' && ( is_archive() || is_search() || is_home() || is_date() || is_author() ) ) {
                switch ($sub) {
                    case 'index': $match = is_home() || is_archive(); break;
                    case 'author': $match = is_author(); break;
                    case 'search': $match = is_search(); break;
                    case 'date': $match = is_date(); break;
                    case 'post_archive': $match = is_post_type_archive('post'); break;
                    case 'category': $match = is_category(); break;
                    case 'child_of_category':
                        if ( is_category() && $id ) $match = cat_is_ancestor_of($id, get_queried_object_id());
                        break;
                    case 'any_child_of_category':
                        if ( is_category() && $id ) {
                            $current_cat = get_queried_object();
                            $ancestors = get_ancestors($current_cat->term_id, 'category');
                            $match = in_array($id, $ancestors, true);
                        }
                        break;
                    case 'post_tag': $match = is_tag(); break;
                    default:
                        if ( taxonomy_exists($sub) && is_tax($sub) ) $match = true;
                        elseif ( post_type_exists($sub) && is_post_type_archive($sub) ) $match = true;
                        break;
                }

                if ( class_exists('WooCommerce') ) {
                    switch ($sub) {
                        case 'shop_page': $match = is_shop(); break;
                        case 'product_search': $match = is_post_type_archive('product') && is_search(); break;
                    }
                }
            }

            // Single
            if ( $type === 'single' && ( is_singular() || is_front_page() || is_404() ) ) {
                switch ($sub) {
                    case 'all': $match = true; break;
                    case 'front_page': $match = is_front_page(); break;
                    case 'post': $match = is_singular('post'); break;
                    case 'in_category': $match = is_single() && has_category($id); break;
                    case 'in_category_children':
                        if ( is_single() ) {
                            $cats = get_the_category(get_the_ID());
                            if ($cats) {
                                foreach ($cats as $cat) {
                                    if ( cat_is_ancestor_of($id, $cat->term_id) ) {
                                        $match = true; break;
                                    }
                                }
                            }
                        }
                        break;
                    case 'in_post_tag': $match = is_single() && has_tag($id); break;
                    case 'post_by_author': $match = is_singular('post') && get_post_field('post_author', get_the_ID()) == $id; break;
                    case 'page': $match = is_page(); break;
                    case 'page_by_author': $match = is_page() && get_post_field('post_author', get_the_ID()) == $id; break;
                    case 'child_of': $match = is_page() && wp_get_post_parent_id(get_the_ID()) == $id; break;
                    case 'any_child_of':
                        if ( is_page() ) {
                            $ancestors = get_post_ancestors(get_the_ID());
                            $match = in_array($id, $ancestors);
                        }
                        break;
                    case 'by_author': $match = is_singular() && get_post_field('post_author', get_the_ID()) == $id; break;
                    case 'not_found404': $match = is_404(); break;
                }
            }

            if ($match) {
                $include_match = true;
                break;
            }
        }

        if ( ! $include_match ) return false;

        // --------- EXCLUDE CHECK ---------
        foreach ( $conditions as $cond ) {
            if ( ($cond['include'] ?? 'include') !== 'exclude' ) continue;

            $sub = $cond['sub'] ?? '';
            $id  = $cond['id'] ?? 0;
            $exclude_match = false;

            // Archive
            if ( $type === 'archive' && ( is_archive() || is_search() || is_home() || is_date() || is_author() ) ) {
                switch ($sub) {
                    case 'index': $exclude_match = is_home() || is_archive(); break;
                    case 'author': $exclude_match = is_author(); break;
                    case 'search': $exclude_match = is_search(); break;
                    case 'date': $exclude_match = is_date(); break;
                    case 'post_archive': $exclude_match = is_post_type_archive('post'); break;
                    case 'category': $exclude_match = is_category(); break;
                    case 'child_of_category':
                        if ( is_category() && $id ) $exclude_match = cat_is_ancestor_of($id, get_queried_object_id());
                        break;
                    case 'any_child_of_category':
                        if ( is_category() && $id ) {
                            $current_cat = get_queried_object();
                            $ancestors = get_ancestors($current_cat->term_id, 'category');
                            $exclude_match = in_array($id, $ancestors, true);
                        }
                        break;
                    case 'post_tag': $exclude_match = is_tag(); break;
                    default:
                        if ( taxonomy_exists($sub) && is_tax($sub) ) $exclude_match = true;
                        elseif ( post_type_exists($sub) && is_post_type_archive($sub) ) $exclude_match = true;
                        break;
                }

                if ( class_exists('WooCommerce') ) {
                    switch ($sub) {
                        case 'shop_page': $exclude_match = is_shop(); break;
                        case 'product_search': $exclude_match = is_post_type_archive('product') && is_search(); break;
                    }
                }
            }

            // Single
            if ( $type === 'single' && ( is_singular() || is_front_page() || is_404() ) ) {
                switch ($sub) {
                    case 'all': $exclude_match = true; break;
                    case 'front_page': $exclude_match = is_front_page(); break;
                    case 'post': $exclude_match = is_singular('post'); break;
                    case 'in_category': $exclude_match = is_single() && has_category($id); break;
                    case 'in_category_children':
                        if ( is_single() ) {
                            $cats = get_the_category(get_the_ID());
                            if ($cats) {
                                foreach ($cats as $cat) {
                                    if ( cat_is_ancestor_of($id, $cat->term_id) ) {
                                        $exclude_match = true; break;
                                    }
                                }
                            }
                        }
                        break;
                    case 'in_post_tag': $exclude_match = is_single() && has_tag($id); break;
                    case 'post_by_author': $exclude_match = is_singular('post') && get_post_field('post_author', get_the_ID()) == $id; break;
                    case 'page': $exclude_match = is_page(); break;
                    case 'page_by_author': $exclude_match = is_page() && get_post_field('post_author', get_the_ID()) == $id; break;
                    case 'child_of': $exclude_match = is_page() && wp_get_post_parent_id(get_the_ID()) == $id; break;
                    case 'any_child_of':
                        if ( is_page() ) {
                            $ancestors = get_post_ancestors(get_the_ID());
                            $exclude_match = in_array($id, $ancestors);
                        }
                        break;
                    case 'by_author': $exclude_match = is_singular() && get_post_field('post_author', get_the_ID()) == $id; break;
                    case 'not_found404': $exclude_match = is_404(); break;
                }
            }

            if ( $exclude_match ) return false;
        }

        return true;
    }


    public function get_builder_content_for_display( $post_id ) {
        if ( empty( $post_id ) || ! get_post_status( $post_id ) ) return ''; 
        if ( ! did_action('elementor/loaded') || ! class_exists('Elementor\Plugin') ) return ''; 

        $elementor = \Elementor\Plugin::instance();
        if ( 'elementor_library' !== get_post_type( $post_id ) && self::CPT !== get_post_type($post_id) ) {
            return '';
        }

        return $elementor->frontend->get_builder_content_for_display( $post_id, true );
    }

    public function easyel_archive_content_elementor( $query = null ) {
        $template_id = $this->easyel_match_template();
        if ( $template_id ) {
            echo $this->get_builder_content_for_display( $template_id ); 
        } else {
            the_content();
        }
    }

    public function easyel_singular_content_elementor( $post_id = null ) {
        if ( !$post_id ) {
            global $post;
            $post_id = $post->ID;
        }

        $template_id = $this->easyel_match_template();
        if ( $template_id ) {
            echo $this->get_builder_content_for_display( $template_id ); 
        } else {
            the_content();
        }
    }

    
        public function easyel_body_class( $classes ) {
       
        if ( ! class_exists('\Elementor\Plugin') ) return $classes;

            $template_id = $this->easyel_match_template();

            if ( $template_id ) {
                $template_type = get_post_meta( $template_id, 'easyel_template_type', true );

                if ( $template_type === 'archive' && ( is_archive() || is_search() || is_home() || is_date() || is_author() ) ) {
                    $classes[] = 'elementor-archive-template';
                }

                if ( $template_type === 'single' && ( is_singular() || is_front_page() || is_404() ) ) {
                    $classes[] = 'elementor-single-template';
                }
            }

            return $classes;
        }

         

}

Easyel_Theme_Builder_Front::instance();