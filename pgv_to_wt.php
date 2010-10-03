<?php
/**
 * PGV to webtrees transfer wizard
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

define('WT_SCRIPT_NAME', 'pgv_to_wt.php');
require './includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';

// We can only import into an empty system, so deny access if we have already created a gedcom or added users.
if (WT_GED_ID || get_user_count()>1) {
	header('Location: index.php');
	exit;
}

// Must be logged in as an admin
if (!WT_USER_IS_ADMIN) {
	header('Location: login.php?url='.WT_SCRIPT_NAME);
	exit;
}

print_header(i18n::translate('PhpGedView to webtrees transfer wizard'));

echo
	'<style type="text/css">
		#container {width: 70%; margin:15px auto; border: 1px solid gray; padding: 10px;}
		#container dl {margin:0 0 40px 25px;}
		#container dt {display:inline; width: 320px; font-weight:normal; margin: 0 0 15px 0;}
		#container dd {color: #81A9CB; margin-bottom:20px;font-weight:bold;}
		#container p {color: #81A9CB; font-size: 14px; font-style: italic; font-weight:bold; padding: 0 5px 5px; align: top;}
		h2 {color: #81A9CB;}
		.good {color: green;}
		.bad {color: red !important;}
		.indifferent {color: blue;}
		#container p.pgv  {color: black; font-size: 12px; font-style: normal; font-weight:normal; padding:0; margin:10px 0 0 320px}
	</style>';

$error='';
$warning='';
$PGV_PATH=safe_POST('PGV_PATH');

if ($PGV_PATH) {
	if (!is_dir($PGV_PATH) || !is_readable($PGV_PATH.'/config.php')) {
		$error=i18n::translate('The specified directory does not contain an installation of PhpGedView');
	} else {
		// Load the configuration settings
		$config_php=file_get_contents($PGV_PATH.'/config.php');
		// The easiest way to do this is to exec() the file - but not lines containing require or PHP tags
		$config_php=preg_replace(
			array(
				'/^\s*(include|require).*/',
				'/.*<\?php.*/',
				'/.*\?>.*/'
			), '', $config_php
		);
		eval($config_php);
		// $INDEX_DIRECTORY can be either absolute or relative to the PhpGedView root.
		if (preg_match('/^(\/|\\|[A-Z]:)/', $INDEX_DIRECTORY)) {
			$INDEX_DIRECTORY=realpath($INDEX_DIRECTORY);
		} else {
			$INDEX_DIRECTORY=realpath($PGV_PATH.'/'.$INDEX_DIRECTORY);
		}
		$wt_config=parse_ini_file(WT_ROOT.'data/config.ini.php');
		if ($DBHOST!=$wt_config['dbhost'] || $DBHOST!=$wt_config['dbhost']) {
			$error=i18n::translate('PhpGedView must use the same database as <b>webtrees</b>');
			unset($wt_config);
		} else {
			unset($wt_config);
			try {
				$PGV_SCHEMA_VERSION=WT_DB::prepare(
					"SELECT site_setting_value FROM {$DBNAME}.{$TBLPREFIX}site_setting WHERE site_setting_name='PGV_SCHEMA_VERSION'"
				)->fetchOne();
				if ($PGV_SCHEMA_VERSION<10) {
					$error=i18n::translate('The version of %s is too old', 'PhpGedView');
				} elseif ($PGV_SCHEMA_VERSION>14) {
					$error=i18n::translate('The version of %s is too new', 'PhpGedView');
				}
			} catch (PDOException $ex) {
				$error=
					/* I18N: %s is a database name/identifier */
					i18n::translate('<b>webtrees</b> cannot connect to the PhpGedView database: %s.', $DBNAME.'@'.$DBHOST).
					'<br/>'.
					/* I18N: %s is an error message */
					i18n::translate('MySQL gave the error: %s', $ex->getMessage());
			}
		}
	}
}

if ($error || empty($PGV_PATH)) {
	// Prompt for location of PhpGedView installation
	echo '<div id="container">';
	echo
		'<h2>',
		i18n::translate('PhpGedView to <b>webtrees</b> transfer wizard'),
		help_link('PGV_WIZARD'),
		'</h2>';
	if ($error) {
		echo '<p class="bad">', $error, '</p>';
	}

	// Look for PGV in some nearby directories
	$pgv_dirs=array();
	$dir=opendir(realpath('..'));
	while (($subdir=readdir($dir))!==false) {
		if (is_dir('../'.$subdir) && preg_match('/pgv|gedview/i', $subdir) && file_exists('../'.$subdir.'/config.php')) {
			$pgv_dirs[]='../'.$subdir;
		}
	}
	closedir($dir);

	echo
		'<form action="', WT_SCRIPT_NAME, '" method="post">',
		'<p>', i18n::translate('Where is your PhpGedView installation?'), '</p>',
		'<dl>',
		'<dt>',i18n::translate('Installation directory'), '</dt>',
		'<dd><input type="text" name="PGV_PATH" size="40" value="'.htmlspecialchars($PGV_PATH).'">',
		'</dd>';
	echo '<dt>', /* find better english before translating */ 'PhpGedView might be found in these locations', '</dt>';
	echo '<dd>';
	foreach ($pgv_dirs as $pgvpath) {
		echo '<p class="pgv">', $pgvpath, '</p>';
	}
	echo
		'</dd>',
		'</dl>',
		'<div class="center"><input type="submit" value="'.i18n::translate('next').'"></div>',
		'</form>',
		'</div>';
	exit;
}

// Run in a transaction
WT_DB::exec("START TRANSACTION");

// Delete the existing user accounts, and any information associated with it
WT_DB::exec("UPDATE `##log` SET user_id=NULL");
WT_DB::exec("DELETE FROM `##change`");
WT_DB::exec("DELETE FROM `##block_setting`");
WT_DB::exec("DELETE FROM `##block`");
WT_DB::exec("DELETE FROM `##user_gedcom_setting`");
WT_DB::exec("DELETE FROM `##user_setting`");
WT_DB::exec("DELETE FROM `##message`");
WT_DB::exec("DELETE FROM `##user`");
WT_DB::exec("DELETE FROM `##user_setting`");
WT_DB::exec("DELETE FROM `##user`");

