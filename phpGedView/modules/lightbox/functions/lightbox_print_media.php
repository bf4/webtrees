<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 to 2009  PGV Development Team.  All rights reserved.
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
 *
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
function lightbox_print_media($pid, $level=1, $related=false, $kind=1, $noedit=false) {

	$edit="1";
	$n=1;
	$fn=1;

	global $MULTI_MEDIA, $TBLPREFIX, $SHOW_ID_NUMBERS, $MEDIA_EXTERNAL;
	global $pgv_lang, $pgv_changes, $factarray, $view;
	global $GEDCOM, $MEDIATYPE;
	global $WORD_WRAPPED_NOTES, $MEDIA_DIRECTORY, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION;

	global $is_media, $cntm1, $cntm2, $cntm3, $cntm4, $t, $mgedrec;
	global $res, $typ2b, $edit, $tabno, $n, $item, $items, $p, $note, $rowm, $note_text, $reorder;
	global $action, $order, $order2, $rownum, $rownum1, $rownum2, $rownum3, $rownum4, $media_data, $sort_i;
	
	global $GEDCOM_ID_PREFIX;

	if (!showFact("OBJE", $pid)) return false;
	if (!isset($pgv_changes[$pid."_".$GEDCOM])) $gedrec = find_gedcom_record($pid);
	else $gedrec = find_updated_record($pid);
	$ids = array($pid);

	//-- find all of the related ids
	if ($related) {
		$ct = preg_match_all("/1 FAMS @(.*)@/", $gedrec, $match, PREG_SET_ORDER);
		for ($i=0; $i<$ct; $i++) {
			$ids[] = trim($match[$i][1]);
		}
	}

	//LBox -- if  exists, get a list of the sorted current objects in the indi gedcom record  -  (1 _PGV_OBJS @xxx@ .... etc) ----------
	$sort_current_objes = array();
	if ($level>0) $sort_regexp = "/".$level." _PGV_OBJS @(.*)@/";
	else $sort_regexp = "/_PGV_OBJS @(.*)@/";
	$sort_ct = preg_match_all($sort_regexp, $gedrec, $sort_match, PREG_SET_ORDER);
	for ($i=0; $i<$sort_ct; $i++) {
		if (!isset($sort_current_objes[$sort_match[$i][1]])) $sort_current_objes[$sort_match[$i][1]] = 1;
		else $sort_current_objes[$sort_match[$i][1]]++;
		$sort_obje_links[$sort_match[$i][1]][] = $sort_match[$i][0];
	}

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
	for ($i=0; $i<$ct; $i++) {
		if (!isset($current_objes[$match[$i][1]])) {
			$current_objes[$match[$i][1]] = 1;
		} else {
			$current_objes[$match[$i][1]]++;
		}
		$obje_links[$match[$i][1]][] = $match[$i][0];
	}

	$media_found = false;

	// Get the related media items
	$sqlmm = "SELECT DISTINCT ";
	$sqlmm .= "m_media, m_ext, m_file, m_titl, m_gedfile, m_gedrec, mm_gid, mm_gedrec FROM ".$TBLPREFIX."media, ".$TBLPREFIX."media_mapping where ";
	$sqlmm .= "mm_gid IN (";
	$vars=array();
	foreach ($ids as $id) {
		$sqlmm .= "?, ";
		$vars[]=$id;
	}
	$sqlmm = rtrim($sqlmm, ', ');
	$sqlmm .= ") AND mm_gedfile=? AND mm_media=m_media AND mm_gedfile=m_gedfile ";
	$vars[]=PGV_GED_ID;
	//-- for family and source page only show level 1 obje references
	if ($level>0) {
		$sqlmm .= "AND mm_gedrec ".PGV_DB::$LIKE." ?";
		$vars[]="$level OBJE%";
	}

	// Set type of media from call in album
	switch ($kind) {
	case 1:
		$tt=$pgv_lang["ROW_TYPE__photo"];
		$sqlmm.="AND (m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ?)";
		$vars[]='%TYPE photo%';
		$vars[]='%TYPE map%';
		$vars[]='%TYPE painting%';
		$vars[]='%TYPE tombstone%';
		break;
	case 2:
		$tt=$pgv_lang["ROW_TYPE__document"];
		$sqlmm.="AND (m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ?)";
		$vars[]='%TYPE card%';
		$vars[]='%TYPE certificate%';
		$vars[]='%TYPE document%';
		$vars[]='%TYPE magazine%';
		$vars[]='%TYPE manuscript%';
		$vars[]='%TYPE newspaper%';
		break;
	case 3:
		$tt=$pgv_lang["ROW_TYPE__census"];
		$sqlmm.="AND (m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ?)";
		$vars[]='%TYPE electronic%';
		$vars[]='%TYPE fiche%';
		$vars[]='%TYPE film%';
		break;
	case 4:
		$tt=$pgv_lang["ROW_TYPE__other"];
		$sqlmm.="AND (m_gedrec NOT ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ? OR m_gedrec ".PGV_DB::$LIKE." ?)";
		$vars[]='%TYPE %';
		$vars[]='%TYPE coat%';
		$vars[]='%TYPE book%';
		$vars[]='%TYPE audio%';
		$vars[]='%TYPE video%';
		$vars[]='%TYPE other%';
		break;
	case 5:
	default:
		$tt      = $pgv_lang["ROW_TYPE__notinDB"];
		break;
	}

	if ($sort_ct>0) {
		$sqlmm .= $orderbylist;
	} else {
		$sqlmm .= " ORDER BY mm_gid DESC ";
	}

	$rows=PGV_DB::prepare($sqlmm)->execute($vars)->fetchAll(PDO::FETCH_ASSOC);
	$foundObjs = array();
	$numm = count($rows);

	// Begin to Layout the Album Media Rows
	if ($numm>0 || $kind==5) {
		if ($kind!=5) {
			echo "\n\n";
			echo "<table cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"facts_table\"><tr>", "\n";

			echo '<td width="100" align="center" class="descriptionbox" style="vertical-align:middle;">';
			echo "<b>{$tt}</b>";
			echo '</td>';

			echo '<td class="facts_value" >';
			echo '<table class="facts_table" width="100%" cellpadding="0"><tr><td >' . "\n";
			echo "<div id=\"thumbcontainer".$kind."\">" . "\n";
			echo "<ul class=\"section\" id=\"thumblist_".$kind."\">" . "\n\n";
		}

		// Album Reorder include =============================
		// Following used for Album media sort ------------------
		$reorder=safe_get('reorder', '1', '0');
		if ($reorder==1) {
			if ($kind==1) $rownum1=$numm;
			if ($kind==2) $rownum2=$numm;
			if ($kind==3) $rownum3=$numm;
			if ($kind==4) $rownum4=$numm;
			if ($kind==5) include 'modules/lightbox/functions/lb_horiz_sort.php';
		}
		// ==================================================

		// Start pulling media items into thumbcontainer div ==============================
		foreach ($rows as $rowm) {
			if (isset($foundObjs[$rowm['m_media']])) {
				if (isset($current_objes[$rowm['m_media']])) {
					$current_objes[$rowm['m_media']]--;
				}
				continue;
			}
			// NOTE: Determine the size of the mediafile
			$imgwidth = 300+40;
			$imgheight = 300+150;
			if (isFileExternal($rowm["m_file"])) {
				if (in_array($rowm["m_ext"], $MEDIATYPE)) {
					$imgwidth = 400+40;
					$imgheight = 500+150;
				} else {
					$imgwidth = 800+40;
					$imgheight = 400+150;
				}
			} else if (media_exists(check_media_depth($rowm["m_file"], "NOTRUNC"))) {
				$imgsize = findImageSize(check_media_depth($rowm["m_file"], "NOTRUNC"));
				$imgwidth = $imgsize[0]+40;
				$imgheight = $imgsize[1]+150;
			}
			$rows=array();


			//-- if there is a change to this media item then get the
			//-- updated media item and show it
			if (isset($pgv_changes[$rowm["m_media"]."_".$GEDCOM][0]["gid"]) && $kind!=5  ) {
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
			} else {
				if (!isset($current_objes[$rowm['m_media']]) && ($rowm['mm_gid']==$pid)) {
					$rows['old'] = $rowm;
				} else {
					$rows['normal'] = $rowm;
					if (isset($current_objes[$rowm['m_media']])) {
						$current_objes[$rowm['m_media']]--;
					}
				}
			}

			foreach ($rows as $rtype => $rowm) {
				if ($kind!=5) {
					$res = lightbox_print_media_row($rtype, $rowm, $pid);
				}
				$media_found = $media_found || $res;
				$foundObjs[$rowm['m_media']]=true;
			}
		}
		
		// =====================================================================================
		//-- Objects are removed from the $current_objes list as they are printed.
		//-- Any "Extra" objects left in the list are new objects recently added to the gedcom
		//-- but not yet accepted into the database.  
		//-- We will print them too, and put any "Extra Items not in DB" into a new Row.
		
		// Firstly, get count of Items in Database for Individual only (excludes family ID's)
		$indiobjs = "SELECT DISTINCT ";
		$indiobjs .= "m_media, m_ext, m_file, m_titl, m_gedfile, m_gedrec, mm_gid, mm_gedrec FROM ".$TBLPREFIX."media, ".$TBLPREFIX."media_mapping where ";
		$indiobjs .= "mm_gid IN (";
		$vars2=array();
		foreach ($ids as $id2) {
			// if (strstr($id2, "I")) {
			if (strstr($id2, $GEDCOM_ID_PREFIX)) {
			
				$indiobjs .= "?, ";
				$vars2[]=$id2;
			}
		}
		$indiobjs = rtrim($indiobjs, ', ');
		$indiobjs .= ") AND mm_gedfile=? AND mm_media=m_media AND mm_gedfile=m_gedfile ";
		$vars2[]=PGV_GED_ID;
		$rows=PGV_DB::prepare($indiobjs)->execute($vars2)->fetchAll(PDO::FETCH_ASSOC);
		$foundObjs = array();
		$numindiobjs = count($rows);
		
		// If any items are left in $current_objes list put them into $kind 5 ("Not in DB") row
		if ($kind==5 && $ct!=$numindiobjs) {
			echo "\n\n";
			echo "<table cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"facts_table\"><tr>", "\n";
			echo '<td width="100" align="center" class="descriptionbox" style="vertical-align:middle;">';
			echo "<b>{$tt}</b>";
			echo '</td>';
			echo '<td class="facts_value" >';
			echo '<table class="facts_table" width="100%" cellpadding="0"><tr><td >' . "\n";
			echo "<div id=\"thumbcontainer".$kind."\">" . "\n";
			echo "<ul class=\"section\" id=\"thumblist_".$kind."\">" . "\n\n";
			foreach ($current_objes as $media_id=>$value) {
				while ($value>0) {
					$objSubrec = array_pop($obje_links[$media_id]);
					//-- check if we need to get the object from a remote location
					$ct = preg_match("/(.*):(.*)/", $media_id, $match);
					if ($ct>0) {
						require_once 'includes/class_serviceclient.php';
						$client = ServiceClient::getInstance($match[1]);
						if (!is_null($client)) {
							$newrec = $client->getRemoteRecord($match[2]);
							$row3['m_media'] = $media_id;
							$row3['m_file'] = get_gedcom_value("FILE", 1, $newrec);
							$row3['m_titl'] = get_gedcom_value("TITL", 1, $newrec);
							if (empty($row3['m_titl'])) {
								$row3['m_titl'] = get_gedcom_value("FILE:TITL", 1, $newrec);
							}
							$row3['m_gedrec'] = $newrec;
							$et = preg_match("/(\.\w+)$/", $row3['m_file'], $ematch);
							$ext = "";
							if ($et>0) $ext = substr(trim($ematch[1]),1);
							$row3['m_ext'] = $ext;
							$row3['mm_gid'] = $pid;
							$row3['mm_gedrec'] = get_sub_record($objSubrec{0}, $objSubrec, $gedrec);
						}
					} else {
						$row3 = array();
						$newrec = find_updated_record($media_id);
						if (empty($newrec)) {
							$newrec = find_media_record($media_id);
						}
						$row3['m_media'] = $media_id;
						$row3['m_file'] = get_gedcom_value("FILE", 1, $newrec);
						$row3['m_titl'] = get_gedcom_value("TITL", 1, $newrec);
						if (empty($row3['m_titl'])) {
							$row3['m_titl'] = get_gedcom_value("FILE:TITL", 1, $newrec);
						}
						$row3['m_gedrec'] = $newrec;
						$et = preg_match("/(\.\w+)$/", $row3['m_file'], $ematch);
						$ext = "";
						if ($et>0) { 
							$ext = substr(trim($ematch[1]),1);
						}
						$row3['m_ext'] = $ext;
						$row3['mm_gid'] = $pid;
						$row3['mm_gedrec'] = get_sub_record($objSubrec{0}, $objSubrec, $gedrec);
						if ($newrec && !isset($rowm['m_file'])) {
							// -----
						} else {
							echo "<li class=\"li_new\" >";
							echo "<center><table class=\"pic\" border=\"0\" ></center>";
							echo "<tr><td align=\"center\" colspan=\"4\">";
							echo $row3['m_media'];
							echo "</td></tr>";
							
							$res =  lightbox_print_media_row('new', $row3, $pid);
							$media_found = $media_found || $res;
						}
					}
					$value--;
				}
			}
		}
		
		// No "Extra" Media Items ============================
		if ($kind==5 && $ct==$numindiobjs) {
		// "Extra" Media Item in GEDCOM but NOT in DB ========
		} else if ($kind==5 && $ct!=$numindiobjs) {
			echo "</ul>";
			echo "</div>";
			echo "<div class=\"clearlist\">";
			echo "</div>";
			echo '</td></tr></table>' . "\n";
			echo '</td>'. "\n";
			echo '</tr>';
			echo '</table>' . "\n\n";
		// Media Item in GEDCOM & in DB ======================
		} else {
			echo "</ul>";
			echo "</div>";
			echo "<div class=\"clearlist\">";
			echo "</div>";
			echo '</td></tr></table>' . "\n";
			if ($kind==3 && $numm > 0) {
				echo "<font size='1'>";
				echo $pgv_lang["census_text"];
				echo "</font>";
			}
			echo '</td>'. "\n";
			echo '</tr>';
			echo '</table>' . "\n\n";
		}
		
	}

	if ($media_found) return $is_media="YES" ;
	else return $is_media="NO" ;

}
?>
