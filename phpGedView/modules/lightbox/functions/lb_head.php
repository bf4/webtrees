<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @version $Id: lb_head.php 1430 2007-08-11 23:09:27Z windmillway $
 * @author Brian Holland
 */
?>
<script language="Javascript">
<!--
   function album_help(OPTS) {
   var win01 = window.open(
   <?php if ($pgv_language[$LANGUAGE] == "languages/lang.fr.php") { ?>
   "modules/lightbox/languages/help_fr.php?"+OPTS, "win01", "resizable=1, scrollbars=1, HEIGHT=780, WIDTH=500 ");
   <?php }else{ ?>
   "modules/lightbox/languages/help_en.php?"+OPTS, "win01", "resizable=1, scrollbars=1, HEIGHT=780, WIDTH=500 ");
   <?php } ?>
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
if (file_exists("modules/lightbox/".$pgv_language["english"]))  require("modules/lightbox/".$pgv_language["english"]);
if (file_exists( "modules/lightbox/".$pgv_language[$LANGUAGE])) require( "modules/lightbox/".$pgv_language[$LANGUAGE]);

	//Lightbox-Album header Links
		print "<br>";
		print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
		//Help page
		if ($pgv_language[$LANGUAGE] == "languages/lang.fr.php") { 
			$help = "modules/lightbox/languages/help_fr.php";
		}else{ 
			$help = "modules/lightbox/languages/help_en.php";
		} 		
        print "&nbsp;&nbsp;&nbsp;" ;
		print "<a href=\"" . $help . "\" rel='clearbox(500,760,click)' title=\"" . $pgv_lang["page_help"] . "\" > ";
        print "<img src=\"".$PGV_IMAGE_DIR."/small/help.gif\" class=\"icon\" title=\"" . $pgv_lang["page_help"] . "\" />" ;
        print $pgv_lang["page_help"];
        print "</a>" ;
/*		
		//Slide Show
		if ($mediacnt!=0) {
			print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
			print "<a href=\"modules/lightbox/images/slideshow.jpg\" rel=\"clearbox[general,6,start]\" title=\"" . $pgv_lang["lb_slide_show"] . "\">"; 
			print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\"  title=\"" . $pgv_lang["lb_slide_show"] . "\" />" ;
			print " " . $pgv_lang["lb_slide_show"]; 
			print '</a>'; 
		}else{
		}		
*/		
		//Add a new multimedia object
        if ( userCanEdit(getUserName()) ) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=\"javascript: album_add()\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" > ";
            print "<img src=\"modules/lightbox/images/image_add.gif\" class=\"icon\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" />" ;
            print " " . $pgv_lang["add_obje"] ;
            print " </a> ";
        }else{
        }
		
		//Link to an existing item
        if ( userCanEdit(getUserName()) ) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=\"javascript: album_link()\" title=\"" . $pgv_lang["lb_link_media_full"] . "\" > ";
            print "<img src=\"modules/lightbox/images/image_link.gif\" class=\"icon\" title=\" " . $pgv_lang["lb_link_media_full"] . "\" />" ;
            print " " . $pgv_lang["link_to_existing_media"] ;
            print " </a> ";
        }else{
        }

		//Turn Edit Mode On or Off
		if (!isset($edit)) { $edit=1; }
		else{ $edit==$edit;	}
        if ( userCanEdit(getUserName()) && $edit==1 && $mediacnt!=0) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=" . $PHP_SELF . "?tab=" . $tabno . "&pid=" . $pid . "&edit=0 title=\"" . $pgv_lang["turn_edit_OFF"] . "\">";
            print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\" " . $pgv_lang["turn_edit_OFF"] . "\" />" ;
            print " " . $pgv_lang["turn_edit_OFF"] ;
            print " </a> ";
        }elseif ( userCanEdit(getUserName()) && $edit==0 && $mediacnt!=0) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=" . $PHP_SELF . "?tab=" . $tabno . "&pid=" . $pid . "&edit=1 title=\"" . $pgv_lang["turn_edit_ON"] . "\">";
            print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\" " . $pgv_lang["turn_edit_ON"] . "\" />" ;
            print " " . $pgv_lang["turn_edit_ON"] ;
            print " </a> ";		
        }else{
		}
		
		
?>