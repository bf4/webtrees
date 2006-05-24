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

$pgv_lang["GOOGLEMAP_MAP_TYPE"]	        = "Google-map type";
$pgv_lang["GOOGLEMAP_MAP_TYPE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_TYPE]#~<br /><br />The type of map that will be shown by default. This can be Map, Satellite of Hybrid.";

$pgv_lang["GOOGLEMAP_MAP_SIZE"]	        = "Google-map size";
$pgv_lang["GOOGLEMAP_MAP_SIZE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_SIZE]#~<br /><br />The size of the map (in pixels) as shown on the Individual page.";

$pgv_lang["GOOGLEMAP_MAP_ZOOM"]	        = "Google-map zoom factor";
$pgv_lang["GOOGLEMAP_MAP_ZOOM_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_ZOOM]#~<br /><br />Minimum en maximum zoom factor of the Google map. 1 is the complete earth, 15 is single houses. Note that 15 is only availble at certain locations.";


?>
