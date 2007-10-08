<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */

//-- security check, only allow access from module.php
if (preg_match("/ra_lang\...\.php$/", $_SERVER["PHP_SELF"])>0) {
        print "You cannot access a language file directly.";
        exit;
}

//Config Parameters -------------------------------------
$pgv_lang["configure_lightbox"]		= "Lightbox-Album Configuration";
$pgv_lang["mediatab"]       		= "<b>Individual Page - Media Tab</b> - Appearance";
$pgv_lang["lb_admin_error"]         = "Page only for Administrators";

$pgv_lang["lb_icon"]				= "Icon";
$pgv_lang["lb_text"]				= "Text";
$pgv_lang["lb_both"]				= "Both";
$pgv_lang["lb_none"]				= "None";

$pgv_lang["lb_al_head_links"]		= "<b>Individual Page - Album Tab Header</b> - Link appearance";
$pgv_lang["lb_al_thumb_links"]		= "<b>Individual Page - Album Tab Thumbnails</b> - Link appearance";
$pgv_lang["lb_ml_thumb_links"]		= "<b>Multimedia page - Thumbnails</b> - Link appearance";
$pgv_lang["lb_music_file"]			= "<b>Selected Lightbox Music File</b> - (mp3 only)";
$pgv_lang["lb_ss_speed"]			= "<b>Slide Show speed</b> - In seconds";

// ---------------------------------------------------------------------


$pgv_lang["lb_help"] 		= "Album Help";
$pgv_lang["lightbox"]		= "Album";
$pgv_lang["showmenu"] 		= "Show Menu:";
$pgv_lang["active"] 		= "Active";
$pgv_lang["TYPE__other"] 	= "Other";
$pgv_lang["no_media"] 		= "None";

$pgv_lang["census_text"]  	= "\"These census images have been obtained from \"The National Archives\", the custodian of the original records, ";
$pgv_lang["census_text"] 	.= "and appear here with their approval on the condition that no commercial use is made of them without permission." . "\n" ;
$pgv_lang["census_text"] 	.= "Requests for commercial publication of these or other census images appearing on this website should be directed to: ";
$pgv_lang["census_text"] 	.= "Image Library, The National Archives, Kew, Surrey, TW9 4DU, United Kingdom.\"" . "\n" ;

$pgv_lang["lb_edit_details"] 	= "Edit Details";
$pgv_lang["lb_view_details"] 	= "View Details";
$pgv_lang["lb_edit_media"] 		= "Edit this Media Item's Details";
$pgv_lang["lb_delete_media"] 	= "Remove this Media Item - Only Removes link to this individual - Does not delete Media File or other links";
$pgv_lang["lb_view_media"] 		= "View this Media Item's Details. \nPlus other Media Options - MediaViewer page";
$pgv_lang["lb_add_media"] 		= "Add a new Media Object";
$pgv_lang["lb_add_media_full"] 	= "Add a new Multimedia Object to this Individual";
$pgv_lang["lb_link_media"] 		= "Link to an existing Media Object";
$pgv_lang["lb_link_media_full"] = "Link this Individual to an existing Multimedia Object";

$pgv_lang["lb_slide_show"] 		= "Slide Show";
$pgv_lang["turn_edit_ON"] 		= "Turn Edit Mode ON";
$pgv_lang["turn_edit_OFF"] 		= "Turn Edit Mode OFF";

$pgv_lang["lb_source_avail"] 	= "Source information available - Click here.";

$pgv_lang["lb_private"] 		= "Image linked <br> to a Private Individual";
$pgv_lang["lb_view_source_tip"] = "View Source : ";
$pgv_lang["lb_view_details_tip"] = "View Media Details : ";

?>