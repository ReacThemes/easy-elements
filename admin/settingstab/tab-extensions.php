<?php
$tab_slug = 'extensions';
$extensions_settings = get_option('easy_element_' . $tab_slug, []);

// Default values
$defaults = [
    'enable_js_animation' => 0,
    'enable_cursor'       => 0,
    'enable_wrapper_link' => 0,
];

$extensions_settings = wp_parse_args( $extensions_settings, $defaults );

$fields = [
    'enable_js_animation' => [
        'label'   => __('Enable Easy Animation', 'easy-elements'),
        'is_pro'  => true,
    ],
    'enable_cursor' => [
        'label'   => __('Enable Easy Cursor', 'easy-elements'),
        'is_pro'  => true,
    ],
    'enable_wrapper_link' => [
        'label'   => __('Enable Wrapper Link', 'easy-elements'),
        'is_pro'  => false, // free
    ],
];
?>

<div class="wrap">
    <table class="form-table">
        <tr valign="top">
            <?php foreach($fields as $key => $data) : 
                $is_pro_enable = $data['is_pro'];
                $is_pro        = $is_pro_enable && ! class_exists('Easy_Elements_Pro');
                $pro_class     = $is_pro ? ' easyel-pro-enable' : '';
                $pro_widget    = $is_pro_enable ? ' easyel-pro-widget' : '';
            ?>
                <td class="easyel-extension-item <?php echo esc_attr($pro_class . $pro_widget); ?>">
                    <?php echo esc_html($data['label']); ?>
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
    </table>
</div>
