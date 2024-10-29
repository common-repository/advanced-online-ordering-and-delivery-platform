<?php
/**
 * Manual import
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */
$BndSettings = (array) get_option("bnd_settings");
?>
<div class="row">
	<div class="col-6">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Import Manually</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class="card mb-12">
			<div class="card-body">
			<form action="" method="post">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Data type</th>
							<th>Import Status</th>
							<th>Import</th>
						</tr>
					</thead>
					<tbody>
						<tr>
            				<td>Merchant Details</td>
							<td class="merchant">
								<div id="merchant-sync-icon" class="sync-icon"><i class="fas fa-arrow-alt-circle-down"></i></div>
								<div class="small-text" id="import-text"></div>
    							<div class="spinner-border text-primary" role="status" style="display:none">
                                      <span class="sr-only">Loading...</span>
                                </div>
							</td>
							<td><button type="button" class="btn btn-primary"
									onclick="importModel('merchant','merchant')">Import</button></td>
						</tr>
						<tr>
							<td>Opening Hours</td>
							<td class="opening_hours">
								<div id="opening_hours-sync-icon" class="sync-icon"><i class="fas fa-arrow-alt-circle-down"></i></div>
								<div class="small-text" id="import-text"></div>
    							<div class="spinner-border text-primary" role="status" style="display:none">
                                      <span class="sr-only">Loading...</span>
                                </div>
							</td>
							<td><button type="button" class="btn btn-primary"
									onclick="importModel('opening_hours','opening_hours')">Import</button></td>
						</tr>
						<tr>
							<td>Order Types</td>
							<td class="order_types">
								<div id="order_types-sync-icon" class="sync-icon"><i class="fas fa-arrow-alt-circle-down"></i></div>
								<div class="small-text" id="import-text"></div>
    							<div class="spinner-border text-primary" role="status" style="display:none">
                                      <span class="sr-only">Loading...</span>
                                </div>
							</td>
							<td><button type="button" class="btn btn-primary"
									onclick="importModel('order_types','order_types')">Import</button></td>
						</tr>
						<tr>
							<td>Categories</td>
							<td class="categories">
								<div id="categories-sync-icon" class="sync-icon"><i class="fas fa-arrow-alt-circle-down"></i></div>
								<div class="small-text" id="import-text"></div>
    							<div class="spinner-border text-primary" role="status" style="display:none">
                                      <span class="sr-only">Loading...</span>
                                </div>
							</td>
							<td><button type="button" class="btn btn-primary"
									onclick="importModel('categories','categories')">Import</button></td>
						</tr>
						<tr>
							<td>Modifier Groups/Options</td>
							<td class="modifier_groups">
								<div id="modifier_groups-sync-icon" class="sync-icon"><i class="fas fa-arrow-alt-circle-down"></i></div>
								<div class="small-text" id="import-text"></div>
    							<div class="spinner-border text-primary" role="status" style="display:none">
                                      <span class="sr-only">Loading...</span>
                                </div>
							</td>
							<td><button type="button" class="btn btn-primary"
									onclick="importModel('modifier_groups','modifier_groups')">Import</button></td>
						</tr>
						<tr>
							<td>Items</td>
							<td class="items">
								<div id="items-sync-icon" class="sync-icon"><i class="fas fa-arrow-alt-circle-down"></i></div>
								<div class="small-text" id="import-text"></div>
    							<div class="spinner-border text-primary" role="status" style="display:none">
                                      <span class="sr-only">Loading...</span>
                                </div>
							</td>
							<td>
								<button type="button" class="btn btn-primary"
									onclick="importModel('items','items')">Import</button><br/>
								<button type="button" class="btn btn-primary"
									onclick="importModel('items_by_category','items')" style="margin-top:5px;">Import by Category</button>
							</td>
						</tr>
						<tr>
							<td>Item Groups</td>
							<td class="item_groups">
								<div id="item_groups-sync-icon" class="sync-icon"><i class="fas fa-arrow-alt-circle-down"></i></div>
								<div class="small-text" id="import-text"></div>
    							<div class="spinner-border text-primary" role="status" style="display:none">
                                      <span class="sr-only">Loading...</span>
                                </div>
							</td>
							<td><button type="button" class="btn btn-primary"
									onclick="importModel('item_groups','item_groups')">Import</button></td>
						</tr>
						<tr>
							<td>Attributes</td>
							<td class="attributes">
								<div id="attributes-sync-icon" class="sync-icon"><i class="fas fa-arrow-alt-circle-down"></i></div>
								<div class="small-text" id="import-text"></div>
    							<div class="spinner-border text-primary" role="status" style="display:none">
                                      <span class="sr-only">Loading...</span>
                                </div>
							</td>
							<td><button type="button" class="btn btn-primary"
									onclick="importModel('attributes','attributes')">Import</button></td>
						</tr>
						<tr>
							<td>Item Tags</td>
							<td class="item_tags">
								<div id="item_tags-sync-icon" class="sync-icon"><i class="fas fa-arrow-alt-circle-down"></i></div>
								<div class="small-text" id="import-text"></div>
    							<div class="spinner-border text-primary" role="status" style="display:none">
                                      <span class="sr-only">Loading...</span>
                                </div>
							</td>
							<td><button type="button" class="btn btn-primary"
									onclick="importModel('item_tags','item_tags')">Import</button></td>
						</tr>
						<tr>
							<td>Tax Rates</td>
							<td class="tax_rates">
								<div id="tax_rates-sync-icon" class="sync-icon"><i class="fas fa-arrow-alt-circle-down"></i></div>
								<div class="small-text" id="import-text"></div>
    							<div class="spinner-border text-primary" role="status" style="display:none">
                                      <span class="sr-only">Loading...</span>
                                </div>
							</td>
							<td><button type="button" class="btn btn-primary"
									onclick="importModel('tax_rates','tax_rates')">Import</button></td>
						</tr>
					</tbody>
				</table>
				<button type="button" class="btn btn-success mb-0"
						onclick="importAll()">Import All</button>
				</form>
			</div>
		</div>
	</div>
</div>
