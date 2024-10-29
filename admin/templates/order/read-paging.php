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
				<h4 class="mb-0">Order</h4>
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
					<th class='w-30-pct'>Order Date/Time</th>
					<th class='w-30-pct'>Order No.</th>
					<th class='w-40-pct'>Amount</th>
					<th class='w-40-pct'>Currency</th>
					<th class='w-40-pct'>Tax</th>
					<th class='w-40-pct'>Service Charges</th>
					<th class='w-40-pct'>Delivery Charges</th>
					<th class='w-40-pct'>Discount</th>
					<th class='w-40-pct'>Total</th>
					<th class='w-40-pct'>Order Status</th>
					<th class='w-40-pct'>Payment Status</th>
					<th>Items</th>
				</tr>
			</thead>
			<tbody class='table-sortable'>
<?php
    foreach ($response["records"] as $val) {
        ?>
            <tr data-order-id='<?php echo $val->id; ?>'>
					<td class='model_td'><?php echo $val->created_time ; ?></td>
					<td class='model_td'><?php echo $val->title ; ?></td>
					<td class='model_td'><?php echo buynowdepot_format_price($val->sub_total); ?></td>
					<td class='model_td'><?php echo $val->currency ; ?></td>
					<td class='model_td'><?php echo buynowdepot_format_price($val->total_tax); ?></td>
					<td class='model_td'><?php echo buynowdepot_format_price($val->total_service_charge); ?></td>
					<td class='model_td'><?php echo buynowdepot_format_price($val->delivery_charge); ?></td>
					<td class='model_td'><?php echo buynowdepot_format_price($val->total_discount); ?></td>
					<td class='model_td'><?php echo buynowdepot_format_price($val->total); ?></td>
					<td class='model_td'><?php echo $val->order_status ; ?></td>
					<td class='model_td'><?php echo $val->payment_state ; ?></td>
					<td>
						<button class='btn btn-info mr-1 view-order-item-button'
							data-id='<?php echo $val->id; ?>' data-toggle="modal" data-target="#itemsModal">
							<i class='fas fa-sitemap'></i>
						</button>
						<button class='btn btn-danger view-customer-button'  data-toggle="modal" data-target="#customerModal"
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-user'></i>
						</button>
					</td>
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
<div class="modal fade" id="itemsModal" tabindex="-1" role="dialog" aria-labelledby="itemsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Order Items</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
        	<tr><th>Item</th><th>Add Ons</th><th>Amount</th><th>Tax</th>
        	<tr><th>Mighty Meaty</th><th>Small, Olives, Thin Crust</th><th>$12.50</th><th>$1.25</th>
        	<tr><th>The Cheeseburger</th><th>Small, Olives, Thin Crust</th><th>$12.50</th><th>$1.25</th>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Customer Detaiils</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
        	<tr><td>Name</td><td>Brad Washington</td>
        	<tr><td>Address</td><td>1797  Deercove Drive <br/>Dallas TX 75212<br/> USA</td>
        	<tr><td>Email</td><td>1wyaakzmj7v@temporary-mail.net</td>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>