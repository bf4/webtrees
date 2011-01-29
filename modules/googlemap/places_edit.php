<?php
/**
 * Interface to edit place locations
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2010  PGV Development Team. All rights reserved.
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
 * @package webtrees
 * @subpackage Edit
 * @version $Id$
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require WT_ROOT.'modules/googlemap/defaultconfig.php';
require WT_ROOT.'includes/functions/functions_edit.php';

$action=safe_REQUEST($_REQUEST, 'action');
if (isset($_REQUEST['placeid'])) $placeid = $_REQUEST['placeid'];
if (isset($_REQUEST['place_name'])) $place_name = $_REQUEST['place_name'];

print_simple_header(WT_I18N::translate('Edit geographic place locations'));

if (!WT_USER_IS_ADMIN) {
	echo "<table class=\"facts_table\">\n";
	echo "<tr><td colspan=\"2\" class=\"facts_value\">", WT_I18N::translate('Page only for Administrators');
	echo "</td></tr></table>\n";
	echo "<br /><br /><br />\n";
	print_simple_footer();
	exit;
}
?>
<link type="text/css" href ="modules/googlemap/css/googlemap_style.css" rel="stylesheet" />
<script type="text/javascript">
<!--
function edit_close(newurl) {
	if (newurl) window.opener.location=newurl;
	else if (window.opener.showchanges) window.opener.showchanges();
	window.close();
}
function showchanges() {
	updateMap();
}
//-->
</script>
<?php

// Take a place id and find its place in the hierarchy
// Input: place ID
// Output: ordered array of id=>name values, starting with the Top Level
// e.g. array(0=>"Top Level", 16=>"England", 19=>"London", 217=>"Westminster");
// NB This function exists in both places.php and places_edit.php
function place_id_to_hierarchy($id) {
	$statement=
		WT_DB::prepare("SELECT pl_parent_id, pl_place FROM `##placelocation` WHERE pl_id=?");
	$arr=array();
	while ($id!=0) {
		$row=$statement->execute(array($id))->fetchOneRow();
		$arr=array($id=>$row->pl_place)+$arr;
		$id=$row->pl_parent_id;
	}
	return $arr;
}

// NB This function exists in both admin_places.php and places_edit.php
function getHighestIndex() {
	return (int)WT_DB::prepare("SELECT MAX(pl_id) FROM `##placelocation`")->fetchOne();
}

$where_am_i=place_id_to_hierarchy($placeid);
$level=count($where_am_i);
$link = 'module.php?mod=googlemap&mod_action=admin_places&parent='.$placeid;

if ($action=='addrecord' && WT_USER_IS_ADMIN) {
	$statement=
		WT_DB::prepare("INSERT INTO `##placelocation` (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

	if (($_POST['LONG_CONTROL'] == '') || ($_POST['NEW_PLACE_LONG'] == '') || ($_POST['NEW_PLACE_LATI'] == '')) {
		$statement->execute(array(getHighestIndex()+1, $placeid, $level, stripLRMRLM($_POST['NEW_PLACE_NAME']), null, null, $_POST['NEW_ZOOM_FACTOR'], $_POST['icon']));
	} else {
		$statement->execute(array(getHighestIndex()+1, $placeid, $level, stripLRMRLM($_POST['NEW_PLACE_NAME']), $_POST['LONG_CONTROL'][3].$_POST['NEW_PLACE_LONG'], $_POST['LATI_CONTROL'][3].$_POST['NEW_PLACE_LATI'], $_POST['NEW_ZOOM_FACTOR'], $_POST['icon']));
	}

	// autoclose window when update successful unless debug on
	if (!WT_DEBUG) {
		echo "\n<script type=\"text/javascript\">\n<!--\nedit_close('');\n//-->\n</script>";
	}
	echo "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close('');return false;\">", WT_I18N::translate('Close Window'), "</a></div><br />\n";
	print_simple_footer();
	exit;
}

if ($action=='updaterecord' && WT_USER_IS_ADMIN) {
	$statement=
		WT_DB::prepare("UPDATE `##placelocation` SET pl_place=?, pl_lati=?, pl_long=?, pl_zoom=?, pl_icon=? WHERE pl_id=?");

	if (($_POST['LONG_CONTROL'] == '') || ($_POST['NEW_PLACE_LONG'] == '') || ($_POST['NEW_PLACE_LATI'] == '')) {
		$statement->execute(array(stripLRMRLM($_POST['NEW_PLACE_NAME']), null, null, $_POST['NEW_ZOOM_FACTOR'], $_POST['icon'], $placeid));
	} else {
		$statement->execute(array(stripLRMRLM($_POST['NEW_PLACE_NAME']), $_POST['LATI_CONTROL'][3].$_POST['NEW_PLACE_LATI'], $_POST['LONG_CONTROL'][3].$_POST['NEW_PLACE_LONG'], $_POST['NEW_ZOOM_FACTOR'], $_POST['icon'], $placeid));
	}

	// autoclose window when update successful unless debug on
	if (!WT_DEBUG) {
		echo "\n<script type=\"text/javascript\">\n<!--\nedit_close('');\n//-->\n</script>";
	}
	echo "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close('');return false;\">", WT_I18N::translate('Close Window'), "</a></div><br />\n";
	print_simple_footer();
	exit;
}

// Update placelocation STREETVIEW fields ----------------------------------------------------------
if ($action=='update_sv_params' && WT_USER_IS_ADMIN) {	
	echo "Streetview parameters updated";
	echo "<br /><br />";
	echo "LATI = ".$_REQUEST['svlati']."<br />";
	echo "LONG = ".$_REQUEST['svlong']."<br />";
	echo "BEAR = ".$_REQUEST['svbear']."<br />";
	echo "ELEV = ".$_REQUEST['svelev']."<br />";
	echo "ZOOM = ".$_REQUEST['svzoom']."<br />";
	echo "<br /><br />";	
	$statement=
		WT_DB::prepare("UPDATE ##placelocation SET sv_lati=?, sv_long=?, sv_bearing=?, sv_elevation=?, sv_zoom=? WHERE pl_id=?");		
	$statement->execute(array(stripLRMRLM($_REQUEST['svlati']), $_REQUEST['svlong'], $_REQUEST['svbear'], $_REQUEST['svelev'], $_REQUEST['svzoom'], $placeid));
	if (!WT_DEBUG) {
		echo "\n<script type=\"text/javascript\">\n<!--\nedit_close();\n//-->\n</script>";
	}
	echo "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close();return false;\">", i18n::translate('Close Window'), "</a></div><br />\n";
	print_simple_footer();
	exit;
}

if ($action=="update") {
	// --- find the place in the file
	$row=
		WT_DB::prepare("SELECT pl_place, pl_lati, pl_long, pl_icon, pl_parent_id, pl_level, pl_zoom FROM `##placelocation` WHERE pl_id=?")
		->execute(array($placeid))
		->fetchOneRow();
	$place_name = $row->pl_place;
	$place_icon = $row->pl_icon;
	$selected_country = explode("/", $place_icon);
	if (isset($selected_country[1]) && $selected_country[1]!="flags")
		$selected_country = $selected_country[1];
	else
		$selected_country = "Countries";
	$parent_id = $row->pl_parent_id;
	$level = $row->pl_level;
	$zoomfactor = $row->pl_zoom;
	$parent_lati = "0.0";
	$parent_long = "0.0";
	if ($row->pl_lati!==null && $row->pl_long!==null) {
		$place_lati = (float)(str_replace(array('N', 'S', ','), array('', '-', '.') , $row->pl_lati));
		$place_long = (float)(str_replace(array('E', 'W', ','), array('', '-', '.') , $row->pl_long));
		$show_marker = true;
	} else {
		$place_lati = null;
		$place_long = null;
		$zoomfactor = 1;
		$show_marker = false;
	}

	do {
		$row=
			WT_DB::prepare("SELECT pl_lati, pl_long, pl_parent_id, pl_zoom FROM `##placelocation` WHERE pl_id=?")
			->execute(array($parent_id))
			->fetchOneRow();
		if (!$row) {
			break;
		}
		if ($row->pl_lati!==null && $row->pl_long!==null) {
			$parent_lati = (float)(str_replace(array('N', 'S', ','), array('', '-', '.') , $row->pl_lati));
			$parent_long = (float)(str_replace(array('E', 'W', ','), array('', '-', '.') , $row->pl_long));
			if ($zoomfactor == 1) {
				$zoomfactor = $row->pl_zoom;
			}
		}
		$parent_id = $row->pl_parent_id;
	} 
	while ($row->pl_parent_id!=0 && $row->pl_lati===null && $row->pl_long===null);

	$success = false;

	echo "<b>", str_replace("Unknown", WT_I18N::translate('unknown'), PrintReady(implode(', ', array_reverse($where_am_i, true)))), "</b><br />\n";
}

if ($action=="add") {
	// --- find the parent place in the file
	if ($placeid != 0) {
		if (!isset($place_name)) $place_name  = "";
		$place_lati = null;
		$place_long = null;
		$zoomfactor = 1;
		$parent_lati = "0.0";
		$parent_long = "0.0";
		$place_icon = "";
		$parent_id=$placeid;
		do {
			$row=
				WT_DB::prepare("SELECT pl_lati, pl_long, pl_parent_id, pl_zoom, pl_level FROM `##placelocation` WHERE pl_id=?")
				->execute(array($parent_id))
				->fetchOneRow();
			if ($row->pl_lati!==null && $row->pl_long!==null) {
				$parent_lati=str_replace(array('N', 'S', ','), array('', '-', '.') , $row->pl_lati);
				$parent_long=str_replace(array('E', 'W', ','), array('', '-', '.') , $row->pl_long);
				$zoomfactor=$row->pl_zoom+2;
				if ($zoomfactor>$GOOGLEMAP_MAX_ZOOM) {
					$zoomfactor=$GOOGLEMAP_MAX_ZOOM;
				}
				$level=$row->pl_level+1;
			}
			$parent_id = $row->pl_parent_id;
		} while ($row->pl_parent_id!=0 && $row->pl_lati===null && $row->pl_long===null);
	}
	else {
		if (!isset($place_name)) $place_name  = "";
		$place_lati  = null;
		$place_long  = null;
		$parent_lati = "0.0";
		$parent_long = "0.0";
		$place_icon  = "";
		$parent_id   = 0;
		$level = 0;
		$zoomfactor  = $GOOGLEMAP_MIN_ZOOM;
	}
	$selected_country = "Countries";
	$show_marker = false;
	$success = false;

	if (!isset($place_name) || $place_name=="") echo "<b>", WT_I18N::translate('unknown');
	else echo "<b>", $place_name;
	if (count($where_am_i)>0)
		echo ", ", str_replace("Unknown", WT_I18N::translate('unknown'), PrintReady(implode(', ', array_reverse($where_am_i, true)))), "</b><br />\n";
	echo "</b><br />";
}

// v2 ----------------------------------------------------------------------------------------------
/*
	echo '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=$GOOGLEMAP_API_KEY" type="text/javascript"></script>';
	include_once 'places_edit.js.php';
	$api="v2";
*/
// v2 ----------------------------------------------------------------------------------------------


