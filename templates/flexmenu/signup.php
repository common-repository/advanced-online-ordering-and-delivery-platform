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
global $bnd_register_redirect;
$theme_url = buynowdepot_get_theme_url();
if (! is_user_logged_in()) :
    $style = buynowdepot_get_option('button_style', 'button');
?>
<div class="container">
    <div class="login-page">
    	<div class="row">
    		<div class="col-sm-6" style="padding:0">
    			<img src="<?php echo $theme_url?>/img/pizza-bg.jpg" class="side-image"/>
    		</div>
    		<div class="px-5 col-sm-6">
    			<h2 class="text-dark my-0">Welcome to <?php echo get_bloginfo("name"); ?></h2>
    			<p class="text-50">Sign up to continue</p>
    				<?php do_action( 'buynowdepot_print_errors' ); ?>
    				<form class="mt-5 mb-4" action="" method="post" id="wp_signup_form">
    				<div class="form-group">
    					<label for="bnd-user-email" class="text-dark">Email</label> <input
    						type="text" placeholder="Enter Email" class="form-control required"
    						id="bnd-user-email" name="bnd_user_email" aria-describedby="nameHelp">
    				</div>
    				<div class="form-group">
    					<label for="bnd-user-pass" class="text-dark">Password</label>
    					<input type="password" placeholder="Enter Password"
    						class="form-control required" id="bnd_user_pass" name="bnd_user_pass">
    				</div>
    				<div class="form-group">
    					<label for="bnd-user-pass2" class="text-dark">Password</label>
    					<input type="password" placeholder="Enter Password"
    						class="form-control required" id="bnd_user_pass2" name="bnd_user_pass2">
    				</div>
    				<div class="form-group">
    					<input name="terms" id="terms" type="checkbox" value="Yes"> <label
    					for="terms">I agree to the Terms of Service</label>
    				</div>
    				<div class="py-2 form-group">
    					 <input
    					type="hidden" name="bnd_honeypot" value="" /> <input type="hidden"
    					name="bnd_action" value="user_register" /> <input type="hidden"
    					name="bnd_redirect"
    					value="<?php echo buynowdepot_get_page_url("bnd-menuitems"); ?>" /> <input
    					type="submit" class="btn btn-primary btn-lg btn-block"
    					value="SIGN UP" name="bnd_register_submit"/>
    				</div>
    			</form>
    			<div class="new-acc d-flex align-items-center justify-content-center">
            			<a href="<?php echo site_url( 'bnd-login' ); ?>">
            				<p class="text-center m-0">Already an account? Sign in</p>
            			</a>
    			</div>
    		</div>
    	</div>
    </div>
</div>
<?php else : ?>

	<?php do_action( 'bnd_register_form_logged_in' ); ?>

<?php endif; ?>