<?php

/*
	Plugin Name: WP Rekogni
	Plugin URI: https://mt8.biz
	Description: Assign Tags to Posts By Amazon Rekognition
	Version: 1.0
	Author:  mt8biz
	Author URI: https://mt8.biz
	Domain Path: /languages
	License: GPL2
 */
require __DIR__.'/aws.phar';

require_once plugin_dir_path( __FILE__ ) . '/class-wp-rekogni.php';
require_once plugin_dir_path( __FILE__ ) . '/class-wp-rekogni-admin.php';

$wp_rekogni = new WP_Rekogni();
$wp_rekogni->register_hooks();


function wp_rekogni_plugin_activate() {
	$option_keys = WP_Rekogni::get_option_keys();
	foreach ( $option_keys as $key ) {
		if ( get_option( $key ) == '' ) {
			update_option( $key, '' );
		}
	}
}
register_activation_hook( __FILE__, 'wp_rekogni_plugin_activate' );

function wp_rekogni_plugin_uninstall() {
	$option_keys = WP_Rekogni::get_option_keys();
	foreach ( $option_keys as $key ) {
		delete_option( $key );
	}
}
register_uninstall_hook( __FILE__, 'wp_rekogni_plugin_uninstall' );
