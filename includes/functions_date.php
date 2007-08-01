<?php
/**
 * Date Functions that can be used by any page in PGV
 *
 * The functions in this file are common to all PGV pages and include date conversion
 * routines and sorting functions.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2006  John Finlay and Others
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

if ($CALENDAR_FORMAT=="hijri" || $CALENDAR_FORMAT=="arabic") {
	require_once("includes/functions_date_hijri.php");
}

// Removed Hebrew check because one can have Hebrew disabled but the Gedcom can contain
// Hebrew dates.
// I have turned this check back on to save on memory - enabling the USE_RTL_FUNCTIONS will cause this to be included
if ((stristr($CALENDAR_FORMAT, "hebrew")!==false) || (stristr($CALENDAR_FORMAT, "jewish")!==false) || $USE_RTL_FUNCTIONS) {
	require_once("includes/functions_date_hebrew.php");
}

// The PHP date functions are not always available.
// The Gregorian function doesn't work with BC dates.
// The Julian function tries to be too clever with BC dates.
// The French function doesn't work for dates after year 14 (which were used).
// Hence we write our own.
// See http://en.wikipedia.org/wiki/Julian_day
// See http://en.wikipedia.org/wiki/French_Republican_Calendar
function MyGregorianToJD($month, $day, $year) {
	$a=floor((14-$month)/12); $y=$year+4800-$a; $m=$month+12*$a-3;
	return $day+floor((153*$m+2)/5)+365*$y+floor($y/4)-floor($y/100)+floor($y/400)-32045;
}
function MyJulianToJD($month, $day, $year) {
	$a=floor((14-$month)/12); $y=$year+4800-$a; $m=$month+12*$a-3;
	return $day+floor((153*$m+2)/5)+365*$y+floor($y/4)-32083;
}
function MyFrenchToJD($month, $day, $year) {
	return 2375474+$day+($month-1)*30+$year*365+floor($year/4);
}

// Convert a decimal number to roman numerals
function to_roman($num) {
	$lookup=array(1000=>'M', '900'=>'CM', '500'=>'D', 400=>'CD', 100=>'C', 90=>'XC', 50=>'L', 40=>'XL', 10=>'X', 9=>'IX', 5=>'V', 4=>'IV', 1=>'I');
  if ($num<1) return $num;
	$roman='';
	foreach ($lookup as $key=>$value) while ($num>=$key) {
			$roman.=$value;
			$num-=$key;
		}
	return $roman;
}

// Convert a roman numeral to decimal
function from_roman($roman) {
	$lookup=array(1000=>'M', '900'=>'CM', '500'=>'D', 400=>'CD', 100=>'C', 90=>'XC', 50=>'L', 40=>'XL', 10=>'X', 9=>'IX', 5=>'V', 4=>'IV', 1=>'I');
	$num=0;
	foreach ($lookup as $key=>$value)
		if (strpos($roman, $value)===0) {
			$num+=$key;
			$roman=substr($roman, strlen($value));
		}
	return $num;
}

/**
 * convert a date to other languages or formats
 *
 * converts and translates a date based on the selected language and calendar format
 * @param string $dstr_beg prepend this string to the converted date
 * @param string $dstr_end append the string to the converted date
 * @param int $day the day of month for the date
 * @param string $month the abbreviated month (ie JAN, FEB, MAR, etc)
 * @param int $year the year (ie 1900, 2004, etc)
 * @return string the new converted date
 */
