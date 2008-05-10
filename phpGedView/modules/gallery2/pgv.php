<?php
/**
 * phpGedView Gallery 2 Module.
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
 * @subpackage Gallery2
 * @version $Id$
 * @author Patrick Kellum
 */
/*
 * Seperated out of G2 embeding for use elseware.
 */

global $SERVER_URL, $language_settings, $LANGUAGE, $modinfo, $pgv_lang;

// For block support
if(!defined('PGV_MOD_SIMPLE')){$modinfo = parse_ini_file('modules/gallery2.php', true);}

// Gallery path sanity check
if(!file_exists($modinfo['Gallery2']['path'])){$modinfo['Gallery2']['path'] = 'modules/gallery2';}

// Check if gallery installed, if not then return false
if(!file_exists("{$modinfo['Gallery2']['path']}/embed.php"))
{
	define('PGV_GALLERY2_INIT', false);
	return;
}
define('PGV_GALLERY2_INIT', true);

// Load the embeding API
include_once "{$modinfo['Gallery2']['path']}/embed.php";

// Load PGV embeding language file
require_once 'modules/gallery2/language/mod_en.php';

// Load other language file if needed
if($language_settings[$LANGUAGE]['lang_short_cut'] != 'en' && file_exists("modules/gallery2/language/mod_{$language_settings[$LANGUAGE]['lang_short_cut']}.php")){require_once "modules/gallery2/language/mod_{$language_settings[$LANGUAGE]['lang_short_cut']}.php";}

// Load some tools for embeding ease of use
require_once 'modules/gallery2/G2EmbedDiscoveryUtilities.class';

function mod_gallery2_load($uid)
{
	global $SERVER_URL, $language_settings, $LANGUAGE, $modinfo, $pgv_lang;

	if($SERVER_URL[strlen($SERVER_URL) - 1] == '/'){$sep = '';}else{$sep = '/';}

	$ret = GalleryEmbed::init(array(
		'embedUri'			=> "{$SERVER_URL}{$sep}index.php?mod=gallery2",
		'g2Uri'				=> G2EmbedDiscoveryUtilities::normalizeG2Uri($modinfo['Gallery2']['path']),
		'activeUserId'		=> $uid,
		'activeLanguage'	=> $language_settings[$LANGUAGE]['lang_short_cut'],
		'apiVersion'		=> array(1, 1)
	));
	if($ret)
	{
		/* Error! */
		/* Did we get an error because the user doesn't exist in g2 yet? */
		$ret2 = GalleryEmbed::isExternalIdMapped($uid, 'GalleryUser');
		if($ret2 && $ret2->getErrorCode() & ERROR_MISSING_OBJECT)
		{
			/* The user does not exist in G2 yet. Create in now on-the-fly */
			$ret = GalleryEmbed::createUser($uid, array(
				'username'			=> $uid,
				'email'				=> get_user_setting($uid, 'email'),
				'fullname'			=> getUserFullName($uid),
				'language'			=> $language_settings[get_user_settings($uid, 'language')]['lang_short_cut'],
				'hashedpassword'		=> get_user_password($uid),
				'hashmethod'			=> 'crypt',
				'creationtimestamp'		=> get_user_setting($uid, 'reg_timestamp')
			));
			if($ret)
			{
				/* An error during user creation. Not good, print an error or do whatever is appropriate
				 * in your emApp when an error occurs
				 */
				list($ret3, $g2u) = GalleryCoreApi::fetchUserByUserName($user['username']);
				if($ret3)
				{
					print "{$pgv_lang['mod_gallery2_error_user_create']}<br />\n".$ret->getAsHtml();
					exit;
				}
				else
				{
					/* Add missing External ID Map entry and continue.
					 */
					$ret4 = GalleryCoreApi::addMapEntry('ExternalIdMap', array(
						'externalId' => $uid,
						'entityType' => 'GalleryUser',
						'entityId' => $g2u->getId()
					));
				}
			}
		}
		else
		{
			/* The error we got wasn't due to a missing user, it was a real error */
			if($ret2)
			{
				print "{$pgv_lang['mod_gallery2_error_user_check']}<br />\n".$ret2->getAsHtml();
			}
			print "{$pgv_lang['mod_gallery2_error_init']}<br />\n".$ret->getAsHtml();
			exit;
		}
	}
	//GalleryCapabilities::set('showSidebarBlocks', false);

	// if admin, we need to add them to the admin group if not already added
	if(userIsAdmin($uid) == 'Y')
	{
		list($ret, $user) = GalleryCoreApi::loadEntityByExternalId($uid, 'GalleryUser');
		if($ret){print $ret->wrap(__FILE__, __LINE__);exit;}
		list($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
		if($ret){print $ret->wrap(__FILE__, __LINE__);exit;}
		list($ret, $group) = $module->getParameter('id.adminGroup');
		if($ret){print $ret->wrap(__FILE__, __LINE__);exit;}
		/* First check if the user is not already a member of the group */
		list($ret, $membership) = GalleryCoreApi::fetchGroupsForUser($user->getId());
		if($ret){print $ret->wrap(__FILE__, __LINE__);exit;}
		/* Only add user to group if not already done so */
		if(!isset($membership[$group]))
		{
			$ret = GalleryCoreApi::addUserToGroup($user->getId(), $group);
			if($ret){print $ret->wrap(__FILE__, __LINE__);exit;}
		}
	}
}
