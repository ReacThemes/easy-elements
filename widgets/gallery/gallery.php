<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Easyel__Gallery_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'eel-gallery';
    }

    public function get_title() {
        return esc_html__( 'Simple Gallery', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category_form' ];
    }

    public function get_keywords() {
        return [ 'gallery', 'image', 'photo', 'portfolio' ];
    }

    public function get_style_depends() {
        $handle = 'eel-gallery-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/gallery.css';	    
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style(
                $handle,
                plugins_url( 'css/gallery.css', __FILE__ ),
                [],
                defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0'
            );
        }
        return [ $handle ];
    }

    public function get_script_depends() {
        $handle = 'eel-simple-gallery-script';
        $js_path = plugin_dir_path( __FILE__ ) . 'js/simple-gallery.js';

        // Normal script loading
        if ( ! wp_script_is( $handle, 'registered' ) && file_exists( $js_path ) ) {
            wp_register_script( $handle, plugins_url( 'js/simple-gallery.js', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $js_path ) : '1.0.0' );
        }

        return [ $handle ];
    }

    protected function register_controls() {

        // Gallery Images
        $this->start_controls_section(
            'section_gallery',
            [
                'label' => esc_html__( 'Gallery Images', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'gallery_images',
            [
                'label' => esc_html__( 'Add Images', 'easy-elements' ),
                'type' => Controls_Manager::GALLERY,
                'default' => [],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => esc_html__( '1 Column', 'easy-elements' ),
                    '2' => esc_html__( '2 Columns', 'easy-elements' ),
                    '3' => esc_html__( '3 Columns', 'easy-elements' ),
                    '4' => esc_html__( '4 Columns', 'easy-elements' ),
                    '5' => esc_html__( '5 Columns', 'easy-elements' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'show_caption',
            [
                'label' => esc_html__( 'Show Caption', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'easy-elements' ),
                'label_off' => esc_html__( 'Hide', 'easy-elements' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'caption_source',
            [
                'label' => esc_html__( 'Caption Source', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'media',
                'options' => [
                    'media' => esc_html__( 'Media Library Caption', 'easy-elements' ),
                    'title' => esc_html__( 'Image Title', 'easy-elements' ),
                    'none' => esc_html__( 'None', 'easy-elements' ),
                ],
                'condition' => [
                    'show_caption' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_popup',
            [
                'label' => esc_html__( 'Enable Lightbox', 'easy-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'easy-elements' ),
                'label_off' => esc_html__( 'No', 'easy-elements' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => esc_html__( 'Order By', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'menu_order',
                'options' => [
                    'menu_order' => esc_html__( 'Default', 'easy-elements' ),
                    'title'      => esc_html__( 'Title', 'easy-elements' ),
                    'id'         => esc_html__( 'ID', 'easy-elements' ),
                    'date'       => esc_html__( 'Date', 'easy-elements' ),
                    'rand'       => esc_html__( 'Random', 'easy-elements' ),
                ],
            ]
        );

        $this->add_control(
            'hover_style',
            [
                'label' => esc_html__( 'On Hover', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__( 'Default', 'easy-elements' ),
                    'icon'      => esc_html__( 'Icon', 'easy-elements' ),
                    'text'         => esc_html__( 'Text', 'easy-elements' ),
                ],
            ]
        );

        $this->add_control(
            'hover_text',
            [
                'label' => esc_html__( 'Hover Text', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'View', 'easy-elements' ),
                'placeholder' => esc_html__( 'Enter hover text', 'easy-elements' ),
                'condition' => [
                    'hover_style' => 'text',
                ],
            ]
        );

        $this->add_control(
            'hover_icon',
            [
                'label' => esc_html__( 'Hover Icon', 'easy-elements' ),
                'type'  => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'hover_style' => 'icon',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__('Images', 'easy-elements'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_responsive_control(
            'image_gap',
            [
                'label' => esc_html__( 'Gap', 'easy-elements' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [ 'size' => 10 ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__( 'Height', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 1000, 'step' => 1 ],
                    '%'  => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-item img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover; width: 100%;',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => esc_html__( 'Border', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .eel-gallery-item img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-item img, {{WRAPPER}} .eel-gallery-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_padding',
            [
                'label' => esc_html__( 'Padding', 'easy-elements' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-item img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_caption_style',
            [
                'label' => esc_html__('Caption', 'easy-elements'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_caption' => 'yes',
                ],
            ]
        );

        // Caption Color
        $this->add_control(
            'caption_color',
            [
                'label' => esc_html__('Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Caption Background Color
        $this->add_control(
            'caption_bg_color',
            [
                'label' => esc_html__('Background Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-gallery-caption' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Caption Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'label' => esc_html__('Typography', 'easy-elements'),
                'selector' => '{{WRAPPER}} .eel-gallery-caption',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_hover_overlay_style',
            [
                'label' => esc_html__('Hover Overlay', 'easy-elements'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hover_style!' => 'default',
                ],
            ]
        );
        $this->add_control(
            'hover_overlay_color',
            [
                'label' => esc_html__( 'Hover Overlay Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.6)',
                'selectors' => [
                    '{{WRAPPER}} .eel-hover-content' => 'background-color: {{VALUE}};',
                ],                
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_hover_icon_style',
            [
                'label' => esc_html__('Hover Icon', 'easy-elements'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hover_style' => 'icon',
                ],
            ]
        );
        $this->add_control(
            'hover_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 100 ],
                ],
                'default' => [ 'size' => 16 ],
                'selectors' => [
                    '{{WRAPPER}} .eel-hover-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eel-hover-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eel-hover-icon i, {{WRAPPER}} .eel-hover-icon svg, {{WRAPPER}} .eel-hover-icon svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_hover_text_style',
            [
                'label' => esc_html__('Hover Text', 'easy-elements'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hover_style' => 'text',
                ],
            ]
        );

        // Color
        $this->add_control(
            'hover_text_color',
            [
                'label' => esc_html__('Color', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eel-hover-text span' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'hover_text_color_typography',
                'label' => esc_html__('Typography', 'easy-elements'),
                'selector' => '{{WRAPPER}} .eel-hover-text span',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $images = $settings['gallery_images'];
        
        if ( empty( $images ) ) {
            echo '<p>' . esc_html__( 'Please select images to display the gallery.', 'easy-elements' ) . '</p>';
            return;
        }

        // Order images
        $order_by = $settings['order_by'];
        if ( $order_by === 'rand' ) {
            shuffle( $images );
        } elseif ( $order_by !== 'menu_order' ) {
            usort( $images, function( $a, $b ) use ( $order_by ) {
                $a_post = get_post( $a['id'] );
                $b_post = get_post( $b['id'] );
                if ( ! $a_post || ! $b_post ) return 0;
                return strcmp( strtolower( $a_post->$order_by ), strtolower( $b_post->$order_by ) );
            });
        }

        $popup_enabled = isset( $settings['enable_popup'] ) && $settings['enable_popup'] === 'yes';
        $popup_class   = $popup_enabled ? 'eel-popup-enabled' : '';

        echo '<div class="eel-gallery-grid ' . esc_attr( $popup_class ) . '">';

        foreach ( $images as $index => $image ) {
            $image_url  = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'thumbnail', $settings ) ?: $image['url'];
            $full_image = wp_get_attachment_image_url( $image['id'], 'full' );
            $caption    = '';

            if ( isset($settings['show_caption']) && $settings['show_caption'] === 'yes' ) {
                if ( $settings['caption_source'] === 'media' ) {
                    $caption = wp_get_attachment_caption( $image['id'] );
                } elseif ( $settings['caption_source'] === 'title' ) {
                    $caption = get_the_title( $image['id'] );
                }
            }

            echo '<div class="eel-gallery-item">';

            if ( $popup_enabled ) {
                echo '<a href="' . esc_url( $full_image ) . '" class="eel-popup-link" data-index="' . esc_attr( $index ) . '" data-elementor-open-lightbox="no">';
            } else {
                echo '<a href="' . esc_url( $image['url'] ) . '" target="_blank" rel="noopener" data-elementor-open-lightbox="no">';
            }

            // === Image ===
            echo '<div class="eel-gallery-image-wrap">';
            echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_post_meta( $image['id'], '_wp_attachment_image_alt', true ) ) . '" data-elementor-open-lightbox="no">';

            // === Hover Content ===
            if ( $settings['hover_style'] === 'text' && ! empty( $settings['hover_text'] ) ) {
                echo '<div class="eel-hover-content eel-hover-text">';
                echo '<span>' . esc_html( $settings['hover_text'] ) . '</span>';
                echo '</div>';
            } elseif ( $settings['hover_style'] === 'icon' && ! empty( $settings['hover_icon']['value'] ) ) {
                echo '<div class="eel-hover-content eel-hover-icon">';
                \Elementor\Icons_Manager::render_icon( $settings['hover_icon'], [ 'aria-hidden' => 'true' ] );
                echo '</div>';
            }

            echo '</div>'; // .eel-gallery-image-wrap
            echo '</a>';

            // === Caption ===
            if ( ! empty( $caption ) ) {
                echo '<div class="eel-gallery-caption">' . esc_html( $caption ) . '</div>';
            }

            echo '</div>'; // .eel-gallery-item
        }

        echo '</div>'; // .eel-gallery-grid

        // === Lightbox ===
        if ( $popup_enabled ) :
            ?>
            <div class="eel-lightbox">
                <span class="eel-close">&times;</span>
                <img class="eel-lightbox-image" src="" alt="">
                <button class="eel-prev">&#10094;</button>
                <button class="eel-next">&#10095;</button>
            </div>
            <?php
        endif;
    }


} ?>