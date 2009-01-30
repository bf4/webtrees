<?php
/**
 * Popup window that will allow a user to search for a family id, person id
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Display
 * @version $Id$
 */

require './config.php';
require './includes/functions/functions_print_lists.php';

$type           =safe_GET('type', PGV_REGEX_ALPHA, 'indi');
$filter         =safe_GET('filter');
$action         =safe_GET('action');
$callback       =safe_GET('callback', PGV_REGEX_NOSCRIPT, 'paste_id');
$create         =safe_GET('create');
$media          =safe_GET('media');
$external_links =safe_GET('external_links');
$directory      =safe_GET('directory', PGV_REGEX_NOSCRIPT, $MEDIA_DIRECTORY);
$multiple       =safe_GET_bool('multiple');
$showthumb      =safe_GET_bool('showthumb');
$all            =safe_GET_bool('all');
$choose         =safe_GET('choose', PGV_REGEX_NOSCRIPT, '0all');
$level          =safe_GET('level', PGV_REGEX_INTEGER, 0);
$language_filter=safe_GET('language_filter');
$magnify        =safe_GET_bool('magnify');

if ($showthumb) {
	$thumbget='&showthumb=true';
} else {
	$thumbget='';
}

$embed = substr($choose,0,1)=="1";
$chooseType = substr($choose,1);
if ($chooseType!="media" && $chooseType!="file") {
	$chooseType = "all";
}

//-- force the thumbnail directory to have the same layout as the media directory
//-- Dots and slashes should be escaped for the preg_replace
$srch = "/".addcslashes($MEDIA_DIRECTORY,'/.')."/";
$repl = addcslashes($MEDIA_DIRECTORY."thumbs/",'/.');
$thumbdir = stripcslashes(preg_replace($srch, $repl, $directory));

//-- prevent script from accessing an area outside of the media directory
//-- and keep level consistency
if (($level < 0) || ($level > $MEDIA_DIRECTORY_LEVELS)){
	$directory = $MEDIA_DIRECTORY;
	$level = 0;
} elseif (preg_match("'^$MEDIA_DIRECTORY'", $directory)==0){
	$directory = $MEDIA_DIRECTORY;
	$level = 0;
}
// End variables for find media

// Variables for Find Special Character
if (empty($language_filter)) {
	if (!empty($_SESSION["language_filter"])) {
		$language_filter=$_SESSION["language_filter"];
	} else {
		$language_filter=$lang_short_cut[$LANGUAGE];
	}
}
require 'includes/specialchars.php';
// End variables for Find Special Character

switch ($type) {
	case "indi" :
		print_simple_header($pgv_lang["find_individual"]);
		break;
	case "fam" :
		print_simple_header($pgv_lang["find_fam_list"]);
		break;
	case "media" :
		print_simple_header($pgv_lang["find_media"]);
		$action="filter";
		break;
	case "place" :
		print_simple_header($pgv_lang["find_place"]);
		$action="filter";
		break;
	case "repo" :
		print_simple_header($pgv_lang["repo_list"]);
		$action="filter";
		break;
	case "source" :
		print_simple_header($pgv_lang["find_source"]);
		$action="filter";
		break;
	case "specialchar" :
		print_simple_header($pgv_lang["find_specialchar"]);
		$action="filter";
		break;
}

echo PGV_JS_START;
?>
	function pasteid(id, name,thumb) {
		if(thumb) {
			window.opener.<?php print $callback; ?>(id,name,thumb);
			<?php if (!$multiple) print "window.close();"; ?>
		} else {
			window.opener.<?php print $callback; ?>(id);
			if (window.opener.pastename) window.opener.pastename(name);
			<?php if (!$multiple) print "window.close();"; ?>
		}
	}
	var language_filter;
	function paste_char(selected_char,language_filter,magnify) {
		window.opener.paste_char(selected_char,language_filter,magnify);
		return false;
	}
	function setMagnify() {
		document.filterspecialchar.magnify.value = '<?php print !$magnify; ?>';
		document.filterspecialchar.submit();
	}
	function checknames(frm) {
		if (document.forms[0].subclick) button = document.forms[0].subclick.value;
		else button = "";
		if (frm.filter.value.length<2&button!="all") {
			alert("<?php print $pgv_lang["search_more_chars"]; ?>");
			frm.filter.focus();
			return false;
		}
		if (button=="all") {
			frm.filter.value = "";
		}
		return true;
	}
