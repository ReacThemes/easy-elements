<?php
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Responsive_Control;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();
class Easyel_Contact_Box__Widget extends \Elementor\Widget_Base {

    public function get_style_depends() {
        $handle = 'eel-contact-box-style';
        $css_path = plugin_dir_path( __FILE__ ) . 'css/contact-box.css';
        
        if ( get_option( 'easyel_elements_minify_css', '0' ) === '1' && class_exists( 'Easyel_Elements_CSS_Loader_Helper' ) ) {
            Easyel_Elements_CSS_Loader_Helper::easyel_elements_load_minified_inline_css( $handle, $css_path );
            return [ $handle ];
        }
        
        if ( ! wp_style_is( $handle, 'registered' ) && file_exists( $css_path ) ) {
            wp_register_style( $handle, plugins_url( 'css/contact-box.css', __FILE__ ), [], defined( 'WP_DEBUG' ) && WP_DEBUG ? filemtime( $css_path ) : '1.0.0' );
        }
        return [ $handle ];
    }

    public function get_name() {
        return 'eel-contact-box';
    }

    public function get_title() {
        return esc_html__( 'Easy Contact Box', 'easy-elements' );
    }

    public function get_icon() {
        return 'easy-elements-icon';
    }

    public function get_categories() {
        return [ 'easyelements_category' ];
    }

