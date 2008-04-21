<?php
/**
 * Gedcom Statistics Block
 *
 * This block prints statistical information for the currently active gedcom
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @version $Id$
 * -- Slightly modified (rtl in table values) 2006/06/09 18:00:00 pfblair
 * @package PhpGedView
 * @subpackage Blocks
 */

require_once 'includes/functions_print_lists.php';

$PGV_BLOCKS["print_gedcom_stats"]["name"]     =$pgv_lang["gedcom_stats_block"];
$PGV_BLOCKS["print_gedcom_stats"]["descr"]    ="gedcom_stats_descr";
$PGV_BLOCKS["print_gedcom_stats"]["canconfig"]=true;
$PGV_BLOCKS["print_gedcom_stats"]["config"]   =array(
	"cache"=>1,
	"show_common_surnames"=>"yes",
	"stat_indi"=>"yes",
	"stat_fam"=>"yes",
	"stat_sour"=>"yes",
	"stat_other"=>"yes",
	"stat_media"=>"yes",
	"stat_surname"=>"yes",
	"stat_events"=>"yes",
	"stat_users"=>"yes",
	"stat_first_birth"=>"yes",
	"stat_last_birth"=>"yes",
	"stat_first_death"=>"yes",
	"stat_last_death"=>"yes",
	"stat_long_life"=>"yes",
	"stat_avg_life"=>"yes",
	"stat_most_chil"=>"yes",
	"stat_avg_chil"=>"yes"
	);

//-- function to print the gedcom statistics block

