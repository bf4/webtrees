<?php
/**
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
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

////////////////////////////////////////////////////////////////////////////////
// Localise a date.
////////////////////////////////////////////////////////////////////////////////
function date_localisation_pl(&$q1, &$d1, &$q2, &$d2, &$q3) {
	global $pgv_lang;
	static $NOMINATIVE_MONTHS=NULL;
	static $GENITIVE_MONTHS=NULL;
	static $INSTRUMENTAL_MONTHS=NULL;
	static $LOCATIVE_MONTHS=NULL;

	if (empty($NOMINATIVE_MONTHS)) {
		$NOMINATIVE_MONTHS=array($pgv_lang['jan'], $pgv_lang['feb'], $pgv_lang['mar'], $pgv_lang['apr'], $pgv_lang['may'], $pgv_lang['jun'], $pgv_lang['jul'], $pgv_lang['aug'], $pgv_lang['sep'], $pgv_lang['oct'], $pgv_lang['nov'], $pgv_lang['dec']);
		$GENITIVE_MONTHS=array('stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia');
		$INSTRUMENTAL_MONTHS=array('styczniem', 'lutym', 'marcem', 'kwietniem', 'majem', 'czerwcem', 'lipcem', 'sierpniem', 'wrześniem', 'październikiem', 'listopadem', 'grudniem');
		$LOCATIVE_MONTHS=array('styczniu', 'lutym', 'marcu', 'kwietniu', 'maju', 'czerwcu', 'lipcu', 'sierpniu', 'wrześniu', 'październiku', 'listopadzie', 'grudniu');
	}

	// Months with a day number are genitive, regardless of qualifier
	for ($i=0; $i<12; ++$i) {
		$d1=preg_replace("/(\d+ ){$NOMINATIVE_MONTHS[$i]}/", "$1{$GENITIVE_MONTHS[$i]}", $d1);
		$d2=preg_replace("/(\d+ ){$NOMINATIVE_MONTHS[$i]}/", "$1{$GENITIVE_MONTHS[$i]}", $d2);
	}

	// Months without a day number (i.e. month at start) depend on the qualifier
	switch ($q1) {
	case 'from': case 'to': case 'abt': case 'apx': case 'cir':
		for ($i=0; $i<12; ++$i)
			$d1=preg_replace("/^{$NOMINATIVE_MONTHS[$i]}/", $GENITIVE_MONTHS[$i], $d1);
		break;
	case 'bet': case 'bef':
		for ($i=0; $i<12; ++$i)
			$d1=preg_replace("/^{$NOMINATIVE_MONTHS[$i]}/", $INSTRUMENTAL_MONTHS[$i], $d1);
		break;
	case 'aft':
		for ($i=0; $i<12; ++$i)
			$d1=preg_replace("/^{$NOMINATIVE_MONTHS[$i]}/", $LOCATIVE_MONTHS[$i], $d1);
	 	break;
	}
	switch ($q2) {
	case 'to':
		for ($i=0; $i<12; ++$i)
			$d2=preg_replace("/^{$NOMINATIVE_MONTHS[$i]}/", $GENITIVE_MONTHS[$i], $d2);
		break;
	case 'and':
		for ($i=0; $i<12; ++$i)
			$d2=preg_replace("/^{$NOMINATIVE_MONTHS[$i]}/", $INSTRUMENTAL_MONTHS[$i], $d2);
		break;
	}

	// The qualifiers are simple translations
	if (isset($pgv_lang[$q1]))
		$q1=$pgv_lang[$q1];
	if (isset($pgv_lang[$q2]))
		$q2=$pgv_lang[$q2];
}
////////////////////////////////////////////////////////////////////////////////
// Localise an age.
////////////////////////////////////////////////////////////////////////////////
function age_localisation_pl(&$agestring, &$show_years) {
	global $pgv_lang;
	
	// Only suppress years if there are no months/days
	if (preg_match('/\d[md]/i', $agestring)) {
		$show_years=true;
	}
	$agestring=preg_replace(
		array(
			'/\bchi(ld)?\b/i',
			'/\binf(ant)?\b/i',
			'/\bsti(llborn)?\b/i',
			'/\b1y/i',
			'/\b2y/i','/\b3y/i','/\b4y/i',
			'/\b22y/i','/\b23y/i','/\b24y/i',
			'/\b32y/i','/\b33y/i','/\b34y/i',
			'/\b42y/i','/\b43y/i','/\b44y/i',
			'/\b52y/i','/\b53y/i','/\b54y/i',
			'/\b62y/i','/\b63y/i','/\b64y/i',
			'/\b72y/i','/\b73y/i','/\b74y/i',
			'/\b82y/i','/\b83y/i','/\b84y/i',
			'/\b92y/i','/\b93y/i','/\b94y/i',
			'/\b102y/i','/\b103y/i','/\b104y/i',
			'/\b122y/i','/\b123y/i','/\b124y/i',
			'/\b132y/i','/\b133y/i','/\b134y/i',
			'/(\d+)y/i',
			'/\b1m/i',
			'/\b2m/i','/\b3m/i','/\b4m/i',
			'/\b22m/i','/\b23m/i','/\b24m/i',
			'/(\d+)m/i',
			'/\b1d/i',
			'/(\d+)d/i'
		),
		array(
			$pgv_lang['child'],
			$pgv_lang['infant'],  
	 		$pgv_lang['stillborn'], 
			$show_years ? '1 '.$pgv_lang['year1'] : '1',
			$show_years ? '2 '."lata" : '2', $show_years ? '3 '."lata" : '3', $show_years ? '4 '."lata" : '4',
			$show_years ? '22 '."lata" : '22', $show_years ? '23 '."lata" : '23', $show_years ? '24 '."lata" : '24',
			$show_years ? '32 '."lata" : '32', $show_years ? '33 '."lata" : '33', $show_years ? '34 '."lata" : '34',
			$show_years ? '42 '."lata" : '42', $show_years ? '43 '."lata" : '43', $show_years ? '44 '."lata" : '44',
			$show_years ? '52 '."lata" : '52', $show_years ? '53 '."lata" : '53', $show_years ? '54 '."lata" : '54',
			$show_years ? '62 '."lata" : '62', $show_years ? '63 '."lata" : '63', $show_years ? '64 '."lata" : '64',
			$show_years ? '72 '."lata" : '72', $show_years ? '73 '."lata" : '73', $show_years ? '74 '."lata" : '74',
			$show_years ? '82 '."lata" : '82', $show_years ? '83 '."lata" : '83', $show_years ? '84 '."lata" : '84',
			$show_years ? '92 '."lata" : '92', $show_years ? '93 '."lata" : '93', $show_years ? '94 '."lata" : '94',
			$show_years ? '102 '."lata" : '102', $show_years ? '103 '."lata" : '103', $show_years ? '104 '."lata" : '104',
			$show_years ? '122 '."lata" : '122', $show_years ? '123 '."lata" : '123', $show_years ? '124 '."lata" : '124',
			$show_years ? '132 '."lata" : '132', $show_years ? '133 '."lata" : '133', $show_years ? '134 '."lata" : '134',
			$show_years ? '$1 '.$pgv_lang['years'] : '$1',
			'1 '.$pgv_lang['month1'],
			'2 '."miesiące", '3 '."miesiące", '4 '."miesiące",
			'22 '."miesiące", '23 '."miesiące", '24 '."miesiące",
	 		'$1 '.$pgv_lang['months'],
			'1 '.$pgv_lang['day1'],  
			'$1 '.$pgv_lang['days']
		),
		$agestring
	);
}
////////////////////////////////////////////////////////////////////////////////
// Localise a date differences.
////////////////////////////////////////////////////////////////////////////////
function date_diff_localisation_pl(&$label, &$gap) {
	global $pgv_lang;

	$yrs = round($gap/12);
	if ($gap == 12 || $gap == -12) $label .= $yrs." ".$pgv_lang["year1"]; // 1 rok
	else if (($yrs > 1 && $yrs < 5) || ($yrs < -1 && $yrs < -5)) $label .= $yrs." lata"; // 2-4 lata
	else if (($yrs > 21 || $yrs < -21) && substr($yrs, -1, 1) > 1 && substr($yrs, -1, 1) < 5 && substr($yrs, -2, 1) != 1) $label .= $yrs." lata";
	else if ($gap > 20 or $gap < -20) $label .= $yrs." ".$pgv_lang["years"]; // x lat
	else if ($gap == 1 || $gap == -1) $label .= $gap." ".$pgv_lang["month1"]; // 1 miesiąc
	else if (($gap > 1 && $gap < 5) || ($gap < -1 && $gap > -5)) $label .= $gap." miesiące"; // 2-4 miesiące
	else if ($gap != 0) $label .= $gap." ".$pgv_lang["months"]; // x miesięcy
}
////////////////////////////////////////////////////////////////////////////////
// Localise a number of people.
////////////////////////////////////////////////////////////////////////////////
function num_people_localisation_pl(&$count) {
	global $pgv_lang;

	if ($count == 1)
		print "<br /><b>".$count." ".$pgv_lang["individual"]."</b>";
	else if ($count > 1 && $count < 5)
		print "<br /><b>".$count." ".$pgv_lang["individuals"]."</b>";
	else if ($count > 21 && substr($count, -1, 1) > 1 && substr($count, -1, 1) < 5 && substr($count, -2, 1) != 1)
		print "<br /><b>".$count." ".$pgv_lang["individuals"]."</b>";
	else
		print "<br /><b>".$count." ".$pgv_lang["stat_individuals"]."</b>";
}
?>
