<?php
/**
 * Gedcom Statistics Block
 *
 * This block prints statistical information for the currently active gedcom
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  PGV Development Team
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
 * @package PhpGedView
 * @subpackage Blocks
 */

require_once("includes/functions_print_lists.php");

$PGV_BLOCKS["print_gedcom_stats"]["name"]        = $pgv_lang["gedcom_stats_block"];
$PGV_BLOCKS["print_gedcom_stats"]["descr"]        = "gedcom_stats_descr";
$PGV_BLOCKS["print_gedcom_stats"]["canconfig"]   = true;
$PGV_BLOCKS["print_gedcom_stats"]["config"] = array("show_common_surnames"=>"yes"
	,"stat_indi"=>"yes"
	,"stat_fam"=>"yes"
	,"stat_sour"=>"yes"
	,"stat_other"=>"yes"
	,"stat_surname"=>"yes"
	,"stat_events"=>"yes"
	,"stat_users"=>"yes"
	,"stat_first_birth"=>"yes"
	,"stat_last_birth"=>"yes"
	,"stat_first_death"=>"yes"
	,"stat_last_death"=>"yes"
	,"stat_long_life"=>"yes"
	,"stat_avg_life"=>"yes"
	,"stat_most_chil"=>"yes"
	,"stat_avg_chil"=>"yes"
	);

//-- function to print the gedcom statistics block

