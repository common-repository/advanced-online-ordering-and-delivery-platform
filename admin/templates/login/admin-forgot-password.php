<?php 
/**
 * for gotten password for administrator
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             2.0.0
 * @package           Bnd_Flex_Order_Delivery/admin/login/admin-forgot-password
 */

$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
?>
<div class="row">
	<div class="col-sm-3"></div>
	<div class="col-sm-6">
		<!-- /.login-logo -->
		<div class="card card-primary">
			<div class="card-header">Forgot password</div>
			<div class="card-body">
				<p>Please enter your email address to continue.</p>
				<form action="#" method="post" name="adminForgotPassword" name="adminForgotPassword">
					<div class="input-group mb-3">
						<input type="email" class="form-control" placeholder="Email" id="email" name="email"/>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button type="button" class="btn btn-primary btn-block" id="adminForgotPasswordRequest">Request
								new password</button>
						</div>
						<!-- /.col -->
					</div>
				</form>
				<p class="mt-3 mb-1">
					<a href="#" id="deviceSignIn">Login</a>
				</p>
			</div>
			<!-- /.card-body -->
		</div>
		<!-- /.card -->
	</div>
	<div class="col-sm-3"></div>
</div>
<script>
	$(document).ready(function(){
		initPasswordPageLinks();
	});
</script>
