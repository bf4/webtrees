<?php
// DEBUG ===================
/*
print_r($gmarks[1]);
echo '<br /><br />';
print_r($gmarks[2]);
echo '<br /><br />';
echo ($gmarks[3]['fact']);
echo '<br /><br />';
print_r($gmarks[4]);
echo '<br /><br />';
print_r($gmarks[5]);
echo '<br /><br />';
print_r($gmarks[6]);
echo '<br /><br />';
print_r($gmarks[7]);
echo '<br /><br />';
print_r($gmarks[8]);
echo '<br /><br />';
print_r($gmarks[9]);
echo '<br /><br />';
print_r($gmarks[10]);
*/
// DEBUG END ===============
?>

<script type="text/javascript">

//<![CDATA[
    
    // this variable will collect the html which will eventually be placed in the side_bar 
    var side_bar_html = ""; 
	var map_center = new google.maps.LatLng(53.8403,-2.0377);	
    var gmarkers = [];
    var gicons = [];
    var map = null;
    
    var markers = [];

	var infowindow = new google.maps.InfoWindow( { 
    	// size: new google.maps.Size(150,50),
    	maxWidth: 600
  	});
  	
  	<?php 
	echo 'gicons["red"] = new google.maps.MarkerImage("http://maps.google.com/mapfiles/marker.png",';
      	// This marker is 20 pixels wide by 34 pixels tall.
    	echo 'new google.maps.Size(20, 34),';
      	// The origin for this image is 0,0.
    	echo 'new google.maps.Point(0,0),';
      	// The anchor for this image is at 9,34.
    	echo 'new google.maps.Point(9, 34));';
      	
  	// Marker sizes are expressed as a Size of X,Y
  	// where the origin of the image (0,0) is located
  	// in the top left of the image.
 
  	// Origins, anchor positions and coordinates of the marker
  	// increase in the X direction to the right and in
  	// the Y direction down.

  	echo 'var iconImage = new google.maps.MarkerImage("http://maps.google.com/mapfiles/marker.png",';
      	// This marker is 20 pixels wide by 34 pixels tall.
    	echo 'new google.maps.Size(20, 34),';
      	// The origin for this image is 0,0.
    	echo 'new google.maps.Point(0,0),';
      	// The anchor for this image is at 9,34.
    	echo 'new google.maps.Point(9, 34));';
    
  	echo 'var iconShadow = new google.maps.MarkerImage("http://www.google.com/mapfiles/shadow50.png",';
      	// The shadow image is larger in the horizontal dimension
      	// while the position and offset are the same as for the main image.
      	echo 'new google.maps.Size(37, 34),';
      	echo 'new google.maps.Point(0,0),';
      	echo 'new google.maps.Point(9, 34));';
    // Shapes define the clickable region of the icon.
  	// The type defines an HTML &lt;area&gt; element 'poly' which
    // traces out a polygon as a series of X,Y points. The final
    // coordinate closes the poly by connecting to the first coordinate.
  	echo 'var iconShape = {';
      	echo 'coord: [9,0,6,1,4,2,2,4,0,8,0,12,1,14,2,16,5,19,7,23,8,26,9,30,9,34,11,34,11,30,12,26,13,24,14,21,16,18,18,16,20,12,20,8,18,4,16,2,15,1,13,0],';
      	echo 'type: "poly"';
  	echo '};';  	
  	?>

	function getMarkerImage(iconColor) {
   		if ((typeof(iconColor)=="undefined") || (iconColor==null)) { 
      		iconColor = "red"; 
   		}
   		if (!gicons[iconColor]) {
      		gicons[iconColor] = new google.maps.MarkerImage("http://maps.google.com/mapfiles/marker"+ iconColor +".png",
      		// This marker is 20 pixels wide by 34 pixels tall.
      		new google.maps.Size(20, 34),
      		// The origin for this image is 0,0.
      		new google.maps.Point(0,0),
      		// The anchor for this image is at 6,20.
      		new google.maps.Point(9, 34));
   		} 
   		return gicons[iconColor];
	}

	function category2color(category) {
   		var color = "red";
   		switch(category) {
    	 	case "theatre": color = "";
    	    	break;
    	 	case "golf":    color = "_green";
    	    	break;
    	 	case "info":    color = "_yellow";
    	    	break;
    	 	default:   color = "";
    	    	break;
   		}
   		return color;
	}

    gicons["theatre"] = getMarkerImage(category2color("theatre"));
    gicons["golf"] = getMarkerImage(category2color("golf"));
    gicons["info"] = getMarkerImage(category2color("info"));

	// A function to create the marker and set up the event window
	function createMarker(i, latlng, event, html, category, placed, index, tab, address) {

    	var contentString = html;
    	var marker = new google.maps.Marker({
        	position: latlng,
        	icon: gicons[category],
        	shadow: iconShadow,
        	map: map,
        	title: address,
        	zIndex: Math.round(latlng.lat()*-100000)<<5
        });
        
        // === Store the tab, category and event info as marker properties ===
        //marker.myindex = index;
        marker.mytab = tab;
        //marker.myplaced = placed;
        marker.mycategory = category;                                 
        marker.myevent = event;
        marker.myaddress = address;
        gmarkers.push(marker);
		
		// == Open infowindow when marker is clicked ==
    	google.maps.event.addListener(marker, 'click', function() {
    		infowindow.close();
        	infowindow.setContent(contentString);
        	infowindow.open(map, marker);       	
			// Use jquery for tabs -------- 
			jQuery("#gmtabs").tabs();
			jQuery("#gmtabs").tabs('select', tab); 	
			//jQuery("#gmtabs div:last").show();
			return false;
        	// == rebuild the side bar ==
        //	makeSidebar(i);
      	
    	});

	}

    // == shows all markers of a particular category, and ensures the checkbox is checked ==
    function show(category) {
        for (var i=0; i<gmarkers.length; i++) {
          	if (gmarkers[i].mycategory == category) {
            	gmarkers[i].setVisible(true);
          	}
        }
        // == check the checkbox ==
        document.getElementById(category+"box").checked = true;
        // == close any info window for clarity
        infowindow.close();
    }

    // == hides all markers of a particular category, and ensures the checkbox is cleared ==
    function hide(category) {
        for (var i=0; i<gmarkers.length; i++) {
          	if (gmarkers[i].mycategory == category) {
            	gmarkers[i].setVisible(false);
          	}
        }
        // == clear the checkbox ==
        document.getElementById(category+"box").checked = false;
        // == close the info window, in case its open on a marker that we just hid
        infowindow.close();
    }

    // == a checkbox has been clicked ==
    function boxclick(box,category) {
        if (box.checked) {
          	show(category);
        } else {
          	hide(category);
        }
        // == rebuild the side bar ==
       makeSidebar();
    }
    

	// == Opens Marker infowindow when corresponding Sidebar item is clicked ==
    function myclick(i, index, tab) {
   
		infowindow.close();
        google.maps.event.trigger(gmarkers[i],"click", tab);
        // google.maps.event.trigger(markers[index], "click", tab);

        // Still trying to get this to work ***************
	//	jQuery("#gmtabs").tabs();
	//	jQuery("#gmtabs").tabs('tabs', tab); // switch to clicked tab
	//	jQuery("#gmtabs div:last").show();
		
		// == rebuild the side bar ==
        //makeSidebar(i);
    }
    
    // == rebuild sidebar (hidden item) when any marker's infowindow is closed ==
    google.maps.event.addListener(infowindow, 'closeclick', function() {
       	makeSidebar();
    });

    // == rebuilds the sidebar (hidden item) to match the markers currently displayed ==
    function makeSidebar(x) {
        var html = "";
        //var tab = gmarkers.mytab;
        for (var i=0; i<gmarkers.length; i++) {
          	if (gmarkers[i].getVisible()) {
          		// if (x==gmarkers[i].myindex) {
          		if (x==i ) {
            		html += '<a style="text-decoration:none; color:black; background:white; " href="javascript:myclick('+i+', '+gmarkers[i].mytab+')">' + gmarkers[i].myevent + '<\/a><br>';         		
          		} else if (gmarkers[i].mycategory=='theatre') {
            		html += '<a style="text-decoration:none; color:red;" href="javascript:myclick('+i+', '+gmarkers[i].mytab+')">' + gmarkers[i].myevent + '<\/a><br>';
          		} else if (gmarkers[i].mycategory=='golf') {
            		html += '<a style="text-decoration:none; color:green;" href="javascript:myclick('+i+', '+gmarkers[i].mytab+')">' + gmarkers[i].myevent + '<\/a><br>';
          		} else if (gmarkers[i].mycategory=='info') {
            		html += '<a style="text-decoration:none; color:yellow;" href="javascript:myclick('+i+', '+gmarkers[i].mytab+')">' + gmarkers[i].myevent + '<\/a><br>';
            	} else {
            		html += '<a style="text-decoration:none; color:black;" href="javascript:myclick('+i+', '+gmarkers[i].mytab+')">'+ gmarkers[i].myevent + '<\/a><br>';            	
            	}
          	}
        }
        document.getElementById("side_bar").innerHTML = html;
        x=null;
    }
      
	// Home control ----------------------------------------------------------------
	/* The HomeControl adds a control to the map that simply
	 * returns the user to the original map position (map_center).
	 * This constructor takes the control DIV as an argument.
	 */ 
	function HomeControl(controlDiv, map) {
  		// Set CSS styles for the DIV containing the control
  		// Setting padding to 5 px will offset the control
  		// from the edge of the map
  		controlDiv.style.paddingTop = '5px';
  		controlDiv.style.paddingRight = '0px';

  		// Set CSS for the control border
  		var controlUI = document.createElement('DIV');
  		controlUI.style.backgroundColor = 'white';
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
  		controlText.innerHTML = '<b>Redraw Map<\/b>';
  		controlUI.appendChild(controlText);

  		// Setup the click event listeners: simply set the map to original LatLng
  		google.maps.event.addDomListener(controlUI, 'click', function() {
    		//map.setCenter(map_center), 
    		//map.setZoom(7), 
    		//map.setMapTypeId(google.maps.MapTypeId.TERRAIN)
    		
    		loadMap();
  		});
	}

  	function loadMap() {
  		// Create the map and mapOptions
		var mapOptions = {
			zoom: 7,
			center: map_center,
			mapTypeId: google.maps.MapTypeId.TERRAIN,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.DROPDOWN_MENU // DEFAULT, DROPDOWN_MENU, HORIZONTAL_BAR
			},
			navigationControl: true,
      		navigationControlOptions: {
   				position: google.maps.ControlPosition.TOP_RIGHT,		// BOTTOM, BOTTOM_LEFT, LEFT, TOP, etc
   				style: google.maps.NavigationControlStyle.SMALL	// ANDROID, DEFAULT, SMALL, ZOOM_PAN
      		},
      		scrollwheel: false
		};
		//map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		map = new google.maps.Map(document.getElementById("map_pane"), mapOptions);

		// Close any infowindow when map is clicked
    	google.maps.event.addListener(map, 'click', function() {
        	infowindow.close();
        	// == rebuild sidebar (hidden item) ==
        	makeSidebar();       	
    	});
    	
  		// Create the Home DIV and call the HomeControl() constructor in this DIV.
  		var homeControlDiv = document.createElement('DIV');
  		var homeControl = new HomeControl(homeControlDiv, map);
  		homeControlDiv.index = 1;
  		map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);

  		// Add the markers to the map from the $gmarks array 		
  		var locations = [
			<?php foreach($gmarks as $gmark) { ?>
    			[
					"<?php echo $gmark['fact'].''; ?>", 
    				"<?php echo $gmark['lati']; ?>", 
    				"<?php echo $gmark['lng']; ?>", 
    				"<?php $date=new GedcomDate($gmark['date']); echo addslashes($date->Display(true)); ?>", 
    				"<?php if (!empty($gmark['info'])) 	{ echo ''.$gmark['info']; } else { echo NULL; } ?>", 
    				"<?php if (!empty($gmark['name'])) { $person=Person::getInstance($gmark['name']); if ($person) { echo '<a href=\"', $person->getLinkUrl(), '\">', $person->canDisplayName() ? PrintReady(addcslashes($person->getFullName(), '"')) : i18n::translate('Private'), '<\/a>'; } } ?>", 
    				"<?php if (preg_match('/2 PLAC (.*)/', $gmark['placerec']) == 0) { print_address_structure_map($gmark['placerec'], 1); } else { echo preg_replace('/\"/', '\\\"', print_fact_place_map($gmark['placerec'])); } ?>",
					"<?php echo $gmark['index'].''; ?>", 
					"<?php echo $gmark['tabindex'].''; ?>",
					"<?php echo $gmark['placed'].''; ?>",
					// extra printable items ===
					"<?php echo strip_tags(preg_replace('/\"/', '\\\"', print_fact_place_map($gmark['placerec']))); ?>",
					"<?php if (!empty($gmark['name'])) { $person=Person::getInstance($gmark['name']); if ($person) { echo $person->canDisplayName() ? PrintReady(addcslashes($person->getFullName(), '"')) : i18n::translate('Private'); } } ?>"    				
    			], 
    		<?php } ?> 
    	];
    	
    	var bounds = new google.maps.LatLngBounds ();


    	var np = new Array();
		var numtabs = new Array();
		var npo = new Array();
		for (var p = 0; p < locations.length; p++) {
			np[p] = ''+p+'';		
			numtabs[p] = 0;
			npo[p] = new Array;
    	    for (var i = 0; i < locations.length; i++) {        
				if (jQuery.inArray(np[p], locations[i][7])==0) {
					npo[p][numtabs[p]] = i;
					numtabs[p]++;
				}
    	    }
    	} 
    
    	// Loop through all location markers -------    	
        for (var i = 0; i < locations.length; i++) {       	
        	// obtain the attributes of each marker
        	var event = locations[i][0];					// Event or Fact
        	var lat = locations[i][1];						// Latitude
        	var lng = locations[i][2];						// Longitude
        	var date = locations[i][3];						// Date of event or fact
        	var info = locations[i][4];						// info on occupation, or 
       		var name = locations[i][5];						// Persons name
       		var address = locations[i][6];					// Address of event or fact
      		var index = locations[i][7];					// index
       		var tab = locations[i][8];						// tab index
       		var placed = locations[i][9];					// Yes indicates multitab item
       		var addr2 = locations[i][10];					// printable address for marker title
       		var name2 = locations[i][11];					// printable name for marker title
       		var point = new google.maps.LatLng(lat,lng);	// Latitude, Longitude
       		var category = "theatre";						// Category for future pedigree map use etc

       		// === Use this variable if a multitab marker ===
   			// If a fact with info or a persons name ---  			

       	//	if (locations[i][4] || locations[i][5] ) {
     		
       			var event_item ="";
       			var event_tab ="";       			
       			var tabcontid = ""; 

			for (var n = 0; n < locations.length; n++) {
       			if (i==npo[n][0] || i==npo[n][1] || i==npo[n][2] || i==npo[n][3]) {
       				for (var x=0; x<numtabs[n]; x++) { 
       					tabcontid=npo[n][x];
       					event_item 	+= 	[ '<li><a href="#gtab'+x+'" ><span>'+locations[tabcontid][0]+'<\/span><\/a><\/li>' ]; 
       					event_tab 	+=	[ '<div id="gtab'+x+'"><p>'+locations[tabcontid][4]+''+locations[tabcontid][5]+'<br />'+locations[tabcontid][6]+'<br />'+locations[tabcontid][3]+'<br /><\/p><\/div>' ]; 
   					}
     			}
     		}
    			
   				var multitabs = [  				
 				 	'<div id="gmtabs">',
						'<ul>',						
							event_item,    							
  						'<\/ul>',
    	    				event_tab,
  	  				'<\/div>'
   				].join('');

		/*		
   		  	} else { 

      			var multitabs = [
   	 			 	'<div id="gmtabs">',   	 			 	
   	  					'<ul>',
   	   						'<li><a href="#tab-1"><span>'+locations[i][0]+'<\/span><\/a><\/li>',
 						'<\/ul>',   	  						  					
 						'<div id="tab-1">',
    	    				'<p>',
    	   						locations[i][4]+''+locations[i][5]+'<br />'+locations[i][6]+'<br />'+locations[i][3]+'<br />',
     						'<\/p>',
     					'<\/div>',	  			  				    	  				    	  				
    	  			'<\/div>'
    			].join('');
    			
   		  	}
		*/

			   			
    			// === Use this variable if a single tab marker ===
    			// If a fact with info or a persons name ---
    		  	if (locations[i][4] || locations[i][5]) {
    		  	
      				var singletab = [
   	 				 	'<div id="gmtabs">',   	 			 	
   	  							'<ul>',
   	    							'<li><a href="#gtab1"><span>'+locations[i][0]+'<\/span><\/a><\/li>',
   	  							'<\/ul>',   	  						  					
   	  							'<div id="tab1">',
    	    						'<p>',
    	    							locations[i][4]+''+locations[i][5]+'<br />'+locations[i][6]+'<br />'+locations[i][3]+'<br />',
    	    						'<\/p>',
    	  						'<\/div>',	  			  				    	  				    	  				
    	  				'<\/div>'
    				].join('');
    				
    		  	} else {
    		  	
      				var singletab = [
   	 			 		'<div id="gmtabs">',  	 			 	
   	  							'<ul>',
   	    							'<li><a href="#gtab1"><span>'+locations[i][0]+'<\/span><\/li>',
   	  							'<\/ul>',	 	  					
   	  							'<div id="tab1">',
    	    						'<p>',
    	    							locations[i][6]+'<br />'+locations[i][3],
    	    						'<\/p>',
    	  						'<\/div>',    	  				    	  							
    	  				'<\/div>'
    				].join('');    		  
    		  	}
    		  	

   		  	
       		// === If 'placed="yes", (Use multitabs variable, else use singletab variable) ===      		
       		if (locations[i][9] == "yes" ) {
       			var html = multitabs;
       		} else {
       			var html = singletab;
       		} 
       		
       		// create the marker
       		var marker = createMarker(i, point, event, html, category, placed, index, tab, addr2);

    		var myLatLng = new google.maps.LatLng(locations[i][1], locations[i][2]);
    		bounds.extend(myLatLng);
    		map.fitBounds(bounds);

   		
      	}  // end loop
      	
      	
   		// == show or hide the categories initially ==
   		//show("theatre");
    	//hide("golf");
        //hide("info");
        
        // initially load sidebar (hidden item)
        makeSidebar();
   	
    }	// end loadMap()

//]]>
</script>

