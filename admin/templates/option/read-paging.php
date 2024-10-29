<?php 
/**
 * option CRUD
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/option
 */
?>
<div class="row">
	<div class="col-12 dataTables_wrapper">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Options</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'>
				<form id='search-option-form' action='#' method='post'>
					<div class='input-group pull-left w-30-pct' data-toggle='tooltip'
						title='Search options.' data-placement='right'>

						<input type='text' value='<?php echo $keywords;?>' name='keywords'
							class='form-control option-search-keywords'
							placeholder='Search options...' /> <span
							class='input-group-append'>
							<button type='submit' class='btn btn-primary' type='button'>
								<i class='fas fa-search'></i>
							</button>
						</span>

					</div>
				</form>
			</div>
			<div class='data-button-area'>
				<div id='create-option'
					class='btn btn-primary pull-right m-b-15px mr-1 create-option-button'>
					<span class='fas fa-plus'></span> Create Option
				</div>
			</div>
		</div>
<?php
if (isset($response["message"]) && $response["message"] == "No options found.") {
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
					<th class='w-40-pct'>Description</th>
					<th class='w-40-pct'>Alternate Name</th>
					<th class='w-40-pct'>Image</th>
					<th class='w-40-pct'>Display</th>
					<th class='w-40-pct'>Sort Order</th>
					<th class='w-30-pct text-align-center'>Action</th>
					<th class='w-30-pct text-align-center'>Options</th>
				</tr>
			</thead>
			<tbody class='table-sortable'>
<?php
    foreach ($response["records"] as $val) {
        ?>
            <tr data-option-id='<?php echo $val->clid; ?>'>
					<td class='td-shuffle'><button class='btn btn-default'>
							<i class='fas fa-random'></i>
						</button></td>
					<td class='model_td'><?php echo $val->name ; ?></td>
					<td class='model_td'><?php echo $val->description ; ?></td>
					<td class='model_td'><?php echo $val->alternate_name ; ?></td>
					<td class='model_td'><img src='<?php echo $val->image_link; ?>'
						width='60' /></td>
					<td class='model_td'>

						<div
							class="custom-switch custom-switch-primary-inverse custom-switch-small pl-1"
							data-toggle="tooltip" data-placement="left" title="On">
							<input class="custom-switch-input" id="switchDark-<?php echo $val->clid; ?>"
								type="checkbox" checked> <label class="custom-switch-btn"
								for="switchDark-<?php echo $val->id; ?>"></label>
						</div>
					</td>
					<td class='model_td'><?php echo $val->sort_order ; ?></td>
					<td>
						<button class='btn btn-info mr-1 update-option-button'
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-edit'></i>
						</button>
						<button class='btn btn-danger delete-option-button'
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-trash'></i>
						</button>
					</td>
					<td class='model_td'><a href=''>Options</a></td>
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