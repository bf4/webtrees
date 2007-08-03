<?php
/**
 * MySQL User and Authentication functions
 *
 * This file contains the MySQL specific functions for working with users and authenticating them.
 * It also handles the internal mail messages, favorites, news/journal, and storage of MyGedView
 * customizations.  Assumes that a database connection has already been established.
 *
 * You can extend PhpGedView to work with other systems by implementing the functions in this file.
 * Other possible options are to use LDAP for authentication.
 *
 * $Id$
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007	John Finlay and Others
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
 * @package PhpGedView
 * @subpackage DB
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

/**
 * authenticate a username and password
 *
 * This function takes the given <var>$username</var> and <var>$password</var> and authenticates
 * them against the database.  The passwords are encrypted using the crypt() function.
 * The username is stored in the <var>$_SESSION["pgv_user"]</var> session variable.
 * @param string $username the username for the user attempting to login
 * @param string $password the plain text password to test
 * @param boolean $basic true if the userName and password were retrived via Basic HTTP authentication. Defaults to false. At this point, this is only used for logging
 * @return bool return true if the username and password credentials match a user in the database return false if they don't
 */
function authenticateUser($username, $password, $basic=false) {
	global $TBLPREFIX, $GEDCOM, $pgv_lang;
	checkTableExists();
	$user = getUser($username);
	//-- make sure that we have the actual username as it was stored in the DB
	if ($user!==false) {
		$username = $user['username'];
		if (crypt($password, $user["password"])==$user["password"]) {
	        if (!isset($user["verified"])) $user["verified"] = "";
	        if (!isset($user["verified_by_admin"])) $user["verified_by_admin"] = "";
	        if ((($user["verified"] == "yes") and ($user["verified_by_admin"] == "yes")) or ($user["canadmin"] != "")){
		        $sql = "UPDATE ".$TBLPREFIX."users SET u_loggedin='Y', u_sessiontime='".time()."' WHERE u_username='$username'";
		        $res = dbquery($sql,false);

				AddToLog(($basic ? "Basic HTTP Authentication" :"Login"). " Successful ->" . $username ."<-");
				//-- reset the user's session
				$_SESSION = array();
				$_SESSION['pgv_user'] = $username;
				//-- unset the cookie_login session var to show that they have logged in with their password
				$_SESSION['cookie_login'] = false;
				if (isset($pgv_lang[$user["language"]])) $_SESSION['CLANGUAGE'] = $user['language'];
				//-- only change the gedcom if the user does not have an gedcom id
				//-- for the currently active gedcom
				if (empty($user["gedcomid"][$GEDCOM])) {
					//-- if the user is not in the currently active gedcom then switch them
					//-- to the first gedcom for which they have an ID
					foreach($user["gedcomid"] as $ged=>$id) {
						if (!empty($id)) {
							$_SESSION['GEDCOM']=$ged;
							break;
						}
					}
				}
				// pass entire user data insted of just username since we have it already.
				runHooks('login', array('user'=>$user));
				return true;
			}
		}
	}
	AddToLog(($basic ? "Basic HTTP Authentication" : "Login") . " Failed ->" . $username ."<-");
	return false;
}

/**
 * authenticate a username and password using Basic HTTP Authentication
 *
 * This function uses authenticateUser(), for authentication, but retrives the userName and password provided via basic auth.
 * @return bool return true if the user is already logged in or the basic HTTP auth username and password credentials match a user in the database return false if they don't
 * @TODO Security audit for this functionality
 * @TODO Do we really need a return value here?
 * @TODO should we reauthenticate the user even if already logged in?
 * @TODO do we need to set the user language and other jobs done in login.php? Should that loading be moved to a function called from the authenticateUser function?
 */
function basicHTTPAuthenticateUser() {
	global $pgv_lang;
	$username = getUserName();
	if(empty($username)){ //not logged in.
		if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])
				|| (! authenticateUser($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], true))) {
			header('WWW-Authenticate: Basic realm="' . $pgv_lang["basic_realm"] . '"');
			header('HTTP/1.0 401 Unauthorized');
			echo $pgv_lang["basic_auth_failure"] ;
			exit;
		}
	} else { //already logged in or successful basic authentication
		return true; //probably not needed
	}
}

/**
 * logs a user out of the system
 * @param string $username	optional parameter to logout a specific user
 */
function userLogout($username = "") {
	global $TBLPREFIX, $GEDCOM, $LANGUAGE;

	if ($username=="") {
		if (isset($_SESSION["pgv_user"])) $username = $_SESSION["pgv_user"];
		else if (isset($_COOKIE["pgv_rem"])) $username = $_COOKIE["pgv_rem"];
		else return;
	}
	$sql = "UPDATE ".$TBLPREFIX."users SET u_loggedin='N' WHERE u_username='".$username."'";
	$res = dbquery($sql,false);

	AddToLog("Logout - " . $username);

	if ((isset($_SESSION['pgv_user']) && ($_SESSION['pgv_user']==$username)) || (isset($_COOKIE['pgv_rem'])&&$_COOKIE['pgv_rem']==$username)) {
		if ($_SESSION['pgv_user']==$username) {
			$_SESSION['pgv_user'] = "";
			unset($_SESSION['pgv_user']);
			if (isset($_SESSION["pgv_counter"])) $tmphits = $_SESSION["pgv_counter"];
			else $tmphits = -1;
			@session_destroy();
			$_SESSION["gedcom"]=$GEDCOM;
			$_SESSION["show_context_help"]="yes";
			@setcookie("pgv_rem", "", -1000);
			if($tmphits>=0) $_SESSION["pgv_counter"]=$tmphits; //set since it was set before so don't get double hits
		}
	}
	runHooks('logout', array('username'=>$username));
}

/**
 * Updates the login time in the database of the given user
 * The login time is used to automatically logout users who have been
 * inactive for the defined session time
 * @param string $username	the username to update the login info for
 */
function userUpdateLogin($username) {
	global $TBLPREFIX;

	if (empty($username)) $username = getUserName();
	if (empty($username)) return;

	$sql = "UPDATE ".$TBLPREFIX."users SET u_sessiontime='".time()."' WHERE u_username='$username'";
	$res = dbquery($sql);
}

/**
 * return a sorted array of user
 *
 * returns a sorted array of the users in the system
 * @link http://phpgedview.sourceforge.net/devdocs/arrays.php#users
 * @param string $field the field in the user array to sort on
 * @param string $order asc or dec
 * @return array returns a sorted array of users
 */
function getUsers($field = "username", $order = "asc", $sort2 = "firstname") {
	global $TBLPREFIX, $usersortfields;
	$sql = "SELECT * FROM ".$TBLPREFIX."users ORDER BY u_".$field." ".strtoupper($order).", u_".$sort2;
	$res = dbquery($sql);

	$users = array();
	if ($res) {
		while($user_row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$user = array();
			$user["username"]=$user_row["u_username"];
			$user["firstname"]=stripslashes($user_row["u_firstname"]);
			$user["lastname"]=stripslashes($user_row["u_lastname"]);
			$user["gedcomid"]=unserialize($user_row["u_gedcomid"]);
			$user["rootid"]=unserialize($user_row["u_rootid"]);
			$user["password"]=$user_row["u_password"];
			if ($user_row["u_canadmin"]=='Y') $user["canadmin"]=true;
			else $user["canadmin"]=false;
			$user["canedit"]=unserialize($user_row["u_canedit"]);
			//-- convert old <3.1 access levels to the new 3.2 access levels
			foreach($user["canedit"] as $key=>$value) {
				if ($value=="no") $user["canedit"][$key] = "access";
				if ($value=="yes") $user["canedit"][$key] = "edit";
			}
			$user["email"] = $user_row["u_email"];
			$user["verified"] = $user_row["u_verified"];
			$user["verified_by_admin"] = $user_row["u_verified_by_admin"];
			$user["language"] = $user_row["u_language"];
			$user["pwrequested"] = $user_row["u_pwrequested"];
			$user["reg_timestamp"] = $user_row["u_reg_timestamp"];
			$user["reg_hashcode"] = $user_row["u_reg_hashcode"];
			$user["theme"] = $user_row["u_theme"];
			$user["loggedin"] = $user_row["u_loggedin"];
			$user["sessiontime"] = $user_row["u_sessiontime"];
			$user["contactmethod"] = $user_row["u_contactmethod"];
			if ($user_row["u_visibleonline"]!='N') $user["visibleonline"] = true;
			else $user["visibleonline"] = false;
			if ($user_row["u_editaccount"]!='N') $user["editaccount"] = true;
			else $user["editaccount"] = false;
			$user["default_tab"] = $user_row["u_defaulttab"];
			$user["comment"] = $user_row["u_comment"];
			$user["comment_exp"] = $user_row["u_comment_exp"];
			$user["sync_gedcom"] = $user_row["u_sync_gedcom"];
			$user["relationship_privacy"] = $user_row["u_relationship_privacy"];
			$user["max_relation_path"] = $user_row["u_max_relation_path"];
//			if ($user_row["u_auto_accept"]!="Y") $user["auto_accept"] = false;
//			else $user["auto_accept"] = true;
			if ($user_row["u_auto_accept"]!="N") $user["auto_accept"] = true;
			else $user["auto_accept"] = false;
			$ext = runHooks('getuser', $user);
			if(count($ext) > 0)
			{
				$user = array_merge($user, $ext);
			}
			$users[$user_row["u_username"]] = $user;
		}
	}
	$res->free();
	return $users;
}

