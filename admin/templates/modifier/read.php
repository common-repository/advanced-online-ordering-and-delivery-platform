<?php
/**
 * modifier CRUD
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/modifier
 */
$modifierGroup = Bnd_Flex_Order_Delivery_Session::instance()->get("modifier-group");
?>
<div class="row">
	<div class="col-12 dataTables_wrapper">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Items for <strong><?php echo $modifierGroup["name"];?></strong> </h4>
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
			<!-- 
			<div class='data-button-area'>
				<div id='create-option'
					class='btn btn-primary pull-right m-b-15px mr-1 create-option-button'>
					<span class='fas fa-plus'></span> Create Option
				</div>
			</div>
			 -->
			 <div class='data-button-area'>
			 	<div id='read-modifier-group'
					class='btn btn-primary pull-right m-b-15px mr-1 read-modifier-group-button'>
					<span class='fas fa-list'></span> Modifier Group List
				</div>
				<div id='save-model-sort-order' data-model="modifier" class='btn btn-primary pull-right m-b-15px mr-1 save-sort-order-button'>
    				<span class='fas fa-save'></span> Save Display Order
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
					<th class='w-40-pct'>Alternate Name</th>
					<th class='w-40-pct'>Price</th>
					<th class='w-40-pct'>Sort Order</th>
				</tr>
			</thead>
			<tbody class='table-sortable'>
<?php
    foreach ($response["records"] as $val) {
        ?>
            <tr data-modifier-id='<?php echo $val->clid; ?>'>
				<td class='td-shuffle'><button class='btn btn-default'>
						<i class='fas fa-random'></i>
					</button></td>
				<td class='model_td'><?php echo $val->name ; ?></td>
				<td class='model_td'><?php echo $val->alternate_name ; ?></td>
				<td class='model_td'><?php echo buynowdepot_format_price($val->price) ; ?></td>
				<td class='model_td'><?php echo $val->sort_order ; ?></td>
			</tr>
<?php
    }
}
?>
	</tbody>
		</table>
	</div>
</div>