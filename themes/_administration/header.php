<?php
/**
 * Header for webtrees theme
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @subpackage Themes
 * @version $Id: header.php 9898 2010-11-20 03:35:38Z nigel $
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

echo
	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
	'<html xmlns="http://www.w3.org/1999/xhtml" ', i18n::html_markup(), '>',
	'<head>',
	'<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />',
	'<title>', htmlspecialchars($title), '</title>',
	'<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />',
	'<link rel="stylesheet" href="', $stylesheet, '" type="text/css" media="all" />',
	'<link rel="stylesheet" href="', WT_THEME_DIR, 'jquery/jquery-ui_theme.css" type="text/css" />';
	$javascript;
	$head; //-- additional header information

echo
	'</head>',
	'<body id="body" ',$bodyOnLoad, '>',
// Header
	'<div id="admin_head" class="ui-widget-content">',
		'<div id="info">',
			WT_WEBTREES, ' ', WT_VERSION_TEXT,
			'<br />',
			i18n::translate('Current Server Time:'), ' ', format_timestamp(time()),
			'<br />',
			i18n::translate('Current User Time:'), ' ', format_timestamp(client_time()),
		'</div>',
		'<div id="title">', i18n::translate('Administration'), '</div>',
	'</div>',
// Side menu 
	'<div id="admin_menu" class="ui-widget-content">',
		'<ul>',
			'<li>';	$class=""; if (curPageName()=="administration.php") {$class="current";} echo '<a class="',$class,'" href="administration.php">', i18n::translate('Administration'), '</a></li>',
			'<li><a href="index.php?ctype=user">', i18n::translate('Back to My Page'), '</li>',	
			'<li>';	$class=""; if (curPageName()=="admin_site_config.php") {$class="current";} echo '<a class="',$class, '" href="admin_site_config.php">', i18n::translate('Site'), '</a>',
				'<ul>',
					'<li>';	$class=""; if (curPageName()=="admin_site_config.php") {$class="current";} echo '<a class="' ,$class, '" href="admin_site_config.php">', i18n::translate('Server configuration'), '</a></li>',
					'<li>';	$class=""; if (curPageName()=="logs.php") {$class="current";} echo '<a class="' ,$class, '" href="logs.php">', i18n::translate('Logs'), '</a></li>',
					'<li>';	$class=""; if (curPageName()=="readme.php") {$class="current";} echo '<a class="' ,$class, '" href="readme.php">', i18n::translate('README documentation'), '</a></li>',
					'<li>';	$class=""; if (curPageName()=="wtinfo.php") {$class="current";} echo '<a class="' ,$class, '" href="wtinfo.php?action=phpinfo">', i18n::translate('PHP information'), '</a></li>',
					'<li>';	$class=""; if (curPageName()=="manageservers.php") {$class="current";} echo '<a class="' ,$class, '" href="manageservers.php">', i18n::translate('Manage sites'), '</a></li>',
				'</ul>',
			'</li>',
			'<li>';	$class=""; if (curPageName()=="ged_admin.php") {$class="current";} echo '<a class="' ,$class, '" href="ged_admin.php">', i18n::translate('Family trees'), '</a>',
				'<ul>',
					'<li>';	$class=""; if (curPageName()=="ged_admin.php") {$class="current";} echo '<a class="' ,$class, '" href="ged_admin.php">', i18n::translate('Manage GEDCOMs'), '</a></li>',
					'<li><span>', i18n::translate('Configure these trees'), ' :</span>',
						'<ul>';
							//-- gedcom list
							$gedcom_titles=get_gedcom_titles();
							if (get_site_setting('ALLOW_CHANGE_GEDCOM')) {
								foreach ($gedcom_titles as $gedcom_title) {
									$pagename = curPageName();
									$class=""; if ($gedcom_title->gedcom_title==PrintReady(get_gedcom_setting(WT_GED_ID, 'title')) && $pagename == 'ged_config.php') {$class="current";} 
									echo '<li><a class="', $class, '" href="ged_config.php?ctype=gedcom&amp;ged='.rawurlencode($gedcom_title->gedcom_name), '">', $gedcom_title->gedcom_title,'</a></li>';					
								}
							}
						echo '</ul>',
					'</li>',
				'</ul>',
			'</li>',
			'<li>';	$class=""; if (curPageName()=="user_list.php") {$class="current";} echo '<a class="' ,$class, '" href="user_list.php">', i18n::translate('Users'), '</a>',
				'<ul>',
					'<li>';	$class=""; if (curPageName()=="user_list.php") {$class="current";} echo '<a class="' ,$class, '" href="user_list.php">', i18n::translate('List users'), '</a></li>',
					'<li>';	$class=""; if (curPageName()=="user_admin.php") {$class="current";} echo '<a class="' ,$class, '" href="user_admin.php">', i18n::translate('Add user'), '</a></li>',
					'<li>';	$class=""; if (curPageName()=="#") {$class="current";} echo '<a class="' ,$class, '" href="#">', i18n::translate('Bulk messaging'), '</a></li>',
				'</ul>',
			'</li>',
			'<li><a href="admin_modules.php">', i18n::translate('Modules'), '</a>',
				'<ul>',
					'<li><a href="admin_modules.php">', i18n::translate('Manage modules'), '</a></li>',
					'<li><a href="admin_modules.php#tabs">', i18n::translate('Tabs - manage'), '</a></li>',
					'<li><a href="admin_modules.php#sidebars">', i18n::translate('Sidebar - manage'), '</a></li>',
					'<li><a href="admin_modules.php.ui-menus">', i18n::translate('Menus - manage'), '</a></li>',
					'<li><span>-----------------------------</span>',
						'<ul>';
							foreach (WT_Module::getInstalledModules() as $module) {
								$status=WT_DB::prepare("SELECT status FROM `##module` WHERE module_name=?")->execute(array($module->getName()))->fetchOne();
								if ($module instanceof WT_Module_Config)
									echo '<li><a href="../', $module->getConfigLink(), '">', $module->getTitle(), ' - configure</a></li>';
							}
						echo '</ul>',
					'</li>',
			'<li><a href="user_info.php">', i18n::translate('Media'), '</a>',
				'<ul>',
					'<li><a href="admin_media.php">', i18n::translate('Manage media'), '</a></li>',
					'<li><a href="#">', i18n::translate('Upload media'), '</a></li>',
					'<li><a href="#">', i18n::translate('Media firewall'), '</a><li>',
				'</ul>',
			'</li>',
		'</ul>',
	'</div>',
// Content -->
'<div id="admin_content" class="ui-widget-content">';
