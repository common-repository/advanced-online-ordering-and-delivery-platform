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
?>
<div class="bg-white rounded shadow-sm sticky_sidebar overflow-hidden">
<?php if (is_user_logged_in()) {
    $user = wp_get_current_user();
    $db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
    $customer = (array)$db->getCustomer($user->user_email);
    ?>
	<a href="<?php echo buynowdepot_get_page_url('bnd-profile');?>" class="">
		<div class="d-flex align-items-center p-3">
			<div class="right">
				<h6 class="mb-1 font-weight-bold">
					Welcome <?php echo $customer["first_name"]." ".$customer["last_name"]; ?>&nbsp;
				</h6>
				<p class="text-muted m-0 small"><?php echo $user->data->user_email ?></p>
			</div>
		</div>
	</a>
	<a href="<?php echo buynowdepot_get_page_url('bnd-profile-address');?>"
		class="d-flex w-100 align-items-center border-bottom p-3">
		<div class="left mr-3">
			<h6 class="font-weight-bold mb-1 text-dark">Address</h6>
			<p class="small text-muted m-0">Add or remove a delivery address</p>
		</div>
		<div class="right ml-auto">
			<h6 class="font-weight-bold m-0">
				<i class="fas fa-chevron-right"></i>
			</h6>
		</div>
	</a> 
	<?php } ?> 
	<!-- profile-details -->
	<div class="bg-white profile-details">
		<a href="<?php echo buynowdepot_get_page_url('bnd-faq');?>"
			class="d-flex w-100 align-items-center border-bottom px-3 py-4">
			<i class="far fa-question-circle mr-2" style="width:32px;height:32px"></i>
			<div class="left mr-3">
				<h6 class="font-weight-bold m-0 text-dark">
					Help & Support
				</h6>
			</div>
			<div class="right ml-auto">
				<h6 class="font-weight-bold m-0">
					<i class="fas fa-chevron-right"></i>
				</h6>
			</div>
		</a> 
		<a href="<?php echo buynowdepot_get_page_url('bnd-contact-us');?>"
			class="d-flex w-100 align-items-center border-bottom px-3 py-4">
			<i class="fas fa-phone-square mr-2" style="width:32px;height:32px"></i>
			<div class="left mr-3">
				<h6 class="font-weight-bold m-0 text-dark">
					Contact
				</h6>
			</div>
			<div class="right ml-auto">
				<h6 class="font-weight-bold m-0">
					<i class="fas fa-chevron-right"></i>
				</h6>
			</div>
		</a> 
		<a href="<?php echo buynowdepot_get_page_url('bnd-terms');?>"
			class="d-flex w-100 align-items-center border-bottom px-3 py-4">
			<i class="far fa-handshake mr-2" style="width:32px;height:32px"></i>
			<div class="left mr-3">
				<h6 class="font-weight-bold m-0 text-dark">
					Term of use
				</h6>
			</div>
			<div class="right ml-auto">
				<h6 class="font-weight-bold m-0">
					<i class="fas fa-chevron-right"></i>
				</h6>
			</div>
		</a> <a href="<?php echo buynowdepot_get_page_url('bnd-privacy');?>"
			class="d-flex w-100 align-items-center px-3 py-4">
			<i class="fas fa-user-lock mr-2" style="width:32px;height:32px"></i>
			<div class="left mr-3">
				<h6 class="font-weight-bold m-0 text-dark">
					Privacy policy
				</h6>
			</div>
			<div class="right ml-auto">
				<h6 class="font-weight-bold m-0">
					<i class="fas fa-chevron-right"></i>
				</h6>
			</div>
		</a>
	</div>