function convert_date($dstr_beg, $dstr_end, $day, $month, $year) {
	global $pgv_lang, $DATE_FORMAT, $LANGUAGE, $CALENDAR_FORMAT, $monthtonum, $TEXT_DIRECTION;
	$altDay=30;

	$month = trim($month);
	$day = trim($day);
	$skipday = false;
	$skipmonth = false;
	if (empty($month)||!isset($monthtonum[strtolower($month)])) {
		$dstr_beg .= " ".$month." ";
		$month = "jan";
		$skipmonth=true;
	}
	if (empty($day)) {
		$day = 1;
		if ((!empty($month))&&(isset($monthtonum[$month]))){
			$yy = $year;
			//-- make sure there is always a year
			if (empty($yy)) $yy = date("Y");
			if (function_exists("cal_days_in_month")) $altDay = cal_days_in_month(CAL_GREGORIAN, $monthtonum[$month], $yy);
			else $altDay = 30;
		}
		$skipday = true;
	}
	if ($CALENDAR_FORMAT=="jewish" && $LANGUAGE != "hebrew" && !empty($year) && ! (preg_match("/^\d+$/", $year)==0)) {
		$month = $monthtonum[$month];
		$jd = gregoriantojd($month, $day, $year);
		$hebrewDate = jdtojewish($jd);
		list ($hebrewMonth, $hebrewDay, $hebrewYear) = split ('/', $hebrewDate);
		$altJd = gregoriantojd($month, $altDay, $year);
		$altHebrewDate = jdtojewish($altJd);
		list ($altHebrewMonth, $altHebrewDay, $altHebrewYear) = split ('/', $altHebrewDate);

		$hebrewMonthName = getJewishMonthName($hebrewMonth, $hebrewYear);
		if($skipday && !$skipmonth && $altHebrewMonth !=0 && $altHebrewYear !=0 && $hebrewMonth != $altHebrewMonth && $hebrewYear != $altHebrewYear) { //elul tishrai
			$hebrewMonthName .= " ";
			$hebrewMonthName .= $hebrewYear;
			$hebrewYear = " / ";
			$hebrewYear .= getJewishMonthName($altHebrewMonth, $altHebrewYear);
			$hebrewYear .= " ";
			$hebrewYear .= $altHebrewYear;
		} else if($skipday && !$skipmonth && $altHebrewMonth !=0 && $hebrewMonth != $altHebrewMonth) {
			$hebrewMonthName .= " / ";
			$hebrewMonthName .= getJewishMonthName($altHebrewMonth, $altHebrewYear);
		} else if($altHebrewYear !=0 && $hebrewYear != $altHebrewYear && $skipday) {
			$hebrewYear .= " / ";
			$hebrewYear .= $altHebrewYear;
		}
		if ($skipday) $hebrewDay = "";
		if ($skipmonth) $hebrewMonthName = "";
		if ($DATE_FORMAT == "D. M Y" && $skipday)
			 $newdate = preg_replace("/D/", $hebrewDay, "D M Y");
		else $newdate = preg_replace("/D/", $hebrewDay, $DATE_FORMAT);
		$newdate = preg_replace("/M/", $hebrewMonthName, $newdate);
		$newdate = preg_replace("/Y/", $hebrewYear, $newdate);
		$datestr = $dstr_beg . $newdate . $dstr_end;
	}
	else if ($CALENDAR_FORMAT=="jewish_and_gregorian" && $LANGUAGE != "hebrew" && !empty($year) && ! (preg_match("/^\d+$/", $year)==0)) {
		$monthnum = $monthtonum[$month];
		$jd = gregoriantojd($monthnum, $day, $year);
		$hebrewDate = jdtojewish($jd);
		list ($hebrewMonth, $hebrewDay, $hebrewYear) = split ('/', $hebrewDate);

		$altJd = gregoriantojd($monthnum, $altDay, $year);
		$altHebrewDate = jdtojewish($altJd);
		list ($altHebrewMonth, $altHebrewDay, $altHebrewYear) = split ('/', $altHebrewDate);
		$hebrewMonthName = getJewishMonthName($hebrewMonth, $hebrewYear);

		if($skipday && !$skipmonth && $altHebrewMonth !=0 && $altHebrewYear !=0 && $hebrewMonth != $altHebrewMonth && $hebrewYear != $altHebrewYear ) { //elul tishrai
			$hebrewMonthName .= " ";
			$hebrewMonthName .= $hebrewYear;
			$hebrewYear = " / ";
			$hebrewYear .= getJewishMonthName($altHebrewMonth, $altHebrewYear);
			$hebrewYear .= " ";
			$hebrewYear .= $altHebrewYear;
		} else if($skipday && !$skipmonth && $altHebrewMonth !=0 && $hebrewMonth != $altHebrewMonth) {
			$hebrewMonthName .= " / ";
			$hebrewMonthName .= getJewishMonthName($altHebrewMonth, $altHebrewYear);
		} else if($altHebrewYear !=0 && $hebrewYear != $altHebrewYear && $skipday) {
			$hebrewYear .= " / ";
			$hebrewYear .= $altHebrewYear;
		}

		if ($skipday) $hebrewDay = "";
		if ($skipmonth) $hebrewMonthName = "";
		if (!empty($year)) {
		if ($DATE_FORMAT == "D. M Y" && $skipday)
			 $newdate = preg_replace("/D/", $hebrewDay, "D M Y");
		else $newdate = preg_replace("/D/", $hebrewDay, $DATE_FORMAT);
		$newdate = preg_replace("/M/", $hebrewMonthName, $newdate);
		$newdate = preg_replace("/Y/", $hebrewYear, $newdate);
		}
		else $newdate="";
		if ($skipday) $day = "";
		if ($skipmonth) $month = "";
		if ($DATE_FORMAT == "D. M Y" && $skipday)
			 $gdate = preg_replace("/D/", $day, "D M Y");
		else $gdate = preg_replace("/D/", $day, $DATE_FORMAT);
		$gdate = preg_replace("/M/", $month, $gdate);
		$gdate = preg_replace("/Y/", $year, $gdate);
		$gdate = trim($gdate);
		$datestr = $dstr_beg . $gdate . " ($newdate)" . $dstr_end;
	}
	else if (($CALENDAR_FORMAT=="hebrew" || ($CALENDAR_FORMAT=="jewish" && $LANGUAGE == "hebrew")) && !empty($year) && ! (preg_match("/^\d+$/", $year)==0)) {

		$month = $monthtonum[$month];
		$jd = gregoriantojd($month, $day, $year);
		$hebrewDate = jdtojewish($jd);
		list ($hebrewMonth, $hebrewDay, $hebrewYear) = split ('/', $hebrewDate);

		$altJd = gregoriantojd($month, $altDay, $year);
		$altHebrewDate = jdtojewish($altJd);
		list ($altHebrewMonth, $altHebrewDay, $altHebrewYear) = split ('/', $altHebrewDate);

		if ($skipday) $hebrewDay = "";
		if ($skipmonth) $hebrewMonth = "";
		$newdate = getFullHebrewJewishDates($hebrewYear, $hebrewMonth, $hebrewDay, $altHebrewYear, $altHebrewMonth);
		$datestr = $dstr_beg . $newdate . $dstr_end;
	}
	else if (($CALENDAR_FORMAT=="hebrew_and_gregorian" || ($CALENDAR_FORMAT=="jewish_and_gregorian" && $LANGUAGE == "hebrew")) && !empty($year) && ! (preg_match("/^\d+$/", $year)==0)) {
		$monthnum = $monthtonum[$month];
		//if (preg_match("/^\d+$/", $year)==0) $year = date("Y");
		$jd = gregoriantojd($monthnum, $day, $year);
		$hebrewDate = jdtojewish($jd);
		list ($hebrewMonth, $hebrewDay, $hebrewYear) = split ('/', $hebrewDate);

		$altJd = gregoriantojd($monthnum, $altDay, $year);
		$altHebrewDate = jdtojewish($altJd);
		list ($altHebrewMonth, $altHebrewDay, $altHebrewYear) = split ('/', $altHebrewDate);

		if ($skipday) $hebrewDay = "";
		if ($skipmonth) $hebrewMonth = "";
		if (!empty($year)) $newdate = getFullHebrewJewishDates($hebrewYear, $hebrewMonth, $hebrewDay, $altHebrewYear, $altHebrewMonth);
		else $newdate = "";
		if ($skipday) $day = "";
		if ($skipmonth) $month = "";
		if ($DATE_FORMAT == "D. M Y" && $skipday)
			 $gdate = preg_replace("/D/", $day, "D M Y");
		else $gdate = preg_replace("/D/", $day, $DATE_FORMAT);
		$gdate = preg_replace("/M/", $month, $gdate);
		$gdate = preg_replace("/Y/", $year, $gdate);
		$gdate = trim($gdate);
		$datestr = $dstr_beg  . " ". $newdate . " ($gdate) ". $dstr_end;
	}
	else if ($CALENDAR_FORMAT=="julian") {
		$monthnum = $monthtonum[$month];
		$jd = gregoriantojd($monthnum, $day, $year);
		$jDate = jdtojulian($jd);
		list ($jMonth, $jDay, $jYear) = split ('/', $jDate);
		$jMonthName = jdmonthname ( $jd, 3);
		if ($skipday) $jDay = "";
		if ($skipmonth) $jMonthName = "";
		$newdate = preg_replace("/D/", $jDay, $DATE_FORMAT);
		$newdate = preg_replace("/M/", $jMonthName, $newdate);
		$newdate = preg_replace("/Y/", $jYear, $newdate);
		$datestr = $dstr_beg . $newdate . $dstr_end;
	}
	else if ($CALENDAR_FORMAT=="hijri") {
		$monthnum = $monthtonum[$month];
		$hDate = getHijri($day, $monthnum, $year);
		list ($hMonthName, $hDay, $hYear) = split ('/', $hDate);
		if ($skipday) $hDay = "";
		if ($skipmonth) $hMonthName = "";
		$newdate = preg_replace("/D/", $hDay, $DATE_FORMAT);
		$newdate = preg_replace("/M/", $hMonthName, $newdate);
		$newdate = preg_replace("/Y/", $hYear, $newdate);
		$datestr = $dstr_beg . '<span dir="rtl" lang="ar-sa">'.$newdate . '</span>';
		if($TEXT_DIRECTION == "ltr") { //only do this for ltr languages
	  		$datestr .= getLRM(); //add entity to return to left to right direction
	  	}
		$datestr .= $dstr_end;
	}
	else if ($CALENDAR_FORMAT=="arabic") {
		$monthnum = $monthtonum[$month];
		$aDate = getArabic($day, $monthnum, $year);
		list ($aMonthName, $aDay, $aYear) = split ('/', $aDate);
		if ($skipday) $aDay = "";
		if ($skipmonth) $aMonthName = "";
		$newdate = preg_replace("/D/", $aDay, $DATE_FORMAT);
		$newdate = preg_replace("/M/", $aMonthName, $newdate);
		$newdate = preg_replace("/Y/", $aYear, $newdate);
		$datestr = $dstr_beg . '<span dir="rtl" lang="ar-sa">'.$newdate . '</span>';
		if($TEXT_DIRECTION == "ltr") { //only do this for ltr languages
	  		$datestr .= getLRM(); //add entity to return to left to right direction
	  	}
		$datestr .= $dstr_end;
	}
	else if ($CALENDAR_FORMAT=="french") {
		$monthnum = $monthtonum[$month];
		$jd = gregoriantojd($monthnum, $day, $year);
		$frenchDate = jdtofrench($jd);
		list ($fMonth, $fDay, $fYear) = split ('/', $frenchDate);
		$fMonthName = jdmonthname ( $jd, 5);
		if ($skipday) $fDay = "";
		if ($skipmonth) $fMonthName = "";
		$newdate = preg_replace("/D/", $fDay, $DATE_FORMAT);
		$newdate = preg_replace("/M/", $fMonthName, $newdate);
		$newdate = preg_replace("/Y/", $fYear, $newdate);
		$datestr = $dstr_beg . $newdate . $dstr_end;
	}
	else {
		$temp_format = "~".$DATE_FORMAT;
		if ($skipday)
		{
		  //-- if the D is before the M the get the substr of everthing after the M
		  //-- if the D is after the M then just replace it
		  //-- @TODO figure out how to replace D. anywhere in the string
		  $pos1 = strpos($temp_format, "M");
		  $pos2 = strpos($temp_format, "D");
		  if ($pos2<$pos1) $temp_format = substr($temp_format, $pos1-1);
		  else $temp_format = preg_replace("/D/", "", $temp_format);
		}
		if ($skipmonth)
		{
		  $month = "";
		  $dpos_d_01 = strpos($temp_format, "M");
		  $dpos_d_00 = $dpos_d_01;
		  $dpos_d_02 = strlen($temp_format);
		  if ($dpos_d_01>0)
		  {
			while (!strpos("DY",$temp_format[$dpos_d_01]))
			{
			  $temp_format01 = substr($temp_format,0,$dpos_d_00);
			  $temp_format02 = substr($temp_format,$dpos_d_01);
			  $temp_format = $temp_format01.$temp_format02;
			  $dpos_d_02 = strlen($temp_format);
			  $dpos_d_01++;
			  if ($dpos_d_01 >= $dpos_d_02) break;
			}
		  }
		}
		$newdate = trim(substr($temp_format,1));

		$newdate = preg_replace("/D/", $day, $newdate);
		$newdate = preg_replace("/M/", $month, $newdate);
		$newdate = preg_replace("/Y/", $year, $newdate);
		$datestr = $dstr_beg . $newdate . $dstr_end;
	}
	return $datestr;
}

