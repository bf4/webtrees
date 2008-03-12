<?php
/**
 * Login Block
 *
 * This block prints a form that will allow a user to login
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  John Finlay and Others
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

	if (PGV_USER_ID) {
		return;
	}
	$id="login_block";
	$title = "";
	if ($USE_REGISTRATION_MODULE) $title .= print_help_link("index_login_register_help", "qm", "", false, true);
	else $title .= print_help_link("index_login_help", "qm", "", false, true);
	$title .= $pgv_lang["login"];
	$content = "<div class=\"center\"><form method=\"post\" action=\"$LOGIN_URL\" name=\"loginform\" onsubmit=\"t = new Date(); document.loginform.usertime.value=t.getFullYear()+'-'+(t.getMonth()+1)+'-'+t.getDate()+' '+t.getHours()+':'+t.getMinutes()+':'+t.getSeconds(); return true;\">";
	$content .= "<input type=\"hidden\" name=\"url\" value=\"index.php?ctype=$ctype&amp;\" />";
	$content .= "<input type=\"hidden\" name=\"ged\" value=\"";
	if (isset($GEDCOM)) $content .= $GEDCOM; 
	$content .= "\" />";
	$content .= "<input type=\"hidden\" name=\"pid\" value=\"";
	if (isset($pid)) $content .= $pid; 
	$content .= "\" />";
	$content .= "<input type=\"hidden\" name=\"usertime\" value=\"\" />";
	$content .= "<input type=\"hidden\" name=\"action\" value=\"login\" />";
	$content .= "<table>";
	$content .= "<tr><td ";
	$content .= write_align_with_textdir_check("right", true);
	$content .= ">".$pgv_lang["username"]."</td><td><input type=\"text\" name=\"username\" size=\"15\" class=\"formField\" /></td></tr>";
	$content .= "<tr><td ";
	$content .= write_align_with_textdir_check("right", true);
	$content .= ">".$pgv_lang["password"]."</td><td><input type=\"password\" name=\"password\" size=\"15\" class=\"formField\" /></td></tr>";
	if ($ALLOW_REMEMBER_ME) {
		$content .= "<tr><td>".$pgv_lang["remember_me"]."</td><td><input type=\"checkbox\" name=\"remember\" value=\"yes\" class=\"formField\" ";
		if (!empty($_COOKIE["pgv_rem"])) $content .= "checked=\"checked\" ";
		$content .= "/>";
		$content .= print_help_link("remember_me_help", "qm", "", false, true);
		$content .= "</td></tr>";
		}
	$content .= "<tr><td colspan=\"2\" class=\"center\">";
	$content .= "<input type=\"submit\" value=\"".$pgv_lang["login"]."\" />&nbsp;";
	$content .= "</td></tr>";
	$content .= "</table>";
	$content .= "</form></div>";
	$content .= "<div class=\"center\">";
	if ($USE_REGISTRATION_MODULE) {
		$content .= $pgv_lang["no_account_yet"];
		$content .= "<br />";
		$content .= "<a href=\"login_register.php?action=register\">";
		$content .= $pgv_lang["requestaccount"];
		$content .= "</a>";
		$content .= print_help_link("new_user_help", "qm", "", false, true);

		$content .= "<br /><br />";
		$content .= $pgv_lang["lost_password"];
		$content .= "<br />";
		$content .= "<a href=\"login_register.php?action=pwlost\">";
		$content .= $pgv_lang["requestpassword"];
		$content .= "</a>";
		$content .= print_help_link("new_password_help", "qm", "", false, true);
	}
	$content .= "</div><br />";

	print '<div id="'.$id.'" class="block"><table class="blockheader" cellspacing="0" cellpadding="0"><tr>';
	print '<td class="blockh1">&nbsp;</td>';
	print '<td class="blockh2 blockhc"><b>'.$title.'</b></td>';
	print '<td class="blockh3">&nbsp;</td>';
	print '</tr></table><div class="blockcontent">';
	if ($block) {
		print '<div class="small_inner_block">'.$content.'</div>';
	} else {
		print $content;
	}
	print '</div></div>';
}
?>
