function loadAdminLogin() {
	loadTemplate("login/admin-login", ".app-panel-login");
}

function loadAdminRegister() {
	loadTemplate("login/admin-register", ".app-panel-login");
}

function loadAdminForgotPassword() {
	loadTemplate("login/admin-forgot-password", ".app-panel-login");
}

function loadAdminlicense() {
	loadTemplate("login/admin-login-license", ".app-panel-login");
}

function initLoginPageLinks() {
	$("#adminLoginForm").validate({
	    // Specify validation rules
	    rules: {
	      // The key name on the left side is the name attribute
	      // of an input field. Validation rules are defined
	      // on the right side
	      password: "required",
	      code:"required",
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
	    	code: "Please enter activation code"
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
		var client = new BNDClient();
		var registerData = {
			email:value["email"],
			password:value["password"],
			code:value["code"],
			device_id:'wpadmin'
		};
		console.log(registerData);
	    client.registerDevice(registerData).then(function(response){
	    	if (response["status"]=="success") {
	    		console.log(response);
		    	var success = saveLicenseInfo(response["content"]);
    	    	showToast('Website registered sucessfully', 'Save successful',"success");
    	    	//location.reload();
	    	}
		    else {
		    	bootbox.alert("Website could not be registered."+response["content"]);
  		        return false; 
		   	}
	    });
	});
	
	$('#deviceRegisterNew').on("click", function(){
		loadAdminRegister();
	});
	$('#deviceForgotPassword').on("click", function(){
		loadAdminForgotPassword();
	});
}


function initRegisterPageLinks() {
	$("#adminRegisterForm").validate({
	    // Specify validation rules
	    rules: {
	      // The key name on the left side is the name attribute
	      // of an input field. Validation rules are defined
	      // on the right side
	      password: "required",
	      confirm_password: {
	    	required:true,
	    	equals:'#password',
	      },
	      terms:"required",
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
	    	confirm_password: {
	    		required: "Please enter confirm password",
	    		equals:"Confirm password doesn't match"
	    	},
	    	terms: "Please agree to the terms"
	    },
	    errorPlacement: function (error, element) {
            if (element.parent().hasClass('input-group') || element.parent().hasClass('icheck-primary')) {
                error.insertBefore(element.parent());
            } else {
                error.insertBefore(element);
            }
        }
	}); 
	$('#deviceAdminRegister').on("click", function(){
		var valid = $("#adminRegisterForm").valid();
		if (!valid) {
			return;
		}
		var value=$('#adminRegisterForm').serializeObject();
		var registerData = {
			email:value["email"],
			password:value["password"],
		};
		console.log(registerData);
		var client = new BNDClient();
	    client.registerAdmin(registerData).then(function(response){
	    	if (response["status"]=="success") {
	    		showToast('You account has been created. Please sign-in and activate this device to continue.','Save successful',"success");
	    		loadAdminLogin();
	    		return true;
	    	}
		    else {
		    	bootbox.alert("Account could not be created. Please try again later.");
  		        return false; 
		   	}
	    });
	});
	$('#deviceSignIn').off("click");
	$('#deviceSignIn').on("click", function(){
		loadAdminLogin();
	});
}


function initPasswordPageLinks() {
	$("#adminForgotPassword").validate({
	    // Specify validation rules
	    rules: {
	      // The key name on the left side is the name attribute
	      // of an input field. Validation rules are defined
	      // on the right side
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
	    },
	    errorPlacement: function (error, element) {
            if (element.parent().hasClass('input-group')) {
                error.insertBefore(element.parent());
            } else {
                error.insertBefore(element);
            }
        }
	}); 
	$('#adminForgotPasswordRequest').on("click", function(){
		var valid = $("#adminLoginForm").valid();
		if (!valid) {
			return;
		}
		var value=$('#adminLoginForm').serializeObject();
		var client = new BNDClient();
		var registerData = {
			admin_email:value["email"],
			admin_password:value["password"],
			activation_code:value["activation_code"],
			device_id:deviceId
		};
	    client.registerDevice(JSON.stringify(registerData)).then(function(response){
	    	if (response["error"]==false) {
		      saveLicenseInfo(response["content"]).then(function(cusrResult){
    	    		$(document).Toasts('create', {
    	    	        title: 'Save successful',
    	    	        body: 'Device registered sucessfully'
    	    	    });
		    	});
	    	}
		    else {
		    	bootbox.alert("Device could not be registered. Please try again later.");
  		        return false; 
		   	}
	    });
	});
	
	$('#deviceSignIn').on("click", function(){
		loadAdminLogin();
	});
}