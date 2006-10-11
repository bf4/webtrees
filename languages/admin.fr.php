<?php
/**
 * French Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2006  Christophe Bx, Julien Damon
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
if (preg_match("/admin\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
	print "You cannot access a language file directly.";
	exit;
}

$pgv_lang["keep_media"]                 = "Conserver les liens MultiMedia";
$pgv_lang["files_in_backup"]            = "Liste des fichiers sauvegardés";
$pgv_lang["created_remotelinks"]        = "Création table réussie <i>Remotelinks</i>.";
$pgv_lang["created_remotelinks_fail"]   = "Impossible de créer la table <i>Remotelinks</i>.";
$pgv_lang["created_indis"]              = "Création table réussie <i>Individuals</i>.";
$pgv_lang["created_indis_fail"]         = "Impossible de créer la table <i>Individuals</i>.";
$pgv_lang["created_fams"]               = "Création table réussie <i>Families</i>.";
$pgv_lang["created_fams_fail"]          = "Impossible de créer la table <i>Families</i>.";
$pgv_lang["created_sources"]            = "Création table réussie <i>Sources</i>.";
$pgv_lang["created_sources_fail"]       = "Impossible de créer la table <i>Sources</i>.";
$pgv_lang["created_other"]              = "Création table réussie <i>Other</i>.";
$pgv_lang["created_other_fail"]         = "Impossible de créer la table <i>Other</i>.";
$pgv_lang["created_places"]             = "Création table réussie <i>Places</i>.";
$pgv_lang["created_places_fail"]        = "Impossible de créer la table <i>Places</i>.";
$pgv_lang["created_placelinks"]         = "Création table réussie <i>Place links</i>.";
$pgv_lang["created_placelinks_fail"]    = "Impossible de créer la table <i>Place links</i>.";
$pgv_lang["created_media_fail"]         = "Impossible de créer la table <i>Media</i>.";
$pgv_lang["created_media_mapping_fail"] = "Impossible de créer la table <i>Media mappings</i>.";
$pgv_lang["no_thumb_dir"]               = " impossible de créer le dossier vignettes";
$pgv_lang["move_to"]                    = "Déplacer vers --&gt;";
$pgv_lang["folder_created"]             = "Dossier créé";
$pgv_lang["folder_no_create"]           = "Impossible de créer le dossier";
$pgv_lang["security_no_create"]         = "Avertissement concernant la sécurité : fichier index.php absent du dossier ";
$pgv_lang["security_not_exist"]         = "Avertissement concernant la sécurité : impossible de créer le fichier index.php dans le dossier ";
#pgv_lang["label_add_search_server"]    = "Add IP";
$pgv_lang["label_add_server"]           = "Ajouter";
$pgv_lang["label_ban_server"]           = "Soumettre";
$pgv_lang["label_delete"]               = "Supprimer";
#pgv_lang['progress_bars_info']         = "The status bars below will let you know how the import is progressing.  If the time limit runs out the import will be stopped and you will be asked to press a continue button.  If you don't see a continue button, please go back and enter a smaller time limit value.";
#pgv_lang["upload_replacement"]         = "Upload Replacement";
#pgv_lang["about_user"]                 = "You must first create your main administrative user.  This user will have privileges to update the configuration files, view private data, and create other users.";
#pgv_lang["access"]                     = "Access";
$pgv_lang["add_gedcom"]                 = "Paramètrer un GEDCOM déjà envoyé sur le serveur";
$pgv_lang["add_new_gedcom"]             = "Créer un nouveau GEDCOM vide";
#pgv_lang["add_new_language"]           = "Add files and settings for a new language";
$pgv_lang["add_user"]                   = "Ajouter un compte utilisateur";
$pgv_lang["admin_approved"]             = "Votre compte sur #SERVER_NAME#";
$pgv_lang["admin_gedcom"]               = "Administrateur GEDCOM";
$pgv_lang["admin_gedcoms"]              = "Administration des fichiers GEDCOM";
$pgv_lang["admin_geds"]                 = "Fichiers et données GEDCOM";
$pgv_lang["admin_info"]                 = "Informations";
$pgv_lang["admin_site"]                 = "Administration du site";
#pgv_lang["admin_user_warnings"]        = "One or more user accounts have warnings";
#pgv_lang["admin_verification_waiting"] = "User accounts awaiting verification by admin";
$pgv_lang["administration"]             = "Administration";
#pgv_lang["ALLOW_CHANGE_GEDCOM"]        = "Allow GEDCOM switching";
#pgv_lang["ALLOW_REMEMBER_ME"]          = "Show <b>Remember Me</b> option on Login page";
#pgv_lang["ALLOW_USER_THEMES"]          = "Allow users to select their own theme";
$pgv_lang["ansi_encoding_detected"]     = "Ce fichier est au format ANSI. PhpGedView recommande le format UTF-8.";
$pgv_lang["ansi_to_utf8"]               = "Convertir ce fichier GEDCOM format ANSI en format UTF-8 ?";
$pgv_lang["apply_privacy"]              = "Appliquer les règles de restrictions d'accès ?";
#pgv_lang["back_useradmin"]             = "Back to User Administration";
$pgv_lang["bytes_read"]                 = "Octets lus";
$pgv_lang["calc_marr_names"]            = "Ajout du nom des maris";
#pgv_lang["can_admin"]                  = "User can administer";
#pgv_lang["can_edit"]                   = "Access level";
$pgv_lang["change_id"]                  = "Changer le code individu en";
$pgv_lang["choose_priv"]                = "Niveau de restriction:";
$pgv_lang["cleanup_places"]             = "Chargement des lieux";
#pgv_lang["cleanup_users"]              = "Cleanup users";
$pgv_lang["click_here_to_continue"]     = "Continuer";
$pgv_lang["click_here_to_go_to_pedigree_tree"]= "Afficher l'arbre";
$pgv_lang["comment"]                    = "Avis de l'administrateur";
$pgv_lang["comment_exp"]                = "Avertissement de l'administrateur le";
#pgv_lang["config_help"]                = "Configuration help";
#pgv_lang["config_still_writable"]      = "Your <i>config.php</i> file is still writable.  For security, you should set the permissions of this file back to read-only when you have finished configuring your site.";
$pgv_lang["configuration"]              = "Configurer PhpGedView";
#pgv_lang["configure"]                  = "Configure PhpGedView";
#pgv_lang["configure_head"]             = "PhpGedView Configuration";
$pgv_lang["confirm_gedcom_delete"]      = "Confirmez-vous la suppression de ce GEDCOM ?";
$pgv_lang["confirm_password"]           = "Vous devez confirmer le mot de passe.";
$pgv_lang["confirm_user_delete"]        = "Confirmez-vous la suppression de cet utilisateur ?";
$pgv_lang["create_user"]                = "Ajouter un utilisateur";
$pgv_lang["current_users"]              = "Liste des comptes";
$pgv_lang["daily"]                      = "Quotidien";
$pgv_lang["dataset_exists"]             = "Un fichier GEDCOM de même nom a déjà été introduit dans la base de données.";
#pgv_lang["date_registered"]            = "Date registered";
$pgv_lang["day_before_month"]           = "Jour Mois Année (JJ MM AAAA)";
$pgv_lang["DEFAULT_GEDCOM"]             = "GEDCOM par défaut";
#pgv_lang["default_user"]               = "Create the default administrative user.";
#pgv_lang["del_gedrights"]              = "GEDCOM no longer active, remove user references.";
$pgv_lang["del_proceed"]                = "Continuer";
$pgv_lang["del_unvera"]                 = "Compte non vérifié par un deadministrateur.";
#pgv_lang["del_unveru"]                 = "User didn't verify within 7 days.";
$pgv_lang["do_not_change"]              = "Ne pas modifier";
$pgv_lang["download_file"]              = "Télécharger le fichier";
$pgv_lang["download_gedcom"]            = "Recevoir le fichier GEDCOM sur votre système (download)";
$pgv_lang["download_here"]              = "Téléchargement.";
$pgv_lang["download_note"]              = "Note: un gros fichier GEDCOM risque d'être long à charger. Si PHP stoppe avant la fin du chargement, votre fichier sera incomplet. Vérifier la présence de la ligne '0 TRLR' à la fin du fichier. Généralement, les temps d'envoi (upload)  ou de réception (download) du fichier GEDCOM sont équivalents.";
$pgv_lang["duplicate_username"]         = "Utilisateur déjà existant.  Un utilisateur existe déjà sous ce nom.  Veuillez retourner à la page précédente et choisir un autre nom.";
$pgv_lang["editaccount"]                = "Utilisateur autorisé à modifier le compte";
$pgv_lang["empty_dataset"]              = "Voulez-vous vider le fichier ?";
$pgv_lang["empty_lines_detected"]       = "Lignes vides trouvées dans le fichier GEDCOM. Au chargement, elles seront supprimées.";
#pgv_lang["enable_disable_lang"]        = "Configure supported languages";
$pgv_lang["enter_email"]                = "Vous devez entrer une adresse courriel.";
$pgv_lang["enter_fullname"]             = "Vous devez entrer un prénom et un nom.";
$pgv_lang["error_ban_server"]           = "Adresse IP invalide.";
#pgv_lang["error_delete_person"]        = "You must select the person whose remote link you wish to delete.";
$pgv_lang["error_header_write"]         = "Le fichier GEDCOM #GEDCOM# est en lecture-seule. Vérifier les attributs et droits d'accès.";
#pgv_lang["error_siteauth_failed"]      = "Failed to authenticate to remote site";
#pgv_lang["error_url_blank"]            = "Please do not leave remote site title or URL blank";
#pgv_lang["error_view_info"]            = "You must select the person whose information you wish to view.";
$pgv_lang["example_date"]               = "Exemple de date incorrecte dans votre GEDCOM";
$pgv_lang["example_place"]              = "Exemple de lieu incorrect dans votre GEDCOM";
#pgv_lang["fbsql"]                      = "FrontBase";
$pgv_lang["found_record"]               = "enregistrement(s) trouvé(s)";
$pgv_lang["ged_download"]               = "Télécharger";
$pgv_lang["ged_import"]                 = "Importer";
$pgv_lang["gedcom_adm_head"]            = "Administration GEDCOM";
#pgv_lang["gedcom_config_write_error"]  = "";
$pgv_lang["gedcom_downloadable"]        = "Ce fichier GEDCOM est téléchargeable par n'importe qui sur Internet !<br />Consultez la section SECURITY du fichier <a href=\"readme.txt\">readme.txt</a> pour corriger ce problème";
$pgv_lang["gedcom_file"]                = "Fichier GEDCOM";
$pgv_lang["gedcom_not_imported"]        = "Ce fichier GEDCOM n'a pas encore été importé.";
#pgv_lang["ibase"]                      = "InterBase";
#pgv_lang["ifx"]                        = "Informix";
$pgv_lang["img_admin_settings"]         = "Configuration de l'éditeur d'images";
$pgv_lang["import_complete"]            = "Import terminé";
$pgv_lang["import_marr_names"]          = "Ajouter les noms des maris";
$pgv_lang["import_options"]             = "Options d'importation";
$pgv_lang["import_progress"]            = "Chargement en cours...";
$pgv_lang["import_statistics"]          = "Statistiques d'importation";
$pgv_lang["import_time_exceeded"]       = "Dépassement de la limite de temps d'exécution.";
$pgv_lang["inc_languages"]              = " Langues";
$pgv_lang["INDEX_DIRECTORY"]            = "Répertoire index";
$pgv_lang["invalid_dates"]              = "Les dates de mauvais format seront transformés en JJ MMM AAAA (ie. 1 JAN 2004).";
$pgv_lang["invalid_header"]             = "Lignes trouvées avant le premier marqueur GEDCOM (0 HEAD). Au chargement, elles seront supprimées.";
$pgv_lang["label_add_server"]           = "Ajouter";
$pgv_lang["label_add_search_server"]    = "Ajouter une adresse IP";
$pgv_lang["label_added_servers"]        = "Serveurs distants ajoutés";
$pgv_lang["label_ban_server"]           = "Soumettre";
$pgv_lang["label_banned_servers"]       = "Liste noire";
$pgv_lang["label_families"]             = "Familles";
$pgv_lang["label_gedcom_id2"]           = "ID base de données";
$pgv_lang["label_individuals"]          = "Individus";
#pgv_lang["label_manual_search_engines"]= "Manually mark Search Engines by IP";
$pgv_lang["label_new_server"]           = "Ajouter un site";
$pgv_lang["label_password_id"]          = "Mot de passe";
$pgv_lang["label_remove_ip"]            = "Adresse IP indésirable (Ex: 198.128.*.*): ";
#pgv_lang["label_remove_search"]        = "Mark IP addresses as Search Engine Spiders: ";
#pgv_lang["label_server_info"]          = "All people remotely linked through the site:";
#pgv_lang["label_server_url"]           = "Site URL/IP";
$pgv_lang["label_username_id"]          = "Utilisateur";
$pgv_lang["label_view_local"]           = "Voir info locale sur cette personne";
$pgv_lang["label_view_remote"]          = "Voir info distante sur cette personne";
#pgv_lang["LANG_SELECTION"]             = "Supported languages";
#pgv_lang["LANGUAGE_DEFAULT"]           = "You have not configured the languages your site will support.<br />PhpGedView will use its default actions.";
$pgv_lang["last_login"]                 = "Dernière connexion";
$pgv_lang["lasttab"]                    = "Dernier onglet sélectioné";
#pgv_lang["leave_blank"]                = "Leave password blank if you want to keep the current password.";
$pgv_lang["link_manage_servers"]        = "Gestion des sites";
$pgv_lang["logfile_content"]            = "Contenu du fichier journal";
$pgv_lang["macfile_detected"]           = "Fichier au format Macintosh. Au chargement, il sera converti au format DOS.";
#pgv_lang["mailto"]                     = "Mailto link";
$pgv_lang["merge_records"]              = "Fusionner les enregistrements";
#etpgv_lang["message_to_all"]             = "Send message to all users";
#pgv_lang["messaging"]                  = "PhpGedView internal messaging";
#pgv_lang["messaging2"]                 = "Internal messaging with emails";
#pgv_lang["messaging3"]                 = "PhpGedView sends emails with no storage";
$pgv_lang["month_before_day"]           = "Mois Jour Année (MM JJ AAAA)";
$pgv_lang["monthly"]                    = "Mensuel";
#pgv_lang["msql"]                       = "Mini SQL";
#pgv_lang["mssql"]                      = "Microsoft SQL server";
#pgv_lang["mysql"]                      = "MySQL";
#pgv_lang["mysqli"]                     = "MySQL 4.1+ and PHP 5";
#pgv_lang["never"]                      = "Never";
#pgv_lang["no_logs"]                    = "Disable logging";
#pgv_lang["no_messaging"]               = "No contact method";
$pgv_lang["none"]                       = "Libre";
#pgv_lang["oci8"]                       = "Oracle 7+";
#pgv_lang["page_views"]                 = "&nbsp;&nbsp;page views in&nbsp;&nbsp;";
$pgv_lang["performing_validation"]      = "Validation du fichier GEDCOM";
#pgv_lang["pgsql"]                      = "PostgreSQL";
#pgv_lang["pgv_config_write_error"]     = "Error!!! Cannot write to the PhpGedView configuration file.  Please check file and directory permissions and try again.";
#pgv_lang["PGV_MEMORY_LIMIT"]           = "Memory limit";
$pgv_lang["pgv_registry"]               = "Voir les autres sites web utilisant PhpGedView";
#pgv_lang["PGV_SESSION_SAVE_PATH"]      = "Session save path";
#pgv_lang["PGV_SESSION_TIME"]           = "Session timeout";
#pgv_lang["PGV_SIMPLE_MAIL"]            = "Use simple mail headers in external mails";
#pgv_lang["PGV_STORE_MESSAGES"]         = "Allow messages to be stored online";
$pgv_lang["phpinfo"]                    = "PHPInfo";
$pgv_lang["place_cleanup_detected"]     = "Anomalie sur le format des lieux. Ces erreurs doivent être corrigées avant de continuer. En voici quelques exemples : ";
$pgv_lang["please_be_patient"]          = "Merci de patienter...";
$pgv_lang["privileges"]                 = "Privilèges";
$pgv_lang["reading_file"]               = "Lecture du fichier GEDCOM";
$pgv_lang["readme_documentation"]       = "Lire la documentation README";
#pgv_lang["remove_ip"]                  = "Remove IP";
#pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"]= "Require an administrator to approve new user registrations";
#pgv_lang["review_readme"]              = "You should review the <a href=\"readme.txt\" target=\"_blank\">readme.txt</a> file before continuing to configure PhpGedView.<br /><br />";
$pgv_lang["rootid"]                     = "Individu racine";
$pgv_lang["seconds"]                    = "&nbsp;&nbsp;secondes";
$pgv_lang["select_an_option"]           = "Choisir l'une des options ci-dessous";
$pgv_lang["SERVER_URL"]                 = "URL PhpGedView";
#pgv_lang["show_phpinfo"]               = "Show PHP information page";
$pgv_lang["siteadmin"]                  = "Administrateur du site";
$pgv_lang["skip_cleanup"]               = "Ignorer";
#pgv_lang["sqlite"]                     = "SQLite";
#pgv_lang["sybase"]                     = "Sybase";
#pgv_lang["sync_gedcom"]                = "Synchronize User Settings with GEDCOM Data";
#pgv_lang["system_time"]                = "Current System Time:";
#pgv_lang["TBLPREFIX"]                  = "Database Table Prefix";
$pgv_lang["themecustomization"]         = "Personnalisation des thèmes";
$pgv_lang["time_limit"]                 = "Durée max.";
$pgv_lang["title_manage_servers"]       = "Gestion des sites";
#pgv_lang["title_view_conns"]           = "View Connections";
$pgv_lang["translator_tools"]           = "Outils de traduction";
$pgv_lang["update_myaccount"]           = "Mise à jour de mon compte utilisateur";
$pgv_lang["update_user"]                = "Mise à jour de l'utilisateur";
$pgv_lang["upload_gedcom"]              = "Envoyer un fichier GEDCOM sur le serveur (upload)";
$pgv_lang["USE_REGISTRATION_MODULE"]    = "Autoriser les visiteurs à demander un compte";
$pgv_lang["user_auto_accept"]           = "Accepter automatiquement les modifications faites par cet utilisateur";
$pgv_lang["user_contact_method"]        = "Préférence pour les contacts";
$pgv_lang["user_create_error"]          = "Impossible d'ajouter cet utilisateur. Revenir en arrière et ré-essayer.";
$pgv_lang["user_created"]               = "Nouvel utilisateur ajouté avec succès.";
$pgv_lang["user_default_tab"]           = "Onglet par défaut sur les fiches individuelles";
#pgv_lang["user_path_length"]           = "que lesMax relationship privacy path length";
$pgv_lang["user_relationship_priv"]     = "Limiter l'accès aux proches";
$pgv_lang["users_admin"]                = "Administrateurs du site";
$pgv_lang["users_gedadmin"]             = "Administrateurs GEDCOM";
$pgv_lang["users_total"]                = "Nombre de comptes";
$pgv_lang["users_unver"]                = "Non vérifié par l'utilisateur";
$pgv_lang["users_unver_admin"]          = "Non vérifié par l'administrateur";
$pgv_lang["usr_deleted"]                = "Comptes supprimés: ";
#pgv_lang["usr_idle"]                   = "Number of months since the last login for a user's account to be considered inactive: ";
#pgv_lang["usr_idle_toolong"]           = "User's account has been inactive too long: ";
#pgv_lang["usr_no_cleanup"]             = "Nothing found to cleanup";
#pgv_lang["usr_unset_gedcomid"]         = "Unset GEDCOM ID for ";
#pgv_lang["usr_unset_rights"]           = "Unset GEDCOM rights for ";
#pgv_lang["usr_unset_rootid"]           = "Unset root ID for ";
$pgv_lang["valid_gedcom"]               = "Fichier GEDCOM correct.  Le nettoyage de la base n'est pas nécessaire.";
$pgv_lang["validate_gedcom"]            = "Validation GEDCOM";
$pgv_lang["verified"]                   = "Vérifié par l'utilisateur";
$pgv_lang["verified_by_admin"]          = "Approuvé par l'administrateur";
$pgv_lang["verify_gedcom"]              = "Validation du fichier GEDCOM";
$pgv_lang["verify_upload_instructions"] = "En choisissant <i>Continuer</i> l'ancien GEDCOM sera remplacé par le nouveau fichier chargé. En choisissant <i>Annuler</i> l'ancien fichier GEDCOM restera inchangé.";
$pgv_lang["view_changelog"]             = "Voir le journal des modifications changelog.txt";
$pgv_lang["view_logs"]                  = "Voir le fichier journal";
$pgv_lang["view_readme"]                = "Voir le fichier readme.txt";
$pgv_lang["visibleonline"]              = "Visible par les autres utilisateurs";
$pgv_lang["visitor"]                    = "Visiteur";
#pgv_lang["warn_users"]                 = "Users with warnings";
$pgv_lang["weekly"]                     = "Hebdomadaire";
$pgv_lang["welcome_new"]                = "Bienvenue sur votre nouveau site PhpGedView.";
$pgv_lang["yearly"]                     = "Annuel";
$pgv_lang["you_may_login"]              = " a été approuvé par l'administrateur du site. Vous pouvez maintenant vous connecter au site PhpGedView par ce lien";

?>
