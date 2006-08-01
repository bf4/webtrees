<?php
 /**
 * Checks to see if various thing are writable when the editconfig.php file is called.
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
 * @package PhpGedView
 * @subpackage Admin
 * @see editconfig.php
 */
 
 require_once('config.php');

 if (!file_is_writeable("config.php"))
{
	if (!@chmod("config.php", 0777))
	{
		print_header("");
		echo "<center><span class=\"error\">You need to change the security settings in your config.php file so that it is writable.</span></center>";
		//print_text("not_writable");
		print_footer();
		exit;
	}
}

if (is_writable($INDEX_DIRECTORY))
{
	if (!@chmod($INDEX_DIRECTORY, 0777))
	{
		print_header("");
		echo "<center><span class=\"error\">You need to change the security settings in your index directory so that it is writable.</span></center>";
		//print_text("not_writable");
		print_footer();
		exit;
	}
}

if (is_writable($MEDIA_DIRECTORY))
{
	if (!@chmod($MEDIA_DIRECTORY, 0777))
	{
		print_header("");
		echo "<center><span class=\"error\">You need to change the security settings in your media directory so that it is writable.</span></center>";
		//print_text("not_writable");
		print_footer();
		exit;
	}
}

if (is_writable($MEDIA_DIRECTORY."/thumbs"))
{
	if (!@chmod($MEDIA_DIRECTORY."/thumbs", 0777))
	{
		print_header("");
		echo "<center><span class=\"error\">You need to change the security settings in your media thumbs directory so that it is writable.</span></center>";
		//print_text("not_writable");
		print_footer();		
	}
}

if (is_writable($MEDIA_DIRECTORY))
{
	if (!@chmod($THEMES_DIRECTORY, 0777))
	{
		print_header("");
		echo "<center><span class=\"error\">You need to change the security settings in your themes directory so that it is writable.</span></center>";
		//print_text("not_writable");
		print_footer();
		exit;
	}
}
?>
