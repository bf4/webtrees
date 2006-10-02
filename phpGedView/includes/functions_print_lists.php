<?php
/**
 * Functions for printing lists
 *
 * Various printing functions for printing lists
 * used on the indilist, famlist, find, and search pages.
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
 * @subpackage Display
 * @version $Id$
 */
if (strstr($_SERVER["SCRIPT_NAME"],"functions")) {
	 print "Now, why would you want to do that. You're not hacking are you?";
	 exit;
}

/**
 * print a person in a list
 *
 * This function will print a
 * clickable link to the individual.php
 * page with the person's name
 * lastname, firstname and their
 * birthplace and date
 * @author John Finlay
 * @param string $key the GEDCOM xref id of the person to print
 * @param array $value is an array of the form array($name, $GEDCOM)
 */
function print_list_person($key, $value, $findid=false, $asso="", $useli=true) {
	global $pgv_lang, $SCRIPT_NAME, $pass, $indi_private, $indi_hide, $indi_total, $factarray;
	global $GEDCOM, $SHOW_ID_NUMBERS, $TEXT_DIRECTION, $SHOW_PEDIGREE_PLACES, $PGV_IMAGE_DIR, $PGV_IMAGES, $SHOW_DEATH_LISTS;

	if ($value[1]>=1) $value[1] = get_gedcom_from_id($value[1]);
	$GEDCOM = $value[1];
	if (!isset($indi_private)) $indi_private=array();
	if (!isset($indi_hide)) $indi_hide=array();
	if (!isset($indi_total)) $indi_total=array();
	$indi_total[$key."[".$GEDCOM."]"] = 1;

	$disp = displayDetailsByID($key);
	if (showLivingNameByID($key)||$disp) {
		if (begRTLText($value[0])) $listDir = "rtl";
		else $listDir = "ltr";
		$tag = "span";
		if ($useli) $tag = "li";
		print "<".$tag." class=\"".$listDir."\" dir=\"".$listDir."\">";
		if ($findid == true) print "<a href=\"javascript:;\" onclick=\"pasteid('".$key."', '".preg_replace("/(['\"])/", "\\$1", PrintReady($value[0]))."'); return false;\" class=\"list_item\"><b>".$value[0]."</b>";
		else print "<a href=\"individual.php?pid=$key&amp;ged=$value[1]\" class=\"list_item\"><b>".PrintReady($value[0])."</b>";
		if ($SHOW_ID_NUMBERS){
			print "&nbsp;&nbsp;";
			if ($listDir=="rtl") print "&rlm;";
			print "(".$key.")";
			if ($listDir=="rtl") print "&rlm;";
			print "&nbsp;&nbsp;";
		}

		if (!$disp) {
			print "<br /><i>".$pgv_lang["private"]."</i>";
			$indi_private[$key."[".$GEDCOM."]"] = 1;
		}
		else {
			print_first_major_fact($key, array("BIRT", "CHR", "BAPM", "BAPL", "ADOP"));
			print_first_major_fact($key, array("DEAT", "BURI"));
			/**
			$fact = print_first_major_fact($key);
			if (isset($SHOW_DEATH_LISTS) && $SHOW_DEATH_LISTS==true) {
				if ($fact!="DEAT") {
					$indirec = find_person_record($key);
					$factrec = get_sub_record(1, "1 DEAT", $indirec);
					if (strlen($factrec)>7 && showFact("DEAT", $key) && !FactViewRestricted($key, $factrec)) {
						print " -- <i>";
						print $factarray["DEAT"];
						print " ";
						print_fact_date($factrec);
						print_fact_place($factrec);
						print "</i>";
					}
				}
			}
			**/
		}
		print "</a>";
		if (($asso != "") && ($disp)) {
			$p1 = strpos($asso,"[");
			$p2 = strpos($asso,"]");
			$ged = substr($asso,$p1+1,$p2-$p1-1);
			if ($ged>=1) $ged = get_gedcom_from_id($ged);
			$key = substr($asso,0,$p1);
			$oldged = $GEDCOM;
			$GEDCOM = $ged;
			$name = get_person_name($key);
			$GEDCOM = $oldged;
			print " <a href=\"individual.php?pid=$key&amp;ged=$ged\" title=\"$name\" class=\"list_item\">";
			print "&nbsp;&nbsp;";
			if ($TEXT_DIRECTION=="ltr") print "(".$pgv_lang["associate"]."&nbsp;&nbsp;".$key.")";
  			else print "&rlm;(&rlm;".$pgv_lang["associate"]."&nbsp;&nbsp;".$key."&rlm;)&rlm;</span></a>";
		}
//		if ($useli) print "</li>";
		print "</".$tag.">";
	}
	else {
		$pass = TRUE;
		$indi_hide[$key."[".$GEDCOM."]"] = 1;
	}
}

/**
 * print a family in a list
 *
 * This function will print a
 * clickable link to the family.php
 * @param string $key the GEDCOM xref id of the person to print
 * @param array $value is an array of the form array($name, $GEDCOM)
 */
