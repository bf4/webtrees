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

require_once($factsfile["english"]);
if (file_exists($factsfile[$LANGUAGE])) require_once($factsfile[$LANGUAGE]);

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
	global $pgv_lang, $pass, $fam_private, $fam_hide, $fam_total, $SHOW_ID_NUMBERS, $SHOW_FAM_ID_NUMBERS;
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
		if ($SHOW_FAM_ID_NUMBERS) {
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
function print_indi_table($datalist) {
	global $pgv_lang, $factarray, $factsfile, $LANGUAGE, $SHOW_ID_NUMBERS, $TEXT_DIRECTION;
	global $tindilist;
?>
	<script type="text/javascript" src="strings.js"></script>
	<script type="text/javascript" src="sorttable.js"></script>
<?php
	require_once("includes/person_class.php");
	echo "<h3>".$pgv_lang["individuals"]."</h3>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID

	//-- filter buttons
	$person = new Person("");
	echo "<button type=\"button\" onclick=\"return table_filter('".$table_id."', 'SEX', 'M')\">";
	$person->sex = "M"; echo $person->getSexImage()."&nbsp;</button> ";
	echo "<button type=\"button\" onclick=\"return table_filter('".$table_id."', 'SEX', 'F')\">";
	$person->sex = "F"; echo $person->getSexImage()."&nbsp;</button> ";
	echo "<button type=\"button\" onclick=\"return table_filter('".$table_id."', 'SEX', 'U')\">";
	$person->sex = "U"; echo $person->getSexImage()."&nbsp;</button> ";
	echo "<button type=\"button\" onclick=\"return table_filter('".$table_id."', 'DEAD', '0')\">";
	echo $pgv_lang["alive"]."</button> ";
	echo "<button type=\"button\" onclick=\"return table_filter('".$table_id."', 'DEAD', '1')\">";
	echo $pgv_lang["dead"]."</button> ";
	echo "<button type=\"button\" onclick=\"return table_filter('".$table_id."', '', '')\">";
	echo $pgv_lang["reset"]."</button> ";

	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$factarray["NAME"]."</th>";
	echo "<th class=\"list_label\" style=\"display:none\">SEX</th>";
	echo "<th class=\"list_label\">".$factarray["BIRT"]."</th>";
	echo "<th class=\"list_label\">".$factarray["PLAC"]."</th>";
	echo "<th class=\"list_label\" style=\"display:none\">DEAD</th>";
	echo "<th class=\"list_label\">".$factarray["DEAT"]."</th>";
	echo "<th class=\"list_label\">".$factarray["PLAC"]."</th>";
	echo "</tr>\n";

	//-- table body
	foreach($datalist as $key => $value) {
		if (isset($value["gid"])) $gid = $value["gid"]; // from indilist
		if (isset($value[4])) $gid = $value[4]; // from indilist ALL

		if (isset($value["gedcom"])) $person = new Person($value["gedcom"]); // from source.php
		else if (isset($tindilist[$gid])) $person = new Person($tindilist[$gid]["gedcom"]);
		else $person = Person::getInstance($gid);
		//if (!$person->canDisplayName()) continue;

		echo "<tr><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		if ($SHOW_ID_NUMBERS) {
			echo "<a href=\"".$person->getLinkUrl()."\" class=\"list_item\">".$person->xref."</a>";
			echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		}
		echo "<a href=\"".$person->getLinkUrl()."\" class=\"list_item\"><b>".$person->getSortableName()."</b></a>";
		echo $person->getSexImage();
		$addname = $person->getSortableAddName();
		if (!empty($addname)) echo "<br /><a href=\"".$person->getLinkUrl()."\" class=\"list_item\"><b>".$addname."</b></a>";

		echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\" style=\"display:none\">";
		echo $person->getSex();

		echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		echo "<span style=\"display:none\">".$person->getSortableBirthDate()."</span>"; // store hidden sortable datetime
		echo "<a href=\"".$person->getDateUrl($person->bdate)."\" class=\"list_item\">".get_changed_date($person->getBirthDate())."</a>";

		echo "&nbsp;</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		echo "<a href=\"".$person->getPlaceUrl($person->getBirthPlace())."\" class=\"list_item\">".$person->getPlaceShort($person->getBirthPlace())."</a>";

		echo "&nbsp;</td><td class=\"$TEXT_DIRECTION list_value_wrap\" style=\"display:none\">";
		if ($person->isDead()) echo "1"; else echo "0";

		echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		echo "<span style=\"display:none\">".$person->getSortableDeathDate()."</span>"; // store hidden sortable datetime
		if ($person->isDead()) echo "<a href=\"".$person->getDateUrl($person->ddate)."\" class=\"list_item\">".get_changed_date($person->getDeathDate())."</a>";

		echo "&nbsp;</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		echo "<a href=\"".$person->getPlaceUrl($person->getDeathPlace())."\" class=\"list_item\">".$person->getPlaceShort($person->getDeathPlace())."</a>";

		echo "&nbsp;</td></tr>\n";
	}
	echo "</table>\n";
}

