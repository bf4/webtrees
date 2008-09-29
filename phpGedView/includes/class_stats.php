<?php
/**
 * GEDCOM Statistics Class
 *
 * This class provides a quick & easy method for accessing statistics
 * about the GEDCOM.
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
 * @version $Id$
 * @author Patrick Kellum
 * @package PhpGedView
 * @subpackage Lists
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_CLASS_STATS_PHP', '');

require_once 'includes/functions_print_lists.php';

// Methods not allowed to be used in a statistic
define('STATS_NOT_ALLOWED', 'stats,getAllTags,getTags');

class stats {
	var $_gedcom;
	var $_gedcom_url;
	var $_ged_id;
	var $_server_url; // Absolute URL for generating external links.  e.g. in RSS feeds
	static $_not_allowed = false;
	static $_media_types = array('audio', 'book', 'card', 'certificate', 'document', 'electronic', 'magazine', 'manuscript', 'map', 'fiche', 'film', 'newspaper', 'painting', 'photo', 'tombstone', 'video', 'other');
	// For Google charts simple encoding
	static $_encoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	static $_xencoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-.';

	function stats($gedcom, $server_url='') {
		self::$_not_allowed = explode(',', STATS_NOT_ALLOWED);
		$this->_setGedcom($gedcom);
		$this->_server_url = $server_url;
	}

	function _setGedcom($gedcom) {
		$this->_gedcom = $gedcom;
		$this->_ged_id = PrintReady(get_id_from_gedcom($gedcom));
		$this->_gedcom_url = encode_url($gedcom);
	}

	/**
	 * Return an array of all supported tags and an example of its output.
	 */
	function getAllTags() {
		$examples = array();
		$methods = get_class_methods('stats');
		$c = count($methods);
		for ($i=0; $i < $c; $i++) {
			if ($methods[$i][0] == '_' || in_array($methods[$i], self::$_not_allowed)) {
				continue;
			}
			$examples[$methods[$i]] = $this->$methods[$i]();
			if (stristr($methods[$i], 'percentage') || $methods[$i]=='averageChildren') {
				$examples[$methods[$i]] .='%';
			}
			if (stristr($methods[$i], 'highlight')) {
				$examples[$methods[$i]]=str_replace(array(' align="left"', ' align="right"'), '', $examples[$methods[$i]]);
			}
		}
		ksort($examples);
		return $examples;
	}

	/**
	 * Return a string of all supported tags and an example of its output in table row form.
	 */
	function getAllTagsTable() {
		global $TEXT_DIRECTION;
		$examples = array();
		$methods = get_class_methods($this);
		$c = count($methods);
		for ($i=0; $i < $c; $i++) {
			if (in_array($methods[$i], self::$_not_allowed) || $methods[$i][0] == '_' || $methods[$i] == 'getAllTagsTable' || $methods[$i] == 'getAllTagsText') {
				continue;
			} // Include this method name to prevent bad stuff happening
			$examples[$methods[$i]] = $this->$methods[$i]();
			if (stristr($methods[$i], 'percentage') || $methods[$i]=='averageChildren') {
				$examples[$methods[$i]] .='%';
			}
			if (stristr($methods[$i], 'highlight')) {
				$examples[$methods[$i]]=str_replace(array(' align="left"', ' align="right"'), '', $examples[$methods[$i]]);
			}
		}
		$out = '';
		if ($TEXT_DIRECTION=='ltr') {
			$alignVar = 'right';
			$alignRes = 'left';
		} else {
			$alignVar = 'left';
			$alignRes = 'right';
		}
		foreach ($examples as $tag=>$v) {
			$out .= "\t<tr class=\"vevent\">"
				."<td class=\"list_value_wrap\" align=\"{$alignVar}\" valign=\"top\" style=\"padding:3px\">{$tag}</td>"
				."<td class=\"list_value_wrap\" align=\"{$alignRes}\" valign=\"top\">{$v}</td>"
				."</tr>\n"
			;
		}
		return $out;
	}

	/**
	 * Return a string of all supported tags in plain text.
	 */
	function getAllTagsText() {
		$examples=array();
		$methods=get_class_methods($this);
		$c=count($methods);
		for ($i=0; $i < $c; $i++) {
			if (in_array($methods[$i], self::$_not_allowed) || $methods[$i][0] == '_' || $methods[$i] == 'getAllTagsTable' || $methods[$i] == 'getAllTagsText') {continue;} // Include this method name to prevent bad stuff happining
			$examples[$methods[$i]] = $methods[$i];
		}
		$out = '';
		foreach ($examples as $tag=>$v) {
			$out .= "{$tag}<br />\n";
		}
		return $out;
	}

	/*
	 * Get tags and their parsed results.
	 */
	function getTags($text) {
		global $pgv_lang, $factarray;
		static $funcs;

		// Retrive all class methods
		isset($funcs) or $funcs = get_class_methods($this);

		// Extract all tags from the provided text
		$ct = preg_match_all("/#(.+)#/U", (string)$text, $match);
		$tags = $match[1];
		$c = count($tags);
		$new_tags = array(); // tag to replace
		$new_values = array(); // value to replace it with

		/*
		 * Parse block tags.
		 */
		for($i=0; $i < $c; $i++)
		{
			$full_tag = $tags[$i];
			// Added for new parameter support
			$params = explode(':', $tags[$i]);
			if (count($params) > 1)
			{
				$tags[$i] = array_shift($params);
			}
			else
			{
				$params = null;
			}

			// Skip non-tags and non-allowed tags
			if ($tags[$i][0] == '_' || in_array($tags[$i], self::$_not_allowed)) {continue;}

			// Generate the replacement value for the tag
			if (method_exists($this, $tags[$i]))
			{
				$new_tags[] = "#{$full_tag}#";
				$new_values[] = $this->$tags[$i]($params);
			}
			elseif ($tags[$i] == 'help')
			{
				// re-merge, just in case
				$new_tags[] = "#{$full_tag}#";
				$new_values[] = print_help_link(join(':', $params), 'qm', '', false, true);
			}
			/*
			 * Parse language variables.
			 */
			// pgv_lang - long
			elseif ($tags[$i] == 'lang')
			{
				// re-merge, just in case
				$params = join(':', $params);
				$new_tags[] = "#{$full_tag}#";
				$new_values[] = print_text($pgv_lang[$params], 0, 2);
			}
			// pgv_lang
			elseif (isset($pgv_lang[$tags[$i]]))
			{
				$new_tags[] = "#{$full_tag}#";
				$new_values[] = print_text($pgv_lang[$tags[$i]], 0, 2);
			}
			// factarray
			elseif (isset($factarray[$tags[$i]]))
			{
				$new_tags[] = "#{$full_tag}#";
				$new_values[] = $factarray[$tags[$i]];
			}
			// GLOBALS
			elseif (isset($GLOBALS[$tags[$i]]))
			{
				$new_tags[] = "#{$full_tag}#";
				$new_values[] = $GLOBALS[$tags[$i]];
			}
			// CONSTANTS
			elseif (substr($tags[$i], 0, 4) == 'PGV_' & defined($tags[$i]))
			{
				$new_tags[] = "#{$full_tag}#";
				$new_values[] = constant($tags[$i]);
			}
			// OLD GLOBALS THAT ARE NOW CONSTANTS
			elseif (defined("PGV_{$tags[$i]}"))
			{
				$new_tags[] = "#PGV_{$tags[$i]}#";
				$new_values[] = constant("PGV_{$tags[$i]}");
			}
		}
		unset($tags);
		return array($new_tags, $new_values);
	}

