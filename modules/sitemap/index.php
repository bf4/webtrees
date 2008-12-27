<?php
/**
 * Display a diff between two language files to help in translating.
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
 * @subpackage Languages
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

loadLangFile("pgv_confighelp, sitemap:lang, sitemap:help_text");

//-- make sure that they have admin status before they can use this page
//-- otherwise have them login again
$uname = getUserName();
if (empty($uname)) {
	header("Location: login.php?url=module.php?mod=sitemap");
	exit;
}

$action				= safe_REQUEST($_REQUEST, 'action', PGV_REGEX_XREF);
$welcome			= safe_REQUEST($_REQUEST, 'welcome', PGV_REGEX_XREF);
$gedcom_name		= safe_REQUEST($_REQUEST, 'gedcom_name');
$filename			= safe_REQUEST($_REQUEST, 'filename');
$index				= safe_REQUEST($_REQUEST, 'index');
$welcome_priority	= safe_REQUEST($_REQUEST, 'welcome_priority', PGV_REGEX_XREF);
$welcome_update		= safe_REQUEST($_REQUEST, 'welcome_update', PGV_REGEX_XREF);
$indi_rec			= safe_REQUEST($_REQUEST, 'indi_rec', PGV_REGEX_XREF);
$indirec_priority	= safe_REQUEST($_REQUEST, 'indirec_priority', PGV_REGEX_XREF);
$indirec_update		= safe_REQUEST($_REQUEST, 'indirec_update', PGV_REGEX_XREF);
$indi_lists			= safe_REQUEST($_REQUEST, 'indi_lists', PGV_REGEX_XREF);
$indilist_priority	= safe_REQUEST($_REQUEST, 'indilist_priority', PGV_REGEX_XREF);
$indilist_update	= safe_REQUEST($_REQUEST, 'indilist_update', PGV_REGEX_XREF);
$fam_rec			= safe_REQUEST($_REQUEST, 'fam_rec', PGV_REGEX_XREF);
$famrec_priority	= safe_REQUEST($_REQUEST, 'famrec_priority', PGV_REGEX_XREF);
$famrec_update		= safe_REQUEST($_REQUEST, 'famrec_update', PGV_REGEX_XREF);
$fam_lists			= safe_REQUEST($_REQUEST, 'fam_lists', PGV_REGEX_XREF);
$famlist_priority	= safe_REQUEST($_REQUEST, 'famlist_priority', PGV_REGEX_XREF);
$famlist_update		= safe_REQUEST($_REQUEST, 'famlist_update', PGV_REGEX_XREF);

if ($action=="sendFiles") {
	global $filename, $gedcom_name;
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$filename.'"');

	echo "<?xml version='1.0' encoding='UTF-8'?>\n";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"".$SERVER_URL."modules/sitemap/gss.xsl\"?>\n";
	echo "	<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\"\n";
	echo "	xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
	echo "	xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.84\n";
	echo "	http://www.google.com/schemas/sitemap/0.84/sitemap.xsd\">\n";

	if (isset($welcome)) {
		echo "   <url>\n";
		echo "	  <loc>".$SERVER_URL."index.php?command=gedcom&amp;ged=".urlencode($gedcom_name)."</loc>\n";
		echo "	  <changefreq>".$welcome_update."</changefreq>\n";
		echo "	  <priority>0.".$welcome_priority."</priority>\n";
		echo "   </url>\n";
	}

	if (isset($indi_rec)) {
		$sql = "SELECT i_id,i_gedcom FROM ".$TBLPREFIX."individuals WHERE i_file='".$index."'";
		$res = dbquery($sql);
		while ($row =& $res->fetchRow()) {
			echo "   <url>\n";
			echo "	  <loc>".$SERVER_URL."individual.php?pid=".$row[0]."&amp;ged=".urlencode($gedcom_name)."</loc>\n";
			$arec = get_sub_record(1, "1 CHAN", $row[1], 1);
			if (!empty($arec) && preg_match("/2 DATE (.*)/", $arec, $datematch))
			  echo "	  <lastmod>".date("Y-m-d", strtotime($datematch[1]))."</lastmod>\n";
			echo "	  <changefreq>".$indirec_update."</changefreq>\n";
			echo "	  <priority>0.".$indirec_priority."</priority>\n";
			echo "   </url>\n";
		}
		$res->free();
	}

	if (isset($fam_rec)) {
		$sql = "SELECT f_id,f_gedcom FROM ".$TBLPREFIX."families WHERE f_file='".$index."'";
		$res = dbquery($sql);
		while ($row =& $res->fetchRow()) {
			echo "   <url>\n";
			echo "	  <loc>".$SERVER_URL."family.php?famid=".$row[0]."&amp;ged=".urlencode($gedcom_name)."</loc>\n";
			$arec = get_sub_record(1, "1 CHAN", $row[1], 1);
			if (!empty($arec) && preg_match("/2 DATE (.*)/", $arec, $datematch))
			  echo "	  <lastmod>".date("Y-m-d", strtotime($datematch[1]))."</lastmod>\n";
			echo "	  <changefreq>".$famrec_update."</changefreq>\n";
			echo "	  <priority>0.".$famrec_priority."</priority>\n";
			echo "   </url>\n";
		}
		$res->free();
	}

	if (isset($fam_lists)) {
		foreach(get_indilist_salpha($SHOW_MARRIED_NAMES, true, PGV_GED_ID) as $letter) {
			if ($letter!='@') {
				echo "   <url>\n";
				echo "	  <loc>".$SERVER_URL."famlist.php?alpha=".urlencode($letter)."&amp;ged=".urlencode($gedcom_name)."</loc>\n";
				echo "	  <changefreq>".$famlist_update."</changefreq>\n";
				echo "	  <priority>0.".$famlist_priority."</priority>\n";
				echo "   </url>\n";
			}
		}
	}

	if (isset($indi_lists)) {
		foreach (get_indilist_salpha($SHOW_MARRIED_NAMES, false, PGV_GED_ID) as $letter) {
			if ($letter!='@') {
				echo "   <url>\n";
				echo "	  <loc>".$SERVER_URL."indilist.php?alpha=".urlencode($letter)."&amp;ged=".urlencode($gedcom_name)."</loc>\n";
				echo "	  <changefreq>".$indilist_update."</changefreq>\n";
				echo "	  <priority>0.".$indilist_priority."</priority>\n";
				echo "   </url>\n";
			}
		}
	}
	echo "</urlset>";
	exit;
}

if ($action=="sendIndex") {
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="SitemapIndex.xml"');

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"".$SERVER_URL."modules/sitemap/gss.xsl\"?>\n";
	echo "   <sitemapindex xmlns=\"http://www.google.com/schemas/sitemap/0.84\">\n";

	if (isset($filenames)) {
		foreach($filenames as $ged_index=>$ged_name) {
			$xml_name = preg_replace("/\.ged/",".xml", $ged_name);
			echo "   <sitemap>\n";
			echo "	  <loc>".$SERVER_URL."SM_".$xml_name."</loc>\n";
			echo "	  <lastmod>".date("Y-m-d")."</lastmod>\n ";
			echo "   </sitemap>\n";
		}
	}
	echo "   </sitemapindex>\n";
	exit;
}

print_header($pgv_lang["generate_sitemap"]);

?>
<script language="JavaScript" type="text/javascript">
<!--
var helpWin;
function helpPopup(which) {
	if ((!helpWin)||(helpWin.closed)) helpWin = window.open('module.php?mod=sitemap&pgvaction=sitemap_help&help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');
	else helpWin.location = 'modules/sitemap/sitemap_help.php?help='+which;
	return false;
}

function getHelp(which) {
	if ((helpWin)&&(!helpWin.closed)) helpWin.location='module.php?mod=sitemap&pgvaction=sitemap_help&help='+which;
}
//-->
</script>
<?php

if ($action=="generate") {
	echo "<h3>";
	print_help_link("SITEMAP_help", "qm", "SITEMAP");
	echo $pgv_lang["generate_sitemap"];
	echo "</h3>\n";
	echo "<table class=\"facts_table\">\n";
	echo "   <tr><td class=\"topbottombar\">".$pgv_lang["selected_item"]."</td></tr>\n";
	if (isset($_POST["welcome_page"])) echo "<tr><td class=\"optionbox\">".$pgv_lang["welcome_page"]."</td></tr>\n";
	if (isset($_POST["indi_recs"])) echo "<tr><td class=\"optionbox\">".$pgv_lang["sm_indi_info"]."</td></tr>\n";
	if (isset($_POST["indi_list"])) echo "<tr><td class=\"optionbox\">".$pgv_lang["sm_individual_list"]."</td></tr>\n";
	if (isset($_POST["fam_recs"])) echo "<tr><td class=\"optionbox\">".$pgv_lang["sm_family_info"]."</td></tr>\n";
	if (isset($_POST["fam_list"])) echo "<tr><td class=\"optionbox\">".$pgv_lang["sm_family_list"]."</td></tr>\n";
//  if (isset($_POST["GEDCOM_Privacy"])) echo "<tr><td class=\"optionbox\">".$pgv_lang["gedcoms_privacy"]."</td></tr>\n";

	echo "   <tr><td class=\"topbottombar\">".$pgv_lang["gedcoms_selected"]."</td></tr>\n";
	foreach($GEDCOMS as $ged_index=>$ged_rec) {
		if (isset($_POST["GEDCOM_".$ged_rec["id"]])) echo "<tr><td class=\"optionbox\">".$ged_rec["title"]."</td></tr>\n";
	}

	echo "   <tr><td class=\"topbottombar\">".$pgv_lang["sitemaps_generated"]."</td></tr>\n";
	$filecounter = 0;
	foreach($GEDCOMS as $ged_index=>$ged_rec) {
		if (isset($_POST["GEDCOM_".$ged_rec["id"]])) {
			$filecounter += 1;
			$sitemapFilename = "SM_".preg_replace("/\.ged/",".xml",$ged_rec["gedcom"]);
			echo "<tr><td class=\"optionbox\"><a href=\"module.php?mod=sitemap&action=sendFiles&index=".$ged_rec["id"]."&gedcom_name=".$ged_rec["gedcom"]."&filename=".$sitemapFilename;
			if (isset($_POST["welcome_page"])) echo "&welcome=true&welcome_priority=".$welcome_priority."&welcome_update=".$welcome_update;
			if (isset($_POST["indi_recs"])) echo "&indi_rec=true&indirec_priority=".$indirec_priority."&indirec_update=".$indirec_update;
			if (isset($_POST["indi_list"])) echo "&indi_lists=true&indilist_priority=".$indilist_priority."&indilist_update=".$indilist_update;
			if (isset($_POST["fam_recs"])) echo "&fam_rec=true&famrec_priority=".$famrec_priority."&famrec_update=".$famrec_update;
			if (isset($_POST["fam_list"])) echo "&fam_lists=true&famlist_priority=".$famlist_priority."&famlist_update=".$famlist_update;
//		  if (isset($_POST["GEDCOM_Privacy"])) echo "&no_private_links=true";
			echo "\">".$sitemapFilename."</a></td></tr>\n";
		}
	}
	if ($filecounter > 1) {
		echo "<tr><td class=\"optionbox\"><a href=\"module.php?mod=sitemap&action=sendIndex";
		foreach($GEDCOMS as $ged_index=>$ged_rec) {
			if (isset($_POST["GEDCOM_".$ged_rec["id"]])) {
				echo "&filenames[".$ged_rec["id"]."]=".$ged_rec["gedcom"];
			}
		}
		echo "\">SitemapIndex.xml</a></td></tr>\n";
	}
	echo "   <tr><td class=\"topbottombar\">".$pgv_lang["sitemaps_placement"]."</td></tr>\n";
	echo "</table>\n";
	echo "<br />\n";
}

if ($action=="") {
		$i = 0;
?>

<h3><?php print_help_link("SITEMAP_help", "qm", "SITEMAP"); echo $pgv_lang["generate_sitemap"]?></h3>

<form method="post" enctype="multipart/form-data" id="sitemap" name="sitemap" action="module.php?mod=sitemap">
	<input type="hidden" name="action" value="generate" />
	<table class="facts_table width100">
		<tr>
			<td class="descriptionbox wrap width50"><?php print_help_link("SM_GEDCOM_SELECT_help", "qm", "SM_GEDCOM_SELECT"); echo $pgv_lang["gedcoms_selected"];?></td>
			<td class="optionbox" colspan="3">
<?php
	foreach($GEDCOMS as $ged_index=>$ged_rec) {
		echo "				<input type=\"checkbox\" name=\"GEDCOM_".$ged_rec["id"]."\" value=\"".$ged_rec["id"]."\" tabindex=\"".$i++."\" checked>".$ged_rec["title"]."<br />\n";
	}
?>
			</td>
		</tr>
		<tr>
			<td class="descriptionbox wrap width50" rowspan="6">
				<?php print_help_link("SM_ITEM_SELECT_help", "qm", "SM_ITEM_SELECT"); echo $pgv_lang["selected_item"];?>
			</td>
			<td class="topbottombar"><?php echo $pgv_lang["sm_item"];?></td>
			<td class="topbottombar"><?php echo $pgv_lang["sm_priority"];?></td>
			<td class="topbottombar"><?php echo $pgv_lang["sm_updates"];?></td>
		</tr>
		<tr>
			<td class="optionbox">
				<input type="checkbox" name="welcome_page" tabindex="<?php $i++; echo $i?>" checked><?php echo $pgv_lang["welcome_page"];?>
			</td>
			<td class="optionbox">
				<select name="welcome_priority" tabindex="<?php $i++; echo $i?>">
					<option value="1">0.1</option>
					<option value="2">0.2</option>
					<option value="3">0.3</option>
					<option value="4">0.4</option>
					<option value="5">0.5</option>
					<option value="6">0.6</option>
					<option value="7" selected="selected">0.7</option>
					<option value="8">0.8</option>
					<option value="9">0.9</option>
				</select>
			</td>
			<td class="optionbox">
				<select name="welcome_update" tabindex="<?php $i++; echo $i?>">
					<option value="always"><?php echo $pgv_lang["sm_always"];?></option>
					<option value="hourly"><?php echo $pgv_lang["sm_hourly"];?></option>
					<option value="daily"><?php echo $pgv_lang["sm_daily"];?></option>
					<option value="weekly"><?php echo $pgv_lang["sm_weekly"];?></option>
					<option value="monthly" selected="selected"><?php echo $pgv_lang["sm_monthly"];?></option>
					<option value="yearly"><?php echo $pgv_lang["sm_yearly"];?></option>
					<option value="never"><?php echo $pgv_lang["sm_never"];?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="optionbox"><input type="checkbox" name="indi_recs" tabindex="<?php $i++; echo $i?>" checked><?php echo $pgv_lang["sm_indi_info"];?></td>
			<td class="optionbox">
				<select name="indirec_priority" tabindex="<?php $i++; echo $i?>">
					<option value="1">0.1</option>
					<option value="2">0.2</option>
					<option value="3">0.3</option>
					<option value="4">0.4</option>
					<option value="5" selected="selected">0.5</option>
					<option value="6">0.6</option>
					<option value="7">0.7</option>
					<option value="8">0.8</option>
					<option value="9">0.9</option>
				</select>
			</td>
			<td class="optionbox">
				<select name="indirec_update" tabindex="<?php $i++; echo $i?>">
					<option value="always"><?php echo $pgv_lang["sm_always"];?></option>
					<option value="hourly"><?php echo $pgv_lang["sm_hourly"];?></option>
					<option value="daily"><?php echo $pgv_lang["sm_daily"];?></option>
					<option value="weekly"><?php echo $pgv_lang["sm_weekly"];?></option>
					<option value="monthly" selected="selected"><?php echo $pgv_lang["sm_monthly"];?></option>
					<option value="yearly"><?php echo $pgv_lang["sm_yearly"];?></option>
					<option value="never"><?php echo $pgv_lang["sm_never"];?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="optionbox"><input type="checkbox" name="indi_list" tabindex="<?php $i++; echo $i?>"><?php echo $pgv_lang["sm_individual_list"];?></td>
			<td class="optionbox">
				<select name="indilist_priority" tabindex="<?php $i++; echo $i?>">
					<option value="1">0.1</option>
					<option value="2">0.2</option>
					<option value="3" selected="selected">0.3</option>
					<option value="4">0.4</option>
					<option value="5">0.5</option>
					<option value="6">0.6</option>
					<option value="7">0.7</option>
					<option value="8">0.8</option>
					<option value="9">0.9</option>
				</select>
			</td>
			<td class="optionbox">
				<select name="indilist_update" tabindex="<?php $i++; echo $i?>">
					<option value="always"><?php echo $pgv_lang["sm_always"];?></option>
					<option value="hourly"><?php echo $pgv_lang["sm_hourly"];?></option>
					<option value="daily"><?php echo $pgv_lang["sm_daily"];?></option>
					<option value="weekly"><?php echo $pgv_lang["sm_weekly"];?></option>
					<option value="monthly" selected="selected"><?php echo $pgv_lang["sm_monthly"];?></option>
					<option value="yearly"><?php echo $pgv_lang["sm_yearly"];?></option>
					<option value="never"><?php echo $pgv_lang["sm_never"];?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="optionbox"><input type="checkbox" name="fam_recs" tabindex="<?php $i++; echo $i?>" checked><?php echo $pgv_lang["sm_family_info"];?></td>
			<td class="optionbox">
				<select name="famrec_priority" tabindex="<?php $i++; echo $i?>">
					<option value="1">0.1</option>
					<option value="2">0.2</option>
					<option value="3">0.3</option>
					<option value="4">0.4</option>
					<option value="5" selected="selected">0.5</option>
					<option value="6">0.6</option>
					<option value="7">0.7</option>
					<option value="8">0.8</option>
					<option value="9">0.9</option>
				</select>
			</td>
			<td class="optionbox">
				<select name="famrec_update" tabindex="<?php $i++; echo $i?>">
					<option value="always"><?php echo $pgv_lang["sm_always"];?></option>
					<option value="hourly"><?php echo $pgv_lang["sm_hourly"];?></option>
					<option value="daily"><?php echo $pgv_lang["sm_daily"];?></option>
					<option value="weekly"><?php echo $pgv_lang["sm_weekly"];?></option>
					<option value="monthly" selected="selected"><?php echo $pgv_lang["sm_monthly"];?></option>
					<option value="yearly"><?php echo $pgv_lang["sm_yearly"];?></option>
					<option value="never"><?php echo $pgv_lang["sm_never"];?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="optionbox"><input type="checkbox" name="fam_list" tabindex="<?php $i++; echo $i?>"><?php echo $pgv_lang["sm_family_list"];?></td>
			<td class="optionbox">
				<select name="famlist_priority" tabindex="<?php $i++; echo $i?>">
					<option value="1">0.1</option>
					<option value="2">0.2</option>
					<option value="3" selected="selected">0.3</option>
					<option value="4">0.4</option>
					<option value="5">0.5</option>
					<option value="6">0.6</option>
					<option value="7">0.7</option>
					<option value="8">0.8</option>
					<option value="9">0.9</option>
				</select>
			</td>
			<td class="optionbox">
				<select name="famlist_update" tabindex="<?php $i++; echo $i?>">
					<option value="always"><?php echo $pgv_lang["sm_always"];?></option>
					<option value="hourly"><?php echo $pgv_lang["sm_hourly"];?></option>
					<option value="daily"><?php echo $pgv_lang["sm_daily"];?></option>
					<option value="weekly"><?php echo $pgv_lang["sm_weekly"];?></option>
					<option value="monthly" selected="selected"><?php echo $pgv_lang["sm_monthly"];?></option>
					<option value="yearly"><?php echo $pgv_lang["sm_yearly"];?></option>
					<option value="never"><?php echo $pgv_lang["sm_never"];?></option>
				</select>
			</td>
		</tr>
	</table>
	<center><input id="savebutton" type="submit" value="<?php echo $pgv_lang["sm_generate"];?>" /></center><br /><br />
</form>

<?php
}
print_footer();
?>