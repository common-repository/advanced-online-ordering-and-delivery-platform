function openAddOn(item, selectedMod) {
	$('#bnd-extras-container').html("");
	$('#extras').modal('show');
	$.get(bnd_ajax_url+"?item="+item+"&mod="+selectedMod+"&action=buynowdepot_get_modifiers", function (data) {
        if(data!=undefined)
        {
        	console.log(data);
        	$('#bnd-extras-container').html(data.data.page);
        	numberSpinnerExtra();
        	updatePrice();
        	$(".modifier-option").click(function(){
        		updatePrice();
        	});
        }
    });
}

function editAddOn(key) {
	$('#extras').modal('show');
	$.get(bnd_ajax_url+"?key="+key+"&action=buynowdepot_edit_modifiers", function (data) {
        if(data!=undefined)
        {
        	console.log(data);
        	$('#bnd-extras-container').html(data.data.page);
        	numberSpinnerExtra();
        	updatePrice();
        	$(".modifier-option").click(function(){
        		updatePrice();
        	});
        }
    });
}

function updatePrice() {
	var totalPrice = parseFloat($('#hdn-extra-item-price').val());
	$(".modifier-option").each(function(index){
		var id = $(this).attr("id").split("-")[1];
		if ($(this).prop("checked")) {
			var price = $('#hdn-extra-'+id).val();
			totalPrice+=parseFloat(price);
		}
	});
	var quantity = parseInt($('#extra-quantity').val());
	totalPrice = quantity*totalPrice;
	$('#extra-price').html("$"+totalPrice.toFixed(2));
}

function updateCart() {
	var modifiers = [];
	$(".modifier-option").each(function(index){
		var id = $(this).attr("id").split("-")[1];
		if ($(this).prop("checked")) {
			modifiers.push(id);
		}
	});
	var key = $('#key').val();
	var item = $('#extra-item').val();
	var quantity = parseInt($('#extra-quantity').val());
	var instructions = $('#extra-instructions').val();
	$('#bnd-extras-container').html("");
    $('#extras').modal('hide');
	showLoading();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "key="+key+"&item="+item+"&quantity="+quantity+"&modifiers="+modifiers.join(",")+"&instructions="+instructions+"&action=bnd_add_to_cart",
		  success: function(response){
			console.log(response);
		    $('.message-area').html(response.message);
		    renderCart(response);
		    updatePageData();
		    hideLoading();
		  },
		  failure: function(response) {
			  
		  }
		  
	});
}

function updateQuantity(key, quantity) {
	console.log(key);
	console.log(quantity);
	showLoading();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "key="+key+"&quantity="+quantity+"&action=bnd_update_quantity",
		  success: function(response){
		    $('.message-area').html(response.message);
		    renderCart(response);
		    updatePageData();
		    hideLoading();
		  },
		  failure: function(response) {			  
		  }
		  
	}).done(function(){
	});
}

function renderCart(data) {
    //console.log(data);
    $('#bnd-cart-items').html(data["data"]["page"]);
    numberSpinnerCart();
}

function renderEmptyCart(data) {
	$('#bnd-cart-items').html(data["data"]["page"]);
}

function renderCartItems(data) {
	$('#bnd-cart-items-list').html(data["data"]["cart_list"]);
}

function showCart() {
	showLoading();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "action=bnd_display_cart",
		  success: function(response){
			  console.log(response);
			  if (response["success"]==true) {
				  renderCart(response);
				  updatePageData();
			  }
			  else {
				  renderEmptyCart(response);
				  updatePageData();
			  }
			  hideLoading();
		  },
		  failure: function(response) {
			  
		  }  
	});
}

function showAddress() {
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "action=bnd_display_address",
		  success: function(response){
			  console.log(response);
			  if (response["success"]==true) {
				  renderAddress(response);
			  }
			  hideLoading();
		  },
		  failure: function(response) {
			  
		  }  
	});
}

function showCartItems(callback) {
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "action=bnd_display_cart_items",
		  success: function(response){
			  console.log(response);
			  if (response["success"]==true) {
				  renderCartItems(response);
				  callback();
			  }
			  hideLoading();
		  },
		  failure: function(response) {
			  
		  }  
	});
}

function showLoading() {
	$('.cart-spinner-container').show();
}

