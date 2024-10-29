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
?>
<div class="d-none">
	<div class="bg-primary p-3 d-flex align-items-center">
		<a class="toggle togglew toggle-2" href="#"><span></span></a>
		<h4 class="font-weight-bold m-0 text-white">Search</h4>
	</div>
</div>
<div class="bnd-popular">
	<!-- Most popular -->
	<div class="container">
		<div class="search py-5">
			<div class="input-group mb-4">
				<input type="text"
					class="form-control form-control-lg input_search border-right-0"
					id="inlineFormInputGroup" value="Search">
				<div class="input-group-prepend">
					<div
						class="btn input-group-text bg-white border_search border-left-0 text-primary">
						<i class="fas fa-search"></i>
					</div>
				</div>
			</div>
			<!-- Content Row -->
			<div class="row d-flex align-items-center justify-content-center py-5">
				<div class="col-md-4 py-5">
					<div class="text-center py-5">
						<p class="h4 mb-4">
							<i class="fa fa-search bg-primary text-white rounded p-2"></i>
						</p>
						<p class="font-weight-bold text-dark h5">Item not found</p>
						<p>Nothing mathes your searh criteria, please try again.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>