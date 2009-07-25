<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census Proposed Text Area File
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 to 2008  PGV Development Team.  All rights reserved.
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
if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

global $theme_name; 

?>
<script>
function openInNewWindow(frm)
{
	// open a blank window
	var aWindow = window.open('', 'TableAddRow2NewWindow',
	'scrollbars=yes,menubar=yes,resizable=yes,location=no,toolbar=no,width=400,height=700');
	aWindow.focus();
	
	// set the target to the blank window
	frm.target = 'TableAddRow2NewWindow';
	
	// submit
	frm.submit();
}
function help_window2(frm)
{
	// open a blank window
	var aWindow = window.open('modules/GEDFact_assistant/_CENS/census_asst_help.php', 'TableAddRow2NewWindow',
	'scrollbars=yes,menubar=yes,resizable=yes,location=no,toolbar=no,width=400,height=700');
	aWindow.focus();
	
	// set the target to the blank window
	frm.target = 'TableAddRow2NewWindow';
	
	// submit
	//frm.submit();
}
function help_window() {
var win02 = window.open(
"modules/GEDFact_assistant/tableaddrow_nw.html", "win02", "resizable=1, menubar=0, scrollbars=1, top=20, HEIGHT=840, WIDTH=450 ");
if (window.focus) {win02.focus();}
}
</script>

			<!--   ---- The proposed Census Text -------- -->
			<table class="facts_table" width="60%" border=3>
				<tr>
					<td align="center" class="descriptionbox" colspan="1">
						<input type="button" value="<?php echo $pgv_lang["page_help"]; ?>" onclick="javascript: help_window2(this.form)" />
					</td>
					<td align="center" class="descriptionbox" colspan="2">
						<b> The Proposed Census Text </b>&nbsp;&nbsp;
						<font size="1">
						<input type="button" value="<?php echo $pgv_lang["preview"]; ?>" onclick="preview();" />
						&nbsp;&nbsp; Click "Preview" to copy Input Fields Information.
						</font>
					</td>
					<td align="center" class="descriptionbox" colspan="1">
					<?php
						echo "<input type=\"submit\" value=\"".$pgv_lang["save"]."\" />";
					?>
					</td>
				</tr>
				
				<tr>
					<?php
					echo "<td class=\"descriptionbox\" ".$TEXT_DIRECTION." wrap=\"nowrap\">";
						print_help_link("edit_SHARED_NOTE_help", "qm");
					echo $pgv_lang["shared_note"];
					echo "</td>";
					echo "<td class=\"optionbox wrap\" ><center><textarea name=\"NOTE\" id=\"NOTE\" rows=\"20\" cols=\"88\"></textarea></center>";
						print_specialchar_link("NOTE",true);
					echo "</td>";
					echo "<td class=\"facts_value wrap\" colspan=2></td>";
					?>
				</tr>
			</table>
			
