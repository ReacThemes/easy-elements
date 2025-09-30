<?php
$tab_slug = isset($current_tab) ? $current_tab : 'widget';
$grouped_widgets = [];

// Group widgets
foreach ( $available_elements as $key => $widget ) {
    $group = isset($widget['group']) ? $widget['group'] : 'General Widgets';
    $grouped_widgets[$group][$key] = $widget;
}
?>

<div class="easy-widgets-wrapper">
    <?php foreach ( $grouped_widgets as $group_name => $widgets ) : 
        $group_slug = str_replace(' ', '-', strtolower($group_name));
        ?>
        <h2 class="easy-widget-group-title"><?php echo esc_html($group_name); ?></h2>
        <div class="easy-widgets-grid <?php echo esc_attr( $group_slug ); ?>">
            <?php foreach ( $widgets as $key => $widget ) : 
                $option_name = 'easy_element_' . $tab_slug . '_' . $key;
                $enabled = get_option($option_name, '1');

                $is_pro_enable = isset($widget['is_pro']) && $widget['is_pro'];
                $is_pro = $is_pro_enable && ! class_exists('Easy_Elements_Pro');
                $easyel_pro_attr_class = $is_pro ? ' easyel-pro-enable' : '';
                $pro_widget = $is_pro_enable ? 'easyel-pro-widget' : '';
            ?>
                <div class="easy-widget-item <?php echo esc_attr($easyel_pro_attr_class . ' ' . $pro_widget); ?>" data-widget-key="<?php echo esc_attr($key); ?>">
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
                                data-tab="<?php echo esc_attr($tab_slug); ?>"
                                value="1"
                                <?php checked( $enabled, '1' ); ?>
                                <?php disabled( $is_pro ); ?> />
                            <span class="slider"></span>
                        </label>
                        <span class="toggle-status"></span>
                        <?php if( $is_pro_enable ) : ?>
                            <div class="easyel-pro-badge">Pro</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>