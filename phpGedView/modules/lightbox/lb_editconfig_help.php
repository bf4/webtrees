<?php
/**
 * English Language Configure Help file for PHPGedView
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2007  PGV Development Team
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
 * @package webtrees
 * @subpackage Admin
 * $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$help = safe_GET("help");

print_simple_header(i18n::translate('Configuration help'));
print '<span class="helpheader">';
echo i18n::translate('Configuration help');
print '</span><br /><br /><span class="helptext">';
if ($help == "help_contents_help") {
		if (PGV_USER_IS_ADMIN) {
		$help = "admin_help_contents_help";
		print_text("admin_help_contents_head_help");
	}
	else print_text("help_contents_head_help");
	print_help_index($help);
}
else {
	if ($help == "help_uploadgedcom.php") $help = "help_addgedcom.php";
print_text($help);
}
print "</span><br /><br />";
print "<a href=\"help_text.php?help=help_contents_help\"><b>";
echo i18n::translate('Help Contents');
print "</b></a><br />";
print "<a href=\"javascript:;\" onclick=\"window.close();\"><b>";
echo i18n::translate('Close Window');
print "</b></a>";
print_simple_footer();
?>
