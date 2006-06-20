<?php
if(file_exists('modules/gallery2/embed.php')){require_once 'modules/gallery2/embed.php';}

class mod_gallery2_deleteuser
{
	function hook($params)
	{
		if(!file_exists('modules/gallery2/embed.php')){return null;}
		global $language_settings, $SERVER_URL;

		$ret = GalleryEmbed::init(array(
			'embedUri'		=> 'index.php?mod=gallery2',
			'g2Uri'			=> "{$SERVER_URL}modules/gallery2/",
			'loginRedirect'		=> "{$SERVER_URL}login.php",
			'apiVersion'		=> array(1, 1)
		));

		require_once('modules/gallery2/modules/core/classes/GalleryCoreApi.class');
		require_once('modules/gallery2/modules/core/classes/GallerySession.class');
		$ret = GalleryEmbed::deleteUser($params['username']);
		return null;
	}
}
?>