function print_gedcom_stats($block = true, $config="", $side, $index) {
		global $PGV_BLOCKS, $pgv_lang, $GEDCOM, $GEDCOMS, $ALLOW_CHANGE_GEDCOM, $command, $COMMON_NAMES_THRESHOLD, $PGV_IMAGE_DIR, $PGV_IMAGES;
		global $top10_block_present, $TBLPREFIX, $monthtonum;		// Set in index.php

		if (empty($config)) $config = $PGV_BLOCKS["print_gedcom_stats"]["config"];
		if (!isset($config['stat_indi'])) $config = $PGV_BLOCKS["print_gedcom_stats"]["config"];

		print "<div id=\"gedcom_stats\" class=\"block\">\n";
		print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
		print "<td class=\"blockh1\" >&nbsp;</td>";
		print "<td class=\"blockh2\" ><div class=\"blockhc\">";
		print_help_link("index_stats_help", "qm");
		if ($PGV_BLOCKS["print_gedcom_stats"]["canconfig"]) {
			$username = getUserName();
			if ((($command=="gedcom")&&(userGedcomAdmin($username))) || (($command=="user")&&(!empty($username)))) {
				if ($command=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
				else $name = $username;
				print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;command=$command&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=700,height=400,scrollbars=1,resizable=1'); return false;\">";
				print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
			}
		}
		print "<b>".$pgv_lang["gedcom_stats"]."</b>";
		print "</div></td>";
		print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
		print "</table>";
		print "<div class=\"blockcontent\">";

		print "<b><a href=\"index.php?command=gedcom\">".PrintReady($GEDCOMS[$GEDCOM]["title"])."</a></b><br />\n";
		$head = find_gedcom_record("HEAD");
		$ct=preg_match("/1 SOUR (.*)/", $head, $match);
		if ($ct>0) {
			$softrec = get_sub_record(1, "1 SOUR", $head);
			$tt= preg_match("/2 NAME (.*)/", $softrec, $tmatch);
			if ($tt>0) $title = trim($tmatch[1]);
			else $title = trim($match[1]);
			if (!empty($title)) {
					$text = str_replace(array("#SOFTWARE#", "#CREATED_SOFTWARE#"), $title, $pgv_lang["gedcom_created_using"]);
					$tt = preg_match("/2 VERS (.*)/", $softrec, $tmatch);
					if ($tt>0) $version = trim($tmatch[1]);
					else $version="";
					$text = str_replace(array("#VERSION#", "#CREATED_VERSION#"), $version, $text);
					print $text;
			}
		}
		$ct=preg_match("/1 DATE (.*)/", $head, $match);
		if ($ct>0) {
			$date = trim($match[1]);
			if (empty($title)) $text = str_replace(array("#DATE#", "#CREATED_DATE#"), get_changed_date($date), $pgv_lang["gedcom_created_on"]);
			else $text = $text = str_replace(array("#DATE#", "#CREATED_DATE#"), get_changed_date($date), $pgv_lang["gedcom_created_on2"]);
			print $text;
		}

		print "<br />\n";
		print "<table><tr><td valign=\"top\" class=\"width20\"><table cellspacing=\"1\" cellpadding=\"0\">";
		if ($config["stat_indi"]=="yes") {
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_individuals"]?>
				</td>
				<td class="facts_value">
					<a href="indilist.php?surname_sublist=no&amp;ged=<?php echo $GEDCOM?>"><?php echo get_list_size("indilist")?></a>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_surname"]=="yes") {
			//-- total unique surnames
			$sql = "SELECT COUNT(i_surname) FROM ".$TBLPREFIX."individuals WHERE i_file='".$GEDCOMS[$GEDCOM]["id"]."' GROUP BY i_surname";
			$tempsql = dbquery($sql);
			$res =& $tempsql;
			$surname_count = $res->numRows();
			$res->free();
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_surnames"]?>
				</td>
				<td class="facts_value">
					<a href="indilist.php?surname_sublist=yes&amp;ged=<?php echo $GEDCOM?>"><?php echo $surname_count?></a>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_fam"]=="yes") {
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_families"]?>
				</td>
				<td class="facts_value">
					<a href="famlist.php"><?php echo get_list_size("famlist")?></a>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_sour"]=="yes") {
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_sources"]?>
				</td>
				<td class="facts_value">
					<a href="sourcelist.php"><?php echo get_list_size("sourcelist")?></a>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_other"]=="yes") {
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_other"]?>
				</td>
				<td class="facts_value">
					<?php echo get_list_size("otherlist")?>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_events"]=="yes") {
			//-- total events
			$sql = "SELECT COUNT(d_gid) FROM ".$TBLPREFIX."dates WHERE d_file='".$GEDCOMS[$GEDCOM]["id"]."'";
			$tempsql = dbquery($sql);
			$res =& $tempsql;
			$row =& $res->fetchRow();
			$event_count = $row[0];
			$res->free();
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_events"]?>
				</td>
				<td class="facts_value">
					<?php echo $event_count?>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_users"]=="yes") {
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_users"]?>
				</td>
				<td class="facts_value">
					<?php echo count(getUsers())?>
				</td>
			</tr>
			<?php
		}
		if (!$block) {
			print "</table>\n";
			print "</td><td><br /></td><td valign=\"top\">";
			print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\">";
		}
		if ($config["stat_first_birth"]=="yes") {
			// NOTE: Get earliest birth year
			$sql = "select d_gid, d_year as year from ".$TBLPREFIX."dates where d_file = '".$GEDCOMS[$GEDCOM]["id"]."' and d_fact = 'BIRT' and d_year != '0' and d_type is null ORDER BY d_year ASC, d_mon ASC, d_day ASC";
			//print $sql;
			$tempsql = dbquery($sql);
			$res =& $tempsql;
			$row =& $res->fetchRow(DB_FETCHMODE_ASSOC);
			$res->free();
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_earliest_birth"]?>
				</td>
				<td class="facts_value">
					<a href="calendar.php?action=year&amp;year=<?php echo $row["year"]?>"><?php echo $row["year"]?></a>
				</td>
				<td  class="facts_value">
					<?php	if (!$block && displayDetailsById($row["d_gid"])) print_list_person($row["d_gid"], array(get_person_name($row["d_gid"]), $GEDCOM), false, "", false)?>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_last_birth"]=="yes") {
			// NOTE: Get the latest birth year
			$sql = "select d_gid, d_year as year from ".$TBLPREFIX."dates where d_file = '".$GEDCOMS[$GEDCOM]["id"]."' and d_fact = 'BIRT' and d_year != '0' and d_type is null ORDER BY d_year DESC, d_mon DESC , d_day DESC";
			//print $sql;
			$tempsql = dbquery($sql);
			$res =& $tempsql;
			$row =& $res->fetchRow(DB_FETCHMODE_ASSOC);
			$res->free();
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_latest_birth"]?>
				</td>
				<td class="facts_value">
					<a href="calendar.php?action=year&amp;year=<?php echo $row["year"]?>"><?php echo $row["year"]?></a>
				</td>
				<td  class="facts_value">
					<?php if (!$block && displayDetailsById($row["d_gid"])) print_list_person($row["d_gid"], array(get_person_name($row["d_gid"]), $GEDCOM), false, "", false)?>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_first_death"]=="yes") {
			// NOTE: Get earliest death year
			$sql = "select d_gid, d_year as year from ".$TBLPREFIX."dates where d_file = '".$GEDCOMS[$GEDCOM]["id"]."' and d_fact = 'DEAT' and d_year != '0' and d_type is null ORDER BY d_year ASC, d_mon ASC, d_day ASC";
			//print $sql;
			$tempsql = dbquery($sql);
			$res =& $tempsql;
			$row =& $res->fetchRow(DB_FETCHMODE_ASSOC);
			$res->free();
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_earliest_death"]?>
				</td>
				<td class="facts_value">
					<a href="calendar.php?action=year&amp;year=<?php echo $row["year"]?>"><?php echo $row["year"]?></a>
				</td>
				<td  class="facts_value">
					<?php if (!$block && displayDetailsById($row["d_gid"])) print_list_person($row["d_gid"], array(get_person_name($row["d_gid"]), $GEDCOM), false, "", false)?>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_last_death"]=="yes") {
			// NOTE: Get the latest death year
			$sql = "select d_gid, d_year as year from ".$TBLPREFIX."dates where d_file = '".$GEDCOMS[$GEDCOM]["id"]."' and d_fact = 'DEAT' and d_year != '0' and d_type is null ORDER BY d_year DESC, d_mon DESC , d_day DESC";
			//print $sql;
			$tempsql = dbquery($sql);
			$res =& $tempsql;
			$row =& $res->fetchRow(DB_FETCHMODE_ASSOC);
			$res->free();
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_latest_death"]?>
				</td>
				<td class="facts_value">
					<a href="calendar.php?action=year&amp;year=<?php echo $row["year"]?>"><?php echo $row["year"]?></a>
				</td>
				<td  class="facts_value">
					<?php if (!$block && displayDetailsById($row["d_gid"])) print_list_person($row["d_gid"], array(get_person_name($row["d_gid"]), $GEDCOM), false, "", false)?>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_long_life"]=="yes") {
			//-- get the person who lived the longest
			$sql = "select death.d_year-birth.d_year as age, death.d_gid from ".$TBLPREFIX."dates as death, ".$TBLPREFIX."dates as birth where birth.d_gid=death.d_gid AND death.d_file='".$GEDCOMS[$GEDCOM]["id"]."' and birth.d_file=death.d_file AND birth.d_fact='BIRT' and death.d_fact='DEAT' AND birth.d_year>0 and death.d_year>0 and birth.d_type is null and death.d_type is null ORDER BY age DESC";
			$tempsql = dbquery($sql, true, 1);
			$res =& $tempsql;
			$row =& $res->fetchRow();
			$res->free();
			?>
			<tr>
				<td class="facts_label">
					<?php echo $pgv_lang["stat_longest_life"]?>
				</td>
				<td class="facts_value">
					<?php echo $row[0]?>
				</td>
				<td  class="facts_value">
					<?php if (!$block && displayDetailsById($row[1])) print_list_person($row[1], array(get_person_name($row[1]), $GEDCOM), false, "", false)?>
				</td>
			</tr>
			<?php
		}
		if ($config["stat_avg_life"]=="yes") {
			//-- avg age at death
			$sql = "select avg(death.d_year-birth.d_year) as age from ".$TBLPREFIX."dates as death, ".$TBLPREFIX."dates as birth where birth.d_gid=death.d_gid AND death.d_file='".$GEDCOMS[$GEDCOM]["id"]."' and birth.d_file=death.d_file AND birth.d_fact='BIRT' and death.d_fact='DEAT' AND birth.d_year>0 and death.d_year>0 and birth.d_type is null and death.d_type is null";
			$tempsql = dbquery($sql, false);
			if (!DB::isError($tempsql)) {
				$res =& $tempsql;
				$row =& $res->fetchRow();
				?>
				<tr>
					<td class="facts_label">
						<?php echo $pgv_lang["stat_avg_age_at_death"]?>
					</td>
					<td class="facts_value">
						<?php	printf("%d", $row["0"])?>
					</td>
					<td  class="facts_value">
						&nbsp;
					</td>
				</tr>
				<?php
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
				?>
				<tr>
					<td class="facts_label">
						<?php echo $pgv_lang["stat_most_children"]?>
					</td>
					<td class="facts_value">
						<?php	echo $row["0"]?>
					</td>
					<td  class="facts_value">
						<?php if (displayDetailsById($row[1], "FAM")) print_list_family($row[1], array(get_family_descriptor($row[1]), $GEDCOM), false, "", false)?>
					</td>
				</tr>
				<?php
			}
		}
		if ($config["stat_avg_chil"]=="yes") {
			//-- avg number of children
			$sql = "SELECT avg(1.00 * f_numchil) from ".$TBLPREFIX."families WHERE f_file='".$GEDCOMS[$GEDCOM]["id"]."'";
			$tempsql = dbquery($sql, false);
			if (!DB::isError($tempsql)) {
				$res =& $tempsql;
				$row =& $res->fetchRow();
				?>
				<tr>
					<td class="facts_label">
						<?php echo $pgv_lang["stat_average_children"]?>
					</td>
					<td class="facts_value">
						<?php	printf("%.2f", $row["0"])?>
					</td>
					<td  class="facts_value">
						&nbsp;
					</td>
				</tr>
				<?php
			}
		}
		print "</table>";
		print "</td></tr></table>";
		// NOTE: Print the most common surnames
		if ($config["show_common_surnames"]=="yes") {
			$surnames = get_common_surnames_index($GEDCOM);
			if (count($surnames)>0) {
				print "<br />";
				print_help_link("index_common_names_help", "qm");
				print "<b>".$pgv_lang["common_surnames"]."</b><br />\n";
				$i=0;
				foreach($surnames as $indexval => $surname) {
					if (stristr($surname["name"], "@N.N")===false) {
						if ($i>0) {
							print ", ";
						}
						print "<a href=\"indilist.php?ged=".$GEDCOM."&amp;surname=".urlencode($surname["name"])."\">".PrintReady($surname["name"])."</a>";
						$i++;
					}
				}
			}
		}

		print "</div>\n";
		print "</div>";
}

function print_gedcom_stats_config($config) {
	global $pgv_lang, $PGV_BLOCKS, $TEXT_DIRECTION;
	if (empty($config)) $config = $PGV_BLOCKS["print_gedcom_stats"]["config"];
	if (!isset($config['stat_indi'])) $config = $PGV_BLOCKS["print_gedcom_stats"]["config"];

	print "<tr><td class=\"descriptionbox width20\">".$pgv_lang["gedcom_stats_show_surnames"]."</td>";?>
	<td class="optionbox">
		<select name="show_common_surnames">
			<option value="yes"<?php if ($config["show_common_surnames"]=="yes") print " selected=\"selected\"";?>><?php print $pgv_lang["yes"]; ?></option>
			<option value="no"<?php if ($config["show_common_surnames"]=="no") print " selected=\"selected\"";?>><?php print $pgv_lang["no"]; ?></option>
		</select>
	</td></tr>
	<tr>
		<td class="descriptionbox width20"><?php print $pgv_lang["stats_to_show"]; ?></td>
		<td class="optionbox">
			<table>
				<tr>
					<td><input type="checkbox" value="yes" name="stat_indi" <?php if ($config['stat_indi']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_individuals"]; ?></td>
					<td><input type="checkbox" value="yes" name="stat_first_birth" <?php if ($config['stat_first_birth']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_earliest_birth"]; ?></td>
				</tr>
				<tr>
					<td><input type="checkbox" value="yes" name="stat_surname" <?php if ($config['stat_surname']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_surnames"]; ?></td>
					<td><input type="checkbox" value="yes" name="stat_last_birth" <?php if ($config['stat_last_birth']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_latest_birth"]; ?></td>
				</tr>
				<tr>
					<td><input type="checkbox" value="yes" name="stat_fam" <?php if ($config['stat_fam']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_families"]; ?></td>
					<td><input type="checkbox" value="yes" name="stat_first_death" <?php if ($config['stat_first_death']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_earliest_death"]; ?></td>
				</tr>
				<tr>
					<td><input type="checkbox" value="yes" name="stat_sour" <?php if ($config['stat_sour']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_sources"]; ?></td>
					<td><input type="checkbox" value="yes" name="stat_last_death" <?php if ($config['stat_last_death']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_latest_death"]; ?></td>
				</tr>
				<tr>
					<td><input type="checkbox" value="yes" name="stat_long_life" <?php if ($config['stat_long_life']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_longest_life"]; ?></td>
					<td><input type="checkbox" value="yes" name="stat_avg_life" <?php if ($config['stat_avg_life']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_avg_age_at_death"]; ?></td>
				</tr>
				<tr>
					<td><input type="checkbox" value="yes" name="stat_other" <?php if ($config['stat_other']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_other"]; ?></td>
					<td><input type="checkbox" value="yes" name="stat_most_chil" <?php if ($config['stat_most_chil']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_most_children"]; ?></td>
				</tr>
				<tr>
					<td><input type="checkbox" value="yes" name="stat_events" <?php if ($config['stat_events']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_events"]; ?></td>
					<td><input type="checkbox" value="yes" name="stat_avg_chil" <?php if ($config['stat_avg_chil']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_average_children"]; ?></td>
				</tr>
				<tr>
					<td><input type="checkbox" value="yes" name="stat_users" <?php if ($config['stat_users']=="yes") print "checked=\"checked\""; ?> /> <?php print $pgv_lang["stat_users"]; ?></td>
					<td><br /></td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
}
?>