<?php
echo PGV_JS_END;

$options = array();
$options["option"][]= "findindi";
$options["option"][]= "findfam";
$options["option"][]= "findmedia";
$options["option"][]= "findplace";
$options["option"][]= "findrepo";
$options["option"][]= "findsource";
$options["option"][]= "findspecialchar";
$options["form"][]= "formindi";
$options["form"][]= "formfam";
$options["form"][]= "formmedia";
$options["form"][]= "formplace";
$options["form"][]= "formrepo";
$options["form"][]= "formsource";
$options["form"][]= "formspecialchar";

print "<div align=\"center\">";
print "<table class=\"list_table $TEXT_DIRECTION width90\" border=\"0\">";
print "<tr><td style=\"padding: 10px;\" valign=\"top\" class=\"facts_label03 width90\">"; // start column for find text header

switch ($type) {
	case "indi" :
		print $pgv_lang["find_individual"];
		break;
	case "fam" :
		print $pgv_lang["find_fam_list"];
		break;
	case "media" :
		print $pgv_lang["find_media"];
		break;
	case "place" :
		print $pgv_lang["find_place"];
		break;
	case "repo" :
		print $pgv_lang["repo_list"];
		break;
	case "source" :
		print $pgv_lang["find_source"];
		break;
	case "specialchar" :
		print $pgv_lang["find_specialchar"];
		break;
}

print "</td>"; // close column for find text header

// start column for find options
print "</tr><tr><td class=\"list_value\" style=\"padding: 5px;\">";

// Show indi and hide the rest
if ($type == "indi") {
	print "<div align=\"center\">";
	print "<form name=\"filterindi\" method=\"get\" onsubmit=\"return checknames(this);\" action=\"find.php\">";
	print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
	print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
	print "<input type=\"hidden\" name=\"type\" value=\"indi\" />";
	print "<input type=\"hidden\" name=\"multiple\" value=\"$multiple\" />";
	print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print $pgv_lang["name_contains"]." <input type=\"text\" name=\"filter\" value=\"";
	if ($filter) print $filter;
	print "\" />";
	print "</td></tr>";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print "<input type=\"submit\" value=\"".$pgv_lang["filter"]."\" /><br />";
	print "</td></tr></table>";
	print "</form></div>";
}

// Show fam and hide the rest
if ($type == "fam") {
	print "<div align=\"center\">";
	print "<form name=\"filterfam\" method=\"get\" onsubmit=\"return checknames(this);\" action=\"find.php\">";
	print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
	print "<input type=\"hidden\" name=\"type\" value=\"fam\" />";
	print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
	print "<input type=\"hidden\" name=\"multiple\" value=\"$multiple\" />";
	print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print $pgv_lang["name_contains"]." <input type=\"text\" name=\"filter\" value=\"";
	if ($filter) print $filter;
	print "\" />";
	print "</td></tr>";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print "<input type=\"submit\" value=\"".$pgv_lang["filter"]."\" /><br />";
	print "</td></tr></table>";
	print "</form></div>";
}

