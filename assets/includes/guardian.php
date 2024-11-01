<?php
if ( ! defined('ABSPATH') ) {
	die;
}

/**
 * Gets the currently requested page.
 * 
 * Returns the string of the current page from the 'pagenow' global.
 * 
 * @since 1.0
 * @return string $dtjwpg_current_page
 */
function dtjwpg_current_page() {
    global $pagenow;

    $current_page = ! empty( $pagenow ) ? $pagenow : '';

    return $current_page;

    /*
	// Get the current page in a variable
	$dtjwpg_current_page = ! empty( $GLOBALS['pagenow'] ) ? $GLOBALS['pagenow'] : '';

	// Return the current page
	return $dtjwpg_current_page;
    /**/
}

/**
 * Checks whether the hide backend setting is enabled.
 *
 * @since 1.0
 * @return boolean
 */
function dtjwpg_has_hidden_backend() {

	// Grab the backend token from the database
	$dtjwpg_backend_token = get_option('dtjwpg_backend_token_option');

	// Check whether the token is set to an invalid string
	if ( 
		empty( $dtjwpg_backend_token ) ||
		'' == $dtjwpg_backend_token ||
		' ' == $dtjwpg_backend_token ||
		'/' == $dtjwpg_backend_token ||
		'wp-login' == $dtjwpg_backend_token ||
		'wp-admin' == $dtjwpg_backend_token ||
		'wp-content' == $dtjwpg_backend_token ||
		'wp-includes' == $dtjwpg_backend_token
	) {

		return false;

	} else {

		return true;

	}

}

/**
 * Checks if the incorrect login token redirect is set.
 * 
 * This function will check if there is a custom redirect set
 * if the login token is wrong or not set for wp-login.php and will
 * return false if not or return the string of the redirect if
 * it is set.
 *
 * @since 1.0
 * @return string|boolean
 */
function dtjwpg_has_redirect_token() {

	// Grab the backend token from the database
	$dtjwpg_backend_redirect = get_option('dtjwpg_backend_redirect_option');

	// Check whether the token is set to an invalid string
	if ( 
		empty( $dtjwpg_backend_redirect ) ||
		'' == $dtjwpg_backend_redirect ||
		' ' == $dtjwpg_backend_redirect ||
		'/' == $dtjwpg_backend_redirect ||
		'wp-login' == $dtjwpg_backend_redirect ||
		'wp-admin' == $dtjwpg_backend_redirect ||
		'wp-content' == $dtjwpg_backend_redirect ||
		'wp-includes' == $dtjwpg_backend_redirect
	) {

		return false;

	} else {

		return $dtjwpg_backend_redirect;

	}

}

/**
 * Disables file editing within wp-admin.
 * 
 * @since 1.0
 * @return void
 */
function dtjwpg_disable_file_edits() {

	// Check if file editing should be disabled
	if ( 'on' == get_option('dtjwpg_wp_fileedit_option') ) {

		// Check it's not already defined and then define it
		if ( ! defined('DISALLOW_FILE_EDIT') ) {
			define('DISALLOW_FILE_EDIT', true);
		}

	}

}
add_action('plugins_loaded', 'dtjwpg_disable_file_edits', 1);



/**
 * Turns debugging mode on if not already defined.
 * 
 * @since 1.2
 * @return void
 */
function dtjwpg_wp_debug_mode() {

	// Check if debug mode has been turned on
	if ( 'on' == get_option('dtjwpg_wp_debug_option') ) {

		// Make sure that the main WP_DEBUG constant isn't already defined
		if ( ! defined('WP_DEBUG') ) {
			
			// Turn debug mode on
			define('WP_DEBUG', true);

			// Check to see if the other debug constants are defined
			if ( ! defined('WP_DEBUG_LOG') && ! defined('WP_DEBUG_DISPLAY') ) {

				// Define the other two constants
				define('WP_DEBUG_LOG', true);
				define('WP_DEBUG_DISPLAY', true);

			}

		}

	}

}
add_action('plugins_loaded', 'dtjwpg_wp_debug_mode', 1);

