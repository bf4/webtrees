<?php
/**
 * French Language file for Google map module
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2006  John Finlay and Others
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
 * @version $Id$
 */
if (preg_match("/lang\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
	print "You cannot access a language file directly.";
	exit;
}

$pgv_lang["googlemap"]                  = "Cartographie";
$pgv_lang["no_gmtab"]                   = "Aucune donnée cartographique";
$pgv_lang["gm_disabled"]                = "Module Googlemap désactivé";

$pgv_lang['gm_redraw_map']              = "Actualiser";
$pgv_lang["gm_map"]                     = "Plan/Carte";
$pgv_lang["gm_satellite"]               = "Satellite";
$pgv_lang["gm_hybrid"]                  = "Mixte";

// Configuration texts
$pgv_lang["gm_manage"]                  = "Configuration Googlemap";
$pgv_lang["configure_googlemap"]        = "Configuration Googlemap";
$pgv_lang["gm_admin_error"]             = "Accès réservé aux administrateurs";
$pgv_lang["gm_db_error"]                = "Table placelocation introuvable dans la base de données";
$pgv_lang["gm_table_created"]           = "Table placelocation créée";
$pgv_lang["googlemap_enable"]           = "Activer Googlemap";
$pgv_lang["googlemapkey"]               = "Clé API Google Maps";
$pgv_lang["gm_map_type"]                = "Type par défaut de la carte";
$pgv_lang["gm_map_size"]                = "Taille de la carte (en pixels)";
$pgv_lang["gm_map_size_x"]              = "Largeur";
$pgv_lang["gm_map_size_y"]              = "Hauteur";
$pgv_lang["gm_map_zoom"]                = "Facteur de zoom sur la carte";
$pgv_lang["gm_digits"]                  = "chiffres";
#pgv_lang["gm_min"]                     = "Min.";
#pgv_lang["gm_max"]                     = "Max.";
$pgv_lang["gm_default_level0"]          = "Niveau supérieur par défaut";
$pgv_lang["gm_nof_levels"]              = "Nombre de niveaux";
$pgv_lang["gm_config_per_level"]        = "Configuration par niveau";
$pgv_lang["gm_name_prefix"]             = "Préfixe";
$pgv_lang["gm_name_postfix"]            = "Suffixe";
$pgv_lang["gm_name_pre_post"]           = "Ordre préfixe/suffixe";
$pgv_lang["gm_level"]                   = "Niveau";
$pgv_lang["gm_pp_none"]                 = "Aucun préfixe/suffixe";
$pgv_lang["gm_pp_n_pr_po_b"]            = "Normal, préfixe, suffixe, les 2";
$pgv_lang["gm_pp_n_po_pr_b"]            = "Normal, suffixe, préfixe, les 2";
$pgv_lang["gm_pp_pr_po_b_n"]            = "Préfixe, suffixe, les 2, normal";
$pgv_lang["gm_pp_po_pr_b_n"]            = "Suffixe, préfixe, les 2, normal";
$pgv_lang["gm_pp_pr_po_n_b"]            = "Préfixe, suffixe, normal, les 2";
$pgv_lang["gm_pp_po_pr_n_b"]            = "Suffixe, préfixe, normal, les 2";

// Texts used on the Places location page
$pgv_lang["edit_place_locations"]       = "Éditer les coordonnées du lieu";
$pgv_lang["pl_no_places_found"]         = "Lieu introuvable";
$pgv_lang["pl_zoom_factor"]             = "Facteur de zoom";
$pgv_lang["pl_place_icon"]              = "Icône";
$pgv_lang["pl_edit"]                    = "Éditer le lieu";
$pgv_lang["pl_add_place"]               = "Ajouter un lieu";
$pgv_lang["pl_import_gedcom"]           = "Importer depuis le GEDCOM en cours";
$pgv_lang["pl_import_all_gedcom"]       = "Importer depuis tous les GEDCOMs";
$pgv_lang["pl_import_file"]             = "Importer depuis un fichier";
$pgv_lang["pl_export_file"]             = "Exporter la vue courante vers un fichier";
$pgv_lang["pl_export_all_file"]         = "Exporter tous les lieux vers un fichier";
$pgv_lang["pl_north_short"]             = "N";
$pgv_lang["pl_south_short"]             = "S";
$pgv_lang["pl_east_short"]              = "E";
$pgv_lang["pl_west_short"]              = "O";
$pgv_lang["pl_places_filename"]         = "Fichier contenant les lieux (CSV)";
$pgv_lang["pl_clean_db"]                = "Supprimer tous les lieux/coordonnées avant d'importer ?";
$pgv_lang["pl_update_only"]             = "Modifier les lieux existants seulement ?";
$pgv_lang["pl_overwrite_data"]          = "Écraser les lieux par les valeurs du fichier ?";
$pgv_lang["pl_no_places_found"]         = "Lieu introuvable";
$pgv_lang["pl_use_this_value"]          = "Utiliser cette valeur";
$pgv_lang["pl_precision"]               = "Précision";
$pgv_lang["pl_country"]                 = "Pays";
$pgv_lang["pl_state"]                   = "État";
$pgv_lang["pl_city"]                    = "Ville";
$pgv_lang["pl_neighborhood"]            = "Voisinage";
$pgv_lang["pl_house"]                   = "Maison";
$pgv_lang["pl_max"]                     = "Max";

$pgv_lang["pl_flag"]                    = "Drapeau";
$pgv_lang["flags_edit"]                 = "Choisir un drapeau";
$pgv_lang["pl_change_flag"]             = "Changer de drapeau";
$pgv_lang["pl_remove_flag"]             = "Supprimer un drapeau";

$pgv_lang["pl_remove_location"]         = "Supprimer ce lieu ?";
$pgv_lang["pl_delete_error"]            = "Lieu non supprimé : ce lieu contient des sous-lieux";
?>
