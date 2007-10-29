<?php
/**
 * Functions for printing lists
 *
 * Various printing functions for printing lists
 * used on the indilist, famlist, find, and search pages.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

require_once("includes/person_class.php");

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
	global $pgv_lang, $pass, $indi_private, $indi_hide, $indi_total;
	global $GEDCOM, $SHOW_ID_NUMBERS, $TEXT_DIRECTION;

	if ($value[1]>=1) $value[1] = get_gedcom_from_id($value[1]);
	$GEDCOM = $value[1];
	if (!isset($indi_private)) $indi_private=array();
	if (!isset($indi_hide)) $indi_hide=array();
	if (!isset($indi_total)) $indi_total=array();
	$indi_total[$key."[".$GEDCOM."]"] = 1;

	$tag = "span";
	if ($useli) $tag = "li";
		
	$person = Person::getInstance($key);
	if (is_null($person)) {
		print "<".$tag.">";
		print "<span class=\"error\">".$pgv_lang["unable_to_find_record"]." ".$key."</span>";
		print "</".$tag.">";
		return;
	}
	$disp = $person->canDisplayDetails();
	if ($person->canDisplayName()) {
		if (begRTLText($value[0])) $listDir = "rtl";
		else $listDir = "ltr";
		print "<".$tag." class=\"".$listDir."\" dir=\"".$listDir."\">";
		if ($findid == true) print "<a href=\"javascript:;\" onclick=\"pasteid('".$key."', '".preg_replace("/(['\"])/", "\\$1", PrintReady($value[0]." - ".$person->getBirthYear()))."'); return false;\" class=\"list_item\"><b>".$value[0]."</b>";
		else print "<a href=\"individual.php?pid=$key&amp;ged=$value[1]\" class=\"list_item\"><b>".PrintReady($value[0])."</b>";
		if ($SHOW_ID_NUMBERS){
			print "&nbsp;&nbsp;";
			if ($listDir=="rtl") print getRLM();
			print "(".$key.")";
			if ($listDir=="rtl") print getRLM();
			print "&nbsp;&nbsp;";
		}

		if (!$disp) {
			print "<br /><i>".$pgv_lang["private"]."</i>";
			$indi_private[$key."[".$GEDCOM."]"] = 1;
		}
		else {
			print_first_major_fact($key, array("BIRT", "CHR", "BAPM", "BAPL", "ADOP"));
			print_first_major_fact($key, array("DEAT", "BURI"));
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
  			else print getRLM() . "(" . getRLM() .$pgv_lang["associate"]."&nbsp;&nbsp;".$key. getRLM() . ")" . getRLM() . "</span></a>";
		}
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
	global $GEDCOM, $TEXT_DIRECTION;
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
			if ($listDir=="rtl") print getRLM();
			print "(".$key.")";
			if ($listDir=="rtl") print getRLM();
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
  			else print getRLM() . "(" . getRLM() .$pgv_lang["associate"]." &nbsp;&nbsp;".$indikey.getRLM() . ")" . getRLM() . "</span></a>";
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
	global $source_total, $source_hide, $SHOW_ID_NUMBERS, $GEDCOM;

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
			if ($listDir=="rtl") print getRLM() . "(".$key.")" . getRLM();
			else print getLRM() . "(".$key.")" . getLRM();
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
	global $repo_total, $repo_hide, $SHOW_ID_NUMBERS, $GEDCOM;

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
			if ($listDir=="rtl") print getRLM() . "(".$id.")" . getRLM();
			else print getLRM() . "(".$id.")" . getLRM();
		}
		print "</a></".$tag.">\n";
	}
	else $repo_hide[$key."[".$GEDCOM."]"] = 1;
}

/**
 * print a sortable table of individuals
 *
 * @param array $datalist contain individuals that were extracted from the database.
 * @param string $legend optional legend of the fieldset
 * @param string $option optional filtering option
 */
