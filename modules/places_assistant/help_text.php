<?php
/**
 * Module help text.
 *
 * This file is included from the application help_text.php script.
 * It simply needs to set $title and $text for the help topic $help_topic
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2011 webtrees development team.
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
 */

if (!defined('WT_WEBTREES') || !defined('WT_SCRIPT_NAME') || WT_SCRIPT_NAME!='help_text.php') {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

switch ($help) {

case 'SPLIT_PLACES':
	$title=WT_I18N::translate('Split places in edit mode');
	$text=WT_I18N::translate('Split each place name by commas into subfields for easier editing.  Example :<br /><ol><li>Default mode<br /><u>Place</u>: Half Moon Bay, San Mateo, California, USA<br /><br /></li><li>Split mode<br /><u>Country</u>: USA<br /><u>State</u>: California<br/><u>County</u>: San Mateo<br/><u>City</u>: Half Moon Bay</li></ol>');
	break;
}
