<?php
/**
 * Individual Page
 *
 * Display all of the information about an individual
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008	John Finlay and Others
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
 * @subpackage Charts
 * @version $Id$
 */

require("config.php");

loadLangFile("pgv_facts, gm_lang");

if (empty($show_changes)) $show_changes = "yes";
if (empty($action)) $action="";
if (!isset($pid)) $pid="";
$pid = clean_input($pid);
$default_tab = $GEDCOM_DEFAULT_TAB;

$indirec = find_person_record($pid);
if (!$indirec) $indirec = find_record_in_file($pid);
$NAME_LINENUM = 1;

//-- make sure we have the true pid from the record
$ct = preg_match("/0 @(.*)@/", $indirec, $match);
if ($ct>0) $pid = trim($match[1]);

if (($USE_RIN)&&($indirec==false)) {
   $pid = find_rin_id($pid);
   $indirec = find_person_record($pid);
}

$uname = getUserName();
if (!empty($uname)) {
	if (get_user_setting($uname, 'default_tab') {
		$default_tab = get_user_setting($uname, 'default_tab');
	}
	//-- add favorites action
	if (($action=="addfav")&&(!empty($gid))) {
			$gid = strtoupper($gid);
			$indirec = find_person_record($gid);
			if ($indirec) {
					$favorite = array();
					$favorite["username"] = $uname;
					$favorite["gid"] = $gid;
					$favorite["type"] = "INDI";
					$favorite["file"] = $GEDCOM;
					addFavorite($favorite);
			}
	}
}

$accept_success=false;
if (PGV_USER_CAN_ACCEPT) {
	if ($action=="accept") {
		if ($PGV_DATABASE!="index") {
			if (accept_changes($pid."_".$GEDCOM)) {
					$show_changes="no";
					$accept_success=true;
					$indirec = find_record_in_file($pid);
			}
		}
		else {
			header("Location: edit_changes.php");
			exit;
		}
	}
}

// -- array of GEDCOM elements that will be found but should not be displayed
$nonfacts = array();
$nonfacts[] = "FAMS";
$nonfacts[] = "FAMC";
$nonfacts[] = "MAY";
$nonfacts[] = "BLOB";
$nonfacts[] = "CHIL";
$nonfacts[] = "HUSB";
$nonfacts[] = "WIFE";
$nonfacts[] = "";

$nonfamfacts[] = "UID";
$nonfamfacts[] = "";

$sexarray = array();
$sexarray["M"] = $pgv_lang["male"];
$sexarray["F"] = $pgv_lang["female"];
$sexarray["U"] = $pgv_lang["unknown"];
$indifacts = array();			 // -- array to store the fact records in for sorting and displaying
$globalfacts = array();
$otheritems = array();			  //-- notes, sources, media objects
$sex = "U";

$FACT_COUNT=0;
$NAME_COUNT=0;

$st = preg_match("/1 SEX (.*)/", $indirec, $smatch);
if ($st>0) {
		$sex = trim($smatch[1]);
}

//-- get birth date for age calculations
$bpos1 = strpos($indirec, "1 BIRT");
if ($bpos1) {
		$index = 1;
		$birthrec = get_sub_record(1, "1 BIRT", $indirec, $index);
		while(!empty($birthrec)) {
				$hct = preg_match("/2 DATE.*(@#DHEBREW@)/", $birthrec, $match);
				if ($hct>0) {
						$dct = preg_match("/2 DATE.*(\d\d\d\d)/", $birthrec, $match);
						if ($dct>0) $hebrew_birthyear = trim($match[1]);
						$dct = preg_match("/2 DATE.*(...) \d\d\d\d/", $birthrec, $match);
						if ($dct>0) {
								if (isset($monthtonum[strtolower(trim($match[1]))])) {
										$hebrew_birthmonth = $monthtonum[strtolower(trim($match[1]))];
								}
						}
						$dct = preg_match("/2 DATE.*(\d+) ... \d\d\d\d/", $birthrec, $match);
						if ($dct>0) {
								$hebrew_birthdate = trim($match[1]);
						}
				}
				else {
						$dct = preg_match("/2 DATE.*(\d\d\d\d)/", $birthrec, $match);
						if ($dct>0) $birthyear = trim($match[1]);
						$dct = preg_match("/2 DATE.*([A-Za-z]{3}) \d\d\d\d/", $birthrec, $match);
						if ($dct>0) {
								if (isset($monthtonum[strtolower(trim($match[1]))])) {
										$birthmonth = $monthtonum[strtolower(trim($match[1]))];
								}
						}
						$dct = preg_match("/2 DATE[^\d]*(\d+) [A-Za-z]{3} \d\d\d\d/", $birthrec, $match);
						if ($dct>0) {
								$birthdate = trim($match[1]);
						}
				}
				$index++;
				$birthrec = get_sub_record(1, "1 BIRT", $indirec, $index);
		}
}

if (userCanEdit()) {
   if (isset($pgv_changes[$pid."_".$GEDCOM])) {
		  $newrec = find_record_in_file($pid);
		  $indilines = split("\n", $newrec);   // -- find the number of lines in the individuals record
		  $lct = count($indilines);
		  $factrec = "";   // -- complete fact record
		  $line = "";	// -- temporary line buffer
		  $f=0;
		  $newfacts = array();
		  $newother = array();
		  $newglobal = array();
		  $linenum = 0;
		  for($i=1; $i<=$lct; $i++) {
				 if ($i<$lct) $line = preg_replace("/\r/", "", $indilines[$i]);
				 else $line=" ";
				 if (empty($line)) $line=" ";
				 if (($i==$lct)||($line{0}==1)) {
//						$ft = preg_match("/1\s(_?\w+)(.*)/", $factrec, $match);
						$ft = preg_match("/1\s(\w+)(.*)/", $factrec, $match);
						if ($ft>0) $fact = $match[1];
						else $fact="";
						$fact = trim($fact);
						// -- handle special name fact case
						if ($fact=="NAME") {
							$newglobal[] = array($linenum, $factrec);
						}
						// -- handle special source fact case
						else if ($fact=="SOUR") {
						   $newother[]=array($linenum, $factrec);
						}
						// -- handle special media object case
						else if ($fact=="OBJE") {
						   $newother[]=array($linenum, $factrec);
						}
						// -- handle special note fact case
						else if ($fact=="NOTE") {
						   $newother[]=array($linenum, $factrec);
						}
						// -- handle special sex case
						else if ($fact=="SEX") {
							$newglobal[] = array($linenum, $factrec);
						}
						else if (!in_array($fact, $nonfacts)) {
							$newfacts[$f]=array($linenum, $factrec);
							$f++;
						}
						$factrec = $line;
						$linenum = $i;
				 }
				 else $factrec .= "\n".$line;
		  }
   }
}

$name = get_person_name($pid);

// Check if an additional name exists for this person
$addname = get_add_person_name($pid);

$disp = displayDetails($indirec);
if (!$disp && !showLivingName($indirec)) $name = $pgv_lang["private"];
print_header($name." - $pid - ".$pgv_lang["indi_info"]);
//print_help_link("individual_details_help", "page_help");

if (!$disp && !showLivingName($indirec)) {
   print_privacy_error($CONTACT_EMAIL);
   print_footer();
   exit;
}

//-- find all the fact information
$indilines = split("\n", $indirec);   // -- find the number of lines in the individuals record
$lct = count($indilines);
$factrec = "";	 // -- complete fact record
$line = "";   // -- temporary line buffer
$f=0;	   // -- counter
$o = 0;
$g = 0;
$linenum=1;
$sexfound = false;
for($i=1; $i<=$lct; $i++) {
   if ($i<$lct) $line = preg_replace("/\r/", "", $indilines[$i]);
   else $line=" ";
   if (empty($line)) $line=" ";
   if (($i==$lct)||($line{0}==1)) {
//		  $ft = preg_match("/1\s(_?\w+)(.*)/", $factrec, $match);
		  $ft = preg_match("/1\s(\w+)(.*)/", $factrec, $match);
		  if ($ft>0) $fact = $match[1];
		  else $fact="";
		  $fact = trim($fact);
		  // -- handle special name fact case
		  if ($fact=="NAME") {
				 $globalfacts[$g] = array($linenum, $factrec);
				 $g++;
		  }
		  // -- handle special source fact case
		  else if ($fact=="SOUR") {
				 $otheritems[$o] = array($linenum, $factrec);
				 $o++;
		  }
		  // -- handle special media object case
		  else if ($fact=="OBJE") {
				 $otheritems[$o] = array($linenum, $factrec);
				 $o++;
		  }
		  // -- handle special note fact case
		  else if ($fact=="NOTE") {
				 $otheritems[$o] = array($linenum, $factrec);
				 $o++;
		  }
		  // -- handle special sex case
		  else if ($fact=="SEX") {
				 $globalfacts[$g] = array($linenum, $factrec);
				 $g++;
				 $sexfound = true;
		  }
		  else if (!in_array($fact, $nonfacts)) {
				 $indifacts[$f]=array($linenum, $factrec);
				 $f++;
		  }
		  $factrec = $line;
		  $linenum = $i;
   }
   else $factrec .= "\n".$line;
}
//-- add a new sex fact if one was not found
if (!$sexfound) {
	$globalfacts[$g] = array('new', "1 SEX U");
	$g++;
}

//-- loop through new facts and add them to the list if they are any changes
if (isset($newfacts)) {
		//-- compare new and old facts of the Personal Fact and Details tab 1
   for($i=0; $i<count($indifacts); $i++) {
		  $found=false;
		  foreach($newfacts as $indexval => $newfact) {
				 if (trim($newfact[1])==trim($indifacts[$i][1])) {
						$indifacts[$i][0] = $newfact[0];				//-- make sure the correct linenumber is used
						$found=true;
						break;
				 }
		  }
		  if ((!$found)&&($show_changes=="yes")) {
				 $indifacts[$i][1].="\nPGV_OLD\n";
		  }
   }
   foreach($newfacts as $indexval => $newfact) {
		  $found=false;
		  foreach($indifacts as $indexval => $fact) {
				 if (trim($fact[1])==trim($newfact[1])) {
						$found=true;
						break;
				 }
		  }
		  if ((!$found)&&($show_changes=="yes")) {
				 $newfact[1].="\nPGV_NEW\n";
				 $indifacts[$f]=$newfact;
				 $f++;
		  }
   }
   //-- compare new and old facts of the Notes Sources and Media tab 2
   for($i=0; $i<count($otheritems); $i++) {
		  $found=false;
		  foreach($newother as $indexval => $newfact) {
				 if (trim($newfact[1])==trim($otheritems[$i][1])) {
						 $otheritems[$i][0] = $newfact[0];				  //-- make sure the correct linenumber is used
						$found=true;
						break;
				 }
		  }
		  if ((!$found)&&($show_changes=="yes")) {
				 $otheritems[$i][1].="\nPGV_OLD\n";
		  }
   }
   foreach($newother as $indexval => $newfact) {
		  $found=false;
		  foreach($otheritems as $indexval => $fact) {
				 if (trim($fact[1])==trim($newfact[1])) {
						$found=true;
						break;
				 }
		  }
		  if ((!$found)&&($show_changes=="yes")) {
				 $newfact[1].="\nPGV_NEW\n";
				 $otheritems[$o]=$newfact;
				 $o++;
		  }
   }
   //-- compare new and old facts of the Global facts
   for($i=0; $i<count($globalfacts); $i++) {
		  $found=false;
		  foreach($newglobal as $indexval => $newfact) {
				 if (trim($newfact[1])==trim($globalfacts[$i][1])) {
						 $globalfacts[$i][0] = $newfact[0]; 			   //-- make sure the correct linenumber is used
						$found=true;
						break;
				 }
		  }
		  if ((!$found)&&($show_changes=="yes")) {
				 $globalfacts[$i][1].="\nPGV_OLD\n";
		  }
   }
   foreach($newglobal as $indexval => $newfact) {
		  $found=false;
		  foreach($globalfacts as $indexval => $fact) {
				 if (trim($fact[1])==trim($newfact[1])) {
						$found=true;
						break;
				 }
		  }
		  if ((!$found)&&($show_changes=="yes")) {
				 $newfact[1].="\nPGV_NEW\n";
				 $globalfacts[$f]=$newfact;
				 $f++;
		  }
   }
}
//-- find family as spouse
$ct = preg_match_all("/1\s+FAMS\s+@(.*)@/", $indirec, $fmatch, PREG_SET_ORDER);
for($j=0; $j<$ct; $j++) {
		$famid = $fmatch[$j][1];
		$famrec = find_family_record($famid);
		$parents=find_parents_in_record($famrec);
		if ($parents['HUSB']==$pid) $spouse=$parents['WIFE'];
		else $spouse=$parents['HUSB'];
		$indilines = split("\n", $famrec);	 // -- find the number of lines in the individuals record
		$lct = count($indilines);
		$factrec = "";	 // -- complete fact record
		$line = "";   // -- temporary line buffer
		$linenum = 0;
		for($i=1; $i<=$lct; $i++) {
				if ($i<$lct) $line = preg_replace("/\r/", "", $indilines[$i]);
				else $line=" ";
				if (empty($line)) $line=" ";
				if (($i==$lct)||($line{0}==1)) {
//						$ft = preg_match("/1\s(_?\w+)(.*)/", $factrec, $match);
						$ft = preg_match("/1\s(\w+)(.*)/", $factrec, $match);
						if ($ft>0) $fact = $match[1];
						else $fact="";
						$fact = trim($fact);
						// -- handle special source fact case
						if (($fact!="SOUR") && ($fact!="OBJE") && ($fact!="NOTE") && ($fact!="CHAN") && ($fact!="_UID") && ($fact!="RIN")) {
								if ((!in_array($fact, $nonfacts))&&(!in_array($fact, $nonfamfacts))) {
										$factrec.="\nPGV_SPOUSE: $spouse\n";
										$factrec.="PGV_FAMILY_ID: $famid\n";
										$indifacts[$f]=array($linenum, $factrec);
										$f++;
								}
						}
						else if ($fact=="OBJE") {
								$factrec.="\nPGV_SPOUSE: $spouse\n";
								$factrec.="PGV_FAMILY_ID: $famid\n";
								$otheritems[$o]=array($linenum, $factrec);
								$o++;
						}
						$factrec = $line;
						$linenum = $i;
				}
				else $factrec .= "\n".$line;
		}
}
print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"facts_table\"><tr>";
if (($disp) && ($MULTI_MEDIA && $SHOW_HIGHLIGHT_IMAGES)) {
	$firstmediarec = find_highlighted_object($pid, $indirec);
	if (!empty($firstmediarec)) {
		if ($USE_THUMBS_MAIN) $filename = $firstmediarec["thumb"];
		else $filename = $firstmediarec["file"];
		if (empty($filename)) $filename = $firstmediarec["thumb"];
		if (!empty($filename)) {
			print "\n\t<td rowspan=\"2\" width=\"100\" valign=\"top\"><img src=\"$filename\" align=\"left\" class=\"image\" alt=\"".$firstmediarec["file"]."\" /></td>";
		}
	}
}

print "<td valign=\"top\">";
if ($accept_success) print "<b>".$pgv_lang["accept_successful"]."</b><br />";
print "<span class=\"name_head\">".PrintReady($name)." <span dir=\"ltr\">($pid)</span>";
print "</span><br />";
// Line below added for additional name
if (strlen($addname) > 0) print "<span class=\"name_head\">".PrintReady($addname)."</span><br />";
if ($disp) {
	$SEX_COUNT = 0;
	$TOTAL_NAMES = 0;
	foreach ($globalfacts as $key => $value) {
//		$ft = preg_match("/\d\s(_?\w+)(.*)/", $value[1], $match);
		$ft = preg_match("/\d\s(\w+)(.*)/", $value[1], $match);
		if ($ft>0) $fact = $match[1];
		else $fact="";
		$fact = trim($fact);
		if ($fact=="SEX") {
			$SEX_COUNT++;
			$SEX_LINENUM = $value[0];
		}
		if ($fact=="NAME") {
			$TOTAL_NAMES++;
			$NAME_LINENUM = $value[0];
		}
	}
	print "\n<table><tr>";
	$i=0;
	$maxi=0;
	foreach ($globalfacts as $key => $value) {
//		$ft = preg_match("/\d\s(_?\w+)(.*)/", $value[1], $match);
		$ft = preg_match("/\d\s(\w+)(.*)/", $value[1], $match);
		if ($ft>0) $fact = $match[1];
		else $fact="";
		$fact = trim($fact);
		if ($fact=="SEX") print_sex_record($value[1], $value[0]);
		if ($fact=="NAME") print_name_record($value[1], $value[0]);
		$FACT_COUNT++;
		print "<td width=\"10\"><br /></td>\n";
		$i++;
		$maxi++;
		if ($i>3) {
			print "</tr><tr>";
			$i=0;
		}
	}
	//-- - put the birth info in this section
	$birthrec = get_sub_record(1, "1 BIRT", $indirec);
	$deathrec = get_sub_record(1, "1 DEAT", $indirec);
	if (((!empty($birthrec))&&(!FactViewRestricted($pid, $birthrec))&&(ShowFactDetails("BIRT", $pid))) ||
			((!empty($deathrec))&&(!FactViewRestricted($pid, $deathrec))&&(ShowFactDetails("DEAT", $pid))) ||
			$SHOW_LDS_AT_GLANCE) {
		print "<td valign=\"top\"";
		if ($i<$maxi) print "colspan=\"".($maxi-$i)."\"";
		print ">";
		if (!empty($birthrec)&&(!FactViewRestricted($pid, $birthrec))&&(ShowFactDetails("BIRT", $pid))) {
			print "<span class=\"label\">" . (isset($pgv_lang["BIRT"]) ? $pgv_lang["BIRT"] : $factarray["BIRT"] ) . "</span> ";
			print "<span class=\"field\">";
			print_fact_date($birthrec);
			print_fact_place($birthrec);
			print "</span><br />\n";
		}
		if (!empty($deathrec)&&(!FactViewRestricted($pid, $deathrec))&&(ShowFactDetails("DEAT", $pid))) {
			print "<span class=\"label\">" . (isset($pgv_lang["DEAT"]) ? $pgv_lang["DEAT"] : $factarray["DEAT"] ) . "</span> ";
			print "<span class=\"field\">";
			print_fact_date($deathrec);
			print_fact_place($deathrec);
			print "</span><br />\n";
		}
		if ($SHOW_LDS_AT_GLANCE) print "<b>".get_lds_glance($indirec)."</b>";
		print "</td>";
	}
	print "</tr></table>\n";
	if($SHOW_COUNTER)
	{
		//print indi counter only if displaying a non-private person
		require("hitcount.php");
		print "\n<br />".$pgv_lang["hit_count"]."	".$hits."\n";
	}
}

$visibility = "visible";
$position = "relative";
$display = "block";
if ($view!="preview") {
	$visibility = "hidden";
	$position = "absolute";
	$display = "none";
	print "\n\t\t</td><td ";
	if ($TEXT_DIRECTION=="rtl") print "align=\"left\"";
	else print "align=\"right\"";
	print " valign=\"top\"> \n";
	print '<table class="sublinks_table" cellspacing="4" cellpadding="0">';
	print '    <tr>';
	print '        <td class="list_label '.$TEXT_DIRECTION.'" colspan="4">'.$pgv_lang["indis_charts"].'</td></tr>';
	print '    <tr>';
	print '        <td class="sublinks_cell '.$TEXT_DIRECTION.'">';
	//-- charts menu

	if ($TEXT_DIRECTION=="rtl") $ff="_rtl";
	else $ff="";
	$menu = array();
	$menu["label"] = $pgv_lang["charts"];
	$menu["labelpos"] = "right";
	if (!empty($PGV_IMAGES["pedigree"]["small"]))
		$menu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["small"];
	$menu["link"] = "pedigree.php?rootid=$pid";
	$menu["class"] = "submenuitem$ff";
	$menu["hoverclass"] = "submenuitem_hover$ff";
	$menu["flyout"] = "down";
	$menu["submenuclass"] = "submenu$ff";
	$menu["items"] = array();

	$submenu = array();
	$submenu["label"] = $pgv_lang["pedigree_chart"];
	$submenu["labelpos"] = "right";
	if (!empty($PGV_IMAGES["pedigree"]["small"]))
		$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["small"];
	$submenu["link"] = "pedigree.php?rootid=$pid";
	$submenu["class"] = "submenuitem$ff";
	$submenu["hoverclass"] = "submenuitem_hover$ff";
	$menu["items"][] = $submenu;

	$submenu = array();
	$submenu["label"] = $pgv_lang["descend_chart"];
	$submenu["labelpos"] = "right";
	if (!empty($PGV_IMAGES["descendant"]["small"]))
		$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["descendant"]["small"];
	$submenu["link"] = "descendancy.php?pid=$pid";
	$submenu["class"] = "submenuitem$ff";
	$submenu["hoverclass"] = "submenuitem_hover$ff";
	$menu["items"][] = $submenu;

	$submenu = array();
	$submenu["label"] = $pgv_lang["timeline_chart"];
	$submenu["labelpos"] = "right";
	if (!empty($PGV_IMAGES["timeline"]["small"]))
		$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["timeline"]["small"];
	$submenu["link"] = "timeline.php?pids[]=$pid";
	$submenu["class"] = "submenuitem$ff";
	$submenu["hoverclass"] = "submenuitem_hover$ff";
	$menu["items"][] = $submenu;

	$username = getUserName();
	if (!empty($username)) {
		$my_id=get_user_gedcom_setting($username, $GEDCOM, 'gedcomid');
		if ($my_id) {
			$submenu = array();
			$submenu["label"] = $pgv_lang["relationship_to_me"];
			$submenu["labelpos"] = "right";
			if (!empty($PGV_IMAGES["relationship"]["small"]))
				$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["relationship"]["small"];
			$submenu["link"] = "relationship.php?pid1={$my_id}&amp;pid2={$pid}";
			$submenu["class"] = "submenuitem$ff";
			$submenu["hoverclass"] = "submenuitem_hover$ff";
			$menu["items"][] = $submenu;
		}
	}

	if (file_exists("ancestry.php")) {
		$submenu = array();
		$submenu["label"] = $pgv_lang["ancestry_chart"];
		$submenu["labelpos"] = "right";
		if (!empty($PGV_IMAGES["ancestry"]["small"]))
			$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["ancestry"]["small"];
		$submenu["link"] = "ancestry.php?rootid=$pid";
		$submenu["class"] = "submenuitem$ff";
		$submenu["hoverclass"] = "submenuitem_hover$ff";
		$menu["items"][] = $submenu;
	}

	if (file_exists("fanchart.php") and function_exists("imagecreate")) {
		$submenu = array();
		$submenu["label"] = $pgv_lang["fan_chart"];
		$submenu["labelpos"] = "right";
		if (!empty($PGV_IMAGES["fanchart"]["small"]))
			$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["fanchart"]["small"];
		$submenu["link"] = "fanchart.php?rootid=$pid";
		$submenu["class"] = "submenuitem$ff";
		$submenu["hoverclass"] = "submenuitem_hover$ff";
		$menu["items"][] = $submenu;
	}
	if (file_exists("hourglass.php")) {
		$submenu = array();
		$submenu["label"] = $pgv_lang["hourglass_chart"];
		$submenu["labelpos"] = "right";
		if (!empty($PGV_IMAGES["hourglass"]["small"]))
			$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["hourglass"]["small"];
		$submenu["link"] = "hourglass.php?pid=$pid";
		$submenu["class"] = "submenuitem$ff";
		$submenu["hoverclass"] = "submenuitem_hover$ff";
		$menu["items"][] = $submenu;
	}
	print_menu($menu);

	if (file_exists("reports/individual.xml")) {
		print '        </td><td class="sublinks_cell '.$TEXT_DIRECTION.'">';

		$menu = array();
		$menu["label"] = $pgv_lang["reports"];
		$menu["labelpos"] = "right";
		If ($THEME_DIR != $PGV_BASE_DIRECTORY."themes/minimal/" && $THEME_DIR != $PGV_BASE_DIRECTORY."themes/simplygreen/")
			$menu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["reports"]["small"];
		$menu["link"] = "reportengine.php?action=setup&amp;report=reports/individual.xml&amp;pid=$pid";
		$menu["class"] = "submenuitem$ff";
		$menu["hoverclass"] = "submenuitem_hover$ff";
		$menu["flyout"] = "down";
		$menu["submenuclass"] = "submenu$ff";
		$menu["items"] = array();

		$submenu = array();
		$submenu["label"] = $pgv_lang["individual_report"];
		$submenu["labelpos"] = "right";
 		if (!empty($PGV_IMAGES["reports"]["small"]))
			$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["reports"]["small"];
		$submenu["link"] = "reportengine.php?action=setup&amp;report=reports/individual.xml&amp;pid=$pid";
		$submenu["class"] = "submenuitem$ff";
		$submenu["hoverclass"] = "submenuitem_hover$ff";
		$menu["items"][] = $submenu;

	print_menu($menu);
	}

	//-- only allow editors or users who are editing their own individual or their immediate relatives
	$uname = getUserName();
	$canedit = userCanEdit();
	if (!$canedit) {
		$my_id=get_user_gedcom_setting($uname, $GEDCOM, 'gedcomid');
		$famids = pgv_array_merge(find_sfamily_ids($my_id), find_family_ids($my_id));
		$related=false;
		foreach ($famids as $famid) {
			if (!isset($pgv_changes[$famid."_".$GEDCOM])) $famrec = find_family_record($famid);
			else $famrec = find_updated_record($famid);
			if (preg_match("/1 (HUSB|WIFE|CHIL) @$pid@/", $famrec)) {
				$canedit=true;
				break;
			}
		}
	}

	if ($canedit&&($view!="preview")&&($disp)) {
		print "</td>\n";
		print '        <td class="sublinks_cell '.$TEXT_DIRECTION.'">';
		//-- charts menu
		$menu = array();
		$menu["label"] = $pgv_lang["edit"];
		$menu["labelpos"] = "right";
		if (!empty($PGV_IMAGES["edit_indi"]["small"]))
			$menu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["edit_indi"]["small"];
		$menu["link"] = "";
		$menu["onclick"] = "return quickEdit('".$pid."');";
		$menu["class"] = "submenuitem$ff";
		$menu["hoverclass"] = "submenuitem_hover$ff";
		$menu["flyout"] = "down";
		$menu["submenuclass"] = "submenu$ff";
		$menu["items"] = array();
		$submenu = array();
		$submenu["label"] = $pgv_lang["quick_update_title"];
		$submenu["labelpos"] = "right";
		$submenu["icon"] = "";
		$submenu["link"] = "#";
		$submenu["onclick"] = "return quickEdit('".$pid."');";
		$submenu["class"] = "submenuitem$ff";
		$submenu["hoverclass"] = "submenuitem_hover$ff";
		$menu["items"][] = $submenu;
		if (userCanEdit($uname)) {
			$submenu = array();
			$submenu["label"] = $pgv_lang["edit_raw"];
			$submenu["labelpos"] = "right";
			$submenu["icon"] = "";
			$submenu["link"] = "#";
			$submenu["onclick"] = "return edit_raw('$pid');";
			$submenu["class"] = "submenuitem$ff";
			$submenu["hoverclass"] = "submenuitem_hover$ff";
			$menu["items"][] = $submenu;
			if (preg_match_all("/1 FAMS/", $indirec, $match)>1) {
				$submenu = array();
				$submenu["label"] = $pgv_lang["reorder_families"];
				$submenu["labelpos"] = "right";
				$submenu["icon"] = "";
				$submenu["link"] = "#";
				$submenu["onclick"] = "return reorder_families('$pid');";
				$submenu["class"] = "submenuitem$ff";
				$submenu["hoverclass"] = "submenuitem_hover$ff";
				$menu["items"][] = $submenu;
			}
			$submenu = array();
			$submenu["label"] = $pgv_lang["delete_person"];
			$submenu["labelpos"] = "right";
			$submenu["icon"] = "";
			$submenu["link"] = "#";
			$submenu["onclick"] = "return deleteperson('$pid');";
			$submenu["class"] = "submenuitem$ff";
			$submenu["hoverclass"] = "submenuitem_hover$ff";
			$menu["items"][] = $submenu;
			$menu["items"][] = "separator";
			if ($TOTAL_NAMES<2) {
				$submenu = array();
				$submenu["label"] = $pgv_lang["edit_name"];
				$submenu["labelpos"] = "right";
				$submenu["icon"] = "";
				$submenu["link"] = "#";
				$submenu["onclick"] = "return edit_name('$pid', $NAME_LINENUM);";
				$submenu["class"] = "submenuitem$ff";
				$submenu["hoverclass"] = "submenuitem_hover$ff";
				$menu["items"][] = $submenu;
			}
			$submenu = array();
			$submenu["label"] = $pgv_lang["add_name"];
			$submenu["labelpos"] = "right";
			$submenu["icon"] = "";
			$submenu["link"] = "#";
			$submenu["onclick"] = "return add_name('$pid');";
			$submenu["class"] = "submenuitem$ff";
			$submenu["hoverclass"] = "submenuitem_hover$ff";
			$menu["items"][] = $submenu;
			if ($SEX_COUNT<2) {
				$submenu = array();
				$submenu["label"] = $pgv_lang["edit"]." ".$pgv_lang["sex"];
				$submenu["labelpos"] = "right";
				$submenu["icon"] = "";
				$submenu["link"] = "#";
				if ($SEX_LINENUM=="new") $submenu["onclick"] = "return add_new_record('$pid', 'SEX');";
			    else $submenu["onclick"] = "return edit_record('$pid', $SEX_LINENUM);";
				$submenu["class"] = "submenuitem$ff";
				$submenu["hoverclass"] = "submenuitem_hover$ff";
				$menu["items"][] = $submenu;
			}
		}
		if (isset($pgv_changes[$pid."_".$GEDCOM])) {
			$menu["items"][] = "separator";
			$submenu = array();
			$submenu["labelpos"] = "right";
			$submenu["icon"] = "";
			if ($show_changes=="no") {
				$submenu["label"] = $pgv_lang["show_changes"];
				$submenu["link"] = "individual.php?pid=$pid&amp;show_changes=yes";
			}
			else {
				$submenu["label"] = $pgv_lang["hide_changes"];
				$submenu["link"] = "individual.php?pid=$pid&amp;show_changes=no";
			}
			$submenu["class"] = "submenuitem$ff";
			$submenu["hoverclass"] = "submenuitem_hover$ff";
			$menu["items"][] = $submenu;
			if (PGV_USER_CAN_ACCEPT) {
				$submenu = array();
				$submenu["label"] = $pgv_lang["accept_all"];
				$submenu["labelpos"] = "right";
				$submenu["icon"] = "";
				if ($PGV_DATABASE!="index") $submenu["link"] = "individual.php?pid=$pid&amp;action=accept";
				else {
					$submenu["link"] = "#";
					$submenu["onclick"] = "window.open('edit_changes.php','','width=600,height=600,resizable=1,scrollbars=1'); return false;";
				}
				$submenu["class"] = "submenuitem$ff";
				$submenu["hoverclass"] = "submenuitem_hover$ff";
				$menu["items"][] = $submenu;
			}
		}
		print_menu($menu);
	}

	if ($disp && ($SHOW_GEDCOM_RECORD || $ENABLE_CLIPPINGS_CART>=PGV_USER_ACCESS_LEVEL)) {
		print '        </td><td class="sublinks_cell '.$TEXT_DIRECTION.'">';
		$menu = array();
		$menu["label"] = $pgv_lang["other"];
		$menu["labelpos"] = "right";
		if ($SHOW_GEDCOM_RECORD) {
			if (!empty($PGV_IMAGES["gedcom"]["small"]))
				$menu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"];
				if ($show_changes=="yes"  && userCanEdit()) $menu["link"] = "javascript:show_gedcom_record('new');";
				else $menu["link"] = "javascript:show_gedcom_record();";
		}
		else {
			if (!empty($PGV_IMAGES["clippings"]["small"]))
				$menu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["clippings"]["small"];
				$menu["link"] = "clippings.php?action=add&amp;id=$pid&amp;type=indi";
		}
		$menu["class"] = "submenuitem$ff";
		$menu["hoverclass"] = "submenuitem_hover$ff";
		$menu["flyout"] = "down";
		$menu["submenuclass"] = "submenu$ff";
		$menu["items"] = array();


		if ($disp && $SHOW_GEDCOM_RECORD) {

			$submenu = array();
			$submenu["label"] = $pgv_lang["view_gedcom"];
			$submenu["labelpos"] = "right";
			if (!empty($PGV_IMAGES["gedcom"]["small"]))
				$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"];
			if ($show_changes=="yes"  && userCanEdit()) $submenu["link"] = "javascript:show_gedcom_record('new');";
			else $submenu["link"] = "javascript:show_gedcom_record();";
			$submenu["class"] = "submenuitem$ff";
			$submenu["hoverclass"] = "submenuitem_hover$ff";
			$menu["items"][] = $submenu;
		}
		if ($disp && $ENABLE_CLIPPINGS_CART>=PGV_USER_ACCESS_LEVEL) {
			$submenu = array();
			$submenu["label"] = $pgv_lang["add_to_cart"];
			$submenu["labelpos"] = "right";
			if (!empty($PGV_IMAGES["clippings"]["small"]))
				$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["clippings"]["small"];
			$submenu["link"] = "clippings.php?action=add&amp;id=$pid&amp;type=indi";
			$submenu["class"] = "submenuitem$ff";
			$submenu["hoverclass"] = "submenuitem_hover$ff";
			$menu["items"][] = $submenu;
		}

		if ($disp && !empty($uname)) {
			$submenu = array();
			$submenu["label"] = $pgv_lang["add_to_my_favorites"];
			$submenu["labelpos"] = "right";
			if (!empty($PGV_IMAGES["gedcom"]["small"]))
				$submenu["icon"] = $PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"];
			$submenu["link"] = "individual.php?action=addfav&amp;pid=$pid&amp;gid=$pid";
			$submenu["class"] = "submenuitem$ff";
			$submenu["hoverclass"] = "submenuitem_hover$ff";
			$menu["items"][] = $submenu;
		}

		print_menu($menu);
	}

	print "</td></tr></table><br />\n";
}
?>
</td>
<td width="10"><br /></td>
</tr><tr><td valign="bottom" colspan="2">
<script language="JavaScript" type="text/javascript">
<!--
// javascript function to open a window with the raw gedcom in it
function show_gedcom_record(shownew) {
	fromfile="";
	if (shownew=="yes") fromfile='&fromfile=1';
	var recwin = window.open("gedrecord.php?pid=<?php print $pid; ?>"+fromfile, "", "top=50,left=50,width=300,height=400,scrollbars=1,scrollable=1,resizable=1");
}
function resize_content_div(i) {
	 // check for container ..
	var cont = document.getElementById("content");
	if (!cont) cont = document.getElementById("container");
	if (cont) {
		if (document.getElementById("marker"+i)) {
			var y = getAbsoluteTop("marker"+i);
			if (y<300) y=600;
			cont.style.height =y.toString()+'px';
		}
	}
}
function getAbsoluteTop(objectId)
{
		o = document.getElementById(objectId)
		oTop = o.offsetTop
				while(o.offsetParent!=null)
				{
						oParent = o.offsetParent
						oTop += oParent.offsetTop
						o = oParent
				}
		return oTop
}

var tabstyles = new Array();
tabstyles[0] = "tab_cell_inactive";
tabstyles[1] = "tab_cell_inactive";
tabstyles[2] = "tab_cell_inactive";
tabstyles[3] = "tab_cell_inactive";
tabstyles[4] = "tab_cell_inactive";
tabstyles[5] = "tab_cell_inactive";
tabstyles[6] = "tab_cell_inactive";

var lasttab = "";

   function switch_tab(tab) {
	   lasttab = tab;
		  var tab0=document.getElementById("facts");
		  var tab1=document.getElementById("notes");
		  var tab2=document.getElementById("sources");
		  var tab3=document.getElementById("media");
		  var tab4=document.getElementById("relatives");
		  var tab5=document.getElementById("googlemap");
		  var tab6=document.getElementById("researchlog");
		  var pagetab0=document.getElementById("pagetab0");
		  var pagetab1=document.getElementById("pagetab1");
		  var pagetab2=document.getElementById("pagetab2");
		  var pagetab3=document.getElementById("pagetab3");
		  var pagetab4=document.getElementById("pagetab4");
		  var pagetab5=document.getElementById("pagetab5");
		  var pagetab6=document.getElementById("pagetab6");
		  var pagetab0bottom=document.getElementById("pagetab0bottom");
		  var pagetab1bottom=document.getElementById("pagetab1bottom");
		  var pagetab2bottom=document.getElementById("pagetab2bottom");
		  var pagetab3bottom=document.getElementById("pagetab3bottom");
		  var pagetab4bottom=document.getElementById("pagetab4bottom");
		  var pagetab5bottom=document.getElementById("pagetab5bottom");
		  var pagetab6bottom=document.getElementById("pagetab6bottom");
		  if (tab==0) {
				 MM_showHideLayers('facts', ' ', 'show',' ');
				 MM_showHideLayers('notes', ' ', 'hide',' ');
				 MM_showHideLayers('sources', ' ', 'hide',' ');
				 MM_showHideLayers('media', ' ', 'hide',' ');
				 MM_showHideLayers('relatives', ' ', 'hide',' ');
				 MM_showHideLayers('googlemap', ' ','hide',' ');
				 MM_showHideLayers('researchlog', ' ','hide',' ');
				 tab0.style.display='block';
				 tab1.style.display='none';
				 if (tab2) tab2.style.display='none';
				 tab3.style.display='none';
				 tab4.style.display='none';
				 if (tab5) tab5.style.display='none';
				 if (tab6) tab6.style.display='none';
				 pagetab0.className='tab_cell_active';
				 pagetab1.className=tabstyles[1];
				 if (tab2) pagetab2.className=tabstyles[2];
				 if (pagetab3)	pagetab3.className=tabstyles[3];
				 pagetab4.className=tabstyles[4];
				 if (pagetab5) pagetab5.className=tabstyles[5];
				 if (pagetab6) pagetab6.className=tabstyles[6];
				 pagetab0bottom.className='tab_active_bottom';
				 pagetab1bottom.className='tab_inactive_bottom';
				 if (tab2) pagetab2bottom.className='tab_inactive_bottom';
				 if (pagetab3)	pagetab3bottom.className='tab_inactive_bottom';
				 pagetab4bottom.className='tab_inactive_bottom';
				 if (pagetab5)	pagetab5bottom.className='tab_inactive_bottom';
				 if (pagetab6)	pagetab6bottom.className='tab_inactive_bottom';
		  }
		  else if (tab==1) {
				 MM_showHideLayers('facts', ' ', 'hide',' ');
				 MM_showHideLayers('notes', ' ', 'show',' ');
				 MM_showHideLayers('sources', ' ', 'hide',' ');
				 MM_showHideLayers('media', ' ', 'hide',' ');
				 MM_showHideLayers('relatives', ' ', 'hide',' ');
				 MM_showHideLayers('googlemap', ' ','hide',' ');
				 MM_showHideLayers('researchlog', ' ','hide',' ');
				 tab0.style.display='none';
				 tab1.style.display='block';
				 if (tab2) tab2.style.display='none';
				 tab3.style.display='none';
				 tab4.style.display='none';
				 if (tab5) tab5.style.display='none';
				 if (tab6) tab6.style.display='none';
				 pagetab0.className=tabstyles[0];
				 pagetab1.className='tab_cell_active';
				 if (tab2) pagetab2.className=tabstyles[2];
				 if (pagetab3)	pagetab3.className=tabstyles[3];
				 pagetab4.className=tabstyles[4];
				 if (pagetab5) pagetab5.className=tabstyles[5];
				 if (pagetab6) pagetab6.className=tabstyles[6];
				 pagetab0bottom.className='tab_inactive_bottom';
				 pagetab1bottom.className='tab_active_bottom';
				 if (tab2) pagetab2bottom.className='tab_inactive_bottom';
				 if (pagetab3)	pagetab3bottom.className='tab_inactive_bottom';
				 pagetab4bottom.className='tab_inactive_bottom';
				 if (pagetab5) pagetab5bottom.className='tab_inactive_bottom';
				 if (pagetab6) pagetab6bottom.className='tab_inactive_bottom';
		  }
		  else if (tab==2) {
				 MM_showHideLayers('facts', ' ', 'hide',' ');
				 MM_showHideLayers('notes', ' ', 'hide',' ');
				 MM_showHideLayers('sources', ' ', 'show',' ');
				 MM_showHideLayers('media', ' ', 'hide',' ');
				 MM_showHideLayers('relatives', ' ', 'hide',' ');
				 MM_showHideLayers('googlemap', ' ','hide',' ');
				 MM_showHideLayers('researchlog', ' ','hide',' ');
				 tab0.style.display='none';
				 tab1.style.display='none';
				 if (tab2) tab2.style.display='block';
				 tab3.style.display='none';
				 tab4.style.display='none';
				 if (tab5) tab5.style.display='none';
				 if (tab6) tab6.style.display='none';
				 pagetab0.className=tabstyles[0];
				 pagetab1.className=tabstyles[1];
				 if (tab2) pagetab2.className='tab_cell_active';
				 if (pagetab3)	pagetab3.className=tabstyles[3];
				 pagetab4.className=tabstyles[4];
				 if (pagetab5) pagetab5.className=tabstyles[5];
				 if (pagetab6) pagetab6.className=tabstyles[6];
				 pagetab0bottom.className='tab_inactive_bottom';
				 pagetab1bottom.className='tab_inactive_bottom';
				 if (tab2) pagetab2bottom.className='tab_active_bottom';
				 if (pagetab3)	pagetab3bottom.className='tab_inactive_bottom';
				 pagetab4bottom.className='tab_inactive_bottom';
				 if (pagetab5) pagetab5bottom.className='tab_inactive_bottom';
				 if (pagetab6) pagetab6bottom.className='tab_inactive_bottom';
		  }
		  else if (tab==3) {
				 MM_showHideLayers('facts', ' ', 'hide',' ');
				 MM_showHideLayers('notes', ' ', 'hide',' ');
				 MM_showHideLayers('sources', ' ', 'hide',' ');
				 MM_showHideLayers('media', ' ', 'show',' ');
				 MM_showHideLayers('relatives', ' ', 'hide',' ');
				 MM_showHideLayers('googlemap', ' ','hide',' ');
				 MM_showHideLayers('researchlog', ' ','hide',' ');
				 tab0.style.display='none';
				 tab1.style.display='none';
				 if (tab2) tab2.style.display='none';
				 tab3.style.display='block';
				 tab4.style.display='none';
				 if (tab5) tab5.style.display='none';
				 if (tab6) tab6.style.display='none';
				 pagetab0.className=tabstyles[0];
				 pagetab1.className=tabstyles[1];
				 if (tab2) pagetab2.className=tabstyles[2];
				 if (pagetab3)	pagetab3.className='tab_cell_active';
				 pagetab4.className=tabstyles[4];
				 if (pagetab5) pagetab5.className=tabstyles[5];
				 if (pagetab6) pagetab6.className=tabstyles[6];
				 pagetab0bottom.className='tab_inactive_bottom';
				 pagetab1bottom.className='tab_inactive_bottom';
				 if (tab2) pagetab2bottom.className='tab_inactive_bottom';
				 if (pagetab3)	pagetab3bottom.className='tab_active_bottom';
				 pagetab4bottom.className='tab_inactive_bottom';
				 if (pagetab5) pagetab5bottom.className='tab_inactive_bottom';
				 if (pagetab6) pagetab6bottom.className='tab_inactive_bottom';
		  }
		  else if (tab==4) {
				 MM_showHideLayers('facts', ' ', 'hide',' ');
				 MM_showHideLayers('notes', ' ', 'hide',' ');
				 MM_showHideLayers('sources', ' ', 'hide',' ');
				 MM_showHideLayers('media', ' ', 'hide',' ');
				 MM_showHideLayers('relatives', ' ', 'show',' ');
				 MM_showHideLayers('googlemap', ' ','hide',' ');
				 MM_showHideLayers('researchlog', ' ','hide',' ');
				 tab0.style.display='none';
				 tab1.style.display='none';
				 if (tab2) tab2.style.display='none';
				 tab3.style.display='none';
				 tab4.style.display='block';
				 if (tab5) tab5.style.display='none';
				 if (tab6) tab6.style.display='none';
				 pagetab0.className=tabstyles[0];
				 pagetab1.className=tabstyles[1];
				 if (tab2) pagetab2.className=tabstyles[2];
				 if (pagetab3)	pagetab3.className=tabstyles[3];
				 pagetab4.className='tab_cell_active';
				 if (pagetab5) pagetab5.className=tabstyles[5];
				 if (pagetab6) pagetab6.className=tabstyles[6];
				 pagetab0bottom.className='tab_inactive_bottom';
				 pagetab1bottom.className='tab_inactive_bottom';
				 if (tab2) pagetab2bottom.className='tab_inactive_bottom';
				 if (pagetab3)	pagetab3bottom.className='tab_inactive_bottom';
				 pagetab4bottom.className='tab_active_bottom';
				 if (pagetab5) pagetab5bottom.className='tab_inactive_bottom';
				 if (pagetab6) pagetab6bottom.className='tab_inactive_bottom';
		  }
		 else if (tab==5) {
				MM_showHideLayers('facts', ' ', 'hide',' ');
				MM_showHideLayers('notes', ' ', 'hide',' ');
				MM_showHideLayers('sources', ' ', 'hide',' ');
				MM_showHideLayers('media', ' ', 'hide',' ');
				MM_showHideLayers('relatives', ' ', 'hide',' ');
				MM_showHideLayers('googlemap', ' ','show',' ');
				MM_showHideLayers('researchlog', ' ','hide',' ');
				tab0.style.display='none';
				tab1.style.display='none';
				if (tab2) tab2.style.display='none';
				tab3.style.display='none';
				tab4.style.display='none';
				if (tab5) {
                    tab5.style.display='block';
                    ResizeMap();
                }
				if (tab6) tab6.style.display='none';
				pagetab0.className=tabstyles[0];
				pagetab1.className=tabstyles[1];
				if (tab2) pagetab2.className=tabstyles[2];
				if (pagetab3)	pagetab3.className=tabstyles[3];
				pagetab4.className=tabstyles[4];
				if (pagetab5) pagetab5.className='tab_cell_active';
				if (pagetab6) pagetab6.className=tabstyles[6];
				pagetab0bottom.className='tab_inactive_bottom';
				pagetab1bottom.className='tab_inactive_bottom';
				if (tab2) pagetab2bottom.className='tab_inactive_bottom';
				if (pagetab3)  pagetab3bottom.className='tab_inactive_bottom';
				pagetab4bottom.className='tab_inactive_bottom';
				if (pagetab5) pagetab5bottom.className='tab_active_bottom';
				if (pagetab6) pagetab6bottom.className='tab_inactive_bottom';
		  }
		 else if (tab==6) {
				MM_showHideLayers('facts', ' ', 'hide',' ');
				MM_showHideLayers('notes', ' ', 'hide',' ');
				MM_showHideLayers('sources', ' ', 'hide',' ');
				MM_showHideLayers('media', ' ', 'hide',' ');
				MM_showHideLayers('relatives', ' ', 'hide',' ');
				MM_showHideLayers('googlemap', ' ','hide',' ');
				MM_showHideLayers('researchlog', ' ','show',' ');
				tab0.style.display='none';
				tab1.style.display='none';
				if (tab2) tab2.style.display='none';
				tab3.style.display='none';
				tab4.style.display='none';
				if (tab5) tab5.style.display='none';
				if (tab6) tab6.style.display='block';
				pagetab0.className=tabstyles[0];
				pagetab1.className=tabstyles[1];
				if (tab2) pagetab2.className=tabstyles[2];
				if (pagetab3)	pagetab3.className=tabstyles[3];
				pagetab4.className=tabstyles[4];
				if (pagetab5) pagetab5.className=tabstyles[5];
				if (pagetab6) pagetab6.className='tab_cell_active';
				pagetab0bottom.className='tab_inactive_bottom';
				pagetab1bottom.className='tab_inactive_bottom';
				if (tab2) pagetab2bottom.className='tab_inactive_bottom';
				if (pagetab3)  pagetab3bottom.className='tab_inactive_bottom';
				pagetab4bottom.className='tab_inactive_bottom';
				if (pagetab5) pagetab5bottom.className='tab_inactive_bottom';
				if (pagetab6) pagetab6bottom.className='tab_active_bottom';
		}
		   resize_content_div(tab+1);
		  return false;
   }

   function showchanges() {
		  window.location = '<?php print $PHP_SELF."?pid=$pid&show_changes=yes"; ?>';
   }
   //-->
</script>
<?php
if ($view!="preview") {
?>
<table class="tabs_table" cellspacing="0">
   <tr>
		  <td id="pagetab0" class="tab_cell_active" onclick="return switch_tab(0);"><a href="#" onclick="return switch_tab(0);"><?php print $pgv_lang["personal_facts"]?></a></td>
		  <td id="pagetab1" class="tab_cell_inactive" onclick="return switch_tab(1);"><a href="#" onclick="return switch_tab(1);"><?php print $pgv_lang["notes"]?></a></td>
		  <?php if ($SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) { ?>
			<td id="pagetab2" class="tab_cell_inactive" onclick="return switch_tab(2);"><a href="#" onclick="return switch_tab(2);"><?php print $pgv_lang["ssourcess"]?></a></td>
		  <?php }
		  if ($MULTI_MEDIA == TRUE){?>
			<td id="pagetab3" class="tab_cell_inactive" onclick="return switch_tab(3);"><a href="#" onclick="return switch_tab(3);"><?php print $pgv_lang["media"]?></a></td>
		  <?php }?>
		  <td id="pagetab4" class="tab_cell_inactive" onclick="return switch_tab(4);"><a href="#" onclick="return switch_tab(4);"><?php print $pgv_lang["relatives"]?></a></td>
		  <?php if (file_exists("modules/googlemap.php")) { ?>
		  <td id="pagetab5" class="tab_cell_inactive" onclick="return switch_tab(5);"><a href="#" onclick="return switch_tab(5);"><?php print $pgv_lang["googlemap"]?></a></td>
		  <?php } ?>
		  <?php if (file_exists("modules/researchlog.php") && ($SHOW_RESEARCH_LOG>=PGV_USER_ACCESS_LEVEL)) { ?>
		  <td id="pagetab6" class="tab_cell_inactive" onclick="return switch_tab(6);"><a href="#" onclick="return switch_tab(6);"><?php print $pgv_lang["research_log"]?></a></td>
		  <?php } ?>
		</tr>
		<tr>
		  <td id="pagetab0bottom" class="tab_active_bottom"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" /></td>
		  <td id="pagetab1bottom" class="tab_inactive_bottom"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" /></td>
		  <?php if ($SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) { ?>
		  <td id="pagetab2bottom" class="tab_inactive_bottom"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" /></td>
		  <?php } if ($MULTI_MEDIA == TRUE){?>
		  <td id="pagetab3bottom" class="tab_inactive_bottom"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" /></td>
		  <?php }?>
		  <td id="pagetab4bottom" class="tab_inactive_bottom"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" /></td>
          <?php if (file_exists("modules/googlemap.php")) { ?>
		  <td id="pagetab5bottom" class="tab_inactive_bottom"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" /></td>
		  <?php }?>
		  <?php if (file_exists("modules/researchlog.php") && ($SHOW_RESEARCH_LOG>=PGV_USER_ACCESS_LEVEL)) { ?>
		  <td id="pagetab6bottom" class="tab_inactive_bottom"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" /></td>
		  <?php }?>
		  <td class="tab_inactive_bottom_right"><img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]; ?>" width="1" height="1" /></td>
   </tr>
</table>

<?php
}
print "</td></tr></table>\n";
//--------------------------------Start 1st tab individual page
//--- Personal Facts and Details
print "\n\t<div id=\"facts\" class=\"tab_page\" style=\"position: $position; display: block; top: auto; left: auto; visibility: visible; z-index: 1; \">\n\t";
if ($view=="preview") print "<span class=\"subheaders\">".$pgv_lang["personal_facts"]."</span>";
//-- sort the facts
usort($indifacts, "compare_facts");