///////////////////////////////////////////////////////////////////////////////
// GEDCOM                                                                    //
///////////////////////////////////////////////////////////////////////////////

	function gedcomFilename() {return get_gedcom_from_id($this->_ged_id);}

	function gedcomID() {return $this->_ged_id;}

	function gedcomTitle() {return PrintReady(get_gedcom_setting($this->_ged_id, 'title'));}

	static function _gedcomHead()
	{
		static $cache=null;
		if (is_array($cache)) {
			return $cache;
		}
		$head=find_other_record('HEAD');
		$ct=preg_match("/1 SOUR (.*)/", $head, $match);
		if ($ct > 0) {
			$softrec=get_sub_record(1, '1 SOUR', $head);
			$tt=preg_match("/2 NAME (.*)/", $softrec, $tmatch);
			if ($tt > 0) {
				$title=trim($tmatch[1]);
			} else {
				$title=trim($match[1]);
			}
			if (!empty($title)) {
				$tt=preg_match("/2 VERS (.*)/", $softrec, $tmatch);
				if ($tt > 0) {
					$version=trim($tmatch[1]);
				} else {
					$version='';
				}
			} else {
				$version='';
			}
			$tt=preg_match("/1 SOUR (.*)/", $softrec, $tmatch);
			if ($tt > 0) {
				$source=trim($tmatch[1]);
			} else {
				$source=trim($match[1]);
			}
		}
		$cache=array($title, $version, $source);
		return $cache;
	}

	static function gedcomCreatedSoftware()
	{
		$head=self::_gedcomHead();
		return $head[0];
	}

	static function gedcomCreatedVersion()
	{
		$head=self::_gedcomHead();
		// fix broken version string in Family Tree Maker
		if (strstr($head[1], 'Family Tree Maker ')) {
			$p=strpos($head[1], '(') + 1;
			$p2=strpos($head[1], ')');
			$head[1]=substr($head[1], $p, ($p2 - $p));
		}
		// Fix EasyTree version
		if ($head[2]=='EasyTree') {
			$head[1]=substr($head[1], 1);
		}
		return $head[1];
	}

	static function gedcomDate()
	{
		$head=find_other_record('HEAD');
		if (preg_match("/1 DATE (.+)/", $head, $match)) {
			$date=new GedcomDate($match[1]);
			return $date->Display(false);
		}
		return '';
	}

	function gedcomUpdated()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT d_year, d_month, d_day FROM {$TBLPREFIX}dates WHERE d_file={$this->_ged_id} AND d_fact='CHAN' ORDER BY d_julianday2 DESC, d_type", 1);
		if (isset($rows[0])) {
			$date=new GedcomDate("{$rows[0]['d_day']} {$rows[0]['d_month']} {$rows[0]['d_year']}");
			return $date->Display(false);
		}
		return self::gedcomDate();
	}

	function gedcomHighlight()
	{
		$highlight=false;
		if (file_exists("images/gedcoms/{$this->_gedcom}.jpg"))
		{
			$highlight="images/gedcoms/{$this->_gedcom}.jpg";
		}
		elseif (file_exists("images/gedcoms/{$this->_gedcom}.png"))
		{
			$highlight="images/gedcoms/{$this->_gedcom}.png";
		}
		if (!$highlight) {return '';}
		$imgsize=findImageSize($highlight);
		return "<a href=\"".encode_url("{$this->_server_url}index.php?ctype=gedcom&ged={$this->_gedcom_url}")."\" style=\"border-style:none;\"><img src=\"{$highlight}\" {$imgsize[3]} style=\"border:none; padding:2px 6px 2px 2px;\" class=\"gedcom_highlight\" alt=\"\" /></a>";
	}

	function gedcomHighlightLeft()
	{
		$highlight=false;
		if (file_exists("images/gedcoms/{$this->_gedcom}.jpg")) {
			$highlight="images/gedcoms/{$this->_gedcom}.jpg";
		} else
			if (file_exists("images/gedcoms/{$this->_gedcom}.png")) {
				$highlight="images/gedcoms/{$this->_gedcom}.png";
			}
		if (!$highlight) {
			return '';
		}
		$imgsize=findImageSize($highlight);
		return "<a href=\"".encode_url("{$this->_server_url}index.php?ctype=gedcom&ged={$this->_gedcom_url}")."\" style=\"border-style:none;\"><img src=\"{$highlight}\" {$imgsize[3]} style=\"border:none; padding:2px 6px 2px 2px;\" align=\"left\" class=\"gedcom_highlight\" alt=\"\" /></a>";
	}

	function gedcomHighlightRight()
	{
		$highlight=false;
		if (file_exists("images/gedcoms/{$this->_gedcom}.jpg")) {
			$highlight="images/gedcoms/{$this->_gedcom}.jpg";
		} else
			if (file_exists("images/gedcoms/{$this->_gedcom}.png")) {
				$highlight="images/gedcoms/{$this->_gedcom}.png";
			}
		if (!$highlight) {
			return '';
		}
		$imgsize=findImageSize($highlight);
		return "<a href=\"".encode_url("{$this->_server_url}index.php?ctype=gedcom&ged={$this->_gedcom_url}")."\" style=\"border-style:none;\"><img src=\"{$highlight}\" {$imgsize[3]} style=\"border:none; padding:2px 6px 2px 2px;\" align=\"right\" class=\"gedcom_highlight\" alt=\"\" /></a>";
	}

///////////////////////////////////////////////////////////////////////////////
// Totals                                                                    //
///////////////////////////////////////////////////////////////////////////////

	function _getPercentage($total, $type)
	{
		$per=null;
		switch($type)
		{
			default:
			case 'all':
				$per=round(100 * $total / ($this->totalIndividuals() + $this->totalFamilies() + $this->totalSources() + $this->totalOtherRecords()), 2);
				break;
			case 'individual':
				$per=round(100 * $total / $this->totalIndividuals(), 2);
				break;
			case 'family':
				$per=round(100 * $total / $this->totalFamilies(), 2);
				break;
			case 'source':
				$per=round(100 * $total / $this->totalSources(), 2);
				break;
			case 'other':
				$per=round(100 * $total / $this->totalOtherRecords(), 2);
				break;
		}
		return $per;
	}

	function totalIndividuals()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT COUNT(i_file) AS tot FROM {$TBLPREFIX}individuals WHERE i_file=".$this->_ged_id);
		return $rows[0]['tot'];
	}

	function totalIndividualsPercentage()
	{
		return $this->_getPercentage($this->totalIndividuals(), 'all', 2);
	}

	function totalFamilies()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT COUNT(f_file) AS tot FROM {$TBLPREFIX}families WHERE f_file=".$this->_ged_id);
		return $rows[0]['tot'];
	}

	function totalFamiliesPercentage()
	{
		return $this->_getPercentage($this->totalFamilies(), 'all', 2);
	}

	function totalSources()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT COUNT(s_file) AS tot FROM {$TBLPREFIX}sources WHERE s_file=".$this->_ged_id);
		return $rows[0]['tot'];
	}

	function totalSourcesPercentage()
	{
		return $this->_getPercentage($this->totalSources(), 'all', 2);
	}

	function totalOtherRecords()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT COUNT(o_file) AS tot FROM {$TBLPREFIX}other WHERE o_file=".$this->_ged_id);
		return $rows[0]['tot'];
	}

	function totalOtherPercentage()
	{
		return $this->_getPercentage($this->totalOtherRecords(), 'all', 2);
	}

	function totalSurnames($params = null)
	{
		global $TBLPREFIX;
		if ($params !== null)
		{
			$dis = '';
			$surnames = array();
			foreach ($params as $surname) {$surnames[] = "i_surname='{$surname}'";}
			$surnames = join(' OR ', $surnames);
			$opt = " AND ({$surnames}) ";
		}
		else
		{
			$dis = ' DISTINCT ';
			$opt = '';
		}
		$rows = self::_runSQL("SELECT COUNT({$dis}i_surname) AS tot FROM {$TBLPREFIX}individuals WHERE i_file={$this->_ged_id}{$opt}");
		return $rows[0]['tot'];
	}

	function totalEvents($params = null)
	{
		global $TBLPREFIX;
		if ($params !== null)
		{
			$types = array();
			$no_types = array();
			foreach ($params as $type)
			{
				if ($type{0} == '!')
				{
					$type = substr($type, 1);
					$no_types[] = "'{$type}'";
				}
				else
				{
					$types[] = "'{$type}'";
				}
			}
			$opt = array();
			if (count($types))
			{
				$opt[] = ' AND d_fact IN ('.join(',', $types).') ';
			}
			if (count($no_types))
			{
				$opt[] = ' AND d_fact NOT IN ('.join(',', $no_types).') ';
			}
			$opt = join('', $opt);
		}
		else
		{
			$opt = '';
		}
		$rows = self::_runSQL("SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file={$this->_ged_id} AND d_fact!='CHAN' AND d_gid!='HEAD'{$opt}");
		if (!isset($rows[0])) {return '';}
		return $rows[0]['tot'];
	}

	function totalEventsBirth()
	{
		return $this->totalEvents(explode('|',PGV_EVENTS_BIRT));
	}

	function totalEventsDeath()
	{
		return $this->totalEvents(explode('|',PGV_EVENTS_DEAT));
	}

	function totalEventsMarriage()
	{
		return $this->totalEvents(explode('|',PGV_EVENTS_MARR));
	}

	function totalEventsOther()
	{
		$facts = array_merge(explode('|', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT));
		$no_facts = array();
		foreach ($facts as $fact)
		{
			$fact = '!'.str_replace('\'', '', $fact);
			$no_facts[] = $fact;
		}
		return $this->totalEvents($no_facts);
	}

	function totalSexMales()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file={$this->_ged_id} AND i_gedcom LIKE '%1 SEX M%'");
		if (!isset($rows[0])) {return '';}
		return $rows[0]['tot'];
	}
	function totalSexMalesPercentage()
	{
		global $TBLPREFIX;
		return $this->_getPercentage($this->totalSexMales(), 'individual');
	}

	function totalSexFemales()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file={$this->_ged_id} AND i_gedcom LIKE '%1 SEX F%'");
		if (!isset($rows[0])) {return '';}
		return $rows[0]['tot'];
	}

	function totalSexFemalesPercentage()
	{
		global $TBLPREFIX;
		return $this->_getPercentage($this->totalSexFemales(), 'individual');
	}

	function totalSexUnknown()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file={$this->_ged_id} AND (i_gedcom NOT LIKE '%1 SEX M%' AND i_gedcom NOT LIKE '%1 SEX F%')");
		if (!isset($rows[0])) {return '';}
		return $rows[0]['tot'];
	}

	function totalSexUnknownPercentage()
	{
		global $TBLPREFIX;
		return $this->_getPercentage($this->totalSexUnknown(), 'individual');
	}

	function chartSex($params=null)
	{
		global $pgv_lang, $TEXT_DIRECTION;
		if ($params === null) {$params = array();}
		if (isset($params[0]) && $params[0] != '') {$size = strtolower($params[0]);}else{$size = '450x125';}
		if (isset($params[1]) && $params[1] != '') {$color_female = strtolower($params[1]);}else{$color_female = 'ffd1dc';}
		if (isset($params[2]) && $params[2] != '') {$color_male = strtolower($params[2]);}else{$color_male = 'add8e6';}
		if (isset($params[3]) && $params[3] != '') {$color_unknown = strtolower($params[3]);}else{$color_unknown = '000000';}
		$sizes = explode('x', $size);
		$tot_f = $this->totalSexFemalesPercentage();
		$tot_m = $this->totalSexMalesPercentage();
		$tot_u = $this->totalSexUnknownPercentage();
		$chd = self::_array_to_extended_encoding(array($tot_u, $tot_f, $tot_m));
		$chl = reverseText($pgv_lang['stat_unknown']).'|'.reverseText($pgv_lang['stat_females']).'|'.reverseText($pgv_lang['stat_males']);
		return "<img src=\"".encode_url("http://chart.apis.google.com/chart?cht=p3&chd=e:{$chd}&chs={$size}&chco={$color_unknown},{$color_female},{$color_male}&chf=bg,s,ffffff00&chl={$chl}")."\" width=\"{$sizes[0]}\" height=\"{$sizes[1]}\" alt=\"\" />";
	}

	function totalLiving()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file={$this->_ged_id} AND i_isdead=0");
		if (!isset($rows[0])) {return '';}
		return $rows[0]['tot'];
	}

	function totalLivingPercentage()
	{
		global $TBLPREFIX;
		return $this->_getPercentage($this->totalLiving(), 'individual');
	}

	function totalDeceased()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file={$this->_ged_id} AND i_isdead=1");
		if (!isset($rows[0])) {return '';}
		return $rows[0]['tot'];
	}

	function totalDeceasedPercentage()
	{
		global $TBLPREFIX;
		return $this->_getPercentage($this->totalDeceased(), 'individual');
	}

	function totalMortalityUnknown()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file={$this->_ged_id} AND i_isdead=-1");
		if (!isset($rows[0])) {return '';}
		return $rows[0]['tot'];
	}

	function totalMortalityUnknownPercentage()
	{
		global $TBLPREFIX;
		return $this->_getPercentage($this->totalMortalityUnknown(), 'individual');
	}

	function chartMortality($params=null)
	{
		global $pgv_lang, $TEXT_DIRECTION;
		if ($params === null) {$params = array();}
		if (isset($params[0]) && $params[0] != '') {$size = strtolower($params[0]);}else{$size = '450x125';}
		if (isset($params[1]) && $params[1] != '') {$color_living = strtolower($params[1]);}else{$color_living = 'ffffff';}
		if (isset($params[2]) && $params[2] != '') {$color_dead = strtolower($params[2]);}else{$color_dead = 'cccccc';}
		if (isset($params[3]) && $params[3] != '') {$color_unknown = strtolower($params[3]);}else{$color_unknown = '777777';}
		$sizes = explode('x', $size);
		$tot_l = $this->totalLivingPercentage();
		$tot_d = $this->totalDeceasedPercentage();
		$tot_u = $this->totalMortalityUnknownPercentage();
		$chd = self::_array_to_extended_encoding(array($tot_u, $tot_l, $tot_d));
		$chl = reverseText($pgv_lang['total_unknown']).'|'.reverseText($pgv_lang['total_living']).'|'.reverseText($pgv_lang['total_dead']);
		return "<img src=\"".encode_url("http://chart.apis.google.com/chart?cht=p3&chd=e:{$chd}&chs={$size}&chco={$color_unknown},{$color_living},{$color_dead}&chf=bg,s,ffffff00&chl={$chl}")."\" width=\"{$sizes[0]}\" height=\"{$sizes[1]}\" alt=\"\" />";
	}

	static function totalUsers($params=null)
	{
		$adj = 0;
		if(isset($params[0]) && $params[0] != '') {$adj = (integer)$params[0];}else{$adj = 0;}
		return get_user_count() + $adj;
	}

	static function totalAdmins()
	{
		global $TBLPREFIX;
		$rows = self::_runSQL("SELECT COUNT(u_username) AS tot FROM {$TBLPREFIX}users WHERE u_canadmin='Y'");
		if (!isset($rows[0])) {return 0;}
		return $rows[0]['tot'];
	}

	static function totalNonAdmins()
	{
		global $TBLPREFIX;
		$rows = self::_runSQL("SELECT COUNT(u_username) AS tot FROM {$TBLPREFIX}users WHERE u_canadmin!='Y'");
		if (!isset($rows[0])) {return 0;}
		return $rows[0]['tot'];
	}

	function _totalMediaType($type='all')
	{
		global $TBLPREFIX, $MULTI_MEDIA;
		if (!$MULTI_MEDIA) {return 0;}
		if (!in_array($type, self::$_media_types) && $type != 'all' && $type != 'unknown') {return 0;}
		$like = '';
		if ($type != 'all')
		{
			if ($type != 'unknown')
			{
				$like = " AND m_gedrec LIKE '%3 TYPE {$type}%'";
			}
			else
			{
				// There has to be a better way then this :(
				$nolike = array();
				foreach (self::$_media_types as $t)
				{
					$nolike[] = "m_gedrec NOT LIKE '%3 TYPE {$t}%'";
				}
				$nolike = join(' AND ', $nolike);
				$like = " AND ({$nolike})";
			}
		}
		$rows = self::_runSQL("SELECT COUNT(m_id) AS tot FROM {$TBLPREFIX}media WHERE m_gedfile='{$this->_ged_id}'{$like}");
		if (!isset($rows[0])) {return 0;}
		return $rows[0]['tot'];
	}

	function totalMedia() {return $this->_totalMediaType('all');}
	function totalMediaAudio() {return $this->_totalMediaType('audio');}
	function totalMediaBook() {return $this->_totalMediaType('book');}
	function totalMediaCard() {return $this->_totalMediaType('card');}
	function totalMediaCertificate() {return $this->_totalMediaType('certificate');}
	function totalMediaDocument() {return $this->_totalMediaType('document');}
	function totalMediaElectronic() {return $this->_totalMediaType('electronic');}
	function totalMediaMagazine() {return $this->_totalMediaType('magazine');}
	function totalMediaManuscript() {return $this->_totalMediaType('manuscript');}
	function totalMediaMap() {return $this->_totalMediaType('map');}
	function totalMediaFiche() {return $this->_totalMediaType('fiche');}
	function totalMediaFilm() {return $this->_totalMediaType('film');}
	function totalMediaNewspaper() {return $this->_totalMediaType('newspaper');}
	function totalMediaPainting() {return $this->_totalMediaType('painting');}
	function totalMediaPhoto() {return $this->_totalMediaType('photo');}
	function totalMediaTombstone() {return $this->_totalMediaType('tombstone');}
	function totalMediaVideo() {return $this->_totalMediaType('video');}
	function totalMediaOther() {return $this->_totalMediaType('other');}
	function totalMediaUnknown() {return $this->_totalMediaType('unknown');}

	function chartMedia($params=null)
	{
		global $pgv_lang, $TEXT_DIRECTION;
		if ($params === null) {$params = array();}
		if (isset($params[0]) && $params[0] != '') {$size = strtolower($params[0]);}else{$size = '450x125';}
		if (isset($params[1]) && $params[1] != '') {$color_from = strtolower($params[1]);}else{$color_from = 'ffffff';}
		if (isset($params[2]) && $params[2] != '') {$color_to = strtolower($params[2]);}else{$color_to = '000000';}
		$sizes = explode('x', $size);
		$tot = $this->_totalMediaType('all');
		// Beware divide by zero
		if ($tot==0) {
			$tot=1;
		}
		// Build a table listing only the media types actually present in the GEDCOM
		$mediaCounts = array();
		$mediaTypes = "";
		foreach (self::$_media_types as $type) {
			$count = $this->_totalMediaType($type);
			if ($count != 0) {
				$mediaCounts[] = round(100 * $count / $tot, 0);
				$mediaTypes .= reverseText($pgv_lang['TYPE__'.$type]);
				$mediaTypes .= '|';
			}
		}
		$count = $this->_totalMediaType('unknown');
		if ($count != 0) {
			$mediaCounts[] = round(100 * $count / $tot, 0);
			$mediaTypes .= reverseText($pgv_lang['unknown']);
			$mediaTypes .= '|';
		}
		$chd = self::_array_to_extended_encoding($mediaCounts);
		$chl = urlencode(substr($mediaTypes,0,-1));
		return "<img src=\"".encode_url("http://chart.apis.google.com/chart?cht=p3&chd=e:{$chd}&chs={$size}&chco={$color_from},{$color_to}&chf=bg,s,ffffff00&chl={$chl}")."\" width=\"{$sizes[0]}\" height=\"{$sizes[1]}\" alt=\"\" />";
	}

