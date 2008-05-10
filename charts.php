<?php
/**
 * Charts Block
 *
 * This block prints pedigree, descendency, or hourglass charts for the chosen person
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008 PGV Development Team, all rights reserved.
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
	"type"=>'pedigree',
	"details"=>'no'
	);
	
function print_charts_block($block = true, $config="", $side, $index) {
	global $PGV_BLOCKS, $pgv_lang, $GEDCOM, $ctype, $PGV_IMAGE_DIR, $PGV_IMAGES, $PEDIGREE_ROOT_ID, $PEDIGREE_FULL_DETAILS;
	global $show_full, $bwidth, $bheight;
	
	if (empty($config)) $config = $PGV_BLOCKS["print_charts_block"]["config"];
	if (empty($config['details'])) $config['details'] = 'no';
	if (empty($config["rootId"])) {
		if (!PGV_USER_ID) {
			$config["rootId"] = $PEDIGREE_ROOT_ID;
		} else {
			if (PGV_USER_GEDCOM_ID) {
				$config["rootId"] = PGV_USER_GEDCOM_ID;
			} else {
				$config["rootId"] = $PEDIGREE_ROOT_ID;
			}
		}
	}
	
	if ($config["details"]=="no") {
		$show_full = 0;
		// Here we could adjust the block width & height to accommodate larger displays 
	} else {
		$show_full = 1;
		// Here we could adjust the block width & height to accommodate larger displays 
	}

	$PEDIGREE_FULL_DETAILS = $show_full;		// Override GEDCOM configuration (but only for the Charts block)
	
	if ($config['type']!='treenav') {
		include_once("includes/controllers/hourglass_ctrl.php");
		/* @var $controller HourglassController */
		$controller->init($config["rootId"],0,3);
		$controller->setupJavascript();
	}
	else {
		require_once('includes/treenav_class.php');
		$nav = new TreeNav($config['rootId'],'blocknav',-1);
		$nav->generations = 2;
	}
	
	$person = Person::getInstance($config["rootId"]);
	if ($person==null) $person = Person::getInstance($PEDIGREE_ROOT_ID);
	
	$id = "charts_block";
	$title = print_help_link("index_charts_help", "qm", "", false, true);
	if ($PGV_BLOCKS["print_charts_block"]["canconfig"]) {
		if ($ctype=="gedcom" && PGV_USER_GEDCOM_ADMIN || $ctype=="user" && PGV_USER_ID) {
			if ($ctype=="gedcom") {
				$name = preg_replace("/'/", "\'", $GEDCOM);
			} else {
				$name = PGV_USER_NAME;
			}
			$title .= "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;ctype=$ctype&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=700,height=400,scrollbars=1,resizable=1'); return false;\">";
			$title .= "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>";
		}
	}
	if ($person) {
		$name=$person->getName();
	}
	switch($config['type']) {
		case 'pedigree':
			$title .= $name." ".$pgv_lang["index_header"];
			break;
		case 'descendants':
			$title .= $name." ".$pgv_lang["descend_chart"];
			break;
		case 'hourglass':
			$title .= $name." ".$pgv_lang["hourglass_chart"];
			break;
		case 'treenav':
			$title .= $name." ".$pgv_lang["tree"];
			break;
	}
	$content = "";
	$content .= "<script src=\"phpgedview.js\" language=\"JavaScript\" type=\"text/javascript\"></script>";
	if ($show_full==0) {
		$content .= '<span class="details2"><center>'.$pgv_lang['charts_click_box'].'</center></span><br />';
	}
	$content .= '<table cellspacing="0" cellpadding="0" border="0"><tr>';
	if ($config['type']=='descendants' || $config['type']=='hourglass') {
		$content .= "<td valign=\"middle\">";
		ob_start();
		$controller->print_descendency($config['rootId'], 1, false);
		$content .= ob_get_clean();
		$content .= "</td>";
	}
	if ($config['type']=='pedigree' || $config['type']=='hourglass') {
		//-- print out the root person
		if ($config['type']!='hourglass') {
			$content .= "<td valign=\"middle\">";
			ob_start();
			print_pedigree_person($config['rootId']);
			$content .= ob_get_clean();
			$content .= "</td>";
		}
		$content .= "<td valign=\"middle\">";
		ob_start();
		$controller->print_person_pedigree($config['rootId'], 1);
		$content .= ob_get_clean();
		$content .= "</td>";
	}
	if ($config['type']=='treenav') {
		$content .= "<td>";
		ob_start();
		$nav->drawViewport('blocknav', "", "240px");
		$content .= ob_get_clean();
		$content .= "</td>";
	}
	$content .= "</tr></table>";
	$content .= '<script type="text/javascript">
		<!--
		if (sizeLines) sizeLines();
		//-->
		</script>';
	
	print '<div id="'.$id.'" class="block"><table class="blockheader" cellspacing="0" cellpadding="0"><tr>';
	print '<td class="blockh1">&nbsp;</td>';
	print '<td class="blockh2 blockhc"><b>'.$title.'</b></td>';
	print '<td class="blockh3">&nbsp;</td>';
	print '</tr></table><div class="blockcontent">';
	if ($block) {
		print '<div class="small_inner_block">'.$content.'</div>';
	} else {
		print $content;
	}
	print '</div></div>';
}

function print_charts_block_config($config) {
	global $pgv_lang, $ctype, $PGV_BLOCKS, $TEXT_DIRECTION, $PEDIGREE_ROOT_ID;
	if (empty($config)) $config = $PGV_BLOCKS["print_charts_block"]["config"];
	if (empty($config["rootId"])) $config["rootId"] = $PEDIGREE_ROOT_ID;
	if (empty($config['details'])) $config['details'] = 'no';
?>
	<tr><td class="descriptionbox wrap width33"><?php print $pgv_lang["chart_type"]; ?></td>
	<td class="optionbox">
		<select name="type">
			<option value="pedigree"<?php if ($config["type"]=="pedigree") print " selected=\"selected\""; ?>><?php print $pgv_lang["index_header"]; ?></option>
			<option value="descendants"<?php if ($config["type"]=="descendants") print " selected=\"selected\""; ?>><?php print $pgv_lang["descend_chart"]; ?></option>
			<option value="hourglass"<?php if ($config["type"]=="hourglass") print " selected=\"selected\""; ?>><?php print $pgv_lang["hourglass_chart"]; ?></option>
			<?php if (file_exists("includes/treenav_class.php")) { ?>
			<option value="treenav"<?php if ($config["type"]=="treenav") print " selected=\"selected\""; ?>>TreeNav</option>
			<?php } ?>
		</select>
	</td></tr>
	<tr>
		<td class="descriptionbox wrap width33"><?php print $pgv_lang["show_details"]; ?></td>
	<td class="optionbox">
		<select name="details">
				<option value="no" <?php if ($config["details"]=="no") print " selected=\"selected\""; ?>><?php print $pgv_lang['no']; ?></option>
				<option value="yes" <?php if ($config["details"]=="yes") print " selected=\"selected\""; ?>><?php print $pgv_lang['yes']; ?></option>
		</select>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox wrap width33"><?php print $pgv_lang["root_person"]; ?></td>
		<td class="optionbox">
			<input type="text" name="rootId" id="rootId" value="<?php print $config['rootId']; ?>" size="5" />
			<?php
			print_findindi_link('rootId','');
			if ($config['rootId']) {
				echo
					'<span class="list_item">',
					get_person_name($config['rootId']),
					format_first_major_fact($config['rootId']),
					'</span>';
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
