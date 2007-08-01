<?php
/**
 * GEDCOM Statistics Class
 *
 * This class provides a quick & easy method for accessing statistics
 * about the GEDCOM.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @version $Id:  $
 * @author Patrick Kellum
 * @package PhpGedView
 * @subpackage Lists
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

require_once 'includes/functions_print_lists.php';
class stats
{
	var $_gedcom;
	var $_compat = false;

	function stats($gedcom)
	{
		$this->_setGedcom($gedcom);
	}

	function _setGedcom($gedcom)
	{
		global $GEDCOMS;
		$this->_gedcom = $GEDCOMS[$gedcom];
	}

	/**
	 * Return an array of all supported tags and an example ot its output.
	 */
	function getAllTags()
	{
		$examples = array();
		$methods = get_class_methods('stats');
		$c = count($methods);
		for($i = 0; $i < $c; $i++)
		{
			if($methods[$i][0] == '_' || $methods[$i] == 'stats' || $methods[$i] == 'getAllTags' || $methods[$i] == 'getTags'){continue;}
			$examples[$methods[$i]] = $this->$methods[$i]();
			if(stristr($methods[$i], 'percentage') || $methods[$i] == 'averageChildren'){$examples[$methods[$i]] .= '%';}
			if(stristr($methods[$i], 'highlight')){$examples[$methods[$i]] = str_replace(array(' align="left"', ' align="right"'), '', $examples[$methods[$i]]);}
		}
		ksort($examples);
		return $examples;
	}

	/*
	 * Get tags and their parsed results.
	 */
	function getTags($text)
	{
		global $pgv_lang, $factarray;

		$ct = preg_match_all("/#(.+)#/U", "{$text}", $match);
		$tags = $match[1];
		$new_tags = array();
		$new_values = array();
		$c = count($tags);

		if($this->_compat)
		{
			static $funcs = null;
			if(!is_array($funcs)){$funcs = get_class_methods($this);}
		}

		/*
		 * Parse block tags.
		 */
		for($i = 0; $i < $c; $i++)
		{
			// Why are PHP functions case insentive?
			if($this->_compat){if(!array_search($tags[$i], $funcs)){continue;}}
			if(method_exists($this, $tags[$i]))
			{
				$new_tags[] = "#{$tags[$i]}#";
				$new_values[] = $this->$tags[$i]();
				unset($tags[$i]);
			}
		}

		/*
		 * Parse language variables.
		 */
		foreach($tags as $i=>$x)
		{
			// help link
			if(substr($x, 0, 5) == 'help:')
			{
				$new_tags[] = "#{$x}#";
				$new_values[] = print_help_link(substr($x, 5), 'qm', '', false, true);
				unset($tags[$i]);
			}
			// pgv_lang - long
			if(substr($x, 0, 5) == 'lang:' && isset($pgv_lang[substr($x, 5)]))
			{
				$new_tags[] = "#{$x}#";
				$new_values[] = print_text($pgv_lang[substr($x, 5)], 0, 2);
				unset($tags[$i]);
			}
			// pgv_lang
			elseif(isset($pgv_lang[$x]))
			{
				$new_tags[] = "#{$x}#";
				$new_values[] = print_text($pgv_lang[$x], 0, 2);
				unset($tags[$i]);
			}
			// factarray
			elseif(isset($factarray[$x]))
			{
				$new_tags[] = "#{$x}#";
				$new_values[] = $factarray[$x];
				unset($tags[$i]);
			}
			// GLOBALS
			elseif(isset($GLOBALS[$x]))
			{
				$new_tags[] = "#{$x}#";
				$new_values[] = $GLOBALS[$x];
				unset($tags[$i]);
			}
		}
		return array($new_tags, $new_values);
	}