/**
 * parse a gedcom date
 *
 * this function will parse a gedcom date and convert it to the form defined by the language file
 * by calling the convert_date function
 * @param string $datestr the date string (ie everything after the DATE tag)
 * @return string the new date string
 */
function get_changed_date($datestr, $linebr=false) {
	global $pgv_lang, $DATE_FORMAT, $LANGUAGE, $CALENDAR_FORMAT, $monthtonum, $dHebrew;
	global $USE_RTL_FUNCTIONS; //--- required??
	global $CalYear;   //-- Hebrew calendar year

	$checked_dates = array();

	$datestr = trim($datestr);

	// INFANT CHILD STILLBORN DEAD DECEASED Y AUG ...
	if (preg_match("/\d/", $datestr)==0) 	{

		if (isset($pgv_lang[$datestr])) return $pgv_lang[$datestr];
		if (isset($pgv_lang[str2upper($datestr)])) return $pgv_lang[str2upper($datestr)];
		if (isset($pgv_lang[str2lower($datestr)])) return $pgv_lang[str2lower($datestr)];

	    if (stristr($datestr, "#DHEBREW")) {

			$datestr = preg_replace("/@([#A-Z]+)@/", "", $datestr);
			$pdate = parse_date($datestr);

// hebrew double dates with month only
			$a1=0;
			$a2=0;
			$a3=0;
			if (str2upper($pdate[0]["ext"])=="BETAND") {
				$a1=8;
				$a2=9;
				$a3=8;
			}
			else if (str2upper($pdate[0]["ext"])=="FROMTO") {
				$a1=9;
				$a2=10;
				$a3=7;
			}
			if ($a1 != 0) {
				$pdate = parse_date(substr($datestr,0,$a1));
				$tmp = $pgv_lang[str2lower($pdate[0]["ext"])]." ";
				if (isset($pdate[0]["mon"])) {
					if (!function_exists("getJewishMonthName")) require_once("includes/functions_date_hebrew.php");
		   			if ($LANGUAGE=="hebrew") $tmp .= getHebrewJewishMonth($pdate[0]["mon"], $CalYear);
		   			else                     $tmp .= getJewishMonthName($pdate[0]["mon"], $CalYear);
	   			}
	   			$pdate = parse_date(substr($datestr,$a2,$a3));
				$tmp .= " ".$pgv_lang[str2lower($pdate[0]["ext"])]." ";
				if (isset($pdate[0]["mon"])) {
					if (!function_exists("getJewishMonthName")) require_once("includes/functions_date_hebrew.php");
		   			if ($LANGUAGE=="hebrew") $tmp .= getHebrewJewishMonth($pdate[0]["mon"], $CalYear);
		   			else                     $tmp .= getJewishMonthName($pdate[0]["mon"], $CalYear);
	   			}
			}

			else {
				if (isset($pgv_lang[$pdate[0]["ext"]])) 				$tmp = $pgv_lang[$pdate[0]["ext"]]." ";
				else if (isset($pgv_lang[str2upper($pdate[0]["ext"])])) $tmp = $pgv_lang[str2upper($pdate[0]["ext"])]." ";
				else if (isset($pgv_lang[str2lower($pdate[0]["ext"])])) $tmp = $pgv_lang[str2lower($pdate[0]["ext"])]." ";
				else if ($pdate[0]["ext"]=="") $tmp = "";
	   	 		else return $datestr;

	   			if (isset($pdate[0]["mon"])) {
					if (!function_exists("getJewishMonthName")) require_once("includes/functions_date_hebrew.php");
		   			if ($LANGUAGE=="hebrew") $tmp .= getHebrewJewishMonth($pdate[0]["mon"], $CalYear);
		   			else                     $tmp .= getJewishMonthName($pdate[0]["mon"], $CalYear);

// bet-and/from-to  one Hebrew one Gregorian - the gregorian is filled with a (wrong) Hebrew month
// the Hebrew year is translated to the current year

		   		}
	    		else return $datestr;
    		}
        	return $tmp;
		}
		// abt Aug
		else {
		$pdate = parse_date($datestr);

// double dates with month only
		$a1=0;
		$a2=0;
		$a3=0;
		if (str2upper($pdate[0]["ext"])=="BETAND") {
			$a1=8;
			$a2=8;
			$a3=8;
		}
		else if (str2upper($pdate[0]["ext"])=="FROMTO") {
			$a1=9;
			$a2=9;
			$a3=7;
		}
		if ($a1 != 0) {
			$pdate = parse_date(substr($datestr,0,$a1));

			$tmp = $pgv_lang[str2lower($pdate[0]["ext"])]." ";
			if (isset($pgv_lang[$pdate[0]["month"]])) 				  $tmp .= " ".$pgv_lang[$pdate[0]["month"]];
			else if (isset($pgv_lang[str2upper($pdate[0]["month"])])) $tmp .= " ".$pgv_lang[str2upper($pdate[0]["month"])];
			else if (isset($pgv_lang[str2lower($pdate[0]["month"])])) $tmp .= " ".$pgv_lang[str2lower($pdate[0]["month"])];
	   		$pdate = parse_date(substr($datestr,$a2,$a3));

			$tmp .= " ".$pgv_lang[str2lower($pdate[0]["ext"])]." ";
			if (isset($pgv_lang[$pdate[0]["month"]])) 				  $tmp .= " ".$pgv_lang[$pdate[0]["month"]];
			else if (isset($pgv_lang[str2upper($pdate[0]["month"])])) $tmp .= " ".$pgv_lang[str2upper($pdate[0]["month"])];
			else if (isset($pgv_lang[str2lower($pdate[0]["month"])])) $tmp .= " ".$pgv_lang[str2lower($pdate[0]["month"])];
		}

		else {
			if (isset($pgv_lang[$pdate[0]["ext"]])) 				$tmp = $pgv_lang[$pdate[0]["ext"]];
			else if (isset($pgv_lang[str2upper($pdate[0]["ext"])])) $tmp = $pgv_lang[str2upper($pdate[0]["ext"])];
			else if (isset($pgv_lang[str2lower($pdate[0]["ext"])])) $tmp = $pgv_lang[str2lower($pdate[0]["ext"])];
			else return $datestr;
			if (isset($pgv_lang[$pdate[0]["month"]])) 				  $tmp .= " ".$pgv_lang[$pdate[0]["month"]];
			else if (isset($pgv_lang[str2upper($pdate[0]["month"])])) $tmp .= " ".$pgv_lang[str2upper($pdate[0]["month"])];
			else if (isset($pgv_lang[str2lower($pdate[0]["month"])])) $tmp .= " ".$pgv_lang[str2lower($pdate[0]["month"])];
			else return $datestr;
		}
        return $tmp;
		}
	}

	// need day of the week ?
	if (!strpos($datestr, "#") && (strpos($DATE_FORMAT, "F") or strpos($DATE_FORMAT, "d") or strpos($DATE_FORMAT, "j"))) {
		$dateged = "";
		$pdate = parse_date($datestr);

		$i=0;
		while (!empty($pdate[$i]["year"])) {
			$day = @$pdate[$i]["day"];
			$mon = @$pdate[$i]["mon"];
			$year = $pdate[$i]["year"];
			if (!empty($day)) {
				if (!defined('ADODB_DATE_VERSION')) require_once("adodb-time.inc.php");
				$fmt = $DATE_FORMAT; // D j F Y
				$fmt = str_replace("R", "", $fmt); // R = french Revolution date
				$adate = adodb_date($fmt, adodb_mktime(0, 0, 0, $mon, $day, $year));
			}
			else if (!empty($mon)) $adate=$pgv_lang[strtolower($pdate[$i]["month"])]." ".$year;
			else $adate=$year;
			// already in english !
			if ($LANGUAGE!="english") {
				foreach (array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December") as $indexval => $item) {
					// February => Février
					$translated = $pgv_lang[substr(strtolower($item),0,3)];
					$adate = str_replace($item, $translated, $adate);
					// Feb => Fév
					$item = substr($item, 0, 3);
					$translated = substr($translated, 0, 3);
					$adate = str_replace($item, $translated, $adate);
				}
				foreach (array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday") as $indexval => $item) {
					// Friday => Vendredi
					$translated = $pgv_lang[strtolower($item)];
					$adate = str_replace($item, $translated, $adate);
					// Fri => Ven
					$item = substr($item, 0, 3);
					$translated = substr($translated, 0, 3);
					$adate = str_replace($item, $translated, $adate);
				}
			}
			// french first day of month
			if ($LANGUAGE=="french") $adate = str_replace(" 1 ", " 1er ",$adate);
			// french calendar from 22 SEP 1792 to 31 DEC 1805
			if (!empty($day) and strpos($DATE_FORMAT, "R") and function_exists("gregoriantojd")) {
				if ( (1792<$year and $year<1806) or ($year==1792 and ($mon>9 or ($mon==9 and $day>21)))) {
					$jd = gregoriantojd($mon, $day, $year);
					$frenchDate = jdtofrench($jd);
					list ($fMonth, $fDay, $fYear) = split ('/', $frenchDate);
					$fMonthName = jdmonthname ($jd, 5);
					$adate = "<u>$fDay $fMonthName An $fYear</u>".($linebr ? "<br />" : " ").$adate;
				}
			}
			if (isset($pdate[$i]["ext"])) {
				$txt = strtolower($pdate[$i]["ext"]);
				if (isset($pgv_lang[$txt])) $txt = $pgv_lang[$txt];
				else $txt = $pdate[$i]["ext"];
				$adate = $txt. " ". $adate . " ";
			}
			$dateged .= $adate;
			$i++;
		}
		if (!empty($dateged)) return trim($dateged);
	}

	//-- Is the date a Hebrew date
	if (stristr($datestr, "#DHEBREW")) {
		 $dHebrew=1;
		 $datestr = preg_replace("/@([#A-Z]+)@/", "", $datestr);

	}
	else $dHebrew=0;
	//-- check for DAY MONTH YEAR dates

	$Dt = "";
	$ct = preg_match_all("/(\d{1,2}\s)?([a-zA-Z]{3})?\s?(\d{4})?/", $datestr, $match, PREG_SET_ORDER);

	for($i=0; $i<$ct; $i++) {
		$match[$i][0] = trim($match[$i][0]);
		if ((!empty($match[$i][0]))&&(!in_array($match[$i][0], $checked_dates))) {
			if (!empty($match[$i][1])) $day = trim($match[$i][1]);
			else $day = "";
			if (!empty($match[$i][2])) $month = strtolower($match[$i][2]);
			else $month = "";

			if (isset($monthtonum[$month])&&(preg_match("/".$month."[a-z]/i", $datestr)==0)) {
								$checked_dates[] = $match[$i][0];
				if (!empty($match[$i][3])) $year = $match[$i][3];
				else $year = "";
				$pos1 = strpos($datestr, $match[$i][0]);
				$pos2 = $pos1 + strlen($match[$i][0]);
				$dstr_beg = substr($datestr, 0, $pos1);
				$dstr_end = substr($datestr, $pos2);
				//-- sometimes with partial dates a space char is found in the match and not added to the dstr_beg string
				//-- the following while loop will check for spaces at the start of the match and add them to the dstr_beg
				$j=0;
				while(($j<strlen($match[$i][0]))&&($match[$i][0]{$j}==" ")) {
					$dstr_beg.=" ";
					$j++;
				}
				//<-- Day zero-suppress
				if ($day > 0 && $day < 10) $day = preg_replace("/0/", ""."\$1", $day);
				if (!$dHebrew) {
					$datestr = convert_date($dstr_beg, $dstr_end, $day, $month, $year);
					if ($day != "") $Dt = $day;
				}
				else {
					if (!function_exists("convert_hdate")) require_once("includes/functions_date_hebrew.php");
					$datestr = convert_hdate($dstr_beg, $dstr_end, $day, $month, $year);

					$Dt = "";
				}
			}
			else $month="";
		}
	}
	if (!isset($month)) $month="";
	//-- search for just years because the above code will only allow dates with a valid month to pass
	//-- this will make sure years get converted for non romanic alphabets such as hebrew
	$ct = preg_match_all("/.?(\d\d\d\d)/", $datestr, $match, PREG_SET_ORDER);

	if ((stristr($CALENDAR_FORMAT, "hebrew")!==false) || (stristr($CALENDAR_FORMAT, "jewish")!==false) || ($dHebrew)) { // check if contain hebrew dates then heared also with hebrew date!!!!

		$checked_dates_str = implode(",", $checked_dates);
		for($i=0; $i<$ct; $i++) {
			$match[$i][0] = trim($match[$i][0]);
			if ((!empty($match[$i][0]))&&(stristr($checked_dates_str, $match[$i][0])===false)&&(strstr($match[$i][0], "#")===false)) {
				$checked_dates_str .= ", ".$match[$i][0];
				$day = "";
				$month = "";
				$year = $match[$i][1];

				if ($year<4000 || $dHebrew) {
					$pos1 = strpos($datestr, $match[$i][0]);
					$pos2 = $pos1 + strlen($match[$i][0]);
					$dstr_beg = substr($datestr, 0, $pos1);
					$dstr_end = substr($datestr, $pos2);
					if (!function_exists("convert_hdate")) require_once("includes/functions_date_hebrew.php");
					if (!$dHebrew) $datestr = convert_date($dstr_beg, $dstr_end, $day, $month, $year);
					else $datestr = convert_hdate($dstr_beg, $dstr_end, $day, $month, $year);
				}
			}
		}
	}
	else if ($CALENDAR_FORMAT=="hijri") {
		$checked_dates_str = implode(",", $checked_dates);
		for($i=0; $i<$ct; $i++) {
			$match[$i][0] = trim($match[$i][0]);
			if ((!empty($match[$i][0]))&&(stristr($checked_dates_str, $match[$i][0])===false)&&(strstr($match[$i][0], "#")===false)&&(stristr($datestr, $match[$i][0]."</span>")===false)) {
				$checked_dates_str .= ", ".$match[$i][0];
				$day = "";
				$month = "";
				$year = $match[$i][1];
				//if ($year<4000) {
					$pos1 = strpos($datestr, $match[$i][0]);
					$pos2 = $pos1 + strlen($match[$i][0]);
					$dstr_beg = substr($datestr, 0, $pos1);
					$dstr_end = substr($datestr, $pos2);
					$datestr = convert_date($dstr_beg, $dstr_end, $day, $month, $year);
				//}
			}
		}
	}

	if ($LANGUAGE == "turkish") $datestr = getTurkishDate($datestr);
	else {
	if ($LANGUAGE == "finnish") $datestr = getFinnishDate($datestr, $Dt);
	else {
		$array_short = array("jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec", "abt", "aft", "and", "bef", "bet", "cal", "est", "from", "int", "to", "cir", "apx");
		foreach($array_short as $indexval => $value){
			$datestr = preg_replace("/(\W)$value([^a-zA-Z])/i", "\$1".$pgv_lang[$value]."\$2", $datestr);
			$datestr = preg_replace("/^$value([^a-zA-Z])/i", $pgv_lang[$value]."\$1", $datestr);
		}
	  }
	}
	return $datestr;
}

