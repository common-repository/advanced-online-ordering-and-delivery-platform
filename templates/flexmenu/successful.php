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
$theme_url =  buynowdepot_get_theme_url();
?>
<div class="d-none">
	<div class="bg-primary p-3 d-flex align-items-center">
		<a class="toggle togglew toggle-2" href="#"><span></span></a>
		<h4 class="font-weight-bold m-0 text-white">Thank you :)</h4>
	</div>
</div>
<div
	class="py-5 bnd-coming-soon d-flex justify-content-center align-items-center">
	<div class="col-md-6">
		<div class="text-center pb-3">
			<h1 class="font-weight-bold">Your order has been
				successful</h1>
			<p>Your order number is <strong><?php echo Bnd_Flex_Order_Delivery_Session::instance()->get("order_number");?></strong>. An email has been send to your email address mentioning the details of your order.</p>
			<?php if (is_user_logged_in()) {?>			
			<p>
				You can check your order status in <a href="/bnd-my-order"
					class="font-weight-bold text-decoration-none text-primary">My
					Orders</a> about next steps information.
			</p>
			<?php } ?>
		</div>
		<!-- continue -->
		<div class="bg-white rounded text-center p-4 shadow-sm">
			<h1 class="display-1 mb-4">ðŸŽ‰</h1>
			<h6 class="font-weight-bold mb-2">Preparing your order</h6>
			<p class="small text-muted">Your order will be prepared and will
				come soon</p>
			<a href="<?php echo buynowdepot_get_page_url('bnd-trackorder').'&orderno='.Bnd_Flex_Order_Delivery_Session::instance()->get("order_number"); ?>"
				class="btn rounded btn-primary btn-lg btn-block">Track My Order</a>
		</div>
	</div>
</div>