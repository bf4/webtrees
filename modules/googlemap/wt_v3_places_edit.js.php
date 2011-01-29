<?php

?>

<head>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="modules/googlemap/wt_v3_places_edit_overlays.js.php"></script>

<script type="text/javascript">

var map;
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
	
	google.maps.event.addListener(map, 'maptypeid_changed', function() {	
		// alert(map.mapTypeId);
		// overlays();
  	});
  	
	// === This function creates the UK country overlays ===================
	overlays();
	// === Above located in modules/googlemap/wt_v3_placeOverlays.js.php ===

  	// Create the DIV to hold the control and call HomeControl() passing in this DIV. ======
  	var homeControlDiv = document.createElement('DIV');
  	var homeControl = new HomeControl(homeControlDiv, map);
  	homeControlDiv.index = 1;
  	map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);


	var infowindow = new google.maps.InfoWindow({ 
		content: '<div id="gmedit_iw" style="width:200px; height: 45px;" class="iwstyle"><br />'+pl_name+'</div>'
	});

	<?php if ($level==0) { ?>
  		var image = new google.maps.MarkerImage("<?php echo $place_icon; ?>",
      		new google.maps.Size(25, 15),
      		new google.maps.Point(0, 0),
      		new google.maps.Point(0, 44)
    	);    
		<?php
    	echo 'var iconShadow = new google.maps.MarkerImage("modules/googlemap/images/flag_shadow.png",';
    	  	echo 'new google.maps.Size(35, 45),';	// Shadow size
    	  	echo 'new google.maps.Point(0,0),';		// Shadow origin
    	  	echo 'new google.maps.Point(1, 45)';	// Shadow anchor is base of flagpole
    	echo ');';
    	?>

		var marker = new google.maps.Marker({
			icon: image,
			shadow: iconShadow,
			position: latlng,
			map: map,
			title: pl_name,
			draggable: true
		});
		
	<?php } else { ?>
		var marker = new google.maps.Marker({
			position: latlng,
			map: map,
			title: pl_name,
			draggable: true
		});
	<?php } ?>

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map, marker);
	});
	
  	google.maps.event.addListener(marker, 'drag', function() {
    	pos1 = marker.getPosition();
        document.getElementById('NEW_PLACE_LATI').value = pos1.lat();
        document.getElementById('NEW_PLACE_LONG').value = pos1.lng();
  	});
	
  	google.maps.event.addListener(marker, 'dragend', function() {
    	geocodePosition(marker.getPosition());
    	pos2 = marker.getPosition();
        document.getElementById('NEW_PLACE_LATI').value = pos2.lat();
        document.getElementById('NEW_PLACE_LONG').value = pos2.lng();
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
