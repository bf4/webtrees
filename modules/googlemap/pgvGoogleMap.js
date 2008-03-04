/**
 * Javascript module for Googlemap
 *
 * This module contains the Javasript functions needed by the Googlemap
 * module of phpGedView
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003  John Finlay and Others
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
 * @package PhpGedView
 * @subpackage Display
 * @version $Id: pgvGoogleMap.js$
 * $Id$
 */
 
 /*
 	var G_INCOMPAT = false;
 	function GScript(src) {
 		document.write('<' + 'script src="' + src + '"' +' type="text/javascript"><' + '/script>');
 	}
 	function GBrowserIsCompatible() {
 		if (G_INCOMPAT) return false;
 		if (!window.RegExp) return false;
 		var AGENTS = ["opera","msie","safari","firefox","netscape","mozilla"];
 		var agent = navigator.userAgent.toLowerCase();
 		for (var i = 0; i < AGENTS.length; i++) {
 			var agentStr = AGENTS[i];
 			if (agent.indexOf(agentStr) != -1) {
 				var versionExpr = new RegExp(agentStr + "[ \/]?([0-9]+(\.[0-9]+)?)");
 				var version = 0;
 				if (versionExpr.exec(agent) != null) {
 					version = parseFloat(RegExp.$1);
 				}
 				if (agentStr == "opera") return version >= 7;
 				if (agentStr == "safari") return version >= 125;
 				if (agentStr == "msie") return (version >= 5.5 &&agent.indexOf("powerpc") == -1);
 				if (agentStr == "netscape") return version > 7;
 				if (agentStr == "firefox") return version >= 0.8;
 			}
 		}
 		return !!document.getElementById;
 	}
 	
 	function GLoad() {
 		if (!GValidateKey("f6c97a7f64063cfee7c2dc2157847204d4dbf093")) {
 			G_INCOMPAT = true;
 			alert("The Google Maps API key used on this web site was registered for a different web site. You can generate a new key for this web site at http://www.google.com/apis/maps/.");
 			return;
 		}
 		GLoadApi(["http://mt0.google.com/mt?n=404&v=ap.31&","http://mt1.google.com/mt?n=404&v=ap.31&","http://mt2.google.com/mt?n=404&v=ap.31&","http://mt3.google.com/mt?n=404&v=ap.31&"], ["http://kh0.google.com/kh?n=404&v=13&","http://kh1.google.com/kh?n=404&v=13&","http://kh2.google.com/kh?n=404&v=13&","http://kh3.google.com/kh?n=404&v=13&"], ["http://mt0.google.com/mt?n=404&v=apt.32&","http://mt1.google.com/mt?n=404&v=apt.32&","http://mt2.google.com/mt?n=404&v=apt.32&","http://mt3.google.com/mt?n=404&v=apt.32&"],"ABQIAAAAN0eV3Cc4gLQrtHsK2tgfahT2yXp_ZAY8_ufC3CFXhHIE1NvwkxTdpBgSyl9HME4xo0Mw9fVXAj6rpA","","",true);
 		if (window.GJsLoaderInit) {
 			GJsLoaderInit("http://maps.google.com/mapfiles/maps2.69.api.js");
 		}
 	}
 	
 	function GUnload() {
 		if (window.GUnloadApi) {
 			GUnloadApi();
 		}
 	}
 	
 	var _mFlags = {};
 	var _mHost = "http://maps.google.com";
 	var _mUri = "/maps";
 	var _mDomain = "google.com";
 	var _mStaticPath = "http://www.google.com/intl/en_us/mapfiles/";
 	var _mTermsUrl = "http://www.google.com/intl/en_us/help/terms_local.html";
 	var _mTerms = "Terms of Use";
 	var _mMapMode = "Map";
 	var _mMapModeShort = "Map";
 	var _mMapError = "We are sorry, but we don\'t have maps at this zoom level for this region.<p>Try zooming out for a broader look.</p>";
 	var _mSatelliteMode = "Satellite";
 	var _mSatelliteModeShort = "Sat";
 	var _mSatelliteError = "We are sorry, but we don\'t have imagery at this zoom level for this region.<p>Try zooming out for a broader look.</p>";
 	var _mHybridMode = "Hybrid";
 	var _mHybridModeShort = "Hyb";
 	var _mSatelliteToken = "fzwq1F3_OogoSZbQ3TG3pSEQtHOlpJ_0T0k70g";
 	var _mZoomIn = "Zoom In";
 	var _mZoomOut = "Zoom Out";
 	var _mZoomSet = "Click to set zoom level";
 	var _mZoomDrag = "Drag to zoom";
 	var _mPanWest = "Pan left";
 	var _mPanEast = "Pan right";
 	var _mPanNorth = "Pan up";
 	var _mPanSouth = "Pan down";
 	var _mLastResult = "Return to the last result";
 	var _mMapCopy = "Map data &#169;2006 ";
 	var _mSatelliteCopy = "Imagery &#169;2006 ";
 	var _mGoogleCopy = "&#169;2006 Google";
 	var _mKilometers = "km";
 	var _mMiles = "mi";
 	var _mMeters = "m";
 	var _mFeet = "ft";
 	var _mPreferMetric = false;
 	var _mTabBasics = "Address";
 	var _mTabDetails = "Details";
 	var _mDecimalPoint = '.';
 	var _mThousandsSeparator = ',';
 	var _mUsePrintLink = 'To see all the details that are visible on the screen,use the "Print" link next to the map.';
 	var _mPrintSorry = '';
 	var _mMapPrintUrl = 'http://www.google.com/mapprint';
 	var _mPrint = 'Print';
 	var _mOverview = 'Overview';
 	var _mStart = 'Start';
 	var _mEnd = 'End';
 	var _mStep = 'Step %1$s';
 	var _mStop = 'Destination %1$s';
 	var _mHideAllMaps = 'Hide Maps';
 	var _mShowAllMaps = 'Show All Maps';
 	var _mUnHideMaps = 'Show Maps';
 	var _mShowLargeMap = 'Show original map view.';
 	var _mmControlTitle = null;
 	var _mAutocompleteFrom = 'from';
 	var _mAutocompleteTo = 'to';
 	var _mAutocompleteNearRe = '^(?:(?:.*?)&#92;s+)(?:(?:in|near|around|close to):?&#92;s+)(.+)$';
 	var _mSvgEnabled = true;
 	var _mSvgForced = false;
 	var _mStreetMapAlt = 'Show street map';
 	var _mSatelliteMapAlt = 'Show satellite imagery';
 	var _mHybridMapAlt = 'Show imagery with street names';
 	var _mLogInfoWinExp = true;
 	var _mLogPanZoomClks = false;
 	var _mLogWizard = true;
 	
 	function GLoadMapsScript() {
 		if (GBrowserIsCompatible()) {
 			GScript("http://maps.google.com/mapfiles/maps2.78.api.js");
 		}
 	}
 	
 	GLoadMapsScript();
 */
    var markers   = [];
    var Boundaries = new GLatLngBounds();
    var map;
    var mapready = 0;

    function highlight(index, tab) {
        GEvent.trigger( markers[index], "click", tab);
    } 

    function SetBoundaries(MapBounds) {
        Boundaries = MapBounds;
    }

    function ResizeMap() {
        var clat = 0.0;
        var clng = 0.0;
        var zoomlevel = 1;

        if (mapready == 1)
        {
            clat = (Boundaries.getNorthEast().lat() + Boundaries.getSouthWest().lat())/2;
            clng = (Boundaries.getNorthEast().lng() + Boundaries.getSouthWest().lng())/2;
            zoomlevel = map.getBoundsZoomLevel(Boundaries);
            for(i = 0; ((i < 10) && (zoomlevel == 1)); i++) {
                zoomlevel = map.getBoundsZoomLevel(Boundaries);
            }
            zoomlevel = zoomlevel-1;
            map.setCenter(new GLatLng(clat, clng));
            if (zoomlevel < minZoomLevel) {
                zoomlevel = minZoomLevel;
            }
            if (zoomlevel > startZoomLevel) {
                zoomlevel = startZoomLevel;
            }
            map.checkResize();
            map.setCenter(new GLatLng(clat, clng), zoomlevel);
            map.savePosition();
        }
    }

    function AddMarker(Marker) {
        map.addOverlay(Marker);
        markers.push(Marker);
    }

    function loadMap(maptype) {
        var pointArray = [];
        if (GBrowserIsCompatible()) {
            map = new GMap2(document.getElementById("map_pane"));
            map.addControl(new GLargeMapControl());
            map.addControl(new GScaleControl()) ;
            map.setCenter(new GLatLng( 0.0, 0.0), 0, maptype );
            mapready = 1;
            ResizeMap();
            // Our info window content
      }
    }
    

