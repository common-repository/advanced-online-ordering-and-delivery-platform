<?php
/**
 * Menu Items page
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
$cart_details = $repository->getCartDetails();
$no_items = (!$cart_details)|| (!$cart_details["total"]) || ($cart_details["total"]["item_count"]==0);
?>
<?php include_once 'category-header.php'; ?>
<div class="container position-relative">
	<div class="row">
		<div class="col-md-8 pt-3">
			<div id="bnd-cart-items-list">

			</div>
		</div>
		<div class="col-md-4 pt-3">
			<div class="cart-spinner-container" style="display:none">
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
    	showCartItems(function(){;
        	$('.cart-modify').click(function(){
    	    	var key = $(this).data("item");
    		    editAddOn(key);
    		});
    	    $('.cart-remove').click(function(){
    		    var key = $(this).data("item");
    		    var name = $(this).data("name");
    		    removeCart(key,name);
    		});
    	});
    }
	$(document).ready(function() {
		showCart();
	});
</script>