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
if (preg_match("/admin\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
	print "Du har ikke direkte adgang til en sprogfil.";
	exit;
}

$pgv_lang["thumbnail_deleted"]		= "Miniaturebilledet blev slettet.";
$pgv_lang["thumbnail_not_deleted"]	= "Miniaturebilledet kunne ikke slettes.";
$pgv_lang["step2"]					= "Del 2 af 4:";
$pgv_lang["refresh"]				= "Opdater";
$pgv_lang["move_file_success"]		= "Mediefiler og miniaturebilleder blev flyttet.";
$pgv_lang["media_folder_corrupt"]	= "Folderen med mediefiler er ødelagt.";
$pgv_lang["media_file_not_deleted"]	= "Mediefil kunne ikke slettes.";
$pgv_lang["gedcom_deleted"]		= "GEDCOM [#GED#] er nu slettet.";
$pgv_lang["gedadmin"]				= "GEDCOM administrator";
$pgv_lang["full_name"]				= "Fuldt navn";
$pgv_lang["error_header"] 		= "GEDCOM-filen [#GEDCOM#], findes ikke på det angivne sted.";
$pgv_lang["confirm_delete_file"]	= "Er du sikker på at du vil slette denne fil?";
$pgv_lang["confirm_folder_delete"] = "Er du sikker på at du vil slette denne folder?";
$pgv_lang["manage_gedcoms"]			= "Slægtsfiler og privatliv";
$pgv_lang["created_remotelinks"]	= "Oprettede tabel over <i>Eksterne links</i>.";
$pgv_lang["created_remotelinks_fail"] 	= "Kunne ikke oprette tabel over <i>Eksterne links</i>.";
$pgv_lang["created_indis"]		= "Oprettede tabellen <i>Personer</i>.";
$pgv_lang["created_indis_fail"]	= "Kunne ikke oprette tabellen <i>Personer</i>!";
$pgv_lang["created_fams"]		= "Oprettede tabellen <i>Familier</i>.";
$pgv_lang["created_fams_fail"]	= "Kunne ikke oprette tabellen <i>Familier</i>!";
$pgv_lang["created_sources"]	= "Oprettede tabellen <i>Kilder</i>.";
$pgv_lang["created_sources_fail"]	= "Kunne ikke oprette tabellen <i>Kilder</i>!";
$pgv_lang["created_other"]		= "Oprettede tabellen <i>Andet</i>.";
$pgv_lang["created_other_fail"]	= "Kunne ikke oprette tabellen <i>Andet</i>!";
$pgv_lang["created_places"]		= "Oprettede tabellen <i>Steder</i>.";
$pgv_lang["created_places_fail"]	= "Kunne ikke oprette <i>Stednavne</i> tabel.";
$pgv_lang["created_placelinks"] 	= "Tabel over <i>stednavne links</i> blev table.";
$pgv_lang["created_media_fail"]	= "Kunne ikke oprette <i>Medie</i>-tabel.";
$pgv_lang["created_media_mapping_fail"]	= "Kunne ikke oprette <i>Medie mappings</i> tabel.";
$pgv_lang["no_thumb_dir"]			= " folder til miniaturebilleder findes ikke og den kunne ikke oprettes.";
$pgv_lang["move_to"]				= "Flyt til";
$pgv_lang["folder_created"]		= "Folder oprettet";
$pgv_lang["security_no_create"]		= "Sikkerhedsadvarsel: Filen <b><i>index.php</i></b> findes ikke i ";
$pgv_lang["security_not_exist"]		= "Sikkerhedsadvarsel: Kunne ikke oprette filen <b><i>index.php</i></b> i ";
$pgv_lang["label_add_server"]       = "Tilføj";
$pgv_lang["label_ban_server"] = "Indsend";
$pgv_lang["label_delete"]           = "Slet";
$pgv_lang["add_gedcom"]			= "Tilføj en GEDCOM-fil";
$pgv_lang["add_new_gedcom"]			= "Opret en ny GEDCOM-fil";
$pgv_lang["admin_approved"]		= "Din konto hos #SERVER_NAME# er blevet godkendt";
$pgv_lang["admin_gedcom"]			= "Administrer GEDCOM-fil";
$pgv_lang["admin_geds"]				= "Administration af data og GEDCOM-fil";
$pgv_lang["admin_info"]				= "Information";
$pgv_lang["admin_site"]				= "Administration af websitet";
$pgv_lang["administration"]			= "Administration";
$pgv_lang["ansi_encoding_detected"]	= "Opdaget ANSI tekstkodning.  PhpGedView fungerer bedst med filer som er kodet med UTF-8.";
$pgv_lang["ansi_to_utf8"]			= "Konverter denne ANSI-kodede GEDCOM-fil til UTF-8?";
$pgv_lang["apply_privacy"]			= "Anvend privatlivsindstillinger?";
$pgv_lang["bytes_read"]			= "Bytes læst:";
$pgv_lang["calc_marr_names"]		= "Kopierer ægtemændenes navn";
$pgv_lang["change_id"]				= "Ret person ID til:";
$pgv_lang["choose_priv"]			= "Vælg niveau mht. privatliv:";
$pgv_lang["cleanup_places"]			= "Ryd op i steder";
$pgv_lang["click_here_to_go_to_pedigree_tree"] = "Klik her for at gå til anetræet.";
$pgv_lang["comment"]				= "Admin kommentarer vedr. bruger";
$pgv_lang["comment_exp"]			= "Admin advarsel på datoen";
$pgv_lang["configuration"]			= "Programindstillinger";
$pgv_lang["confirm_user_delete"]	= "Er du sikker på at du vil slette brugeren";
$pgv_lang["create_user"]			= "Opret bruger";
$pgv_lang["dataset_exists"]		= "Der er allerede importeret en GEDCOM-fil i databasen med navnet ";
$pgv_lang["day_before_month"]		= "Dag før måned (DD MM ÅÅÅÅ)";
$pgv_lang["do_not_change"]			= "Udfør ingen ændringer";
$pgv_lang["download_gedcom"]		= "Download GEDCOM";
$pgv_lang["download_note"]			= "NB! Store GEDCOM-filer kan tage lang tid at downloade. Hvis PHP giver besked om, at tiden er udløbet førend downloading er færdig, så kan det være, at du ikke har modtaget hele filen.  For at checke om den downloadede GEDCOM-fil er korrekt, kan du se om filen indeholder linien <b>0 TRLR</b> tilslut.  Som en tommelfingerregel vil det tage lige så lang tid at downloade GEDCOM-filen, som det tog at importere den (afhængig af hastigheden på din internetforbindelse).";
$pgv_lang["duplicate_username"]		= "NB!! Brugernavnet findes allerede. Gå tilbage og vælg et andet brugernavn.";
$pgv_lang["editaccount"]			= "Giv denne bruger rettighed til at ændre sin brugerkonto";
$pgv_lang["empty_dataset"]		= "Vil du tømme den nuværende slægtsdatabase og lægge data ind påny?";
$pgv_lang["empty_lines_detected"]	= "Opdaget tomme linier i din GEDCOM-fil.  Under oprydningen vil disse tomme linier blive fjernet.";
$pgv_lang["error_ban_server"]       = "Ugyldig IP-adresse.";
$pgv_lang["error_delete_person"]    = "Du skal vælge den person, hvis eksterne link du ønsker at slette.";
$pgv_lang["error_header_write"]	= "GEDCOM-filen [#GEDCOM#], er ikke skrivbar. Check attributter og adgangsrettigheder.";
$pgv_lang["error_siteauth_failed"]	= "Kunne ikke identificere ekstern site";
$pgv_lang["error_url_blank"]	     	= "Lad venligst ikke URL eller titlen på den eksterne site være tom";
$pgv_lang["error_view_info"]        = "Du skal vælge den person, hvis information du ønsker at se.";
$pgv_lang["example_date"]			= "Eksempel på ugyldig dato fra din GEDCOM-fil:";
$pgv_lang["example_place"]			= "Eksempel på ugyldig sted fra din GEDCOM-fil:";
$pgv_lang["found_record"]		= "Poster fundet";
$pgv_lang["ged_import"]			= "Importer";
$pgv_lang["gedcom_config_write_error"] = "FEJL! Kan ikke skrive til slægtsdatabasens (GEDCOM) konfigurationsfil.";
$pgv_lang["gedcom_downloadable"]	= "<br />Besøgende på din website kan downloade denne GEDCOM-fil!<br />Læs mere om det i readme.txt filen <a href=\"\".(file_exists('readme-dansk.txt')?\"readme-dansk.txt\":\"readme.txt\").\"\">readme\".(file_exists('readme-dansk.txt')?\"-dansk\":\"\").\".txt</a> i afsnittet 12. SIKKERHED / PRIVATLIV<br />for at finde en løsning på dette.";
$pgv_lang["gedcom_file"]			= "GEDCOM-fil:";
$pgv_lang["img_admin_settings"]		= "Ret indstillinger for billedbehandling";
$pgv_lang["import_complete"]	= "Import færdig";
$pgv_lang["import_marr_names"]		= "Importer vielsesnavn";
$pgv_lang["import_options"]		= "Import muligheder";
$pgv_lang["import_progress"]	= "Import igang...";
$pgv_lang["import_statistics"]	= "Import Statistik";
$pgv_lang["import_time_exceeded"]	= "Grænsen for eksekvering af importen blev nået.  Klik på knappen: Fortsæt herunder for at genoptage importen af GEDCOM-filen.";
$pgv_lang["inc_languages"]		= "Sprog";
$pgv_lang["invalid_dates"]			= "Opdaget ugyldig dato-format. Ved rettelse vil disse blive ændret til formatet DD MMM ÅÅÅÅ (f.eks. 1 JAN 2004).";
$pgv_lang["invalid_header"]			= "Opdaget at der er linier før startlinien (0 HEAD) i GEDCOM-filen.  Under oprydning vil disse linier blive fjernet.";
$pgv_lang["label_added_servers"]  	= "Tilføj ekstern site";
$pgv_lang["label_banned_servers"]   = "Udeluk sites efter IP-adresse";
$pgv_lang["label_families"]         = "Familier";
$pgv_lang["label_gedcom_id2"]       = "GEDCOM-ID:";
$pgv_lang["label_individuals"]      = "Personer";
$pgv_lang["label_new_server"]       = "Tilføj ekstern site";
$pgv_lang["label_password_id"]	   	= "Adgangskode";
$pgv_lang["label_remove_ip"]	     	= "Udeluk IP-adresse (fx: 198.128.*.*): ";
$pgv_lang["label_server_info"]      = "Alle personer der er linket eksternt til via denne site:";
$pgv_lang["label_server_url"]       = "URL/IP-adresse";
$pgv_lang["label_username_id"]	   	= "Brugernavn";
$pgv_lang["label_view_local"]       = "Vis lokal information om person";
$pgv_lang["label_view_remote"]      = "Vis ekstern information om person";
$pgv_lang["link_manage_servers"]    = "Håndter sites";
$pgv_lang["logfile_content"]		= "Indhold i log-filen";
$pgv_lang["macfile_detected"]		= "Opdaget Macintosh-fil.  Under oprydning vil denne fil blive konverteret til en DOS-fil.";
$pgv_lang["merge_records"]		= "Flette poster (dobbeltregistrerede)";
$pgv_lang["month_before_day"]		= "Måned før dag (MM DD YYYY)";
$pgv_lang["none"]					= "Ingen";
$pgv_lang["performing_validation"]	= "Valideringen er udført...!  Foretag de nødvendige valg og klik derefter på 'Ryd'";
$pgv_lang["pgv_registry"]			= "Vis andre websites, der anvender PhpGedView";
$pgv_lang["phpinfo"]				= "PHPInfo";
$pgv_lang["place_cleanup_detected"]	= "Opdaget ugyldige stedkoder.  Disse bør ændres!  Følgende steder er ugyldige: ";
$pgv_lang["please_be_patient"]	= "VENT venligst...";
$pgv_lang["reading_file"]		= "Læser GEDCOM-filen";
$pgv_lang["readme_documentation"]	= "ReadMe-dokumentation (Engelsk)";
$pgv_lang["rootid"]					= "Proband ID";
$pgv_lang["select_an_option"]		= "Alternativer:";
$pgv_lang["siteadmin"]				= "Site administrator";
$pgv_lang["skip_cleanup"]		= "Skip oprydning...!?";
$pgv_lang["time_limit"]			= "Tidsgrænse";
$pgv_lang["title_manage_servers"]   = "Håndter sites";
$pgv_lang["title_view_conns"]       = "Vis forbindelser";
$pgv_lang["update_myaccount"]		= "Opdater Min konto";
$pgv_lang["update_user"]			= "Opdater brugerkonto";
$pgv_lang["upload_gedcom"]			= "Upload GEDCOM-fil(er)";
$pgv_lang["user_auto_accept"]		= "Accepter automatisk ændringer udført af denne bruger";
$pgv_lang["user_contact_method"]	= "Ønsket kontaktmetode";
$pgv_lang["user_create_error"]		= "Bruger kan ikke oprettes. Gå tilbage og prøv igen.";
$pgv_lang["user_created"]			= "Bruger er oprettet.";
$pgv_lang["user_default_tab"]		= "Fanebladet, der skal vises som standard på faktasiden for personer";
$pgv_lang["valid_gedcom"]			= "Gyldig GEDCOM-fil fundet. Det er ikke nødvendigt at foretage ændringer.";
$pgv_lang["validate_gedcom"]		= "Check kvaliteten af GEDCOM-filen";
$pgv_lang["verified"]			= "Bruger har<br />bekræftet ansøgningen";
$pgv_lang["verified_by_admin"]	= "Godkendt bruger<br />[af Admin]";
$pgv_lang["verify_gedcom"]		= "Verificer GEDCOM";
$pgv_lang["verify_upload_instructions"]	= "Hvis du vælger at fortsætte, vil den eksisterende GEDCOM-fil blive erstattet med den fil du har valgt at uploade. Den nye fil vil derefter blive importeret til PhpGedView.<br />Vælger du at afbryde, vil den eksisterende GEDCOM-fil forblive uændret.";
$pgv_lang["view_changelog"]			= "Læs filen changelog.txt";
$pgv_lang["view_logs"]				= "Vis log-fil ";
$pgv_lang["view_readme"]			= "Læs readme.txt filen";
$pgv_lang["visibleonline"]			= "Vis brugere der er logget ind";
$pgv_lang["visitor"]				= "Gæst";
$pgv_lang["you_may_login"]		= " af administratoren til websitet.<br />Du kan nu logge dig ind på websitet ved at klikke på linket nedenfor:";


?>