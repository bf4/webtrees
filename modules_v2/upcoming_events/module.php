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

require_once WT_ROOT.'includes/functions/functions_print_lists.php';

class upcoming_events_WT_Module extends WT_Module implements WT_Module_Block {
	// Extend class WT_Module
	public function getTitle() {
		return WT_I18N::translate('Upcoming Events');
	}

	// Extend class WT_Module
	public function getDescription() {
		return WT_I18N::translate('The Upcoming Events block shows anniversaries of events that will occur in the near future.  You can configure the amount of detail shown, and the administrator can configure how far into the future this block will look.');
	}

	// Implement class WT_Module_Block
	public function getBlock($block_id, $template=true, $cfg=null) {
		global $ctype, $WT_IMAGES;

		$days     =get_block_setting($block_id, 'days',      7);
		$filter   =get_block_setting($block_id, 'filter',    true);
		$onlyBDM  =get_block_setting($block_id, 'onlyBDM',   false);
		$infoStyle=get_block_setting($block_id, 'infoStyle', 'table');
		$sortStyle=get_block_setting($block_id, 'sortStyle', 'alpha');
		$block    =get_block_setting($block_id, 'block',     true);
		if ($cfg) {
			foreach (array('days', 'filter', 'onlyBDM', 'infoStyle', 'sortStyle', 'block') as $name) {
				if (array_key_exists($name, $cfg)) {
					$$name=$cfg[$name];
				}
			}
		}

		$startjd=WT_CLIENT_JD+1;
		$endjd  =WT_CLIENT_JD+$days;

		// Output starts here
		$id=$this->getName().$block_id;
		$title='';
		if ($ctype=="gedcom" && WT_USER_GEDCOM_ADMIN || $ctype=="user" && WT_USER_ID) {
			$title.="<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?action=configure&amp;ctype={$ctype}&amp;block_id={$block_id}', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
			$title.="<img class=\"adminicon\" src=\"".$WT_IMAGES["admin"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".WT_I18N::translate('Configure')."\" /></a>";
		}
		$title.= WT_I18N::translate('Upcoming Events');

		$content = "";
		switch ($infoStyle) {
		case "list":
			// Output style 1:  Old format, no visible tables, much smaller text.  Better suited to right side of page.
			$content.=print_events_list($startjd, $endjd, $onlyBDM?'BIRT MARR DEAT':'', $filter, $sortStyle);
			break;
		case "table":
			// Style 2: New format, tables, big text, etc.  Not too good on right side of page
			ob_start();
			$content.=print_events_table($startjd, $endjd, $onlyBDM?'BIRT MARR DEAT':'', $filter, $sortStyle);
			$content.=ob_get_clean();
			break;
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
			set_block_setting($block_id, 'days',          safe_POST_integer('days', 1, 30, 7));
			set_block_setting($block_id, 'filter',        safe_POST_bool('filter'));
			set_block_setting($block_id, 'onlyBDM',       safe_POST_bool('onlyBDM'));
			set_block_setting($block_id, 'infoStyle',     safe_POST('infoStyle', array('list', 'table'), 'table'));
			set_block_setting($block_id, 'sortStyle',     safe_POST('sortStyle', array('alpha', 'anniv'), 'alpha'));
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

		$filter=get_block_setting($block_id, 'filter',     true);
		echo '<tr><td class="descriptionbox wrap width33">';
		echo WT_I18N::translate('Show only events of living people?');
		echo '</td><td class="optionbox">';
		echo edit_field_yes_no('filter', $filter);
		echo '</td></tr>';

		$onlyBDM=get_block_setting($block_id, 'onlyBDM',    false);
		echo '<tr><td class="descriptionbox wrap width33">';
		echo WT_I18N::translate('Show only Births, Deaths, and Marriages?');
		echo '</td><td class="optionbox">';
		echo edit_field_yes_no('onlyBDM', $onlyBDM);
		echo '</td></tr>';

		$infoStyle=get_block_setting($block_id, 'infoStyle', 'table');
		echo '<tr><td class="descriptionbox wrap width33">';
		echo WT_I18N::translate('Presentation style');
		echo '</td><td class="optionbox">';
		echo select_edit_control('infoStyle', array('list'=>WT_I18N::translate('list'), 'table'=>WT_I18N::translate('table')), null, $infoStyle, '');
		echo '</td></tr>';

		$sortStyle=get_block_setting($block_id, 'sortStyle',  'alpha');
		echo '<tr><td class="descriptionbox wrap width33">';
		echo WT_I18N::translate('Sort Style');
		echo '</td><td class="optionbox">';
		echo select_edit_control('sortStyle', array('alpha'=>WT_I18N::translate('alphabetically'), 'anniv'=>WT_I18N::translate('By Anniversary')), null, $sortStyle, '');
		echo '</td></tr>';

		$block=get_block_setting($block_id, 'block', true);
		echo '<tr><td class="descriptionbox wrap width33">';
		echo /* I18N: label for a yes/no option */ WT_I18N::translate('Add a scrollbar when block contents grow');
		echo '</td><td class="optionbox">';
		echo edit_field_yes_no('block', $block);
		echo '</td></tr>';
	}
}
