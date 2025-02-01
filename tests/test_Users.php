<?php

/**
 * @since 1.0
 */
class test_Users extends WP_UnitTestCase {
	public function test_generate_username_from_name() {
		$first_name = 'John';
		$last_name  = 'Doe';
		$username   = CoolKidsNetwork\Models\Users::generate_username_from_name( $first_name, $last_name );

		$this->assertEquals( 'john.doe', $username, 'If the username does not exist, return the first name and last name separated by a period.' );

		$user_id = $this->factory->user->create( array( 'user_login' => $username ) );
		$username = CoolKidsNetwork\Models\Users::generate_username_from_name( $first_name, $last_name );

		$this->assertEquals( 'john.doe1', $username, 'If the username exists, append a number to the username.' );
	}
}