print "\n\t<table class=\"facts_table\">";
if (!$disp) {
   print "<tr><td class=\"facts_value\" colspan=\"2\">";
   print_privacy_error($CONTACT_EMAIL);
   print "</td></tr>";
}
else {
		if (count($indifacts)==0) print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_tab1"]."<script language=\"JavaScript\" type=\"text/javascript\">tabstyles[0]='tab_cell_inactive_empty'; document.getElementById('pagetab0').className='tab_cell_inactive_empty';</script></td></tr>\n";
		$yetdied=false;
		foreach ($indifacts as $key => $value) {
			if (stristr($value[1], "1 DEAT")) $yetdied=true;
			if (preg_match("/PGV_FAMILY_ID: (.*)/", $value[1], $match)>0) {
				// do not show family events after death
				if (!$yetdied) {
					print_fact($value[1],trim($match[1]),$value[0], $indirec);
				}
			}
			else print_fact($value[1],$pid,$value[0], $indirec);
			$FACT_COUNT++;
		}
}
//-- new fact link
if (($view!="preview") &&(userCanEdit())&&($disp)) {
	$addfacts = array("BIRT","CHR","DEAT","BURI","CREM","ADOP","BAPM","BARM","BASM","BLES","CHRA","CONF","FCOM","ORDN","NATU","EMIG","IMMI","CENS","PROB","WILL","GRAD","RETI","CAST","DSCR","EDUC","IDNO","NATI","NCHI","NMR","OCCU","PROP","RELI","RESI","SSN","TITL","BAPL","CONL","ENDL","SLGC","_MILI");
	//-- the following line needs to be rethought because sometimes you want to add the same fact twice if you have conflicting information (john)
//   	$addfacts = array_merge(CheckFactUnique(array("BIRT","DEAT","BURI","CREM","FCOM","IDNO","NCHI","NMR","RETI","SSN"), $indifacts, "INDI"), array("CHR","ADOP","BAPM","BARM","BASM","BLES","CHRA","CONF","ORDN","NATU","EMIG","IMMI","CENS","PROB","WILL","GRAD","CAST","DSCR","EDUC","NATI","OCCU","PROP","RELI","RESI","TITL","BAPL","CONL","ENDL","SLGC","_MILI"));
	usort($addfacts, "factsort");
	print "<tr><td class=\"facts_label\">".$pgv_lang["add_fact"]."</td>";
	print "<td class=\"facts_value\">";
	print "<form method=\"get\" name=\"newfactform\">\n";
	print "<select id=\"newfact\" name=\"newfact\">\n";
	foreach($addfacts as $indexval => $fact) {
  		print PrintReady("<option value=\"$fact\">".$factarray[$fact]. " [".$fact."]</option>\n");
	}
	print "<option value=\"EVEN\">".$pgv_lang["custom_event"]." [EVEN]</option>\n";
	if (!empty($_SESSION["clipboard"])) {
		foreach($_SESSION["clipboard"] as $key=>$fact) {
			if ($fact["type"]=="INDI") {
				print "<option value=\"clipboard_$key\">".$pgv_lang["add_from_clipboard"]." ".$factarray[$fact["fact"]]."</option>\n";
			}
		}
	}
	print "</select>";
	print "<input type=\"button\" value=\"".$pgv_lang["add"]."\" onclick=\"add_record('$pid', 'newfact');\" />\n";
	print_help_link("add_new_facts_help", "qm");
	print "</form>\n";
	print "</td></tr>\n";
}
print "\n\t</table>\n<br />";
// start
print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" id=\"marker1\" width=\"1\" height=\"1\" alt=\"\"	/>";
// end
print "</div>\n";

