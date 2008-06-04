<?php
/**
 * Advanced HTML Block
 *
 * This block will print advanced HTML text with keyword support entered by an admin
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
 * @subpackage Blocks
 */

require_once 'includes/functions_print_lists.php';
require_once 'includes/class_stats.php';

$PGV_BLOCKS['print_htmlplus_block']['name']			= $pgv_lang['htmlplus_block_name'];
$PGV_BLOCKS['print_htmlplus_block']['descr']		= 'htmlplus_block_descr';
$PGV_BLOCKS['print_htmlplus_block']['canconfig']	= true;
$PGV_BLOCKS['print_htmlplus_block']['config']		= array(
	'cache'=>0,
	'title'=>'',
	'html'=>"{$pgv_lang['html_block_sample_part1']} <img src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}\" alt=\"{$pgv_lang['config_block']}\" /> {$pgv_lang['html_block_sample_part2']}",
	'gedcom'=>'__current__',
	'compat'=>0
);

function print_htmlplus_block($block=true, $config='', $side, $index)
{
	global
	$ctype,
	$factarray,
	$GEDCOM,
	$HTML_BLOCK_COUNT,
	$PGV_BLOCKS,
	$PGV_IMAGE_DIR,
	$PGV_IMAGES,
	$pgv_lang,
	$TBLPREFIX,
	$TEXT_DIRECTION,
	$DEFAULT_GEDCOM,
	$MULTI_MEDIA,
	$SHOW_ID_NUMBERS
	;
	// config sanity check
	if (empty($config)){$config = $PGV_BLOCKS['print_htmlplus_block']['config'];}else{foreach($PGV_BLOCKS['print_htmlplus_block']['config'] as $k=>$v){if (!isset($config[$k])){$config[$k] = $v;}}}

	if (!isset($HTML_BLOCK_COUNT)){$HTML_BLOCK_COUNT = 0;}$HTML_BLOCK_COUNT++;

	/*
	 * Select GEDCOM
	 */
	$CURRENT_GEDCOM = $GEDCOM;
	switch($config['gedcom']) {
	case '__current__':
		break;
	case '':
		break;
	case '__default__':
		if ($DEFAULT_GEDCOM == '') {
			foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
				$GEDCOM = $ged_name;
				break;
			}
		} else {
			$GEDCOM = $DEFAULT_GEDCOM;
		}
		break;
	default:
		if (check_for_import($config['gedcom'])) {
			$GEDCOM = $config['gedcom'];
		}
		break;
	}

	/*
	 * Initiate the stats object.
	 */
	if ($config['compat'] == 1)
	{
		include_once 'includes/class_stats_compat.php';
		$stats = new stats_compat($GEDCOM);
	}
	else
	{
		$stats = new stats($GEDCOM);
	}
	
	// Make some values from the GEDCOM's 0 HEAD record visible to the world
	global $CREATED_SOFTWARE, $CREATED_VERSION, $CREATED_DATE;
	$CREATED_SOFTWARE = $stats->gedcomCreatedSoftware();
	$CREATED_VERSION = $stats->gedcomCreatedVersion();
	$CREATED_DATE = $stats->gedcomDate();	

	/*
	 * First Pass.
	 * Handle embedded language, fact, global, etc. references
	 *   This needs to be done first because the language variables could themselves
	 *   contain embedded keywords.
	 */
	// Title
	$config['title'] = print_text($config['title'],0,2);
	// Content
	$config['html'] = print_text($config['html'],0,2);

	/*
	 * Second Pass.
	 */
	list($new_tags, $new_values) = $stats->getTags("{$config['title']} {$config['html']}");
	// Title
	if (strstr($config['title'], '#')){$config['title'] = str_replace($new_tags, $new_values, $config['title']);}
	// Content
	$config['html'] = str_replace($new_tags, $new_values, $config['html']);

	/*
	 * Restore Current GEDCOM
	 */
	$GEDCOM = $CURRENT_GEDCOM;

	/*
	 * Start Of Output
	 */
	$id = "html_block{$HTML_BLOCK_COUNT}";
	$title = "";
	if ($config['title'] != '') {
		if (PGV_USER_GEDCOM_ADMIN) {
			$title .= print_help_link('index_htmlplus_ahelp', 'qm_ah', '', false, true);
		} else {
			$title .= print_help_link('index_htmlplus_help', 'qm', '', false, true);
		}
		if ($PGV_BLOCKS['print_htmlplus_block']['canconfig']) {
			if ($ctype=="gedcom" && PGV_USER_GEDCOM_ADMIN || $ctype=="user" && PGV_USER_ID) {
				if ($ctype=="gedcom") {
					$name = preg_replace("/'/", "\'", $GEDCOM);
				} else {
					$name = PGV_USER_NAME;
				}
				$title .= "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name={$name}&amp;ctype={$ctype}&amp;action=configure&amp;side={$side}&amp;index={$index}', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">"
				."<img class=\"adminicon\" src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}\" width=\"15\" height=\"15\" border=\"0\" alt=\"{$pgv_lang['config_block']}\" /></a>"
				;
			}
		}
		$title .= $config['title'];
	}

	$content = $config['html'];
	if ($config['title'] == '' && $PGV_BLOCKS['print_htmlplus_block']['canconfig']) {
		if ($ctype=="gedcom" && PGV_USER_GEDCOM_ADMIN || $ctype=="user" && PGV_USER_ID) {
			if ($ctype=="gedcom") {
				$name = preg_replace("/'/", "\'", $GEDCOM);
			} else {
				$name = PGV_USER_NAME;
			}
			$content .= "<br />"
			.print_help_link('index_htmlplus_ahelp', 'qm_ah', '', false, true)
			."<a href=\"javascript:;\" onclick=\"window.open('index_edit.php?name={$name}&amp;ctype={$ctype}&amp;action=configure&amp;side={$side}&amp;index={$index}', '_blank', 'top=50,left=50,width=600,height=500,scrollbars=1,resizable=1'); return false;\">"
			."<img class=\"adminicon\" src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}\" width=\"15\" height=\"15\" border=\"0\" alt=\"{$pgv_lang['config_block']}\" title=\"{$pgv_lang['config_block']}\" /></a>"
			;
		}
	}

	print '<div id="'.$id.'" class="block"><table class="blockheader" cellspacing="0" cellpadding="0"><tr>';
	print '<td class="blockh1">&nbsp;</td>';
	print '<td class="blockh2 blockhc"><b>'.$title.'</b></td>';
	print '<td class="blockh3">&nbsp;</td>';
	print '</tr></table><div class="blockcontent">';
	if ($block) {
		print '<div class="small_inner_block">'.$content.'</div>';
	} else {
		print '<div class="normal_inner_block">'.$content.'</div>';
	}
	print '</div></div>';
}

