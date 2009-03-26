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

global $summary, $theme_name, $pgv_lang, $factarray;
 
$pid = safe_get('pid');
// echo $pid;
$year = "1901";
$censevent  = new Event("1 CENS\n2 DATE 03 MAR".$year."");
$censdate   = $censevent->getDate();
$censyear   = $censdate->date1->y;
$ctry       = "UK";
$married    = GedcomDate::Compare($censdate, $marrdate);

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

// Various Debugs =========================================
// var_dump($person->getFullName()); 

/*
$fred = ($person->getAllBirthPlaces());
$fredrev = explode(", ", $fred[0]);
$fredrev = array_reverse($fredrev);
$fredrev = implode(", ", $fredrev);
echo $fred[0];
echo "<br />";
echo $fredrev;
*/

//=========================================================



echo "<table border=0><tr>";
	echo "<td width=\"10%\" wrap=\"nowrap\">";
		echo "<center><font size=\"2\"><b>".$censyear."&nbsp;".$ctry."&nbsp;".$factarray["CENS"]."</b></font></center>";
	echo "</td>";
	echo "<td width=\"25%\" wrap=\"nowrap\">";
		echo "<center><font size=\"2\"><b>&nbsp;&nbsp; </font><font size=\"2\">";
		echo " Head of Family &nbsp;&nbsp;</font><font size=\"2\">:";
		echo " &nbsp;&nbsp;" . $wholename . "&nbsp; (" . $pid . ")";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;</font>";
	echo "</td>";
	echo "<td width=\"35%\">";
		if ($summary) {
			echo '<table><tr><td width="10"><center><br /></td><td valign="top" colspan="', $maxcols-$col, '"><font size="1">', $summary, '</font></center></td></tr></table>';
		}
	echo "</td>";
echo "</tr></table>";
?>

<table class="facts_table" width="100%" border=0>

	<tr>
		<td valign="top" width="60%">
			<?php 
			//-- Census & Source Information Area ============================================= 
			include('modules/GEDFact_assistant/CENS/census_2_source_input.php');
			?>
		</td>
		
		<td rowspan="2" valign="top" width ="220">
			<?php 
			//-- Search  and Add Family Members Area ========================================= 
			include('modules/GEDFact_assistant/CENS/census_3_search_add.php'); ?>
		</td>
	</tr>
	<tr>
		<td align="left" valign="bottom" width="80%">
			<?php
			//-- Proposed Census Text Area ==================================================
			include('modules/GEDFact_assistant/CENS/census_4_text.php');
			?>
		</td>
	</tr>
	<tr>
		<td colspan=2 align="center" width="100%">
			<?php
			//-- Census Text Input Area ===========================================================
			?>
			<br />
			<table width="95%" border="0">
				<tr>
					<td colspan="2" align="center">
						<table width="100%" border='3' cellspacing="1" >
							<tr>
								<td colspan="12" id="5678" class="option_box" style="border: 0px solid transparent;">
									<?php
									include('modules/GEDFact_assistant/CENS/census_5_input.php');
									?> 
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

</table>

