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
$theme_url = buynowdepot_get_theme_url();
$model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$result = $model->getCurrentMerchant();
?>
<div class="bnd-profile">
	<div class="d-none">
		<div class="bg-primary border-bottom p-3 d-flex align-items-center">
			<a class="toggle togglew toggle-2" href="#"><span></span></a>
			<h4 class="font-weight-bold m-0 text-white">Profile</h4>
		</div>
	</div>
	<!-- profile -->
	<div class="container position-relative">
		<div class="py-5 bnd-profile row">
			<div class="col-md-4 mb-3">
				<?php include 'profile-left.php';?>
			</div>
			<div class="col-md-8 mb-3">
				<div class="rounded shadow-sm mb-1">
					<div
						class="bnd-cart-item-profile bg-white rounded shadow-sm p-4">
					<h6 class="font-weight-bold">You can contact us at</h6>
					<div
						class="d-flex">
						<div class="p-3 mr-3">
							<strong><?php echo ($result!=null)?$result->name:""; ?></strong><br/>
							<?php if ($result!=null) {
							    echo $result->address1."\n".$result->address2."\n".$result->address3;
								}?><br/>
							<?php echo $result->city." ".$result->state." ".$result->zip." ".$result->state;?><br/>
							Phone :<?php echo $result->phone_number;?><br/>
							Email :<?php echo $result->contact_email;?><br/>
						</div>
						<div class="right">
							<div class="gmap_canvas">
								<iframe width="400" height="300" id="gmap_canvas"
									src="https://maps.google.com/maps?q=florida&t=&z=10&ie=UTF8&iwloc=&output=embed"
									frameborder="0" scrolling="no" marginheight="0"
									marginwidth="0"></iframe>
							</div>
						</div>
					</div>
					</div>
				</div>
				<div class="rounded shadow-sm">
					<div
						class="bnd-cart-item-profile bg-white rounded shadow-sm p-4">
						<div class="flex-column">
							<h6 class="font-weight-bold">Tell us about yourself</h6>
							<p class="text-muted">Whether you have questions or you would
								just like to say hello, contact us.</p>
							<form name="contactForm" action="">
								<div class="form-group">
									<label for="exampleFormControlInput1"
										class="small font-weight-bold">Your Name</label> <input
										type="text" class="form-control" id="exampleFormControlInput1"
										placeholder="">
								</div>
								<div class="form-group">
									<label for="exampleFormControlInput2"
										class="small font-weight-bold">Email Address</label> <input
										type="email" class="form-control"
										id="exampleFormControlInput2"
										placeholder="">
								</div>
								<div class="form-group">
									<label for="exampleFormControlInput3"
										class="small font-weight-bold">Phone Number</label> <input
										type="text" class="form-control"
										id="exampleFormControlInput3" placeholder="1-234-567-8900">
								</div>
								<div class="form-group">
									<label for="exampleFormControlTextarea1"
										class="small font-weight-bold">HOW CAN WE HELP YOU?</label>
									<textarea class="form-control" id="exampleFormControlTextarea1"
										placeholder="Hi there, I would like to ..." rows="3"></textarea>
								</div>
								<div class="btn btn-primary btn-block" onclick="submtForm('contactForm')">SUBMIT</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>