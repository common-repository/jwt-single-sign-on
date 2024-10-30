=== JWT Single Sign On ===
Contributors: seschi98
Tags: sso, jwt, login, singlesignon, single-sign-on, token
Requires at least: 3.0.1
Tested up to: 5.1
Requires PHP: 5.2
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
This plugin allows you to connect your existing authentication mechanism to Wordpress with the use of JWT.
 
== Description ==

This plugin allows signing in users via JSON Web Token (JWT) in Wordpress. It is used to allow users from another website/service/etc. to securely use their credentials for the Wordpress site. 
Sample use case: There is an existing user database (e.g. in an intranet environment) and the users should be able to login to an external site that is powered by wordpress, without having to remember another password. This mechanism is also called Single Sign On (SSO). 
Please note that the user information and role mappings are updated each time the user logs in via SSO. If you do not want to sync the roles from your existing system to wordpress, you can disable the functionality via the settings page.
 
== Installation ==
 
1. Upload the downloaded plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the administration dashboard, go to "Settings" > "Single Sign On" and configure the JWT settings.
 
== Frequently Asked Questions ==
 
= Where can I configure the plugin? =
 
In the administration dashboard, go to "Settings" > "Single Sign On".

= Can I request a feature? =

Yes! You can create an issue on GitHub. The repository can be found under https://github.com/seschi98/wordpress-jwt-single-sign-on
 
== Screenshots ==
 
There are no screenshots for this plugin (yet).


== Changelog ==
 
= 1.2.0 =
* Adding some validation so that default values for new plugin settings are automatically populated when a plugin update is performed.
* Adding the possibility to disable the SSO button on the login page.

= 1.1.1 =
* Fixing the plugin name in readme.txt so it is compliant to the Wordpress specification

= 1.1.0 =
* Updating readme file to provide more information
* Updating admin settings page to give more information about the setup process
* Removed dependecy for jQueryUI CDN
* Adding a proper readme.txt file for Wordpress Plugin Directory
* Adding the ability to control whether the clientId and site's SSO address should be appended to the JWT Server URL
* Adding the ability to disable role synchronisation
 
= 1.0.0 =
* Initial Proof-Of-Concept for this plugin.
 

== JWT Server ==
 
You will need to implement an endpoint on your website/app that has access to the logged 
in user (via login form or Kerberos-like authentication etc). That endpoint needs to create
and sign a JWT with the HS256 algorithm and the below described information in the payload.
It then has to redirect the user back to the Wordpress site `https://example.org/?auth=jwt&code={jwt}`
where `{jwt}` is the signed JWT as string representation.

``
{
  "iat": 1516239022,
  "iss": "issuer_name",
  "user_info": {
    "user_login": "johndoe",
    "user_name": "John Doe",
    "first_name": "John",
    "last_name": "Doe",
    "user_email": "johndoe@example.org",
    "user_roles": ["role1"]
  }
}
``