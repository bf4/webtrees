<?php
/**
 * Name Specific Functions
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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

$NPFX_accept = array("Adm", "Amb", "Brig", "Can", "Capt", "Chan", "Chapln", "Cmdr", "Col", "Cpl", "Cpt", "Dr", "Gen", "Gov", "Hon", "Lady", "Lt", "Mr", "Mrs", "Ms", "Msgr", "Pfc", "Pres", "Prof", "Pvt", "Rabbi", "Rep", "Rev", "Sen", "Sgt", "Sir", "Sr", "Sra", "Srta", "Ven");

/**
 * Get array of common surnames from index
 *
 * This function returns a simple array of the most common surnames
 * found in the individuals list.
 * @param int $min the number of times a surname must occur before it is added to the array
 */
function get_common_surnames_index($ged) {
	global $GEDCOMS;

	if (empty($GEDCOMS[$ged]["commonsurnames"])) store_gedcoms();
	$surnames = array();
	if (empty($GEDCOMS[$ged]["commonsurnames"]) || ($GEDCOMS[$ged]["commonsurnames"]==",")) return $surnames;
	$names = preg_split("/[,;]/", $GEDCOMS[$ged]["commonsurnames"]);
	foreach($names as $indexval => $name) {
		$name = trim($name);
		if (!empty($name)) $surnames[$name]["name"] = stripslashes($name);
	}
	return $surnames;
}

/**
 * Get array of common surnames
 *
 * This function returns a simple array of the most common surnames
 * found in the individuals list.
 * @param int $min the number of times a surname must occur before it is added to the array
 */
function get_common_surnames($min) {
	global $TBLPREFIX, $GEDCOM, $CONFIGURED, $GEDCOMS, $COMMON_NAMES_ADD, $COMMON_NAMES_REMOVE, $pgv_lang, $HNN, $ANN;

	$surnames = array();
	if (!$CONFIGURED || !adminUserExists() || (count($GEDCOMS)==0) || (!check_for_import($GEDCOM))) return $surnames;
	$surnames = get_top_surnames(100);
	arsort($surnames);
	$topsurns = array();
	$i=0;
	foreach($surnames as $indexval => $surname) {
		$surname["name"] = trim($surname["name"]);
		if (!empty($surname["name"])
				&& stristr($surname["name"], "@N.N")===false
				&& stristr($surname["name"], $HNN)===false
				&& stristr($surname["name"], $ANN.",")===false
				&& stristr($COMMON_NAMES_REMOVE, $surname["name"])===false ) {
			if ($surname["match"]>=$min) {
				$topsurns[UTF8_strtoupper($surname["name"])] = $surname;
			}
			$i++;
		}
	}
	$addnames = preg_split("/[,;] /", $COMMON_NAMES_ADD);
	if ((count($addnames)==0) && (!empty($COMMON_NAMES_ADD))) $addnames[] = $COMMON_NAMES_ADD;
	foreach($addnames as $indexval => $name) {
		if (!empty($name)) {
			$topsurns[$name]["name"] = $name;
			$topsurns[$name]["match"] = $min;
		}
	}
	$delnames = preg_split("/[,;] /", $COMMON_NAMES_REMOVE);
	if ((count($delnames)==0) && (!empty($COMMON_NAMES_REMOVE))) $delnames[] = $COMMON_NAMES_REMOVE;
	foreach($delnames as $indexval => $name) {
		if (!empty($name)) {
			unset($topsurns[$name]);
		}
	}

	//-- check if we found some, else recurse
	if (empty($topsurns) && $min>2) $topsurns = get_common_surnames($min/2);
	uasort($topsurns, "itemsort");
	return $topsurns;
}

/**
 * get the sortable name from the gedcom name
 * @param string $name 	the name from the 1 NAME gedcom line including the /
 * @return string 	The new name in the form Surname, Given Names
 */
