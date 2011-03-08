<?php
// Functions and logic for GEDCOM "STAT" codes
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
// $Id$

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class WT_Gedcom_Code_Stat {

	// Get a list of status codes that can be used on a given LDS tag
	public static function statusCodes($tag) {
		switch ($tag) {
		case 'BAPL':
		case 'CONL':
			// LDS_BAPTISM_DATE_STATUS
			return array('CHILD', 'COMPLETED', 'EXCLUDED', 'INFANT', 'PRE-1970', 'STILLBORN', 'SUBMITTED', 'UNCLEARED');
		case 'ENDL':
			// LDS_ENDOWMENT_DATE_STATUS
			return array('CHILD', 'COMPLETED', 'EXCLUDED', 'INFANT', 'PRE-1970', 'STILLBORN', 'SUBMITTED', 'UNCLEARED');
		case 'SLGC':
			// LDS_CHILD_SEALING_DATE_STATUS
			return array('BIC', 'COMPLETED', 'EXCLUDED', 'PRE-1970', 'STILLBORN', 'SUBMITTED', 'UNCLEARED');
		case 'SLGS':
			// LDS_SPOUSE_SEALING_DATE_STATUS
			return array('CANCELED', 'COMPLETED', 'DNS', 'DNS/CAN', 'EXCLUDED', 'PRE-1970', 'SUBMITTED', 'UNCLEARED');
		default:
			trigger_error('Internal error - bad argument to WT_Gedcom_Code_Stat::statusCodes("'.$tag.'")', E_USER_ERROR);
		}
	}

	// Get the localized name for a status code
	public static function statusName($status_code) {
		switch ($status_code) {
		case 'BIC':
			return /* LDS sealing statuses; http://en.wikipedia.org/wiki/Sealing_(Latter_Day_Saints) */
				WT_I18N::translate('Born in the covenant');
		case 'CANCELED':
			return WT_I18N::translate('Sealing cancelled (divorce)');
		case 'CHILD':
			return WT_I18N::translate('Died as a child: exempt');
		case 'CLEARED':
			// This status appears in PGV, but not in the GEDCOM 5.5.1 specification.
			return WT_I18N::translate('Cleared but not yet completed');
		case 'COMPLETED':
			return WT_I18N::translate('Completed; date unknown');
		case 'DNS':
			return WT_I18N::translate('Do Not Seal: unauthorized');
		case 'DNS/CAN':
			return WT_I18N::translate('Do Not Seal, previous sealing cancelled');
		case 'EXCLUDED':
			return WT_I18N::translate('Excluded from this submission');
		case 'INFANT':
			return WT_I18N::translate('Died as an infant: exempt');
		case 'PRE-1970':
			return WT_I18N::translate('Completed before 1970; date not available');
		case 'STILLBORN':
			return WT_I18N::translate('Stillborn: exempt');
		case 'SUBMITTED':
			return WT_I18N::translate('Submitted but not yet cleared');
		case 'UNCLEARED':
			return WT_I18N::translate('Uncleared: insufficient data');
		default:
			return $status_code;
		}
	}

	// A sorted list of all status names, for a given GEDCOM tag
	public static function statusNames($tag) {
		$status_names=array();
		foreach (self::statusCodes($tag) as $status_code) {
			$status_names[$status_code]=self::statusName($status_code);
		}
		uasort($status_names, 'utf8_strcasecmp');
		return $status_names;
	}
}
