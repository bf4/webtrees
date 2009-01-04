<?php
/**
 * Google map module for phpGedView
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @subpackage Module
 * $Id$
 * @author windmillway
 */
	if (file_exists("modules/googlemap/defaultconfig.php")) {
//		print "<div id=\"googlemap\" class=\"tab_page\" style=\"display:none;\" >\n";
		print "<span class=\"subheaders\">".$pgv_lang["googlemap"]."</span>\n";

		include_once('modules/googlemap/googlemap.php');

		if ($GOOGLEMAP_ENABLED == "false") {
			print "<table class=\"facts_table\">\n";
			print "<tr><td id=\"no_tab8\" colspan=\"2\" class=\"facts_value\">".$pgv_lang["gm_disabled"]."</td></tr>\n";
			if (PGV_USER_IS_ADMIN) {
				print "<tr><td align=\"center\" colspan=\"2\">\n";
				print "<a href=\"module.php?mod=googlemap&amp;pgvaction=editconfig\">".$pgv_lang["gm_manage"]."</a>";
				print "</td>";
				print "</tr>\n";
			}
			print "\n\t</table>\n<br />";
			?>
			<script language="JavaScript" type="text/javascript">
			<!--
				function ResizeMap () {}
				function SetMarkersAndBounds () {}
			//-->
			</script>
			<?php
		}else{
			if(empty($SEARCH_SPIDER)) {
				$tNew = preg_replace("/&HIDE_GOOGLEMAP=true/", "", $_SERVER["REQUEST_URI"]);
				$tNew = preg_replace("/&HIDE_GOOGLEMAP=false/", "", $tNew);
				$tNew = preg_replace("/&/", "&amp;", $tNew);
				if($SESSION_HIDE_GOOGLEMAP == "true") {
					print "&nbsp;&nbsp;&nbsp;<span class=\"font9\"><a href=\"".$tNew."&amp;HIDE_GOOGLEMAP=false\">";
					print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["plus"]["other"]."\" border=\"0\" width=\"11\" height=\"11\" alt=\"".$pgv_lang["activate"]."\" title=\"".$pgv_lang["activate"]."\" />";
					print " ".$pgv_lang["activate"]."</a></span>\n";
					} else {
						print "&nbsp;&nbsp;&nbsp;<span class=\"font9\"><a href=\"" .$tNew."&amp;HIDE_GOOGLEMAP=true\">";
						print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["minus"]["other"]."\" border=\"0\" width=\"11\" height=\"11\" alt=\"".$pgv_lang["deactivate"]."\" title=\"".$pgv_lang["deactivate"]."\" />";
						print " ".$pgv_lang["deactivate"]."</a></span>\n";
					}
			}
			
			if (!$controller->indi->canDisplayName()) {
				print "\n\t<table class=\"facts_table\">";
				print "<tr><td class=\"facts_value\">";
				print_privacy_error($CONTACT_EMAIL);
				print "</td></tr>";
				print "\n\t</table>\n<br />";
				print "<script type=\"text/javascript\">\n";
				print "function ResizeMap ()\n{\n}\n</script>\n";
			}else{
				if(empty($SEARCH_SPIDER)) {
					if($SESSION_HIDE_GOOGLEMAP == "false") {
						include_once('modules/googlemap/googlemap.php');
						print "<table width=\100%\" border=\"0\" class=\"facts_table\">\n";
						print "<tr><td valign=\"top\">\n";
						print "<div id=\"googlemap_left\">\n";
						print "<img src=\"images/hline.gif\" width=\"".$GOOGLEMAP_XSIZE."\" height=\"0\" alt=\"\" /><br/>";
						print "<div id=\"map_pane\" style=\"border: 1px solid gray; color:black; width: 100%; height: ".$GOOGLEMAP_YSIZE."px\"></div>\n";
						if (PGV_USER_IS_ADMIN) {
							print "<table width=\"100%\"><tr>\n";
							print "<td width=\"33%\" align=\"left\">\n";
							print "<a href=\"module.php?mod=googlemap&amp;pgvaction=editconfig\">".$pgv_lang["gm_manage"]."</a>";
							print "</td>\n";
							print "<td width=\"33%\" align=\"center\">\n";
							print "<a href=\"module.php?mod=googlemap&amp;pgvaction=places\">".$pgv_lang["edit_place_locations"]."</a>";
							print "</td>\n";
							print "<td width=\"33%\" align=\"right\">\n";
							print "<a href=\"module.php?mod=googlemap&amp;pgvaction=placecheck\">".$pgv_lang["placecheck"]."</a>";
							print "</td>\n";
							print "</tr></table>\n";
						}
						print "</div>\n";
						print "</td>\n";
						print "<td valign=\"top\" width=\"28%\">\n";
							print "<div id=\"googlemap_content\">\n";
								setup_map();
								if ($controller->default_tab==7) {
//									$controller->getTab(7);
								}
								else{
									loading_message();
								}
							print "</div>\n";
						print "</td>";
						
						// Dummy <td> for Navigator =============================================================
							// Show or Hide Navigator -----------
							if (isset($_COOKIE['famnav'])) {
								$Fam_Navigator=$_COOKIE['famnav'];
							}else{
								$Fam_Navigator="NO";
							}
							if ($_COOKIE['famnav'] == "YES") {
								print "<td width=\"220px\" align=\"center\" valign=\"top\">";
									//
								print "</td>";
							}
						// =====================================================================================

						print "</tr></table>\n";

					}
				}
			}
		}
		// start
		print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["spacer"]["other"]."\" id=\"marker6\" width=\"1\" height=\"1\" alt=\"\" />";
		// end
//		print "</div>\n";
		
	}
?>