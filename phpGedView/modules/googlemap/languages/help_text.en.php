<?php
/**
 * English Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  PGV Development Team
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
 * @subpackage GoogleMap
 * @version $Id$
 */
if (preg_match("/help_text\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
  print "You cannot access a language file directly.";
  exit;
}

$pgv_lang["GOOGLEMAP_API_KEY"]          = "Google-map API key";
$pgv_lang["GOOGLEMAP_API_KEY_help"]     = "~#pgv_lang[GOOGLEMAP_API_KEY]#~<br /><br />Insert your Google Map API key here.  You can request a key here: <a target=\"_blank\" href=\"http://www.google.com/apis/maps/\">http://www.google.com/apis/maps/</a>";

$pgv_lang["GOOGLEMAP_MAP_TYPE"]         = "Google-map type";
$pgv_lang["GOOGLEMAP_MAP_TYPE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_TYPE]#~<br /><br />The type of map that will be shown by default. This can be Map, Satellite of Hybrid.";

$pgv_lang["GOOGLEMAP_MAP_SIZE"]         = "Google-map size";
$pgv_lang["GOOGLEMAP_MAP_SIZE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_SIZE]#~<br /><br />The size of the map (in pixels) as shown on the Individual page.";

$pgv_lang["GOOGLEMAP_MAP_ZOOM"]         = "Google-map zoom factor";
$pgv_lang["GOOGLEMAP_MAP_ZOOM_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_ZOOM]#~<br /><br />Minimum en maximum zoom factor for the Google map. 1 is the full map, 15 is single house. Note that 15 is only availble at certain locations.";

$pgv_lang["GOOGLEMAP_PRECISION"]        = "Precision of the latitude and longitude";
$pgv_lang["GOOGLEMAP_PRECISION_help"]   = "~#pgv_lang[GOOGLEMAP_PRECISION]#~<br /><br />This specifies the precision of the different levels when entering new locations. For example a country will be specified with precision 0 (=0 digits after the decimal point), while a town needs 3 or 4 digits.";

$pgv_lang["PL_EDIT_LOCATION"]           = "Edit or delete location";
$pgv_lang["PL_EDIT_LOCATION_help"]      = "Here you can edit the location or delete the location. When you click on Edit a new window will open where you can change the values of the location.<br>If you click on the delete-icon the record will be deleted. This can only be done if there are no records connected to this location. If no records are connected the delete-icon is active, otherwise it is inactive.";

$pgv_lang["PL_ADD_LOCATION"]            = "Add location";
$pgv_lang["PL_ADD_LOCATION_help"]       = "Use this to add a place to the location table. The location will be added at this level.";

$pgv_lang["PL_IMPORT_GEDCOM"]           = "Import locations from GEDCOM";
$pgv_lang["PL_IMPORT_GEDCOM_help"]      = "Import location-data from current GEDCOM. The current GEDCOM will be scanned and all locations will be added to the table. If latitude and longitude are available these will also be imported.";

$pgv_lang["PL_IMPORT_ALL_GEDCOM"]       = "Import locations from all GEDCOMs";
$pgv_lang["PL_IMPORT_ALL_GEDCOM_help"]  = "Import location-data from all GEDCOMs. All GEDCOMs will be scanned and all locations will be added to the table. If latitude and longitude are available these will also be imported.";

$pgv_lang["PL_IMPORT_FILE"]             = "Import locations from file";
$pgv_lang["PL_IMPORT_FILE_help"]        = "Import location data from a file. The file should be formatted as CSV file on the local computer. The record seperator used within the lines is ';'.";

$pgv_lang["PL_EXPORT_FILE"]             = "Export locations to file";
$pgv_lang["PL_EXPORT_FILE_help"]        = "Export location data to a file. This option will save the data from the current view and all dependant data to a file. This means that if a country is selected and the states are shown, this option will save the data of the states, all the counties that are defined in those states and all places within those counties.";

$pgv_lang["PL_EXPORT_ALL_FILE"]         = "Export all locations to file";
$pgv_lang["PL_EXPORT_ALL_FILE_help"]    = "Export all location data to a file. This option will save all location data and transfer it to the local computer.";

// Help texts for places_edit.php

$pgv_lang["PLE_PLACES"]                 = "Enter placename";
$pgv_lang["PLE_PLACES_help"]            = "Here you can enter or change the name of the place.";

$pgv_lang["PLE_PRECISION"]              = "Enter precision";
$pgv_lang["PLE_PRECISION_help"]         = "Here you can enter the prcision. Based on this setting the number of digits that will be used in the latitude and longitude is determined.";

$pgv_lang["PLE_LATLON_CTRL"]            = "Enter latitude or Longitude";
$pgv_lang["PLE_LATLON_CTRL_help"]       = "Here the latitude and longitude can be entered. First select the area you want to set (E/W or N/S). Next enter the value for latitude or longitude. This should be a decimal value.<br>The decimal value can be determined by converting the minutes and seconds uding the following formula:<br>degrees_decimal = ((seconds / 60) + minutes) / 60 + degrees.";

$pgv_lang["PLE_ZOOM"]                   = "Enter zoom level";
$pgv_lang["PLE_ZOOM_help"]              = "Here the zoomlevel can be entered. This value will be used as the minimal value when displaying this location on a map.";

$pgv_lang["PLE_ICON"]                   = "Select an icon";
$pgv_lang["PLE_ICON_help"]              = "Here an icon can be set or removed. Using this link a flag can be selected. When this location is shown, this flag will be displayed.";

$pgv_lang["PLE_FLAGS"]                  = "Select flag";
$pgv_lang["PLE_FLAGS_help"]             = "Using the pulldown menu it is possible to select a country, of which a flag can be selected. If no flags are shown, then there are no flags defined for this country.";

?>