/**
 * create an anchor url to the calendar for a date
 *
 * create an anchor url to the calendar for a date and parses the date using the get changed date
 * function
 * @author Roland (botak)
 * @param string $datestr the date string (ie everything after the DATE tag)
 * @return string a converted date with anchor html tags around it <a href="">date</a>
 */
function get_date_url($datestr){
	global $monthtonum, $USE_RTL_FUNCTIONS;
	global $CalYear;   //-- Hebrew calendar year

//TODO Do not create a url link for dates as stillborn, infant, child, dead, deceased, Y
//TODO Do not create a today url link for dates from the monthly Day not set box, inform editors about the error

    if (!stristr($datestr, "#DHEBREW") || $USE_RTL_FUNCTIONS) {

//		Commented out 2 lines as I don't know why they are here
//		$datestrip = preg_replace("/[a-zA-Z]/", "", $datestr);
//		$datestrip = trim($datestrip);

		$checked_dates = array();
		$tmpdatelink = "";
		//-- added trim to datestr to fix broken links produced by matches to a single space char
		$ct = preg_match_all("/(\d{1,2}\s)?([a-zA-Z]{3})?\s?(\d{3,4})?/", trim($datestr), $match, PREG_SET_ORDER);

		for($i=0; $i<$ct; $i++) {
			$tmp = strtolower(trim($datestr));
			if (substr($tmp,0,3)=="bet" || (substr($tmp,0,4)=="from" && substr(stristr($tmp, "to"),0,3)=="to ")) {
			// Checks if date is bet(ween)..and or from..to
				$cb = preg_match_all("/ (\d\d\d\d|\d\d\d)/", trim($datestr), $match_bet, PREG_SET_ORDER);
                    if ($USE_RTL_FUNCTIONS && stristr($datestr, "#DHEBREW")) {

                    	$hebdate = get_date_url_hebrew($datestr);
                    	$action = $hebdate["action"];
                    	$start_day = $hebdate["start_day"];
                    	$end_day = $hebdate["end_day"];
                    	$start_month = $hebdate["start_month"];
                    	$end_month = $hebdate["end_month"];
                    	$start_year = $hebdate["start_year"];
                    	$end_year = $hebdate["end_year"];
                    }
                    else {

						if (!empty($match_bet[0][0])) $start_year = trim($match_bet[0][0]);
						else $start_year = "";
						if (!empty($match_bet[1][0])) $end_year = trim($match_bet[1][0]);
						else $end_year = "";
						if ($start_year>$end_year){
							$datelink = $start_year;
							$start_year = $end_year;
							$end_year = $datelink;
						}
						if ($start_year !="" || $end_year != "")
							$action = "year";
						else {
                    	$cm = preg_match_all("/([a-zA-Z]{2,4})?\s?(\d{1,2}\s)?([a-zA-Z]{3})?\s?(\d{3,4})?/", trim($datestr), $match_bet, PREG_SET_ORDER);

							if (empty($match_bet[0][2]) && empty($match_bet[1][2]))
								 $action = "calendar";
							else $action = "today";

						    if (!empty($match_bet[0][2])) $start_day = trim($match_bet[0][2]);
						    else $start_day = "";
						    if (!empty($match_bet[0][3])) $start_month = trim($match_bet[0][3]);
						    else $start_month = "";
						    if (!empty($match_bet[1][2])) $end_day = trim($match_bet[1][2]);
						    else $end_day = "";
						    if (!empty($match_bet[1][3])) $end_month = trim($match_bet[1][3]);
						    else $end_month = "";
						}
					}
					$datelink = "<a class=\"date\" href=\"calendar.php?";
					If ($action == "year" && ((isset($start_year) && strlen($start_year)>0) ||
					    (isset($end_year) && strlen($end_year)>0))) {

						if (isset($start_year) && strlen($start_year)>0) $datelink .= "year=".$start_year;
						if (isset($end_year) && strlen($end_year)>0 && isset($start_year) && strlen($start_year)>0 && $start_year!=$end_year)
						   $datelink .= "-";
				    	if (!isset($start_year) || strlen($start_year)==0 && isset($end_year) && strlen($end_year) > 0) $datelink .= "year=";
						if (isset($end_year) && strlen($end_year) > 0 && $start_year!=$end_year) $datelink .= $end_year;
					}
					else if ($action == "today" || $action == "calendar") {

					  if (isset($start_day) && strlen($start_day) > 0 && isset($start_month) && strlen($start_month) > 0) {

						if (isset($start_year) && strlen($start_year) > 0)  $datelink .= "year=".$start_year;
						else if (isset($end_year) && strlen($end_year) > 0) $datelink .= "year=".$end_year;
						if ($action == "today") 							$datelink .= "&day=".$start_day;
				    	$datelink .= "&month=".$start_month;
					  }
					  else if (isset($end_day) && strlen($end_day) > 0 && isset($end_month) && strlen($end_month) > 0) {

						if (isset($end_year) && strlen($end_year) > 0) $datelink .= "year=".$end_year;
						if ($action == "today") $datelink .= "&day=".$end_day;
					    $datelink .= "&month=".$end_month;
				      }
				      else if (isset($start_month) && strlen($start_month) > 0) $datelink .= "&month=".$start_month;
				    }
					$datelink .= "&amp;filterof=all&amp;action=".$action."\">";
                    if (isset($match_bet[5][4])	&& isset($match_bet[11][4])) {
						if (trim($match_bet[5][0])==trim($match_bet[5][4]) && trim($match_bet[11][0])==trim($match_bet[11][4])) {

							$tmp       = get_changed_date($match_bet[0][1]." @#DHEBREW@ ".$match_bet[5][0]);
							$tmp      .= " ".get_changed_date($match_bet[6][1]." @#DHEBREW@ ".$match_bet[11][0]);
							$datelink .= $tmp."</a>";
						}
			        }
 			        $datelink .= get_changed_date($datestr)."</a>";
			}
			else {
				$match[$i][0] = trim($match[$i][0]);

				if ((!empty($match[$i][0]))&&(!in_array($match[$i][0], $checked_dates))) {
					$checked_dates[] = $match[$i][0];
					if (!empty($match[$i][1])) $day = trim($match[$i][1]);
					else $day = "";

					if (isset($match[$i][2])) $tmpmnth = strtolower($match[$i][2]);
					if ((isset($tmpmnth) && isset($monthtonum[$tmpmnth])) || $tmpdatelink=="")
						if (!empty($tmpmnth)) $month = $tmpmnth;
						else $month = "";
					if (!isset($monthtonum[$month])) $month=""; // abt and ust (of august) are not a month !

					if (!empty($match[$i][3])) $year = $match[$i][3];
					else $year = "";

					if ($tmpdatelink=="") {
						if (!empty($day) && !empty($month)) 	$tmpdatelink  = "today";
						else if (!empty($year))   				$tmpdatelink  = "year";
						     else if (!empty($month))   		$tmpdatelink  = "calendar";
						          else 							$tmpdatelink  = "";
						$tmplink = "\">";
					}

					if (stristr($datestr, "#DHEBREW") && $USE_RTL_FUNCTIONS) {
						    $dateheb = array();
						    if ($day!="")      			$date[0]["day"]   = $day;
						    else if ($tmpdatelink=="calendar") $date[0]["day"]   = '30';
						         else                   $date[0]["day"]   = '01';
						    if ($month>0 && $month<14)  $date[0]["mon"]   = $month;
						    else if ($month!="")    	$date[0]["mon"]   = $monthtonum[str2lower($month)];
 							     else               	$date[0]["mon"]   = '01';
 							if (!empty($year)) 		    $date[0]["year"]  = $year;
 							else if (!empty($CalYear) && $tmpdatelink=="today") $date[0]["year"] = $CalYear;
 							if ($tmpdatelink=="year" && $day=="" && !empty($year)) {
	 							                        $date[1]["day"]   = '30';
	 							    	                $date[1]["mon"]   = '13';
 														$date[1]["year"]  = $year;
						    }

                            if (!empty($date[0]["year"]))
                                                		$dateheb = jewishGedcomDateToGregorian($date);
                            else                  		$dateheb = jewishGedcomDateToCurrentGregorian($date);

    						if (!empty($dateheb[0]["day"]))
    													$day 	= $dateheb[0]["day"];
    						else                        $day     = "";
    						if (!empty($dateheb[0]["month"]))
    													$month   = $dateheb[0]["month"];
    						else                        $month   = "";
    						if (!empty($dateheb[0]["year"]))
    													$year    = $dateheb[0]["year"];
    						else                        $year    = "";
					}
					$datelink = "<a class=\"date\" href=\"calendar.php?";
					if (isset($day) && strlen($day) > 0) 	 $datelink 	.= "day=".$day."&amp;";
					if (isset($month) && strlen($month) > 0) $datelink 	.= "month=".$month."&amp;";
					if (isset($year) && strlen($year) > 0)   $datelink 	.= "year=".$year;
					if (isset($dateheb[1]["year"]) && $year!=$dateheb[1]["year"] && $tmpdatelink=="year")
															 $datelink .= "-".$dateheb[1]["year"]."&amp;";
					else 									 $datelink .= "&amp;";
					$datelink .= "filterof=all&amp;action=";
					$datelink .= $tmpdatelink.$tmplink;
					$datelink .= get_changed_date($datestr)."</a>";
				}
			}
		}
		if (!isset($datelink)) $datelink="";
		return $datelink;
	}
	else {
		$datelink = get_changed_date($datestr);
		return $datelink;
	}
}

