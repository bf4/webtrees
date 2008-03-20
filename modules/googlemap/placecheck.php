<?php
/**
 * Check a GEDCOM file for compliance with the 5.5.1 specification
 * and other common errors.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 Nigel Osborne nigelo@users.sourceforge.net
 * Modifications Copyright (C) 2008 Greg Roach
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
 * @version 4.1 09 Jul 2007
 * $Id$
 */
require("config.php");
require("googlemap.php"); // gives access to googlemap functions

loadLangFile("gm_lang, gm_help");

// Must be an admin user to use this module
if (!PGV_USER_GEDCOM_ADMIN) {
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
if (!isset($openinnew)) $openinnew=0;              // Open links in same/new tab/window
if (!isset($state))     $state='XYZ';
if (!isset($country))   $country='XYZ';

$target=($openinnew==1 ? " target='_blank'" : '');
print "<div align=\"center\" style=\"width: 99%;\"><h1>".$pgv_lang["placecheck"]."</h1></div>";

//Start of User Defined options
print "<table border='0' width='100%' height='100px' overflow='auto';>";
print "<form method='post' name='placecheck' action='module.php?mod=googlemap&amp;pgvaction=placecheck'>";
print "<tr valign='top'>";
print "<td>";
print "<table align='left'>";
print "<tr><td colspan='2'class='descriptionbox' align='center'><strong>".$pgv_lang['placecheck_options']."</strong></td></tr>";
//Option box to select gedcom
print "<tr><td class='descriptionbox'>{$pgv_lang["gedcom_file"]}</td>";
print "<td class='optionbox'><select name='ged'>";
foreach ($all_geds as $key=>$value)
	print "<option value='$key'".($key==$ged?" selected='selected'":"").">$key</option>";
print "</select></td></tr>";
//Option box for 'Open in new window'
print "<tr><td class='descriptionbox'>{$pgv_lang["open_link"]}</td>";
print "<td class='optionbox'><select name='openinnew'>";
print "<option value='0' ".($openinnew==0?" selected='selected'":"").">{$pgv_lang["same_win"]}</option>";
print "<option value='1' ".($openinnew==1?" selected='selected'":"").">{$pgv_lang["new_win"]}</option>";
print "</select></td></tr>";
//Option box to select top level place within Gedcom
print "<tr><td class='descriptionbox'>".$pgv_lang['placecheck_top']."</td>";
print "<td class='optionbox'><select name='country'>";
print "<option value='XYZ'selected='selected'>".$pgv_lang['placecheck_select1']."</option>";
print "<option value='XYZ'>".$pgv_lang["all"]."</option>";
$query="SELECT pl_id, pl_place FROM {$TBLPREFIX}placelocation WHERE pl_level=0 ORDER BY pl_place";
foreach ($DBCONN->getAssoc($query) as $id=>$place) {
	print "<option value='{$place}'";
	if ($place==$country) {
		print " selected='selected'";
		$par_id=$id;
	}
	print ">{$place}</option>";
}
print "</select></td></tr>";

//Option box to select level one place within the selected top level
if ($country!='XYZ') {
	print "<tr><td class='descriptionbox'>".$pgv_lang['placecheck_one']."</td>";
	print "<td class='optionbox'><select name='state'>";
	print "<option value='XYZ' selected='selected'>".$pgv_lang['placecheck_select2']."</option>";
	print "<option value='XYZ'>".$pgv_lang["all"]."</option>";
	$query="SELECT pl_place FROM {$TBLPREFIX}placelocation WHERE pl_parent_id={$par_id} ORDER BY pl_place";
	foreach ($DBCONN->getCol($query) as $place) {
		print "<option value='{$place}'".($place==$state?" selected='selected'":"").">{$place}</option>";
	}
	print "</select></td></tr>";
}
print "</table>";
print "</td>";
//Show Filter table
if (!isset ($_POST["matching"])) {$matching=0;} else {$matching=1;}
print "<td>";
print "<table>";
print "<tr><td colspan='2' class='descriptionbox' align='center'>";
print_help_link("PLACECHECK_FILTER_help", "qm", "PLACECHECK_FILTER");
print "<strong>".$pgv_lang["placecheck_filter_text"]."</strong></td></tr>";
print "<tr><td class='descriptionbox'>";
print_help_link("PLACECHECK_MATCH_help", "qm", "PLACECHECK_MATCH");
print $pgv_lang["placecheck_match"]."</td>";
print "<td class='optionbox'><input type=\"checkbox\" name=\"matching\" value=\"active\"";
if($matching==1) {
	print " checked=\"checked\"";
}
print "></td></tr>";
print "</table>";
print "</td>";

//Show Key table
print "<td rowspan='2'>";
print "<table align='right'>";
print "<tr><td colspan='4' align='center' class='descriptionbox'><strong>".$pgv_lang['placecheck_key']."</strong></td></tr>";
print "<tr><td class='facts_value'><font color='#FF0000'>".$factarray["PLAC"]."</font></td><td class='facts_value' align='center'><strong><font color='#FF0000'>X</font></strong></td><td align='center' class='facts_value'><strong><font color='#FF0000'>X</font></strong></td><td class='facts_value'><font size=\"-2\">".$pgv_lang['placecheck_key1']."</font></td></tr>";
print "<tr><td class='facts_value'><a>".$factarray["PLAC"]."</a></td><td class='facts_value' align='center'><strong><font color='#FF0000'>X</font></strong></td><td align='center' class='facts_value'><strong><font color='#FF0000'>X</font></strong></td><td class='facts_value'><font size=\"-2\">".$pgv_lang['placecheck_key2']."</font></td></tr>";
print "<tr><td class='facts_value'><font color='#00FF00'><strong>unknown</strong></font></td><td class='facts_value' align='center'><strong><font color='#FF0000'>X</font></strong></td><td align='center' class='facts_value'><strong><font color='#FF0000'>X</font></strong></td><td class='facts_value'><font size=\"-2\">".$pgv_lang['placecheck_key3']."</font></td></tr>";
print "<tr><td class='facts_value'><a>unknown</a></td><td class='facts_value' align='center'>N55.0</td><td align='center' class='facts_value'>W75.0</td><td class='facts_value'><font size=\"-2\">".$pgv_lang['placecheck_key4']."</font></td></tr>";
print "</table>";
print "</td>";
print "</tr>";
print "<tr>";
print "<td colspan='2'>";
print "<input type='submit' value='{$pgv_lang["show"]}'><input type='hidden' name='action' value='go'>";
print "</td>";
print "</tr>";
print "</form>";
print "</table>";
print "<hr />";

// Do not run until user selects a gedcom/place/etc.
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
$place_list=array();
$i=0;
while (($value=fgets($handle))!==false)
	if (preg_match('/^2 PLAC ([^\r\n]+)/', $value, $match)) {
		$place=$match[1];
		if ($country=='XYZ') {
			$place_list[$i]=$place;
		}
		if (strpos($place, $country)!==false) {
			if ($state=='XYZ') {
				$place_list[$i]=$place;
			}
			if (strpos($place, $state)!==false) {
				$place_list[$i]=$place;
			}
		}
		$i++;
	}

//sort the array, limit to unique values, and count them
$place_parts=array();
$place_list=array_unique($place_list);
sort($place_list);
$i=count($place_list);

//calculate maximum no. of levels to display
$x=0;
$max=0;
while ($x<$i) {
	$levels=explode(",", $place_list[$x]);
	$parts=count($levels);
	if ($parts>$max) $max=$parts;
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
	window.location='<?php print $_SERVER["REQUEST_URI"]; ?>&show_changes=yes';
}

var helpWin;
function helpPopup(which) {
	if ((!helpWin)||(helpWin.closed)) helpWin=window.open('module.php?mod=googlemap&pgvaction=editconfig_help&help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');
	else helpWin.location='modules/googlemap/editconfig_help.php?help='+which;
	return false;
}
function getHelp(which) {
	if ((helpWin)&&(!helpWin.closed)) helpWin.location='module.php?mod=googlemap&pgvaction=editconfig_help&help='+which;
}

function closeHelp() {
	if (helpWin) helpWin.close();
}

//-->
</script>
<?php

//start to produce the display table
$cols=0;
$span=$max*3+3;
print "<table class='facts_table' width='100%'><tr>";
print "<td rowspan='3' class='descriptionbox' align='center'><strong>".$pgv_lang['placecheck_gedheader']."</strong></td>";
print "<td class='descriptionbox' colspan='".$span."' align='center'><strong>".$pgv_lang['placecheck_gm_header']."</strong></td></tr>";
print "<tr>";
while ($cols<$max) {
	print "<td class='descriptionbox' colspan='3' align='center'><strong>".PrintReady($pgv_lang['gm_level'])."&nbsp;".$cols."</strong></td>";
	$cols++;
}
print "</tr><tr>";
$cols=0;
while ($cols<$max) {
	print "<td class='descriptionbox' align='center'><strong>".$factarray["PLAC"]."</strong></td><td class='descriptionbox' align='center'><strong>".$factarray["LATI"]."</strong><td class='descriptionbox' align='center'><strong>".$factarray["LONG"]."</strong></td></td>";
	$cols++;
}
print "</tr>";
$countrows=0;
while ($x<$i) {
	$placestr="";
	$levels=explode(",", $place_list[$x]);
	$parts=count($levels);
	$levels=array_reverse($levels);
	$placestr.="<a href=\"placelist.php?action=show&amp;";
	foreach($levels as $pindex=>$ppart) {
		$ppart=urlencode(trim($ppart));
		$placestr.="parent[$pindex]=".$ppart."&amp;";
	}
	$placestr.="level=".count($levels);
	$placestr.="\">".$place_list[$x]."</a>";
	$gedplace="<tr><td class='facts_value'>".$placestr."</td>";
	$z=0;
	$y=0;
	$id=0;
	$level=0;
	$matched[$x]=0;// used to exclude places where the gedcom place is matched at all levels
	$mapstr_edit="<a href=\"javascript:;\" onclick=\"edit_place_location('";
	$mapstr_add="<a href=\"javascript:;\" onclick=\"add_place_location('";
	$mapstr3="";
	$mapstr4="";
	$mapstr5="')\" title='";
	$mapstr6="' >";
	$mapstr7="')\">";
	$mapstr8="</a>";
	while ($z<$parts) {
		if ($levels[$z]==' ' || $levels[$z]=='')
			$levels[$z]="unknown";// GoogleMap module uses "unknown" while GEDCOM uses , ,

		$levels[$z]=rtrim(ltrim($levels[$z]));

		$placelist=create_possible_place_names($levels[$z], $z+1); // add the necessary prefix/postfix values to the place name
		foreach ($placelist as $key=>$placename) {
			$escparent=preg_replace("/\?/","\\\\\\?", $DBCONN->escapeSimple($placename));
			$psql="SELECT pl_id, pl_place, pl_long, pl_lati, pl_zoom FROM {$TBLPREFIX}placelocation WHERE pl_level={$z} AND pl_parent_id={$id} AND pl_place LIKE '{$escparent}' ORDER BY pl_place";
			$res=dbquery($psql);
			$row=& $res->fetchRow(DB_FETCHMODE_ASSOC);
			$res->free();
			if (!empty($row['pl_id'])) {
				$row['pl_placerequested']=$levels[$z]; // keep the actual place name that was requested so we can display that instead of what is in the db 
				break;
			}
		}
		if ($row['pl_id']!='') {
			$id=$row['pl_id'];
		}

		if ($row['pl_place']!='') {
			$placestr2=$mapstr_edit.$id."&amp;level=".$level.$mapstr3.$mapstr5.$pgv_lang["placecheck_zoom"].$row['pl_zoom'].$mapstr6.$row['pl_placerequested'].$mapstr8;
			if ($row['pl_place']=='unknown')
				$matched[$x]++;
		} else {
			if ($levels[$z]=="unknown") {
				$placestr2=$mapstr_add.$id."&amp;level=".$level.$mapstr3.$mapstr7."<font color='#00FF00'><strong>".rtrim(ltrim($levels[$z]))."</strong></font>".$mapstr8;$matched[$x]++;
			} else {
				$placestr2=$mapstr_add.$id."&amp;level=".$level.$mapstr3.$mapstr7."<font color='#FF0000'>".rtrim(ltrim($levels[$z]))."</font>".$mapstr8;$matched[$x]++;
			}
		}
		$plac[$z]="<td class='facts_value'>".$placestr2."</td>";
		if ($row['pl_lati']!='') {
			$lati[$z]="<td class='facts_value'>".$row['pl_lati']."</td>";
		} else {
			$lati[$z]="<td class='facts_value' align='center'><strong><font color='#FF0000'>X</font></strong></td>";$matched[$x]++;
		}
		if ($row['pl_long']!='') {
			$long[$z]="<td class='facts_value'>".$row['pl_long']."</td>";
		} else {
			$long[$z]="<td class='facts_value' align='center'><strong><font color='#FF0000'>X</font></strong></td>";$matched[$x]++;
		}
		$level++;
		$mapstr3=$mapstr3."&amp;parent[".$z."]=".$row['pl_placerequested'];
		$mapstr4=$mapstr4."&amp;parent[".$z."]=".rtrim(ltrim($levels[$z]));
		$z++;
	}
	if ($matching==1) {
		$matched[$x]=1;
	}
	if ($matched[$x]!=0) {
		print $gedplace;
		$z=0;
		while ($z<$max) {
		if ($z<$parts) {
			print $plac[$z];
			print $lati[$z];
			print $long[$z];
		} else {
			print "<td class='facts_value'>&nbsp;</td><td class='facts_value'>&nbsp;</td><td class='facts_value'>&nbsp;</td>";}
			$z++;
		}
		print "</tr>";
		$countrows++;
	}
	$x++;
}

// Print final row of table
print "<tr><td colspan=\"2\" class=\"list_label\">".$pgv_lang['placecheck_unique'].": ".$countrows."</td></tr></table><br/><br/>";

//Close the gedcom file
fclose($handle);

//print footers
print "<hr />";
print_footer();
?>