//--------------------------------Start 2nd tab individual page
//--- Notes
print "\n\t<div id=\"notes\" class=\"tab_page\" style=\"position: $position; display: $display; top: auto; left: auto; visibility: $visibility; z-index: 2; \">";
if ($view=="preview") print "<span class=\"subheaders\">".$pgv_lang["notes"]."</span>";
print "\n\t<table class=\"facts_table\">";
if (!$disp) {
   print "<tr><td class=\"facts_value\">";
   print_privacy_error($CONTACT_EMAIL);
   print "</td></tr>";
}
else {
		$notecount=0;
		  foreach ($otheritems as $key => $factrec) {
//				 $ft = preg_match("/\d\s(_?\w+)(.*)/", $factrec[1], $match);
				 $ft = preg_match("/\d\s(\w+)(.*)/", $factrec[1], $match);
				 if ($ft>0) $fact = $match[1];
				 else $fact="";
				 $fact = trim($fact);
				 if ($fact=="NOTE") {
						print_main_notes($factrec[1], 1, $pid, $factrec[0]);
						$notecount++;
				 }
				 $FACT_COUNT++;
		  }
   if ($notecount==0) print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_tab2"]."<script language=\"JavaScript\" type=\"text/javascript\">tabstyles[1]='tab_cell_inactive_empty'; document.getElementById('pagetab1').className='tab_cell_inactive_empty';</script></td></tr>\n";
   //-- New Note Link
   if (($view!="preview") && (userCanEdit())&&($disp)) {
		  print "<tr><td class=\"facts_label\">".$pgv_lang["add_note_lbl"]."</td>";
		  print "<td class=\"facts_value\">";
		  print "<a href=\"#\" onclick=\"return add_new_record('$pid','NOTE');\">".$pgv_lang["add_note"]."</a>";
		  print_help_link("add_note_help", "qm");
		  print "<br />\n";
		  print "</td></tr>\n";
   }
}
print "\n\t</table>\n<br />";
// start
print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" id=\"marker2\" width=\"1\" height=\"1\" alt=\"\" />";
// end
print "</div>\n";

