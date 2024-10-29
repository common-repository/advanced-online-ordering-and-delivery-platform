<?php 
/**
 * Category CRUD
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/category
 */
?>
<div class="row">
	<div class="col-12 dataTables_wrapper">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Categories</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'>
				<form id='search-category-form' action='#' method='post'>
					<div class='input-group pull-left w-30-pct' data-toggle='tooltip'
						title='Search categories.' data-placement='right'>

						<input type='text' value='<?php echo $keywords;?>' name='keywords'
							class='form-control category-search-keywords'
							placeholder='Search categories...' /> <span
							class='input-group-append'>
							<button type='button' class='btn btn-primary' type='button' id="search-category-button">
								<i class='fas fa-search'></i>
							</button>
						</span>
					</div>
				</form>
			</div>
			<div class='data-button-area'>
				<div id='save-model-sort-order' data-model="category" class='btn btn-primary pull-right m-b-15px mr-1 save-sort-order-button'>
					<span class='fas fa-save'></span> Save Display Order
				</div>
			 </div>
		</div>
<?php
if (isset($response["message"]) && $response["message"] == "No records found.") {
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
					<th class='w-30-pct text-align-center'>Items</th>
				</tr>
			</thead>
			<tbody class='table-sortable'>
<?php
    foreach ($response["records"] as $val) {
        ?>
            <tr data-category-id='<?php echo $val->clid; ?>' data-id="<?php echo $val->sort_order; ?>">
					<td class='td-shuffle'><button class='btn btn-default'>
							<i class='fas fa-random'></i>
						</button></td>
					<td class='model_td'><?php echo $val->name ; ?></td>
					<td class='model_td'><?php echo $val->description ; ?></td>
					<td class='model_td'><?php echo $val->alternate_name ; ?></td>
					<td class='model_td'><img src='<?php echo buynowdepot_get_image_url($val->image_link); ?>'
						width='60' onclick="showLibrary()"/></td>
					<td class='model_td'>
						<label class="label-switch">
                          <input type="checkbox" id="temp-display-<?php echo $val->clid; ?>" <?php echo $val->display?"checked":""; ?> onclick="updateCategoryDisplay('<?php echo $val->clid;?>')">
                          <span class="label-slider round"></span>
                        </label>
                        <input type="hidden" name="display-<?php echo $val->clid; ?>" id="display-<?php echo $val->clid; ?>" value="<?php echo $val->display?1:0; ?>"/>
					</td>
					<td class='model_td'><?php echo $val->sort_order; ?></td>
					<td>
						<button class='btn btn-info mr-1 update-category-button'
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-edit'></i>
						</button>
						<!--
						<button class='btn btn-danger delete-category-button'
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-trash'></i>
						</button>
						-->
					</td>
					<td class='model_td'>
						<button class='btn btn-success mr-1 view-item-button' onclick="getItemsByCategory('<?php echo $val->clid?>')">
							<i class='fas fa-sitemap'></i>
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