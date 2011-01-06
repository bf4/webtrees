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
	'webtrees'=>WT_THEME_DIR.'images/header.png',
	'help'=>WT_THEME_DIR.'images/help.png',
	'edit'=>WT_THEME_DIR.'images/edit.png',
	'email'=>WT_THEME_DIR.'images/email.png',
	'open'=>WT_THEME_DIR.'images/open.png',
	'close'=>WT_THEME_DIR.'images/close.png',
	'button_indi'=>WT_THEME_DIR.'images/indi.gif',
	'button_family'=>WT_THEME_DIR.'images/family.gif',
	'button_media'=>WT_THEME_DIR.'images/media.gif',
	'button_repository'=>WT_THEME_DIR.'images/repository.gif',
	'button_source'=>WT_THEME_DIR.'images/source.gif',
	'button_find_facts'=>WT_THEME_DIR.'images/find_facts.png',
	'zoomin'=>WT_THEME_DIR.'images/zoomin.pmg',
	'zoomout'=>WT_THEME_DIR.'images/zoomout.png',
	'minus'=>WT_THEME_DIR.'images/close.png',
	'plus'=>WT_THEME_DIR.'images/open.png',
	'remove'=>WT_THEME_DIR.'images/delete.png',
	'remove_grey'=>WT_THEME_DIR.'images/delete_grey.png',
	'rarrow2'=>WT_THEME_DIR.'images/rarrow2.gif',
	'larrow2'=>WT_THEME_DIR.'images/larrow2.gif',
	'darrow2'=>WT_THEME_DIR.'images/darrow2.gif',
	'uarrow2'=>WT_THEME_DIR.'images/uarrow2.gif',
	'rarrow'=>WT_THEME_DIR.'images/rarrow.gif',
	'larrow'=>WT_THEME_DIR.'images/larrow.gif',
	'darrow'=>WT_THEME_DIR.'images/darrow.gif',
	'uarrow'=>WT_THEME_DIR.'images/uarrow.gif',
	'rdarrow'=>WT_THEME_DIR.'images/rdarrow.gif',
	'ldarrow'=>WT_THEME_DIR.'images/ldarrow.gif',
	'ddarrow'=>WT_THEME_DIR.'images/ddarrow.gif',
	'udarrow'=>WT_THEME_DIR.'images/udarrow.gif',
	'sex_f_9x9'=>'images/sex_f_9x9.gif',
	'sex_m_9x9'=>'images/sex_m_9x9.gif',
	'sex_u_9x9'=>'images/sex_u_9x9.gif',

	// media images
	'media'=>WT_THEME_DIR.'images/media/media.png',
	'media_audio'=>WT_THEME_DIR.'images/media/audio.png',
	'media_doc'=>WT_THEME_DIR.'images/media/doc.png',
	'media_flash'=>WT_THEME_DIR.'images/media/flash.png',
	'media_ged'=>WT_THEME_DIR.'images/media/ged.png',
	'media_globe'=>WT_THEME_DIR.'images/media/globe.png',
	'media_html'=>WT_THEME_DIR.'images/media/html.pmg',
	'media_picasa'=>WT_THEME_DIR.'images/media/picasa.png',
	'media_pdf'=>WT_THEME_DIR.'images/media/pdf.png',
	'media_tex'=>WT_THEME_DIR.'images/media/tex.png',
	'media_wmv'=>WT_THEME_DIR.'images/media/wmv.png',
);

