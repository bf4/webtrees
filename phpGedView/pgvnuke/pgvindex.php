<?php
/*=================================================
	Project: phpGedView
	File: pgvindex.php
	Author: Jim Carey
	
	Comments:
		Interfaces to phpGedView from postNuke and phpNuke. 
		
		If the user is logged in to PostNuke or phpNuke then they will be 
		logged in to phpGedView.
		If the user isn't defined in phpGedView then the user will be created.
		If the user is an admin in PostNuke or phpNuke   
		then they will be able to edit in phpGedView
		Auto admin creation is no longer supported - that should be specifically 
		granted by an admin in phpGedView once the userid is created
		You may need to tweak the user settings once created in phpGedView as not 
		all of the information can be inferred from PostNuke/phpNuke
		The post-config.php can set most of the defaults though		
		
		This module should be called via a modload from postnuke or phpnuke - eg:
		
		http://carey.id.au/modules.php?op=modload&name=phpGedView&file=pgvindex
		
		(see http://carey.id.au or
		http://carey.id.au/phpnuke )

		and click on Family Tree link on left to demonstrate)
		
		and should be stored (using that example) in /modules/phpGedView along with 
		post-config.php and postwrap.js
		
		postgedview.php should be stored in the directory that you have uploaded phpGedView code to
		
		
		I have adapted some code from PostWrap (the javascript for example) to allow 
		this to open in the main window under Post/phpNuke 
		(ie an iFRAME). There is, as in PostWrap, an option to then open 
		into a window on its own without the wrapping. 
		
		Note Note Note Note Note Note Note - Important Important
		you must have a working phpGedView in your phpGedView directory.
		That is you must have setup your initial admin user and uploaded your gedcom
		
		Have fun - see readme for more info 
		- no apologies for the poorness of the code :-)

	Change Log:
		used to interface to the fabulous phpGedView: Genealogy Viewer
		
		April 23 2004 	added ability to open in main PostNuke content area
						added compatibility with V3.0 phpGedView
		July 6 2004		added phpNuke and interface ability
						added compatibility with V3.1 phpGedView	
		August 10 2004  added ability to split interface code from phpGedView main code
								
   

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
===================================================*/
	define('_OPENDIRECTMSG','Open in new window');
	define('_SORRYBROWSER','Sorry, your browser does not understand iframes. Here is the ');
	define('_LINKYOU','link');
	define('_SORRYBROWSER1',' to the page.');