///////////////////////////////////////////////////////////////////////////////
// Birth & Death                                                             //
///////////////////////////////////////////////////////////////////////////////

	function _mortalityQuery($type='full', $life_dir='ASC', $birth_death='BIRT')
	{
		global $TBLPREFIX, $pgv_lang, $SHOW_ID_NUMBERS, $listDir, $DBTYPE;
		if ($birth_death == 'BIRT')
		{
			$query_field = "'".str_replace('|', "','", PGV_EVENTS_BIRT)."'";
		}
		else
		{
			$birth_death = 'DEAT';
			$query_field = "'".str_replace('|', "','", PGV_EVENTS_DEAT)."'";
		}
		if ($life_dir == 'ASC')
		{
			$dmod = 'MIN';
		}
		else
		{
			$dmod = 'MAX';
			$life_dir = 'DESC';
		}
		switch($DBTYPE)
		{
			// Testing new style
			default:
			{
				$rows=self::_runSQL(''
					.' SELECT'
						.' d2.d_year,'
						.' d2.d_type,'
						.' d2.d_fact,'
						.' d2.d_gid'
					.' FROM'
						." {$TBLPREFIX}dates AS d2"
					.' WHERE'
						." d2.d_file={$this->_ged_id} AND"
						." d2.d_fact IN ({$query_field}) AND"
						.' d2.d_julianday1=('
							.' SELECT'
								." {$dmod}(d1.d_julianday1)"
							.' FROM'
								." {$TBLPREFIX}dates AS d1"
							.' WHERE'
								." d1.d_file={$this->_ged_id} AND"
								." d1.d_fact IN ({$query_field}) AND"
								.' d1.d_julianday1!=0'
						.' )'
					.' ORDER BY'
						." d_julianday1 {$life_dir}, d_type"
				);
				break;
			}
			// MySQL 4.0 can't handle nested queries, so we use the old style. Of course this hits the performance of PHP4 users a tiny bit, but it's the best we can do.
			case 'mysql':
			{
				$rows=self::_runSQL(''
					.' SELECT'
						.' d_year,'
						.' d_type,'
						.' d_fact,'
						.' d_gid'
					.' FROM'
						." {$TBLPREFIX}dates"
					.' WHERE'
						." d_file={$this->_ged_id} AND"
						." d_fact IN ({$query_field}) AND"
						.' d_julianday1!=0'
					.' ORDER BY'
						." d_julianday1 {$life_dir},"
						.' d_type ASC'
				, 1);
				break;
			}
		}
		if (!isset($rows[0])) {return '';}
		$row=$rows[0];
		$person=Person::getInstance($row['d_gid']);
		switch($type)
		{
			default:
			case 'full':
				if (displayDetailsById($row['d_gid'])) {
					$result=$person->format_list('span', false, $person->getListName());
				} else {
					$result=$pgv_lang['privacy_error'];
				}
				break;
			case 'year':
				$date=new GedcomDate($row['d_type'].' '.$row['d_year']);
				$result=$date->Display(true);
				break;
			case 'name':
				$id='';
				if ($SHOW_ID_NUMBERS) {
					if ($listDir=='rtl') {
						$id="&nbsp;&nbsp;" . getRLM() . "({$row['d_gid']})" . getRLM();
					} else {
						$id="&nbsp;&nbsp;({$row['d_gid']})";
					}
				}
				$result="<a href=\"".$person->getLinkUrl()."\">".$person->getFullName()."{$id}</a>";
				break;
			case 'place':
				$result=format_fact_place(Person::getInstance($row['d_gid'])->getFactByType($row['d_fact']), true, true, true);
				break;
		}
		return str_replace('<a href="', '<a href="'.$this->_server_url, $result);
	}

	//
	// Birth
	//

	function firstBirth() {return $this->_mortalityQuery('full', 'ASC', 'BIRT');}
	function firstBirthYear() {return $this->_mortalityQuery('year', 'ASC', 'BIRT');}
	function firstBirthName() {return $this->_mortalityQuery('name', 'ASC', 'BIRT');}
	function firstBirthPlace() {return $this->_mortalityQuery('place', 'ASC', 'BIRT');}

	function lastBirth() {return $this->_mortalityQuery('full', 'DESC', 'BIRT');}
	function lastBirthYear() {return $this->_mortalityQuery('year', 'DESC', 'BIRT');}
	function lastBirthName() {return $this->_mortalityQuery('name', 'DESC', 'BIRT');}
	function lastBirthPlace() {return $this->_mortalityQuery('place', 'DESC', 'BIRT');}

	//
	// Death
	//

	function firstDeath() {return $this->_mortalityQuery('full', 'ASC', 'DEAT');}
	function firstDeathYear() {return $this->_mortalityQuery('year', 'ASC', 'DEAT');}
	function firstDeathName() {return $this->_mortalityQuery('name', 'ASC', 'DEAT');}
	function firstDeathPlace() {return $this->_mortalityQuery('place', 'ASC', 'DEAT');}

	function lastDeath() {return $this->_mortalityQuery('full', 'DESC', 'DEAT');}
	function lastDeathYear() {return $this->_mortalityQuery('year', 'DESC', 'DEAT');}
	function lastDeathName() {return $this->_mortalityQuery('name', 'DESC', 'DEAT');}
	function lastDeathPlace() {return $this->_mortalityQuery('place', 'DESC', 'DEAT');}

