<?php
/**
 * Gedcom News Block
 *
 * This block allows administrators to enter news items for the active gedcom
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

$PGV_BLOCKS['print_gedcom_news']['name']		= $pgv_lang['gedcom_news_block'];
$PGV_BLOCKS['print_gedcom_news']['descr']		= 'gedcom_news_descr';
$PGV_BLOCKS['print_gedcom_news']['type']		= 'gedcom';
$PGV_BLOCKS['print_gedcom_news']['canconfig']	= true;
$PGV_BLOCKS['print_gedcom_news']['config']		= array(
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
	global $pgv_lang, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION, $GEDCOM, $command, $TIME_FORMAT, $VERSION, $PGV_BLOCKS;

	if(empty($config))
	{
		$config = $PGV_BLOCKS['print_gedcom_news']['config'];
	}
	if ($config['flag'] == 0)
	{
		$config['limit'] = 'nolimit';
	}
	if (isset($_REQUEST['gedcom_news_archive']))
	{
		$config['limit'] = 'nolimit';
		$config['flag'] = 0;
	}

	$uname = getUserName();
	$usernews = getUserNews($GEDCOM);

	print "<div id=\"gedcom_news\" class=\"block\">\n"
		.'<table class="blockheader" cellspacing="0" cellpadding="0" style="direction:ltr;"><tr>'
		.'<td class="blockh1">&nbsp;</td>'
		.'<td class="blockh2"><div class="blockhc">'
	;
	if(userGedcomAdmin(getUserName()))
	{
		print_help_link('index_gedcom_news_ahelp', 'qm_ah');
	}
	else
	{
		print_help_link('index_gedcom_news_help', 'qm');
	}
	if($PGV_BLOCKS['print_gedcom_news']['canconfig'])
	{
		$username = getUserName();
		if((($command == 'gedcom') && (userGedcomAdmin($username))) || (!empty($username)))
		{
			if($command == 'gedcom')
			{
				$name = preg_replace("/'/", "\'", $GEDCOM);
			}
			else
			{
				$name = $username;
			}
			print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name={$name}&amp;command={$command}&amp;action=configure&amp;side={$side}&amp;index={$index}', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">"
				."<img class=\"adminicon\" src=\"{$PGV_IMAGE_DIR}/{$PGV_IMAGES['admin']['small']}\" width=\"15\" height=\"15\" border=\"0\" alt=\"{$pgv_lang['config_block']}\" /></a>\n"
			;
		}
	}
	print "<b>{$pgv_lang['gedcom_news']}</b>"
		."</div></td>"
		."<td class=\"blockh3\">&nbsp;</td></tr>"
		."</table>"
		."<div class=\"blockcontent\">"
	;
	if($block)
	{
		print "<div class=\"small_inner_block, {$TEXT_DIRECTION}\">\n";
	}
	if(count($usernews) == 0)
	{
		print $pgv_lang['no_news'].'<br />';
	}
	$c = 0;
	$td = time();
	foreach($usernews as $key=>$news)
	{
		if ($config['limit'] == 'count')
		{
			if ($c >= $config['flag'])
			{
				break;
			}
			$c++;
		}
		if ($config['limit'] == 'date')
		{
			if (floor(($td - $news['date']) / 86400) > $config['flag'])
			{
				break;
			}
		}
		$day = date('j', $news['date']);
		$mon = date('M', $news['date']);
		$year = date('Y', $news['date']);
//		print "<div class=\"person_box\" id=\"{$news['anchor']}\">\n";
		print "<div class=\"news_box\" id=\"{$news['anchor']}\">\n";
		$ct = preg_match("/#(.+)#/", $news['title'], $match);
		if($ct > 0)
		{
			if(isset($pgv_lang[$match[1]]))
			{
				$news['title'] = preg_replace("/$match[0]/", $pgv_lang[$match[1]], $news['title']);
			}
		}
		print "<span class=\"news_title\">".PrintReady($news['title'])."</span><br />\n"
			."<span class=\"news_date\">".get_changed_date("{$day} {$mon} {$year}").' - '.date($TIME_FORMAT, $news['date'])."</span><br /><br />\n"
		;
		$ct = preg_match("/#(.+)#/", $news['text'], $match);
		if($ct > 0)
		{
			if(isset($pgv_lang[$match[1]]))
			{
				$news['text'] = preg_replace("/{$match[0]}/", $pgv_lang[$match[1]], $news['text']);
			}
		}
		$ct = preg_match("/#(.+)#/", $news['text'], $match);
		if($ct > 0)
		{
			if(isset($pgv_lang[$match[1]]))
			{
				$news['text'] = preg_replace("/{$match[0]}/", $pgv_lang[$match[1]], $news['text']);
			}
			$varname = $match[1];
			if(isset($$varname))
			{
				$news['text'] = preg_replace("/{$match[0]}/", $$varname, $news['text']);
			}
		}
		$trans = get_html_translation_table(HTML_SPECIALCHARS);
		$trans = array_flip($trans);
		$news['text'] = strtr($news['text'], $trans);
		$news['text'] = nl2br($news['text']);
		print PrintReady($news['text'])."<br />\n";
		if(userGedcomAdmin($uname))
		{
			print "<hr size=\"1\" />"
				."<a href=\"javascript:;\" onclick=\"editnews('{$key}'); return false;\">{$pgv_lang['edit']}</a> | "
				."<a href=\"index.php?action=deletenews&amp;news_id={$key}&amp;command={$command}\" onclick=\"return confirm('{$pgv_lang['confirm_news_delete']}');\">{$pgv_lang['delete']}</a><br />"
			;
		}
		print "</div>\n";
	}
	if($block)
	{
		print "</div>\n";
	}
	$printedAddLink = false;
	if(userGedcomAdmin($uname))
	{
		print "<a href=\"javascript:;\" onclick=\"addnews('".preg_replace("/'/", "\'", $GEDCOM)."'); return false;\">".$pgv_lang["add_news"]."</a>";
		$printedAddLink = true;
	}
	if ($config['limit'] == 'date' || $config['limit'] == 'count')
	{
		if ($printedAddLink) print "&nbsp;&nbsp;|&nbsp;&nbsp;";
		print_help_link("gedcom_news_archive_help", "qm");
		print "<a href=\"index.php?gedcom_news_archive=yes&amp;command={$command}\">".$pgv_lang['gedcom_news_archive']."</a><br />";
	}
	print "</div>\n</div>"
	;
}

function print_gedcom_news_config($config)
{
	global $pgv_lang, $PGV_BLOCKS;
	if (empty ($config))
	{
		$config = $PGV_BLOCKS['print_gedcom_news']['config'];
	}
	if (!isset ($config['limit']))
	{
		$config['limit'] = 'nolimit';
	}
	if (!isset ($config['flag']))
	{
		$config['flag'] = 0;
	}

	// Limit Type
	print '<tr><td class="descriptionbox width20">';
	print_help_link("gedcom_news_limit_help", "qm");
	print $pgv_lang['gedcom_news_limit'].'</td>';
	$output = '<td class="optionbox">'
		.'<select name="limit">'
		.'<option value="nolimit"'.($config['limit'] == 'nolimit'?' selected="selected"':'').">{$pgv_lang['gedcom_news_limit_nolimit']}</option>\n"
		.'<option value="date"'.($config['limit'] == 'date'?' selected="selected"':'').">{$pgv_lang['gedcom_news_limit_date']}</option>\n"
		.'<option value="count"'.($config['limit'] == 'count'?' selected="selected"':'').">{$pgv_lang['gedcom_news_limit_count']}</option>\n"
		.'</select></td></tr>'
	;
	print $output;

	// Flag to look for
	print '<tr><td class="descriptionbox width20">';
	print_help_link("gedcom_news_flag_help", "qm");
	print $pgv_lang['gedcom_news_flag'].'</td>';
	$output = '<td class="optionbox"><input type="text" name="flag" size="4" maxlength="4" value="' 
		.$config['flag']
		.'"></td></tr>'
	;
	print $output;
}
?>