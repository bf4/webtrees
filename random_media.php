<?php
/**
 * Random Media Block
 *
 * This block will randomly choose media items and show them in a block
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
 * @version $Id$
 * @package PhpGedView
 * @subpackage Blocks
 */

//-- only enable this block if multi media has been enabled
if ($MULTI_MEDIA) {
	$PGV_BLOCKS["print_random_media"]["name"]        = $pgv_lang["random_media_block"];
	$PGV_BLOCKS["print_random_media"]["descr"]        = "random_media_descr";
	$PGV_BLOCKS["print_random_media"]["canconfig"]        = true;
	$PGV_BLOCKS["print_random_media"]["config"] = array("filter"=>"all");

	require_once 'includes/functions_print_facts.php';

	//-- function to display a random picture from the gedcom
	function print_random_media($block = true, $config="", $side, $index) {
		global $pgv_lang, $GEDCOM, $foundlist, $medialist, $MULTI_MEDIA, $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES;
		global $MEDIA_EXTERNAL, $MEDIA_DIRECTORY, $SHOW_SOURCES, $GEDCOM_ID_PREFIX, $FAM_ID_PREFIX, $SOURCE_ID_PREFIX;
		global $MEDIATYPE, $THUMBNAIL_WIDTH;
		global $PGV_BLOCKS, $command;

  		if (empty($config)) $config = $PGV_BLOCKS["print_random_media"]["config"];
  		if (isset($config["filter"])) $filter = $config["filter"];  // indi, event, or all
  		else $filter = "all";

		if (!$MULTI_MEDIA) return;
		$medialist = array();
		$foundlist = array();

		$medialist = get_medialist(false, '');
		$ct = count($medialist);
		if ($ct>0) {
				$i=0;
				$disp = false;
				while(!$disp && $i<40) {
					$value = array_rand($medialist);
					//print_r($medialist[$value]); print "<br />";
					$links = $medialist[$value]["LINKS"];
					$disp = $medialist[$value]["EXISTS"] && $medialist[$value]["LINKED"] && $medialist[$value]["CHANGE"]!="delete" ;
					$disp &= displayDetailsByID($value["XREF"], "OBJE");
					$disp &= !FactViewRestricted($value["XREF"], $value["GEDCOM"]);

					$isExternal = strstr($medialist[$value]["FILE"], "://");

					if ($block && !$isExternal) $disp &= file_exists($medialist[$value]["THUMB"]);

					if ($disp && count($links) != 0){
						foreach($links as $key=>$type) {
							$gedrec = find_gedcom_record($key);
							$disp &= !empty($gedrec);
							$disp &= $type!="SOUR";
							$disp &= displayDetailsById($key, $type);
						}
						if ($disp && $filter!="all") {
							// Apply filter criteria
							$ct = preg_match("/0\s(@.*@)\sOBJE/", $medialist[$value]["GEDCOM"], $match);
							$objectID = $match[1];
							$ct2 = preg_match("/(\d)\sOBJE\s{$objectID}/", $gedrec, $match2);
							$objectRefLevel = $match2[1];
							if ($filter=="indi" && $objectRefLevel!="1") $disp = false;
							if ($filter=="event" && $objectRefLevel=="1") $disp = false;
						}
					}
					$i++;
				}
				if (!$disp) return false;

				print "<div id=\"random_picture\" class=\"block\">\n";
				print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
				print "<td class=\"blockh1\" >&nbsp;</td>";
				print "<td class=\"blockh2\" ><div class=\"blockhc\">";
				print_help_link("index_media_help", "qm", "random_picture");
				if ($PGV_BLOCKS["print_random_media"]["canconfig"]) {
					$username = getUserName();
					if ((($command=="gedcom")&&(userGedcomAdmin($username))) || (($command=="user")&&(!empty($username)))) {
						if ($command=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
						else $name = $username;
						print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;command=$command&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
						print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
					}
				}
				print "<b>".$pgv_lang["random_picture"]."</b>";
				print "</div></td>";
				print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
				print "</table>";
				print "<div class=\"blockcontent\" >";
				$imgsize = findImageSize($medialist[$value]["FILE"]);
				$imgwidth = $imgsize[0]+40;
				$imgheight = $imgsize[1]+150;
				print "<table id=\"random_picture_box\" width=\"95%\"><tr><td valign=\"top\"";

				if ($block) print " align=\"center\" class=\"details1\"";
				else print " class=\"details2\"";
				print " ><a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($medialist[$value]["FILE"])."',$imgwidth, $imgheight);\">";
				$mediaTitle = "";
				if ($medialist[$value]["TITL"]!=$medialist[$value]["FILE"]) {
					$mediaTitle = PrintReady($medialist[$value]["TITL"]);
				}
				if ($block) {
					print "<img src=\"".$medialist[$value]["THUMB"]."\" border=\"0\" class=\"thumbnail\"";
					if ($isExternal) print " width=\"".$THUMBNAIL_WIDTH."\"";
					print " alt=\"" . $mediaTitle . "\" title=\"" . $mediaTitle . "\" />";
				} else {
					print "<img src=\"".$medialist[$value]["FILE"]."\" border=\"0\" class=\"thumbnail\" ";
					$imgsize = findImageSize($medialist[$value]["FILE"]);
					if ($imgsize[0] > 175) print "width=\"175\" ";
					print " alt=\"" . $mediaTitle . "\" title=\"" . $mediaTitle . "\" />";
				}
				print "</a>\n";
				if ($block) print "<br />";
				else print "</td><td class=\"details2\">";
				if ($medialist[$value]["TITL"]!=$medialist[$value]["FILE"]) {
				    print "<a href=\"medialist.php?action=filter&amp;search=yes&amp;filter=".PrintReady($medialist[$value]["TITL"])."&amp;ged=".$GEDCOM."\">";
				    if (strlen($medialist[$value]["TITL"]) > 0) print "<b>". $mediaTitle ."</b>";
				}
				else print "<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($medialist[$value]["FILE"])."',$imgwidth, $imgheight);\">";
				print "</a><br />";

				PrintMediaLinks ($medialist[$value]["LINKS"], "normal");

				print "<br /><div class=\"indent" . ($TEXT_DIRECTION=="rtl"?"_rtl":"") . "\">";
				print_fact_notes($medialist[$value]["GEDCOM"], "1");
				print "</div>";
				print "</td></tr></table>\n";
				print "</div>"; // blockcontent
				print "</div>"; // block
		}
	}


	function print_random_media_config($config) {
		global $pgv_lang, $PGV_BLOCKS, $TEXT_DIRECTION;
		if (empty($config)) $config = $PGV_BLOCKS["print_random_media"]["config"];
		if (!isset($config["filter"])) $config["filter"] = "all";
		print "<tr><td class=\"descriptionbox width20\">";
 			print_help_link("random_media_persons_or_all_help", "qm");
			print $pgv_lang["random_media_persons_or_all"];
		print "</td>";?>
		<td class="optionbox">
	   	<select name="filter">
	    	<option value="indi"<?php if ($config["filter"]=="indi") print " selected=\"selected\"";?>><?php print $pgv_lang["random_media_persons"]; ?></option>
	    	<option value="event"<?php if ($config["filter"]=="event") print " selected=\"selected\"";?>><?php print $pgv_lang["random_media_events"]; ?></option>
	    	<option value="all"<?php if ($config["filter"]=="all") print " selected=\"selected\"";?>><?php print $pgv_lang["all"]; ?></option>
	  	</select>
	  	</td></tr>

	  	<?php
	}

}
?>