///////////////////////////////////////////////////////////////////////////////
// GEDCOM                                                                    //
///////////////////////////////////////////////////////////////////////////////

	function gedcomFilename(){return $this->_gedcom['gedcom'];}

	function gedcomID(){return $this->_gedcom['id'];}

	function gedcomTitle(){return $this->_gedcom['title'];}

	function _gedcomHead()
	{
		static $cache = null;
		if(is_array($cache)){return $cache;}
		$head = find_gedcom_record('HEAD');
		$ct = preg_match("/1 SOUR (.*)/", $head, $match);
		if($ct > 0)
		{
			$softrec = get_sub_record(1, '1 SOUR', $head);
			$tt = preg_match("/2 NAME (.*)/", $softrec, $tmatch);
			if($tt > 0){$title = trim($tmatch[1]);}else{$title = trim($match[1]);}
			if(!empty($title))
			{
				$tt = preg_match("/2 VERS (.*)/", $softrec, $tmatch);
				if($tt > 0){$version = trim($tmatch[1]);}else{$version = '';}
			}
			else
			{
				$version = '';
			}
			$tt = preg_match("/1 SOUR (.*)/", $softrec, $tmatch);
			if($tt > 0){$source = trim($tmatch[1]);}else{$source = trim($match[1]);}
		}
		$cache = array($title, $version, $source);
		return $cache;
	}

	function gedcomCreatedSoftware()
	{
		$head = $this->_gedcomHead();
		return $head[0];
	}

	function gedcomCreatedVersion()
	{
		$head = $this->_gedcomHead();
		// fix broken version string in Family Tree Maker
		if(strstr($head[1], 'Family Tree Maker '))
		{
			$p = strpos($head[1], '(') + 1;
			$p2 = strpos($head[1], ')');
			$head[1] = substr($head[1], $p, ($p2 - $p));
		}
		// Fix EasyTree version
		if($head[2] == 'EasyTree')
		{
			$head[1] = substr($head[1], 1);
		}
		return $head[1];
	}

	function gedcomDate()
	{
		$head = find_gedcom_record('HEAD');
		$ct = preg_match("/1 DATE (.*)/", $head, $match);
		if($ct > 0)
		{
			return get_changed_date(trim($match[1]));
		}
		return '';
	}

	function gedcomUpdated()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_year, d_month, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'CHAN' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);
		if(!isset($rows[0]))
		{
			$head = find_gedcom_record('HEAD');
			$ct = preg_match("/1 DATE (.*)/", $head, $match);
			if($ct > 0)
			{
				return get_changed_date(trim($match[1]));
			}
		}
		return get_changed_date("{$rows[0]['d_day']} {$rows[0]['d_month']} {$rows[0]['d_year']}");
	}

	function gedcomHighlight()
	{
		$highlight = false;
		if(file_exists("images/gedcoms/{$this->_gedcom['gedcom']}.jpg")){$highlight = "images/gedcoms/{$this->_gedcom['gedcom']}.jpg";}elseif(file_exists("images/gedcoms/{$this->_gedcom['gedcom']}.png")){$highlight = "images/gedcoms/{$this->_gedcom['gedcom']}.png";}
		if(!$highlight){return '';}
		$imgsize = findImageSize($highlight);
		return "<a href=\"index.php?ctype=gedcom&amp;ged={$this->_gedcom['gedcom']}\" style=\"border-style:none;\"><img src=\"{$highlight}\" {$imgsize[3]} style=\"border:none; padding:2px 6px 2px 2px;\" /></a>";
	}

	function gedcomHighlightLeft()
	{
		$highlight = false;
		if(file_exists("images/gedcoms/{$this->_gedcom['gedcom']}.jpg")){$highlight = "images/gedcoms/{$this->_gedcom['gedcom']}.jpg";}elseif(file_exists("images/gedcoms/{$this->_gedcom['gedcom']}.png")){$highlight = "images/gedcoms/{$this->_gedcom['gedcom']}.png";}
		if(!$highlight){return '';}
		$imgsize = findImageSize($highlight);
		return "<a href=\"index.php?ctype=gedcom&amp;ged={$this->_gedcom['gedcom']}\" style=\"border-style:none;\"><img src=\"{$highlight}\" {$imgsize[3]} style=\"border:none; padding:2px 6px 2px 2px;\" align=\"left\" /></a>";
	}

	function gedcomHighlightRight()
	{
		$highlight = false;
		if(file_exists("images/gedcoms/{$this->_gedcom['gedcom']}.jpg")){$highlight = "images/gedcoms/{$this->_gedcom['gedcom']}.jpg";}elseif(file_exists("images/gedcoms/{$this->_gedcom['gedcom']}.png")){$highlight = "images/gedcoms/{$this->_gedcom['gedcom']}.png";}
		if(!$highlight){return '';}
		$imgsize = findImageSize($highlight);
		return "<a href=\"index.php?ctype=gedcom&amp;ged={$this->_gedcom['gedcom']}\" style=\"border-style:none;\"><img src=\"{$highlight}\" {$imgsize[3]} style=\"border:none; padding:2px 6px 2px 2px;\" align=\"right\" /></a>";
	}

