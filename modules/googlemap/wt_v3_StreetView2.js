function addStreetViewOverlay() {
alert('HELLO_ 1');
	/**+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		First lets set up the Street View Overlay.
		This is alot more complicated than setting one up in v2 of the API.
		We need to add ImageMaps to the map.
		This is done in the following lines of code...
	++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	
	//====================================================================================================================================================
	//	Fist lets create the Traffic ImageMap
	//----------------------------------------------------------------------------------------------------------------------------------------------------
	var traffic = new google.maps.ImageMapType({
		getTileUrl: function(coord, zoom) {
			var X = coord.x % (1 << zoom);  // wrap
			return "http://mt3.google.com/mapstt?" +
				"zoom=" + zoom + "&x=" + X + "&y=" + coord.y + "&client=api";
		},
		tileSize: new google.maps.Size(256, 256),
		isPng: true
	});
	//====================================================================================================================================================
	//	Now add the ImageMapType overlay to the map
	//----------------------------------------------------------------------------------------------------------------------------------------------------
	map.overlayMapTypes.push(traffic);
	map.overlayMapTypes.push(null);
	//====================================================================================================================================================
	
	//====================================================================================================================================================
	//	Now create the StreetView ImageMap
	//----------------------------------------------------------------------------------------------------------------------------------------------------
	//http://cbk0.google.com/cbk?output=overlay&zoom=12&x=2045&y=1361&cb_client=api
	var street = new google.maps.ImageMapType({
		getTileUrl: function(coord, zoom) {
			var X = coord.x % (1 << zoom);  // wrap
			return "http://cbk0.google.com/cbk?output=overlay&" +
				"zoom=" + zoom + "&x=" + X + "&y=" + coord.y + "&cb_client=api";
		},
		tileSize: new google.maps.Size(256, 256),
		isPng: true
	});
	//====================================================================================================================================================
	//  Add the Street view Image Map
	//----------------------------------------------------------------------------------------------------------------------------------------------------
	map.overlayMapTypes.setAt(1, street);
	//====================================================================================================================================================
	
	//====================================================================================================================================================
	//	Open the Streetmap
	//----------------------------------------------------------------------------------------------------------------------------------------------------
	var openStreet = new google.maps.ImageMapType({
		getTileUrl: function(ll, z) {
			var X = ll.x % (1 << z);  // wrap
			return "http://tile.openstreetmap.org/" + z + "/" + X + "/" + ll.y + ".png";
		},
		tileSize: new google.maps.Size(256, 256),
		isPng: true,
		maxZoom: 18,
		name: "OSM",
		alt: "Open Streetmap tiles"
	});
	//====================================================================================================================================================
	
	//====================================================================================================================================================
	//  Create our Geocoder so that we can determine the address status
	//----------------------------------------------------------------------------------------------------------------------------------------------------
	var geocoder = new google.maps.Geocoder();
	//====================================================================================================================================================
	//  Now create the function to Geocode
	//----------------------------------------------------------------------------------------------------------------------------------------------------
	function geocode(opts) {
		function geocodeResult(response, status) {
			if (status == google.maps.GeocoderStatus.OK && response[0]) {
				document.getElementById("result").innerHTML = response[0].formatted_address;
				map.fitBounds(response[0].geometry.viewport);
			} 
			else {
				//alert("Sorry, " + status);
				document.getElementById("result").innerHTML = "?";
			}
		} // trim leading and trailing space with trim capable browsers
		if(opts.address && opts.address.trim) opts.address = opts.address.trim();
		if(opts.address || opts.latLng) geocoder.geocode(opts, geocodeResult); // no empty request
	}
	//====================================================================================================================================================
	
	/**+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		This concludes the set up of the StreetViewOverlay
		Now we can continue with the rest of the StreetView code
	++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	toggleLayer();
	myPano.setVisible(true);
	SVOverlay = true;
	OverlayListeners.push(google.maps.event.addListener(map,"click", function (/*overlay,*/point/*,overlaylatlng*/) {
		latlng = point.latLng;
		if(latlng) {
			//userPoint = google.maps.StreetViewClient.getPanoramaByLocation(latlng);
			SetPano(latlng);
		}
		else if(overlaylatlng) {
			try {
				SetPano(overlaylatlng);
			}
			catch(error) {
				alert("There doesn't appear to be a Street View for that location!");
			}
		}
		else
			return;
	}));
}

