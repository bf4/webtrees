<?php
/**
 *  Add Remote Link Page
 *
 *  Allow a user the ability to add links to people from other servers and other gedcoms.
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
 * @package PhpGedView
 * @subpackage Charts
 * @version $Id$
 */

require_once("includes/controllers/remotelink_ctrl.php");
$controller = new RemoteLinkController();
$controller->init();

print_simple_header($pgv_lang["title_remote_link"]);

//-- only allow gedcom admins to create remote links
if ($controller->canAccess()) {
	//print "pid: $pid<br />";
	//print "gedrec: $gedrec<br />";
	print $pgv_lang["access_denied"];
	//-- display messages as to why the editing access was denied
	if (!userGedcomAdmin(getUserName())) print "<br />".$pgv_lang["user_cannot_edit"];
	if (!$ALLOW_EDIT_GEDCOM) print "<br />".$pgv_lang["gedcom_editing_disabled"];
	if (!$disp) {
		print "<br />".$pgv_lang["privacy_prevented_editing"];
		if (!empty($pid)) print "<br />".$pgv_lang["privacy_not_granted"]." pid $pid.";
		if (!empty($famid)) print "<br />".$pgv_lang["privacy_not_granted"]." famid $famid.";
	}
	print "<br /><br /><div class=\"center\"><a href=\"javascript: ".$pgv_lang["close_window"]."\" onclick=\"window.close();\">".$pgv_lang["close_window"]."</a></div>\n";
	print_simple_footer();
	exit;
}

$controller->runAction();
?>

<script language="JavaScript" type="text/javascript">
<!--
function sameServer(){
  alert('<?php print $pgv_lang["error_same"];?>');
}
function remoteServer(){
  alert('<?php print $pgv_lang["error_remote"];?>');
}
function swapComponents(btnPressed){
    var tdId = document.getElementById('tdId');
    var tdblah = document.getElementById('tdUrl');
    var tdblah2 = document.getElementById('tdUrlText');
    var tdGIDLBL = document.getElementById('tdGIDLBL');
    var tdGID = document.getElementById('tdGID');

    if(btnPressed=="remote"){
      tdblah.innerHTML = '<?php print preg_replace(array("/'/", "/[\r\n]+/"), array("\\'", " "), print_help_link("link_remote_site_help", "qm", "", false, true));?> <?php echo $pgv_lang["label_site"];?>';
      tdblah2.innerHTML =  '<?php echo $pgv_lang["lbl_server_list"]; ?><br /><select id="cbExistingServers" name="cbExistingServers" style="width: 400px;"><?php if(isset($server_list)){foreach($server_list as $key=>$server){?><option value="<?php echo $key; ?>"><?php print $server['name'];?></option><?php }}?></select><br /><br />-or-<br /><br /><?php echo $pgv_lang["lbl_type_server"];?><br /><?php echo $pgv_lang["label_site_url"];?><input type="text" id="txtURL" name="txtURL" size="66"><br /><?php echo $pgv_lang["label_gedcom_id2"];?><input type="text" id="txtGID" name="txtGID" size="14"/><br /><?php echo $pgv_lang["label_username_id2"];?><input type="text" id="txtUsername" name="txtUsername" size="20"/><br /><?php echo $pgv_lang["label_password_id2"];?>&nbsp;<input type="password" id="txtPassword" name="txtPassword" size="20"/>';
      tdId.innerHTML = '<?php print preg_replace(array("/'/", "/[\r\n]+/"), array("\\'", " "), print_help_link("link_person_id_help", "qm", "", false, true));?> <?php echo $pgv_lang['label_remote_id'];?>';
	}
	else if(btnPressed=="FamilySearch"){
	    tdblah.innerHTML = '<?php print preg_replace(array("/'/", "/[\r\n]+/"), array("\\'", " "), print_help_link("link_family_search_help", "qm", "", false, true));?> <?php echo "Family Search";?>';
		tdblah2.innerHTML =  '<?php echo $pgv_lang["label_site_url"];?><input type="text" id="txtFS_Url" name="txtFS_URL" size="40"/><br /><?php echo $pgv_lang["label_username_id2"];?><input type="text" id="txtFS_Username" name="txtFS_Username" size="20"/><br /><?php echo $pgv_lang["label_password_id2"];?>&nbsp;<input type="password" id="txtFS_Password" name="txtFS_Password" size="20"/>';
        tdId.innerHTML = '<?php print preg_replace(array("/'/", "/[\r\n]+/"), array("\\'", " "), print_help_link("link_person_id_help", "qm", "", false, true));?> <?php echo $pgv_lang['label_remote_id'];?>';
	}

	else{
        tdblah.innerHTML = '<?php print preg_replace(array("/'/", "/[\r\n]+/"), array("\\'", " "), print_help_link("link_gedcom_id_help", "qm", "", false, true));?> <?php echo $pgv_lang['label_gedcom_id'];?>';
        tdId.innerHTML = '<?php print preg_replace(array("/'/", "/[\r\n]+/"), array("\\'", " "), print_help_link("link_person_id_help", "qm", "", false, true));?> <?php echo $pgv_lang['label_local_id'];?>';
        tdblah2.innerHTML = '<select id="cbGedcomId" name="cbGedcomId" style="width: 200px;"><?php foreach($GEDCOMS as $ged){?><option><?php print $ged["gedcom"];?></option><?php }?></select><br />';

    }
}

