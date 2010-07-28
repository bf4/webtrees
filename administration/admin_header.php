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
 * @version $Id: header.php 7095 2010-03-01 19:33:01Z veit $
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
	'<link rel="stylesheet" href="admin_style.css" type="text/css" media="all" />',
	'<link rel="stylesheet" href="../js/jquery/css/jquery-ui.custom.css" type="text/css" />';

echo $javascript;
echo $head; //-- additional header information
echo
	'<script type="text/javascript" src="../js/jquery/jquery.min.js"></script>',
	'<script type="text/javascript" src="../js/jquery/jquery-ui.min.js"></script>',
	'<script type="text/javascript" src="dataTables-1.7/media/js/jquery.dataTables.min.js"></script>';
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
});

</script>
<?php
echo
	'</head>',
	'<body id="body" ',$bodyOnLoad, '>';
// Header
echo
	'<div id="admin_head">',
		'<div id="logo"><img src="images/header.jpg" width="281" height="50" alt="" /></div>',
		'<div id="info">',
			WT_WEBTREES, ' ', WT_VERSION_TEXT,
			'<br />',
			i18n::translate('Current Server Time:'),
			' ', format_timestamp(time()),
			'<br />',
			i18n::translate('Current User Time:'),
			' ', format_timestamp(client_time()),

		'</div>',
		'<div id="title">', i18n::translate('Administration'), '</div>',
		'<img src="images/hline.gif" width="100%" height="3" alt="" />',
	'</div>';
		
// Side menu 
echo
	'<div id="admin_menu">',
		'<dl><dt><a href="ged_config.php"><img src="images/admin.png" />', i18n::translate('Administration'), '</a></dt></dl>',
		'<dl><dt><a href="../index.php?ctype=user"><img src="images/my_page.png" />', i18n::translate('Back to My Page'), '</dt></dl>',	
		'<dl>',
			'<dt><a href="site_config.php"><img src="images/gedcom.png" />', i18n::translate('Site'), '</a></dt>',
			'<dd><a href="site_config.php">', i18n::translate('Configuration'), '</a></dd>',
			'<dd><a href="logs.php">', i18n::translate('Logs'), '</a></dd>',
			'<dd><a href="readme.php">', i18n::translate('README documentation'), '</a></dd>',
			'<dd><a href="wtinfo.php?action=phpinfo">', i18n::translate('PHP information'), '</a></dd>',
			'<dd><a href="manageservers.php">', i18n::translate('Manage sites'), '</a></dd>',
		'</dl>',
		'<dl>',
			'<dt><a href="ged_admin.php"><img src="images/tree.png" />', i18n::translate('GEDCOMs'), '</a></dt>',
			'<dd><a href="ged_admin.php">', i18n::translate('Manage GEDCOMs'), '</a></dd>';
				//-- gedcom list
				$gedcom_titles=get_gedcom_titles();
				if (get_site_setting('ALLOW_CHANGE_GEDCOM')) {
					foreach ($gedcom_titles as $gedcom_title) {
						echo '<dd><a href="', PrintReady(encode_url('ged_config.php?ctype=gedcom&ged='.$gedcom_title->gedcom_name)), '">', PrintReady($gedcom_title->gedcom_title, true),' - configure</a></dd>';
					}
				}
		echo '</dl>',
		'<dl><dt><a href="user_info.php"><img src="images/users.png" />', i18n::translate('Users'), '</a></dt',
			'<dd><a href="user_info.php">', i18n::translate('Information'), '</a></dd>',
			'<dd><a href="user_list.php">', i18n::translate('Manage users'), '</a></dd>',
			'<dd><a href="user_admin.php?action=createform">', i18n::translate('Add user'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Bulk messaging'), '</a></dd>',
		'</dl>',
		'<dl><dt><a href="../admin_modules.php"><img src="images/modules.png" />', i18n::translate('Modules'), '</a></dt',
			'<dd><a href="../admin_modules.php">', i18n::translate('Manage modules'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Tabs - configure'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Sidebar - configure'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Menus - configure'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Google Map - configure'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Lightbox - configure'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Sitemap - configure'), '</a></dd>',
		'</dl>',
		'<dl><dt><a href="user_info.php"><img src="images/media.png" />', i18n::translate('Media'), '</a></dt>',
			'<dd><a href="#">', i18n::translate('Manage media'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Upload media'), '</a></dd>',
			'<dd><a href="#">', i18n::translate('Media firewall'), '</a></dd>',
		'</dl>',
	'</div>',
// Content -->
'<div id="admin_content">';
