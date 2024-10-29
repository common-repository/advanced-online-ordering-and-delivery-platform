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
defined('ABSPATH') || exit();
$theme_url = buynowdepot_get_theme_url();
$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$merchant = (array) $db->getMerchantAddress();
$repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
$cart_details = $repository->getCartDetails();
$countries = $db->getCountries();
$BndSettings = (array) get_option("bnd_settings");
$default_country_code = $BndSettings["default_country_code"];
$payment_key = $BndSettings["payment_key"];
?>

<?php include_once 'category-header.php'; ?>
<div class="container position-relative">
	<div class="row my-3">
		<div class="col-md-8 mb-3">
			<div id="bnd_address_detail">
			</div>
			<div
				class="accordion mb-3 rounded shadow-sm bg-white overflow-hidden"
				id="accordionExample">
				<div class="bnd-card bg-white border-bottom overflow-hidden">
					<div class="bnd-card-header" id="headingOne">
						<div
							class="d-flex p-3 align-items-center btn btn-link w-100 mb-0"
							data-toggle="collapse"
							data-target="#collapseOne" aria-expanded="true"
							aria-controls="collapseOne">
							<i class="fas fa-credit-card mr-3"></i> <strong>Credit/Debit Card</strong> <i
								class="fas fa-chevron-down ml-auto"></i>
						</div>
					</div>
					<div id="collapseOne" class="collapse show"
						aria-labelledby="headingOne" data-parent="#accordionExample">
						<div class="bnd-card-body border-top p-3">
							<div class="m-0 category-heading">Provide payment details</div>
							<form name="paymentForm" action="" method="post"
								id="paymentForm">
								<?php echo wp_nonce_field( 'paymentForm', '_wpnonce', true, false );?>
								<input name="myToken" type="hidden" value="" id="myToken"/>
								<div class="row">
                          			<div class="col-sm-6">
                          				<p class="small">
                							WE ACCEPT <span class="bnd-card ml-2 font-weight-bold">(
                								Master Card / Visa Card / Amex /Diner's Club)</span>
                						</p>
                          			</div>
                          			<div class="col-sm-6">
                          				<div class="payment-image">
                							<img src="<?php echo $theme_url."/img/payment-methods.png"?>" style="height: 40px" />
                						</div>
                          			</div>
                          		</div>
                      			<div class="row">
                  					<div class="col-sm-6">
        			          			<div class="form-group">
        				                    <label for="firstName">First name</label>
        				                    <input type="text" class="form-control" id="paymentFirstName" placeholder="First name" name="paymentFirstName">
        					            </div>
        					        </div>
        					        <div class="col-sm-6">
        			          			<div class="form-group">
        				                    <label for="lastName">Last name</label>
        				                    <input type="text" class="form-control" id="paymentLastName" placeholder="Last name"  name="paymentLastName">
        					            </div>
        					        </div>
        					        <div class="col-md-12">
        		          				<div class="form-group">
        				                    <label for="paymentCardNumber">Card Number</label>
        				                    <iframe id="tokenFrame" name="tokenFrame" src="https://fts-uat.cardconnect.com/itoke/ajax-tokenizer.html?css=.error%7Bcolor%3A%20red%3B%7Dinput%7Bdisplay%3A%20block%3Bwidth%3A%20100%25%3Bheight%3A%2024px%3Bpadding%3A%200.375rem%200.75rem%3Bfont-size%3A%201rem%3Bfont-weight%3A%20400%3Bline-height%3A%201.5%3Bcolor%3A%20%23495057%3Bbackground-color%3A%20%23fff%3Bbackground-clip%3A%20padding-box%3Bborder%3A%201px%20solid%20%23ced4da%3Bborder-radius%3A0.25rem%3Bbox-shadow%3A%20inset%200%200%200%20rgb(0%200%200%20%2F%200%25)%3Btransition%3A%20border-color%200.15s%20ease-in-out%2C%20box-shadow%200.15s%20ease-in-out%3B%7Dbody%7Bwidth%3Acalc(100%25%20-%2027px)%3Bmargin%3A0%3Bpadding%3A0%7D" width="100%" scrolling="no"  style="width:100%;height:38px;margin:0;padding:0;border-style:none;oveflow:hidden"></iframe>
        				               </div>
        				            </div>
        				            <div class="col-sm-6">
        							    <div class="form-group">
        				                    <label for="paymentExpiryDate">Expiry Month/Year</label>
        				                    <div class="oneline">
        				                    	<input type="text" class="form-control" id="paymentExpiryDateMM" name="paymentExpiryDateMM" placeholder="MM" value=""  style="display:inline; width:45%">/<input type="text" class="form-control" id="paymentExpiryDateYY" name="paymentExpiryDateYY" placeholder="YY" value=""  style="display:inline; width:45%">
        				                    </div>
        				               </div>
        				           </div>
        				           <div class="col-sm-6">
        				               <div class="form-group">
        				                    <label for="paymentCVV">CVV</label>
        				                    <input type="text" class="form-control" id="paymentCVV" name="paymentCVV" placeholder="Enter CVV" value="">
        				               </div>
        							</div>
                      			</div>
                          		<div class="row">
                  					<div class="col-sm-12">
        			          			<div class="form-group">
        				                    <label for="firstName">Billing Address</label>
        				                    <input type="text" class="form-control" id="paymentAddress1"  name="paymentAddress1" placeholder="Address Line 1" value="">
        					            </div>
        					            <div class="form-group">
        				                    <input type="text" class="form-control" id="paymentAddress2"  name="paymentAddress2" placeholder="Address Line 2" value="">
        					            </div>
        					            <div class="form-group">
        				                    <input type="text" class="form-control" id="paymentAddress3"  name="paymentAddress3" placeholder="Address Line 3" value="">
        					            </div>
        					        </div>
        					        <div class="col-sm-6">
        			          			<div class="form-group">
        				                    <input type="text" class="form-control" id="paymentCity"  name="paymentCity" placeholder="City" value="">
        					            </div>
        					        </div>
        					        <div class="col-sm-6">
        			          			<div class="form-group">
        				                    <input type="text" class="form-control" id="paymentState"  name="paymentState" placeholder="State" value="">
        					            </div>
        					        </div>
        					        <div class="col-sm-6">
        			          			<div class="form-group">
        				                    <input type="text" class="form-control" id="paymentZip" name="paymentZip" placeholder="Zip" value="">
        					            </div>
        					        </div>
        					        <div class="col-sm-6">
        			          			<div class="form-group">
        				                    <input type="text" class="form-control" id="paymentCountry"  name="paymentCountry" placeholder="Country" value="US"/>
        					            </div>
        					        </div>
                				</div>
								<div class="payment-button-container">
									<div class="payment-button">
										<div class="btn-group" id="processPayment">
											<div class="btn btn-success btn-lg">Pay Now</div>
											<div class="btn input-group-text btn-lg">
												<i class="far fa-credit-card"></i>&nbsp;
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="bnd-card bg-white overflow-hidden">
					<div class="bnd-card-header" id="headingThree">
						<div
							class="d-flex p-3 align-items-center btn btn-link w-100"
							data-toggle="collapse"
							data-target="#collapseThree" aria-expanded="false"
							aria-controls="collapseThree">
							<i class="fas fa-dollar-sign mr-3"></i><strong>Cash on Delivery</strong> <i
								class="fas fa-chevron-down ml-auto"></i>
						</div>
					</div>
					<div id="collapseThree" class="collapse"
						aria-labelledby="headingThree" data-parent="#accordionExample">
						<div class="card-body border-top">
							<h6 class="mb-3 mt-0 mb-3 font-weight-bold">Cash</h6>
							<p class="m-0">Please keep exact change handy to help us serve
								you better</p>
						</div>
						<div class="payment-button-container">
							<div class="p-3 payment-button">
								<div class="btn-group">
									<button class="btn btn-success btn-lg"
										onclick="completeOrder('Cash')">Complete Order</button>
									<button class="btn input-group-text btn-lg">
										<i class="fas fa-shopping-cart"
											onclick="completeOrder('Cash')"></i>&nbsp;
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    	<div class="col-md-4">
			<div class="cart-spinner-container" style="display: none">
				<div class="spinner-border text-danger" role="status"
					id="cart-spinner">
					<span class="sr-only">Loading...</span>
				</div>
			</div>
			<div
				class="bnd-cart-item rounded shadow-sm overflow-hidden sticky_sidebar">
				<div class="cart-header  border-bottom">
					<div class="cart-icon">
						<i class="fas fa-utensils"></i>
					</div>
					<div class="cart-header-text font-weight-bold h6 p-3 mb-0 w-100">
						<h6>Your Order</h6>
					</div>
				</div>
				<div id="bnd-cart-items"></div>
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
							onclick="saveAddress()">Save changes</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
function updatePageData() {
	showAddress();
}

function cloverTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted to the server
      //$('#cloverToken').val(token);
      completeOrder('Payment');
}

