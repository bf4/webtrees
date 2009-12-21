<?php

/**
 *
 * Description of file:
 * This page is called when you have matches for a record.  It allows
 * you to select a single record or multiple records and create a link between your
 * current PGV person and the FamilySearch record.  If there are multiple
 * records selected from the FamilySearch database, it will merge those into a new
 * FamilySearch record and then create the link between the new record and the selected
 * PGV person.
 *
 * PHP FamilySearch API Client
 * Copyright (C) 2007  Neumont University
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * See LICENSE.txt for the full license.  If you did not receive a
 * copy of the license with this code, you may find a copy online
 * at http://www.opensource.org/licenses/lgpl-license.php
 *
 * @author Chris Hill
 */

//chdir('../../');
require_once('config.php');
require_once(PGV_ROOT.'modules/FamilySearch/RA_AutoMatch.php');
require_once(PGV_ROOT."includes/functions/functions_print_facts.php");

if (!PGV_USER_CAN_EDIT) {
	header('Location: index.php');
	exit;
}

$match = array();

if (isset($_POST['merge'])) $match = $_POST['merge'];
$pid = safe_POST_xref("pid", '');
$action = safe_POST('action', PGV_REGEX_ALPHANUM, '');

$matcher = new RA_AutoMatch();
//-- make sure logged into NFS
if (!$matcher->isLoggedIn()) {
	header("Location: individual.php?pid=".$pid);
	exit;
}

global $nonfacts;
$nonfacts = array("FAMS","FAMC","OBJE");
$localPerson = Person::getInstance($pid, true, true);
$currentID = $matcher->getFSID($localPerson);
if ($currentID) {
	//-- make sure we always combine with the currently linked record
	if (!in_array($currentID, $match)) $match[] = $currentID;
}
$FSID = "";

if (count($match)==0) {
	header("Location: individual.php?pid=".$pid."&tab=FamilySearch&error=No+Records+Selected");
	exit;
}

print_header('FamilySearch Merge');

if (count($match)>1) {
	$FSID = $matcher->combine($match);
	if (!$FSID) {
		echo "There was a problem trying to combine the records:<br />";
		echo $matcher->getXMLGed()->error->message;
		print_footer();
		exit;
	}
}
else {
	$FSID = $match[0];
}

$remotePerson = $matcher->getPGVPerson($FSID, false, true);
$localfacts = $localPerson->getFacts($nonfacts);
sort_facts($localfacts);
$remotefacts = $remotePerson->getFacts($nonfacts);
sort_facts($remotefacts);

