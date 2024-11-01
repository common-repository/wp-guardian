<?php
if ( isset( $_POST['save_options'] ) ) {
    update_option( 'dtjwpg_verify_option', sanitize_text_field( $_POST['dtjwpg_verify_option'] ) );
    update_option( 'wpguardian_brute_force', (int) sanitize_text_field( $_POST['wpguardian_brute_force'] ) );

    echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
}
?>

<h2 style="font-size: 24px;"><?php _e( 'Login Security Settings', 'wp-guardian' ); ?></h2>

<h3><?php _e( 'Two-Step Verification', 'wp-guardian' ); ?></h3>
<p><?php _e( 'Two-Step Verification gives you the option to add another step to the login process by requiring a code to be entered when a user wants to login.', 'wp-guardian' ); ?></p>

<form method="post">
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="dtjwpg_verify_option"><?php _e( 'Enable Two Step Verification', 'wp-guardian' ); ?></label></th>
                <td>
                    <p><input type="checkbox" id="dtjwpg_verify_option" class="dtjwpg_verify_option" name="dtjwpg_verify_option" <?php checked( 'on', (string) get_option( 'dtjwpg_verify_option' ) ); ?>></p>
                    <p class="description"><?php _e( 'Turning this setting on allows people to use Two Step Verification to secure their accounts requiring them to verify who they are with their password and a code that is sent to them. Users will need to choose a verification method first before this is activated but turning this option off will disable it for everyone.', 'wp-guardian' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="wpguardian_brute_force"><?php _e( 'Enable Brute Force Protection', 'wp-guardian' ); ?></label></th>
                <td>
                    <p><input type="checkbox" id="wpguardian_brute_force" value="1" class="wpguardian_brute_force" name="wpguardian_brute_force" <?php checked( 1, (int) get_option( 'wpguardian_brute_force' ) ); ?>></p>
                    <p class="description"><?php _e( 'Enable login (<code>wp-login.php</code>) brute force protection.', 'wp-guardian' ); ?></p>
                </td>
            </tr>
        </tbody>
    </table>

    <hr>

    <p>
        <button type="submit" name="save_options" class="button button-primary"><?php _e( 'Save Settings', 'wp-guardian' ); ?></button>
    </p>
</form>
