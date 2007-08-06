<?php
/**
 * phpGedView Research Assistant Tool - Form Loader Engine.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * Language author: Ixen Gerthannes
 *
 * @package PhpGedView
 * @subpackage Research_Assistant
 * @version $Id$
 * @author Jason Porter
 * @author Wade Lasson
 * @author Brandon Gagnon
 * @author Brian Kramer
 * @author Julian Gautier
 * @author Hector Pena
 */

//-- security check, only allow access from module.php
if (preg_match("/ra_lang\...\.php$/", $_SERVER["PHP_SELF"])>0) {
	print "Nie można uzyskać bezpośredniego dostępu do pliku.";
	exit;
}
$pgv_lang["missing_info"] 		= "Brakujące informacje";
$temp_out_autosearch = 	"Ta opcja spowoduje automatyczne przeszukanie baz Ancestry i FamilySearch, ";
$temp_out_autosearch .= "możesz wybrać poszukiwania po nazwisku i dacie narodzin/śmierci <br />";
$pgv_lang["auto_search"]		= $temp_out_autosearch;
$pgv_lang["auto_search_text"]	= "Automatyczne wyszukiwanie";
$pgv_lang["task_list"]			= "Zadania";
$pgv_lang["task_list_text"]		= "Ten obszar wyświetla zadania które utworzyłeś, kliknij Pokaż aby zobaczyć dane zadanie";

// -- HELP COMMENTS
$temp_out_comments = "W tej sekcji można dodawać komentarze dotyczące danej osoby aby mogli je obejrzeć oraz wypowiedzieć się na ich temat inni";
$pgv_lang["help_comments"] = $temp_out_comments;

// -- MENU ITEM MESSAGES
$pgv_lang["my_tasks"]							= "Moje zadania";
$pgv_lang["add_task"]							= "Dodaj zadanie";
$pgv_lang["view_folders"]						= "Pokaż foldery";
$pgv_lang["view_probabilities"]					= "Pokaż prawdopodobieństwa";
$pgv_lang["up_folder"]							= "Folder wyżej";
$pgv_lang["edit_folder"]						= "Dodaj/edytuj folder";
$pgv_lang["gen_tasks"]							= "Automatycznie generuj zadania";


// -- RA GENERAL MESSAGES
$pgv_lang["delete"]								= "Usuń";
$pgv_lang["edit_task"]							= "Edytuj zadanie";
$pgv_lang["view"]								= "Pokaż";
$pgv_lang["name"]								= "Nazwa";
$pgv_lang["folder"]								= "Folder";
$pgv_lang["completed"]							= "Zakończono";
$pgv_lang["comres"]								= "Komentarze/Rezultaty";
$pgv_lang["description"]						= "Opis";
$pgv_lang["created"]							= "Utworzono";
$pgv_lang["modified"]							= "Zmodyfikowano";
$pgv_lang["folder_list"]						= "Lista folderów";
$pgv_lang["details"]							= "Szczegóły";
$pgv_lang["result"]                     		= "Rezultaty";
$pgv_lang["okay"]                               = "Ok";

// -- RA_FOLDER MESSAGES
$pgv_lang["Edit_Task"]                 			= "Edytuj zadanie";
$pgv_lang["End_Date"]                 			= "Data końcowa";
$pgv_lang["Start_Date"]                 		= "Data początkowa";
$pgv_lang["Task_Name"]                			= "Nazwa zadania";
$pgv_lang["Folder_Name"]                		= "Nazwa folderu";
$pgv_lang["Folder_View"]                		= "Pokaż folder";
$pgv_lang["Task_View"]                  		= "Pokaż zadanie";
$pgv_lang["page_header"]						= "Foldery asystenta badań";
$pgv_lang["folder_new"]							= "Utwórz nowy folder";
$pgv_lang["folder_delete_check"]				= "Czy na pewno chcesz usunąć ten folder?";
$pgv_lang["no_folder_name"]             		= "Pole nazwy folderu musi być wypełnione.";
$pgv_lang["add_folder"]                 		= "Dodaj folder";
$pgv_lang["edit_folder"]                		= "Edytuj folder";
$pgv_lang["folder_name"]                		= "Nazwa folderu:";
$pgv_lang["Parent_Folder:"]             		= "Folder nadrzędny:";
$pgv_lang["No_Parent"]                  		= "Brak nadrzędnego";
$pgv_lang["Folder_Description:"]        		= "Opis folderu:";
$pgv_lang["Folder_names_must_be_unique"]		= "Nazwy folderów muszą być unikatowe.";
$pgv_lang["folder_submitted"]          			= "Twój folder został wysłany"; 
$pgv_lang["folder_problem"]             		= "Wystąpił problem podczas dodawania twojego folderu, spróbuj jeszcze raz";

