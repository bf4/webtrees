<?php
/**
 * Various functions used by the language editor of PhpGedView
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @subpackage Admin
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_FUNCTIONS_EDITLANG_PHP', '');

//-----------------------------------------------------------------
function add_backslash_before_dollarsign($dstring) {
	$dummy = str_replace(chr(92) . chr(36), chr(36), $dstring);
	$dummy = str_replace(chr(36), chr(92) . chr(36), $dummy);
	return $dummy;
}

//-----------------------------------------------------------------
function crlf_lf_to_br($dstring) {
	$dummy = str_replace("\r\n", "<br />", $dstring);
	$dummy = str_replace("\n", "<br />", $dummy);
	return $dummy;
}

//-----------------------------------------------------------------
function mask_all($dstring) {
	$dummy = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $dstring);
	return $dummy;
}

//-----------------------------------------------------------------
function unmask_all($dstring) {
	$dummy = str_replace(array('&lt;', '&gt;', '&amp;'), array('<', '>', '&'), $dstring);
	return $dummy;
}

//-----------------------------------------------------------------
function LockFile($Temp_Filename) {
	while (file_exists($Temp_Filename . ".tmp")) {clearstatcache();} # wait till file is deleted
	$fp = fopen($Temp_Filename . ".tmp", "w");
	fclose($fp); # temp-file to block all access to $Filename
}

//-----------------------------------------------------------------
function UnLockFile($Temp_Filename) {
	while (!@unlink($Temp_Filename . ".tmp")) {clearstatcache();} # remove file block
}




//-----------------------------------------------------------------
function read_complete_file_into_array($dFileName, $string_needle) {
	global $file_type, $language2, $lang_shortcut;

	if (!is_array($string_needle)) $array_needle = array($string_needle);
	else $array_needle = $string_needle;

	$Filename =  $dFileName;
	LockFile($Filename);

	$LineCounter = 0;
	$InfoArray = array();
	$dFound = ($fp = @fopen($Filename, "r"));

	if (!$dFound) {
		$dUserRealName = getUserFullName(PGV_USER_ID);
		$Language2 = ucfirst($language2);

		switch ($file_type) {
		case "lang":
		case "admin":
		case "editor":
			$comment1 = "$Language2 Language file for PhpGedView.";
			$comment2 = "// -- Define $Language2 texts for use on various pages";
			break;
		case "facts":
			$comment1 = "$Language2 Language file for PhpGedView.";
			$comment2 = "// -- Define a fact array to map GEDCOM tags with their $Language2 values";
			break;
		case "configure_help":
			$comment1 = "$Language2 Language file for PhpGedView.";
			$comment2 = "//-- Define $Language2 Help texts for use on Configuration pages";
			break;
		case "help_text":
			$comment1 = "$Language2 Language file for PhpGedView.";
			$comment2 = "//-- Define $Language2 Help texts for use on various pages";
			break;
		case "countries":
			$comment1 = "$Language2 Language file for PhpGedView.";
			$comment2 = "//-- Define $Language2 name equivalents for Chapman country codes";
			break;
		case "faqlist":
			$comment1 = "$Language2 FAQ file for PhpGedView.";
			$comment2 = "//-- Define $Language2 Frequently Asked Questions";
			break;
		case "extra":
			$comment1 = "$Language2 extra definitions file for PhpGedView.";
			$comment2 = "//-- Define $Language2 extra definitions";
			break;
		case "rs_lang":
			$comment1 = "$Language2 Language file for PhpGedView Researchlog";
			$comment2 = '// -- RS GENERAL MESSAGES';
			break;
		default:
			$comment1 = 'This should never happen';
			$comment2 = '';
			break;
		}

		$dFound = ($fp = @fopen($Filename, "w"));
		fwrite($fp, "<?php".PGV_EOL);
		fwrite($fp, "/**".PGV_EOL);
		fwrite($fp, " * $comment1".PGV_EOL);
		fwrite($fp, " *".PGV_EOL);
		fwrite($fp, " * PhpGedView: Genealogy Viewer".PGV_EOL);
		fwrite($fp, " * Copyright (C) 2002 to ".date("Y")."  PGV Development Team.  All rights reserved".PGV_EOL);
		fwrite($fp, " *".PGV_EOL);
		fwrite($fp, " * This program is free software; you can redistribute it and/or modify".PGV_EOL);
		fwrite($fp, " * it under the terms of the GNU General Public License as published by".PGV_EOL);
		fwrite($fp, " * the Free Software Foundation; either version 2 of the License, or".PGV_EOL);
		fwrite($fp, " * (at your option) any later version.".PGV_EOL);
		fwrite($fp, " *".PGV_EOL);
		fwrite($fp, " * This program is distributed in the hope that it will be useful,".PGV_EOL);
		fwrite($fp, " * but WITHOUT ANY WARRANTY; without even the implied warranty of".PGV_EOL);
		fwrite($fp, " * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the".PGV_EOL);
		fwrite($fp, " * GNU General Public License for more details.".PGV_EOL);
		fwrite($fp, " *".PGV_EOL);
		fwrite($fp, " * You should have received a copy of the GNU General Public License".PGV_EOL);
		fwrite($fp, " * along with this program; if not, write to the Free Software".PGV_EOL);
		fwrite($fp, " * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA".PGV_EOL);
		fwrite($fp, " *".PGV_EOL);
		fwrite($fp, " * @package PhpGedView".PGV_EOL);
		fwrite($fp, " * @author ".$dUserRealName."".PGV_EOL);
		fwrite($fp, " * @created ".date("Y")."-".date("m")."-".date("d").PGV_EOL);
		fwrite($fp, " * @version \$Id\$".PGV_EOL);
		fwrite($fp, " */".PGV_EOL);
		fwrite($fp, PGV_EOL);
		fwrite($fp, "if (stristr(\$_SERVER[\"SCRIPT_NAME\"], basename(__FILE__))!==false) {".PGV_EOL);
		fwrite($fp, "	print \"You cannot access a language file directly.\";".PGV_EOL);
		fwrite($fp, "	exit;".PGV_EOL);
		fwrite($fp, "}".PGV_EOL);
		fwrite($fp, PGV_EOL);
		fwrite($fp, "$comment2".PGV_EOL);
		fwrite($fp, PGV_EOL);
		fwrite($fp, "?>".PGV_EOL);
		fclose($fp);

		$dFound = ($fp = @fopen($Filename, "r"));
	}

	if ($dFound) {
		$inComment = false;		// Indicates whether we're skipping from "/*" to "*/"
		$slashStar = "/*";
		$starSlash = "*/";
		while (!feof($fp)) {
			$line = fgets($fp, (6 * 1024));

			if (!$inComment) {
				if (substr($line, 0, 2) == $slashStar) {
					$inComment = true;
				}
			}

			$foundNeedle = false;
			if (!$inComment) {
				foreach ($array_needle as $needle) {
					if (!$foundNeedle && $x = strpos(trim($line), $needle)) {
						if ($x == 1) {
							$line_mine = $line;
							$line = trim($line);
							$key = trim(substr($line, 0, strpos($line, "]") + 1));
							$ct = preg_match("/=\s*\"(.*)\"/", $line, $match);
							# if ($ct>0) $content = trim($match[1]);
							if ($ct>0) $content = $match[1];
							else $content = "";
							$InfoArray[$LineCounter][0] = $key;				// keystring
							# print "#".$key."# ";
							$InfoArray[$LineCounter][1] = $content;			// message of keystring
                	
							# print "#".$content."#<br />";
							if ($content != "") {
								$InfoArray[$LineCounter][2] = get_last_string($line_mine, $content);	// pos of the first char of the message
							}
							else $InfoArray[$LineCounter][2] = "";
                	
							$InfoArray[$LineCounter][3] = $line_mine;			// complete line
							$foundNeedle = true;
						}
					}
		    	}
	    	}
			if (!$foundNeedle) $InfoArray[$LineCounter][0] = $line;
			$LineCounter++;

			if (substr($line, 0, 2) == $starSlash) $inComment = false;
			if (substr(trim($line), -2) == $starSlash) $inComment = false;
		}
		fclose($fp);
	}
	else print "E R R O R !!!";
	# exit;

	UnLockFile($Filename);

	return $InfoArray;
}

