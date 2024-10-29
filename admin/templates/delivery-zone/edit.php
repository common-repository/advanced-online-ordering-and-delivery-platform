<?php 
/**
 * Delivery Zone CRUD
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/templates/delivery-zone
 */

$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$result = $db->getMerchantAddress();
$results = $db->getAllModels("delivery_zone");
$currentMap = $db->getModelById("delivery_zone", sanitize_text_field($_GET["id"]));
$BndSettings = (array)get_option("bnd_settings");
?>
<div class="row">
	<div class="col-12">
		<div class="d-flex">
			<div class="connection-header">
				<h4 class="mb-0">Update Zone</h4>
			</div>
			<div class="align-right"></div>
		</div>
		<div class='d-flex mb-2'>
			<div class='data-form-search'></div>
			<div class='data-button-area'>
				<div id='read-delivery-zone'
					class='btn btn-primary pull-right m-b-15px mr-1 read-delivery-zone-button'>
					<span class='fas fa-list'></span> Delivery Zone List
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
		<div class="col-sm-7">
		<?php if ($BndSettings["google_maps_api_key"]=="") {?>
		<div class="alert alert-danger">
			Google map api key is not configured for you account. please add it to enable map related features.
		</div>
		<?php } ?>
		<div class="d-flex">
			<div class="connection-header">
				
			</div>
		</div>
		<div class="card mb-12">
			
			<div class="card-header"><h4 class="mb-0">Choose region on the map</h4></div>
			<div class="card-body">
				<form action="" method="post">
					<div id="google-map" style="width: 100%; height: 500px"
						class="marchant-location-map mb-0"></div>
					<div class="merchant-location-text mb-0">Draw a zone and fill the information on the right to define a zone.</div>
				</form>
  				<button type="button" id="delete-selected-shape" class="btn btn-primary mr-0 mb-1">Delete Selected</button>    		    
			</div>
		</div>
	</div>
	<div class="col-sm-5">
		<div class="card mb-12">
			<div class="card-header"><h4 class="mb-0">Update zone details</h4></div>
			<div class="card-body">
        		<form id='update-delivery-zone-form' action='#' method='post'>
        			<input type="hidden" name="id" id="id" value="<?php echo $currentMap->id;?>"/>
        			<table class='table table-hover table-responsive table-bordered' style="width:100%">       
        				<tr>
        					<td>Name</td>
        					<td><input type='text' name='name' class='form-control' id="name"  value="<?php echo $currentMap->name;?>"/></td>
        				</tr>
        				<tr>
        					<td>Fee Type</td>
        					<td>
        						<select name="fee_type">
        							<option value="Amount">Amount</option>
        							<option value="Percentage">Percentage</option>
        						</select>       				
        					</td>
        				</tr> 
        				<tr>
        					<td>Minimum amount</td>
        					<td>
        						<input type='text' id="min-amount" name='min_amount' class='form-control'  value="<?php echo $currentMap->min_amount;?>"/> 				
        					</td>
        				</tr>      
        				<tr>
        					<td>Delivery Fee</td>
        					<td>
        						<input type='text' id="delivery-fee" name='delivery_fee' class='form-control'  value="<?php echo $currentMap->delivery_fee;?>"/> 				
        					</td>
        				</tr>      
        				<tr>
        					<td>Region Data</td>
        					<td><textarea id="area-map" name='area_map' class='form-control' readonly="readonly"><?php echo html_entity_decode($currentMap->area_map);?></textarea></td>
        				</tr>
        
        				<tr>
        					<td></td>
        					<td>
        						<button type='button' class='btn btn-primary' onclick="updateModel('#delivery-content', 'delivery-zone')">
        							<span class='fas fa-plus'></span> Save
        						</button>
        					</td>
        				</tr>
        	
        			</table>
        			<input type="hidden" id="zone-type" name="zone_type" value="<?php echo $currentMap->zone_type;?>">
        		</form>
        	</div>
        </div>
	</div>
</div>
<script type="text/javascript">
function initMap() {
	if (typeof google === 'object' && typeof google.maps === 'object') {
    	var geocoder = new google.maps.Geocoder();
        var address = '<?php echo $result->zip;?>'
        	var lat=<?php echo ($result->lat)?$result->lat:0;?>;
            var lng=<?php echo ($result->lng)?$result->lng:0;?>;
        if (lat==0 && lng==0) {
            geocoder.geocode({ 'address': 'zipcode '+address }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    lat = results[0].geometry.location.lat();
                    lng = results[0].geometry.location.lng();
                    setupDeliveryMap(lat,lng);
                    setupMapDrawing();
                } else {
                	showAlert("Unable to setup map, please check if you have correct google maps api.");
                }
        	});
        }
        else {
         	setupDeliveryMap(lat, lng);
         	setupMapDrawing();
        }
        currentMapId = <?php echo $currentMap->id;?>;
        <?php foreach ($results as $row) {?>
        	allMapsData['<?php echo $row->id;?>']='<?php echo json_encode($row);?>';
        <?php } ?>
        drawExistingMap();
	}
	else {
		showAlert("Google map api key is not configured. please configure it to enable map related features");
	}
}

$(document).ready(function(){
	initMap();

	$("#create-delivery-zone-form").validate({
	    // Specify validation rules
	    rules: {
	      // The key name on the left side is the name attribute
	      // of an input field. Validation rules are defined
	      // on the right side
	      name: "required",
	      delivery_fee: "required",
	      min_amount:"required",
	      area_map:"required",
	    },
	    // Specify validation error messages
	    messages: {
	      name: "Please enter name for the zone",
	      delivery_fee: "Please enter delivery fee",
		  area_map: "Please choose an area on the map",
		  min_amount:"Minimum amount is required"
	    }
	  }); 
});
</script>