<?php
/**
 * Display categories
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */
?>
<div class='d-flex mb-2'>
	<div class='data-form-search'>
		<form id='search-category-form' action='#' method='post'>
			<div class='input-group pull-left w-30-pct' data-toggle='tooltip'
				title='Search categories.' data-placement='right'>

				<input type='text' value='<?php echo $keywords;?>' name='keywords'
					class='form-control category-search-keywords'
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
		<div id='create-category'
			class='btn btn-primary pull-right m-b-15px mr-1 create-category-button'>
			<span class='fas fa-plus'></span> Create Category
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
            <tr data-category-id='<?php echo $val->id; ?>'>
			<td class='td-shuffle'><button class='btn btn-default'>
					<i class='fas fa-random'></i>
				</button></td>
			<td class='category_td'><?php echo $val->name ; ?></td>
			<td class='category_td'><?php echo $val->description ; ?></td>
			<td class='category_td'><?php echo $val->alternate_name ; ?></td>
			<td class='category_td'><img src='<?php echo $val->image_link; ?>'	width='60' /></td>
			<td class='category_td'>

				<div
					class="custom-switch custom-switch-primary-inverse custom-switch-small pl-1"
					data-toggle="tooltip" data-placement="left" title="On">
					<input class="custom-switch-input" id="switchDark" type="checkbox"
						checked> <label class="custom-switch-btn" for="switchDark"></label>
				</div>
			</td>
			<td class='category_td'><?php echo $val->sort_order ; ?></td>
			<td>
				<button class='btn btn-info mr-1 update-category-button'
					data-id='<?php echo $val->id; ?>'>
					<i class='fas fa-edit'></i>
				</button>
				<button class='btn btn-danger delete-category-button'
					data-id='<?php echo $val->id; ?>'>
					<i class='fas fa-trash'></i>
				</button>
			</td>
			<td class='category_td'><a href=''>Items</a></td>
		</tr>
<?php

}
}
$pagination_class = ($keywords=="") ? "categories-pagination-normal"  : "categories-pagination-search";
?>
	</tbody>
</table>
<ul class='pagination <?php echo $pagination_class ?> pull-left margin-zero padding-bottom-2em'>
<?php 
        if($response["paging"]["first"]!=""){
            echo "<li><a data-page='". $response["paging"]["first"] . "'>First Page</a></li>";
        }
        foreach ($response["paging"]["pages"] as $key => $val) {
            $active_page=($val["current_page"]=="yes") ? "class='active'" : "";
            echo "<li ".$active_page."><a data-page='".$val['url']. "'>" .$val['page']."</a></li>";
        }
        if($response["paging"]["last"]!=""){
            echo "<li><a data-page='".$response["paging"]["last"] ."'>Last Page</a></li>";
        }
?>
</ul>