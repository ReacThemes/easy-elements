<?php
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_FAQ_Accordion_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
		$handle = 'eel-faq-accordion-style';
		$css_path = plugin_dir_path( __FILE__ ) . 'css/faq.css';
		
		if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
			Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
			return [ $handle ];
		}
		
		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style( $handle, plugins_url( 'css/faq.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
		}
		return [ $handle ];
	}

	public function get_script_depends() {
		$handle = 'eel-faq-accordion-script';
		$js_path = plugin_dir_path( __FILE__ ) . 'js/faq.js';
		
		        // Check if minification is enabled and helper class exists
        if ( get_option( 'easyel_elements_minify_js', '0' ) === '1' && class_exists( 'Easyel_Elements_JS_Loader_Helper' ) ) {
            try {
                Easyel_Elements_JS_Loader_Helper::easyel_elements_load_minified_inline_js( $handle, $js_path );
                return [ $handle ];
            } catch ( Exception $e ) {
                // Fallback to normal loading if minification fails
              
            }
        }
		
		if ( ! wp_script_is( $handle, 'registered' ) && file_exists( $js_path ) ) {
			wp_register_script( $handle, plugins_url( 'js/faq.js', __FILE__ ), [ 'jquery' ], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $js_path ) : '1.0.0', true );
		}
		return [ $handle ];
	}

	public function get_name() {
		return 'eel-faq-accordion';
	}

	public function get_title() {
		return esc_html__( 'Easy FAQ', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	public function get_keywords() {
		return [ 'faq', 'accordion', 'question', 'answer' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_faq_content',
			[ 'label' => esc_html__( 'Easy FAQ Items', 'easy-elements' ) ]
		);

		$repeater = new Repeater();

		$repeater->add_control( 'title', [
			'label' => esc_html__( 'Question', 'easy-elements' ),
			'type' => Controls_Manager::TEXT,
			'label_block' => true,
			'default' => esc_html__( 'What is Easy Elements?', 'easy-elements' ),
		] );

		$repeater->add_control( 'description', [
			'label' => esc_html__( 'Answer', 'easy-elements' ),
			'type' => Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Easy Elements is a best plugin for Elementor.', 'easy-elements' ),
		] );

		$repeater->add_control( 'active_toggle', [
			'label' => esc_html__( 'Active by Default', 'easy-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			'label_off' => esc_html__( 'No', 'easy-elements' ),
			'return_value' => 'yes',
			'default' => 'no',
		] );

		$repeater->add_control( 'icon_open', [
			'label' => esc_html__( 'Open Icon', 'easy-elements' ),
			'type' => Controls_Manager::ICONS,
			'default' => [ '' ],
		] );

		$repeater->add_control( 'icon_close', [
			'label' => esc_html__( 'Close Icon', 'easy-elements' ),
			'type' => Controls_Manager::ICONS,
			'default' => [ '' ],
		] );

		$this->add_control( 'faq_items', [
		    'label' => esc_html__( 'FAQ List', 'easy-elements' ),
		    'type' => Controls_Manager::REPEATER,
		    'fields' => $repeater->get_controls(),
		    'default' => [
		        [
		            'title'       => esc_html__( 'What is Easy Elements?', 'easy-elements' ),
		            'description' => esc_html__( 'Easy Elements is a custom Elementor addon plugin that offers useful widgets.', 'easy-elements' ),
		        ],
		        [
		            'title'       => esc_html__( 'Does it work with Elementor Free?', 'easy-elements' ),
		            'description' => esc_html__( 'Yes, it fully supports Elementor Free version without requiring Elementor Pro.', 'easy-elements' ),
		        ],
		        [
		            'title'       => esc_html__( 'How to install Easy Elements?', 'easy-elements' ),
		            'description' => esc_html__( 'Upload the plugin via WordPress Dashboard or FTP and activate it.', 'easy-elements' ),
		        ],
		        [
		            'title'       => esc_html__( 'What widgets are included?', 'easy-elements' ),
		            'description' => esc_html__( 'Widgets include logo grid, testimonials, CTA sections, pricing tables, and more.', 'easy-elements' ),
		        ],
		        [
		            'title'       => esc_html__( 'How do I get support or updates?', 'easy-elements' ),
		            'description' => esc_html__( 'Support and updates are available via the official website or marketplace.', 'easy-elements' ),
		        ],
		    ],
		    'title_field' => '{{{ title }}}',
		] );

		$this->add_control( 'title_tag', [
			'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'h4',
			'options' => [
				'h1' => 'H1',
				'h2' => 'H2',
				'h3' => 'H3',
				'h4' => 'H4',
				'h5' => 'H5',
				'h6' => 'H6',
				'div' => 'div',
				'span' => 'span',
				'p' => 'p',
			],
		] );

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'row-reverse' => [
						'title' => esc_html__( 'Left', 'easy-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'row' => [
						'title' => esc_html__( 'Right', 'easy-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'row',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .eel-faq-question' => 'flex-direction: {{VALUE}}; justify-content: left;',
				],
			]
		);


		$this->add_control( 'open_all_toggle', [
			'label' => esc_html__( 'Open All FAQs by Default', 'easy-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			'label_off' => esc_html__( 'No', 'easy-elements' ),
			'return_value' => 'yes',
			'default' => 'no',
		] );

		$this->add_control( 'enable_sticky', [
			'label' => esc_html__( 'Enable Sticky', 'easy-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			'label_off' => esc_html__( 'No', 'easy-elements' ),
			'return_value' => 'yes',
			'default' => 'no',
			'condition' => [
				'open_all_toggle' => 'yes',
			],
		] );

		$this->add_control( 'enable_faq_schema', [
			'label' => esc_html__( 'Enable FAQ Schema', 'easy-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'easy-elements' ),
			'label_off' => esc_html__( 'No', 'easy-elements' ),
			'return_value' => 'yes',
			'default' => 'no',
		] );

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style_section',
			[
				'label' => esc_html__( 'Title', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Title Color
		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-title' => 'color: {{VALUE}};',
			],
		] );

		// Title Typography
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-title',
		] );

		$this->end_controls_section();
		$this->start_controls_section(
			'description_style_section',
			[
				'label' => esc_html__( 'Description', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Description Color
		$this->add_control( 'description_color', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-answer' => 'color: {{VALUE}};',
			],
		] );

		// Description Typography
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'label'    => esc_html__( 'Typography', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-answer',
		] );

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__( 'Icon', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control( 'icon_color', [
			'label'     => esc_html__( 'Color', 'easy-elements' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eel-faq-icon, {{WRAPPER}} .eel-faq-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
			],
		] );

		$this->add_responsive_control(
			'icon_position_y',
			[
				'label' => esc_html__( 'Vertical Position', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-faq-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'icon_position_y_active',
			[
				'label' => esc_html__( 'Vertical Position Active', 'easy-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .eel-faq-item.active .eel-faq-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_box_style_section',
			[
				'label' => esc_html__( 'Items', 'easy-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
		    Group_Control_Background::get_type(),
		    [
		        'name' => 'faq_background',
		        'label' => esc_html__( 'Background Type', 'easy-elements' ),
		        'types' => [ 'classic', 'gradient' ],
		        'selector' => '{{WRAPPER}} .eel-faq-item',
		    ]
		);

		// Border Radius
		$this->add_responsive_control( 'item_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'easy-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .eel-faq-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		// Border
		$this->add_group_control( \Elementor\Group_Control_Border::get_type(), [
			'name'     => 'item_border',
			'label'    => esc_html__( 'Border', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-item',
		] );

		// Shadow
		$this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [
			'name'     => 'item_shadow',
			'label'    => esc_html__( 'Box Shadow', 'easy-elements' ),
			'selector' => '{{WRAPPER}} .eel-faq-item',
		] );

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__('Padding', 'easy-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .eel-faq-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label' => esc_html__('Space Between Items', 'easy-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .eel-faq-accordion' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		

		$this->end_controls_section();
	}


	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( empty( $settings['faq_items'] ) ) return;

		$title_tag = tag_escape( $settings['title_tag'] ?? 'h4' );
		$open_all_class = ( $settings['open_all_toggle'] === 'yes' ) ? 'eel-faq-open-all' : '';
		$enable_sticky = ( $settings['enable_sticky'] === 'yes' ) ? 'eel-faq-sticky' : '';
		

		// FAQ Schema Output
		if ( isset( $settings['enable_faq_schema'] ) && $settings['enable_faq_schema'] === 'yes' && ! empty( $settings['faq_items'] ) ) {

			$schema = [
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => array_map( function ( $item ) {
					return [
						'@type'          => 'Question',
						'name'           => isset( $item['title'] ) ? wp_strip_all_tags( $item['title'] ) : '',
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text'  => isset( $item['description'] ) ? wp_kses_post( $item['description'] ) : '',
						],
					];
				}, $settings['faq_items'] ),
			];
		
			echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
		}
		
		?>

		<div class="eel-faq-accordion <?php echo esc_attr( $open_all_class ); ?> <?php echo esc_attr( $enable_sticky ); ?>" id="<?php echo esc_attr( $this->get_id() ); ?>">
			<?php foreach ( $settings['faq_items'] as $index => $item ) :
				$is_active = ( ! empty( $item['active_toggle'] ) && $item['active_toggle'] === 'yes' ) ? 'active' : '';
				$title = isset( $item['title'] ) ? $item['title'] : '';
				$description = isset( $item['description'] ) ? $item['description'] : '';
				$icon_open = isset( $item['icon_open'] ) ? $item['icon_open'] : array();
				$icon_close = isset( $item['icon_close'] ) ? $item['icon_close'] : array();
				?>
				<div class="eel-faq-item <?php echo esc_attr( $is_active ); ?>">
					<div class="eel-faq-question">
						<<?php echo esc_html( $title_tag ); ?> class="eel-faq-title" tabindex="0">
							<?php echo esc_html( $title ); ?>
						</<?php echo esc_html( $title_tag ); ?>>
						<span class="eel-faq-icon eel-faq-icon-open">
							<?php
								if ( ! empty( $icon_open ) && ! empty( $icon_open['value'] ) ) {
									Icons_Manager::render_icon( $icon_open, [ 'aria-hidden' => 'true' ] );
								} else {
									echo '<i class="unicon-close" aria-hidden="true"></i>';
								}
							?>
						</span>
						<span class="eel-faq-icon eel-faq-icon-close">
							<?php
							if ( ! empty( $icon_close ) && ! empty( $icon_close['value'] ) ) {
								Icons_Manager::render_icon( $icon_close, [ 'aria-hidden' => 'true' ] );
							} else {
								echo '<i class="unicon-add" aria-hidden="true"></i>';
							}
							?>
						</span>
					</div>
					<div class="eel-faq-answer">
						<?php echo wp_kses_post( $description ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
?>