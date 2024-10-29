<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link       https://buynowdepot.com
 * @since      1.0.0
 *
 * @package    Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/admin/partials
 */
?>
<?php
$BndSettings = (array) get_option("bnd_settings");
$model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
$pending_orders = $model->countOrders(1);
$completed_orders = $model->countOrders(2);
$refunded_orders = $model->countOrders(3);
$users_data = count_users();
$avail_roles = $users_data["avail_roles"];
$customers = isset($avail_roles["subscriber"])?$avail_roles["subscriber"]:0;
$notifications = $model->getNotifications(5);
$activities = $model->getActivityLog(10);
$count_categories = $model->CountCategories();
$count_items = $model->CountItems();
$count_tax_rate = $model->CountTaxes();
$count_modifier_group = $model->CountModifierGroups();
$recent_orders = $repository->getRecentOrders(4)["orders"];
$salesData = $repository->getOrdersByWeek();
$revenueData = $repository->getRevenueByWeek();
$categoryData = $repository->getCategoriesChartData();
$bestSellers = $repository->getBestSellers();
?>
<script type="text/javascript">
var bnd_ajax_url = '<?php echo admin_url( 'admin-ajax.php', isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://' );?>';
var bnd_rest_url= '<?php echo get_rest_url();?>';
var BndSettings = [];
BndSettings['api_region']='<?php echo $BndSettings['api_region']?>';
BndSettings['api_env']='<?php echo $BndSettings['api_env']?>';
BndSettings['merchant_id']='<?php echo $BndSettings['merchant_id']?>';

