<?php
/**
 * Classes and libraries for individual tabs
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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

define('PGV_class_TAB_PHP', '');

/**
 * Defines the base class for a tab on the individual page
 * Tabs are created by Modules through instances of the PGVModule class
 * @author jfinlay
 *
 */
abstract class Tab {
	protected $name;
	protected $controller;
	
	public function getName() { return $this->name; }
	public function setName($n) { $this->name = $n; }
	public function getController() {return $this->controller; }
	public function setController($c) {$this->controller = $c; }
	
	/**
	 * get the content of the tab
	 * @return string
	 */
	public abstract function getContent();
	/**
	 * does this tab have content
	 * This method can be used to hide a tab for insufficient access rights or
	 * lack of data
	 * @return boolean
	 */
	public abstract function hasContent();
	
	/**
	 * can this tab be loaded with AJAX
	 * @return unknown_type
	 */
	public function canLoadAjax() { return true; }
	/**
	 * any content that needs to be loaded before ajax calls such as javascript
	 * @return string
	 */
	public function getPreLoadContent() { return ""; }
	/**
	 * the javascript that needs to be called after every tab change, in order for this
	 * tab to function properly
	 * @return string	a string representation of the javascript
	 */
	public function getJSCallbackAllTabs() { return ""; }
	/**
	 * the javascript that needs to be called when changing just to this tab, in order for this
	 * tab to function properly
	 * @return string	a string representation of the javascript
	 */

	public function getJSCallback() { return ""; }
} 
?>
