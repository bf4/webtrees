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

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

/**
 * -----------------------------------------------------------------------------
 * Print the links to multi-media objects
 * @param string $pid        The the xref id of the object to find media records related to
 * @param int $level        The level of media object to find
 * @param boolean $related        Whether or not to grab media from related records
 */

// -----------------------------------------------------------------------------
// function lightbox_print_main_media($pid, $level=1, $related=false, $noedit=false) {
// -----------------------------------------------------------------------------

	$t=$kind ;
	$edit="1";
	$n=1;
	$fn=1;

	global $MULTI_MEDIA, $TBLPREFIX, $SHOW_ID_NUMBERS, $MEDIA_EXTERNAL;
	global $pgv_lang, $pgv_changes, $factarray, $view;
	global $GEDCOMS, $GEDCOM, $MEDIATYPE, $DBCONN, $DBTYPE;
	global $WORD_WRAPPED_NOTES, $MEDIA_DIRECTORY, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION;

	global $is_media, $cntm1, $cntm2, $cntm3, $cntm4, $t, $mgedrec;
	global $res, $typ2b, $edit, $tabno, $n, $item, $items, $p, $note, $rowm, $note_text, $reorder;
	global $action, $order, $order2, $rownum, $rownum1, $rownum2, $rownum3, $rownum4, $media_data, $sort_i;

	// Set type of media from call in album
	if ($t==1) {
		$tt      = $pgv_lang["ROW_TYPE__photo"];
		$typ2b   = "(";
		$typ2b  .= " (m_gedrec LIKE '%TYPE photo%')       OR ";
		$typ2b  .= " (m_gedrec LIKE '%TYPE map%')         OR ";
		$typ2b  .= " (m_gedrec LIKE '%TYPE painting%')    OR ";
		$typ2b  .= " (m_gedrec LIKE '%TYPE tombstone%')      ";
		$typ2b  .= ")";
	}
	if ($t==2){
		$tt      = $pgv_lang["ROW_TYPE__document"];
		$typ2b   = "(";
		$typ2b  .= " (m_gedrec LIKE '%TYPE card%')        OR ";
		$typ2b  .= " (m_gedrec LIKE '%TYPE certificate%') OR ";
		$typ2b  .= " (m_gedrec LIKE '%TYPE document%')    OR ";
		$typ2b  .= " (m_gedrec LIKE '%TYPE magazine%')    OR ";
		$typ2b  .= " (m_gedrec LIKE '%TYPE manuscript%')  OR ";
		$typ2b  .= " (m_gedrec LIKE '%TYPE newspaper%')      ";
		$typ2b  .= ")";
	}
	if ($t==3){
		$tt      = $pgv_lang["ROW_TYPE__census"];
		$typ2b   = "(";
		$typ2b  .= " (m_gedrec LIKE '%TYPE electronic%')  OR ";
		$typ2b  .= " (m_gedrec LIKE '%TYPE fiche%')       OR ";
		$typ2b  .= " (m_gedrec LIKE '%TYPE film%')           ";
		$typ2b  .= ")";
	}
	if ($t==4){
		$tt      = $pgv_lang["ROW_TYPE__other"];
		$typ2b   = "(";
		$typ2b  .= " (m_gedrec NOT LIKE '%TYPE %')        OR ";
		$typ2b  .= " (m_gedrec LIKE     '%TYPE book%')    OR ";
		$typ2b  .= " (m_gedrec LIKE     '%TYPE audio%')   OR ";
		$typ2b  .= " (m_gedrec LIKE     '%TYPE video%')   OR ";
		$typ2b  .= " (m_gedrec LIKE     '%TYPE other%')      ";
		$typ2b  .= ")";
	}
	if ($t==5){
		$tt      = $pgv_lang["ROW_TYPE__footnotes"];
		$typ2b   = "(m_gedrec LIKE     '%%')";
	}

	if (!showFact("OBJE", $pid)) return false;
	if (!isset($pgv_changes[$pid."&nbsp;".$GEDCOM])) $gedrec = find_gedcom_record($pid);
	else $gedrec = find_updated_record($pid);
	$ids = array($pid);

	//-- find all of the related ids
	if ($related) {
		$ct = preg_match_all("/1 FAMS @(.*)@/", $gedrec, $match, PREG_SET_ORDER);
		for($i=0; $i<$ct; $i++) {
			$ids[] = trim($match[$i][1]);
		}
	}

	//LBox -- if  exists, get a list of the sorted current objects in the indi gedcom record  -  (1 _PGV_OBJS @xxx@ .... etc) ----------
	$sort_current_objes = array();
	if ($level>0) $sort_regexp = "/".$level." _PGV_OBJS @(.*)@/";
	else $sort_regexp = "/_PGV_OBJS @(.*)@/";
	$sort_ct = preg_match_all($sort_regexp, $gedrec, $sort_match, PREG_SET_ORDER);
	for($i=0; $i<$sort_ct; $i++) {
		if (!isset($sort_current_objes[$sort_match[$i][1]])) $sort_current_objes[$sort_match[$i][1]] = 1;
		else $sort_current_objes[$sort_match[$i][1]]++;
		$sort_obje_links[$sort_match[$i][1]][] = $sort_match[$i][0];
	}
	$sort_media_found = false;

	// create ORDER BY list from Gedcom sorted records list  ---------------------------
	$orderbylist = 'ORDER BY '; // initialize
	foreach ($sort_match as $id) {
		$orderbylist .= "m_media='$id[1]' DESC, ";
	}
	$orderbylist = rtrim($orderbylist, ', ');
	// ---------------------------------------------------------------------------------------------------------------------------------------------------

	//-- get a list of the current objects in the record
	$current_objes = array();
	if ($level>0) $regexp = "/".$level." OBJE @(.*)@/";
	else $regexp = "/OBJE @(.*)@/";
	$ct = preg_match_all($regexp, $gedrec, $match, PREG_SET_ORDER);
	for($i=0; $i<$ct; $i++) {
		if (!isset($current_objes[$match[$i][1]])) $current_objes[$match[$i][1]] = 1;
		else $current_objes[$match[$i][1]]++;
		$obje_links[$match[$i][1]][] = $match[$i][0];
	}

	$media_found = false;

	// Get the related media items
		// Adding DISTINCT is the fix for: [ 1488550 ] Family/Individual Media Duplications
		// but it may not work for all RDBMS.
		// $sqlmm  = "SELECT ";
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
	//-- for family and source page only show level 1 obje references
	if ($level>0) $sqlmm .= "AND mm_gedrec LIKE '$level OBJE%'";

	$sqlmm .= " AND $typ2b ";
	// $sqlmm .= " ORDER BY m_titl ";
	if ($sort_ct>0) {
		$sqlmm .= $orderbylist;
	}else{
		$sqlmm .= " ORDER BY mm_gid DESC ";
	}

	$resmm = dbquery($sqlmm);
	$foundObjs = array();

	$numm = $resmm->numRows();

	// Begin to Layout the Album Media Rows
	if ( ($t==1 && $numm>0 || $t==2 && $numm>0 || $t==3 && $numm>0 || $t==4 && $numm>0 || ($t==5 )) ) {
		if ($t==5){ // do nothing
		}else{
			echo "\n\n";
			echo "<table cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"facts_table\"><tr>", "\n";

			echo '<td width="100" align="center" class="descriptionbox" style="vertical-align:middle;">';
				if ($t==5){
					echo "<b>" . $tt . "</b>";
				}else if ( ($t!=5) && PGV_USER_CAN_ACCESS){
					echo "<b>" . $tt . "</b>";
					// echo "(" . $numm . ")";
				}else{
					echo "<b>" . $tt . "</b>";
				}
			echo '</td>';

			//echo '<td width="2"></td>';

			echo '<td class="facts_value" >';
			echo '<table class="facts_table" width=\"100%\" cellpadding=\"0\"><tr><td >' . "\n";
				echo "<div id=\"thumbcontainer".$t."\">" . "\n";
				echo "<ul class=\"section\" id=\"thumblist_".$t."\">" . "\n\n";
				//echo "<ul id=\"thumblist\">" . "\n\n";
		}

		// Album Reorder include =============================
		// Following used for Album media sort ------------------
		$reorder=safe_get('reorder', '1', '0');
		if ($reorder==1) {
			if ($t==1) { $rownum1=$numm; }
			if ($t==2) { $rownum2=$numm; }
			if ($t==3) { $rownum3=$numm; }
			if ($t==4) { $rownum4=$numm; }
		}
		// ----------------------------------------------------------------
		if (($reorder==1 && $t==5) ) {
			 include ("modules/lightbox/functions/lb_horiz_sort.php");
		}
		// ==================================================

		// Start pulling media items into thumbcontainer div ==============================
		while ($rowm = $resmm->fetchRow(DB_FETCHMODE_ASSOC)) {
			if (isset($foundObjs[$rowm['m_media']])) {
				if (isset($current_objes[$rowm['m_media']])) $current_objes[$rowm['m_media']]--;
				continue;
			}
			// NOTE: Determine the size of the mediafile
			$imgwidth = 300+40;
			$imgheight = 300+150;
			if (isFileExternal($rowm["m_file"])) {
				if (in_array($rowm["m_ext"], $MEDIATYPE)) {
					$imgwidth = 400+40;
					$imgheight = 500+150;
				}else{
					$imgwidth = 800+40;
					$imgheight = 400+150;
				}
			}else if (media_exists(check_media_depth($rowm["m_file"], "NOTRUNC"))) {
				$imgsize = findImageSize(check_media_depth($rowm["m_file"], "NOTRUNC"));
				$imgwidth = $imgsize[0]+40;
				$imgheight = $imgsize[1]+150;
			}
			$rows=array();


			//-- if there is a change to this media item then get the
			//-- updated media item and show it
			// if (isset(find_updated_record($rowm["m_media"]) {
			if (isset($pgv_changes[$rowm["m_media"]."_".$GEDCOM][0]["gid"]) && $t!="5"  ){
			// if (isset($pgv_changes[$rowm["m_media"]."&nbsp;".$GEDCOM])) {
				$newrec = find_updated_record($rowm["m_media"]);
				$row = array();
				$row['m_media'] = $rowm["m_media"];
				$row['m_file'] = get_gedcom_value("FILE", 1, $newrec);
				$row['m_titl'] = get_gedcom_value("TITL", 1, $newrec);
				if (empty($row['m_titl'])) $row['m_titl'] = get_gedcom_value("FILE:TITL", 1, $newrec);
				$row['m_gedrec'] = $newrec;
				$et = preg_match("/(\.\w+)$/", $row['m_file'], $ematch);
				$ext = "";
				if ($et>0) $ext = substr(trim($ematch[1]),1);
				$row['m_ext'] = $ext;
				$row['mm_gid'] = $pid;
				$row['mm_gedrec'] = $rowm["mm_gedrec"];
				$rows['new'] = $row;
				$rows['old'] = $rowm;
				$current_objes[$rowm['m_media']]--;
			}else{
				if (!isset($current_objes[$rowm['m_media']]) && ($rowm['mm_gid']==$pid)) {
					$rows['old'] = $rowm;
				}else{
					$rows['normal'] = $rowm;
					if (isset($current_objes[$rowm['m_media']])) {
						$current_objes[$rowm['m_media']]--;
					}
				}
			}


			foreach($rows as $rtype => $rowm) {
				if ($t!=5){

					$res = lightbox_print_media_row($rtype, $rowm, $pid);

				}
				$media_found = $media_found || $res;
				$foundObjs[$rowm['m_media']]=true;
			}
			$mgedrec[] = $rowm["m_gedrec"];
		}

		if ($t==5) {
		}else{
			echo "</ul>";
			echo "</div>";
			echo "<div class=\"clearlist\">";
			echo "</div>";
			// echo "</center>";

			echo '</td></tr></table>' . "\n";

			if ($t==3 && $numm > 0) {
				echo "<font size='1'>";
				echo $pgv_lang["census_text"];
				echo "</font>";
			}

			// echo "</center>" . "\n";
			echo '</td>'. "\n";
			echo '</tr>';
			echo '</table>' . "\n\n";
		}
	}

	//-- objects are removed from the $current_objes list as they are printed
	//-- any objects left in the list are new objects recently added to the gedcom
	//-- but not yet accepted into the database.  We will print them too.

	foreach($current_objes as $media_id=>$value) {
		while($value>0) {
			$objSubrec = array_pop($obje_links[$media_id]);
			//-- check if we need to get the object from a remote location
			$ct = ( preg_match("/(.*):(.*)/", $media_id, $match) );
			if ($ct>0) {
				require_once 'includes/class_serviceclient.php';
				$client = ServiceClient::getInstance($match[1]);
				if (!is_null($client)) {
					$newrec = $client->getRemoteRecord($match[2]);
					$row['m_media'] = $media_id;
					$row['m_file'] = get_gedcom_value("FILE", 1, $newrec);
					$row['m_titl'] = get_gedcom_value("TITL", 1, $newrec);

					if (empty($row['m_titl'])) $row['m_titl'] = get_gedcom_value("FILE:TITL", 1, $newrec);
					$row['m_gedrec'] = $newrec;
					$et = preg_match("/(\.\w+)$/", $row['m_file'], $ematch);
					$ext = "";
					if ($et>0) $ext = substr(trim($ematch[1]),1);
					$row['m_ext'] = $ext;
					$row['mm_gid'] = $pid;
					$row['mm_gedrec'] = get_sub_record($objSubrec{0}, $objSubrec, $gedrec);

					// BH added "if" qualifiers for time $t ----------------------------------------
					if ($t==1 && $ct>0 ) {
						$typ2a  = ( (eregi("TYPE photo",$row['m_gedrec']) || eregi("TYPE painting",$row['m_gedrec']) || eregi("TYPE map",$row['m_gedrec']) || eregi("TYPE tombstone",$row['m_gedrec'])) && !eregi(".pdf",$row['m_file']) );
					}
					if ($t==2 && $ct>0 ) {
						$typ2a  = ( (eregi("TYPE card",$row['m_gedrec']) || eregi("TYPE certificate",$row['m_gedrec']) || eregi("TYPE document",$row['m_gedrec']) || eregi("TYPE magazine",$row['m_gedrec']) || eregi("TYPE manuscript",$row['m_gedrec']) || eregi("TYPE newspaper",$row['m_gedrec'])) ) ;
					}
					if ($t==3 && $ct>0 ) {
						$typ2a  = ( (eregi("TYPE electronic",$row['m_gedrec']) || eregi("TYPE film",$row['m_gedrec']) || eregi("TYPE fiche",$row['m_gedrec'])) );
					}
					if ($t==4 && $ct>0 ) {
						$typ2a  = ( !eregi("TYPE",$row['m_gedrec']) || eregi("TYPE other",$row['m_gedrec']) || eregi("TYPE book",$row['m_gedrec']) || eregi("TYPE audio",$row['m_gedrec']) || eregi("TYPE video",$row['m_gedrec']) );
					}

					if ( $typ2a ) {
						echo '<table border="0" class="facts_table"><tr>';
						echo '<td width="80" align="center" class="descriptionbox">' ;
						echo "<b><br /><br />" . $tt . "</b><br /><br />(" . $ct . ")";
						echo '</td>' . "\n";
						echo '<td class="facts_value">';
						echo "<center>" . "\n\n";
						echo '<table><tr><td>' . "\n";
						echo "<center>" . "\n\n";  // needed for Firefox
						echo "<div id=\"thumbcontainer".$t."\">" . "\n";

						echo "<ul class=\"section\" id=\"thumblist_".$t."\">" . "\n\n";

							$res = lightbox_print_media_row('normal', $row, $pid);
							$media_found = $media_found || $res;

						echo "</ul>";
						echo "</div>";
						echo "<div id=\"clearlist\">";
						echo "</div";
						echo "</center>";

						echo '</td></tr></table>';
						echo "</center>";

						echo '</td>';
						echo '</td>';
						echo '</tr>';
						echo '</table>' . "\n\n";
					}
				}
			}
			$value--;
		}
	}



// ====================================================================================


	if ($media_found) return $is_media="YES" ;
	else return $is_media="NO" ;

// -----------------------------------------------------------------------------
// }
// -----------------------------------------------------------------------------


?>
