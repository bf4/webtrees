<?php
/**
 * Polish texts
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
 *
 * Modifications Copyright (c) 2010 Greg Roach
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
 * @author Łukasz Wileński <wooc@users.sourceforge.net>
 * @package webtrees
 * @subpackage Languages
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$pgv_lang["module_admin"]			= "Zarządzanie modułami";
$pgv_lang["mod_admin_installed"]	= "Zainstalowane moduły";
$pgv_lang["mod_admin_tabs"]			= "Zarządzaj zakładkami";
$pgv_lang["mod_admin_menus"]		= "Zarządzaj menu";
$pgv_lang["mod_admin_intro"]		= "Poniżej znajduje się lista wszystkich modułów zainstalowanych w tej wersji PhpGedView. Moduły są instalowane poprzez zapisanie ich w katalogu <b>modules</b>. Tutaj można ustawić poziom dostępu dla każdego modułu w zależności od pliku GEDCOM. Jeśli moduł zawiera zakładki dla strony informacji o osobie lub obiekty menu można również ustwić poziom dostępu jak również ich kolejność.";
$pgv_lang["mod_admin_active"]		= "Aktywny";
$pgv_lang["mod_admin_name"]			= "Nazwa modułu";
$pgv_lang["mod_admin_description"]	= "Opis";
$pgv_lang["mod_admin_version"]		= "Wersja / PGV";
$pgv_lang["mod_admin_hastab"]		= "Zakładka?";
$pgv_lang["mod_admin_hasmenu"]		= "Menu?";
$pgv_lang["mod_admin_access_level"]	= "Poziom dostępu";
$pgv_lang["mod_admin_order"]		= "Kolejność";
$pgv_lang["mod_admin_config"]		= "Konfiguracja";
$pgv_lang["mod_admin_settings"]		= "Ustawienia konfiguracyjne modułu";
$pgv_lang["ret_module_admin"]		= "Wróc do strony administracyjnej modułu";
$pgv_lang["ret_admin"]				= "Wróć do strony administracyjnej";

$pgv_lang["enter_comment"]			= "Tutaj można wprowadzić komentarz";
$pgv_lang["upload_a_gedcom"]		= "Wgraj plik GEDCOM";
$pgv_lang["start_entering"]			= "Rozpocznij wprowadzanie danych";
$pgv_lang["add_gedcom_from_path"]	= "Dodaj plik GEDCOM";
$pgv_lang["manage_gedcoms"]			= "Zarządzaj plikami GEDCOM";
$pgv_lang["get_started_instructions"] = "Wybierz jedną z poniższych opcji, aby zacząć korzystać z PhpGedView";

$pgv_lang["admin_users_exists"]		= "Istniejący użytkownicy z prawami administracyjnymi:";
$pgv_lang["install_step_1"]			= "Sprawdź środowisko";
$pgv_lang["install_step_2"]			= "Połączenie z bazą danych";
$pgv_lang["install_step_3"]			= "Utwórz tabele";
$pgv_lang["install_step_4"]			= "Konfiguracja strony";
$pgv_lang["install_step_5"]			= "Języki";
$pgv_lang["install_step_6"]			= "Zapisz konfigurację";
$pgv_lang["install_step_7"]			= "Utwórz administratora";
$pgv_lang["install_wizard"]			= "Kreator instalacji";
$pgv_lang["basic_site_config"]		= "Ustawienia podstawowe";
$pgv_lang["adv_site_config"]		= "Ustawienia zaawansowane";
$pgv_lang["config_not_saved"]		= "*Twoje ustawienia<br />nie będą zapisane<br />dopóki nie ukończysz kroku 6.";
$pgv_lang["download_config"]		= "Pobierz config.php";
$pgv_lang["site_unavailable"]		= "Strona jest obecnie niedostępna";
$pgv_lang["to_manage_users"]		= "By zarządzać użytkownikami użyj strony <a href=\"useradmin.php\">Zarządzania użytkownikami</a>.";
$pgv_lang["db_tables_created"]		= "Tabele bazy danych zostały utworzone";
$pgv_lang["config_saved"]			= "Konfiguracja zapisana pomyślnie";
$pgv_lang["checking_errors"]		= "Sprawdzanie serwera...";
$pgv_lang["checking_php_version"]	= "Sprawdzanie wymaganej wersji PHP:";
$pgv_lang["failed"]					= "Test nie zaliczony";
$pgv_lang["pgv_requires_version"]	= "PhpGedView wymaga PHP w wersji #PGV_REQUIRED_PHP_VERSION# lub wyższej.";
$pgv_lang["using_php_version"]		= "Używasz PHP w wersji #PGV_ACTUAL_PHP_VERSION#";
$pgv_lang["checking_db_support"]	= "Sprawdzanie wspieranych baz danych:";
$pgv_lang["no_db_extensions"]		= "Nie posiadasz żadnego z obsługiwanych rozszerzeń bazy danych.";
$pgv_lang["db_ext_support"]			= "Posiadasz wsparcie #DBEXT#";
$pgv_lang["checking_config.php"]	= "Sprawdzanie pliku config.php:";
$pgv_lang["config.php_missing"]		= "Plik config.php nie został znaleziony.";
$pgv_lang["config.php_missing_instr"]	= "Kreator instalacji nie będzie w stanie zapisać ustawień do pliku config.php. Możesz wykonać kopię pliku config.dist i zmienić jego nazwę na config.php. Alternatywnie, po zakończeniu tego kreatora będziesz miał możliwość pobrania ustawień i przesłania wynikowego pliku config.php.";
$pgv_lang["config.php_not_writable"]	= "Plik config.php jest tylko do odczytu.";
$pgv_lang["config.php_not_writable_instr"]	= "Kreator instalacji nie będzie w stanie zapisać ustawień do pliku config.php. Plik ma ustawiony atrybut tylko do odczytu, po zakończeniu tego kreatora będziesz miał możliwość pobrania ustawień i przesłania wynikowego pliku config.php.";
$pgv_lang["passed"]					= "Test zaliczony";
$pgv_lang["config.php_writable"]	= "Plik config.php jest obecny i jest zapisywalny.";
$pgv_lang["checking_warnings"]		= "Sprawdzanie ograniczeń...";
$pgv_lang["checking_timelimit"]		= "Sprawdzanie możliwości zmiany limitu czasu:";
$pgv_lang["cannot_change_timelimit"]= "Nie można zmienić limitu czasu.";
$pgv_lang["cannot_change_timelimit_instr"] = "Możesz nie być w stanie uruchomić wszystkich funkcji na dużych plikach GEDCOM z dużą ilością osób.";
$pgv_lang["current_max_timelimit"]	= "Maksymalny limit czasu";
$pgv_lang["check_memlimit"]			= "Sprawdzanie możliwości zmiany limitu pamięci:";
$pgv_lang["cannot_change_memlimit"]	= "Nie można zmienić limitu pamięci.";
$pgv_lang["cannot_change_memlimit_instr"] = "Możesz nie być w stanie uruchomić wszystkich funkcji na dużych plikach GEDCOM z dużą ilością osób.";
$pgv_lang["current_max_memlimit"]	= "Obecny limit pamięci";
$pgv_lang["check_upload"]			= "Sprawdzanie możliwości przesyłania plików:";
$pgv_lang["current_max_upload"]		= "Maksymalny rozmiar przesyłanego pliku:";
$pgv_lang["check_gd"]				= "Sprawdzanie biblioteki GD image:";
$pgv_lang["cannot_use_gd"]			= "Brak biblioteki GD image. Nie będzie można automatycznie tworzyć miniatur obrazów.";
$pgv_lang["check_sax"]				= "Sprawdzanie biblioteki SAX XML:";
$pgv_lang["cannot_use_sax"]			= "Brak biblioteki SAX XML. Nie będzie w stanie uruchomić raportów lub innych pomocniczych funkcji.";
$pgv_lang["check_dom"]				= "Sprawdzanie biblioteki DOM XML:";
$pgv_lang["cannot_use_dom"]			= "Brak biblioteki DOM XML. Nie będzie można eksportować do XMLa.";
$pgv_lang["check_calendar"]			= "Sprawdzanie biblioteki Advanced Calendar (Zaawansowanego Kalendarza):";
$pgv_lang["cannot_use_calendar"]	= "Nie masz wsparcia zaawansowanego kalendarza. Nie będzie w stanie uruchomić niektórych zaawansowanych funkcji kalendarza.";
$pgv_lang["warnings_passed"]		= "Test zakończony pomyślnie. Brak ograniczeń.";
$pgv_lang["warning_instr"]			= "Jeżeli występują jakieś ograniczenia nadal możesz uruchomić PhpGedView na tym serwerze, ale niektóre funkcje mogą być wyłączone lub jakość ich działania może być niezadawalająca.";
$pgv_lang["user"]					= "Użytkownik autoryzowany";
$pgv_lang["editors"]				= "Użytkownicy z prawem edycji";
$pgv_lang["gedcom_admins"]			= "Administratorzy pliku GEDCOM";
$pgv_lang["site_admins"]			= "Administratorzy strony";
$pgv_lang["nobody"]					= "Nikt";
$pgv_lang["thumbnail_deleted"]		= "Plik miniaturek został usunięty.";
$pgv_lang["thumbnail_not_deleted"]	= "Usuwanie pliku miniaturek nie powiodło się.";
$pgv_lang["refresh"]				= "Odśwież";
$pgv_lang["move_file_success"]		= "Przenoszenie plików multimediów i miniaturek zakończone pomyślnie.";
$pgv_lang["media_folder_corrupt"]	= "Katalog z multimediami jest uszkodzony.";
$pgv_lang["media_file_not_deleted"]	= "Usuwanie pliku multimedialnego nie powiodło się.";
$pgv_lang["gedcom_deleted"]			= "GEDCOM [#GED#] został usunięty.";
$pgv_lang["gedadmin"]				= "Administrator GEDCOM";
$pgv_lang["full_name"]				= "Imię i nazwisko";
$pgv_lang["error_header"] 			= "Plik GEDCOM <b>#GEDCOM#</b> nie istnieje pod podaną lokalizacją.";
$pgv_lang["confirm_delete_file"]	= "Czy na pewno chcesz usunąć ten plik?";
$pgv_lang["confirm_folder_delete"] 	= "Czy na pewno chcesz usunąć ten katalog?";
$pgv_lang["confirm_remove_links"]	= "Czy na pewno chcesz usunąć wszystkie powiązania z tym obiektem?";
$pgv_lang["PRIV_PUBLIC"]			= "Pokaż wszystkim";
$pgv_lang["PRIV_USER"]				= "Pokaż tylko autoryzowanym użytkownikom";
$pgv_lang["PRIV_NONE"]				= "Pokaż tylko administratorom";
$pgv_lang["PRIV_HIDE"]				= "Ukryj nawet przed administratorami";
$pgv_lang["manage_gedcoms"]			= "Zarządzaj plikami GEDCOM";
$pgv_lang["keep_media"]				= "Zachowaj powiązania multimedialne";
$pgv_lang["current_links"]			= "Powiązania";
$pgv_lang["add_more_links"]			= "Dodaj powiązania";
$pgv_lang["enter_pid_or_name"]		= "Wprowadź identyfikator lub nazwisko i imię osoby";
$pgv_lang["set_links"]				= "Ustaw powiązania";
$pgv_lang["add_or_remove_links"]	= "Zarządzaj powiązaniami";

$pgv_lang["keep"]					= "Zachowaj";
$pgv_lang["unlink"]					= "Usuń";
$pgv_lang["nav"]					= "Nawigator";
$pgv_lang["fam_nav"]				= "Nawigator rodziny";
$pgv_lang["keep_link"]				= "Zachowaj powiązanie na liście";
$pgv_lang["remove_link"]			= "Usuń powiązanie z listy";
$pgv_lang["open_nav"]				= "Otwórz nawigator rodziny";
$pgv_lang["link_exists"]			= "Te powiązanie już istnieje";
$pgv_lang["id_not_valid"]			= "Nieprawidłowy identyfikator Osoby, Rodziny lub Źródła";
$pgv_lang["add_fam_other_links"]	= "Dodaj rodzinę i wyszukane osoby";
$pgv_lang["search_add_links"]		= "Szukaj osób, które chcesz dodać do listy powiązań z multimediami.";
$pgv_lang["enter_name"]				= "Wprowadź imię lub nazwisko ";
$pgv_lang["add_indi_to_link_list"]	= "Kliknij imię i nazwisko osoby aby dodać ją do listy powiązań.";
$pgv_lang["click_choose_head"]		= "Kliknij #GLOBALS[tempStringHead]# aby wybrać osobę jako Głowę rodziny.";
$pgv_lang["click_choose_head_text"]	= "Kliknij aby wybrać osobę jako Głowę rodziny.";
$pgv_lang["head"]					= "Głowa";
$pgv_lang["id_empty"]				= "Podczas dodawania powiązania pole identyfikatora nie może być puste.";
$pgv_lang["link_deleted"]			= "Powiązanie z #GLOBALS[remLinkId]# zostało usunięte";
$pgv_lang["link_added"]				= "Dodano powiązanie z #GLOBALS[addLinkId]#";
$pgv_lang["no_update_CHANs"]		= "Nie aktualizuj wpisów CHAN (ostatnia zmiana)";
$pgv_lang["no_CHANs_update"]		= "Nie zaktualizowano wpisów CHAN (ostatnia zmiana)";

$pgv_lang["files_in_backup"]		= "Pliki objęte tą kopią zapasową";
$pgv_lang["created_remotelinks"]	= "Tworzenie tabeli <i>Zewnętrzne odnośniki</i> zostało zakończone.";
$pgv_lang["created_remotelinks_fail"]	= "Tworzenie tabeli <i>Zewnętrzne odnośniki</i> nie powiodło się.";
$pgv_lang["created_indis"]			= "Tworzenie tabeli <i>Osoby</i> zostało zakończone.";
$pgv_lang["created_indis_fail"]		= "Tworzenie tabeli <i>Osoby</i> nie powiodło się.";
$pgv_lang["created_fams"]			= "Tworzenie tabeli <i>Rodziny</i> zostało zakończone.";
$pgv_lang["created_fams_fail"]		= "Tworzenie tabeli <i>Rodziny</i> nie powiodło się.";
$pgv_lang["created_sources"]		= "Tworzenie tabeli <i>Źródła</i> zostało zakończone.";
$pgv_lang["created_sources_fail"]	= "Tworzenie tabeli <i>Źródła</i> nie powiodło się.";
$pgv_lang["created_other"]			= "Tworzenie tabeli <ii>Inne</i> zostało zakończone.";
$pgv_lang["created_other_fail"]		= "Tworzenie tabeli <i>Inne</i> nie powiodło się.";
$pgv_lang["created_places"]			= "Utworzono tabelę <i>Miejsc</i>.";
$pgv_lang["created_places_fail"]	= "Błąd utworzenia tabeli <i>Miejsc</i>.";
$pgv_lang["created_placelinks"] 	= "Tworzenie tabeli <i>Odnośniki miejsc</i> zostało zakończone.";
$pgv_lang["created_placelinks_fail"]= "Tworzenie tabeli <i>Odnośniki miejsc</i> nie powiodło się.";
$pgv_lang["created_media_fail"]		= "Tworzenie tabeli <i>Multimedia</i> nie powiodło się.";
$pgv_lang["created_media_mapping_fail"]	= "Tworzenie tabeli <i>Mapowanie multimediów</i> nie powiodło się.";
$pgv_lang["no_thumb_dir"]			= "Katalog miniaturek nie istnieje i tworzenie go nie powiodło się.";
$pgv_lang["folder_created"]			= "Utworzono katalog";
$pgv_lang["folder_no_create"]		= "Tworzenie katalogu nie powiodło się";
$pgv_lang["security_no_create"]		= "Ostrzeżenie bezpieczeństwa: Nie można utworzyć pliku <b><i>index.php</i></b> w";
$pgv_lang["security_not_exist"]		= "Ostrzeżenie bezpieczeństwa: Plik <b><i>index.php</i></b> nie istnieje w";
$pgv_lang["label_added_servers"]	= "Zdalne serwery";
$pgv_lang["label_banned_servers"]	= "Zabroń dostępu dla stron po IP";
$pgv_lang["label_delete"]			= "Usuń";
$pgv_lang["progress_bars_info"]		= "Paski postępu poniżej dadzą ci możliwość śledzenia statusu importu. Jeśli przekroczony zostanie limit czasu, import zostanie wstrzymany do czasu, aż wciśniesz przycisk <b>Kontynuuj</b>. Jeśli nie zobaczysz przycisku <b>Kontynuuj</b>, będzie trzeba rozpocząć import ponownie z mniejszym ograniczeniem na czas. Gdy plik GEDCOM zostanie wczytany pojawi się napis <b>Import zakończony</b>.";
$pgv_lang["upload_replacement"]		= "Wczytaj nową wersję";
$pgv_lang["about_user"]				= "Najpierw musisz stworzyć głównego administratora. Ten użytkownik będzie miał uprawnienia modyfikowania plików konfiguracyjnych, przeglądania poufnych danych i tworzenia nowych użytkowników.";
$pgv_lang["access"]					= "Dostęp";
$pgv_lang["add_gedcom"]				= "Dodaj plik GEDCOM";
$pgv_lang["add_new_gedcom"]			= "Utwórz nowy plik GEDCOM";
$pgv_lang["add_new_language"]		= "Dodaj pliki i ustawienia dla nowego języka";
$pgv_lang["add_user"]				= "Dodaj nowego użytkownika";
$pgv_lang["admin_gedcom"]			= "Administrator GEDCOM";
$pgv_lang["admin_gedcoms"]			= "Kliknij tutaj aby zarządzać plikami GEDCOM";
$pgv_lang["admin_geds"]				= "Zarządanie danymi i plikami GEDCOM";
$pgv_lang["admin_info"]				= "Informacyjne";
$pgv_lang["admin_site"]				= "Zarządzanie stroną";
$pgv_lang["admin_user_warnings"]	= "Co najmniej jedno konto użytkownika wygenerowało ostrzeżenie";
$pgv_lang["admin_verification_waiting"] = "Konta użytkowników oczekują na zatwierdzenie przez administratora";
$pgv_lang["administration"]			= "Administracja";
$pgv_lang["ALLOW_CHANGE_GEDCOM"]	= "Zezwalaj na przełączanie pomiędzy plikami GEDCOM";
$pgv_lang["ALLOW_USER_THEMES"]		= "Zezwalaj użytkownikom wybierać własny motyw";
$pgv_lang["ansi_encoding_detected"]	= "Wykryto kodowanie znaków w standardzie ANSI. PhpGedView działa najlepiej z plikami zakodowanymi w UTF-8.";
$pgv_lang["ansi_to_utf8"]			= "Czy przekonwertować plik GEDCOM z ANSI do UTF-8?";
$pgv_lang["apply_privacy"]			= "Czy zastosować ustawienia prywatności?";
$pgv_lang["back_useradmin"]			= "Powrót do zarządzania użytkownikami";
$pgv_lang["bytes_read"]				= "Wczytanych bajtów:";
$pgv_lang["can_admin"]				= "Użytkownik może zarządzać";
$pgv_lang["can_edit"]				= "Poziom dostępu";
$pgv_lang["change_id"]				= "Zmień identyfikator osoby na:";
$pgv_lang["choose_priv"]			= "wybierz poziom prywatności:";
$pgv_lang["cleanup_places"]			= "Porządkuj miejsca";
$pgv_lang["cleanup_users"]			= "Porządkuj użytkowników";
$pgv_lang["click_here_to_continue"]	= "Kliknij tu aby kontynuować.";
$pgv_lang["click_here_to_go_to_pedigree_tree"]	= "Kliknij tu by iść do drzewa genealogicznego.";
$pgv_lang["comment"]				= "Komentarz administratora do użytkownika";
$pgv_lang["comment_exp"]			= "Ostrzeżenie administratora z datą";
$pgv_lang["config_help"]			= "Pomoc konfiguracyjna";
$pgv_lang["config_still_writable"]	= "Plik <i>config.php</i> nie jest zabezpieczony przed zapisem. Ze względów bezpieczeństwa należy ustawić prawa dostępu do tego pliku na tylko-do-odczytu kiedy konfiguracja zostanie zakończona.";
$pgv_lang["configuration"]			= "Konfiguracja";
$pgv_lang["configure"]				= "Konfiguruj PhpGedView";
$pgv_lang["configure_head"]			= "Konfiguracja PhpGedView";
$pgv_lang["confirm_gedcom_delete"]	= "Czy na pewno chcesz usunąć ten plik GEDCOM?";
$pgv_lang["confirm_user_delete"]	= "Czy na pewno chcesz usunąć tego użytkownika?";
$pgv_lang["create_user"]			= "Utwórz użytkownika";
$pgv_lang["current_users"]			= "Lista użytkowników";
$pgv_lang["daily"]					= "Codziennie";
$pgv_lang["dataset_exists"]			= "Plik GEDCOM o tej nazwie został już zaimportowany do bazy danych.";
$pgv_lang["unsync_warning"]			= "Ten plik GEDCOM <em>nie</em> jest zsynchronizowany z bazą danych. Może nie zawierać ostatnich zmian. Powinieneś wgrać jeszcze raz.";
$pgv_lang["date_registered"]		= "Data rejestracji";
$pgv_lang["day_before_month"]		= "Dzień przed miesiącem (DD MM RRRR)";
$pgv_lang["DEFAULT_GEDCOM"]			= "Domyślny plik GEDCOM";
$pgv_lang["default_user"]			= "Utwórz domyślnego administratora.";
$pgv_lang["del_gedrights"]			= "Plik GEDCOM nie jest aktywny, usuń powiązania z użytkownikami.";
$pgv_lang["del_proceed"]			= "Dalej";
$pgv_lang["del_unvera"]				= "Użytkownik nie został zatwierdzony przez administratora.";
$pgv_lang["del_unveru"]				= "Użytkownik nie potwierdził zgłoszenia w ciągu 7 dni.";
$pgv_lang["do_not_change"]			= "Nie zmieniaj";
$pgv_lang["download_gedcom"]		= "Pobierz plik GEDCOM";
$pgv_lang["download_here"]			= "Kliknij tutaj aby pobrać plik.";
$pgv_lang["download_note"]			= "Uwaga: Przetworzenie dużych plików baz danych przed pobraniem może zająć dużo czasu. Jeśli połączenie wygaśnie zanim pobieranie zostanie zakończone, możesz nie zakończyć pobierania poprawnie.<br /><br />Aby upewnić się, że pobieranie zakończyło się pomyślnie, sprawdź czy ostatnia linia pliku GEDCOM to <b>0&nbsp;TRLR</b>. W przypadku pliku w formacie XML ostatnią linią powinno być <b>&lt;/database&gt;</b>. Pliki te są plikami tekstowymi; możesz użyć dowolnego edytora tekstu do przejrzenia pliku, ale <u>nie</u> zapisuj pobranego pliku GEDCOM po tym, jak go przejrzysz.<br /><br />Przeważnie pobieranie pliku GEDCOM powinno zająć tyle samo czasu, co jego importowanie.";
$pgv_lang["editaccount"]			= "Zezwól użytkownikowi na edycję konta";
$pgv_lang["empty_dataset"]			= "Czy chcesz usunąć stare dane i nadpisać je nowymi?";
$pgv_lang["empty_lines_detected"]	= "Wykryto puste linie w pliku GEDCOM. Podczas porządkowania zostaną one usunięte.";
$pgv_lang["enable_disable_lang"]	= "Konfiguruj dostępne języki";
$pgv_lang["error_ban_server"]		= "Nieprawidłowy adres IP.";
$pgv_lang["error_delete_person"]	= "Musisz wybrać osobę, której zewnętrzny odnośnik chcesz usunąć.";
$pgv_lang["error_header_write"]		= "Plik GEDCOM [#GEDCOM#] jest zabezpieczony przed zapisem. Sprawdź atrybuty i prawa dostępu.";
$pgv_lang["error_siteauth_failed"]	= "Autoryzacja na zewnętrznej stronie nie powiodła się";
$pgv_lang["error_url_blank"]		= "Podaj tytuł lub adres URL zewnętrznej strony.";
$pgv_lang["error_view_info"]		= "Wybierz osobę, której szczegóły chcesz zobaczyć.";
$pgv_lang["example_date"]			= "Przykład nieprawidłowej daty w twoim GEDCOM-ie:";
$pgv_lang["example_place"]			= "Przykład nieprawidłowego miejsca w twoim GEDCOM-ie:";
$pgv_lang["found_record"]			= "Znalezionych wpisów";
$pgv_lang["ged_download"]			= "Pobierz";
$pgv_lang["ged_import"]				= "Importuj";
$pgv_lang["ged_export"] 			= "Eksportuj";
$pgv_lang["ged_check"] 				= "Sprawdź";
$pgv_lang["gedcom_adm_head"]		= "Zarządzanie plikami GEDCOM";
$pgv_lang["gedcom_config_write_error"]	= "Błąd! Nie można zapisać do pliku <i>#GLOBALS[whichFile]#</i>. Sprawdź, czy plik ma wymagane uprawnienia do zapisu.";
$pgv_lang["gedcom_downloadable"]	= "Tego pliku GEDCOM nie można pobrać przez internet!<br />Zobacz sekcję BEZPIECZEŃSTWO w pliku <a href=\"readme.txt\"><b>readme.txt</b></a> aby naprawić ten problem";
$pgv_lang["gedcom_file"]			= "Plik GEDCOM:";
$pgv_lang["gedcom_not_imported"]	= "Ten plik GEDCOM nie został jeszcze zaimportowany.";
$pgv_lang["img_admin_settings"]		= "Zmień konfigurację manipulacji obrazami";
$pgv_lang["autoContinue"]			= "Kontynuuj automatycznie";
$pgv_lang["import_complete"]		= "Import zakończony";
$pgv_lang["import_options"]			= "Opcje importu";
$pgv_lang["import_progress"]		= "Trwa importowanie...";
$pgv_lang["import_statistics"]		= "Statystyki importu";
$pgv_lang["import_time_exceeded"]	= "Przekroczono limit czasu wykonania. Kliknij na przycisk Dalej aby wznowić import pliku GEDCOM.";
$pgv_lang["inc_languages"]			= "Języki";
$pgv_lang["INDEX_DIRECTORY"]		= "Katalog z plikiem index";
$pgv_lang["invalid_dates"]			= "Wykryto nieprawidłowe formaty daty. Podczas porządkowania zostaną zamienione na format DD MMM RRRR (np. 01 JAN 2004).";
$pgv_lang["BOM_detected"] 			= "Wykryto znak BOM (Byte Order Mark) na początku pliku. Podczas porządkowania ten znak specialny zostanie usunięty.";
$pgv_lang["invalid_header"]			= "Wykryto wiersze przed nagłówkiem GEDCOM-u <b>0&nbsp;HEAD</b>. Podczas porządkowania te wiersze zostaną usunięte.";
$pgv_lang["label_families"]			= "Rodziny";
$pgv_lang["label_gedcom_id2"]		= "ID pliku GEDCOM:";
$pgv_lang["label_individuals"]		= "Osoby";
$pgv_lang["label_manual_search_engines"] = "Zaznacz ręcznie wyszukiwarki po IP";
$pgv_lang["label_new_server"]		= "Dodaj nową stronę";
$pgv_lang["label_password_id"]		= "Hasło";
$pgv_lang["label_server_info"]		= "Osoby i rodziny z zewnątrz powiązane przez stronę:";
$pgv_lang["label_server_url"]		= "URL/IP strony";
$pgv_lang["label_username_id"]		= "Login";
$pgv_lang["label_view_local"]		= "Zobacz lokalne dane osoby";
$pgv_lang["label_view_remote"]		= "Zobacz dane osoby znajdujące się na stronie zdalnej";
$pgv_lang["LANG_SELECTION"] 		= "Dostępne języki";
$pgv_lang["LANGUAGE_DEFAULT"]		= "Nie skonfigurowano dostępnych języków dla strony.<br />PhpGedView użyje ustawień domyślnych.";
$pgv_lang["last_login"]				= "Ostatni login";
$pgv_lang["lasttab"]				= "Ostatnio odwiedzona zakładka osoby";
$pgv_lang["leave_blank"]			= "Pozostaw to pole puste, jeśli nie chcesz zmieniać hasła.";
$pgv_lang["link_manage_servers"]	= "Zarządzaj stronami";
$pgv_lang["logfile_content"]		= "Zawartość plika loga";
$pgv_lang["macfile_detected"]		= "Wykryto plik Macintosha. Podczas porządkowania zostanie przekonwertowany do pliku DOS.";
$pgv_lang["mailto"]					= "Odnośnik mailto";
$pgv_lang["merge_records"]			= "Scal wpisy";
$pgv_lang["message_to_all"]			= "Wyślij wiadomość do wszystkich użytkowników";
$pgv_lang["messaging"]				= "Wewnętrzna komunikacja PhpGedView";
$pgv_lang["messaging2"]				= "Wewnętrzna komunikacja przez email";
$pgv_lang["messaging3"]				= "PhpGedView wysyła emaile bez zachowywania";
$pgv_lang["month_before_day"]		= "Miesiąc przed dniem (MM DD RRRR)";
$pgv_lang["monthly"]				= "Co miesiąc";
$pgv_lang["mysql"]					= "MySQL";
$pgv_lang["never"]					= "nigdy";
$pgv_lang["no_logs"]				= "Wyłącz logowanie";
$pgv_lang["no_messaging"]			= "Brak kontaktu";
$pgv_lang["page_views"]				= "&nbsp;&nbsp;wizyt na stronie w ciągu&nbsp;&nbsp;";
$pgv_lang["performing_validation"]	= "Sprawdzanie poprawności pliku GEDCOM...";
$pgv_lang["pgv_config_write_error"] = "Błąd! Nie można zapisywać do pliku konfiguracyjnego PhpGedView. Sprawdź uprawnienia pliku i katalogu i spróbuj ponownie.";
$pgv_lang["PGV_MEMORY_LIMIT"]		= "Limit pamięci";
$pgv_lang["PGV_SESSION_SAVE_PATH"]	= "Ścieżka zapisu sesji";
$pgv_lang["PGV_SESSION_TIME"]		= "Wygaśnięcie sesji";
$pgv_lang["PGV_SIMPLE_MAIL"]		= "Użyj prostych nagłówków w zewnętrznych wiadomościach";
$pgv_lang["PGV_SMTP_ACTIVE"]		= "Użyj SMTP do wysyłania wiadomości e-mail";
$pgv_lang["PGV_SMTP_HOST"]			= "Serwer SMTP";
$pgv_lang["PGV_SMTP_HELO"]			= "Nazwa domeny SMTP";
$pgv_lang["PGV_SMTP_PORT"]			= "Port SMTP";
$pgv_lang["PGV_SMTP_AUTH"]			= "Użyj nazwy i hasła";
$pgv_lang["PGV_SMTP_AUTH_USER"]		= "Nazwa użytkownika";
$pgv_lang["PGV_SMTP_AUTH_PASS"]		= "Hasło";
$pgv_lang["PGV_SMTP_SSL"]			= "Bezpieczne połączenie";
$pgv_lang["PGV_SMTP_FROM_NAME"]		= "Nazwa nadawcy";
$pgv_lang["PGV_STORE_MESSAGES"]		= "Zezwól na przechowywanie wiadomości online";
$pgv_lang["phpinfo"]				= "PHPInfo";
$pgv_lang["place_cleanup_detected"]	= "Wykryto miejsca z nieprawidłowym kodowaniem. Te błędy należy poprawić.";
$pgv_lang["please_be_patient"]		= "Prosimy o cierpliwość";
$pgv_lang["privileges"]				= "Uprawnienia";
$pgv_lang["reading_file"]			= "Wczytywanie pliku GEDCOM";
$pgv_lang["readme_documentation"]	= "Dokumentacja README";
$pgv_lang["remove_ip"]				= "Usuń IP";
$pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"] = "Wymagaj zatwierdzenia przez administratora rejestracji nowych użytkowników";
$pgv_lang["review_readme"]			= "Przejrzyj plik <a href=\"readme.txt\" target=\"_blank\">readme.txt</a> przed dalszą konfiguracją PhpGedView.";
$pgv_lang["seconds"]				= "&nbsp;&nbsp;sekund";
$pgv_lang["select_an_option"]		= "Wybierz opcję poniżej:";
$pgv_lang["SERVER_URL"]				= "PhpGedView URL";
$pgv_lang["show_phpinfo"]			= "Pokaż stronę informacyjną PHP";
$pgv_lang["siteadmin"]				= "Administrator strony";
$pgv_lang["system_time"]			= "Bieżący czas serwera:";
$pgv_lang["user_time"]				= "Bieżący czas użytkownika:";
$pgv_lang["TBLPREFIX"]				= "Przedrostek tabel w bazie danych";
$pgv_lang["themecustomization"]		= "Dostosowywanie motywu";
$pgv_lang["time_limit"]				= "Limit czasu:";
$pgv_lang["title_manage_servers"]	= "Zarządzaj stronami";
$pgv_lang["title_view_conns"]		= "Zobacz połączenia";
$pgv_lang["translator_tools"]		= "Narzędzia tłumacza";
$pgv_lang["update_myaccount"]		= "Aktualizuj moje konto";
$pgv_lang["update_user"]			= "Aktualizuj konto użytkownika";
$pgv_lang["upload_gedcom"]			= "Wgraj plik GEDCOM";
$pgv_lang["USE_REGISTRATION_MODULE"]= "Zezwól gościom na rejestrację";
$pgv_lang["user_auto_accept"]		= "Automatycznie zatwierdź zmiany tego użytkownika";
$pgv_lang["user_contact_method"]	= "Preferowana metoda kontaktu";
$pgv_lang["user_create_error"]		= "Nie można dodać użytkownika. Spróbuj ponownie.";
$pgv_lang["user_created"]			= "Tworzenie użytkownika zostało zakończone pomyślnie.";
$pgv_lang["user_default_tab"]		= "Domyślna zakładka na stronie danych osoby";
$pgv_lang["user_path_length"]		= "Maksymalna długość ścieżki pokrewieństwa dla prywatności";
$pgv_lang["user_relationship_priv"]	= "Ogranicz dostęp do spokrewnionych osób";
$pgv_lang["users_admin"]			= "Administratorzy strony";
$pgv_lang["users_gedadmin"]			= "Administratorzy GEDCOM";
$pgv_lang["users_total"]			= "Liczba użytkowników";
$pgv_lang["users_unver"]			= "Niepotwierdzone przez użytkownika";
$pgv_lang["users_unver_admin"]		= "Niezatwierdzone przez administratora";
$pgv_lang["usr_deleted"]			= "Usunięty użytkownik:";
$pgv_lang["usr_idle"]				= "Liczba miesięcy od ostatniego logowania do uznania konta za nieaktywne:";
$pgv_lang["usr_idle_toolong"]		= "Konto użytkownika było zbyt długo nieaktywne:";
$pgv_lang["usr_no_cleanup"]			= "Nie ma potrzeby porządkowania";
$pgv_lang["usr_unset_gedcomid"]		= "Usuń identyfikator GEDCOM dla";
$pgv_lang["usr_unset_rights"]		= "Usuń prawa GEDCOM dla";
$pgv_lang["usr_unset_rootid"]		= "Usuń identyfikator korzenia dla";
$pgv_lang["valid_gedcom"]			= "Wykryto prawidłowy plik GEDCOM. Nie ma potrzeby porządkowania.";
$pgv_lang["validate_gedcom"]		= "Sprawdź poprawność pliku GEDCOM";
$pgv_lang["verified"]				= "Potwierdzony przez siebie";
$pgv_lang["verified_by_admin"]		= "Zatwierdzony przez administratora";
$pgv_lang["verify_gedcom"]			= "Zatwierdź plik GEDCOM";
$pgv_lang["verify_upload_instructions"]	= "Wykryto plik GEDCOM o tej samej nazwie. Jeśli będziesz kontynuować, starszy plik GEDCOM zostanie zastąpiony wgrywanym plikiem i importowanie rozpocznie się od nowa. Jeśli anulujesz, starszy plik GEDCOM pozostanie niezmieniony.";
$pgv_lang["view_logs"]				= "Zobacz pliki loga";
$pgv_lang["view_readme"]			= "Zobacz plik readme.txt";
$pgv_lang["visibleonline"]			= "Widoczny dla innych użytkowników";
$pgv_lang["visitor"]				= "Gość";
$pgv_lang["warn_users"]				= "Użytkownicy z ostrzeżeniami";
$pgv_lang["weekly"]					= "Co tydzień";
$pgv_lang["welcome_new"]			= "Witaj na swojej nowej stronie PhpGedView.";
$pgv_lang["yearly"]					= "Co rok";
$pgv_lang["admin_OK_subject"]		= "Zatwierdzenie konta na #SERVER_NAME#";
$pgv_lang["admin_OK_message"]		= "Administrator strony #SERVER_NAME# zatwierdził twoje zgłoszenie. Możesz się teraz zalogować używając odnośnika: #SERVER_NAME#";
$pgv_lang["batch_update"]			= "Uaktualnij/edytuj informacje w plikach GEDCOM";
$pgv_lang["gedcheck"]				= "Tester GEDCOM";
$pgv_lang["gedcheck_text"]			= "Ten moduł sprawdza poprawność formatu pliku GEDCOM według <a href=\"http://phpgedview.sourceforge.net/ged551-5.pdf\">Specyfikacji 5.5.1 GEDCOM</a>. Wykrywa także wiele rodzajów częstych błędów w danych. Pamiętaj, że istnieje wiele wersji, rozszerzeń i odmian specyfikacji, więc nie musisz się przejmować żadnymi błędami poza tymi oznaczonymi jako krytyczne. Objaśnienia do wszystkich błędów możesz znaleźć w specyfikacji, więc zajrzyj do niej zanim poprosisz o pomoc.";
$pgv_lang["gedcheck_sync"]			= "Modyfikacje wprowadzone do bazy danych nie są zsynchronizowane z plikiem #GLOBALS[ged]#. Zawartość pliku może być nieaktualna. Możesz zsynchronizować go z bazą danych, przeprowadzając operację <b><a \"#GLOBALS[ged_link]#\">eksportu</a></b>.";
$pgv_lang["gedcheck_nothing"]		= "Nie znaleziono błędów na tym poziomie.";
$pgv_lang["level"]					= "Poziom";
$pgv_lang["critical"]				= "Krytyczny";
$pgv_lang["error"]					= "Błąd";
$pgv_lang["warning"]				= "Ostrzeżenie";
$pgv_lang["info"]					= "Informacja";
$pgv_lang["open_link"]				= "Otwieraj odnośniki w";
$pgv_lang["same_win"]				= "starej zakładce/oknie";
$pgv_lang["new_win"]				= "nowej zakładce/oknie";
$pgv_lang["context_lines"]			= "Wiersze kontekstu GEDCOM";
$pgv_lang["all_rec"]				= "Wszystkie wpisy";
$pgv_lang["err_rec"]				= "Wpisy z błędami";
$pgv_lang["missing"]				= "brakujący";
$pgv_lang["multiple"]				= "wielokrotny";
$pgv_lang["invalid"]				= "niepoprawny";
$pgv_lang["too_many"]				= "zbyt wiele";
$pgv_lang["too_few"]				= "zbyt mało";
$pgv_lang["no_link"]				= "nie ma odnośnika powrotu";
$pgv_lang["data"]					= "dane";
$pgv_lang["see"]					= "zobacz";
$pgv_lang["noref"]					= "Brak odniesień do tego wpisu";
$pgv_lang["tag"]					= "tag";
$pgv_lang["spacing"]				= "odstęp";
$pgv_lang["ADVANCED_NAME_FACTS"]	= "Szczegółowe fakty o imieniu/nazwisku";
$pgv_lang["ADVANCED_PLAC_FACTS"]	= "Szczegółowe fakty o miejscu";
$pgv_lang["SURNAME_TRADITION"]		= "Tradycja przyjmowania nawiska";
$pgv_lang["tradition_spanish"]		= "hiszpańska";
$pgv_lang["tradition_portuguese"]	= "portugalska";
$pgv_lang["tradition_icelandic"]	= "islandzka";
$pgv_lang["tradition_paternal"]		= "ojcowska";
$pgv_lang["tradition_polish"]		= "polska";
$pgv_lang["tradition_none"]			= "brak";

$pgv_lang["clear_cache_succes"]		= "Pliki cache zostały usunięte.";
$pgv_lang["clear_cache"]			= "Wyczyść pliki cache";
$pgv_lang["sanity_err0"]			= "Błędy:";
$pgv_lang["sanity_err1"]			= "Potrzebujesz PHP w wersji #PGV_REQUIRED_PHP_VERSION# lub nowszej. ";
$pgv_lang["sanity_err2"]			= "Plik lub katalog <i>#GLOBALS[whichFile]#</i> nie istnieje. Sprawdź, czy plik lub katalog istnieje, czy ma poprawną nazwę, i czy prawa do odczytu są poprawnie ustawione.";
$pgv_lang["sanity_err3"]			= "Plik <i>#GLOBALS[whichFile]#</i> nie został wgrany poprawnie. Spróbuj wgrać ten plik jeszcze raz.";
$pgv_lang["sanity_err4"]			= "Plik <i>config.php</i> jest uszkodzony.";
$pgv_lang["sanity_err5"]			= "Plik <i>config.php</i> jest zabezpieczony przed zapisem.";
$pgv_lang["sanity_err6"]			= "Katalog <i>#GLOBALS[INDEX_DIRECTORY]#</i> jest zabezpieczony przed zapisem.";
$pgv_lang["sanity_warn0"]			= "Ostrzeżenia:";
$pgv_lang["sanity_warn1"]			= "Katalog <i>#GLOBALS[MEDIA_DIRECTORY]#</i> jest zabezpieczony przed zapisem. Nie będzie można wczytywać plików multimedialnych ani tworzyć miniaturek w PhpGedView.";
$pgv_lang["sanity_warn2"]			= "Katalog <i>#GLOBALS[MEDIA_DIRECTORY]#thumbs</i> jest zabezpieczony przed zapisem. Nie będzie można wczytywać miniaturek ani tworzyć miniaturek w PhpGedView.";
$pgv_lang["sanity_warn3"]			= "Brak biblioteki GD. PhpGedView będzie nadal działać, ale niektóre możliwości, takie jak tworzenie miniaturek i diagram kołowy nie będą dostępne bez biblioteki GD. Więcej informacji: <a href='http://www.php.net/manual/en/ref.image.php'>http://www.php.net/manual/en/ref.image.php</a> .";
$pgv_lang["sanity_warn4"]			= "Brak biblioteki XML Parser. PhpGedView będzie nadal działać, ale niektóre możliwości, takie jak tworzenie raportów i usługi webowe nie będą dostępne bez biblioteki XML Parser. Więcej informacji: <a href='http://www.php.net/manual/en/ref.xml.php'>http://www.php.net/manual/en/ref.xml.php</a>.";
$pgv_lang["sanity_warn5"]			= "Brak biblioteki DOM XML. PhpGedView będzie nadal działać, ale niektóre możliwości, takie jak Eksport Gramps w Wycinkach, pobieranie plików i usługi webowe nie będą dostęone. Więcej informacji: <a href='http://www.php.net/manual/en/ref.domxml.php'>http://www.php.net/manual/en/ref.domxml.php</a>.";
$pgv_lang["sanity_warn6"]			= "Brak biblioteki Calendar. PhpGedView będzie nadal działać, jednak niektóre możliwości, takie jak konwersja dat do innych kalendarzy (np. hebrajskiego lub francuskiego), nie będą dostępne. Ta biblioteka nie jest kluczowa do uruchomienia PhpGedView. Więcej informacji: <a href='http://www.php.net/manual/en/ref.calendar.php'>http://www.php.net/manual/en/ref.calendar.php</a>.";
$pgv_lang["ip_address"]				= "Adres IP";
$pgv_lang["date_time"]				= "Data i godzina";
$pgv_lang["log_message"]			= "Wpis loga";
$pgv_lang["searchtype"]				= "Typ wyszukiwania";
$pgv_lang["query"]					= "Zapytanie";
$pgv_lang["associated_files"]		= "Powiązane pliki:";
$pgv_lang["remove_all_files"]		= "Usuń wszystkie niepotrzebne pliki";
$pgv_lang["warn_file_delete"]		= "Ten plik zawiera ważne informcje takie jak ustawienia języka lub zmiany danych jeszcze nie zatwierdzone. Czy jesteś pewien by go usunąć?";
$pgv_lang["deleted_files"]			= "Usunięte pliki:";
$pgv_lang["index_dir_cleanup_inst"]	= "Aby usunąc plik lub podkatalog z katalogu Index przesuń go do kosza lub zaznacz. Kliknij przycisk Usuń aby trwale usunąć wybrane pliki.<br /><br />Pliki zaznaczone <img src=\"./images/RESN_confidential.gif\" alt=\"\" /> są wymaganymi do prawidłowego działania i nie mogą być usunięte.<br />Pliki zaznaczone <img src=\"./images/RESN_locked.gif\" alt=\"\" /> zawierają ważne ustawienia oraz zmiany nie zatwierdzone jeszcze przez administratora. Bez absolutnej pewności nie usuwaj ich.";
$pgv_lang["index_dir_cleanup"]		= "Wyczyść katalog Index";
$pgv_lang["error_remote_duplicate"]	= "Ta baza danych istnieje na liście jako <i>#GLOBALS[whichFile]#</i>";
$pgv_lang["error_remove_site"]			= "Zdalny serwer nie może być usunięty.";
$pgv_lang["error_remove_site_linked"]	= "Zdalny serwer nie może być usunięty ponieważ jego lista połączeń nie jest pusta.";

// -- The following text is used to build the phrase "i years, j months, k days, l hours, m minutes"
// -- for use in text such as "xxx ago" or "after xxx" or "in xxx"
$pgv_lang["elapsedYear1"]	=	"1 rok";
$pgv_lang["elapsedYear2"]	=	"#pgv_lang[global_num1]# lata";	// used in Polish for 2,3,4 or 22,23,24 or 32,33,34 etc.
$pgv_lang["elapsedYears"]	=	"#pgv_lang[global_num1]# lat";
$pgv_lang["elapsedMonth1"]	=	"1 miesiąc";
$pgv_lang["elapsedMonth2"]	=	"#pgv_lang[global_num1]# miesiące";	// used in Polish for 2,3,4 or 22,23,24 or 32,33,34 etc.
$pgv_lang["elapsedMonths"]	=	"#pgv_lang[global_num1]# miesięcy";
$pgv_lang["elapsedDay1"]	=	"1 dzień";
$pgv_lang["elapsedDay2"]	=	"#pgv_lang[global_num1]# dni";		// used in Polish for 2,3,4 or 22,23,24 or 32,33,34 etc.
$pgv_lang["elapsedDays"]	=	"#pgv_lang[global_num1]# dni";
$pgv_lang["elapsedHour1"]	=	"1 godzinę";
$pgv_lang["elapsedHour2"]	=	"#pgv_lang[global_num1]# godziny";	// used in Polish for 2,3,4 or 22,23,24 or 32,33,34 etc.
$pgv_lang["elapsedHours"]	=	"#pgv_lang[global_num1]# godzin";
$pgv_lang["elapsedMinute1"]	=	"1 minutę";
$pgv_lang["elapsedMinute2"]	=	"#pgv_lang[global_num1]# minuty";	// used in Polish for 2,3,4 or 22,23,24 or 32,33,34 etc.
$pgv_lang["elapsedMinutes"]	=	"#pgv_lang[global_num1]# minut";

$pgv_lang["elapsedAgo"]		=	"#pgv_lang[global_string1]# temu";

?>
