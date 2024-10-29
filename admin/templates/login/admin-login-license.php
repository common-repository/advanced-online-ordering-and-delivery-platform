<template>
<div class="page no-toolbar no-navbar no-swipeback login-screen-page">
	<div class="page-content login-screen-content">
		<div class="item-content login-logo">
			<div class="item-media">
				<img src="assets/themes/aood/img/logo.png" class="login-image" />
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-6">
			<!-- /.login-logo -->
			<div class="card card-primary">
				<div class="card-header">
					Sign In
				</div>
				<div class="card-body">
					<div class="border border-success rounded p-2 text-center"><strong>This device is registered with Buy Now Depot's Mobile POS</strong></div>
					<p>Please enter your login information to continue.</p>
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
								<button type="button" class="btn btn-primary btn-block" id="deviceSignIn">Sign
									In</button>
							</div>
							<!-- /.col -->
						</div>
					</form>
					<p class="mb-1">
						<a href="#" id="deviceForgotPassword">I forgot my password</a>
					</p>
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
			</div>
			<div class="col-sm-3"></div>
		</div>
	</div>
	<div class="admin-login-button">
		<button class="btn  btn-app btn-primary" id="employeeLoginButton"><i class="fas fa-user"></i> Employees</button>
	</div>
</div>
</template>
<script>
  export default (props, { $on, $f7, $f7router}) => {
	  
    $on('pageInit', () => {
    	hideMenuHeader();
    	initPageLinks();
    });
    
    function initPageLinks() {
    	$("#adminLoginForm").validate({
		    // Specify validation rules
		    rules: {
		      // The key name on the left side is the name attribute
		      // of an input field. Validation rules are defined
		      // on the right side
		      password: "required",
		      email: {
		        required: true,
		        // Specify that email should be validated
		        // by the built-in "email" rule
		        email: true
		      }
		    },
		    // Specify validation error messages
		    messages: {
		    	email: "Please enter a valid email address",
		    	password: "Please enter password",
		    },
		    errorPlacement: function (error, element) {
	            if (element.parent().hasClass('input-group')) {
	                error.insertBefore(element.parent());
	            } else {
	                error.insertBefore(element);
	            }
	        }
		}); 
    	$('#deviceSignIn').on("click", function(){
    		var valid = $("#adminLoginForm").valid();
    		if (!valid) {
    			return;
    		}
    		var value=$('#adminLoginForm').serializeObject();
    		var loginData = {
       			email:value["email"],
       			password:value["password"],
       		};
       		console.log(loginData);
       		validateAdminLogin(loginData).then(function(response){
       			if (response["status"]=="success") {
       				$f7router.navigate("/configuration/");
       	    	}
       		    else {
       		    	bootbox.alert("Invalid credentials. Please try again.");
         		        return false; 
       		   	}
       		});
    	});
    	$('#deviceForgotPassword').on("click", function(){
    		$f7router.navigate("/admin-forgot-password/");
    	});
    	$('#employeeLoginButton').on("click", function(){
    		$f7router.navigate("/login-screen-page/");
    	});
    }
    return $render;
  };

</script>
