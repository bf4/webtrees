<?php
/**
 * Norwegian texts
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$pgv_lang["user"]					= "Godkjent bruker";
$pgv_lang["thumbnail_deleted"]		= "Miniatyrbilde-fil er nå slettet.";
$pgv_lang["thumbnail_not_deleted"]	= "Klarte ikke å slette miniatyrbilde-fil.";
$pgv_lang["step2"]					= "Del 2 av 4:";
$pgv_lang["refresh"]			= "Oppdater";
$pgv_lang["move_file_success"]	= "Media- og miniatyrbilde-filer er nå flyttet.";
$pgv_lang["media_folder_corrupt"]	= "Det er en feil med mediamappen.";
$pgv_lang["media_file_not_deleted"]	= "Klarte ikke å slette mediafil.";
$pgv_lang["gedcom_deleted"]		= "Slektsfilen [#GED#] er nå slettet.";
$pgv_lang["gedadmin"]				= "Administrator av slektsfilen";
$pgv_lang["full_name"]				= "Fullt navn";
$pgv_lang["error_header"] 		= "Slektsfilen [#GEDCOM#], finnes ikke på det angitte stedet.";
$pgv_lang["confirm_delete_file"]	= "Er du sikker på at du vil slette denne filen?";
$pgv_lang["confirm_folder_delete"] = "Er du sikker på at du vil slette denne mappen?";
$pgv_lang["confirm_remove_links"]	= "Er du sikker på at du vil fjerne alle koblinger til dette objektet?";
$pgv_lang["manage_gedcoms"]			= "Slektsfil(er) og personvern";
$pgv_lang["created_remotelinks"]	= "Opprettet tabellen <i>Eksterne koblinger</i>.";
$pgv_lang["created_remotelinks_fail"] 	= "Klarte ikke å opprette tabellen <i>Eksterne koblinger</i>.";
$pgv_lang["created_indis"]		= "Opprettet tabellen <i>Personer</i>.";
$pgv_lang["created_indis_fail"]	= "Klarte ikke å opprette tabellen <i>Personer</i>!";
$pgv_lang["created_fams"]		= "Opprettet tabellen <i>Familier</i>.";
$pgv_lang["created_fams_fail"]	= "Klarte ikke å opprette tabellen <i>Familier</i>!";
$pgv_lang["created_sources"]	= "Opprettet tabellen <i>Kilder</i>.";
$pgv_lang["created_sources_fail"]	= "Klarte ikke å opprette tabellen <i>Kilder</i>!";
$pgv_lang["created_other"]		= "Opprettet tabellen <i>Annet</i>.";
$pgv_lang["created_other_fail"]	= "Klarte ikke å opprette tabellen <i>Annet</i>!";
$pgv_lang["created_places"]		= "Opprettet tabellen <i>Steder</i>.";
$pgv_lang["created_places_fail"]	= "Klarte ikke å opprette tabellen <i>Steder</i>!";
$pgv_lang["created_placelinks"] 	= "Opprettet tabellen <i>Stedskoblinger</i>.";
$pgv_lang["created_placelinks_fail"]	= "Klarte ikke å opprette tabellen <i>Stedskoblinger</i>.";
$pgv_lang["created_media_fail"]	= "Klarte ikke å opprette tabellen <i>Media</i>.";
$pgv_lang["created_media_mapping_fail"]	= "Klarte ikke å opprette tabellen <i>Media-mapper</i>.";
$pgv_lang["no_thumb_dir"]		= " mappen/katalogen for miniatyrbilde(r) finnes ikke og den kunne heller ikke opprettes.";
$pgv_lang["move_to"]			= "Flytt til";
$pgv_lang["folder_created"]		= "Opprettet mappe";
$pgv_lang["folder_no_create"]	= "Kan ikke opprette mappe";
$pgv_lang["security_no_create"]	= "Sikkerhetsadvarsel: Filen <b><i>index.php</i></b> finnes ikke i ";
$pgv_lang["security_not_exist"]	= "Sikkerhetsadvarsel: Klarte ikke å lage filen <b><i>index.php</i></b> i ";
$pgv_lang["label_add_search_server"]	= "Legg til IP";
$pgv_lang["label_add_server"]       = "Legg til";
$pgv_lang["label_ban_server"]		= "Utfør";
$pgv_lang["label_delete"]           = "Slett";
$pgv_lang["add_gedcom"]			= "Legg til en slektsfil";
$pgv_lang["add_new_gedcom"]			= "Lag en ny slektsfil";
$pgv_lang["admin_approved"]		= "Din konto hos #SERVER_NAME# er blitt godkjent";
$pgv_lang["admin_gedcom"]			= "Administrere";
$pgv_lang["admin_geds"]				= "Data- og slektsfil-administrasjon";
$pgv_lang["admin_info"]				= "Informasjon";
$pgv_lang["admin_site"]				= "Administrasjon av nettstedet";
$pgv_lang["administration"]			= "Administrasjon";
$pgv_lang["ansi_encoding_detected"]	= "Oppdaget ANSI tekstkoding.  PhpGedView fungerer best med filer som er kodet med UTF-8.";
$pgv_lang["ansi_to_utf8"]			= "Konvertere fra ANSI til UTF-8 tegnsett?";
$pgv_lang["apply_privacy"]			= "Legge til nye innstillinger for personvern?";
$pgv_lang["bytes_read"]			= "Bytes lest:";
$pgv_lang["calc_marr_names"]		= "Kopierer ektemennenes navn";
$pgv_lang["change_id"]				= "Endre personID til:";
$pgv_lang["choose_priv"]			= "Velg nivå for personvern:";
$pgv_lang["cleanup_places"]			= "Rydd opp i stedene";
$pgv_lang["click_here_to_go_to_pedigree_tree"] = "Klikk her for å gå til slektstreet.";
$pgv_lang["comment"]				= "Kommentar fra admin til bruker";
$pgv_lang["comment_exp"]			= "Advarsel fra admin om dato";
$pgv_lang["configuration"]			= "Program-innstillinger";
$pgv_lang["confirm_user_delete"]	= "Er du sikker på at du vil slette brukeren";
$pgv_lang["create_user"]			= "Opprett bruker";
$pgv_lang["dataset_exists"]		= "Det er allerede importert en slektsfil i databasen med navnet ";
$pgv_lang["day_before_month"]		= "Dag før måned (DD MM ÅÅÅÅ)";
$pgv_lang["do_not_change"]			= "Ikke gjør endringer";
$pgv_lang["download_gedcom"]		= "Laste ned (download) slektsfil (GEDCOM)";
$pgv_lang["download_note"]			= "NB! Store slektsfiler (GEDCOM) kan ta lang tid å forberede før en nedlastning (download).  Dersom PHP melder at tiden har gått ut før nedlastningen er ferdig, så kan det være at du ikke har mottatt hele filen.  For å sjekke om den nedlastede slektsfilen er korrekt, kan se om filen inneholder linjen <b>0 TRLR</b> på slutten.  Som en tommelfinger-regel vil tiden det tar å laste ned slektsfilen, være like lang som det tok å importere den (avhengig av hastigheten på internett-tilkoblingen din).";
$pgv_lang["editaccount"]			= "Gi denne brukeren rettighet til å endre brukerkontoen sin";
$pgv_lang["empty_dataset"]		= "Vil du tømme den nåværende slektsbasen og legge data inn på nytt?";
$pgv_lang["empty_lines_detected"]	= "Oppdaget tomme linjer i slektsfilen din.  Under oppryddingen vil disse tomme linjene bli fjernet.";
$pgv_lang["error_ban_server"]       = "Ugyldig IP-adresse.";
$pgv_lang["error_delete_person"]    = "Du må velge personen som du ønsker å slette den eksterne koblingen til.";
$pgv_lang["error_header_write"]	= "Slektsfilen [#GEDCOM#], er ikke skrivbar. Sjekk attributter og tilgangsrettigheter.";
$pgv_lang["error_siteauth_failed"]	= "Fikk ikke tilgang til eksternt nettsted";
$pgv_lang["error_url_blank"]		= "Tittel og URL til eksternt nettsted må fylles ut";
$pgv_lang["error_view_info"]        = "Du må velge personen som du ønsker å se opplysninger om.";
$pgv_lang["example_date"]			= "Eksempel på ugyldig dato fra slektsfilen din:";
$pgv_lang["example_place"]			= "Eksempel på ugyldig stedsnavn fra slektsfilen din:";
$pgv_lang["found_record"]		= "dataposter funnet";
$pgv_lang["ged_import"]			= "Importer";
$pgv_lang["gedcom_downloadable"]	= "<br />Besøkende på nettstedet ditt kan laste ned (download) denne slektsfilen!<br />Les mer om dette i filen <a href=\"".(file_exists('readme-norsk.txt')?"readme-norsk.txt":"readme.txt")."\">readme".(file_exists('readme-norsk.txt')?"-norsk":"").".txt</a> i avsnittet 12. SIKKERHET / PERSONVERN<br />for å finne en løsning på dette.";
$pgv_lang["gedcom_file"]			= "Slektsfil:";
$pgv_lang["img_admin_settings"]		= "Endre innstillingene for bilde-behandling";
$pgv_lang["import_complete"]	= "Import ferdig";
$pgv_lang["import_marr_names"]		= "Importere Navn som gift";
$pgv_lang["import_options"]			= "Valg for import";
$pgv_lang["import_progress"]	= "Import utført...";
$pgv_lang["import_statistics"]		= "Statistikk for importen";
$pgv_lang["import_time_exceeded"]	= "Maks utføringstid for en handling er nådd !<br />- Klikk på knappen \"Fortsett\" for å gjenoppta importen av slektsfilen (som ny handling).";
$pgv_lang["inc_languages"]			= " Språk";
$pgv_lang["invalid_dates"]			= "Oppdaget ugyldig dato-format. Ved rydding vil disse bli endret til formatet DD MMM ÅÅÅÅ (f.eks. 1 JAN 2004).";
$pgv_lang["invalid_header"]			= "Oppdaget at det er linjer før startlinjen (0 HEAD) i slektsfilen.  Under oppryddingen vil disse linjene bli fjernet.";
$pgv_lang["label_added_servers"]	= "Eksterne nettsteder som er lagt til";
$pgv_lang["label_banned_servers"]   = "Bannlys nettsteder ved IP";
$pgv_lang["label_families"]         = "Familier";
$pgv_lang["label_gedcom_id2"]       = "GEDCOM-ID:";
$pgv_lang["label_individuals"]      = "Personer";
$pgv_lang["label_manual_search_engines"]   = "Merk søkemaskiner manuelt ved IP";
$pgv_lang["label_new_server"]       = "Legg til nytt nettsted";
$pgv_lang["label_password_id"]		= "Passord";
$pgv_lang["label_remove_ip"]		= "Bannlys IP-adresse (f.eks: 198.128.*.*): ";
$pgv_lang["label_remove_search"]	= "Merk IP-adresser som Søkemaskinspioner: ";
$pgv_lang["label_server_info"]      = "Alle personer som har en kobling til dette nettstedet:";
$pgv_lang["label_server_url"]       = "URL/IP til nettsted";
$pgv_lang["label_username_id"]		= "Brukernavn";
$pgv_lang["label_view_local"]       = "Vis opplysninger om personen som er lagre lokalt";
$pgv_lang["label_view_remote"]      = "Vis opplysninger om personen som er lagret eksternt";
$pgv_lang["link_manage_servers"]    = "Oppsett for nettsteder";
$pgv_lang["logfile_content"]		= "Innhold i logg-filen";
$pgv_lang["macfile_detected"]		= "Oppdaget Macintosh-fil.  Under oppryddingen vil denne filen bli konvertert til en DOS-fil.";
$pgv_lang["merge_records"]		= "Flette data (dobbelregisterte)";
$pgv_lang["month_before_day"]		= "Måned før dag (MM DD YYYY)";
$pgv_lang["performing_validation"]	= "Sjekken er utført...!  Gjør de nødvendige valgene og klikk deretter på 'Rydd'";
$pgv_lang["pgv_registry"]			= "Vis andre nettsteder som bruker PhpGedView";
$pgv_lang["phpinfo"]				= "PHPInfo";
$pgv_lang["place_cleanup_detected"]	= "Oppdaget ugyldig stedskoder.  Disse bør endres!  Følgende steder er ugyldige: ";
$pgv_lang["please_be_patient"]	= "Vennligst VENT...";
$pgv_lang["reading_file"]		= "Leser slektsfilen";
$pgv_lang["readme_documentation"]	= "ReadMe-dokumentasjon (Engelsk)";
$pgv_lang["remove_ip"] 				= "Fjern IP";
$pgv_lang["rootid"]					= "ID til startperson<br />i slektsbasen";
$pgv_lang["select_an_option"]		= "Alternativer:";
$pgv_lang["siteadmin"]				= "Administrator av nettstedet";
$pgv_lang["skip_cleanup"]		= "Ikke rydd...!?";
$pgv_lang["time_limit"]			= "Tidsgrense:";
$pgv_lang["title_manage_servers"]   = "Oppsett for nettsteder";
$pgv_lang["title_view_conns"]       = "Vis tilkoblinger";
$pgv_lang["update_myaccount"]		= "Oppdater Min konto";
$pgv_lang["update_user"]			= "Oppdater brukerkonto";
$pgv_lang["upload_gedcom"]			= "Hente (upload) slektsfil(er) (GEDCOM)";
$pgv_lang["user_auto_accept"]		= "Godkjenn automatisk endringer gjort av denne brukeren";
$pgv_lang["user_contact_method"]	= "Ønsket kontaktmetode";
$pgv_lang["user_create_error"]		= "Bruker kan ikke opprettes. Gå tilbake og prøv på nytt.";
$pgv_lang["user_created"]			= "Bruker er opprettet.";
$pgv_lang["user_default_tab"]		= "Arkfanen som skal vises som standard på faktasiden til personer";
$pgv_lang["valid_gedcom"]			= "Gyldig slektsfil funnet.  Det er ikke nødvendig å gjøre endringer.";
$pgv_lang["validate_gedcom"]		= "Sjekker kvaliteten til slektsfilen";
$pgv_lang["verified"]			= "Bruker har<br />bekreftet søknaden";
$pgv_lang["verified_by_admin"]	= "Godkjent bruker<br />[av Admin]";
$pgv_lang["verify_gedcom"]			= "Sjekk slektsfilen (GEDCOM)";
$pgv_lang["verify_upload_instructions"]	= "Dersom du velger å fortsette, vil den eksisterende slektsfilen bli erstattet med den filen som du har valgt å laste opp. Den nye filen vil deretter bli importert inn i PhpGedView.<br />Velger du å avbryte, vil den eksisterende slektsfilen forbli uforandret.";
$pgv_lang["view_changelog"]			= "Vis filen changelog.txt";
$pgv_lang["view_logs"]				= "Vis logg-fil ";
$pgv_lang["view_readme"]			= "Vis filen readme.txt";
$pgv_lang["visibleonline"]			= "Vis andre at du er pålogget";
$pgv_lang["visitor"]				= "Besøkende";
$pgv_lang["you_may_login"]		= " av administratoren til nettstedet.<br />Du kan nå logge deg inn på nettstedet ved å klikke på linken under:";


?>
