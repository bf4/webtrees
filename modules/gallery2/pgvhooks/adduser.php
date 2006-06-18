<?php
require_once 'modules/gallery2/embed.php';

class mod_gallery2_adduser
{
	function hook($user)
	{
		global $language_settings, $SERVER_URL;

		$ret = GalleryEmbed::init(array(
			'embedUri'		=> 'index.php?mod=gallery2',
			'g2Uri'			=> "{$SERVER_URL}modules/gallery2/",
			'loginRedirect'		=> "{$SERVER_URL}login.php",
			'apiVersion'		=> array(1, 1)
		));

		require_once('modules/gallery2/modules/core/classes/GalleryCoreApi.class');
		require_once('modules/gallery2/modules/core/classes/GallerySession.class');
		$ret = GalleryEmbed::createUser($user['username'], array(
			'username'			=> $user['username'],
			'email'				=> $user['email'],
			'fullname'			=> "{$user['firstname']} {$user['lastname']}",
			'language'			=> $language_settings[$user['language']]['lang_short_cut'],
			'hashedpassword'	=> $user['password'],
			'hashmethod'		=> 'crypt',
			'creationtimestamp'	=> $user['reg_timestamp']
		));
		return null;
	}
}
?>