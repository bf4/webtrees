<?php
/**
 * Calculates the relationship between two individuals in the gedcom
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
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
 * This Page Is Valid XHTML 1.0 Transitional! > 20 August 2005
 *
 * @package webtrees
 * @subpackage Charts
 * @version $Id$
 */

define('PGV_SCRIPT_NAME', 'relationship.php');
require './config.php';
require_once PGV_ROOT.'includes/functions/functions_charts.php';
require_once PGV_ROOT.'includes/classes/class_person.php';

function getRelationshipSentence($path, $pid1, $pid2) {
	// Look for paths with *specific* names first.
	// Note that every combination must be listed separately, as the same english
	// name can be used for many different relationships.  e.g.
	// brother's wife & husband's sister = sister-in-law.
	//
	// For this reason, we need to use a "generic" english relationships,
	// which will need translating into specific english relationships, even
	// for english.
	//
	// $path is an array of the 12 possible gedcom family relationships:
	// mother/father/parent
	// brother/sister/sibling
	// husband/wife/spouse
	// son/daughter/child
	//
	// This is always the shortest path, so "father, daughter" is "half-sister", not "sister".
	//
	// This is very repetitive in english, but necessary in order to handle the
	// complexities of other languages.
	//
	// TODO: handle unmarried partners, so need male-partner, female-partner, unknown-partner

	// Make each relationship parts the same length, for simpler matching.
	$combined_path='';
	foreach ($path as $rel) {
		$combined_path.=substr($rel, 0, 3);
	}
	switch ($combined_path) {
	//  Level One relationships
	case 'mot': return i18n::translate('mother');
	case 'fat': return i18n::translate('father');
	case 'par': return i18n::translate('parent');
	case 'hus': return i18n::translate('husband');
	case 'wif': return i18n::translate('wife');
	case 'spo': return i18n::translate('spouse');
	case 'son': return i18n::translate('son');
	case 'dau': return i18n::translate('daughter');
	case 'chi': return i18n::translate('child');
	case 'bro':
		$dob1=Person::GetInstance($pid1)->getBirthDate();
		$dob2=Person::GetInstance($pid2)->getBirthDate();
		if ($dob1->isOK() && $dob2->isOK()) {
			if (abs($dob1->JD()-$dob2->JD())<2) {
				return i18n::translate('twin brother');
			} else if ($dob1->JD()<$dob2->JD()) {
				return i18n::translate('younger brother');
			} else {
				return i18n::translate('elder brother');
			}
		}
		return i18n::translate('brother');
	case 'sis':
		$dob1=Person::GetInstance($pid1)->getBirthDate();
		$dob2=Person::GetInstance($pid2)->getBirthDate();
		if ($dob1->isOK() && $dob2->isOK()) {
			if (abs($dob1->JD()-$dob2->JD())<2) {
				return i18n::translate('twin sister');
			} else if ($dob1->JD()<$dob2->JD()) {
				return i18n::translate('younger sister');
			} else {
				return i18n::translate('elder sister');
			}
		}
		return i18n::translate('sister');
	case 'sib':
		$dob1=Person::GetInstance($pid1)->getBirthDate();
		$dob2=Person::GetInstance($pid2)->getBirthDate();
		if ($dob1->isOK() && $dob2->isOK()) {
			if (abs($dob1->JD()-$dob2->JD())<2) {
				return i18n::translate('twin sibling');
			} else if ($dob1->JD()<$dob2->JD()) {
				return i18n::translate('younger sibling');
			} else {
				return i18n::translate('elder sibling');
			}
		}
		return i18n::translate('sibling');
	
	// Level Two relationships
	case 'motmot':  return /* I18N: grandmother */ i18n::translate('mother\'s mother');
	case 'motfat':  return /* I18N: grandfather */ i18n::translate('mother\'s father');
	case 'motpar':  return /* I18N: grandparent */ i18n::translate('mother\'s parent');
	case 'fatmot':  return /* I18N: grandmother */ i18n::translate('father\'s mother');
	case 'fatfat':  return /* I18N: grandfather */ i18n::translate('father\'s father');
	case 'fatpar':  return /* I18N: grandparent */ i18n::translate('father\'s parent');
	case 'parmot':  return /* I18N: grandmother */ i18n::translate('parent\'s mother');
	case 'parfat':  return /* I18N: grandfather */ i18n::translate('parent\'s father');
	case 'parpar':  return /* I18N: grandparent */ i18n::translate('parent\'s parent');

	case 'daumot':  return /* I18N: granddaughter */ i18n::translate('daughter\'s daughter');
	case 'dauson':  return /* I18N: grandson      */ i18n::translate('daughter\'s son');
	case 'dauchi':  return /* I18N: grandchild    */ i18n::translate('daughter\'s child');
	case 'sondau':  return /* I18N: granddaughter */ i18n::translate('son\'s daughter');
	case 'sonfat':  return /* I18N: grandson      */ i18n::translate('son\'s son');
	case 'sonchi':  return /* I18N: grandchild    */ i18n::translate('son\'s child');
	case 'chidau':  return /* I18N: granddaughter */ i18n::translate('child\'s daughter');
	case 'chison':  return /* I18N: grandson      */ i18n::translate('child\'s son');
	case 'chichi':  return /* I18N: grandchild    */ i18n::translate('child\'s child');

	case 'mothus': return /* I18N: step-father */ i18n::translate('mother\'s husband');
	case 'mothus': return /* I18N: step-father */ i18n::translate('mother\'s husband');
	case 'fatwif': return /* I18N: step-mother */ i18n::translate('father\'s wife');
	case 'fatwif': return /* I18N: step-mother */ i18n::translate('father\'s wife');
	case 'parspo': return /* I18N: step-parent */ i18n::translate('parent\'s spouse');
	case 'parspo': return /* I18N: step-parent */ i18n::translate('parent\'s spouse');

	case 'motson': return /* I18N: half-brother */ i18n::translate('mother\'s son');
	case 'motdau': return /* I18N: half-sister  */ i18n::translate('mother\'s daughter');
	case 'motchi': return /* I18N: half-sibling */ i18n::translate('mother\'s child');
	case 'fatson': return /* I18N: half-brother */ i18n::translate('father\'s son');
	case 'fatdau': return /* I18N: half-sister  */ i18n::translate('father\'s daughter');
	case 'fatchi': return /* I18N: half-sibling */ i18n::translate('father\'s child');
	case 'parson': return /* I18N: half-brother */ i18n::translate('parent\'s son');
	case 'pardau': return /* I18N: half-sister  */ i18n::translate('parent\'s daughter');
	case 'parchi': return /* I18N: half-sibling */ i18n::translate('parent\'s child');

	case 'motsis': return /* I18N: aunt        */ i18n::translate('mother\'s sister');
	case 'motbro': return /* I18N: uncle       */ i18n::translate('mother\'s brother');
	case 'motsib': return /* I18N: aunt/uncle  */ i18n::translate('mother\'s sibling');
	case 'fatsis': return /* I18N: aunt        */ i18n::translate('father\'s sister');
	case 'fatbro': return /* I18N: uncle       */ i18n::translate('father\'s brother');
	case 'fatsib': return /* I18N: aunt/uncle  */ i18n::translate('father\'s sibling');
	case 'parsis': return /* I18N: aunt        */ i18n::translate('parent\'s sister');
	case 'parbro': return /* I18N: uncle       */ i18n::translate('parent\'s brother');
	case 'parsib': return /* I18N: aunt/uncle  */ i18n::translate('parent\'s sibling');

	case 'broson': return /* I18N: nephew       */ i18n::translate('bother\'s son');
	case 'brodau': return /* I18N: niece        */ i18n::translate('bother\'s daughter');
	case 'brochi': return /* I18N: nephew/neice */ i18n::translate('bother\'s child');
	case 'sisson': return /* I18N: nephew       */ i18n::translate('sister\'s son');
	case 'sisdau': return /* I18N: niece        */ i18n::translate('sister\'s daughter');
	case 'sischi': return /* I18N: nephew/neice */ i18n::translate('sister\'s child');
	case 'sibson': return /* I18N: nephew       */ i18n::translate('sibling\'s son');
	case 'sibdau': return /* I18N: niece        */ i18n::translate('sibling\'s daughter');
	case 'sibchi': return /* I18N: nephew/neice */ i18n::translate('sibling\'s child');

	case 'wifsis': return /* I18N: sister-in-law  */ i18n::translate('wife\'s sister');
	case 'hussis': return /* I18N: sister-in-law  */ i18n::translate('husband\'s sister');
	case 'sposis': return /* I18N: sister-in-law  */ i18n::translate('spouses\'s sister');
	case 'wifbro': return /* I18N: brother-in-law */ i18n::translate('wife\'s brother');
	case 'husbro': return /* I18N: brother-in-law */ i18n::translate('husband\'s brother');
	case 'spobro': return /* I18N: brother-in-law */ i18n::translate('spouses\'s brother');

	case 'browif': return /* I18N: sister-in-law  */ i18n::translate('brother\'s wife');
	case 'sishub': return /* I18N: brother-in-law */ i18n::translate('sister\'s husband');

	case 'husmot': return /* I18N: mother-in-law  */ i18n::translate('husband\'s mother');
	case 'wifmot': return /* I18N: mother-in-law  */ i18n::translate('wife\'s mother');
	case 'spomot': return /* I18N: mother-in-law  */ i18n::translate('souses\'s mother');
	case 'husfat': return /* I18N: father-in-law  */ i18n::translate('husband\'s father');
	case 'wiffat': return /* I18N: father-in-law  */ i18n::translate('wife\'s father');
	case 'spofat': return /* I18N: father-in-law  */ i18n::translate('souses\'s father');

	case 'sonwif': return /* I18N: daughter-in-law */ i18n::translate('son\'s wife');
	case 'dauhus': return /* I18N: son-in-law      */ i18n::translate('daughter\'s husband');

	// Level Three relationships
	case 'motmotmot': return /* I18N: great-grandmother */ i18n::translate('mother\'s mother\'s mother');
	case 'motmotfat': return /* I18N: great-grandfather */ i18n::translate('mother\'s mother\'s father');
	case 'motmotpar': return /* I18N: great-grandparent */ i18n::translate('mother\'s mother\'s parent');
	case 'motfatmot': return /* I18N: great-grandmother */ i18n::translate('mother\'s father\'s mother');
	case 'motfatfat': return /* I18N: great-grandfather */ i18n::translate('mother\'s father\'s father');
	case 'motfatpar': return /* I18N: great-grandparent */ i18n::translate('mother\'s father\'s parent');
	case 'motparmot': return /* I18N: great-grandmother */ i18n::translate('mother\'s parent\'s mother');
	case 'motparfat': return /* I18N: great-grandfather */ i18n::translate('mother\'s parent\'s father');
	case 'motparpar': return /* I18N: great-grandparent */ i18n::translate('mother\'s parent\'s parent');
	case 'fatmotmot': return /* I18N: great-grandmother */ i18n::translate('father\'s mother\'s mother');
	case 'fatmotfat': return /* I18N: great-grandfather */ i18n::translate('father\'s mother\'s father');
	case 'fatmotpar': return /* I18N: great-grandparent */ i18n::translate('father\'s mother\'s parent');
	case 'fatfatmot': return /* I18N: great-grandmother */ i18n::translate('father\'s father\'s mother');
	case 'fatfatfat': return /* I18N: great-grandfather */ i18n::translate('father\'s father\'s father');
	case 'fatfatpar': return /* I18N: great-grandparent */ i18n::translate('father\'s father\'s parent');
	case 'fatparmot': return /* I18N: great-grandmother */ i18n::translate('father\'s parent\'s mother');
	case 'fatparfat': return /* I18N: great-grandfather */ i18n::translate('father\'s parent\'s father');
	case 'fatparpar': return /* I18N: great-grandparent */ i18n::translate('father\'s parent\'s parent');
	case 'parmotmot': return /* I18N: great-grandmother */ i18n::translate('parent\'s mother\'s mother');
	case 'parmotfat': return /* I18N: great-grandfather */ i18n::translate('parent\'s mother\'s father');
	case 'parmotpar': return /* I18N: great-grandparent */ i18n::translate('parent\'s mother\'s parent');
	case 'parfatmot': return /* I18N: great-grandmother */ i18n::translate('parent\'s father\'s mother');
	case 'parfatfat': return /* I18N: great-grandfather */ i18n::translate('parent\'s father\'s father');
	case 'parfatpar': return /* I18N: great-grandparent */ i18n::translate('parent\'s father\'s parent');
	case 'parparmot': return /* I18N: great-grandmother */ i18n::translate('parent\'s parent\'s mother');
	case 'parparfat': return /* I18N: great-grandfather */ i18n::translate('parent\'s parent\'s father');
	case 'parparpar': return /* I18N: great-grandparent */ i18n::translate('parent\'s parent\'s parent');

	case 'motmotsis': return /* I18N: great-aunt        */ i18n::translate('mother\'s mother\'s sister');
	case 'motmotbro': return /* I18N: great-uncle       */ i18n::translate('mother\'s mother\'s brother');
	case 'motmotsib': return /* I18N: great-aunt/uncle  */ i18n::translate('mother\'s mother\'s sibling');
	case 'motfatsis': return /* I18N: great-aunt        */ i18n::translate('mother\'s father\'s sister');
	case 'motfatbro': return /* I18N: great-uncle       */ i18n::translate('mother\'s father\'s brother');
	case 'motfatsib': return /* I18N: great-aunt/uncle  */ i18n::translate('mother\'s father\'s sibling');
	case 'motparsis': return /* I18N: great-aunt        */ i18n::translate('mother\'s parent\'s sister');
	case 'motparbro': return /* I18N: great-uncle       */ i18n::translate('mother\'s parent\'s brother');
	case 'motparsib': return /* I18N: great-aunt/uncle  */ i18n::translate('mother\'s parent\'s sibling');
	case 'fatmotsis': return /* I18N: great-aunt        */ i18n::translate('father\'s mother\'s sister');
	case 'fatmotbro': return /* I18N: great-uncle       */ i18n::translate('father\'s mother\'s brother');
	case 'fatmotsib': return /* I18N: great-aunt/uncle  */ i18n::translate('father\'s mother\'s sibling');
	case 'fatfatsis': return /* I18N: great-aunt        */ i18n::translate('father\'s father\'s sister');
	case 'fatfatbro': return /* I18N: great-uncle       */ i18n::translate('father\'s father\'s brother');
	case 'fatfatsib': return /* I18N: great-aunt/uncle  */ i18n::translate('father\'s father\'s sibling');
	case 'fatparsis': return /* I18N: great-aunt        */ i18n::translate('father\'s parent\'s sister');
	case 'fatparbro': return /* I18N: great-uncle       */ i18n::translate('father\'s parent\'s brother');
	case 'fatparsib': return /* I18N: great-aunt/uncle  */ i18n::translate('father\'s parent\'s sibling');
	case 'parmotsis': return /* I18N: great-aunt        */ i18n::translate('parent\'s mother\'s sister');
	case 'parmotbro': return /* I18N: great-uncle       */ i18n::translate('parent\'s mother\'s brother');
	case 'parmotsib': return /* I18N: great-aunt/uncle  */ i18n::translate('parent\'s mother\'s sibling');
	case 'parfatsis': return /* I18N: great-aunt        */ i18n::translate('parent\'s father\'s sister');
	case 'parfatbro': return /* I18N: great-uncle       */ i18n::translate('parent\'s father\'s brother');
	case 'parfatsib': return /* I18N: great-aunt/uncle  */ i18n::translate('parent\'s father\'s sibling');
	case 'parparsis': return /* I18N: great-aunt        */ i18n::translate('parent\'s parent\'s sister');
	case 'parparbro': return /* I18N: great-uncle       */ i18n::translate('parent\'s parent\'s brother');
	case 'parparsib': return /* I18N: great-aunt/uncle  */ i18n::translate('parent\'s parent\'s sibling');

	case 'daudaudau': return /* I18N: great-granddaughter */ i18n::translate('daughter\'s daughter\'s daughter');
	case 'daudauson': return /* I18N: great-grandson      */ i18n::translate('daughter\'s daughter\'s son');
	case 'daudauchi': return /* I18N: great-grandchild    */ i18n::translate('daughter\'s daughter\'s child');
	case 'dausondau': return /* I18N: great-granddaughter */ i18n::translate('daughter\'s son\'s daughter');
	case 'dausonson': return /* I18N: great-grandson      */ i18n::translate('daughter\'s son\'s son');
	case 'dausonchi': return /* I18N: great-grandchild    */ i18n::translate('daughter\'s son\'s child');
	case 'dauchidau': return /* I18N: great-granddaughter */ i18n::translate('daughter\'s child\'s daughter');
	case 'dauchison': return /* I18N: great-grandson      */ i18n::translate('daughter\'s child\'s son');
	case 'dauchichi': return /* I18N: great-grandchild    */ i18n::translate('daughter\'s child\'s child');
	case 'sondaudau': return /* I18N: great-granddaughter */ i18n::translate('son\'s daughter\'s daughter');
	case 'sondauson': return /* I18N: great-grandson      */ i18n::translate('son\'s daughter\'s son');
	case 'sondauchi': return /* I18N: great-grandchild    */ i18n::translate('son\'s daughter\'s child');
	case 'sonsondau': return /* I18N: great-granddaughter */ i18n::translate('son\'s son\'s daughter');
	case 'sonsonson': return /* I18N: great-grandson      */ i18n::translate('son\'s son\'s son');
	case 'sonsonchi': return /* I18N: great-grandchild    */ i18n::translate('son\'s son\'s child');
	case 'sonchidau': return /* I18N: great-granddaughter */ i18n::translate('son\'s child\'s daughter');
	case 'sonchison': return /* I18N: great-grandson      */ i18n::translate('son\'s child\'s son');
	case 'sonchichi': return /* I18N: great-grandchild    */ i18n::translate('son\'s child\'s child');
	case 'chidaudau': return /* I18N: great-granddaughter */ i18n::translate('child\'s daughter\'s daughter');
	case 'chidauson': return /* I18N: great-grandson      */ i18n::translate('child\'s daughter\'s son');
	case 'chidauchi': return /* I18N: great-grandchild    */ i18n::translate('child\'s daughter\'s child');
	case 'chisondau': return /* I18N: great-granddaughter */ i18n::translate('child\'s son\'s daughter');
	case 'chisonson': return /* I18N: great-grandson      */ i18n::translate('child\'s son\'s son');
	case 'chisonchi': return /* I18N: great-grandchild    */ i18n::translate('child\'s son\'s child');
	case 'chichidau': return /* I18N: great-granddaughter */ i18n::translate('child\'s child\'s daughter');
	case 'chichison': return /* I18N: great-grandson      */ i18n::translate('child\'s child\'s son');
	case 'chichichi': return /* I18N: great-grandchild    */ i18n::translate('child\'s child\'s child');

	case 'mothusson': return /* I18N: step-brother */ i18n::translate('mother\'s husband\'s son');
	case 'mothusdau': return /* I18N: step-sister  */ i18n::translate('mother\'s husband\'s daughter');
	case 'mothuschi': return /* I18N: step-sibling */ i18n::translate('mother\'s husband\'s child');
	case 'fatwifson': return /* I18N: step-brother */ i18n::translate('father\'s wife\'s son');
	case 'fatwifdau': return /* I18N: step-sister  */ i18n::translate('father\'s wife\'s daughter');
	case 'fatwifchi': return /* I18N: step-sibling */ i18n::translate('father\'s wife\'s child');
	case 'parsposon': return /* I18N: step-brother */ i18n::translate('parent\'s spouse\'s son');
	case 'parspodau': return /* I18N: step-sister  */ i18n::translate('parent\'s spouse\'s daughter');
	case 'parspochi': return /* I18N: step-sibling */ i18n::translate('parent\'s spouse\'s child');

	case 'motmotsis': return /* I18N: great-aunt        */ i18n::translate('mother\'s mother\'s sister');
	case 'motmotbro': return /* I18N: great-uncle       */ i18n::translate('mother\'s mother\'s brother');
	case 'motmotsib': return /* I18N: great-aunt/uncle  */ i18n::translate('mother\'s mother\'s sibling');
	case 'motfatsis': return /* I18N: great-aunt        */ i18n::translate('mother\'s father\'s sister');
	case 'motfatbro': return /* I18N: great-uncle       */ i18n::translate('mother\'s father\'s brother');
	case 'motfatsib': return /* I18N: great-aunt/uncle  */ i18n::translate('mother\'s father\'s sibling');
	case 'motparsis': return /* I18N: great-aunt        */ i18n::translate('mother\'s parent\'s sister');
	case 'motparbro': return /* I18N: great-uncle       */ i18n::translate('mother\'s parent\'s brother');
	case 'motparsib': return /* I18N: great-aunt/uncle  */ i18n::translate('mother\'s parent\'s sibling');
	case 'fatmotsis': return /* I18N: great-aunt        */ i18n::translate('father\'s mother\'s sister');
	case 'fatmotbro': return /* I18N: great-uncle       */ i18n::translate('father\'s mother\'s brother');
	case 'fatmotsib': return /* I18N: great-aunt/uncle  */ i18n::translate('father\'s mother\'s sibling');
	case 'fatfatsis': return /* I18N: great-aunt        */ i18n::translate('father\'s father\'s sister');
	case 'fatfatbro': return /* I18N: great-uncle       */ i18n::translate('father\'s father\'s brother');
	case 'fatfatsib': return /* I18N: great-aunt/uncle  */ i18n::translate('father\'s father\'s sibling');
	case 'fatparsis': return /* I18N: great-aunt        */ i18n::translate('father\'s parent\'s sister');
	case 'fatparbro': return /* I18N: great-uncle       */ i18n::translate('father\'s parent\'s brother');
	case 'fatparsib': return /* I18N: great-aunt/uncle  */ i18n::translate('father\'s parent\'s sibling');
	case 'parmotsis': return /* I18N: great-aunt        */ i18n::translate('parent\'s mother\'s sister');
	case 'parmotbro': return /* I18N: great-uncle       */ i18n::translate('parent\'s mother\'s brother');
	case 'parmotsib': return /* I18N: great-aunt/uncle  */ i18n::translate('parent\'s mother\'s sibling');
	case 'parfatsis': return /* I18N: great-aunt        */ i18n::translate('parent\'s father\'s sister');
	case 'parfatbro': return /* I18N: great-uncle       */ i18n::translate('parent\'s father\'s brother');
	case 'parfatsib': return /* I18N: great-aunt/uncle  */ i18n::translate('parent\'s father\'s sibling');
	case 'parparsis': return /* I18N: great-aunt        */ i18n::translate('parent\'s parent\'s sister');
	case 'parparbro': return /* I18N: great-uncle       */ i18n::translate('parent\'s parent\'s brother');
	case 'parparsib': return /* I18N: great-aunt/uncle  */ i18n::translate('parent\'s parent\'s sibling');
	}

	// Look for generic/pattern relationships.
	// TODO: these are heavily based on english relationship names.
	// We need feedback from other languages to improve this.
	if (preg_match('/^((?:mot|fat|par)*)(bro|sis|sib)$/', $combined_path, $match)) {
		$up=strlen($match[1])/3;
		$last=substr($combined_path, -3, 3);
		switch($last) {
		case 'bro': return i18n::translate('great x %d aunt',       $up-1);
		case 'sis': return i18n::translate('great x %d uncle',      $up-1);
		case 'sib': return i18n::translate('great x %d aunt/uncle', $up-1);
		}
	}
	if (preg_match('/^((?:mot|fat|par)*)(?:bro|sis|sib)((?:son|dau|chi)*)$/', $combined_path, $match)) {
		$up  =strlen($match[1])/3;
		$down=strlen($match[2])/3;
		$last=substr($combined_path, -3, 3);
		if ($down==0) {
			switch($last) {
			case 'mot': return i18n::translate('great x %d grandmother', $up-2);
			case 'fat': return i18n::translate('great x %d grandfather', $up-2);
			case 'par': return i18n::translate('great x %d grandparent', $up-2);
			}
		}
		if ($up==0) {
			switch($last) {
			case 'son': return i18n::translate('great x %d grandson',      $down-2);
			case 'dau': return i18n::translate('great x %d granddaughter', $down-2);
			case 'chi': return i18n::translate('great x %d grandchild',    $down-2);
			}
		}
		// Cousins.  http://en.wikipedia.org/wiki/File:CousinTree.svg
		if ($up==$down) {
			switch($last) {
			case 'son': return i18n::translate('%s male cousin',   i18n::ordinal_word($up-1));
			case 'dau': return i18n::translate('%s female cousin', i18n::ordinal_word($up-1));
			case 'chi': return i18n::translate('%s cousin',        i18n::ordinal_word($up-1));
			}
		} else {
			$removed=abs($down-$up);
			switch($last) {
			case 'son':
				return i18n::plural(
					'%1$s male cousin, %2$d time removed', '%1$s male cousin, %2$d times removed',
					$removed, i18n::ordinal_word(min($up, $down)), $removed
				);
			case 'dau':
				return i18n::plural(
					'%1$s female cousin, %2$d time removed', '%1$s female cousin, %2$d times removed',
					$removed, i18n::ordinal_word(min($up, $down)), $removed
				);
			case 'chi': return i18n::plural('%1$s cousin, %2$d time removed', '%1$s cousin, %2$d times removed',
				$removed, i18n::ordinal_word(min($up, $down)), $removed
				);
			}
		}
	}

	// TODO: break the relationship down into sub-relationships.  e.g. cousin's cousin.

	// We don't have a specific name for this relationship, and we can't match it with a pattern.
	// Just spell it out.
	switch (array_pop($path)) {
	case 'mother':   $relationship=i18n::translate('mother'  ); break;
	case 'father':   $relationship=i18n::translate('father'  ); break;
	case 'parent':   $relationship=i18n::translate('parent'  ); break;
	case 'husband':  $relationship=i18n::translate('husband' ); break;
	case 'wife':     $relationship=i18n::translate('wife'    ); break;
	case 'spouse':   $relationship=i18n::translate('spouse'  ); break;
	case 'brother':  $relationship=i18n::translate('brother' ); break;
	case 'sister':   $relationship=i18n::translate('sister'  ); break;
	case 'sibling':  $relationship=i18n::translate('sibling' ); break;
	case 'son':      $relationship=i18n::translate('son'     ); break;
	case 'daughter': $relationship=i18n::translate('daughter'); break;
	case 'child':    $relationship=i18n::translate('child'   ); break;
	}
	while ($path) {
		switch (array_pop($path)) {
			// I18N: These strings are used to build paths of relationships, such as "father's wife's husband's brother"
		case 'mother':   $relationship=i18n::translate('mother\'s %s',   $relationship); break;
		case 'father':   $relationship=i18n::translate('father\'s %s',   $relationship); break;
		case 'parent':   $relationship=i18n::translate('parent\'s %s',   $relationship); break;
		case 'husband':  $relationship=i18n::translate('husband\'s %s',  $relationship); break;
		case 'wife':     $relationship=i18n::translate('wife\'s %s',     $relationship); break;
		case 'spouse':   $relationship=i18n::translate('spouse\'s %s',   $relationship); break;
		case 'brother':  $relationship=i18n::translate('brother\'s %s',  $relationship); break;
		case 'sister':   $relationship=i18n::translate('sister\'s %s',   $relationship); break;
		case 'sibling':  $relationship=i18n::translate('sibling\'s %s',  $relationship); break;
		case 'son':      $relationship=i18n::translate('son\'s %s',      $relationship); break;
		case 'daughter': $relationship=i18n::translate('daughter\'s %s', $relationship); break;
		case 'child':    $relationship=i18n::translate('child\'s %s',    $relationship); break;
		}
	}
	return $relationship;
}

