<?php
/**
 * Family List
 *
 * The family list shows all families from a chosen gedcom file. The list is
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
 * NOTE: indilist.php and famlist.php contain mostly identical code.
 * Updates to one file almost certainly need to be made to the other one as well.
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
$surname =safe_GET('surname', '[^<>&%{};]*'); // All fams with this surname.  NB - allow ' and "
$show_all=safe_GET('show_all', array('no','yes'), 'no'); // All fams

if (isset($alpha) || isset($surname) || $show_all=='yes') $showList = true;
else $showList = false;		// Don't show the list until we have some filter criteria
if (!isset($alpha)) $alpha = '';

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
$initials=array_keys(get_fam_alpha());
if (! $initials) {
	$initials[]='@';
}

// Decide which initial letter to show by default - if the user hasn't
// specified.  Use the one in the same character set as the page language
function default_initial($initials, $sample_text=null) {
	global $pgv_lang;
	if (is_null($sample_text)) {
		$language=whatLanguage($pgv_lang['mother']); // Pick any text
	} else {
		$language=whatLanguage($sample_text);
	}
	foreach ($initials as $initial) {
		if (whatLanguage($initial)==$language && $initial!=',') {
			return $initial;
		}
	}
	return reset($initials);
}

// Fetch the list of fams, and make sure selections are consistent.
// i.e. can't specify show_all and surname at the same time.
if ($show_all=='yes') {
	$alpha='';
	$surname='';
	$legend=$pgv_lang['all'];
	$fams=get_fam_list();
	$url='famlist.php?show_all=yes';
} elseif ($surname) {
	$surname=UTF8_strtoupper($surname);
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
	$fams=get_surname_fams($surname);
	$url='famlist.php?surname='.urlencode($surname);
} else {
	// Can only select initial letters that are actually used.
//	if (! in_array($alpha, $initials)) {
//		$alpha=default_initial($initials);
//	}
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
	$fams=get_alpha_fams($alpha);
	$url='famlist.php?alpha='.urlencode($alpha);
}

// Don't sublists short lists.
if (count($fams)<$SUBLIST_TRIGGER_F) {
	$falpha='';
	$show_all_firstnames='no';
}

// If the total number of fams is small enough for search spiders to take in one
// hit, then send them straight there.
if ($SEARCH_SPIDER) {
	$surname_sublist=count($fams)>900 ? 'yes' : 'no';
}

print_header($pgv_lang['family_list'].' : '.$legend);
echo '<h2 class="center">', $pgv_lang['family_list'], '</h2>';

// Print a selection list of initial letters
$list=array();
$delayedList = array();
foreach ($initials as $letter) {
	switch ($letter) {
	case '@':
		$delay = true;
		$html=$pgv_lang['NN'];
		break;
	case ',':
		$delay = true;
		$html=$pgv_lang['none'];
		break;
	default:
		$delay = false;
		$html=$letter;
		break;
	}
	if ($showList && $letter==$alpha && $show_all=='no') {
		if ($surname) {
			$html='<a href="famlist.php?alpha='.urlencode($letter).'" class="warning">'.$html.'</a>';
		} else {
			$html='<span class="warning">'.$html.'</span>';
		}
	} else {
		$html='<a href="famlist.php?alpha='.urlencode($letter).'">'.$html.'</a>';
	}
	if ($delay) $delayedList[] = $html;
	else $list[] = $html;
}
foreach ($delayedList as $listEntry) {
	$list[] = $listEntry;		// "@" and "," should always be at the end of the letter list
}
// Search spiders don't get the "show all" option as the other links give them everything.
if (!$SEARCH_SPIDER) {
	if ($show_all=='yes') {
		$list[]='<span class="warning">'.$pgv_lang['all'].'</span>';
	} else {
		$list[]='<a href="famlist.php?show_all=yes">'.$pgv_lang['all'].'</a>';
	}
}
echo '<p class="center">';
print_help_link('alpha_help', 'qm', 'alpha_index');
print $pgv_lang["first_letter_fname"]."<br />";
echo join(' | ', $list), '</p>';

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

if ($showList) {
	if ($surname_sublist=='yes') {
		// Show the surname list
		// Note that we count/display SPFX SURN, but sort/group under just SURN
		$surnames=array();
		foreach (array_keys($fams) as $pid) {
			if (empty($pid)) continue;
			$family=Family::getInstance($pid);
			foreach (array($family->husb, $family->wife) as $person) {
				if (!is_object($person)) continue;
				foreach ($person->getAllNames() as $name) {
					$surn=UTF8_strtoupper($name['surn']);
					// Ignore diacritics - need to use the same logic as get_fam_alpha()
					// TODO: This ought to be a language-dependent conversion, as in some
					// languages, letters with diacritics are regarded as separate letters.
					$initial=get_first_letter($surn);
					if ($DICTIONARY_SORT[$LANGUAGE]) {
						$position = strpos($UCDiacritWhole, $initial);
						if ($position!==false) {
							$position = $position >> 1;
							$initial = substr($UCDiacritStrip, $position, 1);
						} else {
							$position = strpos($LCDiacritWhole, $initial);
							if ($position!==false) {
								$position = $position >> 1;
								$initial = substr($LCDiacritStrip, $position, 1);
							}
						}
					}
					if ($show_all=='yes' || $surname && $surname==$surn || !$surname && $alpha==$initial) {
						switch ($surn) {
						case '@N.N.':
							$spfxsurn=$pgv_lang['NN'];
							break;
						case '':
							$spfxsurn='('.$pgv_lang['none'].')';
							break;
						default:
							$spfxsurn=$name['spfx'] ? $name['spfx'].' '.$name['surn'] : $name['surn'];
							break;
						}
						if (! array_key_exists($surn, $surnames)) {
							$surnames[$surn]=array();
						}
						if (! array_key_exists($spfxsurn, $surnames[$surn])) {
							$surnames[$surn][$spfxsurn]=array();
						}
						// $surn is the base surname, e.g. GOGH
						// $spfxsurn is the full surname, e.g. van GOGH
						// $pid allows us to count fams as well as surnames, for fams that
						// appear twice in this list.
						$surnames[$surn][$spfxsurn][$pid]=true;
					}
				}
			}
		}
		uksort($surnames, 'compareStrings');
		switch ($SURNAME_LIST_STYLE) {
		case 'style3':
			echo format_surname_tagcloud($surnames, 'famlist', true);
			break;
		case 'style2':
		default:
			echo format_surname_table($surnames, 'famlist');
			break;
		}
	} else {
		// Show the family list

		$families=array();
		$givn_initials=array();
		// Show the fam list
		foreach (array_keys($fams) as $pid) {
			if (empty($pid)) continue;
			$family=Family::getInstance($pid);
			foreach ($family->getAllNames() as $n=>$name) {
				list($husb_name, $wife_name)=explode(' + ', $name['sort']);
				// Check for husband name
				list($husb_surn,$husb_givn)=explode(',', $husb_name);
				$givn_alpha=get_first_letter($husb_givn);
				if ((!$surname || $surname==$husb_surn) &&
				    (!$alpha   || $alpha==get_first_letter($husb_name))) {
					$givn_initials[$givn_alpha]=$givn_alpha;
					if (!$falpha || $falpha==$givn_alpha) {
						$family->setPrimaryName($n);
						$families[]=$family;
						continue 2;
					}
				}
				// Check for wife name
				list($wife_surn,$wife_givn)=explode(',', $wife_name);
				$givn_alpha=get_first_letter($wife_givn);
				if ((!$surname || $surname==$wife_surn) &&
				    (!$alpha   || $alpha==get_first_letter($wife_name))) {
					$givn_initials[$givn_alpha]=$givn_alpha;
					if (!$falpha || $falpha==$givn_alpha) {
						$family->setPrimaryName($n);
						$families[]=$family;
						continue 2;
					}
				}
			}
		}
		uasort($givn_initials, 'stringsort');

		// Break long lists by initial letter of given name
		//if (count($fams)>$SUBLIST_TRIGGER_F) {
		if (($surname || $show_all=='yes') && count($fams)>$SUBLIST_TRIGGER_F) { // Ingore setting on initial lists at request of MA
			$showList = false;		// Don't show the list until we have some filter criteria
			if (!empty($falpha) || $show_all_firstnames=='yes') $showList = true;
			if (!$falpha && $show_all_firstnames=='no') {
				// If we didn't specify initial or all, filter by the first initial
				$falpha=default_initial($givn_initials, $alpha);
				$legend.=', '.$falpha;
				foreach ($families as $key=>$value) {
					if (strpos($value['name'], ','.$falpha)===false) {
						unset($families[$key]);
					}
				}
			}
			$list=array();
			$delayedList = array();
			foreach ($givn_initials as $givn_initial) {
				switch ($givn_initial) {
				case '@':
					$delay = true;
					$html=$pgv_lang['NN'];
					break;
				default:
					$delay = false;
					$html=$givn_initial;
					break;
				}
				if ($showList && $givn_initial==$falpha && $show_all_firstnames=='no') {
					$html='<span class="warning">'.$html.'</span>';
				} else {
					$html='<a href="'.$url.'&amp;falpha='.$givn_initial.'">'.$html.'</a>';
				}
				if ($delay) $delayedList[] = $html;
				else $list[]=$html;
			}
			foreach ($delayedList as $listEntry) {
				$list[] = $listEntry;
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
				print PrintReady(str_replace("#surname#", check_NN($surname), $pgv_lang['fams_with_surname']));
				echo '</h2>';
			}
			echo '<p class="center">';
			print_help_link('alpha_help', 'qm', 'alpha_index');
			echo $pgv_lang['first_letter_fname'], '<br />';
			echo join(' | ', $list), '</p>';
		}

		if ($showList) {
			usort($families, array('GedcomRecord', 'Compare'));
			if ($legend && $show_all=='no') {
				$legend=PrintReady(str_replace("#surname#", check_NN($legend), $pgv_lang['fams_with_surname']));
			}
			print_fam_table($families, $legend);
		}
	}
}

print_footer();

?>
