<?php
/**
 * French Language file for PhpGedView.
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
 * @subpackage Languages
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	header("HTTP/1.0 403 Forbidden" );
	exit;
}

$pgv_lang["age_differences"]            = "Voir les écarts d'âge";
$pgv_lang["date_of_entry"]              = "Date d'entrée dans le document original";
$pgv_lang["multi_site_search"]          = "Recherche multi-sites";
#pgv_lang["switch_lifespan"]            = "Show Lifespan chart";
#pgv_lang["switch_timeline"]            = "Show Timeline chart";
$pgv_lang["differences"]                = "Différences";
#pgv_lang["charts_block"]               = "Charts Block";
#pgv_lang["charts_block_descr"]         = "The Charts block allows you to place a chart on the Welcome or the MyGedView portal page.  You can configure the block to show an ancestors, descendants, or hourglass view.  You can also choose the root person for the chart.";
#pgv_lang["chart_type"]                 = "Chart Type";
#pgv_lang["changedate1"]                = "Ending range of change dates";
#pgv_lang["changedate2"]                = "Starting range of change dates";
$pgv_lang["search_place_word"]          = "Mots entiers seulement";
$pgv_lang["invalid_search_input"]       = "Entrez un nom de personne ou de lieu en complément de l'année";
$pgv_lang["duplicate_username"]         = "Utilisateur déjà existant.  Un utilisateur existe déjà sous ce nom.  Veuillez retourner à la page précédente et choisir un autre nom.";
$pgv_lang["cache_life"]                 = "Fichier Cache";
$pgv_lang["genealogy"]                  = "Généalogie";
$pgv_lang["activate"]                   = "Activer";
$pgv_lang["deactivate"]                 = "Désactiver";
$pgv_lang["play"]                       = "Démarrer";
$pgv_lang["stop"]                       = "Arrêter";
$pgv_lang["random_media_start_slide"]   = "Démarrer le diaporama au chargement de la page ?";
$pgv_lang["random_media_ajax_controls"] = "Afficher les contrôles AJAX ?";
$pgv_lang["description"]                = "Description";
$pgv_lang["current_dir"]                = "Répertoire actif: ";
$pgv_lang["SHOW_ID_NUMBERS"]            = "Afficher le code GEDCOM";
$pgv_lang["SHOW_HIGHLIGHT_IMAGES"]      = "Afficher les miniatures des individus";
$pgv_lang["view_img_details"]           = "Voir les détails de l'image";
$pgv_lang["server_folder"]              = "Nom du dossier sur le serveur";
$pgv_lang["medialist_recursive"]        = "Voir les sous-répertoires";
$pgv_lang["media_options"]              = "Options MultiMedia";
$pgv_lang["confirm_password"]           = "Vous devez confirmer le mot de passe.";
$pgv_lang["enter_email"]                = "Vous devez entrer une adresse courriel.";
$pgv_lang["enter_fullname"]             = "Vous devez entrer un prénom et un nom.";
$pgv_lang["name"]                       = "Nom";
$pgv_lang["children"]                   = "Enfants";
$pgv_lang["child"]                      = "Enfant";
$pgv_lang["family"]                     = "Famille";
$pgv_lang["as_child"]                   = "Parents, frères et sœurs";
$pgv_lang["source_menu"]                = "Options pour la source";
$pgv_lang["other_records"]              = "Autres enregistrements liés à cette source";
$pgv_lang["other_repo_records"]         = "Enregistrements liés à ce dépôt d'archives";
$pgv_lang["repo_info"]                  = "Information sur le dépôt d'archives";
$pgv_lang["enter_terms"]                = "Entrez vos critères de recherche";
$pgv_lang["search_asso_label"]          = "Associés";
$pgv_lang["search_asso_text"]           = "Afficher les personnes/familles associées";
$pgv_lang["search_DM"]                  = "Daitch-Mokotoff";
$pgv_lang["search_fams"]                = "Familles";
$pgv_lang["search_gedcom"]              = "Recherche dans le fichier GEDCOM";
$pgv_lang["search_geds"]                = "Rechercher dans les fichiers GEDCOM suivants";
$pgv_lang["search_indis"]               = "Individus";
$pgv_lang["search_inrecs"]              = "Rechercher dans";
$pgv_lang["search_prtall"]              = "Tous les noms";
$pgv_lang["search_prthit"]              = "Noms correspondant";
$pgv_lang["results_per_page"]           = "Résultats par page";
$pgv_lang["firstname_search"]           = "Prénom";
$pgv_lang["search_prtnames"]            = "Noms des individu à imprimer:";
$pgv_lang["other_searches"]             = "Autres recherches";
$pgv_lang["add_to_cart"]                = "Ajouter au panier";
$pgv_lang["view_gedcom"]                = "Voir les balises GEDCOM";
$pgv_lang["welcome"]                    = "Bienvenue";
$pgv_lang["son"]                        = "Fils";
$pgv_lang["daughter"]                   = "Fille";
$pgv_lang["welcome_page"]               = "Page d'accueil";
$pgv_lang["editowndata"]                = "Mon compte";
$pgv_lang["user_admin"]                 = "Administrer les utilisateurs";
$pgv_lang["manage_media"]               = "Gestion des objets MultiMedia";
$pgv_lang["search_general"]             = "Recherche générale";
$pgv_lang["clipping_privacy"]           = "Respect de la vie privée : certains enregistrements n'ont pu être ajoutés";
$pgv_lang["chart_new"]                  = "Arbre de la famille";
$pgv_lang["loading"]                    = "Chargement...";
$pgv_lang["clear_chart"]                = "Effacer tout";
$pgv_lang["file_information"]           = "Informations du fichier";
$pgv_lang["choose_file_type"]           = "Type du fichier";
$pgv_lang["add_individual_by_id"]       = "Ajout d'individus par leur code";
$pgv_lang["advanced_options"]           = "Options avancées";
$pgv_lang["zip_files"]                  = "Fichiers compressés";
$pgv_lang["include_media"]              = "Inclure les objets MultiMedia (compressés Zip)";
$pgv_lang["roman_surn"]                 = "Nom 'Romanisé'";
$pgv_lang["roman_givn"]                 = "Prénom 'Romanisé'";
$pgv_lang["include"]                    = "Comprenant:";
$pgv_lang["page_x_of_y"]                = "Page #GLOBALS[currentPage]# de #GLOBALS[lastPage]#";
$pgv_lang["options"]                    = "Options";
$pgv_lang["config_update_ok"]           = "Votre fichier de configuration a été mis à jour.";
$pgv_lang["page_size"]                  = "Format de la page";
$pgv_lang["record_not_found"]           = "Enregistrement GEDCOM non trouvé.";
$pgv_lang["result_page"]                = "Resultats";
$pgv_lang["edit_media"]                 = "Editer l'objet MultiMedia";
$pgv_lang["wiki_main_page"]             = "Wiki : page d'accueil";
$pgv_lang["wiki_users_guide"]           = "Wiki : guide d'utilisation";
$pgv_lang["wiki_admin_guide"]           = "Wiki : guide d'administration";
$pgv_lang["no_search_for"]              = "Merci de choisir une option de recherche";
$pgv_lang["no_search_site"]             = "Merci d'indiquer au moins un site distant.";
$pgv_lang["search_sites"]               = "Sites de recherche";
$pgv_lang["site_list"]                  = "Site: ";
$pgv_lang["site_had"]                   = " contenait les informations suivantes";
$pgv_lang["label_search_engine_detected"]= "Robot détecté";

$pgv_lang["ex-spouse"]                  = "Ex-conjoint";
$pgv_lang["ex-wife"]                    = "Ex-épouse";
$pgv_lang["ex-husband"]                 = "Ex-mari";
$pgv_lang["noemail"]                    = "Adresses sans email";
$pgv_lang["onlyemail"]                  = "Seulement les adresses sans email";
$pgv_lang["maxviews_exceeded"]          = "Cette page a dépassé son quota de visites, merci de réessayer plus tard.";
$pgv_lang["broadcast_not_logged_6mo"]   = "Envoyer une alerte aux utilisateurs dont la dernière connexion date de plus de 6 mois";
$pgv_lang["broadcast_never_logged_in"]  = "Envoyer une alerte aux utilisateurs jamais connectés depuis leur enregistrement";
$pgv_lang["stats_to_show"]              = "Selectionner les statistiques à afficher dans ce bloc";
$pgv_lang["stat_avg_age_at_death"]      = "Moyenne de l'âge de décès";
$pgv_lang["stat_longest_life"]          = "Personne ayant vécu le plus longtemps";
$pgv_lang["stat_most_children"]         = "Record du nombre d'enfants";
$pgv_lang["stat_average_children"]      = "Moyenne enfants par famille";
$pgv_lang["stat_events"]                = "Evènements";
$pgv_lang["stat_media"]                 = "Objets MultiMedia";
$pgv_lang["stat_surnames"]              = "Noms de familles";
$pgv_lang["stat_users"]                 = "Utilisateurs";
$pgv_lang["no_family_facts"]            = "Aucun évènement pour cette famille.";
$pgv_lang["stat_males"]                 = "Total hommes";
$pgv_lang["stat_females"]               = "Total femmes";

$pgv_lang["sunday_1st"]                 = "Dim";
$pgv_lang["monday_1st"]                 = "Lun";
$pgv_lang["tuesday_1st"]                = "Mar";
$pgv_lang["wednesday_1st"]              = "Mer";
$pgv_lang["thursday_1st"]               = "Jeu";
$pgv_lang["friday_1st"]                 = "Ven";
$pgv_lang["saturday_1st"]               = "Sam";

$pgv_lang["jan_1st"]                    = "Jan";
$pgv_lang["feb_1st"]                    = "Fév";
$pgv_lang["mar_1st"]                    = "Mar";
$pgv_lang["apr_1st"]                    = "Avr";
$pgv_lang["may_1st"]                    = "Mai";
$pgv_lang["jun_1st"]                    = "Juin";
$pgv_lang["jul_1st"]                    = "Juil";
$pgv_lang["aug_1st"]                    = "Août";
$pgv_lang["sep_1st"]                    = "Sep";
$pgv_lang["oct_1st"]                    = "Oct";
$pgv_lang["nov_1st"]                    = "Nov";
$pgv_lang["dec_1st"]                    = "Déc";

$pgv_lang["edit_source"]                = "Modifier source";
$pgv_lang["familybook_chart"]           = "Livret familial";
$pgv_lang["family_of"]                  = "Famille de :&nbsp;";
$pgv_lang["descent_steps"]              = "Niveaux de descendance";

$pgv_lang["cancel"]                     = "Annuler";
$pgv_lang["cookie_help"]                = "Ce site utilise des cookies.<br />Merci de vérifier que votre navigateur les accepte.<br />Pour plus d'informations, consulter les pages d'aide de votre navigateur.";
//new stuff
//Individual
$pgv_lang["indi_is_remote"]             = "Les informations de cette personne proviennent d'un autre site.";
$pgv_lang["link_remote"]                = "Lier à une personne d'un autre site";
//Add Remote Link
$pgv_lang["title_search_link"]          = "Ajouter un lien distant local";
$pgv_lang["label_site_url2"]            = "URL du site";
//new stuff

$pgv_lang["delete_family_confirm"]      = "Confirmez-vous la suppression de cette famille ? NB: les individus ne seront pas effacés";
$pgv_lang["delete_family"]              = "Supprimer cette famille";
$pgv_lang["add_favorite"]               = "Ajouter un favori";
$pgv_lang["url"]                        = "URL";
$pgv_lang["add_fav_enter_note"]         = "Entrer un commentaire pour ce favori";
$pgv_lang["add_fav_or_enter_url"]       = "OU<br />Entrer une URL et un titre";
$pgv_lang["add_fav_enter_id"]           = "Entrer un code Individu, Famille ou Source";
$pgv_lang["next_email_sent"]            = "Prochaine alerte courriel après le ";
$pgv_lang["last_email_sent"]            = "Dernière alerte courriel envoyée le ";
$pgv_lang["remove_child"]               = "Retirer cet enfant de la famille";
$pgv_lang["link_new_husb"]              = "Relier à une personne existante comme mari";
$pgv_lang["link_new_wife"]              = "Relier à une personne existante comme épouse";
$pgv_lang["address_labels"]             = "Etiquettes adresses";
$pgv_lang["filter_address"]             = "Afficher les adresses contenant:";
$pgv_lang["address_list"]               = "Liste adresse";
$pgv_lang["autocomplete"]               = "Autocompletion";
$pgv_lang["index_edit_advice"]          = "Sélectionner un bloc puis cliquer sur une des flèches pour le déplacer";
$pgv_lang["changelog"]                  = "Nouveautés de la version #VERSION#";
$pgv_lang["html_block_descr"]           = "Bloc HTML simple pour afficher un message de votre choix.";
$pgv_lang["html_block_sample_part1"]    = "<p class='blockhc'><b>Saisir le titre ici</b></p><br /><p>Cliquer sur le bouton Configuration";
$pgv_lang["html_block_sample_part2"]    = "pour modifier ce texte</p>";
$pgv_lang["html_block_name"]            = "Bloc HTML";
$pgv_lang["htmlplus_block_name"]        = "Bloc HTML avancé";
#pgv_lang["htmlplus_block_descr"]       = "This is an HTML block that you can place on your page to add any sort of message you may want.  You can insert references to information from your GEDCOM into the HTML text.";
$pgv_lang["htmlplus_block_templates"]   = "Modèles";
$pgv_lang["htmlplus_block_content"]     = "Contenu";
#pgv_lang["htmlplus_block_narrative"]   = "Narrative style (English only)";
$pgv_lang["htmlplus_block_custom"]      = "Personnalisé";
#pgv_lang["htmlplus_block_keyword"]     = "Keyword Examples (English only)";
$pgv_lang["htmlplus_block_taglist"]     = "Liste de balises";
$pgv_lang["htmlplus_block_compat"]      = "Compatibilité";
$pgv_lang["htmlplus_block_current"]     = "Courant";
$pgv_lang["htmlplus_block_default"]     = "Défaut";
$pgv_lang["htmlplus_block_gedcom"]      = "Arbre";
$pgv_lang["htmlplus_block_birth"]       = "naissance";
$pgv_lang["htmlplus_block_death"]       = "décès";
$pgv_lang["htmlplus_block_marrage"]     = "mariage";
$pgv_lang["htmlplus_block_adoption"]    = "adoption";
$pgv_lang["htmlplus_block_burial"]      = "sépulture";
$pgv_lang["htmlplus_block_census"]      = "recensement";
$pgv_lang["num_to_show"]                = "Nombre de lignes à afficher";
$pgv_lang["days_to_show"]               = "Nombre de jours à afficher";
$pgv_lang["before_or_after"]            = "Compteur des lieux avant ou après le nom ?";
$pgv_lang["before"]                     = "avant";
$pgv_lang["after"]                      = "après";
$pgv_lang["config_block"]               = "Configurer le bloc";
$pgv_lang["enter_comments"]             = "Entrez votre lien de parenté en commentaire.";
$pgv_lang["comments"]                   = "Commentaires";
$pgv_lang["child-family"]               = "Parents, frères et sœurs";
$pgv_lang["spouse-family"]              = "Conjoint et enfants";
$pgv_lang["direct-ancestors"]           = "Ancêtres en ligne directe";
$pgv_lang["ancestors"]                  = "Ancêtres en ligne directe et leurs familles";
$pgv_lang["descendants"]                = "Descendants";
$pgv_lang["choose_relatives"]           = "Choisir";
$pgv_lang["relatives_report"]           = "Parenté";
$pgv_lang["total_living"]               = "Vivants";
$pgv_lang["total_dead"]                 = "Décédés";
$pgv_lang["total_not_born"]             = "A naître";
$pgv_lang["remove_custom_tags"]         = "Supprimer les marqueurs PGV ? (ex. _PGVU, _THUM)";
$pgv_lang["cookie_login_help"]          = "Vous pouvez demander à rester mémorisé sur ce poste.<br />Vous aurez ainsi l'accès immédiat aux données protégées lors d'une prochaine connexion.<br />Par sécurité, vous devrez vous identifier de nouveau pour utiliser les fonctions d'administration.";
$pgv_lang["remember_me"]                = "Rester mémorisé sur cet ordinateur";
$pgv_lang["fams_with_surname"]          = "Familles avec le nom #surname#";
$pgv_lang["support_contact"]            = "Contact technique";
$pgv_lang["genealogy_contact"]          = "Contact généalogie";
$pgv_lang["common_upload_errors"]       = "La cause probable de cette erreur est la taille autorisée par votre hébergeur (valeur PHP par défaut: 2MB). Renseignez-vous auprès de votre hébergeur pour modifier le fichier php.ini, ou utilisez un logiciel FTP pour télécharger votre fichier sur le serveur. Voir la page <a href=\"uploadgedcom.php?action=add_form\">Ajouter un fichier GEDCOM</a>.";
$pgv_lang["total_memory_usage"]         = "Mémoire utilisée :";
$pgv_lang["mothers_family_with"]        = "Famille maternelle avec ";
$pgv_lang["fathers_family_with"]        = "Famille paternelle avec ";
$pgv_lang["family_with"]                = "Famille avec ";
$pgv_lang["halfsibling"]                = "Demi frère/sœur";
$pgv_lang["halfbrother"]                = "Demi-frère";
$pgv_lang["halfsister"]                 = "Demi-sœur";
$pgv_lang["family_timeline"]            = "Voir la famille sur l'échelle de temps";
$pgv_lang["children_timeline"]          = "Voir les enfants sur l'échelle de temps";
$pgv_lang["other"]                      = "Autre";
$pgv_lang["sort_by_marriage"]           = "Trier par date de mariage";
$pgv_lang["reorder_families"]           = "Modifier l'ordre des familles";
$pgv_lang["indis_with_surname"]         = "Individus portant le nom #surname#";
$pgv_lang["first_letter_fname"]         = "Sélectionner la première lettre du prénom.";
$pgv_lang["total_names"]                = "Noms affichés";
$pgv_lang["top10_pageviews_nohits"]     = "Liste vide.";
$pgv_lang["top10_pageviews_msg"]        = "Le compteur de visites doit être activé pour que ce bloc fonctionne.";
$pgv_lang["review_changes_descr"]       = "Le bloc «Modifications en attente de validation» affiche la liste des changements que l'administrateur doit confirmer avant leur enregistrement définitif dans la base. Un rappel lui est envoyé chaque jour par courriel.";
$pgv_lang["review_changes_block"]       = "Modifications en attente de validation";
$pgv_lang["review_changes_email"]       = "Envoi d'alertes par courriel ?";
$pgv_lang["review_changes_email_freq"]  = "Fréquence des alertes courriel (jours)";
$pgv_lang["review_changes_subject"]     = "PhpGedView - Liste des modifications en attente";
$pgv_lang["review_changes_body"]        = "Il reste des modifications en attente de validation sur le site PhpGedView. Merci d'utiliser le lien suivant pour vous connecter et confirmer ces changements.";
$pgv_lang["show_pending"]               = "Voir les modifications en attente de validation";
$pgv_lang["show_spouses"]               = "Afficher les conjoints";
$pgv_lang["quick_update_title"]         = "Modification expresse";
$pgv_lang["quick_update_instructions"]  = "Cette page permet la saisie des principales informations d'une personne : naissance, mariage, décès. Il n'est pas nécessaire de tout saisir : vos modifications seront vérifiées par l'administrateur du site avant leur publication.";
$pgv_lang["update_name"]                = "Modif nom";
$pgv_lang["update_fact"]                = "Modif évènement";
$pgv_lang["update_fact_restricted"]     = "La modification de cet enregistrement est restreinte ";
$pgv_lang["update_photo"]               = "Modif photo";
$pgv_lang["select_fact"]                = "Choisir l'évènement...";
$pgv_lang["update_address"]             = "Modif adresse";
$pgv_lang["top10_pageviews_descr"]      = "Ce bloc affiche les 10 pages les plus visitées pour ce fichier GEDCOM. Le compteur de visites doit être activé (voir les options de configuration).";
$pgv_lang["top10_pageviews"]            = "Liste des pages les plus visitées";
$pgv_lang["top10_pageviews_block"]      = "Bloc «Pages les plus visitées»";
$pgv_lang["stepdad"]                    = "Beau-père";
$pgv_lang["stepmom"]                    = "Belle-mère";
$pgv_lang["stepsister"]                 = "Sœur par remariage";
$pgv_lang["stepbrother"]                = "Frère par remariage";
$pgv_lang["fams_charts"]                = "Options pour cette famille";
$pgv_lang["indis_charts"]               = "Options pour cet individu";
$pgv_lang["none"]                       = "Libre";
$pgv_lang["locked"]                     = "Restreint";
$pgv_lang["privacy"]                    = "Protégé";
#pgv_lang["number_sign"]                = "#";

//-- GENERAL HELP MESSAGES
$pgv_lang["qm"]                         = "?";
$pgv_lang["qm_ah"]                      = "?";
$pgv_lang["page_help"]                  = "Aide";
$pgv_lang["help_for_this_page"]         = "Aide pour cette page";
$pgv_lang["help_contents"]              = "Sommaire de l'aide";
$pgv_lang["show_context_help"]          = "Afficher l'aide contextuelle";
$pgv_lang["hide_context_help"]          = "Masquer l'aide contextuelle";
$pgv_lang["sorry"]                      = "<b>Désolé, texte d'aide non disponible</b>";
$pgv_lang["help_not_exist"]             = "<b>Texte d'aide non disponible</b>";
$pgv_lang["var_not_exist"]              = "<span style=font-weight: bold>Variable langue non trouvée. Merci de signaler cette erreur.</span>";
$pgv_lang["resolution"]                 = "Résolution de l'écran";
$pgv_lang["menu"]                       = "Menu";
$pgv_lang["header"]                     = "Bandeau";
$pgv_lang["imageview"]                  = "Afficheur d'images";

//-- CONFIG FILE MESSAGES
$pgv_lang["login_head"]                 = "Identification PhpGedView";
$pgv_lang["for_support"]                = "Pour tout problème technique contacter";
$pgv_lang["for_contact"]                = "Pour toute question sur la généalogie contacter";
$pgv_lang["for_all_contact"]            = "Pour toute question, contacter l'administrateur";
$pgv_lang["build_error"]                = "Fichier GEDCOM mis à jour.";
$pgv_lang["choose_username"]            = "Identifiant souhaité";
$pgv_lang["username"]                   = "Identifiant";
$pgv_lang["invalid_username"]           = "L'identifiant contient des caractères interdits";
$pgv_lang["firstname"]                  = "Prénom";
$pgv_lang["lastname"]                   = "Nom de famille";
$pgv_lang["choose_password"]            = "Mot de passe souhaité";
$pgv_lang["password"]                   = "Mot de passe";
$pgv_lang["confirm"]                    = "Confirmer le mot de passe";
$pgv_lang["login"]                      = "Connexion";
$pgv_lang["logout"]                     = "Déconnexion";
$pgv_lang["admin"]                      = "Administration";
$pgv_lang["logged_in_as"]               = "Connecté ";
$pgv_lang["my_pedigree"]                = "Mon arbre";
$pgv_lang["my_indi"]                    = "Ma fiche";
$pgv_lang["yes"]                        = "Oui";
$pgv_lang["no"]                         = "Non";
$pgv_lang["change_theme"]               = "Changer de thème";

//-- INDEX (PEDIGREE_TREE) FILE MESSAGES
$pgv_lang["index_header"]               = "Arbre d'ascendance";
$pgv_lang["gen_ped_chart"]              = "Arbre de #PEDIGREE_GENERATIONS# générations";
$pgv_lang["generations"]                = "Nombre de générations";
$pgv_lang["view"]                       = "Afficher";
$pgv_lang["fam_spouse"]                 = "Famille avec le conjoint";
$pgv_lang["root_person"]                = "Code individu";
$pgv_lang["hide_details"]               = "Masquer les détails";
$pgv_lang["show_details"]               = "Afficher les détails";
$pgv_lang["person_links"]               = "Liens vers les arbres, familles, et parents proches.";
$pgv_lang["zoom_box"]                   = "Zoom avant/arrière sur cette case.";
$pgv_lang["orientation"]                = "Orientation";
$pgv_lang["portrait"]                   = "Portrait";
$pgv_lang["landscape"]                  = "Paysage";
$pgv_lang["start_at_parents"]           = "Retour aux parents";
$pgv_lang["charts"]                     = "Diagrammes";
$pgv_lang["lists"]                      = "Listes";
$pgv_lang["max_generation"]             = "Le nombre maximum de générations est #PEDIGREE_GENERATIONS#.";
$pgv_lang["min_generation"]             = "Le nombre minimum de générations est 3.";
$pgv_lang["box_width"]                  = "Largeur de boîte";

//-- FUNCTIONS FILE MESSAGES
$pgv_lang["unable_to_find_family"]      = "Aucun lien famille";
$pgv_lang["unable_to_find_record"]      = "Aucun enregistrement trouvé";
$pgv_lang["title"]                      = "Titre";
$pgv_lang["living"]                     = "Personne vivante";
$pgv_lang["private"]                    = "Détails privés";
$pgv_lang["birth"]                      = "Naissance";
$pgv_lang["death"]                      = "Décès";
$pgv_lang["descend_chart"]              = "Tableau de descendance";
$pgv_lang["individual_list"]            = "Liste des individus";
$pgv_lang["family_list"]                = "Liste des familles";
$pgv_lang["source_list"]                = "Liste des sources";
$pgv_lang["place_list"]                 = "Liste des lieux";
$pgv_lang["place_list_aft"]             = "Lieux après";
$pgv_lang["media_list"]                 = "Liste des objets MultiMedia";
$pgv_lang["search"]                     = "Recherche";
$pgv_lang["clippings_cart"]             = "Extraction de données";
$pgv_lang["print_preview"]              = "Page imprimable";
$pgv_lang["cancel_preview"]             = "Retour page complète";
$pgv_lang["change_lang"]                = "Changer de langue";
$pgv_lang["print"]                      = "Imprimer";
$pgv_lang["total_queries"]              = "Requêtes sur la base de données";
$pgv_lang["total_privacy_checks"]       = "Contrôles de restriction d'accès";
$pgv_lang["back"]                       = "Retour";

//-- INDIVIDUAL FILE MESSAGES
$pgv_lang["aka"]                        = "Nom d'usage";
$pgv_lang["male"]                       = "Masculin";
$pgv_lang["female"]                     = "Féminin";
$pgv_lang["temple"]                     = "Temple (LDS)";
$pgv_lang["temple_code"]                = "Code du temple (LDS)";
$pgv_lang["status"]                     = "Statut";
$pgv_lang["source"]                     = "Source";
$pgv_lang["text"]                       = "Texte";
$pgv_lang["note"]                       = "Note";
$pgv_lang["NN"]                         = "(nom inconnu)";
$pgv_lang["PN"]                         = "(prénom inconnu)";
$pgv_lang["unrecognized_code"]          = "Code GEDCOM inconnu";
$pgv_lang["unrecognized_code_msg"]      = "Erreur non répertoriée. Merci de signaler cette erreur à ";
$pgv_lang["indi_info"]                  = "Informations de l'individu";
$pgv_lang["pedigree_chart"]             = "Arbre d'ascendance";
$pgv_lang["individual"]                 = "Individu";
$pgv_lang["as_spouse"]                  = "Conjoint et enfants";
$pgv_lang["privacy_error"]              = "Respect de la vie privée : les détails de cet enregistrement ne sont pas affichés.<br />";
$pgv_lang["more_information"]           = "Pour plus d'informations contacter";
$pgv_lang["given_name"]                 = "Prénom";
$pgv_lang["surname"]                    = "Nom de famille";
$pgv_lang["suffix"]                     = "Suffixe";
$pgv_lang["sex"]                        = "Sexe";
$pgv_lang["personal_facts"]             = "Faits et détails personnels";
$pgv_lang["type"]                       = "Type";
$pgv_lang["parents"]                    = "Parents";
$pgv_lang["siblings"]                   = "Frères et sœurs";
$pgv_lang["father"]                     = "Père";
$pgv_lang["mother"]                     = "Mère";
#pgv_lang["parent"]                     = "Parent";
#pgv_lang["self"]                       = "Self";
$pgv_lang["relatives"]                  = "Famille proche";
$pgv_lang["relatives_events"]           = "Évènements de la famille proche";
$pgv_lang["historical_facts"]           = "Faits historiques";
$pgv_lang["partner"]                    = "Concubin";
$pgv_lang["spouse"]                     = "Conjoint";
$pgv_lang["spouses"]                    = "Conjoints";
$pgv_lang["surnames"]                   = "Noms de famille";
$pgv_lang["adopted"]                    = "Adopté";
$pgv_lang["foster"]                     = "Adoptif";
$pgv_lang["sealing"]                    = "Scellement";
$pgv_lang["challenged"]                 = "Validé";
$pgv_lang["disproved"]                  = "Réfuté";
$pgv_lang["infant"]                     = "Nourrisson";
$pgv_lang["stillborn"]                  = "Mort-né";
$pgv_lang["deceased"]                   = "Décédé";
$pgv_lang["link_as_wife"]               = "Relier cette personne à une famille existante comme épouse";
$pgv_lang["no_tab1"]                    = "Aucun fait lié à cet individu.";
$pgv_lang["no_tab2"]                    = "Aucune note liée à cet individu.";
$pgv_lang["no_tab3"]                    = "Aucune source liée à cet individu.";
$pgv_lang["no_tab4"]                    = "Aucun objet MultiMedia lié à cet individu.";
$pgv_lang["no_tab5"]                    = "Aucun proche lié à cet individu.";
$pgv_lang["no_tab6"]                    = "Aucune recherche liée à cet individu.";
$pgv_lang["show_fact_sources"]          = "Voir toutes les sources";
$pgv_lang["show_fact_notes"]            = "Voir toutes les notes";

//-- FAMILY FILE MESSAGES
$pgv_lang["family_info"]                = "Informations de la famille";
$pgv_lang["family_group_info"]          = "Informations sur la famille";
$pgv_lang["husband"]                    = "Epoux";
$pgv_lang["wife"]                       = "Epouse";
$pgv_lang["marriage"]                   = "Mariage";
$pgv_lang["lds_sealing"]                = "Cérémonie (LDS)";
$pgv_lang["marriage_license"]           = "Autorisation légale de mariage";
$pgv_lang["no_children"]                = "Aucun enfant connu";
$pgv_lang["childless_family"]           = "Famille sans enfant";
$pgv_lang["parents_timeline"]           = "Voir l'échelle de temps";

//-- CLIPPINGS FILE MESSAGES
$pgv_lang["clip_cart"]                  = "Extraction de données";
$pgv_lang["which_links"]                = "Quels autres parents de cette famille souhaitez-vous ajouter ?";
$pgv_lang["just_family"]                = "Ajouter seulement cette famille.";
$pgv_lang["parents_and_family"]         = "Ajouter les parents avec cette famille.";
$pgv_lang["parents_and_child"]          = "Ajouter les parents et les enfants avec la famille.";
$pgv_lang["parents_desc"]               = "Ajouter les parents et tous les descendants avec la famille.";
$pgv_lang["continue"]                   = "Poursuivre la sélection";
$pgv_lang["which_p_links"]              = "Quels autres parents de cette personne souhaitez-vous ajouter ?";
$pgv_lang["just_person"]                = "Ajouter seulement cette personne.";
$pgv_lang["person_parents_sibs"]        = "Ajouter cette personne, ses parents, ses frères et sœurs.";
$pgv_lang["person_ancestors"]           = "Ajouter cette personne et ses ascendants.";
$pgv_lang["person_ancestor_fams"]       = "Ajouter cette personne, ses ascendants et leurs familles.";
$pgv_lang["person_spouse"]              = "Ajouter cette personne, son conjoint et les enfants.";
$pgv_lang["person_desc"]                = "Ajouter cette personne, son conjoint et toute leur descendance.";
$pgv_lang["which_s_links"]              = "Quels enregistrements liés à cette source voulez-vous ajouter ?";
$pgv_lang["just_source"]                = "Ajouter seulement cette source.";
$pgv_lang["linked_source"]              = "Ajouter cette source et les individus/familles qui y sont liés.";
$pgv_lang["person_private"]             = "Respect de la vie privée : les détails personnels sur cet individu ne seront pas inclus.";
$pgv_lang["family_private"]             = "Respect de la vie privée : les détails personnels sur cette famille ne seront pas inclus.";
$pgv_lang["download"]                   = "Faire un clic-droit (ctrl-clic sur Macintoch) sur le lien ci-dessous et sélectionnez «Enregistrer la cible sous...» pour télécharger le fichier.";
$pgv_lang["cart_is_empty"]              = "Votre panier est vide.";
$pgv_lang["id"]                         = "Code";
$pgv_lang["name_description"]           = "Nom / Description";
$pgv_lang["remove"]                     = "Retirer";
$pgv_lang["empty_cart"]                 = "Vider la sélection";
$pgv_lang["download_now"]               = "Télécharger maintenant";
$pgv_lang["download_file"]              = "Télécharger le fichier sur votre système (<i>Download</i>)";
$pgv_lang["indi_downloaded_from"]       = "Provenance de cet individu";
$pgv_lang["family_downloaded_from"]     = "Provenance de cette famille";
$pgv_lang["source_downloaded_from"]     = "Provenance de cette source";

//-- PLACELIST FILE MESSAGES
$pgv_lang["connections"]                = "Liens trouvés avec ces lieux";
$pgv_lang["top_level"]                  = " [sommaire] ";
$pgv_lang["form"]                       = "Les lieux sont classés dans cet ordre :<br />";
$pgv_lang["default_form"]               = "Ville, Département ou District, Région ou Etat, Pays";
$pgv_lang["default_form_info"]          = "(par défaut)";
$pgv_lang["unknown"]                    = "(vide)";
$pgv_lang["individuals"]                = "Individus";
$pgv_lang["view_records_in_place"]      = "Afficher tous les évènements pour ce lieu";
$pgv_lang["place_list2"]                = "Liste des lieux";
$pgv_lang["show_place_hierarchy"]       = "Voir les lieux classés par niveaux";
$pgv_lang["show_place_list"]            = "Voir tous les lieux dans une liste";
$pgv_lang["total_unic_places"]          = "Total lieux uniques";

//-- MEDIALIST FILE MESSAGES
$pgv_lang["external_objects"]           = "Objets externes";
$pgv_lang["multi_title"]                = "Liste des objets MultiMedia";
$pgv_lang["media_found"]                = "Objets MultiMedia trouvés";
$pgv_lang["view_person"]                = "Afficher la personne";
$pgv_lang["view_family"]                = "Afficher la famille";
$pgv_lang["view_source"]                = "Afficher la source";
$pgv_lang["view_object"]                = "Afficher l'objet";
$pgv_lang["prev"]                       = "Précédent";
$pgv_lang["next"]                       = "Suivant";
$pgv_lang["next_image"]                 = "Image suivante";
$pgv_lang["file_not_found"]             = "Fichier non trouvé";
$pgv_lang["medialist_show"]             = "Afficher";
$pgv_lang["per_page"]                   = "Objets MultiMedia par page";
$pgv_lang["media_format"]               = "Format Media";
$pgv_lang["image_size"]                 = "Taille Image";
$pgv_lang["media_id"]                   = "Identifiant MultiMedia";
$pgv_lang["invalid_id"]                 = "Objet non trouvé dans ce fichier GEDCOM.";
$pgv_lang["record_updated"]             = "Enregistrement #pid# mis à jour.";
$pgv_lang["record_not_updated"]         = "Impossible de mettre à jour l'enregistrement #pid#.";
$pgv_lang["record_removed"]             = "Enregistrement #xref# supprimé du fichier GEDCOM.";
$pgv_lang["record_not_removed"]         = "Impossible de supprimer l'enregistrement #xref# du fichier GEDCOM.";
$pgv_lang["record_added"]               = "Enregistrement #xref# ajouté au fichier GEDCOM.";
$pgv_lang["record_not_added"]           = "Impossible d'ajouter l'enregistrement #xref# au fichier GEDCOM.";

//-- SEARCH FILE MESSAGES
$pgv_lang["soundex_search"]             = "Recherche phonétique du nom (méthode SOUNDEX)";
$pgv_lang["sources"]                    = "Sources";
$pgv_lang["lastname_search"]            = "Nom";
$pgv_lang["search_place"]               = "Lieu";
$pgv_lang["search_year"]                = "Année";
$pgv_lang["no_results"]                 = "Recherche infructueuse.";
$pgv_lang["search_soundex"]             = "Recherche phonétique";
$pgv_lang["search_replace"]             = "Recherche et remplace";
$pgv_lang["search_sources"]             = "Sources";
$pgv_lang["search_more_chars"]          = "Entrer au moins un caractère";
$pgv_lang["search_soundextype"]         = "Type de recherche phonétique:";
$pgv_lang["search_russell"]             = "Russell";
$pgv_lang["search_tagfilter"]           = "Exclure les données non généalogiques";
$pgv_lang["search_tagfon"]              = "Oui";
$pgv_lang["search_tagfoff"]             = "Non";
$pgv_lang["associate"]                  = "associé";
$pgv_lang["search_record"]              = "Enregistrement complet";
$pgv_lang["search_to"]                  = "à";

//-- SOURCELIST FILE MESSAGES
$pgv_lang["titles_found"]               = "Titres";
$pgv_lang["find_source"]                = "Choisir une source";

//-- REPOLIST FILE MESSAGES
$pgv_lang["repo_list"]                  = "Liste des dépôts d'archives";
$pgv_lang["repos_found"]                = "Dépôts d'archives trouvés";
$pgv_lang["find_repository"]            = "Choisir un dépôt d'archives";
$pgv_lang["total_repositories"]         = "Nombre de dépôts d'archives";
$pgv_lang["confirm_delete_repo"]        = "Confirmez-vous la suppression de cet élément ?";

//-- SOURCE FILE MESSAGES
$pgv_lang["source_info"]                = "Information sur la source";
$pgv_lang["people"]                     = "Individus";
$pgv_lang["families"]                   = "Familles";
$pgv_lang["total_sources"]              = "Sources";

//-- BUILDINDEX FILE MESSAGES
$pgv_lang["invalid_gedformat"]          = "Format GEDCOM 5.5 non respecté";
$pgv_lang["exec_time"]                  = "Fichier chargé en";
$pgv_lang["unable_to_create_index"]     = "Impossible de créer l'index.<br />Vérifier les droits d'écriture dans le répertoire PhpGedView.";
$pgv_lang["changes_present"]            = "Il reste des modifications en attente de validation pour ce fichier GEDCOM.<br />Elles seront automatiquement validées si vous rechargez le fichier maintenant.";
$pgv_lang["sec"]                        = "sec.";

//-- INDIVIDUAL AND FAMILYLIST FILE MESSAGES
$pgv_lang["total_fams"]                 = " Familles";
$pgv_lang["total_indis"]                = " Individus";
$pgv_lang["notes"]                      = "Notes";
$pgv_lang["ssourcess"]                  = "Sources";
$pgv_lang["media"]                      = "Objets MultiMedia";
$pgv_lang["name_contains"]              = "Le nom contient";
$pgv_lang["filter"]                     = "Filtre";
$pgv_lang["find_individual"]            = "Choisir un individu";
$pgv_lang["find_familyid"]              = "Choisir une famille";
$pgv_lang["find_sourceid"]              = "Choisir une source";
$pgv_lang["find_specialchar"]           = "Saisie des caractères spéciaux";
$pgv_lang["magnify"]                    = "Agrandir";
$pgv_lang["skip_surnames"]              = "Afficher tous les noms";
$pgv_lang["show_surnames"]              = "Afficher la liste des noms";
$pgv_lang["all"]                        = " [Tous] ";
$pgv_lang["hidden"]                     = "Masqués";
$pgv_lang["confidential"]               = "Confidentiel";
$pgv_lang["alpha_index"]                = "Index alphabétique";
$pgv_lang["name_list"]                  = "Liste des noms";
$pgv_lang["firstname_alpha_index"]      = "Index alphabétique par patronyme";
$pgv_lang["roots"]                      = "Racines";
$pgv_lang["leaves"]                     = "Feuilles";
$pgv_lang["widow"]                      = "Veuve";
$pgv_lang["widower"]                    = "Veuf";

//-- TIMELINE FILE MESSAGES
$pgv_lang["age"]                        = "Age";
$pgv_lang["days"]                       = "jours";
$pgv_lang["months"]                     = "mois";
$pgv_lang["years"]                      = "ans";
$pgv_lang["day1"]                       = "jour";
$pgv_lang["month1"]                     = "mois";
$pgv_lang["year1"]                      = "an";
$pgv_lang["after_death"]                = "après le décès";
$pgv_lang["timeline_title"]             = "Echelle de temps";
$pgv_lang["timeline_chart"]             = "Echelle de temps";
$pgv_lang["remove_person"]              = "Retirer cette personne";
$pgv_lang["show_age"]                   = "Afficher le marqueur d'âge";
$pgv_lang["add_another"]                = "Ajouter une personne au diagramme ";
$pgv_lang["find_id"]                    = "Choisir";
$pgv_lang["show"]                       = "Afficher";
$pgv_lang["year"]                       = "Année";
$pgv_lang["timeline_instructions"]      = "Avec un navigateur récent, vous pouvez cliquer sur les cadres et les faire glisser.";
$pgv_lang["zoom_in"]                    = "Zoom avant";
$pgv_lang["zoom_out"]                   = "Zoom arrière";
$pgv_lang["timeline_beginYear"]         = "Année début";
$pgv_lang["timeline_endYear"]           = "Année fin";
$pgv_lang["timeline_scrollSpeed"]       = "Vitesse";
$pgv_lang["timeline_controls"]          = "Actions";
$pgv_lang["include_family"]             = "Inclure la proche famille";
$pgv_lang["lifespan_chart"]             = "Ligne de temps";

// calendar conversion options
$pgv_lang["cal_none"]                   = "Aucune conversion de calendrier";
$pgv_lang["cal_gregorian"]              = "Grégorien";
$pgv_lang["cal_julian"]                 = "Julien";
$pgv_lang["cal_french"]                 = "Français (révolutionnaire)";
$pgv_lang["cal_jewish"]                 = "Israélite";
$pgv_lang["cal_hebrew"]                 = "Hébreu";
$pgv_lang["cal_jewish_and_gregorian"]   = "Israélite et grégorien";
$pgv_lang["cal_hebrew_and_gregorian"]   = "Hébreu et grégorien";
$pgv_lang["cal_hijri"]                  = "Hijri";
$pgv_lang["cal_arabic"]                 = "Arabe";

// some religious dates
$pgv_lang["easter"]                     = "Pâques";
$pgv_lang["ascension"]                  = "Ascension";
$pgv_lang["pentecost"]                  = "Pentecôte";
$pgv_lang["assumption"]                 = "Assomption";
$pgv_lang["all_saints"]                 = "Toussaint";
$pgv_lang["christmas"]                  = "Noël";

// am/pm suffixes for 12 hour clocks
$pgv_lang["a.m."]                       = "matin";
$pgv_lang["p.m."]                       = "après-midi";
$pgv_lang["noon"]                       = "midi";
$pgv_lang["midn"]                       = "minuit";

//-- MONTH NAMES
$pgv_lang["jan"]                        = "Janvier";
$pgv_lang["feb"]                        = "Février";
$pgv_lang["mar"]                        = "Mars";
$pgv_lang["apr"]                        = "Avril";
$pgv_lang["may"]                        = "Mai";
$pgv_lang["jun"]                        = "Juin";
$pgv_lang["jul"]                        = "Juillet";
$pgv_lang["aug"]                        = "Août";
$pgv_lang["sep"]                        = "Septembre";
$pgv_lang["oct"]                        = "Octobre";
$pgv_lang["nov"]                        = "Novembre";
$pgv_lang["dec"]                        = "Décembre";

#pgv_lang["vend"]                       = "Vendémiaire";
#pgv_lang["brum"]                       = "Brumaire";
#pgv_lang["frim"]                       = "Frimaire";
#pgv_lang["nivo"]                       = "Nivôse";
#pgv_lang["pluv"]                       = "Pluviôse";
#pgv_lang["vent"]                       = "Ventôse";
#pgv_lang["germ"]                       = "Germinal";
#pgv_lang["flor"]                       = "Floréal";
#pgv_lang["prai"]                       = "Prairial";
#pgv_lang["mess"]                       = "Messidor";
#pgv_lang["ther"]                       = "Thermidor";
#pgv_lang["fruc"]                       = "Fructidor";
#pgv_lang["comp"]                       = "jours complémentaires";

#pgv_lang["tsh"]                        = "Tishrei";
#pgv_lang["csh"]                        = "Heshvan";
#pgv_lang["ksl"]                        = "Kislev";
#pgv_lang["tvt"]                        = "Tevet";
#pgv_lang["shv"]                        = "Shevat";
#pgv_lang["adr"]                        = "Adar";
#pgv_lang["adr_leap_year"]              = "Adar I";
#pgv_lang["ads"]                        = "Adar II";
#pgv_lang["nsn"]                        = "Nissan";
#pgv_lang["iyr"]                        = "Iyar";
#pgv_lang["svn"]                        = "Sivan";
#pgv_lang["tmz"]                        = "Tamuz";
#pgv_lang["aav"]                        = "Av";
#pgv_lang["ell"]                        = "Elul";

#pgv_lang["muhar"]                      = "Muharram";
#pgv_lang["safar"]                      = "Safar";
#pgv_lang["rabia"]                      = "Rabi' al-awwal";
#pgv_lang["rabit"]                      = "Rabi' al-thani";
#pgv_lang["jumaa"]                      = "Jumada al-awwal";
#pgv_lang["jumat"]                      = "Jumada al-thani";
#pgv_lang["rajab"]                      = "Rajab";
#pgv_lang["shaab"]                      = "Sha'aban";
#pgv_lang["ramad"]                      = "Ramadan";
#pgv_lang["shaww"]                      = "Shawwal";
#pgv_lang["dhuaq"]                      = "Dhu al-Qi'dah";
#pgv_lang["dhuah"]                      = "Dhu al-Hijjah";

$pgv_lang["b.c."]                       = "av.J-C";

$pgv_lang["abt"]                        = "vers";
$pgv_lang["aft"]                        = "après";
$pgv_lang["and"]                        = "et";
$pgv_lang["bef"]                        = "avant";
$pgv_lang["bet"]                        = "entre";
$pgv_lang["cal"]                        = "date calculée";
$pgv_lang["est"]                        = "date estimée";
$pgv_lang["from"]                       = "de";
$pgv_lang["int"]                        = "interprétée";
$pgv_lang["to"]                         = "à";
$pgv_lang["cir"]                        = "environ";
$pgv_lang["apx"]                        = "approx.";

//-- Admin File Messages
$pgv_lang["rebuild_indexes"]            = "Reconstruire les index";
$pgv_lang["password_mismatch"]          = "Mot de passe sans correspondance.";
$pgv_lang["enter_username"]             = "Vous devez entrer un nom d'utilisateur.";
$pgv_lang["enter_password"]             = "Vous devez entrer un mot de passe.";
$pgv_lang["save"]                       = "Enregistrer";
$pgv_lang["saveandgo"]                  = "Enregistrer et ouvrir la nouvelle page";
$pgv_lang["delete"]                     = "Supprimer";
$pgv_lang["edit"]                       = "Modifier";
$pgv_lang["no_login"]                   = "Authentification de l'utilisateur impossible.";
$pgv_lang["basic_realm"]                = "Authentification PhpGedView";
$pgv_lang["basic_auth_failure"]         = "Entrer un login et un mot de passe pour accéder à cette ressource";
$pgv_lang["basic_auth"]                 = "Authentification simple";
#pgv_lang["digest_auth"]                = "Digest Authentication"; //not used in code yet
$pgv_lang["no_auth_needed"]             = "Pas d'authentification";
$pgv_lang["file_not_exists"]            = "Le fichier n'existe pas.";
$pgv_lang["research_assistant"]         = "Assistant de recherches";
$pgv_lang["utf8_to_ansi"]               = "Convertir ce fichier GEDCOM format UTF-8 en format ANSI (ISO-8859-1) ?";
$pgv_lang["media_linked"]               = "Cet objet MultiMedia est relié à :";
$pgv_lang["media_not_linked"]           = "Cet objet MultiMedia n'est relié à aucun enregistrement GEDCOM.";
#pgv_lang["media_dir_1"]                = "This media object is located on an external server";
#pgv_lang["media_dir_2"]                = "This media object is in the standard media directory";
#pgv_lang["media_dir_3"]                = "This media object is in the protected media directory";
#pgv_lang["thumb_dir_1"]                = "This thumbnail is located on an external server";
#pgv_lang["thumb_dir_2"]                = "This thumbnail is in the standard media directory";
#pgv_lang["thumb_dir_3"]                = "This thumbnail is in the protected media directory";
#pgv_lang["moveto_2"]                   = "Move to protected directory";
#pgv_lang["moveto_3"]                   = "Move to standard directory";
#pgv_lang["move_standard"]              = "Move to standard";
#pgv_lang["move_protected"]             = "Move to protected";
#pgv_lang["move_mediadirs"]             = "Move Media directories";
#pgv_lang["setperms"]                   = "Set Media Permissions";
#pgv_lang["setperms_writable"]          = "Make World-Writable";
#pgv_lang["setperms_readonly"]          = "Make World-Read Only";
#pgv_lang["setperms_success"]           = "Permissions Set";
#pgv_lang["setperms_failure"]           = "Permissions Not Set";
#pgv_lang["setperms_time_exceeded"]     = "The execution time limit was reached.  Try the command again on a smaller directory.";
#pgv_lang["move_time_exceeded"]         = "The execution time limit was reached.  Try the command again to move the rest of the files.";
#pgv_lang["media_firewall_rootdir_no_exist"]= "The Media Firewall root directory you requested does not exist.  You must create it first.";
#pgv_lang["media_firewall_protected_dir_no_exist"]= "The protected media directory could not be created in the Media Firewall root directory.  Please create this directory and make it world-writable.";
#pgv_lang["media_firewall_protected_dir_not_writable"]	= "The protected media directory in the Media Firewall root directory is not world writable. ";
#pgv_lang["media_firewall_invalid_dir"] = "Error: The Media Firewall was launched from a directory other than the media directory.";

//-- Relationship chart messages
$pgv_lang["relationship_great"]         = "Grand";
$pgv_lang["relationship_chart"]         = "Parenté";
$pgv_lang["person1"]                    = "Personne 1";
$pgv_lang["person2"]                    = "Personne 2";
$pgv_lang["no_link_found"]              = "Aucun (autre) lien trouvé entre les deux individus.";
$pgv_lang["sibling"]                    = "Frère/Sœur";
$pgv_lang["follow_spouse"]              = "Suivre les liens par mariage.";
$pgv_lang["timeout_error"]              = "La recherche s'est achevée avant qu'un lien de parenté ne soit trouvé.";
$pgv_lang["grandchild"]                 = "Petit-enfant";
$pgv_lang["grandson"]                   = "Petit-fils";
$pgv_lang["granddaughter"]              = "Petite-fille";
$pgv_lang["greatgrandchild"]            = "Arrière-petit-enfant";
$pgv_lang["greatgrandson"]              = "Arrière-petit-fils";
$pgv_lang["greatgranddaughter"]         = "Arrière-petite-fille";
$pgv_lang["brother"]                    = "Frère";
$pgv_lang["sister"]                     = "Sœur";
$pgv_lang["aunt"]                       = "Tante";
$pgv_lang["uncle"]                      = "Oncle";
$pgv_lang["nephew"]                     = "Neveu";
$pgv_lang["niece"]                      = "Nièce";
$pgv_lang["firstcousin"]                = "Cousin(e) germain(e)";
$pgv_lang["femalecousin"]               = "Cousine";
$pgv_lang["malecousin"]                 = "Cousin";
$pgv_lang["relationship_to_me"]         = "Parenté avec moi";
$pgv_lang["rela_husb"]                  = "Parenté avec l'époux";
$pgv_lang["rela_wife"]                  = "Parenté avec l'épouse";
$pgv_lang["next_path"]                  = "Chemin suivant";
$pgv_lang["show_path"]                  = "Voir le chemin";
$pgv_lang["line_up_generations"]        = "Aligner par génération";
$pgv_lang["oldest_top"]                 = "Afficher les parents en haut";

// %1\$s replaced by first person, %2\$s by the relationship and %3\$s by the second person.
$pgv_lang["relationship_male_1_is_the_2_of_3"]= "%1\$s est le %2\$s de %3\$s.";
$pgv_lang["relationship_female_1_is_the_2_of_3"]= "%1\$s est la %2\$s de %3\$s.";

$pgv_lang["mother_in_law"]              = "Belle-mère";
$pgv_lang["father_in_law"]              = "Beau-père";
$pgv_lang["brother_in_law"]             = "Beau-frère";
$pgv_lang["sister_in_law"]              = "Belle-sœur";
$pgv_lang["son_in_law"]                 = "Gendre";
$pgv_lang["daughter_in_law"]            = "Belle-fille";
$pgv_lang["cousin_in_law"]              = "Cous. par alliance";

$pgv_lang["step_son"]                   = "Beau-fils";
$pgv_lang["step_daughter"]              = "Belle-fille";

// the bosa_brothers_offspring name is used for fraternal nephews and nieces - the names below can be extended to any number
// of generations just by adding more translations.
// 1st generation
$pgv_lang["bosa_brothers_offspring_2"]  = "neveu";             // brother's son
$pgv_lang["bosa_brothers_offspring_3"]  = "nièce";            // brother's daughter
// 2nd generation
$pgv_lang["bosa_brothers_offspring_4"]  = "petit-neveu";       // brother's son's son 
$pgv_lang["bosa_brothers_offspring_5"]  = "petite-nièce";     // brother's son's daughter
$pgv_lang["bosa_brothers_offspring_6"]  = "petit-neveu";       // brother's daughter's son
$pgv_lang["bosa_brothers_offspring_7"]  = "petite-nièce";     // brother's daughter's daughter
// for the general case of offspring of the nth generation use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_brothers_son"]           = "%2\$d x arrière petit-neveu";
$pgv_lang["n_x_brothers_daughter"]      = "%2\$d x arrière petite-nièce";
// the bosa_sisters_offspring name is used for sisters nephews and nieces - the names below can be extended to any number
// of generations just by adding more translations.
// 1st generation
$pgv_lang["bosa_sisters_offspring_2"]   = "neveu";             // sister's son
$pgv_lang["bosa_sisters_offspring_3"]   = "nièce";              // sister's daughter
// 2nd generation
$pgv_lang["bosa_sisters_offspring_4"]   = "petit-neveu";       // sister's son's son 
$pgv_lang["bosa_sisters_offspring_5"]   = "petite-nièce";        // sister's son's daughter
$pgv_lang["bosa_sisters_offspring_6"]   = "petit-neveu";       // sister's daughter's son
$pgv_lang["bosa_sisters_offspring_7"]   = "petite-nièce";        // sister's daughter's daughter
// for the general case of offspring of the nth generation use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_sisters_son"]            = "%2\$d x arrière petit-neveu";
$pgv_lang["n_x_sisters_daughter"]       = "%2\$d x arrière petite-nièce";

// the bosa name is used for offspring - the names below can be extended to any number
// of generations just by adding more translations.
// 1st generation
$pgv_lang["bosa_2"]                     = "fils";                   // son
$pgv_lang["bosa_3"]                     = "fille";              // daughter
// 2nd generation
$pgv_lang["bosa_4"]                     = "petit-fils";            // son's son 
$pgv_lang["bosa_5"]                     = "petite-fille";           // son's daughter
$pgv_lang["bosa_6"]                     = "petit-fils";            // daughter's son
$pgv_lang["bosa_7"]                     = "petite-fille";           // daughter's daughter
// 3rd generation
$pgv_lang["bosa_8"]                     = "arrière petit-fils";    // son's son's son   
$pgv_lang["bosa_9"]                     = "arrière petite-fille";   // son's son's daughter
$pgv_lang["bosa_10"]                    = "arrière petit-fils";	// son's daughters son
$pgv_lang["bosa_11"]                    = "arrière petite-fille";   // son's daughters daughter
$pgv_lang["bosa_12"]                    = "arrière petit-fils";    // daughter's son's son 
$pgv_lang["bosa_13"]                    = "arrière petite-fille";   // daughter's son's daughter
$pgv_lang["bosa_14"]                    = "arrière petit-fils";	// daughter's daughters son
$pgv_lang["bosa_15"]                    = "arrière petite-fille";   // daughter's daughters daughter
// for the general case of offspring of the nth generation use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_grandson_from_son"]      = "%3\$d x arrière petit-fils";
$pgv_lang["n_x_granddaughter_from_son"] = "%3\$d x arrière petite-fille";
$pgv_lang["n_x_grandson_from_daughter"] = "%3\$d x arrière petit-fils";
$pgv_lang["n_x_granddaughter_from_daughter"]= "%3\$d x arrière petite-fille";

// the sosa_uncle name is used for uncles - the names below can be extended to any number
// of generations just by adding more translations.
// to allow fo language variations we specify different relationships for paternal and maternal
// aunts and uncles
// 1st generation
$pgv_lang["sosa_uncle_2"]               = "oncle"; // fathers brother
$pgv_lang["sosa_uncle_3"]               = "oncle"; // mothers brother
// 2nd generation
$pgv_lang["sosa_uncle_4"]               = "grand-oncle";      // fathers's fathers brother 
$pgv_lang["sosa_uncle_5"]               = "grand-oncle";      // fathers mothers brother
$pgv_lang["sosa_uncle_6"]               = "grand-oncle";      // mothers fathers brother
$pgv_lang["sosa_uncle_7"]               = "grand-oncle";      // mothers mothers brother
// for the general case of uncles of the nth degree use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_paternal_uncle"]         = "%2\$d x arrière grand-oncle";
$pgv_lang["n_x_maternal_uncle"]         = "%2\$d x arrière grand-oncle";

// the sosa_aunt name is used for aunts - the names below can be extended to any number
// of generations just by adding more translations.
// to allow fo language variations we specify different relationships for paternal and maternal
// aunts and aunts
// 1st generation
$pgv_lang["sosa_aunt_2"]                = "tante";  // fathers sister
$pgv_lang["sosa_aunt_3"]                = "tante";  // mothers sister
// 2nd generation
$pgv_lang["sosa_aunt_4"]                = "grand-tante";      // fathers's fathers sister 
$pgv_lang["sosa_aunt_5"]                = "grand-tante";      // fathers mothers sister
$pgv_lang["sosa_aunt_6"]                = "grand-tante";      // mothers fathers sister
$pgv_lang["sosa_aunt_7"]                = "grand-tante";      // mothers mothers sister
// for the general case of aunts of the nth degree use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_paternal_aunt"]          = "%2\$d x ";
$pgv_lang["n_x_maternal_aunt"]          = "%2\$d x ";

// the sosa_uncle name is used for uncles(by marriage) - the names below can be extended to any number
// of generations just by adding more translations.
// to allow fo language variations we specify different relationships for paternal and maternal
// aunts and uncles
// 1st generation
$pgv_lang["sosa_uncle_bm_2"]            = "oncle"; // fathers brother
$pgv_lang["sosa_uncle_bm_3"]            = "oncle"; // mothers brother
// 2nd generation
$pgv_lang["sosa_uncle_bm_4"]            = "grand-oncle";      // fathers's fathers brother 
$pgv_lang["sosa_uncle_bm_5"]            = "grand-oncle";      // fathers mothers brother
$pgv_lang["sosa_uncle_bm_6"]            = "grand-oncle";      // mothers fathers brother
$pgv_lang["sosa_uncle_bm_7"]            = "grand-oncle";      // mothers mothers brother
// for the general case of uncles of the nth degree use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_paternal_uncle_bm"]      = "%2\$d x arrière grand-oncle";
$pgv_lang["n_x_maternal_uncle_bm"]      = "%2\$d x arrière grand-oncle";

// the sosa_aunt name is used for aunts (by marriage)- the names below can be extended to any number
// of generations just by adding more translations.
// to allow fo language variations we specify different relationships for paternal and maternal
// aunts and aunts
// 1st generation
$pgv_lang["sosa_aunt_bm_2"]             = "tante";  // fathers sister
$pgv_lang["sosa_aunt_bm_3"]             = "tante";  // mothers sister
// 2nd generation
$pgv_lang["sosa_aunt_bm_4"]             = "grand-tante";      // fathers's fathers sister 
$pgv_lang["sosa_aunt_bm_5"]             = "grand-tante";      // fathers mothers sister
$pgv_lang["sosa_aunt_bm_6"]             = "grand-tante";      // mothers fathers sister
$pgv_lang["sosa_aunt_bm_7"]             = "grand-tante";      // mothers mothers sister
// for the general case of aunts of the nth degree use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_paternal_aunt_bm"]       = "%2\$d x ";
$pgv_lang["n_x_maternal_aunt_bm"]       = "%2\$d x ";

// if a specific cousin relationship cannot be represented in a language translate as "";
$pgv_lang["male_cousin_1"]              = "cousin germain";
$pgv_lang["male_cousin_2"]              = "cousin issu de germain";
$pgv_lang["male_cousin_3"]              = "cousin issu d'issu de germain";
$pgv_lang["male_cousin_4"]              = "";
$pgv_lang["male_cousin_5"]              = "";
$pgv_lang["male_cousin_6"]              = "";
$pgv_lang["male_cousin_7"]              = "";
$pgv_lang["male_cousin_8"]              = "";
$pgv_lang["male_cousin_9"]              = "";
$pgv_lang["male_cousin_10"]             = "";
$pgv_lang["male_cousin_11"]             = "";
$pgv_lang["male_cousin_12"]             = "";
$pgv_lang["male_cousin_13"]             = "";
$pgv_lang["male_cousin_14"]             = "";
$pgv_lang["male_cousin_15"]             = "";
$pgv_lang["male_cousin_16"]             = "";
$pgv_lang["male_cousin_17"]             = "";
$pgv_lang["male_cousin_18"]             = "";
$pgv_lang["male_cousin_19"]             = "";
$pgv_lang["male_cousin_20"]             = "";
$pgv_lang["male_cousin_n"]              = "";
$pgv_lang["female_cousin_1"]            = "cousine germaine";
$pgv_lang["female_cousin_2"]            = "cousine issue de germaine";
$pgv_lang["female_cousin_3"]            = "cousine issue d'issue de germaine";
$pgv_lang["female_cousin_4"]            = "";
$pgv_lang["female_cousin_5"]            = "";
$pgv_lang["female_cousin_6"]            = "";
$pgv_lang["female_cousin_7"]            = "";
$pgv_lang["female_cousin_8"]            = "";
$pgv_lang["female_cousin_9"]            = "";
$pgv_lang["female_cousin_10"]           = "";
$pgv_lang["female_cousin_11"]           = "";
$pgv_lang["female_cousin_12"]           = "";
$pgv_lang["female_cousin_13"]           = "";
$pgv_lang["female_cousin_14"]           = "";
$pgv_lang["female_cousin_15"]           = "";
$pgv_lang["female_cousin_16"]           = "";
$pgv_lang["female_cousin_17"]           = "";
$pgv_lang["female_cousin_18"]           = "";
$pgv_lang["female_cousin_19"]           = "";
$pgv_lang["female_cousin_20"]           = "";
$pgv_lang["female_cousin_n"]            = "";

// Only referenced from english specific functions
#pgv_lang["removed_ascending_1"]        = " once removed ascending";
#pgv_lang["removed_ascending_2"]        = " twice removed ascending";
#pgv_lang["removed_ascending_3"]        = " three times removed ascending";
#pgv_lang["removed_ascending_4"]        = " four times removed ascending";
#pgv_lang["removed_ascending_5"]        = " five times removed ascending";
#pgv_lang["removed_ascending_6"]        = " six times removed ascending";
#pgv_lang["removed_ascending_7"]        = " seven times removed ascending";
#pgv_lang["removed_ascending_8"]        = " eight times removed ascending";
#pgv_lang["removed_ascending_9"]        = " nine times removed ascending";
#pgv_lang["removed_ascending_10"]       = " ten times removed ascending";
#pgv_lang["removed_ascending_11"]       = " eleven times removed ascending";
#pgv_lang["removed_ascending_12"]       = " twelve times removed ascending";
#pgv_lang["removed_ascending_13"]       = " thirteen times removed ascending";
#pgv_lang["removed_ascending_14"]       = " fourteen times removed ascending";
#pgv_lang["removed_ascending_15"]       = " fifteen times removed ascending";
#pgv_lang["removed_ascending_16"]       = " sixteen times removed ascending";
#pgv_lang["removed_ascending_17"]       = " seventeen times removed ascending";
#pgv_lang["removed_ascending_18"]       = " eighteen times removed ascending";
#pgv_lang["removed_ascending_19"]       = " nineteen times removed ascending";
#pgv_lang["removed_ascending_20"]       = " twenty times removed ascending";
#pgv_lang["removed_descending_1"]       = " once removed descending";
#pgv_lang["removed_descending_2"]       = " twice removed descending";
#pgv_lang["removed_descending_3"]       = " three times removed descending";
#pgv_lang["removed_descending_4"]       = " four times removed descending";
#pgv_lang["removed_descending_5"]       = " five times removed descending";
#pgv_lang["removed_descending_6"]       = " six times removed descending";
#pgv_lang["removed_descending_7"]       = " seven times removed descending";
#pgv_lang["removed_descending_8"]       = " eight times removed descending";
#pgv_lang["removed_descending_9"]       = " nine times removed descending";
#pgv_lang["removed_descending_10"]      = " ten times removed descending";
#pgv_lang["removed_descending_11"]      = " eleven times removed descending";
#pgv_lang["removed_descending_12"]      = " twelve times removed descending";
#pgv_lang["removed_descending_13"]      = " thirteen times removed descending";
#pgv_lang["removed_descending_14"]      = " fourteen times removed descending";
#pgv_lang["removed_descending_15"]      = " fifteen times removed descending";
#pgv_lang["removed_descending_16"]      = " sixteen times removed descending";
#pgv_lang["removed_descending_17"]      = " seventeen times removed descending";
#pgv_lang["removed_descending_18"]      = " eighteen times removed descending";
#pgv_lang["removed_descending_19"]      = " nineteen times removed descending";
#pgv_lang["removed_descending_20"]      = " twenty times removed descending";

//-- GEDCOM edit utility
$pgv_lang["check_delete"]               = "Confirmez-vous la suppression de cet élément ?";
$pgv_lang["access_denied"]              = "<b>Accès interdit</b><br />Vous n'avez pas accès cette ressource";
$pgv_lang["changes_exist"]              = "<span class='warning'>Ce fichier GEDCOM a été modifié</span>&nbsp;&nbsp;";
$pgv_lang["find_place"]                 = "Choisir un lieu";
$pgv_lang["close_window"]               = "Fermer la fenêtre";
$pgv_lang["close_window_without_refresh"]= "Fermer la fenêtre sans rafraîchir";
$pgv_lang["place_contains"]             = "Le lieu contient";
$pgv_lang["add"]                        = "Ajouter";
$pgv_lang["custom_event"]               = "Evénement personnalisé";
$pgv_lang["delete_person"]              = "Supprimer la fiche de cet individu";
$pgv_lang["confirm_delete_person"]      = "Confirmez-vous la suppression de cette fiche ?";
$pgv_lang["find_media"]                 = "Choisir un objet MultiMedia";
$pgv_lang["set_link"]                   = "Mettre un lien";
$pgv_lang["delete_source"]              = "Supprimer cette source";
$pgv_lang["confirm_delete_source"]      = "Confirmez-vous la suppression de cette source ?";
$pgv_lang["find_family"]                = "Choisir une famille";
$pgv_lang["find_fam_list"]              = "Liste famille ?";
$pgv_lang["edit_name"]                  = "Modifier le nom";
$pgv_lang["delete_name"]                = "Supprimer le nom";
$pgv_lang["select_date"]                = "Choisir une date";
$pgv_lang["user_cannot_edit"]           = "Cet utilisateur ne peut modifier le fichier GEDCOM.";
$pgv_lang["ged_noshow"]                 = "Cette page a été désactivée par l'administrateur.";

//-- calendar.php messages
$pgv_lang["bdm"]                        = "Naissances|Mariages|Décès";
$pgv_lang["on_this_day"]                = "Ce jour-là...";
$pgv_lang["in_this_month"]              = "Ce mois-là...";
$pgv_lang["in_this_year"]               = "Cette année-là...";
$pgv_lang["year_anniversary"]           = "#year_var# anniversaire";
$pgv_lang["today"]                      = "Aujourd'hui";
$pgv_lang["day"]                        = "Jour";
$pgv_lang["month"]                      = "Mois";
$pgv_lang["showcal"]                    = "Montrer les évènements de";
$pgv_lang["anniversary"]                = "Anniversaire";
$pgv_lang["anniversary_calendar"]       = "Calendrier";
$pgv_lang["sunday"]                     = "Dimanche";
$pgv_lang["monday"]                     = "Lundi";
$pgv_lang["tuesday"]                    = "Mardi";
$pgv_lang["wednesday"]                  = "Mercredi";
$pgv_lang["thursday"]                   = "Jeudi";
$pgv_lang["friday"]                     = "Vendredi";
$pgv_lang["saturday"]                   = "Samedi";
$pgv_lang["viewday"]                    = "Anniversaires du jour";
$pgv_lang["viewmonth"]                  = "Anniversaires du mois";
$pgv_lang["viewyear"]                   = "Anniversaires de l'année";
$pgv_lang["all_people"]                 = "Toutes les personnes";
$pgv_lang["living_only"]                = "Les personnes vivantes seulement";
$pgv_lang["recent_events"]              = "Evénements récents (- 100 ans)";
$pgv_lang["day_not_set"]                = "Jour absent";

//-- user self registration module
$pgv_lang["lost_password"]              = "Mot de passe perdu ?";
$pgv_lang["requestpassword"]            = "Demander un nouveau mot de passe";
$pgv_lang["no_account_yet"]             = "Vous n'êtes pas encore inscrit ?";
$pgv_lang["requestaccount"]             = "Demander un compte utilisateur";
$pgv_lang["emailadress"]                = "Adresse courriel";
$pgv_lang["mandatory"]                  = "Les champs marqués * sont obligatoires.";
$pgv_lang["mail01_line01"]              = "Bonjour #user_fullname# ...";
$pgv_lang["mail01_line02"]              = "Une demande a été adressée à ( #SERVER_NAME# ) pour une connexion avec votre adresse courriel ( #user_email# ).";
$pgv_lang["mail01_line03"]              = "Les informations suivantes ont été utilisées.";
$pgv_lang["mail01_line04"]              = "Merci de cliquer sur le lien ci-dessous et de renseigner les champs demandés pour vérifier votre compte et l'adresse courriel.";
$pgv_lang["mail01_line05"]              = "Si vous n'avez pas demandé une inscription vous pouvez supprimer ce message.";
$pgv_lang["mail01_line06"]              = "Vous ne recevrez plus de messages de ce système parce que, manquant une réponse de votre part dans 7 jours, ce compte sera supprimé.";
$pgv_lang["mail01_subject"]             = "Votre inscription sur #SERVER_NAME#";

$pgv_lang["mail02_line01"]              = "Bonjour à l'administrateur ...";
$pgv_lang["mail02_line02"]              = "Un nouvel utilisateur s'est inscrit sur ( #SERVER_NAME# ).";
$pgv_lang["mail02_line03"]              = "L'utilisateur a reçu un message avec les informations nécessaires à la vérification de son compte.";
$pgv_lang["mail02_line04"]              = "Dès que l'utilisateur aura fait la vérification vous serez informé par message afin que vous puissiez l'autoriser à se connecter à votre site.";
$pgv_lang["mail02_line04a"]             = "Vous serez averti par courriel lorsque l'utilisateur aura fait la vérification de son compte. Il pourra se connecter sans action de votre part.";
$pgv_lang["mail02_subject"]             = "Nouvelle inscription sur #SERVER_NAME#";

$pgv_lang["hashcode"]                   = "Code de vérification";
$pgv_lang["thankyou"]                   = "Bonjour #user_fullname# ...<br />Merci pour votre inscription";
$pgv_lang["pls_note06"]                 = "Vous allez recevoir un message de confirmation à l'adresse ( #user_email# ).<br /><br />En suivant les instructions de ce message vous pourrez activer votre compte.<br /><br />Si vous n'activez pas votre compte avant sept jours, il sera supprimé<br />(vous pourrez vous enregistrer à nouveau dans ce cas).<br /><br />Pour vous connecter au site, votre nom de connexion et votre mot de passe sont nécessaires.";
$pgv_lang["pls_note06a"]                = "Vous allez recevoir un message de confirmation à l'adresse ( #user_email# ). En suivant les instructions de ce message vous pourrez activer votre compte. Si vous n'activez pas votre compte avant sept jours, il sera supprimé (vous pourrez vous enregistrer à nouveau dans ce cas). Apres activation de votre compte, vous pourrez vous connecter au site.";

$pgv_lang["registernew"]                = "Confirmation du nouveau compte";
$pgv_lang["user_verify"]                = "Verification de l'utilisateur";
$pgv_lang["send"]                       = "Envoyer";

$pgv_lang["pls_note07"]                 = "Merci d'entrer votre identifiant, votre mot de passe et le code de vérification que vous avez reçu par courriel afin de vérifier votre demande de compte.";
$pgv_lang["pls_note08"]                 = "Les informations de l'utilisateur #user_name# ont été controlées.";

$pgv_lang["mail03_line01"]              = "Bonjour à l'administrateur ...";
$pgv_lang["mail03_line02"]              = "#newuser[username]# ( #newuser[fullname]# ) a vérifié les informations de son inscription.";
$pgv_lang["mail03_line03"]              = "Merci de cliquer sur le lien ci-dessous pour vous connecter au site et donner à l'utilisateur l'autorisation de se connecter.";
$pgv_lang["mail03_line03a"]             = "L'utilisateur peut maintenant se connecter sans action de votre part.";
$pgv_lang["mail03_subject"]             = "Nouvelle vérification sur #SERVER_NAME#";

$pgv_lang["pls_note09"]                 = "Vous avez été identifié comme un utilisateur inscrit.";
$pgv_lang["pls_note10"]                 = "L'administrateur a été informé.<br />Vous pourrez vous connecter avec votre identifiant de connexion et votre mot de passe<br />dès qu'il vous en aura donné l'autorisation.";
$pgv_lang["pls_note10a"]                = "Vous pouvez maintenant vous connecter avec votre nom de compte et votre mot de passe.";
$pgv_lang["data_incorrect"]             = "Informations incorrectes!<br />Merci de réessayer!";
$pgv_lang["user_not_found"]             = "Identification impossible. Merci de réessayer";

$pgv_lang["lost_pw_reset"]              = "Demande de mot de passe perdu";
$pgv_lang["pls_note11"]                 = "Pour restaurer votre mot de passe, fournissez votre nom d'utilisateur PhpGedView sur ce site et l'adresse courriel associée à ce compte.<br /><br />Vous recevrez par courriel une URL spéciale qui contiendra un code de confirmation de votre compte. Vous pourrez alors vous connecter et ensuite changer votre mot de passe.<br /><br />Par mesure de sécurité, vous ne devez fournir votre code de confirmation à personne, même pas aux administrateurs de ce site (ils ne vous le demanderont pas).<br /><br />Si vous avez besoin d'aide de la part de l'administrateur du site, faites une demande d'assistance.";

$pgv_lang["mail04_line01"]              = "Bonjour #user_fullname# ...";
$pgv_lang["mail04_line02"]              = "Un nouveau mot de passe a été demandé pour votre compte!";
$pgv_lang["mail04_line03"]              = "Recommandation:";
$pgv_lang["mail04_line04"]              = "Cliquez maintenant sur le lien ci-dessous, connectez-vous avec le nouveau mot de passe et, par précaution, changez-le immediatement.";
$pgv_lang["mail04_line05"]              = "Pour changer votre mot de passe, une fois connecté, cliquez le lien '#pgv_lang[myuserdata]#' dans le menu '#pgv_lang[mygedview]#' et remplissez les champs concernant le mot de passe.";
$pgv_lang["mail04_subject"]             = "Demande d'informations pour #SERVER_NAME#";

$pgv_lang["pwreqinfo"]                  = "Bonjour...<br /><br />Un message a été envoyé à l'adresse (#user[email]#) avec le nouveau mot de passe.<br /><br />Merci de vérifier votre messagerie car vous devriez recevoir ce message dans les prochaines minutes.<br /><br />Recommandation:<br /><br />Après avoir récupéré ce message, connectez-vous à ce site et changez votre mot de passe pour conserver l'integrité de vos données.";

$pgv_lang["myuserdata"]                 = "Mon compte";
$pgv_lang["user_theme"]                 = "Mon thème";
$pgv_lang["mgv"]                        = "Mon portail";
$pgv_lang["mygedview"]                  = "Mon portail";
$pgv_lang["passwordlength"]             = "Le mot de passe doit contenir au moins 6 caractères.";
$pgv_lang["welcome_text_auth_mode_1"]   =	"<b>BIENVENUE</b><br /><br />L'accès à ce site est autorisé à tous les visiteurs ayant un compte.<br />Si vous avez déjà un compte, vous pouvez vous connecter.<br /><br />Sinon, remplissez le formulaire.<br />Après vérification, l'administrateur activera votre compte.<br />Vous recevrez un message d'information.";
$pgv_lang["welcome_text_auth_mode_2"]   =	"<b>BIENVENUE</b><br /><br />L'accès à ce site est réservé aux utilisateurs <b>autorisés</b>.<br />Si vous avez déjà un compte, vous pouvez vous connecter.<br /><br />Sinon, remplissez le formulaire.<br />Après vérification, l'administrateur acceptera ou refusera votre demande.<br />Vous recevrez un message d'information.";
$pgv_lang["welcome_text_auth_mode_3"]   =	"<b>BIENVENUE</b><br /><br />L'accès à ce site est réservé aux utilisateurs <b>membres de la famille</b>.<br />Si vous avez déjà un compte, vous pouvez vous connecter.<br /><br />Sinon, remplissez le formulaire.<br />Après vérification, l'administrateur acceptera ou refusera votre demande.<br />Vous recevrez un message d'information.";
$pgv_lang["welcome_text_cust_head"]     =	"<b>BIENVENUE</b><br /><br />L'accès à ce site est autorisé aux utilisateurs ayant un compte et un mot de passe.<br />";
$pgv_lang["acceptable_use"]             = "<div class=\"largeError\">Attention:</div><div class=\"error\">En renseignant et en soumettant ce formulaire, vous acceptez:<ul><li>de protéger la vie privée des personnes vivantes qui sont renseignées sur notre site;</li><li>et dans la boite textuelle ci-dessous, vous acceptez ou bien d'expliquer avec qui vous avez un lien de parenté, ou alors vous nous communiquez des informations sur une personne qui devrait apparaître sur notre site.</li></ul></div>";


//-- mygedview page
$pgv_lang["upcoming_events"]            = "Prochains anniversaires";
$pgv_lang["living_or_all"]              = "Voir seulement les personnes vivantes ?";
$pgv_lang["basic_or_all"]               = "Voir seulement Naissances, Mariages et Décès ?";
$pgv_lang["style"]                      = "Style de présentation";
$pgv_lang["style1"]                     = "Liste";
$pgv_lang["style2"]                     = "Table";
$pgv_lang["style3"]                     = "Nuage de mots";
$pgv_lang["cal_download"]               = "Afficher le bouton de téléchargement des évènements au format hcal ?";
$pgv_lang["no_events_living"]           = "Aucun évènement pour une personne vivante dans les #pgv_lang[global_num1]# prochains jours.";
$pgv_lang["no_events_living1"]          = "Aucun évènement pour une personne vivante pour demain.";
$pgv_lang["no_events_all"]              = "Aucun évènement dans les #pgv_lang[global_num1]# prochains jours.";
$pgv_lang["no_events_all1"]             = "Aucun évènement pour demain.";
$pgv_lang["no_events_privacy"]          = "Des évènements existent dans les #pgv_lang[global_num1]# prochains jours, mais leur accès est restreint.";
$pgv_lang["no_events_privacy1"]         = "Des évènements existent pour demain, mais leur accès est restreint.";
$pgv_lang["more_events_privacy"]        = "<br />d'autres évènements existent dans les #pgv_lang[global_num1]# prochains jours, mais leur accès est restreint.";
$pgv_lang["more_events_privacy1"]       = "<br />d'autres évènements existent pour demain, mais leur accès est restreint.";
$pgv_lang["none_today_living"]          = "Aucun évènement pour une personne vivante pour aujourd'hui.";
$pgv_lang["none_today_all"]             = "Aucun évènement pour aujourd'hui.";
$pgv_lang["none_today_privacy"]         = "Des évènements existent pour aujourd'hui, mais leur accès est restreint.";
$pgv_lang["more_today_privacy"]         = "<br />d'autres évènements existent pour aujourd'hui, mais leur accès est restreint.";
$pgv_lang["chat"]                       = "Discussion";
$pgv_lang["users_logged_in"]            = "Utilisateurs connectés";
$pgv_lang["anon_user"]                  = "1 utilisateur anonyme connecté";
$pgv_lang["anon_users"]                 = "#pgv_lang[global_num1]# utilisateurs anonymes connectés";
$pgv_lang["login_user"]                 = "1 utilisateur connecté";
$pgv_lang["login_users"]                = "#pgv_lang[global_num1]# utilisateurs connectés";
$pgv_lang["no_login_users"]             = "Aucun utilisateur connecté";
$pgv_lang["message"]                    = "Envoi de message";
$pgv_lang["my_messages"]                = "Mes messages";
$pgv_lang["date_created"]               = "Date d'envoi";
$pgv_lang["message_from"]               = "Adresse courriel";
$pgv_lang["message_from_name"]          = "Votre nom";
$pgv_lang["message_to"]                 = "Destinataire";
$pgv_lang["message_subject"]            = "Objet";
$pgv_lang["message_body"]               = "Texte";
$pgv_lang["no_to_user"]                 = "Pas de destinataire. Impossible de continuer.";
$pgv_lang["provide_email"]              = "Merci d'indiquer votre adresse courriel.<br />Sans cette adresse, nous ne pourrons pas vous répondre.<br />Votre adresse ne sera utilisée que pour faire cette réponse.";
$pgv_lang["reply"]                      = "Réponse";
$pgv_lang["message_deleted"]            = "Message supprimé";
$pgv_lang["message_sent"]               = "Message envoyé";
$pgv_lang["reset"]                      = "Restaurer";
$pgv_lang["site_default"]               = "Par défaut pour le site";
$pgv_lang["mygedview_desc"]             = "Pour organiser vos favoris, suivre les anniversaires, échanger avec les autres utilisateurs...";
$pgv_lang["no_messages"]                = "Vous n'avez pas de messages en attente.";
$pgv_lang["clicking_ok"]                = "En cliquant sur OK, vous ouvrirez une autre fenêtre où vous pourrez contacter #user[fullname]#";
$pgv_lang["favorites"]                  = "Favoris";
$pgv_lang["my_favorites"]               = "Mes favoris";
$pgv_lang["no_favorites"]               = "Vous n'avez pas sélectionné de favoris. Pour ajouter un individu à vos favoris, lancez une recherche et cliquez sur <b>Ajouter</b> ou utilisez la case ci-dessous pour entrer un identifiant.";
$pgv_lang["add_to_my_favorites"]        = "Ajouter à mes favoris";
$pgv_lang["gedcom_favorites"]           = "Favoris";
$pgv_lang["no_gedcom_favorites"]        = "L'administrateur n'a sélectionné aucun favori.";
$pgv_lang["confirm_fav_remove"]         = "Confirmez-vous la suppression de ce favori ?";
$pgv_lang["invalid_email"]              = "Merci de fournir une adresse courriel valide.";
$pgv_lang["enter_subject"]              = "Merci d'entrer l'objet du message.";
$pgv_lang["enter_body"]                 = "Merci d'entrer un texte de message avant de faire l'envoi.";
$pgv_lang["confirm_message_delete"]     = "Confirmez-vous la suppression de ce message ? Toute suppression est définitive.";
$pgv_lang["message_email1"]             = "Le message suivant vous a ete envoye par ";
$pgv_lang["message_email2"]             = "Vous avez envoye le message suivant a l'utilisateur PhpGedView ";
$pgv_lang["message_email3"]             = "Vous avez envoye le message suivant a l'administrateur PhpGedView ";
$pgv_lang["viewing_url"]                = "Ce message a ete envoye depuis l'URL ";
$pgv_lang["messaging2_help"]            = "Lorsque vous envoyez un message, une copie vous est automatiquement adressee.";
$pgv_lang["random_picture"]             = "Une image au hasard";
$pgv_lang["message_instructions"]       = "<b>Respect de la vie privée :</b> Les informations sur une personne vivante ne seront envoyées qu'aux proches pouvant justifier d'un lien de parenté. <br /><br />Si vous proposez un ajout ou une correction, merci d'indiquer les sources de vos informations.<br /><br />";
$pgv_lang["sending_to"]                 = "Ce message va être envoyé à #TO_USER#";
$pgv_lang["preferred_lang"]             = "Cet utilisateur préfère recevoir les messages en #USERLANG#";
$pgv_lang["gedcom_created_using"]       = "Fichier GEDCOM créé avec <b>#SOFTWARE# #VERSION#</b>.";
$pgv_lang["gedcom_created_on"]          = "Fichier GEDCOM créé le <b>#DATE#</b>.";
$pgv_lang["gedcom_created_on2"]         = " le <b>#DATE#</b>";
$pgv_lang["gedcom_stats"]               = "Statistiques GEDCOM";
$pgv_lang["stat_individuals"]           = "Individus";
$pgv_lang["stat_families"]              = "Familles";
$pgv_lang["stat_sources"]               = "Sources";
$pgv_lang["stat_other"]                 = "Autres enregistrements";
$pgv_lang["stat_earliest_birth"]        = "Naissance la +ancienne";
$pgv_lang["stat_latest_birth"]          = "Naissance la +récente";
$pgv_lang["stat_earliest_death"]        = "Décès le +ancien";
$pgv_lang["stat_latest_death"]          = "Décès le +récent";
$pgv_lang["customize_page"]             = "Personnalisez votre page d'accueil";
$pgv_lang["customize_gedcom_page"]      = "Personnalisez cette page d'accueil GEDCOM";
$pgv_lang["upcoming_events_block"]      = "Bloc «Evènements à venir»";
$pgv_lang["upcoming_events_descr"]      = "Le bloc «Evènements à venir» affiche les anniversaires des 30 prochains jours.";
$pgv_lang["todays_events_block"]        = "Bloc «Ce jour-là»";
$pgv_lang["todays_events_descr"]        = "Le bloc «Ce jour-là» affiche les anniversaires du jour. Les utilisateurs identifiés voient les anniversaires des personnes vivantes.";
#pgv_lang["yahrzeit_block"]             = "Upcoming Yahrzeiten";
#pgv_lang["yahrzeit_descr"]             = "The Upcoming Yahrzeiten block shows anniversaries of death dates that will occur in the near future.  You can configure the period shown, and the Administrator can configure how far into the future this block will look.";
$pgv_lang["logged_in_users_block"]      = "Bloc «Utilisateurs connectés»";
$pgv_lang["logged_in_users_descr"]      = "Le bloc «Utilisateurs connectés» affiche les comptes des utilisateurs actuellement connectés à cette base.";
$pgv_lang["user_messages_block"]        = "Bloc «Mes messages»";
$pgv_lang["user_messages_descr"]        = "Le bloc «Mes messages» affiche les messages reçus par l'utilisateur connecté.";
$pgv_lang["user_favorites_block"]       = "Bloc «Mes favoris»";
$pgv_lang["user_favorites_descr"]       = "Le bloc «Mes favoris» affiche les liens mémorisés par l'utilisateur.";
$pgv_lang["welcome_block"]              = "Bloc «Bienvenue»";
$pgv_lang["welcome_descr"]              = "Le bloc «Bienvenue» affiche la date, l'heure, et un accès rapide aux principales informations.";
$pgv_lang["random_media_block"]         = "Bloc «Une image au hasard»";
$pgv_lang["random_media_descr"]         = "Le bloc «Une image au hasard» affiche un lien au hasard vers un objet MultiMedia de la base.";
$pgv_lang["random_media_persons_or_all"]= "Montrer seulement les personnes, les évènements, ou tout ?";
$pgv_lang["random_media_persons"]       = "Personnes";
$pgv_lang["random_media_events"]        = "Evènements";
$pgv_lang["gedcom_block"]               = "Bloc «Accueil GEDCOM»";
$pgv_lang["gedcom_descr"]               = "Le bloc «Accueil GEDCOM» est similaire au bloc «Bienvenue».";
$pgv_lang["gedcom_favorites_block"]     = "Bloc «Favoris GEDCOM»";
$pgv_lang["gedcom_favorites_descr"]     = "Le bloc «Favoris GEDCOM» affiche les liens mémorisés par l'administrateur.";
$pgv_lang["gedcom_stats_block"]         = "Bloc «Statistiques GEDCOM»";
$pgv_lang["gedcom_stats_descr"]         = "Le bloc «Statistiques GEDCOM» affiche quelques informations générales sur la base : date de création, nombre d'individus...";
$pgv_lang["gedcom_stats_show_surnames"] = "Montrer les noms les plus fréquents ?";
$pgv_lang["portal_config_intructions"]  = "Depuis cette page vous pouvez personnaliser votre portail «Mon GedView» en arrangeant les blocs à votre convenance. Le portail est découpé en deux sections, la section Principale et la section de Droite. La section Principale est plus large et s'affiche sous le titre. La section de Droite s'affiche sur le côté droit de la page. Chaque section possède sa propre liste de blocs. Vous pouvez ajouter, supprimer et réordonner les blocs selon votre goût.";
$pgv_lang["login_block"]                = "Bloc «Login»";
$pgv_lang["login_descr"]                = "Le bloc «Login» permet de se connecter en saisissant son identifiant et son mot de passe.";
$pgv_lang["theme_select_block"]         = "Bloc «Changer de thème»";
$pgv_lang["theme_select_descr"]         = "Le bloc «Changer de thème» affiche le sélecteur de thème même si le changement de thème est désactivé.";
$pgv_lang["block_top10_title"]          = "Noms les plus fréquents";
$pgv_lang["block_top10"]                = "Bloc «Top 10»";
$pgv_lang["block_top10_descr"]          = "Le bloc «Top 10» affiche les 10 noms les plus fréquents.";

$pgv_lang["gedcom_news_block"]          = "Bloc «Nouvelles GEDCOM»";
$pgv_lang["gedcom_news_descr"]          = "Le bloc «Nouvelles GEDCOM» affiche les nouvelles envoyées par l'administrateur.";
$pgv_lang["gedcom_news_limit"]          = "Limite d'affichage:";
$pgv_lang["gedcom_news_limit_nolimit"]  = "Pas de limite";
$pgv_lang["gedcom_news_limit_date"]     = "Age de l'article";
$pgv_lang["gedcom_news_limit_count"]    = "Nombre d'articles";
$pgv_lang["gedcom_news_flag"]           = "Limite:";
$pgv_lang["gedcom_news_archive"]        = "Voir archives";
$pgv_lang["user_news_block"]            = "Bloc «Mon journal»";
$pgv_lang["user_news_descr"]            = "Le bloc «Mon journal» permet à l'utilisateur de conserver des notes personnelles en ligne.";
$pgv_lang["my_journal"]                 = "Mon Journal";
$pgv_lang["no_journal"]                 = "Journal vide.";
$pgv_lang["confirm_journal_delete"]     = "Confirmez-vous la suppression de cet élément du journal ?";
$pgv_lang["add_journal"]                = "Ajouter un élément au journal";
$pgv_lang["gedcom_news"]                = "Nouvelles";
$pgv_lang["confirm_news_delete"]        = "Confirmez-vous la suppression de cette nouvelle ?";
$pgv_lang["add_news"]                   = "Ajouter une nouvelle";
$pgv_lang["no_news"]                    = "Aucune nouvelle.";
$pgv_lang["edit_news"]                  = "Editer un élément";
$pgv_lang["enter_title"]                = "Entrer un titre.";
$pgv_lang["enter_text"]                 = "Entrer un texte.";
$pgv_lang["news_saved"]                 = "Elément sauvegardé.";
$pgv_lang["article_text"]               = "Texte";
$pgv_lang["main_section"]               = "Blocs de la section principale";
$pgv_lang["right_section"]              = "Blocs de la section de droite";
$pgv_lang["available_blocks"]           = "Blocs disponibles";
$pgv_lang["move_up"]                    = "Monter";
$pgv_lang["move_down"]                  = "Descendre";
$pgv_lang["move_right"]                 = "Déplacer à droite";
$pgv_lang["move_left"]                  = "Déplacer à gauche";
$pgv_lang["broadcast_all"]              = "Envoyer un message à tous les utilisateurs";
$pgv_lang["hit_count"]                  = "Compteur";
$pgv_lang["phpgedview_message"]         = "Message PhpGedView";
$pgv_lang["common_surnames"]            = "Principaux noms de familles";
$pgv_lang["default_news_title"]         = "Bienvenue";
$pgv_lang["default_news_text"]          = "Ce site utilise l'outil de généalogie <a href=\"http://www.phpgedview.net/\" target=\"_blank\">PhpGedView #VERSION#</a>.<br /><br />Pour démarrer, faîtes un choix dans le menu <b>Diagrammes</b> ou dans le menu <b>Listes</b>.<br /><br />En cas de difficultés, reportez-vous au menu <b>Aide</b>.<br /><br />Merci de votre visite.";
$pgv_lang["reset_default_blocks"]       = "Retour aux blocs par défaut";
$pgv_lang["recent_changes"]             = "Modifications récentes";
$pgv_lang["recent_changes_block"]       = "Bloc «Modifications récentes»";
$pgv_lang["recent_changes_descr"]       = "Le bloc «Modifications récentes» affiche toutes les modifications de la base GEDCOM sur les #pgv_lang[global_num1]# derniers jours. Il vous aidera à suivre les changements réalisés (marqueur 'CHAN').";
$pgv_lang["recent_changes_none"]        = "<b>Aucune modification enregistrée ces #pgv_lang[global_num1]# derniers jours.</b><br />";
$pgv_lang["recent_changes_some"]        = "<b>Modifications enregistrées ces #pgv_lang[global_num1]# derniers jours</b><br />";
$pgv_lang["show_empty_block"]           = "Toujours afficher ce bloc même s'il est vide ?";
$pgv_lang["hide_block_warn"]            = "Si vous cachez un bloc vide, vous ne le pourrez le reconfigurer que lorsqu'il redeviendra visible en n'étant plus vide.";
$pgv_lang["delete_selected_messages"]   = "Supprimer les messages sélectionnés";
$pgv_lang["use_blocks_for_default"]     = "Utiliser ce bloc par défaut pour tous les utilisateurs ?";
$pgv_lang["block_not_configure"]        =	"Ce bloc ne peut pas être configuré.";

//-- validate GEDCOM
$pgv_lang["add_media_tool"]             = "Utilitaire «Ajout media»";

//-- hourglass chart
$pgv_lang["hourglass_chart"]            = "Sablier";

//-- report engine
$pgv_lang["choose_report"]              = "Choisir un rapport";
$pgv_lang["enter_report_values"]        = "Entrer les paramètres du rapport";
$pgv_lang["selected_report"]            = "Rapport sélectionné";
$pgv_lang["select_report"]              = "Selectionner";
$pgv_lang["download_report"]            = "Enregistrer le rapport";
$pgv_lang["reports"]                    = "Rapports";
$pgv_lang["pdf_reports"]                = "Rapports format PDF";
$pgv_lang["html_reports"]               = "Rapports format HTML";

//-- Ahnentafel report
$pgv_lang["ahnentafel_report"]          = "Liste par générations";
$pgv_lang["ahnentafel_header"]          = "'Ahnentafel' : liste par générations pour ";
$pgv_lang["ahnentafel_generation"]      = "Génération n° ";
$pgv_lang["ahnentafel_pronoun_m"]       = "Il ";
$pgv_lang["ahnentafel_pronoun_f"]       = "Elle ";
$pgv_lang["ahnentafel_born_m"]          = "est né";
$pgv_lang["ahnentafel_born_f"]          = "est née";
$pgv_lang["ahnentafel_christened_m"]    = "a été baptisé";
$pgv_lang["ahnentafel_christened_f"]    = "a été baptisée";
$pgv_lang["ahnentafel_married_m"]       = "a épousé";
$pgv_lang["ahnentafel_married_f"]       = "a épousé";
$pgv_lang["ahnentafel_died_m"]          = "est décédé";
$pgv_lang["ahnentafel_died_f"]          = "est décédée";
$pgv_lang["ahnentafel_buried_m"]        = "a été enterré";
$pgv_lang["ahnentafel_buried_f"]        = "a été enterrée";
$pgv_lang["ahnentafel_place"]           = ", ";
$pgv_lang["ahnentafel_no_details"]      = " les détails sont inconnus";

//-- Changes report
#pgv_lang["changes_report"]             = "Changes Report";
#pgv_lang["changes_pending_tot"]        = "Total pending changes: ";
#pgv_lang["changes_accepted_tot"]       = "Total accepted changes: ";

//-- Descendancy report
$pgv_lang["descend_report"]             = "Descendance";
$pgv_lang["descendancy_header"]         = "Descendance de ";

$pgv_lang["family_group_report"]        = "Famille";
$pgv_lang["page"]                       = "Page";
$pgv_lang["of"]                         = "de";
$pgv_lang["enter_famid"]                = "Code famille";
$pgv_lang["show_sources"]               = "Montrer les sources ?";
$pgv_lang["show_notes"]                 = "Montrer les notes ?";
$pgv_lang["show_basic"]                 = "Montrer les principaux<br />évènements même vides ?";
$pgv_lang["show_photos"]                = "Montrer les photos ?";
$pgv_lang["relatives_report_ext"]       = "Parenté élargie";
$pgv_lang["with"]                       = "avec";
$pgv_lang["on"]                         = "le";
$pgv_lang["in"]                         = "en";
$pgv_lang["individual_report"]          = "Individu";
$pgv_lang["enter_pid"]                  = "Code individu";
$pgv_lang["generated_by"]               = "Généré par";
$pgv_lang["list_children"]              = "Liste des enfants par ordre de naissance.";
$pgv_lang["birth_report"]               = "Naissances par lieu";
$pgv_lang["birthplace"]                 = "Le lieu commence par";
$pgv_lang["birthdate1"]                 = "Date de naissance mini";
$pgv_lang["birthdate2"]                 = "Date de naissance maxi";
$pgv_lang["death_report"]               = "Décès par lieu";
$pgv_lang["deathplace"]                 = "Le lieu commence par";
$pgv_lang["deathdate1"]                 = "Date de décès mini";
$pgv_lang["deathdate2"]                 = "Date de décès maxi";
$pgv_lang["marr_report"]                = "Mariages par lieu";
$pgv_lang["marrplace"]                  = "Le lieu commence par";
$pgv_lang["marrdate1"]                  = "Date de mariage mini";
$pgv_lang["marrdate2"]                  = "Date de mariage maxi";
$pgv_lang["sort_by"]                    = "Trier par";

$pgv_lang["cleanup"]                    = "Continuer";

//-- CONFIGURE (extra) messages for programs patriarch and statistics
$pgv_lang["dynasty_list"]               = "Panorama des familles";
$pgv_lang["patriarch_list"]             = "Liste des patriarches";
$pgv_lang["statistics"]                 = "Statistiques";

//-- Merge Records
$pgv_lang["merge_same"]                 = "Impossible de fusionner les enregistrements : ils ne sont pas du même type.";
$pgv_lang["merge_step1"]                = "Fusion : étape 1/3";
$pgv_lang["merge_step2"]                = "Fusion : étape 2/3";
$pgv_lang["merge_step3"]                = "Fusion : étape 3/3";
$pgv_lang["select_gedcom_records"]      = "Selectionner les 2 enregistrements GEDCOM à fusionner. Ils doivent être du même type.";
$pgv_lang["merge_to"]                   = "Fusion vers :";
$pgv_lang["merge_from"]                 = "Fusion de :";
$pgv_lang["merge_facts_same"]           = "Les champs suivants sont identiques dans les 2 enregistrements et seront fusionnés automatiquement";
$pgv_lang["no_matches_found"]           = "Aucun champ correspondant";
$pgv_lang["unmatching_facts"]           = "Les champs suivants sont différents. Sélectionner la valeur à conserver.";
$pgv_lang["record"]                     = "Enregistrement";
$pgv_lang["adding"]                     = "Ajout";
$pgv_lang["updating_linked"]            = "Mise à jour de l'enregistrement lié";
$pgv_lang["merge_more"]                 = "Fusionner d'autres enregistrements.";
$pgv_lang["same_ids"]                   = "Entrer des identifiants différents.";

//-- ANCESTRY FILE MESSAGES
$pgv_lang["ancestry_chart"]             = "Tableau d'ascendance";
$pgv_lang["gen_ancestry_chart"]         = "Ascendance sur #PEDIGREE_GENERATIONS# générations";
$pgv_lang["chart_style"]                = "Style de présentation";
$pgv_lang["chart_list"]                 = "Liste";
$pgv_lang["chart_booklet"]              = "Livret";
$pgv_lang["show_cousins"]               = "Afficher les cousins";
// 1st generation
$pgv_lang["sosa_2"]                     = "Père";
$pgv_lang["sosa_3"]                     = "Mère";
// 2nd generation
$pgv_lang["sosa_4"]                     = "Grand-père paternel";
$pgv_lang["sosa_5"]                     = "Grand-mère paternelle";
$pgv_lang["sosa_6"]                     = "Grand-père maternel";
$pgv_lang["sosa_7"]                     = "Grand-mère maternelle";
// 3rd generation
$pgv_lang["sosa_8"]                     = "Arrière-grand-père";
$pgv_lang["sosa_9"]                     = "Arrière-grand-mère";
$pgv_lang["sosa_10"]                    = "Arrière-grand-père";
$pgv_lang["sosa_11"]                    = "Arrière-grand-mère";
$pgv_lang["sosa_12"]                    = "Arrière-grand-père";
$pgv_lang["sosa_13"]                    = "Arrière-grand-mère";
$pgv_lang["sosa_14"]                    = "Arrière-grand-père";
$pgv_lang["sosa_15"]                    = "Arrière-grand-mère";
// 4th generation
$pgv_lang["sosa_16"]                    = "Arrière-arrière-grand-père";
$pgv_lang["sosa_17"]                    = "Arrière-arrière-grand-mère";
$pgv_lang["sosa_18"]                    = "Arrière-arrière-grand-père";
$pgv_lang["sosa_19"]                    = "Arrière-arrière-grand-mère";
$pgv_lang["sosa_20"]                    = "Arrière-arrière-grand-père";
$pgv_lang["sosa_21"]                    = "Arrière-arrière-grand-mère";
$pgv_lang["sosa_22"]                    = "Arrière-arrière-grand-père";
$pgv_lang["sosa_23"]                    = "Arrière-arrière-grand-mère";
$pgv_lang["sosa_24"]                    = "Arrière-arrière-grand-père";
$pgv_lang["sosa_25"]                    = "Arrière-arrière-grand-mère";
$pgv_lang["sosa_26"]                    = "Arrière-arrière-grand-père";
$pgv_lang["sosa_27"]                    = "Arrière-arrière-grand-mère";
$pgv_lang["sosa_28"]                    = "Arrière-arrière-grand-père";
$pgv_lang["sosa_29"]                    = "Arrière-arrière-grand-mère";
$pgv_lang["sosa_30"]                    = "Arrière-arrière-grand-père";
$pgv_lang["sosa_31"]                    = "Arrière-arrière-grand-mère";

// for the general case of ancestors of the nth generation use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["sosa_paternal_male_n_generations"]= "%3\$d x arrière grand-père paternel";
$pgv_lang["sosa_paternal_female_n_generations"]= "%3\$d x arrière grand-mère paternelle";
$pgv_lang["sosa_maternal_male_n_generations"]= "%3\$d x arrière grand-père maternel";
$pgv_lang["sosa_maternal_female_n_generations"]= "%3\$d x arrière grand-mère maternelle";

//-- FAN CHART
$pgv_lang["compact_chart"]              = "Arbre compact";
$pgv_lang["fan_chart"]                  = "Roue";
$pgv_lang["gen_fan_chart"]              = "Roue sur #PEDIGREE_GENERATIONS# générations";
$pgv_lang["fan_width"]                  = "Taille de la roue";
$pgv_lang["gd_library"]                 = "Problème de configuration du serveur PHP : la librairie graphique GD 2.x est nécessaire pour utiliser les fonctions Image.";
$pgv_lang["gd_freetype"]                = "Problème de configuration du serveur PHP : la librairie FreeType est nécessaire pour utiliser les fontes 'TrueType'.";
$pgv_lang["gd_helplink"]                = "http://fr.php.net/gd";
$pgv_lang["fontfile_error"]             = "Fichier de fonte absent du serveur PHP";
$pgv_lang["fanchart_IE"]                = "Cette image ne peut pas être imprimée directement par votre navigateur. Enregistrez-la sur votre disque local : clic-droit «Enregistrer l'image sous...» pour l'imprimer.";

//-- RSS Feed
$pgv_lang["rss_descr"]                  = "Nouvelles et liens du site #GEDCOM_TITLE#";
$pgv_lang["rss_logo_descr"]             = "Créé par PhpGedView";
$pgv_lang["rss_feeds"]                  = "Flux RSS";
$pgv_lang["no_feed_title"]              = "Flux non disponible";
$pgv_lang["no_feed"]                    = "Aucun flux RSS trouvé pour ce site PhpGedView";
$pgv_lang["feed_login"]                 = "Si vous disposez d'un compte sur ce site PhpGedView, vous pouvez <a href=\"#AUTH_URL#\">vous connectez (login)</a> au serveur en utilisant le processus d'authentification HTTP afin d'accéder aux informations privées.";
$pgv_lang["authenticated_feed"]         = "Flux d'authentification";

//-- ASSOciates RELAtionship
// After any change in the following list, please check $assokeys in edit_interface.php
$pgv_lang["attendant"]                  = "Préposé";
$pgv_lang["attending"]                  = "Présent";
$pgv_lang["best_man"]                   = "Garçon d'honneur";
$pgv_lang["bridesmaid"]                 = "Demoiselle d'honneur";
$pgv_lang["buyer"]                      = "Acheteur";
$pgv_lang["circumciser"]                = "Circonciseur";
$pgv_lang["civil_registrar"]            = "Officier de l'Etat-Civil";
$pgv_lang["friend"]                     = "Ami(e)";
$pgv_lang["godfather"]                  = "Parrain";
$pgv_lang["godmother"]                  = "Marraine";
$pgv_lang["godparent"]                  = "Parrain/marraine";
$pgv_lang["informant"]                  = "Déclarant";
$pgv_lang["lodger"]                     = "Locataire";
$pgv_lang["nurse"]                      = "Nourrice";
$pgv_lang["priest"]                     = "Prêtre";
$pgv_lang["rabbi"]                      = "Rabbin";
$pgv_lang["registry_officer"]           = "Greffier";
$pgv_lang["seller"]                     = "Vendeur";
$pgv_lang["servant"]                    = "Serviteur";
$pgv_lang["twin"]                       = "Jumeau/jumelle";
$pgv_lang["twin_brother"]               = "Frère jumeau";
$pgv_lang["twin_sister"]                = "Sœur jumelle";
$pgv_lang["witness"]                    = "Témoin";

//-- statistics utility
$pgv_lang["statutci"]                   = "impossible de créer un index";
$pgv_lang["statnnames"]                 = "nombre de noms    =";
$pgv_lang["statnfam"]                   = "nombre de familles =";
$pgv_lang["statnmale"]                  = "nombre d'individus masculins    =";
$pgv_lang["statnfemale"]                = "nombre d'individus féminins  =";
$pgv_lang["statvars"]                   = "Renseignez les variables suivantes";
$pgv_lang["statlxa"]                    = "le long de l'axe des x:";
$pgv_lang["statlya"]                    = "le long de l'axe des x:";
$pgv_lang["statlza"]                    = "le long de l'axe des z";
$pgv_lang["stat_10_none"]               = "aucun";
$pgv_lang["stat_11_mb"]                 = "mois de naissance";
$pgv_lang["stat_12_md"]                 = "mois de décès";
$pgv_lang["stat_13_mm"]                 = "mois du mariage";
$pgv_lang["stat_14_mb1"]                = "mois de naissance ou du premier enfant obtenu par une relation";
$pgv_lang["stat_15_mm1"]                = "mois du premier mariage";
$pgv_lang["stat_16_mmb"]                = "mois entre le mariage et le premier enfant";
$pgv_lang["stat_17_arb"]                = "âge à la naissance.";
$pgv_lang["stat_18_ard"]                = "âge au décès.";
$pgv_lang["stat_19_arm"]                = "âge au mariage.";
$pgv_lang["stat_20_arm1"]               = "âge au premier mariage.";
$pgv_lang["stat_21_nok"]                = "nombre d'enfants.";
$pgv_lang["stat_200_none"]              = "tous (ou vide)";
$pgv_lang["stat_201_num"]               = "nombres";
$pgv_lang["stat_202_perc"]              = "pourcentage";
$pgv_lang["stat_300_none"]              = "aucun";
$pgv_lang["stat_301_mf"]                = "masculin/féminin";
$pgv_lang["stat_302_cgp"]               = "périodes. Vérifiez les valeurs cochées pour les périodes de l'axe des z";
$pgv_lang["statmess1"]                  = "<b>Remplissez les lignes suivantes relatives aux paramètres de l'axe des x ou de l'axe des z </b>";
$pgv_lang["statar_xgp"]                 = "valeurs cochées pour les périodes (axe des x):";
$pgv_lang["statar_xgl"]                 = "valeurs cochées pour les âges (axe des x):";
$pgv_lang["statar_xgm"]                 = "valeurs cochées pour le mois (axe des x):";
$pgv_lang["statar_xga"]                 = "valeurs cochées pour les nombres (axe des x):";
$pgv_lang["statar_zgp"]                 = "valeurs cochées pour les périodes (axe des z):";
$pgv_lang["statreset"]                  = "réinitialisation";
$pgv_lang["statsubmit"]                 = "montrer le graphique";

//-- statisticsplot utility
$pgv_lang["statistiek_list"]            = "Graphique statistique";
#pgv_lang["stpl"]                       = "...";
$pgv_lang["stplGDno"]                   = "Graphics Display Library n'est pas disponible avec PHP 4. Contactez votre administrateur";
$pgv_lang["stpljpgraphno"]              = "Les modules JPgraph ne sont pas disponibles dans le répertoire <i>phpgedview/jpgraph/</i> . Vous pouvez les récupérer sur ce site http://www.aditus.nu/jpgraph/jpdownload.php<br /> <h3>Installez avant toute chose JPgraph dans le répertoire <i>phpgedview/jpgraph/</i></h3><br />";
$pgv_lang["stplinfo"]                   = "informations de plotting:";
$pgv_lang["stpltype"]                   = "type:";
$pgv_lang["stplnoim"]                   = " n'est pas implémenté:";
$pgv_lang["stplmf"]                     = " / homme-femme";
$pgv_lang["stplipot"]                   = " / par période de temps";
$pgv_lang["stplgzas"]                   = "bordures de l'axe des z:";
$pgv_lang["stplmonth"]                  = "mois";
$pgv_lang["stplnumbers"]                = "nombres pour une famille";
$pgv_lang["stplage"]                    = "âge";
$pgv_lang["stplperc"]                   = "pourcentage";
$pgv_lang["stplnumof"]                  = "Totaux ";
$pgv_lang["stplmarrbirth"]              = "Mois entre le mariage et la naissance du premier enfant";

//-- alive in year
$pgv_lang["alive_in_year"]              = "Vivant cette année-là";
$pgv_lang["is_alive_in"]                = "Ont vécu en l'an ";
$pgv_lang["alive"]                      = "Vivant ";
$pgv_lang["dead"]                       = "Décédé ";
$pgv_lang["maybe"]                      = "A vérifier ";
$pgv_lang["both_alive"]                 = "Vivants";
$pgv_lang["both_dead"]                  = "Décédés";

//-- Help system
$pgv_lang["definitions"]                = "Définitions";

//-- Index_edit
$pgv_lang["block_desc"]                 = "Description du bloc";
$pgv_lang["click_here"]                 = "Continuer";
$pgv_lang["click_here_help"]            = "~#pgv_lang[click_here]#~<br /><br />Cliquez sur ce bouton pour conserver vos changements.<br /><br />On vous mène à la page #pgv_lang[welcome]# ou #pgv_lang[mygedview]#, mais il se peut que vos changements ne vous sont pas montrés.  Dans ce cas, utilisez la fonction «Rafraichir page» de votre viseur.";
$pgv_lang["block_summaries"]            = "~#pgv_lang[block_desc]#~<br /><br />Voici une brève description de chacun des blocs qui vous pouvez placer sur les pages #pgv_lang[welcome]# ou #pgv_lang[mygedview]#.<br /><table border='1' align='center'><tr><td class='list_value'><b>#pgv_lang[name]#</b></td><td class='list_value'><b>#pgv_lang[description]#</b></td></tr>#pgv_lang[block_summary_table]#</table><br /><br />";
// Built in index_edit.php
$pgv_lang["block_summary_table"]        = "&nbsp;";

//-- Find page
$pgv_lang["total_places"]               = "Lieux trouvés";
$pgv_lang["media_contains"]             = "Objet MultiMedia:";
$pgv_lang["repo_contains"]              = "Dépôt d'archives:";
$pgv_lang["source_contains"]            = "Source:";
$pgv_lang["display_all"]                = "Afficher tout";

//-- accesskey navigation
$pgv_lang["accesskeys"]                 = "Raccourcis clavier";
#pgv_lang["accesskey_skip_to_content"]  = "C";
#pgv_lang["accesskey_search"]           = "S";
$pgv_lang["accesskey_skip_to_content_desc"]= "Contenu";
#pgv_lang["accesskey_viewing_advice"]   = "0";
$pgv_lang["accesskey_viewing_advice_desc"]= "Astuces";
#pgv_lang["accesskey_home_page"]        = "1";
#pgv_lang["accesskey_help_content"]     = "2";
#pgv_lang["accesskey_help_current_page"]= "3";
#pgv_lang["accesskey_contact"]          = "4";

#pgv_lang["accesskey_individual_details"]= "I";
#pgv_lang["accesskey_individual_relatives"]= "R";
#pgv_lang["accesskey_individual_notes"] = "N";
#pgv_lang["accesskey_individual_sources"]= "O";
//clash with IE addBookmark but not a likely problem
#pgv_lang["accesskey_individual_media"] = "A";
#pgv_lang["accesskey_individual_research_log"]= "L";
#pgv_lang["accesskey_individual_pedigree"]= "P";
#pgv_lang["accesskey_individual_descendancy"]= "D";
#pgv_lang["accesskey_individual_timeline"]= "T";
#pgv_lang["accesskey_individual_relation_to_me"]= "M";
//clash with rarely used English Netscape/Mozilla Go menu
#pgv_lang["accesskey_individual_gedcom"]= "G";

#pgv_lang["accesskey_family_parents_timeline"]= "P";
#pgv_lang["accesskey_family_children_timeline"]= "D";
#pgv_lang["accesskey_family_timeline"]  = "T";
//clash with rarely used English Netscape/Mozilla English Go menu
#pgv_lang["accesskey_family_gedcom"]    = "G";

// FAQ Page
$pgv_lang["add_faq_header"]             = "En-tête FAQ";
$pgv_lang["add_faq_body"]               = "Corps FAQ";
$pgv_lang["add_faq_order"]              = "Position FAQ";
$pgv_lang["add_faq_visibility"]         = "Visibilité FAQ";
$pgv_lang["no_faq_items"]               = "FAQ vide.";
$pgv_lang["position_item"]              = "Item no";
$pgv_lang["faq_list"]                   = "Liste FAQ";
$pgv_lang["confirm_faq_delete"]         = "Confirmez-vous la suppression de cette information";
$pgv_lang["preview"]                    = "Prévisualier";
$pgv_lang["no_id"]                      = "Indiquez un no de FAQ!";

// Help search
$pgv_lang["hs_title"]                   = "Aide à la recherche";
$pgv_lang["hs_search"]                  = "Recherche";
$pgv_lang["hs_close"]                   = "Fermer la fenêtre";
$pgv_lang["hs_results"]                 = "Résultats :";
$pgv_lang["hs_keyword"]                 = "Rechercher";
$pgv_lang["hs_searchin"]                = "Rechercher dans";
$pgv_lang["hs_searchuser"]              = "Aide utilisateur";
#pgv_lang["hs_searchmodules"]           = "Modules Help";
$pgv_lang["hs_searchconfig"]            = "Aide administrateur";
$pgv_lang["hs_searchhow"]               = "Type de recherche";
$pgv_lang["hs_searchall"]               = "Tous les mots";
$pgv_lang["hs_searchany"]               = "Au moins un mot";
$pgv_lang["hs_searchsentence"]          = "Phrase exacte";
$pgv_lang["hs_intruehelp"]              = "Texte d'aide seulement";
$pgv_lang["hs_inallhelp"]               = "Tout le texte";

// Media import
$pgv_lang["choose"]                     = "Choisir : ";
$pgv_lang["account_information"]        = "Informations du compte";

//-- Media item "TYPE" sub-field
#pgv_lang["TYPE__audio"]                = "Audio";
$pgv_lang["TYPE__book"]                 = "Livre";
$pgv_lang["TYPE__card"]                 = "Carte";
$pgv_lang["TYPE__certificate"]          = "Certificat";
#pgv_lang["TYPE__document"]             = "Document";
$pgv_lang["TYPE__electronic"]           = "Electronique";
#pgv_lang["TYPE__fiche"]                = "Microfiche";
#pgv_lang["TYPE__film"]                 = "Microfilm";
#pgv_lang["TYPE__magazine"]             = "Magazine";
$pgv_lang["TYPE__manuscript"]           = "Manuscrit";
$pgv_lang["TYPE__map"]                  = "Carte ou plan";
$pgv_lang["TYPE__newspaper"]            = "Journal";
#pgv_lang["TYPE__photo"]                = "Photo";
$pgv_lang["TYPE__tombstone"]            = "Pierre tombale";
#pgv_lang["TYPE__video"]                = "Video";

//-- Other media suff
$pgv_lang["view_slideshow"]             = "Voir en diaporama";
$pgv_lang["download_image"]             = "Télécharger le fichier";
$pgv_lang["no_media"]                   = "Aucun objet MultiMedia trouvé";
$pgv_lang["media_privacy"]              = "Objet MultiMedia est protégé";
$pgv_lang["relations_heading"]          = "Cette image est liée à :";
$pgv_lang["file_size"]                  = "Taille du fichier";
$pgv_lang["img_size"]                   = "Taille de l'image";
#pgv_lang["media_broken"]               = "This media file is broken and cannot be watermarked";
#pgv_lang["unknown_mime"]               = "Media Firewall error: >Unknown Mimetype< for file";

//-- Modules
$pgv_lang["module_error_unknown_action_v2"]= "Type d'action inconnu: [action].";
$pgv_lang["module_error_unknown_type"]  = "Type de module inconnu.";

//-- sortable tables buttons
$pgv_lang["button_alive_in_year"]       = "Affichage des personnes vivantes l'année indiquée ci-contre.";
$pgv_lang["button_BIRT_Y100"]           = "Affichage des personnes nées depuis moins de 100 ans";
$pgv_lang["button_BIRT_YES"]            = "Affichage des personnes nées depuis plus de 100 ans.";
$pgv_lang["button_DEAT_H"]              = "Affichage des couples dont seul le mari est décédé à la date d'aujourd'hui.";
$pgv_lang["button_DEAT_N"]              = "Affichage des personnes vivantes ou des couples dont les deux époux sont vivants à la date d'aujourd'hui.";
$pgv_lang["button_DEAT_W"]              = "Affichage des couples dont seule la femme est décédée à la date d'aujourd'hui.";
$pgv_lang["button_DEAT_Y"]              = "Affichage des personnes décédées ou des couples dont les deux époux sont décédés à la date d'aujourd'hui.";
$pgv_lang["button_DEAT_Y100"]           = "Affichage des personnes décédées depuis moins de 100 ans.";
$pgv_lang["button_DEAT_YES"]            = "Affichage des personnes décédées depuis plus de 100 ans.";
$pgv_lang["button_MARR_DIV"]            = "Affichage des couples divorcés.";
$pgv_lang["button_MARR_U"]              = "Affichage des couples dont la date de mariage est inconnue.";
$pgv_lang["button_MARR_Y100"]           = "Affichage des couples mariés depuis moins de 100 ans.";
$pgv_lang["button_MARR_YES"]            = "Affichage des couples mariés depuis plus de 100 ans.";
$pgv_lang["button_reset"]               = "Retour à la liste complète.";
$pgv_lang["button_SEX_F"]               = "Affichage des femmes uniquement.";
$pgv_lang["button_SEX_M"]               = "Affichage des hommes uniquement.";
$pgv_lang["button_SEX_U"]               = "Affichage des personnes de sexe indéterminé.";
$pgv_lang["button_TREE_L"]              = "Affichage des couples ou individus &laquo;feuilles&raquo;, c'est-à-dire : vivants à la date d'aujourd'hui mais n'ayant pas d'enfant enregistré dans la base.";
$pgv_lang["button_TREE_R"]              = "Affichage des couples ou individus &laquo;racines&raquo;, appelés également &laquo;patriarches&raquo;, c'est-à-dire : dont ni le père ni la mère ne sont enregistrés dans la base.";
$pgv_lang["sort_column"]                = "Trier sur cette colonne.";
?>
