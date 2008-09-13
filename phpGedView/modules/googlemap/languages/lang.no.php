<?php
/*=================================================
   charset=  utf-8
   Project:  phpGedView
   File:     lang.no.php
   Author:   Johan Borkhuis
   Comments: Norwegian Language file for Google map module
===================================================*/

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$pgv_lang["googlemap"]              = "Kart";
$pgv_lang["no_gmtab"]               = "Det er ingen kartreferanser knyttet til denne personen";
$pgv_lang["gm_disabled"]            = "Modulen Google Map er ikke aktivert";

$pgv_lang['gm_redraw_map']          = "Tegn kartet på nytt";
$pgv_lang["gm_map"]                 = "Kart";
$pgv_lang["gm_satellite"]           = "Satelitt";
$pgv_lang["gm_hybrid"]              = "Kombinert";

// Configuration texts
$pgv_lang["gm_manage"]              = "Innstillinger for Google Map";
$pgv_lang["configure_googlemap"]    = "Google Map innstillinger";
$pgv_lang["gm_admin_error"]         = "Side bare for administratorer";
$pgv_lang["gm_db_error"]            = "Fant ikke tabellen for kartreferanser til steder i databasen";
$pgv_lang["gm_table_created"]       = "Tabell opprettet for kartreferanser til steder";
$pgv_lang["googlemap_enable"]       = "Aktivere Google Map";
$pgv_lang["googlemapkey"]           = "Nøkkel til Google Map API";
$pgv_lang["gm_map_type"]            = "Standard karttype";
$pgv_lang["gm_map_size"]            = "Størrelse på kartet (i punkter)";
$pgv_lang["gm_map_size_x"]          = "Bredde";
$pgv_lang["gm_map_size_y"]          = "Høyde";
$pgv_lang["gm_map_zoom"]            = "Zoom-faktor for kart";
$pgv_lang["gm_digits"]              = "siffer";
$pgv_lang["gm_min"]                 = "Min.";
$pgv_lang["gm_max"]                 = "Maks";
$pgv_lang["gm_default_level0"]      = "Standard verdi for toppnivå";
$pgv_lang["gm_nof_levels"]          = "Antall nivå";
$pgv_lang["gm_config_per_level"]    = "Configuration per level";
$pgv_lang["gm_name_prefix"]         = "Prefiks";
$pgv_lang["gm_name_postfix"]        = "Postfiks";
$pgv_lang["gm_name_pre_post"]       = "Rekkefølge for pre-/postfiks";
$pgv_lang["gm_level"]               = "Nivå";
$pgv_lang["gm_pp_none"]             = "Ingen pre-/postfiks";
$pgv_lang["gm_pp_n_pr_po_b"]        = "Normal, prefiks, postfiks, begge";
$pgv_lang["gm_pp_n_po_pr_b"]        = "Normal, postfiks, prefiks, begge";
$pgv_lang["gm_pp_pr_po_b_n"]        = "Prefiks, postfiks, begge, normal";
$pgv_lang["gm_pp_po_pr_b_n"]        = "Postfiks, prefiks, begge, normal";
$pgv_lang["gm_pp_pr_po_n_b"]        = "Prefiks, postfiks, normal, begge";
$pgv_lang["gm_pp_po_pr_n_b"]        = "Postfiks, prefiks, normal, begge";

// Texts used on the Places location page
$pgv_lang["edit_place_locations"]   = "Rediger kartreferanser til steder";
$pgv_lang["pl_no_places_found"]     = "Fant ingen steder";
$pgv_lang["pl_zoom_factor"]         = "Zoom-faktor";
$pgv_lang["pl_place_icon"]          = "Ikon";
$pgv_lang["pl_edit"]                = "Rediger kartreferanse";
$pgv_lang["pl_add_place"]           = "Legg til sted";
$pgv_lang["pl_import_gedcom"]       = "Importer fra aktiv slektsbase (GED)";
$pgv_lang["pl_import_all_gedcom"]   = "Importer fra alle slektsbaser (GED)";
$pgv_lang["pl_import_file"]         = "Importer fra fil";
$pgv_lang["pl_export_file"]         = "Eksporter disse til en fil";
$pgv_lang["pl_export_all_file"]     = "Eksporter alle kartreferanser til en fil";
$pgv_lang["pl_north_short"]         = "N";
$pgv_lang["pl_south_short"]         = "S";
$pgv_lang["pl_east_short"]          = "Ø";
$pgv_lang["pl_west_short"]          = "V";
$pgv_lang["pl_places_filename"]     = "Fil som inneholder steder (CSV)";
$pgv_lang["pl_clean_db"]            = "Fjerne alle lagrede kartreferanser til steder før import?";
$pgv_lang["pl_update_only"]         = "Oppdatere bare steder som finnes fra før?";
$pgv_lang["pl_overwrite_data"]      = "Overskrive kartreferanser med data fra filen?";
$pgv_lang["pl_no_places_found"]     = "Fant ingen steder";
$pgv_lang["pl_use_this_value"]      = "Bruk denne verdien";
$pgv_lang["pl_precision"]           = "Nøyaktighet";
$pgv_lang["pl_country"]             = "Land";
$pgv_lang["pl_state"]               = "Fylke";
$pgv_lang["pl_city"]                = "Sted";
$pgv_lang["pl_neighborhood"]        = "Nærområde";
$pgv_lang["pl_house"]               = "Hus";
$pgv_lang["pl_max"]                 = "Maks";

$pgv_lang["pl_flag"]                = "Flagg";
$pgv_lang["flags_edit"]             = "Velg flagg";
$pgv_lang["pl_change_flag"]         = "Bytt flagg";
$pgv_lang["pl_remove_flag"]         = "Fjern flagg";

$pgv_lang["pl_remove_location"]     = "Fjern dette stedet?";
$pgv_lang["pl_delete_error"]        = "Klarte ikke å fjerne stedet: Det er registrert tilhørende lokale steder";

?>
