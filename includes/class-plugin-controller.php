<?php
namespace CoolKidsNetwork;

class Plugin_Controller {
	/**
	 * The current plugin version.
	 */
	const PLUGIN_VERSION = 1.0;

	public static function boot_plugin() {
		Hooks_Controller::register_hooks();
		Shortcodes_Controller::register_shortcodes();
	}

	public static function enqueue_styles() {
		wp_enqueue_style( 'ckn-styles', CKN_PLUGIN_URL . 'css/output.css', array(), self::PLUGIN_VERSION );
	}
}
