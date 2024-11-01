<?php
if ( isset( $_POST['save_options'] ) ) {
    update_option( 'dtjwpg_wp_fileedit_option', sanitize_text_field( $_POST['dtjwpg_wp_fileedit_option'] ) );
    update_option( 'dtjwpg_wp_debug_option', sanitize_text_field( $_POST['dtjwpg_wp_debug_option'] ) );

    update_option( 'dtjwpg_backend_token_option', sanitize_text_field( $_POST['dtjwpg_backend_token_option'] ) );
    update_option( 'dtjwpg_backend_redirect_option', sanitize_text_field( $_POST['dtjwpg_backend_redirect_option'] ) );

    echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
}
?>

<h2 style="font-size: 24px;"><?php _e( 'Security Settings', 'wp-guardian' ); ?></h2>

<form method="post">
    <h3><?php _e( 'WordPress Options', 'wp-guardian' ); ?></h3>
    <p><?php _e( 'WordPress is highly configurable, but there are some settings that cannot be changed without going into the code. Now you can manage these settings easily.', 'wp-guardian' ); ?></p>

    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="dtjwpg_wp_fileedit_option"><?php _e( 'Disable File Editor', 'wp-guardian' ); ?></label></th>
                <td>
                    <p><input type="checkbox" id="dtjwpg_wp_fileedit_option" class="dtjwpg_wp_fileedit_option" name="dtjwpg_wp_fileedit_option" <?php checked( 'on', (string) get_option( 'dtjwpg_wp_fileedit_option' ) ); ?>></p>
                    <p class="description" id="description-dtjwpg_wp_fileedit_option"><?php _e( 'Editing Core, plugin and theme files via the WordPress admin area is unnecessary and can be a security risk if your account is hacked. Turning this off ensures files aren&#39;t editable by site users.', 'wp-guardian' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="dtjwpg_wp_debug_option"><?php _e( 'Enable Debug Mode', 'wp-guardian' ); ?></label></th>
                <td>
                    <p><input type="checkbox" id="dtjwpg_wp_debug_option" class="dtjwpg_wp_debug_option" name="dtjwpg_wp_debug_option" <?php checked( 'on', (string) get_option( 'dtjwpg_wp_debug_option' ) ); ?>></p>
                    <p class="description" id="description-dtjwpg_wp_debug_option"><?php _e( 'You can enable <code>WP_DEBUG</code> mode by turning this setting on. <strong>Do not enable this on a production website unless you know what you&#39;re doing.</strong>', 'wp-guardian' ); ?></p>
                </td>
            </tr>
        </tbody>
    </table>

    <hr>

    <h3><?php _e( 'Dashboard Options', 'wp-guardian' ); ?></h3>
    <p><?php _e( 'By default the WordPress dashboard is easily accessible to anyone via <code>/wp-admin/</code> or <code>/wp-login.php</code>. Now you can add a token to those requests which will prevent people from reaching the login page.', 'wp-guardian' ); ?></p>

    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="dtjwpg_backend_token_option"><?php _e( 'Secret Login Token', 'wp-guardian' ); ?></label></th>
                <td>
                    <p><input type="text" id="dtjwpg_backend_token_option" class="dtjwpg_backend_token_option regular-text" name="dtjwpg_backend_token_option" value="<?php echo get_option( 'dtjwpg_backend_token_option' ); ?>"></p>
                    <p class="description" id="description-dtjwpg_backend_token_option"><?php _e( 'Enter a string of letters &amp; numbers that will act as a token to verify the request made to the login page. For example, if the path was set to <code>a1b2c3</code>, the login URL becomes <code>/wp-login.php?dtjwpg-token=a1b2c3</code>. <strong>Leave this field blank to disable this setting.</strong>', 'wp-guardian' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="dtjwpg_backend_redirect_option"><?php _e( 'Incorrect Token Redirect', 'wp-guardian' ); ?></label></th>
                <td>
                    <p><input type="text" id="dtjwpg_backend_redirect_option" class="dtjwpg_backend_redirect_option regular-text" name="dtjwpg_backend_redirect_option" value="<?php echo get_option( 'dtjwpg_backend_redirect_option' ); ?>"></p>
                    <p class="description" id="description-dtjwpg_backend_redirect_option"><?php _e( 'When an incorrect token is provided for the login page, the user will be redirected to the homepage by default. You can enter a slug for a different page if you&#39;d like to redirect them elsewhere.', 'wp-guardian' ); ?></p>
                </td>
            </tr>
        </tbody>
    </table>

    <hr>

    <p>
        <button type="submit" name="save_options" class="button button-primary"><?php _e( 'Save Settings', 'wp-guardian' ); ?></button>
    </p>
</form>
