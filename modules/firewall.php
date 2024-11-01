<?php
defined( 'ABSPATH' ) || die();



function wp_guardian_gatekeeper_long_requests( $enable ) {
    if ( (int) get_option( 'wp_guardian_firewall_long_requests' ) === 1 ) {
        return true;
    }

    return false;
}
add_filter( 'wp_guardian_gatekeeper_long_requests', 'wp_guardian_gatekeeper_long_requests' );

function wp_guardian_gatekeeper_long_req_length() {
    if ( (int) get_option( 'wp_guardian_firewall_long_requests_length' ) > 0 ) {
        return (int) get_option( 'wp_guardian_firewall_long_requests_length' );
    }

    return 2000;
}
add_action( 'wp_guardian_gatekeeper_long_req_length', 'wp_guardian_gatekeeper_long_req_length' );

function wp_guardian_gatekeeper_post_scanning( $enable ) {
    if ( (int) get_option( 'wp_guardian_firewall_xss' ) === 1 ) {
        return true;
    }

    return false;
}
add_filter( 'wp_guardian_gatekeeper_post_scanning', 'wp_guardian_gatekeeper_post_scanning' );



function wp_guardian_gatekeeper_core() {
    if ( ! defined( 'DOING_CRON' ) || ! DOING_CRON ) {
        $ignored_patterns = [
            'admin-ajax.php',
            'wp-json/wp/',
            'wp-json/edd/',
        ];

        foreach ( $ignored_patterns as $pattern ) {
            if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], $pattern ) !== false ) {
                return; // Exit the function, request should be ignored
            }
        }

        $request_uri_array  = apply_filters( 'request_uri_items', array( '\/\.env', '\s', '<', '>', '\^', '`', '@@', '\?\?', '\/&&', '\\', '\/=', '\/:\/', '\/\/\/', '\.\.\.', '\/\*(.*)\*\/', '\+\+\+', '\{0\}', '0x00', '%00', '\(\/\(', '(\/|;|=|,)nt\.', '@eval', 'eval\(', 'union(.*)select', '\(null\)', 'base64_', '(\/|%2f)localhost', '(\/|%2f)pingserver', 'wp-config\.php', '(\/|\.)(s?ftp-?)?conf(ig)?(uration)?\.', '\/wwwroot', '\/makefile', 'crossdomain\.', 'self\/environ', 'usr\/bin\/perl', 'var\/lib\/php', 'etc\/passwd', 'etc\/hosts', 'etc\/motd', 'etc\/shadow', '\/https:', '\/http:', '\/ftp:', '\/file:', '\/php:', '\/cgi\/', '\.asp', '\.bak', '\.bash', '\.bat', '\.cfg', '\.cgi', '\.cmd', '\.conf', '\.db', '\.dll', '\.ds_store', '\.exe', '\/\.git', '\.hta', '\.htp', '\.init?', '\.jsp', '\.msi', '\.mysql', '\.pass', '\.pwd', '\.sql', '\/\.svn', '\.exec\(', '\)\.html\(', '\{x\.html\(', '\.php\([0-9]+\)', '(benchmark|sleep)(\s|%20)*\(', '\/(db|mysql)-?admin', '\/document_root', '\/error_log', 'indoxploi', '\/sqlpatch', 'xrumer', 'www\.(.*)\.cn', '%3Cscript', '\/vbforum(\/)?', '\/vbulletin(\/)?', '\{\$itemURL\}', '(\/bin\/)(cc|chmod|chsh|cpp|echo|id|kill|mail|nasm|perl|ping|ps|python|tclsh)(\/)?$', '((curl_|shell_)?exec|(f|p)open|function|fwrite|leak|p?fsockopen|passthru|phpinfo|posix_(kill|mkfifo|setpgid|setsid|setuid)|proc_(close|get_status|nice|open|terminate)|system)(.*)(\()(.*)(\))', '(\/)(^$|0day|c99|configbak|curltest|db|index\.php\/index|(my)?sql|(php|web)?shell|php-?info|temp00|vuln|webconfig)(\.php)' ) );
        $query_string_array = apply_filters( 'query_string_items', array( '\(0x', '0x3c62723e', ';!--=', '\(\)\}', ':;\};', '\.\.\/', '\/\*\*\/', '127\.0\.0\.1', 'localhost', 'loopback', '%0a', '%0d', '%00', '%2e%2e', '%0d%0a', '@copy', 'concat(.*)(\(|%28)', 'allow_url_(fopen|include)', '(c99|php|web)shell', 'auto_prepend_file', 'disable_functions?', 'gethostbyname', 'input_file', 'execute', 'safe_mode', 'file_(get|put)_contents', 'mosconfig', 'open_basedir', 'outfile', 'proc_open', 'root_path', 'user_func_array', 'path=\.', 'mod=\.', '(globals|request)(=|\[)', 'f(fclose|fgets|fputs|fsbuff)', '\$_(env|files|get|post|request|server|session)', '(\+|%2b)(concat|delete|get|select|union)(\+|%2b)', '(cmd|command)(=|%3d)(chdir|mkdir)', '(absolute_|base|root_)(dir|path)(=|%3d)(ftp|https?)', '(s)?(ftp|inurl|php)(s)?(:(\/|%2f|%u2215)(\/|%2f|%u2215))', '(\/|%2f)(=|%3d|\$&|_mm|cgi(\.|-)|inurl(:|%3a)(\/|%2f)|(mod|path)(=|%3d)(\.|%2e))', '(<|>|\'|")(.*)(\/\*|alter|base64|benchmark|cast|char|concat|convert|create|declare|delete|drop|encode|exec|fopen|function|html|insert|md5|request|script|select|set|union|update)' ) );
        $user_agent_array   = apply_filters( 'user_agent_items', array( '&lt;', '%0a', '%0d', '%27', '%3c', '%3e', '%00', '0x00', '\/bin\/bash', '360Spider', 'acapbot', 'acoonbot', 'alexibot', 'asterias', 'attackbot', 'backdorbot', 'base64_decode', 'becomebot', 'binlar', 'blackwidow', 'blekkobot', 'blexbot', 'blowfish', 'bullseye', 'bunnys', 'butterfly', 'careerbot', 'casper', 'checkpriv', 'cheesebot', 'cherrypick', 'chinaclaw', 'choppy', 'clshttp', 'cmsworld', 'copernic', 'copyrightcheck', 'cosmos', 'crescent', 'cy_cho', 'datacha', 'demon', 'diavol', 'discobot', 'disconnect', 'dittospyder', 'dotbot', 'dotnetdotcom', 'dumbot', 'emailcollector', 'emailsiphon', 'emailwolf', 'eval\(', 'exabot', 'extract', 'eyenetie', 'feedfinder', 'flaming', 'flashget', 'flicky', 'foobot', 'g00g1e', 'getright', 'gigabot', 'go-ahead-got', 'gozilla', 'grabnet', 'grafula', 'harvest', 'heritrix', 'httrack', 'icarus6j', 'jetbot', 'jetcar', 'jikespider', 'kmccrew', 'leechftp', 'libweb', 'linkextractor', 'linkscan', 'linkwalker', 'loader', 'lwp-download', 'masscan', 'miner', 'majestic', 'md5sum', 'mechanize', 'mj12bot', 'morfeus', 'moveoverbot', 'netmechanic', 'netspider', 'nicerspro', 'nikto', 'nutch', 'octopus', 'pagegrabber', 'planetwork', 'postrank', 'proximic', 'purebot', 'pycurl', 'queryn', 'queryseeker', 'radian6', 'radiation', 'realdownload', 'remoteview', 'rogerbot', 'scooter', 'seekerspider', 'semalt', '(c99|php|web)shell', 'shellshock', 'siclab', 'sindice', 'sistrix', 'sitebot', 'site(.*)copier', 'siteexplorer', 'sitesnagger', 'skygrid', 'smartdownload', 'snoopy', 'sosospider', 'spankbot', 'spbot', 'sqlmap', 'stackrambler', 'stripper', 'sucker', 'surftbot', 'sux0r', 'suzukacz', 'suzuran', 'takeout', 'teleport', 'telesoft', 'true_robots', 'turingos', 'turnit', 'unserialize', 'vampire', 'vikspider', 'voideye', 'webleacher', 'webreaper', 'webstripper', 'webvac', 'webviewer', 'webwhacker', 'winhttp', 'wwwoffle', 'woxbot', 'xaldon', 'xxxyy', 'yamanalab', 'yioopbot', 'youda', 'zeus', 'zmeu', 'zyborg' ) );
        $referrer_array     = apply_filters( 'referrer_items', array( 'blue\s?pill', 'ejaculat', 'erectile', 'erections', 'hoodia', 'huronriver', 'impotence', 'levitra', 'libido', 'lipitor', 'phentermin', 'pro[sz]ac', 'sandyauer', 'semalt\.com', 'todaperfeita', 'tramadol', 'ultram', 'unicauca', 'valium', 'viagra', 'vicodin', 'xanax', 'ypxaieo' ) );
        $post_array         = apply_filters( 'post_items', array( '<%=', '\+\/"\/\+\/', '(<|%3C|&lt;?|u003c|x3c)script', 'src=#\s', '(href|src)="javascript:', '(href|src)=javascript:', '(href|src)=`javascript:' ) );

        //
        $request_uri_string  = '';
        $query_string_string = '';
        $user_agent_string   = '';
        $referrer_string     = '';

        $long_requests   = apply_filters( 'wp_guardian_gatekeeper_long_requests', true );
        $long_req_length = apply_filters( 'wp_guardian_gatekeeper_long_req_length', 2000 );
        $post_scanning   = apply_filters( 'wp_guardian_gatekeeper_post_scanning', false );

        if ( isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
            $request_uri_string = $_SERVER['REQUEST_URI'];
        }
        if ( isset( $_SERVER['QUERY_STRING'] ) && ! empty( $_SERVER['QUERY_STRING'] ) ) {
            $query_string_string = $_SERVER['QUERY_STRING'];
        }
        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
            $user_agent_string = $_SERVER['HTTP_USER_AGENT'];
        }
        if ( isset( $_SERVER['HTTP_REFERER'] ) && ! empty( $_SERVER['HTTP_REFERER'] ) ) {
            $referrer_string = $_SERVER['HTTP_REFERER'];
        }

        $matches = [];

        //
        if ( $long_requests && ( strlen( $request_uri_string ) > $long_req_length || strlen( $referrer_string ) > $long_req_length ) ) {
            wp_guardian_gatekeeper_response( [ $long_req_length ], $request_uri_string, $query_string_string, $user_agent_string, $referrer_string );
        }

        if ( $request_uri_string && preg_match( '/' . implode( '|', $request_uri_array ) . '/i', $request_uri_string, $matches ) ) {
            wp_guardian_gatekeeper_response( $matches, $request_uri_string, $query_string_string, $user_agent_string, $referrer_string );
        }

        if ( $query_string_string && preg_match( '/' . implode( '|', $query_string_array ) . '/i', $query_string_string, $matches ) ) {
            wp_guardian_gatekeeper_response( $matches, $request_uri_string, $query_string_string, $user_agent_string, $referrer_string );
        }

        if ( $user_agent_string && preg_match( '/' . implode( '|', $user_agent_array ) . '/i', $user_agent_string, $matches ) ) {
            wp_guardian_gatekeeper_response( $matches, $request_uri_string, $query_string_string, $user_agent_string, $referrer_string );
        }

        if ( $referrer_string && preg_match( '/' . implode( '|', $referrer_array ) . '/i', $referrer_string, $matches ) ) {
            wp_guardian_gatekeeper_response( $matches, $request_uri_string, $query_string_string, $user_agent_string, $referrer_string );
        }

        if ( $post_scanning && isset( $_POST ) ) {
            foreach ( $_POST as $key => $value ) {
                $value = wp_guardian_gatekeeper_get_string( $value );

                if ( empty( $value ) ) {
                    continue;
                }

                if ( preg_match( '/' . implode( '|', $post_array ) . '/i', $value, $matches ) ) {
                    wp_guardian_gatekeeper_response( $matches, $request_uri_string, $query_string_string, $user_agent_string, $referrer_string );

                    break;

                }
            }
        }
    }
}