// -- Missing Information Help 
$temp_out_missinginfo = "Ten obszar wyświetla brakujące informacje o rekordzie.";
$temp_out_missinginfo .= "Wybierz element i folder po czym naciśnij przycisk Dodaj zadanie aby utworzyć zadanie dla brakującego elementu.";  
$temp_out_missinginfo .= "Już utworzone zadania będą wyświetlone ze znacznikiem 'Pokaż' zamiast opcji zaznaczenia <br />";
$temp_out_missinginfo .= " <a href=\"javascript:void(0);\" onClick=\"fullScreen('helpvids/MissingInformationUserHelp.htm');\">Naciśnij tutaj aby otworzyć podręcznik na pełnym ekranie</a>";
$pgv_lang["ra_missing_info_help"] = $temp_out_missinginfo;

// -- RA_EDITFOLDER MESSAGES	
$pgv_lang["edit_research_folder"]			= "Edytuj folder badań";
$pgv_lang["folder_not_exist"]				= "Poniższy folder nie istnieje: ";
$pgv_lang["folder_parent"]					= "Folder nadrzędny";
$pgv_lang["parent_id"]						= "Brak";
$pgv_lang["folder_users"]					= "Inni użytkownicy mogący oglądać ten folder";

// -- RA_EDITLOG MESSAGES
$pgv_lang["edit_research_log"]				= "Edytuj dziennik badań";
$pgv_lang["log_not_exist"]					= "Poniższy dziennik nie istnieje: ";

// -- RA_LOG MESSAGES
$pgv_lang["edit_log_entry"]					= "Edytuj wpis w dzienniku badań";
$pgv_lang["log_no_entry"]					= "BŁĄD: Nie masz praw dostępu do tego elementu.";
$pgv_lang["log_modified"]					= "Ostatnio zmodyfikowane";
$pgv_lang["log_modified_by"]				= "Ostatnio zmodyfikowane przez";
$pgv_lang["log_edit_entry"]					= "Edytuj ten wpis";

// -- RA_LISTLOGS MESSAGES
$pgv_lang["research_logs"]					= "Dzienniki badań";
$pgv_lang["log_no_entry_folder"]			= "BŁĄD: Nie masz praw dostępu do tego folderu.";
$pgv_lang["folder_sub"]						= "Foldery podrzędne";
$pgv_lang["folder_sub_new"]					= "Utwórz folder podrzędny";
$pgv_lang["task_entry"]						= "Utwórz nowe zadanie.";
$pgv_lang["log_show"]						= "Pokaż wszystkie dzienniki";
$pgv_lang["log_show_uncomplete"]			= "Pokaż niekompletne dzienniki";
$pgv_lang["log_show_complete"]				= "Pokaż kompletne dzienniki";
$pgv_lang["log_delete_check"]				= "Czy na pewno chcesz usunąć ten wpis z dziennika?";

// -- RA_FUNCTIONS MESSAGES
$pgv_lang["function_folder_delete"]			= "BŁĄD: Nie można usunąć tego folderu ponieważ wciąż zawiera w sobie wpisy.<br />Najpierw usuń wszystkie wpisy z tego dziennika po czym usuń folder.";
$pgv_lang["function_subfolder_delete"]		= "BŁĄD: Nie można usunąć tego folderu ponieważ zawiera foldery podrzędne.<br />Najpierw przenieś lub usuń foldery podrzędne, po czym usuń ten folder raz jeszcze.";
$pgv_lang["folder_delete_ok"]				= "Folder #folder_name# został usunięty.";
$pgv_lang["folder_update_ok"]				= "Folder #folder_name# został pomyślnie zaktualizowany.";
$_SESSION['pgv_lang["keywords"]']			= "Słowa kluczowe:";
$pgv_lang["folder_added"]					= "Folder #folder_name# został pomyślnie dodany.";
$_SESSION['pgv_lang["search"]']				= "Szukaj";

//-- RA_SEARCH MESSAGES
$pgv_lang["search_results"]					= "Rezultaty wyszukiwania";
$pgv_lang["nothing_found"]					= "Nie znaleziono pasujących wpisów.";

//-- ERROR MESSAGES
$pgv_lang["no_folder"]						= "Nie istnieje aktualnie żaden folder. Najpierw utwórz folder.";

