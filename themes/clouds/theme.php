<?php
/**
 * Clouds theme
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView Cloudy theme
 * Original author w.a. bastein http://genealogy.bastein.biz
 * Copyright (C) 2010  PGV Development Team.  All rights reserved.
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
 * @package webtrees
 * @subpackage Themes
 * @version $Id$
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$theme_name       = "clouds";
$modules 		  = WT_THEME_DIR . "modules.css";
$stylesheet       = WT_THEME_DIR . "style.css";
$print_stylesheet = WT_THEME_DIR . "print.css";			//-- CSS level 2 print stylesheet to use
$headerfile       = WT_THEME_DIR . "header.php";		//-- Header information for the site
$rtl_stylesheet   = WT_THEME_DIR . "style_rtl.css";		//-- CSS level 2 stylesheet to use
$toplinks         = WT_THEME_DIR . "toplinks.php";		//-- File to display the icons and links to different sections    
$footerfile       = WT_THEME_DIR . "footer.php";		//-- Footer information for the site     
$WT_IMAGE_DIR     = WT_THEME_DIR . "images";			//-- directory to look for images
$FAVICON          = $WT_IMAGE_DIR. "/favicon.ico";
$WT_USE_HELPIMG   = true;                                // set to true to use image for help questionmark, set to false to use $wt_lang["qm"]
$WT_MENU_LOCATION = "top";

//-- variables for image names
//- WT main icons
$WT_IMAGES["calendar"]["large"] = "calendar.gif";
$WT_IMAGES["clippings"]["large"] = "clippings.gif";
$WT_IMAGES["favorite"]["large"] = "fav.gif";
$WT_IMAGES["gedcom"]["large"] = "gedcom.gif";
$WT_IMAGES["help"]["large"] = "help.gif";
$WT_IMAGES["home"]["large"] = "home.gif";
$WT_IMAGES["indis"]["large"] = "indis.gif";
$WT_IMAGES["lists"]["large"] = "lists.gif";
$WT_IMAGES["media"]["large"] = "media.gif";
$WT_IMAGES["menu_repository"]["large"] = "menu_repository.gif";
$WT_IMAGES["menu_source"]["large"] = "menu_source.gif";
$WT_IMAGES["mypage"]["large"] = "mypage.gif";
$WT_IMAGES["notes"]["large"] = "notes.gif";
$WT_IMAGES["pedigree"]["large"] = "pedigree.gif";
$WT_IMAGES["printer"]["large"] = "printer.gif";
$WT_IMAGES["reports"]["large"] = "report.gif";
$WT_IMAGES["repository"]["large"] = "repository.gif";
$WT_IMAGES["search"]["large"] = "search.gif";
$WT_IMAGES["sfamily"]["large"] = "sfamily.gif";
$WT_IMAGES["source"]["large"] = "source.gif";
$WT_IMAGES["sex"]["large"] = "male.gif";
$WT_IMAGES["sexf"]["large"] = "female.gif";
$WT_IMAGES["sexn"]["large"] = "fe_male.gif";
$WT_IMAGES["edit_source"]["large"] = "small/edit_sour.gif";

$WT_IMAGES["edit_fam"]["large"] = "edit_fam.gif";
$WT_IMAGES["edit_indi"]["large"] = "edit_indi.gif";
$WT_IMAGES["edit_sour"]["large"] = "edit_sour.gif";
$WT_IMAGES["edit_repo"]["large"] = "Sedit_repo.gif";

//- PGV small icons
$WT_IMAGES["admin"]["small"] = "small/admin.gif";
$WT_IMAGES["ancestry"]["small"] = "small/ancestry.gif";
$WT_IMAGES["calendar"]["small"] = "small/calendar.gif";
$WT_IMAGES["cfamily"]["small"] = "small/cfamily.gif";
$WT_IMAGES["childless"]["small"] = "small/childless.gif";
$WT_IMAGES["clippings"]["small"] = "small/clippings.gif";
$WT_IMAGES["descendant"]["small"] = "small/descendancy.gif";
$WT_IMAGES["edit_fam"]["small"] = "small/edit_fam.gif";
$WT_IMAGES["edit_sour"]["small"] = "small/edit_sour.gif";
$WT_IMAGES["edit_repo"]["small"] = "small/edit_repo.gif";
$WT_IMAGES["fambook"]["small"] = "small/fambook.gif";
$WT_IMAGES["fanchart"]["small"] = "small/fanchart.gif";
$WT_IMAGES["favorite"]["small"] = "small/fav.gif";
$WT_IMAGES["favorites"]["small"] = "small/gedcom.gif";
$WT_IMAGES["gedcom"]["small"] = "small/gedcom.gif";
$WT_IMAGES["help"]["small"] = "small/help.gif";
$WT_IMAGES["home"]["small"] = "small/home.gif";
$WT_IMAGES["hourglass"]["small"] = "small/hourglass.gif";
$WT_IMAGES["indis"]["small"] = "small/indis.gif";
$WT_IMAGES["lists"]["small"] = "small/lists.gif";
$WT_IMAGES["media"]["small"] = "small/media.gif";
$WT_IMAGES["menu_help"]["small"] = "small/menu_help.gif";
$WT_IMAGES["menu_media"]["small"] = "small/menu_media.gif";
$WT_IMAGES["menu_repository"]["small"] = "small/menu_repository.gif";
$WT_IMAGES["menu_source"]["small"] = "small/menu_source.gif";
$WT_IMAGES["mypage"]["small"] = "small/mypage.gif";
$WT_IMAGES["notes"]["small"] = "small/notes.gif";
$WT_IMAGES["patriarch"]["small"] = "small/patriarch.gif";
$WT_IMAGES["pedigree"]["small"] = "small/pedigree.gif";
$WT_IMAGES["place"]["small"] = "small/place.gif";
$WT_IMAGES["printer"]["small"] = "small/printer.gif";
$WT_IMAGES["relationship"]["small"] = "small/relationship.gif";
$WT_IMAGES["reports"]["small"] = "small/report.gif";
$WT_IMAGES["repository"]["small"] = "small/repository.gif";
$WT_IMAGES["rings"]["small"] = "small/rings.gif";
$WT_IMAGES["search"]["small"] = "small/search.gif";
$WT_IMAGES["sex"]["small"] = "small/male.gif";
$WT_IMAGES["sexf"]["small"] = "small/female.gif";
$WT_IMAGES["sexn"]["small"] = "small/fe_male.gif";
$WT_IMAGES["sfamily"]["small"] = "small/sfamily.gif";
$WT_IMAGES["source"]["small"] = "small/source.gif";
$WT_IMAGES["statistic"]["small"] = "small/statistic.gif";
$WT_IMAGES["timeline"]["small"] = "small/timeline.gif";
$WT_IMAGES["tree"]["small"] = "small/gedcom.gif";
$WT_IMAGES["wiki"]["small"] = "small/w_22.png";

//- PGV buttons for data entry pages
$WT_IMAGES["addmedia"]["button"] = "buttons/addmedia.gif";
$WT_IMAGES["addrepository"]["button"] = "buttons/addrepository.gif";
$WT_IMAGES["addsource"]["button"] = "buttons/addsource.gif";
$WT_IMAGES["addnote"]["button"] = "buttons/addnote.gif";
$WT_IMAGES["autocomplete"]["button"] = "buttons/autocomplete.gif";
$WT_IMAGES["calendar"]["button"] = "buttons/calendar.gif";
$WT_IMAGES["family"]["button"] = "buttons/family.gif";
$WT_IMAGES["head"]["button"] = "buttons/head.gif";
$WT_IMAGES["indi"]["button"] = "buttons/indi.gif";
$WT_IMAGES["keyboard"]["button"] = "buttons/keyboard.gif";
$WT_IMAGES["media"]["button"] = "buttons/media.gif";
$WT_IMAGES["note"]["button"] = "buttons/note.gif";
$WT_IMAGES["place"]["button"] = "buttons/place.gif";
$WT_IMAGES["refresh"]["button"] = "buttons/refresh.gif";
$WT_IMAGES["repository"]["button"] = "buttons/repository.gif";
$WT_IMAGES["source"]["button"] = "buttons/source.gif";
$WT_IMAGES["note"]["button"] = "buttons/note.gif";
$WT_IMAGES["head"]["button"] = "buttons/head.gif";
$WT_IMAGES["find_facts"]["button"] = "buttons/find_facts.png";

// Media images
$WT_IMAGES["media"]["audio"] = "media/audio.png";
$WT_IMAGES["media"]["doc"] = "media/doc.gif";
$WT_IMAGES["media"]["flash"] = "media/flash.png";
$WT_IMAGES["media"]["flashrem"] = "media/flashrem.png";
$WT_IMAGES["media"]["ged"] = "media/ged.gif";
$WT_IMAGES["media"]["globe"] = "media/globe.png";
$WT_IMAGES["media"]["html"] = "media/html.gif";
$WT_IMAGES["media"]["picasa"] = "media/picasa.png";
$WT_IMAGES["media"]["pdf"] = "media/pdf.gif";
$WT_IMAGES["media"]["tex"] = "media/tex.gif";
$WT_IMAGES["media"]["wmv"] = "media/wmv.png";
$WT_IMAGES["media"]["wmvrem"] = "media/wmvrem.png";

//- other images
$WT_IMAGES["add"]["other"] = "add.gif";
$WT_IMAGES["darrow"]["other"] = "darrow.gif";
$WT_IMAGES["darrow2"]["other"] = "darrow2.gif";
$WT_IMAGES["ddarrow"]["other"] = "ddarrow.gif";
$WT_IMAGES["dline"]["other"] = "dline.gif";
$WT_IMAGES["dline2"]["other"] = "dline2.gif";
$WT_IMAGES["webtrees"]["other"] = "webtrees.png";
$WT_IMAGES["hline"]["other"] = "hline.gif";
$WT_IMAGES["larrow"]["other"] = "larrow.gif";
$WT_IMAGES["larrow2"]["other"] = "larrow2.gif";
$WT_IMAGES["ldarrow"]["other"] = "ldarrow.gif";
$WT_IMAGES["minus"]["other"] = "minus.gif";
$WT_IMAGES["note"]["other"] = "notes.gif";
$WT_IMAGES["plus"]["other"] = "plus.gif";
$WT_IMAGES["rarrow"]["other"] = "rarrow.gif";
$WT_IMAGES["rarrow2"]["other"] = "rarrow2.gif";
$WT_IMAGES["rdarrow"]["other"] = "rdarrow.gif";
$WT_IMAGES["remove"]["other"] = "remove.gif";
$WT_IMAGES["spacer"]["other"] = "spacer.gif";
$WT_IMAGES["uarrow"]["other"] = "uarrow.gif";
$WT_IMAGES["uarrow2"]["other"] = "uarrow2.gif";
$WT_IMAGES["uarrow3"]["other"] = "uarrow3.gif";
$WT_IMAGES["udarrow"]["other"] = "udarrow.gif";
$WT_IMAGES["vline"]["other"] = "vline.gif";
$WT_IMAGES["zoomin"]["other"] = "zoomin.gif";
$WT_IMAGES["zoomout"]["other"] = "zoomout.gif";
$WT_IMAGES["stop"]["other"] = "stop.gif";
$WT_IMAGES["pin-out"]["other"] = "pin-out.png";
$WT_IMAGES["pin-in"]["other"] = "pin-in.png";
$WT_IMAGES["default_image_M"]["other"] = "silhouette_male.gif";
$WT_IMAGES["default_image_F"]["other"] = "silhouette_female.gif";
$WT_IMAGES["default_image_U"]["other"] = "silhouette_unknown.gif";
$WT_IMAGES['slide_open']['other'] = "open.png";
$WT_IMAGES['slide_close']['other'] = "close.png";

// - lifespan chart arrows
$WT_IMAGES["lsltarrow"]["other"] = "lsltarrow.gif";
$WT_IMAGES["lsrtarrow"]["other"] = "lsrtarrow.gif";
$WT_IMAGES["lsdnarrow"]["other"] = "lsdnarrow.gif";
$WT_IMAGES["lsuparrow"]["other"] = "lsuparrow.gif";

//-- Variables for the Fan chart
$fanChart = array(
	'font'		=> WT_ROOT.'includes/fonts/DejaVuSans.ttf',
	'size'		=> '7px',
	'color'		=> '#000000',
	'bgColor'	=> '#eeeeee',
	'bgMColor'	=> '#b1cff0',
	'bgFColor'	=> '#e9daf1'
);

//-- This section defines variables for the pedigree chart
$bwidth = 225;		// -- width of boxes on pedigree chart
$bheight = 78;		// -- height of boxes on pedigree chart
$baseyoffset = -20;	// -- position the entire pedigree tree relative to the top of the page
$basexoffset = 10;	// -- position the entire pedigree tree relative to the left of the page
$bxspacing = 4;		// -- horizontal spacing between boxes on the pedigree chart
$byspacing = 5;	// -- vertical spacing between boxes on the pedigree chart
$brborder = 1;		// -- box right border thickness

// -- global variables for the descendancy chart
$Dbaseyoffset = 20;	// -- position the entire descendancy tree relative to the top of the page
$Dbasexoffset = 20;	// -- position the entire descendancy tree relative to the left of the page
$Dbxspacing = 0;	// -- horizontal spacing between boxes
$Dbyspacing = 10;	// -- vertical spacing between boxes
$Dbwidth = 250;		// -- width of DIV layer boxes
$Dbheight = 78;		// -- height of DIV layer boxes
$Dindent = 15;		// -- width to indent descendancy boxes
$Darrowwidth = 30;	// -- additional width to include for the up arrows

$CHARTS_CLOSE_HTML = true;                //-- should the charts, pedigree, descendacy, etc close the HTML on the page

// --  The largest possible area for charts is 300,000 pixels. As the maximum height or width is 1000 pixels
$WT_STATS_S_CHART_X = "440";
$WT_STATS_S_CHART_Y = "125";
$WT_STATS_L_CHART_X = "900";
// --  For map charts, the maximum size is 440 pixels wide by 220 pixels high
$WT_STATS_MAP_X = "440";
$WT_STATS_MAP_Y = "220";

$WT_STATS_CHART_COLOR1 = "ffffff";
$WT_STATS_CHART_COLOR2 = "95b8e0";
$WT_STATS_CHART_COLOR3 = "c8e7ff";

// Arrow symbol or icon for up-page links on Help pages
// This icon is referred to in Help text by: #GLOBALS[UpArrow]#
if (file_exists($WT_IMAGE_DIR."/uarrow3.gif")) $UpArrow = "<img src=\"{$WT_IMAGE_DIR}/uarrow3.gif\" class=\"icon\" border=\"0\" alt=\"^\" />";
else $UpArrow = "<b>^^&nbsp;&nbsp;</b>";

?>