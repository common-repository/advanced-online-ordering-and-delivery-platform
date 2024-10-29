<?php 
/**
 * Refund requests
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */
$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$keywords="";
?>
<div class="row">
	<div class="col-12">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Refund requests</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'>
				<form id='search-delivery-zone-form' action='#' method='post'>
					<div class='input-group pull-left w-30-pct' data-toggle='tooltip'
						title='Search refunds' data-placement='right'>

						<input type='text' value='<?php echo $keywords;?>' name='keywords'
							class='form-control category-search-keywords'
							placeholder='Enter order #' /> <span
							class='input-group-append'>
							<button type='submit' class='btn btn-primary' type='button'>
								<i class='fas fa-search'></i>
							</button>
						</span>

					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		You do not have any refund requests.
	</div>
</div>