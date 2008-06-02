<?php
/**
 * Provides media tab header for reorder media Items using drag and drop
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PHPGedView Development Team.  All rights reserved.
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
	global $LB_AL_HEAD_LINKS, $gedrec;
	
	require_once("js/prototype.js.htm");
	require_once("js/scriptaculous.js.htm");
?>
<script language="javascript" type="text/javascript">
<!--
	function reorder_media() {
	var win02 = window.open(
	"edit_interface.php?action=reorder_media&pid=<?php print $pid; ?>", "win02", "resizable=1, menubar=0, scrollbars=1, top=20, HEIGHT=840, WIDTH=450 ");
	if (window.focus) {win02.focus();}
	}   
-->
</script>

<?php
/*
// Find if indi and family associated media exists and then count ( $m_count)  ===================================================
	// Check indi gedcom items
		$gedrec = find_gedcom_record($pid);
		$regexp = "/OBJE @(.*)@/";
		$ct_indi = preg_match_all($regexp, $gedrec, $match, PREG_SET_ORDER);
	//-- find all of the related ids
		// if ($related) {
			$ct = preg_match_all("/1 FAMS @(.*)@/", $gedrec, $match, PREG_SET_ORDER);
			for($i=0; $i<$ct; $i++) {
				$ids[] = trim($match[$i][1]);
			}
		// }
	// Use database to get details of indi items and related items ---------------------------------------------
		$sqlmm = "SELECT DISTINCT ";
		$sqlmm .= "m_media, m_ext, m_file, m_titl, m_gedfile, m_gedrec, mm_gid, mm_gedrec FROM ".$TBLPREFIX."media, ".$TBLPREFIX."media_mapping where ";
		$sqlmm .= "mm_gid IN (";
		$i=0;
		foreach($ids as $key=>$id) {
			if ($i>0) $sqlmm .= ",";
			$sqlmm .= "'".$DBCONN->escapeSimple($id)."'";
			$i++;
		}
		$sqlmm .= ") AND mm_gedfile = '".$GEDCOMS[$GEDCOM]["id"]."' AND mm_media=m_media AND mm_gedfile=m_gedfile ";
		//-- for family and source page only show level 1 obje references----------------------------------------
		$level=0;
		if ($level>0) {
			$sqlmm .= "AND mm_gedrec LIKE '$level OBJE%'";
		}
		// Order by -------------------------------------------------------
		$sqlmm .= " ORDER BY mm_gid DESC ";
		// Perform DB Query -----------------------
		$resmm = dbquery($sqlmm);
		$foundObjs = array();
	// Database media count --------------------------------
	$db_count = $resmm->numRows();
	//Total Media count
	$tot_med_ct = ($db_count + $ct_indi);
// Debug ----------------------------------------------
// echo "Total Media count = " . $tot_med_ct;
// =====================================================================================
*/

	// print "<table border=0 width=\"100%\"><tr>";
	$gedrec = find_gedcom_record($pid);
	$regexp = "/OBJE @(.*)@/";
	$ct = preg_match_all($regexp, $gedrec, $match, PREG_SET_ORDER);
//	if ($tot_med_ct>1) {
		print "<table border=0 width=\"100%\"><tr>";
		// print "<td class=\"width10 center wrap\" valign=\"top\"></td>";
			//Popup Reorder Media
			print "<td class=\"width15 center wrap\" valign=\"top\">";
			print "<button type=\"button\" title=\"". $pgv_lang["reorder_media"]."\" onclick=\"reorder_media();\">". $pgv_lang["reorder_media"] ."</button>";
		print "</td>";
		//print "<td width=\"5%\">&nbsp;</td>";
		print "\n";
		print "</tr></table>";
//	}
?>
