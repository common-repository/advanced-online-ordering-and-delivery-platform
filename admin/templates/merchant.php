<?php
/**
 * Merchant
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 */
$model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$result = $model->getCurrentMerchant();
$BndSettings = (array)get_option("bnd_settings");
if (!$result) { ?>
    <div class="row">
        <div class="col-sm-6">
            Merchant details are not updated. Please update your details.
        </div>
    </div>
<?php 
} else {
?>
<div class="row">
	<div class="col-sm-6">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Restaurant Details</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class="card mb-12">
			<div class="card-body">
			<form action="" method="post">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td>Name</td>
							<td><strong><?php echo ($result!=null)?$result->name:""; ?></strong></td>
						</tr>
						<tr>
							<td>Address 1</td>
							<td>
								<?php if ($result!=null) {
								    $address = $result->address1;
                                    echo $address;
								}?>
							</td>
						</tr>
						<tr>
							<td>Address 2</td>
							<td>
								<?php if ($result!=null) {
								    if (isset($result->address2)) {
								        $address=$result->address2;
								    }
                                    echo $address;
								}?>
							</td>
						</tr>
						<tr>
							<td>Address 3</td>
							<td>
								<?php if ($result!=null) {
								    if (isset($result->address3)) {
								        $address=$result->address3;
								    }
								    echo $address;
								}?>
							</td>
						</tr>
						<tr>
							<td>City</td>
							<td><?php echo $result->city; ?></td>
						</tr>
						<tr>
							<td>State</td>
							<td><?php echo $result->state; ?></td>
						</tr>
						<tr>
							<td>Zip</td>
							<td><?php echo $result->zip; ?></td>
						</tr>
						<tr>
							<td>Country</td>
							<td><?php echo $result->country; ?></td>
						</tr>
						<tr>
							<td>Phone</td>
							<td><?php echo ($result!=null)?$result->phone_number:""; ?></td>
						</tr>
						<tr>
							<td>Email</td>
							<td><?php echo ($result!=null)?$result->contact_email:""; ?></td>
						</tr>	
						<tr>
							<td>Website</td>
							<td><?php echo ($result!=null)?$result->website:""; ?></td>
						</tr>	
						<tr>
							<td>Latitude</td>
							<td><?php echo ($result!=null && isset($result->lat))?$result->lat:"0"; ?></td>
						</tr>	
						<tr>
							<td>Longitude</td>
							<td><?php echo ($result!=null && isset($result->lng))?$result->lng:"0"; ?></td>
						</tr>
						<tr>
							<td>QR Code</td>
							<td>
								<div id="qrcode" style="width:100px; height:100px; margin-top:5px;"></div>
								<a class="btn btn-secondary" href="#" style="width:100px; margin-top:5px;" id="downloadQR" onclick="downloadQR()">Download</a>
							</td>
						</tr>						
					</tbody>
				</table>
				<!-- 
				<button type="button" class="btn btn-success mb-0"
						onclick="saveMerchantLocal()">Save on Clover</button>
				 -->
				</form>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Choose location on map</h4>
			</div>
		</div>
		<div class="card mb-12">
			<div class="card-body">
			<?php if ($BndSettings["google_maps_api_key"]=="") {?>
			<div class="alert alert-danger">
				Google map api key is not configured for you account. please add it to enable map related features.
			</div>
			<?php } ?>
			<form action="" method="post">
				<div id="google-map" style="width:100%;height:570px" class="marchant-location-map mb-0">
				</div>
				<div class="merchant-location-text mb-0">Drag the marker to adjust your location on the map and click save location button</div>
				<button type="button" class="btn btn-success mb-0"
						onclick="saveMapLocation('#google-map')">Save Location</button> 
				<input type="hidden" id="current-map-location" data-id="<?php echo $result->clid;?>" value=""/>
			</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

$(document).ready(function(){
	initMap();
	makeCode('<?php echo get_site_url()."/bnd-menuitems";?>');
});
function initMap() {
	if (typeof google === 'object' && typeof google.maps === 'object') {
    	var geocoder = new google.maps.Geocoder();
        var address = '<?php echo $result->zip;?>';
        var lat=<?php echo ($result->lat)?$result->lat:0;?>;
        var lng=<?php echo ($result->lat)?$result->lng:0;?>;
        if (lat==0 && lng==0) {
            geocoder.geocode({ 'address': 'zipcode '+address }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    lat = results[0].geometry.location.lat();
                    lng = results[0].geometry.location.lng();
                    setupMap(lat,lng);
                    
                } else {
                    alert("Request failed.")
                }
        	});
        }
        else {
         	setupMap(lat, lng);
        }
	}
	else {
		showAlert("Google map api key is not configured. Please enable it to configure map related features");
	}
}

	

function makeCode (url) {	
	var qrcode = new QRCode(document.getElementById("qrcode"), {
		width : 100,
		height : 100
	});			
	qrcode.makeCode(url);
}

function downloadQR() {
	var img = document.querySelector('#qrcode img');
	var url = img.src;
	url = url.replace(/^data:image\/\w+;base64,/, '');
	downloadImage('qrcode', url, "png");
}

function downloadImage(name, content, type) {
	  var link = document.createElement('a');
	  link.style = 'position: fixed; left -10000px;';
	  link.href = `data:application/octet-stream;base64,${encodeURIComponent(content)}`;
	  link.download = /\.\w+/.test(name) ? name : `${name}.${type}`;

	  document.body.appendChild(link);
	  link.click();
	  document.body.removeChild(link);
	}
</script>
<?php } ?>