<?php
/**
 * Finnish Language file for PhpGedView.
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
 * @package PhpGedView
 * @subpackage GoogleMap
 * @translator Jani Miettinen
 * @version $Id$
 */
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Et pääse suoraan kielitiedostoon.";
	exit;
}
$pgv_lang["autosearch_ssurname"] 	= "Lisää puolison sukunimi:";
$pgv_lang["autosearch_sgivennames"] = "Lisää puolison etunimet:";
$pgv_lang["autosearch_plugin_name_gensearchhelp"] = "Genealogy-Search-Help.com lisäke";

$pgv_lang["googlemap"]              = "Kartta";
$pgv_lang["no_gmtab"]               = "Ei kartta tietoa tällä henkilöllä";
$pgv_lang["gm_disabled"]            = "GoogleKartta moduli estetty";

$pgv_lang["gm_redraw_map"]          = "Näytä kartta uudelleen";
$pgv_lang["gm_map"]                 = "Kartta";
$pgv_lang["gm_satellite"]           = "Satelliitti";
$pgv_lang["gm_hybrid"]              = "Hybridi";

// Configuration texts
$pgv_lang["gm_manage"]              = "Hallitse GoogleKartta konfiguraatiota";
$pgv_lang["configure_googlemap"]    = "GoogleKartta konfiguraatio";
$pgv_lang["gm_admin_error"]         = "Sivu vain ylläpitäjille";
$pgv_lang["gm_db_error"]            = "paikkasijainti taulu ei löydy tietokannasta";
$pgv_lang["gm_table_created"]       = "paikkasijainti taulu luotu";
$pgv_lang["googlemap_enable"]       = "Ota käyttöön GoogleKartta";
$pgv_lang["googlemapkey"]           = "GoogleKartta API avain";
$pgv_lang["gm_map_type"]            = "Oletus kartta tyyppi";
$pgv_lang["gm_map_size"]            = "Kartan koko (pikseleinä)";
$pgv_lang["gm_map_size_x"]          = "Leveys";
$pgv_lang["gm_map_size_y"]          = "Korkeus";
$pgv_lang["gm_map_zoom"]            = "Kartan zoomaus kerroin";
$pgv_lang["gm_digits"]              = "taso"; //"luku";
$pgv_lang["gm_min"]                 = "Min.";
$pgv_lang["gm_max"]                 = "Max.";
$pgv_lang["gm_default_level0"]      = "Vakio ylätason arvo";
$pgv_lang["gm_nof_levels"]          = "Tasojen määrä";
$pgv_lang["gm_config_per_level"]    = "Tasojen konfigurointi";
$pgv_lang["gm_name_prefix"]         = "Etuliite";
$pgv_lang["gm_name_postfix"]        = "Jälkiliite";
$pgv_lang["gm_name_pre_post"]       = "Etuliite / jälkiliite järjestys";
$pgv_lang["gm_level"]               = "Taso";
$pgv_lang["gm_pp_none"]             = "Ei etu-/jälkiliitettä";
$pgv_lang["gm_pp_n_pr_po_b"]        = "Normaali, etuliite, jälkiliite, kumpikin";
$pgv_lang["gm_pp_n_po_pr_b"]        = "Normaali, jälkiliite, etuliite, kumpikin";
$pgv_lang["gm_pp_pr_po_b_n"]        = "Etuliite, jälkiliite, kumpikin, normaali";
$pgv_lang["gm_pp_po_pr_b_n"]        = "Jälkiliite, etuliite, kumpikin, normaali";
$pgv_lang["gm_pp_pr_po_n_b"]        = "Etuliite, jälkiliite, normal, kumpikin";
$pgv_lang["gm_pp_po_pr_n_b"]        = "Jälkiliite, etuliite, normal, kumpikin";
$pgv_lang["googlemap_coord"]        = "Näytä kartta koordinaatit";

