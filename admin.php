<?php
/**
 * Administrative User Interface.
 *
 * Provides links for administrators to get to other administrative areas of the site
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PGV Development Team
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
 * @subpackage Admin
 * @version $Id$
 */

/**
 * load the main configuration and context
 */
require "config.php";
if (!userGedcomAdmin(getUserName())) {
	header("Location: login.php?url=admin.php");
	exit;
}

require  $confighelpfile["english"];
if (file_exists( $confighelpfile[$LANGUAGE])) require  $confighelpfile[$LANGUAGE];

if (!isset($action)) $action="";

print_header($pgv_lang["administration"]);

$d_pgv_changes = "";
if (count($pgv_changes) > 0) $d_pgv_changes = "<a href=\"javascript:;\" onclick=\"window.open('edit_changes.php','_blank','width=600,height=500,resizable=1,scrollbars=1'); return false;\">" . $pgv_lang["accept_changes"] . "</a>\n";

if (!isset($logfilename)) $logfilename = "";
$file_nr = 0;
$dir_var = opendir ($INDEX_DIRECTORY);
$dir_array = array();
while ($file = readdir ($dir_var))
{
  if ((strpos($file, ".log") > 0) && (strstr($file, "pgv-") !== false )){$dir_array[$file_nr] = $file; $file_nr++;}
}
closedir($dir_var);
$d_logfile_str = "&nbsp;";
if (count($dir_array)>0) {
  $d_logfile_str = "<form name=\"logform\" action=\"admin.php\" method=\"post\">";
  $d_logfile_str .= $pgv_lang["view_logs"] . ": ";
  $d_logfile_str .= "\n<select name=\"logfilename\">\n";
  $ct = count($dir_array);
  for($x = 0; $x < $file_nr; $x++)

  {
    $ct--;
    $d_logfile_str .= "<option value=\"";
    $d_logfile_str .= $dir_array[$ct];
    if ($dir_array[$ct] == $logfilename) $d_logfile_str .= "\" selected=\"selected";
    $d_logfile_str .= "\">";
    $d_logfile_str .= $dir_array[$ct];
    $d_logfile_str .= "</option>\n";
  }
  $d_logfile_str .= "</select>\n";
// $d_logfile_str .= "<input type=\"submit\" name=\"logfile\" value=\" &gt; \" />";
  $d_logfile_str .= "<input type=\"button\" name=\"logfile\" value=\" &gt; \" onclick=\"window.open('printlog.php?logfile='+document.logform.logfilename.options[document.logform.logfilename.selectedIndex].value, '_blank', 'top=50,left=10,width=600,height=500,scrollbars=1,resizable=1');\" />";
  $d_logfile_str .= "</form>";
}

$usermanual_filename = "docs/english/PGV-manual-en.html";
$d_LangName = "lang_name_" . "english";
$doc_lang = $pgv_lang[$d_LangName];
$new_usermanual_filename = "docs/" . $languages[$LANGUAGE] . "/PGV-manual-" . $language_settings[$LANGUAGE]["lang_short_cut"] . ".html";
if (file_exists($new_usermanual_filename)){$usermanual_filename = $new_usermanual_filename; $d_LangName = "lang_name_" . $languages[$LANGUAGE]; $doc_lang = $pgv_lang[$d_LangName];}

$d_img_module_str = "&nbsp;";
if (file_exists("img_editconfig.php")) $d_img_module_str = "<a href=\"img_editconfig.php?action=edit\">".$pgv_lang["img_admin_settings"]."</a><br />";

$err_write = file_is_writeable("config.php");

