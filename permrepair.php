<?php
/*
Plugin Name: PermRepair - Fix Permissions
Plugin URI: https://github.com/khalequzzaman17/permrepair
Description: A simple plugin to fix file and directory permissions on your site.
Version: 1.0
Author: Khalequzzaman
Author URI: https://github.com/khalequzzaman17
License: GPL2
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

// register a custom menu page
function fix_permissions_menu_page() {
	add_menu_page(
		'Fix Permissions', // page title
		'Fix Permissions', // menu title
		'manage_options', // capability
		'fix-permissions', // menu slug
		'fix_permissions_page_content', // function to display the page
		'dashicons-admin-tools', // icon url
		20 // position
	);
}
add_action( 'admin_menu', 'fix_permissions_menu_page' );

// display the page content
function fix_permissions_page_content() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<h1>Fix Permissions</h1>';
	echo '<p>Use the button below to fix the file and directory permissions on your site:</p>';
	echo '<form method="post">';
	echo '<input type="submit" name="fix_permissions" value="Fix Permissions" class="button-secondary" />';
	echo '</form>';
	echo '</div>';
}

// fix the file and directory permissions
function fix_permissions() {
	if ( isset( $_POST['fix_permissions'] ) ) {
		// get the site's root directory
		$root_dir = ABSPATH;
		// get all files and directories in the root directory
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $root_dir ),
			RecursiveIteratorIterator::SELF_FIRST
		);
		// set the desired permissions
		$permissions = 0644;
		// fix the permissions for each file and directory
		foreach ( $files as $file ) {
			if ( !$file->isDir() ) {
				chmod( $file, $permissions );
			}
		}
		// show a success message
		echo '<div class="notice notice-success is-dismissible"><p>The file and directory permissions have been fixed.</p></div>';
	}
}
add_action( 'admin_init', 'fix_permissions' );
