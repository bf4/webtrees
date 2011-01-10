<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<div id="mapcanva" style="margin-top:26px; width:520px; height:200px;"></div>
<div id="warning" style="margin-top:-115px; width:520px; height:100px;"></div>
	<h5 style="font: 10px verdana; color:red;">
		<b>No Streetview coordinates are saved yet.</b><br />
		a. If no Streetview is displayed below, drag the "Panda" Marker above to a "blue" Street on the map.<br />
		b. When the Streetview is displayed, adjust as necessary to enable the required view.<br />
		c. When the required view is displayed, click the button "Save View".
	</h5>
</div>

<?php
// Note the api key is extracted for the url for now to avoid errors  --- these following line will disappear when v3 is employed --
$ggmkey = $_GET['ggmkey'];
?>
	
<div id="streetcanva" style="background:#cccccc; margin-top:-15px; width:520px; height:405px;"></div>
<script type="text/javascript" src="http://maps.google.com/maps?file=api&hl=en&v=2&key=<?php echo $ggmkey; ?>&sensor=false"></script>
<!-- <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script> -->
<script type="text/javascript">
    // Instead of handling events directly in the tags
    // attributes (ie. <body onload="...">), I prefer
    // listening to the events programmatically like:
    GEvent.addDomListener(window, 'load', Initialize);
    GEvent.addDomListener(window, 'unload', GUnload);
    
	var qsParm = new Array();
	function qs() {
		var query = window.location.search.substring(1);
		var parms = query.split('&');
		for (var i=0; i<parms.length; i++) {
			var pos = parms[i].indexOf('=');
			if (pos > 0) {
				var key = parms[i].substring(0,pos);
				var val = parms[i].substring(pos+1);
				qsParm[key] = val;
			}
		}
	} 	
	qsParm['x'] = null;
	qsParm['y'] = null;
	qs();
	
	var x = qsParm['x'];
    var y = qsParm['y'];
    
    var map;    // This will be our map instance
    var center; // This is a GLatLng representing the initial position of the map
    var marker; // The one and only draggable marker
    var pano;   // The street view object
    var svOverlay;	//The Map 'blue' Streetview overlay
    var bearing; //parent.document.getElementById('sv_bearText').value;

    // This function is called when the page has loaded.
    // see above, the GEvent.addDomListener(...) call.
    // What it does, it simply creates the map then creates
    // the street view.
    function Initialize() {
        InitializeMap();
        InitializeStreetView();
    }

    // This function is used to create the map.
    // It is called by Initialize() on the page load.
    function InitializeMap() {
        // Create a map instance
        map = new GMap2(document.getElementById('mapcanva'));
        // Create the center point the map will be initialized with
        center = new GLatLng(y, x);
        
        // Our draggable "panda" marker
        var icon = new GIcon();
        var imageNum = Math.round(parent.document.getElementById('sv_bearText').value/22.5) % 16;
        var imageUrl = "http://mk.mapchannels.com/panda-" + imageNum + ".png";            
        icon.image = imageUrl;
        icon.iconSize = new GSize(49,52);
        icon.iconAnchor =  new GPoint(25,36);
        marker = new GMarker(center, {"icon":icon, "draggable":true});

      	svOverlay = new GStreetviewOverlay();
       
        // Initialize the map
        map.setCenter(center, 15);
        map.setUIToDefault();
        map.addOverlay(marker);
      	map.addOverlay(svOverlay);

        // Listen to the 'dragend' event of the marker.
        // Each time the user will drag the marker, the
        // function 'OnMarkerDragged' will be called receiving
        // the longitude and latitude of the marker's new
        // position
        GEvent.addListener(marker, 'dragend', OnMarkerDragged);
    }
    
    function updateMarker() {            
        // update marker image
        var imageNum = Math.round(bearing/22.5) % 16;
        var imageUrl = "http://mk.mapchannels.com/panda-" + imageNum + ".png";                
        marker.setImage(imageUrl);
          
        if (!dragging) {        
            // update marker location
            marker.setPoint(latlng);                
        }
    }

    // This function is used to create the street view viewport.
    // It is called by Initialize() on the page load, after the map.
    function InitializeStreetView() {
        // This sets some options
        var options = { latlng: center, pov: { yaw: 0, pitch: 5} };
        pano = new GStreetviewPanorama(document.getElementById('streetcanva'), options);

        // Listen to the 'initialized' event of the street view.
        // This event occurs every time a new panorama is initialized
        // in the street view viewport.
        // 'OnStreetViewChanged' will be called and will receive a
        // 'GStreetviewLocation' object so we can have the location
        // of the current panorama.
        GEvent.addListener(pano, 'initialized', OnStreetViewChanged);
             
        GEvent.addListener(pano, 'yawchanged', function(newyaw) {
        	parent.document.getElementById('sv_bearText').value = newyaw+"\u00B0";
        	bearing = parseFloat(newyaw);
            updateMarker();
        });
		GEvent.addListener(pano, 'pitchchanged', function(newpitch) {
        	parent.document.getElementById('sv_elevText').value = newpitch+"\u00B0";
		});
		GEvent.addListener(pano, 'zoomchanged', function(newzoom) {
        	parent.document.getElementById('sv_zoomText').value = newzoom;
		});

        
        // Error handling, in case the user don't have the flash player
        GEvent.addListener(pano, 'error', OnStreetViewError);
    }

    // This function is called when the marker has been dragged
    // (See InitializeMap())
    function OnMarkerDragged(loc) {
        // The map's marker have a new location, synchronize street view also
        pano.setLocationAndPOV(loc);
        // Re-center the map according to the marker's position
        map.panTo(loc);
    }

    // This function is called when the street view has changed
    // (See InitializeStreetView())
    function OnStreetViewChanged(loc) {
        // Update the marker's position according to the street view position
        marker.setPoint(loc.latlng);
        // set the database input fields on parent page.
        parent.document.getElementById('sv_latiText').value = loc.lat+"\u00B0";
        parent.document.getElementById('sv_longText').value = loc.lng+"\u00B0";
        // Re-center the map according to the marker's position
        map.panTo(loc.latlng);
    }

    // Error handling function, in case the user doesn't have the
    // flash player installed
    function OnStreetViewError(errCode) {
        if (errCode == 603) {
            alert('flash is required');
        }
    }
</script>
</html>
