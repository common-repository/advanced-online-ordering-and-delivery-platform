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
    		<div class="col-sm-6" style="padding:0">
    			<img src="<?php echo $theme_url?>/img/pizza-bg.jpg" class="side-image"/>
    		</div>
    		<div class="px-5 col-sm-6">
				<h2 class="mb-3">Verify your phone number</h2>
				<h6 class="text-black-50">Enter your OTP code here</h6>
				<form action="home.html">
					<div class="row my-5 mx-0 otp">
						<div class="col pr-1 pl-0">
							<input type="text" value="4" class="form-control form-control-lg">
						</div>
						<div class="col px-2">
							<input type="text" value="0" class="form-control form-control-lg">
						</div>
						<div class="col px-2">
							<input type="text" value="8" class="form-control form-control-lg">
						</div>
						<div class="col pl-1 pr-0">
							<input type="text" value="0" class="form-control form-control-lg">
						</div>
					</div>
					<button class="btn btn-lg btn-primary btn-block">Verify Now</button>
				</form>
			</div>
			<div class="new-acc d-flex align-items-center justify-content-center">
				<a href="login.html">
					<p class="text-center m-0">Already an account? Sign in</p>
				</a>
			</div>
		</div>
	</div>
</div>