if ($action=='save' && empty($_REQUEST['copyall'])) {
	require_once("includes/functions/functions_edit.php");
	$newlocal = trim($localPerson->getGedcomRecord());
	$newremote = trim($remotePerson->getGedcomRecord());
	$changedlocal = false;
	$changedremote = false;
	$copytoremote = array();
	$deleteremote = array();
	foreach($localfacts as $lfact) {
		$id = $localPerson->getXref()."=".$lfact->getLineNumber();
		if (!empty($_POST['copy='.$id])) {
			$newremote.="\r\n".$lfact->getGedcomRecord();
			$changedremote = true;
			$copytoremote[] = $lfact;
		}
		if (!empty($_POST['del='.$id])) {
			$newlocal = preg_replace("~".$lfact->getGedcomRecord()."~m", "", $newlocal);
			$changedlocal = true;
		} 
	}
	foreach($remotefacts as $rfact) {
		$id = $remotePerson->getXref()."=".$rfact->getLineNumber();
		if (!empty($_POST['copy='.$id])) {
			$newlocal.="\r\n".$rfact->getGedcomRecord();
			$changedlocal = true;
		}
		if (!empty($_POST['del='.$id])) {
			$newremote = preg_replace("~".$rfact->getGedcomRecord()."~m", "", $newremote);
			$changedremote = true;
			$deleteremote[] = $rfact;
		} 
	}
	//print "<pre>".$newlocal."</pre>";
	//print "<pre>".$newremote."</pre>";
	$remotePerson = new Person($newremote);
	//print count($deleteremote)." ".count($copytoremote);
	
	$newXG = $matcher->updatePerson($FSID, $deleteremote, $copytoremote);
	//print $newXG;
	if (!empty($newXG)) {
		$oldfsid = $matcher->getFSID($localPerson);
		if (!empty($oldfsid)) {
			$newlocal = preg_replace("/".$FSID."/", $newXG, $newlocal);
		}
		else {
			$newlocal .= "\r\n1 REFN ".$newXG."\r\n2 TYPE FamilySearch";
				
			//-- add the RFN linkage
			if ($FS_CONFIG['family_search_remotelink']) {
				$serverID = $matcher->getServerId();
				if (!empty($serverID)) {
					$newlocal .= "\r\n1 RFN ".$serverID.":".$newXG;
				}
			}
		}
		$changedlocal = true;
	}
	if ($changedlocal) replace_gedrec($pid, $newlocal);
	if (!empty($newXG)) {
		?>
	<p><?php print $localPerson->getFullName();	?>
	was successfully updated.  This person's ID on the remote site is <?php print $newXG; ?><br /></p>
	<p>
	<!-- <a href="module.php?mod=FamilySearch&amp;pgvaction=FS_Relatives&pid=<?php echo $pid;?>&fsid=<?php echo $newXG ?>">Continue to Relatives</a> -->
	</p>
	<?php } else { ?>
		<p class="error">There was an error adding this person to FamilySearch.</p>
		<p class="error"><?php echo $matcher->getXMLGed()->error->message?></p>
	<?php } ?>
	<p>
	<a href="individual.php?pid=<?php echo $pid;?>">Go back to individual details for <?php echo $localPerson->getFullName()?></a>
	</p>
	<?php 
} // ------- end save action
else {

$copied = array();
if ($FS_CONFIG['family_search_copyall'] || !empty($_REQUEST['copyall'])) {
	foreach($localfacts as $lfact) {
		$found = false;
		foreach($remotefacts as $rfact) {
			if ($rfact->getTag()==$lfact->getTag()) {
				if (($rfact->getTag()!="EVEN" && $rfact->getTag()!="FACT") || trim($rfact->getType())==trim($lfact->getType())) {
					$found = true;
					break;
				}
			}
		}
		if (!$found) {
			$copied[] = $lfact;
			$remotefacts[] = $lfact;
			$lfact->copied = true;
		}
	}
	foreach($remotefacts as $rfact) {
		$found = false;
		foreach($localfacts as $lfact) {
			if ($rfact->getTag()==$lfact->getTag()) {
				if (($rfact->getTag()!="EVEN" && $rfact->getTag()!="FACT") || trim($rfact->getType())==trim($lfact->getType())) {
					$found = true;
					break;
				}
			}
		}
		if (!$found) {
			$copied[]=$rfact;
			$localfacts[] = $rfact;
			$rfact->copied = true;
		}
	}
}

?>
<script type="text/javascript">
//<![CDATA[
	jQuery('document').ready(function(){
		jQuery('#localfacts tr, #remotefacts tr').hover(
			function() {
				var tag = "."+jQuery(this).attr("title")+" td";
				jQuery(tag).css("border-width", "3px");
			}, 
			function(){
				var tag = "."+jQuery(this).attr("title")+" td";
				jQuery(tag).css("border", "");
			});
	});

	function copyLocal(id, tag) {
		var copyInput = document.getElementById('copy='+id);
		var link = document.getElementById('link='+id);
		if (copyInput.value=='Y') {
			copyInput.value = '';
			link.innerHTML = "Copy --&gt;";
			var copiedRow = document.getElementById('copied='+id);
			copiedRow.parentNode.removeChild(copiedRow);
		}
		else {
			copyInput.value = 'Y';
			link.innerHTML = "Don't Copy";
			var tr = document.getElementById(id);
			var newhtml = '<tr title="'+tag+'" class="'+tag+'" id="copied='+id+'"><td class="optionbox"><a href="#" onclick="return copyLocal(\''+id+'\', \''+tag+'\');">Don\'t Copy</a></td>';
			newhtml+=tr.innerHTML.substring(0,tr.innerHTML.lastIndexOf('<td')-1)+'</tr>';			
			jQuery('#remotefacts').append(newhtml);
		}
		return false;
	}

	function copyRemote(id, tag) {
		var copyInput = document.getElementById('copy='+id);
		var link = document.getElementById('link='+id);
		if (copyInput.value=='Y') {
			copyInput.value = '';
			link.innerHTML = "&lt;-- Copy";
			var copiedRow = document.getElementById('copied='+id);
			copiedRow.parentNode.removeChild(copiedRow);
		}
		else {
			copyInput.value = 'Y';
			link.innerHTML = "Don't Copy";
			var tr = document.getElementById(id);
			var newhtml = '<tr title="'+tag+'" class="'+tag+'" id="copied='+id+'">';
			newhtml+=tr.innerHTML.substring(tr.innerHTML.indexOf('</td>')+5);
			newhtml +='<td class="optionbox"><a href="#" onclick="return copyRemote(\''+id+'\', \''+tag+'\');">Don\'t Copy</a></td></tr>';			
			jQuery('#localfacts').append(newhtml);
		}
		return false;
	}

	function deleteLocal(id) {
		var delInput = document.getElementById('del='+id);
		if (delInput.value=='Y') {
			delInput.value='';
			var elRow = document.getElementById(id);
			elRow.style.opacity='1';
			document.getElementById('dellink='+id).innerHTML = "Delete";
			document.getElementById('link='+id).style.display = 'inline';
			document.getElementById('editlink='+id).style.display = 'inline';
		}
		else {
			delInput.value='Y';
			var elRow = document.getElementById(id);
			elRow.style.opacity='0.5';
			document.getElementById('dellink='+id).innerHTML = "Don't Delete";
			document.getElementById('link='+id).style.display = 'none';
			document.getElementById('editlink='+id).style.display = 'none';
		}
		return false;
	}

	function deleteRemote(id) {
		var delInput = document.getElementById('del='+id);
		if (delInput.value=='Y') {
			delInput.value='';
			var elRow = document.getElementById(id);
			elRow.style.opacity='1';
			document.getElementById('dellink='+id).innerHTML = "Delete";
			document.getElementById('link='+id).style.display = 'inline';
		}
		else {
			delInput.value='Y';
			var elRow = document.getElementById(id);
			elRow.style.opacity='0.5';
			document.getElementById('dellink='+id).innerHTML = "Don't Delete";
			document.getElementById('link='+id).style.display = 'none';
		}
		return false;
	}
	
//]]>
  </script>
<style type="text/css">
<!--
.deleted {
	opacity: 0.5;
}
//
-->
</style>
<form method="post" action="module.php">
	<input type="hidden" name="mod"	value="FamilySearch" /> 
	<input type="hidden" name="pgvaction" value="FS_MergePerson" /> 
	<input type="hidden" name="pid"	value="<?php echo $pid ?>" /> 
	<input type="hidden" name="merge[]"	value="<?php echo $FSID ?>" />
	<input type="hidden" name="action" value="save" />
<table class="facts_table">
	<tr>
		<!-- local -->
		<td class="topbottombar width50">Local Assertions</td>
		<!-- remote -->
		<td class="topbottombar width50">Remote Assertions</td>
	</tr>
	<tr>
		<!-- local -->
		<td valign="top"><span class="name_head"><?php echo $localPerson->getFullName(); ?></span>
		<table id="localfacts" class="connectedSortable">
		<?php
		foreach($localfacts as $fact) {
			$tag = $fact->getTag();
			if (!in_array($tag, $nonfacts)) {
				$id = $fact->getParentObject()->getXref()."=".$fact->getLineNumber();
				$rowid = $id;
				if ($fact->getParentObject()!=$localPerson) $rowid = 'copied='.$id;
				if ($tag=="EVEN" || $tag=="FACT") $tag=trim($fact->getType());
				echo "<tr title=\"{$tag}\" class=\"{$tag}\" id=\"{$rowid}\">";
				ob_start();
				print_fact($fact, true);
				$out = ob_get_contents();
				$out = preg_replace(array("~<tr[^>]*>~","~</tr>~"), array("",""), $out);
				ob_end_clean();
				echo $out;
				$copyText = "Copy --&gt;";
				$isCopied = '';
				if (isset($fact->copied)) {
					$copyText = "Don't Copy";
					$isCopied = 'Y';
				}
				?>
			<td class="facts_value nowrap">
			<?php if ($fact->getParentObject()==$localPerson) {?>
			<input type="hidden" id="copy=<?php echo $id?>" name="copy=<?php echo $id?>" value="<?php echo $isCopied?>" /> 
			<input type="hidden" id="del=<?php echo $id?>" name="del=<?php echo $id?>" value="" /> 
			<a href="#" id="link=<?php echo $id?>"
				onclick="return copyLocal('<?php echo $id;?>', '<?php echo $tag?>');"><?php echo $copyText?></a><br />
			<a href="#" id="editlink=<?php echo $id?>"
				onclick="return edit_record('<?php echo $pid ?>', <?php echo $fact->getLineNumber(); ?>);">Edit</a><br />
			<a href="#" id="dellink=<?php echo $id?>" onclick="return deleteLocal('<?php echo $id?>');">Delete</a>
			<?php } else { ?> 
			<a href="#" onclick="return copyRemote('<?php echo $id;?>', '<?php echo $tag?>');">Don't Copy</a> 
			<?php }?>
			</td>
			<?php
			echo "</tr>";
			}
		}
		?>
		</table>
		</td>
		<!-- remote -->
		<td valign="top"><span class="name_head"><?php echo $remotePerson->getFullName(); ?></span>
		<table id="remotefacts" class="connectedSortable">
		<?php
		foreach($remotefacts as $fact) {
			$tag = $fact->getTag();
			if (!in_array($tag, $nonfacts)) {
				$id = $fact->getParentObject()->getXref()."=".$fact->getLineNumber();
				$rowid = $id;
				if ($fact->getParentObject()!=$remotePerson) $rowid = 'copied='.$id;
				if ($tag=="EVEN" || $tag=="FACT") $tag=trim($fact->getType());
				echo "<tr title=\"{$tag}\" class=\"{$tag}\" id=\"{$rowid}\">";
				$copyText = "&lt;-- Copy";
				$isCopied = '';
				if (isset($fact->copied)) {
					$copyText = "Don't Copy";
					$isCopied = 'Y';
				}
				?>
			<td class="facts_value nowrap">
			<?php if ($fact->getParentObject()==$remotePerson) {?>
				<input type="hidden" id="copy=<?php echo $id?>"	name="copy=<?php echo $id?>" value="<?php echo $isCopied?>" /> 
				<input type="hidden" id="del=<?php echo $id?>" name="del=<?php echo $id?>" value="" /> 
				<a href="#" id="link=<?php echo $id?>" onclick="return copyRemote('<?php echo $id;?>', '<?php echo $tag?>');"><?php echo $copyText?></a><br />
				<a href="#" id="dellink=<?php echo $id?>" onclick="return deleteRemote('<?php echo $id?>');">Delete</a>
			<?php } else { ?> 
				<a href="#"	onclick="return copyLocal('<?php echo $id;?>', '<?php echo $tag?>');">Don't	Copy</a> <?php }?></td>
			<?php
			ob_start();
			print_fact($fact, true);
			$out = ob_get_contents();
			$out = preg_replace(array("~<tr[^>]*>~","~</tr>~"), array("",""), $out);
			ob_end_clean();
			echo $out;
			echo "</tr>";
			}
		}
		?>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="topbottombar">
			<input type="button" name="cancel" value="Cancel" /> 
			<?php if (!$FS_CONFIG['family_search_copyall']) {?>
			<input type="submit" name="copyall"	value="Copy all non-conflicting facts" /> 
			<?php }?> 
			<input type="submit" name="save" value="Save Changes" />
		</td>
	</tr>
</table>
</form>
<?php
} //-- end default action
print_footer(); // Displays the bottom footer for the page
?>