function print_indi_table($datalist, $legend="", $option="") {
	global $pgv_lang, $factarray, $LANGUAGE, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $SHOW_MARRIED_NAMES, $TEXT_DIRECTION, $GEDCOM_ID_PREFIX, $GEDCOM;
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $SEARCH_SPIDER;

	if (count($datalist)<1) return;
	$tiny = (count($datalist)<=500);
	if ($option=="MARR_PLAC") return;
	$name_subtags = array("", "_AKA", "_HEB", "ROMN");
	if ($SHOW_MARRIED_NAMES) $name_subtags[] = "_MARNM";
	require_once("js/sorttable.js.htm");
	require_once("includes/person_class.php");
	// legend
	if ($option=="BIRT_PLAC" || $option=="DEAT_PLAC") {
		$filter=$legend;
		$legend=$factarray[substr($option,0,4)]." @ ".$legend;
	}
	if ($legend == "") $legend = $pgv_lang["individuals"];
	$legend = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]."\" alt=\"\" align=\"middle\" /> ".$legend;
	print "<fieldset><legend>".$legend."</legend>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
	//-- filter buttons
	$person = new Person("");
	print "<button type=\"button\" class=\"SEX_M\" title=\"".$pgv_lang["button_SEX_M"]."\" >";
	$person->sex = "M"; print $person->getSexImage()."&nbsp;</button> ";
	print "<button type=\"button\" class=\"SEX_F\" title=\"".$pgv_lang["button_SEX_F"]."\" >";
	$person->sex = "F"; print $person->getSexImage()."&nbsp;</button> ";
	print "<button type=\"button\" class=\"SEX_U\" title=\"".$pgv_lang["button_SEX_U"]."\" >";
	$person->sex = "U"; print $person->getSexImage()."&nbsp;</button> ";
	print " <input type=\"text\" size=\"4\" id=\"aliveyear\" value=\"".date('Y')."\" /> ";
	print "<button type=\"button\" class=\"alive_in_year\" title=\"".$pgv_lang["button_alive_in_year"]."\" >";
	print $pgv_lang["alive_in_year"]."</button> ";
	print "<button type=\"button\" class=\"DEAT_N\" title=\"".$pgv_lang["button_DEAT_N"]."\" >";
	print $pgv_lang["alive"]."</button> ";
	print "<button type=\"button\" class=\"DEAT_Y\" title=\"".$pgv_lang["button_DEAT_Y"]."\" >";
	print $pgv_lang["dead"]."</button> ";
	print "<button type=\"button\" class=\"TREE_R\" title=\"".$pgv_lang["button_TREE_R"]."\" >";
	print $pgv_lang["roots"]."</button> ";
	print "<button type=\"button\" class=\"TREE_L\" title=\"".$pgv_lang["button_TREE_L"]."\" >";
	print $pgv_lang["leaves"]."</button> ";
	print "<br />";
	print "<button type=\"button\" class=\"BIRT_YES\" title=\"".$pgv_lang["button_BIRT_YES"]."\" >";
	print $factarray["BIRT"]."&gt;100</button> ";
	print "<button type=\"button\" class=\"BIRT_Y100\" title=\"".$pgv_lang["button_BIRT_Y100"]."\" >";
	print $factarray["BIRT"]."&lt;=100</button> ";
	print "<button type=\"button\" class=\"DEAT_YES\" title=\"".$pgv_lang["button_DEAT_YES"]."\" >";
	print $factarray["DEAT"]."&gt;100</button> ";
	print "<button type=\"button\" class=\"DEAT_Y100\" title=\"".$pgv_lang["button_DEAT_Y100"]."\" >";
	print $factarray["DEAT"]."&lt;=100</button> ";
	print "<button type=\"button\" class=\"reset\" title=\"".$pgv_lang["button_reset"]."\" >";
	print $pgv_lang["reset"]."</button> ";
	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	echo "<td></td>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela\">INDI</th>";
	echo "<th class=\"list_label\">".$factarray["NAME"]."</th>";
	echo "<th class=\"list_label\" style=\"display:none\">GIVN</th>";
	if ($option=="sosa") echo "<th class=\"list_label\">Sosa</th>";
	echo "<th class=\"list_label\">".$factarray["BIRT"]."</th>";
	if ($tiny) echo "<td class=\"list_label\"><img src=\"./images/reminder.gif\" alt=\"".$pgv_lang["anniversary"]."\" title=\"".$pgv_lang["anniversary"]."\" border=\"0\" /></td>";
	echo "<th class=\"list_label\">".$factarray["PLAC"]."</th>";
	if ($tiny) echo "<th class=\"list_label\"><img src=\"./images/children.gif\" alt=\"".$pgv_lang["children"]."\" title=\"".$pgv_lang["children"]."\" border=\"0\" /></th>";
	echo "<th class=\"list_label\">".$factarray["DEAT"]."</th>";
	if ($tiny) echo "<td class=\"list_label\"><img src=\"./images/reminder.gif\" alt=\"".$pgv_lang["anniversary"]."\" title=\"".$pgv_lang["anniversary"]."\" border=\"0\" /></td>";
	echo "<th class=\"list_label\">".$factarray["AGE"]."</th>";
	echo "<th class=\"list_label\">".$factarray["PLAC"]."</th>";
	if ($tiny && $SHOW_LAST_CHANGE) echo "<th class=\"list_label rela\">".$factarray["CHAN"]."</th>";
	echo "<th class=\"list_label\" style=\"display:none\">SEX</th>";
	echo "<th class=\"list_label\" style=\"display:none\">BIRT</th>";
	echo "<th class=\"list_label\" style=\"display:none\">DEAT</th>";
	echo "<th class=\"list_label\" style=\"display:none\">TREE</th>";
	echo "</tr>\n";
	//-- table body
	print "<tbody>\n";
	$hidden = 0;
	$n = 0;
	$d100y=new GedcomDate(date('Y')-100);  // 100 years ago
	$dateY = date("Y");
	$today_jd = GregorianToJD(date('m'), date('d'), date('Y'));
	foreach($datalist as $key => $value) {
		if (!is_array($value)) {
			$person = null;
			if (strpos($key, $GEDCOM_ID_PREFIX)!==false) $person = Person::getInstance($key); // from placelist
			if (is_null($person)) $person = Person::getInstance($value); // from ancestry chart and search
			if (!is_null($person)) $name = $person->getSortableName(); //-- for search results
		}
		else {
			$gid = $key;
			if (isset($value["gid"])) $gid = $value["gid"]; // from indilist
			if (isset($value[4])) $gid = $value[4]; // from indilist ALL
			$person = Person::getInstance($gid);
			if (isset($value["name"]) && $person->canDisplayName()) $name = $value["name"];
			else $name = $person->getSortableName();
			if (isset($value[4])) $name = $person->getSortableName($value[0]); // from indilist ALL
		}
		/* @var $person Person */
		if (is_null($person)) continue;
		if (!$person->canDisplayName()) {
			$hidden++;
			continue;
		}
		//-- place filtering
		if ($option=="BIRT_PLAC" && strstr($person->getBirthPlace(), $filter)===false) continue;
		if ($option=="DEAT_PLAC" && strstr($person->getDeathPlace(), $filter)===false) continue;
		//-- Counter
		print "<tr>";
		print "<td class=\"list_value_wrap rela list_item\">".++$n."</td>";
		//-- Gedcom ID
		if ($SHOW_ID_NUMBERS)
			echo '<td class="list_value_wrap rela">'.$person->getXrefLink().'</td>';
		//-- SOSA
		if ($option=="sosa") {
			print "<td class=\"list_value_wrap\">";
			$sosa = $key;
			$rootid = $datalist[1];
			print "<a href=\"relationship.php?pid1=".$rootid."&amp;pid2=".$person->xref."\"".
			" class=\"list_item name2\">".$sosa."</a>";
			print "</td>";
		}
		//-- Indi name(s)
		if ($person->isDead()) print "<td class=\"list_value_wrap\"";
		else print "<td class=\"list_value_wrap alive\"";
		print " align=\"".get_align($name)."\">";
		print "<a href=\"".$person->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		if ($tiny) print $person->getSexImage();

		foreach ($name_subtags as $k=>$subtag) {
			for ($num=1; $num<9; $num++) {
				$addname = $person->getSortableName($subtag, $num);
				if (!empty($addname) && $addname!=$name) print "<br /><a title=\"".$subtag."\" href=\"".$person->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a>";
				if (empty($addname)) break;
			}
		}
		echo "</td>";
		//-- GIVN
		echo "<td style=\"display:none\">";
		$exp = explode(",", str_replace('<', ',', $name).",");
		echo $exp[1];
		echo "</td>";
		//-- SOSA
		if ($option=="sosa") {
			echo "<td class=\"list_value_wrap\">";
			$sosa = $key;
			$rootid = $datalist[1];
			echo "<a href=\"relationship.php?pid1=".$rootid."&amp;pid2=".$person->xref."\"".
			" title=\"".$pgv_lang["relationship_chart"]."\"".
			" name=\"{$sosa}\"".
			" class=\"list_item name2\">".$sosa."</a>";
			echo "</td>";
		}
		//-- Birth date
		print "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap\">";
		$bdate=$person->getBirthDate(false);
		print str_replace('<a', '<a name="'.$bdate->MinJD().'"', $bdate->Display(empty($SEARCH_SPIDER)));
		//-- Birth 2nd date ?
		if (!empty($person->bdate2)) {
			$bdate2=$person->bdate2;
			print '<br />'.$bdate2->Display(empty($SEARCH_SPIDER));
		}
		print "</td>";
		//-- Birth anniversary
		if ($tiny)
			echo "<td class=\"list_value_wrap rela\"><span class=\"age\">".GedcomDate::GetAgeYears($bdate)."</span></td>";
		//-- Birth place
		print "<td class=\"list_value_wrap\" align=\"".get_align($person->getBirthPlace())."\">";
		if(!empty($SEARCH_SPIDER)) {
			print PrintReady($person->getPlaceShort($person->getBirthPlace()));
		}
		else {
			print "<a href=\"".$person->getPlaceUrl($person->getBirthPlace())."\" class=\"list_item\" title=\"".$person->getBirthPlace()."\">"
			.PrintReady($person->getPlaceShort($person->getBirthPlace()))."</a>";
		}
		print "&nbsp;</td>";
		//-- Number of children
		if ($tiny) {
			print "<td class=\"list_value_wrap\">";
			if(!empty($SEARCH_SPIDER))
				print $person->getNumberOfChildren();
			else
				print "<a href=\"".$person->getLinkUrl()."\" class=\"list_item\" name=\"".$person->getNumberOfChildren()."\">".$person->getNumberOfChildren()."</a>";
			print "</td>";
		}
		//-- Death date
		print "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap\">";
		$ddate=$person->getDeathDate(false);
		print str_replace('<a', '<a name="'.$ddate->MaxJD().'"', $ddate->Display(empty($SEARCH_SPIDER)));
		//-- Death 2nd date ?
		if (!empty($person->ddate2)) {
			$ddate2=$person->ddate2;
			print '<br />'.$ddate2->Display(empty($SEARCH_SPIDER));
		}
		print "</td>";
		//-- Death anniversary
		if ($tiny) {
			print "<td class=\"list_value_wrap rela\">";
			if ($person->isDead() && !$person->dest) {
				echo "<span class=\"age\">".GedcomDate::GetAgeYears($ddate)."</span>";
			} else
				echo "&nbsp;";
			print '</td>';
		}
		//-- Age at death
		print "<td class=\"list_value_wrap\">";
		if ($person->isDead() && !$person->dest && $bdate->MinJD()>0)
			echo "<a name=\"".($ddate->MaxJD()-$bdate->MinJD())."\" class=\"list_item age\">".GedcomDate::GetAgeYears($bdate, $ddate)."</a>";
		else
			echo '<a name="-1">&nbsp;</a>';
		echo "</td>";
		//-- Death place
		print "<td class=\"list_value_wrap\" align=\"".get_align($person->getDeathPlace())."\">";
		if(!empty($SEARCH_SPIDER)) {
			print PrintReady($person->getPlaceShort($person->getDeathPlace()));
		}
		else {
			print "<a href=\"".$person->getPlaceUrl($person->getDeathPlace())."\" class=\"list_item\" title=\"".$person->getDeathPlace()."\">"
			.PrintReady($person->getPlaceShort($person->getDeathPlace()))."</a>";
		}
		print "&nbsp;</td>";
		//-- Last change
		if ($tiny && $SHOW_LAST_CHANGE)
			print "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap rela\">".$person->LastChangeTimestamp(empty($SEARCH_SPIDER))."</td>";
		//-- Sorting by gender
		print "<td style=\"display:none\">";
		print $person->getSex();
		print "</td>";
		//-- Filtering by birth date
		echo "<td style=\"display:none\">";
		if (!$person->disp || GedcomDate::Compare($bdate, $d100y)>0)
			echo "Y100";
		else
			echo "YES";
		echo "</td>";
		//-- Filtering by death date
		print "<td style=\"display:none\">";
		if ($person->isDead()) {
			if (GedcomDate::Compare($ddate, $d100y)>0)
				echo "Y100";
			else
				echo "YES";
		} else
			echo "N";
		echo "</td>";
		//-- Roots or Leaves ?
		echo "<td style=\"display:none\">";
		if (!$person->getChildFamilyIds()) echo "R"; // roots
		else if (!$person->isDead() && $person->getNumberOfChildren()<1) echo "L"; // leaves
		echo "</td>";

		echo "</tr>\n";
	}
	echo "</tbody>\n";
	//-- table footer
	echo "<tfoot><tr>";
	echo "<td></td>";
	if ($SHOW_ID_NUMBERS) echo "<td></td>"; // INDI:ID
	echo "<td class=\"list_label\">"; // NAME
	echo '<a href="javascript:;" onclick="sortByNextCol(this)"><img src="images/topdown.gif" alt="" border="0" /> '.$factarray["GIVN"].'</a><br />';
	echo $pgv_lang["total_names"]." : ".$n;
	if ($hidden) echo "<br /><span class=\"warning\">".$pgv_lang["hidden"]." : ".$hidden."</span>";
	echo "</td>";
	echo "<td style=\"display:none\">GIVN</td>";
	if ($option=="sosa") echo "<td></td>"; // SOSA
	echo "<td></td>"; // BIRT:DATE
	if ($tiny) echo "<td></td>"; // BIRT:Reminder
	echo "<td></td>"; // BIRT:PLAC
	if ($tiny) echo "<td></td>"; // Children
	echo "<td></td>"; // DEAT:DATE
	if ($tiny) echo "<td></td>"; // DEAT:Reminder
	echo "<td></td>"; // DEAT:AGE
	echo "<td></td>"; // DEAT:PLAC
	if ($tiny && $SHOW_LAST_CHANGE) echo "<td></td>"; // CHAN
	echo "<td style=\"display:none\">SEX</td>";
	echo "<td style=\"display:none\">BIRT</td>";
	echo "<td style=\"display:none\">DEAT</td>";
	echo "<td style=\"display:none\">TREE</td>";
	echo "</tr></tfoot>";
	echo "</table>\n";
	echo "</fieldset>\n";
}