function removeStreetViewOverlay() {
alert('HELLO');
	if(SVOverlay)
		SVOverlay.setMap(null);
	for(i = 0; i < OverlayListeners.length; i++) {
		google.maps.event.removeListener(OverlayListeners[i]);
	}
	OverlayListeners = [];
}

function toggleLayer(){
	var state = document.getElementById("pano").style.display;
	if (state == 'block') {
			document.getElementById("pano").style.display = 'none';
	} 
	else {
		document.getElementById("pano").style.display = 'block';
	}
}

function SetStreetViewI() {
	try {
		var POV = {yaw:0,pitch:0, zoom:1};
		//var IntPoint = google.maps.StreetViewClient.getPanoramaByLocation(LatLngI);
		myPano.setPosition(LatLngI);
	}
	catch(error) {
		return;
	}
}

function SetPano(latlng) {
	try {
		//var PanoPosition = google.maps.StreetViewClient.getPanoramaByLocation(latlng);
		myPano.setPosition(latlng);
	}
	catch(error) {
		return;
	}
}

function SetPano2(latlng) {;
	try {
		var myHeading = BreadCrumbArrayHeading[BreadCrumbHeadingC];
		var myPitch = 0;
		var myZoom = 0;
		if(myHeading == 0)
			myHeading = myHeading + 0.00275;
		var myPov = { heading:myHeading, pitch:myPitch, zoom:myZoom };
		//var PanoPosition = google.maps.StreetViewClient.getPanoramaByLocation(latlng);
		myPano.setPosition(latlng);
		myPano.setPov(myPov);
		BreadCrumbHeadingC++;
	}
	catch(error) {
		return;
	}
}

function SetStreetViewReplay() {
	if(StreetViewReplay == true) {
		StreetViewReplay = false;	
	}
	else
		StreetViewReplay = true;
}

function InitMarkersForSV() {
	myPano.setVisible(true);
	for( i = 0; i < ZoneStartFinishMarkersSV.length; i++ ) {
		if(ZoneStartFinishMarkersSV[i]) {
			ZoneStartFinishMarkersSV[i].setMap(myPano);
		}
	}
}

function ClearMarkersForSV() {
	for( i = 0; i < ZoneStartFinishMarkers.length; i++ ) {
		if(ZoneStartFinishMarkersSV[i]) {
			ZoneStartFinishMarkersSV[i].setMap(null);
		}
	}
	removeListernsForSV();
	ZoneStartFinishMarkersSV = [];
}

function ClearMarkerForSV(idStart, idFinish) {
	if((ZoneStartFinishMarkersSV[idStart])) {
		(ZoneStartFinishMarkersSV[idStart]).setMap(null);
		ZoneStartFinishMarkersSV[idStart] =  null;		
	}
	if((ZoneStartFinishMarkersSV[idFinish])) {
		(ZoneStartFinishMarkersSV[idFinish]).setMap(null);
		ZoneStartFinishMarkersSV[idFinish] = null;
	}
}

function addMarkerForSV(ZoneNumber) {
	var svStartTitle, svFinishTitle;
	var svStartPosition, svFinishPosition;
	var svStartIcon, svFinishIcon;
	var svStartIconS, svFinishIconS;

	//var Title = (ZoneStartFinishMarkers[i]).getTitle();
	
	var SVmIDStart = (ZoneNumber * 2);
	var SVmIDFinish = (ZoneNumber * 2) + 1;
	
	//svStartTitle = (ZoneStartFinishMarkers[SVmIDStart]).getTitle();
	//svFinishTitle = (ZoneStartFinishMarkers[SVmIDFinish]).getTitle();
	
	svStartPosition = (ZoneStartFinishMarkers[SVmIDStart]).getPosition();
	svFinishPosition = (ZoneStartFinishMarkers[SVmIDFinish]).getPosition();			
	
	svZoneStartMarkerOptions = { "position": svStartPosition, "icon": StartPoint, "shadow": StartPointS,  "clickable": true, "draggable": true, "title": "" + ZoneNumber + "" };
	svZoneFinishMarkerOptions = { "position": svFinishPosition, "icon": FinishPoint, "shadow": FinishPointS,  "clickable": true, "draggable": true, "title": "" + ZoneNumber + "" };
	
	var StartMarkerSV = new google.maps.Marker(svZoneStartMarkerOptions);
	var FinishMarkerSV = new google.maps.Marker(svZoneFinishMarkerOptions);
	
	ZoneStartFinishMarkersSV[SVmIDStart] = StartMarkerSV;
	ZoneStartFinishMarkersSV[SVmIDFinish] = FinishMarkerSV;
				
	addListernsForSV(SVmIDStart, SVmIDFinish);
	
	(ZoneStartFinishMarkersSV[SVmIDStart]).setMap(myPano);
	(ZoneStartFinishMarkersSV[SVmIDFinish]).setMap(myPano);
}

