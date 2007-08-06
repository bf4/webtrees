<?php
/**
 * On Upcoming Events Block
 *
 * This block will print a list of upcoming events
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
  $username = getUserName();
  if (empty($username)) $allowDownload = "no";


  if ($daysprint < 1) $daysprint = 1;
  if ($daysprint > $DAYS_TO_SHOW_LIMIT) $daysprint = $DAYS_TO_SHOW_LIMIT;  // valid: 1 to limit

  // Look for cached Facts data
  $found_facts = get_event_list();

  // Output starts here
  print "<div id=\"upcoming_events\" class=\"block\">";
  print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
  print "<td class=\"blockh1\" >&nbsp;</td>";
  print "<td class=\"blockh2\" ><div class=\"blockhc\">";
  print_help_link("index_events_help", "qm");
  if ($PGV_BLOCKS["print_upcoming_events"]["canconfig"]) {
    $username = getUserName();
    if ((($ctype=="gedcom")&&(userGedcomAdmin($username))) || (($ctype=="user")&&(!empty($username)))) {
      if ($ctype=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
      else $name = $username;
      print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;ctype=$ctype&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
      print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
    }
  }
  print "<b>".$pgv_lang["upcoming_events"]."</b>";
  print "</div></td>";
  print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
  print "</table>";
  print "<div class=\"blockcontent\" >";
  if ($block) print "<div class=\"small_inner_block\">\n";


  // Output style 1:  Old format, no visible tables, much smaller text.  Better suited to right side of page.
  if ($infoStyle=="style1") {
	$OutputDone = false;
	$PrivateFacts = false;
	$lastgid="";

	$dateRangeStart=mktime( 0, 0, 0)+86400;
	$dateRangeEnd  =mktime(23,59,59)+86400*$daysprint;

	foreach($found_facts as $key=>$factarray) {
	  $anniversaryDate = $factarray[3];
	  if ($anniversaryDate>=$dateRangeStart && $anniversaryDate<=$dateRangeEnd) {
	    if ($factarray[2]=="INDI") {
	      $gid = $factarray[0];
	      $factrec = $factarray[1];
		    $disp = true;
	      if ($filter=="living" and is_dead_id($gid)) $disp = false;
	      else if (!displayDetailsByID($gid)) {
	        $disp = false;
	        $PrivateFacts = true;
	      }
	      if ($disp) {
	        $indirec = find_person_record($gid);
	        if ($indirec) {
	          $filterev = "all";
	          if ($onlyBDM == "yes") $filterev = "bdm";
	          $text = get_calendar_fact($factrec, "upcoming", $filter, $gid, $filterev);
	          if ($text!="filter") {
	            if (FactViewRestricted($gid, $factrec) or $text=="") {
	              $PrivateFacts = true;
	            } else {
	              if ($lastgid!=$gid) {
	                if ($lastgid != "") print "<br />";
	                $name = get_person_name($gid);
	                print "<a href=\"individual.php?pid=$gid&amp;ged=".$GEDCOM."\">";
	                print "<b>".PrintReady($name)."</b>";
	                print "<img id=\"box-".$gid."-".$key."-gender\" src=\"$PGV_IMAGE_DIR/";
	                if (preg_match("/1 SEX M/", $indirec)>0) print $PGV_IMAGES["sex"]["small"]."\" title=\"".$pgv_lang["male"]."\" alt=\"".$pgv_lang["male"];
	                else  if (preg_match("/1 SEX F/", $indirec)>0) print $PGV_IMAGES["sexf"]["small"]."\" title=\"".$pgv_lang["female"]."\" alt=\"".$pgv_lang["female"];
	                else print $PGV_IMAGES["sexn"]["small"]."\" title=\"".$pgv_lang["unknown"]."\" alt=\"".$pgv_lang["unknown"];
	                print "\" class=\"gender_image\" />";
	                if ($SHOW_ID_NUMBERS) {
		                  print "&nbsp;";
		                  if ($TEXT_DIRECTION=="rtl") print getRLM();
		                  print "(".$gid.")";
		                  if ($TEXT_DIRECTION=="rtl") print getRLM();
	                }
	                print "</a><br />\n";
	                $lastgid=$gid;
	              }
	              print "<div class=\"indent" . ($TEXT_DIRECTION=="rtl"?"_rtl":"") . "\">";
	              print $text;
	              print "</div>";
	              $OutputDone = true;
	            }
	          }
	        }
	      }
	    }

	    if ($factarray[2]=="FAM") {
	      $gid = $factarray[0];
	      $factrec = $factarray[1];

	      $disp = true;
	      if ($filter=="living") {
	        $parents = find_parents($gid);
	        if (is_dead_id($parents["HUSB"])) $disp = false;
	        else if (!displayDetailsByID($parents["HUSB"])) {
	          $disp = false;
	          $PrivateFacts = true;
	        }
	        if ($disp) {
	          if (is_dead_id($parents["WIFE"])) $disp = false;
	          else if (!displayDetailsByID($parents["WIFE"])) {
	            $disp = false;
	            $PrivateFacts = true;
	          }
	        }
	      }
	      else if (!displayDetailsByID($gid, "FAM")) {
	        $disp = false;
	        $PrivateFacts = true;
	      }
	      if ($disp) {
	        $famrec = find_family_record($gid);
	        if ($famrec) {
	          $name = get_family_descriptor($gid);
	          $filterev = "all";
	          if ($onlyBDM == "yes") $filterev = "bdm";
	          $text = get_calendar_fact($factrec, "upcoming", $filter, $gid, $filterev);
	          if ($text!="filter" and strpos($famrec, "1 DIV")===false) {
	            if (FactViewRestricted($gid, $factrec) or $text=="") {
			        $PrivateFacts = true;
		          } else {
	              if ($lastgid!=$gid) {
	                if ($lastgid != "") print "<br />";
	                print "<a href=\"family.php?famid=$gid&amp;ged=".$GEDCOM."\"><b>".PrintReady($name)."</b>";
	                if ($SHOW_ID_NUMBERS) {
		                  print "&nbsp;";
		                  if ($TEXT_DIRECTION=="rtl") print getRLM();
		                  print "(".$gid.")";
		                  if ($TEXT_DIRECTION=="rtl") print getRLM();
	                }
	                print "</a><br />\n";
	                $lastgid=$gid;
	              }
	              print "<div class=\"indent" . ($TEXT_DIRECTION=="rtl"?"_rtl":"") . "\">";
	              print $text;
	              print "</div>";
	              $OutputDone = true;
	            }
	          }
	        }
	      }
	    }
	  }
	}

	if ($PrivateFacts) {    // Facts were found but not printed for some reason
	  $pgv_lang["global_num1"] = $daysprint;
	  $Advisory = "no_events_privacy";
	  if ($OutputDone) $Advisory = "more_events_privacy";
	  if ($daysprint==1) $Advisory .= "1";
	  print "<b>";
	  print_text($Advisory);
	  print "</b><br />";
	} else if (!$OutputDone) {    // No Facts were found
	  $pgv_lang["global_num1"] = $daysprint;
	  $Advisory = "no_events_" . $config["filter"];
	  if ($daysprint==1) $Advisory .= "1";
	  print "<b>";
	  print_text($Advisory);
	  print "</b><br />";
	}
  }


  // Style 2: New format, tables, big text, etc.  Not too good on right side of page
  if ($infoStyle=="style2") {
	$option = "";
	if ($onlyBDM == "yes") $option .= " onlyBDM";
	if ($filter == "living") $option .= " living";
	if ($allowDownload == "no") $option .= " noDownload";
	print_events_table($found_facts, $daysprint, $option);
  }


  if ($block) print "</div>\n";
  print "</div>"; // blockcontent
  print "</div>"; // block
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
  	</td></tr>
  <?php

	// Cache file life is not configurable by user:  anything other than 1 day doesn't make sense
	print "<input type=\"hidden\" name=\"cache\" value=\"1\" />";
}
?>
