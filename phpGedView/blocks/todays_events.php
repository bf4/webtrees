<?php
/**
 * On This Day Events Block
 *
 * This block will print a list of today's events
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003  John Finlay and Others
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
 * @version $Id: todays_events.php,v 1.1.2.49 2006/05/16 15:02:35 canajun2eh Exp $
 */

$PGV_BLOCKS["print_todays_events"]["name"]        = $pgv_lang["todays_events_block"];
$PGV_BLOCKS["print_todays_events"]["descr"]        = "todays_events_descr";
$PGV_BLOCKS["print_todays_events"]["canconfig"]        = true;
$PGV_BLOCKS["print_todays_events"]["config"] = array("filter"=>"all", "onlyBDM"=>"no");

//-- today's events block
//-- this block prints a list of today's upcoming events of living people in your gedcom
function print_todays_events($block=true, $config="", $side, $index) {
  global $pgv_lang, $month, $year, $day, $monthtonum, $HIDE_LIVE_PEOPLE, $SHOW_ID_NUMBERS, $command, $TEXT_DIRECTION, $SHOW_FAM_ID_NUMBERS;
  global $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $PGV_BLOCKS;

  $block = true;      // Always restrict this block's height

  if (empty($config)) $config = $PGV_BLOCKS["print_todays_events"]["config"];
  if (isset($config["filter"])) $filter = $config["filter"];  // "living" or "all"
  else $filter = "all";
  if (isset($config["onlyBDM"])) $onlyBDM = $config["onlyBDM"];  // "yes" or "no"
  else $onlyBDM = "no";

  // Look for cached Facts data
  $found_facts = get_event_list();

  //-- Start output
  print "<div id=\"on_this_day_events\" class=\"block\">";
  print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
  print "<td class=\"blockh1\" >&nbsp;</td>";
  print "<td class=\"blockh2\" ><div class=\"blockhc\">";
  print_help_link("index_onthisday_help", "qm");
  $username = getUserName();
  if ($PGV_BLOCKS["print_todays_events"]["canconfig"]) {
    $username = getUserName();
    if ((($command=="gedcom")&&(userGedcomAdmin($username))) || (($command=="user")&&(!empty($username)))) {
      if ($command=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
      else $name = $username;
      print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;command=$command&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
      print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
    }
  }
  print "<b>".$pgv_lang["on_this_day"]."</b>";
  print "</div></td>";
  print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
  print "</table>";

  print "<div class=\"blockcontent\" >";
  if ($block) print "<div class=\"small_inner_block\">\n";

  $OutputDone = false;
  $PrivateFacts = false;
  $lastgid="";

  $dateRangeStart = mktime(0,0,0,$monthtonum[strtolower($month)],$day,$year);
  $dateRangeEnd = $dateRangeStart+(60*60*24)-1;

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
            $text = get_calendar_fact($factrec, "today", $filter, $gid, $filterev);
            if ($text!="filter") {
              if (FactViewRestricted($gid, $factrec) or $text=="") {
                $PrivateFacts = true;
              } else {
                if ($lastgid!=$gid) {
                  if ($lastgid != "") print "<br />";
                  $name = get_person_name($gid);
                  print "<a href=\"individual.php?pid=$gid&amp;ged=".$GEDCOM."\"><b>".PrintReady($name)."</b>";
                  print "<img id=\"box-".$gid."-".$key."-sex\" src=\"$PGV_IMAGE_DIR/";
                  if (preg_match("/1 SEX M/", $indirec)>0) print $PGV_IMAGES["sex"]["small"]."\" title=\"".$pgv_lang["male"]."\" alt=\"".$pgv_lang["male"];
                  else  if (preg_match("/1 SEX F/", $indirec)>0) print $PGV_IMAGES["sexf"]["small"]."\" title=\"".$pgv_lang["female"]."\" alt=\"".$pgv_lang["female"];
                  else print $PGV_IMAGES["sexn"]["small"]."\" title=\"".$pgv_lang["unknown"]."\" alt=\"".$pgv_lang["unknown"];
                  print "\" class=\"sex_image\" />";
                  if ($SHOW_ID_NUMBERS) {
	                  print "&nbsp;";
	                  if ($TEXT_DIRECTION=="rtl") print "&rlm;";
	                  print "(".$gid.")";
	                  if ($TEXT_DIRECTION=="rtl") print "&rlm;";
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
            $text = get_calendar_fact($factrec, "today", $filter, $gid, $filterev);
//          if ($text!="filter") {
            if ($text!="filter" and strpos($famrec, "1 DIV")===false) {
              if (FactViewRestricted($gid, $factrec) or $text=="") {
	  	        $PrivateFacts = true;
	          } else {
                if ($lastgid!=$gid) {
                  if ($lastgid != "") print "<br />";
                  print "<a href=\"family.php?famid=$gid&amp;ged=".$GEDCOM."\"><b>".PrintReady($name)."</b>";
                  if ($SHOW_FAM_ID_NUMBERS) {
	                  print "&nbsp;";
	                  if ($TEXT_DIRECTION=="rtl") print "&rlm";
	                  print "(".$gid.")";
	                  if ($TEXT_DIRECTION=="rtl") print "&rlm";
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
    $Advisory = "none_today_privacy";
    if ($OutputDone) $Advisory = "more_today_privacy";
    print "<b>";
    print_text($Advisory);
    print "</b><br />";
  } else if (!$OutputDone) {    // No Facts were found
    $Advisory = "none_today_" . $config["filter"];
    print "<b>";
    print_text($Advisory);
    print "</b><br />";
  }

  if ($block) print "</div>\n";
  print "</div>"; // blockcontent
  print "</div>"; // block
}

function print_todays_events_config($config) {
	global $pgv_lang, $PGV_BLOCKS, $TEXT_DIRECTION;
	if (empty($config)) $config = $PGV_BLOCKS["print_todays_events"]["config"];
	if (!isset($config["filter"])) $config["filter"] = "all";
	if (!isset($config["onlyBDM"])) $config["onlyBDM"] = "no";

	print "<tr><td class=\"descriptionbox width20\">".$pgv_lang["living_or_all"]."</td>";?>
	<td class="optionbox">
   	<select name="filter">
    	<option value="all"<?php if ($config["filter"]=="all") print " selected=\"selected\"";?>><?php print $pgv_lang["no"]; ?></option>
    	<option value="living"<?php if ($config["filter"]=="living") print " selected=\"selected\"";?>><?php print $pgv_lang["yes"]; ?></option>
  	</select>
  	</td></tr>

  	<?php
  	print "<tr><td class=\"descriptionbox width20\">";
 	print_help_link("basic_or_all_help", "qm");
  	print $pgv_lang["basic_or_all"]."</td>";?>
  	<td class="optionbox">
  	<select name="onlyBDM">
    	<option value="no"<?php if ($config["onlyBDM"]=="no") print " selected=\"selected\"";?>><?php print $pgv_lang["no"]; ?></option>
    	<option value="yes"<?php if ($config["onlyBDM"]=="yes") print " selected=\"selected\"";?>><?php print $pgv_lang["yes"]; ?></option>
  	</select>
  	</td></tr>
  <?php
}
?>
