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
 * @version $Id: lang.en.php 294 2006-07-24 12:27:56Z opus27 $
 */
$pgv_lang["user"]					= "Inloggade användare";
$pgv_lang["thumbnail_deleted"]		= "Miniatyrbild raderades.";
$pgv_lang["thumbnail_not_deleted"]	= "Miniatyrbild kunde inte raderas.";
$pgv_lang["step2"]				= "Steg 2 av 4:";
$pgv_lang["refresh"]				= "Ladda om";
$pgv_lang["move_file_success"]		= "Media- och miniatyrfiler flyttades korrekt.";
$pgv_lang["media_folder_corrupt"]	= "Mediamappen är skadad.";
$pgv_lang["media_file_not_deleted"]	= "Mediafil kunde inte raderas.";
$pgv_lang["gedcom_deleted"]		= "Radering av GEDCOM [#GED#] lyckades.";
$pgv_lang["gedadmin"]				= "GEDCOM-administratör";
$pgv_lang["full_name"]			= "Fullständigt namn";
$pgv_lang["error_header"] 		= "GEDCOM-filen, <b>[#GEDCOM#]</b>, finns inte på den angivna platsen.";
$pgv_lang["confirm_delete_file"]	= "Är du säker på at du vill radera denna fil?";
$pgv_lang["confirm_folder_delete"] = "Är du säker på att du vill radera denna mapp?";
$pgv_lang["confirm_remove_links"]	= "Är du säker på att du vill ta bort alla länkar till detta objekt?";
$pgv_lang["manage_gedcoms"]		= "Administrera GEDCOM-filer och integritetsinställningar";
$pgv_lang["created_remotelinks"]	= "Lyckades skapa <i>länk till andra</i> tabellen.";
$pgv_lang["created_remotelinks_fail"] 	= "Kunde inte skapa <i>länk till andra</i> tabellen.";
$pgv_lang["created_indis"]		= "Skapande av <i>Persontabell</i> lyckades.";
$pgv_lang["created_indis_fail"]	= "Kunde inte skapa <i>Persontabellen</i>.";
$pgv_lang["created_fams"]		= "Skapande av <i>Familjetabell</i> lyckades.";
$pgv_lang["created_fams_fail"]	= "Kunde inte skapa <i>Familjetabellen<i>.";
$pgv_lang["created_sources"]	= "Skapande av <i>Källtabell</i> lyckades.";
$pgv_lang["created_sources_fail"]	= "Kunde inte skapa <i>Källtabellen</i>.";
$pgv_lang["created_other"]		= "Skapande av <i>Diversetabell</i> lyckades.";
$pgv_lang["created_other_fail"]	= "Kunde inte skapa <i>diversetabellen</i>.";
$pgv_lang["created_places"]		= "Skapande av <i>Ortstabell</i> lyckades.";
$pgv_lang["created_places_fail"]= "Kunde inte skapa <i>Orttabellen</i>.";
$pgv_lang["created_placelinks"] 	= "<i>Ortlänkstabellen</i> skapades utan problem.";
$pgv_lang["created_placelinks_fail"]	= "Kan inte skapa <i>ortlänkstabellen</i>.";
$pgv_lang["created_media_fail"]	= "Kan inte skapa <i>Mediatabell</i>.";
$pgv_lang["created_media_mapping_fail"]	= "Kan inte skapa <i>Mediamappningstabellen.</i>";
$pgv_lang["no_thumb_dir"]		= "miniatyrbildsmappen existerar inte och det gick inte att skapa en.";
$pgv_lang["move_to"]			= "Flytta till";
$pgv_lang["folder_created"]		= "Skapad mapp";
$pgv_lang["folder_no_create"]		= "Kan inte skapa mapp";
$pgv_lang["security_no_create"]	= "Säkerhetsvarning: Filen <b><i>index.php</i></b> finns inte i ";
$pgv_lang["security_not_exist"]	= "Säkerhetsvarning, kan inte skapa <b><i>index.php</i></b> i ";
$pgv_lang["label_add_search_server"]	= "Lägg till IP-adress";
$pgv_lang["label_add_server"]       = "Lägga till";
$pgv_lang["label_ban_server"] 		= "Skicka";
$pgv_lang["label_delete"]           = "Radera";
$pgv_lang["upload_replacement"]		="Ladda upp ersättningsfil";
$pgv_lang["add_gedcom"]			= "Lägg till GEDCOM-fil";
$pgv_lang["add_new_gedcom"]		= "Skapa ny GEDCOM-fil";
$pgv_lang["admin_approved"]		= "Ditt konto på #SERVER_NAME# har blivit godkänt.";
$pgv_lang["admin_gedcom"]		= "Administrera GEDCOM";
$pgv_lang["admin_geds"]				= "Data och GEDCOM-administration";
$pgv_lang["admin_info"]				= "Information";
$pgv_lang["admin_site"]				= "Sajtadministration";
$pgv_lang["administration"]		= "Administration";
$pgv_lang["ansi_encoding_detected"]	= "ANSIkodning upptäckt i filen. PhpGedView fungerar bäst med teckenkodningen UTF-8.";
$pgv_lang["ansi_to_utf8"]		= "Konvertera denna ANSI(iso-8859-1) kodade GEDCOM till UTF-8?";
$pgv_lang["apply_privacy"]			= "Tillämpa integritetsinställningar?";
$pgv_lang["bytes_read"]			= "Bytes lästa:";
$pgv_lang["calc_marr_names"]	= "Beräknar namn för vigda";
$pgv_lang["change_id"]				= "Ändra person ID till:";
$pgv_lang["choose_priv"]			= "Välj integritetsnivå:";
$pgv_lang["cleanup_places"]		= "Städa orter";
$pgv_lang["click_here_to_go_to_pedigree_tree"] = "Klicka här för att gå till antavlan.";
$pgv_lang["comment"]			= "Administratörens kommentarer om användaren";
$pgv_lang["comment_exp"]			= "Adminstratörvarning utfärdad den";
$pgv_lang["configuration"]		= "Konfiguration";
$pgv_lang["confirm_user_delete"]= "Är det säkert att du vill ta bort denna användare";
$pgv_lang["create_user"]		= "Skapa ny användare";
$pgv_lang["dataset_exists"]		= "En GEDCOM-fil med detta namn är redan importerad i databasen.";
$pgv_lang["day_before_month"]		= "Dag före månad (DD MM ÅÅÅÅ)";
$pgv_lang["do_not_change"]			= "Ändra inte";
$pgv_lang["download_gedcom"]	= "Ladda ner GEDCOM-fil";
$pgv_lang["download_note"]		= "NOTERING: Stora GEDCOM kan ta lång tid att processa innan nerladdning. Om PHP-tidsinställningen är för kort är det inte säkert att din nerladdning blir komplett.<br /><br />Du kan kontrollera din nerladdad GEDCOM-fil efter <b>0 TRLR</b> raden i slutet av filen för att försäkra dig om att filen är komplett. GEDCOM-filen är text, du kan använda en texteditor, men var försiktig så att du <u>inte</u> sparar GEDCOM-filen efter du kontrollerat den.<br /><br />Vanligtvis kan det ta lika lång tid att ladda ner som det tog att importera din GEDCOM.";
$pgv_lang["duplicate_username"]	= "Användarnamn upptaget.  En användare med det användarnamnet finns redan.  Gå tillbaka och välj ett annat namn och försök igen.";
$pgv_lang["editaccount"]		= "Tillåt användaren att redigera sin kontoinformation";
$pgv_lang["empty_dataset"]		= "Vill du radera den gamla datan och ersätta den med den nya datan?";
$pgv_lang["empty_lines_detected"]	= "Tomma rader upptäcktes i din GEDCOM-fil. Vid städning kommer dessa tomma rader att tas bort.";
$pgv_lang["error_ban_server"]       = "Felaktig IPadress.";
$pgv_lang["error_delete_person"]    = "Du måste välja person vars länk ska raderas.";
$pgv_lang["error_header_write"]	= "GEDCOM-filen, <b>[#GEDCOM#]</b>, är inte skrivbar. Kontrollera fil- och access-rättigheter.";
$pgv_lang["error_siteauth_failed"]	= "Kunde inte logga in på sajten";
$pgv_lang["error_url_blank"]		= "Lämna inte sajt titel eller URL tom";
$pgv_lang["error_view_info"]        = "Du måste välja personen som du vill visa information om.";
$pgv_lang["example_date"]			= "Exempel på ogiltigt datum från din GEDCOM-fil:";
$pgv_lang["example_place"]			= "Exempel på en felaktig ort från din GEDCOM:";
$pgv_lang["found_record"]		= "Hittade poster";
$pgv_lang["ged_import"]			= "Importera";
$pgv_lang["gedcom_downloadable"] 	= "Denna GEDCOM-fil är nerladdningsbar över internet!<br />Var vänlig läs Säkerhetssektionen i <a href=\"readme.txt\">readme.txt</a> för att rätta till problemet";
$pgv_lang["gedcom_file"]		= "GEDCOM-fil:";
$pgv_lang["img_admin_settings"]	= "Ändra bildmanipuleringskonfigurationen";
$pgv_lang["import_complete"]	= "Importen är färdig";
$pgv_lang["import_marr_names"]	= "Importera namn från vigsel";
$pgv_lang["import_options"]		= "Importeringsval";
$pgv_lang["import_progress"]	= "Import framsteg...";
$pgv_lang["import_statistics"]	= "Importeringsstatistik";
$pgv_lang["import_time_exceeded"]	= "Exekveringstidens gräns nåddes. Klicka på fortsätt nedan för att fortsätta importera GEDCOM-filen.";
$pgv_lang["inc_languages"]				= "Språk";
$pgv_lang["invalid_dates"]			= "Upptäckte ogiltigt datumformat, vid upprensning kommer formatet att ändras till DD MMM ÅÅÅÅ(t.ex. 1 JAN 2004).";
$pgv_lang["invalid_header"]		= "Upptäckt rader före GEDCOM-headern (0 HEAD). Dessa rader kommer att raderas vid städning.";
$pgv_lang["label_added_servers"]	= "Lagt till server";
$pgv_lang["label_banned_servers"]   = "Förbjud sajter via IP";
$pgv_lang["label_families"]         = "Familjer";
$pgv_lang["label_gedcom_id2"]       = "DatabasID:";
$pgv_lang["label_individuals"]      = "Personer";
$pgv_lang["label_manual_search_engines"]   = "Markera manuellt Sökmotorspindlar via IP-adress";
$pgv_lang["label_new_server"]       = "Lägg till ny sajt";
$pgv_lang["label_password_id"]		= "Lösenord";
$pgv_lang["label_remove_ip"]		= "Förbjud IPAdress(t.ex. 198.128.*.*): ";
$pgv_lang["label_remove_search"]	= "Markera IP-Adress som en sökmotorspindel: ";
$pgv_lang["label_server_info"]      = "Alla personer som är länkade från andra sajter:";
$pgv_lang["label_server_url"]       = "Sajt URL/IP";
$pgv_lang["label_username_id"]		= "Användarnamn";
$pgv_lang["label_view_local"]       = "Visa lokal information om person";
$pgv_lang["label_view_remote"]      = "Visa inforamtion från annan sajt om person";
$pgv_lang["link_manage_servers"]    = "Hantera sajter";
$pgv_lang["logfile_content"]	= "Innehåll i loggfil";
$pgv_lang["macfile_detected"]	= "Macintosh fil upptäckt. Filen kommer att konverteras till en DOS-fil vid städning.";
$pgv_lang["merge_records"]      = "Slå ihop poster";
$pgv_lang["month_before_day"]		= "Månad före dag (MM DD ÅÅÅÅ)";
$pgv_lang["none"]				= "Inga";
$pgv_lang["performing_validation"]	= "Genomför GEDCOM-validering...";
$pgv_lang["pgv_registry"]		= "Visa andra PhpGedView-sajter";
$pgv_lang["phpinfo"]				= "PHP Info";
$pgv_lang["place_cleanup_detected"]	= "Felaktig ortskodning upptäckt. Dessa fel bör rättas till. Följande prov visar den felaktiga orten som upptäcktes:";
$pgv_lang["please_be_patient"]	= "Var god vänta...";
$pgv_lang["reading_file"]		= "Läser GEDCOM-fil";
$pgv_lang["readme_documentation"]	= "README-dokumentation";
$pgv_lang["remove_ip"] 			= "Tabort IP-adress";
$pgv_lang["rootid"]				= "Startperson för antavla";
$pgv_lang["select_an_option"]	= "Välj alternativ nedan:";
$pgv_lang["siteadmin"]				= "Sajt-administratör";
$pgv_lang["skip_cleanup"]		= "Hoppa över städning";
$pgv_lang["time_limit"]				= "Tidsgräns:";
$pgv_lang["title_manage_servers"]   = "Hantera sajter";
$pgv_lang["title_view_conns"]       = "Visa kopplingar";
$pgv_lang["update_myaccount"]	= "Uppdatera mitt konto";
$pgv_lang["update_user"]		= "Uppdatera användarkonto";
$pgv_lang["upload_gedcom"]		= "Ladda upp GEDCOM-fil";
$pgv_lang["user_auto_accept"]		= "Acceptera automatiskt ändringar gjorda av denna användare";
$pgv_lang["user_contact_method"]= "Föredragen kontaktmetod";
$pgv_lang["user_create_error"]	= "Går inte att skapa användare.  Var snäll gå tillbaka och försök igen.";
$pgv_lang["user_created"]		= "Ny användare skapad.";
$pgv_lang["user_default_tab"]	= "Den flik som visas som standard på ansedelsidan";
$pgv_lang["valid_gedcom"]			= "Godkänd GEDCOM-fil upptäckt. Ingen rensning behövs.";
$pgv_lang["validate_gedcom"]	= "Validera GEDCOM-filen";
$pgv_lang["verified"]			= "Användaren verifierade sig själv";
$pgv_lang["verified_by_admin"]	= "Användare godkänd av admin";
$pgv_lang["verify_gedcom"]		= "Verifiera GEDCOM";
$pgv_lang["verify_upload_instructions"]	= "Om du väljer att fortsätta kommer den gamla GEDCOM-filen att ersättas med filen du har laddat upp och importprocessen kommer att börja igen. Om du väljer att avbryta kommer den gamla GEDCOM-filen att förbli oförändrad.";
$pgv_lang["view_changelog"]		= "Visa changelog.txt-filen";
$pgv_lang["view_logs"]			= "Visa loggfiler";
$pgv_lang["view_readme"]			= "Visa readme.txt-fil";
$pgv_lang["visibleonline"]		= "Synlig för andra användare när du är online";
$pgv_lang["visitor"]				= "Besökare";
$pgv_lang["you_may_login"]		= " av sajt administratören. Du kan nu logga in på PhpGedView sajten genom att följa länken nedan:";


?>