<?php
/**
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 *
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
global $bnd_login_redirect;
$theme_url = buynowdepot_get_theme_url();
if (! is_user_logged_in()) :
    $style = buynowdepot_get_option('button_style', 'button');

    ?>
<div class="container">
<div class="login-page">
	<div class="row">
		<div class="col-sm-6">
			<img src="<?php echo $theme_url?>/img/pizza-bg.jpg" class="side-image"/>
		</div>
		<div class="px-5 col-sm-6 ml-auto">
			<h2 class="text-dark my-0">Welcome to <?php echo get_bloginfo("name"); ?></h2>
			<p class="text-50">Please Sign-in to continue</p>
			<?php buynowdepot_print_errors(); ?>
			<form class="mt-5 mb-4" id="bnd_login_form" class="bnd_form"
			action="" method="post">
				<div class="form-group">
					<label for="bnd_user_login"><?php _e( 'Email', 'bnd-flex-order-delivery' ); ?></label>
					<input type="email" placeholder="Enter Email" class="form-control"
						id="bnd_user_login" aria-describedby="emailHelp"
						name="bnd_user_login">
				</div>
				<div class="form-group">
					<label for="bnd_user_pass"><?php _e( 'Password', 'bnd-order-delivery' ); ?></label>
					<input placeholder="Enter Password"
						name="bnd_user_pass" class="form-control" id="user_pass" type="password">
				</div>
				<div class="form-group">
					<label for="rememberme" class="text-dark"></label> <input
						name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember Me', 'bnd-flex-order-delivery' ); ?>
					</div>
				<input type="submit" class="btn btn-primary btn-lg btn-block"
					value="SIGN IN" /> <input type="hidden" name="bnd_redirect"
					value="<?php echo buynowdepot_get_page_url("bnd-menuitems"); ?>" /> <input
					type="hidden" name="bnd_login_nonce"
					value="<?php echo wp_create_nonce( 'bnd-login-nonce' ); ?>" /> <input
					type="hidden" name="bnd_action" value="user_login" />
			</form>
			<div class="align-items-center justify-content-center">
				<a href="<?php echo site_url( 'bnd-forgot-password' ); ?>"
					class="text-decoration-none">
					<?php _e( 'Forgot your password?', 'bnd-flex-order-delivery' ); ?>
				</a><br/><a href="<?php echo site_url( 'bnd-signup' ); ?>">
					Don't have an account? Sign up
				</a>
			</div>
		</div>
	</div>
</div>
<?php else : ?>

	<?php do_action( 'bnd_login_form_logged_in' ); ?>

<?php endif; ?>
</div>