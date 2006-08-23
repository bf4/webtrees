<?php
/**
 * phpGedView Research Assistant Tool - ra_EditTask
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
 * @package PhpGedView
 * @subpackage Research_Assistant
 * @version $Id: ra_EditTask.php 999 2006-06-13 22:35:43Z jfinlay $:
 * @author Jason Porter
 * @author Wade Lasson
 * @author Brandon Gagnon
 * @author Brian Kramer
 * @author Julian Gautier
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"ra_EditTask.php")) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
// Require the database functions
require_once("includes/functions_db.php");
require_once("includes/person_class.php");
include("modules/research_assistant/languages/ra_lang.en.php");
global $pgv_lang, $TBLPREFIX, $DBCONN;
 
 	/**
	 * GETS the TITLE of the task with the given taskid
	 * 
	 * @return mixed title of the task
	 */
function getTitle(){
    global $TBLPREFIX, $DBCONN;

    $sql = "SELECT t_title FROM " . $TBLPREFIX . "tasks WHERE t_id='".$DBCONN->escapeSimple($_REQUEST['taskid'])."'";
    $res = dbquery($sql);
    $out = "";

    while($title =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
        $out = db_cleanup($title["t_title"]);
    }

    return $out;
}
	
	/**
	 * GETS the DATES of the task with the given taskid
	 * 
	 * @return mixed dates of the task
	 */
	function getDates(){
        global $TBLPREFIX, $DBCONN;

		$sql = "SELECT t_startdate, t_enddate, t_results FROM " . $TBLPREFIX . "tasks WHERE t_id='".$DBCONN->escapeSimple($_REQUEST['taskid'])."'";
		$res = dbquery($sql);

        $s_date = "";	
        $e_date = ""; 
        $t_results = "";
        $out = "";

		while($dates =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$s_date = $dates["t_startdate"];
			$e_date = $dates["t_enddate"];
			$t_results = $dates["t_results"];
		}
		
		return array($s_date, $e_date, $t_results);
	}
	
	/**
	 * GETS a list of all available FOLDERS with the folder that the current task is in, on top
	 * 
	 * @return mixed list of available folders
	 */
	function getFolders() {
        global $TBLPREFIX;

        $sql = "select fr_name, fr_id from " . $TBLPREFIX . "folders";
		$res = dbquery($sql);
        
        $out = "";
        while($foldername =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
		    $out .= '<option value="'.$foldername['fr_id'].'">'.PrintReady($foldername['fr_name']) . '</option>';
		}
        
        return $out;
	}
	
	/**
	 * GETS all PEOPLE associated with the task given taskid
	 * 
	 * @return mixed people associated with the task
	 */
	function getPeople(){
        global $TBLPREFIX, $DBCONN;

		$sql = 	"SELECT it_i_id FROM " . $TBLPREFIX . "individualtask WHERE it_t_id='" . $DBCONN->escapeSimple($_REQUEST["taskid"]) . "'";
		$res = dbquery($sql);

		$people = array();
		while($row = $res->fetchRow()){
			if (!empty($row[0])) {
				$people[$row[0]] = Person::getInstance($row[0]);
			}
		}

		return $people;
	}
	
	/**
	 * GETS the DESCRIPTION of the task with the given taskid
	 * 
	 * @return mixed description of the task
	 */
	function getDescription(){
        global $TBLPREFIX, $DBCONN;

		$sql = "SELECT t_description FROM " . $TBLPREFIX . "tasks WHERE t_id='".$DBCONN->escapeSimple($_REQUEST['taskid'])."'";
		$res = dbquery($sql);

        $out = "";
		while($desc =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out = $desc["t_description"];
		}

		return $out;
	}
	
	/**
	 * GETS all SOURCES associated with the task given taskid
	 * 
	 * @return sources associated with the task
	 */
	function getSources(){
        global $TBLPREFIX, $DBCONN, $GEDCOMS, $GEDCOM;

		$sql = 	"SELECT s_name, s_id FROM " . $TBLPREFIX . "sources, ".$TBLPREFIX."tasksource WHERE s_file=".$GEDCOMS[$GEDCOM]['id']." AND ts_s_id=s_id AND ts_t_id='" . $DBCONN->escapeSimple($_REQUEST["taskid"]) . "'";
		$res = dbquery($sql);

		$sources = array();
		while($source =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$sources[$source["s_id"]] = $source["s_name"];
		}

		return $sources;
	}
	
	/**
	 * GETS all COMMENTS associated with the task
	 * 
	 * @return mixed comments associated with the task
	 */
	function getComments(){
		global $pgv_lang, $TBLPREFIX, $DBCONN;
		$sql = 	"SELECT c_u_username, c_body, c_datetime, c_id " .
				"FROM " . $TBLPREFIX . "comments " .
				"WHERE c_t_id='" . $DBCONN->escapeSimple($_REQUEST["taskid"]). "' " .
				"ORDER BY c_datetime DESC";
		$res = dbquery($sql);
		$out = "";

		while($comment = $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$comment = db_cleanup($comment);
			$out .= '<div class="blockcontent"><div class="person_box" id="comment1"><span class="news_title">' .
					$comment["c_u_username"]. 	// INSERT username
					'</span><br /><span class="news_date">' .
					get_changed_date(date("d M Y", (int)$comment["c_datetime"])).' - '. date("g:i:s A",(int)$comment["c_datetime"]).		// INSERT datetime
					'</span><br /><br />' .
					nl2br($comment["c_body"]).			// INSERT body
					'<hr size="1" />';
					
			if((userIsAdmin(getUserName())) || (getUserName() == $comment["c_u_username"])){
				$out .= '<a href="javascript:;" onclick="editcomment(' .
							''.$comment["c_id"].'' .	// INSERT commentid
							')">'.$pgv_lang["edit"].'</a> | <a href="" onclick="confirm_prompt(\''.$pgv_lang["comment_delete_check"].'\', ' .
							$comment["c_id"].'' .	// INSERT commentid
							'); return false;">'.$pgv_lang["delete"].'</a>';
			}
			$out .= '<br /></div></div><br/>';
		}
		return $out;
	}
	
	
	if(isset($_REQUEST['delete']) && !empty($_REQUEST['delete'])){
		// TODO: Verify user
		$sql = "DELETE FROM " . $TBLPREFIX . "comments WHERE c_id='".$DBCONN->escapeSimple($_REQUEST['delete'])."'";
		$res = dbquery($sql);
	}
