<?php
if(file_exists('modules/gallery2/embed.php')){require_once 'modules/gallery2/embed.php';}

class mod_gallery2_logout
{
	function hook($params)
	{
		if(!file_exists('modules/gallery2/embed.php')){return null;}
		$g2ret = GalleryEmbed::logout();
	}
}
?>