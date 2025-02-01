<?php

namespace CoolKidsNetwork\Models;

class Roles {
	/**
	 * Roles for Cool Kids Network.
	 */
	const COOL_KIDS_NETWORK_ROLES = array( 'cool_kid', 'cooler_kid', 'coolest_kid' );

	/**
	 * Checks required permission check for custom endpoints defined in this plugin.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public static function check_required_role() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Callback for REST route to update user role.
	 *
	 * @since 1.0
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public static function update_user_role( $request ) {
		$status         = 400;
		$request_params = $request->get_params();
		if ( empty( $request_params['email'] ) || empty( $request_params['role'] ) ) {
			return self::send_rest_response( $status, __( 'Missing required parameters', 'cool-kids-network' ), false );
		}

		if ( ! in_array( $request_params['role'], self::COOL_KIDS_NETWORK_ROLES, true ) ) {
			return self::send_rest_response( $status, __( 'Invalid role', 'cool-kids-network' ), false );
		}

		if ( ! get_role( $request_params['role'] ) ) {
			return self::send_rest_response( $status, __( 'Role does not exist', 'cool-kids-network' ), false );
		}

		$user = get_user_by( 'email', $request_params['email'] );

		if ( ! $user ) {
			return self::send_rest_response( $status, __( 'User not found', 'cool-kids-network' ), false );
		}
		$result = wp_update_user(
			array(
				'ID'   => $user->ID,
				'role' => $request_params['role'],
			)
		);
		if ( is_wp_error( $result ) ) {
			return self::send_rest_response( $status, __( 'Failed to update role', 'cool-kids-network' ), false );
		}
		$status = 200;
		return self::send_rest_response( $status, __( 'Role updated', 'cool-kids-network' ), true );
	}

	/**
	 * @since 1.0
	 *
	 * @param int    $status
	 * @param string $message
	 * @param bool   $success
	 *
	 * @return \WP_REST_Response
	 */
	private static function send_rest_response( $status, $message, $success ) {
		return new \WP_REST_Response(
			array(
				'success' => $success,
				'message' => $message,
			),
			$status
		);
	}

	/**
	 * Callback for plugin activation.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function on_activation() {
		add_role(
			'cool_kid',
			'Cool Kid'
		);
		add_role(
			'cooler_kid',
			'Cooler Kid',
			array(
				'view_other_users_name'    => true,
				'view_other_users_country' => true,
			)
		);
		add_role(
			'coolest_kid',
			'Coolest Kid',
			array(
				'view_other_users_name'    => true,
				'view_other_users_country' => true,
				'view_other_users_email'   => true,
				'view_other_users_role'    => true,
			)
		);
	}
}
