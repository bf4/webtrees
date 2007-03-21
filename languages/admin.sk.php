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
$pgv_lang["confirm_folder_delete"] = "Ste si istý, že chcete zmazať tento adresár?";
$pgv_lang["confirm_remove_links"]	= "Ste si istý, že chcete odstrániť všetky spojenia pre tento objekt?";
$pgv_lang["manage_gedcoms"]		= "Správa GEDCOM súborov a úprava utajenia";
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
$pgv_lang["move_to"]			= "Presunúť do -->";
$pgv_lang["folder_created"]		= "Vytvorená zložka";
$pgv_lang["folder_no_create"]		= "Vytvoriť adresár nie je možné";
$pgv_lang["security_no_create"]		= "Bezpečnostné hlásenie: Súbor <b><i>index.php</i> neexistuje v";
$pgv_lang["security_not_exist"]		= "Bezpečnostné hlásenie: Súbor <b><i>index.php</i></b> nemohol byť vytvorený v";
$pgv_lang["label_add_search_server"]	= "Pridať IP";
$pgv_lang["label_add_server"]       = "Pridať";
$pgv_lang["label_ban_server"]		= "Postúpené";
$pgv_lang["label_delete"]           = "Zmazať";
$pgv_lang["upload_replacement"]		  	="Zmena cesty načítania";
$pgv_lang["add_gedcom"]			= "Pridať GEDCOM";
$pgv_lang["add_new_gedcom"]		= "Vytvoriť nový GEDCOM";
$pgv_lang["admin_approved"]		= "Váš účet na #SERVER_NAME# bol povolený";
$pgv_lang["admin_gedcom"]		= "Spravovať GEDCOM";
$pgv_lang["admin_geds"]			= "Administrácia dát a GEDCOMU";
$pgv_lang["admin_info"]			= "Informačný blok";
$pgv_lang["admin_site"]			= "Administrácia stránky";
$pgv_lang["administration"]		= "Administrácia";
$pgv_lang["ansi_encoding_detected"]	= "Rozoznané kódovanie ANSI. PhpGedView pracuje najlepšie zo súbormi s kódovaním UTF-8.";
$pgv_lang["ansi_to_utf8"]		= "Previesť kódovanie v tomto GEDCOM súbore z ANSI (ISO-8859-1) na UTF-8?";
$pgv_lang["apply_privacy"]		= "Použiť nastavenie utajenia?";
$pgv_lang["bytes_read"]			= "Načítané bajty:";
$pgv_lang["calc_marr_names"]		= "Odhadnúť mená po sobáši";
$pgv_lang["change_id"]			= "Zmeniť ID osôb na:";
$pgv_lang["choose_priv"]		= "Vyberte úroveň utajenia:";
$pgv_lang["cleanup_places"]		= "Vyčistenie miest";
$pgv_lang["click_here_to_go_to_pedigree_tree"]	= "Kliknite sem pre vstup do rodokmeňa.";
$pgv_lang["comment"]			= "Poznámky admina k uživateľovi";
$pgv_lang["comment_exp"]		= "Varovania admina ku dňu";
$pgv_lang["configuration"]		= "Konfigurácia";
$pgv_lang["confirm_user_delete"]	= "Ste si istý, že chcete zmazať uživateľa";
$pgv_lang["create_user"]		= "Vytvoriť uživateľa";
$pgv_lang["dataset_exists"]		= "GEDCOM súbor z týmto názvom už bol do databázy importovaný.";
$pgv_lang["day_before_month"]		= "Deň pred mesiacom (DD MM YYYY)";
$pgv_lang["do_not_change"]		= "Nemeniť";
$pgv_lang["download_gedcom"]		= "Stiahnúť GEDCOM";
$pgv_lang["download_note"]		= "POZNÁMKA: Veľké GEDCOM súbory sa môžu sťahovať veľmi dlho. Ak PHP preruší proces ešte pred úplným stiahnutím súboru, stiahnutý GEDCOM nebude kompletný. Ak sa chcete uistiť, že je váš súbor celý, podívajte sa, či je na jeho konci riadok 0 TRLR. Stiahnutie súboru by malo trvať približne rovnako dlho ako jeho nahranie.";
$pgv_lang["duplicate_username"]		= "Toto uživateľské meno už existuje. Prosím, vráťte sa späť a vyberte iné uživateľské meno.";
$pgv_lang["editaccount"]		= "Umožniť tomuto uživateľovi upravovať informácie o svojom účte";
$pgv_lang["empty_dataset"]		= "Chcete vymazať staré dáta a nahradiť ich novými?";
$pgv_lang["empty_lines_detected"]	= "Vo vašom GEDCOM súbore boli nájdené prázdné riadky. Pri čistení budú tieto riadky odstránené.";
$pgv_lang["error_ban_server"]       = "Neplatná IP adresa.";
$pgv_lang["error_delete_person"]    = "Musíte vybrať osobu ktorej vzdialený link chcete zmazať.";
$pgv_lang["error_header_write"]		= "Do súboru GEDCOM [#GEDCOM#] sa nedá zapisovať. Skontrolujte vlastnosti a prístupové práva súboru.";
$pgv_lang["error_siteauth_failed"]	= "Neúspešná autorizácia na vzdialenej stránke";
$pgv_lang["error_url_blank"]		= "Prosím nenechajte názov a URL vzdialenej stránky prázdny";
$pgv_lang["error_view_info"]        = "Musíte vybrať osobu ktorej informácie chcete zobraziť.";
$pgv_lang["example_date"]		= "Ukážka neplatného dátového formátu z vášho GEDCOMu:";
$pgv_lang["example_place"]		= "Ukážka neplatného formátu miesta z vášho GEDCOMu:";
$pgv_lang["found_record"]		= "Nájdený záznam";
$pgv_lang["ged_import"]			= "Import";
$pgv_lang["gedcom_downloadable"] 	= "Tento GEDCOM môže byť stiahnutý po internete!<br />Prosím prečítajte si odstavec o BEZPEČNOSTI v súbore <a href=\"readme.txt\">readme.txt</a> a zjednajte nápravu";
$pgv_lang["gedcom_file"]		= "Súbor GEDCOM:";
$pgv_lang["img_admin_settings"]		= "Upraviť nastavenie nakladania z obrázkami";
$pgv_lang["import_complete"]		= "Importovanie je hotové";
$pgv_lang["import_marr_names"]		= "Import mien po sobáši";
$pgv_lang["import_options"]		= "Voľby importu.";
$pgv_lang["import_progress"]		= "Priebeh nahrávania...";
$pgv_lang["import_statistics"]		= "Štatistika importu";
$pgv_lang["import_time_exceeded"]	= "Časový limit pre spracovanie bol prekročený. Kliknite na tlačítko Pokračovať pre zobrazenie rekapitulácie importu GEDCOM súboru.";
$pgv_lang["inc_languages"]		= " Jazyky";
$pgv_lang["invalid_dates"]		= "Rozoznané nesprávné formáty dátumu, vyčistením budú tieto formáty zmenené do podoby DD MMM YYYY (napr. 1 JAN 2005).";
$pgv_lang["invalid_header"]		= "V GEDCOM súbore boli nájdené riadky pred hlavičkou GEDCOM <b>0&nbsp;HEAD</b>. Pri čistení súboru budú tieto riadky odstránené.";
$pgv_lang["label_added_servers"]	= "Pridané vzdialené servery";
$pgv_lang["label_banned_servers"]   = "Zakázané stránky podle IP";
$pgv_lang["label_families"]         = "Rodiny";
$pgv_lang["label_gedcom_id2"]       = "GEDCOM ID:";
$pgv_lang["label_individuals"]      = "Osoby";
$pgv_lang["label_manual_search_engines"]   = "Manuálne určite Search Engines pomocou IP";
$pgv_lang["label_new_server"]       = "Pridať novú stránku";
$pgv_lang["label_password_id"]		= "Heslo";
$pgv_lang["label_remove_ip"]		= "Zakázaná IP adresa (napr. 198.128.*.*):";
$pgv_lang["label_remove_search"]	= "Označte IP adresy ako Search Engine Spiders:";
$pgv_lang["label_server_info"]      = "Všetci sú vzdialene pripojený cez stránku:";
$pgv_lang["label_server_url"]       = "URL/IP stránky";
$pgv_lang["label_username_id"]		= "Uživateľské meno";
$pgv_lang["label_view_local"]       = "Zobraziť lokálne informácie o osobe";
$pgv_lang["label_view_remote"]      = "Zobraziť vzdialené informácie o osobe";
$pgv_lang["link_manage_servers"]    = "Správa stránok";
$pgv_lang["logfile_content"]		= "Obsah \"log\" súboru";
$pgv_lang["macfile_detected"]		= "Bol nájdený súbor pre Macintosh. Pri čistení bude tento súbor prevedený na súbor pre DOS.";
$pgv_lang["merge_records"]			= "Zlúčiť záznamy";
$pgv_lang["month_before_day"]		= "Mesiac pred dňom (MM DD YYYY)";
$pgv_lang["none"]					= "Žiadny";
$pgv_lang["performing_validation"]	= "Vykonávanie validácie (skontrolovanie) GEDCOMu, vyberte potrebné možnosti a kliknite na 'Pokračovať'";
$pgv_lang["pgv_registry"]		= "Zobraziť iné weby používajúce PhpGedView";
$pgv_lang["phpinfo"]			= "PHPInfo";
$pgv_lang["place_cleanup_detected"]	= "Bolo rozoznané zlé kódovanie miest. Tieto chyby by mali byť opravené. Nasledujúci príklad ukazuje jedno z nesprávne zapísaných miest:";
$pgv_lang["please_be_patient"]		= "PROSÍM O STRPENIE";
$pgv_lang["reading_file"]		= "Načítanie súboru GEDCOM";
$pgv_lang["readme_documentation"]	= "README dokumentácia";
$pgv_lang["remove_ip"] 			= "Odstrániť IP";
$pgv_lang["rootid"]			= "Východisková osoba vývodu";
$pgv_lang["select_an_option"]		= "Vyberte jednu z možností:";
$pgv_lang["siteadmin"]			= "Administrátor stránky";
$pgv_lang["skip_cleanup"]			= "Preskočiť opravovanie";
$pgv_lang["time_limit"]			= "Časový limit:";
$pgv_lang["title_manage_servers"]   = "Spravovať stránky";
$pgv_lang["title_view_conns"]       = "Zobraziť spojenie.";
$pgv_lang["update_myaccount"]		= "Aktualizovať môj účet";
$pgv_lang["update_user"]		= "Aktualizovať uživateľský účet";
$pgv_lang["upload_gedcom"]		= "Nahrať GEDCOM";
$pgv_lang["user_auto_accept"]		= "Automaticky akceptovať zmeny urobené uživateľom";
$pgv_lang["user_contact_method"]	= "Uprednostňovaný spôsob kontaktu";
$pgv_lang["user_create_error"]		= "Nie je možné pridať uživateľa. Prosím vráťte sa späť a skúste to znovu.";
$pgv_lang["user_created"]		= " Uživateľ bol úspešne vytvorený.";
$pgv_lang["user_default_tab"]		= "Implicitné záložky k zobrazeniu na osobnej informačnej stránke";
$pgv_lang["valid_gedcom"]		= "Validný GEDCOM. Žiadne opravy nebolo treba.";
$pgv_lang["validate_gedcom"]		= "Potvrdiť platnosť GEDCOMu";
$pgv_lang["verified"]			= "Uživateľ potvrdil registráciu";
$pgv_lang["verified_by_admin"]		= "Uživateľ bol adminom povolený";
$pgv_lang["verify_gedcom"]		= "Verifikácia GEDCOMu";
$pgv_lang["verify_upload_instructions"]	= "Ak zvolíte Pokračovať, bude starý GEDCOM súbor nahradený novým súborom, ktorý ste nahrali, a importovanie začne znovu. Ak zvolíte Zrušiť, zostane starý súbor zachovaný.";
$pgv_lang["view_changelog"]			= "Zobraziť changelog.txt súbor";
$pgv_lang["view_logs"]			= "Zobraziť logfiles";
$pgv_lang["view_readme"]		= "Zobraziť súbor readme.txt";
$pgv_lang["visibleonline"]		= "Viditeľný pre iných uživateľov, keď je online";
$pgv_lang["visitor"]			= "Návštevník";
$pgv_lang["you_may_login"]		= " administrátorom stránok. Teraz sa môžete odkazom dolu prihlásiť do systému PhpGedView:";


?>
