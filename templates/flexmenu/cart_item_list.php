<?php
/**
 * Displays list of items in the cart on the cart view page. 
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link       https://buynowdepot.com
 * @since      1.0.0
 *
 * @package    Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/templates/flexmenu
 */
?>
<?php 
$cart_details = $response;
$no_items=($cart_details["total"]["item_count"]==0);
?>
<div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
	<div class="d-flex item-aligns-center">
		<p class="font-weight-bold h6 p-3 border-bottom mb-0 w-100">My Cart (<?php echo ($no_items)?"":$cart_details["total"]["item_count"]?> Items)</p>
	</div>
	<div id="bnd-item-list">
	<?php
	if ($no_items) { ?>
		<div class="row m-0">
			<div class="col-md-12 py-5 px-3 border-top">
	    		<?php echo "You do not have any item in the cart." ?>
	    	</div>
		</div>
	<?php 
	} else { ?>
		<div class="row m-0">
			<div class="col-md-12 px-0 border-top">
            <?php
            foreach ($cart_details["lineItems"] as $item) {
                ?>
				<div class="p-3 border-bottom">
					<div class="media">
						<div class="image-area">
							<img alt="#" src="<?php echo $item["image_url"];?>"
								alt="<?php echo $item["name"];?>"
								class="me-3 rounded menu-item-image">
                        </div>
						<div class="media-body">
							<h6 class="mb-1"><strong><?php echo $item["name"];?></strong>
							</h6>
							<p class="text-muted"><?php echo isset($item["modifiers"])?$item["modifiers"]:"";?>                                        
							<div class="d-flex">
								<div class="main-cart-quantity">Quantity: <?php echo $item["quantity"];?></div>
								<div class="main-cart-price">Subtotal: <?php echo buynowdepot_format_price($item["subtotal"]);?></div>
							</div>
						</div>
						<div class="item-button-area">
							<div class="item-button-area-inner">
								<div class="input-group cart-modify"  data-item="<?php echo $item["key"];?>" data-name="<?php echo $item["name"];?>">
									<div class="input-group-prepend"><span class="input-group-text"><i class="feather-edit"></i></span></div>
									<button class="btn btn-success form-control"	data-target="#extras">Modify</button>
								</div>
								<div class="mb-1"></div>
								<div class="input-group cart-remove"  data-item="<?php echo $item["key"];?>"  data-name="<?php echo $item["name"];?>">
									<div class="input-group-prepend"><span class="input-group-text"><i class="feather-trash-2"></i></span></div>
									<button class="btn btn-secondary form-control"	data-target="#extras">Remove</button>
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