/**
 * print a sortable table of families
 *
 * @param array $datalist contain families that were extracted from the database.
 */
function print_fam_table($datalist) {
	global $pgv_lang, $factarray, $factsfile, $LANGUAGE, $SHOW_ID_NUMBERS, $TEXT_DIRECTION;
?>
	<script type="text/javascript" src="strings.js"></script>
	<script type="text/javascript" src="sorttable.js"></script>
<?php
	require_once("includes/family_class.php");
	echo "<h3>".$pgv_lang["families"]."</h3>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID

	//-- filter buttons
	echo "<button type=\"button\" onclick=\"return table_filter('".$table_id."', 'DEAD', '0')\">";
	echo $pgv_lang["alive"]."</button> ";
	echo "<button type=\"button\" onclick=\"return table_filter('".$table_id."', 'DEAD', '1')\">";
	echo $pgv_lang["dead"]."</button> ";
	echo "<button type=\"button\" onclick=\"return table_filter('".$table_id."', '', '')\">";
	echo $pgv_lang["reset"]."</button> ";

	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$factarray["NAME"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["spouse"]."</th>";
	echo "<th class=\"list_label\">".$factarray["MARR"]."</th>";
	echo "<th class=\"list_label\">".$factarray["PLAC"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["children"]."</th>";
	echo "<th class=\"list_label\" style=\"display:none\">DEAD</th>";
	echo "</tr>\n";

	//-- table body
	foreach($datalist as $key => $value) {
		if (isset($value["gid"])) $gid = $value["gid"];

		if (isset($value["gedcom"])) $family = new Family($value["gedcom"]);
		else if (isset($tfamlist[$gid])) $family = new Family($tfamlist[$gid]["gedcom"]);
		else $family = Family::getInstance($gid);

		$husb = $family->getHusband();
		if (is_null($husb)) $husb = new Person('');
		$wife = $family->getWife();
		if (is_null($wife)) $wife = new Person('');
		//if (!$husb->canDisplayName() and !$wife->canDisplayName()) continue;

		echo "<tr><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		if ($SHOW_ID_NUMBERS) {
			echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item\">".$family->xref."</a>";
			echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		}
		echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item\"><b>".$husb->getSortableName()."</b></a>";
		echo $husb->getSexImage();
		$addname = $husb->getSortableAddName();
		if (!empty($addname)) echo "<br /><a href=\"".$family->getLinkUrl()."\" class=\"list_item\"><b>".$addname."</b></a>";

		echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		echo "<a href=\"".$family->getLinkUrl()."\" class=\"list_item\"><b>".$wife->getSortableName()."</b></a>";
		echo $wife->getSexImage();
		$addname = $wife->getSortableAddName();
		if (!empty($addname)) echo "<br /><a href=\"".$family->getLinkUrl()."\" class=\"list_item\"><b>".$addname."</b></a>";

		echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		echo "<span style=\"display:none\">".$family->getSortableMarriageDate()."</span>"; // store hidden sortable datetime
		echo "<a href=\"".$family->getDateUrl($family->marr_date)."\" class=\"list_item\">".get_changed_date($family->getMarriageDate())."</a>";

		echo "&nbsp;</td><td class=\"$TEXT_DIRECTION  list_value_wrap\">";
		echo "<a href=\"".$family->getPlaceUrl($family->getMarriagePlace())."\" class=\"list_item\">".$family->getPlaceShort($family->getMarriagePlace())."</a>";

		echo "&nbsp;</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		echo "<center><a href=\"".$family->getLinkUrl()."\" class=\"list_item\">".$family->getNumberOfChildren()."</a></center>";

		echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\" style=\"display:none\">";
		if ($husb->isDead() and $wife->isDead()) echo "1"; else echo "0";

		echo "</td></tr>\n";
	}
	echo "</table>\n";
}

/**
 * print a sortable table of sources
 *
 * @param array $datalist contain sources that were extracted from the database.
 */
function print_sour_table($datalist) {
	global $pgv_lang, $factarray, $factsfile, $LANGUAGE, $SHOW_ID_NUMBERS, $TEXT_DIRECTION;
?>
	<script type="text/javascript" src="strings.js"></script>
	<script type="text/javascript" src="sorttable.js"></script>
<?php
	require_once("includes/source_class.php");
	echo "<h3>".$pgv_lang["sources"]."</h3>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID

	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$factarray["TITL"]."</th>";
	echo "<th class=\"list_label\">".$factarray["AUTH"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["individuals"]."</th>";
	echo "<th class=\"list_label\">".$pgv_lang["families"]."</th>";
	echo "</tr>\n";

	//-- table body
	foreach ($datalist as $key => $value) {
		$source = new Source($value["gedcom"]);
		echo "<tr><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		if ($SHOW_ID_NUMBERS) {
			echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$source->xref."</a>";
			echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		}
		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\"><b>".$value["name"]."</b></a>";

		echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		echo "<a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".$source->getAuth()."</a>";

		echo "&nbsp;</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		echo "<center><a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".count($source->getSourceIndis())."</a></center>";

		echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		echo "<center><a href=\"".$source->getLinkUrl()."\" class=\"list_item\">".count($source->getSourceFams())."</a></center>";

		echo "</td></tr>\n";
	}
	echo "</table>\n";
}

/**
 * print a sortable table of repositories
 *
 * @param array $datalist contain repositories that were extracted from the database.
 */
function print_repo_table($datalist) {
	global $pgv_lang, $factarray, $factsfile, $LANGUAGE, $SHOW_ID_NUMBERS, $TEXT_DIRECTION;
?>
	<script type="text/javascript" src="strings.js"></script>
	<script type="text/javascript" src="sorttable.js"></script>
<?php
	//TODO require_once("includes/repository_class.php");
	echo "<h3>".$pgv_lang["repos_found"]."</h3>";
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID

	//-- table header
	echo "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	echo "<tr>";
	if ($SHOW_ID_NUMBERS) echo "<th class=\"list_label\">".$pgv_lang["id"]."</th>";
	echo "<th class=\"list_label\">".$factarray["NAME"]."</th>";
	echo "</tr>\n";
	foreach ($datalist as $key => $value) {
		//TODO $repo = new Repository($value["gedcom"]);
		$url = "repo.php?rid=".$key."&amp;ged=".get_gedcom_from_id($value["gedfile"]);
		echo "<tr><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		if ($SHOW_ID_NUMBERS) {
			echo "<a href=\"".$url."\" class=\"list_item\">".$key."</a>";
			echo "</td><td class=\"$TEXT_DIRECTION list_value_wrap\">";
		}
		echo "<a href=\"".$url."\" class=\"list_item\"><b>".$value["name"]."</b></a>";
		echo "</td></tr>\n";
	}
	echo "</table>\n";
}
?>