///////////////////////////////////////////////////////////////////////////////
// Totals                                                                    //
///////////////////////////////////////////////////////////////////////////////

	function totalIndividuals(){return get_list_size('indilist');}
	function totalIndividualsPercentage(){return $this->_getPercentage(get_list_size('indilist'), 'all', 2);}

	function totalFamilies(){return get_list_size('famlist');}
	function totalFamiliesPercentage(){return $this->_getPercentage(get_list_size('famlist'), 'all', 2);}

	function totalSources(){return get_list_size('sourcelist');}
	function totalSourcesPercentage(){return $this->_getPercentage(get_list_size('sourcelist'), 'all', 2);}

	function totalOtherRecords(){return get_list_size('otherlist');}
	function totalOtherPercentage(){return $this->_getPercentage(get_list_size('otherlist'), 'all', 2);}

	function totalSurnames()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT COUNT(i_surname) AS tot FROM {$TBLPREFIX}individuals WHERE i_file='{$this->_gedcom['id']}' GROUP BY i_surname");
		return count($rows);
	}

	function totalEvents()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file='{$this->_gedcom['id']}' AND d_fact != 'CHAN' AND d_gid != 'HEAD'");
		return $rows[0]['tot'];
	}

	function totalEventsBirth()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file='{$this->_gedcom['id']}' AND d_fact = 'BIRT'");
		return $rows[0]['tot'];
	}

	function totalEventsDeath()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file='{$this->_gedcom['id']}' AND d_fact = 'DEAT'");
		return $rows[0]['tot'];
	}

	function totalEventsMarriage()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file='{$this->_gedcom['id']}' AND d_fact = 'MARR'");
		return $rows[0]['tot'];
	}

	function totalEventsOther()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file='{$this->_gedcom['id']}' AND d_fact != 'BIRT' AND d_fact != 'DEAT' AND d_fact != 'MARR' AND d_fact != 'CHAN' AND d_gid != 'HEAD'");
		return $rows[0]['tot'];
	}

	function totalSexMales()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file='{$this->_gedcom['id']}' AND i_gedcom LIKE '%1 SEX M%'");
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
		$rows = $this->_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file='{$this->_gedcom['id']}' AND i_gedcom LIKE '%1 SEX F%'");
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
		$rows = $this->_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file='{$this->_gedcom['id']}' AND (i_gedcom NOT LIKE '%1 SEX M%' AND i_gedcom NOT LIKE '%1 SEX F%')");
		return $rows[0]['tot'];
	}

	function totalSexUnknownPercentage()
	{
		global $TBLPREFIX;
		return $this->_getPercentage($this->totalSexUnknown(), 'individual');
	}

	function totalLiving()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file='{$this->_gedcom['id']}' AND i_isdead=0");
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
		$rows = $this->_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file='{$this->_gedcom['id']}' AND i_isdead=1");
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
		$rows = $this->_runSQL("SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file='{$this->_gedcom['id']}' AND i_isdead=-1");
		return $rows[0]['tot'];
	}

	function totalMortalityUnknownPercentage()
	{
		global $TBLPREFIX;
		return $this->_getPercentage($this->totalMortalityUnknown(), 'individual');
	}

	function totalUsers()
	{
		return count(getUsers());
	}

	function totalMedia()
	{
		global $GEDCOMS, $GEDCOM, $TBLPREFIX, $MULTI_MEDIA;
		if($MULTI_MEDIA == true){$rows = $this->_runSQL("SELECT COUNT(m_id) AS tot FROM {$TBLPREFIX}media WHERE m_gedfile='{$this->_gedcom['id']}'");return $rows[0]['tot'];}else{return '';}
	}

///////////////////////////////////////////////////////////////////////////////
// Births                                                                    //
///////////////////////////////////////////////////////////////////////////////

	function firstBirth()
	{
		global $TBLPREFIX, $pgv_lang;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'BIRT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		if(displayDetailsById($row['d_gid']))
		{
			ob_start();
			print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $this->_gedcom['gedcom']), false, '', false);
			$indi = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$indi = $pgv_lang['privacy_error'];
		}
		return $indi;
	}

	function firstBirthYear()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'BIRT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		return "<a href=\"calendar.php?action=year&amp;year={$row['d_year']}&amp;ged={$this->_gedcom['gedcom']}\">{$row['d_year']}</a>";
	}

	function firstBirthName()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'BIRT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		$id = '';
		if($SHOW_ID_NUMBERS)
		{
			if($listDir == 'rtl')
			{
				$id = "&nbsp;&nbsp;" . getRLM() . "({$row['d_gid']})" . getRLM();
			}
			else
			{
				$id = "&nbsp;&nbsp;({$row['d_gid']})";
			}
		}
		return "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."{$id}</a>";
	}

	function firstBirthPlace()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'BIRT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		ob_start();
		print_fact_place(get_sub_record(1, '1 BIRT', find_person_record($row['d_gid'])), true, true, true);
		$place = ob_get_contents();
		ob_end_clean();
		return $place;
	}

	function lastBirth()
	{
		global $TBLPREFIX, $pgv_lang;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'BIRT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		if(displayDetailsById($row['d_gid']))
		{
			ob_start();
			print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $this->_gedcom['gedcom']), false, '', false);
			$indi = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$indi = $pgv_lang['privacy_error'];
		}
		return $indi;
	}

	function lastBirthYear()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'BIRT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		return "<a href=\"calendar.php?action=year&amp;year={$row['d_year']}&amp;ged={$this->_gedcom['gedcom']}\">{$row['d_year']}</a>";
	}

	function lastBirthName()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'BIRT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		$id = '';
		if($SHOW_ID_NUMBERS)
		{
			if($listDir == 'rtl')
			{
				$id = "&nbsp;&nbsp;" . getRLM() . "({$row['d_gid']})" . getRLM();
			}
			else
			{
				$id = "&nbsp;&nbsp;({$row['d_gid']})";
			}
		}
		return "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."{$id}</a>";
	}

	function lastBirthPlace()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'BIRT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		ob_start();
		print_fact_place(get_sub_record(1, '1 BIRT', find_person_record($row['d_gid'])), true, true, true);
		$place = ob_get_contents();
		ob_end_clean();
		return $place;
	}

