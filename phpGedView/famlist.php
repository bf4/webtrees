<?php
/**
 * Family List
 *
 * The Family list shows all families from a chosen gedcom file. The list is
 * setup in two sections. The alphabet bar and the details.
 *
 * The alphabet bar shows all the available letters users can click. The bar is built
 * up from the lastnames' first letter. Added to this bar is the symbol @, which is
 * shown as a translated version of the variable <var>pgv_lang["NN"]</var>, and a
 * translated version of the word ALL by means of variable <var>$pgv_lang["all"]</var>.
 *
 * The details can be shown in two ways, with surnames or without surnames. By default
 * the user first sees a list of surnames of the chosen letter and by clicking on a
 * surname a list with names of people with that chosen surname is displayed.
 *
 * Beneath the details list is the option to skip the surname list or show it.
 * Depending on the current status of the list.
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
 * This Page Is Valid XHTML 1.0 Transitional! > 24 August 2005
 *
 * @version $Id$
 * @package PhpGedView
 * @subpackage Lists
 */

require("config.php");
require_once("includes/functions_print_lists.php");

// We show three different lists:
$alpha   =safe_GET('alpha'); // All surnames beginning with this letter where "@"=unknown and ","=none
$surname =safe_GET('surname'); // All indis with this surname
$show_all=safe_GET('show_all', array('no','yes'), 'no'); // All indis

// Long lists can be broken down by given name
$falpha=safe_GET('falpha'); // All first names beginning with this letter
$show_all_firstnames=safe_GET('show_all_firstnames', array('no','yes'), 'no');

// We can show either a list of surnames or a list of names
$surname_sublist=safe_GET('surname_sublist', array('no','yes'));
if (!$surname_sublist) {
	$surname_sublist=safe_COOKIE('surname_sublist', array('no','yes'), 'yes');
}
setcookie('surname_sublist', $surname_sublist);

print_header($pgv_lang["family_list"]);
print "<div class =\"center\">";
print "\n\t<h2>".$pgv_lang["family_list"]."</h2>";

if (empty($surname_sublist)) $surname_sublist = "yes";
if (empty($show_all)) $show_all = "no";

// Remove slashes
if (isset($alpha)) {
	$alpha = stripLRMRLM(stripslashes($alpha));
	$doctitle = $pgv_lang["family_list"]." : ".$alpha;
}
if (isset($surname)) {
	$surname = stripLRMRLM(stripslashes($surname));
	$doctitle = $pgv_lang["family_list"]." : ";
	if (empty($surname) or trim("@".$surname,"_")=="@" or $surname=="@N.N.") $doctitle .= $pgv_lang["NN"];
	else $doctitle .= $surname;
}
if (isset($alpha) || isset($surname) || $show_all=='yes') $showList = true;
else $showList = false;		// Don't show the list until we have some filter criteria
if (isset($doctitle)) {
	?>
	<script language="JavaScript" type="text/javascript">
	<!--
		document.title = '<?php print $doctitle; ?>';
	//-->
	</script>
	<?php
}
if (empty($show_all_firstnames)) $show_all_firstnames = "no";
if (empty($DEBUG)) $DEBUG = false;

/**
 * Total famlist array
 *
 * The tfamlist array will contain families that are extracted from the database.
 * @global array $tfamlist
 */
$tfamlist = array();

/**
 * Family alpha array
 *
 * The famalpha array will contain all first letters that are extracted from families last names
 * @global array $famalpha
 */

$famalpha = get_fam_alpha();

uasort($famalpha, "stringsort");

if (empty($surname_sublist))
        $surname_sublist = "yes";

/**
 * In the first half of 2007, Google is only indexing the first 1,000 urls
 * on a page.  We now produce 4 urls per line, instead of 12.  So, we divide
 * 1000 by 5 for some breathing room, and adjust to do surname pages if the
 * alphalist page would exceed that number minus the header urls and alphas.
 * 200 - letters - unknown and all - menu urls
 * If you have over 200 families in the same surname, some will still not
 * get indexed through here, and will have to be caught by the close relatives
 * on the individual.php, family.php, or the indilist.php page.
 */