//--------------------------------Start 3rd tab individual page
//--- Sources
if ($SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) {
print "\n\t<div id=\"sources\" class=\"tab_page\" style=\"position: $position; display: $display; top: auto; left: auto; visibility: $visibility; z-index: 2; \">";
	if ($view=="preview") print "<span class=\"subheaders\">".$pgv_lang["ssourcess"]."</span>";
	print "\n\t<table class=\"facts_table\">";

	if (!$disp) {
	   print "<tr><td class=\"facts_value\">";
	   print_privacy_error($CONTACT_EMAIL);
	   print "</td></tr>";
	}
	else {
	   $sourcecount = 0;
			  foreach ($otheritems as $key => $factrec) {
					 $ft = preg_match("/\d\s(\w+)(.*)/", $factrec[1], $match);
					 if ($ft>0) $fact = $match[1];
					 else $fact="";
					 $fact = trim($fact);
					 if ($fact=="SOUR") {
							 $sourcecount++;
							print_main_sources($factrec[1], 1, $pid, $factrec[0]);
					 }
					 $FACT_COUNT++;
			  }

	   if ($sourcecount==0) print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_tab3"]."<script language=\"JavaScript\" type=\"text/javascript\">tabstyles[2]='tab_cell_inactive_empty'; document.getElementById('pagetab2').className='tab_cell_inactive_empty';</script></td></tr>\n";
	   //-- New Source Link
	   if (($view!="preview") && (userCanEdit())&&($disp)) {
			  print "<tr><td class=\"facts_label\">".$pgv_lang["add_source_lbl"]."</td>";
	    	  print "<td class=\"facts_value\">";
			  print "<a href=\"#\" onclick=\"return add_new_record('$pid','SOUR');\">".$pgv_lang["add_source"]."</a>";
			  print_help_link("add_source_help", "qm");
			  print "<br />\n";
			  print "</td></tr>\n";
	   }
	}
	print "\n\t</table>\n<br />";
	// start
	print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" id=\"marker3\" width=\"1\" height=\"1\" alt=\"\" />";
	// end
	print "</div>\n";
}

