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
<div class="row">
	<div class="col-12 dataTables_wrapper">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Payments</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'>
				<form id='search-order-form' action='#' method='post'>
					<div class='input-group pull-left w-30-pct' data-toggle='tooltip'
						title='Search orders' data-placement='right'>

						<input type='text' value='<?php echo $keywords;?>' name='keywords'
							class='form-control order-search-keywords'
							placeholder='Search orders...' /> <span
							class='input-group-append'>
							<button type='submit' class='btn btn-primary' type='button'>
								<i class='fas fa-search'></i>
							</button>
						</span>

					</div>
				</form>
			</div>
		</div>
<?php
if (isset($response["message"]) && $response["message"] == "No record found.") {
    ?>
<div class='overflow-hidden w-100-pct'>
			<div class='alert alert-danger'>No record found.</div>
		</div>
<?php
} // display products if they exist
else {
    ?>
<table class='table table-bordered table-hover'>
			<thead>
				<tr>
					<th class='w-30-pct'>Payment Date/Time</th>
					<th class='w-30-pct'>Order No.</th>
					<th class='w-40-pct'>Total Amount</th>
					<th class='w-40-pct'>Currency</th>
					<th class='w-40-pct'>Tax</th>
					<th class='w-40-pct'>Notes</th>
					<th class='w-40-pct'>Payment Reference</th>
					<th class='w-40-pct'>Payment Status</th>
				</tr>
			</thead>
			<tbody class='table-sortable'>
<?php
    foreach ($response["records"] as $val) {
        ?>
            <tr data-order-id='<?php echo $val->id; ?>'>
					<td class='model_td'><?php echo $val->created_time ; ?></td>
					<td class='model_td'><?php echo $val->order_clid ; ?></td>
					<td class='model_td'><?php echo buynowdepot_format_price($val->amount) ; ?></td>
					<td class='model_td'><?php echo $val->currency ; ?></td>
					<td class='model_td'><?php echo buynowdepot_format_price($val->tax_amount); ?></td>
					<td class='model_td'><?php echo $val->note ?></td>
					<td class='model_td'><?php echo $val->ext_payment_id ?></td>
					<td class='model_td'><?php echo $val->result; ?></td>
				</tr>
<?php
    }
}
?>
	</tbody>
		</table>
		<?php include_once BUYNOWDEPOT_PLUGIN_DIR.'/admin/templates/paging.php';?>
	</div>
</div>