function hideLoading() {
	$('.cart-spinner-container').hide();
}

function showLoadingCart() {
	$('.cart-spinner-container').show();
}

function hideLoadingCart() {
	$('.cart-spinner-container').hide();
}

function numberSpinnerCart() {
  $('.cart-quantity .number-spinner>.ns-btn>a').click(function() {
    var btn = $(this),
      oldValue = btn.closest('.number-spinner').find('input').val().trim(),
      newVal = 0;

    if (btn.attr('data-dir') === 'up') {
      newVal = parseInt(oldValue) + 1;
    } else {
      if (oldValue > 1) {
        newVal = parseInt(oldValue) - 1;
      } else {
        newVal = 1;
      }
    }
    btn.closest('.number-spinner').find('input').val(newVal);
    var key = $(this).attr("id").split("-")[1];
    if (oldValue!==newVal) {
    	updateQuantity(key,newVal);
    }
  });
}

function numberSpinnerExtra() {
	  $('.extra-price-item .number-spinner>.ns-btn>a').click(function() {
	    var btn = $(this),
	      oldValue = btn.closest('.number-spinner').find('input').val().trim(),
	      newVal = 0;
	
	    if (btn.attr('data-dir') === 'up') {
	      newVal = parseInt(oldValue) + 1;
	    } else {
	      if (oldValue > 1) {
	        newVal = parseInt(oldValue) - 1;
	      } else {
	        newVal = 1;
	      }
	    }
	    btn.closest('.number-spinner').find('input').val(newVal);
	    updatePrice();
	  });
}

function allPageSlider(catCount) {
	$('.page-category-slider').slick({
        slidesToShow: catCount,
        arrows: true,
        responsive: [{
                breakpoint: 768,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: catCount-1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: catCount-2
                }
            }
        ]
    });
}
function removeCart(key, itemName) {	
	bootbox.confirm({
	    message: "Are you sure you want to remove <b>"+itemName+"</b> from cart?",
	    buttons: {
	        confirm: {
	            label: 'Yes',
	            className: 'btn-success'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },
	    callback: function (result) {
	        if (result==true) {
	        	showLoading();
	        	$.ajax({
	        		  type: "POST",
	        		  url: bnd_ajax_url,
	        		  data: "key="+key+"&action=bnd_delete_from_cart",
	        		  success: function(response){
	        			console.log(response);
	        		    $('.message-area').html(response.message);
	        		    renderCart(response);
	        		    updatePageData();
	        		    hideLoading();
	        		  },
	        		  failure: function(response) {	        			  
	        		  }
	        		  
	        	});
	        }
	    }
	});
}


function loadUrl(url) {
	window.location.href=url;
}

function displayCategorySlider(catCount) {
	 $('.menu-category-slider').slick({
	        slidesToShow: catCount,
	        arrows: true,
	        responsive: [{
	                breakpoint: 768,
	                settings: {
	                    arrows: false,
	                    centerMode: true,
	                    centerPadding: '40px',
	                    slidesToShow: catCount-1
	                }
	            },
	            {
	                breakpoint: 480,
	                settings: {
	                    arrows: false,
	                    centerMode: true,
	                    centerPadding: '40px',
	                    slidesToShow: catCount-2
	                }
	            }
	        ]
	    });
	 
	 $('.bnd-slider-item').click(function(){
		 var id = $(this).data('clid');
		 $('html, body').animate({
		        scrollTop: parseInt($("#category-"+id).offset().top)
		    }, 1000);
	 });
}

function renderAddress(data) {
	//console.log(data);
    $('#bnd_address_detail').html(data["data"]["page_address"]);
}

function showAddressModal() {
	clearAddress();
	$('#addressModal').modal('show');
}

function saveAddress(formName) {
	var valid = $("#addressForm").valid();
	if (!valid) {
		return;
	}
	var postData = $("#addressForm").serialize();
	showLoading();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: postData+"&action=bnd_add_address",
		  success: function(response){
			console.log(response);
			renderCart(response);
			updatePageData();
			hideLoading();
			$('#addressModal').modal('hide');
		  },
		  failure: function(response) {	        			  
		  }
		  
	});
}

