<?php

namespace CoolKidsNetwork;

class Rest_Controller {
	/**
	 * Current API version.
	 */
	const VERSION = 1;

	/**
	 * The namespace of the REST API.
	 */
	const REST_API_NAMESPACE = 'ckn/v';

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace = self::REST_API_NAMESPACE . self::VERSION;

	/**
	 * The base of this controller's route.
	 */
	const BASE_ROUTE = 'user_role';

	/**
	 * The base of this controller's route.
	 *
	 * @var string
	 */
	protected $rest_base = self::BASE_ROUTE;

	/**
	 * @since 1.0
	 *
	 * @return void
	 */
	public function register_routes() {
		$route = '/' . $this->rest_base;

		register_rest_route(
			$this->namespace,
			$route,
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( 'CoolKidsNetwork\Roles', 'update_user_role' ),
				'permission_callback' => array( 'CoolKidsNetwork\Roles', 'check_required_role' ),
			)
		);
	}
}
