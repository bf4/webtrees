<?php
/**
 * phpGedView Research Assistant Tool - ra_ViewTasks
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * @version $Id$:
 * @author Jason Porter
 * @author Wade Lasson
 * @author Brandon Gagnon
 * @author Brian Kramer
 * @author Julian Gautier
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"module.php")===false) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
// Require the database functions
require_once("includes/person_class.php");
global $pgv_lang;
 
 	/**
	 * GETS the TITLE of the task with the given taskid
	 * 
	 * @return mixed title of the task
	 */
function getTitle(){
    global $TBLPREFIX;

    $sql = "SELECT t_title FROM " . $TBLPREFIX . "tasks WHERE t_id='$_REQUEST[taskid]'";
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
        global $TBLPREFIX;

		$sql = "SELECT t_startdate, t_enddate FROM " . $TBLPREFIX . "tasks WHERE t_id='$_REQUEST[taskid]'";
		$res = dbquery($sql);

        $s_date = "";	
        $e_date = ""; 
        $out = "";

		while($dates =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
			$s_date = $dates["t_startdate"];
			$e_date = $dates["t_enddate"];
		}

        // Display either the starting and ending date or just the ending date
		if(empty($e_date)) 
			$out .= "opened: $s_date";
		else
			$out .= "$s_date - $e_date";

		return $out;
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
		    $out .= '<option value="'.$foldername['fr_id'].'">'.$foldername['fr_name'] . '</option>';
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

		$sql = 	"SELECT it_i_id FROM ".$TBLPREFIX."individualtask WHERE it_t_id='" . $DBCONN->escapeSimple($_REQUEST["taskid"])."'";
		$res = dbquery($sql);

		$out = "";
		while($people =& $res->fetchRow()){
			$person = Person::getInstance($people[0]);
			if (!is_null($person)) {
				$bdate=$person->getEstimatedBirthDate();
				$byear=$bdate->gregorianYear();
				$out .= '<a href="individual.php?pid='.$people[0].'">'.PrintReady($person->getName()." - ".$byear.'</a><br />';
			}
		}

		return $out;
	}
	
	/**
	 * GETS the DESCRIPTION of the task with the given taskid
	 * 
	 * @return mixed description of the task
	 */
	function getDescription(){
        global $TBLPREFIX;

		$sql = "SELECT t_description FROM " . $TBLPREFIX . "tasks WHERE t_id='" . $_REQUEST["taskid"] . "'";
		$res = dbquery($sql);

        $out = "";
		while($desc = $res->fetchRow()){
			$out = db_cleanup($desc[0]);
		}

		return $out;
	}
	
	/**
	 * GETS the results of the task with the given taskid
	 * 
	 * @return mixed description of the task
	 */
	function getResults(){
        global $TBLPREFIX, $DBCONN;

		$sql = "SELECT t_results FROM " . $TBLPREFIX . "tasks WHERE t_id='" . $DBCONN->escapeSimple($_REQUEST["taskid"]). "'";
		$res = dbquery($sql);

        $out = "";
		while($desc = $res->fetchRow()){
			$out = db_cleanup($desc[0]);
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
		global $pgv_lang, $TBLPREFIX;
		$sql = 	"SELECT c_u_username, c_body, c_datetime, c_id " .
				"FROM " . $TBLPREFIX . "comments " .
				"WHERE c_t_id='" . $_REQUEST["taskid"] . "' " .
				"ORDER BY c_datetime DESC";
		$res = dbquery($sql);
		$out = "";

		while($comment = $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$comment = db_cleanup($comment);
			$date=new GedcomDate(date("d M Y", (int)$comment["c_datetime"]));
			$out .= '<div class="blockcontent"><div class="person_box" id="comment1"><span class="news_title">' .
					$comment["c_u_username"] . 	// INSERT username
					'</span><br /><span class="news_date">' .
					$date->Display(false).' - '. date("g:i:s A",(int)$comment["c_datetime"]).		// INSERT datetime
					'</span><br /><br />' .
					nl2br($comment["c_body"]) .			// INSERT body
					'<hr size="1" />';
					
			if(PGV_USER_IS_ADMIN || PGV_USER_NAME==$comment["c_u_username"]){
				$out .= '<a href="javascript:;" onclick="editcomment(' .
							$comment["c_id"] .	// INSERT commentid
							')">'.$pgv_lang["edit"].'</a> | <a href="" onclick="confirm_prompt(\''.$pgv_lang["comment_delete_check"].'\', ' .
							$comment["c_id"] .	// INSERT commentid
							'); return false;">'.$pgv_lang["delete"].'</a>';
			}
			$out .= '<br /></div></div><br/>';
		}
		return $out;
	}
	
	
	if(isset($_REQUEST['delete']) && !empty($_REQUEST['delete'])){
		// TODO: Verify user
		$sql = "DELETE FROM " . $TBLPREFIX . "comments WHERE c_id='$_REQUEST[delete]'";
		$res = dbquery($sql);
	}
?>

<!--JAVASCRIPT-->
<script language="JavaScript" type="text/javascript"><!--
	function showchanges() {
		window.location = 'module.php?mod=research_assistant&action=edittask&taskid=<?php print $_REQUEST['taskid']; ?>';
	}
  	function editcomment(commentid) {
  		window.open('editcomment.php?taskid=<?php print $_REQUEST['taskid']; ?>&commentid='+commentid, '', 'top=50,left=50,width=800,height=500,resizable=1,scrollbars=1');
  	}
  	function confirm_prompt(text, commentid) {
    	if (confirm(text)) {
      		window.location = 'module.php?mod=research_assistant&action=viewtask&delete='+commentid+'&taskid=<?php print $_REQUEST['taskid']; ?>';
    	}
    }
    //-->
</script>
<!--BEGIN VIEW TASK FORM-->
<form action="module.php" method="post">
    <input type="hidden" name="mod" value="research_assistant" />
    <input type="hidden" name="action" value="updatetask" />
    <input type="hidden" name="taskid" value="<?php print $_REQUEST['taskid'] ?>" />
	<table class="list_table" align="center" border="0" width="40%">
  		<tr>
<!--HEADING-->
    		<th colspan="4" align="right" class="topbottombar">
    			<h2>
    				 <?php  print $pgv_lang["view_task"]; print_help_link("ra_task_view_help", "qm", '', false, false);?>
    			</h2>
    		</th>
    	</tr>
    	<tr>
<!----- todo: print RTL title, description, people and source right justified ----->
<!--TITLE-->
			<th class="descriptionbox">
      			<?php print $pgv_lang["title"]; ?>
      		</th>
      		<th class="optionbox" colspan="3" align="left"> 
      		  		
      			<?php
      				// get title, given taskid
      				print PrintReady(getTitle());
      			?>
      		</th>    		
    	</tr>
    	<tr>
<!--DESCRIPTION-->
			<th class="descriptionbox">
      			<?php print $pgv_lang["description"]; ?>
      		</th>
	      	<td class="optionbox" colspan="3" align="left">  
	      		<?php
	      			print PrintReady(nl2br(getDescription()));
	      		?>
	      	</td>
	    </tr>    
    	<tr>
<!--PEOPLE-->
			<th class="descriptionbox">
                    <?php print $pgv_lang["people"]; ?>
            </th>
            <td class="optionbox" colspan="3" align="left">  
      			<?php
      				// Get people, given taskid
      				print getPeople();
      			?>
      			</td>
            </tr>
	    <tr>
<!--SOURCES-->
			<th class="descriptionbox">
      			<?php print $pgv_lang["source"]; ?>
      		</th>
			<td class="optionbox" colspan="3" align="left">  
      		<?php
       			$sources = getSources();
       			$sval = '';
       			foreach($sources as $sid=>$source) {
       				$sval .= ';'.$sid;
       				print '<a id="link_'.$sid.'" href="source.php?sid='.$sid.'">'.$source.'</a>';
       			}
       		?>
      	</td>
        </tr>
        <?php $results = getResults();
        if (!empty($results)) { ?>
        <tr>
<!--results-->
			<th class="descriptionbox">
      			<?php print $pgv_lang["result"]; ?>
      		</th>
	      	<td class="optionbox" colspan="3" >
	      		<?php
	      			print $results;
	      		?>
	      	</td>
	    </tr> 
	    <?php } ?>
	<tr>
<!--EDIT-->
        <th colspan="4" align="right" class="topbottombar"><input type="button" value="<?php print $pgv_lang["edit_task"]; ?>" 
        onclick="window.location.href='module.php?mod=research_assistant&amp;action=edittask&amp;taskid=<?php print $_REQUEST['taskid']; ?>'"/></th>
    </tr>
				
    <tr>
    <td colspan="4">
    <br />
    </td>
    </tr>
    <tr>
<!--HEADING-->
    		<th colspan="4" align="right" class="topbottombar">
    			<h3>
    				<?php print $pgv_lang["comments"]; print_help_link("ra_comments_help", "qm", '', false, false);?>
    			</h3>
    		</th>
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
        <?php print PrintReady(getComments()); ?>
        
	</div>
<!--END COMMENT SECTION-->
</td>
</tr>
<tr class="topbottombar">
    		<td colspan="4">
<input type="button" value="<?php print $pgv_lang["add_new_comment"]; ?>" name="Add New Comment" onclick="window.open('editcomment.php?taskid='+<?php print $_REQUEST['taskid']; ?>, '', 
        'top=50,left=50,width=800,height=500,resizable=1,scrollbars=1');">
        </td>
    	</tr>
	     </table>
    </form>

<!--END VIEW TASK FORM-->
