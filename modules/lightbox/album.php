<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
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
loadLangFile("lb_lang");

global $LANGUAGE, $mediatab, $mediacnt;
global $edit, $controller, $tabno, $_REQUEST, $thumb_edit, $n, $LB_URL_WIDTH, $LB_URL_HEIGHT, $LB_TT_BALLOON ;
global $reorder, $PHP_SELF, $rownum, $sort_i, $GEDCOM;

// Get Javascript variables from lb_config.php --------------------------- 
include_once('modules/lightbox/lb_config.php'); 
//	include_once('modules/lightbox/functions/browser_detection_php_ar.php');


if (isset($edit)) {
	$edit=$edit;
}else{
	$edit=1;
	}

// Used when sorting media on album tab page ===============================================
if ($reorder==1 ){

$sort_i=0; // Used in sorting on lightbox_print_media_row.php page

?>
	<script type="text/javascript">
	// This script saves the dranNdrop reordered info into a hidden form input element (name=order2)
	function saveOrder() {
		var sections = document.getElementsByClassName('section');
		var order = '';
		sections.each(function(section) {
			order += Sortable.sequence(section) + ',';
		});
		document.getElementById("ord2").value = order;
	}; 
	</script>
	
	
	<form name="reorder_form" method="post" action="edit_interface.php">
		<input type="hidden" name="action" value="al_reorder_media_update" />
		<input type="hidden" name="pid" value="<?php print $pid; ?>" />
		<input type="hidden" id="ord2" name="order2" value="" />

		<center>
		<button type="submit" title="<?php print $pgv_lang["reorder_media_save"];?>" onclick="saveOrder();" ><?php print $pgv_lang["save"];?></button>&nbsp;
		<button type="submit" title="<?php print $pgv_lang["reorder_media_reset"];?>" onclick="document.reorder_form.action.value='al_reset_media_update'; document.reorder_form.submit();"><?php print $pgv_lang["reset"];?></button>&nbsp;
		<button type="button" title="<?php print $pgv_lang["reorder_media_cancel"];?>" onClick="location.href='<?php echo $PHP_SELF . "?tab=7&pid=" . $pid  ;?>'"><?php print $pgv_lang["cancel"];?></button> 
<?php
/*
		// Debug ---------------------------------------------------------------------------
		&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" onClick="getGroupOrder()" value="Debug: Sorted">
		// ------------------------------------------------------------------------------------
*/
?>
		</center>
	</form>
<?php
}
// =====================================================================================

//------------------------------------------------------------------------------
// Start Main Table
//------------------------------------------------------------------------------
echo "<table border='0' width='100%'><tr><td>", "\n\n";

//------------------------------------------------------------------------------
// Build Thumbnail Rows
//------------------------------------------------------------------------------

//echo "<td border='0' id='ROWS'>";
	echo "<table width=\"100%\"><tr><td valign=\"top\" >";
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
           elseif ($t==5 ) {
                lightbox_print_media($pid, 0, true, 5);
           }		   
           else{
           }
 
     }

     echo "</td>";


//------------------------------------------------------------------------------
// Build Relatives navigator from includes/controllers/individual_ctrl
//------------------------------------------------------------------------------
	echo '<td valign="top" align="center" width="220">', "\n" ;
// echo "<td>";
	echo "<table ><tr><td class=\"optionbox\" align=\"center\">";
	echo "<b>{$pgv_lang['view_lightbox']}</b><br /><br />" . "\n" ;
		$controller->lightbox();	 
	echo "<br />";
	echo "</td>";
	echo "</tr></table>";


	echo "</td>" . "\n\n" ;
// -----------------------------------------------------------------------------
// end Relatives navigator
// -----------------------------------------------------------------------------


//------------------------------------------------------------------------------
// End Main Table
//------------------------------------------------------------------------------
echo "</td>";
echo "</tr></table>";
