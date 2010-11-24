<?php
/**
 * Module help text.
 *
 * This file is included from the application help_text.php script.
 * It simply needs to set $title and $text for the help topic $help_topic
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
 * @version $Id$
 */

if (!defined('WT_WEBTREES') || !defined('WT_SCRIPT_NAME') || WT_SCRIPT_NAME!='help_text.php') {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

switch ($help) {
case 'add_faq_item':
	$title=i18n::translate('Frequently asked questions');
	$text=
		i18n::translate('FAQs are lists of questions and answers, which allow you to explain the site\'s rules, policies, and procedures to your visitors.  Questions are typically concerned with privacy, copyright, user-accounts, unsuitable content, requirement for source-citations, etc.').
		'<br/><br/>'.
		i18n::translate('You may use HTML to format the answer and to add links to other wesbites.');
	break;

case 'add_faq_order':
	$title=i18n::translate('FAQ position');
	$text=i18n::translate('This field controls the order in which the FAQ items are displayed.<br /><br />You do not have to enter the numbers sequentially.  If you leave holes in the numbering scheme, you can insert other items later.  For example, if you use the numbers 1, 6, 11, 16, you can later insert items with the missing sequence numbers.  Negative numbers and zero are allowed, and can be used to insert items in front of the first one.<br /><br />When more than one FAQ item has the same position number, only one of these items will be visible.');
	break;

case 'add_faq_visibility':
	$title=i18n::translate('FAQ visibility');
	$text=i18n::translate('You can determine whether this FAQ will be visible regardless of GEDCOM, or whether it will be visible only to the current GEDCOM.<br /><ul><li><b>ALL</b>&nbsp;&nbsp;&nbsp;The FAQ will appear in all FAQ lists, regardless of GEDCOM.</li><li><b>%s</b>&nbsp;&nbsp;&nbsp;The FAQ will appear only in the currently active GEDCOM\'s FAQ list.</li></ul>', WT_GEDCOM);
	break;

case 'delete_faq_item':
	$title=i18n::translate('Delete FAQ item');
	$text=i18n::translate('This option will let you delete an item from the FAQ page');
	break;

case 'edit_faq_item':
	$title=i18n::translate('Edit FAQ item');
	$text=i18n::translate('This option will let you edit an item on the FAQ page.');
	break;

case 'movedown_faq_item':
	$title=i18n::translate('Move FAQ item down');
	$text=i18n::translate('This option will let you move an item downwards on the FAQ page.<br /><br />Each time you use this option, the FAQ Position number of this item is increased by one.  You can achieve the same effect by editing the item in question and changing the FAQ Position field.  When more than one FAQ item has the same position number, only one of these items will be visible.');
	break;

case 'moveup_faq_item':
	$title=i18n::translate('Move FAQ item up');
	$text=i18n::translate('This option will let you move an item upwards on the FAQ page.<br /><br />Each time you use this option, the FAQ Position number of this item is reduced by one.  You can achieve the same effect by editing the item in question and changing the FAQ Position field.  When more than one FAQ item has the same position number, only one of these items will be visible.');
	break;

}
