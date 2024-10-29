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
<div class="bnd-profile">
	<div class="container position-relative">
		<div class="py-5 bnd-profile row">
			<div class="col-md-4 mb-3">
				<?php include_once 'profile-left.php';?>
			</div>
			<div class="col-md-8 mb-3">
				<div class="bnd-cart-item-profile">
					<h5>Fequently Asked Questions</h5>
					<div id="account">
						<!-- Title -->
						<div class="mb-2 mt-3">
							<h6 class="font-weight-semi-bold mb-0">Account</h6>
						</div>
						<!-- End Title -->
						<!-- Account Accordion -->
						<div id="accountAccordion">
							<!-- Card -->
							<div
								class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
								<div id="accountHeadingOne">
									<h5 class="mb-0">
										<button
											class="shadow-none btn btn-block d-flex justify-content-between card-btn p-3"
											data-toggle="collapse" data-target="#accountCollapseOne"
											aria-expanded="false" aria-controls="accountCollapseOne">
											How do I change my password? <span class="card-btn-arrow"> <span
												class="fas fa-chevron-down"></span>
											</span>
										</button>
									</h5>
								</div>
								<div id="accountCollapseOne" class="collapse show"
									aria-labelledby="accountHeadingOne"
									data-parent="#accountAccordion">
									<div class="card-body border-top p-3 text-muted">You can login to your account and click on my profile link. There you can update your password. </div>
								</div>
							</div>
							<!-- End Card -->
							<!-- Card -->
							<div
								class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
								<div id="accountHeadingTwo">
									<h5 class="mb-0">
										<button
											class="shadow-none btn btn-block d-flex justify-content-between card-btn collapsed p-3"
											data-toggle="collapse" data-target="#accountCollapseTwo"
											aria-expanded="false" aria-controls="accountCollapseTwo">
											How do I delete my account? <span class="card-btn-arrow"> <span
												class="fas fa-chevron-down"></span>
											</span>
										</button>
									</h5>
								</div>
								<div id="accountCollapseTwo" class="collapse"
									aria-labelledby="accountHeadingTwo"
									data-parent="#accountAccordion">
									<div class="card-body border-top p-3 text-muted">Please send a mail to us, if you want your account to be deleted. We shall confirm by email, once that is done. </div>
								</div>
							</div>
							<!-- End Card -->
							<!-- Card -->
							<div
								class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
								<div id="accountHeadingThree">
									<h5 class="mb-0">
										<button
											class="shadow-none btn btn-block d-flex justify-content-between card-btn collapsed p-3"
											data-toggle="collapse" data-target="#accountCollapseThree"
											aria-expanded="false" aria-controls="accountCollapseThree">
											How do I change my account settings? <span
												class="card-btn-arrow"> <span class="fas fa-chevron-down"></span>
											</span>
										</button>
									</h5>
								</div>
								<div id="accountCollapseThree" class="collapse"
									aria-labelledby="accountHeadingThree"
									data-parent="#accountAccordion">
									<div class="card-body border-top p-3 text-muted">Login to your account and change your account details using my profile link</div>
								</div>
							</div>
							<!-- End Card -->
							<!-- Card -->
							<div
								class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
								<div id="accountHeadingFour">
									<h5 class="mb-0">
										<button
											class="shadow-none btn btn-block d-flex justify-content-between card-btn collapsed p-3"
											data-toggle="collapse" data-target="#accountCollapseFour"
											aria-expanded="false" aria-controls="accountCollapseFour">
											I forgot my password. How do I reset it? <span
												class="card-btn-arrow"> <span class="fas fa-chevron-down"></span>
											</span>
										</button>
									</h5>
								</div>
								<div id="accountCollapseFour" class="collapse"
									aria-labelledby="accountHeadingFour"
									data-parent="#accountAccordion">
									<div class="card-body border-top p-3 text-muted">Please click on the reset password link  on the Sign in screeen to reset your password.</div>
								</div>
							</div>
							<!-- End Card -->
						</div>
						<!-- End Account Accordion -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>