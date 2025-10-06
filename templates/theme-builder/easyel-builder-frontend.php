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

            if ( is_singular() || is_front_page() || is_404() ) {
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

            // safe decode
            $conditions = safe_json_decode($conditions);

            foreach ( $conditions as $key => $cond ) {
                if ( ! is_array($cond) ) {
                    $conditions[$key] = [
                        'include' => 'include',
                        'main'    => '',
                        'sub'     => '',
                    ];
                } else {
                    $conditions[$key]['include'] = $cond['include'] ?? 'include';
                    $conditions[$key]['main']    = $cond['main'] ?? '';
                    $conditions[$key]['sub']     = $cond['sub'] ?? '';
                }
            }

            if ( $this->check_conditions( $template_type, $conditions ) ) {
                return $tmpl->ID; 
            }
        }

        return false;
    }




    public function check_conditions( $type, $conditions ) {
       

        if ( class_exists('Easy_Elements_Pro') ) {
            return EasyEL_Free_Pro_Unlock::instance()->check_conditions_pro( $type, $conditions );
        }

        foreach ( $conditions as $cond ) {
            $main = $cond['main'] ?? '';
            $sub  = $cond['sub'] ?? $main;

            if ( ($cond['include'] ?? 'include') !== 'include' ) continue;

            // Only allow "all archive" and "all singular"
            if ( ( $type === 'archive' && $sub === 'index' ) || ( $type === 'archive' && $sub === 'entire-site' ) ) {

                if ( class_exists('WooCommerce') && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
                    return false;
                } else {
                    if ( is_home() || is_archive() || is_search() || is_date() || is_author() ) {
                        return true;
                    }
                }
            }

            if ( ( $type === 'single' && $sub === 'all' ) || ( $type === 'single' && $sub === 'entire-site' ) ) {
                if ( class_exists('WooCommerce') && (
                    is_singular('product') || 
                    is_cart() || 
                    is_checkout() || 
                    is_account_page()
                ) ) {
                    return false;
                } {
                    if ( is_singular() || is_front_page() || is_404() ) {
                        return true;
                    }
                }
            }
        }

        return false;
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
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe because output is intended HTML.
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
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe because output is intended HTML.
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