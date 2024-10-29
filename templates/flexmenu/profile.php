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
$customer = (array)$db->getCustomer($user->user_email);
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
				<?php include_once 'profile-left.php';?>
			</div>
			<div class="col-md-8 mb-3">
				<div class="rounded shadow-sm p-4 bg-white">
					<h6 class="mb-4">Profile Details</h6>
					<div id="edit_profile">
						<div>
							<form action="#" name="profileForm" id="profileForm">
								<div class="form-group">
									<label for="exampleInputName1">First Name</label> <input
										type="text" class="form-control" id="firstName" name="first_name"
										value="<?php echo $customer["first_name"]?>">
								</div>
								<div class="form-group">
									<label for="exampleInputName1">Last Name</label> <input
										type="text" class="form-control" id="lastName" name="last_name"
										value="<?php echo $customer["last_name"]?>">
								</div>
								<div class="form-group">
									<label for="exampleInputNumber1">Mobile Number</label> <input
										type="number" class="form-control" id="mobileNumber" name="mobile_number"
										value="<?php echo $customer["mobile_number"]?>">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1">Email</label> <input
										type="email" class="form-control" id="email" name="email"
										value="<?php echo $customer["email"]?>" disabled="disabled">
								</div>
								<div class="text-center">
									<button type="button" class="btn btn-primary btn-block" onclick="saveProfile()">Save
										Changes</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="rounded shadow-sm p-4 bg-white">
					<h6 class="mb-4">Change Password</h6>
					<div id="change_password">
						<div>
							<form action="#" name="passwordForm" id="passwordForm">
								<div class="form-group">
									<label for="newPassword">New Password</label> <input
										type="password" class="form-control" id="firstName" name="new_password"
										value="">
								</div>
								<div class="form-group">
									<label for="changePassword">Confirm Password</label> <input
										type="password" class="form-control" id="lastName" name="confirm_password"
										value="">
								</div>
								<div class="text-center">
									<button type="button" class="btn btn-primary btn-block" onclick="savePassword()">Update Password</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<<script type="text/javascript">
$("#profileForm").validate({
    // Specify validation rules
    rules: {
      mobile_number: {
        minlength: 7
      }
    },
    // Specify validation error messages
    messages: {
      mobile_number: "Please enter a valid mobile_number"
    }
  });
$("#passwordForm").validate({
    // Specify validation rules
    rules: {
      new_password: {
        required: true
      },
      confirm_password: {
          required: true
      }
    }
  });
</script>
