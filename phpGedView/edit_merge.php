<?php
/**
* Merge Two Gedcom Records
*
* This page will allow you to merge 2 gedcom records
*
* phpGedView: Genealogy Viewer
* Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
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
* @subpackage Edit
* @version $Id$
*/

define('PGV_SCRIPT_NAME', 'edit_merge.php');
require './config.php';
require_once PGV_ROOT.'includes/functions/functions_edit.php';
require_once PGV_ROOT.'includes/functions/functions_import.php';

$ged=$GEDCOM;
$gid1=safe_POST_xref('gid1');
$gid2=safe_POST_xref('gid2');
$action=safe_POST('action', PGV_REGEX_ALPHA, 'choose');
$ged2=safe_POST('ged2', PGV_REGEX_NOSCRIPT, $GEDCOM);
$keep1=safe_POST('keep1', PGV_REGEX_UNSAFE);
$keep2=safe_POST('keep2', PGV_REGEX_UNSAFE);
if (empty($keep1)) $keep1=array();
if (empty($keep2)) $keep2=array();

print_header($pgv_lang["merge_records"]);

if ($ENABLE_AUTOCOMPLETE) require PGV_ROOT.'js/autocomplete.js.htm';

//-- make sure they have accept access privileges
if (!PGV_USER_CAN_ACCEPT) {
	echo "<span class=\"error\">", $pgv_lang["access_denied"], "</span>";
	print_footer();
	exit;
}

