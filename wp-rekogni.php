<?php

/*
	Plugin Name: WP Rekogni
	Plugin URI: https://github.com/mt8/wp-rekogni
	Description: Assign Tags to Posts By Amazon Rekognition
	Version: 1.0.1
	Author:  mt8biz
	Author URI: https://mt8.biz
	Domain Path: /languages
	License: GPL2
 */
require __DIR__.'/vendor/autoload.php';

require_once plugin_dir_path( __FILE__ ) . '/class-wp-rekogni.php';
require_once plugin_dir_path( __FILE__ ) . '/class-wp-rekogni-admin.php';

$wp_rekogni = new WP_Rekogni();
$wp_rekogni->register_hooks();

function wp_rekogni_plugin_uninstall() {
	delete_option( WP_Rekogni::SLUG );
}
register_uninstall_hook( __FILE__, 'wp_rekogni_plugin_uninstall' );
