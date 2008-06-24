<?php
/**
 * German language file for Lightbox Album module
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 to 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 * @translator  Gerd Kroll
 */

//-- security check, only allow access from module.php
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Direkter Sprach-Dateien Zugriff ist nicht erlaubt.";
}

// Added in VERSION 4.1.6
$pgv_lang["lb_tt_balloonLegend"]	= "Album Ordner Miniaturbildbereich - Links (oben)";
$pgv_lang["lb_tt_balloon_help"]	= "~#pgv_lang[lb_tt_balloonLegend]#~<br />Durch diese Option können Sie bestimmen, ob die Links über jedem Miniaturbild eine Sprechblase oder einen normalen Tip aufrufen.<br /><br />Diese Links zeigen Ihnen Bemerkungen und Details über das Medien-Objekt.<br />";



$pgv_lang["mediatabLegend"]			= "Multimedia Ordner Darstellung";
$pgv_lang["mediatab_help"]			= "~#pgv_lang[mediatabLegend]#~<br />Hier können Sie bestimmen, ob der gewöhnliche Medien-Ordner auf der #pgv_lang[indi_info]# Seite gezeigt werden soll oder nicht.<br /><br />Wenn Sie <b>#pgv_lang[hide]#</b> wählen, wird nur der <b>#pgv_lang[lightbox]#</b> Ordner gezeigt, und dieser Ordner wird dann auch auf <b>#pgv_lang[media]#</b> umbenannt.<br />";
$pgv_lang["lb_al_head_linksLegend"]	= "Album Ordner Kopfbereich Links-Darstellung";
$pgv_lang["lb_al_head_links_help"]	= "~#pgv_lang[lb_al_head_linksLegend]#~<br />Hier können Sie bestimmen, ob die Links im Kopfbereich des #pgv_lang[lightbox]# Ordners nur mit Ikonen, nur mit Text, oder mit Ikonen und Text dargestellt werden sollen.  Diese Links sind für die Option-Einstellung, oder für das Bearbeiten von neuen Medien-Objekten gedacht.<br /><br />Die <b>#pgv_lang[lb_icon]#</b> Wahl ist wahrscheinlich nicht sehr nützlich, da Sie die Funktion des jeweiligen Ikons nur durch Verschieben des Maus-Pfeils über das Ikon erkennen können.<br />";
$pgv_lang["lb_al_thumb_linksLegend"]= "Album Ordner Miniaturbilder Links-Darstellung";
$pgv_lang["lb_al_thumb_links_help"]	= "~#pgv_lang[lb_al_thumb_linksLegend]#~<br />Hier können Sie bestimmen, ob der Links-Bereich unter jedem Miniaturbild nur Ikone oder nur Text enthalten soll.  Diese Links sind für das Bearbeiten oder Löschen von Medien-Objekten gedacht.<br />";
$pgv_lang["lb_ml_thumb_linksLegend"]= "Minaturbilder Links-Darstellung";
$pgv_lang["lb_ml_thumb_links_help"]	= "~#pgv_lang[lb_ml_thumb_linksLegend]#~<br />Hier können Sie bestimmen, ob links Links über den Details jedes Medien-Objekts in der Medien-Liste nur mit Ikonen, nur mit Text, oder mit Ikonen und Text dargestellt werden sollen.  Diese Links sind für das Bearbeiten oder Löschen von Medien-Objekten gedacht.<br /><br />Wenn Sie hier <b>#pgv_lang[lb_none]#</b> wählen, sind die Links völlig versteckt, genau als ob der Benutzer keine Editier-Rechte hätte.<br />";
$pgv_lang["lb_ss_speedLegend"]		= "Dia-Vortrag Geschwindigkeit";
$pgv_lang["lb_ss_speed_help"]		= "~#pgv_lang[lb_ss_speedLegend]#~<br />Diese Option bestimmt die Wartezeit zwischen Bildern des Dia-Vortrags.<br />";
$pgv_lang["lb_transitionLegend"]	= "Dia-Wechsel Geschwindigkeit";
$pgv_lang["lb_transition_help"]		= "~#pgv_lang[lb_transitionLegend]#~<br />Diese Option bestimmt die Geschwindigkeit des Diawechsels.  Ihre Wahl ist für den laufenden Diavortrag gültig.  Sie ist auch für den manuellen Diawechsel gültig.<br /><br />Die <b>#pgv_lang[lb_none]#</b> Option bestimmt dass das alte Bild sofort durch ein neues ersetzt wird, ohne dass Änderungen der Bildgröße beobachtet werden können.<br />";
$pgv_lang["lb_music_fileLegend"]	= "Dia-Vortrag Tonspur";
$pgv_lang["lb_music_file_help"]		= "~#pgv_lang[lb_music_fileLegend]#~<br />Diese Option bestimmt die Tonspur, die während des Dia-Vortrags gespielt werden soll.  Das leere Feld bestimmt, dass während des Vortrags kein Ton spielt.<br /><br />Bitte beachten Sie, dass nur Dateien im mp3 Format unterstützt sind.<br />";
 
?>