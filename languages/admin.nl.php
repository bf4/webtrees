<?php
/**
 * Dutch texts
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
 * @package PhpGedView
 * @author Eduard Wustenveld
 * @author Erik Bent
 * @author Boudewijn Sjouke
 * @subpackage Languages
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

$pgv_lang["user"]						= "Aangemelde gebruiker";
$pgv_lang["thumbnail_deleted"]		= "Miniweergave verwijderd.";
$pgv_lang["thumbnail_not_deleted"]	= "Miniweergave kan niet worden verwijderd.";
$pgv_lang["step2"]						= "Stap 2 van 4:";
$pgv_lang["refresh"]					= "Ververs";
$pgv_lang["move_file_success"]			= "Multimedia- en miniweergavebestanden succesvol verplaatst.";
$pgv_lang["media_folder_corrupt"]		= "De multimediamap is corrupt.";
$pgv_lang["media_file_not_deleted"]	= "Mediabestand kan niet worden verwijderd.";
$pgv_lang["gedcom_deleted"]				= "GEDCOM [#GED#] verwijderd.";
$pgv_lang["gedadmin"]					= "GEDCOM-beheerder";
$pgv_lang["full_name"]					= "Volledige naam";
$pgv_lang["error_header"]				= "Het GEDCOM-bestand, [#GEDCOM#], kan niet worden gevonden op de aangegeven plaats.";
$pgv_lang["confirm_delete_file"]	= "Bestand verwijderen?";
$pgv_lang["confirm_folder_delete"] = "Map verwijderen?";
$pgv_lang["confirm_remove_links"]	= "Alle koppelingen naar dit object verwijderen?";
$pgv_lang["manage_gedcoms"]				= "Beheer GEDCOM-bestanden en privacy-instellingen";
$pgv_lang["created_remotelinks"]	= "Maken van tabel <i>Remotelinks</i> geslaagd.";
$pgv_lang["created_remotelinks_fail"] 	= "De tabel <i>Remotelinks</i> kan niet worden aangemaakt.";
$pgv_lang["created_indis"]				= "Maken van tabel <i>Individuals</i> geslaagd.";
$pgv_lang["created_indis_fail"]			= "De tabel <i>Individuals</i> kan niet worden aangemaakt.";
$pgv_lang["created_fams"]				= "Maken van tabel <i>Families</i> geslaagd.";
$pgv_lang["created_fams_fail"]			= "De tabel <i>Families</i> kan niet worden aangemaakt.";
$pgv_lang["created_sources"]			= "Maken van tabel <i>Sources</i> geslaagd.";
$pgv_lang["created_sources_fail"]		= "De tabel <i>Sources</i> kan niet worden aangemaakt.";
$pgv_lang["created_other"]				= "Maken van tabel <i>Other</i> geslaagd.";
$pgv_lang["created_other_fail"]			= "De tabel <i>Other</i> kan niet worden aangemaakt.";
$pgv_lang["created_places"]				= "Maken van tabel <i>Places</i> geslaagd.";
$pgv_lang["created_places_fail"]		= "De tabel <i>Places</i> kan niet worden aangemaakt.";
$pgv_lang["created_placelinks"] 	= "Maken van tabel <i>Placelinks</i> geslaagd.";
$pgv_lang["created_placelinks_fail"]	= "De tabel <i>placelinks</i> kan niet worden aangemaakt.";
$pgv_lang["created_media_fail"]	= "De tabel <i>media</i> kan niet worden aangemaakt.";
$pgv_lang["created_media_mapping_fail"]	= "De tabel <i>media mappings</i> kan niet worden aangemaakt.";
$pgv_lang["no_thumb_dir"]				= " map voor miniweergaves bestaat niet en kan niet worden aangemaakt";
$pgv_lang["move_to"]					= "Verplaats naar -->";
$pgv_lang["folder_created"]				= "Map gemaakt";
$pgv_lang["folder_no_create"]		= "Map kan niet worden aangemaakt";
$pgv_lang["security_no_create"]			= "Beveiligingswaarschuwing: index.php bestaat niet in ";
$pgv_lang["security_not_exist"]			= "Beveiligingswaarschuwing: kan index.php niet aanmaken in ";
$pgv_lang["label_add_search_server"]	= "IP-Adres toevoegen";
$pgv_lang["label_add_server"]       	= "Toevoegen";
$pgv_lang["label_ban_server"]       	= "Blokkeer&gt;&gt;";
$pgv_lang["label_delete"]           	= "Verwijder";
$pgv_lang["add_gedcom"]					= "GEDCOM-bestand toevoegen";
$pgv_lang["add_new_gedcom"]				= "Nieuw GEDCOM-bestand maken";
$pgv_lang["admin_approved"]				= "Uw aanmelding op #SERVER_NAME# is goedgekeurd";
$pgv_lang["admin_gedcom"]				= "Beheren";
$pgv_lang["admin_geds"]					= "Beheer GEDCOM's en gegevens";
$pgv_lang["admin_info"]					= "Informatie";
$pgv_lang["admin_site"]					= "Beheer website";
$pgv_lang["administration"]				= "Beheer";
$pgv_lang["ansi_encoding_detected"]		= "ANSI bestandscodering geconstateerd. PhpGedView werkt het best met bestanden gecodeerd volgens UTF-8.";
$pgv_lang["ansi_to_utf8"]				= "Converteer dit GEDCOM-bestand van ANSI (ISO-8859-1) naar UTF-8?";
$pgv_lang["apply_privacy"]				= "Privacy-instellingen toepassen?";
$pgv_lang["bytes_read"]					= "Bytes gelezen:";
$pgv_lang["calc_marr_names"]			= "Bepalen huwelijkse achternamen";
$pgv_lang["change_id"]					= "Wijzig persoons-ID in:";
$pgv_lang["choose_priv"]				= "Kies privacy-niveau:";
$pgv_lang["cleanup_places"]				= "Opschonen locaties";
$pgv_lang["click_here_to_go_to_pedigree_tree"] = "Klik hier om naar de kwartierstaat te gaan.";
$pgv_lang["comment"]					= "Commentaar beheerder";
$pgv_lang["comment_exp"]				= "Waarschuw beheerder op datum";
$pgv_lang["configuration"]				= "Instellingen";
$pgv_lang["confirm_user_delete"]		= "Weet u zeker dat u deze gebruiker wilt verwijderen?";
$pgv_lang["create_user"]				= "Gebruiker maken";
$pgv_lang["dataset_exists"]				= "Een GEDCOM-bestand met deze naam staat reeds in de database.";
$pgv_lang["day_before_month"]			= "Dag voor maand (DD MM YYYY)";
$pgv_lang["do_not_change"]				= "Niet wijzigen";
$pgv_lang["download_gedcom"]			= "Download GEDCOM-bestand";
$pgv_lang["download_note"]				= "LET OP: Bij grote GEDCOM-bestanden kan het lang duren voordat de download gereed is.<br />De toegestane limiet voor de uitvoeringstijd van een PHP-pagina kan hierdoor worden overschreden.<br />Controleer daarom altijd of het gedownloade GEDCOM-bestand als laatste regel '0 TRLR' bevat.";
$pgv_lang["editaccount"]				= "Sta gebruiker toe om gebruikersgegevens te wijzigen";
$pgv_lang["empty_dataset"]				= "Wilt u de oude gegevens verwijderen en de gegevens uit het nieuwe GEDCOM-bestand toevoegen?";
$pgv_lang["empty_lines_detected"]		= "Lege regels ontdekt in uw GEDCOM-bestand. Bij het opschonen worden deze verwijderd.";
$pgv_lang["error_ban_server"]       = "Selecteer de site die u wilt blokkeren.";
$pgv_lang["error_delete_person"]    = "Selecteer de persoon waarvan de externe koppeling verwijderd moet worden.";
$pgv_lang["error_header_write"]			= "Schrijfrechten benodigd op het GEDCOM-bestand [#GEDCOM#], controleer attributen en toegangsrechten.";
$pgv_lang["error_siteauth_failed"]	= "Aanmelden bij gekoppelde site niet gelukt";
$pgv_lang["error_url_blank"]		= "Gekoppelde site naam of URL niet leeg laten";
$pgv_lang["error_view_info"]        = "Selecteer de persoon waarvan de informatie moet worden getoond.";
$pgv_lang["example_date"]				= "Voorbeeld van een ongeldige datum in uw GEDCOM-bestand:";
$pgv_lang["example_place"]			= "Voorbeeld van een foutieve locatieaanduiding in uw GEDCOM-bestand:";
$pgv_lang["found_record"]				= "Record gevonden";
$pgv_lang["ged_import"]					= "Importeer";
$pgv_lang["gedcom_config_write_error"]	= "Fout! Kan niet schrijven naar GEDCOM-configuratiebestand.";
$pgv_lang["gedcom_downloadable"]		= "Dit GEDCOM-bestand kan worden gedownload vanaf het Internet!<br />Lees de SECURITY-sectie in het <a href=\"readme.txt\">readme.txt</a>-bestand om dit probleem te verhelpen.";
$pgv_lang["gedcom_file"]				= "GEDCOM-bestand";
$pgv_lang["img_admin_settings"]			= "Configureren bewerking afbeeldingen";
$pgv_lang["import_complete"]			= "Import gereed";
$pgv_lang["import_marr_names"]			= "Importeren achternamen in huwelijk";
$pgv_lang["import_options"]		= "Importopties";
$pgv_lang["import_progress"]			= "Voortgang importeren....";
$pgv_lang["import_statistics"]	= "Importeren statistieken";
$pgv_lang["import_time_exceeded"]	= "De maximale uitvoeringstijd is bereikt. Druk op de knop \"Doorgaan\" om de import van het GEDCOM-bestand voort te zetten.";
$pgv_lang["inc_languages"]				= "Talen";
$pgv_lang["invalid_dates"]				= "Ongeldig datumformaat gevonden. Bij het opschonen worden deze datums gewijzigd in het formaat DD MMM YYYY (bijvoorbeeld 1 JAN 2004).";
$pgv_lang["invalid_header"]				= "GEDCOM-regels aanwezig voor de kopregel (0 HEAD). Deze worden bij het opschonen verwijderd.";
$pgv_lang["label_added_servers"]	= "Toegevoegde gekoppelde servers";
$pgv_lang["label_banned_servers"]   	= "Geblokkeerde sites";
$pgv_lang["label_families"]         	= "Gezinnen";
$pgv_lang["label_gedcom_id2"]       = "GEDCOM-ID:";
$pgv_lang["label_individuals"]      	= "Personen";
$pgv_lang["label_manual_search_engines"]   = "Handmatig zoekmachines markeren op basis van IP adres";
$pgv_lang["label_new_server"]       	= "Site toevoegen";
$pgv_lang["label_password_id"]		= "Wachtwoord";
$pgv_lang["label_remove_ip"]		= "Blokkeer IP adres: ";
$pgv_lang["label_remove_search"]	= "Markeer IP adres als Zoekmachine Spin";
$pgv_lang["label_server_info"]      = "Alle personen gekoppeld via deze site:";
$pgv_lang["label_server_url"]       	= "URL/IP";
$pgv_lang["label_username_id"]		= "Gebruikersnaam";
$pgv_lang["label_view_local"]       = "Bekijk lokale informatie van een persoon";
$pgv_lang["label_view_remote"]      = "Bekijk gekoppelde informatie van een persoon";
$pgv_lang["link_manage_servers"]    	= "Site-koppelingen beheren";
$pgv_lang["logfile_content"]			= "Inhoud van log-bestand";
$pgv_lang["macfile_detected"]			= "Het bestand is in Macintosh-formaat. Bij het opschonen wordt het omgezet naar DOS-formaat.";
$pgv_lang["merge_records"]				= "Gegevens samenvoegen";
$pgv_lang["month_before_day"]			= "Maand voor dag (MM DD YYYY)";
$pgv_lang["performing_validation"]		= "GEDCOM-validatie wordt uitgevoerd, selecteer de benodigde opties en klik op \"Opschonen\"";
$pgv_lang["pgv_registry"]				= "Andere websites met PhpGedView";
$pgv_lang["phpinfo"]					= "Toon PHP-informatie";
$pgv_lang["place_cleanup_detected"]		= "Foutieve locatiecoderingen aanwezig. Deze fouten moeten hersteld worden. Het volgende bestandsdeel geeft de foutieve locatiecodering aan:";
$pgv_lang["please_be_patient"]			= "EVEN GEDULD A.U.B.";
$pgv_lang["reading_file"]				= "GEDCOM-bestand inlezen";
$pgv_lang["readme_documentation"]		= "README documentatie";
$pgv_lang["remove_ip"] 			= "Verwijder IP adres";
$pgv_lang["rootid"]						= "Startpersoon van de kwartierstaat";
$pgv_lang["select_an_option"]			= "Selecteer een optie:";
$pgv_lang["siteadmin"]					= "Site-beheerder";
$pgv_lang["skip_cleanup"]				= "Opschonen overslaan";
$pgv_lang["time_limit"]					= "Tijdslimiet:";
$pgv_lang["title_manage_servers"]   	= "Beheer sites";
$pgv_lang["title_view_conns"]       = "Bekijk verbindingen";
$pgv_lang["update_myaccount"]			= "Mijn gegevens bijwerken";
$pgv_lang["update_user"]				= "Aanpassen gebruiker";
$pgv_lang["upload_gedcom"]				= "Upload GEDCOM-bestand";
$pgv_lang["user_auto_accept"]		= "Accepteer wijzigingen van deze gebruiker automatisch";
$pgv_lang["user_contact_method"]		= "Gewenste verzendwijze";
$pgv_lang["user_create_error"]			= "Het is niet mogelijk om de gebruiker toe te voegen. Probeer het opnieuw.";
$pgv_lang["user_created"]				= "Gebruiker gemaakt.";
$pgv_lang["user_default_tab"]			= "Standaard tabblad voor persoonspagina";
$pgv_lang["valid_gedcom"]				= "Geldig GEDCOM-bestand gevonden. Opschonen is niet noodzakelijk.";
$pgv_lang["validate_gedcom"]			= "Valideren GEDCOM-bestand";
$pgv_lang["verified"]					= "Registratie bevestigd";
$pgv_lang["verified_by_admin"]			= "Registratie goedgekeurd";
$pgv_lang["verify_gedcom"]		= "Gedcom verifieren";
$pgv_lang["verify_upload_instructions"]		= "Als u kiest om door te gaan, wordt het oude GEDCOM-bestand overschreven door het GEDCOM-bestand dat u heeft ge-upload, en begint het importeren opnieuw. Als u Annuleren kiest blijft het oude GEDCOM-bestand intact.";
$pgv_lang["view_changelog"]				= "Versie #VERSION# aanpassingen";
$pgv_lang["view_logs"]					= "Bekijk log-bestanden";
$pgv_lang["view_readme"]				= "README documentatie";
$pgv_lang["visibleonline"]				= "Zichtbaar voor anderen als aangemeld";
$pgv_lang["visitor"]					= "Bezoeker";
$pgv_lang["you_may_login"]				= " door de Beheerder. U kunt nu aanloggen op de website door op de onderstaande koppeling te klikken.";


?>
