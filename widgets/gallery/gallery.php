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
                'label' => esc_html__( 'Enable Popup', 'easy-elements' ),
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

        $this->end_controls_section();

        // Layout Options
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'easy-elements' ),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
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

        $this->add_control(
            'image_gap',
            [
                'label' => esc_html__( 'Image Gap', 'easy-elements' ),
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
            $image_url  = Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'thumbnail', $settings ) ?: $image['url'];
            $full_image = wp_get_attachment_image_url( $image['id'], 'full' );
            $caption    = '';

            if ( $settings['show_caption'] === 'yes' ) {
                if ( $settings['caption_source'] === 'media' ) {
                    $caption = wp_get_attachment_caption( $image['id'] );
                } elseif ( $settings['caption_source'] === 'title' ) {
                    $caption = get_the_title( $image['id'] );
                }
            }

            echo '<div class="eel-gallery-item">';

            if ( $popup_enabled ) {
                echo '<a href="' . esc_url( $full_image ) . '" class="eel-popup-link" data-index="' . esc_attr( $index ) . '">';
            } else {
                echo '<a href="' . esc_url( $image['url'] ) . '" target="_blank" rel="noopener">';
            }

            echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_post_meta( $image['id'], '_wp_attachment_image_alt', true ) ) . '">';
            echo '</a>';

            if ( ! empty( $caption ) ) {
                echo '<div class="eel-gallery-caption">' . esc_html( $caption ) . '</div>';
            }

            echo '</div>';
        }

        echo '</div>';

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

}