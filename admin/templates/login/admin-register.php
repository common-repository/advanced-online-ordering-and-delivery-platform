<?php 
/**
 * registration page for administrator
 *
 * © Copyright 2023  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             2.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/admin/login/admin-register
 */

?>
<div class="row">
	<div class="col-sm-3"></div>
	<div class="col-sm-6">
		<!-- /.login-logo -->
		<div class="card card-primary">
			<div class="card-header">Register Membership</div>
			<div class="card-body">
				<p>Please enter your details. The email address mentioned will be used as your primary email for Buy Now Depot's Mobile POS.</p>
				<form action="#" method="post" name="adminRegisterForm" id="adminRegisterForm">
					<div class="input-group mb-3">
						<input type="email" class="form-control" placeholder="Email"  id="email" name="email"/>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" class="form-control"
							placeholder="Password" id="password" name="password"/>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" class="form-control"
							placeholder="Retype password"  id="confirmPassword" name="confirm_password"/>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-8">
							<div class="icheck-primary">
								<input type="checkbox" id="agreeTerms" name="terms"
									value="agree"/> <label for="agreeTerms"> I
									agree to the <a href="#">terms</a>
								</label>
							</div>
						</div>
						<!-- /.col -->
						<div class="col-4">
							<button type="button" class="btn btn-primary btn-block" id="deviceAdminRegister">Register</button>
						</div>
						<!-- /.col -->
					</div>
				</form>
				<p class="mb-0">
					<a href="#" class="text-center" id="deviceSignIn" onclick="loadAdminLogin()">I already have an account</a>
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
    	initRegisterPageLinks();
    });
</script>
