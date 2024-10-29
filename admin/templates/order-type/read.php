<?php 
/**
 * order details
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */
?>
<div class='d-flex mb-2'>
	<div class='data-form-search'>
	</div>
	<div class='data-button-area'>
		<div id='create-order-type'
			class='btn btn-primary pull-right m-b-15px mr-1 create-category-button'>
			<span class='fas fa-plus'></span> Create Order Type
		</div>
	</div>
</div>
<?php
if (isset($response["message"]) && $response["message"] == "No order types found.") {
    ?>
<div class='overflow-hidden w-100-pct'>
	<div class='alert alert-danger'>No records found.</div>
</div>
<?php
} // display products if they exist
else {
    ?>
<table class='table table-bordered table-hover'>
	<thead>
		<tr>
			<th class='w-30-pct'>Name</th>
			<th class='w-40-pct'>Taxable</th>
			<th class='w-40-pct'>Default</th>
			<th class='w-40-pct'>Filter Categories</th>
			<th class='w-40-pct'>Display</th>
			<th class='w-40-pct'>Min. Order Amount</th>
			<th class='w-40-pct'>Max. Order Amount</th>
			<th class='w-40-pct'>Fee</th>
			<th class='w-40-pct'>Max Radius</th>
			<th class='w-30-pct text-align-center'>Categories</th>
		</tr>
	</thead>
	<tbody class='table-sortable'>
<?php
    foreach ($response as $val) {
        ?>
            <tr data-category-id='<?php echo $val->clid; ?>'>
			<td class='model_td'><?php echo $val->label ; ?></td>
			<td class='model_td'><?php echo ($val->taxable==1)?"Yes":"No"; ?></td>
			<td class='model_td'><?php echo ($val->is_default==1)?"Yes":"No"; ?></td>
			<td class='model_td'><?php echo ($val->filtered_categories==1)?"Yes":"No"; ?></td>
			<td class='model_td'><?php echo ($val->display==1)?"Yes":"No"; ?></td>
			<td class='model_td'><?php echo $val->min_order_amount ; ?></td>
			<td class='model_td'><?php echo $val->max_order_amount ; ?></td>
			<td class='model_td'><?php echo $val->fee ; ?></td>
			<td class='model_td'><?php echo $val->max_radius ; ?></td>
			<td class='model_td'>Categories</td>
		</tr>
<?php
    }
}
?>
	</tbody>
</table>