////////////////////////////////////////////////////////////////////////////////
if (ob_get_level() == 0) ob_start();
echo '<p>config.php => wt_site_setting ...</p>'; ob_flush(); flush(); usleep(50000);
// TODO May need to set 'DATA_DIRECTORY' to $INDEX_DIRECTORY when dealing with media??
@set_site_setting('STORE_MESSAGES',                  $PGV_STORE_MESSAGES);
@set_site_setting('SMTP_SIMPLE_MAIL',                $PGV_SIMPLE_MAIL);
@set_site_setting('USE_REGISTRATION_MODULE',         $USE_REGISTRATION_MODULE);
@set_site_setting('REQUIRE_ADMIN_AUTH_REGISTRATION', $REQUIRE_ADMIN_AUTH_REGISTRATION);
@set_site_setting('ALLOW_USER_THEMES',               $ALLOW_USER_THEMES);
@set_site_setting('ALLOW_CHANGE_GEDCOM',             $ALLOW_CHANGE_GEDCOM);
@set_site_setting('SESSION_TIME',                    $PGV_SESSION_TIME);
@set_site_setting('SMTP_ACTIVE',                     $PGV_SMTP_ACTIVE ? 'external' : 'internal');
@set_site_setting('SMTP_HOST',                       $PGV_SMTP_HOST);
@set_site_setting('SMTP_HELO',                       $PGV_SMTP_HELO);
@set_site_setting('SMTP_PORT',                       $PGV_SMTP_PORT);
@set_site_setting('SMTP_AUTH',                       $PGV_SMTP_AUTH);
@set_site_setting('SMTP_AUTH_USER',                  $PGV_SMTP_AUTH_USER);
@set_site_setting('SMTP_AUTH_PASS',                  $PGV_SMTP_AUTH_PASS);
@set_site_setting('SMTP_SSL',                        $PGV_SMTP_SSL);
@set_site_setting('SMTP_FROM_NAME',                  $PGV_SMTP_FROM_NAME);

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_site_setting => wt_site_setting ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##site_setting` (setting_name, setting_value)".
	" SELECT site_setting_name, site_setting_value FROM {$DBNAME}.{$TBLPREFIX}site_setting".
	" WHERE site_setting_name IN ('DEFAULT_GEDCOM', 'LAST_CHANGE_EMAIL')"
)->execute();

////////////////////////////////////////////////////////////////////////////////

