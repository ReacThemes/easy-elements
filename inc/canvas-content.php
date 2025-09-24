<?php
defined( 'ABSPATH' ) || exit;
if ( ! class_exists( __NAMESPACE__ . '\EasyelOffcanvas_PostType' ) ) {

    class EasyelOffcanvas_PostType {

        private string $type = 'easy-offcanvas';
        private string $slug = 'easy-offcanvas';
        private string $name = 'Off Canvas';
        private string $singular_name = 'Off Canvas';
        private string $icon = 'dashicons-menu';

        public function __construct() {
            add_action( 'init', [ $this, 'register' ] );
            add_action( 'elementor/init', [ $this, 'enable_elementor_support' ] );
        }

        /**
         * Register Custom Post Type
         */
        public function register(): void {
            register_post_type( $this->type, [
                'labels' => [
                    'name'               => __( 'Off Canvas', 'easy-elements' ),
                    'singular_name'      => __( 'Off Canvas', 'easy-elements' ),
                    'add_new'            => __( 'Add New', 'easy-elements' ),
                    'add_new_item'       => __( 'Add New Off Canvas', 'easy-elements' ),
                    'edit_item'          => __( 'Edit Off Canvas', 'easy-elements' ),
                    'new_item'           => __( 'New Off Canvas', 'easy-elements' ),
                    'all_items'          => __( 'All Off Canvas', 'easy-elements' ),
                    'view_item'          => __( 'View Off Canvas', 'easy-elements' ),
                    'search_items'       => __( 'Search Off Canvas', 'easy-elements' ),
                    'not_found'          => __( 'No off canvas found', 'easy-elements' ),
                    'not_found_in_trash' => __( 'No off canvas found in Trash', 'easy-elements' ),
                    'menu_name'          => __( 'Off Canvas', 'easy-elements' ),
                ],
                'public'             => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => [ 'slug' => $this->slug ],
                'capability_type'    => 'post',
                'has_archive'        => false,
                'hierarchical'       => false,
                'menu_position'      => 21,
                'menu_icon'          => $this->icon,
                'supports'           => [ 'title', 'editor' ],
                'exclude_from_search'=> true,
                'publicly_queryable' => true,
                'show_in_rest'       => true, // ✅ Gutenberg + REST API
            ] );
        }

        /**
         * Enable Elementor editor support
         */
        public function enable_elementor_support(): void {
            add_post_type_support( $this->type, 'elementor' );
        }
    }

    new EasyelOffcanvas_PostType();
}
