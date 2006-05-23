<?php
// $Id: index.php,v 1.2 2005/04/16 10:42:38 pkellum Exp $
include_once 'modules/gallery2/embed.php';
mod_gallery2_init();
function mod_gallery2_init()
{
	$g2uid = getUserName();
	$g2ret = GalleryEmbed::init(array (
		'embedUri' => 'module.php?mod=gallery2',
		'embedPath' => '/',
		'relativeG2Path' => 'modules/gallery2',
		'loginRedirect' => '/login.php',
		'activeUserId' => $g2uid
	));
	if ($g2ret->isError())
	{
		if ($g2ret->getErrorCode() & ERROR_MISSING_OBJECT)
		{
			$g2ret = GalleryEmbed::isExternalIdMapped($g2uid, 'GalleryUser');
			if ($g2ret->isError() && ($g2ret->getErrorCode() & ERROR_MISSING_OBJECT))
			{
				$g2user = getUser($g2uid);
				if (isset ($lang_short_cut[$g2user['language']]))
				{
					$g2lang = $lang_short_cut[$g2user['language']];
				}
				else
				{
					$g2lang = 'en';
				}
				$g2args = array (
					'username' => $g2user['username'],
					'email' => $g2user['email'],
					'fullname' => $g2user['fullname'],
					'language' => $g2lang,
					'hashedpassword' => $g2user['password'],
					'hashmethod' => 'crypt',
					'creationtimestamp' => $g2user['reg_timestamp']
				);
				$g2create = GalleryEmbed :: createUser($g2uid, $g2args);
				if (!$g2create->isSuccess())
				{
					print 'Failed to create G2 user with extId ['.$g2uid.']. Here is the error message from G2: <br />'.$g2create->getAsHtml();
					return false;
				}
				$g2ret = GalleryEmbed::login($g2uid);
				if ($g2ret->isError())
				{
					print $g2ret->getAsHtml();
					exit;
				}
			}
			else
			{
				print $g2ret->getAsHtml();
				exit;
			}

		}
		else
		{
			print $g2ret->getAsHtml();
			exit;
		}


		print $g2ret->getAsHtml();
		exit;
	}

	if (userIsAdmin($g2uid) == 'Y')
	{
		$g2ret = mod_gallery2_addUserToAdminGroup($g2uid);
		if ($g2ret->isError())
		{
			print $g2ret->getAsHtml();
			exit;
		}
	}

	$g2data = GalleryEmbed::handleRequest();
	if ($g2data['isDone'])
	{
		exit;
	}
	else
	{
		print_header($GLOBALS['modinfo']['Module']['title'], $g2data['headHtml']);
		print $g2data['bodyHtml'];
		print_footer();
	}
	return true;
}

function mod_gallery2_addUserToAdminGroup($uid)
{
	// get G2 user id
	list ($ret, $user) = GalleryCoreApi::loadEntityByExternalId($uid, 'GalleryUser');
	if ($ret->isError())
	{
		return $ret->wrap(__FILE__, __LINE__);
	}
	$userID = $user->getId();
	// get G2 admin group id
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret->isError()) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $adminGroupId) = $module->getParameter('id.adminGroup');
	if ($ret->isError())
	{
		return $ret->wrap(__FILE__, __LINE__);
	}
	// First check if the user is not already a member of the group
	list ($ret, $membership) = GalleryCoreApi::fetchGroupsForUser($userID);
	if ($ret->isError())
	{
		return $ret->wrap(__FILE__, __LINE__);
	}
	// Only add user to group if not already done so
	if (!isset ($membership[$adminGroupId]))
	{
		$ret = GalleryCoreApi::addUserToGroup($userID, $adminGroupId);
		if ($ret->isError())
		{
			return $ret->wrap(__FILE__, __LINE__);
		}
	}
	return GalleryStatus::success();
}
?>