    public function get_keywords() {
        return [ 'contact' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            '_section_logo',
            [
                'label' => esc_html__( 'Contact Box Settings', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'info_type',
            [
                'label' => esc_html__('Select Info Type', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'address',
                'options' => [
                    'address' => esc_html__('Address', 'easy-elements'),
                    'phone'   => esc_html__('Phone', 'easy-elements'),
                    'email'   => esc_html__('Email', 'easy-elements'),
                    'hours'   => esc_html__('Hours', 'easy-elements'),
                    'website'   => esc_html__('Website', 'easy-elements'),
                ],
            ]
        );


        $this->add_control(
            'icon_box_off',
            [
                'label' => esc_html__('Icon Box Disable', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'easy-elements'),
                'label_off' => esc_html__('No', 'easy-elements'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'icon_box_off' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eel_title',
            [
                'label' => esc_html__( 'Label', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Headquarters', 'easy-elements' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_position',
            [
                'label' => esc_html__('Label Position', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => esc_html__('Top', 'easy-elements'),
                    'bottom' => esc_html__('Bottom', 'easy-elements'),
                ],
            ]
        );

        $this->add_control(
            'address',
            [
                'label' => esc_html__( 'Information', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Apt 1010 Business Avenue, Toronto, Canada', 'easy-elements' ),
                'condition' => [
                    'info_type' => 'address',
                ],
            ]
        );

        $this->add_control(
            'hours',
            [
                'label' => esc_html__( 'Hours', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Monday-Friday:8:30 AM - 5:30 PM', 'easy-elements' ),
                'condition' => [
                    'info_type' => 'hours',
                ],
            ]
        );

        $this->add_control(
            '_phone',
            [
                'label' => esc_html__( 'Phone Number 01', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '+99 254 36587', 'easy-elements' ),
                'condition' => [
                    'info_type' => 'phone',
                ],
            ]
        );

        $this->add_control(
            'phone2',
            [
                'label' => esc_html__( 'Phone Number 02', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '+99 254 36587', 'easy-elements' ),
                'condition' => [
                    'info_type' => 'phone',
                ],
            ]
        );

        $this->add_control(
            'email',
            [
                'label' => esc_html__( 'Email 01', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'example@gmail.com', 'easy-elements' ),
                'condition' => [
                    'info_type' => 'email',
                ],
            ]
        );

        $this->add_control(
            'email2',
            [
                'label' => esc_html__( 'Email 02', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'example2@gmail.com', 'easy-elements' ),
                'condition' => [
                    'info_type' => 'email',
                ],
            ]
        );

        $this->add_control(
            'website',
            [
                'label' => esc_html__( 'Website', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'https://example.com', 'easy-elements' ),
                'condition' => [
                    'info_type' => 'website',
                ],
            ]
        );

        $this->add_control(
            'website_link',
            [
                'label' => esc_html__('Website URL', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'show_external' => true,
                'condition' => [
                    'info_type' => 'website',
                ],
            ]
        );

        $this->add_control(
            'full_box_link',
            [
                'label' => esc_html__('Make Full Box Clickable', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'easy-elements'),
                'label_off' => esc_html__('No', 'easy-elements'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('This option will not work if you use a second phone or second email field.', 'easy-elements'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Custom Link', 'easy-elements'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'description' => esc_html__('Custom link to be used for full box or manual overrides.', 'easy-elements'),
                'condition' => [
                    'full_box_link' => 'yes',
                    'info_type' => ['address', 'hours'],
                ],
            ]
        );

        $this->add_control(
            'icon_direction',
            [
                'label' => esc_html__( 'Direction', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'top',
                'options' => [                    
                    'left' => [
                        'title' => esc_html__( 'Left', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => esc_html__( 'Top', 'easy-elements' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'easy-elements' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'condition' => [
                    'icon_box_off' => 'yes',
                ],
            ]
        );


        $this->add_responsive_control(
            '_text_align',
            [
                'label' => esc_html__( 'Alignment', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ee--contact-box' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Title HTML Tag', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p'   => 'p',
                ],
            ]
        );

        $this->end_controls_section();        

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--contact-box .eel-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ee--contact-box .eel-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .ee--contact-box .eel-icon svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__( 'Background', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ee--contact-box .eel-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'icon_typography',
                'label' => esc_html__( 'Icon Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .ee--contact-box .eel-icon',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .ee--contact-box .eel-icon',
            ]
        );

        $this->add_control(
            'icon__border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--contact-box .eel-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--contact-box .eel-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
         $this->add_responsive_control(
            'icon_width',
            [
                'label' => esc_html__('Icon Area Width', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--contact-box .eel-icon' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_height',
            [
                'label' => esc_html__('Icon Area Height', 'easy-elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--contact-box .eel-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__( 'Label', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contact-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => '_title_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .contact-box-title',
            ]
        );  

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'easy-elements' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ee--contact-box .contact-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );      

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__( 'Information', 'easy-elements' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contact-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'desc_hover_color',
            [
                'label' => esc_html__( 'Hover Color', 'easy-elements' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contact-box-description a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'box_desc_typography',
                'label' => esc_html__( 'Typography', 'easy-elements' ),
                'selector' => '{{WRAPPER}} .contact-box-description',
            ]
        );


        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $icon_direction = $settings['icon_direction'] ?? '';
        $full_box_link  = $settings['full_box_link'] ?? 'no';
        $type = $settings['info_type'] ?? '';

        $type_map = [
            'phone'   => ['_phone', 'phone2'],
            'email'   => ['email', 'email2'],
            'website' => ['website'],
            'hours'   => ['hours'],
            'address' => ['address'],
        ];

        $value = $second_value = '';
        if (isset($type_map[$type])) {
            $keys = $type_map[$type];
            $value = $settings[$keys[0]] ?? '';
            $second_value = isset($keys[1]) ? ($settings[$keys[1]] ?? '') : '';
        }

        $link = '';
        $target = $nofollow = '';

        if (in_array($type, ['phone', 'email', 'website']) && !empty($value)) {
            if ($type === 'phone') {
                $link = 'tel:' . $value;
            } elseif ($type === 'email') {
                $link = 'mailto:' . $value;
            } elseif ($type === 'website' && !empty($settings['website_link']['url'])) {
                $link = $settings['website_link']['url'];
                $target = !empty($settings['website_link']['is_external']) ? ' target="_blank"' : '';
                $nofollow = ' rel="noopener noreferrer"';
            }
        }

        if (empty($link) && !empty($settings['link']['url'])) {
            $link = $settings['link']['url'];
            $target = !empty($settings['link']['is_external']) ? ' target="_blank"' : '';
            $nofollow = !empty($settings['link']['nofollow']) ? ' rel="nofollow"' : '';
        }

        $title_tag = $settings['title_tag'] ?? 'h3';
        $title_position = $settings['title_position'] ?? 'top';

        if ($full_box_link === 'yes' && !empty($link)) :
            echo wp_kses_post( '<a href="' . esc_url($link) . '"' . $target . $nofollow . ' class="eel-full-link-wrap">');
        endif;
        ?>

        <div class="ee--contact-box <?php echo esc_attr($icon_direction); ?>">

            <?php if (!empty($settings['icon']) && ($settings['icon_box_off'] ?? '') === 'yes') : ?>
                <span class="eel-icon">
                    <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>

            <?php if ($icon_direction === 'left' || $icon_direction === 'right') : ?>
                <div class="eel-title-content-wrap">
            <?php endif; ?>

            <?php
            if ($title_position === 'top') {
                $this->render_title($settings['eel_title'] ?? '', $title_tag);
            }
            ?>

            <?php if (!empty($value)) : ?>
                <div class="contact-box-description">
                    <?php $this->render_contact_description($value, $second_value, $full_box_link, $type, $settings, $target, $nofollow); ?>
                </div>
            <?php endif; ?>

            <?php
            if ($title_position === 'bottom') {
                $this->render_title($settings['eel_title'] ?? '', $title_tag);
            }
            ?>

            <?php if ($icon_direction === 'left' || $icon_direction === 'right') : ?>
                </div>
            <?php endif; ?>

        </div>

        <?php
        if ($full_box_link === 'yes' && !empty($link)) :
            echo '</a>';
        endif;
    }

    protected function render_contact_description($value, $second_value, $full_box_link, $type, $settings, $target = '', $nofollow = '') {
        if ($full_box_link === 'yes') {
            echo esc_html($value);
            if (!empty($second_value)) {
                echo '<br>' . esc_html($second_value);
            }
            return;
        }

        switch ( $type ) {
            case 'phone':
                $this->render_phone_with_text( $value );
                if ( ! empty( $second_value ) ) {
                    echo '<br>';
                    $this->render_phone_with_text( $second_value );
                }
                break;
        
            case 'email':
                if ( is_email( $value ) ) {
                    $email1 = sprintf(
                        '<a href="mailto:%1$s">%2$s</a>',
                        antispambot( esc_attr( $value ) ),
                        esc_html( antispambot( $value ) )
                    );
                    echo wp_kses(
                        $email1,
                        [
                            'a' => [ 'href' => [] ],
                        ]
                    );
                }
        
                if ( ! empty( $second_value ) && is_email( $second_value ) ) {
                    $email2 = sprintf(
                        '<br><a href="mailto:%1$s">%2$s</a>',
                        antispambot( esc_attr( $second_value ) ),
                        esc_html( antispambot( $second_value ) )
                    );
                    echo wp_kses(
                        $email2,
                        [
                            'br' => [],
                            'a'  => [ 'href' => [] ],
                        ]
                    );
                }
                break;
        
            case 'website':
                if ( ! empty( $settings['website_link']['url'] ) ) {
                    $href     = esc_url( $settings['website_link']['url'] );
                    $target   = ! empty( $settings['website_link']['is_external'] ) ? ' target="_blank"' : '';
                    $rel_attr = ' rel="' . esc_attr(
                        ! empty( $settings['website_link']['nofollow'] ) ? 'nofollow noopener noreferrer' : 'noopener noreferrer'
                    ) . '"';
        
                    $link_html = sprintf(
                        '<a href="%1$s"%2$s%3$s>%4$s</a>',
                        $href,
                        $target,
                        $rel_attr,
                        esc_html( $value )
                    );
        
                    echo wp_kses(
                        $link_html,
                        [
                            'a' => [
                                'href'   => [],
                                'target' => [],
                                'rel'    => [],
                            ],
                        ]
                    );
                } else {
                    echo esc_html( $value );
                }
                break;
        
            default:
                echo wp_kses_post( $value );
                break;
        }               
    }

    protected function render_phone_with_text($value) {
        if (preg_match('/([+]?\d[\d\s\-().]{6,})/', $value, $matches, PREG_OFFSET_CAPTURE)) {
            $number = trim($matches[1][0]);
            $start = $matches[1][1];
            $end = $start + strlen($number);
            $before = substr($value, 0, $start);
            $after = substr($value, $end);
            echo esc_html($before);
            echo '<a href="tel:' . esc_attr(preg_replace('/\D+/', '', $number)) . '">' . esc_html($number) . '</a>';
            echo esc_html($after);
        } else {
            echo esc_html($value);
        }
    }

    protected function render_title($title, $tag) {
        if (!empty($title)) {
            printf('<%1$s class="contact-box-title">%2$s</%1$s>', esc_attr($tag), esc_html($title));
        }
    }

} ?>