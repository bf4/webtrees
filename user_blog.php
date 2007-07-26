<?php
/**
 * User Blog Block
 *
 * This block allows users to have their own blog
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003  John Finlay and Others
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
 * @package PhpGedView
 * @subpackage Blocks
 */

$PGV_BLOCKS["print_user_news"]["name"]		= $pgv_lang["user_news_block"];
$PGV_BLOCKS["print_user_news"]["descr"]		= "user_news_descr";
$PGV_BLOCKS["print_user_news"]["type"]		= "user";
$PGV_BLOCKS["print_user_news"]["canconfig"]	= false;
$PGV_BLOCKS["print_user_news"]["config"]	= array("cache"=>0);

/**
 * Prints a user news/journal
 *
 */
function print_user_news($block=true, $config="", $side, $index) {
		global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION, $ctype, $TIME_FORMAT;

		$uname = getUserName();
		$usernews = getUserNews($uname);

		print "<div id=\"user_news\" class=\"block\">\n";
		print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
		print "<td class=\"blockh1\" >&nbsp;</td>";
		print "<td class=\"blockh2\" ><div class=\"blockhc\">";
		print_help_link("mygedview_myjournal_help", "qm");
		print "<b>".$pgv_lang["my_journal"]."</b>";
		print "</div></td>";
		print "<td class=\"blockh3\">&nbsp;</td></tr>";
		print "</table>";
		print "<div class=\"blockcontent\">";
		if ($block) print "<div class=\"small_inner_block, $TEXT_DIRECTION\">\n";
		if (count($usernews)==0) print $pgv_lang["no_journal"];
		foreach($usernews as $key=>$news) {
				$day = date("j", $news["date"]);
				$mon = date("M", $news["date"]);
				$year = date("Y", $news["date"]);
				print "<div class=\"person_box\">\n";
				$ct = preg_match("/#(.+)#/", $news["title"], $match);
				if ($ct>0) {
						if (isset($pgv_lang[$match[1]])) $news["title"] = preg_replace("/$match[0]/", $pgv_lang[$match[1]], $news["title"]);
				}
				print "<span class=\"news_title\">".PrintReady($news["title"])."</span><br />\n";
				print "<span class=\"news_date\">".get_changed_date("$day $mon $year")." - ".date($TIME_FORMAT, $news["date"])."</span><br /><br />\n";
				$ct = preg_match("/#(.+)#/", $news["text"], $match);
				if ($ct>0) {
						if (isset($pgv_lang[$match[1]])) $news["text"] = preg_replace("/$match[0]/", $pgv_lang[$match[1]], $news["text"]);
				}
				$ct = preg_match("/#(.+)#/", $news["text"], $match);
				if ($ct>0) {
						if (isset($pgv_lang[$match[1]])) $news["text"] = preg_replace("/$match[0]/", $pgv_lang[$match[1]], $news["text"]);
						if (isset($$match[1])) $news["text"] = preg_replace("/$match[0]/", $$match[1], $news["text"]);
				}
				$trans = get_html_translation_table(HTML_SPECIALCHARS);
				$trans = array_flip($trans);
				$news["text"] = strtr($news["text"], $trans);
				$news["text"] = nl2br($news["text"]);
				print PrintReady($news["text"])."<br /><br />\n";
				print "<a href=\"javascript:;\" onclick=\"editnews('$key'); return false;\">".$pgv_lang["edit"]."</a> | ";
				print "<a href=\"index.php?action=deletenews&amp;news_id=$key&amp;ctype=$ctype\" onclick=\"return confirm('".$pgv_lang["confirm_journal_delete"]."');\">".$pgv_lang["delete"]."</a><br />";
				print "</div><br />\n";
		}
		if ($block) print "</div>\n";
		if (!empty($uname)) print "<br /><a href=\"javascript:;\" onclick=\"addnews('$uname'); return false;\">".$pgv_lang["add_journal"]."</a>\n";
		print "</div>\n";
		print "</div>";
}
?>
