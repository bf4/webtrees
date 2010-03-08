<?php
/**
 * Facility in Census assistant that will allow a user to search for a person id
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
 * @subpackage Display
 * @version $Id$
 */

require './config.php';

require_once 'includes/functions/functions_print_lists.php';

$type          =safe_GET('type', PGV_REGEX_ALPHA, 'indi');
$filter        =safe_GET('filter');
$action        =safe_GET('action');
$callback      =safe_GET('callback', PGV_REGEX_NOSCRIPT, 'paste_id');
$create        =safe_GET('create');
$media         =safe_GET('media');
$external_links=safe_GET('external_links');
$directory     =safe_GET('directory', PGV_REGEX_NOSCRIPT, $MEDIA_DIRECTORY);
$multiple      =safe_GET_bool('multiple');
$showthumb     =safe_GET_bool('showthumb');
$all           =safe_GET_bool('all');
$choose=safe_GET('choose', PGV_REGEX_NOSCRIPT, '0all');

$thumbget = "";
if ($showthumb) {$thumbget = "&showthumb=true";}

$embed = substr($choose,0,1)=="1";
$chooseType = substr($choose,1);
if ($chooseType!="media" && $chooseType!="file") $chooseType = "all";

//-- force the thumbnail directory to have the same layout as the media directory
//-- Dots and slashes should be escaped for the preg_replace
$srch = "/".addcslashes($MEDIA_DIRECTORY,'/.')."/";
$repl = addcslashes($MEDIA_DIRECTORY."thumbs/",'/.');
$thumbdir = stripcslashes(preg_replace($srch, $repl, $directory));
$level=safe_GET('level', PGV_REGEX_INTEGER, 0);

//-- prevent script from accessing an area outside of the media directory
//-- and keep level consistency
if (($level < 0) || ($level > $MEDIA_DIRECTORY_LEVELS)){
	$directory = $MEDIA_DIRECTORY;
	$level = 0;
} elseif (preg_match("'^$MEDIA_DIRECTORY'", $directory)==0){
	$directory = $MEDIA_DIRECTORY;
	$level = 0;
}
// End variables for find media

// Variables for Find Special Character
$language_filter=safe_GET('language_filter');

if (empty($language_filter)) {
	if (!empty($_SESSION["language_filter"])) $language_filter = $_SESSION["language_filter"];
	else $language_filter=$lang_short_cut[$LANGUAGE];
}
$magnify=safe_GET_bool('magnify');

require 'includes/specialchars.php';

// End variables for Find Special Character

switch ($type) {
	case "indi" :
		print_simple_header(i18n::translate('Find Individual ID'));
		break;
	case "fam" :
		print_simple_header(i18n::translate('Find Family List'));
		break;
	case "media" :
		print_simple_header(i18n::translate('Find Media'));
		$action="filter";
		break;
	case "place" :
		print_simple_header(i18n::translate('Find Place'));
		$action="filter";
		break;
	case "repo" :
		print_simple_header(i18n::translate('Repositories'));
		$action="filter";
		break;
	case "source" :
		print_simple_header(i18n::translate('Find Source'));
		$action="filter";
		break;
	case "specialchar" :
		print_simple_header(i18n::translate('Find Special Characters'));
		$action="filter";
		break;
}

?>
<script language="JavaScript" type="text/javascript">
<!--
	function pasterow(id, nam, mnam, label, gend, cond, dom, dob, dod, occu, age, birthpl, fbirthpl, mbirthpl, chilBLD) {
		window.opener.insertRowToTable(id, nam, mnam, label, gend, cond, dom, dob, dod, occu, age, birthpl, fbirthpl, mbirthpl, chilBLD);
		<?php if (!$multiple) print "window.close();"; ?>
	}
	
	function pasteid(id, name,thumb) {
		if (thumb) {
			window.opener.<?php print $callback; ?>(id,name,thumb);
			<?php if (!$multiple) print "window.close();"; ?>
		}else{
			window.opener.<?php print $callback; ?>(id);
			if (window.opener.pastename) window.opener.pastename(name);
			<?php if (!$multiple) print "window.close();"; ?>
		}
	}

	var language_filter;
	function paste_char(selected_char,language_filter,magnify) {
		window.opener.paste_char(selected_char,language_filter,magnify);
		return false;
	}

	function setMagnify() {
		document.filterspecialchar.magnify.value = '<?PHP print !$magnify; ?>';
		document.filterspecialchar.submit();
	}

	function checknames(frm) {
		if (document.forms[0].subclick) button = document.forms[0].subclick.value;
		else button = "";
		if (frm.filter.value.length<2&button!="all") {
			alert("<?php print i18n::translate('Please enter more than one character'); ?>");
			frm.filter.focus();
			return false;
		}
		if (button=="all") {
			frm.filter.value = "";
		}
		return true;
	}
