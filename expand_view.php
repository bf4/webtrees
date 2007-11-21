<?php
/**
 * Used by AJAX to load the expanded view inside person boxes
 * 
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
require_once("config.php");
require_once("includes/person_class.php");

$pid = $_REQUEST['pid'];
$person = Person::getInstance($pid);
if (!$person->canDisplayDetails()) return $pgv_lang['private'];

$nonfacts = array("SEX","FAMS","FAMC","NAME","TITL","NOTE","SOUR","SSN","OBJE","HUSB","WIFE","CHIL","ALIA","ADDR","PHON","SUBM","_EMAIL","CHAN","URL","EMAIL","WWW","RESI","_UID","_TODO","REFN");
$person->add_family_facts(false);
$subfacts = $person->getIndiFacts();
	  
sort_facts($subfacts);

$f2 = 0;
/* @var $event Event */
foreach($subfacts as $indexval => $event) {
	if ($event->canShowDetails()) {	  	
			if ($f2>0) print "<br />\n";
			$f2++;
			// handle ASSO record
		if ($event->getTag()=='ASSO') {
			print_asso_rela_record($pid, $event->getGedComRecord(), false);
				continue;
			}
		$fact = $event->getTag();
		$details = $event->getDetail();
					 print "<span class=\"details_label\">";
		print $event->getLabel();
					 print "</span> ";
		$details = $event->getDetail();
				if ($details!="Y" && $details!="N") print PrintReady($details);
		print_fact_date($event, false, false, $fact, $pid, $person->getGedcomRecord());
			//-- print spouse name for marriage events
		$famid = $event->getFamilyId();
		$spouseid = $event->getSpouseId();
		if (!empty($spouseid)) {
			$spouse = Person::getInstance($spouseid);
			if (!is_null($spouse)) {
				print " <a href=\"individual.php?pid=$spouseid&amp;ged=$GEDCOM\">";
				print PrintReady($spouse->getName());
				print "</a>";
				print " - ";
				}
			}
		if (!empty($famid)) {
			print "<a href=\"family.php?famid=$famid\">[".$pgv_lang["view_family"]."]</a>\n";
		}
		print_fact_place($event, true, true);
	  }
}
?>