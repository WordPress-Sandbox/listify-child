<?php
/**
 * Lost password form
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
 <?php wc_print_notices(); ?>

<div class="row">
	<div class="medium-10 medium-centered large-6 large-centered columns">

		<div class="login-register-container">
				
			<div class="account-forms-container">
				
				<form method="post" class="lost_reset_password">
				
					<?php if( 'lost_password' == $args['form'] ) : ?>
				
						<p class="lost-reset-pass-text"><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p>
				
						<p class="form-row">
							<input class="input-text" type="text" name="user_login" id="user_login" placeholder="<?php esc_html_e( 'Username or email address *', 'woocommerce' ); ?>" />
						</p>
				
					<?php else : ?>
				
						<p class="lost-reset-pass-text"><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce') ); ?></p>
				
						<p class="form-row">

							<input type="password" class="input-text" name="password_1" id="password_1" placeholder="<?php esc_html_e( 'New password *', 'woocommerce' ); ?>"/>
						</p>
						<p class="form-row">

							<input type="password" class="input-text" name="password_2" id="password_2"  placeholder="<?php esc_html_e( 'Re-enter new password *', 'woocommerce' ); ?>"/>
						</p>
				
						<input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
						<input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />
				
					<?php endif; ?>
				
					<div class="clear"></div>
					<?php do_action( 'woocommerce_lostpassword_form' ); ?>
					<p class="form-row">
						<input type="hidden" name="wc_reset_password" value="true" />
						<input type="submit" class="button" value="<?php echo 'lost_password' == $args['form'] ? __( 'Reset Password', 'woocommerce' ) : __( 'Save', 'woocommerce' ); ?>" />
					</p>

					<?php wp_nonce_field( $args['form'] ); ?>
				</form>
			</div><!-- .account-forms-container	-->
		
		</div><!-- .login-register-container-->
	</div><!-- .medium-8 .large-6-->
</div><!-- .row-->
	
			