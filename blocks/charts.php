<?php
/**
 * Charts Block
 *
 * This block prints pedigree, descendency, or hourglass charts for the chosen person
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PGV Development Team
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
 * @version $Id$
 * -- Slightly modified (rtl in table values) 2006/06/09 18:00:00 pfblair
 * @package PhpGedView
 * @subpackage Blocks
 */

$PGV_BLOCKS["print_charts_block"]["name"]		= $pgv_lang["charts_block"];
$PGV_BLOCKS["print_charts_block"]["descr"]		= "charts_block_descr";
$PGV_BLOCKS["print_charts_block"]["canconfig"]	= true;
$PGV_BLOCKS["print_charts_block"]["config"]		= array(
	"cache"=>1,
	"rootId"=>'',
	"type"=>'pedigree'
	);
	
function print_charts_block($block = true, $config="", $side, $index) {
	global $PGV_BLOCKS, $pgv_lang, $GEDCOM, $GEDCOMS, $ctype, $PGV_IMAGE_DIR, $PGV_IMAGES, $PEDIGREE_ROOT_ID;
	global $show_full;
	
	$show_full=0;
	if (empty($config)) $config = $PGV_BLOCKS["print_charts_block"]["config"];
	if (empty($config["rootId"])) {
		$username = getUserName();
		if (empty($username)) $config["rootId"] = $PEDIGREE_ROOT_ID;
		else {
			$user = getUser($username);
			if (!empty($user["gedcom_id"][$GEDCOM])) $config["rootId"] = $user["gedcom_id"][$GEDCOM];
			else $config["rootId"] = $PEDIGREE_ROOT_ID;
		}
	}
	
	if ($config['type']!='treenav') {
		include_once("includes/controllers/hourglass_ctrl.php");
		/* @var $controller HourglassController */
		$controller->init($config["rootId"],0,2);
		$controller->setupJavascript();
	}
	else {
		require_once('includes/treenav_class.php');
		$nav = new TreeNav($config['rootId'],'blocknav',-1);
		$nav->generations = 2;
	}
	
	$person = Person::getInstance($config["rootId"]);
	if ($person==null) $person = Person::getInstance($PEDIGREE_ROOT_ID);
	
	print "<div id=\"charts_block\" class=\"block\">\n";
	print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
	print "<td class=\"blockh1\" >&nbsp;</td>";
	print "<td class=\"blockh2\" ><div class=\"blockhc\">";
	print_help_link("index_charts_help", "qm");
	if ($PGV_BLOCKS["print_charts_block"]["canconfig"]) {
		$username = getUserName();
		if ((($ctype=="gedcom")&&(userGedcomAdmin($username))) || (($ctype=="user")&&(!empty($username)))) {
			if ($ctype=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
			else $name = $username;
			print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;ctype=$ctype&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=700,height=400,scrollbars=1,resizable=1'); return false;\">";
			print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
		}
	}
	$title = "";
	$name = $person->getName();
	switch($config['type']) {
		case 'pedigree':
			$title = $name." ".$pgv_lang["index_header"];
			break;
		case 'descendants':
			$title = $name." ".$pgv_lang["descend_chart"];
			break;
		case 'hourglass':
			$title = $name." ".$pgv_lang["hourglass_chart"];
			break;
		case 'treenav':
			$title = $name." ".$pgv_lang["tree"];
			break;
	}
	print "<b>".$title."</b>";
	print "</div></td>";
	print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
	print "</table>";
	print "<div class=\"blockcontent\">";
	print "<div class=\"small_inner_block\">";
	?>
	<table cellspacing="0" cellpadding="0" border="0"><tr>
	<?php
	if ($config['type']=='descendants' || $config['type']=='hourglass') {
		print "<td valign=\"middle\">";
		$controller->print_descendency($config['rootId'], 0);
		print "</td>";
	}
	if ($config['type']=='pedigree' || $config['type']=='hourglass') {
		//-- print out the root person
		if ($config['type']!='hourglass') {
			print "<td valign=\"middle\">";
			print_pedigree_person($config['rootId']);
			print "</td>";
		}
		print "<td valign=\"middle\">";
		$controller->print_person_pedigree($config['rootId'], 0);
		print "</td>";
	}
	if ($config['type']=='treenav') {
		print "<td>";
		$nav->drawViewport('blocknav', "", "240px");
		print "</td>";
	}
	print "</tr></table>\n";
	print "</div>\n";
	print "</div>\n";
	print "</div>";
}

function print_charts_block_config($config) {
	global $pgv_lang, $ctype, $PGV_BLOCKS, $TEXT_DIRECTION, $PEDIGREE_ROOT_ID;
	if (empty($config)) $config = $PGV_BLOCKS["print_charts_block"]["config"];
	if (empty($config["rootId"])) $config["rootId"] = $PEDIGREE_ROOT_ID;
?>
	<tr><td class="descriptionbox wrap width33"><?php print $pgv_lang["chart_type"]; ?></td>
	<td class="optionbox">
		<select name="type">
			<option value="pedigree"<?php if ($config["type"]=="pedigree") print " selected=\"selected\"";?>><?php print $pgv_lang["index_header"]; ?></option>
			<option value="descendants"<?php if ($config["type"]=="descendants") print " selected=\"selected\"";?>><?php print $pgv_lang["descend_chart"]; ?></option>
			<option value="hourglass"<?php if ($config["type"]=="hourglass") print " selected=\"selected\"";?>><?php print $pgv_lang["hourglass_chart"]; ?></option>
			<?php if (file_exists("includes/treenav_class.php")) { ?>
			<option value="treenav"<?php if ($config["type"]=="treenav") print " selected=\"selected\"";?>>TreeNav</option>
			<?php } ?>
		</select>
	</td></tr>
	<tr>
		<td class="descriptionbox wrap width33"><?php print $pgv_lang["root_person"]; ?></td>
		<td class="optionbox">
			<input type="text" name="rootId" id="rootId" value="<?php print $config["rootId"]?>" size="5" />
			<?php print_findindi_link("rootId","");
			if (!empty($config["rootId"])) {
				print "\n<span class=\"list_item\">".get_person_name($config["rootId"]);
				print_first_major_fact($config["rootId"]);
				print "</span>\n";
			}
			?>
		</td>
	</tr>
  	<?php

	// Cache file life
	if ($ctype=="gedcom") {
  		print "<tr><td class=\"descriptionbox wrap width33\">";
			print_help_link("cache_life_help", "qm");
			print $pgv_lang["cache_life"];
		print "</td><td class=\"optionbox\">";
			print "<input type=\"text\" name=\"cache\" size=\"2\" value=\"".$config["cache"]."\" />";
		print "</td></tr>";
	}
}
?>