if ($PGV_SCHEMA_VERSION>=12) {
echo '<p>pgv_gedcom => wt_gedcom ...</p>'; ob_flush(); flush(); usleep(50000);
	WT_DB::prepare(
		"INSERT INTO `##gedcom` (gedcom_id, gedcom_name)".
		" SELECT gedcom_id, gedcom_name FROM {$DBNAME}.{$TBLPREFIX}gedcom"
	)->execute();

	echo '<p>pgv_gedcom_setting => wt_gedcom_setting ...</p>'; ob_flush(); flush(); usleep(50000);
	WT_DB::prepare(
		"INSERT INTO `##gedcom_setting` (gedcom_id, setting_name, setting_value)".
		" SELECT gedcom_id, setting_name,".
		"  CASE setting_name".
		"  WHEN 'THEME_DIR' THEN".
		"   CASE setting_value".
		"   WHEN ''                    THEN ''".
		"   WHEN 'themes/cloudy/'      THEN 'themes/clouds/'".
		"   WHEN 'themes/minimal/'     THEN 'themes/minimal/'".
		"   WHEN 'themes/simplyblue/'  THEN 'themes/colors/'".
		"   WHEN 'themes/simplygreen/' THEN 'themes/colors/'".
		"   WHEN 'themes/simplyred/'   THEN 'themes/colors/'".
		"   WHEN 'themes/xenea/'       THEN 'themes/xenea/'".
		"   ELSE 'themes/webtrees/'". // ocean, simplyred/blue/green, standard, wood
		"  END".
		"  WHEN 'LANGUAGE' THEN".
		"   CASE setting_value".
		"   WHEN 'catalan'    THEN 'ca'".
		"   WHEN 'english'    THEN 'en_US'".
		"   WHEN 'english-uk' THEN 'en_GB'". // PGV had the config for en_GB, but no language files
		"   WHEN 'polish'     THEN 'pl'".
		"   WHEN 'italian'    THEN 'it'".
		"   WHEN 'spanish'    THEN 'es'".
		"   WHEN 'finnish'    THEN 'fi'".
		"   WHEN 'french'     THEN 'fr'".
		"   WHEN 'german'     THEN 'de'".
		"   WHEN 'danish'     THEN 'da'".
		"   WHEN 'portuguese' THEN 'pt'".
		"   WHEN 'hebrew'     THEN 'he'".
		"   WHEN 'estonian'   THEN 'et'".
		"   WHEN 'turkish'    THEN 'tr'".
		"   WHEN 'dutch'      THEN 'nl'".
		"   WHEN 'slovak'     THEN 'sk'".
		"   WHEN 'norwegian'  THEN 'nn'".
		"   WHEN 'slovenian'  THEN 'sl'".
		"   WHEN 'hungarian'  THEN 'hu'".
		"   WHEN 'swedish'    THEN 'sv'".
		"   WHEN 'russian'    THEN 'ru'".
		"   ELSE 'en_US'". // PGV supports other languages that webtrees does not (yet)
		"  END".
		"  ELSE setting_value".
		"  END".
		" FROM {$DBNAME}.{$TBLPREFIX}gedcom_setting".
		" WHERE setting_name NOT IN ('HOME_SITE_TEXT', 'HOME_SITE_URL')"
	)->execute();

	echo '<p>pgv_user => wt_user ...</p>'; ob_flush(); flush(); usleep(50000);
	try {
		// "INSERT IGNORE" is needed to allow for PGV users with duplicate emails.  Only the first will be imported.
		WT_DB::prepare(
			"INSERT IGNORE INTO `##user` (user_id, user_name, real_name, email, password)".
			" SELECT user_id, user_name, CONCAT_WS(' ', us1.setting_value, us2.setting_value), us3.setting_value, password FROM {$DBNAME}.{$TBLPREFIX}user".
			" LEFT JOIN {$DBNAME}.{$TBLPREFIX}user_setting us1 USING (user_id)".
			" LEFT JOIN {$DBNAME}.{$TBLPREFIX}user_setting us2 USING (user_id)".
			" JOIN {$DBNAME}.{$TBLPREFIX}user_setting us3 USING (user_id)".
			" WHERE us1.setting_name='firstname'".
			" AND us2.setting_name='lastname'".
			" AND us3.setting_name='email'"
		)->execute();
	} catch (PDOException $ex) {
		// Ignore duplicates
	}

	echo '<p>pgv_user_setting => wt_user_setting ...</p>'; ob_flush(); flush(); usleep(50000);
	WT_DB::prepare(
		"INSERT INTO `##user_setting` (user_id, setting_name, setting_value)".
		" SELECT user_id, setting_name,".
		" CASE setting_name".
		" WHEN 'language' THEN ".
		"  CASE setting_value".
		"  WHEN 'catalan'    THEN 'ca'".
		"  WHEN 'english'    THEN 'en_US'".
		"  WHEN 'english-uk' THEN 'en_GB'". // PGV had the config for en_GB, but no language files
		"  WHEN 'polish'     THEN 'pl'".
		"  WHEN 'italian'    THEN 'it'".
		"  WHEN 'spanish'    THEN 'es'".
		"  WHEN 'finnish'    THEN 'fi'".
		"  WHEN 'french'     THEN 'fr'".
		"  WHEN 'german'     THEN 'de'".
		"  WHEN 'danish'     THEN 'da'".
		"  WHEN 'portuguese' THEN 'pt'".
		"  WHEN 'hebrew'     THEN 'he'".
		"  WHEN 'estonian'   THEN 'et'".
		"  WHEN 'turkish'    THEN 'tr'".
		"  WHEN 'dutch'      THEN 'nl'".
		"  WHEN 'slovak'     THEN 'sk'".
		"  WHEN 'norwegian'  THEN 'nn'".
		"  WHEN 'slovenian'  THEN 'sl'".
		"  WHEN 'hungarian'  THEN 'hu'".
		"  WHEN 'swedish'    THEN 'sv'".
		"  WHEN 'russian'    THEN 'ru'".
		"  ELSE 'en_US'". // PGV supports other languages that webtrees does not (yet)
		"  END".
		" WHEN 'theme' THEN".
		"  CASE setting_value".
		"  WHEN ''                    THEN ''".
		"  WHEN 'themes/cloudy/'      THEN 'themes/clouds/'".
		"  WHEN 'themes/minimal/'     THEN 'themes/minimal/'".
		"  WHEN 'themes/simplyblue/'  THEN 'themes/colors/'".
		"  WHEN 'themes/simplygreen/' THEN 'themes/colors/'".
		"  WHEN 'themes/simplyred/'   THEN 'themes/colors/'".
		"  WHEN 'themes/xenea/'       THEN 'themes/xenea/'".
		"  ELSE 'themes/webtrees/'". // ocean, simplyred/blue/green, standard, wood
		" END".
		" ELSE".
		"  CASE".
		"  WHEN setting_value IN ('Y', 'yes') THEN 1 WHEN setting_value IN ('N', 'no') THEN 0 ELSE setting_value END".
		" END".
		" FROM {$DBNAME}.{$TBLPREFIX}user_setting".
		" JOIN `##user` USING (user_id)".
		" WHERE setting_name NOT IN ('email', 'firstname', 'lastname')"
	)->execute();

	echo '<p>pgv_user_gedcom_setting => wt_user_gedcom_setting ...</p>'; ob_flush(); flush(); usleep(50000);
	WT_DB::prepare(
		"INSERT INTO `##user_gedcom_setting` (user_id, gedcom_id, setting_name, setting_value)".
		" SELECT user_id, gedcom_id, setting_name, setting_value FROM {$DBNAME}.{$TBLPREFIX}user_gedcom_setting".
		" JOIN `##user` USING (user_id)"
	)->execute();

} else {
	// Copied from PGV's db_schema_11_12
	if (file_exists("{$INDEX_DIRECTORY}/gedcoms.php")) {
		require_once "{$INDEX_DIRECTORY}/gedcoms.php";
		$file=$INDEX_DIRECTORY.'/gedcoms.php';
		echo '<p>', $file, ' => wt_gedcom ...</p>'; ob_flush(); flush(); usleep(50000);
		if (isset($GEDCOMS) && is_array($GEDCOMS)) {
			foreach ($GEDCOMS as $array) {
				try {
					WT_DB::prepare("INSERT INTO `##gedcom` (gedcom_id, gedcom_name) VALUES (?,?)")
						->execute(array($array['id'], $array['gedcom']));
				} catch (PDOException $ex) {
					// Ignore duplicates
				}
				// insert gedcom
				foreach ($array as $key=>$value) {
					if ($key!='id' && $key!='gedcom' && $key!='commonsurnames') {
						try {
							WT_DB::prepare("INSERT INTO `##gedcom_setting` (gedcom_id, setting_name, setting_value) VALUES (?,?, ?)")
								->execute(array($array['id'], $key, $value));
						} catch (PDOException $ex) {
							// Ignore duplicates
						}
					}
				}
			}
		}
	}

	// Migrate the data from pgv_users into pgv_user/pgv_user_setting/pgv_user_gedcom_setting
	echo '<p>pgv_users => wt_user ...</p>'; ob_flush(); flush(); usleep(50000);
	try {
		// "INSERT IGNORE" is needed to allow for PGV users with duplicate emails.  Only the first will be imported.
		WT_DB::prepare(
			"INSERT IGNORE INTO `##user` (user_name, real_name, email, password)".
			" SELECT u_username, CONCAT_WS(' ', u_firstname, u_lastname), u_email, u_password FROM {$DBNAME}.{$TBLPREFIX}users"
		)->execute();
	} catch (PDOException $ex) {
		// This could only fail if;
		// a) we've already done it (upgrade)
		// b) it doesn't exist (new install)
	}
	echo '<p>pgv_users => wt_user_setting ...</p>'; ob_flush(); flush(); usleep(50000);
	try {
		WT_DB::prepare(
			"INSERT INTO `##user_setting` (user_id, setting_name, setting_value)".
			" SELECT user_id, 'canadmin', ".
			" CASE WHEN u_canadmin IN ('Y', 'yes') THEN 1 WHEN u_canadmin IN ('N', 'no') THEN 0 ELSE u_canadmin END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'verified', ".
			" CASE WHEN u_verified IN ('Y', 'yes') THEN 1 WHEN u_verified IN ('N', 'no') THEN 0 ELSE u_verified END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'verified_by_admin', ".
			" CASE WHEN u_verified_by_admin IN ('Y', 'yes') THEN 1 WHEN u_verified_by_admin IN ('N', 'no') THEN 0 ELSE u_verified_by_admin END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'language', ".
			" CASE u_language".
			"  WHEN 'catalan'    THEN 'ca'".
			"  WHEN 'english'    THEN 'en_US'".
			"  WHEN 'english-uk' THEN 'en_GB'". // PGV had the config for en_GB, but no language files
			"  WHEN 'polish'     THEN 'pl'".
			"  WHEN 'italian'    THEN 'it'".
			"  WHEN 'spanish'    THEN 'es'".
			"  WHEN 'finnish'    THEN 'fi'".
			"  WHEN 'french'     THEN 'fr'".
			"  WHEN 'german'     THEN 'de'".
			"  WHEN 'danish'     THEN 'da'".
			"  WHEN 'portuguese' THEN 'pt'".
			"  WHEN 'hebrew'     THEN 'he'".
			"  WHEN 'estonian'   THEN 'et'".
			"  WHEN 'turkish'    THEN 'tr'".
			"  WHEN 'dutch'      THEN 'nl'".
			"  WHEN 'slovak'     THEN 'sk'".
			"  WHEN 'norwegian'  THEN 'nn'".
			"  WHEN 'slovenian'  THEN 'sl'".
			"  WHEN 'hungarian'  THEN 'hu'".
			"  WHEN 'swedish'    THEN 'sv'".
			"  WHEN 'russian'    THEN 'ru'".
			"  ELSE 'en_US'". // PGV supports other languages that webtrees does not (yet)
			" END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'pwrequested', ".
			" CASE WHEN u_pwrequested IN ('Y', 'yes') THEN 1 WHEN u_pwrequested IN ('N', 'no') THEN 0 ELSE u_pwrequested END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'reg_timestamp', u_reg_timestamp".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'reg_hashcode', u_reg_hashcode".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'theme', ".
			" CASE u_theme".
			"  WHEN ''                    THEN ''".
			"  WHEN 'themes/cloudy/'      THEN 'themes/clouds/'".
			"  WHEN 'themes/minimal/'     THEN 'themes/minimal/'".
			"  WHEN 'themes/simplyblue/'  THEN 'themes/colors/'".
			"  WHEN 'themes/simplygreen/' THEN 'themes/colors/'".
			"  WHEN 'themes/simplyred/'   THEN 'themes/colors/'".
			"  WHEN 'themes/xenea/'       THEN 'themes/xenea/'".
			"  ELSE 'themes/webtrees/'". // ocean, simplyred/blue/green, standard, wood
			" END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'loggedin', 0".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'sessiontime', u_sessiontime".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'contactmethod', u_contactmethod".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'visibleonline', ".
			" CASE WHEN u_visibleonline IN ('Y', 'yes') THEN 1 WHEN u_visibleonline IN ('N', 'no') THEN 0 ELSE u_visibleonline END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'editaccount', ".
			" CASE WHEN u_editaccount IN ('Y', 'yes') THEN 1 WHEN u_editaccount IN ('N', 'no') THEN 0 ELSE u_editaccount END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'defaulttab', ".
			" CASE WHEN u_defaulttab IN ('Y', 'yes') THEN 1 WHEN u_defaulttab IN ('N', 'no') THEN 0 ELSE u_defaulttab END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'comment', u_comment".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'comment_exp', u_comment_exp".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'relationship_privacy', ".
			" CASE WHEN u_relationship_privacy IN ('Y', 'yes') THEN 1 WHEN u_relationship_privacy IN ('N', 'no') THEN 0 ELSE u_relationship_privacy END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'max_relation_path', u_max_relation_path".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)".
			" UNION ALL".
			" SELECT user_id, 'auto_accept', ".
			" CASE WHEN u_auto_accept IN ('Y', 'yes') THEN 1 WHEN u_auto_accept IN ('N', 'no') THEN 0 ELSE u_auto_accept END".
			" FROM {$DBNAME}.{$TBLPREFIX}users".
			" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)"
		)->execute();
	} catch (PDOException $ex) {
		// This could only fail if;
		// a) we've already done it (upgrade)
		// b) it doesn't exist (new install)
	}
	echo '<p>pgv_users => wt_user_gedcom_setting ...</p>'; ob_flush(); flush(); usleep(50000);
	try {
		$user_gedcom_settings=
			WT_DB::prepare(
				"SELECT user_id, u_gedcomid, u_rootid, u_canedit".
				" FROM {$DBNAME}.{$TBLPREFIX}users".
				" JOIN ##user ON (user_name=u_username COLLATE utf8_unicode_ci)"
			)->fetchAll();
		foreach ($user_gedcom_settings as $setting) {
			@$array=unserialize($setting->u_gedcomid);
			if (is_array($array)) {
				foreach ($array as $gedcom=>$value) {
					$id=get_id_from_gedcom($gedcom);
					if ($id) {
						// Allow for old/invalid gedcom values in array
						set_user_gedcom_setting($setting->user_id, $id, 'gedcomid', $value);
					}
				}
			}
			@$array=unserialize($setting->u_rootid);
			if (is_array($array)) {
				foreach ($array as $gedcom=>$value) {
					$id=get_id_from_gedcom($gedcom);
					if ($id) {
						// Allow for old/invalid gedcom values in array
						set_user_gedcom_setting($setting->user_id, $id, 'rootid', $value);
					}
				}
			}
			@$array=unserialize($setting->u_canedit);
			if (is_array($array)) {
				foreach ($array as $gedcom=>$value) {
					$id=get_id_from_gedcom($gedcom);
					if ($id) {
						// Allow for old/invalid gedcom values in array
						set_user_gedcom_setting($setting->user_id, $id, 'canedit', $value);
					}
				}
			}
		}

	} catch (PDOException $ex) {
		// This could only fail if;
		// a) we've already done it (upgrade)
		// b) it doesn't exist (new install)
	}
}

