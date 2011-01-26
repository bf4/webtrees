<?php

?>

<script type="text/javascript">

	if (window.attachEvent) {
		window.attachEvent("onload", function() {
			loadMap(); // Internet Explorer
		});
		window.attachEvent("onunload", function() {
			GUnload(); // Internet Explorer
		});
	} else {
		window.addEventListener("load", function() {
			loadMap(); // Firefox and standard browsers
		}, false);
		window.addEventListener("unload", function() {
			GUnload(); // Firefox and standard browsers
		}, false);
	}
	var childplaces = [];
	var geocoder = new GClientGeocoder();

	function updateMap() {
		var point;
		var zoom;
		var latitude;
		var longitude;
		var i;

		document.editplaces.save1.disabled = "";
		document.editplaces.save2.disabled = "";
		zoom = parseInt(document.editplaces.NEW_ZOOM_FACTOR.value);

		prec = 20;
		for (i=0;i<document.editplaces.NEW_PRECISION.length;i++) {
			if (document.editplaces.NEW_PRECISION[i].checked) {
				prec = document.editplaces.NEW_PRECISION[i].value;
			}
		}
		if ((document.editplaces.NEW_PLACE_LATI.value == "") ||
			(document.editplaces.NEW_PLACE_LONG.value == "")) {
			latitude = parseFloat(document.editplaces.parent_lati.value).toFixed(prec);
			longitude = parseFloat(document.editplaces.parent_long.value).toFixed(prec);
			point = new GLatLng (latitude, longitude);
			map.clearOverlays();
		} else {
			latitude = parseFloat(document.editplaces.NEW_PLACE_LATI.value).toFixed(prec);
			longitude = parseFloat(document.editplaces.NEW_PLACE_LONG.value).toFixed(prec);
			document.editplaces.NEW_PLACE_LATI.value = latitude;
			document.editplaces.NEW_PLACE_LONG.value = longitude;

			if (latitude < 0.0) {
				latitude = latitude * -1;
				document.editplaces.NEW_PLACE_LATI.value = latitude;
			}
			if (longitude < 0.0) {
				longitude = longitude * -1;
				document.editplaces.NEW_PLACE_LONG.value = longitude;
			}
			if (document.editplaces.LATI_CONTROL.value == "PL_S") {
				latitude = latitude * -1;
			}
			if (document.editplaces.LONG_CONTROL.value == "PL_W") {
				longitude = longitude * -1;
			}

			point = new GLatLng (latitude, longitude);

			map.clearOverlays();

			if (document.editplaces.icon.value == "") {
				map.addOverlay(new GMarker(point));
			}
			else {
				var flagicon = new GIcon();
				flagicon.image = document.editplaces.icon.value;
				flagicon.shadow = "modules/googlemap/images/flag_shadow.png";
				flagicon.iconSize = new GSize(25, 15);
				flagicon.shadowSize = new GSize(35, 45);
				flagicon.iconAnchor = new GPoint(1, 45);
				flagicon.infoWindowAnchor = new GPoint(5, 1);
				map.addOverlay(new GMarker(point, flagicon));
			}
		}

		map.setCenter(point, zoom);
		//document.getElementById('resultDiv').innerHTML = "";

		var childicon = new GIcon();
		childicon.image = "http://labs.google.com/ridefinder/images/mm_20_green.png";
		childicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
		childicon.iconSize = new GSize(12, 20);
		childicon.shadowSize = new GSize(22, 20);
		childicon.iconAnchor = new GPoint(6, 20);
		childicon.infoWindowAnchor = new GPoint(5, 1);
		for (i=0; i < childplaces.length; i++) {
			map.addOverlay(childplaces[i]);
		}
	}

	function Map_type() {}
	Map_type.prototype = new GControl();

	Map_type.prototype.refresh = function() {
		if (this.map.getCurrentMapType() != G_NORMAL_MAP)
			this.button1.className = 'non_active';
		else
			this.button1.className = 'active';
		if (this.map.getCurrentMapType() != G_SATELLITE_MAP)
			this.button2.className = 'non_active';
		else
			this.button2.className = 'active';
		if (this.map.getCurrentMapType() != G_HYBRID_MAP)
			this.button3.className = 'non_active';
		else
			this.button3.className = 'active';
		if (this.map.getCurrentMapType() != G_PHYSICAL_MAP)
			this.button4.className = 'non_active';
		else
			this.button4.className = 'active';
	}

	Map_type.prototype.initialize = function(place_map) {
		var list  = document.createElement("ul");
		list.id = 'map_type';

		var button1 = document.createElement('li');
		var button2 = document.createElement('li');
		var button3 = document.createElement('li');
		var button4 = document.createElement('li');

		button1.innerHTML = '<?php echo WT_I18N::translate('Map'); ?>';
		button2.innerHTML = '<?php echo WT_I18N::translate('Satellite'); ?>';
		button3.innerHTML = '<?php echo WT_I18N::translate('Hybrid'); ?>';
		button4.innerHTML = '<?php echo WT_I18N::translate('Terrain'); ?>';

		button1.onclick = function() { map.setMapType(G_NORMAL_MAP); return false; };
		button2.onclick = function() { map.setMapType(G_SATELLITE_MAP); return false; };
		button3.onclick = function() { map.setMapType(G_HYBRID_MAP); return false; };
		button4.onclick = function() { map.setMapType(G_PHYSICAL_MAP); return false; };

		list.appendChild(button1);
		list.appendChild(button2);
		list.appendChild(button3);
		list.appendChild(button4);

		this.button1 = button1;
		this.button2 = button2;
		this.button3 = button3;
		this.button4 = button4;
		this.map = map;
		map.getContainer().appendChild(list);
		return list;
	}

	Map_type.prototype.getDefaultPosition = function()
	{
		return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(2, 2));
	}

	function loadMap() {
		var zoom;
		
		
	if (GBrowserIsCompatible()) {

			map = new GMap2(document.getElementById("map_pane"));
			map.addControl(new GSmallZoomControl3D());
			map.addControl(new GScaleControl()) ;
			var bounds = new GLatLngBounds();
			var map_type;
			map_type = new Map_type();
			map.addControl(map_type);
			GEvent.addListener(map, 'maptypechanged', function() {
				map_type.refresh();
			});
			GEvent.addListener(map, 'click', function(overlay, point) {
				if (overlay) {
					//probably not needed in this case
					//map.removeOverlay(overlay);
				} else if (point) {
					map.clearOverlays();
					// Create our "tiny" yellow marker icon where the user clicked,
					// The full size red marker is at the stored coordinates.
					var smicon = new GIcon();
					smicon.image = "http://labs.google.com/ridefinder/images/mm_20_yellow.png";
					smicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
					smicon.iconSize = new GSize(12, 20);
					smicon.shadowSize = new GSize(22, 20);
					smicon.iconAnchor = new GPoint(6, 20);
					smicon.infoWindowAnchor = new GPoint(5, 1);

					map.panTo(point);
					prec = 20;
					for (i=0;i<document.editplaces.NEW_PRECISION.length;i++) {
						if (document.editplaces.NEW_PRECISION[i].checked) {
							prec = document.editplaces.NEW_PRECISION[i].value;
						}
					}

					if (point.y < 0.0) {
						document.editplaces.NEW_PLACE_LATI.value = (point.y.toFixed(prec) * -1);
						document.editplaces.LATI_CONTROL.value = "PL_S";
					} else {
						document.editplaces.NEW_PLACE_LATI.value = point.y.toFixed(prec);
						document.editplaces.LATI_CONTROL.value = "PL_N";
					}
					if (point.x < 0.0) {
						document.editplaces.NEW_PLACE_LONG.value = (point.x.toFixed(prec) * -1);
						document.editplaces.LONG_CONTROL.value = "PL_W";
					} else {
						document.editplaces.NEW_PLACE_LONG.value = point.x.toFixed(prec);
						document.editplaces.LONG_CONTROL.value = "PL_E";
					}
					newval = new GLatLng (point.y.toFixed(prec), point.x.toFixed(prec));
					if (document.editplaces.icon.value == "") {
						map.addOverlay(new GMarker(newval));
					}
					else {
						var flagicon = new GIcon();
						flagicon.image = document.editplaces.icon.value;
						flagicon.shadow = "modules/googlemap/images/flag_shadow.png";
						flagicon.iconSize = new GSize(25, 15);
						flagicon.shadowSize = new GSize(35, 45);
						flagicon.iconAnchor = new GPoint(1, 45);
						flagicon.infoWindowAnchor = new GPoint(5, 1);
						map.addOverlay(new GMarker(newval, flagicon));
					}
					// Trying to get the smaller yellow icon drawn in front.
					map.addOverlay(new GMarker(point, smicon));
					//document.getElementById('resultDiv').innerHTML = "";
					document.editplaces.save1.disabled = "";
					document.editplaces.save2.disabled = "";
					var childicon = new GIcon();
					childicon.image = "http://labs.google.com/ridefinder/images/mm_20_green.png";
					childicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
					childicon.iconSize = new GSize(12, 20);
					childicon.shadowSize = new GSize(22, 20);
					childicon.iconAnchor = new GPoint(6, 20);
					childicon.infoWindowAnchor = new GPoint(5, 1);
					for (i=0; i < childplaces.length; i++) {
						map.addOverlay(childplaces[i]);
					}
				}
			});
			GEvent.addListener(map, "moveend", function() {
				document.editplaces.NEW_ZOOM_FACTOR.value = map.getZoom();
			});
			<?php if (($place_long == null) || ($place_lati == null)) { ?>
				map.setCenter(new GLatLng( <?php echo $parent_lati, ", ", $parent_long, "), ", $zoomfactor; ?>, G_NORMAL_MAP );
			<?php } else { ?>
				map.setCenter(new GLatLng( <?php echo $place_lati, ", ", $place_long, "), ", $zoomfactor; ?>, G_NORMAL_MAP );
			<?php } ?>

// === Script timeout problem is here. The following code is supressed ============================= 
<?php /*	
// =================================================================================================

<?php   if ($level < 3) { ?>
			var childicon = new GIcon();
			childicon.image = "http://labs.google.com/ridefinder/images/mm_20_green.png";
			childicon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
			childicon.iconSize = new GSize(12, 20);
			childicon.shadowSize = new GSize(22, 20);
			childicon.iconAnchor = new GPoint(6, 20);
			childicon.infoWindowAnchor = new GPoint(5, 1);
<?php
			$rows=
				WT_DB::prepare("SELECT pl_place, pl_lati, pl_long, pl_icon FROM `##placelocation` WHERE pl_parent_id=?")
				->execute(array($placeid))
				->fetchAll();
			$i = 0;
			foreach ($rows as $row) {
				if ($row->pl_lati!==null && $row->pl_long!==null) {
					//delete leading zero
					$pl_lati = str_replace(array('N', 'S', ','), array('', '-', '.') , $row->pl_lati);
					$pl_long = str_replace(array('E', 'W', ','), array('', '-', '.') , $row->pl_long);
					if ($pl_lati >= 0) {
						$row->pl_lati = abs($pl_lati);
					} elseif ($pl_lati < 0) {
						$row->pl_lati = "-".abs($pl_lati);
					}
					if ($pl_long >= 0) {
						$row->pl_long = abs($pl_long);
					} elseif ($pl_long < 0) {
						$row->pl_long = "-".abs($pl_long);
					}

					echo " childplaces.push(new GMarker(new GLatLng(", $row->pl_lati, ", ", $row->pl_long, "), childicon));\n";
					echo " GEvent.addListener(childplaces[", $i, "], \"click\", function() {\n";
					echo "             childplaces[", $i, "].openInfoWindowHtml(\"<td width='100%'><div class='iwstyle' style='width: 250px;'><br />", addslashes($row->pl_place), "<br /><br /></div>\")});\n";
					echo " map.addOverlay(childplaces[", $i, "]);\n";
					echo " bounds.extend(new GLatLng(", $row->pl_lati, ", ", $row->pl_long, "));\n";
					$i++;
					echo " map.setCenter(bounds.getCenter());\n";
				}
			}
		}
			
// =================================================================================================			
*/ ?><?php
// === end supressed code ==========================================================================

		if ($show_marker == true) {
			if (($place_icon == NULL) || ($place_icon == "")) {
				if (($place_lati == null) || ($place_long == null)) { ?>
					var icon_type = new GIcon();
					icon_type.image = "modules/googlemap/images/marker_yellow.png";
					icon_type.shadow = "modules/googlemap/images/shadow50.png";
					icon_type.iconSize = new GSize(20, 34);
					icon_type.shadowSize = new GSize(37, 34);
					icon_type.iconAnchor = new GPoint(10, 34);
					icon_type.infoWindowAnchor = new GPoint(5, 1);
					map.addOverlay(new GMarker(new GLatLng(<?php echo $parent_lati, ", ", $parent_long; ?>), icon_type));
					<?php } else { ?>
						map.addOverlay(new GMarker(new GLatLng(<?php echo $place_lati, ", ", $place_long; ?>)));
					<?php }
				} else { ?>
					var flagicon = new GIcon();
					flagicon.image = "<?php echo $place_icon; ?>";
					flagicon.shadow = "modules/googlemap/images/flag_shadow.png";
					flagicon.iconSize = new GSize(25, 15);
					flagicon.shadowSize = new GSize(35, 45);
					flagicon.iconAnchor = new GPoint(1, 45);
					flagicon.infoWindowAnchor = new GPoint(5, 1);
					<?php if (($place_lati == null) || ($place_long == null)) { ?>
						map.addOverlay(new GMarker(new GLatLng(<?php echo $parent_lati, ", ", $parent_long; ?>), flagicon));
					<?php } else { ?>
						map.addOverlay(new GMarker(new GLatLng(<?php echo $place_lati, ", ", $place_long; ?>), flagicon));
					<?php }
				}
			} ?>
			// Our info window content

		}
	}

	function edit_close() {
		if (window.opener.showchanges) window.opener.showchanges();
		GUnload();
		window.close();
	}

	function setLoc(lat, lng) {
		prec = 20;
		for (i=0;i<document.editplaces.NEW_PRECISION.length;i++) {
			if (document.editplaces.NEW_PRECISION[i].checked) {
				prec = document.editplaces.NEW_PRECISION[i].value;
			}
		}

		if (lat < 0.0) {
			document.editplaces.NEW_PLACE_LATI.value = (lat.toFixed(prec) * -1);
			document.editplaces.LATI_CONTROL.value = "PL_S";
		} else {
			document.editplaces.NEW_PLACE_LATI.value = lat.toFixed(prec);
			document.editplaces.LATI_CONTROL.value = "PL_N";
		}
		if (lng < 0.0) {
			document.editplaces.NEW_PLACE_LONG.value = (lng.toFixed(prec) * -1);
			document.editplaces.LONG_CONTROL.value = "PL_W";
		} else {
			document.editplaces.NEW_PLACE_LONG.value = lng.toFixed(prec);
			document.editplaces.LONG_CONTROL.value = "PL_E";
		}
		newval = new GLatLng (lat.toFixed(prec), lng.toFixed(prec));
		updateMap();
	}

	function createMarker(point, name, coordinates) {
		var icon = new GIcon();
		icon.image = "modules/googlemap/images/marker_yellow.png";
		icon.shadow = "modules/googlemap/images/shadow50.png";
		icon.iconSize = new GSize(20, 34);
		icon.shadowSize = new GSize(37, 34);
		icon.iconAnchor = new GPoint(10, 34);
		icon.infoWindowAnchor = new GPoint(5, 1);

		var marker = new GMarker(point, icon);
		GEvent.addListener(marker, "click", function() {
			marker.openInfoWindowHtml(name + "<br /><a href=\"javascript:;\" onclick=\"setLoc(" + coordinates[1] + ", " + coordinates[0] + ");\"><?php echo PrintReady(WT_I18N::translate('Use this value')); ?></a></div>");
		});
		return marker;
	}

	function change_icon() {
		window.open('module.php?mod=googlemap&mod_action=flags&countrySelected=<?php echo $selected_country; ?>', '_blank', 'top=50, left=50, width=600, height=500, resizable=1, scrollbars=1');
	return false;
	}

	function remove_icon() {
		document.editplaces.icon.value = "";
		document.getElementById('flagsDiv').innerHTML = "<a href=\"javascript:;\" onclick=\"change_icon();return false;\"><?php echo WT_I18N::translate('Change flag'); ?></a>";
	}

	function addAddressToMap(response) {
	   	map.clearOverlays();
	   	var bounds = new GLatLngBounds();
	   	if (!response || response.Status.code != 200) {
	  		alert("<?php echo WT_I18N::translate('No places found'); ?>");
	   	} else {
			if (response.Placemark.length>0) {
				for (i=0;i<response.Placemark.length;i++) {
					place = response.Placemark[i];
					point = new GLatLng(place.Point.coordinates[1], place.Point.coordinates[0]);
					var name = '<td width=\'100%\'><div class=\'iwstyle\' style=\'width: 250px;\'>' + place.address + '<br />' + '<b><?php echo WT_I18N::translate('Country'); ?>:</b> ' + place.AddressDetails.Country.CountryNameCode;
					var marker = createMarker(point, name, place.Point.coordinates);
					map.addOverlay(marker);
					bounds.extend(point);
				}
				zoomlevel = map.getBoundsZoomLevel(bounds)-1;
				if (zoomlevel < <?php echo $GOOGLEMAP_MIN_ZOOM; ?>) {
					zoomlevel = <?php echo $GOOGLEMAP_MIN_ZOOM; ?>;
				}
				if (zoomlevel > <?php echo $GOOGLEMAP_MAX_ZOOM; ?>) {
					zoomlevel = <?php echo $GOOGLEMAP_MAX_ZOOM; ?>;
				}
				if (document.editplaces.NEW_ZOOM_FACTOR.value<zoomlevel) {
					zoomlevel = document.editplaces.NEW_ZOOM_FACTOR.value;
					if (zoomlevel < <?php echo $GOOGLEMAP_MIN_ZOOM; ?>) {
						zoomlevel = <?php echo $GOOGLEMAP_MIN_ZOOM; ?>;
					}
					if (zoomlevel > <?php echo $GOOGLEMAP_MAX_ZOOM; ?>) {
						zoomlevel = <?php echo $GOOGLEMAP_MAX_ZOOM; ?>;
					}

				}
				map.setCenter(bounds.getCenter(), zoomlevel);
			}
		}
	}

	function showLocation_level(address) {
		address += '<?php if ($level>0) echo ", ", addslashes(PrintReady(implode(', ', array_reverse($where_am_i, true)))); ?>';
		geocoder.getLocations(address, addAddressToMap);
	}

	function showLocation_all(address) {
		geocoder.getLocations(address, addAddressToMap);
	}

	function updatewholename() {
	}

	function paste_char(value, lang, mag) {
		document.editplaces.NEW_PLACE_NAME.value += value;
		language_filter = lang;
		magnify = mag;
	}
	//-->
</script>

<?php

?>