function edit_close() {
	if (window.opener.showchanges) window.opener.showchanges();
	window.close();
}

function checkform(frm){
	if (frm.txtPID.value=='') {
		alert('Please enter all fields.');
		return false;
	}
	return true;
}
//-->
</script>
<?php if ($action!="addlink") { ?>
<form method="post" name="addRemoteRelationship"
	action="addremotelink.php" onsubmit="return checkform(this);">
	<input type="hidden" name="action" value="addlink" /> 
	<input type="hidden" name="pid" value="<?php print $pid;?>"/> 
	<?php echo $name;?> <br />
<br />
<table class="facts_table">
	<tr>
		<td class="title" colspan="2"><?php print_help_link("link_remote_help", "qm"); ?> <?php echo $pgv_lang["title_remote_link"];?></td>
	</tr>
	<tr>
		<td class="descriptionbox width20"><?php print_help_link('link_remote_rel_help', 'qm');?> <?php echo $pgv_lang["label_rel_to_current"];?></td>
		<td class="optionbox"><select id="cbRelationship"
			name="cbRelationship">
			<!--  for now only allow creation of same person links... other links are confusing and cause problems 
			<option value="mother" selected><?php echo $pgv_lang["mother"];?></option>
			<option value="father"><?php echo $pgv_lang["father"];?></option>
			<option value="husband"><?php echo $pgv_lang["husband"];?></option>
			<option value="wife"><?php echo $pgv_lang["wife"];?></option>
			<option value="son"><?php echo $pgv_lang["son"];?></option>
			<option value="daughter"><?php echo $pgv_lang["daughter"];?></option>
			-->
			<option value="self"><?php echo $pgv_lang["current_person"];?></option>
		</select></td>
	</tr>
	<tr>
		<td class="descriptionbox width20"><?php print_help_link('link_remote_location_help', 'qm');?> <?php echo $pgv_lang["label_location"];?></td>
		<td class="optionbox"><input type="radio" id="local" name="location" value="local" onclick="swapComponents('')" />
			<?php echo $pgv_lang["label_same_server"];?>&nbsp;&nbsp;&nbsp;
		<input type="radio" id="remote" name="location" value="remote" checked
			onclick="swapComponents('remote')" /> <?php echo $pgv_lang["label_diff_server"];?>&nbsp;&nbsp;&nbsp;
		<?php if ($has_familysearch) { ?>
		<input type="radio" id="FamilySearch" name="location" value="FamilySearch" onclick="swapComponents('FamilySearch')" /> Family Search
		<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="descriptionbox width20" id="tdUrl"><?php print_help_link('link_remote_site_help', 'qm');?>
		<?php echo $pgv_lang["label_site"];?></td>
		<td class="optionbox" id="tdUrlText"><?php echo $pgv_lang["lbl_server_list"]; ?><br />
		<select id="cbExistingServers" name="cbExistingServers"
			style="width: 400px;">
			<?php
			if(isset($server_list)){
				foreach($server_list as $key=>$server){?>

			<option value="<?php echo $key; ?>"><?php print $server['name'];?></option>

			<?php
			}
}
?>
		</select> <br />
		<br />
		-or-<br />
		<br />
		<?php echo $pgv_lang["lbl_type_server"];?><br />
		<?php echo $pgv_lang["label_site_url"];?><input type="text"
			id="txtURL" name="txtURL" size="66"><br />
			<?php echo $pgv_lang["label_gedcom_id2"];?><input type="text"
			id="txtGID" name="txtGID" size="14" /><br />
			<?php echo $pgv_lang["label_username_id2"];?><input type="text"
			id="txtUsername" name="txtUsername" size="20" /><br />
			<?php echo $pgv_lang["label_password_id2"];?>&nbsp;<input
			type="password" id="txtPassword" name="txtPassword" size="20" /></td>
	</tr>
	<tr>
		<td class="descriptionbox width20" id="tdId"><?php print_help_link('link_person_id_help', 'qm');?>
		<?php echo $pgv_lang["label_remote_id"];?></td>
		<td class="optionbox"><input type="text" id="txtPID" name="txtPID"
			size="14" /></td>
	</tr>
</table>
<br />
<input type="submit" value="<?php echo $pgv_lang['label_add_remote_link'];?>"
id="btnSubmit" name="btnSubmit" value="add"/></form>
<?php
}
// autoclose window when update successful
if ($success && $EDIT_AUTOCLOSE) print "\n<script type=\"text/javascript\">\n<!--\nedit_close();\n//-->\n</script>";

print "<div class=\"center\"><a href=\"javascript:// ".$pgv_lang["close_window"]."\" onclick=\"edit_close();\">".$pgv_lang["close_window"]."</a></div><br />\n";

print_simple_footer();
?>
