<?php

defined( 'ABSPATH' ) or die( 'You are not allowed to call this script directly!' );

/**
 * Class WPOSSO_Admin
 *
 * @author Sebastian Schimpfhauser <seschi98@googlemail.com>
 * @package WP JWT Single Sign On Plugin
 */
class JWT_SSO_Admin {

	protected $option_name = 'jwt_sso_options';

	public static function init() {
		add_action( 'admin_init', array( new self, 'admin_init' ) );
		add_action( 'admin_menu', array( new self, 'add_page' ) );
	}

	public function admin_init() {
		register_setting( 'jwt_sso_options', $this->option_name, array( $this, 'validate' ) );
	}

	public function add_page() {
        if(current_user_can('jwt_sso')) {
            add_options_page( 'Single Sign On', 'Single Sign On', 'manage_options', 'jwt_sso_options', array(
                $this,
                'options_do_page'
            ) );   
        }
	}

	public function admin_head() {

        // Jquery Accordion
        wp_enqueue_style('jquery-ui');
		wp_enqueue_script( 'jquery-ui-accordion' );

		wp_enqueue_style( 'jwt_sso_admin' );
		wp_enqueue_script( 'jwt_sso_admin' );
	}

	public function options_do_page() {
		$options = get_option( $this->option_name );
		$this->admin_head();
		?>
        <div class="wrap">
            <h2>Single Sign On Configuration</h2>
            <p>
                When activated, this plugin adds a Single Sign On button to the login screen.
                <br/>
                <strong>NOTE:</strong> If you want to add a
                custom link anywhere in your theme simply link to
                <strong><?php echo site_url( '?auth=sso' ); ?></strong>
                if the user is not logged in.
            </p>
            <br />
            <div>
                <h3 id="ssso-configuration">JWT Settings</h3>
                <div>
                    <form method="post" action="options.php">
						<?php settings_fields( 'jwt_sso_options' ); ?>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">JWT Server URL</th>
                                <td>
                                    <input type="text" name="<?php echo $this->option_name ?>[jwt_server_url]"
                                           value="<?php echo $options["jwt_server_url"]; ?>"/>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">JWT Secret Key</th>
                                <td>
                                    <input type="text" name="<?php echo $this->option_name ?>[jwt_secret]"
                                           value="<?php echo $options["jwt_secret"]; ?>"/>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">Valid Issuers (Comma separated list)</th>
                                <td>
                                    <input type="text" name="<?php echo $this->option_name ?>[jwt_issuers]"
                                           value="<?php echo $options["jwt_issuers"]; ?>"/>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">Client Identifier</th>
                                <td>
                                    <input type="text" name="<?php echo $this->option_name ?>[client_id]"
                                        value="<?php echo $options["client_id"]; ?>"/>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">Append site's SSO URL to JWT Server</th>
                                <td>
                                    <input type="checkbox" name="<?php echo $this->option_name ?>[append_sso_url]"
                                        value="1" <?php echo $options["append_sso_url"] == 1 ? 'checked="checked"' : ''; ?> />
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">Append clientId to JWT Server</th>
                                <td>
                                    <input type="checkbox" name="<?php echo $this->option_name ?>[append_client_id]"
                                        value="1" <?php echo $options["append_client_id"] == 1 ? 'checked="checked"' : ''; ?> />
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">Sync roles with external system</th>
                                <td>
                                    <input type="checkbox" name="<?php echo $this->option_name ?>[sync_roles]"
                                        value="1" <?php echo $options["sync_roles"] == 1 ? 'checked="checked"' : ''; ?> />
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">Show SSO button on login page</th>
                                <td>
                                    <input type="checkbox" name="<?php echo $this->option_name ?>[show_sso_button_login_page]"
                                        value="1" <?php echo $options["show_sso_button_login_page"] == 1 ? 'checked="checked"' : ''; ?> />
                                </td>
                            </tr>

                        </table>

                        <p class="submit">
                            <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>"/>
                        </p>
                    </form>
                </div>
            </div>
        </div>
        <div style="clear:both;"></div>
		<?php
	}

	public function validate( $input ) {
		$input['append_sso_url'] = isset( $input['append_sso_url'] ) ? $input['append_sso_url'] : 0;
		$input['append_client_id'] = isset( $input['append_client_id'] ) ? $input['append_client_id'] : 0;
		$input['sync_roles'] = isset( $input['sync_roles'] ) ? $input['sync_roles'] : 0;
		$input['show_sso_button_login_page'] = isset( $input['show_sso_button_login_page'] ) ? $input['show_sso_button_login_page'] : 0;

		return $input;
	}
}

JWT_SSO_Admin::init();
