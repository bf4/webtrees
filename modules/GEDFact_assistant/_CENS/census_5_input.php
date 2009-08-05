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
/*
?>
<style type="text/css">
<!--
#tblSample td, th { padding: 0.2em; }
.classy0 { background-color: #cccccc; color: #000000; }
.classy1 { background-color: #cccccc; color: #000000; }
-->
</style>
<?php
*/
echo '<script src="modules/GEDFact_assistant/_CENS/js/census_5_input.js" type="text/javascript"></script>';
?>

<center>

<table><tr><td>

<div id="wn" style="width:69.8em; overflow:auto;">
    <div id="lyr1" style="height:15.46em; overflow:auto;">    
	<table id="t1" border="0" cellpadding="0" cellspacing="2">
        <tr>
			<td>
			
			<table width="100%" border="0" cellspacing="2">
				<tr>
					<td align="center" colspan="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td align="center">
						<input type="button" value="Add/Insert Blank Row" onclick="insertRowToTable('','','','','','','Age','','','');" />
					<td align="center" colspan="3">&nbsp;</td>
					<td align="right">
						<font size="1">Add</font><br>
						<input  type="radio" name="totallyrad" value="0" checked="checked" />
					</td>
					<td width="2%"><font size="1"></font></td>
				</tr>
			</table>
			
			<table style="width:68em;" border="0" cellspacing="1" id="tblSample">
				<thead>
				<tr>
					<th width="2%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>#</font></th>
					<th width="3%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Indi ID</font></th>
					<th id=".b.Name" width="10%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Name</font></th>
					<th id=".b.Relation" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Relation</font></th>
					<th id=".b.Cond" width="3%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Cond</font></th>
					<th id=".b.YOB" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>YOB</font></th>
					<th id=".b.Age" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Age</font></th>
					<th id=".b.YMD" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Y/M/D</font></th>
					<th id=".b.Sex" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Sex</font></th>
					<th id=".b.Occupation" width="13%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Occupation</font></th>
					<th id=".b.Birthplace" width="9%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Birthplace</font></th>
					<?php  if ($ctry == "USA") { ?>
						<th id=".b.Father Birthplace" width="9%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Father Birthplace</font></th>
						<th id=".b.Mother Birthplace" width="9%" class="descriptionbox" style="border: 0px solid transparent;" align="left"><font size=1>Mother Birthplace</font></th>
					<?php  } ?>
					<th class="descriptionbox" style="border: 0px solid transparent;" align="center"><font size=1>Del</font></th>
					<th width="3%" class="descriptionbox" style="border: 0px solid transparent;" align="center"><font size=1>Ins</font></th>
					<th width="2%" class="descriptionbox" style="border: 0px solid transparent;" align="center"><font size=1>#</font></th>
				</tr>
				</thead>

				<tbody>
				</tbody>
				
			</table>
			</td>
		</tr>
	</table>
	
	</div> <!-- end lyr1 div -->
</div>  <!-- end wn div -->
</td>
</tr></table>

</center>
