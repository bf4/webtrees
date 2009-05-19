<?php
/**
 * Media Link Assistant Control module for phpGedView
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
function help_window() {
var win02 = window.open(
"modules/GEDFact_assistant/MEDIA/help.php", "win02", "resizable=1, menubar=0, scrollbars=1, left=70 top=20, HEIGHT=500, WIDTH=450 ");
if (window.focus) {win02.focus();}
}
</script>

			<!--   ---- Save Preview Area -------- -->
			<table class="facts_table" width="60%" border=0>
				<tr>
					<td align="center" class="descriptionbox" colspan="1">
						<input type="button" value="<?php echo $pgv_lang["page_help"]; ?>" onclick="javascript: help_window(this.form);" />
					</td>
				</tr>
			</table>
			