/*
	if (!eregi("modules.php", $SCRIPT_NAME)) 
	{
        	die ("You can't access this file directly...");
  	}
*/ 
	global $op;
	global $mop;
	global $user_email;
	global $user_prefix;

	/* Detect PHP-Nuke or PostNuke  and react accordingly */
	if (!strcmp($op, "modload") || !strcmp($mop, "modload")) 
	{
        	if (isset($GLOBALS['pnconfig']) && function_exists("authorised")) 
		{
			$nuke_type = "postnuke"; 
		} 
		else 
		{
	    		$nuke_type = "phpnuke"; 
		}
	}
		
	global $config;
	global $GEDBASEDIR;
	global $GED_MODULENAME;
	global $DBHOST, $DBUSER, $DBPASS, $DBNAME, $DBCONN, $DBSEL, $TOTAL_QUERIES;
	global $TBLPREFIX;
	include 'post-config.php';
	$GED_MODULENAME = $name;
	$GED_WRAP = "modules/$GED_MODULENAME";

	if 	($def_gedbasedir != "")
	{
		$GEDBASEDIR = $def_gedbasedir;
	}
	else
	{	
		$GEDBASEDIR = "modules/$GED_MODULENAME/";
	}
	if (!isset($config)) { include("config.php"); }
	$config["module"] = "gedview";
    $username = "";
	if ($nuke_type=="postnuke")
	{		
		//Parsing the path to find the root -- Ron
		if ($PATH_TRANSLATED != "")
		{
			eregi("([^/\\]*)$", $PATH_TRANSLATED, $strWebPageName);
			$DOCUMENT_ROOT = eregi_replace($GEDBASEDIR,"",eregi_replace($strWebPageName[0], "", $PATH_TRANSLATED));
		}
		if (pnUserLoggedIn()) 
		{
			$username = pnUserGetVar('uname');
 			$uid = pnSessionGetVar('uid');
            $email = pnUserGetVar('email');
            $firstname = pnUserGetVar('name');
            $lastname = pnUserGetVar('name');
			list($userperms, $groupperms) = pnSecGetAuthInfo();
			// set default canedit to no
			$canedit = "no";
			$num = count($groupperms);
				/*
				The following are the security levels for PostNuke
				So we are testing to see if any group membership at the 500 or greater
				level - if so then we set canedit so they can edit in phpGedView
					ACCESS_INVALID', -1
					ACCESS_NONE', 0
					ACCESS_OVERVIEW', 100
					ACCESS_READ', 200
					ACCESS_COMMENT', 300
					ACCESS_MODERATE', 400
					ACCESS_EDIT', 500
					ACCESS_ADD', 600
					ACCESS_DELETE', 700
					ACCESS_ADMIN', 800
				*/
			for ($ii = 0; $ii < $num; $ii++)
			{
				if ($groupperms[$ii][level] > 400)
				{
					$canedit = "yes";
				}
			}
		}
		else 
		{	
			// not logged 
			$username = "";
			$config["ssl"] = false;
			$_SESSION['pgv_user'] = "";
		}
	}
	// phpnuke
	if ($nuke_type=="phpnuke")
	{
		global $user, $cookie;

		cookiedecode($user);

		$username = $cookie[1];
		if (!isset($username))
		{
		    $username = "";
		}
		if (($username == "Anonymous") or ($username == ""))
		{
			// not logged 
			$username = "";
			$config["ssl"] = false;
			$_SESSION['pgv_user'] = "";
		}
		
		if(!is_array($user)) 
		{
			$user_get = base64_decode($user);
  			$user_get = explode(":", $user_get);
  			$username = "$user_get[1]";
		} 
		else 
		{
  			$username = "$user[1]";
		}
    	$sql2 = "SELECT * FROM ".$user_prefix."_users WHERE username='$username'";
		$result2 = $db->sql_query($sql2);
		$num = $db->sql_numrows($result2);
		$userinfo = $db->sql_fetchrow($result2);
        $email = $userinfo[user_email];
		$firstname = $userinfo[name];
		$lastname = $userinfo[name];
		if ($userinfo[user_level] == "2")
		{
			$canedit = "yes";
		}
		else
		{
			$canedit = "no";
		}
	}
	
		
	session_write_close();

	if (($username != "Anonymous") and ($username != ""))
	{	
		$vars=array();
		// set pgv_user so that they will be logged in in gedview
		if (isset($_COOKIE['post_user'])) unset($_COOKIE['post_user']);
		if (isset($_COOKIE['post_email'])) unset($_COOKIE['post_email']);
		if (isset($_COOKIE['post_firstname'])) unset($_COOKIE['post_firstname']);
		if (isset($_COOKIE['post_lastname'])) unset($_COOKIE['post_lastname']);
		if (isset($_COOKIE['post_canedit'])) unset($_COOKIE['post_canedit']);
		setcookie('post_user',$username,0,'/');
		setcookie('post_firstname',$firstname,0,'/');
		setcookie('post_lastname',$lastname,0,'/');
		setcookie('post_email',$email,0,'/');
		setcookie('post_canedit',$canedit,0,'/');
		//setup the  post_config variables as cookies so postgedview doesnt need to read them
		if (isset($_COOKIE['def_canadmin'])) unset($_COOKIE['def_canadmin']);
		setcookie('def_canadmin',$def_canadmin,0,'/');
		if (isset($_COOKIE['def_canedit'])) unset($_COOKIE['def_canedit']);
		setcookie('def_canedit',$def_canedit,0,'/');
		if (isset($_COOKIE['def_rootid'])) unset($_COOKIE['def_rootid']);
		setcookie('def_rootid',$def_rootid,0,'/');
		if (isset($_COOKIE['def_verified'])) unset($_COOKIE['def_verified']);
		setcookie('def_verified',$def_verified,0,'/');
		if (isset($_COOKIE['def_verified_by_admin'])) unset($_COOKIE['def_verified_by_admin']);
		setcookie('def_verified_by_admin',$def_verified_by_admin,0,'/');
		if (isset($_COOKIE['def_create_user'])) unset($_COOKIE['def_create_user']);
		setcookie('def_create_user',$def_create_user,0,'/');
		if (isset($_COOKIE['def_language'])) unset($_COOKIE['def_language']);
		setcookie('def_language',$def_language,0,'/');
		if (isset($_COOKIE['def_upass'])) unset($_COOKIE['def_upass']);
		setcookie('def_upass',$def_upass,0,'/');
		if (isset($_COOKIE['def_gedcom'])) unset($_COOKIE['def_gedcom']);
		setcookie('def_gedcom',$def_gedcom,0,'/');
		if (isset($_COOKIE['def_contact_method'])) unset($_COOKIE['def_contact_method']);
		setcookie('def_contact_method',$def_contact_method,0,'/');
		if (isset($_COOKIE['def_theme'])) unset($_COOKIE['def_theme']);
		setcookie('def_theme',$def_theme,0,'/');

		$next = $GEDBASEDIR."postgedview.php";
	}
	else
	{
		//I've changed this from $DOCUMENT_ROOT to $GEDBASEDIR and added logout --Ron
		if (($nuke_type == "postnuke") or ($nuke_type == "phpnuke"))
		{
			$next = $GEDBASEDIR."index.php?logout=1";
		}
		else
		{
			$next = "index.php?logout=1";
		}
	}
	$src = "$GED_WRAP/postwrap.js";
	$jscontent = "\n\n<!-- Begin PostWrap Auto Resize -->\n"
			."<script language=\"javascript\" type=\"text/javascript\" src=\"$src\">\n"
			."</script>\n"
			."<!-- End PostWrap Auto Resize -->\n\n";
		
	$vsize = 0;				
	$title = "<br /><center>[ <a href=\"$GEDBASEDIR/index.php\" target=\"_blank\">"._OPENDIRECTMSG."</a> ]</center>";
	$end_title = "<br />[ <a href=\"$GEDBASEDIR/index.php\" target=\"_blank\">"._OPENDIRECTMSG."</a> ]";

	if ($nuke_type == "postnuke")
	{		
		// Build and display title and content	
		$content = "\n\n<!-- Begin Modified PostWrap Content -->\n" 
		."<div class=\"pn-pagetitle\" align=\"center\">$title</div>\n"
		."<iframe id=\"PostWrap\" src=\"$next\" width=\"100%\" height=\"500\"  frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\">\n"
		."  <br />"._SORRYBROWSER."<a class=\"pn-pagetitle\" href=\"index.php\" target=\"_blank\">"._LINKYOU."</a>"._SORRYBROWSER1."<br /><br />\n"
		."</iframe>\n"
		."<div class=\"pn-pagetitle\" align=\"center\">$end_title</div>\n"
		."<!-- End PostWrap Content -->\n\n";
		include("$DOCUMENT_ROOT/header.php");
		echo $content;	
		include("$DOCUMENT_ROOT/footer.php");
		echo $jscontent;
	}
	if ($nuke_type == "phpnuke")
	{
		$jscontent="<script language=\"JavaScript\">\n"
		."\n\n<!--"
		."function resize_iframe()\n"
		."{\n"
		."  //resize the iframe according to the size of the window\n"
 		."document.getElementById(\"PostWrap\").height=document.body.offsetHeight+100;"
		."}\n"
		."window.onresize=resize_iframe; //this will resize the iframe every time you change the size of the window."
		."//-->\n"
		."</script>\n";
		
		// Build and display title and content	
		$content = "\n\n<!-- Begin Modified PostWrap Content -->\n" 
		."<div  align=\"center\">$title</div>\n"
		."<iframe id=\"PostWrap\" src=\"$next\" width=\"100%\" onload='resize_iframe()' scrolling=\"yes\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\">\n"
		."  <br />"._SORRYBROWSER."<a href=\"index.php\" target=\"_blank\">"._LINKYOU."</a>"._SORRYBROWSER1."<br /><br />\n"
		."</iframe>\n"
		."<div  align=\"center\">$end_title</div>\n"
		."<!-- End PostWrap Content -->\n\n";
		include ("header.php");	
		OpenTable();	
		echo $content;
		CloseTable();
		echo $jscontent;
		include("footer.php");		
	}
	
	// Finish up
	exit;

?>