//--------------------------------Start 4th tab individual page
//--- Media
print "\n\t<div id=\"media\" class=\"tab_page\" style=\"position: $position; display: $display; top: auto; left: auto; visibility: $visibility; z-index: 2; \">";
if ($view=="preview") print "<span class=\"subheaders\">".$pgv_lang["media"]."</span>";
print "\n\t<table class=\"facts_table\">";
if (!$disp) {
   print "<tr><td class=\"facts_value\">";
   print_privacy_error($CONTACT_EMAIL);
   print "</td></tr>";
}
else {
   $mediacount = 0;
		  foreach ($otheritems as $key => $factrec) {
//				 $ft = preg_match("/\d\s(_?\w+)(.*)/", $factrec[1], $match);
				 $ft = preg_match("/\d\s(\w+)(.*)/", $factrec[1], $match);
				 if ($ft>0) $fact = $match[1];
				 else $fact="";
				 $fact = trim($fact);
				 if ($fact=="OBJE") {
					if (preg_match("/PGV_FAMILY_ID: (.*)/", $factrec[1], $match)>0) print_main_media($factrec[1], 1, trim($match[1]), $factrec[0]);
		  			else print_main_media($factrec[1], 1, $pid, $factrec[0]);
					$mediacount++;
				 }
				 $FACT_COUNT++;
		  }
   if ($mediacount==0) print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_tab4"]."<script language=\"JavaScript\" type=\"text/javascript\">tabstyles[3]='tab_cell_inactive_empty'; document.getElementById('pagetab3').className='tab_cell_inactive_empty';</script></td></tr>\n";

   //-- New Media link
   if (($view!="preview") && (userCanEdit())&&($disp)) {
		  print "<tr><td class=\"facts_label\">".$pgv_lang["add_media_lbl"]."</td>";
		  print "<td class=\"facts_value\">";
		  print "<a href=\"#\" onclick=\"return add_new_record('$pid','OBJE');\">".$pgv_lang["add_media"]."</a>";
		  print_help_link("add_media_help", "qm");
		  print "<br />\n";
		  print "</td></tr>\n";
   }
}
print "\n\t</table>\n<br />";
// start
print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" id=\"marker4\" width=\"1\" height=\"1\" alt=\"\" />";
// end

