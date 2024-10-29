<?php
/**
 * Configuration
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */
$BndSettings = (array) get_option("bnd_settings");
/*
$BndSettings["image_base_url"]="";
$BndSettings["cdn_url"]="";
$BndSettings["order_number_prefix"]="";
$BndSettings["use_discount_coupon"]="";
$BndSettings["minimum_order_time"]="";
$BndSettings["maximum_order_time"]="";
$BndSettings["order_type_delivery"]="";
$BndSettings["order_type_pickup"]="";
update_option("bnd_settings",$BndSettings);*/
$model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$orderTypes = $model->getOrderTypes();
?>
<div class="row">
	<div class="col-6">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Additional Menu Configuration</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class="card mb-12">
			<div class="card-body">
			<form action="" method="post" name="configuration-form" id="configuration-form">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td>Base URL for images</td>
							<td>
								<input type="text" name="image_base_url" value="<?php echo ($BndSettings["image_base_url"])?$BndSettings["image_base_url"]:buynowdepot_get_theme_url()."/img";?>"/>
							</td>
						</tr>
						<tr>
							<td>CDN URL for images</td>
							<td>
								<input type="text" name="cdn_url" value="<?php echo ($BndSettings["cdn_url"])?$BndSettings["cdn_url"]:"";?>"/>
							</td>
						</tr>
						<tr>
							<td>Order No. Prefix</td>
							<td>
								<input type="text" name="order_prefix" value="<?php echo ($BndSettings["order_prefix"])?$BndSettings["order_prefix"]:"";?>" />
							</td>
						</tr>
						<tr>
							<td>Discount Coupons</td>
							<td>
								<label class="label-switch">
                                  <input type="checkbox" id="temp-use_discount_coupon" <?php echo ($BndSettings["use_discount_coupon"])?"checked":""; ?> onclick="changeSwitch('temp-use_discount_coupon','use_discount_coupon')">
                                  <span class="label-slider round"></span>
                                </label>
                                <input type="hidden" name="use_discount_coupon" id="use_discount_coupon" value="<?php echo $BndSettings["use_discount_coupon"] ?>"/>
							</td>
						</tr>
						<tr>
							<td>Minimum Order Time (Minutes)</td>
							<td>
								<input type="text" name="minimum_order_time" value="<?php echo $BndSettings["minimum_order_time"] ?>" />
							</td>
						</tr>
						<tr>
							<td>Maximum Order Time (Days)</td>
							<td>
								<input type="text" name="maximum_order_time" value="<?php echo $BndSettings["maximum_order_time"] ?>" />
							</td>
						</tr>
						<tr>
							<td>Delivery Fee Name</td>
							<td>
								<input type="text" name="delivery_fees_name" value="<?php echo $BndSettings["delivery_fees_name"] ?>" />
							</td>
						</tr>
						<tr>
							<td>Service Fee Name</td>
							<td>
								<input type="text" name="service_fees_name" value="<?php echo $BndSettings["service_fees_name"] ?>" />
							</td>
						</tr>
						<tr>
							<td>Default Country Code</td>
							<td>
								<input type="text" name="default_country_code" value="<?php echo $BndSettings["default_country_code"] ?>" />
							</td>
						</tr>
						<tr>
							<td>Google maps API Key</td>
							<td>
								<input type="text" name="google_maps_api_key" value="<?php echo $BndSettings["google_maps_api_key"] ?>" />
							</td>
						</tr>
						<tr>
							<td>Show Blank Categories</td>
							<td>
								<select name="show_blank_categories">
									<option value="0" <?php echo ($BndSettings["show_blank_categories"]==0)?"selected":""?>>No</option>
									<option value="1" <?php echo ($BndSettings["show_blank_categories"]==1)?"selected":""?>>Yes</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Override Online Category Update</td>
							<td>
								<select name="override_online_category_update">
									<option value="0" <?php echo ($BndSettings["override_online_category_update"]==0)?"selected":""?>>No</option>
									<option value="1" <?php echo ($BndSettings["override_online_category_update"]==1)?"selected":""?>>Yes</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Order Type (Delivery)</td>
							<td>
								<select name="order_type_delivery">
									<option value="DEFAULT">Default</option>
									<?php foreach($orderTypes as $orderType) {?>
										<option value="<?php echo $orderType->clid?>" <?php echo ($orderType->is_default)?"selected":""?>><?php echo $orderType->label?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Order Type (Pickup)</td>
							<td>
								<select name="order_type_pickup">
									<option value="DEFAULT">Default</option>
									<?php foreach($orderTypes as $orderType) {?>
										<option value="<?php echo $orderType->clid?>" <?php echo ($orderType->is_default)?"selected":""?>><?php echo $orderType->label?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<button type="button" class="btn btn-success mb-0"
						onclick="saveConfiguration()">Confirm</button>
				</form>
			</div>
		</div>
	</div>
</div>
