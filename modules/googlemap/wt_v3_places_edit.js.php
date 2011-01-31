<?php

?>

<head>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="modules/googlemap/wt_v3_places_edit_overlays.js.php"></script>

<script type="text/javascript">

var map;
var marker;
var zoom;
var pl_name = "<?php echo $place_name; ?>";
var pl_lati = "<?php echo $place_lati; ?>";
var pl_long = "<?php echo $place_long; ?>";
var pl_zoom = <?php echo $zoomfactor; ?>;
var latlng = new google.maps.LatLng(pl_lati, pl_long);
var polygon1;
var geocoder = new google.maps.Geocoder();

	function geocodePosition(pos) {
  		geocoder.geocode({
    		latLng: pos
  		}, function(responses) {
    		if (responses && responses.length > 0) {
      			updateMarkerAddress(responses[0].formatted_address);
    		} else {
      			updateMarkerAddress('Cannot determine address at this location.');
    		}
  		});
	}

	function updateMap(event) {
		var point;
		var latitude;
		var longitude;
		var i;
		
		zoom = parseFloat(document.editplaces.NEW_ZOOM_FACTOR.value);

		document.editplaces.save1.disabled = "";
		document.editplaces.save2.disabled = "";
		zoom = parseInt(document.editplaces.NEW_ZOOM_FACTOR.value);

		prec = 20;
		for (i=0;i<document.editplaces.NEW_PRECISION.length;i++) {
			if (document.editplaces.NEW_PRECISION[i].checked) {
				prec = document.editplaces.NEW_PRECISION[i].value;
			}
		}

		if ((document.editplaces.NEW_PLACE_LATI.value == "") ||
			(document.editplaces.NEW_PLACE_LONG.value == "")) {
			latitude = parseFloat(document.editplaces.parent_lati.value).toFixed(prec);
			longitude = parseFloat(document.editplaces.parent_long.value).toFixed(prec);
			point = new google.maps.LatLng(latitude, longitude);
		} else {
			latitude = parseFloat(document.editplaces.NEW_PLACE_LATI.value).toFixed(prec);
			longitude = parseFloat(document.editplaces.NEW_PLACE_LONG.value).toFixed(prec);
			document.editplaces.NEW_PLACE_LATI.value = latitude;
			document.editplaces.NEW_PLACE_LONG.value = longitude;

		  	if (event == 'flag_drag') {			  	
				if (longitude < 0.0 ) {
					longitude = longitude * -1;
					document.editplaces.NEW_PLACE_LONG.value = longitude;
					document.editplaces.LONG_CONTROL.value = "PL_W";	
				} else {
					longitude = longitude ;
					document.editplaces.NEW_PLACE_LONG.value = longitude;
					document.editplaces.LONG_CONTROL.value = "PL_E";		
				}			
				if (latitude < 0.0 ) {
					latitude = latitude * -1;
					document.editplaces.NEW_PLACE_LATI.value = latitude;
					document.editplaces.LATI_CONTROL.value = "PL_S";	
				} else {
					latitude = latitude ;
					document.editplaces.NEW_PLACE_LATI.value = latitude;
					document.editplaces.LATI_CONTROL.value = "PL_N";		
				}
			
				if (document.editplaces.LATI_CONTROL.value == "PL_S") {
					latitude = latitude * -1;
				}
				if (document.editplaces.LONG_CONTROL.value == "PL_W") {
					longitude = longitude * -1;
				}
				point = new google.maps.LatLng(latitude, longitude);				
		  	} else {		  	
				if (latitude < 0.0) {
					latitude = latitude * -1;
					document.editplaces.NEW_PLACE_LATI.value = latitude;
				}
				if (longitude < 0.0) {
					longitude = longitude * -1;
					document.editplaces.NEW_PLACE_LONG.value = longitude;
				}
				if (document.editplaces.LATI_CONTROL.value == "PL_S") {
					latitude = latitude * -1;
				}
				if (document.editplaces.LONG_CONTROL.value == "PL_W") {
					longitude = longitude * -1;
				}
				point = new google.maps.LatLng(latitude, longitude);		  	
		  	}
		}
		
		map.setCenter(point);
		map.setZoom(zoom);
		marker.setPosition(point);

	}
	
// END of To be worked through for v3 ==============================================================



// The HomeControl returns user to original position and style =================
function HomeControl(controlDiv, map) {
  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control from the edge of the map
  controlDiv.style.paddingTop = '5px';
  controlDiv.style.paddingRight = '0px';

  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.color = 'black';
  controlUI.style.borderColor = 'black';
  controlUI.style.borderColor = 'black';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '2px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to set the map to Home';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('DIV');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.paddingLeft = '15px';
  controlText.style.paddingRight = '15px';
  controlText.innerHTML = '<b>Home<\/b>';
  controlUI.appendChild(controlText);

  // Setup the click event listeners: simply set the map to original LatLng
  google.maps.event.addDomListener(controlUI, 'click', function() {
    map.setCenter(latlng), 
    map.setZoom(pl_zoom), 
    map.setMapTypeId(google.maps.MapTypeId.ROADMAP)
  });
}



