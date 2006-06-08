<?php
/**
 * Advanced HTML Block
 *
 * This block will print advanced HTML text with keyword support entered by an admin
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
 * @version $Id$
 * @author Patrick Kellum
 * @package PhpGedView
 * @subpackage Blocks
 */

require_once 'includes/functions_print_lists.php';

$PGV_BLOCKS['print_htmlplus_block']['name']			= $pgv_lang['htmlplus_block_name'];
$PGV_BLOCKS['print_htmlplus_block']['descr']		= 'htmlplus_block_descr';
$PGV_BLOCKS['print_htmlplus_block']['canconfig']	= true;
$PGV_BLOCKS['print_htmlplus_block']['config']		= array(
	'title'=>'',
	'html'=>"{$pgv_lang['html_block_sample_part1']} <img src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}\" alt=\"{$pgv_lang['config_block']}\" /> {$pgv_lang['html_block_sample_part2']}",
	'gedcom'=>'__current__'
);

function print_htmlplus_block($block=true, $config='', $side, $index)
{
	global
		$ALLOW_CHANGE_GEDCOM,
		$command,
		$COMMON_NAMES_THRESHOLD,
		$factarray,
		$GEDCOM,
		$GEDCOMS,
		$HTML_BLOCK_COUNT,
		$monthtonum, // Set in index.php
		$PGV_BLOCKS,
		$PGV_IMAGE_DIR,
		$PGV_IMAGES,
		$pgv_lang,
		$TBLPREFIX,
		$TEXT_DIRECTION,
		$DEFAULT_GEDCOM
	;
	if(empty($config))
	{
		$config = $PGV_BLOCKS['print_htmlplus_block']['config'];
	}
	// config sanity check
	else
	{
		foreach($PGV_BLOCKS['print_htmlplus_block']['config'] as $k=>$v)
		{
			if(!isset($config[$k]))
			{
				$config[$k] = $v;
			}
		}
	}
	if(!isset($HTML_BLOCK_COUNT))
	{
		$HTML_BLOCK_COUNT = 0;
	}
	$HTML_BLOCK_COUNT++;

	// make working copies of the block's title and the HTML
	$configTitle = $config['title'];
	$configHtml = $config['html'];

	// resolve references to $pgv_lang variables first
	$pos = strpos($configTitle, "#pgv_lang[");
	if ($pos !== false) {
		$configTitle = print_text($configTitle, 0, 2);
	}
	$pos = strpos($configHtml, "#pgv_lang[");
	if ($pos !== false) {
		$configHtml = print_text($configHtml, 0, 2);
	}

	// find references to other built-in variables
	$ct = preg_match_all("/#(.+)#/U", "{$configTitle} {$configHtml}", $match);
	$tags = array_flip($match[1]);

	$keywords = array();
	$dynamic = array();
	// build up the month string
	static $months = '';
	if($months == '')
	{
		foreach($monthtonum as $month=>$mon)
		{
			$months .= ", '{$month}'";
		}
		$months = strtoupper($months);
	}
	/*
	 * Select GEDCOM
	 */
	$CURRENT_GEDCOM = $GEDCOM;
	switch($config['gedcom'])
	{
		case '__current__':{break;}
		case '__default__':
		{
			if($DEFAULT_GEDCOM == '')
			{
				foreach($GEDCOMS as $gedid=>$ged)
				{
					$GEDCOM = $gedid;
					break;
				}
			}
			else
			{
				$GEDCOM = $DEFAULT_GEDCOM;
			}
			break;
		}
		default:
		{
			if(check_for_import($config['gedcom'])){$GEDCOM = $config['gedcom'];}
			break;
		}
	}
	/*
	 * GEDCOM File tags
	 */
	if(
		isset($tags['GEDCOM'])
	)
	{
		$keywords[] = '#GEDCOM#';
		$dynamic[] = $GEDCOM;
		unset($tags['GEDCOM']);
	}
	if(
		isset($tags['GEDCOM_ID'])
	)
	{
		$keywords[] = '#GEDCOM_ID#';
		$dynamic[] = $GEDCOMS[$GEDCOM]['id'];
		unset($tags['GEDCOM_ID']);
	}
	if(
		isset($tags['GEDCOM_TITLE'])
	)
	{
		$keywords[] = '#GEDCOM_TITLE#';
		$dynamic[] = PrintReady($GEDCOMS[$GEDCOM]['title']);
	}
	if(
		isset($tags['CREATED_SOFTWARE']) ||
		isset($tags['CREATED_VERSION']) ||
		isset($tags['CREATED_DATE'])
	)
	{
		$head = find_gedcom_record('HEAD');
	}
	if(
		isset($tags['CREATED_SOFTWARE']) ||
		isset($tags['CREATED_VERSION'])
	)
	{
		$ct = preg_match("/1 SOUR (.*)/", $head, $match);
		if($ct > 0)
		{
			$softrec = get_sub_record(1, '1 SOUR', $head);
			$tt = preg_match("/2 NAME (.*)/", $softrec, $tmatch);
			if($tt > 0)
			{
				$title = trim($tmatch[1]);
			}
			else
			{
				$title = trim($match[1]);
			}
			if (!empty($title))
			{
				$tt = preg_match("/2 VERS (.*)/", $softrec, $tmatch);
				if ($tt > 0)
				{
					$version = trim($tmatch[1]);
				}
				else
				{
					$version = '';
				}
			}
			if(isset($tags['CREATED_SOFTWARE']))
			{
				$keywords[] = '#CREATED_SOFTWARE#';
				$dynamic[] = $title;
				unset($tags['CREATED_SOFTWARE']);
			}
			if(isset($tags['CREATED_VERSION']))
			{
				// fix version string in Family Tree Maker 2005 - (12.0.345 SP1) August 20, 2004
				if($title == 'Family Tree Maker 2005 for Windows')
				{
					$version = str_replace('Family Tree Maker 2005 ', '', $version);
				}
				// fix version string in Family Tree Maker 2006 - (13.0.281)
				if($version == 'Family Tree Maker (13.0.281)')
				{
					$version = '(13.0.281)';
				}
				$keywords[] = '#CREATED_VERSION#';
				$dynamic[] = $version;
				unset($tags['CREATED_VERSION']);
			}
		}
	}
	if(
		isset($tags['CREATED_DATE'])
	)
	{
		$ct = preg_match("/1 DATE (.*)/", $head, $match);
		if($ct > 0)
		{
			$keywords[] = '#CREATED_DATE#';
			$dynamic[] = get_changed_date(trim($match[1]));
			unset($tags['CREATED_DATE']);
		}
	}
	if(
		isset($tags['GEDCOM_UPDATED'])
	)
	{
		$sql = "SELECT d_year, d_month, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$GEDCOMS[$GEDCOM]['id']}' AND d_fact = 'CHAN' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC";
		$rows = print_htmlplus_block_sql($sql, 1);
		// if never changed, use created date
		if(!isset($rows[0]))
		{
			$head = find_gedcom_record('HEAD');
			$ct = preg_match("/1 DATE (.*)/", $head, $match);
			if($ct > 0)
			{
				$keywords[] = '#GEDCOM_UPDATED#';
				$dynamic[] = get_changed_date(trim($match[1]));
			}
		}
		else
		{
			$keywords[] = '#GEDCOM_UPDATED#';
			$dynamic[] = get_changed_date("{$rows[0]['d_day']} {$rows[0]['d_month']} {$rows[0]['d_year']}");
		}
		unset($tags['GEDCOM_UPDATED']);
	}
	/*
	 * Media Tags
	 */
	if(
		isset($tags['HIGHLIGHT'])
	)
	{
		$highlight = false;
		if(file_exists("images/gedcoms/{$GEDCOM}.jpg"))
		{
			$highlight = "images/gedcoms/{$GEDCOM}.jpg";
		}
		if(file_exists("images/gedcoms/{$GEDCOM}.png"))
		{
			$highlight = "images/gedcoms/{$GEDCOM}.png";
		}
		if($highlight != false)
		{
			$imgsize = findImageSize($highlight);
			$keywords[] = '#HIGHLIGHT#';
			$dynamic[] = "<a href=\"index.php?command=gedcom&amp;ged={$GEDCOM}\" style=\"border-style:none;\"><img src=\"{$highlight}\" {$imgsize[3]} style=\"border:none; padding:2px 6px 2px 2px;\" /></a>";
		}
		else
		{
			$keywords[] = '#HIGHLIGHT#';
			$dynamic[] = '';
		}
		unset($tags['HIGHLIGHT']);
	}
	/*
	 *
	 */
	if(
		isset($tags['COMMON_SURNAMES'])
	)
	{
		$surnames = get_common_surnames_index($GEDCOM);
		if(count($surnames) > 0)
		{
			$common = array();
			foreach($surnames as $indexval=>$surname)
			{
				if(stristr($surname['name'], '@N.N') === false)
				{
					$common[] = '<a href="indilist.php?surname='.urlencode($surname['name'])."&amp;ged={$GEDCOM}\">".PrintReady($surname['name']).'</a>';
				}
			}
			$keywords[] = '#COMMON_SURNAMES#';
			$dynamic[] = join(', ', $common);
		}
		unset($tags['COMMON_SURNAMES']);
	}
	if(
		isset($tags['TOTAL_INDI'])
	)
	{
		$keywords[] = '#TOTAL_INDI#';
		$dynamic[] = get_list_size('indilist');
		unset($tags['TOTAL_INDI']);
	}
	if(
		isset($tags['TOTAL_FAM'])
	)
	{
		$keywords[] = '#TOTAL_FAM#';
		$dynamic[] = get_list_size('famlist');
		unset($tags['TOTAL_FAM']);
	}
	if(
		isset($tags['TOTAL_SOUR'])
	)
	{
		$keywords[] = '#TOTAL_SOUR#';
		$dynamic[] = get_list_size('sourcelist');
		unset($tags['TOTAL_SOUR']);
	}
	if(
		isset($tags['TOTAL_OTHER'])
	)
	{
		$keywords[] = '#TOTAL_OTHER#';
		$dynamic[] = get_list_size('otherlist');
		unset($tags['TOTAL_OTHER']);
	}
	if(
		isset($tags['TOTAL_SURNAMES'])
	)
	{
		//-- total unique surnames
		$sql = "SELECT COUNT(i_surname) FROM {$TBLPREFIX}individuals WHERE i_file='{$GEDCOMS[$GEDCOM]['id']}' GROUP BY i_surname";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#TOTAL_SURNAMES#';
		$dynamic[] = count($rows);
		unset($tags['TOTAL_SURNAMES']);
	}
	if(
		isset($tags['TOTAL_EVENTS'])
	)
	{
		//-- total events
		$sql = "SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file='{$GEDCOMS[$GEDCOM]['id']}' AND d_fact != 'CHAN' AND d_gid != 'HEAD'";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#TOTAL_EVENTS#';
		$dynamic[] = $rows[0]['tot'];
		unset($tags['TOTAL_EVENTS']);
	}
	if(
		isset($tags['TOTAL_EVENTS_BIRTH'])
	)
	{
		$sql = "SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file='{$GEDCOMS[$GEDCOM]['id']}' AND d_fact = 'BIRT'";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#TOTAL_EVENTS_BIRTH#';
		$dynamic[] = $rows[0]['tot'];
		unset($tags['TOTAL_EVENTS_BIRTH']);
	}
	if(
		isset($tags['TOTAL_EVENTS_DEATH'])
	)
	{
		$sql = "SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file='{$GEDCOMS[$GEDCOM]['id']}' AND d_fact = 'DEAT'";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#TOTAL_EVENTS_DEATH#';
		$dynamic[] = $rows[0]['tot'];
		unset($tags['TOTAL_EVENTS_DEATH']);
	}
	if(
		isset($tags['TOTAL_EVENTS_MARRIAGE'])
	)
	{
		$sql = "SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file='{$GEDCOMS[$GEDCOM]['id']}' AND d_fact = 'MARR'";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#TOTAL_EVENTS_MARRIAGE#';
		$dynamic[] = $rows[0]['tot'];
		unset($tags['TOTAL_EVENTS_MARRIAGE']);
	}
	if(
		isset($tags['TOTAL_EVENTS_OTHER'])
	)
	{
		$sql = "SELECT COUNT(d_gid) AS tot FROM {$TBLPREFIX}dates WHERE d_file='{$GEDCOMS[$GEDCOM]['id']}' AND d_fact != 'BIRT' AND d_fact != 'DEAT' AND d_fact != 'MARR' AND d_fact != 'CHAN' AND d_gid != 'HEAD'";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#TOTAL_EVENTS_OTHER#';
		$dynamic[] = $rows[0]['tot'];
		unset($tags['TOTAL_EVENTS_OTHER']);
	}
	if(
		isset($tags['TOTAL_MALES'])
	)
	{
		$sql = "SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file='{$GEDCOMS[$GEDCOM]['id']}' AND i_gedcom LIKE '%1 SEX M%'";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#TOTAL_MALES#';
		$dynamic[] = $rows[0]['tot'];
		unset($tags['TOTAL_MALES']);
	}
	if(
		isset($tags['TOTAL_FEMALES'])
	)
	{
		$sql = "SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file='{$GEDCOMS[$GEDCOM]['id']}' AND i_gedcom LIKE '%1 SEX F%'";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#TOTAL_FEMALES#';
		$dynamic[] = $rows[0]['tot'];
		unset($tags['TOTAL_FEMALES']);
	}
	if(
		isset($tags['TOTAL_UNKNOWN_SEX'])
	)
	{
		$sql = "SELECT COUNT(i_id) AS tot FROM {$TBLPREFIX}individuals WHERE i_file='{$GEDCOMS[$GEDCOM]['id']}' AND (i_gedcom NOT LIKE '%1 SEX M%' AND i_gedcom NOT LIKE '%1 SEX F%')";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#TOTAL_UNKNOWN_SEX#';
		$dynamic[] = $rows[0]['tot'];
		unset($tags['TOTAL_UNKNOWN_SEX']);
	}
	if(
		isset($tags['TOTAL_USERS'])
	)
	{
		$keywords[] = '#TOTAL_USERS#';
		$dynamic[] = count(getUsers());
		unset($tags['TOTAL_USERS']);
	}
	/*
	 * First Birth Tags
	 */
	if(
		isset($tags['FIRST_BIRTH']) ||
		isset($tags['FIRST_BIRTH_YEAR']) ||
		isset($tags['FIRST_BIRTH_NAME'])
	)
	{
		// NOTE: Get earliest birth year
		$sql = "SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$GEDCOMS[$GEDCOM]['id']}' AND d_fact = 'BIRT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC";
		$rows = print_htmlplus_block_sql($sql, 1);
		$row = $rows[0];
		if(isset($tags['FIRST_BIRTH']))
		{
			if(displayDetailsById($row['d_gid']))
			{
				ob_start();
				print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $GEDCOM), false, '', false);
				$keywords[] = '#FIRST_BIRTH#';
				$dynamic[] = ob_get_contents();
				ob_end_clean();

			}
			else
			{
				$keywords[] = '#FIRST_BIRTH#';
				$dynamic[] = $pgv_lang['privacy_error'];
			}
			unset($tags['FIRST_BIRTH']);
		}
		if(isset($tags['FIRST_BIRTH_YEAR']))
		{
			$keywords[] = '#FIRST_BIRTH_YEAR#';
			$dynamic[] = "<a href=\"calendar.php?action=year&amp;year={$row['d_year']}&amp;ged={$GEDCOM}\">{$row['d_year']}</a>";
			unset($tags['FIRST_BIRTH_YEAR']);
		}
		if(isset($tags['FIRST_BIRTH_NAME']))
		{
			$keywords[] = '#FIRST_BIRTH_NAME#';
			$dynamic[] = "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$GEDCOM}\">".get_person_name($row['d_gid']).'</a>';
			unset($tags['FIRST_BIRTH_NAME']);
		}
	}
	/*
	 * Last Birth Tags
	 */
	if(
		isset($tags['LAST_BIRTH']) ||
		isset($tags['LAST_BIRTH_YEAR']) ||
		isset($tags['LAST_BIRTH_NAME'])
	)
	{
		// NOTE: Get the latest birth year
		$sql = "SELECT d_gid, d_year, d_mon, d_day FROM {$TBLPREFIX}dates WHERE d_file = '{$GEDCOMS[$GEDCOM]['id']}' AND d_fact = 'BIRT' AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC";
		$rows = print_htmlplus_block_sql($sql, 1);
		$row = $rows[0];
		if(isset($tags['LAST_BIRTH']))
		{
			if(displayDetailsById($row['d_gid']))
			{
				ob_start();
				print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $GEDCOM), false, '', false);
				$keywords[] = '#LAST_BIRTH#';
				$dynamic[] = ob_get_contents();
				ob_end_clean();

			}
			else
			{
				$keywords[] = '#LAST_BIRTH#';
				$dynamic[] = $pgv_lang['privacy_error'];
			}
			unset($tags['LAST_BIRTH']);
		}
		if(isset($tags['LAST_BIRTH_YEAR']))
		{
			$keywords[] = '#LAST_BIRTH_YEAR#';
			$dynamic[] = "<a href=\"calendar.php?action=year&amp;year={$row['d_year']}&amp;ged={$GEDCOM}\">{$row['d_year']}</a>";
			unset($tags['LAST_BIRTH_YEAR']);
		}
		if(isset($tags['LAST_BIRTH_NAME']))
		{
			$keywords[] = '#LAST_BIRTH_NAME#';
			$dynamic[] = "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$GEDCOM}\">".get_person_name($row['d_gid']).'</a>';
			unset($tags['LAST_BIRTH_NAME']);
		}
	}
	/*
	 * Lifespan Tags
	 */
	if(
		isset($tags['LONG_LIFE']) ||
		isset($tags['LONG_LIFE_AGE']) ||
		isset($tags['LONG_LIFE_NAME']) ||
		isset($tags['TOP10_OLDEST']) ||
		isset($tags['TOP10_OLDEST_LIST'])
	)
	{
		//-- get the person who lived the longest
		$sql = "SELECT death.d_year-birth.d_year AS age, death.d_gid FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth WHERE birth.d_gid=death.d_gid AND death.d_file='{$GEDCOMS[$GEDCOM]['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL ORDER BY age DESC";
		$rows = print_htmlplus_block_sql($sql, 10);
		$row = $rows[0];
		if(isset($tags['LONG_LIFE']))
		{
			if(displayDetailsById($row['d_gid']))
			{
				ob_start();
				print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $GEDCOM), false, '', false);
				$keywords[] = '#LONG_LIFE#';
				$dynamic[] = ob_get_contents();
				ob_end_clean();
			}
			else
			{
				$keywords[] = '#LONG_LIFE#';
				$dynamic[] = $pgv_lang['privacy_error'];
			}
			unset($tags['LONG_LIFE']);
		}
		if(isset($tags['LONG_LIFE_AGE']))
		{
			$keywords[] = '#LONG_LIFE_AGE#';
			$dynamic[] = $row['age'];
			unset($tags['LONG_LIFE_AGE']);
		}
		if(isset($tags['LONG_LIFE_NAME']))
		{
			$keywords[] = '#LONG_LIFE_NAME#';
			$dynamic[] = "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$GEDCOM}\">".get_person_name($row['d_gid']).'</a>';
			unset($tags['LONG_LIFE_NAME']);
		}
		if(isset($tags['TOP10_OLDEST']))
		{
			$top10 = array();
			foreach($rows as $row)
			{
				$top10[] = "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$GEDCOM}\">".get_person_name($row['d_gid'])."</a> [{$row['age']} {$pgv_lang['years']}]";
			}
			$keywords[] = '#TOP10_OLDEST#';
			$tempText = join("; ", $top10);
			if ($TEXT_DIRECTION=='rtl') {
				$tempText = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $tempText);
			}
			$dynamic[] = $tempText;
			unset($tags['TOP10_OLDEST']);
		}
		if(isset($tags['TOP10_OLDEST_LIST']))
		{
			$top10 = array();
			foreach($rows as $row)
			{
				$top10[] = "<li><a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$GEDCOM}\">".get_person_name($row['d_gid'])."</a> [{$row['age']} {$pgv_lang['years']}]</li>";
			}
			$keywords[] = '#TOP10_OLDEST_LIST#';
			$tempText = join("\n", $top10);
			if ($TEXT_DIRECTION=='rtl') {
				$tempText = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $tempText);
			}
			$dynamic[] = "<ul>{$tempText}</ul>";
			unset($tags['TOP10_OLDEST_LIST']);
		}
	}
	if(isset($tags['AVG_LIFE']))
	{
		//-- avg age at death
		$sql = "SELECT AVG(death.d_year-birth.d_year) AS age FROM {$TBLPREFIX}dates AS death, {$TBLPREFIX}dates AS birth WHERE birth.d_gid=death.d_gid AND death.d_file='{$GEDCOMS[$GEDCOM]['id']}' AND birth.d_file=death.d_file AND birth.d_fact='BIRT' AND death.d_fact='DEAT' AND birth.d_year>0 AND death.d_year>0 AND birth.d_type IS NULL AND death.d_type IS NULL";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#AVG_LIFE#';
		$dynamic[] = sprintf('%d', $rows[0]['age']);
		unset($tags['AVG_LIFE']);
	}
	/*
	 * First Event Tags
	 */
	if(
		isset($tags['FIRST_EVENT']) ||
		isset($tags['FIRST_EVENT_YEAR']) ||
		isset($tags['FIRST_EVENT_TYPE']) ||
		isset($tags['FIRST_EVENT_NAME'])
	)
	{
		$sql = "SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$GEDCOMS[$GEDCOM]['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year ASC, d_mon ASC, d_day ASC";
		$rows = print_htmlplus_block_sql($sql, 1);
		$row = $rows[0];
		if(isset($tags['FIRST_EVENT']))
		{
			if(displayDetailsById($row['d_gid']))
			{
				ob_start();
				print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $GEDCOM), false, '', false);
				$keywords[] = '#FIRST_EVENT#';
				$dynamic[] = ob_get_contents();
				ob_end_clean();

			}
			else
			{
				$keywords[] = '#FIRST_EVENT#';
				$dynamic[] = $pgv_lang['privacy_error'];
			}
			unset($tags['FIRST_EVENT']);
		}
		if(isset($tags['FIRST_EVENT_YEAR']))
		{
			$keywords[] = '#FIRST_EVENT_YEAR#';
			$dynamic[] = "<a href=\"calendar.php?action=year&amp;year={$row['d_year']}&amp;ged={$GEDCOM}\">{$row['d_year']}</a>";
			unset($tags['FIRST_EVENT_YEAR']);
		}
		if(isset($tags['FIRST_EVENT_TYPE']))
		{
			$eventTypes = array(
				'BIRT'=>'birth',
				'DEAT'=>'death',
				'MARR'=>'marrage',
				'ADOP'=>'adoption',
				'BURI'=>'burial',
				'CENS'=>'census added'
			);
			$keywords[] = '#FIRST_EVENT_TYPE#';
			$dynamic[] = $eventTypes[$row['d_fact']];
			unset($tags['FIRST_EVENT_TYPE']);
		}
		if(isset($tags['FIRST_EVENT_NAME']))
		{
			$keywords[] = '#FIRST_EVENT_NAME#';
			$dynamic[] = "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$GEDCOM}\">".get_person_name($row['d_gid']).'</a>';
			unset($tags['FIRST_EVENT_NAME']);
		}
	}
	/*
	 * Last Event Tags
	 */
	if(
		isset($tags['LAST_EVENT']) ||
		isset($tags['LAST_EVENT_YEAR']) ||
		isset($tags['LAST_EVENT_TYPE']) ||
		isset($tags['LAST_EVENT_NAME'])
	)
	{
		$sql = "SELECT d_gid, d_year, d_month, d_mon, d_day, d_fact FROM {$TBLPREFIX}dates WHERE d_file = '{$GEDCOMS[$GEDCOM]['id']}' AND d_gid != 'HEAD' AND (d_fact = 'BIRT' OR d_fact = 'DEAT' OR d_fact = 'MARR' OR d_fact = 'ADOP' OR d_fact = 'BURI') AND d_year != '0' AND d_type IS NULL ORDER BY d_year DESC, d_mon DESC, d_day DESC";
		$rows = print_htmlplus_block_sql($sql, 1);
		$row = $rows[0];
		if(isset($tags['LAST_EVENT']))
		{
			if(displayDetailsById($row['d_gid']))
			{
				ob_start();
				print_list_person($row['d_gid'], array(get_person_name($row['d_gid']), $GEDCOM), false, '', false);
				$keywords[] = '#LAST_EVENT#';
				$dynamic[] = ob_get_contents();
				ob_end_clean();

			}
			else
			{
				$keywords[] = '#LAST_EVENT#';
				$dynamic[] = $pgv_lang['privacy_error'];
			}
			unset($tags['LAST_EVENT']);
		}
		if(isset($tags['LAST_EVENT_YEAR']))
		{
			$keywords[] = '#LAST_EVENT_YEAR#';
			$dynamic[] = "<a href=\"calendar.php?action=year&amp;year={$row['d_year']}&amp;ged={$GEDCOM}\">{$row['d_year']}</a>";
			unset($tags['LAST_EVENT_YEAR']);
		}
		if(isset($tags['LAST_EVENT_TYPE']))
		{
			$eventTypes = array(
				'BIRT'=>'birth',
				'DEAT'=>'death',
				'MARR'=>'marrage',
				'ADOP'=>'adoption',
				'BURI'=>'burial',
				'CENS'=>'census added'
			);
			$keywords[] = '#LAST_EVENT_TYPE#';
			$dynamic[] = $eventTypes[$row['d_fact']];
			unset($tags['LAST_EVENT_TYPE']);
		}
		if(isset($tags['LAST_EVENT_NAME']))
		{
			$keywords[] = '#LAST_EVENT_NAME#';
			$dynamic[] = "<a href=\"individual.php?pid={$row['d_gid']}&amp;ged={$GEDCOM}\">".get_person_name($row['d_gid']).'</a>';
			unset($tags['LAST_EVENT_NAME']);
		}
	}
	/*
	 * Family Size Tags
	 */
	if(
		isset($tags['MOST_CHILD']) ||
		isset($tags['MOST_CHILD_TOTAL']) ||
		isset($tags['MOST_CHILD_NAME']) ||
		isset($tags['TOP10_BIGFAM']) ||
		isset($tags['TOP10_BIGFAM_LIST'])
	)
	{
		//-- most children
		$sql = "SELECT f_numchil, f_id FROM {$TBLPREFIX}families WHERE f_file='{$GEDCOMS[$GEDCOM]['id']}' ORDER BY f_numchil DESC";
		$rows = print_htmlplus_block_sql($sql, 10);
		$row = $rows[0];
		if(isset($tags['MOST_CHILD']))
		{
			if(displayDetailsById($row['f_id'], 'FAM'))
			{
				ob_start();
				print_list_family($row['f_id'], array(get_family_descriptor($row['f_id']), $GEDCOM), false, '', false);
				$keywords[] = '#MOST_CHILD#';
				$dynamic[] = ob_get_contents();
				ob_end_clean();
			}
			else
			{
				$keywords[] = '#MOST_CHILD#';
				$dynamic[] = $pgv_lang['privacy_error'];
			}
		}
		if(isset($tags['MOST_CHILD_TOTAL']))
		{
			$keywords[] = '#MOST_CHILD_TOTAL#';
			$dynamic[] = $row['f_numchil'];
		}
		if(isset($tags['MOST_CHILD_NAME']))
		{
			$keywords[] = '#MOST_CHILD_NAME#';
			$dynamic[] = "<a href=\"family.php?famid={$row['f_id']}&amp;ged={$GEDCOM}\">".get_family_descriptor($row['f_id']).'</a>';
			unset($tags['MOST_CHILD_NAME']);
		}
		if(isset($tags['TOP10_BIGFAM']))
		{
			$top10 = array();
			foreach($rows as $row)
			{
				$top10[] = "<a href=\"family.php?famid={$row['f_id']}&amp;ged={$GEDCOM}\">".get_family_descriptor($row['f_id'])."</a> [{$row['f_numchil']} {$pgv_lang['children']}]";
			}
			$keywords[] = '#TOP10_BIGFAM#';
			$tempText = join("; ", $top10);
			if ($TEXT_DIRECTION=='rtl') {
				$tempText = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $tempText);
			}
			$dynamic[] = $tempText;
			unset($tags['TOP10_BIGFAM']);
		}
		if(isset($tags['TOP10_BIGFAM_LIST']))
		{
			$top10 = array();
			foreach($rows as $row)
			{
				$top10[] = "<li><a href=\"family.php?famid={$row['f_id']}&amp;ged={$GEDCOM}\">".get_family_descriptor($row['f_id'])."</a> [{$row['f_numchil']} {$pgv_lang['children']}]</li>";
			}
			$keywords[] = '#TOP10_BIGFAM_LIST#';
			$tempText = join("\n", $top10);
			if ($TEXT_DIRECTION=='rtl') {
				$tempText = str_replace(array("[", "]", "(", ")", "+"), array("&rlm;[", "&rlm;]", "&rlm;(", "&rlm;)", "&rlm;+"), $tempText);
			}
			$dynamic[] = "<ul>{$tempText}</ul>";
			unset($tags['TOP10_BIGFAM_LIST']);
		}
	}
	if(
		isset($tags['AVG_CHILD'])
	)
	{
		//-- avg number of children
		$sql = "SELECT AVG(f_numchil) AS tot FROM {$TBLPREFIX}families WHERE f_file='{$GEDCOMS[$GEDCOM]['id']}'";
		$rows = print_htmlplus_block_sql($sql);
		$keywords[] = '#AVG_CHILD#';
		$dynamic[] = sprintf('%.2f', $rows[0]['tot']);
		unset($tags['AVG_CHILD']);
	}
	/*
	 * Contact Tags
	 */
	if(
		isset($tags['WEBMASTER_CONTACT']) ||
		isset($tags['GEDCOM_CONTACT'])
	)
	{
		if($GLOBALS['SUPPORT_METHOD'] == 'none' && $GLOBALS['CONTACT_METHOD'] == 'none')
		{
			unset($tags['WEBMASTER_CONTACT']);
			unset($tags['GEDCOM_CONTACT']);
		}
		else
		{
			if($GLOBALS['SUPPORT_METHOD'] == 'none'){
				$GLOBALS['WEBMASTER_EMAIL'] = $GLOBALS['CONTACT_EMAIL'];
			}
			if($GLOBALS['CONTACT_METHOD'] == 'none')
			{
				$GLOBALS['CONTACT_EMAIL'] = $GLOBALS['WEBMASTER_EMAIL'];
			}
		}
		if(isset($tags['WEBMASTER_CONTACT']))
		{
			// webmaster
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
			$keywords[] = '#WEBMASTER_CONTACT#';
			$dynamic[] = $contact;
			unset($tags['WEBMASTER_CONTACT']);
		}
		if(isset($tags['GEDCOM_CONTACT']))
		{
			// contact
			$user = getUser($GLOBALS['CONTACT_EMAIL']);
			if(($user) && ($GLOBALS['SUPPORT_METHOD'] != 'mailto'))
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
			$keywords[] = '#GEDCOM_CONTACT#';
			$dynamic[] = $contact;
			unset($tags['GEDCOM_CONTACT']);
		}
	}
	/*
	 * Date/Time Tags
	 */
	if(
		isset($tags['SERVER_DATE']) ||
		isset($tags['SERVER_TIME']) ||
		isset($tags['SERVER_TIME_24']) ||
		isset($tags['SERVER_TIMEZONE'])
	)
	{
		if(isset($tags['SERVER_DATE']))
		{
			$keywords[] = '#SERVER_DATE#';
			$dynamic[] = get_changed_date(date('j').' '.strtoupper(date('M')).' '.date('Y'));
			unset($tags['SERVER_DATE']);
		}
		if(isset($tags['SERVER_TIME']))
		{
			$keywords[] = '#SERVER_TIME#';
			$dynamic[] = date('g:i a');
			unset($tags['SERVER_TIME']);
		}
		if(isset($tags['SERVER_TIME_24']))
		{
			$keywords[] = '#SERVER_TIME_24#';
			$dynamic[] = date('G:i');
			unset($tags['SERVER_TIME_24']);
		}
		if(isset($tags['SERVER_TIMEZONE']))
		{
			$keywords[] = '#SERVER_TIMEZONE#';
			$dynamic[] = date('T');
			unset($tags['SERVER_TIMEZONE']);
		}
	}
	$useLocalTime = false;
	if(
		isset($tags['LOCAL_DATE']) ||
		isset($tags['LOCAL_TIME']) ||
		isset($tags['LOCAL_TIME_24']) ||
		isset($tags['LOCAL_TIMEZONE'])
	)
	{
		$useLocalTime = true;
		if(isset($tags['LOCAL_DATE']))
		{
			$keywords[] = '#LOCAL_DATE#';
			$dynamic[] = "\n<script language=\"JavaScript\"><!--\ndocument.write(date.getDate() + \" \" + months[date.getMonth()] + \" \" + date.getFullYear());\n//--></script>\n";
			unset($tags['LOCAL_DATE']);
		}
		if(isset($tags['LOCAL_TIME']))
		{
			$keywords[] = '#LOCAL_TIME#';
			$dynamic[] = "\n<script language=\"JavaScript\"><!--\ndocument.write(hours12+\":\"+minutes+\" \"+ampm);\n//--></script>\n";
			unset($tags['LOCAL_TIME']);
		}
		if(isset($tags['LOCAL_TIME_24']))
		{
			$keywords[] = '#LOCAL_TIME_24#';
			$dynamic[] = "\n<script language=\"JavaScript\"><!--\ndocument.write(date.getHours()+\":\"+minutes);\n//--></script>\n";
			unset($tags['LOCAL_TIME_24']);
		}
		if(isset($tags['LOCAL_TIMEZONE']))
		{
			$keywords[] = '#LOCAL_TIMEZONE#';
			$dynamic[] = "\n<script language=\"JavaScript\"><!--\ndocument.write(timezone);\n//--></script>\n";
			unset($tags['LOCAL_TIMEZONE']);
		}
	}
	/*
	 * Language Tags
	 */
	foreach($tags as $kw=>$x)
	{
		// pgv_lang
		if(isset($pgv_lang[$kw]))
		{
			$keywords[] = "#{$kw}#";
			$dynamic[] = $pgv_lang[$kw];
			unset($tags[$kw]);
		}
		// factarray
		elseif(isset($factarray[$kw]))
		{
			$keywords[] = "#{$kw}#";
			$dynamic[] = $factarray[$kw];
			unset($tags[$kw]);
		}
		// GLOBALS
		elseif(isset($GLOBALS[$kw]))
		{
			$keywords[] = "#{$kw}#";
			$dynamic[] = $GLOBALS[$kw];
			unset($tags[$kw]);
		}
	}

	/*
	 * Restore Current GEDCOM
	 */
	$GEDCOM = $CURRENT_GEDCOM;

	/*
	 * Start Of Output
	 */
	$out = "<div id=\"html_block{$HTML_BLOCK_COUNT}\" class=\"block\">\n";
	if($configTitle != '')
	{
		$out .= "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>"
			."<td class=\"blockh1\" >&nbsp;</td>"
			."<td class=\"blockh2\" ><div class=\"blockhc\">"
		;
		print $out;
		if(userGedcomAdmin(getUserName()))
		{
			print_help_link('index_htmlplus_ahelp', 'qm_ah');
		}
		else
		{
			print_help_link('index_htmlplus_help', 'qm');
		}
		if(strstr($configTitle, '#'))
		{
			$configTitle = str_replace($keywords, $dynamic, $configTitle);
		}
		$out = '';
		if($PGV_BLOCKS['print_htmlplus_block']['canconfig'])
		{
			$username = getUserName();
			if((($command == 'gedcom') && (userGedcomAdmin($username))) || (($command == 'user') && (!empty($username))))
			{
				if($command == 'gedcom')
				{
					$name = preg_replace("/'/", "\'", $GEDCOM);
				}
				else
				{
					$name = $username;
				}
				$out .= "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name={$name}&amp;command={$command}&amp;action=configure&amp;side={$side}&amp;index={$index}', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">"
					."<img class=\"adminicon\" src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}\" width=\"15\" height=\"15\" border=\"0\" alt=\"{$pgv_lang['config_block']}\" /></a>\n"
				;
			}
		}
		$out .= "<b>{$configTitle}</b>"
			."</div></td>"
			."<td class=\"blockh3\">&nbsp;</td></tr>\n"
			."</table>"
		;
	}

	$out .= "<div class=\"blockcontent\">";
	if($block)
	{
		$out .= "<div class=\"small_inner_block\">\n";
	}
	if($useLocalTime)
	{
		$out .= "<script language=\"JavaScript\"><!--\n"
			."var months = new Array('January','February','March','April','May','June','July','August','September','October','November','December');\n"
			// there has to be a better way to get timezone code in JavaScript :(
			// btw, these are not likely 100% correct :(
			."var timezones = new Array();\n"
			."timezones[\"-12\"] = \"IDLW\";"
			."timezones[\"-11\"] = \"NT\";"
			."timezones[\"-10\"] = \"ASHT\";"
			."timezones[\"-9\"] = \"YST\";"
			."timezones[\"-8\"] = \"PST\";"
			."timezones[\"-7\"] = \"MST\";"
			."timezones[\"-6\"] = \"CST\";"
			."timezones[\"-5\"] = \"EST\";"
			."timezones[\"-4\"] = \"AST\";"
			//."timezones[\"-3\"] = \"\";"
			//."timezones[\"-2\"] = \"\";"
			//."timezones[\"-1\"] = \"\";"
			."timezones[\"0\"] = \"GMT\";"
			."timezones[\"+1\"] = \"CET\";"
			."timezones[\"+2\"] = \"EET\";"
			."timezones[\"+3\"] = \"BT\";"
			//."timezones[\"+4\"] = \"\";"
			//."timezones[\"+5\"] = \"\";"
			."timezones[\"+5.5\"] = \"IST\";"
			//."timezones[\"+6\"] = \"\";"
			//."timezones[\"+7\"] = \"\";"
			."timezones[\"+8\"] = \"CCT\";"
			."timezones[\"+9\"] = \"JST\";"
			."timezones[\"+10\"] = \"GST\";"
			//."timezones[\"+11\"] = \"\";"
			."timezones[\"+12\"] = \"IDLE\";"
			//."timezones[\"+13\"] = \"\";"
			."var date = new Date();\n"
			."var minutes = date.getMinutes();\n"
			."var hours12 = date.getHours();\n"
			."var ampm = \"\";\n"
			."if(hours12 > 11){\n"
			."    ampm = \"pm\";\n"
			."    hours12 = hours12 - 12;\n"
			."} else {\n"
			."    ampm = \"am\";\n"
			."    if(hours12 == 0){\n"
			."        hours12 = 12;\n"
			."    }\n"
			."}\n"
			."if(minutes < 10){\n"
			."    minutes = \"0\"+minutes;\n"
			."}\n"
			."var timezone = timezones[-(date.getTimezoneOffset() / 60)];\n"
			."//--></script>\n"
		;
	}
	$out .= str_replace($keywords, $dynamic, $configHtml);
	if($block)
	{
		$out .= "</div>\n";
	}
	if($configTitle == '' && $PGV_BLOCKS['print_htmlplus_block']['canconfig'])
	{
		$username = getUserName();
		if((($command == 'gedcom') && (userGedcomAdmin($username))) || (($command == 'user') && (!empty($username))))
		{
			if($command == 'gedcom')
			{
				$name = preg_replace("/'/", "\'", $GEDCOM);
			}
			else
			{
				$name = $username;
			}
			$out .= "<br />";
			print $out;
			print_help_link('index_htmlplus_ahelp', 'qm_ah');
			$out = "<a href=\"javascript:;\" onclick=\"window.open('index_edit.php?name={$name}&amp;command={$command}&amp;action=configure&amp;side={$side}&amp;index={$index}', '_blank', 'top=50,left=50,width=600,height=500,scrollbars=1,resizable=1'); return false;\">"
				."<img class=\"adminicon\" src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}\" width=\"15\" height=\"15\" border=\"0\" alt=\"{$pgv_lang['config_block']}\" title=\"{$pgv_lang['config_block']}\" /></a>\n"
			;
		}
	}
	$out .= "</div>\n" // blockcontent
		."</div>\n" // block
	;
	print $out;
	return true;
}

