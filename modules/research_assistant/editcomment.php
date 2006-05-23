<?php
/*
 * Created on Nov 7, 2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 

 require_once("config.php");
 global $TBLPREFIX;

/***********************************************************************************************************
 *                                        AUTHENTICATING                                                   *
 ***********************************************************************************************************/
 
 
	//********************************************************************************************** 
  	// If the user is not logged in, take them to the login page.
	if (empty($_SESSION['pgv_user'])){
 		header("Location: login.php?url={$PHP_SELF}");
 		exit;
	}
 
 
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
 		
	  	// If we are EDITing, do an UPDATE statement.
	  	if($_REQUEST['submit'] == "edit"){
	  		verify_user(getUserName());
	  		$sql = "UPDATE ".$TBLPREFIX."comments SET c_body='$_POST[body]' WHERE c_id='$_REQUEST[commentid]'";
	  		$res = dbquery($sql);
	  		print "Your comment was successfully edited.";
	  	}
	  	
	  	// If we are adding a NEW comment, do an INSERT statement.
	  	else if($_REQUEST['submit'] == "new"){
	  		$sql = "SELECT concat(u_firstname, ' ',u_lastname) as name FROM pgv_users WHERE u_username='admin'";
	  		$res = dbquery($sql);
	  		$name =& $res->fetchRow(DB_FETCHMODE_ASSOC);
			$username = $name["name"];
	  		
	  		$cid = get_next_id("comments", "c_id");
	  		
	  		$sql = 	"INSERT INTO ".$TBLPREFIX."comments (c_id, c_t_id, c_u_username, c_body, c_datetime) " .
					"VALUES ($cid, '$_REQUEST[taskid]', '$username', '$_POST[body]', '".time()."')";
	  		$res = dbquery($sql);
	  		print "Your comment was successfully added.";
	  	}
	  	
	  	// Otherwise print out an error message.
	  	else{
	  		//error
	  	}
	}
	
	//**********************************************************************************************
	// If nothing is being submitted then check if the user is EDITing an existing COMMENT.
	else if(isset($_REQUEST['commentid']) && $_REQUEST['commentid'] != ""){
		verify_user(getUserName());
		$sql = "SELECT c_body FROM ".$TABLEPREFIX."comments WHERE c_id='$_REQUEST[commentid]'";
	  	$res = dbquery($sql);
		while($comment =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out = $comment['c_body'];
		}
		print_simple_header('Edit Comment');
		print '<span class="subheaders">'."Edit Comment".'</span>';
		print print_comment_body($out);
	}
	
	//**********************************************************************************************
	// If the user is not editing an existing comment, check if the user is adding a NEW comment.  
	else if(isset($_REQUEST['taskid']) && $_REQUEST['taskid'] != ""){
		print_simple_header("Add New Comment");
		print '<span class="subheaders">'."Add New Comment".'</span>';
	  	print print_comment_body();
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
	 * @return textsarea with existing comment or a blank textarea for adding a new comment
	 */
	function print_comment_body($body = ''){
		$out = '<table><tr><td valign="top" align="right">' .
	  		   'comment body' . // lang
	  		   '</td><td><textarea name="body" rows="10" cols="80" wrap="on">';
	  	
	  	if(!empty($body)){
	  		$out .= $body;
	  		$out = '<form action="editcomment.php?taskid='.$_REQUEST['taskid'].'&commentid='.$_REQUEST['commentid'].'&submit=edit" method="post">'.$out;
	  	}
	  	else{
	  		$out = '<form action="editcomment.php?taskid='.$_REQUEST['taskid'].'&submit=new" method="post">'.$out;
	  	}
		$out .= '</textarea></td></tr><tr><td></td><td><input type="submit" value="' .
				'save' . // lang
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
 	print "<center><br /><br /><a href=\"#\" onclick=\"if (window.opener.refreshpage) window.opener.refreshpage(); window.close();\">close window</a><br /></center>";
 	
	print_simple_footer();
	
?>