add_action( 'plugins_loaded', 'wp_guardian_gatekeeper_core' );

function wp_guardian_gatekeeper_response( $matches, $request_uri_string, $query_string_string, $user_agent_string, $referrer_string ) {
    // Increment the block count
    $count = get_option( 'wp_guardian_block_count', 0 );
    $count++;
    update_option( 'wp_guardian_block_count', $count );

    $matches = isset( $matches[0] ) ? $matches[0] : null;

    if ( (int) get_option( 'wp_guardian_firewall_log' ) === 1 ) {
        wp_guardian_gatekeeper_log( $matches, $request_uri_string, $query_string_string, $user_agent_string, $referrer_string );
    }

    $header_1 = apply_filters( 'wp_guardian_gatekeeper_header_1', 'HTTP/1.1 403 Forbidden' );
    $header_2 = apply_filters( 'wp_guardian_gatekeeper_header_2', 'Status: 403 Forbidden' );
    $header_3 = apply_filters( 'wp_guardian_gatekeeper_header_3', 'Connection: Close' );

    header( $header_1 );
    header( $header_2 );
    header( $header_3 );

    exit();
}

function wp_guardian_gatekeeper_get_string( $var ) {
    if ( ! is_array( $var ) ) {
        return $var;
    }

    foreach ( $var as $key => $value ) {
        if ( is_array( $value ) ) {
            wp_guardian_gatekeeper_get_string( $value );
        } else {
            return $value;
        }
    }
}