$show_full=$PEDIGREE_FULL_DETAILS;
if (isset($_REQUEST['show_full'])) $show_full = $_REQUEST['show_full'];
if (!isset($_REQUEST['path_to_find'])) {
	$path_to_find = 0;
	$pretty = 1;
	unset($_SESSION["relationships"]);
}
else $path_to_find = $_REQUEST['path_to_find'];
if ($path_to_find == -1) {
	$path_to_find = 0;
	unset($_SESSION["relationships"]);
}

//-- previously these variables were set in theme.php, now they are no longer required to be set there
$Dbasexoffset = 0;
$Dbaseyoffset = 0;

if ($show_full==false) {
	$Dbheight=25;
	$Dbwidth-=40;
}

$bwidth = $Dbwidth;
$bheight = $Dbheight;

$title_string = "";

$pid1=safe_GET_xref('pid1');
$pid2=safe_GET_xref('pid2');

if (!isset($_REQUEST['followspouse'])) $followspouse = 0;
else $followspouse = $_REQUEST['followspouse'];
if (!isset($_REQUEST['pretty'])) $pretty = 0;
else $pretty = $_REQUEST['pretty'];
if (!isset($_REQUEST['asc'])) $asc=1;
else $asc = $_REQUEST['asc'];
if ($asc=="") $asc=1;
if (empty($pid1)) {
	$followspouse = 1;
	$pretty = 1;
}
$check_node = true;
$disp = true;

