<?php 
/**
 * modifier group CRUD
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/modifier-group
 */
?>
<div class="row">
	<div class="col-12 dataTables_wrapper">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Modifier Groups</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'>
				<form id='search-modifier_group-form' action='#' method='post'>
					<div class='input-group pull-left w-30-pct' data-toggle='tooltip'
						title='Search modifier groups.' data-placement='right'>

						<input type='text' value='<?php echo $keywords;?>' name='keywords'
							class='form-control modifier-group-search-keywords'
							placeholder='Search modifier groups...' /> <span
							class='input-group-append'>
							<button type='button' class='btn btn-primary' type='button' id="search-modifier-group-button">
								<i class='fas fa-search'></i>
							</button>
						</span>

					</div>
				</form>
			</div>
			<!-- 
			<div class='data-button-area'>
				<div id='create-modifier-group'
					class='btn btn-primary pull-right m-b-15px mr-1 create-modifier-group-button'>
					<span class='fas fa-plus'></span> Create Modifier Group
				</div>
			</div>
			 -->
			<div class='data-button-area'>
    			<div id='save-model-sort-order' data-model="modifier_group" class='btn btn-primary pull-right m-b-15px mr-1 save-sort-order-button'>
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
					<th class='w-40-pct'>Alternate Name</th>
					<th class='w-40-pct'>Min. Required</th>
					<th class='w-40-pct'>Max Allowed</th>
					<th class='w-40-pct'>All-Inclusive</th>
					<th class='w-40-pct'>Sort Order</th>
					<th class='w-30-pct text-align-center'>Action</th>
					<th class='w-30-pct text-align-center'>Modifiers</th>
				</tr>
			</thead>
			<tbody class='table-sortable'>
<?php
    foreach ($response["records"] as $val) {
        ?>
            <tr data-modifier_group-id='<?php echo $val->clid; ?>'>
					<td class='td-shuffle'><button class='btn btn-default'>
							<i class='fas fa-random'></i>
						</button></td>
					<td class='model_td'><?php echo $val->name ; ?></td>
					<td class='model_td'><?php echo $val->alternate_name ; ?></td>
					<td class='model_td'><?php echo $val->min_required ; ?></td>
					<td class='model_td'><?php echo $val->max_allowed ; ?></td>
					<td class='model_td'>
						<label class="label-switch">
                          <input type="checkbox" id="temp-default-<?php echo $val->clid; ?>" <?php echo $val->show_by_default?"checked":""; ?> onclick="updateModifierGroupDefault('<?php echo $val->clid;?>')">
                          <span class="label-slider round"></span>
                        </label>
                        <input type="hidden" name="default-<?php echo $val->clid; ?>" id="default-<?php echo $val->clid; ?>" value="<?php echo $val->show_by_default?1:0; ?>"/>
					</td>
					<td class='model_td'><?php echo $val->sort_order ; ?></td>
					<td>
						<button class='btn btn-info mr-1 update-modifier-group-button'
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-edit'></i>
						</button>
						<!-- 
						<button class='btn btn-danger delete-modifier-group-button'
							data-id='<?php echo $val->id; ?>'>
							<i class='fas fa-trash'></i>
						</button>
						 -->
					</td>
					<td class='model_td'>
						<button class='btn btn-success mr-1 view-option-button' onclick="getModifiersByGroup('<?php echo $val->clid?>')">
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