//-->
</script>
<?php
$options = array();
$options["option"][]= "findindi";
$options["option"][]= "findfam";
$options["option"][]= "findmedia";
$options["option"][]= "findplace";
$options["option"][]= "findrepo";
$options["option"][]= "findsource";
$options["option"][]= "findspecialchar";
$options["form"][]= "formindi";
$options["form"][]= "formfam";
$options["form"][]= "formmedia";
$options["form"][]= "formplace";
$options["form"][]= "formrepo";
$options["form"][]= "formsource";
$options["form"][]= "formspecialchar";

global $TEXT_DIRECTION, $MULTI_MEDIA;
print "<div align=\"center\">";
print "<table class=\"list_table $TEXT_DIRECTION width90\" border=\"0\">";
print "<tr><td style=\"padding: 10px;\" valign=\"top\" class=\"facts_label03 width90\">"; // start column for find text header

switch ($type) {
	case "indi" :
		print i18n::translate('Find Individual ID');
		break;
	case "fam" :
		print i18n::translate('Find Family List');
		break;
	case "media" :
		print i18n::translate('Find Media');
		break;
	case "place" :
		print i18n::translate('Find Place');
		break;
	case "repo" :
		print i18n::translate('Repositories');
		break;
	case "source" :
		print i18n::translate('Find Source');
		break;
	case "specialchar" :
		print i18n::translate('Find Special Characters');
		break;
}

	print "</td>"; // close column for find text header

	// start column for find options
	print "</tr><tr><td class=\"list_value\" style=\"padding: 5px;\">";

	// Show indi and hide the rest
	if ($type == "indi") {
		print "<div align=\"center\">";
		print "<form name=\"filterindi\" method=\"get\" onsubmit=\"return checknames(this);\" action=\"find.php\">";
		print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
		print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
		print "<input type=\"hidden\" name=\"type\" value=\"indi\" />";
		print "<input type=\"hidden\" name=\"multiple\" value=\"$multiple\" />";
/*
		print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print i18n::translate('Name contains:')." <input type=\"text\" name=\"filter\" value=\"";
		if ($filter) print $filter;
		print "\" />";
		print "</td></tr>";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print "<input type=\"submit\" value=\"".i18n::translate('Filter')."\" /><br />";
		print "</td></tr></table>";
*/
		print "</form></div>";
	}

	// Show fam and hide the rest
	if ($type == "fam") {
		print "<div align=\"center\">";
		print "<form name=\"filterfam\" method=\"get\" onsubmit=\"return checknames(this);\" action=\"find.php\">";
		print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
		print "<input type=\"hidden\" name=\"type\" value=\"fam\" />";
		print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
		print "<input type=\"hidden\" name=\"multiple\" value=\"$multiple\" />";
		print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print i18n::translate('Name contains:')." <input type=\"text\" name=\"filter\" value=\"";
		if ($filter) print $filter;
		print "\" />";
		print "</td></tr>";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print "<input type=\"submit\" value=\"".i18n::translate('Filter')."\" /><br />";
		print "</td></tr></table>";
		print "</form></div>";
	}

	// Show media and hide the rest
	if ($type == "media" && $MULTI_MEDIA) {
		print "<div align=\"center\">";
		print "<form name=\"filtermedia\" method=\"get\" onsubmit=\"return checknames(this);\" action=\"find.php\">";
		print "<input type=\"hidden\" name=\"choose\" value=\"".$choose."\" />";
		print "<input type=\"hidden\" name=\"directory\" value=\"".$directory."\" />";
		print "<input type=\"hidden\" name=\"thumbdir\" value=\"".$thumbdir."\" />";
		print "<input type=\"hidden\" name=\"level\" value=\"".$level."\" />";
		print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
		print "<input type=\"hidden\" name=\"type\" value=\"media\" />";
		print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
		print "<input type=\"hidden\" name=\"subclick\">"; // This is for passing the name of which submit button was clicked
		print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print i18n::translate('Media contains:')." <input type=\"text\" name=\"filter\" value=\"";
		if ($filter) print $filter;
		print "\" />";
		print_help_link("simple_filter","qm");
		print "</td></tr>";
		print "<tr><td class=\"list_label width10\" wstyle=\"padding: 5px;\">";
		print "<input type=\"checkbox\" name=\"showthumb\" value=\"true\"";
		if( $showthumb) print "checked=\"checked\"";
		print "onclick=\"javascript: this.form.submit();\" />".i18n::translate('Show thumbnails');
		print_help_link("show_thumb","qm");
		print "</td></tr>";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print "<input type=\"submit\" name=\"search\" value=\"".i18n::translate('Filter')."\" onclick=\"this.form.subclick.value=this.name\" />&nbsp;";
		print "<input type=\"submit\" name=\"all\" value=\"".i18n::translate('Display all')."\" onclick=\"this.form.subclick.value=this.name\" />";
		print "</td></tr></table>";
		print "</form></div>";
	}

	// Show place and hide the rest
	if ($type == "place") {
		print "<div align=\"center\">";
		print "<form name=\"filterplace\" method=\"get\"  onsubmit=\"return checknames(this);\" action=\"find.php\">";
		print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
		print "<input type=\"hidden\" name=\"type\" value=\"place\" />";
		print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
		print "<input type=\"hidden\" name=\"subclick\">"; // This is for passing the name of which submit button was clicked
		print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print i18n::translate('Place contains:')." <input type=\"text\" name=\"filter\" value=\"";
		if ($filter) print $filter;
		print "\" />";
		print "</td></tr>";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print "<input type=\"submit\" name=\"search\" value=\"".i18n::translate('Filter')."\" onclick=\"this.form.subclick.value=this.name\" />&nbsp;";
		print "<input type=\"submit\" name=\"all\" value=\"".i18n::translate('Display all')."\" onclick=\"this.form.subclick.value=this.name\" />";
		print "</td></tr></table>";
		print "</form></div>";
	}

	// Show repo and hide the rest
	if ($type == "repo" && $SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) {
		print "<div align=\"center\">";
		print "<form name=\"filterrepo\" method=\"get\" onsubmit=\"return checknames(this);\" action=\"find.php\">";
		print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
		print "<input type=\"hidden\" name=\"type\" value=\"repo\" />";
		print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
		print "<input type=\"hidden\" name=\"subclick\">"; // This is for passing the name of which submit button was clicked
		print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print i18n::translate('Repository contains:')." <input type=\"text\" name=\"filter\" value=\"";
		if ($filter) print $filter;
		print "\" />";
		print "</td></tr>";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print "<input type=\"submit\" name=\"search\" value=\"".i18n::translate('Filter')."\" onclick=\"this.form.subclick.value=this.name\" />&nbsp;";
		print "<input type=\"submit\" name=\"all\" value=\"".i18n::translate('Display all')."\" onclick=\"this.form.subclick.value=this.name\" />";
		print "</td></tr></table>";
		print "</form></div>";
	}

	// Show source and hide the rest
	if ($type == "source" && $SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) {
		print "<div align=\"center\">";
		print "<form name=\"filtersource\" method=\"get\" onsubmit=\"return checknames(this);\" action=\"find.php\">";
		print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
		print "<input type=\"hidden\" name=\"type\" value=\"source\" />";
		print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
		print "<input type=\"hidden\" name=\"subclick\">"; // This is for passing the name of which submit button was clicked
		print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print i18n::translate('Source contains:')." <input type=\"text\" name=\"filter\" value=\"";
		if ($filter) print $filter;
		print "\" />";
		print "</td></tr>";
		print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
		print "<input type=\"submit\" name=\"search\" value=\"".i18n::translate('Filter')."\" onclick=\"this.form.subclick.value=this.name\" />&nbsp;";
		print "<input type=\"submit\" name=\"all\" value=\"".i18n::translate('Display all')."\" onclick=\"this.form.subclick.value=this.name\" />";
		print "</td></tr></table>";
		print "</form></div>";
	}

	// Show specialchar and hide the rest
	if ($type == "specialchar") {
		print "<div align=\"center\">";
		print "<form name=\"filterspecialchar\" method=\"get\" action=\"find.php\">";
		print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
		print "<input type=\"hidden\" name=\"type\" value=\"specialchar\" />";
		print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
		print "<input type=\"hidden\" name=\"magnify\" value=\"".$magnify."\" />";
		print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
		print "<tr><td class=\"list_label\" style=\"padding: 5px;\">";
		print "<select id=\"language_filter\" name=\"language_filter\" onchange=\"submit();\">";
		print "\n\t<option value=\"\">".i18n::translate('Change Language')."</option>";
		$language_options = "";
		foreach($specialchar_languages as $key=>$value) {
			$language_options.= "\n\t<option value=\"$key\">$value</option>";
		}
		$language_options = str_replace("\"$language_filter\"","\"$language_filter\" selected",$language_options);
		print $language_options;
		print "</select><br /><a href=\"javascript:;\" onclick=\"setMagnify()\">".i18n::translate('Magnify')."</a>";
		print "</td></tr></table>";
		print "</form></div>";
	}
	// end column for find options
