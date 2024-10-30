<?php
/**
 * File callback.php
 *
 * @author Sebastian Schimpfhauser <seschi98@googlemail.com>
 * @package WP JWT Single Sign On Plugin
 */

defined( 'ABSPATH' ) or die( 'You are not allowed to call this script directly!' );

use \Firebase\JWT\JWT;


// Redirect the user back to the home page if logged in.
if ( is_user_logged_in() ) {
	wp_redirect( home_url() );
	exit;
}

// Grab a copy of the options and set the redirect location.
$options       = get_option( 'jwt_sso_options' );
$user_redirect = wpssoc_get_user_redirect_url();

// Authenticate Check and Redirect
if ( ! isset( $_GET['code'] ) ) {
	$params = array();

	if($options['append_sso_url'] == 1) {
		$params['redirect_url'] = site_url( '?auth=sso' );
	}

	if($options['append_client_id'] == 1) {
		$params['client_id'] = $options['client_id'];
	}

	$params = http_build_query( $params );
	wp_redirect( $options['jwt_server_url'] . '?' . $params );
	exit;
}

// Handle the callback from the server is there is one.
if ( isset( $_GET['code'] ) && ! empty( $_GET['code'] ) ) {

	$code = sanitize_text_field( $_GET['code'] );
	$decoded = null;
	try {
		$decoded = JWT::decode($code, $options['jwt_secret'], array('HS256'));
	} catch(\Exception $ex) {
		echo 'Single Sign On failed! Click here to go back to the home page: <a href="' . site_url() . '">Home</a>';
		exit();
	}

	$allowed_issuers = explode(',', $options['jwt_issuers']);
	for($i = 0; $i < count($allowed_issuers); $i++) {
		$allowed_issuers[$i] = trim($allowed_issuers[$i]);
	}

	if(count($allowed_issuers) > 0 && !in_array($decoded->iss, $allowed_issuers)) {
		exit("Single Sign On failed! Invalid issuer.");
	}

	$user_info = $decoded->user_info;

	$user_id   = username_exists( $user_info->user_login );
	if ( ! $user_id && email_exists( $user_info->user_email ) == false ) {

		// Does not have an account... Register and then log the user in
		$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
		$user_id         = wp_create_user( $user_info->user_login, $random_password, $user_info->user_email );

		// Trigger new user created action so that there can be modifications to what happens after the user is created.
		// This can be used to collect other information about the user.
		do_action( 'wpoc_user_created', $user_info, 1);

		wp_update_user([
			'ID' => $user_id,
			'display_name' => $user_info->user_name,
			'nickname' => $user_info->user_name,
			'first_name' => $user_info->first_name,
			'last_name' => $user_info->last_name,
		]);

		if($options['sync_roles'] == 1) {
			$user = get_user_by( 'login', $user_info->user_login );
			$user->set_role("");
			foreach($user_info->user_roles as $r) {
				$user->add_role($r);
			}
		}

		wp_clear_auth_cookie();
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );

		if ( is_user_logged_in() ) {
			wp_redirect( $user_redirect );
			exit;
		}

	} else {

		// Already Registered... Log the User In
		$random_password = __( 'User already exists.  Password inherited.' );
		$user            = get_user_by( 'login', $user_info->user_login );


		// Trigger action when a user is logged in. This will help allow extensions to be used without modifying the
		// core plugin.
		do_action( 'wpoc_user_login', $user_info, 1);

		wp_update_user([
			'ID' => $user->ID,
			'display_name' => $user_info->user_name,
			'user_email' => $user_info->user_email,
			'nickname' => $user_info->user_name,
			'first_name' => $user_info->first_name,
			'last_name' => $user_info->last_name,
		]);

		if($options['sync_roles'] == 1) {
			$user->set_role("");
			foreach($user_info->user_roles as $r) {
				$user->add_role($r);
			}
		}

		// User ID 1 is not allowed
		//if ( '1' === $user->ID ) {
		//	wp_die( 'For security reasons, this user can not use Single Sign On' );
		//}

		wp_clear_auth_cookie();
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID );

		if ( is_user_logged_in() ) {
			wp_redirect( $user_redirect );
			exit;
		}
	}


	exit( 'Single Sign On Failed.' );
}
