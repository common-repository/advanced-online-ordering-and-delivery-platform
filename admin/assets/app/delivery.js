var markers = [];
var coords =[];
var polygon;
var currentMarker;
var map;
var shape="";
var shapeDrawn=false;
var infoWindow;
var shapes = {};
var selectedShape;
var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
var labelIndex = 0;
var drawingManager;
var counterShape=0;
var allMapsData = {};
var currentMapId;
var circleScales={
	"20":1128.497220,
	"19":2256.994440,
	"18":4513.988880,
	"17":9027.977761,
	"16":18055.955520,
	"15":36111.911040,
	"14":72223.822090,
	"13":144447.644200,
	"12":288895.288400,
	"11":577790.576700,
	"10":1155581.153000,
	"9":2311162.307000,
	"8":4622324.614000,
	"7":9244649.227000,
	"6":18489298.450000,
	"5":36978596.910000,
	"4":73957193.820000,
	"3":147914387.600000,
	"2":295828775.300000,
	"1":591657550.500000
};
const iconBase =  "https://developers.google.com/maps/documentation/javascript/examples/full/images/"


function setupDelivery() {
    setupModel('#delivery-content',"delivery-zone");
    $(document).on('click', '.read-live-tracking-button', function(){
    	showLiveTracking();
    });
    $(document).on('click', '.read-delivery-setup-button', function(){
    	showDeliverySetup();
    });
}

function showDelivery() {
	var elem = $('.read-delivery-zone-button');
	activateTab(elem);
	showModelFirstPage('#delivery-content',"delivery-zone");
}
function showDeliverySetup() {
	var elem = $('.read-delivery-setup-button');
	activateTab(elem);
	loadTemplate('delivery-setup',"#delivery-content", function(){
	});
}

function showLiveTracking() {
	var elem = $('.read-live-tracking-button');
	activateTab(elem);
	loadTemplate('live-tracking',"#delivery-content", function(){
		initTrackingMap();
	});
}


function setupDeliveryMap(lat, lng) {
	const location = { lat: lat, lng: lng };
    console.log(location);
    // The map, centered at Uluru
    map = new google.maps.Map(document.getElementById("google-map"), {
      zoom: 14,
      center: location,
    });
    // The marker, positioned at Uluru
    const marker = new google.maps.Marker({
      position: location,
      map: map,
      draggable:true,
    });
    $('#current-map-location').val(location.toString());

    infoWindow= new google.maps.InfoWindow({ 
  		    size: new google.maps.Size(150,50)
  		  });
}

function setupMapDrawing() {

    var rectangleOptions = {
    	strokeColor: "#FF0000",
   		strokeOpacity: 0.8,
   		strokeWeight: 2,
   		fillColor: "#FF0000",
   		fillOpacity: 0.35,
   		editable: true,
   		draggable:true
    };
    var circleOptions = {
    	strokeColor: "#00ff00",
    	strokeOpacity: 0.8,
    	strokeWeight: 2,
    	fillColor: "#00FF00",
    	fillOpacity: 0.35,
    	editable: true,
    	draggable:true
    };
    var polygonOptions = {
		strokeColor: 'blue',
	    strokeOpacity: 0.8,
	    strokeWeight: 2,
	    fillColor: 'blue',
	    fillOpacity: 0.35,
	    editable: true,
	    draggable:true
    };
    // Creates a drawing manager attached to the map that allows the user to
	// draw
    // markers, lines, and shapes.
    drawingManager = new google.maps.drawing.DrawingManager({   
	      drawingControl: true,
	      drawingControlOptions: {
	        position: google.maps.ControlPosition.TOP_CENTER,
	        drawingModes: ['rectangle','circle','polygon'],
	      },
	      label: labels[labelIndex++ % labels.length],
	      rectangleOptions: rectangleOptions,
	      circleOptions: circleOptions,
	      polygonOptions: polygonOptions,
	      map: map
    });
     
    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        var isNotMarker = (e.type != google.maps.drawing.OverlayType.MARKER);
        // Switch back to non-drawing mode after drawing a shape.
        drawingManager.setDrawingMode(null);
        // Add an event listener that selects the newly-drawn shape when the
		// user
        // mouses down on it.
        var newShape = e.overlay;
        newShape.type = e.type;
        newShape.id=new Date().getTime()+'_'+Math.floor(Math.random()*1000);
        shapes[newShape.id]=newShape;
        counterShape+=1;
        google.maps.event.addListener(newShape, 'bounds_changed', function() {        
          deleteInfowindow(infoWindow);
          setSelection(newShape, isNotMarker);
        });
        google.maps.event.addListener(newShape, 'click', function() {          
          setSelection(newShape, isNotMarker);
          deleteInfowindow(infoWindow);          
        });
        setSelection(newShape, isNotMarker);
    });
    // Clear the current selection when the drawing mode is changed, or when the
    // map is clicked.
    google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(map, 'click', clearSelection);
    google.maps.event.addDomListener(document.getElementById('delete-selected-shape'), 'click', deleteSelectedShape);
    
}


