<?php
/**
 * Check a GEDCOM file for compliance with the 5.5.1 specification
 * and other common errors.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2006-2007 Greg Roach fisharebest@users.sourceforge.net
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
 * @version $Id$ 2.6
 */

require("config.php");

require( $pgv_language["english"]);
if (file_exists( $pgv_language[$LANGUAGE])) require  $pgv_language[$LANGUAGE];
require $confighelpfile["english"];
if (file_exists($confighelpfile[$LANGUAGE])) require $confighelpfile[$LANGUAGE];
require $helptextfile["english"];
if (file_exists($helptextfile[$LANGUAGE])) require $helptextfile[$LANGUAGE];
require_once($factsfile["english"]);
if (file_exists( $factsfile[$LANGUAGE])) require_once $factsfile[$LANGUAGE];

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
//Start of User Defined options	
$target=($openinnew==1 ? " target='_blank'" : '');
//Option box to select gedcom 
print "<form method='post' name='placecheck' action='module.php?mod=googlemap&pgvaction=placecheck'>\n";
print "<table class='list_table, $TEXT_DIRECTION'>\n";
print "<tr><td class='list_label'>{$pgv_lang["gedcom_file"]}</td>\n";
print "<td class='optionbox'><select name='ged'>\n";
foreach ($all_geds as $key=>$value)
	print "<option value='$key'".($key==$ged?" selected='selected'":"").">$key</option>\n";
print "</select></td></tr>";

//Option box for 'Open in new window' 
print "<tr><td class='list_label'>&nbsp; {$pgv_lang["open_link"]} &nbsp;</td>\n";
print "<td class='optionbox'><select name='openinnew'>\n";
print "<option value='0' ".($openinnew==0?" selected='selected'":"")."/>{$pgv_lang["same_win"]}</option>\n";
print "<option value='1' ".($openinnew==1?" selected='selected'":"")."/>{$pgv_lang["new_win"]}</option>\n";
print "</select></td></tr>";

//Option box to select top level place within Gedcom
print "<tr><td class='list_label'>Top Level Place: </td>\n";
print "<td class='optionbox'><select name='country' onchange='this.form.submit()'>\n";
print "<option selected='selected'>Select Top Level...</option>";
print "<option value='XYZ'>ALL</option>";
$query   = "SELECT pl_place, pl_id FROM ".$TBLPREFIX."placelocation WHERE pl_level = '0'ORDER BY pl_place ASC";
$result = mysql_query($query);
while ($row = mysql_fetch_array($result, MYSQL_NUM))
	print "<option value='$row[0]'".($row[0]==$country?" selected='selected'":"").">$row[0]</option>\n";
print "</select></td></tr>";

//Option box to select level one place within the selected top level
if (isset($country)) {
$query1   = "SELECT * FROM ".$TBLPREFIX."placelocation WHERE pl_place LIKE '$country' ";
$result1 = mysql_query($query1);
$row1 = mysql_fetch_array($result1);
$par_id = $row1[0];
print "<tr><td class='list_label'>Level One Place: </td>\n";
print "<td class='optionbox'><select name='state' onchange='this.form.submit()'>\n";
print "<option value='XYZ' selected='selected'>Select Next Level...</option>";
print "<option value='XYZ'>ALL</option>";
$query2   = "SELECT pl_place FROM ".$TBLPREFIX."placelocation WHERE pl_level = '1' AND pl_parent_id = '$par_id' ORDER BY pl_place ASC";
$result2 = mysql_query($query2);
while ($row2 = mysql_fetch_array($result2, MYSQL_NUM))
print "<option value='$row2[0]'".($row2[0]==$state?" selected='selected'":"").">$row2[0]</option>\n";
print "</select></td></tr>";
}

print "</table><input type='hidden' name='action' value='go'></form><hr />\n";

// Do not run until user selects a level, as default page may take a while to load.
// Instead, show some useful help info.
if (!isset($action)) {
	print "<P>".$pgv_lang['placecheck_text']."</P><HR />";
	print_footer();
	exit();
}

