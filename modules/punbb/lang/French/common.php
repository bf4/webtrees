﻿<?php

/*
// Determine what locale to use
switch (PHP_OS)
{
	case 'WINNT':
	case 'WIN32':
		$locale = 'french';
		break;

	case 'FreeBSD':
	case 'NetBSD':
	case 'OpenBSD':
		$locale = 'fr_FR.ISO8859-1';
		break;

	default:
		$locale = 'fr_FR';
		break;
}

// Attempt to set the locale
setlocale(LC_CTYPE, $locale);
*/


// Language definitions for frequently used strings
 
$lang_common = array(
// Text orientation and encoding
'lang_direction'		=>	'ltr',	// ltr (Left-To-Right) or rtl (Right-To-Left)
'lang_encoding'			=>	'iso-8859-1',
'lang_multibyte'		=>	false,
 
// Notices
'Bad request'			=>	'Erreur. Le lien que vous avez suivi est incorrect ou périmé.',
'No view'				=>	'Vous n\'avez pas l\'autorisation d\'accéder à ces forums.',
'No permission'			=>	'Vous n\'avez pas l\'autorisation d\'afficher cette page.',
'Bad referrer'			=>	'Mauvais HTTP_REFERER. Vous avez été renvoyé sur cette page par une source inconnue ou interdite. Si le problème persiste, assurez-vous que le champ \'URL de base\' de la page Admin/Options est correctement renseigné et que vous visitez ces forums en utilisant cette URL. Plus d\'informations pourront être trouvées dans la documentation de PunBB.',
 
// Topic/forum indicators
'New icon'				=>	'Il y a des nouveaux messages',
'Normal icon'			=>	'<!-- -->',
'Closed icon'			=>	'Cette discussion est fermée',
'Redirect icon'			=>	'Forum de redirection',
 
// Miscellaneous
'Announcement'			=>	'Annonce',
'Options'				=>	'Options',
'Actions'				=>	'Actions',
'Submit'				=>	'Envoyer',	// "name" of submit buttons
'Ban message'			=>	'Votre compte utilisateur est exclu de ce forum.',
'Ban message 2'			=>	'L\'exclusion expire le',
'Ban message 3'			=>	'L\'administrateur ou le modérateur qui a exclu votre compte utilisateur envoit le message suivant&#160;:',
'Ban message 4'			=>	'Pour toute question, contactez l\'administrateur',
'Never'					=>	'Jamais',
'Today'					=>	'Aujourd\'hui',
'Yesterday'				=>	'Hier',
'Info'					=>	'Info',		// a common table header
'Go back'				=>	'Retour',
'Maintenance'			=>	'Maintenance',
'Redirecting'			=>	'Redirection',
'Click redirect'		=>	'Cliquez ici si vous ne voulez pas attendre (ou si votre navigateur ne vous redirige pas).',
'on'					=>	'actif',		// as in "BBCode is on"
'off'					=>	'inactif',
'Invalid e-mail'		=>	'L\'adresse de courriel que vous avez saisie est invalide.',
'required field'		=>	'est un champ requis pour ce formulaire.',	// for javascript form validation
'Last post'				=>	'Dernier message',
'by'					=>	'par',	// as in last post by someuser
'New posts'				=>	'Nouveaux&#160;messages',	// the link that leads to the first new post (use &#160; for spaces)
'New posts info'		=>	'Allez au premier nouveau message de cette discussion.',	// the popup text for new posts links
'Username'				=>	'Nom d\'utilisateur',
'Password'				=>	'Mot de passe',
'E-mail'				=>	'Courriel',
'Send e-mail'			=>	'Envoyer un courriel',
'Moderated by'			=>	'Modéré par',
'Registered'			=>	'Date d\'inscription',
'Subject'				=>	'Sujet',
'Message'				=>	'Message',
'Topic'					=>	'Discussion',
'Forum'					=>	'Forum',
'Posts'					=>	'Messages',
'Replies'				=>	'Réponses',
'Author'				=>	'Auteur',
'Pages'					=>	'Pages',
'BBCode'				=>	'BBCode',	// You probably shouldn't change this
'img tag'				=>	'Balise [img]',
'Smilies'				=>	'Émoticônes',
'and'					=>	'et',
'Image link'			=>	'image',	// This is displayed (i.e. <image>) instead of images when "Show images" is disabled in the profile
'wrote'					=>	'a écrit',	// For [quote]'s
'Code'					=>	'Code',		// For [code]'s
'Mailer'				=>	'Courriel automatique',	// As in "MyForums Mailer" in the signature of outgoing e-mails
'Important information'	=>	'Information importante',
'Write message legend'	=>	'Veuillez écrire votre message et l\'envoyer',
 
// Title
'Title'					=>	'Titre',
'Member'				=>	'Membre',	// Default title
'Moderator'				=>	'Modérateur',
'Administrator'			=>	'Administrateur',
'Banned'				=>	'Banni',
'Guest'					=>	'Invité',
 
// Stuff for include/parser.php
'BBCode error'			=>	'La syntaxe BBCode est incorrecte.',
'BBCode error 1'		=>	'Il manque la balise d\'ouverture pour [/quote].',
'BBCode error 2'		=>	'Il manque la balise de fermeture pour [code].',
'BBCode error 3'		=>	'Il manque la balise d\'ouverture pour [/code].',
'BBCode error 4'		=>	'Il manque une ou plusieurs balises de fermeture pour [quote].',
'BBCode error 5'		=>	'Il manque une ou plusieurs balises d\'ouverture manquantes pour [/quote].',
 
// Stuff for the navigator (top of every page)
'Index'					=>	'Accueil forums',
'User list'				=>	'Liste des membres',
'Rules'					=>  'Règles',
'Search'				=>  'Recherche',
'Register'				=>  'Inscription',
'Login'					=>  'S\'identifier',
'Not logged in'			=>  'Vous n\'êtes pas identifié.',
'Profile'				=>	'Profil',
'Logout'				=>	'Déconnexion',
'Logged in as'			=>	'Connecté en tant que',
'Admin'					=>	'Administration',
'Last visit'			=>	'Dernière visite',
'Show new posts'		=>	'Afficher les nouveaux messages depuis la dernière visite',
'Mark all as read'		=>	'Marquer toutes les discussions comme lues',
'Link separator'		=>	'',	// The text that separates links in the navigator
 
// Stuff for the page footer
'Board footer'			=>	'Pied de page des forums',
'Search links'			=>	'Liens de recherche',
'Show recent posts'		=>	'Afficher les messages récents',
'Show unanswered posts'	=>	'Afficher les messages sans réponse',
'Show your posts'		=>	'Afficher vos messages',
'Show subscriptions'	=>	'Afficher les discussions auxquelles vous êtes abonné',
'Jump to'				=>	'Aller à',
'Go'					=>	' Aller ',		// submit button in forum jump
'Move topic'			=>  'Déplacer la discussion',
'Open topic'			=>  'Ouvrir la discussion',
'Close topic'			=>  'Fermer la discussion',
'Unstick topic'			=>  'Détacher la discussion',
'Stick topic'			=>  'Épingler la discussion',
'Moderate forum'		=>	'Modérer le forum',
'Delete posts'			=>	'Supprimer plusieurs messages',
'Debug table'			=>	'Informations de débogue',
 
// For extern.php RSS feed
'RSS Desc Active'		=>	'Les discussions récemment actives de',	// board_title will be appended to this string
'RSS Desc New'			=>	'Les dernières discussions de',					// board_title will be appended to this string
'Posted'				=>	'Écrit le'	// The date/time a topic was started
);
 