/**
 * get the current username
 *
 * gets the username for the currently active user
 * 1. first checks the session
 * 2. then checks the remember cookie
 * @return string 	the username of the user or an empty string if the user is not logged in
 */
function getUserName() {
	global $ALLOW_REMEMBER_ME, $DBCONN, $logout, $SERVER_URL;
	//-- this section checks if the session exists and uses it to get the username
	if (isset($_SESSION)) {
		if (!empty($_SESSION['pgv_user'])) return $_SESSION['pgv_user'];
	}
	if (isset($HTTP_SESSION_VARS)) {
		if (!empty($HTTP_SESSION_VARS['pgv_user'])) return $HTTP_SESSION_VARS['pgv_user'];
	}
	if ($ALLOW_REMEMBER_ME) {
		$tSERVER_URL = preg_replace(array("'https?://'", "'www.'", "'/$'"), array("","",""), $SERVER_URL);
		if(empty($tSERVER_URL)) $tSERVER_URL = $SERVER_URL; 	// cannot assume we had a match.
		if ((isset($_SERVER['HTTP_REFERER'])) && !empty($tSERVER_URL) && (stristr($_SERVER['HTTP_REFERER'],$tSERVER_URL)!==false)) $referrer_found=true;
		if (!empty($_COOKIE["pgv_rem"])&& (empty($referrer_found)) && empty($logout)) {
			if (!is_object($DBCONN)) return $_COOKIE["pgv_rem"];
			$user = getUser($_COOKIE["pgv_rem"]);
			if ($user) {
				if (time() - $user["sessiontime"] < 60*60*24*7) {
					$_SESSION['pgv_user'] = $_COOKIE["pgv_rem"];
					$_SESSION['cookie_login'] = true;
					return $_COOKIE["pgv_rem"];
				}
			}
		}
	}
	return "";
}

/**
 * check if given username is an admin
 *
 * takes a username and checks if the
 * user has administrative privileges
 * to change the configuration files
 */
function userIsAdmin($username) {
	if (empty($username)) return false;
	if (isset($_SESSION['cookie_login']) && $_SESSION['cookie_login']==true) return false;
	$user = getUser($username);
	if (!$user) return false;
	return $user["canadmin"];
}

/**
 * check if given username is an admin for the current gedcom
 *
 * takes a username and checks if the
 * user has administrative privileges
 * to change the configuration files for the currently active gedcom
 */
function userGedcomAdmin($username, $ged="") {
	global $GEDCOM;

	if (empty($ged)) $ged = $GEDCOM;
	if (isset($_SESSION['cookie_login']) && ($_SESSION['cookie_login']==true)) return false;
	if (userIsAdmin($username)) return true;
	if (empty($username)) return false;
	$user = getUser($username);
	if (!$user) return false;
	if (isset($user["canedit"][$ged])) {
		if ($user["canedit"][$ged]=="admin") return true;
		else return false;
	}
	else return false;
}

/**
 * check if the given user has access privileges on this gedcom
 *
 * takes a username and checks if the user has access privileges to view the private
 * gedcom data.
 * @param string $username the username of the user to check
 * @return boolean true if user can access false if they cannot
 */
function userCanAccess($username) {
	global $GEDCOM;

	if (userIsAdmin($username)) return true;
	if (empty($username)) return false;
	$user = getUser($username);
	if (!$user) return false;
	if (isset($user["canedit"][$GEDCOM])) {
		if ($user["canedit"][$GEDCOM]!="none" || $user["canadmin"]) return true;
		else return false;
	}
	else return false;
}

/**
 * check if the given user has write privileges on this gedcom
 *
 * takes a username and checks if the user has write privileges to change
 * the gedcom data. First check if the administrator has turned on editing privileges for this gedcom
 * @param string $username the username of the user to check
 * @return boolean true if user can edit false if they cannot
 */
function userCanEdit($username) {
	global $ALLOW_EDIT_GEDCOM, $GEDCOM;

	if (!$ALLOW_EDIT_GEDCOM) return false;
	if (userIsAdmin($username)) return true;
	if (empty($username)) return false;
	$user = getUser($username);
	if (!$user) return false;
	if ($user["canadmin"]) return true;
	if (isset($user["canedit"][$GEDCOM])) {
		if ($user["canedit"][$GEDCOM]=="yes" || $user["canedit"][$GEDCOM]=="edit" || $user["canedit"][$GEDCOM]=="admin"|| $user["canedit"][$GEDCOM]=="accept" || $user["canedit"][$GEDCOM]===true) return true;
		else return false;
	}
	else return false;
}

/**
 * Can user accept changes
 *
 * takes a username and checks if the user has write privileges to
 * change the gedcom data and accept changes
 * @param string $username	the username of the user check privileges
 * @return boolean true if user can accept false if user cannot accept
 */
function userCanAccept($username) {
	global $ALLOW_EDIT_GEDCOM, $GEDCOM;

	if ($_SESSION['cookie_login']) return false;
	if (userIsAdmin($username)) return true;
	if (!$ALLOW_EDIT_GEDCOM) return false;
	if (empty($username)) return false;
	$user = getUser($username);
	if (!$user) return false;
	if (isset($user["canedit"][$GEDCOM])) {
		if ($user["canedit"][$GEDCOM]=="accept") return true;
		if ($user["canedit"][$GEDCOM]=="admin") return true;
		else return false;
	}
	else return false;
}

/**
 * Should user's changed automatically be accepted
 * @param string $username	the user name of the user to check
 * @return boolean 		true if the changes should automatically be accepted
 */
function userAutoAccept($username = "") {
	if (empty($username)) $username = getUserName();
	if (empty($username)) return false;

	if (!userCanAccept($username)) return false;
	$user = getUser($username);
//	if ($user["auto_accept"]) return true;
	return $user["auto_accept"];
}

/**
 * does an admin user exits
 *
 * Checks to see if an admin user has been created
 * @return boolean true if an admin user has been defined
 */
function adminUserExists() {
	global $TBLPREFIX, $DBCONN, $PGV_ADMIN_EXISTS;
	
	if (isset($PGV_ADMIN_EXISTS)) return $PGV_ADMIN_EXISTS;
	
	if (checkTableExists()) {
		$sql = "SELECT u_username FROM ".$TBLPREFIX."users WHERE u_canadmin='Y'";
		$res = dbquery($sql);

		if ($res!==false && !DB::isError($res)) {
			$count = $res->numRows();
			//-- no need to iterate, we only need the count
			// while($row =& $res->fetchRow());
			$res->free();
			if ($count==0) {
				$PGV_ADMIN_EXISTS = false;
				return false;
			}
			$PGV_ADMIN_EXISTS = true;
			return true;
		}
	}
	return false;
}

/**
 * check if the user database tables exist
 *
 * If the tables don't exist then create them
 * If the tables do exist check if they need to be upgraded
 * to the latest version of the database schema.
 */
