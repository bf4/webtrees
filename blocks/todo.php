<?php
/**
 * TODO Block
 *
 * This block will print a list of things to do, based on _TODO records
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Blocks
 * @author Greg Roach, fisharebest@users.sourceforge.net
 * @version $Id$
 */

$PGV_BLOCKS['print_todo']['name']     =$pgv_lang['todo_block'];
$PGV_BLOCKS['print_todo']['descr']    ='todo_descr';
$PGV_BLOCKS['print_todo']['canconfig']=true;
$PGV_BLOCKS['print_todo']['config']   =array(
	'cache'          =>0,
	'show_unassigned'=>'yes', // show unassigned items
	'show_other'     =>'no',  // show items assigned to other users
	'show_future'    =>'no'   // show items with a future date
);

// this block prints a list of _TODO events in your gedcom
function print_todo($block=true, $config='', $side, $index) {
	global $pgv_lang, $factarray, $ctype, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $PGV_BLOCKS;

	$block=true; // Always restrict this block's height

	if (empty($config)) {
		$config=$PGV_BLOCKS['print_todo']['config'];
	}

	$id='todo';
	$title = print_help_link('todo_help', 'qm','',false,true);
	if ($PGV_BLOCKS['print_todo']['canconfig']) {
		if ($ctype=='gedcom' && PGV_USER_GEDCOM_ADMIN || $ctype=='user' && PGV_USER_ID) {
			if ($ctype=='gedcom') {
				$name = preg_replace("/'/", "\'", $GEDCOM);
			} else {
				$name = PGV_USER_NAME;
			}
			$title .= "<a href=\"javascript: configure block\" onclick=\"window.open('".encode_url("index_edit.php?name={$name}&ctype={$ctype}&action=configure&side={$side}&index={$index}")."', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
			$title .= "<img class=\"adminicon\" src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}\" width=\"15\" height=\"15\" border=\"0\" alt=\"{$pgv_lang['config_block']}\" /></a>";
		}
	}
	$title .= $pgv_lang['todo_block'];
	$content = "";

	require_once("js/sorttable.js.htm");
	require_once("includes/gedcomrecord.php");

	$all_gedcoms=get_all_gedcoms();

	$table_id = 'ID'.floor(microtime()*1000000); // sorttable requires a unique ID
	$content .= '<table id="'.$table_id.'" class="sortable list_table center">';
	$content .= '<tr>';
	if (count($all_gedcoms)>1) {
		$content .= '<th class="list_label">GEDCOM</th>';
	}
	$content .= '<th class="list_label">'.$factarray['DATE'].'</th>';
	$content .= '<th class="list_label">'.$pgv_lang['record'].'</th>';
	if ($config['show_unassigned']=='yes' || $config['show_other']=='yes') {
		$content .= '<th class="list_label">'.$pgv_lang['username'].'</th>';
	}
	$content .= '<th class="list_label">'.$factarray['TEXT'].'</th>';
	$content .= '</tr>';

	$OLD_GEDCOM=$GEDCOM;
	$found=false;
	$end_jd=$config['show_future']=='yes' ? 99999999 : client_jd();
	foreach ($all_gedcoms as $ged_id=>$ged_name) {
		$GEDCOM=$ged_name;
		foreach (get_calendar_events(0, $end_jd, '_TODO', $ged_id) as $todo) {
			$record=GedcomRecord::getInstance($todo['id']);
			$pgvu=get_gedcom_value('_PGVU', 2, $todo['factrec']);
			if ($record && ($pgvu==PGV_USER_ID || !$pgvu && $config['show_unassigned']=='yes' || $pgvu && $config['show_other']=='yes')) {
				$content.='<tr valign="top">';
				if (count($all_gedcoms)>1) {
					$content.='<td class="list_value_wrap"><a href="'.encode_url("index.php?ctype=gedcom&ged={$ged_name}").'">'.$ged_name.'</a></td>';
				}
				$content.='<td class="list_value_wrap">'.str_replace('<a', '<a name="'.$todo['date']->MinJD().'"', $todo['date']->Display(false)).'</td>';
				$name=$record->getListName();
				$content.='<td class="list_value_wrap" align="'.get_align($name).'"><a href="'.encode_url($record->getLinkUrl()).'">'.PrintReady($name).'</a></td>';
				if ($config['show_unassigned']=='yes' || $config['show_other']=='yes') {
					$content.='<td class="list_value_wrap">'.$pgvu.'</td>';
				}
				$text=get_gedcom_value('_TODO', 1, $todo['factrec']);
				$content.='<td class="list_value_wrap" align="'.get_align($text).'">'.PrintReady($text).'</td>';
				$content.='</tr>';
				$found=true;
			}
		}
	}
	$GEDCOM=$OLD_GEDCOM;

	$content .= '</table>';
	if (!$found) {
		$content.='<p>'.$pgv_lang['todo_nothing'].'</p>';
	}

	print '<div id="'.$id.'" class="block"><table class="blockheader" cellspacing="0" cellpadding="0"><tr>';
	print '<td class="blockh1">&nbsp;</td>';
	print '<td class="blockh2 blockhc"><b>'.$title.'</b></td>';
	print '<td class="blockh3">&nbsp;</td>';
	print '</tr></table><div class="blockcontent">';
	if ($block) {
		print '<div class="small_inner_block">'.$content.'</div>';
	} else {
		print $content;
	}
	print '</div></div>';
}

function print_todo_config($config) {
	global $pgv_lang, $PGV_BLOCKS, $DAYS_TO_SHOW_LIMIT;

	if (empty($config)) {
		$config=$PGV_BLOCKS['print_todo']['config'];
	}

	print '<tr><td class="descriptionbox wrap width33">';
	print_help_link('todo_show_other_help', 'qm');
	print $pgv_lang['todo_show_other']."</td>";
	print '</td><td class="optionbox">';
	print '<select name="show_other">';
	foreach (array('yes', 'no') as $option) {
		print "<option value=\"{$option}\"";
		if ($config['show_other']==$option)
			print " selected=\"selected\"";
		print ">{$pgv_lang[$option]}</option>";
	}
	print '</select></td></tr>';
	
	print '<tr><td class="descriptionbox wrap width33">';
	print_help_link('todo_show_unassigned_help', 'qm');
	print $pgv_lang['todo_show_unassigned']."</td>";
	print '</td><td class="optionbox">';
	print '<select name="show_unassigned">';
	foreach (array('yes', 'no') as $option) {
		print "<option value=\"{$option}\"";
		if ($config['show_unassigned']==$option)
			print " selected=\"selected\"";
		print ">{$pgv_lang[$option]}</option>";
	}
	print '</select></td></tr>';

	print '<tr><td class="descriptionbox wrap width33">';
	print_help_link('todo_show_future_help', 'qm');
	print $pgv_lang['todo_show_future']."</td>";
	print '</td><td class="optionbox">';
	print '<select name="show_future">';
	foreach (array('yes', 'no') as $option) {
		print "<option value=\"{$option}\"";
		if ($config['show_future']==$option)
			print " selected=\"selected\"";
		print ">{$pgv_lang[$option]}</option>";
	}
	print '</select></td></tr>';

	// Cache file life is not configurable by user
	print '<input type="hidden" name="cache" value="0" />';
}
?>