</div>
<?php if (is_user_logged_in()) {?>
<div class="modal fade" id="paycard" tabindex="-1" role="dialog"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Credit/Debit Card</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h6 class="m-0">Add new card</h6>
				<p class="small">
					WE ACCEPT <span class="bnd-card ml-2 font-weight-bold">( Master
						Card / Visa Card / Rupay )</span>
				</p>
				<form>
					<div class="form-row">
						<div class="col-md-12 form-group">
							<label class="form-label font-weight-bold small">Card number</label>
							<div class="input-group">
								<input placeholder="Card number" type="number"
									class="form-control">
								<div class="input-group-append">
									<button type="button" class="btn btn-outline-secondary">
										<i class="feather-credit-card"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="col-md-8 form-group">
							<label class="form-label font-weight-bold small">Valid
								through(MM/YY)</label><input
								placeholder="Enter Valid through(MM/YY)" type="number"
								class="form-control">
						</div>
						<div class="col-md-4 form-group">
							<label class="form-label font-weight-bold small">CVV</label><input
								placeholder="Enter CVV Number" type="number"
								class="form-control">
						</div>
						<div class="col-md-12 form-group">
							<label class="form-label font-weight-bold small">Name on card</label><input
								placeholder="Enter Card number" type="text" class="form-control">
						</div>
						<div class="col-md-12 form-group mb-0">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" id="custom-checkbox1"
									class="custom-control-input"><label title="" type="checkbox"
									for="custom-checkbox1" class="custom-control-label small pt-1">Securely
									save this card for a faster checkout next time.</label>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer p-0 border-0">
				<div class="col-6 m-0 p-0">
					<button type="button" class="btn border-top btn-lg btn-block"
						data-dismiss="modal">Close</button>
				</div>
				<div class="col-6 m-0 p-0">
					<button type="button" class="btn btn-primary btn-lg btn-block">Save
						changes</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Delivery Address</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="">
					<div class="form-row">
						<div class="col-md-12 form-group">
							<label class="form-label">Delivery Area</label>
							<div class="input-group">
								<input placeholder="Delivery Area" type="text"
									class="form-control">
								<div class="input-group-append">
									<button type="button" class="btn btn-outline-secondary">
										<i class="feather-map-pin"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="col-md-12 form-group">
							<label class="form-label">Complete Address</label><input
								placeholder="Complete Address e.g. house number, street name, landmark"
								type="text" class="form-control">
						</div>
						<div class="col-md-12 form-group">
							<label class="form-label">Delivery Instructions</label><input
								placeholder="Delivery Instructions e.g. Opposite Gold Souk Mall"
								type="text" class="form-control">
						</div>
						<div class="mb-0 col-md-12 form-group">
							<label class="form-label">Nickname</label>
							<div class="btn-group btn-group-toggle w-100"
								data-toggle="buttons">
								<label class="btn btn-outline-secondary active"> <input
									type="radio" name="options" id="option1" checked> Home
								</label> <label class="btn btn-outline-secondary"> <input
									type="radio" name="options" id="option2"> Work
								</label> <label class="btn btn-outline-secondary"> <input
									type="radio" name="options" id="option3"> Other
								</label>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer p-0 border-0">
				<div class="col-6 m-0 p-0">
					<button type="button" class="btn border-top btn-lg btn-block"
						data-dismiss="modal">Close</button>
				</div>
				<div class="col-6 m-0 p-0">
					<button type="button" class="btn btn-primary btn-lg btn-block">Save
						changes</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Invite Modal-->
<div class="modal fade" id="inviteModal" tabindex="-1"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h5 class="modal-title font-weight-bold text-dark">Invite</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body py-0">
				<button class="btn btn-light text-primary btn-sm">
					<i class="feather-plus"></i>
				</button>
				<span class="ml-2 smal text-primary">Send an invite to a friend</span>
				<p class="mt-3 small">Invited friends (2)</p>
				<div class="d-flex align-items-center mb-3">
					<div class="mr-3">
						<img alt="#" src="img/user1.jpg" class="img-fluid rounded-circle">
					</div>
					<div>
						<p class="small font-weight-bold text-dark mb-0">Kate Simpson</p>
						<P class="mb-0 small">katesimpson@outbook.com</P>
					</div>
				</div>
				<div class="d-flex align-items-center mb-3">
					<div class="mr-3">
						<img alt="#" src="img/user2.png" class="img-fluid rounded-circle">
					</div>
					<div>
						<p class="small font-weight-bold text-dark mb-0">Andrew Smith</p>
						<P class="mb-0 small">andrewsmith@ui8.com</P>
					</div>
				</div>
			</div>
			<div class="modal-footer border-0"></div>
		</div>
	</div>
</div>
<?php } ?>