//-- HELP MESSAGES
$pgv_lang["help_rs_folders.php"]			= "Foldery asystenta badań<br /> #pgv_lang[sorry]#";
$pgv_lang["help_rs_editfolder.php"]			= "Edytuj foldery asystenta badań<br />#pgv_lang[sorry]#";
$pgv_lang["help_rs_editlog.php"]			= "Edytuj dziennik asystenta badań<br />#pgv_lang[sorry]#";
$pgv_lang["ra_fold_name_help"]				= "<H2><B>Widok folderu:</B></H2><ul><li><B>Nazwa folderu:</B> Kolumna zawiera nazwy wszystkich istniejących folderów.</li><li><B>Opis:</B> Kolumna zawiera opisy folderów.</li></ul><br /><br /><a href=\"helpvids/ResearchAssistantUserHelp.htm\">Podręcznik asystenta badań</a>";
$pgv_lang["ra_add_task_help"]				= "<H2><B>Dodaj zadanie:</B></H2></H2><ul><li><B>Nazwa zadania:</B>To pole powinno zawierać nazwę aktualnie dodawanego zadania.</li><li><B>Folder:</B>W tym polu możesz określić folder do którego zostanie dodane zadanie.</li><li><B>Opis:</B> Wpisz opis zadania które chesz dodać.</li><li><B>Źródła:</B> Skojarz źródła z tym zadaniem.</li><li><B>Osoby:</B> Skojarz osoby z tym zadaniem.</li></ul>";
$pgv_lang["ra_edit_folder_help"]			= "<H2><B>Edytuj folder:</B></H2><ul><li><B>Nazwa folderu:</B> Tutaj powinieneś umieścić nazwę folderu który edytujesz.</B></li><li><B>Folder nadrzędny:</B> Tu możesz przypisać folder nadrzędny do aktualnie edytowanego folderu.</B></li><li><B>Opis folderu:</B> To jest opis folderu który edytujesz.</B></li><ul>";
$pgv_lang["ra_add_folder_help"]				= "<H2><B>Dodaj folder:</B></H2><ul><li><B>Nazwa folderu:</B> To pole powinno zawierać nazwę aktualnie dodawanego zadania.</B></li><li><B>Folder nadrzędny:</B> Tu możesz przypisać folder nadrzędny do aktualnie dodawanego folderu.</B></li><li><B>Opis folderu:</B> To jest opis folderu który dodajesz.</B></li><ul>";
$pgv_lang["ra_view_task_help"]				= "<H2><B>Widok zadań:</B></H2><ul><li><B>Nazwa zadania:</B> Ta kolumna zawiera nazwy wszystkich zadań.</B></li><li><B>Opis:</B> Ta kolumna zawiera opisy zadań.</li><li><B>Data początkowa:</B> Ta kolumna zawiera daty początkowe każdego z zadań.</li><li><B>Zakończone:</B>Kolumna pokazuje czy dane zadanie zostało zakończone.</li><li><B>Szczegóły:</B>Kolumna pokazuje szczegóły dotyczące każdego z zadań.</li><li><B>Usuń:</B>Opcja usunie zadanie.</li><ul><br /><a href=\"helpvids/MissingInformationUserHelp.htm\">Podręcznik użytkownika</a>";
$pgv_lang["ra_task_view_help"]				= "<H2><B>Pokaż zadanie:</B></H2><ul><li><B>Nazwa:</B>Pole powinno zawierać nazwę zadania.</li><li><B>Osoby:</B> Skojarz osoby z tym zadaniem.</li><li><B>Opis:</B> Podaj opis dodawanego zadania.</li><li><B>Źródła:</B> Skojarz źródła z tym zadaniem.</li><li>Naciśnij przycisk 'Edytuj zadanie' aby zmienić szczegóły z nim związane.</li></ul>";
$pgv_lang["ra_comments_help"]				= "<H2><B>Komentarze:</B></H2><ul><li>Tu będą widoczne komentarze związane z tym zadaniem. Naciśnij przycisk 'Dodaj komentarz' aby dodać komentarz.</li></ul>";
$pgv_lang["ra_GenerateTasks_help"]			= "<H2><B>Generuj zadania:</B></H2><p>Ten formularz generuje zadania z tagów _TODO zawartych w twoim pliku GEDCOM.</p><ul><li><B>Generuj:</B> zaznacz każde zadanie do wygenerowania w momencie naciśnięcia przycisku 'Generuj'.</li><li><B>Nazwa zadania:</B> To jest nazwa która będzie nadana zadaniu.  Domyślnie jest to tekst zawarty w tagu _TODO, wyłączając tagi CONT</li><li><B>Opis zadania:</B> Opis jaki będzie nadany zadaniu.  Jest on generowany na podstawie tekstu zawartego w tagu _TODO oraz wszystkich związanych tagów CONT.  </li><li><B>Edytuj:</B> kliknij łącze aby edytować zadanie.</li><li><B>Wybierz folder:</B> wybierz folder w którym będą umieszczane wygenerowane zadania.</li><li><B>Generuj:</B> generuje zadania które zostały zaznaczone.</li><li><B>Zakończ:</B> przekierowuje cię do strony z podglądem folderów.</li></ul>";
$pgv_lang["ra_EditGenerateTasks_help"]		= "<H2><B>Edytuj wygenerowane zadanie:</B></H2><p>Ten formularz pozwala na edycję zadań wygenerowanych z tagów _TODO zawartych w pliku GEDCOM.</p><ul><li><B>Nazwa zadania:</B> To jest nazwa zadania.  </li><li><B>Opis zadania:</B> To jest opis zadania. </li><li><B>Osoby:</B> kliknij łącze aby wybrać osobę do skojarzenia z tym zadaniem.</li><li><B>Źródła:</B> kliknij łącze aby wybrać źródło do skojarzenia z tym zadaniem.</li><li><B>Zapisz:</B> zapisuje wszystkie zmiany i przekierowuje do strony generowania zadań.</li><li><B>Anuluj:</B> odrzuca wszystkie wprowadzone zmiany i przekierowuje do strony generowania zadań.</li></ul>";
$pgv_lang["ra_configure_privacy_help"]		= "<H2><B>Konfiguruj opcje prywatności:</B></H2></H2><ul><li><B>Pokaż wszystkim:</B> Pozwala każdemu oglądać to zadanie.</li><li><B>Pokaż tylko użytkownikom zalogowanym:</B> Pokazuje to zadanie tylko zalogowanym użytkownikom.</li><li><B> Pokaż tylko administratorom:</B> Pokazuje zadanie tylko administratorom.</li><li><B> Ukryj nawet przed administratorami:</B> Nie pokazuje tego zadania nikomu.</li></ul>";

