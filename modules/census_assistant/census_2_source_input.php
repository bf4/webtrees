<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census and Souce Input Area File File
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
?>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
var OutputAddress = "";

var OutputCell_x_2 = "";
var OutputCell_x_3 = "";
var OutputCell_x_4 = "";
var OutputCell_x_5 = "";
var OutputCell_x_7 = "";
var OutputCell_x_9 = "";
var OutputCell_x_10 = "";


// ===== ** Important ** ===========================
// variable rowtimes comes from "census_input_head.php"
// var rowtimes=11;
// ===============================================

function InitSaveVariables(form) {
	OutputAddress		=	form.OutputAddress.value;
	OutputClass			=	form.OutputClass.value;
	OutputPiece			=	form.OutputPiece.value;
	OutputFolio			=	form.OutputFolio.value;
	OutputPage			=	form.OutputPage.value;
	OutputCensusSource	=	form.OutputCensusSource.value;
	OutputCensusPlace	=	form.OutputCensusPlace.value;
	
	for (var r = 1; r <=rowtimes; r++) {
		//OutputCell_x_1		=	form['OutputCell_'+r+'_1'].value;  // Id
		OutputCell_x_2		=	form['OutputCell_'+r+'_2'].value;  // Name
		OutputCell_x_3		=	form['OutputCell_'+r+'_3'].value;  // Relationship
		OutputCell_x_4		=	form['OutputCell_'+r+'_4'].value;  // Gend
		OutputCell_x_5		=	form['OutputCell_'+r+'_5'].value;  // Cond
		//OutputCell_x_6r		=	form['OutputCell_'+r+'_6'].value;  // YOB
		OutputCell_x_7		=	form['OutputCell_'+r+'_7'].value;  // Age
		//OutputCell_x_8		=	form['OutputCell_'+r+'_8'].value;  // YMD
		OutputCell_x_9		=	form['OutputCell_'+r+'_9'].value;  // Occupation
		OutputCell_x_10		=	form['OutputCell_'+r+'_10'].value; // Birthplace
	}
	
}

function InputToOutput(form) {
	var tbli = document.getElementById(TABLE_NAME);
	var inputrows = tbli.tBodies[0].rows.length;
	
	if (form.copy.checked) {
		InitSaveVariables(form);
		form.OutputAddress.value		=	form.InputAddress.value;
		form.OutputClass.value			=	form.InputClass.value;
		form.OutputPiece.value			=	form.InputPiece.value;
		form.OutputFolio.value			=	form.InputFolio.value;
		form.OutputPage.value			=	form.InputPage.value;
		form.OutputCensusSource.value	=	form.InputCensusSource.value;
		form.OutputCensusPlace.value	=	form.InputCensusPlace.value;
		
		
		for (var s = 1; s <=inputrows; s++) {
		//	form['OutputCell_'+s+'_1'].value	=	form['inputCell_'+s+'_1'].value;  // Id
			form['OutputCell_'+s+'_2'].value	=	form['InputCell_'+s+'_2'].value;  // Name
			form['OutputCell_'+s+'_3'].value	=	form['InputCell_'+s+'_3'].value;  // Relationship
			form['OutputCell_'+s+'_4'].value	=	form['InputCell_'+s+'_4'].value;  // Gend
			form['OutputCell_'+s+'_5'].value	=	form['InputCell_'+s+'_5'].value;  // Cond
		//	form['OutputCell_'+s+'_6'].value	=	form['inputCell_'+s+'_6'].value;  // YOB
			form['OutputCell_'+s+'_7'].value	=	form['InputCell_'+s+'_7'].value;  // Age
		//	form['OutputCell_'+s+'_8'].value	=	form['inputCell_'+s+'_8'].value;  // YMD
			form['OutputCell_'+s+'_9'].value	=	form['InputCell_'+s+'_9'].value;  // Occupation
			form['OutputCell_'+s+'_10'].value	=	form['InputCell_'+s+'_10'].value; // Birthplace
		}
	}else{
		form.OutputAddress.value		=	OutputAddress;
		form.OutputClass.value			=	OutputClass;
		form.OutputPiece.value			=	OutputPiece;
		form.OutputFolio.value			=	OutputFolio;
		form.OutputPage.value			=	OutputPage;
		form.OutputCensusSource.value	=	OutputCensusSource;
		form.OutputCensusPlace.value	=	OutputCensusPlace;

		for (var t = 1; t <=rowtimes; t++) {
		//	form['OutputCell_'+t+'_1'].value		=	OutputCell_x_1;  // id
			form['OutputCell_'+t+'_2'].value		=	OutputCell_x_2;  // Name
			form['OutputCell_'+t+'_3'].value		=	OutputCell_x_3; // Relationship
			form['OutputCell_'+t+'_4'].value		=	OutputCell_x_4; // Gend
			form['OutputCell_'+t+'_5'].value		=	OutputCell_x_5; // Cond
		//	form['OutputCell_'+t+'_6'].value		=	OutputCell_x_6; // YOB
			form['OutputCell_'+t+'_7'].value		=	OutputCell_x_7; // Age
		//	form['OutputCell_'+t+'_8'].value		=	OutputCell_x_8; // YMD
			form['OutputCell_'+t+'_9'].value		=	OutputCell_x_9; // Occupation
			form['OutputCell_'+t+'_10'].value		=	OutputCell_x_10; // Birthplace
		}

	}

}
//  End -->
</script>
<?php