///////////////////////////////////////////////////////////////////////////////
// Lifespan                                                                  //
///////////////////////////////////////////////////////////////////////////////

	function _longlifeQuery($type='full', $sex='F')
	{
		global $TBLPREFIX, $pgv_lang, $SHOW_ID_NUMBERS, $listDir;
		$sex_search = ' 1=1';
		if ($sex == 'F')
		{
			$sex_search = " i_gedcom LIKE '%1 SEX F%'";
		}
		elseif ($sex == 'M')
		{
			$sex_search = " i_gedcom LIKE '%1 SEX M%'";
		}

		$rows=self::_runSQL(''
			.' SELECT'
				.' death.d_gid AS id,'
				.' death.d_julianday2-birth.d_julianday1 AS age'
			.' FROM'
				." {$TBLPREFIX}dates AS death,"
				." {$TBLPREFIX}dates AS birth,"
				." {$TBLPREFIX}individuals AS indi"
			.' WHERE'
				.' indi.i_id=birth.d_gid AND'
				.' birth.d_gid=death.d_gid AND'
				." death.d_file={$this->_ged_id} AND"
				.' birth.d_file=death.d_file AND'
				.' birth.d_file=indi.i_file AND'
				." birth.d_fact IN ('BIRT', 'CHR', 'BAPM', '_BRTM') AND"
				." death.d_fact IN ('DEAT', 'BURI', 'CREM') AND"
				.' birth.d_julianday1!=0 AND'
				.' death.d_julianday1!=0 AND'
				.$sex_search
			.' ORDER BY'
				.' age DESC'
		, 1);
		if (!isset($rows[0])) {return '';}
		$row = $rows[0];
		$person=Person::getInstance($row['id']);
		switch($type)
		{
			default:
			case 'full':
				if (displayDetailsById($row['id'])) {
					$result=$person->format_list('span', false, $person->getListName());
				} else {
					$result= $pgv_lang['privacy_error'];
				}
				break;
			case 'age':
				$result=floor($row['age']/365.25);
				break;
			case 'name':
				$id = '';
				if ($SHOW_ID_NUMBERS) {
					if ($listDir == 'rtl') {
						$id = "&nbsp;&nbsp;".getRLM()."({$row['id']})".getRLM();
					} else {
						$id = "&nbsp;&nbsp;({$row['id']})";
					}
				}
				$result="<a href=\"".$person->getLinkUrl()."\">".$person->getFullName()."{$id}</a>";
				break;
		}
		return str_replace('<a href="', '<a href="'.$this->_server_url, $result);
	}

	function _topTenOldest($type='list', $sex='BOTH', $params=null)
	{
		global $TBLPREFIX, $TEXT_DIRECTION, $pgv_lang;
		if ($sex == 'F')
		{
			$sex_search = " AND i_gedcom LIKE '%1 SEX F%'";
		}
		elseif ($sex == 'M')
		{
			$sex_search = " AND i_gedcom LIKE '%1 SEX M%'";
		}
		else
		{
			$sex_search = '';
		}
		if ($params !== null && isset($params[0])) {$total = $params[0];}else{$total = 10;}
		$rows=self::_runSQL(''
			.' SELECT '
				.' MAX(death.d_julianday2-birth.d_julianday1) AS age,'
				.' death.d_gid'
			.' FROM'
				." {$TBLPREFIX}dates AS death,"
				." {$TBLPREFIX}dates AS birth,"
				." {$TBLPREFIX}individuals AS indi"
			.' WHERE'
				.' indi.i_id=birth.d_gid AND'
				.' birth.d_gid=death.d_gid AND'
				." death.d_file={$this->_ged_id} AND"
				.' birth.d_file=death.d_file AND'
				.' birth.d_file=indi.i_file AND'
				." birth.d_fact IN ('BIRT', 'CHR', 'BAPM', '_BRTM') AND"
				." death.d_fact IN ('DEAT', 'BURI', 'CREM') AND"
				.' birth.d_julianday1!=0 AND'
				.' death.d_julianday1!=0'
				.$sex_search
			.' GROUP BY'
				.' d_gid'	
			.' ORDER BY'
				.' age DESC'
		, $total);
		if (!isset($rows[0])) {return '';}
		if(count($rows) < $total){$total = count($rows);}
		$top10=array();
		for($c = 0; $c < $total; $c++)
		{
			$person=Person::getInstance($rows[$c]['d_gid']);
			if ($type == 'list')
			{
				$top10[]="\t<li><a href=\"".$person->getLinkUrl()."\">".PrintReady($person->getFullName())."</a> ".PrintReady("[".floor($rows[$c]['age']/365.25)." {$pgv_lang['years']}]")."</li>\n";
			}
			else
			{
				$top10[]="<a href=\"".$person->getLinkUrl()."\">".PrintReady($person->getFullName())."</a> [".PrintReady(floor($rows[$c]['age']/365.25))." {$pgv_lang['years']}]";
			}
		}
		if ($type == 'list')
		{
			$top10=join("\n", $top10);
		}
		else
		{
			$top10=join(';&nbsp; ', $top10);
		}
		if ($TEXT_DIRECTION=='rtl') {
			$top10=str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $top10);
		}
		if ($type == 'list')
		{
			return "<ul>\n{$top10}</ul>\n";
		}
		// Statstics are used by RSS feeds, etc., so need absolute URLs.
		return $top10;
	}

	function _averageLifespanQuery($sex='BOTH')
	{
		global $TBLPREFIX;
		if ($sex == 'F')
		{
			$sex_search = " AND i_gedcom LIKE '%1 SEX F%'";
		}
		elseif ($sex == 'M')
		{
			$sex_search = " AND i_gedcom LIKE '%1 SEX M%'";
		}
		else
		{
			$sex_search = '';
		}
		$rows=self::_runSQL(''
			.' SELECT'
				.' AVG(death.d_julianday2-birth.d_julianday1) AS age'
			.' FROM'
				." {$TBLPREFIX}dates AS death,"
				." {$TBLPREFIX}dates AS birth,"
				." {$TBLPREFIX}individuals AS indi"
			.' WHERE'
				.' indi.i_id=birth.d_gid AND'
				.' birth.d_gid=death.d_gid AND'
				." death.d_file={$this->_ged_id} AND"
				.' birth.d_file=death.d_file AND'
				.' birth.d_file=indi.i_file AND'
				." birth.d_fact IN ('BIRT', 'CHR', 'BAPM', '_BRTM') AND"
				." death.d_fact IN ('DEAT', 'BURI', 'CREM') AND"
				.' birth.d_julianday1!=0 AND'
				.' death.d_julianday1!=0'
				.$sex_search
		, 1);
		if (!isset($rows[0])) {return '';}
		$row=$rows[0];
		return PrintReady(floor($row['age']/365.25));
	}

	// Both Sexes

	function longestLife() {return $this->_longlifeQuery('full', 'BOTH');}
	function longestLifeAge() {return $this->_longlifeQuery('age', 'BOTH');}
	function longestLifeName() {return $this->_longlifeQuery('name', 'BOTH');}

	function topTenOldest($params=null) {return $this->_topTenOldest('nolist', 'BOTH', $params);}
	function topTenOldestList($params=null) {return $this->_topTenOldest('list', 'BOTH', $params);}

	function averageLifespan() {return $this->_averageLifespanQuery('BOTH');}

	// Female Only

	function longestLifeFemale() {return $this->_longlifeQuery('full', 'F');}
	function longestLifeFemaleAge() {return $this->_longlifeQuery('age', 'F');}
	function longestLifeFemaleName() {return $this->_longlifeQuery('name', 'F');}

	function topTenOldestFemale($params=null) {return $this->_topTenOldest('nolist', 'F', $params);}
	function topTenOldestFemaleList($params=null) {return $this->_topTenOldest('list', 'F', $params);}

	function averageLifespanFemale() {return $this->_averageLifespanQuery('F');}

	// Male Only

	function longestLifeMale() {return $this->_longlifeQuery('full', 'M');}
	function longestLifeMaleAge() {return $this->_longlifeQuery('age', 'M');}
	function longestLifeMaleName() {return $this->_longlifeQuery('name', 'M');}

	function topTenOldestMale($params=null) {return $this->_topTenOldest('nolist', 'M', $params);}
	function topTenOldestMaleList($params=null) {return $this->_topTenOldest('list', 'M', $params);}

	function averageLifespanMale() {return $this->_averageLifespanQuery('M');}

