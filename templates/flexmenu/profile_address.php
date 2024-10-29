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
$user = wp_get_current_user();
$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$countries = $db->getCountries();
$BndSettings = (array) get_option("bnd_settings");
$default_country_code = $BndSettings["default_country_code"];
?>
<div class="bnd-profile">
	<!-- profile -->
	<div class="container position-relative">
		<div class="py-5 bnd-profile row">
			<div class="col-md-4 mb-3">
				<?php include_once 'profile-left.php';?>
			</div>
			<div class="col-md-8 mb-3">
				<div class="rounded shadow-sm p-4 bg-white">
					<h6 class="mb-4">Delivery Addresses</h6>
					<div id="bnd_address_detail"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- modal delivery address -->
<div class="modal fade" id="addressModal" tabindex="-1" role="dialog"
	aria-labelledby="addressModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<form class="needs-validation" name="addressForm" id="addressForm">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add Address</h5>
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-row">
						<input type="hidden" name="id" value="" id="id">
						<div class=col-md-12">
							<div class="row">
        						<div class="col-md-6 form-group">
        							<label class="form-label required">First Name</label> <input
        								placeholder="First Name" type="text" class="form-control"
        								name="first_name" id="first_name">
        						</div>
        						<div class="col-md-6 form-group">
        							<label class="form-label required">Last Name</label> <input
        								placeholder="Last Name" type="text" class="form-control"
        								name="last_name" id="last_name">
        						</div>
        					</div>
        					
						</div>
						<div class="col-md-12 form-group">
							<label class="form-label required">Address Line 1</label> <input
								placeholder="House number, Building society name" type="text"
								class="form-control" name="address1"  id="address1">
						</div>
						<div class="col-md-12 form-group">
							<label class="form-label">Address Line 2</label> <input
								placeholder="Street name" type="text" class="form-control"
								name="aadress2"  id="address2">
						</div>
						<div class="col-md-12 form-group">
							<label class="form-label">Address Line 3</label> <input
								placeholder="Landmark" type="text" class="form-control"
								name="aadress3"   id="address3">
						</div>
						<div class="col-md-6 form-group">
							<label class="form-label required">City</label> <input
								placeholder="City" type="text" name="city" class="form-control"  id="city">
						</div>
						<div class="col-md-6 form-group">
							<label class="form-label required">State</label> <input
								placeholder="State" type="text" name="state"
								class="form-control"   id="state">
						</div>
						<div class="col-md-6 form-group">
							<label class="form-label required">Zip code</label> <input
								placeholder="Zip code" type="text" name="zip"
								class="form-control"   id="zip">
						</div>
						<div class="col-md-6 form-group">
							<label class="form-label required">Country</label> <select
								name="country" class="form-control">
                        	<?php foreach ($countries as $country) {
                        	    if ($country->code == $default_country_code) {
                        	    ?>
                        			<option value="<?php echo $country->code;?>" selected><?php echo $country->name; ?></option>
                        		<?php } else {?>
                        			<option value="<?php echo $country->code;?>"><?php echo $country->name; ?></option>
                        	<?php }
                        	}?>
                        	</select>
						</div>
						<div class="col-md-12 form-group">
							<label class="form-label  required">Phone number</label> <input
								placeholder="Phone number" type="text" class="form-control"
								name="phone_number"   id="phone_number">
						</div>
						<div class="col-md-12 form-group">
							<label class="form-label required">Email</label> <input
								placeholder="Email" type="text" class="form-control"
								name="email"   id="email">
						</div>
						<div class="col-md-12 form-group">
							<label class="form-label">Label this address</label> <input
								placeholder="Address Label" type="text" class="form-control"
								name="address_type" id="address_type">
						</div>
					</div>
				</div>
				<div class="modal-footer p-0 border-0">
					<div class="col-6 m-0 p-0">
						<button type="button" class="btn border-top btn-lg btn-block"
							data-dismiss="modal">Close</button>
					</div>
					<div class="col-6 m-0 p-0">
						<button type="button" class="btn btn-primary btn-lg btn-block"
							onclick="saveProfileAddress()">Save changes</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
function updatePageData() {
	showProfileAddress();
}

$(document).ready(function() {
	updatePageData();
	  // Initialize form validation on the registration form.
	  // It has the name attribute "registration"
	  $("#addressForm").validate({
	    // Specify validation rules
	    rules: {
	      // The key name on the left side is the name attribute
	      // of an input field. Validation rules are defined
	      // on the right side
	      name: "required",
	      address1: "required",
	      city:"required",
	      state:"required",
	      zip:"required",
	      email: {
	        required: true,
	        // Specify that email should be validated
	        // by the built-in "email" rule
	        email: true
	      },
	      phone_number: {
	        required: true,
	        minlength: 7
	      }
	    },
	    // Specify validation error messages
	    messages: {
	      name: "Please enter your name",
	      email: "Please enter a valid email address"
	    }
	  }); 
});
</script>