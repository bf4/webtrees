<?php
// Header for webtrees administration theme
//
// webtrees: Web based Family History software
// Copyright (C) 2010 webtrees development team.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// @version $Id$

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

echo
	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
	'<html xmlns="http://www.w3.org/1999/xhtml" ', WT_I18N::html_markup(), '>',
	'<head>',
	'<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />',
	'<title>', htmlspecialchars($title), '</title>',
	'<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />',
	'<link rel="stylesheet" href="', WT_THEME_DIR, 'jquery/jquery-ui_theme.css" type="text/css" />',
	'<link rel="stylesheet" href="', $stylesheet, '" type="text/css" media="all" />',
	$javascript,
	'</head>';
?>
	<!-- This is a script that claims solve all pre-IE9 compatability issues -->
	<!--[if lt IE 9]>
	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js" type="text/javascript">IE7_PNG_SUFFIX=".png";</script>
	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/ie7-squish.js" type="text/javascript"></script>
	<![endif]-->	
<?php
echo	
	'<body id="body">',
// Header
	'<div id="admin_head" class="ui-widget-content">',
	'<a href="', WT_WEBTREES_URL, '" title="', WT_WEBTREES, '" alt="', WT_WEBTREES, '"><img src="', $WT_IMAGES['webtrees'], '"/></a>',
	'<div id="title"><a href="admin.php">', WT_I18N::translate('Administration'), '</a></div>',
	'<div id="links">',
	'<a href="index.php">', WT_I18N::translate('My Page'), '</a> | ',
	logout_link(),
	'<span>';
	$language_menu=WT_MenuBar::getLanguageMenu();
		if ($language_menu) {
			echo ' | ', $language_menu->getMenuAsList();
		}
	echo '</span>';
	if (WT_USER_CAN_ACCEPT && exists_pending_change()) {
	echo ' | <li><a href="javascript:;" onclick="window.open(\'edit_changes.php\',\'_blank\',\'width=600,height=500,resizable=1,scrollbars=1\'); return false;" style="color:red;">', WT_I18N::translate('Pending changes'), '</a></li>';
	}
	echo '</div>',
	'<div id="info">',
	WT_WEBTREES, ' ', WT_VERSION_TEXT,
	'<br />',
	WT_I18N::translate('Current Server Time:'), ' ', format_timestamp(time()),
	'<br />',
	WT_I18N::translate('Current User Time:'), ' ', format_timestamp(client_time()),
	'</div>',
	'</div>',
// Side menu 
	'<div id="admin_menu" class="ui-widget-content">',
	'<ul>',
	'<li><a ', (WT_SCRIPT_NAME=="admin.php" ? 'class="current" ' : ''), 'href="admin.php">',
	WT_I18N::translate('Administration'),
	'</a></li>';
if (WT_USER_IS_ADMIN) {
	echo
		'<li><ul>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_site_config.php" ? 'class="current" ' : ''), 'href="admin_site_config.php">',
		WT_I18N::translate('Site configuration'),
		'</a></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_site_logs.php" ? 'class="current" ' : ''), 'href="admin_site_logs.php">',
		WT_I18N::translate('Logs'),
		'</a></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_site_readme.php" ? 'class="current" ' : ''), 'href="admin_site_readme.php">', WT_I18N::translate('README documentation'), '</a></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_site_info.php" ? 'class="current" ' : ''), 'href="admin_site_info.php?action=phpinfo">', WT_I18N::translate('PHP information'), '</a></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_site_ipaddress.php" ? 'class="current" ' : ''), 'href="admin_site_ipaddress.php">', WT_I18N::translate('Manage sites'), '</a></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_site_clean.php" ? 'class="current" ' : ''), 'href="admin_site_clean.php">', WT_I18N::translate('Cleanup data directory'), '</a></li>',
		'</ul></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_trees_manage.php" ? 'class="current" ' : ''), 'href="admin_trees_manage.php">',
		WT_I18N::translate('Family trees'),
		'</a></li>';
} else {
	echo '<li>', WT_I18N::translate('Family trees'), '</li>';
}
echo '<li><ul>';
//-- gedcom list
foreach (get_all_gedcoms() as $ged_id=>$gedcom) {
	if (userGedcomAdmin(WT_USER_ID, $ged_id)) {
		echo
			'<li><span><a ', (WT_SCRIPT_NAME=="admin_trees_config.php" && WT_GED_ID==$ged_id ? 'class="current" ' : ''), 'href="admin_trees_config.php?ged='.rawurlencode($gedcom).'">',
			htmlspecialchars(get_gedcom_setting($ged_id, 'title')),
			'</a></span></li>';
	}
}
echo
	'<li><a ', (WT_SCRIPT_NAME=="admin_site_merge.php" ? 'class="current" ' : ''), 'href="admin_site_merge.php">', WT_I18N::translate('Merge records'), '</a></li>',
	'<li><a ', (WT_SCRIPT_NAME=="admin_site_other.php" ? 'class="current" ' : ''), 'href="admin_site_other.php">', WT_I18N::translate('Add unlinked records'), '</a></li>',
	'</ul></li>';
