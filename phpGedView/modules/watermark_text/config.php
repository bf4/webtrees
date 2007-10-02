<?php
/**
 * config.php for watermark_text module
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  PGV Development Team
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
 * @subpackage watermark_text
 * @version $Id$
 */

//====================
// FIRST INCLUSION
//====================

global $GEDCOMS, $GEDCOM;

//$word1_txt: text to past in media file: can be a PGV variable like $GEDCOMS[$GEDCOM]["title"]  or a text like "My inclusion number one" or a concatenation of both
// can be empty to disable this first inclusion
$word1_text		= $GEDCOMS[$GEDCOM]["title"];

//maximum font size for "word1" ; will be automaticaly reduced to fit in the image
$word1_maxsize	= 5000;

//rgb color codes for pasting text1
$word1_color	= "0, 0, 0";

//tt font used for pasting text1 with path to that font file
//this file must exist in the watermark_text directory in order to work
$word1_font		= "arialbi.ttf";

//$word1_vpos: vertical position for the text to past; possible values are: top, middle or bottom, across
$word1_vpos		= "across";

//$word1_hpos: horizontal position for the text to past in media file; possible values are: left, right, top2bottom, bottom2top 
//this value is used only if $word1_vpos=across
$word1_hpos		= "left";

//====================
// SECOND INCLUSION ()same syntax as first inclusion)
//====================

$word2_text		= $_SERVER["HTTP_HOST"];
$word2_maxsize	= 30;
$word2_color	= "255, 165, 96";
$word2_font		= "arialbi.ttf";
$word2_vpos		= "across";
$word2_hpos		= "top2bottom";

?>