////////////////////////////////////////////////////////////////////////////////
// Our user ID will have changed.  Switch to the PGV user with the same name.
// If this does not exist, we'll get logged out by session.php on the next page.
$_SESSION['wt_user']=get_user_id(WT_USER_NAME);

define('PGV_PHPGEDVIEW', true);
define('PGV_PRIV_PUBLIC', WT_PRIV_PUBLIC);
define('PGV_PRIV_USER', WT_PRIV_USER);
define('PGV_PRIV_NONE', WT_PRIV_NONE);
define('PGV_PRIV_HIDE', WT_PRIV_HIDE);
$PRIV_PUBLIC=WT_PRIV_PUBLIC;
$PRIV_USER=WT_PRIV_USER;
$PRIV_NONE=WT_PRIV_NONE;
$PRIV_HIDE=WT_PRIV_HIDE;
foreach (get_all_gedcoms() as $ged_id=>$gedcom) {
	$config=get_gedcom_setting($ged_id, 'config');
	if ($PGV_SCHEMA_VERSION>=12) {
	$config=str_replace('${INDEX_DIRECTORY}', $INDEX_DIRECTORY.'/', $config);
	} else {
		$config=str_replace($INDEX_DIRECTORY, $INDEX_DIRECTORY.'/', $config);
	}
	if (is_readable($config)) {
		require $config;
	}
	$privacy=get_gedcom_setting($ged_id, 'privacy');
	if ($PGV_SCHEMA_VERSION>=12) {
	$privacy=str_replace('${INDEX_DIRECTORY}', $INDEX_DIRECTORY.'/', $privacy);
	} else {
		$privacy=str_replace($INDEX_DIRECTORY, $INDEX_DIRECTORY.'/', $privacy);
	}
	if (is_readable($privacy)) {
		require $privacy;
	}

	@set_gedcom_setting($ged_id, 'ABBREVIATE_CHART_LABELS',      $ABBREVIATE_CHART_LABELS);
	@set_gedcom_setting($ged_id, 'ADVANCED_NAME_FACTS',          $ADVANCED_NAME_FACTS);
	@set_gedcom_setting($ged_id, 'ADVANCED_PLAC_FACTS',          $ADVANCED_PLAC_FACTS);
	@set_gedcom_setting($ged_id, 'ALLOW_EDIT_GEDCOM',            $ALLOW_EDIT_GEDCOM);
	@set_gedcom_setting($ged_id, 'ALLOW_THEME_DROPDOWN',         $ALLOW_THEME_DROPDOWN);
	@set_gedcom_setting($ged_id, 'AUTO_GENERATE_THUMBS',         $AUTO_GENERATE_THUMBS);
	@set_gedcom_setting($ged_id, 'CALENDAR_FORMAT',              $CALENDAR_FORMAT);
	@set_gedcom_setting($ged_id, 'CHART_BOX_TAGS',               $CHART_BOX_TAGS);
	@set_gedcom_setting($ged_id, 'CHECK_MARRIAGE_RELATIONS',     $CHECK_MARRIAGE_RELATIONS);
	@set_gedcom_setting($ged_id, 'COMMON_NAMES_ADD',             $COMMON_NAMES_ADD);
	@set_gedcom_setting($ged_id, 'COMMON_NAMES_REMOVE',          $COMMON_NAMES_REMOVE);
	@set_gedcom_setting($ged_id, 'COMMON_NAMES_THRESHOLD',       $COMMON_NAMES_THRESHOLD);
	@set_gedcom_setting($ged_id, 'CONTACT_USER_ID',              get_user_id($CONTACT_EMAIL));
	@set_gedcom_setting($ged_id, 'DEFAULT_PEDIGREE_GENERATIONS', $DEFAULT_PEDIGREE_GENERATIONS);
	@set_gedcom_setting($ged_id, 'DISPLAY_JEWISH_GERESHAYIM',    $DISPLAY_JEWISH_GERESHAYIM);
	@set_gedcom_setting($ged_id, 'DISPLAY_JEWISH_THOUSANDS',     $DISPLAY_JEWISH_THOUSANDS);
	@set_gedcom_setting($ged_id, 'ENABLE_AUTOCOMPLETE',          $ENABLE_AUTOCOMPLETE);
	@set_gedcom_setting($ged_id, 'EXPAND_NOTES',                 $EXPAND_NOTES);
	@set_gedcom_setting($ged_id, 'EXPAND_RELATIVES_EVENTS',      $EXPAND_RELATIVES_EVENTS);
	@set_gedcom_setting($ged_id, 'EXPAND_SOURCES',               $EXPAND_SOURCES);
	@set_gedcom_setting($ged_id, 'FAM_FACTS_ADD',                $FAM_FACTS_ADD);
	@set_gedcom_setting($ged_id, 'FAM_FACTS_QUICK',              $FAM_FACTS_QUICK);
	@set_gedcom_setting($ged_id, 'FAM_FACTS_UNIQUE',             $FAM_FACTS_UNIQUE);
	@set_gedcom_setting($ged_id, 'FAM_ID_PREFIX',                $FAM_ID_PREFIX);
	@set_gedcom_setting($ged_id, 'FULL_SOURCES',                 $FULL_SOURCES);
	@set_gedcom_setting($ged_id, 'GEDCOM_DEFAULT_TAB',           $GEDCOM_DEFAULT_TAB);
	@set_gedcom_setting($ged_id, 'GEDCOM_ID_PREFIX',             $GEDCOM_ID_PREFIX);
	@set_gedcom_setting($ged_id, 'GENERATE_UIDS',                $GENERATE_UIDS);
	@set_gedcom_setting($ged_id, 'HIDE_GEDCOM_ERRORS',           $HIDE_GEDCOM_ERRORS);
	@set_gedcom_setting($ged_id, 'HIDE_LIVE_PEOPLE',             $HIDE_LIVE_PEOPLE);
	@set_gedcom_setting($ged_id, 'INDI_FACTS_ADD',               $INDI_FACTS_ADD);
	@set_gedcom_setting($ged_id, 'INDI_FACTS_QUICK',             $INDI_FACTS_QUICK);
	@set_gedcom_setting($ged_id, 'INDI_FACTS_UNIQUE',            $INDI_FACTS_UNIQUE);
	switch ($LANGUAGE) {
	case 'catalan':    @set_gedcom_setting($ged_id, 'LANGUAGE', 'ca'); break;
	case 'english-uk': @set_gedcom_setting($ged_id, 'LANGUAGE', 'en_GB'); break;
	case 'polish':     @set_gedcom_setting($ged_id, 'LANGUAGE', 'pl'); break;
	case 'italian':    @set_gedcom_setting($ged_id, 'LANGUAGE', 'it'); break;
	case 'spanish':    @set_gedcom_setting($ged_id, 'LANGUAGE', 'es'); break;
	case 'finnish':    @set_gedcom_setting($ged_id, 'LANGUAGE', 'fi'); break;
	case 'french':     @set_gedcom_setting($ged_id, 'LANGUAGE', 'fr'); break;
	case 'german':     @set_gedcom_setting($ged_id, 'LANGUAGE', 'de'); break;
	case 'danish':     @set_gedcom_setting($ged_id, 'LANGUAGE', 'da'); break;
	case 'portuguese': @set_gedcom_setting($ged_id, 'LANGUAGE', 'pt'); break;
	case 'hebrew':     @set_gedcom_setting($ged_id, 'LANGUAGE', 'he'); break;
	case 'estonian':   @set_gedcom_setting($ged_id, 'LANGUAGE', 'et'); break;
	case 'turkish':    @set_gedcom_setting($ged_id, 'LANGUAGE', 'tr'); break;
	case 'dutch':      @set_gedcom_setting($ged_id, 'LANGUAGE', 'nl'); break;
	case 'slovak':     @set_gedcom_setting($ged_id, 'LANGUAGE', 'sk'); break;
	case 'norwegian':  @set_gedcom_setting($ged_id, 'LANGUAGE', 'nn'); break;
	case 'slovenian':  @set_gedcom_setting($ged_id, 'LANGUAGE', 'sl'); break;
	case 'hungarian':  @set_gedcom_setting($ged_id, 'LANGUAGE', 'hu'); break;
	case 'swedish':    @set_gedcom_setting($ged_id, 'LANGUAGE', 'sv'); break;
	case 'russian':    @set_gedcom_setting($ged_id, 'LANGUAGE', 'ru'); break;
	default:           @set_gedcom_setting($ged_id, 'LANGUAGE', 'en_US'); break;
	}
	@set_gedcom_setting($ged_id, 'LINK_ICONS',                   $LINK_ICONS);
	@set_gedcom_setting($ged_id, 'MAX_ALIVE_AGE',                $MAX_ALIVE_AGE);
	@set_gedcom_setting($ged_id, 'MAX_DESCENDANCY_GENERATIONS',  $MAX_DESCENDANCY_GENERATIONS);
	@set_gedcom_setting($ged_id, 'MAX_PEDIGREE_GENERATIONS',     $MAX_PEDIGREE_GENERATIONS);
	@set_gedcom_setting($ged_id, 'MAX_RELATION_PATH_LENGTH',     $MAX_RELATION_PATH_LENGTH);
	@set_gedcom_setting($ged_id, 'MEDIA_DIRECTORY',              'media/');
	@set_gedcom_setting($ged_id, 'MEDIA_DIRECTORY_LEVELS',       $MEDIA_DIRECTORY_LEVELS);
	@set_gedcom_setting($ged_id, 'MEDIA_EXTERNAL',               $MEDIA_EXTERNAL);
	@set_gedcom_setting($ged_id, 'MEDIA_FIREWALL_ROOTDIR',       $MEDIA_FIREWALL_ROOTDIR);
	@set_gedcom_setting($ged_id, 'MEDIA_FIREWALL_THUMBS',        $MEDIA_FIREWALL_THUMBS);
	@set_gedcom_setting($ged_id, 'MEDIA_ID_PREFIX',              $MEDIA_ID_PREFIX);
	@set_gedcom_setting($ged_id, 'META_DESCRIPTION',             $META_DESCRIPTION);
	@set_gedcom_setting($ged_id, 'META_ROBOTS',                  $META_ROBOTS);
	@set_gedcom_setting($ged_id, 'META_TITLE',                   $META_TITLE);
	@set_gedcom_setting($ged_id, 'MULTI_MEDIA',                  $MULTI_MEDIA);
	@set_gedcom_setting($ged_id, 'NOTE_FACTS_ADD',               $NOTE_FACTS_ADD);
	@set_gedcom_setting($ged_id, 'NOTE_FACTS_QUICK',             $NOTE_FACTS_QUICK);
	@set_gedcom_setting($ged_id, 'NOTE_FACTS_UNIQUE',            $NOTE_FACTS_UNIQUE);
	@set_gedcom_setting($ged_id, 'NOTE_ID_PREFIX',               'N');
	@set_gedcom_setting($ged_id, 'NO_UPDATE_CHAN',               $NO_UPDATE_CHAN);
	@set_gedcom_setting($ged_id, 'PAGE_AFTER_LOGIN',             'mypage');
	@set_gedcom_setting($ged_id, 'PEDIGREE_FULL_DETAILS',        $PEDIGREE_FULL_DETAILS);
	@set_gedcom_setting($ged_id, 'PEDIGREE_LAYOUT',              $PEDIGREE_LAYOUT);
	@set_gedcom_setting($ged_id, 'PEDIGREE_ROOT_ID',             $PEDIGREE_ROOT_ID);
	@set_gedcom_setting($ged_id, 'PEDIGREE_SHOW_GENDER',         $PEDIGREE_SHOW_GENDER);
	@set_gedcom_setting($ged_id, 'POSTAL_CODE',                  $POSTAL_CODE);
	@set_gedcom_setting($ged_id, 'PREFER_LEVEL2_SOURCES',        $PREFER_LEVEL2_SOURCES);
	@set_gedcom_setting($ged_id, 'QUICK_REQUIRED_FACTS',         $QUICK_REQUIRED_FACTS);
	@set_gedcom_setting($ged_id, 'QUICK_REQUIRED_FAMFACTS',      $QUICK_REQUIRED_FAMFACTS);
	@set_gedcom_setting($ged_id, 'REPO_FACTS_ADD',               $REPO_FACTS_ADD);
	@set_gedcom_setting($ged_id, 'REPO_FACTS_QUICK',             $REPO_FACTS_QUICK);
	@set_gedcom_setting($ged_id, 'REPO_FACTS_UNIQUE',            $REPO_FACTS_UNIQUE);
	@set_gedcom_setting($ged_id, 'REPO_ID_PREFIX',               $REPO_ID_PREFIX);
	@set_gedcom_setting($ged_id, 'REQUIRE_AUTHENTICATION',       $REQUIRE_AUTHENTICATION);
	@set_gedcom_setting($ged_id, 'SAVE_WATERMARK_IMAGE',         $SAVE_WATERMARK_IMAGE);
	@set_gedcom_setting($ged_id, 'SAVE_WATERMARK_THUMB',         $SAVE_WATERMARK_THUMB);
	@set_gedcom_setting($ged_id, 'SEARCH_FACTS_DEFAULT',         $SEARCH_FACTS_DEFAULT);
	@set_gedcom_setting($ged_id, 'SHOW_AGE_DIFF',                $SHOW_AGE_DIFF);
	@set_gedcom_setting($ged_id, 'SHOW_CONTEXT_HELP',            $SHOW_CONTEXT_HELP);
	@set_gedcom_setting($ged_id, 'SHOW_COUNTER',                 $SHOW_COUNTER);
	@set_gedcom_setting($ged_id, 'SHOW_DEAD_PEOPLE',             $SHOW_DEAD_PEOPLE);
	@set_gedcom_setting($ged_id, 'SHOW_EMPTY_BOXES',             $SHOW_EMPTY_BOXES);
	@set_gedcom_setting($ged_id, 'SHOW_EST_LIST_DATES',          $SHOW_EST_LIST_DATES);
	@set_gedcom_setting($ged_id, 'SHOW_FACT_ICONS',              $SHOW_FACT_ICONS);
	@set_gedcom_setting($ged_id, 'SHOW_GEDCOM_RECORD',           $SHOW_GEDCOM_RECORD);
	@set_gedcom_setting($ged_id, 'SHOW_HIGHLIGHT_IMAGES',        $SHOW_HIGHLIGHT_IMAGES);
	@set_gedcom_setting($ged_id, 'SHOW_LDS_AT_GLANCE',           $SHOW_LDS_AT_GLANCE);
	@set_gedcom_setting($ged_id, 'SHOW_LEVEL2_NOTES',            $SHOW_LEVEL2_NOTES);
	@set_gedcom_setting($ged_id, 'SHOW_LIST_PLACES',             $SHOW_LIST_PLACES);
	@set_gedcom_setting($ged_id, 'SHOW_LIVING_NAMES',            $SHOW_LIVING_NAMES);
	@set_gedcom_setting($ged_id, 'SHOW_MARRIED_NAMES',           $SHOW_MARRIED_NAMES);
	@set_gedcom_setting($ged_id, 'SHOW_MEDIA_DOWNLOAD',          $SHOW_MEDIA_DOWNLOAD);
	@set_gedcom_setting($ged_id, 'SHOW_MEDIA_FILENAME',          $SHOW_MEDIA_FILENAME);
	@set_gedcom_setting($ged_id, 'SHOW_MULTISITE_SEARCH',        $SHOW_MULTISITE_SEARCH);
	@set_gedcom_setting($ged_id, 'SHOW_PARENTS_AGE',             $SHOW_PARENTS_AGE);
	@set_gedcom_setting($ged_id, 'SHOW_PEDIGREE_PLACES',         $SHOW_PEDIGREE_PLACES);
	@set_gedcom_setting($ged_id, 'SHOW_PRIVATE_RELATIONSHIPS',   $SHOW_PRIVATE_RELATIONSHIPS);
	@set_gedcom_setting($ged_id, 'SHOW_REGISTER_CAUTION',        $SHOW_REGISTER_CAUTION);
	@set_gedcom_setting($ged_id, 'SHOW_RELATIVES_EVENTS',        $SHOW_RELATIVES_EVENTS);
	@set_gedcom_setting($ged_id, 'SHOW_SPIDER_TAGLINE',          $SHOW_SPIDER_TAGLINE);
	@set_gedcom_setting($ged_id, 'SHOW_STATS',                   $SHOW_STATS);
	@set_gedcom_setting($ged_id, 'SOURCE_ID_PREFIX',             $SOURCE_ID_PREFIX);
	@set_gedcom_setting($ged_id, 'SOUR_FACTS_ADD',               $SOUR_FACTS_ADD);
	@set_gedcom_setting($ged_id, 'SOUR_FACTS_QUICK',             $SOUR_FACTS_QUICK);
	@set_gedcom_setting($ged_id, 'SOUR_FACTS_UNIQUE',            $SOUR_FACTS_UNIQUE);
	@set_gedcom_setting($ged_id, 'SPLIT_PLACES',                 $SPLIT_PLACES);
	@set_gedcom_setting($ged_id, 'SUBLIST_TRIGGER_F',            $SUBLIST_TRIGGER_F);
	@set_gedcom_setting($ged_id, 'SUBLIST_TRIGGER_I',            $SUBLIST_TRIGGER_I);
	@set_gedcom_setting($ged_id, 'SURNAME_LIST_STYLE',           $SURNAME_LIST_STYLE);
	@set_gedcom_setting($ged_id, 'SURNAME_TRADITION',            $SURNAME_TRADITION);
	switch ($THEME_DIR) {
	case '':                   @set_gedcom_setting($ged_id, 'THEME_DIR', '');
	case 'themes/cloudy/':     @set_gedcom_setting($ged_id, 'THEME_DIR', 'themes/clouds/');
	case 'themes/minimal/':    @set_gedcom_setting($ged_id, 'THEME_DIR', 'themes/minimal/');
	case 'themes/simplyblue/':
	case 'themes/simplygreen/':
	case 'themes/simplyred/':  @set_gedcom_setting($ged_id, 'THEME_DIR', 'themes/colors/');
	case 'themes/xenea/':      @set_gedcom_setting($ged_id, 'THEME_DIR', 'themes/xenea/');
	default:                   @set_gedcom_setting($ged_id, 'THEME_DIR', 'themes/webtrees/');
	}
	@set_gedcom_setting($ged_id, 'THUMBNAIL_WIDTH',              $THUMBNAIL_WIDTH);
	@set_gedcom_setting($ged_id, 'UNDERLINE_NAME_QUOTES',        $UNDERLINE_NAME_QUOTES);
	@set_gedcom_setting($ged_id, 'USE_GEONAMES',                 $USE_GEONAMES);
	@set_gedcom_setting($ged_id, 'USE_MEDIA_FIREWALL',           $USE_MEDIA_FIREWALL);
	@set_gedcom_setting($ged_id, 'USE_MEDIA_VIEWER',             $USE_MEDIA_VIEWER);
	@set_gedcom_setting($ged_id, 'USE_RIN',                      $USE_RIN);
	@set_gedcom_setting($ged_id, 'USE_SILHOUETTE',               $USE_SILHOUETTE);
	@set_gedcom_setting($ged_id, 'USE_THUMBS_MAIN',              $USE_THUMBS_MAIN);
	@set_gedcom_setting($ged_id, 'WATERMARK_THUMB',              $WATERMARK_THUMB);
	@set_gedcom_setting($ged_id, 'WEBMASTER_USER_ID',            get_user_id($WEBMASTER_EMAIL));
	@set_gedcom_setting($ged_id, 'WELCOME_TEXT_AUTH_MODE',       $WELCOME_TEXT_AUTH_MODE);
	@set_gedcom_setting($ged_id, 'WELCOME_TEXT_AUTH_MODE_'.WT_LOCALE, $WELCOME_TEXT_AUTH_MODE_4);
	@set_gedcom_setting($ged_id, 'WELCOME_TEXT_CUST_HEAD',       $WELCOME_TEXT_CUST_HEAD);
	@set_gedcom_setting($ged_id, 'WORD_WRAPPED_NOTES',           $WORD_WRAPPED_NOTES);
	@set_gedcom_setting($ged_id, 'ZOOM_BOXES',                   $ZOOM_BOXES);

	// TODO import whatever privacy settings as are compatible with the new system

	set_gedcom_setting($ged_id, 'config',   null);
	set_gedcom_setting($ged_id, 'privacy',  null);
	set_gedcom_setting($ged_id, 'path',     null);
	set_gedcom_setting($ged_id, 'pgv_ver',  null);
	set_gedcom_setting($ged_id, 'imported', 1);
}

