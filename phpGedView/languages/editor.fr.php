<?php
/**
 * French Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  Christophe Bx, Julien Damon
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
 * @subpackage Languages
 * @author Christophe Bx
 * @author Julien Damon
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

$pgv_lang["admin_override"]             = "Option administrateur";
$pgv_lang["no_update_CHAN"]             = "Conserver la date de mise à jour (CHAN)";
$pgv_lang["select_events"]              = "Choisir un évènement";
$pgv_lang["source_events"]              = "Evènements associés à cette source";
$pgv_lang["advanced_name_fields"]       = "Variantes du nom";
$pgv_lang["accept_changes"]             = "Accepter/Rejeter les modifications";
$pgv_lang["replace"]                    = "Modifier un enregistrement";
$pgv_lang["append"]                     = "Ajouter un enregistrement";
$pgv_lang["review_changes"]             = "Revoir les modifications GEDCOM";
$pgv_lang["remove_object"]              = "Supprimer l'objet";
$pgv_lang["remove_links"]               = "Supprimer les liens";
$pgv_lang["media_not_deleted"]          = "Répertoire Media non supprimé.";
$pgv_lang["thumbs_not_deleted"]         = "Répertoire des vignettes non supprimé.";
$pgv_lang["thumbs_deleted"]             = "Répertoire des vignettes supprimé.";
$pgv_lang["show_thumbnail"]             = "Montrer les vignettes";
$pgv_lang["link_media"]                 = "Relier un objet MultiMedia";
$pgv_lang["to_person"]                  = "à une personne";
$pgv_lang["to_family"]                  = "à une famille";
$pgv_lang["to_source"]                  = "à une source";
$pgv_lang["edit_fam"]                   = "Modifier la famille";
$pgv_lang["copy"]                       = "Copier";
$pgv_lang["cut"]                        = "Couper";
$pgv_lang["sort_by_birth"]              = "Trier par date de naissance";
$pgv_lang["reorder_children"]           = "Modifier l'ordre des enfants";
$pgv_lang["add_from_clipboard"]         = "Ajouter depuis le presse-papiers: ";
$pgv_lang["record_copied"]              = "Enregistrement copié dans le presse-papiers";
$pgv_lang["add_unlinked_person"]        = "Ajouter une personne isolée";
$pgv_lang["add_unlinked_source"]        = "Ajouter une source isolée";
$pgv_lang["server_file"]                = "Nom du fichier sur le serveur";
$pgv_lang["server_file_advice"]         = "Ne pas modifier pour conserver le nom d'origine.";
$pgv_lang["server_file_advice2"]        = "Entrer une URL commencant par &laquo;http://&raquo;.";
#pgv_lang["server_folder_advice"]       = "You can enter up to #GLOBALS[MEDIA_DIRECTORY_LEVELS]# folder names to follow the default &laquo;#GLOBALS[MEDIA_DIRECTORY]#&raquo;.<br />Do not enter the &laquo;#GLOBALS[MEDIA_DIRECTORY]#&raquo; part of the destination folder name.";
#pgv_lang["server_folder_advice2"]      = "This entry is ignored if you have entered a URL into the file name field.";
#pgv_lang["add_linkid_advice"]          = "Enter or search for the ID of the person, family, or source to which this media item should be linked.";
#pgv_lang["use_browse_advice"]          = "Use the &laquo;Browse&raquo; button to search your local computer for the desired file.";
#pgv_lang["add_media_other_folder"]     = "Other folder... please type in";
#pgv_lang["add_media_file"]             = "Existing Media file on server";
$pgv_lang["date_of_entry"]              = "Date d'entrée dans le document original";
$pgv_lang["main_media_ok1"]             = "Fichier MultiMedia <b>#GLOBALS[oldMediaName]#</b> renommé <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["main_media_ok2"]             = "Fichier MultiMedia <b>#GLOBALS[oldMediaName]#</b> déplacé de <b>#GLOBALS[oldMediaFolder]#</b> vers <b>#GLOBALS[newMediaFolder]#</b>.";
$pgv_lang["main_media_ok3"]             = "Fichier MultiMedia renommé et déplacé de <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> vers <b>#GLOBALS[newMediaFolder]##GLOBALS[newMediaName]#</b>.";
$pgv_lang["main_media_fail0"]           = "Fichier MultiMedia <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> non trouvé.";
$pgv_lang["main_media_fail1"]           = "Fichier MultiMedia <b>#GLOBALS[oldMediaName]#</b> impossible à renommer en <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["main_media_fail2"]           = "Fichier MultiMedia <b>#GLOBALS[oldMediaName]#</b> impossible à déplacer de <b>#GLOBALS[oldMediaFolder]#</b> vers <b>#GLOBALS[newMediaFolder]#</b>.";
$pgv_lang["main_media_fail3"]           = "Fichier MultiMedia impossible à renommer et déplacer de <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> vers <b>#GLOBALS[newMediaFolder]##GLOBALS[newMediaName]#</b>.";
$pgv_lang["thumb_media_ok1"]            = "Vignette <b>#GLOBALS[oldMediaName]#</b> renommée <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["thumb_media_ok2"]            = "Vignette <b>#GLOBALS[oldMediaName]#</b> déplacée de <b>#GLOBALS[oldThumbFolder]#</b> vers <b>#GLOBALS[newThumbFolder]#</b>.";
$pgv_lang["thumb_media_ok3"]            = "Vignette renommée et déplacée de <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> vers <b>#GLOBALS[newThumbFolder]##GLOBALS[newMediaName]#</b>.";
$pgv_lang["thumb_media_fail0"]          = "Vignette <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> non trouvée.";
$pgv_lang["thumb_media_fail1"]          = "Vignette <b>#GLOBALS[oldMediaName]#</b> impossible à renommer en <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["thumb_media_fail2"]          = "Vignette <b>#GLOBALS[oldMediaName]#</b> impossible à déplacer de <b>#GLOBALS[oldThumbFolder]#</b> vers <b>#GLOBALS[newThumbFolder]#</b>.";
$pgv_lang["thumb_media_fail3"]          = "Vignette impossible à renommer et déplacer de <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> vers <b>#GLOBALS[newThumbFolder]##GLOBALS[newMediaName]#</b>.";
$pgv_lang["add_asso"]                   = "Associer une nouvelle personne";
$pgv_lang["edit_sex"]                   = "Editer le genre";
$pgv_lang["add_obje"]                   = "Ajouter un nouvel objet Multimedia";
$pgv_lang["add_name"]                   = "Ajouter un nom";
$pgv_lang["edit_raw"]                   = "Modifier les balises GEDCOM";
$pgv_lang["label_add_remote_link"]      = "Ajouter un lien";
$pgv_lang["label_gedcom_id"]            = "ID base de données";
$pgv_lang["label_local_id"]             = "ID individu [local]";
$pgv_lang["accept"]                     = "Accepter";
$pgv_lang["accept_all"]                 = "Accepter toutes les modifications";
$pgv_lang["accept_gedcom"]              = "Vous pouvez accepter ou refuser chaque modification<br />Pour accepter tous les changements, importer à nouveau le fichier.<br />Pour refuser une modification, cliquer sur le lien 'Annuler' affiché à côté.<br />Pour connaître la nature d'une modification cliquer sur le lien 'Voir les différences'.";
$pgv_lang["accept_successful"]          = "Les modifications ont été acceptées";
$pgv_lang["add_child"]                  = "Ajouter un enfant";
$pgv_lang["add_child_to_family"]        = "Ajouter un enfant à cette famille";
$pgv_lang["add_fact"]                   = "Ajouter un nouvel enregistrement";
$pgv_lang["add_father"]                 = "Ajouter un nouveau père";
$pgv_lang["add_husb"]                   = "Ajouter un époux";
$pgv_lang["add_husb_to_family"]         = "Ajouter un époux à cette famille";
$pgv_lang["add_media"]                  = "Ajouter un nouvel objet MultiMedia";
$pgv_lang["add_media_lbl"]              = "Ajouter un objet MultiMedia";
$pgv_lang["add_mother"]                 = "Ajouter une nouvelle mère";
$pgv_lang["add_new_chil"]               = "Ajouter un nouvel enfant";
$pgv_lang["add_new_husb"]               = "Ajouter un nouvel époux";
$pgv_lang["add_new_wife"]               = "Ajouter une nouvelle épouse";
$pgv_lang["add_note"]                   = "Ajouter une nouvelle note";
$pgv_lang["add_note_lbl"]               = "Ajouter une note";
$pgv_lang["add_sibling"]                = "Ajouter un frère ou une sœur";
$pgv_lang["add_son_daughter"]           = "Ajouter un fils ou une fille";
$pgv_lang["add_source"]                 = "Ajouter une nouvelle source";
$pgv_lang["add_source_lbl"]             = "Ajouter une source";
$pgv_lang["add_wife"]                   = "Ajouter une épouse";
$pgv_lang["add_wife_to_family"]         = "Ajouter une épouse à cette famille";
$pgv_lang["advanced_search_discription"]= "Recherche avancée";
$pgv_lang["auto_thumbnail"]             = "Vignette Automatique";
$pgv_lang["basic_search"]               = "recherche";
$pgv_lang["basic_search_discription"]   = "Recherche basique";
$pgv_lang["birthdate_search"]           = "Date de naissance: ";
$pgv_lang["birthplace_search"]          = "Lieu de naissance: ";
$pgv_lang["change"]                     = "Changer";
#pgv_lang["change_family_instr"]        = "Use this page to change or remove family members.<br /><br />For each member in the family, you can use the Change link to choose a different person to fill that role in the family.  You can also use the Remove link to remove that person from the family.<br /><br />When you have finished changing the family members, click the Save button to save the changes.<br />";
$pgv_lang["change_family_members"]      = "Modifier les membres de la famille";
$pgv_lang["changes_occurred"]           = "Les modifications suivantes ont été faites pour cet individu";
$pgv_lang["confirm_remove"]             = "Confirmez-vous la suppression de cette personne de la famille ?";
$pgv_lang["confirm_remove_object"]      = "Confirmez-vous la suppression de cet objet ?";
$pgv_lang["create_repository"]          = "Créer un dépôt d'archives";
$pgv_lang["create_source"]              = "Créer une nouvelle source";
$pgv_lang["current_person"]             = "Identique";
$pgv_lang["date"]                       = "Date";
$pgv_lang["deathdate_search"]           = "Date de décès: ";
$pgv_lang["deathplace_search"]          = "Lieu de décès: ";
$pgv_lang["delete_dir_success"]         = "Répertoires Media et Vignettes supprimés.";
$pgv_lang["delete_file"]                = "Supprimer le fichier";
$pgv_lang["delete_repo"]                = "Supprimer ce dépôt d'archives";
$pgv_lang["directory_not_empty"]        = "Répertoire non vide.";
$pgv_lang["directory_not_exist"]        = "Répertoire absent.";
$pgv_lang["error_remote"]               = "Vous avez sélectionné un site distant.";
$pgv_lang["error_same"]                 = "Vous avez sélectionné le même site.";
$pgv_lang["external_file"]              = "Objet Multimedia non trouvé sur le serveur. Impossible de le supprimer, déplacer ou renommer.";
$pgv_lang["file_missing"]               = "Fichier non reçu. Veuillez recommencer.";
$pgv_lang["file_partial"]               = "Fichier reçu partiellement. Veuillez recommencer.";
$pgv_lang["file_success"]               = "Fichier chargé avec succès";
$pgv_lang["file_too_big"]               = "Le fichier chargé dépasse la taille autorisée";
$pgv_lang["folder"]                     = "Répertoire";
$pgv_lang["gedcom_editing_disabled"]    = "La modification de ce fichier GEDCOM a été interdite par l'administrateur.";
$pgv_lang["gedcomid"]                   = "Code GEDCOM";
$pgv_lang["gedrec_deleted"]             = "Enregistrement GEDCOM supprimé avec succès.";
$pgv_lang["gen_thumb"]                  = "Créer les vignettes";
$pgv_lang["gender_search"]              = "Sexe: ";
$pgv_lang["generate_thumbnail"]         = "Création automatique d'une vignette depuis ";
$pgv_lang["hebrew_givn"]                = "Prénom(s) Hébreu";
$pgv_lang["hebrew_surn"]                = "Nom Hébreu";
$pgv_lang["hide_changes"]               = "Masquer les modifications.";
$pgv_lang["highlighted"]                = "Surbrillance";
$pgv_lang["illegal_chars"]              = "Présence de caractères interdits";
#pgv_lang["invalid_search_multisite_input"]= "Please enter one of the following:  Name, Birth Date, Birth Place, Death Date, Death Place, and Sex ";
#pgv_lang["invalid_search_multisite_input_gender"]= "Please search again with more information than just sex";
$pgv_lang["label_diff_server"]          = "Un autre site";
$pgv_lang["label_location"]             = "Adresse";
$pgv_lang["label_password_id2"]         = "Mot de passe: ";
$pgv_lang["label_rel_to_current"]       = "Lien de parenté";
$pgv_lang["label_remote_id"]            = "ID individu [distant]";
$pgv_lang["label_same_server"]          = "Le même site";
$pgv_lang["label_site"]                 = "Site";
$pgv_lang["label_site_url"]             = "URL:";
$pgv_lang["label_username_id2"]         = "Utilisateur: ";
$pgv_lang["lbl_server_list"]            = "Site existant";
$pgv_lang["lbl_type_server"]            = "Nouveau site";
$pgv_lang["link_as_child"]              = "Relier cette personne à une famille existante comme enfant";
$pgv_lang["link_as_husband"]            = "Relier cette personne à une famille existante comme époux";
$pgv_lang["link_success"]               = "Lien ajouté avec succès";
$pgv_lang["link_to_existing_media"]     = "Relier à un objet MultiMedia existant";
$pgv_lang["max_media_depth"]            = "Limite de profondeur dans le dossier : #MEDIA_DIRECTORY_LEVELS# ";
$pgv_lang["max_upload_size"]            = "Taille maxi pour le téléchargement ";
$pgv_lang["media_deleted"]              = "Répertoire Media supprimé.";
$pgv_lang["media_exists"]               = "Le fichier MultiMedia existe déjà.";
$pgv_lang["media_file"]                 = "Objet MultiMedia";
$pgv_lang["media_file_deleted"]         = "Le fichier MultiMedia a été supprimé.";
$pgv_lang["media_file_not_moved"]       = "Impossible de déplacer le fichier MultiMedia.";
$pgv_lang["media_file_not_renamed"]     = "Impossible de déplacer ou renommer le fichier MultiMedia.";
$pgv_lang["media_thumb_exists"]         = "La vignette existe déjà.";
$pgv_lang["multi_site_search"]          = "Recherche multi-sites";
$pgv_lang["multiple_gedcoms"]           = "Impossible de supprimer, renommer ou déplacer ce fichier : il reste des liens avec un autre GEDCOM sur ce serveur.";
$pgv_lang["must_provide"]               = "Vous devez fournir un";
$pgv_lang["name_search"]                = "Nom: ";
$pgv_lang["new_repo_created"]           = "Nouveau dépôt d'archives créé";
$pgv_lang["new_source_created"]         = "Nouvelle source créée avec succès.";
$pgv_lang["no_changes"]                 = "Aucune modification en attente de validation.";
$pgv_lang["no_known_servers"]           = "Aucun serveur connu<br />Pas de résultat.";
$pgv_lang["no_temple"]                  = "Temple absent (LDS)";
$pgv_lang["no_upload"]                  = "Opération non autorisée ou répertoire non accessible en écriture.";
$pgv_lang["paste_id_into_field"]        = "Cliquez ici pour sélectionner cette source ";
$pgv_lang["paste_rid_into_field"]       = "Cliquez ici pour sélectionner ce dépôt d'archives ";
$pgv_lang["photo_replace"]              = "Remplacer l'ancienne photo ?";
$pgv_lang["privacy_not_granted"]        = "Impossible de modifier les droits pour";
$pgv_lang["privacy_prevented_editing"]  = "Droits insuffisants pour modifier cet enregistrement.";
$pgv_lang["record_marked_deleted"]      = "Une demande de suppression de cet enregistrement a été faite, en attente de validation par l'administrateur.";
$pgv_lang["replace_with"]               = "Remplacer par";
$pgv_lang["show_changes"]               = "<span class='warning'>Fiche modifiée</span>&nbsp;&nbsp;Afficher les modifications.";
$pgv_lang["thumb_genned"]               = "Une image vignette a été créée automatiquement.";
$pgv_lang["thumbgen_error"]             = "Impossible de créer une vignette pour ";
$pgv_lang["thumbnail"]                  = "Vignette";
$pgv_lang["title_remote_link"]          = "Ajouter un lien distant";
$pgv_lang["undo"]                       = "Annuler";
$pgv_lang["undo_all"]                   = "<span class='warning'>Annuler toutes les modifications</span>";
$pgv_lang["undo_all_confirm"]           = "Confirmez-vous l'annulation de toutes les modifications sur ce fichier GEDCOM ?";
$pgv_lang["undo_successful"]            = "Annulation réussie";
$pgv_lang["update_successful"]          = "Mise à jour réussie";
$pgv_lang["upload"]                     = "Téléchargement";
$pgv_lang["upload_error"]               = "Erreur lors du chargement de votre fichier GEDCOM sur le serveur.";
$pgv_lang["upload_media"]               = "Charger des objets MultiMedia";
$pgv_lang["upload_media_help"]          = "~#pgv_lang[upload_media]#~<br /><br />Choisir un fichier sur votre ordinateur. Il sera copié sur le serveur dans le dossier <b>#MEDIA_DIRECTORY#</b> ou un de ses sous-dossiers.<br /><br />Le nom du dossier sera ajouté à #MEDIA_DIRECTORY#. Par exemple, #MEDIA_DIRECTORY#famille. Si le dossier de vignettes n'existe pas, il sera créé automatiquement.";
$pgv_lang["upload_successful"]          = "Téléchargement réussi";
$pgv_lang["view_change_diff"]           = "Voir les différences";

?>
