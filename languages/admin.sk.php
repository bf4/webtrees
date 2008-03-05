<?php
/**
 * Slovak Language file for PhpGedView.
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
 *
 * @author PGV Developers
 * @package PhpGedView
 * @subpackage Languages
 * @author Peter Moravčík
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Nemáte priamy prístup k súboru zo slovenčinou.";
	exit;
}

$pgv_lang["user"]			= "Autorizovaný uživateľ";
$pgv_lang["thumbnail_deleted"]		= "Súbor náhľadu bol úspešne zmazaný.";
$pgv_lang["thumbnail_not_deleted"]	= "Súbor náhľadu nemôže byť zmazaný.";
$pgv_lang["step2"]			= "Krok 2 z 4:";
$pgv_lang["refresh"]			= "Obnoviť";
$pgv_lang["move_file_success"]		= "Súbory medií a náhľadov boli úspešne presunuté.";
$pgv_lang["media_folder_corrupt"]	= "Adresár médií je vadný.";
$pgv_lang["media_file_not_deleted"]	= "Súbor médií nemôže byť zmazaný.";
$pgv_lang["gedcom_deleted"]		= "GEDCOM súbor [#GED#] bol úspešne zmazaný.";
$pgv_lang["gedadmin"]			= "Administrátor GEDCOMu";
$pgv_lang["full_name"]			= "Celé meno";
$pgv_lang["error_header"] 		= "Súbor GEDCOM, [#GEDCOM#], nie je na zadanom mieste.";
$pgv_lang["confirm_delete_file"]	= "Ste si istý, že chcete zmazať tento súbor?";
$pgv_lang["confirm_folder_delete"]      = "Ste si istý, že chcete zmazať tento adresár?";
$pgv_lang["confirm_remove_links"]	= "Ste si istý, že chcete odstrániť všetky spojenia pre tento objekt?";
$pgv_lang["PRIV_PUBLIC"]		= "Zobraziť verejne";
$pgv_lang["PRIV_USER"]			= "Zobraziť iba autorizovaným uživateľom";
$pgv_lang["PRIV_NONE"]			= "Zobraziť iba admin uživateľom";
$pgv_lang["PRIV_HIDE"]			= "Skryť i administrátorom";
$pgv_lang["manage_gedcoms"]		= "Správa GEDCOM súborov a úprava utajenia";
$pgv_lang["keep_media"]			= "Uchovať linky k médiám";
$pgv_lang["files_in_backup"]		= "Súbory uložené v tejto zálohe";
$pgv_lang["created_remotelinks"]	= "Tabuľka <i>Remotelinks</i> úspešne vytvorená.";
$pgv_lang["created_remotelinks_fail"] 	= "Nie je možné vytvoriť tabuľku <i>Remotelinks</i>.";
$pgv_lang["created_indis"]		= "Tabuľka <i>Osoby</i> bola úspešne vytvorená.";
$pgv_lang["created_indis_fail"]		= "Nie je možné vytvoriť tabuľku <i>Osoby</i>.";
$pgv_lang["created_fams"]		= "Tabuľka <i>Rodiny</i> bola úspešne vytvorená.";
$pgv_lang["created_fams_fail"]		= "Nie je možné vytvoriť tabuľku <i>Rodiny</i>.";
$pgv_lang["created_sources"]		= "Tabuľka <i>Pramene</i> bola úspešne vytvorená.";
$pgv_lang["created_sources_fail"]	= "Nie je možné vytvoriť tabuľku <i>Pramene</i>.";
$pgv_lang["created_other"]		= "Tabuľka <i>Ostatné</i> bola úspešne vytvorená.";
$pgv_lang["created_other_fail"]		= "Nie je možné vytvoriť tabuľku <i>Ostatné</i>.";
$pgv_lang["created_places"]		= "Tabuľka <i>Miesta</i> bola úspešne vytvorená.";
$pgv_lang["created_places_fail"]	= "Nie je možné vytvoriť tabuľku <i>Miesta</i>.";
$pgv_lang["created_placelinks"] 	= "Tabuľka <i>Prepojené Miesta</i> bola úspešne vytvorená.";
$pgv_lang["created_placelinks_fail"]	= "Nie je možné vytvoriť tabuľku <i>Prepojené Miesta</i>.";
$pgv_lang["created_media_fail"]		= "Nie je možné vytvoriť tabuľku <i>Médiá</i>.";
$pgv_lang["created_media_mapping_fail"]	= "Nie je možné vytvoriť tabuľku <i>Zobrazenie médií</i>.";
$pgv_lang["no_thumb_dir"]		= "adresár náhľadov neexistuje a nemohol byť založený.";
$pgv_lang["folder_created"]		= "Vytvorená zložka";
$pgv_lang["folder_no_create"]		= "Vytvoriť adresár nie je možné";
$pgv_lang["security_no_create"]		= "Bezpečnostné hlásenie: Súbor <b><i>index.php</i> neexistuje v";
$pgv_lang["security_not_exist"]		= "Bezpečnostné hlásenie: Súbor <b><i>index.php</i></b> nemohol byť vytvorený v";
$pgv_lang["label_add_search_server"]	= "Pridať IP";
$pgv_lang["label_add_server"]           = "Pridať";
$pgv_lang["label_ban_server"]		= "Postúpené";
$pgv_lang["label_delete"]               = "Zmazať";
$pgv_lang["progress_bars_info"]		= "Stavový riadok dolu vám zobrazuje stav Importu. Keď bude prekročený časový limit tak sa zastaví a vy budete vyzvaný ku stlačeniu tlačidla <b>Pokračovať</b>. V prípade že nevidíte tlačidlo <b>Pokračovať</b>, musíte reštartovať Import s menšou hodnotou časového limitu.";
$pgv_lang["upload_replacement"]		= "Zmena cesty načítania";
$pgv_lang["about_user"]			= "Najskôr musíte vytvoriť administrátorský účet. Administrátor bude mať právo aktualizovať konfiguračné súbory, prezerať si súkromné dáta a vytvárať ďalšie účty.";
$pgv_lang["access"]			= "Prístup";
$pgv_lang["add_gedcom"]			= "Pridať GEDCOM";
$pgv_lang["add_new_gedcom"]		= "Vytvoriť nový GEDCOM";
$pgv_lang["add_new_language"]		= "Pridať súbory a nastavenia pre nový jazyk";
$pgv_lang["add_user"]			= "Pridať nového užívateľa";
$pgv_lang["admin_gedcom"]		= "Spravovať GEDCOM";
$pgv_lang["admin_gedcoms"]		= "Pre administráciu GEDCOM súborov kliknite sem.";
$pgv_lang["admin_geds"]			= "Administrácia dát a GEDCOMU";
$pgv_lang["admin_info"]			= "Informačný blok";
$pgv_lang["admin_site"]			= "Administrácia stránky";
$pgv_lang["admin_user_warnings"]	= "Jeden, alebo viacero užívateľov má upozornenia";
$pgv_lang["admin_verification_waiting"] = "Užívateľské účty čakajúce na verifikáciu administrátorom";
$pgv_lang["administration"]		= "Administrácia";
$pgv_lang["ALLOW_CHANGE_GEDCOM"]	= "Povoliť prepínanie GEDCOMov";
$pgv_lang["ALLOW_REMEMBER_ME"]		= "Zobraziť voľbu <b>Uložiť v tomto počítači?</b> na prihlasovacej stránke.";
$pgv_lang["ALLOW_USER_THEMES"]		= "Umožniť uživateľom vybrať si vlastný motív";
$pgv_lang["ansi_encoding_detected"]	= "Rozoznané kódovanie ANSI. PhpGedView pracuje najlepšie zo súbormi s kódovaním UTF-8.";
$pgv_lang["ansi_to_utf8"]		= "Previesť kódovanie v tomto GEDCOM súbore z ANSI (ISO-8859-1) na UTF-8?";
$pgv_lang["apply_privacy"]		= "Použiť nastavenie utajenia?";
$pgv_lang["back_useradmin"]		= "Späť do administrácie uživateľov";
$pgv_lang["bytes_read"]			= "Načítané bajty:";
$pgv_lang["calc_marr_names"]		= "Odhadnúť mená po sobáši";
$pgv_lang["can_admin"]			= "Užívateľ môže administrovať";
$pgv_lang["can_edit"]			= "Úroveň prístupu";
$pgv_lang["change_id"]			= "Zmeniť ID osôb na:";
$pgv_lang["choose_priv"]		= "Vyberte úroveň utajenia:";
$pgv_lang["cleanup_places"]		= "Vyčistenie miest";
$pgv_lang["cleanup_users"]		= "Vyčistiť uživateľov";
$pgv_lang["click_here_to_continue"]	= "Kliknite sem pre pokračovanie.";
$pgv_lang["click_here_to_go_to_pedigree_tree"]	= "Kliknite sem pre vstup do rodokmeňa.";
$pgv_lang["comment"]			= "Poznámky admina k uživateľovi";
$pgv_lang["comment_exp"]		= "Varovania admina ku dňu";
$pgv_lang["config_help"]		= "Nápoveda konfigurácie";
$pgv_lang["config_still_writable"]	= "Do vašeho súboru <i>config.php</i> je ešte možné zapisovať. Ak ste už dokončili nastavovanie svojich stránok, mali by ste kvôli bezpečnosti zmeniť práva k tomuto súboru späť na \'len pre čítanie\'.";
$pgv_lang["configuration"]		= "Konfigurácia";
$pgv_lang["configure"]			= "Konfigurácia PhpGedView";
$pgv_lang["configure_head"]		= "Nastavenie PhpGedView";
$pgv_lang["confirm_gedcom_delete"]	= "Ste si istý, že chcete zmazať tento GEDCOM";
$pgv_lang["confirm_user_delete"]	= "Ste si istý, že chcete zmazať uživateľa";
$pgv_lang["create_user"]		= "Vytvoriť uživateľa";
$pgv_lang["current_users"]		= "Zoznam užívateľov";
$pgv_lang["daily"]			= "Deňne";
$pgv_lang["dataset_exists"]		= "GEDCOM súbor z týmto názvom už bol do databázy importovaný.";
$pgv_lang["date_registered"]		= "Dátum registrácie";
$pgv_lang["day_before_month"]		= "Deň pred mesiacom (DD MM YYYY)";
$pgv_lang["DEFAULT_GEDCOM"]		= "Implicitný GEDCOM";
$pgv_lang["default_user"]		= "Vytvoriť implicitného administrátora.";
$pgv_lang["del_gedrights"]		= "GEDCOM uz nie je aktívny, zrušte uživatelské odkazy.";
$pgv_lang["del_proceed"]		= "Pokračuj";
$pgv_lang["del_unvera"]			= "Užívateľ nebol overený administrátorom.";
$pgv_lang["del_unveru"]			= "Užívateľ nebol overený v priebehu 7 dní.";
$pgv_lang["do_not_change"]		= "Nemeniť";
$pgv_lang["download_file"]		= "Stiahnúť súbor";
$pgv_lang["download_gedcom"]		= "Stiahnúť GEDCOM";
$pgv_lang["download_here"]		= "Kliknite tu pre načítanie súboru.";
$pgv_lang["download_note"]		= "POZNÁMKA: Veľké GEDCOM súbory sa môžu sťahovať veľmi dlho. Ak PHP preruší proces ešte pred úplným stiahnutím súboru, stiahnutý GEDCOM nebude kompletný. Ak sa chcete uistiť, že je váš súbor celý, podívajte sa, či je na jeho konci riadok 0 TRLR. Stiahnutie súboru by malo trvať približne rovnako dlho ako jeho nahranie.";
$pgv_lang["editaccount"]		= "Umožniť tomuto uživateľovi upravovať informácie o svojom účte";
$pgv_lang["empty_dataset"]		= "Chcete vymazať staré dáta a nahradiť ich novými?";
$pgv_lang["empty_lines_detected"]	= "Vo vašom GEDCOM súbore boli nájdené prázdné riadky. Pri čistení budú tieto riadky odstránené.";
$pgv_lang["enable_disable_lang"]	= "Konfigurovať podporované jazyky";
$pgv_lang["error_ban_server"]       	= "Neplatná IP adresa.";
$pgv_lang["error_delete_person"]    	= "Musíte vybrať osobu ktorej vzdialený link chcete zmazať.";
$pgv_lang["error_header_write"]		= "Do súboru GEDCOM [#GEDCOM#] sa nedá zapisovať. Skontrolujte vlastnosti a prístupové práva súboru.";
$pgv_lang["error_siteauth_failed"]	= "Neúspešná autorizácia na vzdialenej stránke";
$pgv_lang["error_url_blank"]		= "Prosím nenechajte názov a URL vzdialenej stránky prázdny";
$pgv_lang["error_view_info"]        	= "Musíte vybrať osobu ktorej informácie chcete zobraziť.";
$pgv_lang["example_date"]		= "Ukážka neplatného dátového formátu z vášho GEDCOMu:";
$pgv_lang["example_place"]		= "Ukážka neplatného formátu miesta z vášho GEDCOMu:";
$pgv_lang["fbsql"]			= "FrontBase";
$pgv_lang["found_record"]		= "Nájdený záznam";
$pgv_lang["ged_download"]		= "Stiahnúť";
$pgv_lang["ged_import"]			= "Import";
$pgv_lang["ged_check"] 			= "Kontrola";
$pgv_lang["gedcom_adm_head"]		= "Administrácia GEDCOM";
$pgv_lang["gedcom_config_write_error"]	= "CH Y B A !!!<br />Nie je možné zapisovať do súboru <i>#GLOBALS[whichFile]#</i>. Prosím skontrolujte nastavenie prístupových práv.";
$pgv_lang["gedcom_downloadable"] 	= "Tento GEDCOM môže byť stiahnutý po internete!<br />Prosím prečítajte si odstavec o BEZPEČNOSTI v súbore <a href=\"readme.txt\">readme.txt</a> a zjednajte nápravu";
$pgv_lang["gedcom_file"]		= "Súbor GEDCOM:";
$pgv_lang["gedcom_not_imported"]	= "Tento GEDCOM ešte nebol importovaný.";
$pgv_lang["ibase"]			= "InterBase";
$pgv_lang["ifx"]			= "Informix";
$pgv_lang["img_admin_settings"]		= "Upraviť nastavenie nakladania z obrázkami";
$pgv_lang["import_complete"]		= "Importovanie je hotové";
$pgv_lang["import_marr_names"]		= "Import mien po sobáši";
$pgv_lang["import_options"]		= "Voľby importu.";
$pgv_lang["import_progress"]		= "Priebeh nahrávania...";
$pgv_lang["import_statistics"]		= "Štatistika importu";
$pgv_lang["import_time_exceeded"]	= "Časový limit pre spracovanie bol prekročený. Kliknite na tlačítko Pokračovať pre zobrazenie rekapitulácie importu GEDCOM súboru.";
$pgv_lang["inc_languages"]		= "Jazyky";
$pgv_lang["INDEX_DIRECTORY"]		= "Adresár pre index súbor";
$pgv_lang["invalid_dates"]		= "Rozoznané nesprávné formáty dátumu, vyčistením budú tieto formáty zmenené do podoby DD MMM YYYY (napr. 1 JAN 2005).";
$pgv_lang["BOM_detected"] 		= "Byte Order Mark (BOM) bol nájdený na začiatku súboru. Tento špeciálny kód bude odstránený.";
$pgv_lang["invalid_header"]		= "V GEDCOM súbore boli nájdené riadky pred hlavičkou GEDCOM <b>0&nbsp;HEAD</b>. Pri čistení súboru budú tieto riadky odstránené.";
$pgv_lang["label_added_servers"]	= "Pridané vzdialené servery";
$pgv_lang["label_banned_servers"]   	= "Zakázané stránky podle IP";
$pgv_lang["label_families"]         	= "Rodiny";
$pgv_lang["label_gedcom_id2"]       	= "GEDCOM ID:";
$pgv_lang["label_individuals"]      	= "Osoby";
$pgv_lang["label_manual_search_engines"]   = "Manuálne určite Search Engines pomocou IP";
$pgv_lang["label_new_server"]       	= "Pridať novú stránku";
$pgv_lang["label_password_id"]		= "Heslo";
$pgv_lang["label_remove_ip"]		= "Zakázaná IP adresa (napr. 198.128.*.*):";
$pgv_lang["label_remove_search"]	= "Označte IP adresy ako Search Engine Spiders:";
$pgv_lang["label_server_info"]      	= "Všetci sú vzdialene pripojený cez stránku:";
$pgv_lang["label_server_url"]       	= "URL/IP stránky";
$pgv_lang["label_username_id"]		= "Uživateľské meno";
$pgv_lang["label_view_local"]       	= "Zobraziť lokálne informácie o osobe";
$pgv_lang["label_view_remote"]      	= "Zobraziť vzdialené informácie o osobe";
$pgv_lang["LANG_SELECTION"] 		= "Podporované jazyky";
$pgv_lang["LANGUAGE_DEFAULT"]		= "Nemáte nakonfigurované jazyky ktoré má vaša stránka podporovať.<br />PhpGedView použije implicitné nastavenie.";
$pgv_lang["last_login"]			= "Posledné prihlásenie";
$pgv_lang["lasttab"]			= "Posledná návšteva záložky osoby";
$pgv_lang["leave_blank"]		= "Ponechajte pole heslo prázdne, ak chcte zachovať aktuálne heslo.";
$pgv_lang["link_manage_servers"]    	= "Správa stránok";
$pgv_lang["logfile_content"]		= "Obsah \"log\" súboru";
$pgv_lang["macfile_detected"]		= "Bol nájdený súbor pre Macintosh. Pri čistení bude tento súbor prevedený na súbor pre DOS.";
$pgv_lang["mailto"]			= "Mailto link";
$pgv_lang["merge_records"]		= "Zlúčiť záznamy";
$pgv_lang["message_to_all"]		= "Poslať správu všetkým užívateľom";
$pgv_lang["messaging"]			= "Interné správy PhpGedView";
$pgv_lang["messaging2"]			= "Interné správy s e-mailami";
$pgv_lang["messaging3"]			= "PhpGedView odosiela maily bez ukladania";
$pgv_lang["month_before_day"]		= "Mesiac pred dňom (MM DD YYYY)";
$pgv_lang["monthly"]			= "Mesačne";
$pgv_lang["msql"]			= "Mini SQL";
$pgv_lang["mssql"]			= "Microsoft SQL server";
$pgv_lang["mysql"]			= "MySQL";
$pgv_lang["mysqli"]			= "MySQL 4.1+ a PHP 5";
$pgv_lang["never"]			= "Nikdy";
$pgv_lang["no_logs"]			= "Zakázať prihlasovanie";
$pgv_lang["no_messaging"]		= "Bez kontaktnej metódy";
$pgv_lang["none"]			= "Žiadny";
$pgv_lang["oci8"]			= "Oracle 7+";
$pgv_lang["page_views"]			= "&nbsp;&nbsp;page views in&nbsp;&nbsp;";
$pgv_lang["performing_validation"]	= "Vykonávanie validácie (skontrolovanie) GEDCOMu, vyberte potrebné možnosti a kliknite na 'Pokračovať'";
$pgv_lang["pgsql"]			= "PostgreSQL";
$pgv_lang["pgv_config_write_error"] 	= "Chyba!!! Nie je možné zapisovať do konfiguračného súboru PhpGedView. Prosím skontrolujte oprávnenia pre súbor a adresár a skuste zápis znovu.";
$pgv_lang["PGV_MEMORY_LIMIT"]		= "Limit pamäte";
$pgv_lang["pgv_registry"]		= "Zobraziť iné weby používajúce PhpGedView";
$pgv_lang["PGV_SESSION_SAVE_PATH"]	= "Cesta pre ukladanie session";
$pgv_lang["PGV_SESSION_TIME"]		= "Session timeout";
$pgv_lang["PGV_SIMPLE_MAIL"] 		= "Použiť jednoduchú hlavičku pre externé maily";
$pgv_lang["PGV_STORE_MESSAGES"]		= "Povoliť on-line ukladanie správ";
$pgv_lang["phpinfo"]			= "PHPInfo";
$pgv_lang["place_cleanup_detected"]	= "Bolo rozoznané zlé kódovanie miest. Tieto chyby by mali byť opravené. Nasledujúci príklad ukazuje jedno z nesprávne zapísaných miest:";
$pgv_lang["privileges"]			= "Opravnenie";
$pgv_lang["please_be_patient"]		= "PROSÍM O STRPENIE";
$pgv_lang["reading_file"]		= "Načítanie súboru GEDCOM";
$pgv_lang["readme_documentation"]	= "README dokumentácia";
$pgv_lang["remove_ip"] 			= "Odstrániť IP";
$pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"] 	= "Požadovať schválenie registrácie nových uživateľov administrátorom";
$pgv_lang["review_readme"]		= "Prečítajte si súbor <a href=\"readme.txt\" target=\"_blank\">readme.txt</a> než budete pokračovať v konfigurácii PhpGedView.<br /><br />";
$pgv_lang["rootid"]			= "Východisková osoba vývodu";
$pgv_lang["seconds"]			= "&nbsp;&nbsp;sekundy";
$pgv_lang["select_an_option"]		= "Vyberte jednu z možností:";
$pgv_lang["SERVER_URL"]			= "PhpGedView URL";
$pgv_lang["show_phpinfo"]		= "Zobraziť informačnú stránku PHP";
$pgv_lang["siteadmin"]			= "Administrátor stránky";
$pgv_lang["skip_cleanup"]		= "Preskočiť opravovanie";
$pgv_lang["sqlite"]			= "SQLite";
$pgv_lang["sybase"]			= "Sybase";
$pgv_lang["sync_gedcom"]		= "Synchronizovať nastavenia užívateľa s GEDCOM údajmi";
$pgv_lang["system_time"]		= "Aktuálny čas servera:";
$pgv_lang["user_time"]			= "Aktuálny čas užívateľa:";
$pgv_lang["TBLPREFIX"]			= "Prefix databázovej tabuľky";
$pgv_lang["themecustomization"]		= "Prisposobiť tému";
$pgv_lang["time_limit"]			= "Časový limit:";
$pgv_lang["title_manage_servers"]   	= "Spravovať stránky";
$pgv_lang["title_view_conns"]       	= "Zobraziť spojenie.";
$pgv_lang["translator_tools"]		= "Nástroje prekladateľa";
$pgv_lang["update_myaccount"]		= "Aktualizovať môj účet";
$pgv_lang["update_user"]		= "Aktualizovať uživateľský účet";
$pgv_lang["upload_gedcom"]		= "Nahrať GEDCOM";
$pgv_lang["USE_REGISTRATION_MODULE"]	= "Povoliť návštevníkom požiadať o registráciu účtu";
$pgv_lang["user_auto_accept"]		= "Automaticky akceptovať zmeny urobené uživateľom";
$pgv_lang["user_contact_method"]	= "Uprednostňovaný spôsob kontaktu";
$pgv_lang["user_create_error"]		= "Nie je možné pridať uživateľa. Prosím vráťte sa späť a skúste to znovu.";
$pgv_lang["user_created"]		= "Uživateľ bol úspešne vytvorený.";
$pgv_lang["user_default_tab"]		= "Implicitné záložky k zobrazeniu na osobnej informačnej stránke";
$pgv_lang["user_path_length"]		= "Maximálna dĺžka cesty utajenia pre príbuzných";
$pgv_lang["user_relationship_priv"]	= "Limit prístupu k príbuzným osobám";
$pgv_lang["users_admin"]		= "Administrátori stránky";
$pgv_lang["users_gedadmin"]		= "GEDCOM administrátori";
$pgv_lang["users_total"]		= "Celkový počet užívateľov";
$pgv_lang["users_unver"]		= "Neverifikované užívateľom";
$pgv_lang["users_unver_admin"]		= "Neverifikované administrátorom";
$pgv_lang["usr_deleted"]		= "Zmazaný užívateľ:";
$pgv_lang["usr_idle"]			= "Počet mesiacov od posledného prihlásenia aby bol účet považovaný za neaktívny:";
$pgv_lang["usr_idle_toolong"]		= "Uživateľský účet bol neaktívny príliš dlho:";
$pgv_lang["usr_no_cleanup"]		= "Nenájdené nič pre vyčistenie";
$pgv_lang["usr_unset_gedcomid"]		= "Zrušiť GEDCOM ID pre";
$pgv_lang["usr_unset_rights"]		= "Zrušiť GEDCOM oprávnenia pre";
$pgv_lang["usr_unset_rootid"]		= "Zrušiť koreňové ID pre";
$pgv_lang["valid_gedcom"]		= "Validný GEDCOM. Žiadne opravy nebolo treba.";
$pgv_lang["validate_gedcom"]		= "Potvrdiť platnosť GEDCOMu";
$pgv_lang["verified"]			= "Uživateľ potvrdil registráciu";
$pgv_lang["verified_by_admin"]		= "Uživateľ bol adminom povolený";
$pgv_lang["verify_gedcom"]		= "Verifikácia GEDCOMu";
$pgv_lang["verify_upload_instructions"]	= "Ak zvolíte Pokračovať, bude starý GEDCOM súbor nahradený novým súborom, ktorý ste nahrali, a importovanie začne znovu. Ak zvolíte Zrušiť, zostane starý súbor zachovaný.";
$pgv_lang["view_changelog"]		= "Zobraziť changelog.txt súbor";
$pgv_lang["view_logs"]			= "Zobraziť logfiles";
$pgv_lang["view_readme"]		= "Zobraziť súbor readme.txt";
$pgv_lang["visibleonline"]		= "Viditeľný pre iných uživateľov, keď je online";
$pgv_lang["visitor"]			= "Návštevník";
$pgv_lang["warn_users"]			= "Užívatelia s upozorneniami";
$pgv_lang["weekly"]			= "Týždenne";
$pgv_lang["welcome_new"]		= "Vitajte na vašich nových PhpGedView web stránkách.";
$pgv_lang["yearly"]			= "Ročne";
$pgv_lang["admin_OK_subject"]		= "Schválenie účtu pre #SERVER_NAME#";
$pgv_lang["admin_OK_message"]		= "Administrátor stránky PhpGedView #SERVER_NAME# schválil vašu žiadosť o zriadenie účtu. Možete sa teraz prihlásiť pomocou linku:\r\n\r\n#SERVER_NAME#\r\n";
$pgv_lang["gedcheck"]     		= "GEDCOM kontrolor";
$pgv_lang["gedcheck_text"]		= "Tento modul kontroluje formát súboru GEDCOM podľa <a href=\"http://phpgedview.sourceforge.net/ged551-5.pdf\">5.5.1 GEDCOM Specification</a>. Tiež kontroluje obvyklé chyby vo vašich údajoch. Note that there are lots of versions, extensions and variations on the specification so you should not be concerned with any issues other than those flagged as \"Critical\". The explanation for all the line-by-line errors can be found in the specification, so please check there before asking for help.";
$pgv_lang["level"]        		= "Úroveň";
$pgv_lang["critical"]     		= "Kritický";
$pgv_lang["error"]        		= "Chyba";
$pgv_lang["warning"]      		= "Upozornenie";
$pgv_lang["info"]         		= "Informácia";
$pgv_lang["open_link"]    		= "Otvoriť link v";
$pgv_lang["same_win"]     		= "Tá istá tabuľka/okno";
$pgv_lang["new_win"]      		= "Nová tabuľka/okno";
$pgv_lang["context_lines"]		= "Riadky obsahu GEDCOMu";
$pgv_lang["all_rec"]      		= "Všetky záznamy";
$pgv_lang["err_rec"]      		= "Záznamy s chybami";
$pgv_lang["missing"]      		= "chýbajúci";
$pgv_lang["multiple"]     		= "viacnásobný";
$pgv_lang["invalid"]      		= "neplatný";
$pgv_lang["too_many"]     		= "príliš veľa";
$pgv_lang["too_few"]      		= "príliš málo";
$pgv_lang["no_link"]      		= "nie je link späť";
$pgv_lang["data"]         		= "údaje";
$pgv_lang["see"]          		= "pozri";
$pgv_lang["noref"]        		= "Žiadne referencie na tento záznam";
$pgv_lang["tag"]          		= "tag";
$pgv_lang["spacing"]      		= "odstup";
$pgv_lang["ADVANCED_NAME_FACTS"] 	= "Rozšírené meno udalosti";
$pgv_lang["ADVANCED_PLAC_FACTS"] 	= "Rozšírené meno miesta udalosti";
$pgv_lang["SURNAME_TRADITION"] 		= "Tradícia priezviska";
$pgv_lang["spanish"]           		= "Španielsky";
$pgv_lang["portuguese"]        		= "Potugalsky";
$pgv_lang["icelandic"]         		= "Islandsky";
$pgv_lang["paternal"]          		= "Po otcovi";


$pgv_lang["sanity_err0"]		= "Chyby:";
$pgv_lang["sanity_err1"]		= "Potrebujete PHP verziu 4.3 alebo vyššiu.";
$pgv_lang["sanity_err2"]		= "Súbor alebo adresár <i>#GLOBALS[whichFile]#</i> neexistuje. Prosím skontrolujte existenciu súboru alebo adresára, jeho meno a nastavenie pristupových práv.";
$pgv_lang["sanity_err3"]		= "Súbor <i>#GLOBALS[whichFile]#</i> nebol načítaný korektne. Skúste ho načítať opakovane.";
$pgv_lang["sanity_err4"]		= "Súbor <i>config.php</i> je poškodený.";
$pgv_lang["sanity_err5"]		= "Súbor <i>config.php</i> je chránený proti zápisu.";
$pgv_lang["sanity_err6"]		= "Adresár<i>#GLOBALS[INDEX_DIRECTORY]#</i> je chránený proti zápisu.";
$pgv_lang["sanity_warn0"]		= "Varovania:";
$pgv_lang["sanity_warn1"]		= "Adresár <i>#GLOBALS[MEDIA_DIRECTORY]#</i> je chránený proti zápisu. Nebudete môcť načítať média a generovať náhľady v PhpGedView.";
$pgv_lang["sanity_warn2"]		= "Adresár <i>#GLOBALS[MEDIA_DIRECTORY]#</i> je chránený proti zápisu. Nebudete môcť načítať náhľady alebo generovať náhľady v PhpGedView.";
$pgv_lang["sanity_warn3"]		= "Knihovňa GD imaging neexistuje. PhpGedView bude nadalej funkčný, ale niektoré funkcie, ako napríklad generovanie rnáhľadov a kruhové diagramy nebudú funkčné bez GD knihovne. Prosím podívajte sa na <a href=\'http://www.php.net/manual/en/ref.image.php\'>http://www.php.net/manual/en/ref.image.php</a> pre dalšie informácie.";
$pgv_lang["sanity_warn4"]		= "Knihovňa XML Parser neexistuje. PhpGedView bude nadalej funkčný, ale niektoré funkcie, ako napríklad generovanie reportov a WEB služby nebudú funkčné bez XML Parser knihovne. Prosím podívajte sa na <a href=\'http://www.php.net/manual/en/ref.xml.php\'>http://www.php.net/manual/en/ref.xml.php</a> pre dalšie informácie.";
$pgv_lang["sanity_warn5"]		= "Knihovňa DOM XML neexistuje. PhpGedView bude nadalej funkčný, ale niektoré funkcie, ako napríklad Export do Výstrižkov, download a WEB služby nebudú funkčné. Prosím podívajte sa na <a href=\'http://www.php.net/manual/en/ref.domxml.php\'>http://www.php.net/manual/en/ref.domxml.php</a> pre dalšie informácie.";
$pgv_lang["sanity_warn6"]		= "Knihovňa Calendar neexistuje. PhpGedView bude nadalej funkčný, ale niektoré funkcie, ako napríklad konverzia na iný kalendár (Zidovský, Francúzsky...), nebude funkčná. Toto nieje podstatné pre spustenie PhpGedView. Prosím podívajte sa na <a href=\'http://www.php.net/manual/en/ref.calendar.php\'>http://www.php.net/manual/en/ref.calendar.php</a> pre dalšie informácie.";
$pgv_lang["ip_address"]			= "IP adresa";
$pgv_lang["date_time"]			= "Dátum a čas";
$pgv_lang["log_message"]		= "Log";
$pgv_lang["searchtype"]			= "Typ hľadania";
$pgv_lang["query"]			= "Otázka";
$pgv_lang["clear_cache_succes"]		= "Cache súbory budú odstránené.";
$pgv_lang["clear_cache"]		= "Zmazať cache súbory";
$pgv_lang["deleted_files"]           	= "Zmazané súbory:";
$pgv_lang["index_dir_cleanup_inst"]	= "Ak chcete zmazať súbor alebo podadresár z adresára Index, pretiahniťe ho do odpadkového koša, alebo ho označte pomocou checkboxu. Kliknite na tlačidlo Zmazať pre definitívne zmazanie označených súborov.<br /><br />Súbory označené <img src=\"./images/RESN_confidential.gif\" /> sú potrebné pre riadne procesy a nemôžu byť zmazané.<br />Súbory označené <img src=\"./images/RESN_locked.gif\" /> obsahujú dôležité nastavenia, alebo čakajúce zmeny a môžu byť zmazané iba ak ste si istý, že viete čo robíte.<br /><br />";
$pgv_lang["index_dir_cleanup"]		= "Vyčistiť zložku Index";
$pgv_lang["warn_file_delete"]		= "Tento súbor obsahuje dôležité údaje, ako je nastavenie jazyka, alebo čakajúce zmeny. Ste si istý, že ho chcete zmazať?";
$pgv_lang["associated_files"]		= "Asociované súbory:";
$pgv_lang["remove_all_files"]		= "Odstrániť všetky nepodstatné súbory";
?>