function checkTableExists() {
	global $TBLPREFIX, $DBCONN, $DBTYPE, $PGV_CHECKED_TABLES;

	//-- make sure we only run this function once
	if (isset($PGV_CHECKED_TABLES) && $PGV_CHECKED_TABLES) return true;
	$PGV_CHECKED_TABLES = true;

	$has_users = false;
	$has_gedcomid = false;
	$has_email = false;
	$has_messages = false;
	$has_favorites = false;
	$has_sessiontime = false;
	$has_blocks = false;
	$has_contactmethod = false;
	$has_news = false;
	$has_visible = false;
	$has_account = false;
	$has_defaulttab = false;
	$has_blockconfig = false;
	$has_comment = false;
	$has_comment_exp = false;
	$has_sync_gedcom = false;
	$has_first_name = false;
	$has_relation_privacy = false;
	$has_fav_note = false;
	$has_auto_accept = false;
	$has_mutex = false;

	$sqlite = ($DBTYPE == 'sqlite');

	if (DB::isError($DBCONN)) return false;
	$data = $DBCONN->getListOf('tables');
	foreach($data as $indexval => $table) {
		if (empty($TBLPREFIX) || strpos($table, $TBLPREFIX) === 0) {
			switch(substr($table, strlen($TBLPREFIX))) {
				case "users":
					$has_users = true;
					$info = $DBCONN->tableInfo($TBLPREFIX."users");
					foreach($info as $indexval => $field) {
						switch ($field["name"]) {
							case "u_gedcomid":
								$has_gedcomid = true;
								break;
							case "u_email":
								$has_email = true;
								break;
							case "u_sessiontime":
								$has_sessiontime = true;
								break;
							case "u_contactmethod":
								$has_contactmethod = true;
								break;
							case "u_visibleonline":
								$has_visible = true;
								break;
							case "u_editaccount":
								$has_account = true;
								break;
							case "u_defaulttab":
								$has_defaulttab = true;
								break;
							case "u_comment":
								$has_comment = true;
								break;
							case "u_comment_exp":
								$has_comment_exp = true;
								break;
							case "u_sync_gedcom":
								$has_sync_gedcom = true;
								break;
							case "u_firstname":
								$has_first_name = true;
								break;
							case "u_relationship_privacy":
								$has_relation_privacy = true;
								break;
							case "u_auto_accept":
								$has_auto_accept = true;
								break;
						}
					}
					break;
				case "messages":
					$has_messages = true;
					break;
				case "favorites":
					$has_favorites = true;
					$info = $DBCONN->tableInfo($TBLPREFIX."favorites");
					foreach($info as $indexval => $field) {
						switch ($field["name"]) {
							case "fv_note":
								$has_fav_note = true;
								break;
						}
					}
					break;
				case "blocks":
					$has_blocks = true;
					$info = $DBCONN->tableInfo($TBLPREFIX."blocks");
					foreach($info as $indexval => $field) {
						switch ($field["name"]) {
							case "b_config":
								$has_blockconfig = true;
								break;
						}
					}
					break;
				case "news":
					$has_news = true;
					break;
				case "mutex":
					$has_mutex = true;
					break;
			}
		}
	}
	if (!$has_users || !$has_gedcomid ||$DBTYPE == "mssql" && !$has_first_name ||
			$sqlite && (!$has_email || !$has_sessiontime || !$has_contactmethod || !$has_visible || !$has_account || !$has_defaulttab || !$has_comment || !$has_sync_gedcom || !$has_first_name || !$has_relation_privacy || !$has_auto_accept)) {
		$sql = "DROP TABLE ".$TBLPREFIX."users";
		$res = dbquery($sql, false);

		$sql = "CREATE TABLE ".$TBLPREFIX."users (u_username VARCHAR(30) NOT NULL, u_password VARCHAR(255), " .
				"u_firstname VARCHAR(255), u_lastname VARCHAR(255), u_gedcomid TEXT, u_rootid TEXT, " .
				"u_canadmin VARCHAR(2), u_canedit TEXT, u_email TEXT, u_verified VARCHAR(20), " .
				"u_verified_by_admin VARCHAR(20), u_language VARCHAR(50), u_pwrequested VARCHAR(20), " .
				"u_reg_timestamp VARCHAR(50), u_reg_hashcode VARCHAR(255), u_theme VARCHAR(50), u_loggedin VARCHAR(2), " .
				"u_sessiontime INT, u_contactmethod VARCHAR(20), u_visibleonline VARCHAR(2), u_editaccount VARCHAR(2), " .
				"u_defaulttab INT, u_comment VARCHAR(255), u_comment_exp VARCHAR(20), u_sync_gedcom VARCHAR(2), " .
				"u_relationship_privacy VARCHAR(2), u_max_relation_path INT, u_auto_accept VARCHAR(2), PRIMARY KEY(u_username))";
		/*
		 * -- the following sql queries will revert the users table back to 3.x
		alter table pgv_users drop column u_comment;
		alter table pgv_users drop column u_comment_exp;
		alter table pgv_users drop column u_sync_gedcom;
		alter table pgv_users drop column u_relationship_privacy;
		alter table pgv_users drop column u_max_relation_path;
		alter table pgv_users drop column u_auto_accept;
		alter table pgv_users add column u_fullname VARCHAR(255) after u_password;
		update table pgv_users set u_fullname = concat(u_firstname," ",u_lastname);
		alter table pgv_users drop column u_firstname;
		alter table pgv_users drop column u_lastname;
		 */
		$res = dbquery($sql);
		$sql = "CREATE INDEX users_username ON ".$TBLPREFIX."users (u_username)";
		$res = dbquery($sql);
	} else {
		if (!$has_email) {
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_email TEXT";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_verified VARCHAR(20)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_verified_by_admin VARCHAR(20)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_language VARCHAR(50)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_pwrequested VARCHAR(20)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_reg_timestamp VARCHAR(50)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_reg_hashcode VARCHAR(255)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_theme VARCHAR(50)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_loggedin VARCHAR(1)";
			$pres = dbquery($sql);
		}
		if (!$has_sessiontime) {
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_sessiontime INT";
			$pres = dbquery($sql);
		}
		if (!$has_contactmethod) {
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_contactmethod VARCHAR(20)";
			$pres = dbquery($sql);
		}
		if (!$has_visible) {
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_visibleonline VARCHAR(2)";
			$pres = dbquery($sql);
		}
		if (!$has_account) {
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_editaccount VARCHAR(2)";
			$pres = dbquery($sql);
		}
		if (!$has_defaulttab) {
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_defaulttab INT";
			$pres = dbquery($sql);
		}
		if (!$has_comment) {
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_comment VARCHAR(255)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_comment_exp VARCHAR(20)";
			$pres = dbquery($sql);
		}
		if (!$has_sync_gedcom) {
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_sync_gedcom VARCHAR(2)";
			$pres = dbquery($sql);
		}
		if (!$has_first_name) {
			//-- add new first and last name fields
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_firstname VARCHAR(255) AFTER u_fullname";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_lastname VARCHAR(255) AFTER u_firstname";
			$pres = dbquery($sql);
			//-- convert the old fullname to first and last names
			$sql = "UPDATE ".$TBLPREFIX."users SET u_lastname=SUBSTRING_INDEX(u_fullname, ' ', -1), u_firstname=SUBSTRING_INDEX(u_fullname, ' ', 1)";
			$pres = dbquery($sql);
			//-- drop the old fullname field
			$sql = "ALTER TABLE ".$TBLPREFIX."users DROP COLUMN u_fullname";
			$pres = dbquery($sql);
		}
		if (!$has_relation_privacy) {
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_relationship_privacy VARCHAR(2)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_max_relation_path INT";
			$pres = dbquery($sql);
		}
		if (!$has_auto_accept) {
			$sql = "ALTER TABLE ".$TBLPREFIX."users ADD u_auto_accept VARCHAR(2)";
			$pres = dbquery($sql);
			//-- set default for auto_accept to N
			$sql = "UPDATE ".$TBLPREFIX."users SET u_auto_accept='N'";
			$pres = dbquery($sql);
		}
	}
	if (!$has_messages) {
		$sql = "DROP TABLE ".$TBLPREFIX."messages";
		$res = dbquery($sql, false);
		$sql = "CREATE TABLE ".$TBLPREFIX."messages (m_id INT NOT NULL, m_from VARCHAR(255), m_to VARCHAR(30), m_subject VARCHAR(255), m_body TEXT, m_created VARCHAR(255), PRIMARY KEY(m_id))";
		$res = dbquery($sql);
		$sql = "CREATE INDEX messages_to ON ".$TBLPREFIX."messages (m_to)";
		$res = dbquery($sql);
	}
	if (!$has_favorites || $sqlite && (!$has_fav_note)) {
		$sql = "DROP TABLE ".$TBLPREFIX."favorites";
		$res = dbquery($sql, false);
		$sql = "CREATE TABLE ".$TBLPREFIX."favorites (fv_id INT NOT NULL, fv_username VARCHAR(30), fv_gid VARCHAR(10), fv_type VARCHAR(10), fv_file VARCHAR(100), fv_url VARCHAR(255), fv_title VARCHAR(255), fv_note TEXT, PRIMARY KEY(fv_id))";
		$res = dbquery($sql);
		$sql = "CREATE INDEX favorites_username ON ".$TBLPREFIX."favorites (fv_username)";
		$res = dbquery($sql);
	} else {
		if (!$has_fav_note) {
			$sql = "ALTER TABLE ".$TBLPREFIX."favorites ADD fv_url VARCHAR(255)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."favorites ADD fv_title VARCHAR(255)";
			$pres = dbquery($sql);
			$sql = "ALTER TABLE ".$TBLPREFIX."favorites ADD fv_note TEXT";
			$pres = dbquery($sql);
		}
	}
	if (!$has_blocks || $sqlite && (!$has_blockconfig)) {
		$sql = "DROP TABLE ".$TBLPREFIX."blocks";
		$res = dbquery($sql, false);
		$sql = "CREATE TABLE ".$TBLPREFIX."blocks (b_id INT NOT NULL, b_username VARCHAR(100), b_location VARCHAR(30), b_order INT, b_name VARCHAR(255), b_config TEXT, PRIMARY KEY(b_id))";
		$res = dbquery($sql);
		$sql = "CREATE INDEX blocks_username ON ".$TBLPREFIX."blocks (b_username)";
		$res = dbquery($sql);
	} else {
		if (!$has_blockconfig) {
			$sql = "ALTER TABLE ".$TBLPREFIX."blocks ADD b_config TEXT";
			$res = dbquery($sql);
		}
	}
	if (!$has_news) {
		$sql = "DROP TABLE ".$TBLPREFIX."news";
		$res = dbquery($sql, false);
		$sql = "CREATE TABLE ".$TBLPREFIX."news (n_id INT NOT NULL, n_username VARCHAR(100), n_date INT, n_title VARCHAR(255), n_text TEXT, PRIMARY KEY(n_id))";
		$res = dbquery($sql);
		$sql = "CREATE INDEX news_username ON ".$TBLPREFIX."news (n_username)";
		$res = dbquery($sql);
	}
	if (!$has_mutex) {
		$sql = "DROP TABLE ".$TBLPREFIX."mutex";
		$res = dbquery($sql, false);
		$sql = "create table ".$TBLPREFIX."mutex (mx_id INT NOT NULL, mx_name VARCHAR(255), mx_thread VARCHAR(255), mx_time INT, PRIMARY KEY (mx_id))";
		$res = dbquery($sql);
		$sql = "CREATE INDEX mutex_name ON ".$TBLPREFIX."mutex (mx_name)";
		$res = dbquery($sql);
	}
	return true;
}

