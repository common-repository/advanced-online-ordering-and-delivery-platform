<?php
/**
 * Delivery Zone CRUD
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/delivery-zone
 */
?>
<div class="row">
	<div class="col-12 dataTables_wrapper">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Delivery Zones</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'>
				<form id='search-delivery-zone-form' action='#' method='post'>
					<div class='input-group pull-left w-30-pct' data-toggle='tooltip'
						title='Search categories.' data-placement='right'>

						<input type='text' value='<?php echo $keywords;?>' name='keywords'
							class='form-control category-search-keywords'
							placeholder='Search delivery zones...' /> <span
							class='input-group-append'>
							<button type='submit' class='btn btn-primary' type='button'>
								<i class='fas fa-search'></i>
							</button>
						</span>

					</div>
				</form>
			</div>
			<div class='data-button-area'>
				<div id='create-delivery-zone'
					class='btn btn-primary pull-right m-b-15px mr-1 create-delivery-zone-button'>
					<span class='fas fa-plus'></span> New Delivery Zone
				</div>
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
					<th class='w-30-pct'>Zone Name</th>
					<th class='w-40-pct'>Min Amount</th>
					<th class='w-40-pct'>Fee Type</th>
					<th class='w-40-pct'>Delivery Fee</th>
					<th class='w-40-pct'>Default</th>
					<th class='w-40-pct'>Action</th>
				</tr>
			</thead>
			<tbody class='table-sortable'>
<?php
    foreach ($response["records"] as $val) {
        ?>
            <tr data-delivery-zone-id='<?php echo $val->id; ?>'>
					<td class='model_td'><?php echo $val->name ; ?></td>
					<td class='model_td'><?php echo $val->min_amount ; ?></td>
					<td class='model_td'><?php echo $val->fee_type ; ?></td>
					<td class='model_td'><?php echo $val->delivery_fee ; ?></td>
					<td class='model_td'><?php echo ($val->is_default==1)?"Yes":"No" ; ?></td>
					<td>
						<button class='btn btn-info mr-1 update-delivery-zone-button'
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-edit'></i>
						</button>
						<button class='btn btn-danger delete-delivery-zone-button'
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-trash'></i>
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