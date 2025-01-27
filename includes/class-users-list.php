<?php

class User_List {
	public static function get_user_data( $role ) {
		$wp_user_search  = new WP_User_Query( array( 'fields' => 'all' ) );
		$users           = $wp_user_search->get_results();
		$meta_key        = 'country';
		$users_with_meta = array();
		foreach ( $users as $user ) {
			$user_meta_value = get_user_meta( $user->ID, $meta_key, true );
			$user_data       = array(
				'name'    => $user->display_name,
				$meta_key => $user_meta_value,
			);
			if ( 'coolest_kid' === $role ) {
				$user_data = array_merge(
					$user_data,
					array(
						'email' => $user->user_email,
						'role'  => $user->roles,
					)
				);
			}
			$users_with_meta[] = $user_data;
		}
		return $users_with_meta;
	}
}