function selectAddress(id) {
	showLoading();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "id="+id+"&action=bnd_select_address",
		  success: function(response){
			console.log(response);
			renderCart(response);
			updatePageData();
			hideLoading();
		  },
		  failure: function(response) {	        			  
		  }
	});
}

function editAddress(id) {
	clearAddress();
	$('#addressModal').modal('show');
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "id="+id+"&action=bnd_edit_address",
		  success: function(response){
			$('#id').val(response["id"]);
			$('#first_name').val(response["first_name"]);
			$('#last_name').val(response["last_name"]);
			$('#address1').val(response["address1"]);
			$('#address2').val(response["address2"]);
			$('#address3').val(response["address3"]);
			$('#city').val(response["city"]);
			$('#state').val(response["state"]);
			$('#zip').val(response["zip"]);
			$('#country').val(response["country"]);
			$('#phone_number').val(response["phone_number"]);
			$('#email').val(response["email"]);
			$('#address_type').val(response["address_type"]);
		  },
		  failure: function(response) {	        			  
		  }
	});
}

function clearAddress() {
	$('#addressForm .valid').removeClass("valid");
	$('#id').val("");
	$('#first_name').val("");
	$('#last_name').val("");
	$('#address1').val("");
	$('#address2').val("");
	$('#address3').val("");
	$('#city').val("");
	$('#state').val("");
	$('#zip').val("");
	$('#country').val("");
	$('#phone_number').val("");
	$('#email').val("");
	$('#address_type').val("");
}

function removeAddress(id) {	
	bootbox.confirm({
	    message: "Are you sure you want to remove the address?",
	    buttons: {
	        confirm: {
	            label: 'Yes',
	            className: 'btn-success'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },
	    callback: function (result) {
	        if (result==true) {
	        	showLoading();
	        	$.ajax({
	      		  type: "POST",
	      		  url: bnd_ajax_url,
	      		  data: "id="+id+"&action=bnd_remove_address",
	      		  success: function(response){
	      			renderCart(response);
	      			updatePageData();
	      			hideLoading();
	      		  },
	      		  failure: function(response) {	        			  
	      		  }
	        	});
	        }
	    }
	});
}
function confirmPickup(id) {
	showLoading();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "action=bnd_confirm_pickup",
		  success: function(response){
			console.log(response);
			renderCart(response);
			updatePageData();
			hideLoading();
		  },
		  failure: function(response) {	        			  
		  }
	});
}

function confirmDelivery(id) {
	showLoading();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "action=bnd_confirm_delivery",
		  success: function(response){
			console.log(response);
			renderCart(response);
			updatePageData();
			hideLoading();
		  },
		  failure: function(response) {	        			  
		  }
	});
}

function completeOrder(orderType) {
	if (orderType=="Payment") {
		showLoading();
		var postData = $("#paymentForm").serialize();
		$.ajax({
			  type: "POST",
			  url: bnd_ajax_url,
			  data: postData+"&action=bnd_complete_order_payment",
			  success: function(response){
				console.log(response);
				hideLoading();
				if (response["status"]=="success") {
					loadUrl(bnd_base_url+"/bnd-successful");
				}
				else {
					bootbox.alert("Error while creating the order :"+response["message"]);
				}
			  },
			  failure: function(response) {
			  }
		});
	}
	else {
		showLoading();
		var postData = $("#paymentForm").serialize();
		$.ajax({
			  type: "POST",
			  url: bnd_ajax_url,
			  data: postData+"&action=bnd_complete_order",
			  success: function(response){
				console.log(response);
				if (response["status"]=="success") {
					loadUrl(bnd_base_url+"/bnd-successful");
				}
				else {
					bootbox.alert("Error while creating the order :"+response["message"]);
				}
				hideLoading();
			  },
			  failure: function(response) {	  
				  console.log(response);
			  }
		});
	}
}

function applyDiscount() {
	showLoading();
	var postData = "coupon="+$('#discount_coupon_code').val();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: postData+"&action=bnd_apply_discount",
		  success: function(response){
			console.log(response);
			if (response["success"]==true) {
				renderCart(response);
				updatePageData();
			}
			else {
				bootbox.alert("Discount can't be applied :"+response["message"]);
			}
			hideLoading();
		  },
		  failure: function(response) {	        			  
		  }
	});
}

