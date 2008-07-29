<?php
/**
 *  Manage Servers Page
 *
 *  Allow a user the ability to manage servers i.e. allowing, banning, deleting
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Admin
 * @version $Id$
 * @author rbennett
 */

require("config.php");
require_once("includes/functions.php");
require_once("includes/functions_edit.php");
require_once("includes/functions_import.php");
require_once("includes/serviceclient_class.php");

print_header($pgv_lang["title_manage_servers"]);
//-- only allow gedcom admins here
if (!PGV_USER_GEDCOM_ADMIN) {
	print $pgv_lang["access_denied"];
	//-- display messages as to why the editing access was denied
	if (!PGV_USER_GEDCOM_ADMIN) print "<br />".$pgv_lang["user_cannot_edit"];
	print "<br /><br /><div class=\"center\"><a href=\"javascript: ".$pgv_lang["close_window"]."\" onclick=\"window.close();\">".$pgv_lang["close_window"]."</a></div>\n";
	print_footer();
	exit;
}

$banned = array();
if (file_exists($INDEX_DIRECTORY."banned.php"))	require($INDEX_DIRECTORY."banned.php");
$banned = array_unique($banned);						// Make sure we have no duplicates
$search_engines = array();
if (file_exists($INDEX_DIRECTORY."search_engines.php"))	require($INDEX_DIRECTORY."search_engines.php");
$search_engines = array_unique($search_engines);		// Make sure we have no duplicates
$remoteServer = array();
$remoteServer = get_server_list();

$action = safe_GET('action');
if (empty($action)) $action = safe_POST('action');
$address = safe_GET('address');
if (empty($address)) $address = safe_POST('address');

$deleteBanned = safe_POST('deleteBanned');
if (!empty($deleteBanned)) {		// A "remove banned IP" button was pushed
	$action = 'deleteBanned';
	$address = $deleteBanned;
}

$deleteSearch = safe_POST('deleteSearch');
if (!empty($deleteSearch)) {		// A "remove search engine IP" button was pushed
	$action = 'deleteSearch';
	$address = $deleteSearch;
}

$deleteServer = safe_POST('deleteServer');
if (!empty($deleteServer)) {		// A "remove remote server" button was pushed
	$action = 'deleteServer';
	$address = $deleteServer;
}

if (empty($action)) $action = 'showForm';

/*
 * Validate input string to be an IP address
 */
function validIP($address) {
	if (!preg_match('/^\d{1,3}\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)$/', $address)) return false;
	$pieces = explode('.', $address);
	foreach ($pieces as $number) {
		if ($number!="*" && $number>255) return false;
	}
	return true;
}


/**
 * Adds an IP address to the banned.php file
 */
if ($action=='addBanned') {
	if (validIP($address)) {
		$banned[] = $address;
		$banned = array_unique($banned);		// Make sure we have no duplicates
		
		$bannedtext = "<?php\n//--List of banned IP addresses\n";
		$bannedtext .= "\$banned = array();\n";	
		foreach ($banned as $value) {
			$bannedtext .= "\$banned[] = \"".$value."\";\n";
		}
		$bannedtext .= "\n"."?>";
    	
		$fp = @fopen($INDEX_DIRECTORY."banned.php", "w");
		if (!$fp) {
			global $whichFile;
			$whichFile = $INDEX_DIRECTORY."banned.php";
			$errorBanned = print_text("gedcom_config_write_error",0,1);
		} else {
			fwrite($fp, $bannedtext);
			fclose($fp);
			$logline = AddToLog("banned.php updated");
 			check_in($logline, "banned.php", $INDEX_DIRECTORY);	
		}
	} else $errorBanned = $pgv_lang["error_ban_server"];
	
	$action = 'showForm';
}

/**
 * Removes an IP address from the banned.php file
 */