function print_htmlplus_block_config($config)
{
	global
	$pgv_lang,
	$factarray,
	$ctype,
	$PGV_BLOCKS,
	$TEXT_DIRECTION,
	$LANGUAGE,
	$language_settings,
	$GEDCOM,
	$DEFAULT_GEDCOM
	;
	$templates = array();
	$d = dir('blocks/');
	while(false !== ($entry = $d->read()))
	{
		if (strstr($entry, 'block_htmlplus_'))
		{
			$tpl = file("blocks/{$entry}");
			$info = array_shift($tpl);
			$bits = explode('|', $info);
			if (count($bits) != 2)
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
	// config sanity check
	if (empty($config)){$config = $PGV_BLOCKS['print_htmlplus_block']['config'];}else{foreach($PGV_BLOCKS['print_htmlplus_block']['config'] as $k=>$v){if (!isset($config[$k])){$config[$k] = $v;}}}
	// title
	$config['title'] = htmlentities($config['title'], ENT_COMPAT, 'UTF-8');
	print "<tr><td class=\"descriptionbox wrap width33\">"
	.print_help_link('index_htmlplus_title_help', 'qm_ah', '', false, true)
	."{$factarray['TITL']}</td>"
	."<td class=\"optionbox\"><input type=\"text\" name=\"title\" size=\"30\" value=\"{$config['title']}\" /></td></tr>"
	;
	// templates
	print "<tr><td class=\"descriptionbox wrap width33\">"
	.print_help_link('index_htmlplus_template_help', 'qm_ah', '', false, true)
	."{$pgv_lang['htmlplus_block_templates']}</td>"
	."<td class=\"optionbox\">"
	."<select name=\"template\" onChange=\"document.block.html.value=document.block.template.options[document.block.template.selectedIndex].value;\">"
	."<option value=\"\">{$pgv_lang['htmlplus_block_custom']}</option>"
	;
	foreach($templates as $tpl) {
		print "<option value=\"{$tpl['template']}\">{$tpl['title']}</option>";
	}
	print "</select>"
	."</td></tr>"
	;
	// gedcom
	if (count(get_all_gedcoms()) > 1) {
		if ($config['gedcom'] == '__current__'){$sel_current = ' selected="selected"';}else{$sel_current = '';}
		if ($config['gedcom'] == '__default__'){$sel_default = ' selected="selected"';}else{$sel_default = '';}
		print "<tr><td class=\"descriptionbox wrap width33\">"
		.print_help_link('index_htmlplus_gedcom_help', 'qm_ah', '', false, true)
		."{$pgv_lang['htmlplus_block_gedcom']}</td>"
		."<td class=\"optionbox\">"
		."<select name=\"gedcom\">"
		."<option value=\"__current__\"{$sel_current}>{$pgv_lang['htmlplus_block_current']}</option>"
		."<option value=\"__default__\"{$sel_default}>{$pgv_lang['htmlplus_block_default']}</option>"
		;
		foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
			if ($ged_name==$config['gedcom']) {
				$sel = ' selected="selected"';
			} else {
				$sel = '';
			}
			echo '<option value="', $ged_name, '"', $sel, '>', get_gedcom_setting($ged_id, 'title'), '</option>';
		}
		print "</select>"
		."</td></tr>"
		;
	}
	// html
	print "<tr><td class=\"descriptionbox wrap width33\">"
	.print_help_link('index_htmlplus_content_help', 'qm_ah', '', false, true)
	."{$pgv_lang['htmlplus_block_content']}<br /><br />"
	."</td>"
	."<td class=\"optionbox\">"
	."<textarea name=\"html\" rows=\"10\" cols=\"80\">{$config['html']}</textarea>"
	."</td></tr>"
	;
	// compatibility mode
	if ($config['compat'] == 1){$compat = ' checked="CHECKED"';}else{$compat = '';}
	print "<tr><td class=\"descriptionbox wrap width33\">"
	.print_help_link('index_htmlplus_compat_help', 'qm_ah', '', false, true)
	."{$pgv_lang['htmlplus_block_compat']}</td>"
	."<td class=\"optionbox\"><input type=\"checkbox\" name=\"compat\" value=\"1\"{$compat} /></td></tr>"
	;
	// Cache file life
	if ($ctype=="gedcom") {
		print "<tr><td class=\"descriptionbox wrap width33\">";
		print_help_link("cache_life_help", "qm");
		print $pgv_lang["cache_life"];
		print "</td><td class=\"optionbox\">";
		print "<input type=\"text\" name=\"cache\" size=\"2\" value=\"".$config["cache"]."\" />";
		print "</td></tr>";
	}
}
?>
