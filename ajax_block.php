<?php
/**
 * Generate a gedcom/user welcome page block using ajax
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2008  Greg Roach.  All rights reserved.
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
 * @package PhpGedView
 * @subpackage Edit
 * @version $Id$
 */

require './config.php';
header("Content-Type: text/html; charset=$CHARACTER_SET");

// We have finished writing to $_SESSION, so release the lock
session_write_close();

// Unfortunately, we haven't used any naming convention, which means we need
// to load every block in order to find the one that has the "print" function
// that we need.
$PGV_BLOCKS=array();
$dh=dir('blocks');
while ($file=$dh->read()) {
	if (preg_match('/\.php$/', $file)) {
		require_once 'blocks/'.$file;
	}
}
$dh->close();
if (is_dir('modules')) {
	$dh=dir('modules');
	while ($module=$dh->read()) {
		if (is_dir('modules/'.$module.'/blocks')) {
			$dh2=dir('modules/'.$module.'/blocks');
			while ($file=$dh2->read()) {
				if (preg_match('/\.php$/', $file)) {
					require_once 'modules/'.$module.'/blocks/'.$file;
				}
			}
			$dh2->close();
		}
	}
}
$dh->close();

// Arguments:
$name  =safe_GET('name', '[a-zA-Z0-9_]+');
$block =safe_GET_bool('block');
$config=safe_GET('config', PGV_REGEX_UNSAFE);
$side  =safe_GET('side', array('main', 'right'));
$index =safe_GET('index', PGV_REGEX_INTEGER);
$ctype =safe_GET('ctype', array('user', 'gedcom'));

// Load and execute the block
if (function_exists($name)) {
	$name($block, unserialize($config), $side, $index);
} else {
	die('invalid block: '.$name);
}
