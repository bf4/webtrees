<?php

/**
 * Searches based on user query.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008	PGV Development Team. All rights reserved.
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
 * @subpackage Display
 * @version $Id$
 */

require './config.php';
require './includes/controllers/advancedsearch_ctrl.php';
require './includes/functions/functions_print_lists.php';

$controller=new AdvancedSearchController();
$controller->init();

// Print the top header
print_header($pgv_lang["advanced_search"]);
?>
<script language="JavaScript" type="text/javascript">
<!--
	function checknames(frm) {
		action = "<?php print $controller->action ?>";

		return true;
	}

	var numfields = <?php print count($controller->fields); ?>;
	/**
	 * add a row to the table of fields
	 */
	function addFields() {
		//-- get the table
		var tbl = document.getElementById('field_table').tBodies[0];
		//-- create the new row
		var trow = document.createElement('tr');
		//-- create the new label cell
		var label = document.createElement('td');
		label.className='list_label';
		//-- create a select for the user to choose the field
		var sel = document.createElement('select');
		sel.name = 'fields['+numfields+']';
		sel.rownum = numfields;
		sel.onchange = function() {
			showDate(this, this.rownum);
		};

		//-- all of the field options
		<?php foreach($controller->getOtherFields() as $field) { ?>
		opt = document.createElement('option');
		opt.value='<?php print $field; ?>';
		opt.text='<?php print $controller->getLabel($field); ?>';
		sel.appendChild(opt);
		<?php } ?>
		label.appendChild(sel);
		trow.appendChild(label);
		//-- create the new value cell
		var val = document.createElement('td');
		val.id = 'vcell'+numfields;
		val.className='list_value';

		var inp = document.createElement('input');
		inp.name='values['+numfields+']';
		inp.type='text';
		inp.id='value'+numfields;
		inp.tabindex=numfields+1;
		val.appendChild(inp);
		trow.appendChild(val);
		var lastRow = tbl.lastChild.previousSibling;

		tbl.insertBefore(trow, lastRow.nextSibling);
		numfields++;
	}

	/**
	 * add the date options selection
	 */
	function showDate(sel, row) {
		var type = sel.options[sel.selectedIndex].value;
		var pm = document.getElementById('plusminus'+row);
		if (!type.match("DATE$")) {
			//-- if it is not a date do not show the date
			if (pm) pm.parentNode.removeChild(pm);
			return;
		}
		//-- if it is a date and the plusminus is already show, then leave
		if (pm) return;
		var elm = document.getElementById('vcell'+row);
		var sel = document.createElement('select');
		sel.id = 'plusminus'+row;
		sel.name = 'plusminus['+row+']';
		var opt = document.createElement('option');
		opt.value='';
		opt.text='<?php print $pgv_lang['exact']; ?>';
		sel.appendChild(opt);
		opt = document.createElement('option');
		opt.value='';
		opt.text='+/- 2 <?php print $pgv_lang['years2']; ?>';
		sel.appendChild(opt);
		opt = document.createElement('option');
		opt.value='5';
		opt.text='+/- 5 <?php print $pgv_lang['years']; ?>';
		sel.appendChild(opt);
		opt = document.createElement('option');
		opt.value='10';
		opt.text='+/- 10 <?php print $pgv_lang['years']; ?>';
		sel.appendChild(opt);
		var spc = document.createTextNode(' ');
		elm.appendChild(spc);
		elm.appendChild(sel);
	}
//-->
</script>

