<?php
/**
 * Popup window for Editing news items
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2005  PGV Development Team
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
 * @package webtrees
 */

define('WT_SCRIPT_NAME', 'editnews.php');
require './includes/session.php';

print_simple_header(WT_I18N::translate('Add/edit journal/news entry'));

if (!WT_USER_ID) {
	echo WT_I18N::translate('<b>Access Denied</b><br />You do not have access to this resource.');
	print_simple_footer();
	exit;
}

$action  =safe_GET('action', array('compose', 'save', 'delete'), 'compose');
$news_id =safe_GET('news_id');
$username=safe_REQUEST($_REQUEST, 'username');
$date    =safe_POST('date', WT_REGEX_UNSAFE);
$title   =safe_POST('title', WT_REGEX_UNSAFE);
$text    =safe_POST('text', WT_REGEX_UNSAFE);

if (empty($username)) $username=$GEDCOM;

if ($action=="compose") {
	echo '<span class="subheaders">'.WT_I18N::translate('Add/edit journal/news entry').'</span>';
	?>
	<script language="JavaScript" type="text/javascript">
		function checkForm(frm) {
			if (frm.title.value=="") {
				alert('<?php echo WT_I18N::translate('Please enter a title.'); ?>');
				document.messageform.title.focus();
				return false;
			}
			<?php if (!array_key_exists('ckeditor', WT_Module::getActiveModules())) { //will be empty for FCK. FIXME, use FCK API to check for content.
			?>
			if (frm.text.value=="") {
				alert('<?php echo WT_I18N::translate('Please enter some text for this News or Journal entry.'); ?>');
				document.messageform.text.focus();
				return false;
			}
			<?php } ?>
			return true;
		}
	</script>
	<?php
	echo "<br /><form name=\"messageform\" method=\"post\" action=\"editnews.php?action=save&news_id=".$news_id."\" onsubmit=\"return checkForm(this);";
	echo "\">";
	if ($news_id) {
		$news = getNewsItem($news_id);
	} else {
		$news = array();
		$news["username"] = $username;
		$news["date"] = client_time();
		$news["title"] = "";
		$news["text"] = "";
	}
	echo "<input type=\"hidden\" name=\"username\" value=\"".$news["username"]."\" />";
	echo "<input type=\"hidden\" name=\"date\" value=\"".$news["date"]."\" />";
	echo "<table>";
	echo "<tr><td align=\"right\">".WT_I18N::translate('Title:')."</td><td><input type=\"text\" name=\"title\" size=\"50\" value=\"".$news["title"]."\" /><br /></td></tr>";
	echo "<tr><td valign=\"top\" align=\"right\">".WT_I18N::translate('Entry Text:')."<br /></td>";
	echo "<td>";
	if (array_key_exists('ckeditor', WT_Module::getActiveModules())) {
		require_once WT_ROOT.'modules/ckeditor/ckeditor.php';
		$oCKeditor = new CKEditor();
		$oCKeditor->basePath =  './modules/ckeditor/';
		$oCKeditor->config['width'] = 700;
		$oCKeditor->config['height'] = 250;
		$oCKeditor->config['AutoDetectLanguage'] = false ;
		$oCKeditor->config['DefaultLanguage'] = 'en';
		$oCKeditor->editor('text', $news["text"]);
	} else { //use standard textarea
		echo "<textarea name=\"text\" cols=\"80\" rows=\"10\">".$news["text"]."</textarea>";
	}
	echo "<br /></td></tr>";
	echo "<tr><td></td><td><input type=\"submit\" value=\"".WT_I18N::translate('Save')."\" /></td></tr>";
	echo "</table>";
	echo "</form>";
} else if ($action=="save") {
	$date=time()-$_SESSION["timediff"];
	if (empty($title)) $title="No Title";
	if (empty($text)) $text="No Text";
	$message = array();
	if ($news_id) {
		$message["id"]=$news_id;
	}
	$message["username"] = $username;
	$message["date"]=$date;
	$message["title"] = $title;
	$message["text"] = $text;
	if (addNews($message)) {
		echo WT_I18N::translate('News/Journal entry successfully saved.');
	}
} else if ($action=="delete") {
	if (deleteNews($news_id)) echo WT_I18N::translate('The news/journal entry has been deleted.');
}
echo "<center><br /><br /><a href=\"javascript:;\" onclick=\"if (window.opener.refreshpage) window.opener.refreshpage(); window.close();\">".WT_I18N::translate('Close Window')."</a><br /></center>";

print_simple_footer();