print "</div>\n";

//--------------------------------Start 5th tab individual page
//--- Close relatives
print "\n\t<div id=\"relatives\" class=\"tab_page\" style=\"position: $position; display: $display; top: auto; left: auto; visibility: $visibility; z-index: 3; \">";
//-- print the names of the parents
$hfamids = find_family_ids($pid);
if (($view!="preview") && (userCanEdit())&&($disp)) {
	if (($show_changes=="yes")&&(isset($pgv_changes[$pid."_".$GEDCOM]))) {
	   if (empty($newrec)) $newrec = find_record_in_file($pid);
	   $ct = preg_match_all("/1\sFAMC\s@(.*)@/", $newrec, $match, PREG_SET_ORDER);
		for($i=0; $i<$ct; $i++) {
			if (!in_array($match[$i][1], $hfamids)) $hfamids[]=$match[$i][1];
		}
	}
}
$personcount=0;
$hparents=false;
if (count($hfamids)>0) {
	$hparents=false;
	for($j=0; $j<count($hfamids); $j++) {
		if (!empty($hfamids[$j])) {
			$famlink = get_sub_record(1, "1 FAMC @".$hfamids[$j]."@", $indirec);
			print "<table><tr><td><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["cfamily"]["small"]."\" border=\"0\" class=\"icon\" alt=\"\" /></td>";
			print "<td><span class=\"subheaders\">";
			$ft = preg_match("/2 PEDI (.*)/", $famlink, $fmatch);
			if ($ft>0) print $pgv_lang[trim($fmatch[1])]." ";
			print $pgv_lang["as_child"]."</span>";
			if ($view!="preview") {
				print " - <a href=\"family.php?famid=$hfamids[$j]\">[".$pgv_lang["view_family"];
				if ($SHOW_FAM_ID_NUMBERS) print " &lrm;($hfamids[$j])&lrm;";
				print "]</a>";
			}
			print "</td></tr></table>";
			print "\n\t<table class=\"facts_table\">";
			$famrec = find_family_record($hfamids[$j]);
			if (empty($famrec)) $famrec = find_record_in_file($hfamids[$j]);
			$hparents = find_parents_in_record($famrec);
			$newhparents = $hparents;
			//-- check for an updated family record
			if (userCanEdit()&&($disp)) {
				if (($show_changes=="yes")&&(isset($pgv_changes[$hfamids[$j]."_".$GEDCOM]))) {
					$newrec = find_record_in_file($hfamids[$j]);
					$newhparents = find_parents_in_record($newrec);
					$newchil = array();
					$num = preg_match_all("/1\s*CHIL\s*@(.*)@/", $newrec, $smatch,PREG_SET_ORDER);
					for($i=0; $i<$num; $i++) {
							$newchil[] = $smatch[$i][1];
					}
				}
			}
			if ($hparents) {
				$styleadd="";
				//-- check if the father have been updated
				if ($hparents['HUSB']!=$newhparents['HUSB']) {
					$styleadd="red";
					if (!empty($newhparents['HUSB'])) {
						$srec = find_record_in_file($newhparents['HUSB']);
						$isf = "NN";
						if (preg_match("/1 SEX F/", $srec)>0) {
								$label = $pgv_lang["mother"];
								$isf = "F";
						}
						if (preg_match("/1 SEX M/", $srec)>0) {
								$label = $pgv_lang["father"];
								$isf = "";
						}
						if ($isf == "NN") $label = $pgv_lang["parent"];
						print "<tr><td class=\"facts_labelblue\">".$label."</td><td class=\"facts_value, person_box$isf\">";
						print_pedigree_person($newhparents['HUSB'],2,$view!="preview");
						$personcount++;
						print "</td></tr>\n";
					}
				}
				if (!empty($hparents['HUSB'])) {
					$srec = find_person_record($hparents['HUSB']);
					if (empty($srec)) $srec = find_record_in_file($hparents['HUSB']);
					$isf = "NN";
					if (preg_match("/1 SEX F/", $srec)>0) {
							$label = $pgv_lang["mother"];
							$isf = "F";
					}
					if (preg_match("/1 SEX M/", $srec)>0) {
							$label = $pgv_lang["father"];
							$isf = "";
					}
					if ($isf == "NN") $label = $pgv_lang["parent"];
					print "<tr><td class=\"facts_label$styleadd\">".$label."</td><td class=\"facts_value, person_box$isf\">";
					print_pedigree_person($hparents['HUSB'],2,$view!="preview");
					$personcount++;
					print "</td></tr>\n";
				}
				else {
					if (($view!="preview") && (userCanEdit())&&($disp)) {
						print "<tr><td class=\"facts_label$styleadd\">".$pgv_lang["father"]."</td><td class=\"facts_value, person_box\">";
						print "<a href=\"#\" onclick=\"return addnewparentfamily('$pid', 'HUSB', '$hfamids[$j]');\">".$pgv_lang["add_father"]."</a>";
						print_help_link("add_new_parent_help", "qm");
						print "<br /></td></tr>\n";
					}
				}
				$styleadd="";
				if ($hparents['WIFE']!=$newhparents['WIFE']) {
					$styleadd="red";
					if (!empty($newhparents['WIFE'])) {
						$srec = find_record_in_file($newhparents['WIFE']);
						$isf = "NN";
						if (preg_match("/1 SEX F/", $srec)>0) {
								$label = $pgv_lang["mother"];
								$isf = "F";
						}
						if (preg_match("/1 SEX M/", $srec)>0) {
								$label = $pgv_lang["father"];
								$isf = "";
						}
						if ($isf == "NN") $label = $pgv_lang["parent"];
						print "<tr><td class=\"facts_labelblue\">".$label."</td><td class=\"facts_value$styleadd, person_box$isf\">";
						print_pedigree_person($newhparents['WIFE'],2,$view!="preview");
						$personcount++;
						print "</td></tr>\n";
					}
				}
				if (!empty($hparents['WIFE'])) {
					$srec = find_person_record($hparents['WIFE']);
					if (empty($srec)) $srec = find_record_in_file($hparents['WIFE']);
					$isf = "NN";
					if (preg_match("/1 SEX F/", $srec)>0) {
							$label = $pgv_lang["mother"];
							$isf = "F";
					}
					if (preg_match("/1 SEX M/", $srec)>0) {
							$label = $pgv_lang["father"];
							$isf = "";
					}
					if ($isf == "NN") $label = $pgv_lang["parent"];
					print "<tr><td class=\"facts_label$styleadd\">".$label."</td><td class=\"facts_value$styleadd, person_box$isf\">";
					print_pedigree_person($hparents['WIFE'],2,$view!="preview");
					$personcount++;
					print "</td></tr>\n";
				}
				else {
					if (($view!="preview") && (userCanEdit())&&($disp)) {
						print "<tr><td class=\"facts_label$styleadd\">".$pgv_lang["mother"]."</td><td class=\"facts_value, person_box\">";
						print "<a href=\"#\" onclick=\"return addnewparentfamily('$pid', 'WIFE', '$hfamids[$j]');\">".$pgv_lang["add_mother"]."</a>";
						print_help_link("add_new_parent_help", "qm");
						print "<br /></td></tr>\n";
					}
				}
			}
			//-- print the siblings
			$chil = array();
			$num = preg_match_all("/1\s*CHIL\s*@(.*)@/", $famrec, $smatch,PREG_SET_ORDER);
			for($i=0; $i<$num; $i++) {
				$spid = $smatch[$i][1];
				if (strtoupper($spid)!=strtoupper($pid)) {
					$chil[] = $spid;
					$styleadd="";
					if (isset($newchil)&&(!in_array($spid, $newchil))) $styleadd="red";
					$srec = find_person_record($spid);
					if (empty($srec)) $srec = find_record_in_file($spid);
					$isf = "NN";
					if (preg_match("/1 SEX F/", $srec)>0) {
							$label = $pgv_lang["sister"];
							$isf = "F";
					}
					if (preg_match("/1 SEX M/", $srec)>0) {
							$label = $pgv_lang["brother"];
							$isf = "";
					}
					if ($isf == "NN") $label = $pgv_lang["sibling"];
					print "<tr><td class=\"facts_label$styleadd\">".$label."</td><td class=\"facts_value$styleadd, person_box$isf\">";
					print_pedigree_person($spid,2,$view!="preview");
					$personcount++;
					print "</td></tr>\n";
				}
			}
			if (isset($newchil)) {
				for($i=0; $i<count($newchil); $i++) {
					if ((!in_array($newchil[$i], $chil))&&(strtoupper($newchil[$i])!=strtoupper($pid))) {
						$srec = find_record_in_file($newchil[$i]);
						$isf = "NN";
						if (preg_match("/1 SEX F/", $srec)>0) {
								$label = $pgv_lang["sister"];
								$isf = "F";
						}
						if (preg_match("/1 SEX M/", $srec)>0) {
								$label = $pgv_lang["brother"];
								$isf = "";
						}
						if ($isf == "NN") $label = $pgv_lang["sibling"];
						print "<tr><td class=\"facts_labelblue\">".$label."</td><td class=\"facts_valueblue, person_box$isf\">";
						print_pedigree_person($newchil[$i],2,$view!="preview");
						$personcount++;
						print "</td></tr>\n";
					}
				}
			}
			if (($view!="preview") && (userCanEdit())&&($disp)) {
				print "<tr><td class=\"facts_label\">".$pgv_lang["add_child_to_family"]."</td><td class=\"facts_value\"><a href=\"#\" onclick=\"return addnewchild('$hfamids[$j]');\">".$pgv_lang["add_sibling"]."</a>";
				print_help_link("add_sibling_help", "qm");
				print "</td></tr>\n";
			}
			print "\n\t</table>";

			//-- print any step-families
			foreach($hparents as $indexval => $parent) {
			if (!empty($parent)) {
				$parentrec = find_person_record($parent);
				$parentsex = "U";
				$pct = preg_match("/1 SEX (.)/", $parentrec, $pmatch);
				if ($pct>0) $parentsex = $pmatch[1];
				$stepfamids = find_sfamily_ids($parent);
				for($f=0; $f<count($stepfamids); $f++) {
					$num = 0;
					//-- don't show families we already showed before
					if (!in_array($stepfamids[$f], $hfamids)) {
						$famrec = find_family_record($stepfamids[$f]);
						if (empty($famrec)) $famrec = find_record_in_file($stepfamids[$f]);
						$num = preg_match_all("/1\s*CHIL\s*@(.*)@/", $famrec, $smatch,PREG_SET_ORDER);
						//-- don't show families without children
						if ($num>0) {
							$stepparents = find_parents_in_record($famrec);
							print "<table><tr><td><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["cfamily"]["small"]."\" border=\"0\" class=\"icon\" alt=\"\" /></td>";
							print "<td><span class=\"subheaders\">";
							$ft = preg_match("/2 PEDI (.*)/", $famlink, $fmatch);
							if ($ft>0) print $pgv_lang[trim($fmatch[1])]." ";
							if ($stepparents) {
								if ($parent!=$stepparents['HUSB']) {
									if ($parentsex=="M") print $pgv_lang["fathers_family_with"];
									else print $pgv_lang["mothers_family_with"];
									if (!empty($stepparents['HUSB'])) {
										if (displayDetailsById($stepparents['HUSB']) && showLivingNameById($stepparents['HUSB'])) print PrintReady(get_person_name($stepparents['HUSB']));
										else print $pgv_lang["private"];
									}
									else print $pgv_lang["unknown"];
									print "</span>";
									if ($view!="preview") {
										print " - <a href=\"family.php?famid=$stepfamids[$f]\">[".$pgv_lang["view_family"];
										if ($SHOW_FAM_ID_NUMBERS) print " &lrm;($stepfamids[$f])&lrm;";
										print "]</a>";
									}
									print "</td></tr></table>";
									print "\n\t<table class=\"facts_table\">";
									if (!empty($stepparents['HUSB'])) {
										print "<tr><td class=\"facts_label$styleadd\">";
										//print $pgv_lang["stepdad"];
										print "<br />";
										print "</td><td class=\"facts_value, person_box\">";
										print_pedigree_person($stepparents['HUSB'],2,$view!="preview");
										$personcount++;
										print "</td></tr>\n";
									}
								}
								else if ($parent!=$stepparents['WIFE']) {
									if ($parentsex=="F") print $pgv_lang["mothers_family_with"];
									else print $pgv_lang["fathers_family_with"];
									if (!empty($stepparents['WIFE'])) {
										if (displayDetailsById($stepparents['HUSB']) && showLivingNameById($stepparents['HUSB'])) print PrintReady(get_person_name($stepparents['WIFE']));
										else print $pgv_lang["private"];
									}
									else print $pgv_lang["unknown"];
									print "</span>";
									if ($view!="preview") {
										print " - <a href=\"family.php?famid=$stepfamids[$f]\">[".$pgv_lang["view_family"];
										if ($SHOW_FAM_ID_NUMBERS) print " &lrm;($stepfamids[$f])&lrm;";
										print "]</a>";
									}
									print "</td></tr></table>";
									print "\n\t<table class=\"facts_table\">";
									if (!empty($stepparents['WIFE'])) {
										print "<tr><td class=\"facts_label$styleadd\">";
										//print $pgv_lang["stepmom"];
										print "<br />";
										print "</td><td class=\"facts_value$styleadd, person_boxF\">";
										print_pedigree_person($stepparents['WIFE'],2,$view!="preview");
										$personcount++;
										print "</td></tr>\n";
									}
								}
							}
							//-- print the siblings
							$chil = array();
							for($i=0; $i<$num; $i++) {
								$spid = $smatch[$i][1];
								if (strtoupper($spid)!=strtoupper($pid)) {
									$chil[] = $spid;
									$styleadd="";
									if (isset($newchil)&&(!in_array($spid, $newchil))) $styleadd="red";
									$srec = find_person_record($spid);
									if (empty($srec)) $srec = find_record_in_file($spid);
									$isf = "NN";
									if (preg_match("/1 SEX F/", $srec)>0) {
											$label = $pgv_lang["halfsister"];
											$isf = "F";
									}
									if (preg_match("/1 SEX M/", $srec)>0) {
											$label = $pgv_lang["halfbrother"];
											$isf = "";
									}
									if ($isf == "NN") $label = $pgv_lang["halfsibling"];
									print "<tr><td class=\"facts_label$styleadd\">".$label."</td><td class=\"facts_value$styleadd, person_box$isf\">";
									print_pedigree_person($spid,2,$view!="preview");
									$personcount++;
									print "</td></tr>\n";
								}
							}
							print "\n\t</table>";
						}
					}
				}
			}
			}
		}
	}
}
else {
	if (($view!="preview") && (userCanEdit())&&($disp)) {
		print "<a href=\"#\" onclick=\"return addnewparent('$pid', 'HUSB');\">".$pgv_lang["add_father"]."</a><br />";
		print "<a href=\"#\" onclick=\"return addnewparent('$pid', 'WIFE');\">".$pgv_lang["add_mother"]."</a>";
	}
}