if (!(empty($SEARCH_SPIDER))) {
	$googleSplit = 200 - 26 - 2 - 4;
	if (isset($alpha))
       		$show_count = count(get_alpha_fams($alpha));
	else if (isset($surname))
        	$show_count = count(get_surname_fams($surname));
	else
        	$show_count = count(get_fam_list());

        if (($show_count > $googleSplit ) && (empty($surname)))  /* Generate extra surname pages if needed */
                $surname_sublist = "yes";
        else
                $surname_sublist = "no";
}

if (isset($alpha) && !isset($famalpha["$alpha"])) $alpha="";

if (count($famalpha) > 0) {
	if (empty($SEARCH_SPIDER)) {
		print_help_link("alpha_help", "qm", "alpha_index");
		print $pgv_lang["first_letter_name"]."<br />\n";
	}
	$delayLetter = false;		// indicates that "@" was found in list
	foreach ($famalpha as $letter=>$list) {
		if (empty($alpha)) {
			if (!empty($surname)) {
				if ($USE_RTL_FUNCTIONS && hasRTLText($surname)) $alpha = substr(preg_replace(array("/ [jJsS][rR]\.?,/", "/ I+,/", "/^[a-z. ]*/"), array(",",",",""), $surname),0,2);
				else $alpha = substr(preg_replace(array("/ [jJsS][rR]\.?,/", "/ I+,/", "/^[a-z. ]*/"), array(",",",",""), $surname),0,1);
			}
		}
		if (!isset($alpha)) {
			$alpha = $letter;
		}
		if ($letter == "@") $delayLetter = true;
		else {
			print "<a href=\"".encode_url("famlist.php?alpha={$letter}&surname_sublist={$surname_sublist}&ged={$GEDCOM}")."\">";
			if ($showList && $alpha==$letter && $show_all=="no") print "<span class=\"warning\">".$letter."</span>";
			else print $letter;
			print "</a> | \n";
		}
	}
	if ($delayLetter) {
		$letter = '@';
		print "<a href=\"".encode_url("famlist.php?alpha={$letter}&surname_sublist=yes&surname=@N.N.&ged={$GEDCOM}")."\">";
		if ($showList && $alpha==$letter && $show_all=="no") print "<span class=\"warning\">".PrintReady($pgv_lang["NN"])."</span>";
		else print PrintReady($pgv_lang["NN"]);
		print "</a> | \n";
	}
	print "<a href=\"".encode_url("famlist.php?show_all=yes&surname_sublist={$surname_sublist}&ged={$GEDCOM}")."\">";
	if ($show_all=="yes") print "<span class=\"warning\">".$pgv_lang["all"]."</span></a>\n";
	else print $pgv_lang["all"]."</a>\n";
}

print "<br /><br />";

if(empty($SEARCH_SPIDER)) {
	if (isset($alpha) && $alpha != "@") {
		if ($surname_sublist=="yes") {
			print_help_link("skip_sublist_help", "qm", "skip_surnames");
			print "<a href=\"".encode_url("famlist.php?alpha={$letter}&surname_sublist=no&show_all={$show_all}&ged={$GEDCOM}")."\">".$pgv_lang["skip_surnames"]."</a>";
		} else {
			print_help_link("skip_sublist_help", "qm", "show_surnames");
			print "<a href=\"".encode_url("famlist.php?alpha={$letter}&surname_sublist=yes&show_all={$show_all}&ged={$GEDCOM}")."\">".$pgv_lang["show_surnames"]."</a>";
		}
	}
}

if (empty($SEARCH_SPIDER)) {
	print_help_link("name_list_help", "qm", "name_list");
	print "<br /><br />";
}

