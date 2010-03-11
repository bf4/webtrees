<?php
/**
 * Lightbox configuration User Interface.
 *
 * Provides links for administrators to get to other administrative areas of the site
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2008  PGV Development Team. All rights reserved.
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
 * This Page Is Valid XHTML 1.0 Transitional! > 01 September 2005
 *
 * @package webtrees
 * @subpackage Admin
 * $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

if (PGV_USER_IS_ADMIN) { ?>
   <tr>
	  <td colspan="2" class="topbottombar" style="text-align:center; "><?php echo i18n::translate('Lightbox-Album Configuration'); ?></td>
   </tr>
   <tr>
      <td class="optionbox">
		<?php print_help_link("LIGHTBOX_CONFIG", "qm", "LIGHTBOX_CONFIG");?><a href="module.php?mod=lightbox&pgvaction=lb_editconfig"><?php echo i18n::translate('Manage Lightbox configuration');?>
		</a>
	  </td>
      <td class="optionbox">
		
	  </td>
   </tr>

<?php }
?>