//-----------------------------------------------------------------
function find_in_file($MsgNr, $dlang_file) {
	global $PGV_BASE_DIRECTORY;
	$openfilename =  $dlang_file;
	$my_array = @file($openfilename);

	$my_Dummy = $my_array[$MsgNr];

	$my_Dummy = trim(substr($my_Dummy, strpos($my_Dummy, "=") + 1));
	$my_Dummy = substr($my_Dummy, strpos($my_Dummy, "\"") + 1);
	$my_Dummy = substr($my_Dummy, 0, strrpos($my_Dummy, ";") - 1);

	return $my_Dummy;
}

//-----------------------------------------------------------------
function find_key_in_new_language_old($new_array, $string_needle) {
	$dummy = "";
	$dcount = 0;
	while ($new_array[$dcount] != "") {
		if ($new_array[$dcount][0] == $string_needle) return $dcount;
		$dcount++;
	}
	return false;
}

//-----------------------------------------------------------------
function write_array_into_file($dFileName01, $writeArray, $add_new_message_at_line, $new_message_string) {
	global $PGV_BASE_DIRECTORY;

	$Filename =  $dFileName01;
	LockFile($Filename);

	$LineCounter = 0;
	if ($fp = @fopen($Filename, "w")) {
		$could_write = true;
		foreach ($writeArray as $indexval => $var) {
			/* A new message which didn't exist before inside the language file */
			if ($LineCounter == $add_new_message_at_line) {
				fwrite($fp, $new_message_string.PGV_EOL);
				$LineCounter++;
			}
    	
			if (empty($var[1])) {
				if (isset($var[3])) {
					/* Message content is empty */
					fwrite($fp, $var[3]);
				} else {
					/* Outlined file content */
					fwrite($fp, $var[0]);
				}
			} else {
				$output = substr($var[3], 0, $var[2]).$var[1]."\";".PGV_EOL;
				fwrite($fp, $output);
			}
			$LineCounter++;
		}
		fclose($fp);
	} else $could_write = false;

	UnLockFile($Filename);
	return $could_write;
}

