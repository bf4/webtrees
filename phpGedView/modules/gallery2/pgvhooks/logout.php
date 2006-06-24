<?php
$modinfo = parse_ini_file('modules/gallery2.php', true);
if(!isset($modinfo['Gallery2']['path'])){$modinfo['Gallery2']['path'] = 'modules/gallery2';}
if(file_exists("{$modinfo['Gallery2']['path']}/embed.php")){include_once "{$modinfo['Gallery2']['path']}/embed.php";}

class mod_gallery2_logout
{
	function hook($params)
	{
		if(!class_exists('GalleryEmbed')){return null;}
		$g2ret = GalleryEmbed::logout();
		return null;
	}
}
?>