if (WT_USER_IS_ADMIN) {
	echo
		'<li><a ', (WT_SCRIPT_NAME=="admin_users_list.php" ? 'class="current" ' : ''), 'href="useradmin.php">',
		WT_I18N::translate('Users'),
		'</a></li>',
		'<li><ul>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_users_add.php" ? 'class="current" ' : ''), 'href="admin_users_add.php?action=createform">', WT_I18N::translate('Add a new user'), '</a></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_users_bulk.php" ? 'class="current" ' : ''), 'href="admin_users_bulk.php">',
		WT_I18N::translate('Send broadcast messages'),
		'<li><a ', (WT_SCRIPT_NAME=="admin_users_clean.php" ? 'class="current" ' : ''), 'href="admin_users_clean.php?action=cleanup">',
		WT_I18N::translate('Delete inactive users'),
		'</a></li>',
		'</ul></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_media.php" ? 'class="current" ' : ''), 'href="admin_media.php">',
		WT_I18N::translate('Media'),
		'</a></li>',
		'<li><ul>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_media_upload.php" ? 'class="current" ' : ''), 'href="admin_media_upload.php">',
		WT_I18N::translate('Upload media files'),
		'</a></li>',
		'</ul></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_modules.php" ? 'class="current" ' : ''), 'href="admin_modules.php">',
		WT_I18N::translate('Modules'),
		'</a></li>',
		'<li><ul>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_module_menus.php" ? 'class="current" ' : ''), 'href="admin_module_menus.php">',
		WT_I18N::translate('Menus'),
		'</a></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_module_tabs.php" ? 'class="current" ' : ''), 'href="admin_module_tabs.php">',
		WT_I18N::translate('Tabs'),
		'</a></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_module_blocks.php" ? 'class="current" ' : ''), 'href="admin_module_blocks.php">',
		WT_I18N::translate('Blocks'),
		'</a></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_module_sidebar.php" ? 'class="current" ' : ''), 'href="admin_module_sidebar.php">',
		WT_I18N::translate('Sidebar'),
		'</a></li>',
		'<li><a ', (WT_SCRIPT_NAME=="admin_module_reports.php" ? 'class="current" ' : ''), 'href="admin_module_reports.php">',
		WT_I18N::translate('Reports'),
		'</a></li>',
		'</ul></li>';
	foreach (WT_Module::getActiveModules() as $module) {
		if ($module instanceof WT_Module_Config) {
			echo '<li><span><a ', (WT_SCRIPT_NAME=="module.php" && safe_GET('mod')==$module->getName() ? 'class="current" ' : ''), 'href="', $module->getConfigLink(), '">', $module->getTitle(), '</a></span></li>';
		}
	}
}
echo
	'</ul>',
	'</div>',
	// Content -->
	'<div id="admin_content" class="ui-widget-content">';
