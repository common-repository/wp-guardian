<?php
$action_url = $_SERVER['REQUEST_URI'];

if ( isset( $_POST['save_settings'] ) ) {
    update_option( 'dtjwpg_core_uninstall_remember', (int) sanitize_text_field( $_POST['dtjwpg_core_uninstall_remember'] ) );

    echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
}
?> 

<h2 style="font-size: 24px;">Settings</h2>

<form action="<?php echo $action_url; ?>" method="post">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label>Data Retention</label></th>
                <td>
                    <p>
                        <input type="checkbox" value="1" name="dtjwpg_core_uninstall_remember" <?php checked( 1, (int) get_option( 'dtjwpg_core_uninstall_remember' ) ); ?>> Keep data on uninstall
                        <br><small>This setting allows you to choose whether the plugin data should be kept if you choose to uninstall the plugin.</small>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

    <hr>

    <p>
        <button type="submit" name="save_settings" class="button button-primary"><?php _e( 'Save Settings', 'wp-guardian' ); ?></button>
    </p>
</form>
