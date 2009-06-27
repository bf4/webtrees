<?php
/**
* List branches by surname
*
* phpGedView: Genealogy Viewer
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
* @package PhpGedView
* @subpackage Lists
* @version $Id: autocomplete.php 5693 2009-06-13 00:43:07Z windmillway $
*/

require './config.php';
$surn = safe_GET('surn', '[^<>&%{};]*');
$surn = UTF8_strtoupper($surn);

//-- random surname
if ($surn=='*') {
	$surn = array_rand(get_indilist_surns("", "", false, true, PGV_GED_ID));
}

//-- form
print_header($surn);
if ($ENABLE_AUTOCOMPLETE) {
	require './js/autocomplete.js.htm';
}
?>
<form name="surnlist" id="surnlist" action="?">
	<table class="center facts_table width50">
		<tr>
			<td class="descriptionbox <?php print $TEXT_DIRECTION; ?>">
				<?php print_help_link("surname_help", "qm", "surname"); print $pgv_lang["surname"]; ?></td>
			<td class="optionbox <?php print $TEXT_DIRECTION; ?>">
				<input type="text" name="surn" id="SURN" value="<?php echo $surn?>" />
				<input type="submit" value="<?php echo $pgv_lang['view']; ?>" />
				<input type="submit" value="Random" onclick="document.surnlist.surn.value='*';" />
				</td>
		</tr>
	</table>
</form>
<?php

//-- results
if ($surn) {
	$icon = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["sfamily"]["small"]."\" alt=\"\" align=\"middle\" />";
	echo "<fieldset><legend>{$icon} {$surn}</legend>";
	$indis = get_indilist_indis($surn, "", "", false, false, PGV_GED_ID);
	$xrefs = array();
	foreach ($indis as $k=>$person) {
		$xrefs[] = $person->xref;
	}
	echo "<ol>";
	foreach ($indis as $k=>$person) {
		$famc = $person->getPrimaryChildFamily();
		if (!$famc || (array_search($famc->getHusbId(), $xrefs)===false && array_search($famc->getWifeId(), $xrefs)===false)) {
			print_fams($person);
		}
	}
	echo "</ol>";
	echo "</fieldset>";
}
print_footer();

function print_fams($person, $famid=null) {
	global $pgv_lang, $surn;
	// filter children (some may have a different surname)
	if ($famid) {
		list($surn1) = explode(", ", $person->getListName());
		if (stripos($surn1, $surn)===false && stripos($surn, $surn1)===false) {
			echo "<span title=\"".strip_tags($person->getFullName())."\">".$person->getSexImage()."...</span>";
			return;
		}
	}
	// current indi
	echo "<li>";
	$current = $person->getSexImage()."<a target=\"_blank\" title=\"{$person->xref}\" href=\"{$person->getLinkUrl()}\">{$person->getFullName()}</a> ".$person->getBirthDeathYears();
	if ($famid && $person->getChildFamilyPedigree($famid)) {
		$current = "<span class='red'>".$pgv_lang[$person->getChildFamilyPedigree($famid)]."</span> ".$current;
	}
	// spouses and children
	if (count($person->getSpouseFamilies())<1) {
		echo $current;
	}
	foreach ($person->getSpouseFamilies() as $f=>$family) {
		echo $current;
		$spouse = $family->getSpouse($person);
		if ($spouse) {
			if ($family->getMarriageYear()) {
				echo "&nbsp;&nbsp;<span class='details1' title=\"".strip_tags($family->getMarriageDate()->Display())."\">&times;".$family->getMarriageYear()."</span>&nbsp;";
			}
			list($surn2, $givn2) = explode(", ", $spouse->getListName());
			echo $spouse->getSexImage()."<a target=\"_blank\" title=\"{$family->xref}\" href=\"{$family->getLinkUrl()}\">{$givn2}</a> ",
				"<a title=\"{$surn2}\" href=\"?surn={$surn2}\">{$surn2}</a> ",
				$spouse->getBirthDeathYears();
		}
		echo "<ol>";
		foreach ($family->getChildren() as $c=>$child) {
			print_fams($child, $family->xref);
		}
		echo "</ol>";
	}
	echo "</li>";
}
?>