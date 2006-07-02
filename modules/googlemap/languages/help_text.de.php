<?php
/**
 * German Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  PGV Development Team
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
 * @subpackage GoogleMap
 * @version $Id$
 */
if (preg_match("/help_text\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
  print "You cannot access a language file directly.";
  exit;
}

$pgv_lang["GOOGLEMAP_API_KEY"]          = "Google-Map API Schlüssel";
$pgv_lang["GOOGLEMAP_API_KEY_help"]     = "~#pgv_lang[GOOGLEMAP_API_KEY]#~<br /><br />Tragen Sie hier Ihren Google-Map API Schlüssel (key) ein.  Den Schlüssel bekommen Sie hier: <a target=\"_blank\" href=\"http://www.google.com/apis/maps/\">http://www.google.com/apis/maps/</a>";

$pgv_lang["GOOGLEMAP_MAP_TYPE"]	        = "Standard Kartentyp";
$pgv_lang["GOOGLEMAP_MAP_TYPE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_TYPE]#~<br /><br />Darstellungstyp der Karte, die normalerweise angezeigt wird. Das ist entweder Straßenkarte, Satellitenfoto oder Kombination.";

$pgv_lang["GOOGLEMAP_MAP_SIZE"]	        = "Größe der Karte";
$pgv_lang["GOOGLEMAP_MAP_SIZE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_SIZE]#~<br /><br />Die Abmessungen der Karte (in Pixel), so, wie sie auf der Personenseite gezeigt wird.";

$pgv_lang["GOOGLEMAP_MAP_ZOOM"]	        = "Zoomfaktor der Karte";
$pgv_lang["GOOGLEMAP_MAP_ZOOM_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_ZOOM]#~<br /><br />Kleinster und größter Zoomfaktor der Karte. 1 zeigt die gesamte Erde, 15 zeigt einzelne Häuser. Achtung: Stufe 15 wird von Google noch nicht an allen Orten angeboten!";

$pgv_lang["GOOGLEMAP_PRECISION"]        = "Genauigkeit der Breiten- und Längengrade";
$pgv_lang["GOOGLEMAP_PRECISION_help"]   = "~#pgv_lang[GOOGLEMAP_PRECISION]#~<br /><br />Hier wird die Genauigkeit der entsptrechenden Hierarchiestufe bestimmt, wenn ein neuer Ort eingetragen wird. Beispielweise könnte für ein Land die Genauigkeit '0' ( = 0 Nachkommastellen) eingestellt werden, wogegen eine Stadt 3 oder 4 Stellen benötigt.";

$pgv_lang["PL_EDIT_LOCATION"]           = "Ortsdaten bearbeiten oder löschen";
$pgv_lang["PL_EDIT_LOCATION_help"]      = "~#pgv_lang[PL_EDIT_LOCATION_help]#~<br /><br />Hier können Sie die Ortsdaten ändern oder löschen. Ein Klick auf 'bearbeiten' öffnet ein neues Bearbeitungsfenster für die Ortsdaten.<br>Ein Klick auf 'löschen' ist nur möglich, wenn keine anderen Orte unterhalb dieser Hierarchie definiert sind.";

$pgv_lang["PL_ADD_LOCATION"]            = "Ortsdaten hinzufügen";
$pgv_lang["PL_ADD_LOCATION_help"]       = "~#pgv_lang[PL_ADD_LOCATION_help]#~<br /><br />Hiermit können Sie einen Ort in die Ortetabelle eintragen. Der Ort wird auf der aktuellen Hierarchiestufe hinzugefügt!";

$pgv_lang["PL_IMPORT_GEDCOM"]           = "Ortsdaten aus GEDCOM importieren";
$pgv_lang["PL_IMPORT_GEDCOM_help"]      = "~#pgv_lang[PL_IMPORT_LOCATION_help]#~<br /><br />Die aktuelle GEDCOM-Datei wird durchsucht und alle Orte werden in die Orte-Tabelle eingetragen. Soweit Breiten- und Längengrade vorhanden sind, werden diese auch importiert.";

$pgv_lang["PL_IMPORT_ALL_GEDCOM"]       = "Ortsdaten aus allen GEDCOM importieren";
$pgv_lang["PL_IMPORT_ALL_GEDCOM_help"]  = "~#pgv_lang[PL_IMPORT_ALL_LOCATION_help]#~<br /><br />Alle GEDCOM-Dateien werden durchsucht und alle Orte werden in die Orte-Tabelle eingetragen. Soweit Breiten- und Längengrade vorhanden sind, werden diese auch importiert.";

$pgv_lang["PL_IMPORT_FILE"]             = "Ortsdaten aus Datei importieren";
$pgv_lang["PL_IMPORT_FILE_help"]        = "~#pgv_lang[PL_IMPORT_FILE_help]#~<br /><br />Die Datei muß als CSV-Datei lokal vorliegen. Als Trennzeichen wird ';' (Semikolon) erwartet.";

$pgv_lang["PL_EXPORT_FILE"]             = "Ortsdaten exportieren";
$pgv_lang["PL_EXPORT_FILE_help"]        = ""~#pgv_lang[PL_EXPORT_FILE_help]#~<br /><br />Mit dieser Option werden die Ortsdaten der aktuelle Ansicht und aller iht nachgeordneten Hierarchiestufen in eine Datei exportiert. Wenn also z.B. Deutschland ausgewählt ist und alle Bundesländer sichtbar sind, werden die Ortsdaten aller Bundesländer, Kreise/Städte und Gemeinden exportiert.";

$pgv_lang["PL_EXPORT_ALL_FILE"]         = "Alle Ortsdaten exportieren";
$pgv_lang["PL_EXPORT_ALL_FILE_help"]    = "~#pgv_lang[PL_EXPORT_ALL_FILE_help]#~<br /><br />Mit dieser Option werden alle Ortsdaten exportiert und auf den PC heruntergeladen.";

// Help texts for places_edit.php

$pgv_lang["PLE_PLACES"]                 = "Ortsname";
$pgv_lang["PLE_PLACES_help"]            = "~#pgv_lang[PL_PLACES_help]#~<br /><br />Hier kann der Ortsname eingetragen bzw. geändert werden.";

$pgv_lang["PLE_PRECISION"]              = "Genauigkeit";
$pgv_lang["PLE_PRECISION_help"]         = "~#pgv_lang[PL_PRECISION_help]#~<br /><br />Hier wird die Genauigkeit der Detaildarstellung eingetragen. Dieser Wert bestimmt die Anzahl der Nachkommastellen der Längen- und Breitengrade.";

$pgv_lang["PLE_LATLON_CTRL"]            = "Breiten- / Längengrad";
$pgv_lang["PLE_LATLON_CTRL_help"]       = "~#pgv_lang[PL_LATLON_CTRL_help]#~<br /><br />Hier können Breiten- und Längengrad eingetragen werden. Zuerst bitte den Bereich (N/S oder O/W) auswählen. Danach den Breiten- oder Längengrad als Dezimalwert eintragen. <br>Der Dezimalwert errechnet sich anhand folgender Formel:<br>Grad_dezimal = ((Sekunden / 60) + Minuten) / 60 + Grad.";

$pgv_lang["PLE_ZOOM"]                   = "Zoomstufe";
$pgv_lang["PLE_ZOOM_help"]              = "~#pgv_lang[PL_ZOOM_help]#~<br /><br />Hier wird die Vergrößerungsstufe eingetragen. Der Wert stellt die kleinste Zoomstufe dieses Ortes auf der Karte dar.";

$pgv_lang["PLE_ICON"]                   = "Icon";
$pgv_lang["PLE_ICON_help"]              = "~#pgv_lang[PL_ICON_help]#~<br /><br />Hier können Sie ein Icon bestimmen oder entfernen. Mit dem Link kann die Flagge bestimmt werden, die zu diesem Ort angezeigt werden soll.";

$pgv_lang["PLE_FLAGS"]                  = "Flaggen";
$pgv_lang["PLE_FLAGS_help"]             = "~#pgv_lang[PL_FLAGS_help]#~<br /><br />Aus dieser Liste wählen Sie das Land aus, aus dessen Flaggen Sie eine auswählen wollen. Wenn keine Flaggen angezeigt werden, sind auch noch keine Flaggen für dieses Land hinterlegt.";

?>