//-- print the spouses and children
$famids = find_sfamily_ids($pid);
if (($view!="preview") && (userCanEdit())&&($disp)) {
   if (($show_changes=="yes")&&(isset($pgv_changes[$pid."_".$GEDCOM]))) {
		   $newrec = find_record_in_file($pid);
		   $ct = preg_match_all("/1\sFAMS\s@(.*)@/", $newrec, $match, PREG_SET_ORDER);
				for($i=0; $i<$ct; $i++) {
						if (!in_array($match[$i][1], $famids)) $famids[]=$match[$i][1];
				}
   }
}
if (count($famids)>0) {
  $hparents=false;
  for($f=0; $f<count($famids); $f++) {
		 if (!empty($famids[$f])) {
				$famrec = find_family_record($famids[$f]);
				if (empty($famrec)) $famrec = find_record_in_file($famids[$f]);
				if ($famrec) {
					print "<table><tr><td><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["sfamily"]["small"]."\" border=\"0\" class=\"icon\" alt=\"\" /></td>";
					//print "<td><span class=\"subheaders\">".$pgv_lang["as_spouse"];
					print "<td><span class=\"subheaders\">".$pgv_lang["family_with"]." ";
					$parents = find_parents_in_record($famrec);
					$spid = "";
					if($parents) {
						if($pid!=$parents["HUSB"]) {
							$spid=$parents["HUSB"];
							$spousetag = "HUSB";
						}
						else {
							$spid=$parents["WIFE"];
							$spousetag = "WIFE";
						}
					}
					if (!empty($spid)) {
						if (displayDetailsById($spid) && showLivingNameById($spid)) print PrintReady(get_person_name($spid));
						else print $pgv_lang["private"];
					}
					else print $pgv_lang["unknown"];
					print "</span> ";
					if ($view!="preview") print " - <a href=\"family.php?famid=$famids[$f]\"> [".$pgv_lang["view_family"];
					if ($SHOW_FAM_ID_NUMBERS) print " &lrm;($famids[$f])&lrm;";
					print "]</a>";
					print "</td></tr></table>";
					print "\n\t<table class=\"facts_table\">";
					$newparents = $parents;
					unset($newchil);
					if (userCanEdit()&&($disp)) {
							   if (($show_changes=="yes")&&(isset($pgv_changes[$famids[$f]."_".$GEDCOM]))) {
									   $newrec = find_record_in_file($famids[$f]);
									   $newparents = find_parents_in_record($newrec);
									   $newchil = array();
									   $num = preg_match_all("/1\s*CHIL\s*@(.*)@/", $newrec, $smatch,PREG_SET_ORDER);
									for($i=0; $i<$num; $i++) {
											$newchil[] = $smatch[$i][1];
									}
							   }
					}
					if($parents) {
							if (!empty($spid)) {
									$styleadd="";
									if ((!is_array($newparents))||(!in_array($spid, $newparents))) {
											if($pid!=$newparents["HUSB"]) $nspid=$newparents["HUSB"];
											else $nspid=$newparents["WIFE"];
											$srec = find_person_record($nspid);
											if (empty($srec)) $srec = find_record_in_file($nspid);
											$isf = "NN";
											if (preg_match("/1 SEX F/", $srec)>0) {
													$label = $pgv_lang["mother"];
													$isf = "F";
											}
											if (preg_match("/1 SEX M/", $srec)>0) {
													$label = $pgv_lang["father"];
													$isf = "";
											}
											if ($isf == "NN") $label = $pgv_lang["parent"];
											print "<tr><td class=\"facts_labelblue\">".$label."</td><td class=\"facts_valueblue, person_box$isf\">";
											print_pedigree_person($spid, 2,$view!="preview");
											$personcount++;
											print "</td></tr>\n";
											$styleadd="red";
									}
									$srec = find_person_record($spid);
									if (empty($srec)) $srec = find_record_in_file($spid);
									$isf = "NN";
									if (preg_match("/1 SEX F/", $srec)>0) {
											$label = $pgv_lang["mother"];
											$isf = "F";
									}
									if (preg_match("/1 SEX M/", $srec)>0) {
											$label = $pgv_lang["father"];
											$isf = "";
									}
									if ($isf == "NN") $label = $pgv_lang["parent"];
									print "<tr><td class=\"facts_label$styleadd\">".$label."</td><td class=\"facts_value$styleadd, person_box$isf\">";
									print_pedigree_person($spid, 2,$view!="preview");
									$personcount++;
									print "</td></tr>\n";
							}
							else {
									if (userCanEdit()&&($disp)) {
											print "<tr><td class=\"facts_label\">";
											if ($spousetag=="WIFE") print $pgv_lang["add_wife"];
											else print $pgv_lang["add_husb"];
											print "</td><td class=\"facts_value\">";
											print "<a href=\"#\" onclick=\"return addnewspouse('$famids[$f]', '$spousetag');\">".$pgv_lang["add_".strtolower($spousetag)."_to_family"]."</a>";
											print "</td></tr>\n";
									}
							}
					}
				   $num = preg_match_all("/1\s*CHIL\s*@(.*)@/", $famrec, $smatch,PREG_SET_ORDER);
				   $chil = array();
				   for($i=0; $i<$num; $i++) {
						  $spid = $smatch[$i][1];
						  $chil[] = $spid;
						  $styleadd="";
						  if (isset($newchil)&&(!in_array($spid, $newchil))) $styleadd="red";
						  $srec = find_person_record($spid);
						  if (empty($srec)) $srec = find_record_in_file($spid);
						  $isf = "NN";
						  if (preg_match("/1 SEX F/", $srec)>0) {
						  		$label = $pgv_lang["daughter"];
						  		$isf = "F";
						  }
						  if (preg_match("/1 SEX M/", $srec)>0) {
						  		$label = $pgv_lang["son"];
						  		$isf = "";
						  }
						  if ($isf == "NN") $label = $pgv_lang["child"];
						  print "<tr><td class=\"facts_label$styleadd\">".$label."</td><td class=\"facts_value$styleadd, person_box$isf\">";
						  print_pedigree_person($spid,2,$view!="preview");
						  $personcount++;
						  print "</td></tr>\n";
				   }
				   if (isset($newchil)) {
								for($i=0; $i<count($newchil); $i++) {
										if (!in_array($newchil[$i], $chil)) {
												$srec = find_record_in_file($newchil[$i]);
						  						$isf = "NN";
						  						if (preg_match("/1 SEX F/", $srec)>0) {
						  								$label = $pgv_lang["daughter"];
						  								$isf = "F";
						  						}
						  						if (preg_match("/1 SEX M/", $srec)>0) {
						  								$label = $pgv_lang["son"];
						  								$isf = "";
						  						}
						  						if ($isf == "NN") $label = $pgv_lang["child"];
												print "<tr><td class=\"facts_labelblue\">".$label."</td><td class=\"facts_valueblue, person_box$isf\">";
												print_pedigree_person($newchil[$i],2,$view!="preview");
												$personcount++;
												print "</td></tr>\n";
										}
								}
						}
				   if (($view!="preview") && (userCanEdit())&&($disp)) {
						print "<tr><td class=\"facts_label\">".$pgv_lang["add_child_to_family"]."</td><td class=\"facts_value\"><a href=\"#\" onclick=\"return addnewchild('$famids[$f]');\">".$pgv_lang["add_son_daughter"]."</a>";
						print_help_link("add_son_daughter_help", "qm");
						print "</td></tr>\n";
				   }
				   print "</table>";
				}
		 }
  }
}
if ($personcount==0){
	print "<table class=\"facts_table\"><tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_tab5"];
	print "<script language=\"JavaScript\" type=\"text/javascript\">tabstyles[4]='tab_cell_inactive_empty'; document.getElementById('pagetab4').className='tab_cell_inactive_empty';</script>";
	print "</td></tr></table>\n";
}
print "<br />\n";
if (($view!="preview") && (userCanEdit())&&($disp)) {
	if (count($famids)>1) {
		print "<a href=\"#\" onclick=\"return reorder_families('$pid');\">".$pgv_lang["reorder_families"]."</a>";
		print_help_link("reorder_families_help", "qm");
		print "<br />\n";
	}
   print "<a href=\"#\" onclick=\"return add_famc('$pid');\">".$pgv_lang["link_as"].($LANGUAGE=="german"?$pgv_lang["child"]:str2lower($pgv_lang["child"]))."</a>";
			 print_help_link("link_child_help", "qm");
		  print "<br />\n";
   if ($sex!="F") {
		   print "<a href=\"#\" onclick=\"return add_fams('$pid','HUSB');\">".$pgv_lang["link_as"].($LANGUAGE=="german"?$pgv_lang["husband"]:str2lower($pgv_lang["husband"]))."</a>";
		   print_help_link("link_husband_help", "qm");
		   print "<br />\n";
		   print "<a href=\"#\" onclick=\"return addspouse('$pid','WIFE');\">".$pgv_lang["add_new_wife"]."</a>";
		   print_help_link("add_wife_help", "qm");
		   print "<br />\n";
   }
   if ($sex!="M") {
		   print "<a href=\"#\" onclick=\"return add_fams('$pid','WIFE');\">".$pgv_lang["link_as"].($LANGUAGE=="german"?$pgv_lang["wife"]:str2lower($pgv_lang["wife"]))."</a>";
		   print_help_link("link_wife_help", "qm");
		   print "<br />\n";
		   print "<a href=\"#\" onclick=\"return addspouse('$pid','HUSB');\">".$pgv_lang["add_new_husb"]."</a>";
		   print_help_link("add_husband_help", "qm");
		   print "<br />\n";
   }
}

