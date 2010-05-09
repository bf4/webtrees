<?php
/**
 * Edit Privacy Settings
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @author PGV Development Team
 * @package webtrees
 * @subpackage Privacy
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'edit_privacy.php');
require './includes/session.php';
require WT_ROOT.'includes/functions/functions_print_facts.php';
require WT_ROOT.'includes/functions/functions_edit.php';

if (empty($ged)) $ged = $GEDCOM;

if (!userGedcomAdmin(WT_USER_ID, $ged)) {
	header('Location: editgedcoms.php');
	exit;
}

switch (safe_POST('action')) {
case 'delete':
	WT_DB::prepare(
		"DELETE FROM {$TBLPREFIX}default_resn WHERE default_resn_id=?"
	)->execute(array(safe_POST('default_resn_id')));
	break;
case 'add_xref':
	WT_DB::prepare(
		"REPLACE INTO {$TBLPREFIX}default_resn (gedcom_id, xref, resn) VALUES (?, ?, ?)"
	)->execute(array(WT_GED_ID, safe_POST('xref'), safe_POST('resn')));
	break;
case 'add_tag_type':
	WT_DB::prepare(
		"REPLACE INTO {$TBLPREFIX}default_resn (gedcom_id, tag_type, resn) VALUES (?, ?, ?)"
	)->execute(array(WT_GED_ID, safe_POST('tag_type'), safe_POST('resn')));
	break;
case 'update':
	header('Location: editgedcoms.php');
	exit;
}

$PRIVACY_CONSTANTS=array(
	'none'        =>i18n::translate('Show to public'),
	'privacy'     =>i18n::translate('Show only to authenticated users'),
	'confidential'=>i18n::translate('Show only to admin users'),
	'hidden'      =>i18n::translate('Hide even from admin users')
);

$all_tags=WT_DB::prepare("SELECT fact_type FROM {$TBLPREFIX}fact WHERE fact_type NOT IN ('FAMC','FAMS','HUSB','WIFE','CHIL') UNION SELECT record_type FROM {$TBLPREFIX}record WHERE record_type NOT IN ('HEAD','TRLR')")->fetchOneColumn();
foreach ($all_tags as &$tag) {
	$tag=i18n::translate('%1$s [%2$s]', strip_tags(translate_fact($tag)), $tag);
}

uasort($all_tags, 'utf8_strcasecmp');

$PRIVACY_MODULE = get_privacy_file(WT_GED_ID);

print_header(i18n::translate('Edit privacy settings'));

if ($ENABLE_AUTOCOMPLETE) require WT_ROOT.'js/autocomplete.js.htm';
?>
<table class="facts_table <?php print $TEXT_DIRECTION; ?>">
	<tr>
		<td colspan="2" class="facts_label"><?php
			print "<h2>".i18n::translate('Edit GEDCOM privacy settings')." - ".PrintReady(strip_tags(get_gedcom_setting(get_id_from_gedcom($ged), 'title'))). "</h2>";
			print "(" . getLRM() . $PRIVACY_MODULE.")";
			print "<br /><br /><a href=\"editgedcoms.php\"><b>";
			print i18n::translate('Return to the GEDCOM management menu');
			print "</b></a><br /><br />"; ?>
		</td>
	</tr>
</table>
<?php
if ($action=="update") {
	$boolarray = array();
	$boolarray["yes"] = "true";
	$boolarray["no"] = "false";
	$boolarray[false] = "false";
	$boolarray[true] = "true";
	print "<table class=\"facts_table $TEXT_DIRECTION\">";
	print "<tr><td class=\"descriptionbox\">";
	print i18n::translate('Performing update.');
	print "<br />";
	$configtext = implode('', file("privacy.php"));
	print i18n::translate('Config file read.');
	print "</td></tr></table>\n";
	$configtext = preg_replace('/\$SHOW_DEAD_PEOPLE\s*=\s*.*;/', "\$SHOW_DEAD_PEOPLE = ".$_POST["v_SHOW_DEAD_PEOPLE"].";", $configtext);
	$configtext = preg_replace('/\$SHOW_LIVING_NAMES\s*=\s*.*;/', "\$SHOW_LIVING_NAMES = ".$_POST["v_SHOW_LIVING_NAMES"].";", $configtext);
	$configtext = preg_replace('/\$SHOW_SOURCES\s*=\s*.*;/', "\$SHOW_SOURCES = ".$_POST["v_SHOW_SOURCES"].";", $configtext);
	$configtext = preg_replace('/\$MAX_ALIVE_AGE\s*=\s*".*";/', "\$MAX_ALIVE_AGE = \"".$_POST["v_MAX_ALIVE_AGE"]."\";", $configtext);
	if ($MAX_ALIVE_AGE!=$_POST["v_MAX_ALIVE_AGE"]) reset_isdead(get_id_from_gedcom($ged));
	$configtext = preg_replace('/\$SHOW_MULTISITE_SEARCH\s*=\s*.*;/', "\$SHOW_MULTISITE_SEARCH = ".$_POST["v_SHOW_MULTISITE_SEARCH"].";", $configtext);
	$configtext = preg_replace('/\$ENABLE_CLIPPINGS_CART\s*=\s*.*;/', "\$ENABLE_CLIPPINGS_CART = ".$_POST["v_ENABLE_CLIPPINGS_CART"].";", $configtext);
	$configtext = preg_replace('/\$PRIVACY_BY_YEAR\s*=\s*.*;/', "\$PRIVACY_BY_YEAR = ".$boolarray[$_POST["v_PRIVACY_BY_YEAR"]].";", $configtext);
	$configtext = preg_replace('/\$PRIVACY_BY_RESN\s*=\s*.*;/', "\$PRIVACY_BY_RESN = ".$boolarray[$_POST["v_PRIVACY_BY_RESN"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_DEAD_PEOPLE\s*=\s*.*;/', "\$SHOW_DEAD_PEOPLE = ".$_POST["v_SHOW_DEAD_PEOPLE"].";", $configtext);
	$configtext = preg_replace('/\$USE_RELATIONSHIP_PRIVACY\s*=\s*.*;/', "\$USE_RELATIONSHIP_PRIVACY = ".$boolarray[$_POST["v_USE_RELATIONSHIP_PRIVACY"]].";", $configtext);
	$configtext = preg_replace('/\$MAX_RELATION_PATH_LENGTH\s*=\s*.*;/', "\$MAX_RELATION_PATH_LENGTH = \"".$_POST["v_MAX_RELATION_PATH_LENGTH"]."\";", $configtext);
	$configtext = preg_replace('/\$CHECK_MARRIAGE_RELATIONS\s*=\s*.*;/', "\$CHECK_MARRIAGE_RELATIONS = ".$boolarray[$_POST["v_CHECK_MARRIAGE_RELATIONS"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_PRIVATE_RELATIONSHIPS\s*=\s*.*;/', "\$SHOW_PRIVATE_RELATIONSHIPS = ".$boolarray[$_POST["v_SHOW_PRIVATE_RELATIONSHIPS"]].";", $configtext);

	//-- Update the "Person Privacy" section
	$configtext_beg = substr($configtext, 0, strpos($configtext, "//-- start person privacy --//"));
	$configtext_end = substr($configtext, strpos($configtext, "//-- end person privacy --//"));
	$person_privacy_text = "//-- start person privacy --//\n\$person_privacy = array();\n";
	if (!isset($v_person_privacy) || !is_array($v_person_privacy)) $v_person_privacy = array();
	foreach ($person_privacy as $key=>$value) {
		if (isset($v_person_privacy_del[$key]) || $key==$v_new_person_privacy_access_ID) continue;
		if (isset($v_person_privacy[$key])) $person_privacy_text .= "\$person_privacy['$key'] = ".$v_person_privacy[$key].";\n";
		else $person_privacy_text .= "\$person_privacy['$key'] = ".$PRIVACY_CONSTANTS[$value].";\n";
	}
	if ($v_new_person_privacy_access_ID && $v_new_person_privacy_access_option) {
		$gedobj = new GedcomRecord(find_gedcom_record($v_new_person_privacy_access_ID, WT_GED_ID));
		$v_new_person_privacy_access_ID = $gedobj->getXref();
		if ($v_new_person_privacy_access_ID) $person_privacy_text .= "\$person_privacy['$v_new_person_privacy_access_ID'] = ".$v_new_person_privacy_access_option.";\n";
	}
	$configtext = $configtext_beg . $person_privacy_text . $configtext_end;

	//-- Update the "User Privacy" section
	$configtext_beg = substr($configtext, 0, strpos($configtext, "//-- start user privacy --//"));
	$configtext_end = substr($configtext, strpos($configtext, "//-- end user privacy --//"));
	$person_privacy_text = "//-- start user privacy --//\n\$user_privacy = array();\n";
	if (!isset($v_user_privacy) || !is_array($v_user_privacy)) $v_user_privacy = array();
	foreach ($user_privacy as $key=>$value) {
		foreach ($value as $id=>$setting) {
			if (isset($v_user_privacy_del[$key][$id]) || ($key==$v_new_user_privacy_username && $id==$v_new_user_privacy_access_ID)) continue;
			if (isset($v_user_privacy[$key][$id])) $person_privacy_text .= "\$user_privacy['$key']['$id'] = ".$v_user_privacy[$key][$id].";\n";
			else $person_privacy_text .= "\$user_privacy['$key']['$id'] = ".$PRIVACY_CONSTANTS[$setting].";\n";
		}
	}
	if ($v_new_user_privacy_username && $v_new_user_privacy_access_ID && $v_new_user_privacy_access_option) {
		$gedobj = new GedcomRecord(find_gedcom_record($v_new_user_privacy_access_ID, WT_GED_ID));
		$v_new_user_privacy_access_ID = $gedobj->getXref();
		if ($v_new_user_privacy_access_ID) $person_privacy_text .= "\$user_privacy['$v_new_user_privacy_username']['$v_new_user_privacy_access_ID'] = ".$v_new_user_privacy_access_option.";\n";
	}
	$configtext = $configtext_beg . $person_privacy_text . $configtext_end;

	//-- Update the "Global Facts Privacy" section
	$configtext_beg = substr($configtext, 0, strpos($configtext, "//-- start global facts privacy --//"));
	$configtext_end = substr($configtext, strpos($configtext, "//-- end global facts privacy --//"));
	$person_privacy_text = "//-- start global facts privacy --//\n\$global_facts = array();\n";
	if (!isset($v_global_facts) || !is_array($v_global_facts)) $v_global_facts = array();
	foreach ($global_facts as $tag=>$value) {
		foreach ($value as $key=>$setting) {
			if (isset($v_global_facts_del[$tag][$key]) || ($tag==$v_new_global_facts_abbr && $key==$v_new_global_facts_choice)) continue;
			if (isset($v_global_facts[$tag][$key])) $person_privacy_text .= "\$global_facts['$tag']['$key'] = ".$v_global_facts[$tag][$key].";\n";
			else $person_privacy_text .= "\$global_facts['$tag']['$key'] = ".$PRIVACY_CONSTANTS[$setting].";\n";
		}
	}
	if ($v_new_global_facts_abbr && $v_new_global_facts_choice && $v_new_global_facts_access_option) {
		$person_privacy_text .= "\$global_facts['$v_new_global_facts_abbr']['$v_new_global_facts_choice'] = ".$v_new_global_facts_access_option.";\n";
	}
	$configtext = $configtext_beg . $person_privacy_text . $configtext_end;

	//-- Update the "Person Facts Privacy" section
	$configtext_beg = substr($configtext, 0, strpos($configtext, "//-- start person facts privacy --//"));
	$configtext_end = substr($configtext, strpos($configtext, "//-- end person facts privacy --//"));
	$person_privacy_text = "//-- start person facts privacy --//\n\$person_facts = array();\n";
	if (!isset($v_person_facts) || !is_array($v_person_facts)) $v_person_facts = array();
	foreach ($person_facts as $id=>$value) {
		foreach ($value as $tag=>$value1) {
			foreach ($value1 as $key=>$setting) {
				if (isset($v_person_facts_del[$id][$tag][$key]) || ($id==$v_new_person_facts_access_ID && $tag==$v_new_person_facts_abbr && $key==$v_new_person_facts_choice)) continue;
				if (isset($v_person_facts[$id][$tag][$key])) $person_privacy_text .= "\$person_facts['$id']['$tag']['$key'] = ".$v_person_facts[$id][$tag][$key].";\n";
				else $person_privacy_text .= "\$person_facts['$id']['$tag']['$key'] = ".$PRIVACY_CONSTANTS[$setting].";\n";
			}
		}
	}
	if ($v_new_person_facts_access_ID && $v_new_person_facts_abbr && $v_new_global_facts_choice && $v_new_global_facts_access_option) {
		$gedobj = new GedcomRecord(find_gedcom_record($v_new_person_facts_access_ID, WT_GED_ID));
		$v_new_person_facts_access_ID = $gedobj->getXref();
		if ($v_new_person_facts_access_ID) $person_privacy_text .= "\$person_facts['$v_new_person_facts_access_ID']['$v_new_person_facts_abbr']['$v_new_person_facts_choice'] = ".$v_new_person_facts_access_option.";\n";
	}
	$configtext = $configtext_beg . $person_privacy_text . $configtext_end;

	$PRIVACY_MODULE = $INDEX_DIRECTORY.$GEDCOM."_priv.php";
	$fp = @fopen($PRIVACY_MODULE, "wb");
	if (!$fp) {
		print "<span class=\"error\">".i18n::translate('E R R O R !!!<br />Could not write to file <i>%s</i>.  Please check it for proper Write permissions.', $PRIVACY_MODULE)."<br /></span>\n";
	} else {
		fwrite($fp, $configtext);
		fclose($fp);
	}
	// NOTE: load the new variables
	require $INDEX_DIRECTORY.$GEDCOM.'_priv.php';
	$logline = AddToLog("Privacy file $PRIVACY_MODULE updated", 'config');
 	$gedcomprivname = $GEDCOM."_priv.php";

 	//-- delete the cache files for the Home Page blocks
	require_once WT_ROOT.'includes/index_cache.php';
	clearCache();
}
?>
<script language="JavaScript" type="text/javascript">
<!--
		var pastefield;
	function paste_id(value) {
		pastefield.value=value;
	}
//-->
</script>

<form name="editprivacyform" method="post" action="edit_privacy.php">
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="ged" value="<?php print $GEDCOM;?>" />

	<table class="facts_table">
		<tr>
			<td class="topbottombar <?php print $TEXT_DIRECTION; ?>" colspan="2">
				<?php echo i18n::translate('General Privacy settings'); ?>
			</td>
		</tr>
		<tr>
			<td class="descriptionbox wrap width20 <?php print $TEXT_DIRECTION; ?>">
				<?php echo i18n::translate('Show dead people'), help_link('SHOW_DEAD_PEOPLE'); ?>
			</td>
			<td class="optionbox">
				<select size="1" name="v_SHOW_DEAD_PEOPLE"><?php write_access_option($SHOW_DEAD_PEOPLE); ?></select>
			</td>
		</tr>
		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Show living names'), help_link('SHOW_LIVING_NAMES'); ?>
			</td>
			<td class="optionbox">
				<select size="1" name="v_SHOW_LIVING_NAMES"><?php write_access_option($SHOW_LIVING_NAMES); ?></select>
			</td>
		</tr>
		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Show sources'), help_link('SHOW_SOURCES'); ?>
			</td>
			<td class="optionbox">
				<select size="1" name="v_SHOW_SOURCES"><?php write_access_option($SHOW_SOURCES); ?></select>
			</td>
		</tr>
		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Enable Clippings Cart'), help_link('ENABLE_CLIPPINGS_CART'); ?>
			</td>
			<td class="optionbox">
				<select size="1" name="v_ENABLE_CLIPPINGS_CART"><?php write_access_option($ENABLE_CLIPPINGS_CART); ?></select>
		</td>
		</tr>

		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Show Multi-Site Search'), help_link('SHOW_MULTISITE_SEARCH'); ?>
			</td>
			<td class="optionbox">
				<select size="1" name="v_SHOW_MULTISITE_SEARCH"><?php write_access_option($SHOW_MULTISITE_SEARCH); ?></select>
			</td>
		</tr>

		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Limit Privacy by age of event'), help_link('PRIVACY_BY_YEAR'); ?>
			</td>
			<td class="optionbox">
				<?php echo edit_field_yes_no('v_PRIVACY_BY_YEAR', $PRIVACY_BY_YEAR); ?>
			</td>
		</tr>

		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Use GEDCOM (RESN) Privacy restriction'), help_link('PRIVACY_BY_RESN'); ?>
			</td>
			<td class="optionbox">
				<?php echo edit_field_yes_no('v_PRIVACY_BY_RESN', $PRIVACY_BY_RESN); ?>
			</td>
		</tr>

		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Show private relationships'), help_link('SHOW_PRIVATE_RELATIONSHIPS'); ?>
			</td>
			<td class="optionbox">
				<?php echo edit_field_yes_no('v_SHOW_PRIVATE_RELATIONSHIPS', $SHOW_PRIVATE_RELATIONSHIPS); ?>
			</td>
		</tr>

		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Use relationship privacy'), help_link('USE_RELATIONSHIP_PRIVACY'); ?>
			</td>
			<td class="optionbox">
				<?php echo edit_field_yes_no('v_USE_RELATIONSHIP_PRIVACY', $USE_RELATIONSHIP_PRIVACY); ?>
			</td>
		</tr>

		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Max. relation path length'), help_link('MAX_RELATION_PATH_LENGTH'); ?>
			</td>
			<td class="optionbox">
				<select size="1" name="v_MAX_RELATION_PATH_LENGTH"><?php
				for ($y = 1; $y <= 10; $y++) {
					print "<option";
					if ($MAX_RELATION_PATH_LENGTH == $y) print " selected=\"selected\"";
					print ">";
					print $y;
					print "</option>";
				}
				?></select>
			</td>
		</tr>

		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Check marriage relations'), help_link('CHECK_MARRIAGE_RELATIONS'); ?>
			</td>
			<td class="optionbox">
				<?php echo edit_field_yes_no('v_CHECK_MARRIAGE_RELATIONS', $CHECK_MARRIAGE_RELATIONS); ?>
			</td>
		</tr>

		<tr>
			<td class="descriptionbox wrap">
				<?php echo i18n::translate('Age at which to assume a person is dead'), help_link('MAX_ALIVE_AGE'); ?>
			</td>
			<td class="optionbox">
				<input type="text" name="v_MAX_ALIVE_AGE" value="<?php print $MAX_ALIVE_AGE; ?>" size="5" />
			</td>
		</tr>
		<tr>
			<td class="topbottombar <?php print $TEXT_DIRECTION; ?>" colspan="2">
				<input type="submit" value="<?php echo i18n::translate('Save'); ?>" />
			</td>
		</tr>
	</table>
	</form>
	<br />
	<table class="facts_table">
		<tr>
			<td class="topbottombar <?php print $TEXT_DIRECTION; ?>" colspan="3">
				<?php echo i18n::translate('Default privacy restrictions - these apply to records and facts that do not contain an explicit restriction'); ?>
			</td>
		</tr>
<?php
$rows=WT_DB::prepare(
	"SELECT default_resn_id, tag_type, xref, resn".
	" FROM {$TBLPREFIX}default_resn".
	" WHERE gedcom_id=?".
	" ORDER BY xref, tag_type"
)->execute(array(WT_GED_ID))->fetchAll();
foreach ($rows as $row) {
	echo '<form method="post" action="', WT_SCRIPT_NAME, '"><tr><td class="optionbox" width="*">';
	echo '<input type="hidden" name="action" value="delete">';
	echo '<input type="hidden" name="default_resn_id" value="', $row->default_resn_id, '">';
	if ($row->xref) {
		// I18N: "Record ID I1234 (John DOE)
		$record=GedcomRecord::getInstance($row->xref);
		if ($record) {
			$name=$record->getFullName();
		} else {
			$name=i18n::translate('this record does not exist');
		}
		echo i18n::translate('Record ID %1$s (%2$s)', $row->xref, $name);
	} else {
		// I18N: "Record type SOUR (Source)
		echo i18n::translate('Record type %1$s (%2$s)', $row->tag_type, translate_fact($row->tag_type));
	}
	echo '</td><td class="optionbox" width="1">';
	echo $PRIVACY_CONSTANTS[$row->resn];
	echo '</td><td class="optionbox" width="1">';
	echo '<input type="submit" value="', i18n::translate('Delete'), '" />';
	echo '</td></tr></form>';
}
echo '<form method="post" action="', WT_SCRIPT_NAME, '"><tr><td class="optionbox" width="*">';
echo '<input type="hidden" name="action" value="add_xref">';
echo '<input type="text" class="pedigree_form" name="xref" id="xref" size="6" />';
print_findindi_link("xref","");
print_findfamily_link("xref");
print_findsource_link("xref");
print_findrepository_link("xref");
print_findmedia_link("xref", "1media");
echo '</td><td class="optionbox" width="1">';
echo select_edit_control('resn', $PRIVACY_CONSTANTS, null, 'privacy', null);
echo '</td><td class="optionbox" width="1">';
echo '<input type="submit" value="', i18n::translate('Add'), '" />';
echo '</td></tr></form>';
unset($PRIVACY_CONSTANTS['none']); // The fact default is 'none' - do not need to select it.
echo '<form method="post" action="', WT_SCRIPT_NAME, '"><tr><td class="optionbox" width="*">';
echo '<input type="hidden" name="action" value="add_tag_type">';
echo select_edit_control('tag_type', $all_tags, null, null, null);
echo '</td><td class="optionbox" width="1">';
echo select_edit_control('resn', $PRIVACY_CONSTANTS, null, 'privacy', null);
echo '</td><td class="optionbox" width="1">';
echo '<input type="submit" value="', i18n::translate('Add'), '" />';
echo '</td></tr></form>';
echo '</table>';
print_footer();
exit;
