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
<div class="optionbox" style="text-align:left; padding:3px; border:3px outset; margin-bottom:3px;">
	<span style="margin:0 5px 0 5px;"><input type="button" value="<?php echo $pgv_lang["page_help"]; ?>" onclick="javascript: help_window2(this.form)" /></span>
	<span style="font-weight:bold;">Proposed Census Text&nbsp;&nbsp;</span>
	<span style="margin:0 5px 0 5px;"><input type="button" value="<?php echo $pgv_lang["preview"]; ?>" onclick="preview();" /></span>
	<span class="font10">Click "Preview" to copy Input Fields Information.</span>
	<span style="margin:0 0 5px 32px;"><input type="submit" value="<?php echo $pgv_lang["save"]; ?>" /></span>
	<br /><br />
	<span class="descriptionbox width15 nowrap <?php $TEXT_DIRECTION; ?>">
		<?php
			print_help_link("edit_SHARED_NOTE_help", "qm");
			echo $pgv_lang["shared_note"];
		?>
	</span>
	<div class="optionbox wrap center" >
		<textarea name="NOTE" id="NOTE" rows="20" style="width:98%;"></textarea><br />
		<?php print_specialchar_link("NOTE",true); ?>
	</div>
</div>




