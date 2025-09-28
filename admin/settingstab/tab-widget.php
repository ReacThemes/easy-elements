<?php
// Grouping
$grouped_widgets = [];
foreach ( $available_elements as $key => $widget ) {
    $group = isset($widget['group']) ? $widget['group'] : 'General Widgets';
    $grouped_widgets[$group][$key] = $widget;
}
?>

<div class="easy-widgets-wrapper">
    <?php foreach ( $grouped_widgets as $group_name => $widgets ) : ?>
        <h2 class="easy-widget-group-title"><?php echo esc_html($group_name); ?></h2>
        <div class="easy-widgets-grid">
            <?php foreach ( $widgets as $key => $widget ) : 
                $enabled = get_option('easy_element_' . $key, '1');
                $is_pro_enable = isset( $widget['is_pro'] ) && $widget['is_pro'];
                $is_pro = $is_pro_enable && ! class_exists('Easy_Elements_Pro');
                $disabled_attr = $is_pro ? 'disabled="disabled"' : '';
                $checked = $enabled === '1' ? 'checked' : '';
            ?>
                <div class="easy-widget-item <?php echo $is_pro ? 'easyel-pro-enable' : ''; ?>" data-widget-key="<?php echo esc_attr($key); ?>">
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
                                <?php checked( $enabled, '1' ); ?>
                                <?php disabled( $is_pro ); ?> />
                            <span class="slider"></span>
                        </label>
                        <span class="toggle-status"></span>
                        <?php if( $is_pro ) : ?>
                            <div class="easyel-pro-badge">Pro</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>