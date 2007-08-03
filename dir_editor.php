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
 * @subpackage Edit
 * @version $Id: edit_interface.php 384 2006-08-30 16:51:24Z opus27 $
 */

require_once("config.php");
require_once("includes/functions_edit.php");

if (!userIsAdmin(getUserName())) {
	header("Location: login.php?url=dir_editor.php");
	exit;
}

Global $GEDCOMS;
require("languages/countries.en.php");
if (file_exists("languages/countries.".$lang_short_cut[$LANGUAGE].".php")) require("languages/countries.".$lang_short_cut[$LANGUAGE].".php");
asort($countries);

if (!userIsAdmin(getUserName())) {
		$loginURL = "$LOGIN_URL?url=".urlencode(basename($SCRIPT_NAME)."?".$QUERY_STRING);
		header("Location: $loginURL");
	exit;
}
if ($_SESSION["cookie_login"]) {
	header("Location: login.php?type=simple&ged=$GEDCOM&url=dir_editor.php".urlencode("?".$QUERY_STRING));
	exit;
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
		unlink($INDEX_DIRECTORY.$v);
	 	print "<span class=\"error\">".$v."</span><br/>";
	}
	
}

	require_once("js/prototype.js.htm");
	require_once("js/scriptaculous.js.htm");
	//print "<br /><b>".$pgv_lang["index_edit"]."</b>";
	//print_help_link("reorder_children_help", "qm");
	?>
		
		<form name="delete_form" method="post" action="">
		<ul id="reorder_list">
		<?php
			
					$dir = dir($INDEX_DIRECTORY);
					
					$path = $INDEX_DIRECTORY; // snag our path
					while (false !== ($entry = $dir->read())) {
		   				//echo $entry."\n";
						if($entry{0} != '.'){
							foreach($GEDCOMS as $key=>$val){
							 
								if($key == $entry)
								{
									
									print "<li class=\"facts_value\" alt=\"$entry\" style=\"margin-bottom:2px;\" id=\"lock_$entry\" >";
									print "<img src=\"./images/RESN_confidential.gif\" />";
									print "<span class=\"name2\">".$entry."</span>";
									print "&nbsp;&nbsp; Associated Files:<i>  ".str_replace($path,"",$val{"privacy"});
									print "  ".str_replace($path,"",$val{"config"})."</i>";
									
									// add to our locked array
									$locked_by_context[] = str_replace($path,"",$val{"privacy"});
									$locked_by_context[] = str_replace($path,"",$val{"config"});
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
							
							//print_pedigree_person($pid,2,false);

							print "</li>";
							}
						}
						
					}
					?></ul><?php
					$dir->close();
				
				print "<div style=\"margin-bottom:2px;\" id=\"trash\" >";
				//print_pedigree_person($pid,2,false);
				
				print "<table><tr><td><img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["trashcan"]["medium"]."\" align=\"left\"/></td>";
				print "<td valign=\"top\"><ul id=\"trashlist\">";
				print "</ul></td></tr></table>";
				print "</div>";
				
				//print_r($GEDCOMS);
				//print_r($element);
	
		?>
		
<script type="text/javascript" language="javascript">

	new Effect.BlindDown('reorder_list', {duration: 1});
	
		<?php  
		 foreach($element as $key=>$val)
		 {
		 	print "new Draggable('".$val."',{revert:true});";
		 }
		 ?>
		 
	 Droppables.add('trash', {
  onDrop: function(element) 
     { $('trashlist').innerHTML += 
        '<li class="facts_value">'+ element.innerHTML +'<input type="hidden" name="to_delete[]" value="'+element.innerHTML+'"/></li>' ; 
        element.style.display = "none";
        }});
function ul_clear()
{
	$('trashlist').innerHTML = ""; 
	
}	
</script>

		<button type="submit"><?php print $pgv_lang["save"];?></button>
		<button type="submit" onclick="ul_clear();"><?php print $pgv_lang["cancel"];?></button>
	<?php print_help_link("dir_editor_help","qm", '', false, false); ?>
	</form>
	
	
<?php print_footer(); ?>