<?php
/**
 * Display partial section for cart
 *
 * This file is used to markup the public-facing aspects of the plugin.
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link       https://buynowdepot.com
 * @since      1.0.0
 *
 * @package    Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/templates/flexmenu
 */
?>
<?php 
$data = $response;
?>
<div class="d-flex border-bottom bnd-cart-item-profile p-3">
	<?php if($data["order_type"]=='delivery') { ?>
	<div class="p-3 bg-white rounded shadow-sm w-100 align-items-center">
		<p class="text-muted mb-1">
			<strong><i class="fas fa-truck"></i>&nbsp; This order is for delivery</strong>
		</p>
		<div class="d-flex">
			<div class="cart-address-area">
				<?php if(!empty($data["delivery_address"])) { ?>
				<small>Deliver to :</small>
				<h6 class="mb-0 font-weight-bold"><?php echo $data["delivery_address"]["first_name"]." ".$data["delivery_address"]["last_name"];?></h6>
				<p class="mb-0 text-muted"><?php echo $data["delivery_address"]["address1"];?>
					<?php echo $data["delivery_address"]["address2"];?>
					<?php echo $data["delivery_address"]["address3"];?></p>
				<p class="mb-0 text-muted"><?php echo $data["delivery_address"]["city"];?>,
					<?php echo $data["delivery_address"]["state"];?> <?php echo $data["delivery_address"]["zip"];?></p>
				<?php } else { ?>
				<p class="mb-0 text-muted">You do not have any address configured currently.</p>
				<?php }?>
			</div>
			<div class="address-button-area">
				<div class="btn btn-success"
					onclick="confirmPickup()">I will Pickup</div>
			</div>
		</div>
	</div>
	<?php } else { ?> 
	<div class="p-3 bg-white rounded shadow-sm w-100 align-items-center">
		<p class="text-muted mb-1">
			<strong><i class="fas fa-shopping-bag"></i>&nbsp; This order is for pick up</strong>
		</p>
		<div class="d-flex">
			<div class="cart-address-area">
				<small>Pickup from :</small>
				<h6 class="mb-0 font-weight-bold"><?php echo $data["merchant_address"]["name"];?></h6>
				<p class="mb-0 text-muted"><?php echo $data["merchant_address"]["address1"];?>
					<?php echo $data["merchant_address"]["address2"];?>
					<?php echo $data["merchant_address"]["address3"];?></p>
				<p class="mb-0 text-muted"><?php echo $data["merchant_address"]["city"];?>,
					<?php echo $data["merchant_address"]["state"];?> <?php $data["merchant_address"]["zip"];?></p>
			</div>
			<div class="address-button-area">
				<div class="btn btn-success"
					onclick="confirmDelivery()">Change To Delivery</div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
<?php foreach($data["lineItems"] as $item) {?>
<div class="d-flex px-2 py-1 border-bottom">
	<img alt="#" src="<?php echo $item["image_url"]?>" alt="<?php echo $item["name"]?>"
		class="mr-1 rounded-pill cart-item-image">
	<div class="media-body">
	    <p class="cart-area-item m-0"><?php echo $item["name"]?></p>
		<span class="cart-area-modifier"> <?php echo $item["modifiers"];?> </span>
	</div>
	<div class="cart-quantity">
		<div class="number-spinner">
			<span class="ns-btn"> <a data-dir="dwn" class="item-quantity-up"
				id="item_quantity_down-<?php echo $item["key"];?>"><span class="icon-minus"></span></a>
			</span> <input type="text" class="pl-ns-value"
				value="<?php echo $item["quantity"];?>" maxlength="2" id="item_quantity-<?php echo $item["key"]; ?>"
				readonly="true"> <span class="ns-btn"> <a data-dir="up"
				class="item-quantity-down" id="item_quantity_up-<?php echo $item["key"]; ?>"><span
					class="icon-plus"></span></a>
			</span>
		</div>
	</div>
	<div class="cart-price">
		<p class="cart-price-item"><?php echo buynowdepot_format_price($item["subtotal"]);?></p>
	</div>
</div>
<?php } ?>
<div class="cart-modify-button border-bottom py-1">
	<div class="btn-group" id="cart-modify-button"
		onclick="loadUrl(bnd_base_url+'/bnd-cart-display')">
		<div class="btn btn-outline-success">
			<i class="fas fa-shopping-cart"></i>
		</div>
		<div class="btn btn-outline-success">View/Modify Cart</div>
	</div>
</div>
<div class="p-3 py-3 border-bottom clearfix">
	<div class="input-group-sm mb-2 input-group">
		<input placeholder="Enter promo code" type="text" class="form-control"
			name="discount_coupon_code" id="discount_coupon_code">
		<div class="input-group-append">
			<div type="button" class="btn btn-primary"
				onclick="applyDiscount()">
				<i class="fas fa-percent"></i> APPLY
			</div>
		</div>
	</div>
	<div class="mb-2 input-group">
		<div class="input-group-prepend">
			<span class="input-group-text"><i class="far fa-comment-alt"></i></span>
		</div>
		<textarea placeholder="Any suggestions? Please write it here"
			aria-label="With textarea" class="form-control"></textarea>
	</div>
	<div class="input-group-sm mb-2 input-group">
		<div class="input-group-prepend">
			<span class="input-group-text" style="width:42px">Tip</span>
		</div>
		<input type="text" class="form-control" name="tip_value"
			id="tip_value" aria-label="Text input with segmented dropdown button"
			style="text-align: right" value="<?php echo $data["total"]["tip_value"];?>">
		<select name="tip_type" id="tip_type" class="form-control" style="background-color:#f2f2f2">
				<option value="percent" <?php echo ($data["total"]["tip_type"]=="percent")?"selected":"";?>>Percentage</option>
				<option value="amount" <?php echo ($data["total"]["tip_type"]=="amount")?"selected":"";?>>$ Amount</option>
		</select>
		<div class="input-group-append">
			<div type="button" class="btn btn-success" onclick="applyTip()">Apply</div>
		</div>
	</div>
</div>
<div class="p-3 clearfix border-bottom">
	<p class="mb-1">
		Item Total <span class="float-right text-dark"><?php echo buynowdepot_format_price($data["total"]["subtotal"]);?></span>
	</p>
	<p class="mb-1">
		Tax <span class="float-right text-dark"><?php echo buynowdepot_format_price($data["total"]["total_tax"]);?></span>
	</p>
	<p class="mb-1">
		<?php echo $data["total"]["service_charge_name"];?> <span class="float-right text-dark"><?php echo buynowdepot_format_price($data["total"]["total_fees"]);?></span>
	</p>
	<p class="mb-1 text-success">
		Total Discount<span class="float-right text-success"><?php echo buynowdepot_format_price($data["total"]["total_discount"]);?></span>
	</p>
	<p class="mb-1 text-success">
		Delivery Charge<span class="float-right text-success"><?php echo buynowdepot_format_price($data["total"]["delivery_charge"]);?></span>
	</p>
	<p class="mb-1 text-success">
		Tip<span class="float-right text-success"><?php echo buynowdepot_format_price($data["total"]["tip"]);?></span>
	</p>
	<hr>
	<div class="checkout-amount font-weight-bold mb-0">
		TO PAY <span class="float-right"><?php echo buynowdepot_format_price($data["total"]["total"]);?></span>
	</div>
</div>
<div class="p-3">
	<div class="btn-group d-flex"
		onclick="loadUrl(bnd_base_url+'/bnd-checkout')">
		<div class="btn btn-success btn-lg">Checkout</div>
		<div class="btn input-group-text btn-lg checkout-icon text-dark">
			<i class="fas fa-arrow-circle-right"></i>&nbsp;
		</div>
	</div>
</div>