function sortable_name_from_name($name) {
	global $NPFX_accept;
	//-- remove any unwanted characters from the name
	if (preg_match("/^\.(\.*)$|^\?(\?*)$|^_(_*)$|^,(,*)$/", $name)) $name = preg_replace(array("/,/","/\./","/_/","/\?/"), array("","","",""), $name);
	$ct = preg_match("~(.*)/(.*)/(.*)~", $name, $match);
	if ($ct>0) {
		$surname = trim($match[2]);
		if (empty($surname)) $surname = "@N.N.";
		$givenname = trim($match[1]);
		$othername = trim($match[3]);
		if (empty($givenname)&&!empty($othername)) {
			$givenname = $othername;
			$othername = "";
		}

		// Remove any prefixes from given name
		while (preg_match('/^(\w+)\.? +(.*)/', $givenname, $match) && in_array($match[1], $NPFX_accept))
			$givenname=$match[2];

		if (empty($givenname)) $givenname = "@P.N.";
		$name = $surname;
		if (!empty($othername)) $name .= " ".$othername;
		$name .= ", ".$givenname;
	}
	if (!empty($name)) return $name;
	else return "@N.N., @P.N.";
}

/**
 * strip name prefixes
 *
 * this function strips the prefixes of lastnames
 * get rid of jr. Jr. Sr. sr. II, III and van, van der, de lowercase surname prefixes
 * a . and space must be behind a-z to ensure shortened prefixes and multiple prefixes are removed
 * @param string $lastname	The name to strip
 * @return string	The updated name
 */
function strip_prefix($lastname){
	$name = preg_replace(array("/ [jJsS][rR]\.?,/", "/ I+,/", "/^([a-z]{1,4}[\. \_\-\(\[])+/"), array(",",",",""), $lastname);
	$name = trim($name);
	if ($name=="") return $lastname;
	return $name;
}

/**
 * add a surname to the surnames array for counting
 * @param string $nsurname
 * @return string
 */
function surname_count($nsurname) {
	global $surnames, $alpha, $surname, $show_all, $i, $testname;
	// Match names with chosen first letter
	$lname = strip_prefix($nsurname);
	if (empty($lname)) $lname = $nsurname;
	$sort_letter=get_first_letter($lname);
		$tsurname = preg_replace(array("/ [jJsS][rR]\.?/", "/ I+/"), array("",""), $nsurname);
		$tsurname = UTF8_strtoupper($tsurname);
		if (empty($surname) || (UTF8_strtoupper($surname)==$tsurname)) {
			if (!isset($surnames[$tsurname])) {
				$surnames[$tsurname] = array();
				$surnames[$tsurname]["name"] = $nsurname;
				$surnames[$tsurname]["match"] = 1;
				$surnames[$tsurname]["fam"] = 1;
				$surnames[$tsurname]["alpha"] = get_first_letter($tsurname);
			}
			else {
				$surnames[$tsurname]["match"]++;
				if ($i==0 || $testname != $tsurname) $surnames[$tsurname]["fam"]++;
			}
			if ($i==0) $testname = $tsurname;
		}
		return $nsurname;
}

/**
 * get first letter
 *
 * get the first letter of a UTF-8 string
 * @param string $text	the text to get the first letter from
 * @return string 	the first letter UTF-8 encoded
 */
function get_first_letter($text, $import=false) {
	global $LANGUAGE, $CHARACTER_SET;
	global $MULTI_LETTER_ALPHABET, $digraph, $trigraph, $quadgraph, $digraphAll, $trigraphAll, $quadgraphAll;

	$danishFrom = array("AA", "Aa", "AE", "Ae", "OE", "Oe", "aa", "ae", "oe");
	$danishTo   = array("Å", "Å", "Æ", "Æ", "Ø", "Ø", "å", "æ", "ø");

	$text=trim(UTF8_strtoupper($text));
	if (!$import) {
		if ($LANGUAGE=="danish" || $LANGUAGE=="norwegian") {
			$text = str_replace($danishFrom, $danishTo, $text);
		}
	}

	$multiByte = false;
	// Look for 4-byte combinations that should be treated as a single character
	$letter = substr($text, 0, 4);
	if ($import) {
		if (isset($quadgraphAll[$letter])) $multiByte = true;
	} else {
		if (isset($quadgraph[$letter])) $multiByte = true;
	}

	if (!$multiByte) {
		// 4-byte combination isn't listed: try 3-byte combination
		$letter = substr($text, 0, 3);
		if ($import) {
			if (isset($trigraphAll[$letter])) $multiByte = true;
		} else {
			if (isset($trigraph[$letter])) $multiByte = true;
		}
	}

	if (!$multiByte) {
		// 3-byte combination isn't listed: try 2-byte combination
		$letter = substr($text, 0, 2);
		if ($import) {
			if (isset($digraphAll[$letter])) $multiByte = true;
		} else {
			if (isset($digraph[$letter])) $multiByte = true;
		}
	}

	if (!$multiByte) {
		// All lists failed: try for a UTF8 character
		$charLen = 1;
		$letter = substr($text, 0, 1);
		if ((ord($letter) & 0xE0) == 0xC0) $charLen = 2;		// 2-byte sequence
		if ((ord($letter) & 0xF0) == 0xE0) $charLen = 3;		// 3-byte sequence
		if ((ord($letter) & 0xF8) == 0xF0) $charLen = 4;		// 4-byte sequence
		$letter = substr($text, 0, $charLen);
	}
        if ($letter=="/") $letter="@"; //where has @P.N. vanished from names with a null firstname?
	
	return $letter;
}


