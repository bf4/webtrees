<?php
/**
 * Family Tree Clippings Cart
 *
 * Uses the $_SESSION["cart"] to store the ids of clippings to download
 * @TODO print a message if people are not included due to privacy
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
 * @subpackage Charts
 * @version $Id$
 */

require './config.php';

require_once 'includes/controllers/clippings_ctrl.php';
$controller = new ClippingsController();
$controller->init();

// -- print html header information
print_header($pgv_lang["clip_cart"]);
require 'js/autocomplete.js.htm';
require 'js/sorttable.js.htm';
?>

<h2><?php $pgv_lang["clippings_cart"] ?></h2>
<?php

if ($controller->action=='add') {
	if ($controller->type=='fam') {?>
		<form action="clippings.php" method="get">
		<table>
			<tr><td class="topbottombar"><?php print $pgv_lang["which_links"]?>
			<input type="hidden" name="id" value="<?php print $controller->id; ?>" />
			<input type="hidden" name="type" value="<?php print $controller->type ?>" />
			<input type="hidden" name="action" value="add1" /></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" checked value="none" /><?php print $pgv_lang["just_family"]?></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="parents" /><?php print $pgv_lang["parents_and_family"]?></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="members" /><?php print $pgv_lang["parents_and_child"]?></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="descendants" /><?php print $pgv_lang["parents_desc"]?></td></tr>
			<tr><td class="topbottombar"><input type="submit" value="<?php print $pgv_lang["continue"]?>" /></td></tr>

		</table>
		</form>
	<?php }
	else if ($controller->type=='indi') {?>
		<form action="clippings.php" method="get">
		<table>
			<tr><td class="topbottombar"><?php print $pgv_lang["which_p_links"]?>
			<input type="hidden" name="id" value="<?php print $controller->id; ?>" />
			<input type="hidden" name="type" value="<?php print $controller->type ?>" />
			<input type="hidden" name="action" value="add1" /></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" checked value="none" /><?php print $pgv_lang["just_person"]?></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="parents" /><?php print $pgv_lang["person_parents_sibs"]?></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="ancestors" /><?php print $pgv_lang["person_ancestors"]?></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="ancestorsfamilies" /><?php print $pgv_lang["person_ancestor_fams"]?></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="members" /><?php print $pgv_lang["person_spouse"]?></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="descendants" /><?php print $pgv_lang["person_desc"]?></td></tr>
			<tr><td class="topbottombar"><input type="submit" value="<?php print $pgv_lang["continue"]?>" />
		</table>
		</form>
	<?php } else if ($controller->type=='sour')  {?>
		<form action="clippings.php" method="get">
		<table>
			<tr><td class="topbottombar"><?php print $pgv_lang["which_s_links"]?>
			<input type="hidden" name="id" value="<?php print $controller->id; ?>" />
			<input type="hidden" name="type" value="<?php print $controller->type ?>" />
			<input type="hidden" name="action" value="add1" /></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" checked value="none" /><?php print $pgv_lang["just_source"]?></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="linked" /><?php print $pgv_lang["linked_source"]?></td></tr>
			<tr><td class="topbottombar"><input type="submit" value="<?php print $pgv_lang["continue"]?>" />
		</table>
		</form>
	<?php }
	}
$ct = count($cart);

if ($controller->privCount>0) {
	print "<span class=\"error\">".$pgv_lang["clipping_privacy"]."</span><br /><br />\n";
}

