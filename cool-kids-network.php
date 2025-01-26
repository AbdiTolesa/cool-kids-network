<?php

/**
* Plugin Name: CoolKidsNetwork
* Description: A new type of game.
* Version: 1.0
* Author: Abdi Tolessa
* Author URI: https://abditsori.com
* Text Domain: cool-kids-network
 */

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload_packages.php';

CoolKidsNetwork\Hooks::register_hooks();
