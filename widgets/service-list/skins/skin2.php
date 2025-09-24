<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="eel-service-media">
    <div class="eel-icon-img-wrap <?php echo esc_attr($image_only); ?>">
        <?php if ( $settings['media_type'] === 'number' ) : ?>
            <span class="eel--number">
                <?php echo esc_html($settings['number']); ?>
            </span>
        <?php elseif ( $settings['media_type'] === 'icon' && ! empty( $settings['service_icon']['value'] ) ) : ?>
            <span class="eel-service-icon">
                <?php \Elementor\Icons_Manager::render_icon( $settings['service_icon'], ['aria-hidden' => 'true'] ); ?>
            </span>
        <?php elseif ( in_array( $settings['media_type'], ['image', 'image_only'] ) && ! empty( $settings['service_image']['url'] ) ) : ?>
            <?php
            if ( ! empty( $settings['service_image']['id'] ) ) {
                echo wp_get_attachment_image(
                    $settings['service_image']['id'],
                    'full',
                    false,
                    array(
                        'class' => 'eel-service-image',
                        'alt'   => esc_attr($title),
                    )
                );
            } else if ( ! empty( $settings['service_image']['url'] ) ) {
                // Use get_image_tag() for images without attachment ID
                echo wp_kses_post(
                    get_image_tag(
                        0,
                        esc_attr($title),
                        esc_attr($title),
                        'eel-service-image',
                        $settings['service_image']['url']
                    )
                );
            }
            ?>
        <?php endif; ?>
    </div>
    <<?php echo esc_attr($title_tag); ?> class="eel-service-title">
        <?php echo wp_kses_post($title); ?>
    </<?php echo esc_attr($title_tag); ?>>

    <?php if ( $link ) : ?>
        <?php if ( $settings['readmore_type'] === 'readmore' && $settings['readmore_text'] ) : ?>
        <a class="eel-readmore" href="<?php echo esc_url($link); ?>"<?php echo $is_external ? ' ' . esc_attr($is_external) : ''; ?><?php echo $nofollow ? ' ' . esc_attr($nofollow) : ''; ?>>
            <?php echo esc_html($settings['readmore_text']); ?> <i class="unicon-chevron-right"></i>
        </a>
        <?php else : ?>
            <a class="eel-readmore eel-only-icon" href="<?php echo esc_url($link); ?>"<?php echo $is_external ? ' ' . esc_attr($is_external) : ''; ?><?php echo $nofollow ? ' ' . esc_attr($nofollow) : ''; ?>>
            <span class="screen-reader-text"><?php echo esc_html__( 'Read more', 'easy-elements' ); ?></span>
            <span>
                <?php
                if ( ! empty( $settings['readmore_icon']['value'] ) ) {
                    \Elementor\Icons_Manager::render_icon( $settings['readmore_icon'], ['aria-hidden' => 'true'] );
                } else {
                    echo '<i class="unicon-arrow-right"></i>';
                }
                ?>
            </span> 
            </a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="eel-des">
    <?php echo wp_kses_post($desc); ?>
</div>     