$(document).ready(function() {
	  showCart();
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

	  $("#paymentForm").validate({
	    // Specify validation rules
	    rules: {
	      	// The key name on the left side is the name attribute
	      	// of an input field. Validation rules are defined
	      	// on the right side
	      	paymentCardNumber: "required",
	      	paymentFirstName:"required",
	      	paymentLastName:"required",
	      	paymentExpiryDateYY:"required",
	   	  	paymentExpiryDateMM:"required",
		  	paymentCVV:"required",
		  	paymentAddress1:"required",
		  	paymentCity:"required",
			paymentState:"required",
			paymentZip:"required",
			paymentCountry:"required"
	    },
	    // Specify validation error messages
	    messages: {
	    	paymentCardNumber: "Please enter your card number",
		  	paymentFirstName: "Please enter your first name",
		  	paymentLastName: "Please enter your last name",
		  	paymentExpiryDateYY:"Please enter expiry year",
		  	paymentExpiryDateMM:"Please enter expiry month",
		  	paymentCVV:"Please enter cvv from your card",
		  	paymentAddress1:"Please enter address",
		  	paymentCity:"Please enter city",
			paymentState:"Please enter state",
			paymentZip:"Please enter zip",
			paymentCountry:"Please enter country"
	    }
	  }); 

	  /*
	  $('#cardNumber').validateCreditCard(function(result) {
		    console.log(result);
		    if (result.valid) {
		        $("#cardType").removeClass('invalid');
		        $("#cardType").addClass('valid');
		    } else {
		        $("#cardType").removeClass('valid');
		        $("#cardType").addClass('invalid');
		    }
		});*/
		/*
		const styles = {
    	  'card-number input': {
    		'height': '36px',
    	    'padding': '5px',
    	    'font-size': '1rem',
    	    'font-weight': '400',
    	    'line-height': '26px',
    	    'color': '#495057',
    	    'background-color': '#fff',
    	  },
    	  'iframe':{
        	  'width':'100%',
        	  'height':'38px !important'
    	  },
    	  '.brand': {
        	  'top':'2px',
        	  'right':'2px'
    	  },
    		'card-date input': {
    			'height': '36px',
        	    'padding': '5px',
        	    'font-size': '1rem',
        	    'font-weight': '400',
        	    'line-height': '26px',
        	    'color': '#495057',
        	    'background-color': '#fff',
    	  },

		'card-cvv input': {
			'height': '36px',
    	    'padding': '5px',
    	    'font-size': '1rem',
    	    'font-weight': '400',
    	    'line-height': '26px',
    	    'color': '#495057',
    	    'background-color': '#fff',
	  	},
        'card-postal-code input': {
        	'height': '36px',
    	    'padding': '5px',
    	    'font-size': '1rem',
    	    'font-weight': '400',
    	    'line-height': '26px',
    	    'color': '#495057',
    	    'background-color': '#fff',
        }
    	};
    	
	  const clover = new Clover('<?php echo $payment_key; ?>');
	  const elements = clover.elements();
	  const form = document.getElementById('paymentForm');
	  const cardNumber = elements.create('CARD_NUMBER', styles);
	  const cardDate = elements.create('CARD_DATE', styles);
	  const cardCvv = elements.create('CARD_CVV', styles);
	  const cardPostalCode = elements.create('CARD_POSTAL_CODE', styles);
	    
	  cardNumber.mount('#card-number');
	  cardDate.mount('#card-date');
	  cardCvv.mount('#card-cvv');
	  cardPostalCode.mount('#card-postal-code');

	  const cardResponse = document.getElementById('card-response');
	  const displayCardNumberError = document.getElementById('card-number-errors');
	  const displayCardDateError = document.getElementById('card-date-errors');
	  const displayCardCvvError = document.getElementById('card-cvv-errors');
	  const displayCardPostalCodeError = document.getElementById('card-postal-code-errors');
	  var displayError={};
	  displayError["CARD_NUMBER"]=displayCardNumberError;
	  displayError["CARD_CVV"]=displayCardCvvError;
	  displayError["CARD_DATE"]=displayCardDateError;
	  displayError["CARD_POSTAL_CODE"]=displayCardPostalCodeError;
	    // Handle real-time validation errors from the card element
	    cardNumber.addEventListener('change', function(event) {
	      console.log(`cardNumber changed ${JSON.stringify(event)}`);
	    });

	    cardNumber.addEventListener('blur', function(event) {
	      console.log(`cardNumber blur ${JSON.stringify(event)}`);
	    });

	    cardDate.addEventListener('change', function(event) {
	      console.log(`cardDate changed ${JSON.stringify(event)}`);
	    });

	    cardDate.addEventListener('blur', function(event) {
	      console.log(`cardDate blur ${JSON.stringify(event)}`);
	    });

	    cardCvv.addEventListener('change', function(event) {
	      console.log(`cardCvv changed ${JSON.stringify(event)}`);
	    });

	    cardCvv.addEventListener('blur', function(event) {
	      console.log(`cardCvv blur ${JSON.stringify(event)}`);
	    });

	    cardPostalCode.addEventListener('change', function(event) {
	      console.log(`cardPostalCode changed ${JSON.stringify(event)}`);
	    });

	    cardPostalCode.addEventListener('blur', function(event) {
	      console.log(`cardPostalCode blur ${JSON.stringify(event)}`);
	    });
	*/
	 // Listen for form submission
	    $('#processPayment').click(function(event) {
	      event.preventDefault();
	      // Use the iframe's tokenization method with the user-entered card details
	      /*
	      clover.createToken()
	        .then(function(result) {
		    console.log(result);
	        if (result.errors) {
	          Object.entries(result.errors).forEach(function ([key, value]) {
		          console.log(key);
		          console.log(displayError[key]);
	            displayError[key].textContent = value;
	          });
	        } else {
	          cloverTokenHandler(result.token);
	        }
	      });*/
	      if ($("#paymentForm").valid()) {
	      	completeOrder('Payment');
	      }
	    });

	    window.addEventListener('message', function(event) {
	        var token = JSON.parse(event.data);
	        var mytoken = document.getElementById('myToken');
	        mytoken.value = token.message;
	        console.log(mytoken.value);
	    }, false);
});
</script>