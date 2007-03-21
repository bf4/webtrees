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

$pgv_lang["user"]					= "Autentikus felhasználó";
$pgv_lang["thumbnail_deleted"]		= "Elönézet állomány sikeresen törölve.";
$pgv_lang["thumbnail_not_deleted"]	= "Elönézet állományt nem lehetett kitörölni.";
$pgv_lang["step2"]			= "2. lépés a 4-ből:";
$pgv_lang["refresh"]				= "Frissítés";
$pgv_lang["move_file_success"]		= "Média és elönézet állomány sikeresen átrakva.";
$pgv_lang["media_folder_corrupt"]	= "A média mappa hibás.";
$pgv_lang["media_file_not_deleted"]	= "Média állományt nem lehetett kitörölni.";
$pgv_lang["gedcom_deleted"]		= "GEDCOM [#GED#] sikeresen törölve lett.";
$pgv_lang["gedadmin"]				= "GEDCOM Adminisztrátor";
$pgv_lang["full_name"]			= "Teljes név";
$pgv_lang["error_header"] 		= "A <b>#GEDCOM#</b> nevű GEDCOM-állomány a megadott helyen nem érhető el.";
$pgv_lang["confirm_delete_file"]	= "Biztosan törölni kivánja ezt az állományt?";
$pgv_lang["confirm_folder_delete"] = "Biztos benne hogy, ezt a mappát ki akarja törölni?";
$pgv_lang["confirm_remove_links"]	= "Biztosan kivánja ennek az elemnek az összes kapcsolatait törölni?";
$pgv_lang["manage_gedcoms"]		= "GEDCOM-kezelés és diszkréciós beállítások";
$pgv_lang["created_indis"]		= "A <i>Személyek</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_indis_fail"] 	= "A <i>Személyek</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_fams"]		= "A <i>Családok</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_fams_fail"]  	= "A <i>Családok</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_sources"]		= "A <i>Források</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_sources_fail"]       = "A <i>Források</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_other"]		= "Az <i>Egyebek</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_other_fail"] 	= "Az <i>Egyebek</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_places"]		= "A <i>Helyszínek</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_places_fail"]        = "A <i>Helyszínek</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_placelinks"] 	= "A <i>Helyszinek Link</i> táblát sikeresen létrehoztuk.";
$pgv_lang["created_placelinks_fail"]	= "A <i>Helyszinek Link</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_media_fail"]	= "A <i>Média</i> táblát nem sikerült létrehozni.";
$pgv_lang["created_media_mapping_fail"]	= "A <i>Média címezés</i> táblát nem sikerült létrehozni.";
$pgv_lang["no_thumb_dir"]			= " az elönézet mappa nem létezik és nem lehetett létrehozni.";
$pgv_lang["folder_created"]		= "Könyvtár létrehozása";
$pgv_lang["folder_no_create"]		= "A mappát nem sikerült létrehozni";
$pgv_lang["security_no_create"]		= "Biztonsági Figyelmeztetés: Az állomány <b><i>index.php</i></b>, nem létezik itt";
$pgv_lang["security_not_exist"]		= "Biztonsági Figyelmeztetés: Nem lehetett az <b><i>index.php</i></b> állományt létrehozni itt ";
$pgv_lang["label_add_server"]       = "Hozzáad";
$pgv_lang["label_delete"]           = "Töröl";
$pgv_lang["add_gedcom"]			= "Új GEDCOM-állomány hozzáadása";
$pgv_lang["add_new_gedcom"]		= "Új GEDCOM-állomány létrehozása";
$pgv_lang["admin_approved"]		= "Elfogadtuk az azonosítóját a(z) #SERVER_NAME# szerveren";
$pgv_lang["admin_gedcom"]               = "GEDCOM-adminisztráció";
$pgv_lang["admin_geds"]				= "Adat és GEDCOM adminisztráció";
$pgv_lang["admin_info"]				= "Információs";
$pgv_lang["admin_site"]				= "Site adminisztráció";
$pgv_lang["administration"]		= "Adminisztráció";
$pgv_lang["ansi_encoding_detected"]	= "ANSI kódolást találtunk. A program akkor működik a legmegfelelőbben, ha az állományok kódolása UTF-8.";
$pgv_lang["ansi_to_utf8"]		= "Át szeretné konvertálni ezt a GEDCOM-ot ANSI-ból UTF-8-ba?";
$pgv_lang["apply_privacy"]			= "Alkalmazás a bizalmas beállításoknak?";
$pgv_lang["bytes_read"]			= "Byte beolvasva:";
$pgv_lang["calc_marr_names"]		= "Házasult nevek kiszámolása";
$pgv_lang["change_id"]			= "A személyes ID megváltoztatása erre:";
$pgv_lang["choose_priv"]			= "Válasszon bizalmassági fokot:";
$pgv_lang["cleanup_places"]             = "Helyszínek tisztítása";
$pgv_lang["click_here_to_go_to_pedigree_tree"] = "Kattintson ide, hogy megtekintse az ősfát.";
$pgv_lang["comment"]				= "Adminisztrátor megjegyzése a felhasználóról";
$pgv_lang["comment_exp"]			= "Adminisztrátor figyelmeztetés ezen a dátumon";
$pgv_lang["configuration"]		= "Beállítások";
$pgv_lang["confirm_user_delete"]	= "Valóban törölni kívánja ezt a felhasználót?";
$pgv_lang["create_user"]		= "Felhasználó létrehozása";
$pgv_lang["dataset_exists"]		= "Ezzel a névvel már van adatbázisba importált GEDCOM.";
$pgv_lang["day_before_month"]		= "Nap a hónap előtt (NN HH ÉÉÉÉ)";
$pgv_lang["do_not_change"]		= "Ne legyen változás";
$pgv_lang["download_gedcom"]		= "GEDCOM-állomány letöltése";
$pgv_lang["download_note"]              = "Megjegyzés: A nagyméretű GEDCOM-állományok letöltés előtti feldolgozása hosszú időt vehet igénybe. Ha PHP előre definiált futási ideje letelik a letöltés befejezése előtt, akkor Ön egy nem teljes állomnyt kaphat.<br/><br/>A helyes letöltést ellenőrizheti az állomány végén található értékkel: <b>0&nbsp;TRLR</b>. GEDCOM álományok sima írott szöveggel vannak írva igy bármilyen szövegolvasó szofverrel kitudja nyitni, de biztos legyen benne hogy <u>ne</u> spórolja meg a GEDCOM álományt az ellenörzés után.<br/><br/>Általánosságban a letöltés kb. annyi ideig tart, mint az adott GEDCOM-állomány importálása.";
$pgv_lang["duplicate_username"]		= "Ezzel a névvel már létezik felhasználó. Kérem, lépjen vissza és válasszon másik felhasználónevet!";
$pgv_lang["editaccount"]		= "A felhasználó szerkesztheti a saját felhasználói adatait";
$pgv_lang["empty_dataset"]		= "Ki szeretné törölni a régi adatokat és kicserélni ezzel az új adatokkal?";
$pgv_lang["empty_lines_detected"]       = "Empty lines were detected in your GEDCOM file.  On cleanup, these empty lines will be removed.";
$pgv_lang["error_ban_server"]       = "Érvénytelen IP cím.";
$pgv_lang["error_header_write"] 	= "A <b>#GEDCOM#</b> nevű GEDCOM-állomány nem írható. Kérjük, ellenőrizze a tulajdonságait és jogosultságait.";
$pgv_lang["example_date"]		= "Hibás dátum a GEDCOM-állományból:";
$pgv_lang["example_place"]			= "Hibás helyszín a GEDCOM-állományból:";
$pgv_lang["found_record"]		= "Rekordot találtunk";
$pgv_lang["ged_import"]			= "Importálás";
$pgv_lang["gedcom_downloadable"]        = "Ez a GEDCOM-állomány letölthető az interneten kerszetül.<br/>Kérjük, tekintse át a <a href=\"readme.txt\"><b>readme.txt</b></a> SECURITY (BIZTONSÁG) fejezetét a probléma megszüntetéséhez.";
$pgv_lang["gedcom_file"]		= "GEDCOM-állomány:";
$pgv_lang["img_admin_settings"]		= "A képeszekesztés beállításai";
$pgv_lang["import_complete"]		="Az importálás elkészült";
$pgv_lang["import_marr_names"]		= "Házasult név importálása";
$pgv_lang["import_options"]		= "Import Lehetöségek";
$pgv_lang["import_progress"]    	= "Az importálás folyamatban...";
$pgv_lang["import_statistics"]	= "Statisztika Importálás";
$pgv_lang["import_time_exceeded"]	= "A maximun futtatási idő lejárt. Kattintson a Folytatás gombra hogy, folytassa a GEDCOM állomány importálását.";
$pgv_lang["inc_languages"]		= "Nyelvi állományok";
$pgv_lang["invalid_dates"]		= "Hibás dátumformátumokat találtam, a tisztítás után ezek a következő formátumra cseréljük: Év Hónap Nap (pl. 2004. január 1.).";
$pgv_lang["invalid_header"]             = "A GEDCOM-állomány fejléce <b>(0 HEAD)</b> előtti sorokat találtunk.  Ezeket a tisztítás során el fogjuk távolítani.";
$pgv_lang["label_families"]         = "Családok";
$pgv_lang["label_gedcom_id2"]       = "Adatbázis azonosítószáma:";
$pgv_lang["label_individuals"]      = "Személyek";
$pgv_lang["label_password_id"]		= "Jelszó";
$pgv_lang["label_remove_ip"]		= "IP cím letíltása (Pl: 198.128.*.*): ";
$pgv_lang["label_username_id"]		= "Felhasználónév";
$pgv_lang["logfile_content"]    	= "A napló-állomány tartalma";
$pgv_lang["macfile_detected"]   	= "Macintosh-állományt találtunk. A tisztítás során ezt DOS-állománnyá fogjuk konvertálni.";
$pgv_lang["merge_records"]              = "Rekordok összefűzése";
$pgv_lang["month_before_day"]		= "Hónap a nap előtt (HH NN ÉÉÉÉ)";
$pgv_lang["none"]					= "Semmi";
$pgv_lang["performing_validation"]	= "GEDCOM ellenőrzés kezdődik, válassza ki a kívánt lehetőségeket, majd kattintson a 'Tisztítás' gombra.";
$pgv_lang["pgv_registry"]               = "Oldalak, melyek szintén PhpGedView-t használnak";
$pgv_lang["phpinfo"]				= "PHP információ";
$pgv_lang["place_cleanup_detected"]     = "Érvénytelen helyszín-kódolást találtunk, melyeket javítani lenne szükséges. Az észlelt érvénytelen helyszínt a következő minta mutatja be: ";
$pgv_lang["please_be_patient"]		= "KÉRJÜK, LEGYEN TÜRELEMMEL";
$pgv_lang["reading_file"]		= "GEDCOM állomány beolvasása";
$pgv_lang["readme_documentation"]	= "OLVASSEL Dokumentáció";
$pgv_lang["rootid"]			= "A családfa-grafikon kezdő személye";
$pgv_lang["select_an_option"]		= "Válasszon az alábbi lehetőségek közül:";
$pgv_lang["siteadmin"]				= "Site adminisztrátor";
$pgv_lang["skip_cleanup"]				= "Tisztítás megszakítása";
$pgv_lang["time_limit"]				= "Időhatár:";
$pgv_lang["update_myaccount"]		= "A felhasználói adataim frissítése";
$pgv_lang["update_user"]		= "Felhasználói jogosultság frissítése";
$pgv_lang["upload_gedcom"]		= "GEDCOM-állomány feltöltése";
$pgv_lang["user_auto_accept"]		= "Ennek a használónak a változtatásai automatikusan elfogadható";
$pgv_lang["user_contact_method"]	= "Kapcsolattartási mód";
$pgv_lang["user_create_error"]		= "Nem sikerült a felhasználót hozzáadni. Kérjük lépjen vissza, és próbálja meg újra.";
$pgv_lang["user_created"]		= "A felhasználót sikeresen hozzáadtuk.";
$pgv_lang["user_default_tab"]		= "Mutassa ezt az oldalt mint alap oldal a Személyek Információs oldalán";
$pgv_lang["valid_gedcom"]		= "Érvényes GEDCOM-ot észleltem. Nincs szükség tisztításra.";
$pgv_lang["validate_gedcom"]		= "GEDCOM érvényességének ellenőrzése";
$pgv_lang["verified"]			= "A felhasználó megerősítette jelentkezését";
$pgv_lang["verified_by_admin"]		= "A felhasználót elfogadta az adminisztrátor";
$pgv_lang["verify_gedcom"]		= "GEDCOM ellenörzése";
$pgv_lang["verify_upload_instructions"] = "Ha Ön a folytatás mellet dönt, a korábbi GEDCOM-állományt az Ön által feltöltöttre fogjuk lecserélni és az importálási folyamat újrakezdődik. Ha a megszakítást választja, a korábbi GEDCOM-állomány érintetlen marad.";
$pgv_lang["view_changelog"]			= "changelog.txt megtekintése";
$pgv_lang["view_logs"]			= "Napló-állományok megtekintése";
$pgv_lang["view_readme"]			= "readme.txt állomány tekintése";
$pgv_lang["visibleonline"]              = "Bejelentkezés után látható";
$pgv_lang["visitor"]				= "Látogató";
$pgv_lang["you_may_login"]		= " az adminisztrátor által. Bejelentkezhet a PhpGedView oldalra a lenti hivatkozásra kattintva:";


?>