///////////////////////////////////////////////////////////////////////////////
// Deaths                                                                    //
///////////////////////////////////////////////////////////////////////////////

	function firstDeath()
	{
		global $TBLPREFIX, $pgv_lang;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'DEAT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		if(displayDetailsById($row['d_gid']))
		{
			ob_start();
			print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $this->_gedcom['gedcom']), false, '', false);
			$indi = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$indi = $pgv_lang['privacy_error'];
		}
		return $indi;
	}

	function firstDeathYear()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'DEAT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		return "<a href=\"calendar.php?action=year&amp;year={$row['d_year']}&amp;ged={$this->_gedcom['gedcom']}\">{$row['d_year']}</a>";
	}

	function firstDeathName()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'DEAT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		$id = '';
		if($SHOW_ID_NUMBERS)
		{
			if($listDir == 'rtl')
			{
				$id = "&nbsp;&nbsp;" . getRLM() . "({$row['d_gid']})" . getRLM();
			}
			else
			{
				$id = "&nbsp;&nbsp;({$row['d_gid']})";
			}
		}
		return "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."{$id}</a>";
	}

	function firstDeathPlace()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'DEAT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		ob_start();
		print_fact_place(get_sub_record(1, '1 DEAT', find_person_record($row['d_gid'])), true, true, true);
		$place = ob_get_contents();
		ob_end_clean();
		return $place;
	}

	function lastDeath()
	{
		global $TBLPREFIX, $pgv_lang;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'DEAT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		if(displayDetailsById($row['d_gid']))
		{
			ob_start();
			print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $this->_gedcom['gedcom']), false, '', false);
			$indi = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$indi = $pgv_lang['privacy_error'];
		}
		return $indi;
	}

	function lastDeathYear()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'DEAT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		return "<a href=\"calendar.php?action=year&amp;year={$row['d_year']}&amp;ged={$this->_gedcom['gedcom']}\">{$row['d_year']}</a>";
	}

	function lastDeathName()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'DEAT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		$id = '';
		if($SHOW_ID_NUMBERS)
		{
			if($listDir == 'rtl')
			{
				$id = "&nbsp;&nbsp;" . getRLM() . "({$row['d_gid']})" . getRLM();
			}
			else
			{
				$id = "&nbsp;&nbsp;({$row['d_gid']})";
			}
		}
		return "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."{$id}</a>";
	}

	function lastDeathPlace()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_fact = 'DEAT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		ob_start();
		print_fact_place(get_sub_record(1, '1 DEAT', find_person_record($row['d_gid'])), true, true, true);
		$place = ob_get_contents();
		ob_end_clean();
		return $place;
	}

