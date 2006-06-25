<?php
$modinfo = parse_ini_file('modules/gallery2.php', true);
if(!isset($modinfo['Gallery2']['path'])){$modinfo['Gallery2']['path'] = 'modules/gallery2';}
if(file_exists("{$modinfo['Gallery2']['path']}/embed.php"))
{
	include_once "{$modinfo['Gallery2']['path']}/embed.php";
	require_once 'modules/gallery2/G2EmbedDiscoveryUtilities.class';
	require_once("{$modinfo['Gallery2']['path']}/modules/core/classes/GalleryCoreApi.class");
	require_once("{$modinfo['Gallery2']['path']}/modules/core/classes/GallerySession.class");
}

class mod_gallery2_deleteuser
{
	function hook($params)
	{
		global $language_settings, $SERVER_URL, $modinfo;

		if(!class_exists('GalleryEmbed')){return null;}

		if($SERVER_URL[strlen($SERVER_URL) - 1] == '/'){$sep = '';}else{$sep = '/';}

		$ret = GalleryEmbed::init(array(
			'embedUri'			=> 'index.php?mod=gallery2',
			'g2Uri'				=> G2EmbedDiscoveryUtilities::normalizeG2Uri($modinfo['Gallery2']['path']),
			'loginRedirect'		=> "{$SERVER_URL}{$sep}login.php",
			'apiVersion'		=> array(1, 1)
		));

		$ret = GalleryEmbed::deleteUser($params['username']);
		return null;
	}
}
?>