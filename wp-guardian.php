<?php
/**
 * Plugin Name: WP Guardian
 * Plugin URI: https://getbutterfly.com/wordpress-plugins/wp-guardian/
 * Description: An easy way to harden your website's security effectively.
 * Version: 1.5.2
 * Author: Ciprian Popescu
 * Author URI: https://getbutterfly.com/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wp-guardian
 *
 * WP Guardian
 * Copyright (C) 2024 Ciprian Popescu (getbutterfly@gmail.com)
 * Copyright (C) 2016-2017 James Cooper (@jfcby)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

define( 'DTJWPG_VERSION', '1.5.2' );
define( 'DTJWPG_URL', __FILE__ );
define( 'DTJWPG_BASENAME', plugin_basename( DTJWPG_URL ) );
define( 'DTJWPG_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'DTJWPG_INCLUDES', DTJWPG_DIR . '/assets/includes/' );
define( 'DTJWPG_MODULES', DTJWPG_DIR . '/modules/' );
define( 'DTJWPG_TEMPLATES', DTJWPG_DIR . '/assets/templates/' );

require_once DTJWPG_INCLUDES . 'config.php';
require_once DTJWPG_INCLUDES . 'core.php';
require_once DTJWPG_INCLUDES . 'guardian.php';

// Firewall
if ( (int) get_option( 'wp_guardian_firewall_enable' ) === 1 ) {
    require_once DTJWPG_MODULES . 'firewall.php';
}
