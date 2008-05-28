<?php
/**
 * Header for SimplyRed theme
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

global $SEARCH_SPIDER; $TEXT_DIRECTION;

$date=new GedcomDate(date('j M Y', client_time()));
$displayDate = $date->Display(false);

?>
<div id="header" class="<?php print $TEXT_DIRECTION; ?>">
<div class="headerimg" style="margin: 3px; background: url(<?php print $PGV_IMAGE_DIR; ?>/bannerimage.jpg) no-repeat center <?php print $TEXT_DIRECTION=="ltr"?"left":"right" ?>;">
  <table width="99%" class="headerimg" cellpadding="3" cellspacing="0" style="height:186;">
    <tr>
      <td width="760">
        &nbsp;
      </td>
    <?php if(empty($SEARCH_SPIDER)) { ?>
      <td>
        <?php print_lang_form(1); ?>
        <?php print_theme_dropdown(); ?>
      </td>
    <?php } ?>
    </tr>
    <tr>
      <td valign="bottom">
        <div class="title">
          <?php print_gedcom_title_link(TRUE); ?>
        </div>
      </td>
    <?php if(empty($SEARCH_SPIDER)) { ?>
      <td style="white-space: nowrap">
        <form action="search.php" method="get">
          <input type="hidden" name="action" value="general" />
          <input type="hidden" name="topsearch" value="yes" />
          <input type="text" name="query" accesskey="<?php print $pgv_lang["accesskey_search"]?>" size="15" value="<?php print $pgv_lang['search']?>"
              onfocus="if (this.value == '<?php print $pgv_lang['search']?>') this.value=''; focusHandler();"
              onblur="if (this.value == '') this.value='<?php print $pgv_lang['search']?>';" />
          <input type="submit" name="search" value=">" />
        </form>
        <?php print_favorite_selector(); ?>
      </td>
    <?php } ?>
    </tr>
    <tr>
      <td>
        <?php print_user_links(); ?>
      </td>
      <td valign="bottom">
        <?php if(empty($SEARCH_SPIDER)) { ?>
        <div class="date">
          <?php print $displayDate; ?>
        </div>
        <?php } ?>
        <a href="<?php print $HOME_SITE_URL; ?>">
        <?php print $HOME_SITE_TEXT; ?>
        </a>
      </td>
    </tr>
  </table>
</div>