/**
 * Add a new user
 *
 * Adds a new user to the data store
 * @param array $newuser	The new user array to add
 * @param string $msg		The log message to write to the log
 */
function addUser($newuser, $msg = "added") {
	global $TBLPREFIX, $DBCONN, $USE_RELATIONSHIP_PRIVACY, $MAX_RELATION_PATH_LENGTH;

	if (checkTableExists()) {
//		if (!isset($newuser["relationship_privacy"])) {
//			if ($USE_RELATIONSHIP_PRIVACY) $newuser["relationship_privacy"] = "Y";
//			else $newuser["relationship_privacy"] = "N";
//		}
//		if (!isset($newuser["max_relation_path"])) $newuser["max_relation_path"] = $MAX_RELATION_PATH_LENGTH;
//		if (!isset($newuser["auto_accept"])) $newuser["auto_accept"] = "N";
		$newuser = db_prep($newuser);
		$newuser["firstname"] = preg_replace("/\//", "", $newuser["firstname"]);
		$newuser["lastname"] = preg_replace("/\//", "", $newuser["lastname"]);
		$sql = "INSERT INTO ".$TBLPREFIX."users VALUES('".$DBCONN->escapeSimple($newuser["username"])."','".$DBCONN->escapeSimple($newuser["password"])."','".$DBCONN->escapeSimple($newuser["firstname"])."','".$DBCONN->escapeSimple($newuser["lastname"])."','".$DBCONN->escapeSimple(serialize($newuser["gedcomid"]))."','".$DBCONN->escapeSimple(serialize($newuser["rootid"]))."'";
		if ($newuser["canadmin"]) $sql .= ",'Y'";
		else $sql .= ",'N'";
		$sql .= ",'".$DBCONN->escapeSimple(serialize($newuser["canedit"]))."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["email"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["verified"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["verified_by_admin"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["language"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["pwrequested"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["reg_timestamp"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["reg_hashcode"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["theme"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["loggedin"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["sessiontime"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["contactmethod"])."'";
		if ($newuser["visibleonline"]) $sql .= ",'Y'";
		else $sql .= ",'N'";
		if ($newuser["editaccount"]) $sql .= ",'Y'";
		else $sql .= ",'N'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["default_tab"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["comment"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["comment_exp"])."'";
//		if (isset($newuser["sync_gedcom"])) $sql .= ",'".$DBCONN->escapeSimple($newuser["sync_gedcom"])."'";
//		else $sql .= ",'N'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["sync_gedcom"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["relationship_privacy"])."'";
		$sql .= ",'".$DBCONN->escapeSimple($newuser["max_relation_path"])."'";
//		if (isset($newuser["auto_accept"]) && $newuser["auto_accept"]===true) $sql .= ",'Y'";
		if ($newuser["auto_accept"]) $sql .= ",'Y'";
		else $sql .= ",'N'";
		$sql .= ")";
		$tmp = dbquery($sql);
		$res =& $tmp;
		$activeuser = getUserName();
		if ($activeuser == "") $activeuser = "Anonymous user";
		AddToLog($activeuser." ".$msg." user -> ".$newuser["username"]." <-");
		if ($res)
		{
			runHooks('adduser', $newuser);
			return true;
		}
	}
	return false;
}

/**
 * Update a user
 *
 * Updates a user's record in the data store
 * @param string $username	The username of the user to update
 * @param array $newuser	The new user array to add
 * @param string $msg		The log message to write to the log
 */
function updateUser($username, $newuser, $msg = "updated") {
	global $TBLPREFIX, $DBCONN, $USE_RELATIONSHIP_PRIVACY, $MAX_RELATION_PATH_LENGTH;

	if (checkTableExists()) {
		$newuser = db_prep($newuser);
		$newuser['previous_username'] = $username;
		$newuser["firstname"] = preg_replace("/\//", "", $newuser["firstname"]);
		$newuser["lastname"] = preg_replace("/\//", "", $newuser["lastname"]);
		$sql = "UPDATE ".$TBLPREFIX."users SET u_username='".$DBCONN->escapeSimple($newuser["username"])."', " .
				"u_password='".$newuser["password"]."', " .
				"u_firstname='".$DBCONN->escapeSimple($newuser["firstname"])."', " .
				"u_lastname='".$DBCONN->escapeSimple($newuser["lastname"])."', " .
				"u_gedcomid='".$DBCONN->escapeSimple(serialize($newuser["gedcomid"]))."'," .
				"u_rootid='".$DBCONN->escapeSimple(serialize($newuser["rootid"]))."'";
		if ($newuser["canadmin"]) $sql .= ", u_canadmin='Y'";
		else $sql .= ", u_canadmin='N'";
		$sql .= ", u_canedit='".$DBCONN->escapeSimple(serialize($newuser["canedit"]))."'";
		$sql .= ", u_email='".$DBCONN->escapeSimple($newuser["email"])."'";
		$sql .= ", u_verified='".$DBCONN->escapeSimple($newuser["verified"])."'";
		$sql .= ", u_verified_by_admin='".$DBCONN->escapeSimple($newuser["verified_by_admin"])."'";
		$sql .= ", u_language='".$DBCONN->escapeSimple($newuser["language"])."'";
		$sql .= ", u_pwrequested='".$DBCONN->escapeSimple($newuser["pwrequested"])."'";
		$sql .= ", u_reg_timestamp='".$DBCONN->escapeSimple($newuser["reg_timestamp"])."'";
		$sql .= ", u_reg_hashcode='".$DBCONN->escapeSimple($newuser["reg_hashcode"])."'";
		$sql .= ", u_theme='".$DBCONN->escapeSimple($newuser["theme"])."'";
		$sql .= ", u_loggedin='".$DBCONN->escapeSimple($newuser["loggedin"])."'";
		$sql .= ", u_sessiontime='".$DBCONN->escapeSimple($newuser["sessiontime"])."'";
		$sql .= ", u_contactmethod='".$DBCONN->escapeSimple($newuser["contactmethod"])."'";
		if ($newuser["visibleonline"]) $sql .= ", u_visibleonline='Y'";
		else $sql .= ", u_visibleonline='N'";
		if ($newuser["editaccount"]) $sql .= ", u_editaccount='Y'";
		else $sql .= ", u_editaccount='N'";
		$sql .= ", u_defaulttab='".$DBCONN->escapeSimple($newuser["default_tab"])."'";
		$sql .= ", u_comment='".$DBCONN->escapeSimple($newuser["comment"])."'";
		$sql .= ", u_comment_exp='".$DBCONN->escapeSimple($newuser["comment_exp"])."'";
		$sql .= ", u_sync_gedcom='".$DBCONN->escapeSimple($newuser["sync_gedcom"])."'";
		$sql .= ", u_relationship_privacy='".$DBCONN->escapeSimple($newuser["relationship_privacy"])."'";
		$sql .= ", u_max_relation_path='".$DBCONN->escapeSimple($newuser["max_relation_path"])."'";
		if ($newuser["auto_accept"]) $sql .= ", u_auto_accept='Y'";
		else $sql .= ", u_auto_accept='N'";
		$sql .= " WHERE u_username='".$DBCONN->escapeSimple($username)."'";
		$res = dbquery($sql);
		$activeuser = getUserName();
		if ($activeuser == "") $activeuser = "Anonymous user";
		AddToLog($activeuser." ".$msg." user [old username '".$newuser['previous_username']."'] new username-> ".$newuser["username"]." <-");

		//-- update all reference tables if username changed
		if ($newuser["username"]!=$username) {
			$sql = "UPDATE ".$TBLPREFIX."blocks SET b_username='".$DBCONN->escapeSimple($newuser["username"])."' WHERE b_username='".$DBCONN->escapeSimple($username)."'";
			$res = dbquery($sql);
			$sql = "UPDATE ".$TBLPREFIX."favorites SET fv_username='".$DBCONN->escapeSimple($newuser["username"])."' WHERE fv_username='".$DBCONN->escapeSimple($username)."'";
			$res = dbquery($sql);
			$sql = "UPDATE ".$TBLPREFIX."messages SET m_from='".$DBCONN->escapeSimple($newuser["username"])."' WHERE m_from='".$DBCONN->escapeSimple($username)."'";
			$res = dbquery($sql);
			$sql = "UPDATE ".$TBLPREFIX."messages SET m_to='".$DBCONN->escapeSimple($newuser["username"])."' WHERE m_to='".$DBCONN->escapeSimple($username)."'";
			$res = dbquery($sql);
			$sql = "UPDATE ".$TBLPREFIX."news SET n_username='".$DBCONN->escapeSimple($newuser["username"])."' WHERE n_username='".$DBCONN->escapeSimple($username)."'";
			$res = dbquery($sql);
		}
		if($res)
		{
			runHooks('updateuser', $newuser);
			return true;
		}
	}
	return false;
}

