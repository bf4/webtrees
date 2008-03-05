<?php
/**
 * Slovak Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PGV Development Team
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
 *
 * @author PGV Developers
 * @package PhpGedView
 * @subpackage Languages
 * @author Peter Moravčík
 * @version $Id: faqlist.sk.php 2449 2008-01-04 11:57:29Z canajun2eh $
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Nemáte priamy prístup k súboru zo slovenčinou.";
	exit;
}

//-- Define Slovak Frequently Asked Questions
$faqlist["FAQ_050_head"] = "POĎAKOVANIE";
$faqlist["FAQ_010_head"] = "VITAJTE V #GLOBALS[GEDCOM_TITLE]# FAQ";
$faqlist["FAQ_000_head"] = "\"FAQ\": UŽ SOM TO POČUL, ALE ČO TO VLASTNE JE?";
$faqlist["FAQ_000_body"] = "b>FAQ</b> je skratka pre <b>F</b>requently <b>A</b>sked <b>Q</b>uestion (Často kladené otázky).<br /><br />FAQ zoznam je zoznam často kladených otázok a odpovedí na ne. Sú zapisované PhpGedView týmom.";

?>