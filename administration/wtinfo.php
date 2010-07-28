<?php
/**
 * Displays information on the PHP installation
 *
 * Provides links for administrators to get to other administrative areas of the site
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
 * @version $Id: wtinfo.php 8511 2010-05-29 07:10:55Z lucasz $
 */

define('WT_SCRIPT_NAME', 'wtinfo.php');
require '../includes/session.php';
require 'admin_functions.php';

//if (!WT_USER_GEDCOM_ADMIN) {
//	header("Location: ../login.php?url=wtinfo.php?action=".$action);
//exit;
//}

if (isset($_REQUEST['action'])) $action = $_REQUEST['action'];

if (!isset($action)) $action = "";

if ($action == "phpinfo") {
	$helpindex = "phpinfo_help";
	admin_header(i18n::translate('PHP information'));
	
	echo '<h1>', i18n::translate('PHP information'), '</h1>';
	echo '<div>';
	
	ob_start();

	phpinfo();
	$php_info = ob_get_contents();

	ob_end_clean();

	$php_info    = str_replace(" width=\"600\"", " width=\"\"", $php_info);
	$php_info    = str_replace("</body></html>", "", $php_info);
	$php_info    = str_replace("<table", "<table class=\"facts_table ltr\"", $php_info);
	$php_info    = str_replace("td class=\"e\"", "td class=\"facts_value\"", $php_info);
	$php_info    = str_replace("td class=\"v\"", "td class=\"facts_value\"", $php_info);
	$php_info    = str_replace("tr class=\"v\"", "tr", $php_info);
	$php_info    = str_replace("tr class=\"h\"", "tr", $php_info);

	$php_info    = str_replace(";", "; ", $php_info);
	$php_info    = str_replace(",", ", ", $php_info);

	// Put logo in table header
	$logo_offset = strpos($php_info, "<td>");
	$php_info = substr_replace($php_info, "<td colspan=\"3\" class=\"center\">", $logo_offset, 4);
	$logo_width_offset = strpos($php_info, "width=\"\"");
	$php_info = substr_replace($php_info, "", $logo_width_offset, 8);
	$php_info = str_replace(" width=\"\"", "", $php_info);

	$offset = strpos($php_info, "<table");
	$php_info = substr($php_info, $offset);

	echo $php_info;

	echo '</div>';

	//	exit;
}

include 'admin_footer.php';
?>
