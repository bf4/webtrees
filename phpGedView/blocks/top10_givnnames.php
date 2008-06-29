<?php
/**
 * Top 10 Given Names Block
 *
 * This block will show the top 10 given names that occur most frequently in the active gedcom
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2008  PGV Development Team.  All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License or,
 * at your discretion, any later version.
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
 * @author kiwi_pgv
 * @package PhpGedView
 * @subpackage Blocks
 */

$PGV_BLOCKS["print_block_givn_top10"]["name"]		= $pgv_lang["block_givn_top10"];
$PGV_BLOCKS["print_block_givn_top10"]["descr"]		= "block_givn_top10_descr";
$PGV_BLOCKS["print_block_givn_top10"]["type"]		= "both";
$PGV_BLOCKS["print_block_givn_top10"]["infoStyle"]	= "style2";
$PGV_BLOCKS["print_block_givn_top10"]["canconfig"]	= true;
$PGV_BLOCKS["print_block_givn_top10"]["config"]		= array(
	"cache"=>7,
	"num"=>10, 
	"infoStyle"=>"style2",
	"showUnknown"=>"yes"
	);

/**
 * Print First Names Block
 */
function print_block_givn_top10($block=true, $config="", $side, $index) {
	global $TBLPREFIX, $DBCONN, $GEDCOMS;
	global $pgv_lang, $GEDCOM, $DEBUG, $TEXT_DIRECTION;
	global $PGV_BLOCKS, $ctype, $PGV_IMAGES, $PGV_IMAGE_DIR;

	if (empty($config)) $config = $PGV_BLOCKS["print_block_givn_top10"]["config"];
	if (isset($config["infoStyle"])) $infoStyle = $config["infoStyle"];  // "style1" or "style2"
	else $infoStyle = "style2";
	if (isset($config["showUnknown"])) $showUnknown = $config["showUnknown"];  // "yes" or "no"
	else $showUnknown = "yes";
	
	//-- cache the result in the session so that subsequent calls do not have to
	//-- perform the calculation all over again.
	if (isset($_SESSION["first_names_f"][$GEDCOM]) && isset($_SESSION["first_names_m"][$GEDCOM]) && isset($_SESSION["first_names_u"][$GEDCOM]) && (!isset($DEBUG)||($DEBUG==false))) {
		$name_list_f = $_SESSION["first_names_f"][$GEDCOM];
		$name_list_m = $_SESSION["first_names_m"][$GEDCOM];
		$name_list_u = $_SESSION["first_names_u"][$GEDCOM];
	} else {
		$name_list_f = array();
		$name_list_m = array();
		$name_list_u = array();
	
		//DB query
		$sql = "SELECT DISTINCT i_name, i_gedcom FROM ".$TBLPREFIX."individuals WHERE i_file=".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]["id"])." ";
		$res = dbquery($sql);

		if (!DB::isError($res)) {
			while ($row =& $res->fetchRow()) {
				if (preg_match('/1 SEX F/', $row[1])>0) $genderList = 'name_list_f';
				else if (preg_match('/1 SEX M/', $row[1])>0) $genderList = 'name_list_m';
				else $genderList = 'name_list_u';
				$allNames = get_indi_names($row[1], false, false);		// Get all names (except Married name)
				foreach ($allNames as $name) {
					$firstnamestring = preg_replace(':/.*/:', '', $name[0]);		// Remove surname
					$firstnamestring = str_replace(array('*', '.', '-', '_', ',', '(', ')', '[', ']', '{', '}'), ' ', $firstnamestring);
					$nameList = explode(" ", $firstnamestring);
					foreach ($nameList as $givnName) {
						$givnName = preg_replace(array(":^'(.*)'$:", ':^"(.*)"$:'), '\1', $givnName);		// Remove quotes and apostrophes enclosing name
						if (strlen($givnName)>1) {
							if (!isset(${$genderList}[$givnName])) ${$genderList}[$givnName] = 0;
							${$genderList}[$givnName] ++;
						}
					}
				}
			}
			$res->free();
		}
		//Calculate Female names
		arsort($name_list_f, SORT_NUMERIC);
		$_SESSION["first_names_f"][$GEDCOM] = $name_list_f;

		//Calculate Male names
		arsort($name_list_m, SORT_NUMERIC);
		$_SESSION["first_names_m"][$GEDCOM] = $name_list_m;

		//Calculate Unknown names
		arsort($name_list_u, SORT_NUMERIC);
		$_SESSION["first_names_u"][$GEDCOM] = $name_list_u;
	}

	//Print block header

	$id="top10givennames";
	$title = print_help_link("index_common_given_names_help", "qm","",false,true);
	if ($PGV_BLOCKS["print_block_givn_top10"]["canconfig"]) {
		if ($ctype=="gedcom" && PGV_USER_GEDCOM_ADMIN || $ctype=="user" && PGV_USER_ID) {
			if ($ctype=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
			else $name = PGV_USER_NAME;
			$title .= "<a href=\"javascript: configure block\" onclick=\"window.open('".encode_url("index_edit.php?name={$name}&ctype={$ctype}&action=configure&side={$side}&index={$index}")."', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
			$title .= "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>";
		}
	}
	$title .= str_replace("10", $config["num"], $pgv_lang["block_givn_top10_title"]);
		
	print '<div id="'.$id.'" class="block"><table class="blockheader" cellspacing="0" cellpadding="0"><tr>';
	print '<td class="blockh1">&nbsp;</td>';
	print '<td class="blockh2 blockhc"><b>'.$title.'</b></td>';
	print '<td class="blockh3">&nbsp;</td>';
	print '</tr></table><div class="blockcontent">';
	if ($block) print '<div class="small_inner_block">';
	else print '<div class="normal_inner_block">';
	
	//Select List or Table
	switch ($infoStyle) {
	case "style1":	// Output style 1:  Simple list style.  Better suited to left side of page.
		if ($TEXT_DIRECTION=='ltr') $padding = 'padding-left: 15px';
		else $padding = 'padding-right: 15px';
		//List Female names
		$nameList = array_slice($name_list_f, 0, $config["num"]);
		if (count($nameList)>0) {
			print "<b>".$pgv_lang["female"]."</b>";
			print "<div class=\"wrap\" style=\"$padding\">";
			if ($TEXT_DIRECTION=='rtl') $nameList = array_reverse($nameList, TRUE);		// This won't handle lists that span several lines
			foreach ($nameList as $key => $value) {
				if ($TEXT_DIRECTION=='ltr') echo ' ', PrintReady("{$key}&nbsp;({$value})&nbsp;&nbsp;");
				else echo ' ', PrintReady("&nbsp;&nbsp;({$value})&nbsp;{$key}");
			}
			print "</div><br />";
		}
		//List Male names	
		$nameList = array_slice($name_list_m, 0, $config["num"]);
		if (count($nameList)>0) {
			print "<b>".$pgv_lang["male"]."</b>";
			print "<div class=\"wrap\" style=\"$padding\">";
			if ($TEXT_DIRECTION=='rtl') $nameList = array_reverse($nameList, TRUE);		// This won't handle lists that span several lines
			foreach ($nameList as $key => $value) {
				if ($TEXT_DIRECTION=='ltr') echo ' ', PrintReady("{$key}&nbsp;({$value})&nbsp;&nbsp;");
				else echo ' ', PrintReady("&nbsp;&nbsp;({$value})&nbsp;{$key}");
			}
			print "</div><br />";
		}
		//List Unknown names	
		$nameList = array_slice($name_list_u, 0, $config["num"]);
		if (count($nameList)>0 && $showUnknown=="yes") {
			print "<b>".$pgv_lang["unknown"]."</b>";
			print "<div class=\"wrap\" style=\"$padding\">";
			if ($TEXT_DIRECTION=='rtl') $nameList = array_reverse($nameList, TRUE);		// This won't handle lists that span several lines
			foreach ($nameList as $key => $value) {
				if ($TEXT_DIRECTION=='ltr') echo ' ', PrintReady("{$key}&nbsp;({$value})&nbsp;&nbsp;");
				else echo ' ', PrintReady("&nbsp;&nbsp;({$value})&nbsp;{$key}");
			}
			print "</div><br />";
		}
		break;
	case "style2":	// Style 2: Tabular format.  Narrow, 2-column table, good on right side of page
		$nameAlign = ($TEXT_DIRECTION=='ltr') ? 'left':'right';
		//Table Headings
		print "<table class=\"center\">";
		print "<tr>";
			if (count($name_list_f)>0) print "<td class='descriptionbox' align='center'>".$pgv_lang["female"]."</td>";
			if (count($name_list_m)>0) print "<td class='descriptionbox' align='center'>".$pgv_lang["male"]."</td>";
			if (count($name_list_u)>0 && $showUnknown=="yes") print "<td class='descriptionbox' align='center'>".$pgv_lang["unknown"]."</td>";
		print "</tr>";
		print "<tr>";
		//List Female names
			if (count($name_list_f)>0) {
				print "<td valign='top'><table>";
				print "<tr><td class='descriptionbox' align='center'>".$pgv_lang["name"]."</td><td class='descriptionbox' align='center'>".$pgv_lang["count"]."</td></tr>";
				$nameList = array_slice($name_list_f, 0, $config["num"]);
				foreach ($nameList as $key => $value) {
					echo "<tr><td class='optionbox' align='{$nameAlign}'>".PrintReady($key)."</td><td class='optionbox' align='right'>".$value."</td></tr>";
				}
				print "</table></td>";
			}
		//List Male names
			if (count($name_list_m)>0) {
				print "<td valign='top'><table>";
				print "<tr><td class='descriptionbox' align='center'>".$pgv_lang["name"]."</td><td class='descriptionbox' align='center'>".$pgv_lang["count"]."</td></tr>";
				$nameList = array_slice($name_list_m, 0, $config["num"]);
				foreach ($nameList as $key => $value) {
					echo "<tr><td class='optionbox' align='{$nameAlign}'>".PrintReady($key)."</td><td class='optionbox' align='right'>".$value."</td></tr>";
				}
				print "</table></td>";
			}
		//List Unknown names	
			if (count($name_list_u)>0 && $showUnknown=="yes") {
				print "<td valign='top'><table>";
				print "<tr><td class='descriptionbox' align='center'>".$pgv_lang["name"]."</td><td class='descriptionbox' align='center'>".$pgv_lang["count"]."</td></tr>";
				$nameList = array_slice($name_list_u, 0, $config["num"]);
				foreach ($nameList as $key => $value) {
					echo "<tr><td class='optionbox' align='{$nameAlign}'>".PrintReady($key)."</td><td class='optionbox' align='right'>".$value."</td></tr>";
				}
				print "</table></td>";
			}
		//Close table off
		print "</tr></table>";
		break;
	}
print "</div></div></div>";
}

