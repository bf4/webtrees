<?php
require_once 'includes/classes/class_tab.php';

class notes_Tab extends Tab {

	protected $noteCount = null;

	public function getContent() {
		global $pgv_lang, $factarray, $CONTACT_EMAIL, $FACT_COUNT;
		global $SHOW_LEVEL2_NOTES;
		global $NAV_NOTES;

		$out = "<span class=\"subheaders\">".$pgv_lang["notes"]."</span><div id=\"notes_content\">";

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
		<td class="descriptionbox rela"><input id="checkbox_note2"
			type="checkbox"
			<?php if ($SHOW_LEVEL2_NOTES) echo " checked=\"checked\""?>
			onclick="toggleByClassName('TR', 'row_note2');" /> <label
			for="checkbox_note2"><?php echo $pgv_lang["show_fact_notes"];?></label>
			<?php print_help_link("show_fact_sources_help", "qm", "show_fact_notes");?>
		</td>
	</tr>
	<?php
	$otherfacts = $this->controller->getOtherFacts();
	foreach ($otherfacts as $key => $event) {
		$fact = $event->getTag();
		if ($fact=="NOTE") {
			print_main_notes($event->getGedcomRecord(), 1, $this->controller->pid, $event->getLineNumber());
		}
		$FACT_COUNT++;
	}
	// 2nd to 5th level notes/sources
	$this->controller->indi->add_family_facts(false);
	foreach ($this->controller->getIndiFacts() as $key => $factrec) {
		for ($i=2; $i<6; $i++) {
			print_main_notes($factrec->getGedcomRecord(), $i, $this->controller->pid, $factrec->getLineNumber(), true);
		}
	}
	if ($this->get_note_count()==0) print "<tr><td id=\"no_tab2\" colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_tab2"]."</td></tr>\n";
	//-- New Note Link
	if (!$this->controller->isPrintPreview() && $this->controller->canedit) {
		?>
	<tr>
		<td class="facts_label"><?php print_help_link("add_note_help", "qm"); ?><?php echo $pgv_lang["add_note_lbl"]; ?></td>
		<td class="facts_value"><a href="javascript:;"
			onclick="add_new_record('<?php echo $this->controller->pid; ?>','NOTE'); return false;"><?php echo $pgv_lang["add_note"]; ?></a>
		<br />
		</td>
	</tr>
	<tr>
		<td class="facts_label"><?php print_help_link("add_shared_note_help", "qm"); ?><?php echo $pgv_lang["add_shared_note_lbl"]; ?></td>
		<td class="facts_value"><a href="javascript:;"
			onclick="add_new_record('<?php echo $this->controller->pid; ?>','SHARED_NOTE'); return false;"><?php echo $pgv_lang["add_shared_note"]; ?></a>
		<br />
		</td>
	</tr>
	<?php
	}
}
?>
</table>
<br />
<?php
if (!$SHOW_LEVEL2_NOTES) {
	?>
<script language="JavaScript" type="text/javascript">
			<!--
			toggleByClassName('TR', 'row_note2');
			//-->
			</script>
	<?php
		}
		$out .= ob_get_contents();
		ob_end_clean();
		$out .= "</div>";
		return $out;
	}

	function get_note_count() {
		if ($this->noteCount===null) {
			$ct = preg_match_all("/\d NOTE /", $this->controller->indi->gedrec, $match, PREG_SET_ORDER);
			foreach ($this->controller->indi->getSpouseFamilies() as $k => $sfam)
			$ct += preg_match("/\d NOTE /", $sfam->getGedcomRecord());
			$this->noteCount = $ct;
		}
		return $this->noteCount;
	}

	public function hasContent() {
		return $this->get_note_count()>0;
	}
}
?>