<?php
/**
 * Plugin Name: JWT Single Sign on
 * Plugin URI: https://github.com/seschi98/wordpress-jwt-single-sign-on
 * Version: 1.2.0
 * Description: Provides Simple Single Sign On integration with the use of JWT
 * Author: seschi98
 * Author URI: https://github.com/seschi98/
 */

defined( 'ABSPATH' ) or die( 'You are not allowed to call this script directly!' );

if ( ! defined( 'JWT_SSO_FILE' ) ) {
	define( 'JWT_SSO_FILE', plugin_dir_path( __FILE__ ) );
}

require JWT_SSO_FILE . '/vendor/autoload.php';

// Require the main plugin clas
require_once( JWT_SSO_FILE . '/src/class-jwt-sso-client.php' );

add_action( "wp_loaded", '_jwt_sso_register_files' );
function _jwt_sso_register_files() {
	wp_register_style( 'jwt_sso_admin', plugins_url( '/assets/css/admin.css', __FILE__ ) );
	wp_register_style( 'jquery-ui', plugins_url( '/assets/css/jquery-ui.css', __FILE__ ) );
	wp_register_script( 'jwt_sso_admin', plugins_url( '/assets/js/admin.js', __FILE__ ) );
}

add_action( 'admin_menu', array( new JWT_SSO_Client, 'plugin_init' ) );
register_activation_hook( __FILE__, array( new JWT_SSO_Client, 'setup' ) );
register_activation_hook( __FILE__, array( new JWT_SSO_Client, 'upgrade' ) );