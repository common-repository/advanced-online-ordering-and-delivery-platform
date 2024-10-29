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
$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$result = $db->getModelById("category", sanitize_text_field($_GET["id"]));
?>
<div class="row">
	<div class="col-12">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Category</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'></div>
			<div class='data-button-area'>
				<div id='read-category'
					class='btn btn-primary pull-right m-b-15px mr-1 read-category-button'>
					<span class='fas fa-list'></span> Category List
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="card mb-12">
			<div class="card-header"><h4 class="mb-0">Update Category</h4></div>
			<div class="card-body">
        		<form id='update-category-form' action='#' method='post'>
        			<input type="hidden" name="id" id="id" value="<?php echo $result->clid;?>"/>
        			<table class='table table-hover table-bordered' style="width:100%">       
        				<tr>
        					<td>Name</td>
        					<td><input type='text' name='name' class='form-control' id="name"  value="<?php echo $result->name;?>"/></td>
        				</tr>
        				<tr>
        					<td>Alternate Name</td>
        					<td><input type='text' name='alternate_name' class='form-control' id="alternate_name"  value="<?php echo $result->alternate_name;?>"/></td>
        				</tr>
        				<tr>
        					<td>Description</td>
        					<td><textarea name='description' class='form-control' id="description"><?php echo $result->description;?></textarea></td>
        				</tr>
        				<tr>
        					<td>Sort Order</td>
        					<td>
        						<input type='text' name='sort_order' class='form-control' id="sort_order"  value="<?php echo $result->sort_order;?>"/>
        					</td>
        				</tr> 
        				<tr>
        					<td>Display</td>
        					<td>
        						<label class="label-switch">
                                  <input type="checkbox" id="temp-display" <?php echo $result->display?"checked":""; ?> onclick="changeSwitch('temp-display','display')">
                                  <span class="label-slider round"></span>
                                </label>
                                <input type="hidden" name="display" id="display" value="<?php echo $result->display?1:0; ?>"/>
        					</td>
        				</tr>
        				<tr>
        					<td>Image URL</td>
        					<td>
        						<input type='text' name='image_link' class='form-control' id="image_link"  value="<?php echo $result->image_link;?>"/>
        					</td>
        				</tr> 
        				<tr>
        					<td></td>
        					<td>
        						<button type='button' class='btn btn-primary' onclick="updateModel('#inventory-content', 'category')">
        							<span class='fas fa-plus'></span> Save
        						</button>
        					</td>
        				</tr>       	
        			</table>
        		</form>
        	</div>
        </div>
	</div>
	<div class="col-sm-6">
    	<div class="card mb-12">
    		<div class="card-header"><h4 class="mb-0">Category Image</h4></div>
    		<div class="card-body">
    			<div class="edit-category-image">
                    <div class="card">
                        <div class="position-relative">
                            <a href="#"><img class="card-img-top" src="<?php echo buynowdepot_get_image_url($result->image_link); ?>" alt="Card image cap" id="category-image-url"></a>
                        </div>
                        <div class="card-footer" style="text-align: center">
                            <button class='btn btn-danger mr-1 remove-image-button'>
    							<i class='fas fa-trash-alt'></i>
    						</button>
    						<button class='btn btn-secondary mr-1 edit-image-button'>
    							<i class='fas fa-edit'></i>
    						</button>
                        </div>
                    </div>
                </div>
    		</div>
    	</div>
    </div>
</div>
<script type="text/javascript">

$(document).ready(function(){
	$("#update-category-form").validate({
	    // Specify validation rules
	    rules: {
	      // The key name on the left side is the name attribute
	      // of an input field. Validation rules are defined
	      // on the right side
	      name: "required",
	      sort_order:"required",
	      display:"required"
	    },
	    // Specify validation error messages
	    messages: {
	      name: "Please enter category name",
	      sort_order: "Please enter sort order",
	      display: "Please enter display"
	    }
	  }); 
	$('.edit-image-button').off("click");
	$('.remove-image-button').off("click");
	$('.edit-image-button').on("click", function(e){
		changeImage(e, function(attachment){
			$('#image_link').val(attachment.url);
		    $('#category-image-url').attr("src",attachment.url);
		});
	});
});
</script>