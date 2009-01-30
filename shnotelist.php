<?php
/**
 * Parses gedcom file and displays a list of the shared notes in the file.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Lists
 */

require './config.php';
require_once 'includes/functions/functions_print_lists.php';

print_header($pgv_lang['shnote_list']);

echo '<div class="center"><h2>'.$pgv_lang['shnote_list'].'</h2>';
print_shnote_table(get_shnote_list(PGV_GED_ID));
?>
<script language="javascript" type="text/javascript">
<!--
function addnew_shnote() {
	win04 = window.open(
	"edit_interface.php?action=addnewshnote&pid=newshnote", "win04", "top=70, left=70, width=600, height=500, resizable=1, scrollbars=1 ");
	if (window.focus) {win04.focus();}
}
-->
</script
<?php
echo "<a href=\"javascript: addnew_shnote()\"> ";
echo "<b>".$pgv_lang['create_shnote']."</b>";
echo "</a>";
echo '</div>';

echo "<br /><br />";

print_footer();
?>