?>

			<table class="facts_table" width="100%" border=3>

				<tr>
					<td align="left" class="descriptionbox" width="10%"><font size=1>Existing Census Source:</font></td>
					<td align="left" class="optionbox" width="60%" colspan="3" >

						<select name="InputCensusSource" style="font-size:10px; background:#FFC0C0;"  onChange="this.style.background=this.options[this.selectedIndex].style.background">  
						<option style="font-size:10px; background:#FFC0C0;" value="Choose Source," selected="selected" >Choose Source</option>
						<option style="font-size:10px; background:#FFFFFF;" value="UK 1901 Census," >UK 1901 Census</option>
						</select>

					</td>
					</tr>
				<tr>
					<td align="left" class="descriptionbox" width="10%"><font size=1>Source ID:</font></td>
					<td align="left" class="facts_value" width="50%">
						<input name="InputCensusSourceID" type="text" size="10" STYLE="font-size: 10px;" value="S2" /></td>
					<td align="left" class="descriptionbox" width="15%"><font size=1>Census Country:</font></td>
					<td align="left" class="facts_value" width="15%">
						<input name="InputCensusCountry" type="text" size="9" STYLE="font-size: 10px;" value="UK" /></td>
				</tr>
				<tr>
					<td align="left" class="descriptionbox" width="10%"><font size=1>Census Place:</font></td>
					<td align="left" class="facts_value" width="50%">
						<input name="InputCensusPlace" type="text" size="70" STYLE="font-size: 10px;" value="Chorley, Lancashire, England" /></td>
					<td align="left" class="descriptionbox" width="15%"><font size=1>Census Year:</font></td>
					<td align="left" class="facts_value" width="15%">
						<input name="InputCensusYear" type="text" size="9" STYLE="font-size: 10px;" value="1901" /></td>
				</tr>
				<tr>
					<td align="left" class="descriptionbox" width="10%"><font size=1>Address:</font></td>
					<td align="left" class="facts_value" width="50%">
						<input name="InputAddress" type="text" size="70" STYLE="font-size: 10px;" value="Station House, Brinscall, Lancashire, England" /></td>
					<td align="left" class="descriptionbox" width="15%"><font size=1>Census Date:</font></td>
					<td align="left" class="facts_value" width="15%">
						<input name="InputCensusDate" type="text" size="9" STYLE="font-size: 10px;" value="5 April"/></td>
					</tr>
				<tr>
					<td align="left" class="descriptionbox" width="10%"><font size=1>Citation within Source:</font></td>
					<td align="left" class="facts_value" width="80%" colspan="3">
						<font size=1>Class:</font><input name="InputClass" type="text" size="8" STYLE="font-size: 10px;" value="RG12" />
						<font size=1>Piece:</font><input name="InputPiece" type="text" size="8" STYLE="font-size: 10px;" value="3390" />
						<font size=1>Folio:</font><input name="InputFolio" type="text" size="7" STYLE="font-size: 10px;" value="56" />
						<font size=1>Page: </font><input name="InputPage"  type="text" size="6" STYLE="font-size: 10px;" value="20" />
					</td>
				</tr>
			</table>
			
