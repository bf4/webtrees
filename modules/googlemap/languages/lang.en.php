<?php
/*=================================================
   charset=utf-8
   Project:         phpGedView
   File:            lang.en.php
   Author:          Johan Borkhuis
   Comments:        English Language file for Google map module
===================================================*/


$pgv_lang["googlemap"]              = "Map";
$pgv_lang["no_gmtab"]               = "No map data for this person";
$pgv_lang["gm_disabled"]            = "Googlemap module disabled";

$pgv_lang['gm_redraw_map']          = "Redraw map";
$pgv_lang["gm_map"]                 = "Map";
$pgv_lang["gm_satellite"]           = "Satellite";
$pgv_lang["gm_hybrid"]              = "Hybrid";

// Configuration texts
$pgv_lang["gm_manage"]              = "Manage Googlemap configuration";
$pgv_lang["configure_googlemap"]    = "Googlemap Configuration";
$pgv_lang["gm_admin_error"]         = "Page only for Administrators";
$pgv_lang["gm_db_error"]            = "placelocation table not found in database";
$pgv_lang["gm_table_created"]       = "placelocation table created";
$pgv_lang["googlemap_enable"]       = "Enable Googlemap";
$pgv_lang["googlemapkey"]           = "Google Map API key";
$pgv_lang["gm_map_type"]            = "Default map type";
$pgv_lang["gm_map_size"]            = "Size of map (in pixels)";
$pgv_lang["gm_map_size_x"]          = "Width";
$pgv_lang["gm_map_size_y"]          = "Height";
$pgv_lang["gm_map_zoom"]            = "Zoom factor of map";
$pgv_lang["gm_digits"]              = "digits";
$pgv_lang["gm_min"]                 = "Min.";
$pgv_lang["gm_max"]                 = "Max.";
$pgv_lang["gm_default_level0"]      = "Default top level value";
$pgv_lang["gm_nof_levels"]          = "Number of levels";
$pgv_lang["gm_config_per_level"]    = "Configuration per level";
$pgv_lang["gm_name_prefix"]         = "Prefix";
$pgv_lang["gm_name_postfix"]        = "Postfix";
$pgv_lang["gm_name_pre_post"]       = "Prefix / Postfix order";
$pgv_lang["gm_level"]               = "Level";
$pgv_lang["gm_pp_none"]             = "No pre/postfix";
$pgv_lang["gm_pp_n_pr_po_b"]        = "Normal, prefix, postfix, both";
$pgv_lang["gm_pp_n_po_pr_b"]        = "Normal, postfix, prefix, both";
$pgv_lang["gm_pp_pr_po_b_n"]        = "Prefix, postfix, both, normal";
$pgv_lang["gm_pp_po_pr_b_n"]        = "Postfix, prefix, both, normal";
$pgv_lang["gm_pp_pr_po_n_b"]        = "Prefix, postfix, normal, both";
$pgv_lang["gm_pp_po_pr_n_b"]        = "Postfix, prefix, normal, both";
$pgv_lang["googlemap_coord"]        = "Display Map Co-ordinates";


// Texts used on the Places location page
$pgv_lang["edit_place_locations"]   = "Edit geographic place locations";
$pgv_lang["pl_no_places_found"]     = "No places found";
$pgv_lang["pl_zoom_factor"]         = "Zoom factor";
$pgv_lang["pl_place_icon"]          = "Icon";
$pgv_lang["pl_edit"]                = "Edit geographic location";
$pgv_lang["pl_add_place"]           = "Add place";
$pgv_lang["pl_import_gedcom"]       = "Import from current GEDCOM";
$pgv_lang["pl_import_all_gedcom"]   = "Import from all GEDCOMs";
$pgv_lang["pl_import_file"]         = "Import from file";
$pgv_lang["pl_export_file"]         = "Export current view to file";
$pgv_lang["pl_export_all_file"]     = "Export all locations to file";
$pgv_lang["pl_north_short"]         = "N";
$pgv_lang["pl_south_short"]         = "S";
$pgv_lang["pl_east_short"]          = "E";
$pgv_lang["pl_west_short"]          = "W";
$pgv_lang["pl_places_filename"]     = "File containing places (CSV)";
$pgv_lang["pl_clean_db"]            = "Clear all place-locations before import?";
$pgv_lang["pl_update_only"]         = "Update existing places only?";
$pgv_lang["pl_overwrite_data"]      = "Overwrite location-data with data from file?";
$pgv_lang["pl_no_places_found"]     = "No places found";
$pgv_lang["pl_use_this_value"]      = "Use this value";
$pgv_lang["pl_precision"]           = "Precision";
$pgv_lang["pl_country"]             = "Country";
$pgv_lang["pl_state"]               = "State";
$pgv_lang["pl_city"]                = "City";
$pgv_lang["pl_neighborhood"]        = "Neighborhood";
$pgv_lang["pl_house"]               = "House";
$pgv_lang["pl_max"]                 = "Max";

$pgv_lang["pl_flag"]                = "Flag";
$pgv_lang["flags_edit"]             = "Select flag";
$pgv_lang["pl_change_flag"]         = "Change flag";
$pgv_lang["pl_remove_flag"]         = "Remove flag";

$pgv_lang["pl_remove_location"]     = "Remove this location?";
$pgv_lang["pl_delete_error"]        = "Location not removed: this location contains sub-locations";
$pgv_lang["list_inactive"]        	= "Click here to show inactive places";

//Placecheck specific text
$pgv_lang["placecheck"]				= "Place Check";
$pgv_lang['placecheck_text']		= "This will list all the places from the selected gedcom file";
$pgv_lang['placecheck_top']			= "Top Level Place";
$pgv_lang['placecheck_one']			= "Level One Place";
$pgv_lang['placecheck_select1']		= "Select Top Level...";
$pgv_lang['placecheck_select2']		= "Select Next Level...";
$pgv_lang['placecheck_key']			= "Key to colors used below";
$pgv_lang['placecheck_key1']		= "this place and its coordinates do not exist in the googlemap tables";
$pgv_lang['placecheck_key2']		= "this place exists in the googlemap tables, but it has no coordinates";
$pgv_lang['placecheck_head']		= "Place list for gedcom file";
$pgv_lang['placecheck_gedheader']	= "Gedcom File Place Data<br/>(2 PLAC tag)";
$pgv_lang['placecheck_gm_header']	= "GoogleMap Places Table Data";
$pgv_lang['placecheck_unique']		= "Total unique places";
$pgv_lang["placecheck_zoom"]         = "Zoom=";

?>