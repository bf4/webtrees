<?php
/**
* Checks to see if the version of php you are using is newer then 4.3.
* Checks to see if the config.php, the index directory, the media directory, the media thumbs directory,
* and the media/themes directory are writable.
* Checks to see if the imagecreatefromjpeg, xml_parser_create, and GregorianToJD functions exist. 
* Checks to see if the DomDocument class exists.
* Checks to see if the database is configured correctly.
* Checks to see if the "config.php", "includes", "includes/session.php", "includes/functions.php",
"includes/functions_db.php", "themes/", "includes/lang_settings_std.php", "includes/functions_db.php",
"includes/authentication.php", "includes/functions_name.php", "includes/functions_print.php",
"includes/functions_rtl.php", "includes/functions_mediadb.php", "includes/functions_date.php", 
"includes/templecodes.php", "includes/functions_privacy.php", "includes/menu.php", "config_gedcom.php",
"privacy.php", and "hitcount.php" files exist.
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
if ((double) phpversion() < 4.3) 
{
	$errors[] = "<span class=\"error\">You need to have PHP version 4.3 or higher.</span>";
}

//-- define function
if (!function_exists('file_is_writable')) {
	function file_is_writable($file) {
		$err_write = false;
		$handle = @fopen($file,"r+");
		if	($handle)	{
			$i = fclose($handle);
			$err_write = true;
		}
		return($err_write);
	}
}

$arr = array("config.php", "includes", "includes/session.php", "includes/functions.php",
			 "includes/functions_db.php", "themes/", "includes/lang_settings_std.php", 
			 "includes/functions_db.php", "includes/authentication.php", "includes/functions_name.php", 
			 "includes/functions_print.php", "includes/functions_rtl.php", "includes/functions_mediadb.php", 
             "includes/functions_date.php", "includes/templecodes.php", "includes/functions_privacy.php",
             "includes/menu.php", "config_gedcom.php", "privacy.php", "hitcount.php");
foreach($arr as $k => $v)
{
	if (!file_exists($v))
	{
		$errors[] = "<span class=\"error\">The file \"$v\" does not exist. You might want to check and make sure that the file exists, was not missnamed, and read permissions are set correctly.</span>";
	}
}
if (count($errors)>0) print_sanity_errors();
@require_once("config.php");

if (!file_is_writable("config.php")) 
{
	//if (!@ chmod("config.php", 0777)) 
	//{
		$errors[] = "<span class=\"error\">You need to change the security settings in your config.php file so that it is writable.</span>";
	//}
}

if (!is_writable($INDEX_DIRECTORY)) 
{
	//if (!@ chmod($INDEX_DIRECTORY, 0777)) 
	//{
		$errors[] = "<span class=\"error\">You need to change the security settings in your index directory so that it is writable.</span>";
	//}
}

if (!is_writable($MEDIA_DIRECTORY)) 
{
	//if (!@ chmod($MEDIA_DIRECTORY, 0777)) 
	//{
		$warnings[] = "Your media directory is not writable.  You will not be able to upload media files or generate thumbnails in PhpGedView unless this directory is writable.";
	//}
}
if (!is_writable($MEDIA_DIRECTORY . "thumbs")) 
{
	//if (!@ chmod($MEDIA_DIRECTORY . "thumbs", 0777)) 
	//{
		$warnings[] = "Your thumbs directory is not writable.  You will not be able to upload thumbnails or generate thumbnails in PhpGedView unless this directory is writable.";
	//}
}

if (!function_exists('imagecreatefromjpeg')) 
{
	$warnings[] = "The GD imaging library does not exist. PhpGedView will still function, but some of the features, such as thumbnail generation and the circle diagram will not work without the GD library.  You can go to <a href=\"http://www.php.net/manual/en/ref.image.php\">http://www.php.net/manual/en/ref.image.php</a> for more information.";
}

if (!function_exists('xml_parser_create')) 
{
	$warnings[] = "The XML Parser library does not exist. PhpGedView will still function, but some of the features, such as report generation and web services will not work without the xml parser library. You can go to <a href=\"http://us3.php.net/manual/en/ref.xml.php\">http://us3.php.net/manual/en/ref.xml.php</a> for more information.";
}

if (!class_exists('DomDocument')) 
{
	$warnings[] = "The DOM XML library does not exist. PhpGedView will still function, but some of the features, such as Gramps Export features in the clippings cart, download, and web services will not work. You can go to <a href=\"http://us2.php.net/manual/en/ref.dom.php\">http://us2.php.net/manual/en/ref.dom.php</a> for more information.";
	
}

if (!function_exists('GregorianToJD')) 
{
	$warnings[] = "The Calendar library does not exist. PhpGedView will still function, but some of the features, such as conversion to other calendars such as Hebrew or French will not work.  It is not essential for running PhpGedView. You can go to <a href=\"http://us2.php.net/manual/en/ref.calendar.php\">http://us2.php.net/manual/en/ref.calendar.php</a> for more information.";
}

if (($CONFIGURED || (isset($_REQUEST['action']) && $_REQUEST['action']=="update")) && !check_db(true)) 
{
	require_once $confighelpfile["english"];
	if (file_exists($confighelpfile[$LANGUAGE]))
		require_once $confighelpfile[$LANGUAGE];
		$error = "";
	$error = "<span class=\"error\">";
	$error .= $pgv_lang["db_setup_bad"];
	$error .= "</span><br />";
	$error .= "<span class=\"error\">" . $DBCONN->getMessage() . " " . $DBCONN->getUserInfo() . "</span><br />";
	
	if ($CONFIGURED == true) 
	{
		//-- force the incoming user to enter the database password before they can configure the site for security.
		if (!isset ($_POST["security_check"]) || !isset ($_POST["security_user"]) || (($_POST["security_check"] != $DBPASS) && ($_POST["security_user"] == $DBUSER))) 
		{
			$error .= "<br /><br />";
			$error .= print_text("enter_db_pass", 0, 1);
			$error .= "<br /><form method=\"post\" action=\"editconfig.php\"> ";
			$error .= $pgv_lang["DBUSER"];
			$error .= " <input type=\"text\" name=\"security_user\" /><br />\n";
			$error .= $pgv_lang["DBPASS"];
			$error .= " <input type=\"password\" name=\"security_check\" /><br />\n";
			$error .= "<input type=\"submit\" value=\"";
			$error .= $pgv_lang["login"];
			$error .= "\" />\n";
			$error .= "</form>\n";
			$errors[] = $error;
		}
		else $warnings[] = $error;
	}
	else $warnings[] = $error;
}

function print_sanity_errors() {
	global $warnings, $errors;
	if (preg_match("/\Wsanity_check.php/", $_SERVER["SCRIPT_NAME"])>0)
	{
		//Prints warnings
		if (count($warnings)>0)
		{
			print "<center><span style=\"color: green; font-weight: bold;\">Warnings: </span></center>";
			foreach($warnings as $warning) 
			{
				print "<center><span style=\"color: blue; font-weight: bold;\">".$warning."</span></center>";
			}
		}
		//Prints errors
		if (count($errors)>0)
		{
			print "<center><span style=\"color: green; font-weight: bold;\">Errors: </span></center>";
			foreach($errors as $error) 
			{
				print "<center><span style=\"color: red; font-weight: bold;\">".$error."</span></center>";
			}
			exit;
		}
		if (count($warnings) == 0 && count($errors) == 0)
		{
			print "Congratulations, you have no warnings or errors.";
		}
	}
}
print_sanity_errors();
?>