///////////////////////////////////////////////////////////////////////////////
// Events                                                                    //
///////////////////////////////////////////////////////////////////////////////

	function _eventQuery($type, $direction, $facts)
	{
		global $TBLPREFIX, $pgv_lang, $SHOW_ID_NUMBERS, $listDir;
		$eventTypes = array(
			'BIRT'=>$pgv_lang['htmlplus_block_birth'],
			'DEAT'=>$pgv_lang['htmlplus_block_death'],
			'MARR'=>$pgv_lang['htmlplus_block_marrage'],
			'ADOP'=>$pgv_lang['htmlplus_block_adoption'],
			'BURI'=>$pgv_lang['htmlplus_block_burial'],
			'CENS'=>$pgv_lang['htmlplus_block_census']
		);

		$fact_query = "IN ('".str_replace('|', "','", $facts)."')";

		if ($direction != 'ASC') {$direction = 'DESC';}
		$rows=self::_runSQL(''
			.' SELECT'
				.' d_gid AS id,'
				.' d_year AS year,'
				.' d_fact AS fact,'
				.' d_type AS type'
			.' FROM'
				." {$TBLPREFIX}dates"
			.' WHERE'
				." d_file={$this->_ged_id} AND"
				." d_gid!='HEAD' AND"
				." d_fact {$fact_query} AND"
				.' d_julianday1!=0'
			.' ORDER BY'
				." d_julianday1 {$direction}, d_type"
		, 1);
		if (!isset($rows[0])) {return '';}
		$row=$rows[0];
		$record=GedcomRecord::getInstance($row['id']);
		switch($type)
		{
			default:
			case 'full':
				if ($record->canDisplayDetails()) {
					$result=$record->format_list('span', false, $record->getListName());
				} else {
					$result=$pgv_lang['privacy_error'];
				}
				break;
			case 'year':
			{
				$date=new GedcomDate($row['type'].' '.$row['year']);
				$result=$date->Display(true);
				break;
			}
			case 'type':
				if (isset($eventTypes[$row['fact']])) {
					$result=$eventTypes[$row['fact']];
				} else {
					$result='';
				}
				break;
			case 'name':
				$id = '';
				if ($SHOW_ID_NUMBERS)
				{
					if ($listDir == 'rtl')
					{
						$id="&nbsp;&nbsp;" . getRLM() . "({$row['id']})" . getRLM();
					}
					else
					{
						$id="&nbsp;&nbsp;({$row['id']})";
					}
				}
				$result="<a href=\"".$record->getLinkUrl()."\">".PrintReady($record->getFullName())."{$id}</a>";
				break;
			case 'place':
				$result=format_fact_place($record->getFactByType($row['fact']), true, true, true);
				break;
		}
		return str_replace('<a href="', '<a href="'.$this->_server_url, $result);
	}

	function firstEvent() {
		return $this->_eventQuery('full', 'ASC', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT);
	}
	function firstEventYear() {
		return $this->_eventQuery('year', 'ASC', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT);
	}
	function firstEventType() {
		return $this->_eventQuery('type', 'ASC', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT);
	}
	function firstEventName() {
		return $this->_eventQuery('name', 'ASC', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT);
	}
	function firstEventPlace() {
		return $this->_eventQuery('place', 'ASC', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT);
	}
	function lastEvent() {
		return $this->_eventQuery('full', 'DESC', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT);
	}
	function lastEventYear() {
		return $this->_eventQuery('year', 'DESC', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT);
	}
	function lastEventType() {
		return $this->_eventQuery('type', 'DESC', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT);
	}
	function lastEventName() {
		return $this->_eventQuery('name', 'DESC', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT);
	}
	function lastEventPlace() {
		return $this->_eventQuery('place', 'DESC', PGV_EVENTS_BIRT.'|'.PGV_EVENTS_MARR.'|'.PGV_EVENTS_DEAT);
	}

///////////////////////////////////////////////////////////////////////////////
// Marriage                                                                  //
///////////////////////////////////////////////////////////////////////////////

	/*
	 * Query the database for marriage tags.
	 */
	function _marriageQuery($type='full', $age_dir='ASC', $sex='F')
	{
		global $TBLPREFIX, $pgv_lang;
		if ($sex == 'F') {$sex_field = 'f_wife';}else{$sex_field = 'f_husb';}
		if ($age_dir != 'ASC') {$age_dir = 'DESC';}
		$rows=self::_runSQL(''
			.' SELECT'
				.' fam.f_id,'
				." fam.{$sex_field},"
				.' married.d_julianday2-birth.d_julianday1 AS age,'
				.' indi.i_id AS i_id'
			.' FROM'
				." {$TBLPREFIX}families AS fam"
			.' LEFT JOIN'
				." {$TBLPREFIX}dates AS birth ON birth.d_file = {$this->_ged_id}"
			.' LEFT JOIN'
				." {$TBLPREFIX}dates AS married ON married.d_file = {$this->_ged_id}"
			.' LEFT JOIN'
				." {$TBLPREFIX}individuals AS indi ON indi.i_file = {$this->_ged_id}"
			.' WHERE'
				.' birth.d_gid = indi.i_id AND'
				.' married.d_gid = fam.f_id AND'
				." indi.i_id = fam.{$sex_field} AND"
				." fam.f_file = {$this->_ged_id} AND"
				." birth.d_fact IN ('BIRT', 'CHR', 'BAPM', '_BRTM') AND"
				." married.d_fact = 'MARR' AND"
				.' birth.d_julianday1 != 0 AND'
				.' married.d_julianday1 != 0 AND'
				." i_gedcom LIKE '%1 SEX {$sex}%'"
			.' ORDER BY'
				." married.d_julianday2-birth.d_julianday1 {$age_dir}"
		, 1);
		if (!isset($rows[0])) {return '';}
		$row=$rows[0];
		$family=Family::getInstance($row['f_id']);
		$person=Person::getInstance($row[$sex_field]);
		switch($type)
		{
			default:
			case 'full':
				if ($family->canDisplayDetails()) {
					$result=$family->format_list('span', false, $person->getFullName());
				} else {
					$result=$pgv_lang['privacy_error'];
				}
				break;
			case 'name':
				$result="<a href=\"".$family->getLinkUrl()."\">".$person->getFullName().'</a>';
				break;
			case 'age':
				$result=floor($row['age']/365.25);
				break;
		}
		return str_replace('<a href="', '<a href="'.$this->_server_url, $result);
	}

	//
	// Female only
	//

	function youngestMarriageFemale() {return $this->_marriageQuery('full', 'ASC', 'F');}
	function youngestMarriageFemaleName() {return $this->_marriageQuery('name', 'ASC', 'F');}
	function youngestMarriageFemaleAge() {return $this->_marriageQuery('age', 'ASC', 'F');}

	function oldestMarriageFemale() {return $this->_marriageQuery('full', 'DESC', 'F');}
	function oldestMarriageFemaleName() {return $this->_marriageQuery('name', 'DESC', 'F');}
	function oldestMarriageFemaleAge() {return $this->_marriageQuery('age', 'DESC', 'F');}

	//
	// Male only
	//

	function youngestMarriageMale() {return $this->_marriageQuery('full', 'ASC', 'M');}
	function youngestMarriageMaleName() {return $this->_marriageQuery('name', 'ASC', 'M');}
	function youngestMarriageMaleAge() {return $this->_marriageQuery('age', 'ASC', 'M');}

	function oldestMarriageMale() {return $this->_marriageQuery('full', 'DESC', 'M');}
	function oldestMarriageMaleName() {return $this->_marriageQuery('name', 'DESC', 'M');}
	function oldestMarriageMaleAge() {return $this->_marriageQuery('age', 'DESC', 'M');}

