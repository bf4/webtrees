<?php
/**
 * Batch Update plugin for phpGedView - remove duplicate links in records
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2008 Greg Roach.  All rights reserved.
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
 * @subpackage Module
 * $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class plugin extends base_plugin {
	// Default is to operate on INDI records
	function getRecordTypesToUpdate() {
		return array('INDI', 'FAM', 'SOUR', 'REPO', 'NOTE', 'OBJE');
	}

	static function doesRecordNeedUpdate($xref, $gedrec) {
		return
			preg_match('/(\n1.*@.+@.*(?:(?:\n[2-9].*)*))(?:\n1.*(?:\n[2-9].*)*)*\1/', $gedrec) ||
			preg_match('/(\n2.*@.+@.*(?:(?:\n[3-9].*)*))(?:\n2.*(?:\n[3-9].*)*)*\1/', $gedrec) ||
			preg_match('/(\n3.*@.+@.*(?:(?:\n[4-9].*)*))(?:\n3.*(?:\n[4-9].*)*)*\1/', $gedrec);
	}

	static function updateRecord($xref, $gedrec) {
		return preg_replace(
			array(
				'/(\n1.*@.+@.*(?:(?:\n[2-9].*)*))((?:\n1.*(?:\n[2-9].*)*)*\1)/',
				'/(\n2.*@.+@.*(?:(?:\n[3-9].*)*))((?:\n2.*(?:\n[3-9].*)*)*\1)/',
				'/(\n3.*@.+@.*(?:(?:\n[4-9].*)*))((?:\n3.*(?:\n[4-9].*)*)*\1)/'
			),
			'$2',
			$gedrec
		);
	}
}
