<?php
/**
 * File: rewrites.php
 *
 * @author Sebastian Schimpfhauser <seschi98@googlemail.com>
 * @package WP JWT Single Sign On Plugin
 */
defined( 'ABSPATH' ) or die( 'You are not allowed to call this script directly!' );

/**
 * Class JWT_SSO_Rewrites
 *
 */
class JWT_SSO_Rewrites {

	function create_rewrite_rules( $rules ) {
		global $wp_rewrite;
		$newRule  = array( 'auth/(.+)' => 'index.php?auth=' . $wp_rewrite->preg_index( 1 ) );
		$newRules = $newRule + $rules;

		return $newRules;
	}

	function add_query_vars( $qvars ) {
		$qvars[] = 'auth';

		return $qvars;
	}

	function flush_rewrite_rules() {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}

	function template_redirect_intercept() {
		global $wp_query;
		if ( $wp_query->get( 'auth' ) && $wp_query->get( 'auth' ) == 'sso' ) {
			require_once( dirname( dirname( __FILE__ ) ) . '/src/callback.php' );
			exit;
		}
	}
}

$JWT_SSO_Rewrites = new JWT_SSO_Rewrites();
add_filter( 'rewrite_rules_array', array( $JWT_SSO_Rewrites, 'create_rewrite_rules' ) );
add_filter( 'query_vars', array( $JWT_SSO_Rewrites, 'add_query_vars' ) );
add_filter( 'wp_loaded', array( $JWT_SSO_Rewrites, 'flush_rewrite_rules' ) );
add_action( 'template_redirect', array( $JWT_SSO_Rewrites, 'template_redirect_intercept' ) );