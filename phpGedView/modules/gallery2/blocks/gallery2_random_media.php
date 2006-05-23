<?php
/**
 * Gallery 2 Random Media Block
 *
 * This block will randomly choose media items and show them in a block
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003  John Finlay and Others
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * $Id: gallery2_random_media.php,v 1.1 2005/04/16 10:41:07 pkellum Exp $
 * @package PhpGedView
 * @subpackage Blocks
 */

$PGV_BLOCKS['print_g2_random_media']['name']		= 'Random Media 2';
$PGV_BLOCKS['print_g2_random_media']['descr']		= 'random_media_descr';
$PGV_BLOCKS['print_g2_random_media']['canconfig']	= false;

require_once 'modules/gallery2/embed.php';
$g2ret = GalleryEmbed::init(array (
	'embedUri' => 'module.php?mod=gallery2',
	'embedPath' => '/',
	'relativeG2Path' => 'modules/gallery2',
	'loginRedirect' => '/login.php'
));

//-- function to display a random picture from the gedcom
function print_g2_random_media($block = true, $config='', $side, $index) {
	global $pgv_lang, $GEDCOM, $medialist, $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $MEDIA_EXTERNAL, $MEDIA_DIRECTORY, $SHOW_SOURCES;

	$params = array (
		'blocks' => 'randomImage',
		'show' => 'title|date|views'
	);
	$g2data = GalleryEmbed::getImageBlock($params);
	$search = array (
		'gbBlock',
		'giThumbnail',
		'giDescription',
		'giInfo'
	);
	$replace = array (
		'',
		'',
		'details2',
		'details2'
	);
	$img = str_replace($search, $replace, $g2data[1]);
			print "<div id=\"random_picture\" class=\"block\">";
			print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
			print "<td class=\"blockh1\" >&nbsp;</td>";
			print "<td class=\"blockh2\" ><div class=\"blockhc\">";
			print "<b>".$pgv_lang["random_picture"]."</b>";
			print_help_link("index_media_help", "qm");
			print "</div></td>";
			print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
			print "</table>";
			print "<div class=\"blockcontent\" >";
//			if ($block) print "<div class=\"small_inner_block\">\n";
			print "<table id=\"random_picture_box\" width=\"95%\"><tr><td valign=\"top\"";

			if ($block) print " align=\"center\" class=\"details1\"";
			else print " class=\"details2\"";
			print " >";
			print $img;
			print "</td></tr></table>\n";
//			if ($block) print "</div>\n";
			print "</div>"; // blockcontent
			print "</div>"; // block
}
?>