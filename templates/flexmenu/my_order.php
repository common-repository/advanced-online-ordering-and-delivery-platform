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
$model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
$merchant = $model->getMerchantAddress();
$orders = $repository->getOrdersForUser();
?>
<div class="d-none">
	<div class="bg-primary border-bottom p-3 d-flex align-items-center">
		<a class="toggle togglew toggle-2" href="#"><span></span></a>
		<h4 class="font-weight-bold m-0 text-white">My Order</h4>
	</div>
</div>
<section class="py-4 bnd-main-body">
	<div class="container">
		<div class="row">
			<div class="col-md-3 mb-3">
				<ul
					class="nav nav-tabsa custom-tabsa border-0 flex-column bg-white rounded overflow-hidden shadow-sm p-2 c-t-order"
					id="myTab" role="tablist">
					<li class="nav-item" role="presentation"><a
						class="nav-link border-0 text-dark py-3 active" id="completed-tab"
						data-toggle="tab" href="#completed" role="tab"
						aria-controls="completed" aria-selected="true"> <i
							class="fas fa-check mr-2 text-success mb-0 fa-2x"></i> Completed
					</a></li>
					<li class="nav-item border-top" role="presentation"><a
						class="nav-link border-0 text-dark py-3" id="progress-tab"
						data-toggle="tab" href="#progress" role="tab"
						aria-controls="progress" aria-selected="false"> <i
							class="fas fa-clock mr-2 text-warning mb-0 fa-2x"></i> On Progress
					</a></li>
					<li class="nav-item border-top" role="presentation"><a
						class="nav-link border-0 text-dark py-3" id="canceled-tab"
						data-toggle="tab" href="#canceled" role="tab"
						aria-controls="canceled" aria-selected="false"> <i
							class="fas fa-times mr-2 text-danger mb-0 fa-2x"></i> Canceled
					</a></li>
				</ul>
			</div>
			<div class="tab-content col-md-9" id="myTabContent">
				<div class="tab-pane fade show active" id="completed"
					role="tabpanel" aria-labelledby="completed-tab">
					<div class="order-body">
						<?php foreach ($orders as $orderDetails) {
						    $order = $orderDetails["order"];
						    $lineItems = $orderDetails["lineItems"];
						    if ($order->order_status==2) {
						        //$orderDetails = $repository->getOrderDetails($order->clid);
						?>
						<div class="pb-3">
							<div class="p-3 rounded shadow-sm bg-white">
								<div class="d-flex border-bottom pb-3">
									<div>
										<p class="mb-0 font-weight-bold">
											<a href="<?php echo buynowdepot_get_page_url('bnd-menuitems'); ?>" class="text-dark"><?php echo $merchant->name?></a>
										</p>
										<p class="mb-0"><?php echo $merchant->city?>, <?php echo $merchant->country?></p>
										<p>ORDER # <?php echo $order->clid?></p>
									</div>
									<div class="m-1" style="width:250px">
										<?php
    										foreach ($lineItems as $line) {?>
                        					<p class="mb-2">
                        						<?php echo $line["item"]->name;?> x 1 &nbsp;<span
                        										class="float-right text-secondary"><?php echo buynowdepot_format_price($line["line"]->price_with_modification);?></span>
                        								</p>
                        					<?php } ?>
									</div>
									<div class="ml-auto">
										<p class="bg-success text-white py-1 px-2 rounded small mb-1">Delivered</p>
										<p class="small font-weight-bold text-center">
											<i class="fas fa-clock"></i> <?php echo $order->created_time?>
										</p>
									</div>
								</div>
								<div class="d-flex pt-3">
									<div class="text-muted m-0 ml-auto mr-3 small">
										Total Payment<br> <span
											class="text-dark font-weight-bold"><?php echo buynowdepot_format_price($order->total)?></span>
									</div>
									<div class="text-right">
										<a href="<?php echo buynowdepot_get_page_url('bnd-menuitems'); ?>" class="btn btn-primary px-3 text-white">Reorder</a>
									</div>
								</div>
							</div>
						</div>
						<?php } }?>
					</div>
				</div>
				<div class="tab-pane fade" id="progress" role="tabpanel"
					aria-labelledby="progress-tab">
					<div class="order-body">
						<?php foreach ($orders as $orderDetails) {
						    $order = $orderDetails["order"];
						    $lineItems = $orderDetails["lineItems"];
						    if ($order->order_status==0 || $order->order_status==1) {
						        
						?>
						<div class="pb-3">
							<div class="p-3 rounded shadow-sm bg-white">
								<div class="d-flex border-bottom pb-3">
									<div>
										<p class="mb-0 font-weight-bold">
											<a href="<?php echo buynowdepot_get_page_url('bnd-menuitems'); ?>" class="text-dark"><?php echo $merchant->name?></a>
										</p>
										<p class="mb-0"><?php echo $merchant->city?>, <?php echo $merchant->country?></p>
										<p>ORDER #<?php echo $order->clid?></p>
									</div>
									<div class="m-0" style="width:250px">
										<?php
    										foreach ($lineItems as $line) {?>
                        					<p class="mb-2">
                        						<?php echo $line["item"]->name;?> x 1 &nbsp;<span
                        										class="float-right text-secondary"><?php echo buynowdepot_format_price($line["line"]->price_with_modification);?></span>
                        								</p>
                        					<?php }?> 
									</div>
									<div class="ml-auto">
										<p class="bg-warning text-white py-1 px-2 rounded small mb-1">Pending</p>
										<p class="small font-weight-bold text-center">
											<i class="fas fa-clock"></i> <?php echo $order->created_time?>
										</p>
									</div>
								</div>
								<div class="d-flex pt-3">
									<div class="text-muted m-0 ml-auto mr-3 small">
										Total Payment<br> <span
											class="text-dark font-weight-bold"><?php echo buynowdepot_format_price($order->total)?></span>
									</div>
									<div class="text-right">
										<a href="<?php echo buynowdepot_get_page_url('bnd-trackorder'); ?>&orderno=<?php echo $order->clid;?>" class="btn btn-primary px-3 text-white">Track Order</a>
										<a href="<?php echo buynowdepot_get_page_url('bnd-contact-us'); ?>" class="btn btn-outline-primary px-3">Help</a>
									</div>
								</div>
							</div>
						</div>
						<?php } }?>
					</div>
				</div>
				<div class="tab-pane fade" id="canceled" role="tabpanel"
					aria-labelledby="canceled-tab">
					<div class="order-body">
						<?php foreach ($orders as $orderDetails) {
						    $order = $orderDetails["order"];
						    $lineItems = $orderDetails["lineItems"];
						    if ($order->order_status==3) {
						?>
						<div class="pb-3">
							<div class="p-3 rounded shadow-sm bg-white">
								<div class="d-flex border-bottom pb-3">
									<div>
										<p class="mb-0 font-weight-bold">
											<a href="<?php echo buynowdepot_get_page_url('bnd-menuitems'); ?>" class="text-dark"><?php echo $merchant->name?></a>
										</p>
										<p class="mb-0"><?php echo $merchant->city?>, <?php echo $merchant->country?></p>
										<p>ORDER #<?php echo $order->clid?></p>
									</div>
									<div class="m-0" style="width:250px">
										<?php
    										foreach ($lineItems as $line) {?>
                        					<p class="mb-2">
                        						<?php echo $line["item"]->name;?> x 1 &nbsp;<span
                        										class="float-right text-secondary"><?php echo buynowdepot_format_price($line["line"]->price_with_modification);?></span>
                        								</p>
                        					<?php } ?>
									</div>
									<div class="ml-auto">
										<p class="bg-danger text-white py-1 px-2 rounded small mb-1">Cancelled</p>
										<p class="small font-weight-bold text-center">
											<i class="fas fa-clock"></i> <?php echo $order->created_time?>
										</p>
									</div>
								</div>
								<div class="d-flex pt-3">
									<div class="text-muted m-0 ml-auto mr-3 small">
										Total Payment<br> <span
											class="text-dark font-weight-bold"><?php echo buynowdepot_format_price($order->total)?></span>
									</div>
									<div class="text-right">
										<a href="<?php echo buynowdepot_get_page_url('bnd-menuitems'); ?>" class="btn btn-primary px-3">Reorder</a>
									</div>
								</div>
							</div>
						</div>
						<?php } }?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>