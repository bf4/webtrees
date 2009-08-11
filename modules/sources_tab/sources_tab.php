<?php
require_once 'includes/classes/class_tab.php';

global $pgv_lang;
if (isset($pgv_lang['ssourcess']))
$pgv_lang['sources_tab'] = $pgv_lang['ssourcess'];

class sources_tab_Tab extends Tab {

	protected $sourceCount = null;

	public function getContent() {
		global $CONTACT_EMAIL, $pgv_lang, $FACT_COUNT;
		global $SHOW_LEVEL2_NOTES;
		global $NAV_SOURCES;
		
		/*if (isset($_COOKIE['row_sour2'])) $SHOW_LEVEL2_SOURCES = ($_COOKIE['row_sour2']);
		else*/ $SHOW_LEVEL2_SOURCES = $SHOW_LEVEL2_NOTES;

		$out = "<span class=\"subheaders\">".$pgv_lang["ssourcess"]."</span><div id=\"sources_content\">";

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
					<label for="checkbox_sour2"><?php echo $pgv_lang["show_fact_sources"];?></label>
					<?php print_help_link("show_fact_sources_help", "qm", "show_fact_sources");?>
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
			if ($this->get_source_count()==0) print "<tr><td id=\"no_tab3\" colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_tab3"]."</td></tr>\n";
			//-- New Source Link
			if (!$this->controller->isPrintPreview() && $this->controller->canedit) {
			?>
				<tr>
					<td class="facts_label"><?php print_help_link("add_source_help", "qm"); ?><?php echo $pgv_lang["add_source_lbl"]; ?></td>
					<td class="facts_value">
					<a href="javascript:;" onclick="add_new_record('<?php echo $this->controller->pid; ?>','SOUR'); return false;"><?php echo $pgv_lang["add_source"]; ?></a>
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
		$out .= ob_get_contents();
		ob_end_clean();
		$out .= "</div>";
		return $out;
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
