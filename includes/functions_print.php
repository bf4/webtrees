<?php
/**
 * Function for printing
 *
 * Various printing functions used by all scripts and included by the functions.php file.
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

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

require_once 'includes/functions_charts.php';
require_once 'includes/menu.php';

/**
 * print the information for an individual chart box
 *
 * find and print a given individuals information for a pedigree chart
 * @param string $pid	the Gedcom Xref ID of the   to print
 * @param int $style	the style to print the box in, 1 for smaller boxes, 2 for larger boxes
 * @param boolean $show_famlink	set to true to show the icons for the popup links and the zoomboxes
 * @param int $count	on some charts it is important to keep a count of how many boxes were printed
 */
function print_pedigree_person($pid, $style=1, $show_famlink=true, $count=0, $personcount="1") {
	global $HIDE_LIVE_PEOPLE, $SHOW_LIVING_NAMES, $PRIV_PUBLIC, $factarray, $ZOOM_BOXES, $LINK_ICONS, $view, $SCRIPT_NAME, $GEDCOM;
	global $pgv_lang, $MULTI_MEDIA, $SHOW_HIGHLIGHT_IMAGES, $bwidth, $bheight, $PEDIGREE_FULL_DETAILS, $SHOW_ID_NUMBERS, $SHOW_PEDIGREE_PLACES;
	global $CONTACT_EMAIL, $CONTACT_METHOD, $TEXT_DIRECTION, $DEFAULT_PEDIGREE_GENERATIONS, $OLD_PGENS, $talloffset, $PEDIGREE_LAYOUT, $MEDIA_DIRECTORY;
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $ABBREVIATE_CHART_LABELS, $USE_MEDIA_VIEWER;
	global $chart_style, $box_width, $generations, $show_spouse, $show_full;
	global $CHART_BOX_TAGS, $SHOW_LDS_AT_GLANCE, $PEDIGREE_SHOW_GENDER;
	global $SEARCH_SPIDER;

	if ($style != 2) $style=1;
	if (empty($show_full)) $show_full = 0;
	if (empty($PEDIGREE_FULL_DETAILS)) $PEDIGREE_FULL_DETAILS = 0;

	if (!isset($OLD_PGENS)) $OLD_PGENS = $DEFAULT_PEDIGREE_GENERATIONS;
	if (!isset($talloffset)) $talloffset = $PEDIGREE_LAYOUT;
	// NOTE: Start div out-rand()
	$person=Person::getInstance($pid);
	if ($pid==false || empty($person)) {
		print "<div id=\"out-".rand()."\" class=\"person_boxNN\" style=\"width: ".$bwidth."px; height: ".$bheight."px; padding: 2px; overflow: hidden;\">";
		print "<br />";
		print "</div>";
		return false;
	}
	if ($count==0) $count = rand();
	$lbwidth = $bwidth*.75;
	if ($lbwidth < 150) $lbwidth = 150;
	
	$tmp=array('M'=>'','F'=>'F', 'U'=>'NN');
	$isF=$tmp[$person->getSex()];

	$personlinks = "";
	$icons = "";
	$classfacts = "";
	$genderImage = "";
	$BirthDeath = "";
	$outBoxAdd = "";
	$thumbnail = "";
	$showid = "";
	$iconsStyleAdd = "float: right; ";
	if ($TEXT_DIRECTION=="rtl") $iconsStyleAdd="float: left; ";

	$disp=$person->canDisplayDetails();

	$boxID = $pid.".".$personcount.".".$count;
	$mouseAction1 = "onmouseover=\"clear_family_box_timeout('".$boxID."');\" onmouseout=\"family_box_timeout('".$boxID."');\"";
	$mouseAction2 = " onmouseover=\"expandbox('".$boxID."', $style); return false;\" onmouseout=\"restorebox('".$boxID."', $style); return false;\"";
	$mouseAction3 = " onmousedown=\"expandbox('".$boxID."', $style); return false;\" onmouseup=\"restorebox('".$boxID."', $style); return false;\"";
	$mouseAction4 = " onclick=\"expandbox('".$boxID."', $style); return false;\"";
	if ($person->canDisplayName()) {
		if ($show_famlink && (empty($SEARCH_SPIDER))) {
			if ($LINK_ICONS!="disabled") {
				//-- draw a box for the family popup
				// NOTE: Start div I.$pid.$personcount.$count.links
				$personlinks .= "\n\t\t\t<table class=\"person_box$isF\"><tr><td class=\"details1\">";
				// NOTE: Zoom
				if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["pedigree_chart"].": ".$pid;
				else $title = $pid." :".$pgv_lang["pedigree_chart"];
				$personlinks .= "<a href=\"".encode_url("pedigree.php?rootid={$pid}&show_full={$PEDIGREE_FULL_DETAILS}&PEDIGREE_GENERATIONS={$OLD_PGENS}&talloffset={$talloffset}&ged={$GEDCOM}")."\" title=\"$title\" $mouseAction1><b>".$pgv_lang["index_header"]."</b></a>";

				if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["descend_chart"].": ".$pid;
				else $title = $pid." :".$pgv_lang["descend_chart"];
				$personlinks .= "<br /><a href=\"".encode_url("descendancy.php?pid={$pid}&show_full={$PEDIGREE_FULL_DETAILS}&generations={$generations}&box_width={$box_width}&ged={$GEDCOM}")."\" title=\"$title\" $mouseAction1><b>".$pgv_lang["descend_chart"]."</b></a><br />";

				$username = PGV_USER_NAME;
				if (!empty($username)) {
					$myid=PGV_USER_GEDCOM_ID;
					if ($myid) {
						if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["relationship_chart"].": ".$pid;
						else $title = $pid." :".$pgv_lang["relationship_chart"];
						$personlinks .= "<a href=\"".encode_url("relationship.php?show_full={$PEDIGREE_FULL_DETAILS}&pid1={$myid}&pid2={$pid}&show_full={$PEDIGREE_FULL_DETAILS}&pretty=2&followspouse=1&ged={$GEDCOM}")."\" title=\"$title\" ".$mouseAction1."><b>".$pgv_lang["relationship_to_me"]."</b></a><br />";
					}
				}
				// NOTE: Zoom
				if (file_exists("ancestry.php")) {
					if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["ancestry_chart"].": ".$pid;
					else $title = $pid." :".$pgv_lang["ancestry_chart"];
					$personlinks .= "<a href=\"".encode_url("ancestry.php?rootid={$pid}&show_full={$PEDIGREE_FULL_DETAILS}&chart_style={$chart_style}&PEDIGREE_GENERATIONS={$OLD_PGENS}&box_width={$box_width}&ged={$GEDCOM}")."\" title=\"$title\" ".$mouseAction1."><b>".$pgv_lang["ancestry_chart"]."</b></a><br />";
				}
				if (file_exists("compact.php")) {
					if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["compact_chart"].": ".$pid;
					else $title = $pid." :".$pgv_lang["compact_chart"];
					$personlinks .= "<a href=\"".encode_url("compact.php?rootid={$pid}&ged={$GEDCOM}")."\" title=\"$title\" ".$mouseAction1."><b>".$pgv_lang["compact_chart"]."</b></a><br />";
				}
				if (file_exists("fanchart.php") and defined("IMG_ARC_PIE") and function_exists("imagettftext")) {
					if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["fan_chart"].": ".$pid;
					else $title = $pid." :".$pgv_lang["fan_chart"];
					$personlinks .= "<a href=\"".encode_url("fanchart.php?rootid={$pid}&PEDIGREE_GENERATIONS={$OLD_PGENS}&ged={$GEDCOM}")."\" title=\"$title\" ".$mouseAction1."><b>".$pgv_lang["fan_chart"]."</b></a><br />";
				}
				if (file_exists("hourglass.php")) {
					if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["hourglass_chart"].": ".$pid;
					else $title = $pid." :".$pgv_lang["hourglass_chart"];
					$personlinks .= "<a href=\"".encode_url("hourglass.php?pid={$pid}&show_full={$PEDIGREE_FULL_DETAILS}&chart_style={$chart_style}&PEDIGREE_GENERATIONS={$OLD_PGENS}&box_width={$box_width}&ged={$GEDCOM}&show_spouse={$show_spouse}")."\" title=\"$title\" ".$mouseAction1."><b>".$pgv_lang["hourglass_chart"]."</b></a><br />";
				}
					
				$fams = $person->getSpouseFamilies();
				/* @var $family Family */
				foreach($fams as $famid=>$family) {
					if (!is_null($family)) {
						$spouse = $family->getSpouse($person);
							
						$children = $family->getChildren();
						$num = count($children);
						if ((!empty($spouse))||($num>0)) {
							if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["familybook_chart"].": ".$famid;
							else $title = $famid." :".$pgv_lang["familybook_chart"];
							$personlinks .= "<a href=\"".encode_url("family.php?famid={$famid}&show_full=1&ged={$GEDCOM}")."\" title=\"$title\" ".$mouseAction1."><b>".$pgv_lang["fam_spouse"]."</b></a><br />";
							if (!empty($spouse)) {
								if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["indi_info"].": ".$spouse->getXref();
								else $title = $spouse->getXref()." :".$pgv_lang["indi_info"];
								$tmp=$spouse->getXref();
								$personlinks .= "<a href=\"".encode_url("individual.php?pid={$tmp}&ged={$GEDCOM}")."\" title=\"$title\" $mouseAction1>";
								if ($spouse->canDisplayName()) $personlinks .= PrintReady($spouse->getName());
								else $personlinks .= $pgv_lang["private"];
								$personlinks .= "</a><br />\n";
							}
						}
						/* @var $child Person */
						foreach($children as $c=>$child) {
							$cpid = $child->getXref();
							if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["indi_info"].": ".$cpid;
							else $title = $cpid." :".$pgv_lang["indi_info"];
							$personlinks .= "\n\t\t\t\t&nbsp;&nbsp;<a href=\"individual.php?pid=$cpid&amp;ged=$GEDCOM\" title=\"$title\" $mouseAction1>";
							if ($child->canDisplayName()) $personlinks .= PrintReady($child->getName());
							else $personlinks .= $pgv_lang["private"];
							$personlinks .= "<br /></a>";
						}
					}
				}
				$personlinks .= "</td></tr></table>\n\t\t";
			}
			// NOTE: Start div out-$pid.$personcount.$count
			if ($style==1) $outBoxAdd .= " class=\"person_box$isF\" style=\"width: ".$bwidth."px; height: ".$bheight."px; padding: 2px; overflow: hidden; z-index:'-1';\"";
			else $outBoxAdd .= " style=\"padding: 2px;\"";
			// NOTE: Zoom
			if (($ZOOM_BOXES!="disabled")&&(!$show_full)) {
				if ($ZOOM_BOXES=="mouseover") $outBoxAdd .= $mouseAction2;
				if ($ZOOM_BOXES=="mousedown") $outBoxAdd .= $mouseAction3;
				if (($ZOOM_BOXES=="click")&&($view!="preview")) $outBoxAdd .= $mouseAction4;
			}
			//-- links and zoom icons
			// NOTE: Start div icons-$personcount.$pid.$count
			if ($show_full) $iconsStyleAdd .= " display: block;";
			else $iconsStyleAdd .= " display: none;";
			//print "\">";
			// NOTE: Zoom
			if (($ZOOM_BOXES!="disabled")&&($show_full)) {
				$icons .= "<a href=\"javascript:;\"";
				if ($ZOOM_BOXES=="mouseover") $icons .= $mouseAction2;
				if ($ZOOM_BOXES=="mousedown") $icons .= $mouseAction3;
				if ($ZOOM_BOXES=="click") $icons .= $mouseAction4;
				$icons .= "><img id=\"iconz-$boxID\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["zoomin"]["other"]."\" width=\"25\" height=\"25\" border=\"0\" alt=\"".$pgv_lang["zoom_box"]."\" title=\"".$pgv_lang["zoom_box"]."\" /></a>";
			}
			if ($LINK_ICONS!="disabled") {
				$click_link="javascript:;";
				$whichChart="";
				if (preg_match("/pedigree.php/", $SCRIPT_NAME)>0) {
					$click_link=encode_url("pedigree.php?rootid={$pid}&show_full={$PEDIGREE_FULL_DETAILS}&PEDIGREE_GENERATIONS={$OLD_PGENS}&talloffset={$talloffset}&ged={$GEDCOM}");
					$whichChart="pedigree_chart";
					$whichID=$pid;
				}

				if (preg_match("/hourglass.php/", $SCRIPT_NAME)>0) {
					$click_link=encode_url("hourglass.php?pid={$pid}&show_full={$PEDIGREE_FULL_DETAILS}&generations={$generations}&box_width={$box_width}&ged={$GEDCOM}");
					$whichChart="hourglass_chart";
					$whichID=$pid;
				}

				if (preg_match("/ancestry.php/", $SCRIPT_NAME)>0) {
					$click_link=encode_url("ancestry.php?rootid={$pid}&show_full={$PEDIGREE_FULL_DETAILS}&chart_style={$chart_style}&PEDIGREE_GENERATIONS={$OLD_PGENS}&box_width={$box_width}&ged={$GEDCOM}");
					$whichChart="ancestry_chart";
					$whichID=$pid;
				}

				if (preg_match("/descendancy.php/", $SCRIPT_NAME)>0) {
					$click_link=encode_url("descendancy.php?&show_full={$PEDIGREE_FULL_DETAILS}&pid={$pid}&agenerations={$generations}&box_width={$box_width}&ged={$GEDCOM}");
					$whichChart="descend_chart";
					$whichID=$pid;
				}

				if ((preg_match("/family.php/", $SCRIPT_NAME)>0)&&!empty($famid)) {
					$click_link=encode_url("family.php?famid={$famid}&show_full=1&ged={$GEDCOM}");
					$whichChart="familybook_chart";
					$whichID=$famid;
				}

				if (preg_match("/individual.php/", $SCRIPT_NAME)>0) {
					$click_link=encode_url("individual.php?pid={$pid}&ged={$GEDCOM}");
					$whichChart="indi_info";
					$whichID=$pid;
				}

				if (empty($whichChart)) $title="";
				else {
					if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang[$whichChart].": ".$whichID;
					else $title = $whichID." :".$pgv_lang[$whichChart];
				}
				$icons .= "<a href=\"$click_link\" title=\"$title\" ";
				// NOTE: Zoom
				if ($LINK_ICONS=="mouseover") $icons .= "onmouseover=\"show_family_box('".$boxID."', '";
				if ($LINK_ICONS=="click") $icons .= "onclick=\"toggle_family_box('".$boxID."', '";
				if ($style==1) $icons .= "box$pid";
				else $icons .= "relatives";
				$icons .= "');";
				$icons .= " return false;\" ";
				// NOTE: Zoom
				$icons .= "onmouseout=\"family_box_timeout('".$boxID."');";
				$icons .= " return false;\"";
				if (($click_link=="#")&&($LINK_ICONS!="click")) $icons .= "onclick=\"return false;\"";
				$icons .= "><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["small"]."\" width=\"25\" border=\"0\" vspace=\"0\" hspace=\"0\" alt=\"".$pgv_lang["person_links"]."\" title=\"".$pgv_lang["person_links"]."\" /></a>";
			}
		}
		else {
			if ($style==1) {
				$outBoxAdd .= "class=\"person_box$isF\" style=\"width: ".$bwidth."px; height: ".$bheight."px; padding: 2px; overflow: hidden;\"";
			} else {
				$outBoxAdd .= "class=\"person_box$isF\" style=\"padding: 2px; overflow: hidden;\"";
			}
			// NOTE: Zoom
			if (($ZOOM_BOXES!="disabled")&&(empty($SEARCH_SPIDER))) {
				if ($ZOOM_BOXES=="mouseover") $outBoxAdd .= $mouseAction2;
				if ($ZOOM_BOXES=="mousedown") $outBoxAdd .= $mouseAction3;
				if (($ZOOM_BOXES=="click")&&($view!="preview")) $outBoxAdd .= $mouseAction4;
			}
		}
	}
	else {
		if ($style==1) $outBoxAdd .= "class=\"person_box$isF\" style=\"width: ".$bwidth."px; height: ".$bheight."px; padding: 2px; overflow: hidden;\"";
		else $outBoxAdd .= "class=\"person_box$isF\" style=\"padding: 2px; overflow: hidden;\"";
	}
	//-- find the name
	$name = $person->getFullName();
	if ($MULTI_MEDIA && $SHOW_HIGHLIGHT_IMAGES && showFact("OBJE", $pid)) {
		$object = $person->findHighlightedMedia();
		if (!empty($object["thumb"])) {
			$size = findImageSize($object["thumb"]);
			$class = "pedigree_image_portrait";
			if ($size[0]>$size[1]) $class = "pedigree_image_landscape";
			if($TEXT_DIRECTION == "rtl") $class .= "_rtl";
			// NOTE: IMG ID
			$imgsize = findImageSize($object["file"]);
			$imgwidth = $imgsize[0]+50;
			$imgheight = $imgsize[1]+150;
		
			//LBox --------  change for Lightbox Album --------------------------------------------
			if (file_exists("modules/lightbox/album.php")) {
				$thumbnail .= "<a href=\"" . $object["file"] . "\" rel=\"clearbox[general_2]\" rev=\"" . $object['mid'] . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name,ENT_COMPAT,'UTF-8')) . "\">";
			}else
			// ---------------------------------------------------------------------------------------------
			
			if (!empty($object['mid']) && $USE_MEDIA_VIEWER) {
				$thumbnail .= "<a href=\"".encode_url("mediaviewer.php?mid=".$object['mid'])."\" >";
			}else{
				$thumbnail .= "<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($object["file"])."',$imgwidth, $imgheight);\">";
			}
			$thumbnail .= "<img id=\"box-$boxID-thumb\" src=\"".$object["thumb"]."\" vspace=\"0\" hspace=\"0\" class=\"$class\" alt=\"\" title=\"".strip_tags($name)."\"";
			if (!$show_full) $thumbnail .= " style=\"display: none;\"";
			if ($imgsize) $thumbnail .= " /></a>";
			else $thumbnail .= " />";
		}
	}
	//-- find additional name
	$addname=$person->getAddName();
	$name = PrintReady($name);

	if ($TEXT_DIRECTION=="ltr") $title = $pgv_lang["indi_info"].": ".$pid;
	else $title = $pid." :".$pgv_lang["indi_info"];
	// add optional CSS style for each fact
	$indirec = $person->getGedcomRecord();
	$cssfacts = array("BIRT","CHR","DEAT","BURI","CREM","ADOP","BAPM","BARM","BASM","BLES","CHRA","CONF","FCOM","ORDN","NATU","EMIG","IMMI","CENS","PROB","WILL","GRAD","RETI","CAST","DSCR","EDUC","IDNO",
	"NATI","NCHI","NMR","OCCU","PROP","RELI","RESI","SSN","TITL","BAPL","CONL","ENDL","SLGC","_MILI");
	foreach($cssfacts as $indexval => $fact) {
		$ct = preg_match("/1 $fact/", $indirec, $nmatch);
		if ($ct>0) $classfacts .= " $fact";
	}
	if ($PEDIGREE_SHOW_GENDER)
		$genderImage = " ".$person->getSexImage('', "box-$boxID-gender");
	if ($SHOW_ID_NUMBERS) {
		if ($TEXT_DIRECTION=="ltr") $showid .= "<span class=\"details$style\">" . getLRM() . "($pid)" . getLRM() . " </span>";
		else $showid .= "<span class=\"details$style\">" . getRLM() . "($pid)" . getRLM() . " </span>";
	}
	if (strlen($addname) > 0) {
		$tempStyle = $style;
		if (hasRTLText($addname) && $style=='1') $tempStyle = '2';
		$addname = "<br /><span id=\"addnamedef-$boxID\" class=\"name$tempStyle\"> ".PrintReady($addname)."</span>";
	}
	if ($SHOW_LDS_AT_GLANCE) {
		$addname = ' <span class="details$style">'.get_lds_glance($indirec).'</span>' . $addname;
	}

		if ($show_full) {

			$opt_tags=preg_split('/\W/', $CHART_BOX_TAGS, 0, PREG_SPLIT_NO_EMPTY);

			// Show BIRT or equivalent event
			foreach (explode('|', PGV_EVENTS_BIRT) as $birttag) {
			if (!in_array($birttag, $opt_tags)) {
				$event = $person->getFactByType($birttag);
				if (!is_null($event) && $event->canShow()) {
					$BirthDeath .= $event->print_simple_fact(true);
					break;
					}
				}
			}

			// Show optional events (before death)
			foreach ($opt_tags as $key=>$tag) {
			if (!preg_match('/^('.PGV_EVENTS_DEAT.')$/', $tag)) {
				$event = $person->getFactByType($tag);
				if (!is_null($event) && $event->canShow()) {
					$BirthDeath .= $event->print_simple_fact(true);
					unset ($opt_tags[$key]);
				}
			}
		}

			// Show DEAT or equivalent event
			foreach (explode('|', PGV_EVENTS_DEAT) as $deattag) {
			$event = $person->getFactByType($deattag);
			if (!is_null($event) && $event->canShow()) {
				$BirthDeath .= $event->print_simple_fact(true);
					if (in_array($deattag, $opt_tags)) {
						unset ($opt_tags[array_search($deattag, $opt_tags)]);
					}
					break;
				}
			}

			// Show remaining optional events (after death)
			foreach ($opt_tags as $tag) {
			$event = $person->getFactByType($tag);
			if (!is_null($event) && $event->canShow()) {
				$BirthDeath .= $event->print_simple_fact(true);
				}
			}
		}
	global $THEME_DIR;
	include($THEME_DIR."templates/personbox_template.php");
}

