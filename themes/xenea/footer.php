<?php
/**
 * Footer for Xenea theme
 *
 * PhpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  John Finlay and others.  All rights reserved.
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
 * @subpackage Themes
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}


print "</div> <!-- closing div id=\"content\" -->\n";//FIXME uncomment as soon as ready
print "<div id=\"footer\" class=\"$TEXT_DIRECTION\">";
print "\n\t<div align=\"center\" style=\"width:99%;\">";
print contact_links();
print '<br /><a href="'.PGV_PHPGEDVIEW_URL.'" target="_blank"><img src="'.$PGV_IMAGE_DIR.'/'.$PGV_IMAGES['gedview']['other'].'" width="100" height="45" border="0" alt="'.PGV_PHPGEDVIEW.' '.PGV_VERSION_TEXT.'" title="'.PGV_PHPGEDVIEW.' '.PGV_VERSION_TEXT.'" /></a><br />';
print "\n\t<br />";
print_help_link("preview_help", "qm");
print "<a href=\"$SCRIPT_NAME?view=preview&amp;".get_query_string()."\">".$pgv_lang["print_preview"]."</a>";
print "<br />";
if ($SHOW_STATS || (isset($DEBUG) && ($DEBUG==true))) print_execution_stats();
if ($buildindex) print " ".$pgv_lang["build_error"]."  <a href=\"editgedcoms.php\">".$pgv_lang["rebuild_indexes"]."</a>\n";
if (exists_pending_change()) {
	print "<br />".$pgv_lang["changes_exist"]." <a href=\"javascript:;\" onclick=\"window.open('edit_changes.php','_blank','width=600,height=500,resizable=1,scrollbars=1'); return false;\">".$pgv_lang["accept_changes"]."</a>\n";
}
print "</div>";
print "</div> <!-- close div id=\"footer\" -->\n";
?>
