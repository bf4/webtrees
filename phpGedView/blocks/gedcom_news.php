<?php
/**
 * Gedcom News Block
 *
 * This block allows administrators to enter news items for the active gedcom
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
 * @subpackage Blocks
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_GEDCOM_NEWS_PHP', '');

$PGV_BLOCKS['print_gedcom_news']['name']		= i18n::translate('GEDCOM News');
$PGV_BLOCKS['print_gedcom_news']['descr']		= i18n::translate('The GEDCOM News block shows the visitor news releases or articles posted by an admin user.<br /><br />The News block is a good place to announce a significant database update, a family reunion, or the birth of a child.');
$PGV_BLOCKS['print_gedcom_news']['type']		= 'gedcom';
$PGV_BLOCKS['print_gedcom_news']['canconfig']	= true;
$PGV_BLOCKS['print_gedcom_news']['config']		= array(
	'cache'=>7,
	'limit' => 'nolimit',
	'flag' => 0
);

/**
 * Prints a gedcom news/journal
 *
 * @todo Add an allowed HTML translation
 */
function print_gedcom_news($block = true, $config='', $side, $index)
{
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION, $ctype, $PGV_BLOCKS;

	if(empty($config)) {
		$config = $PGV_BLOCKS['print_gedcom_news']['config'];
	}
	if ($config['flag'] == 0) {
		$config['limit'] = 'nolimit';
	}
	if (isset($_REQUEST['gedcom_news_archive'])) {
		$config['limit'] = 'nolimit';
		$config['flag'] = 0;
	}

	$usernews = getUserNews(PGV_GEDCOM);

	$id = "gedcom_news";
	$title = "";
	if ($PGV_BLOCKS['print_gedcom_news']['canconfig']) {
		if ($ctype=="gedcom" && PGV_USER_GEDCOM_ADMIN || $ctype=="user" && PGV_USER_ID) {
			if ($ctype=="gedcom") {
				$name = PGV_GEDCOM;
			} else {
				$name = PGV_USER_NAME;
			}
			$title .= "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name={$name}&amp;ctype={$ctype}&amp;action=configure&amp;side={$side}&amp;index={$index}', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">"
			."<img class=\"adminicon\" src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}\" width=\"15\" height=\"15\" border=\"0\" alt=\"".i18n::translate('Configure')."\" /></a>\n"
			;
		}
	}
	$title .= i18n::translate('News');
	if(PGV_USER_GEDCOM_ADMIN) {
		$title .= help_link('index_gedcom_news_adm');
	} else {
		$title .= help_link('index_gedcom_news');
	}
	$content = "";
	if(count($usernews) == 0)
	{
		$content .= i18n::translate('No News articles have been submitted.').'<br />';
	}
	$c = 0;
	$td = time();
	foreach($usernews as $news)
	{
		if ($config['limit'] == 'count') {
			if ($c >= $config['flag']) {
				break;
			}
			$c++;
		}
		if ($config['limit'] == 'date') {
			if (floor(($td - $news['date']) / 86400) > $config['flag']) {
				break;
			}
		}
		//		print "<div class=\"person_box\" id=\"{$news['anchor']}\">\n";
		$content .= "<div class=\"news_box\" id=\"{$news['anchor']}\">\n";

		// Look for $GLOBALS substitutions in the News title
		$newsTitle = embed_globals($news['title']);
		$content .= "<span class=\"news_title\">".PrintReady($newsTitle)."</span><br />\n";
		$content .= "<span class=\"news_date\">".format_timestamp($news['date'])."</span><br /><br />\n";

		// Look for $GLOBALS substitutions in the News text
		$newsText = embed_globals($news['text']);
		$trans = get_html_translation_table(HTML_SPECIALCHARS);
		$trans = array_flip($trans);
		$newsText = strtr($newsText, $trans);
		$newsText = nl2br($newsText);
		$content .= PrintReady($newsText)."<br />\n";

		// Print Admin options for this News item
		if(PGV_USER_GEDCOM_ADMIN) {
			$content .= "<hr size=\"1\" />"
			."<a href=\"javascript:;\" onclick=\"editnews('".$news['id']."'); return false;\">".i18n::translate('Edit')."</a> | "
			."<a href=\"".encode_url("index.php?action=deletenews&news_id=".$news['id']."&ctype={$ctype}")."\" onclick=\"return confirm('".i18n::translate('Are you sure you want to delete this News entry?')."');\">".i18n::translate('Delete')."</a><br />";
		}
		$content .= "</div>\n";
	}
	$printedAddLink = false;
	if (PGV_USER_GEDCOM_ADMIN) {
		$content .= "<a href=\"javascript:;\" onclick=\"addnews('".urlencode(PGV_GEDCOM)."'); return false;\">".i18n::translate('Add a News article')."</a>";
		$printedAddLink = true;
	}
	if ($config['limit'] == 'date' || $config['limit'] == 'count') {
		if ($printedAddLink) $content .= "&nbsp;&nbsp;|&nbsp;&nbsp;";
		$content .= "<a href=\"".encode_url("index.php?gedcom_news_archive=yes&ctype={$ctype}")."\">".i18n::translate('View archive')."</a>";
		$content .= help_link('gedcom_news_archive').'<br />';
	}

	global $THEME_DIR;
	if ($block) {
		require $THEME_DIR.'templates/block_small_temp.php';
	} else {
		require $THEME_DIR.'templates/block_main_temp.php';
	}
}

function print_gedcom_news_config($config)
{
	global $ctype, $PGV_BLOCKS;
	if (empty ($config)) $config = $PGV_BLOCKS["print_gedcom_news"]["config"];
	if (!isset ($config["limit"])) $config["limit"] = "nolimit";
	if (!isset ($config["flag"])) $config["flag"] = 0;
	if (!isset($config["cache"])) $config["cache"] = $PGV_BLOCKS["print_gedcom_news"]["config"]["cache"];

	// Limit Type
	echo
		'<tr><td class="descriptionbox wrap width33">',
		i18n::translate('Limit display by:'), help_link('gedcom_news_limit'),
		'</td><td class="optionbox"><select name="limit"><option value="nolimit"',
		($config['limit'] == 'nolimit'?' selected="selected"':'').">",
		i18n::translate('No limit')."</option>",
		'<option value="date"'.($config['limit'] == 'date'?' selected="selected"':'').">".i18n::translate('Age of item')."</option>",
		'<option value="count"'.($config['limit'] == 'count'?' selected="selected"':'').">".i18n::translate('Number of items')."</option>",
		'</select></td></tr>';

	// Flag to look for
	echo '<tr><td class="descriptionbox wrap width33">';
	echo i18n::translate('Limit:'), help_link('gedcom_news_flag');
	echo '</td><td class="optionbox"><input type="text" name="flag" size="4" maxlength="4" value="'.$config['flag'].'" /></td></tr>';

	// Cache file life
	if ($ctype=="gedcom") {
		echo '<tr><td class="descriptionbox wrap width33">';
		echo i18n::translate('Cache file life'), help_link('cache_life');
		echo '</td><td class="optionbox">';
		echo '<input type="text" name="cache" size="2" value="', $config['cache'], '" />';
		echo "</td></tr>";
	}
}
?>
