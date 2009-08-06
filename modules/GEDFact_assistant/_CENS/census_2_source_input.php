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
global $pgv_lang, $TEXT_DIRECTION;

?>

<script>
	function findSource(field) {
		pastefield = field;
		findwin = window.open('find.php?type=source', '_blank', 'left=50,top=50,width=600,height=500,resizable=1,scrollbars=1');
		return false;
	}
	function openerpasteid(id) {
		window.opener.paste_id(id);
		window.close();
	}

	function paste_id(value) {
		pastefield.value = value;
	}

	function paste_char(value,lang,mag) {
		pastefield.value += value;
		language_filter = lang;
		magnify = mag;
	}

	function edit_close(newurl) {
		if (newurl)
			window.opener.location=newurl;
		else
			if (window.opener.showchanges)
				window.opener.showchanges();
		window.close();
	}
	
	function changeYear(cenyear) {
		changeAge(cenyear);
		changeCols(cenyear);
	}
	
	
	
	function changeAge(cenyear) {
		var curryr = document.getElementById('curryear');
		if (curryr.value !="") {
			var currcenyear=curryr.value;
		}
		var base1901 ="<?php echo $censyear; ?>";
		if (currcenyear) {
			var agecorr = (currcenyear-cenyear);
		}else{
			var agecorr = (base1901-cenyear);
		}
		var tbl = document.getElementById('tblSample');
		for(var i=1; i<tbl.rows.length; i++){ // start at i=1 because we need to avoid header
			var tr = tbl.rows[i];
			for(var j=2; j<tr.cells.length; j++){
				if (j!=6) {
					//	miss out all cols except age
					continue;
				}else{
					var ageat1901 = (tr.cells[j].childNodes[0].value);
					var newage=(ageat1901-agecorr);
					// alert (newage);
					tr.cells[j].childNodes[0].value=newage;
				}
			}
		}
		var censtitl_a = document.getElementById('censCtry');
		if (censtitl_a.value =="USA") {
			document.getElementById('Titl').value = "<?php echo "Federal Census Transcription - ".$wholename." - ";?>";
		}else{
			document.getElementById('Titl').value = "<?php echo "Census Transcription - ".$wholename." - ";?>";
		}
		
		var curr = document.getElementById('curryear');
		curr.value = cenyear;
	}
	
	function changeCols(cenyear) {
		// Add or Remove columns
		var flip = "";
		if (cenyear=="1881") {
			flip = "none";
		}else{
			flip = "block";
		}
			var cells = document.getElementsByName('col_3');
			for (var i=0; i<cells.length; i++) {
				cells[i].style.display = flip;
			}
			// cells[cells.length-1].style.display = flip;
			
	}
	
</script>

<div class="optionbox" style="font-weight:bold; font-size:0.9em; text-align:left; padding:0.3em; border:0.3em outset; margin-bottom:0.3em;">
	<div style="width:16em; float:left;">Census:&nbsp;
		<script type="text/javascript">
			var censyear = new DynamicOptionList();
			censyear.addDependentFields("ctry","censyear");
			censyear.forValue("UK").addOptions( "choose", "1841", "1851", "1861", "1871", "1881", "1891", "1901", "1911", "1921" );
			censyear.forValue("USA").addOptions( "choose", "1790", "1800", "1810", "1820", "1830", "1840", "1850", "1860", "1870", "1880", "1890", "1900", "1910", "1920", "1930");
			censyear.forValue("UK").setDefaultOptions("choose");
			censyear.forValue("USA").setDefaultOptions("choose");
		</script>
		<select id="censCtry" name="ctry" style="font:0.9em normal;">
			<option value="UK" >UK</option>
			<option value="USA">USA</option>
		</select>
		<select onchange="if( this.options[this.selectedIndex].value!='') {
			changeYear(this.options[this.selectedIndex].value);
			}" 
			id="censYear" name="censyear" style="font:0.9em normal;">
		</select>
		<input type="hidden" id="curryear" name="curryear" value="" />
	</div>
	<div style="font-weight:bold;">&nbsp;&nbsp;&nbsp;Title:&nbsp;
		<script type="text/javascript">
			 document.writeln('<input id="Titl" name="Titl" type="text" style="width:34.6em; font:0.9em normal;" value="<?php echo "Census Transcription - ".$wholename." - ";?>" />');
		</script>
	</div>
</div>
			
