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
	static private $alphabet;
	static private $collation;
	static private $long_date_format;
	static private $short_date_format;
	static private $time_format_hm;
	static private $time_format_hms;
	static private $list_separator;
	static private $text_direction;


	static public function setLocale($locale='auto') {
		self::$translation_adapter=new Zend_Translate(
			'gettext',
			PGV_ROOT.'language',
			$locale,
			array('scan'=>Zend_Translate::LOCALE_FILENAME)
		);
		// By using specially named strings to store language parameters, we can store all the
		// settings, translations and other support for each language in one file.
		// This makes it simple for users to add/remove/share languages.

		// I18N: This is a space separated list of initial letters for lists of names, etc.  Multi-letter characters are OK, e.g. "A B C D E F G CH H I ..."
		self::$alphabet=i18n::translate('LANGUAGE_ALPHABET');
		// I18N: This is either ltr for languages written in left-to-right scripts such as latin or cyrillic and rtl for languages written in right-to-left scripts such as arabic or hebrew.
		self::$text_direction=i18n::translate('LANGUAGE_TEXT_DIRECTION');
		// I18N: This is the name of the MySQL utf8 collation sequence for this language.  See http://dev.mysql.com/doc/refman/5.1/en/se-db2-collations.html
		self::$collation=i18n::translate('LANGUAGE_COLLATION');
		// I18N: This is the format string for full dates, such as 14 October 1908.  See http://php.net/date for codes
		self::$long_date_format=i18n::translate('LANGUAGE_LONG_DATE_FORMAT');
		// I18N: This is the format string for short dates, such as 14 Oct 1908.  See http://php.net/date for codes
		self::$short_date_format=i18n::translate('LANGUAGE_SHORT_DATE_FORMAT');
		// I18N: This is the format string for times with hours, minutes and seconds, such as 10:23:12pm.  See http://php.net/date for codes
		self::$time_format_hm=i18n::translate('LANGUAGE_TIME_FORMAT_HM');
		// I18N: This is the puncutation symbol used to separate items in a list.  e.g. the <comma><space> in "red, green, yellow and blue"
		self::$time_format_hms=i18n::translate('LANGUAGE_TIME_FORMAT_HMS');
		// I18N: This is the format string for times with hours and seconds, such as 10:23pm.  See http://php.net/date for codes
		self::$list_separator=i18n::translate('LANGUAGE_LIST_SEPARATOR');
		// I18N: This is the puncutation symbol used to separate the final items in a list.  e.g. the <space><comma><space> in "red, green, yellow and blue"
		self::$list_separator_last=i18n::translate('LANGUAGE_LIST_SEPARATOR_LAST');
	}

	// echo i18n::translate('Hello World!');
	// echo i18n::translate('The %s sat on the mat', 'cat');
	static public function translate(/* var_args */) {
		$args=func_get_args();
		$args[0]=self::$translation_adapter->_($args[0]);
		// TODO: for each embedded string, if the text-direction is the opposite of the
		// page language, then wrap it in &ltr; on LTR pages and &rtl; on RTL pages.
		// This will ensure that non/weakly direction characters in the main string
		// are displayed correctly by the browser's BIDI algorithm.
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

	// Determine whether a message has a translation in the current language.
	// if (i18n::is_translated('_CUSTOM_FACT'))
	static public function is_translated($string) {
		return i18n::translate($string)!=$string;
	}
	static public function is_not_translated($string) {
		return i18n::translate($string)==$string;
	}

	// Convert an array to a list.  For example
	// array("red", "green", "yellow", "blue") => "red, green, yellow and blue"
	static public function make_list($array) {
		// TODO: for each array element, if the text-direction is the opposite of the
		// page language, then wrap it in &ltr; on LTR pages and &rtl; on RTL pages.
		// This will ensure that non/weakly direction characters in the main string
		// are displayed correctly by the browser's BIDI algorithm.
		$n=count($array);
		switch ($n) {
		case 0:
			return '';
		case 1:
			return $array(0);
		default:
			return implode(self::$list_separator, array_slice($array, 0, $n-1)).self::$list_separator_last.$array[$n-1];
		}
	}
}