?>

<!--JAVASCRIPT-->
<script language="JavaScript" type="text/javascript"><!--
	function showchanges() {
		window.location = 'module.php?mod=research_assistant&action=edittask&taskid=<?php print $_REQUEST['taskid']; ?>';
	}
  	function editcomment(commentid) {
  		window.open('editcomment.php?taskid=<?php print $_REQUEST['taskid']; ?>&commentid='+commentid, '', 'top=50,left=50,width=600,height=400,resizable=1,scrollbars=1');
  	}
  	function confirm_prompt(text, commentid) {
    	if (confirm(text)) {
      		window.location = 'module.php?mod=research_assistant&action=edittask&delete='+commentid+'&taskid=<?php print $_REQUEST['taskid']; ?>';
    	}
    }
    //-->
</script>
<!--BEGIN EDIT TASK FORM-->
<form action="module.php" method="post">
    <input type="hidden" name="mod" value="research_assistant" />
    <input type="hidden" name="action" value="updatetask" />
    <input type="hidden" name="taskid" value="<?php print $_REQUEST['taskid'] ?>" />
	<table class="list_table" align="center" border="0" width="40%">
  		<tr>
<!--HEADING-->
    		<th colspan="4" align="right" class="topbottombar">
    			<h2>
    				<?php print $pgv_lang["edit_task"]; print_help_link("ra_edit_task_help", "qm", '', false, false); ?>
    			</h2>
    		</th>
    	</tr>
    	<tr>
<!--TITLE-->
			<td class="descriptionbox">
      			<?php print $pgv_lang["title"]; ?>
      		</td>
      		<td class="optionbox">
      			<?php
      				// get title, given taskid
      				print '<input type="text" name="title" value="'.PrintReady(getTitle()).'" size="50"/>';
      			?>
      		</td>
<!--FOLDER-->
			<td class="descriptionbox">
      			<?php print $pgv_lang["folder"]; ?>
      		</td>
			<td class="optionbox">
 				<select name="folder">
					<?php
						// Get a list of all available folders
						print PrintReady(getFolders());
					?>
      			</select>
      		</td>
    	</tr>
	    <tr>
<!--DESCRIPTION-->
			<td class="descriptionbox">
      			<?php print $pgv_lang["description"]; ?>
      		</td>
	      	<td class="optionbox" colspan="3" >
	      		<?php
	      			// get description, given taskid
	      			print '<textarea name="desc" rows="3" cols="55">';
	      			print PrintReady(getDescription());
	      			print '</textarea>';
	      		?>
	      	</td>
	    </tr>    
	    <tr>
