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

/*
$year = "1901";
$censevent  = new Event("1 CENS\n2 DATE 31 MAR ".$year."");
$censdate   = $censevent->getDate();
*/

$censdate  = new GedcomDate("31 MAR 1901");
$censyear   = $censdate->date1->y;

// TEST ONLY ===============================================
/*
$censevent2  = new GedcomDate("31 MAR 1901");
// Display date examples ----------------------------------- 
$censdate2   = $censevent2->Display(false, 'j O E');
$censyear2   = $censevent2->Display(false, 'E');
// Use dates for calculation examples ----------------------
$censyear   =  $censevent2->date1->y;$dif=10; 
echo $censyear-$dif;

*/
// END TEST ================================================

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
<script src="modules/GEDFact_assistant/_CENS/js/date.js" type="text/javascript"></script>


<?php
	// Debug =============================================================
	echo PGV_JS_START;
		// echo "parent_cls();";
		// echo "var link3 = 'individual.php?pid=$pid&show_changes=yes';";
		// echo "gparent_refr(link3);";
	echo PGV_JS_END;
	// Debug =============================================================

	// Header of assistant window =====================================================
	echo "<div class=\"cens_header\">";
		echo "<div class=\"cens_header_left\">";
			echo $pgv_lang["head"];
			echo " &nbsp;" . $wholename . "&nbsp; (" . $pid . ")";
		echo "</div>";
			if ($summary) {
				echo "<div class=\"cens_header_right\"/>". $summary. "</div>";
			}
	echo "</div>";
	

	//-- Census & Source Information Area ============================================= 
	echo "<div class=\" cens_container\">";
		echo "<span>";
			include('modules/GEDFact_assistant/_CENS/census_2_source_input.php');
		echo "</span>";
		//-- Proposed Census Text Area ================================================
		echo "<span>";
			include('modules/GEDFact_assistant/_CENS/census_4_text.php');
		echo "</span>";
	echo "</div>";
	
	//-- Search  and Add Family Members Area ========================================== 
	?><!--[if IE]><br /><![endif]--><?php
	echo "<div class=\"optionbox cens_search\" style=\"overflow:-moz-scrollbars-horizontal;overflow-x:hidden;overflow-y:scroll;\">";
		?>	<!--[if IE]><style>.cens_search{height:35.0em;}</style><![EndIf]-->
			<!--[if lte IE 7]><style>.cens_search{margin-top:-0.7em;}</style><![EndIf]--><?php
		include('modules/GEDFact_assistant/_CENS/census_3_search_add.php');
	echo "</div>";
	
	//-- Census Text Input Area =======================================================
	?>
	<div class="optionbox cens_textinput">
		<div class="cens_textinput_left">
			<input type="button" value="<?php echo $pgv_lang["cens_add_insert"]; ?>" onclick="insertRowToTable('', '', '', '', '', '', '', '', 'Age', '', '', '', '', '');" />
		</div>
		<div class="cens_textinput_right">
			<?php echo $pgv_lang["add"]; ?><br>
			<input  type="radio" name="totallyrad" value="0" checked="checked" />
		</div>	
	<?php
	
	//-- Census Add Rows Area =========================================================
		echo "<div class=\"cens_addrows\">";
			include('modules/GEDFact_assistant/_CENS/census_5_input.php');
		echo "</div>";
		?> 
	</div>


<script language="JavaScript" type="text/javascript">
 window.onLoad = initDynamicOptionLists();
</script>

