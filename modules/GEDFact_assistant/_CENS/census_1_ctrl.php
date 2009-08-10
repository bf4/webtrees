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
<!-- <script type="text/javascript" src="modules/GEDFact_assistant/_CENS/js/ie-console.js"></script>
<script type="text/javascript">IEConsole.log('Welcome BH to ie-console!'); </script> -->
<?php

	// Header of assistant window
	echo "<div style=\"text-align:left; margin-top:0.5em;\">";
		echo "<div style=\"float:left; margin-left:1.5em; font-weight:bold;\">";
			echo "Head of Family &nbsp;:";
			echo " &nbsp;" . $wholename . "&nbsp; (" . $pid . ")";
		echo "</div>";
		echo "<div style=\"float:right; margin: 0 2em 0 0;\">";
			if ($summary) {
				echo "<div style=\"text-align:left; margin: 0 2em 0 0;\"/>". $summary. "</div>";
			}
		echo "</div>";
	echo "</div>";

	//-- Census & Source Information Area ============================================= 
	echo "<div style=\" clear:both; float:left; width:48em; height:34em; margin: 0.5em 0 0 0.3em;\">";
		echo "<span style=\"margin: 0 0.5em;\">";
			include('modules/GEDFact_assistant/_CENS/census_2_source_input.php');
		echo "</span>";
		//-- Proposed Census Text Area ==================================================
		echo "<span style=\"margin: 0 0.5em;\">";
			include('modules/GEDFact_assistant/_CENS/census_4_text.php');
		echo "</span>";
	echo "</div>";
	//-- Search  and Add Family Members Area ========================================= 
	echo "<div class=\"optionbox\" style=\"float:left; border:0.3em outset; margin: 0.5em 0 0 0.3em; width: 21.5em; height:34.24em; overflow:auto;\">";
		include('modules/GEDFact_assistant/_CENS/census_3_search_add.php'); 
	echo "</div>";
	//-- Census Text Input Area ===========================================================
	?>
	<div class="optionbox" style="clear:both; border:0.3em outset; float:left; margin:0.4em 0 0 0.3em; width:69.8em; \">
	<table style="width:69.8em; float:left;" border="0" cellspacing="1">
		<tr>
			<td align="center" colspan="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td align="center">
				<input type="button" value="Add/Insert Blank Row" onclick="insertRowToTable('','','','','','','Age','','','');" />
			<td align="center" colspan="3">&nbsp;</td>
			<td align="right">
				<font size="1">Add</font><br>
				<input  type="radio" name="totallyrad" value="0" checked="checked" />
			</td>
			<td width="2%" colspan="1"><font size="1"></font></td>
			<td width="2%" colspan="1"><font size="1"></font></td>
		</tr>
	</table>

	<?php
	echo "<div style=\"clear:both; border:0em outset; float:left; margin:0 0 0 0; width:69.8em; \">";
	include('modules/GEDFact_assistant/_CENS/census_5_input.php');
	echo "</div>";
?> 
	</div>


<script language="JavaScript" type="text/javascript">
 window.onLoad = initDynamicOptionLists();
</script>