print "</td></tr>";
print "</table>"; // Close table with find options

print "<br />";
print "<a href=\"javascript:;\" onclick=\"if (window.opener.showchanges) window.opener.showchanges(); window.close();\">".i18n::translate('Close Window')."</a><br />\n";
print "<br />";

if ($action=="filter") {
	$filter = trim($filter);
	
	
	// ========================================================================
	
	// Output Individual's Details for GEDFact assistant ------
	if ($type == "indi") {
		print "<table class=\"tabs_table $TEXT_DIRECTION width90\">\n\t\t<tr>";
		$myindilist=search_indis_names(array($filter), array(PGV_GED_ID), 'AND');
		if ($myindilist) {
			print "\n\t\t<td class=\"list_value_wrap $TEXT_DIRECTION\"><ul>";
			usort($myindilist, array('GedcomRecord', 'Compare'));
			foreach($myindilist as $indi ) {

				$nam = $indi->getAllNames();
				$wholename = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
				$fulln = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surname'];
				$fulln = str_replace('"', '\'', $fulln);									// Replace double quotes
				$fulln = str_replace("@N.N.", "(".i18n::translate('unknown').")", $fulln);
				$fulln = str_replace("@P.N.", "(".i18n::translate('unknown').")", $fulln);
				$givn  = rtrim($nam[0]['givn'],'*');
				$surn  = $nam[0]['surname'];
				if (isset($nam[1])) {
					$fulmn = rtrim($nam[1]['givn'],'*')."&nbsp;".$nam[1]['surname'];
					$fulmn = str_replace('"', '\'', $fulmn);								// Replace double quotes
					$fulmn = str_replace("@N.N.", "(".i18n::translate('unknown').")", $fulmn);
					$fulmn = str_replace("@P.N.", "(".i18n::translate('unknown').")", $fulmn);
					$marn  = $nam[1]['surname'];
				} else {
					$fulmn = $fulln;
				}

				//-- Build Indi Parents Family to get FBP and MBP  -----------
				$families = $indi->getChildFamilies();
				foreach($families as $famid=>$family) {
					if (!is_null($family)) {
						$father = $family->getHusband();
						$mother = $family->getWife();
						if (!is_null($father)) { 
							$FBP = $father->getBirthPlace(); 
						}
						if (!is_null($mother)) { 
							$MBP = $mother->getBirthPlace(); 
						}
					} 
				}
				if (!isset($FBP)) { $FBP = "UNK, UNK, UNK, UNK"; }
				if (!isset($MBP)) { $MBP = "UNK, UNK, UNK, UNK"; }
				
				//-- Build Indi Spouse Family to get marriage Date ----------
				$families = $indi->getSpouseFamilies();
				foreach($families as $famid=>$family) {
					$marrdate = $family->getMarriageDate();
					$marrdate = ($marrdate->minJD()+$marrdate->maxJD())/2;  // Julian
					$children = $family->getChildren();
				}
				if (!isset($marrdate)) { $marrdate = ""; }

				//-- Get Children's Name, DOB, DOD --------------------------
				if (isset($children)) {
					$chBLDarray = Array();
					foreach ($children as $key=>$child) {
						$chnam   = $child->getAllNames();
						$chfulln = rtrim($chnam[0]['givn'],'*')." ".$chnam[0]['surname'];
						$chfulln = str_replace('"', "", $chfulln);											// Must remove quotes completely here
						$chfulln = str_replace("@N.N.", "(".i18n::translate('unknown').")", $chfulln);
						$chfulln = str_replace("@P.N.", "(".i18n::translate('unknown').")", $chfulln);			// Child's Full Name
						$chdob   = ($child->getBirthDate()->minJD()+$child->getBirthDate()->maxJD())/2;		// Child's Date of Birth (Julian)
						if (!isset($chdob)) { $chdob = ""; }
						$chdod   = ($child->getDeathDate()->minJD()+$child->getDeathDate()->maxJD())/2;		// Child's Date of Death (Julian)
						if (!isset($chdod)) { $chdod = ""; }
						$chBLD   = ($chfulln.", ".$chdob.", ".$chdod);
						array_push($chBLDarray, $chBLD);
					}
				}
				if (isset($chBLDarray) && $indi->getSex()=="F") {
					$chBLDarray = implode("::", $chBLDarray);
				} else {
					$chBLDarray = '';
				}
				
				echo "<li>";
					// ==============================================================================================================================
					// NOTES = function pasterow(id, nam, mnam, label, gend, cond, dom, dob, age, dod, occu, birthpl, fbirthpl, mbirthpl, chilBLD) {
					// ==============================================================================================================================
					echo "<a href=\"javascript:;\" onclick=\"window.opener.insertRowToTable(";
						echo "'".$indi->getXref()."', ";															 // id        - Indi Id 
						echo "'".addslashes(strip_tags($fulln))."', ";												 // nam       - Name
						echo "'".addslashes(strip_tags($fulmn))."', ";												 // mnam      - Married Name
						echo "'-', ";																				 // label     - Relation to Head of Household
						echo "'".$indi->getSex()."', ";																 // gend      - Sex
						echo "'S', ";																				 // cond      - Marital Condition
						echo "'".$marrdate."', ";																	 // dom       - Date of Marriage
						echo "'".(($indi->getBirthDate()->minJD() + $indi->getBirthDate()->maxJD())/2)."' ,";		 // dob       - Date of Birth
						echo "'".(1901-$indi->getbirthyear())."' ,";												 // ~age~     - Census Date minus YOB (Preliminary)
						echo "'".(($indi->getDeathDate()->minJD() + $indi->getDeathDate()->maxJD())/2)."' ,";		 // dod       - Date of Death
						echo "'', ";																				 // occu      - Occupation
						echo "'".$indi->getbirthplace()."', ";														 // birthpl   - Birthplace
						echo "'".$FBP."', ";																		 // fbirthpl  - Father's Birthplace
						echo "'".$MBP."', ";																		 // mbirthpl  - Mother's Birthplace
						echo "'".$chBLDarray."'";																	 // chilBLD   - Array of Children (name, birthdate, deathdate)
						echo ");";
						echo "return false;\">";
					echo "<b>".$indi->getFullName()."</b>&nbsp;&nbsp;&nbsp;";										 // Name Link
					echo "</span><br><span class=\"list_item\">Born ".$indi->getbirthyear()."&nbsp;&nbsp;&nbsp;".$indi->getbirthplace()."</span>";
					echo "</a>";
				echo "</li>";
			echo "<hr />";
			}
			echo '</td></tr><tr><td class="list_label">', i18n::translate('Total individuals'), ' ', count($myindilist), '</tr></td>';
		} else {
			print "<td class=\"list_value_wrap\">";
			print i18n::translate('No results found.');
			print "</td></tr>";
		}
		print "</table>";
	}
	
	// ========================================================================

	
	// Output Family
	if ($type == "fam") {
		print "\n\t<table class=\"tabs_table $TEXT_DIRECTION width90\">\n\t\t<tr>";
		// Get the famrecs with hits on names from the family table
		// Get the famrecs with hits in the gedcom record from the family table
		$myfamlist = pgv_array_merge(
			search_fams_names(array($filter), array(PGV_GED_ID), 'AND'),
			search_fams(array($filter), array(PGV_GED_ID), 'AND', true)
		);
		if ($myfamlist) {
			$curged = $GEDCOM;
			print "\n\t\t<td class=\"list_value_wrap $TEXT_DIRECTION\"><ul>";
			usort($myfamlist, array('GedcomRecord', 'Compare'));
			foreach($myfamlist as $family) {
				echo $family->format_list('li', true);
			}
			echo '</ul></td></tr><tr><td class="list_label">', i18n::translate('Total families'), ' ', count($myfamlist), '</tr></td>';
		} else {
			print "<td class=\"list_value_wrap\">";
			print i18n::translate('No results found.');
			print "</td></tr>";
		}
		print "</table>";
	}

	// Output Media
	if ($type == "media") {
		global $dirs;

		$medialist = get_medialist(true, $directory);

		print "\n\t<table class=\"tabs_table $TEXT_DIRECTION width90\">\n\t\t";
		// Show link to previous folder
		if ($level>0) {
			$levels = explode("/", $directory);
			$pdir = "";
			for($i=0; $i<count($levels)-2; $i++) $pdir.=$levels[$i]."/";
			$levels = explode("/", $thumbdir);
			$pthumb = "";
			for($i=0; $i<count($levels)-2; $i++) $pthumb.=$levels[$i]."/";
			$uplink = "<a href=\"".encode_url("find.php?directory={$pdir}&thumbdir={$pthumb}&level=".($level-1)."{$thumbget}&type=media&choose={$choose}")."\">&nbsp;&nbsp;&nbsp;&lt;-- <span dir=\"ltr\">".$pdir."</span>&nbsp;&nbsp;&nbsp;</a><br />\n";
		}

		// Start of media directory table
		print "<table class=\"list_table $TEXT_DIRECTION width90\">";

		// Tell the user where he is
		print "<tr>";
			print "<td class=\"topbottombar\" colspan=\"2\">";
				print i18n::translate('Current directory');
				print "<br />";
				print substr($directory,0,-1);
			print "</td>";
		print "</tr>";

		// display the directory list
		if (count($dirs) || $level) {
			sort($dirs);
			if ($level){
				print "<tr><td class=\"list_value $TEXT_DIRECTION\" colspan=\"2\">";
				print $uplink."</td></tr>";
			}
			print "<tr><td class=\"descriptionbox $TEXT_DIRECTION\" colspan=\"2\">";
			print "<a href=\"".encode_url("find.php?directory={$directory}&thumbdir=".str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $directory)."&level={$level}{$thumbget}&external_links=http&type=media&choose={$choose}")."\">".i18n::translate('External objects')."</a>";
			print "</td></tr>";
			foreach ($dirs as $indexval => $dir) {
				print "<tr><td class=\"list_value $TEXT_DIRECTION\" colspan=\"2\">";
				print "<a href=\"".encode_url("find.php?directory={$directory}{$dir}/&thumbdir={$directory}{$dir}/&level=".($level+1)."{$thumbget}&type=media&choose={$choose}")."\"><span dir=\"ltr\">".$dir."</span></a>";
				print "</td></tr>";
			}
		}
		print "<tr><td class=\"descriptionbox $TEXT_DIRECTION\" colspan=\"2\"></td></tr>";

		/**
		 * This action generates a thumbnail for the file
		 *
		 * @name $create->thumbnail
		 */
		if ($create=="thumbnail") {
			$filename = $_REQUEST["file"];
			generate_thumbnail($directory.$filename,$thumbdir.$filename);
		}

		print "<br />";

		// display the images TODO x across if lots of files??
		if (count($medialist) > 0) {
			foreach ($medialist as $indexval => $media) {

				// Check if the media belongs to the current folder
				preg_match_all("/\//", $media["FILE"], $hits);
				$ct = count($hits[0]);

				if (($ct <= $level+1 && $external_links != "http" && !isFileExternal($media["FILE"])) || (isFileExternal($media["FILE"]) && $external_links == "http")) {
					// simple filter to reduce the number of items to view
					$isvalid = filterMedia($media, $filter, 'http');
					if ($isvalid && $chooseType!="all") {
						if ($chooseType=="file" && !empty($media["XREF"])) $isvalid = false;	// skip linked media files
						if ($chooseType=="media" && empty($media["XREF"])) $isvalid = false;	// skip unlinked media files
					}

					if ($isvalid) {
						if ($media["EXISTS"] && media_filesize($media["FILE"]) != 0){
							$imgsize = findImageSize($media["FILE"]);
							$imgwidth = $imgsize[0]+40;
							$imgheight = $imgsize[1]+150;
						}
						else {
							$imgwidth = 0;
							$imgheight = 0;
						}

						print "<tr>";

						//-- thumbnail field
						if ($showthumb) {
							print "\n\t\t\t<td class=\"list_value $TEXT_DIRECTION width10\">";
							if (isset($media["THUMB"])) print "<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($media["FILE"])."',$imgwidth, $imgheight);\"><img src=\"".filename_decode($media["THUMB"])."\" border=\"0\" width=\"50\" alt=\"\" /></a>\n";
							else print "&nbsp;";
						}

						//-- name and size field
						print "\n\t\t\t<td class=\"list_value $TEXT_DIRECTION\">";
						if ($media["TITL"] != "") {
							print "<b>".PrintReady($media["TITL"])."</b>&nbsp;&nbsp;";
							if ($TEXT_DIRECTION=="rtl") print getRLM();
							print "(".$media["XREF"].")";
							if ($TEXT_DIRECTION=="rtl") print getRLM();
							print "<br />";
						}
						if (!$embed){
							print "<a href=\"javascript:;\" onclick=\"pasteid('".addslashes($media["FILE"])."');\"><span dir=\"ltr\">".$media["FILE"]."</span></a> -- ";
						}
						else print "<a href=\"javascript:;\" onclick=\"pasteid('".$media["XREF"]."','".addslashes($media["TITL"])."','".addslashes($media["THUMB"])."');\"><span dir=\"ltr\">".$media["FILE"]."</span></a> -- ";
						print "<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($media["FILE"])."',$imgwidth, $imgheight);\">".i18n::translate('View')."</a><br />";
						if (!$media["EXISTS"] && !isFileExternal($media["FILE"])) print $media["FILE"]."<br /><span class=\"error\">".i18n::translate('The filename entered does not exist.')."</span><br />";
						else if (!isFileExternal($media["FILE"]) && !empty($imgsize[0])) {
							print "<br /><sub>&nbsp;&nbsp;".i18n::translate('Image Dimensions')." -- ".$imgsize[0]."x".$imgsize[1]."</sub><br />";
						}
						if ($media["LINKED"]) {
							print i18n::translate('This media object is linked to the following:')."<br />";
							foreach ($media["LINKS"] as $indi => $type_record) {
								if ($type_record!='INDI' && $type_record!='FAM' && $type_record!='SOUR' && $type_record!='OBJE') continue;
								$record=GedcomRecord::getInstance($indi);
								echo '<br /><a href="'.encode_url($record->getLinkUrl()).'">';
								switch($type_record) {
								case 'INDI':
									echo i18n::translate('View Person'), ' - ';
									break;
								case 'FAM':
									echo i18n::translate('View Family'), ' - ';
									break;
								case 'SOUR':
									echo i18n::translate('View Source'), ' - ';
									break;
								case 'OBJE':
									echo i18n::translate('View Object'), ' - ';
									break;
								}
								echo PrintReady($record->getFullName()), '</a>';
							}
						} else {
							print i18n::translate('This media object is not linked to any GEDCOM record.');
						}
						print "\n\t\t\t</td>";
					}
				}
			}
		}
		else {
			print "<tr><td class=\"list_value_wrap\">";
			print i18n::translate('No results found.');
			print "</td></tr>";
		}
		print "</table>";
	}

	// Output Places
	if ($type == "place") {
		print "\n\t<table class=\"tabs_table $TEXT_DIRECTION width90\">\n\t\t<tr>";
		$placelist = array();
		if ($all || $filter)
		{
			find_place_list($filter);
			uasort($placelist, "stringsort");
			$ctplace = count($placelist);
			if ($ctplace>0) {
				print "\n\t\t<td class=\"list_value_wrap $TEXT_DIRECTION\"><ul>";
				foreach($placelist as $indexval => $revplace) {
					$levels = explode(',', $revplace);		// -- split the place into comma seperated values
					$levels = array_reverse($levels);				// -- reverse the array so that we get the top level first
					$placetext="";
					$j=0;
					foreach($levels as $indexval => $level) {
						if ($j>0) $placetext .= ", ";
						$placetext .= trim($level);
						$j++;
					}
					print "<li><a href=\"javascript:;\" onclick=\"pasteid('".preg_replace(array("/'/",'/"/'), array("\'",'&quot;'), $placetext)."');\">".PrintReady($revplace)."</a></li>\n";
				}
				print "\n\t\t</ul></td></tr>";
				print "<tr><td class=\"list_label\">".i18n::translate('Places found')." ".$ctplace;
				print "</td></tr>";
			}
			else {
				print "<tr><td class=\"list_value_wrap $TEXT_DIRECTION\"><ul>";
				print i18n::translate('No results found.');
				print "</td></tr>";
			}
		}
		print "</table>";
	}

	// Output Repositories
	if ($type == "repo") {
		print "\n\t<table class=\"tabs_table $TEXT_DIRECTION width90\">\n\t\t<tr>";
		$repo_list = get_repo_list(PGV_GED_ID);
		if ($repo_list) {
			print "\n\t\t<td class=\"list_value_wrap\"><ul>";
			foreach ($repo_list as $repo) {
				echo "<li><a href=\"javascript:;\" onclick=\"pasteid('".$repo->getXref()."');\"><span class=\"list_item\">".$repo->getListName()."&nbsp;&nbsp;&nbsp;";
				echo PGV_LPARENS.$repo->getXref().PGV_RPARENS;
				echo "</span></a></li>";
			}
			print "</ul></td></tr>";
			print "<tr><td class=\"list_label\">".i18n::translate('Repositories found')." ".count($repo_list);
			print "</td></tr>";
		}
		else {
			print "<tr><td class=\"list_value_wrap\">";
			print i18n::translate('No results found.');
			print "</td></tr>";
		}
		print "</table>";

	}
	// Output Sources
	if ($type=="source") {
		echo '<table class="tabs_table ', $TEXT_DIRECTION, ' width90"><tr><td class="list_value"><tr>';
		if ($filter) {
			$mysourcelist = search_sources(array($filter), array(PGV_GED_ID), 'AND', true);
		} else {
			$mysourcelist = get_source_list(PGV_GED_ID);
		}
		if ($mysourcelist) {
			usort($mysourcelist, array('GedcomRecord', 'Compare'));
			echo '<td class="list_value_wrap"><ul>';
			foreach ($mysourcelist as $source) {
				echo '<li><a href="javascript:;" onclick="pasteid(\'', $source->getXref(), "', '", preg_replace("/(['\"])/", "\\$1", PrintReady($source->getFullName())), '\'); return false;"><span class="list_item">', PrintReady($source->getFullName()), '</span></a></li>';
			}
			echo '</ul></td></tr><tr><td class="list_label">', i18n::translate('Total Sources'), ' ', count($mysourcelist), '</td></tr>';
		}
		else {
			echo '<tr><td class="list_value_wrap">', i18n::translate('No results found.'), '</td></tr>';
		}
		print '</table>';
		if (PGV_USER_CAN_EDIT) {
			print_help_link('edit_add_unlinked_source', 'qm'); ?><a href="javascript: <?php print i18n::translate('Add an unlinked source'); ?>" onclick="addnewsource(''); return false;"><?php print i18n::translate('Add an unlinked source'); ?></a>
		<?php
		}
	}

	// Output Special Characters
	if ($type == "specialchar") {
		print "\n\t<table class=\"tabs_table $TEXT_DIRECTION width90\">\n\t\t<tr>\n\t\t<td class=\"list_value center wrap\" dir=\"$TEXT_DIRECTION\"><br/>";
		// lower case special characters
		if ($magnify) {
			echo '<span class="largechars">';
		}
		foreach($lcspecialchars as $key=>$value) {
			$value = str_replace("'","\'",$value);
			print "<a href=\"javascript:;\" onclick=\"return paste_char('$value','$language_filter','$magnify');\">";
			print $key;
			print "</span></a> ";
		}
		if ($magnify) {
			echo '<span class="largechars">';
		}
		echo '<br/><br/>';
		//upper case special characters
		if ($magnify) {
			echo '<span class="largechars">';
		}
		foreach($ucspecialchars as $key=>$value) {
			$value = str_replace("'","\'",$value);
			print "<a href=\"javascript:;\" onclick=\"return paste_char('$value','$language_filter','$magnify');\">";
			print $key;
			print "</span></a> ";
		}
		if ($magnify) {
			echo '<span class="largechars">';
		}
		echo '<br/><br/>';
		// other special characters (not letters)
		if ($magnify) {
			echo '<span class="largechars">';
		}
		foreach($otherspecialchars as $key=>$value) {
			$value = str_replace("'","\'",$value);
			print "<a href=\"javascript:;\" onclick=\"return paste_char('$value','$language_filter','$magnify');\">";
			print $key;
			print "</span></a> ";
		}
		if ($magnify) {
			echo '<span class="largechars">';
		}
		echo '<br/><br/></td></tr></table>';
	}
}
print "</div>"; // Close div that centers table
?>
<script language="JavaScript" type="text/javascript">
<!--
	document.filter<?php print $type; ?>.filter.focus();
//-->
</script>
<?php
print_simple_footer();

?>