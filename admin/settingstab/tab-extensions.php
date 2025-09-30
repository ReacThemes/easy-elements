<?php
$tab_slug = 'extensions';
$extensions_settings = get_option('easy_element_' . $tab_slug, []);
$defaults = array_fill_keys(array_keys(easyel_get_extension_fields()), 0);
$extensions_settings = wp_parse_args($extensions_settings, $defaults);

$fields = easyel_get_extension_fields();

// Group fields
$grouped_fields = [];
foreach ($fields as $key => $data) {
    $group_name = $data['group'] ?? 'General';
    $grouped_fields[$group_name][$key] = $data;
}
?>

<div class="wrap easyel-extension-main-wrapper">
    <table class="form-table">
        <?php foreach($grouped_fields as $group_name => $group_fields) : ?>
            <tr valign="top" class="easyel-extension-heading-group">
                <th  style="padding-top:15px; text-align:left; font-weight:bold;"><?php echo esc_html($group_name); ?></th>
            </tr>
            <tr valign="top" class="easyel-extension-wrapper">
                <?php foreach($group_fields as $key => $data) : 
                    $is_pro_enable = $data['is_pro'];
                    $is_pro        = $is_pro_enable && ! class_exists('Easy_Elements_Pro');
                    $pro_class     = $is_pro ? ' easyel-pro-enable' : '';
                    $pro_widget    = $is_pro_enable ? ' easyel-pro-widget' : '';
                ?>
                    <td class="easyel-extension-item <?php echo esc_attr($pro_class . $pro_widget); ?>" style="padding-right:20px;">
                       
                        <div class="widget-header">
                            <span class="dashicons <?php echo esc_attr($data['icon']); ?>"></span>
                            <strong><?php echo esc_html($data['label']); ?></strong>
                        </div>

                        <p class="widget-demo">
                            <a href="<?php echo esc_url($data['demo_url']); ?>" target="_blank" rel="noopener noreferrer">
                                <?php esc_html_e('View Demo / docs', 'easy-elements'); ?>
                            </a>
                        </p>
                        <label class="easy-toggle-switch">
                            <input type="checkbox" 
                                   class="easyel-extension-toggle" 
                                   data-key="<?php echo esc_attr($key); ?>" 
                                   data-tab="<?php echo esc_attr($tab_slug); ?>" 
                                   value="1"
                                   <?php checked(1, $extensions_settings[$key]); ?>
                                   <?php disabled($is_pro, true); ?> />
                            <span class="slider round"></span>
                        </label>
                        <?php if ( $is_pro ) : ?>
                            <span class="easyel-pro-badge">Pro</span>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
