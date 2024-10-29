<?php
/**
 * Common header for categories
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
defined('ABSPATH') || exit();
$theme_url = buynowdepot_get_theme_url();
$repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
$categories = $repository->getCategoriesItems(array());
$catCount = count($categories);
if ($catCount>5) {
    $catCount=5;
}
$show_blank_categories = buynowdepot_get_option("show_blank_categories");
?>
<div class="container">
	<div class="sticky_sidebar">
		<p class="font-weight-bold pt-3 m-0">Categories</p>
		<!-- slider -->
		<div class="page-category-slider rounded" id="bnd-category-list">
		<?php
        foreach ($categories as $category) {
            ?>
			<div class="bnd-slider-item" data-clid="<?php echo $category["clid"];?>" onclick="loadCategory('<?php echo esc_url( buynowdepot_get_page_url( 'bnd-menuitems' ) );?>');"  style="cursor:pointer;">
				<div
					class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
					<div class="list-card-image">
						<img alt="#" src="<?php echo $category["image_link"];?>"
							class="img-fluid item-img w-100">
					</div>
					<div class="p-1 position-relative">
						<div class="list-card-body">
							<div class="heading mb-1">
								<span class="text-black"><?php echo $category["name"];?></span>
							</div>
							<p class="text-gray mb-3"><?php echo $category["description"];?></p>
						</div>
					</div>
				</div>
			</div>
		<?php
        }
        ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		allPageSlider(<?php echo $catCount;?>);
	});
	function loadCategory(url) {
		window.location.href=url;
	}
</script>