<?php
/**
 * Provides media tab header for reorder media Items using drag and drop
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PHPGedView Development Team
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
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */
?>
<?php
	require_once("js/prototype.js.htm");
	require_once("js/scriptaculous.js.htm");
?>
<script language="javascript" type="text/javascript">
<!--
	function reorder_media() {
	var win02 = window.open(
	"edit_interface.php?action=reorder_media&pid=<?php print $pid; ?>", "win02", "resizable=1, menubar=0, scrollbars=1, top=20, HEIGHT=840, WIDTH=450 ");
	if (window.focus) {win02.focus();}
	}   
-->
</script>
<?php
		print "<table border=0 width=\"75%\"><tr>";
		print "<td class=\"width10 center wrap\" valign=\"top\"></td>";

		if ($LB_AL_HEAD_LINKS == "icon") {
		print "<td>";
		}

		//Popup Reorder Media
		if (PGV_USER_CAN_EDIT) {
			if ($LB_AL_HEAD_LINKS == "both") {
				print "<td class=\"width15 center wrap\" valign=\"top\">";
				print "<a href=\"javascript: reorder_media()\" title=\"Reorder Media Popup\" >" ;
				print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\"  title=\"Reorder Media Popup\" /><br />" ;
				print "" . $pgv_lang["reorder_media"] . "&nbsp;";
				print '</a>';
				print "</td>";
				//print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "text") {
				print "<td class=\"width15 center wrap\" valign=\"top\">";
				print "<a href=\"javascript: reorder_media()\" title=\"Reorder Media Popup\" >" ;
				print "" . $pgv_lang["reorder_media"] . "&nbsp;";
				print '</a>';
				print "</td>";
				//print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}else if ($LB_AL_HEAD_LINKS == "icon") {
				print "&nbsp;&nbsp;&nbsp;&nbsp;";
				print "<a href=\"javascript: reorder_media()\" title=\"Reorder Media Popup\" >" ;
				print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\"  title=\"Reorder Media Popup\" /><br />" ;
				print '</a>';
				//print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}else{
				print "<td class=\"width15 center wrap\" valign=\"top\">";
				print "<a href=\"javascript: reorder_media()\" title=\"Reorder Media Popup\" >" ;
				print "" . $pgv_lang["reorder_media"] . "&nbsp;";
				print '</a>';
				print "</td>";
				//print "<td width=\"5%\">&nbsp;</td>";
				print "\n";
			}
			
		}
		
		if ($LB_AL_HEAD_LINKS == "icon") {
		print "</td>";
		}
		
		print "</tr></table>";
?>
