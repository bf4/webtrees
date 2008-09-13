<?php
/**
 * German Language file for PhpGedView.
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
 * @subpackage GoogleMap
 * @translator Christian Helms
 * @translator Gerd Kroll
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$pgv_lang["googlemap"]              = "Karte";
$pgv_lang["no_gmtab"]               = "Keine Karteninformation für diese Person";
$pgv_lang["gm_disabled"]            = "Kartendarstellung deaktiviert";

$pgv_lang["gm_redraw_map"]          = "Karte neu zeichnen";
$pgv_lang["gm_map"]                 = "Karte";
$pgv_lang["gm_satellite"]           = "Satellit";
$pgv_lang["gm_hybrid"]              = "Kombination";

// Configuration texts
$pgv_lang["gm_manage"]              = "Kartendarstellung anpassen";
$pgv_lang["configure_googlemap"]    = "Googlemap Konfiguration";
$pgv_lang["gm_admin_error"]         = "Nur für Verwalter";
$pgv_lang["gm_db_error"]            = "Tabelle 'placelocation' in Datenbank nicht vorhanden";
$pgv_lang["gm_table_created"]       = "Tabelle 'placelocation' neu angelegt";
$pgv_lang["googlemap_enable"]       = "Kartendarstellung aktivieren";
$pgv_lang["googlemapkey"]           = "Googlemap API Schlüssel";
$pgv_lang["gm_map_type"]            = "Standard Kartentyp";
$pgv_lang["gm_map_size"]            = "Größe der Karte (in Pixel)";
$pgv_lang["gm_map_size_x"]          = "Breite";
$pgv_lang["gm_map_size_y"]          = "Höhe";
$pgv_lang["gm_map_zoom"]            = "Zoomfaktor der Karte";
$pgv_lang["gm_digits"]              = "Nachkommastellen";
$pgv_lang["gm_min"]                 = "Min.";
$pgv_lang["gm_max"]                 = "Max.";
$pgv_lang["gm_default_level0"]      = "Standardwert der höchsten Stufen";
$pgv_lang["gm_nof_levels"]          = "Anzahl der Stufen";
$pgv_lang["gm_config_per_level"]    = "Konfiguration pro Stufe";
$pgv_lang["gm_name_prefix"]         = "Präfix";
$pgv_lang["gm_name_postfix"]        = "Suffix";
$pgv_lang["gm_name_pre_post"]       = "Reihenfolge von Präfix / Suffix";
$pgv_lang["gm_level"]               = "Stufe";
$pgv_lang["gm_pp_none"]             = "Kein Präfix / Suffix";
$pgv_lang["gm_pp_n_pr_po_b"]        = "Normal, Präfix, Suffix, beide";
$pgv_lang["gm_pp_n_po_pr_b"]        = "Normal, Suffix, Präfix, beide";
$pgv_lang["gm_pp_pr_po_b_n"]        = "Präfix, Suffix, beide, normal";
$pgv_lang["gm_pp_po_pr_b_n"]        = "Suffix, Präfix, beide, normal";
$pgv_lang["gm_pp_pr_po_n_b"]        = "Präfix, Suffix, normal, beide";
$pgv_lang["gm_pp_po_pr_n_b"]        = "Suffix, Präfix, normal, beide";

// Texts used on the Places location page
$pgv_lang["edit_place_locations"]   = "Geografische Ortskoordinaten bearbeiten";
$pgv_lang["pl_zoom_factor"]         = "Zoomfaktor";
$pgv_lang["pl_place_icon"]          = "Icon";
$pgv_lang["pl_edit"]                = "Geografische Ortsdaten bearbeiten";
$pgv_lang["pl_add_place"]           = "Ort hinzufügen";
$pgv_lang["pl_import_gedcom"]       = "Aus aktueller GEDCOM-Datei importieren";
$pgv_lang["pl_import_all_gedcom"]   = "Aus allen GEDCOM-Dateien importieren";
$pgv_lang["pl_import_file"]         = "Aus Datei importieren";
$pgv_lang["pl_export_file"]         = "Aktuelle Ansicht in Datei exportieren";
$pgv_lang["pl_export_all_file"]     = "Alle Orte in Datei exportieren";
$pgv_lang["pl_north_short"]         = "N";
$pgv_lang["pl_south_short"]         = "S";
$pgv_lang["pl_east_short"]          = "O";
$pgv_lang["pl_west_short"]          = "W";
$pgv_lang["pl_places_localfile"]	= "Server-Datei mit Ortsdaten (CSV)";
$pgv_lang["pl_places_filename"]     = "Datei mit Ortsdaten (CSV)";
$pgv_lang["pl_clean_db"]            = "Alle Ortsdaten vor dem Import löschen?";
$pgv_lang["pl_update_only"]         = "Nur die vorhandenen Orte aktualisieren?";
$pgv_lang["pl_overwrite_data"]      = "Überschreiben der Ortsdaten mit den Daten aus der Datei?";
$pgv_lang["pl_no_places_found"]     = "Keine Orte gefunden";
$pgv_lang["pl_use_this_value"]      = "Diesen Wert verwenden";
$pgv_lang["pl_precision"]           = "Genauigkeit";
$pgv_lang["pl_country"]             = "Staat";
$pgv_lang["pl_state"]               = "Bundesland";
$pgv_lang["pl_city"]                = "Kreis / kreisfreie Stadt";
$pgv_lang["pl_neighborhood"]        = "Stadt / Ortsteil";
$pgv_lang["pl_house"]               = "Haus";
$pgv_lang["pl_max"]                 = "Max";

$pgv_lang["pl_flag"]                = "Flagge";
$pgv_lang["flags_edit"]             = "Flagge auswählen";
$pgv_lang["pl_change_flag"]         = "Flagge ändern";
$pgv_lang["pl_remove_flag"]         = "Flagge entfernen";

$pgv_lang["pl_remove_location"]     = "Diese Ortsdaten entfernen?";
$pgv_lang["pl_delete_error"]        = "Ortsdaten wurden nicht entfernt! Es sind noch weitere, abhängige Ortsdaten vorhanden.";

?>
