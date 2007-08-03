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
 * @version $Id: $
 */

require_once("includes/controllers/media_ctrl.php");

$DEBUG = 0;

if (empty($controller->pid)) {
	// the requested file is not in the database
  // Note: the 404 error status is still in effect.  IE will not display the message below because it is too short. 
	echo "<div align=\"center\">".$pgv_lang["no_media"]."</div>";
  if (@$_SERVER['REDIRECT_URL']) {
		echo "<div align=\"center\">".$_SERVER['REDIRECT_URL']."</div>";
  }
  exit;
}

// if we got here, then the requested file was found in the database
// and $controller contains all the details about the media object
$serverFilename = $controller->getServerFilename();

if (strpos($_SERVER['REDIRECT_URL'], '/thumbs/')) {
  // the user requested a thumbnail, but the $controller only knows how to lookup information on the main file 
  // display the thumbnail file instead of the main file
  $serverFilename = $INDEX_DIRECTORY.$controller->mediaobject->getThumbnail();
}

if (!file_exists($serverFilename)) {
	// the requested file is in the database, but it does not exist on the server
  // Note: the 404 error status is still in effect.  IE will not display the message below because it is too short. 
	echo "<div align=\"center\">".$pgv_lang["no_media"]."</div>";
	echo "<div align=\"center\">".$serverFilename."</div>";
  exit;
}

$protocol = $_SERVER["SERVER_PROTOCOL"];  // determine if we are using HTTP/1.0 or HTTP/1.1

$filesize = filesize($serverFilename);
$filetime = filemtime($serverFilename);
$filetimeHeader = gmdate("D, d M Y H:i:s", $filetime).' GMT';

$expireOffset = 3600 * 24;  // tell browser to cache this for 24 hours
$expireHeader = gmdate("D, d M Y H:i:s", time() + $expireOffset) . " GMT";

$if_modified_since = 'x';
if (@$_SERVER["HTTP_IF_MODIFIED_SINCE"]) { 
  $if_modified_since = preg_replace('/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"]);
} 

if ($DEBUG) {
  // this is for debugging the media firewall
  echo  '<table border="1">';
  echo  '<tr><td>Requested URL</td><td>'.$_SERVER['REDIRECT_URL'].'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>serverFilename</td><td>'.$serverFilename.'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>controller->mediaobject->getFilename()</td><td>'.$controller->mediaobject->getFilename().'</td><td>this is direct from the gedcom</td></tr>';
  echo  '<tr><td>controller->mediaobject->getFiletype()</td><td>'.$controller->mediaobject->getFiletype().'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>controller->mediaobject->getContenttype()</td><td>'.$controller->mediaobject->getContenttype().'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>controller->mediaobject->getFilesize()</td><td>'.$controller->mediaobject->getFilesize().'</td><td>this is wrong</td></tr>';
  echo  '<tr><td>filesize</td><td>'.$filesize.'</td><td>this is right</td></tr>';
  echo  '<tr><td>controller->mediaobject->getThumbnail()</td><td>'.$controller->mediaobject->getThumbnail().'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>controller->mediaobject->canDisplayDetails()</td><td>'.$controller->mediaobject->canDisplayDetails().'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>controller->mediaobject->getTitle()</td><td>'.$controller->mediaobject->getTitle().'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>basename($serverFilename)</td><td>'.basename($serverFilename).'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>filetime</td><td>'.$filetime.'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>filetimeHeader</td><td>'.$filetimeHeader.'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>if_modified_since</td><td>'.$if_modified_since.'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>expireHeader</td><td>'.$expireHeader.'</td><td>&nbsp;</td></tr>';
  echo  '<tr><td>protocol</td><td>'.$protocol.'</td><td>&nbsp;</td></tr>';
  echo  '</table>';

  echo '<pre>';
  print_r ($controller->mediaobject);
  echo '</pre>';

  phpinfo();
  
} else {
  // do the real work here
    
  // check PGV permissions
  if (!$controller->mediaobject->canDisplayDetails()) {
    // if no permissions, bail
    // Note: the 404 error status is still in effect.  IE will not display the message below because it is too short. 
    echo "<div align=\"center\">".$pgv_lang["media_privacy"]."</div>";
    exit;
  }
  
  // add caching headers.  allow browser to cache file, but not proxy
  // see http://www.badpenguin.org/docs/php-cache.html
  header("Last-Modified: " . $filetimeHeader);
  header("Expires: ".$expireHeader);
  header("Cache-Control: max-age=".$expireOffset.", s-maxage=0, proxy-revalidate");

  // if this file is already in the user's cache, don't resend it
  // see http://ontosys.com/php/cache.html
  if ($if_modified_since == $filetimeHeader) {
    header($protocol." 304 Not Modified");
    exit;
  }
  
  // idea for future: embed text in the image
  // ideas here: https://sourceforge.net/tracker/index.php?func=detail&aid=1739602&group_id=55456&atid=477081

  // reset the 404 error
  header($protocol." 200 OK");
  header("Status: 200 OK");
  
  // send headers for the image
  header("Content-Type: " . $controller->mediaobject->getContenttype());
  header("Content-Length: " . $filesize);
  header('Content-Disposition: inline; filename="'.basename($serverFilename).'"');

  // open the file and send it
  $fp = fopen($serverFilename, 'rb');
  fpassthru($fp);
  exit;
 
}

?>