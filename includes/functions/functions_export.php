<?php
/**
* Functions for exporting data
*
* webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
* Copyright (C) 2002 to 2009 PGV Development Team.  All rights reserved.
*
* Modifications Copyright (c) 2010 Greg Roach
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
* @subpackage Admin
* @version $Id$
*/

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('WT_FUNCTIONS_EXPORT_PHP', '');

// Tidy up a gedcom record on export, for compatibility/portability
function reformat_record_export($rec) {
	global $WORD_WRAPPED_NOTES;

	$newrec='';
	foreach (preg_split('/[\r\n]+/', $rec, -1, PREG_SPLIT_NO_EMPTY) as $line) {
		// Escape @ characters
		// TODO:
		// Need to replace '@' with '@@', unless it is either
		// a) an xref, such as @I123@
		// b) an escape, such as @#D FRENCH R@
		if (false) {
			$line=str_replace('@', '@@', $line);
		}
		// Split long lines
		// The total length of a GEDCOM line, including level number, cross-reference number,
		// tag, value, delimiters, and terminator, must not exceed 255 (wide) characters.
		// Use quick strlen() check before using slower utf8_strlen() check
		if (strlen($line)>WT_GEDCOM_LINE_LENGTH && utf8_strlen($line)>WT_GEDCOM_LINE_LENGTH) {
			list($level, $tag)=explode(' ', $line, 3);
			if ($tag!='CONT' && $tag!='CONC') {
				$level++;
			}
			do {
				// Split after $pos chars
				$pos=WT_GEDCOM_LINE_LENGTH;
				if ($WORD_WRAPPED_NOTES) {
					// Split on a space, and remove it (for compatibility with some desktop apps)
					while ($pos && utf8_substr($line, $pos-1, 1)!=' ') {
						--$pos;
					}
					if ($pos==strpos($line, ' ', 3)+1) {
						// No spaces in the data! Can't split it :-(
						break;
					} else {
						$newrec.=utf8_substr($line, 0, $pos-1).WT_EOL;
						$line=$level.' CONC '.utf8_substr($line, $pos);
					}
				} else {
					// Split on a non-space (standard gedcom behaviour)
					while ($pos && utf8_substr($line, $pos-1, 1)==' ') {
						--$pos;
					}
					if ($pos==strpos($line, ' ', 3)) {
						// No non-spaces in the data! Can't split it :-(
						break;
					}
					$newrec.=utf8_substr($line, 0, $pos).WT_EOL;
					$line=$level.' CONC '.utf8_substr($line, $pos);
				}
			} while (utf8_strlen($line)>WT_GEDCOM_LINE_LENGTH);
		}
		$newrec.=$line.WT_EOL;
	}
	return $newrec;
}

/*
* Create a header for a (newly-created or already-imported) gedcom file.
*/
function gedcom_header($gedfile) {
	$ged_id=get_id_from_gedcom($gedfile);

	// Default values for a new header
	$HEAD="0 HEAD";
	$SOUR="\n1 SOUR ".WT_WEBTREES."\n2 NAME ".WT_WEBTREES."\n2 VERS ".WT_VERSION_TEXT;
	$DEST="\n1 DEST DISKETTE";
	$DATE="\n1 DATE ".strtoupper(date("d M Y"))."\n2 TIME ".date("H:i:s");
	$GEDC="\n1 GEDC\n2 VERS 5.5.1\n2 FORM Lineage-Linked";
	$CHAR="\n1 CHAR UTF-8";
	$FILE="\n1 FILE {$gedfile}";
	$LANG="";
	$PLAC="\n1 PLAC\n2 FORM City, County, State/Province, Country";
	$COPR="";
	$SUBN="";
	$SUBM="\n1 SUBM @SUBM@\n0 @SUBM@ SUBM\n1 NAME ".WT_USER_NAME; // The SUBM record is mandatory

	// Preserve some values from the original header
	if (get_gedcom_setting($ged_id, 'imported')) {
		$head=find_gedcom_record("HEAD", $ged_id);
		if (preg_match("/\n1 PLAC\n2 FORM .+/", $head, $match)) {
			$PLAC=$match[0];
		}
		if (preg_match("/\n1 LANG .+/", $head, $match)) {
			$LANG=$match[0];
		}
		if (preg_match("/\n1 SUBN .+/", $head, $match)) {
			$SUBN=$match[0];
		}
		if (preg_match("/\n1 COPR .+/", $head, $match)) {
			$COPR=$match[0];
		}
		// Link to SUBM/SUBN records, if they exist
		$subn=
			WT_DB::prepare("SELECT o_id FROM `##other` WHERE o_type=? AND o_file=?")
			->execute(array('SUBN', $ged_id))
			->fetchOne();
		if ($subn) {
			$SUBN="\n1 SUBN @{$subn}@";
		}
		$subm=
			WT_DB::prepare("SELECT o_id FROM `##other` WHERE o_type=? AND o_file=?")
			->execute(array('SUBM', $ged_id))
			->fetchOne();
		if ($subm) {
			$SUBM="\n1 SUBM @{$subm}@";
		}
	}

	return $HEAD.$SOUR.$DEST.$DATE.$GEDC.$CHAR.$FILE.$COPR.$LANG.$PLAC.$SUBN.$SUBM."\n";
}

