<?php
/**
 * Yahrzeit Block
 *
 * This block will print a list of upcoming yahrzeit (hebrew death anniversaries) 
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 Greg Roach, fisharebest@users.sourceforge.net
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

$PGV_BLOCKS["print_yahrzeit"]["name"]		= $pgv_lang["yahrzeit_block"];
$PGV_BLOCKS["print_yahrzeit"]["descr"]		= "yahrzeit_descr";
$PGV_BLOCKS["print_yahrzeit"]["canconfig"]	= true;
$PGV_BLOCKS["print_yahrzeit"]["config"]		= array(
	"cache"=>1,
	"days"=>30
	);

//-- this block prints a list of upcoming yahrzeit events of people in your gedcom
function print_yahrzeit($block=true, $config="", $side, $index) {
  global $pgv_lang, $factarray, $SHOW_ID_NUMBERS, $ctype, $TEXT_DIRECTION;
  global $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $PGV_BLOCKS;
  global $DAYS_TO_SHOW_LIMIT;

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
	if ($block)
		print "<div class=\"small_inner_block\">\n";

	// The standard anniversary rules cover most of the Yahrzeit rules.
	// We just need to handle one special case:
	// If the event was on 30th, and the first anniversary year had
	// 30 days, then the anniversary moves forward to the 1st of the
	// next month.
	// Fetch normal anniversaries...
	$yahrzeits=array();
	for ($jd=$startjd-1; $jd<=$endjd;++$jd)
		foreach (get_anniversary_events($jd, 'DEAT') as $fact)
			// Exact hebrew dates only
			if ($fact['date']->date1->CALENDAR_ESCAPE=='@#DHEBREW@' && $fact['date']->date1->d!=0 && $fact['date']->date1->y!=0)
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
	}
	// Print a simple list
	foreach ($yahrzeits as $yahrzeit)
		if ($yahrzeit['jd']>=$startjd) {
			$ind=Person::GetInstance($yahrzeit['id']);
			print "<a href=\"".$ind->getLinkUrl()."\">".PrintReady($ind->getSortableName())."</a>".$ind->getSexImage();
			$today=new JewishDate($yahrzeit['jd']);
			$td=new GedcomDate($today->Format('@ A O E'));
			print ": ".$yahrzeit['date']->Display(true, NULL, array())." - ".$td->Display(true, NULL, array('gregorian'))."<br/>";
		}

	if ($block)
		print "</div>\n";
  print "</div>"; // blockcontent
  print "</div>"; // block
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
