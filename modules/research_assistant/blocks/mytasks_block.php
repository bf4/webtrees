<?php
/**
 * My Tasks Block
 *
 * This block will print a users tasks
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @version $Id: mytasks_block.php 69 2006-05-24 20:40:08Z yalnifj $
 * @package PhpGedView
 * @subpackage Blocks
 */

$pgv_lang["mytasks_block"] = "MyTasks Block";

$PGV_BLOCKS["print_mytasks"]["name"]        = $pgv_lang["mytasks_block"];
$PGV_BLOCKS["print_mytasks"]["descr"]        = "mytasks_block_descr";
$PGV_BLOCKS["print_mytasks"]["canconfig"]        = true;
$PGV_BLOCKS["print_mytasks"]['config']		= array("unassigned" => "no", 
"completed" => "no");

//-- print user messages
function print_mytasks($block=true, $config="", $side, $index) {
		global $pgv_lang, $PGV_IMAGE_DIR, $TEXT_DIRECTION, $TIME_FORMAT, $PGV_STORE_MESSAGES, $PGV_IMAGES, $usersortfields;
		global $TBLPREFIX, $PGV_BLOCKS, $command, $GEDCOM;
		
		if (empty($config)) $config = $PGV_BLOCKS["print_mytasks"]["config"];
  		if (isset($config["unassigned"])) $unassigned = $config["unassigned"];  // "yes" or "no"
  		else $filter = "no";
  		if (isset($config["completed"])) $completed = $config["completed"];  // "yes" or "no"
  		else $completed = "no";
  		
		$userName = getUserName();
		
		//USERS CURRENT TASKS
		$sql =	"Select * From " .$TBLPREFIX. "tasks where t_username ='".$userName."' AND t_enddate IS NULL";
		$res = dbquery($sql);
		$out = "<table><tr><th class='descriptionbox'>Task Name</th><th class='descriptionbox'>Start Date</th><th class='descriptionbox'>Edit</th></tr>";
		while ($task = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$task = db_cleanup($task);
			$tasktitle = '<a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task['t_id'].'">'.$task['t_title'].'</a>';
			$out .= '<tr><td>'.$tasktitle.'</td><td>'.date("d M Y",$task["t_startdate"]);
			$out .= '</td><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=edittask&amp;taskid='.$task["t_id"].'" class="link">'.$pgv_lang["edit"].'</a>';
			$out .= '</td></tr>';
			}
		$out .= '</table>';
		$res->numRows();
		
		//USERS COMPLETED TASKS
		if($completed =="yes"){
		$sql = "Select * From " .$TBLPREFIX. "tasks where t_username ='".$userName."' and t_enddate is NOT NULL";
		$res = dbquery($sql);
		$out .= "<b>Completed Tasks</b><br/><table><tr><th class='descriptionbox'>Task Name</th><th class='descriptionbox'>Start Date</th><th class='descriptionbox'>Edit</th></tr>";
		while ($task = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$task = db_cleanup($task);
			$tasktitle = '<a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task['t_id'].'">'.$task['t_title'].'</a>';
			$out .= '<tr><td>'.$tasktitle.'</td><td>'.date("d M Y",$task["t_startdate"]);
			$out .= '</td><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=edittask&amp;taskid='.$task["t_id"].'" class="link">'.$pgv_lang["edit"].'</a>';
			$out .= '</td></tr>';
			}
		$out .= '</table>';
		$res->numRows();
		}
		
		//UNASSIGNED TASKS
		if($unassigned =="yes")
		{
			$sql = "Select * From " .$TBLPREFIX. "tasks where t_username =''";
			$res = dbquery($sql);
			$out .= "<b>Unassigned Tasks</b><br/><table><tr><th class='descriptionbox'>Task Name</th><th class='descriptionbox'>Start Date</th><th class='descriptionbox'>Edit</th><th class='descriptionbox'>TakeOn</th></tr>";
			while ($task = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
				$task = db_cleanup($task);
				$tasktitle = '<a href="module.php?mod=research_assistant&amp;action=viewtask&amp;taskid='.$task['t_id'].'">'.$task['t_title'].'</a>';
				$out .= '<tr><td>'.$tasktitle.'</td><td>'.date("d M Y",$task["t_startdate"]);
				$out .= '</td><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=edittask&amp;taskid='.$task["t_id"].'" class="link">'.$pgv_lang["edit"].'</a>';
				$out .= '</td><td class="optionbox"><a href="module.php?mod=research_assistant&amp;action=assignUser&amp;t_id='.$task["t_id"].'&amp;t_username='.$userName.'" class="link">TakeOn</a></td></tr>';
				}
			$out .= '</table>';
			$res->numRows();
		}
				
// Print heading
		if(getUserName())
		{
		print "<div id=\"mytasks_block\" class=\"block\">\n";
		print "<table class=\"blockheader\" cellspacing=\"0\" cellpadding=\"0\" style=\"direction:ltr;\"><tr>";
		print "<td class=\"blockh1\" >&nbsp;</td>";
		print "<td class=\"blockh2\" ><div class=\"blockhc\">";
		print_help_link("mygedview_message_help", "qm");
		
		if ($PGV_BLOCKS["print_mytasks"]["canconfig"]) {
    		$username = getUserName();
    		if ((($command=="gedcom")&&(userGedcomAdmin($username))) || (($command=="user")&&(!empty($username)))) {
     		 if ($command=="gedcom") $name = preg_replace("/'/", "\'", $GEDCOM);
     		 else $name = $username;
     		 print "<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?name=$name&amp;command=$command&amp;action=configure&amp;side=$side&amp;index=$index', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
      		print "<img class=\"adminicon\" src=\"$PGV_IMAGE_DIR/".$PGV_IMAGES["admin"]["small"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$pgv_lang["config_block"]."\" /></a>\n";
   			 }
		}
		
		print "<b>".$pgv_lang["my_tasks"]."&nbsp;&nbsp;</b>";
		if ($TEXT_DIRECTION=="rtl") print "&rlm;"; 
//Print Tasks	
		print "<td class=\"blockh3\"></td></tr></table>\n";
		
		print "<div class=\"blockcontent\">";
		print $out;
		print "</div></div>";
		
		}
}

function print_mytasks_config($config) {
	global $pgv_lang, $PGV_BLOCKS, $TEXT_DIRECTION;
	if (empty($config)) $config = $PGV_BLOCKS["print_mytasks"]["config"];
	if (!isset($config["unassigned"])) $config["unassigned"] = "no";
	if (!isset($config["completed"])) $config["completed"] = "no";

	print "<tr><td class=\"descriptionbox width20\"> Show unassigned tasks? </td>";?>
	<td class="optionbox">
   	<select name="unassigned">
    	<option value="no"<?php if ($config["unassigned"]=="no") print " selected=\"selected\"";?>><?php print $pgv_lang["no"]; ?></option>
    	<option value="yes"<?php if ($config["unassigned"]=="yes") print " selected=\"selected\"";?>><?php print $pgv_lang["yes"]; ?></option>
  	</select>
  	</td></tr>

  	<?php
  	print "<tr><td class=\"descriptionbox width20\"> Show completed tasks? </td>";?>
  	<td class="optionbox">
  	<select name="completed">
    	<option value="no"<?php if ($config["completed"]=="no") print " selected=\"selected\"";?>><?php print $pgv_lang["no"]; ?></option>
    	<option value="yes"<?php if ($config["completed"]=="yes") print " selected=\"selected\"";?>><?php print $pgv_lang["yes"]; ?></option>
  	</select>
  	</td></tr>
  <?php
}

?>
