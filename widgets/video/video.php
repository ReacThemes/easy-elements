<?php
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use \Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Video_Popup_Widget extends \Elementor\Widget_Base {

	public function get_style_depends() {
		$handle = 'eel-video-popup-style'; 
		$css_path = plugin_dir_path( __FILE__ ) . 'css/video.css';
		
		if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
			Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
			return [ $handle ];
		}
		
		if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
			wp_register_style( $handle, plugins_url( 'css/video.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
		}	
	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-video-popup';
	}

	public function get_title() {
		return __( 'Easy Video Popup', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_video',
			[
				'label' => __( 'Video Popup Settings', 'easy-elements' ),
			]
		);

		$this->add_control(
		    'display_type',
		    [
		        'label' => __( 'Display Type', 'easy-elements' ),
		        'type' => Controls_Manager::SELECT,
		        'default' => 'normal',
		        'options' => [
		        	'normal' => __( 'Normal Only', 'easy-elements' ),
		            'popup'  => __( 'Popup Only', 'easy-elements' ),		            
		        ],
		    ]
		);


		$this->add_control(
			'video_type',
			[
				'label'   => __( 'Video Type', 'easy-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'youtube',
				'options' => [
					'youtube'    => __( 'YouTube', 'easy-elements' ),
					'vimeo'      => __( 'Vimeo', 'easy-elements' ),
					'self_hosted'=> __( 'Self Hosted', 'easy-elements' ),
				],
			]
		);

		$this->add_control(
			'video_url',
			[
				'label'       => __( 'Video URL', 'easy-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'https://www.youtube.com/watch?v=hDABYoas7SY',
				'placeholder' => __( 'https://www.youtube.com/watch?v=hDABYoas7SY', 'easy-elements' ),
				'condition'   => [
					'video_type!' => 'self_hosted',
				],
			]
		);

		$this->add_control(
			'self_hosted_video',
			[
				'label'     => __( 'Self Hosted Video', 'easy-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'media_type' => 'video',
				'condition' => [
					'video_type' => 'self_hosted',
				],
			]
		);

		$this->add_control(
		    'self_hosted_autoplay',
		    [
		        'label' => __( 'Autoplay', 'easy-elements' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => '',
		        'condition' => [
		            'display_type' => 'normal',
		            'video_type' => 'self_hosted',
		        ],
		    ]
		);

		$this->add_control(
		    'self_hosted_loop',
		    [
		        'label' => __( 'Loop', 'easy-elements' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => '',
		        'condition' => [
		            'display_type' => 'normal',
		            'video_type' => 'self_hosted',
		        ],
		    ]
		);

		$this->add_control(
		    'self_hosted_muted',
		    [
		        'label' => __( 'Muted', 'easy-elements' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => '',
		        'condition' => [
		            'display_type' => 'normal',
		            'video_type' => 'self_hosted',
		        ],
		    ]
		);

		$this->add_control(
		    'self_hosted_controls',
		    [
		        'label' => __( 'Show Controls', 'easy-elements' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => 'yes',
		        'condition' => [
		            'display_type' => 'normal',
		            'video_type' => 'self_hosted',
		        ],
		    ]
		);


		$this->add_control(
		    'video_height',
		    [
		        'label' => __( 'Video Height', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'size_units' => [ 'px', 'vh' ],
		        'range' => [
		            'px' => [
		                'min' => 100,
		                'max' => 1000,
		            ],
		            'vh' => [
		                'min' => 10,
		                'max' => 100,
		            ],
		        ],
		        'default' => [
		            'size' => 400,
		            'unit' => 'px',
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-wrapper iframe' => 'height: {{SIZE}}{{UNIT}};',
		            '{{WRAPPER}} .eel-video-popup-wrapper video' => 'height: {{SIZE}}{{UNIT}};',
		        ],
		        'condition' => [
		        	'display_type' => 'normal',
		        ],
		    ]
		);


		$this->add_control(
			'popup_trigger_text',
			[
				'label'       => __( 'Trigger Text', 'easy-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Play Video', 'easy-elements' ),
				'condition' => [
					'display_type' => 'popup',
				],
			]
		);

		$this->add_responsive_control(
		    'popup_icon_spacing',
		    [
		        'label' => __( 'Text Spacing', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SLIDER,
		        'range' => [
		            'px' => [
		                'min' => 0,
		                'max' => 100,
		            ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap + .eel-trigger-text' => 'margin-left: {{SIZE}}{{UNIT}};',
		            '{{WRAPPER}} .eel-video-popup-btn .eel-trigger-text + .eel-icon-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		        ],
		    ]
		);


		$this->add_control(
		    'popup_text_color',
		    [
		        'label' => __( 'Text Color', 'easy-elements' ),
		        'type' => Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn .eel-trigger-text' => 'color: {{VALUE}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Typography::get_type(),
		    [
		        'name' => 'popup_text_typography',
		        'label' => __( 'Typography', 'easy-elements' ),
		        'selector' => '{{WRAPPER}} .eel-video-popup-btn .eel-trigger-text',
		        'condition' => [
		            'display_type' => 'popup',
		        ],
		    ]
		);

		$this->add_control(
		    'popup_icon_show',
		    [
		        'label' => __( 'Show Icon', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::SWITCHER,
		        'label_on' => __( 'Yes', 'easy-elements' ),
		        'label_off' => __( 'No', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default' => 'yes',
		        'condition' => [
		            'display_type' => 'popup',
		        ],
		    ]
		);


		$this->add_control(
			'popup_trigger_icon',
			[
				'label' => __( 'Icon', 'easy-elements' ),
				'type'  => Controls_Manager::ICONS,
				'default' => [
					'value' => '',
					'library' => 'fa-solid',
				],
				'condition' => [
					'display_type' => 'popup',
					'popup_icon_show' => 'yes',
				],
			]
		);		

		$this->add_control(
		    'popup_icon_position',
		    [
		        'label' => __( 'Icon Position', 'easy-elements' ),
		        'type' => Controls_Manager::SELECT,
		        'default' => 'before',
		        'options' => [
		            'before' => __( 'Before Text', 'easy-elements' ),
		            'after'  => __( 'After Text', 'easy-elements' ),
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);


		$this->add_control(
		    'popup_icon_color',
		    [
		        'label' => __( 'Icon Color', 'easy-elements' ),
		        'type'  => Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn i'   => 'color: {{VALUE}};',
		            '{{WRAPPER}} .eel-video-popup-btn svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-video-popup-btn svg path' => 'fill: {{VALUE}};',

		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);


		$this->add_responsive_control(
		    'icon_size',
		    [
		        'label' => __( 'Icon Size', 'easy-elements' ),
		        'type' => Controls_Manager::SLIDER,
		        'range' => [
		            'px' => [
		                'min' => 8,
		                'max' => 100,
		            ],
		        ],
		        'default' => [
		            'size' => 20,
		            'unit' => 'px',
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
		            '{{WRAPPER}} .eel-video-popup-btn svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eel-video-popup-btn svg path' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_control(
		    'icon_bg_color',
		    [
		        'label' => __( 'Circle Background Color', 'easy-elements' ),
		        'type' => Controls_Manager::COLOR,
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap' => 'background-color: {{VALUE}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'circle_size',
		    [
		        'label' => __( 'Circle Size', 'easy-elements' ),
		        'type' => Controls_Manager::SLIDER,
		        'range' => [
		            'px' => [ 'min' => 10, 'max' => 200 ],
		        ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; place-content: center;',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'icon_border_radius',
		    [
		        'label' => __( 'Circle Border Radius', 'easy-elements' ),
		        'type' => \Elementor\Controls_Manager::DIMENSIONS,
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap, {{WRAPPER}} .eel-icon-wrap .eel-overlay-play' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_group_control(
		    \Elementor\Group_Control_Box_Shadow::get_type(),
		    [
		        'name'     => 'circle_shadow',
		        'label'    => __( 'Circle Shadow', 'easy-elements' ),
		        'selector' => '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap',
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);


		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'     => 'button_border',
		        'label'    => __( 'Button Border', 'easy-elements' ),
		        'selector' => '{{WRAPPER}} .eel-video-popup-btn .eel-icon-wrap',
		        'condition' => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_control(
		    'circle_glow',
		    [
		        'label'        => __( 'Active Ripple', 'easy-elements' ),
		        'type'         => \Elementor\Controls_Manager::SWITCHER,
		        'label_on'     => __( 'On', 'easy-elements' ),
		        'label_off'    => __( 'Off', 'easy-elements' ),
		        'return_value' => 'yes',
		        'default'      => '',
		        'condition'    => [
		            'display_type' => 'popup',
		            'popup_icon_show' => 'yes',
		        ],
		    ]
		);

		$this->add_control(
		    'glow_color',
		    [
		        'label'     => __( 'Ripple Color', 'easy-elements' ),
		        'type'      => Controls_Manager::COLOR,
		        'default'   => 'rgba(102, 102, 102, 0.1)',
		        'selectors' => [
		            '{{WRAPPER}} .eel-video-popup-btn.eel-glow-active .eel-icon-wrap' => '--glow-color: {{VALUE}};',
		        ],
		        'condition' => [
		            'display_type' => 'popup',
		            'circle_glow' => 'yes',		            
		        ],
		    ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_popup_overlay_settings',
			[
				'label' => __( 'Play Overlay Settings', 'easy-elements' ),
				'condition' => [
					'display_type' => 'popup',
				],
			]
		);

		$this->add_control(
			'popup_play_overlay',
			[
				'label'       => __( 'Play Overlay', 'easy-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Play', 'easy-elements' ),
			]
		);

		$this->add_control(
            'popup_play_color',
            [
                'label' => esc_html__('Text Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-icon-wrap .eel-overlay-play' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'popup_play__typography',
                'selector' => '{{WRAPPER}} .eel-icon-wrap .eel-overlay-play',
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background_overly_play',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eel-icon-wrap .eel-overlay-play',
			]
		);

		$this->end_controls_section();
	}

	private function get_youtube_id( $url ) {
        preg_match('/(?:v=|\/)([0-9A-Za-z_-]{11}).*/', $url, $matches);
        return $matches[1] ?? '';
    }

    private function get_vimeo_id( $url ) {
        preg_match('/(\d+)/', $url, $matches);
        return $matches[1] ?? '';
    }

	protected function render() {
	    $settings = $this->get_settings_for_display();
	    $video_url = '';
	    $embed_type = $settings['video_type'];
	    $display_type = $settings['display_type'];

	    if ( $embed_type === 'self_hosted' && ! empty( $settings['self_hosted_video']['url'] ) ) {
	        $video_url = esc_url( $settings['self_hosted_video']['url'] );
	    } elseif ( ! empty( $settings['video_url'] ) ) {
	        $video_url = esc_url( $settings['video_url'] );
	    }

	    if ( empty( $video_url ) ) {
	        return;
	    }

	    $video_id = uniqid('eel_video_');
	    $circle_glow = ! empty( $settings['circle_glow'] ) && $settings['circle_glow'] === 'yes' ? 'eel-glow-active' : '';

	    echo '<div class="eel-video-popup-wrapper">';

	    if ( $display_type === 'normal' ) {
	        if ( $embed_type === 'self_hosted' && !empty( $settings['self_hosted_video']['url'] ) ) {
	            $attrs = [];

	            if ( ! empty( $settings['self_hosted_autoplay'] ) ) {
	                $attrs[] = 'autoplay';
	            }
	            if ( ! empty( $settings['self_hosted_loop'] ) ) {
	                $attrs[] = 'loop';
	            }
	            if ( ! empty( $settings['self_hosted_muted'] ) ) {
	                $attrs[] = 'muted';
	            }
	            if ( ! empty( $settings['self_hosted_controls'] ) ) {
	                $attrs[] = 'controls';
	            }

	            $attrs[] = 'playsinline';

	            echo '<video src="' . esc_url($video_url) . '" ' . esc_attr(implode( ' ', $attrs )) . ' class="eel-normal-video"></video>';
	        } else {
	            $embed_url = '';
	            if ( $embed_type === 'youtube' ) {
	                $yt_id = $this->get_youtube_id($video_url);
	                if ($yt_id) {
	                    $embed_url = 'https://www.youtube.com/embed/' . $yt_id;
	                }
	            } elseif ( $embed_type === 'vimeo' ) {
	                $vimeo_id = $this->get_vimeo_id($video_url);
	                if ($vimeo_id) {
	                    $embed_url = 'https://player.vimeo.com/video/' . $vimeo_id;
	                }
	            }

	            if ( $embed_url ) {
	                echo '<iframe class="eel-normal-video" src="' . esc_url($embed_url) . '" frameborder="0" allowfullscreen></iframe>';
	            }
	        }
	    }

	    if ( $display_type === 'popup' ) {
	        $icon_html = '';
	        $icon_setting = $settings['popup_trigger_icon'];
	        // Handle custom SVG upload (Elementor 3.18+)
	        if (
	            isset($icon_setting['library']) &&
	            $icon_setting['library'] === 'svg' &&
	            isset($icon_setting['value']['url'])
	        ) {
	            $svg_url = $icon_setting['value']['url'];
	            if (strtolower(pathinfo($svg_url, PATHINFO_EXTENSION)) === 'svg') {
	                $response = wp_remote_get($svg_url);
	                if (!is_wp_error($response) && isset($response['body'])) {
	                    $icon_html = $response['body'];
	                }
	            }
	        } elseif (
	            isset($icon_setting['library']) &&
	            $icon_setting['library'] === 'image' &&
	            isset($icon_setting['value']['url'])
	        ) {
	            $img_url = esc_url($icon_setting['value']['url']);
	            $attachment_id = attachment_url_to_postid($img_url);
	            if ($attachment_id) {
	                $icon_html = wp_get_attachment_image($attachment_id, 'full', false, [
	                    'style' => 'max-width:100%;max-height:100%;',
	                    'alt' => '',
	                ]);
	            } else {
	                // Do not output the image if not an attachment
	                $icon_html = '';
	            }
	        } elseif (!empty($icon_setting['value']) && is_string($icon_setting['value'])) {
	            $icon_class = esc_attr($icon_setting['value']);
	            $icon_html = '<i class="' . $icon_class . '" aria-hidden="true"></i>';
	        } else {
	            $icon_html = '<i class="unicon-play" aria-hidden="true"></i>';
	        }

			$button_label = ! empty( $settings['popup_trigger_text'] ) ? wp_strip_all_tags( $settings['popup_trigger_text'] ) : 'Play Video';
	        ?>

			<button class="eel-video-popup-btn entro-all-video-popup <?php echo esc_attr($circle_glow); ?>"
				data-video-type="<?php echo esc_attr( $embed_type ); ?>"
				data-video-src="<?php echo esc_url( $video_url ); ?>"
				data-popup-id="<?php echo esc_attr( $video_id ); ?>"
				aria-label="<?php echo esc_attr( $button_label ); ?>">

				<?php if ( $settings['popup_icon_show'] === 'yes' && $settings['popup_icon_position'] === 'before' ) : ?>
					<span class="eel-icon-wrap"><?php echo wp_kses_post($icon_html); ?>
						<?php if(!empty($settings['popup_play_overlay'])): ?>
							<span class="eel-overlay-play"><?php echo esc_html( $settings['popup_play_overlay'] ); ?> </span>
						<?php endif; ?>
					</span>
				<?php endif; ?>

				<span class="eel-trigger-text"><?php echo esc_html( $settings['popup_trigger_text'] ); ?></span>

				<?php if ( $settings['popup_icon_show'] === 'yes' && $settings['popup_icon_position'] === 'after' ) : ?>
					<span class="eel-icon-wrap"><?php echo wp_kses_post($icon_html); ?> 
					<?php if(!empty($settings['popup_play_overlay'])): ?>
						<span class="eel-overlay-play"><?php echo esc_html( $settings['popup_play_overlay'] ); ?> </span>
					<?php endif; ?>
					</span>
				<?php endif; ?>

			</button>

	        <div id="<?php echo esc_attr($video_id); ?>" class="eel-video-popup-overlay">
	            <div class="eel-video-popup-content">
	                <span class="eel-video-popup-close">&times;</span>
	                <div class="eel-video-popup-iframe-wrapper"></div>
	            </div>
	        </div>
	        <?php
	    }

	    echo '</div>';
	}
}?>