<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census information about an individual
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
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

	<script>
	function addDate(theCensDate) {
		var ddate = theCensDate.split(', ');
		document.getElementById('setctry').value = ddate[3];
		document.getElementById('setyear').value = ddate[0];
		// alert(document.getElementById('setctry').value+" "+document.getElementById('setyear').value);
		cal_setDateField('<?php echo $element_id;?>', parseInt(ddate[0]), parseInt(ddate[1]), parseInt(ddate[2])); return false;
	}
	</script>

	<select onchange =	"if (this.options[this.selectedIndex].value!='') {
							addDate(this.options[this.selectedIndex].value);
						}">
		<option value='' SELECTED>Census Date</option>
		<option value=""></option>
		<option class="UK"  value='1911, 3, 02, UK'>UK 1911</option>
		<option class="UK"  value="1901, 2, 31, UK">UK 1901</option>
		<option class="UK"  value="1891, 3, 05, UK">UK 1891</option>
		<option class="UK"  value="1881, 3, 03, UK">UK 1881</option>
		<option class="UK"  value="1871, 3, 02, UK">UK 1871</option>
		<option class="UK"  value="1861, 3, 07, UK">UK 1861</option>
		<option class="UK"  value="1851, 2, 30, UK">UK 1851</option>
		<option class="UK"  value="1841, 5, 06, UK">UK 1841</option>
		<option value=""></option>
		<option class="USA" value="1930, 3, 01, USA">US 1930</option>
		<option class="USA" value="1920, 0, 01, USA">US 1920</option>
		<option class="USA" value="1910, 3, 15, USA">US 1910</option>
		<option class="USA" value="1900, 5, 01, USA">US 1900</option>
		<option class="USA" value="1890, 5, 01, USA">US 1890</option>
		<option class="USA" value="1880, 5, 01, USA">US 1880</option>
		<option class="USA" value="1870, 5, 01, USA">US 1870</option>
		<option class="USA" value="1860, 5, 01, USA">US 1860</option>
		<option class="USA" value="1850, 5, 01, USA">US 1850</option>
		<option class="USA" value="1840, 5, 01, USA">US 1840</option>
		<option class="USA" value="1830, 5, 01, USA">US 1830</option>
		<option class="USA" value="1820, 7, 07, USA">US 1820</option>
		<option class="USA" value="1810, 7, 06, USA">US 1810</option>
		<option class="USA" value="1800, 7, 04, USA">US 1800</option>
		<option class="USA" value="1790, 7, 02, USA">US 1790</option>
		<option value=""></option>
	</select>

	<input type="hidden" id="setctry" name="setctry" value="" />
	<input type="hidden" id="setyear" name="setyear" value="" />