if ($action=='deleteBanned') {
	$banned = array_flip($banned);
	unset($banned[$address]);
	$banned = array_flip($banned);
	
	$bannedtext = "<?php\n//--List of banned IP addresses\n";
	$bannedtext .= "\$banned = array();\n";	
	foreach ($banned as $value) {
		$bannedtext .= "\$banned[] = \"".$value."\";\n";
	}
	$bannedtext .= "\n"."?>";

	$fp = @fopen($INDEX_DIRECTORY."banned.php", "w");
	if (!$fp) {
		global $whichFile;
		$whichFile = $INDEX_DIRECTORY."banned.php";
		$errorBanned = print_text("gedcom_config_write_error",0,1);
	} else {
		fwrite($fp, $bannedtext);
		fclose($fp);
		$logline = AddToLog("banned.php updated");
 		check_in($logline, "banned.php", $INDEX_DIRECTORY);	
	}
	
	$action = 'showForm';
}

/**
 * Adds an IP address to the search_engines.php file
 */
if ($action=='addSearch') {
	if (validIP($address)) {
		$search_engines[] = $address;
		$search_engines = array_unique($search_engines);		// Make sure we have no duplicates
		$searchtext = "<?php\n//--List of search engine IP addresses\n";
		$searchtext .= "\$search_engines = array();\n";	
		foreach ($search_engines as $value) {
			$searchtext .= "\$search_engines[] = \"".$value."\";\n";
		}
		$searchtext .= "\n"."?>";
	
		$fp = @fopen($INDEX_DIRECTORY."search_engines.php", "w");
		if (!$fp) {
			global $whichFile;
			$whichFile = $INDEX_DIRECTORY."search_engines.php";
			$errorSearch = print_text("gedcom_config_write_error",0,1);
		} else {
			fwrite($fp, $searchtext);
			fclose($fp);
			$logline = AddToLog("search_engines.php updated");
	 		check_in($logline, "search_engines.php", $INDEX_DIRECTORY);	
		}
	} else $errorSearch = $pgv_lang["error_ban_server"];
	
	$action = 'showForm';
}

/**
 * Removes an IP address from the search_engines.php file
 */
if ($action=='deleteSearch') {
	$search_engines = array_flip($search_engines);
	unset($search_engines[$address]);
	$search_engines = array_flip($search_engines);
		
	$searchtext = "<?php\n//--List of search engine IP addresses\n";
	$searchtext .= "\$search_engines = array();\n";	
	foreach ($search_engines as $value) {
		$searchtext .= "\$search_engines[] = \"".$value."\";\n";
	}
	$searchtext .= "\n"."?>";

	$fp = @fopen($INDEX_DIRECTORY."search_engines.php", "wb");
	if (!$fp) {
		global $whichFile;
		$whichFile = $INDEX_DIRECTORY."search_engines.php";
		$errorSearch = '<span class="error">'.print_text("gedcom_config_write_error",0,1).'<br /></span>\n';
	} else {
		fwrite($fp, $searchtext);
		fclose($fp);
		$logline = AddToLog("search_engines.php updated");
 		check_in($logline, "search_engines.php", $INDEX_DIRECTORY);	
	}
	
	$action = 'showForm';
}

/**
 * Adds a server to the outbound remote linking list
 */
if ($action=='addServer') {
	$serverTitle = stripslashes(safe_POST('serverTitle'));
	$serverURL = stripslashes(safe_POST('serverURL'));
	$gedcom_id = stripslashes(safe_POST('gedcom_id'));
	$username = stripslashes(safe_POST('username'));
	$password = stripslashes(safe_POST('password'));

	if (!$serverTitle=="" || !$serverURL=="") {
		$gedcom_string = "0 @new@ SOUR\r\n";
		$gedcom_string.= "1 URL ".$serverURL."\r\n";
    	$gedcom_string.= "1 TITL ".$serverTitle."\r\n";
		$gedcom_string.= "1 _DBID ".$gedcom_id."\r\n";
		$gedcom_string.= "2 _USER ".$username."\r\n";
		$gedcom_string.= "2 _PASS ".$password."\r\n";
    	
		$service = new ServiceClient($gedcom_string);
		$sid = $service->authenticate();
		if (empty($sid) || PEAR::isError($sid)) {
			$errorServer = $pgv_lang["error_siteauth_failed"];
		} else {
			$serverID = append_gedrec($gedcom_string);
			accept_changes($serverID."_".$GEDCOM);
		}
	} else $errorServer = $pgv_lang["error_url_blank"];
	
	$remoteServer = array();
	$remoteServer = get_server_list();		// refresh the list
	$action = 'showForm';
}

