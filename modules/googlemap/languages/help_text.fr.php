<?php
/**
 * French Language file for PhpGedView.
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
 * @package PhpGedView
 * @subpackage GoogleMap
 * @version $Id$
 */
if (preg_match("/help_text\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
  print "Vous ne pouvez pas accéder à ce fichier de langue directement.";
  exit;
}

$pgv_lang["GOOGLEMAP_API_KEY"]          = "Clé API Google Maps";
$pgv_lang["GOOGLEMAP_API_KEY_help"]     = "~#pgv_lang[GOOGLEMAP_API_KEY]#~<br /><br />Insérez votre clé API Google Maps ici.  Vous pouvez réclamer une clé à cette adresse: <a target=\"_blank\" href=\"http://www.google.com/apis/maps/\">http://www.google.com/apis/maps/</a>";

$pgv_lang["GOOGLEMAP_MAP_TYPE"]         = "Type de carte";
$pgv_lang["GOOGLEMAP_MAP_TYPE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_TYPE]#~<br /><br />Le type de la carte qui sera montrée par défaut. La carte peut être de type Plan, Satellite, ou Hybride.";

$pgv_lang["GOOGLEMAP_MAP_SIZE"]         = "Taille de la carte";
$pgv_lang["GOOGLEMAP_MAP_SIZE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_SIZE]#~<br /><br />La taille de la carte (en pixels) telle qu'elle apparaitra sur la page Individu.";

$pgv_lang["GOOGLEMAP_MAP_ZOOM"]         = "Facteur de zoom";
$pgv_lang["GOOGLEMAP_MAP_ZOOM_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_ZOOM]#~<br /><br />Facteur de zoom mini et maxi applicable à la carte. 1 correspond à une vue de la carte entière, 15 permet de voir juste une maison. Notez qu'un zoom de 15 n'est disponible que dans certains endroits.";

$pgv_lang["GOOGLEMAP_PRECISION"]        = "Précision de la latitude et de la longitude";
$pgv_lang["GOOGLEMAP_PRECISION_help"]   = "~#pgv_lang[GOOGLEMAP_PRECISION]#~<br /><br />Ceci précise le degré de précision des coordonnées. Par exemple, un pays entier sera spécifié avec une précision 0 (i.e. 0 chiffres après la virgule), alors qu'une ville a besoin de trois ou quatre chiffres.";

$pgv_lang["PL_EDIT_LOCATION"]           = "Éditer ou effacer un lieu";
$pgv_lang["PL_EDIT_LOCATION_help"]      = "Permet d'éditer ou de supprimer un lieu. Une nouvelle fenêtre s'affiche et permet de modifier les données.<br>Si vous cliquer sur l'icône de suppression, cet enregistrement sera supprimé. Cette opération n'est possible que si aucun évènement n'est en relation avec ce lieu. Si c'est bien le cas, l'icône de suppression est active, sinon elle est inactive.";

$pgv_lang["PL_ADD_LOCATION"]            = "Ajouter un lieu";
$pgv_lang["PL_ADD_LOCATION_help"]       = "Utiliser cette fonction pour ajouter une entrée à la table qui contient tous les lieux.";

$pgv_lang["PL_IMPORT_GEDCOM"]           = "Importer des lieux depuis le GEDCOM";
$pgv_lang["PL_IMPORT_GEDCOM_help"]      = "Permet d'importer des lieux depuis le GEDCOM en cours. Le GEDCOM en cours est parcouru et tous les lieux rencontrés sont ajoutées à la table de la base de données. Si une latitude et une longitude sont trouvées, celles-ci sont aussi importées.";

$pgv_lang["PL_IMPORT_ALL_GEDCOM"]       = "Importer des lieux depuis tous les GEDCOMs";
$pgv_lang["PL_IMPORT_ALL_GEDCOM_help"]  = "Permet d'importer des lieux depuis tous les GEDCOMs. Tous les GEDCOMs sont parcourus et tous les lieux trouvés sont ajoutés à la table de la base de données. Si une latitude et une longitude sont trouvées, celles-ci sont aussi importées.";

$pgv_lang["PL_IMPORT_FILE"]             = "Importer des lieux depuis un fichier";
$pgv_lang["PL_IMPORT_FILE_help"]        = "Permet d'importer des lieux depuis un fichier. Ce fichier doit être au format CSV et être hébergé sur l'ordinateur local. Le séparateur de champs est le point-virgule.";

$pgv_lang["PL_EXPORT_FILE"]             = "Exporter les lieux vers un fichier";
$pgv_lang["PL_EXPORT_FILE_help"]        = "Permet d'exporter les lieux vers un fichier. Cette option enregistre les données de la vue en cours, ainsi que toutes les données qui en dépendent. Par exemple, si la France choisie en tant que pays et que les régions sont visibles, cette option sauvera les données de ces régions et de leurs départements, ainsi que les communes de ces départements";

$pgv_lang["PL_EXPORT_ALL_FILE"]         = "Exporter tous les lieux vers un fichier";
$pgv_lang["PL_EXPORT_ALL_FILE_help"]    = "Permet d'exporter tous les lieux vers un fichier et de le transférer vers l'ordinateur local.";

// Help texts for places_edit.php

$pgv_lang["PLE_PLACES"]                 = "Nom du lieu";
$pgv_lang["PLE_PLACES_help"]            = "Permet de définir ou de modifier le nom du lieu.";

$pgv_lang["PLE_PRECISION"]              = "Précision";
$pgv_lang["PLE_PRECISION_help"]         = "Permet de définir la précision. Cette valeur est utilisée pour déterminer le nombre de chiffres significatifs de la latitude et de la longitude.";

$pgv_lang["PLE_LATLON_CTRL"]            = "Latitude ou longitude";
$pgv_lang["PLE_LATLON_CTRL_help"]       = "Permet d'entrer la latitude et la longitude du lieu. Sélectionnez d'abord la coordonnée (E/W ou N/S). Entrez ensuite la valeur pour la latitude ou la longitude. Elles doivent être exprimées en degrés décimaux.<br>Les degrés décimaux peuvent être déduits des degrés/minutes/secondes grâce à la formule suivante:<br>degrés_décimaux = ((secondes / 60) + minutes) / 60 + degrés.";

$pgv_lang["PLE_ZOOM"]                   = "Facteur de zoom";
$pgv_lang["PLE_ZOOM_help"]              = "Permet de définir le facteur de zoom. Cette valeur définit le facteur minimal lors de l'affichage de ce lieu sur la carte.";

$pgv_lang["PLE_ICON"]                   = "Icône";
$pgv_lang["PLE_ICON_help"]              = "Permet de choisir ou de supprimer une icône. Lorsque ce lieu est affiché, l'icône choisie apparait";

$pgv_lang["PLE_FLAGS"]                  = "Drapeau";
$pgv_lang["PLE_FLAGS_help"]             = "Ce menu déroulant permet de choisir un pays et de sélectionner son drapeau. Si aucun drapeau n'est défini pour ce pays aucun drapeau n'est montré.";

?>

