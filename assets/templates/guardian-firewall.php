<?php
$action_url = $_SERVER['REQUEST_URI'];

if ( isset( $_POST['set_exclusions'] ) ) {
    update_option( 'wp_guardian_firewall_enable', (int) sanitize_text_field( $_POST['wp_guardian_firewall_enable'] ) );

    update_option( 'wp_guardian_firewall_long_requests', (int) sanitize_text_field( $_POST['wp_guardian_firewall_long_requests'] ) );
    update_option( 'wp_guardian_firewall_xss', (int) sanitize_text_field( $_POST['wp_guardian_firewall_xss'] ) );
    update_option( 'wp_guardian_firewall_external_post', (int) sanitize_text_field( $_POST['wp_guardian_firewall_external_post'] ) );
    update_option( 'wp_guardian_firewall_brute_force', (int) sanitize_text_field( $_POST['wp_guardian_firewall_brute_force'] ) );
    update_option( 'wp_guardian_firewall_log', (int) sanitize_text_field( $_POST['wp_guardian_firewall_log'] ) );

    update_option( 'wp_guardian_firewall_log_size', (int) sanitize_text_field( $_POST['wp_guardian_firewall_log_size'] ) );
    update_option( 'wp_guardian_firewall_long_requests_length', (int) sanitize_text_field( $_POST['wp_guardian_firewall_long_requests_length'] ) );

    update_option( 'wp_guardian_firewall_redirect_page', sanitize_text_field( $_POST['wp_guardian_firewall_redirect_page'] ) );
    update_option( 'wp_guardian_firewall_redirect_custom', sanitize_url( $_POST['wp_guardian_firewall_redirect_custom'] ) );

    echo '<div class="updated notice is-dismissible"><p>Security Filters and Redirect page updated.</p></div>';
}
?> 

<h2 style="font-size: 24px;">Guardian Firewall</h2>

<p>This is a lightning fast firewall that automatically protects your WordPress site against malicious URL requests. No configuration necessary.</p>

<form action="<?php echo $action_url; ?>" method="post">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label>Guardian</label></th>
                <td>
                    <p>
                        <input type="checkbox" value="1" name="wp_guardian_firewall_enable" <?php checked( 1, (int) get_option( 'wp_guardian_firewall_enable' ) ); ?>> Enable <b>Guardian</b> firewall
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label>Guardian Behaviour</label></th>
                <td>
                    <p>
                        <input type="checkbox" value="1" name="wp_guardian_firewall_long_requests" <?php checked( 1, (int) get_option( 'wp_guardian_firewall_long_requests' ) ); ?>> Block long URL requests
                        <br>
                        <input type="checkbox" value="1" name="wp_guardian_firewall_xss" <?php checked( 1, (int) get_option( 'wp_guardian_firewall_xss' ) ); ?>> Block XSS in POST data
                        <br>
                        <input type="checkbox" value="1" name="wp_guardian_firewall_external_post" <?php checked( 1, (int) get_option( 'wp_guardian_firewall_external_post' ) ); ?>> Block external POST requests
                        <br>
                        <input type="checkbox" value="1" name="wp_guardian_firewall_brute_force" <?php checked( 1, (int) get_option( 'wp_guardian_firewall_brute_force' ) ); ?>> Block brute force requests
                        <br>
                        <input type="checkbox" value="1" name="wp_guardian_firewall_log" <?php checked( 1, (int) get_option( 'wp_guardian_firewall_log' ) ); ?>> Log blocked requests
                    </p>
                    <p>
                        <input type="number" value="<?php echo get_option( 'wp_guardian_firewall_long_requests_length' ); ?>" name="wp_guardian_firewall_long_requests_length" placeholder="2000" min="0" style="width: 200px;"> characters in URL to check for
                    </p>
                    <p>
                        <input type="number" value="<?php echo get_option( 'wp_guardian_firewall_log_size' ); ?>" name="wp_guardian_firewall_log_size" placeholder="10000" min="0" style="width: 200px;"> records to keep in the log
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label>Upon Detecting Attack</label></th>
                <td>
                    <p>
                        <input type="radio" name="wp_guardian_firewall_redirect_page" value="403" <?php checked( '403', (string) get_option( 'wp_guardian_firewall_redirect_page' ) ); ?>>
                        Show 403 error page (default, recommended)
                    </p>
                    <p>
                        <input type="radio" name="wp_guardian_firewall_redirect_page" value="homepage" <?php checked( 'homepage', (string) get_option( 'wp_guardian_firewall_redirect_page' ) ); ?>>
                        Redirect to homepage
                    </p>
                    <p>
                        <input type="radio" name="wp_guardian_firewall_redirect_page" value="custom" <?php checked( 'custom', (string) get_option( 'wp_guardian_firewall_redirect_page' ) ); ?>>
                        Redirect to custom page (set below)
                    </p>
                    <p>
                        <input type="url" value="<?php echo get_option( 'wp_guardian_firewall_redirect_custom' ); ?>" name="redirect_custom" class="regular-text" placeholder="https://">
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <p>
                        <input type="submit" name="set_exclusions" class="button button-primary" value="Save Changes">
                    </p>
                </th>
                <td></td>
        </tbody>
    </table>
</form>