// v3 ----------------------------------------------------------------------------------------------

	echo '<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>';
 	include_once 'wt_v3_places_edit.js.php';
 	$api="v3";

// v3 ----------------------------------------------------------------------------------------------

?>



<form method="post" id="editplaces" name="editplaces" action="module.php?mod=googlemap&amp;mod_action=places_edit">
	<input type="hidden" name="action" value="<?php echo $action; ?>record" />
	<input type="hidden" name="placeid" value="<?php echo $placeid; ?>" />
	<input type="hidden" name="level" value="<?php echo $level; ?>" />
	<input type="hidden" name="icon" value="<?php echo $place_icon; ?>" />
	<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
	<input type="hidden" name="place_long" value="<?php echo $place_long; ?>" />
	<input type="hidden" name="place_lati" value="<?php echo $place_lati; ?>" />
	<input type="hidden" name="parent_long" value="<?php echo $parent_long; ?>" />
	<input type="hidden" name="parent_lati" value="<?php echo $parent_lati; ?>" />
	<input name="save1" type="submit" value="<?php echo WT_I18N::translate('Save'); ?>" /><br />

	<table class="facts_table">
	<tr>
		<td class="optionbox" colspan="2">
		<center><div id="map_pane" style="width: 100%; height: 300px"></div></center>
		</td>
	</tr>
	<tr>
		<td class="optionbox center" colspan="2">
		<?php
			if ($api=="v3") {
		 		echo WT_I18N::translate('Drag the Marker in the Map above to re-position the place location');
			}
		?>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php echo translate_fact('PLAC'), help_link('PLE_PLACES','googlemap'); ?></td>
		 <td class="optionbox"><input type="text" id="new_pl_name" name="NEW_PLACE_NAME" value="<?php echo htmlspecialchars($place_name); ?>" size="25" class="address_input" />
		<div id="INDI_PLAC_pop" style="display: inline;">
		<?php print_specialchar_link("NEW_PLACE_NAME", false); ?></div>
		<label for="new_pl_name"><a href="javascript:;" onclick="showLocation_level(document.getElementById('new_pl_name').value); return false">&nbsp;<?php echo WT_I18N::translate('Search on this level'); ?></a></label>&nbsp;&nbsp;|
	  <label for="new_pl_name"><a href="javascript:;" onclick="showLocation_all(document.getElementById('new_pl_name').value); return false">&nbsp;<?php echo WT_I18N::translate('Search all'); ?></a></label>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php echo WT_I18N::translate('Precision'), help_link('PLE_PRECISION','googlemap'); ?></td>
		<?php
			$exp = explode(".", $place_lati);
			if (isset($exp[1])) {
				$precision1 = strlen($exp[1]);
			} else {
				$precision1 = -1;
			}
			$exp = explode(".", $place_long);
			if (isset($exp[1])) {
				$precision2 = strlen($exp[1]);
			} else {
				$precision2 = -1;
			}
			($precision1 > $precision2) ? ($precision = $precision1) : ($precision = $precision2);
			if ($precision == -1 ) ($level > 3) ? ($precision = 3) : ($precision = $level);
			elseif ($precision > 5) {
				$precision = 5;
			}
		?>
		<td class="optionbox">
			<input type="radio" id="new_prec_0" name="NEW_PRECISION" onchange="updateMap();" <?php if ($precision==$GOOGLEMAP_PRECISION_0) echo "checked=\"checked\""; ?> value="<?php echo $GOOGLEMAP_PRECISION_0; ?>" />
			<label for="new_prec_0"><?php echo WT_I18N::translate('Country'); ?></label>
			<input type="radio" id="new_prec_1" name="NEW_PRECISION" onchange="updateMap();" <?php if ($precision==$GOOGLEMAP_PRECISION_1) echo "checked=\"checked\""; ?> value="<?php echo $GOOGLEMAP_PRECISION_1; ?>" />
			<label for="new_prec_1"><?php echo WT_I18N::translate('State'); ?></label>
			<input type="radio" id="new_prec_2" name="NEW_PRECISION" onchange="updateMap();" <?php if ($precision==$GOOGLEMAP_PRECISION_2) echo "checked=\"checked\""; ?> value="<?php echo $GOOGLEMAP_PRECISION_2; ?>" />
			<label for="new_prec_2"><?php echo WT_I18N::translate('City'); ?></label>
			<input type="radio" id="new_prec_3" name="NEW_PRECISION" onchange="updateMap();" <?php if ($precision==$GOOGLEMAP_PRECISION_3) echo "checked=\"checked\""; ?> value="<?php echo $GOOGLEMAP_PRECISION_3; ?>" />
			<label for="new_prec_3"><?php echo WT_I18N::translate('Neighborhood'); ?></label>
			<input type="radio" id="new_prec_4" name="NEW_PRECISION" onchange="updateMap();"<?php if ($precision==$GOOGLEMAP_PRECISION_4) echo "checked=\"checked\""; ?> value="<?php echo $GOOGLEMAP_PRECISION_4; ?>" />
			<label for="new_prec_4"><?php echo WT_I18N::translate('House'); ?></label>
			<input type="radio" id="new_prec_5" name="NEW_PRECISION" onchange="updateMap();"<?php if ($precision>$GOOGLEMAP_PRECISION_4) echo "checked=\"checked\""; ?> value="<?php echo $GOOGLEMAP_PRECISION_5; ?>" />
			<label for="new_prec_5"><?php echo WT_I18N::translate('Max'); ?></label>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php echo translate_fact('LATI'), help_link('PLE_LATLON_CTRL','googlemap'); ?></td>
		<td class="optionbox">
			<select name="LATI_CONTROL" onchange="updateMap();">
				<option value="" <?php if ($place_lati == null) echo " selected=\"selected\""; ?>></option>
				<option value="PL_N" <?php if ($place_lati > 0) echo " selected=\"selected\""; echo ">", WT_I18N::translate_c('North', 'N'); ?></option>
				<option value="PL_S" <?php if ($place_lati < 0) echo " selected=\"selected\""; echo ">", WT_I18N::translate_c('South', 'S'); ?></option>
			</select>
			<input type="text" id="NEW_PLACE_LATI" name="NEW_PLACE_LATI" value="<?php if ($place_lati != null) echo abs($place_lati); ?>" size="20" onchange="updateMap();" /></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php echo translate_fact('LONG'), help_link('PLE_LATLON_CTRL','googlemap'); ?></td>
		<td class="optionbox">
			<select name="LONG_CONTROL" onchange="updateMap();">
				<option value="" <?php if ($place_long == null) echo " selected=\"selected\""; ?>></option>
				<option value="PL_E" <?php if ($place_long > 0) echo " selected=\"selected\""; echo ">", WT_I18N::translate_c('East', 'E'); ?></option>
				<option value="PL_W" <?php if ($place_long < 0) echo " selected=\"selected\""; echo ">", WT_I18N::translate_c('West', 'W'); ?></option>
			</select>
			<input type="text" id="NEW_PLACE_LONG" name="NEW_PLACE_LONG" value="<?php if ($place_long != null) echo abs($place_long); ?>" size="20" onchange="updateMap();" /></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php echo WT_I18N::translate('Zoom factor'), help_link('PLE_ZOOM','googlemap'); ?></td>
		<td class="optionbox">
			<input type="text" name="NEW_ZOOM_FACTOR" value="<?php echo $zoomfactor; ?>" size="20" onchange="updateMap();" /></td>
	</tr>
	<tr>
		<td class="descriptionbox"><?php echo WT_I18N::translate('Flag'), help_link('PLE_ICON','googlemap'); ?></td>
		<td class="optionbox">
			<div id="flagsDiv">
<?php
		if (($place_icon == NULL) || ($place_icon == "")) { ?>
				<a href="javascript:;" onclick="change_icon();return false;"><?php echo WT_I18N::translate('Change flag'); ?></a>
<?php   }
		else { ?>
				<img alt="<?php echo /* I18N: The emblem of a country or region */ WT_I18N::translate('Flag'); ?>" src="<?php echo $place_icon; ?>"/>&nbsp;&nbsp;
				<a href="javascript:;" onclick="change_icon();return false;"><?php echo WT_I18N::translate('Change flag'); ?></a>&nbsp;&nbsp;
				<a href="javascript:;" onclick="remove_icon();return false;"><?php echo WT_I18N::translate('Remove flag'); ?></a>
<?php   } ?>
			</div></td>
	</tr>
	</table>
	<input name="save2" type="submit" value="<?php echo WT_I18N::translate('Save'); ?>" /><br />
</form>
<?php
echo "<center><a href=\"javascript:;\" onclick=\"edit_close('')\">", WT_I18N::translate('Close Window'), "</a><br /></center>\n";

print_simple_footer();
