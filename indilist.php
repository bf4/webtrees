<?php
/**
 * Individual List
 *
 * The individual list shows all individuals from a chosen gedcom file. The list is
 * setup in two sections. The alphabet bar and the details.
 *
 * The alphabet bar shows all the available letters users can click. The bar is built
 * up from the lastnames first letter. Added to this bar is the symbol @, which is
 * shown as a translated version of the variable <var>pgv_lang["NN"]</var>, and a
 * translated version of the word ALL by means of variable <var>$pgv_lang["all"]</var>.
 *
 * The details can be shown in two ways, with surnames or without surnames. By default
 * the user first sees a list of surnames of the chosen letter and by clicking on a
 * surname a list with names of people with that chosen surname is displayed.
 *
 * Beneath the details list is the option to skip the surname list or show it.
 * Depending on the current status of the list.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008 PGV Development Team.  All rights reserved.
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
 * $Id$
 * @package PhpGedView
 * @subpackage Lists
 */

require 'config.php';
require_once 'includes/functions_print_lists.php';

// We show three different lists:
$alpha   =safe_GET('alpha'); // All surnames beginning with this letter where "@"=unknown and ","=none
$surname =safe_GET('surname'); // All indis with this surname
$show_all=safe_GET('show_all', array('no','yes'), 'no'); // All indis

// Long lists can be broken down by given name
$falpha=safe_GET('falpha'); // All first names beginning with this letter
$show_all_firstnames=safe_GET('show_all_firstnames', array('no','yes'), 'no');

// We can show either a list of surnames or a list of names
$surname_sublist=safe_GET('surname_sublist', array('no','yes'));
if (!$surname_sublist) {
	$surname_sublist=safe_COOKIE('surname_sublist', array('no','yes'), 'yes');
}
setcookie('surname_sublist', $surname_sublist);

// Fetch a list of the initial letters of all surnames in the database
$initials=array_keys(get_indi_alpha());
if (! $initials) {
	$initials[]='@';
}

// Fetch the list of indis, and make sure selections are consistent.
// i.e. can't specify show_all and surname at the same time.
if ($show_all=='yes') {
	$alpha='';
	$surname='';
	$legend='';
	$indis=get_indi_list();
	$url='indilist.php?show_all=yes';
} elseif ($surname) {
	$surname=str2upper($surname);
	$alpha=get_first_letter($surname);
	$show_all='no';
	$legend=$surname;
	switch($falpha) {
	case '':
		break;
	case '@':
		$legend.=', '.$pgv_lang['NN'];
		break;
	default:
		$legend.=', '.$falpha;
		break;
	}
	$surname_sublist='no';
	$indis=get_surname_indis($surname);
	$url='indilist.php?surname='.urlencode($surname);
} else {
	// Can only select initial letters that are actually used.
	if (! in_array($alpha, $initials)) {
		$alpha=reset($initials);
	}
	$show_all='no';
	$surname='';
	if ($alpha=='@') {
		$legend=$pgv_lang['NN'];
		$surname_sublist='no';
		$surname='@N.N.';
	} elseif ($alpha==',') {
		$legend=$pgv_lang['none'];
		$surname_sublist='no';
	} else {
		$legend=$alpha;
	}
	$indis=get_alpha_indis($alpha);
	$url='indilist.php?alpha='.urlencode($alpha);
}

// Don't sublists short lists.
if (count($indis)<100) {
	$falpha='';
	$show_all_firstnames='no';
}

// If the total number of indis is small enough for search spiders to take in one
// hit, then send them straight there.
if ($SEARCH_SPIDER) {
	$surname_sublist=count($indis)>900 ? 'yes' : 'no';
}

print_header($pgv_lang['individual_list'].' : '.$legend);
echo '<h2 class="center">', $pgv_lang['individual_list'], '</h2>';

// Print a selection list of initial letters
$list=array();
foreach ($initials as $letter) {
	switch ($letter) {
	case '@':
		$html=$pgv_lang['NN'];
		break;
	case ',':
		$html=$pgv_lang['none'];
		break;
	default:
		$html=$letter;
		break;
	}
	if ($letter==$alpha && $show_all=='no') {
		if ($surname) {
			$html='<a href="indilist.php?alpha='.urlencode($letter).'" class="warning">'.$html.'</a>';
		} else {
			$html='<span class="warning">'.$html.'</span>';
		}
	} else {
		$html='<a href="indilist.php?alpha='.urlencode($letter).'">'.$html.'</a>';
	}
	$list[]=$html;
}
// Seach spiders don't get the "show all" option as the other links give them everything.
if (!$SEARCH_SPIDER) {
	if ($show_all=='yes') {
		$list[]='<span class="warning">'.$pgv_lang['all'].'</span>';
	} else {
		$list[]='<a href="indilist.php?show_all=yes">'.$pgv_lang['all'].'</a>';
	}
}
echo '<p class="center">';
print_help_link('alpha_help', 'qm', 'alpha_index');
echo ' ', join(' | ', $list), '</p>';

