<?php
/**
 * Login Block
 *
 * This block prints a form that will allow a user to login
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
 * $Id$
 * @package PhpGedView
 * @subpackage Blocks
 */

$PGV_BLOCKS["print_login_block"]["name"]		= $pgv_lang["login_block"];
$PGV_BLOCKS["print_login_block"]["descr"]		= "login_descr";
$PGV_BLOCKS["print_login_block"]["type"]		= "gedcom";
$PGV_BLOCKS["print_login_block"]["canconfig"]	= false;
$PGV_BLOCKS["print_login_block"]["config"]		= array("cache"=>-1);

/**
 * Print Login Block
 *
 * Prints a block allowing the user to login to the site directly from the portal
 */
function print_login_block($block = true, $config="", $side, $index) {
		global $pgv_lang, $GEDCOM, $GEDCOMS, $ctype, $SCRIPT_NAME, $QUERY_STRING, $USE_REGISTRATION_MODULE, $LOGIN_URL, $ALLOW_REMEMBER_ME;

		$uname = getUserName();
		if (!empty($uname)) return;
		print "<div id=\"login_block\" class=\"block\">\n";
		print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
		print "<td class=\"blockh1\" >&nbsp;</td>";
		print "<td class=\"blockh2\" ><div class=\"blockhc\">";
		if ($USE_REGISTRATION_MODULE) print_help_link("index_login_register_help", "qm");
		else print_help_link("index_login_help", "qm");
		print "<b>".$pgv_lang["login"]."</b>";
		print "</div></td>";
		print "<td class=\"blockh3\">&nbsp;</td></tr>\n";
		print "</table>";
		print "<div class=\"blockcontent\">";
		print "<div class=\"center\"><form method=\"post\" action=\"$LOGIN_URL\" name=\"loginform\" onsubmit=\"t = new Date(); document.loginform.usertime.value=t.getFullYear()+'-'+(t.getMonth()+1)+'-'+t.getDate()+' '+t.getHours()+':'+t.getMinutes()+':'+t.getSeconds(); return true;\">\n";
		print "<input type=\"hidden\" name=\"url\" value=\"index.php?ctype=$ctype&amp;\" />\n";
		print "<input type=\"hidden\" name=\"ged\" value=\"";if (isset($GEDCOM)) print $GEDCOM; print "\" />";
		print "<input type=\"hidden\" name=\"pid\" value=\"";if (isset($pid)) print $pid; print "\" />";
		print "<input type=\"hidden\" name=\"usertime\" value=\"\" />\n";
		print "<input type=\"hidden\" name=\"action\" value=\"login\" />\n";
		print "<table>";
		print "<tr><td ";
		write_align_with_textdir_check("right");
		print ">".$pgv_lang["username"]."</td><td><input type=\"text\" name=\"username\" size=\"15\" class=\"formField\" /></td></tr>\n";
		print "<tr><td ";
		write_align_with_textdir_check("right");
		print ">".$pgv_lang["password"]."</td><td><input type=\"password\" name=\"password\" size=\"15\" class=\"formField\" /></td></tr>\n";
		if ($ALLOW_REMEMBER_ME) {
			print "<tr><td>".$pgv_lang["remember_me"]."</td><td><input type=\"checkbox\" name=\"remember\" value=\"yes\" class=\"formField\" ";
			if (!empty($_COOKIE["pgv_rem"])) print "checked=\"checked\" ";
			print "/>";
			print_help_link("remember_me_help", "qm");
			print "</td></tr>\n";
		}
		print "<tr><td colspan=\"2\" class=\"center\">";
		print "<input type=\"submit\" value=\"".$pgv_lang["login"]."\" />&nbsp;";
		print "<input type=\"submit\" value=\"".$pgv_lang["admin"]."\" onclick=\"document.loginform.url.value='admin.php'\" />";
		print "</td></tr>\n";
		print "</table>\n";
		print "</form></div>\n";
		print "<div class=\"center\">";
		if ($USE_REGISTRATION_MODULE){
			print $pgv_lang["no_account_yet"];
			print "<br />";
			print "<a href=\"login_register.php?action=register\">";
			print $pgv_lang["requestaccount"];
			print "</a>";
			print_help_link("new_user_help", "qm");

			print "<br /><br />";
			print $pgv_lang["lost_password"];
			print "<br />";
			print "<a href=\"login_register.php?action=pwlost\">";
			print $pgv_lang["requestpassword"];
			print "</a>";
			print_help_link("new_password_help", "qm");
		}
		print "</div>";
		print "</div>";
		print "</div>";
}
?>