/**
 * Removes a server from the remote linking outbound list
 */
if ($action=='deleteServer') {
	if (!empty($address)) {
		$address = stripslashes($address);
		if (delete_gedrec($address)) {
			accept_changes($address."_".$GEDCOM);
		}
	}
	
	$remoteServer = array();
	$remoteServer = get_server_list();		// refresh the list
	$action = 'showForm';
}

?>


<table class="width66" align="center">
 <tr>
  <td colspan="2" class="title" align="center">
   <?php echo $pgv_lang["title_manage_servers"];?>
  </td>
 </tr>
 <tr>
  <td>
   <form name="searchengineform" action="manageservers.php" method="post">
   <!-- Search Engine IP address table --> 
   <table class="width100" align="center">
    <tr>
     <td class="facts_label">
      <?php print_help_link("help_manual_search_engines", "qm"); ?>
      <b><?php echo $pgv_lang["label_manual_search_engines"];?></b>
     </td>
    </tr>
    <tr>
     <td class="facts_value">
      <table align="center">
<?php
  foreach ($search_engines as $index=>$value) { 
    ?>
       <tr>
        <td><span dir="ltr">
         <input type="text" name="address<?php echo $index;?>" size="16" value="<?php echo $value;?>" READONLY />
        </span></td>
        <td>
         &nbsp;&nbsp;&nbsp;
         <button name="deleteSearch" value="<?php echo $value;?>" type="submit"><?php echo $pgv_lang["remove"];?></button>
        </td>
       </tr>
<?php }?>
       <tr>
        <td><span dir="ltr">
         <input type="hidden" name="action" value="addSearch" />
         <input type="text" name="address" size="16" value="<?php echo (empty($errorSearch))? '':$address;?>" />
         </span></td>
        <td>
         &nbsp;&nbsp;&nbsp;
         <input type="submit" value="<?php echo $pgv_lang['add'];?>" />
        </td>
       </tr>
<?php
	if (!empty($errorSearch)) {
		print '<tr><td colspan="2"><span class="warning">';
		print $errorSearch;
		print '</span></td></tr>';
		$errorSearch = '';
	}
?>
      </table>
     </td>
    </tr>
   </table>
   </form>
  </td>
 </tr>
</table>

<table class="width66" align="center">
 <tr>
  <td>
   <form name="banIPform" action="manageservers.php" method="post">
   <!-- Banned IP address table --> 
   <table class="width100" align="center">
    <tr>
     <td class="facts_label">
      <?php print_help_link("help_banning", "qm"); ?>
      <b><?php echo $pgv_lang["label_banned_servers"];?></b>
     </td>
    </tr>
    <tr>
     <td class="facts_value">
      <table align="center">
<?php
  foreach ($banned as $index=>$value) 
    { ?>
       <tr>
        <td><span dir="ltr">
         <input type="text" name="address<?php echo $index;?>" size="16" value="<?php echo $value;?>" READONLY />
        </span></td>
        <td>
         &nbsp;&nbsp;&nbsp;
         <button name="deleteBanned" value="<?php echo $value;?>" type="submit"><?php echo $pgv_lang["remove"];?></button>
        </td>
       </tr>
<?php }?>
       <tr>
        <td><span dir="ltr">
         <input name="action" type="hidden" value="addBanned"/>
         <input type="text" id="txtAddIp" name="address" size="16"  value="<?php echo (empty($errorBanned))? '':$address;?>" />
        </span></td>
        <td>
         &nbsp;&nbsp;&nbsp;
         <input type="submit" value="<?php echo $pgv_lang['add'];?>" />
        </td>
       </tr>