if ($action!="choose") {
	if ($gid1==$gid2 && $GEDCOM==$ged2) {
		$action="choose";
		echo "<span class=\"error\">", $pgv_lang["same_ids"], "</span>\n";
	} else {
		if (!isset($pgv_changes[$gid1."_".PGV_GEDCOM])) {
			$gedrec1 = find_gedcom_record($gid1, PGV_GED_ID);
		} else {
			$gedrec1 = find_updated_record($gid1, get_id_from_gedcom($GEDCOM));
		}
		if (!isset($pgv_changes[$gid2."_".$ged2])) {
			$gedrec2 = find_gedcom_record($gid2, get_id_from_gedcom($ged2));
		} else {
			$gedrec2 = find_updated_record($gid2, get_id_from_gedcom($ged2));
		}

		// Fetch the original XREF - may differ in case from the supplied value
		$tmp=new Person($gedrec1); $gid1=$tmp->getXref();
		$tmp=new Person($gedrec2); $gid2=$tmp->getXref();

		if (empty($gedrec1)) {
			echo '<span class="error">', $pgv_lang['unable_to_find_record'], ':</span> ', $gid1, ', ', $ged;
			$action="choose";
		} elseif (empty($gedrec2)) {
			echo '<span class="error">', $pgv_lang['unable_to_find_record'], ':</span> ', $gid2, ', ', $ged2;
			$action="choose";
		} else {
			$type1 = "";
			$ct = preg_match("/0 @$gid1@ (.*)/", $gedrec1, $match);
			if ($ct>0) {
				$type1 = trim($match[1]);
			}
			$type2 = "";
			$ct = preg_match("/0 @$gid2@ (.*)/", $gedrec2, $match);
			if ($ct>0) $type2 = trim($match[1]);
			if (!empty($type1) && ($type1!=$type2)) {
				echo "<span class=\"error\">", $pgv_lang["merge_same"], "</span>\n";
				$action="choose";
			} else {
				$facts1 = array();
				$facts2 = array();
				$prev_tags = array();
				$ct = preg_match_all('/\n1 (\w+)/', $gedrec1, $match, PREG_SET_ORDER);
				for($i=0; $i<$ct; $i++) {
					$fact = trim($match[$i][1]);
					if (isset($prev_tags[$fact])) {
						$prev_tags[$fact]++;
					} else {
						$prev_tags[$fact] = 1;
					}
					$subrec = get_sub_record(1, "1 $fact", $gedrec1, $prev_tags[$fact]);
					$facts1[] = array("fact"=>$fact, "subrec"=>trim($subrec));
				}
				$prev_tags = array();
				$ct = preg_match_all('/\n1 (\w+)/', $gedrec2, $match, PREG_SET_ORDER);
				for($i=0; $i<$ct; $i++) {
					$fact = trim($match[$i][1]);
					if (isset($prev_tags[$fact])) {
						$prev_tags[$fact]++;
					} else {
						$prev_tags[$fact] = 1;
					}
					$subrec = get_sub_record(1, "1 $fact", $gedrec2, $prev_tags[$fact]);
					$facts2[] = array("fact"=>$fact, "subrec"=>trim($subrec));
				}
				if ($action=="select") {
					echo "<h2>", $pgv_lang["merge_step2"], "</h2>\n";
					echo "<form method=\"post\" action=\"edit_merge.php\">\n";
					echo $pgv_lang["merge_facts_same"], "<br />\n";
					echo "<input type=\"hidden\" name=\"gid1\" value=\"", $gid1, "\">\n";
					echo "<input type=\"hidden\" name=\"gid2\" value=\"", $gid2, "\">\n";
					echo "<input type=\"hidden\" name=\"ged\" value=\"", $GEDCOM, "\">\n";
					echo "<input type=\"hidden\" name=\"ged2\" value=\"", $ged2, "\">\n";
					echo "<input type=\"hidden\" name=\"action\" value=\"merge\">\n";
					$equal_count=0;
					$skip1 = array();
					$skip2 = array();
					echo "<table border=\"1\">\n";
					foreach($facts1 as $i=>$fact1) {
						foreach($facts2 as $j=>$fact2) {
							if (UTF8_strtoupper($fact1["subrec"])==UTF8_strtoupper($fact2["subrec"])) {
								$skip1[] = $i;
								$skip2[] = $j;
								$equal_count++;
								echo "<tr><td>";
								if (isset($factarray[$fact1["fact"]])) {
									echo $factarray[$fact1["fact"]];
								} else {
									echo $fact1["fact"];
								}
								echo "<input type=\"hidden\" name=\"keep1[]\" value=\"", $i, "\" /></td>\n<td>", nl2br($fact1["subrec"]), "</td></tr>\n";
							}
						}
					}
					if ($equal_count==0) {
						echo "<tr><td>", $pgv_lang["no_matches_found"], "</td></tr>\n";
					}
					echo "</table><br /><br />\n";
					echo $pgv_lang["unmatching_facts"], "<br />\n";
					echo "<table class=\"list_table\">\n";
					echo "<tr><td class=\"list_label\">", $pgv_lang["record"], " ", $gid1, "</td><td class=\"list_label\">", $pgv_lang["record"], " ", $gid2, "</td></tr>\n";
					echo "<tr><td valign=\"top\" class=\"list_value\">\n";
					echo "<table border=\"1\">\n";
					foreach($facts1 as $i=>$fact1) {
						if (($fact1["fact"]!="CHAN")&&(!in_array($i, $skip1))) {
							echo "<tr><td><input type=\"checkbox\" name=\"keep1[]\" value=\"", $i, "\" checked=\"checked\" /></td>";
							echo "<td>", nl2br($fact1["subrec"]), "</td></tr>\n";
						}
					}
					echo "</table>\n";
					echo "</td><td valign=\"top\" class=\"list_value\">\n";
					echo "<table border=\"1\">\n";
					foreach($facts2 as $j=>$fact2) {
						if (($fact2["fact"]!="CHAN")&&(!in_array($j, $skip2))) {
							echo "<tr><td><input type=\"checkbox\" name=\"keep2[]\" value=\"", $j, "\" checked=\"checked\" /></td>";
							echo "<td>", nl2br($fact2["subrec"]), "</td></tr>\n";
						}
					}
					echo "</table>";
					echo "</td></tr>";
					echo "</table>\n";
					echo "<input type=\"submit\" value=\"", $pgv_lang["merge_records"], "\">\n";
					echo "</form>\n";
				} elseif ($action=="merge") {
					$manual_save = true;
					echo "<h2>", $pgv_lang["merge_step3"], "</h2>\n";
					if ($GEDCOM==$ged2) {
						$success = delete_gedrec($gid2);
						if ($success) {
							echo "<br />", $pgv_lang["gedrec_deleted"], "<br />\n";
						}

						//-- replace all the records that linked to gid2
						$ids=fetch_all_links($gid2, PGV_GED_ID);

						foreach ($ids as $id) {
							if (isset($pgv_changes[$id."_".PGV_GEDCOM])) {
								$record=find_updated_record($id, PGV_GED_ID);
							} else {
								$record=fetch_gedcom_record($id, PGV_GED_ID);
								$record=$record['gedrec'];
								echo $pgv_lang["updating_linked"], " {$id}<br />\n";
								$newrec=str_replace("@$gid2@", "@$gid1@", $record);
								$newrec=preg_replace(
									'/(\n1.*@.+@.*(?:(?:\n[2-9].*)*))((?:\n1.*(?:\n[2-9].*)*)*\1)/',
									'$2',
									$newrec
								);
								replace_gedrec($id, $newrec);
							}
						}

						// Merge hit counters
						$hits=PGV_DB::prepare(
							"SELECT page_name, SUM(page_count)".
							" FROM {$TBLPREFIX}hit_counter".
							" WHERE gedcom_id=? AND page_parameter IN (?, ?)".
							" GROUP BY page_name"
						)->execute(array(PGV_GED_ID, $gid1, $gid2))->fetchAssoc();
						foreach ($hits as $page_name=>$page_count) {
							PGV_DB::prepare(
								"UPDATE {$TBLPREFIX}hit_counter SET page_count=?".
								" WHERE gedcom_id=? AND page_name=? AND page_parameter=?"
							)->execute(array($page_count, PGV_GED_ID, $page_name, $gid1));
						}
						PGV_DB::prepare(
							"DELETE FROM {$TBLPREFIX}hit_counter".
						 	" WHERE gedcom_id=? AND page_parameter=?"
						)->execute(array(PGV_GED_ID, $gid2));
					}
					$newgedrec = "0 @$gid1@ $type1\n";
					for($i=0; ($i<count($facts1) || $i<count($facts2)); $i++) {
						if (isset($facts1[$i])) {
							if (in_array($i, $keep1)) {
								$newgedrec .= $facts1[$i]["subrec"]."\n";
								echo $pgv_lang["adding"], " ", $facts1[$i]["fact"], " ", $pgv_lang["from"], " ", $gid1, "<br />\n";
							}
						}
						if (isset($facts2[$i])) {
							if (in_array($i, $keep2)) {
								$newgedrec .= $facts2[$i]["subrec"]."\n";
								echo $pgv_lang["adding"], " ", $facts2[$i]["fact"], " ", $pgv_lang["from"], " ", $gid2, "<br />\n";
							}
						}
					}

					replace_gedrec($gid1, $newgedrec);
					if ($SYNC_GEDCOM_FILE) {
						write_file();
					}
					write_changes();
					$rec=GedcomRecord::getInstance($gid1);
					$pid=$rec->getXrefLink(); // $pid is embedded in $pgv_lang['record_updated']
					echo '<br />', print_text('record_updated', 0, 1), '<br />';
					$fav_count=update_favorites($gid2, $gid1);
					if ($fav_count > 0) {
						echo '<br />', $fav_count, ' ', $pgv_lang["updated_favorites"], '<br />';
					}
					echo "<br /><a href=\"edit_merge.php?action=choose\">", $pgv_lang["merge_more"], "</a><br />\n";
					echo "<br /><br /><br />\n";
				}
			}
		}
	}
}
if ($action=="choose") {
	?>
	<script language="JavaScript" type="text/javascript">
	<!--
	var pasteto;
	function iopen_find(textbox, gedselect) {
		pasteto = textbox;
		ged = gedselect.options[gedselect.selectedIndex].value;
		findwin = window.open('find.php?type=indi&ged='+ged, '_blank', 'left=50, top=50, width=600, height=500, resizable=1,  scrollbars=1');
	}
	function fopen_find(textbox, gedselect) {
		pasteto = textbox;
		ged = gedselect.options[gedselect.selectedIndex].value;
		findwin = window.open('find.php?type=fam&ged='+ged, '_blank', 'left=50, top=50, width=600, height=500, resizable=1, scrollbars=1');
	}
	function sopen_find(textbox, gedselect) {
		pasteto = textbox;
		ged = gedselect.options[gedselect.selectedIndex].value;
		findwin = window.open('find.php?type=source&ged='+ged, '_blank', 'left=50, top=50, width=600, height=500, resizable=1, scrollbars=1');
	}
	function paste_id(value) {
		pasteto.value=value;
	}
	//-->
	</script>
	<?php
	echo "<h2>", $pgv_lang["merge_step1"], "</h2>\n";
	echo "<form method=\"post\" name=\"merge\" action=\"edit_merge.php\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"select\" />\n";
	echo $pgv_lang["select_gedcom_records"], "<br />\n";
	echo "\n\t\t<table class=\"list_table, ", $TEXT_DIRECTION, "\">\n\t\t<tr>";
	echo "<td class=\"list_label\">&nbsp;";
	echo $pgv_lang["merge_to"];
	echo "&nbsp;</td><td>";
	echo "<input type=\"text\" id=\"gid1\" name=\"gid1\" value=\"", $gid1, "\" size=\"10\" tabindex=\"1\"/> ";
	echo '<script type="text/javascript">document.getElementById("gid1").focus();</script>';
	echo "<select name=\"ged\" tabindex=\"4\">\n";
	$all_gedcoms=get_all_gedcoms();
	asort($all_gedcoms);
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		echo "<option value=\"", $ged_name, "\"";
		if (empty($ged) && $ged_id==PGV_GED_ID || !empty($ged) && $ged==$ged_name) {
			echo " selected=\"selected\"";
		}
		echo ">", PrintReady(strip_tags(get_gedcom_setting($ged_id, 'title'))), "</option>\n";
	}
	echo "</select>\n";
	echo "<a href=\"javascript:iopen_find(document.merge.gid1, document.merge.ged);\" tabindex=\"6\"> ", $pgv_lang["find_individual"], "</a> |";
	echo " <a href=\"javascript:fopen_find(document.merge.gid1, document.merge.ged);\" tabindex=\"8\"> ", $pgv_lang["find_familyid"], "</a> |";
	echo " <a href=\"javascript:sopen_find(document.merge.gid1, document.merge.ged);\" tabindex=\"10\"> ", $pgv_lang["find_sourceid"], "</a>";
	print_help_link("rootid_help", "qm");
	echo "</td></tr><tr><td class=\"list_label\">&nbsp;";
	echo $pgv_lang["merge_from"];
	echo "&nbsp;</td><td>";
	echo "<input type=\"text\" name=\"gid2\" value=\"", $gid2, "\" size=\"10\" tabindex=\"2\"/> ";
	echo "<select name=\"ged2\" tabindex=\"5\">\n";
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		echo "<option value=\"", $ged_name, "\"";
		if (empty($ged2) && $ged_id==PGV_GED_ID || !empty($ged2) && $ged2==$ged_name) {
			echo " selected=\"selected\"";
		}
		echo ">", PrintReady(strip_tags(get_gedcom_setting($ged_id, 'title'))), "</option>\n";
	}
	echo "</select>\n";
	echo "<a href=\"javascript:iopen_find(document.merge.gid2, document.merge.ged2);\" tabindex=\"7\"> ", $pgv_lang["find_individual"], "</a> |";
	echo "<a href=\"javascript:fopen_find(document.merge.gid2, document.merge.ged2);\" tabindex=\"9\"> ", $pgv_lang["find_familyid"], "</a> |";
	echo "<a href=\"javascript:sopen_find(document.merge.gid2, document.merge.ged2);\" tabindex=\"11\"> ", $pgv_lang["find_sourceid"], "</a>";
	print_help_link("rootid_help", "qm");
	echo "</td></tr><tr><td colspan=\"2\">";
	echo "<input type=\"submit\" value=\"", $pgv_lang["merge_records"], "\"  tabindex=\"3\"/>\n";
	echo "</td></tr></table>";
	echo "</form>\n";
}

print_footer();
?>
