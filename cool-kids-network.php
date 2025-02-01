<?php
/**
* Plugin Name: Cool Kids Network
* Description: A new type of game.
* Version: 1.0
* Author: Abdi Tolessa
* Author URI: https://abditsori.com
* Text Domain: cool-kids-network
 */

register_activation_hook( __FILE__, array( 'CoolKidsNetwork\Models\Roles', 'on_activation' ) );

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload_packages.php';

CoolKidsNetwork\Controllers\Plugin_Controller::boot_plugin();

if ( ! defined( 'CKN_PLUGIN_DIR' ) ) {
	define( 'CKN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CKN_VIEWS_DIR' ) ) {
	define( 'CKN_VIEWS_DIR', CKN_PLUGIN_DIR . 'views/' );
}

if ( ! defined( 'CKN_PLUGIN_URL' ) ) {
	define( 'CKN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
