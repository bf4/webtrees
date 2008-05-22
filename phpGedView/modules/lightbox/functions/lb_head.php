<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox 4.1
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PHPGedView Development Team
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
?>
<?php
global $lang_short_cut, $LANGUAGE, $PHP_SELF;

loadLangFile("lb_lang");

// The following is temporary, until the handling of the Lightbox Help system
// is adjusted to match the usual PhpGedView practice
$lbHelpFile = "modules/lightbox/languages/help.".$lang_short_cut[$LANGUAGE].".php";
if (!file_exists($lbHelpFile)) $lbHelpFile = "modules/lightbox/languages/help_text.en.php";

?>

<script language="Javascript">
<!--
	function album_help(OPTS) {
		var win01 = window.open("<?php print $lbHelpFile;?>?"+OPTS, "win01", "resizable=1, scrollbars=1, HEIGHT=780, WIDTH=500 ");
		win01.focus()
	}

	function album_add() {
		var win01 = window.open(
		"addmedia.php?action=showmediaform&linktoid=<?php print $pid; ?>", "win01", "resizable=1, scrollbars=1, top=50, HEIGHT=780, WIDTH=600 ");
		win01.focus()
	}

	function album_link() {
		var win01 = window.open(
		"inverselink.php?linktoid=<?php print $pid; ?>&linkto=person", "win01", "resizable=1, scrollbars=1, top=50, HEIGHT=200, WIDTH=600 ");
		win01.focus()
	}
-->
</script>

<?php

