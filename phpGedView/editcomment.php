<?php
/*
 * Created on Nov 7, 2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 

 require_once("config.php");
 if (file_exists('modules/research_assistant/languages/ra_lang.en.php')) include_once('modules/research_assistant/languages/ra_lang.en.php');
 global $TBLPREFIX;

/***********************************************************************************************************
 *                                        AUTHENTICATING                                                   *
 ***********************************************************************************************************/
 
 
	//********************************************************************************************** 
  	// If the user is not logged in, take them to the login page.
//	if (empty($_SESSION['pgv_user'])){
// 		header("Location: login.php?url={$PHP_SELF}");
// 		exit;
//	}
 
 
 	//**********************************************************************************************
 	// If the user doesnt have access then take them to the index.
 	if ($SHOW_RESEARCH_ASSISTANT < getUserAccessLevel()){
 		header("Location: index.php");
 		exit;
 	}

	




/***********************************************************************************************************
 *                                           REQUESTS                                                      *
 ***********************************************************************************************************/

    //**********************************************************************************************
    //TODO: on new comment, change 'admin' to whoever is logged in.
    // Check if anything is being SUBMITted to the form.
 	if(isset($_REQUEST['submit']) && $_REQUEST['submit'] != ""){
		print_simple_header($pgv_lang["edit_comment"]);
	  	// If we are adding a NEW comment, do an INSERT statement.
	  	 if($_REQUEST['submit'] == "new"){
	  		if ($_REQUEST['type']=='task') {
	  			$cid = get_next_id("comments", "c_id");
	  			$sql = 	"INSERT INTO ".$TBLPREFIX."comments (c_id, c_t_id, c_u_username, c_body, c_datetime) ";
				$sql .=	"VALUES ($cid, '".$DBCONN->escapeSimple($_REQUEST['id'])."', '".getUserName()."', '".$DBCONN->escapeSimple($_POST['body'])."', '".time()."')";
	  		}
	  		else {
	  			$cid = get_next_id("user_comments", "uc_id");
	  			$sql = "INSERT INTO ".$TBLPREFIX."user_comments (uc_id,uc_username,uc_comment,uc_datetime,uc_p_id,uc_f_id) ";
	  			$sql .= "VALUES ($cid, '".getUserName();
	  			$sql .= "','".$DBCONN->escapeSimple($_POST['body']).
	  				"','".time().
	  				"','".$DBCONN->escapeSimple($_REQUEST['id']).
	  				"','".$GEDCOMS[$GEDCOM]['id']."')";
	  		}
	  		$res = dbquery($sql);
	  		print $pgv_lang["comment_success"];
	  	}
	  	
 		// If we are EDITing, do an UPDATE statement.
	  	else {
	  		verify_user(getUserName());
	  		if ($_REQUEST['type']=='task') {
		  		$sql = "UPDATE ".$TBLPREFIX."comments SET c_body='".$DBCONN->escapeSimple($_POST['body'])."' WHERE c_id='$_REQUEST[commentid]'";
	  		}
	  		else {
				$sql = "UPDATE ".$TBLPREFIX."user_comments SET uc_comment='".$DBCONN->escapeSimple($_POST['body'])."' WHERE uc_id='$_REQUEST[commentid]'";
	  		}
	  		$res = dbquery($sql);
	  		print $pgv_lang["comment_success"];
	  	}
	}
	
	//**********************************************************************************************
	// If nothing is being submitted then check if the user is EDITing an existing COMMENT.
	else if(isset($_REQUEST['commentid']) && $_REQUEST['commentid'] != ""){
		verify_user(getUserName());
		$sql = "SELECT c_body FROM ".$TBLPREFIX."comments WHERE c_id='$_REQUEST[commentid]'";
	  	$res = dbquery($sql);
		while($comment =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out = db_cleanup($comment['c_body']);
		}
		print_simple_header($pgv_lang["edit_comment"]);
		print '<span class="subheaders">'.$pgv_lang["edit_comment"].'</span>';
		print print_comment_body($out, 'task', $_REQUEST['commentid'], $_REQUEST['taskid']);
	}
	//**********************************************************************************************
	// If nothing is being submitted then check if the user is EDITing an existing COMMENT.
	else if(isset($_REQUEST['ucommentid']) && $_REQUEST['ucommentid'] != ""){
		verify_user(getUserName());
		$sql = "SELECT uc_comment FROM ".$TBLPREFIX."user_comments WHERE uc_id='$_REQUEST[ucommentid]'";
	  	$res = dbquery($sql);
		while($comment =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out = db_cleanup($comment['uc_comment']);
		}
		print_simple_header($pgv_lang["edit_comment"]);
		print '<span class="subheaders">'.$pgv_lang["edit_comment"].'</span>';
		print print_comment_body($out, 'person', $_REQUEST['ucommentid'], $_REQUEST['pid']);
	}
	//**********************************************************************************************
	// If the user is not editing an existing comment, check if the user is adding a NEW comment.  
	else if(isset($_REQUEST['taskid']) && $_REQUEST['taskid'] != ""){
		print_simple_header($pgv_lang["add_new_comment"]);
		print '<span class="subheaders">'.$pgv_lang["add_new_comment"].'</span>';
	  	print print_comment_body('', 'task', 'new', $_REQUEST['taskid']);
	}
	//**********************************************************************************************
	// If the user is not editing an existing comment, check if the user is adding a NEW person comment.  
	else if(isset($_REQUEST['pid']) && $_REQUEST['pid'] != ""){
		print_simple_header($pgv_lang["add_new_comment"]);
		print '<span class="subheaders">'.$pgv_lang["add_new_comment"].'</span>';
	  	print print_comment_body('', 'person', 'new', $_REQUEST['pid']);
	}
	  
	//**********************************************************************************************
	// If none of the above occur, then give an error message.
	else{
	  	print_error();
	}
	
	
	
	
	
	