function initialize() {

	// Define map
	var myOptions = {
		zoom: pl_zoom,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,					// ROADMAP, SATELLITE, HYBRID, TERRAIN
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU 	// DEFAULT, DROPDOWN_MENU, HORIZONTAL_BAR
		},
      	navigationControlOptions: {
   			position: google.maps.ControlPosition.TOP_RIGHT,		// BOTTOM, BOTTOM_LEFT, LEFT, TOP, etc
   			style: google.maps.NavigationControlStyle.SMALL			// ANDROID, DEFAULT, SMALL, ZOOM_PAN
      	},
      	streetViewControl: false,									// Show Pegman or not
      	scrollwheel: false     		
	};
	
	map = new google.maps.Map(document.getElementById("map_pane"), myOptions);

	// *** === NOTE *** This function creates the UK country overlays ==================
	overlays();
	// === Above function is located in modules/googlemap/wt_v3_placeOverlays.js.php ===

  	// Create the DIV to hold the control and call HomeControl() passing in this DIV. --
  	var homeControlDiv = document.createElement('DIV');
  	var homeControl = new HomeControl(homeControlDiv, map);
  	homeControlDiv.index = 1;
  	map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);
	// ---------------------------------------------------------------------------------
	
	google.maps.event.addListener(map, 'zoom_changed', function() {
		document.editplaces.NEW_ZOOM_FACTOR.value = map.zoom;        
  	});	
	
	var infowindow = new google.maps.InfoWindow({ 
		content: '<div id="gmedit_iw" style="width:200px; height: 45px;" class="iwstyle"><br />'+pl_name+'</div>'
	});

	// Create the Marker
	<?php 
	if ($level < 2 && $place_icon != '') {	
  		echo "var image = new google.maps.MarkerImage('$place_icon',";
      		echo 'new google.maps.Size(25, 15),';	// Image size
      		echo 'new google.maps.Point(0, 0),';	// Image origin
      		echo 'new google.maps.Point(0, 44)';	// Image anchor
    	echo ');';
    	echo 'var iconShadow = new google.maps.MarkerImage("modules/googlemap/images/flag_shadow.png",';
    	  	echo 'new google.maps.Size(35, 45),';	// Shadow size
    	  	echo 'new google.maps.Point(0,0),';		// Shadow origin
    	  	echo 'new google.maps.Point(1, 45)';	// Shadow anchor is base of flagpole
    	echo ');';
		echo 'marker = new google.maps.Marker({';
			echo 'icon: image,';
			echo 'shadow: iconShadow,';
			echo 'position: latlng,';
			echo 'map: map,';
			echo 'title: pl_name,';
			echo 'draggable: true';
		echo '});';		
	} else { 
		echo 'marker = new google.maps.Marker({';
			echo 'position: latlng,';
			echo 'map: map,';
			echo 'title: pl_name,';
			echo 'draggable: true';
		echo '});';
	}
	?>

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map, marker);
	});
	
	prec = 20;
	for (i=0;i<document.editplaces.NEW_PRECISION.length;i++) {
		if (document.editplaces.NEW_PRECISION[i].checked) {
			prec = document.editplaces.NEW_PRECISION[i].value;
		}
	}	
  	google.maps.event.addListener(marker, 'drag', function() {
    	pos1 = marker.getPosition();
        document.getElementById('NEW_PLACE_LATI').value = parseFloat(pos1.lat()).toFixed(prec); 
        document.getElementById('NEW_PLACE_LONG').value = parseFloat(pos1.lng()).toFixed(prec);
  	});	
  	google.maps.event.addListener(marker, 'dragend', function() {
    	geocodePosition(marker.getPosition());
    	pos2 = marker.getPosition();
    	old_lati = document.getElementById('NEW_PLACE_LATI').value;
    	old_long = document.getElementById('NEW_PLACE_LONG').value;
        document.getElementById('NEW_PLACE_LATI').value = parseFloat(pos2.lat()).toFixed(prec);
        document.getElementById('NEW_PLACE_LONG').value = parseFloat(pos2.lng()).toFixed(prec);
        updateMap('flag_drag');
  	});
	
}

function change_icon() {
	window.open('module.php?mod=googlemap&mod_action=flags&countrySelected=<?php echo $selected_country; ?>', '_blank', 'top=50, left=50, width=600, height=500, resizable=1, scrollbars=1');
return false;
}

function remove_icon() {
	document.editplaces.icon.value = "";
	document.getElementById('flagsDiv').innerHTML = "<a href=\"javascript:;\" onclick=\"change_icon();return false;\"><?php echo WT_I18N::translate('Change flag'); ?></a>";
}

</script>

</head>


<body onload="initialize()" >

<table><tr><td align="center">

</td></tr></table>
</body>

<?php

?>
