function setupDiscountCoupon() {
    setupModel('#discount-content',"discount-coupon");
    $(document).on('click', '.read-live-tracking-button', function(){
    	showLiveTracking();
    });
}

function showDiscountCoupon() {
	var elem = $('.read-discount-coupon-button');
	activateTab(elem);
	showModelFirstPage('#discount-content',"discount-coupon");
}