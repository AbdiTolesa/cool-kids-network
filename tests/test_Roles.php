<?php

class test_Roles extends WP_UnitTestCase {
	public function test_update_user_role() {
		CoolKidsNetwork\Roles::on_activation(); // Create roles
		$users = get_users(
			array(
				'role'   => 'administrator',
				'number' => 1,
			)
		);
		wp_set_current_user( reset( $users )->ID );

		$user_id = $this->factory->user->create( array( 'user_login' => 'jon.doe', 'user_email' => 'test@example.com', 'role' => 'cool_kid' ) );

		$request  = new WP_REST_Request( 'POST', '/ckn/v1/user_role' );
		$request->set_param( 'email', 'test@example.com' );
		$request->set_param( 'role', 'coolest_kid' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );
		$data = $response->get_data();
		$this->assertTrue( $data['success'] );

		$user = get_user_by( 'ID', $user_id );
		$this->assertContains( 'coolest_kid', $user->roles, 'If the user exists, update the user role.' );

		$request->set_param( 'email', 'test@example.com' );
		$request->set_param( 'role', 'subscriber' );
		$response = rest_get_server()->dispatch( $request );
		$this->assertEquals( 400, $response->get_status(), 'Avoid updating user to unsupported role.' );
		$data = $response->get_data();
		$this->assertFalse( $data['success'] );
		$this->assertEquals( $data['message'], 'Invalid role' );
	}
}