/**
 * print a sortable table of families
 *
 * @param array $datalist contain families that were extracted from the database.
 * @param string $legend optional legend of the fieldset
 * @param string $option optional filtering option
 */
function print_fam_table($datalist, $legend="", $option="") {
	global $pgv_lang, $factarray, $LANGUAGE, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $SHOW_MARRIED_NAMES, $TEXT_DIRECTION, $GEDCOM;
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $SEARCH_SPIDER;

	if (count($datalist)<1) return;
	$tiny = (count($datalist)<=500);
	if ($option=="BIRT_PLAC" || $option=="DEAT_PLAC") return;
	$name_subtags = array("", "_AKA", "_HEB", "ROMN");
	//if ($SHOW_MARRIED_NAMES) $name_subtags[] = "_MARNM";
	require_once("js/sorttable.js.htm");
	require_once("includes/family_class.php");
	// legend
	if ($option=="MARR_PLAC") {
		$filter=$legend;
		$legend=$factarray["MARR"]." @ ".$legend;
	}
	if ($legend == "") $legend = $pgv_lang["families"];
	$legend = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["sfamily"]["small"]."\" alt=\"\" align=\"middle\" /> ".$legend;
	echo "<fieldset><legend>".$legend."</legend>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
	//-- filter buttons
	echo "<button type=\"button\" class=\"DEAT_N\" title=\"".$pgv_lang["button_DEAT_N"]."\" >";
	echo $pgv_lang["both_alive"]."</button> ";
	echo "<button type=\"button\" class=\"DEAT_W\" title=\"".$pgv_lang["button_DEAT_W"]."\" >";
	echo $pgv_lang["widower"]."</button> ";
	echo "<button type=\"button\" class=\"DEAT_H\" title=\"".$pgv_lang["button_DEAT_H"]."\" >";
	echo $pgv_lang["widow"]."</button> ";
	echo "<button type=\"button\" class=\"DEAT_Y\" title=\"".$pgv_lang["button_DEAT_Y"]."\" >";
	echo $pgv_lang["both_dead"]."</button> ";
	echo "<button type=\"button\" class=\"TREE_R\" title=\"".$pgv_lang["button_TREE_R"]."\" >";
	echo $pgv_lang["roots"]."</button> ";
	echo "<button type=\"button\" class=\"TREE_L\" title=\"".$pgv_lang["button_TREE_L"]."\" >";
	echo $pgv_lang["leaves"]."</button> ";
	echo "<br />";
	echo "<button type=\"button\" class=\"MARR_U\" title=\"".$pgv_lang["button_MARR_U"]."\" >";
	echo $factarray["MARR"]." ?</button> ";
	echo "<button type=\"button\" class=\"MARR_YES\" title=\"".$pgv_lang["button_MARR_YES"]."\" >";
	echo $factarray["MARR"]."&gt;100</button> ";
	echo "<button type=\"button\" class=\"MARR_Y100\" title=\"".$pgv_lang["button_MARR_Y100"]."\" >";
	echo $factarray["MARR"]."&lt;=100</button> ";
	echo "<button type=\"button\" class=\"MARR_DIV\" title=\"".$pgv_lang["button_MARR_DIV"]."\" >";
	echo $factarray["DIV"]."</button> ";
	echo "<button type=\"button\" class=\"reset\" title=\"".$pgv_lang["button_reset"]."\" >";
	echo $pgv_lang["reset"]."</button> ";
	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<thead><tr>";
	echo "<td class=\"sorttable_nosort\"></td>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela sorttable_numeric\">FAM</th>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela sorttable_numeric\">INDI</th>";
	echo "<th class=\"list_label\">".$factarray["NAME"]."</th>";
	echo "<th style=\"display:none\">HUSB:GIVN</th>";
	echo "<th class=\"list_label\">".$factarray["AGE"]."</th>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela\">INDI</th>";
	echo "<th class=\"list_label\">".$pgv_lang["spouse"]."</th>";
	echo "<th style=\"display:none\">WIFE:GIVN</th>";
	echo "<th class=\"list_label\">".$factarray["AGE"]."</th>";
	echo "<th class=\"list_label\">".$factarray["MARR"]."</th>";
	if ($tiny) echo "<td class=\"list_label sorttable_nosort\"><img src=\"./images/reminder.gif\" alt=\"".$pgv_lang["anniversary"]."\" title=\"".$pgv_lang["anniversary"]."\" border=\"0\" /></td>";
	echo "<th class=\"list_label\">".$factarray["PLAC"]."</th>";
	if ($tiny) 	echo "<th class=\"list_label\"><img src=\"./images/children.gif\" alt=\"".$pgv_lang["children"]."\" title=\"".$pgv_lang["children"]."\" border=\"0\" /></th>";
	if ($tiny && $SHOW_LAST_CHANGE) echo "<th class=\"list_label rela\">".$factarray["CHAN"]."</th>";
	echo "<th style=\"display:none\">MARR</th>";
	echo "<th style=\"display:none\">DEAT</th>";
	echo "<th style=\"display:none\">TREE</th>";
	echo "</tr></thead>\n";
	//-- table body
	echo "<tbody>\n";
	$hidden = 0;
	$n = 0;
	$d100y=new GedcomDate(date('Y')-100);  // 100 years ago
	foreach($datalist as $key => $value) {
		if (!is_array($value)) {
			$family = Family::getInstance($key); // from placelist
			if (is_null($family)) $family = Family::getInstance($value); // from ancestry chart
			unset($value);
		}
		else {
			$gid = "";
			if (isset($value["gid"])) $gid = $value["gid"];
			if (isset($value["gedcom"])) $family = new Family($value["gedcom"]);
			else $family = Family::getInstance($gid);
		}
		if (is_null($family)) continue;
		//-- Retrieve husband and wife
		$husb = $family->getHusband();
		if (is_null($husb)) $husb = new Person('');
		$wife = $family->getWife();
		if (is_null($wife)) $wife = new Person('');
		if (!$husb->canDisplayName() || !$wife->canDisplayName()) {
			$hidden++;
			continue;
		}
		//-- place filtering
		if ($option=="MARR_PLAC" && strstr($family->getMarriagePlace(), $filter)===false) continue;
		//-- Counter
		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".++$n."</td>";
		//-- Family ID
		if ($SHOW_ID_NUMBERS)
			echo '<td class="list_value_wrap rela">'.$family->getXrefLink().'</td>';
		//-- Husband ID
		if ($SHOW_ID_NUMBERS)
			echo '<td class="list_value_wrap rela">'.$husb->getXrefLink().'</td>';
		//-- Husband name(s)
		if (isset($value["name"])) {
			$partners = explode(" + ", $value["name"]); // "husb + wife"
			$name = check_NN($partners[0]);
		}
		else $name = $husb->getSortableName();
		if ($husb->isDead()) echo "<td class=\"list_value_wrap\"";
		else echo "<td class=\"list_value_wrap alive\"";
		echo " align=\"".get_align($name)."\">";
		echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		if ($tiny && $husb->xref) echo $husb->getSexImage();
 		foreach ($name_subtags as $k=>$subtag) {
 			for ($num=1; $num<9; $num++) {
 				$addname = $husb->getSortableName($subtag, $num);
 				if (!empty($addname) && $addname!=$name) echo "<br /><a title=\"".$subtag."\" href=\"".$family->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a>";
 				if (empty($addname)) break;
 			}
 		}
		echo "</td>";
		//-- Husb GIVN
		echo "<td style=\"display:none\">";
		$exp = explode(",", str_replace('<', ',', $name).",");
		echo $exp[1];
		echo "</td>";
		//-- Husband age
		echo "<td class=\"list_value_wrap\">";
		$mdate=$family->getMarriageDate();
		$hdate=$husb->GetBirthDate();
		if (is_null($hdate) || is_null($mdate))
			print "&nbsp;";
		else {
			$hage =GedcomDate::GetAgeYears($hdate, $mdate);
			print "<a name=\"".($mdate->MaxJD()-$hdate->MinJD())."\" class=\"list_item age\">{$hage}</a>";
		}
		echo "</td>";
		//-- Wife ID
		if ($SHOW_ID_NUMBERS)
			echo '<td class="list_value_wrap rela">'.$wife->getXrefLink().'</td>';
		//-- Wife name(s)
		if (isset($value["name"])) $name = check_NN($partners[1]);
		else $name = $wife->getSortableName();
		if ($wife->isDead()) echo "<td class=\"list_value_wrap\"";
		else echo "<td class=\"list_value_wrap alive\"";
		echo " align=\"".get_align($name)."\">";
		echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		if ($tiny && $wife->xref) echo $wife->getSexImage();
 		foreach ($name_subtags as $k=>$subtag) {
 			for ($num=1; $num<9; $num++) {
 				$addname = $wife->getSortableName($subtag, $num);
 				if (!empty($addname) && $addname!=$name) echo "<br /><a title=\"".$subtag."\" href=\"".$family->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a>";
 				if (empty($addname)) break;
 			}
 		}
		echo "</td>";
		//-- Wife GIVN
		echo "<td style=\"display:none\">";
		$exp = explode(",", str_replace('<', ',', $name).",");
		echo $exp[1];
		echo "</td>";
		//-- Wife age
		echo "<td class=\"list_value_wrap\">";
		$wdate=$wife->GetBirthDate();
		if (is_null($wdate) || is_null($mdate))
			print "&nbsp;";
		else {
			$wage =GedcomDate::GetAgeYears($wdate, $mdate);
			print "<a name=\"".($mdate->MaxJD()-$wdate->MinJD())."\" class=\"list_item age\">{$wage}</a>";
		}
		echo "</td>";
		//-- Marriage date
		echo "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap\">";
		print str_replace('<a', '<a name="'.$mdate->MinJD().'"', $mdate->Display(empty($SEARCH_SPIDER)));
		//-- Marriage 2nd date ?
		if (!empty($family->marr_date2)) {
			$mdate2=$family->marr_date2;
			print '<br />'.$mdate2->Display(empty($SEARCH_SPIDER));
		}
		echo "</td>";
		//-- Marriage anniversary
		if ($tiny)
			echo "<td class=\"list_value_wrap rela\"><span class=\"age\">".GedcomDate::GetAgeYears($mdate)."</span></td>";
		//-- Marriage place
		echo "<td class=\"list_value_wrap\" align=\"".get_align($family->getMarriagePlace())."\">";
		if(!empty($SEARCH_SPIDER)) {
			echo PrintReady($family->getPlaceShort($family->getMarriagePlace()));
		}
		else {
			echo "<a href=\"".$family->getPlaceUrl($family->getMarriagePlace())."\" class=\"list_item\" title=\"".$family->getMarriagePlace()."\">"
			.PrintReady($family->getPlaceShort($family->getMarriagePlace()))."</a>";
		}
		echo "&nbsp;</td>";
		//-- Number of children
		if ($tiny) {
			echo "<td class=\"list_value_wrap\">";
			if(!empty($SEARCH_SPIDER))
				echo $family->getNumberOfChildren();
			else
				echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item\" name=\"".$family->getNumberOfChildren()."\">".$family->getNumberOfChildren()."</a>";
			echo "</td>";
		}
		//-- Last change
		if ($tiny && $SHOW_LAST_CHANGE)
			print '<td class="'.strrev($TEXT_DIRECTION).' list_value_wrap rela">'.$family->LastChangeTimestamp(empty($SEARCH_SPIDER)).'</td>';
		//-- Sorting by marriage date
		echo "<td style=\"display:none\">";
		if (!$family->disp || $mdate->MinJD()==0)
			echo "U";
		else
			if (GedcomDate::Compare($mdate, $d100y)>0)
				echo "Y100";
			else
				echo "YES";
		if ($family->isDivorced())
			echo " DIV";
		echo "</td>";
		//-- Sorting alive/dead
		echo "<td style=\"display:none\">";
		if ($husb->isDead() && $wife->isDead()) echo "Y";
		if ($husb->isDead() && !$wife->isDead()) {
			if ($wife->getSex()=="F") echo "H";
			if ($wife->getSex()=="M") echo "W"; // male partners
		}
		if (!$husb->isDead() && $wife->isDead()) {
			if ($husb->getSex()=="M") echo "W";
			if ($husb->getSex()=="F") echo "H"; // female partners
		}
		if (!$husb->isDead() && !$wife->isDead()) echo "N";
		echo "</td>";
		//-- Roots or Leaves
		echo "<td style=\"display:none\">";
		if (!$husb->getChildFamilyIds() && !$wife->getChildFamilyIds()) echo "R"; // roots
		else if (!$husb->isDead() && !$wife->isDead() && $family->getNumberOfChildren()<1) echo "L"; // leaves
		echo "</td>";

		echo "</tr>\n";
	}
	echo "</tbody>\n";
	//-- table footer
	echo "<tfoot><tr>";
	echo "<td></td>";
	if ($SHOW_ID_NUMBERS) echo "<td></td>"; // FAM:ID
	if ($SHOW_ID_NUMBERS) echo "<td></td>"; // HUSB:ID
	echo "<td class=\"list_label\">"; // HUSB:NAME
	echo '<a href="javascript:;" onclick="sortByNextCol(this)"><img src="images/topdown.gif" alt="" border="0" /> '.$factarray["GIVN"].'</a><br />';
	echo $pgv_lang["total_fams"]." : ".$n;
	if ($hidden) echo "<br /><span class=\"warning\">".$pgv_lang["hidden"]." : ".$hidden."</span>";
	echo "</td>";
	echo "<td style=\"display:none\">HUSB:GIVN</td>";
	echo "<td></td>"; // HUSB:AGE
	if ($SHOW_ID_NUMBERS) echo "<td></td>"; // WIFE:ID
	echo "<td class=\"list_label\" style=\"vertical-align: top;\">"; // WIFE:NAME
	echo '<a href="javascript:;" onclick="sortByNextCol(this)"><img src="images/topdown.gif" alt="" border="0" /> '.$factarray["GIVN"].'</a><br />';
	echo "</td>";
	echo "<td style=\"display:none\">WIFE:GIVN</td>";
	echo "<td></td>"; // WIFE:AGE
	echo "<td></td>"; // MARR:DATE
	echo "<td></td>"; // MARR:Reminder
	echo "<td></td>"; // MARR:PLAC
	if ($tiny) echo "<td></td>"; // FAM:ChildrenCount
	if ($tiny && $SHOW_LAST_CHANGE) echo "<td></td>"; // FAM:CHAN
	echo "<td style=\"display:none\">MARR</td>";
	echo "<td style=\"display:none\">DEAT</td>";
	echo "<td style=\"display:none\">TREE</td>";
	echo "</tr></tfoot>";
	echo "</table>\n";
	echo "</fieldset>\n";
}