function print_list_family($key, $value, $findid=false, $asso="", $useli=true) {
	global $pgv_lang, $pass, $fam_private, $fam_hide, $fam_total, $SHOW_ID_NUMBERS;
	global $GEDCOM, $HIDE_LIVE_PEOPLE, $SHOW_PEDIGREE_PLACES;
	global $TEXT_DIRECTION;
	$GEDCOM = $value[1];
	if (!isset($fam_private)) $fam_private=array();
	if (!isset($fam_hide)) $fam_hide=array();
	if (!isset($fam_total)) $fam_total=array();
	$fam_total[$key."[".$GEDCOM."]"] = 1;
	$famrec=find_family_record($key);
	$display = displayDetailsByID($key, "FAM");
	$showLivingHusb=true;
	$showLivingWife=true;
	$parents = find_parents($key);
	//-- check if we can display both parents
	if (!$display) {
		$showLivingHusb=showLivingNameByID($parents["HUSB"]);
		$showLivingWife=showLivingNameByID($parents["WIFE"]);
	}
	if ($showLivingWife && $showLivingHusb) {
		if (begRTLText($value[0])) $listDir = "rtl";
		else $listDir = "ltr";
		if ($useli) $tag = "li";
		else $tag = "span";
		print "<".$tag." class=\"".$listDir."\" dir=\"".$listDir."\">";
		if ($findid == true) print "<a href=\"javascript:;\" onclick=\"pasteid('".$key."'); return false;\" class=\"list_item\"><b>".PrintReady($value[0])."</b>";
		else print "<a href=\"family.php?famid=$key&amp;ged=$value[1]\" class=\"list_item\"><b>".PrintReady($value[0])."</b>";
		if ($SHOW_ID_NUMBERS) {
			print "&nbsp;&nbsp;";
			if ($listDir=="rtl") print "&rlm;";
			print "(".$key.")";
			if ($listDir=="rtl") print "&rlm;";
			print "&nbsp;&nbsp;";
		}
		if (!$display) {
			print "<br /><i>".$pgv_lang["private"]."</i>";
			$fam_private[$key."[".$GEDCOM."]"] = 1;
		}
		else {
			print_first_major_fact($key, array("MARR"));
			print_first_major_fact($key, array("DIV"));
		}
		print "</a>";
		if ($asso != "") {
			$p1 = strpos($asso,"[");
			$p2 = strpos($asso,"]");
			$ged = substr($asso,$p1+1,$p2-$p1-1);
			$indikey = substr($asso,0,$p1);
			$oldged = $GEDCOM;
			$GEDCOM = $ged;
			$name = get_person_name($key);
			$GEDCOM = $oldged;
			print " <a href=\"individual.php?pid=$indikey&amp;ged=$ged\" title=\"$name\" class=\"list_item\">";
			print "&nbsp;&nbsp;";
			if ($TEXT_DIRECTION=="ltr") print "(".$pgv_lang["associate"]."&nbsp;&nbsp;".$indikey.")</a>";
  			else print "&rlm;(&rlm;".$pgv_lang["associate"]." &nbsp;&nbsp;".$indikey."&rlm;)&rlm;</span></a>";
		}
		print "</".$tag.">";
	}															//begin re-added by pluntke
	if (!$showLivingWife || !$showLivingHusb) {				   	//fixed THIS line (changed && to ||)
		$pass = TRUE;
		$fam_hide[$key."[".$GEDCOM."]"] = 1;
	}															//end re-added by pluntke
}

/**
 * print a source in a list
 *
 * This function will print a
 * clickable link to the source.php
 * page with the source's name
 * @param string $key the GEDCOM xref id of the person to print
 * @param array $value is an array of the form array($name, $GEDCOM)
 */
function print_list_source($key, $value, $useli=true) {
	global $source_total, $source_hide, $SHOW_SOURCES, $SHOW_ID_NUMBERS, $GEDCOM, $TEXT_DIRECTION;

	$GEDCOM = get_gedcom_from_id($value["gedfile"]);
	if (!isset($source_total)) $source_total=array();
	$source_total[$key."[".$GEDCOM."]"] = 1;
	if (displayDetailsByID($key, "SOUR")) {
		if (begRTLText($value["name"])) $listDir = "rtl";
		else $listDir = "ltr";
		if ($useli) $tag = "li";
		else $tag = "span";
		print "\n\t\t\t<".$tag." class=\"".$listDir."\" dir=\"".$listDir."\">";
		print "\n\t\t\t<a href=\"source.php?sid=$key&amp;ged=".get_gedcom_from_id($value["gedfile"])."\" class=\"list_item\"><b>".PrintReady($value["name"])."</b>";
		if ($SHOW_ID_NUMBERS) {
			print "&nbsp;&nbsp;";
			if ($listDir=="rtl") print "&rlm;(".$key.")&rlm;";
			else print "&lrm;(".$key.")&lrm;";
		}
		print "</a>\n";
		print "</".$tag.">\n";
	}
	else $source_hide[$key."[".$GEDCOM."]"] = 1;
}

