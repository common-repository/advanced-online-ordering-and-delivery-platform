function setupMerchant() {
    $(document).on('click', '.read-merchant-button', function(){
    	showMerchant();
    });
    $(document).on('click', '.read-opening-hours-button', function(){
    	showOpeningHours();
    });
    setupModel("#merchant-content","order-type");
}

function showMerchant() {
	var elem = $('.read-merchant-button');
	activateTab(elem);
	loadTemplate('merchant',"#merchant-content", function(){
		$('.data-editable').editable({
			send: 'always',
			type:$(this).data("type"),
		    url: bnd_rest_url+'bnd-rest-api/merchant/update_field',
		    title: 'Enter '+$(this).data("label"),
		    success:function(response, newValue) {
		    	console.log(response);
		    }
		});
	});
}
function showOpeningHours() {
	var elem = $('.read-opening-hours-button');
	activateTab(elem);
	loadTemplate('opening-hours',"#merchant-content", function(){});
}

function updateOpeningHours(id, day) {
	var data = [];
	data["id"]=id;
	var startTime = $('#'+day+'-start').val();
	var endTime = $('#'+day+'-end').val();
	data[day]=startTime+"-"+endTime;
	updateModelFields(data, "#merchant-content", "opening-hours", function() {});
}

function setupMap(lat, lng) {
	if (typeof google === 'object' && typeof google.maps === 'object') {
		const location = { lat: lat, lng: lng };
	    console.log(location);
	    // The map, centered at Uluru
	    window.map = new google.maps.Map(document.getElementById("google-map"), {
	      zoom: 14,
	      center: location,
	    });
	    // The marker, positioned at Uluru
	    const marker = new google.maps.Marker({
	      position: location,
	      map: window.map,
	      draggable:true,
	    });
	    google.maps.event.addListener(marker, 'dragend', function(event) {
	        var currentPosition = [event.latLng.lat(),event.latLng.lng()];
	        $('#current-map-location').val(currentPosition.toString());
	    });
	}
	else {
		showAlert("Google map api is not configured. please configure to use map related features");
	}
}

function saveMapLocation(target) {
	var url = bnd_rest_url+'bnd-rest-api/merchant/update';
	var latlng = $('#current-map-location').val().split(",");
	var id = $('#current-map-location').data("id");
	showLoading(target);
	$.ajax({
		  type: "POST",
		  url: url,
		  data:"id="+id+"&lat="+latlng[0]+"&lng="+latlng[1],
		  success: function(response){
			console.log(response);
			hideLoading(target);
		  },
		  failure: function(response) {	        			  
		  }
	});
}