/**
 * get an individuals age at the given date
 * @param string $indirec the individual record so that we can get the birth date
 * @param string $datestr the date string (everything after DATE) to calculate the age for
 * @param string $style optional style (default 1=HTML style)
 * @return string the age in a string
 */
function get_age($indirec, $datestr, $style=1) {
	global $pgv_lang,$monthtonum, $USE_RTL_FUNCTIONS;
	$estimates = array("abt","aft","bef","est","cir");
	$realbirthdt="";
	$bdatestr = "";

	$min_birt_jd=0; $max_birt_jd=0; // Earliest/latest dates for the birth.
	$min_even_jd=0; $max_even_jd=0; // Earliest/latest dates for the event.
	$approx=false;

	// If the person has no birth, try a christening/baptism date instead.
	$btag='1 BIRT';
	if (strpos($indirec, $btag)===false) $btag='1 CHR';
	if (strpos($indirec, $btag)===false) $btag='1 BAPM';
	if (strpos($indirec, $btag)===false) return '';

	// A Gedcom date can indicate a range (JAN => 1 JAN-31 JAN and 2000 => 1 JAN-31-DEC
	// It can also be an explicit range (JUN-JUL => 1 JUN-31-JUL)
	// Multiple dates may also be present.
	$index = 1;
	$birthrec = get_sub_record(1, $btag, $indirec, $index);
	while(!empty($birthrec)) {
		if (preg_match("/2 DATE (.+)/", $birthrec, $match)) {
			$date=parse_date($match[1]);
			if ($date[0]['jd1']>0) {
				if (!empty($date[0]['ext']) && empty($date[1])) // A date range is not an approximation
					$approx=true;
				if ($min_birt_jd==0)
					$min_birt_jd=$date[0]['jd1'];
				else
					$min_birt_jd=min($min_birt_jd, $date[0]['jd1']);
				$max_birt_jd=max($max_birt_jd, $date[0]['jd2']);
			}
			if (!empty($date[1]))
				$max_birt_jd=max($max_birt_jd, $date[1]['jd2']);
		}
		$index++;
		$birthrec = get_sub_record(1, $btag, $indirec, $index);
	}
	if ($min_birt_jd==0)
		return;
	if ($max_birt_jd==0)
		$max_birt_jd=$min_birt_jd;

	$date=parse_date($datestr);
	if ($date[0]['jd1']>0) {
		if (!empty($date[0]['ext']) && empty($date[1])) // A date range is not an approximation
			$approx=true;
		$min_even_jd=$date[0]['jd1'];
		$max_even_jd=$date[0]['jd2'];
	}
	if (!empty($date[1]))
		$max_even_jd=max($max_even_jd, $date[1]['jd2']);

	if ($min_even_jd==0)
		return;
	if ($max_even_jd==0)
		$max_even_jd=$min_even_jd;

	// We now have earliest/latest possible dates for both the birth and the event.
  $min_age=max(0,$min_even_jd - $max_birt_jd);
	$max_age=$max_even_jd - $min_birt_jd;

	// Convert to days/months/years/etc.  NB - this is not perfect, as
	// the birth/event dates may be in different calendars.
	if (abs($max_age)<30) { // show in days
		if ($min_age==$max_age)
			if ($max_age==0)
				$age=""; // do not print "Age: 0 days"
			else if ($max_age==1)
				$age="1 {$pgv_lang['day1']}";
			else
				$age=$max_age." {$pgv_lang['days']}";
		else
			$age=$min_age."-".$max_age." {$pgv_lang['days']}";
	} else if (abs($max_age)<731) { // show in months
		$min_age=floor($min_age/30.4);
		$max_age=floor($max_age/30.4);
		if ($min_age==$max_age)
			if ($max_age-$min_age==1)
				$age="1 {$pgv_lang['month1']}";
			else
				$age=$max_age." {$pgv_lang['months']}";
		else
			$age=$min_age."-".$max_age." {$pgv_lang['months']}";
	} else { // show in years
		$min_age=floor($min_age/365.25);
		$max_age=floor($max_age/365.25);
		if ($min_age==$max_age)
			if ($max_age-$min_age==1)
				$age="1 {$pgv_lang['year1']}";
			else
				$age=$max_age; //"$max_age {$pgv_lang['years']}";
		else
			$age=$min_age."-".$max_age; // {$pgv_lang['years']}";
	}

	if ($approx && !strpos($age, "-")) $age.=" {$pgv_lang['apx']}";
	if ($style)  $age=" <span class=\"age\">({$pgv_lang['age']} {$age})</span>";
	return $age;
}