///////////////////////////////////////////////////////////////////////////////
// Family Size                                                               //
///////////////////////////////////////////////////////////////////////////////

	function _familyQuery($type='full')
	{
		global $TBLPREFIX, $pgv_lang;
		$rows=self::_runSQL(''
			.' SELECT'
				.' f_numchil AS tot,'
				.' f_id AS id'
			.' FROM'
				." {$TBLPREFIX}families"
			.' WHERE'
				." f_file={$this->_ged_id}"
			.' ORDER BY'
				.' tot DESC'
		, 1);
		if (!isset($rows[0])) {return '';}
		$row = $rows[0];
		$family=Family::getInstance($row['id']);
		switch($type)
		{
			default:
			case 'full':
				if ($family->canDisplayDetails())
				{
					$result=$family->format_list('span', false, $family->getListName());
				} else {
					$result = $pgv_lang['privacy_error'];
				}
				break;
			case 'size':
				$result=$row['tot'];
				break;
			case 'name':
				$result="<a href=\"".encode_url($family->getLinkUrl())."\">".PrintReady($family->getFullName()).'</a>';
				break;
		}
		// Statistics are used by RSS feeds, etc., so need absolute URLs.
		return str_replace('<a href="', '<a href="'.$this->_server_url, $result);
	}

	function _topTenFamilyQuery($type='list', $params=null)
	{
		global $TBLPREFIX, $TEXT_DIRECTION, $pgv_lang;
		if ($params !== null && isset($params[0])) {$total = $params[0];}else{$total = 10;}
		$rows=self::_runSQL(''
			.' SELECT DISTINCT'
				.' f_numchil AS tot,'
				.' f_id AS id'
			.' FROM'
				." {$TBLPREFIX}families"
			.' WHERE'
				." f_file={$this->_ged_id}"
			.' ORDER BY'
				.' tot DESC'
		, $total);
		if (!isset($rows[0])) {return '';}
		if(count($rows) < $total){$total = count($rows);}
		$top10 = array();
		for($c = 0; $c < $total; $c++) {
			$family=Family::getInstance($rows[$c]['id']);
			if ($family->canDisplayDetails()) {
				if ($type == 'list') {
					$top10[] = "\t<li><a href=\"".encode_url($family->getLinkUrl())."\">".PrintReady($family->getFullName())."</a> [{$rows[$c]['tot']} {$pgv_lang['children']}]</li>\n";
				} else {
					$top10[] = "<a href=\"".encode_url($family->getLinkUrl())."\">".PrintReady($family->getFullName())."</a> [{$rows[$c]['tot']} {$pgv_lang['children']}]";
				}
			}
		}
		if ($type == 'list')
		{
			$top10=join("\n", $top10);
		}
		else
		{
			$top10 = join(';&nbsp; ', $top10);
		}
		if ($TEXT_DIRECTION == 'rtl')
		{
			$top10 = str_replace(array('[', ']', '(', ')', '+'), array('&rlm;[', '&rlm;]', '&rlm;(', '&rlm;)', '&rlm;+'), $top10);
		}
		if ($type == 'list')
		{
			return "<ul>\n{$top10}</ul>\n";
		}
		return $top10;
	}

	function largestFamily() {return $this->_familyQuery('full');}
	function largestFamilySize() {return $this->_familyQuery('size');}
	function largestFamilyName() {return $this->_familyQuery('name');}

	function topTenLargestFamily($params=null) {return $this->_topTenFamilyQuery('nolist', $params);}
	function topTenLargestFamilyList($params=null) {return $this->_topTenFamilyQuery('list', $params);}

	function chartLargestFamilies($params=null)
	{
		global $TBLPREFIX, $pgv_lang;
		if ($params === null) {$params = array();}
		if (isset($params[0]) && $params[0] != '') {$size = strtolower($params[0]);}else{$size = '700x150';}
		if (isset($params[1]) && $params[1] != '') {$color_from = strtolower($params[1]);}else{$color_from = 'ffffff';}
		if (isset($params[2]) && $params[2] != '') {$color_to = strtolower($params[2]);}else{$color_to = '000000';}
		if (isset($params[3]) && $params[3] != '') {$total = strtolower($params[3]);}else{$total = 10;}
		$sizes = explode('x', $size);
		$rows=self::_runSQL(''
			.' SELECT'
				.' f_numchil AS tot,'
				.' f_id AS id'
			.' FROM'
				." {$TBLPREFIX}families"
			.' WHERE'
				." f_file={$this->_ged_id}"
			.' ORDER BY'
				.' tot DESC'
		, $total);
		if (!isset($rows[0])) {return '';}
		if(count($rows) < $total){$total = count($rows);}
		$tot = 0; foreach ($rows as $indexval=>$row) {$tot += $row['tot'];}
		$chd = '';
		$chl = array();
		for($i = 0; $i < $total; $i++)
		{
			if ($tot==0) {
				$per=0;
			} else {
				$per = round(100 * $rows[$i]['tot'] / $tot, 0);
			}
			$chd .= self::_array_to_extended_encoding(array($per));
			$family=Family::getInstance($rows[$i]['id']);
			$chl[] = reverseText($family->getFullName());
		}
		$chl = join('|', $chl);
		return "<img src=\"".encode_url("http://chart.apis.google.com/chart?cht=p3&chd=e:{$chd}&chs={$size}&chco={$color_from},{$color_to}&chf=bg,s,ffffff00&chl={$chl}")."\" width=\"{$sizes[0]}\" height=\"{$sizes[1]}\" alt=\"\" />";
	}

	function averageChildren()
	{
		global $TBLPREFIX;
		$rows=self::_runSQL("SELECT AVG(f_numchil) AS tot FROM {$TBLPREFIX}families WHERE f_file={$this->_ged_id}");
		$row=$rows[0];
		return sprintf('%.2f', $row['tot']);
	}

///////////////////////////////////////////////////////////////////////////////
// Surnames                                                                  //
///////////////////////////////////////////////////////////////////////////////

	static function _commonSurnamesQuery($type='list', $show_tot=false, $params=null) {
		global $TEXT_DIRECTION, $COMMON_NAMES_THRESHOLD, $SURNAME_LIST_STYLE;

		if (is_array($params) && isset($params[0]) && $params[0] != '') {$threshold = strtolower($params[0]);}else{$threshold = $COMMON_NAMES_THRESHOLD;}
		if(is_array($params) && isset($params[1]) && $params[1] != '' && $params[1] >= 0){$maxtoshow = strtolower($params[1]);}else{$maxtoshow = false;}
		if(is_array($params) && isset($params[2]) && $params[2] != ''){$sorting = strtolower($params[2]);}else{$sorting = 'alpha';}
		$surname_list = get_common_surnames($threshold);
		if (count($surname_list) == 0) return '';
		uasort($surname_list, array('stats', '_name_total_rsort'));
		if ($maxtoshow>0) $surname_list = array_slice($surname_list, 0, $maxtoshow);

		switch($sorting) {
			default:
			case 'alpha':
				uasort($surname_list, array('stats', '_name_name_sort'));
				break;
			case 'ralpha':
				uasort($surname_list, array('stats', '_name_name_rsort'));
				break;
			case 'count':
				uasort($surname_list, array('stats', '_name_total_sort'));
				break;
			case 'rcount':
				uasort($surname_list, array('stats', '_name_total_rsort'));
				break;
		}

		// Note that we count/display SPFX SURN, but sort/group under just SURN
		$surnames=array();
		foreach (array_keys($surname_list) as $surname) {
			foreach (array_keys(get_surname_indis($surname)) as $pid) {
				$person=Person::getInstance($pid);
				foreach ($person->getAllNames() as $name) {
					$surn=reset(explode(',', $name['sort']));
					if ($surname==$surn) {
						$spfxsurn=reset(explode(',', $name['list']));
						if (! array_key_exists($surn, $surnames)) {
							$surnames[$surn]=array();
						}
						if (! array_key_exists($spfxsurn, $surnames[$surn])) {
							$surnames[$surn][$spfxsurn]=array();
						}
						// $surn is the base surname, e.g. GOGH
						// $spfxsurn is the full surname, e.g. van GOGH
						// $pid allows us to count indis as well as surnames, for indis that
						// appear twice in this list.
						$surnames[$surn][$spfxsurn][$pid]=true;
					}
				}
			}
		}

		//switch ($SURNAME_LIST_STYLE) {
		//case 'style3':
		//	return format_surname_tagcloud($surnames, 'indilist', $show_tot);
		//case 'style2':
		//default:
			return format_surname_list($surnames, ($type=='list' ? 1 : 2), $show_tot);
		//}
	}

	static function commonSurnames($params=array('','','alpha')) {return self::_commonSurnamesQuery('nolist', false, $params);}
	static function commonSurnamesTotals($params=array('','','rcount')) {return self::_commonSurnamesQuery('nolist', true, $params);}
	static function commonSurnamesList($params=array('','','alpha')) {return self::_commonSurnamesQuery('list', false, $params);}
	static function commonSurnamesListTotals($params=array('','','rcount')) {return self::_commonSurnamesQuery('list', true, $params);}

	function chartCommonSurnames($params=null)
	{
		global $pgv_lang, $COMMON_NAMES_THRESHOLD;
		if ($params === null) {$params = array();}
		if (isset($params[0]) && $params[0] != '') {$size = strtolower($params[0]);}else{$size = '350x100';}
		if (isset($params[1]) && $params[1] != '') {$color_from = strtolower($params[1]);}else{$color_from = 'ffffff';}
		if (isset($params[2]) && $params[2] != '') {$color_to = strtolower($params[2]);}else{$color_to = '000000';}
		if (isset($params[3]) && $params[3] != '') {$threshold = strtolower($params[3]);}else{$threshold = $COMMON_NAMES_THRESHOLD;}
		$sizes = explode('x', $size);
		$surnames = get_common_surnames($threshold);
		if (count($surnames) <= 0) {return '';}
		$tot = 0;
		foreach ($surnames as $indexval=>$surname) {$tot += $surname['match'];}
		$chd = '';
		$chl = array();
		foreach ($surnames as $indexval=>$surname)
		{
			if ($tot==0) {
				$per = 0;
			} else {
				$per = round(100 * $surname['match'] / $tot, 0);
			}
			$chd .= self::_array_to_extended_encoding($per);
			$chl[] = reverseText($surname['name']);
		}
		$chl = join('|', $chl);
		return "<img src=\"".encode_url("http://chart.apis.google.com/chart?cht=p3&chd=e:{$chd}&chs={$size}&chco={$color_from},{$color_to}&chf=bg,s,ffffff00&chl={$chl}")."\" width=\"{$sizes[0]}\" height=\"{$sizes[1]}\" alt=\"\" />";
	}


