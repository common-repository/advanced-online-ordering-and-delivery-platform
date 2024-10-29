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
$cart = new Bnd_Flex_Order_Delivery_Cart();
$cart->empty_cart();
?>
<div class="d-none">
	<div class="bg-primary p-3 d-flex align-items-center">
		<a class="toggle togglew toggle-2" href="#"><span></span></a>
		<h4 class="font-weight-bold m-0 text-white">Logged out</h4>
	</div>
</div>
<div
	class="py-5 bnd-coming-soon d-flex justify-content-center align-items-center">
	<div class="col-md-6">
		<div class="text-center pb-3">
			<h1 class="display-1 mb-4">ðŸŽ‰</h1>
			<h1 class="font-weight-bold">You have been logged out successfully. </h1>
			<p>
				Thank you for ordering from <strong><?php echo get_bloginfo("name"); ?></strong>. Please visit again.
			</p>
		</div>
	</div>
</div>