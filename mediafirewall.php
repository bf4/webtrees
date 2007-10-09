<?php
/**
 * Media Firewall
 * Called when a 404 error occurs in the media directory
 * Serves images from the index directory
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005	John Finlay and Others
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
 *
 * @package PhpGedView
 * @version $Id$
 */

require_once("includes/controllers/media_ctrl.php");

$debug_mediafirewall	= 0; // set to 1 if you want to see media firewall values displayed instead of images
$debug_watermark		= 0; // set to 1 if you want to see error messages from the watermark module instead of broken images
$debug_forceImageRegen	= 0; // set to 1 if you want to force an image to be regenerated (for debugging only)

// pass in an image type and this will determine if your system supports editing of that image type 
function isImageTypeSupported($reqtype) {
	$reqtype = strtolower($reqtype);
	if ( ( ($reqtype == 'jpg') || ($reqtype == 'jpeg') ) && (imagetypes() & IMG_JPG)) {
		return ('jpeg');
	} else if (($reqtype == 'gif') && (imagetypes() & IMG_GIF)) {
		return ('gif');
	} else if (($reqtype == 'png') && (imagetypes() & IMG_PNG)) {
		return ('png');
	} else if ( ( ($reqtype == 'wbmp') || ($reqtype == 'bmp') ) && (imagetypes() & IMG_WBMP)) {
		return ('wbmp');
	} else {
		return false;
	}
}

// pass in an image type and an error message
// if the image type is supported:
//   creates an image, adds the text, sends headers, outputs the image and exits the script
// if the image type is not supported:
//   sends html version of error message and exits the script
// basic idea from http://us.php.net/manual/en/function.imagecreatefromjpeg.php
function sendErrorAndExit($type, $line1, $line2 = false) {

	// line2 contains the information that only an admin should see, such as the full path to a file
	if(!userIsAdmin(getUserName())) {  	 
		$line2 = false;
	}

	$type = isImageTypeSupported($type);
	if ( $type ){
		// figure out how long the errr message is
		$numchars = strlen($line1);
		if ($line2 && (strlen($line2) > $numchars)) {
			$numchars = strlen($line2);
		}
		// set an arbitrary max length
		if ($numchars > 100) $numchars = 100;

		// width of image is based on the number of characters
		$width = $numchars * 6.5;
		$height = 60;

		$im  = imagecreatetruecolor($width, $height);  /* Create a black image */
		$bgc = imagecolorallocate($im, 255, 255, 255); /* set background color */
		$tc  = imagecolorallocate($im, 0, 0, 0);       /* set text color */
		imagefilledrectangle($im, 2, 2, $width-4, $height-4, $bgc); /* create a rectangle, leaving 2 px border */
		imagestring($im, 2, 5, 5, $line1, $tc);
		if ($line2) {
			imagestring($im, 2, 5, 30, $line2, $tc);
		}

		// Note: any error status (such as 404) is still in effect 
		header('Content-Type: image/'.$type);
		$imSendFunc = 'image'.$type;
		$imSendFunc($im);
		imagedestroy($im);
	} else {
		// output a standard html string
		// Note: any error status (such as 404) is still in effect 
		echo '<!-- filler space so IE will display the custom 404 error -->';
		echo '<!-- filler space so IE will display the custom 404 error -->';
		echo '<!-- filler space so IE will display the custom 404 error -->';
		echo '<!-- filler space so IE will display the custom 404 error -->';
		echo '<!-- filler space so IE will display the custom 404 error -->';
		echo '<!-- filler space so IE will display the custom 404 error -->';
		echo '<!-- filler space so IE will display the custom 404 error -->';
		echo '<!-- filler space so IE will display the custom 404 error -->';
		echo "<div align=\"center\">".$line1."</div>";
		if ($line2) {
			echo "<div align=\"center\">".$line2."</div>";
		}
	}
	exit;
}