/**
 * print out standard HTML header
 *
 * This function will print out the HTML, HEAD, and BODY tags and will load in the CSS javascript and
 * other auxiliary files needed to run PGV.  It will also include the theme specific header file.
 * This function should be called by every page, except popups, before anything is output.
 *
 * Popup pages, because of their different format, should invoke function print_simple_header() instead.
 *
 * @param string $title	the title to put in the <TITLE></TITLE> header tags
 * @param string $head
 * @param boolean $use_alternate_styles
 */
function print_header($title, $head="",$use_alternate_styles=true) {
	global $pgv_lang, $bwidth;
	global $HOME_SITE_URL, $HOME_SITE_TEXT, $SERVER_URL;
	global $BROWSERTYPE, $SEARCH_SPIDER;
	global $view, $cart;
	global $CHARACTER_SET, $PGV_IMAGE_DIR, $GEDCOMS, $GEDCOM, $GEDCOM_TITLE, $CONTACT_EMAIL, $COMMON_NAMES_THRESHOLD, $INDEX_DIRECTORY;
	global $SCRIPT_NAME, $QUERY_STRING, $action, $query, $changelanguage,$theme_name;
	global $FAVICON, $stylesheet, $print_stylesheet, $rtl_stylesheet, $headerfile, $toplinks, $THEME_DIR, $print_headerfile;
	global $PGV_IMAGES, $TEXT_DIRECTION, $ONLOADFUNCTION,$REQUIRE_AUTHENTICATION, $SHOW_SOURCES, $ENABLE_RSS, $RSS_FORMAT;
	global $META_AUTHOR, $META_PUBLISHER, $META_COPYRIGHT, $META_DESCRIPTION, $META_PAGE_TOPIC, $META_AUDIENCE, $META_PAGE_TYPE, $META_ROBOTS, $META_REVISIT, $META_KEYWORDS, $META_TITLE, $META_SURNAME_KEYWORDS;

	// If not on allowed list, dump the spider onto the redirect page.
	// This kills recognized spiders in their tracks.
	// To stop unrecognized spiders, see META_ROBOTS below.
	if(!empty($SEARCH_SPIDER)) {
		if(!((strstr($SCRIPT_NAME, "/individual.php")) ||
		     (strstr($SCRIPT_NAME, "/indilist.php")) ||
		     (strstr($SCRIPT_NAME, "/login.php")) ||
		     (strstr($SCRIPT_NAME, "/family.php")) ||
		     (strstr($SCRIPT_NAME, "/famlist.php")) ||
		     (strstr($SCRIPT_NAME, "/help_text.php")) ||
		     (strstr($SCRIPT_NAME, "/source.php")) ||
		     (strstr($SCRIPT_NAME, "/search_engine.php")) ||
		     (strstr($SCRIPT_NAME, "/index.php"))) ) {
			header("Location: search_engine.php");
			exit;
		}
	}
	header("Content-Type: text/html; charset=$CHARACTER_SET");

	// Determine browser type
	$BROWSERTYPE = "other";
	if (!empty($_SERVER["HTTP_USER_AGENT"])) {
		if (stristr($_SERVER["HTTP_USER_AGENT"], "Opera"))
			$BROWSERTYPE = "opera";
		else if (stristr($_SERVER["HTTP_USER_AGENT"], "Netscape"))
			$BROWSERTYPE = "netscape";
		else if (stristr($_SERVER["HTTP_USER_AGENT"], "Gecko"))
			$BROWSERTYPE = "mozilla";
		else if (stristr($_SERVER["HTTP_USER_AGENT"], "MSIE"))
			$BROWSERTYPE = "msie";
	}

	print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	print "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n\t<head>\n\t\t";
	print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$CHARACTER_SET\" />\n\t\t";
	if( $FAVICON ) {
		print "<link rel=\"shortcut icon\" href=\"$FAVICON\" type=\"image/x-icon\" />\n";
	}

	if (empty($META_TITLE)) $metaTitle = ' - '.PGV_PHPGEDVIEW;
	else $metaTitle = " - ".$META_TITLE.' - '.PGV_PHPGEDVIEW;
	print "<title>".PrintReady(strip_tags($title.$metaTitle), TRUE)."</title>\n\t";
	$GEDCOM_TITLE = get_gedcom_setting(PGV_GED_ID, 'title');
	if ($ENABLE_RSS){
		$applicationType = "application/rss+xml";
		if ($RSS_FORMAT == "ATOM" || $RSS_FORMAT == "ATOM0.3"){
			$applicationType = "application/atom+xml";
		}
		if(empty($GEDCOM_TITLE)){
			$GEDCOM_TITLE = "RSS";
		}
		if(! $REQUIRE_AUTHENTICATION){
			print "<link href=\"".encode_url("{$SERVER_URL}rss.php?ged={$GEDCOM}")."\" rel=\"alternate\" type=\"$applicationType\" title=\"".PrintReady(strip_tags($GEDCOM_TITLE), TRUE)."\" />\n\t";
		}
		//print "<link href=\"".encode_url("{$SERVER_URL}rss.php?ged={$GEDCOM}&auth=basic")."\" rel=\"alternate\" type=\"$applicationType\" title=\"$GEDCOM_TITLE - " . $pgv_lang["authenticated_feed"] . "\" />\n\t";
	}
	print "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\" media=\"all\" />";
	if ((!empty($rtl_stylesheet))&&($TEXT_DIRECTION=="rtl")) print "<link rel=\"stylesheet\" href=\"$rtl_stylesheet\" type=\"text/css\" media=\"all\" />\n";
	if ($use_alternate_styles) {
		if ($BROWSERTYPE != "other") {
			print "<link rel=\"stylesheet\" href=\"".$THEME_DIR.$BROWSERTYPE.".css\" type=\"text/css\" media=\"all\" />\n";
		}
	}

	//	-------------- Lightbox ----------------
	if ($TEXT_DIRECTION=='rtl') {
		echo '<link rel="stylesheet" href="modules/lightbox/css/clearbox_music_RTL.css" type="text/css" />';
		echo '<link rel="stylesheet" href="modules/lightbox/css/album_page_RTL_ff.css" type="text/css" media="screen" />';
	} else {
		echo '<link rel="stylesheet" href="modules/lightbox/css/clearbox_music.css" type="text/css" />';
		echo '<link rel="stylesheet" href="modules/lightbox/css/album_page.css" type="text/css" media="screen" />';
	}
	//	-------------- Lightbox ----------------

	print "<link rel=\"stylesheet\" href=\"$print_stylesheet\" type=\"text/css\" media=\"print\" />\n";
	if ($BROWSERTYPE == "msie") print "<style type=\"text/css\">\nFORM { margin-top: 0px; margin-bottom: 0px; }\n</style>\n";
	//echo '<!-- ', PGV_PHPGEDVIEW, ' ', PGV_VERSION_TEXT, ' -->', "\n";
	if (isset($changelanguage))
		$query_string=normalize_query_string($QUERY_STRING."&amp;changelanguage=&amp;NEWLANGUAGE=");
	else
		$query_string = $QUERY_STRING;
	if ($view!="preview") {
		$old_META_AUTHOR = $META_AUTHOR;
		$old_META_PUBLISHER = $META_PUBLISHER;
		$old_META_COPYRIGHT = $META_COPYRIGHT;
		$old_META_DESCRIPTION = $META_DESCRIPTION;
		$old_META_PAGE_TOPIC = $META_PAGE_TOPIC;
		if (get_user_id($CONTACT_EMAIL)) {
			$cuserName=getUserFullName($CONTACT_EMAIL);
			if (empty($META_AUTHOR)) $META_AUTHOR = $cuserName;
			if (empty($META_PUBLISHER)) $META_PUBLISHER = $cuserName;
			if (empty($META_COPYRIGHT)) $META_COPYRIGHT = $cuserName;
		}
		if (!empty($META_AUTHOR)) print "<meta name=\"author\" content=\"".PrintReady(strip_tags($META_AUTHOR), TRUE)."\" />\n";
		if (!empty($META_PUBLISHER)) print "<meta name=\"publisher\" content=\"".PrintReady(strip_tags($META_PUBLISHER), TRUE)."\" />\n";
		if (!empty($META_COPYRIGHT)) print "<meta name=\"copyright\" content=\"".PrintReady(strip_tags($META_COPYRIGHT), TRUE)."\" />\n";
		print "<meta name=\"keywords\" content=\"".PrintReady(strip_tags($META_KEYWORDS), TRUE);
		if ($META_SURNAME_KEYWORDS) {
			$surnames = get_common_surnames_index($GEDCOM);
			$surnameList = '';
			foreach($surnames as $surname=>$count) {
				if ($surname != '?') {
					$surnameList .= ', ';
					$surnameList .= $surname;
				}
			}
			print PrintReady(strip_tags($surnameList), TRUE);
		}
		print "\" />\n";
		if ((empty($META_DESCRIPTION))&&(!empty($GEDCOM_TITLE))) $META_DESCRIPTION = $GEDCOM_TITLE;
		if ((empty($META_PAGE_TOPIC))&&(!empty($GEDCOM_TITLE))) $META_PAGE_TOPIC = $GEDCOM_TITLE;
		if (!empty($META_DESCRIPTION)) print "<meta name=\"description\" content=\"".preg_replace("/\"/", "", PrintReady(strip_tags($META_DESCRIPTION), TRUE))."\" />\n";
		if (!empty($META_PAGE_TOPIC)) print "<meta name=\"page-topic\" content=\"".preg_replace("/\"/", "", PrintReady(strip_tags($META_PAGE_TOPIC), TRUE))."\" />\n";
		if (!empty($META_AUDIENCE)) print "<meta name=\"audience\" content=\"".PrintReady(strip_tags($META_AUDIENCE), TRUE)."\" />\n";
		if (!empty($META_PAGE_TYPE)) print "<meta name=\"page-type\" content=\"".PrintReady(strip_tags($META_PAGE_TYPE), TRUE)."\" />\n";

		// Restrict good search engine spiders to the index page and the individual.php pages.
		// Quick and dirty hack that will still leave some url only links in Google.
		// Also ignored by crawlers like wget, so other checks have to be done too.
		if((strstr($SCRIPT_NAME, "/individual.php")) ||
		   (strstr($SCRIPT_NAME, "/indilist.php")) ||
		   (strstr($SCRIPT_NAME, "/family.php")) ||
		   (strstr($SCRIPT_NAME, "/famlist.php")) ||
		   (strstr($SCRIPT_NAME, "/help_text.php")) ||
		   (strstr($SCRIPT_NAME, "/source.php")) ||
		   (strstr($SCRIPT_NAME, "/search_engine.php")) ||
		   (strstr($SCRIPT_NAME, "/index.php")) ) {
			// empty case is to index,follow anyways.
			if (empty($META_ROBOTS)) $META_ROBOTS = "index,follow";
				print "<meta name=\"robots\" content=\"".PrintReady(strip_tags($META_ROBOTS), TRUE)."\" />\n";
		}
		else {
			print "<meta name=\"robots\" content=\"noindex,nofollow\" />\n";
		}
		if (!empty($META_REVISIT)) print "<meta name=\"revisit-after\" content=\"".PrintReady(strip_tags($META_REVISIT), TRUE)."\" />\n";
		echo '<meta name="generator" content="', PGV_PHPGEDVIEW, ' - ', PGV_PHPGEDVIEW_URL, "\" />\n";
		$META_AUTHOR = $old_META_AUTHOR;
		$META_PUBLISHER = $old_META_PUBLISHER;
		$META_COPYRIGHT = $old_META_COPYRIGHT;
		$META_DESCRIPTION = $old_META_DESCRIPTION;
		$META_PAGE_TOPIC = $old_META_PAGE_TOPIC;
	}
	else {
?>
<script language="JavaScript" type="text/javascript">
<!--
function hidePrint() {
	var printlink = document.getElementById('printlink');
	var printlinktwo = document.getElementById('printlinktwo');
	if (printlink) {
		printlink.style.display='none';
		printlinktwo.style.display='none';
	}
}
function showBack() {
	var printlink = document.getElementById('printlink');
	var printlinktwo = document.getElementById('printlinktwo');
	if (printlink) {
		printlink.style.display='inline';
		printlinktwo.style.display='inline';
	}
}
//-->
</script>
<?php
}
?>
<script language="JavaScript" type="text/javascript">
	<!--
	<?php print "query = \"$query_string\";\n"; ?>
	<?php print "textDirection = \"$TEXT_DIRECTION\";\n"; ?>
	<?php print "browserType = \"$BROWSERTYPE\";\n"; ?>
	<?php print "themeName = \"".strtolower($theme_name)."\";\n"; ?>
	<?php print "SCRIPT_NAME = \"$SCRIPT_NAME\";\n"; ?>
	/* keep the session id when opening new windows */
	<?php print "sessionid = \"".session_id()."\";\n"; ?>
	<?php print "sessionname = \"".session_name()."\";\n"; ?>
	plusminus = new Array();
	plusminus[0] = new Image();
	plusminus[0].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]; ?>";
	plusminus[1] = new Image();
	plusminus[1].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["minus"]["other"]; ?>";
	zoominout = new Array();
	zoominout[0] = new Image();
	zoominout[0].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["zoomin"]["other"]; ?>";
	zoominout[1] = new Image();
	zoominout[1].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["zoomout"]["other"]; ?>";
	arrows = new Array();
	arrows[0] = new Image();
	arrows[0].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["larrow2"]["other"]; ?>";
	arrows[1] = new Image();
	arrows[1].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["rarrow2"]["other"]; ?>";
	arrows[2] = new Image();
	arrows[2].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["uarrow2"]["other"]; ?>";
	arrows[3] = new Image();
	arrows[3].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["darrow2"]["other"]; ?>";

