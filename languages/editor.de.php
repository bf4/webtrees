<?php
/**
 * English texts
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
 *
 * @author PGV Developers
 * @package PhpGedView
 * @subpackage Languages
 * @version $Id$
 */
if (preg_match("/editor\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
	print "Direkter Sprach-Dateien Zugriff ist nicht erlaubt.";
	exit;
}

$pgv_lang["advanced_name_fields"]	= "Weitere Namen (Spitzname, Ehename, usw.)";
$pgv_lang["accept_changes"]                = "Änderungen übernehmen / verwerfen";
$pgv_lang["replace"]                        = "Datensatz ersetzen";
$pgv_lang["append"]                                = "Datensatz hinzufügen";
$pgv_lang["review_changes"]                = "GEDCOM Änderung überprüfen";
$pgv_lang["remove_object"]			= "Objekt entfernen";
$pgv_lang["remove_links"]			= "Verbindungen löschen";
$pgv_lang["media_not_deleted"]		= "Medien-Verzeichnis nicht entfernt.";
$pgv_lang["thumbs_not_deleted"]		= "Miniaturbild-Verzeichnis nicht entfernt.";
$pgv_lang["thumbs_deleted"]			= "Miniaturbild-Verzeichnis erfolgreich entfernt.";
$pgv_lang["show_thumbnail"]			= "Miniaturbilder anzeigen";
$pgv_lang["link_media"]			= "Multimedia-Objekt verbinden";
$pgv_lang["to_person"]			= "mit Person";
$pgv_lang["to_family"]			= "mit Familie";
$pgv_lang["to_source"]			= "mit Quelle";
$pgv_lang["edit_fam"]				= "Familie bearbeiten";
$pgv_lang["copy"]					= "Kopieren";
$pgv_lang["cut"]					= "Ausschneiden";
$pgv_lang["sort_by_birth"]			= "Nach Geburtsdaten sortieren";
$pgv_lang["reorder_children"]		= "Kinder neu ordnen";
$pgv_lang["add_from_clipboard"]		= "Aus der Zwischenablage hinzufügen: ";
$pgv_lang["record_copied"]			= "Datensatz in die Zwischenablage kopiert";
$pgv_lang["add_unlinked_person"]	= "Eine Person ohne Verbindung hinzufügen";
$pgv_lang["add_unlinked_source"]	= "Eine Quelle ohne Verbindung hinzufügen";
$pgv_lang["server_file"]			= "Dateiname auf dem Server";
$pgv_lang["server_file_advice"]		= "Nicht ändern, um den ursprünglichen Namen zu behalten.";
$pgv_lang["server_file_advice2"]	= "Sie können hier einen URL eingeben, der mit &laquo;http://&raquo; beginnt.";
$pgv_lang["server_folder_advice"]	= "Sie können bis zu #GLOBALS[MEDIA_DIRECTORY_LEVELS]# Verzeichnisnamen zusätzlich zum &laquo;#GLOBALS[MEDIA_DIRECTORY]#&raquo; Standardnamen eingeben.<br />Der &laquo;#GLOBALS[MEDIA_DIRECTORY]#&raquo; Teil des Verzeichnisnamens wird NICHT eingegeben.";
$pgv_lang["server_folder_advice2"]	= "Diese Eingabe wird nicht beachtet, wenn Sie oben einen URL eingegeben haben.";
$pgv_lang["add_linkid_advice"]		= "Die ID Nummer einer Person, Familie, oder Quelle eingeben oder suchen, um eine Verbindung zu diesem Medien Objekt herzustellen.";
$pgv_lang["use_browse_advice"]		= "Mit dem &laquo;Browse&raquo; Button können Sie Ihren lokalen Computer nach der gewünschten Datei durchsuchen.";
$pgv_lang["add_media_other_folder"]		= "Anderes Verzeichnis ... bitte eingeben";
$pgv_lang["add_media_file"]				= "Bereits bestehende Medien-Datei";
$pgv_lang["date_of_entry"]			= "Datum des Eintrages in der ursprünglichen Quelle";
$pgv_lang["main_media_ok1"]			= "Das Medien-Objekt <b>#GLOBALS[oldMediaName]#</b> wurde auf <b>#GLOBALS[newMediaName]#</b> umbenannt.";
$pgv_lang["main_media_ok2"]			= "Das Medien-Objekt <b>#GLOBALS[oldMediaName]#</b> wurde von <b>#GLOBALS[oldMediaFolder]#</b> nach <b>#GLOBALS[newMediaFolder]#</b> verlegt.";
$pgv_lang["main_media_ok3"]			= "Das Medien-Objekt wurde von <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> nach <b>#GLOBALS[newMediaFolder]##GLOBALS[newMediaName]#</b> verlegt und umbenannt.";
$pgv_lang["main_media_fail0"]		= "Das Medien-Objekt <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> existiert nicht.";
$pgv_lang["main_media_fail1"]		= "Das Medien-Objekt <b>#GLOBALS[oldMediaName]#</b> konnte nicht auf <b>#GLOBALS[newMediaName]#</b> umbenannt werden.";
$pgv_lang["main_media_fail2"]		= "Das Medien-Objekt <b>#GLOBALS[oldMediaName]#</b> konnte nicht von <b>#GLOBALS[oldMediaFolder]#</b> nach <b>#GLOBALS[newMediaFolder]#</b> verlegt werden.";
$pgv_lang["main_media_fail3"]		= "Das Medien-Objekt konnte nicht von <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> nach <b>#GLOBALS[newMediaFolder]##GLOBALS[newMediaName]#</b> verlegt und umbenannt werden.";
$pgv_lang["thumb_media_ok1"]		= "Das Miniaturbild <b>#GLOBALS[oldMediaName]#</b> wurde auf <b>#GLOBALS[newMediaName]#</b> umbenannt.";
$pgv_lang["thumb_media_ok2"]		= "Das Miniaturbild <b>#GLOBALS[oldMediaName]#</b> wurde von <b>#GLOBALS[oldThumbFolder]#</b> nach <b>#GLOBALS[newThumbFolder]#</b> verlegt.";
$pgv_lang["thumb_media_ok3"]		= "Das Miniaturbild wurde von <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> nach <b>#GLOBALS[newThumbFolder]##GLOBALS[newMediaName]#</b> verlegt und umbenannt.";
$pgv_lang["thumb_media_fail0"]		= "Das Miniaturbild <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> existiert nicht.";
$pgv_lang["thumb_media_fail1"]		= "Das Miniaturbild <b>#GLOBALS[oldMediaName]#</b> konnte nicht auf <b>#GLOBALS[newMediaName]#</b> umbenannt werden.";
$pgv_lang["thumb_media_fail2"]		= "Das Miniaturbild <b>#GLOBALS[oldMediaName]#</b> konnte nicht von <b>#GLOBALS[oldThumbFolder]#</b> nach <b>#GLOBALS[newThumbFolder]#</b> verlegt werden.";
$pgv_lang["thumb_media_fail3"]		= "Das Miniaturbild konnte nicht von <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> nach <b>#GLOBALS[newThumbFolder]##GLOBALS[newMediaName]#</b> verlegt und umbenannt werden.";
$pgv_lang["add_asso"]				= "Neue Beziehung hinzufügen";
$pgv_lang["edit_sex"]				= "Geschlecht ändern";
$pgv_lang["add_obje"]                        = "Neues Multimedia Objekt hinzufügen";
$pgv_lang["add_name"]			= "Neuen Namen hinzufügen";
$pgv_lang["edit_raw"]                        = "GEDCOM Rohdaten bearbeiten";
$pgv_lang["label_add_remote_link"]  = "Ferne Verbindung hinzufügen";
$pgv_lang["label_gedcom_id"]        = "GEDCOM ID";
$pgv_lang["label_local_id"]         = "Personen ID";
$pgv_lang["accept"]                                = "Übernehmen";
$pgv_lang["accept_all"]                        = "Alle Änderungen übernehmen";
$pgv_lang["accept_gedcom"]              = "Entscheiden Sie für jede Änderung, ob Sie sie übernehmen oder verwerfen möchten.<br /><br />Um alle Änderungen auf einmal zu übernehmen, klicken Sie <b>«Alle Änderungen übernehmen»</b> in der Box unten.<br />Weitere Informationen über eine Änderung erhalten Sie über den Link <b>«Änderungen ansehen»</b> oder klicken Sie <b>«GEDCOM Datensatz ansehen»</b>, um die veränderte GEDCOM Datei anzusehen.";
$pgv_lang["accept_successful"]        = "Änderungen erfolgreich in die Datenbank übernommen";
$pgv_lang["add_child"]                  = "Kind hinzufügen";
$pgv_lang["add_child_to_family"]        = "Kind zu dieser Familie hinzufügen";
$pgv_lang["add_fact"]                   = "neues Ereignis hinzufügen";
$pgv_lang["add_father"]                        = "Einen Vater hinzufügen";
$pgv_lang["add_husb"]                   = "Ehemann hinzufügen";
$pgv_lang["add_husb_to_family"]                = "Einen Ehemann zu dieser Familie hinzufügen";
$pgv_lang["add_media"]                        = "Neue Multimedia Datei hinzufügen";
$pgv_lang["add_media_lbl"]                = "Multimedia Datei hinzufügen";
$pgv_lang["add_mother"]                        = "Eine Mutter hinzufügen";
$pgv_lang["add_new_chil"] = "Ein Kind hinzufügen";
$pgv_lang["add_new_husb"]               = "Einen weiteren Ehemann hinzufügen";
$pgv_lang["add_new_wife"]               = "Eine weitere Ehefrau hinzufügen";
$pgv_lang["add_note"]                   = "Neue Bemerkung hinzufügen";
$pgv_lang["add_note_lbl"]                = "Bemerkung hinzufügen";
$pgv_lang["add_sibling"]                = "Einen Bruder oder eine Schwester hinzufügen";
$pgv_lang["add_son_daughter"]                = "Einen Sohn oder eine Tochter hinzufügen";
$pgv_lang["add_source"]                 = "Neue Quelle hinzufügen";
$pgv_lang["add_source_lbl"]                = "Quelle hinzufügen";
$pgv_lang["add_wife"]                   = "Ehefrau hinzufügen";
$pgv_lang["add_wife_to_family"]                = "Eine Ehefrau zu dieser Familie hinzufügen";
$pgv_lang["advanced_search_discription"] = "Erweiterte Internetseiten-Suche";
$pgv_lang["auto_thumbnail"]				= "Automatisches Miniaturbild";
$pgv_lang["basic_search"]			= "Suche";
$pgv_lang["basic_search_discription"] = "Einfache Internetseiten-Suche";
$pgv_lang["birthdate_search"]		= "Geburtsdatum:";
$pgv_lang["birthplace_search"]		= "Geburtsort:";
$pgv_lang["change"]					= "Ändern";
$pgv_lang["change_family_instr"]	= "Benutze diese Seite zum Ändern oder Entfernen von Familienmitgliedern.<br /><br />Für jedes Familienmitglied können Sie den <b>Ändern</b> Button klicken, um eine andere Person für diese Rolle in der Familie zu wählen.<br /><br />Um Ihre Änderungen zu speichern, klicken Sie den <b>Speichern</b> Button.<br />";
$pgv_lang["change_family_members"]	= "Familienmitglieder ändern";
$pgv_lang["changes_occurred"]                = "An diesem Datensatz wurden folgende Änderungen vorgenommen:";
$pgv_lang["confirm_remove"]			= "Möchten Sie wirklich diese Person aus dieser Familie entfernen?";
$pgv_lang["confirm_remove_object"]	= "Möchten Sie wirklich dieses Objekt entfernen?";
$pgv_lang["create_repository"]		= "Archiv erstellen";
$pgv_lang["create_source"]                = "Neue Quelle erstellen";
$pgv_lang["current_person"]         = "genau wie aktuelle Person";
$pgv_lang["date"]                        = "Datum";
$pgv_lang["deathdate_search"]		= "Sterbedatum:";
$pgv_lang["deathplace_search"]		= "Sterbeort:";
$pgv_lang["delete_dir_success"]		= "Medien- und Miniaturbild-Verzeichnisse erfolgreich entfernt.";
$pgv_lang["delete_file"]			= "Datei löschen";
$pgv_lang["delete_repo"]			= "Archiv löschen";
$pgv_lang["directory_not_empty"]	= "Verzeichnis ist nicht leer.";
$pgv_lang["directory_not_exist"]	= "Verzeichnis existiert nicht.";
$pgv_lang["error_remote"]           = "Sie haben eine ferne Seite gewählt.";
$pgv_lang["error_same"]             = "Sie haben die gleiche Seite gewählt.";
$pgv_lang["external_file"]			= "Diese Medien-Datei existiert nicht auf diesem Server.  Sie kann nicht gelöscht, verlegt, oder umbenannt werden.";
$pgv_lang["family"]                        = "Familie";
$pgv_lang["file_missing"]               = "Es kam keine Datei auf Ihrem Server an. Bitte erneut hochladen.";
$pgv_lang["file_partial"]               = "Die Datei wurde nur teilweise hochgeladen. Bitte versuchen Sie es erneut.";
$pgv_lang["file_success"]               = "Datei wurde erfolgreich auf Ihren Server hochgeladen";
$pgv_lang["file_too_big"]               = "Die zu hochladende Datei ist größer als erlaubt";
$pgv_lang["folder"]		 			= "Server Verzeichnis";
$pgv_lang["gedcom_editing_disabled"]	= "Änderung dieser GEDCOM Datei wurde vom Verwalter gesperrt.";
$pgv_lang["gedcomid"]                   = "GEDCOM ID Nummer dieser Person";
$pgv_lang["gedrec_deleted"]                = "GEDCOM Datensatz erfolgreich gelöscht.";
$pgv_lang["gen_thumb"]				= "Miniaturbild erzeugen";
$pgv_lang["gender_search"]			= "Geschlecht:";
$pgv_lang["generate_thumbnail"]		= "Miniaturbild automatisch erstellen aus ";
$pgv_lang["hebrew_givn"]			= "Hebräische Vornamen";
$pgv_lang["hebrew_surn"]			= "Hebräischer Nachname";
$pgv_lang["hide_changes"]                = "Hier klicken, um die Änderungen zu verbergen.";
$pgv_lang["highlighted"]                = "Hervorgehobenes Bild";
$pgv_lang["illegal_chars"]			= "Leerer Name oder unzulässige Zeichen im Namen";
$pgv_lang["invalid_search_input"] 	= "Bitte geben Sie einen Namen oder einen Ort zusätzlich zur Jahresangabe an";
$pgv_lang["invalid_search_multisite_input"] = "Bitte geben Sie eins der folgenden ein: Name, Geburtsdatum, Geburtsort, Sterbedatum, Sterbeort, oder Geschlecht";
$pgv_lang["invalid_search_multisite_input_gender"] = "Bitte nochmals suchen, aber mit mehr Informationen als nur das Geschlecht";
$pgv_lang["label_diff_server"]      = "Andere Internetseite";
$pgv_lang["label_location"]         = "Lage";
$pgv_lang["label_password_id2"]		= "Passwort:";
$pgv_lang["label_rel_to_current"]   = "Beziehung zur aktuellen Person";
$pgv_lang["label_remote_id"]        = "ID dieser Person auf der fernen Seite";
$pgv_lang["label_same_server"]      = "Gleiche Internetseite";
$pgv_lang["label_site"]             = "Internetseite";
$pgv_lang["label_site_url"]         = "Internetseiten-Adresse:";
$pgv_lang["label_username_id2"]		= "Benutzername:";
$pgv_lang["lbl_server_list"]        = "Existierende Seite benutzen.";
$pgv_lang["lbl_type_server"]        = "Geben Sie eine neue Seite ein.";
$pgv_lang["link_as_child"]			= "Diese Person als Kind mit einer existierenden Familie verbinden";
$pgv_lang["link_as_husband"]		= "Diese Person als Ehemann mit einer existierenden Familie verbinden";
$pgv_lang["link_success"]			= "Link erfolgreich hinzugefügt";
$pgv_lang["link_to_existing_media"]		= "Mit einem bereits bestehenden Medien-Objekt verbinden";
$pgv_lang["max_media_depth"]		= "Sie können nur zur Verzeichnistiefe #MEDIA_DIRECTORY_LEVELS# gehen";
$pgv_lang["max_upload_size"]		= "Maximale Größe zum Hochladen: ";
$pgv_lang["media_deleted"]			= "Medien Verzeichnis erfolgreich entfernt.";
$pgv_lang["media_exists"]			= "Medien Datei existiert bereits.";
$pgv_lang["media_file"]                 = "Multimedia Datei zum Hochladen";
$pgv_lang["media_file_deleted"]		= "Medien Datei erfolgreich gelöscht.";
$pgv_lang["media_file_not_moved"]	= "Medien Datei konnte nicht verlegt werden.";
$pgv_lang["media_file_not_renamed"]	= "Medien Datei konnte nicht verlegt oder umbenannt werden.";
$pgv_lang["media_thumb_exists"]		= "Miniaturbild existiert bereits.";
$pgv_lang["multi_site_search"] 		= "Mehrere Internetseiten suchen";
$pgv_lang["multiple_gedcoms"]		= "Diese Medien-Datei ist mit einer anderen genealogischen Datei auf diesem Server verbunden.  Sie kann nicht gelöscht, verlegt, oder umbenannt werden, bis diese Verbindungen gelöscht sind.";
$pgv_lang["must_provide"]               = "Bitte eingeben:";
$pgv_lang["name_search"]			= "Name:";
$pgv_lang["new_repo_created"]		= "neues Archiv erstellt";
$pgv_lang["new_source_created"]        = "Neue Quelle erfolgreich erstellt.";
$pgv_lang["no_changes"]                        = "Es liegen derzeit keine Änderungen zur Überprüfung vor.";
$pgv_lang["no_known_servers"]		= "Keine bekannten Server<br />Nichts wird gefunden";
$pgv_lang["no_temple"]                        = "Kein Tempel - Ordinanz zu Lebzeiten";
$pgv_lang["no_upload"]				= "Multimedia Dateien können nicht hochgeladen werden, weil Multimedia Objekte deaktiviert sind oder weil für das Medien Verzeichnis keine Schreibrechte bestehen.";
$pgv_lang["paste_id_into_field"]= "Fügen Sie diese ID Nummer in das jeweilige Eingabefeld ein, um auf diesen neu erstandenen Datensatz zu verweisen: ";
$pgv_lang["paste_rid_into_field"]	= "Fügen Sie die folgende ID ein, um auf dieses Archiv zu verweisen ";
$pgv_lang["photo_replace"] = "Möchten Sie ein älteres Foto durch dieses ersetzen?";
$pgv_lang["privacy_not_granted"]	= "Sie haben keinen Zugriff auf";
$pgv_lang["privacy_prevented_editing"]	= "Die Datenschutz Einstellungen verhindern, dass Sie diesen Datensatz bearbeiten können.";
$pgv_lang["record_marked_deleted"]		= "Dieser Datensatz wird gelöscht, nachdem der Verwalter es genehmigt hat.";
$pgv_lang["replace_with"]			= "Ersetzen mit ...";
$pgv_lang["show_changes"]                = "Dieser Eintrag wurde geändert. Hier klicken, um die Änderungen zu sehen.";
$pgv_lang["thumb_genned"]			= "Miniaturbild #thumbnail# wurde automatisch erstellt.";
$pgv_lang["thumbgen_error"]			= "Miniaturbild #thumbnail# konnte nicht automatisch erstellt werden.";
$pgv_lang["thumbnail"]                  = "Miniaturbild zum Hochladen";
$pgv_lang["title_remote_link"]      = "Ferne Verbindung hinzufügen";
$pgv_lang["undo"]                        = "Änderungen verwerfen";
$pgv_lang["undo_all"]				= "Alle Änderungen verwerfen";
$pgv_lang["undo_all_confirm"]		= "Möchten Sie wirklich alle Änderungen an dieser GEDCOM Datei verwerfen?";
$pgv_lang["undo_successful"]                = "Verwerfen war erfolgreich";
$pgv_lang["update_successful"]                = "Aktualisierung erfolgreich";
$pgv_lang["upload"]					= "Hochladen";
$pgv_lang["upload_error"]               = "Beim übertragen (upload) Ihrer Datei auf Ihren Server trat ein Fehler auf.";
$pgv_lang["upload_media"]                = "Multimedia Dateien auf Server hochladen";
$pgv_lang["upload_media_help"]		= "~#pgv_lang[upload_media]#~<br /><br />Wählen Sie die Dateien zum Hochladen auf Ihren Server. Alle Dateien werden in das <b>#MEDIA_DIRECTORY#</b> Verzeichnis aktualisiert  oder in eines seiner Unterverzeichnisse.<br /><br />Verzeichnisnamen die Sie eingeben, werden an #MEDIA_DIRECTORY# angehängt. Zum Beispiel, #MEDIA_DIRECTORY#Müller. Falls das Miniaturbild-Verzeichnis nicht existiert, wird es automatisch erstellt.";
$pgv_lang["upload_successful"]                = "Hochladen erfolgreich.";
$pgv_lang["view_change_diff"]                = "Änderungen ansehen";


?>