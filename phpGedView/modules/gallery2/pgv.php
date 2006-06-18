<?php
// $Id: $
/*
 * Seperated out of G2 embeding for use elseware.
 */
require_once 'modules/gallery2/language/mod_en.php';
if(file_exists("modules/gallery2/language/mod_{$language_settings[$LANGUAGE]['lang_short_cut']}.php")){require_once "modules/gallery2/language/mod_{$language_settings[$LANGUAGE]['lang_short_cut']}.php";}

function mod_gallery2_load($uid)
{
	global $SERVER_URL, $language_settings, $LANGUAGE;
	$ret = GalleryEmbed::init(array(
		'embedUri'		=> 'index.php?mod=gallery2',
		'g2Uri'			=> "{$SERVER_URL}modules/gallery2/",
		'loginRedirect'		=> "{$SERVER_URL}login.php",
		'activeUserId'		=> $uid,
		'activeLanguage'	=> $language_settings[$LANGUAGE]['lang_short_cut'],
		'apiVersion'		=> array(1, 1)
	));
	if($ret)
	{
		/* Error! */
		/* Did we get an error because the user doesn't exist in g2 yet? */
		$user = getUser($uid);
		$ret2 = GalleryEmbed::isExternalIdMapped($uid, 'GalleryUser');
		if($ret2 && $ret2->getErrorCode() & ERROR_MISSING_OBJECT)
		{
			/* The user does not exist in G2 yet. Create in now on-the-fly */
			$ret = GalleryEmbed::createUser($uid, array(
				'username'		=> $user['username'],
				'email'			=> $user['email'],
				'fullname'		=> "{$user['firstname']} {$user['lastname']}",
				'language'		=> $language_settings[$user['language']]['lang_short_cut'],
				'hashedpassword'	=> $user['password'],
				'hashmethod'		=> 'crypt',
				'creationtimestamp'	=> $user['reg_timestamp']
			));
			if($ret)
			{
				/* An error during user creation. Not good, print an error or do whatever is appropriate
				 * in your emApp when an error occurs
				 */
				print "{$pgv_lang['mod_gallery2_error_user_create']}<br />\n".$ret->getAsHtml();
				exit;
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
?>