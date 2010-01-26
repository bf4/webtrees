<?php
/**
 * Classes and libraries for module system
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2010 John Finlay
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

global $pgv_lang;
$pgv_lang['all_tab'] = $pgv_lang['all'];

class all_tab_Tab extends Tab {
	
	public function getJSCallback() {
		$out = 'if (selectedTab=="all_tab") {
		';
		$i = 0;
		foreach($this->controller->modules as $mod) {
			if ($mod->hasTab()) {
				if ($i>0 && $mod->getName()!='all_tab' && $mod->getTab()->canLoadAjax()) {
					$out .= 'if (!tabCache["'.$mod->getName().'"]) {
						jQuery("#'.$mod->getName().'").load("individual.php?action=ajax&module='.$mod->getName().'&pid='.$this->controller->pid.'");
						tabCache["'.$mod->getName().'"] = true;
					}';
				}
				$i++;
			}
		}
		
		$out .= '
			jQuery("#tabs > div").each(function() { 
				if (this.name!="all_tab") {
					jQuery(this).removeClass("ui-tabs-hide");
				}
			});
			}
		';
		return $out;
	}
	
	public function canLoadAjax() { return false; }
	
	public function getContent() {
		
		$out = "<div id=\"all_content\">";
		$out .= "<!-- all tab doesn't have it's own content -->";
		$out .= "</div>";
		return $out;
	}

	public function hasContent() {
		return true;
	}
}
?>
