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
loadLangFile("lb_lang");

global $LANGUAGE, $mediatab, $mediacnt;
global $edit, $controller, $tabno, $_REQUEST, $thumb_edit, $n, $LB_URL_WIDTH, $LB_URL_HEIGHT ;

// Get Javascript variables from lb_config.php --------------------------- 
include_once('modules/lightbox/lb_config.php'); 
//	include_once('modules/lightbox/functions/browser_detection_php_ar.php');


if (isset($edit)) {
	$edit=$edit;
}else{
	$edit=1;
	}
//------------------------------------------------------------------------------
// Start Main Table
//------------------------------------------------------------------------------
echo "<table border=0 width='100%'><tr>" . "\n\n";

//------------------------------------------------------------------------------
// Build Thumbnail Rows
//------------------------------------------------------------------------------

     echo "<td>";
     for ($t=1; $t <=5; $t++) {

           if ($t==1) {
                lightbox_print_media($pid, 0, true, 1);
           }
           elseif ($t==2) {
                lightbox_print_media($pid, 0, true, 2);
           }
           elseif ($t==3) {
                lightbox_print_media($pid, 0, true, 3);
           }
           elseif ($t==4) {
                lightbox_print_media($pid, 0, true, 4);
           }
           elseif ($t==5) {
                lightbox_print_media($pid, 0, true, 5);
           }		   
           else{
           }

     }
     echo '</td>';


//------------------------------------------------------------------------------
// Build Relatives navigator from includes/controllers/individual_ctrl
//------------------------------------------------------------------------------
     echo '<td border=0 valign="top" align="center" width=220 class="optionbox" >' . "\n" ;
     echo "<b>" . $pgv_lang["view"] . " '" . $pgv_lang["lightbox"] ."'</b><br /><br />" . "\n" ;


     echo '<table><tr><td>';
     $controller->lightbox();	 
     echo '</td></tr></table>';


     echo '<br /></td>' . "\n\n" ;
// -----------------------------------------------------------------------------
// end Relatives navigator
// -----------------------------------------------------------------------------


//------------------------------------------------------------------------------
// End Main Table
//------------------------------------------------------------------------------
echo "</tr></table>";
echo "<center>" . "\n";
?>