$title_string .= i18n::translate('Relationship Chart');
// -- print html header information
print_header($title_string);

if ($ENABLE_AUTOCOMPLETE) require PGV_ROOT.'js/autocomplete.js.htm';

// Lbox additions if installed ---------------------------------------------------------------------------------------------
if (PGV_USE_LIGHTBOX) {
	require PGV_ROOT.'modules/lightbox/lb_defaultconfig.php';
	require_once PGV_ROOT.'modules/lightbox/functions/lb_call_js.php';
}
// ------------------------------------------------------------------------------------------------------------------------------

if ($pid1) {
	//-- check if the id is valid
	$indirec = Person::getInstance($pid1);
	// Allow entry of i123 instead of I123
	if (!$indirec && $pid1!=strtoupper($pid1)) {
		$pid1=strtoupper($pid1);
		$indirec=Person::getInstance($pid1);
	}
	// Allow user to specify person without the prefix
	if (!$indirec && $GEDCOM_ID_PREFIX) {
		$pid1=$GEDCOM_ID_PREFIX.$pid1;
		$indirec=Person::getInstance($pid1);
	}
	if ($indirec) {
		$title_string.=':<br />'.$indirec->getFullName();
	} else {
		$pid1='';
	}
	if (!empty($_SESSION["pid1"]) && ($_SESSION["pid1"]!=$pid1)) {
		unset($_SESSION["relationships"]);
		$path_to_find=0;
	}
}
if ($pid2) {
	//-- check if the id is valid
	$indirec = Person::getInstance($pid2);
	// Allow entry of i123 instead of I123
	if (!$indirec && $pid2!=strtoupper($pid2)) {
		$pid1=strtoupper($pid2);
		$indirec=Person::getInstance($pid2);
	}
	// Allow user to specify person without the prefix
	if (!$indirec && $GEDCOM_ID_PREFIX) {
		$pid2=$GEDCOM_ID_PREFIX.$pid2;
		$indirec = Person::getInstance($pid2);
	}
	if ($indirec) {
		$title_string.=' '.i18n::translate('and').' '.$indirec->getFullName();
	} else {
		$pid2='';
	}
	if (!empty($_SESSION["pid2"]) && ($_SESSION["pid2"]!=$pid2)) {
		unset($_SESSION["relationships"]);
		$path_to_find=0;
	}
}
?>
<script language="JavaScript" type="text/javascript">
var pastefield;
function paste_id(value) {
	pastefield.value=value;
}
</script>
<div id="relationship_chart_options<?php print ($TEXT_DIRECTION=="ltr")?"":"_rtl";?>" style="position: relative; z-index:90; width:98%;">
<h2><?php print PrintReady($title_string);?></h2><br />
<!-- // Print the form to change the number of displayed generations -->
<?php
if ($view!="preview") {
	$Dbaseyoffset += 110; ?>
	<form name="people" method="get" action="relationship.php">
	<input type="hidden" name="path_to_find" value="<?php print $path_to_find ?>" />

	<table class="list_table <?php print $TEXT_DIRECTION ?>" style="align:<?php print ($TEXT_DIRECTION=="ltr"?"left":"right");?>; margin:0;">

	<!-- // Relationship header -->
	<tr><td colspan="2" class="topbottombar center">
	<?php print i18n::translate('Relationship Chart')?>
	</td>

	<!-- // Empty space -->
	<td>&nbsp;</td>

	<!-- // Options header -->
	<td colspan="2" class="topbottombar center">
	<?php print i18n::translate('Options:')?>
	</td></tr>

	<!-- // Person 1 -->
	<tr><td class="descriptionbox">
	<?php echo print i18n::translate('Person 1'), help_link('relationship_id'); ?>
	</td>
	<td class="optionbox vmiddle">
	<input tabindex="1" class="pedigree_form" type="text" name="pid1" id="pid1" size="3" value="<?php print $pid1 ?>" />
	<?php
	print_findindi_link("pid1","");?>
	</td>

	<!-- // Empty space -->
	<td></td>

	<!-- // Show details -->
	<td class="descriptionbox">
	<?php echo i18n::translate('Show Details'), help_link('show_full'); ?>
	</td>
	<td class="optionbox vmiddle">
	<input type="hidden" name="show_full" value="<?php print $show_full ?>" />
		<?php
	if (!$pretty && $asc==-1) print "<input type=\"hidden\" name=\"asc\" value=\"$asc\" />";
	print "<input tabindex=\"3\" type=\"checkbox\" name=\"showfull\" value=\"0\"";
	if ($show_full) print " checked=\"checked\"";
	print " onclick=\"document.people.show_full.value='".(!$show_full)."';\" />";?>
	</td></tr>

	<!-- // Person 2 -->
	<tr><td class="descriptionbox">
	<?php echo i18n::translate('Person 2'), help_link('relationship_id'); ?>
	</td>
	<td class="optionbox vmiddle">
	<input tabindex="2" class="pedigree_form" type="text" name="pid2" id="pid2" size="3" value="<?php print $pid2 ?>" />
		<?php
		print_findindi_link("pid2","");?>
	</td>

	<!-- // Empty space -->
	<td>&nbsp;</td>

	<!-- // Line up generations -->
	<td class="descriptionbox">
	<?php
	echo i18n::translate('Line up the same generations'), help_link('line_up_generations'); ?>
	</td>
	<td class="optionbox">
	<input tabindex="5" type="checkbox" name="pretty" value="2"
	<?php
	if ($pretty) print " checked=\"checked\"";
	print " onclick=\"expand_layer('oldtop1'); expand_layer('oldtop2');\"" ?> />
	</td></tr>

	<!-- // Empty line -->
	<tr><td class="descriptionbox">&nbsp;</td>
	<td class="optionbox">&nbsp;</td>

	<!-- // Empty space -->
	<td>&nbsp;</td>

	<!-- // Show oldest top -->
	<td class="descriptionbox">
	<div id="oldtop1" style="display:
	<?php
	if ($pretty) print "block";
	else print "none";
	?>">
	<?php echo i18n::translate('Show oldest top'), help_link('oldest_top'); ?>
	</div>
	</td><td class="optionbox">
	<div id="oldtop2" style="display:
	<?php
	if ($pretty) print "block";
	else print "none";?>">
	<input tabindex="4" type="checkbox" name="asc" value="-1"
	<?php
	if ($asc==-1) print " checked=\"checked\"";?>
	/>
	</div></td></tr>

	<!-- // Show path -->
	<tr><td class="descriptionbox">
	<?php $pass = false;
	if ((isset($_SESSION["relationships"]))&&((!empty($pid1))&&(!empty($pid2)))) {
		$pass = true;
		$i=0;
		$new_path=true;
		if (isset($_SESSION["relationships"][$path_to_find])) $node = $_SESSION["relationships"][$path_to_find];
		else $node = get_relationship($pid1, $pid2, $followspouse, 0, true, $path_to_find);
		if (!$node) {
			$path_to_find--;
			$check_node=$node;
		}
		foreach($_SESSION["relationships"] as $indexval => $node) {
			if ($i==0) print i18n::translate('Show path').": </td><td class=\"list_value\" style=\"padding: 3px;\">";
			if ($i>0) print " | ";
			if ($i==$path_to_find){
				print "<span class=\"error\" style=\"valign: middle\">".($i+1)."</span>";
				$new_path=false;
			}
			else {
				print "<a href=\"".encode_url("relationship.php?pid1={$pid1}&pid2={$pid2}&path_to_find={$i}&followspouse={$followspouse}&pretty={$pretty}&show_full={$show_full}&asc={$asc}")."\">".($i+1)."</a>\n";
			}
			$i++;
		}
		if (($new_path)&&($path_to_find<$i+1)&&($check_node)) print " | <span class=\"error\">".($i+1)."</span>";
		print "</td>";
	} else {
		if ((!empty($pid1))&&(!empty($pid2))) {
			if ((!displayDetailsById($pid1))&&(!showLivingNameById($pid1))) {
				$disp = false;
			} elseif ((!displayDetailsById($pid2))&&(!showLivingNameById($pid2))) {
				$disp = false;
			}
			if ($disp) {
				echo i18n::translate('Show path'), ": </td>";
				echo "\n\t\t<td class=\"optionbox\">";
				echo " <span class=\"error vmmiddle\">";
				$check_node = get_relationship($pid1, $pid2, $followspouse, 0, true, $path_to_find);
				echo $check_node ? "1" : "&nbsp;".i18n::translate('No results found.'), "</span></td>";
				$prt = true;
			}
		}
		if (!isset($prt)) {
			echo "&nbsp;</td><td class=\"optionbox\">&nbsp;</td>";
		}
	}
?>
	<!-- // Empty space -->
	<td></td>

	<!-- // Check relationships by marriage -->
	<td class="descriptionbox">
	<?php echo i18n::translate('Check relationships by marriage'), help_link('follow_spouse'); ?>
	</td>
	<td class="optionbox" id="followspousebox">
	<input tabindex="6" type="checkbox" name="followspouse" value="1"
	<?php
	if ($followspouse) {
		echo " checked=\"checked\"";
	}
	echo " onclick=\"document.people.path_to_find.value='-1';\""?> />
	</td>
	<?php
	if ((!empty($pid1))&&(!empty($pid2))&&($disp)) {
		echo "</tr><tr>";
		if (($disp)&&(!$check_node)) {
			echo "<td class=\"topbottombar wrap vmiddle center\" colspan=\"2\">";
			if (isset($_SESSION["relationships"])) {
				if ($path_to_find==0) {
					echo "<span class=\"error\">", i18n::translate('No link between the two individuals could be found.'), "</span><br />";
				} else {
					echo "<span class=\"error\">", i18n::translate('No other link between the two individuals could be found.'), "</span><br />";
				}
			}
			if (!$followspouse) {
				?>
				<script language="JavaScript" type="text/javascript">
				document.getElementById("followspousebox").className='facts_valuered';
				</script>
				<?php
				echo "<input class=\"error\" type=\"submit\" value=\"", i18n::translate('Check relationships by marriage'), "\" onclick=\"people.followspouse.checked='checked';\"/>";
			}
			echo "</td>";
		} else {
			echo "<td class=\"topbottombar vmiddle center\" colspan=\"2\"><input type=\"submit\" value=\"", i18n::translate('Find next path'), "\" onclick=\"document.people.path_to_find.value='", $path_to_find+1, "';\" /></td>\n";
		}
		$pass = true;
	}

	if ($pass == false) echo "</tr><tr><td colspan=\"2\" class=\"topbottombar wrap\">&nbsp;</td>";?>

	<!-- // Empty space -->
	<td></td>

	<!-- // View button -->
	<td class="topbottombar vmiddle center" colspan="2">
	<input tabindex="7" type="submit" value="<?php print i18n::translate('View')?>" />
	</td></tr>


	</table></form>
	<?php
}
else {
	$Dbaseyoffset=55;
	$Dbasexoffset=10;
}
?>
</div>
<?php
if ($show_full==0) {
	echo '<br /><span class="details2">', i18n::translate('Click on any of the boxes to get more information about that person.'), '</span><br />';
}
?>
<div id="relationship_chart<?php print ($TEXT_DIRECTION=="ltr")?"":"_rtl";?>" style="position:relative; z-index:1; width:98%;">
<?php
$maxyoffset = $Dbaseyoffset;
if ((!empty($pid1))&&(!empty($pid2))) {
	if (!$disp) {
		print "<br /><br />";
		print_privacy_error($CONTACT_EMAIL);
	}
	else {
		if (isset($_SESSION["relationships"][$path_to_find])) $node = $_SESSION["relationships"][$path_to_find];
		else $node = get_relationship($pid1, $pid2, $followspouse, 0, true, $path_to_find);
		if ($node!==false) {
			$_SESSION["pid1"] = $pid1;
			$_SESSION["pid2"] = $pid2;
			if (!isset($_SESSION["relationships"])) $_SESSION["relationships"] = array();
			$_SESSION["relationships"][$path_to_find] = $node;
			$yoffset = $Dbaseyoffset + 20;
			$xoffset = $Dbasexoffset;
			$colNum = 0;
			$rowNum = 0;
			$boxNum = 0;
			$previous="";
			$previous2="";
			$xs = $Dbxspacing+70;
			$ys = $Dbyspacing+50;
			// step1 = tree depth calculation
			if ($pretty) {
				$dmin=0;
				$dmax=0;
				$depth=0;
				foreach($node["path"] as $index=>$pid) {
					if (($node["relations"][$index]=="father")||($node["relations"][$index]=="mother")) {

					$depth++;
					if ($depth>$dmax) $dmax=$depth;
					if ($asc==0) $asc=1; // the first link is a parent link
					}
					if ($node["relations"][$index]=="child") {
						$depth--;
						if ($depth<$dmin) $dmin=$depth;
						if ($asc==0) $asc=-1; // the first link is a child link
					}
				}
				$depth=$dmax+$dmin;
				// need more yoffset before the first box ?
				if ($asc==1) $yoffset -= $dmin*($Dbheight+$ys);
				if ($asc==-1) $yoffset += $dmax*($Dbheight+$ys);
				$rowNum = ($asc==-1) ? $depth : 0;
			}
			$maxxoffset = -1*$Dbwidth-20;
			$maxyoffset = $yoffset;
			if ($TEXT_DIRECTION=="ltr") {
				$rArrow = $PGV_IMAGES["rarrow"]["other"];
				$lArrow = $PGV_IMAGES["larrow"]["other"];
			} else {
				$rArrow = $PGV_IMAGES["larrow"]["other"];
				$lArrow = $PGV_IMAGES["rarrow"]["other"];
			}
			foreach($node["path"] as $index=>$pid) {
				print "\r\n\r\n<!-- Node:{$index} -->\r\n";
				$linex = $xoffset;
				$liney = $yoffset;
				$mfstyle = "NN";
				$indirec = find_person_record($pid, PGV_GED_ID);
				if (strpos($indirec, "1 SEX F")!==false) $mfstyle="F";
				if (strpos($indirec, "1 SEX M")!==false) $mfstyle="";
				$arrow_img = $PGV_IMAGE_DIR."/".$PGV_IMAGES["darrow"]["other"];
				if (($node["relations"][$index]=="father")||($node["relations"][$index]=="mother")) {
					$line = $PGV_IMAGES["vline"]["other"];
					$liney += $Dbheight;
					$linex += $Dbwidth/2;
					$lh = 54;
					$lw = 3;
					//check for paternal grandparent relationship
					if ($pretty) {
						if ($asc==0) $asc=1;
						if ($asc==-1) $arrow_img = $PGV_IMAGE_DIR."/".$PGV_IMAGES["uarrow"]["other"];
						$lh=$ys;
						$linex=$xoffset+$Dbwidth/2;
						// put the box up or down ?
						$yoffset += $asc*($Dbheight+$lh);
						$rowNum += $asc;
						if ($asc==1) $liney = $yoffset-$lh; else $liney = $yoffset+$Dbheight;
						// need to draw a joining line ?
						if ($previous=="child" and $previous2!="parent") {
							$joinh = 3;
							$joinw = $xs/2+2;
							$xoffset += $Dbwidth+$xs;
							$colNum ++;
							//$rowNum is inherited from the box immediately to the left
							$linex = $xoffset-$xs/2;
							if ($asc==-1) $liney=$yoffset+$Dbheight; else $liney=$yoffset-$lh;
							$joinx = $xoffset-$xs;
							$joiny = $liney-2-($asc-1)/2*$lh;
							echo "<div id=\"joina", $index, "\" style=\"position:absolute; ", $TEXT_DIRECTION=="ltr"?"left":"right", ":", $joinx + $Dbxspacing, "px; top:", $joiny + $Dbyspacing, "px; z-index:-100; \" align=\"center\"><img src=\"", $PGV_IMAGE_DIR, "/", $PGV_IMAGES["hline"]["other"], "\" align=\"left\" width=\"", $joinw, "\" height=\"", $joinh, "\" alt=\"\" /></div>\n";
							$joinw = $xs/2+2;
							$joinx = $joinx+$xs/2;
							$joiny = $joiny+$asc*$lh;
							echo "<div id=\"joinb", $index, "\" style=\"position:absolute; ", $TEXT_DIRECTION=="ltr"?"left":"right", ":", $joinx + $Dbxspacing, "px; top:", $joiny + $Dbyspacing, "px; z-index:-100; \" align=\"center\"><img src=\"", $PGV_IMAGE_DIR, "/", $PGV_IMAGES["hline"]["other"], "\" align=\"left\" width=\"", $joinw, "\" height=\"", $joinh, "\" alt=\"\" /></div>\n";
						}
						$previous2=$previous;
						$previous="parent";
					}
					else $yoffset += $Dbheight+$Dbyspacing+50;
				}
				if ($node["relations"][$index]=="sibling") {
					$arrow_img = $PGV_IMAGE_DIR."/".$rArrow;
					if ($mfstyle=="F") $node["relations"][$index]="sister";
					if ($mfstyle=="") $node["relations"][$index]="brother";
					$xoffset += $Dbwidth+$Dbxspacing+70;
					$colNum ++;
					//$rowNum is inherited from the box immediately to the left
					$line = $PGV_IMAGES["hline"]["other"];
					$linex += $Dbwidth;
					$liney += $Dbheight/2;
					$lh = 3;
					$lw = 70;
					if ($pretty) {
						$lw = $xs;
						$linex = $xoffset-$lw;
						$liney = $yoffset+$Dbheight/4;
						$previous2=$previous;
						$previous="";
					}
				}
				if ($node["relations"][$index]=="spouse") {
					$arrow_img = $PGV_IMAGE_DIR."/".$rArrow;
					if ($mfstyle=="F") $node["relations"][$index]="wife";
					if ($mfstyle=="") $node["relations"][$index]="husband";
					$xoffset += $Dbwidth+$Dbxspacing+70;
					$colNum ++;
					//$rowNum is inherited from the box immediately to the left
					$line = $PGV_IMAGES["hline"]["other"];
					$linex += $Dbwidth;
					$liney += $Dbheight/2;
					$lh = 3;
					$lw = 70;
					if ($pretty) {
						$lw = $xs;
						$linex = $xoffset-$lw;
						$liney = $yoffset+$Dbheight/4;
						$previous2=$previous;
						$previous="";
					}
				}
				if ($node["relations"][$index]=="child") {
					if ($mfstyle=="F") $node["relations"][$index]="daughter";
					if ($mfstyle=="") $node["relations"][$index]="son";
					$line = $PGV_IMAGES["vline"]["other"];
					$liney += $Dbheight;
					$linex += $Dbwidth/2;
					$lh = 54;
					$lw = 3;
					if ($pretty) {
						if ($asc==0) $asc=-1;
						if ($asc==1) $arrow_img = $PGV_IMAGE_DIR."/".$PGV_IMAGES["uarrow"]["other"];
						$lh=$ys;
						$linex = $xoffset+$Dbwidth/2;
						// put the box up or down ?
						$yoffset -= $asc*($Dbheight+$lh);
						$rowNum -= $asc;
						if ($asc==-1) $liney = $yoffset-$lh; else $liney = $yoffset+$Dbheight;
						// need to draw a joining line ?
						if ($previous=="parent" and $previous2!="child") {
							$joinh = 3;
							$joinw = $xs/2+2;
							$xoffset += $Dbwidth+$xs;
							$colNum ++;
							//$rowNum is inherited from the box immediately to the left
							$linex = $xoffset-$xs/2;
							if ($asc==1) $liney=$yoffset+$Dbheight; else $liney=$yoffset-($lh+$Dbyspacing);
							$joinx = $xoffset-$xs;
							$joiny = $liney-2+($asc+1)/2*$lh;
							print "<div id=\"joina$index\" style=\"position:absolute; ".($TEXT_DIRECTION=="ltr"?"left":"right").":".($joinx+$Dbxspacing)."px; top:".($joiny+$Dbyspacing)."px; z-index:-100; \" align=\"center\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["hline"]["other"]."\" align=\"left\" width=\"".$joinw."\" height=\"".$joinh."\" alt=\"\" /></div>\n";
							$joinw = $xs/2+2;
							$joinx = $joinx+$xs/2;
							$joiny = $joiny-$asc*$lh;
							print "<div id=\"joinb$index\" style=\"position:absolute; ".($TEXT_DIRECTION=="ltr"?"left":"right").":".($joinx+$Dbxspacing)."px; top:".($joiny+$Dbyspacing)."px; z-index:-100; \" align=\"center\"><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["hline"]["other"]."\" align=\"left\" width=\"".$joinw."\" height=\"".$joinh."\" alt=\"\" /></div>\n";
						}
						$previous2=$previous;
						$previous="child";
					}
					else $yoffset += $Dbheight+$Dbyspacing+50;
				}
				if ($yoffset > $maxyoffset) $maxyoffset = $yoffset;
				$plinex = $linex;
				$pxoffset = $xoffset;

				// Adjust all box positions for proper placement with respect to other page elements
				if ($BROWSERTYPE=="mozilla" && $TEXT_DIRECTION=="rtl") $pxoffset += 10;
				else $pxoffset -= 3;
				$pyoffset = $yoffset - 2;

				if ($index>0) {
					if ($TEXT_DIRECTION=="rtl" && $line!=$PGV_IMAGES["hline"]["other"]) {
						print "<div id=\"line$index\" dir=\"ltr\" style=\"background:none; position:absolute; right:".($plinex+$Dbxspacing)."px; top:".($liney+$Dbyspacing)."px; width:".($lw+$lh*2)."px; z-index:-100; \" align=\"right\">";
						print "<img src=\"$PGV_IMAGE_DIR/$line\" align=\"right\" width=\"$lw\" height=\"$lh\" alt=\"\" />\n";
						print "<br />";
						print $pgv_lang[$node["relations"][$index]]."\n";
						print "<img src=\"$arrow_img\" border=\"0\" align=\"middle\" alt=\"\" />\n";
					}
					else {
						print "<div id=\"line$index\" style=\"background:none;  position:absolute; ".($TEXT_DIRECTION=="ltr"?"left":"right").":".($plinex+$Dbxspacing)."px; top:".($liney+$Dbyspacing)."px; width:".($lw+$lh*2)."px; z-index:-100; \" align=\"".($lh==3?"center":"left")."\"><img src=\"$PGV_IMAGE_DIR/$line\" align=\"left\" width=\"$lw\" height=\"$lh\" alt=\"\" />\n";
						print "<br />";
						print "<img src=\"$arrow_img\" border=\"0\" align=\"middle\" alt=\"\" />\n";
						if ($lh == 3) print "<br />"; // note: $lh==3 means horiz arrow
						print $pgv_lang[$node["relations"][$index]]."\n";
					}
					print "</div>\n";
				}
				// Determine the z-index for this box
				$boxNum ++;
				if ($TEXT_DIRECTION=="rtl" && $BROWSERTYPE=="mozilla") {
					if ($pretty) $zIndex = ($colNum * $depth - $rowNum + $depth);
						else $zIndex = $boxNum;
				} else {
					if ($pretty) $zIndex = 200 - ($colNum * $depth + $rowNum);
					else $zIndex = 200 - $boxNum;
				}

				print "<div id=\"box$pid.0\" style=\"position:absolute; ".($TEXT_DIRECTION=="ltr"?"left":"right").":".$pxoffset."px; top:".$pyoffset."px; width:".$Dbwidth."px; height:".$Dbheight."px; z-index:".$zIndex."; \"><table><tr><td colspan=\"2\" width=\"$Dbwidth\" height=\"$Dbheight\">";
				print_pedigree_person($pid, 1, ($view!="preview"));
				print "</td></tr></table></div>\n";
			}

			$sentence = getRelationshipSentence(array_slice($node['relations'], 1), $pid1, $pid2);
			if($sentence != false) {
				print "<div style=\"position:absolute; ".($TEXT_DIRECTION=="ltr"?"left":"right").":1px; top:".abs($Dbaseyoffset-70)."px; z-index:1;\">";
				print "<h4>";
				print $sentence;
				print "</h4></div>\n";
			}
		}
	}
}

$maxyoffset += 100;
?>
</div>
<script language="JavaScript" type="text/javascript">
	relationship_chart_div = document.getElementById("relationship_chart");
	if (!relationship_chart_div) relationship_chart_div = document.getElementById("relationship_chart_rtl");
	if (relationship_chart_div) {
		relationship_chart_div.style.height = <?php print ($maxyoffset-50); ?> + "px";
		relationship_chart_div.style.width = "100%";
	}
</script>
<?php
print_footer();

?>
