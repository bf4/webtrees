<?php
/**
 * Class that defines a media object
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
 * @package PhpGedView
 * @subpackage Charts
 * @version $Id$
 */

require_once('includes/gedcomrecord.php');

class Media extends GedcomRecord {
	var $disp = true;
	var $title = "";
	var $file = "";
	var $ext = "";
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
		if (empty($this->title)) $this->title = get_gedcom_value("TITL", 2, $gedrec);
		$this->file = get_gedcom_value("FILE", 1, $gedrec);
		$et = preg_match("/(\.\w+)$/", $this->file, $ematch);
		$this->ext = "";
		if ($et>0) $this->ext = substr(trim($ematch[1]),1);
	}

	/**
	 * Static function used to get an instance of a media object
	 * @param string $pid	the ID of the media to retrieve
	 */
	function &getInstance($pid, $simple=true) {
		global $objectlist, $GEDCOM, $GEDCOMS, $pgv_changes;

		if (isset($objectlist[$pid]) && $objectlist[$pid]['gedfile']==$GEDCOMS[$GEDCOM]['id']) {
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

	/**
	 * get the media title
	 * @return string
	 */
	function getTitle() {
		global $pgv_lang;
		if (!$this->canDisplayDetails()) return $pgv_lang["private"];
		if (empty($this->title)) return $pgv_lang["unknown"];
		return $this->title;
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
	 * get the media file type
	 * @return string
	 */
	function getFiletype() {
		return $this->ext;
	}

	/**
	 * get the URL to link to this object
	 * @string a url that can be used to link to this object
	 */
	function getLinkUrl() {
		global $GEDCOM;

		$url = "medialist.php?action=filter&amp;search=yes&amp;filter=".$this->getTitle()."&amp;ged=".$GEDCOM;
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