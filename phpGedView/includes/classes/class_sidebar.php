<?php
/**
 * Classes and libraries for sidebars
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
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
 * @package webtrees
 * @subpackage Modules
 * @version $Id: class_media.php 5451 2009-05-05 22:15:34Z fisharebest $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_CLASS_SIDEBAR_PHP', '');

/**
 * Defines the base class for a sidebar
 * Sidebars are created by Modules through instances of the PGVModule class
 * @author jfinlay
 *
 */
abstract class Sidebar {
	protected $name;
	protected $controller = null;
	
	public function getName() { return $this->name; }
	public function setName($n) { $this->name = $n; }
	public function &getController() {return $this->controller; }
	public function setController(&$c) {$this->controller = $c; }
	
	/**
	 * Get the displayable title for this sidebar
	 * @return string
	 */
	public function getTitle() {
		return i18n::translate('Sidebar');
	}
	
	/**
	 * get the content of the tab
	 * @return string
	 */
	public abstract function getContent();
	
	/**
	 * get the content of the tab during an ajax callback
	 * @return string
	 */
	public abstract function getAjaxContent();
	
	/**
	 * does this tab have content
	 * This method can be used to hide a tab for insufficient access rights or
	 * lack of data
	 * @return boolean
	 */
	public abstract function hasContent();
	
} 
?>
