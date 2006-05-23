<?php
/*
 * Created on May 18, 2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 if (strstr($_SERVER["SCRIPT_NAME"],"module.php")===false) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
 }
 global $PRIV_HIDE, $PRIV_PUBLIC, $PRIV_USER, $PRIV_NONE;
 
 $SHOW_ADD_TASK          = $PRIV_PUBLIC;
 
 $SHOW_VIEW_FOLDERS      = $PRIV_PUBLIC;
 
 $SHOW_ADD_FOLDER        = $PRIV_PUBLIC;
 
 $SHOW_VIEW_INFERENCES   = $PRIV_PUBLIC;
 
?>