<?php if (PGV_USER_CAN_EDIT) { ?>
function delete_record(pid, linenum, mediaid) {
	if (!mediaid) mediaid="";
	if (confirm('<?php print $pgv_lang["check_delete"]; ?>')) {
		window.open('edit_interface.php?action=delete&pid='+pid+'&linenum='+linenum+'&mediaid='+mediaid+"&"+sessionname+"="+sessionid, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	}
	return false;
}
function deleteperson(pid) {
	if (confirm('<?php print $pgv_lang["confirm_delete_person"]; ?>')) {
		window.open('edit_interface.php?action=deleteperson&pid='+pid+"&"+sessionname+"="+sessionid, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	}
	return false;
}

function deleterepository(pid) {
	if (confirm('<?php print $pgv_lang["confirm_delete_repo"]; ?>')) {
		window.open('edit_interface.php?action=deleterepo&pid='+pid+"&"+sessionname+"="+sessionid, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	}
	return false;
}
<?php } ?>
function message(username, method, url, subject) {
	if ((!url)||(url=="")) url='<?php print urlencode(basename($SCRIPT_NAME)."?".$QUERY_STRING); ?>';
	if ((!subject)||(subject=="")) subject= '';
	window.open('message.php?to='+username+'&method='+method+'&url='+url+'&subject='+subject+"&"+sessionname+"="+sessionid, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	return false;
}
var whichhelp = 'help_<?php print basename($SCRIPT_NAME)."&action=".$action; ?>';
//-->
</script>
<script src="phpgedview.js" language="JavaScript" type="text/javascript"></script>
<?php
	print $head;
	print "</head>\n\t<body id=\"body\"";
	if ($view=="preview") print " onbeforeprint=\"hidePrint();\" onafterprint=\"showBack();\"";
	print " onload=\"";
	if (!empty($ONLOADFUNCTION)) print $ONLOADFUNCTION;
	if ($TEXT_DIRECTION=="rtl") {
		print " maxscroll = document.documentElement.scrollLeft;";
	}
	print "\"";
	print ">\n\t";
	print "<!-- begin header section -->\n";
	if ($view!="preview") {
		include($headerfile);
		include($toplinks);
	}
	else {
		include($print_headerfile);
	}
	print "<!-- end header section -->\n";
	print "<!-- begin content section -->\n";
}
/**
 * print simple HTML header
 *
 * This function will print out the HTML, HEAD, and BODY tags and will load in the CSS javascript and
 * other auxiliary files needed to run PGV.  It does not include any theme specific header files.
 * This function should be called by every page before anything is output on popup pages.
 *
 * @param string $title	the title to put in the <TITLE></TITLE> header tags
 * @param string $head
 * @param boolean $use_alternate_styles
 */
function print_simple_header($title) {
	global $pgv_lang;
	global $HOME_SITE_URL;
	global $HOME_SITE_TEXT, $SEARCH_SPIDER;
	global $view, $rtl_stylesheet;
	global $CHARACTER_SET, $PGV_IMAGE_DIR;
	global $SCRIPT_NAME, $QUERY_STRING, $action, $query, $changelanguage;
	global $FAVICON, $stylesheet, $headerfile, $toplinks, $THEME_DIR, $print_headerfile, $SCRIPT_NAME;
	global $TEXT_DIRECTION, $GEDCOMS, $GEDCOM, $GEDCOM_TITLE, $CONTACT_EMAIL, $COMMON_NAMES_THRESHOLD,$PGV_IMAGES;
	global $META_AUTHOR, $META_PUBLISHER, $META_COPYRIGHT, $META_DESCRIPTION, $META_PAGE_TOPIC, $META_AUDIENCE, $META_PAGE_TYPE, $META_ROBOTS, $META_REVISIT, $META_KEYWORDS, $META_TITLE, $META_SURNAME_KEYWORDS;

	// If not on allowed list, dump the spider onto the redirect page.
	// This kills recognized spiders in their tracks.
	// To stop unrecognized spiders, see META_ROBOTS below.
	if(!empty($SEARCH_SPIDER)) {
		if(!((strstr($SCRIPT_NAME, "/individual.php")) ||
		     (strstr($SCRIPT_NAME, "/indilist.php")) ||
		     (strstr($SCRIPT_NAME, "/family.php")) ||
		     (strstr($SCRIPT_NAME, "/famlist.php")) ||
		     (strstr($SCRIPT_NAME, "/help_text.php")) ||
		     (strstr($SCRIPT_NAME, "/source.php")) ||
		     (strstr($SCRIPT_NAME, "/search_engine.php")) ||
		     (strstr($SCRIPT_NAME, "/index.php"))) ) {
			header("Location: search_engine.php");
			exit;
		}
	}
	$GEDCOM_TITLE = get_gedcom_setting(PGV_GED_ID, 'title');
	header("Content-Type: text/html; charset=$CHARACTER_SET");
	print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	print "<html xmlns=\"http://www.w3.org/1999/xhtml\"><head>";
	print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$CHARACTER_SET\" />";
	if( $FAVICON ) {
		print "<link rel=\"shortcut icon\" href=\"$FAVICON\" type=\"image/x-icon\" />";
	}
	if (empty($META_TITLE)) $metaTitle = ' - '.PGV_PHPGEDVIEW;
	else $metaTitle = " - ".$META_TITLE.' - '.PGV_PHPGEDVIEW;
	print "<title>".PrintReady(strip_tags($title).$metaTitle, TRUE)."</title>";
	print "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\" />";
	if ((!empty($rtl_stylesheet))&&($TEXT_DIRECTION=="rtl")) print "<link rel=\"stylesheet\" href=\"$rtl_stylesheet\" type=\"text/css\" media=\"all\" />";

	//	-------------- Lightbox ----------------
	if ($TEXT_DIRECTION=='rtl') {
		echo '<link rel="stylesheet" href="modules/lightbox/css/clearbox_music_RTL.css" type="text/css" />';
		echo '<link rel="stylesheet" href="modules/lightbox/css/album_page_RTL_ff.css" type="text/css" media="screen" />';
	} else {
		echo '<link rel="stylesheet" href="modules/lightbox/css/clearbox_music.css" type="text/css" />';
		echo '<link rel="stylesheet" href="modules/lightbox/css/album_page.css" type="text/css" media="screen" />';
	}
	//	-------------- Lightbox ----------------

	$old_META_AUTHOR = $META_AUTHOR;
	$old_META_PUBLISHER = $META_PUBLISHER;
	$old_META_COPYRIGHT = $META_COPYRIGHT;
	$old_META_DESCRIPTION = $META_DESCRIPTION;
	$old_META_PAGE_TOPIC = $META_PAGE_TOPIC;
	if (get_user_id($CONTACT_EMAIL)) {
		$cuserName=getUserFullName($CONTACT_EMAIL);
		if (empty($META_AUTHOR)) $META_AUTHOR = $cuserName;
		if (empty($META_PUBLISHER)) $META_PUBLISHER = $cuserName;
		if (empty($META_COPYRIGHT)) $META_COPYRIGHT = $cuserName;
	}
	if (!empty($META_AUTHOR)) print "<meta name=\"author\" content=\"".PrintReady(strip_tags($META_AUTHOR), TRUE)."\" />";
	if (!empty($META_PUBLISHER)) print "<meta name=\"publisher\" content=\"".PrintReady(strip_tags($META_PUBLISHER), TRUE)."\" />";
	if (!empty($META_COPYRIGHT)) print "<meta name=\"copyright\" content=\"".PrintReady(strip_tags($META_COPYRIGHT), TRUE)."\" />";
	print "<meta name=\"keywords\" content=\"".PrintReady(strip_tags($META_KEYWORDS), TRUE);
	if ($META_SURNAME_KEYWORDS) {
		$surnames = get_common_surnames_index($GEDCOM);
		$surnameList = '';
		foreach($surnames as $surname=>$count) {
			if ($surname != '?') {
				$surnameList .= ', ';
				$surnameList .= $surname;
			}
		}
		print PrintReady(strip_tags($surnameList), TRUE);
	}
	print "\" />";
	if ((empty($META_DESCRIPTION))&&(!empty($GEDCOM_TITLE))) $META_DESCRIPTION = $GEDCOM_TITLE;
	if ((empty($META_PAGE_TOPIC))&&(!empty($GEDCOM_TITLE))) $META_PAGE_TOPIC = $GEDCOM_TITLE;
	if (!empty($META_DESCRIPTION)) print "<meta name=\"description\" content=\"".preg_replace("/\"/", "", PrintReady(strip_tags($META_DESCRIPTION), TRUE))."\" />";
	if (!empty($META_PAGE_TOPIC)) print "<meta name=\"page-topic\" content=\"".preg_replace("/\"/", "", PrintReady(strip_tags($META_PAGE_TOPIC), TRUE))."\" />";
	if (!empty($META_AUDIENCE)) print "<meta name=\"audience\" content=\"".PrintReady(strip_tags($META_AUDIENCE), TRUE)."\" />";
	if (!empty($META_PAGE_TYPE)) print "<meta name=\"page-type\" content=\"".PrintReady(strip_tags($META_PAGE_TYPE), TRUE)."\" />";

	// Restrict good search engine spiders to the index page and the individual.php pages.
	// Quick and dirty hack that will still leave some url only links in Google.
	// Also ignored by crawlers like wget, so other checks have to be done too.
	if((strstr($SCRIPT_NAME, "/individual.php")) ||
	   (strstr($SCRIPT_NAME, "/indilist.php")) ||
	   (strstr($SCRIPT_NAME, "/family.php")) ||
	   (strstr($SCRIPT_NAME, "/famlist.php")) ||
	   (strstr($SCRIPT_NAME, "/help_text.php")) ||
	   (strstr($SCRIPT_NAME, "/source.php")) ||
	   (strstr($SCRIPT_NAME, "/search_engine.php")) ||
	   (strstr($SCRIPT_NAME, "/index.php")) ) {
		// empty case is to index,follow anyways.
		if (empty($META_ROBOTS)) $META_ROBOTS = "index,follow";
		print "<meta name=\"robots\" content=\"".PrintReady(strip_tags($META_ROBOTS), TRUE)."\" />";
	}
	else {
		print "<meta name=\"robots\" content=\"noindex,nofollow\" />";
	}
	if (!empty($META_REVISIT)) print "<meta name=\"revisit-after\" content=\"".PrintReady(strip_tags($META_REVISIT), TRUE)."\" />";
	echo '<meta name="generator" content="'.PGV_PHPGEDVIEW.' '.PGV_VERSION_TEXT.' - '.PGV_PHPGEDVIEW_URL.'" />';
	$META_AUTHOR = $old_META_AUTHOR;
	$META_PUBLISHER = $old_META_PUBLISHER;
	$META_COPYRIGHT = $old_META_COPYRIGHT;
	$META_DESCRIPTION = $old_META_DESCRIPTION;
	$META_PAGE_TOPIC = $old_META_PAGE_TOPIC;
?>
	<style type="text/css">
	<!--
	.largechars {
		font-size: 18px;
	}
	-->
	</style>
	<script language="JavaScript" type="text/javascript">
	<!--
	/* set these vars so that the session can be passed to new windows */
	<?php print "sessionid = \"".session_id()."\";"; ?>
	<?php print "sessionname = \"".session_name()."\";"; ?>
	plusminus = new Array();
	plusminus[0] = new Image();
	plusminus[0].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]; ?>";
	plusminus[1] = new Image();
	plusminus[1].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["minus"]["other"]; ?>";
	zoominout = new Array();
	zoominout[0] = new Image();
	zoominout[0].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["zoomin"]["other"]; ?>";
	zoominout[1] = new Image();
	zoominout[1].src = "<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["zoomout"]["other"]; ?>";

	var helpWin;
	function helpPopup(which) {

		if ((!helpWin)||(helpWin.closed)) helpWin = window.open('help_text.php?help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');

		else helpWin.location = 'help_text.php?help='+which;
		return false;
	}
function message(username, method, url, subject) {
	if ((!url)||(url=="")) url='<?php print urlencode(basename($SCRIPT_NAME)."?".$QUERY_STRING); ?>';
	if ((!subject)||(subject=="")) subject= '';
	window.open('message.php?to='+username+'&method='+method+'&url='+url+'&subject='+subject+"&"+sessionname+"="+sessionid, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	return false;
}
	//-->
	</script>
	<script src="phpgedview.js" language="JavaScript" type="text/javascript"></script>
	<?php
	echo '</head>';
	echo '<body style="margin:5px;" onload="loadHandler();">';
}
// -- print the html to close the page
function print_footer() {
	global $without_close, $pgv_lang, $view, $buildindex, $DBTYPE;
	global $SHOW_STATS, $SCRIPT_NAME, $QUERY_STRING, $footerfile, $print_footerfile, $GEDCOMS, $ALLOW_CHANGE_GEDCOM, $printlink;
	global $PGV_IMAGE_DIR, $theme_name, $PGV_IMAGES, $TEXT_DIRECTION, $footer_count, $DEBUG;

	if (!isset($footer_count)) $footer_count = 1;
	else $footer_count++;
	print "<!-- begin footer -->";
	if ($view!="preview") {
		include($footerfile);
	}
	else {
		include($print_footerfile);
		print "<div id=\"backprint\" style=\"text-align: center; width: 95%\"><br />";
		$backlink = $SCRIPT_NAME."?".get_query_string();
		if (!$printlink) {
			print "<br /><a id=\"printlink\" href=\"javascript:;\" onclick=\"print(); return false;\">".$pgv_lang["print"]."</a><br />";
			print " <a id=\"printlinktwo\" href=\"javascript:;\" onclick=\"window.location='".$backlink."'; return false;\">".$pgv_lang["cancel_preview"]."</a><br />";
		}
		$printlink = true;
		print "</div>";
	}
	if (function_exists("load_behaviour")) {
		load_behaviour();  // @see function_print_lists.php
	}
	echo google_analytics();
	echo '</body></html>';
}
// -- print the html to close the page
function print_simple_footer() {
	global $pgv_lang;
	global $start_time, $buildindex;
	global $SHOW_STATS;
	global $SCRIPT_NAME, $QUERY_STRING;
	global $PGV_IMAGE_DIR, $PGV_IMAGES;
	if (empty($SCRIPT_NAME)) {
		$SCRIPT_NAME = $_SERVER["SCRIPT_NAME"];
		$QUERY_STRING = $_SERVER["QUERY_STRING"];
	}
	print "<br /><br /><div align=\"center\" style=\"width: 99%;\">";
	print contact_links();
	print '<a href="'.PGV_PHPGEDVIEW_URL.'" target="_blank"><img src="'.$PGV_IMAGE_DIR.'/'.$PGV_IMAGES['gedview']['other'].'" border="0" alt="'.PGV_PHPGEDVIEW." ".PGV_VERSION_TEXT.'" title="'.PGV_PHPGEDVIEW." ".PGV_VERSION_TEXT.'" /></a><br />';
	if ($SHOW_STATS || isset($DEBUG) && $DEBUG==true) {
		print_execution_stats();
	}
	print "</div></body></html>";
}

// Generate code for google analytics
function google_analytics() {
	if (defined('PGV_GOOGLE_ANALYTICS')) {
		return '<script type="text/javascript">var gaJsHost=(("https:"==document.location.protocol)?"https://ssl.":"http://www.");document.write(unescape("%3Cscript src=\'"+gaJsHost+"google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));</script><script type="text/javascript">var pageTracker=_gat._getTracker("'.PGV_GOOGLE_ANALYTICS.'");pageTracker._initData();pageTracker._trackPageview();</script>';
	} else {
		return '';
	}
}

/**
 * Prints Exection Statistics
 *
 * prints out the execution time and the databse queries
 */
function print_execution_stats() {
	global $start_time, $pgv_lang, $TOTAL_QUERIES, $PRIVACY_CHECKS;
	$end_time = getmicrotime();
	$exectime = $end_time - $start_time;
	print "<br /><br />".$pgv_lang["exec_time"];
	printf(" %.3f ".$pgv_lang["sec"], $exectime);
	print "  ".$pgv_lang["total_queries"]." $TOTAL_QUERIES.";
	if (!$PRIVACY_CHECKS) $PRIVACY_CHECKS=0;
	print " ".$pgv_lang["total_privacy_checks"]." $PRIVACY_CHECKS.";
	if (function_exists("memory_get_usage")) {
		print " ".$pgv_lang["total_memory_usage"]." ";
		if (function_exists("memory_get_peak_usage")) $mem = memory_get_peak_usage()/1024;
		else $mem = memory_get_usage()/1024;
		printf("%.2f", $mem);
		print " KB.";
	}
	print "<br />";
}

//-- print a form to change the language
function print_lang_form($option=0) {
	global $ENABLE_MULTI_LANGUAGE;

	if ($ENABLE_MULTI_LANGUAGE) {
		//-- don't show the form if there is only one language enabled
		$language_menu=MenuBar::getLanguageMenu();
		if (count($language_menu->submenus)<2) {
			return;
		}

		print '<div class="lang_form">';
		switch($option) {
		case 1:
			echo $language_menu->getMenuAsIcons();
			break;
		default:
			echo $language_menu->getMenuAsDropdown();
			break;
		}
		print '</div>';
	}
}
/**
 * print user links
 *
 * this function will print login/logout links and other links based on user privileges
 */
function print_user_links() {
	global $pgv_lang, $SCRIPT_NAME, $QUERY_STRING, $GEDCOM;
	global $LOGIN_URL, $SEARCH_SPIDER;

	if (PGV_USER_ID) {
		print '<a href="edituser.php" class="link">'.$pgv_lang["logged_in_as"].' ('.PGV_USER_NAME.')</a><br />';
		if (PGV_USER_GEDCOM_ADMIN) {
			print "<a href=\"admin.php\" class=\"link\">".$pgv_lang["admin"]."</a> | ";
		}
		print "<a href=\"index.php?logout=1\" class=\"link\">".$pgv_lang["logout"]."</a>";
	} else {
		$QUERY_STRING = normalize_query_string($QUERY_STRING.'&amp;logout=');
		if (empty($SEARCH_SPIDER)) {
			print "<a href=\"$LOGIN_URL?url=".rawurlencode(basename($SCRIPT_NAME).decode_url(normalize_query_string($QUERY_STRING."&amp;ged=$GEDCOM")))."\" class=\"link\">".$pgv_lang["login"]."</a>";
		}
	}
	print "<br />";
}

// Print a link to allow email/messaging contact with a user
// Optionally specify a method (used for webmaster/genealogy contacts)
function user_contact_link($user_id, $method=null) {
	global $pgv_lang;

	if (is_null($method)) {
		$method=get_user_setting($user_id, 'contactmethod');
		$access='';
	} else {
		$access="accesskey='{$pgv_lang['accesskey_contact']}'";
	}

	// Webmaster/contact addresses can be an email address as well as a user-id
	if (strpos($user_id, '@')!==false) {
		$email=$user_id;
		$fullname=$user_id;
		if ($method!='none') {
			$method='mailto';
		}
	} else {
		$email=get_user_setting($user_id, 'email');
		$fullname=getUserFullName($user_id);
	}

	switch ($method) {
	case 'none':
		return '';
	case 'mailto':
		return "<a href='mailto:{$email}' {$access}>{$fullname}</a>";
	default:
		return "<a href='javascript:;' onclick='message(\"{$user_id}\",\"{$method}\");return false;' {$access}>{$fullname}</a>";
	}
}

// Print a menu item to allow email/messaging contact with a user
// Optionally specify a method (used for webmaster/genealogy contacts)
function user_contact_menu($user_id, $method=null) {
	global $pgv_lang;

	if (is_null($method)) {
		$method=get_user_setting($user_id, 'contactmethod');
	}

	// Webmaster/contact addresses can be an email address as well as a user-id
	if (strpos($user_id, '@')!==false) {
		$email=$user_id;
		$fullname=$user_id;
		if ($method!='none') {
			$method='mailto';
		}
	} else {
		$email=get_user_setting($user_id, 'email');
		$fullname=getUserFullName($user_id);
	}

	switch ($method) {
	case 'none':
		return array();
	case 'mailto':
		return array('label'=>$fullname, 'labelpos'=>'right', 'class'=>'submenuitem', 'hoverclass'=>'submenuitem_hover', 'link'=>"mailto:{$email}");
	default:
		return array('label'=>$fullname, 'labelpos'=>'right', 'class'=>'submenuitem', 'hoverclass'=>'submenuitem_hover', 'link'=>'#', 'onclick'=>"message('{$user_id}','{$method}');return false;");
	}
}

// print links for genealogy and technical contacts
//
// this function will print appropriate links based on the preferred contact methods for the genealogy
// contact user and the technical support contact user

function print_contact_links() { // This function is used by 3rd party themes.
	print contact_links();
}

function contact_links() {
	global $WEBMASTER_EMAIL, $SUPPORT_METHOD, $CONTACT_EMAIL, $CONTACT_METHOD, $pgv_lang;

	$support_link=user_contact_link($WEBMASTER_EMAIL, $SUPPORT_METHOD);
	$contact_link=user_contact_link($CONTACT_EMAIL,   $CONTACT_METHOD);
	if (!$support_link) {
		$support_link=$contact_link;
	}
	if (!$contact_link) {
		$contact_link=$support_link;
	}
	if (!$support_link) {
		return '';
	}
	if ($support_link==$contact_link) {
		return '<div class="contact_links">'.$pgv_lang['for_all_contact'].' '.$support_link.'</div>';
	} else {
		return '<div class="contact_links">'.$pgv_lang['for_support'].' '.$support_link.'<br />'.$pgv_lang['for_contact'].' '.$contact_link.'</div>';
	}
}

function contact_menus() {
	global $WEBMASTER_EMAIL, $SUPPORT_METHOD, $CONTACT_EMAIL, $CONTACT_METHOD, $pgv_lang;

	$support_menu=user_contact_menu($WEBMASTER_EMAIL, $SUPPORT_METHOD);
	$contact_menu=user_contact_menu($CONTACT_EMAIL,   $CONTACT_METHOD);
	if (!$support_menu) {
		$support_menu=$contact_menu;
	}
	if (!$contact_menu) {
		$contact_menu=$support_menu;
	}
	if (!$support_menu) {
		return array();
	}
	$menuitems=array();
	if ($support_menu==$contact_menu) {
		$support_menu['label']=$pgv_lang['support_contact'];
		$menuitems[]=$support_menu;
	} else {
		$support_menu['label']=$pgv_lang['support_contact'];
		$menuitems[]=$support_menu;
		$contact_menu['label']=$pgv_lang['genealogy_contact'];
		$menuitems[]=$contact_menu;
	}
	return $menuitems;
}

//-- print user favorites
function print_favorite_selector($option=0) {
	global $pgv_lang, $GEDCOM, $SCRIPT_NAME, $SHOW_ID_NUMBERS, $pid, $INDEX_DIRECTORY, $indilist, $famlist, $sourcelist, $medialist, $QUERY_STRING, $famid, $sid;
	global $TEXT_DIRECTION, $REQUIRE_AUTHENTICATION, $PGV_IMAGE_DIR, $PGV_IMAGES, $SEARCH_SPIDER;
	$username = PGV_USER_NAME;
	if (!empty($username)) $userfavs = getUserFavorites($username);
	else {
		if ($REQUIRE_AUTHENTICATION) return false;
		$userfavs = array();
	}
	if (empty($pid)&&(!empty($famid))) $pid = $famid;
	if (empty($pid)&&(!empty($sid))) $pid = $sid;
	$gedcomfavs = getUserFavorites($GEDCOM);
	if ((empty($username))&&(count($gedcomfavs)==0)) return;

	if(!empty($SEARCH_SPIDER)) {
		return; // show no favorites, because they taint every page that is indexed.
	}

	print "<div class=\"favorites_form\">";
	switch($option) {
	case 1:
		$menu = array();
		$menu["label"] = $pgv_lang["favorites"];
		$menu["labelpos"] = "right";
		$menu["link"] = "#";
		$menu["class"] = "favmenuitem";
		$menu["hoverclass"] = "favmenuitem_hover";
		$menu["flyout"] = "down";
		$menu["submenuclass"] = "favsubmenu";
		$menu["items"] = array();
		$mygedcom = $GEDCOM;
		$current_gedcom = $GEDCOM;
		$mypid = $pid;
		if (count($userfavs)>0) {
			$submenu = array();
			$submenu["label"] = "<b>".$pgv_lang["my_favorites"]."</b>";
			$submenu["labelpos"] = "right";
			$submenu["link"] = "#";
			$submenu["class"] = "favsubmenuitem";
			$submenu["hoverclass"] = "favsubmenuitem_hover";
			$menu["items"][] = $submenu;
		}
		foreach($userfavs as $key=>$favorite) {
			$pid = $favorite["gid"];
			$current_gedcom = $GEDCOM;
			$GEDCOM = $favorite["file"];
			$submenu = array();
			if ($favorite["type"]=="URL" && !empty($favorite["url"])) {
//				$submenu["link"] = encode_url($favorite["url"]."&ged=$GEDCOM");
				$submenu["link"] = encode_url($favorite["url"]);
				$submenu["label"] = PrintReady($favorite["title"]);
				$submenu["labelpos"] = "right";
				$submenu["class"] = "favsubmenuitem";
				$submenu["hoverclass"] = "favsubmenuitem_hover";
				$menu["items"][] = $submenu;
			} else {
				$record=GedcomRecord::getInstance($pid);
				if ($record && $record->canDisplayName()) {
					$submenu["link"] = encode_url($record->getLinkUrl());
					$submenu["label"] = PrintReady($record->getFullName());
					if ($SHOW_ID_NUMBERS) {
						if ($TEXT_DIRECTION=="ltr") {
							$submenu["label"] .= " (".$record->getXref().")";
						} else {
							$submenu["label"] .= " " . getRLM() . "(".$record->getXref().")" . getRLM();
						}
					}
					$submenu["labelpos"] = "right";
					$submenu["class"] = "favsubmenuitem";
					$submenu["hoverclass"] = "favsubmenuitem_hover";
					$menu["items"][] = $submenu;
				}
			}
		}
		$pid = $mypid;
		$GEDCOM = $mygedcom;
		if ((!empty($username))&&(strpos($_SERVER["SCRIPT_NAME"], "individual.php")!==false)) {
			$menu["items"][]="separator";
			$submenu = array();
			$submenu["label"] = $pgv_lang["add_to_my_favorites"];
			$submenu["labelpos"] = "right";
			$submenu["link"] = encode_url("individual.php?action=addfav&gid={$pid}&pid={$pid}");
			$submenu["class"] = "favsubmenuitem";
			$submenu["hoverclass"] = "favsubmenuitem_hover";
			$menu["items"][] = $submenu;
		}
		if (count($gedcomfavs)>0) {
			$menu["items"][]="separator";
			$submenu = array();
			$submenu["label"] = "<b>".$pgv_lang["gedcom_favorites"]."</b>";
			$submenu["labelpos"] = "right";
			$submenu["link"] = "#";
			$submenu["class"] = "favsubmenuitem";
			$submenu["hoverclass"] = "favsubmenuitem_hover";
			$menu["items"][] = $submenu;
			$current_gedcom = $GEDCOM;
			foreach($gedcomfavs as $key=>$favorite) {
				$GEDCOM = $favorite["file"];
				$pid = $favorite["gid"];
				$submenu = array();
				if ($favorite["type"]=="URL" && !empty($favorite["url"])) {
//					$submenu["link"] = encode_url($favorite["url"]."&ged=$GEDCOM");
					$submenu["link"] = encode_url($favorite["url"]);
					$submenu["label"] = PrintReady($favorite["title"]);
					$submenu["labelpos"] = "right";
					$submenu["class"] = "favsubmenuitem";
					$submenu["hoverclass"] = "favsubmenuitem_hover";
					$menu["items"][] = $submenu;
				} else {
					$record=GedcomRecord::getInstance($pid);
					if ($record && $record->canDisplayName()) {
						$submenu["link"] = encode_url($record->getLinkUrl());
						$submenu["label"] = PrintReady($record->getFullName());
						if ($SHOW_ID_NUMBERS) {
							if ($TEXT_DIRECTION=="ltr") {
								$submenu["label"] .= " (".$record->getXref().")";
							} else {
								$submenu["label"] .= " " . getRLM() . "(".$record->getXref().")" . getRLM();
							}
						}
						$submenu["labelpos"] = "right";
						$submenu["class"] = "favsubmenuitem";
						$submenu["hoverclass"] = "favsubmenuitem_hover";
						$menu["items"][] = $submenu;
					}
				}
			}
			$pid = $mypid;
			$GEDCOM = $mygedcom;
			print_menu($menu);
		}
		break;
	default:
		print "<form name=\"favoriteform\" action=\"$SCRIPT_NAME";
		print "\" method=\"post\" onsubmit=\"return false;\">";
		print "<select name=\"fav_id\" class=\"header_select\" onchange=\"if (document.favoriteform.fav_id.options[document.favoriteform.fav_id.selectedIndex].value!='') window.location=document.favoriteform.fav_id.options[document.favoriteform.fav_id.selectedIndex].value; if (document.favoriteform.fav_id.options[document.favoriteform.fav_id.selectedIndex].value=='add') window.location='{$SCRIPT_NAME}".normalize_query_string("{$QUERY_STRING}&amp;action=addfav&amp;gid={$pid}&amp;pid={$pid}")."';\">";
		print "<option value=\"\">".$pgv_lang["favorites"]."</option>";
		if (!empty($username)) {
			if (count($userfavs)>0 || (strpos($_SERVER["SCRIPT_NAME"], "individual.php")!==false || strpos($_SERVER["SCRIPT_NAME"], "family.php")!==false || strpos($_SERVER["SCRIPT_NAME"], "source.php")!==false)) {
				print "<optgroup label=\"".$pgv_lang["my_favorites"]."\">";
			}
			$mygedcom = $GEDCOM;
			$current_gedcom = $GEDCOM;
			$mypid = $pid;
			if (strpos($_SERVER["SCRIPT_NAME"], "individual.php")!==false || strpos($_SERVER["SCRIPT_NAME"], "family.php")!==false || strpos($_SERVER["SCRIPT_NAME"], "source.php")!==false) {
				print "<option value=\"add\">- ".$pgv_lang["add_to_my_favorites"]." -</option>";
			}
			foreach($userfavs as $key=>$favorite) {
				$current_gedcom = $GEDCOM;
				$GEDCOM = $favorite["file"];
				$pid = $favorite["gid"];
				if ($favorite["type"]=="URL" && !empty($favorite["url"])) {
//					print "<option value=\"".encode_url($favorite["url"]."&ged=".$GEDCOM)."\">".PrintReady($favorite["title"]);
					print "<option value=\"".encode_url($favorite["url"])."\">".PrintReady($favorite["title"]);
					print "</option>";
				} else {
					$record=GedcomRecord::getInstance($pid);
					if ($record && $record->canDisplayName()) {
						$name=$record->getFullName();
						if ($SHOW_ID_NUMBERS) {
							if ($TEXT_DIRECTION=="ltr") {
								$name.=' ('.$record->getXref().')';
							} else {
								$name.=' '.getRLM().'('.$record->getXref().')'.getRLM();
							}
						}
						print "<option value=\"". encode_url($record->getLinkUrl())."\">".$name."</option>";
					}
				}
			}
			if (count($userfavs)>0 || (strpos($_SERVER["SCRIPT_NAME"], "individual.php")!==false || strpos($_SERVER["SCRIPT_NAME"], "family.php")!==false || strpos($_SERVER["SCRIPT_NAME"], "source.php")!==false)) {
				print "</optgroup>";
			}
			$GEDCOM = $mygedcom;
			$pid = $mypid;
		}
		if (count($gedcomfavs)>0) {
			print "<optgroup label=\"".$pgv_lang["gedcom_favorites"]."\">";
			$mygedcom = $GEDCOM;
			$current_gedcom = $GEDCOM;
			$mypid = $pid;
			foreach($gedcomfavs as $key=>$favorite) {
				$current_gedcom = $GEDCOM;
				$GEDCOM = $favorite["file"];
				$pid = $favorite["gid"];
				if ($favorite["type"]=="URL" && !empty($favorite["url"])) {
//					print "<option value=\"".encode_url($favorite["url"]."&ged=".$GEDCOM)."\">".PrintReady($favorite["title"]);
					print "<option value=\"".encode_url($favorite["url"])."\">".PrintReady($favorite["title"]);
					print "</option>";
				} else {
					$record=GedcomRecord::getInstance($pid);
					if ($record && $record->canDisplayName()) {
						$name=$record->getFullName();
						if ($SHOW_ID_NUMBERS) {
							if ($TEXT_DIRECTION=="ltr") {
								$name.=' ('.$record->getXref().')';
							} else {
								$name.=' '.getRLM().'('.$record->getXref().')'.getRLM();
							}
						}
						print "<option value=\"". encode_url($record->getLinkUrl())."\">".$name."</option>";
					}
				}
			}
			print "</optgroup>";
			$GEDCOM = $mygedcom;
			$pid = $mypid;
		}
		print "</select></form>";
		break;
	}
	print "</div>\n";
}

/**
 * print a note record
 * @param string $text
 * @param int $nlevel	the level of the note record
 * @param string $nrec	the note record to print
 * @param bool $textOnly	Don't print the "Note: " introduction
 * @param boolean $return	Print the data or return the data
 * @return boolean
 */
function print_note_record($text, $nlevel, $nrec, $textOnly=false, $return=false) {
	global $pgv_lang;
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $EXPAND_SOURCES, $EXPAND_NOTES;
	if (!isset($EXPAND_NOTES)) $EXPAND_NOTES = $EXPAND_SOURCES; // FIXME
	$elementID = "N-".floor(microtime()*1000000);
	$text = trim($text);
	$text .= get_cont($nlevel, $nrec);
	$text = str_replace("~~", "<br />", $text);
	$text = trim(expand_urls(stripLRMRLM($text)));
	$data = "";
	if (!empty($text)) {
		$text = PrintReady($text);
		if ($textOnly) {
			if (!$return) {
				print $text;
				return true;
			}
			else return $text;
		}
		$brpos = strpos($text, "<br />");
		$data .= "<br /><span class=\"label\">";
		if ($brpos !== false) {
			if ($EXPAND_NOTES) $plusminus="minus"; else $plusminus="plus";
			$data .= "<a href=\"javascript:;\" onclick=\"expand_layer('$elementID'); return false;\"><img id=\"{$elementID}_img\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES[$plusminus]["other"]."\" border=\"0\" width=\"11\" height=\"11\" alt=\"".$pgv_lang["show_details"]."\" title=\"".$pgv_lang["show_details"]."\" /></a> ";
		}
		$data .= $pgv_lang["note"].": </span><span class=\"field\">";
		if ($brpos !== false) {
			$data .= substr($text, 0, $brpos);
			$data .= "<span id=\"$elementID\"";
			if ($EXPAND_NOTES) $data .= " style=\"display:block\"";
			$data .= " class=\"note_details\">";
			$data .= substr($text, $brpos + 6);
			$data .= "</span>";
		} else {
			$data .= $text;
		}
		if (!$return) {
			print $data;
			return true;
		}
		else return $data;
	}
	return false;
}

/**
 * Print all of the notes in this fact record
 * @param string $factrec	the factrecord to print the notes from
 * @param int $level		The level of the factrecord
 * @param bool $textOnly	Don't print the "Note: " introduction
 * @param boolean $return	whether to return text or print the data
 */
function print_fact_notes($factrec, $level, $textOnly=false, $return=false) {
	global $pgv_lang;
	global $factarray;
	$data = "";
	$printDone = false;
	$nlevel = $level+1;
	$ct = preg_match_all("/$level NOTE(.*)/", $factrec, $match, PREG_SET_ORDER);
	for($j=0; $j<$ct; $j++) {
		$spos1 = strpos($factrec, $match[$j][0]);
		$spos2 = strpos($factrec."\n$level", "\n$level", $spos1+1);
		if (!$spos2) $spos2 = strlen($factrec);
		$nrec = substr($factrec, $spos1, $spos2-$spos1);
		if (!isset($match[$j][1])) $match[$j][1]="";
		$nt = preg_match("/@(.*)@/", $match[$j][1], $nmatch);
		$closeSpan = false;
		if ($nt==0) {
			//-- print embedded note records
			$closeSpan = print_note_record($match[$j][1], $nlevel, $nrec, $textOnly, true);
			$data .= $closeSpan;
		} else {
			if (displayDetailsByID($nmatch[1], "NOTE")) {
				//-- print linked note records
				$noterec = find_gedcom_record($nmatch[1]);
				$nt = preg_match("/0 @$nmatch[1]@ NOTE (.*)/", $noterec, $n1match);
				$closeSpan = print_note_record(($nt>0)?$n1match[1]:"", 1, $noterec, $textOnly, true);
				$data .= $closeSpan;
				if (!$textOnly) {
					if (preg_match("/1 SOUR/", $noterec)>0) {
						$data .= "<br />";
						$data .= print_fact_sources($noterec, 1, true);
					}
				}
			}
		}
		if (!$textOnly) {
			if (preg_match("/$nlevel SOUR/", $factrec)>0) {
				$data .= "<div class=\"indent\">";
				$data .= print_fact_sources($nrec, $nlevel, true);
				$data .= "</div>";
			}
		}
		if($closeSpan){
			$data .= "</span>";
		}
		$printDone = true;
	}
	if ($printDone) $data .= "<br />"; 
	if (!$return) print $data;
	else return $data;
}
/**
 * print a gedcom title linked to the gedcom portal
 *
 * This function will print the HTML to link the current gedcom title back to the
 * gedcom portal welcome page
 * @author John Finlay
 */
function print_gedcom_title_link($InHeader=FALSE) {
	global $GEDCOMS, $GEDCOM, $GEDCOM_TITLE;
	if ((count($GEDCOMS)==0)||(empty($GEDCOM))) return;
	print "<a href=\"index.php?ctype=gedcom\" class=\"gedcomtitle\">".PrintReady($GEDCOM_TITLE, $InHeader)."</a>";
}

//-- function to print a privacy error with contact method
function print_privacy_error($username) {
	global $pgv_lang, $CONTACT_METHOD, $SUPPORT_METHOD, $WEBMASTER_EMAIL;

	$method = $CONTACT_METHOD;
	if ($username==$WEBMASTER_EMAIL) {
		$method = $SUPPORT_METHOD;
	}
	if (!get_user_id($username)) {
		$method = "mailto";
	}
	print "<br /><span class=\"error\">".$pgv_lang["privacy_error"]." ";
	if ($method=="none") {
		print "</span><br />";
		return;
	}
	print $pgv_lang["more_information"];
	if ($method=="mailto") {
		if (!get_user_id($username)) {
			$email = $username;
			$fullname = $username;
		} else {
			$email = get_user_setting($username, 'email');
			$fullname=getUserFullName($username);
		}
		print " <a href=\"mailto:$email\">".$fullname."</a></span><br />";
	} else {
		$userName=getUserFullName($username);
		print " <a href=\"javascript:;\" onclick=\"message('$username','$method'); return false;\">".$userName."</a></span><br />";
	}
}

/* Function to print popup help boxes
 * @param string $help		The variable that needs to be processed.
 * @param int $helpText		The text to be printed if the theme does not use images for help links
 * @param int $show_desc		The text to be shown as JavaScript description
 * @param boolean $use_print_text	If the text needs to be printed with the print_text() function
 * @param boolean $return	return the text instead of printing it
 */
function print_help_link($help, $helpText, $show_desc="", $use_print_text=false, $return=false) {
	global $pgv_lang, $view, $PGV_USE_HELPIMG, $PGV_IMAGES, $PGV_IMAGE_DIR, $SEARCH_SPIDER;

	$output='';
	if (!$SEARCH_SPIDER && $view!='preview' && $_SESSION['show_context_help']) {
		$output.=' <a class="help" tabindex="0" href="javascript:// ';
		if ($show_desc) {
			if ($use_print_text) {
				$output.=print_text($show_desc, 0, 1);
			} else {
				if (stristr($pgv_lang[$show_desc], "\"")) {
					$output.=preg_replace('/\"/','\'',$pgv_lang[$show_desc]);
				} else {
					$output.=strip_tags($pgv_lang[$show_desc]);
				}
			}
		} else {
			$output.=$help;
		}
		$output.="\" onclick=\"helpPopup('$help'); return false;\">";
		if ($PGV_USE_HELPIMG) {
			$output.='<img src="'.$PGV_IMAGE_DIR.'/'.$PGV_IMAGES['help']['small'].'" class="icon" width="15" height="15" alt="" /></a>';
		} else {
			$output.=$pgv_lang[$helpText].'&nbsp;&nbsp;</a>';
		}
	}

	if (!$return) {
		print $output;
	}
	return $output;
}

/**
 * print a language variable
 *
 * It accepts any kind of language variable. This can be a single variable but also
 * a variable with included variables that needs to be converted.
 * print_text, which used to be called print_help_text, now takes 3 parameters
 *		of which only the 1st is mandatory
 * The first parameter is the variable that needs to be processed.  At nesting level zero,
 *		this is the name of a $pgv_lang array entry.  "whatever" refers to
 *		$pgv_lang["whatever"].  At nesting levels greater than zero, this is the name of
 *		any global variable, but *without* the $ in front.  For example, VERSION or
 *		pgv_lang["whatever"] or factarray["rowname"].
 * The second parameter is $level for the nested vars in a sentence.  This indicates
 *		that the function has been called recursively.
 * The third parameter $noprint is for returning the text instead of printing it
 *		This parameter, when set to 2 means, in addition to NOT printing the result,
 *		the input string $help is text that needs to be interpreted instead of being
 *		the name of a $pgv_lang array entry.  This lets you use this function to work
 *		on something other than $pgv_lang array entries, but coded according to the
 *		same rules.
 * When we want it to return text we need to code:
 * print_text($mytext, 0, 2);
 * @param string $help		The variable that needs to be processed.
 * @param int $level		The position of the embedded variable
 * @param int $noprint		The switch if the text needs to be printed or returned
 */
function print_text($help, $level=0, $noprint=0){
	global $pgv_lang, $factarray, $faqlist, $COMMON_NAMES_THRESHOLD;
	global $INDEX_DIRECTORY, $GEDCOMS, $GEDCOM, $GEDCOM_TITLE, $LANGUAGE;
	global $GUESS_URL, $UpArrow, $DAYS_TO_SHOW_LIMIT, $MEDIA_DIRECTORY;
	global $repeat, $thumbnail, $xref, $pid;

	if (!isset($_SESSION["DEBUG_LANG"])) $DEBUG_LANG = "no";
	else $DEBUG_LANG = $_SESSION["DEBUG_LANG"];
	if ($DEBUG_LANG == "yes") print "[LANG_DEBUG] Variable called: ".$help."<br /><br />";
	$sentence = false;
	if ($level>0) {
		// Map legacy global variables (e.g. $VERSION) onto their replacement constants (e.g. PGV_VERSION)
		if ((preg_match('/^([A-Z_]+)$/', $help, $match) || preg_match('/^GLOBALS\[\'([A-Z_])\'\]+$/', $help, $match)) && defined('PGV_'.$match[1])) {
			$help='PGV_'.$match[1];
		}
		$value = false;
		// Only allow access to constants prefixed by PGV_
		if (substr($help, 0, 4)=='PGV_' && defined($help)) {
			$value=constant($help);
		} else {
			eval("if (isset(\$$help)) \$value = \$$help;");
		}
		if ($value===false) return false;
		$sentence = $value;
	}
	if ($sentence===false) {
		if ($noprint == 2) {
			$sentence = $help;
		} else {
			if (isset($pgv_lang[$help])) {
				$sentence = $pgv_lang[$help];
			} else {
				if ($DEBUG_LANG == "yes") {
					print "[LANG_DEBUG] Variable not present: ".$help."<br /><br />";
				}
				$sentence = $pgv_lang["help_not_exist"];
			}
		}
	}
	$mod_sentence = "";
	$replace = "";
	$replace_text = "";
	$sub = "";
	$pos1 = 0;
	$pos2 = 0;
	$ct = preg_match_all("/#([a-zA-Z0-9_.\-\[\]]+)#/", $sentence, $match, PREG_SET_ORDER);
	for($i=0; $i<$ct; $i++) {
		$value = "";
		$newreplace = preg_replace(array("/\[/","/\]/"), array("['","']"), $match[$i][1]);
		if ($DEBUG_LANG == "yes") print "[LANG_DEBUG] Embedded variable: ".$match[$i][1]."<br /><br />";
		$value = print_text($newreplace, $level+1);
		if ($value!==false) $sentence = str_replace($match[$i][0], $value, $sentence);
		else if ($noprint==0 && $level==0) $sentence = str_replace($match[$i][0], $match[$i][1].": ".$pgv_lang["var_not_exist"], $sentence);
	}
	// ------ Replace paired ~  by tag_start and tag_end (those vars contain CSS classes)
	while (stristr($sentence, "~") == TRUE){
		$pos1 = strpos($sentence, "~");
		$mod_sentence = substr_replace($sentence, " ", $pos1, 1);
		if (stristr($mod_sentence, "~")){  // If there's a second one:
			$pos2 = strpos($mod_sentence, "~");
			$replace = substr($sentence, ($pos1+1), ($pos2-$pos1-1));
			$replace_text = "<span class=\"helpstart\">".UTF8_strtoupper($replace)."</span>";
			$sentence = str_replace("~".$replace."~", $replace_text, $sentence);
		} else break;
	}
	if ($noprint>0) return $sentence;
	if ($level>0) return $sentence;
	print $sentence;
}
function print_help_index($help){
	global $pgv_lang;
	$sentence = $pgv_lang[$help];
	$mod_sentence = "";
	$replace = "";
	$replace_text = "";
	$sub = "";
	$pos1 = 0;
	$pos2 = 0;
	$admcol=false;
	$ch=0;
	$help_sorted = array();
	$var="";
	while (stristr($sentence, "#") == TRUE){
		$pos1 = strpos($sentence, "#");
		$mod_sentence = substr_replace($sentence, " ", $pos1, 1);
		$pos2 = strpos($mod_sentence, "#");
		$replace = substr($sentence, ($pos1+1), ($pos2-$pos1-1));
		$sub = preg_replace(array("/pgv_lang\\[/","/\]/"), array("",""), $replace);
		if (isset($pgv_lang[$sub])) {
			$items = preg_split("/,/", $pgv_lang[$sub]);
			$var = $pgv_lang[$items[1]];
		}
		$sub = preg_replace(array("/factarray\\[/","/\]/"), array("",""), $replace);
		if (isset($factarray[$sub])) {
			$items = preg_split("/,/", $factarray[$sub]);
			$var = $factarray[$items[1]];
		}
		if (substr($var,0,1)=="_") {
			$admcol=true;
			$ch++;
		}
		$replace_text = "<a href=\"".encode_url("help_text.php?help=".$items[0])."\">".$var."</a><br />";
		$help_sorted[$replace_text] = $var;
		$sentence = str_replace("#".$replace."#", $replace_text, $sentence);
	}
	uasort($help_sorted, "stringsort");
	if ($ch==0) $ch=count($help_sorted);
	else $ch +=$ch;
	if ($ch>0) print "<table width=\"100%\"><tr><td style=\"vertical-align: top;\"><ul>";
	$i=0;
	foreach ($help_sorted as $k => $help_item){
		print "<li>".$k."</li>";
		$i++;
		if ($i==ceil($ch/2)) print "</ul></td><td style=\"vertical-align: top;\"><ul>";
	}
	if ($ch>0) print "</ul></td></tr></table>";
}
/**
 * prints a JavaScript popup menu
 *
 * This function will print the DHTML required
 * to create a JavaScript Popup menu.  The $menu
 * parameter is an array that looks like this
 * $menu["label"] = "Charts";
 * $menu["labelpos"] = "down"; // tells where the text should be positioned relative to the picture options are up down left right
 * $menu["icon"] = "images/pedigree.gif";
 * $menu["hovericon"] = "images/pedigree2.gif";
 * $menu["link"] = "pedigree.php";
 * $menu["accesskey"] = "Z"; // optional accesskey
 * $menu["class"] = "menuitem";
 * $menu["hoverclass"] = "menuitem_hover";
 * $menu["flyout"] = "down"; // options are up down left right
 * $menu["items"] = array(); // an array of like menu items
 * $menu["onclick"] = "return javascript";  // java script to run on click
 * @author John Finlay
 * @param array $menu the menuitems array to print
 */
function print_menu($menu, $parentmenu="") {
	include_once 'includes/menu.php';
	$conv = array(
		'label'=>'label',
		'labelpos'=>'labelpos',
		'icon'=>'icon',
		'hovericon'=>'hovericon',
		'link'=>'link',
		'accesskey'=>'accesskey',
		'class'=>'class',
		'hoverclass'=>'hoverclass',
		'flyout'=>'flyout',
		'submenuclass'=>'submenuclass',
		'onclick'=>'onclick'
	);
	$obj = new Menu();
	if ($menu == 'separator') {
		$obj->isSeperator();
		$obj->printMenu();
		return;
	}
	$items = false;
	foreach ($menu as $k=>$v) {
		if ($k == 'items' && is_array($v) && count($v) > 0) $items = $v;
		else {
			if (isset($conv[$k])){
				if ($v != '') {
					$obj->$conv[$k] = $v;
				}
			}
		}
	}
	if ($items !== false) {
		foreach ($items as $sub) {
			$sobj = new Menu();
			if ($sub == 'separator') {
				$sobj->isSeperator();
				$obj->addSubmenu($sobj);
				continue;
			}
			foreach ($sub as $k2=>$v2) {
				if (isset($conv[$k2])) {
					if ($v2 != '') {
						$sobj->$conv[$k2] = $v2;
					}
				}
			}
			$obj->addSubmenu($sobj);
		}
	}
	$obj->printMenu();
}


//-------------------------------------------------------------------------------------------------------------
// switches between left and rigth align on chosen text direction
//-------------------------------------------------------------------------------------------------------------
function write_align_with_textdir_check($t_dir, $return=false)
{
  global $TEXT_DIRECTION;
  $out = "";
  if ($t_dir == "left")
  {
		if ($TEXT_DIRECTION == "ltr")
		{
			$out .= " style=\"text-align:left; \" ";
		}
		else
		{
			$out .= " style=\"text-align:right; \" ";
		}
  }
  else
  {
		if ($TEXT_DIRECTION == "ltr")
		{
			$out .= " style=\"text-align:right; \" ";
		}
		else
		{
			$out .= " style=\"text-align:left; \" ";
		}
  }
  if ($return) return $out;
  print $out;
}
//-- print theme change dropdown box
function print_theme_dropdown($style=0) {
	global $ALLOW_THEME_DROPDOWN, $ALLOW_USER_THEMES;

	if ($ALLOW_THEME_DROPDOWN && $ALLOW_USER_THEMES) {
		echo '<div class="theme_form">';
		$theme_menu=MenuBar::getThemeMenu();
		switch ($style) {
		case 0:
			echo $theme_menu->getMenuAsDropdown();
			break;
		case 1:
			echo $theme_menu->getMenu();
			break;
		}
		echo '</div>';
	} else {
		echo '&nbsp;';
	}
}

/**
 * Prepare text with parenthesis for printing
 * Convert & to &amp; for xhtml compliance
 *
 * @param string $text to be printed
 */
function PrintReady($text, $InHeaders=false, $trim=true) {
	global $query, $action, $firstname, $lastname, $place, $year, $DEBUG;
	global $TEXT_DIRECTION_array, $TEXT_DIRECTION, $USE_RTL_FUNCTIONS;
	// Check whether Search page highlighting should be done or not
	$HighlightOK = false;
	if (strstr($_SERVER["SCRIPT_NAME"], "search.php")) {  // If we're on the Search page
		if (!$InHeaders) {        //   and also in page body
			if ((isset($query) and ($query != "")) ) {  //   and the query isn't blank
				$HighlightOK = true;     // It's OK to mark search result
			}
		}
	}
	//-- convert all & to &amp;
	$text = preg_replace("/&/", "&amp;", $text);
	//$text = preg_replace(array("/&/","/</","/>/"), array("&amp;","&lt;","&gt;"), $text);
	//-- make sure we didn't double convert existing HTML entities like so:  &foo; to &amp;foo;
	$text = preg_replace("/&amp;(\w+);/", "&$1;", $text);
    if ($trim) $text = trim($text);
    //-- if we are on the search page body, then highlight any search hits
    //  In this routine, we will assume that the input string doesn't contain any
    //  \x01 or \x02 characters.  We'll represent the <span class="search_hit"> by \x01
    //  and </span> by \x02.  We will translate these \x01 and \x02 into their true
    //  meaning at the end.
    //
    //  This special handling is required in case the user has submitted a multiple
    //  argument search, in which the second or later arguments can be found in the
    //  <span> or </span> strings.
    if ($HighlightOK) {
			if (isset($query)) {
				$queries = explode(" ", $query);
				$newtext = $text;
				$hasallhits = true;
				foreach($queries as $index=>$query1) {
					$query1esc=preg_quote($query1, '/');
					if (@preg_match("/(".$query1esc.")/i", $text)) { // Use @ as user-supplied query might be invalid.
						$newtext = preg_replace("/(".$query1esc.")/i", "\x01$1\x02", $newtext);
					}
					else if (@preg_match("/(".UTF8_strtoupper($query1esc).")/", UTF8_strtoupper($text))) {
						$nlen = strlen($query1);
						$npos = strpos(UTF8_strtoupper($text), UTF8_strtoupper($query1));
						$newtext = substr_replace($newtext, "\x02", $npos+$nlen, 0);
						$newtext = substr_replace($newtext, "\x01", $npos, 0);
					}
					else $hasallhits = false;
				}
				if ($hasallhits) $text = $newtext;
			}
			if (isset($action) && ($action === "soundex")) {
				if (isset($firstname)) {
					$queries = explode(" ", $firstname);
					$newtext = $text;
					$hasallhits = true;
					foreach($queries as $index=>$query1) {
						$query1esc=preg_quote($query1, '/');
						if (preg_match("/(".$query1esc.")/i", $text)) {
							$newtext = preg_replace("/(".$query1esc.")/i", "\x01$1\x02", $newtext);
						}
						else if (preg_match("/(".UTF8_strtoupper($query1esc).")/", UTF8_strtoupper($text))) {
							$nlen = strlen($query1);
							$npos = strpos(UTF8_strtoupper($text), UTF8_strtoupper($query1));
							$newtext = substr_replace($newtext, "\x02", $npos+$nlen, 0);
							$newtext = substr_replace($newtext, "\x01", $npos, 0);
						}
						else $hasallhits = false;
					}
					if ($hasallhits) $text = $newtext;
				}
				if (isset($lastname)) {
					$queries = explode(" ", $lastname);
					$newtext = $text;
					$hasallhits = true;
					foreach($queries as $index=>$query1) {
						$query1esc=preg_quote($query1, '/');
						if (preg_match("/(".$query1esc.")/i", $text)) {
							$newtext = preg_replace("/(".$query1esc.")/i", "\x01$1\x02", $newtext);
						}
						else if (preg_match("/(".UTF8_strtoupper($query1esc).")/", UTF8_strtoupper($text))) {
							$nlen = strlen($query1);
							$npos = strpos(UTF8_strtoupper($text), UTF8_strtoupper($query1));
							$newtext = substr_replace($newtext, "\x02", $npos+$nlen, 0);
							$newtext = substr_replace($newtext, "\x01", $npos, 0);
						}
						else $hasallhits = false;
					}
					if ($hasallhits) $text = $newtext;
				}
				if (isset($place)) {
					$queries = explode(" ", $place);
					$newtext = $text;
					$hasallhits = true;
					foreach($queries as $index=>$query1) {
						$query1esc=preg_quote($query1, '/');
						if (preg_match("/(".$query1esc.")/i", $text)) {
							$newtext = preg_replace("/(".$query1esc.")/i", "\x01$1\x02", $newtext);
						}
						else if (preg_match("/(".UTF8_strtoupper($query1esc).")/", UTF8_strtoupper($text))) {
							$nlen = strlen($query1);
							$npos = strpos(UTF8_strtoupper($text), UTF8_strtoupper($query1));
							$newtext = substr_replace($newtext, "\x02", $npos+$nlen, 0);
							$newtext = substr_replace($newtext, "\x01", $npos, 0);
						}
						else $hasallhits = false;
					}
					if ($hasallhits) $text = $newtext;
				}
				if (isset($year)) {
					$queries = explode(" ", $year);
					$newtext = $text;
					$hasallhits = true;
					foreach($queries as $index=>$query1) {
						$query1=preg_quote($query1, '/');
						if (preg_match("/(".$query1.")/i", $text)) {
							$newtext = preg_replace("/(".$query1.")/i", "\x01$1\x02", $newtext);
						}
						else $hasallhits = false;
					}
					if ($hasallhits) $text = $newtext;
				}
			}
			// All the "Highlight start" and "Highlight end" flags are set:
			//  Delay the final clean-up and insertion of proper <span> and </span>
			//  until parentheses, braces, and brackets have been processed
    }

	// Look for strings enclosed in parentheses, braces, or brackets.
	//
	// Parentheses, braces, and brackets have weak directionality and aren't handled properly
	// when they enclose text whose directionality differs from that of the page.
	//
	// To correct the problem, we need to enclose the parentheses, braces, or brackets with
	// zero-width characters (&lrm; or &rlm;) having a directionality that matches the
	// directionality of the text that is enclosed by the parentheses, etc.
	if ($USE_RTL_FUNCTIONS || $TEXT_DIRECTION=="rtl") {
		$charPos = 0;
		$lastChar = strlen($text);
		$newText = "";
		while (true) {
			if ($charPos > $lastChar) break;
			$thisChar = substr($text, $charPos, 1);
			$charPos ++;
			if ($thisChar=="(" || $thisChar=="{" || $thisChar=="[") {
				$tempText = "";
				while (true) {
					$tempChar = "";
					if ($charPos > $lastChar) break;
					$tempChar = substr($text, $charPos, 1);
					$charPos ++;
					if ($tempChar==")" || $tempChar=="}" || $tempChar=="]") break;
					$tempText .= $tempChar;
				}
				$thisLang = whatLanguage($tempText);
				if (!isset($TEXT_DIRECTION_array[$thisLang]) || $TEXT_DIRECTION_array[$thisLang]=="ltr") {
					$newText .= getLRM() . $thisChar . $tempText. $tempChar . getLRM();
				} else {
					$newText .= getRLM() . $thisChar . $tempText. $tempChar . getRLM();
				}
			} else {
				$newText .= $thisChar;
			}
		}
		$text = $newText;
	}

    // Parentheses, braces, and brackets have been processed:
    // Finish processing of "Highlight Start and "Highlight end"
	$text = str_replace(array("\x02\x01", "\x02 \x01", "\x01", "\x02"), array("", " ", "<span class=\"search_hit\">", "</span>"), $text);
    return $text;
}
/**
 * print ASSO RELA information
 *
 * Ex1:
 * <code>1 ASSO @I1@
 * 2 RELA Twin</code>
 *
 * Ex2:
 * <code>1 CHR
 * 2 ASSO @I1@
 * 3 RELA Godfather
 * 2 ASSO @I2@
 * 3 RELA Godmother</code>
 *
 * @param string $pid		person or family ID
 * @param string $factrec	the raw gedcom record to print
 * @param string $linebr	optional linebreak
 */
function print_asso_rela_record($pid, $factrec, $linebr=false, $type='INDI') {
	global $GEDCOM, $SHOW_ID_NUMBERS, $TEXT_DIRECTION, $pgv_lang, $factarray, $PGV_IMAGE_DIR, $PGV_IMAGES, $view;
	global $PEDIGREE_FULL_DETAILS, $LANGUAGE, $lang_short_cut;
	// get ASSOciate(s) ID(s)
	$ct = preg_match_all("/\d ASSO @(.*)@/", $factrec, $match, PREG_SET_ORDER);
	for ($i=0; $i<$ct; $i++) {
		$level = substr($match[$i][0],0,1);
		$pid2 = $match[$i][1];
		if (empty($pid) && isset($match[1][1])) $pid = $match[1][1];
		// get RELAtionship field
		$autoRela = false;		// Indicates that the RELA information was automatically generated
		$assorec = get_sub_record($level, "$level ASSO ", $factrec, $i+1);
//		if (substr($_SERVER["SCRIPT_NAME"],1) == "pedigree.php") {
			$rct = preg_match("/\d RELA (.*)/", $assorec, $rmatch);
			if ($rct>0) {
				// RELAtionship name in user language
				$key = strtolower(trim($rmatch[1]));
				if (substr($key,0,1)=='*') {
					$autoRela = true;
					$key = substr($key,1);
				}
				$cr = preg_match_all("/sosa_(.*)/", $key, $relamatch, PREG_SET_ORDER);
				if ($cr > 0) {
					$rela = get_sosa_name($relamatch[0][1]);
				}
				else {
					if (isset($pgv_lang[$key])) $rela = $pgv_lang[$key];
					else if (isset($factarray[strtoupper($key)])) $rela = $factarray[strtoupper($key)];
					else $rela = $rmatch[1];
					if ($key == "nephew") {
						$node = get_relationship($pid, $pid2);
						if (isset($node["path"][1])) {
							$sex3 = Person::getInstance($node["path"][1])->getSex();
							if ($sex3 == "M")  $rela = $pgv_lang["bosa_brothers_offspring_2"];
							else if ($sex3 == "F")  $rela = $pgv_lang["bosa_sisters_offspring_2"];
						}
					}
					else if ($key == "niece") {
						$node = get_relationship($pid, $pid2);
						if (isset($node["path"][1])) {
							$sex3 = Person::getInstance($node["path"][1])->getSex();
							if ($sex3 == "M")  $rela = $pgv_lang["bosa_brothers_offspring_3"];
							else if ($sex3 == "F")  $rela = $pgv_lang["bosa_sisters_offspring_3"];
						}
					}
					else if ($key == "uncle" || $key == "aunt") {
						$node = get_relationship($pid, $pid2);
						if (isset($node["path"][1])) {
							$sex3 = Person::getInstance($node["path"][1])->getSex();
							if ($sex3 == "M")  $rela = $pgv_lang["sosa_{$key}_2"];
							else if ($sex3 == "F")  $rela = $pgv_lang["sosa_{$key}_3"];
						}
					}
				}
				$p = strpos($rela, "(=");
				if ($p>0) $rela = trim(substr($rela, 0, $p));
//				if ($pid2==$pid) print "<span class=\"details_label\">";
				// Allow special processing for different languages
				$func="rela_localisation_{$lang_short_cut[$LANGUAGE]}";
				if (function_exists($func))
					// Localise the relationship
					$func($rela);
				else
					print " {$rela}: ";
//				if ($pid2==$pid) print "</span>";
			}
			else $rela = $factarray["RELA"]; // default
//		}

		// ASSOciate ID link
		$gedrec = find_gedcom_record($pid2);
		if (strstr($gedrec, "@ INDI")!==false || strstr($gedrec, "@ SUBM")!==false) {
			// ID name
			if ((DisplayDetailsByID($pid2))||(showLivingNameByID($pid2))) {
				$name = get_person_name($pid2);
				$addname = get_add_person_name($pid2);
			}
			else {
				$name = $pgv_lang["private"];
				$addname = "";
			}
			print "<a href=\"".encode_url("individual.php?pid={$pid2}&ged={$GEDCOM}")."\">" . PrintReady($name);
//			if (!empty($addname)) print "<br />" . PrintReady($addname);
			if (!empty($addname)) print " - " . PrintReady($addname);
			if ($SHOW_ID_NUMBERS) {
				print "&nbsp;&nbsp;";
				if ($TEXT_DIRECTION=="rtl") print getRLM();
				print "(".$pid2.")";
				if ($TEXT_DIRECTION=="rtl") print getRLM();
			}
			print "</a>";
			// ID age
			if (preg_match("/2 DATE (.*)/", $factrec, $dmatch)) {
				$tmp=new Person($gedrec);
				$birth_date=$tmp->getBirthDate();
				$event_date=new GedcomDate($dmatch[1]);
				$death_date=$tmp->getDeathDate(false);
				$ageText = '';

				if (!strstr($factrec, "_BIRT_") && !strstr($factrec, "_DEAT_") && GedcomDate::Compare($event_date, $death_date)>=0 && $tmp->isDead()) {
					// After death, print time since death
					$age=get_age_at_event(GedcomDate::GetAgeGedcom($death_date, $event_date), true);
					if (!empty($age))
						if (GedcomDate::GetAgeGedcom($death_date, $event_date)=="0d") $ageText = "(".$pgv_lang["at_death_day"].")";
						else $ageText = "(".$age." ".$pgv_lang["after_death"].")";
				}
				else if (GedcomDate::GetAgeGedcom($birth_date, $event_date)!="0d") {
					$age=get_age_at_event(GedcomDate::GetAgeGedcom($birth_date, $event_date), false);
					if (!empty($age))
						$ageText = "({$pgv_lang['age']} {$age})";
				}
				if (!empty($ageText)) print '<span class="age"> '.PrintReady($ageText).'</span>';
			}

			// RELAtionship calculation : for a family print relationship to both spouses
			if ($view!="preview" && !$autoRela) {
				if ($type=='FAM') {
					$famrec = find_family_record($pid);
					if ($famrec) {
						$parents = find_parents_in_record($famrec);
						$pid1 = $parents["HUSB"];
						if ($pid1 && $pid1!=$pid2) print " - <a href=\"".encode_url("relationship.php?show_full={$PEDIGREE_FULL_DETAILS}&pid1={$pid1}&pid2={$pid2}&pretty=2&followspouse=1&ged={$GEDCOM}")."\">[" . $pgv_lang["relationship_chart"] . "<img src=\"$PGV_IMAGE_DIR/" . $PGV_IMAGES["sex"]["small"] . "\" title=\"" . $pgv_lang["husband"] . "\" alt=\"" . $pgv_lang["husband"] . "\" class=\"gender_image\" />]</a>";
						$pid1 = $parents["WIFE"];
						if ($pid1 && $pid1!=$pid2) print " - <a href=\"".encode_url("relationship.php?show_full={$PEDIGREE_FULL_DETAILS}&pid1={$pid1}&pid2={$pid2}&pretty=2&followspouse=1&ged={$GEDCOM}")."\">[" . $pgv_lang["relationship_chart"] . "<img src=\"$PGV_IMAGE_DIR/" . $PGV_IMAGES["sexf"]["small"] . "\" title=\"" . $pgv_lang["wife"] . "\" alt=\"" . $pgv_lang["wife"] . "\" class=\"gender_image\" />]</a>";
					}
				}
				else if ($pid!=$pid2) print " - <a href=\"".encode_url("relationship.php?show_full={$PEDIGREE_FULL_DETAILS}&pid1={$pid}&pid2={$pid2}&pretty=2&followspouse=1&ged={$GEDCOM}")."\">[" . $pgv_lang["relationship_chart"] . "]</a>";
			}

		}
		else if (strstr($gedrec, "@ FAM")!==false) {
			print "<a href=\"".encode_url("family.php?show_full=1&famid={$pid2}")."\">";
			if ($TEXT_DIRECTION == "ltr") print getLRM(); else print " " . getRLM();
			print "[".$pgv_lang["view_family"];
			if ($SHOW_ID_NUMBERS) print " " . getLRM() . "($pid2)" . getLRM();
			if ($TEXT_DIRECTION == "ltr") print getLRM() . "]</a>"; else print getRLM() . "]</a>";
		}
		else {
			print $pgv_lang["unknown"];
			if ($SHOW_ID_NUMBERS) {
				print "&nbsp;&nbsp;";
				if ($TEXT_DIRECTION=="rtl") print getRLM();
				print "(".$pid2.")";
				if ($TEXT_DIRECTION=="rtl") print getRLM();
			}
		}
		if ($linebr) print "<br />";
		print_fact_notes($assorec, $level+1);
		if (substr($_SERVER["SCRIPT_NAME"],1) == "pedigree.php") {
			print "<br />";
			if (function_exists('print_fact_sources')) print_fact_sources($assorec, $level+1);
		}
	}
}
/**
 * Format age of parents in HTML
 *
 * @param string $pid	child ID
 */
function format_parents_age($pid) {
	global $pgv_lang, $factarray, $SHOW_PARENTS_AGE;

	$html='';
	if ($SHOW_PARENTS_AGE) {
		$person=Person::getInstance($pid);
		$families=$person->getChildFamilies();
		// Multiple sets of parents (e.g. adoption) cause complications, so ignore.
		$birth_date=$person->getBirthDate();
		if ($birth_date->isOK() && count($families)==1) {
			$family=current($families);
			// Allow for same-sex parents
			foreach (array($family->getHusband(), $family->getWife()) as $parent) {
				if ($parent && $age=GedcomDate::GetAgeYears($parent->getBirthDate(), $person->getBirthDate())) {
					$deatdate=$parent->getDeathDate();
					$class='';
					switch ($parent->getSex()) {
					case 'F':
						// Highlight mothers who die in childbirth or shortly afterwards
						if ($deatdate->isOK() && $deatdate->MinJD()<$birth_date->MinJD()+90) {
							$class='parentdeath';
							$title=$factarray['_DEAT_MOTH'];
						} else {
							$title=$pgv_lang['mother'];
						}
						break;
					case 'M':
						// Highlight fathers who die before the birth
						if ($deatdate->isOK() && $deatdate->MinJD()<$birth_date->MinJD()) {
							$class='parentdeath';
							$title=$factarray['_DEAT_FATH'];
						} else {
							$title=$pgv_lang['father'];
						}
						break;
					default:
						$title=$pgv_lang['parent'];
						break;
					}
					if ($class) {
						$html.=' <span class="'.$class.'" title="'.$title.'">'.$parent->getSexImage().$age.'</span>';
					} else {
						$html.=' <span title="'.$title.'">'.$parent->getSexImage().$age.'</span>';
					}
				}
			}
			if ($html) {
				$html='<span class="age">'.$html.'</span>';
			}
		}
	}
	return $html;
}
/**
 * print fact DATE TIME
 *
 * @param Event $eventObj	Event to print the date for
 * @param boolean $anchor	option to print a link to calendar
 * @param boolean $time		option to print TIME value
 */
function format_fact_date(&$eventObj, $anchor=false, $time=false) {
	global $factarray, $pgv_lang, $pid, $SEARCH_SPIDER;

	if (!is_object($eventObj)) pgv_error_handler(2, "Must use Event object", __FILE__, __LINE__);
	$factrec = $eventObj->getGedcomRecord();
	$html='';
	// Recorded age
	$fact_age=get_gedcom_value('AGE', 2, $factrec);
	if (empty($fact_age))
		$fact_age=get_gedcom_value('DATE:AGE', 2, $factrec);
	$husb_age=get_gedcom_value('HUSB:AGE', 2, $factrec);
	$wife_age=get_gedcom_value('WIFE:AGE', 2, $factrec);

	// Calculated age
	if (preg_match('/2 DATE (.+)/', $factrec, $match)) {
		$date=new GedcomDate($match[1]);
		$html.=' '.$date->Display($anchor && !$SEARCH_SPIDER);
		// time
		if ($time) {
			$timerec=get_sub_record(2, '2 TIME', $factrec);
			if (empty($timerec)) {
				$timerec=get_sub_record(2, '2 DATE', $factrec);
			}
			if (preg_match('/[2-3] TIME (.*)/', $timerec, $tmatch)) {
				$html.=' - <span class="date">'.$tmatch[1].'</span>';
			}
		}
		$fact = $eventObj->getTag();
		$person = $eventObj->getParentObject();
		if (!is_null($person) && $person->getType()=='INDI') {
			// age of parents at child birth
			if ($fact=='BIRT') {
				$html .= format_parents_age($person->getXref());
			}
			// age at event
			else if ($fact!='CHAN' && $fact!='_TODO') {
				$birth_date=$person->getBirthDate(false);
				$death_date=$person->getDeathDate(false);
				$ageText = '';
				if ((GedcomDate::Compare($date, $death_date)<0 || !$person->isDead()) || $fact=='DEAT') {
					// Before death, print age
					$age=GedcomDate::GetAgeGedcom($birth_date, $date);
					// Only show calculated age if it differs from recorded age
					if (!empty($age)) {
						if (!empty($fact_age) && $fact_age!=$age ||
						    empty($fact_age) && empty($husb_age) && empty($wife_age) ||
						    !empty($husb_age) && $person->getSex()=='M' && $husb_age!= $age ||
						    !empty($wife_age) && $person->getSex()=='F' && $wife_age!=$age)
							$ageText = '('.$pgv_lang['age'].' '.get_age_at_event($age, false).')';
					}
				}
				if ($fact!='DEAT' && GedcomDate::Compare($date, $death_date)>=0) {
					// After death, print time since death
					$age=get_age_at_event(GedcomDate::GetAgeGedcom($death_date, $date), true);
					if (!empty($age))
						if (GedcomDate::GetAgeGedcom($death_date, $date)=="0d") $ageText = '('.$pgv_lang['at_death_day'].')';
						else $ageText = '('.$age.' '.$pgv_lang['after_death'].')';
				}
				if (!empty($ageText)) $html .= '<span class="age"> '.PrintReady($ageText).'</span>';
			}
		}
		else if (!is_null($person) && $person->getType()=='FAM') {
			$indirec=find_person_record($pid);
			$indi=new Person($indirec);
			$birth_date=$indi->getBirthDate(false);
			$death_date=$indi->getDeathDate(false);
			$ageText = '';
			if (GedcomDate::Compare($date, $death_date)<=0) {
				$age=GedcomDate::GetAgeGedcom($birth_date, $date);
				// Only show calculated age if it differs from recorded age
				if (!empty($age) && $age>0) {
					if (!empty($fact_age) && $fact_age!=$age ||
					    empty($fact_age) && empty($husb_age) && empty($wife_age) ||
					    !empty($husb_age) && $indi->getSex()=='M' && $husb_age!= $age ||
					    !empty($wife_age) && $indi->getSex()=='F' && $wife_age!=$age)
						$ageText = '('.$pgv_lang['age'].' '.get_age_at_event($age, false).')';
				}
			}
			if (!empty($ageText)) $html .= '<span class="age"> '.PrintReady($ageText).'</span>';
		}
	} else {
		// 1 DEAT Y with no DATE => print YES
		// 1 DEAT N is not allowed
		// It is not proper GEDCOM form to use a N(o) value with an event tag to infer that it did not happen.
		$factrec = str_replace("\r\nPGV_OLD\r\n", '', $factrec);
		$factrec = str_replace("\r\nPGV_NEW\r\n", '', $factrec);
		$factdetail = preg_split('/ /', trim($factrec));
		if (isset($factdetail)) if (count($factdetail) == 3) if (strtoupper($factdetail[2]) == 'Y') {
			$html.=$pgv_lang['yes'];
		}
	}
	// print gedcom ages
	foreach (array($factarray['AGE']=>$fact_age, $pgv_lang['husband']=>$husb_age, $pgv_lang['wife']=>$wife_age) as $label=>$age) {
		if (!empty($age)) {
			$html.=' <span class="label">'.$label.'</span>: <span class="age">'.PrintReady(get_age_at_event($age, false)).'</span>';
		}
	}
	return $html;
}
/**
 * print fact PLACe TEMPle STATus
 *
 * @param Event $eventObj	gedcom fact record
 * @param boolean $anchor	option to print a link to placelist
 * @param boolean $sub		option to print place subrecords
 * @param boolean $lds		option to print LDS TEMPle and STATus
 */
function format_fact_place(&$eventObj, $anchor=false, $sub=false, $lds=false) {
	global $SHOW_PEDIGREE_PLACES, $TEMPLE_CODES, $pgv_lang, $factarray, $SEARCH_SPIDER;
	if ($eventObj==null) return '';
	if (!is_object($eventObj)) {
		pgv_error_handler("2", "Object was not sent in, please use Event object", __FILE__, __LINE__);
		$factrec = $eventObj;
	}
	else $factrec = $eventObj->getGedcomRecord();
	$html='';

	$ct = preg_match("/2 PLAC (.*)/", $factrec, $match);
	if ($ct>0) {
		$html.=' ';
		$levels = preg_split("/,/", $match[1]);
		if ($anchor && (empty($SEARCH_SPIDER))) {
			$place = trim($match[1]);
			// reverse the array so that we get the top level first
			$levels = array_reverse($levels);
			$tempURL = "placelist.php?action=show&";
			foreach($levels as $pindex=>$ppart) {
				// routine for replacing ampersands
				$ppart = preg_replace("/amp\%3B/", "", trim($ppart));
				$tempURL .= "parent[{$pindex}]=".PrintReady($ppart).'&';
			}
			$tempURL .= 'level='.count($levels);
			$html .= '<a href="'.encode_url($tempURL).'"> '.PrintReady($place).'</a>';
		} else {
			if (!$SEARCH_SPIDER) {
				$html.=' -- ';
			}
			for ($level=0; $level<$SHOW_PEDIGREE_PLACES; $level++) {
				if (!empty($levels[$level])) {
					if ($level>0) {
						$html.=", ";
					}
					$html.=PrintReady($levels[$level]);
				}
			}
		}
	}
	$ctn=0;
	if ($sub) {
		$placerec = get_sub_record(2, '2 PLAC', $factrec);
		if (!empty($placerec)) {
			$cts = preg_match('/\d ROMN (.*)/', $placerec, $match);
			if ($cts>0) {
				if ($ct>0) {
					$html.=" - ";
				}
				$html.=' '.PrintReady($match[1]);
			}
			$cts = preg_match('/\d _HEB (.*)/', $placerec, $match);
			if ($cts>0) {
				if ($ct>0) {
					$html.=' - ';
				}
				$html.=' '.PrintReady($match[1]);
			}
			$map_lati="";
			$cts = preg_match('/\d LATI (.*)/', $placerec, $match);
			if ($cts>0) {
				$map_lati=$match[1];
				$html.='<br /><span class="label">'.$factarray['LATI'].': </span>'.$map_lati;
			}
			$map_long="";
			$cts = preg_match('/\d LONG (.*)/', $placerec, $match);
			if ($cts>0) {
				$map_long=$match[1];
				$html.=' <span class="label">'.$factarray['LONG'].': </span>'.$map_long;
			}
			if ($map_lati and $map_long) {
				$map_lati=trim(strtr($map_lati,"NSEW,�"," - -. ")); // S5,6789 ==> -5.6789
				$map_long=trim(strtr($map_long,"NSEW,�"," - -. ")); // E3.456� ==> 3.456
				$html.=' <a target="_BLANK" href="'.encode_url("http://www.mapquest.com/maps/map.adp?searchtype=address&formtype=latlong&latlongtype=decimal&latitude={$map_lati}&longitude={$map_long}").'"><img src="images/mapq.gif" border="0" alt="Mapquest &copy;" title="Mapquest &copy;" /></a>';
				$html.=' <a target="_BLANK" href="'.encode_url("http://maps.google.com/maps?q={$map_lati},{$map_long}").'"><img src="images/bubble.gif" border="0" alt="Google Maps &copy;" title="Google Maps &copy;" /></a>';
				$html.=' <a target="_BLANK" href="'.encode_url("http://www.multimap.com/map/browse.cgi?lat={$map_lati}&lon={$map_long}&scale=&icon=x").'"><img src="images/multim.gif" border="0" alt="Multimap &copy;" title="Multimap &copy;" /></a>';
				$html.=' <a target="_BLANK" href="'.encode_url("http://www.terraserver.com/imagery/image_gx.asp?cpx={$map_long}&cpy={$map_lati}&res=30&provider_id=340").'"><img src="images/terrasrv.gif" border="0" alt="TerraServer &copy;" title="TerraServer &copy;" /></a>';
			}
			if (preg_match('/\d NOTE (.*)/', $placerec, $match)) {
				ob_start();
				print_fact_notes($placerec, 3);
				$html.=ob_get_contents();
				ob_end_clean();
			}
		}
	}
	if ($lds) {
		if (preg_match('/2 TEMP (.*)/', $factrec, $match)) {
			$tcode=trim($match[1]);
			if (array_key_exists($tcode, $TEMPLE_CODES)) {
				$html.='<br/>'.$pgv_lang['temple'].': '.$TEMPLE_CODES[$tcode];
			} else {
				$html.='<br/>'.$pgv_lang['temple_code'].$tcode;
			}
		}
		if (preg_match('/2 STAT (.*)/', $factrec, $match)) {
			$html.='<br />'.$pgv_lang['status'].': '.trim($match[1]);
			if (preg_match('/3 DATE (.*)/', $factrec, $match)) {
				$date=new GedcomDate($match[1]);
				$html.=', '.$factarray['STAT:DATE'].': '.$date->Display(false);
			}
		}
	}
	return $html;
}
/**
 * print first major fact for an Individual
 *
 * @param string $key	indi pid
 */
function format_first_major_fact($key, $majorfacts = array("BIRT", "CHR", "BAPM", "DEAT", "BURI", "BAPL", "ADOP")) {
	global $pgv_lang, $factarray, $LANGUAGE, $TEXT_DIRECTION;

	$html='';
	$person = GedcomRecord::getInstance($key);
	if (is_null($person)) return;
	foreach ($majorfacts as $indexval => $fact) {
		$event = $person->getFactByType($fact);
		if (!is_null($event) && $event->hasDatePlace() && $event->canShow()) {
			$html.='<span dir="'.$TEXT_DIRECTION.'"><br /><i>';
			$html .= $event->getLabel();
			$html.=' '.format_fact_date($event).format_fact_place($event).'</i></span>';
			break;
		}
	}
	return $html;
}

/**
 * Check for facts that may exist only once for a certain record type.
 * If the fact already exists in the second array, delete it from the first one.
 */
function CheckFactUnique($uniquefacts, $recfacts, $type) {
	foreach($recfacts as $indexval => $factarray) {
		$fact=false;
		if (is_object($factarray)) {
			/* @var $factarray Event */
			$fact = $factarray->getTag();
		}
		else {
			if (($type == "SOUR") || ($type == "REPO")) $factrec = $factarray[0];
			if (($type == "FAM") || ($type == "INDI")) $factrec = $factarray[1];
			 
		$ft = preg_match("/1 (\w+)(.*)/", $factrec, $match);
		if ($ft>0) {
			$fact = trim($match[1]);
			}
		}
		if ($fact!==false) {
			$key = array_search($fact, $uniquefacts);
			if ($key !== false) unset($uniquefacts[$key]);
		}
	}
	return $uniquefacts;
}

/**
 * Print a new fact box on details pages
 * @param string $id	the id of the person,family,source etc the fact will be added to
 * @param array $usedfacts	an array of facts already used in this record
 * @param string $type	the type of record INDI, FAM, SOUR etc
 */
function print_add_new_fact($id, $usedfacts, $type) {
	global $factarray, $pgv_lang;
	global $INDI_FACTS_ADD,    $FAM_FACTS_ADD,    $SOUR_FACTS_ADD,    $REPO_FACTS_ADD;
	global $INDI_FACTS_UNIQUE, $FAM_FACTS_UNIQUE, $SOUR_FACTS_UNIQUE, $REPO_FACTS_UNIQUE;
	global $INDI_FACTS_QUICK,  $FAM_FACTS_QUICK,  $SOUR_FACTS_QUICK,  $REPO_FACTS_QUICK;

	switch ($type) {
	case "INDI":
		$addfacts   =preg_split("/[, ;:]+/", $INDI_FACTS_ADD,    -1, PREG_SPLIT_NO_EMPTY);
		$uniquefacts=preg_split("/[, ;:]+/", $INDI_FACTS_UNIQUE, -1, PREG_SPLIT_NO_EMPTY);
		$quickfacts =preg_split("/[, ;:]+/", $INDI_FACTS_QUICK,  -1, PREG_SPLIT_NO_EMPTY);
		break;
	case "FAM":
		$addfacts   =preg_split("/[, ;:]+/", $FAM_FACTS_ADD,     -1, PREG_SPLIT_NO_EMPTY);
		$uniquefacts=preg_split("/[, ;:]+/", $FAM_FACTS_UNIQUE,  -1, PREG_SPLIT_NO_EMPTY);
		$quickfacts =preg_split("/[, ;:]+/", $FAM_FACTS_QUICK,   -1, PREG_SPLIT_NO_EMPTY);
		break;
	case "SOUR":
		$addfacts   =preg_split("/[, ;:]+/", $SOUR_FACTS_ADD,    -1, PREG_SPLIT_NO_EMPTY);
		$uniquefacts=preg_split("/[, ;:]+/", $SOUR_FACTS_UNIQUE, -1, PREG_SPLIT_NO_EMPTY);
		$quickfacts =preg_split("/[, ;:]+/", $SOUR_FACTS_QUICK,  -1, PREG_SPLIT_NO_EMPTY);
		break;
	case "REPO":
		$addfacts   =preg_split("/[, ;:]+/", $REPO_FACTS_ADD,    -1, PREG_SPLIT_NO_EMPTY);
		$uniquefacts=preg_split("/[, ;:]+/", $REPO_FACTS_UNIQUE, -1, PREG_SPLIT_NO_EMPTY);
		$quickfacts =preg_split("/[, ;:]+/", $REPO_FACTS_QUICK,  -1, PREG_SPLIT_NO_EMPTY);
		break;
	default:
		return;
	}
	$addfacts=array_merge(CheckFactUnique($uniquefacts, $usedfacts, $type), $addfacts);
	$quickfacts=array_intersect($quickfacts, $addfacts);

	usort($addfacts, "factsort");
	print "<tr><td class=\"descriptionbox\">";
	print_help_link("add_new_facts_help", "qm");
	print $pgv_lang["add_fact"]."</td>";
	print "<td class=\"optionbox\">";
	print "<form method=\"get\" name=\"newfactform\" action=\"\" onsubmit=\"return false;\">";
	print "<select id=\"newfact\" name=\"newfact\">";
	foreach($addfacts as $indexval => $fact) {
		print PrintReady("<option value=\"$fact\">".$factarray[$fact]. " [".$fact."]</option>");
	}
	if (($type == "INDI") || ($type == "FAM")) print "<option value=\"EVEN\">".$pgv_lang["custom_event"]." [EVEN]</option>";
	if (!empty($_SESSION["clipboard"])) {
		foreach($_SESSION["clipboard"] as $key=>$fact) {
			if ($fact["type"]==$type || $fact["type"]=='all') {
				print "<option value=\"clipboard_$key\">".$pgv_lang["add_from_clipboard"]." ".$factarray[$fact["fact"]]."</option>";
			}
		}
	}
	print "</select>";
	print "<input type=\"button\" value=\"".$pgv_lang["add"]."\" onclick=\"add_record('$id', 'newfact');\" />";
	foreach($quickfacts as $k=>$v) echo "&nbsp;<small><a href='javascript://$v' onclick=\"add_new_record('$id', '$v');return false;\">".$factarray["$v"]."</a></small>&nbsp;";
	print "</form>";
	print "</td></tr>";
}

/**
 * javascript declaration for calendar popup
 *
 * @param none
 */
function init_calendar_popup() {
	global $pgv_lang, $WEEK_START;

	print "<script language=\"JavaScript\" type='text/javascript'>";
	// month names
	print "cal_setMonthNames(";
	foreach(array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec') as $n=>$mon) {
		if ($n>0) print ",";
		print "\"".$pgv_lang[$mon]."\"";
	}
	print ");";
	// day headers
	print "cal_setDayHeaders(";
	foreach(array('sunday_1st','monday_1st','tuesday_1st','wednesday_1st','thursday_1st','friday_1st','saturday_1st') as $indexval => $day) {
		if (isset($pgv_lang[$day])) {
			if ($day!=="sunday_1st") print ",";
			print "\"".$pgv_lang[$day]."\"";
		}
	}
	print ");";
	// week start day
	print "cal_setWeekStart(".$WEEK_START.");";
	print "</script>";
}

/**
 * prints a link to open the Find Special Character window
 * @param string $element_id	the ID of the element the value will be pasted to
 * @param string $indiname		the id of the element the name should be pasted to
 * @param boolean $asString		Whether or not the HTML should be returned as a string or printed
 * @param boolean $multiple		Whether or not the user will be selecting multiple people
 * @param string $ged			The GEDCOM to search in
 */
function print_findindi_link($element_id, $indiname, $asString=false, $multiple=false, $ged='', $filter='') {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;

	$text = $pgv_lang["find_individual"];
	if (empty($ged)) $ged=$GEDCOM;
	if (isset($PGV_IMAGES["indi"]["button"])) $Link = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["indi"]["button"]."\" alt=\"".$text."\" title=\"".$text."\" border=\"0\" align=\"middle\" />";
	else $Link = $text;
	$out = " <a href=\"javascript:;\" onclick=\"findIndi(document.getElementById('".$element_id."'), document.getElementById('".$indiname."'), '".$multiple."', '".$ged."', '".$filter."'); findtype='individual'; return false;\">";
	$out .= $Link;
	$out .= "</a>";
	if ($asString) return $out;
	print $out;
}

function print_findplace_link($element_id, $ged='', $asString=false) {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;

	if (empty($ged)) $ged=$GEDCOM;
	$text = $pgv_lang["find_place"];
	if (isset($PGV_IMAGES["place"]["button"])) $Link = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["place"]["button"]."\" alt=\"".$text."\" title=\"".$text."\" border=\"0\" align=\"middle\" />";
	else $Link = $text;
	$out = " <a href=\"javascript:;\" onclick=\"findPlace(document.getElementById('".$element_id."'), '".$ged."'); return false;\">";
	$out .= $Link;
	$out .= "</a>";
	if ($asString) return $out;
	print $out;
}

function print_findfamily_link($element_id, $ged='', $asString=false) {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;

	if (empty($ged)) $ged=$GEDCOM;
	$text = $pgv_lang["find_familyid"];
	if (isset($PGV_IMAGES["family"]["button"])) $Link = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["family"]["button"]."\" alt=\"".$text."\" title=\"".$text."\" border=\"0\" align=\"middle\" />";
	else $Link = $text;
	$out = " <a href=\"javascript:;\" onclick=\"findFamily(document.getElementById('".$element_id."'), '".$ged."'); return false;\">";
	$out .= $Link;
	$out .= "</a>";
	if ($asString) return $out;
	print $out;
}

function print_specialchar_link($element_id, $vert, $asString=false) {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES;

	$text = $pgv_lang["find_specialchar"];
	if (isset($PGV_IMAGES["keyboard"]["button"])) $Link = "<img id=\"".$element_id."_spec\" name=\"".$element_id."_spec\" src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["keyboard"]["button"]."\"  alt=\"".$text."\"  title=\"".$text."\" border=\"0\" align=\"middle\" />";
	else $Link = $text;
	$out = " <a href=\"javascript:;\" onclick=\"findSpecialChar(document.getElementById('".$element_id."')); updatewholename(); return false;\">";
	$out .= $Link;
	$out .= "</a>";
	if ($asString) return $out;
	print $out;
}

function print_autopaste_link($element_id, $choices, $concat=1, $name=1, $submit=0) {
	global $pgv_lang;

	print "<small>";
	foreach ($choices as $indexval => $choice) {
		print " &nbsp;<a href=\"javascript:;\" onclick=\"document.getElementById('".$element_id."').value ";
		if ($concat) print "+=' "; else print "='";
		print $choice."'; ";
		if ($name) print " updatewholename();";
		if ($submit) print " document.forms[0].submit();";
		print " return false;\">".$choice."</a>";
	}
	print "</small>";
}

function print_findsource_link($element_id, $sourcename="", $asString=false, $ged='') {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;

	if (empty($ged)) $ged=$GEDCOM;
	$text = $pgv_lang["find_sourceid"];
	if (isset($PGV_IMAGES["source"]["button"])) $Link = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["source"]["button"]."\" alt=\"".$text."\" title=\"".$text."\" border=\"0\" align=\"middle\" />";
	else $Link = $text;
	$out = " <a href=\"javascript:;\" onclick=\"findSource(document.getElementById('".$element_id."'), document.getElementById('".$sourcename."'), '".$ged."'); findtype='source'; return false;\">";
	$out .= $Link;
	$out .= "</a>";
	if ($asString) return $out;
	print $out;
}

function print_findrepository_link($element_id, $ged='', $asString=false) {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;

	if (empty($ged)) $ged=$GEDCOM;
	$text = $pgv_lang["find_repository"];
	if (isset($PGV_IMAGES["repository"]["button"])) $Link = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["repository"]["button"]."\" alt=\"".$text."\" title=\"".$text."\" border=\"0\" align=\"middle\" />";
	else $Link = $text;
	$out = " <a href=\"javascript:;\" onclick=\"findRepository(document.getElementById('".$element_id."'), '".$ged."'); return false;\">";
	$out .= $Link;
	$out .= "</a>";
	if ($asString) return $out;
	print $out;
}

function print_findmedia_link($element_id, $choose="", $ged='', $asString=false) {
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;

	$out = '';
	$text = $pgv_lang["find_media"];
	if (empty($ged)) $ged=$GEDCOM;
	if (isset($PGV_IMAGES["media"]["button"])) $Link = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["media"]["button"]."\" alt=\"".$text."\" title=\"".$text."\" border=\"0\" align=\"middle\" />";
	else $Link = $text;
	$out .= " <a href=\"javascript:;\" onclick=\"findMedia(document.getElementById('".$element_id."'), '".$choose."', '".$ged."'); return false;\">";
	$out .=  $Link;
	$out .= "</a>";
	if ($asString) return $out;
	print $out;
}

/**
 * get a quick-glance view of current LDS ordinances
 * @param string $indirec
 * @return string
 */
function get_lds_glance($indirec) {
	$text = "";

	$ord = get_sub_record(1, "1 BAPL", $indirec);
	if ($ord) $text .= "B";
	else $text .= "_";
	$ord = get_sub_record(1, "1 ENDL", $indirec);
	if ($ord) $text .= "E";
	else $text .= "_";
	$found = false;
	$ct = preg_match_all("/1 FAMS @(.*)@/", $indirec, $match, PREG_SET_ORDER);
	for($i=0; $i<$ct; $i++) {
		$famrec = find_family_record($match[$i][1]);
		if ($famrec) {
			$ord = get_sub_record(1, "1 SLGS", $famrec);
			if ($ord) {
				$found = true;
				break;
			}
		}
	}
	if ($found) $text .= "S";
	else $text .= "_";
	$ord = get_sub_record(1, "1 SLGC", $indirec);
	if ($ord) $text .= "P";
	else $text .= "_";
	return $text;
}

/**
 * This function produces a hexadecimal dump of the input string for debugging purposes
 */

function DumpString($input) {
	if (empty($input)) return false;

	$UTF8 = array();
	$hex1L = "";
	$hex1R = "";
	$hex2L = "";
	$hex2R = "";
	$hex3L = "";
	$hex3R = "";
	$hex4L = "";
	$hex4R = "";

	$pos = 0;
	while (true) {
		// Separate the input string into UTF8 characters
		$byte0 = ord(substr($input, $pos, 1));
		$charLen = 1;
		if (($byte0 & 0xE0) == 0xC0) $charLen = 2;  // 2-byte sequence
		if (($byte0 & 0xF0) == 0xE0) $charLen = 3;  // 3-byte sequence
		if (($byte0 & 0xF8) == 0xF0) $charLen = 4;  // 4-byte sequence
		$thisChar = substr($input, $pos, $charLen);
		$UTF8[] = $thisChar;

		// Separate the current UTF8 character into hexadecimal digits
		$byte = bin2hex(substr($thisChar, 0, 1));
		$hex1L .= substr($byte, 0, 1);
		$hex1R .= substr($byte, 1, 1);

		if ($charLen > 1) {
			$byte = bin2hex(substr($thisChar, 1, 1));
			$hex2L .= substr($byte, 0, 1);
			$hex2R .= substr($byte, 1, 1);
		} else {
			$hex2L .= " ";
			$hex2R .= " ";
		}

		if ($charLen > 2) {
			$byte = bin2hex(substr($thisChar, 2, 1));
			$hex3L .= substr($byte, 0, 1);
			$hex3R .= substr($byte, 1, 1);
		} else {
			$hex3L .= " ";
			$hex3R .= " ";
		}

		if ($charLen > 3) {
			$byte = bin2hex(substr($thisChar, 3, 1));
			$hex4L .= substr($byte, 0, 1);
			$hex4R .= substr($byte, 1, 1);
		} else {
			$hex4L .= " ";
			$hex4R .= " ";
		}

		$pos += $charLen;
		if ($pos>=strlen($input)) break;
	}

	$pos = 0;
	$lastPos = count($UTF8);
	$haveByte4 = (trim($hex4L)!="");
	$haveByte3 = (trim($hex3L)!="");
	$haveByte2 = (trim($hex2L)!="");

	// We're ready: now output everything
	print "<br /><code><span dir=\"ltr\">";
	while (true) {
		$lineLength = $lastPos - $pos;
		if ($lineLength>100) $lineLength = 100;

		// Line 1: ruler
		$thisLine = substr("      ".$pos, -6)." ";
		$thisLine .= substr("........10........20........30........40........50........60........70........80........90.......100", 0, $lineLength);
		print str_replace(" ", "&nbsp;", $thisLine)."<br />";

		// Line 2: UTF8 character string
		$thisLine = "  UTF8 ";
		for ($i=$pos; $i<($pos+$lineLength); $i++) {
			if (ord(substr($UTF8[$i], 0, 1)) < 0x20) $thisLine .= getLRM() . " ";
			else $thisLine .= getLRM() . $UTF8[$i];
		}
		print str_replace(array(" ", PGV_UTF8_LRM, PGV_UTF8_RLM), array("&nbsp;", "&nbsp;", "&nbsp;"), $thisLine)."<br />";

		// Line 3:  First hexadecimal byte
		$thisLine = "Byte 1 ";
		$thisLine .= substr($hex1L, $pos, $lineLength);
		$thisLine .= "<br />";
		$thisLine .= "       ";
		$thisLine .= substr($hex1R, $pos, $lineLength);
		$thisLine .= "<br />";
		print str_replace(array(" ", "<br&nbsp;/>"), array("&nbsp;", "<br />"), $thisLine);

		// Line 4:  Second hexadecimal byte
		if ($haveByte2) {
			$thisLine = "Byte 2 ";
			$thisLine .= substr($hex2L, $pos, $lineLength);
			$thisLine .= "<br />";
			$thisLine .= "       ";
			$thisLine .= substr($hex2R, $pos, $lineLength);
			$thisLine .= "<br />";
			print str_replace(array(" ", "<br&nbsp;/>"), array("&nbsp;", "<br />"), $thisLine);
		}

		// Line 5:  Third hexadecimal byte
		if ($haveByte3) {
			$thisLine = "Byte 3 ";
			$thisLine .= substr($hex3L, $pos, $lineLength);
			$thisLine .= "<br />";
			$thisLine .= "       ";
			$thisLine .= substr($hex3R, $pos, $lineLength);
			$thisLine .= "<br />";
			print str_replace(array(" ", "<br&nbsp;/>"), array("&nbsp;", "<br />"), $thisLine);
		}

		// Line 6:  Fourth hexadecimal byte
		if ($haveByte4) {
			$thisLine = "Byte 4 ";
			$thisLine .= substr($hex4L, $pos, $lineLength);
			$thisLine .= "<br />";
			$thisLine .= "       ";
			$thisLine .= substr($hex4R, $pos, $lineLength);
			$thisLine .= "<br />";
			print str_replace(array(" ", "<br&nbsp;/>"), array("&nbsp;", "<br />"), $thisLine);
		}
		print "<br />";
		$pos += $lineLength;
		if ($pos >= $lastPos) break;
	}

	print "</span></code>";
	return true;
}
?>