/**
 * remove any custom webtrees tags from the given gedcom record
 * custom tags include _WT_USER and _THUM
 * @param string $gedrec the raw gedcom record
 * @return string the updated gedcom record
 */
function remove_custom_tags($gedrec, $remove="no") {
	if ($remove=="yes") {
		//-- remove _WT...
		$gedrec = preg_replace("/\d _WT.*/", "", $gedrec);
		//-- remove _THUM
		$gedrec = preg_replace("/\d _THUM .*/", "", $gedrec);
	}
	//-- cleanup so there are not any empty lines
	$gedrec = preg_replace(array("/(\r\n)+/", "/\r+/", "/\n+/"), array("\r\n", "\r", "\n"), $gedrec);
	//-- make downloaded file DOS formatted
	$gedrec = preg_replace("/([^\r])\n/", "$1\n", $gedrec);
	return $gedrec;
}

/**
 * Convert media path by:
 * - removing current media directory
 * - adding a new prefix
 * - making directory name separators consistent
 */
function convert_media_path($rec, $path, $slashes) {
	global $MEDIA_DIRECTORY;

	$file = get_gedcom_value("FILE", 1, $rec);
	if (preg_match("~^https?://~i", $file)) return $rec; // don't modify URLs

	$rec = str_replace('FILE '.$MEDIA_DIRECTORY, 'FILE '.trim($path).'/', $rec);
	$rec = str_replace('\\', '/', $rec);
	$rec = str_replace('//', '/', $rec);
	if ($slashes=='backward') $rec = str_replace('/', '\\', $rec);
	return $rec;
}

/*
 * Export the database in GEDCOM format
 *
 *  input parameters:
 * $gedcom:         GEDCOM to be exported
 * $gedout:         Handle of output file
 * $exportOptions:  array of options for this Export operation as follows:
 *  'privatize':    which Privacy rules apply?  (none, visitor, user, manager)
 *  'toANSI':       should the output be produced in ANSI instead of UTF-8?  (yes, no)
 *  'noCustomTags': should custom tags be removed?  (yes, no)
 *  'path':         what constant should prefix all media file paths?  (eg: media/  or c:\my pictures\my family
 *  'slashes':      what folder separators apply to media file paths?  (forward, backward)
 */
