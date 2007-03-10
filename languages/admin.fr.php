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
if (preg_match("/admin\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
	print "You cannot access a language file directly.";
	exit;
}

$pgv_lang["clear_cache_succes"]         = "Fichiers supprimés du cache.";
$pgv_lang["clear_cache"]                = "Vider le cache";
$pgv_lang["sanity_err0"]                = "Erreurs:";
$pgv_lang["sanity_err1"]                = "Ce programme nécessite PHP version 4.3 ou supérieure.";
$pgv_lang["sanity_err2"]                = "Fichier ou répertoire non trouvé : <i>#GLOBALS[whichFile]#</i>. Vérifier son existence et ses droits d'accès.";
$pgv_lang["sanity_err3"]                = "Fichier mal téléchargé : <i>#GLOBALS[whichFile]#</i>. Essayer à nouveau.";
$pgv_lang["sanity_err4"]                = "Fichier corrompu : <i>config.php</i>.";
$pgv_lang["sanity_err5"]                = "Impossible d'écrire dans le fichier : <i>config.php</i>.";
$pgv_lang["sanity_err6"]                = "Impossible d'écrire dans le répertoire : <i>#GLOBALS[INDEX_DIRECTORY]#</i>.";
$pgv_lang["sanity_warn0"]               = "Avertissements:";
$pgv_lang["sanity_warn1"]               = "Impossible d'écrire dans le répertoire : <i>#GLOBALS[MEDIA_DIRECTORY]#</i>. Vous ne pourrez pas charger d'objets MultiMedia.";
$pgv_lang["sanity_warn2"]               = "Impossible d'écrire dans le répertoire : <i>#GLOBALS[MEDIA_DIRECTORY]#thumbs</i>. Vous ne pourrez pas créer les vignettes des objets MultiMedia.";
$pgv_lang["sanity_warn3"]               = "Librairie 'GD' absente. Il vous sera impossible d'utiliser certaines fonctionnalités : génération automatique des vignettes MultiMedia et le diagramme circulaire. Pour en savoir plus : <a href='http://www.php.net/manual/fr/ref.image.php'>http://www.php.net/manual/fr/ref.image.php</a>.";
$pgv_lang["sanity_warn4"]               = "Librairie 'XML Parser' absente. Il vous sera impossible d'utiliser certaines fonctionnalités : rapports, services web.... Pour en savoir plus : <a href='http://www.php.net/manual/fr/ref.xml.php'>http://www.php.net/manual/fr/ref.xml.php</a>.";
$pgv_lang["sanity_warn5"]               = "Librairie 'DOM XML'  absente. Il vous sera impossible d'utiliser certaines fonctionnalités : export 'Gramps', téléchargements, services web... Pour en savoir plus : <a href='http://www.php.net/manual/fr/ref.dom.php'>http://www.php.net/manual/fr/ref.dom.php</a>.";
$pgv_lang["sanity_warn6"]               = "Librairie 'Calendar' absente. Il vous sera impossible d'utiliser certaines fonctionnalités : conversion de dates au format hébreu ou calendrier révolutionnaire. Pour en savoir plus : <a href='http://www.php.net/manual/fr/ref.calendar.php'>http://www.php.net/manual/fr/ref.calendar.php</a>.";
$pgv_lang["ip_address"]                 = "Adresse IP";
$pgv_lang["date_time"]                  = "Date et heure";
#pgv_lang["log_message"]                = "Log Message";
$pgv_lang["searchtype"]                 = "Type de recherche";
$pgv_lang["query"]                      = "Requête";
$pgv_lang["user"]                       = "Utilisateur authentifié";
$pgv_lang["thumbnail_deleted"]          = "Vignette supprimée.";
$pgv_lang["thumbnail_not_deleted"]      = "Ce fichier vignette est protégé et ne peut pas être supprimé sans autorisation.";
$pgv_lang["step2"]                      = "Etape 2/4 :";
$pgv_lang["refresh"]                    = "Rafraîchir";
$pgv_lang["move_file_success"]          = "Le fichier MultiMedia et la vignette ont été déplacés.";
$pgv_lang["media_folder_corrupt"]       = "Le dossier MultiMedia est corrompu.";
$pgv_lang["media_file_not_deleted"]     = "Ce fichier MultiMedia est protégé et ne peut pas être supprimé sans autorisation.";
$pgv_lang["gedcom_deleted"]             = "GEDCOM [#GED#] supprimé avec succès.";
$pgv_lang["gedadmin"]                   = "Administrateur GEDCOM";
$pgv_lang["full_name"]                  = "Prénom et nom";
$pgv_lang["error_header"]               = "Le fichier GEDCOM #GEDCOM# n'existe pas à l'emplacement indiqué.";
$pgv_lang["confirm_delete_file"]        = "Confirmez-vous la suppression de ce fichier ?";
$pgv_lang["confirm_folder_delete"]      = "Confirmez-vous la suppression de ce dossier ?";
$pgv_lang["confirm_remove_links"]       = "Confirmez-vous la suppression des liens vers cet objet ?";
$pgv_lang["PRIV_PUBLIC"]                = "Montrer à tout le monde";
$pgv_lang["PRIV_USER"]                  = "Montrer uniquement aux utilisateurs authentifiés";
$pgv_lang["PRIV_NONE"]                  = "Montrer uniquement à l'administrateur";
$pgv_lang["PRIV_HIDE"]                  = "Ne montrer à personne";
$pgv_lang["manage_gedcoms"]             = "Gérer les fichiers GEDCOM";
$pgv_lang["keep_media"]                 = "Conserver les liens MultiMedia";
$pgv_lang["files_in_backup"]            = "Liste des fichiers sauvegardés";
$pgv_lang["created_remotelinks"]        = "Table <i>Remotelinks</i> créée.";
$pgv_lang["created_remotelinks_fail"]   = "Impossible de créer la table <i>Remotelinks</i>";
$pgv_lang["created_indis"]              = "Table <i>Individus</i> créée.";
$pgv_lang["created_indis_fail"]         = "Impossible de créer la table <i>Individus</i>";
$pgv_lang["created_fams"]               = "Table <i>Familles</i> créée.";
$pgv_lang["created_fams_fail"]          = "Impossible de créer la table <i>Familles</i>";
$pgv_lang["created_sources"]            = "Table <i>Sources</i> créée.";
$pgv_lang["created_sources_fail"]       = "Impossible de créer la table <i>Sources</i>";
$pgv_lang["created_other"]              = "Table <i>Autres</i> créée.";
$pgv_lang["created_other_fail"]         = "Impossible de créer la table <i>Autres</i>";
$pgv_lang["created_places"]             = "Table <i>Lieux</i> créée.";
$pgv_lang["created_places_fail"]        = "Impossible de créer la table <i>Lieux</i>";
$pgv_lang["created_placelinks"]         = "Table <i>Place links</i> créée.";
$pgv_lang["created_placelinks_fail"]    = "Impossible de créer la table <i>Place links</i>";
$pgv_lang["created_media_fail"]         = "Impossible de créer la table <i>Media</i>";
$pgv_lang["created_media_mapping_fail"] = "Impossible de créer la table <i>Media mappings</i>";
$pgv_lang["no_thumb_dir"]               = " impossible de créer le dossier vignettes";
$pgv_lang["folder_created"]             = "Dossier créé";
$pgv_lang["folder_no_create"]           = "Impossible de créer le dossier";
$pgv_lang["security_no_create"]         = "Avertissement concernant la sécurité : fichier index.php absent du dossier ";
$pgv_lang["security_not_exist"]         = "Avertissement concernant la sécurité : impossible de créer le fichier index.php dans le dossier ";
$pgv_lang["label_add_search_server"]    = "Ajouter IP";
$pgv_lang["label_add_server"]           = "Ajouter";
$pgv_lang["label_ban_server"]           = "Soumettre";
$pgv_lang["label_delete"]               = "Supprimer";
$pgv_lang["progress_bars_info"]         = "La barre d'état vous indique la progression du chargement. En cas de dépassement du temps-limite, cliquer sur 'Continuer'. Si le bouton 'Continuer' n'apparait pas, recommencer l'opération avec un temps-limite plus petit.";
$pgv_lang["upload_replacement"]         = "Ecraser le fichier";
$pgv_lang["about_user"]                 = "Vous devez d'abord créer un <b>administrateur principal</b>. Cet utilisateur pourra mettre à jour les fichiers de configuration, consulter les données privées et accorder des droits à d'autres utilisateurs.";
$pgv_lang["access"]                     = "Droits pour Consulter";
$pgv_lang["add_gedcom"]                 = "Paramètrer un GEDCOM déjà envoyé sur le serveur";
$pgv_lang["add_new_gedcom"]             = "Créer un nouveau GEDCOM vide";
$pgv_lang["add_new_language"]           = "Ajouter fichiers et paramètres pour une nouvelle langue";
$pgv_lang["add_user"]                   = "Ajouter un nouvel utilisateur";
$pgv_lang["admin_gedcom"]               = "Administrateur GEDCOM";
$pgv_lang["admin_gedcoms"]              = "Gérer les fichiers GEDCOM.";
$pgv_lang["admin_geds"]                 = "Fichiers et données GEDCOM";
$pgv_lang["admin_info"]                 = "Informations";
$pgv_lang["admin_site"]                 = "Administration du site";
$pgv_lang["admin_user_warnings"]        = "Un ou plusieurs comptes ont un avertissement";
$pgv_lang["admin_verification_waiting"] = "Compte(s) en attente de vérification par l'administrateur";
$pgv_lang["administration"]             = "Administration";
$pgv_lang["ALLOW_CHANGE_GEDCOM"]        = "Autoriser le choix du fichier GEDCOM";
$pgv_lang["ALLOW_REMEMBER_ME"]          = "Autoriser l'option \"Rester connecté\"";
$pgv_lang["ALLOW_USER_THEMES"]          = "Permettre aux utilisateurs de choisir leur propre thème";
$pgv_lang["ansi_encoding_detected"]     = "Ce fichier est au format ANSI. PhpGedView recommande le format UTF-8.";
$pgv_lang["ansi_to_utf8"]               = "Convertir ce fichier GEDCOM format ANSI en format UTF-8 ?";
$pgv_lang["apply_privacy"]              = "Appliquer les règles de restrictions d'accès ?";
$pgv_lang["back_useradmin"]             = "Retour au menu Administration";
$pgv_lang["bytes_read"]                 = "Octets lus";
$pgv_lang["calc_marr_names"]            = "Ajout du nom des maris";
$pgv_lang["can_admin"]                  = "Droits pour Administrer";
$pgv_lang["can_edit"]                   = "Droits pour Modifier";
$pgv_lang["change_id"]                  = "Changer le code individu en";
$pgv_lang["choose_priv"]                = "Niveau de restriction:";
$pgv_lang["cleanup_places"]             = "Chargement des lieux";
$pgv_lang["cleanup_users"]              = "Suppression des utilisateurs";
$pgv_lang["click_here_to_continue"]     = "Continuer.";
$pgv_lang["click_here_to_go_to_pedigree_tree"]= "Afficher l'arbre";
$pgv_lang["comment"]                    = "Avis de l'administrateur";
$pgv_lang["comment_exp"]                = "Avertissement de l'administrateur le";
$pgv_lang["config_help"]                = "Aide à la configuration";
$pgv_lang["config_still_writable"]      = "Votre fichier <b>config.php</b> est accessible en écriture. Par sécurité il faut le remettre en <b>lecture-seule</b> après toute modification.";
$pgv_lang["configuration"]              = "Configurer PhpGedView";
$pgv_lang["configure"]                  = "Configurer PhpGedView";
$pgv_lang["configure_head"]             = "Configuration PhpGedView";
$pgv_lang["confirm_gedcom_delete"]      = "Confirmez-vous la suppression de ce fichier GEDCOM ?";
$pgv_lang["confirm_user_delete"]        = "Confirmez-vous la suppression de cet utilisateur ?";
$pgv_lang["create_user"]                = "Ajouter un utilisateur";
$pgv_lang["current_users"]              = "Liste des utilisateurs";
$pgv_lang["daily"]                      = "Quotidien";
$pgv_lang["dataset_exists"]             = "Un fichier GEDCOM de même nom a déjà été introduit dans la base de données.";
$pgv_lang["date_registered"]            = "Déclaration";
$pgv_lang["day_before_month"]           = "Jour Mois Année (JJ MM AAAA)";
$pgv_lang["DEFAULT_GEDCOM"]             = "Fichier GEDCOM par défaut";
$pgv_lang["default_user"]               = "Création de l'administrateur par défaut.";
#pgv_lang["del_gedrights"]              = "GEDCOM no longer active, remove user references.";
$pgv_lang["del_proceed"]                = "Continuer";
$pgv_lang["del_unvera"]                 = "Compte non vérifié par un deadministrateur.";
#pgv_lang["del_unveru"]                 = "User didn't verify within 7 days.";
$pgv_lang["do_not_change"]              = "Ne pas modifier";
$pgv_lang["download_file"]              = "Télécharger le fichier sur votre système (<i>Download</i>)";
$pgv_lang["download_gedcom"]            = "Recevoir le fichier GEDCOM sur votre système (download)";
$pgv_lang["download_here"]              = "Charger le fichier sur votre système (<i>Download</i>).";
$pgv_lang["download_note"]              = "Note: un gros fichier GEDCOM risque d'être long à charger. Si PHP stoppe avant la fin du chargement, votre fichier sera incomplet. Vérifier la présence de la ligne '0 TRLR' à la fin du fichier. Généralement, les temps d'envoi (upload)  ou de réception (download) du fichier GEDCOM sont équivalents.";
$pgv_lang["editaccount"]                = "Utilisateur autorisé à modifier le compte";
$pgv_lang["empty_dataset"]              = "Voulez-vous vider le fichier ?";
$pgv_lang["empty_lines_detected"]       = "Lignes vides trouvées dans le fichier GEDCOM. Au chargement, elles seront supprimées.";
$pgv_lang["enable_disable_lang"]        = "Configurer les langues";
$pgv_lang["error_ban_server"]           = "Adresse IP invalide.";
#pgv_lang["error_delete_person"]        = "You must select the person whose remote link you wish to delete.";
$pgv_lang["error_header_write"]         = "Le fichier GEDCOM #GEDCOM# est en lecture-seule. Vérifier les attributs et droits d'accès.";
$pgv_lang["error_siteauth_failed"]      = "Echec d'authentification au site distant";
#pgv_lang["error_url_blank"]            = "Please do not leave remote site title or URL blank";
#pgv_lang["error_view_info"]            = "You must select the person whose information you wish to view.";
$pgv_lang["example_date"]               = "Exemple de date incorrecte dans votre GEDCOM";
$pgv_lang["example_place"]              = "Exemple de lieu incorrect dans votre GEDCOM";
$pgv_lang["fbsql"]                      = "FrontBase";
$pgv_lang["found_record"]               = "enregistrement(s) trouvé(s)";
$pgv_lang["ged_download"]               = "Télécharger (<i>Download</i>)";
$pgv_lang["ged_import"]                 = "Importer";
$pgv_lang["ged_check"]                  = "Vérifier";
$pgv_lang["gedcom_adm_head"]            = "Administration GEDCOM";
$pgv_lang["gedcom_config_write_error"]  = "E R R E U R !!!<br />Impossible d'écrire dans le fichier <i>#GLOBALS[whichFile]#</i>. Vérifier les droits d'accès.";
$pgv_lang["gedcom_downloadable"]        = "Ce fichier GEDCOM est téléchargeable par n'importe qui sur Internet !<br />Consultez la section SECURITY du fichier <a href=\"readme.txt\">readme.txt</a> pour corriger ce problème";
$pgv_lang["gedcom_file"]                = "Fichier GEDCOM";
$pgv_lang["gedcom_not_imported"]        = "Ce fichier GEDCOM n'a pas encore été chargé.";
$pgv_lang["ibase"]                      = "InterBase";
$pgv_lang["ifx"]                        = "Informix";
$pgv_lang["img_admin_settings"]         = "Configuration de l'éditeur d'images";
$pgv_lang["import_complete"]            = "Import terminé";
$pgv_lang["import_marr_names"]          = "Ajouter les noms des maris";
$pgv_lang["import_options"]             = "Options d'importation";
$pgv_lang["import_progress"]            = "Chargement en cours...";
$pgv_lang["import_statistics"]          = "Statistiques d'importation";
$pgv_lang["import_time_exceeded"]       = "Dépassement de la limite de temps d'exécution.";
$pgv_lang["inc_languages"]              = " Langues";
$pgv_lang["INDEX_DIRECTORY"]            = "Répertoire des fichiers d'index";
$pgv_lang["invalid_dates"]              = "Les dates de mauvais format seront transformées en JJ MMM AAAA (ie. 1 JAN 2004).";
$pgv_lang["BOM_detected"]               = "Marqueur Byte Order Mark (BOM) trouvé en début de fichier. Il sera supprimé.";
$pgv_lang["invalid_header"]             = "Lignes trouvées avant le premier marqueur GEDCOM (0 HEAD). Au chargement, elles seront supprimées.";
$pgv_lang["label_add_server"]           = "Ajouter";
$pgv_lang["label_add_search_server"]    = "Ajouter IP";
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
$pgv_lang["label_server_url"]           = "URL ou adresse IP du site";
$pgv_lang["label_username_id"]          = "Utilisateur";
$pgv_lang["label_view_local"]           = "Voir info locale sur cette personne";
$pgv_lang["label_view_remote"]          = "Voir info distante sur cette personne";
$pgv_lang["LANG_SELECTION"]             = "Langues supportées";
$pgv_lang["LANGUAGE_DEFAULT"]           = "Vous n'avez pas configuré les options de langues.<br />PhpGedView va utiliser les valeurs par défaut.";
$pgv_lang["last_login"]                 = "Dernière visite";
$pgv_lang["lasttab"]                    = "Dernier onglet sélectioné";
$pgv_lang["leave_blank"]                = "Laisser le champ vide pour conserver le mot de passe existant.";
$pgv_lang["link_manage_servers"]        = "Gestion des sites";
$pgv_lang["logfile_content"]            = "Contenu du fichier journal";
$pgv_lang["macfile_detected"]           = "Fichier au format Macintosh. Au chargement, il sera converti au format DOS.";
$pgv_lang["mailto"]                     = "Lien Courriel [mailto:]";
$pgv_lang["merge_records"]              = "Fusionner les enregistrements";
$pgv_lang["message_to_all"]             = "Envoi d'un message à tous les utilisateurs";
$pgv_lang["messaging"]                  = "Messagerie interne PhpGedView";
$pgv_lang["messaging2"]                 = "Messagerie interne par courriel";
$pgv_lang["messaging3"]                 = "PhpGedView ne conserve pas les courriels envoyés";
$pgv_lang["month_before_day"]           = "Mois Jour Année (MM JJ AAAA)";
$pgv_lang["monthly"]                    = "Mensuel";
$pgv_lang["msql"]                       = "Mini SQL";
$pgv_lang["mssql"]                      = "Microsoft SQL Server";
$pgv_lang["mysql"]                      = "MySQL";
$pgv_lang["mysqli"]                     = "MySQL 4.1+ et PHP 5";
$pgv_lang["never"]                      = "Jamais";
$pgv_lang["no_logs"]                    = "Journal désactivé";
$pgv_lang["no_messaging"]               = "Messagerie désactivée";
$pgv_lang["none"]                       = "Libre";
$pgv_lang["oci8"]                       = "Oracle 7+";
$pgv_lang["page_views"]                 = "&nbsp;&nbsp;visites en &nbsp;&nbsp;";
$pgv_lang["performing_validation"]      = "Validation du fichier GEDCOM";
$pgv_lang["pgsql"]                      = "PostgreSQL";
$pgv_lang["pgv_config_write_error"]     = "Impossible d'écrire dans le fichier de configuration PhpGedView. Vérifier les droits d'accès et réessayer.";
$pgv_lang["PGV_MEMORY_LIMIT"]           = "Limite de mémoire";
$pgv_lang["pgv_registry"]               = "Voir les autres sites web utilisant PhpGedView";
$pgv_lang["PGV_SESSION_SAVE_PATH"]      = "Répertoire des sauvegardes de sessions";
$pgv_lang["PGV_SESSION_TIME"]           = "Limite de durée d'une session";
$pgv_lang["PGV_SIMPLE_MAIL"]            = "Utiliser un en-tête simple pour les courriels";
$pgv_lang["PGV_STORE_MESSAGES"]         = "Autoriser le stockage des messages sur le serveur";
$pgv_lang["phpinfo"]                    = "PHPInfo";
$pgv_lang["place_cleanup_detected"]     = "Anomalie sur le format des lieux. Ces erreurs doivent être corrigées avant de continuer. En voici quelques exemples : ";
$pgv_lang["please_be_patient"]          = "Merci de patienter...";
$pgv_lang["privileges"]                 = "Droits";
$pgv_lang["reading_file"]               = "Lecture du fichier GEDCOM";
$pgv_lang["readme_documentation"]       = "Lire la documentation README";
$pgv_lang["remove_ip"]                  = "Supprimer IP";
$pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"]= "Un administrateur devra valider toute nouvelle demande de compte.";
$pgv_lang["review_readme"]              = "Il est conseillé de consulter d'abord le fichier <a href=readme.txt target=_blank>readme.txt</a> avant de poursuivre la configuration de PhpGedView.<br /><br />";
$pgv_lang["rootid"]                     = "Individu racine";
$pgv_lang["seconds"]                    = "&nbsp;&nbsp;secondes";
$pgv_lang["select_an_option"]           = "Choisir l'une des options ci-dessous";
$pgv_lang["SERVER_URL"]                 = "URL du serveur";
$pgv_lang["show_phpinfo"]               = "Voir la page PHPInfo ";
$pgv_lang["siteadmin"]                  = "Administrateur du site";
$pgv_lang["skip_cleanup"]               = "Ignorer";
$pgv_lang["sqlite"]                     = "SQLite";
$pgv_lang["sybase"]                     = "Sybase";
$pgv_lang["sync_gedcom"]                = "Synchroniser avec les données GEDCOM";
$pgv_lang["system_time"]                = "Heure du serveur";
$pgv_lang["user_time"]                  = "Heure du navigateur";
$pgv_lang["TBLPREFIX"]                  = "Préfixe des noms de tables";
$pgv_lang["themecustomization"]         = "Personnalisation des thèmes";
$pgv_lang["time_limit"]                 = "Durée max.";
$pgv_lang["title_manage_servers"]       = "Gestion des sites";
$pgv_lang["title_view_conns"]           = "Voir les connexions";
$pgv_lang["translator_tools"]           = "Outils de traduction";
$pgv_lang["update_myaccount"]           = "Mise à jour de mon compte utilisateur";
$pgv_lang["update_user"]                = "Mise à jour de l'utilisateur";
$pgv_lang["upload_gedcom"]              = "Envoyer un fichier GEDCOM sur le serveur (upload)";
$pgv_lang["USE_REGISTRATION_MODULE"]    = "Permettre aux utilisateurs de demander l'enregistrement de leur compte";
$pgv_lang["user_auto_accept"]           = "Accepter automatiquement les modifications faites par cet utilisateur";
$pgv_lang["user_contact_method"]        = "Préférence pour les contacts";
$pgv_lang["user_create_error"]          = "Impossible d'ajouter cet utilisateur. Revenir en arrière et ré-essayer.";
$pgv_lang["user_created"]               = "Nouvel utilisateur ajouté avec succès.";
$pgv_lang["user_default_tab"]           = "Onglet par défaut sur les fiches individuelles";
$pgv_lang["user_path_length"]           = "Degré de parenté max";
$pgv_lang["user_relationship_priv"]     = "Limiter l'accès aux proches";
$pgv_lang["users_admin"]                = "Administrateurs du site";
$pgv_lang["users_gedadmin"]             = "Administrateurs GEDCOM";
$pgv_lang["users_total"]                = "Nombre total d'utilisateurs";
$pgv_lang["users_unver"]                = "Non vérifié par l'utilisateur";
$pgv_lang["users_unver_admin"]          = "Non vérifié par l'administrateur";
$pgv_lang["usr_deleted"]                = "Utilisateur supprimé: ";
#pgv_lang["usr_idle"]                   = "Number of months since the last login for a user's account to be considered inactive: ";
#pgv_lang["usr_idle_toolong"]           = "User's account has been inactive too long: ";
$pgv_lang["usr_no_cleanup"]             = "Rien à supprimer";
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
$pgv_lang["warn_users"]                 = "Utilisateurs en anomalie";
$pgv_lang["weekly"]                     = "Hebdo";
$pgv_lang["welcome_new"]                = "Bienvenue sur votre site PhpGedView. L'affichage de cette page signifie que PhpGedView a été correctement installé sur votre serveur. Vous pouvez lancer la configuration.<br />";
$pgv_lang["yearly"]                     = "Annuel";
#pgv_lang["admin_OK_subject"]           = "Approval of account at #SERVER_NAME#";
#pgv_lang["admin_OK_message"]           = "The administrator at the PhpGedView site #SERVER_NAME# has approved your application for an account.  You may now login by accessing the following link:\r\n\r\n#SERVER_NAME#\r\n";

// Text for the Gedcom Checker
$pgv_lang["gedcheck"]                   = "Vérificateur Gedcom";          // Module title
$pgv_lang["gedcheck_text"]              = "Ce module vérifie le format du fichier GEDCOM selon la norme <a href=\"http://phpgedview.sourceforge.net/ged551-5.pdf\">GEDCOM 5.5.1</a>. L'outil détecte aussi certaines erreurs fréquentes dans les données. Comme il existe de nombreuses variantes de cette norme, seules les erreurs 'graves' sont indispensables à corriger. Merci de bien lire l'explication de chaque erreur dans la norme avant de demander de l'aide.";
$pgv_lang["level"]                      = "Niveau";                   // Levels of checking
$pgv_lang["critical"]                   = "Erreur grave";
$pgv_lang["error"]                      = "Erreur";
$pgv_lang["warning"]                    = "Avertissement";
$pgv_lang["info"]                       = "Information";
$pgv_lang["open_link"]                  = "Ouvrir les liens dans";           // Where to open links
$pgv_lang["same_win"]                   = "le même onglet ou la même fenêtre";
$pgv_lang["new_win"]                    = "un nouvel onglet ou une nouvelle fenêtre";
$pgv_lang["context_lines"]              = "Nombre de lignes GEDCOM<br />avant et après la ligne en erreur"; // Number of lines either side of error
$pgv_lang["all_rec"]                    = "Tous les enregistrements";             // What to show
$pgv_lang["err_rec"]                    = "Les enregistrements comportant une erreur";
$pgv_lang["missing"]                    = "balise manquante";                 // General error messages
$pgv_lang["multiple"]                   = "multiple";
$pgv_lang["invalid"]                    = "mauvaise";
$pgv_lang["too_many"]                   = "trop";
$pgv_lang["too_few"]                    = "pas assez";
$pgv_lang["no_link"]                    = "le lien de retour est manquant";
$pgv_lang["data"]                       = "donnée";                    // Specific errors (used with general errors)
$pgv_lang["see"]                        = "voir";
$pgv_lang["noref"]                      = "aucun lien vers cet enregistrement";
$pgv_lang["tag"]                        = "balise";
$pgv_lang["spacing"]                    = "espacement";
$pgv_lang["ADVANCED_NAME_FACTS"]        = "Options avancées pour le nom de famille";
$pgv_lang["SURNAME_TRADITION"]          = "Mode de transmission du nom"; // Default surname inheritance
$pgv_lang["spanish"]                    = "Façon Espagnole";
$pgv_lang["portuguese"]                 = "Façon Portugaise";
$pgv_lang["icelandic"]                  = "Façon Islandaise";
$pgv_lang["paternal"]                   = "Nom du père (défaut)";

?>
