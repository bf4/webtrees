<?php
/**
 * Local versions of mstring functions that may not be present on target servers.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2008 Greg Roach.  All rights reserved
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
 * @version $Id: $
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

define ('PGV_MB_UTF_REGEX', '/[\x09\x0A\x0D\x20-\x7E]|[\xC2-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF][\x80-\xBF]/');

function mb_substr($string, $start=0, $length=null) {
	if (!preg_match_all(PGV_MB_UTF_REGEX, $string, $match)) {
		return '';
	}
	$total=count($match[0]);
	if ($start<0) {
		$start=$total+$start;
	}
	if ($start<0) {
		$start=0;
	}
	if (is_null($length)) {
		$length=$total;
	}
	if ($start+$length>$total) {
		$length=$total-$start;
	}
  return implode('', array_slice($match[0], $start, $length));
}

function mb_strlen($string) {
	if (!preg_match_all(PGV_MB_UTF_REGEX, $string, $match)) {
		return 0;
	} else {
		return count($match[0]);
	}
}

?>