/**
 * print a sortable table of sources
 *
 * @param array $datalist contain sources that were extracted from the database.
 * @param string $legend optional legend of the fieldset
 */
function print_sour_table($datalist, $legend="") {
	global $pgv_lang, $factarray, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $TEXT_DIRECTION;
	global $PGV_IMAGE_DIR, $PGV_IMAGES;

	if (count($datalist)<1) return;
	$tiny = (count($datalist)<=500);
	$name_subtags = array("_HEB", "ROMN");
	require_once("js/sorttable.js.htm");
	require_once("includes/source_class.php");

	if ($legend == "") $legend = $pgv_lang["sources"];
	$legend = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["source"]["small"]."\" alt=\"\" align=\"middle\" /> ".$legend;
	echo "<fieldset><legend>".$legend."</legend>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<thead><tr>";
	echo "<td class=\"sorttable_nosort\"></td>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela sorttable_numeric\">SOUR</th>";
	echo "<th class=\"list_label\">".$factarray["TITL"]."</th>";
	$t2 = false; echo "<td class=\"list_label t2\">".$factarray["TITL"]."2</td>";
	echo "<th class=\"list_label\">".$factarray["AUTH"]."</th>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela sorttable_numeric\">REPO</th>";
	//-- only show the count of linked records if the DB is sufficiently small to handle the load
	$show_details = (get_list_size("indilist")<1000);
	if ($tiny && $show_details) {
		echo "<th class=\"list_label\">".$pgv_lang["individuals"]."</th>";
		echo "<th class=\"list_label\">".$pgv_lang["families"]."</th>";
		echo "<th class=\"list_label\">".$pgv_lang["media"]."</th>";
	}
	if ($tiny && $SHOW_LAST_CHANGE) echo "<th class=\"list_label rela\">".$factarray["CHAN"]."</th>";
	echo "</tr></thead>\n";
	//-- table body
	echo "<tbody>\n";
	$hidden = 0;
	$n = 0;
	foreach ($datalist as $key => $value) {
		if (!is_array($value)) {
			$source = Source::getInstance($key); // from placelist
			if (is_null($source)) $source = Source::getInstance($value);
			unset($value);
		}
		else {
			$gid = "";
			if (isset($value["gid"])) $gid = $value["gid"];
			if (isset($value["gedcom"])) $source = new Source($value["gedcom"]);
			else $source = Source::getInstance($gid);
		}
		if (is_null($source)) continue;
		if (!$source->canDisplayDetails()) {
			$hidden++;
			continue;
		}
		//-- Counter
		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".++$n."</td>";
		//-- Source ID
		if ($SHOW_ID_NUMBERS)
			echo '<td class="list_value_wrap rela">'.$source->getXrefLink().'</td>';
		//-- Source name(s)
		$name = $source->getSortableName();
		echo "<td class=\"list_value_wrap\" align=\"".get_align($name)."\">";
		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item name2\">".PrintReady($name)."</a>";
		echo "</td>";
		// alternate title in a new column
		echo "<td class=\"list_value_wrap t2\">";
		foreach ($name_subtags as $k=>$subtag) {
			$addname = $source->getSortableName($subtag);
			if (!empty($addname) && $addname!=$name) {
				echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a><br />";
				$t2 = true;
			}
		}
		echo "&nbsp;</td>";
		//-- Author
		echo "<td class=\"list_value_wrap\" align=\"".get_align($source->getAuth())."\">";
		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".PrintReady($source->getAuth())."</a>";
		echo "&nbsp;</td>";
		//-- REPO
		if ($SHOW_ID_NUMBERS) {
			echo "<td class=\"list_value_wrap rela\">";
			echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$source->getRepo()."</a>";
			echo "</td>";
		}
		if ($tiny && $show_details) { // $source->countSourceXXXX() is very slow.
			//-- Linked INDIs
			echo "<td class=\"list_value_wrap\">";
			echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$source->countSourceIndis()."</a>";
			echo "</td>";
			//-- Linked FAMs
			echo "<td class=\"list_value_wrap\">";
			echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$source->countSourceFams()."</a>";
			echo "</td>";
			//-- Linked OBJEcts
			echo "<td class=\"list_value_wrap\">";
			echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$source->countSourceObjects()."</a>";
			echo "</td>";
		}
		//-- Last change
		if ($tiny && $SHOW_LAST_CHANGE)
			print '<td class="'.strrev($TEXT_DIRECTION).' list_value_wrap rela">'.$source->LastChangeTimestamp(empty($SEARCH_SPIDER)).'</td>';
		echo "</tr>\n";
	}
	echo "</tbody>\n";
	//-- table footer
	echo "<tfoot><tr>";
	echo "<td></td>";
	if ($SHOW_ID_NUMBERS) echo "<td></td>";
	echo "<td class=\"list_label\">";
	echo $pgv_lang["total_sources"]." : ".$n;
	if ($hidden) echo "<br /><span class=\"warning\">".$pgv_lang["hidden"]." : ".$hidden."</span>";
	echo "</td>";
	echo "<td></td>";
	echo "<td class=\"t2\"></td>";
	echo "<td></td>";
	if ($tiny && $show_details) {
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
	}
	if ($tiny && $SHOW_LAST_CHANGE) echo "<td></td>";
	echo "</tr></tfoot>";
	echo "</table>\n";
	echo "</fieldset>\n";
	//-- hide TITLE2 col if empty
	if (!$t2) {
		echo <<< T2
		<script type="text/javascript">
		// <![CDATA[
			var table = document.getElementById("$table_id");
			cells = table.getElementsByTagName('td');
			for (i=0;i<cells.length;i++) if (cells[i].className && (cells[i].className.indexOf('t2') != -1)) cells[i].style.display='none';
		// ]]>
		</script>
T2;
	}
}

