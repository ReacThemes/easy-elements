 <!-- Widgets Grid -->
<div class="easy-widgets-grid">
    <?php
    $counter = 0;
    foreach ($available_elements as $key => $widget) {
        $enabled = get_option('easy_element_' . $key, '1');
        ?>
        <div class="easy-widget-item" data-widget-key="<?php echo esc_attr($key); ?>">
            <div class="widget-header">
                <span class="dashicons <?php echo esc_attr($widget['icon']); ?>"></span>
                <strong><?php echo esc_html($widget['title']); ?></strong>
            </div>
            
            <p class="widget-description"><?php echo esc_html($widget['description']); ?></p>
            
            <p class="widget-demo">
                <a href="<?php echo esc_url($widget['demo_url']); ?>" target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e('View Demo', 'easy-elements'); ?>
                </a>
            </p>
            
            <div class="widget-toggle">
                <label class="easy-toggle-switch">
                    <input type="checkbox" 
                        class="widget-toggle-checkbox" 
                        data-widget-key="<?php echo esc_attr($key); ?>"
                        value="1" 
                        <?php checked($enabled, '1'); ?> />
                    <span class="slider"></span>
                </label>
                <span class="toggle-status"></span>
            </div>
        </div>
        <?php
        $counter++;
    }
    ?>
</div>