// Check if the backend is hidden to prevent brute force attacks
if ( true === dtjwpg_has_hidden_backend() ) {

	// Prevent redirects using shortcuts like `/login` and `/admin`
	remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);

	/**
	 * Redirects the user if a valid token is not supplied.
	 * 
	 * Checks what page the current user is on and redirects
	 * to either wp-login if a valid backend token is provided
	 * or redirects to a 404 page if it's not.
	 * 
	 * @since 1.0
	 * @return void
	 */
	function dtjwpg_hide_backend() {

		// Get the backend token from the database
		$dtjwpg_backend_token = get_option('dtjwpg_backend_token_option');

		// Get the backend token from the URL query
		$dtjwpg_token_query = ! empty( $_GET['dtjwpg-token'] ) ? $_GET['dtjwpg-token'] : '';

		// Get the action query from the URL
		$dtjwpg_action_query = ! empty( $_GET['action'] ) ? $_GET['action'] : '';

		// Are we on the wp-login page - and if so - only redirect if we have an incorrect token
		if ( 'wp-login.php' == dtjwpg_current_page() && ! is_user_logged_in() && 'POST' != $_SERVER['REQUEST_METHOD'] && 'rp' != $dtjwpg_action_query && 'postpass' != $dtjwpg_action_query ) {

			// When an incorrect token is provided, redirect!
			if ( $dtjwpg_backend_token !== $dtjwpg_token_query ) {

				// Check if we have a redirect token set
				if ( false !== dtjwpg_has_redirect_token() ) {

					wp_safe_redirect(esc_url( home_url('/') ) . dtjwpg_has_redirect_token(), '301');
					die();

				} else {

					wp_safe_redirect('/', '301');
					die();

				}

			}

		} elseif ( 'index.php' == dtjwpg_current_page() && ! is_user_logged_in() ) {

			/**
			 * We need to intercept requests to wp-admin because the login URL
			 * has been filtered to have the backend token in it for other links.
			 *
			 * Check if the requested path contains wp-admin.
			 */
			if ( false !== strpos( $_SERVER['REQUEST_URI'], '/wp-admin/' ) ) {

				// Check if we have a redirect token set
				if ( false !== dtjwpg_has_redirect_token() ) {

					wp_redirect(esc_url( home_url('/') ) . dtjwpg_has_redirect_token(), '301');
					die();

				} else {

					wp_safe_redirect('/', '301');
					die();

				}

			}

		}

	}
	add_action('plugins_loaded', 'dtjwpg_hide_backend', 5);

	/**
	 * Redirect to the login page using the token.
	 * 
	 * Redirects the user to the login page if the login token is
	 * used in the URL as a slug.
	 * 
	 * @since 1.0
	 * @return void
	 */
	function dtjwpg_redirect_token_slug() {

		// Get the login page token from the database
		$dtjwpg_backend_token = get_option('dtjwpg_backend_token_option');

		// Check that the requested URL contains the login token as a slug correctly in order to redirect
		if ( home_url( $dtjwpg_backend_token ) == untrailingslashit( home_url( $_SERVER['REQUEST_URI'] ) ) ) {
		
			// Build the URL to contain the login token
			$dtjwpg_login_url = add_query_arg( array( 'dtjwpg-token' => $dtjwpg_backend_token ), home_url('wp-login.php') );

			// Redirect to the login page
			wp_redirect( $dtjwpg_login_url );
			die();
		
		}

	}
	add_action('template_redirect', 'dtjwpg_redirect_token_slug', 5);

	/**
	 * Rewrites authentication URLs to include the backend token.
	 * 
	 * This function rewrites the URL for four main components
	 * of 'user auth'. Those being login, register, logout and
	 * lost password URLs used by wp-login.php.
	 * 
	 * @param string $auth_url
	 * @param string $redirect
	 * 
	 * @since 1.0
	 * @return string $dtjwpg_rewrite
	 */
	function dtjwpg_rewrite_auth_url( $auth_url, $redirect = '' ) {

		// Get the backend token from the database
		$dtjwpg_backend_token = get_option('dtjwpg_backend_token_option');

		// Get the URL and insert the backend token
		$dtjwpg_rewrite = add_query_arg( array( 'dtjwpg-token' => $dtjwpg_backend_token ), $auth_url );

		// Return the new URL
		return $dtjwpg_rewrite;

	}
	add_filter('login_url', 'dtjwpg_rewrite_auth_url', 20, 2);
	add_filter('register_url', 'dtjwpg_rewrite_auth_url', 20, 2);
	add_filter('logout_url', 'dtjwpg_rewrite_auth_url', 20, 2);
	add_filter('lostpassword_url', 'dtjwpg_rewrite_auth_url', 20, 2);

	/**
	 * Rewrites the redirect URLs for the auth pages.
	 * 
	 * Rewrites the redirect to link for the three different wp-login.php
	 * screens except for logging in because that will go to wp-admin anyway.
	 * 
	 * @param string $redirect_to
	 * 
	 * @since 1.0
	 * @return string $dtjwpg_redirect
	 */
	function dtjwpg_rewrite_redirect_url( $redirect_to ) {

		// Get the backend token from the database
		$dtjwpg_backend_token = get_option('dtjwpg_backend_token_option');

		// Get the URL and insert the backend token
		$dtjwpg_redirect = add_query_arg( array( 'dtjwpg-token' => $dtjwpg_backend_token ), $redirect_to );

		// Return the new URL
		return $dtjwpg_redirect;

	}
	add_filter('logout_redirect', 'dtjwpg_rewrite_redirect_url', 20, 1);
	add_filter('lostpassword_redirect', 'dtjwpg_rewrite_redirect_url', 20, 1);
	add_filter('registration_redirect', 'dtjwpg_rewrite_redirect_url', 20, 1);

}



