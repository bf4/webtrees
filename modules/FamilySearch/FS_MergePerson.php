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
require_once('modules/FamilySearch/RA_AutoMatch.php');
require_once("includes/functions/functions_print_facts.php");

if (!userGedcomAdmin(getUserName())) {
	header('Location: index.php');
	exit;
}

$match = array();
$people= array();

if (isset($_POST['merge'])) $match = $_POST['merge'];
if (isset($_POST['record'])) $recordNumber=$_POST['record'];
$pid=safe_POST_xref("pid", '');
$action = safe_POST('action', PGV_REGEX_ALPHANUM, '');

$matcher = new RA_AutoMatch();
//-- make sure logged into NFS
if (!$matcher->isLoggedIn()) {
	header("Location: individual.php?pid=".$pid);
	exit;
}

print_header('FamilySearch Merge');

global $nonfacts;
$nonfacts = array("FAMS","FAMC","OBJE");
$localPerson = Person::getInstance($pid);
$currentID = $matcher->getFSID($localPerson);
//-- make sure we always combine with the currently linked record
if ($currentID && !in_array($currentID, $match)) $match[] = $currentID;

$FSID = "";
if (count($match)>1) {
	$FSID = $matcher->combine($match);
	if ($FSID=="ERROR") {
		echo $matcher->getXMLGed()->error->message;
	}
}
else {
	$FSID = $match[0];
}

$remotePerson = $matcher->getPGVPerson($FSID, false);
$localfacts = $localPerson->getFacts($nonfacts);
sort_facts($localfacts);
$remotefacts = $remotePerson->getFacts($nonfacts);
sort_facts($remotefacts);

//print "<pre>".$remotePerson->getGedcomRecord()."</pre>";
if ($action=='save') {
	require_once("includes/functions/functions_edit.php");
	$newlocal = $localPerson->getGedcomRecord();
	$newremote = $remotePerson->getGedcomRecord();
	$changedlocal = false;
	$changedremote = false;
	$copytoremote = array();
	$deleteremote = array();
	foreach($localfacts as $lfact) {
		$id = $localPerson->getXref()."=".$lfact->getLineNumber();
		if (!empty($_POST['copy='.$id])) {
			$newremote.=$lfact->getGedcomRecord();
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
			$newlocal.=$rfact->getGedcomRecord();
			$changedlocal = true;
		}
		if (!empty($_POST['del='.$id])) {
			$newremote = preg_replace("~".$rfact->getGedcomRecord()."~m", "", $newremote);
			$changedremote = true;
			$deleteremote[] = $rfact;
		} 
	}
	//print "<pre>".$newlocal."</pre>";
	if ($changedlocal) replace_gedrec($pid, $newlocal);
	//print "<pre>".$newremote."</pre>";
	$remotePerson = new Person($newremote);
	print count($deleteremote)." ".count($copytoremote);
	
	$newXG = $matcher->updatePerson($FSID, $deleteremote, $copytoremote);
	print $newXG;
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
		}
	}
}

?>
<link type="text/css"
	href="<?php echo PGV_THEME_DIR?>jquery/jquery-ui-1.7.1.custom.css"
	rel="Stylesheet" />
<script
	type="text/javascript" src="js/jquery/jquery.min.js"></script>
<script
	type="text/javascript" src="js/jquery/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript">
//<![CDATA[
	$('document').ready(function(){
		$('#localfacts tr, #remotefacts tr').hover(
			function() {
				var tag = "."+$(this).attr("title")+" td";
				$(tag).css("border-width", "3px");
			}, 
			function(){
				var tag = "."+$(this).attr("title")+" td";
				$(tag).css("border", "");
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
			$('#remotefacts').append(newhtml);
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
			newhtml+=tr.innerHTML.substring(tr.innerHTML.indexOf('</td>')+5)
			newhtml +='<td class="optionbox"><a href="#" onclick="return copyRemote(\''+id+'\', \''+tag+'\');">Don\'t Copy</a></td></tr>';			
			$('#localfacts').append(newhtml);
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
				if (in_array($fact, $copied)) {
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
				if (!$fact->getValue("_FSID")) {
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
