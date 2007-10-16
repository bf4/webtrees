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

require_once('includes/date_class.php');

/**
 * get an individuals age at the given date
 * @param string $indirec the individual record so that we can get the birth date
 * @param string $datestr the date string (everything after DATE) to calculate the age for
 * @param string $style optional style (default 1=HTML style)
 * @return string the age in a string
 */
function get_age($indirec, $datestr, $style=1) {
	global $pgv_lang;

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
				return ''; // do not print "Age: 0 days"
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

	$agestring=preg_replace(
		array(
			'/\bchi(ld)?\b/i',
			'/\binf(ant)?\b/i',
			'/\bsti(llborn)?\b/i',
			'/\b1y/i',
			'/(\d+)y/i',
			'/\b1m/i',
			'/(\d+)m/i',
			'/\b1d/i',
			'/(\d+)d/i'
		),
		array(
			$pgv_lang['child'],
			$pgv_lang['infant'],  
	 		$pgv_lang['stillborn'], 
			'1' /*'1 '.$pgv_lang['year1']*/, 
			'$1' /*'$1 '.$pgv_lang['years']*/,
	  	'1 '.$pgv_lang['month1'], 
	 		'$1 '.$pgv_lang['months'],
	  	'1 '.$pgv_lang['day1'],  
			'$1 '.$pgv_lang['days']
		),
		$agestring
	);
	if (!empty($agestring))
		$agestring="<span class=\"age\">{$agestring}</span>";
	return $agestring;
}

// This function is deprecated.  Use class GedcomDate instead.
function parse_date($date)
{
	$gdate=new GedcomDate($date);
	$pdate=array();
	$pdate[]=array(
		'ext'=>strtoupper($gdate->qual1),
		'cal'=>$gdate->date1->CALENDAR_ESCAPE,
		'day'=>$gdate->date1->d,
		'mon'=>$gdate->date1->m,
		'month'=>strtoupper($gdate->date1->NUM_TO_MONTH[$gdate->date1->m]),
		'year'=>$gdate->date1->y,
		'jd1'=>$gdate->date1->minJD,
		'jd2'=>$gdate->date1->maxJD
		);
	if (!preg_match("/{$gdate->date1->CALENDAR_ESCAPE}/", $date))
		$pdate[0]['cal']='';
	if (!is_null($gdate->date2)) {
		$pdate[]=array(
			'ext'=>strtoupper($gdate->qual2),
			'cal'=>$gdate->date2->CALENDAR_ESCAPE,
			'day'=>$gdate->date2->d,
			'mon'=>$gdate->date2->m,
			'month'=>strtoupper($gdate->date2->NUM_TO_MONTH[$gdate->date2->m]),
			'year'=>$gdate->date2->y,
			'jd1'=>$gdate->date2->minJD,
			'jd2'=>$gdate->date2->maxJD
		);
		if (!preg_match("/{$gdate->date2->CALENDAR_ESCAPE}/", $date))
			$pdate[1]['cal']='';
	}
	return $pdate;
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

////////////////////////////////////////////////////////////////////////////////
// This pair of functions converts between the internal gedcom date and the
// text that the user sees when editing a date on a form.
// They can be overridden by the presence of gedcom_to_edit_date_XX() in
// includes/extras/functions.XX.php
////////////////////////////////////////////////////////////////////////////////
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
		$datestr=preg_replace("/\b".str_replace('.','[.]?',$pgv_lang[$keyword])."\b/i", strtoupper($keyword), $datestr);

	foreach (array('ads','adr_leap_year','adr','jan_1st','feb_1st','mar_1st','apr_1st','may_1st','jun_1st','jul_1st','aug_1st','sep_1st','oct_1st','nov_1st','dec_1st') as $keyword)
		$datestr=preg_replace("/\b".str_replace('.','[.]?',$pgv_lang[$keyword])."\b/i", strtoupper(substr($keyword,0,3)), $datestr);

	// APX and CIR are not gedcom 5.5.1 keywords
	foreach (array('apx','cir') as $keyword)
		$datestr=preg_replace("/\b".str_replace('.','[.]?',$pgv_lang[$keyword])."\b/i", 'ABT', $datestr);

	return $datestr;
}

////////////////////////////////////////////////////////////////////////////////
// Convert a unix timestamp into a formated date-time value, for logs, etc.
// We can't just use date("$DATE_FORMAT- $TIME_FORMAT") as this doesn't
// support internationalisation.
// Don't attempt to convert into other calendars, as not all days start at
// midnight, and we can only get it wrong.
// Remove HTML tags, as the <span class="date"> wrappers apply to gedcom dates,
// not timestamps
////////////////////////////////////////////////////////////////////////////////
function format_timestamp($t=NULL) {
	global $DATE_FORMAT, $TIME_FORMAT;
	if (is_null($t))
		$t=client_time();
	$d=new GedcomDate(date('j M Y', $t));
	return strip_tags($d->Display(false, "{$DATE_FORMAT} -", array()).date(" {$TIME_FORMAT}", $t));
}

////////////////////////////////////////////////////////////////////////////////
// Get the current julian day on the client/server
////////////////////////////////////////////////////////////////////////////////
function server_jd() {
	static $today=NULL;
	if (is_null($today))
		$today=new GedcomDate(date('j M Y'));
	return $today->MinJD();
}
function client_jd() {
	static $today=NULL;
	if (is_null($today))
		$today=new GedcomDate(date('j M Y'), client_time());
	return $today->MinJD();
}

////////////////////////////////////////////////////////////////////////////////
// Get the current timestamp of the client, not the server
////////////////////////////////////////////////////////////////////////////////
function client_time() {
	if (isset($_SESSION["timediff"]))
		return time()-$_SESSION["timediff"];
	else
		return time();
}

?>