// Texts used on the Places location page
$pgv_lang["edit_place_locations"]   = "Muokkaa sijainti paikka tietoja";
$pgv_lang["pl_no_places_found"]     = "Ei paikkoja lyötynyt";
$pgv_lang["pl_zoom_factor"]         = "Zoomaus kerroin";
$pgv_lang["pl_place_icon"]          = "Ikoni";
$pgv_lang["pl_edit"]                = "Muokkaa paikan sijaintia";
$pgv_lang["pl_add_place"]           = "Lisää paikka";
$pgv_lang["pl_import_gedcom"]       = "Tuo nykyisestä GEDCOM:sta";
$pgv_lang["pl_import_all_gedcom"]   = "Tuo kaikista GEDCOM:eista";
$pgv_lang["pl_import_file"]         = "Tuo tiedostosta";
$pgv_lang["pl_export_file"]         = "Vie nykyinen näkymä tiedostoon";
$pgv_lang["pl_export_all_file"]     = "Vie kaikki sijainnit tiedostoon";
$pgv_lang["pl_north_short"]         = "P";
$pgv_lang["pl_south_short"]         = "E";
$pgv_lang["pl_east_short"]          = "I";
$pgv_lang["pl_west_short"]          = "L";
$pgv_lang["pl_places_localfile"]	= "Palvelintiedosto, joka sisältää paikkoja (CSV)";
$pgv_lang["pl_places_filename"]     = "Tiedosto sisältää paikkoja (CSV)";
$pgv_lang["pl_clean_db"]            = "Siivoa kaikki paikkatiedot ennen tuontia?";
$pgv_lang["pl_update_only"]         = "Päivitä vain olemassaolevat paikat?";
$pgv_lang["pl_overwrite_data"]      = "Kirjoita paikkatiedot yli tiedoston tiedoilla?";
$pgv_lang["pl_use_this_value"]      = "Käytä tätä arvoa";
$pgv_lang["pl_precision"]           = "Tarkkuus";
$pgv_lang["pl_country"]             = "Maa";
$pgv_lang["pl_state"]               = "Lääni";
$pgv_lang["pl_city"]                = "Kaupunki";
$pgv_lang["pl_neighborhood"]        = "Naapurusto";
$pgv_lang["pl_house"]               = "Talo";
$pgv_lang["pl_max"]                 = "Maksimi";
$pgv_lang["pl_delete"]              = "Poista sijaintipaikka";

$pgv_lang["pl_flag"]                = "Lippu";
$pgv_lang["flags_edit"]             = "Valitse lippu";
$pgv_lang["pl_change_flag"]         = "Vaihda lippu";
$pgv_lang["pl_remove_flag"]         = "Poista lippu";

$pgv_lang["pl_remove_location"]     = "Poista tämä sijainti?";
$pgv_lang["pl_delete_error"]        = "Sijaintia ei poistettu: tämä sijainti sisältää alikohteita";
$pgv_lang["list_inactive"]        	= "Klikkaa tässä nähdäksesi joutilaat paikat";

//Placecheck specific text
$pgv_lang["placecheck"]				= "Paikka Tarkistus";
$pgv_lang["placecheck_text"]		= "Tämä listaa kaikki paikat valitusta GEDCOM-tiedostosta. Oletuksena tämä ei sisällytä paikkoja, jotka täysin vastaavat GEDCOM tiedostoa ja GoogleKartta tauluja";
$pgv_lang["placecheck_top"]			= "Ylimmän tason paikat";
$pgv_lang["placecheck_one"]			= "Tason yksi paikat";
$pgv_lang["placecheck_select1"]		= "Valitse ylin taso...";
$pgv_lang["placecheck_select2"]		= "Valitse seuraava taso...";
$pgv_lang["placecheck_key"]			= "Avain värit joita käytetään";
$pgv_lang["placecheck_key1"]		= "tämä paikka ja sen koordinaatit ei löydy GoogleKartta taulusta";
$pgv_lang["placecheck_key2"]		= "tämä paikka on GoogleKartta taulussa, mutta ei sisällä koordinaattia";
$pgv_lang["placecheck_key3"]		= "Tämä paikka taso on tyhjä GEDCOM tiedostossasi. Se olisi hyvä lisätä <br/>GoogleKartta paikkoihin \"tuntematon\" koordinaattien kanssa edelliseltä<br/>tasolta ennen kuin lisäät paikkoja seuraavalle tasolle.";
$pgv_lang["placecheck_key4"]		= "tämä paikka taso on tyhjä sinun GEDCOM tiedostossasi, mutta esillä 'tuntematon'<br/> GoogleKartta paikka taulussa koordinaattien kera. Ei ei vaadi toimintaa<br/> ennen kuin kadoksissa oleva taso voidaan lisätä";
$pgv_lang["placecheck_head"]		= "Paikka lista GEDCOM tiedostosta";
$pgv_lang["placecheck_gedheader"]	= "GEDCOM tiedosto paikka data<br/>(2 PLAC tag)";
$pgv_lang["placecheck_gm_header"]	= "GoogleKartta Paikka taulu data";
$pgv_lang["placecheck_unique"]		= "yhteensä yksilöllisiä paikkoja";
$pgv_lang["placecheck_zoom"]        = "Zoomaus=";
$pgv_lang["placecheck_options"]     = "Paikkatarkistus lista valinnat";
$pgv_lang["placecheck_filter_text"] = "Lista suodatus valinnat";
$pgv_lang["placecheck_match"] 		= "Sisällytä täysin vastaavat paikat -";
?>