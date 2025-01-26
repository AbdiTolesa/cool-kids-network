<?php

namespace CoolKidsNetwork;

class Custom_Endpoints {

	/**
	 * @return void
	 */
	public static function register_endpoints() {
		register_rest_route(
			'wc/v3',
			'/stoneline/variant/(?P<sku>[a-zA-Z0-9\.]+)',
			array(
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => array( 'CoolKidsNetwork\Variable_Products', 'schedule_delete_product_variation' ),
				'permission_callback' => array( 'CoolKidsNetwork\Helper_Functions', 'check_required_role' ),
			)
		);
	}
}
