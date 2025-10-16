<div class="grid-item">
    <div class="ee--team-img d-flex">
        <?php if ( $action_type === 'link' && $link ) : ?>
            <a href="<?php echo esc_url( $link ); ?>"
            <?php if ( $target ) : ?>target="<?php echo esc_attr( $target ); ?>"<?php endif; ?>
            <?php if ( $nofollow ) : ?>rel="<?php echo esc_attr( $nofollow ); ?>"<?php endif; ?>>
        <?php elseif ( $action_type === 'popup' ) : ?>
            <a href="#<?php echo esc_attr($unique_id); ?>" class="eel-popup-trigger" data-popup-id="<?php echo esc_attr($unique_id); ?>">
        <?php endif; ?>   

        <?php if ( $image_data ) : ?>
            <div class="eel-team-img-area">
                <img class="eel-team-img"
                src="<?php echo esc_url( $image_data[0] ); ?>"
                width="<?php echo esc_attr( $image_data[1] ); ?>"
                height="<?php echo esc_attr( $image_data[2] ); ?>"
                alt="<?php echo esc_attr( $alt ); ?>"
                title="<?php echo esc_attr( $title ); ?>"
                loading="lazy"
                decoding="async" fetchpriority="<?php echo esc_attr( $fetchpriority ); ?>">
            </div>
            <div class="eel-image-overlay"></div>
        <?php endif; ?>   
        <div class="eel-name-deg-wrap <?php echo esc_attr( $settings['content_show'] )?>">
            <?php if ( ! empty( $name ) ) :
                    echo wp_kses_post( $name );
                endif; ?>
            <?php if ( ! empty( $designation ) ) : ?>
                <div class="eel-designation"><?php echo esc_html( $designation ); ?></div>
            <?php endif; ?>                   
        </div>
        <?php if ( ($action_type === 'link' && $link) || $action_type === 'popup' ) : ?>
            </a>
        <?php endif; ?>
        <?php if ( $action_type === 'popup' ) : ?>
            <div id="<?php echo esc_attr($unique_id); ?>" class="eel-popup-modal" style="display:none;">
                <div class="eel-popup-content">
                    <span class="eel-popup-close">&times;</span>
                    <div class="eel-popup-header">
                        <?php if ( ! empty( $name ) ) : ?>
                            <div class="eel-popup-name"><?php echo wp_kses_post( $name ); ?></div>
                        <?php endif; ?>
                        <?php if ( ! empty( $designation ) ) : ?>
                            <div class="eel-popup-designation"><?php echo esc_html( $designation ); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="eel-popup-details">
                        <?php if ( ! empty( $details ) ) : ?>
                            <?php echo nl2br( esc_html( $details ) ); ?>
                        <?php else : ?>
                            <p><?php esc_html_e( 'No additional details available.', 'easy-elements' ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>