/**
 * wp_guardian_gatekeeper_firewall_header_1
 *
 * @param mixed $header
 * @return void
 */
function wp_guardian_gatekeeper_firewall_header_1( $header ) {
    if ( (string) get_option( 'wp_guardian_firewall_redirect_page' ) === 'homepage' ) {
        return 'Location: ' . home_url();
    } elseif ( (string) get_option( 'wp_guardian_firewall_redirect_page' ) === 'custom' && (string) get_option( 'wp_guardian_firewall_redirect_custom' ) !== '' ) {
        return 'Location: ' . get_option( 'wp_guardian_firewall_redirect_custom' );
    }
}

function wp_guardian_gatekeeper_firewall_header_2( $header ) {
    return '';
}

function wp_guardian_gatekeeper_firewall_header_3( $header ) {
    return '';
}

if (
    (string) get_option( 'wp_guardian_firewall_redirect_page' ) === 'homepage' ||
    (
        (string) get_option( 'wp_guardian_firewall_redirect_page' ) === 'custom' &&
        (string) get_option( 'wp_guardian_firewall_redirect_custom' ) !== ''
    )
) {
    add_filter( 'wp_guardian_gatekeeper_header_1', 'wp_guardian_gatekeeper_firewall_header_1' );

    add_filter( 'wp_guardian_gatekeeper_header_2', 'wp_guardian_gatekeeper_firewall_header_2' );
    add_filter( 'wp_guardian_gatekeeper_header_3', 'wp_guardian_gatekeeper_firewall_header_3' );
}
//



