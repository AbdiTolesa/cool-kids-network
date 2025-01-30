<?php
/**
 * Displays the signup form.
 *
 * @since 1.0
 */
if ( isset( $_REQUEST['error'] ) && 'email_exists' === $_REQUEST['error'] ) {
	?>
	<p class="error"><?php esc_html_e( 'User with that email already exists.', 'cool-kids-network' ); ?></p>
	<?php
}
?>
<form action="" method="post">
	<?php wp_nonce_field( 'ckn_signup_action' ); ?>
	<label for="ckn-email"><?php esc_html_e( 'Email:', 'cool-kids-network' ); ?></label>
	<input type="email" name="ckn-email" id="ckn-email" required>
	<input type="submit" value="<?php esc_html_e( 'Sign Up', 'cool-kids-network' ); ?>">
</form>
