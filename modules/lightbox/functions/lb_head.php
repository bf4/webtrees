<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox 4.1
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 to 2008  PGV Development Team.  All rights reserved.
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
global $lang_short_cut, $LANGUAGE, $PHP_SELF, $reorder;

loadLangFile("lb_lang");

if (!file_exists("modules/googlemap/defaultconfig.php")) {
	$tabno = "6";
	}else{
	$tabno = "7";
}

// The following is temporary, until the handling of the Lightbox Help system
// is adjusted to match the usual PhpGedView practice
$lbHelpFile = "modules/lightbox/languages/help.".$lang_short_cut[$LANGUAGE].".php";
if (!file_exists($lbHelpFile)) $lbHelpFile = "modules/lightbox/languages/help_text.en.php";

?>

<script language="javascript" type="text/javascript">
<!--
	function album_help(OPTS) {
		var win01 = window.open("<?php print $lbHelpFile;?>?"+OPTS, "win01", "resizable=1, scrollbars=1, HEIGHT=780, WIDTH=500 ");
		win01.focus()
	}

	function reorder_media() {
	var win02 = window.open(
	"edit_interface.php?action=reorder_media&pid=<?php print $pid; ?>", "win02", "resizable=1, menubar=0, scrollbars=1, top=20, HEIGHT=840, WIDTH=450 ");
	if (window.focus) {win02.focus();}
	}   
	
	function album_add() {
		win03 = window.open(
		"addmedia.php?action=showmediaform&linktoid=<?php print $pid; ?>", "win03", "resizable=1, scrollbars=1, top=50, HEIGHT=780, WIDTH=600 ");
		if (window.focus) {win03.focus();}
	}
	
	function album_link() {
		win04 = window.open(
		"inverselink.php?linktoid=<?php print $pid; ?>&linkto=person", "win04", "resizable=1, scrollbars=1, top=50, HEIGHT=200, WIDTH=600 ");
		win04.focus()
	}
-->
</script>

<?php

// Load Lightbox javascript and css files
include('modules/lightbox/functions/lb_call_js.php');