/**
 * deletes the user with the given username.
 * @param string $username	the username to delete
 * @param string $msg		a message to write to the log file
 */
function deleteUser($username, $msg = "deleted") {
	global $TBLPREFIX, $users;
	unset($users[$username]);
	$username = db_prep($username);
	$sql = "DELETE FROM ".$TBLPREFIX."users WHERE u_username='$username'";
	$res = dbquery($sql);

	$activeuser = getUserName();
	if ($activeuser == "") $activeuser = "Anonymous user";
	if (($msg != "changed") && ($msg != "reqested password for") && ($msg != "verified")) AddToLog($activeuser." ".$msg." user -> ".$username." <-");
	if($res)
	{
		runHooks('deleteuser', array('username'=>$username));
		return true;
	}
	else{return false;}
}

/**
 * creates a user as reference for a gedcom export
 * @param string $export_accesslevel
 */
function create_export_user($export_accesslevel) {
	GLOBAL $GEDCOM;

	if (getUser("export")) deleteUser("export");

	$newuser = array();
	$newuser["username"] = "export";
	$newuser["firstname"] = "Export";
	$newuser["lastname"] = "useraccount";
	$newuser["gedcomid"] = "";
	$newuser["rootid"] = "";
	srand((double)microtime()*1000000);
	$allow = "abcdefghijkmnpqrstuvwxyz123456789";
	$password = "";
	for($i=0; $i<8; $i++) {
		$password .= $allow[rand()%strlen($allow)];
	}
	$newuser["password"] = $password;
	if ($export_accesslevel == "admin") $newuser["canadmin"] = true;
	else $newuser["canadmin"] = false;
	if ($export_accesslevel == "gedadmin") $newuser["canedit"][$GEDCOM] = "admin";
	elseif ($export_accesslevel == "user") $newuser["canedit"][$GEDCOM] = "access";
	else $newuser["canedit"][$GEDCOM] = "none";
	$newuser["email"] = "";
	$newuser["verified"] = "yes";
	$newuser["verified_by_admin"] = "yes";
	$newuser["language"] = "english";
	$newuser["pwrequested"] = "";
	$newuser["reg_timestamp"] = "";
	$newuser["reg_hashcode"] = "";
	$newuser["theme"] = "";
	$newuser["loggedin"] = "";
	$newuser["sessiontime"] = time();
	$newuser["contactmethod"] = "none";
	$newuser["visibleonline"] = false;
	$newuser["editaccount"] = false;
	$newuser["default_tab"] = 0;
	$newuser["comment"] = "Dummy tester for export purposes";
	$newuser["comment_exp"] = 0;
	$newuser["sync_gedcom"] = "N";
	$newuser["relationship_privacy"] = "N";
	$newuser["max_relation_path"] = 0;
//	$newuser["auto_accept"] = "N";
	$newuser["auto_accept"] = false;
	addUser($newuser);
}

/**
 * get a user array
 *
 * finds a user from the given username and returns a user array of the form
 * defined at {@link http://www.phpgedview.net/devdocs/arrays.php#user}
 * @param string $username the username of the user to return
 * @return array the user array to return
 */
function getUser($username) {
	global $TBLPREFIX, $users, $REGEXP_DB, $GEDCOMS, $DBTYPE;

	if (empty($username)) return false;
	if (isset($users[$username])) return $users[$username];
	$username = db_prep($username);
	$sql = "SELECT * FROM ".$TBLPREFIX."users WHERE ";
	if (stristr($DBTYPE, "mysql")!==false) $sql .= "BINARY ";
	$sql .= "u_username='".$username."'";
	$res = dbquery($sql, false);

	if ($res===false || DB::isError($res)) return false;
	if ($res->numRows()==0) return false;
	if ($res) {
		while($user_row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($user_row) {
				$user = array();
				$user["username"]=$user_row["u_username"];
				$user["firstname"]=stripslashes($user_row["u_firstname"]);
				$user["lastname"]=stripslashes($user_row["u_lastname"]);
				$user["gedcomid"]=unserialize($user_row["u_gedcomid"]);
				$user["rootid"]=unserialize($user_row["u_rootid"]);
				$user["password"]=$user_row["u_password"];
				if ($user_row["u_canadmin"]=='Y') $user["canadmin"]=true;
				else $user["canadmin"]=false;
				$user["canedit"]=unserialize($user_row["u_canedit"]);
				//-- convert old <3.1 access levels to the new 3.2 access levels
				foreach($user["canedit"] as $key=>$value) {
					if ($value=="no") $user["canedit"][$key] = "access";
					if ($value=="yes") $user["canedit"][$key] = "edit";
				}
				foreach($GEDCOMS as $ged=>$gedarray) {
					if (!isset($user["canedit"][$ged])) $user["canedit"][$ged] = "access";
				}
				$user["email"] = $user_row["u_email"];
				$user["verified"] = $user_row["u_verified"];
				$user["verified_by_admin"] = $user_row["u_verified_by_admin"];
				$user["language"] = $user_row["u_language"];
				$user["pwrequested"] = $user_row["u_pwrequested"];
				$user["reg_timestamp"] = $user_row["u_reg_timestamp"];
				$user["reg_hashcode"] = $user_row["u_reg_hashcode"];
				$user["theme"] = $user_row["u_theme"];
				$user["loggedin"] = $user_row["u_loggedin"];
				$user["sessiontime"] = $user_row["u_sessiontime"];
				$user["contactmethod"] = $user_row["u_contactmethod"];
				if ($user_row["u_visibleonline"]!='N') $user["visibleonline"]=true;
				else $user["visibleonline"]=false;
				if ($user_row["u_editaccount"]!='N' || $user["canadmin"]) $user["editaccount"]=true;
				else $user["editaccount"]=false;
				$user["default_tab"] = $user_row["u_defaulttab"];
				$user["comment"] = $user_row["u_comment"];
				$user["comment_exp"] = $user_row["u_comment_exp"];
				$user["sync_gedcom"] = $user_row["u_sync_gedcom"];
				$user["relationship_privacy"] = $user_row["u_relationship_privacy"];
				$user["max_relation_path"] = $user_row["u_max_relation_path"];
//				if ($user_row["u_auto_accept"]!='Y') $user["auto_accept"]=false;
//				else $user["auto_accept"]=true;
				if ($user_row["u_auto_accept"]!='N') $user["auto_accept"]=true;
				else $user["auto_accept"]=false;
				$ext = runHooks('getuser', $user);
				if(count($ext) > 0)
				{
					$user = array_merge($user, $ext);
				}
				$users[$user_row["u_username"]] = $user;
			}
		}
		$res->free();
		if (isset($user)) return $user;
	}
	return false;
}

/**
 * get a user from a gedcom id
 *
 * finds a user from their gedcom id
 * @param string $id	the gedcom id to to search on
 * @param string $gedcom	the gedcom filename to match
 * @return array 	returns a user array
 */