function applyTip() {
	showLoading();
	var postData = "tip_value="+$('#tip_value').val()+"&tip_type="+$('#tip_type').val();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: postData+"&action=bnd_apply_tip",
		  success: function(response){
			console.log(response);
			if (response["success"]==true) {
				renderCart(response);
				updatePageData();
			}
			else {
				bootbox.alert("Tip can't be applied :"+response["message"]);
			}
			hideLoading();
		  },
		  failure: function(response) {	        			  
		  }
	});
}

function saveProfile(formName) {
	var valid = $("#profileForm").valid();
	if (!valid) {
		return;
	}
	var postData = $("#profileForm").serialize();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: postData+"&action=bnd_save_profile",
		  success: function(response){
			  console.log(response);
			  if (response["status"]=="success") {
				  updateProfile(response["data"]);
				  displaySuccess(response["message"]);
			  }
			  else {
				  displayError(response["message"]);
			  }
		  },
		  failure: function(response) {	        			  
		  } 
	});
}

function savePassword(formName) {
	var valid = $("#passwordForm").valid();
	if (!valid) {
		return;
	}
	var postData = $("#passwordForm").serialize();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: postData+"&action=bnd_save_password",
		  success: function(response){
			  console.log(response);
			  if (response["status"]=="success") {
				  displaySuccess(response["message"]);
			  }
			  else {
				  displayError(response["message"]);
			  }
		  },
		  failure: function(response) {	        			  
		  } 
	});
}

function updateProfile(data) {
	console.log(data);
	$('#firstName').val(data["first_name"]);
	$('#lastName').val(data["last_name"]);
	$('#mobileNumber').val(data["mobile_number"]);
}

function displaySuccess(message) {
	$('#global-alert').removeClass("alert-danger");
	$('#global-alert').addClass("alert-success");
	$('#global-alert-text').html(message);
	$('#global-alert').show();
}
function displayError(message) {
	$('#global-alert').removeClass("alert-success");
	$('#global-alert').addClass("alert-danger");
	$('#global-alert-text').html(message);
	$('#global-alert').show();
}


function showProfileAddress() {
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "action=bnd_display_profile_address",
		  success: function(response){
			  renderAddress(response);
		  },
		  failure: function(response) {
			  
		  }  
	});
}

function saveProfileAddress(formName) {
	var valid = $("#addressForm").valid();
	if (!valid) {
		return;
	}
	var postData = $("#addressForm").serialize();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: postData+"&action=bnd_add_profile_address",
		  success: function(response){
			renderAddress(response)
			$('#addressModal').modal('hide');
		  },
		  failure: function(response) {	        			  
		  }
		  
	});
}

function setDefaultProfileAddress(id) {
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "id="+id+"&action=bnd_default_profile_address",
		  success: function(response){
			renderAddress(response);
		  },
		  failure: function(response) {	        			  
		  }
	});
}

function editProfileAddress(id) {
	clearAddress();
	$('#addressModal').modal('show');
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "id="+id+"&action=bnd_edit_profile_address",
		  success: function(response){
			$('#id').val(response["id"]);
			$('#first_name').val(response["first_name"]);
			$('#last_name').val(response["last_name"]);
			$('#address1').val(response["address1"]);
			$('#address2').val(response["address2"]);
			$('#address3').val(response["address3"]);
			$('#city').val(response["city"]);
			$('#state').val(response["state"]);
			$('#zip').val(response["zip"]);
			$('#country').val(response["country"]);
			$('#phone_number').val(response["phone_number"]);
			$('#email').val(response["email"]);
			$('#address_type').val(response["address_type"]);
		  },
		  failure: function(response) {	        			  
		  }
	});
}

function removeProfileAddress(id) {	
	bootbox.confirm({
	    message: "Are you sure you want to remove the address?",
	    buttons: {
	        confirm: {
	            label: 'Yes',
	            className: 'btn-success'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },
	    callback: function (result) {
	        if (result==true) {
	        	$.ajax({
	      		  type: "POST",
	      		  url: bnd_ajax_url,
	      		  data: "id="+id+"&action=bnd_remove_profile_address",
	      		  success: function(response){
	      			renderAddress(response);
	      		  },
	      		  failure: function(response) {	        			  
	      		  }
	        	});
	        }
	    }
	});
}