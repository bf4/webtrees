<?php
/**
 * Google map module for phpGedView
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Modifications Copyright (c) 2010 Greg Roach
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package webtrees
 * @subpackage Module
 * $Id: V3a_googlemap.js.php 9140 2010-07-21 16:01:50Z greg $
 * @author Brian Holland
 */
?>

<script type="text/javascript">var ie = 0;</script>
<!--[if IE]>
<script type="text/javascript">ie = 1;</script>
<![endif]-->

<script type="text/javascript">

//<![CDATA[
    
    // this variable will collect the html which will eventually be placed in the side_bar 
    var side_bar_html = ""; 
	var map_center = new google.maps.LatLng(53.8403,-2.0377);	
    var gmarkers = [];
    var gicons = [];
    var map = null;
    
    //var markers = [];

	var infowindow = new google.maps.InfoWindow( { 
    	// size: new google.maps.Size(150,50),
    	// maxWidth: 600
  	});
  	
  	<?php 
	echo 'gicons["red"] = new google.maps.MarkerImage("http://maps.google.com/mapfiles/marker.png",';
    	echo 'new google.maps.Size(20, 34),';
    	echo 'new google.maps.Point(0,0),';
    	echo 'new google.maps.Point(9, 34));';

  	echo 'var iconImage = new google.maps.MarkerImage("http://maps.google.com/mapfiles/marker.png",';
    	echo 'new google.maps.Size(20, 34),';
    	echo 'new google.maps.Point(0,0),';
    	echo 'new google.maps.Point(9, 34));';
    
  	echo 'var iconShadow = new google.maps.MarkerImage("http://www.google.com/mapfiles/shadow50.png",';
      	echo 'new google.maps.Size(37, 34),';
      	echo 'new google.maps.Point(0,0),';
      	echo 'new google.maps.Point(9, 34));';
      	
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
      		new google.maps.Size(20, 34),
      		new google.maps.Point(0,0),
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
    
	
	// var SView = "";
	// A function to create the marker and set up the event window
	function createMarker(i, latlng, event, html, category, placed, index, tab, address) {
	
    	var contentString = '<div id="iwcontent">'+html+'<\/div>';
    	
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
		if (document.getElementById("golfbox").checked == false) { 
			// Either events -----------
    		google.maps.event.addListener(marker, 'click', function() {
    			infowindow.close();
        		infowindow.setContent(contentString);
        		infowindow.open(map, marker);       	
				// Use jquery for tabs -------- 
				//jQuery("#gmtabs").tabs();
				//jQuery("#gmtabs").tabs('select', tab); 			
        		// == rebuild the side bar ==
        		makeSidebar(i);      	
    		});
    	} else {
    		// Or streetview -----------
			var message =
				"Marker: " + (i + 1) + "<br/>" +
				"LatLng: " + marker.getPosition().toUrlValue(4) + "<br/>";
			setMarkerMessage(marker, message);    		
    	}

	}
	
	// == Streetview function when streetview selected =====
	function setMarkerMessage(marker, message) {
		google.maps.event.addListener(marker, 'click', function() {
			var streetViewDiv = document.createElement('div');
			streetViewDiv.style.width = "320px";
			streetViewDiv.style.height = "240px";					
			var streetViewPanorama = new  google.maps.StreetViewPanorama(
				streetViewDiv,
				{
					position: marker.getPosition(),
					navigationControl: false,
      				linksControl: false,
      				addressControl: false,
					pov: {
						heading: 62,
						pitch: 0,
						zoom: 1.7
					}
							
				}						
			);	
			map.setStreetView(streetViewPanorama);					
			infowindow.setContent(streetViewDiv);
			infowindow.open(map, marker);
			google.maps.event.trigger(streetViewPanorama, 'resize')
		});
	}
	
	// == Streetview function when streetview selected =====
	function setMarkerMessage2(marker2, message) {
			var streetViewDiv2 = document.createElement('div');
			streetViewDiv2.style.width = "320px";
			streetViewDiv2.style.height = "240px";					
			var streetViewPanorama2 = new google.maps.StreetViewPanorama(
				streetViewDiv2,
				{
					position: marker2.getPosition(),
					navigationControl: false,
      				linksControl: false,
      				addressControl: false,
					pov: {
						heading: 62,
						pitch: 0,
						zoom: 1.7
					}
							
				}						
			);	
			map.setStreetView(streetViewPanorama2);					
			infowindow.setContent(streetViewDiv2);
			infowindow.open(map, marker2);
			google.maps.event.trigger(streetViewPanorama2, 'resize')
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
       loadMap();
    }

	// == Opens Marker infowindow when corresponding Sidebar item is clicked ==
    function myclick(i, index, tab) { 

		if (document.getElementById("golfbox").checked == false) { 
		
			// Either events -----------
			infowindow.close();
			google.maps.event.trigger(gmarkers[i], "click", tab);
			// Use jquery for tabs -------- 
			//$("#gmtabs").tabs();
			//$("#gmtabs").tabs('select', tab); 			
        	// == rebuild the side bar ==
        	makeSidebar(i);   
        		
    	} else {
    	
    		// Or streetview -----------    		
			var message =
				"Marker: " + gmarkers[i] + "<br>" +
				"LatLng: " + gmarkers[i].getPosition().toUrlValue(4) + "<br>";			
			setMarkerMessage2(gmarkers[i], message);
			
    	}
  
        
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
	 * returns the user to the original map position ... loadMap() function
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
  		controlText.innerHTML = '<b><?php echo i18n::translate('Redraw map')?><\/b>';
  		controlUI.appendChild(controlText);

  		// Setup the click event listeners: simply set the map to original LatLng
  		google.maps.event.addDomListener(controlUI, 'click', function() {    		
    		loadMap();
  		});
	}

  	function loadMap() { 
  	
		<?php 	
			global $pid, $PEDIGREE_GENERATIONS, $MAX_PEDIGREE_GENERATIONS, $ENABLE_AUTOCOMPLETE, $MULTI_MEDIA, $SHOW_HIGHLIGHT_IMAGES, $WT_IMAGES, $GEDCOM;
		?>

		
  		// Create the map and mapOptions
		var mapOptions = {
			zoom: 7,
			center: map_center,
			mapTypeId: google.maps.MapTypeId.TERRAIN,					// ROADMAP, SATELLITE, HYBRID, TERRAIN
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.DROPDOWN_MENU 	// DEFAULT, DROPDOWN_MENU, HORIZONTAL_BAR
			},
			navigationControl: true,
      		navigationControlOptions: {
   				position: google.maps.ControlPosition.TOP_RIGHT,		// BOTTOM, BOTTOM_LEFT, LEFT, TOP, etc
   				style: google.maps.NavigationControlStyle.SMALL			// ANDROID, DEFAULT, SMALL, ZOOM_PAN
      		},
      		scrollwheel: false
		};
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
			<?php foreach($gmarks as $gmark) { 
						
				// create thumbnail images of highlighted images ===========================
				if (!empty($pid)) {
					$this_person = Person::getInstance($pid);
				}
				if (!empty($gmark['name'])) {
					$person = Person::getInstance($gmark['name']);
				}
				
				// This person -----------------------------
				if (!empty($this_person)) {
					$indirec = $this_person->getGedcomRecord();
					$image = "";							
					if ($MULTI_MEDIA && $SHOW_HIGHLIGHT_IMAGES) {
						if (!empty($pid)) {
							$object = find_highlighted_object($pid, WT_GED_ID, $indirec);
						} else {
							$object = "";
						}

						if (!empty($object["thumb"])) {
							$size = findImageSize($object["thumb"]);
							$class = "pedigree_image_portrait";
							if ($size[0]>$size[1]) $class = "pedigree_image_landscape";
							if ($TEXT_DIRECTION=="rtl") $class .= "_rtl";
							$image = "<img src='{$object["thumb"]}' vspace='0' hspace='0' class='{$class}' alt ='' title='' >";
						} else {
							$class = "pedigree_image_portrait";
							if ($TEXT_DIRECTION == "rtl") $class .= "_rtl";
							$sex = $this_person->getSex();
							$image = "<img src=\'./";
							if ($sex == 'F') { $image .= $WT_IMAGES["default_image_F"]; }
							elseif ($sex == 'M') { $image .= $WT_IMAGES["default_image_M"]; }
							else { $image .= $WT_IMAGES["default_image_U"]; }
							$image .="\' align=\'left\' class=\'".$class."\' border=\'none\' alt=\'\' />";
						}
					} // end of add image
					
				}
				
				// Other people ----------------------------
				if (!empty($person)) {
					$indirec2 = $person->getGedcomRecord();
					$image2 = "";							
					if ($MULTI_MEDIA && $SHOW_HIGHLIGHT_IMAGES) {
						if (!empty($gmark['name'])) {
							$object2 = find_highlighted_object($gmark['name'], WT_GED_ID, $indirec2);
						} else {
							$object2 = "";
						}

						if (!empty($object2["thumb"])) {
							$size = findImageSize($object2["thumb"]);
							$class = "pedigree_image_portrait";
							if ($size[0]>$size[1]) $class = "pedigree_image_landscape";
							if ($TEXT_DIRECTION=="rtl") $class .= "_rtl";
							$image2 = "<img src='{$object2["thumb"]}' vspace='0' hspace='0' class='{$class}' alt ='' title='' >";
						} else {
							$class = "pedigree_image_portrait";
							if ($TEXT_DIRECTION == "rtl") $class .= "_rtl";
							$sex = $person->getSex();
							$image2 = "<img src=\'./";
							if ($sex == 'F') { $image2 .= $WT_IMAGES["default_image_F"]; }
							elseif ($sex == 'M') { $image2 .= $WT_IMAGES["default_image_M"]; }
							else { $image2 .= $WT_IMAGES["default_image_U"]; }
							$image2 .="\' align=\'left\' class=\'".$class."\' border=\'none\' alt=\'\' />";
						}
					} // end of add image
					
				}
								
			?>
    			[
					"<?php echo $gmark['fact'].''; ?>", 
    				"<?php echo $gmark['lati']; ?>", 
    				"<?php echo $gmark['lng']; ?>", 
    				"<?php if (!empty($gmark['date'])) { $date=new GedcomDate($gmark['date']); echo addslashes($date->Display(true)); } else { echo i18n::translate('Date not known'); } ?>", 
    				"<?php if (!empty($gmark['info'])) 	{ echo ''.$gmark['info']; } else { echo NULL; } ?>", 
    				"<?php if (!empty($gmark['name'])) { $person=Person::getInstance($gmark['name']); if ($person) { echo '<a href=\"', $person->getLinkUrl(), '\">', $person->canDisplayName() ? PrintReady(addcslashes($person->getFullName(), '"')) : i18n::translate('Private'), '<\/a>'; } } ?>", 
    				"<?php if (preg_match('/2 PLAC (.*)/', $gmark['placerec']) == 0) { print_address_structure_map($gmark['placerec'], 1); } else { echo preg_replace('/\"/', '\\\"', print_fact_place_map($gmark['placerec'])); } ?>",
					"<?php echo $gmark['index'].''; ?>", 
					"<?php echo $gmark['tabindex'].''; ?>",
					"<?php echo $gmark['placed'].''; ?>",
					
					// 10. location marker tooltip - extra printable item for marker title.
					"<?php echo strip_tags(preg_replace('/\"/', '\\\"', print_fact_place_map($gmark['placerec']))); ?>",
					
					// 11. 
					"<?php if (!empty($gmark['name'])) { $person=Person::getInstance($gmark['name']); if ($person) { echo $person->canDisplayName() ? PrintReady(addcslashes($person->getFullName(), '"')) : i18n::translate('Private'); } } ?>",
					
					// 12. Other people's Highlighted image.
					"<?php if (!empty($gmark['name'])) { echo $image2; } else { echo ''; } ?>",
					
					// 13. This Individual's Highlighted image.
					"<?php if (!empty($pid)) { echo $image; } else { echo ''; } ?>"
    			], 
    		<?php } ?> 
    	];    	
    	// Fix IE bug reporting one too many in locations.length statement -----
		if (ie==1) {
			locations.length=locations.length - 1;
		}
		
		// Set the Marker bounds -----------------------------------------------
    	var bounds = new google.maps.LatLngBounds ();
    	

		// Calculate tabs to be placed for each marker -------------------------
    	var np = new Array();
		var numtabs = new Array();
		var npo = new Array();
		
		for (var p = 0; p < locations.length; p++) {
			np[p] = ''+p+'';			
			numtabs[p] = 0;
			npo[p] = new Array();
    	    for (var q = 0; q < locations.length; q++) {        
				if (jQuery.inArray(np[p], locations[q][7])==0) {
					npo[p][numtabs[p]] = q;
					numtabs[p]++;
				}
    	    }
    	} 

    	// Loop through all location markers -----------------------------------    	
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
       		var name2 = locations[i][11];					// printable name for marker title
       		var point = new google.maps.LatLng(lat,lng);	// Latitude, Longitude
       		if (document.getElementById("golfbox").checked == false) { 
       			var category = "theatre";						// Category for future pedigree map use etc
       			var addr2 = locations[i][10];					// printable address for marker title
       		} else {
       			var category = "golf";
       			var addr2 = locations[i][10];					// printable address for marker title
       		}

       		// === Use this variable if a multitab marker ===
   			// If a fact with info or a persons name ---  			    		
       		var event_item ="";
       		var event_tab ="";       			
			var tabcontid = "";			
			var divhead = '<h4 id="iwhead" >'+address+'<\/h4>';
			
			for (var n = 0; n < locations.length; n++) {
       			if (i==npo[n][0] || i==npo[n][1] || i==npo[n][2] || i==npo[n][3] || i==npo[n][4] || i==npo[n][5] || i==npo[n][6] || i==npo[n][7] || i==npo[n][8] || i==npo[n][9] || i==npo[n][10] || i==npo[n][11] || i==npo[n][12] || i==npo[n][13] || i==npo[n][14] || i==npo[n][15] || i==npo[n][16] || i==npo[n][17] || i==npo[n][18] || i==npo[n][19] || i==npo[n][20] || i==npo[n][21] || i==npo[n][22]) {
       				for (var x=0; x<numtabs[n]; x++) { 			
       					tabcontid=npo[n][x];
       					// If a fact with info or a persons name ---
       					if (locations[tabcontid][5]) {
       						event_tab 	+=	[ '<table><tr><td class="highlt_img">'+locations[tabcontid][12]+'<\/td><td><p><span id="sp1">'+locations[tabcontid][0]+'<\/span><br />'+locations[tabcontid][4]+'<b>'+locations[tabcontid][5]+'<\/b><br />'+locations[tabcontid][3]+'<br /><\/p><\/td><\/tr><\/table>' ]; 
   						} else if (locations[tabcontid][4]) {
   							event_tab 	+=	[ '<table><tr><td class="highlt_img">'+locations[tabcontid][13]+'<\/td><td><p><span id="sp1">'+locations[tabcontid][0]+'<\/span><br />'+locations[tabcontid][4]+'<b>'+locations[tabcontid][5]+'<\/b><br />'+locations[tabcontid][3]+'<br /><\/p><\/td><\/tr><\/table>' ]; 
   						} else {
       						event_tab 	+=	[ '<table><tr><td class="highlt_img">'+locations[tabcontid][13]+'<\/td><td><p><span id="sp1">'+locations[tabcontid][0]+'<\/span><br />'+locations[tabcontid][3]+'<br /><\/p><\/td><\/tr><\/table>' ];    						
   						}
   					}
     			}
     		} 
    /* 		
     		var multitabs = [  				
 			 	'<div id = "gmtabs">',
          					'<ul >',
          						'<li><a href="#tabs-1" id="EV">Events<\/a><\/li>',
          						'<li><a href="#tabs-2" id="SV">Street View<\/a><\/li>',
          					'<\/ul>',
          					'<div id="tabs-1">',
          						event_tab,
          					'<\/div>',
          					'<div id="tabs-2">',
          						'StreetVIEW',
          					'<\/div>',
          					
  	  			'<\/div>'
   			].join('');
   	*/		
     		
   			var multitabs = [  				
 			 	'<div id = "gmtabs">', 
						'<div id="tab-1">',
    	    				event_tab,
						'<\/div>',
  	  			'<\/div>'
   			].join('');
			
			   			
    		// === Use this variable if a single tab marker ===
    		// If a fact with info or a persons name ---
   		  	if (locations[i][5]) {    		  	
   				var singletab = [
   	 				 '<div id="gmtabs">',   	 			 	 	  						  					
   	  						'<div id="tab-1">',
   	    						'<table><tr><td class="highlt_img">'+locations[tabcontid][12]+'<\/td><td><p>',
   	    							'<span id="sp1">'+locations[i][0]+'<\/span><br />'+locations[i][4]+'<b>'+locations[i][5]+'<\/b><br />'+locations[i][3]+'<br />',
    	    					'<\/p><\/td><\/tr><\/table>',
    	  					'<\/div>',	  			  				    	  				    	  				
    	  			'<\/div>'
    			].join('');
    		} else if (locations[i][4]) {
   				var singletab = [
   	 				 '<div id="gmtabs">',   	 			 	 	  						  					
   	  						'<div id="tab-1">',
   	    						'<table><tr><td class="highlt_img">'+locations[tabcontid][13]+'<\/td><td><p>',
   	    							'<span id="sp1">'+locations[i][0]+'<\/span><br />'+locations[i][4]+'<b>'+locations[i][5]+'<\/b><br />'+locations[i][3]+'<br />',
    	    					'<\/p><\/td><\/tr><\/table>',
    	  					'<\/div>',	  			  				    	  				    	  				
    	  			'<\/div>'
    			].join('');    			
    	  	} else {    		  	
      			var singletab = [
			 		'<div id="gmtabs">',  	 			 		 	  					
   	  					'<div id="tab-1">',
   	  						'<table><tr><td class="highlt_img">'+locations[tabcontid][13]+'<\/td><td><p>',
    	    					'<span id="sp1">'+locations[i][0]+'<\/span><br />'+locations[i][3]+'<br />',
    	   					'<\/p><\/td><\/tr><\/table>',
   						'<\/div>',    	  				    	  							
   	  				'<\/div>'
   				].join('');    		  
   		  	}
    		  	

   		  	
       		// === If 'placed="yes", (Use multitabs variable, else use singletab variable) ===      		
       		if (locations[i][9] == "yes" ) {
       			var html = divhead + multitabs;
       		} else {
       			var html = divhead + singletab;
       		} 

      		
       		// create the marker -----------------------------------------------
       		var marker = createMarker(i, point, event, html, category, placed, index, tab, addr2);
    		var myLatLng = new google.maps.LatLng(locations[i][1], locations[i][2]);
    		bounds.extend(myLatLng);
    		map.fitBounds(bounds);
  		
      	}  // end loop through location markers
      	
      	
   		// == show or hide the categories initially ==
   		show("theatre");
    	hide("golf");
        //hide("info");
        
        // initially load sidebar (hidden item but THIS IS required)
        makeSidebar();
   	
    }	// end loadMap()
    

//]]>
</script>

