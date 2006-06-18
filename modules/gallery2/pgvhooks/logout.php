<?php
require_once 'modules/gallery2/embed.php';
class mod_gallery2_logout
{
	function hook($params)
	{
		$g2ret = GalleryEmbed::logout();
	}
}
?>