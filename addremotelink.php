<?php
/**
 *  Add Remote Link Page
 *
 *  Allow a user the ability to add links to people from other servers and other gedcoms.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
if (!$controller->canAccess()) {
	//print "pid: $pid<br />";
	//print "gedrec: $gedrec<br />";
	print '<span class="error">';
	print $pgv_lang["access_denied"].'<br /';
	//-- display messages as to why the editing access was denied
	if (!PGV_USER_GEDCOM_ADMIN) {
		print $pgv_lang["user_cannot_edit"];
	} else if (!$ALLOW_EDIT_GEDCOM) {
		print $pgv_lang["gedcom_editing_disabled"];
	} else {
		print $pgv_lang["privacy_prevented_editing"];
		if (!empty($pid)) print "<br />".$pgv_lang["privacy_not_granted"]." pid $pid.";
		if (!empty($famid)) print "<br />".$pgv_lang["privacy_not_granted"]." famid $famid.";
	}
	print '</span>';
	print "<br /><br /><div class=\"center\"><a href=\"javascript: ".$pgv_lang["close_window"]."\" onclick=\"window.close();\">".$pgv_lang["close_window"]."</a></div>\n";
	print_simple_footer();
	exit;
}

$remoteServers = get_server_list();
$gedcomList = get_all_gedcoms();

$success = $controller->runAction();
?>

<script language="JavaScript" type="text/javascript">
<!--
function sameServer() {
  alert('<?php print $pgv_lang["error_same"];?>');
}

function remoteServer() {
  alert('<?php print $pgv_lang["error_remote"];?>');
}

function swapComponents(btnPressed) {
	var labelSite = document.getElementById('labelSite');
    var existingContent = document.getElementById('existingContent');
    var localContent = document.getElementById('localContent');
    var remoteContent = document.getElementById('remoteContent');

    if (btnPressed=="remote") {
	    labelSite.innerHTML = '<?php echo $pgv_lang["label_site"];?>';
		existingContent.style.display='none';
		localContent.style.display='none';
		remoteContent.style.display='block';
    } else if (btnPressed=="local") {
	    labelSite.innerHTML = '<?php echo $pgv_lang["label_gedcom_id"];?>';
		existingContent.style.display='none';
		localContent.style.display='block';
		remoteContent.style.display='none';
    } else {
	    labelSite.innerHTML = '<?php echo $pgv_lang["label_site"];?>';
		existingContent.style.display='block';
		localContent.style.display='none';
		remoteContent.style.display='none';
    }
}

function edit_close() {
	if (window.opener.showchanges) window.opener.showchanges();
	window.close();
}

function checkform(frm) {
	if (frm.txtPID.value=='') {
		alert('Please enter all fields.');
		return false;
	}
	return true;
}
//-->
</script>
<?php if ($action!="addlink") { 
	$success = false;
?>
<form method="post" name="addRemoteRelationship" action="addremotelink.php" onsubmit="return checkform(this);">
	<input type="hidden" name="action" value="addlink" />
	<input type="hidden" name="pid" value="<?php print $pid;?>" />
	<span class="title"><?php
		$record=GedcomRecord::getInstance($pid);
		print PrintReady($record->getFullName());
		print "&nbsp;&nbsp;";
 		print PrintReady("(".$pid.")");
	?></span> <br />
<br />
<table class="facts_table">
	<tr>
		<td class="title" colspan="2"><?php print_help_link("link_remote_help", "qm"); ?>
		<?php echo $pgv_lang["title_remote_link"];?></td>
	</tr>
	<tr>
		<td class="descriptionbox wrap width20"><?php print_help_link('link_remote_rel_help', 'qm');?>
		<?php echo $pgv_lang["label_rel_to_current"];?></td>
		<td class="optionbox"><select id="cbRelationship"
			name="cbRelationship">
			<option value="self" selected="selected"><?php echo $pgv_lang["current_person"];?></option>
			<!--  for now only allow creation of same person links... other links are confusing and cause problems 
			<option value="mother"><?php echo $pgv_lang["mother"];?></option>
			<option value="father"><?php echo $pgv_lang["father"];?></option>
			<option value="husband"><?php echo $pgv_lang["husband"];?></option>
			<option value="wife"><?php echo $pgv_lang["wife"];?></option>
			<option value="son"><?php echo $pgv_lang["son"];?></option>
			<option value="daughter"><?php echo $pgv_lang["daughter"];?></option>
			-->
		</select></td>
	</tr>
	<?php if (count($remoteServers)>0 || count($gedcomList)>1) { ?>
	<tr>
		<td class="descriptionbox wrap width20"><?php print_help_link('link_remote_location_help', 'qm');?>
		<?php echo $pgv_lang["label_location"];?></td>
		<td class="optionbox">
			<?php if (count($gedcomList)>0) { ?>
				<input type="radio" id="local" name="location" value="local" onclick="swapComponents('local')" /> 
				<?php echo $pgv_lang["label_same_server"];?>&nbsp;&nbsp;&nbsp;
			<?php 
			} if (count($remoteServers)>0) { ?>
				<input type="radio" id="existing" name="location" value="existing" onclick="swapComponents('existing');" /> 
				<?php echo $pgv_lang["lbl_server_list"];?>&nbsp;&nbsp;&nbsp;
			<?php } ?>
			<input type="radio" id="remote" name="location" value="remote" checked="checked" onclick="swapComponents('remote');" /> 
			<?php echo $pgv_lang["label_diff_server"];?>
		</td>
	</tr>
	<?php } ?>
	
	<tr>
		<td class="descriptionbox wrap width20">
		<?php print_help_link('link_person_id_help', 'qm');?>
		<?php echo $pgv_lang["label_local_id"];?>
		</td>
		<td class="optionbox"><input type="text" id="txtPID" name="txtPID" size="14" /></td>
	</tr>
	<tr>
		<td class="descriptionbox wrap width20"><?php print_help_link('link_remote_site_help', 'qm');?>
			<span id="labelSite"><?php echo $pgv_lang["label_site"];?></span>
		</td>
		<td class="optionbox" id="tdUrlText">
			<div id="existingContent" style="display:none;">
			<?php echo $pgv_lang["lbl_server_list"]; ?><br />
			<select id="cbExistingServers" name="cbExistingServers"
				style="width: 400px;">
				<?php
				foreach ($remoteServers as $key=>$server) {?>
					<option value="<?php echo $key; ?>"><?php print PrintReady($server['name']);?></option>
				<?php }
				?>
			</select> <br />
			<br />
			</div>
			<div id="remoteContent" style="display:block;">
			<?php echo $pgv_lang["lbl_type_server"];?>
			<table><tr>
					<td ><?php echo $pgv_lang["title"];?></td>
					<td><input type="text" id="txtTitle" name="txtTitle" size="66" /></td>
				</tr><tr>
					<td valign="top"><?php echo $pgv_lang["label_site_url"];?></td>
					<td><input type="text" id="txtURL" name="txtURL" size="66" />
      				<br /><?php echo $pgv_lang["example"];?>&nbsp;&nbsp;http://www.remotesite.com/phpGedView/genservice.php?wsdl</td>
				</tr><tr>
					<td><?php echo $pgv_lang["label_gedcom_id2"];?></td>
					<td><input type="text" id="txtGID" name="txtGID" /></td>
				</tr><tr>
					<td><?php echo $pgv_lang["label_username_id2"];?></td>
					<td><input type="text" id="txtUsername" name="txtUsername" /></td>
				</tr><tr>
					<td><?php echo $pgv_lang["label_password_id2"];?></td>
					<td><input type="password" id="txtPassword" name="txtPassword" /></td>
				</tr></table>
			</div>
			<div id="localContent" style="display:none;">
			<table><tr>
					<td ><?php echo $pgv_lang["title"];?></td>
					<td><input type="text" id="txtCB_Title" name="txtCB_Title" size="66" /></td>
				</tr><tr>
					<td valign="top"><?php echo $pgv_lang["gedcom_file"];?></td>
					<td><select id="txtCB_GID" name="txtCB_GID">
					<?php
						foreach ($gedcomList as $ged_name) {
							if ($ged_name != $GEDCOM) {
								echo '<option value="', $ged_name, '">', $ged_name, '</option>';
							}
						}
					?>
					</select></td>
				</tr></table>
			</div>
		</td>
	</tr>
</table>
<br />
<input type="submit"
	value="<?php echo $pgv_lang['label_add_remote_link'];?>" id="btnSubmit"
	name="btnSubmit" value="add" /></form>
		<?php
}
// autoclose window when update successful
if ($success && $EDIT_AUTOCLOSE) print "\n<script type=\"text/javascript\">\n<!--\nedit_close();\n//-->\n</script>";

print "<div class=\"center\"><a href=\"javascript:// ".$pgv_lang["close_window"]."\" onclick=\"edit_close();\">".$pgv_lang["close_window"]."</a></div><br />\n";

print_simple_footer();
?>
