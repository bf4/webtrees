<?php
/**
 * Top 10 Pageviews Block
 *
 * This block will show the top 10 records from the Gedcom that have been viewed the most
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @version $Id: top10_pageviews.php,v 1.1.2.18 2006/04/13 12:06:36 canajun2eh Exp $
 * @package PhpGedView
 * @subpackage Blocks
 */

$PGV_BLOCKS["top10_pageviews"]["name"]           = $pgv_lang["top10_pageviews"];
$PGV_BLOCKS["top10_pageviews"]["descr"]          = "top10_pageviews_descr";
$PGV_BLOCKS["top10_pageviews"]["canconfig"]        = true;
$PGV_BLOCKS["top10_pageviews"]["config"] = array("num"=>10, "count_placement"=>"left");

function top10_pageviews($block=true, $config="", $side, $index) {
	global $pgv_lang, $GEDCOM, $INDEX_DIRECTORY, $PGV_BLOCKS, $command, $PGV_IMAGES, $PGV_IMAGE_DIR, $SHOW_SOURCES, $TEXT_DIRECTION;

	if (empty($config)) $config = $PGV_BLOCKS["top10_pageviews"]["config"];
	if (isset($config["count_placement"])) $CountSide = $config["count_placement"];
	else $CountSide = "left";

	$PGV_COUNTER_FILENAME = $INDEX_DIRECTORY.$GEDCOM."pgv_counters.txt";
	//-- if the counter file does not exist then don't do anything
	if (!file_exists($PGV_COUNTER_FILENAME)) {
		if (userIsAdmin(getUserName())) {
			print "<div id=\"top10\" class=\"block\">\n";
			print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
			print "<td class=\"blockh1\" >&nbsp;</td>";
			print "<td class=\"blockh2\" ><div class=\"blockhc\">";
			print_help_link("index_top10_pageviews_help", "qm");
			print "<b>".$pgv_lang["top10_pageviews"]."</b>";
			print "</div></td>";
			print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
			print "</table>";
			print "<div class=\"blockcontent\">\n";
			print "<span class=\"error\">\n".$pgv_lang["top10_pageviews_msg"]."</span>\n";
			print "</div>";
			print "</div>\n";
		}
		return;
	}
	//-- load the lines from the file
	$lines = file($PGV_COUNTER_FILENAME);
	$ids = array();
	//-- loop through the lines and create an array of ids
	foreach($lines as $indexval => $line) {
		$ct = preg_match("/@(.+)@\s*(\d+)/", $line, $match);
		if ($ct>0) {
			$id = trim($match[1]);
			$count = trim($match[2]);
			if (!empty($id)) $ids[$id] = $count;
		}
	}
	print "<div id=\"top10hits\" class=\"block\">\n";
	print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
	print "<td class=\"blockh1\" >&nbsp;</td>";
	print "<td class=\"blockh2\" ><div class=\"blockhc\">";
	print_help_link("index_top10_pageviews_help", "qm");
	if ($PGV_BLOCKS["top10_pageviews"]["canconfig"]) {
		$username = getUserName();
		if ((($command=="gedcom")&&(userGedcomAdmin($username))) || (($command=="user")&&(!empty($username)))) {
			if ($command=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
			else $name = $username;
			print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;command=$command&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
			print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
		}
	}
	print "<b>".$pgv_lang["top10_pageviews"]."</b>";
	print "</div></td>";
	print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
	print "</table>";
	print "<div class=\"blockcontent\">\n";
	if ($block) print "<div class=\"small_inner_block\">\n";
	if (count($ids)>0) {
		arsort($ids);
		if ($block) print "<table width=\"90%\">";
		else print "<table>";
		$i=0;
		foreach($ids as $id=>$count) {
			$gedrec = find_gedcom_record($id);
			$ct = preg_match("/0 @(.*)@ (.*)/", $gedrec, $match);
			if ($ct>0) {
				$type = trim($match[2]);
				$disp = displayDetailsById($id, $type);
				if ($disp) {
					if ($type=="INDI") {
						print "<tr valign=\"top\">";
						if ($CountSide=="left") {
							print "<td dir=\"ltr\" align=\"right\">";
							if ($TEXT_DIRECTION=="rtl") print "&nbsp;";
							print "[".$count."]";
							if ($TEXT_DIRECTION=="ltr") print "&nbsp;";
							print "</td>";
						}
						print "<td class=\"name2\" ><a href=\"individual.php?pid=".urlencode($id)."\">".PrintReady(get_person_name($id))."</a></td>";
						if ($CountSide=="right") {
							print "<td dir=\"ltr\" align=\"right\">";
							if ($TEXT_DIRECTION=="ltr") print "&nbsp;";
							print "[".$count."]";
							if ($TEXT_DIRECTION=="rtl") print "&nbsp;";
							print "</td>";
						}
						print "</tr>";
						$i++;
					}
					if ($type=="FAM") {
						print "<tr valign=\"top\">";
						if ($CountSide=="left") {
							print "<td dir=\"ltr\" align=\"right\">";
							if ($TEXT_DIRECTION=="rtl") print "&nbsp;";
							print "[".$count."]";
							if ($TEXT_DIRECTION=="ltr") print "&nbsp;";
							print "</td>";
						}
						print "<td class=\"name2\" ><a href=\"family.php?famid=".urlencode($id)."\">".PrintReady(get_family_descriptor($id))."</a></td>";
						if ($CountSide=="right") {
							print "<td dir=\"ltr\" align=\"right\">";
							if ($TEXT_DIRECTION=="ltr") print "&nbsp;";
							print "[".$count."]";
							if ($TEXT_DIRECTION=="rtl") print "&nbsp;";
							print "</td>";
						}
						print "</tr>";
						$i++;
					}
					if ($type=="REPO") {
						if ($SHOW_SOURCES>=getUserAccessLevel(getUserName())) {
							print "<tr valign=\"top\">";
							if ($CountSide=="left") {
								print "<td dir=\"ltr\" align=\"right\">";
								if ($TEXT_DIRECTION=="rtl") print "&nbsp;";
								print "[".$count."]";
								if ($TEXT_DIRECTION=="ltr") print "&nbsp;";
								print "</td>";
							}
							print "<td class=\"name2\" ><a href=\"repo.php?rid=".urlencode($id)."\">".PrintReady(get_repo_descriptor($id))."</a></td>";
							if ($CountSide=="right") {
								print "<td dir=\"ltr\" align=\"right\">";
								if ($TEXT_DIRECTION=="ltr") print "&nbsp;";
								print "[".$count."]";
								if ($TEXT_DIRECTION=="rtl") print "&nbsp;";
								print "</td>";
							}
							print "</tr>";
							$i++;
						}
					}
					if ($type=="SOUR") {
						if ($SHOW_SOURCES>=getUserAccessLevel(getUserName())) {
							print "<tr valign=\"top\">";
							if ($CountSide=="left") {
								print "<td dir=\"ltr\" align=\"right\">";
								if ($TEXT_DIRECTION=="rtl") print "&nbsp;";
								print "[".$count."]";
								if ($TEXT_DIRECTION=="ltr") print "&nbsp;";
								print "</td>";
							}
							print "<td class=\"name2\" ><a href=\"source.php?sid=".urlencode($id)."\">".PrintReady(get_source_descriptor($id))."</a></td>";
							if ($CountSide=="right") {
								print "<td dir=\"ltr\" align=\"right\">";
								if ($TEXT_DIRECTION=="ltr") print "&nbsp;";
								print "[".$count."]";
								if ($TEXT_DIRECTION=="rtl") print "&nbsp;";
								print "</td>";
							}
							print "</tr>";
							$i++;
						}
					}
					if ($i>=$config["num"]) break;
				}
			}
		}
		print "</table>";
	}
	else print "<b>".$pgv_lang["top10_pageviews_nohits"]."</b>\n";
	if ($block) print "</div>\n";
	print "</div>";
	print "</div>";
}

function top10_pageviews_config($config) {
	global $pgv_lang, $PGV_BLOCKS;
	if (empty($config)) $config = $PGV_BLOCKS["top10_pageviews"]["config"];
	?>
	<?php print $pgv_lang["num_to_show"]; ?> <input type="text" name="num" size="2" value="<?php print $config["num"]; ?>" />
	<br /><br />
	<?php print $pgv_lang["before_or_after"];?>&nbsp;<select name="count_placement">
		<option value="left"<?php if ($config["count_placement"]=="left") print " selected=\"selected\"";?>><?php print $pgv_lang["before"]; ?></option>
		<option value="right"<?php if ($config["count_placement"]=="right") print " selected=\"selected\"";?>><?php print $pgv_lang["after"]; ?></option>
	</select>

	<?php
}
?>