$users = getUsers();
$verify_msg = false;
$warn_msg = false;
foreach($users as $indexval => $user) {
	if (!$user["verified_by_admin"] && $user["verified"])  {
		$verify_msg = true;
	}
	if (!empty($user["comment_exp"])) {
		if ((strtotime($user["comment_exp"]) != "-1") && (strtotime($user["comment_exp"]) < time("U"))) $warn_msg = true;
	}
	if (($verify_msg) && ($warn_msg)) break;
}
// [START] New code for dhtmlXTabbar
require_once("js/dhtmlXTabbar.js.htm");
?>
<h4>!!! This is an Admin TabBar demo. Scroll down for previous version !!!</h4>
<div id="admin_tabbar" class="dhtmlxTabBar" imgpath="js/scbr.com/dhtmlXTabbar/imgs/" style="width:640px; height:480px;" zzskinColors="#FCFBFC,#F4F3EE" zztabstyle="winScarf" >
	<div id="admin_tab_info" name="<?php echo $pgv_lang["admin_info"]?>" class="indent" zzwidth="auto" >
		<fieldset>
			<legend> PhpGedView
			</legend>
			<ul>
				<li>
				<label> Version
				</label>
				<?php echo $VERSION . " " . $VERSION_RELEASE ?></li>
				<li>
				<?php print_help_link("changelog_help", "qm"); ?>
				<a href="changelog.php" target="manual" title="<?php print $pgv_lang["view_changelog"]; ?>">
					<?php print_text("changelog"); ?></a></li>
				<li>
				<?php print_help_link("readmefile_help", "qm"); ?>
				<a href="readme.txt" target="manual" title="<?php print $pgv_lang["view_readme"]; ?>">
					<?php print $pgv_lang["readme_documentation"];?></a></li>
				<li>
				<?php print_help_link("config_help_help", "qm"); ?>
				<a href="pgvinfo.php?action=confighelp">
					<?php print $pgv_lang["config_help"];?></a></li>
				<li>
				<?php print_help_link("registry_help", "qm"); ?>
				<a href="http://phpgedview.sourceforge.net/registry.php" target="_blank">
					<?php print $pgv_lang["pgv_registry"];?></a></li>
			</ul>
		</fieldset>
		<fieldset>
			<legend> Server
			</legend>
			<ul>
				<li>
				<img src="images/small/time_server.gif" alt="<?php echo $pgv_lang["system_time"]?>" title="<?php echo $pgv_lang["system_time"]?> " />
				<?php echo get_changed_date(date("j M Y"))." - ".date($TIME_FORMAT);?></li>
				<li>
				<?php print_help_link("phpinfo_help", "qm"); ?>
				<a href="pgvinfo.php?action=phpinfo" title="<?php print $pgv_lang["show_phpinfo"]; ?>">
					<?php print $pgv_lang["phpinfo"];?></a></li>
			</ul>
		</fieldset>
		<fieldset>
			<legend> Browser
			</legend>
			<ul>
				<li>
				<img src="images/small/time_desktop.gif" alt="<?php echo $pgv_lang["user_time"]?>" title="<?php echo $pgv_lang["user_time"]?> " />
				<?php echo get_changed_date(date("j M Y", time()-$_SESSION["timediff"]))." - ".date($TIME_FORMAT, time()-$_SESSION["timediff"]);?></li>
			</ul>
	</fieldset>
	</div>
	<?php if (userIsAdmin(getUserName())) {?>
	<div id="admin_tab_site" name="<?php echo $pgv_lang["admin_site"]?>">
		<fieldset>
			<legend> Users
			</legend>
			<ul>
				<li>
				<?php print_help_link("um_tool_help", "qm"); ?>
				<a href="usermigrate.php?proceed=migrate">
					<?php print $pgv_lang["um_header"];?></a></li>
				<li>
				<?php print_help_link("help_useradmin.php", "qm"); ?>
				<a href="useradmin.php">
					<?php print $pgv_lang["user_admin"];?></a></li>
				<li>
				<?php print_help_link("um_bu_help", "qm"); ?>
				<a href="usermigrate.php?proceed=backup">
					<?php print $pgv_lang["um_backup"];?></a></li>
			</ul>
		</fieldset>
		<fieldset>
			<legend> Languages
			</legend>
			<ul>
				<li>
				<?php print_help_link("help_changelanguage.php", "qm"); ?>
				<a href="changelanguage.php?action=editold">
					<?php print $pgv_lang["enable_disable_lang"];?></a></li>
				<?php
				if (!file_exists($INDEX_DIRECTORY . "lang_settings.php")) {
					print "<br /><span class=\"error\">";
					print $pgv_lang["LANGUAGE_DEFAULT"];
					print "</span>";
				}?>
				<li>
					<?php print_help_link("add_new_language_help", "qm"); ?>
					<a href="changelanguage.php?action=addnew">
						<?php print $pgv_lang["add_new_language"];?></a></li>
				<li>
					<?php print_help_link("help_editlang.php", "qm"); ?>
					<a href="editlang.php">
						<?php print $pgv_lang["translator_tools"];?></a></li>
			</ul>
		</fieldset>
		<fieldset>
			<legend> Servers
			</legend>
			<ul>
				<li>
					<a href="">Search engines</a></li>
				<li>
					<a href="">Black list</a></li>
				<li>
					<a href="">Remote phpGedView servers</a></li>
			</ul>
		</fieldset>
		<fieldset>
			<legend> Logs
			</legend>
			<?php print $d_logfile_str; ?>
		</fieldset>
	</div>
	<?php }?>
	<div id="admin_tab_ged" name="<?php echo $pgv_lang["admin_geds"]?>">
		<ul>
			<li>
			<?php print_help_link("edit_gedcoms_help", "qm"); ?>
			<a href="editgedcoms.php">
				<?php print $pgv_lang["manage_gedcoms"];?></a></li>
			<li>
			<?php print_help_link("help_edit_merge.php", "qm"); ?>
			<a href="edit_merge.php">
				<?php print $pgv_lang["merge_records"]; ?></a></li>
			<?php if (userCanEdit(getUserName())) { ?>
			<li>
			<?php print_help_link("edit_add_unlinked_person_help", "qm"); ?>
			<a href="javascript: <?php print $pgv_lang["add_unlinked_person"]; ?>" onclick="addnewchild(''); return false;">
				<?php print $pgv_lang["add_unlinked_person"]; ?></a></li>
			<li>
			<?php print_help_link("edit_add_unlinked_source_help", "qm"); ?>
			<a href="javascript: <?php print $pgv_lang["add_unlinked_source"]; ?>" onclick="addnewsource(''); return false;">
				<?php print $pgv_lang["add_unlinked_source"]; ?></a></li>
			<?php } ?>
			<li>
			<?php if ($d_pgv_changes != "") print $d_pgv_changes; else print "&nbsp;"; ?></li>
		</ul>
	</div>
	<div id="admin_tab_mod" name="Modules">
		<fieldset>
			<legend> Googlemap
			</legend>
			<a href="">Googlemap</a>
		</fieldset>
	</div>
