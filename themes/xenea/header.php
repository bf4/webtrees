<?php
/**
 * Header for Xenea theme
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

global $SEARCH_SPIDER; $TEXT_DIRECTION;

$date=new GedcomDate(date('j M Y', client_time()));
$displayDate = $date->Display(false);

?>
<div id="header" class="<?php print $TEXT_DIRECTION; ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#003399">
   <tr>
      <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-image:url('<?php 
      		if ($TEXT_DIRECTION=="ltr") {
	      		print $PGV_IMAGE_DIR."/cabeza.jpg'); ";
	      		print "background-position:left top; ";
  			} else {
	  			print $PGV_IMAGE_DIR."/cabeza_rtl.jpg'); ";
	  			print "background-position:right top; ";
  			}
      		?>background-repeat:repeat-y; height:40px;">
              <tr>
                <td width="10"><img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" /></td>
                <td valign="middle"><font color="#FFFFFF" size="5" face="Verdana, Arial, Helvetica, sans-serif"><?php global $GEDCOMS, $GEDCOM; print PrintReady($GEDCOMS[$GEDCOM]["title"], TRUE); ?></font></td>
		<?php if(empty($SEARCH_SPIDER)) { ?>
                <td align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>">
               	<form action="search.php" method="get">
				<input type="hidden" name="action" value="general" />
				<input type="hidden" name="topsearch" value="yes" />
				<input type="text" name="query" accesskey="<?php print $pgv_lang["accesskey_search"]?>" size="12" value="<?php print $pgv_lang['search']?>" onfocus="if (this.value == '<?php print $pgv_lang['search']?>') this.value=''; focusHandler();" onblur="if (this.value == '') this.value='<?php print $pgv_lang['search']?>';" />
				<input type="submit" name="search" value="&gt;" />
				</form>
				</td>
				<td width="10"><img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" /></td>
		<?php } ?>
              </tr></table>
		<?php if(empty($SEARCH_SPIDER)) { ?>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#84beff" style="background-image:url('<?php print $PGV_IMAGE_DIR; ?>/barra.gif');">
              <tr>
                <td width="10"><img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="18" alt="" /></td>
                <td><div id="favtheme" align="<?php print $TEXT_DIRECTION=="rtl"?"right":"left" ?>" class="blanco"><?php print_theme_dropdown(1); ?></div><?php print_user_links(); ?></td>
				<td valign="top"></td>
                <td><div align="center"><?php print_lang_form(1); ?></div></td>
				<td><div id="favdate" align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" class="blanco"><?php print_favorite_selector(1); ?><?php print $displayDate; ?>


                </div></td><td width="10"><img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" /></td></tr></table>
		<?php } ?>
