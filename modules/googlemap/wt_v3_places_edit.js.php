<?php

?>

<head>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">

var map3;
var pl_name = "<?php echo $place_name; ?>";
var pl_lati = "<?php echo $place_lati; ?>";
var pl_long = "<?php echo $place_long; ?>";
var pl_zoom = <?php echo $zoomfactor; ?>;
var latlng = new google.maps.LatLng(pl_lati, pl_long);


/**
 * The HomeControl adds a control to the map that simply
 * returns the user to Chicago. This constructor takes
 * the control DIV as an argument.
 */ 

function HomeControl(controlDiv, map3) {
  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map
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
    map3.setCenter(latlng), 
    map3.setZoom(pl_zoom), 
    map3.setMapTypeId(google.maps.MapTypeId.ROADMAP)
  });
}

function initialize3() {
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
	map3 = new google.maps.Map(document.getElementById("map_pane"), myOptions);
	

  	// Create the DIV to hold the control and
  	// call the HomeControl() constructor passing
  	// in this DIV.
  	var homeControlDiv = document.createElement('DIV');
  	var homeControl = new HomeControl(homeControlDiv, map3);
  	homeControlDiv.index = 1;
  	map3.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);


	var infowindow = new google.maps.InfoWindow({ 
		content: '<div id="gmedit_iw" style="width:200px; height: 45px;" class="iwstyle"><br />'+pl_name+'</div>'
	});

	var marker3 = new google.maps.Marker({
		position: latlng,
		map: map3,
		title: pl_name,
		draggable: true
	});

	google.maps.event.addListener(marker3, 'click', function() {
		infowindow.open(map3, marker3);
	});
	
}



</script>

</head>


<body onload="initialize3()" >

<table><tr><td align="center">

</td></tr></table>
</body>

<?php

?>
