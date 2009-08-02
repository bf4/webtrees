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
echo '<script src="modules/GEDFact_assistant/_CENS/js/census_5_input.js" type="text/javascript"></script>';
?>

<center>

<script src="modules/GEDFact_assistant/_CENS/js/dw_event.js" type="text/javascript"></script>
<script src="modules/GEDFact_assistant/_CENS/js/dw_scroll.js" type="text/javascript"></script>
<script src="modules/GEDFact_assistant/_CENS/js/dw_scrollbar.js" type="text/javascript"></script>
<script src="modules/GEDFact_assistant/_CENS/js/scroll_controls.js" type="text/javascript"></script>

<script type="text/javascript">
function init_dw_Scroll() {
    var wndo = new dw_scrollObj('wn', 'lyr1', 't1');
 //   var wndo2 = new dw_scrollObj('wn2', 'lyr2', 't2');
    wndo.setUpScrollbar("dragBar", "track", "h", 1, 1);
    wndo.setUpScrollControls('scrollbar');
	wndo.setUpScrollbar("dragBar2", "track2", "v", 1, 1);
    wndo.setUpScrollControls('scrollbar2');
}

// if code supported, link in the style sheet and call the init function onload
if ( dw_scrollObj.isSupported() ) {
	dw_writeStyleSheet('modules/GEDFact_assistant/_CENS/css/CENS_scrolling.css');
    dw_Event.add( window, 'load', init_dw_Scroll);
}
</script>

<div id="scrollbar">
    <div id="left"><a class="mouseover_left" href="#"><img src="modules/GEDFact_assistant/_CENS/images/btn-lft.gif" width="13" height="13" alt="" border="0" /></a></div>
    <div id="track">
         <div id="dragBar"></div>
    </div>
    <div id="right"><a class="mouseover_right" href="#"><img src="modules/GEDFact_assistant/_CENS/images/btn-rt.gif" width="13" height="13" alt="" border="0" /></a></div>
</div>
<!-- border attribute added to reduce support questions on the subject. If you like valid strict markup, remove and place a img {border:none;} spec in style sheet -->
	
<table><tr><td>

<div id="wn">
    <div id="lyr1">    
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
			
			<table width="812px" border="0" cellspacing="1" id="tblSample">
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
			
			<!-- spacing required for optimal view horizontally -->
			<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<!-- spacing required for optimal view vertically -->
		<tr><td height="276"> <br /><br /> </td></tr>
		<tr><td height="276"> <br /><br /> </td></tr>
	</table>
	
	</div> <!-- end lyr1 div -->
</div>  <!-- end wn div -->
</td>

<td>
<div id="scrollbar2">
	<div id="up2"><a class="mouseover_up" href="#"><img src="modules/GEDFact_assistant/_CENS/images/btn-up.gif" width="13" height="13" alt="" border="0" /></a></div>
	<div id="track2">
		<div id="dragBar2"></div>
	</div>
	<div id="down2"><a class="mouseover_down" href="#"><img src="modules/GEDFact_assistant/_CENS/images/btn-dn.gif" width="13" height="13" alt="" border="0" /></a></div>
</div>
</td>
</tr></table>

</center>
