<?php
/**
 * Display partial section for address
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
$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$merchant = (array) $db->getMerchantAddress();
$data = $response;
$addressList = $addresses;
?>
<div class="mb-3 rounded shadow-sm bg-white overflow-hidden">
	<div class="bnd-cart-item-profile bg-white p-3">
		<div class="d-flex flex-column">
			<?php if (is_user_logged_in()) { ?>
			<div class="row" id="addressDetails">
				<?php
                //$addressList = $data["user_addresses"];
                if (! empty($addressList)) {
                    foreach ($addressList as $addr) {
                        $address = (array)$addr;
                        ?>  
			      	<div class="col-lg-6 ">
						<div class="mb-3 address-box rounded shadow-sm w-100 p-2">
							<div class="d-flex">
								<div class="align-items-center mb-2 p-3">
									<h6 class="mb-0"><?php echo $address["first_name"]." ".$address["last_name"];?></h6>
									<p class="small text-muted m-0"><?php echo $address["address1"].(isset($address["address2"])?"<br>".$address["address2"]:"").(isset($address["address3"])?"<br>".$address["address3"]:"");?></p>
									<p class="small text-muted m-0"><?php echo $address["city"]; ?>, <?php echo $address["state"]; ?> <?php echo $address["zip"]; ?></p>
									<p class="small text-muted m-0"><?php echo isset($address["country"])?$address["country"]:""; ?></p>
								</div>
							   <?php if ($address["is_default"]==1) {?>
						       <div class="mb-2 p-3">
									<span class="label label-default" style="padding:6px;border:1px solid #464646; border-radius:4px;">Default</span>
							   </div>
								<?php } else { ?> 
								<div class="mb-2 p-3">
									<button class="btn btn-outline-danger"
										style="height: 32px;"
										onclick="setDefaultProfileAddress('<?php echo $address["id"];?>')">Set Default</button>
								</div>
								<?php }?>
							</div>
							<div class="d-flex">
								<button class="btn btn-secondary border-top w-100 mr-1"
									style="display: block" onclick="editProfileAddress('<?php echo $address["id"]; ?>')">
									<i class="fas fa-edit"></i>&nbsp;Edit
								</button>
								<button class="btn btn-secondary border-top w-100"
									style="display: block"
									onclick="removeProfileAddress('<?php echo $address["id"]; ?>')">
									<i class="fas fa-trash"></i>&nbsp;Remove
								</button>
							</div>
						</div>
					</div>
			
			  <?php
                }
            } else {
                ?>
		      	<div class="col-lg-12">
				<p class="py-2">You do not have any addresses configure yet.
					Please add at least one address.</p>
			</div>
		  <?php } ?>
		  </div>
		  <div class="row">
		  	<div class="col-lg-6">
		  		<a class="btn btn-primary" href="#"  onclick="showAddressModal()" style="color:#fff"> ADD NEW ADDRESS </a>
		  	</div>
		  </div>
		  <?php  } ?>				  
		</div>
	</div>
</div>