//-- RA_VIEWTASK MESSAGES
$pgv_lang["view_task"]						= "Pokaż zadanie";
$pgv_lang["comments"]						= "Komentarze";
$pgv_lang["add_new_comment"]				= "Dodaj komentarz";
$pgv_lang["no_sources"]						= "Nie ma źródeł skojarzonych z tym zadaniem.";
$pgv_lang["no_people"]						= "Nie ma osób skojarzonych z tym zadaniem.";
$pgv_lang["no_indi_tasks"]					= "Nie ma zadań skojarzonych z tą osobą.";
$pgv_lang["edit_comment"]					= "Edytuj komentarz";
$pgv_lang["comment_success"]				= "Komentarz został dodany pomyślnie.";
$pgv_lang['comment_body']					= 'Komentarz';

//-- RA_COMMENT MESSAGES
$pgv_lang["comment_delete_check"]		= "Czy na pewno chcesz usunąć ten komentarz?";

//-- RA_ADDTASK MESSAGES
$pgv_lang["add_new_task"]				= "Dodaj zadanie";
$pgv_lang["title"]						= "Nazwa";
$pgv_lang["submit"]						= "Wyślij";

//-- RA_EDITTASK MESSAGES
$pgv_lang["edit_task"]					= "Edytuj zadanie";

//-- RA_CONFIGURE PRIVACY MESSAGES
$pgv_lang["configure_privacy"]		    = "Konfiguruj opcje prywatności";
$pgv_lang["show_my_tasks"]              = "Pokaż 'Moje zadania'";
$pgv_lang["show_add_task"]		        = "Pokaż 'Dodaj zadanie'";
$pgv_lang["show_auto_gen_task"]         = "Pokaż 'Automatyczne generowanie zadań'";
$pgv_lang["show_view_folders"]		    = "Pokaż 'Widok folderów'";
$pgv_lang["show_add_folder"]		    = "Pokaż 'Dodaj folder'";
$pgv_lang["show_add_unlinked_source"]   = "Pokaż 'Dodaj niepowiązane źródło'";
$pgv_lang["show_view_probabilities"]	= "Pokaż 'Widok prawdopodobieństw'";




//-- COMMENT HELP
$pgv_lang["comment_title_help"]			= "Tytuł komentarza.";
$pgv_lang["comment_help"]				= "Naciśnij tutaj aby uzyskać pomoc.";

//-- Census Forms
$pgv_lang["rows"]                       = "Liczba wierszy";
$pgv_lang["state"]                      = "Stan";
$pgv_lang["call/url"]                   = "Numer telefonu/URL";
$pgv_lang["enumDate"]                   = "Data enumeracji";
$pgv_lang["county"]                     = "Hrabstwo";
$pgv_lang["city"]                       = "Miasto";
$pgv_lang["page"]                       = "Strona";                  
?>
