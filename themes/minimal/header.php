<?php
/**
 * Header for Minimal theme
 *
 * PhpGedView: Genealogy Viewer
 * Copyright (c) 2002 to 2008  John Finlay and others.  All rights reserved.
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
 * @subpackage Themes
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

global $SEARCH_SPIDER; 
?>
<div id="header" class="<?php print $TEXT_DIRECTION; ?>">
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table width="100%">
			<tr>
			<td valign="middle">
				<div class="title" style="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>">
					<?php print_gedcom_title_link(TRUE); ?>
				</div>
				<br />
				<a href="<?php print $HOME_SITE_URL; ?>"><?php print $HOME_SITE_TEXT; ?></a>
			</td>
			<td align="center" valign="middle">
				<?php print_user_links(); ?>
			</td>
			<?php if(empty($SEARCH_SPIDER)) { ?>
			<td align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" valign="middle" >
				<?php print_theme_dropdown(); ?>
				<?php print_lang_form(); ?>
			</td>
			<td style="white-space: normal;" align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" valign="middle" >
				<form action="search.php" method="get">
					<input type="hidden" name="action" value="general" />
					<input type="hidden" name="topsearch" value="yes" />
					<input type="text" name="query" accesskey="<?php print $pgv_lang["accesskey_search"]?>" size="15" value="<?php print $pgv_lang['search']?>" onfocus="if (this.value == '<?php print $pgv_lang['search']?>') this.value=''; focusHandler();" onblur="if (this.value == '') this.value='<?php print $pgv_lang['search']?>';" />
					<input type="submit" name="search" value=" &gt; " />
				</form>
				<?php print_favorite_selector(); ?>
			</td>
			<?php } ?>
			</tr>
			</table>
		</td>
	</tr>
</table>
