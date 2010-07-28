<?php
/**
 * A form to edit site configuration.
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
 * @subpackage Admin
 * @version $Id: siteconfig.php 8934 2010-06-23 21:27:24Z greg $
 */

define('WT_SCRIPT_NAME', 'site_config.php');
require '../includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';
require 'admin_functions.php';

admin_header(i18n::translate('Site configuration'));

	
switch (safe_POST('action')) {
case 'update':
	$data_directory= safe_POST('data_directory');
	if ($data_directory && is_dir($data_directory) && is_readable($data_directory) && is_writable($data_directory) && file_exists($data_directory.DIRECTORY_SEPARATOR.'config.ini.php') && is_readable($data_directory.DIRECTORY_SEPARATOR.'config.ini.php')) {
		set_site_setting('INDEX_DIRECTORY', $data_directory);
	}
	set_site_setting('STORE_MESSAGES',                  safe_POST('store_messages'));
	set_site_setting('USE_REGISTRATION_MODULE',         safe_POST('use_registration_module'));
	set_site_setting('REQUIRE_ADMIN_AUTH_REGISTRATION', safe_POST('require_admin_auth_registration'));
	set_site_setting('ALLOW_USER_THEMES',               safe_POST('allow_user_themes'));
	set_site_setting('ALLOW_CHANGE_GEDCOM',             safe_POST('allow_change_gedcom'));
	set_site_setting('SESSION_SAVE_PATH',               safe_POST('session_save_path'));
	set_site_setting('SESSION_TIME',                    safe_POST('session_time'));
	set_site_setting('SERVER_URL',                      safe_POST('server_url'));
	set_site_setting('LOGIN_URL',                       safe_POST('login_url'));
	set_site_setting('MEMORY_LIMIT',                    safe_POST('memory_limit', '\d+[KMG]?', ini_get('memory_limit')));
	set_site_setting('MAX_EXECUTION_TIME',              safe_POST('max_execution_time', '\d+', ini_get('max_execution_time')));
	set_site_setting('SMTP_ACTIVE',                     safe_POST('smtp_active', 'internal|external|disabled', 'internal'));
	set_site_setting('SMTP_HOST',                       safe_POST('smtp_host'));
	set_site_setting('SMTP_HELO',                       safe_POST('smtp_helo'));
	set_site_setting('SMTP_PORT',                       safe_POST('smtp_port'));
	set_site_setting('SMTP_AUTH',                       safe_POST('smtp_auth'));
	set_site_setting('SMTP_AUTH_USER',                  safe_POST('smtp_auth_user'));
	set_site_setting('SMTP_AUTH_PASS',                  safe_POST('smtp_auth_pass'));
	set_site_setting('SMTP_SSL',                        safe_POST('smtp_ssl'));
	set_site_setting('SMTP_FROM_NAME',                  safe_POST('smtp_from_name'));
	set_site_setting('SMTP_SIMPLE_MAIL',                safe_POST('smtp_simple_mail'));

	// We've saved the updated values - now return to the admin page
	header('Location: admin.php');
	exit;
}

$smtp_active=get_site_setting('SMTP_ACTIVE');