if ($showList) {
	echo '<p class="center">';
	if (($surname_sublist=="yes")&&($show_all=="yes")) {
		get_fam_list();
		if (!isset($alpha)) $alpha="";
		$surnames = array();
		$fam_hide = array();
		foreach ($famlist as $gid=>$fam) {
			if (displayDetailsById($gid, "FAM")||showLivingNameById($gid, "FAM")) {
				$names = preg_split("/\+/", $fam["name"]);
				$foundnames = array();
				for($i=0; $i<count($names); $i++) {
					$name = trim($names[$i]);
					$sname = extract_surname($name);
					if (isset($foundnames[$sname])) {
						if (isset($surnames[$sname]["match"])) $surnames[$sname]["match"]--;
					}
					else $foundnames[$sname]=1;
				}
			}
			else $fam_hide[$gid."[".$fam["gedfile"]."]"] = 1;
		}
		uasort($surnames, "itemsort");
		print_surn_table($surnames, "FAM");
		if (count($fam_hide)>0) print "<br /><span class=\"warning\">".$pgv_lang["hidden"].": ".count($fam_hide)."</span>";
	}
	else if (($surname_sublist=="yes")&&(empty($surname))&&($show_all=="no")) {
		if (!isset($alpha)) $alpha="";
		$tfamlist = get_alpha_fams($alpha);
		$surnames = array();
		$fam_hide = array();
		foreach ($tfamlist as $gid=>$fam) {
			if ((displayDetailsByID($gid, "FAM"))||(showLivingNameById($gid, "FAM"))) {
				foreach ($fam["surnames"] as $indexval => $name) {
					$lname = strip_prefix($name);
					if (empty($lname)) $lname = $name;
					$firstLetter=get_first_letter(UTF8_strtoupper($lname));
					if ($alpha==$firstLetter) surname_count(trim($name));
				}
			}
			else $fam_hide[$gid."[".$fam["gedfile"]."]"] = 1;
		}
		$i = 0;
		uasort($surnames, "itemsort");
		print_surn_table($surnames, "FAM");
		if (count($fam_hide)>0) print "<br /><span class=\"warning\">".$pgv_lang["hidden"].": ".count($fam_hide)."</span>";
	} else {
		$firstname_alpha = false;
		//-- if the surname is set then only get the names in that surname list
		if ((!empty($surname))&&($surname_sublist=="yes")) {
			$surname = trim($surname);
			$tfamlist = get_surname_fams($surname);
			//-- split up long surname lists by first letter of first name
			if ($SUBLIST_TRIGGER_F>0 && count($tfamlist)>$SUBLIST_TRIGGER_F) $firstname_alpha = true;
		}

		if (($surname_sublist=="no")&&(!empty($alpha))&&($show_all=="no")) {
			$tfamlist = get_alpha_fams($alpha);
		}

		//-- simplify processing for ALL famlist
		if (($surname_sublist=="no")&&($show_all=="yes")) {
			$tfamlist = get_fam_list();
			uasort($tfamlist, "itemsort");
		} else {
			//--- the list is really long so divide it up again by the first letter of the first name
			if ($firstname_alpha) {
				$showList = false;		// Don't show the list until we have some filter criteria
				if (isset($falpha) || $show_all_firstnames=='yes') $showList = true;
				if (!isset($_SESSION[$surname."_firstalphafams"])||$DEBUG) {
					$firstalpha = array();
					foreach ($tfamlist as $gid=>$fam) {
						$initials = array();
						foreach (array($fam['husb'], $fam['wife']) as $pid) {
							$person=Person::getInstance($pid);
							foreach ($person->getAllNames() as $n=>$name) {
								if ($name['type']=='_MARNM') continue;
								list($surn,$givn) = explode(',', $name['sort'], 2);
								if (!empty($surname) && UTF8_strtoupper(trim($surn))!=UTF8_strtoupper($surname)) continue;
								$letter=UTF8_strtoupper(get_first_letter(trim($givn)));
								if (!isset($initials[$letter])) $initials[$letter] = $letter;	// Unique initials only
							}
						}
						foreach ($initials as $letter) {
							if (isset($firstalpha[$letter])) $firstalpha[$letter]["ids"] .= ",".$gid;
							else $firstalpha[$letter] = array("letter"=>$letter, "ids"=>$gid);
						}
					}
					uasort($firstalpha, "lettersort");
					//-- put the list in the session so that we don't have to calculate this the next time
					$_SESSION[$surname."_firstalphafams"] = $firstalpha;
				}
				else $firstalpha = $_SESSION[$surname."_firstalphafams"];
				echo '<h2 class="center">';
				print PrintReady(str_replace("#surname#", check_NN($surname), $pgv_lang["fams_with_surname"]));
				echo '</h2>';
				echo '<br />';
				print_help_link("firstname_f_help", "qm", "firstname_alpha_index");
				print $pgv_lang["first_letter_fname"]."<br />\n";
				$delayLetter = false;		// indicates that "@" was found in list
				foreach ($firstalpha as $letter=>$list) {
					if (!isset($falpha)) {
						$falpha = $letter;
					}
					if ($letter == "@") $delayLetter = true;
					else {
						print "<a href=\"".encode_url("famlist.php?alpha={$alpha}&surname={$surname}&falpha={$letter}&surname_sublist={$surname_sublist}&ged={$GEDCOM}")."\">";
						if ($showList && $falpha==$letter && $show_all_firstnames=="no") print "<span class=\"warning\">".$letter."</span>";
						else print $letter;
						print "</a> | \n";
					}
				}
				if ($delayLetter) {
					$letter = '@';
					print "<a href=\"".encode_url("famlist.php?alpha={$alpha}&surname={$surname}&falpha={$letter}&surname_sublist=yes&ged={$GEDCOM}")."\">";
					if ($showList && $falpha==$letter && $show_all_firstnames=="no") print "<span class=\"warning\">".PrintReady($pgv_lang["NN"])."</span>";
					else print PrintReady($pgv_lang["NN"]);
					print "</a> | \n";
				}
				if ($show_all_firstnames=="yes") print "<a href=\"".encode_url("famlist.php?alpha={$alpha}&surname={$surname}&show_all_firstnames=no&ged={$GEDCOM}")."\"><span class=\"warning\">".$pgv_lang["all"]."</span></a>\n";
				else print "<a href=\"".encode_url("famlist.php?alpha={$alpha}&surname={$surname}&show_all_firstnames=yes&ged={$GEDCOM}")."\">".$pgv_lang["all"]."</a>\n";
				if ($show_all_firstnames=="no") {
					$ffamlist = array();
					if (empty($falpha)) $falpha = key($firstalpha);
					$ids = preg_split("/,/", $firstalpha[$falpha]["ids"]);
					foreach ($ids as $indexval => $id) {
						if (isset($famlist[$id])) $ffamlist[$id] = $famlist[$id];
					}
					$tfamlist = $ffamlist;
				}
			}
			uasort($tfamlist, "itemsort");
		}
	}
	echo '</p>';

	if ($show_all=="yes") unset($alpha);
	if (!empty($surname) && $surname_sublist=="yes") $legend = str_replace("#surname#", check_NN($surname), $pgv_lang["fams_with_surname"]);
	else if (isset($alpha) and $show_all=="no") $legend = str_replace("#surname#", $alpha.".", $pgv_lang["fams_with_surname"]);
	else $legend = $pgv_lang["families"];
	if ($show_all_firstnames=="yes") $falpha = "@";
	if (isset($falpha) and $falpha!="@") $legend .= ", ".$falpha.".";
	$legend = PrintReady($legend);

	if ($showList && (!empty($surname) || $surname_sublist=="no")) {
		print_fam_table($tfamlist, $legend);
	}
}

print "<br /><br />\n";
print "</div>\n";
if(empty($SEARCH_SPIDER)) {
        print_footer();
}
else {
        print "</div>\n</body>\n</html>\n";
}
?>
