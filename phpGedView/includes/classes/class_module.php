<?php
/**
 * Classes and libraries for module system
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2009 John Finlay
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

define('PGV_class_MODULE_PHP', '');

require_once(PGV_ROOT.'includes/classes/class_tab.php');
require_once(PGV_ROOT.'includes/classes/class_sidebar.php');

/**
 * abstract class that is to be overidden by implementing modules
 * @author jfinlay
 *
 */
abstract class PGVModule {
	private $id = 0;
	private $accessLevel = array();
	private $menuEnabled = array();
	private $tabEnabled = array();
	private $sidebarEnabled = array();
	private $taborder = 99;
	private $menuorder = 99;
	private $sidebarorder = 99;

	// -- overide in base classes
	protected $name = 'default name';
	protected $description = 'this is the default description';
	protected $version = '0';
	protected $pgvVersion = '4.2.2';
	protected $menu = null;
	protected $tab = null;
	protected $sidebar = null;
	protected $configLink = null;

	public static $default_tabs = array('family_nav', 'personal_facts', 'sources_tab', 'notes', 'media', 'lightbox', 'tree', 'googlemap', 'relatives', 'all_tab');
	public static $default_sidebars = array('descendancy', 'family_nav', 'clippings', 'individuals', 'families');
	public static $default_menus = array('page_menu');

	/**
	 * Get an instance of the desired module class based on a db row
	 * @param $row
	 * @return PGVModule
	 */
	static function &getInstance($row) {
		$entry=$row->mod_name;
		if (file_exists("modules/$entry/pgv_module.php")) {
			include_once("modules/$entry/pgv_module.php");
			$menu_class = $entry."_PGVModule";
			$obj = new $menu_class();
			$obj->setId($row->mod_id);
			$obj->setName($entry);
			$obj->setDescription($row->mod_description);
			$obj->setTaborder($row->mod_taborder);
			$obj->setMenuorder($row->mod_menuorder);
			$obj->setSidebarorder($row->mod_sidebarorder);
			return $obj;
		}
		return null;
	}

	//-- getters and setters
	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	public function getDescription() { return $this->description; }
	public function getTaborder() { return $this->taborder; }
	public function getMenuorder() { return $this->menuorder; }
	public function getSidebarorder() { return $this->sidebarorder; }
	public function getVersion() { return $this->version; }
	public function getPgvVersion() { return $this->pgvVersion; }
	public function getAccessLevel($gedId = PGV_GED_ID) {
		if (!isset($this->accessLevel[$gedId])) $this->accessLevel[$gedId] = PGV_PRIV_PUBLIC;
		return $this->accessLevel[$gedId];
	}
	public function getMenuEnabled($gedId = PGV_GED_ID) {
		if (!isset($this->menuEnabled[$gedId])) $this->menuEnabled[$gedId] = PGV_PRIV_PUBLIC;
		return $this->menuEnabled[$gedId];
	}
	public function getTabEnabled($gedId = PGV_GED_ID) {
		if (!isset($this->tabEnabled[$gedId])) $this->tabEnabled[$gedId] = PGV_PRIV_PUBLIC;
		return $this->tabEnabled[$gedId];
	}
	public function getSidebarEnabled($gedId = PGV_GED_ID) {
		if (!isset($this->sidebarEnabled[$gedId])) $this->sidebarEnabled[$gedId] = PGV_PRIV_PUBLIC;
		return $this->sidebarEnabled[$gedId];
	}
	public function getAccessLevelArray() {
		return $this->accessLevel;
	}
	public function getMenuEnabledArray() {
		return $this->menuEnabled;
	}
	public function getTabEnabledArray() {
		return $this->tabEnabled;
	}
	public function getSidebarEnabledArray() {
		return $this->sidebarEnabled;
	}
	public function setName($name) { $this->name = $name; }
	public function setId($id) { $this->id = $id; }
	public function setDescription($d) { $this->description = $d; }
	public function setVersion($v) { $this->version = $v; }
	public function setPgvVersion($v) { $this->pgvVersion = $v; }
	public function setMenuorder($o) { $this->menuorder = $o; }
	public function setTaborder($o) { $this->taborder = $o; }
	public function setSidebarorder($o) { $this->sidebarorder = $o; }