/**
 * This function replaces @N.N. and @P.N. with the language specific translations
 * @param mixed $names	$names could be an array of name parts or it could be a string of the name
 * @return string
 */
function check_NN($names) {
	global $pgv_lang, $UNDERLINE_NAME_QUOTES;
	global $unknownNN, $unknownPN;

	$fullname = "";

	if (!is_array($names)){
		$lang = whatLanguage($names);
		$NN = $unknownNN[$lang];
		$names = stripslashes($names);
		$names = preg_replace(array("~ /~","~/,~","~/~"), array(" ", ",", " "), $names);
		$names = preg_replace(array("/@N.N.?/","/@P.N.?/"), array($unknownNN[$lang],$unknownPN[$lang]), trim($names));
		//-- underline names with a * at the end
		//-- see this forum thread http://sourceforge.net/forum/forum.php?thread_id=1223099&forum_id=185165
		if ($UNDERLINE_NAME_QUOTES) {
			$names = preg_replace("/\"(.+)\"/", "<span class=\"starredname\">$1</span>", $names);
		}
		$names = preg_replace("/([^ ]+)\*/", "<span class=\"starredname\">$1</span>", $names);
		return $names;
	}
	if (count($names) == 2 && stristr($names[0], "@N.N") && stristr($names[1], "@N.N")){
		$fullname = $pgv_lang["NN"]. " + ". $pgv_lang["NN"];
	}
	else {
		for($i=0; $i<count($names); $i++) {
			$lang = whatLanguage($names[$i]);
			$unknown = false;
			if (stristr($names[$i], "@N.N")) {
				$unknown = true;
				$names[$i] = preg_replace("/@N.N.?/", $unknownNN[$lang], trim($names[$i]));
			}
			if (stristr($names[$i], "@P.N")) $names[$i] = $unknownPN[$lang];
			if ($i==1 && $unknown && count($names)==3) $fullname .= ", ";
			else if ($i==2 && $unknown && count($names)==3) $fullname .= " + ";
				else if ($i==2 && stristr($names[2], "Individual ") && count($names) == 3) $fullname .= " + ";
			else if ($i==2 && count($names)>3) $fullname .= " + ";
				else $fullname .= ", ";
				$fullname .= trim($names[$i]);
			}
		}
	$fullname = trim($fullname);
	if (substr($fullname,-1)==",") $fullname = substr($fullname,0,strlen($fullname)-1);
	if (substr($fullname,0,2)==", ") $fullname = substr($fullname,2);
	$fullname = trim($fullname);
	if (empty($fullname)) return $pgv_lang["NN"];

	return $fullname;
}

// Returns 1 if $string is valid 7 bit ASCII and 0 otherwise.
function is_7bitascii($string) {
	return preg_match('/^(?:[\x09\x0A\x0D\x20-\x7E])*$/', $string);
}

// Returns 1 if $string is valid UTF-8 and 0 otherwise.
// See http://w3.org/International/questions/qa-forms-utf-8.html
function is_utf8($string) {
	return preg_match('/^(?:[\x09\x0A\x0D\x20-\x7E]|[\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})*$/', $string);
}

