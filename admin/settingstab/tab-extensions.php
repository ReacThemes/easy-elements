<?php 
    $checked_animation = get_option('easyel_enable_js_animation', 0);
    $checked_cursor    = get_option('easyel_enable_cursor', 0);
    
    ?>
<div class="wrap">
    <h1><?php esc_html_e( 'All Extensions', 'easy-elements' ); ?></h1>
    <table class="form-table">
        <tr valign="top">
            <td>
                <?php esc_html_e( 'Enable Easy Animation', 'easy-elements' ); ?>
                <label class="easy-toggle-switch">
                    <input type="checkbox" id="easyel_enable_js_animation" value="1" <?php checked( 1, $checked_animation ); ?> />
                    <span class="slider round"></span>
                </label>
            </td>

            <td>
                <?php esc_html_e( 'Enable Easy Cursor', 'easy-elements' ); ?>
                <label class="easy-toggle-switch">
                    <input type="checkbox" id="easyel_enable_cursor" value="1" <?php checked( 1, $checked_cursor ); ?> />
                    <span class="slider round"></span>
                </label>
            </td>
        </tr>
    </table>
</div>