<?php
/**
 * PopUp Window to provide editing features.
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
 * @author Dparker
 * @package PhpGedView
 * @subpackage Admin
 * @version $Id$
 */

require_once("config.php");
require_once("includes/functions_edit.php");

if (!userIsAdmin(getUserName())) {
	header("Location: login.php?url=dir_editor.php");
	exit;
}

if ($_SESSION["cookie_login"]) {
	header("Location: login.php?type=simple&ged=$GEDCOM&url=dir_editor.php".urlencode("?".$QUERY_STRING));
	exit;
}

function full_rmdir( $dir )
{
	if ( !is_writable( $dir ) )
	{
		if ( !@chmod( $dir, 0777 ) )
		{
			return FALSE;
		}
	}
	 
	$d = dir( $dir );
	while ( FALSE !== ( $entry = $d->read() ) )
	{
		if ( $entry == '.' || $entry == '..' )
		{
			continue;
		}
		$entry = $dir . '/' . $entry;
		if ( is_dir( $entry ) )
		{
			if ( !full_rmdir( $entry ) )
			{
				return FALSE;
			}
			continue;
		}
		if ( !@unlink( $entry ) )
		{
			$d->close();
			return FALSE;
		}
	}
	 
	$d->close();
	 
	rmdir( $dir );
	 
	return TRUE;
}

// Vars
$ajaxdeleted = false;
$elements = Array();
$locked_by_context = array("readme.txt","index.php","gedcoms.php");

print_header("Index Directory Editor");
 
//post back
if(isset($_REQUEST["to_delete"]))
{
	print "<span class=\"error\">".$pgv_lang["deleted_files"]."</span><br/>";
	foreach($_REQUEST["to_delete"] as $k=>$v){
		if (is_dir($INDEX_DIRECTORY.$v)) full_rmdir($INDEX_DIRECTORY.$v);
		else unlink($INDEX_DIRECTORY.$v);
	 	print "<span class=\"error\">".$v."</span><br/>";
	}
	
}

require_once("js/prototype.js.htm");
require_once("js/scriptaculous.js.htm");
//print "<br /><b>".$pgv_lang["index_edit"]."</b>";
//print_help_link("reorder_children_help", "qm");
?>

<form name="delete_form" method="post" action="">
<table>
	<tr>
		<td>
		<ul id="reorder_list">
		<?php
			
		//-- lock the GEDCOM and settings files
		foreach($GEDCOMS as $key=>$val){
			$locked_by_context[] = str_replace($INDEX_DIRECTORY,"",$val{"privacy"});
			$locked_by_context[] = str_replace($INDEX_DIRECTORY,"",$val{"config"});
		}
					$dir = dir($INDEX_DIRECTORY);
					
					$path = $INDEX_DIRECTORY; // snag our path
					while (false !== ($entry = $dir->read())) {
		   				//echo $entry."\n";
						if($entry{0} != '.'){
				if(isset($GEDCOMS[$entry]))
								{
					$val = $GEDCOMS[$entry];
									print "<li class=\"facts_value\" alt=\"$entry\" style=\"margin-bottom:2px;\" id=\"lock_$entry\" >";
									print "<img src=\"./images/RESN_confidential.gif\" />";
									print "<span class=\"name2\">".$entry."</span>";
									print "&nbsp;&nbsp; Associated Files:<i>  ".str_replace($path,"",$val{"privacy"});
									print "  ".str_replace($path,"",$val{"config"})."</i>";
								}
								else if (in_array($entry, $locked_by_context)){
																		
									print "<li class=\"facts_value\" alt=\"$entry\" style=\"margin-bottom:2px;\" id=\"lock_$entry\" >";
									print "<img src=\"./images/RESN_confidential.gif\" />";
									print "<span class=\"name2\">".$entry."</span>";	
								}
								else{
									print "<li class=\"facts_value\" alt=\"$entry\" style=\"cursor:move;margin-bottom:2px;\" id=\"li_$entry\" >";
									print $entry;
									$element[] = "li_".$entry;
								}
							print "</li>";
							}
						}
		?>
		</ul>
		</td>
		<td valign="top" id="trash"><?php
					$dir->close();
				
		print "<div style=\"margin-bottom:2px;\">";
		print "<table><tr><td>";
		if (isset($PGV_IMAGES["trashcan"]["medium"])) print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["trashcan"]["medium"]."\" align=\"left\"/>";
		else print "TRASH";
		print "</td>";
				print "<td valign=\"top\"><ul id=\"trashlist\">";
				print "</ul></td></tr></table>";
				print "</div>";
				
		?> <script type="text/javascript" language="javascript">

	new Effect.BlindDown('reorder_list', {duration: 1});
	
		<?php  
		 foreach($element as $key=>$val)
		 {
		 	print "new Draggable('".$val."',{revert:true});";
		 }
		 ?>
		 
	 Droppables.add('trash', {
	 hoverclass: 'facts_valuered',
  onDrop: function(element) 
     { $('trashlist').innerHTML += 
        '<li class="facts_value">'+ element.innerHTML +'<input type="hidden" name="to_delete[]" value="'+element.innerHTML+'"/></li>' ; 
        element.style.display = "none";
       // element.className='facts_valuered';
        }});
function ul_clear()
{
	$('trashlist').innerHTML = ""; 
	
	list = document.getElementById('reorder_list');
	children = list.childNodes;
	for(i=0; i<children.length; i++) {
		node = children[i];
		if (node.tagName=='li' || node.tagName=='LI') {
			//node.className='facts_value';
			node.style.display='list-item';
		}
	}
}	
</script>

		<button type="submit"><?php print $pgv_lang["delete"];?></button>
		<button type="button" onclick="ul_clear(); return false;"><?php print $pgv_lang["cancel"];?></button>
		<?php print_help_link("dir_editor_help","qm", '', false, false); ?></td>
	</tr>
</table>
</form>
		<?php print_footer(); ?>