function addListernsForSV(idStart, idFinish) {
	/**+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	First we create our DragEnd Listeners for Street View
	++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	SVListeners.push(google.maps.event.addListener(ZoneStartFinishMarkersSV[idStart], "dragend", (function(){				//  Creates the Listener for the StartMarker
		ZoneID = (ZoneStartFinishMarkersSV[idStart]).getTitle();													//  Sets the ZoneID to the ID of the Zone set in the title above
		var mIDStart = (ZoneID * 2);
		var mIDFinish = (ZoneID * 2) + 1;
		var Point = (ZoneStartFinishMarkersSV[idStart]).getPosition();												//  Gets the new location of the marker
		var pointb = FinishPoints[mIDFinish];												//  Gets the Point of the Finish Marker
		Distance = Point.distanceFrom(pointb);												//  Gets the Distance from the Finish Marker
		UpdateString = "S," + ZoneID + Point + Distance + pointb;							//  Sets the update string to the new location - S indicates Start
		(ZoneStartFinishMarkers[mIDStart]).setPosition(Point);
	} )));
	
	/**+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	Create the Drag Listeners for our Street View Markers	
	++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	SVListeners.push(google.maps.event.addListener(ZoneStartFinishMarkersSV[idStart], "drag", (function(){				//  Creates the Listener for the StartMarker
		ZoneID = (ZoneStartFinishMarkersSV[idStart]).getTitle();	
		var mIDStart = (ZoneID * 2);
		var Point = (ZoneStartFinishMarkersSV[idStart]).getPosition();											//  Gets the new location of the marker
		(ZoneStartFinishMarkers[mIDStart]).setPosition(Point);
	} )));
	//====================================================================================================================================================
}

function removeListernsForSV() {
	for( var i; i < SVListeners.length; i++)
		if(SVListeners[i])
			google.maps.removeListener(SVListeners[i]);
	SVListeners = [];
}
	
/**
Function no longer used
function addMarkersForSV() {
alert("addMarkersForSV");
	for(i = 0; i < ZoneStartFinishMarkers.length; i++ ) {
		if(ZoneStartFinishMarkers[i]) {
			var svStartTitle, svFinishTitle;
			var svStartPosition, svFinishPosition;
			var svStartIcon, svFinishIcon;
			var svStartIconS, svFinishIconS;
		
			var Title = (ZoneStartFinishMarkers[i]).getTitle();
			
			var SVmIDStart = (Title * 2);
			var SVmIDFinish = (Title * 2) + 1;
			
			svStartTitle = (ZoneStartFinishMarkers[SVmIDStart]).getTitle();
			svFinishTitle = (ZoneStartFinishMarkers[SVmIDFinish]).getTitle();
			
			svStartPosition = (ZoneStartFinishMarkers[SVmIDStart]).getPosition();
			svFinishPosition = (ZoneStartFinishMarkers[SVmIDFinish]).getPosition();			
			
			svZoneStartMarkerOptions = { "position": svStartPosition, "icon": StartPoint, "shadow":StartPointS,  "clickable": true, "draggable": true, "title": "" + svStartTitle + "" };
			svZoneFinishMarkerOptions = { "position": svFinishPosition, "icon": FinishPoint, "shadow":FinishPointS,  "clickable": true, "draggable": true, "title": "" + svFinishTitle + "" };
			
			var StartMarkerSV = new google.maps.Marker(svZoneStartMarkerOptions);
			var FinishMarkerSV = new google.maps.Marker(svZoneFinishMarkerOptions);
			
			ZoneStartFinishMarkersSV[SVmIDStart] = StartMarkerSV;
			ZoneStartFinishMarkersSV[SVmIDFinish] = FinishMarkerSV;
						
			addListernsForSV(SVmIDStart, SVmIDFinish);
			
			(ZoneStartFinishMarkersSV[SVmIDStart]).setMap(myPano);
			(ZoneStartFinishMarkersSV[SVmIDFinish]).setMap(myPano);
			
			i++;
		}
	}
}
*/