///////////////////////////////////////////////////////////////////////////////
// Lifespan                                                                  //
///////////////////////////////////////////////////////////////////////////////

	// Both Sexes

	function longestLife()
	{
		global $TBLPREFIX, $pgv_lang;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth WHERE birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL ORDER BY age DESC", 1);$row = $rows[0];
		if(displayDetailsById($row['d_gid']))
		{
			ob_start();
			print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $this->_gedcom['gedcom']), false, '', false);
			$indi = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$indi = $pgv_lang['privacy_error'];
		}
		return $indi;
	}

	function longestLifeAge()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth WHERE birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL ORDER BY age DESC", 1);$row = $rows[0];
		return $row['age'];
	}

	function longestLifeName()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth WHERE birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL ORDER BY age DESC", 1);$row = $rows[0];
		$id = '';
		if($SHOW_ID_NUMBERS)
		{
			if($listDir == 'rtl')
			{
				$id = "&nbsp;&nbsp;" . getRLM() . "({$row['d_gid']})" . getRLM();
			}
			else
			{
				$id = "&nbsp;&nbsp;({$row['d_gid']})";
			}
		}
		return "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."{$id}</a>";
	}

	function topTenOldest()
	{
		global $TBLPREFIX, $TEXT_DIRECTION, $pgv_lang;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth WHERE birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL ORDER BY age DESC", 10);
		$top10 = array();
		foreach($rows as $row)
		{
			$top10[] = "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."</a> [{$row['age']} {$pgv_lang['years']}]";
		}
		$top10 = join("; ", $top10);
		if($TEXT_DIRECTION == 'rtl'){$top10 = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $top10);}
		return $top10;
	}

	function topTenOldestList()
	{
		global $TBLPREFIX, $TEXT_DIRECTION, $pgv_lang;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth WHERE birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL ORDER BY age DESC", 10);
		$top10 = array();
		foreach($rows as $row)
		{
			$top10[] = "\t<li><a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."</a> [{$row['age']} {$pgv_lang['years']}]</li>";
		}
		$top10 = join("\n", $top10);
		if($TEXT_DIRECTION == 'rtl'){$top10 = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $top10);}
		return "<ul>\n{$top10}</ul>\n";
	}

	function averageLifespan()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT AVG(death.d_year-birth.d_year) AS age FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth WHERE birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL", 1);$row = $rows[0];
		return sprintf('%d', $row['age']);
	}

	// Male Only

	function longestLifeMale()
	{
		global $TBLPREFIX, $pgv_lang;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX M%' ORDER BY age DESC", 1);$row = $rows[0];
		if(displayDetailsById($row['d_gid']))
		{
			ob_start();
			print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $this->_gedcom['gedcom']), false, '', false);
			$indi = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$indi = $pgv_lang['privacy_error'];
		}
		return $indi;
	}

	function longestLifeMaleAge()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX M%' ORDER BY age DESC", 1);$row = $rows[0];
		return $row['age'];
	}

	function longestLifeMaleName()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX M%' ORDER BY age DESC", 1);$row = $rows[0];
		$id = '';
		if($SHOW_ID_NUMBERS)
		{
			if($listDir == 'rtl')
			{
				$id = "&nbsp;&nbsp;" . getRLM() . "({$row['d_gid']})" . getRLM();
			}
			else
			{
				$id = "&nbsp;&nbsp;({$row['d_gid']})";
			}
		}
		return "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."{$id}</a>";
	}

	function topTenOldestMale()
	{
		global $TBLPREFIX, $TEXT_DIRECTION, $pgv_lang;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX M%' ORDER BY age DESC", 10);
		$top10 = array();
		foreach($rows as $row)
		{
			$top10[] = "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."</a> [{$row['age']} {$pgv_lang['years']}]";
		}
		$top10 = join("; ", $top10);
		if($TEXT_DIRECTION == 'rtl'){$top10 = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $top10);}
		return $top10;
	}

	function topTenOldestMaleList()
	{
		global $TBLPREFIX, $TEXT_DIRECTION, $pgv_lang;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX M%' ORDER BY age DESC", 10);
		$top10 = array();
		foreach($rows as $row)
		{
			$top10[] = "\t<li><a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."</a> [{$row['age']} {$pgv_lang['years']}]</li>";
		}
		$top10 = join("\n", $top10);
		if($TEXT_DIRECTION == 'rtl'){$top10 = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $top10);}
		return "<ul>\n{$top10}</ul>\n";
	}

	function averageLifespanMale()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT AVG(death.d_year-birth.d_year) AS age FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX M%'", 1);$row = $rows[0];
		return sprintf('%d', $row['age']);
	}

	// Male Only

	function longestLifeFemale()
	{
		global $TBLPREFIX, $pgv_lang;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX F%' ORDER BY age DESC", 1);$row = $rows[0];
		if(displayDetailsById($row['d_gid']))
		{
			ob_start();
			print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $this->_gedcom['gedcom']), false, '', false);
			$indi = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$indi = $pgv_lang['privacy_error'];
		}
		return $indi;
	}

	function longestLifeFemaleAge()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX F%' ORDER BY age DESC", 1);$row = $rows[0];
		return $row['age'];
	}

	function longestLifeFemaleName()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX F%' ORDER BY age DESC", 1);$row = $rows[0];
		$id = '';
		if($SHOW_ID_NUMBERS)
		{
			if($listDir == 'rtl')
			{
				$id = "&nbsp;&nbsp;" . getRLM() . "({$row['d_gid']})" . getRLM();
			}
			else
			{
				$id = "&nbsp;&nbsp;({$row['d_gid']})";
			}
		}
		return "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."{$id}</a>";
	}

	function topTenOldestFemale()
	{
		global $TBLPREFIX, $TEXT_DIRECTION, $pgv_lang;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX F%' ORDER BY age DESC", 10);
		$top10 = array();
		foreach($rows as $row)
		{
			$top10[] = "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."</a> [{$row['age']} {$pgv_lang['years']}]";
		}
		$top10 = join("; ", $top10);
		if($TEXT_DIRECTION == 'rtl'){$top10 = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $top10);}
		return $top10;
	}

	function topTenOldestFemaleList()
	{
		global $TBLPREFIX, $TEXT_DIRECTION, $pgv_lang;
		$rows = $this->_runSQL("SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX F%' ORDER BY age DESC", 10);
		$top10 = array();
		foreach($rows as $row)
		{
			$top10[] = "\t<li><a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."</a> [{$row['age']} {$pgv_lang['years']}]</li>";
		}
		$top10 = join("\n", $top10);
		if($TEXT_DIRECTION == 'rtl'){$top10 = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $top10);}
		return "<ul>\n{$top10}</ul>\n";
	}

	function averageLifespanFemale()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT AVG(death.d_year-birth.d_year) AS age FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth, {$TBLPREFIX}individuals AS indi WHERE indi.i_id=birth.d_gid AND birth.d_gid=death.d_gid AND death.d_file='{$this->_gedcom['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL AND i_gedcom LIKE '%1 SEX F%'", 1);$row = $rows[0];
		return sprintf('%d', $row['age']);
	}


