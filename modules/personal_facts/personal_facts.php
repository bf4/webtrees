<?php
require_once 'includes/classes/class_tab.php';

class personal_facts_Tab extends Tab {
	
	public function getContent() {
		global $FACT_COUNT, $CONTACT_EMAIL, $PGV_IMAGE_DIR, $PGV_IMAGES, $pgv_lang, $EXPAND_RELATIVES_EVENTS;
		global $n_chil, $n_gchi, $n_ggch;
		global $EXPAND_RELATIVES_EVENTS, $LANGUAGE, $lang_short_cut;
		global $NAV_FACTS;

		/*if (isset($_COOKIE['row_rela'])) $EXPAND_RELATIVES_EVENTS = ($_COOKIE['row_rela']);
		if (isset($_COOKIE['row_histo'])) $EXPAND_HISTO_EVENTS = ($_COOKIE['row_histo']);
		else*/ $EXPAND_HISTO_EVENTS = false;

		$out = "<span class=\"subheaders\">".$pgv_lang["personal_facts"]."</span><div id=\"facts_content\">";
		//-- only need to add family facts on this tab
		$this->controller->indi->add_family_facts();

		ob_start();
		?>
		<table class="facts_table" style="margin-top:-2px; "cellpadding="0">
		<?php if (!$this->controller->indi->canDisplayDetails()) {
			print "<tr><td class=\"facts_value\" colspan=\"2\">";
			print_privacy_error($CONTACT_EMAIL);
			print "</td></tr>";
		}
		else {
			$indifacts = $this->controller->getIndiFacts();
			if (count($indifacts)==0) {?>
				<tr>
					<td id="no_tab1" colspan="2" class="facts_value"><?php echo $pgv_lang["no_tab1"]?>
					</td>
				</tr>
			<?php }?>
			<tr id="row_top">
				<td valign="top"></td>
				<td class="descriptionbox rela">
					<input id="checkbox_rela" type="checkbox" <?php if ($EXPAND_RELATIVES_EVENTS) echo " checked=\"checked\""?> onclick="toggleByClassName('TR', 'row_rela');" />
					<label for="checkbox_rela"><?php echo $pgv_lang["relatives_events"]?></label>
					<?php if (file_exists("languages/histo.".$lang_short_cut[$LANGUAGE].".php")) {?>
						<input id="checkbox_histo" type="checkbox" <?php if ($EXPAND_HISTO_EVENTS) echo " checked=\"checked\""?> onclick="toggleByClassName('TR', 'row_histo');" />
						<label for="checkbox_histo"><?php echo $pgv_lang["historical_facts"]?></label>
					<?php }?>
				</td>

			</tr>
			<?php
			$yetdied=false;
			$n_chil=1;
			$n_gchi=1;
			$n_ggch=1;
			foreach ($indifacts as $key => $value) {
				if ($value->getTag() == "DEAT") $yetdied = true;
				if ($value->getTag() == "CREM") $yetdied = true;
				if ($value->getTag() == "BURI") $yetdied = true;

				if (!is_null($value->getFamilyId())) {
					if (!$yetdied) {
						print_fact($value, $this->controller->canedit==false);
					}
				}
				else print_fact($value, $this->controller->canedit==false);
				$FACT_COUNT++;
			}
		}
		//-- new fact link
		if ((!$this->controller->isPrintPreview()) && $this->controller->canedit) {
			print_add_new_fact($this->controller->pid, $indifacts, "INDI");
		}
		?>
		</table>
		<br />
		<script language="JavaScript" type="text/javascript">
		<!--
		<?php
		if (!$EXPAND_RELATIVES_EVENTS) print "toggleByClassName('TR', 'row_rela');\n";
		if (!$EXPAND_HISTO_EVENTS) print "toggleByClassName('TR', 'row_histo');\n";
		?>
		//-->
		</script>
		<?php
		$out .= ob_get_contents();
		ob_end_clean();
		$out .= "</div>";
		return $out;
	}
	
	public function hasContent() {
		return true;
	}
} 
?>