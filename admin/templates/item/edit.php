<?php 
/**
 * item CRUD
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/item
 */
$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$result = $db->getByCloverId("item", sanitize_text_field($_GET["id"]));
?>
<div class="row">
	<div class="col-12">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Item</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'></div>
			<div class='data-button-area'>
				<div id='read-category'
					class='btn btn-primary pull-right m-b-15px mr-1 read-item-button'>
					<span class='fas fa-list'></span> Item List
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="card mb-12">
			<div class="card-header"><h4 class="mb-0">Update Item</h4></div>
			<div class="card-body">
        		<form id='update-item-form' action='#' method='post'>
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
        					<td>Price</td>
        					<td><?php echo buynowdepot_format_price($result->price);?></td>
        				</tr>
        				<tr>
        					<td>Price Type</td>
        					<td><?php echo $result->price_type;?></td>
        				</tr>
        				<tr>
        					<td>Price Unit</td>
        					<td><?php echo $result->price_unit;?></td>
        				</tr>
        				<tr>
        					<td>Product Code</td>
        					<td><?php echo $result->product_code;?></td>
        				</tr>
        				<tr>
        					<td>SKU</td>
        					<td><?php echo $result->sku;?></td>
        				</tr>
        				<tr>
        					<td>Is revenue?</td>
        					<td><?php echo $result->is_revenue?"Yes":"No";?></td>
        				</tr>
        				<tr>
        					<td>Default Tax Rate?</td>
        					<td><?php echo $result->default_tax_rate?"Yes":"No";?></td>
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
                                  <input type="checkbox" id="temp-is-hidden" <?php echo ($result->is_hidden==0)?"checked":""; ?> onclick="changeSwitch('temp-is-hidden','is_hidden', true)">
                                  <span class="label-slider round"></span>
                                </label>
                                <input type="hidden" name="is_hidden" id="is_hidden" value="<?php echo $result->is_hidden?0:1; ?>"/>
        					</td>
        				</tr>
        				<tr>
        					<td></td>
        					<td>
        						<button type='button' class='btn btn-primary' onclick="updateModel('#inventory-content', 'item')">
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
    		<div class="card-header"><h4 class="mb-0">Item Images</h4></div>
    		<div class="card-body">
    			<?php 
    			$itemImages = $db->getItemImages($result->clid);
    			foreach($itemImages as $image) {
    			?>
    			<div class="edit-item-box  mr-1 mb-2">
             		<div class="edit-item-image">
                            <a href="#"><img src="<?php echo buynowdepot_get_image_url($image->image_url); ?>" alt="Card image cap" id="item-image-<?php echo $image->id?>"></a>
                    </div>
					<div class="edit-item-text">
				        <div class="input-group mb-3">
                            <input type="text" name="image_url-<?php echo $image->id?>" id="image_url-<?php echo $image->id?>" class="form-control" placeholder="Image URL"  value="<?php echo $image->image_url?>">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button"  onclick="saveImage(<?php echo $image->id?>)">Save</button>
                            </div>
                        </div>
                    </div>
                   <div class="edit-item-button" style="text-align: center">
                        <button class='btn btn-danger mr-1 remove-image-button' data-id="<?php echo $image->id;?>">
							<i class='fas fa-trash-alt'></i>
						</button>
						<button class='btn btn-primary mr-1 edit-image-button' data-id="<?php echo $image->id;?>">
							<i class='fas fa-edit'></i>
						</button>
						<button class='btn <?php echo $image->is_default?"btn-success":"btn-secondary";?> mr-1 default-image-button' data-id="<?php echo $image->id;?>">
							<i class='fas fa-check-square'></i>
						</button>
                    </div>
                 </div>
                 <?php } ?>
                 <div class="edit-item-box  mr-1 mb-2">
             		<div class="edit-item-image">
                        <a href="#"><img src="<?php echo buynowdepot_get_image_url("no-image.png"); ?>" alt="Card image cap" id="item-image-0" id="image_src-<?php echo $image->id?>"></a>
					</div>
					<div class="edit-item-text">
		                <div class="input-group mb-3">
                            <input type="text" name="image_url-new" id="image_url-new" class="form-control" placeholder="Image URL"  value="">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" onclick="saveImage()" >Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="edit-item-button" style="text-align: center">
                        <button class="btn btn-success add-image-button" style="height:100%">
                        	<i class="fas fa-plus"></i>
                        </button>
                    </div>
                 </div>
            </div>
    	</div>
    </div>
</div>
<script type="text/javascript">

$(document).ready(function(){
	$("#update-item-form").validate({
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
	      name: "Please enter image name",
	      sort_order: "Please enter sort order",
	      display: "Please enter display"
	    }
	  }); 
	$('.edit-image-button').off("click");
	$('.remove-image-button').off("click");
	$('.add-image-button').off("click");
	$('.default-image-button').off("click");
	$('.edit-image-button').on("click", function(e){
		var imageLinkId = $(this).data("id");
		changeImage(e, function(attachment) {
			updateImage(imageLinkId, attachment.url);
		});
	});
	$('.add-image-button').on("click", function(e){
		changeImage(e, function(attachment) {
			insertImage(attachment.url);
		});
	});
	$('.remove-image-button').on("click", function(e){
		var id = $(this).data("id");
    	bootbox.confirm("Are you sure you want to delete the image?", function(result){
    	    if (result==true) {
    	    	$.ajax({
    	    		url: bnd_rest_url+"bnd-rest-api/item-image/delete",
    	            type : "POST",
    	            data:"id="+id,
    	            success : function(result) {
    	     			if (result["status"]=="success") {
    	     				reloadPage();
    	     			}
    	            },
    	            error: function(xhr, resp, text) {
    	                console.log(xhr, resp, text);
    	            }
    	        });
    	    }
    	})
	});
	$('.default-image-button').on("click", function(e){
		var id = $(this).data("id");
		var itemId=$('#id').val();
		$.ajax({
    		url: bnd_rest_url+"bnd-rest-api/run_query?action=setDefaultImage&id="+id+"&item="+itemId,
            type : "GET",
            success : function(result) {
     			if (result["status"]=="success") {
     				reloadPage();
     			}
            },
            error: function(xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
	});
});

function reloadPage() {
	var id = $('#id').val();
	loadTemplate("item/edit",'#inventory-content', function(){
		setupEditModel('#inventory-content', "item");
	},"id="+id);
}

function saveImage(id) {
	if (id==null || id ==undefined) {
		var imageUrl = $('#image_url-new').val();
		insertImage(imageUrl);
	}
	else {
		var imageUrl = $('#image_url-'+id).val();
		updateImage(id, imageUrl);
	} 
}

function updateImage(id, url) {
	var form_data = "id="+id+"&image_url="+url;
	$.ajax({
        url: bnd_rest_url+"bnd-rest-api/item-image/update",
        type : "POST",
        data : form_data,
        success : function(result) {
			if (result["status"]=="success") {
				reloadPage();
			}
        },
        error: function(xhr, resp, text) {
            console.log(xhr, resp, text);
        }
    });
}

function insertImage(url) {
	var item = $('#id').val();
	var form_data = "item_clid="+item+"&image_url="+url;
	$.ajax({
        url: bnd_rest_url+"bnd-rest-api/item-image/create",
        type : "POST",
        data : form_data,
        success : function(result) {
        	if (result["status"]=="success") {
        		reloadPage();
			}
        },
        error: function(xhr, resp, text) {
            console.log(xhr, resp, text);
        }
    });
}
</script>