<?php 
/**
 * Search Plug-in for www.genealogy.com
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008 John Finlay and others. All rights reserved.
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
 * @version $Id$
 * @package PhpGedView
 * @subpackage Research Assistant
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once PGV_ROOT.'modules/research_assistant/search_plugin/base_autosearch.php';

class AutoSearch extends Base_AutoSearch {
	function AutoSearch() {
		parent::__construct(
			// Name of this file, without the extension.
			// NB must also define this in $pgv_lang["autosearch_plugin_name_XXXX"]
			basename(__FILE__, '.php'),
			// Search engine URL, ending in "?" or "&"
			'http://www.genealogy.com/cgi-bin/wizard_search.cgi?MN=&',
			// Form method: GET or POST
			'GET',
			// Array keys are field names for the URL.
			// Array values are:
			//  function = defined in Base_AutoSearch and $pgv_lang["autosearch_XXXX"]
			//  extra    = extra html to add to the checkbox
			array(
				'FN'       =>array('function'=>'givenname', 'extra'=>'checked="checked"'),
				'LN'       =>array('function'=>'surname',   'extra'=>'checked="checked"'),
				'BDATE'    =>array('function'=>'byear',     'extra'=>'checked="checked"'),
				'BLOCATION'=>array('function'=>'bloc',      'extra'=>'checked="checked"'),
				'DDATE'    =>array('function'=>'dyear',     'extra'=>'checked="checked"'),
				'DLOCATION'=>array('function'=>'dloc',      'extra'=>'checked="checked"')
			)
		);
	}
}

?>
