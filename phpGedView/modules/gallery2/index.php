<?php
// $Id: $
if(!file_exists(dirname(__FILE__).'/embed.php')){header('Location: index.php');print ' ';exit;}
include_once dirname(__FILE__).'/embed.php';
include_once dirname(__FILE__).'/pgv.php';

mod_gallery2_run();

function mod_gallery2_run()
{
	// load user and create a g2 user if required
	mod_gallery2_load(getUserName());

	$data = GalleryEmbed::handleRequest();
	if($data['isDone']){exit;}
	$head = GalleryEmbed::parseHead($data['headHtml']);
	print_header($head[0], join("\n", $head[1])."\n".join("\n", $head[2]));
	print $data['bodyHtml'];
	print_footer();
	return true;
}
?>