<h2 class="center"><?php print $controller->getPageTitle(); ?></h2>
<?php $somethingPrinted = $controller->PrintResults(); ?>
<!--	/*************************************************** Search Form Outer Table **************************************************/ -->
<form method="post" name="searchform" onsubmit="return checknames(this);" action="search_advanced.php">
<input type="hidden" name="action" value="<?php print $controller->action; ?>" />
<input type="hidden" name="isPostBack" value="true" />
<table id="field_table" class="list_table $TEXT_DIRECTION" width="35%" border="0">
	<tr>
		<td colspan="4" class="facts_label03" style="text-align:center; ">
			<?php print $pgv_lang["advanced_search"]; print_help_link("advanced_search_help", "qm"); ?>
		</td>
	</tr>
	<!-- // search terms -->
	<?php
	$fct = count($controller->fields);
	for($i=0; $i<$fct; $i++) {
		if (strpos($controller->getField($i), "FAMC:HUSB:NAME")===0) continue;
		if (strpos($controller->getField($i), "FAMC:WIFE:NAME")===0) continue;
	?>
	<tr>
		<td class="list_label">
			<?php print $controller->getLabel($controller->getField($i)); ?>
		</td>
		<td id="vcell<?php print $i; ?>" class="list_value">
			<input type="hidden" name="fields[<?php print $i ?>]" value="<?php print $controller->getField($i); ?>" />
			<input tabindex="<?php print $i+1; ?>" type="text" id="value<?php print $i; ?>" name="values[<?php print $i; ?>]" value="<?php print $controller->getValue($i); ?>" />
			<?php if (preg_match("/:DATE$/", $controller->getField($i))>0) {
				?>
				<select name="plusminus[<?php print $i ?>]">
					<option value=""><?php print $pgv_lang["exact"]; ?></option>
					<option value="2" <?php if (!empty($controller->plusminus[$i]) && $controller->plusminus[$i]==2) print "selected=\"selected\""; ?>>+/- 2 <?php print $pgv_lang['years2']; ?></option>
					<option value="5" <?php if (!empty($controller->plusminus[$i]) && $controller->plusminus[$i]==5) print "selected=\"selected\""; ?>>+/- 5 <?php print $pgv_lang['years']; ?></option>
					<option value="10" <?php if (!empty($controller->plusminus[$i]) && $controller->plusminus[$i]==10) print "selected=\"selected\""; ?>>+/- 10 <?php print $pgv_lang['years']; ?></option>
				</select>
			<?php }?>
		</td>
		<?php
		//-- relative fields
		if ($i==0 && $fct>4) {
			$j=$fct;
			?>
			<td rowspan="100" class="list_value">
				<table>
					<!--  father -->
					<tr>
						<td>
							<input type="hidden" name="fields[<?php print $j; ?>]" value="FAMC:HUSB:NAME:GIVN:SDX" />
							<?php print $pgv_lang['father'] ?><br /><input type="text" name="values[<?php print $j; ?>]" value="<?php print $controller->getValue($controller->getIndex("FAMC:HUSB:NAME:GIVN:SDX")); ?>" />
						</td>
						<?php $j++; ?>
						<td>
							<input type="hidden" name="fields[<?php print $j; ?>]" value="FAMC:HUSB:NAME:SURN:SDX" />
							<?php print $factarray['SURN'] ?><br /><input type="text" name="values[<?php print $j; ?>]" value="<?php print $controller->getValue($controller->getIndex("FAMC:HUSB:NAME:SURN:SDX")); ?>" />
						</td>
						<?php $j++; ?>
					</tr>
					<!--  mother -->
					<tr>
						<td>
							<input type="hidden" name="fields[<?php print $j; ?>]" value="FAMC:WIFE:NAME:GIVN:SDX" />
							<?php print $pgv_lang['mother'] ?><br /><input type="text" name="values[<?php print $j; ?>]" value="<?php print $controller->getValue($controller->getIndex("FAMC:WIFE:NAME:GIVN:SDX")); ?>" />
						</td>
						<?php $j++; ?>
						<td>
							<input type="hidden" name="fields[<?php print $j; ?>]" value="FAMC:WIFE:NAME:SURN:SDX" />
							<?php print $factarray['SURN'] ?><br /><input type="text" name="values[<?php print $j; ?>]" value="<?php print $controller->getValue($controller->getIndex("FAMC:WIFE:NAME:SURN:SDX")); ?>" />
						</td>
						<?php $j++; ?>
					</tr>
					<!-- spouse -->
					<tr>
					<?php $j++; ?>
					</tr>
				</table>
			</td>
		<?php } ?>
	</tr>
	<?php } ?>
	<tr>
		<td class="list_value" style="vertical-align: middle; text-align: center; padding: 5px;"  colspan="3">
			<a href="#" onclick="addFields();"><?php print $pgv_lang['more_fields']; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input tabindex="<?php print $i+1; ?>" type="submit" value="<?php print $pgv_lang["search"]; ?>" />
		</td>
	</tr>
</table>
</form>
<br /><br /><br /><br />
<?php
// set the focus on the first field unless multisite or some search results have been printed
if (!$somethingPrinted ) {
?>
	<script language="JavaScript" type="text/javascript">
	<!--
		document.getElementById('value0').focus();
	//-->
	</script>
<?php
}
//-- somewhere the session gedcom gets changed, so we will change it back
$_SESSION['GEDCOM'] = $GEDCOM;
print_footer();
?>
