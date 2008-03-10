<?php
/**
 * Random Media Block
 *
 * This block will randomly choose media items and show them in a block
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2006  PGV Development Team
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
	$PGV_BLOCKS["print_random_media"]["name"]		= $pgv_lang["random_media_block"];
	$PGV_BLOCKS["print_random_media"]["descr"]		= "random_media_descr";
	$PGV_BLOCKS["print_random_media"]["canconfig"]	= true;
	$PGV_BLOCKS["print_random_media"]["config"]		= array(
		"cache"=>0,
		"filter"=>"all",
		"controls"=>"yes",
		"start"=>"no",

		"filter_avi"	=>"no",
		"filter_bmp"	=>"yes",
		"filter_gif"	=>"yes",
		"filter_jpeg"	=>"yes",
		"filter_mp3"	=>"no",
		"filter_ole"	=>"yes",
		"filter_pcx"	=>"yes",
		"filter_png"	=>"yes",
		"filter_tiff"	=>"yes",
		"filter_wav"	=>"no",

		"filter_audio"			=>"no",
		"filter_book"			=>"yes",
		"filter_card"			=>"yes",
		"filter_certificate"	=>"yes",
		"filter_document"		=>"yes",
		"filter_electronic"		=>"yes",
		"filter_fiche"			=>"yes",
		"filter_film"			=>"yes",
		"filter_magazine"		=>"yes",
		"filter_manuscript"		=>"yes",
		"filter_map"			=>"yes",
		"filter_newspaper"		=>"yes",
		"filter_photo"			=>"yes",
		"filter_tombstone"		=>"yes",
		"filter_video"			=>"no"
		);

	require_once 'includes/functions_print_facts.php';

	//-- function to display a random picture from the gedcom
	function print_random_media($block = true, $config="", $side, $index) {
		global $pgv_lang, $GEDCOM, $foundlist, $MULTI_MEDIA, $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES;
		global $MEDIA_EXTERNAL, $MEDIA_DIRECTORY, $SHOW_SOURCES, $GEDCOM_ID_PREFIX, $FAM_ID_PREFIX, $SOURCE_ID_PREFIX;
		global $MEDIATYPE, $THUMBNAIL_WIDTH, $USE_MEDIA_VIEWER, $DEBUG;
		global $PGV_BLOCKS, $ctype, $action;
		global $PGV_IMAGE_DIR, $PGV_IMAGES;

		if (!$MULTI_MEDIA) return;

  		if (empty($config)) $config = $PGV_BLOCKS["print_random_media"]["config"];
  		if (isset($config["filter"])) $filter = $config["filter"];  // indi, event, or all
  		else $filter = "all";
  		if (!isset($config['controls'])) $config['controls'] ="yes";
  		if (!isset($config['start'])) $config['start'] ="no";

		$medialist = array();
		$foundlist = array();

		$medialist = get_medialist(false, '', true, true);
		$ct = count($medialist);
		if ($ct>0) {
			$i=0;
			$disp = false;
			//-- try up to 40 times to get a media to display
			while($i<40) {
				$error = false;
				$value = array_rand($medialist);
				if (isset($DEBUG)&&($DEBUG==true)) {
					print "<br />";print_r($medialist[$value]);print "<br />";
					print "Trying ".$medialist[$value]["XREF"]."<br />\n";
				}
				$links = $medialist[$value]["LINKS"];
				$disp = ($medialist[$value]["EXISTS"]>0) && $medialist[$value]["LINKED"] && $medialist[$value]["CHANGE"]!="delete" ;
				if (isset($DEBUG)&&($DEBUG==true) && !$disp && !$error) {$error = true; print "<span class=\"error\">".$medialist[$value]["XREF"]." File does not exist, or is not linked to anyone, or is marked for deletion.</span><br />\n";}

				$disp &= displayDetailsByID($medialist[$value]["XREF"], "OBJE");
				$disp &= !FactViewRestricted($medialist[$value]["XREF"], $medialist[$value]["GEDCOM"]);

				if (isset($DEBUG)&&($DEBUG==true) && !$disp && !$error) {$error = true; print "<span class=\"error\">".$medialist[$value]["XREF"]." Failed to pass privacy</span><br />\n";}

				$isExternal = isFileExternal($medialist[$value]["FILE"]);

				if ($block && !$isExternal) $disp &= ($medialist[$value]["THUMBEXISTS"]>0);
				if (isset($DEBUG)&&($DEBUG==true) && !$disp && !$error) {$error = true; print "<span class=\"error\">".$medialist[$value]["XREF"]." thumbnail file could not be found</span><br />\n";}

				// Filter according to format and type  (Default: unless configured otherwise, don't filter)
				if (!empty($medialist[$value]["FORM"]) && isset($config["filter_".$medialist[$value]["FORM"]]) && $config["filter_".$medialist[$value]["FORM"]]!="yes") $disp = false;
				if (!empty($medialist[$value]["TYPE"]) && isset($config["filter_".$medialist[$value]["TYPE"]]) && $config["filter_".$medialist[$value]["TYPE"]]!="yes") $disp = false;
				if (isset($DEBUG)&&($DEBUG==true) && !$disp && !$error) {$error = true; print "<span class=\"error\">".$medialist[$value]["XREF"]." failed Format or Type filters</span><br />\n";}

				if ($disp && count($links) != 0){
					/** link privacy allready checked in displayDetailsById
					foreach($links as $key=>$type) {
						$gedrec = find_gedcom_record($key);
						$disp &= !empty($gedrec);
						//-- source privacy is now available through the display details by id method
						// $disp &= $type!="SOUR";
						$disp &= displayDetailsById($key, $type);
					}
					if (isset($DEBUG)&&($DEBUG==true)&&!$disp && !$error) {$error = true; print "<span class=\"error\">".$medialist[$value]["XREF"]." failed link privacy</span><br />\n";}
					*/
					if ($disp && $filter!="all") {
						// Apply filter criteria
						$ct = preg_match("/0\s(@.*@)\sOBJE/", $medialist[$value]["GEDCOM"], $match);
						$objectID = $match[1];
						//-- we could probably use the database for this filter
						foreach($links as $key=>$type) {
							$gedrec = find_gedcom_record($key);
							$ct2 = preg_match("/(\d)\sOBJE\s{$objectID}/", $gedrec, $match2);
							if ($ct2>0) {
								$objectRefLevel = $match2[1];
								if ($filter=="indi" && $objectRefLevel!="1") $disp = false;
								if ($filter=="event" && $objectRefLevel=="1") $disp = false;
								if (isset($DEBUG)&&($DEBUG==true)&&!$disp && !$error) {$error = true; print "<span class=\"error\">".$medialist[$value]["XREF"]." failed to pass config filter</span><br />\n";}
							}
							else $disp = false;
						}
					}
				}
				//-- leave the loop if we find an image that works
				if ($disp) {
					break;
				}
				//-- otherwise remove the private media item from the list
				else {
					if (isset($DEBUG)&&($DEBUG==true)) print "<span class=\"error\">".$medialist[$value]["XREF"]." Will not be shown</span><br />\n";
					unset($medialist[$value]);
				}
				//-- if there are no more media items, then try to get some more
				if (count($medialist)==0) $medialist = get_medialist(false, '', true, true);
				$i++;
			}
			if (!$disp) return false;

			if ($action!="ajax") {
				print "<div id=\"random_picture$index\" class=\"block\">\n";
				print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
				print "<td class=\"blockh1\" >&nbsp;</td>";
				print "<td class=\"blockh2\" ><div class=\"blockhc\">";
				print_help_link("index_media_help", "qm", "random_picture");
				if ($PGV_BLOCKS["print_random_media"]["canconfig"]) {
					if ($ctype=="gedcom" && PGV_USER_GEDCOM_ADMIN || $ctype=="user" && PGV_USER_ID) {
						if ($ctype=="gedcom") {
							$name = preg_replace("/'/", "\'", $GEDCOM);
						} else {
							$name = PGV_USER_NAME;
						}
						print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;ctype=$ctype&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
						print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
					}
				}
				print "<b>".$pgv_lang["random_picture"]."</b>";
				print "</div></td>";
				print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
				print "</table>";
				print "<div class=\"blockcontent\" id=\"random_picture_container$index\">\n";
				if ($config['controls']=='yes') {
					if ($config['start']=='yes' || (isset($_COOKIE['rmblockplay'])&&$_COOKIE['rmblockplay']=='true')) $image = "stop";
					else $image = "rarrow";
					$linkNextImage = "<a href=\"javascript: ".$pgv_lang["next_image"]."\" onclick=\"return ajaxBlock('random_picture_content$index', 'print_random_media', '$side', $index, '$ctype', true);\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES['rdarrow']['other']."\" border=\"0\" alt=\"".$pgv_lang["next_image"]."\" title=\"".$pgv_lang["next_image"]."\" /></a>\n";
	
					print "<div class=\"center\" id=\"random_picture_controls$index\">\n<br />";
					if ($TEXT_DIRECTION=="rtl") print $linkNextImage;
					print "<a href=\"javascript: ".$pgv_lang["play"]."/".$pgv_lang["stop"]."\" onclick=\"togglePlay(); return false;\">";
					if (isset($PGV_IMAGES[$image]['other'])) print "<img id=\"play_stop\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES[$image]['other']."\" border=\"0\" alt=\"".$pgv_lang["play"]."/".$pgv_lang["stop"]."\" title=\"".$pgv_lang["play"]."/".$pgv_lang["stop"]."\"/>";
					else print $pgv_lang["play"]."/".$pgv_lang["stop"];
					print "</a>\n";
					if ($TEXT_DIRECTION=="ltr") print $linkNextImage;
					?>
					</div>
					<script language="JavaScript" type="text/javascript">
					<!--
						var play = false;
						function togglePlay() {
							if (play) {
								play = false;
								imgid = document.getElementById('play_stop');
								imgid.src = '<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["rarrow"]['other']; ?>';
							}
							else {
								play = true;
								playSlideShow();
								imgid = document.getElementById('play_stop');
								imgid.src = '<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["stop"]['other']; ?>';
							}
						}
            	
						function playSlideShow() {
							if (play) {
									ajaxBlock('random_picture_content<?php print $index; ?>', 'print_random_media', '<?php print $side; ?>', <?php print $index; ?>, '<?php print $ctype; ?>', false);
								window.setTimeout('playSlideShow()', 4000);
							}
						}
					//-->
					</script>
					<?php
				}
				if ($config['start']=='yes') {
					?>
					<script language="JavaScript" type="text/javascript">
					<!--
						play = true;
						imgid = document.getElementById('play_stop');
						imgid.src = '<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["stop"]['other']; ?>';
						window.setTimeout('playSlideShow()', 4000);
					//-->
					</script>
					<?php
				}
				print "<div class=\"center\" id=\"random_picture_content$index\">";
			}
			$imgsize = findImageSize($medialist[$value]["FILE"]);
			$imgwidth = $imgsize[0]+40;
			$imgheight = $imgsize[1]+150;
			print "<table id=\"random_picture_box\" width=\"100%\"><tr><td valign=\"top\"";

			if ($block) print " align=\"center\" class=\"details1\"";
			else print " class=\"details2\"";
			$mediaid = $medialist[$value]["XREF"];
			if ($USE_MEDIA_VIEWER) {
			print " ><a href=\"mediaviewer.php?mid=".$mediaid."\">";
			}
			else {
				print " ><a href=\"javascript:;\" onclick=\"return openImage('".$medialist[$value]["FILE"]."', $imgwidth, $imgheight);\">";
			}
			$mediaTitle = "";
			if (!empty($medialist[$value]["TITL"])) {
				$mediaTitle = PrintReady($medialist[$value]["TITL"]);
			}
			else $mediaTitle = basename($medialist[$value]["FILE"]);
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
			print "<a href=\"mediaviewer.php?mid=".$mediaid."\">";
			print "<b>". $mediaTitle ."</b>";
			print "</a><br />";

			PrintMediaLinks ($medialist[$value]["LINKS"], "normal");

			print "<br /><div class=\"indent" . ($TEXT_DIRECTION=="rtl"?"_rtl":"") . "\">";
			print_fact_notes($medialist[$value]["GEDCOM"], "1");
			print "</div>";
			print "</td></tr></table>\n";
			if ($action!="ajax") {
				print "</div>"; // random_picture_content
				print "</div>"; // blockcontent
			}
			print "</div>"; // block
		}
	}


	function print_random_media_config($config) {
		global $pgv_lang, $factarray, $PGV_BLOCKS, $TEXT_DIRECTION;

		if (empty($config)) $config = $PGV_BLOCKS["print_random_media"]["config"];
		if (!isset($config["filter"])) $config["filter"] = "all";
		if (!isset($config["controls"])) $config["controls"] = "yes";
		if (!isset($config["start"])) $config["start"] = "no";

		if (!isset($config["filter_avi"])) {
			$config["filter_avi"]	= "no";
			$config["filter_bmp"]	= "yes";
			$config["filter_gif"]	= "yes";
			$config["filter_jpeg"]	= "yes";
			$config["filter_mp3"]	= "no";
			$config["filter_ole"]	= "yes";
			$config["filter_pcx"]	= "yes";
			$config["filter_png"]	= "yes";
			$config["filter_tiff"]	= "yes";
			$config["filter_wav"]	= "no";

			$config["filter_audio"]			= "no";
			$config["filter_book"]			= "yes";
			$config["filter_card"]			= "yes";
			$config["filter_certificate"]	= "yes";
			$config["filter_document"]		= "yes";
			$config["filter_electronic"]	= "yes";
			$config["filter_fiche"]			= "yes";
			$config["filter_film"]			= "yes";
			$config["filter_magazine"]		= "yes";
			$config["filter_manuscript"]	= "yes";
			$config["filter_map"]			= "yes";
			$config["filter_newspaper"]		= "yes";
			$config["filter_photo"]			= "yes";
			$config["filter_tombstone"]		= "yes";
			$config["filter_video"]			= "no";
		}

		print "<tr><td class=\"descriptionbox wrap width33\">";
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

	  	<tr><td class="descriptionbox wrap width33"><?php print_help_link("random_media_filter_help", "qm"); print $pgv_lang["filter"]; ?></td>
	  	<td class="optionbox">
	  		<center><b><?php print $factarray["FORM"]; ?></b></center>
	  		<table class="width100">
	  			<tr>
					<td class="width33"><input type="checkbox" value="yes" name="filter_avi" <?php if ($config['filter_avi']=="yes") print "checked=\"checked\""; ?> />&nbsp;&nbsp;avi&nbsp;&nbsp;</td>
					<td class="width33"><input type="checkbox" value="yes" name="filter_avi" <?php if ($config['filter_bmp']=="yes") print "checked=\"checked\""; ?> />&nbsp;&nbsp;bmp&nbsp;&nbsp;</td>
					<td class="width33"><input type="checkbox" value="yes" name="filter_avi" <?php if ($config['filter_gif']=="yes") print "checked=\"checked\""; ?> />&nbsp;&nbsp;gif&nbsp;&nbsp;</td>
	  			</tr><tr>
					<td class="width33"><input type="checkbox" value="yes" name="filter_jpeg" <?php if ($config['filter_jpeg']=="yes") print "checked=\"checked\""; ?> />&nbsp;&nbsp;jpeg&nbsp;&nbsp;</td>
					<td class="width33"><input type="checkbox" value="yes" name="filter_mp3" <?php if ($config['filter_mp3']=="yes") print "checked=\"checked\""; ?> />&nbsp;&nbsp;mp3&nbsp;&nbsp;</td>
					<td class="width33"><input type="checkbox" value="yes" name="filter_ole" <?php if ($config['filter_ole']=="yes") print "checked=\"checked\""; ?> />&nbsp;&nbsp;ole&nbsp;&nbsp;</td>
	  			</tr><tr>
					<td class="width33"><input type="checkbox" value="yes" name="filter_pcx" <?php if ($config['filter_pcx']=="yes") print "checked=\"checked\""; ?> />&nbsp;&nbsp;pcx&nbsp;&nbsp;</td>
					<td class="width33"><input type="checkbox" value="yes" name="filter_png" <?php if ($config['filter_png']=="yes") print "checked=\"checked\""; ?> />&nbsp;&nbsp;png&nbsp;&nbsp;</td>
					<td class="width33"><input type="checkbox" value="yes" name="filter_tiff" <?php if ($config['filter_tiff']=="yes") print "checked=\"checked\""; ?> />&nbsp;&nbsp;tiff&nbsp;&nbsp;</td>
	  			</tr>
	  			</tr><tr>
					<td class="width33"><input type="checkbox" value="yes" name="filter_wav" <?php if ($config['filter_wav']=="yes") print "checked=\"checked\""; ?> />&nbsp;&nbsp;wav&nbsp;&nbsp;</td>
					<td class="width33">&nbsp;</td>
					<td class="width33">&nbsp;</td>
	  			</tr>
	  		</table>
	  		<br /><center><b><?php print $factarray["TYPE"]; ?></b></center>
	  		<table class="width100">
	  			<tr>
	  			<?php
	  			
				//-- Build array of currently defined values for the Media Type
				foreach ($pgv_lang as $varname => $typeValue) {
					if (substr($varname, 0, 6) == "TYPE__") {
						$type[strtolower(substr($varname, 6))] = $typeValue;
					}
				}
				//-- Sort the array into a meaningful order
				array_flip($type);
				asort($type);
				array_flip($type);
				//-- Build the list of checkboxes
				$i = 0;
				foreach ($type as $typeName => $typeValue) {
					$i++;
					if ($i > 3) {
						$i = 1;
						print "</tr><tr>";
					}
					print "<td class=\"width33\"><input type=\"checkbox\" value=\"yes\" name=\"filter_".$typeName."\"";
					if ($config['filter_'.$typeName]=="yes") print "checked=\"checked\"";
					print " />&nbsp;&nbsp;".$typeValue."&nbsp;&nbsp;</td>";
				}
	  			?>
	  			</tr>
	  		</table>
		</td></tr>
	  		  	
	  	<tr><td class="descriptionbox wrap width33"><?php print_help_link("random_media_ajax_controls_help", "qm"); print $pgv_lang["random_media_ajax_controls"]; ?></td>
	  	<td class="optionbox"><select name="controls">
			<option value="yes" <?php if ($config["controls"]=="yes") print " selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
			<option value="no" <?php if ($config["controls"]=="no") print " selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
		</select>
		</td></tr>
		<tr><td class="descriptionbox wrap width33"><?php print_help_link("random_media_start_slide_help", "qm"); print $pgv_lang["random_media_start_slide"]; ?></td>
	  	<td class="optionbox"><select name="start">
			<option value="yes" <?php if ($config["start"]=="yes") print " selected=\"selected\""; ?>><?php print $pgv_lang["yes"]; ?></option>
			<option value="no" <?php if ($config["start"]=="no") print " selected=\"selected\""; ?>><?php print $pgv_lang["no"]; ?></option>
		</select>
		</td></tr>

	  	<?php
		// Cache file life is not configurable by user:  anything other "no cache" doesn't make sense
		print "<input type=\"hidden\" name=\"cache\" value=\"0\" />";
	}

}
?>
