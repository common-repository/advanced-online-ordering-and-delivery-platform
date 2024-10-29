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
					<th class='w-30-pct'>Name</th>
					<th class='w-40-pct'>Alternate Name</th>
					<th class='w-40-pct'>Min. Required</th>
					<th class='w-40-pct'>Max. Allowed</th>
					<th class='w-40-pct'>Sort Order</th>
				</tr>
			</thead>
			<tbody class='table-sortable'>
<?php
    foreach ($response["records"] as $val) {
        ?>
            <tr data-category-id='<?php echo $val->clid; ?>'>
    			<td class='model_td'><?php echo $val->name ; ?></td>
    			<td class='model_td'><?php echo $val->alternate_name ; ?></td>
    			<td class='model_td'><?php echo $val->min_required ; ?></td>
    			<td class='model_td'><?php echo $val->max_allowed ; ?></td>
    			<td class='model_td'><?php echo $val->sort_order ; ?></td>
    		</tr>
<?php
    }
}
?>
	</tbody>
</table>