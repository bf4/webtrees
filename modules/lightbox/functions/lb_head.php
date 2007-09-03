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
 * @version $Id$
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
require( "modules/lightbox/".$pgv_language["english"]);
if (file_exists( "modules/lightbox/".$pgv_language[$LANGUAGE])) require  "modules/lightbox/".$pgv_language[$LANGUAGE];

global $reorder, $edit;
if (!isset($edit)) {$edit=1;} 
else{$edit==$edit;}

	//Lightbox-Album header Links
		print "<br>";
		print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
		//Help page
        print "&nbsp;&nbsp;&nbsp;" ;
        print "<a href=\"javascript: album_help()\" title=\"" . $pgv_lang["page_help"] . "\" >" ;
        print "<img src=\"".$PGV_IMAGE_DIR."/small/help.gif\" class=\"icon\" title=\"" . $pgv_lang["page_help"] . "\" />" ;
        print $pgv_lang["page_help"];
        print "</a>" ;

/*	NOT READY YET - More work to do		
		//Reorder Media
//		if ( userIsAdmin(getUserName()) && $mediacnt!=0 &&  (!isset($edit) || $edit==1) && ($reorder!=1 || !isset($reorder)) ) {
		if ( userCanEdit(getUserName()) && $mediacnt!=0 &&  (!isset($edit) || $edit==1) && ($reorder!=1 || !isset($reorder)) ) {		
			print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
//			print "<a href=\"javascript: reorder_media()\" title=\"Reorder Media\" >" ;
			print "<a href=\"" . $_SERVER['PHP_SELF'] . "?tab=" . $tabno . "&pid=" . $pid . "&edit=" . $edit . "&reorder=1\" title=\"Change Media Order *** Not working fully yet ! ***\" >" ;
			print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\"  title=\"Change Media Order *** Not working fully yet ! ***\" >" ;
			print " Change Media Order" ; 
			print '</a>';  		
//		}elseif ( userIsAdmin(getUserName()) && $mediacnt!=0 && (!isset($edit) || $edit==1) ){
		}elseif ( userCanEdit(getUserName()) && $mediacnt!=0 && (!isset($edit) || $edit==1) ){		
			print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
//			print "<a href=\"javascript: reorder_media()\" title=\"Reorder Media\" >" ;
			print "<a href=\"" . $_SERVER['PHP_SELF'] . "?tab=" . $tabno . "&pid=" . $pid . "&edit=" . $edit . "\" title=\"Save Media Order *** Not working fully yet ! ***\" >" ;
			print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\"  title=\"Save Media Order\" />" ;
			print " Save Media Order" ; 
			print '</a>';
				//NOTE for 4.2 SVN
				print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
				print "( Drag the Media icons by the id number at the top, to the left or right to sort. Then Save )";
				print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
				print "<font color=\"red\"> *** PLEASE NOTE THIS FUNCTION IS NOT WORKING YET (only the interface) *** </font>";				
		}else{
		}
*/		
		//Slide Show
		if ( $reorder==1 ) {
		}else{
			if ($mediacnt!=0) {
				print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
				print "<a href=\"modules/lightbox/images/slideshow.jpg\" rel=\"clearbox[general,6,start]\" title=\"" . $pgv_lang["lb_slide_show"] . "\">"; 
				print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\"  title=\"" . $pgv_lang["lb_slide_show"] . "\" />" ;
				print " " . $pgv_lang["lb_slide_show"]; 
				print '</a>';
			}else{
			}	
		}
		
		//Add a new multimedia object
		if ( $reorder==1 || $edit==0 ) {
		}elseif ( userCanEdit(getUserName()) ) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=\"javascript: album_add()\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" > ";
            print "<img src=\"modules/lightbox/images/image_add.gif\" class=\"icon\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" />" ;
            print " " . $pgv_lang["add_obje"] ;
            print " </a> ";
        }else{
        }
		
		//Link to an existing item
		if ( $reorder==1  || $edit==0 ) {
        }elseif ( userCanEdit(getUserName()) ) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=\"javascript: album_link()\" title=\"" . $pgv_lang["lb_link_media_full"] . "\" > ";
            print "<img src=\"modules/lightbox/images/image_link.gif\" class=\"icon\" title=\" " . $pgv_lang["lb_link_media_full"] . "\" />" ;
            print " " . $pgv_lang["link_to_existing_media"] ;
            print " </a> ";
        }else{
        }

		//Turn Edit Mode On or Off
		if ( $reorder==1 ) {
        }elseif (userIsAdmin(getUserName()) && $edit==1 && $mediacnt!=0) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=" . $PHP_SELF . "?tab=" . $tabno . "&pid=" . $pid . "&edit=0 title=\"" . $pgv_lang["turn_edit_OFF"] . "\">";
            print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\" " . $pgv_lang["turn_edit_OFF"] . "\" />" ;
            print " " . $pgv_lang["turn_edit_OFF"] ;
            print " </a> ";
        }elseif (userIsAdmin(getUserName()) && $edit==0 && $mediacnt!=0) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=" . $PHP_SELF . "?tab=" . $tabno . "&pid=" . $pid . "&edit=1 title=\"" . $pgv_lang["turn_edit_ON"] . "\">";
            print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\" " . $pgv_lang["turn_edit_ON"] . "\" />" ;
            print " " . $pgv_lang["turn_edit_ON"] ;
            print " </a> ";		
        }else{
		}
		
		
?>