	public function setAccessLevel($access, $gedId=PGV_GED_ID) {
		$this->accessLevel[$gedId] = $access;
	}
	public function setMenuEnabled($access, $gedId=PGV_GED_ID) {
		$this->menuEnabled[$gedId] = $access;
	}
	public function setTabEnabled($access, $gedId=PGV_GED_ID) {
		$this->tabEnabled[$gedId] = $access;
	}
	public function setSidebarEnabled($access, $gedId=PGV_GED_ID) {
		$this->sidebarEnabled[$gedId] = $access;
	}
	public function setGeneralAccess($type, $access, $gedId) {
		switch($type) {
			case 'A':
				$this->setAccessLevel($access, $gedId);
				break;
			case 'T':
				$this->setTabEnabled($access, $gedId);
				break;
			case 'M':
				$this->setMenuEnabled($access, $gedId);
				break;
			case 'S':
				$this->setSidebarEnabled($access, $gedId);
				break;
		}
	}

	/**
	 * does this module implement a tab
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasTab() { return false; }
	/**
	 * does this module implement a menu
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasMenu() { return false; }

	/**
	 * does this module implement a sidebar
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function hasSidebar() { return false; }


	/**
	 * does this module implement a menu
	 * should be overidden in extending classes
	 * @return boolean
	 */
	public function getConfigLink() { return $this->configLink; }

	/**
	 * get the menu for this module
	 * should be overidden in extending classes
	 * @return Menu
	 */
	public function &getMenu() { return null; }
	/**
	 * get the tab for this module
	 * @return Tab
	 */
	public function &getTab() { return null; }

	/**
	 * get the sidebar for this module
	 * @return Sidebar
	 */
	public function &getSidebar() { return null; }

	static function compare_tab_order(&$a, &$b) {
		return $a->getTaborder() - $b->getTaborder();
	}

	static function compare_menu_order(&$a, &$b) {
		return $a->getMenuorder() - $b->getMenuorder();
	}

	static function compare_sidebar_order(&$a, &$b) {
		return $a->getSidebarorder() - $b->getSidebarorder();
	}

	static function compare_name(&$a, &$b) {
		return strcmp($a->getName(), $b->getName());
	}

	static function getActiveList($type='A', $access = PGV_USER_ACCESS_LEVEL, $ged_id = PGV_GED_ID) {
		global $TBLPREFIX;

		$modules = array();
		$statement=PGV_DB::prepare(
			"SELECT * FROM {$TBLPREFIX}module JOIN {$TBLPREFIX}module_privacy ON mod_id=mp_mod_id WHERE mp_access>=? AND mp_type='{$type}' AND mp_file=?"
		);
		$statement->execute(array($access, $ged_id));
		$entry = "";
		while($row = $statement->fetch()) {
			if ($row->mod_name!=$entry) {
				$entry = $row->mod_name;
				$mod = PGVModule::getInstance($row);
				if ($mod) {
					$modules[$entry] = $mod;
					$mod->setGeneralAccess($row->mp_type, $row->mp_access, $row->mp_file);
				}
				else AddToLog("Invalide module ".$entry);
			}
			else {
				$mod = $modules[$entry];
				$mod->setGeneralAccess($row->mp_type, $row->mp_access, $row->mp_file);
			}

		}
		return $modules;
	}

	static function getActiveListAllGeds($access = PGV_USER_ACCESS_LEVEL) {
		global $TBLPREFIX;

		$modules = array();
		$statement=PGV_DB::prepare(
			"SELECT * FROM {$TBLPREFIX}module JOIN {$TBLPREFIX}module_privacy ON mod_id=mp_mod_id WHERE mp_access>=?"
		);
		$statement->execute(array($access));
		$entry = "";
		while($row = $statement->fetch()) {
			if ($row->mod_name!=$entry) {
				$entry = $row->mod_name;
				$mod = PGVModule::getInstance($row);
				if ($mod) { 
					$modules[$entry] = $mod;
					$mod->setGeneralAccess($row->mp_type, $row->mp_access, $row->mp_file);
				}
				else AddToLog("Invalide module ".$entry);
			}
			else {
				$mod = $modules[$entry];
				$mod->setGeneralAccess($row->mp_type, $row->mp_access, $row->mp_file);
			}

		}
		return $modules;
	}

