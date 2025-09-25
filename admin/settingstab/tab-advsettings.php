<?php 

$minify_css = get_option('easyel_elements_minify_css', '0');
$minify_js = get_option('easyel_elements_minify_js', '0');
        ?>
<div class="wrap">
    <h3><?php echo esc_html__( 'Advance Settings', 'easy-elements' ); ?></h3>
    <table class="form-table">
        <tr>
            <th scope="row"><?php echo esc_html__('Minify All CSS', 'easy-elements'); ?>
            <p class="description"><?php echo esc_html__('Enable to minify all plugin CSS output on frontend.', 'easy-elements'); ?></p></th>
            <td>
                <label class="easy-toggle-switch">
                    <input type="checkbox" id="easyel_elements_minify_css" value="1" <?php checked($minify_css, '1'); ?> />
                    <span class="slider"></span>
                </label>
                
            </td>
        </tr>
        <tr>
            <th scope="row"><?php echo esc_html__('Minify All JS', 'easy-elements'); ?>
            <p class="description"><?php echo esc_html__('Enable to minify all plugin JS output on frontend.', 'easy-elements'); ?></p></th>
            <td>
                <label class="easy-toggle-switch">
                    <input type="checkbox" id="easyel_elements_minify_js" value="1" <?php checked($minify_js, '1'); ?> />
                    <span class="slider"></span>
                </label>
                
            </td>
        </tr>
    </table>
</div>