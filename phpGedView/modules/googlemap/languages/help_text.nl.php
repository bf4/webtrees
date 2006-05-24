<?php
/**
 * Dutch Language file for PhpGedView.
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

$pgv_lang["GOOGLEMAP_API_KEY"]	    = "Google-map API code";
$pgv_lang["GOOGLEMAP_API_KEY_help"]	= "~#pgv_lang[GOOGLEMAP_API_KEY]#~<br /><br />Voer hier uw Google Map API code in. U kunt een code aanvragen op <a target=\"_blank\" href=\"http://www.google.com/apis/maps/\">http://www.google.com/apis/maps/</a>";

$pgv_lang["GOOGLEMAP_MAP_TYPE"]	        = "Google-map type";
$pgv_lang["GOOGLEMAP_MAP_TYPE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_TYPE]#~<br /><br />Het type kaart dat standaard word getoond. Dit kan zijn Kaart, Satelliet of Combinatie";

$pgv_lang["GOOGLEMAP_MAP_SIZE"]	        = "Google-map afmeting";
$pgv_lang["GOOGLEMAP_MAP_SIZE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_SIZE]#~<br /><br />De afmeting van de kaart (in pixels) zoals deze getoond word op de pagina met Persoonsinformatie";

$pgv_lang["GOOGLEMAP_MAP_ZOOM"]	        = "Google-map zoom factor";
$pgv_lang["GOOGLEMAP_MAP_ZOOM_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_ZOOM]#~<br /><br />Minimum en maximum zoom factor van de Google map. 1 is de gehele aarde, 15 is enkele huizen. Houd er rekeing mee dat factor 15 niet overal beschikbaar is.";


?>
