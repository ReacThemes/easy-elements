<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="ee--tstml-inner-wrap">
    <div class="eel-author-wrap">
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

        <div class="eel-author">
            <?php if ( ! empty( $item['name'] ) ) : ?>
                <div class="eel-name"><?php echo esc_html( $item['name'] ); ?></div>
            <?php endif; ?>

            <?php if ( ! empty( $item['designation'] ) ) : ?>
                <em class="eel-designation"><?php echo esc_html( $item['designation'] ); ?></em>
            <?php endif; ?>

            <?php if ( ! empty( $item['rating'] ) ) : ?>
                <div class="eel-rating" aria-label="Rating: <?php echo intval( $item['rating'] ); ?> out of 5">
                    <?php
                    $rating = intval( $item['rating'] );
                    for ( $i = 1; $i <= 5; $i++ ) {
                        echo '<span class="star' . ( $i <= $rating ? ' filled' : '' ) . '">' . ( $i <= $rating ? '★' : '☆' ) . '</span>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ( ! empty( $item['description'] ) ) : ?>
        <div class="eel-description"><?php echo esc_html( $item['description'] ); ?></div>
    <?php endif; ?>

    <?php if ( ! empty( $item['quote_icon']['value'] ) ) : ?>
        <div class="eel-quote" aria-hidden="true">
            <?php \Elementor\Icons_Manager::render_icon( $item['quote_icon'], [ 'aria-hidden' => 'true' ] ); ?>
        </div>
    <?php endif; ?>
</div>