/**
 * Put all characters in a string in lowercase
 *
 * This function is a replacement for strtolower() and will put all characters in lowercase
 *
 * @author	eikland
 * @param	string $value the text to be converted to lowercase
 * @return	string $value_lower the converted text in lowercase
 * @todo look at function performance as it is much slower than strtolower
 */
/* function str2lower($value) {
	global $ALPHABET_upper, $ALPHABET_lower;
	static $all_ALPHABET_upper, $all_ALPHABET_lower;

	// Input may be 7 or 8 bit ASCII and we need UTF8 to work properly
	if (is_7bitascii($value))
		return strtolower($value);
	else if (!is_utf8($value))
		$value=utf8_encode($value);

	//-- get all of the upper and lower alphabets as a string
	if (!isset($all_ALPHABET_upper)) {
		$all_ALPHABET_upper = "";
		$all_ALPHABET_lower = "";
		foreach ($ALPHABET_upper as $l => $up_alphabet){
			$lo_alphabet = $ALPHABET_lower[$l];
			$ll = strlen($lo_alphabet);
			$ul = strlen($up_alphabet);
			if ($ll < $ul) $lo_alphabet .= substr($up_alphabet, $ll);
			if ($ul < $ll) $up_alphabet .= substr($lo_alphabet, $ul);
			$all_ALPHABET_lower .= $lo_alphabet;
			$all_ALPHABET_upper .= $up_alphabet;
		}
	}

	$len=strlen($value);
	for ($i=0; $i<$len;) {
		$letter=ord($value[$i]);
		if ($letter < 0x80) { // 7 bit ASCII
			$value[$i]=strtolower($value[$i]);
			$i++;
		} else if ($letter < 0xC0) { // 8 bit ASCII
			$pos=strpos($all_ALPHABET_upper, $value[$i]);
			if ($pos===false)
				$i++;
			else
				$value[$i++]=$all_ALPHABET_lower[$pos];
		} else if ($letter < 0xE0) { // 2 byte UNICODE
			$pos=strpos($all_ALPHABET_upper, $value[$i].$value[$i+1]);
			if ($pos===false)
				$i+=2;
			else {
				$value[$i++]=$all_ALPHABET_lower[$pos++];
				$value[$i++]=$all_ALPHABET_lower[$pos  ];
			}
		} else if ($letter < 0xF0) { // 3 byte UNICODE
			$pos=strpos($all_ALPHABET_upper, $value[$i].$value[$i+1].$value[$i+2]);
			if ($pos===false)
				$i+=3;
			else {
				$value[$i++]=$all_ALPHABET_lower[$pos++];
				$value[$i++]=$all_ALPHABET_lower[$pos++];
				$value[$i++]=$all_ALPHABET_lower[$pos  ];
			}
		}	else { // 4 byte UNICODE
			$pos=strpos($all_ALPHABET_upper, $value[$i].$value[$i+1].$value[$i+2].$value[$i+3]);
			if ($pos===false)
				$i+=4;
			else	{
				$value[$i++]=$all_ALPHABET_lower[$pos++];
				$value[$i++]=$all_ALPHABET_lower[$pos++];
				$value[$i++]=$all_ALPHABET_lower[$pos++];
				$value[$i++]=$all_ALPHABET_lower[$pos  ];
			}
		}
	}
	return $value;
} */
// END function str2lower

/**
 * Put all characters in a string in uppercase
 *
 * This function is a replacement for strtoupper() and will put all characters in uppercase
 *
 * @author	botak
 * @param	string $value the text to be converted to uppercase
 * @return	string $value_upper the converted text in uppercase
 * @todo look at function performance as it is much slower than strtoupper
 */
