<?php
/**
 * Googlemap configuration User Interface.
 *
 * Provides links for administrators to get to other administrative areas of the site
 *
 * phpGedView: Genealogy Viewer
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
 * This Page Is Valid XHTML 1.0 Transitional! > 01 September 2005
 *
 * @package PhpGedView
 * @subpackage Admin
 * @version $Id$
 */

require("modules/sitemap/".$pgv_language["english"]);
require("modules/sitemap/".$helptextfile["english"]);
if (file_exists("modules/sitemap/".$pgv_language[$LANGUAGE])) require("modules/sitemap/".$pgv_language[$LANGUAGE]);
if (file_exists("modules/sitemap/".$helptextfile[$LANGUAGE])) require("modules/sitemap/".$helptextfile[$LANGUAGE]);

if (userIsAdmin(getUserName())) { ?>
   <tr>
	  <td colspan="2" class="topbottombar" style="text-align:center; "><?php print $pgv_lang["generate_sitemap"]; ?></td>
   </tr>
   <tr>
      <td class="optionbox"><a href="module.php?mod=sitemap"><?php print $pgv_lang["generate_sitemap"];?></a>
	  </td>
      <td class="optionbox">
	  </td>
   </tr>
<?php }

?>