<?php
	if (!empty($errorBanned)) {
		print '<tr><td colspan="2"><span class="warning">';
		print $errorBanned;
		print '</span></td></tr>';
		$errorBanned = '';
	}
?>
      </table>
     </td>
    </tr>
   </table>
   </form>
  </td>
 </tr>
</table>

<table class="width66" align="center">
 <tr>
  <td>
   <form name="serverlistform" action="manageservers.php" method="post">
   <!-- remote server list --> 
   <table class="width100">
    <tr>
     <td class="facts_label">
      <b><?php echo $pgv_lang["label_added_servers"];?></b>
     </td>
    </tr>
    <tr>
     <td class="facts_value">
      <table>
<?php
  foreach ($remoteServer as $sid=>$value) 
    { ?>
       <tr>
        <td>
          <button name="deleteServer" value="<?php echo $sid;?>" type="submit"><?php echo $pgv_lang["remove"];?></button>
        </td>
        <td>
         &nbsp;&nbsp;
         <button type="button" onclick="window.open('viewconnections.php?selectedServer=<?php echo $sid;?>', '_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1')"><?php echo $pgv_lang["title_view_conns"];?></button>
         &nbsp;&nbsp;
         <?php echo PrintReady($value["name"]); ?>
        </td>
       </tr>
<?php }?>
      </table>
     </td>
    </tr>
   </table>
   </form>
  </td>
 </tr>
</table>
<?php
if (empty($errorServer)) {
	$serverTitle = '';
	$serverURL = '';
	$gedcom_id = '';
	$username = '';
}
?>
<form name="addserversform" action="manageservers.php" method="post"">
<table class="width66" align="center">
 <tr>
  <td valign="top">
   <!-- Add remote server table -->
   <table class="width100">
    <tr>
     <td class="facts_label" colspan="2">
      <?php print_help_link("help_remotesites", "qm"); ?>
      <b><?php print $pgv_lang["label_new_server"];?></b>
     </td>
    </tr>
    <tr>
     <td class="facts_label width20">
      <?php print $pgv_lang["title"];?>
     </td>
     <td class="facts_value">
      <input type="text" size="50" name="serverTitle" value="<?php echo PrintReady($serverTitle);?>" />
     </td>
    </tr>
    <tr>
     <td class="facts_label width20">
      <?php print_help_link('link_remote_site_help', 'qm');?>
      <?php print $pgv_lang["label_server_url"];?>
     </td>
     <td class="facts_value">
      <input type="text" size="50" name="serverURL" value="<?php echo PrintReady($serverURL);?>" />
      <br /><?php echo $pgv_lang["example"];?>&nbsp;&nbsp;http://www.remotesite.com/phpGedView/genservice.php?wsdl
     </td>
    </tr>
    <tr>
     <td class="facts_label width20">
      <?php echo $pgv_lang["label_gedcom_id2"];?>
     </td>
     <td class="facts_value">
      <input type="text" name="gedcom_id" value="<?php echo PrintReady($gedcom_id);?>" />
     </td>
    </tr>
    <tr>
     <td class="facts_label width20">
      <?php print $pgv_lang["label_username_id"];?>
     </td>
     <td class="facts_value">
      <input type="text" name="username" value="<?php echo PrintReady($username);?>" />
     </td>
    </tr> 
    <tr>
     <td class="facts_label width20">
      <?php print $pgv_lang["label_password_id"];?>
     </td>
     <td class="facts_value">
      <input type="password" name="password" />
     </td>
    </tr>
    <tr>
     <td class="facts_value" align="center" colspan="2">
      <input type="submit" value="<?php echo $pgv_lang['add'];?>" />
      <input name="action" type="hidden" value="addServer"/>
<?php
	if (!empty($errorServer)) {
		print '<br /><br /><span class="warning">';
		print $errorServer;
		print '</span>';
		$errorServer = '';
	}
?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
<?php
  print_footer();
?>
