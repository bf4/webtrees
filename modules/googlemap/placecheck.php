<?php
/**
 * Check a GEDCOM file for compliance with the 5.5.1 specification
 * and other common errors.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 Nigel Osborne nigelo@users.sourceforge.net
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
 * @author Nigel Osborne 26 Mar 2007
 * @package PhpGedView
 * @version $Id$ 3.0 09 Jun 2007
 */

require("config.php");

require( "modules/googlemap/".$pgv_language["english"]);
if (file_exists( "modules/googlemap/".$pgv_language[$LANGUAGE])) require  "modules/googlemap/".$pgv_language[$LANGUAGE];
require( "modules/googlemap/".$helptextfile["english"]);
if (file_exists("modules/googlemap/".$helptextfile[$LANGUAGE])) require "modules/googlemap/".$helptextfile[$LANGUAGE];

// Must be an admin user to use this module
if (!userGedcomAdmin(getUserName())) {
	header("Location: login.php?url=placelist.php");
	exit;
}
print_header($pgv_lang["placecheck"].' - '.$GEDCOM);

// Scan all the gedcom directories for gedcom files
$all_dirs=array($INDEX_DIRECTORY=>"");
foreach ($GEDCOMS as $value)
	$all_dirs[dirname($value["path"])."/"]="";

$all_geds=array();
foreach ($all_dirs as $key=>$value) {
	$dir=opendir($key);
	while ($file=readdir($dir))
		if (!is_dir($key.$file) && is_readable($key.$file)) {
			$h=fopen($key.$file, 'r');
			if (preg_match('/0.*HEAD/i', fgets($h,255)))
				$all_geds[$file]=$key.$file;
			fclose($h);
		}
	closedir($dir);
}
if (count($all_geds)==0)
	$all_geds[]='-';

// Default values
if (!isset($ged))
	if (isset($GEDCOM) && in_array($GEDCOM, $all_geds))
		$ged=$GEDCOM;                                  // Current gedcom
	else {
		$tmp=array_keys($all_geds);
		$ged=$tmp[0];                                  // First gedcom in directory
	}
if (!isset($openinnew)) $openinnew=0;				  // Open links in same/new tab/window
if (!isset($state)){
	$state='XYZ';}
if (!isset($country)){
	$country='XYZ';}
	
//Start of User Defined options	

//Start of User Defined options
print "<table border='0' width='100%'><tr><td>";
$target=($openinnew==1 ? " target='_blank'" : '');
//Option box to select gedcom
print "<form method='post' name='placecheck' action='module.php?mod=googlemap&amp;pgvaction=placecheck'>\n";
print "<table align='left'>\n";
print "<tr><td class='list_label'>{$pgv_lang["gedcom_file"]}</td>\n";
print "<td class='optionbox'><select name='ged'>\n";
foreach ($all_geds as $key=>$value)
	print "<option value='$key'".($key==$ged?" selected='selected'":"").">$key</option>\n";
print "</select></td></tr>";

//Option box for 'Open in new window'
print "<tr><td class='list_label'>&nbsp; {$pgv_lang["open_link"]} &nbsp;</td>\n";
print "<td class='optionbox'><select name='openinnew'>\n";
print "<option value='0' ".($openinnew==0?" selected='selected'":"").">{$pgv_lang["same_win"]}</option>\n";
print "<option value='1' ".($openinnew==1?" selected='selected'":"").">{$pgv_lang["new_win"]}</option>\n";
print "</select></td></tr>";

//Option box to select top level place within Gedcom
print "<tr><td class='list_label'>".$pgv_lang['placecheck_top']."</td>\n";
print "<td class='optionbox'><select name='country' onchange='this.form.submit()'>\n";
print "<option selected='selected'>".$pgv_lang['placecheck_select1']."</option>";
print "<option value='XYZ'>".$pgv_lang["all"]."</option>";
$query   = "SELECT pl_place, pl_id FROM ".$TBLPREFIX."placelocation WHERE pl_level = 0 ORDER BY pl_place ASC";
$result = mysql_query($query);
while ($row = mysql_fetch_array($result, MYSQL_NUM))
	print "<option value='$row[0]'".($row[0]==$country?" selected='selected'":"").">$row[0]</option>\n";
print "</select></td></tr>";

//Option box to select level one place within the selected top level
if ($country !='XYZ') {
$query1   = "SELECT pl_id FROM ".$TBLPREFIX."placelocation WHERE pl_place LIKE '$country' ";
$result1 = mysql_query($query1);
$row1 = mysql_fetch_array($result1);
$par_id = $row1[0];
print "<tr><td class='list_label'>".$pgv_lang['placecheck_one']."</td>\n";
print "<td class='optionbox'><select name='state' onchange='this.form.submit()'>\n";
print "<option value='XYZ' selected='selected'>".$pgv_lang['placecheck_select2']."</option>";
print "<option value='XYZ'>".$pgv_lang["all"]."</option>";
$query2   = "SELECT pl_place, pl_id FROM ".$TBLPREFIX."placelocation WHERE pl_level = 1 AND pl_parent_id = '$par_id' ORDER BY pl_place ASC";
$result2 = mysql_query($query2);
while ($row2 = mysql_fetch_array($result2, MYSQL_NUM))
print "<option value='$row2[0]'".($row2[0]==$state?" selected='selected'":"").">$row2[0]</option>\n";
print "</select></td></tr>";
}