/**
 * translate gedcom age string
 *
 * Examples:
 * 4y 8m 10d.
 * Chi
 * INFANT
 *
 * @param string $agestring gedcom AGE field value
 * @return string age in user language
 * @see http://homepages.rootsweb.com/~pmcbride/gedcom/55gcch2.htm#AGE_AT_EVENT
 */
function get_age_at_event($agestring) {
	global $pgv_lang;

	$age = "";
	$match = explode(" ", strtolower($agestring));
	for ($i=0; $i<count($match); $i++) {
		$txt = trim($match[$i]);
		$txt = trim($txt, ".");
		if ($txt=="chi") $txt="child";
		if ($txt=="inf") $txt="infant";
		if ($txt=="sti") $txt="stillborn";
		if (isset($pgv_lang[$txt])) $age.=$pgv_lang[$txt];
		else {
			$n = trim(substr($txt,0,-1));
			$u = substr($txt,-1,1);
			if ($u=="y") {
				$age.= " ".$n." ";
				if ($n == 1) $age .= $pgv_lang["year1"];
				else $age .= $pgv_lang["years"];
			}
			else if ($u=="m") {
				$age.= " ".$n." ";
				if ($n == 1) $age .= $pgv_lang["month1"];
				else $age .= $pgv_lang["months"];
			}
			else if ($u=="d") {
				$age.= " ".$n." ";
				if ($n == 1) $age .= $pgv_lang["day1"];
				else $age .= $pgv_lang["days"];
			}
			else $age.=" ".$txt;
		}
	}
	return $age;
}

