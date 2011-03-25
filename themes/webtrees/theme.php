<?php
/**
 * Standard theme
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
  * PNG Icons By:Alessandro Rei; License: GPL; http://www.kde-look.org/content/show.php/Dark-Glass+reviewed?content=67902
 *
 * @package webtrees
 * @subpackage Themes
 * @version $Id$
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$theme_name = "webtrees"; // need double quotes, as file is scanned/parsed by script
$stylesheet       = WT_THEME_DIR.'style.css';
$print_stylesheet = WT_THEME_DIR.'print.css';
$headerfile       = WT_THEME_DIR.'header.php';
$footerfile       = WT_THEME_DIR.'footer.php';
$WT_USE_HELPIMG   = true;
$WT_MENU_LOCATION = 'top';

//- main icons
$WT_IMAGES=array(
	'admin'=>WT_THEME_DIR.'images/admin.png',
	'ancestry'=>WT_THEME_DIR.'images/ancestry.png',
	'calendar'=>WT_THEME_DIR.'images/calendar.png',
	'cfamily'=> WT_THEME_DIR.'images/family.png',
	'charts'=>WT_THEME_DIR.'images/charts.png',
	'childless'=>WT_THEME_DIR.'images/childless.png',
	'clippings'=>WT_THEME_DIR.'images/clippings.png',
	'descendant'=>WT_THEME_DIR.'images/descendancy.png',
	'edit_fam'=>WT_THEME_DIR.'images/edit_fam.png',
	'edit_indi'=>WT_THEME_DIR.'images/edit_indi.png',
	'edit_media'=>WT_THEME_DIR.'images/edit_media.png',
	'edit_note'=>WT_THEME_DIR.'images/edit_note.png',
	'edit_repo'=>WT_THEME_DIR.'images/edit_repo.png',
	'edit_sour'=>WT_THEME_DIR.'images/edit_source.png',
	'fambook'=>WT_THEME_DIR.'images/source.png',
	'fanchart'=>WT_THEME_DIR.'images/fanchart.png',
	'favorites'=>WT_THEME_DIR.'images/favorites.png',
	'gedcom'=>WT_THEME_DIR.'images/tree.png',
	'help'=>WT_THEME_DIR.'images/help2.png',
	'home'=>WT_THEME_DIR.'images/home.png',
	'hourglass'=>WT_THEME_DIR.'images/hourglass.png',
	'indis'=>WT_THEME_DIR.'images/indis.png',
	'lists'=>WT_THEME_DIR.'images/lists.png',
	'media'=>WT_THEME_DIR.'images/media.png',
	'menu_help'=>WT_THEME_DIR.'images/help.png',
	'menu_media'=>WT_THEME_DIR.'images/media.png',
	'menu_note'=>WT_THEME_DIR.'images/notes.png',
	'menu_repository'=>WT_THEME_DIR.'images/repository.png',
	'menu_source'=>WT_THEME_DIR.'images/source.png',
	'mypage'=>WT_THEME_DIR.'images/mypage.png',
	'notes'=>WT_THEME_DIR.'images/notes.png',
	'patriarch'=>WT_THEME_DIR.'images/patriarch.png',
	'pedigree'=>WT_THEME_DIR.'images/pedigree.png',
	'place'=>WT_THEME_DIR.'images/place.png',
	'relationship'=>WT_THEME_DIR.'images/relationship.png',
	'reports'=>WT_THEME_DIR.'images/reports.png',
	'repository'=>WT_THEME_DIR.'images/repository.png',
	'rings'=>WT_THEME_DIR.'images/rings.png',
	'search'=>WT_THEME_DIR.'images/search.png',
	'selected'=>WT_THEME_DIR.'images/selected.png',
	'sex_f_15x15'=>WT_THEME_DIR.'images/sex_f_15x15.png',
	'sex_f_9x9'=>WT_THEME_DIR.'images/sex_f_9x9.png',
	'sex_m_15x15'=>WT_THEME_DIR.'images/sex_m_15x15.png',
	'sex_m_9x9'=>WT_THEME_DIR.'images/sex_m_9x9.png',
	'sex_u_15x15'=>WT_THEME_DIR.'images/sex_u_15x15.png',
	'sex_u_9x9'=>WT_THEME_DIR.'images/sex_u_9x9.png',
	'sfamily'=>WT_THEME_DIR.'images/family.png',
	'source'=>WT_THEME_DIR.'images/source.png',
	'statistic'=>WT_THEME_DIR.'images/statistic.png',
	'target'=>WT_THEME_DIR.'images/buttons/target.png',
	'timeline'=>WT_THEME_DIR.'images/timeline.png',
	'tree'=>WT_THEME_DIR.'images/tree.png',
	'warning'=>WT_THEME_DIR.'images/warning.png',
	'wiki'=>WT_THEME_DIR.'images/w_22.png',

	//- buttons for data entry pages
	'button_addmedia'=>WT_THEME_DIR.'images/buttons/addmedia.png',
	'button_addnote'=>WT_THEME_DIR.'images/buttons/addnote.png',
	'button_addrepository'=>WT_THEME_DIR.'images/buttons/addrepository.png',
	'button_addsource'=>WT_THEME_DIR.'images/buttons/addsource.png',
	'button_calendar'=>WT_THEME_DIR.'images/buttons/calendar.png',
	'button_family'=>WT_THEME_DIR.'images/buttons/family.png',
	'button_find_facts'=>WT_THEME_DIR.'images/buttons/find_facts.png',
	'button_head'=>WT_THEME_DIR.'images/buttons/head.png',
	'button_indi'=>WT_THEME_DIR.'images/buttons/indi.png',
	'button_keyboard'=>WT_THEME_DIR.'images/buttons/keyboard.png',
	'button_media'=>WT_THEME_DIR.'images/buttons/media.png',
	'button_note'=>WT_THEME_DIR.'images/buttons/note.png',
	'button_place'=>WT_THEME_DIR.'images/buttons/place.png',
	'button_repository'=>WT_THEME_DIR.'images/buttons/repository.png',
	'button_source'=>WT_THEME_DIR.'images/buttons/source.png',

	// media images
	'media_audio'=>WT_THEME_DIR.'images/media/audio.png',
	'media_doc'=>WT_THEME_DIR.'images/media/doc.png',
	'media_flash'=>WT_THEME_DIR.'images/media/flash.png',
	'media_flashrem'=>WT_THEME_DIR.'images/media/flashrem.png',
	'media_ged'=>WT_THEME_DIR.'images/media/unknown.png',
	'media_globe'=>WT_THEME_DIR.'images/media/www.png',
	'media_html'=>WT_THEME_DIR.'images/media/www.png',
	'media_picasa'=>WT_THEME_DIR.'images/media/picasa.png',
	'media_pdf'=>WT_THEME_DIR.'images/media/pdf.png',
	'media_tex'=>WT_THEME_DIR.'images/media/tex.png',
	'media_wmv'=>WT_THEME_DIR.'images/media/wmv.png',
	'media_wmvrem'=>WT_THEME_DIR.'images/media/wmvrem.png',

	//- other images
	'add'=>WT_THEME_DIR.'images/add.png',
	'darrow'=>WT_THEME_DIR.'images/darrow.png',
	'darrow2'=>WT_THEME_DIR.'images/darrow2.png',
	'ddarrow'=>WT_THEME_DIR.'images/ddarrow.png',
	'dline'=>WT_THEME_DIR.'images/dline.png',
	'dline2'=>WT_THEME_DIR.'images/dline2.png',
	'webtrees'=>WT_THEME_DIR.'images/webtrees_s.png',
	'hline'=>WT_THEME_DIR.'images/hline.png',
	'larrow'=>WT_THEME_DIR.'images/larrow.png',
	'larrow2'=>WT_THEME_DIR.'images/larrow2.png',
	'ldarrow'=>WT_THEME_DIR.'images/ldarrow.png',
	'minus'=>WT_THEME_DIR.'images/minus.png',
	'note'=>WT_THEME_DIR.'images/notes.png',
	'plus'=>WT_THEME_DIR.'images/plus.png',
	'rarrow'=>WT_THEME_DIR.'images/rarrow.png',
	'rarrow2'=>WT_THEME_DIR.'images/rarrow2.png',
	'rdarrow'=>WT_THEME_DIR.'images/rdarrow.png',
	'remove'=>WT_THEME_DIR.'images/remove.png',
	'spacer'=>WT_THEME_DIR.'images/spacer.png',
	'uarrow'=>WT_THEME_DIR.'images/uarrow.png',
	'uarrow2'=>WT_THEME_DIR.'images/uarrow2.png',
	'uarrow3'=>WT_THEME_DIR.'images/uarrow3.png',
	'udarrow'=>WT_THEME_DIR.'images/udarrow.png',
	'vline'=>WT_THEME_DIR.'images/vline.png',
	'zoomin'=>WT_THEME_DIR.'images/zoomin.png',
	'zoomout'=>WT_THEME_DIR.'images/zoomout.png',
	'stop'=>WT_THEME_DIR.'images/stop.png',
	'pin-out'=>WT_THEME_DIR.'images/pin-out.png',
	'pin-in'=>WT_THEME_DIR.'images/pin-in.png',
	'default_image_M'=>WT_THEME_DIR.'images/silhouette_male.png',
	'default_image_F'=>WT_THEME_DIR.'images/silhouette_female.png',
	'default_image_U'=>WT_THEME_DIR.'images/silhouette_unknown.png',
	'slide_open'=>WT_THEME_DIR.'images/open.png',
	'slide_close'=>WT_THEME_DIR.'images/close.png',
	'reminder'=>WT_THEME_DIR.'images/reminder.png',
	'children'=>WT_THEME_DIR.'images/children.png',

	// - lifespan chart arrows
	'lsltarrow'=>WT_THEME_DIR.'images/lifespan-left.png',
	'lsrtarrow'=>WT_THEME_DIR.'images/lifespan-right.png',
	'lsdnarrow'=>WT_THEME_DIR.'images/lifespan-down.png',
	'lsuparrow'=>WT_THEME_DIR.'images/lifespan-up.png',
);

//-- variables for the fan chart
$fanChart = array(
	'font'    =>WT_ROOT.'includes/fonts/DejaVuSans.ttf',
	'size'    =>'7px',
	'color'   =>'#000000',
	'bgColor' =>'#eeeeee',
	'bgMColor'=>'#b1cff0',
	'bgFColor'=>'#e9daf1'
);

//-- pedigree chart variables
$bwidth=225;     // -- width of boxes on pedigree chart
$bheight=80;     // -- height of boxes on pedigree chart
$baseyoffset=10; // -- position the entire pedigree tree relative to the top of the page
$basexoffset=10; // -- position the entire pedigree tree relative to the left of the page
$bxspacing=0;    // -- horizontal spacing between boxes on the pedigree chart
$byspacing=5;    // -- vertical spacing between boxes on the pedigree chart
$brborder=1;     // -- box right border thickness

// -- descendancy chart variables
$Dbaseyoffset=0; // -- position the entire descendancy tree relative to the top of the page
$Dbasexoffset=0; // -- position the entire descendancy tree relative to the left of the page
$Dbxspacing=0;   // -- horizontal spacing between boxes
$Dbyspacing=1;   // -- vertical spacing between boxes
$Dbwidth=270;    // -- width of DIV layer boxes
$Dbheight=80;    // -- height of DIV layer boxes
$Dindent=15;     // -- width to indent descendancy boxes
$Darrowwidth=15; // -- additional width to include for the up arrows

$CHARTS_CLOSE_HTML=true; //-- should the charts, pedigree, descendacy, etc close the HTML on the page

// --  The largest possible area for charts is 300,000 pixels. As the maximum height or width is 1000 pixels
$WT_STATS_S_CHART_X=440;
$WT_STATS_S_CHART_Y=125;
$WT_STATS_L_CHART_X=900;
// --  For map charts, the maximum size is 440 pixels wide by 220 pixels high
$WT_STATS_MAP_X=440;
$WT_STATS_MAP_Y=220;

$WT_STATS_CHART_COLOR1="ffffff";
$WT_STATS_CHART_COLOR2="9ca3d4";
$WT_STATS_CHART_COLOR3="e5e6ef";

// Arrow symbol or icon for up-page links on Help pages
$UpArrow = "<img src=\"".WT_THEME_DIR."images/uarrow3.png\" class=\"icon\" border=\"0\" alt=\"^\" />";