////////////////////////////////////////////////////////////////////////////////

if ($PGV_SCHEMA_VERSION>=13) {
	echo '<p>pgv_hit_counter => wt_hit_counter ...</p>'; ob_flush(); flush(); usleep(50000);
	WT_DB::prepare(
		"REPLACE INTO `##hit_counter` (gedcom_id, page_name, page_parameter, page_count)".
		" SELECT gedcom_id, page_name, page_parameter, page_count FROM {$DBNAME}.{$TBLPREFIX}hit_counter"
	)->execute();
} else {
	// Copied from PGV's db_schema_12_13
	$statement=WT_DB::prepare("INSERT IGNORE INTO `##hit_counter` (gedcom_id, page_name, page_parameter, page_count) VALUES (?, ?, ?, ?)");

	foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
		// Caution these files might be quite large...
		$file=$INDEX_DIRECTORY.'/'.$ged_name.'pgv_counters.txt';
		echo '<p>', $file, ' => wt_hit_counter ...</p>'; ob_flush(); flush(); usleep(50000);
		if (file_exists($file)) {
			foreach (file($file) as $line) {
				if (preg_match('/(@([A-Za-z0-9:_-]+)@ )?(\d+)/', $line, $match)) {
					if ($match[2]) {
						$page_name='individual.php';
						$page_parameter=$match[2];
					} else {
						$page_name='index.php';
						$page_parameter='gedcom:'.$ged_id;
					}
					try {
						$statement->execute(array($ged_id, $page_name, $page_parameter, $match[3]));
					} catch (PDOException $ex) {
						// Primary key violation?  Ignore?
					}
				}
			}
		}
	}
}

