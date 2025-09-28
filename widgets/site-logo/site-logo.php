<?php
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Site_Logo_Widget extends \Elementor\Widget_Base {	

	public function get_style_depends() {
		$handle = 'eel-site-logo-style';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/logo.css';
		
		if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
			Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
			return [ $handle ];
		}
		
		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style( $handle, plugins_url( 'css/logo.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
		}
		return [ $handle ];
	}


	public function get_name() {
		return 'eel-site-logo';
	}

	public function get_title() {
		return __( 'Easy Site Logo', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_header_footer_category' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_logo', [
			'label' => __( 'Site Logo Settings', 'easy-elements' ),
		] );
		$this->add_control( 'site_logo_fallback', [
			'label'        => __( 'Use Custom Logo?', 'easy-elements' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '',
		] );
		$this->add_control( 'custom_image', [
			'label'     => __( 'Custom Logo Image', 'easy-elements' ),
			'type'      => Controls_Manager::MEDIA,
			'dynamic'   => [ 'active' => true ],
			'default'   => [ 'url' => Utils::get_placeholder_image_src() ],
			'condition' => [ 'site_logo_fallback' => 'yes' ],
		] );
		$this->add_responsive_control(
		    'logo_height',
		    [
		        'label' => esc_html__( 'Logo Width', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => [ 'px', '%', 'em', 'vh' ],
		        'range' => [
		            'px' => [
		                'min' => 10,
		                'max' => 500,
		                'step' => 1,
		            ],
		            '%' => [
		                'min' => 1,
		                'max' => 100,
		            ],
		            'em' => [
		                'min' => 0.1,
		                'max' => 10,
		            ],
		        ],
		        'default' => [
		            'unit' => 'px',
		            'size' => '',
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-site-logo a img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
					'{{WRAPPER}} .eel-site-logo-added' => 'width: {{SIZE}}{{UNIT}};',
		        ],
		    ]
		);
		$this->add_responsive_control( 'align', [
			'label'     => __( 'Alignment', 'easy-elements' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [ 'title' => __( 'Left', 'easy-elements' ), 'icon' => 'eicon-h-align-left' ],
				'center' => [ 'title' => __( 'Center', 'easy-elements' ), 'icon' => 'eicon-h-align-center' ],
				'right'  => [ 'title' => __( 'Right', 'easy-elements' ), 'icon' => 'eicon-h-align-right' ],
			],
			'default'   => 'left',
			'selectors' => [
				'{{WRAPPER}} .eel-site-logo' => 'text-align: {{VALUE}};',
			],
		] );
		$this->add_control( 'caption_source', [
			'label'   => __( 'Show Caption?', 'easy-elements' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => '',
		] );
		$this->add_control( 'caption', [
			'label'     => __( 'Custom Caption', 'easy-elements' ),
			'type'      => Controls_Manager::TEXT,
			'dynamic'   => [ 'active' => true ],
			'condition' => [ 'caption_source' => 'yes' ],
		] );
		$this->add_control( 'link_to', [
			'label'   => __( 'Link To', 'easy-elements' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'default',
			'options' => [
				'default' => __( 'Home Page', 'easy-elements' ),
				'none'    => __( 'None', 'easy-elements' ),
				'file'    => __( 'Media File', 'easy-elements' ),
				'custom'  => __( 'Custom URL', 'easy-elements' ),
			],
		] );
		$this->add_control( 'link', [
			'label'       => __( 'Custom URL', 'easy-elements' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [ 'active' => true ],
			'placeholder' => __( 'https://your-link.com', 'easy-elements' ),
			'condition'   => [ 'link_to' => 'custom' ],
			'show_label'  => false,
		] );
		$this->end_controls_section();

		// Caption Style Section
		$this->start_controls_section(
			'section_caption_style',
			[
				'label' => __( 'Caption Style', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [ 'caption_source' => 'yes' ],
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'     => __( 'Color', 'easy-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eel-site-logo .wp-caption-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'label'    => __( 'Typography', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel-site-logo .wp-caption-text',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'caption_text_shadow',
				'label'    => __( 'Text Shadow', 'easy-elements' ),
				'selector' => '{{WRAPPER}} .eel-site-logo .wp-caption-text',
			]
		);

		$this->add_responsive_control(
			'caption_margin',
			[
				'label'      => __( 'Margin', 'easy-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eel-site-logo .wp-caption-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Add Header Edit Button if in Header template
		if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
			$this->start_controls_section(
				'ee_header_edit_section',
				[
					'label' => __( 'Header Builder', 'easy-elements' ),
				]
			);
			$this->add_control(
				'ee_header_edit_button',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<a href="http://localhost:10185/wp-admin/edit.php?post_type=ee-elementor-hf" target="_blank" class="elementor-button elementor-button-success" style="margin:10px 0;display:inline-block;">' . __( 'Edit Header Templates', 'easy-elements' ) . '</a>',
					'content_classes' => 'ee-header-edit-link',
				]
			);
			$this->end_controls_section();
		}
	}

	protected function get_logo_or_fallback( $settings ) {
		// Helper to get image URL from attachment ID
		$get_image_url = function( $id ) {
			$img = wp_get_attachment_image_src( $id, 'full' );
			return $img ? $img[0] : false;
		};

		// 1. Custom logo from widget
		if ( 'yes' === $settings['site_logo_fallback'] ) {
			if ( ! empty( $settings['custom_image']['id'] ) ) {
				if ( $url = $get_image_url( $settings['custom_image']['id'] ) ) {
					return [ 'type' => 'image', 'url' => $url ];
				}
			} elseif ( ! empty( $settings['custom_image']['url'] ) ) {
				return [ 'type' => 'image', 'url' => $settings['custom_image']['url'] ];
			}
		}

		// 2. Kirki logo (entro_logo)
		$kirki_logo = null;
		if ( class_exists( 'Kirki' ) ) {
			$kirki_logo = Kirki::get_option( 'entro', 'site_logo' );
		}
		if ( is_array( $kirki_logo ) && ! empty( $kirki_logo['url'] ) ) {
			return [ 'type' => 'image', 'url' => $kirki_logo['url'] ];
		}
		if ( is_string( $kirki_logo ) && filter_var( $kirki_logo, FILTER_VALIDATE_URL ) ) {
			return [ 'type' => 'image', 'url' => $kirki_logo ];
		}

		// 3. WP Site Identity logo
		if ( $logo_id = get_theme_mod( 'custom_logo' ) ) {
			if ( $url = $get_image_url( $logo_id ) ) {
				return [ 'type' => 'image', 'url' => $url ];
			}
		}

		// 4. Kirki site title and tagline fallback
		$kirki_title = null;
		$kirki_tagline = null;
		if ( class_exists( 'Kirki' ) ) {
			$kirki_title = Kirki::get_option( 'entro', 'site_title' );
			$kirki_tagline = Kirki::get_option( 'entro', 'site_tagline' );
		}
		if ( ! empty( $kirki_title ) ) {
			return [
				'type'    => 'text',
				'title'   => $kirki_title,
				'tagline' => $kirki_tagline,
			];
		}

		// 5. Site title and tagline fallback
		return [
			'type'    => 'text',
			'title'   => get_bloginfo( 'name' ),
			'tagline' => get_bloginfo( 'description' ),
		];
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$logo_data = $this->get_logo_or_fallback( $settings );
		$caption  = ! empty( $settings['caption_source'] ) && 'yes' === $settings['caption_source'] ? $settings['caption'] : '';
		$alt_text = esc_attr( get_bloginfo( 'name' ) );

		$this->add_render_attribute( 'wrapper', 'class', 'eel-site-logo' );

		// Add eel-site-logo-added class if logo image is present
		if ( $logo_data['type'] === 'image' ) {
			$this->add_render_attribute( 'wrapper', 'class', 'eel-site-logo-added' );
		}

		// Link setup
		$link = '';
		if ( 'file' === $settings['link_to'] && isset( $logo_data['url'] ) ) {
			$link = $logo_data['url'];
		} elseif ( 'default' === $settings['link_to'] ) {
			$link = home_url();
		} elseif ( 'custom' === $settings['link_to'] && ! empty( $settings['link']['url'] ) ) {
			$link = $settings['link']['url'];
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe, output contains trusted HTML attributes.
		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>';

		if ( $caption ) {
			echo '<figure class="wp-caption">';
		}
		if ( $link ) {
			$target = ! empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe, output contains trusted HTML attributes.
			echo '<a href="' . esc_url( $link ) . '"' . $target . '>';
		}
		if ( $logo_data['type'] === 'image' ) {
			$attachment_id = 0;
			if ( ! empty( $settings['custom_image']['id'] ) ) {
				$attachment_id = $settings['custom_image']['id'];
			} else {
				$attachment_id = attachment_url_to_postid( $logo_data['url'] );
			}
			if ( $attachment_id ) {
				$sticky_logo_id  = get_theme_mod( 'sticky_logo' );
				$sticky_logo_url = is_numeric( $sticky_logo_id ) ? wp_get_attachment_image_url( $sticky_logo_id, 'full' ) : $sticky_logo_id;
			
				// Build class string
				$main_logo_classes = 'eel-main-logo';
				if ( empty( $sticky_logo_url ) ) {
					$main_logo_classes .= ' eel-no-sticky';
				}
			
				// Output main logo
				echo wp_get_attachment_image( $attachment_id, 'full', false, [
					'class'    => $main_logo_classes,
					'alt'      => $alt_text,
					'loading'  => 'lazy',
					'decoding' => 'async',
				] );
			
				// Output sticky logo if available
				if ( $sticky_logo_url ) {
					echo '<img class="eel-sticky-logo" src="' . esc_url( $sticky_logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" decoding="async">';
				}
			} else {
				$img_html = '<img src="' . esc_url( $logo_data['url'] ) . '" alt="' . esc_attr( $alt_text ) . '" decoding="async">';
				echo wp_kses_post( apply_filters( 'easy_elements_site_logo_fallback_img_html', $img_html, $logo_data['url'], $alt_text, $settings ) );
			}			
		} else {
			// Show site title and tagline as fallback
			echo '<div class="eel-site-title-tagline">';
			echo '<h1 class="eel-site-title">' . esc_html( $logo_data['title'] ) . '</h1>';
			if ( ! empty( $logo_data['tagline'] ) ) {
				echo '<p class="eel-site-tagline">' . esc_html( $logo_data['tagline'] ) . '</p>';
			}
			echo '</div>';
		}
		if ( $link ) {
			echo '</a>';
		}
		if ( $caption ) {
			echo '<figcaption class="wp-caption-text">' . esc_html( $caption ) . '</figcaption>';
			echo '</figure>';
		}
		echo '</div>';
	}
}
?>