if($ct==0) {

	// -- new lines, added by Jans, to display helptext when cart is empty
	if ($controller->action!='add') {

		loadLangFile("pgv_help");
		print_text("help_clippings.php");

		?>
		<script language="JavaScript" type="text/javascript">
		<!--
		var pastefield;
		function paste_id(value)
		{
			pastefield.value=value;
		}
		//-->
		</script>
		<form method="get" name="addin" action="clippings.php">
		<table>
		<tr>
			<td colspan="2" class="topbottombar" style="text-align:center; ">
				<?php print $pgv_lang["add_individual_by_id"];
				print_help_link("add_by_id_help", "qm");?>
			</td>
		</tr>
		<tr>
			<td class="optionbox">
				<input type="hidden" name="action" value="add"/>
				<input type="text" name="id" id="cart_item_id" size="5"/>
			</td>
			<td class="optionbox">
				<?php print_findindi_link('pid',''); ?>
				<?php print_findfamily_link('pid',''); ?>
				<?php print_findsource_link('pid',''); ?>
				<input type="submit" value="<?php print $pgv_lang["add"];?>"/>

			</td>
		</tr>
		</table>
		</form>
		<?php
	}

	// -- end new lines
	print "\r\n\t\t<br /><br />".$pgv_lang["cart_is_empty"]."<br /><br />";
}
else {
	if ($controller->action != 'download' && $controller->action != 'add') { ?>
		<form method="get" action="clippings.php">
		<input type="hidden" name="action" value="download" />
		<table><tr><td valign="top">
		<table>
		<tr><td colspan="2" class="topbottombar"><h2><?php print $pgv_lang["file_information"] ?></h2></td></tr>
		<tr>
		<td class="descriptionbox wrap"><?php print $pgv_lang["choose_file_type"] ?></td>
		<td class="optionbox">
		<?php print getLRM();?><input type="radio" name="filetype" checked="checked"  value="gedcom" /> GEDCOM <?php print_help_link("def_gedcom_help", "qm"); ?><?php print getLRM();?>
		<br />
		<?php print getLRM();?><input type="radio" name="filetype" value="gramps" /> Gramps XML <?php print_help_link("def_gramps_help", "qm"); ?><?php print getLRM();?>
		</td></tr>

		<tr><td class="descriptionbox wrap"><?php print $pgv_lang["zip_files"]; ?> </td>
		<td class="optionbox"><input type="checkbox" name="Zip" value="yes" checked="checked" /><?php print_help_link("zip_help", "qm"); ?></td></tr>
		<tr><td class="descriptionbox wrap"><?php print $pgv_lang["include_media"]; ?></td>
		<td class="optionbox"> <input type="checkbox" name="IncludeMedia" value="yes" checked="checked" /><?php print_help_link("include_media_help", "qm"); ?></td></tr>

		<tr><td class="optionbox" colspan="2">
		<br />
		<a href="javascript:;" onclick="return expand_layer('advanced');"><img id="advanced_img" src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]; ?>" border="0" width="11" height="11" alt="" title="" /> <?php print $pgv_lang["advanced_options"]; ?></a>
		<div id="advanced" style="display: none;">
		<table>
		<tr><td><input type="checkbox" name="convert" value="yes" /></td><td><?php print $pgv_lang["utf8_to_ansi"]; print_help_link("utf8_ansi_help", "qm"); ?></td></tr>
		<tr><td><input type="checkbox" name="remove" value="yes" checked="checked" /></td><td><?php print $pgv_lang["remove_custom_tags"]; print_help_link("remove_tags_help", "qm"); ?></td></tr>
		</table>
		</div>
		<br />
		<tr><td class="topbottombar" colspan="2">
		<input type="submit" value="<?php print $pgv_lang["download_now"]; ?>" />
		<?php print_help_link("clip_download_help", "qm"); ?>
		</td></tr>
		</form>

		</td></tr>
		</table>
		<br />

		<script language="JavaScript" type="text/javascript">
		<!--
		var pastefield;
		function paste_id(value)
		{
			pastefield.value=value;
		}
		//-->
		</script>
		<form method="get" name="addin" action="clippings.php">
		<table>
		<tr>
			<td colspan="2" class="topbottombar" style="text-align:center; ">
				<?php print $pgv_lang["add_individual_by_id"];
				print_help_link("add_by_id_help", "qm");?>
			</td>
		</tr>
		<tr>
			<td class="optionbox">
				<input type="hidden" name="action" value="add"/>
				<input type="text" name="id" id="cart_item_id" size="8" />
			</td>
			<td class="optionbox">
				<?php //print_findindi_link('pid',''); ?>
				<?php //print_findfamily_link('pid',''); ?>
				<?php //print_findsource_link('pid',''); ?>
				<input type="submit" value="<?php print $pgv_lang["add"];?>"/>

			</td>
		</tr>
		</table>
		</form>


	<?php } ?>
	<br /><a href="clippings.php?action=empty"><?php print $pgv_lang["empty_cart"]."  "; ?></a>
	<?php print_help_link("empty_cart_help", "qm"); ?>
	</td><td valign="top">
	<table id="mycart" class="sortable list_table">
		<tr>
			<th class="list_label"><?php echo $pgv_lang["type"]?></th>
			<th class="list_label"><?php echo $pgv_lang["id"]?></th>
			<th class="list_label"><?php echo $pgv_lang["name_description"]?></th>
			<th class="list_label"><?php echo $pgv_lang["remove"]?></th>
			<th class="list_label"><?php print_help_link("clip_cart_help", "qm"); ?></th>
		</tr>
<?php
	for ($i=0; $i<$ct; $i++) {
		$clipping = $cart[$i];
		$tag = strtoupper(substr($clipping['type'],0,4)); // source => SOUR
		//print_r($clipping);
		//-- don't show clippings from other gedcoms
		if ($clipping['gedcom']==$GEDCOM) {
			if ($tag=='INDI') $icon = "indis";
			if ($tag=='FAM' ) $icon = "sfamily";
			if ($tag=='SOUR') $icon = "source";
			if ($tag=='REPO') $icon = "repository";
			if ($tag=='NOTE') $icon = "notes";
			if ($tag=='OBJE') $icon = "media";
			?>
			<tr><td class="list_value">
				<?php if (!empty($icon)) { ?><img src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES[$icon]["small"];?>" border="0" alt="<?php echo $tag;?>" title="<?php echo $tag;?>" /><?php } ?>
			</td>
			<td class="list_value ltr"><?php echo $clipping['id']?></td>
			<td class="list_value">
			<?php
			$record=GedcomRecord::getInstance($clipping['id']);
			echo '<a href="'.encode_url($record->getLinkUrl()).'">'.PrintReady($record->getListName()).'</a>';
			?>
			</td>
			<td class="list_value center vmiddle"><a href="clippings.php?action=remove&amp;item=<?php echo $i;?>"><img src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["remove"]["other"];?>" border="0" alt="<?php echo $pgv_lang["remove"]?>" title="<?php echo $pgv_lang["remove"];?>" /></a></td>
		</tr>
		<?php
		}
	}
?>
	</table>
	</td></tr></table>
<?php
}
if (isset($_SESSION["cart"])) $_SESSION["cart"]=$cart;
print_footer();
?>
