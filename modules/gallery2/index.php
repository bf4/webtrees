<?php
// $Id: $

if(!defined('PGV_GALLERY2_INIT'))
{
	include_once('modules/gallery2/pgv.php');
	if(PGV_GALLERY2_INIT === false){header('Location: index.php'); print ' '; exit;}
}

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