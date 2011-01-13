<?php
/**
 * Displays a streetview map
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2010  PGV Development Team. All rights reserved.
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
 * @subpackage Lists - placehierarchy
 * @version $Id: streetview.php 10309 2011-01-03 21:13:31Z greg $
 */
 ?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>Street/Map Control</title>
    
    <style type="text/css">
    
    body
    {
        font-family: Sans-Serif, Arial, Verdana, Helvetica;
        margin-top: 0px;
        margin-bottom: 0px;
        margin-right: 0px;
        margin-left: 0px;
        font-size: small;
    }
    div.markerTooltip
    {
        font-family: Arial; color: black; font-size:11px; font-weight: bold;
        background-color: #FFFFFF; margin: 0px; padding: 2px; border: solid 1px black;
    } 
    
    a.menuoff { color:#4682B4; background-color:white; text-decoration:none; border:solid 1px #4682B4; padding-left:5px; padding-right:5px;  }
    a.menuoff:hover { color: #4682B4; background-color:white; }
    
    a.menuon { color:white; background-color:#4682B4; font-weight:bold; text-decoration:none; border:solid 1px #4682B4; padding-left:5px; padding-right:5px;  }
    a.menuon:hover { color:white; background-color:#4682B4; }
    
    A:focus { outline: 0; -moz-outline: none; }  
    
    </style>
  
  	<?php 
  	// Note the api key is extracted for the url for now to avoid errors  --- these following lines will disappear when v3 is employed --
  	$ggmkey = $_GET['ggmkey'];
	/*    
    <!--<script type="text/javascript" src="http://maps.google.com/maps?file=api&hl=en&v=2&key=<?php echo $GOOGLEMAP_API_KEY; ?>&sensor=false"></script> --> 
    <!-- <script type="text/javascript" src="http://maps.google.com/maps?file=api&hl=en&v=2&key=ABQIAAAAOi47wpMF0Gnw8XwdliBxXRQCeCcK0aPAvfHdl869rpMy_2bb3BS8IwYF2P1LoyQ6LNCmQz7lDm_Bkw&sensor=false"></script> -->
	*/
	?>

    <script type="text/javascript" src="http://maps.google.com/maps?file=api&hl=en&v=2&key=<?php echo $ggmkey; ?>&sensor=false"></script>
    <script type="text/javascript">
    
	// Following function creates an array of the google map parameters passed ---------------------    
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
	// ---------------------------------------------------------------------------------------------
 
/*
*
*  StreetCities.com - Street/Map Control version 1.01
*  Copyright (c) 2009 - 2010, Map Channels, mapchannels@gmail.com
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
* 
*       http://www.apache.org/licenses/LICENSE-2.0
*
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*  limitations under the License.
*
*  This code creates an embeddable street view and map control using the Google Maps API.
*  Information on this code is available at http://www.streetcities.com/streetmap.aspx and http://www.streetcities.com/streetmapapi.aspx
*  More free mapping resources can be found at http://www.streetcities.com and http://www.mapchannels.com
*
*/

        var map = null;             // main map object
        var panorama = null;        // main panorama object

        var isIE = false;           // internet explorer
        
        // default map settings
        //var x = -122.42; // longitude
        //var y = 37.77917;  // latitude
		var x = qsParm['x'];
    	var y = qsParm['y'];
        var zoom = 15;      // map zoom level
        var mapType = 2;    // 0=road 1=sat 2=hybrid 3=terrain

        // streetview settings
        var pitch = 0;      // 90=Sky 0=Horizon -90=Ground
        var magnify = 0;    // 0=Normal 1=Level 1 Zoom 2=Level 2 Zoom
        var bearing = 0;    // View Direction in  Degrees
        var panoId = "";    // unique panorama id
        
        // GeoXML Layer
        var layerUrl = "";      // 106025709491715126474.000435dbd9aaa53f7b44c      
        var layerTitle = "";
        
        var geoXmlLayer = null;
        
        var linkShow = true;        // include a link to full screen map and street view
        var coordShow = true;       // show streetview coordinates and direction below the streetview
        var scrollWheelOn = false;  // scrollwheel zooming      
        var mapControlType = 1;     // 0=None 1=Small 2=Large
        var initialView = true;     // false=start in map view, true=start with full window street view
        
        var streetviewOverlay = null;        
        var streetviewClient = null;        
        var currentMode = -1;       // 0= map 1=street view
        
        // dimensions of browser window
        var windowWidth = 0;
        var windowHeight = 0;        
        var streetTitle = "";       // Area title displayed above Street View panorama        

        var latlng = null;          // current marker location
        var marker = null;          // streetview marker object
        var streetviewValid = false;    // set if a street view exists for current latlng
        var panInitialized = false;     // set when a valid panorama is found during an update
        
        var tooltip = null;     // the tooltip div
        var dragging = false;   // flag set when map marker is being dragged
        var oldLatlong = null;
        var oldStreetTitle = "";
        
        // initial map and street view settings, used for restoreView()
        var origLatlng = null;
        var origZoom = 0;
        var origBearing = 0;
        var origPitch = 0;
        var origMagnify = 0;
        
        var searchOn = false;
        
        // get the control settings contained in the url
        function decodeParameters()
        {
            var queryString = document.location.search.substring(1) + "&";    
            var xParam = getParameter(queryString, "x");
            var yParam = getParameter(queryString, "y");        
            var zoomParam = getParameter(queryString, "z"); 
            var maptypeParam = getParameter(queryString, "t"); 
            var mapcontrolParam = getParameter(queryString, "c"); 

            var bearingParam = getParameter(queryString, "b"); 
            var pitchParam = getParameter(queryString, "p");
            // var magnifyParam = getParameter(queryString, "m");
            var magnifyParam = parent.document.getElementById('sv_zoomText').value;

            var coordShowParam = getParameter(queryString, "j");
            var linkShowParam = getParameter(queryString, "k");
            var scrollwheelParam = getParameter(queryString, "s"); 
            var initialviewParam = getParameter(queryString, "v"); 

            var msid = getParameter(queryString, "msid"); 
            if (msid.length)
            {
                // Get the Google My Map url and title
                layerUrl = "http://maps.google.com/maps/ms?client=firefox-a&hl=en&ie=UTF8&msa=0&output=kml&msid=" + msid;
                layerTitle = getParameter(queryString, "name"); 
            }
            
            
            if (xParam.length)
            {
                x = parseFloat(xParam);
            }       
            if (yParam.length)
            {
                y = parseFloat(yParam);
            }       
            if (zoomParam.length)
            {
                zoom = parseFloat(zoomParam);
            }       
            if (maptypeParam.length)
            {
                mapType = parseFloat(maptypeParam);
            }       
            
            if (mapcontrolParam.length)
            {
                mapControlType  = parseInt(mapcontrolParam);
            }
            
            if (bearingParam.length)
            {
                bearing = parseInt(bearingParam);
            }       
            if (pitchParam.length)
            {
                pitch = parseInt(pitchParam);
            }       
            if (magnifyParam.length)
            {
                magnify = parseInt(magnifyParam);
            }       
            
            if (coordShowParam.length)
            {
                coordShow = (coordShowParam == "1");
            }       
            if (linkShowParam.length)
            {
                linkShow = (linkShowParam == "1");
            }       
            if (scrollwheelParam.length)
            {
                scrollWheelOn = (scrollwheelParam == "1");
            }       
            if (initialviewParam.length)
            {
                initialView = (initialviewParam == "1");
            }       
            
            // default location - should be a location with a street view panorama available
            latlng = new GLatLng(y,x);
            
            // store original view
            origLatlng = latlng;
            origZoom = zoom;
            origBearing = bearing;
            origPitch = pitch;
            origMagnify = magnify;
        }        

        function displayMode()
        {
            eID("m0").className = (currentMode ? "menuoff" : "menuon");
            eID("m1").className = (currentMode ? "menuon" : "menuoff");
        }
        
        // apply the selected street view size
        function setMode(a)
        {
            if (currentMode != a)
            {
                currentMode = a;
                displayMode();

                if (currentMode == 1)   // hide map and show the panorama
                {
                    eID("panFrame").style.display = "block";
                    eID("mapFrame").style.display = "none";
                    
                    initPanorama(latlng);
                }
                else
                {                
                    eID("panFrame").style.display = "none";
                    eID("mapFrame").style.display = "block";

                    resizeMap();
                    
                    map.setCenter(latlng);
                    showTooltip();
                    
                    // setTimeout("initSearch();", 1);
                }
            }
        }
        
        // create map with street view enabled and initial location marker
        function initMap()    
        {
            // get initial window dimensions            
            resizeMap();

            // chrome bugfix
            if (windowHeight > 0)
            {
                // ok
            }
            else
            {
                // GLog.write("retry ... ");
                setTimeout("initMap()", 50);
                return;
            }

        
            var mapTypeCodes = [G_NORMAL_MAP, G_SATELLITE_MAP, G_HYBRID_MAP, G_PHYSICAL_MAP];
            decodeParameters();

            
            map = new GMap2(eID("mapDiv"));
            map.addMapType(G_PHYSICAL_MAP);
            
            if (mapControlType == 1)
            {
                map.addControl(new GSmallZoomControl3D());
            }
            if (mapControlType == 2)
            {
                map.addControl(new GLargeMapControl3D());
            }
            
            map.addControl(new GScaleControl());
            map.addControl(new GMenuMapTypeControl());
            map.enableDoubleClickZoom();
            map.enableContinuousZoom();
            if (scrollWheelOn)
            {
                map.enableScrollWheelZoom();
            }
            
            // marker tooltip div
            tooltip = document.createElement("toolDiv");
            map.getPane(G_MAP_FLOAT_PANE).appendChild(tooltip);
            tooltip.style.visibility = "hidden";
            tooltip.innerHTML = "<div id='tooltipText' class='markerTooltip'>&nbsp;<\/div>";
    

            // add kml layer (if specified)
            if (layerUrl != "")
            {
                geoXmlLayer = new GGeoXml(layerUrl);
                map.addOverlay(geoXmlLayer);
                
                eID("layerTitle").innerHTML = layerTitle;
            }
            else
            {
                eID("layerShow").style.display = "none";
            }
            
            map.setCenter(latlng,zoom);
            map.setMapType(mapTypeCodes[mapType]);
            
            // set up street view overlay and client
            streetOverlay = new GStreetviewOverlay();
            map.addOverlay(streetOverlay);
            streetviewClient = new GStreetviewClient();
      
            // map ads 
            var adManagerOptions = 
            {
                style: 'adunit',
                channel: "5429192447",
                maxAdsOnMap: 1
            };
        
            var adManager = new GAdsManager(map, "pub-5408854154696215", adManagerOptions);
            adManager.disable();

            createMarker();
            
            GEvent.addListener(map, "click", function(overlay, point)
            {
                // remember previous point
                oldLatlong = latlng;
                oldStreetTitle = streetTitle;
                
                if (point)
                {
                    pitch = 0;
                    magnify = 0;
                    latlng = point;
                    
                    setTimeout("setMode(1);", 1);
                }
            });
            
            GEvent.addListener(map, "zoomend", function()
            {
                showTooltip();
            });
            
            if (initialView)
            {
                // start in Street View
                eID("mapFrame").style.display = "none";
                eID("panFrame").style.display = "block";
            }
            
            currentMode = 0;
            displayMode();
            
            updatePanStats();
            initPanorama(latlng);
        }
        
        function initSearch()
        {
            if (!searchOn)
            {
                map.enableGoogleBar();
                searchOn = true;
            }
        }
        
        // Create the main panorama
        function createPanorama(container)
        {
            if (panorama == null)
            {
                // create the panorama
                panorama = new GStreetviewPanorama(container);
                panorama.setLocationAndPOV(latlng, {"yaw":bearing,"pitch":pitch,"zoom":magnify});
                
                // listen for changes in bearing and latlng, then update marker accordingly
                GEvent.addListener(panorama,"yawchanged",function(a)
                     {
                        bearing = parseFloat(a);
                        updateMarker();
                        updatePanStats();
                        parent.document.getElementById('sv_bearText').value = Math.round(a) +"\u00B0";
                     });

                GEvent.addListener(panorama,"pitchchanged",function(a)
                     {
                        pitch = parseFloat(a);
                        updatePanStats();                
						parent.document.getElementById('sv_elevText').value = Math.round(a) +"\u00B0";
                     });

                GEvent.addListener(panorama,"zoomchanged",function(a)
                     {
                        magnify = parseInt(a);
                        updatePanStats();
                        parent.document.getElementById('sv_zoomText').value = a;
                     });

                GEvent.addListener(panorama,"initialized",function(a)
                     {
                        latlng = a.latlng;
                        streetTitle = a.description;
                        panoId = a.panoId;
                        
                        updateMarker();
                        updatePanStats();
                     }); 
            }
        }

        // Create the Street View map marker
        function createMarker()
        {
            var icon = new GIcon();

            var imageNum = Math.round(bearing/22.5) % 16;
            var imageUrl = "http://mk.mapchannels.com/panda-" + imageNum + ".png";
            
            icon.image = imageUrl;
            icon.iconSize = new GSize(49,52);
            icon.iconAnchor =  new GPoint(25,36);
            
            marker = new GMarker(latlng, {"icon":icon, "draggable":true});
                         
            GEvent.addListener(marker, "dragstart", function()
           {            
                dragging = true;
                hideTooltip();

                // remember original point
                oldLatlong = latlng;
                oldStreetTitle = streetTitle;
           });              
            
            GEvent.addListener(marker, "dragend", function()
           {            
                dragging = false;
                latlng = marker.getPoint();
                
                // reset streetview pitch/zoom but keep current streetview bearing
                magnify = 0;
                pitch = 0;
                setMode(1);
           });              
            
           GEvent.addListener(marker, "click", function()
           {  
                hideTooltip();

                // reset streetview pitch/zoom but keep current streetview bearing
                magnify = 0;
                pitch = 0;
                
                setMode(1);
           }); 
            
            map.addOverlay(marker);
        }

        // Update the co-ordinate and link labels at the base of the control        
        function updatePanStats()
        {
            eID("tooltipText").innerHTML = formatTooltip(streetTitle);

            // display current lat long and direction
            if (coordShow)
            {
                var coordLabel = eID("coordLabel");
                var html = "&nbsp;lati <b>" + latlng.lat() + "&deg;&nbsp;<\/b> long <b>" + latlng.lng() + "&deg;&nbsp;<\/b> bearing <b>" + parseInt(bearing) + "&deg;&nbsp;<\/b> elev <b>" + parseInt(pitch) + "&deg;&nbsp;<\/b> zoom <b>" + parseInt(magnify) + "<\/b><button style='margin-top:-3px; float:right;'>Save View<\/button>";
                parent.document.getElementById('sv_latiText').value = latlng.lat()+"\u00B0";
                parent.document.getElementById('sv_longText').value = latlng.lng()+"\u00B0";
                if (coordLabel)
                {
                    coordLabel.innerHTML = html;
                }
            }                

            // display links to full page map and street view
            if (linkShow)
            {
                var linkLabel = eID("linkLabel");
                if (linkLabel)
                {
                    var label = streetTitle;
                    if (label == "")
                    {
                        label = "Bookmark";
                    }
                    var googleLink = 
                        "http://maps.google.com/maps?q=" + label + "%40" + latlng.lat() + "," + latlng.lng() +
                        "&z=" + map.getZoom() + "&layer=c" +
                        "&cbp=1," + bearing + ",," + magnify + "," + pitch +
                        "&cbll=" + latlng.lat() + "," + latlng.lng() + "&panoid=" + panoId;
                
                    linkLabel.innerHTML =
                        "<a target='_blank' href='http://www.streetcities.com/' style='color:darkgoldenrod; font-weight:bold' title='www.streetcities.com - Free Street View and Mapping Tools'>Street Cities<\/a> " +
                        "<a target='_blank' href='" + googleLink + "' style='color:green; font-weight:bold' title='View Larger Google Map'>Google Maps<\/a>&nbsp;";
                }                
            }
        }

        function updateMarker()
        {            
            // update marker image
            var imageNum = Math.round(bearing/22.5) % 16;
            var imageUrl = "http://mk.mapchannels.com/panda-" + imageNum + ".png";                
            marker.setImage(imageUrl);
          
           if (!dragging)
           {
                // update marker location
                marker.setPoint(latlng);                
            }
        }
        
        // return to the initial map location
        function viewRestore()
        {
            latlng = origLatlng;
            zoom = origZoom;
            
            bearing = origBearing;
            pitch = origPitch;
            magnify = origMagnify;
            
            map.setCenter(latlng, zoom);
            
            // initPanorama(origLatlng);
            currentMode = -1;
            setMode(1);
        }

        // find nearest panorama      
        function initPanorama(point)
        {                
            if (true)   // !panInitialized)
            {
                createPanorama(eID("panDiv"));
                panInitialized = true;
            }   
            
            // change current point (note, if a nearby street view is found then the latlng will be updated to the latlng of the streetview)
            streetviewClient.getNearestPanorama(point, initPanorama2);
        }

        // callback function for initPanorama() - change panorama view to specified latlong
        function initPanorama2(param)
        {
            // validate new location
            streetviewValid  = true;
            if ((param == null) || (param.location == null))
            {
                // No Street View available, return to the previous view
                streetviewValid  = false;
                latlng = oldLatlong;
                streetTitle = oldStreetTitle;
            }
            else
            {    
                // store new streetview location and title
                var location = param.location;
                latlng = location.latlng;
                streetTitle = location.description;                
                if (oldStreetTitle == "")
                {
                    oldStreetTitle = streetTitle;
                }
            }
                
            updateMarker();            
            updatePanStats();                
            showTooltip();

            if (streetviewValid)
            {
                currentMode = 1;
                displayMode();

                panorama.setLocationAndPOV(latlng, {"yaw":bearing,"pitch":pitch,"zoom":magnify});
            }
            
        }


        // Hide/show the street view overlay
        function streetGridClick(a)
        {
            if (a.checked)
            {
                map.addOverlay(streetOverlay);
            }
            else
            {
                map.removeOverlay(streetOverlay);
            }
        }
        
        function layerShowClick(a)
        {
            if (a.checked)
            {
                map.addOverlay(geoXmlLayer);
            }
            else
            {
                map.removeOverlay(geoXmlLayer);
            }
        }

        // when the main window is resized resize map and panorama
        function resizeMap()
        {
            // internet explorer ?
            var agent = navigator.userAgent.toLowerCase();
            if (agent.indexOf("msie")!=-1)
            {
                isIE = true;
            }

            if (isIE)
            {
                windowWidth = parseInt(document.documentElement.clientWidth);
                windowHeight = parseInt(document.documentElement.clientHeight);
            }
            else
            {
                windowWidth = parseInt(window.innerWidth);
                windowHeight = parseInt(window.innerHeight);
            }
            

            // chrome bugfix
            if (windowHeight > 0)
            {
                // ok
            }
            else
            {
                return;
            }            

            var mapDiv = eID("mapDiv");
            var panDiv = eID("panDiv");
            
            if (mapDiv)
            {
                mapDiv.style.width = (windowWidth) + "px";
                var h = (windowHeight - 50);
                if (h > 0)
                {
                    mapDiv.style.height = h + "px";
                }
            }
            
            if (panDiv)
            {            
                panDiv.style.width = (windowWidth) + "px";
                var h = (windowHeight - 52);
                if (h > 0)
                {
                    panDiv.style.height = h + "px";
                }
            }
            
            if (map)
            {
                map.checkResize();
                if (latlng)
                {
                    map.setCenter(latlng);
                }
            }
            
            if (panorama)
            {
                panorama.checkResize();
            }
        }

        // tidy up when closing the page        
        function closeMap()
        {
            if (panorama)
            {
                panorama.remove();
            }
            GUnload();
        }
        
        
        // show marker tooltip on the map adjacent to the marker
        function showTooltip()
        {
            if (tooltip && !dragging)
            {
                var zoom = map.getZoom();
                
                var l_pt = map.getCurrentMapType().getProjection().fromLatLngToPixel(map.fromDivPixelToLatLng(new GPoint(0,0),true), zoom);
                var l_offset = map.getCurrentMapType().getProjection().fromLatLngToPixel(latlng, zoom);
                var l_size = new GSize(l_offset.x - l_pt.x + 16, l_offset.y - l_pt.y);
                var l_pos = new GControlPosition(G_ANCHOR_TOP_LEFT, l_size); 
                
                l_pos.apply(tooltip);
                tooltip.style.visibility = "visible";
            }
	    }

        // hide the marker tooltip
        function hideTooltip()
        {
            if (tooltip)
            {
	            tooltip.style.visibility = "hidden";
	        }
        }

        // utils
        function eID(id)
        {
            return document.getElementById(id); 
        }   
            
        function getParameter(q, n)
        {
           var r = "";
           if (q && q.length > 0 && n && n.length)
           {
                var p = n + "=";
                var b = q.indexOf(p);
                if (b != -1)
                {
                    b += p.length;
                    var e = q.indexOf("&" , b);      
                    if (e == -1) e = n.length;
                    r = unescape(q.substring(b,e));
               }
           }    
           return r;
        }
            
        function formatTooltip(a)
        {
            var text = "";
            
            for (var i=0; i<a.length; i++)
            {
                var c = a.substr(i,1);
                if (c == ' ' || c == '+')
                {
                    text += "&nbsp;";
                }
                else
                {
                    text  += c;
                }
            }
            
            return text;
        }
        
        var ffLookup = [ 1, 10, 100, 1000, 10000, 100000, 1000000, 10000000 ];
        
        function formatFloat(a, p)
        {
            var m = ffLookup[p];
            return parseInt(a * m) / m;
        }

   </script>
    <script type="text/javascript">
        window.onload=initMap
        window.onresize=resizeMap
        window.onunload=closeMap
    </script>

</head>
<body>

   <table cellspacing="0" cellpadding="0" style="width:100%">
   		<tr>
   			<td style="width:100%" colspan="2">
   			
    	    	<table cellspacing="0" cellpadding="0" style="width:100%">
    	    	<tr style="height:25px; font-size:13px">
    	    		<td style="padding:2px; width:120px">
              			<input id="streetGrid" type="checkbox" checked="true" onclick="streetGridClick(this)" /> Street Overlay
        			</td>
        			<td style="padding:2px">
              			<input id="layerShow" type="checkbox" checked="true" onclick="layerShowClick(this)" /><span id="layerTitle"></span>    
        			</td>
        			<td align="right" style="width:220px">	        
            			<a id="m0" class="menuoff" href="javascript:setMode(0)" title="Show Map">Map</a> &nbsp;
            			<a id="m1" class="menuoff" href="javascript:setMode(1)" title="Show Street View" >Street View</a> &nbsp;
            			<a class="menuoff" href="javascript:viewRestore()"  title="Return to the initial view location" >Restore</a>&nbsp;
        			</td>
        		</tr>
        		</table>                
      
	        	<div id="mapFrame">
                	<div id="mapDiv" ></div>               
        		</div>

        		<div id="panFrame" style="display:none">
            		<div id="panDiv"></div>
    		    </div>
                
    		</td>
    	</tr>
    	<tr style="height:5px"><td></td></tr>

    </table>
    
</body>
</html>




