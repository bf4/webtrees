<?php
/**
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 Greg Roach
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
 * @version $Id:$
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
	static $NOMINATIVE_POLISH_MONTHS=NULL;
	static $GENITIVE_POLISH_MONTHS=NULL;
	static $INSTRUMENTAL_POLISH_MONTHS=NULL;
	static $LOCATIVE_POLISH_MONTHS=NULL;

	if (empty($NOMINATIVE_POLISH_MONTHS)) {
		$NOMINATIVE_POLISH_MONTHS=array($pgv_lang['jan'], $pgv_lang['feb'], $pgv_lang['mar'], $pgv_lang['apr'], $pgv_lang['may'], $pgv_lang['jun'], $pgv_lang['jul'], $pgv_lang['aug'], $pgv_lang['sep'], $pgv_lang['oct'], $pgv_lang['nov'], $pgv_lang['dec']);
		$GENITIVE_POLISH_MONTHS=array('stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia');
		$INSTRUMENTAL_POLISH_MONTHS=array('styczniem', 'lutym', 'marcem', 'kwietniem', 'majem', 'czerwcem', 'lipcem', 'sierpniem', 'wrześniem', 'październikiem', 'listopadem', 'grudniem');
		$LOCATIVE_POLISH_MONTHS=array('styczniu', 'lutym', 'marcu', 'kwietniu', 'maju', 'czerwcu', 'lipcu', 'sierpniu', 'wrześniu', 'październiku', 'listopadzie', 'grudniu');
	}

	// Dates with a number are genitive
	foreach ($NOMINATIVE_POLISH_MONTHS as $key=>$value) {
		$d1=preg_replace("/(\d+ ){$value}/", "$1 ".$GENITIVE_POLISH_MONTHS[$key], $d1);
		$d2=preg_replace("/(\d+ ){$value}/", "$1 ".$GENITIVE_POLISH_MONTHS[$key], $d2);
	}
	// Dates without a number depend on the qualifier
	switch ($q1) {
		case 'est': case 'int': case 'cal': case '':
			foreach ($NOMINATIVE_POLISH_MONTHS as $key=>$value) {
				$date['month']=$NOMINATIVE_POLISH_MONTHS[$date['mon']];
			}
			break;
		case 'from': case 'to': case 'abt': case 'apx': case 'cir':
			foreach ($NOMINATIVE_POLISH_MONTHS as $key=>$value) {
				$date['month']=$GENITIVE_POLISH_MONTHS[$date['mon']];
			}
			break;
		case 'bet': case 'bef':
			foreach ($NOMINATIVE_POLISH_MONTHS as $key=>$value) {
				$date['month']=$INSTRUMENTAL_POLISH_MONTHS[$date['mon']];
			}
			break;
		case 'aft':
			foreach ($NOMINATIVE_POLISH_MONTHS as $key=>$value) {
				$date['month']=$LOCATIVE_POLISH_MONTHS[$date['mon']];
			}
	 		break;
		}
	switch ($q2) {
		case 'to':
			foreach ($NOMINATIVE_POLISH_MONTHS as $key=>$value) {
				$date['month']=$GENITIVE_POLISH_MONTHS[$date['mon']];
			}
			break;
		case 'and':
			foreach ($NOMINATIVE_POLISH_MONTHS as $key=>$value) {
				$date['month']=$INSTRUMENTAL_POLISH_MONTHS[$date['mon']];
			}
			break;
		}
	}

	if ($date['day']!=0) {
		$date['month']=$GENITIVE_POLISH_MONTHS[$date['mon']];
	} else {
		switch ($date['ext']) {
		case 'EST': case 'INT': case 'CAL': case '':
			$date['month']=$NOMINATIVE_POLISH_MONTHS[$date['mon']];
			break;
		case 'FROM': case 'TO': case 'ABT': case 'APX': case 'CIR':
			$date['month']=$GENITIVE_POLISH_MONTHS[$date['mon']];
			break;
		case 'BET': case 'AND': case 'BEF':
			$date['month']=$INSTRUMENTAL_POLISH_MONTHS[$date['mon']];
			break;
		case 'AFT':
			$date['month']=$LOCATIVE_POLISH_MONTHS[$date['mon']];
	 		break;
		}
	}

	if (isset($pgv_lang[$q1]))
		$q1=$pgv_lang[$q1];
	if (isset($pgv_lang[$q2]))
		$q2=$pgv_lang[$q2];
}
?>
