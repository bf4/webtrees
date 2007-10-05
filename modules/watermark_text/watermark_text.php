<?php
/**
 * adds watermark to images
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
 
// determine which config file to use
// return the path to the config file and the time it was last updated
function getWatermarkConfigInfo() {
	global $GEDCOMS, $GEDCOM;

	// first look for a gedcom-specific file
	$configfile = 'modules/watermark_text/'.$GEDCOMS[$GEDCOM]['gedcom'].'_conf.php';
	$configlastupdate = 0;
	if (file_exists($configfile)) {
		$configlastupdate = filemtime($configfile);
		return array ($configfile, $configlastupdate);
	} else {
		$configfile = '';  
	}

	// now look for a generic file
	$configfile = 'modules/watermark_text/config.php';
	if (file_exists($configfile)) {
		$configlastupdate = filemtime($configfile);
	} else {
		$configfile = '';  
	}
	return array ($configfile, $configlastupdate);
}



// the media firewall passes in an image and a path to the config file
// this function can manipulate the image however it wants
// before returning it back to the media firewall
function applyWatermark($im, $configfile) {

	// load configuration variables
	// note, this file was determine by getConfigInfo
	// we pass it in here to avoid calling file_exists a couple more times 
	if ($configfile) {
		include $configfile;
	}

	// these values should be set in config.php. if not, set some defaults.  
	if (!isset($word1_text))		$word1_text   = "Sample Text";
	if (!isset($word1_maxsize))	$word1_maxsize = 90;
	if (!isset($word1_color))		$word1_color  = "0, 0, 0";
	if (!isset($word1_font))		$word1_font   = "";
	if (!isset($word1_vpos))		$word1_vpos   = "middle";
	if (!isset($word1_hpos))		$word1_hpos   = "left";

	if (!isset($word2_text))		$word2_text   = "Sample Text";
	if (!isset($word2_maxsize))	$word2_maxsize = 30;
	if (!isset($word2_color))		$word2_color  = "0, 0, 0";
	if (!isset($word2_font))		$word2_font   = "";
	if (!isset($word2_vpos))		$word2_vpos   = "top";
	if (!isset($word2_hpos))		$word2_hpos   = "left";

	embedText($im, $word1_text, $word1_maxsize, $word1_color, $word1_font, $word1_vpos, $word1_hpos);
	embedText($im, $word2_text, $word2_maxsize, $word2_color, $word2_font, $word2_vpos, $word2_hpos);

	return ($im);
}
 
function embedText($im, $text, $maxsize, $color, $font, $vpos, $hpos) {

	// there are two ways to embed text with PHP
	// (preferred) using GD and FreeType you can embed text using any True Type font
	// (fall back) if that is not available, you can insert basic monospaced text

	// determine whether TTF is available
	$useTTF = (function_exists("imagettftext")) ? true : false;
	if (!isset($font)||($font=='')||!file_exists('modules/watermark_text/'.$font)) $useTTF = false;

	# no errors if an invalid color string was passed in, just strange colors 
	$col=explode(",", $color);
	$textcolor = @imagecolorallocate($im, $col[0],$col[1],$col[2]);

	// paranoia is good!  make sure all variables have a value
	if (!isset($vpos) || ($vpos!="top" && $vpos!="middle" && $vpos!="bottom" && $vpos!="across")) $vpos = "middle";
	if (($vpos="across") && (!isset($hpos) || ($hpos!="left" && $hpos!="right" && $hpos!="top2bottom" && $hpos!="bottom2top"))) $hpos = "left";

	// make adjustments to settings that imagestring and imagestringup can't handle 
	if (!$useTTF) {
		// imagestringup only writes up, can't use top2bottom
		if ($hpos=="top2bottom") $hpos = "bottom2top";  
	}

	$height = imagesy($im);
	$width  = imagesx($im);
	$calc_angle=rad2deg(atan($height/$width));
	$hypoth=$height/sin(deg2rad($calc_angle));

	// vertical and horizontal position of the text
	switch ($vpos) {
		case "top":
			$taille=textlength($maxsize,$width,$text);
			$pos_y=$height*0.15+$taille;
			$pos_x=$width*0.15;
			$rotation=0;
			break;
		case "middle":
			$taille=textlength($maxsize,$width,$text);
			$pos_y=($height+$taille)/2;
			$pos_x=$width*0.15;
			$rotation=0;
			break;			
		case "bottom":
			$taille=textlength($maxsize,$width,$text);
			$pos_y=($height*.85-$taille);
			$pos_x=$width*0.15;
			$rotation=0;
			break;
		case "across": 
			switch ($hpos) {
				case "left":
				$taille=textlength($maxsize,$hypoth,$text);
				$pos_y=($height*.85-$taille);
				$taille_text=($taille-2)*(strlen(stripslashes($text)));
				$pos_x=$width*0.15;
				$rotation=$calc_angle;
				break;
				case "right":
				$taille=textlength($maxsize,$hypoth,$text);
				$pos_y=($height*.15-$taille);
				$pos_x=$width*0.85;
				$rotation=$calc_angle+180;
				break;
				case "top2bottom":
				$taille=textlength($maxsize,$height,$text);
				$pos_y=($height*.15-$taille);
				$pos_x=($width*.90-$taille);
				$rotation=-90;
				break;
				case "bottom2top":
				$taille=textlength($maxsize,$height,$text);
				$pos_y = $height*0.85;
				$pos_x = $width*0.15;
				$rotation=90;
				break;			
			}
			break;
		default:
	}

	// apply the text
	if ($useTTF) {
		imagettftext($im, $taille, $rotation, $pos_x, $pos_y, $textcolor, $font, stripslashes($text));
	} else {
		if ($rotation!=90) {
			imagestring($im, 5, $pos_x, $pos_y, stripslashes($text),$textcolor);
		} else {
			imagestringup($im, 5, $pos_x, $pos_y, stripslashes($text),$textcolor);
		}
	}

}

function textlength($t,$mxl,$text) {
	$taille_c = $t;
	while (($taille_c-2)*(strlen(stripslashes($text))) > $mxl) {
		$taille_c--;
		if ($taille_c == 2) break;
	}
	return ($taille_c);
}

?>