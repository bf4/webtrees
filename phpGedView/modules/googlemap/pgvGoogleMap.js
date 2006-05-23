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
            map.setCenter(new GLatLng(clat, clng));
            if (zoomlevel == 1) {
                zoomlevel = 5;
            }
            if (zoomlevel > 14) {
                zoomlevel = 14;
            }
            map.checkResize();
            map.setCenter(new GLatLng(clat, clng), zoomlevel-1);
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
            map.addControl(new GMapTypeControl());
            map.addControl(new GScaleControl()) ;
            map.setCenter(new GLatLng( 0.0, 0.0), 1, maptype );
            mapready = 1;
            SetMarkersAndBounds();
            ResizeMap();
            // Our info window content
      }
    }

