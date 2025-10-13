<?php
use Elementor\Controls_Manager;
defined( 'ABSPATH' ) || die();
class Easyel_Image_Reveal__Widget extends \Elementor\Widget_Base {
   
    public function get_style_depends() {
        $handle = 'eel-img-reveal-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/reveal.css';
        
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/reveal.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }
        return [ $handle ];
    }

    public function get_name() {
        return 'eel-image-reveal';
    }

    public function get_icon() {
		return 'easy-elements-icon';
	}

    public function get_title() {
        return esc_html__( 'Image Reveal', 'easy-elements' );
    }

    public function get_categories() {
        return [ 'pielements_category' ];
    }

    public function get_keywords() {
        return [ 'box', 'image', 'icon', 'icon-box', 'text' ];
    }
    
	protected function register_controls() {

		$this->start_controls_section(
            'image_section',
            [
                'label' => esc_html__( 'Image', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        ); 

        $this->add_control(
            'image',
            [
                'label' => esc_html__( 'Choose Image', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::MEDIA,     
            ]
        ); 

        $this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', 
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'large',
			]
		);
        
        $this->add_control(
            'reveal_direction',
            [
                'label'   => esc_html__( 'Reveal Direction', 'easy-elements' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left'   => esc_html__( 'Left', 'easy-elements' ),
                    'right'  => esc_html__( 'Right', 'easy-elements' ),
                    'top'    => esc_html__( 'Top', 'easy-elements' ),
                    'bottom' => esc_html__( 'Bottom', 'easy-elements' ),
                ],
            ]
        );

         $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'easy-elements' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'easy-elements' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'toggle' => true,
            ]
        );

        $this->add_control(
            'custom_url',
            [
                'label' => esc_html__( 'Custom Image URL', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'Type or paste your URL', 'easy-elements' ),
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'image_style_section',
            [
                'label' => esc_html__( 'Style', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );        

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__( 'Width', 'easy-elements' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
                'selectors' => [
					'{{WRAPPER}} .eel-reveal-img-main img' => 'width: {{SIZE}}{{UNIT}};',                     
				],                  
            ]
        ); 

        $this->add_responsive_control(
            'image_max_width',
            [
                'label' => esc_html__( 'Max Width', 'easy-elements' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],              
                'selectors' => [
                    '{{WRAPPER}} .eel-reveal-img-main img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__( 'Height', 'easy-elements' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
                'selectors' => [
					'{{WRAPPER}} .eel-reveal-img-main img' => 'height: {{SIZE}}{{UNIT}};',                      
				],                  
            ]
        );  
       
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'image_border',
                'label'    => esc_html__( 'Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-reveal-img-main img',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ], 
                'selectors' => [
					'{{WRAPPER}} .eel-reveal-img-main img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],               
            ]
        ); 

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-reveal-img-main img',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {

        $settings   = $this->get_settings_for_display(); 
        $image      = ! empty( $settings['image']['url'] ) ? $settings['image']['url'] : '';
        $img_alt    = ! empty( $settings['image']['alt'] ) ? $settings['image']['alt'] : ''; 
        $custom_url = ! empty( $settings['custom_url']['url'] ) ? $settings['custom_url']['url'] : '';
        $position   = ! empty( $settings['alignment'] ) ? $settings['alignment'] : '';
        $direction  = ! empty( $settings['reveal_direction'] ) ? $settings['reveal_direction'] : 'left';

        if ( ! empty( $settings['image']['id'] ) ) :

            // Get image HTML
            $image_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html(
                $settings,
                'thumbnail',
                'image'
            );

            // Ensure $custom_url is a string
            $custom_url = ! empty( $custom_url ) ? (string) $custom_url : '';

            ?>
            <div class="eel-img-reveal-wrap" data-reveal>
                <div class="eel-reveal-img-main eel-reveal-direction-<?php echo esc_attr($direction); ?> eel-reveal-<?php echo esc_attr($position); ?>">

                    <?php if ( $custom_url !== '' ) : ?>
                        <a href="<?php echo esc_url( $custom_url ); ?>">
                            <?php echo str_replace('<img ', '<img loading="lazy" ', $image_html); ?>
                        </a>
                    <?php else : ?>
                        <?php echo str_replace('<img ', '<img loading="lazy" ', $image_html); ?>
                    <?php endif; ?>

                </div>
            </div>
        <?php
        endif;
    }
}