// Load Lightbox javascript and css files
include('modules/lightbox/functions/lb_call_js.php'); 

	//Lightbox-Album header Links
		//print "<br />";
		print "<table border=0 width=\"66%\"><tr>";
		print "<td class=\"width10 center wrap\" valign=\"top\">";

		
		// Configuration
        if (PGV_USER_IS_ADMIN) {
			if ($LB_AL_HEAD_LINKS == "both") {	
	            print "<td class=\"width20 center wrap\" valign=\"top\">";
	            print "<a href=\"module.php?mod=lightbox&pgvaction=lb_editconfig&pid=" . $pid . "\">";
				print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\"" . $pgv_lang["configure_lightbox"] . "\" /><br />" ;
				print "" . $pgv_lang["configure_lightbox"] . "";
				print "</a>";
				print "</td>"; 
	            print "<td width=\"5%\">&nbsp;</td>";			
	            print "\n";
			}else if ($LB_AL_HEAD_LINKS == "text") {
	            print "<td class=\"width20 center wrap\" valign=\"top\">";
	            print "<a href=\"module.php?mod=lightbox&pgvaction=lb_editconfig\">";
				print "" . $pgv_lang["configure_lightbox"] . "";
				print "</a>";
				print "</td>"; 
	            print "<td width=\"5%\">&nbsp;</td>";			
	            print "\n";
			}else if ($LB_AL_HEAD_LINKS == "icon") {
				print "<td>";
				print "&nbsp;&nbsp;";
	            print "<a href=\"module.php?mod=lightbox&pgvaction=lb_editconfig&pid=" . $pid . "\">";
				print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\"" . $pgv_lang["configure_lightbox"] . "\" />" ;	
				print "</a>";
				print "\n";
			}
        }		
		
		//Add a new multimedia object
        if (PGV_USER_CAN_EDIT) {
			if ($LB_AL_HEAD_LINKS == "both") {	
				print "<td class=\"width20 center wrap\" valign=\"top\">";
	            print "<a href=\"javascript: album_add()\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" > ";
				print "<img src=\"modules/lightbox/images/image_add.gif\" class=\"icon\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" /><br />" ;
				print $pgv_lang["lb_add_media"] ;
	            print " </a> ";
	            print "</td>";
	            print "<td width=\"5%\">&nbsp;</td>";            
			}else if ($LB_AL_HEAD_LINKS == "text") {	
				print "<td class=\"width20 center wrap\" valign=\"top\">";
	            print "<a href=\"javascript: album_add()\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" > ";
				print $pgv_lang["lb_add_media"] ;
	            print " </a> ";
	            print "</td>";
	            print "<td width=\"5%\">&nbsp;</td>";            
			}else if ($LB_AL_HEAD_LINKS == "icon") {
				print "&nbsp;&nbsp;";
	            print "<a href=\"javascript: album_add()\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" > ";
				print "<img src=\"modules/lightbox/images/image_add.gif\" class=\"icon\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" />" ;
	            print "</a>";
			}
        }
		
		//Link to an existing item
        if (PGV_USER_CAN_EDIT) {
			if ($LB_AL_HEAD_LINKS == "both") {	
				print "<td class=\"width20 center wrap\" valign=\"top\">";
	            print "<a href=\"javascript: album_link()\" title=\"" . $pgv_lang["lb_link_media_full"] . "\" > ";
				print "<img src=\"modules/lightbox/images/image_link.gif\" class=\"icon\" title=\" " . $pgv_lang["lb_link_media_full"] . "\" /><br />" ;
				print $pgv_lang["lb_link_media"] ;
	            print " </a> ";
	            print "</td>";
	            print "<td width=\"5%\">&nbsp;</td>"; 
			}else if ($LB_AL_HEAD_LINKS == "text") {
				print "<td class=\"width20 center wrap\" valign=\"top\">";
	            print "<a href=\"javascript: album_link()\" title=\"" . $pgv_lang["lb_link_media_full"] . "\" > ";
				print $pgv_lang["lb_link_media"] ;
	            print " </a> ";
	            print "</td>";
	            print "<td width=\"5%\">&nbsp;</td>"; 
			}else if ($LB_AL_HEAD_LINKS == "icon") {
				print "&nbsp;&nbsp;";
	            print "<a href=\"javascript: album_link()\" title=\"" . $pgv_lang["lb_link_media_full"] . "\" > ";
				print "<img src=\"modules/lightbox/images/image_link.gif\" class=\"icon\" title=\" " . $pgv_lang["lb_link_media_full"] . "\" />" ;
	            print "</a> ";
			}else{
			}
        }

		//Turn Edit Mode On or Off
		if (!isset($edit)) $edit=1;
		if ($edit==1) {
			$lbEditMsg = $pgv_lang["turn_edit_OFF"];
			$lbEditMode = 0;
		} else {
			$lbEditMsg = $pgv_lang["turn_edit_ON"];
			$lbEditMode = 1;
		}
	
        if (PGV_USER_CAN_EDIT && $mediacnt!=0) {
			if ($LB_AL_HEAD_LINKS == "both") {
 				print "<td class=\"width20 center wrap\" valign=\"top\">";
	            print "<a href=" . $PHP_SELF . "?tab=7&pid=" . $pid . "&edit={$lbEditMode} title=\"{$lbEditMsg}\">";
 	          	print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\"{$lbEditMsg}\" /><br />" ;
            	print $lbEditMsg;
	            print "</a>";
	            print "</td>";
	            print "<td width=\"5%\">&nbsp;</td>";
            }else if ($LB_AL_HEAD_LINKS == "text") {
  				print "<td class=\"width20 center wrap\" valign=\"top\">";
	            print "<a href=" . $PHP_SELF . "?tab=7&pid=" . $pid . "&edit={$lbEditMode} title=\"{$lbEditMsg}\">";
	          	print $lbEditMsg;
	            print "</a>";
	            print "</td>";
	            print "<td width=\"5%\">&nbsp;</td>";
           }else if ($LB_AL_HEAD_LINKS == "icon") {
				print "&nbsp;&nbsp;";
	            print "<a href=" . $PHP_SELF . "?tab=7&pid=" . $pid . "&edit={$lbEditMode} title=\"{$lbEditMsg}\">";
          		print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\"{$lbEditMsg}\" /><br />" ;
	            print "</a>";
				print "</td>";
			}           			
		}			
            		
		print "</tr></table>";
?>
