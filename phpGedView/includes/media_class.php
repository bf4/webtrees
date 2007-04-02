<?php
/**
 * Class that defines a media object
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2006	John Finlay and Others
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
	var $note = "";
	var $filesize = -1;
	var $width = 0;
	var $height = 0;
	var $indilist = null;
	var $famlist = null;

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
		global $objectlist, $GEDCOM, $GEDCOMS, $pgv_changes;

		if (isset($objectlist[$pid]['gedfile']) && $objectlist[$pid]['gedfile']==$GEDCOMS[$GEDCOM]['id']) {
			if (isset($objectlist[$pid]['object'])) return $objectlist[$pid]['object'];
		}

		$gedrec = find_media_record($pid);
		if (empty($gedrec)) {
			$ct = preg_match("/(\w+):(.+)/", $pid, $match);
			if ($ct>0) {
				$servid = trim($match[1]);
				$remoteid = trim($match[2]);
				$service = ServiceClient::getInstance($servid);
				$newrec= $service->mergeGedcomRecord($remoteid, "0 @".$pid."@ OBJE\r\n1 RFN ".$pid, false);
				$gedrec = $newrec;
			}
		}
		if (empty($gedrec)) {
			if (userCanEdit(getUserName()) && isset($pgv_changes[$pid."_".$GEDCOM])) {
				$gedrec = find_updated_record($pid);
				$fromfile = true;
			}
		}
		if (empty($gedrec)) return null;
		$obje = new Media($gedrec, $simple);
		if (!empty($fromfile)) $obje->setChanged(true);
		$objectlist[$pid]['object'] = &$obje;
		return $obje;
	}

	/**
	 * Check if privacy options allow this record to be displayed
	 * @return boolean
	 */
	function canDisplayDetails() {
		return $this->disp;
	}
	
	//Returns the Note for the associated media
	function getNote(){
		return $this->note;
	}
	/**
	 * get the media title
	 * @return string
	 */
	function getTitle() {
		global $pgv_lang;
		if (!$this->canDisplayDetails()) return $pgv_lang["private"];
		if (empty($this->title)) {
			$title = basename($this->file);
			if (!empty($title)) return $title;
			return $pgv_lang["unknown"];
		}
		return $this->title;
	}

	/**
	 * get the _HEB or ROMN media title
	 * @return string
	 */
	function getAddTitle() {
		global $pgv_lang;

		if (!$this->canDisplayDetails()) return "";
		
		$addtitle = get_gedcom_value("TITL:_HEB", 2, $this->gedrec);
		if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:_HEB", 1, $this->gedrec);
		if (!empty($addtitle)) $addtitle = "<br />".$addtitle;
		
		$addtitle2 = get_gedcom_value("TITL:ROMN", 2, $this->gedrec);
		if (empty($addtitle2)) $addtitle2 = get_gedcom_value("TITL:ROMN", 1, $this->gedrec);
		
		if (!empty($addtitle2)) $addtitle .= "<br />\n".$addtitle2;
		return $addtitle;
	}

	/**
	 * get the media sortable name
	 * @return string
	 */
	function getSortableName() {
		return $this->getTitle();
	}

	/**
	 * get the media file name
	 * @return string
	 */
	function getFilename() {
		return $this->file;
	}

	/**
	 * get the thumbnail filename
	 * @return string
	 */
	function getThumbnail() {
		return thumbnail_file($this->file);
	}

	/**
	 * get the media file size
	 * @return string
	 */
	function getFilesize() {
		if ($this->filesize<0) $this->filesize = sprintf("%.2f", @filesize($this->file)/1024);
		return $this->filesize;
	}

	/**
	 * get the media file type
	 * @return string
	 */
	function getFiletype() {
		if ($this->ext) return $this->ext;
		// image ?
		$imageTypes = array("","GIF", "JPG", "PNG", "SWF", "PSD", "BMP", "TIFF", "TIFF", "JPC", "JP2", "JPX", "JB2", "SWC", "IFF", "WBMP", "XBM");
		$imgsize = @getimagesize($this->file); // [0]=width [1]=height [2]=filetype
		$this->width = 0+$imgsize[0];
		$this->height = 0+$imgsize[1];
		$this->ext = $imageTypes[0+$imgsize[2]];
		if ($this->ext) return $this->ext;
		// not an image : get file extension
		$exp = explode("?", $this->file);
		$pathinfo = pathinfo($exp[0]);
		$this->ext = @strtoupper($pathinfo['extension']);
		// unknown file type
		if (!$this->ext) $this->ext = "-";
		return $this->ext;
	}

	/**
	 * get the URL to link to this object
	 * @string a url that can be used to link to this object
	 */
	function getLinkUrl() {
		global $GEDCOM;

		$url = "mediaviewer.php?mid=".$this->getXref()."&amp;ged=".$GEDCOM;
		/** FIXME
		if ($this->isRemote()) {
			$parts = preg_split("/:/", $this->rfn);
			if (count($parts)==2) {
				$servid = $parts[0];
				$aliaid = $parts[1];
				if (!empty($servid)&&!empty($aliaid)) {
					$servrec = find_gedcom_record($servid);
					if (empty($servrec)) $servrec = find_updated_record($servid);
					if (!empty($servrec)) {
						$surl = get_gedcom_value("URL", 1, $servrec);
						$url = "medialist.php?id=".$aliaid;
						if (!empty($surl)) $url = dirname($surl)."/".$url;
						$gedcom = get_gedcom_value("_DBID", 1, $servrec);
						if (!empty($gedcom)) $url.="&amp;ged=".$gedcom;
					}
				}
			}
		}**/
		return $url;
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
}
?>
