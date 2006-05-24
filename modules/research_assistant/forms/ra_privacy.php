<?php
/*
 * Created on May 18, 2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 if (strstr($_SERVER["SCRIPT_NAME"],"ra_privacy.php")!==false) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
 }
 global $PRIV_HIDE, $PRIV_PUBLIC, $PRIV_USER, $PRIV_NONE;
 
 $SHOW_MY_TASKS              = $PRIV_NONE;
 
 $SHOW_ADD_TASK              = $PRIV_NONE;
 
 $SHOW_VIEW_FOLDERS          = $PRIV_NONE;
 
 $SHOW_ADD_FOLDER            = $PRIV_NONE;
 
 $SHOW_ADD_UNLINKED_SOURCE   = $PRIV_NONE;
  
 $SHOW_VIEW_PROBABILITIES    = $PRIV_NONE;
?>