/**
 * print a sortable table of repositories
 *
 * @param array $datalist contain repositories that were extracted from the database.
 * @param string $legend optional legend of the fieldset
 */
function print_repo_table($datalist, $legend="") {
	global $pgv_lang, $factarray, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $TEXT_DIRECTION;
	global $PGV_IMAGE_DIR, $PGV_IMAGES;

	if (count($datalist)<1) return;
	$tiny = (count($datalist)<=500);
	$name_subtags = array("_HEB", "ROMN");
	require_once("js/sorttable.js.htm");
	require_once("includes/repository_class.php");

	if ($legend == "") $legend = $pgv_lang["repos_found"];
	$legend = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["repository"]["small"]."\" alt=\"\" align=\"middle\" /> ".$legend;
	echo "<fieldset><legend>".$legend."</legend>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<thead><tr>";
	echo "<td class=\"sorttable_nosort\"></td>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela sorttable_numeric\">REPO</th>";
	echo "<th class=\"list_label\">".$factarray["NAME"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["sources"]."</th>";
	if ($tiny && $SHOW_LAST_CHANGE) echo "<th class=\"list_label rela\">".$factarray["CHAN"]."</th>";
	echo "</tr></thead>\n";
	//-- table body
	echo "<tbody>\n";
	$hidden = 0;
	$n = 0;
	foreach ($datalist as $key => $value) {
		if (!is_array($value)) {
			$repo = Repository::getInstance($key);
			if (is_null($repo)) $repo = Repository::getInstance($value);
			unset($value);
		}
		else {
			$gid = "";
			if (isset($value["gid"])) $gid = $value["gid"];
			if (isset($value["gedcom"])) $repo = new Repository($value["gedcom"]);
			else $repo = Repository::getInstance($gid);
		}
		if (is_null($repo)) continue;
		if (!$repo->canDisplayDetails()) {
			$hidden++;
			continue;
		}
		//-- Counter
		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".++$n."</td>";
		//-- REPO ID
		if ($SHOW_ID_NUMBERS)
			echo '<td class="list_value_wrap rela">'.$repo->getXrefLink().'</td>';
		//-- Repository name(s)
		$name = $repo->getSortableName();
		echo "<td class=\"list_value_wrap\" align=\"".get_align($name)."\">";
		echo "<a href=\"".$repo->getLinkUrl()."\" class=\"list_item name2\">".PrintReady($name)."</a>";
		foreach ($name_subtags as $k=>$subtag) {
			$addname = $repo->getSortableName($subtag);
			if (!empty($addname) && $addname!=$name) echo "<br /><a title=\"".$subtag."\" href=\"".$repo->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a>";
		}
		echo "</td>";
		//-- Linked SOURces
		echo "<td class=\"list_value_wrap\">";
		echo "<a href=\"".$repo->getLinkUrl()."\" class=\"list_item\">".count($repo->getRepositorySours())."</a>";
		echo "</td>";
		//-- Last change
		if ($tiny && $SHOW_LAST_CHANGE)
			print '<td class="'.strrev($TEXT_DIRECTION).' list_value_wrap rela">'.$repo->LastChangeTimestamp(empty($SEARCH_SPIDER)).'</td>';
		echo "</tr>\n";
	}
	echo "</tbody>\n";
	//-- table footer
	echo "<tfoot><tr>";
	echo "<td></td>";
	if ($SHOW_ID_NUMBERS) echo "<td></td>";
	echo "<td class=\"list_label\">";
	echo $pgv_lang["total_repositories"]." : ".$n;
	if ($hidden) echo "<br />".$pgv_lang["hidden"]." : ".$hidden;
	echo "</td>";
	echo "<td></td>";
	if ($SHOW_LAST_CHANGE) echo "<td></td>";
	echo "</tr></tfoot>";
	echo "</table>\n";
	echo "</fieldset>\n";
}

/**
 * print a sortable table of media objects
 *
 * @param array $datalist contain media objects that were extracted from the database.
 * @param string $legend optional legend of the fieldset
 */
function print_media_table($datalist, $legend="") {
	global $pgv_lang, $factarray, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $TEXT_DIRECTION;
	global $PGV_IMAGE_DIR, $PGV_IMAGES;

	if (count($datalist)<1) return;
	require_once("js/sorttable.js.htm");
	require_once("includes/media_class.php");

	if ($legend == "") $legend = $pgv_lang["media"];
	$legend = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["media"]["small"]."\" alt=\"\" align=\"middle\" /> ".$legend;
	echo "<fieldset><legend>".$legend."</legend>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<thead><tr>";
	echo "<td class=\"sorttable_nosort\"></td>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela sorttable_numeric\">OBJE</th>";
	echo "<th class=\"list_label\">".$factarray["TITL"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["individuals"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["families"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["sources"]."</th>";
	if ($SHOW_LAST_CHANGE) echo "<th class=\"list_label rela\">".$factarray["CHAN"]."</th>";
	echo "</tr></thead>\n";
	//-- table body
	echo "<tbody>\n";
	$n = 0;
	foreach ($datalist as $key => $value) {
		$media = new Media($value["GEDCOM"]);
		if (is_null($media)) $media = Media::getInstance($key);
		if (is_null($media)) continue;
		//-- Counter
		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".++$n."</td>";
		//-- Object ID
		if ($SHOW_ID_NUMBERS)
			echo '<td class="list_value_wrap rela">'.$media->getXrefLink().'</td>';
		//-- Object name(s)
		$name = $media->getSortableName();
		echo "<td class=\"list_value_wrap\" align=\"".get_align($name)."\">";
		echo "<a href=\"".$media->getLinkUrl()."\" class=\"list_item name2\">".PrintReady($name)."</a>";
		echo "<br /><a href=\"".$media->getLinkUrl()."\">".basename($media->file)."</a>";
		echo "<br />".$media->getFiletype();
		echo "&nbsp;&nbsp;".$media->width."x".$media->height;
		echo "&nbsp;&nbsp;".$media->getFilesize()."kB";
		print_fact_notes("1 NOTE ".$media->getNote(),1);
		echo "</td>";
		//-- Linked records
		foreach (array("INDI", "FAM", "SOUR") as $rectype) {
			$resu = array();
			foreach ($value["LINKS"] as $k=>$v) {
			  if ($v!=$rectype) continue;
				$record = GedcomRecord::getInstance($k);
				$txt = $record->getSortableName();
				if ($SHOW_ID_NUMBERS) $txt .= " (".$k.")";
				$resu[] = $txt;
			}
			sort($resu);
			echo "<td class=\"list_value_wrap\" align=\"".get_align(@$resu[0])."\">";
			foreach ($resu as $txt) echo "<a href=\"".$record->getLinkUrl()."\" class=\"list_item\">".PrintReady("&bull; ".$txt)."</a><br />";
			echo "</td>";
		}
		//-- Last change
		if ($SHOW_LAST_CHANGE)
			print "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap rela\">".$media->LastChangeTimestamp(empty($SEARCH_SPIDER))."</td>";
		echo "</tr>\n";
	}
	echo "</tbody>\n";
	echo "</table>\n";
	echo "</fieldset>\n";
}

/**
 * print a tag cloud of surnames
 * print a sortable table of surnames
 *
 * @param array $datalist contain records that were extracted from the database.
 * @param string $target where to go after clicking a surname : INDI page or FAM page
 * @param string $listFormat presentation style: "style2 = sortable list, "style3" = cloud
 */
function print_surn_table($datalist, $target="INDI", $listFormat="") {
  global $pgv_lang, $factarray, $GEDCOM, $TEXT_DIRECTION, $COMMON_NAMES_THRESHOLD;
  global $SURNAME_LIST_STYLE;
  if (count($datalist)<1) return;

  if (empty($listFormat)) $listFormat = $SURNAME_LIST_STYLE;

  if ($listFormat=="style3") {
	// Requested style is "cloud", where the surnames are a list of names (with links), 
	// and the font size used for each name depends on the number of occurrences of this name
	// in the database - generally known as a 'tag cloud'.
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
	//-- table header
	echo "<table id=\"".$table_id."\" class=\"tag_cloud_table\">";
	//-- table body
	echo "<tr>";
	echo "<td class=\"tag_cloud\">";
	//-- Calculate range for font sizing
	$max_tag = 0;
	$font_tag = 0;
	foreach($datalist as $key => $value) {
		if (!isset($value["name"])) break;
		if ($value["match"]>$max_tag)
			$max_tag = $value["match"];
	}
	$font_tag = $max_tag / 6;
	//-- Print each name
	foreach($datalist as $key => $value) {
		if (!isset($value["name"])) break;
		$surn = $value["name"];
		if ($target=="FAM") $url = "famlist.php";	else $url = "indilist.php";
		$url .= "?ged=".$GEDCOM."&amp;surname=".urlencode($surn);
		if (empty($surn) || trim("@".$surn,"_")=="@" || $surn=="@N.N.") $surn = $pgv_lang["NN"];
		$fontsize = ceil($value["match"]/$font_tag);
		if ($TEXT_DIRECTION=="ltr") {
			$title = PrintReady($surn." (".$value["match"].")");
			$tag = PrintReady("<font size=\"".$fontsize."\">".$surn."</font><span class=\"tag_cloud_sub\">&nbsp;(".$value["match"].")</span>");
		} else {
			$title = PrintReady("(".$value["match"].") ".$surn);
			$tag = PrintReady("<span class=\"tag_cloud_sub\">(".$value["match"].")&nbsp;</span><font size=\"".$fontsize."\">".$surn."</font>");
		}

		echo "<a href=\"".$url."\" class=\"list_item\" title=\"".$title."\">".$tag."</a>&nbsp;&nbsp; ";
	}
	echo "</td>";
	echo "</tr>\n";
	//-- table footer
	echo "</table>\n";
	return;
  }

    // Requested style isn't "cloud".  In this case, we'll produce a sortable list.
	require_once("js/sorttable.js.htm");
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	echo "<td></td>";
	echo "<th class=\"list_label\">".$factarray["SURN"]."</th>";
	echo "<th class=\"list_label\">";
//	if ($target=="FAM") echo $pgv_lang["families"]; else echo $pgv_lang["individuals"];
	if ($target=="FAM") echo $pgv_lang["spouses"]; else echo $pgv_lang["individuals"];
	echo "</th>";
	echo "</tr>\n";
	//-- table body
	$total = 0;
	$n = 0;
	foreach($datalist as $key => $value) {
		if (!isset($value["name"])) break;
		$surn = $value["name"];
		if ($target=="FAM") $url = "famlist.php";	else $url = "indilist.php";
		$url .= "?ged=".$GEDCOM."&amp;surname=".urlencode($surn);
		//-- Counter
		echo "<tr>";
		echo "<td class=\"list_value_wrap rela list_item\">".++$n."</td>";
		//-- Surname
		if (empty($surn) or trim("@".$surn,"_")=="@" or $surn=="@N.N.") $surn = $pgv_lang["NN"];
		echo "<td class=\"list_value_wrap\" align=\"".get_align($surn)."\">";
		echo "<a href=\"".$url."\" class=\"list_item name1\">".PrintReady($surn)."</a>";
		echo "&nbsp;</td>";
		//-- Surname count
		echo "<td class=\"list_value_wrap\">";
		echo "<a href=\"{$url}\" class=\"list_item name2\" name=\"{$value['match']}\">{$value["match"]}</a>";
		echo "</td>";
		$total += $value["match"];

		echo "</tr>\n";
	}
	//-- table footer
	echo "<tr class=\"sortbottom\">";
	echo "<td class=\"list_item\">&nbsp;</td>";
	echo "<td class=\"list_item\">&nbsp;</td>";
	echo "<td class=\"list_label name2\">".$total."</td>";
	echo "</tr>\n";
	echo "</table>\n";
}

/**
 * print a sortable table of recent changes
 * also called by mediaviewer to list records linked to a media
 *
 * @param array $datalist contain records that were extracted from the database.
 */
function print_changes_table($datalist) {
	global $pgv_lang, $factarray, $LANGUAGE, $SHOW_ID_NUMBERS, $SHOW_LAST_CHANGE, $SHOW_MARRIED_NAMES, $TEXT_DIRECTION;
	if (count($datalist)<1) return;
	require_once("js/sorttable.js.htm");
	require_once("includes/gedcomrecord.php");
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<thead><tr>";
	//echo "<td></td>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela sorttable_numeric\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["record"]."</th>";
	echo "<th style=\"display:none\">GIVN</th>";
	echo "<th class=\"list_label\">".$factarray["CHAN"]."</th>";
	echo "<th class=\"list_label\">".$factarray["_PGVU"]."</th>";
	echo "</tr></thead>\n";
	//-- table body
	echo "<tbody>\n";
	$hidden = 0;
	$n = 0;
	$NMAX = 1000;
	foreach($datalist as $key => $value) {
		if ($n>=$NMAX) break;
		$record = null;
		if (!is_array($value)) $record = GedcomRecord::getInstance($key);
		else {
			if (isset($value['d_gid'])) $record = GedcomRecord::getInstance($value['d_gid']);
			if (is_null($record) && isset($value[0])) $record = GedcomRecord::getInstance($value[0]);
		}
		if (is_null($record)) continue;
		// Privacy
		if (!$record->canDisplayDetails()) {
			$hidden++;
			continue;
		}
		//-- Counter
		echo "<tr>";
		//echo "<td class=\"list_value_wrap rela list_item\">".++$n."</td>";
		++$n;
		//-- Record ID
		if ($SHOW_ID_NUMBERS)
			echo '<td class="list_value_wrap rela">'.$record->getXrefLink().'</td>';
		//-- Record name(s)
		if ($record->type=="FAM") $name = $record->getSortableName(true);
		else $name = $record->getSortableName();
		echo "<td class=\"list_value_wrap\" align=\"".get_align($name)."\">";
		echo "<a href=\"".$record->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		if ($record->type=="INDI") {
			echo $record->getSexImage();
			$name_subtags = array("", "_AKA", "_HEB", "ROMN");
			if ($SHOW_MARRIED_NAMES) $name_subtags[] = "_MARNM";
 			foreach ($name_subtags as $k=>$subtag) {
 				for ($num=1; $num<9; $num++) {
 					$addname = $record->getSortableName($subtag, $num);
 					if (!empty($addname) && $addname!=$name) echo "<br /><a title=\"".$subtag."\" href=\"".$record->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a>";
 					if (empty($addname)) break;
 				}
 			}
		}
		if ($record->type=="SOUR" || $record->type=="REPO") {
			$name_subtags = array("_HEB", "ROMN");
			foreach ($name_subtags as $k=>$subtag) {
				$addname = $record->getSortableName($subtag);
				if (!empty($addname) && $addname!=$name) echo "<br /><a title=\"".$subtag."\" href=\"".$record->getLinkUrl()."\" class=\"list_item\">".PrintReady($addname)."</a>";
			}
		}
		echo "</td>";
		//-- GIVN
		echo "<td style=\"display:none\">";
		$exp = explode(",", str_replace('<', ',', $name).",");
		echo $exp[1];
		echo "</td>";
		//-- Last change date/time
		print "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap rela\">".$record->LastChangeTimestamp(empty($SEARCH_SPIDER))."</td>";
		//-- Last change user
		print "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap rela\">".$record->LastChangeUser(empty($SEARCH_SPIDER))."</td>";
		echo "</tr>\n";
	}
	echo "</tbody>\n";
	//-- table footer
	echo "<tfoot><tr>";
	//echo "<td></td>";
	if ($SHOW_ID_NUMBERS) echo "<td></td>";
	echo "<td class=\"list_label\">";
	echo '<a href="javascript:;" onclick="sortByNextCol(this)"><img src="images/topdown.gif" alt="" border="0" /> '.$factarray["GIVN"].'</a><br />';
	echo $pgv_lang["total_names"].": ".$n;
	if ($hidden) echo "<br /><span class=\"warning\">".$pgv_lang["hidden"]." : ".$hidden."</span>";
	if ($n>=$NMAX) echo "<br /><span class=\"warning\">".$pgv_lang["recent_changes"]." &gt; ".$NMAX."</span>";
	echo "</td>";
	echo "<td style=\"display:none\">GIVN</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr></tfoot>";
	echo "</table>\n";
}

/**
 * print a sortable table of events
 * and generates hCalendar records
 * @see http://microformats.org/
 *
 * @param array $datalist contain records that were extracted from the database.
 * @param int $nextdays optional days count
 * @param string $option optional filtering option
 */
function print_events_table($startjd, $endjd, $events='BIRT MARR DEAT', $only_living=false, $allow_download=false) {
	global $pgv_lang, $factarray, $SHOW_ID_NUMBERS, $SHOW_MARRIED_NAMES, $TEXT_DIRECTION, $SERVER_URL;
	require_once("js/sorttable.js.htm");
	require_once("includes/gedcomrecord.php");
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
	//-- table header
	print "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	print "<thead><tr>";
	//echo "<td></td>";
	//if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label rela sorttable_numeric\">".$pgv_lang["id"]."</th>";
	print "<th class=\"list_label\">".$pgv_lang["record"]."</th>";
	print "<th style=\"display:none\">GIVN</th>";
	print "<th class=\"list_label\">".$factarray["DATE"]."</th>";
	print "<td class=\"list_label\"><img src=\"./images/reminder.gif\" alt=\"".$pgv_lang["anniversary"]."\" title=\"".$pgv_lang["anniversary"]."\" border=\"0\" /></th>";
	print "<th class=\"list_label\">".$factarray["EVEN"]."</th>";
	print "</tr></thead>\n";
	//-- table body
	print "<tbody>\n";
	$hidden = 0;
	$n = 0;

	// Which types of name do we display for an INDI
 	$name_subtags = array("", "_AKA", "_HEB", "ROMN");
	if ($SHOW_MARRIED_NAMES)
		$name_subtags[] = "_MARNM";

	foreach(get_event_list() as $key => $value) {
		if ($value['jd']<$startjd || $value['jd']>$endjd)
			continue;
		//-- only birt/marr/deat ?
		if (!empty($events) && strpos($events, $value['fact'])===false)
			continue;

		//-- get gedcom record - it may have been deleted since we cached the event
		$record = GedcomRecord::getInstance($value['id']);
		if (is_null($record))
			continue;
		//-- only living people ?
		if ($only_living) {
			if ($record->type=="INDI" && $record->isDead())
				continue;
			if ($record->type=="FAM") {
				$husb = $record->getHusband();
				if (is_null($husb) || $husb->isDead())
					continue;
				$wife = $record->getWife();
				if (is_null($wife) || $wife->isDead())
					continue;
			}
		}

		// Privacy
		if (!$record->canDisplayDetails() || !showFactDetails($value['fact'], $value['id']) || FactViewRestricted($value['id'], $value['factrec'])) {
			$hidden++;
			continue;
		}
		//-- Counter
		$n++;
		print "<tr class=\"vevent\">"; // hCalendar:vevent
		//-- Record name(s)
		if ($record->type=="FAM")
			$name=$record->getSortableName(true);
		else
			$name=$record->getSortableName();
		$url=$record->getLinkUrl();

		print "<td class=\"list_value_wrap\" align=\"".get_align($name)."\">";
		print "<a href=\"".$record->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		if ($record->type=="INDI") {
			print $record->getSexImage();
			foreach ($name_subtags as $subtag) {
				for ($num=1; ; ++$num) {
					$addname = $record->getSortableName($subtag, $num);
					if (empty($addname))
						break;
					else
						if ($addname!=$name)
							print "<br /><a title=\"".$subtag."\" href=\"".$url."\" class=\"list_item\">".PrintReady($addname)."</a>";
				}
			}
		}
		print "</td>";
		//-- GIVN
		echo "<td style=\"display:none\">";
		$exp = explode(",", str_replace('<', ',', $name).",");
		echo $exp[1];
		echo "</td>";
		//-- Event date
		print "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap\">";
		print str_replace('<a', '<a name="'.$value['date']->MinJD().'"', $value['date']->Display(empty($SEARCH_SPIDER)));
		print "</td>";
		//-- Anniversary
		print "<td class=\"list_value_wrap rela\">";
		$anniv = $value['anniv'];
		if ($anniv==0)
			print '&nbsp;';
		else
			print $anniv;
		if ($allow_download) {
			// hCalendar:dtstart and hCalendar:summary
			print "<abbr class=\"dtstart\" title=\"".strip_tags($value['date']->Display(false,'Ymd',array()))."\"></abbr>";
			print "<abbr class=\"summary\" title=\"".$pgv_lang["anniversary"]." #$anniv ".$factarray[$value['fact']]." : ".PrintReady(strip_tags($record->getSortableName()))."\"></abbr>";
		}
		print "</td>";
		//-- Event name
		print "<td class=\"list_value_wrap\">";
		print "<a href=\"".$url."\" class=\"list_item url\">".$factarray[$value['fact']]."</a>"; // hCalendar:url
		print "&nbsp;</td>";

		print "</tr>\n";
	}
	print "</tbody>\n";
	//-- table footer
	print "<tfoot><tr>";
	//echo "<td></td>";
	//if ($SHOW_ID_NUMBERS) echo "<td></td>";
	print "<td class=\"list_label\">";
	echo '<a href="javascript:;" onclick="sortByNextCol(this)"><img src="images/topdown.gif" alt="" border="0" /> '.$factarray["GIVN"].'</a><br />';
	print $pgv_lang["stat_events"].": ".$n;
	if ($hidden) echo "<br /><span class=\"warning\">".$pgv_lang["hidden"]." : ".$hidden."</span>";
	print "</td>";
	print "<td style=\"display:none\">GIVN</td>";
	print "<td>";
	if ($allow_download) {
		$uri = $SERVER_URL.basename($_SERVER["REQUEST_URI"]);
		global $whichFile;
		$whichFile = "hCal-events.ics";
		$title = print_text("download_file",0,1);
		if ($n) print "<a href=\"http://feeds.technorati.com/events/".$uri."\"><img src=\"images/hcal.png\" border=\"0\" alt=\"".$title."\" title=\"".$title."\" /></a>";
	}
	print "</td>";
	print "<td></td>";
	print "<td></td>";
	print "</tr></tfoot>";
	print "</table>\n";
}

/**
 * print a list of events
 *
 * This performs the same function as print_events_table(), but formats the output differently.
 */
function print_events_list($startjd, $endjd, $events='BIRT MARR DEAT', $only_living=false) {
	global $pgv_lang, $factarray, $SHOW_ID_NUMBERS, $SHOW_MARRIED_NAMES, $TEXT_DIRECTION;

	// Did we have any output?  Did we skip anything?
	$output=false;
	$filter=false;
	$private=false;

	$return='';

	foreach(get_event_list() as $key => $value) {
		if ($value['jd']<$startjd || $value['jd']>$endjd)
			continue;
		//-- only birt/marr/deat ?
		if (!empty($events) && strpos($events, $value['fact'])===false)
			continue;

		//-- get gedcom record - it may have been deleted since we cached the event
		$record = GedcomRecord::getInstance($value['id']);
		if (is_null($record))
			continue;
		//-- only living people ?
		if ($only_living) {
			if ($record->type=="INDI" && $record->isDead()) {
				$filter=true;
				continue;
			}
			if ($record->type=="FAM") {
				$husb = $record->getHusband();
				if (is_null($husb) || $husb->isDead()) {
					$filter=true;
					continue;
				}
				$wife = $record->getWife();
				if (is_null($wife) || $wife->isDead()) {
					$filter=true;
					continue;
				}
			}
		}

		// Privacy
		if (!$record->canDisplayDetails() || !showFactDetails($value['fact'], $value['id']) || FactViewRestricted($value['id'], $value['factrec'])) {
			$private=true;
			continue;
		}
		$output=true;

		$name=$record->getSortableName();
		$url=$record->getLinkUrl();

		$return.="<a href=\"".$record->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		if ($record->type=="INDI")
			$return.=$record->getSexImage();
		$return.="<div class=\"indent\">";
		$return.=$factarray[$value['fact']].' - '.$value['date']->Display(true);
		if ($value['anniv']!=0)
			$return.=" (" . str_replace("#year_var#", $value['anniv'], $pgv_lang["year_anniversary"]).")";
		if (!empty($value['plac']))
			$return.=" - <a href=\"".GedcomRecord::getPlaceUrl($value['plac'])."\">".$value['plac']."</a>";
		$return.="</div>";
	}

	// Print a final summary message about restricted/filtered facts
	$pgv_lang["global_num1"]=$endjd-$startjd+1; // TODO: This doesn't work as expected??
	$return.="<b>";
	if ($private)
		if ($output)
			if ($endjd==client_jd())
				$return.=print_text ("more_today_privacy", 0, 1);
			else
				if ($startjd==$endjd)
					$return.=print_text ("more_events_privacy1", 0, 1);
				else
					$return.=print_text ("more_events_privacy", 0, 1);
		else
			if ($endjd==client_jd())
				$return.=print_text ("none_today_privacy", 0, 1);
			else
				if ($startjd==$endjd)
					$return.=print_text ("no_events_privacy1", 0, 1);
				else
					$return.=print_text ("no_events_privacy", 0, 1);
	else
		if (!$output)
			if ($filter)
				if ($endjd==client_jd())
					$return.=print_text ("none_today_living", 0, 1);
				else
					if ($startjd==$endjd)
						$return.=print_text ("no_events_living1", 0, 1);
					else
						$return.=print_text ("no_events_living", 0, 1);
			else
				if ($endjd==client_jd())
					$return.=print_text ("none_today_all", 0, 1);
				else
					if ($startjd==$endjd)
						$return.=print_text ("no_events_all1", 0, 1);
					else
						$return.=print_text ("no_events_all", 0, 1);

	$return.="</b>";
	return $return;
}

/**
 * check string align direction depending on language and rtl config
 *
 * @param string $txt string argument
 * @return string left|right
 */
function get_align($txt) {
		global $TEXT_DIRECTION, $USE_RTL_FUNCTIONS;

		if (!empty($txt)) {
  			if ($TEXT_DIRECTION=="rtl" && !hasRTLText($txt) && hasLTRText($txt)) return "left";
  			if ($TEXT_DIRECTION=="ltr" && hasRTLText($txt) && !hasLTRText($txt) && $USE_RTL_FUNCTIONS) return "right";
		}
		if ($TEXT_DIRECTION=="rtl") return "right";
		return "left";
}

/**
 * load behaviour js data
 * to be called at the end just before </body> tag
 *
 * @see http://bennolan.com/behaviour/
 * @param none
 */
function load_behaviour() {
	global $pgv_lang;
	require_once("js/prototype.js.htm");
	require_once("js/behaviour.js.htm");
	require_once("js/overlib.js.htm");
?>
	<script type="text/javascript">
	// <![CDATA[
	var myrules = {
		'fieldset button' : function(element) {
			element.onmouseover = function() { // show helptext
				helptext = this.title;
				if (helptext=='') helptext = this.value;
				if (helptext=='' || helptext==undefined) helptext = 'Help text : button_'+this.className;
				this.title = helptext; if (document.all) return; // IE = title
				this.value = helptext; this.title = ''; // Firefox = value
				return overlib(helptext, BGCOLOR, "#000000", FGCOLOR, "#FFFFE0");
			}
			element.onmouseout = nd; // hide helptext
			element.onmousedown = function() { // show active button
				var buttons = this.parentNode.getElementsByTagName("button");
				for (var i=0; i<buttons.length; i++) buttons[i].style.opacity = 1;
				this.style.opacity = 0.67;
			}
			element.onclick = function() { // apply filter
				var temp = this.parentNode.getElementsByTagName("table")[0];
				if (!temp) return true;
				var table = temp.id;
				var args = this.className.split('_'); // eg: BIRT_YES
				if (args[0]=="alive") return table_filter_alive(table);
				if (args[0]=="reset") return table_filter(table, "", "");
				if (args[1].length) return table_filter(table, args[0], args[1]);
				return false;
			}
		}/**,
		'.sortable th' : function(element) {
			element.onmouseout = nd; // hide helptext
			element.onmouseover = function() { // show helptext
				helptext = this.title;
				if (helptext=='') helptext = this.value;
				if (helptext=='' || helptext==undefined) helptext = <?php echo "'".$pgv_lang["sort_column"]."'"?>;
				this.title = helptext; if (document.all) return; // IE = title
				this.value = helptext; this.title = ''; // Firefox = value
				return overlib(helptext, BGCOLOR, "#000000", FGCOLOR, "#FFFFE0");
			}
		}**/
	}
	Behaviour.register(myrules);
	// ]]>
	</script>
<?php
}
?>
