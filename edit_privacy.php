<?php
/**
 * Edit Privacy Settings
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
 * This Page Is Valid XHTML 1.0 Transitional! > 12 September 2005
 *
 * @author PGV Development Team
 * @package PhpGedView
 * @subpackage Privacy
 * @version $Id$
 */
require "config.php";
require_once("includes/datamodel/gedcomrecord_class.php");
loadLangFile("pgv_confighelp, pgv_help");

if (empty($ged)) $ged = $GEDCOM;
if (!userGedcomAdmin(PGV_USER_ID, $ged) || empty($ged)) {
	header("Location: editgedcoms.php");
	exit;
}
if (isset($_REQUEST['action'])) $action = $_REQUEST['action'];

$PRIVACY_CONSTANTS = array();
$PRIVACY_CONSTANTS[$PRIV_HIDE] = "\$PRIV_HIDE";
$PRIVACY_CONSTANTS[$PRIV_PUBLIC] = "\$PRIV_PUBLIC";
$PRIVACY_CONSTANTS[$PRIV_USER] = "\$PRIV_USER";
$PRIVACY_CONSTANTS[$PRIV_NONE] = "\$PRIV_NONE";

if (!isset($PRIVACY_BY_YEAR)) $PRIVACY_BY_YEAR = false;
if (!isset($MAX_ALIVE_AGE)) $MAX_ALIVE_AGE = 120;
/**
 * print write_access option
 *
 * @param string $checkVar
 */
function write_access_option($checkVar) {
  global $PRIV_HIDE, $PRIV_PUBLIC, $PRIV_USER, $PRIV_NONE;
  global $pgv_lang;
  print "<option value=\"\$PRIV_PUBLIC\"";
  if ($checkVar==$PRIV_PUBLIC) print " selected=\"selected\"";
  print ">".$pgv_lang["PRIV_PUBLIC"]."</option>\n";
  print "<option value=\"\$PRIV_USER\"";
  if ($checkVar==$PRIV_USER) print " selected=\"selected\"";
  print ">".$pgv_lang["PRIV_USER"]."</option>\n";
  print "<option value=\"\$PRIV_NONE\"";
  if ($checkVar==$PRIV_NONE) print " selected=\"selected\"";
  print ">".$pgv_lang["PRIV_NONE"]."</option>\n";
  print "<option value=\"\$PRIV_HIDE\"";
  if ($checkVar==$PRIV_HIDE) print " selected=\"selected\"";
  print ">".$pgv_lang["PRIV_HIDE"]."</option>\n";
}
/**
 * print yes/no select option
 *
 * @param string $checkVar
 */
function write_yes_no($checkVar) {
  global $pgv_lang;
  print "<option";
  if ($checkVar == false) print " selected=\"selected\"";
  print " value=\"no\">";
  print $pgv_lang["no"];
  print "</option>\n";
  print "<option";
  if ($checkVar == true) print " selected=\"selected\"";
  print " value=\"yes\">";
  print $pgv_lang["yes"];
  print "</option>";
}
/**
 * print find and print gedcom record ID
 *
 * @param string $checkVar	gedcom key
 * @param string $outputVar	error message style
 */
function search_ID_details($checkVar, $outputVar) {
	global $GEDCOMS, $GEDCOM;
	global $pgv_lang;
	$indirec = find_gedcom_record($checkVar);
	if (empty($indirec)) $indirec = find_updated_record($checkVar);
	if (!empty($indirec)) {
		$ct = preg_match("/0 @(.*)@ (.*)/", $indirec, $match);
		if ($ct>0) {
			$pid = $match[1];
			$type = trim($match[2]);
		}
		switch ($type) {
		case 'INDI':
			echo '<span class="list_item">', PrintReady(get_person_name($pid)), format_first_major_fact($pid), '</span>';
			break;
		default:
			$record=GedcomRecord::getInstance($pid);
			echo '<span class="list_item">', $record->getListName(), '</span>';
			break;
	}
	} else {
		print "<span class=\"error\">";
		if ($outputVar == 1) {
			print $pgv_lang["unable_to_find_privacy_indi"];
			print "<br />[" . $checkVar . "]";
		}
		if ($outputVar == 2) {
			print $pgv_lang["unable_to_find_privacy_indi"];
		}
		print "</span><br /><br />";
	}
}
if (empty($action)) $action="";
$PRIVACY_MODULE = get_privacy_file();
print_header($pgv_lang["privacy_header"]);
?>
<table class="facts_table <?php print $TEXT_DIRECTION; ?>">
	<tr>
		<td colspan="2" class="facts_label"><?php
			print "<h2>".$pgv_lang["edit_privacy_title"]." - ".PrintReady(strip_tags(get_gedcom_setting($ged, 'title'))). "</h2>";
			print "(" . getLRM() . $PRIVACY_MODULE.")";
			print "<br /><br /><a href=\"editgedcoms.php\"><b>";
			print $pgv_lang["lang_back_manage_gedcoms"];
			print "</b></a><br /><br />";?>
		</td>
	</tr>