////////////////////////////////////////////////////////////////////////////////

if ($PGV_SCHEMA_VERSION>=14) {
	echo '<p>pgv_ip_address => wt_ip_address ...</p>'; ob_flush(); flush(); usleep(50000);
	WT_DB::prepare(
		"INSERT IGNORE INTO `##ip_address` (ip_address, category, comment)".
		" SELECT ip_address, category, comment FROM {$DBNAME}.{$TBLPREFIX}ip_address"
	)->execute();
} else {
	// Copied from PGV's db_schema_13_14
	$statement=WT_DB::prepare("REPLACE INTO `##ip_address` (ip_address, category, comment) VALUES (?, ?, ?)");
	echo '<p>banned.php => wt_ip_address ...</p>'; ob_flush(); flush(); usleep(50000);
	if (is_readable($INDEX_DIRECTORY.'/banned.php')) {
		@require $INDEX_DIRECTORY.'/banned.php';
		if (!empty($banned) && is_array($banned)) {
			foreach ($banned as $value) {
				try {
					if (is_array($value)) {
						// New format: array(ip, comment)
						$statement->execute(array($value[0], 'banned', $value[1]));
					} else {
						// Old format: string(ip)
						$statement->execute(array($value, 'banned', ''));
					}
				} catch (PDOException $ex) {
					echo $ex, '<br/>';
				}
			}
		}
	}
	echo '<p>search_engines.php => wt_ip_address ...</p>'; ob_flush(); flush(); usleep(50000);
	if (is_readable($INDEX_DIRECTORY.'/search_engines.php')) {
		@require $INDEX_DIRECTORY.'/search_engines.php';
		if (!empty($search_engines) && is_array($search_engines)) {
			foreach ($search_engines as $value) {
				try {
					if (is_array($value)) {
						// New format: array(ip, comment)
						$statement->execute(array($value[0], 'search-engine', $value[1]));
					} else {
						// Old format: string(ip)
						$statement->execute(array($value, 'search-engine', ''));
					}
				} catch (PDOException $ex) {
					echo $ex, '<br/>';
				}
			}
		}
	}
}