// Parse a date into DAY/MONTH/YEAR (for the calendar) and JULIAN_DAY (for sorting)
// Performance is important, as this function can be called many thousands of times per page.
// Note that complex regexes take time to parse initially, but will be very quick when they
// are subsequently used.
function parse_date($date)
{
	$date=preg_replace('/\(.*/',         '',  $date); // Bracketed text at end of CAL type dates
	$date=preg_replace('/[^\d\w\s#@]+/', ' ', $date); // Punctuation
	$date=preg_replace('/\s+/',          ' ', $date); // Multiple spaces
	$date=preg_replace('/(^ |\r| $)/',   '',  $date); // Leading/trailing whitespace
	$date=str2upper($date);
	// Some applications wrongly prefix the entire date string with a calendar escape, rather
	// than prefixing each individual date.
	// e.g. "@#DJULIAN@ BET 1520 AND 1530" instead of "BET @#DJULIAN@ 1520 AND @#DJULIAN@ 1530"
	if (preg_match('/^(@#D[A-Z ]+@) (FROM|BET) (.+) (AND|TO) (.+)/', $date, $match))
		return array(parse_single_gedcom_date("{$match[2]} {$match[1]} {$match[3]}"), parse_single_gedcom_date("{$match[4]} {$match[1]} {$match[5]}"));
	else if (preg_match('/^(@#D[A-Z ]+@) (FROM|BET|TO|AND|BEF|AFT|CAL|EST|INT|ABT|APX|EST|CIR) (.+)/', $date, $match))
		return array(parse_single_gedcom_date("{$match[2]} {$match[1]} {$match[3]}"));
	else if (preg_match('/^((FROM|BET) .+) ((AND|TO) .+)/', $date, $match))
		return array(parse_single_gedcom_date($match[1]), parse_single_gedcom_date($match[3]));
	else
		return array(parse_single_gedcom_date($date));
}

