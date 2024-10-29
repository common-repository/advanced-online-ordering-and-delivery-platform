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
$model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
$merchant = $model->getMerchantAddress();
$order = null;
$lineItems = null;
$showError = '';
$order_number = '';
if (isset($_GET["orderno"])) {
    $order_number = sanitize_text_field($_GET["orderno"]);
    $orderDetails = $repository->getOrderDetails($order_number);
    if (isset($orderDetails) && count($orderDetails) > 0) {
        $order = $orderDetails["order"];
        $lineItems = $orderDetails["lineItems"];
    } else {
        $showError = "This order no. is invalid";
    }
}
?>
<div class="d-none">
	<div class="bg-primary border-bottom p-3 d-flex align-items-center">
		<a class="toggle togglew toggle-2" href="#"><span></span></a>
		<h4 class="font-weight-bold m-0 text-white">Track</h4>
	</div>
</div>
<?php
include_once 'category-header.php';
?>
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="input-group mt-4 mb-4">
				<input type="text"
					class="form-control form-control-lg border-right-0"
					id="orderNumber" value="<?php echo $order_number;?>"
					placeholder="Search by order number">
				<div class="input-group-prepend">
					<div
						class="btn input-group-text bg-white border-left-0 text-primary"
						onclick="searchOrder()">
						<i class="fas fa-search"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div
				class="section bg-white bnd-track-order-page position-relative">
				<iframe class="position-absolute"
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57248.18789146089!2d-80.24175829624484!3d26.261282276363378!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d904bf84ba5953%3A0x6958787ffe04cd12!2sPompano%20Beach%2C%20FL%2033063%2C%20USA!5e0!3m2!1sen!2suk!4v1619812098048!5m2!1sen!2suk"
					width="100%" height="676" style="border: 0;" allowfullscreen="yes"
					loading="lazy"></iframe>
	<?php if ($order!=null) { ?>
	<div class="container pt-5 pb-5">
    		<div class="row d-flex align-items-center">
    			<div class="col-md-6 text-center pb-4">
    				<div class="bnd-point mx-auto"></div>
    			</div>
    			<div class="col-md-6">
    				<div class="bg-white p-4 shadow-lg rounded mb-2">
    					<div class="mb-2">
    						<small>Order #<?php echo $order->clid?><a
    							class="float-right font-weight-bold" href="#"><i
    								class="feather-help-circle"></i> HELP</a></small>
    					</div>
    					<h6 class="mb-1 mt-1">
    						<a
    							href="<?php echo buynowdepot_get_page_url('bnd-menuitems'); ?>"
    							class="text-black"><?php echo $merchant->name;?>
    			</a>
    					</h6>
    					<p class="text-gray mb-0">
    						<i class="feather-clock"></i> 04:19 PM | <?php echo count($lineItems)." Item(s)";?> | <?php echo buynowdepot_format_price($order->total);?>
    		</p>
    				</div>
    
    				<div class="bg-white p-4 shadow-lg rounded">
    					<div class="bnd-track-order-detail po">
    						<h5 class="mt-0 mb-3">Order Details</h5>
    						<div class="row">
    							<div class="col-md-5">
    								<small>FROM</small>
    								<h6 class="mb-1 mt-1">
    									<a
    										href="<?php echo buynowdepot_get_page_url('bnd-menuitems'); ?>"
    										class="text-black"><i class="feather-shopping-cart"></i> <?php echo $merchant->name;?> </a>
    								</h6>
    								<p class="text-gray mb-5"><?php echo ($merchant->address1.'<br>'.$merchant->city.' '.$merchant->state.' '.$merchant->zip);?></p>
    								<small>DELIVER TO</small>
    								<h6 class="mb-1 mt-1">
    									<span class="text-black"><i class="feather-map-pin"></i>
    										Other </span>
    								</h6>
    								<p class="text-gray mb-0"><?php echo $order->note; ?></p>
    							</div>
    							<div class="col-md-7">
    								<div class="mb-2">
    									<small><i class="feather-list"></i>ITEMS</small>
    								</div>
    					<?php foreach ($lineItems as $line) {?>
    					<p class="mb-2">
    									<i class="feather-ui-press text-danger food-item">Â·</i>
    						<?php echo $line["item"]->name;?> x 1 <span
    										class="float-right text-secondary"><?php echo buynowdepot_format_price($line["line"]->price_with_modification);?></span>
    								</p>
    					<?php } ?>
    					<hr>
    								<p class="mb-0 font-weight-bold text-black">
    									Subtotal <span class="float-right text-secondary"><?php echo buynowdepot_format_price($order->sub_total);?></span>
    								</p>
    								<p class="mb-0 font-weight-bold text-black">
    									Delivery Charge <span class="float-right text-secondary"><?php echo buynowdepot_format_price($order->delivery_charge);?></span>
    								</p>
    								<p class="mb-0 font-weight-bold text-black">
    									Service Charge <span class="float-right text-secondary"><?php echo buynowdepot_format_price($order->total_service_charge);?></span>
    								</p>
    								<p class="mb-0 font-weight-bold text-black">
    									Total Tax <span class="float-right text-secondary"><?php echo buynowdepot_format_price($order->total_tax);?></span>
    								</p>
    								<p class="mb-0 font-weight-bold text-black">
    									Discount <span class="float-right text-secondary">-<?php echo buynowdepot_format_price($order->total_discount);?></span>
    								</p>
    								<p class="mb-0 font-weight-bold text-black">
    									Tip <span class="float-right text-secondary"><?php echo buynowdepot_format_price($order->total_tip);?></span>
    								</p>
    								<p class="mb-0 font-weight-bold text-black">
    									TOTAL BILL <span class="float-right text-secondary"><?php echo buynowdepot_format_price($order->total);?></span>
    								</p>
    							</div>
    						</div>
    					</div>
    				</div>
    				<div class="bg-white p-4 shadow-lg rounded mt-2">
    					<div class="row text-center">
    						<div class="col">
    							<i class="feather-list h4 icofont-3x text-info"></i>
    							<p class="mt-1 font-weight-bold text-dark mb-0">Order Received</p>
    							<small class="text-info mb-0">NOW</small>
    						</div>
    						<div class="col">
    							<i class="feather-check-circle h4 icofont-3x text-success"></i>
    							<p class="mt-1 font-weight-bold text-dark mb-0">Order
    								Confirmed</p>
    							<small class="text-success mb-0">NEXT</small>
    						</div>
    						<div class="col">
    							<i class="feather-truck h4 icofont-3x text-primary"></i>
    							<p class="mt-1 font-weight-bold text-dark mb-0">Order Picked
    								Up</p>
    							<small class="text-primary mb-0">LATER (ET : 30min)</small>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    		<?php } ?>		
			</div>
		</div>
	</div>
</div>

<script>
$(function() {
<?php if ($showError) { ?>
$('#global_message').html('<?php echo $showError?>');
$('#global_message').show();
<?php } ?>
});
function searchOrder() {
	loadUrl("<?php echo buynowdepot_get_page_url('bnd-trackorder').'&orderno='?>"+$('#orderNumber').val());			
}
</script>