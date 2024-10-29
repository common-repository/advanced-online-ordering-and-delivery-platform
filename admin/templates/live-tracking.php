<?php 
/**
 * Live tracking
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */
$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$result = $db->getMerchantAddress();
$keywords="";
?>
<div class="row">
	<div class="col-12">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Live Delivery Tracking</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'>
				<form id='search-delivery-zone-form' action='#' method='post'>
					<div class='input-group pull-left w-30-pct' data-toggle='tooltip'
						title='Search categories.' data-placement='right'>

						<input type='text' value='<?php echo $keywords;?>' name='keywords'
							class='form-control category-search-keywords'
							placeholder='Enter order #, Driver # etc.' /> <span
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
		<div id="google-map" style="width: 100%; height: 800px"	class="marchant-location-map mb-0"></div>
	</div>
</div>
<script type="text/javascript">
function initMap() {
	var geocoder = new google.maps.Geocoder();
    var address = '<?php echo $result->zip;?>';
	var lat=<?php echo ($result->lat)?$result->lat:0;?>;
    var lng=<?php echo ($result->lng)?$result->lng:0;?>;
    if (lat==0 && lng==0) {
        geocoder.geocode({ 'address': 'zipcode '+address }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                lat = results[0].geometry.location.lat();
                lng = results[0].geometry.location.lng();
                setupDeliveryMap(lat,lng);
                updateMarkers();
            } else {
                alert("Request failed.")
            }
    	});
    }
    else {
     	setupDeliveryMap(lat, lng);
     	updateMarkers();
    }
}

$(document).ready(function(){
	initMap();
});

var counter = 0;
var vehicles={};

function getData() {
	var lat=<?php echo ($result->lat)?$result->lat:0;?>;
    var lng=<?php echo ($result->lng)?$result->lng:0;?>;
    var factor = 0.0001*counter;
    var data = [
    	{id:1,name:"Vehice1",lat:lat+counter*factor,lng:lng+counter*factor},
    	{id:2,name:"Vehice2",lat:lat+counter*factor,lng:lng},
    	{id:3,name:"Vehice3",lat:lat-counter*factor,lng:lng},
    	{id:4,name:"Vehice4",lat:lat,lng:lng+counter*factor}
    ];
	counter+=1;
	return data;
}

function updateMarkers() {
	var data = getData();
	for (var i = 0; i < data.length; i++) {
    	var id = data[i]['id'];
    	var name = data[i]['name'];
    	var point = {lat:data[i]['lat'],lng:data[i]['lng']};
        console.log(point);
    	var existing = vehicles[id];
    	if (existing == undefined) {
    		var marker = createMarker(point, name);
    		vehicles[id] = marker;
    	} else {
    		vehicles[id].setPosition(point);
    	}
	}
	setTimeout(function() {
		updateMarkers();
	}, 10000);
}

function createMarker(point, name) {
	var marker = new google.maps.Marker({
    	position: point,
    	title: name,
    	icon:'<?php echo plugin_dir_url( __FILE__ )."/car1.png";?>',
    	map: map
	});
	 
	var infowindow = new google.maps.InfoWindow({
		content: name
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});	 
	return marker;
}
</script>