function print_block_givn_top10_config($config) {
	global $pgv_lang, $ctype, $PGV_BLOCKS, $TEXT_DIRECTION;
	if (empty($config)) $config = $PGV_BLOCKS["print_block_givn_top10"]["config"];
	if (!isset($config["cache"])) $config["cache"] = $PGV_BLOCKS["print_block_givn_top10"]["config"]["cache"];
	if (!isset($config["infoStyle"])) $config["infoStyle"] = "style2";
	if (!isset($config["showUnknown"])) $config["showUnknown"] = "yes";

	print "<tr><td class=\"descriptionbox wrap width33\">".$pgv_lang["num_to_show"]."</td>";?>
	<td class="optionbox">
		<input type="text" name="num" size="2" value="<?php print $config["num"]; ?>" />
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
	print_help_link("showUnknown_help", "qm");
	print $pgv_lang["showUnknown"];
	?>
	</td><td class="optionbox">
		<select name="showUnknown">
			<option value="yes"<?php if ($config["showUnknown"]=="yes") print " selected=\"selected\"";?>><?php print $pgv_lang["yes"]; ?></option>
			<option value="no"<?php if ($config["showUnknown"]=="no") print " selected=\"selected\"";?>><?php print $pgv_lang["no"]; ?></option>
		</select>
	</td></tr>

<?php	// Cache file life
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