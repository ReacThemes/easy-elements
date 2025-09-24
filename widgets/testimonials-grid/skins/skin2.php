<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( $settings['avatar_image_top'] === 'yes' ) {
    $avatar_image_top = 'eel-avatar-image-top';
}
?>
<div class="ee--tstml-inner-wrap <?php echo esc_attr($skin); ?> <?php echo esc_attr($avatar_image_top); ?>">
    <?php if ( ! empty( $logo_data ) ) : ?>
        <div class="eel-company-logo">
            <img
                src="<?php echo esc_url( $logo_data[0] ); ?>"
                width="<?php echo esc_attr( $logo_data[1] ); ?>"
                height="<?php echo esc_attr( $logo_data[2] ); ?>"
                alt="<?php echo esc_attr( $logo_alt ); ?>"
                title="<?php echo esc_attr( $logo_title ); ?>"
                loading="lazy"
                decoding="async"
                fetchpriority="low"
            >
        </div>
    <?php endif; ?>

    <div class="eel-picture-des-wrap">
        <?php
        if ( $settings['show_image'] === 'yes' && $image_data ) : ?>
            <div class="eel-picture">
            <img
            src="<?php echo esc_url( $image_data[0] ); ?>"
            width="<?php echo esc_attr( $image_data[1] ); ?>"
            height="<?php echo esc_attr( $image_data[2] ); ?>"
            alt="<?php echo esc_attr( $alt ); ?>"
            title="<?php echo esc_attr( $title ); ?>"
            loading="lazy"
            decoding="async" fetchpriority="low">
        </div>
        <?php endif; ?>
        <div class="eel--description-wrap">
            <?php if ( ! empty( $item['description'] ) ) : ?>
                <div class="eel-description"><?php echo esc_html( $item['description'] ); ?></div>
            <?php endif; ?>
            <div class="eel-author-wrap">       
                <div class="eel-author">
                    <?php if ( ! empty( $item['name'] ) ) : ?>
                        <div class="eel-name"><?php echo esc_html( $item['name'] ); ?></div>
                    <?php endif; ?>

                    <?php if ( ! empty( $item['designation'] ) ) : ?>
                        <em class="eel-designation"><?php echo esc_html( $item['designation'] ); ?></em>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
