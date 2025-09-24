<?php
/**
 * Easy Theme Builder - Custom Post Type Registration (Singleton)
 */
if ( ! class_exists( 'Easyel_Theme_Builder_CPT' ) ) {

    class Easyel_Theme_Builder_CPT {

        /**
         * Holds the singular instance
         *
         * @var Easyel_Theme_Builder_CPT|null
         */
        private static $instance = null;

        const EASYEL_BUILDER_CPT = 'easy_theme_builder';
        const EASY_TAB_BASE      = 'edit.php?post_type=easy_theme_builder';

        /**
         * Get instance
         *
         * @return Easyel_Theme_Builder_CPT
         */
        public static function instance() {
            if ( self::$instance === null ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Constructor is private to force singleton
         */
        private function __construct() {

            add_action( 'init', array( $this, 'easyel_theme_builder_post_type' ) );
            add_action( 'admin_menu', array( $this,  'easyel_theme_builder_templates_menu' ), 20 ) ;
            add_filter( 'views_edit-' . self::EASYEL_BUILDER_CPT, [ $this, 'easyel_builder_filter_markup'] );
            add_action('admin_footer', array( $this, "easyel_add_new_id_added_func") );

            add_action( 'admin_enqueue_scripts', [ $this, 'easy_builder_enqueue_assets' ] );

            add_action( 'current_screen', function () {
				$current_screen = get_current_screen();
				if ( ! $current_screen || ! strstr( $current_screen->post_type, 'easy_theme_builder' ) ) {
					return;
				}

				add_action( 'in_admin_footer', [ $this, 'easy_add_new_template_template'], 10, 2 );
			} );

            add_action('wp_ajax_easyel_save_template_conditions', [ $this, "easyel_ajax_save_builder" ] );

            add_filter( 'manage_easy_theme_builder_posts_columns', [ $this, "easy_theme_builder_posts_columns" ] );
            add_filter( 'manage_edit-easy_theme_builder_sortable_columns', [ $this, "easy_theme_builder_sortable_columns"] );
            add_action( 'manage_easy_theme_builder_posts_custom_column', [ $this, "easy_theme_builder_posts_custom_column"], 10, 2 );
            add_action( 'pre_get_posts', [ $this, 'easyel_filter_easy_theme_builder_by_type' ] );

            // archive 
            add_action('wp_ajax_easyel_get_archives', [ $this, "easyel_get_archives_func" ] ); 
            add_action('wp_ajax_easyel_get_singulars', [ $this, "easyel_get_singulars_func" ] ); 

            /* get builder ajax data..*/ 
            add_action('wp_ajax_easyel_get_builder', [ $this, 'easyel_ajax_get_builder' ] );

            add_action('wp_ajax_easyel_update_builder', [ $this, 'easyel_update_builder_callback'] );

        }

        /**
         * Enqueue admin scripts and styles.
         *
         * @param string $hook The current admin page.
         */
        public function easy_builder_enqueue_assets( $hook ) {

            $assetsUrl = '/templates/theme-builder/assets/';

            wp_enqueue_style(
                'easyel-builder-style',
                EASYELEMENTS_DIR_URL . $assetsUrl. 'css/easy-modal-css.css',
                [],
                time()
            );

            wp_enqueue_script(
                'easyel-builder-script',
                EASYELEMENTS_DIR_URL . $assetsUrl. 'js/modal-popup.js',
                [ 'jquery' ],
                time(),
                true
            );

            wp_localize_script(
                'easyel-builder-script',
                'easyel_builder_obj',
                [
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'nonce'    => wp_create_nonce( 'easyel_ajax_nonce' ),
                    'admin_url' => admin_url(),
                ]
            );
        }

        function easyel_theme_builder_templates_menu() {
            // Archive Templates
            add_submenu_page(
                'easy-elements-dashboard',
                __('Theme Builder', 'easy-elements'),
                __('Theme Builder', 'easy-elements'),
                'manage_options',
                'edit.php?post_type=easy_theme_builder'
            );
        }
        /**
         * Register Custom Post Type
         */
        public function easyel_theme_builder_post_type() {

            $labels = array(
                'name'                  => _x( 'Theme Templates', 'Post Type General Name', 'easy-elements' ),
                'singular_name'         => _x( 'Theme Template', 'Post Type Singular Name', 'easy-elements' ),
                'menu_name'             => __( 'Easy Theme Builder', 'easy-elements' ),
                'name_admin_bar'        => __( 'Theme Template', 'easy-elements' ),
                'archives'              => __( 'Template Archives', 'easy-elements' ),
                'attributes'            => __( 'Template Attributes', 'easy-elements' ),
                'all_items'             => __( 'All Templates', 'easy-elements' ),
                'add_new_item'          => __( 'Add New Template', 'easy-elements' ),
                'add_new'               => __( 'Add New', 'easy-elements' ),
                'new_item'              => __( 'New Template', 'easy-elements' ),
                'edit_item'             => __( 'Edit Template', 'easy-elements' ),
                'update_item'           => __( 'Update Template', 'easy-elements' ),
                'view_item'             => __( 'View Template', 'easy-elements' ),
                'view_items'            => __( 'View Templates', 'easy-elements' ),
                'search_items'          => __( 'Search Template', 'easy-elements' ),
            );

            $args = array(
                'label'                 => __( 'Theme Builder', 'easy-elements' ),
                'description'           => __( 'Custom theme builder templates.', 'easy-elements' ),
                'labels'                => $labels,
                'supports'              => array( 'title', 'editor', 'elementor' ),
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => false,
                'menu_position'         => 5,
                'menu_icon'             => 'dashicons-layout',
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => false,
                'can_export'            => true,
                'has_archive'           => false,
                'exclude_from_search'   => true,
                'publicly_queryable'    => true,
                'capability_type'       => 'post',
                'show_in_rest'          => true,
            );

            register_post_type( self::EASYEL_BUILDER_CPT, $args );
        }

         /**
         * Add Custom Tabs Above Table
         */
        public function easyel_builder_filter_markup( $views ) {

            global $typenow;

            if ( $typenow === 'easy_theme_builder' ) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is a read-only filter, no action performed.
                $current = isset($_GET['easy_etb_type']) ? sanitize_key( wp_unslash($_GET['easy_etb_type']) ) : '';
                $base_url = admin_url( 'edit.php?post_type=easy_theme_builder' );

                ob_start();
                ?>
                <div class="easyel-builder-tabs">
                    <a href="<?php echo esc_url( $base_url ); ?>" class="easyel-tab <?php echo $current=='' ? 'active' : ''; ?>">
                        <?php esc_html_e( 'All', 'easy-elements' ); ?>
                    </a>
                    <a href="<?php echo esc_url( $base_url . '&easy_etb_type=archive' ); ?>" class="easyel-tab <?php echo $current=='archive' ? 'active' : ''; ?>">
                        <?php esc_html_e( 'Archive', 'easy-elements' ); ?>
                    </a>
                    <a href="<?php echo esc_url( $base_url . '&easy_etb_type=single' ); ?>" class="easyel-tab <?php echo $current=='single' ? 'active' : ''; ?>">
                        <?php esc_html_e( 'Single', 'easy-elements' ); ?>
                    </a>
                </div>
                <?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe because output is intended HTML.
                echo ob_get_clean();
            }

            return $views;
        }

        public static function easyel_template_type() {
            $template_types = apply_filters(
                'easyelements/theme-builder/template-types',
                [
                    'all'     => esc_html__( 'All', 'easy-elements' ),
                    'single'  => esc_html__( 'single', 'easy-elements' ),
                    'archive' => esc_html__( 'Archive', 'easy-elements' ),
                ]
            );

            return $template_types;
        }

        // new id added
        public function easyel_add_new_id_added_func() {
            $screen = get_current_screen();
            if ( $screen && $screen->id === 'edit-easy_theme_builder' ) {   

            ?>
            <script>
                jQuery(document).ready(function($) {
                    $('.page-title-action').attr('id', 'easyel-theme-builder-add-template').attr('href', '#');
                });
            </script>
            <!-- Modal Overlay -->
            <div id="easyel-template-modal-edit" class="easyel-modal-overlay-edit easyel-edit-template-condition">
                <div class="easyel-modal-content-edit">
                    <div class="easyel-template-error-message"></div>
                    <span class="easyel-close">&times;</span>
                    <div class="easyel-choose-template">
                        <h2 class="easyel-choose-template">Edit Template Type</h2>
                        <div class="easyel-template-type">
                            <select class="easyel-builder-tmpl-type" name="easyel_builder_tmpl_type">
                                <option value="">Select Template Type</option>
                                <option value="archive">Archive</option>
                                <option value="single">Single</option>
                            </select>
                        </div>
                        <div class="easyel-template-type">
                            <input type="text" name="easyel_builder_template_name" class="easyel-builder-template-name" placeholder="Enter template Name"/>
                        </div>
                    </div>
                    <h2> Edit Template Elements Condition</h2>
                    <p>Where do you want to display your template?</p>

                    <div id="easyel-conditions-wrapper-edit" class="easyel-conditions-wrapper-edit">
                        <div class="easyel-condition-row-edit">
                            <select class="easyel-include-type">
                                <option value="include">Include</option>
                                <option value="exclude">Exclude</option>
                            </select>
                            <select class="easyel-condition-main">
                                <option value="entire-site">Entire Site</option>
                                <option value="archives">Archives</option>
                                <option value="singular">Singular</option>
                            </select>
                            <select class="easyel-condition-sub">
                                <option value="all">All Archives</option>
                            </select>
                            <span class="easyel-remove-row">&times;</span>
                        </div>
                    </div>

                    <button type="button" id="easyel-add-condition-edit">+ Add Condition</button>
                    <div class="easyel-modal-footer">
                        <button class="easyel-cancel-btn">Cancel</button>
                        <button class="easyel-edit-template">Update</button>
                        <a href="#" id="easyel-edit-with-elementor" class="easyel-edit-with-elementor">Edit With Eleemntor</a>
                    </div>
                </div>
            </div>
            <?php
            }
        }

        public function easy_add_new_template_template() {
            $screen = get_current_screen();
            
			ob_start();
            require_once EASYELEMENTS_DIR_PATH . '/templates/theme-builder/popup-content.php';
			$template = ob_get_clean();
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe because output is intended HTML.
			echo $template;
		}

        /*builder ajax*/
        public function easyel_ajax_save_builder() {
            // Check nonce
            check_ajax_referer('easyel_ajax_nonce', 'nonce');

            if (!current_user_can('manage_options')) {
                wp_send_json_error(__('Permission denied', 'easy-elements'));
            }
            
            if( isset($_POST['conditions'], $_POST['template_type'], $_POST['template_name']) ) {

                $conditions    = isset($_POST['conditions']) ? wp_json_encode( array_map('sanitize_text_field', wp_unslash((array) $_POST['conditions']) ) ) : '';
                $template_type = isset($_POST['template_type']) ? sanitize_text_field( wp_unslash($_POST['template_type']) ) : '';
                $template_name = isset($_POST['template_name']) ? sanitize_text_field( wp_unslash($_POST['template_name']) ) : '';

                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Slow query with meta_query is intentional.
                $existing_posts = get_posts( [
                    'post_type'  => 'easy_theme_builder',
                    'post_status'=> 'publish',
                    // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Slow meta_query intentional
                    'meta_query' => [
                        'relation' => 'AND',
                        [
                            'key'   => 'easyel_template_type',
                            'value' => $template_type,
                        ],
                        [
                            'key'   => 'easyel_conditions',
                            'value' => $conditions,
                        ]
                    ]
                ] );

                if(!empty($existing_posts)) {
                    wp_send_json_error([
                        'message' => 'A template with the same type and conditions already exists!'
                    ]);
                }

                $new_post = [
                    'post_title'    => $template_name,
                    'post_status'   => 'publish',
                    'post_type'     => 'easy_theme_builder',
                    'meta_input'    => [
                        'easyel_template_type' => $template_type,
                        'easyel_conditions'    => $conditions
                    ]
                ];

                $post_id = wp_insert_post($new_post);

                if( $post_id && !is_wp_error( $post_id ) ){

                    update_post_meta($post_id, '_wp_page_template', 'elementor_header_footer'); // Elementor Full Width
                    update_post_meta($post_id, '_elementor_template_type', 'wp-page');
                    update_post_meta($post_id, '_elementor_edit_mode', 'builder');

                    $edit_url = add_query_arg(
                        [
                            'post'   => $post_id,
                            'action' => 'elementor'
                        ],
                        admin_url( 'post.php' )
                    );

                    wp_send_json_success([
                        'message'   => 'Template saved & published successfully!',
                        'post_id'   => $post_id,
                        'edit_url'  => $edit_url
                    ]);

                } else {
                    wp_send_json_error( ['message' => 'Failed to create template post.'] );
                }

            } else {
                wp_send_json_error(['message' => 'Missing required data!']);
            }
        }

        public function easy_theme_builder_posts_columns( $columns ) {
            $new_columns = [];

            foreach ( $columns as $key => $value ) {
               
                if ( 'title' === $key ) {
                    $new_columns[ $key ] = $value;
                    $new_columns['template_type'] = __( 'Template Type', 'easy-elements' );
                    
                } elseif ( 'date' === $key ) {
                    continue;
                } else {
                    $new_columns[ $key ] = $value;
                }
            }

            $new_columns['display_conditions'] = __( 'Display Conditions', 'easy-elements' );
            
            $new_columns['date'] = $columns['date'];

            return $new_columns;
        }

        public function easy_theme_builder_sortable_columns ( $columns ) {
            $columns['template_type'] = 'template_type';
            return $columns;
        }

        public function easy_theme_builder_posts_custom_column( $column, $post_id ) {
            if ( $column === 'template_type' ) {
                $type = get_post_meta( $post_id, 'easyel_template_type', true );
                echo esc_html( ucfirst( $type ) );
            }

            if ( $column === 'display_conditions' ) {
                $conditions = get_post_meta( $post_id, 'easyel_conditions', true );

                if ( $conditions ) {
                    $decoded = json_decode( $conditions, true );

                    if ( is_array( $decoded ) ) {
                        foreach ( $decoded as $cond ) {
                            // include/exclude + main → sub
                            $include_type = isset( $cond['include'] ) ? $cond['include'] : 'include';
                            echo '<div>';
                            echo '<strong>' . esc_html( ucfirst($include_type) ) . '</strong> : ';
                            echo esc_html( $cond['main'] ) . ' → ' . esc_html( $cond['sub'] );
                            echo '</div>';
                        }
                    }
                } else {
                    echo '<em>—</em>';
                }
            }
        }

        public function easyel_filter_easy_theme_builder_by_type( $query ) {
            if ( is_admin() && $query->is_main_query() && $query->get('post_type') === 'easy_theme_builder' ) {
               
                // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
                if ( isset( $_GET['easy_etb_type'] ) 
                    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
                    && in_array( $_GET['easy_etb_type'], ['single','archive'], true ) 
                ) {
                    $query->set( 'meta_query', [
                        [
                            'key'   => 'easyel_template_type', 
                            // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
                            'value' => sanitize_text_field( wp_unslash( $_GET['easy_etb_type'] ) ),
                        ]
                    ]);
                }

            }
        }

        // Get archives
       
        public function easyel_get_archives_func() {
            $archives = [
                'core'             => [],
                'posts_archive'    => [],
                'products_archive' => [],
                'custom'           => []
            ];

            // ----- Core Archives -----
            $archives['core'][] = [
                'value' => 'index',
                'label' => __('All Archives','easy-elements'),
                'pro'   => false,
                'group' => 'Core'
            ];
            $archives['core'][] = [
                'value' => 'author',
                'label' => __('Author Archive [Pro]','easy-elements'),
                'pro'   => true,
                'group' => 'Core'
            ];
            $archives['core'][] = [
                'value' => 'search',
                'label' => __('Search Results [Pro]','easy-elements'),
                'pro'   => true,
                'group' => 'Core'
            ];
            $archives['core'][] = [
                'value' => 'date',
                'label' => __('Date Archive [Pro]','easy-elements'),
                'pro'   => true,
                'group' => 'Core'
            ];

            // ----- Default Posts archive -----
            $archives['posts_archive'][] = ['value'=>'post_archive','label'=>__('Posts archive [Pro]','easy-elements'),'pro'=>true,'group'=>'Posts'];
            $archives['posts_archive'][] = ['value'=>'category','label'=>__('Categories [Pro]','easy-elements'),'pro'=>true,'group'=>'Posts'];
            $archives['posts_archive'][] = ['value'=>'child_of_category','label'=>__('Direct child Category of [Pro]','easy-elements'),'pro'=>true,'group'=>'Posts'];
            $archives['posts_archive'][] = ['value'=>'any_child_of_category','label'=>__('Any child Category of [Pro]','easy-elements'),'pro'=>true,'group'=>'Posts'];

            $tags = get_terms(['taxonomy'=>'post_tag','hide_empty'=>false]);
            if ( ! empty($tags) ) {
                $archives['posts_archive'][] = [
                    'value'=>'post_tag',
                    'label'=>__('Tags [Pro]','easy-elements'),
                    'pro'  => true,
                    'group'=> 'Posts'
                ];
            }

            // ----- Dynamic Post Types & Taxonomies -----
            $args = [
                'public'   => true,
                '_builtin' => true,
            ];
            $post_types = get_post_types($args, 'objects');
            unset($post_types['attachment']);

            $args['_builtin'] = false;
            $custom_post_types = get_post_types($args, 'objects');

            $post_types = apply_filters('easyel_location_rule_post_types', array_merge($post_types, $custom_post_types));
            $taxonomies = get_taxonomies(['public'=>true], 'objects');

            if ( ! empty($taxonomies) ) {
                foreach ( $taxonomies as $taxonomy ) {
                    if ( 'post_format' === $taxonomy->name ) {
                        continue;
                    }
                    foreach ( $post_types as $post_type ) {
                        if ( in_array($post_type->name, $taxonomy->object_type, true) ) {
                            $archives['custom'][] = [
                                'value' => $taxonomy->name,
                                'label' => sprintf(
                                    /* translators: 1: Taxonomy singular name, 2: Post type singular name */
                                    __('%1$s (%2$s) [Pro]', 'easy-elements'),
                                    $taxonomy->labels->singular_name,
                                    $post_type->labels->singular_name
                                ),
                                'pro'   => true,
                                'group' => 'Custom'
                            ];
                        }
                    }
                }
            }

            // ----- WooCommerce Special Handling -----
            if ( class_exists('WooCommerce') ) {
                $archives['products_archive'][] = [
                    'value' => 'all_product_archive',
                    'label' => __('All Product Archives', 'easy-elements'),
                    'pro'   => false,
                    'group' => 'Products'
                ];

                $woo_taxonomies = ['shop_page', 'product_search', 'product_brand', 'product_cat', 'product_tag'];

                $pro_active_check =  class_exists('Easy_Elements_Pro') ? '' : "[Pro]"; 

                foreach ($woo_taxonomies as $tax) {
                    if ($tax === 'shop_page') {
                        $archives['products_archive'][] = [
                            'value' => $tax,
                            'label' => __('Shop Page [Pro]', 'easy-elements'),
                            'pro'   => true,
                            'group' => 'Products'
                        ];
                    } elseif ($tax === 'product_search') {
                        $archives['products_archive'][] = [
                            'value' => $tax,
                            'label' => __('Search Results [Pro]', 'easy-elements'),
                            'pro'   => true,
                            'group' => 'Products'
                        ];
                    } else {
                        $taxonomy_obj = get_taxonomy($tax);
                        if ($taxonomy_obj) {
                            $archives['products_archive'][] = [
                                'value' => $tax,
                                'label' => "Product ". $taxonomy_obj->labels->singular_name . " " . $pro_active_check,
                                'pro'   => true,
                                'group' => 'Products'
                            ];
                        }
                    }
                }
            }

            /**
             * Filter: easyel_archives_data
             * Allow developers to modify archives (e.g. unlock Pro)
             */
            $archives = apply_filters('easyel_archives_data', $archives);

            wp_send_json_success($archives);
        }

        public function easyel_get_singulars_func() {
            $singulars = [];

            $singulars[] = [
                'value' => 'all',
                'label' => __('All Singular','easy-elements'),
                'pro'   => false,
                'group' => null
            ];

            $singulars[] = [
                'value' => 'front_page',
                'label' => __('Front Page [Pro]','easy-elements'),
                'pro'   => true,
                'group' => null
            ];

            $post_items = [
                ['value'=>'post','label'=>__('Posts [Pro]','easy-elements'),'pro'=>true],
                ['value'=>'in_category','label'=>__('In Category [Pro]','easy-elements'),'pro'=>true],
                ['value'=>'in_category_children','label'=>__('In Category Children [Pro]','easy-elements'),'pro'=>true],
                ['value'=>'in_post_tag','label'=>__('In Tag [Pro]','easy-elements'),'pro'=>true],
                ['value'=>'post_by_author','label'=>__('Posts By Author [Pro]','easy-elements'),'pro'=>true],
            ];
            foreach($post_items as $item){
                $item['group'] = 'Posts';
                $singulars[] = $item;
            }

            $page_items = [
                ['value'=>'page','label'=>__('Pages [Pro]','easy-elements'),'pro'=>true],
                ['value'=>'page_by_author','label'=>__('Pages By Author [Pro]','easy-elements'),'pro'=>true],
            ];

            foreach($page_items as $item){
                $item['group'] = 'Page';
                $singulars[] = $item;
            }

            $others = [
                ['value'=>'child_of','label'=>__('Direct Child Of [Pro]','easy-elements'),'pro'=>true],
                ['value'=>'any_child_of','label'=>__('Any Child Of [Pro]','easy-elements'),'pro'=>true],
                ['value'=>'by_author','label'=>__('By Author [Pro]','easy-elements'),'pro'=>true],
                ['value'=>'not_found404','label'=>__('404 Page [Pro]','easy-elements'),'pro'=>true ],
            ];

            foreach( $others as $item ){
                $item['group'] = null;
                $singulars[] = $item;
            }

            // WooCommerce product single
            if ( class_exists('WooCommerce') ) {
                $singulars[] = [
                    'value' => 'product',
                    'label' => __('Products [Pro]', 'easy-elements'),
                    'pro'   => true,
                    'group' => 'Products'
                ];
                $singulars[] = [
                    'value' => 'product_by_author',
                    'label' => __('Products By Author [Pro]', 'easy-elements'),
                    'pro'   => true,
                    'group' => 'Products'
                ];
            }

            /**
             * Filter: easyel_singulars_data
             * Allows modification of singulars array (e.g., to make Pro items free)
             *
             * @param array $singulars Array of singulars
             */
            $singulars = apply_filters( 'easyel_singulars_data', $singulars );

            wp_send_json_success($singulars);
        }

        /*Ajax modal edit popup*/
        public function easyel_ajax_get_builder() {
            check_ajax_referer('easyel_ajax_nonce', 'nonce');

            if ( ! current_user_can('manage_options') ) {
                wp_send_json_error(['message' => __('Permission denied', 'easy-elements')]);
            }

            if ( empty($_POST['post_id']) ) {
                wp_send_json_error(['message' => __('Missing post ID!', 'easy-elements')]);
            }

            $post_id = intval($_POST['post_id']);
            $post    = get_post($post_id);

            if ( ! $post || $post->post_type !== 'easy_theme_builder' ) {
                wp_send_json_error(['message' => __('Invalid post!', 'easy-elements')]);
            }

            $template_type = get_post_meta($post_id, 'easyel_template_type', true);
            $conditions    = get_post_meta($post_id, 'easyel_conditions', true);
            $conditions    = !empty($conditions) ? json_decode($conditions, true) : [];

            wp_send_json_success([
                'post_id'       => $post_id,
                'template_name' => $post->post_title,
                'template_type' => $template_type,
                'conditions'    => $conditions,
            ]);
        }

        function easyel_update_builder_callback() {
            check_ajax_referer('easyel_ajax_nonce', 'nonce');

            if (!current_user_can('manage_options')) {
                wp_send_json_error(['message' => 'Permission denied']);
            }

            $post_id       = isset($_POST['post_id']) ? absint(wp_unslash($_POST['post_id'])) : 0;
            $template_name = isset($_POST['template_name']) ? sanitize_text_field(wp_unslash($_POST['template_name'])) : '';
            $template_type = isset($_POST['template_type']) ? sanitize_text_field(wp_unslash($_POST['template_type'])) : '';
            $conditions = isset($_POST['conditions'])
                ? map_deep( wp_unslash( $_POST['conditions'] ), 'sanitize_text_field' ) : [];

            if (!$post_id) {
                wp_send_json_error(['message' => 'Invalid post ID']);
            }

            $existing_posts = get_posts([
                'post_type'   => 'easy_theme_builder',
                'post_status' => 'publish',
                'fields'      => 'ids',
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Slow meta_query intentional
                'meta_query'  => [
                    'relation' => 'AND',
                    [
                        'key'   => 'easyel_template_type',
                        'value' => $template_type,
                    ],
                    [
                        'key'   => 'easyel_conditions',
                        'value' => wp_json_encode($conditions),
                    ],
                ],
            ]);

            $existing_posts = array_filter( $existing_posts, function( $post ) use ( $post_id ) {
                return $post->ID != $post_id;
            });

            if (!empty($existing_posts)) {
                wp_send_json_error([
                    'message' => 'A template with the same type and conditions already exists!'
                ]);
            }

            wp_update_post([
                'ID'         => $post_id,
                'post_title' => $template_name,
            ]);

            // Update meta
            update_post_meta($post_id, 'easyel_template_type', $template_type);
            update_post_meta($post_id, 'easyel_conditions', wp_json_encode($conditions));

            wp_send_json_success(['message' => 'Template updated successfully!']);
        }

    }

    // Initialize single instance
    Easyel_Theme_Builder_CPT::instance();
}