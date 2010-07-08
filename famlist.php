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
 * translated version of the word ALL by means of variable <var>i18n::translate('All')</var>.
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
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2009 PGV Development Team.  All rights reserved.
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
 * @subpackage Lists
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'famlist.php');
require './includes/session.php';
require_once WT_ROOT.'includes/functions/functions_print_lists.php';

$SUBLIST_TRIGGER_F=get_gedcom_setting(WT_GED_ID, 'SUBLIST_TRIGGER_F');

// We show three different lists:
$alpha   =safe_GET('alpha'); // All surnames beginning with this letter where "@"=unknown and ","=none
$surname =safe_GET('surname', '[^<>&%{};]*'); // All fams with this surname.  NB - allow ' and "
$show_all=safe_GET('show_all', array('no','yes'), 'no'); // All fams

// Don't show the list until we have some filter criteria
if (isset($alpha) || isset($surname) || $show_all=='yes') {
	$showList = true;
} else {
	$showList = false;
}

// Long lists can be broken down by given name
$falpha=safe_GET('falpha'); // All first names beginning with this letter
$show_all_firstnames=safe_GET('show_all_firstnames', array('no','yes'), 'no');

// We can show either a list of surnames or a list of names
$surname_sublist=safe_GET('surname_sublist', array('no','yes'));
if (!$surname_sublist) {
	$surname_sublist=safe_COOKIE('surname_sublist', array('no','yes'), 'yes');
}
setcookie('surname_sublist', $surname_sublist);

// We can either include or exclude married names.
// We default to exclude.
$show_marnm=safe_GET('show_marnm', array('no','yes'));
if (!$show_marnm) {
	$show_marnm=safe_COOKIE('show_marnm_famlist', array('no','yes'));
}
if (!$show_marnm) {
	$show_marnm='no';
}
setcookie('show_marnm_famlist', $show_marnm);
// Override $SHOW_MARRIED_NAMES for this page
$SHOW_MARRIED_NAMES=($show_marnm=='yes');

// Fetch a list of the initial letters of all surnames in the database
$initials=get_indilist_salpha($SHOW_MARRIED_NAMES, true, WT_GED_ID);

// Make sure selections are consistent.
// i.e. can't specify show_all and surname at the same time.
if ($show_all=='yes') {
	$alpha='';
	$surname='';
	$legend=i18n::translate('All');
	$url='famlist.php?show_all=yes';
} elseif ($surname) {
	$surname=utf8_strtoupper($surname);
	$alpha=utf8_substr($surname, 0, 1);
	foreach (explode(' ', i18n::$alphabet) as $letter) {
		if (strpos($surname, utf8_strtoupper($letter))===0) {
			$alpha=utf8_strtoupper($letter);
		}
	}
	$show_all='no';
	$legend=$surname;
	switch($falpha) {
	case '':
		break;
	case '@':
		$legend.=', '.i18n::translate('(unknown)');
		break;
	default:
		$legend.=', '.$falpha;
		break;
	}
	$surname_sublist='no';
	$url='famlist.php?surname='.urlencode($surname);
} else {
	$show_all='no';
	$surname='';
	if ($alpha=='@') {
		$legend=i18n::translate('(unknown)');
		$surname_sublist='no';
		$surname='@N.N.';
	} elseif ($alpha==',') {
		$legend=i18n::translate('None');
		$surname_sublist='no';
	} else {
		$legend=$alpha;
	}
	$url='famlist.php?alpha='.urlencode($alpha);
}


print_header(i18n::translate('Families').' : '.$legend);
echo '<h2 class="center">', i18n::translate('Families'), '</h2>';

// Print a selection list of initial letters
foreach ($initials as $letter=>$count) {
	switch ($letter) {
	case '@':
		$html=i18n::translate('(unknown)');
		break;
	case ',':
		$html=i18n::translate('None');
		break;
	default:
		$html=$letter;
		break;
	}
	if ($count) {
		if ($showList && $letter==$alpha && $show_all=='no') {
			if ($surname) {
				$html='<a href="famlist.php?alpha='.urlencode($letter).'" class="warning" title="'.$count.'">'.$html.'</a>';
			} else {
				$html='<span class="warning" title="'.$count.'">'.$html.'</span>';
			}
		} else {
			$html='<a href="famlist.php?alpha='.urlencode($letter).'" title="'.$count.'">'.$html.'</a>';
		}
	}
	$list[]=$html;
}

