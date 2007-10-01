<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
if (preg_match("/ra_lang\...\.php$/", $_SERVER["PHP_SELF"])>0) {
        print "You cannot access a language file directly.";
        exit;
}

$pgv_lang["lb_help"] = "Aide d'Album";
$pgv_lang["lightbox"] = "Album";
$pgv_lang["showmenu"] = "Afficher Menu:";
$pgv_lang["active"] = "Actif";
$pgv_lang["TYPE__other"] = "Autres";
$pgv_lang["no_media"] = "Aucun";
$pgv_lang["census_text"]  = "<< Ces images de recensement ont été obtenues à partir de << The National Archives >>, du gardien des disques d'original, ";
$pgv_lang["census_text"] .= "et apparaissent ici avec leur approbation à condition qu'aucune utilisation commerciale n'est faite d'eux sans permission." . "\n" ;
$pgv_lang["census_text"] .= "Des demandes de la publication commerciale de ces derniers ou d'autres images de recensement apparaissant sur ce site Web, devraient être dirigées vers : ";
$pgv_lang["census_text"] .= "<< Image Library, The National Archives, Kew, Surrey, TW9 4DU, United Kingdom. >> >>" . "\n" ;

$pgv_lang["lb_edit_details"] = "Editer détails";
$pgv_lang["lb_view_details"] = "Afficher détails";
$pgv_lang["lb_edit_media"] = "Editer les détails de cet article de médias ";
$pgv_lang["lb_delete_media"] = "Enlever cet article de médias - enleve seulement le lien sur cet individu - ne supprime pas le fichier ou d'autres liens ";
$pgv_lang["lb_view_media"] = "Afficher les détails de cet article de médias. \nPlus d'autres Options de médias. ";
$pgv_lang["lb_add_media"] = "Ajouter un nouvel objet Multimédia";
$pgv_lang["lb_add_media_full"] = "Ajouter de nouveaux Multimédia s'opposent à cet individu ";
$pgv_lang["lb_link_media"] = "Relier � un objet Multimédia existant";
$pgv_lang["lb_link_media_full"] = "Relier cet individu à un objet existant de Multimédia ";

$pgv_lang["lb_slide_show"] = "Projection de diapositives";
$pgv_lang["turn_edit_ON"] = "Tourner éditent le mode DESSUS";
$pgv_lang["turn_edit_OFF"] = "Tourner éditent le mode AU LOIN";

$pgv_lang["lb_source_avail"] = "L'information de source disponible - Cliquer ici";

$pgv_lang["lb_private"] = "L'image<br>lié<br>à un<br>individu<br>privé";


?>