function print_gedcom_stats($block = true, $config="", $side, $index) {
	global $PGV_BLOCKS, $pgv_lang, $GEDCOM, $GEDCOMS, $ALLOW_CHANGE_GEDCOM, $ctype, $COMMON_NAMES_THRESHOLD, $PGV_IMAGE_DIR, $PGV_IMAGES, $MULTI_MEDIA;
	global $top10_block_present, $TBLPREFIX;

	if (empty($config)) $config = $PGV_BLOCKS["print_gedcom_stats"]["config"];
	if (!isset($config['stat_indi'])) $config = $PGV_BLOCKS["print_gedcom_stats"]["config"];
	if (!isset($config['stat_first_death'])) $config['stat_first_death'] = $PGV_BLOCKS["print_gedcom_stats"]["config"]['stat_first_death'];
	if (!isset($config['stat_last_death'])) $config['stat_last_death'] = $PGV_BLOCKS["print_gedcom_stats"]["config"]['stat_last_death'];
	if (!isset($config['stat_media'])) $config['stat_media'] = $PGV_BLOCKS["print_gedcom_stats"]["config"]['stat_media'];

	$id = "gedcom_stats";
	$title = print_help_link("index_stats_help", "qm", "", false, true);
	if ($PGV_BLOCKS["print_gedcom_stats"]["canconfig"]) {
		if ($ctype=="gedcom" && PGV_USER_GEDCOM_ADMIN || $ctype=="user" && PGV_USER_ID) {
			if ($ctype=="gedcom") {
				$name = preg_replace("/'/", "\'", $GEDCOM);
			} else {
				$name = PGV_USER_NAME;
			}
			$title .= "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;ctype=$ctype&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=700,height=400,scrollbars=1,resizable=1'); return false;\">";
			$title .= "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
		}
	}
	$title .= $pgv_lang["gedcom_stats"];

	$content = "<b><a href=\"index.php?ctype=gedcom\">".PrintReady($GEDCOMS[$GEDCOM]["title"])."</a></b><br />\n";
	$head = find_gedcom_record("HEAD");
	$ct=preg_match("/1 SOUR (.*)/", $head, $match);
	if ($ct>0) {
		$softrec = get_sub_record(1, "1 SOUR", $head);
		$tt= preg_match("/2 NAME (.*)/", $softrec, $tmatch);
		if ($tt>0) $title = printReady(trim($tmatch[1]));
		else $title = trim($match[1]);
		if (!empty($title)) {
			$text = str_replace(array("#SOFTWARE#", "#CREATED_SOFTWARE#"), $title, $pgv_lang["gedcom_created_using"]);
			$tt = preg_match("/2 VERS (.*)/", $softrec, $tmatch);
			if ($tt>0) $version = printReady(trim($tmatch[1]));
			else $version="";
			$text = str_replace(array("#VERSION#", "#CREATED_VERSION#"), $version, $text);
			$content .= $text;
		}
	}
	$ct=preg_match("/1 DATE (.+)/", $head, $match);
	if ($ct>0) {
		$date = new GedcomDate($match[1]);
		if (empty($title)) $text = str_replace(array("#DATE#", "#CREATED_DATE#"), $date->Display(false), $pgv_lang["gedcom_created_on"]);
		else $text = $text = str_replace(array("#DATE#", "#CREATED_DATE#"), $date->Display(false), $pgv_lang["gedcom_created_on2"]);
		$content .= $text;
	}

	$content .= "<br />\n";
	$content .= "<table><tr><td valign=\"top\" class=\"width20\"><table cellspacing=\"1\" cellpadding=\"0\">";
	if ($config["stat_indi"]=="yes") {
		$content .= '
<tr>
<td class="facts_label">'.$pgv_lang["stat_individuals"].'</td>
<td class="facts_value">
<div dir="rtl">
	<a href="indilist.php?surname_sublist=no&amp;ged='.$GEDCOM.'">'.get_list_size("indilist").'</a>
</div>
</td>
</tr>';
	}
	if ($config["stat_surname"]=="yes") {
		//-- total unique surnames
		$sql = "SELECT i_surname FROM ".$TBLPREFIX."individuals WHERE i_file='".$GEDCOMS[$GEDCOM]["id"]."' GROUP BY i_surname";
		$tempsql = dbquery($sql);
		$res =& $tempsql;
		$surname_count = $res->numRows();
		$res->free();
		$content .= '
<tr>
<td class="facts_label">'.$pgv_lang["stat_surnames"].'</td>
<td class="facts_value">
<div dir="rtl"><a
	href="indilist.php?surname_sublist=yes&amp;ged='.$GEDCOM.'">'.$surname_count.'</a>
</div>
</td>
</tr>';
	}
	if ($config["stat_fam"]=="yes") {
		$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_families"].'</td>
<td class="facts_value">
<div dir="rtl"><a href="famlist.php">'. get_list_size("famlist").'</a>
</div>
</td>
</tr>';
	}
	if ($config["stat_sour"]=="yes") {
		$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_sources"].'</td>
<td class="facts_value">
<div dir="rtl"><a href="sourcelist.php">'. get_list_size("sourcelist").'</a>
</div>
</td>
</tr>';
	}
	if ($config["stat_media"]=="yes") {
		if ($MULTI_MEDIA==true) {
			$media_count = get_list_size('objectlist');
			$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_media"].'</td>
<td class="facts_value">
<div dir="rtl"><a href="medialist.php">'. $media_count.'</a></div>
</td>
</tr>';
		}
	}
	if ($config["stat_other"]=="yes") {
		$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_other"].'</td>
<td class="facts_value">
<div dir="rtl">'. get_list_size("otherlist").'</div>
</td>
</tr>';
	}
	if ($config["stat_events"]=="yes") {
		//-- total events
		$sql = "SELECT COUNT(d_file) FROM ".$TBLPREFIX."dates WHERE d_file='".$GEDCOMS[$GEDCOM]["id"]."'";
		$tempsql = dbquery($sql);
		$res =& $tempsql;
		$row =& $res->fetchRow();
		$event_count = $row[0];
		$res->free();
		$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_events"].'</td>
<td class="facts_value">
<div dir="rtl">'. $event_count.'</div>
</td>
</tr>';
	}
	if ($config["stat_users"]=="yes") {
		$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_users"].'</td>
<td class="facts_value">
<div dir="rtl">';
			if (PGV_USER_GEDCOM_ADMIN){
			$content .= "<a href=\"useradmin.php\">".get_user_count()."</a>";
		} else {
			$content .= get_user_count();
		}
		$content .= '</div>
</td>
</tr>';
	}
	if (!$block) {
		$content .="</table>\n";
		$content .= "</td><td><br /></td><td valign=\"top\">";
		$content .= "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\">";
	}
	if ($config["stat_first_birth"]=="yes") {
		// NOTE: Get earliest birth year (prefer gregorian to other calendars if both available)
		$sql="SELECT d_gid, d_year AS year FROM {$TBLPREFIX}dates WHERE d_file={$GEDCOMS[$GEDCOM]['id']} AND d_fact='BIRT' AND d_julianday1!=0 ORDER BY d_julianday1 ASC, d_type='@#DGREGORIAN' DESC";
		$tempsql = dbquery($sql, true, 1);
		$res =& $tempsql;
		$row =& $res->fetchRow(DB_FETCHMODE_ASSOC);
		$ct = $res->numRows();
		$res->free();
		if ($ct > 0) {
			$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_earliest_birth"].'</td>
<td class="facts_value">
<div dir="rtl">
	<a href="calendar.php?action=year&amp;year='. $row["year"].'">'.
			$row["year"].'</a></div>
</td>';
			if (!$block){
				$content .= '<td class="facts_value">';
				if (displayDetailsById($row["d_gid"])) {
					$content.=format_list_person($row["d_gid"], array(get_person_name($row["d_gid"]), $GEDCOM), false, "", 'span');
				} else {
					$content.='&nbsp;';
				}
				$content .= '</td>';
			}
			$content .= '</tr>';
		}
	}
	if ($config["stat_last_birth"]=="yes") {
		// NOTE: Get the latest birth year
		$sql="SELECT d_gid, d_year AS year FROM {$TBLPREFIX}dates WHERE d_file={$GEDCOMS[$GEDCOM]['id']} AND d_fact='BIRT' AND d_julianday2!=0 ORDER BY d_julianday2 DESC, d_type='@#DGREGORIAN' DESC";
		$tempsql = dbquery($sql, true, 1);
		$res =& $tempsql;
		$row =& $res->fetchRow(DB_FETCHMODE_ASSOC);
		$ct = $res->numRows();
		$res->free();
		if ($ct>0) {
			$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_latest_birth"].'</td>
<td class="facts_value">
<div dir="rtl">
	<a href="calendar.php?action=year&amp;year='. $row["year"] .'">'.
			$row["year"].'</a></div>
</td>';
			if (!$block){
				$content .= '<td class="facts_value">';
				if (displayDetailsById($row["d_gid"])) {
					$content.=format_list_person($row["d_gid"], array(get_person_name($row["d_gid"]), $GEDCOM), false, "", 'span');
				} else {
					$content.='&nbsp;';
				}
				$content .= '</td>';
			}
			$content .= '</tr>';
		}
	}
	if ($config["stat_first_death"]=="yes") {
		// NOTE: Get earliest death year
		$sql="SELECT d_gid, d_year AS year FROM {$TBLPREFIX}dates WHERE d_file={$GEDCOMS[$GEDCOM]['id']} AND d_fact='DEAT' AND d_julianday1!=0 ORDER BY d_julianday1 ASC, d_type='@#DGREGORIAN' DESC";
		$tempsql = dbquery($sql, true, 1);
		$res =& $tempsql;
		$row =& $res->fetchRow(DB_FETCHMODE_ASSOC);
		$ct = $res->numRows();
		$res->free();
		if ($ct > 0) {
			$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_earliest_death"].'</td>
<td class="facts_value">
<div dir="rtl"><a
	href="calendar.php?action=year&amp;year='. $row["year"].'">'.
			$row["year"].'</a></div>
</td>';
			if (!$block){
				$content .= '<td class="facts_value">';
				if (displayDetailsById($row["d_gid"])) {
					$content.=format_list_person($row["d_gid"], array(get_person_name($row["d_gid"]), $GEDCOM), false, "", 'span');
				} else {
					$content.='&nbsp;';
				}
				$content .= '</td>';
			}
			$content .= '</tr>';
		}
	}
	if ($config["stat_last_death"]=="yes") {
		// NOTE: Get the latest death year
		$sql="SELECT d_gid, d_year AS year FROM {$TBLPREFIX}dates WHERE d_file={$GEDCOMS[$GEDCOM]['id']} AND d_fact='DEAT' AND d_julianday2!=0 ORDER BY d_julianday2 DESC, d_type='@#DGREGORIAN' DESC";
		$tempsql = dbquery($sql, true, 1);
		$res =& $tempsql;
		$row =& $res->fetchRow(DB_FETCHMODE_ASSOC);
		$ct = $res->numRows();
		$res->free();
		if ($ct > 0) {
			$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_latest_death"] .'</td>
<td class="facts_value">
<div dir="rtl"><a
	href="calendar.php?action=year&amp;year='. $row["year"].'">'.
			$row["year"].'</a></div>
</td>';
			if (!$block){
				$content .= '<td class="facts_value">';
				if (displayDetailsById($row["d_gid"])) {
					$content.=format_list_person($row["d_gid"], array(get_person_name($row["d_gid"]), $GEDCOM), false, "", 'span');
				} else {
					$content.='&nbsp;';
				}
				$content .= '</td>';
			}
			$content .='</tr>';
		}
	}
	if ($config["stat_long_life"]=="yes") {
		//-- get the person who lived the longest
		$sql="SELECT death.d_julianday2-birth.d_julianday1 AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth WHERE birth.d_gid=death.d_gid AND death.d_file={$GEDCOMS[$GEDCOM]['id']} AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_julianday1!=0 AND death.d_year!=0 ORDER BY age DESC";
		$tempsql = dbquery($sql, true, 1);
		$res =& $tempsql;
		$row =& $res->fetchRow();
		$ct = $res->numRows();
		$res->free();
		if ($ct>0) {
			$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_longest_life"].'</td>
<td class="facts_value">
<div dir="rtl">'. floor($row[0]/365.25).'</div>
</td>';
			if (!$block){
				$content .= '<td class="facts_value">';
				if (displayDetailsById($row[1])) {
					$content.=format_list_person($row[1], array(get_person_name($row[1]), $GEDCOM), false, "", 'span');
				} else {
					$content.='&nbsp;';
				}
				$content .='</td>';
			}
			$content .= '</tr>';
		}
	}
	if ($config["stat_avg_life"]=="yes") {
		//-- avg age at death
		$sql="SELECT AVG(death.d_julianday1-birth.d_julianday1) AS AGE FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth WHERE birth.d_gid=death.d_gid AND death.d_file={$GEDCOMS[$GEDCOM]['id']} AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_julianday1!=0 AND death.d_julianday1!=0";
		$tempsql = dbquery($sql, false);
		if (!DB::isError($tempsql)) {
			$res =& $tempsql;
			$row =& $res->fetchRow();
			$ct = $res->numRows();
			$res->free();
			if ($ct>0 && $row[0]>0) {
				$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_avg_age_at_death"].'</td>
<td class="facts_value">
<div dir="rtl">'. sprintf("%d", floor($row[0]/365.25)).'</div>
</td>';
				if (!$block) {
					$content .= '<td class="facts_value">&nbsp;</td>';
				}
				$content .= '</tr>';
			}
		}
	}

	if ($config["stat_most_chil"]=="yes" && !$block) {
		//-- most children
		$sql = "SELECT f_numchil, f_id FROM ".$TBLPREFIX."families WHERE f_file='".$GEDCOMS[$GEDCOM]["id"]."' ORDER BY f_numchil DESC";
		//print $sql;
		$tempsql = dbquery($sql, true, 10);
		if (!DB::isError($tempsql)) {
			$res =& $tempsql;
			$row =& $res->fetchRow();
			$res->free();
			$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_most_children"].'</td>
<td class="facts_value">
<div dir="rtl">'. $row["0"].'</div>
</td>
<td class="facts_value">';
			if (displayDetailsById($row[1], "FAM")) {
				ob_start();
				print_list_family($row[1], array(get_family_descriptor($row[1]), $GEDCOM), false, "", false);
				$content .= ob_get_clean();
			}
			$content .= '</td>
</tr>';
		}
	}
	if ($config["stat_avg_chil"]=="yes") {
		//-- avg number of children
		$sql = "SELECT avg(1.00 * f_numchil) from ".$TBLPREFIX."families WHERE f_file='".$GEDCOMS[$GEDCOM]["id"]."'";
		$tempsql = dbquery($sql, false);
		if (!DB::isError($tempsql)) {
			$res =& $tempsql;
			$row =& $res->fetchRow();
			$ct = $res->numRows();
			$res->free();
			if ($ct>0) {
				$content .= '
<tr>
<td class="facts_label">'. $pgv_lang["stat_average_children"].'</td>
<td class="facts_value">
<div dir="rtl">'. sprintf("%.2f", $row["0"]).'</div>
</td>';
				if (!$block) {
					$content .= '<td class="facts_value">&nbsp;</td>';
				}
				$content .= '</tr>';
			}
		}
	}
	$content .= "</table>";
	$content .= "</td></tr></table>";
	// NOTE: Print the most common surnames
	if ($config["show_common_surnames"]=="yes") {
		$surnames = get_common_surnames_index($GEDCOM);
		if (count($surnames)>0) {
			$content .= "<br />";
			$content .= print_help_link("index_common_names_help", "qm", "", false, true);
			$content .= "<b>".$pgv_lang["common_surnames"]."</b><br />\n";
			$i=0;
			foreach($surnames as $indexval => $surname) {
				if (stristr($surname["name"], "@N.N")===false) {
					if ($i>0) {
						$content .= ", ";
					}
					$content .= "<a href=\"indilist.php?ged=".$GEDCOM."&amp;surname=".urlencode($surname["name"])."\">".PrintReady($surname["name"])."</a>";
					$i++;
				}
			}
		}
	}

	print '<div id="'.$id.'" class="block"><table class="blockheader" cellspacing="0" cellpadding="0"><tr>';
	print '<td class="blockh1">&nbsp;</td>';
	print '<td class="blockh2 blockhc"><b>'.$title.'</b></td>';
	print '<td class="blockh3">&nbsp;</td>';
	print '</tr></table><div class="blockcontent">';
	if ($block) {
		print '<div class="small_inner_block">'.$content.'</div>';
	} else {
		print $content;
	}
	print '</div></div>';
}