</table>
<?php
if ($action=="update") {
	if (!isset($_POST)) $_POST = $HTTP_POST_VARS;
	$boolarray = array();
	$boolarray["yes"]="true";
	$boolarray["no"]="false";
	$boolarray[false]="false";
	$boolarray[true]="true";
	print "<table class=\"facts_table $TEXT_DIRECTION\">";
	print "<tr><td class=\"descriptionbox\">";
	print $pgv_lang["performing_update"];
	print "<br />";
	$configtext = implode('', file("privacy.php"));
	print $pgv_lang["config_file_read"];
	print "</td></tr></table>\n";
	$configtext = preg_replace('/\$SHOW_DEAD_PEOPLE\s*=\s*.*;/', "\$SHOW_DEAD_PEOPLE = ".$_POST["v_SHOW_DEAD_PEOPLE"].";", $configtext);
	$configtext = preg_replace('/\$SHOW_LIVING_NAMES\s*=\s*.*;/', "\$SHOW_LIVING_NAMES = ".$_POST["v_SHOW_LIVING_NAMES"].";", $configtext);
	$configtext = preg_replace('/\$SHOW_SOURCES\s*=\s*.*;/', "\$SHOW_SOURCES = ".$_POST["v_SHOW_SOURCES"].";", $configtext);
	$configtext = preg_replace('/\$MAX_ALIVE_AGE\s*=\s*".*";/', "\$MAX_ALIVE_AGE = \"".$_POST["v_MAX_ALIVE_AGE"]."\";", $configtext);
	if ($MAX_ALIVE_AGE!=$_POST["v_MAX_ALIVE_AGE"]) reset_isdead();
	if (file_exists("modules/research_assistant.php")) {
		$configtext = preg_replace('/\$SHOW_RESEARCH_ASSISTANT\s*=\s*.*;/', "\$SHOW_RESEARCH_ASSISTANT = ".$_POST["v_SHOW_RESEARCH_ASSISTANT"].";", $configtext);
	}
	$configtext = preg_replace('/\$SHOW_MULTISITE_SEARCH\s*=\s*.*;/', "\$SHOW_MULTISITE_SEARCH = ".$_POST["v_SHOW_MULTISITE_SEARCH"].";", $configtext);
	$configtext = preg_replace('/\$ENABLE_CLIPPINGS_CART\s*=\s*.*;/', "\$ENABLE_CLIPPINGS_CART = ".$_POST["v_ENABLE_CLIPPINGS_CART"].";", $configtext);
	$configtext = preg_replace('/\$PRIVACY_BY_YEAR\s*=\s*.*;/', "\$PRIVACY_BY_YEAR = ".$boolarray[$_POST["v_PRIVACY_BY_YEAR"]].";", $configtext);
	$configtext = preg_replace('/\$PRIVACY_BY_RESN\s*=\s*.*;/', "\$PRIVACY_BY_RESN = ".$boolarray[$_POST["v_PRIVACY_BY_RESN"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_DEAD_PEOPLE\s*=\s*.*;/', "\$SHOW_DEAD_PEOPLE = ".$_POST["v_SHOW_DEAD_PEOPLE"].";", $configtext);
	$configtext = preg_replace('/\$USE_RELATIONSHIP_PRIVACY\s*=\s*.*;/', "\$USE_RELATIONSHIP_PRIVACY = ".$boolarray[$_POST["v_USE_RELATIONSHIP_PRIVACY"]].";", $configtext);
	$configtext = preg_replace('/\$MAX_RELATION_PATH_LENGTH\s*=\s*.*;/', "\$MAX_RELATION_PATH_LENGTH = \"".$_POST["v_MAX_RELATION_PATH_LENGTH"]."\";", $configtext);
	$configtext = preg_replace('/\$CHECK_MARRIAGE_RELATIONS\s*=\s*.*;/', "\$CHECK_MARRIAGE_RELATIONS = ".$boolarray[$_POST["v_CHECK_MARRIAGE_RELATIONS"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_PRIVATE_RELATIONSHIPS\s*=\s*.*;/', "\$SHOW_PRIVATE_RELATIONSHIPS = ".$boolarray[$_POST["v_SHOW_PRIVATE_RELATIONSHIPS"]].";", $configtext);
	$configtext_beg = substr($configtext, 0, strpos($configtext, "//-- start person privacy --//"));
	$configtext_end = substr($configtext, strpos($configtext, "//-- end person privacy --//"));
	$person_privacy_text = "//-- start person privacy --//\n\$person_privacy = array();\n";
	if (!isset($v_person_privacy_del)) $v_person_privacy_del = array();
	if (!is_array($v_person_privacy_del)) $v_person_privacy_del = array();
	if (!isset($v_person_privacy)) $v_person_privacy = array();
	if (!is_array($v_person_privacy)) $v_person_privacy = array();
	foreach($person_privacy as $key=>$value) {
		if (!isset($v_person_privacy_del[$key])) {
			if (isset($v_person_privacy[$key])) $person_privacy_text .= "\$person_privacy['$key'] = ".$v_person_privacy[$key].";\n";
			else $person_privacy_text .= "\$person_privacy['$key'] = ".$PRIVACY_CONSTANTS[$value].";\n";
		}
	}
	if ((!empty($v_new_person_privacy_access_ID))&&(!empty($v_new_person_privacy_acess_option))) {
		$gedobj = new GedcomRecord(find_gedcom_record($v_new_person_privacy_access_ID));
		$v_new_person_privacy_access_ID = $gedobj->getXref();
		if (!empty($v_new_person_privacy_access_ID)) $person_privacy_text .= "\$person_privacy['$v_new_person_privacy_access_ID'] = ".$v_new_person_privacy_acess_option.";\n";
	}
	$configtext = $configtext_beg . $person_privacy_text . $configtext_end;
	$configtext_beg = substr($configtext, 0, strpos($configtext, "//-- start user privacy --//"));
	$configtext_end = substr($configtext, strpos($configtext, "//-- end user privacy --//"));
	$person_privacy_text = "//-- start user privacy --//\n\$user_privacy = array();\n";
	if (!isset($v_user_privacy_del)) $v_user_privacy_del = array();
	if (!is_array($v_user_privacy_del)) $v_user_privacy_del = array();
	if (!isset($v_user_privacy)) $v_user_privacy = array();
	if (!is_array($v_user_privacy)) $v_user_privacy = array();
	foreach($user_privacy as $key=>$value) {
		foreach($value as $id=>$setting) {
			if (!isset($v_user_privacy_del[$key][$id])) {
				if (isset($v_user_privacy[$key][$id])) $person_privacy_text .= "\$user_privacy['$key']['$id'] = ".$v_user_privacy[$key][$id].";\n";
				else $person_privacy_text .= "\$user_privacy['$key']['$id'] = ".$PRIVACY_CONSTANTS[$setting].";\n";
			}
		}
	}
	if ((!empty($v_new_user_privacy_username))&&(!empty($v_new_user_privacy_access_ID))&&(!empty($v_new_user_privacy_acess_option))) {
		$gedobj = new GedcomRecord(find_gedcom_record($v_new_user_privacy_access_ID));
		$v_new_user_privacy_access_ID = $gedobj->getXref();
		if (!empty($v_new_user_privacy_access_ID)) $person_privacy_text .= "\$user_privacy['$v_new_user_privacy_username']['$v_new_user_privacy_access_ID'] = ".$v_new_user_privacy_acess_option.";\n";
	}
	$configtext = $configtext_beg . $person_privacy_text . $configtext_end;
	$configtext_beg = substr($configtext, 0, strpos($configtext, "//-- start global facts privacy --//"));
	$configtext_end = substr($configtext, strpos($configtext, "//-- end global facts privacy --//"));
	$person_privacy_text = "//-- start global facts privacy --//\n\$global_facts = array();\n";
	if (!isset($v_global_facts_del)) $v_global_facts_del = array();
	if (!is_array($v_global_facts_del)) $v_global_facts_del = array();
	if (!isset($v_global_facts)) $v_global_facts = array();
	if (!is_array($v_global_facts)) $v_global_facts = array();
	foreach($global_facts as $tag=>$value) {
		foreach($value as $key=>$setting) {
			if (!isset($v_global_facts_del[$tag][$key])) {
				if (isset($v_global_facts[$tag][$key])) $person_privacy_text .= "\$global_facts['$tag']['$key'] = ".$v_global_facts[$tag][$key].";\n";
				else $person_privacy_text .= "\$global_facts['$tag']['$key'] = ".$PRIVACY_CONSTANTS[$setting].";\n";
			}
		}
	}
	if ((!empty($v_new_global_facts_abbr))&&(!empty($v_new_global_facts_choice))&&(!empty($v_new_global_facts_acess_option))) {
		$person_privacy_text .= "\$global_facts['$v_new_global_facts_abbr']['$v_new_global_facts_choice'] = ".$v_new_global_facts_acess_option.";\n";
	}
	$configtext = $configtext_beg . $person_privacy_text . $configtext_end;
	$configtext_beg = substr($configtext, 0, strpos($configtext, "//-- start person facts privacy --//"));
	$configtext_end = substr($configtext, strpos($configtext, "//-- end person facts privacy --//"));
	$person_privacy_text = "//-- start person facts privacy --//\n\$person_facts = array();\n";
	if (!isset($v_person_facts_del)) $v_person_facts_del = array();
	if (!is_array($v_person_facts_del)) $v_person_facts_del = array();
	if (!isset($v_person_facts)) $v_person_facts = array();
	if (!is_array($v_person_facts)) $v_person_facts = array();
	foreach($person_facts as $id=>$value) {
		foreach($value as $tag=>$value1) {
			foreach($value1 as $key=>$setting) {
				if (!isset($v_person_facts_del[$id][$tag][$key])) {
					if (isset($v_person_facts[$id][$tag][$key])) $person_privacy_text .= "\$person_facts['$id']['$tag']['$key'] = ".$v_person_facts[$id][$tag][$key].";\n";
					else $person_privacy_text .= "\$person_facts['$id']['$tag']['$key'] = ".$PRIVACY_CONSTANTS[$setting].";\n";
				}
			}
		}
	}
	if ((!empty($v_new_person_facts_access_ID))&&(!empty($v_new_person_facts_abbr))&&(!empty($v_new_global_facts_choice))&&(!empty($v_new_global_facts_acess_option))) {
		$gedobj = new GedcomRecord(find_gedcom_record($v_new_person_facts_access_ID));
		$v_new_person_facts_access_ID = $gedobj->getXref();
		if (!empty($v_new_person_facts_access_ID)) $person_privacy_text .= "\$person_facts['$v_new_person_facts_access_ID']['$v_new_person_facts_abbr']['$v_new_person_facts_choice'] = ".$v_new_person_facts_acess_option.";\n";
	}
	$configtext = $configtext_beg . $person_privacy_text . $configtext_end;
	$PRIVACY_MODULE = $INDEX_DIRECTORY.$GEDCOM."_priv.php";
	$fp = @fopen($PRIVACY_MODULE, "wb");
	if (!$fp) {
		global $whichFile;
		$whichFile = $PRIVACY_MODULE;
		print "<span class=\"error\">".print_text("gedcom_config_write_error",0,1)."<br /></span>\n";
	}
	else {
		fwrite($fp, $configtext);
		fclose($fp);
	}
	// NOTE: load the new variables
	include $INDEX_DIRECTORY.$GEDCOM."_priv.php";
	$logline = AddToLog("Privacy file $PRIVACY_MODULE updated");
 	$gedcomprivname = $GEDCOM."_priv.php";
 	check_in($logline, $gedcomprivname, $INDEX_DIRECTORY);

 	//-- delete the cache files for the welcome page blocks
	include_once("includes/index_cache.php");
	clearCache();
}
?>
<script language="JavaScript" type="text/javascript">
<!--
  	var pastefield;
	function paste_id(value) {
	    pastefield.value=value;
	}
	var helpWin;
	function helpPopup(which) {
		if ((!helpWin)||(helpWin.closed)) helpWin = window.open('editconfig_help.php?help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');
		else helpWin.location = 'editconfig_help.php?help='+which;
		return false;
	}
//-->
</script>
<form name="editprivacyform" method="post" action="edit_privacy.php">
		<input type="hidden" name="action" value="update" />
		<?php print "<input type=\"hidden\" name=\"ged\" value=\"".$GEDCOM."\" />\n";
require_once("js/dhtmlXTabbar.js.htm");
		?>
	<div id="gedpriv_tabbar" class="dhtmlxTabBar" width="100%" height="500px" <?php if($TEXT_DIRECTION=="rtl") echo ' align="right"'?>>
		<div id="gedpriv1" name="<?php echo $pgv_lang["general_privacy"]?>" class="indent" >
			<fieldset>
				<legend>
					<?php echo $pgv_lang["general_privacy"]?>
				</legend>
				<table class="facts_table">
			<tr>
						<td class="descriptionbox wrap <?php print $TEXT_DIRECTION;?>">
							<?php print_help_link("SHOW_DEAD_PEOPLE_help", "qm", "SHOW_DEAD_PEOPLE"); print $pgv_lang["SHOW_DEAD_PEOPLE"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_SHOW_DEAD_PEOPLE">
								<?php write_access_option($SHOW_DEAD_PEOPLE); ?>
							</select></td>
			</tr>
			<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("SHOW_LIVING_NAMES_help", "qm", "SHOW_LIVING_NAMES"); print $pgv_lang["SHOW_LIVING_NAMES"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_SHOW_LIVING_NAMES">
								<?php write_access_option($SHOW_LIVING_NAMES); ?>
							</select></td>
			</tr>
			<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("SHOW_SOURCES_help", "qm", "SHOW_SOURCES"); print $pgv_lang["SHOW_SOURCES"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_SHOW_SOURCES">
								<?php write_access_option($SHOW_SOURCES); ?>
							</select></td>
			</tr>
			<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("ENABLE_CLIPPINGS_CART_help", "qm", "ENABLE_CLIPPINGS_CART"); print $pgv_lang["ENABLE_CLIPPINGS_CART"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_ENABLE_CLIPPINGS_CART">
								<?php write_access_option($ENABLE_CLIPPINGS_CART); ?>
							</select></td>
			</tr>
			<?php if (file_exists("modules/research_assistant.php")) { ?>
				<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("SHOW_RESEARCH_ASSISTANT_help", "qm", "SHOW_RESEARCH_ASSISTANT"); print $pgv_lang["SHOW_RESEARCH_ASSISTANT"]; ?></td>
					<td class="optionbox">
							<select size="1" name="v_SHOW_RESEARCH_ASSISTANT">
								<?php write_access_option($SHOW_RESEARCH_ASSISTANT); ?>
							</select></td>
				</tr>
			<?php } ?>
			<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("SHOW_MULTISITE_SEARCH_help", "qm", "SHOW_MULTISITE_SEARCH"); print $pgv_lang["SHOW_MULTISITE_SEARCH"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_SHOW_MULTISITE_SEARCH">
								<?php write_access_option($SHOW_MULTISITE_SEARCH); ?>
							</select></td>
			</tr>
			<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("PRIVACY_BY_YEAR_help", "qm", "PRIVACY_BY_YEAR"); print $pgv_lang["PRIVACY_BY_YEAR"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_PRIVACY_BY_YEAR">
								<?php write_yes_no($PRIVACY_BY_YEAR); ?>
							</select></td>
			</tr>
			<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("PRIVACY_BY_RESN_help", "qm", "PRIVACY_BY_RESN"); print $pgv_lang["PRIVACY_BY_RESN"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_PRIVACY_BY_RESN">
								<?php write_yes_no($PRIVACY_BY_RESN); ?>
							</select></td>
			</tr>
			<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("SHOW_PRIVATE_RELATIONSHIPS_help", "qm", "SHOW_PRIVATE_RELATIONSHIPS"); print $pgv_lang["SHOW_PRIVATE_RELATIONSHIPS"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_SHOW_PRIVATE_RELATIONSHIPS">
								<?php write_yes_no($SHOW_PRIVATE_RELATIONSHIPS); ?>
							</select></td>
			</tr>
			<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("USE_RELATIONSHIP_PRIVACY_help", "qm", "USE_RELATIONSHIP_PRIVACY"); print $pgv_lang["USE_RELATIONSHIP_PRIVACY"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_USE_RELATIONSHIP_PRIVACY">
								<?php write_yes_no($USE_RELATIONSHIP_PRIVACY); ?>
							</select></td>
			</tr>
			<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("MAX_RELATION_PATH_LENGTH_help", "qm", "MAX_RELATION_PATH_LENGTH"); print $pgv_lang["MAX_RELATION_PATH_LENGTH"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_MAX_RELATION_PATH_LENGTH">
<?php
          for ($y = 1; $y <= 10; $y++) {
            print "<option";
            if ($MAX_RELATION_PATH_LENGTH == $y) print " selected=\"selected\"";
            print ">";
            print $y;
            print "</option>";
          }
								?>
							</select></td>
			</tr>
			<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("CHECK_MARRIAGE_RELATIONS_help", "qm", "CHECK_MARRIAGE_RELATIONS"); print $pgv_lang["CHECK_MARRIAGE_RELATIONS"]; ?></td>
				<td class="optionbox">
							<select size="1" name="v_CHECK_MARRIAGE_RELATIONS">
								<?php write_yes_no($CHECK_MARRIAGE_RELATIONS); ?>
							</select></td>
			</tr>
		<tr>
						<td class="descriptionbox wrap">
							<?php print_help_link("MAX_ALIVE_AGE_help", "qm", "MAX_ALIVE_AGE"); print $pgv_lang["MAX_ALIVE_AGE"]?></td>
						<td class="optionbox">
							<input type="text" name="v_MAX_ALIVE_AGE" value="<?php print $MAX_ALIVE_AGE?>" size="5"/></td>
		</tr>
					<tr>
						<td></td>
						<td>
							<input type="submit" tabindex="<?php $i++; print $i?>" value="<?php print $pgv_lang["save_config"]?>
							" onclick="closeHelp();" /></td>
					</tr>
		</table>
				</fieldset>
	</div>
		<div id="gedpriv2" name="<?php echo $pgv_lang["person_privacy"]?>" class="indent" >
			<fieldset>
				<legend>
					<?php echo $pgv_lang["person_privacy"]?>
				</legend>
		<table class="facts_table">
			<tr>
						<td class="descriptionbox">
							<?php print $pgv_lang["id"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["full_name"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["accessible_by"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["delete"]; ?></td>
					</tr>
<?php
          if (count($person_privacy) > 0) {
            foreach($person_privacy as $key=>$value) {
		?>
					<tr>
						<td class="optionbox">
							<?php print $key; ?></td>
						<td class="optionbox">
							<?php search_ID_details($key, 1); ?></td>
						<td class="optionbox">
							<select size="1" name="v_person_privacy[<?php print $key; ?>]">
								<?php write_access_option($value); ?>
							</select></td>
						<td class="optionbox">
							<input type="checkbox" name="v_person_privacy_del[<?php print $key; ?>]" value="1" /></td>
						</tr>
<?php
						}
					}?>
						<tr>
							<td class="optionbox width20">
								<input type="text" class="pedigree_form" name="v_new_person_privacy_access_ID" id="v_new_person_privacy_access_ID" size="4" />
								<?php
			 print_findindi_link("v_new_person_privacy_access_ID","");
			 print_findfamily_link("v_new_person_privacy_access_ID");
			 print_findsource_link("v_new_person_privacy_access_ID");
			 print_findmedia_link("v_new_person_privacy_access_ID", "1media");
							?></td>
						<td class="descriptionbox"></td>
							<td class="optionbox">
							<select size="1" name="v_new_person_privacy_acess_option">
								<?php write_access_option(""); ?>
							</select></td>
						<td class="descriptionbox"></td>
						</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							<input type="submit" tabindex="<?php $i++; print $i?>" value="<?php print $pgv_lang["save_config"]?>
							" onclick="closeHelp();" /></td>
						<td></td>
					</tr>
				</table>
				</fieldset>
		</div>
		<div id="gedpriv3" name="<?php echo $pgv_lang["user_privacy"]?>" class="indent" >
			<fieldset>
				<legend>
					<?php echo $pgv_lang["user_privacy"]?>
				</legend>
					<table class="facts_table">
						<tr>
						<td class="descriptionbox">
							<?php print $pgv_lang["user_name"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["id"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["full_name"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["show_question"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["delete"]; ?></td>
						</tr>
						<?php
          if (count($user_privacy) > 0) {
            foreach($user_privacy as $key=>$value) {
	            foreach($value as $id=>$setting) {
						?>
					<tr class="<?php print $TEXT_DIRECTION; ?>">
							<td class="optionbox">
							<?php print $key; ?></td>
							<td class="optionbox">
							<?php print $id; ?></td>
						<td class="optionbox">
							<?php search_ID_details($id, 2); ?></td>
						<td class="optionbox">
							<select size="1" name="v_user_privacy[<?php print $key; ?>][<?php print $id; ?> ]">
								<?php write_access_option($setting); ?>
							</select></td>
						<td class="optionbox">
							<input type="checkbox" name="v_user_privacy_del[<?php print $key; ?>][<?php print $id; ?>
							]" value="1" /></td>
						</tr>
						<?php
        			}
        		}
						}?>
						<tr class="<?php print $TEXT_DIRECTION; ?>">
							<td class="optionbox width20">
								<select size="1" name="v_new_user_privacy_username">
								<?php
								foreach(get_all_users() as $user_id=>$user_name)
                {
                  print "<option";
                  print " value=\"";
									print $user_id;
                  print "\">";
									print getUserFullName($user_id);
                  print "</option>";
                }
								?>
							</select></td>
							<td class="optionbox">
								<input type="text" class="pedigree_form" name="v_new_user_privacy_access_ID" id="v_new_user_privacy_access_ID" size="4" />
								<?php
			 print_findindi_link("v_new_user_privacy_access_ID","");
			 print_findfamily_link("v_new_user_privacy_access_ID");
			 print_findsource_link("v_new_user_privacy_access_ID");
			 print_findmedia_link("v_new_person_privacy_access_ID", "1media");
							?></td>
						<td class="descriptionbox"></td>
							<td class="optionbox">
							<select size="1" name="v_new_user_privacy_acess_option">
								<?php write_access_option(""); ?>
							</select></td>
						<td class="descriptionbox"></td>
						</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td>
							<input type="submit" tabindex="<?php $i++; print $i?>" value="<?php print $pgv_lang["save_config"]?>
							" onclick="closeHelp();" /></td>
						<td></td>
					</tr>
					</table>
				</fieldset>
		</div>
		<div id="gedpriv4" name="<?php echo $pgv_lang["global_facts"]?>" class="indent" >
			<fieldset>
				<legend>
					<?php echo $pgv_lang["global_facts"]?>
				</legend>
					<table class="facts_table">
						<tr>
						<td class="descriptionbox">
							<?php print $pgv_lang["name_of_fact"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["choice"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["accessible_by"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["delete"]; ?></td>
						</tr>
						<?php
          if (count($global_facts) > 0) {
            foreach($global_facts as $tag=>$value) {
	            foreach($value as $key=>$setting) {
						?>
						<tr class="<?php print $TEXT_DIRECTION; ?>">
							<td class="optionbox">
<?php
                if (isset($factarray[$tag])) print $factarray[$tag];
                else print $tag;
							?></td>
							<td class="optionbox">
<?php
              if ($key == "show") print $pgv_lang["fact_show"];
              if ($key == "details") print $pgv_lang["fact_details"];
							?></td>
						<td class="optionbox">
							<select size="1" name="v_global_facts[<?php print $tag; ?>][<?php print $key; ?> ]">
								<?php write_access_option($setting); ?>
							</select></td>
						<td class="optionbox">
							<input type="checkbox" name="v_global_facts_del[<?php print $tag; ?>][<?php print $key; ?>
							]" value="1" /></td>
						</tr>
						<?php
						}
	    			}
	    		}
		?>
						<tr class="<?php print $TEXT_DIRECTION; ?>">
							<td class="optionbox">
								<select size="1" name="v_new_global_facts_abbr">
								<?php
                print "<option value=\"\">".$pgv_lang["choose"]."</option>";
                foreach($factarray as $tag=>$label) {
                  print "<option";
                  print " value=\"";
                  print $tag;
                  print "\">";
                  print $tag . " - " . str_replace("<br />", " ", $label);
                  print "</option>";
                }
								?>
							</select></td>
							<td class="optionbox">
								<select size="1" name="v_new_global_facts_choice">
								<option value="details">
								<?php print $pgv_lang["fact_details"]; ?>
								</option>
								<option value="show">
								<?php print $pgv_lang["fact_show"]; ?>
								</option>
							</select></td>
							<td class="optionbox">
							<select size="1" name="v_new_global_facts_acess_option">
								<?php write_access_option(""); ?>
							</select></td>
						<td class="descriptionbox"></td>
						</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							<input type="submit" tabindex="<?php $i++; print $i?>" value="<?php print $pgv_lang["save_config"]?>
							" onclick="closeHelp();" /></td>
						<td></td>
					</tr>
					</table>
				</fieldset>
		</div>
		<div id="gedpriv5" name="<?php echo $pgv_lang["person_facts"]?>" class="indent" >
			<fieldset>
				<legend>
					<?php echo $pgv_lang["person_facts"]?>
				</legend>
					<table class="facts_table">
						<tr>
						<td class="descriptionbox">
							<?php print $pgv_lang["id"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["full_name"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["name_of_fact"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["choice"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["accessible_by"]; ?></td>
						<td class="descriptionbox">
							<?php print $pgv_lang["delete"]; ?></td>
						</tr>
						<?php
      if (count($person_facts) > 0) {
        foreach($person_facts as $id=>$value) {
            foreach($value as $tag=>$value1) {
	            foreach($value1 as $key=>$setting) {
						?>
						<tr class="<?php print $TEXT_DIRECTION; ?>">
							<td class="optionbox">
							<?php print $id; ?></td>
							<td class="optionbox">
							<?php
              search_ID_details($id, 2);
							?></td>
						<td class="optionbox">
<?php
            print $tag. " - ".$factarray[$tag];
							?></td>
						<td class="optionbox">
<?php
							if ($key == "show") print $pgv_lang["fact_show"];
							if ($key == "details") print $pgv_lang["fact_details"];
							?></td>
							<td class="optionbox">
							<select size="1" name="v_person_facts[<?php print $id; ?>][<?php print $tag; ?> ][
								<?php print $key; ?> ]">
								<?php write_access_option($setting); ?>
							</select></td>
						<td class="optionbox">
							<input type="checkbox" name="v_person_facts_del[<?php print $id; ?>][<?php print $tag; ?> ][
							<?php print $key; ?>
							]" value="1" /></td>
						</tr>
						<?php
							}
			}
					}
        }
					?>
						<tr class="<?php print $TEXT_DIRECTION; ?>">
							<td class="optionbox">
								<input type="text" class="pedigree_form" name="v_new_person_facts_access_ID" id="v_new_person_facts_access_ID" size="4" />
								<?php
                print_findindi_link("v_new_person_facts_access_ID","");
			 print_findfamily_link("v_new_person_facts_access_ID");
			 print_findsource_link("v_new_person_facts_access_ID");
							?></td>
						<td class="descriptionbox"></td>
							<td class="optionbox">
								<select size="1" name="v_new_person_facts_abbr">
								<?php
                foreach($factarray as $tag=>$label) {
                  print "<option";
                  print " value=\"";
                  print $tag;
                  print "\">";
                  print $tag . " - " . str_replace("<br />", " ", $label);
                  print "</option>";
                }
								?>
							</select></td>
							<td class="optionbox">
								<select size="1" name="v_new_person_facts_choice">
								<option value="details">
								<?php print $pgv_lang["fact_details"]; ?>
								</option>
								<option value="show">
								<?php print $pgv_lang["fact_show"]; ?>
								</option>
							</select></td>
							<td class="optionbox">
							<select size="1" name="v_new_person_facts_acess_option">
								<?php write_access_option(""); ?>
							</select></td>
						<td class="descriptionbox"></td>
						</tr>
				<tr>
						<td></td>
						<td></td>
						<td></td>
						<td>
							<input type="submit" tabindex="<?php $i++; print $i?>" value="<?php print $pgv_lang["save_config"]?>
							" onclick="closeHelp();" /></td>
						<td></td>
				</tr>
			</table>
				</fieldset>
		</div>
	</div>
		<table class="facts_table" border="0">
		<tr>
			<td class="topbottombar">
				<input type="submit" tabindex="<?php $i++; print $i?>" value="<?php print $pgv_lang["save_config"]?> " onclick="closeHelp();" /> &nbsp;&nbsp;
				<input type="reset" tabindex="<?php $i++; print $i?>" value="<?php print $pgv_lang["reset"]?>
				" /><br /></td>
		</tr>
	</table>
		</form>
<?php
print_footer();

?>