print "</table><input type='hidden' name='action' value='go' /></form>\n";

//Show Key table
print "</td><td>";
print "<table align='right'>\n";
print "<tr><td colspan='4' align='center' class='descriptionbox'><strong>".$pgv_lang['placecheck_key']."</strong></td></tr>";
print "<tr><td class='facts_value'><font color='#FF0000'>".$factarray["PLAC"]."</font></td><td class='facts_value' align='center'><strong><font color='#FF0000'>X</font></strong></td><td align='center' class='facts_value'><strong><font color='#FF0000'>X</font></strong></td><td class='facts_value'>".$pgv_lang['placecheck_key1']."</td></tr>";
print "<tr><td class='facts_value'><a>".$factarray["PLAC"]."</a></td><td class='facts_value' align='center'><strong><font color='#FF0000'>X</font></strong></td><td align='center' class='facts_value'><strong><font color='#FF0000'>X</font></strong></td><td class='facts_value'>".$pgv_lang['placecheck_key2']."</td></tr>";
print "<tr><td class='facts_value'><font color='#00FF00'><strong>unknown</strong></font></td><td class='facts_value' align='center'><strong><font color='#FF0000'>X</font></strong></td><td align='center' class='facts_value'><strong><font color='#FF0000'>X</font></strong></td><td class='facts_value'>".$pgv_lang['placecheck_key3']."</td></tr>";
print "<tr><td class='facts_value'><a>unknown</a></td><td class='facts_value' align='center'>N55.0</td><td align='center' class='facts_value'>W75.0</td><td class='facts_value'>".$pgv_lang['placecheck_key4']."</td></tr>";
print "</table>";
Print "</td></tr></table><hr />";

// Do not run until user selects a level, as default page may take a while to load.
// Instead, show some useful help info.
if (!isset($action)) {
	print "<p>".$pgv_lang['placecheck_text']."</p><hr />";
	print_footer();
	exit();
}

//Identify gedcom file
print "<strong>".$pgv_lang['placecheck_head'].": </strong>".$ged."<br/><br/>";

//Select all '2 PLAC ' tags in the file and create array
$handle=fopen($all_geds[$ged], 'r');
$place_list = array();
$i=0;
while (($value=fgets($handle))!==false) {
	$value=preg_replace('/[\r\n]+/', '', $value);
	if (preg_match('/^\s*(\d*)\s*(@[^@#]+@)?\s*(\S*)\s*(.*)/', $value, $match)) {
if (function_exists('memory_get_usage') && memory_get_usage()>33550000) print "<P>".count($gedfile).':'.memory_get_usage()."</P>";
if (preg_match('/(2 PLAC)/', $value)){
$place=substr($value,7);

if ($country == 'XYZ'){
	$place_list[$i]=$place;}
		if (strpos($place, $country) !==false){

if ($state == 'XYZ'){
	$place_list[$i]=$place;}
		if (strpos($place, $state) !==false){
$place_list[$i]=$place;}
}
$i++;}}
}

//sort the array, limit to unique values, and count them
$place_parts=array();
$place_list=array_unique($place_list);
sort($place_list);
$i=count($place_list);

//calculate maximum no. of levels to display
$x=0;
$max=0;
while ($x < $i){
	$levels=explode(",", $place_list[$x]);
	$parts=count($levels);
	if ($parts > $max) $max = $parts;
$x++;}
$x=0;