// start
print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" id=\"marker5\" width=\"1\" height=\"1\" alt=\"\" />";
// end
print "</div>";


//--------------------------------Start 6th tab individual page
//--- Google map
if (file_exists("modules/googlemap.php")) {
	print "\n\t<div id=\"googlemap\" class=\"tab_page\" style=\"position: $position; display: $display; top: auto; left: auto; visibility: $visibility; z-index: 2; \">";
        if (!$disp) {
            print "\n\t<table class=\"facts_table\">";
            print "<tr><td class=\"facts_value\">";
            print_privacy_error($CONTACT_EMAIL);
            print "</td></tr>";
            print "\n\t</table>\n<br />";
            print "<script type=\"text/javascript\">\n";
            print "function ResizeMap ()\n{\n}\n</script>\n";
        }
        else {
	        include_once('modules/googlemap/googlemap.php');
            build_indiv_map($indifacts, $famids);
        }
	// start
	print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" id=\"marker6\" width=\"1\" height=\"1\" alt=\"\" />";
	// end

	print "</div>\n";
}

//--------------------------------Start 7th tab individual page
//--- Research Log
if (file_exists("modules/researchlog.php") && ($SHOW_RESEARCH_LOG>=PGV_USER_ACCESS_LEVEL)) {
	print "\n\t<div id=\"researchlog\" class=\"tab_page\" style=\"position: $position; display: $display; top: auto; left: auto; visibility: $visibility; z-index: 2; \">";
	if ($view=="preview") print "<span class=\"subheaders\">".$pgv_lang["research_log"]."</span>";
	if (!$disp) {
		print "\n\t<table class=\"facts_table\">";
	    print "<tr><td class=\"facts_value\">";
	    print_privacy_error($CONTACT_EMAIL);
	    print "</td></tr>";
	    print "\n\t</table>\n<br />";
	}
	else {
	   include_once('modules/researchlog/researchlog.php');
	   $mod = new researchlog();
	   $out = $mod->tab($pid);
	   print $out;
	}

	// start
	print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" id=\"marker6\" width=\"1\" height=\"1\" alt=\"\" />";
	// end

	print "</div>\n";
}

?>
<script language="JavaScript" type="text/javascript">
<!--
// resize the content div so that the footer div will be in the correct location
	var tabs = new Array();
	tabs[0]=document.getElementById("facts");
	height = 0;
	for(i=0; i<tabs.length; i++) {
		temp = tabs[i];
		if (temp) {
			tempheight = temp.offsetHeight + temp.offsetTop;
			if (tempheight > height) height = tempheight;
		}
	}
	content_div = document.getElementById("content");
	if (content_div) content_div.style.height = height-content_div.offsetTop+"px";
	switch_tab(<?php print $default_tab; ?>);
//-->
</script>
<?php
print_footer();

?>
