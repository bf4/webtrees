<?php
/**
 * On Upcoming Events Block
 *
 * This block will print a list of upcoming events
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
 * @subpackage Blocks
 * @version $Id$
 */

$PGV_BLOCKS["print_upcoming_events"]["name"]		= $pgv_lang["upcoming_events_block"];
$PGV_BLOCKS["print_upcoming_events"]["descr"]		= "upcoming_events_descr";
$PGV_BLOCKS["print_upcoming_events"]["infoStyle"]	= "style2";
$PGV_BLOCKS["print_upcoming_events"]["canconfig"]	= true;
$PGV_BLOCKS["print_upcoming_events"]["config"]		= array(
	"cache"=>1,
	"days"=>30,
	"filter"=>"all",
	"onlyBDM"=>"no",
	"infoStyle"=>"style2",
	"allowDownload"=>"yes"
	);

//-- upcoming events block
//-- this block prints a list of upcoming events of people in your gedcom
function print_upcoming_events($block=true, $config="", $side, $index) {
	global $pgv_lang, $SHOW_ID_NUMBERS, $ctype, $TEXT_DIRECTION;
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $PGV_BLOCKS;
	global $DAYS_TO_SHOW_LIMIT;

	$block = true;      // Always restrict this block's height

	if (empty($config)) $config = $PGV_BLOCKS["print_upcoming_events"]["config"];
	if (!isset($DAYS_TO_SHOW_LIMIT)) $DAYS_TO_SHOW_LIMIT = 30;
	if (isset($config["days"])) $daysprint = $config["days"];
	else $daysprint = 30;
	if (isset($config["filter"])) $filter = $config["filter"];  // "living" or "all"
	else $filter = "all";
	if (isset($config["onlyBDM"])) $onlyBDM = $config["onlyBDM"];  // "yes" or "no"
	else $onlyBDM = "no";
	if (isset($config["infoStyle"])) $infoStyle = $config["infoStyle"];  // "style1" or "style2"
	else $infoStyle = "style2";
	if (isset($config["allowDownload"])) $allowDownload = $config["allowDownload"];	// "yes" or "no"
	else $allowDownload = "yes";

	// Don't permit calendar download if not logged in
	if (!PGV_USER_ID) {
		$allowDownload = "no";
	}


	if ($daysprint < 1) $daysprint = 1;
	if ($daysprint > $DAYS_TO_SHOW_LIMIT) $daysprint = $DAYS_TO_SHOW_LIMIT;  // valid: 1 to limit
	$startjd=client_jd()+1;
	$endjd=client_jd()+$daysprint;

	// Output starts here
	$id="upcoming_events";
	$title = print_help_link("index_events_help", "qm","",false, true);
	if ($PGV_BLOCKS["print_upcoming_events"]["canconfig"]) {
		if ($ctype=="gedcom" && PGV_USER_GEDCOM_ADMIN || $ctype=="user" && PGV_USER_ID) {
			if ($ctype=="gedcom") {
				$name = preg_replace("/'/", "\'", $GEDCOM);
			} else {
				$name = PGV_USER_NAME;
			}
 			$title .= "<a href=\"javascript: configure block\" onclick=\"window.open('".encode_url("index_edit.php?name={$name}&ctype={$ctype}&action=configure&side={$side}&index={$index}")."', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
			$title .= "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>";
		}
	}
	$title .= $pgv_lang["upcoming_events"];

	$content = "";
	switch ($infoStyle) {
	case "style1":
		// Output style 1:  Old format, no visible tables, much smaller text.  Better suited to right side of page.
		$content .= print_events_list($startjd, $endjd, $onlyBDM=='yes'?'BIRT MARR DEAT':'', $filter=='living', true);
		break;
	case "style2":
		// Style 2: New format, tables, big text, etc.  Not too good on right side of page
		ob_start();
		print_events_table($startjd, $endjd, $onlyBDM=='yes'?'BIRT MARR DEAT':'', $filter=='living', $allowDownload=='yes');
		$content .= ob_get_clean();
		break;
	}

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

function print_upcoming_events_config($config) {
	global $pgv_lang, $PGV_BLOCKS, $DAYS_TO_SHOW_LIMIT;
	if (empty($config)) $config = $PGV_BLOCKS["print_upcoming_events"]["config"];
	if (!isset($DAYS_TO_SHOW_LIMIT)) $DAYS_TO_SHOW_LIMIT = 30;
	if (!isset($config["days"])) $config["days"] = 30;
	if (!isset($config["filter"])) $config["filter"] = "all";
	if (!isset($config["onlyBDM"])) $config["onlyBDM"] = "no";
	if (!isset($config["infoStyle"])) $config["infoStyle"] = "style2";
	if (!isset($config["allowDownload"])) $config["allowDownload"] = "yes";

	if ($config["days"] < 1) $config["days"] = 1;
	if ($config["days"] > $DAYS_TO_SHOW_LIMIT) $config["days"] = $DAYS_TO_SHOW_LIMIT;  // valid: 1 to limit

	?>
		<tr><td class="descriptionbox wrap width33">
		<?php
	print_help_link("days_to_show_help", "qm");
	print $pgv_lang["days_to_show"];
	?>
	</td><td class="optionbox">
		<input type="text" name="days" size="2" value="<?php print $config["days"]; ?>" />
	</td></tr>

		<tr><td class="descriptionbox wrap width33">
		<?php
		print $pgv_lang["living_or_all"];
		?>
		</td><td class="optionbox">
	<select name="filter">
		<option value="all"<?php if ($config["filter"]=="all") print " selected=\"selected\"";?>><?php print $pgv_lang["no"]; ?></option>
		<option value="living"<?php if ($config["filter"]=="living") print " selected=\"selected\"";?>><?php print $pgv_lang["yes"]; ?></option>
	</select>
	</td></tr>

		<tr><td class="descriptionbox wrap width33">
	<?php
	print_help_link("basic_or_all_help", "qm");
	print $pgv_lang["basic_or_all"];
	?>
	</td><td class="optionbox">
		<select name="onlyBDM">
		<option value="no"<?php if ($config["onlyBDM"]=="no") print " selected=\"selected\"";?>><?php print $pgv_lang["no"]; ?></option>
		<option value="yes"<?php if ($config["onlyBDM"]=="yes") print " selected=\"selected\"";?>><?php print $pgv_lang["yes"]; ?></option>
		</select>
	</td></tr>

		<tr><td class="descriptionbox wrap width33">
	<?php
	print_help_link("style_help", "qm");
	print $pgv_lang["style"];
	?>
	</td><td class="optionbox">
		<select name="infoStyle">
			<option value="style1"<?php if ($config["infoStyle"]=="style1") print " selected=\"selected\"";?>><?php print $pgv_lang["style1"]; ?></option>
			<option value="style2"<?php if ($config["infoStyle"]=="style2") print " selected=\"selected\"";?>><?php print $pgv_lang["style2"]; ?></option>
		</select>
	</td></tr>

		<tr><td class="descriptionbox wrap width33">
		<?php
 	print_help_link("cal_dowload_help", "qm");
		print $pgv_lang["cal_download"]."</td>";
		?>
		<td class="optionbox">
		<select name="allowDownload">
			<option value="yes"<?php if ($config["allowDownload"]=="yes") print " selected=\"selected\"";?>><?php print $pgv_lang["yes"]; ?></option>
			<option value="no"<?php if ($config["allowDownload"]=="no") print " selected=\"selected\"";?>><?php print $pgv_lang["no"]; ?></option>
		</select>
		<input type="hidden" name="cache" value="1" />
		</td></tr>
	<?php

}
?>
