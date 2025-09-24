<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easyel_Social_Share_Widget extends \Elementor\Widget_Base {
	
	public function get_style_depends() {
		$handle = 'eel-social-share';
	    $css_path = plugin_dir_path( __FILE__ ) . 'css/social-share.css';
	    
	    if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
	        Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
	        return [ $handle ];
	    }
	    
	    if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
	        wp_register_style( $handle, plugins_url( 'css/social-share.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
	    }

	    return [ $handle ];
	}

	public function get_name() {
		return 'eel-social-share';
	}

	public function get_title() {
		return __( 'Easy Social Share', 'easy-elements' );
	}

	public function get_icon() {
		return 'easy-elements-icon';
	}

	public function get_categories() {
		return [ 'easyelements_category' ];
	}

	protected function register_controls() {

	    $this->start_controls_section(
	        'content_section',
	        [
	            'label' => esc_html__('Social Share Settings', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_CONTENT,
	        ]
	    );
	    

	    $this->add_control(
	        'social_platforms',
	        [
	            'label' => esc_html__('Social Platforms', 'easy-elements'),
	            'type' => Controls_Manager::REPEATER,
	            'fields' => [
	                [
	                    'name' => 'platform',
	                    'label' => esc_html__('Platform', 'easy-elements'),
	                    'type' => Controls_Manager::SELECT,
	                    'default' => 'facebook',
	                    'options' => [
	                        'facebook' => esc_html__('Facebook', 'easy-elements'),
	                        'twitter' => esc_html__('Twitter/X', 'easy-elements'),
	                        'instagram' => esc_html__('Instagram', 'easy-elements'),
	                        'linkedin' => esc_html__('LinkedIn', 'easy-elements'),
	                        'youtube' => esc_html__('YouTube', 'easy-elements'),
	                        'tiktok' => esc_html__('TikTok', 'easy-elements'),
	                        'pinterest' => esc_html__('Pinterest', 'easy-elements'),
	                        'whatsapp' => esc_html__('WhatsApp', 'easy-elements'),
	                        'telegram' => esc_html__('Telegram', 'easy-elements'),
	                        'snapchat' => esc_html__('Snapchat', 'easy-elements'),
	                        'reddit' => esc_html__('Reddit', 'easy-elements'),
	                        'discord' => esc_html__('Discord', 'easy-elements'),
	                        'spotify' => esc_html__('Spotify', 'easy-elements'),
	                        'email' => esc_html__('Email', 'easy-elements'),
	                        'copy' => esc_html__('Copy Link', 'easy-elements'),
	                    ],
	                ],
	                [
	                    'name' => 'custom_icon',
	                    'label' => esc_html__('Custom Icon', 'easy-elements'),
	                    'type' => Controls_Manager::ICONS,
	                    'default' => [
	                        'value' => '',
	                        'library' => '',
	                    ],
	                ],
	            ],
	            'default' => [
	                [
	                    'platform' => 'facebook',
	                ],
	                [
	                    'platform' => 'twitter',
	                ],
	                [
	                    'platform' => 'instagram',
	                ],
	                [
	                    'platform' => 'linkedin',
	                ],
	                [
	                    'platform' => 'youtube',
	                ],
	                [
	                    'platform' => 'whatsapp',
	                ],
	                [
	                    'platform' => 'telegram',
	                ],
	                [
	                    'platform' => 'copy',
	                ],
	            ],
	            'title_field' => '{{{ platform }}}',
	        ]
	    );

	    $this->add_control(
	        'layout',
	        [
	            'label' => esc_html__('Layout', 'easy-elements'),
	            'type' => Controls_Manager::SELECT,
	            'default' => 'horizontal',
	            'options' => [
	                'horizontal' => esc_html__('Horizontal', 'easy-elements'),
	                'vertical' => esc_html__('Vertical', 'easy-elements'),
	                'grid' => esc_html__('Grid', 'easy-elements'),
	            ],
	        ]
	    );

	    $this->add_control(
	        'open_new_tab',
	        [
	            'label' => esc_html__('Open in New Tab', 'easy-elements'),
	            'type' => Controls_Manager::SWITCHER,
	            'label_on' => esc_html__('Yes', 'easy-elements'),
	            'label_off' => esc_html__('No', 'easy-elements'),
	            'return_value' => 'yes',
	            'default' => 'yes',
	        ]
	    );

	    $this->end_controls_section();
	    
	    // Style Tab - Buttons
	    $this->start_controls_section(
	        'buttons_style_section',
	        [
	            'label' => esc_html__('Buttons Style', 'easy-elements'),
	            'tab' => Controls_Manager::TAB_STYLE,
	        ]
	    );

		$this->add_control(
	        'icon_size',
	        [
	            'label' => esc_html__('Icon Size', 'easy-elements'),
	            'type' => Controls_Manager::SLIDER,
	            'size_units' => ['px', 'em'],
	            'range' => [
	                'px' => [
	                    'min' => 10,
	                    'max' => 50,
	                    'step' => 1,
	                ],
	                'em' => [
	                    'min' => 0.5,
	                    'max' => 3,
	                    'step' => 0.1,
	                ],
	            ],
	            'default' => [
	                'unit' => 'px',
	                    'size' => 18,
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .eel-social-button i' => 'font-size: {{SIZE}}{{UNIT}};',
	                '{{WRAPPER}} .eel-social-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
	            ],
	        ]
	    );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'      => 'icon__bg',
				'label'     => esc_html__( 'Background', 'easy-elements' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .eel-social-share .eel-social-button',
				'separator' => 'before',
			]
		);


		$this->add_control(
	        'icon_color_cutom',
	        [
	            'label' => esc_html__('Color', 'easy-elements'),
	            'type' => Controls_Manager::COLOR,
	            'default' => '',
	            'selectors' => [
	                '{{WRAPPER}} .eel-social-share .eel-social-button i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eel-social-share .eel-social-button svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eel-social-share .eel-social-button svg path' => 'fill: {{VALUE}};',
	            ],
	        ]
	    );

	    $this->add_responsive_control(
	        'button_size',
	        [
	            'label' => esc_html__('Button Size', 'easy-elements'),
	            'type' => Controls_Manager::SLIDER,
	            'size_units' => ['px', 'em'],
	            'range' => [
	                'px' => [
	                    'min' => 30,
	                    'max' => 100,
	                    'step' => 1,
	                ],
	                'em' => [
	                    'min' => 2,
	                    'max' => 6,
	                    'step' => 0.1,
	                ],
	            ],
	            'default' => [
	                'unit' => 'px',
	                    'size' => 45,
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .eel-social-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
	            ],
	        ]
	    );

	    $this->add_responsive_control(
	        'button_spacing',
	        [
	            'label' => esc_html__('Button Spacing', 'easy-elements'),
	            'type' => Controls_Manager::SLIDER,
	            'size_units' => ['px', 'em'],
	            'range' => [
	                'px' => [
	                    'min' => 0,
	                    'max' => 50,
	                    'step' => 1,
	                ],
	                'em' => [
	                    'min' => 0,
	                    'max' => 3,
	                    'step' => 0.1,
	                ],
	            ],
	            'default' => [
	                'unit' => 'px',
	                    'size' => 0,
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .eel-social-button' => 'margin: 0 {{SIZE}}{{UNIT}};',
	                '{{WRAPPER}} .eel-social-button:first-child' => 'margin-left: 0;',
	                '{{WRAPPER}} .eel-social-button:last-child' => 'margin-right: 0;',
	            ],
	        ]
	    );

	    $this->add_control(
	        'button_border_radius',
	        [
	            'label' => esc_html__('Border Radius', 'easy-elements'),
	            'type' => Controls_Manager::DIMENSIONS,
	            'size_units' => ['px', '%'],
	            'default' => [
	                'top' => '50',
	                'right' => '50',
	                'bottom' => '50',
	                'left' => '50',
	                'unit' => '%',
	                'isLinked' => true,
	            ],
	            'selectors' => [
	                '{{WRAPPER}} .eel-social-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	            ],
	        ]
	    );

	    $this->add_group_control(
	        Group_Control_Border::get_type(),
	        [
	            'name' => 'button_border',
	            'selector' => '{{WRAPPER}} .eel-social-button',
	        ]
	    );

	    $this->add_group_control(
	        Group_Control_Box_Shadow::get_type(),
	        [
	            'name' => 'button_box_shadow',
	            'selector' => '{{WRAPPER}} .eel-social-button',
	        ]
	    );

	    $this->end_controls_section();
	}

	protected function render() {
	    $settings = $this->get_settings_for_display();
	    
	    if (empty($settings['social_platforms'])) {
	        return;
	    }

	    $current_url = get_permalink();
	    $current_title = get_the_title();
	    $current_description = get_the_excerpt() ?: get_bloginfo('description');
	    
	    // Get featured image if available
	    $featured_image = '';
	    if (has_post_thumbnail()) {
	        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
	    }

	    $layout_class = 'eel-social-layout-' . ($settings['layout'] ?? 'horizontal');
	    $target = ($settings['open_new_tab'] === 'yes') ? '_blank' : '_self';
	    
	    // Add Open Graph meta tags for better social sharing
	    if (!empty($featured_image)) {
	        echo '<meta property="og:image" content="' . esc_url($featured_image) . '">';
	        echo '<meta property="og:image:width" content="1200">';
	        echo '<meta property="og:image:height" content="630">';
	        echo '<meta name="twitter:image" content="' . esc_url($featured_image) . '">';
	        echo '<meta name="twitter:card" content="summary_large_image">';
	    }
	    echo '<meta property="og:title" content="' . esc_attr($current_title) . '">';
	    echo '<meta property="og:description" content="' . esc_attr($current_description) . '">';
	    echo '<meta property="og:url" content="' . esc_url($current_url) . '">';
	    echo '<meta name="twitter:title" content="' . esc_attr($current_title) . '">';
	    echo '<meta name="twitter:description" content="' . esc_attr($current_description) . '">';
	    
	    ?>
	    <div class="eel-social-share <?php echo esc_attr($layout_class); ?>">	
	        
	        <div class="eel-social-buttons">
	            <?php foreach ($settings['social_platforms'] as $platform): ?>
	                <?php
	                $platform_name = $platform['platform'] ?? '';
	                $share_url = $current_url;
	                $share_title = $current_title;
	                
	                $social_url = $this->get_social_share_url($platform_name, $share_url, $share_title, $current_description, $featured_image);
	                $icon_class = $this->get_social_icon_class($platform_name);
	                $button_class = 'eel-social-button eel-' . $platform_name;
	                
	                // Check for custom icon
	                $custom_icon = $platform['custom_icon'] ?? [];
	                $has_custom_icon = !empty($custom_icon['value']);
	                
	                if ($platform_name === 'copy') {
	                    $button_class .= ' eel-copy-link';
	                    $data_attr = 'data-url="' . esc_attr($share_url) . '"';
	                    $href = '#';
	                } else {
	                    $data_attr = '';
	                    $href = $social_url;
	                }
	                ?>
	                
	                <a href="<?php echo esc_url($href); ?>" 
	                   class="<?php echo esc_attr($button_class); ?>"
	                   target="<?php echo esc_attr($target); ?>"
	                   <?php echo esc_attr($data_attr); ?>
	                   title="<?php echo esc_attr(ucfirst($platform_name)); ?>">
	                    <?php if ($has_custom_icon): ?>
	                        <?php \Elementor\Icons_Manager::render_icon($custom_icon, ['aria-hidden' => 'true']); ?>
	                    <?php else: ?>
	                        <i class="<?php echo esc_attr($icon_class); ?>"></i>
	                    <?php endif; ?>
	                </a>
	            <?php endforeach; ?>
	        </div>
	    </div>

	    <script>
	    jQuery(document).ready(function($) {
	        // Copy link functionality
	        $('.eel-copy-link').on('click', function(e) {
	            e.preventDefault();
	            var url = $(this).data('url');
	            var tempInput = $('<input>');
	            $('body').append(tempInput);
	            tempInput.val(url).select();
	            document.execCommand('copy');
	            tempInput.remove();
	            
	            // Show success message
	            var button = $(this);
	            var originalIcon = button.find('i, svg').clone();
	            button.find('i, svg').remove();
	            button.append('<i class="fas fa-check"></i>');
	            button.addClass('eel-copied');
	            
	            setTimeout(function() {
	                button.find('i').remove();
	                button.append(originalIcon);
	                button.removeClass('eel-copied');
	            }, 2000);
	        });
	    });
	    </script>
	    <?php
	}

	private function get_social_share_url($platform, $url, $title, $description, $image = '') {
	    $encoded_url = urlencode($url);
	    $encoded_title = urlencode($title);
	    $encoded_description = urlencode($description);
	    $encoded_image = urlencode($image);
	    
	    switch ($platform) {
	        case 'facebook':
	            return "https://www.facebook.com/sharer/sharer.php?u={$encoded_url}";
	        case 'twitter':
	            return "https://twitter.com/intent/tweet?url={$encoded_url}&text={$encoded_title}";
	        case 'linkedin':
	            return "https://www.linkedin.com/sharing/share-offsite/?url={$encoded_url}";
	        case 'pinterest':
	            if (!empty($image)) {
	                return "https://pinterest.com/pin/create/button/?url={$encoded_url}&description={$encoded_title}&media={$encoded_image}";
	            } else {
	                return "https://pinterest.com/pin/create/button/?url={$encoded_url}&description={$encoded_title}";
	            }
	        case 'whatsapp':
	            return "https://api.whatsapp.com/send?text={$encoded_title}%20{$encoded_url}";
	        case 'telegram':
	            return "https://t.me/share/url?url={$encoded_url}&text={$encoded_title}";
	        case 'instagram':
	            return "https://www.instagram.com/";
	        case 'youtube':
	            return "https://www.youtube.com/";
	        case 'tiktok':
	            return "https://www.tiktok.com/";
	        case 'snapchat':
	            return "https://www.snapchat.com/";
	        case 'reddit':
	            return "https://www.reddit.com/submit?url={$encoded_url}&title={$encoded_title}";
	        case 'discord':
	            return "https://discord.com/";
	        case 'spotify':
	            return "https://open.spotify.com/";
	        case 'email':
	            return "mailto:?subject={$encoded_title}&body={$encoded_description}%20{$encoded_url}";
	        default:
	            return $url;
	    }
	}

	private function get_social_icon_class($platform) {
	    switch ($platform) {
	        case 'facebook':
	            return 'fab fa-facebook-f';
	        case 'twitter':
	            return 'fab fa-twitter';
	        case 'linkedin':
	            return 'fab fa-linkedin-in';
	        case 'pinterest':
	            return 'fab fa-pinterest-p';
	        case 'whatsapp':
	            return 'fab fa-whatsapp';
	        case 'telegram':
	            return 'fab fa-telegram-plane';
	        case 'instagram':
	            return 'fab fa-instagram';
	        case 'youtube':
	            return 'fab fa-youtube';
	        case 'tiktok':
	            return 'fab fa-tiktok';
	        case 'snapchat':
	            return 'fab fa-snapchat-ghost';
	        case 'reddit':
	            return 'fab fa-reddit-alien';
	        case 'discord':
	            return 'fab fa-discord';
	        case 'spotify':
	            return 'fab fa-spotify';
	        case 'email':
	            return 'fas fa-envelope';
	        case 'copy':
	            return 'fas fa-link';
	        default:
	            return 'fas fa-share';
	    }
	}
}
