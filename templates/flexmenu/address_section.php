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
<!-- Nav tabs -->
<ul class="nav nav-tabs nav-fill" role="tablist"
	style="background-color: #fafafa">
	<li class="nav-item"><a href="#delivery" role="tab"
		data-toggle="tab"
		class="nav-link  <?php echo ($data["order_type"]=='delivery')?"active":"";?>">
			Deliver it to me &nbsp;<i class="fas fa-truck"></i>
	</a></li>
	<li class="nav-item"><a href="#pickup" role="tab"
		data-toggle="tab"
		class="nav-link  <?php echo ($data["order_type"]=='pickup')?"active":"";?>">
			<i class="fas fa-shopping-bag"></i> &nbsp;I'll Pick up
	</a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
	<div
		class="tab-pane fade  <?php echo ($data["order_type"]=='delivery')?"show":"";?> <?php echo ($data["order_type"]=='delivery')?"active":"";?>"
		id="delivery">
		<div class="mb-3 rounded shadow-sm bg-white overflow-hidden">
			<div class="bnd-cart-item-profile bg-white p-3">
				<div class="d-flex flex-column">
					<h6 class="mb-3 font-weight-bold">Delivery Address</h6>
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
    									<?php if (!empty($data["delivery_address"]) && ($address["id"]==$data["delivery_address"]["id"])) {?>
    									<div class="mb-2 p-3">
    										<span class="label label-default" style="padding:6px;border:1px solid #464646; border-radius:4px;">Selected</span>
    									</div>
    									<?php } else if ($address["is_default"]==1) {?>
								       <div class="mb-2 p-3">
    										<span class="label label-default" style="padding:6px;border:1px solid #464646; border-radius:4px;">Selected</span>
    								   </div>
    									<?php } else { ?> 
    									<div class="mb-2 p-3">
    										<button class="btn btn-outline-danger"
    											style="height: 32px;"
    											onclick="selectAddress('<?php echo $address["id"];?>')">Select</button>
    									</div>
    									<?php }?>
    								</div>
    								<div class="d-flex">
    									<button class="btn btn-secondary border-top w-100 mr-1"
    										style="display: block" onclick="editAddress('<?php echo $address["id"]; ?>')">
    										<i class="fas fa-edit"></i>&nbsp;Edit
    									</button>
    									<button class="btn btn-secondary border-top w-100"
    										style="display: block"
    										onclick="removeAddress('<?php echo $address["id"]; ?>')">
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
				  <?php  } 
				  else { 
				      if (isset($data["delivery_address"]) && !empty($data["delivery_address"])) {
				          
				        $address = $data["delivery_address"];
				  ?>
    			      <div class="row" id="addressDetails">
    			      	<div class="col-lg-6 ">
    							<div class="mb-3 address-box rounded shadow-sm w-100 p-2">
    								<div class="d-flex">
    									<div class="align-items-center mb-2 p-3">
    										<h6 class="mb-0"><?php echo $address["first_name"]." ".$address["last_name"];?></h6>
    										<p class="small text-muted m-0"><?php echo $address["address1"].(isset($address["address2"])?"<br>".$address["address2"]:"").(isset($address["address3"])?"<br>".$address["address3"]:"");?></p>
    										<p class="small text-muted m-0"><?php echo $address["city"]; ?>, <?php echo $address["state"]; ?> <?php echo $address["zip"]; ?></p>
    										<p class="small text-muted m-0"><?php echo isset($address["country"])?$address["country"]:""; ?></p>
    									</div>
    								</div>
    								<div class="d-flex">
    									<button class="btn btn-secondary border-top w-100 mr-1"
    										style="display: block" onclick="editAddress('<?php echo $address["id"]; ?>')">
    										<i class="fas fa-edit"></i>&nbsp;Edit
    									</button>
    									<button class="btn btn-secondary border-top w-100"
    										style="display: block"
    										onclick="removeAddress('<?php echo $address["id"]; ?>')">
    										<i class="fas fa-trash"></i>&nbsp;Remove
    									</button>
    								</div>
    							</div>
							</div>
    			      </div>
    			  <?php } else { ?>
			      <div class="row" id="addressDetails">
			      	<div class="col-lg-12">
						<p class="py-2">You do not have any addresses configure yet.
							Please add at least one address.</p>
					</div>
				  	<div class="col-lg-6">
				  		<a class="btn btn-primary" href="#" onclick="showAddressModal()" style="color:#fff"> ADD NEW ADDRESS </a>
				  	</div>
				  </div>
				  <?php }
				  }
				  ?>				
				</div>
			</div>
		</div>
	</div>
	<div
		class="tab-pane fade  <?php echo ($data["order_type"]=='pickup')?"show":"";?> <?php echo ($data["order_type"]=='pickup')?"active":"";?>"
		id="pickup">
		<div
			class="bnd-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">
			<div class="bnd-cart-item-profile bg-white p-3">
				<div class="w-100">
					<h6 class="mb-3 font-weight-bold">You will pick up from :</h6>
					<div class="d-flex bg-white rounded shadow-sm w-50 border">
						<div class="p-3">
							<div class="align-items-center mb-2">
								<h6 class="mb-0"><?php echo $merchant["name"]?></h6>
							</div>
							<p class="small text-muted m-0"><?php echo $merchant["address1"].(isset($merchant["address2"])?"<br>".$merchant["address2"]:"").(isset($merchant["address3"])?"<br>".$merchant["address3"]:"");?></p>
							<p class="small text-muted m-0"><?php echo $merchant["city"].",". $merchant["state"]." ".$merchant["zip"];?></p>
						</div>
						<div class="mb-2 p-3" style="text-align: right">
							<button class="btn btn-outline-danger"
								style="height: 32px; width: 100px"
								onclick="confirmPickup()">Confirm</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>