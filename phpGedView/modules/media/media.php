<?php
/**
 * Classes and libraries for module system
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2010 PGV Development Team. All Rights Reserved.
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
 * @subpackage Modules
 * @version $Id: class_media.php 5451 2009-05-05 22:15:34Z fisharebest $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once PGV_ROOT.'includes/classes/class_tab.php';

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