// Show media and hide the rest
if ($type == "media" && $MULTI_MEDIA) {
	print "<div align=\"center\">";
	print "<form name=\"filtermedia\" method=\"get\" onsubmit=\"return checknames(this);\" action=\"find.php\">";
	print "<input type=\"hidden\" name=\"choose\" value=\"".$choose."\" />";
	print "<input type=\"hidden\" name=\"directory\" value=\"".$directory."\" />";
	print "<input type=\"hidden\" name=\"thumbdir\" value=\"".$thumbdir."\" />";
	print "<input type=\"hidden\" name=\"level\" value=\"".$level."\" />";
	print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
	print "<input type=\"hidden\" name=\"type\" value=\"media\" />";
	print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
	print "<input type=\"hidden\" name=\"subclick\">"; // This is for passing the name of which submit button was clicked
	print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print $pgv_lang["media_contains"]." <input type=\"text\" name=\"filter\" value=\"";
	if ($filter) print $filter;
	print "\" />";
	print_help_link("simple_filter_help","qm");
	print "</td></tr>";
	print "<tr><td class=\"list_label width10\" wstyle=\"padding: 5px;\">";
	print "<input type=\"checkbox\" name=\"showthumb\" value=\"true\"";
	if( $showthumb) print "checked=\"checked\"";
	print "onclick=\"javascript: this.form.submit();\" />".$pgv_lang["show_thumbnail"];
	print_help_link("show_thumb_help","qm");
	print "</td></tr>";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print "<input type=\"submit\" name=\"search\" value=\"".$pgv_lang["filter"]."\" onclick=\"this.form.subclick.value=this.name\" />&nbsp;";
	print "<input type=\"submit\" name=\"all\" value=\"".$pgv_lang["display_all"]."\" onclick=\"this.form.subclick.value=this.name\" />";
	print "</td></tr></table>";
	print "</form></div>";
}

// Show place and hide the rest
if ($type == "place") {
	print "<div align=\"center\">";
	print "<form name=\"filterplace\" method=\"get\"  onsubmit=\"return checknames(this);\" action=\"find.php\">";
	print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
	print "<input type=\"hidden\" name=\"type\" value=\"place\" />";
	print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
	print "<input type=\"hidden\" name=\"subclick\">"; // This is for passing the name of which submit button was clicked
	print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print $pgv_lang["place_contains"]." <input type=\"text\" name=\"filter\" value=\"";
	if ($filter) print $filter;
	print "\" />";
	print "</td></tr>";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print "<input type=\"submit\" name=\"search\" value=\"".$pgv_lang["filter"]."\" onclick=\"this.form.subclick.value=this.name\" />&nbsp;";
	print "<input type=\"submit\" name=\"all\" value=\"".$pgv_lang["display_all"]."\" onclick=\"this.form.subclick.value=this.name\" />";
	print "</td></tr></table>";
	print "</form></div>";
}

// Show repo and hide the rest
if ($type == "repo" && $SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) {
	print "<div align=\"center\">";
	print "<form name=\"filterrepo\" method=\"get\" onsubmit=\"return checknames(this);\" action=\"find.php\">";
	print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
	print "<input type=\"hidden\" name=\"type\" value=\"repo\" />";
	print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
	print "<input type=\"hidden\" name=\"subclick\">"; // This is for passing the name of which submit button was clicked
	print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print $pgv_lang["repo_contains"]." <input type=\"text\" name=\"filter\" value=\"";
	if ($filter) print $filter;
	print "\" />";
	print "</td></tr>";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print "<input type=\"submit\" name=\"search\" value=\"".$pgv_lang["filter"]."\" onclick=\"this.form.subclick.value=this.name\" />&nbsp;";
	print "<input type=\"submit\" name=\"all\" value=\"".$pgv_lang["display_all"]."\" onclick=\"this.form.subclick.value=this.name\" />";
	print "</td></tr></table>";
	print "</form></div>";
}

// Show source and hide the rest
if ($type == "source" && $SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) {
	print "<div align=\"center\">";
	print "<form name=\"filtersource\" method=\"get\" onsubmit=\"return checknames(this);\" action=\"find.php\">";
	print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
	print "<input type=\"hidden\" name=\"type\" value=\"source\" />";
	print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
	print "<input type=\"hidden\" name=\"subclick\">"; // This is for passing the name of which submit button was clicked
	print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print $pgv_lang["source_contains"]." <input type=\"text\" name=\"filter\" value=\"";
	if ($filter) print $filter;
	print "\" />";
	print "</td></tr>";
	print "<tr><td class=\"list_label width10\" style=\"padding: 5px;\">";
	print "<input type=\"submit\" name=\"search\" value=\"".$pgv_lang["filter"]."\" onclick=\"this.form.subclick.value=this.name\" />&nbsp;";
	print "<input type=\"submit\" name=\"all\" value=\"".$pgv_lang["display_all"]."\" onclick=\"this.form.subclick.value=this.name\" />";
	print "</td></tr></table>";
	print "</form></div>";
}

