<?php
// Classes and libraries for module system
//
// webtrees: Web based Family History software
// Copyright (C) 2011 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2010 John Finlay
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// @version $Id$

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class batch_update_WT_Module extends WT_Module implements WT_Module_Config{
	// Extend WT_Module
	public function getTitle() {
		return WT_I18N::translate('Batch Update');
	}

	// Extend WT_Module
	public function getDescription() {
		return WT_I18N::translate('Perform bulk updates and corrections on your GEDCOM data');
	}

	// Extend WT_Module
	public function modAction($mod_action) {
		switch($mod_action) {
		case 'admin_batch_update':
			// TODO: these files should be methods in this class
			require WT_ROOT.WT_MODULES_DIR.$this->getName().'/'.$mod_action.'.php';
			$mod=new batch_update;
			echo $mod->main();
			break;
		}
	}

	// Implement WT_Module_Config
	public function getConfigLink() {
		return 'module.php?mod='.$this->getName().'&amp;mod_action=admin_batch_update';
	}
}