function drawExistingMap() {
	var currentAreaMap = JSON.parse(allMapsData[currentMapId]);
	var areaMap = $("<textarea/>").html(currentAreaMap["area_map"]).val();
	console.log(areaMap);
	var areaMapObject = JSON.parse(areaMap);
	var areaType = currentAreaMap["zone_type"];
	drawShape(areaType, areaMapObject);
}


function drawRectangle(path) {
	// Define a rectangle and set its editable property to true.
	var rectangle = new google.maps.Rectangle({
		 strokeColor: "#FF0000",
		 strokeOpacity: 0.8,
		 strokeWeight: 2,
		 fillColor: "#FF0000",
		 fillOpacity: 0.35,
		 map,
		 bounds: path,
		 editable: true,
		 draggable: true
	});
	return rectangle;
}

function drawCircle(path) {
	var cLng = (path["east"]+path["west"])*0.5;
	var cLat = (path["south"]+path["north"])*0.5;
	var radius = Math.abs(path["west"]-path["east"])*0.5*circleScales["14"];
	var circle = new google.maps.Circle({
	      strokeColor: "#00ff00",
	      strokeOpacity: 0.8,
	      strokeWeight: 2,
	      fillColor: "#00FF00",
	      fillOpacity: 0.35,
	      map,
	      center: {lat:cLat,lng:cLng},
	      radius: radius,
	      editable: true,
	      draggable: true
	    });
	return circle;
}

function drawShape(type, path) {
	var shape;
	if ("circle"==type) {
		shape = drawCircle(path);
	}
	if ("rectangle"==type) {
		shape = drawRectangle(path);
	}
	shape.type=type;
	google.maps.event.addListener(shape, 'bounds_changed', function() {        
        deleteInfowindow(infoWindow);
        setSelection(shape, true);
    });
}

function updateSelectedText(shape) {
	$('#zone-type').val(shape.type);
    posstr = "" + selectedShape.position;
    if (typeof selectedShape.position == 'object') {
      posstr = selectedShape.position.toUrlValue();
    }
    
    pathstr = "" + selectedShape.getPath;
    if (typeof selectedShape.getPath == 'function') {
      console.log(selectedShape.getPath())
      $('#area-map').val(selectedShape.getPath())
    }
    if (typeof selectedShape.getPaths == 'function') {
	    console.log(selectedShape.getPaths());
	    $('#area-map').val(selectedShape.getPaths())
    }
    bndstr = "" + selectedShape.getBounds;
    cntstr = "" + selectedShape.getCenter;
    
    if (typeof selectedShape.getBounds == 'function')  {
      var tmpbounds = selectedShape.getBounds();
      console.log(tmpbounds);
      console.log(tmpbounds.toJSON());
      cntstr = "" + tmpbounds.getCenter().toUrlValue();
      bndstr = "NE: " + tmpbounds.getNorthEast().toUrlValue() + ' <br />'+
      "SW: "+ tmpbounds.getSouthWest().toUrlValue()+' <br />'+
      "i = "+labelIndex++ % labels.length ;
   
      var ne = selectedShape.getBounds().getNorthEast();
      var sw = selectedShape.getBounds().getSouthWest();
      /*var contentString = cntstr+bndstr+'<b>Domain-'+counterShape+', Lat/Long: </b><br />' +
		      'Center: '+(ne.lat()+sw.lat())/2 + ' , ' + (ne.lng()+sw.lng())/2 +' <br />'+
		      'North-East-corner: '+ ne.lat() + ' , ' + ne.lng() + ' <br />';
		infoWindow = new google.maps.InfoWindow();
		infoWindow.setContent(contentString);
		infoWindow.setPosition(ne);
		infoWindow.open(map);  */
      $('#area-map').val(JSON.stringify(selectedShape.getBounds().toJSON()));
    }
}

function deleteInfowindow(infoWindow){

    infoWindow.close();
}



function clearSelection() {
    if (selectedShape) {
      if (typeof selectedShape.setEditable == 'function') {
        selectedShape.setEditable(false);
      }      
      selectedShape = null;
    }
}
  
  
function setSelection(shape, isNotMarker) {
    clearSelection();
    selectedShape = shape;
    if (isNotMarker)
      shape.setEditable(true);
    updateSelectedText(shape);
}
  
  
function deleteSelectedShape() {
	deleteInfowindow(infoWindow);
    if (selectedShape) {
        selectedShape.setMap(null);        
    }
    delete shapes[selectedShape.id];
    $('#area-map').val("");
}

function initTrackingMap() {
	
}

function displayDeliveryMode() {
	var type= $('#delivery_mode').val();
	$('.delivery-form').hide();
	$('#delivery-'+type).show();
}


function saveDeliverySetup() {
	var form_data = $('#delivery-info-form').serialize();
	showLoading('#delivery-content');
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: form_data+"&action=bnd_admin_save_delivery",
		  success: function(response){
			  hideLoading('#delivery-content');
			  showAlert(response["message"]);
		  },
		  failure: function(response) {	   
			  hideLoading('#delivery-content');
			  showAlert(response["message"]);
		  }
	});
}