function wp_guardian_gatekeeper_log( $data, $request_uri_string, $query_string_string, $user_agent_string, $referrer_string ) {
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
        $stmt = $pdo->prepare( "INSERT INTO logs (date, request_uri, query_string, user_agent, referrer) VALUES (?, ?, ?, ?, ?)" );

        // Bind and insert the data
        $stmt->execute(
            [
                date_i18n( 'Y-m-d H:i:s', time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
                $request_uri_string,
                $query_string_string,
                $user_agent_string,
                $referrer_string,
            ]
        );
    } catch ( PDOException $e ) {
        // Handle SQLite exception
        // echo "SQLite error: " . $e->getMessage();
    }
}



/**
 * Clean up the database and keep only the most recent 10,000 records.
 *
 * This function connects to the SQLite database, checks the total number of records,
 * and deletes the excess records if the total exceeds 10,000, keeping only the
 * most recent 10,000 records.
 *
 */
function wp_guardian_cleanup_database_job() {
    // Your SQLite database connection code and cleanup logic here
    $db_path    = get_option( 'wp_guardian_gatekeeper_db_path' );
    $db_records = get_option( 'wp_guardian_firewall_log_size', 10000 );

    if ( ! file_exists( $db_path ) ) {
        return;
    }

    $pdo = new PDO( 'sqlite:' . $db_path );
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    // Retrieve total number of records
    $total_records = $pdo->query( "SELECT COUNT(*) FROM logs" )->fetchColumn();

    // If total records exceed 10,000, delete excess records
    if ( $total_records > 10000 ) {
        $excess_count = $total_records - 10000;
        $pdo->exec( "DELETE FROM logs WHERE id IN (SELECT id FROM logs ORDER BY id LIMIT $excess_count)" );
    }
}