	static function getInstalledList() {
		static $modules;
		if ($modules==null) {
			$modules = array();
			if (!file_exists("modules")) return $this->modules;
			$d = dir("modules");
			while (false !== ($entry = $d->read())) {
				if ($entry{0}!="." && $entry!=".svn" && is_dir("modules/$entry")) {
					if (file_exists("modules/$entry/pgv_module.php")) {
						include_once("modules/$entry/pgv_module.php");
						$menu_class = $entry."_PGVModule";
						$obj = new $menu_class();
						$mod = PGVModule::getModuleByName($entry);
						if ($mod!=null) {
							$mod->setVersion($obj->getVersion());
							$mod->setPgvVersion($obj->getPgvVersion());
							$modules[$entry] = $mod;
						}
						else {
							$modules[$entry] = $obj;
						}
					}
				}
			}
			$d->close();
		}
		return $modules;
	}

	static function getModuleByName($name) {
		global $TBLPREFIX;

		$stmt = PGV_DB::prepare("SELECT * FROM {$TBLPREFIX}module JOIN {$TBLPREFIX}module_privacy ON mod_id=mp_mod_id WHERE mod_name=?");
		$stmt->execute(array($name));
		$row = $stmt->fetchOne();
		$entry = "";
		$mod = null;
		while($row = $stmt->fetch()) {
			if ($row->mod_name!=$entry) {
				$entry = $row->mod_name;
				$mod = PGVModule::getInstance($row);
				$modules[$entry] = $mod;
				$mod->setGeneralAccess($row->mp_type, $row->mp_access, $row->mp_file);
			}
			else {
				$mod = $modules[$entry];
				$mod->setGeneralAccess($row->mp_type, $row->mp_access, $row->mp_file);
			}

		}
		return $mod;
	}

	static function getById($id) {
		global $TBLPREFIX;

		$stmt = PGV_DB::prepare("SELECT * FROM {$TBLPREFIX}module JOIN {$TBLPREFIX}module_privacy ON mod_id=mp_mod_id WHERE mod_id=?");
		$stmt->execute(array($id));
		$row = $stmt->fetchOne();
		$entry = "";
		$mod = null;
		while($row = $stmt->fetch()) {
			if ($row->mod_name!=$entry) {
				$entry = $row->mod_name;
				$mod = PGVModule::getInstance($row);
				$modules[$entry] = $mod;
				$mod->setGeneralAccess($row->mp_type, $row->mp_access, $row->mp_file);
			}
			else {
				$mod = $modules[$entry];
				$mod->setGeneralAccess($row->mp_type, $row->mp_access, $row->mp_file);
			}

		}
		return $mod;
	}

