<?php
/**
 * Classes and libraries for module system
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2009 PGV Development Team. All rights reserved.
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
 * @package webtrees
 * @subpackage Modules
 * @version $Id: class_media.php 5451 2009-05-05 22:15:34Z fisharebest $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
require_once PGV_ROOT.'includes/classes/class_tab.php';

class sources_tab_Tab extends Tab {

	protected $sourceCount = null;

	public function getContent() {
		global $CONTACT_EMAIL, $FACT_COUNT;
		global $SHOW_LEVEL2_NOTES;
		global $NAV_SOURCES;
		
		/*if (isset($_COOKIE['row_sour2'])) $SHOW_LEVEL2_SOURCES = ($_COOKIE['row_sour2']);
		else*/ $SHOW_LEVEL2_SOURCES = $SHOW_LEVEL2_NOTES;

		ob_start();
		?>
		<table class="facts_table">
		<?php
		if (!$this->controller->indi->canDisplayDetails()) {
			print "<tr><td class=\"facts_value\">";
			print_privacy_error($CONTACT_EMAIL);
			print "</td></tr>";
		} else {
		?>
			<tr>
				<td></td>
				<td class="descriptionbox rela">
					<input id="checkbox_sour2" type="checkbox" <?php if ($SHOW_LEVEL2_SOURCES) echo " checked=\"checked\""?> onclick="toggleByClassName('TR', 'row_sour2');" />
					<label for="checkbox_sour2"><?php echo i18n::translate('Show all sources');?></label>
					<?php print_help_link("show_fact_sources", "qm", "show_fact_sources");?>
				</td>
			</tr>
			<?php
			$otheritems = $this->controller->getOtherFacts();
				foreach ($otheritems as $key => $event) {
					if ($event->getTag()=="SOUR") print_main_sources($event->getGedcomRecord(), 1, $this->controller->pid, $event->getLineNumber());
				$FACT_COUNT++;
			}
		}
			// 2nd level sources [ 1712181 ]
			$this->controller->indi->add_family_facts(false);
			foreach ($this->controller->getIndiFacts() as $key => $factrec) {
					print_main_sources($factrec->getGedcomRecord(), 2, $this->controller->pid, $factrec->getLineNumber(), true);
			}
			if ($this->get_source_count()==0) print "<tr><td id=\"no_tab3\" colspan=\"2\" class=\"facts_value\">".i18n::translate('There are no Source citations for this individual.')."</td></tr>\n";
			//-- New Source Link
			if (!$this->controller->isPrintPreview() && $this->controller->canedit) {
			?>
				<tr>
					<td class="facts_label"><?php print_help_link("add_source", "qm"); ?><?php echo i18n::translate('Add Source Citation'); ?></td>
					<td class="facts_value">
					<a href="javascript:;" onclick="add_new_record('<?php echo $this->controller->pid; ?>','SOUR'); return false;"><?php echo i18n::translate('Add a new Source Citation'); ?></a>
					<br />
					</td>
				</tr>
			<?php
			}
		?>
		</table>
		<br />
		<?php
		if (!$SHOW_LEVEL2_SOURCES) {
		?>
			<script language="JavaScript" type="text/javascript">
			<!--
			toggleByClassName('TR', 'row_sour2');
			//-->
			</script>
	<?php
		}
		return '<div id="'.$this->getName().'_content">'.ob_get_clean().'</div>';
	}

	function get_source_count() {
		if ($this->sourceCount===null) {
			$ct = preg_match_all("/\d SOUR @(.*)@/", $this->controller->indi->gedrec, $match, PREG_SET_ORDER);
			foreach ($this->controller->indi->getSpouseFamilies() as $k => $sfam)
				$ct += preg_match("/\d SOUR /", $sfam->getGedcomRecord());
			$this->sourceCount = $ct;
		}
		return $this->sourceCount;
	}

	public function hasContent() {
		return $this->get_source_count()>0;
	}
}
?>
