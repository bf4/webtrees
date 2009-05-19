<?php
/**
 * Media Link Assistant Control module for phpGedView
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


// Test to see if Base pid is filled in ============================
if ($pid=="") {
	echo "<br /><br />";
	echo "<b><font color=\"red\">YOU MUST enter a Base individual ID to be able to \"ADD\" Individual Links</font></b>";
	echo "<br /><br />";
}else{

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

	<table class="facts_table" width=300 border=0 >

		<tr colspan="2">
			<td rowspan="1" align="right" valign="top" width=400>
				<?php 
				//-- Search  and Add Family Members Area ========================================= 
				include('modules/GEDFact_assistant/MEDIA/media_3_search_add.php'); ?>
			</td>
		</tr>
		<tr>
			<td colspan=2 align="left" valign="top" width="100%">
				<?php
				//-- Help/Save Area ==================================================
				include('modules/GEDFact_assistant/MEDIA/media_6_help_save.php');
				include('modules/GEDFact_assistant/MEDIA/media_7_parse_addLinksTbl.php');
				?>
			</td>
		</tr>
		
	</table>

<?php
} // End Test for Base pid 
?>