///////////////////////////////////////////////////////////////////////////////
// Events                                                                    //
///////////////////////////////////////////////////////////////////////////////

	function firstEvent()
	{
		global $TBLPREFIX, $pgv_lang;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		if(displayDetailsById($row['d_gid']))
		{
			ob_start();
			print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $this->_gedcom['gedcom']), false, '', false);
			$indi = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$indi = $pgv_lang['privacy_error'];
		}
		return $indi;
	}

	function firstEventYear()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		return "<a href=\"calendar.php?action=year&amp;year={$row['d_year']}&amp;ged={$this->_gedcom['gedcom']}\">{$row['d_year']}</a>";
	}

	function firstEventType()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		$event = $this->_getEventType($row['d_fact']);
		if($event == false){return '';}
		return $event;
	}

	function firstEventName()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		$id = '';
		if($SHOW_ID_NUMBERS)
		{
			if($listDir == 'rtl')
			{
				$id = "&nbsp;&nbsp;" . getRLM() . "({$row['d_gid']})" . getRLM();
			}
			else
			{
				$id = "&nbsp;&nbsp;({$row['d_gid']})";
			}
		}
		return "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."{$id}</a>";
	}

	function firstEventPlace()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC", 1);$row = $rows[0];
		ob_start();
		print_fact_place(get_sub_record(1, "1 {$row['d_fact']}", find_gedcom_record($row['d_gid'])), true, true, true);
		$place = ob_get_contents();
		ob_end_clean();
		return $place;
	}

	function lastEvent()
	{
		global $TBLPREFIX, $pgv_lang;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		if(displayDetailsById($row['d_gid']))
		{
			ob_start();
			print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $this->_gedcom['gedcom']), false, '', false);
			$indi = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$indi = $pgv_lang['privacy_error'];
		}
		return $indi;
	}

	function lastEventYear()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		return "<a href=\"calendar.php?action=year&amp;year={$row['d_year']}&amp;ged={$this->_gedcom['gedcom']}\">{$row['d_year']}</a>";
	}

	function lastEventType()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		$event = $this->_getEventType($row['d_fact']);
		if($event == false){return '';}
		return $event;
	}

	function lastEventName()
	{
		global $TBLPREFIX, $SHOW_ID_NUMBERS, $listDir;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		$id = '';
		if($SHOW_ID_NUMBERS)
		{
			if($listDir == 'rtl')
			{
				$id = "&nbsp;&nbsp;" . getRLM() . "({$row['d_gid']})" . getRLM();
			}
			else
			{
				$id = "&nbsp;&nbsp;({$row['d_gid']})";
			}
		}
		return "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$this->_gedcom['gedcom']}\">".get_person_name($row['d_gid'])."{$id}</a>";
	}

	function lastEventPlace()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$this->_gedcom['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC", 1);$row = $rows[0];
		ob_start();
		print_fact_place(get_sub_record(1, "1 {$row['d_fact']}", find_gedcom_record($row['d_gid'])), true, true, true);
		$place = ob_get_contents();
		ob_end_clean();
		return $place;
	}

