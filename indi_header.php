<?php
// Individual Page
//
// Display all of the information about an individual
//
// webtrees: Web based Family History software
// Copyright (C) 2011 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// $Id: individual.php 10734 2011-02-09 03:28:42Z nigel $

//define('WT_SCRIPT_NAME', 'indi_header.php');
//require './includes/session.php';

//$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;

// -- array of GEDCOM elements that will be found but should not be displayed
$nonfacts = array("FAMS", "FAMC", "MAY", "BLOB", "CHIL", "HUSB", "WIFE", "RFN", "_WT_OBJE_SORT", "");

$nonfamfacts = array(/*"NCHI",*/ "UID", "");

$controller=new WT_Controller_Individual();
$controller->init();

// tell tabs that use jquery that it is already loaded
//define('WT_JQUERY_LOADED', 1);

// We have finished writing session data, so release the lock
//Zend_Session::writeClose();

//print_header($controller->getPageTitle());

if (!$controller->indi) {
	echo "<b>", WT_I18N::translate('Unable to find record with ID'), "</b><br /><br />";
	print_footer();
	exit;
}
else if (!$controller->indi->canDisplayName()) {
	print_privacy_error();
	print_footer();
	exit;
}
$linkToID=$controller->pid; // -- Tell addmedia.php what to link to

if (strlen($controller->indi->getAddName()) > 0) echo "<span class=\"name_head\">", PrintReady($controller->indi->getAddName()), "</span><br />";

echo '<div id="indi_mainimage">'; // highlighted image
	if ($controller->canShowHighlightedObject()) {
		echo $controller->getHighlightedObject();
	}
echo '</div>';
echo
	'<div id="indi_header">', // container for indi header details
		'<h1>'; // Main name
			echo PrintReady($controller->indi->getFullName());
			if (WT_USER_IS_ADMIN) {
				$user_id=get_user_from_gedcom_xref(WT_GED_ID, $controller->pid);
				if ($user_id) {
					$user_name=get_user_name($user_id);
					echo "&nbsp;";
					echo printReady("<a href=\"admin_users.php?action=edituser&amp;username={$user_name}\">({$user_name})</a>");
				}
			}
	echo
		'</h1>',
		'<div id="header_accordion">'; // accordion for name details and other facts
		if ($controller->indi->canDisplayDetails()) {
			echo
				'<h3>Name Details</h3>', //1st accordion element
				'<div id="indi_name_details">';
				//Display name details
					$globalfacts=$controller->getGlobalFacts();
					$nameSex = array('NAME', 'SEX');
					foreach ($globalfacts as $key=>$value) {
						if ($key == 0) {
						// First name
							$fact = $value->getTag();
							if (in_array($fact, $nameSex)) {
								if ($fact=="NAME") $controller->print_name_record($value);
							}
						} else {
							// 2nd and more names
							$fact = $value->getTag();
							if (in_array($fact, $nameSex)) {
								if ($fact=="NAME") $controller->print_name_record($value);
							}
						}
					}
			echo '</div>';
			//Display facts
			echo
				'<h3>Other facts</h3>', // 2nd accordion element
				'<div id="indi_facts">';
					//Display gender
					foreach ($globalfacts as $key=>$value) {
						$fact = $value->getTag();
						if (in_array($fact, $nameSex)) {
							if ($fact=="SEX") $controller->print_sex_record($value);
						}
					}
					// Display summary birth/death info.
					$summary=$controller->indi->format_first_major_fact(WT_EVENTS_BIRT, 2);
					// If alive display age
					if (!$controller->indi->isDead()) {
						$bdate=$controller->indi->getBirthDate();
						$age = WT_Date::GetAgeGedcom($bdate);
						if ($age!="") $summary.= "<dl><dt class=\"label\">".WT_I18N::translate('Age')."</dt><span class=\"field\">".get_age_at_event($age, true)."</span></dl>";
					}
					$summary.=$controller->indi->format_first_major_fact(WT_EVENTS_DEAT, 2);
					if ($SHOW_LDS_AT_GLANCE) {
						$summary.="<dl><span><b>".get_lds_glance($controller->indi->getGedcomRecord())."</b></span></dl>";
					}
					if ($summary) {
						echo $summary;
					}
			echo '</div>'; // close #indi_facts
		}
	echo '</div>'; // close #header_accordion
echo '</div>'; // close #indi_header
echo WT_JS_START,'jQuery("#header_accordion").accordion({active:false, autoHeight:true, collapsible: true});',	WT_JS_END; //accordion details