function export_gedcom($gedcom, $gedout, $exportOptions) {
	global $GEDCOM;

	// Temporarily switch to the specified GEDCOM
	$oldGEDCOM = $GEDCOM;
	$GEDCOM = $gedcom;
	$ged_id=get_id_from_gedcom($gedcom);

	switch($exportOptions['privatize']) {
	case 'gedadmin':
		$access_level=WT_PRIV_NONE;
		break;
	case 'user':
		$access_level=WT_PRIV_USER;
		break;
	case 'visitor':
		$access_level=WT_PRIV_PUBLIC;
		break;
	case 'none':
		$access_level=WT_USER_ACCESS_LEVEL;
		break;
	}

	$head=gedcom_header($gedcom);
	if ($exportOptions['toANSI']=="yes") {
		$head=str_replace("UTF-8", "ANSI", $head);
		$head=utf8_decode($head);
	}
	$head=remove_custom_tags($head, $exportOptions['noCustomTags']);

	// Buffer the output.  Lots of small fwrite() calls can be very slow when writing large gedcoms.
	$buffer=reformat_record_export($head);

	$recs=
		WT_DB::prepare("SELECT i_gedcom FROM `##individuals` WHERE i_file=? ORDER BY i_id")
		->execute(array($ged_id))
		->fetchOneColumn();
	foreach ($recs as $rec) {
		$rec=remove_custom_tags($rec, $exportOptions['noCustomTags']);
		if ($exportOptions['privatize']!='none') $rec=privatize_gedcom($ged_id, $rec, $access_level);
		if ($exportOptions['toANSI']=="yes") $rec=utf8_decode($rec);
		$buffer.=reformat_record_export($rec);
		if (strlen($buffer)>65536) {
			fwrite($gedout, $buffer);
			$buffer='';
		}
	}

	$recs=
		WT_DB::prepare("SELECT f_gedcom FROM `##families` WHERE f_file=? ORDER BY f_id")
		->execute(array($ged_id))
		->fetchOneColumn();
	foreach ($recs as $rec) {
		$rec=remove_custom_tags($rec, $exportOptions['noCustomTags']);
		if ($exportOptions['privatize']!='none') $rec=privatize_gedcom($ged_id, $rec, $access_level);
		if ($exportOptions['toANSI']=="yes") $rec=utf8_decode($rec);
		$buffer.=reformat_record_export($rec);
		if (strlen($buffer)>65536) {
			fwrite($gedout, $buffer);
			$buffer='';
		}
	}

	$recs=
		WT_DB::prepare("SELECT s_gedcom FROM `##sources` WHERE s_file=? ORDER BY s_id")
		->execute(array($ged_id))
		->fetchOneColumn();
	foreach ($recs as $rec) {
		$rec=remove_custom_tags($rec, $exportOptions['noCustomTags']);
		if ($exportOptions['privatize']!='none') $rec=privatize_gedcom($ged_id, $rec, $access_level);
		if ($exportOptions['toANSI']=="yes") $rec=utf8_decode($rec);
		$buffer.=reformat_record_export($rec);
		if (strlen($buffer)>65536) {
			fwrite($gedout, $buffer);
			$buffer='';
		}
	}

	$recs=
		WT_DB::prepare("SELECT o_gedcom FROM `##other` WHERE o_file=? AND o_type!=? AND o_type!=? ORDER BY o_id")
		->execute(array($ged_id, 'HEAD', 'TRLR'))
		->fetchOneColumn();
	foreach ($recs as $rec) {
		$rec=remove_custom_tags($rec, $exportOptions['noCustomTags']);
		if ($exportOptions['privatize']!='none') $rec=privatize_gedcom($ged_id, $rec, $access_level);
		if ($exportOptions['toANSI']=="yes") $rec=utf8_decode($rec);
		$buffer.=reformat_record_export($rec);
		if (strlen($buffer)>65536) {
			fwrite($gedout, $buffer);
			$buffer='';
		}
	}

	$recs=
		WT_DB::prepare("SELECT m_gedrec FROM `##media` WHERE m_gedfile=? ORDER BY m_media")
		->execute(array($ged_id))
		->fetchOneColumn();
	foreach ($recs as $rec) {
		$rec = convert_media_path($rec, $exportOptions['path'], $exportOptions['slashes']);
		$rec=remove_custom_tags($rec, $exportOptions['noCustomTags']);
		if ($exportOptions['privatize']!='none') $rec=privatize_gedcom($ged_id, $rec, $access_level);
		if ($exportOptions['toANSI']=="yes") $rec=utf8_decode($rec);
		$buffer.=reformat_record_export($rec);
		if (strlen($buffer)>65536) {
			fwrite($gedout, $buffer);
			$buffer='';
		}
	}

	fwrite($gedout, $buffer."0 TRLR".WT_EOL);

	$GEDCOM = $oldGEDCOM;
}
