<?php
/**
 * Install and config WordPress with Lando settings
 */

$lando_root = getenv( 'LANDO_WEBROOT' );

if ( ! file_exists( $lando_root ) && ! is_dir( $lando_root ) ) {
	$lando_info = json_decode( getenv( 'LANDO_INFO' ), true );

	// Create webroot directory.
	shell_exec( "mkdir $lando_root;" );

	// Download WordPress with WP-CLI.
	//$locale = getenv( 'WP_LOCALE' );
	echo "Downloading WordPress...\n";
	//echo shell_exec( 'wp core download --locale="' . $locale . '" --path=' . $lando_root );
	//echo shell_exec( 'wp core download --path=' . $lando_root );
	echo shell_exec( 'composer install' );

	// Create the wp-config.php file.
	$db_host   = $lando_info['database']['internal_connection']['host'];
	$db_name   = $lando_info['database']['creds']['database'];
	$db_user   = $lando_info['database']['creds']['user'];
	$db_pass   = $lando_info['database']['creds']['password'];
	$db_prefix = getenv( 'WP_PREFIX' );
	echo "WordPress config...\n";
	$config_wp = 'wp config create --dbprefix=' . $db_prefix . ' --dbhost=' . $db_host . ' --dbname=' . $db_name . ' --dbuser=' . $db_user . ' --dbpass=' . $db_pass . ' --path=' . $lando_root;
	shell_exec( $config_wp );

	// Install WordPress.
	echo "Instaling WordPress..\n";
	$url         = $lando_info['appserver_nginx']['urls'][1]; // https.
	$title       = getenv( 'SITE_TITLE' );
	$admin_user  = getenv( 'SITE_ADMIN_USER' );
	$admin_pass  = getenv( 'SITE_ADMIN_PASSWORD' );
	$admin_email = getenv( 'SITE_ADMIN_EMAIL' );
	$install_wp  = 'wp core install --url="' . $url . '" --title="' . $title . '" --admin_user="' . $admin_user . '" --admin_password="' . $admin_pass . '" --admin_email="' . $admin_email . '" --path=' . $lando_root;
	shell_exec( $install_wp );
	echo "OH YEAHH!!! Your environment is done";
}