//scripts for edit, add and refresh
?>
<script language="JavaScript" type="text/javascript">
<!--
function edit_place_location(placeid) {
	window.open('module.php?mod=googlemap&pgvaction=places_edit&action=update&placeid='+placeid+"&"+sessionname+"="+sessionid, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	return false;
}

function add_place_location(placeid) {
	window.open('module.php?mod=googlemap&pgvaction=places_edit&action=add&placeid='+placeid+"&"+sessionname+"="+sessionid, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	return false;
}
function showchanges() {
	window.location = '<?php print $_SERVER["REQUEST_URI"]; ?>&show_changes=yes';
}
//-->
</script>
<?php

//start to produce the display table
$cols=0;
$span=$max*3+3;
print "<table class='facts_table' width = '100%'><tr>";
print "<td rowspan='3' class='descriptionbox' align = 'center'><strong>".$pgv_lang['placecheck_gedheader']."</strong></td>";
print "<td class='descriptionbox' colspan = '".$span."' align = 'center'><strong>".$pgv_lang['placecheck_gm_header']."</strong></td></tr>";
print "<tr>";
while ($cols < $max){
print "<td class='descriptionbox' colspan = '3' align = 'center'><strong>".PrintReady($pgv_lang['gm_level'])."&nbsp;".$cols."</strong></td>";
$cols++;}
print "</tr><tr>";
$cols=0;
while ($cols < $max){
print "<td class='descriptionbox' align = 'center'><strong>".$factarray["PLAC"]."</strong></td><td class='descriptionbox' align = 'center'><strong>".$factarray["LATI"]."</strong><td class='descriptionbox' align = 'center'><strong>".$factarray["LONG"]."</strong></td></td>";
$cols++;}
print "</tr>";
while ($x < $i){
	$placestr = "";
	$levels=explode(",", $place_list[$x]);
	$parts=count($levels);
	$levels = array_reverse($levels);
	$placestr .= "<a href=\"placelist.php?action=show&amp;";
        foreach($levels as $pindex=>$ppart) {
             // routine for replacing ampersands
             $ppart = preg_replace("/amp\%3B/", "", trim($ppart));
             $placestr .= "parent[$pindex]=".$ppart."&amp;";
        }
        $placestr .= "level=".count($levels);
        $placestr .= "\"> ".$place_list[$x]."</a>";
	print "<tr><td class='facts_value'>".$placestr."</td>";
	$z=0;
	$id=0;
	$level=0;
	$mapstr_edit = "<a href=\"javascript:;\" onclick=\"edit_place_location('";
	$mapstr_add = "<a href=\"javascript:;\" onclick=\"add_place_location('";
	$mapstr3 = "";
	$mapstr4 = "";
	$mapstr5 = "')\" title='";
	$mapstr6 = "' >";
	$mapstr7 = "')\">";
	$mapstr8 = "</a>";
	while ($z < $parts){
		if ($levels[$z]==' ' || $levels[$z]=='') $levels[$z]="unknown";// GoogleMap module uses "unknown" while GEDCOM uses , ,
		if ($id==0){
			$query   = " SELECT pl_id, pl_place, pl_long, pl_lati, pl_zoom FROM ".$TBLPREFIX."placelocation WHERE pl_level = '$z' AND pl_place LIKE '".rtrim(ltrim($levels[$z]))."'";
			} else {
			$query   = " SELECT pl_id, pl_place, pl_long, pl_lati, pl_zoom FROM ".$TBLPREFIX."placelocation WHERE pl_level = '$z'AND pl_parent_id = '$id' AND pl_place LIKE '".rtrim(ltrim($levels[$z]))."'";
			}
		$result = mysql_query($query);
		$row = @mysql_fetch_array($result);
		if ($row['pl_id'] != '') {$id = $row['pl_id'];}
		
		if ($row['pl_place']!='')
		{$placestr2 = $mapstr_edit.$id."&amp;level=".$level.$mapstr3.$mapstr5.$pgv_lang["placecheck_zoom"].$row['pl_zoom'].$mapstr6.$row['pl_place'].$mapstr8;}
			else
			{
				if ($levels[$z]=="unknown")
					{$placestr2 = $mapstr_add.$id."&amp;level=".$level.$mapstr3.$mapstr7."<font color='#00FF00'><strong>".rtrim(ltrim($levels[$z]))."</strong></font>".$mapstr8;}
				else
					{$placestr2 = $mapstr_add.$id."&amp;level=".$level.$mapstr3.$mapstr7."<font color='#FF0000'>".rtrim(ltrim($levels[$z]))."</font>".$mapstr8;}
			}
			
		$plac = "<td class='facts_value'>".$placestr2."</td>";
		if ($row['pl_lati']  != ''){$lati = "<td class='facts_value'>".$row['pl_lati']."</td>";} else {$lati = "<td class='facts_value' align = 'center'><strong><font color='#FF0000'>X</font></strong></td>";}
		if ($row['pl_long']  != ''){$long = "<td class='facts_value'>".$row['pl_long']."</td>";} else {$long = "<td class='facts_value' align = 'center'><strong><font color='#FF0000'>X</font></strong></td>";}
		print $plac;
		print $lati;
		print $long;
		$level++;
		$mapstr3 = $mapstr3."&amp;parent[".$z."]=".$row['pl_place'];
		$mapstr4 = $mapstr4."&amp;parent[".$z."]=".rtrim(ltrim($levels[$z]));
	$z++;}
$x++;
print "</tr>";}
print "<tr><td colspan=\"2\" class=\"list_label\">".$pgv_lang['placecheck_unique'].": " . $i . "</td></tr></table><br/><br/>";

//Close the gedcom file
fclose($handle);

//print footers
print "<hr />\n";
print_footer();
?>
