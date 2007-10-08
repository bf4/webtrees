<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PHPGedView Development Team
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
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */

//-- security check, only allow access from module.php
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Vous ne pouvez pas d'accéder aux fichiers de langue en direct.";
	exit;
}

//Config Parameters -------------------------------------
$pgv_lang["configure_lightbox"]		= "Configuration de Lightbox";
$pgv_lang["mediatab"]       		= "<b>Page Individu - Etiquette de médias</b> - Aspect";
$pgv_lang["lb_admin_error"]         = "Page only for Administrators";

$pgv_lang["lb_icon"]				= "Icon";
$pgv_lang["lb_text"]				= "Text";
$pgv_lang["lb_both"]				= "Both";
$pgv_lang["lb_none"]				= "None";

$pgv_lang["lb_al_head_links"]		= "<b>Page Individu - Album Tab Header</b> - Aspect de Lien";
$pgv_lang["lb_al_thumb_links"]		= "<b>Page Individu - Album Tab Thumbnails</b> - Aspect de Lien";
$pgv_lang["lb_ml_thumb_links"]		= "<b>Page de Multimédia - Ongles du pouce</b> - Aspect de Lien";
$pgv_lang["lb_music_file"]			= "<b>Fichier choisi de musique de Lightbox</b> - (mp3 seulment)";
$pgv_lang["lb_ss_speed"]			= "<b>Vitesse de Projection de diapositives</b> - En seconds";

// ---------------------------------------------------------------------

$pgv_lang["lb_help"] = "Aide d'Album";
$pgv_lang["lightbox"] = "Album";
$pgv_lang["showmenu"] = "Afficher Menu:";
$pgv_lang["active"] = "Actif";
$pgv_lang["TYPE__other"] = "Autres";
$pgv_lang["no_media"] = "Aucun";
$pgv_lang["census_text"]  = "\"Ces images de recensement ont été obtenues à partir de «The National Archives», gardien des dossiers originaux, ";
$pgv_lang["census_text"] .= "et apparaissent ici avec leur approbation à condition qu'aucune utilisation commerciale n'est faite d'eux sans permission." . "\n" ;
$pgv_lang["census_text"] .= "Des demandes de la publication commerciale de ces derniers ou d'autres images de recensement apparaissant sur ce site Web, devraient être dirigées vers: ";
$pgv_lang["census_text"] .= "Image Library, The National Archives, Kew, Surrey, TW9 4DU, United Kingdom. \"" . "\n" ;

$pgv_lang["lb_edit_details"] = "Editer détails";
$pgv_lang["lb_view_details"] = "Afficher détails";
$pgv_lang["lb_edit_media"] = "Editer les détails de cet objet média ";
$pgv_lang["lb_delete_media"] = "Enlever cet objet média - enlève seulement le lien à cet individu - ne supprime pas le fichier ou d'autres liens ";
$pgv_lang["lb_view_media"] = "Afficher les détails de cet objet média, \naussi d'autres options média. ";
$pgv_lang["lb_add_media"] = "Ajouter un nouvel objet Multimédia";
$pgv_lang["lb_add_media_full"] = "Ajouter de nouveaux objets Multimédia à cet individu ";
$pgv_lang["lb_link_media"] = "Relier un objet Multimédia existant";
$pgv_lang["lb_link_media_full"] = "Relier cet individu à un objet Multimédia existant";

$pgv_lang["lb_slide_show"] = "Projection de diapositives";
$pgv_lang["turn_edit_ON"] = "Activer le mode «edit»";
$pgv_lang["turn_edit_OFF"] = "Désactiver le mode «edit»";

$pgv_lang["lb_source_avail"] = "D'information de source disponible - Cliquez ici";

$pgv_lang["lb_private"] = "L'image est liée à<br />un individu privé";
$pgv_lang["lb_view_source_tip"] = "Afficher source: ";
$pgv_lang["lb_view_details_tip"] = "Afficher détails de média: ";

?>