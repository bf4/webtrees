<?php
require_once 'includes/classes/class_tab.php';

class media_Tab extends Tab {

	protected $mediaCount = null;
	
	public function getContent() {
		global $CONTACT_EMAIL, $pgv_lang, $MULTI_MEDIA;
		global $NAV_MEDIA;
		
		$out = "<span class=\"subheaders\">".$pgv_lang["media"]."</span>";

		ob_start();
		// For Reorder media ------------------------------------
		if (PGV_USER_CAN_EDIT) {
			$out .= "<center>";
			require_once './includes/media_tab_head.php';
			$out .= "</center>";
		}
		?>
		<div id="media_content">
		<table class="facts_table">
		<?php
		$media_found = false;
		if (!$this->controller->indi->canDisplayDetails()) {
			print "<tr><td class=\"facts_value\">";
			print_privacy_error($CONTACT_EMAIL);
			print "</td></tr>";
		}
		else {
			$media_found = print_main_media($this->controller->pid, 0, true);
			if (!$media_found) print "<tr><td id=\"no_tab4\" colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_tab4"]."</td></tr>\n";
			//-- New Media link
			if (!$this->controller->isPrintPreview() && PGV_USER_CAN_EDIT && $this->controller->indi->canDisplayDetails()) {
		?>
				<tr>
					<td class="facts_label"><?php print_help_link("add_media_help", "qm"); ?><?php print $pgv_lang["add_media_lbl"]; ?></td>
					<td class="facts_value">
						<a href="javascript:;" onclick="window.open('addmedia.php?action=showmediaform&linktoid=<?php print $this->controller->pid; ?>', '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1'); return false;"> <?php echo $pgv_lang["add_media"]; ?></a><br />
						<a href="javascript:;" onclick="window.open('inverselink.php?linktoid=<?php print $this->controller->pid; ?>&linkto=person', '_blank', 'top=50,left=50,width=400,height=300,resizable=1,scrollbars=1'); return false;"><?php echo $pgv_lang["link_to_existing_media"]; ?></a>
					</td>
				</tr>
			<?php
			}
		}
		?>
		</table>
			</div>
	<?php
		$out .= ob_get_contents();
		ob_end_clean();
		return $out;
	}
	
	/**
	* get the number of media items for this person
	* @return int
	*/
	function get_media_count() {
		if ($this->mediaCount===null) {
			$ct = preg_match("/\d OBJE/", $this->controller->indi->getGedcomRecord());
			foreach ($this->controller->indi->getSpouseFamilies() as $k=>$sfam)
				$ct += preg_match("/\d OBJE/", $sfam->getGedcomRecord());
			$this->mediaCount = $ct;
		}
		return $this->mediaCount;
	}

	public function hasContent() {
		global $MULTI_MEDIA;
		return ($MULTI_MEDIA && $this->get_media_count()>0);
	}
}
?>