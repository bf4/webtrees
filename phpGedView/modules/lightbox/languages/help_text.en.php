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

$pgv_lang["mediatabLegend"]			= "Media Tab Appearance";
$pgv_lang["mediatab_help"]			= "~#pgv_lang[mediatabLegend]#~<br />This option lets you determine whether the Media tab should be shown on the Personal Facts and Details page.<br /><br />When this option is set to <b>#pgv_lang[hide]#</b>, only the <b>#pgv_lang[lightbox]#</b> tab will be shown, and it will also be re-named to <b>#pgv_lang[media]#</b>.<br />";
$pgv_lang["lb_al_head_linksLegend"]	= "Album Tab Header Link appearance";
$pgv_lang["lb_al_head_links_help"]	= "~#pgv_lang[lb_al_head_linksLegend]#~<br />This option lets you determine whether the header area of the #pgv_lang[lightbox]# tab, which contains links to control various aspects of the Lightbox module, should contain only icons, only text, or both.<br /><br />The <b>#pgv_lang[lb_icon]#</b> option is probably not very useful, since you won't see any indication of each icon's function until your mouse hovers over the icon.<br />";
$pgv_lang["lb_al_thumb_linksLegend"]= "Album Tab Thumbnails Link appearance";
$pgv_lang["lb_al_thumb_links_help"]	= "~#pgv_lang[lb_al_thumb_linksLegend]#~<br />This option lets you determine whether the links area below each thumbnail should show an icon or text.  The links shown here let you edit the Media object's details or delete it.<br />";
$pgv_lang["lb_ml_thumb_linksLegend"]= "Thumbnails Link appearance";
$pgv_lang["lb_ml_thumb_links_help"]	= "~#pgv_lang[lb_ml_thumb_linksLegend]#~<br />This option lets you determine whether the Links area above the Media object's details in the MultiMedia list should contain only icons, only text, or both.  The links shown here let you perform various editing actions on the Media object in question.<br /><br />The <b>#pgv_lang[lb_none]#</b> option completely hides these links, and thus acts as if the user did not have any editing rights.<br />";
$pgv_lang["lb_ss_speedLegend"]		= "Slide Show speed";
$pgv_lang["lb_ss_speed_help"]		= "~#pgv_lang[lb_ss_speedLegend]#~<br />This option determines the length of time each image should be displayed before the Slide Show displays the next image in the sequence.<br />";
$pgv_lang["lb_music_fileLegend"]	= "Selected Lightbox music file";
$pgv_lang["lb_music_file_help"]		= "~#pgv_lang[lb_music_fileLegend]#~<br />This option lets you specify a music or other sound file to be played whenever the slide show is active.<br /><br />This feature only supports files in the mp3 format.<br />";
 
?>