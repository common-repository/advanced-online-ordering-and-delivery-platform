<?php 
/**
 * Login page for administrator
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             2.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/message-template
 */

$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
?>
<div class="row">
	<div class="col-sm-3"></div>
	<div class="col-sm-6">
	<!-- /.login-logo -->
	<div class="card card-primary">
		<div class="card-header">
			Sign In
		</div>
		<div class="card-body">
			<div class="border border-warning rounded p-2 text-center"><strong>This website is not registered with Buy Now Depot's Mobile POS</strong></div>
			<p>Please enter your login information to continue. If you do not have a login, please click on the "Register a new account" link below.</p>
			<form action="#" method="post" name="adminLoginForm" id="adminLoginForm">
				<div class="input-group mb-3">
					<input type="email" class="form-control" placeholder="Email" name="email" id="email"/>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-envelope"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" class="form-control"
						placeholder="Password" name="password" id="password"/>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-8">
						<div class="icheck-primary">
							<input type="checkbox" id="remember" /> <label for="remember">
								Remember Me </label>
						</div>
					</div>
					<!-- /.col -->
					<div class="col-4">
						<button type="button" class="btn btn-primary btn-block" id="deviceSignIn">Sign In</button>
					</div>
					<!-- /.col -->
				</div>
			</form>
			<p class="mb-1">
				<a href="#" id="deviceForgotPassword" >I forgot my password</a>
			</p>
			<p class="mb-0">
				<a href="#" class="text-center" id="deviceRegisterNew">Register a new
					account</a>
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
    	initLoginPageLinks();
 });
</script>