/***********************************************************************************************************
 *                                           FUNCTIONS                                                     *
 ***********************************************************************************************************/
	
	
	/****************************************************************************************
	 * Prints a textarea containing $body or blank if not supplied.
	 * 
	 * @param optional $body to be placed as text into the text area
	 * @param string $type	the type of comment being added
	 * @param string $commentid	the id of the comment
	 * @param string $id	the id of the task or person
	 * @return textsarea with existing comment or a blank textarea for adding a new comment
	 */
	function print_comment_body($body = '', $type='task', $commentid='new', $id=''){
	global $pgv_lang;
		$out = '<form action="editcomment.php" method="post">';
		$out .= '<input type="hidden" name="id" value="'.$id.'" />';
		$out .= '<input type="hidden" name="type" value="'.$type.'" />';
		$out .= '<input type="hidden" name="commentid" value="'.$commentid.'" />';
		$out .= '<input type="hidden" name="submit" value="'.$commentid.'" />';
		$out .= '<table><tr><td valign="top" align="right">' .
	  		   $pgv_lang['comment_body'] . 
	  		   '</td><td><textarea name="body" rows="6" cols="60" wrap="on">';
	  	$out .= $body;
		$out .= '</textarea></td></tr><tr><td></td><td><input type="submit" value="' .
				$pgv_lang['save'] .
				'"/></td></tr></table></form>';
		
	  	return $out;
	}
	
	
	/****************************************************************************************
	 * Prints an error message.
	 * 
	 * @return an error message
	 */
	function print_error(){
		print_simple_header("Error");
	  	print "An error has occured.";
	}
	
	
	/****************************************************************************************
	 * Verify if the user has permission to edit the current comment.
	 * 
	 * @return true if the user can edit the comment, false otherwise.
	 */
	function verify_user($user){
		if(userIsAdmin($user)){
			return;
		}
		
		$sql = "SELECT c_u_username FROM pgv_comments WHERE c_id='$_REQUEST[commentid]'";
	  	$res = dbquery($sql);
		while($users =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out = $users['c_u_username'];
		}
		
		if($user == $out){
			return;
		}
		
		else{
			header("Location: index.php");
 			exit;
		}
	}
 
 
 
 
 
 
/***********************************************************************************************************
*                                           FOOTER                                                         *
***********************************************************************************************************/
 	
 	// Refreshes the opener window, which then displays any edited changes or new comments.
 	print "<center><br /><br /><a href=\"#\" onclick=\"if (window.opener.showchanges) window.opener.showchanges(); window.close();\">close window</a><br /></center>";
 	
	print_simple_footer();
	
?>
