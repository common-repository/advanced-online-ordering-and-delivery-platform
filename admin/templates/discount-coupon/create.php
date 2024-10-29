<?php 
/**
 * Discount coupon CRUD
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/discount-coupon
 */
?>
<div class="row">
	<div class="col-12">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">New Discount Coupon</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'></div>
			<div class='data-button-area'>
				<div id='read-discount-coupon'
					class='btn btn-primary pull-right m-b-15px mr-1 read-discount-coupon-button'>
					<span class='fas fa-list'></span> Discount Coupon List
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="card mb-12">
			<div class="card-header"><h4 class="mb-0">Create Coupon</h4></div>
			<div class="card-body">
        		<form id='create-discount-coupon-form' action='#' method='post'>
        			<table class='table table-hover table-bordered' style="width:100%">       
        				<tr>
        					<td>Name</td>
        					<td><input type='text' name='name' class='form-control' id="name" /></td>
        				</tr>
        				<tr>
        					<td>Coupon Code</td>
        					<td><input type='text' name='code' class='form-control' id="code" /></td>
        				</tr>
        				<tr>
        					<td>Discount Type</td>
        					<td>
        						<select name="discount_type">
        							<option value="Amount">Amount</option>
        							<option value="Percentage">Percentage</option>
        						</select>       				
        					</td>
        				</tr> 
        				<tr>
        					<td>Coupon Value</td>
        					<td>
        						<input type='text' id="value" name='value' class='form-control' /> 				
        					</td>
        				</tr>      
        				<tr>
        					<td>Minimum Order Amount</td>
        					<td>
        						<input type='text' id="min_order_amount" name='min_order_amount' class='form-control' /> 				
        					</td>
        				</tr>      
        				<tr>
        					<td>Start Date</td>
        					<td>
        						<input type='text' id="start_date" name='start_date' class='form-control' placeholder="Start date"/> 				
        					</td>
        				</tr>      
        				<tr>
        					<td>End Date</td>
        					<td>
        						<input type='text' id="end_date" name='end_date' class='form-control' placeholder="End date"/> 				
        					</td>
        				</tr>      
        				<tr>
        					<td>No. of uses</td>
        					<td><input type='text' id="num_usage" name='num_usage' class='form-control'/></td>
        				</tr>
        				<tr>
        					<td></td>
        					<td>
        						<button type='button' class='btn btn-primary' onclick="saveModel('#discount-content', 'discount-coupon')">
        							<span class='fas fa-plus'></span> Save
        						</button>
        					</td>
        				</tr>
        	
        			</table>
        		</form>
        	</div>
        </div>
	</div>
</div>
<script type="text/javascript">

$(document).ready(function(){
	$('#start_date').datepicker({
	    format: 'yyyy-mm-dd',
	});
	$('#end_date').datepicker({
	    format: 'yyyy-mm-dd',
	});
	$("#create-discount-coupon-form").validate({
	    // Specify validation rules
	    rules: {
	      // The key name on the left side is the name attribute
	      // of an input field. Validation rules are defined
	      // on the right side
	      name: "required",
	      code: "required",
	      value:"required",
	      discount_type:"required",
	      min_order_amount:"required",
	      start_date:"required",
	      end_date:"required",
	      num_usage:"required",
	      coupon_value:"required",
	    },
	    // Specify validation error messages
	    messages: {
	      name: "Please enter name for the coupon",
	      code: "Please enter coupon code",
	      discount_type: "Please enter discount type",
		  min_order_amount:"Please enter minimum order amount",
		  start_date:"Please enter start date",
	      end_date:"Please enter end date",
	      num_usage:"Please enter no. of uses of the coupon",
	      coupon_value:"Please enter coupon value",
	    }
	  }); 
});
</script>