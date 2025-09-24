<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<?php

	//foreach ( $settings['easy_testimonials'] as $item ) :
		

		// logo data
		$logo_data   = ! empty( $item['logo']['id'] ) ? wp_get_attachment_image_src( $item['logo']['id'], 'full' ) : '';
		$logo_alt    = ! empty( $item['logo']['id'] ) ? get_post_meta( $item['logo']['id'], '_wp_attachment_image_alt', true ) : '';
		$logo_title  = ! empty( $item['logo']['id'] ) ? get_the_title( $item['logo']['id'] ) : '';

		// image data
		$image_data  = ! empty( $item['image']['id'] ) ? wp_get_attachment_image_src( $item['image']['id'], 'full' ) : '';
		$alt         = ! empty( $item['image']['id'] ) ? get_post_meta( $item['image']['id'], '_wp_attachment_image_alt', true ) : '';
		$title       = ! empty( $item['image']['id'] ) ? get_the_title( $item['image']['id'] ) : '';
		?>
		
                    
                    <?php if ( ! empty( $logo_data ) ) : ?>
                        <div class="eel-company-logo">
                            <img src="<?php echo esc_url( $logo_data[0] ); ?>"
                                width="<?php echo esc_attr( $logo_data[1] ); ?>"
                                height="<?php echo esc_attr( $logo_data[2] ); ?>"
                                alt="<?php echo esc_attr( $logo_alt ); ?>"
                                title="<?php echo esc_attr( $logo_title ); ?>"
                                loading="lazy" decoding="async" fetchpriority="low">
                        </div>
                    <?php endif; ?>
        
                    <?php if ( ! empty( $item['description'] ) ) : ?>
                        <div class="eel-description"><?php echo esc_html( $item['description'] ); ?></div>
                    <?php endif; ?>
        
                    <div class="eel-author-wrap">
                        <?php if ( $settings['show_image'] === 'yes' && $image_data ) : ?>
                            <div class="eel-picture">
                                <img src="<?php echo esc_url( $image_data[0] ); ?>"
                                    width="<?php echo esc_attr( $image_data[1] ); ?>"
                                    height="<?php echo esc_attr( $image_data[2] ); ?>"
                                    alt="<?php echo esc_attr( $alt ); ?>"
                                    title="<?php echo esc_attr( $title ); ?>"
                                    loading="lazy" decoding="async" fetchpriority="low">
                            </div>
                        <?php endif; ?>
        
                        <div class="eel-author">
                            <?php if ( ! empty( $item['name'] ) ) : ?>
                                <div class="eel-name"><?php echo esc_html( $item['name'] ); ?></div>
                            <?php endif; ?>
        
                            <?php if ( ! empty( $item['designation'] ) ) : ?>
                                <em class="eel-designation"><?php echo esc_html( $item['designation'] ); ?></em>
                            <?php endif; ?>
                        </div>
        
                        <?php if ( ! empty( $item['rating'] ) ) : ?>
                            <div class="eel-rating" aria-label="Rating: <?php echo intval( $item['rating'] ); ?> out of 5">
                                <?php
                                $rating = intval( $item['rating'] );
                                for ( $j = 1; $j <= 5; $j++ ) {
                                    echo '<span class="star' . ( $j <= $rating ? ' filled' : '' ) . '">' . ( $j <= $rating ? '★' : '☆' ) . '</span>';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
            
	<?php //endforeach; ?>

    <?php if ( $i > 6 ) : ?>
       <div class="overlay"></div>
    <?php endif; ?>
</div>
