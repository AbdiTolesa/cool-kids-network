<?php

/**
* Plugin Name: Cool Kids Network
* Description: A new type of game.
* Version: 1.0
* Author: Abdi Tolessa
* Author URI: https://abditsori.com
* Text Domain: cool-kids-network
 */

register_activation_hook( __FILE__, array( 'CoolKidsNetwork\Hooks', 'ckn_activate' ) );

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload_packages.php';

CoolKidsNetwork\Hooks::register_hooks();