function print_gedcom_stats_config($config) {
	global $pgv_lang, $ctype, $PGV_BLOCKS, $TEXT_DIRECTION;
	if (empty($config)) $config = $PGV_BLOCKS["print_gedcom_stats"]["config"];
	if (!isset($config["stat_indi"])) $config = $PGV_BLOCKS["print_gedcom_stats"]["config"];
	if (!isset($config["cache"])) $config["cache"] = $PGV_BLOCKS["print_gedcom_stats"]["config"]["cache"];

	?><tr><td class="descriptionbox wrap width33"> <?php print $pgv_lang["gedcom_stats_show_surnames"]; ?></td>
<td class="optionbox"><select name="show_common_surnames">
<option value="yes"
<?php if ($config["show_common_surnames"]=="yes") print " selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
<option value="no"
<?php if ($config["show_common_surnames"]=="no") print " selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
</select></td>
</tr>
<tr>
<td class="descriptionbox wrap width33"><?php print $pgv_lang["stats_to_show"]; ?></td>
<td class="optionbox">
<table>
	<tr>
		<td><input type="checkbox" value="yes" name="stat_indi"
		<?php if ($config['stat_indi']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_individuals"]; ?></td>
		<td><input type="checkbox" value="yes" name="stat_first_birth"
		<?php if ($config['stat_first_birth']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_earliest_birth"]; ?></td>
	</tr>
	<tr>
		<td><input type="checkbox" value="yes" name="stat_surname"
		<?php if ($config['stat_surname']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_surnames"]; ?></td>
		<td><input type="checkbox" value="yes" name="stat_last_birth"
		<?php if ($config['stat_last_birth']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_latest_birth"]; ?></td>
	</tr>
	<tr>
		<td><input type="checkbox" value="yes" name="stat_fam"
		<?php if ($config['stat_fam']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_families"]; ?></td>
		<td><input type="checkbox" value="yes" name="stat_first_death"
		<?php if ($config['stat_first_death']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_earliest_death"]; ?></td>
	</tr>
	<tr>
		<td><input type="checkbox" value="yes" name="stat_sour"
		<?php if ($config['stat_sour']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_sources"]; ?></td>
		<td><input type="checkbox" value="yes" name="stat_last_death"
		<?php if ($config['stat_last_death']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_latest_death"]; ?></td>
	</tr>
	<tr>
		<td><input type="checkbox" value="yes" name="stat_media"
		<?php if ($config['stat_media']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_media"]; ?></td>
		<td><input type="checkbox" value="yes" name="stat_long_life"
		<?php if ($config['stat_long_life']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_longest_life"]; ?></td>
	</tr>
	<tr>
		<td><input type="checkbox" value="yes" name="stat_other"
		<?php if ($config['stat_other']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_other"]; ?></td>
		<td><input type="checkbox" value="yes" name="stat_avg_life"
		<?php if ($config['stat_avg_life']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_avg_age_at_death"]; ?></td>
	</tr>
	<tr>
		<td><input type="checkbox" value="yes" name="stat_events"
		<?php if ($config['stat_events']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_events"]; ?></td>
		<td><input type="checkbox" value="yes" name="stat_most_chil"
		<?php if ($config['stat_most_chil']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_most_children"]; ?></td>
	</tr>
	<tr>
		<td><input type="checkbox" value="yes" name="stat_users"
		<?php if ($config['stat_users']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_users"]; ?></td>
		<td><input type="checkbox" value="yes" name="stat_avg_chil"
		<?php if ($config['stat_avg_chil']=="yes") print "checked=\"checked\""; ?> />
		<?php print $pgv_lang["stat_average_children"]; ?></td>
	</tr>
</table>
</td>
</tr>
		<?php

		// Cache file life
		if ($ctype=="gedcom") {
			print "<tr><td class=\"descriptionbox wrap width33\">";
			print_help_link("cache_life_help", "qm");
			print $pgv_lang["cache_life"];
			print "</td><td class=\"optionbox\">";
			print "<input type=\"text\" name=\"cache\" size=\"2\" value=\"".$config["cache"]."\" />";
			print "</td></tr>";
		}
}
?>
