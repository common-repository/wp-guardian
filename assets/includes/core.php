<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

function dtjwpg_plugin_assets() {
    wp_register_style( 'dtjwpg_admin_css', plugins_url( 'wp-guardian', 'wp-guardian' ) . '/assets/css/style.css', [], DTJWPG_VERSION );

    // DataTables
    wp_register_style( 'dtjwpg-datatables', plugins_url( 'wp-guardian', 'wp-guardian' ) . '/assets/js/datatables/datatables.min.css', [], '2.0.3' );
    wp_register_script( 'dtjwpg-datatables', plugins_url( 'wp-guardian', 'wp-guardian' ) . '/assets/js/datatables/datatables.min.js', [], '2.0.3', true );

    if ( is_user_logged_in() ) {
        wp_enqueue_style( 'dtjwpg_admin_css' );
    }
}

add_action( 'admin_enqueue_scripts', 'dtjwpg_plugin_assets', 10, 0 );
add_action( 'wp_enqueue_scripts', 'dtjwpg_plugin_assets', 10, 0 );



/**
 * Add the plugin settings link to the plugins.php list.
 *
 * @since 1.0
 * @return string $links Returns the list of plugin links.
 */
function wp_guardian_add_plugin_links( $links, $file ) {
    if ( (string) $file !== (string) DTJWPG_BASENAME ) {
        return $links;
    }

    $dtjwpg_settings_link = '<a href="' . menu_page_url( 'dtjwpg_settings', false ) . '">' . __( 'Settings', 'wp-guardian' ) . '</a>';

    array_unshift( $links, $dtjwpg_settings_link );

    return $links;
}

add_filter( 'plugin_action_links', 'wp_guardian_add_plugin_links', 10, 2 );



/**
 * Adds the plugin pages to the admin menu.
 *
 * @since 1.0
 * @return void
 */
function dtjwpg_admin_pages() {
    add_menu_page( __( 'Guardian', 'wp-guardian' ), __( 'Guardian', 'wp-guardian' ), 'manage_options', 'dtjwpg_guardian', false, 'dashicons-shield' );

    add_submenu_page( 'dtjwpg_guardian', __( 'Security', 'wp-guardian' ), __( 'Security', 'wp-guardian' ), 'manage_options', 'dtjwpg_guardian', 'dtjwpg_guardian_template' );
}

add_action( 'admin_menu', 'dtjwpg_admin_pages', 10 );



/**
 * Function callbacks for page specific templates.
 *
 * @since 1.0
 * @return mixed Returns a HTML template.
 */
function dtjwpg_guardian_template() {
    require_once DTJWPG_TEMPLATES . 'guardian.php';
}



/**
 * Secure POST Requests.
 *
 * Restricts POST requests to only be allowed from the site itself.
 */
function wp_guardian_secure_post_requests() {
    if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
        if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
            $remote_addr = $_SERVER['REMOTE_ADDR'];

            // Perform reverse DNS lookup to get hostname
            $host = gethostbyaddr( $remote_addr );

            // Check if the result is not empty and is not equal to the IP address itself
            if ( ! empty( $host ) && $host !== $remote_addr ) {
                $site_url = get_bloginfo( 'url' );

                // Parse the site URL to extract only the domain name
                $parsed_site_url = parse_url( $site_url );
                $site_domain     = isset( $parsed_site_url['host'] ) ? $parsed_site_url['host'] : '';

                // Check if the hostname matches the site domain
                if ( $site_domain !== $host ) {
                    //
                    $db_path = sprintf( '%s/wp-guardian-gatekeeper-%s.sqlite', WP_CONTENT_DIR, hash( 'adler32', sprintf( '%s|%s|%s', AUTH_KEY, AUTH_COOKIE, AUTH_SALT ) ) );

                    if ( (string) get_option( 'wp_guardian_gatekeeper_db_path' ) !== '' ) {
                        update_option( 'wp_guardian_gatekeeper_db_path', $db_path );
                    }

                    try {
                        // Create (connect to) SQLite database in file
                        $pdo = new PDO( 'sqlite:' . $db_path );
                        // Set errormode to exceptions
                        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

                        // Enable Write-Ahead Logging for better concurrency
                        $pdo->exec( 'PRAGMA journal_mode=WAL;' );
                        // Adjust synchronous mode to NORMAL for faster writes at slight risk of data loss
                        $pdo->exec( 'PRAGMA synchronous=NORMAL;' );
                        // Increase cache size for better performance
                        $pdo->exec( 'PRAGMA cache_size=-32000;' ); // Example: 32 MB cache

                        // Create table if it does not exist
                        $pdo->exec(
                            "CREATE TABLE IF NOT EXISTS logs (
                                id INTEGER PRIMARY KEY,
                                date TEXT,
                                request_uri TEXT,
                                query_string TEXT,
                                user_agent TEXT,
                                referrer TEXT
                            )"
                        );

                        // Prepare the insert statement
                        $stmt = $pdo->prepare( "INSERT INTO logs (date, request_uri, query_string) VALUES (?, ?, ?)" );

                        // Bind and insert the data
                        $stmt->execute(
                            [
                                date_i18n( 'Y-m-d H:i:s', time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
                                $host,
                                $host,
                            ]
                        );
                    } catch ( PDOException $e ) {
                        // Handle SQLite exception
                        // echo "SQLite error: " . $e->getMessage();
                    }
                    //

                    status_header( 403 );
                    exit;
                }
            } else {
                // Reverse DNS lookup failed
            }
        }
    }
}

add_action( 'parse_request', 'wp_guardian_secure_post_requests', 1 );
