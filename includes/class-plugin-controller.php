<?php
namespace CoolKidsNetwork;

class Plugin_Controller {
	public static function boot_plugin() {
		Hooks_Controller::register_hooks();
		Shortcodes_Controller::register_shortcodes();
	}
}