// Find if indi and family associated media exists and then count them ( $tot_med_ct)  
include ('includes/media_reorder_count.php');

	// If in re-order mode do not show header links, but instead, show drag and drop title.
	if (isset($reorder) && $reorder==1){
		echo "<center><b>".$pgv_lang["reorder_media_title"]."</b></center>" ;
		echo "<br />";
		
	}else{
		//Show Lightbox-Album header Links
		//print "<br />";
		echo '<table border="0" width="75%"><tr>';
		// print "<td class=\"width10 center wrap\" valign=\"top\"></td>";

		if ($LB_AL_HEAD_LINKS == "icon") {
		print "<td>";
		}

		// Configuration
        if (PGV_USER_IS_ADMIN) {
			if ($LB_AL_HEAD_LINKS == "both") {	
	            print "<td class=\"width15 center wrap\" valign=\"top\">";
	            print "<a href=\"".encode_url("module.php?mod=lightbox&pgvaction=lb_editconfig&pid={$pid}")."\">";
				print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\"{$pgv_lang['configure_lightbox']}\" alt=\"{$pgv_lang['configure_lightbox']}\" /><br />" ;
				print "" . $pgv_lang["configure_lightbox"] . "&nbsp;";
				print "</a>";
				print "</td>"; 
	        //    print "<td width=\"5%\">&nbsp;</td>";			
	            print "\n";
			}else if ($LB_AL_HEAD_LINKS == "text") {
	            print "<td class=\"width15 center wrap\" valign=\"top\">";
	            print "<a href=\"".encode_url("module.php?mod=lightbox&pgvaction=lb_editconfig")."\">";
				print "" . $pgv_lang["configure_lightbox"] . "&nbsp;";
				print "</a>";
				print "</td>"; 
	        //    print "<td width=\"5%\">&nbsp;</td>";			
	            print "\n";
			}else if ($LB_AL_HEAD_LINKS == "icon") {
				print "&nbsp;&nbsp;&nbsp;";
	            print "<a href=\"".encode_url("module.php?mod=lightbox&pgvaction=lb_editconfig&pid={$pid}")."\">";
				print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\"{$pgv_lang['configure_lightbox']}\" alt=\"{$pgv_lang['configure_lightbox']}\" />" ;	
				print "</a>";
				print "\n";
			}
        }		

		//Add a new multimedia object
        if (PGV_USER_CAN_EDIT) {
			if ($LB_AL_HEAD_LINKS == "both") {	
				print "<td class=\"width15 center wrap\" valign=\"top\">";
	            print "<a href=\"javascript: album_add()\"> ";
				print "<img src=\"modules/lightbox/images/image_add.gif\" class=\"icon\" title=\"{$pgv_lang['lb_add_media_full']}\" alt=\"{$pgv_lang['lb_add_media_full']}\" /><br />" ;
				print "" . $pgv_lang["lb_add_media"] . "&nbsp;";
	            print " </a> ";
	            print "</td>";
	            //print "<td width=\"5%\">&nbsp;</td>"; 
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "text") {	
				print "<td class=\"width15 center wrap\" valign=\"top\">";
	            print "<a href=\"javascript: album_add()\"> ";
				print "" . $pgv_lang["lb_add_media"] . "&nbsp;";
	            print " </a> ";
	            print "</td>";
	            //print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "icon") {
				print "&nbsp;&nbsp;&nbsp;";
	            print "<a href=\"javascript: album_add()\"> ";
				print "<img src=\"modules/lightbox/images/image_add.gif\" class=\"icon\" title=\"{$pgv_lang['lb_add_media_full']}\" alt=\"{$pgv_lang['lb_add_media_full']}\" />" ;
	            print "</a>";
				print "\n";
			}
        }

		//Link to an existing item
        if (PGV_USER_CAN_EDIT) {
			if ($LB_AL_HEAD_LINKS == "both") {	
				print "<td class=\"width15 center wrap\" valign=\"top\">";
	            print "<a href=\"javascript: album_link()\"> ";
				print "<img src=\"modules/lightbox/images/image_link.gif\" class=\"icon\" title=\"{$pgv_lang['lb_link_media_full']}\" alt=\"{$pgv_lang['lb_link_media_full']}\" /><br />" ;
				print "" . $pgv_lang["lb_link_media"] . "&nbsp;";
	            print " </a> ";
	            print "</td>";
				//    print "<td width=\"5%\">&nbsp;</td>"; 
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "text") {
				print "<td class=\"width15 center wrap\" valign=\"top\">";
	            print "<a href=\"javascript: album_link()\"> ";
				print "" . $pgv_lang["lb_link_media"] . "&nbsp;";
	            print " </a> ";
	            print "</td>";
				//    print "<td width=\"5%\">&nbsp;</td>"; 
			}else if ($LB_AL_HEAD_LINKS == "icon") {
				print "&nbsp;&nbsp;&nbsp;";
	            print "<a href=\"javascript: album_link()\">";
				print "<img src=\"modules/lightbox/images/image_link.gif\" class=\"icon\" title=\"{$pgv_lang['lb_link_media_full']}\" alt=\"{$pgv_lang['lb_link_media_full']}\" />" ;
	            print "</a> ";
				print "\n";
			}else{
			}
        }

		//Turn Edit Mode On or Off
		/*
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
 				print "<td class=\"width15 center wrap\" valign=\"top\">";
				print "<a href=\"".encode_url("{$PHP_SELF}?tab={$tabno}&pid={$pid}&edit={$lbEditMode}")."\">";
				print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\"{$lbEditMsg}\" alt=\"{$lbEditMsg}\" /><br />" ;
				print "" . $lbEditMsg . "&nbsp;";
				print "</a>";
				print "</td>";
				//    print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "text") {
				print "<td class=\"width15 center wrap\" valign=\"top\">";
				print "<a href=\"".encode_url("{$PHP_SELF}?tab={$tabno}&pid={$pid}&edit={$lbEditMode}")."\">";
				print "" . $lbEditMsg . "&nbsp;";
				print "</a>";
				print "</td>";
				//    print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "icon") {
				print "&nbsp;&nbsp;&nbsp;";
				print "<a href=\"".encode+url("{$PHP_SELF}?tab={$tabno}&pid={$pid}&edit={$lbEditMode}")."\">";
				print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\"{$lbEditMsg}\" alt=\"{$lbEditMsg}\" />" ;
				print "</a>";
				print "\n";
			}
		}
		*/
		
		//Album Reorder Media  ( If media exists and is greater than 1 item ) -----
		if (PGV_USER_CAN_EDIT && $tot_med_ct>1) {
			if ($LB_AL_HEAD_LINKS == "both") {
				print "<td class=\"width15 center wrap\" valign=\"top\">";
				print "<a href=\"".encode_url("{$PHP_SELF}?tab={$tabno}&pid={$pid}&reorder=1")."\">" ;
				print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\" title=\"{$pgv_lang['reorder_media']}\" alt=\"{$pgv_lang['reorder_media']}\" /><br />" ;
				print "" . $pgv_lang["reorder_media"] . "&nbsp;";
				print '</a>';
				print "</td>";
				//print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "text") {
				print "<td class=\"width15 center wrap\" valign=\"top\">";
				print "<a href=\"".encode_url("{$PHP_SELF}?tab={$tabno}&pid={$pid}&reorder=1")."\">" ;
				print "" . $pgv_lang["reorder_media"] . "&nbsp;";
				print '</a>';
				print "</td>";
				//print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "icon") {
				print "&nbsp;&nbsp;&nbsp;";
				print "<a href=\"".encode_url("{$PHP_SELF}?tab={$tabno}&pid={$pid}&reorder=1")."\">" ;
				print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\" title=\"{$pgv_lang['reorder_media']}\" alt=\"{$pgv_lang['reorder_media']}\" />" ;
				print '</a>';
				//print "<td width=\"5%\">&nbsp;</td>";
			}
		}
		
		//Popup Reorder Media ( If media exists and is greater than 1 item ) -----
		if (PGV_USER_CAN_EDIT && $tot_med_ct>1) {
			if ($LB_AL_HEAD_LINKS == "both") {
				print "<td class=\"width15 center wrap\" valign=\"top\">";
				print "<a href=\"javascript: reorder_media()\">" ;
				print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\" title=\"{$pgv_lang['reorder_media_window']}\" alt=\"{$pgv_lang['reorder_media_window']}\" /><br />" ;
				print "" . $pgv_lang["reorder_media_window"] . "&nbsp;";
				print '</a>';
				print "</td>";
				//print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "text") {
				print "<td class=\"width15 center wrap\" valign=\"top\">";
				print "<a href=\"javascript: reorder_media()\">" ;
				print "" . $pgv_lang["reorder_media_window"] . "&nbsp;";
				print '</a>';
				print "</td>";
				//print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "icon") {
				print "&nbsp;&nbsp;&nbsp;&nbsp;";
				print "<a href=\"javascript: reorder_media()\">" ;
				print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\" title=\"{$pgv_lang['reorder_media_window']}\" alt=\"{$pgv_lang['reorder_media_window']}\" /><br />" ;
				print '</a>';
				//print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}
		}
		
		if ($LB_AL_HEAD_LINKS == "icon") {
		print "</td>";
		}
		
		print "</tr></table>";
	}
?>