function getUserByGedcomId($id, $gedcom) {
	global $TBLPREFIX, $users, $REGEXP_DB;

	if (empty($id) || empty($gedcom)) return false;

	$user = false;
	$id = db_prep($id);
	$sql = "SELECT * FROM ".$TBLPREFIX."users WHERE ";
	$sql .= "u_gedcomid LIKE '%".$id."%'";
	$res = dbquery($sql, false);

	if (DB::isError($res)) return false;
	if ($res->numRows()==0) return false;
	if ($res) {
		while($user_row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($user_row) {
				$gedcomid=unserialize($user_row["u_gedcomid"]);
				if (isset($gedcomid[$gedcom]) && ($gedcomid[$gedcom]==$id)) {
					$user = array();
					$user["username"]=$user_row["u_username"];
					$user["firstname"]=stripslashes($user_row["u_firstname"]);
					$user["lastname"]=stripslashes($user_row["u_lastname"]);
					$user["gedcomid"]=$gedcomid;
					$user["rootid"]=unserialize($user_row["u_rootid"]);
					$user["password"]=$user_row["u_password"];
					if ($user_row["u_canadmin"]=='Y') $user["canadmin"]=true;
					else $user["canadmin"]=false;
					$user["canedit"]=unserialize($user_row["u_canedit"]);
					//-- convert old <3.1 access levels to the new 3.2 access levels
					foreach($user["canedit"] as $key=>$value) {
						if ($value=="no") $user["canedit"][$key] = "access";
						if ($value=="yes") $user["canedit"][$key] = "edit";
					}
					$user["email"] = $user_row["u_email"];
					$user["verified"] = $user_row["u_verified"];
					$user["verified_by_admin"] = $user_row["u_verified_by_admin"];
					$user["language"] = $user_row["u_language"];
					$user["pwrequested"] = $user_row["u_pwrequested"];
					$user["reg_timestamp"] = $user_row["u_reg_timestamp"];
					$user["reg_hashcode"] = $user_row["u_reg_hashcode"];
					$user["theme"] = $user_row["u_theme"];
					$user["loggedin"] = $user_row["u_loggedin"];
					$user["sessiontime"] = $user_row["u_sessiontime"];
					$user["contactmethod"] = $user_row["u_contactmethod"];
					if ($user_row["u_visibleonline"]!='N') $user["visibleonline"]=true;
					else $user["visibleonline"]=false;
					if ($user_row["u_editaccount"]!='N' || $user["canadmin"]) $user["editaccount"]=true;
					else $user["editaccount"]=false;
					$user["default_tab"] = $user_row["u_defaulttab"];
					$user["comment"] = $user_row["u_comment"];
					$user["comment_exp"] = $user_row["u_comment_exp"];
					$user["sync_gedcom"] = $user_row["u_sync_gedcom"];
					$user["relationship_privacy"] = $user_row["u_relationship_privacy"];
					$user["max_relation_path"] = $user_row["u_max_relation_path"];
//					if ($user_row["u_auto_accept"]!='Y') $user["auto_accept"]=false;
//					else $user["auto_accept"]=true;
					if ($user_row["u_auto_accept"]!='N') $user["auto_accept"]=true;
					else $user["auto_accept"]=false;
					$ext = runHooks('getuser', $user);
					if(count($ext) > 0)
					{
						$user = array_merge($user, $ext);
					}
					$users[$user_row["u_username"]] = $user;
				}
			}
		}
		$res->free();
		return $user;
	}
	return false;
}

/**
 * add a message into the log-file
 * @param string $LogString		the message to add
 * @param boolean $savelangerror
 * @return string returns the log line if successfully inserted into the log
 */
function AddToLog($LogString, $savelangerror=false) {
	global $INDEX_DIRECTORY, $LOGFILE_CREATE, $argc;

	$wroteLogString = false;

	if ($LOGFILE_CREATE=="none") return;

	//-- do not allow code to be written to the log file
	$LogString = preg_replace("/<\?.*\?>/", "*** CODE DETECTED ***", $LogString);

	if (isset($_SERVER['REMOTE_ADDR'])) $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
	else if ($argc>1) $REMOTE_ADDR = "cli";
	if ($LOGFILE_CREATE !== "none" && $savelangerror === false) {
		if (empty($LOGFILE_CREATE)) $LOGFILE_CREATE="daily";
		if ($LOGFILE_CREATE=="daily") $logfile = $INDEX_DIRECTORY."pgv-" . date("Ymd") . ".log";
		if ($LOGFILE_CREATE=="weekly") $logfile = $INDEX_DIRECTORY."pgv-" . date("Ym") . "-week" . date("W") . ".log";
		if ($LOGFILE_CREATE=="monthly") $logfile = $INDEX_DIRECTORY."pgv-" . date("Ym") . ".log";
		if ($LOGFILE_CREATE=="yearly") $logfile = $INDEX_DIRECTORY."pgv-" . date("Y") . ".log";
		if (is_writable($INDEX_DIRECTORY)) {
			$logline = date("d.m.Y H:i:s") . " - " . $REMOTE_ADDR . " - " . $LogString . "\r\n";
			$fp = fopen($logfile, "a");
			flock($fp, 2);
			fputs($fp, $logline);
			flock($fp, 3);
			fclose($fp);
			$wroteLogString = true;
		}
	}
	if ($wroteLogString) return $logline;
	else return "";
}

//----------------------------------- AddToSearchLog
//-- requires a string to add into the searchlog-file
function AddToSearchLog($LogString, $allgeds) {
	global $INDEX_DIRECTORY, $SEARCHLOG_CREATE, $GEDCOM, $GEDCOMS, $username;

	if (!isset($allgeds)) return;
	if (count($allgeds) == 0) return;

	//-- do not allow code to be written to the log file
	$LogString = preg_replace("/<\?.*\?>/", "*** CODE DETECTED ***", $LogString);

	$oldged = $GEDCOM;
	foreach($allgeds as $indexval => $value) {
		$GEDCOM = $value;
		include(get_config_file());
		if ($SEARCHLOG_CREATE != "none") {
			$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
			if (empty($SEARCHLOG_CREATE)) $SEARCHLOG_CREATE="daily";
			if ($SEARCHLOG_CREATE=="daily") $logfile = $INDEX_DIRECTORY."srch-" . $GEDCOM . date("Ymd") . ".log";
			if ($SEARCHLOG_CREATE=="weekly") $logfile = $INDEX_DIRECTORY."srch-" . $GEDCOM . date("Ym") . "-week" . date("W") . ".log";
			if ($SEARCHLOG_CREATE=="monthly") $logfile = $INDEX_DIRECTORY."srch-" . $GEDCOM . date("Ym") . ".log";
			if ($SEARCHLOG_CREATE=="yearly") $logfile = $INDEX_DIRECTORY."srch-" . $GEDCOM . date("Y") . ".log";
			if (is_writable($INDEX_DIRECTORY)) {
				$logline = "Date / Time: ".date("d.m.Y H:i:s") . " - IP: " . $REMOTE_ADDR . " - User: " .  getUserName() . "<br />";
				if (count($allgeds) == count($GEDCOMS)) $logline .= "Searchtype: Global<br />"; else $logline .= "Searchtype: Gedcom<br />";
				$logline .= $LogString . "<br /><br />\r\n";
				$fp = fopen($logfile, "a");
				flock($fp, 2);
				fputs($fp, $logline);
				flock($fp, 3);
				fclose($fp);
			}
		}
	}
	$GEDCOM = $oldged;
	include(get_config_file());
}

//----------------------------------- AddToChangeLog
//-- requires a string to add into the changelog-file
function AddToChangeLog($LogString, $ged="") {
	global $INDEX_DIRECTORY, $CHANGELOG_CREATE, $GEDCOM, $GEDCOMS, $username, $SEARCHLOG_CREATE;

	//-- do not allow code to be written to the log file
	$LogString = preg_replace("/<\?.*\?>/", "*** CODE DETECTED ***", $LogString);

	if (empty($ged)) $ged = $GEDCOM;
	$oldged = $GEDCOM;
	$GEDCOM = $ged;
	include(get_config_file());
	if ($CHANGELOG_CREATE != "none") {
		$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
		if (empty($CHANGELOG_CREATE)) $CHANGELOG_CREATE="daily";
		if ($CHANGELOG_CREATE=="daily") $logfile = $INDEX_DIRECTORY."/ged-" . $GEDCOM . date("Ymd") . ".log";
		if ($CHANGELOG_CREATE=="weekly") $logfile = $INDEX_DIRECTORY."/ged-" . $GEDCOM . date("Ym") . "-week" . date("W") . ".log";
		if ($CHANGELOG_CREATE=="monthly") $logfile = $INDEX_DIRECTORY."/ged-" . $GEDCOM . date("Ym") . ".log";
		if ($CHANGELOG_CREATE=="yearly") $logfile = $INDEX_DIRECTORY."/ged-" . $GEDCOM . date("Y") . ".log";
		if (is_writable($INDEX_DIRECTORY)) {
			$logline = date("d.m.Y H:i:s") . " - " . $REMOTE_ADDR . " - " . $LogString . "\r\n";
			$fp = fopen($logfile, "a");
			flock($fp, 2);
			fputs($fp, $logline);
			flock($fp, 3);
			fclose($fp);
		}

	}
	$GEDCOM = $oldged;
	include(get_config_file());
}

