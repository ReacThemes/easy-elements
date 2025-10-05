<?php
use EASY_EHF\Lib\EASY_EHF_Target_Rules_Fields;

defined( 'ABSPATH' ) or exit;

/**
 * Easy_EHF_Admin setup
 *
 * @since 1.0.0
 */
class Easy_EHF_Admin {

	/**
	 * Holds the singleton instance of Easy_EHF_Admin.
	 *
	 * @var Easy_EHF_Admin|null
	 */
	private static $instance = null;

	/**
	 * Returns the singleton instance of Easy_EHF_Admin.
	 *
	 * @return Easy_EHF_Admin
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		add_action( 'elementor/init', [ self::class, 'easy_load_admin' ], 0 );

		return self::$instance;
	}

	public static function easy_load_admin() {
		add_action( 'elementor/editor/after_enqueue_styles', [ self::class, 'easy_admin_enqueue_scripts' ] );
	}

	/** Enqueue admin styles after Elementor editor loads. */
	public static function easy_admin_enqueue_scripts( $hook ) {
        $css_file_url = EASYELEMENTS_ASSETS_ADMIN . 'header-footer-builder/admin/assets/css/ehf-admin.css';
        $css_file_path = EASYELEMENTS_DIR_PATH . 'header-footer-builder/admin/assets/css/ehf-admin.css';
        $version = file_exists($css_file_path) ? filemtime($css_file_path) : null;        
        wp_enqueue_style('easy-hfe-admin-styles', $css_file_url, array(), $version );
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'easy_ehf_posttype' ] );	
		add_action( 'admin_enqueue_scripts', array( $this, 'easy_ehf_admin_scripts' ) );
		add_action( 'add_meta_boxes', [ $this, 'ehf_register_metabox' ] );
		add_action( 'save_post', [ $this, 'ehf_save_meta' ] );
		add_action( 'admin_notices', [ $this, 'easy_location_notice' ] );
		add_action( 'template_redirect', [ $this, 'easy_template_frontend' ] );
		add_filter( 'single_template', [ $this, 'easy_load_canvas_template' ] );
		add_filter( 'manage_ee-elementor-hf_posts_columns', [ $this, 'easy_shortcode_columns' ] );
		add_action( 'manage_ee-elementor-hf_posts_custom_column', [ $this, 'easy_rnd_shortcode_column' ], 10, 2 );

		if ( is_admin() ) {
			add_action( 'manage_ee-elementor-hf_posts_custom_column', [ $this, 'easy_content_content' ], 10, 2 );
			add_filter( 'manage_ee-elementor-hf_posts_columns', [ $this, 'easy_col_headings' ] );
			require_once EASYELEMENTS_DIR_PATH . 'header-footer-builder/admin/easy-ehf-addons-actions.php';
		}

	}

	/**
	 * Enqueue admin styles for Easy Elements Header Footer
	 */
	public function easy_ehf_admin_scripts() {
		// Absolute path to the CSS file
		$css_file = EASYELEMENTS_PATH . 'header-footer-builder/admin/assets/css/ehf-admin.css';
		// URL to the CSS file
		$css_url  = EASYELEMENTS_ASSETS_ADMIN . 'header-footer-builder/admin/assets/css/ehf-admin.css';
		// Generate version from file modified time to avoid cache issues
		$version = file_exists( $css_file ) ? filemtime( $css_file ) : '1.0.0';

		// Register and enqueue the style
		wp_register_style(
			'easy-hfe-admin-styles', 
			$css_url,               
			array(),                
			$version
		);

		wp_enqueue_style( 'easy-hfe-admin-styles' );
	}


	/**
	 * Adds or removes list table column headings.
	 *
	 * @param array $columns Array of columns.
	 * @return array
	 */
	public function easy_col_headings( $columns ) {
		unset( $columns['date'] );

		$columns['elementor_hf_display_rules'] = __( 'Display Condition', 'easy-elements' );
		$columns['date']                       = __( 'Date', 'easy-elements' );

		return $columns;
	}

	/**
	 * Adds the custom list table column content.
	 *
	 * @param array $column Name of column.
	 * @param int   $post_id Post id.
	 * @return void
	 */
	public function easy_content_content( $column, $post_id ) {

		if ( 'elementor_hf_display_rules' == $column ) {

			$locations = get_post_meta( $post_id, 'ehf_target_include_locations', true );
			if ( ! empty( $locations ) ) {
				echo '<div class="ast-advanced-headers-location-wrap" style="margin-bottom: 5px;">';
				echo '<strong>Display: </strong>';
				$this->easy_display_location_rules( $locations );
				echo '</div>';
			}

			$locations = get_post_meta( $post_id, 'ehf_target_exclude_locations', true );
			if ( ! empty( $locations ) ) {
				echo '<div class="ast-advanced-headers-exclusion-wrap" style="margin-bottom: 5px;">';
				echo '<strong>Exclusion: </strong>';
				$this->easy_display_location_rules( $locations );
				echo '</div>';
			}

			$users = get_post_meta( $post_id, 'ehf_target_user_roles', true );
			if ( isset( $users ) && is_array( $users ) ) {
				if ( isset( $users[0] ) && ! empty( $users[0] ) ) {
					$user_label = [];
					foreach ( $users as $user ) {
						$user_label[] = esc_html( EASY_EHF_Target_Rules_Fields::easy_get_user_by_key( $user ) );
					}
					echo '<div class="ast-advanced-headers-users-wrap">';
					echo '<strong>Users: </strong>';
					echo esc_html( join( ', ', $user_label ) );
					echo '</div>';
				}
			}
		}
	}

	/**
	 * Get Markup of Location rules for Display rule column.
	 *
	 * @param array $locations Array of locations.
	 * @return void
	 */
	public function easy_display_location_rules( $locations ) {

		$location_label = [];
		$index          = array_search( 'specifics', $locations['rule'] );
		if ( false !== $index && ! empty( $index ) ) {
			unset( $locations['rule'][ $index ] );
		}

		if ( isset( $locations['rule'] ) && is_array( $locations['rule'] ) ) {
			foreach ( $locations['rule'] as $location ) {
				$location_label[] = esc_html( EASY_EHF_Target_Rules_Fields::easy_get_location_by_key( $location ) );
			}
		}
		if ( isset( $locations['specific'] ) && is_array( $locations['specific'] ) ) {
			foreach ( $locations['specific'] as $location ) {
				$location_label[] = esc_html( EASY_EHF_Target_Rules_Fields::easy_get_location_by_key( $location ) );
			}
		}

		echo esc_html( join( ', ', $location_label ) );
	}


	/** Register custom post type for HFE templates. */
	public function easy_ehf_posttype() {
		$labels = [
			'name'               => __( 'Easy Header & Footer', 'easy-elements' ),
			'singular_name'      => __( 'Easy Header & Footer', 'easy-elements' ),
			'menu_name'          => __( 'Easy Header & Footer', 'easy-elements' ),
			'name_admin_bar'     => __( 'Easy Header & Footer', 'easy-elements' ),
			'add_new'            => __( 'Add New', 'easy-elements' ),
			'add_new_item'       => __( 'Add New Header or Footer', 'easy-elements' ),
			'new_item'           => __( 'New Header Footer', 'easy-elements' ),
			'edit_item'          => __( 'Edit Header Footer', 'easy-elements' ),
			'view_item'          => __( 'View Header Footer', 'easy-elements' ),
			'all_items'          => __( 'All Header Footer', 'easy-elements' ),
			'search_items'       => __( 'Search Templates', 'easy-elements' ),
			'parent_item_colon'  => __( 'Parent Templates:', 'easy-elements' ),
			'not_found'          => __( 'No Templates found.', 'easy-elements' ),
			'not_found_in_trash' => __( 'No Templates found in Trash.', 'easy-elements' ),
		];

		$args = [
			'labels'              => $labels,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => true,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_icon'           => 'dashicons-editor-kitchensink',
			'supports'            => [ 'title', 'elementor' ],
		];
		register_post_type( 'ee-elementor-hf', $args );

		add_filter( 'body_class', [ $this, 'easy_fixed_header_body_class' ] );
	}

	/**
	 * Register meta box(es).
	 */
	function ehf_register_metabox() {
		add_meta_box(
			'ehf-meta-box',
			__( 'Easy Header & Footer Builder Options', 'easy-elements' ),
			[
				$this,
				'easy_metabox_render',
			],
			'ee-elementor-hf',
			'normal',
			'high'
		);
	}

	/** Render meta field for the given post. */
	function easy_metabox_render( $post ) {
		$values            = get_post_custom( $post->ID );
		$template_type     = isset( $values['ehf_template_type'] ) ? esc_attr( $values['ehf_template_type'][0] ) : '';
		$display_on_canvas = isset( $values['display-on-canvas-template'] ) ? true : false;

		$fixed_header      = get_post_meta( $post->ID, '_easyel_fixed_header', true );

		// We'll use this nonce field later on when saving.
		wp_nonce_field( 'ehf_meta_nounce', 'ehf_meta_nounce' );
		?>
		<table class="hfe-options-table widefat">
			<tbody>
				<tr class="hfe-options-row type-of-template">
					<td class="hfe-options-row-heading">
						<label for="ehf_template_type"><?php esc_html_e( 'Template Types', 'easy-elements' ); ?></label>
					</td>
					<td class="hfe-options-row-content">
						<select name="ehf_template_type" id="ehf_template_type">
							<option value="" <?php selected( $template_type, '' ); ?>><?php esc_html_e( 'Select Option', 'easy-elements' ); ?></option>
							<option value="type_header" <?php selected( $template_type, 'type_header' ); ?>><?php esc_html_e( 'Header', 'easy-elements' ); ?></option>
							<option value="type_footer" <?php selected( $template_type, 'type_footer' ); ?>><?php esc_html_e( 'Footer', 'easy-elements' ); ?></option>
						</select>
					</td>
				</tr>

				<?php $this->easy_rules_tab(); ?>
				<tr class="hfe-options-row hfe-shortcode">
					<td class="hfe-options-row-heading">
						<label for="ehf_template_type"><?php esc_html_e( 'Shortcode', 'easy-elements' ); ?></label>
						<i class="hfe-options-row-heading-help dashicons dashicons-editor-help" title="<?php esc_html_e( 'Copy this shortcode and paste it into your post, page, or text widget content.', 'easy-elements' ); ?>">
						</i>
					</td>
					<td class="hfe-options-row-content">
						<span class="hfe-shortcode-col-wrap">
							<input type="text" onfocus="this.select();" readonly="readonly" value="[easyhfe_template id='<?php echo esc_attr( $post->ID ); ?>']" class="hfe-large-text code">
						</span>
					</td>
				</tr>

				<tr class="hfe-options-row fixed-header">
					<td class="hfe-options-row-heading">
						<label for="easyel_fixed_header"><?php esc_html_e( 'Enable Transparent Header', 'easy-elements' ); ?></label>
						<div>
						<?php esc_html_e( 'Enable this option to make the header transparent. Turn this on if you want your header to appear clear/see-through at the top of the page.', 'easy-elements' ); ?>
						</div>
					</td>
					<td class="hfe-options-row-content">
						<input type="checkbox" id="easyel_fixed_header" name="easyel_fixed_header" value="1" <?php checked( $fixed_header, '1' ); ?> />
						<em><?php esc_html_e( 'Keep this header fixed at the top of the page.', 'easy-elements' ); ?></em>
					</td>
				</tr>

				<tr class="hfe-options-row enable-for-canvas">
					<td class="hfe-options-row-heading">
						<label for="display-on-canvas-template">
							<?php esc_html_e( 'Enable Layout for Elementor Canvas Template?', 'easy-elements' ); ?>
						</label>
						<i class="hfe-options-row-heading-help dashicons dashicons-editor-help" title="<?php esc_html_e( 'Enabling this option will display this layout on pages using Elementor Canvas Template.', 'easy-elements' ); ?>"></i>
					</td>
					<td class="hfe-options-row-content">
						<input type="checkbox" id="display-on-canvas-template" name="display-on-canvas-template" value="1" <?php checked( $display_on_canvas, true ); ?> />
					</td>
				</tr>				
			</tbody>
		</table>
		<?php
	}

	/**
	 * Display for Rules Tabs.
	 *
	 * @since  1.0.0
	 */
	public function easy_rules_tab() {
		// Load Target Rule assets.
		EASY_EHF_Target_Rules_Fields::get_instance()->easy_admin_styles();

		$include_locations = get_post_meta( get_the_id(), 'ehf_target_include_locations', true );
		$exclude_locations = get_post_meta( get_the_id(), 'ehf_target_exclude_locations', true );
		$users             = get_post_meta( get_the_id(), 'ehf_target_user_roles', true );
		?>
		<tr class="bsf-target-rules-row hfe-options-row">
			<td class="bsf-target-rules-row-heading hfe-options-row-heading">
				<label><?php esc_html_e( 'Visible On', 'easy-elements' ); ?></label>
				<i class="bsf-target-rules-heading-help dashicons dashicons-editor-help"
					title="<?php echo esc_attr__( 'Choose the pages or areas where this template should show.', 'easy-elements' ); ?>"></i>
			</td>
			<td class="bsf-target-rules-row-content hfe-options-row-content">
				<?php
				EASY_EHF_Target_Rules_Fields::target_rule_settings_field(
					'bsf-target-rules-location',
					[
						'title'          => __( 'Display Condition', 'easy-elements' ),
						'value'          => '[{"type":"basic-global","specific":null}]',
						'tags'           => 'site,enable,target,pages',
						'rule_type'      => 'display',
						'add_rule_label' => __( 'Add Display Condition', 'easy-elements' ),
					],
					$include_locations
				);
				?>
			</td>
		</tr>
		<tr class="bsf-target-rules-row hfe-options-row">
			<td class="bsf-target-rules-row-heading hfe-options-row-heading">
				<label><?php esc_html_e( 'Do Not Show On', 'easy-elements' ); ?></label>
				<i class="bsf-target-rules-heading-help dashicons dashicons-editor-help"
					title="<?php echo esc_attr__( 'Select the pages or sections where this template should NOT appear.', 'easy-elements' ); ?>"></i>
			</td>
			<td class="bsf-target-rules-row-content hfe-options-row-content">
				<?php
				EASY_EHF_Target_Rules_Fields::target_rule_settings_field(
					'bsf-target-rules-exclusion',
					[
						'title'          => __( 'Exclude On', 'easy-elements' ),
						'value'          => '[]',
						'tags'           => 'site,enable,target,pages',
						'add_rule_label' => __( 'Add Exclusion Condition', 'easy-elements' ),
						'rule_type'      => 'exclude',
					],
					$exclude_locations
				);
				?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save meta settings for Elementor Header/Footer template.
	 *
	 * Handles target locations, template type, canvas display, and fixed header option.
	 *
	 * @param int $post_id Post ID being saved.
	 */
	public function ehf_save_meta( $post_id ) {

		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['ehf_meta_nounce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['ehf_meta_nounce'] ) ), 'ehf_meta_nounce' ) ) {
			return;
		}

		// if our current user can't edit this post, bail.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$target_locations = EASY_EHF_Target_Rules_Fields::get_format_rule_value( $_POST, 'bsf-target-rules-location' );
		$target_exclusion = EASY_EHF_Target_Rules_Fields::get_format_rule_value( $_POST, 'bsf-target-rules-exclusion' );
		$target_users     = [];

		update_post_meta( $post_id, 'ehf_target_include_locations', $target_locations );
		update_post_meta( $post_id, 'ehf_target_exclude_locations', $target_exclusion );
		update_post_meta( $post_id, 'ehf_target_user_roles', $target_users );

		if ( isset( $_POST['ehf_template_type'] ) ) {
			$template_type = isset( $_POST['ehf_template_type'] )
				? map_deep( wp_unslash( $_POST['ehf_template_type'] ), 'sanitize_text_field' )
				: '';
			update_post_meta( $post_id, 'ehf_template_type', $template_type );
		}

		if ( isset( $_POST['display-on-canvas-template'] ) ) {
			$canvas_template = isset( $_POST['display-on-canvas-template'] )
				? map_deep( wp_unslash( $_POST['display-on-canvas-template'] ), 'sanitize_text_field' )
				: '';
			update_post_meta( $post_id, 'display-on-canvas-template', $canvas_template );
		} else {
			delete_post_meta( $post_id, 'display-on-canvas-template' );
		}

		if ( isset( $_POST['easyel_fixed_header'] ) ) {
			update_post_meta( $post_id, '_easyel_fixed_header', '1' );
		} else {
			delete_post_meta( $post_id, '_easyel_fixed_header' );
		}
	}

	/**
	 * Display notice when editing the header or footer when there is one more of similar layout is active on the site.
	 *
	 * @since 1.0.0
	 */
	public function easy_location_notice() {
		global $pagenow;
		global $post;

		if ( 'post.php' != $pagenow || ! is_object( $post ) || 'ee-elementor-hf' != $post->post_type ) {
			return;
		}

		$template_type = get_post_meta( $post->ID, 'ehf_template_type', true );

		if ( '' !== $template_type ) {
			$templates = Easy_Header_Footer_Elementor::get_template_id( $template_type );

			// Check if more than one template is selected for current template type.
			if ( is_array( $templates ) && isset( $templates[1] ) && $post->ID != $templates[0] ) {
				$post_title        = '<strong>' . esc_html( get_the_title( $templates[0] ) ) . '</strong>';
				$easy_template_location = '<strong>' . esc_html( $this->easy_template_location( $template_type ) ) . '</strong>';
				/* Translators: Post title, Template Location */
				$message = sprintf( __( 'Template %1$s is already assigned to the location %2$s', 'easy-elements' ), $post_title, $easy_template_location );

				echo '<div class="error"><p>';
				echo wp_kses_post( $message );
				echo '</p></div>';
			}
		}
	}

	/**
	 * Convert the Template name to be added in the notice.
	 *
	 * @since  1.0.0
	 *
	 * @param  String $template_type Template type name.
	 *
	 * @return String $template_type Template type name.
	 */
	public function easy_template_location( $template_type ) {
		$template_type = ucfirst( str_replace( 'type_', '', $template_type ) );

		return $template_type;
	}

	/**
	 * Redirect non-admin users away from single Elementor Header/Footer templates.
	 *
	 * @return void
	 */
	public function easy_template_frontend() {
		if ( is_singular( 'ee-elementor-hf' ) && ! current_user_can( 'edit_posts' ) ) {
			wp_redirect( site_url(), 301 );
			die;
		}
	}

	/**
	 * Add body class for fixed Elementor header if enabled.
	 *
	 * @param array $classes Current body classes.
	 * @return array Modified body classes.
	 */
	public function easy_fixed_header_body_class( $classes ) {
		if ( class_exists( 'Easy_Header_Footer_Elementor' ) ) {
			$header_id = Easy_Header_Footer_Elementor::get_settings( 'type_header', '' );
			if ( $header_id && '1' === get_post_meta( $header_id, '_easyel_fixed_header', true ) ) {
				$classes[] = 'easyel-fixed-header';
			}
		}
		return $classes;
	}

	/**
	 * Load Elementor Canvas template for Header/Footer custom post type.
	 *
	 * Overrides the single template for 'ee-elementor-hf' posts to use
	 * Elementor's canvas template.
	 *
	 * @param string $single_template Current single template path.
	 * @return string Template path to use.
	 */
	function easy_load_canvas_template( $single_template ) {
		global $post;

		if ( 'ee-elementor-hf' == $post->post_type ) {
			$elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

			if ( file_exists( $elementor_2_0_canvas ) ) {
				return $elementor_2_0_canvas;
			} else {
				return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
			}
		}

		return $single_template;
	}

	/**
	 * Set shortcode column for template list.
	 *
	 * @param array $columns template list columns.
	 */
	function easy_shortcode_columns( $columns ) {
		$date_column = $columns['date'];

		unset( $columns['date'] );

		$columns['shortcode'] = __( 'Shortcode', 'easy-elements' );
		$columns['date']      = $date_column;

		return $columns;
	}

	/**
	 * Render shortcode column in admin list table.
	 *
	 * Displays a readonly input with the shortcode for the given post ID.
	 *
	 * @param string $column  Column name being rendered.
	 * @param int    $post_id Current post ID.
	 */
	function easy_rnd_shortcode_column( $column, $post_id ) {
		switch ( $column ) {
			case 'shortcode':
				ob_start();
				?>
				<span class="hfe-shortcode-col-wrap">
					<input type="text" onfocus="this.select();" readonly="readonly" value="[easyhfe_template id='<?php echo esc_attr( $post_id ); ?>']" class="hfe-large-text code">
				</span>

				<?php

				ob_get_contents();
				break;
		}
	}
}

Easy_EHF_Admin::instance();