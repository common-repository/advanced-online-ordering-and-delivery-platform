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
		<h4 class="font-weight-bold m-0 text-white">My Order</h4>
	</div>
</div>
<section class="py-4 bnd-main-body">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<ul
					class="nav nav-tabsa custom-tabsa border-0 flex-column bg-white rounded overflow-hidden shadow-sm p-2 c-t-order"
					id="myTab" role="tablist">
					<li class="nav-item" role="presentation"><a
						class="nav-link border-0 text-dark py-3 active"
						href="<?php echo buynowdepot_get_page_url('bnd-my-order'); ?>"> <i
							class="feather-check mr-2 text-success mb-0"></i> Completed
					</a></li>
					<li class="nav-item border-top" role="presentation"><a
						class="nav-link border-0 text-dark py-3" href="<?php echo buynowdepot_get_page_url('bnd-my-order'); ?>&tab=Completed"> <i
							class="feather-clock mr-2 text-warning mb-0"></i> On Progress
					</a></li>
					<li class="nav-item border-top" role="presentation"><a
						class="nav-link border-0 text-dark py-3" href="<?php echo buynowdepot_get_page_url('bnd-my-order'); ?>&tab=Cancelled"> <i
							class="feather-x-circle mr-2 text-danger mb-0"></i> Canceled
					</a></li>
				</ul>
			</div>
			<div class="col-md-9">
				<!-- body -->
				<section
					class="bg-white bnd-main-body rounded shadow-sm overflow-hidden">
					<div class="container p-0">
						<div class="row">
							<div class="col-lg-12">
								<div class="bnd-status">
									<!-- status complete -->
									<div class="p-3 status-order border-bottom bg-white">
										<p class="small m-0">
											<i class="feather-calendar text-primary"></i> 16 June,
											11:30AM
										</p>
									</div>
									<div class="p-3 border-bottom">
										<h6 class="font-weight-bold">Order Status</h6>
										<div class="tracking-wrap">
											<div class="step active">
												<span class="icon text-success"><i
													class="feather-check-circle"></i></span> <span
													class="text small">Ordered</span>
											</div>
											<!-- step.// -->
											<div class="step active">
												<span class="icon text-danger"><i class="feather-x-circle"></i></span>
												<span class="text small">Canceled</span>
											</div>
											<!-- step.// -->
										</div>
									</div>
									<!-- Destination -->
									<div class="p-3 border-bottom bg-white">
										<h6 class="font-weight-bold">Destination</h6>
										<p class="m-0 small">554 West 142nd Street, New York, NY 10031</p>
									</div>
									<div class="p-3 border-bottom">
										<p class="font-weight-bold small mb-1">Courier</p>
										<img alt="#" src="<?php echo $theme_url?>/img/logo_web.png"
											class="img-fluid sc-bnd-logo mr-2"> <span
											class="small text-primary font-weight-bold">Grocery Courier </span>
									</div>
									<!-- total price -->
									<!-- Destination -->
									<div class="p-3 border-bottom bg-white">
										<div class="d-flex align-items-center mb-2">
											<h6 class="font-weight-bold mb-1">Total Cost</h6>
											<h6 class="font-weight-bold ml-auto mb-1">$8.52</h6>
										</div>
										<p class="m-0 small text-muted">
											You can check your order detail here,<br>Thank you for order.
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</section>