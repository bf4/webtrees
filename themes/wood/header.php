<?php
/**
 * Header for Wood theme
 *
 * PhpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  John Finlay and others.  All rights reserved.
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
<table class="<?php print $TEXT_DIRECTION; ?>" border="0" width="95%">
	<tr>
		<td class="header_empty"><br /><!-- empty cell behind menu -->
		</td>
		<td>
			<div class="title" style="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>">
				<?php print_gedcom_title_link(TRUE); ?>
			</div>
			<br />
			<a href="<?php print $HOME_SITE_URL; ?>" class="link"><?php print $HOME_SITE_TEXT; ?></a><br />
		</td>
		<?php if(empty($SEARCH_SPIDER)) { ?>
		<td valign="middle">
			<?php print_theme_dropdown(); ?>
		</td>
		<td style="white-space: normal;" align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" valign="middle" >
			<form action="search.php" method="get">
				<input type="hidden" name="action" value="general" />
				<input type="hidden" name="topsearch" value="yes" />
				<input type="text" name="query" accesskey="<?php print $pgv_lang["accesskey_search"]?>" size="15" value="<?php print $pgv_lang['search']?>" onfocus="if (this.value == '<?php print $pgv_lang['search']?>') this.value=''; focusHandler();" onblur="if (this.value == '') this.value='<?php print $pgv_lang['search']?>';" />
				<input type="submit" name="search" value=" &gt; " style="{font-size: 8pt; }" />
			</form>
			<?php print_favorite_selector(0); ?>
		</td>
		<?php } ?>
	</tr>
</table>
</div>
<div id="header2">
<table cellpadding="3">
	<tr>
		<td valign="top">