/* function str2upper($value) {
	global $ALPHABET_upper, $ALPHABET_lower;
	static $all_ALPHABET_upper, $all_ALPHABET_lower;

	// Input may be 7 or 8 bit ASCII and we need UTF8 to work properly
	if (is_7bitascii($value))
		return strtoupper($value);
	else if (!is_utf8($value))
		$value=utf8_encode($value);

	//-- get all of the upper and lower alphabets as a string
	if (!isset($all_ALPHABET_upper)) {
		$all_ALPHABET_upper = "";
		$all_ALPHABET_lower = "";
		foreach ($ALPHABET_upper as $l => $up_alphabet){
			$lo_alphabet = $ALPHABET_lower[$l];
			$ll = strlen($lo_alphabet);
			$ul = strlen($up_alphabet);
			if ($ll < $ul) $lo_alphabet .= substr($up_alphabet, $ll);
			if ($ul < $ll) $up_alphabet .= substr($lo_alphabet, $ul);
			$all_ALPHABET_lower .= $lo_alphabet;
			$all_ALPHABET_upper .= $up_alphabet;
		}
	}

	$len=strlen($value);
	for ($i=0; $i<$len;) {
		$letter=ord($value[$i]);
		if ($letter < 0x80) { // 7 bit ASCII
			$value[$i]=strtoupper($value[$i]);
			$i++;
		} else if ($letter < 0xC0) { // 8 bit ASCII
			$pos=strpos($all_ALPHABET_lower, $value[$i]);
			if ($pos===false)
				$i++;
			else
				$value[$i++]=$all_ALPHABET_upper[$pos];
		} else if ($letter < 0xE0) { // 2 byte UNICODE
			$pos=strpos($all_ALPHABET_lower, $value[$i].$value[$i+1]);
			if ($pos===false)
		 		$i+=2;
			else {
				$value[$i++]=$all_ALPHABET_upper[$pos++];
				$value[$i++]=$all_ALPHABET_upper[$pos  ];
			}
		} else if ($letter < 0xF0) { // 3 byte UNICODE
			$pos=strpos($all_ALPHABET_lower, $value[$i].$value[$i+1].$value[$i+2]);
			if ($pos!==false) {
				$value[$i++]=$all_ALPHABET_upper[$pos++];
				$value[$i++]=$all_ALPHABET_upper[$pos++];
				$value[$i++]=$all_ALPHABET_upper[$pos  ];
			} else
				$i+=3;
		}	else { // 4 byte UNICODE
			$pos=strpos($all_ALPHABET_lower, $value[$i].$value[$i+1].$value[$i+2].$value[$i+3]);
			if ($pos!==false) {
				$value[$i++]=$all_ALPHABET_upper[$pos++];
				$value[$i++]=$all_ALPHABET_upper[$pos++];
				$value[$i++]=$all_ALPHABET_upper[$pos++];
				$value[$i++]=$all_ALPHABET_upper[$pos  ];
			} else
				$i+=4;
		}
	}
	return $value;
} */
// END function str2upper


/**
 * Convert a string from UTF8 to ASCII
 *
 * This function is a replacement for utf8_decode()
 *
 * @author	http://www.php.net/manual/en/function.utf8-decode.php
 * @param	string $in_str the text to be converted
 * @return	string $new_str the converted text
 */
function smart_utf8_decode($in_str) {
	$new_str = html_entity_decode(htmlentities($in_str,ENT_COMPAT,'UTF-8'),ENT_COMPAT,'ISO-8859-1');
	$new_str = str_replace("&oelig;", "\x9c", $new_str);
	$new_str = str_replace("&OElig;", "\x8c", $new_str);
	return $new_str;
}

/**
 * determine the Daitch-Mokotoff Soundex code for a name
 * @param string $name	The name
 * @return array		The array of codes
 */