//-----------------------------------------------------------------
function read_export_file_into_array($dFileName, $string_needle) {

	if (!is_array($string_needle)) $array_needle = array($string_needle);
	else $array_needle = $string_needle;

	$Filename = $dFileName;

	$LineCounter = 0;
	$InfoArray = array();
	$dFound = ($fp = @fopen($Filename, "r"));

	if (!$dFound)  {
		print "Error file not found"; 
		exit;
	} else {
		$inComment = false;		// Indicates whether we're skipping from "/*" to "*/"
		$slashStar = "/*";
		$starSlash = "*/";
		while (!feof($fp)) {
			$line = fgets($fp, (6 * 1024));

			if (!$inComment) {
				if (substr($line, 0, 2) == $slashStar) {
					$inComment = true;
				}
			}
			if ($inComment) {
				$posnStarSlash = strpos($line, $starSlash);
				if ($posnStarSlash === false) continue;
				$inComment = false;
				if ($posnStarSlash != 0) continue;
				$line = substr($line, 2);
			}

			$foundNeedle = false;
			foreach ($array_needle as $needle) {
				if (!$foundNeedle && $x = strpos(trim($line), $needle)) {
					if ($x == 1) {
						$line_mine = $line;
						$line = trim($line);
						$key = trim(substr($line, 0, strpos($line, "]") + 1));
						$ct = preg_match("/=\s*\"(.*)\";/", $line, $match);
						if ($ct>0) $content = $match[1];
						else $content = "";
						$InfoArray[$LineCounter][0] = $key;				// keystring
						$InfoArray[$LineCounter][1] = $content;			// message of keystring
						$foundNeedle = true;
					}
					$LineCounter++;
				}
			}
		}
		fclose($fp);
	}
	return $InfoArray;
}
//-----------------------------------------------------------------
function get_last_string($hay, $need) {
//	$getLastStr = 0;
	$pos = strpos($hay, $need);
	if (is_int ($pos)) { //this is to decide whether it is "false" or "0"
//		while($pos) {
//			$getLastStr = $getLastStr + $pos + strlen($need);
//			$hay = substr ($hay , $pos + strlen($need));
//			$pos = strpos($hay, $need);
//		}
//		return $getLastStr - strlen($need);
		return $pos;
	} else {
		return -1; //if $need wasn't found it returns "-1" , because it could return "0" if it's found on position "0".
	}
}
//-----------------------------------------------------------------
function check_bom() {
	global $language_settings, $pgv_lang;
	$check = false;
	$fileList = array("pgv_language", "confighelpfile", "helptextfile", "factsfile", "adminfile", "editorfile", "countryfile");

	foreach ($language_settings as $key => $language) {
		// Check if language is active
		if ($language["pgv_lang_use"] == true) {
			// Check each language file
			foreach ($fileList as $key2 => $fileName) {
				if (!file_exists($language[$fileName])) {
					print "<span class=\"warning\">";
					print str_replace("#lang_filename#", substr($language[$fileName], 10), $pgv_lang["no_open"]) . "<br /><br />";
					print "</span>";
				} else {
					$str = file_get_contents($language[$fileName]);
					if (strpos($str, PGV_UTF8_BOM)===0) {
						$check = true;
						print "<span class=\"warning\">".$pgv_lang["bom_found"].substr($language[$fileName], 10).".</span>";
						print "<br />";
						$writetext = substr($str, strlen(PGV_UTF8_BOM));
						if (!$handle = @fopen($language[$fileName], "w")) {
							print "<span class=\"warning\">";
							print str_replace("#lang_filename#", substr($language[$fileName], 10), $pgv_lang["no_open"]) . "<br /><br />";
							print "</span>";
						} else {
							if (@fwrite($handle,$writetext) === false) {
										print "<span class=\"warning\">";
											print str_replace("#lang_filename#", substr($language[$fileName], 10), $pgv_lang["lang_file_write_error"]) . "<br /><br />";
											print "</span>";
								}
								@fclose($handle);
							}
					}
				}
			}
		}
	}
	if ($check == false) print $pgv_lang["bom_not_found"];
}

?>
