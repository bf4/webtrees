<?php
/**
 * Class to support internationalisation (i18n) functionality.
 *
 * Copyright (C) 2010 Greg Roach
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
 * @author Greg Roach
 * @version $Id$
 *
 * We use gettext to provide translation.  You should configure xgettext to
 * search for:
 * translate()
 * plural()
 *
 * We wrap the Zend_Translate gettext library, to allow us to add extra
 * functionality, such as mixed RTL and LTR text.
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_CLASS_I18N_PHP', '');

require_once PGV_ROOT.'library/Zend/Translate.php';

class i18n {
	static private $translation_adapter;

	static public function setLocale($locale='auto') {
		self::$translation_adapter=new Zend_Translate(
			'gettext',
			PGV_ROOT.'language',
			$locale,
			array('scan'=>Zend_Translate::LOCALE_FILENAME)
		);
	}

	// echo i18n::translate('Hello World!');
	// echo i18n::translate('The %s sat on the mat', 'cat');
	static public function translate(/* var_args */) {
		$args=func_get_args();
		$args[0]=self::$translation_adapter->_($args[0]);
		return call_user_func_array('sprintf', $args);
	}

	// echo i18n::plural('There is an error', 'There are errors', $num_errors);
	// echo i18n::plural('There is one error', 'There are %d errors', $num_errors);
	// echo i18n::plural('There is %$1d %$2s cat', 'There are %$1d %$2s cats', $num, $num, $colour);
	static public function plural(/* var_args */) {
		$args=func_get_args();
		$string=self::$translation_adapter->plural($args[0], $args[1], $args[2]);
		array_splice($args, 0, 3, array($string));
		return call_user_func_array('sprintf', $args);
	}
}
