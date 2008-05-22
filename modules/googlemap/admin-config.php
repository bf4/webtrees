<?php
/**
 * Googlemap configuration User Interface.
 *
 * Provides links for administrators to get to other administrative areas of the site
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team
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
 * $Id$
 */

loadLangFile("gm_lang, gm_help");

?>
<script language="JavaScript" type="text/javascript">
<!--
	var helpWin;
	function helpPopup(which) {
		if ((!helpWin)||(helpWin.closed)) helpWin = window.open('module.php?mod=googlemap&pgvaction=editconfig_help&help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');
		else helpWin.location = 'modules/googlemap/editconfig_help.php?help='+which;
		return false;
	}
	function getHelp(which) {
		if ((helpWin)&&(!helpWin.closed)) helpWin.location='module.php?mod=googlemap&pgvaction=editconfig_help&help='+which;
	}

	function closeHelp() {
		if (helpWin) helpWin.close();
	}
	//-->
</script>
<?php

if (PGV_USER_IS_ADMIN) { ?>
   <tr>
	  <td colspan="2" class="topbottombar" style="text-align:center; "><?php print $pgv_lang["configure_googlemap"]; ?></td>
   </tr>
   <tr>
      <td class="optionbox"><?php print_help_link("GOOGLEMAP_CONFIG_help", "qm", "GOOGLEMAP_CONFIG");?><a href="module.php?mod=googlemap&pgvaction=editconfig"><?php print $pgv_lang["gm_manage"];?></a>
	  </td>
      <td class="optionbox"><?php print_help_link("PLE_EDIT_help", "qm", "PLE_EDIT");?><a href="module.php?mod=googlemap&pgvaction=places"><?php print $pgv_lang["edit_place_locations"];?></a>
	  </td>
   </tr>
   <tr>
      <td class="optionbox"><?php print_help_link("GOOGLEMAP_PLACECHECK_help", "qm", "GOOGLEMAP_PLACECHECK");?><a href="module.php?mod=googlemap&pgvaction=placecheck"><?php print $pgv_lang["placecheck"];?></a>
	  </td>
      <td class="optionbox">&nbsp;
	  </td>
   </tr>
<?php }
?>
