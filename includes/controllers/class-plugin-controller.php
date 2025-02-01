<?php
namespace CoolKidsNetwork\Controllers;

class Plugin_Controller {
	/**
	 * The current plugin version.
	 */
	const PLUGIN_VERSION = '1.0';

	/**
	 * Boot the plugin.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function boot_plugin() {
		Hooks_Controller::register_hooks();
		Shortcodes_Controller::register_shortcodes();
	}

	/**
	 * Enqueue the plugin styles.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function enqueue_styles() {
		wp_enqueue_style( 'ckn-styles', CKN_PLUGIN_URL . 'css/output.css', array(), self::PLUGIN_VERSION );
	}
}