///////////////////////////////////////////////////////////////////////////////
// Given Names                                                               //
///////////////////////////////////////////////////////////////////////////////

	/*
	 * [ 1977282 ] Most Common Given Names Block
	 * Original block created by kiwi_pgv
	 */
	static function _commonGivenQuery($sex='B', $type='list', $show_tot=false, $params=null)
	{
		global $TEXT_DIRECTION, $DEBUG, $GEDCOMS, $GEDCOM, $DBCONN, $TBLPREFIX, $pgv_lang;
		static $sort_types = array('count'=>'asort', 'rcount'=>'arsort', 'alpha'=>'ksort', 'ralpha'=>'krsort');
		static $sort_flags = array('count'=>SORT_NUMERIC, 'rcount'=>SORT_NUMERIC, 'alpha'=>SORT_STRING, 'ralpha'=>SORT_STRING);

		if(is_array($params) && isset($params[0]) && $params[0] != '' && $params[0] >= 0){$threshold = strtolower($params[0]);}else{$threshold = 1;}
		if(is_array($params) && isset($params[1]) && $params[1] != '' && $params[1] >= 0){$maxtoshow = strtolower($params[1]);}else{$maxtoshow = 10;}
		if(is_array($params) && isset($params[2]) && $params[2] != '' && isset($sort_types[strtolower($params[2])])){$sorting = strtolower($params[2]);}else{$sorting = 'rcount';}

		//-- cache the result in the session so that subsequent calls do not have to
		//-- perform the calculation all over again.
		//if(isset($_SESSION['first_names_f'][$GEDCOM]) && (!isset($DEBUG) || ($DEBUG == false))) {
		if(
			isset($_SESSION['first_names_F'][$GEDCOM]) &&
			isset($_SESSION['first_names_M'][$GEDCOM]) &&
			isset($_SESSION['first_names_U'][$GEDCOM]) &&
			(!isset($DEBUG) ||
			($DEBUG == false))
		) {
			$name_list_F = $_SESSION['first_names_F'][$GEDCOM];
			$name_list_M = $_SESSION['first_names_M'][$GEDCOM];
			$name_list_U = $_SESSION["first_names_U"][$GEDCOM];
		} else {
			$name_list_F = array();
			$name_list_M = array();
			$name_list_U = array();

			foreach (array_keys(get_indi_list()) as $pid) {
				$person=Person::getInstance($pid);
				if ($person->canDisplayName()) {
					$genderList='name_list_'.$person->getSex();
					foreach ($person->getAllNames() as $name) {
						if ($name['type']!='_MARNM') {
							$firstnamestring = ' '.str_replace(array('*', '.', '-', '_', ',', '(', ')', '[', ']', '{', '}', '@'), ' ', $name['givn']).' '; 
							// Remove names within quotes and apostrophes
							$firstnamestring = preg_replace(array(": '.*' :", ': ".*" :'), ' ', $firstnamestring);
							$firstnamestring = preg_replace(": (\xC2\xAB|\xC2\xBB|\xEF\xB4\xBF|\xEF\xB4\xBE|\xE2\x80\xBA|\xE2\x80\xB9|\xE2\x80\x9E|\xE2\x80\x9C|\xE2\x80\x9D|\xE2\x80\x9A|\xE2\x80\x98|\xE2\x80\x99).*(\xC2\xAB|\xC2\xBB|\xEF\xB4\xBF|\xEF\xB4\xBE|\xE2\x80\xBA|\xE2\x80\xB9|\xE2\x80\x9E|\xE2\x80\x9C|\xE2\x80\x9D|\xE2\x80\x9A|\xE2\x80\x98|\xE2\x80\x99) :", ' ', $firstnamestring);
	
							foreach (explode(' ', $firstnamestring) as $givnName) {
								// Ignore single letters (but not single-character japanese/chinese names?)
								if (strlen($givnName)>1) {
									if (isset(${$genderList}[$givnName])) {
										${$genderList}[$givnName] ++;
									} else {
										${$genderList}[$givnName] = 1;
									}
								}
							}
						}
					}
				}
			}

			arsort($name_list_F, SORT_NUMERIC);
			$_SESSION['first_names_F'][$GEDCOM] = $name_list_F;
			arsort($name_list_M, SORT_NUMERIC);
			$_SESSION['first_names_M'][$GEDCOM] = $name_list_M;
			arsort($name_list_U, SORT_NUMERIC);
			$_SESSION['first_names_U'][$GEDCOM] = $name_list_U;
		}
		if ($sex == 'F') {
			$nameList = array_slice($name_list_F, 0, $maxtoshow);
			eval($sort_types[$sorting]($nameList, $sort_flags[$sorting]).";");
		} else if ($sex == 'M') {
			$nameList = array_slice($name_list_M, 0, $maxtoshow);
			eval($sort_types[$sorting]($nameList, $sort_flags[$sorting]).";");
		} else if ($sex == 'U') {
			$nameList = array_slice($name_list_U, 0, $maxtoshow);
			eval($sort_types[$sorting]($nameList, $sort_flags[$sorting]).";");
		} else {
			$name_list_B = $name_list_F;
			// Combine names and counts from the Male list into the Totals list
			foreach ($name_list_M as $key => $count) {
				if (isset($name_list_B[$key])) $name_list_B[$key] += $count;
				else $name_list_B[$key] = $count;
			}
			// Combine names and counts from the Unknown list into the Totals list
			foreach ($name_list_U as $key => $count) {
				if (isset($name_list_B[$key])) $name_list_B[$key] += $count;
				else $name_list_B[$key] = $count;
			}
			arsort($name_list_B, SORT_NUMERIC);
			$nameList = array_slice($name_list_B, 0, $maxtoshow);
			eval($sort_types[$sorting]($nameList, $sort_flags[$sorting]).";");
		}
		if (count($nameList)==0) return '';

		$common = array();
		foreach ($nameList as $given=>$total) {
			if ($maxtoshow !== -1) {if($maxtoshow-- <= 0){break;}}
			if ($total < $threshold) {break;}
			if ($show_tot) {
				$tot = PrintReady("[{$total}]");
				if ($TEXT_DIRECTION=='ltr') {
					$totL = '';
					$totR = '&nbsp;'.$tot;
				} else {
					$totL = $tot.'&nbsp;';
					$totR = '';
				}
			} else {
				$totL = '';
				$totR = '';
			}
			switch ($type) {
			case 'table':
				$common[] = '<tr><td class="optionbox">'.PrintReady(UTF8_substr($given,0,1).UTF8_strtolower(UTF8_substr($given,1))).'</td><td class="optionbox">'.$total.'</tr>';
				break;
			case 'list':
				$common[] = "\t<li>{$totL}".PrintReady(UTF8_substr($given,0,1).UTF8_strtolower(UTF8_substr($given,1)))."{$totR}</li>\n";
				break;
			case 'nolist':
				$common[] = $totL.PrintReady(UTF8_substr($given,0,1).UTF8_strtolower(UTF8_substr($given,1))).$totR;
				break;
			}
		}
		if ($common) {
			switch ($type) {
			case 'table':
				$lookup=array('M'=>$pgv_lang['male'], 'F'=>$pgv_lang['female'], 'U'=>$pgv_lang['unknown'], 'B'=>$pgv_lang['all']);
				return '<table><tr><td colspan="2" class="descriptionbox center">'.$lookup[$sex].'</td></tr><tr><td class="descriptionbox center">'.$pgv_lang['name'].'</td><td class="descriptionbox center">'.$pgv_lang['count'].'</td></tr>'.join('', $common).'</table>';
			case 'list':
				return "<ul>\n".join("\n", $common)."</ul>\n";
			case 'nolist':
				return join(';&nbsp; ', $common);
			}
		} else {
			return '';
		}
	}

	static function commonGiven($params=array(1,10,'alpha')){return self::_commonGivenQuery('B', 'nolist', false, $params);}
	static function commonGivenTotals($params=array(1,10,'rcount')){return self::_commonGivenQuery('B', 'nolist', true, $params);}
	static function commonGivenList($params=array(1,10,'alpha')){return self::_commonGivenQuery('B', 'list', false, $params);}
	static function commonGivenListTotals($params=array(1,10,'rcount')){return self::_commonGivenQuery('B', 'list', true, $params);}
	static function commonGivenTable($params=array(1,10,'rcount')){return self::_commonGivenQuery('B', 'table', false, $params);}

	static function commonGivenFemale($params=array(1,10,'alpha')){return self::_commonGivenQuery('F', 'nolist', false, $params);}
	static function commonGivenFemaleTotals($params=array(1,10,'rcount')){return self::_commonGivenQuery('F', 'nolist', true, $params);}
	static function commonGivenFemaleList($params=array(1,10,'alpha')){return self::_commonGivenQuery('F', 'list', false, $params);}
	static function commonGivenFemaleListTotals($params=array(1,10,'rcount')){return self::_commonGivenQuery('F', 'list', true, $params);}
	static function commonGivenFemaleTable($params=array(1,10,'rcount')){return self::_commonGivenQuery('F', 'table', false, $params);}

	static function commonGivenMale($params=array(1,10,'alpha')){return self::_commonGivenQuery('M', 'nolist', false, $params);}
	static function commonGivenMaleTotals($params=array(1,10,'rcount')){return self::_commonGivenQuery('M', 'nolist', true, $params);}
	static function commonGivenMaleList($params=array(1,10,'alpha')){return self::_commonGivenQuery('M', 'list', false, $params);}
	static function commonGivenMaleListTotals($params=array(1,10,'rcount')){return self::_commonGivenQuery('M', 'list', true, $params);}
	static function commonGivenMaleTable($params=array(1,10,'rcount')){return self::_commonGivenQuery('M', 'table', false, $params);}

	static function commonGivenUnknown($params=array(1,10,'alpha')){return self::_commonGivenQuery('U', 'nolist', false, $params);}
	static function commonGivenUnknownTotals($params=array(1,10,'rcount')){return self::_commonGivenQuery('U', 'nolist', true, $params);}
	static function commonGivenUnknownList($params=array(1,10,'alpha')){return self::_commonGivenQuery('U', 'list', false, $params);}
	static function commonGivenUnknownListTotals($params=array(1,10,'rcount')){return self::_commonGivenQuery('U', 'list', true, $params);}
	static function commonGivenUnknownTable($params=array(1,10,'rcount')){return self::_commonGivenQuery('U', 'table', false, $params);}