function DMSoundex($name, $option = "") {
	global $PGV_BASEDIRECTORY, $dmsoundexlist, $dmcoding, $maxchar, $INDEX_DIRECTORY, $cachecount, $cachename;

	// If the code tables are not loaded, reload! Keep them global!
	if (!isset($dmcoding)) {
		$fname = $PGV_BASEDIRECTORY."includes/dmarray.full.utf-8.php";
		require($fname);
	}

	// Load the previously saved cachefile and return. Keep the cache global!

	if ($option == "opencache") {
		$cachename = $INDEX_DIRECTORY."DM".date("mdHis", filemtime($fname)).".dat";
		if (file_exists($cachename)) {
			$fp = fopen($cachename, "r");
			$fcontents = fread($fp, filesize($cachename));
			fclose($fp);
			$dmsoundexlist = unserialize($fcontents);
			unset($fcontents);
			$cachecount = count($dmsoundexlist);
			return;
		}
		else {
			$dmsoundexlist = array();
			// clean up old cache
			$handle = opendir($INDEX_DIRECTORY);
			while (($file = readdir ($handle)) != false) {
				if ((substr($file, 0, 2) == "DM") && (substr($file, -4) == ".dat")) unlink($INDEX_DIRECTORY.$file);
			}
			closedir($handle);
			return;
		}
	}

	// Write the cache to disk after use. If nothing is added, just return.
	if ($option == "closecache") {
		if (count($dmsoundexlist) == $cachecount) return;
		$fp = @fopen($cachename, "w");
		if ($fp) {
			@fwrite($fp, serialize($dmsoundexlist));
			@fclose($fp);
			return;
		}
	}

	// Check if in cache
	$name = UTF8_strtoupper($name);
	$name = substr(trim($name), 0, 30);
	if (isset($dmsoundexlist[$name])) return $dmsoundexlist[$name];

	// Define the result array and set the first (empty) result
	$result = array();
	$result[0][0] = "";
	$rescount = 1;
	$nlen = strlen($name);
	$npos = 0;


	// Loop here through the characters of the name
	while($npos < $nlen) {
		// Check, per length of characterstring, if it exists in the array.
		// Start from max to length of 1 character
		$code = array();
		for ($i=$maxchar; $i>=0; $i--) {
			// Only check if not read past the last character in the name
			if (($npos + $i) <= $nlen) {
				// See if the substring exists in the coding array
				$element = substr($name,$npos,$i);
				// If found, add the sets of results to the code array for the letterstring
				if (isset($dmcoding[$element])) {
					$dmcount = count($dmcoding[$element]);
					// Loop here through the codesets
					// first letter? Then store the first digit.
					if ($npos == 0) {
						// Loop through the sets of 3
						for ($k=0; $k<$dmcount/3; $k++) {
							$c = $dmcoding[$element][$k*3];
							// store all results, cleanup later
							$code[] = $c;
						}
						break;
					}
					// before a vowel? Then store the second digit
					// Check if the code for the next letter exists
					if ((isset($dmcoding[substr($name, $npos + $i + 1)]))) {
						// See if it's a vowel
						if ($dmcoding[substr($name, $npos + $i + 1)] == 0) {
							// Loop through the sets of 3
							for ($k=0; $k<$dmcount/3; $k++) {
								$c = $dmcoding[$element][$k*3+1];
								// store all results, cleanup later
								$code[] = $c;
							}
							break;
						}
					}
					// Do this in all other situations
					for ($k=0; $k<$dmcount/3; $k++) {
						$c = $dmcoding[$element][$k*3+2];
						// store all results, cleanup later
						$code[] = $c;
					}
					break;
				}
			}
		}
		// Store the results and multiply if more found
		if (isset($dmcoding[$element])) {
			// Add code to existing results

			// Extend the results array if more than one code is found
			for ($j=1; $j<count($code); $j++) {
				$rcnt = count($result);
				// Duplicate the array
				for ($k=0; $k<$rcnt; $k++) {
					$result[] = $result[$k];
				}
			}

			// Add the code to the existing strings
			// Repeat for every code...
			for ($j=0; $j<count($code); $j++) {
				// and add it to the appropriate block of array elements
				for ($k=0; $k<$rescount; $k++) {
					$result[$j * $rescount + $k][] = $code[$j];
				}
			}
			$rescount=count($result);
			$npos = $npos + strlen($element);
		}
		else {
			// The code was not found. Ignore it and continue.
			$npos = $npos + 1;
		}
	}

	// Kill the doubles and zero's in each result
	// Do this for every result
	$ctr = count($result);
	for ($i=0; $i<$ctr; $i++) {
		$j=1;
		$res = $result[$i][0];
		// and check every code in the result.
		// codes are stored separately in array elements, to keep
		// distinction between 6 and 66.

		$cti = count($result[$i]);
		while($j<$cti) {

//  Zeroes to remain in the Soundex result
			if ((($result[$i][$j-1] != $result[$i][$j]) && ($result[$i][$j] != -1)) || $result[$i][$j] == 0) {

				$res .= $result[$i][$j];
			}
			$j++;
		}
		// Fill up to 6 digits and store back in the array
		$result[$i] = substr($res."000000", 0, 6);
	}

	// Kill the double results in the array
	$result=array_flip(array_flip($result));

	// Store in cache and return
	$dmsoundexlist[$name] = $result;
	return $result;
}

?>
