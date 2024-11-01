<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

$tab     = ( filter_has_var( INPUT_GET, 'tab' ) ) ? filter_input( INPUT_GET, 'tab' ) : 'dashboard';
$section = 'admin.php?page=dtjwpg_guardian&amp;tab=';
?>

<div class="dtjwpg">
    <div class="dtjwpg-wrap wrap">
        <h1>
            <?php _e( 'WP Guardian', 'wp-guardian' ); ?>
        </h1>

        <h2 class="nav-tab-wrapper nav-tab-wrapper-wppd">
            <a href="<?php echo $section; ?>dashboard" class="nav-tab <?php echo $tab === 'dashboard' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
            <a href="<?php echo $section; ?>guardian" class="nav-tab <?php echo $tab === 'guardian' ? 'nav-tab-active' : ''; ?>">Guardian</a>
            <a href="<?php echo $section; ?>security" class="nav-tab <?php echo $tab === 'security' ? 'nav-tab-active' : ''; ?>">Security</a>
            <a href="<?php echo $section; ?>login" class="nav-tab <?php echo $tab === 'login' ? 'nav-tab-active' : ''; ?>">Login Security</a>
            <a href="<?php echo $section; ?>settings" class="nav-tab <?php echo $tab === 'settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
        </h2>

        <?php
        if ( $tab === 'dashboard' ) {
            wp_enqueue_style( 'dtjwpg-datatables' );
            wp_enqueue_script( 'dtjwpg-datatables' );

            // Clean up
            delete_option( '_login_attempts' );

            $users = get_users();

            foreach ( $users as $user ) {
                delete_user_meta( $user->ID, '_login_attempts' );
            }

            global $wpdb;

            $dtjwpg_sql_drop_logins   = 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'dtjwpg_logins';
            $dtjwpg_sql_drop_lockouts = 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'dtjwpg_lockouts';

            // Run the database queries
            $wpdb->query( $dtjwpg_sql_drop_logins );
            $wpdb->query( $dtjwpg_sql_drop_lockouts );
            //

            // Build Guardian progress bar score
            $progress_guardian  = (int) get_option( 'wp_guardian_firewall_enable' ) === 1 ? 50 : 0;
            $progress_guardian += (int) get_option( 'wp_guardian_firewall_long_requests' ) === 1 ? 10 : 0;
            $progress_guardian += (int) get_option( 'wp_guardian_firewall_xss' ) === 1 ? 5 : 0;
            $progress_guardian += (int) get_option( 'wp_guardian_firewall_external_post' ) === 1 ? 5 : 0;
            $progress_guardian += (string) get_option( 'wp_guardian_firewall_redirect_page' ) === '403' ? 10 : 0;

            if ( in_array( 'lighthouse/lighthouse.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                $progress_guardian += 20;
            }

            // Build Guardian progress bar color
            if ( $progress_guardian >= 0 && $progress_guardian <= 30 ) {
                $colour_guardian = '#c0392b'; // Red
            } elseif ( $progress_guardian >= 31 && $progress_guardian <= 60 ) {
                $colour_guardian = '#ff793f'; // Orange
            } elseif ( $progress_guardian >= 61 && $progress_guardian <= 80 ) {
                $colour_guardian = '#badc58'; // Yellow
            } elseif ( $progress_guardian >= 81 && $progress_guardian <= 100 ) {
                $colour_guardian = '#6ab04c'; // Green
            } else {
                $colour_guardian = 'grey';
            }
            ?>

            <div class="lhf--grid lhf--grid-4">
                <div class="lhf--grid-item" style="text-align:center">
                    <div class="lhf--circular-progress">
                        <svg xmlns='https://www.w3.org/2000/svg' viewBox='0 0 100 100' aria-labelledby='title' role='graphic'>
                            <title id='title'>svg circular progress bar</title>
                            <circle cx="50" cy="50" r="40"></circle>
                            <circle cx="50" cy="50" r="40" id='pct-ind'></circle>
                        </svg>
                        <p class="pct"><?php echo $progress_guardian; ?>%</p>
                    </div>
                    <input type="hidden" class="custom-range" id='slider' value='<?php echo $progress_guardian; ?>'>
                    <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        const slider = document.querySelector('#slider');
                        const pct = document.querySelector('.pct');
                        const pctIndicator = document.querySelector('#pct-ind');

                        pct.textContent = `${slider.value}%`;

                        // percent for dashoffset
                        const p = ( 1 - slider.value / 100 ) * (2 * (22 / 7) * 40);
                        pctIndicator.style = `stroke: <?php echo $colour_guardian; ?>; stroke-dashoffset: ${p};`;
                    });
                    </script>
                    <span class="lhf--metric-name" style="justify-content:center">Guardian</span>
                    <p>Firewall protection from known and emerging threats</p>
                    <a href="<?php echo $section; ?>guardian" class="button button-secondary">Manage Guardian</a>
                    <a href="https://www.buymeacoffee.com/wolffe" class="button button-secondary" target="_blank">☕</a>
                </div>
                <div class="lhf--grid-item">
                    <span class="lhf--metric-name">Guardian Protection</span>
                    <p>
                        <span class="lhf-sf-metric-value"><?php echo number_format( get_option( 'wp_guardian_block_count', 0 ) ); ?></span>
                    </p>
                    <p><?php printf( __( 'You are currently using <b>Version %s</b> of the plugin.', 'wp-guardian' ), DTJWPG_VERSION ); ?></p>
                    <p>
                        Total malicious requests blocked on your site.
                        <br><small style="color:var(--color-grey)">This is a lightning fast firewall that automatically protects your WordPress site against malicious URL requests.</small>
                    </p>

                    <?php
                    $database_path = get_option( 'wp_guardian_gatekeeper_db_path' );

                    if ( file_exists( $database_path ) ) {
                        $file_size_bytes    = filesize( $database_path );
                        $file_size_mb       = round( $file_size_bytes / 1048576, 2 );
                        $last_modified_time = filemtime( $database_path );

                        $formatted_last_modified_time = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $last_modified_time );

                        echo "Database size: " . $file_size_mb . " MB<br>";
                        echo "Last modified: " . $formatted_last_modified_time;
                    }
                    ?>

                </div>
                <div class="lhf--grid-item" style="background-color:#686de0;color:#ffffff">
                    <span class="lhf--metric-name">Get Lighthouse Protection!</span>

                    <?php if ( in_array( 'lighthouse/lighthouse.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
                        <p>
                            <span class="module-status module-status--pro">Pro</span>
                            <span class="module-status module-status--plugin">Plugin</span>
                            <span class="module-status module-active">Active</span>
                        </p>
                        <p>Lighthouse enhances performance, security, and page speed, along with providing real-time firewall protection and minimize spam registrations and malware attacks.</p>
                        <a href="<?php echo admin_url( 'admin.php?page=lighthouse' ); ?>" class="button button-secondary">Manage Lighthouse</a>
                    <?php } else { ?>
                        <p style="font-size:18px">Need more security and a faster website? Get Lighthouse today!</p>

                        <p>
                            <span class="module-status module-status--pro">Pro</span>
                            <span class="module-status module-status--new">Recommended</span>
                            <span class="module-status module-inactive">Inactive</span>
                        </p>
                        <p>Lighthouse enhances performance, security, and page speed, along with providing real-time firewall protection and minimize spam registrations and malware attacks.</p>
                        <a href="https://getbutterfly.com/wordpress-plugins/lighthouse/" class="button button-secondary">Get Lighthouse</a>
                    <?php } ?>
                </div>
                <div class="lhf--grid-item" style="background-color:#4834d4;color:#ffffff">
                    <span class="lhf--metric-name">Get Active Analytics!</span>

                    <?php if ( in_array( 'active-analytics/active-analytics.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
                        <p>
                            <span class="module-status module-status--pro">Pro</span>
                            <span class="module-status module-status--plugin">Plugin</span>
                            <span class="module-status module-active">Active</span>
                        </p>
                        <p>Active Analytics is a lightweight, privacy-enhanced WordPress analytics plugin for tracking what really matters.</p>
                        <a href="<?php echo admin_url( 'options-general.php?page=wpaa' ); ?>" class="button button-secondary">Manage Active Analytics</a>
                    <?php } else { ?>
                        <p style="font-size:18px">Need more user visibility and insights? Get Active Analytics today!</p>

                        <p>
                            <span class="module-status module-status--pro">Pro</span>
                            <span class="module-status module-status--new">Recommended</span>
                            <span class="module-status module-inactive">Inactive</span>
                        </p>
                        <p>Active Analytics is a lightweight, privacy-enhanced WordPress analytics plugin for tracking what really matters.</p>
                        <a href="https://getbutterfly.com/wordpress-plugins/active-analytics/" class="button button-secondary">Get Active Analytics</a>
                    <?php } ?>
                </div>
            </div>

            <div class="lhf--grid lhf--grid-1">
                <div class="lhf--grid-item">
                    <h3><?php _e( 'Latest Potential Threats', 'wp-guardian' ); ?></h3>

                    <?php
                    // Define the database path in the wp-content directory
                    $db_path = sprintf(
                        '%s/wp-guardian-gatekeeper-%s.sqlite',
                        WP_CONTENT_DIR,
                        hash('adler32', sprintf('%s|%s|%s', AUTH_KEY, AUTH_COOKIE, AUTH_SALT))
                    );

                    // Check if the database path option is empty or if the file does not exist
                    if ( !file_exists( $db_path ) || (string) get_option('wp_guardian_gatekeeper_db_path') === '' ) {
                        // Update the option with the database path
                        update_option('wp_guardian_gatekeeper_db_path', $db_path);

                        try {
                            // Create (connect to) SQLite database in file
                            $pdo = new PDO('sqlite:' . $db_path);
                            // Set errormode to exceptions
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Enable Write-Ahead Logging for better concurrency
                            $pdo->exec('PRAGMA journal_mode=WAL;');
                            // Adjust synchronous mode to NORMAL for faster writes at slight risk of data loss
                            $pdo->exec('PRAGMA synchronous=NORMAL;');
                            // Increase cache size for better performance
                            $pdo->exec('PRAGMA cache_size=-32000;'); // Example: 32 MB cache

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
                        } catch (PDOException $e) {
                            // Handle SQLite exception
                            echo '<p>' . __('Database creation error: ', 'wp-guardian') . $e->getMessage() . '</p>';
                        }
                    } else {
                        // Retrieve the database path from the option
                        $db_path = get_option('wp_guardian_gatekeeper_db_path');

                        try {
                            // Create (connect to) SQLite database in file
                            $pdo = new PDO('sqlite:' . $db_path);
                            // Set errormode to exceptions
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Prepare SELECT statement to fetch all records from the logs table
                            $stmt = $pdo->prepare("SELECT date, request_uri, query_string, user_agent, referrer FROM logs ORDER BY date DESC");
                            $stmt->execute();

                            // Fetch all the records
                            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Display the logs in a table
                            echo '<table class="display" id="gatekeeper-logs" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Request URI</th>
                                    </tr>
                                </thead>
                                <tbody>';

                            // Iterate over each log entry and display it in the table
                            foreach ($logs as $log) {
                                echo '<tr>
                                    <td style="white-space:nowrap">' . $log['date'] . '</td>
                                    <td>
                                        <code>' . $log['request_uri'] . '</code>
                                        <br><b>UA</b>: ' . htmlspecialchars($log['user_agent']);

                                if ((string) $log['referrer'] !== '') {
                                    echo '<br>→ ' . htmlspecialchars($log['referrer']);
                                }
                                echo '</td>
                                </tr>';
                            }

                            echo '</tbody>
                            </table>';
                        } catch (PDOException $e) {
                            // Handle SQLite exception
                            echo '<p>' . __('Oops! It seems like there are no logs available yet. Please check back later for updates.', 'wp-guardian') . '</p>';
                            echo '<p><small>' . $e->getMessage() . '</small></p>';
                        }
                    }
                    ?>
                </div>
            </div>

            <script>
            document.addEventListener("DOMContentLoaded", (event) => {
                let table = new DataTable('#gatekeeper-logs', {
                    order: [[0, 'desc']],
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100, 250, 500]
                });
            });
            </script>

            <?php
        } elseif ( $tab === 'guardian' ) {
            include DTJWPG_TEMPLATES . 'guardian-firewall.php';
        } elseif ( $tab === 'security' ) {
            include DTJWPG_TEMPLATES . 'guardian-options.php';
        } elseif ( $tab === 'login' ) {
            include DTJWPG_TEMPLATES . 'guardian-lockouts.php';
        } elseif ( $tab === 'settings' ) {
            include DTJWPG_TEMPLATES . 'guardian-settings.php';
        }
        ?>
    </div>
</div>
