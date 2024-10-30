<?php

defined( 'ABSPATH' ) or die( 'You are not allowed to call this script directly!' );

/**
 * Main Class
 *
 * @author Sebastian Schimpfhauser <seschi98@googlemail.com>
 * @package WP JWT Single Sign On Plugin
 */
class JWT_SSO_Client {

	/** Version */
	public $version = "1.0.0";

	/** Server Instance */
	public static $_instance = null;

	/** Default Settings */
	protected $default_settings = array(
		'jwt_server_url'				=> '',
		'jwt_secret'					=> '',
		'jwt_issuers'					=> '',
		'client_id'						=> '',
		'append_sso_url'				=> 0,
		'append_client_id'				=> 0,
		'sync_roles'					=> 1,
		'show_sso_button_login_page'	=> 1,
	);

	function __construct() {

		add_action( "init", array( __CLASS__, "includes" ) );
	}

	/**
	 * populate the instance if the plugin for extendability
	 * @return object plugin instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * plugin includes called during load of plugin
	 * @return void
	 */
	public static function includes() {
		require_once( JWT_SSO_FILE . '/src/functions.php' );
		require_once( JWT_SSO_FILE . '/src/admin-options.php' );
		require_once( JWT_SSO_FILE . '/src/rewrites.php' );
		require_once( JWT_SSO_FILE . '/src/filters.php' );
	}

	/**
	 * Plugin Setup
	 */
	public function setup() {

		// Sync default options with existing options so new options are automatically added when the plugin is updated
		$options = get_option( "jwt_sso_options" );
		foreach($default_settings as $key => $value) {
			if(!array_key_exists($key, $options)) {
				$options[$key] = $value;
			}
		}
		update_option( "jwt_sso_options", $options );

		$role = get_role( 'administrator' );
  		$role->add_cap( 'jwt_sso' );

		$this->install();
	}

	/**
	 * Plugin Initializer
	 */
	public function plugin_init() {
	}

	/**
	 * Plugin Install
	 */
	public function install() {
	}

	/**
	 * Plugin Upgrade
	 */
	public function upgrade() {
	}

}

function _JWT_SSO() {
	return JWT_SSO_Client::instance();
}

$GLOBAL['JWT_SSO'] = _JWT_SSO();