/**
 * print a repository in a list
 *
 * This function will print a
 * clickable link to the repo.php
 * @param string $key the GEDCOM xref id of the person to print
 * @param array $value is an array of the form array($name, $GEDCOM)
 */
function print_list_repository($key, $value, $useli=true) {
	global $repo_total, $repo_hide, $SHOW_ID_NUMBERS, $GEDCOM, $TEXT_DIRECTION;

	$GEDCOM = get_gedcom_from_id($value["gedfile"]);
	if (!isset($repo_total)) $repo_total=array();
	$repo_total[$key."[".$GEDCOM."]"] = 1;
	if (displayDetailsByID($key, "REPO")) {
		if (begRTLText($value["name"])) $listDir = "rtl";
		else $listDir = "ltr";
		if ($useli) $tag = "li";
		else $tag = "span";
		print "\n\t\t\t<".$tag." class=\"".$listDir."\" dir=\"".$listDir."\">";
		$id = $value["id"];
		print "<a href=\"repo.php?rid=$id\" class=\"list_item\">";
		print PrintReady($value["name"]);
		if ($SHOW_ID_NUMBERS) {
			print "&nbsp;&nbsp;";
			if ($listDir=="rtl") print "&rlm;(".$id.")&rlm;";
			else print "&lrm;(".$id.")&lrm;";
		}
		print "</a></".$tag.">\n";
	}
	else $repo_hide[$key."[".$GEDCOM."]"] = 1;
}

/**
 * print a sortable table of individuals
 *
 * @param array $datalist contain individuals that were extracted from the database.
 */
