<?php
/**
 * String handling functions for strings optionally containing UTF-8 characters.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2008  PGV Development Team.  All rights reserved.
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
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}


/*
 * Expand the input string into an array of UTF-8 characters
 */
function UTF8_explodeString($text) {
	if (is_array($text)) return $text;		// No action:  input has already been expanded
	$result = array();
	if ($text=='') return $result;
	
	$charPos = 0;
	$textLen = strlen($text);
	
	while ($charPos<$textLen) {
		$charLen = 1;
		$letter = substr($text, $charPos, 1);
		if ((ord($letter) & 0xE0) == 0xC0) $charLen = 2;		// 2-byte sequence
		if ((ord($letter) & 0xF0) == 0xE0) $charLen = 3;		// 3-byte sequence
		if ((ord($letter) & 0xF8) == 0xF0) $charLen = 4;		// 4-byte sequence
		$result[] = substr($text, $charPos, $charLen);
		$charPos += $charLen;
	}

	return $result;
}


/*
 * Get the length of the input UTF8-encoded string
 */
function UTF8_strlen($text) {
	$UTF8_text = UTF8_explodeString($text);

	return count($UTF8_text);
}


/*
 * Get the ordinal value of the input UTF8-encoded character
 */
function UTF8_ord($char) {
	$charLen = strlen($char);

	switch ($charLen) {
	case 1: 
		$value = ord($char);
		break;
	case 2:
		$value = ((ord(substr($char,0,1)) & 0x1F) << 6) + (ord(substr($char,1,1)) & 0x3F);
		break;
	case 3:
		$value = ((ord(substr($char,0,1)) & 0x0F) << 12) + ((ord(substr($char,1,1)) & 0x3F) << 6) + (ord(substr($char,1,2)) & 0x3F);
		break;
	case 4:
		$value = ((ord(substr($char,0,1)) & 0x07) << 18) + ((ord(substr($char,1,1)) & 0x3F) << 12) + ((ord(substr($char,1,2)) & 0x3F) << 6) + (ord(substr($char,1,3)) & 0x3F);
		break;
	}

	return $value;
}


/*
 * Extract substring from input string
 */
function UTF8_substr($text, $start=0, $end=0) {
	$UTF8_text = UTF8_explodeString($text);
	$textLen = count($UTF8_text);

	if ($textLen==0) return '';

	if ($end==0 || $end>$textLen) $end = $textLen;
	if ($start<0) $start = $textLen - $start;
	if ($end<0) $end = $textLen - $end;
	if ($start>$textLen || $start>$end) return '';

	$result = array_slice($UTF8_text, $start, $end);

	if (is_array($text)) return $result;
	else return implode('', $result);
}


/*
 * Convert input string to upper case
 */
function UTF8_strtoupper($text) {
	global $UTF8_LC_letters;
	$UTF8_text = UTF8_explodeString($text);
	$textLen = count($UTF8_text);
	if ($textLen==0) return $text;

	$result = array();
	foreach ($UTF8_text as $letter) {
		if (isset($UTF8_LC_letters[$letter])) $result[] = $UTF8_LC_letters[$letter];
		else $result[] = $letter;		// No translation when no matching upper case letter exists
	}

	if (is_array($text)) return $result;
	else return implode('', $result);
}


/*
 * Convert input string to lower case
 */
function UTF8_strtolower($text) {
	global $UTF8_LC_letters;
	$UTF8_text = UTF8_explodeString($text);
	$textLen = count($UTF8_text);
	if ($textLen==0) return $text;

	$result = array();
	$UTF8_UC_letters = array_flip($UTF8_LC_letters);
	foreach ($UTF8_text as $letter) {
		if (isset($UTF8_UC_letters[$letter])) $result[] = $UTF8_UC_letters[$letter];
		else $result[] = $letter;		// No translation when no matching lower case letter exists
	}

	if (is_array($text)) return $result;
	else return implode('', $result);
}


/*
 * Case sensitive search for a string to be contained in another string
 */
function UTF8_strstr($haystack, $needle) {
	$UTF8_haystack = UTF8_explodeString($haystack);
	$haystackLen = count($UTF8_haystack);
	$UTF8_needle = UTF8_explodeString($needle);
	$needleLen = count($UTF8_needle);
	if ($haystackLen==0 || $needleLen==0) return false;

	$lastPos = $haystackLen - $needleLen;

	for ($currPos=0; $currPos<=$lastPos; $currPos++) {
		$found = true;
		for ($i=0; $i<$needleLen; $i++) {
			if ($UTF8_haystack[$currPos+$i]!=$UTF8_needle[$i]) {
				$found = false;
				break;
			}
		}
		
	}

	if ($found) return $currPos;
	else return false;
}


/*
 * Case insensitive search for a string to be contained in another string
 */
function UTF8_stristr($haystack, $needle) {
	$UTF8_haystack = UTF8_strtoupper(UTF8_explodeString($haystack));
	$UTF8_needle = UTF8_strtoupper(UTF8_explodeString($needle));

	return UTF8_strstr($UTF8_haystack, $UTF8_needle);
}


/*
 * Case sensitive comparison of two strings
 */
function UTF8_strcmp($text1, $text2) {
	$UTF8_text1 = UTF8_explodeString($text1);
	$text1Len = count($UTF8_text1);
	$UTF8_text2 = UTF8_explodeString($text2);
	$text2Len = count($UTF8_text2);

	$minLen = min($UTF8_text1, $UTF8_text2);

	for ($i=0; $i<$minLen; $i++) {
		$UTF8_ord1 = UTF8_ord($UTF8_text1[$i]);
		$UTF8_ord2 = UTF8_ord($UTF8_text2[$i]);
		if ($UTF8_ord1<$UTF8_ord2) return -1;
		if ($UTF8_ord1>$UTF8_ord2) return 1;
	}

	return $text1Len - $text2Len;
}


/*
 * Case sensitive comparison of two strings, max length specifiable
 */
function UTF8_strncmp($text1, $text2, $maxLen) {
	$UTF8_text1 = UTF8_explodeString($text1);
	$UTF8_text2 = UTF8_explodeString($text2);
	if ($maxLen>0) {
		$UTF8_text1 = array_slice($UTF8_text1, 0, $maxLen);
		$UTF8_text2 = array_slice($UTF8_text2, 0, $maxLen);
	}

	return UTF8_strcmp($UTF8_text1, $UTF8_text2);
}


/* 
 * Case insensitive comparison of two strings
 */
function UTF8_strcasecmp($text1, $text2) {
	$UTF8_text1 = UTF8_strtoupper(UTF8_explodeString($text1));
	$UTF8_text2 = UTF8_strtoupper(UTF8_explodeString($text2));

	return UTF8_strcmp($UTF8_text1, $UTF8_text2);
}


/*
 * Case insensitive comparison of two strings, max length specifiable
 */
function UTF8_strncasecmp($text1, $text2, $maxLength) {
	$UTF8_text1 = UTF8_strtoupper(UTF8_explodeString($text1));
	$UTF8_text2 = UTF8_strtoupper(UTF8_explodeString($text2));
	if ($maxLength>0) {
		$UTF8_text1 = array_slice($UTF8_text1, 0, $maxLength);
		$UTF8_text2 = array_slice($UTF8_text2, 0, $maxLength);
	}

	return UTF8_strcmp($UTF8_text1, $UTF8_text2);
}
?>