echo
	'<h1>', i18n::translate('Server configuration'), '</h1>',

	'<form name="siteconfig" method="post" action="siteconfig.php" autocomplete="off">',
	'<input type="hidden" name="action" value="update" />',
	'<table id="server">',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Data file directory'), help_link('INDEX_DIRECTORY'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="data_directory" value="', get_site_setting('INDEX_DIRECTORY'), '" size="50" /></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Memory limit'), help_link('MEMORY_LIMIT'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="memory_limit" value="', get_site_setting('MEMORY_LIMIT'), '" /></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('PHP time limit'), help_link('MAX_EXECUTION_TIME'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="max_execution_time" value="', get_site_setting('MAX_EXECUTION_TIME'), '" /></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Allow messages to be stored online'), help_link('STORE_MESSAGES'), '</td>',
	'<td class="optionbox wrap">', edit_field_yes_no('store_messages', get_site_setting('STORE_MESSAGES')), '</td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Allow visitors to request account registration'), help_link('USE_REGISTRATION_MODULE'), '</td>',
	'<td class="optionbox wrap">', edit_field_yes_no('use_registration_module', get_site_setting('USE_REGISTRATION_MODULE')), '</td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Require an administrator to approve new user registrations'), help_link('REQUIRE_ADMIN_AUTH_REGISTRATION'), '</td>',
	'<td class="optionbox wrap">', edit_field_yes_no('require_admin_auth_registration', get_site_setting('REQUIRE_ADMIN_AUTH_REGISTRATION')), '</td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Allow users to select their own theme'), help_link('ALLOW_USER_THEMES'), '</td>',
	'<td class="optionbox wrap">', edit_field_yes_no('allow_user_themes', get_site_setting('ALLOW_USER_THEMES')), '</td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Allow GEDCOM switching'), help_link('ALLOW_CHANGE_GEDCOM'), '</td>',
	'<td class="optionbox wrap">', edit_field_yes_no('allow_change_gedcom', get_site_setting('ALLOW_CHANGE_GEDCOM')), '</td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Session save path'), help_link('SESSION_SAVE_PATH'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="session_save_path" value="', get_site_setting('SESSION_SAVE_PATH'), '" size="50" /></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Session timeout'), help_link('SESSION_TIME'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="session_time" value="', get_site_setting('SESSION_TIME'), '" /></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Website URL'), help_link('SERVER_URL'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="server_url" value="', get_site_setting('SERVER_URL'), '" size="50" /></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Login URL'), help_link('LOGIN_URL'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="login_url" value="', get_site_setting('LOGIN_URL'), '" size="50" /></td>',
	'</tr><tr>',
	'<td class="facts_label" colspan="2">', i18n::translate('SMTP mail configuration'), '</td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Messages'), help_link('SMTP_ACTIVE'), '</td>',
	'<td class="optionbox wrap">',
	select_edit_control(
		'smtp_active',
		array(
			'internal'=>i18n::translate('Use PHP mail to send messages'),
			'external'=>i18n::translate('Use SMTP to send messages'),
			'disabled'=>i18n::translate('Do not send messages')
		),
		null,
		$smtp_active,
		'onchange="document.siteconfig.smtp_host.disabled=(this.value!=\'external\');document.siteconfig.smtp_port.disabled=(this.value!=\'external\');document.siteconfig.smtp_helo.disabled=(this.value!=\'external\');document.siteconfig.smtp_simple_mail.disabled=(this.value!=\'external\');document.siteconfig.smtp_auth.disabled=(this.value!=\'external\');document.siteconfig.smtp_auth_user.disabled=(this.value!=\'external\');document.siteconfig.smtp_auth_pass.disabled=(this.value!=\'external\');document.siteconfig.smtp_ssl.disabled=(this.value!=\'external\');document.siteconfig.smtp_from_name.disabled=(this.value!=\'external\');"'
	),
	'</td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Server'), help_link('SMTP_HOST'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="smtp_host" value="', get_site_setting('SMTP_HOST'), '" ', $smtp_active=='external' ? '' : 'disabled', '/></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Port'), help_link('SMTP_PORT'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="smtp_port" value="', get_site_setting('SMTP_PORT'), '" ', $smtp_active=='external' ? '' : 'disabled', '/></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Use simple mail headers'), help_link('SMTP_SIMPLE_MAIL'), '</td>',
	'<td class="optionbox wrap">', edit_field_yes_no('smtp_simple_mail', get_site_setting('SMTP_SIMPLE_MAIL'), $smtp_active=='external' ? '' : 'disabled'), '</td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Use password'), help_link('SMTP_AUTH'), '</td>',
	'<td class="optionbox wrap">', edit_field_yes_no('smtp_auth', get_site_setting('SMTP_AUTH'), $smtp_active=='external' ? '' : 'disabled'), '</td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Username'), help_link('SMTP_AUTH_USER'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="smtp_auth_user" value="', get_site_setting('SMTP_AUTH_USER'), '" ', $smtp_active=='external' ? '' : 'disabled', '/></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Password'), help_link('SMTP_AUTH_PASS'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="smtp_auth_pass" value="', get_site_setting('SMTP_AUTH_PASS'), '" ', $smtp_active=='external' ? '' : 'disabled', '/></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Security'), help_link('SMTP_SSL'), '</td>',
	'<td class="optionbox wrap">',
	select_edit_control(
		'smtp_ssl',
		array(
			'none'=>i18n::translate('none'),
			'ssl'=>i18n::translate('ssl'),
			'tls'=>i18n::translate('tls')
		),
		null,
		get_site_setting('SMTP_SSL'),
		$smtp_active=='external' ? '' : 'disabled'
	),
	'</td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('From email address'), help_link('SMTP_FROM_NAME'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="smtp_from_name" size="50" value="', get_site_setting('SMTP_FROM_NAME'), '" ', $smtp_active=='external' ? '' : 'disabled', '/></td>',
	'</tr><tr>',
	'<td class="descriptionbox width20 wrap">', i18n::translate('Sender email address'), help_link('SMTP_HELO'), '</td>',
	'<td class="optionbox wrap"><input type="text" name="smtp_helo" size="50" value="', get_site_setting('SMTP_HELO'), '" ', $smtp_active=='external' ? '' : 'disabled', '/></td>',
	'</tr><tr>',
	'<td class="topbottombar" colspan="2"><input type="submit" value="', i18n::translate('Save'), '" /></td>',
	'</tr></table></form>';
include 'admin_footer.php';
?>