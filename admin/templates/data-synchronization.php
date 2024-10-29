<?php

/**
 * Data sync
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */

$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$results = $db->getDataSyncs();
?>
<div class="row">
	<div class="col-6">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Data Synchronization</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class="card mb-12">
			<div class="card-body">
			<form action="" method="post">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Setup type</th>
							<th>Auto Sync</th>
							<th>Last Sync Time</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($results as $val) {?>
						<tr>
							<td><?php echo $val->display_name;?></td>
							<td>
								<label class="label-switch">
                                  <input type="checkbox" id="temp-sync_enabled-<?php echo $val->id; ?>" <?php echo $val->sync_enabled?"checked":""; ?> onclick="updateDataSync('<?php echo $val->id;?>')">
                                  <span class="label-slider round"></span>
                                </label>
                                <input type="hidden" name="sync_enabled-<?php echo $val->id; ?>" id="sync_enabled-<?php echo $val->id; ?>" value="<?php echo $val->sync_enabled?1:0; ?>"/>
							</td>
							<td><?php echo $val->last_sync_time;?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				</form>
			</div>
		</div>
	</div>
</div>
