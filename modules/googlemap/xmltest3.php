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
 * $Id: xmltest3.php 9140 2010-07-21 16:01:50Z greg $
 * @author Brian Holland
 */
?>

<script type="text/javascript">var ie = 0;</script>
<!--[if IE]>
<script type="text/javascript">ie = 1;</script>
<![endif]-->

<script type="text/javascript" src="modules/googlemap/util.js"></script>

<script type="text/javascript">

 
    var gmarkers = [];
  	var infowindow;
  	var map;  	
   	var win = "pano"; 

		<?php 	
			global $pid, $PEDIGREE_GENERATIONS, $MAX_PEDIGREE_GENERATIONS, $ENABLE_AUTOCOMPLETE, $MULTI_MEDIA, $SHOW_HIGHLIGHT_IMAGES, $WT_IMAGES, $GEDCOM;
		?>


function loadMap() {

}

	//jQuery(function() {
	
		var BoundsZoom="";
		var BoundsCenter="";
    	var myLatlng = new google.maps.LatLng(0, 0);   	
    	
    	var myOptions = {
      		zoom: 11,
      		center: myLatlng,
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
      		//streetViewControl: true
    	};
 
    	map = new google.maps.Map(document.getElementById("map_pane"), myOptions);	
    	
   		// Close any infowindow when map is clicked
    	google.maps.event.addListener(map, 'click', function() {
    		
      		if (infowindow) {
      			infowindow.close();
      		}
        	// == rebuild sidebar (hidden item) ==
        	// makeSidebar();       	
    	});
    	
    	google.maps.event.addListener(map, 'tilesloaded', function() {
    		if (BoundsZoom=="") {
				BoundsZoom = map.getZoom();
			}
			if (BoundsCenter=="") {
				BoundsCenter = map.getCenter();
			}
		});
		


  		// Create the Home DIV and call the HomeControl() constructor in this DIV.
  		var homeControlDiv = document.createElement('DIV');
  		var homeControl = new HomeControl(homeControlDiv, map);
		//var Mybounds = "";
		//var MyZoom = "";
  		homeControlDiv.index = 1;
  		map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv); 
  		
 		
    	// Get the marker data  - using the prepared temporary xml file ==
    	downloadUrl("modules/googlemap/wt_temp.xml", function(data) {
      		var markers = data.documentElement.getElementsByTagName("marker");
      		var propFact;
      		var propAddress;
      		var propDate;
      		var propName;
      		var propCity;
      		var propState;
      		var propIcon;
      		var propType;
      		var propSF;
 		
      		// == Create a variable used to hold the outer dimensions of map ==
      		var bounds = new google.maps.LatLngBounds();
    		
      		// == Read data - looping through all location markers =================================
      		for (var r = 0; r < markers.length; r++) { 

        		var latlng = new google.maps.LatLng(parseFloat(markers[r].getAttribute("lati")), parseFloat(markers[r].getAttribute("lng")));

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
    			// == Fix IE bug reporting one too many in locations.length statement ==
				if (ie==1) {
					locations.length=locations.length - 1;
				}

		
        		
      			// == Set up arrays for calculating event tab list items for each marker ==
      			var np = new Array();
				var numtabs = new Array();
				var npo = new Array();
			
      			// == Calculate event tab list items to be placed for each marker ==
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
    		 	
        		// obtain the attributes of each marker
        		var event = locations[r][0];					// Event or Fact
        		var lat = locations[r][1];						// Latitude
       			var lng = locations[r][2];						// Longitude
       			var date = locations[r][3];						// Date of event or fact
       			var info = locations[r][4];						// info on occupation, or 
  				var name = locations[r][5];						// Persons name
       			var address = locations[r][6];					// Address of event or fact
      			var index = locations[r][7];					// index
       			var tab = locations[r][8];						// tab index
       			var placed = locations[r][9];					// Yes indicates multitab item
       			var tooltip = locations[r][10];					// Marker Tooltip
				var name2 = locations[r][11];					// printable name for marker title
       			var point = new google.maps.LatLng(lat,lng);	// Latitude, Longitude
       				
       			// ==== For later use perhaps .... (using checkboxes) ==============================
       			/*
       			if (document.getElementById("golfbox").checked == false) { 
					var category = "theatre";			// Category for future pedigree map use etc
       				var addr2 = locations[i][10];		// printable address for marker title
       			} else {
       				var category = "golf";
       				var addr2 = locations[i][10];		// printable address for marker title
       			}
       			*/
 				// =================================================================================
 				
        		propFact=markers[r].getAttribute("fact");
        		propAddress=markers[r].getAttribute("placerec");
        		propDate=locations[r][3];        		
        		propName=locations[r][5];       		
        		propIcon="theatre";

		
        		//define which icon is to be used
        		var icon;
        		if (propIcon=="theatre") {
        			icon = "modules/googlemap/images/icon1.png";
        		} else if (propIcon=="golf") {
        			icon = "modules/googlemap/images/icon3.png";
        		} else {
        			icon = "modules/googlemap/images/icon4.png";
        		}
 			    		
       			var event_item ="";
       			var event_tab ="";       			
				var tabcontid = "";			
				var divhead = '<h4 id="iwhead" >'+address+'<\/h4>';
				
				// == Create infowindow content ===
				for (var n = 0; n < locations.length; n++) {
       				if (r==npo[n][0] || r==npo[n][1] || r==npo[n][2] || r==npo[n][3] || r==npo[n][4] || r==npo[n][5] || r==npo[n][6] || r==npo[n][7] || r==npo[n][8] || r==npo[n][9] || r==npo[n][10] || r==npo[n][11] || r==npo[n][12] || r==npo[n][13] || r==npo[n][14] || r==npo[n][15] || r==npo[n][16] || r==npo[n][17] || r==npo[n][18] || r==npo[n][19] || r==npo[n][20] || r==npo[n][21] || r==npo[n][22]) {
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

        		//create html text for infowindow
           		var text = [
          			'<div id="gmtabs">',
          				'<div class="tabs">',
          					'<ul >',
          						'<li><a href="#tab1" id="EV">Events<\/a><\/li>',
          						'<li><a href="#tab2" id="SV">Street View<\/a><\/li>',
          					'<\/ul>',
          					'<div id="tab1">',
          						'<div id="txt">'+propAddress+'<\/div>',
          						'<div id="evt1">',
          							'<p>',
          								event_tab,
          							//	'<b>' + propFact + '<\/b><br />',
          							//	propName  + '<br />',
          							//	propDate  + '<br />',
          								
									'<\/p>',
        						'<\/div>',
        					'<\/div>',
          					'<div id="tab2" >',
          						'<div id="txt">'+propAddress+'<\/div>',
          						'<div id="pano"><\/div>', 
          					'<\/div>',
          				'<\/div>',
          			'<\/div>'
        		].join('');

        		// == create marker ==
        		var marker = createMarker(text, latlng, icon, index, tab, tooltip);
        		// var marker = createMarker(i, point, event, html, category, placed, index, tab, addr2);
        		
        		//sets the zoom to show all points
        		bounds.extend(latlng);
        		map.fitBounds(bounds);
       		}

 		MyBounds = bounds;
 		
     	});
     	
	//});

	
 
  	function createMarker(text, latlng, icon, index, tab, tooltip) {
    	var image = new google.maps.MarkerImage(icon,
    	// This marker is 20 pixels wide by 34 pixels tall.
    	new google.maps.Size(20, 34),
    	// The origin for this image is 0,0.
    	new google.maps.Point(0,0),
    	// The anchor for this image
    	new google.maps.Point(9, 34));
 
    	var marker = new google.maps.Marker({
    	  	position: latlng, 
    	  	map: map, 
    	  	icon:image,
    	  	title: tooltip
    	});                     
  
    	google.maps.event.addListener(marker, "click", function() {
    	
      		if (infowindow) {
      			infowindow.close();
      		}
        	infowindow = new google.maps.InfoWindow({
	  			content: text          		
			});
        	infowindow.open(map, marker);
        	var panoramaOptions = {
          		position: marker.position,
          		navigationControl: false,
      			linksControl: false,
   				addressControl: false,
				pov: {
					heading: 60,
					pitch: 5,
					zoom: 1.1
				}          	
        	};

      		google.maps.event.addListener(infowindow, 'domready', function() { 
          		jQuery("#gmtabs").tabs();
          		jQuery('#SV').click(function() {
       				var panorama = new google.maps.StreetViewPanorama(document.getElementById("pano"),panoramaOptions);  
            		map.setStreetView(panorama); 
          		});         		
      		});
     		
    	});
    	
        // === Store the tab, category and event info as marker properties ===
        //marker.myindex = index;
        marker.mytab = tab;
        //marker.myplaced = placed;
        //marker.mycategory = category;                                 
        //marker.myevent = event;
       // marker.myaddress = address;
        gmarkers.push(marker);    	
    	
    	
    	return marker;
    	
  	} 
  	
	// == Opens Marker infowindow when corresponding Sidebar item is clicked ==
    function myclick(i, index, tab) { 

	//	if (document.getElementById("golfbox").checked == false) { 
		
			// Either events -----------
      		if (infowindow) {
      			infowindow.close();
      		}
			google.maps.event.trigger(gmarkers[i], "click", tab);
			// Use jquery for tabs -------- 
			//jQuery("#gmtabs").tabs();
			//jQuery("#gmtabs").tabs('select', tab); 			
        	// == rebuild the side bar ==
        	// makeSidebar(i);   
    /*    		
    	} else {
    	
    		// Or streetview -----------    		
			var message =
				"Marker: " + gmarkers[i] + "<br>" +
				"LatLng: " + gmarkers[i].getPosition().toUrlValue(4) + "<br>";			
			setMarkerMessage2(gmarkers[i], message);	
    	} 
    */
    
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

  		// Setup the click event listeners: simply set the map to original LatLng and Zoom
  		google.maps.event.addDomListener(controlUI, 'click', function() {
      		if (infowindow) {
      			infowindow.close();
      		}
			map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
			map.setZoom(BoundsZoom);
			map.setCenter(BoundsCenter);
  		});
	}
	
</script> 

<script>
window.onload=loadMap
</script>



<!-- CSS The following will be moved to css later -->
<style> 
#gmtabs {
font-size: 10px;
width:340px; 
height: 260px;
}

#txt {
/* width:319px; */
margin-top: 0px;
margin-left: -5px;
background: #ffffcc;
text-align: center;
border: 1px solid #dddddd;
border-top: 0px solid #dddddd;
}

#pano {
/*width:324px; */
height: 210px;
/* margin-left: -6px; */
}
</style>