//----------------------------------- addMessage
//-- stores a new message in the database
function addMessage($message) {
	global $TBLPREFIX, $CONTACT_METHOD, $pgv_lang,$CHARACTER_SET, $LANGUAGE, $PGV_STORE_MESSAGES, $SERVER_URL, $PGV_SIMPLE_MAIL, $WEBMASTER_EMAIL, $DBCONN;
	global $TEXT_DIRECTION, $TEXT_DIRECTION_array, $DATE_FORMAT, $DATE_FORMAT_array, $TIME_FORMAT, $TIME_FORMAT_array, $WEEK_START, $WEEK_START_array, $NAME_REVERSE, $NAME_REVERSE_array;
	global $PHPGEDVIEW_EMAIL;

	//-- do not allow users to send a message to themselves
	if ($message["from"]==$message["to"]) return false;

	require_once('includes/functions_mail.php');

	$fUser = getUser($message["from"]);
	$tUser = getUser($message["to"]);
	
	// Switch to the "from" user's language
	$oldLanguage = $LANGUAGE;
	if ($fUser && $LANGUAGE!=$fUser["language"]) loadLanguage($fUser["language"]);

	//-- setup the message body for the "from" user
	$email2 = stripslashes($message["body"]);
	if (isset($message["from_name"])) $email2 = $pgv_lang["message_from_name"]." ".$message["from_name"]."\r\n".$pgv_lang["message_from"]." ".$message["from_email"]."\r\n\r\n".$email2;
	if (!empty($message["url"])) $email2 .= "\r\n\r\n--------------------------------------\r\n\r\n".$pgv_lang["viewing_url"]."\r\n".$SERVER_URL.$message["url"]."\r\n";
	$email2 .= "\r\n=--------------------------------------=\r\nIP ADDRESS: ".$_SERVER['REMOTE_ADDR']."\r\n";
	$email2 .= "DNS LOOKUP: ".gethostbyaddr($_SERVER['REMOTE_ADDR'])."\r\n";
	$email2 .= "LANGUAGE: $LANGUAGE\r\n";
	$subject2 = "[".$pgv_lang["phpgedview_message"].($TEXT_DIRECTION=="ltr"?"] ":" [").stripslashes($message["subject"]);
	$from ="";
	if (!$fUser) {
		$from = $message["from"];
		$email2 = $pgv_lang["message_email3"]."\r\n\r\n".stripslashes($email2);
		$fromFullName = $message["from"];
	}
	else {
		if ($NAME_REVERSE) $fromFullName = $fUser["lastname"]." ".$fUser["firstname"];
		else $fromFullName = $fUser["firstname"]." ".$fUser["lastname"];
		if (!$PGV_SIMPLE_MAIL) $from = hex4email(stripslashes($fromFullName),$CHARACTER_SET). " <".$fUser["email"].">";
		else $from = $fUser["email"];
		$email2 = $pgv_lang["message_email2"]."\r\n\r\n".stripslashes($email2);

	}

	//-- Load the "to" users language
	if ($tUser && $LANGUAGE!=$tUser["language"]) loadLanguage($tUser["language"]);

	if (isset($message["from_name"])) $message["body"] = $pgv_lang["message_from_name"]." ".$message["from_name"]."\r\n".$pgv_lang["message_from"]." ".$message["from_email"]."\r\n\r\n".$message["body"];
	//-- [ phpgedview-Feature Requests-1588353 ] Supress admin IP address in Outgoing PGV Email
	if (!userIsAdmin($message["from"])) {
		if (!empty($message["url"])) $message["body"] .= "\r\n\r\n--------------------------------------\r\n\r\n".$pgv_lang["viewing_url"]."\r\n".$SERVER_URL.$message["url"]."\r\n";
		$message["body"] .= "\r\n=--------------------------------------=\r\nIP ADDRESS: ".$_SERVER['REMOTE_ADDR']."\r\n";
		$message["body"] .= "DNS LOOKUP: ".gethostbyaddr($_SERVER['REMOTE_ADDR'])."\r\n";
		$message["body"] .= "LANGUAGE: $LANGUAGE\r\n";
	}
	if (!isset($message["created"])) $message["created"] = gmdate ("M d Y H:i:s");
	if ($PGV_STORE_MESSAGES && ($message["method"]!="messaging3" && $message["method"]!="mailto" && $message["method"]!="none")) {
		$newid = get_next_id("messages", "m_id");
		$sql = "INSERT INTO ".$TBLPREFIX."messages VALUES ($newid, '".$DBCONN->escapeSimple($message["from"])."','".$DBCONN->escapeSimple($message["to"])."','".$DBCONN->escapeSimple($message["subject"])."','".$DBCONN->escapeSimple($message["body"])."','".$DBCONN->escapeSimple($message["created"])."')";
		$res = dbquery($sql);

	}
	if ($message["method"]!="messaging") {
		$subject1 = "[".$pgv_lang["phpgedview_message"].($TEXT_DIRECTION=="ltr"?"] ":" [").stripslashes($message["subject"]);
		if (!$fUser) {
			$email1 = $pgv_lang["message_email1"];
			if (!empty($message["from_name"])) $email1 .= $message["from_name"]."\r\n\r\n".stripslashes($message["body"]);
			else $email1 .= $from."\r\n\r\n".stripslashes($message["body"]);
		}
		else {
			$email1 = $pgv_lang["message_email1"];
			$email1 .= stripslashes($fromFullName)."\r\n\r\n".stripslashes($message["body"]);
		}
		if (!$tUser) {
			//-- the to user must be a valid user in the system before it will send any mails
			return false;
		} else {
			if ($LANGUAGE!=$tUser["language"]) loadLanguage($tUser["language"]);
			if ($NAME_REVERSE) $toFullName = $tUser["lastname"]." ".$tUser["firstname"];
			else $toFullName = $tUser["firstname"]." ".$tUser["lastname"];
			if (!$PGV_SIMPLE_MAIL) $to = hex4email(stripslashes($toFullName),$CHARACTER_SET). " <".$tUser["email"].">";
			else $to = $tUser["email"];
		}
		if (!$fUser) $header2 = $PHPGEDVIEW_EMAIL;
		else $header2 = $to;
		if (!empty($tUser["email"])) pgvMail($to, $from, $subject1, $email1);
	}
	if ($message["method"]!="messaging") {
		if (!isset($message["no_from"])) {
			if (stristr($from, $PHPGEDVIEW_EMAIL)){
				$admuser = getuser($WEBMASTER_EMAIL);
				$from = $admuser["email"];
			}
			pgvMail($from, $header2, $subject2, $email2);
		}
	}

	if ($LANGUAGE!=$oldLanguage) loadLanguage($oldLanguage);			// restore language settings if needed

	return true;
}

//----------------------------------- deleteMessage
//-- deletes a message in the database
function deleteMessage($message_id) {
	global $TBLPREFIX;

	$sql = "DELETE FROM ".$TBLPREFIX."messages WHERE m_id=".$message_id;
	$res = dbquery($sql);

	if ($res) return true;
	else return false;
}

//----------------------------------- getUserMessages
//-- Return an array of a users messages
function getUserMessages($username) {
	global $TBLPREFIX;

	$messages = array();
	$sql = "SELECT * FROM ".$TBLPREFIX."messages WHERE m_to='$username' ORDER BY m_id DESC";
	$res = dbquery($sql);

	while($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
		$row = db_cleanup($row);
		$message = array();
		$message["id"] = $row["m_id"];
		$message["to"] = $row["m_to"];
		$message["from"] = $row["m_from"];
		$message["subject"] = stripslashes($row["m_subject"]);
		$message["body"] = stripslashes($row["m_body"]);
		$message["created"] = $row["m_created"];
		$messages[] = $message;
	}
	return $messages;
}

/**
 * stores a new favorite in the database
 * @param array $favorite	the favorite array of the favorite to add
 */
function addFavorite($favorite) {
	global $TBLPREFIX, $DBCONN;

	// -- make sure a favorite is added
	if (empty($favorite["gid"]) && empty($favorite["url"])) return false;

	//-- make sure this is not a duplicate entry
	$sql = "SELECT * FROM ".$TBLPREFIX."favorites WHERE ";
	if (!empty($favorite["gid"])) $sql .= "fv_gid='".$DBCONN->escapeSimple($favorite["gid"])."' ";
	if (!empty($favorite["url"])) $sql .= "fv_url='".$DBCONN->escapeSimple($favorite["url"])."' ";
	$sql .= "AND fv_file='".$DBCONN->escapeSimple($favorite["file"])."' AND fv_username='".$DBCONN->escapeSimple($favorite["username"])."'";
	$res =& dbquery($sql);
	if ($res->numRows()>0) return false;

	//-- get the next favorite id number for the primary key
	$newid = get_next_id("favorites", "fv_id");

	//-- add the favorite to the database
	$sql = "INSERT INTO ".$TBLPREFIX."favorites VALUES ($newid, '".$DBCONN->escapeSimple($favorite["username"])."'," .
			"'".$DBCONN->escapeSimple($favorite["gid"])."','".$DBCONN->escapeSimple($favorite["type"])."'," .
			"'".$DBCONN->escapeSimple($favorite["file"])."'," .
			"'".$DBCONN->escapeSimple($favorite["url"])."'," .
			"'".$DBCONN->escapeSimple($favorite["title"])."'," .
			"'".$DBCONN->escapeSimple($favorite["note"])."')";
	$res = dbquery($sql);

	if ($res) return true;
	else return false;
}

/**
 * deleteFavorite
 * deletes a favorite in the database
 * @param int $fv_id	the id of the favorite to delete
 */
function deleteFavorite($fv_id) {
	global $TBLPREFIX;

	$sql = "DELETE FROM ".$TBLPREFIX."favorites WHERE fv_id=".$fv_id;
	$res = dbquery($sql);

	if ($res) return true;
	else return false;
}

/**
 * Get a user's favorites
 * Return an array of a users messages
 * @param string $username		the username to get the favorites for
 */