</div>
<?php
// [END] New code for dhtmlXTabbar
?>
  <table class="center <?php print $TEXT_DIRECTION ?>">
    <tr>
      <td colspan="2" class="topbottombar">
      <?php
      	print "<h2>PhpGedView v" . $VERSION . " " . $VERSION_RELEASE . "<br />";
      	print $pgv_lang["administration"];
      	print "</h2>";
      	print $pgv_lang["system_time"];
      	print " ".get_changed_date(date("j M Y"))." - ".date($TIME_FORMAT);
      	print "<br />".$pgv_lang["user_time"];
      	print " ".get_changed_date(date("j M Y", time()-$_SESSION["timediff"]))." - ".date($TIME_FORMAT, time()-$_SESSION["timediff"]);
      	if (userIsAdmin(getUserName())) {
		  if ($err_write) {
			  print "<br /><span class=\"error\">";
			  print $pgv_lang["config_still_writable"];
			  print "</span><br /><br />";
		  }
		  if ($verify_msg) {
			  print "<br />";
			  print "<a href=\"useradmin.php?action=listusers&amp;filter=admunver\" class=\"error\">".$pgv_lang["admin_verification_waiting"]."</a>";
			  print "<br /><br />";
		  }
		  if ($warn_msg) {
			  print "<br />";
			  print "<a href=\"useradmin.php?action=listusers&amp;filter=warnings\" class=\"error\" >".$pgv_lang["admin_user_warnings"]."</a>";
			  print "<br /><br />";
		  }
	    }
	  ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="descriptionbox" style="text-align:center; "><?php print $pgv_lang["select_an_option"]; ?></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
	<tr>
	  <td colspan="2" class="topbottombar" style="text-align:center; "><?php print $pgv_lang["admin_info"]; ?></td>
	</tr>
	<tr>
	  <td class="optionbox"><?php print_help_link("readmefile_help", "qm"); ?><a href="readme.txt" target="manual" title="<?php print $pgv_lang["view_readme"]; ?>"><?php print $pgv_lang["readme_documentation"];?></a></td>
      <td class="optionbox"><?php print_help_link("phpinfo_help", "qm"); ?><a href="pgvinfo.php?action=phpinfo" title="<?php print $pgv_lang["show_phpinfo"]; ?>"><?php print $pgv_lang["phpinfo"];?></a></td>
	</tr>
	<tr>
      <td class="optionbox"><?php print_help_link("config_help_help", "qm"); ?><a href="pgvinfo.php?action=confighelp"><?php print $pgv_lang["config_help"];?></a></td>
	  <td class="optionbox"><?php print_help_link("changelog_help", "qm"); ?><a href="changelog.php" target="manual" title="<?php print $pgv_lang["view_changelog"]; ?>"><?php print_text("changelog"); ?></a></td>
	</tr>
	<tr>
      <td class="optionbox"><?php print_help_link("registry_help", "qm"); ?><a href="http://phpgedview.sourceforge.net/registry.php" target="_blank"><?php print $pgv_lang["pgv_registry"];?></a></td>
	  <td class="optionbox">&nbsp;</td>
	</tr>
	<tr>
	  <td colspan="2" class="topbottombar" style="text-align:center; "><?php print $pgv_lang["admin_geds"]; ?></td>
	</tr>
	<tr>
	  <td class="optionbox"><?php print_help_link("edit_gedcoms_help", "qm"); ?><a href="editgedcoms.php"><?php print $pgv_lang["manage_gedcoms"];?></a></td>
	  <td class="optionbox"><?php print_help_link("help_edit_merge.php", "qm"); ?><a href="edit_merge.php"><?php print $pgv_lang["merge_records"]; ?></a></td>
	</tr>