function print_htmlplus_block_config($config)
{
	global
		$pgv_lang,
		$PGV_BLOCKS,
		$TEXT_DIRECTION,
		$LANGUAGE,
		$language_settings,
		$GEDCOM,
		$GEDCOMS,
		$DEFAULT_GEDCOM
	;
	$templates = array();
	$d = dir('blocks/');
	while(false !== ($entry = $d->read()))
	{
		if(strstr($entry, 'block_htmlplus_'))
		{
			$tpl = file("blocks/{$entry}");
			$info = array_shift($tpl);
			$bits = explode('|', $info);
			if(count($bits) != 2)
			{
				$bits = array($entry, '');
			}
			$templates[] = array(
				'filename'		=>$entry,
				'title'			=>(isset($pgv_lang[$bits[0]]))?$pgv_lang[$bits[0]]:$bits[0],
				'description'	=>(isset($pgv_lang[$bits[1]]))?$pgv_lang[$bits[1]]:$bits[1],
				'template'		=>htmlspecialchars(join('', $tpl))
			);
		}
	}
	$d->close();
	if(empty($config))
	{
		$config = $PGV_BLOCKS['print_htmlplus_block']['config'];
	}
	// title
	print "<tr><td class=\"descriptionbox width20\">";
	print_help_link('index_htmlplus_title_help', 'qm_ah');
	print "{$pgv_lang['title']}</td>\n"
		."<td class=\"optionbox\"><input type=\"text\" name=\"title\" size=\"30\" value=\"{$config['title']}\" /></td></tr>\n"
	;
	// templates
	print "<tr><td class=\"descriptionbox width20\">";
	print_help_link('index_htmlplus_template_help', 'qm_ah');
	print "{$pgv_lang['htmlplus_block_templates']}</td>\n"
		."<td class=\"optionbox\">"
		."<select name=\"template\" onChange=\"document.block.html.value=document.block.template.options[document.block.template.selectedIndex].value;\">\n"
		."<option value=\"\">{$pgv_lang['htmlplus_block_custom']}</option>\n"
	;
	foreach($templates as $tpl)
	{
		print "<option value=\"{$tpl['template']}\">{$tpl['title']}</option>\n";
	}
	print "</select>\n"
		."</td></tr>\n"
	;
	// gedcom
	print "<tr><td class=\"descriptionbox width20\">";
	print_help_link('index_htmlplus_gedcom_help', 'qm_ah');
	if($config['gedcom'] == '__default__'){$sel = ' selected="selected"';}else{$sel = '';}
	print "{$pgv_lang['htmlplus_block_gedcom']}</td>\n"
		."<td class=\"optionbox\">"
		."<select name=\"gedcom\">\n"
	;
	if($config['gedcom'] == '__current__'){$sel = ' selected="selected"';}else{$sel = '';}
	print "<option value=\"__current__\">{$pgv_lang['htmlplus_block_current']}</option>\n";
	if($config['gedcom'] == '__default__'){$sel = ' selected="selected"';}else{$sel = '';}
	print "<option value=\"__default__\">{$pgv_lang['htmlplus_block_default']}</option>\n";
	foreach($GEDCOMS as $ged)
	{
		if($ged['gedcom'] == $config['gedcom']){$sel = ' selected="selected"';}else{$sel = '';}
		print "<option value=\"{$ged['gedcom']}\"{$sel}>{$ged['title']}</option>\n";
	}
	print "</select>\n"
		."</td></tr>\n"
	;
	// html
	print "<tr><td class=\"descriptionbox width20\">";
	print_help_link('index_htmlplus_content_help', 'qm_ah');
	print "{$pgv_lang['htmlplus_block_content']}</td>\n"
		."<td class=\"optionbox\">"
	;
	$useFCK = file_exists('./modules/FCKeditor/fckeditor.php');
	if($useFCK) // use FCKeditor module
	{
		include_once('./modules/FCKeditor/fckeditor.php');
		$oFCKeditor = new FCKeditor('html') ;
		$oFCKeditor->BasePath =  './modules/FCKeditor/';
		$oFCKeditor->Value = $config['html'];
		$oFCKeditor->Width = 700;
		$oFCKeditor->Height = 250;
		$oFCKeditor->Config['AutoDetectLanguage'] = false ;
		$oFCKeditor->Config['DefaultLanguage'] = $language_settings[$LANGUAGE]['lang_short_cut'];
		$oFCKeditor->Create();
	}
	else //use standard textarea
	{
		print "<textarea name=\"html\" rows=\"10\" cols=\"80\">{$config['html']}</textarea>\n";
	}
	print "</td></tr>\n";
}

function print_htmlplus_block_sql($sql, $count=0)
{
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
		return $rows;
	}
	else
	{
		die();
	}
}
?>