///////////////////////////////////////////////////////////////////////////////
// Family Size                                                               //
///////////////////////////////////////////////////////////////////////////////

	function largestFamily()
	{
		global $TBLPREFIX, $pgv_lang;
		$rows = $this->_runSQL("SELECT f_numchil, f_id FROM {$TBLPREFIX}families WHERE f_file='{$this->_gedcom['id']}' ORDER BY f_numchil DESC", 1);$row = $rows[0];
		if(displayDetailsById($row['f_id'], 'FAM'))
		{
			ob_start();
			print_list_family($row['f_id'], array(get_family_descriptor($row['f_id']), $this->_gedcom['gedcom']), false, '', false);
			$fam = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$fam = $pgv_lang['privacy_error'];
		}
		return $fam;
	}

	function largestFamilySize()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT f_numchil, f_id FROM {$TBLPREFIX}families WHERE f_file='{$this->_gedcom['id']}' ORDER BY f_numchil DESC", 1);$row = $rows[0];
		return $row['f_numchil'];
	}

	function largestFamilyName()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT f_numchil, f_id FROM {$TBLPREFIX}families WHERE f_file='{$this->_gedcom['id']}' ORDER BY f_numchil DESC", 1);$row = $rows[0];
		return "<a href=\"family.php?famid={$row['f_id']}&amp;ged={$this->_gedcom['gedcom']}\">".get_family_descriptor($row['f_id']).'</a>';
	}

	function topTenLargestFamily()
	{
		global $TBLPREFIX, $TEXT_DIRECTION, $pgv_lang;
		$rows = $this->_runSQL("SELECT f_numchil, f_id FROM {$TBLPREFIX}families WHERE f_file='{$this->_gedcom['id']}' ORDER BY f_numchil DESC", 10);
		$top10 = array();
		foreach($rows as $row)
		{
			$top10[] = "<a href=\"family.php?famid={$row['f_id']}&amp;ged={$this->_gedcom['gedcom']}\">".get_family_descriptor($row['f_id'])."</a> [{$row['f_numchil']} {$pgv_lang['children']}]";
		}
		$top10 = join("; ", $top10);
		if($TEXT_DIRECTION == 'rtl'){$top10 = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $top10);}
		return $top10;
	}

	function topTenLargestFamilyList()
	{
		global $TBLPREFIX, $TEXT_DIRECTION, $pgv_lang;
		$rows = $this->_runSQL("SELECT f_numchil, f_id FROM {$TBLPREFIX}families WHERE f_file='{$this->_gedcom['id']}' ORDER BY f_numchil DESC", 10);
		$top10 = array();
		foreach($rows as $row)
		{
			$top10[] = "\t<li><a href=\"family.php?famid={$row['f_id']}&amp;ged={$this->_gedcom['gedcom']}\">".get_family_descriptor($row['f_id'])."</a> [{$row['f_numchil']} {$pgv_lang['children']}]</li>";
		}
		$top10 = join("\n", $top10);
		if($TEXT_DIRECTION == 'rtl'){$top10 = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $top10);}
		return "<ul>\n{$top10}</ul>\n";
	}

	function averageChildren()
	{
		global $TBLPREFIX;
		$rows = $this->_runSQL("SELECT AVG(f_numchil) AS tot FROM {$TBLPREFIX}families WHERE f_file='{$this->_gedcom['id']}'", 1);$row = $rows[0];
		return sprintf('%.2f', $row['tot']);
	}

///////////////////////////////////////////////////////////////////////////////
// Contact                                                                   //
///////////////////////////////////////////////////////////////////////////////

	function contactWebmaster()
	{
		global $pgv_lang;
		if($GLOBALS['SUPPORT_METHOD'] == 'none'){return '';}
		$user = getUser($GLOBALS['WEBMASTER_EMAIL']);
		if(($user) && ($GLOBALS['SUPPORT_METHOD'] != 'mailto'))
		{
			$contact = "<a href=\"javascript:;\" accesskey=\"{$pgv_lang['accesskey_contact']}\" onclick=\"message('{$GLOBALS['WEBMASTER_EMAIL']}', '{$GLOBALS['SUPPORT_METHOD']}'); return false;\">{$user['firstname']} {$user['lastname']}</a>";
		}
		else
		{
			$contact = '<a href="mailto:';
			if($user)
			{
				$contact .= "{$user['email']}\" accesskey=\"{$pgv_lang['accesskey_contact']}\">{$user['firstname']} {$user['lastname']}</a>";
			}
			else
			{
				$contact .= "{$GLOBALS['WEBMASTER_EMAIL']}\">{$GLOBALS['WEBMASTER_EMAIL']}</a>";
			}
		}
		return $contact;
	}

	function contactGedcom()
	{
		global $pgv_lang;
		if($GLOBALS['CONTACT_METHOD'] == 'none'){return '';}
		$user = getUser($GLOBALS['CONTACT_EMAIL']);
		if(($user) && ($GLOBALS['CONTACT_METHOD'] != 'mailto'))
		{
			$contact = "<a href=\"javascript:;\" accesskey=\"{$pgv_lang['accesskey_contact']}\" onclick=\"message('{$GLOBALS['CONTACT_EMAIL']}', '{$GLOBALS['SUPPORT_METHOD']}'); return false;\">{$user['firstname']} {$user['lastname']}</a>";
		}
		else
		{
			$contact = '<a href="mailto:';
			if($user)
			{
				$contact .= "{$user['email']}\" accesskey=\"{$pgv_lang['accesskey_contact']}\">{$user['firstname']} {$user['lastname']}</a>";
			}
			else
			{
				$contact .= "{$GLOBALS['CONTACT_EMAIL']}\">{$GLOBALS['CONTACT_EMAIL']}</a>";
			}
		}
		return $contact;
	}