<?php if (userCanEdit(getUserName())) { ?>
	<tr>
     <td class="optionbox"><?php print_help_link("edit_add_unlinked_person_help", "qm"); ?><a href="javascript: <?php print $pgv_lang["add_unlinked_person"]; ?>" onclick="addnewchild(''); return false;"><?php print $pgv_lang["add_unlinked_person"]; ?></a></td>
     <td class="optionbox"><?php print_help_link("edit_add_unlinked_source_help", "qm"); ?><a href="javascript: <?php print $pgv_lang["add_unlinked_source"]; ?>" onclick="addnewsource(''); return false;"><?php print $pgv_lang["add_unlinked_source"]; ?></a></td>
	</tr>
<?php } ?>
   <tr>
      <td class="optionbox">&nbsp;</td>
      <td class="optionbox"><?php if ($d_pgv_changes != "") print $d_pgv_changes; else print "&nbsp;"; ?></td>
   </tr>
   <?php if (userIsAdmin(getUserName())) { ?>
   <tr>
	  <td colspan="2" class="topbottombar" style="text-align:center; "><?php print $pgv_lang["admin_site"]; ?></td>
   </tr>
   <tr>
      <td class="optionbox"><?php print_help_link("help_editconfig.php", "qm"); ?><a href="editconfig.php"><?php print $pgv_lang["configuration"];?></a></td>
      <td class="optionbox"><?php print_help_link("um_tool_help", "qm"); ?><a href="usermigrate.php?proceed=migrate"><?php print $pgv_lang["um_header"];?></a></td>
   </tr>
   <tr>
   	<td class="optionbox"><?php print_help_link("help_useradmin.php", "qm"); ?><a href="useradmin.php"><?php print $pgv_lang["user_admin"];?></a></td>
	<td class="optionbox"><?php print_help_link("um_bu_help", "qm"); ?><a href="usermigrate.php?proceed=backup"><?php print $pgv_lang["um_backup"];?></a></td>
   </tr>
   <tr>
   	<td class="optionbox"><?php print_help_link("help_faq.php", "qm"); ?><a href="faq.php"><?php print $pgv_lang["faq_list"];?></a></td>
	<td class="optionbox"><?php print_help_link("help_managesites", "qm"); ?><a href="manageservers.php"><?php print $pgv_lang["link_manage_servers"];?></a></td>
   </tr>
   <tr>
      <td class="optionbox"><?php print_help_link("help_changelanguage.php", "qm"); ?><a href="changelanguage.php?action=editold"><?php print $pgv_lang["enable_disable_lang"];?></a>
	     <?php
	     if (!file_exists($INDEX_DIRECTORY . "lang_settings.php")) {
	     	print "<br /><span class=\"error\">";
	     	print $pgv_lang["LANGUAGE_DEFAULT"];
	     	print "</span>";
         }
	     ?>
	  </td>
      <td class="optionbox"><?php print_help_link("add_new_language_help", "qm"); ?><a href="changelanguage.php?action=addnew"><?php print $pgv_lang["add_new_language"];?></a>
	  </td>
   </tr>
   <tr>
      <td class="optionbox"><?php print_help_link("help_editlang.php", "qm"); ?><a href="editlang.php"><?php print $pgv_lang["translator_tools"];?></a>
	  </td>
      <td class="optionbox"><?php print $d_logfile_str; ?></td>
   </tr>
<?php    }

if (file_exists("modules")) {
	$rep = opendir('./modules/');
	while ($file = readdir($rep)) {
	    if(($file <> ".") && ($file <> "..") && (is_dir('./modules/'.$file))) {
	        if (file_exists("modules/".$file."/admin-config.php")) {
	            require "modules/".$file."/admin-config.php";
	        }
	    }
	}
	closedir($rep);
}

?>
  </table>

<?php
  if (isset($logfilename) and ($logfilename != "")) {
    print "<hr><table align=\"center\" width=\"70%\"><tr><td class=\"listlog\">";
    print "<strong>";
    print $pgv_lang["logfile_content"];
    print " [" . $INDEX_DIRECTORY . $logfilename . "]</strong><br /><br />";
    $lines=file($INDEX_DIRECTORY . $logfilename);
    $num = sizeof($lines);
    for ($i = 0; $i < $num ; $i++) {
      print $lines[$i] . "<br />";
    }
    print "</td></tr></table><hr>";
  }
?>
<script language="javascript" type="text/javascript">
<!--
function manageservers(){
  window.open("manageservers.php", "", "top=50,left=50,width=700,height=500,scrollbars=1,resizable=1");
}
//-->
</script>
<br /><br />
<?php
print_footer();
?>
