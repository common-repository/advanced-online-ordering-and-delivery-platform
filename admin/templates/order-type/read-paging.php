<?php
/**
 * order details
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/order
 */
?>
<div class='d-flex mb-2'>
	<div class='data-form-search'>
		<form id='search-order-type-form' action='#' method='post'>
			<div class='input-group pull-left w-30-pct' data-toggle='tooltip'
				title='Search categories.' data-placement='right'>

				<input type='text' value='<?php echo $keywords;?>' name='keywords'
					class='form-control order-type-search-keywords'
					placeholder='Search categories...' /> <span
					class='input-group-append'>
					<button type='submit' class='btn btn-primary' type='button'>
						<i class='fas fa-search'></i>
					</button>
				</span>

			</div>
		</form>
	</div>
	<div class='data-button-area'>
		<div id='create-order-type'
			class='btn btn-primary pull-right m-b-15px mr-1 create-order-type-button'>
			<span class='fas fa-plus'></span> Create Order Type
		</div>
	</div>
</div>
<?php
if (isset($response["message"]) && $response["message"] == "No categories found.") {
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
			<th>Re-order</th>
			<th class='w-30-pct'>Name</th>
			<th class='w-40-pct'>Taxable</th>
			<th class='w-40-pct'>Min. Order Amount</th>
			<th class='w-40-pct'>Max. Order Amount</th>
			<th class='w-40-pct'>Display</th>
			<th class='w-40-pct'>Hours Available</th>
			<th class='w-30-pct text-align-center'>Action</th>
			<th class='w-30-pct text-align-center'>Categories</th>
		</tr>
	</thead>
	<tbody class='table-sortable'>
<?php
    foreach ($response["records"] as $val) {
        ?>
            <tr data-order-type-id='<?php echo $val->id; ?>'>
			<td class='td-shuffle'><button class='btn btn-default'>
					<i class='fas fa-random'></i>
				</button></td>
			<td class='order-type_td'><?php echo $val->label ; ?></td>
			<td class='order-type_td'><?php echo ($val->taxable)?"Yes":"No" ; ?></td>
			<td class='order-type_td'><?php echo $val->min_order_amount ; ?></td>
			<td class='order-type_td'><?php echo $val->max_order_amount ; ?></td>
			<td class='order-type_td'>				<div
					class="custom-switch custom-switch-primary-inverse custom-switch-small pl-1"
					data-toggle="tooltip" data-placement="left" title="On">
					<input class="custom-switch-input" id="switchDark" type="checkbox"
						checked> <label class="custom-switch-btn" for="switchDark"></label>
				</div></td>
			<td class='order-type_td'><?php echo $val->hours_available ; ?></td>
			<td>
				<button class='btn btn-info mr-1 update-order-type-button'
					data-id='<?php echo $val->id; ?>'>
					<i class='fas fa-edit'></i>
				</button>
				<button class='btn btn-danger delete-order-type-button'
					data-id='<?php echo $val->id; ?>'>
					<i class='fas fa-trash'></i>
				</button>
			</td>
			<td class='order-type_td'><a href=''>Categories</a></td>
		</tr>
<?php

}
}
$pagination_class = ($keywords=="") ? "categories-pagination-normal"  : "categories-pagination-search";
?>
	</tbody>
</table>
<?php include_once BUYNOWDEPOT_PLUGIN_DIR.'/admin/templates/paging.php';?>