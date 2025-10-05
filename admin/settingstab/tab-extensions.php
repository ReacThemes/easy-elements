<?php
$tab_slug = 'extensions';
$extensions_settings = get_option('easy_element_' . $tab_slug, []);
$defaults = array_fill_keys(array_keys(easyel_get_extension_fields()), 0);
$extensions_settings = wp_parse_args($extensions_settings, $defaults);

$fields = easyel_get_extension_fields();

// Group fields
$grouped_fields = [];
foreach ($fields as $key => $data) {
    $group_name = $data['group'] ?? 'General Extensions';
    $grouped_fields[$group_name][$key] = $data;
}
?>

<div class="wrap easyel-extension-main-wrapper">
    <div class="form-table easyel-extension">
        <h1 class="easyel-dashboard-heading"><?php esc_html_e('Extensions','easy-elements');?></h1>
        <?php foreach($grouped_fields as $group_name => $group_fields) : 
            $group_slug = str_replace(' ', '-', strtolower($group_name));
            $group_enabled = get_option('easy_element_group_' . $group_slug, 0);
            ?>
            <div class="easyel-extension-heading-group easyel-dflex easyel-justify-between easyel-align-center">
                <h2 class="easyel-extension-group-title"><?php echo esc_html( $group_name ); ?></h2>
                <label class="easyel-toggle-switch-extension">
                    <input type="checkbox" 
                           class="easyel-group-toggle" 
                           name ="easyel-toggle-switch-extension"
                           data-group="<?php echo esc_attr($group_slug); ?>" 
                           <?php checked(1, $group_enabled); ?> /> 
                    <span class="easyel-enable-all">Enable All</span>
                    <span class="slider"></span>
                </label>
                <input type="hidden" name="easy_element_group_<?php echo esc_attr($group_slug); ?>" 
                       class="easyel-group-hidden" 
                       value="<?php echo esc_attr($group_enabled); ?>" />
            </div>

            <div class="easyel-extension-wrapper" data-group="<?php echo esc_attr($group_slug); ?>">
                <?php foreach($group_fields as $key => $data) : 
                    $is_pro_enable = $data['is_pro'];
                    $is_pro        = $is_pro_enable && ! class_exists('Easy_Elements_Pro');
                    $pro_class     = $is_pro ? ' easyel-pro-enable' : '';
                    $pro_widget    = $is_pro_enable ? ' easyel-pro-widget' : '';
                ?>
                    <div class="easyel-extension-item easyel-widget-card easyel-dflex easyel-justify-between easyel-align-center <?php echo esc_attr( $pro_class . $pro_widget ); ?>" style="padding-right:20px;">
                        <div class="easyel-widget-card-content easyel-dflex easyel-align-center">
                             <div class="easyel-widget-icon">
                                <?php if( $is_pro_enable ) : ?>
                                    <div class="easyel-pro-badge">
                                        <i class="easyelIcon-crown"></i>
                                        <?php esc_html_e( 'Pro', 'easy-elements' )?>
                                    </div>
                                <?php endif; ?>
                                <i class="dashicons <?php echo esc_attr($data['icon']); ?>"></i>
                            </div>
                            <div class="easyel-widget-text">
                                <div class="widget-header">
                                    <strong><?php echo esc_html($data['label']); ?></strong>
                                </div>
                                <div class="easyel-demo-link easyel-dflex easyel-align-center">
                                    <a href="<?php echo esc_url($data['demo_url']); ?>" target="_blank" rel="noopener noreferrer">
                                        <i class="easyelIcon-monitor"></i>
                                        <?php esc_html_e('Demo', 'easy-elements'); ?>
                                    </a>
                                    <a href="<?php echo esc_url($data['demo_url']); ?>" target="_blank" rel="noopener noreferrer">
                                        <i class="easyelIcon-document"></i>
                                        <?php esc_html_e('Doc', 'easy-elements'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="widget-toggle easyel-widget-card-switcher">
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
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