///////////////////////////////////////////////////////////////////////////////
// Users                                                                     //
///////////////////////////////////////////////////////////////////////////////

	static function _usersLoggedIn($type='nolist')
	{
		global $PGV_SESSION_TIME, $pgv_lang;
		// Log out inactive users
		foreach (get_idle_users(time() - $PGV_SESSION_TIME) as $user_id=>$user_name)
		{
			if ($user_id != PGV_USER_ID)
			{
				userLogout($user_id);
			}
		}

		$content = '';

		// List active users
		$NumAnonymous = 0;
		$loggedusers = array ();
		$x = get_logged_in_users();
		foreach ($x as $user_id=>$user_name)
		{
			if (PGV_USER_IS_ADMIN || get_user_setting($user_id, 'visibleonline') == 'Y')
			{
				$loggedusers[$user_id] = $user_name;
			}
			else
			{
				$NumAnonymous++;
			}
		}
		$LoginUsers = count($loggedusers);
		if (($LoginUsers == 0) and ($NumAnonymous == 0))
		{
			return $pgv_lang['no_login_users'];
		}
		$Advisory = 'anon_user';
		if ($NumAnonymous > 1) {$Advisory .= 's';}
		if ($NumAnonymous > 0)
		{
			$pgv_lang['global_num1'] = $NumAnonymous; // Make it visible
			$content .= '<b>'.print_text($Advisory, 0, 1).'</b>';
		}
		$Advisory = 'login_user';
		if ($LoginUsers > 1) {$Advisory .= 's';}
		if ($LoginUsers > 0)
		{
			$pgv_lang['global_num1'] = $LoginUsers; // Make it visible
			if ($NumAnonymous)
			{
				if ($type == 'list')
				{
					$content .= "<br /><br />\n";
				}
				else
				{
					$content .= " {$pgv_lang['and']} ";
				}
			}
			if ($type == 'list')
			{
				$content .= '<b>'.print_text($Advisory, 0, 1)."</b>\n<ul>\n";
			}
			else
			{
				$content .= '<b>'.print_text($Advisory, 0, 1)."</b>: ";
			}
		}
		if (PGV_USER_ID)
		{
			foreach ($loggedusers as $user_id=>$user_name)
			{
				if ($type == 'list')
				{
					$content .= "\t<li>".PrintReady(getUserFullName($user_id))." - {$user_name}";
				}
				else
				{
					$content .= PrintReady(getUserFullName($user_id))." - {$user_name}";
				}
				if (PGV_USER_ID != $user_id && get_user_setting($user_id, 'contactmethod') != 'none')
				{
					if ($type == 'list')
					{
						$content .= "<br /><a href=\"javascript:;\" onclick=\"return message('{$user_id}');\">{$pgv_lang['message']}</a>";
					}
					else
					{
						$content .= " <a href=\"javascript:;\" onclick=\"return message('{$user_id}');\">{$pgv_lang['message']}</a>";
					}
				}
				if ($type == 'list')
				{
					$content .= "</li>\n";
				}
			}
		}
		if ($type == 'list')
		{
			$content .= '</ul>';
		}
		return $content;
	}

	static function _usersLoggedInTotal($type='all')
	{
		global $PGV_SESSION_TIME;

		foreach (get_idle_users(time() - $PGV_SESSION_TIME) as $user_id=>$user_name)
		{
			if ($user_id != PGV_USER_ID) {userLogout($user_id);}
		}

		$anon = 0;
		$visible = 0;
		$x = get_logged_in_users();
		foreach ($x as $user_id=>$user_name)
		{
			if (PGV_USER_IS_ADMIN || get_user_setting($user_id, 'visibleonline') == 'Y') {$visible++;}else{$anon++;}
		}
		if ($type == 'anon') {return $anon;}
		elseif ($type == 'visible') {return $visible;}
		else{return $visible + $anon;}
	}

	static function usersLoggedIn() {return self::_usersLoggedIn('nolist');}
	static function usersLoggedInList() {return self::_usersLoggedIn('list');}

	static function usersLoggedInTotal() {return self::_usersLoggedInTotal('all');}
	static function usersLoggedInTotalAnon() {return self::_usersLoggedInTotal('anon');}
	static function usersLoggedInTotalVisible() {return self::_usersLoggedInTotal('visible');}

	static function userID() {return getUserId();}
	static function userName() {return getUserName();}
	static function userFullName() {return getUserFullName(getUserId());}
	static function userFirstName() {return get_user_setting(getUserId(), 'firstname');}
	static function userLastName() {return get_user_setting(getUserId(), 'lastname');}

	static function _getLatestUserData($type='userid', $params=null)
	{
		global $DATE_FORMAT, $TIME_FORMAT, $pgv_lang;
		static $user = null;

		if($user === null)
		{
			$users = get_all_users('DESC', 'reg_timestamp', 'username');
			$user = array_shift($users);
			unset($users);
		}

		switch($type)
		{
			default:
			case 'userid':
			{
				return $user;
			}
			case 'username':
			{
				return get_user_name($user);
			}
			case 'fullname':
			{
				return getUserFullName($user);
			}
			case 'firstname':
			{
				return get_user_setting($user, 'firstname');
			}
			case 'lastname':
			{
				return get_user_setting($user, 'lastname');
			}
			case 'regdate':
			{
				if(is_array($params) && isset($params[0]) && $params[0] != ''){$datestamp = $params[0];}else{$datestamp = $DATE_FORMAT;}
				//$d = new GedcomDate(date('j M Y', get_user_setting($user, 'reg_timestamp')));
				//return strip_tags($d->Display(false, $DATE_FORMAT, array()));
				return date($datestamp, get_user_setting($user, 'reg_timestamp'));
			}
			case 'regtime':
			{
				if(is_array($params) && isset($params[0]) && $params[0] != ''){$datestamp = $params[0];}else{$datestamp = $TIME_FORMAT;}
				return date($datestamp, get_user_setting($user, 'reg_timestamp'));
			}
			case 'loggedin':
			{
				if(is_array($params) && isset($params[0]) && $params[0] != ''){$yes = $params[0];}else{$yes = $pgv_lang['yes'];}
				if(is_array($params) && isset($params[1]) && $params[1] != ''){$no = $params[1];}else{$no = $pgv_lang['no'];}
				return (get_user_setting($user, 'loggedin') == 'Y')?$yes:$no;
			}
		}
	}

	static function latestUserId(){return self::_getLatestUserData('userid');}
	static function latestUserName(){return self::_getLatestUserData('username');}
	static function latestUserFullName(){return self::_getLatestUserData('fullname');}
	static function latestUserFirstName(){return self::_getLatestUserData('firstname');}
	static function latestUserLastName(){return self::_getLatestUserData('lastname');}
	static function latestUserRegDate($params=null){return self::_getLatestUserData('regdate', $params);}
	static function latestUserRegTime($params=null){return self::_getLatestUserData('regtime', $params);}
	static function latestUserLoggedin($params=null){return self::_getLatestUserData('loggedin', $params);}

///////////////////////////////////////////////////////////////////////////////
// Contact                                                                   //
///////////////////////////////////////////////////////////////////////////////

	static function contactWebmaster() {return user_contact_link($GLOBALS['WEBMASTER_EMAIL'], $GLOBALS['SUPPORT_METHOD']);}
	static function contactGedcom() {return user_contact_link($GLOBALS['CONTACT_EMAIL'], $GLOBALS['CONTACT_METHOD']);}

///////////////////////////////////////////////////////////////////////////////
// Date & Time                                                               //
///////////////////////////////////////////////////////////////////////////////

	static function serverDate() {$today=new GedcomDate(date('j M Y')); return $today->Display(false);}

	static function serverTime() {return date('g:i a');}

	static function serverTime24() {return date('G:i');}

	static function serverTimezone() {return date('T');}

	static function browserDate() {$today=new GedcomDate(date('j M Y'), client_time()); return $today->Display(false);}

	static function browserTime() {return date('g:i a', client_time());}

	static function browserTime24() {return date('G:i', client_time());}

	static function browserTimezone() {return date('T', client_time());}

///////////////////////////////////////////////////////////////////////////////
// Tools                                                                     //
///////////////////////////////////////////////////////////////////////////////

	/*
	 * Leave for backwards compatability? Anybody using this?
	 */
	static function _getEventType($type)
	{
		global $pgv_lang;
		$eventTypes=array(
			'BIRT'=>$pgv_lang['htmlplus_block_birth'],
			'DEAT'=>$pgv_lang['htmlplus_block_death'],
			'MARR'=>$pgv_lang['htmlplus_block_marrage'],
			'ADOP'=>$pgv_lang['htmlplus_block_adoption'],
			'BURI'=>$pgv_lang['htmlplus_block_burial'],
			'CENS'=>$pgv_lang['htmlplus_block_census']
		);
		if (isset($eventTypes[$type])) {
			return $eventTypes[$type];
		}
		return false;
	}

	// http://bendodson.com/news/google-extended-encoding-made-easy/
	static function _array_to_extended_encoding($a)
	{
		if (!is_array($a)) {$a = array($a);}
		$encoding = '';
		foreach ($a as $value)
		{
			$first = floor($value / 64);
			$second = $value % 64;
			$encoding .= self::$_xencoding[$first].self::$_xencoding[$second];
		}
		return $encoding;
	}

	static function _name_name_sort($a, $b) {
		return compareStrings(strip_prefix($a['name']), strip_prefix($b['name']), true);		// Case-insensitive compare
	}

	static function _name_name_rsort($a, $b) {
		return compareStrings(strip_prefix($b['name']), strip_prefix($a['name']), true);		// Case-insensitive compare
	}

	static function _name_total_sort($a, $b)
	{
		return $a['match']-$b['match'];
	}

	static function _name_total_rsort($a, $b)
	{
		return $b['match']-$a['match'];
	}

	static function _runSQL($sql, $count=0)
	{
		global $DBTYPE;
		static $cache = array();
		$id = md5($sql)."_{$count}";
		if (isset($cache[$id]))
		{
			return $cache[$id];
		}
		// If we alter the SQL for a specific database for LIMIT reasons, clear the $count so we don't alter it more later
		switch($DBTYPE)
		{
			case 'mssql':
			case 'sybase':
			{
				if ($count > 0)
				{
					$sql = preg_replace('/^([\s(])*SELECT/i', "SELECT TOP {$count}", $sql);
					$count = 0;
				}
				break;
			}
		}
		$rows = array();
		$tempsql = dbquery($sql, true, $count);
		if (!DB::isError($tempsql))
		{
			$res=& $tempsql;
			while($row =& $res->fetchRow(DB_FETCHMODE_ASSOC))
			{
				$rows[] = $row;
			}
			$res->free();
			$cache[$id] = $rows;
			return $rows;
		}
		return false;
	}
}

if(!function_exists('array_combine'))
{
	function array_combine($arr1, $arr2)
	{
		$out = array();
		$arr1 = array_values($arr1);
		$arr2 = array_values($arr2);
		foreach($arr1 as $key1 => $value1){$out[(string)$value1] = $arr2[$key1];}
		return $out;
	}
}