// Show specialchar and hide the rest
if ($type == "specialchar") {
	print "<div align=\"center\">";
	print "<form name=\"filterspecialchar\" method=\"get\" action=\"find.php\">";
	print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
	print "<input type=\"hidden\" name=\"type\" value=\"specialchar\" />";
	print "<input type=\"hidden\" name=\"callback\" value=\"$callback\" />";
	print "<input type=\"hidden\" name=\"magnify\" value=\"".$magnify."\" />";
	print "<table class=\"list_table $TEXT_DIRECTION width100\" border=\"0\">";
	print "<tr><td class=\"list_label\" style=\"padding: 5px;\">";
	print "<select id=\"language_filter\" name=\"language_filter\" onchange=\"submit();\">";
	print "<option value=\"\">".$pgv_lang["change_lang"]."</option>";
	$language_options = "";
	foreach($specialchar_languages as $key=>$value) {
		$language_options.= "<option value=\"$key\">$value</option>";
	}
	$language_options = str_replace("\"$language_filter\"","\"$language_filter\" selected",$language_options);
	print $language_options;
	print "</select><br /><a href=\"javascript:;\" onclick=\"setMagnify()\">".$pgv_lang["magnify"]."</a>";
	print "</td></tr></table>";
	print "</form></div>";
}
// end column for find options
print "</td></tr>";
print "</table>"; // Close table with find options

print "<br />";
print "<a href=\"javascript:;\" onclick=\"if (window.opener.showchanges) window.opener.showchanges(); window.close();\">".$pgv_lang["close_window"]."</a><br />";
print "<br />";

