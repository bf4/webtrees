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

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

$pgv_lang["accept_changes"]			= "Akceptuj / Odrzuć zmiany";
$pgv_lang["replace"]			= "Zamień rekord";
$pgv_lang["append"]				= "Dołącz rekord";
$pgv_lang["review_changes"]			= "Przeglądnij zmiany w GEDCOM";
$pgv_lang["remove_object"]			= "Usuń obiekt";
$pgv_lang["media_not_deleted"]		= "Katalog mediów nie został usunięty.";
$pgv_lang["thumbs_not_deleted"]		= "Katalog miniatur nie został usunięty.";
$pgv_lang["thumbs_deleted"]			= "Katalog miniatur został usunięty.";
$pgv_lang["show_thumbnail"]			= "Pokaż miniaturki";
$pgv_lang["edit_fam"]				= "Edytuj rodzinę";
$pgv_lang["copy"]					= "Kopiuj";
$pgv_lang["cut"]					= "Wytnij";
$pgv_lang["sort_by_birth"]			= "Sortuj według dat urodzin";
$pgv_lang["reorder_children"]		= "Uporządkuj dzieci";
$pgv_lang["add_unlinked_person"]	= "Dodaj niepowiązaną osobę";
$pgv_lang["add_unlinked_source"]	= "Dodaj niepowiązane źródło";
$pgv_lang["server_file"]				= "Nazwa pliku na serwerze";
$pgv_lang["server_file_advice"]			= "Nie zmieniaj aby zachować oryginalną nazwę pliku.";
$pgv_lang["add_media_other_folder"]		= "Inny katalog...proszę wprowadzić";
$pgv_lang["add_media_file"]				= "Istniejący plik mediów na serwerze";
$pgv_lang["date_of_entry"]				= "Data wpisu w oryginalnym źródle";
$pgv_lang["add_asso"]				= "Dodaj nowe powiązanie";
$pgv_lang["edit_sex"]				= "Edytuj płeć";
$pgv_lang["add_obje"]			= "Dodaj nowy obiekt multimedialny";
$pgv_lang["add_name"]				= "Dodaj nowe imię";
$pgv_lang["edit_raw"]			= "Edytuj plik GEDCOM";
$pgv_lang["label_add_remote_link"]  = "Dodaj łącze";
$pgv_lang["label_local_id"]         = "ID osoby";
$pgv_lang["accept"]				= "Zaakceptuj";
$pgv_lang["accept_all"]			= "Zaakceptuj wszystkie zmiany";
$pgv_lang["accept_gedcom"]			= "Każdą zmnianę możesz zaakceptować lub odrzucić.<br />Aby zaakceptować wszystko na raz kliknij \"Zaakceptuj wszystkie zmiany\" poniżej.<br />Aby zobaczyć więcej informacji o zmianie, <br />kliknij \"Pokaż porównianie zmian\" co wyświetli różnice między starą i nową sytuacją, <br />lub kliknij \"Zobacz rekord GEDCOM\" aby zobaczyć nowe informacje w formacie GEDCOM.";
$pgv_lang["accept_successful"]	= "Zmiany zostały wprowadzone do bazy danych";
$pgv_lang["add_child"]				= "Dodaj dziecko";
$pgv_lang["add_child_to_family"]		= "Dodaj dziecko do tej rodziny";
$pgv_lang["add_fact"]				= "Dodaj nowy fakt";
$pgv_lang["add_father"]			= "Dodaj nowego ojca";
$pgv_lang["add_husb"]			= "Dodaj męża";
$pgv_lang["add_husb_to_family"]	= "Dodaj męża do tej rodziny";
$pgv_lang["add_media"]			= "Dodaj nowe multimedia";
$pgv_lang["add_media_lbl"]		= "Dodaj multimedia";
$pgv_lang["add_mother"]			= "Dodaj nową matkę";
$pgv_lang["add_new_chil"] 			= "Dodaj nowe dziecko";
$pgv_lang["add_new_husb"]		= "Dodaj nowego męża";
$pgv_lang["add_new_wife"]		= "Dodaj nową żonę";
$pgv_lang["add_note"]				= "Dodaj nową notatkę";
$pgv_lang["add_note_lbl"]		= "Dodaj notatkę";
$pgv_lang["add_sibling"]		= "Dodaj brata lub siostrę";
$pgv_lang["add_son_daughter"]	= "Dodaj syna lub córkę";
$pgv_lang["add_source"]				= "Dodaj źródło do faktu";
$pgv_lang["add_source_lbl"]		= "Dodaj cytat źródła";
$pgv_lang["add_wife"]			= "Dodaj żonę";
$pgv_lang["add_wife_to_family"]	= "Dodaj żonę do tej rodziny";
$pgv_lang["birthdate_search"]		= "Data urodzin: ";
$pgv_lang["birthplace_search"]		= "Miejsce urodzin: ";
$pgv_lang["change"]					= "Zmień";
$pgv_lang["change_family_members"]	= "Zmień członków rodziny";
$pgv_lang["changes_occurred"]			= "Następujące zmiany dokonano w informacjach o tej osobie:";
$pgv_lang["confirm_remove"]			= "Czy na pewno chcesz usunąć tą osobę z rodziny?";
$pgv_lang["confirm_remove_object"]	= "Czy na pewno chcesz usunąć ten obiekt z bazy danych?";
$pgv_lang["create_repository"]		= "Utwórz repozytorium";
$pgv_lang["create_source"]		= "Utwórz nowe źródło";
$pgv_lang["current_person"]         = "Aktualna osoba";
$pgv_lang["date"]				= "Data";
$pgv_lang["deathdate_search"]		= "Data śmierci: ";
$pgv_lang["deathplace_search"]		= "Miejsce śmierci: ";
$pgv_lang["delete_file"]			= "Usuń plik";
$pgv_lang["delete_repo"]			= "Usuń repozytorium";
$pgv_lang["directory_not_empty"]	= "Katalog nie jest pusty.";
$pgv_lang["directory_not_exist"]	= "Katalog nie istnieje.";
$pgv_lang["family"]				= "Rodzina";
$pgv_lang["file_missing"]			= "Plik nie został wysłany, spróbuj ponownie.";
$pgv_lang["file_partial"]			= "Plik wysłany jedynie częściowo, spróbuj ponownie";
$pgv_lang["file_success"]			= "Plik został wysłany";
$pgv_lang["file_too_big"]			= "Plik przekracza dozwolony rozmiar";
$pgv_lang["gedcom_editing_disabled"]	= "Edycja tego GEDCOM'u została zablokowana";
$pgv_lang["gedcomid"]				= "Identyfikator osoby w GEDCOMie";
$pgv_lang["gedrec_deleted"]			= "Informaja z GEDCOMu została usunięta.";
$pgv_lang["gen_thumb"]				= "Wygeneruj miniaturkę";
$pgv_lang["gender_search"]			= "Płeć: ";
$pgv_lang["generate_thumbnail"]		= "Wygeneruj miniaturkę automatycznie z ";
$pgv_lang["hide_changes"]		= "Kliknij aby ukryć zmiany.";
$pgv_lang["highlighted"]		= "Zaznaczony obraz";
$pgv_lang["illegal_chars"]			= "Niedozwolone znaki w nazwisku";
$pgv_lang["invalid_search_input"] 	= "Wprowadź imię, nazwisko lub miejsce \\n\\t w danym roku";
$pgv_lang["invalid_search_multisite_input"] = "Podaj jedno z następujących: imię/nazwisko, data urodzenia, miejsce urodzenia, data śmierci, miejsce śmierci, płeć ";
$pgv_lang["invalid_search_multisite_input_gender"] = "Szukaj ponownie z większą ilością informacji niż tylko płeć";
$pgv_lang["label_password_id2"]		= "Hasło: ";
$pgv_lang["label_rel_to_current"]   = "Relacja do aktualnej osoby";
$pgv_lang["label_remote_id"]        = "ID zdalnej osoby";
$pgv_lang["label_username_id2"]		= "Nazwa użytkownika: ";
$pgv_lang["link_as_child"]			= "Dodaj jako dziecko do istniejącej rodziny";
$pgv_lang["link_as_husband"]		= "Dodaj jako męża do istniejącej rodziny";
$pgv_lang["link_to_existing_media"]		= "Dodaj istniejący obiekt mediów";
$pgv_lang["media_deleted"]			= "Katalog mediów usunięty.";
$pgv_lang["media_exists"]			= "Plik mediów już istnieje.";
$pgv_lang["media_file"]				= "Plik multimedialny";
$pgv_lang["media_thumb_exists"]		= "Miniatura mediów już istnieje.";
$pgv_lang["must_provide"]			= "Musisz podać ";
$pgv_lang["new_repo_created"]		= "Utworzono nowe repozytorium";
$pgv_lang["new_source_created"]	= "Nowe źródło zostało utworzone.";
$pgv_lang["no_changes"]			= "Nie ma obecnie zmian oczekujących akceptacji.";
$pgv_lang["no_temple"]			= "Brak świątyni - Living Ordinance";
$pgv_lang["no_upload"]				= "Ładowanie plików multimedialnych jest niedozwolone ponieważ opcja ta została wyłączona albo katalog z plikami multimedialnymi jest ustawiony na \"Tylko do odczytu\".";
$pgv_lang["paste_id_into_field"]= "Wklej poniższy identyfikator źródła w pole edycji aby powołać się na to źródło ";
$pgv_lang["paste_rid_into_field"]	= "Wstaw następujące ID do pola edycji by odnieść się do tego repozytorium ";
$pgv_lang["photo_replace"] = "Czy chcesz zastąpic stare zdjęcie tym nowym?";
$pgv_lang["privacy_not_granted"]	= "Nie masz dostępu do ";
$pgv_lang["privacy_prevented_editing"]	= "Ustawienie prywatności uniemożliwia ci edycję";
$pgv_lang["record_marked_deleted"]		= "Ten rekord został zaznaczony do usunięcia po zatwierdzeniu przez administratora.";
$pgv_lang["replace_with"]			= "Zamień na";
$pgv_lang["show_changes"]			= "Te dane zostały zaktualizowane. Kliknij tutaj aby zobaczyć zmiany.";
$pgv_lang["thumb_genned"]			= "Mianiturka automatycznie wygenerowana.";
$pgv_lang["thumbgen_error"]			= "Nie można wygenerować miniaturki dla";
$pgv_lang["thumbnail"]				= "Miniaturka";
$pgv_lang["title_remote_link"]      = "Dodaj zewnętrzne łącze";
$pgv_lang["undo"]				= "Cofnij";
$pgv_lang["undo_all"]				= "Cofnij wszystkie zmiany";
$pgv_lang["undo_successful"]			= "Cofnięcie zakończone sukcesem";
$pgv_lang["update_successful"]			= "Aktualizacja zakończona sukcesem";
$pgv_lang["upload_error"]			= "Wystąpił błąd podczas przesyłania pliku.";
$pgv_lang["upload_media"]			= "Wyślij pliki multimedialne";
$pgv_lang["upload_successful"]			= "Wysyłanie zakończone sukcesem";
$pgv_lang["view_change_diff"]			= "Pokaż porównianie zmian";


?>