// Hook the cleanup function to run daily using WordPress CRON
add_action( 'wp_guardian_cleanup_database_daily', 'wp_guardian_cleanup_database_job' );

// Schedule the CRON job to run daily at midnight
if ( ! wp_next_scheduled( 'wp_guardian_cleanup_database_daily' ) ) {
    wp_schedule_event( strtotime( 'midnight' ), 'daily', 'wp_guardian_cleanup_database_daily' );
}



if ( (int) get_option( 'wpguardian_brute_force' ) === 1 ) {
    function wpguardian_limit_login_attempts() {
        global $pagenow;

        if ( (string) $pagenow === 'wp-login.php' ) {
            if ( ! isset( $_SESSION ) ) {
                session_start();
            }

            // Spam cookie registration
            if ( $_SESSION['last_session_request_bf'] > time() - 5 ) {
                if ( empty( $_SESSION['last_request_count_bf'] ) ) {
                    $_SESSION['last_request_count_bf'] = 1;
                } elseif ( (int) $_SESSION['last_request_count_bf'] < 5 ) {
                    $_SESSION['last_request_count_bf'] = $_SESSION['last_request_count_bf'] + 1;
                } elseif ( (int) $_SESSION['last_request_count_bf'] === 5 ) {

                    if ( isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
                        $request_uri_string = $_SERVER['REQUEST_URI'];
                    }
                    if ( isset( $_SERVER['QUERY_STRING'] ) && ! empty( $_SERVER['QUERY_STRING'] ) ) {
                        $query_string_string = $_SERVER['QUERY_STRING'];
                    }
                    if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
                        $user_agent_string = $_SERVER['HTTP_USER_AGENT'];
                    }
                    if ( isset( $_SERVER['HTTP_REFERER'] ) && ! empty( $_SERVER['HTTP_REFERER'] ) ) {
                        $referrer_string = $_SERVER['HTTP_REFERER'];
                    }

                    wp_guardian_gatekeeper_response( [ 0 ], $request_uri_string, $query_string_string, $user_agent_string, $referrer_string );

                    //START MAIL Notify
                    /*
                    if( get_option("wpuf_mail_alarm") && get_option("wpuf_mail_alarm_bruteforce")  == 1 ) {
                        //Check IP
                        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                        } else {
                            $ip = $_SERVER['REMOTE_ADDR'];
                        }

                        //Check User Agent
                        $get_useragent = $_SERVER['HTTP_USER_AGENT'];

                        //Date and Time
                        $time = current_time('d F Y - H:i');

                        //URL
                        $spamUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                        $message = __("Brute-Force Attacks detected and blocked:", "ua-protection-lang" ) . "\r\n\r\n" .'IP Address: '. $ip ."\r\n" . 'Date: ' . $time ."\r\n" . 'User Agent: '.$get_useragent ."\r\n" .'URL: '. $spamUrl ."\r\n\r\n" . __("If you want, you can block this IP address or User Agents from the WP Ultimate Firewall panel.", "ua-protection-lang" ) ."\r\n" . __("Your website is protected by WP Ultimate Firewall.", "ua-protection-lang" );

                        $email = wp_mail(
                            get_option("wpuf_mail_notify"),

                            trim("Brute-Force Alert - ". get_option("blogname")),

                            stripslashes( trim($message) ),

                            "From:". trim(get_option("blogname"))." <".trim(get_option("admin_email")).">\r\nReply-To:".trim(get_option("admin_email"))
                        );
                    }
                    /**/
                    //END Mail Notify

                    // Block for 30 minutes
                    $_SESSION['last_request_count_bf']   = $_SESSION['last_request_count_bf'] + 1;
                    $_SESSION['last_session_request_bf'] = ( time() + 1795 );
                    wpguardian_block_type();
                } elseif ( (int) $_SESSION['last_request_count_bf'] >= 6 ) {
                    // Block for 30 minutes
                    $_SESSION['last_session_request_bf'] = ( time() + 1795 );
                        wpguardian_block_type();
                    }
                } else {
                    $_SESSION['last_request_count_bf'] = 1;
                }

                $_SESSION['last_session_request_bf'] = time();
        }
    }

    add_action( 'init', 'wpguardian_limit_login_attempts' );
}

