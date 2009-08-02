<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census information about an individual
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
 * @package PhpGedView
 * @subpackage Census Assistant
 * @version $Id$
 */
 
 if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

global $summary, $theme_name, $pgv_lang, $factarray, $censyear, $censdate;
 
$pid = safe_get('pid');
// echo $pid;
$year = "1901";
$censevent  = new Event("1 CENS\n2 DATE 03 MAR".$year."");
$censdate   = $censevent->getDate();
$censyear   = $censdate->date1->y;
$ctry       = "UK";
// $married    = GedcomDate::Compare($censdate, $marrdate);
$married=-1;

$person=Person::getInstance($pid);
// var_dump($person->getAllNames());
$nam = $person->getAllNames();
if (PrintReady($person->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($person->getDeathYear()); }
if (PrintReady($person->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($person->getBirthYear()); }
if ($married>=0 && isset($nam[1])){
	$wholename = rtrim($nam[1]['fullNN']);
} else {
	$wholename = rtrim($nam[0]['fullNN']);
}

$currpid=$pid;
?>

<script src="modules/GEDFact_assistant/_CENS/js/dynamicoptionlist.js" type="text/javascript"></script>

<?php
	// Header of assistant window
	echo "<div style=\"text-align:left; margin-top:5px;\">";
		echo "<div style=\"float:left; margin-left:15px; font-weight:bold;\">";
			echo "Head of Family &nbsp;:";
			echo " &nbsp;" . $wholename . "&nbsp; (" . $pid . ")";
		echo "</div>";
		echo "<div style=\"float:right;\">";
			if ($summary) {
				echo "<div class=\"center\" style=\"text-align:left; margin: 0 10px 0 0;\"/>". $summary. "</div>";
			}
		echo "</div>";
	echo "</div>";
	//-- Census & Source Information Area ============================================= 
	echo "<div style=\" clear:both; float:left; width:75%; margin: 5px 0 0 5px;\">";
		echo "<div>";
			include('modules/GEDFact_assistant/_CENS/census_2_source_input.php');
		echo "</div>";
		//-- Proposed Census Text Area ==================================================
		echo "<div style=\"float:left; width:100%;\">";
			include('modules/GEDFact_assistant/_CENS/census_4_text.php');
		echo "</div>";
	echo "</div>";
	//-- Search  and Add Family Members Area ========================================= 
	echo "<div style=\"float:right; margin: 5px 5px 0 0;\">";
		include('modules/GEDFact_assistant/_CENS/census_3_search_add.php'); 
	echo "</div>";
	//-- Census Text Input Area ===========================================================
	echo "<div class=\"optionbox\" style=\"clear:both;  border:3px outset; float:left; margin: 5px 0 0 5px;\">";
		include('modules/GEDFact_assistant/_CENS/census_5_input.php');
	echo "</div>";
?> 



<script language="JavaScript" type="text/javascript">
 window.onLoad = initDynamicOptionLists();
</script>