// Only show the two step option if it's enabled
if ( 'on' == get_option('dtjwpg_verify_option') ) {

	/**
	 * Checks if a specified user is using Two Step Verification.
	 * Only returns true if a valid method is selected else returns false.
	 * 
	 * @param $user_id User ID of the current user
	 * 
	 * @since 1.0
	 * @return boolean
	 */
	function dtjwpg_using_two_step( $user_id = 0 ) {

		// Check we have a valid user id and they have TSV enabled
		if ( 0 != $user_id && '1' == get_user_meta( $user_id, 'dtjwpg_two_step_method', true ) ) {

			// Yep they're using Two Step Verification
			return true;

		} else {

			// Nope, stop
			return false;

		}

	}

	/**
	 * Add HTML markup to the user profile page.
	 * 
	 * Need to use two hooks here because the hooks fire depending
	 * on whether the user is viewing their own profile or an admin
	 * viewing someone elses.
	 * 
	 * @param $user Object of a user
	 * 
	 * @since 1.0
	 * @return mixed
	 */
	function dtjwpg_add_profile_fields( $user ) {

		?>

			<h2><?php _e('Account Guardian', 'wp-guardian'); ?></h2>
			<table class="dtjwpg-table form-table">
				<tbody>
					<tr class="dtjwpg-two-step user-two-step-wrap">
						<th scope="row"><?php _e('Two Step Verification', 'wp-guardian'); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e('Two Step Verification', 'wp-guardian'); ?></span></legend>
								<select id="dtjwpg_two_step_method" name="dtjwpg_two_step_method">
									<option><?php _e('&mdash; Select &mdash;', 'wp-guardian'); ?></option>
									<option value="1"<?php if ( '1' == get_user_meta( $user->data->ID, 'dtjwpg_two_step_method', true ) ) : ?> selected="selected"<?php endif; ?>><?php _e('Send a code via email', 'wp-guardian'); ?></option>
								</select>
							</fieldset>
						</td>
					</tr>
				</tbody>
			</table>

		<?php

	}
	add_action('show_user_profile', 'dtjwpg_add_profile_fields', 15, 1);
	add_action('edit_user_profile', 'dtjwpg_add_profile_fields', 15, 1);

	/**
	 * Save the values from the Two Step profile fields
	 * 
	 * @param $user_id User id of current user
	 * 
	 * @since 1.0
	 * @return mixed
	 */
	function dtjwpg_save_profile_fields( $user_id ) {

		// Make sure we only get accepted values
		$dtjwpg_tsv_method = (int) sanitize_text_field( $_POST['dtjwpg_two_step_method'] );

		// Can the user update the profile settings
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
		
			// No thanks
			return false;

		} else {

			// Update the user meta data
			update_user_meta( $user_id, 'dtjwpg_two_step_method', $dtjwpg_tsv_method );

		}
	
	}
	add_action('personal_options_update', 'dtjwpg_save_profile_fields', 15, 1);
	add_action('edit_user_profile_update', 'dtjwpg_save_profile_fields', 15, 1);

	/**
	 * Clear auth cookies and redirect users using Two Step.
	 * 
	 * @param $user_login The username of the user trying to login
	 * @param $user       Object of the current user trying to login
	 * 
	 * @since 1.0
	 * @return void
	 */
	function dtjwpg_redirect_two_step( $user_login, $user ) {

		// Check if the user that logged in has two step turned on
		if ( true === dtjwpg_using_two_step($user->ID) ) {

			// Pass the login request data into a variable
			$dtjwpg_login_request = $_REQUEST;

			// Clear the auth cookies so they're technically not logged in
			wp_clear_auth_cookie();

			// Continue to load the next step
			dtjwpg_two_step_setup( $user->ID, $dtjwpg_login_request, '' );

			// Stop further loading
			exit();

		}

	}
	add_action('wp_login', 'dtjwpg_redirect_two_step', 15, 2);

	/**
	 * Verify the users request from the Two Step Verification form.
	 * 
	 * This is a helper function which hands off to another.
	 * 
	 * @since 1.0
	 * @return void
	 */
	function dtjwpg_verify_two_step() {

		// Check that we're on the wp-login.php page
		if ( 'wp-login.php' == $GLOBALS['pagenow'] ) {

			// Get the query variable from the URL (if there is one)
			$dtjwpg_query_vars = isset( $_GET['tsv'] ) ? sanitize_text_field($_GET['tsv']) : '';

			// Check if the query variable is to verify
			if ( 'verify' == $dtjwpg_query_vars ) {

				// Pass the request data into a variable
				$dtjwpg_tsv_request = $_REQUEST;

				// Remove the password from the request
				unset($dtjwpg_tsv_request['pwd']);

				// Pass the user id into a smaller variable
				$dtjwpg_user = $dtjwpg_tsv_request['dtjwpg_tsv_user'];

				// Get a value for if the user should be remembered
				$dtjwpg_remember = isset( $_REQUEST['dtjwpg_tsv_remember'] ) ? true : false;

				// Get the nonce from the database
				$dtjwpg_user_nonce = get_user_meta( $dtjwpg_user, 'dtjwpg_tsv_nonce', true );

				// Get the verification code from the database
				$dtjwpg_user_code = get_user_meta( $dtjwpg_user, 'dtjwpg_tsv_code', true );

				// Get the verification code expiry time
				$dtjwpg_code_expiry = get_user_meta( $dtjwpg_user, 'dtjwpg_tsv_expiry', true );

				// Check the nonce and code to see if it matches and that the code hasn't expired
				if ( $_REQUEST['dtjwpg_tsv_nonce'] == $dtjwpg_user_nonce && $_REQUEST['dtjwpg_tsv_code'] == $dtjwpg_user_code && strtotime( date( 'Y-m-d H:i:s' ) ) <= $dtjwpg_code_expiry ) {

					// User is verified, log them in
					wp_set_auth_cookie( $dtjwpg_user, $dtjwpg_remember );

					// Delete the user meta data we created
					delete_user_meta( $dtjwpg_user, 'dtjwpg_tsv_nonce' );
					delete_user_meta( $dtjwpg_user, 'dtjwpg_tsv_code' );

					// Redirect the user into wp-admin
					wp_safe_redirect('/wp-admin/');

				} else {

					// User failed verification, set error message
					$dtjwpg_tsv_error = __('Incorrect code entered, try again.', 'wp-guardian');

					// Reset the two step process and set it up
					dtjwpg_two_step_setup( $dtjwpg_user, $dtjwpg_tsv_request, $dtjwpg_tsv_error );

				}

				// Stop execution
				die();

			}

		}

	}
	add_action('init', 'dtjwpg_verify_two_step', 5, 2);

	/**
	 * Creates our user meta data for the second login step.
	 * 
	 * @param $user_id The user id of the person logging in.
	 * 
	 * @since 1.0
	 * @return void
	 */
	function dtjwpg_two_step_setup( $user_id, $dtjwpg_login_request, $dtjwpg_tsv_error ) {

		// Make sure we have a proper user id (although we always should at this point)
		if ( 0 != $user_id ) {

			// Create a nonce for the form using wp_hash
			$dtjwpg_tsv_nonce = wp_hash( $user_id . rand(10000, 99999) . date('YmdHis'), 'nonce' );

			// Create a secret code the user must enter
			$dtjwpg_tsv_code = rand(111111, 999999);

			// Create the expiry timestamp
			$dtjwpg_tsv_expiry = strtotime( '+2 hours', strtotime( date( 'Y-m-d H:i:s' ) ) );

			// Add the nonce to the user meta to store it
			update_user_meta( $user_id, 'dtjwpg_tsv_nonce', $dtjwpg_tsv_nonce );

			// Add the code to the user meta to store it
			update_user_meta( $user_id, 'dtjwpg_tsv_code', $dtjwpg_tsv_code );

			// Add the expiry time limit of two hours
			update_user_meta( $user_id, 'dtjwpg_tsv_expiry', $dtjwpg_tsv_expiry );

			// Get the users email address
			$dtjwpg_user_data = get_userdata( $user_id );

			// Setup the parametres for the email
			$dtjwpg_email_args = array(
				'to'			=> $dtjwpg_user_data->data->user_email,
				'subject'		=> __('Verification Code', 'wp-guardian'),
				'body'			=> sprintf( __('<p>Hello!</p><p>Someone has tried to login to your account but it is secured by two step verification.</p><p>Enter the following code within two hours <strong>%s</strong> to verify that it is you.</p><p><em>Remember, you cannot login to your account without a valid verification code.</em></p><p>Sent from WordPress via WP Guardian.</p>', 'wp-guardian'), $dtjwpg_tsv_code)
			);

			// Send the email to the user
			$dtjwpg_email_sent = dtjwpg_send_mail($dtjwpg_email_args);

			// Check to see if the email was sent
			if ( '1' == $dtjwpg_email_sent ) {

				// Load the two step login form
				dtjwpg_load_two_step_form( $user_id, $dtjwpg_login_request, $dtjwpg_tsv_error );

			} else {

				// Set the error message
				$dtjwpg_tsv_error = __('Failed to send email, try again.', 'wp-guardian');

				// The code couldn't be sent, return with an error
				dtjwpg_load_two_step_form( $user_id, $dtjwpg_login_request, $dtjwpg_tsv_error );

			}

		}

	}

	/**
	 * Loads the HTML for the Two Step login form.
	 * 
	 * @param $user_id    The user id of the person logging in.
	 * @param $login_data Meta data from the original login
	 * 
	 * @since 1.0
	 * @return mixed
	 */
	function dtjwpg_load_two_step_form( $user_id, $dtjwpg_login_request, $dtjwpg_tsv_error = '' ) {

		// Sanity check the user id
		if ( ! $user_id ) {
			return;
		}

		// Get the meta data to fill out the form
		$dtjwpg_user_nonce = get_user_meta( $user_id, 'dtjwpg_tsv_nonce', true );
		$dtjwpg_user_remember = isset( $dtjwpg_login_request['rememberme'] ) ? '1' : '';

		// Load the HTML for the login page
		login_header(); ?>

			<?php if ( ! empty( $dtjwpg_tsv_error ) ) : ?>
				<p id="login_error"><?php echo $dtjwpg_tsv_error; ?></p>
			<?php endif; ?>
	
			<form name="twostepform" id="twostepform" action="<?php echo esc_url( home_url('wp-login.php?tsv=verify') ); ?>" method="post">
				<p style="margin-bottom: 20px;">
					<?php _e('A code has been sent to you. It will expire in two hours.', 'wp-guardian'); ?>
				</p>
				<p>
					<label for="dtjwpg_tsv_code"><?php _e('Code', 'wp-guardian'); ?></label>
					<input type="text" name="dtjwpg_tsv_code" id="dtjwpg_tsv_code" class="input" />
				</p>
				<p>
					<input type="hidden" name="dtjwpg_tsv_user" id="dtjwpg_tsv_user" value="<?php echo $user_id; ?>" />
					<input type="hidden" name="dtjwpg_tsv_nonce" id="dtjwpg_tsv_nonce" value="<?php echo $dtjwpg_user_nonce; ?>" />
					<input type="hidden" name="dtjwpg_tsv_remember" id="dtjwpg_tsv_remember" value="<?php echo $dtjwpg_user_remember; ?>" />
					<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php _e('Submit', 'wp-guardian'); ?>" />
				</p>
			</form>

		<?php login_footer();

	}

}



