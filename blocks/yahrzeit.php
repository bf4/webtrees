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

$PGV_BLOCKS['print_yahrzeit']['name']     =$pgv_lang['yahrzeit_block'];
$PGV_BLOCKS['print_yahrzeit']['descr']    ='yahrzeit_descr';
$PGV_BLOCKS['print_yahrzeit']['canconfig']=true;
$PGV_BLOCKS['print_yahrzeit']['config']   =array(
	'cache'        =>1,
	'days'         =>30,
	'infoStyle'    =>'style2',
	'allowDownload'=>'yes'
);

//-- this block prints a list of upcoming yahrzeit events of people in your gedcom
function print_yahrzeit($block=true, $config='', $side, $index) {
	global $pgv_lang, $factarray, $SHOW_ID_NUMBERS, $ctype, $TEXT_DIRECTION;
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $PGV_BLOCKS;
	global $DAYS_TO_SHOW_LIMIT, $SHOW_MARRIED_NAMES, $SERVER_URL;

	$block=true; // Always restrict this block's height

	if (empty($config))
		$config=$PGV_BLOCKS['print_yahrzeit']['config'];

	if (empty($config['infoStyle'    ])) $config['infoStyle'    ]='style2';
	if (empty($config['allowDownload'])) $config['allowDownload']='yes';
	if (empty($config['days'         ])) $config['days'         ]=$DAYS_TO_SHOW_LIMIT;

	if ($config['days']<1                  ) $config['days']=1;
	if ($config['days']>$DAYS_TO_SHOW_LIMIT) $config['days']=$DAYS_TO_SHOW_LIMIT;

	$startjd=server_jd();
	$endjd  =$startjd+max(min($config['days'], 1), $DAYS_TO_SHOW_LIMIT)-1;

	$username=getUserName();
	if (empty($username))
		$allowDownload = "no";

	print '<div id="yahrzeit" class="block">';
	print '<table class="blockheader" cellspacing="0" cellpadding="0"><tr>';
	print '<td class="blockh1">&nbsp;</td>';
	print '<td class="blockh2 blockhc">';
	print_help_link('yahrzeit_help', 'qm');
	if ($PGV_BLOCKS['print_yahrzeit']['canconfig'] && (
		($ctype=='gedcom' && userGedcomAdmin($username)) ||
	  ($ctype=='user' && !empty($username)))) {
		if ($ctype=='gedcom')
			$name=addslashes($GEDCOM);
		else
			$name=$username;
		print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name={$name}&amp;ctype={$ctype}&amp;action=configure&amp;side={$side}&amp;index={$index}', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
		print "<img class=\"adminicon\" src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}\" width=\"15\" height=\"15\" border=\"0\" alt=\"{$pgv_lang['config_block']}\" /></a>";
	}
	print "<b>{$pgv_lang['yahrzeit_block']}</b></td>";
	print '<td class="blockh3">&nbsp;</td></tr></table>';
	print '<div class="blockcontent">';
	if ($block)
		print '<div class="small_inner_block">';

	// The standard anniversary rules cover most of the Yahrzeit rules, we just
	// need to handle a few special cases.
	// Fetch normal anniversaries...
	$yahrzeits=array();
	$hidden=0;
	for ($jd=$startjd-1; $jd<=$endjd+30;++$jd)
		foreach (get_anniversary_events($jd, 'DEAT _YART') as $fact)
			// Extract hebrew dates only
			if ($fact['date']->date1->CALENDAR_ESCAPE=='@#DHEBREW@' && $fact['date']->MinJD()==$fact['date']->MaxJD())
				// Apply privacy
				if (displayDetailsByID($fact['id']) && showFactDetails($fact['fact'], $fact['id']) && !FactViewRestricted($fact['id'], $fact['factrec']))
					$yahrzeits[]=$fact;
				else
					++$hidden;
	// ...then adjust dates
	foreach ($yahrzeits as $key=>$yahrzeit)
		if (strpos('1 DEAT', $yahrzeit['factrec'])!==false) { // Just DEAT, not _YART
			$today=new JewishDate($yahrzeit['jd']);
			$hd=$yahrzeit['date']->MinDate();
			$hd1=new JewishDate($hd);
			$hd1->y+=1;
			$hd1->SetJDFromYMD();
			// Special rules.  See http://www.hebcal.com/help/anniv.html
			// Everything else is taken care of by our standard anniversary rules.
			if ($hd->d==30 && $hd->m==2 && $hd->y!=0 && $hd1->DaysInMonth()<30) { // 30 CSH
				// Last day in CSH
				$yahrzeit[$key]['jd']=JewishDate::YMDtoJD($today->y, 3, 1)-1;
			}
			if ($hd->d==30 && $hd->m==3 && $hd->y!=0 && $hd1->DaysInMonth()<30) { // 30 KSL
				// Last day in KSL
				$yahrzeit[$key]['jd']=JewishDate::YMDtoJD($today->y, 4, 1)-1;
			}
			if ($hd->d==30 && $hd->m==6 && $hd->y!=0 && $today->DaysInMonth()<30 && !$today->IsLeapYear()) { // 30 ADR
				// Last day in SHV
				$yahrzeit[$key]['jd']=JewishDate::YMDtoJD($today->y, 6, 1)-1;
			}
		}

	switch ($config['infoStyle']) {
	case "style1": // List style
		foreach ($yahrzeits as $yahrzeit)
			if ($yahrzeit['jd']>=$startjd && $yahrzeit['jd']<$startjd+$config['days']) {
				$ind=person::GetInstance($yahrzeit['id']);
				print "<a href=\"".$ind->getLinkUrl()."\" class=\"list_item name2\">".$ind->getName()."</a>".$ind->getSexImage();
				print "<div class=\"indent\">";
				print $yahrzeit['date']->Display(true);
				print ', '.str_replace("#year_var#", $yahrzeit['anniv'], $pgv_lang["year_anniversary"]);
				print "</div>";
			}
		break;
	case "style2": // Table style
		require_once("js/sorttable.js.htm");
		require_once("includes/gedcomrecord.php");
		$table_id = "ID".floor(microtime()*1000000); // sorttable requires a unique ID
		print "<table id=\"{$table_id}\" class=\"sortable list_table center\">";
		print "<tr>";
		print "<th class=\"list_label\">{$factarray['NAME']}</th>";
		print "<th style=\"display:none\">GIVN</th>";
		print "<th class=\"list_label\">{$factarray['DATE']}</th>";
		print "<th class=\"list_label\"><img src=\"./images/reminder.gif\" alt=\"{$pgv_lang['anniversary']}\" title=\"{$pgv_lang['anniversary']}\" border=\"0\" /></th>";
		print "<th class=\"list_label\">{$factarray['_YART']}</th>";
		print "</tr>";

		// Which types of name do we display for an INDI
		$name_subtags = array("", "_AKA", "_HEB", "ROMN");
		if ($SHOW_MARRIED_NAMES)
			$name_subtags[] = "_MARNM";

		foreach ($yahrzeits as $yahrzeit)
			if ($yahrzeit['jd']>=$startjd && $yahrzeit['jd']<$startjd+$config['days']) {
				$ind=person::GetInstance($yahrzeit['id']);
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
					print '<a name="0">&nbsp;</a>';
				else
					print "<a name=\"{$anniv}\">{$anniv}</a>";
				if ($config['allowDownload']=='yes') {
					// hCalendar:dtstart and hCalendar:summary
		//TODO does this work??
					print "<abbr class=\"dtstart\" title=\"".strip_tags($yahrzeit['date']->Display(false,'Ymd',array()))."\"></abbr>";
					print "<abbr class=\"summary\" title=\"".$pgv_lang["anniversary"]." #$anniv ".$factarray[$yahrzeit['fact']]." : ".PrintReady(strip_tags($ind->getSortableName()))."\"></abbr>";
				}

				//-- upcomming yahrzeit dates
				print "<td class=\"list_value_wrap\">";

		// TODO print the 2 dates one under the other - done by changing the date class
		// should the style be the same as the style of the death date (in cloudy)?
		// TODO should sort by julian day  - I see now 2 KSL between 19 and 20 CHS and 1 TSH should sort after 20 ELL

				print "<a href=\"".$url."\" class=\"list_item url\">".$td->Display(true, NULL, array('gregorian'))."</a>"; // hCalendar:url
				print "&nbsp;</td>";

				print "</tr>\n";
			}

		//-- table footer
		print "<tr class=\"sortbottom\">";
		print "<td class=\"list_label\">";
		echo '<a href="javascript:;" onclick="sortByNextCol(this)"><img src="images/topdown.gif" alt="" border="0" /> '.$factarray["GIVN"].'</a><br />';
		print $pgv_lang["total_names"].": ".count($yahrzeits);
		if ($hidden)
			print "<br /><span class=\"warning\">{$pgv_lang['hidden']} : {$hidden}</span>";
		print "</td>";
		print "<td style=\"display:none\">GIVN</td>";
		print "<td>";
		if ($config['allowDownload']=='yes') {
			$uri = $SERVER_URL.basename($_SERVER['REQUEST_URI']);
			global $whichFile;
			$whichFile = 'hCal-events.ics';
			$title = print_text('download_file',0,1);
			if (count($yahrzeits))
				print "<a href=\"http://feeds.technorati.com/events/{$uri}\"><img src=\"images/hcal.png\" border=\"0\" alt=\"{$title}\" title=\"{$title}\" /></a>";
		}
		print '</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
		print '</table>';
		break;
	}

	if ($block)
		print '</div>';
  print '</div>'; // blockcontent
 	print '</div>'; // block
}

