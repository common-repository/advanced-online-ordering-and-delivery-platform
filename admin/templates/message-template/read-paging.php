<?php 
/**
 * message template CRUD
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/message-template
 */
?>
<div class="row">
	<div class="col-12 dataTables_wrapper">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Message Templates</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'>
				<form id='search-message-template-form' action='#' method='post'>
					<div class='input-group pull-left w-30-pct' data-toggle='tooltip'
						title='Search message templates.' data-placement='right'>

						<input type='text' value='<?php echo $keywords;?>' name='keywords'
							class='form-control message-template-search-keywords'
							placeholder='Search message templates...' /> <span
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
if (isset($response["message"]) && $response["message"] == "No message templates found.") {
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
					<th>Name</th>
					<th class='w-40-pct'>Text</th>
					<th class='w-10-pct text-align-center'>Action</th>
				</tr>
			</thead>
			<tbody class=''>
<?php
    foreach ($response["records"] as $val) {
        ?>
            <tr data-message-template-id='<?php echo $val->id; ?>' data-id="<?php echo $val->sort_order; ?>">
					<td class='model_td'><?php echo $val->display_name ; ?></td>
					<td class='model_td'><?php echo $val->template_text ; ?></td>
					<td>
						<button class='btn btn-info mr-1 update-message-template-button'
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-edit'></i>
						</button>
						<!--
						<button class='btn btn-danger delete-message-template-button'
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-trash'></i>
						</button>
						-->
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