// This function should only be called by parse_date().
function parse_single_gedcom_date($date)
{
	// Lookup table - only contruct once.
	static $month_to_num=NULL;
	if ($month_to_num==NULL)
		$month_to_num=array(
			''=>0, 'JAN'=>1, 'FEB'=>2, 'MAR'=>3, 'APR'=>4, 'MAY'=>5, 'JUN'=>6,
			'JUL'=>7, 'AUG'=>8, 'SEP'=>9, 'OCT'=>10, 'NOV'=>11, 'DEC'=>12,
			'VEND'=>1, 'BRUM'=>2, 'FRIM'=>3, 'NIVO'=>4, 'PLUV'=>5, 'VENT'=>6,
			'GERM'=>7, 'FLOR'=>8, 'PRAI'=>9, 'MESS'=>10, 'THER'=>11, 'FRUC'=>12,
			'COMP'=>13, 'TSH'=>1, 'CSH'=>2, 'KSL'=>3, 'TVT'=>4, 'SHV'=>5, 'ADR'=>6,
			'ADS'=>7, 'NSN'=>8, 'IYR'=>9, 'SVN'=>10, 'TMZ'=>11, 'AAV'=>12, 'ELL'=>13
		);

	// Extract components from a reasonably well-formed date
	if (preg_match('/^ ?((?P<EXT>FROM|TO|BET|AND|AFT|BEF|EST|INT|CAL|ABT|APX|EST|CIR) ?)?((?P<CAL>@#D[A-Z ]+@) ?)?((((?P<DAY>\d{1,2}) ?)?(?P<MONTH>(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC|VEND|BRUM|FRIM|NIVO|PLUV|VENT|GERM|FLOR|PRAI|MESS|THER|FRUC|COMP|TSH|CSH|KSL|TVT|SHV|ADR|ADS|NSN|IYR|SVN|TMZ|AAV|ELL)) ?)?(?P<YEAR>\b(-?\d{1,4}|((AN )?(?P<FYEAR>[CLXVI]+))))(?P<GYEAR>( \d\d)?)(?P<BC> ?B ?C)?)( ?$)/', $date, $match)) {
		$parsed=array(
			'cal'=>$match['CAL'],
			'ext'=>$match['EXT'],
			'day'=>($match['DAY']=='') ? 0 : (int)$match['DAY'],
			'month'=>$match['MONTH'],
			'mon'=>$month_to_num[$match['MONTH']],  // FEB => 2
			'year'=>($match['BC']=='') ? (int)$match['YEAR'] : 1-$match['YEAR'],  // 1BC=0, 2BC=-1, 3BC=-2, etc.
			'jd1'=>0,
			'jd2'=>0
		);

		// Guess at calendar, if not specified
		if ($match['CAL']=='')
			if (preg_match('/^(VEND|BRUM|FRIM|NIVO|PLUV|VENT|GERM|FLOR|PRAI|MESS|THER|FRUC|COMP)$/', $match['MONTH']))
				$parsed['cal']='@#DFRENCH R@';
			else if (empty($match['MONTH']) && $match['YEAR']>3000 || preg_match('/^(TSH|CSH|KSL|TVT|SHV|ADR|ADS|NSN|IYR|SVN|TMZ|AAV|ELL)$/', $match['MONTH']))
				$parsed['cal']='@#DHEBREW@';
		// TODO? else if year <1582 (or <1?) then @#DJULIAN@

		// Allow french republican years in roman numerals.
		// TODO: should we really allow/encourage this?
		if (!empty($match['FYEAR'])) {
			$parsed['cal']='@#DFRENCH R@';
			$parsed['year']=from_roman($match['FYEAR']);
		}

		// Calculate Julian Day for ease of date sorting.  Single dates can be ranges.
		// i.e. JAN2000 => 1JAN2000-31JAN2000
		$y1=$parsed['year'];
		$m1=$parsed['mon']==0 ? 1 : $parsed['mon'];
		$d1=$parsed['day']==0 ? 1 : $parsed['day'];
		switch ($parsed['cal']) {
		case '':
		case '@#DGREGORIAN@':
			$parsed['jd1']=MyGregorianToJD($m1, $d1, $y1);
			if ($parsed['mon']==0)
				$parsed['jd2']=MyGregorianToJD($m1, $d1, $y1+1)-1;
			else
				if ($parsed['day']==0) {
					if ($m1<12)
						$parsed['jd2']=MyGregorianToJD($m1+1, 1, $y1)-1;
					else
						$parsed['jd2']=MyGregorianToJD(1, 1, $y1+1)-1;
				} else
					$parsed['jd2']=$parsed['jd1'];
			break;
		case '@#DJULIAN@':
			$parsed['jd1']=MyJulianToJD($m1, $d1, $y1);
			if ($parsed['mon']==0)
				$parsed['jd2']=MyJulianToJD($m1, $d1, $y1+1)-1;
			else
				if ($parsed['day']==0) {
					if ($m1<12)
						$parsed['jd2']=MyJulianToJD($m1+1, 1, $y1)-1;
					else
						$parsed['jd2']=MyJulianToJD(1, 1, $y1+1)-1;
				} else
					$parsed['jd2']=$parsed['jd1'];
			break;
		case '@#DFRENCH R@':
			$parsed['jd1']=MyFrenchToJD($m1, $d1, $y1);
			if ($parsed['mon']==0)
				$parsed['jd2']=MyFrenchToJD($m1, $d1, $y1+1)-1;
			else
				if ($parsed['day']==0) {
					if ($m1<12)
						$parsed['jd2']=MyFrenchToJD($m1+1, 1, $y1)-1;
					else
						$parsed['jd2']=MyFrenchToJD(1, 1, $y1+1)-1;
				} else
					$parsed['jd2']=$parsed['jd1'];
			break;
		case '@#DHEBREW@':
			$parsed['jd1']=JewishToJD($m1, $d1, $y1);
			if ($parsed['mon']==0)
			$parsed['jd2']=JewishToJD($m1, $d1, $y1+1)-1;
			else
				if ($parsed['day']==0) {
					if ($m1<13) {
						if (!isAJewishLeapYear($y1) && $m1==6)
							$parsed['jd2']=JewishToJD(8, 1, $y1)-1;
						else
							$parsed['jd2']=JewishToJD($m1+1, 1, $y1)-1;
					} else
						$parsed['jd2']=JewishToJD(1, 1, $y1+1)-1;
				} else
					$parsed['jd2']=$parsed['jd1'];
			break;
		}
	} else { // Not a valid date (e.g. "ABT 14 MAR").  Pick out any bits we can, for linking to the calendar.
		$parsed=array('ext'=>'', 'cal'=>'', 'day'=>0, 'mon'=>0, 'month'=>'', 'year'=>0, 'jd1'=>0, 'jd2'=>0, 'text'=>$date);
		// CAL
		if (preg_match('/(@#.+@)/', $date, $m))
			$parsed['cal' ]=$m[1];
		if (preg_match('/\b(VEND|BRUM|FRIM|NIVO|PLUV|VENT|GERM|FLOR|PRAI|MESS|THER|FRUC|COMP)\b/', $date))
			$parsed['cal']='@#DFRENCH R@';
		if (preg_match('/\b(TSH|CSH|KSL|TVT|SHV|ADR|ADS|NSN|IYR|SVN|TMZ|AAV|ELL)\b/', $date))
			$parsed['cal']='@#DHEBREW@';
		// EXT
		if (preg_match('/\b(FROM|TO|BET|AND|AFT|BEF|EST|INT|CAL|ABT|APX|EST|CIR)\b/', $date, $m))
			$parsed['ext']=$m[1];
		// YEAR
		if (preg_match('/\b(\d\d\d\d)\b/', $date, $m)) {
			$parsed['year']=$m[1];
		}
		// MONTH or DAY-MONTH
		if (preg_match('/((\d{1,2}) )?(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC|VEND|BRUM|FRIM|NIVO|PLUV|VENT|GERM|FLOR|PRAI|MESS|THER|FRUC|COMP|TSH|CSH|KSL|TVT|SHV|ADR|ADS|NSN|IYR|SVN|TMZ|AAV|ELL)$/', $date, $m) && !empty($month_to_num[$m[3]])) {
			$parsed['day']=(int)$m[1];
			$parsed['month']=$m[3];
			$parsed['mon']=$month_to_num[$parsed['month']];
		}
	}

	// Dispite the name, this is used for filtering, rather than sorting.
	$parsed['sort']=sprintf("%04d-%02d-%02d", $parsed['year'], $parsed['mon'], $parsed['day']);

	return $parsed;
}

function isAJewishLeapYear($year) {
	$tmp=$year%19;
	return ($tmp==0 || $tmp==3 || $tmp==6 || $tmp==8 || $tmp==11 || $tmp==14 || $tmp==17);
}

/**
 * Parse a time string into its different parts
 * @param string $timestr	the time as it was taken from the TIME tag
 * @return array	returns an array with the hour, minutes, and seconds
 */
function parse_time($timestr)
{
	$time = preg_split("/:/", $timestr);
	$time['hour'] = $time[0];
	$time['minutes'] = $time[1];
	$time['seconds'] = $time[2];

	return $time;
}

// This pair of functions converts between the internal gedcom date and the
// text that the user sees when editing a date on a form.
// They can be overridden by the presence of gedcom_to_edit_date_XX() in
// includes/extras/functions.XX.php
function default_gedcom_to_edit_date($datestr)
{
	// Don't do too much here - it will annoy experienced PGV users.
	// Maybe just remove calendar escapes, which we will be able to automatically
	// recreate?
	return $datestr;
}

function default_edit_to_gedcom_date($datestr)
{
	global $pgv_lang;
	// The order of these keywords is significant, to avoid partial matches.  In particular:
	// ads:adr_leap_year:adr to prevent "Adar" matching "Adar Sheni" or "Adar I" matching "Adar II"
	// \b prevents the german JULI matching @#DJULIAN@, etc.

	foreach (array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec','vend','brum','frim','nivo','pluv','vent','germ','flor','prai','mess','ther','fruc','comp','tsh','csh','ksl','tvt','shv','nsn','iyr','svn','tmz','aav','ell','abt','aft','and','bef','bet','cal','est','from','int','to','b.c.') as $keyword)
		$datestr=preg_replace("/\b".str_replace('.','[.]?',$pgv_lang[$keyword])."\b/i", $keyword, $datestr);

	foreach (array('ads','adr_leap_year','adr','january_1st','february_1st','march_1st','april_1st','may_1st','june_1st','july_1st','august_1st','september_1st','october_1st','november_1st','december_1st') as $keyword)
		$datestr=preg_replace("/\b".str_replace('.','[.]?',$pgv_lang[$keyword])."\b/i", substr($keyword,0,3), $datestr);

	// APX and CIR are not gedcom 5.5.1 keywords
	foreach (array('apx','cir') as $keyword)
		$datestr=preg_replace("/\b".str_replace('.','[.]?',$pgv_lang[$keyword])."\b/i", 'abt', $datestr);

	return $datestr;
}


?>