<!--SOURCES-->
			<td class="descriptionbox">
                    <?php print $pgv_lang["source"]; ?>
                </td>
                <td class="optionbox" colspan="3">
                <script language="JavaScript" type="text/javascript">
	<!--
	var pastefield;
	var nameElement;
	var lastId;
	function paste_id(value) {
		pastefield.value = pastefield.value + ";" + value;
		lastId = value;
	}
	function pastename(name) {
		nameElement.innerHTML = nameElement.innerHTML + '<a id="link_'+lastId+'" href="source.php?sid='+pastefield.value+'">'+name+'</a> <a id="rem_'+lastId+'" href="#" onclick="clearname(\''+pastefield.id+'\', \'link_'+lastId+'\', \''+lastId+'\'); return false;" ><img src="images/remove.gif" border="0" alt="" /><br /></a>\n';
	}
	function clearname(hiddenName, name, id) {
		pastefield = document.getElementById(hiddenName);
		if (pastefield) {
//			pastefield.value = pastefield.value.replace(new RegExp("\:"+id), '');
			pos1 = pastefield.value.indexOf(";"+id);
			if (pos1>-1) {
				pos2 = pastefield.value.indexOf(";", pos1+1);
				if (pos2==-1) pos2 = pastefield.value.length;
				pastefield.value = pastefield.value.substring(0, pos1)+pastefield.value.substring(pos2);
			}
		}
		nameElement = document.getElementById(name);
		if (nameElement) {
			nameElement.innerHTML = '';
		}
		nameElement = document.getElementById('rem_'+id);
		if (nameElement) {
			nameElement.innerHTML = '';
		}
	}
	//-->
	</script>
                   <div id="sourcelink">
                   		<?php
                   			$sources = getSources();
                   			$sval = '';
                   			foreach($sources as $sid=>$source) {
                   				$sval .= ';'.$sid;
                   				print '<a id="link_'.$sid.'" href="source.php?sid='.$sid.'">'.$source.'</a> <a id="rem_'.$sid.'" href="#" onclick="clearname(\'sourceid\', \'link_'.$sid.'\', \''.$sid.'\'); return false;" ><img src="images/remove.gif" border="0" alt="" /><br /></a>';
                   			}
                   		?>
                   </div>
                   <input type="hidden" id="sourceid" name="sourceid" size="3" value="<?php print $sval; ?>" />
                     <?php print_findsource_link("sourceid", "sourcelink"); ?>
                    <br />
                </td>
            </tr>
            <tr>
<!--PEOPLE-->
			<td class="descriptionbox">
               <?php print $pgv_lang["people"]; ?>
            </td>
            <td id="peoplecell" class="optionbox" colspan="3">
                   <div id="peoplelink">
                   		<?php
                   			$people = getPeople();
                   			$pval = '';
                   			foreach($people as $pid=>$person) {
                   				if(is_object($person)){
                   				$pval .= ';'.$person->getXref();
                   				print '<a id="link_'.$pid.'" href="individual.php?pid='.$pid.'">'.$person->getName().'</a> <a id="rem_'.$pid.'" href="#" onclick="clearname(\'personid\', \'link_'.$pid.'\', \''.$pid.'\'); return false;" ><img src="images/remove.gif" border="0" alt="" /><br /></a>';
                   				}
                   			}
                   		?>
                   </div>
                   <input type="hidden" id="personid" name="personid" size="3" value="<?php print $pval; ?>" />
                     <?php print_findindi_link("personid", "peoplelink"); ?>
                    <br />
            </td>
        </tr>
    <?php
    $data = getDates();
    if (!empty($data[1])) {
    	?>
    	<tr>
			<td class="descriptionbox">
               <?php print $pgv_lang["result"]; ?>
            </td>
            <td class="optionbox" colspan="3">
            	<textarea name="results" cols="55" rows="5"><?php print $data[2]; ?></textarea> 
            </td>
        </tr>
    	<?php
    }
    ?>
	<tr class="topbottombar">
    		<td colspan="4">
    		<input type="submit" value="<?php print $pgv_lang["save"]; ?>" />
    		<input type="button" value="Complete" name="complete" onclick="window.location='module.php?mod=research_assistant&amp;action=completeTask&amp;taskid=<?php print $_REQUEST['taskid'] ?>'" />
    		</td>
   	</tr>
				
    <tr>
    <td colspan="4">
    <br />
    </td>
    </tr>
    <tr>
<!--HEADING-->
    		<td colspan="4" class="topbottombar">
    			<h3>
    				<?php print $pgv_lang["comments"]; ?>
    			</h3>
    		</td>
    	</tr>
    <tr>
    <td colspan="4">
<!--COMMENT SECTION-->
	<div id="gedcom_news" class="block">
		<table class="blockheader" cellspacing="0" cellpadding="0">
			<tr>
				<td class="blockh1" >&nbsp;</td>
				<td class="blockh2" >
					<div class="blockhc"> 
					</div>
				</td>
				<td class="blockh3">&nbsp;</td>
			</tr>
		</table>
        <?php print getComments(); ?>
        
	</div>
<!--END COMMENT SECTION-->
</td>
</tr>
<tr class="topbottombar">
    		<td colspan="4">
<input type="button" value="<?php print $pgv_lang["add_new_comment"]; ?>" name="Add New Comment" onclick="window.open('editcomment.php?taskid='+<?php print $_REQUEST['taskid']; ?>, '', 
        'top=50,left=50,width=600,height=400,resizable=1,scrollbars=1');">
        </td>
    	</tr>
	     </table>
    </form>

<!--END EDIT TASK FORM-->