// Search spiders don't get the "show all" option as the other links give them everything.
if (!$SEARCH_SPIDER) {
	if ($show_all=='yes') {
		$list[]='<span class="warning">'.i18n::translate('All').'</span>';
	} else {
		$list[]='<a href="famlist.php?show_all=yes">'.i18n::translate('All').'</a>';
	}
}
echo '<div class="alpha_index"><p class="center">';
echo i18n::translate('Choose a letter to show families whose name starts with that letter.');
echo help_link('alpha');
echo '<br />', join(' | ', $list), '</p>';

// Search spiders don't get an option to show/hide the surname sublists,
// nor does it make sense on the all/unknown/surname views
if (!$SEARCH_SPIDER) {
	echo '<p class="center">';
	if ($alpha!='@' && $alpha!=',' && !$surname) {
		if ($surname_sublist=='yes') {
			echo '<a href="', $url, '&amp;surname_sublist=no">', i18n::translate('Skip surname lists'), '</a>';
			echo help_link('skip_sublist');
		} else {
			echo '<a href="', $url, '&amp;surname_sublist=yes">', i18n::translate('Show surname lists'), '</a>';
			echo help_link('skip_sublist');
		}
		echo '&nbsp;&nbsp;&nbsp;';
	}
	if ($showList) {
		if ($SHOW_MARRIED_NAMES) {
			echo '<a href="', $url, '&amp;show_marnm=no">', i18n::translate('Exclude married names'), '</a>';
		} else {
			echo '<a href="', $url, '&amp;show_marnm=yes">', i18n::translate('Include married names'), '</a>';
		}
		echo help_link('show_marnm');
		echo '</p>';
	}
}
echo '</div>';

if ($showList) {
	$surns=get_famlist_surns($surname, $alpha, $SHOW_MARRIED_NAMES, WT_GED_ID);
	if ($surname_sublist=='yes') {
		// Show the surname list
		switch ($SURNAME_LIST_STYLE) {
		case 'style1';
			echo format_surname_list($surns, 3, true);
			break;
		case 'style3':
			echo format_surname_tagcloud($surns, 'famlist', true);
			break;
		case 'style2':
		default:
			echo format_surname_table($surns, 'famlist');
			break;
		}
	} else {
		// Show the family list
		$count=0;
		foreach ($surns as $surnames) {
			foreach ($surnames as $list) {
				$count+=count($list);
			}
		}
		// Don't sublists short lists.
		if ($count<$SUBLIST_TRIGGER_F) {
			$falpha='';
			$show_all_firstnames='no';
		} else {
			$givn_initials=get_indilist_galpha($surname, $alpha, $SHOW_MARRIED_NAMES, true, WT_GED_ID);
			// Break long lists by initial letter of given name
			if (($surname || $show_all=='yes') && $count>$SUBLIST_TRIGGER_F) {
				// Don't show the list until we have some filter criteria
				$showList=($falpha || $show_all_firstnames=='yes');
				$list=array();
				foreach ($givn_initials as $givn_initial=>$count) {
					switch ($givn_initial) {
					case '@':
						$html=i18n::translate('(unknown)');
						break;
					default:
						$html=$givn_initial;
						break;
					}
					if ($count) {
					if ($showList && $givn_initial==$falpha && $show_all_firstnames=='no') {
							$html='<span class="warning" title="'.$count.'">'.$html.'</span>';
						} else {
							$html='<a href="'.$url.'&amp;falpha='.$givn_initial.'" title="'.$count.'">'.$html.'</a>';
						}
					}
					$list[]=$html;
				}
				// Search spiders don't get the "show all" option as the other links give them everything.
				if (!$SEARCH_SPIDER) {
					if ($show_all_firstnames=='yes') {
						$list[]='<span class="warning">'.i18n::translate('All').'</span>';
					} else {
						$list[]='<a href="'.$url.'&amp;show_all_firstnames=yes">'.i18n::translate('All').'</a>';
					}
				}
				if ($show_all=='no') {
					echo '<h2 class="center">';
					print i18n::translate('Families with surname %s', check_NN($surname));
					echo '</h2>';
				}
				echo '<div class="alpha_index"><p class="center">';
				echo i18n::translate('Choose a letter to show families where a spouse has a given name which starts with that letter.');
				echo help_link('alpha');
				echo '<br />', join(' | ', $list), '</p></div>';
			}
		}
		if ($showList) {
			if ($legend && $show_all=='no') {
				$legend=i18n::translate('Families with surname %s', check_NN($legend));
			}
			$families=get_famlist_fams($surname, $alpha, $falpha, $SHOW_MARRIED_NAMES, WT_GED_ID);
			print_fam_table($families, $legend);
		}
	}
}

print_footer();

?>
