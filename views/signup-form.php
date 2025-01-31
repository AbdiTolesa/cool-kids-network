<?php
/**
 * Displays the signup form.
 *
 * @since 1.0
 */
if ( isset( $_REQUEST['error'] ) && 'email_exists' === $_REQUEST['error'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
	?>
	<p class="mt-2 text-sm text-red-600"><?php esc_html_e( 'Oops! User with that email already exists!', 'cool-kids-network' ); ?></p>
	<?php
}
?>

<form method="post" class="mx-auto">
	<?php wp_nonce_field( 'ckn_signup_action' ); ?>
	<div class="mb-5">
		<label for="ckn-email" class="block mb-2 text-sm font-medium text-gray-900"><?php esc_html_e( 'Your email', 'cool-kids-network' ); ?></label>
		<input type="email" id="ckn-email"  name="ckn-email"  class="max-w-sm bg-gray-50 border border-gray-500 text-gray-900 placeholder-gray-700 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5" placeholder="name@example.com" required />
	</div>
	<button type="submit" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"><?php esc_html_e( 'Register new account', 'cool-kids-network' ); ?></button>
</form>

