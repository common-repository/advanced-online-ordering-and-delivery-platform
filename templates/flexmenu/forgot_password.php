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
$theme_url = buynowdepot_get_theme_url();
?>
<div class="container">
	<div class="login-page">
		<div class="row">
    		<div class="col-sm-6">
    			<img src="<?php echo $theme_url?>/img/pizza-bg.jpg" class="side-image"/>
    		</div>
    		<div class="px-5 col-sm-6">
    			<div class="px-5 col-10 mx-auto">
    				<h2>Forgot password</h2>
    				<p>Enter your email address below and we'll send you an email with
    					instructions on how to change your password</p>
    				<form action="login.html" class="mt-5 mb-4">
    					<div class="form-group">
    						<label for="exampleInputEmail1">Email</label> <input type="email"
    							class="form-control" id="exampleInputEmail1"
    							aria-describedby="emailHelp">
    					</div>
    					<button class="btn btn-primary btn-lg btn-block">Send</button>
    				</form>
    			</div>
    			<div
    				class="new-acc d-flex align-items-center justify-content-center">
    				<a href="<?php echo site_url( 'bnd-login' ); ?>">
    					<p class="text-center m-0">Already an account? Sign in</p>
    				</a>
    			</div>
    		</div>
    	</div>
	</div>
</div>