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

$pgv_lang["user"]					= "Użytkownik zalogowany";
$pgv_lang["step2"]				= "Krok 2 z 4:";
$pgv_lang["refresh"]				= "Odśwież";
$pgv_lang["move_file_success"]		= "Pliki mediów i miniatur pomyślnie przeniesione.";
$pgv_lang["gedcom_deleted"]		= "GEDCOM [#GED#] został usunięty.";
$pgv_lang["full_name"]				= "Imię i nazwisko";
$pgv_lang["error_header"] 			= "Plik GEDCOM [#GEDCOM#], nie istnieje pod podaną lokalizacją.";
$pgv_lang["confirm_delete_file"]	= "Czy na pewno chcesz usunąć ten plik?";
$pgv_lang["confirm_folder_delete"] = "Czy na pewno chcesz usunąć ten folder?";
$pgv_lang["manage_gedcoms"]			= "Zarządzaj plikami GEDCOM";
$pgv_lang["created_remotelinks"]	= "Tabela <i>Remotelinks</i> utworzona pomyślnie.";
$pgv_lang["created_remotelinks_fail"] 	= "Nie można utworzyć tabeli <i>Remotelinks</i>.";
$pgv_lang["created_indis"]		= "Utworzono tabelę <i>Osób</i>.";
$pgv_lang["created_indis_fail"]	= "Błąd utworzenia tabeli <i>Osób/i>.";
$pgv_lang["created_fams"]		= "Utworzono tabelę <i>Rodzin</i>.";
$pgv_lang["created_fams_fail"]	= "Błąd utworzenia tabeli <i>Rodzin</i>.";
$pgv_lang["created_sources"]	= "Utworzono tabelę <i>Źródeł</i>.";
$pgv_lang["created_sources_fail"]	= "Błąd utworzenia tabeli <i>Źródeł</i>.";
$pgv_lang["created_other"]		= "Utworzono tabelę <i>Innych</i>.";
$pgv_lang["created_other_fail"]	= "Błąd utworzenia tabeli <i>Innych</i>.";
$pgv_lang["created_places"]		= "Utworzono tabelę <i>Miejsc</i>.";
$pgv_lang["created_places_fail"]	= "Błąd utworzenia tabeli <i>Miejsc</i>.";
$pgv_lang["no_thumb_dir"]			= "Katalog miniaturek nie istnieje i nie można go utworzyć";
$pgv_lang["move_to"]				= "Przenieś do -->";
$pgv_lang["folder_created"]		= "Utworzono katalog";
$pgv_lang["folder_no_create"]		= "Katalog nie mógł zostać utworzony";
$pgv_lang["security_no_create"]		= "Ostrzeżenie: Plik <b><i>index.php</i></b> nie istnieje w";
$pgv_lang["label_add_search_server"]	= "Dodaj IP";
$pgv_lang["label_add_server"]       = "Dodaj";
$pgv_lang["label_ban_server"]		= "Wyślij";
$pgv_lang["label_delete"]           = "Usuń";
$pgv_lang["add_gedcom"]				= "Dodaj kolejny GEDCOM";
$pgv_lang["add_new_gedcom"]		= "Dodaj nowy GEDCOM";
$pgv_lang["admin_approved"]		= "Twoje konto na #SERVER_NAME# zostało zatwierdzone";
$pgv_lang["admin_gedcom"]			= "Administrator GEDCOM";
$pgv_lang["admin_info"]				= "Informacyjne";
$pgv_lang["admin_site"]				= "Strona administracyjna";
$pgv_lang["administration"]		= "Administracja";
$pgv_lang["ansi_encoding_detected"]	= "Wykryto kodowanie znaków w pliku w standardzie ANSI. PhpGedView działa najlepiej z plikami zakodowanymi w UTF-8.";
$pgv_lang["ansi_to_utf8"]		= "Skonwertuj zakodowany w ANSI plik do UTF-8?";
$pgv_lang["bytes_read"]			= "Odczytano bajtów:";
$pgv_lang["calc_marr_names"]		= "Generowanie nazwisk małżeńskich";
$pgv_lang["change_id"]			= "Zmień ID osoby na:";
$pgv_lang["cleanup_places"]		= "Miejsca do oczyszczenia";
$pgv_lang["click_here_to_go_to_pedigree_tree"] 	= "Kliknij tu by iść do drzewa genealogicznego.";
$pgv_lang["configuration"]			= "Konfiguracja";
$pgv_lang["confirm_user_delete"]	= "Czy na pewno chcesz usunąć tego użytkownika";
$pgv_lang["create_user"]		= "Utwórz użytkownika";
$pgv_lang["dataset_exists"]			= "Plik GEDCOM o takiej nazwie został juz raz zaimportowany do bazy danych.";
$pgv_lang["day_before_month"]		= "Dzień przed miesiącem (DD MM RRRR)";
$pgv_lang["do_not_change"]		= "Bez zmian";
$pgv_lang["download_gedcom"]			= "Ściagnij GEDCOM";
$pgv_lang["download_note"]		= "UWAGA: Duże GEDCOMy potrzebują sporo czasu na przetworzenie przed ściągnięciem. Jeśli czas wykonywania skryptu PHP okaże się krótszy, możesz nie ściągnąć całości. Sprawdź czy ściągnięty plik zawiera linię 0 TRLR na końcu, co oznacza kompletność. Generalnie ściągnięcie może zająć tyle samo czasu co importowanie.";
$pgv_lang["duplicate_username"]			= "Zduplikowana nazwa użytkownika. Taka nazwa już istnieje, wróć i wybierz inną.";
$pgv_lang["editaccount"]			= "Zezwól użytkownikowi na edycję konta";
$pgv_lang["empty_dataset"]			= "Czy chcesz zaktualizować bazę danych?";
$pgv_lang["empty_lines_detected"]	= "Wykryto puste linie w pliku GEDCOM. Podczas oczyszczania zostaną one usunięte.";
$pgv_lang["error_ban_server"]       = "Nieprawidłowy adres IP.";
$pgv_lang["error_header_write"]	= "Plik GEDCOM [#GEDCOM#] jest zabezpieczony przed zapisem. Sprawdź ustawienie atrybutów.";
$pgv_lang["example_date"]		= "Przykład nieprawidłowej daty w twoim GEDCOM-ie:";
$pgv_lang["example_place"]			= "Przykład nieprawidłowego miejsca w twoim GEDCOM-ie:";
$pgv_lang["found_record"]			= "Znaleziono dane";
$pgv_lang["ged_import"]				= "Importuj GEDCOM";
$pgv_lang["gedcom_config_write_error"]	= "Błąd ! Nie można zapisać pliku konfiguracyjnego GEDCOM'u";
$pgv_lang["gedcom_downloadable"] 	= "Ten plik GEDCOM może zostać ściągnięty przez internet!<br />Zobacz sekcję BEZPIECZEŃSTWO(SECURITY) w pliku <a href=\"readme.txt\"><b>readme.txt</b></a> aby naprawić ten problem";
$pgv_lang["gedcom_file"]			= "Plik GEDCOM:";
$pgv_lang["img_admin_settings"]	= "Zmień konfigurację manipulacji obrazami";
$pgv_lang["import_complete"]			= "Importowanie ukończone";
$pgv_lang["import_marr_names"]		= "Importuj nazwiska małżeńskie";
$pgv_lang["import_options"]		= "Opcje importu";
$pgv_lang["import_progress"]	= "Importowanie w toku...";
$pgv_lang["import_statistics"]	= "Statystyki importu";
$pgv_lang["inc_languages"]		= " Języki";
$pgv_lang["invalid_dates"]		= "Wykryto nieprawidłowy format daty. Podczas oczyszczania zostanie poprawiony do formatu DD MMM RRRR (np. 1 JAN 2004).";
$pgv_lang["invalid_header"]		= "Wykryto linie przed nagłówkiem GEDCOM-u (0 HEAD). Podczas oczyszczania zostaną one usunięte.";
$pgv_lang["label_families"]         = "Rodziny";
$pgv_lang["label_password_id"]		= "Hasło";
$pgv_lang["label_username_id"]		= "Nazwa użytkownika";
$pgv_lang["lasttab"]				= "Ostatnio odwiedzana zakładka dla osoby";
$pgv_lang["logfile_content"]	= "Zawartość logu";
$pgv_lang["macfile_detected"]	= "Wykryto plik w formacie Macintosha. Podczas oczyszczania zostanie skonwertowany do formatu DOS.";
$pgv_lang["merge_records"]			= "Łączenie rekordów";
$pgv_lang["month_before_day"]		= "Miesiąc przed dniem (MM DD RRRR)";
$pgv_lang["none"]					= "Żadne";
$pgv_lang["performing_validation"]	= "Kontrola poprawności GEDCOMu, wybierz konieczne opcje i kliknij 'Oczyszczanie'";
$pgv_lang["pgv_registry"]		= "Zobacz inne strony korzystające z PHPGedView";
$pgv_lang["phpinfo"]				= "PHPInfo";
$pgv_lang["place_cleanup_detected"]	= "Wykryto miejsca z nieprawidłowym kodowaniem. Te błędy trzeba skorygować. Poniżej pokazany jest przykład wykrytego błędu: ";
$pgv_lang["please_be_patient"]			= "PROSIMY O CIERPLIWOŚĆ";
$pgv_lang["reading_file"]			= "Odczytuję plik GEDCOM";
$pgv_lang["readme_documentation"]		= "Dokumentacja README";
$pgv_lang["remove_ip"] 			= "Usuń IP";
$pgv_lang["rootid"]				= "Korzeń wykresu rodowego";
$pgv_lang["select_an_option"]			= "Wybierz opcje poniżej:";
$pgv_lang["siteadmin"]				= "Administrator strony";
$pgv_lang["skip_cleanup"]			= "Pomiń oczyszczanie";
$pgv_lang["time_limit"]				= "Limit czasu:";
$pgv_lang["update_myaccount"]	= "Uaktualnij moje konto";
$pgv_lang["update_user"]			= "Uaktualnij użytkownika";
$pgv_lang["upload_gedcom"]			= "Wyślij GEDCOM";
$pgv_lang["user_auto_accept"]		= "Automatycznie akceptuj zmiany dokonane przez tego użytkownika";
$pgv_lang["user_contact_method"]	= "Preferowana metoda kontaktu";
$pgv_lang["user_create_error"]			= "Nie można dodać użytkownika.  Proszę się cofnąć i spróbować ponownie.";
$pgv_lang["user_created"]			= "Użytkownik stworzony pomyślnie.";
$pgv_lang["user_default_tab"]		= "Domyślna zakładka do pokazania na stronie informacyjnej osoby";
$pgv_lang["valid_gedcom"]		= "Wykryto prawidłowy GEDCOM. Oczyszczanie nie jest porzebne.";
$pgv_lang["validate_gedcom"]	= "Sprawdź poprawność GEDCOM-u";
$pgv_lang["verified"]			= "Użytkownik zweryfikowany";
$pgv_lang["verified_by_admin"]	= "Zatwierdzony przez administratora";
$pgv_lang["verify_gedcom"]		= "Weryfikuj GEDCOM";
$pgv_lang["verify_upload_instructions"]	= "Jeżeli zdecydujesz się na kontynuację, stary plik gedcom zostanie zastąpiony nowym i zacznie się importowanie. Jeżeli przerwiesz, stary gedcom pozostanie niezmieniony.";
$pgv_lang["view_changelog"]			= "Pokaż plik changelog.txt";
$pgv_lang["view_logs"]			= "Pokaż logi";
$pgv_lang["visibleonline"]			= "Widoczny dla innych użytkowników";
$pgv_lang["visitor"]				= "Gość";
$pgv_lang["you_may_login"]		= " przez administratora.  Możesz teraz zalogować się podążając za odnośnikiem poniżej:";


?>