/* Block Type (For Sessions) */
function wpguardian_block_type() {
    $useragentCheck = strtolower( $_SERVER['HTTP_USER_AGENT'] );
    if ( strpos( strtolower($useragentCheck), "googlebot") === false || strpos(strtolower($useragentCheck), "bingbot") === false || strpos(strtolower($useragentCheck), "yahoo! slurp") === false || strpos(strtolower($useragentCheck), "yandexbot/3.0") === false) {
        header( 'HTTP/1.1 403 Forbidden' );
        header( 'Status: 403 Forbidden' );
        header( 'Connection: Close' );

        exit;
    }
}


if ( (int) get_option( 'wp_guardian_firewall_brute_force' ) === 1 ) {
    include ABSPATH . 'wp-includes/pluggable.php';

    if ( ! ( current_user_can( 'administrator' ) ) ) {
        function wpguardian_flood_sec() {
            if ( ! isset( $_SESSION ) ) {
                session_start();
            }

            if ( $_SESSION['last_session_request'] > time() - 3 ) {
                if ( empty($_SESSION['last_request_count'] ) ) {
                    $_SESSION['last_request_count'] = 1;
                } elseif ( (int) $_SESSION['last_request_count'] < 5 ) {
                    $_SESSION['last_request_count'] = $_SESSION['last_request_count'] + 1;
                } elseif ( (int) $_SESSION['last_request_count'] === 5 ) {

                    if ( isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
                        $request_uri_string = $_SERVER['REQUEST_URI'];
                    }
                    if ( isset( $_SERVER['QUERY_STRING'] ) && ! empty( $_SERVER['QUERY_STRING'] ) ) {
                        $query_string_string = $_SERVER['QUERY_STRING'];
                    }
                    if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
                        $user_agent_string = $_SERVER['HTTP_USER_AGENT'];
                    }
                    if ( isset( $_SERVER['HTTP_REFERER'] ) && ! empty( $_SERVER['HTTP_REFERER'] ) ) {
                        $referrer_string = $_SERVER['HTTP_REFERER'];
                    }

                    wp_guardian_gatekeeper_response( [ 0 ], $request_uri_string, $query_string_string, $user_agent_string, $referrer_string );

                    //START MAIL Notify
                    /*
                    if( get_option("wpuf_mail_alarm") && get_option("wpuf_mail_alarm_spam")  == 1 ) {
                        //Check IP
                        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                        } else {
                            $ip = $_SERVER['REMOTE_ADDR'];
                        }
                        
                        //Check User Agent
                        $get_useragent = $_SERVER['HTTP_USER_AGENT'];
                            
                        //Date and Time
                        $time = current_time('d F Y - H:i');
                        
                        //URL
                        $spamUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                            
                        $message = __("Spam Attacks detected and blocked:", "ua-protection-lang" ) . "\r\n\r\n" .'IP Address: '. $ip ."\r\n" . 'Date: ' . $time ."\r\n" . 'User Agent: '.$get_useragent ."\r\n" .'URL: '. $spamUrl ."\r\n\r\n" . __("If you want, you can block this IP address or User Agents from the WP Ultimate Firewall panel.", "ua-protection-lang" ) ."\r\n" . __("Your website is protected by WP Ultimate Firewall.", "ua-protection-lang" );
                            
                        $email = wp_mail(
                            get_option("wpuf_mail_notify"),
                            
                            trim("Spam Alert - ". get_option("blogname")),
                            
                            stripslashes( trim($message) ),
                            
                            "From:". trim(get_option("blogname"))." <".trim(get_option("admin_email")).">\r\nReply-To:".trim(get_option("admin_email"))
                        );
                        
                    }
                    /**/
                    //END Mail Notify	

                    // Block for 30 minutes
                    $_SESSION['last_request_count']   = $_SESSION['last_request_count'] + 1;
                    $_SESSION['last_session_request'] = ( time() + 1795 );

                    wpguardian_block_type();

                } elseif ( (int) $_SESSION['last_request_count'] >= 6 ) {
                    // Block for 30 minutes
                    $_SESSION['last_session_request'] = ( time() + 1795 );

                    wpguardian_block_type();
                }
            } else {
                $_SESSION['last_request_count'] = 1;
            }

            $_SESSION['last_session_request'] = time();
        }

        add_action( 'init', 'wpguardian_flood_sec' );
    }
}
