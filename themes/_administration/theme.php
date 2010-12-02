<?php
/**
 * Standard theme
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
 * @version $Id: theme.php 9831 2010-11-13 04:43:15Z nigel $
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$theme_name = "_administration"; // need double quotes, as file is scanned/parsed by script
$stylesheet       = WT_THEME_DIR.'style.css';
$headerfile       = WT_THEME_DIR.'header.php';
$footerfile       = WT_THEME_DIR.'footer.php';
$WT_USE_HELPIMG   = true;

//- main icons
$WT_IMAGES=array(
	'webtrees'=>WT_THEME_DIR.'images/webtrees_s.png',
	'help'=>WT_THEME_DIR.'images/help.jpg',
	'email'=>WT_THEME_DIR.'images/email.png',
	'open'=>WT_THEME_DIR.'images/open.png',
	'close'=>WT_THEME_DIR.'images/close.png',
	'button_indi'=>WT_THEME_DIR.'images/indi.gif',
	'zoomin'=>WT_THEME_DIR.'images/zoomin.gif', //added only to avoid error messages from print_header function
	'zoomout'=>WT_THEME_DIR.'images/zoomout.gif', //added only to avoid error messages from print_header function
	'minus'=>WT_THEME_DIR.'images/minus.gif', //added only to avoid error messages from print_header function
	'plus'=>WT_THEME_DIR.'images/plus.gif', //added only to avoid error messages from print_header function
	'rarrow2'=>WT_THEME_DIR.'images/rarrow2.gif', //added only to avoid error messages from print_header function
	'larrow2'=>WT_THEME_DIR.'images/larrow2.gif', //added only to avoid error messages from print_header function
	'darrow2'=>WT_THEME_DIR.'images/darrow2.gif', //added only to avoid error messages from print_header function
	'uarrow2'=>WT_THEME_DIR.'images/uarrow2.gif', //added only to avoid error messages from print_header function
);