///////////////////////////////////////////////////////////////////////////////
// Date & Time                                                               //
///////////////////////////////////////////////////////////////////////////////

	function serverDate(){return get_changed_date(date('j').' '.strtoupper(date('M')).' '.date('Y'));}

	function serverTime(){return date('g:i a');}

	function serverTime24(){return date('G:i');}

	function serverTimezone(){return date('T');}

	function browserDate(){return get_changed_date(date('j', time()-$_SESSION["timediff"]).' '.strtoupper(date('M', time()-$_SESSION["timediff"])).' '.date('Y', time()-$_SESSION["timediff"]));}

	function browserTime(){return date('g:i a', time()-$_SESSION["timediff"]);}

	function browserTime24(){return date('G:i', time()-$_SESSION["timediff"]);}

	function browserTimezone(){return date('T', time()-$_SESSION["timediff"]);}

///////////////////////////////////////////////////////////////////////////////
// Misc.                                                                     //
///////////////////////////////////////////////////////////////////////////////

	function commonSurnames()
	{
		$surnames = get_common_surnames_index($this->_gedcom['gedcom']);
		if(count($surnames) > 0)
		{
			$common = array();
			foreach($surnames as $indexval=>$surname){if(stristr($surname['name'], '@N.N') === false){$common[] = '<a href="indilist.php?surname='.urlencode($surname['name'])."&amp;ged={$this->_gedcom['gedcom']}\">".PrintReady($surname['name']).'</a>';}}
			return join(', ', $common);
		}
		return '';
	}

///////////////////////////////////////////////////////////////////////////////
// Tools                                                                     //
///////////////////////////////////////////////////////////////////////////////

	function _getEventType($type)
	{
		global $pgv_lang;
		$eventTypes = array(
			'BIRT'=>$pgv_lang['htmlplus_block_birth'],
			'DEAT'=>$pgv_lang['htmlplus_block_death'],
			'MARR'=>$pgv_lang['htmlplus_block_marrage'],
			'ADOP'=>$pgv_lang['htmlplus_block_adoption'],
			'BURI'=>$pgv_lang['htmlplus_block_burial'],
			'CENS'=>$pgv_lang['htmlplus_block_census']
		);
		if(isset($eventTypes[$type])){return $eventTypes[$type];}
		return false;
	}

	function _getPercentage($total, $type)
	{
		$per = null;
		switch($type)
		{
			default:
			case 'all': $per = round(100 * $total / (get_list_size('indilist') + get_list_size('famlist') + get_list_size('sourcelist') + get_list_size('otherlist')), 2);break;
			case 'individual': $per = round(100 * $total / get_list_size('indilist'), 2);break;
			case 'family': $per = round(100 * $total / get_list_size('famlist'), 2);break;
			case 'source': $per = round(100 * $total / get_list_size('sourcelist'), 2);break;
			case 'other': $per = round(100 * $total / get_list_size('otherlist'), 2);break;
		}
		return $per;
	}

	function _runSQL($sql, $count=0)
	{
		static $cache = array();
		$id = md5($sql)."_{$count}";
		if(isset($cache[$id])){return $cache[$id];}
		$rows = array();
		$tempsql = dbquery($sql, true, $count);
		if(!DB::isError($tempsql))
		{
			$res =& $tempsql;
			while($row =& $res->fetchRow(DB_FETCHMODE_ASSOC))
			{
				$rows[] = $row;
			}
			$res->free();
			$cache[$id] = $rows;
			return $rows;
		}
		return null;
	}
}
?>
