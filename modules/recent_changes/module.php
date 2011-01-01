<?php
// Classes and libraries for module system
//
// webtrees: Web based Family History software
// Copyright (C) 2011 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2010 John Finlay
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
// @version $Id$

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class recent_changes_WT_Module extends WT_Module implements WT_Module_Block {
	// Extend class WT_Module
	public function getTitle() {
		return WT_I18N::translate('Recent changes');
	}

	// Extend class WT_Module
	public function getDescription() {
		return WT_I18N::translate('This block lists the changes that have been made to all family trees within a specified number of days.');
	}

	// Implement class WT_Module_Block
	public function getBlock($block_id, $template=true, $cfg=null) {
		global $ctype, $WT_IMAGES;

		$days=get_block_setting($block_id, 'days', 7);
		$hide_empty=get_block_setting($block_id, 'hide_empty', false);
		$block=get_block_setting($block_id, 'block', true);
		if ($cfg) {
			foreach (array('days', 'hide_empty', 'block') as $name) {
				if (array_key_exists($name, $cfg)) {
					$$name=$cfg[$name];
				}
			}
		}

		$found_facts=get_recent_changes(WT_CLIENT_JD-$days);

		if (empty($found_facts) && $hide_empty) {
			return;
		}
		// Print block header
		$id=$this->getName().$block_id;
		$title='';
		if ($ctype=="gedcom" && WT_USER_GEDCOM_ADMIN || $ctype=='user') {
			if ($ctype=="gedcom") {
				$name = WT_GEDCOM;
			} else {
				$name = WT_USER_NAME;
			}
			$title .= "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?action=configure&amp;ctype={$ctype}&amp;block_id={$block_id}', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
			$title .= "<img class=\"adminicon\" src=\"".$WT_IMAGES["admin"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".WT_I18N::translate('Configure')."\" /></a>";
		}
		$title.= WT_I18N::translate('Recent changes').help_link('recent_changes', $this->getName());

		$content = "";
	// Print block content
		if (count($found_facts)==0) {
			$content .= WT_I18N::translate('There have been no changes within the last %s days.', $days);
		} else {
			$content .= WT_I18N::translate('Changes made within the last %s days', $days);
			// sortable table
			require_once WT_ROOT.'includes/functions/functions_print_lists.php';
			ob_start();
			print_changes_table($found_facts);
			$content .= ob_get_clean();
		}

		if ($template) {
			if ($block) {
				require WT_THEME_DIR.'templates/block_small_temp.php';
			} else {
				require WT_THEME_DIR.'templates/block_main_temp.php';
			}
		} else {
			return $content;
		}
	}

	// Implement class WT_Module_Block
	public function loadAjax() {
		return true;
	}

	// Implement class WT_Module_Block
	public function isUserBlock() {
		return true;
	}

	// Implement class WT_Module_Block
	public function isGedcomBlock() {
		return true;
	}

	// Implement class WT_Module_Block
	public function configureBlock($block_id) {
		if (safe_POST_bool('save')) {
			set_block_setting($block_id, 'days', safe_POST_integer('days', 1, 30, 7));
			set_block_setting($block_id, 'hide_empty', safe_POST_bool('hide_empty'));
			set_block_setting($block_id, 'block',  safe_POST_bool('block'));
			echo WT_JS_START, 'window.opener.location.href=window.opener.location.href;window.close();', WT_JS_END;
			exit;
		}

		require_once WT_ROOT.'includes/functions/functions_edit.php';

		$days=get_block_setting($block_id, 'days', 7);
		echo '<tr><td class="descriptionbox wrap width33">';
		echo WT_I18N::translate('Number of days to show');
		echo '</td><td class="optionbox">';
		echo '<input type="text" name="days" size="2" value="', $days, '" />';
		echo ' <i>', WT_I18N::plural('maximum %d day', 'maximum %d days', 30, 30) ,'</i>';
		echo '</td></tr>';

		$hide_empty=get_block_setting($block_id, 'hide_empty', true);
		echo '<tr><td class="descriptionbox wrap width33">';
		echo WT_I18N::translate('Should this block be hidden when it is empty?');
		echo '</td><td class="optionbox">';
		echo edit_field_yes_no('hide_empty', $hide_empty);
		echo '</td></tr>';
		echo '<tr><td colspan="2" class="optionbox wrap">';
		echo '<span class="error">', WT_I18N::translate('If you hide an empty block, you will not be able to change its configuration until it becomes visible by no longer being empty.'), '</span>';
		echo '</td></tr>';

		$block=get_block_setting($block_id, 'block', true);
		echo '<tr><td class="descriptionbox wrap width33">';
		echo /* I18N: label for a yes/no option */ WT_I18N::translate('Add a scrollbar when block contents grow');
		echo '</td><td class="optionbox">';
		echo edit_field_yes_no('block', $block);
		echo '</td></tr>';
	}
}
