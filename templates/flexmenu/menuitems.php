<?php
/**
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 *
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
defined('ABSPATH') || exit();
$theme_url = buynowdepot_get_theme_url();
$repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
$params = array();
$params["with_items"] = true;
$categories = $repository->getCategoriesItems($params);
$catCount = count($categories);
if ($catCount>5) {
    $catCount=5;
}
$show_blank_categories = buynowdepot_get_option("show_blank_categories");
?>
<div class="container">
	<div class="sticky_sidebar">
		<p class="font-weight-bold pt-3 m-0">Categories</p>
		<!-- slider -->
		<div class="menu-category-slider rounded" id="bnd-category-list">
		<?php
		$count=0;
        foreach ($categories as $category) {
            if ($show_blank_categories==0 && $category["item_count"]==0)
                continue;
            ?>
			<div class="bnd-slider-item"
				data-clid="<?php echo $category["clid"];?>">
				<div
					class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
					<div class="list-card-image">
						<img alt="<?php echo $category["name"];?>" src="<?php echo $category["image_link"];?>"
							class="img-fluid item-img w-100">
					</div>
					<div class="p-1 position-relative">
						<div class="list-card-body">
							<div class="heading mb-1">
								<span class="text-black"><?php echo $category["name"];?></span>
							</div>
							<p class="text-gray mb-3"><?php echo $category["description"];?></p>
						</div>
					</div>
				</div>
			</div>
		<?php
		$count++;
}
?>
		</div>
	</div>
</div>
<div class="container position-relative">
	<div class="row">
		<div class="col-md-8 pt-3">
			<div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
				<div class="d-flex item-aligns-center">
					<p class="font-weight-bold h5 p-3 border-bottom mb-0 w-100">Menu</p>
				</div>
				<div id="bnd-item-list">
				<?php
                foreach ($categories as $category) {
                    if ($show_blank_categories==0 && $category["item_count"]==0)
                        continue;
                    ?>
        			<div class="row m-0"
						id="category-<?php echo $category["clid"];?>">
						<div class="p-3 m-0 bg-light w-100">
							<div class="category-heading"><?php echo $category["name"];?> <small class="text-black-50"><?php echo $category["item_count"];?> ITEMS</small>
							</div>
							<span class="cat-description"><?php echo $category["description"];?></span>
						</div>
						<div class="col-md-12 px-0 border-top">
                        <?php
                        foreach ($category["items"] as $item) {
                            ?>
							<div class="p-3 border-bottom">
								<div class="media">
									<div class="image-area">
										<img alt="#" src="<?php echo $item["image_link"];?>"
											alt="<?php echo $item["name"];?>"
											class="mr-3 rounded menu-item-image">
                						<?php
                                        foreach ($item["tags"] as $tag) {
                                            ?>
                                        <img class="tag-icon"
											src="<?php echo $tag["image_link"];?>"
											alt="<?php echo $tag["name"];?>" />
                                        <?php
                                        }
                                        ?>
                                    </div>
									<div class="media-body">
										<div class="item-heading mb-1"><?php echo $item["name"];?>&nbsp;<span
												class="badge badge-danger"><?php echo $item["label"];?></span>
										</div>
										<p class="item-description"><?php echo $item["description"];?></p>
										<div class="menu-selection">
										<input type="hidden" id="hdn-item-price-<?php echo $item["clid"];?>" value="<?php echo $item["price"];?>"/>
                                        <?php
                                        if (isset($item["modifiers"]) && count($item["modifiers"]) > 0) {
                                            foreach ($item["modifiers"] as $key => $modifier) {
                                                if ($modifier["group_type"] == "select") {
                                                    ?>
                                        		<div class="menu-selection-item first">
												<select name="bnd_item_modifier_<?php echo $item["clid"]?>_<?php echo $modifier["id"]?>" id="bnd_item_modifier_<?php echo $item["clid"]?>_<?php echo $modifier["id"]?>">
                                        			<?php
                                                    foreach ($modifier["items"] as $moditem) {
                                                        ?>
                                        				<option
														value="<?php echo $moditem["id"];?>"><?php echo $moditem["name"]." $".$moditem["price"];?></option>
                                        			<?php
                                                    }
                                                    ?>
                                        			</select>
													<input type="hidden" id="hdn-item-mod-<?php echo $item["clid"];?>" value="<?php echo $modifier["id"]?>"/>
											</div>
                						<?php
                                                }
                                            }
                                        } else {
                                            ?>
                							<div class="menu-selection-item first">   
                								<div class="selectBox selectBox-dropdown" style="color:#464646">          								
                                        			<span class="selectBox-label"><?php echo $item["price"];?></span>
                                        		</div>
											</div>
                						<?php
                                        }
                                        ?>
                                        </div>
									</div>
									<div class="item-button-area">
										<div class="item-button-area-inner">
											<div class="input-group order-button"  data-item="<?php echo $item["clid"];?>">
												<div class="input-group-prepend"><span class="input-group-text text-dark"><i class="fas fa-shopping-cart"></i></span></div>
												<div class="btn btn-success form-control add-to-order"	data-target="#extras">Add To Order</div>
											</div>
										</div>
									</div>
								</div>
							</div>
                        <?php
                        }
                        ?>
                    	</div>
					</div>
                <?php
                }
                ?>
    		    </div>
			</div>
		</div>
		<div class="col-md-4 pt-3">
			<div class="cart-spinner-container rounded shadow-sm overflow-hidden">
            	<div class="spinner-border text-danger" role="status" id="cart-spinner">
                  <span class="sr-only">Loading...</span>
                </div>
            </div>
			<div class="bnd-cart-item rounded shadow-sm overflow-hidden sticky_sidebar">
				<div class="cart-header  border-bottom">
					<div class="cart-icon"><i class="fas fa-utensils"></i></div>
					<div class="cart-header-text font-weight-bold h6 p-3 mb-0 w-100"><h6>Your Order</h6></div>
				</div>
				<div id="bnd-cart-items">
				</div>
			</div>
		</div>
	</div>
	<!-- extras modal -->
	<div class="modal fade" id="extras" tabindex="-1" role="dialog"
		aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content"  id="bnd-extras-container">				
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function updatePageData() {
	}
	
	$(document).ready(function() {
		
		displayCategorySlider(<?php echo $catCount; ?>);
		$('select').selectBox();
		$('.order-button').click(function(){
			var item = $(this).data("item");
			var selectedModifierGroupId = $('#hdn-item-mod-'+item).val();
			var selectedModifier = $("#bnd_item_modifier_"+item+"_"+selectedModifierGroupId).val();
			openAddOn(item, selectedModifier);
		});
		showCart();
		document.querySelectorAll('img').forEach(function(img){
		  	img.onerror = function(){this.style.display='none';};
		});
	});
</script>