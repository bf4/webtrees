<?php
/**
 * English language file for Lightbox Album module
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PhpGedView developers
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
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

// Added in VERSION 4.1.6
$pgv_lang["lb_tt_balloonLegend"]		= "Album Tab Thumbnail - Upper Links Tooltip";
$pgv_lang["lb_tt_balloon_help"]			= "~#pgv_lang[lb_tt_balloonLegend]#~<br />This option lets you determine whether the links above each thumbnail should show a \"Balloon\" Tooltip or \"Normal\" Tooltip when clicked. <br /><br />The links shown here show you the Notes associated with a Media item or the links to the Media item details and source (if available).<br />";



// VERSION 4.1.3 
$pgv_lang["mediatabLegend"]				= "Media Tab Appearance";
$pgv_lang["mediatab_help"]				= "~#pgv_lang[mediatab]#~<br />This option lets you determine whether the Media tab should be shown on the #pgv_lang[indi_info]# page.<br /><br />When this option is set to <b>#pgv_lang[hide]#</b>, only the <b>#pgv_lang[lightbox]#</b> tab will be shown, and it will also be re-named to <b>#pgv_lang[media]#</b>.<br />";
$pgv_lang["lb_al_head_linksLegend"]		= "Album Tab Header Link appearance";
$pgv_lang["lb_al_head_links_help"]		= "~#pgv_lang[lb_al_head_linksLegend]#~<br />This option lets you determine whether the header area of the #pgv_lang[lightbox]# tab, which contains links to control various aspects of the Lightbox module, should contain only icons, only text, or both.<br /><br />The <b>#pgv_lang[lb_icon]#</b> option is probably not very useful, since you won't see any indication of each icon's function until your mouse hovers over the icon.<br />";
$pgv_lang["lb_al_thumb_linksLegend"]	= "Album Tab Thumbnails Link appearance";
$pgv_lang["lb_al_thumb_links_help"]		= "~#pgv_lang[lb_al_thumb_linksLegend]#~<br />This option lets you determine whether the links area below each thumbnail should show an icon or text.  The links shown here let you edit the Media object's details or delete it.<br />";
$pgv_lang["lb_ml_thumb_linksLegend"]	= "Thumbnails Link appearance";
$pgv_lang["lb_ml_thumb_links_help"]		= "~#pgv_lang[lb_ml_thumb_linksLegend]#~<br />This option lets you determine whether the Links area above the Media object's details in the MultiMedia list should contain only icons, only text, or both.  The links shown here let you perform various editing actions on the Media object in question.<br /><br />The <b>#pgv_lang[lb_none]#</b> option completely hides these links, and thus acts as if the user did not have any editing rights.<br />";
$pgv_lang["lb_ss_speedLegend"]			= "Slide Show speed";
$pgv_lang["lb_ss_speed_help"]			= "~#pgv_lang[lb_ss_speedLegend]#~<br />This option determines the length of time each image should be displayed before the Slide Show displays the next image in the sequence.<br />";
$pgv_lang["lb_music_fileLegend"]		= "Slideshow Sound Track";
$pgv_lang["lb_music_file_help"]			= "~#pgv_lang[lb_music_fileLegend]#~<br />This option lets you specify a sound track to be played whenever the slide show is active.  When you leave this field blank, no sound will play during the slide show.<br /><br />This feature only supports files in the mp3 format.<br />";
$pgv_lang["lb_transitionLegend"]		= "Image Transition speed";
$pgv_lang["lb_transition_help"]			= "~#pgv_lang[lb_transitionLegend]#~<br />This option lets you specify the transition speed when the image changes.  This selection is applied during the slideshow.  It is also applied when you move to the next or previous image when the slideshow is not running.<br /><br />The <b>#pgv_lang[lb_none]#</b> option eliminates image transitions so that the new image immediately replaces the old without visible adjustment of the new image's dimensions.<br />";
$pgv_lang["lb_url_dimensionsLegend"]	= "Lightbox URL Window dimensions"; 
$pgv_lang["lb_url_dimensions_help"]		= "~#pgv_lang[lb_url_dimensionsLegend]#~<br />When clicking on a URL image thumbnail, this option lets you specify the Lightbox URL Window dimensions in pixels.<br /><br />This should normally be less than your current browser window dimensions, and certainly less than your screen resolution.<br />";

?>