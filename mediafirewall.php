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
 * @version $Id: mediafirewall.php 1521 2007-08-28 06:14:21Z ljm $
 */

require_once("includes/controllers/media_ctrl.php");

if (file_exists("modules/watermark_text/watermark_text.php")) include ("modules/watermark_text/watermark_text.php");

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
$configlastupdate = 0;
$configfile = "";
$generatewatermark = false;

if ($usewatermark) {
	// determine when the config settings were last updated
	// getWatermarkConfigInfo() is defined in the watermark_text module
	list($configfile, $configlastupdate) = getWatermarkConfigInfo();
	$watermarkfile = getWatermarkPath($serverFilename);
	if (!file_exists($watermarkfile)) {
		// no saved watermark file exists
		// generate the watermark file
		$generatewatermark = true;
	} else {
		$watermarktime = filemtime($watermarkfile);  
		if ( ($configlastupdate > $watermarktime) || ( $filetime > $watermarktime) ) {
			// if config settings were updated after the saved file was created
			// or if the original image was updated after the saved file was created
			// generate the watermark file
			$generatewatermark = true;
		}
	}
}

$mimetype = $controller->mediaobject->getMimetype();

// setup the etag.  use enough info so that if anything important changes, the etag won't match
$etag_string = basename($serverFilename).$filetime.getUserAccessLevel().$SHOW_NO_WATERMARK.$configlastupdate; 
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

	// applyWatermark() is defined in the watermark_text module
	$im = applyWatermark($im, $configfile);

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