//Identify gedcom file
print "<strong>Place list for gedcom file: </strong>".$ged."</br></br>";

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
$x=0;
?>
<script language="JavaScript" type="text/javascript">
<!--
function edit_place_location(placeid) {
	window.open('module.php?mod=googlemap&pgvaction=places_edit&action=update&placeid='+placeid+"&"+sessionname+"="+sessionid, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	return false;
}
//-->
</script>

<?php
// To increase the number of levels displayed to 5, un-comment rows 194 and 201; and change '15' in row 187 to '18'
print "<table border='0' cellspacing='5' width = '100%'><tr>";
print "<td align = 'center'><strong>".PrintReady('Gedcom Place Data (2 PLAC tag)')."</strong><hr></td>";
print "<td colspan = '15' align = 'center'><strong>GoogleMap Places Table Data</strong><hr></td></tr>";
print "<tr><td>&nbsp;</td>";
print "<td colspan = '3' align = 'center'><strong>".PrintReady('Top Level (0)')."</strong><hr></td>";
print "<td colspan = '3' align = 'center'><strong>Level One</strong><hr></td>";
print "<td colspan = '3' align = 'center'><strong>Level Two</strong><hr></td>";
print "<td colspan = '3' align = 'center'><strong>Level Three</strong><hr></td>";
print "<td colspan = '3' align = 'center'><strong>Level Four</strong><hr></td>";
//print "<td colspan = '3' align = 'center'><strong>Level Five</strong><hr></td></tr>";
print "<tr><td>&nbsp;</td>";
print "<td align = 'center'><strong>Place</strong><hr></td><td align = 'center'><strong>Long</strong><hr></td><td align = 'center'><strong>Lat</strong><hr></td>";
print "<td align = 'center'><strong>Place</strong><hr></td><td align = 'center'><strong>Long</strong><hr></td><td align = 'center'><strong>Lat</strong><hr></td>";
print "<td align = 'center'><strong>Place</strong><hr></td><td align = 'center'><strong>Long</strong><hr></td><td align = 'center'><strong>Lat</strong><hr></td>";
print "<td align = 'center'><strong>Place</strong><hr></td><td align = 'center'><strong>Long</strong><hr></td><td align = 'center'><strong>Lat</strong><hr></td>";
print "<td align = 'center'><strong>Place</strong><hr></td><td align = 'center'><strong>Long</strong><hr></td><td align = 'center'><strong>Lat</strong><hr></td>";
//print "<td align = 'center'><strong>Place</strong><hr></td><td align = 'center'><strong>Long</strong><hr></td><td align = 'center'><strong>Lat</strong><hr></td></tr>";
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
	print "<tr><td>".$placestr."</td>";
	$z=0;
	$id=0;
	$level=0;
	
	$mapstr1 = "<a href=\"javascript:;\" onclick=\"edit_place_location('";
	$mapstr2 = "";
	$mapstr3 = "')\">";
	$mapstr4 = "</a>";
	while ($z < $parts){		
		if ($id==0){
			$query   = " SELECT * FROM ".$TBLPREFIX."placelocation WHERE pl_level = '$z' AND pl_place LIKE '".rtrim(ltrim($levels[$z]))."'";} else {
			$query   = " SELECT * FROM ".$TBLPREFIX."placelocation WHERE pl_level = '$z'AND pl_parent_id = '$id' AND pl_place LIKE '".rtrim(ltrim($levels[$z]))."'";
			}
		$result = mysql_query($query);
		$row = @mysql_fetch_array($result);
		$id   = $row['pl_id'];
		$mapstr2 = $mapstr2."&parent[".$z."]=".$row['pl_place'];
		$placestr2 = $mapstr1.$id."&level=".$level.$mapstr2.$mapstr3.$row['pl_place'].$mapstr4;
		if ($row['pl_place'] != ''){$plac = "<td>".$placestr2."</td>";} else {$plac = "<td align = 'center'><strong><font color='#FF0000'>X</font></strong></td>";}
		if ($row['pl_long']  != ''){$long = "<td>".$row['pl_long']."</td>";}  else {$long = "<td align = 'center'><strong><font color='#FF0000'>X</font></strong></td>";}
		if ($row['pl_lati']  != ''){$lati = "<td>".$row['pl_lati']."</td>";}  else {$lati = "<td align = 'center'><strong><font color='#FF0000'>X</font></strong></td>";}
		print $plac;
		print $long;
		print $lati;
		$level++;
	$z++;}
$x++;
print "</tr>";}
print "</tr></table></br></br>";
print "<strong>Total number of unique places = </strong>".$i;

//Close the gedcom file
fclose($handle);

//print footers
print "<hr />\n";
print_footer();
?>