////////////////////////////////////////////////////////////////////////////////

foreach (get_all_gedcoms() as $ged_id=>$gedcom) {
	WT_Module::setDefaultAccess($ged_id);
}

echo '<p>pgv_site_setting => wt_module_setting ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##module_setting` (module_name, setting_name, setting_value)".
	" SELECT 'googlemap', site_setting_name, site_setting_value FROM {$DBNAME}.{$TBLPREFIX}site_setting".
	" WHERE site_setting_name LIKE 'GM_%'"
)->execute();
WT_DB::prepare(
	"REPLACE INTO `##module_setting` (module_name, setting_name, setting_value)".
	" SELECT 'lightbox', site_setting_name, site_setting_value FROM {$DBNAME}.{$TBLPREFIX}site_setting".
	" WHERE site_setting_name LIKE 'LB_%'"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_favorites => wt_favorites ...</p>'; ob_flush(); flush(); usleep(50000);
try {
	WT_DB::prepare(
		"REPLACE INTO `##favorites` (fv_id, fv_username, fv_gid, fv_type, fv_file, fv_url, fv_title, fv_note)".
		" SELECT fv_id, fv_username, fv_gid, fv_type, fv_file, fv_url, fv_title, fv_note FROM {$DBNAME}.{$TBLPREFIX}favorites"
	)->execute();
} catch (PDOException $ex) {
	// This table will only exist if the favorites module is installed in WT
}

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_news => wt_news ...</p>'; ob_flush(); flush(); usleep(50000);
try {
	WT_DB::prepare(
		"REPLACE INTO `##news` (n_id, n_username, n_date, n_title, n_text)".
		" SELECT n_id, n_username, n_date, n_title, n_text FROM {$DBNAME}.{$TBLPREFIX}news"
	)->execute();
} catch (PDOException $ex) {
	// This table will only exist if the news/blog module is installed in WT
}

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_dates => wt_dates ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##dates` (d_day, d_mon, d_month, d_year, d_julianday1, d_julianday2, d_fact, d_gid, d_File, d_type)".
	" SELECT d_day, d_mon, d_month, d_year, d_julianday1, d_julianday2, d_fact, d_gid, d_File, d_type FROM {$DBNAME}.{$TBLPREFIX}dates"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_families => wt_families ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##families` (f_id, f_file, f_husb, f_wife, f_numchil, f_gedcom)".
	" SELECT f_id, f_file, f_husb, f_wife, f_numchil, ".
	" REPLACE(REPLACE(f_gedcom, '\n2 _PGVU ', '\n2 _WT_USER '), '\n1 _PGV_OBJS ', '\n1 _WT_OBJE_SORT ')".
	" FROM {$DBNAME}.{$TBLPREFIX}families"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_individuals => wt_individuals ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##individuals` (i_id, i_file, i_rin, i_isdead, i_sex, i_gedcom)".
	" SELECT i_id, i_file, i_rin, i_isdead, i_sex, ".
	" REPLACE(REPLACE(i_gedcom, '\n2 _PGVU ', '\n2 _WT_USER '), '\n1 _PGV_OBJS ', '\n1 _WT_OBJE_SORT ')".
	" FROM {$DBNAME}.{$TBLPREFIX}individuals"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_link => wt_link ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##link` (l_file, l_from, l_type, l_to)".
	" SELECT l_file, l_from, l_type, l_to FROM {$DBNAME}.{$TBLPREFIX}link"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_media => wt_media ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##media` (m_id, m_media, m_ext, m_titl, m_file, m_gedfile, m_gedrec)".
	" SELECT m_id, m_media, m_ext, m_titl, m_file, m_gedfile, ".
	" REPLACE(m_gedrec, '\n2 _PGVU ', '\n2 _WT_USER ')".
	" FROM {$DBNAME}.{$TBLPREFIX}media"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_media_mapping => wt_media_mapping ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##media_mapping` (mm_id, mm_media, mm_gid, mm_order, mm_gedfile, mm_gedrec)".
	" SELECT mm_id, mm_media, mm_gid, mm_order, mm_gedfile, mm_gedrec FROM {$DBNAME}.{$TBLPREFIX}media_mapping"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_name => wt_name ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##name` (n_file, n_id, n_num, n_type, n_sort, n_full, n_list, n_surname, n_surn, n_givn, n_soundex_givn_std, n_soundex_surn_std, n_soundex_givn_dm, n_soundex_surn_dm)".
	" SELECT n_file, n_id, n_num, n_type, n_sort, n_full, n_list, n_surname, n_surn, n_givn, n_soundex_givn_std, n_soundex_surn_std, n_soundex_givn_dm, n_soundex_surn_dm FROM {$DBNAME}.{$TBLPREFIX}name"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_other => wt_other ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##other` (o_id, o_file, o_type, o_gedcom)".
	" SELECT o_id, o_file, o_type, ".
	" REPLACE(o_gedcom, '\n2 _PGVU ', '\n2 _WT_USER ')".
	" FROM {$DBNAME}.{$TBLPREFIX}other"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_placelinks => wt_placelinks ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##placelinks` (pl_p_id, pl_gid, pl_file)".
	" SELECT pl_p_id, pl_gid, pl_file FROM {$DBNAME}.{$TBLPREFIX}placelinks"
)->execute();