function print_yahrzeit_config($config) {
  global $pgv_lang, $PGV_BLOCKS, $DAYS_TO_SHOW_LIMIT;

	if (empty($config)) $config=$PGV_BLOCKS["print_yahrzeit"]["config"];

	if (empty($config['infoStyle'    ])) $config['infoStyle'    ]='style2';
	if (empty($config['allowDownload'])) $config['allowDownload']='yes';
	if (empty($config['days'         ])) $config['days'         ]=$DAYS_TO_SHOW_LIMIT;

	if ($config['days']<1                  ) $config['days']=1;
	if ($config['days']>$DAYS_TO_SHOW_LIMIT) $config['days']=$DAYS_TO_SHOW_LIMIT;

  print '<tr><td class="descriptionbox wrap width33">';
	print_help_link('days_to_show_help', 'qm');
	print $pgv_lang['days_to_show'];
	print '</td><td class="optionbox">';
	print '<input type="text" name="days" size="2" value="'.$config['days'].'" />';
	print '</td></tr>';

 	print '<tr><td class="descriptionbox wrap width33">';
 	print_help_link('style_help', 'qm');
 	print $pgv_lang['style']."</td>";
	print '</td><td class="optionbox">';
 	print '<select name="infoStyle">';
	foreach (array('style1', 'style2') as $style) {
		print "<option value=\"{$style}\"";
		if ($config['infoStyle']==$style)
			print " selected=\"selected\"";
		print ">{$pgv_lang[$style]}</option>";
	}
	print '</select></td></tr>';

 	print '<tr><td class="descriptionbox wrap width33">';
	print_help_link("cal_dowload_help", "qm");
 	print $pgv_lang["cal_download"]."</td>";
	print '</td><td class="optionbox">';
 	print '<select name="allowDownload">';
 	foreach (array('yes', 'no') as $value) {
		print "<option value=\"{$value}\"";
		if ($config['allowDownload']==$value)
			print " selected=\"selected\"";
		print ">{$pgv_lang[$value]}</option>";
	}
	print '</select></td></tr>';

	// Cache file life is not configurable by user:  anything other than 1 day doesn't make sense
	print "<input type=\"hidden\" name=\"cache\" value=\"1\" />";
}
?>
