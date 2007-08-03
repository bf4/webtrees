<?php
/**
 * German Language file for PhpGedView.
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
 * @package PhpGedView
 * @subpackage SiteMap
 * @version $Id$
 */
if (preg_match("/help_text\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
  print "You cannot access a language file directly.";
  exit;
}

$pgv_lang["SITEMAP"]                = "Sitemap Information";
$pgv_lang["SITEMAP_help"]           = "~#pgv_lang[SITEMAP]#~<br /><br />Mit dieser Seite können Sie eine Sitemap generieren. Sitemaps können von Suchmaschinen zur Optimierung der Indizierung Ihrer Seiten benutzt werdene. Dazu wird eine Datei mit allen Links die der Suchmaschine bekannt gemacht werden sollen angelgt.<br>Dieses Werkzeug erzeugt eine Sitemap Datei pro GEDCOM und (falls mehr als eine Sitemap-Datei generiert wurde) eine Siteindex-Datei. Die generierten Dateien sollten Sie in das Basisverzeichnis von phpGedView kopieren.<br>Zur Zeit werden Sitemap-Dateien nur von Google genutzt. Weitere Informationen auf:<br><a href=\"https://www.google.com/webmasters/sitemaps/docs/en/about.html\">About Google webmaster tools</a>";

$pgv_lang["SM_GEDCOM_SELECT"]       = "GEDCOMs auswählen";
$pgv_lang["SM_GEDCOM_SELECT_help"]  = "~#pgv_lang[SM_GEDCOM_SELECT]#~<br /><br />Wählen Sie hier die GEDCOMs aus, für die eine Sitemap-Datei generiert werden soll. Sie müssen mindestens ein GEDCOM auswählen.";

$pgv_lang["SM_ITEM_SELECT"]         = "Seiten auswählen";
$pgv_lang["SM_ITEM_SELECT_help"]    = "~#pgv_lang[SM_ITEM_SELECT]#~<br /><br />Wählen Sie hier die relevanten Seiten für die Sitemap-Datei aus. Für alle ausgewählten Seiten wird eine Priorität relativ zu den anderen Seiten innerhalb der Sitemap festgelegt.<br>Auch wird die voraussichtliche Häufigkeit der Aktualisierung eingestellt, in der sich die Daten in diesen Seiten ändern. Das beeinflusst natürlich auch die Häufigkeit der Besuche durch die Suchmaschine und beeinflusst so den Traffic, den die Seite erzeugt.";

?>