function print_indi_table($datalist, $title="") {
	global $pgv_lang, $factarray, $LANGUAGE, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $SHOW_MARRIED_NAMES, $TEXT_DIRECTION;
	if (count($datalist)<1) return;
	$name_subtags = array("", "_HEB", "ROMN", "_AKA");
	if ($SHOW_MARRIED_NAMES) $name_subtags[] = "_MARNM";
?>
	<script type="text/javascript" src="strings.js"></script>
	<script type="text/javascript" src="js/kryogenix.org/sorttable.js"></script>
<?php
	require_once("includes/person_class.php");
	if (empty($title)) $title=$pgv_lang["individuals"];
	echo "<fieldset><legend>".$title."</legend>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID

	//-- filter buttons
	$person = new Person("");
	echo "<button type=\"button\" class=\"sexM\" title=\"".$pgv_lang["male"]
	."\" onclick=\"return table_filter('".$table_id."', 'SEX', 'M')\">";
	$person->sex = "M"; echo $person->getSexImage()."&nbsp;</button> ";
	echo "<button type=\"button\" class=\"sexF\" title=\"".$pgv_lang["female"]
	."\" onclick=\"return table_filter('".$table_id."', 'SEX', 'F')\">";
	$person->sex = "F"; echo $person->getSexImage()."&nbsp;</button> ";
	echo "<button type=\"button\" class=\"sexU\" title=\"".$pgv_lang["unknown"]
	."\" onclick=\"return table_filter('".$table_id."', 'SEX', 'U')\">";
	$person->sex = "U"; echo $person->getSexImage()."&nbsp;</button> ";
	echo "<button type=\"button\" class=\"alive\" onclick=\"return table_filter('".$table_id."', 'DEAT', 'N')\">";
	echo $pgv_lang["alive"]."</button> ";
	echo "<button type=\"button\" class=\"dead\" onclick=\"return table_filter('".$table_id."', 'DEAT', 'Y')\">";
	echo $pgv_lang["dead"]."</button> ";
	echo "<button type=\"button\" class=\"roots\" onclick=\"return table_filter('".$table_id."', 'TREE', 'R')\">";
	echo $pgv_lang["roots"]."</button> ";
	echo "<button type=\"button\" class=\"leaves\" onclick=\"return table_filter('".$table_id."', 'TREE', 'L')\">";
	echo $pgv_lang["leaves"]."</button> ";
	echo "<br />";
	$y100 = get_changed_date(date('Y')-100);
	echo "<button type=\"button\" class=\"BIRT_Y\" onclick=\"return table_filter('".$table_id."', 'BIRT', 'YES')\">";
	echo $factarray["BIRT"]."&gt;100</button> ";
	echo "<button type=\"button\" class=\"BIRT_Y100\" onclick=\"return table_filter('".$table_id."', 'BIRT', 'Y100')\">";
	echo $factarray["BIRT"]."&lt;=100</button> ";
	echo "<button type=\"button\" class=\"DEAT_Y\" onclick=\"return table_filter('".$table_id."', 'DEAT', 'YES')\">";
	echo $factarray["DEAT"]."&gt;100</button> ";
	echo "<button type=\"button\" class=\"DEAT_Y100\" onclick=\"return table_filter('".$table_id."', 'DEAT', 'Y100')\">";
	echo $factarray["DEAT"]."&lt;=100</button> ";
	echo "<br />";
	echo $pgv_lang["year"];
	echo " <input type=\"text\" size=\"3\" id=\"aliveyear\" value=\"".date('Y')."\" /> ";
	echo "<button type=\"button\" class=\"alive_in_year\" onclick=\"return table_filter_alive('".$table_id."')\">";
	echo $pgv_lang["alive_in_year"]."</button> ";
	echo "<button type=\"button\" class=\"reset\" onclick=\"return table_filter('".$table_id."', '', '')\">";
	echo $pgv_lang["reset"]."</button> ";

	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	echo "<th class=\"list_label rela\">#</th>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$factarray["NAME"]."</th>";
	echo "<th class=\"list_label\">".$factarray["BIRT"]."</th>";
	echo "<th class=\"list_label\">".$factarray["PLAC"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["children"]."</th>";
	echo "<th class=\"list_label\">".$factarray["DEAT"]."</th>";
	echo "<th class=\"list_label\">".$factarray["PLAC"]."</th>";
	if ($SHOW_LAST_CHANGE) echo "<th class=\"list_label rela\">".$factarray["CHAN"]."</th>";
	echo "<th class=\"list_label\" style=\"display:none\">SEX</th>";
	echo "<th class=\"list_label\" style=\"display:none\">BIRT</th>";
	echo "<th class=\"list_label\" style=\"display:none\">DEAT</th>";
	echo "<th class=\"list_label\" style=\"display:none\">TREE</th>";
	echo "</tr>\n";

	//-- table body
	$n = 1;
	foreach($datalist as $key => $value) {
		//print_r($value);
		if (!is_array($value)) {
			$gid = $value;
			if (empty($gid)) continue;
			$person = Person::getInstance($gid);
		}
		else {
			if (isset($value["gid"])) $gid = $value["gid"]; // from indilist
			if (isset($value[4])) $gid = $value[4]; // from indilist ALL

			if (isset($value["gedcom"])) $person = new Person($value["gedcom"]); // from source.php
			else $person = Person::getInstance($gid);
		}
		//if (!$person->canDisplayName()) continue;
		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".$n++."</td>";

		if ($SHOW_ID_NUMBERS) {
			echo "<td class=\"list_value_wrap rela\">";
			echo "<a href=\"".$person->getLinkUrl()."\" class=\"list_item\">".$person->xref."</a></td>";
		}

		if ($person->isDead()) echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		else echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap alive\">";
		if (isset($value["name"]) and $person->canDisplayName()) $name = $value["name"];
		else $name = $person->getSortableName();
		echo "<a href=\"".$person->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		echo $person->getSexImage();
		foreach ($name_subtags as $key=>$subtag) {
			$addname = $person->getSortableName($subtag);
			if (!empty($addname) and $addname!=$name) echo "<br /><a title=\"".$subtag."\" href=\"".$person->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a>";
		}
		echo "</td>";

		echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap\">";
		echo "<code style=\"display:none\">".$person->getSortableBirthDate()."</code>"; // store hidden sortable datetime
		if (!$person->best) echo "<a href=\"".$person->getDateUrl($person->bdate)."\" class=\"list_item\">".get_changed_date($person->getBirthDate())."</a>";
		echo "&nbsp;</td>";

		echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		echo "<a href=\"".$person->getPlaceUrl($person->getBirthPlace())."\" class=\"list_item\">".$person->getPlaceShort($person->getBirthPlace())."</a>";
		echo "&nbsp;</td>";

		echo "<td class=\"list_value_wrap\">";
		echo "<a href=\"".$person->getLinkUrl()."\" class=\"list_item\">".$person->getNumberOfChildren()."</a>";
		echo "</td>";

		echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap\">";
		echo "<code style=\"display:none\">".$person->getSortableDeathDate()."</code>"; // store hidden sortable datetime
		if ($person->isDead()) {
			if ($person->dest) echo "<span class=\"list_item\">".$pgv_lang["yes"]."</span>";
			else echo "<a href=\"".$person->getDateUrl($person->ddate)."\" class=\"list_item\">".get_changed_date($person->getDeathDate())."</a>";
		}
		echo "&nbsp;</td>";

		echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		echo "<a href=\"".$person->getPlaceUrl($person->getDeathPlace())."\" class=\"list_item\">".$person->getPlaceShort($person->getDeathPlace())."</a>";
		echo "&nbsp;</td>";

		if ($SHOW_LAST_CHANGE) {
			echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap rela\">";
			echo "<code style=\"display:none\">".$person->getSortableLastchangeDate()."</code>"; // store hidden sortable datetime
			$timestamp = get_changed_date($person->getLastchangeDate())." ".get_gedcom_value("DATE:TIME", 2, $person->getLastchangeRecord());
			echo "<a href=\"".$person->getLinkUrl()."\" class=\"list_item\">".$timestamp."</a>";
			echo "&nbsp;</td>";
		}

		echo "<td style=\"display:none\">";
		echo $person->getSex();
		echo "</td>";

		echo "<td style=\"display:none\">";
		if (!$person->disp or $person->getBirthYear()>=date('Y')-100) echo "Y100";
		else echo "YES";
		echo "</td>";

		echo "<td style=\"display:none\">";
		if ($person->isDead()) {
			if ($person->getDeathYear()>=date('Y')-100) echo "Y100";
			else echo "YES";
		}
		else echo "N";
		echo "</td>";

		echo "<td style=\"display:none\">";
		if (!$person->getChildFamilyIds()) echo "R"; // roots
		else if (!$person->isDead() and $person->getNumberOfChildren()<1) echo "L"; // leaves
		echo "</td>";

		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "</fieldset>\n";
}

/**
 * print a sortable table of families
 *
 * @param array $datalist contain families that were extracted from the database.
 */
function print_fam_table($datalist, $title="") {
	global $pgv_lang, $factarray, $LANGUAGE, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $SHOW_MARRIED_NAMES, $TEXT_DIRECTION;
	if (count($datalist)<1) return;
	$name_subtags = array("", "_HEB", "ROMN", "_AKA");
	//if ($SHOW_MARRIED_NAMES) $name_subtags[] = "_MARNM";
?>
	<script type="text/javascript" src="strings.js"></script>
	<script type="text/javascript" src="js/kryogenix.org/sorttable.js"></script>
<?php
	require_once("includes/family_class.php");
	if (empty($title)) $title=$pgv_lang["families"];
	echo "<fieldset><legend>".$title."</legend>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID

	//-- filter buttons
	echo "<button type=\"button\" class=\"both_alive\" onclick=\"return table_filter('".$table_id."', 'DEAT', 'N')\">";
	echo $pgv_lang["both_alive"]."</button> ";
	echo "<button type=\"button\" class=\"widower\" onclick=\"return table_filter('".$table_id."', 'DEAT', 'W')\">";
	echo $pgv_lang["widower"]."</button> ";
	echo "<button type=\"button\" class=\"widow\" onclick=\"return table_filter('".$table_id."', 'DEAT', 'H')\">";
	echo $pgv_lang["widow"]."</button> ";
	echo "<button type=\"button\" class=\"both_dead\" onclick=\"return table_filter('".$table_id."', 'DEAT', 'Y')\">";
	echo $pgv_lang["both_dead"]."</button> ";
	echo "<button type=\"button\" class=\"roots\" onclick=\"return table_filter('".$table_id."', 'TREE', 'R')\">";
	echo $pgv_lang["roots"]."</button> ";
	echo "<button type=\"button\" class=\"leaves\" onclick=\"return table_filter('".$table_id."', 'TREE', 'L')\">";
	echo $pgv_lang["leaves"]."</button> ";
	echo "<br />";
	$y100 = get_changed_date(date('Y')-100);
	echo "<button type=\"button\" class=\"NMR\" onclick=\"return table_filter('".$table_id."', 'MARR', '?')\">";
	echo $factarray["_NMR"]." ?</button> ";
	echo "<button type=\"button\" class=\"MARR_Y\" onclick=\"return table_filter('".$table_id."', 'MARR', 'YES')\">";
	echo $factarray["MARR"]."&gt;100</button> ";
	echo "<button type=\"button\" class=\"MARR_Y100\" onclick=\"return table_filter('".$table_id."', 'MARR', 'Y100')\">";
	echo $factarray["MARR"]."&lt;=100</button> ";
	echo "<button type=\"button\" class=\"DIV\" onclick=\"return table_filter('".$table_id."', 'MARR', 'DIV')\">";
	echo $factarray["DIV"]."</button> ";
	echo "<button type=\"button\" class=\"reset\" onclick=\"return table_filter('".$table_id."', '', '')\">";
	echo $pgv_lang["reset"]."</button> ";

	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	echo "<th class=\"list_label rela\">#</th>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$factarray["NAME"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["spouse"]."</th>";
	echo "<th class=\"list_label\">".$factarray["MARR"]."</th>";
	echo "<th class=\"list_label\">".$factarray["PLAC"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["children"]."</th>";
	if ($SHOW_LAST_CHANGE) echo "<th class=\"list_label rela\">".$factarray["CHAN"]."</th>";
	echo "<th class=\"list_label\" style=\"display:none\">MARR</th>";
	echo "<th class=\"list_label\" style=\"display:none\">DEAT</th>";
	echo "<th class=\"list_label\" style=\"display:none\">TREE</th>";
	echo "</tr>\n";

	//-- table body
	$n = 1;
	foreach($datalist as $key => $value) {
		if (isset($value["gid"])) $gid = $value["gid"];

		if (isset($value["gedcom"])) $family = new Family($value["gedcom"]);
		else $family = Family::getInstance($gid);

		$husb = $family->getHusband();
		if (is_null($husb)) $husb = new Person('');
		$wife = $family->getWife();
		if (is_null($wife)) $wife = new Person('');
		//if (!$husb->canDisplayName() and !$wife->canDisplayName()) continue;

		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".$n++."</td>";
		if ($SHOW_ID_NUMBERS) {
			echo "<td class=\"list_value_wrap rela\">";
			echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item\">".$family->xref."</a>";
			echo "</td>";
		}

		if ($husb->isDead()) echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		else echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap alive\">";
		$name = $husb->getSortableName();
		echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		if ($husb->xref) echo $husb->getSexImage();
		foreach ($name_subtags as $key=>$subtag) {
			$addname = $husb->getSortableName($subtag);
			if (!empty($addname) and $addname!=$name) echo "<br /><a title=\"".$subtag."\" href=\"".$family->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a>";
		}
		echo "</td>";

		if ($wife->isDead()) echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		else echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap alive\">";
		$name = $wife->getSortableName();
		echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		if ($wife->xref) echo $wife->getSexImage();
		foreach ($name_subtags as $key=>$subtag) {
			$addname = $wife->getSortableName($subtag);
			if (!empty($addname) and $addname!=$name) echo "<br /><a title=\"".$subtag."\" href=\"".$family->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a>";
		}
		echo "</td>";

		echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap\">";
		echo "<code style=\"display:none\">".$family->getSortableMarriageDate()."</code>"; // store hidden sortable datetime
		if (!$family->marr_est) echo "<a href=\"".$family->getDateUrl($family->marr_date)."\" class=\"list_item\">".get_changed_date($family->getMarriageDate())."</a>";
		echo "&nbsp;</td>";

		echo "<td class=\"".$TEXT_DIRECTION."  list_value_wrap\">";
		echo "<a href=\"".$family->getPlaceUrl($family->getMarriagePlace())."\" class=\"list_item\">".$family->getPlaceShort($family->getMarriagePlace())."</a>";
		echo "&nbsp;</td>";

		echo "<td class=\"list_value_wrap\">";
		echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item\">".$family->getNumberOfChildren()."</a>";
		echo "</td>";

		if ($SHOW_LAST_CHANGE) {
			echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap rela\">";
			echo "<code style=\"display:none\">".$family->getSortableLastchangeDate()."</code>"; // store hidden sortable datetime
			$timestamp = get_changed_date($family->getLastchangeDate())." ".get_gedcom_value("DATE:TIME", 2, $family->getLastchangeRecord());
			echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item\">".$timestamp."</a>";
			echo "&nbsp;</td>";
		}

		echo "<td style=\"display:none\">";
		if (!$family->disp or $family->getMarriageRecord()=="") echo "?";
		else if ($family->getMarriageYear()>=date('Y')-100) echo "Y100";
		else echo "YES";
		if ($family->isDivorced()) echo " DIV";
		echo "</td>";

		echo "<td style=\"display:none\">";
		if ($husb->isDead() and $wife->isDead()) echo "Y";
		if ($husb->isDead() and !$wife->isDead()) {
			if ($wife->getSex()=="F") echo "H";
			if ($wife->getSex()=="M") echo "W"; // male partners
		}
		if (!$husb->isDead() and $wife->isDead()) {
			if ($husb->getSex()=="M") echo "W";
			if ($husb->getSex()=="F") echo "H"; // female partners
		}
		if (!$husb->isDead() and !$wife->isDead()) echo "N";
		echo "</td>";

		echo "<td style=\"display:none\">";
		if (!$husb->getChildFamilyIds() and !$wife->getChildFamilyIds()) echo "R"; // roots
		else if (!$husb->isDead() and !$wife->isDead() and $family->getNumberOfChildren()<1) echo "L"; // leaves
		echo "</td>";

		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "</fieldset>\n";
}

/**
 * print a sortable table of sources
 *
 * @param array $datalist contain sources that were extracted from the database.
 */
function print_sour_table($datalist, $title="") {
	global $pgv_lang, $factarray, $LANGUAGE, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $TEXT_DIRECTION;
	if (count($datalist)<1) return;
	$name_subtags = array("_HEB", "ROMN");
?>
	<script type="text/javascript" src="strings.js"></script>
	<script type="text/javascript" src="js/kryogenix.org/sorttable.js"></script>
<?php
	require_once("includes/source_class.php");
	if (empty($title)) $title=$pgv_lang["sources"];
	echo "<fieldset><legend>".$title."</legend>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID

	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	echo "<th class=\"list_label rela\">#</th>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$factarray["TITL"]."</th>";
	echo "<th class=\"list_label\">".$factarray["TITL"]."2</th>";
	echo "<th class=\"list_label\">".$factarray["AUTH"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["individuals"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["families"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["media"]."</th>";
	if ($SHOW_LAST_CHANGE) echo "<th class=\"list_label rela\">".$factarray["CHAN"]."</th>";
	echo "</tr>\n";

	//-- table body
	$n = 1;
	foreach ($datalist as $key => $value) {
		$source = new Source($value["gedcom"]);
		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".$n++."</td>";
		if ($SHOW_ID_NUMBERS) {
			echo "<td class=\"list_value_wrap rela\">";
			echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$source->xref."</a>";
			echo "</td>";
		}

		echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		$name = $source->getSortableName();
		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item name2\">".PrintReady($name)."</a>";
		echo "</td>";
		
		// alternate title in a new column
		echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		foreach ($name_subtags as $key=>$subtag) {
			$addname = $source->getSortableName($subtag);
			if (!empty($addname) and $addname!=$name) {
				echo "<a title=\"".$subtag."\" href=\"".$source->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a><br />";
			}
		}
		echo "</td>";

		echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".PrintReady($source->getAuth())."</a>";
		echo "&nbsp;</td>";

		echo "<td class=\"list_value_wrap\">";
//		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".count($source->getSourceIndis())."</a>";
		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$source->countSourceIndis()."</a>";
		echo "</td>";

		echo "<td class=\"list_value_wrap\">";
//		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".count($source->getSourceFams())."</a>";
		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$source->countSourceFams()."</a>";
		echo "</td>";

		echo "<td class=\"list_value_wrap\">";
		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$source->countSourceObjects()."</a>";
		echo "</td>";

		if ($SHOW_LAST_CHANGE) {
			echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap rela\">";
			echo "<code style=\"display:none\">".$source->getSortableLastchangeDate()."</code>"; // store hidden sortable datetime
			$timestamp = get_changed_date($source->getLastchangeDate())." ".get_gedcom_value("DATE:TIME", 2, $source->getLastchangeRecord());
			echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$timestamp."</a>";
			echo "&nbsp;</td>";
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "</fieldset>\n";
}

/**
 * print a sortable table of repositories
 *
 * @param array $datalist contain repositories that were extracted from the database.
 */
function print_repo_table($datalist, $title="") {
	global $pgv_lang, $factarray, $LANGUAGE, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $TEXT_DIRECTION;
	if (count($datalist)<1) return;
	$name_subtags = array("_HEB", "ROMN");
?>
	<script type="text/javascript" src="strings.js"></script>
	<script type="text/javascript" src="js/kryogenix.org/sorttable.js"></script>
<?php
	require_once("includes/repository_class.php");
	if (empty($title)) $title=$pgv_lang["repos_found"];
	echo "<fieldset><legend>".$title."</legend>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID

	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	echo "<th class=\"list_label rela\">#</th>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$factarray["NAME"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["sources"]."</th>";
	if ($SHOW_LAST_CHANGE) echo "<th class=\"list_label rela\">".$factarray["CHAN"]."</th>";
	echo "</tr>\n";

	//-- table body
	$n = 1;
	foreach ($datalist as $key => $value) {
		$repo = new Repository($value["gedcom"]);
		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".$n++."</td>";
		if ($SHOW_ID_NUMBERS) {
			echo "<td class=\"list_value_wrap rela\">";
			echo "<a href=\"".$repo->getLinkUrl()."\" class=\"list_item\">".$repo->xref."</a>";
			echo "</td>";
		}
		echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		$name = $repo->getSortableName();
		echo "<a href=\"".$repo->getLinkUrl()."\" class=\"list_item name2\">".PrintReady($name)."</a>";
		foreach ($name_subtags as $key=>$subtag) {
			$addname = $repo->getSortableName($subtag);
			if (!empty($addname) and $addname!=$name) echo "<br /><a title=\"".$subtag."\" href=\"".$repo->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a>";
		}
		echo "</td>";

		echo "<td class=\"list_value_wrap\">";
		echo "<a href=\"".$repo->getLinkUrl()."\" class=\"list_item\">".count($repo->getRepositorySours())."</a>";
		echo "</td>";

		if ($SHOW_LAST_CHANGE) {
			echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap rela\">";
			echo "<code style=\"display:none\">".$repo->getSortableLastchangeDate()."</code>"; // store hidden sortable datetime
			$timestamp = get_changed_date($repo->getLastchangeDate())." ".get_gedcom_value("DATE:TIME", 2, $repo->getLastchangeRecord());
			echo "<a href=\"".$repo->getLinkUrl()."\" class=\"list_item\">".$timestamp."</a>";
			echo "&nbsp;</td>";
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "</fieldset>\n";
}

/**
 * print a sortable table of media objects
 *
 * @param array $datalist contain media objects that were extracted from the database.
 */
function print_media_table($datalist, $title="") {
	global $pgv_lang, $factarray, $LANGUAGE, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $TEXT_DIRECTION;
	if (count($datalist)<1) return;
?>
	<script type="text/javascript" src="strings.js"></script>
	<script type="text/javascript" src="js/kryogenix.org/sorttable.js"></script>
<?php
	require_once("includes/media_class.php");
	if (empty($title)) $title=$pgv_lang["media"];
	echo "<fieldset><legend>".$title."</legend>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID

	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	echo "<th class=\"list_label rela\">#</th>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$factarray["TITL"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["media_linked"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["media_format"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["media_file_size"]."</th>";
	echo "<th class=\"list_label\">Width</th>";
	echo "<th class=\"list_label\">Height</th>";
	if ($SHOW_LAST_CHANGE) echo "<th class=\"list_label rela\">".$factarray["CHAN"]."</th>";
	echo "</tr>\n";

	//-- table body
	$n = 1;
	foreach ($datalist as $key => $value) {
		//print_r ($value);
		$media = new Media($value["GEDCOM"]);
		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".$n++."</td>";
		if ($SHOW_ID_NUMBERS) {
			echo "<td class=\"list_value_wrap rela\">";
			echo "<a href=\"".$media->getLinkUrl()."\" class=\"list_item\">".$media->xref."</a>";
			echo "</td>";
		}
		echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		$name = $media->getSortableName();
		echo "<a href=\"".$media->getLinkUrl()."\" class=\"list_item name2\">".PrintReady($name)."</a>";
		echo "</td>";

		echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		foreach ($value["LINKS"] as $k=>$v) {
		  if ($v=="INDI") $target = Person::getInstance($k);
		  else if ($v=="FAM") $target = Family::getInstance($k);
		  else if ($v=="SOUR") $target = Source::getInstance($k);
			echo "<a href=\"".$target->getLinkUrl()."\" class=\"list_item\">".PrintReady($target->getSortableName())."</a><br />";
		}
		echo "</td>";

		$imgsize = @getimagesize($media->file); // [0]=width [1]=height [2]=filetype
		$imageTypes = array("-","GIF", "JPG", "PNG", "SWF", "PSD", "BMP", "TIFF", "TIFF", "JPC", "JP2", "JPX", "JB2", "SWC", "IFF", "WBMP", "XBM");
		echo "<td class=\"list_value_wrap\">";
		echo "<a title=\"".$media->file."\" href=\"".$media->getLinkUrl()."\" class=\"list_item\">".$imageTypes[(0+$imgsize[2])]."</a>";
		echo "</td>";

		echo "<td class=\"list_value_wrap\">";
		if ($imgsize[2]) echo "<a href=\"".$media->getLinkUrl()."\" class=\"list_item\">".sprintf("%.1f", @filesize($media->file)/1024)."</a>";
		echo "</td>";

		echo "<td class=\"list_value_wrap\">";
		echo "<a href=\"".$media->getLinkUrl()."\" class=\"list_item\">".(0+$imgsize[0])."</a>";
		echo "</td>";

		echo "<td class=\"list_value_wrap\">";
		echo "<a href=\"".$media->getLinkUrl()."\" class=\"list_item\">".(0+$imgsize[1])."</a>";
		echo "</td>";
		
		if ($SHOW_LAST_CHANGE) {
			echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap rela\">";
			echo "<code style=\"display:none\">".$media->getSortableLastchangeDate()."</code>"; // store hidden sortable datetime
			$timestamp = get_changed_date($media->getLastchangeDate())." ".get_gedcom_value("DATE:TIME", 2, $media->getLastchangeRecord());
			echo "<a href=\"".$media->getLinkUrl()."\" class=\"list_item\">".$timestamp."</a>";
			echo "&nbsp;</td>";
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "</fieldset>\n";
}

/**
 * print a sortable table of recent changes
 *
 * @param array $datalist contain individuals that were extracted from the database.
 */
function print_changes_table($datalist) {
	global $pgv_lang, $factarray, $LANGUAGE, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $SHOW_MARRIED_NAMES, $TEXT_DIRECTION;
	if (count($datalist)<1) return;
?>
	<script type="text/javascript" src="strings.js"></script>
	<script type="text/javascript" src="js/kryogenix.org/sorttable.js"></script>
<?php
	require_once("includes/gedcomrecord.php");
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID

	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	echo "<th class=\"list_label rela\">#</th>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["record"]."</th>";
	echo "<th class=\"list_label\">".$factarray["CHAN"]."</th>";
	echo "<th class=\"list_label\">".$factarray["_PGVU"]."</th>";
	echo "</tr>\n";

	//-- table body
	$n = 1;
	foreach($datalist as $key => $value) {
		$gid = $value[0];
		$record = GedcomRecord::getInstance($gid);
		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".$n++."</td>";
		if ($SHOW_ID_NUMBERS) {
			echo "<td class=\"list_value_wrap rela\">";
			echo "<a href=\"".$record->getLinkUrl()."\" class=\"list_item\">".$record->xref."</a></td>";
		}

		echo "<td class=\"".$TEXT_DIRECTION." list_value_wrap\">";
		$name = $record->getSortableName();
		echo "<a href=\"".$record->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		if ($record->type=="INDI") echo $record->getSexImage();
		echo "</td>";

		echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap \">";
		echo "<code style=\"display:none\">".$record->getSortableLastchangeDate()."</code>"; // store hidden sortable datetime
		$timestamp = get_changed_date($record->getLastchangeDate())." ".get_gedcom_value("DATE:TIME", 2, $record->getLastchangeRecord());
		echo "<a href=\"".$record->getLinkUrl()."\" class=\"list_item\">".$timestamp."</a>";
		echo "&nbsp;</td>";

		echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap \">";
		echo "<a href=\"".$record->getLinkUrl()."\" class=\"list_item\">".$record->getLastchangeUser()."</a>";
		echo "&nbsp;</td>";

		echo "</tr>\n";
	}
	echo "</table>\n";
}
?>