// Search spiders don't get an option to show/hide the surname sublists,
// not does it make sense on the all/unknown/surname views
if (!$SEARCH_SPIDER && $alpha!='@' && $alpha!=',' && !$surname) {
	echo '<p class="center">';
	if ($surname_sublist=='yes') {
		print_help_link('skip_sublist_help', 'qm', 'skip_surnames');
		echo '<a href="', $url, '&amp;surname_sublist=no">', $pgv_lang['skip_surnames'], '</a>';
	} else {
		print_help_link('skip_sublist_help', 'qm', 'show_surnames');
		echo '<a href="', $url, '&amp;surname_sublist=yes">', $pgv_lang['show_surnames'], '</a>';
	}
	print_help_link('name_list_help', 'qm');
	echo '</p>';
}

if ($surname_sublist=='yes') {
	// Show the surname list
	// Note that we count/display SPFX SURN, but sort/group under just SURN
	$surnames=array();
	foreach (array_keys($indis) as $pid) {
		$person=Person::getInstance($pid);
		foreach ($person->getAllNames() as $name) {
			$surn=reset(explode(',', $name['sort']));
			if ($surname && $surname==$surn || !$surname && $alpha==get_first_letter($surn)) {
				$spfxsurn=str2upper(reset(explode(',', $name['list'])));
				if (! array_key_exists($surn, $surnames)) {
					$surnames[$surn]=array();
				}
				if (! array_key_exists($spfxsurn, $surnames[$surn])) {
					$surnames[$surn][$spfxsurn]=array();
				}
				// $surn is the base surname, e.g. GOGH
				// $spfxsurn is the full surname, e.g. van GOGH
				// $pid allows us to count indis as well as surnames, for indis that
				// appear twice in this list.
				$surnames[$surn][$spfxsurn][$pid]=true;
			}
		}
	}
	uksort($surnames, 'compareStrings');
	print_surname_table($surnames, 'indilist');
} else {
	// Show the individual list
	// Note that each person is listed as many times as they have names that
	// match the search criteria.  e.g. Mary Black nee Brown is listed twice
	// on the "B" list.

	$individuals=array();
	$givn_initials=array();
	// Show the indi list
	foreach (array_keys($indis) as $pid) {
		$person=Person::getInstance($pid);
		foreach ($person->getAllNames() as $name) {
			if ($SHOW_MARRIED_NAMES || $name['type']!='_MARNM') {
				list($surn,$givn)=explode(',', $name['sort']);
				$givn_alpha=get_first_letter($givn);
				$givn_initials[$givn_alpha]=$givn_alpha;
				if ((!$surname || $surname==$surn) &&
				    (!$alpha   || $alpha==get_first_letter($name['sort'])) &&
				    (!$falpha  || $falpha==$givn_alpha)) {
					$individuals[]=array('gid'=>$pid, 'name'=>$name['sort']);
				}
			}
		}
	}
	uasort($givn_initials, 'stringsort');

	// Break long lists by initial letter of given name
	if (count($indis)>100) {
		if (!$falpha && $show_all_firstnames=='no') {
			// If we didn't specify initial or all, filter by the first initial
			$falpha=reset($givn_initials);
			$legend.=', '.$falpha;
			foreach ($individuals as $key=>$value) {
				if (strpos($value['name'], ','.$falpha)===false) {
					unset($individuals[$key]);
				}
			}
		}
		$list=array();
		foreach ($givn_initials as $givn_initial) {
			switch ($givn_initial) {
			case '@':
				$html=$pgv_lang['NN'];
				break;
			default:
				$html=$givn_initial;
				break;
			}
			if ($givn_initial==$falpha && $show_all_firstnames=='no') {
				$html='<span class="warning">'.$html.'</span>';
			} else {
				$html='<a href="'.$url.'&amp;falpha='.$givn_initial.'">'.$html.'</a>';
			}
			$list[]=$html;
		}
		// Seach spiders don't get the "show all" option as the other links give them everything.
		if (!$SEARCH_SPIDER) {
			if ($show_all_firstnames=='yes') {
				$list[]='<span class="warning">'.$pgv_lang['all'].'</span>';
			} else {
				$list[]='<a href="'.$url.'&amp;show_all_firstnames=yes">'.$pgv_lang['all'].'</a>';
			}
		}
		if ($show_all=='no') {
			echo '<h2 class="center">';
			print PrintReady(str_replace("#surname#", check_NN($surname), $pgv_lang['indis_with_surname']));
			echo '</h2>';
		}
		echo '<p class="center">', $pgv_lang['first_letter_fname'], '<br />';
		print_help_link('alpha_help', 'qm', 'alpha_index');
		echo ' ', join(' | ', $list), '</p>';
	}

	usort($individuals, 'itemsort');
	if ($legend && $show_all=='no') {
		$legend=PrintReady(str_replace("#surname#", check_NN($legend), $pgv_lang['indis_with_surname']));
	}
	print_indi_table($individuals, $legend);
}

print_footer();

?>
