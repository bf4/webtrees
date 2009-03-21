<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census input table area
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
 * @package PhpGedView
 * @subpackage Census Assistant
 * @version $Id$
 */

?>

<!--
#tblSample td, th { padding: 0.5; }
.classy0 { background-color: #234567; color: #89abcd; }
.classy1 { background-color: #89abcd; color: #234567; }
-->

<!--
#tblSample td, th { padding: 0.2em; }
.classy0 { background-color: #FFD8D8; color: #000000; }
.classy1 { background-color: #E0E9FF; color: #000000; }
.classy2 { background-color: #dddddd; color: #000000; }
-->

<style type="text/css">
<!--
#tblSample td, th { padding: 0.2em; }
.classy0 { background-color: #cccccc; color: #000000; }
.classy1 { background-color: #cccccc; color: #000000; }
-->
</style>

<?php
echo '<script src="modules/GEDFact_assistant/CENS/census_5_input.js" type="text/javascript"></script>';
?>

<center>
	<table width="100%" border="0" cellspacing="2">
		<tr>
			<td align="center" colspan="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td align="center">
				<input type="button" value="Add/Insert Blank Row" onclick="insertRowToTable('','','','','','','','','','');" />
				<!-- <input type="button" value="Delete [D]" onclick="deleteChecked();" /> -->
				<!-- <input type="button" value="Debug Window" onclick="openInNewWindow(this.form);" /> -->
			<td align="center" colspan="3">&nbsp;</td>
			<td align="right">
				<font size="1">Add&nbsp;&nbsp;&nbsp;</font><br>
				<input align="right" type="radio" name="totallyrad" value="0" checked="checked" /><font size="1">&nbsp;&nbsp;</font>
			</td>
		</tr>
	</table>
	<table width="100%" border="0" cellspacing="2" id="tblSample">
		<thead>
			<tr>
			<th class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Item</font></th>
			<th class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Indi ID:</font></th>
			<th width="17%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Name:</font></th>
			<th class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Relation:</font></th>
			<th class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Status:</font></th>
			<th class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>YOB:</font></th>
			<th class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Age:</font></th>
			<th class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Y/M/D:</font></th>
			<th class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Sex:</font></th>
			<th width="13%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Occupation:</font></th>
			<th width="28%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Birthplace:</font></th>
			<th class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Del</font></th>
			<th class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Ins</font></th>
		</tr>
	</thead>
	<tbody></tbody>
</table>
</center>

<!-- </form> -->
	