/**
 * Sends an email to someone based on the given parameters.
 * 
 * @param $dtjwpg_mail An array of arguments for sending an email
 * 
 * @since 1.0
 * @return boolean
 */
function dtjwpg_send_mail( $dtjwpg_mail = array() ) {

	/**
	 * 1. To address ('to')    - required
	 * 2. Subject ('subject')  - required
	 * 3. Message ('body')     - required
	 * 4. Attachment ('file')  - optional
	 * 
	 * The from address is taken from the admin email address
	 * and the headers are automatically set on send.
	 */

	// Set the mail value default
	$dtjwpg_mail_value = '0';

	// Make sure the array is not empty
	if ( ! empty($dtjwpg_mail) ) {

		// Check that the email to send to is a valid address
		if ( filter_var($dtjwpg_mail['to'], FILTER_VALIDATE_EMAIL) ) {

			// Check the subject line is at least one character
			if ( strlen($dtjwpg_mail['subject']) >= 1 ) {

				// Check the message body is at least a character long too
				if ( strlen($dtjwpg_mail['body']) >= 1 ) {

					// The email passed the checks, return a positive value for the Ajax request
					$dtjwpg_mail_value = '1';

					// Add a prefix to the email subject so we know it's from WordPress
					$dtjwpg_mail['subject'] = '[' . get_bloginfo('name') . ']' . ' ' . $dtjwpg_mail['subject'];

					// Build the mail headers here before we send the email
					$dtjwpg_mail['headers'] = 'Content-Type: text/html; charset=UTF-8';

					// Set the attachments for this email if any files are set
					if ( ! empty( $dtjwpg_mail['file'] ) ) {

						$dtjwpg_mail['file'] = array( $dtjwpg_mail['file'] );

					} else {

						$dtjwpg_mail['file'] = array();

					}

					// Send the email to the user
					wp_mail(
						$dtjwpg_mail['to'],
						$dtjwpg_mail['subject'],
						$dtjwpg_mail['body'],
						$dtjwpg_mail['headers'],
						$dtjwpg_mail['file']
					);

				}

			}

		}

	}

	// Return a value for output
	return $dtjwpg_mail_value;

}

