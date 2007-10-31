<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
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
global $lang_short_cut, $LANGUAGE;

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
//loadLangFile("lb_lang");

	//Lightbox-Album header Links
		//print "<br>";
		print "<table border=0 width=\"66%\"><tr>";
		print "<td class=\"width10\">&nbsp;</td>"; 

		
		// Configuration
        if (userIsAdmin(getUserName())) {
			print "<td class=\"width20 center wrap font9\" valign=\"top\">";
			print "<a href=\"module.php?mod=lightbox&pgvaction=lb_editconfig&pid=" . $pid . "\">";
			if ($LB_AL_HEAD_LINKS == "icon" || $LB_AL_HEAD_LINKS == "both") {	
				print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\"" . $pgv_lang["configure_lightbox"] . "\" />" ;
			}
			if ($LB_AL_HEAD_LINKS == "both") {
				print "<br />";
			}	
			if ($LB_AL_HEAD_LINKS == "both" || $LB_AL_HEAD_LINKS == "text") {	
				print $pgv_lang["configure_lightbox"];
			}
			print "</a>";
			print "</td>"; 
			print "<td width=\"5%\">&nbsp;</td>";			
			print "\n";
        }		
		
		//Add a new multimedia object
		if (userCanEdit(getUserName())) {
			print "<td class=\"width20 center wrap font9\" valign=\"top\">";
			print "<a href=\"javascript: album_add()\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" > ";
			if ($LB_AL_HEAD_LINKS == "icon" || $LB_AL_HEAD_LINKS == "both") {	
				print "<img src=\"modules/lightbox/images/image_add.gif\" class=\"icon\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" />";
			}
			if ($LB_AL_HEAD_LINKS == "both") {
				print "<br />";
			}	
			if ($LB_AL_HEAD_LINKS == "both" || $LB_AL_HEAD_LINKS == "text") {	
				print $pgv_lang["lb_add_media"];
			}
			print " </a> ";
			print "</td>";
			print "<td width=\"5%\">&nbsp;</td>";            
        }
		
		//Link to an existing item
        if (userCanEdit(getUserName())) {
			print "<td class=\"width20 center wrap font9\" valign=\"top\">";
			print "<a href=\"javascript: album_link()\" title=\"" . $pgv_lang["lb_link_media_full"] . "\" > ";
			if ($LB_AL_HEAD_LINKS == "icon" || $LB_AL_HEAD_LINKS == "both") {	
				print "<img src=\"modules/lightbox/images/image_link.gif\" class=\"icon\" title=\" " . $pgv_lang["lb_link_media_full"] . "\" />";
			}
			if ($LB_AL_HEAD_LINKS == "both") {
				print "<br />";
			}	
			if ($LB_AL_HEAD_LINKS == "both" || $LB_AL_HEAD_LINKS == "text") {	
				print $pgv_lang["lb_link_media"];
			}
			print " </a> ";
			print "</td>";
			print "<td width=\"5%\">&nbsp;</td>"; 
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
	
		if (userCanEdit(getUserName()) && $mediacnt!=0) {
			print "<td class=\"width20 center wrap font9\" valign=\"top\">";
			print "<a href=" . $PHP_SELF . "?tab=" . $tabno . "&pid=" . $pid . "&edit={$lbEditMode} title=\"{$lbEditMsg}\">";
			if ($LB_AL_HEAD_LINKS == "icon" || $LB_AL_HEAD_LINKS == "both") {	
				print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\"{$lbEditMsg}\" />" ;
			}
			if ($LB_AL_HEAD_LINKS == "both") {
				print "<br />";
			}	
			if ($LB_AL_HEAD_LINKS == "both" || $LB_AL_HEAD_LINKS == "text") {	
				print $lbEditMsg;
			}
			print "</a>";
			print "</td>";
			print "<td width=\"5%\">&nbsp;</td>";
		}			
            		
		print "</tr></table>";
?>