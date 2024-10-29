<?php
/**
 * Change template
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
	<div class="col-3">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Menu Templates</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class="card mb-12">
			<div class="card-body">
			<form action="" method="post">
				<table class="table table-bordered align-center">
					<tbody>
						<tr>
							<td><h4>Flexmenu1</h4></td>
						</tr>
						<tr>
							<td>
								<img src="<?php echo WP_PLUGIN_URL."/bnd-flex-order-delivery/admin/assets/img/flexmenu.jpg"?>" width="200"/>
							</td>
						</tr>
						<tr>
							<td><input type="button" class="btn btn-secondary" value="Selected"></td>
						</tr>					
					</tbody>
				</table>
				</form>
			</div>
		</div>
	</div>
</div>
