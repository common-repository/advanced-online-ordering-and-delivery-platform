<?php
/**
 * Delivery setup
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */

$BndSettings = (array) get_option("bnd_settings");
$formDataStr = $BndSettings["delivery_info"];
$formData=array(
    "delivery_mode"=>"",
    "fixed_delivery_fee"=>"",
    "delivery_distance_1"=>"",
    "delivery_fee_1"=>"",
    "delivery_distance_2"=>"",
    "delivery_fee_2"=>"",
    "delivery_distance_3"=>"",
    "delivery_fee_3"=>"",
    "delivery_distance_4"=>"",
    "delivery_fee_4"=>""
);
if ($formDataStr) {
    $formData= (array)json_decode($formDataStr);
}
?>
<div class="row">
	<div class="col-6">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Delivery Configuration</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class="card mb-12">
			<div class="card-body">
			<form action="" method="post" id="delivery-info-form">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td>Delivery Mode</td>
							<td>
								<select name="delivery_mode" id="delivery_mode" onchange="displayDeliveryMode()">
									<option value="">Please Select</option>
									<option value="fixed" <?php echo ($formData["delivery_mode"]=="fixed")?"selected":""?>>Fixed</option>
									<option value="distance"<?php echo ($formData["delivery_mode"]=="distance")?"selected":""?>>Distance-Based</option>
									<option value="zone" <?php echo ($formData["delivery_mode"]=="zone")?"selected":""?>>Zone-Based</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-bordered delivery-form" id="delivery-fixed" style="display:<?php echo ($formData["delivery_mode"]=="fixed")?"block":"none"?>">
					<tbody>
						<tr>
							<td>Fee</td>
							<td>
								<input type="text" name="fixed_delivery_fee" id="fixed_delivery_fee" value="<?php echo $formData["fixed_delivery_fee"];?>">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-bordered delivery-form" id="delivery-distance"  style="display:<?php echo ($formData["delivery_mode"]=="distance")?"block":"none"?>">
					<thead>
						<tr><th>Distance (Miles)</th><th>Fee</th>
					</thead>
					<tbody>
						<tr>
							<td><input type="text" name="delivery_distance_1" id="delivery_distance_1" value="<?php echo $formData["delivery_distance_1"];?>"></td>
							<td>
								<input type="text" name="delivery_fee_1" id="delivery_fee_1" value="<?php echo $formData["delivery_fee_1"];?>">
							</td>
						</tr>
						<tr>
							<td><input type="text" name="delivery_distance_2" id="delivery_distance_2" value="<?php echo $formData["delivery_distance_2"];?>"></td>
							<td>
								<input type="text" name="delivery_fee_2" id="delivery_fee_2" value="<?php echo $formData["delivery_fee_2"];?>">
							</td>
						</tr>
						<tr>
							<td><input type="text" name="delivery_distance_3" id="delivery_distance_3" value="<?php echo $formData["delivery_distance_3"];?>"></td>
							<td>
								<input type="text" name="delivery_fee_3" id="delivery_fee_3" value="<?php echo $formData["delivery_fee_3"];?>">
							</td>
						</tr>
						<tr>
							<td><input type="text" name="delivery_distance_4" id="delivery_distance_4" value="<?php echo $formData["delivery_distance_4"];?>"></td>
							<td>
								<input type="text" name="delivery_fee_4" id="delivery_fee_4" value="<?php echo $formData["delivery_fee_4"];?>">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-bordered delivery-form" id="delivery-zone" style="display:none">
					<tbody>
						<tr>
							<td></td>
							<td>
								
							</td>
						</tr>
					</tbody>
				</table>
				<button type="button" class="btn btn-success mb-0"
						onclick="saveDeliverySetup()"><i class="fas fa-plus"></i>Save</button>
				</form>
			</div>
		</div>
	</div>
</div>
