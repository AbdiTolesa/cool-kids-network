<?php

namespace CoolKidsNetwork;

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
	 * @return void
	 */
	public static function update_user_role( $request ) {
		$request_params = $request->get_params();
		if ( empty( $request_params['email'] ) || empty( $request_params['role'] ) ) {
			wp_send_json_error( esc_html__( 'Missing required parameters.', 'cool-kids-network' ) );
		}

		if ( ! in_array( $request_params['role'], self::COOL_KIDS_NETWORK_ROLES, true ) ) {
			wp_send_json_error( esc_html__( 'Invalid role.', 'cool-kids-network' ) );
		}

		if ( ! get_role( $request_params['role'] ) ) {
			wp_send_json_error( esc_html__( 'Role does not exist.', 'cool-kids-network' ) );
		}

		$user = get_user_by( 'email', $request_params['email'] );

		if ( ! $user ) {
			wp_send_json_error( esc_html__( 'User not found.', 'cool-kids-network' ) );
		}
		$result = wp_update_user(
			array(
				'ID'   => $user->ID,
				'role' => $request_params['role'],
			)
		);
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( esc_html__( 'Failed to update role.', 'cool-kids-network' ) );
		}
		wp_send_json_success( esc_html__( 'Role updated.', 'cool-kids-network' ) );
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