	/**
	 * Insert or Update a module in the database
	 * @param $mod PGVModule
	 * @return null
	 */
	static function updateModule(&$mod) {
		global $TBLPREFIX;
		if ($mod->getId()==0) {
			$sql = "insert into {$TBLPREFIX}module (mod_id, mod_name, mod_description, mod_taborder, mod_menuorder, mod_sidebarorder) values(?,?,?,?,?,?)";
			$stmt = PGV_DB::prepare($sql);
			$mod->setId(get_next_id("module","mod_id"));
			$stmt->execute(array($mod->getId(),$mod->getName(), $mod->getDescription(), $mod->getTaborder(), $mod->getMenuorder(), $mod->getSidebarorder()));
			$sql = "insert into {$TBLPREFIX}module_privacy (mp_mod_id,mp_file,mp_access,mp_type) values(?,?,?,?)";
			$stmt = PGV_DB::prepare($sql);
			foreach ($mod->getAccessLevelArray() as $ged_id=>$mp) {
				$stmt->execute(array($mod->getId(), $ged_id, $mp, 'A'));
			}
			foreach ($mod->getMenuEnabledArray() as $ged_id=>$mp) {
				$stmt->execute(array($mod->getId(), $ged_id, $mp, 'M'));
			}
			foreach ($mod->getTabEnabledArray() as $ged_id=>$mp) {
				$stmt->execute(array($mod->getId(), $ged_id, $mp, 'T'));
			}
			foreach ($mod->getSidebarEnabledArray() as $ged_id=>$mp) {
				$stmt->execute(array($mod->getId(), $ged_id, $mp, 'S'));
			}
		}
		else {
			$sql = "UPDATE {$TBLPREFIX}module SET mod_name=?, mod_description=?, mod_taborder=?, mod_menuorder=?, mod_sidebarorder=? WHERE mod_id=?";
			$stmt = PGV_DB::prepare($sql);
			$stmt->execute(array($mod->getName(), $mod->getDescription(), $mod->getTaborder(), $mod->getMenuorder(), $mod->getSidebarorder(), $mod->getId()));

			//-- delete the old privacy settings
			$sql = "delete from {$TBLPREFIX}module_privacy where mp_mod_id=?";
			$stmt = PGV_DB::prepare($sql);
			$stmt->execute(array($mod->getId()));

			//-- store the new privacy settings
			$sql = "insert into {$TBLPREFIX}module_privacy (mp_mod_id,mp_file,mp_access,mp_type) values(?,?,?,?)";
			$stmt = PGV_DB::prepare($sql);
			foreach ($mod->getAccessLevelArray() as $ged_id=>$mp) {
				$stmt->execute(array($mod->getId(), $ged_id, $mp, 'A'));
			}
			foreach ($mod->getMenuEnabledArray() as $ged_id=>$mp) {
				$stmt->execute(array($mod->getId(), $ged_id, $mp, 'M'));
			}
			foreach ($mod->getTabEnabledArray() as $ged_id=>$mp) {
				$stmt->execute(array($mod->getId(), $ged_id, $mp, 'T'));
			}
			foreach ($mod->getSidebarEnabledArray() as $ged_id=>$mp) {
				$stmt->execute(array($mod->getId(), $ged_id, $mp, 'S'));
			}
		}
	}

	static function setDefaultTabs($ged_id) {
		$modules = PGVModule::getInstalledList();
		$taborder = 1;
		foreach(self::$default_tabs as $modname) {
			if (isset($modules[$modname])) {
				$mod = $modules[$modname];
				if ($mod->hasTab()) {
					$mod->setTaborder($taborder);
					$mod->setAccessLevel(PGV_PRIV_PUBLIC, $ged_id);
					$mod->setTabEnabled(PGV_PRIV_PUBLIC, $ged_id);
					PGVModule::updateModule($mod);
					$taborder++;
				}
			}
		}
	}

	static function setDefaultMenus($ged_id) {
		$modules = PGVModule::getInstalledList();
		$taborder = 0;
		foreach(self::$default_menus as $modname) {
			if (isset($modules[$modname])) {
				$mod = $modules[$modname];
				if ($mod->hasMenu()) {
					$mod->setMenuorder($taborder);
					$mod->setAccessLevel(PGV_PRIV_PUBLIC, $ged_id);
					$mod->setMenuEnabled(PGV_PRIV_PUBLIC, $ged_id);
					PGVModule::updateModule($mod);
					$taborder++;
				}
			}
		}
	}

	static function setDefaultSidebars($ged_id) {
		$modules = PGVModule::getInstalledList();
		$taborder = 0;
		foreach(self::$default_sidebars as $modname) {
			if (isset($modules[$modname])) {
				$mod = $modules[$modname];
				if ($mod->hasSidebar()) {
					$mod->setSidebarorder($taborder);
					$mod->setAccessLevel(PGV_PRIV_PUBLIC, $ged_id);
					$mod->setSidebarEnabled(PGV_PRIV_PUBLIC, $ged_id);
					PGVModule::updateModule($mod);
					$taborder++;
				}
			}
		}
	}
}
?>