function getUserFavorites($username) {
	global $TBLPREFIX, $GEDCOMS, $DBCONN, $CONFIGURED;

	$favorites = array();
	//-- make sure we don't try to look up favorites for unconfigured sites
	if (!$CONFIGURED || DB::isError($DBCONN)) return $favorites;

	$sql = "SELECT * FROM ".$TBLPREFIX."favorites WHERE fv_username='".$DBCONN->escapeSimple($username)."'";
	$res = dbquery($sql);

	if (!$res) return $favorites;
	while($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
		$row = db_cleanup($row);
		if (isset($GEDCOMS[$row["fv_file"]])) {
			$favorite = array();
			$favorite["id"] = $row["fv_id"];
			$favorite["username"] = $row["fv_username"];
			$favorite["gid"] = $row["fv_gid"];
			$favorite["type"] = $row["fv_type"];
			$favorite["file"] = $row["fv_file"];
			$favorite["title"] = $row["fv_title"];
			$favorite["note"] = $row["fv_note"];
			$favorite["url"] = $row["fv_url"];
			$favorites[] = $favorite;
		}
	}
	$res->free();
	return $favorites;
}

/**
 * get blocks for the given username
 *
 * retrieve the block configuration for the given user
 * if no blocks have been set yet, and the username is a valid user (not a gedcom) then try and load
 * the defaultuser blocks.
 * @param string $username	the username or gedcom name for the blocks
 * @return array	an array of the blocks.  The two main indexes in the array are "main" and "right"
 */
function getBlocks($username) {
	global $TBLPREFIX, $GEDCOMS, $DBCONN;

	$blocks = array();
	$blocks["main"] = array();
	$blocks["right"] = array();
	$sql = "SELECT * FROM ".$TBLPREFIX."blocks WHERE b_username='".$DBCONN->escapeSimple($username)."' ORDER BY b_location, b_order";
	$res = dbquery($sql);

	if ($res->numRows() > 0) {
		while($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$row = db_cleanup($row);
			if (!isset($row["b_config"])) $row["b_config"]="";
			if ($row["b_location"]=="main") $blocks["main"][$row["b_order"]] = array($row["b_name"], unserialize($row["b_config"]));
			if ($row["b_location"]=="right") $blocks["right"][$row["b_order"]] = array($row["b_name"], unserialize($row["b_config"]));
		}
	}
	else {
		$user = getUser($username);
		if ($user) {
			//-- if no blocks found, check for a default block setting
			$sql = "SELECT * FROM ".$TBLPREFIX."blocks WHERE b_username='defaultuser' ORDER BY b_location, b_order";
			$res2 =& dbquery($sql);
			while($row =& $res2->fetchRow(DB_FETCHMODE_ASSOC)){
				$row = db_cleanup($row);
				if (!isset($row["b_config"])) $row["b_config"]="";
				if ($row["b_location"]=="main") $blocks["main"][$row["b_order"]] = array($row["b_name"], unserialize($row["b_config"]));
				if ($row["b_location"]=="right") $blocks["right"][$row["b_order"]] = array($row["b_name"], unserialize($row["b_config"]));
			}
			$res2->free();
		}
	}
	$res->free();
	return $blocks;
}

/**
 * Set Blocks
 *
 * Sets the blocks for a gedcom or user portal
 * the $setdefault parameter tells the program to also store these blocks as the blocks used by default
 * @param String $username the username or gedcom name to update the blocks for
 * @param array $ublocks the new blocks to set for the user or gedcom
 * @param boolean $setdefault	if true tells the program to also set these blocks as the blocks for the defaultuser
 */
function setBlocks($username, $ublocks, $setdefault=false) {
	global $TBLPREFIX, $DBCONN;

	$sql = "DELETE FROM ".$TBLPREFIX."blocks WHERE b_username='".$DBCONN->escapeSimple($username)."' AND b_name!='faq'";
	$res = dbquery($sql);

	foreach($ublocks["main"] as $order=>$block) {
		$newid = get_next_id("blocks", "b_id");
		$sql = "INSERT INTO ".$TBLPREFIX."blocks VALUES ($newid, '".$DBCONN->escapeSimple($username)."', 'main', '$order', '".$DBCONN->escapeSimple($block[0])."', '".$DBCONN->escapeSimple(serialize($block[1]))."')";
		$res = dbquery($sql);

		if ($setdefault) {
			$newid = get_next_id("blocks", "b_id");
			$sql = "INSERT INTO ".$TBLPREFIX."blocks VALUES ($newid, 'defaultuser', 'main', '$order', '".$DBCONN->escapeSimple($block[0])."', '".$DBCONN->escapeSimple(serialize($block[1]))."')";
			$res = dbquery($sql);

		}
	}
	foreach($ublocks["right"] as $order=>$block) {
		$newid = get_next_id("blocks", "b_id");
		$sql = "INSERT INTO ".$TBLPREFIX."blocks VALUES ($newid, '".$DBCONN->escapeSimple($username)."', 'right', '$order', '".$DBCONN->escapeSimple($block[0])."', '".$DBCONN->escapeSimple(serialize($block[1]))."')";
		$res = dbquery($sql);

		if ($setdefault) {
			$newid = get_next_id("blocks", "b_id");
			$sql = "INSERT INTO ".$TBLPREFIX."blocks VALUES ($newid, 'defaultuser', 'right', '$order', '".$DBCONN->escapeSimple($block[0])."', '".$DBCONN->escapeSimple(serialize($block[1]))."')";
			$res = dbquery($sql);

		}
	}
}

/**
 * Adds a news item to the database
 *
 * This function adds a news item represented by the $news array to the database.
 * If the $news array has an ["id"] field then the function assumes that it is
 * as update of an older news item.
 *
 * @author John Finlay
 * @param array $news a news item array
 */
function addNews($news) {
	global $TBLPREFIX, $DBCONN;

	if (!isset($news["date"])) $news["date"] = time()-$_SESSION["timediff"];
	//$sql = "CREATE TABLE ".$TBLPREFIX."news (n_id INT NOT NULL auto_increment, n_username VARCHAR(100), n_date INT, n_text TEXT, PRIMARY KEY(n_id))";
	if (!empty($news["id"])) {
		// In case news items are added from usermigrate, it will also contain an ID.
		// So we check first if the ID exists in the database. If not, insert instead of update.
		$sql = "SELECT * FROM ".$TBLPREFIX."news where n_id=".$news["id"];
		$res = dbquery($sql);

		if ($res->numRows() == 0) {
			$sql = "INSERT INTO ".$TBLPREFIX."news VALUES (".$news["id"].", '".$DBCONN->escapeSimple($news["username"])."','".$DBCONN->escapeSimple($news["date"])."','".$DBCONN->escapeSimple($news["title"])."','".$DBCONN->escapeSimple($news["text"])."')";
		}
		else {
			$sql = "UPDATE ".$TBLPREFIX."news SET n_date='".$DBCONN->escapeSimple($news["date"])."', n_title='".$DBCONN->escapeSimple($news["title"])."', n_text='".$DBCONN->escapeSimple($news["text"])."' WHERE n_id=".$news["id"];
		}
		$res->free();
	}
	else {
		$newid = get_next_id("news", "n_id");
		$sql = "INSERT INTO ".$TBLPREFIX."news VALUES ($newid, '".$DBCONN->escapeSimple($news["username"])."','".$DBCONN->escapeSimple($news["date"])."','".$DBCONN->escapeSimple($news["title"])."','".$DBCONN->escapeSimple($news["text"])."')";
	}
	$res = dbquery($sql);

	if ($res) return true;
	else return false;
}

/**
 * Deletes a news item from the database
 *
 * @author John Finlay
 * @param int $news_id the id number of the news item to delete
 */
function deleteNews($news_id) {
	global $TBLPREFIX;

	$sql = "DELETE FROM ".$TBLPREFIX."news WHERE n_id=".$news_id;
	$res = dbquery($sql);

	if ($res) return true;
	else return false;
}

/**
 * Gets the news items for the given user or gedcom
 *
 * @param String $username the username or gedcom file name to get news items for
 */
function getUserNews($username) {
	global $TBLPREFIX, $DBCONN;

	$news = array();
	$sql = "SELECT * FROM ".$TBLPREFIX."news WHERE n_username='".$DBCONN->escapeSimple($username)."' ORDER BY n_date DESC";
	$res = dbquery($sql);

	while($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
		$row = db_cleanup($row);
		$n = array();
		$n["id"] = $row["n_id"];
		$n["username"] = $row["n_username"];
		$n["date"] = $row["n_date"];
		$n["title"] = stripslashes($row["n_title"]);
		$n["text"] = stripslashes($row["n_text"]);
		$n["anchor"] = "article".$row["n_id"];
		$news[$row["n_id"]] = $n;
	}
	$res->free();
	return $news;
}

/**
 * Gets the news item for the given news id
 *
 * @param int $news_id the id of the news entry to get
 */
function getNewsItem($news_id) {
	global $TBLPREFIX;

	$news = array();
	$sql = "SELECT * FROM ".$TBLPREFIX."news WHERE n_id='$news_id'";
	$res = dbquery($sql);

	while($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
		$row = db_cleanup($row);
		$n = array();
		$n["id"] = $row["n_id"];
		$n["username"] = $row["n_username"];
		$n["date"] = $row["n_date"];
		$n["title"] = stripslashes($row["n_title"]);
		$n["text"] = stripslashes($row["n_text"]);
		$n["anchor"] = "article".$row["n_id"];
		$res->free();
		return $n;
	}
}

?>