</script>
<div id="app-container" class="rounded">
	<div class="row">
		<div class="col-sm-12">
			<div class="navbar d-flex">
				<a class="navbar-logo" href="/wp-admin/admin.php?page=bnd_flex_order_delivery_index" style="padding:0 10px"> <img
					src="<?php echo plugin_dir_url(dirname(__FILE__));?>assets/img/logo.png"
					border="0" style="height:70px"/>
				</a>
				<div class="navbar-header"><h1>Advanced Online Ordering and Delivery Platform</h1></div>
				<div class="navbar-right">
					<div class="header-icons d-inline-block align-middle">
						<div class="position-relative d-none d-sm-inline-block">
							<button class="header-icon btn btn-empty" type="button"
								id="iconMenuButton" data-toggle="dropdown" aria-haspopup="true"
								aria-expanded="false">
								<i class="fas fa-grid"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-right position-absolute"
								id="iconMenuDropdown">
								<div class="alert alert-danger">
									You are currently not connected to clover. <br />Please go to
									the setup and specify the API key.
								</div>
							</div>
						</div>
						<div class="position-relative d-inline-block">
							<button class="header-icon btn btn-empty" type="button"
								id="notificationButton" data-toggle="dropdown"
								aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-bell"></i> <span class="count"><?php echo count($notifications); ?></span>
							</button>
							<div
								class="dropdown-menu dropdown-menu-right mt-3 position-absolute"
								id="notificationDropdown">
								<div class="scroll">
								<?php foreach($notifications as $notif) { ?>
									<div class="d-flex flex-row mb-3 pb-3 border-bottom">
										<div class="pl-3">
											<a href="#">
												<p class="font-weight-medium mb-1"><?php echo $notif->message; ?></p>
												<p class="text-muted mb-0 text-small"><?php echo $notif->notification_time; ?></p>
											</a>
										</div>
									</div>
								<?php } ?>
								</div>
							</div>
						</div>
						<button class="header-icon btn btn-empty d-none d-sm-inline-block"
							type="button" id="fullScreenButton">
							<i class="fas fa-expand"></i> <i
								class="fas fa-compress"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($BndSettings["merchant_login"]==null) {?>
	<div class="row">
		<div class="col-sm-12">
			<div class="app-panel app-panel-login">
				<?php
				include plugin_dir_path( dirname( __FILE__ ) ).'templates/login/admin-login.php'; 
				?>				
			</div>
		</div>
	</div>
	<?php } else { ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex">
    			<div class="menu">
    				<div class="main-menu">
    					<ul class="list-unstyled">
    						<li class="active"><div id="dashboard" class="menu-link-div" onclick="javascript:loadFunction('dashboard', true)"> 
    						 <img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/img/dashboard.png" width="56"/>
    						 <span>Dashboard</span>
    						</div></li>
    						<li><div id="connection"  class="menu-link-div" onclick="javascript:loadFunction('connection', true)"><img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/img/connection.png" width="56"/>
    								Connection
    						</div></li>
    						<li><div id="merchant"  class="menu-link-div" onclick="javascript:loadFunction('merchant', true)"> <img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/img/store.png" width="56"/>
    								Restaurant
    						</div></li>
    						<li><div id="inventory" class="menu-link-div"
    							onclick="javascript:loadFunction('inventory', true)"> <img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/img/inventory.png" width="56"/> Inventory
    						</div></li>
    						<li><div id="delivery" class="menu-link-div" onclick="javascript:loadFunction('delivery', true)">
    								<img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/img/delivery.png" width="56"/>Delivery
    						</div></li>
    						<li><div id="discounts" class="menu-link-div" onclick="javascript:loadFunction('discount', true)">
    								<img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/img/discount.png" width="56"/>Discounts
    						</div></li>
    						<li><div id="orders" class="menu-link-div" onclick="javascript:loadFunction('orders', true)">
    								<img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/img/orders.png" width="56"/>Orders
    						</div></li>
    						<li><div id="configuration" class="menu-link-div"	onclick="javascript:loadFunction('configuration', true)"> <img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/img/configuration.png" width="56"/>Configuration
    						</div></li>
    						<li><div id="helpsupport" class="menu-link-div" onclick="javascript:loadFunction('helpsupport', true)"> <img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/img/helpsupport.png" width="56"/>Help & Support
    						</div></li>
    					</ul>
    				</div>
    			</div>
    			<div class="container">
        			<div id="global-alert" class="gobal-alert alert alert-success alert-dismissible" style="display:none">
                          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                          <div id="global-alert-text"></div> 
                    </div>
				<div class="app-panel app-panel-dashboard">
					<div class="row">
						<div class="col-12">
							<h1>Dashboard</h1>
							<div class="separator mb-2"></div>
						</div>
					</div>
					<div class="row icon-cards-row">
						<div class="col-md-3">
							<div class="card">
								<div class="card-body text-center">
									<i class="fas fa-clock"></i>
									<p class="card-text mb-0">Pending Orders</p>
									<p class="lead text-center"><?php echo !empty($pending_orders)?$pending_orders[0]->Count:0; ?></p>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card">
								<div class="card-body text-center">
									<i class="fas fa-file"></i>
									<p class="card-text mb-0">Completed Orders</p>
									<p class="lead text-center"><?php echo !empty($completed_orders)?$completed_orders[0]->Count:0; ?></p>
								</div>
							</div>
						</div>
						<div class="col-md-3">
						<div class="card">
							<div class="card-body text-center">
								<i class="fas fa-arrow-up"></i>
								<p class="card-text mb-0">Refund Requests</p>
								<p class="lead text-center"><?php echo !empty($refunded_orders)?$refunded_orders[0]->Count:0; ?></p>
							</div>
							</div>
						</div>
						<div class="col-md-3">
						<div class="card">
							<div class="card-body text-center">
								<i class="fas fa-at"></i>
								<p class="card-text mb-0">Users</p>
								<p class="lead text-center"><?php echo $customers; ?></p>
							</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-4">
							<div class="card sales-chart">
								<div class="card-body">
									<h5 class="card-title">Sales</h5>
									<div class="dashboard-line-chart chart">
										<canvas id="salesChart"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 mb-4">
							<div class="card revenue-chart">
								<div class="card-body">
									<h5 class="card-title">Revenue</h5>
									<div class="dashboard-line-chart chart">
										<canvas id="revenueChart"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-lg-4 mb-4">
							<div class="card h-100">
								<div class="card-body">
									<h5 class="card-title">Product Categories</h5>
									<div class="dashboard-donut-chart chart">
										<canvas id="polarChart"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-4 mb-4">
							<div class="card h-100">
								<div class="card-body">
									<h5 class="card-title">Activity Log</h5>
									<div class="scroll dashboard-logs">
										<table class="table table-sm table-borderless">
											<tbody>
												<?php foreach($activities as $activity) { ?>
												<tr>
													<td>
													 <span class="log-indicator <?php echo ($activity->status==1)?"border-theme-1":"border-theme-2"?> align-middle"></span>
													</td>
													<td><span class="font-weight-medium"><?php echo $activity->message; ?></span>
													</td>
													<td class="text-right"><span class="text-muted"><?php echo $activity->notification_time;?></span></td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-4 mb-4">
							<div class="card h-100">
								<div class="card-body">
									<h5 class="card-title">Best Sellers</h5>
									<table class="data-table data-table-standard table table-bordered nowrap"
										data-order="[[ 1, &quot;desc&quot; ]]">
										<thead>
											<tr>
												<th>Name</th>
												<th>No. of Sales</th>
												<th>Category</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($bestSellers as $bestSeller) { ?>
											<tr>
												<td>
													<p class="list-item-heading"><?php echo $bestSeller["item"]; ?></p>
												</td>
												<td>
													<p class="text-muted"><?php echo $bestSeller["count"]; ?></p>
												</td>
												<td>
												<p class="text-muted"><?php echo $bestSeller["category"]; ?></p>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>					
					</div>
					<div class="row">
    					<div class="col-md-6 mb-4">
							<div class="card">
								<div class="position-absolute card-top-buttons">
									<button class="btn btn-header-light icon-button">
										<i class="simple-icon-refresh"></i>
									</button>
								</div>
								<div class="card-body">
									<h5 class="card-title">Recent Orders</h5>
									<div class="scroll">
										<?php 
										foreach($recent_orders as $order) { 
										  $lineItems = $order["lineItems"];
										  $item = null;
										  if (!empty($lineItems)) {
										      $item = $lineItems[0];
										  }
										  $orderDetail = $order["order"];
										  $customer = $order["customer"];
                                        ?>
										<div class="d-flex flex-row mb-3">
											<a class="d-block position-relative" href="#"> <img
												src="<?php echo $BndSettings["image_base_url"]."/".($item!=null?$item["item"]->name:"no-item").".jpg"; ?>"
												alt="<?php echo $item["item"]->name;?>" class="list-thumbnail border-0" /> <span
												class="badge badge-pill badge-theme-2 position-absolute badge-top-right">NEW</span>
											</a>
											<div class="pl-3 pt-2 pr-2 pb-2">
												<a href="#">
													<p class="list-item-heading"><?php echo ($item!=null?$item["item"]->name:"");?></p>
													<div class="pr-4 d-none d-sm-block">
														<p class="text-muted mb-1 text-small"></p>
													</div>
													<div class="text-primary text-small font-weight-medium d-none d-sm-block">
														<?php echo $orderDetail->created_time;?></div>
												</a>
											</div>
										</div>
										<?php  } ?>
									</div>
								</div>
							</div>
						</div>
    					<div class="col-md-6">
							<div class="row icon-cards-row">
								<div class="col-6 mb-4">
									<div class="card"><div class="card-body text-center">
										<i class="fas fa-clock"></i>
										<p class="card-text mb-0">Categories</p>
										<p class="lead text-center"><?php echo !empty($count_categories)?$count_categories[0]->Count:0; ?></p>
									</div></div>
								</div>
								<div class="col-6 mb-4">
									<div class="card">
									<div class="card-body text-center">
										<i class="fas fa-file"></i>
										<p class="card-text mb-0">Items</p>
										<p class="lead text-center"><?php echo !empty($count_items)?$count_items[0]->Count:0; ?></p>
									</div>
									</div>
								</div>
								<div class="col-6 mb-4">
								<div class="card">
									<div class="card-body text-center">
										<i class="fas fa-refresh"></i>
										<p class="card-text mb-0">Modifier Groups</p>
										<p class="lead text-center"><?php echo !empty($count_modifier_group)?$count_modifier_group[0]->Count:0; ?></p>
									</div>
								</div>
								</div>
								<div class="col-6 mb-4">
								<div class="card">
									<div class="card-body text-center">
										<i class="fas fa-mail"></i>
										<p class="card-text mb-0">Tax Rates</p>
										<p class="lead text-center"><?php echo !empty($count_tax_rate)?$count_tax_rate[0]->Count:0; ?></p>
									</div>
								</div>
								</div>
							</div>
    					</div>
					</div>
				</div>
				<div class="app-panel app-panel-merchant">
					<div class="col-12">
							<h1>Restaurant</h1>
							<div class="separator mb-0"></div>
					</div>
					<div class="row">
    					<div class="col-sm-12">
    						<ul class="tabs group">
                                <li><a class="active read-merchant-button" href="#">Store Details</a></li>
                                <li><a href="#" class="read-opening-hours-button">Opening Hours</a></li>
                                <li><a href="#" class="read-order-type-button">Order Types</a></li>
                              </ul>                        
                              <div id="merchant-content" class="data-content-area">
                              </div>
    					</div>						
					</div>
				</div>
				<div class="app-panel app-panel-inventory">
					<div class="col-12">
							<h1>Inventory</h1>
							<div class="separator mb-0"></div>
					</div>
					<div class="row">
    					<div class="col-sm-12">
    						<ul class="tabs group">
                                <li><a class="active read-category-button" href="#">Categories</a></li>
                                <li><a href="#" class="read-item-button">Items</a></li>
                                <li><a href="#" class="read-modifier-group-button">Modifier Groups</a></li>
                              </ul>                        
                              <div id="inventory-content" class="data-content-area">
                              </div>
    					</div>						
					</div>
				</div>
				<div class="app-panel app-panel-delivery">
					<div class="col-12">
							<h1>Delivery</h1>
							<div class="separator mb-0"></div>
					</div>
					<div class="row">
    					<div class="col-sm-12">
    						<ul class="tabs group">
    							<li><a class="active read-delivery-setup-button" href="#">Delivery Setup</a></li>
                                <li><a class="read-delivery-zone-button" href="#">Delivery Zone</a></li>
                                <li><a href="#" class="read-live-tracking-button">Live Tracking</a></li>
                              </ul>                        
                              <div id="delivery-content" class="data-content-area">
                              </div>
    					</div>						
					</div>
				</div>
				<div class="app-panel app-panel-orders">
					<div class="col-12">
							<h1>Orders</h1>
							<div class="separator mb-0"></div>
					</div>
					<div class="row">
    					<div class="col-sm-12">
    						<ul class="tabs group">
                                <li><a class="active read-order-button" href="#">Orders</a></li>
                                <li><a href="#" class="read-order-payment-button">Payments</a></li>
                                <li><a href="#" class="read-refund-request-button">Refund Requests</a></li>
                              </ul>                        
                              <div id="order-content" class="data-content-area">
                              </div>
    					</div>						
					</div>
				</div>
				<div class="app-panel app-panel-configuration">
					<div class="col-12">
							<h1>Configuration</h1>
							<div class="separator mb-0"></div>
					</div>
					<div class="row">
    					<div class="col-sm-12">
    						<ul class="tabs group">
                                <li><a class="active read-configuration-button" href="#">Menu Settings</a></li>
                                <li><a class="read-message-template-button" href="#">Message Texts</a></li>
                                <li><a class="read-template-button" href="#">Design Templates</a></li>
                              </ul>                        
                              <div id="configuration-content" class="data-content-area">
                              </div>
    					</div>						
					</div>
				</div>
				<div class="app-panel app-panel-connection">
        			<div class="col-12">
							<h1>Connectivity</h1>
							<div class="separator mb-0"></div>
					</div>
					<div class="row">
    					<div class="col-sm-12">
    						<ul class="tabs group">
                                <li><a class="active read-cloverconnection-button" href="#">API Key</a></li>
                                <li><a href="#" class="read-datasync-button" >Data Synchronization</a></li>
                                <li><a href="#" class="read-manualimport-button" >Manual Import</a></li>
                            </ul>                        
                            <div id="connection-content" class="data-content-area">
                            </div>
    					</div>						
					</div>
        		 </div>
        		 <div class="app-panel app-panel-discount">
					<div class="col-12">
							<h1>Discounts</h1>
							<div class="separator mb-0"></div>
					</div>
					<div class="row">
    					<div class="col-sm-12">
    						<ul class="tabs group">
                                <li><a class="active read-discount-coupon-button" href="#">Discount Coupons</a></li>
                              </ul>                        
                              <div id="discount-content" class="data-content-area">
                              </div>
    					</div>						
					</div>
				</div>
        		<div class="app-panel app-panel-helpsupport">
					<div class="col-12">
							<h1>Help & Support</h1>
							<div class="separator mb-0"></div>
					</div>
					<div class="row">
    					<div class="col-sm-12">
    						<ul class="tabs group">
                                <li><a class="active read-faq-button" href="#">FAQs</a></li>
                                <li><a href="#" class="read-training-button">Docs & Training</a></li>
                              </ul>                        
                              <div id="helpsupport-content" class="data-content-area">
                              </div>
    					</div>						
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
	<?php }?>
</div>
<script type="text/javascript">
$(document).ready(function() {
	loadSmallCharts();
	var chartData= <?php echo json_encode($salesData); ?>;
	displaySalesChart(chartData);
	var revenueData= <?php echo json_encode($revenueData); ?>;
	displayRevenueChart(revenueData);
	var categoryData = <?php echo json_encode($categoryData); ?>;
	displayPolarChart(categoryData);
});
</script>