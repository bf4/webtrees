<?php
/**
 * Classes and libraries for module system
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
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
 * @package webtrees
 * @subpackage Modules
 * @version $Id: class_media.php 5451 2009-05-05 22:15:34Z fisharebest $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
require_once PGV_ROOT.'includes/classes/class_tab.php';
require_once PGV_ROOT.'includes/classes/class_treenav.php';

class tree_Tab extends Tab {

	public function getJSCallback() {
		return 'treetab.sizeLines(); 
var outdiv = document.getElementById("out_treetab");
var parent = document.getElementById("subtab");
if (!parent) parent = document.getElementById("tabs");
outdiv.style.width = (parent.offsetWidth-30) + "px";';
	}
	
	public function getContent() {
		ob_start();
		$inav = new TreeNav($this->controller->pid,'treetab');
		$inav->generations = 5;
		$inav->zoomLevel = -1;
		$inav->drawViewport('treetab', "auto", "500px");
		return '<div id="'.$this->getName().'_content">'.ob_get_clean().'</div>';
	}
	
	public function hasContent() {
		return true;
	}
} 
?>
