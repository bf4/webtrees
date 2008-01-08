<?php
/**
 * Danish FAQ file for PhpGedView.
 *
 * PhpGedView: Genealogy Viewer
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
 * @package PhpGedView
 * @author Jens Hyllegaard
 * @created 2007-09-03
 * @version $Id: faqlist.da.php 2338 2007-12-01 12:21:20Z hylle $
 */

if (stripos($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Du kan ikke tilgå en sprogfil direkte.";
	exit;
}

//-- Define Danish Frequently Asked Questions
$faqlist["FAQ_000_head"] = "\"FAQ\": Jeg har hørt om dette, med hvad er det?";
$faqlist["FAQ_000_body"] = "<b>FAQ</b> er en forkortelse for <b>F</b>requently <b>A</b>sked <b>Q</b>uestion. (Ofte stillet spørgsmål)<br /><br />FAQ\'en viser en liste over spørgsmål (sammen med deres svar) der optræder jævnligt. Den er sammensat af PhpGedView holdet og opdateres jævnligt.";
$faqlist["FAQ_010_head"] = "Velkommen til #GLOBALS[GEDCOM_TITLE]# FAQ";
$faqlist["FAQ_022_head"] = "Hvorfor skal jeg registrere mig?";
$faqlist["FAQ_025_head"] = "Hvor lang tid går der før min registrering er godkendt?";

?>