// pass in the complete serverpath to an image
// this returns the complete serverpath to be used by the saved watermarked image
// note that each gedcom gets a unique path to store images, this allows each gedcom to have their own watermarking config
function getWatermarkPath ($path) {
	global $GEDCOMS, $GEDCOM, $MEDIA_DIRECTORY;
	$serverroot = get_media_firewall_path($MEDIA_DIRECTORY);
	$path = str_replace($serverroot, $serverroot . 'watermark/'.$GEDCOMS[$GEDCOM]['gedcom'].'/', $path);
	return $path;
}



// the media firewall passes in an image
// this function can manipulate the image however it wants
// before returning it back to the media firewall
function applyWatermark($im) {
	global $GEDCOMS, $GEDCOM;

	// in the future these options will be set in the gedcom configuration area  

	// text to watermark with 
	$word1_text   = $GEDCOMS[$GEDCOM]["title"];
	// maximum font size for "word1" ; will be automaticaly reduced to fit in the image
	$word1_maxsize = 100;
	// rgb color codes for text
	$word1_color  = "0, 0, 0";
	// ttf font file to use. must exist in the includes/fonts/ directory
	$word1_font   = "";
	// vertical position for the text to past; possible values are: top, middle or bottom, across
	$word1_vpos   = "across";
	// horizontal position for the text to past in media file; possible values are: left, right, top2bottom, bottom2top 
	// this value is used only if $word1_vpos=across
	$word1_hpos   = "left";

	$word2_text   = $_SERVER["HTTP_HOST"];
	$word2_maxsize = 20;
	$word2_color  = "0, 0, 0";
	$word2_font   = "";
	$word2_vpos   = "top";
	$word2_hpos   = "top2bottom";

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
	if ($useTTF) {
		if (!isset($font)||($font=='')||!file_exists('includes/fonts/'.$font)) {
	  	$font = 'DejaVuSans.ttf';
			if (!file_exists('includes/fonts/'.$font)) {
			  $useTTF = false;
	    }
	  }
	} 
  
	# no errors if an invalid color string was passed in, just strange colors 
	$col=explode(",", $color);
	$textcolor = @imagecolorallocate($im, $col[0],$col[1],$col[2]);

	// paranoia is good!  make sure all variables have a value
	if (!isset($vpos) || ($vpos!="top" && $vpos!="middle" && $vpos!="bottom" && $vpos!="across")) $vpos = "middle";
	if (($vpos=="across") && (!isset($hpos) || ($hpos!="left" && $hpos!="right" && $hpos!="top2bottom" && $hpos!="bottom2top"))) $hpos = "left";

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
		imagettftext($im, $taille, $rotation, $pos_x, $pos_y, $textcolor, 'includes/fonts/'.$font, stripslashes($text));
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



// ******************************************************
// start processing here

// get serverfilename from the media controller
$serverFilename = $controller->getServerFilename();
$isThumb = false;

if (strpos($_SERVER['REDIRECT_URL'], '/thumbs/')) {
	// the user requested a thumbnail, but the $controller only knows how to lookup information on the main file 
	// display the thumbnail file instead of the main file
	// NOTE: since this script was called when a 404 error occured, we know the requested file
	// does not exist in the main media directory.  just check the media firewall directory
	$serverFilename = get_media_firewall_path($controller->mediaobject->getThumbnail(false));
	$isThumb = true;
}

if (!file_exists($serverFilename)) {
	// the requested file MAY be in the gedcom, but it does NOT exist on the server.  bail.
	// Note: the 404 error status is still in effect. 
	if (!$debug_mediafirewall) sendErrorAndExit($controller->mediaobject->getFiletype(), $pgv_lang["no_media"], $serverFilename);
}

if (empty($controller->pid)) {
	// the requested file IS NOT in the gedcom, but it exists (the check for fileExists was above) 
	if (!userIsAdmin(getUserName()) ) {
		// only show these files to admin users
		// bail since current user is not admin
		// Note: the 404 error status is still in effect. 
		if (!$debug_mediafirewall) sendErrorAndExit($controller->mediaobject->getFiletype(), $pgv_lang["media_privacy"], $serverFilename);
	}
}

// check PGV permissions
if (!$controller->mediaobject->canDisplayDetails()) {
	// if no permissions, bail
	// Note: the 404 error status is still in effect 
	if (!$debug_mediafirewall) sendErrorAndExit($controller->mediaobject->getFiletype(), $pgv_lang["media_privacy"]);
}

$protocol = $_SERVER["SERVER_PROTOCOL"];  // determine if we are using HTTP/1.0 or HTTP/1.1
$filetime = @filemtime($serverFilename);
$filetimeHeader = gmdate("D, d M Y H:i:s", $filetime).' GMT';
$expireOffset = 3600 * 24;  // tell browser to cache this image for 24 hours
$expireHeader = gmdate("D, d M Y H:i:s", time() + $expireOffset) . " GMT";

$type = isImageTypeSupported($controller->mediaobject->getFiletype());
$usewatermark = false;
// if this image supports watermarks and the watermark module is intalled...
if ($type && function_exists("applyWatermark")) {
	// if this is not a thumbnail, or WATERMARK_THUMB is true
	if (!$isThumb || $WATERMARK_THUMB ) {
		// if the user's priv's justify it...
		if (getUserAccessLevel() > $SHOW_NO_WATERMARK ) {
			// add a watermark
			$usewatermark = true;
		}
	}
}

$watermarkfile = "";
$generatewatermark = false;

if ($usewatermark) {
	$watermarkfile = getWatermarkPath($serverFilename);
	if (!file_exists($watermarkfile) || $debug_forceImageRegen) {
		// no saved watermark file exists
		// generate the watermark file
		$generatewatermark = true;
	} else {
		$watermarktime = filemtime($watermarkfile);  
		if ($filetime > $watermarktime) {
			// if the original image was updated after the saved file was created
			// generate the watermark file
			$generatewatermark = true;
		}
	}
}

$mimetype = $controller->mediaobject->getMimetype();

// setup the etag.  use enough info so that if anything important changes, the etag won't match
$etag_string = basename($serverFilename).$filetime.getUserAccessLevel().$SHOW_NO_WATERMARK; 
$etag = dechex(crc32($etag_string));

// parse IF_MODIFIED_SINCE header from client
$if_modified_since = 'x';
if (@$_SERVER["HTTP_IF_MODIFIED_SINCE"]) { 
	$if_modified_since = preg_replace('/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"]);
} 

// parse IF_NONE_MATCH header from client
$if_none_match = 'x';
if (@$_SERVER["HTTP_IF_NONE_MATCH"]) { 
	$if_none_match = str_replace('\"', '', $_SERVER["HTTP_IF_NONE_MATCH"]);
} 

if ($debug_mediafirewall) {
	// this is for debugging the media firewall
	header("Last-Modified: " . $filetimeHeader);
	header('ETag: "'.$etag.'"');

	echo  '<table border="1">';
	echo  '<tr><td>$controller->pid</td><td>'.$controller->pid.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>Requested URL</td><td>'.$_SERVER['REDIRECT_URL'].'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>serverFilename</td><td>'.$serverFilename.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>controller->mediaobject->getFilename()</td><td>'.$controller->mediaobject->getFilename().'</td><td>this is direct from the gedcom</td></tr>';
	echo  '<tr><td>controller->mediaobject->getServerFilename()</td><td>'.$controller->mediaobject->getServerFilename().'</td><td></td></tr>';
	echo  '<tr><td>controller->mediaobject->fileExists()</td><td>'.$controller->mediaobject->fileExists().'</td><td></td></tr>';
	echo  '<tr><td>controller->mediaobject->getFiletype()</td><td>'.$controller->mediaobject->getFiletype().'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>mimetype</td><td>'.$mimetype.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>controller->mediaobject->getFilesize()</td><td>'.$controller->mediaobject->getFilesize().'</td><td>cannot use this</td></tr>';
	echo  '<tr><td>filesize</td><td>'.@filesize($serverFilename).'</td><td>this is right</td></tr>';
	echo  '<tr><td>controller->mediaobject->getThumbnail()</td><td>'.$controller->mediaobject->getThumbnail().'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>controller->mediaobject->canDisplayDetails()</td><td>'.$controller->mediaobject->canDisplayDetails().'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>controller->mediaobject->getTitle()</td><td>'.$controller->mediaobject->getTitle().'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>basename($serverFilename)</td><td>'.basename($serverFilename).'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>filetime</td><td>'.$filetime.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>filetimeHeader</td><td>'.$filetimeHeader.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>if_modified_since</td><td>'.$if_modified_since.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>if_none_match</td><td>'.$if_none_match.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>etag</td><td>'.$etag.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>etag_string</td><td>'.$etag_string.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>expireHeader</td><td>'.$expireHeader.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>protocol</td><td>'.$protocol.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>SHOW_NO_WATERMARK</td><td>'.$SHOW_NO_WATERMARK.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>getUserAccessLevel()</td><td>'.getUserAccessLevel().'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>usewatermark</td><td>'.$usewatermark.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>generatewatermark</td><td>'.$generatewatermark.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>watermarkfile</td><td>'.$watermarkfile.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>type</td><td>'.$type.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>WATERMARK_THUMB</td><td>'.$WATERMARK_THUMB.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>SAVE_WATERMARK_THUMB</td><td>'.$SAVE_WATERMARK_THUMB.'</td><td>&nbsp;</td></tr>';
	echo  '<tr><td>SAVE_WATERMARK_IMAGE</td><td>'.$SAVE_WATERMARK_IMAGE.'</td><td>&nbsp;</td></tr>';
	echo  '</table>';

	echo '<pre>';
	print_r (@getimagesize($serverFilename));
	print_r ($controller->mediaobject);
	print_r ($GEDCOMS[$GEDCOM]);
	echo '</pre>';

	phpinfo();
	exit;
}
// do the real work here

// add caching headers.  allow browser to cache file, but not proxy
if (!$debug_forceImageRegen) {
	header("Last-Modified: " . $filetimeHeader);
	header('ETag: "'.$etag.'"');
	header("Expires: ".$expireHeader);
	header("Cache-Control: max-age=".$expireOffset.", s-maxage=0, proxy-revalidate");
}

// if this file is already in the user's cache, don't resend it
// first check if the if_modified_since param matches
if (($if_modified_since == $filetimeHeader) && !$debug_forceImageRegen) {
	// then check if the etag matches
	if ($if_none_match == $etag) {
		header($protocol." 304 Not Modified");
		exit;
	}
}

// reset the 404 error
header($protocol." 200 OK");
header("Status: 200 OK");

// send headers for the image
if (!$debug_watermark) {
	header("Content-Type: " . $mimetype);
	header('Content-Disposition: inline; filename="'.basename($serverFilename).'"');
}

if ( $generatewatermark ) {
	// generate the watermarked image
	$imCreateFunc = 'imagecreatefrom'.$type;
	$im = @$imCreateFunc($serverFilename);

	if (!$im) {
		// this image is defective.  bail 
		sendErrorAndExit($controller->mediaobject->getFiletype(), $pgv_lang["media_broken"], $serverFilename);  
	}

	$im = applyWatermark($im);

	$imSendFunc = 'image'.$type;
	// save the image, if preferences allow
	if ( ($isThumb && $SAVE_WATERMARK_THUMB) || (!$isThumb && $SAVE_WATERMARK_IMAGE) ) {
		// make sure the directory exists
		if (!is_dir(dirname($watermarkfile))) {
			mkdirs(dirname($watermarkfile));
		}
		// save the image
		$imSendFunc($im, $watermarkfile);
	}

	// send the image
	$imSendFunc($im);
	imagedestroy($im);
	exit;

} else {

	if ( $usewatermark ) {
		// the stored image is good, lets use it
		$serverFilename = $watermarkfile;
	}

	// determine filesize of image (could be original or watermarked version) 
	$filesize = filesize($serverFilename);

	// set one more header
	header("Content-Length: " . $filesize);

	// open the file and send it
	$fp = fopen($serverFilename, 'rb');
	fpassthru($fp);
	exit;
}

?>