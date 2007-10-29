<?php
/**
 * Yahrzeit Block
 *
 * This block will print a list of upcoming yahrzeit (hebrew death anniversaries) 
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 PGV Developers
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
 * @author Greg Roach, fisharebest@users.sourceforge.net
 * @version $Id$
 */

$PGV_BLOCKS["print_yahrzeit"]["name"]		= $pgv_lang["yahrzeit_block"];
$PGV_BLOCKS["print_yahrzeit"]["descr"]		= "yahrzeit_descr";
$PGV_BLOCKS["print_yahrzeit"]["canconfig"]	= true;
$PGV_BLOCKS["print_yahrzeit"]["config"]		= array(
	"cache"=>1,
	"days"=>30,
	"allowDownload"=>"yes" 
// TODO: add to config
	);

//-- this block prints a list of upcoming yahrzeit events of people in your gedcom
function print_yahrzeit($block=true, $config="", $side, $index) {
  	global $pgv_lang, $factarray, $SHOW_ID_NUMBERS, $ctype, $TEXT_DIRECTION;
  	global $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $PGV_BLOCKS;
  	global $DAYS_TO_SHOW_LIMIT;
  
  	// for table
  	global $SHOW_MARRIED_NAMES;

  	$block = true;      // Always restrict this block's height

  	if (empty($config)) $config = $PGV_BLOCKS["print_yahrzeit"]["config"];
  	if (!isset($DAYS_TO_SHOW_LIMIT)) $DAYS_TO_SHOW_LIMIT = 30;
  	if (isset($config["days"])) $daysprint = $config["days"];
  	else $daysprint = 30;
  
  	if ($daysprint < 1) $daysprint = 1;
  	if ($daysprint > $DAYS_TO_SHOW_LIMIT) $daysprint = $DAYS_TO_SHOW_LIMIT;  // valid: 1 to limit
  	$startjd=server_jd();
 	 $endjd=server_jd()+$daysprint;

  	// Output starts here
  	print "<div id=\"yahrzeit\" class=\"block\">";
  	print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
  	print "<td class=\"blockh1\" >&nbsp;</td>";
  	print "<td class=\"blockh2\" ><div class=\"blockhc\">";
  	print_help_link("yahrzeit_help", "qm");
  
  	if ($PGV_BLOCKS["print_yahrzeit"]["canconfig"]) {
   		$username = getUserName();

  		// Don't permit calendar download if not logged in
//if (empty($username)) $allowDownload = "no"; 
//  if (empty($username)) $allow_download=false; 
//  else 
    	$allow_download=false;
//TODO handle download flags  
        
    	if ((($ctype=="gedcom")&&(userGedcomAdmin($username))) || (($ctype=="user")&&(!empty($username)))) {
      		if ($ctype=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
      		else $name = $username;
      		print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;ctype=$ctype&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
      		print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
    	}
  	}
  	print "<b>{$pgv_lang['yahrzeit_block']}</b>";
 	print "</div></td>";
  	print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
  	print "</table>";
 	print "<div class=\"blockcontent\" >";
 	if ($block) print "<div class=\"small_inner_block\">\n";

	// table format			
	require_once("js/sorttable.js.htm");
	require_once("includes/gedcomrecord.php");
	$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
	print "<table id=\"".$table_id."\" class=\"sortable list_table center\">";
	print "<tr>";    
    print "<th class=\"list_label\">".$factarray["NAME"]."</th>";
	print "<th style=\"display:none\">GIVN</th>";
	print "<th class=\"list_label\">".$factarray["DATE"]."</th>";
	print "<th class=\"list_label\"><img src=\"./images/reminder.gif\" alt=\"".$pgv_lang["anniversary"]."\" title=\"".$pgv_lang["anniversary"]."\" border=\"0\" /></th>";
	print "<th class=\"list_label\">".$factarray["_YART"]."</th>";
	print "</tr>\n";
	
	//-- table body
	$hidden = 0; 
	$n = 0; 

	// Which types of name do we display for an INDI
 	$name_subtags = array("", "_AKA", "_HEB", "ROMN");
	if ($SHOW_MARRIED_NAMES) $name_subtags[] = "_MARNM";

	// The standard anniversary rules cover most of the Yahrzeit rules.
	// We just need to handle one special case:
	// If the event was on 30th, and the first anniversary year had
	// 30 days, then the anniversary moves forward to the 1st of the
	// next month.
	// Fetch normal anniversaries...
	$yahrzeits=array();
	for ($jd=$startjd-1; $jd<=$endjd;++$jd)
//		foreach (get_anniversary_events($jd, 'DEAT') as $fact)
		foreach (get_anniversary_events($jd, 'DEAT _YART') as $fact)
			
// TODO!! need also to retrieve 1 FACT/EVEN 2 _YART - their d_fact is FACT/EVEN, not _YART !!! the d_fact should be the TYPE value, if this value exists in the facts.en.php - or we should search also for EVEN/FACT with our facts in the gedcom TYPEg!!!
// we should also show in this case the text of _YART etc. (not EVEN/FACT texts) in the today's and upcoming events blocks   			
// same in calendar ...

			// Exact hebrew DD MMM dates only
//			if ($fact['date']->date1->CALENDAR_ESCAPE=='@#DHEBREW@' && $fact['date']->date1->d!=0 && $fact['date']->date1->y!=0)
			if ($fact['date']->date1->CALENDAR_ESCAPE=='@#DHEBREW@' && $fact['date']->date1->d!=0 && $fact['date']->date1->m!=0)
				$yahrzeits[]=$fact;
	// ...then adjust
	foreach ($yahrzeits as $key=>$yahrzeit) {
		$today=new JewishDate($yahrzeit['jd']);
		$hd=$yahrzeit['date']->MinDate(); // assume a single date, not a range
		$hd1=new HebrewDate($hd);
		$hd1->y+=1;
		$hd1->SetJDFromYMD();
		if ($hd->d==30 && $today->Format('t')=='29' && $hd1->Format('t')==30)
			$yahrzeits[$key]['jd']++;
//TODO does this rule work only on 30th of CSH, KSL and ADR dates?? does not work as expected for CHS
//30 CHS shows yahrzeit date as 2 KSL
////http://www.hebcal.com/help/anniv.html
//30 CHS shows yahrzeit should be 29 CHS (one day before 1 KSL) if no 30 CHS exists on year 1 after the death, else 1 KSL 
//(A 30 CHS birthday would be celebrated 1 KSL, but not on 2 KSL) 
//same for 30 KSL - last of KSL or 1 TVT depending on year 1
//30 ADR yahrzeit - on 30 ADR, if exists this year, else on last day of SHV <====
//nn ADR non leap year yahrzeits - on nn Adar or Adar I (and Adar II) - different rule for birthdays, they are always in Adar II   
//TODO How does the upcomming/today's block and the calendar work?			
	}
	foreach ($yahrzeits as $yahrzeit)

//TODO Should we allow both list (not readable) and table format in config?
/*
	// Print a simple list	
//TODO handle privacy	
	if ($yahrzeit['jd']>=$startjd) {
		$ind=Person::GetInstance($yahrzeit['id']);
		print "<a href=\"".$ind->getLinkUrl()."\">".PrintReady($ind->getSortableName())."</a>".$ind->getSexImage();
		$today=new JewishDate($yahrzeit['jd']);
		$td=new GedcomDate($today->Format('@ A O E'));
		print ": ".$yahrzeit['date']->Display(true, NULL, array())." - ".$td->Display(true, NULL, array('gregorian'))."<br/>";
	}
*/

    // Print table
	if ($yahrzeit['jd']>=$startjd) {
		$ind=Person::GetInstance($yahrzeit['id']);
		// Privacy
		if (!$ind->canDisplayDetails() || !showFactDetails($yahrzeit['fact'], $yahrzeit['id']) || FactViewRestricted($yahrzeit['id'], $yahrzeit['factrec'])) {
			$hidden++;
			continue;
		}	
		//-- Counter
		$n++;				
		print "<tr class=\"vevent\">"; // hCalendar:vevent	
		//-- Record name(s)
		$name=$ind->getSortableName();
		$url=$ind->getLinkUrl();
		print "<td class=\"list_value_wrap\" align=\"".get_align($name)."\">";
		print "<a href=\"".$ind->getLinkUrl()."\" class=\"list_item name2\" dir=\"".$TEXT_DIRECTION."\">".PrintReady($name)."</a>";
		print $ind->getSexImage();
		foreach ($name_subtags as $subtag) {
			for ($num=1; ; ++$num) {
				$addname = $ind->getSortableName($subtag, $num);
				
//TODO In all the lists we should retrieve to $addname only _HEB etc. names that are under 1 NAME
				
				if (empty($addname))
					break;
				else
					if ($addname!=$name)
						print "<br /><a title=\"".$subtag."\" href=\"".$url."\" class=\"list_item\">".PrintReady($addname)."</a>";
			}
		}		
		print "</td>";
		
		//-- GIVN for sorting
		echo "<td style=\"display:none\">";
		$exp = explode(",", str_replace('<', ',', $name).",");
		echo $exp[1];
		echo "</td>";
			
//		print "<a href=\"".$ind->getLinkUrl()."\">".PrintReady($ind->getSortableName())."</a>".$ind->getSexImage();
		$today=new JewishDate($yahrzeit['jd']);
		$td=new GedcomDate($today->Format('@ A O E'));

		//-- death/yahrzeit event date						
		print "<td class=\"".strrev($TEXT_DIRECTION)." list_value_wrap\">";
		print $yahrzeit['date']->Display(true, NULL, array());
		print "</td>";
		//-- Anniversary
		print "<td class=\"list_value_wrap rela\">";
		$anniv = $yahrzeit['anniv'];
		if ($anniv==0)
			print '&nbsp;';
		else
			print $anniv;
		if ($allow_download) {
			// hCalendar:dtstart and hCalendar:summary
//TODO does this work??			
			print "<abbr class=\"dtstart\" title=\"".strip_tags($yahrzeit['date']->Display(false,'Ymd',array()))."\"></abbr>";
			print "<abbr class=\"summary\" title=\"".$pgv_lang["anniversary"]." #$anniv ".$factarray[$yahrzeit['fact']]." : ".PrintReady(strip_tags($record->getSortableName()))."\"></abbr>";
		}

		//-- upcomming yahrzeit dates
		print "<td class=\"list_value_wrap\">";
		
// TODO print the 2 dates one under the other
// should the style be the same as the style of the death date (at least in cloudy)? 
		
		print "<a href=\"".$url."\" class=\"list_item url\">".$td->Display(true, NULL, array('gregorian'))."</a>"; // hCalendar:url
		print "&nbsp;</td>";

		print "</tr>\n";
	}
		
	//-- table footer
	print "<tr class=\"sortbottom\">";
	print "<td class=\"list_label\">";
	echo '<a href="javascript:;" onclick="sortByNextCol(this)"><img src="images/topdown.gif" alt="" border="0" /> '.$factarray["GIVN"].'</a><br />';
	print $pgv_lang["total_names"].": ".$n;
	if ($hidden) print "<br /><span class=\"warning\">".$pgv_lang["hidden"]." : ".$hidden."</span>";
	print "</td>";
	print "<td style=\"display:none\">GIVN</td>";
	print "<td>";
	if ($allow_download) { 
		$uri = $SERVER_URL.basename($_SERVER["REQUEST_URI"]);
		global $whichFile;
		$whichFile = "hCal-events.ics";
		$title = print_text("download_file",0,1);
		if ($n) print "<a href=\"http://feeds.technorati.com/events/".$uri."\"><img src=\"images/hcal.png\" border=\"0\" alt=\"".$title."\" title=\"".$title."\" /></a>";
	}
	print "</td>";
	print "<td></td>";
	print "<td></td>";
	print "</tr>";		
		
	print "</table>\n";		
	if ($block)
		print "</div>\n";
  	print "</div>"; // blockcontent ??
  	print "</div>"; // block ??
}

function print_yahrzeit_config($config) {
  global $pgv_lang, $PGV_BLOCKS, $DAYS_TO_SHOW_LIMIT;
	if (empty($config))
		$config = $PGV_BLOCKS["print_yahrzeit"]["config"];
	if (!isset($DAYS_TO_SHOW_LIMIT))
		$DAYS_TO_SHOW_LIMIT = 30;
	if (!isset($config["days"]))
		$config["days"] = 30;
	if ($config["days"] < 1)
		$config["days"] = 1;
	if ($config["days"] > $DAYS_TO_SHOW_LIMIT)
		$config["days"] = $DAYS_TO_SHOW_LIMIT;  // valid: 1 to limit
  print '<tr><td class="descriptionbox wrap width33">';
	print_help_link("days_to_show_help", "qm");
	print $pgv_lang["days_to_show"];
	print '</td><td class="optionbox">';
	print '<input type="text" name="days" size="2" value="'.$config["days"].'" />';
	print '</td></tr>';

	// Cache file life is not configurable by user:  anything other than 1 day doesn't make sense
	print "<input type=\"hidden\" name=\"cache\" value=\"1\" />";
}
?>