if ($action=="filter") {
	$filter = trim($filter);
	$filter_array=explode(' ', preg_replace('/ {2,}/', ' ', $filter));
	// Output Individual
	if ($type == "indi") {
		print "<table class=\"tabs_table $TEXT_DIRECTION width90\"><tr>";
		$myindilist=search_indis_names($filter_array, array(PGV_GED_ID), 'AND');
		if ($myindilist) {
			print "<td class=\"list_value_wrap $TEXT_DIRECTION\"><ul>";
			usort($myindilist, array('GedcomRecord', 'Compare'));
			foreach($myindilist as $indi) {
				echo $indi->format_list('li', true);
			}
			echo '</ul></td></tr><tr><td class="list_label">', $pgv_lang['total_indis'], ' ', count($myindilist), '</tr></td>';
		} else {
			print "<td class=\"list_value_wrap\">";
			print $pgv_lang["no_results"];
			print "</td></tr>";
		}
		print "</table>";
	}

	// Output Family
	if ($type == "fam") {
		print "<table class=\"tabs_table $TEXT_DIRECTION width90\"><tr>";
		// Get the famrecs with hits on names from the family table
		// Get the famrecs with hits in the gedcom record from the family table
		$myfamlist = pgv_array_merge(
			search_fams_names($filter_array, array(PGV_GED_ID), 'AND'),
			search_fams($filter_array, array(PGV_GED_ID), 'AND', true)
		);
		if ($myfamlist) {
			$curged = $GEDCOM;
			print "<td class=\"list_value_wrap $TEXT_DIRECTION\"><ul>";
			usort($myfamlist, array('GedcomRecord', 'Compare'));
			foreach($myfamlist as $family) {
				echo $family->format_list('li', true);
			}
			echo '</ul></td></tr><tr><td class="list_label">', $pgv_lang['total_fams'], ' ', count($myfamlist), '</tr></td>';
		} else {
			print "<td class=\"list_value_wrap\">";
			print $pgv_lang["no_results"];
			print "</td></tr>";
		}
		print "</table>";
	}

	// Output Media
	if ($type == "media") {
		global $dirs;

		$medialist = get_medialist(true, $directory);

		print "<table class=\"tabs_table $TEXT_DIRECTION width90\">";
		// Show link to previous folder
		if ($level>0) {
			$levels = explode("/", $directory);
			$pdir = "";
			for($i=0; $i<count($levels)-2; $i++) $pdir.=$levels[$i]."/";
			$levels = explode("/", $thumbdir);
			$pthumb = "";
			for($i=0; $i<count($levels)-2; $i++) $pthumb.=$levels[$i]."/";
			$uplink = "<a href=\"".encode_url("find.php?directory={$pdir}&thumbdir={$pthumb}&level=".($level-1)."{$thumbget}&type=media&choose={$choose}")."\">&nbsp;&nbsp;&nbsp;&lt;-- <span dir=\"ltr\">".$pdir."</span>&nbsp;&nbsp;&nbsp;</a><br />";
		}

		// Start of media directory table
		print "<table class=\"list_table $TEXT_DIRECTION width90\">";

		// Tell the user where he is
		print "<tr>";
			print "<td class=\"topbottombar\" colspan=\"2\">";
				print $pgv_lang["current_dir"];
				print "<br />";
				print substr($directory,0,-1);
			print "</td>";
		print "</tr>";

		// display the directory list
		if (count($dirs) || $level) {
			sort($dirs);
			if ($level){
				print "<tr><td class=\"list_value $TEXT_DIRECTION\" colspan=\"2\">";
				print $uplink."</td></tr>";
			}
			print "<tr><td class=\"descriptionbox $TEXT_DIRECTION\" colspan=\"2\">";
			print "<a href=\"".encode_url("find.php?directory={$directory}&thumbdir=".str_replace($MEDIA_DIRECTORY, $MEDIA_DIRECTORY."thumbs/", $directory)."&level={$level}{$thumbget}&external_links=http&type=media&choose={$choose}")."\">".$pgv_lang["external_objects"]."</a>";
			print "</td></tr>";
			foreach ($dirs as $indexval => $dir) {
				print "<tr><td class=\"list_value $TEXT_DIRECTION\" colspan=\"2\">";
				print "<a href=\"".encode_url("find.php?directory={$directory}{$dir}/&thumbdir={$directory}{$dir}/&level=".($level+1)."{$thumbget}&type=media&choose={$choose}")."\"><span dir=\"ltr\">".$dir."</span></a>";
				print "</td></tr>";
			}
		}
		print "<tr><td class=\"descriptionbox $TEXT_DIRECTION\" colspan=\"2\"></td></tr>";

		/**
		 * This action generates a thumbnail for the file
		 *
		 * @name $create->thumbnail
		 */
		if ($create=="thumbnail") {
			$filename = $_REQUEST["file"];
			generate_thumbnail($directory.$filename,$thumbdir.$filename);
		}

		print "<br />";

		// display the images TODO x across if lots of files??
		if (count($medialist) > 0) {
			foreach ($medialist as $indexval => $media) {

				// Check if the media belongs to the current folder
				preg_match_all("/\//", $media["FILE"], $hits);
				$ct = count($hits[0]);

				if (($ct <= $level+1 && $external_links != "http" && !isFileExternal($media["FILE"])) || (isFileExternal($media["FILE"]) && $external_links == "http")) {
					// simple filter to reduce the number of items to view
					$isvalid = filterMedia($media, $filter, 'http');
					if ($isvalid && $chooseType!="all") {
						if ($chooseType=="file" && !empty($media["XREF"])) $isvalid = false; // skip linked media files
						if ($chooseType=="media" && empty($media["XREF"])) $isvalid = false; // skip unlinked media files
					}

					if ($isvalid) {
						if ($media["EXISTS"] && media_filesize($media["FILE"]) != 0){
							$imgsize = findImageSize($media["FILE"]);
							$imgwidth = $imgsize[0]+40;
							$imgheight = $imgsize[1]+150;
						}
						else {
							$imgwidth = 0;
							$imgheight = 0;
						}

						print "<tr>";

						//-- thumbnail field
						if ($showthumb) {
							print "<td class=\"list_value $TEXT_DIRECTION width10\">";
							if (isset($media["THUMB"])) print "<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($media["FILE"])."',$imgwidth, $imgheight);\"><img src=\"".filename_decode($media["THUMB"])."\" border=\"0\" width=\"50\" alt=\"\" /></a>";
							else print "&nbsp;";
						}

						//-- name and size field
						print "<td class=\"list_value $TEXT_DIRECTION\">";
						if ($media["TITL"] != "") {
							print "<b>".PrintReady($media["TITL"])."</b>&nbsp;&nbsp;";
							if ($TEXT_DIRECTION=="rtl") print getRLM();
							print "(".$media["XREF"].")";
							if ($TEXT_DIRECTION=="rtl") print getRLM();
							print "<br />";
						}
						if (!$embed){
							print "<a href=\"javascript:;\" onclick=\"pasteid('".addslashes($media["FILE"])."');\"><span dir=\"ltr\">".$media["FILE"]."</span></a> -- ";
						}
						else print "<a href=\"javascript:;\" onclick=\"pasteid('".$media["XREF"]."','".addslashes($media["TITL"])."','".addslashes($media["THUMB"])."');\"><span dir=\"ltr\">".$media["FILE"]."</span></a> -- ";
						print "<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($media["FILE"])."',$imgwidth, $imgheight);\">".$pgv_lang["view"]."</a><br />";
						if (!$media["EXISTS"] && !isFileExternal($media["FILE"])) print $media["FILE"]."<br /><span class=\"error\">".$pgv_lang["file_not_exists"]."</span><br />";
						else if (!isFileExternal($media["FILE"]) && !empty($imgsize[0])) {
							print "<br /><sub>&nbsp;&nbsp;".$pgv_lang["image_size"]." -- ".$imgsize[0]."x".$imgsize[1]."</sub><br />";
						}
						if ($media["LINKED"]) {
							print $pgv_lang["media_linked"]."<br />";
							foreach ($media["LINKS"] as $indi => $type_record) {
								if ($type_record!='INDI' && $type_record!='FAM' && $type_record!='SOUR' && $type_record!='OBJE') continue;
								$record=GedcomRecord::getInstance($indi);
								echo '<br /><a href="'.encode_url($record->getLinkUrl()).'">';
								switch($type_record) {
								case 'INDI':
									echo $pgv_lang['view_person'], ' - ';
									break;
								case 'FAM':
									echo $pgv_lang['view_family'], ' - ';
									break;
								case 'SOUR':
									echo $pgv_lang['view_source'], ' - ';
									break;
								case 'OBJE':
									echo $pgv_lang['view_object'], ' - ';
									break;
								}
								echo PrintReady($record->getFullName()), '</a>';
							}
						} else {
							print $pgv_lang["media_not_linked"];
						}
						print "</td>";
					}
				}
			}
		}
		else {
			print "<tr><td class=\"list_value_wrap\">";
			print $pgv_lang["no_results"];
			print "</td></tr>";
		}
		print "</table>";
	}

	// Output Places
	if ($type == "place") {
		print "<table class=\"tabs_table $TEXT_DIRECTION width90\"><tr>";
		$placelist = array();
		if ($all || $filter)
		{
			find_place_list($filter);
			uasort($placelist, "stringsort");
			$ctplace = count($placelist);
			if ($ctplace>0) {
				print "<td class=\"list_value_wrap $TEXT_DIRECTION\"><ul>";
				foreach($placelist as $indexval => $revplace) {
					$levels = explode(',', $revplace); // -- split the place into comma seperated values
					$levels = array_reverse($levels); // -- reverse the array so that we get the top level first
					$placetext="";
					$j=0;
					foreach($levels as $indexval => $level) {
						if ($j>0) $placetext .= ", ";
						$placetext .= trim($level);
						$j++;
					}
					print "<li><a href=\"javascript:;\" onclick=\"pasteid('".preg_replace(array("/'/",'/"/'), array("\'",'&quot;'), $placetext)."');\">".PrintReady($revplace)."</a></li>";
				}
				print "</ul></td></tr>";
				print "<tr><td class=\"list_label\">".$pgv_lang["total_places"]." ".$ctplace;
				print "</td></tr>";
			}
			else {
				print "<tr><td class=\"list_value_wrap $TEXT_DIRECTION\"><ul>";
				print $pgv_lang["no_results"];
				print "</td></tr>";
			}
		}
		print "</table>";
	}

	// Output Repositories
	if ($type == "repo") {
		print "<table class=\"tabs_table $TEXT_DIRECTION width90\"><tr>";
		$repo_list = get_repo_list(PGV_GED_ID);
		if ($repo_list) {
			print "<td class=\"list_value_wrap\"><ul>";
			foreach ($repo_list as $repo) {
				echo "<li><a href=\"javascript:;\" onclick=\"pasteid('".$repo->getXref()."');\"><span class=\"list_item\">".$repo->getListName()."&nbsp;&nbsp;&nbsp;";
				echo PGV_LPARENS.$repo->getXref().PGV_RPARENS;
				echo "</span></a></li>";
			}
			print "</ul></td></tr>";
			print "<tr><td class=\"list_label\">".$pgv_lang["repos_found"]." ".count($repo_list);
			print "</td></tr>";
		}
		else {
			print "<tr><td class=\"list_value_wrap\">";
			print $pgv_lang["no_results"];
			print "</td></tr>";
		}
		print "</table>";

	}
	// Output Sources
	if ($type=="source") {
		echo '<table class="tabs_table ', $TEXT_DIRECTION, ' width90"><tr><td class="list_value"><tr>';
		if ($filter) {
			$mysourcelist = search_sources($filter_array, array(PGV_GED_ID), 'AND', true);
		} else {
			$mysourcelist = get_source_list(PGV_GED_ID);
		}
		if ($mysourcelist) {
			usort($mysourcelist, array('GedcomRecord', 'Compare'));
			echo '<td class="list_value_wrap"><ul>';
			foreach ($mysourcelist as $source) {
				echo '<li><a href="javascript:;" onclick="pasteid(\'', $source->getXref(), "', '", preg_replace("/(['\"])/", "\\$1", PrintReady($source->getFullName())), '\'); return false;"><span class="list_item">', PrintReady($source->getFullName()), '</span></a></li>';
			}
			echo '</ul></td></tr><tr><td class="list_label">', $pgv_lang['total_sources'], ' ', count($mysourcelist), '</td></tr>';
		}
		else {
			echo '<tr><td class="list_value_wrap">', $pgv_lang['no_results'], '</td></tr>';
		}
		print '</table>';
		if (PGV_USER_CAN_EDIT) {
			print_help_link('edit_add_unlinked_source_help', 'qm'); ?><a href="javascript: <?php print $pgv_lang['add_unlinked_source']; ?>" onclick="addnewsource(''); return false;"><?php print $pgv_lang['add_unlinked_source']; ?></a>
		<?php
		}
	}

	// Output Special Characters
	if ($type == "specialchar") {
		print "<table class=\"tabs_table $TEXT_DIRECTION width90\"><tr><td class=\"list_value center wrap\" dir=\"$TEXT_DIRECTION\"><br/>";
		// lower case special characters
		if ($magnify) {
			echo '<span class="largechars">';
		}
		foreach($lcspecialchars as $key=>$value) {
			$value = str_replace("'","\'",$value);
			print "<a href=\"javascript:;\" onclick=\"return paste_char('$value','$language_filter','$magnify');\">";
			print $key;
			print "</span></a> ";
		}
		if ($magnify) {
			echo '<span class="largechars">';
		}
		echo '<br/><br/>';
		//upper case special characters
		if ($magnify) {
			echo '<span class="largechars">';
		}
		foreach($ucspecialchars as $key=>$value) {
			$value = str_replace("'","\'",$value);
			print "<a href=\"javascript:;\" onclick=\"return paste_char('$value','$language_filter','$magnify');\">";
			print $key;
			print "</span></a> ";
		}
		if ($magnify) {
			echo '<span class="largechars">';
		}
		echo '<br/><br/>';
		// other special characters (not letters)
		if ($magnify) {
			echo '<span class="largechars">';
		}
		foreach($otherspecialchars as $key=>$value) {
			$value = str_replace("'","\'",$value);
			print "<a href=\"javascript:;\" onclick=\"return paste_char('$value','$language_filter','$magnify');\">";
			print $key;
			print "</span></a> ";
		}
		if ($magnify) {
			echo '<span class="largechars">';
		}
		echo '<br/><br/></td></tr></table>';
	}
}
print "</div>"; // Close div that centers table

// Set focus to the input field
echo PGV_JS_START, 'document.filter', $type, '.filter.focus();', PGV_JS_END;

print_simple_footer();

?>
