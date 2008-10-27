<?php
/**
 * Batch Update plugin for phpGedView - add missing 2 _MARNM records
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
	var $surname=null; // User option: add or replace husband's surname

	function doesRecordNeedUpdate($xref, $gedrec) {
		return preg_match('/^1 SEX F/m', $gedrec) && preg_match('/^1 NAME /m', $gedrec) && $this->_surnames_to_add($xref, $gedrec);
	}

	function updateRecord($xref, $gedrec) {
		preg_match('/^1 NAME (.*)/m', $gedrec, $match);
		$wife_name=$match[1];
		$married_names=array();
		foreach ($this->_surnames_to_add($xref, $gedrec) as $surname) {
			switch ($this->surname) {
			case 'add':
				$married_names[]="\n2 _MARNM ".str_replace('/', '', $wife_name).' /'.$surname.'/';
				break;
			case 'replace':
				$married_names[]="\n2 _MARNM ".preg_replace('!/.*/!', '/'.$surname.'/', $wife_name);
				break;
			}
		}
		return preg_replace('/(^1 NAME .*([\r\n]+[2-9].*)*)/m', '\\1'.implode('', $married_names), $gedrec, 1);
	}

	function _surnames_to_add($xref, $gedrec) {
		$wife_surnames=$this->_surnames($xref, $gedrec);
		$husb_surnames=array();
		$missing_surnames=array();
		preg_match_all('/^1 FAMS @(.+)@/m', $gedrec, $fmatch);
		foreach ($fmatch[1] as $famid) {
			$famrec=batch_update::getLatestRecord($famid, 'FAM');
			if (preg_match('/^1 '.PGV_EVENTS_MARR.'/m', $famrec) && preg_match('/^1 HUSB @(.+)@/m', $famrec, $hmatch)) {
				$husbrec=batch_update::getLatestRecord($hmatch[1], 'INDI');
				$husb_surnames=array_unique(array_merge($husb_surnames, $this->_surnames($hmatch[1], $husbrec)));
			}
		}
		foreach ($husb_surnames as $husb_surname) {
			if (!in_array($husb_surname, $wife_surnames)) {
				$missing_surnames[]=$husb_surname;
			}
		}
		return $missing_surnames;
	}

	function _surnames($xref, $gedrec) {
		if (preg_match_all('/^(?:1 NAME|2 _MARNM) .*\/(.+)\//m', $gedrec, $match)) {
			return $match[1];
		} else {
			return array();
		}
	}

	// Add an option for different surname styles
	function getOptions() {
		parent::getOptions();
		$this->surname=safe_GET('surname', array('add', 'replace'), 'replace');
	}

	function getOptionsForm() {
		global $pgv_lang;

		return
			parent::getOptionsForm().
			'<tr valign="top"><td class="list_label width20">'.$pgv_lang['bu_surname_option'].'</td>'.
			'<td class="optionbox"><select name="surname" onchange="reset_reload();"><option value="replace"'.
			($this->surname=='replace' ? ' selected="selected"' : '').
			'">'.$pgv_lang['bu_surname_replace'].'</option><option value="add"'.
			($this->surname=='add' ? ' selected="selected"' : '').
			'">'.$pgv_lang['bu_surname_add'].'</option></select></td></tr>'
		 	;
	}
}
