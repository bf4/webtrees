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
 * @version $Id$
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

echo
	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
	'<html xmlns="http://www.w3.org/1999/xhtml" ',  i18n::html_markup(), '>',
	'<head>',
	'<title>', htmlspecialchars($title), '</title>',
	'<link rel="shortcut icon" href="', $FAVICON, '" type="image/x-icon" />',
	'<link type="text/css" href="css/redmond/jquery-ui-1.8.5.custom.css" rel="stylesheet" />',
	'<link rel="stylesheet" href="admin_style.css" type="text/css" media="all" />',
	$javascript,
	$head; //-- additional header information
?>
<script type="text/javascript">

jQuery(document).ready(function(){
	jQuery("#list").dataTable({
		"oLanguage": {
			"sLengthMenu": 'Display <select><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="50">50</option><option value="-1">All</option></select> records'
		},
		"bJQueryUI": true,
		"bAutoWidth":false,
		"aaSorting": [[ 1, "asc" ]],
		"iDisplayLength": 10,
		"sPaginationType": "full_numbers"
	});
	
	jQuery(function() {
		$( "button, input:submit, a", ".btn" ).button();
		$( "a", ".btn" ).click(function() { return false; });
	});
jQuery("button").click(function () {
      $("#slide1").slideToggle("slow");
    });

});
</script>
<?php

echo
	'</head>',
	'<body id="body" ',$bodyOnLoad, '>';
// Header
echo
	'<div id="admin_head" class="ui-widget-content">',
		'<div id="logo"><img src="images/header.jpg" width="281" height="50" alt="" /></div>',
		'<div id="info">',
			WT_WEBTREES, ' ', WT_VERSION_TEXT,
			'<br />',
			i18n::translate('Current Server Time:'), ' ', format_timestamp(time()),
			'<br />',
			i18n::translate('Current User Time:'), ' ', format_timestamp(client_time()),
		'</div>',
		'<div id="title">', i18n::translate('Administration'), '</div>',
		//'<img src="images/hline.gif" width="100%" height="3" alt="" />',
	'</div>';
		
// Side menu 
echo
	'<div id="admin_menu" class="ui-widget-content">',
		'<dl><dt><a href="ged_config.php">', i18n::translate('Administration'), '</a></dt></dl>',
		'<dl><dt><a href="../index.php?ctype=user">', i18n::translate('Back to My Page'), '</dt></dl>',	
		'<dl>',
			'<dt>';
			$class=""; if (curPageName()=='siteconfig.php') {$class="current";} 
				echo '<a class="' ,$class, '" href="siteconfig.php">', i18n::translate('Site'), '</a></dt>',
				'<dd><a class="' ,$class, '" href="siteconfig.php">', i18n::translate('Server configuration'), '</a></dd>';
			$class=""; if (curPageName()=='logs.php') {$class="current";} 
				echo '<dd><a class="' ,$class, '" href="logs.php">', i18n::translate('Logs'), '</a></dd>';
			$class=""; if (curPageName()=='readme.php') {$class="current";} 
				echo '<dd><a class="' ,$class, '" href="readme.php">', i18n::translate('README documentation'), '</a></dd>';
			$class=""; if (curPageName()=='wtinfo.php') {$class="current";} 
				echo '<dd><a class="' ,$class, '" href="wtinfo.php?action=phpinfo">', i18n::translate('PHP information'), '</a></dd>';
			$class=""; if (curPageName()=='manageservers.php') {$class="current";} 
				echo '<dd><a class="' ,$class, '" href="manageservers.php">', i18n::translate('Manage sites'), '</a></dd>',
		'</dl>',
		'<dl>',
			'<dt><a href="ged_admin.php">', i18n::translate('GEDCOMs'), '</a></dt>',
			'<dd><a href="ged_admin.php">', i18n::translate('Manage GEDCOMs'), '</a></dd>',
			'<dd><i>Configure these :</i></dd>';
			//-- gedcom list
				$gedcom_titles=get_gedcom_titles();
				if (get_site_setting('ALLOW_CHANGE_GEDCOM')) {
					foreach ($gedcom_titles as $gedcom_title) {
						$pagename = curPageName();
						$class=""; if ($gedcom_title->gedcom_title == PrintReady(get_gedcom_setting(WT_GED_ID, 'title')) && $pagename == 'ged_config.php') {$class="current";} 
						echo '<dd class="indent"><a class="', $class, '" href="', PrintReady(urlencode('ged_config.php?ctype=gedcom&ged='.$gedcom_title->gedcom_name)), '">', PrintReady($gedcom_title->gedcom_title, true),'</a></dd>';
						
					}
				}
		echo '</dl>',
		'<dl><dt><a href="user_stats.php">', i18n::translate('Users'), '</a></dt',
			'<dd><a href="user_stats.php">', i18n::translate('User Statistics'), '</a></dd>',
			'<dd><a href="user_list.php">', i18n::translate('Manage users'), '</a></dd>',
			'<dd><a href="user_admin.php?action=createform">', i18n::translate('Add user'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Bulk messaging'), '</a></dd>',
		'</dl>',
		'<dl><dt><a href="admin_modules.php">', i18n::translate('Modules'), '</a></dt',
			'<dd><a href="admin_modules.php">', i18n::translate('Manage modules'), '</a></dd>',
			'<dd class="indent"><a href="admin_modules.php#tabs">', i18n::translate('Tabs - manage'), '</a></dd>',
			'<dd class="indent"><a href="admin_modules.php#sidebars">', i18n::translate('Sidebar - manage'), '</a></dd>',
			'<dd class="indent"><a href="admin_modules.php#menus">', i18n::translate('Menus - manage'), '</a></dd>',
			'<dd>----------------------------</dd>';
			foreach (WT_Module::getInstalledModules() as $module) {
				$status=WT_DB::prepare("SELECT status FROM `##module` WHERE module_name=?")->execute(array($module->getName()))->fetchOne();
				if ($module instanceof WT_Module_Config)
					echo '<dd><a href="../', $module->getConfigLink(), '">', $module->getTitle(), ' - configure</a></dd>';
			}
		echo '</dl>',
		'<dl><dt><a href="user_info.php">', i18n::translate('Media'), '</a></dt>',
			'<dd><a href="admin_media.php">', i18n::translate('Manage media'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Upload media'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Media firewall'), '</a></dd>',
		'</dl>',
	'</div>',
// Content -->
'<div id="admin_content">';
