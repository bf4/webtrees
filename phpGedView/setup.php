<?php
/**
 * Setup Wizard
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
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
 * @package webtrees
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'setup.php');
define('WT_CONFIG_FILE', 'config.ini.php');
define('WT_REQUIRED_MYSQL_VERSION', '5.0.13'); // For: prepared statements within stored procedures

if (!empty($_POST['action']) && $_POST['action']=='download') {
	header('Content-Type: text/plain');
	header('Content-Disposition: attachment; filename="config.inc.php"');
	echo '; <?php exit; ?> DO NOT DELETE THIS LINE'."\r\n";
	echo 'dbhost="', addcslashes($_POST['dbhost'], '"').'"'."\r\n";
	echo 'dbport="', addcslashes($_POST['dbport'], '"').'"'."\r\n";
	echo 'dbuser="', addcslashes($_POST['dbuser'], '"').'"'."\r\n";
	echo 'dbpass="', addcslashes($_POST['dbpass'], '"').'"'."\r\n";
	echo 'dbname="', addcslashes($_POST['dbname'], '"').'"'."\r\n";
	echo 'tblpfx="', addcslashes($_POST['tblpfx'], '"').'"'."\r\n";
	exit;
}

echo
	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
	'<html xmlns="http://www.w3.org/1999/xhtml">',
	'<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />',
	'<title>Webtrees setup wizard</title>',
	'</head><body>';

if (version_compare(PHP_VERSION, '5.2')<0) {
	// Our translation system requires PHP 5.2, so we cannot translate this message :-(
	echo '<p>This server is running PHP version ', PHP_VERSION, '</p>';
	echo '<p>Webtrees requires PHP 5.2 or later.  PHP 5.3 is recommended.</p>';
	if (version_compare(PHP_VERSION, '5.0')<0) {
		echo '<p>Many servers offer both PHP4 and PHP5.  You may be able to change your default to PHP5 using a control panel or a configuration setting.</p>';
	}
	exit;
}

// This script (uniquely) does not load session.php.
// session.php won't run until a configuration file exists...
// This next block of code is a minimal version of session.php
define('WT_WEBTREES', true);
define('WT_ROOT', '');
define('WT_GED_ID', 0);
set_include_path('library'.PATH_SEPARATOR.get_include_path());
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();
require 'includes/functions/functions.php';
require 'includes/functions/functions_edit.php';
require 'includes/classes/class_i18n.php';
define('WT_LOCALE', i18n::init());

echo '<h1>', i18n::translate('Webtrees setup wizard'), '</h1>';

if (file_exists(WT_CONFIG_FILE)) {
	echo '<p>', i18n::translate('The configuration file %s has been successfully uploaded to the server.', WT_CONFIG_FILE), '</p>';
	echo '<p>', i18n::translate('Checking the file permissions...'), '</p>';
	if (is_readable(WT_CONFIG_FILE)) {
		echo '<p>', i18n::translate('The file has read permission.  Good.'), '</p>';
		if (is_writable(WT_CONFIG_FILE)) {
			echo '<p>', i18n::translate('The file has write permission.  This is OK, but for better security, you should make it read only.'), '</p>';
		} else {
			echo '<p>', i18n::translate('The file does not have write permission.  Good.'), '</p>';
		}
	} else {
		echo '<p>', i18n::translate('The file does not have read permission.  Remember that the file needs to be readable by the webserver.'), '</p>';
	}
	echo '<p><a href="', WT_SCRIPT_NAME, '">', i18n::translate('Check the file\'s permissions again'), '</a></p>';
	echo '<p><a href="index.php">', i18n::translate('Start using webtrees'), '</a></p>';
	// The config file exists - do not go any further.
	// This is an important security feature, to protect existing installations.
	exit;
}

echo '<form name="config" action="', WT_SCRIPT_NAME, '" method="post">';
echo '<input type="hidden" name="lang" value="', WT_LOCALE, '">';

////////////////////////////////////////////////////////////////////////////////
// Step one - choose language and confirm server configuration
////////////////////////////////////////////////////////////////////////////////

if (empty($_POST['maxcpu']) || empty($_POST['maxmem'])) {
	echo
		'<p>', i18n::translate('Change language'), ' ',
		edit_field_language('change_lang', WT_LOCALE, 'onChange="parent.location=\''.WT_SCRIPT_NAME.'?lang=\'+this.value;">'),
		'</p>',
		'<h2>', i18n::translate('Checking server configuration'), '</h2>';
	$warnings=false;
	$errors=false;

	// Mandatory extensions
	foreach (array('pcre', 'pdo', 'pdo_mysql', 'session') as $extension) {
		if (!extension_loaded($extension)) {
			echo '<p>', i18n::translate('PHP extension "%s" is disabled.  You cannot install webtrees until this is enabled.  Please ask your server\'s administrator to enable it.', $extension), '</p>';
			$errors=true;
		}
	}
	// Recommended extensions
	foreach (array(
		'calendar'=>i18n::translate('jewish calendar'),
		'gd'      =>i18n::translate('creating thumbnails of images'),
		'dom'     =>i18n::translate('exporting data in xml format'),
		'xml'     =>i18n::translate('reporting'),
	) as $extension=>$features) {
		if (!extension_loaded($extension)) {
			echo '<p>', i18n::translate('PHP extension "%s" is disabled.  Without it, the following features will not work: %s.  Please ask your server\'s administrator to enable it.', $extension, $features), '</p>';
			$warnings=true;
		}
	}
	// Settings
	foreach (array(
		'file_uploads'=>i18n::translate('upload files'),
	) as $setting=>$features) {
		if (ini_get($setting)==false) {
			echo '<p>', i18n::translate('PHP setting "%s" is disabled. Without it, the following features will not work: %s.  Please ask your server\'s administrator to enable it.', $setting, $features), '</p>';
			$warnings=true;
		}
	}
	if (!$warnings && !$errors) {
		echo '<p>', i18n::translate('The server configuration is OK.'), '</p>';
	}
	echo '<h2>', i18n::translate('Checking server capacity'), '</h2>';
	// Memory
	$mem=to_mb(ini_get('memory_limit'));
	$maxmem=$mem;
	for ($i=$mem+1; $i<=1024; ++$i) {
		@ini_set('memory_limit', $i.'M');
		$newmem=to_mb(ini_get('memory_limit'));
		if ($newmem>$mem) {
			$maxmem=$newmem;
		} else {
			break;
		}
	}
	// CPU
	$cpu=ini_get('max_execution_time');
	$maxcpu=$cpu;
	for ($i=$cpu+1; $i<=300; ++$i) {
		@ini_set('max_execution_time', $i);
		$newcpu=ini_get('max_execution_time');
		if ($newcpu>$cpu) {
			$maxcpu=$newcpu;
		} else {
			break;
		}
	}
	echo
		'<p>',
		i18n::translate('The memory and CPU time requirements depend on the number of individuals in your family tree.'),
		'<br/>',
		i18n::translate('The following list shows typical requirements.'),
		'</p><p>',
		i18n::translate('Small systems (500 individuals): 16-32MB, 10-20 seconds'),
		'<br/>',
		i18n::translate('Medium systems (5000 individuals): 32-64MB, 20-40 seconds'),
		'<br/>',
		i18n::translate('Large systems (50000 individuals): 64-128MB, 40-80 seconds'),
		'</p><p>',
		i18n::translate('This server\'s memory limit is %dMB and its CPU time limit is %d seconds.', $maxmem, $maxcpu),
		'</p>';

	if ($maxmem<32 || $maxcpu<20) {
		echo '<p>', i18n::translate('If you try to exceed these limits, you may experience server time-outs and blank pages.'), '</p>';
	}
	echo '<p>', i18n::translate('To increase these limits, you should contact your server\'s administrator.'), '</p>';
	if (!$errors) {
		echo '<input type="hidden" name="maxcpu" value="'.$maxcpu.'">';
		echo '<input type="hidden" name="maxmem" value="'.$maxmem.'">';
		echo '<br/><hr/><input type="submit" value="'.i18n::translate('continue').'">';

	}
	echo '</form></body></html>';
	exit;
} else {
	// Copy these values through to the next step
	echo '<input type="hidden" name="maxcpu" value="'.$_POST['maxcpu'].'">';
	echo '<input type="hidden" name="maxmem" value="'.$_POST['maxmem'].'">';
	@ini_set('max_execution_time', $_POST['maxcpu']);
	@ini_set('memory_limit', $_POST['maxmem'].'M');
}

////////////////////////////////////////////////////////////////////////////////
// Step two - Database connection.
////////////////////////////////////////////////////////////////////////////////

if (empty($_POST['dbhost'])) $_POST['dbhost']='localhost';
if (empty($_POST['dbport'])) $_POST['dbport']='3306';
if (empty($_POST['dbuser'])) $_POST['dbuser']='';
if (empty($_POST['dbpass'])) $_POST['dbpass']='';

try {
	$db_version_ok=false;
	$dbh=new PDO('mysql:host='.$_POST['dbhost'].';port='.$_POST['dbport'], $_POST['dbuser'], $_POST['dbpass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ, PDO::ATTR_CASE=>PDO::CASE_LOWER, PDO::ATTR_AUTOCOMMIT=>true));
	$dbh->exec("SET NAMES 'utf8'");
	foreach ($dbh->query("SHOW VARIABLES LIKE 'VERSION'") as $row) {
		if (version_compare($row->value, WT_REQUIRED_MYSQL_VERSION, '<')) {
			echo '<p>', i18n::translate('This database is only running MySQL version %s.  You cannot install webtrees here.', $row->value), '</p>';
		} else {
			$db_version_ok=true;
		}
	}
} catch (PDOException $ex) {
	$dbh=null;
	if ($_POST['dbuser']) {
		// If we've supplied a login, then show the error
		echo
			'<p>', i18n::translate('Unable to connect using these settings.  Your server gave the following error.'), '<p>',
			'<pre>', $ex->getMessage(), '</pre>',
			'<p>', i18n::translate('Check the settings and try again.'), '</p>';
	}
}

if (!$dbh || !$db_version_ok) {
	echo
		'<h2>', i18n::translate('Connection to database server'), '</h2>',
		'<p>', i18n::translate('Webtrees needs a MySQL database, version %s or later.', WT_REQUIRED_MYSQL_VERSION), '</p>',
		'<p>', i18n::translate('Your server\'s administrator will provide you with the connection details.'), '</p>',
		'<fieldset><legend>', i18n::translate('database connection'), '</legend>',
		'<table border="0"><tr><td>',
		i18n::translate('server name'), '</td><td>',
		'<input type="text" name="dbhost" value="', $_POST['dbhost'], '"></td><td>',
		i18n::translate('Most sites are configured to use localhost.  This means that your database runs on the same computer as your web server.'),
		'</td></tr><tr><td>',
		i18n::translate('port number'), '</td><td>',
		'<input type="text" name="dbport" value="', $_POST['dbport'], '"></td><td>',
		i18n::translate('Most sites are configured to use the default value of 3306.'),
		'</td></tr><tr><td>',
		i18n::translate('database user account'), '</td><td>',
		'<input type="text" name="dbuser" value="', $_POST['dbuser'], '"></td><td>',
		i18n::translate('This is case sensitive.'),
		'</td></tr><tr><td>',
		i18n::translate('database password'), '</td><td>',
		'<input type="text" name="dbpass" value="', $_POST['dbpass'], '"></td><td>',
		i18n::translate('This is case sensitive.'),
		'</td></tr><tr><td>',
		'</td></tr></table>',
		'</fieldset>',
		'<br/><hr/><input type="submit" value="'.i18n::translate('continue').'">',
		'</form></body></html>';
		exit;
} else {
	// Copy these values through to the next step
	echo '<input type="hidden" name="dbhost" value="'.$_POST['dbhost'].'">';
	echo '<input type="hidden" name="dbport" value="'.$_POST['dbport'].'">';
	echo '<input type="hidden" name="dbuser" value="'.$_POST['dbuser'].'">';
	echo '<input type="hidden" name="dbpass" value="'.$_POST['dbpass'].'">';
}

////////////////////////////////////////////////////////////////////////////////
// Step three - Database connection.
////////////////////////////////////////////////////////////////////////////////

if (empty($_POST['dbname'])) $_POST['dbname']='';
if (empty($_POST['tblpfx'])) $_POST['tblpfx']='wt_';

$dbname_ok=false;
if ($_POST['dbname']) {
	try {
		// Try to create the database, if it does not exist.
		$dbh->exec('CREATE DATABASE IF NOT EXISTS `'.$_POST['dbname'].'` COLLATE utf8_unicode_ci');
		$dbh->exec('USE `'.$_POST['dbname'].'`');
		$dbname_ok=true;
	} catch (PDOException $ex) {
		echo
			'<p>', i18n::translate('Unable to connect using these settings.  Your server gave the following error.'), '<p>',
			'<pre>', $ex->getMessage(), '</pre>',
			'<p>', i18n::translate('Check the settings and try again.'), '</p>';
	}
}

if (!$dbname_ok) {
	echo
		'<h2>', i18n::translate('Database and table names'), '</h2>',
		'<p>', i18n::translate('A database server can store many separate databases.  You need to select an existing database (created by your server\'s administrator) or create a new one (if your database user account has sufficient privileges).'), '</p>',
		'<fieldset><legend>', i18n::translate('database name'), '</legend>',
		'<table border="0"><tr><td>',
		i18n::translate('database name'), '</td><td>',
		'<input type="text" name="dbname" value="', $_POST['dbname'], '"></td><td>',
		 i18n::translate('This is case sensitive.'),
		'</td></tr><tr><td>',
		i18n::translate('table prefix'), '</td><td>',
		'<input type="text" name="tblpfx" value="', $_POST['tblpfx'], '"></td><td>',
		i18n::translate('The prefix is optional, but recommended.  By giving the table names a unique prefix, you can let several different applications share the same database.'),
		'</td></tr></table>',
		'</fieldset>',
		'<br/><hr/><input type="submit" value="'.i18n::translate('continue').'">',
		'</form></body></html>';
		exit;
} else {
	// Copy these values through to the next step
	echo '<input type="hidden" name="dbname" value="'.$_POST['dbname'].'">';
	echo '<input type="hidden" name="tblpfx" value="'.$_POST['tblpfx'].'">';
}

////////////////////////////////////////////////////////////////////////////////
// Step four - site setup data
////////////////////////////////////////////////////////////////////////////////

if (empty($_POST['wtname'    ])) $_POST['wtname'    ]='';
if (empty($_POST['wtuser'    ])) $_POST['wtuser'    ]='';
if (empty($_POST['wtpass'    ])) $_POST['wtpass'    ]='';
if (empty($_POST['wtpass2'   ])) $_POST['wtpass2'   ]='';
if (empty($_POST['wtemail'   ])) $_POST['wtemail'   ]='';
if (empty($_POST['smtpuse'   ])) $_POST['smtpuse'   ]='no';
if (empty($_POST['smtpserv'  ])) $_POST['smtpserv'  ]='localhost';
if (empty($_POST['smtpport'  ])) $_POST['smtpport'  ]='25';
if (empty($_POST['smtpusepw' ])) $_POST['smtpusepw' ]='yes';
if (empty($_POST['smtpuser'  ])) $_POST['smtpuser'  ]='';
if (empty($_POST['smtppass'  ])) $_POST['smtppass'  ]='';
if (empty($_POST['smtpsecure'])) $_POST['smtpsecure']='none';
if (empty($_POST['smtpfrom'  ])) $_POST['smtpfrom'  ]='';
if (empty($_POST['smtpsender'])) $_POST['smtpsender']='';

if (empty($_POST['wtname']) || empty($_POST['wtuser']) || strlen($_POST['wtpass'])<6 || strlen($_POST['wtpass2'])<6 || empty($_POST['wtemail']) || $_POST['wtpass']<>$_POST['wtpass2']) {
	if (strlen($_POST['wtpass'])>0 && strlen($_POST['wtpass'])<6) {
		echo '<p>', i18n::translate('The password needs to be at least six characters long.'), '</p>';
	} elseif ($_POST['wtpass']<>$_POST['wtpass2']) {
		echo '<p>', i18n::translate('The passwords do not match.'), '</p>';
	} elseif ((empty($_POST['wtname']) || empty($_POST['wtuser']) || empty($_POST['wtpass']) || empty($_POST['wtemail'])) && $_POST['wtname'].$_POST['wtuser'].$_POST['wtpass'].$_POST['wtemail']!='') {
		echo '<p>', i18n::translate('You must enter all the administrator account fields.'), '</p>';
	}
	echo
		'<h2>', i18n::translate('System settings'), '</h2>',
		'<p>', i18n::translate('You need to set up an administrator account.  This account can control all aspects of this webtrees installation.  Please choose a strong password.'), '</p>',
		'<fieldset><legend>', i18n::translate('administrator account'), '</legend>',
		'<table border="0"><tr><td>',
		i18n::translate('your name'), '</td><td>',
		'<input type="text" name="wtname" value="', $_POST['wtname'], '"></td><td>',
		i18n::translate('This is your real name, as you would like it displayed on screen.'),
		'</td></tr><tr><td>',
		i18n::translate('login id'), '</td><td>',
		'<input type="text" name="wtuser" value="', $_POST['wtuser'], '"></td><td>',
		i18n::translate('You will use this to login to webtrees.'),
		'</td></tr><tr><td>',
		i18n::translate('password'), '</td><td>',
		'<input type="password" name="wtpass" value="', $_POST['wtpass'], '"></td><td>',
		i18n::translate('This needs to be at least six characters.  It is case-sensitive.'),
		'</td></tr><tr><td>',
		'&nbsp;', '</td><td>',
		'<input type="password" name="wtpass2" value="', $_POST['wtpass2'], '"></td><td>',
		i18n::translate('Type your password again, to make sure you have typed it correctly.'),
		'</td></tr><tr><td>',
		i18n::translate('email address'), '</td><td>',
		'<input type="text" name="wtemail" value="', $_POST['wtemail'], '"></td><td>',
		i18n::translate('This will be used to send you password reminders, site notifications and messages from other family members who register on your site.'),
		'</td></tr><tr><td>',
		'</td></tr></table>',
		'</fieldset>',
		'<p>', i18n::translate('Webtrees needs to send emails, such as password reminders and site notifications.  To do this, it needs to connect to an SMTP (mail-relay) service.  If your server provides this server, enter the details here.  If it does not, most email providers will allow you to use their SMTP service.  Check with their support documentation for detail.'), '</p>',
		'<p>', i18n::translate('To use a Google mail account, use the following settings: server=smtp.gmail.com, port=587, security=tls, username=xxxxx@gmail.com'), '</p>',
		'<p>', i18n::translate('If you do not know these settings, leave the default values.  They may work.  You can change them later.'), '</p>',
		'<fieldset><legend>', i18n::translate('SMTP mail server'), '</legend>',
		'<table border="0"><tr><td>',
		i18n::translate('use SMTP'), '</td><td>',
		'<input type="text" name="smtpuse" value="', $_POST['smtpuse'], '"></td><td>',
		i18n::translate('If you don\'t want to send mail, for example when running webtrees with a single user or on a standalone compter, you can disable this feature.'),
		'</td></tr><tr><td>',
		i18n::translate('server'), '</td><td>',
		'<input type="text" name="smtpserv" value="', $_POST['smtpserv'], '"></td><td>',
		i18n::translate('This is the name of the SMTP server.  localhost means that the mail service is running on the same computer as your web server.'),
		'</td></tr><tr><td>',
		i18n::translate('port'), '</td><td>',
		'<input type="text" name="smtpport" value="', $_POST['smtpport'], '"></td><td>',
		i18n::translate('By default, SMTP works on port 25.'),
		'</td></tr><tr><td>',
		i18n::translate('use password'), '</td><td>',
		'<input type="text" name="smtpusepw" value="', $_POST['smtpusepw'], '"></td><td>',
		i18n::translate('Most SMTP servers require a password.'),
		'</td></tr><tr><td>',
		i18n::translate('username'), '</td><td>',
		'<input type="text" name="smtpuser" value="', $_POST['smtpuser'], '"></td><td>',
		'&nbsp;',
		'</td></tr><tr><td>',
		i18n::translate('password'), '</td><td>',
		'<input type="text" name="smtppass" value="', $_POST['smtppass'], '"></td><td>',
		'&nbsp;',
		'</td></tr><tr><td>',
		i18n::translate('security'), '</td><td>',
		'<input type="text" name="smtpsecure" value="', $_POST['smtpsecure'], '"></td><td>',
		i18n::translate('Most servers do not use secure connections.'),
		'</td></tr><tr><td>',
		i18n::translate('from domain'), '</td><td>',
		'<input type="text" name="smtpfrom" value="', $_POST['smtpfrom'], '"></td><td>',
		i18n::translate('This is used in the "From:" header when sending mails.'),
		'</td></tr><tr><td>',
		i18n::translate('sender domain'), '</td><td>',
		'<input type="text" name="smtpsender" value="', $_POST['smtpsender'], '"></td><td>',
		i18n::translate('This is used in the "Sender:" header when sending mails.  It is often the same as the "From:" header.'),
		'</td></tr><tr><td>',
		'</td></tr></table>',
		'</fieldset>',
		'<br/><hr/><input type="submit" value="'.i18n::translate('continue').'">',
		'</form></body></html>';
		exit;
} else {
	// Copy these values through to the next step
	echo '<input type="hidden" name="wtname"     value="'.$_POST['wtname'].'">';
	echo '<input type="hidden" name="wtuser"     value="'.$_POST['wtuser'].'">';
	echo '<input type="hidden" name="wtpass"     value="'.$_POST['wtpass'].'">';
	echo '<input type="hidden" name="wtpass2"    value="'.$_POST['wtpass2'].'">';
	echo '<input type="hidden" name="wtemail"    value="'.$_POST['wtemail'].'">';
	echo '<input type="hidden" name="smtpuse"    value="'.$_POST['smtpuse'].'">';
	echo '<input type="hidden" name="smtpserv"   value="'.$_POST['smtpserv'].'">';
	echo '<input type="hidden" name="smtpport"   value="'.$_POST['smtpport'].'">';
	echo '<input type="hidden" name="smtpusepw"  value="'.$_POST['smtpusepw'].'">';
	echo '<input type="hidden" name="smtpuser"   value="'.$_POST['smtpuser'].'">';
	echo '<input type="hidden" name="smtppass"   value="'.$_POST['smtppass'].'">';
	echo '<input type="hidden" name="smtpsecure" value="'.$_POST['smtpsecure'].'">';
	echo '<input type="hidden" name="smtpfrom"   value="'.$_POST['smtpfrom'].'">';
	echo '<input type="hidden" name="smtpsender" value="'.$_POST['smtpsender'].'">';
}

////////////////////////////////////////////////////////////////////////////////
// Step five - We have a database connection.  Create the tables.
////////////////////////////////////////////////////////////////////////////////

try {
	// These shouldn't fail.
	$TBLPFX=$_POST['tblpfx'];
	//$dbh->exec("");
	//$dbh->exec("");
	echo
		'<p>', i18n::translate('Your system is almost ready for use.  The final step is to download a configuration file (config.ini.php) and copy this to the webtrees directory on your webserver.  This is a security measure to ensure only the website\'s owner can configure it.'), '</p>',
		'<input type="hidden" name="action" value="download">',
		'<input type="submit" value="'.i18n::translate('download configuration file').'">',
		'</form>',
		'<p>', i18n::translate('After you have copied this file to the webserver, click here to continue'), '</p>',
		'<form name="config" action="', WT_SCRIPT_NAME, '" method="get">',
		'<input type="submit" value="'.i18n::translate('continue').'">',
		'</form></body></html>';
} catch (PDOException $ex) {
	echo
		'<p>', i18n::translate('An unexpected database error occured.'), '</p>',
		'<pre>', $ex->getMessage(), '</pre>',
		'<p>', i18n::translate('The webtrees developers would be very interested to learn about this error.  If you contact them, they will help you resolve the problem.'), '</p>';
}
echo '</form>';
echo '</body>';
echo '</html>';

function to_mb($str) {
	if (substr($str, -1, 1)=='K') {
		return floor(substr($str, 0, strlen($str)-1)/1024);
	}
	if (substr($str, -1, 1)=='M') {
		return floor(substr($str, 0, strlen($str)-1));
	}
	if (substr($str, -1, 1)=='G') {
		return floor(1024*substr($str, 0, strlen($str)-1));
	}
	
}
