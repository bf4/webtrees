<?php
/**
 * English FAQ texts
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007  PGV Development Team
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
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

$faqlist["FAQ_000_head"] = "\"FAQ\": I've heard of this, but what is it?";
$faqlist["FAQ_000_body"] = "<b>FAQ</b> is an acronym for <b>F</b>requently <b>A</b>sked <b>Q</b>uestion.<br /><br />The FAQ list is a list of questions (together with their answers) that occur frequently.  It has been compiled by the PhpGedView team, and is updated frequently.";

?>
