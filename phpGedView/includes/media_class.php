<?php
/**
 * Class that defines a media object
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008	John Finlay and Others
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
 * @subpackage Charts
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

require_once('includes/gedcomrecord.php');

class Media extends GedcomRecord {
	var $disp = true;
	var $title = "";
	var $file = "";
	var $ext = "";
	var $mime = "";
	var $note = "";
	var $filesizeraw = -1;
	var $width = 0;
	var $height = 0;
	var $indilist = null;
	var $famlist = null;
	var $serverfilename = "";
	var $fileexists = false;
	var $filepropset = false;

	/**
	 * Constructor for media object
	 * @param string $gedrec	the raw repository gedcom record
	 */
	function Media($gedrec) {
		parent::GedcomRecord($gedrec);
		$this->disp = displayDetailsByID($this->xref, "OBJE");
		$this->title = get_gedcom_value("TITL", 1, $gedrec);
		$this->note = get_gedcom_value("NOTE", 1, $gedrec);
		if (empty($this->title)) $this->title = get_gedcom_value("TITL", 2, $gedrec);
		$this->file = get_gedcom_value("FILE", 1, $gedrec);
	}

	/**
	 * Static function used to get an instance of a media object
	 * @param string $pid	the ID of the media to retrieve
	 */
	function &getInstance($pid, $simple=true) {
		global $gedcom_record_cache, $GEDCOM, $pgv_changes;

		$ged_id=get_id_from_gedcom($GEDCOM);
		// Check the cache first
		if (isset($gedcom_record_cache[$pid][$ged_id])) {
			return $gedcom_record_cache[$pid][$ged_id];
		}

		$gedrec = find_media_record($pid);
		if (empty($gedrec)) {
			$ct = preg_match("/(\w+):(.+)/", $pid, $match);
			if ($ct>0) {
				$servid = trim($match[1]);
				$remoteid = trim($match[2]);
				require_once 'includes/serviceclient_class.php';
				$service = ServiceClient::getInstance($servid);
				if (!empty($service)) {
					$newrec= $service->mergeGedcomRecord($remoteid, "0 @".$pid."@ OBJE\r\n1 RFN ".$pid, false);
					$gedrec = $newrec;
				}
			}
		}
		if (empty($gedrec)) {
			if (PGV_USER_CAN_EDIT && isset($pgv_changes[$pid."_".$GEDCOM])) {
				$gedrec = find_updated_record($pid);
				$fromfile = true;
			}
		}
		if (empty($gedrec)) return null;
		$obje = new Media($gedrec, $simple);
		if (!empty($fromfile)) $obje->setChanged(true);
		// Store the object in the cache
		$obje->ged_id=$ged_id;
		$gedcom_record_cache[$pid][$ged_id]=&$obje;
		return $obje;
	}

	/**
	 * Check if privacy options allow this record to be displayed
	 * @return boolean
	 */
	function canDisplayDetails() {
		return $this->disp;
	}

	/**
	 * get the media note
	 * @return string
	 */
	function getNote(){
		return $this->note;
	}

	function getName() {
		return $this->getFullName();
	}

	/**
	 * get the media sortable name
	 * @return string
	 */
	function getSortableName() {
		return $this->getSortName();
	}

	/**
	 * get the thumbnail filename
	 * @return string
	 */
	function getThumbnail($generateThumb = true) {
		return thumbnail_file($this->file,$generateThumb);
	}

	/**
	 * get the media file name
	 * @return string
	 */
	function getFilename() {
		return $this->file;
	}

	/**
	 * get the relative file path of the image on the server
	 * @return string
	 */
	function getLocalFilename() {
		return check_media_depth($this->file);
	}

	/**
	 * get the file name on the server
	 * @return string
	 */
	function getServerFilename() {
		global $USE_MEDIA_FIREWALL;
		if ($this->serverfilename) return $this->serverfilename;
		$localfilename = $this->getLocalFilename();
		if (!empty($localfilename)) {
			if (file_exists($localfilename)){
				// found image in unprotected directory
				$this->fileexists = 2;
				$this->serverfilename = $localfilename;
				return $this->serverfilename;
			}
			if ($USE_MEDIA_FIREWALL) {
				$protectedfilename = get_media_firewall_path($localfilename);
				if (file_exists($protectedfilename)){
					// found image in protected directory
					$this->fileexists = 3;
					$this->serverfilename = $protectedfilename;
					return $this->serverfilename;
				}
			}
		}
		// file doesn't exist, return the standard localfilename for backwards compatibility
		$this->fileexists = false;
		$this->serverfilename = $localfilename;
		return $this->serverfilename;
	}

	/**
	 * check if the file exists on this server
	 * @return boolean
	 */
	function fileExists() {
		if (!$this->serverfilename) $this->getServerFilename();
		return $this->fileexists;
	}

	/**
	 * get the media file size
	 * @return string
	 */
	function getFilesize() {
		if (!$this->filepropset) $this->setFileProperties();
		return(sprintf("%.2f", @$this->filesizeraw/1024));
	}

	/**
	 * get the media file size, unformatted
	 * @return number
	 */
	function getFilesizeraw() {
		if (!$this->filepropset) $this->setFileProperties();
		return $this->filesizeraw;
	}

	/**
	 * get the media type
	 * @return string
	 */
	function getMediatype() {
		$mediaType = strtolower(get_gedcom_value("FORM:TYPE", 2, $this->gedrec));
		return $mediaType;
	}

	/**
	 * get the media file type
	 * @return string
	 */
	function getFiletype() {
		if (!$this->filepropset) $this->setFileProperties();
		return $this->ext;
	}

	/**
	 * get the media mime type
	 * @return string
	 */
	function getMimetype() {
		if (!$this->filepropset) $this->setFileProperties();
		return $this->mime;
	}

	/**
	 * get the width of the image
	 * @return number (0 if not an image)
	 */
	function getWidth() {
		if (!$this->filepropset) $this->setFileProperties();
		return $this->width;
	}

	/**
	 * get the height of the image
	 * @return number (0 if not an image)
	 */
	function getHeight() {
		if (!$this->filepropset) $this->setFileProperties();
		return $this->height;
	}

	/**
	 * internal function, sets a number of properties
	 * no need to call directly
	 * @return nothing
	 */
	function setFileProperties() {
		global $pgv_lang;

		if ($this->fileExists()) {
			$this->filesizeraw = @filesize($this->getServerFilename());
			$imgsize=@getimagesize($this->getServerFilename()); // [0]=width [1]=height [2]=filetype ['mime']=mimetype
			if (is_array($imgsize)) {
				// this is an image
				$this->width =0+$imgsize[0];
				$this->height=0+$imgsize[1];
				$imageTypes  =array("","GIF","JPG","PNG","SWF","PSD","BMP","TIFF","TIFF","JPC","JP2","JPX","JB2","SWC","IFF","WBMP","XBM");
				$this->ext   =$imageTypes[0+$imgsize[2]];
				$this->mime  =$imgsize['mime'];
			}
		}
		if (!$this->mime) {
			// this is not an image, OR the file doesn't exist OR it is a url
			// set file type equal to the file extension - can't use parse_url because this may not be a full url
			$exp = explode("?", $this->file);
			$pathinfo = pathinfo($exp[0]);
			$this->ext = @strtoupper($pathinfo['extension']);
			// all mimetypes we wish to serve with the media firewall must be added to this array.
			$mime=array('DOC'=>'application/msword', 'MOV'=>'video/quicktime', 'MP3'=>'audio/mpeg', 'PDF'=>'application/pdf', 
			'PPT'=>'application/vnd.ms-powerpoint', 'RTF'=>'text/rtf', 'SID'=>'image/x-mrsid', 'TXT'=>'text/plain', 'XLS'=>'application/vnd.ms-excel');
			if (empty($mime[$this->ext])) {
				// if we don't know what the mimetype is, use something ambiguous
				$this->mime='application/octet-stream';
				if ($this->fileExists()) {
					// alert the admin if we cannot determine the mime type of an existing file
					// as the media firewall will be unable to serve this file properly
					AddToLog($pgv_lang["unknown_mime"].' >'.$this->file.'<');
				}
			} else {
				$this->mime=$mime[$this->ext];
			}
		}
		$this->filepropset = true;
	}

	/**
	 * get the URL to link to this object
	 * @string a url that can be used to link to this object
	 */
	function getLinkUrl() {
		return parent::getLinkUrl('mediaviewer.php?mid=');
	}

	/**
	 * check if the given Media object is in the objectlist
	 * @param Media $obje
	 * @return mixed  returns the ID for the for the matching media or false if not found
	 */
	function in_obje_list(&$obje) {
		global $TBLPREFIX, $GEDCOMS, $GEDCOM, $FILE, $DBCONN;

		if (is_null($obje)) return false;
		if (empty($FILE)) $FILE = $GEDCOM;
		$sql = "SELECT m_media FROM ".$TBLPREFIX."media WHERE m_file='".$DBCONN->escapeSimple($obje->file)."' AND m_titl LIKE '".$DBCONN->escapeSimple($obje->title)."' AND m_gedfile=".$GEDCOMS[$FILE]['id'];
		$res = dbquery($sql);

		if ($res->numRows()>0) {
			$row = $res->fetchRow();
			return $row[0];
		}

		return false;
	}

	/**
	 * check if this object is equal to the given object
	 * basically just checks if the IDs are the same
	 * @param GedcomRecord $obj
	 */
	function equals(&$obj) {
		if (is_null($obj)) return false;
		if ($this->xref==$obj->getXref()) return true;
		if ($this->title==$obj->title && $this->file==$obj->file) return true;
		return false;
	}

	// If this object has no name, what do we call it?
	function getFallBackName() {
		return basename($this->file);
	}

	// Get an array of structures containing all the names in the record
	function getAllNames() {
		return parent::getAllNames('TITL', 2);
	}
}
?>