////////////////////////////////////////////////////////////////////////////////

try {
	if ($DBNAME.$TBLPREFIX.'placelocation'){
		echo '<p>pgv_placelocation => wt_placelocation ...</p>'; ob_flush(); flush(); usleep(50000);
		WT_DB::exec(
		"CREATE TABLE IF NOT EXISTS `##placelocation` (".
		" pl_id        INTEGER      NOT NULL,".
		" pl_parent_id INTEGER          NULL,".
		" pl_level     INTEGER          NULL,".
		" pl_place     VARCHAR(255)     NULL,".
		" pl_long      VARCHAR(30)      NULL,".
		" pl_lati      VARCHAR(30)      NULL,".
		" pl_zoom      INTEGER          NULL,".
		" pl_icon      VARCHAR(255)     NULL,".
		" PRIMARY KEY     (pl_id),".
		"         KEY ix1 (pl_level),".
		"         KEY ix2 (pl_long),".
		"         KEY ix3 (pl_lati),".
		"         KEY ix4 (pl_place),".
		"         KEY ix5 (pl_parent_id)".
		") COLLATE utf8_unicode_ci ENGINE=InnoDB"
		);

		WT_DB::prepare(
			"REPLACE INTO `##placelocation` (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon)".
			" SELECT pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon FROM {$DBNAME}.{$TBLPREFIX}placelocation"
		)->execute();
	}
} catch (PDOexception $ex) {
	// This table will only exist if the gm module is installed in PGV/WT
}

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_places => wt_places ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##places` (p_id, p_place, p_level, p_parent_id, p_file, p_std_soundex, p_dm_soundex)".
	" SELECT p_id, p_place, p_level, p_parent_id, p_file, p_std_soundex, p_dm_soundex FROM {$DBNAME}.{$TBLPREFIX}places"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_remotelinks => wt_remotelinks ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##remotelinks` (r_gid, r_linkid, r_file)".
	" SELECT r_gid, r_linkid, r_file FROM {$DBNAME}.{$TBLPREFIX}remotelinks"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_sources => wt_sources ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##sources` (s_id, s_file, s_name, s_dbid, s_gedcom)".
	" SELECT s_id, s_file, s_name, s_dbid, ".
	" REPLACE(s_gedcom, '\n2 _PGVU ', '\n2 _WT_USER ')".
	" FROM {$DBNAME}.{$TBLPREFIX}sources"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_messages => wt_message ...</p>'; ob_flush(); flush(); usleep(50000);
WT_DB::prepare(
	"REPLACE INTO `##message` (message_id, sender, ip_address, user_id, subject, body, created)".
	" SELECT m_id, m_from, '127.0.0.1', user_id, m_subject, m_body, str_to_date(m_created,'%a, %d %M %Y %H:%i:%s')".
	" FROM {$DBNAME}.{$TBLPREFIX}messages".
	" JOIN `##user` ON (m_to COLLATE utf8_unicode_ci=user_name)"
)->execute();

////////////////////////////////////////////////////////////////////////////////

WT_DB::exec("COMMIT");

echo '<p><b><a href="editgedcoms.php">', i18n::translate('Click here to continue'), '</a></b></p>';
