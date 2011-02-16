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

class personal_facts_WT_Module extends WT_Module implements WT_Module_Tab {
	// Extend WT_Module
	public function getTitle() {
		return WT_I18N::translate('Personal Facts');
	}

	// Extend WT_Module
	public function getDescription() {
		return WT_I18N::translate('Adds a tab to the individual page which displays the facts of an individual and their close relatives.');
	}

	// Implement WT_Module_Tab
	public function defaultTabOrder() {
		return 10;
	}

	// Implement WT_Module_Tab
	public function getTabContent() {
		global $FACT_COUNT, $EXPAND_RELATIVES_EVENTS, $n_chil, $n_gchi;

		/*if (isset($_COOKIE['row_rela'])) $EXPAND_RELATIVES_EVENTS = ($_COOKIE['row_rela']);
		if (isset($_COOKIE['row_histo'])) $EXPAND_HISTO_EVENTS = ($_COOKIE['row_histo']);
		else*/ $EXPAND_HISTO_EVENTS = false;

		//-- only need to add family facts on this tab
		if (!isset($this->controller->skipFamilyFacts)) {
			$this->controller->indi->add_family_facts();
		}

		ob_start();
		include './indi_header.php';
		?>
		<div class="indi_table">
		<table class="facts_table" style="margin-top:-2px;" cellpadding="0">
		<?php if (!$this->controller->indi->canDisplayDetails()) {
			echo '<tr><td class="facts_value" colspan="2">';
			print_privacy_error();
			echo '</td></tr>';
		} else {
			$indifacts = $this->controller->getIndiFacts();
			if (count($indifacts)==0) { ?>
				<tr>
					<td id="no_tab1" colspan="2" class="facts_value"><?php echo WT_I18N::translate('There are no Facts for this individual.'); ?>
					</td>
				</tr>
			<?php }
			if (!isset($this->controller->skipFamilyFacts)) {
			?>
			<tr id="row_top">
				<td valign="top"></td>
				<td class="descriptionbox rela">
					<input id="checkbox_rela_facts" type="checkbox" <?php if ($EXPAND_RELATIVES_EVENTS) echo ' checked="checked"'; ?> onclick="toggleByClassName('TR', 'row_rela');" />
					<label for="checkbox_rela_facts"><?php echo WT_I18N::translate('Events of close relatives'); ?></label>
					<?php if (file_exists(get_site_setting('INDEX_DIRECTORY').'histo.'.WT_LOCALE.'.php')) { ?>
						<input id="checkbox_histo" type="checkbox" <?php if ($EXPAND_HISTO_EVENTS) echo ' checked="checked"'; ?> onclick="toggleByClassName('TR', 'row_histo');" />
						<label for="checkbox_histo"><?php echo WT_I18N::translate('Historical facts'); ?></label>
					<?php } ?>
				</td>
			</tr>
			<?php
			}
			$yetdied=false;
			$n_chil=1;
			$n_gchi=1;
			foreach ($indifacts as $value) {
				if (strstr(WT_EVENTS_DEAT, $value->getTag())) {
					$yetdied = true;
				}
				if (!is_null($value->getFamilyId())) {
					if (!$yetdied) {
						print_fact($value);
					}
				} else {
					print_fact($value);
				}
				$FACT_COUNT++;
			}
		}
		//-- new fact link
		if ($this->controller->indi->canEdit()) {
			print_add_new_fact($this->controller->pid, $indifacts, 'INDI');
		}
		echo '</table></div>';
		echo WT_JS_START;
		if (!$EXPAND_RELATIVES_EVENTS) {
			echo "toggleByClassName('TR', 'row_rela');";
		}
		if (!$EXPAND_HISTO_EVENTS) {
			echo "toggleByClassName('TR', 'row_histo');";
		}
		echo WT_JS_END;
		return '<div id="'.$this->getName().'_content">'.ob_get_clean().'</div>';
	}

	// Implement WT_Module_Tab
	public function hasTabContent() {
		return true;
	}
	
	// Implement WT_Module_Tab
	public function canLoadAjax() {
		// Don't load this tab using AJAX, otherwise search engines won't see it
		return false;
	}

	// Implement WT_Module_Tab
	public function getPreLoadContent() {
		return '';
	}

	// Implement WT_Module_Tab
	public function getJSCallback() {
		return '';
	}
}
