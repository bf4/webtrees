<?php
// Classes for Gedcom Date/Calendar functionality.
//
// Definitions for the French Republican calendar
//
// NOTE: Since different calendars start their days at different times, (civil
// midnight, solar midnight, sunset, sunrise, etc.), we convert on the basis of
// midday.
//
// webtrees: Web based Family History software
// Copyright (C) 2011 webtrees development team.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// @author Greg Roach
// @version $Id$

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class WT_Date_French extends WT_Date_Calendar {
	static function CALENDAR_ESCAPE() {
		return '@#DFRENCH R@';
	}

	static function MONTH_TO_NUM($m) {
		static $months=array(''=>0, 'vend'=>1, 'brum'=>2, 'frim'=>3, 'nivo'=>4, 'pluv'=>5, 'vent'=>6, 'germ'=>7, 'flor'=>8, 'prai'=>9, 'mess'=>10, 'ther'=>11, 'fruc'=>12, 'comp'=>13);
		if (isset($months[$m])) {
			return $months[$m];
		} else {
			return null;
		}
	}
	static function NUM_TO_MONTH_NOMINATIVE($n, $leap_year) {
		switch ($n) {
		case 1:  return i18n::translate_c('NOMINATIVE', 'Vendémiaire');
		case 2:  return i18n::translate_c('NOMINATIVE', 'Brumaire');
		case 3:  return i18n::translate_c('NOMINATIVE', 'Frimaire');
		case 4:  return i18n::translate_c('NOMINATIVE', 'Nivôse');
		case 5:  return i18n::translate_c('NOMINATIVE', 'Pluviôse');
		case 6:  return i18n::translate_c('NOMINATIVE', 'Ventôse');
		case 7:  return i18n::translate_c('NOMINATIVE', 'Germinal');
		case 8:  return i18n::translate_c('NOMINATIVE', 'Floréal');
		case 9:  return i18n::translate_c('NOMINATIVE', 'Prairial');
		case 10: return i18n::translate_c('NOMINATIVE', 'Messidor');
		case 11: return i18n::translate_c('NOMINATIVE', 'Thermidor');
		case 12: return i18n::translate_c('NOMINATIVE', 'Fructidor');
		case 13: return i18n::translate_c('NOMINATIVE', 'jours complémentaires');
		}
	}
	static function NUM_TO_MONTH_GENITIVE($n, $leap_year) {
		switch ($n) {
		case 1:  return i18n::translate_c('GENITIVE', 'Vendémiaire');
		case 2:  return i18n::translate_c('GENITIVE', 'Brumaire');
		case 3:  return i18n::translate_c('GENITIVE', 'Frimaire');
		case 4:  return i18n::translate_c('GENITIVE', 'Nivôse');
		case 5:  return i18n::translate_c('GENITIVE', 'Pluviôse');
		case 6:  return i18n::translate_c('GENITIVE', 'Ventôse');
		case 7:  return i18n::translate_c('GENITIVE', 'Germinal');
		case 8:  return i18n::translate_c('GENITIVE', 'Floréal');
		case 9:  return i18n::translate_c('GENITIVE', 'Prairial');
		case 10: return i18n::translate_c('GENITIVE', 'Messidor');
		case 11: return i18n::translate_c('GENITIVE', 'Thermidor');
		case 12: return i18n::translate_c('GENITIVE', 'Fructidor');
		case 13: return i18n::translate_c('GENITIVE', 'jours complémentaires');
		}
	}
	static function NUM_TO_MONTH_LOCATIVE($n, $leap_year) {
		switch ($n) {
		case 1:  return i18n::translate_c('LOCATIVE', 'Vendémiaire');
		case 2:  return i18n::translate_c('LOCATIVE', 'Brumaire');
		case 3:  return i18n::translate_c('LOCATIVE', 'Frimaire');
		case 4:  return i18n::translate_c('LOCATIVE', 'Nivôse');
		case 5:  return i18n::translate_c('LOCATIVE', 'Pluviôse');
		case 6:  return i18n::translate_c('LOCATIVE', 'Ventôse');
		case 7:  return i18n::translate_c('LOCATIVE', 'Germinal');
		case 8:  return i18n::translate_c('LOCATIVE', 'Floréal');
		case 9:  return i18n::translate_c('LOCATIVE', 'Prairial');
		case 10: return i18n::translate_c('LOCATIVE', 'Messidor');
		case 11: return i18n::translate_c('LOCATIVE', 'Thermidor');
		case 12: return i18n::translate_c('LOCATIVE', 'Fructidor');
		case 13: return i18n::translate_c('LOCATIVE', 'jours complémentaires');
		}
	}
	static function NUM_TO_MONTH_INSTRUMENTAL($n, $leap_year) {
		switch ($n) {
		case 1:  return i18n::translate_c('INSTRUMENTAL', 'Vendémiaire');
		case 2:  return i18n::translate_c('INSTRUMENTAL', 'Brumaire');
		case 3:  return i18n::translate_c('INSTRUMENTAL', 'Frimaire');
		case 4:  return i18n::translate_c('INSTRUMENTAL', 'Nivôse');
		case 5:  return i18n::translate_c('INSTRUMENTAL', 'Pluviôse');
		case 6:  return i18n::translate_c('INSTRUMENTAL', 'Ventôse');
		case 7:  return i18n::translate_c('INSTRUMENTAL', 'Germinal');
		case 8:  return i18n::translate_c('INSTRUMENTAL', 'Floréal');
		case 9:  return i18n::translate_c('INSTRUMENTAL', 'Prairial');
		case 10: return i18n::translate_c('INSTRUMENTAL', 'Messidor');
		case 11: return i18n::translate_c('INSTRUMENTAL', 'Thermidor');
		case 12: return i18n::translate_c('INSTRUMENTAL', 'Fructidor');
		case 13: return i18n::translate_c('INSTRUMENTAL', 'jours complémentaires');
		}
	}
	static function NUM_TO_SHORT_MONTH($n, $leap_year) {
		// TODO: Do these have short names?
		return self::NUM_TO_MONTH_NOMINATIVE($n, $leap_year);
	}
	static function NUM_TO_GEDCOM_MONTH($n, $leap_year) {
		switch ($n) {
		case 1:  return 'VEND';
		case 2:  return 'BRUM';
		case 3:  return 'FRIM';
		case 4:  return 'NIVO';
		case 5:  return 'PLUV';
		case 6:  return 'VENT';
		case 7:  return 'GERM';
		case 8:  return 'FLOR';
		case 9:  return 'PRAI';
		case 10: return 'MESS';
		case 11: return 'THER';
		case 12: return 'FRUC';
		case 13: return 'COMP';
		}
	}
	static function NUM_MONTHS() {
		return 13;
	}
	static function LONG_DAYS_OF_WEEK($n) {
		switch ($n) {
		case 0: return i18n::translate('Primidi');
		case 1: return i18n::translate('Duodi');
		case 2: return i18n::translate('Tridi');
		case 3: return i18n::translate('Quartidi');
		case 4: return i18n::translate('Quintidi');
		case 5: return i18n::translate('Sextidi');
		case 6: return i18n::translate('Septidi');
		case 7: return i18n::translate('Octidi');
		case 8: return i18n::translate('Nonidi');
		case 9: return i18n::translate('Decidi');
		}
	}
	static function SHORT_DAYS_OF_WEEK($n) {
		// TODO: Do these have short names?
		return self::LONG_DAYS_OF_WEEK($n);
	}
	static function NUM_DAYS_OF_WEEK() {
		return 10; // A "metric" week of 10 unimaginatively named days.
	}
	static function CAL_START_JD() {
		return 2375840; // 22 SEP 1792 = 01 VEND 0001
	}
	static function CAL_END_JD() {
		return 2380687; // 31 DEC 1805 = 10 NIVO 0014
	}

	// Leap years were based on astronomical observations.  Only years 3, 7 and 11
	// were ever observed.  Moves to a gregorian-like (fixed) system were proposed
	// but never implemented.  These functions are valid over the range years 1-14.
	function IsLeapYear() {
		return $this->y%4==3;
	}

	static function YMDtoJD($y, $m, $d) {
		return 2375444+$d+$m*30+$y*365+floor($y/4);
	}

	static function JDtoYMD($j) {
		$y=floor(($j-2375109)*4/1461)-1;
		$m=floor(($j-2375475-$y*365-floor($y/4))/30)+1;
		$d=$j-2375444-$m*30-$y*365-floor($y/4);
		return array($y, $m, $d);
	}

	// Years were written using roman numerals
	function FormatLongYear() {
		return $this->NumToRoman($this->y);
	}
}