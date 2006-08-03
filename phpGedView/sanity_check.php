<?php
/**
* Checks to see if the version of php you are using is newer then 4.3.
* Checks to see if the config.php, the index directory, the media directory, the media thumbs directory, and the media/themes directory are writable.
* Checks to see if the imagecreatefromjpeg, xml_parser_create, and GregorianToJD functions exist. 
* Checks to see if the DomDocument class exists.
* Checks to see if the database is configured correctly.
* All of these things are checked when the editconfig.php file is first loaded. 
* If any of the checks fail the appropriate error or warning message will be displayed.
* 
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

$errors = array();
$warnings = array();
if ((double) phpversion() <= 4.3) 
{
	$errors[] = "<center><span class=\"error\">You need to have PHP version 4.3 or higher.</span></center>";
}

//-- define function
if (!function_exists('file_is_writeable')) {
	function file_is_writeable($file) {
		$err_write = false;
		$handle = @fopen($file,"r+");
		if	($handle)	{
			$i = fclose($handle);
			$err_write = true;
		}
		return($err_write);
	}
}

if (!file_is_writeable("config.php")) 
{
	if (!@ chmod("config.php", 0777)) 
	{
		$errors[] = "<center><span class=\"error\">You need to change the security settings in your config.php file so that it is writable.</span></center>";
	}
}

if (!is_writable($INDEX_DIRECTORY)) 
{
	if (!@ chmod($INDEX_DIRECTORY, 0777)) 
	{
		$errors[] = "<center><span class=\"error\">You need to change the security settings in your index directory so that it is writable.</span></center>";
	}
}

if (!is_writable($MEDIA_DIRECTORY)) 
{
	if (!@ chmod($MEDIA_DIRECTORY, 0777)) 
	{
		$errors[] = "<center><span class=\"error\">You need to change the security settings in your media directory so that it is writable.</span></center>";
	}
}

if (!is_writable($MEDIA_DIRECTORY . "/thumbs")) 
{
	if (!@ chmod($MEDIA_DIRECTORY . "/thumbs", 0777)) 
	{
		$warnings[] = "<center><span class=\"error\">You need to change the security settings in your media thumbs directory so that it is writable.</span></center>";
	}
}

if (!is_writable($MEDIA_DIRECTORY)) 
{
	if (!@ chmod($THEMES_DIRECTORY, 0777)) 
	{
		$errors[] = "<center><span class=\"error\">You need to change the security settings in your themes directory so that it is writable.</span></center>";
	}
}

if (!function_exists('imagecreatefromjpeg')) 
{
	$warnings[] = "<center><span class=\"error\">The function imagecreatefromjpeg does not exist. Go to <a href=\"http://www.php.net/manual/en/function.imagecreatefromjpeg.php\">http://www.php.net/manual/en/function.imagecreatefromjpeg.php</a> for more information.</span></center>";
}

if (!function_exists('xml_parser_create')) 
{
	$warnings[] = "<center><span class=\"error\">The function xml_parser_create does not exist. Go to <a href=\"http://us3.php.net/manual/en/function.xml-parser-create.php\">http://us3.php.net/manual/en/function.xml-parser-create.php</a> for more information.</span></center>";
}

if (!class_exists('DomDocument')) 
{
	$warnings[] = "<center><span class=\"error\">The class DomDocument does not exist.</span></center>";
}

if (!function_exists('GregorianToJD')) 
{
	$warnings[] = "<center><span class=\"error\">The function GregorianToJD does not exist. Go to <a href=\"http://us3.php.net/manual/en/function.gregoriantojd.php\">http://us3.php.net/manual/en/function.gregoriantojd.php</a> for more information.</span></center>";
}


if (($CONFIGURED || $action == "update") && !check_db(true)) 
{
	require_once ('config.php');
	require_once $confighelpfile["english"];
	if (file_exists($confighelpfile[$LANGUAGE]))
		require_once $confighelpfile[$LANGUAGE];
	print_header("");
	print "<span class=\"error\">";
	print $pgv_lang["db_setup_bad"];
	print "</span><br />";
	print "<span class=\"error\">" . $DBCONN->getMessage() . " " . $DBCONN->getUserInfo() . "</span><br />";
	if ($CONFIGURED == true) 
	{
		//-- force the incoming user to enter the database password before they can configure the site for security.
		if (!isset ($_POST["security_check"]) || !isset ($_POST["security_user"]) || (($_POST["security_check"] != $DBPASS) && ($_POST["security_user"] == $DBUSER))) 
		{
			print "<br /><br />";
			print_text("enter_db_pass");
			print "<br /><form method=\"post\" action=\"editconfig.php\"> ";
			print $pgv_lang["DBUSER"];
			print "<input type=\"text\" name=\"security_user\" /><br />\n";
			print $pgv_lang["DBPASS"];
			print " <input type=\"password\" name=\"security_check\" /><br />\n";
			print "<input type=\"submit\" value=\"";
			print $pgv_lang["login"];
			print "\" />\n";
			print "</form>\n";
			print_footer();
			exit;
		}
	}
}
?>
