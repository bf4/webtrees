<?php
/**
 * MyGedView page allows a logged in user the abilty
 * to keep bookmarks, see a list of upcoming events, etc.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  PGV Development Team
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
 * @subpackage Display
 * @version $Id$
 */

require_once("config.php");

if (!isset($CONFIGURED)) {
	print "Unable to include the config.php file.  Make sure that . is in your PHP include path in the php.ini file.";
	exit;
}

if (isset ($_REQUEST['mod']))
{
	require_once 'module.php';
	exit;
}

/**
 * Block definition array
 *
 * The following block definition array defines the
 * blocks that can be used to customize the portals
 * their names and the function to call them
 * "name" is the name of the block in the lists
 * "descr" is the name of a $pgv_lang variable to describe this block
 * - eg: "whatever" here means that $pgv_lang["whatever"] describes this block
 * "type" the options are "user" or "gedcom" or undefined
 * - The type determines which lists the block is available in.
 * - Leaving the type undefined allows it to be on both the user and gedcom portal
 * @global $PGV_BLOCKS
 */

/**
 * Load List of Blocks in blocks directory (unchanged)
 */
$PGV_BLOCKS = array();
$d = dir("blocks");
while (false !== ($entry = $d->read())) {
	if (($entry!=".") && ($entry!="..") && ($entry!="CVS") && (strstr($entry, ".php")!==false)) {
		include_once("blocks/".$entry);
	}
}
$d->close();
/**
 * End loading list of Blocks in blocks directory
 * 
 * Load List of Blocks in modules/XX/blocks directories
 */
if (file_exists("modules")) {
	$dir=dir("modules");
	while (false !== ($entry = $dir->read())) {
		if (!strstr($entry,".") && ($entry!="..") && ($entry!="CVS")&& !strstr($entry, "svn")) {
			$path = 'modules/' . $entry.'/blocks';
			if (is_readable($path)) {
				$d=dir($path);
				while (false !== ($entry = $d->read())) {
					if (($entry!=".") && ($entry!="..") && ($entry!="CVS")&& !strstr($entry, "svn")&&(strstr($entry, ".php")!==false)) {
						$p=$path.'/'.$entry;
						include_once($p);
					}
				}
			}
		}
	}
}
/**
 * End loading list of Blocks in modules/XX/blocks directories
*/

if (isset($_SESSION["timediff"])) $time = time()-$_SESSION["timediff"];
else $time = time();
$day = date("j", $time);
$month = date("M", $time);
$year = date("Y", $time);
if ($USE_RTL_FUNCTIONS) {
	//-------> Today's Hebrew Day with Gedcom Month
	$datearray = array();
 	$datearray[0]["day"]   = $day;
 	$datearray[0]["mon"]   = $monthtonum[str2lower(trim($month))];
 	$datearray[0]["year"]  = $year;
 	$datearray[0]["month"] = $month;

    $date   = gregorianToJewishGedcomDate($datearray);
    $hDay   = $date[0]["day"];
    $hMonth = $date[0]["month"];
    $hYear	= $date[0]["year"];

//    $currhDay   = $hDay;
//    $currhMon   = trim($date[0]["month"]);
//    $currhMonth = $monthtonum[str2lower($currhMon)];
    $currhYear 	= $hYear;
}

if (!isset($action)) $action="";

//-- make sure that they have user status before they can use this page
//-- otherwise have them login again
$uname = getUserName();
if (empty($uname)) {
	if (!empty($command)) {
		if ($command=="user") {
			header("Location: login.php?help_message=mygedview_login_help&url=".urlencode("index.php?command=user"));
			exit;
		}
	}
	$command="gedcom";
}
else $user = getUser($uname);

if (empty($command)) $command="user";

if (!empty($uname)) {
	//-- add favorites action
	if (($action=="addfav")&&(!empty($gid))) {
		$gid = strtoupper($gid);
		if (!isset($favnote)) $favnote = "";
		$indirec = find_gedcom_record($gid);
		$ct = preg_match("/0 @(.*)@ (.*)/", $indirec, $match);
		if ($indirec && $ct>0) {
			$favorite = array();
			if (!isset($favtype)) {
				if ($command=="user") $favtype = "user";
				else $favtype = "gedcom";
			}
			if ($favtype=="gedcom") $favtype = $GEDCOM;
			else $favtype=$uname;
			$favorite["username"] = $favtype;
			$favorite["gid"] = $gid;
			$favorite["type"] = trim($match[2]);
			$favorite["file"] = $GEDCOM;
			$favorite["url"] = "";
			$favorite["note"] = $favnote;
			$favorite["title"] = "";
			addFavorite($favorite);
		}
	}
	if (($action=="addfav")&&(!empty($url))) {
		if (!isset($favnote)) $favnote = "";
		if (empty($favtitle)) $favtitle = $url;
		$favorite = array();
		if (!isset($favtype)) {
			if ($command=="user") $favtype = "user";
			else $favtype = "gedcom";
		}
		if ($favtype=="gedcom") $favtype = $GEDCOM;
		else $favtype=$uname;
		$favorite["username"] = $favtype;
		$favorite["gid"] = "";
		$favorite["type"] = "URL";
		$favorite["file"] = $GEDCOM;
		$favorite["url"] = $url;
		$favorite["note"] = $favnote;
		$favorite["title"] = $favtitle;
		addFavorite($favorite);
	}
	if (($action=="deletefav")&&(isset($fv_id))) {
		deleteFavorite($fv_id);
	}
	else if ($action=="deletemessage") {
		if (isset($message_id)) {
			if (!is_array($message_id)) deleteMessage($message_id);
			else {
				foreach($message_id as $indexval => $mid) {
					if (isset($mid)) deleteMessage($mid);
				}
			}
		}
	}
	else if (($action=="deletenews")&&(isset($news_id))) {
		deleteNews($news_id);
	}
}

//-- get the blocks list
if ($command=="user") {
	$ublocks = getBlocks($uname);
	if ((count($ublocks["main"])==0) and (count($ublocks["right"])==0)) {
		$ublocks["main"][] = array("print_todays_events", "");
		$ublocks["main"][] = array("print_user_messages", "");
		$ublocks["main"][] = array("print_user_favorites", "");

		$ublocks["right"][] = array("print_welcome_block", "");
		$ublocks["right"][] = array("print_random_media", "");
		$ublocks["right"][] = array("print_upcoming_events", "");
		$ublocks["right"][] = array("print_logged_in_users", "");
	}
}
else {
	$ublocks = getBlocks($GEDCOM);
	if ((count($ublocks["main"])==0) and (count($ublocks["right"])==0)) {
		$ublocks["main"][] = array("print_gedcom_stats", "");
		$ublocks["main"][] = array("print_gedcom_news", "");
		$ublocks["main"][] = array("print_gedcom_favorites", "");
		$ublocks["main"][] = array("review_changes_block", "");

		$ublocks["right"][] = array("print_gedcom_block", "");
		$ublocks["right"][] = array("print_random_media", "");
		$ublocks["right"][] = array("print_todays_events", "");
		$ublocks["right"][] = array("print_logged_in_users", "");
	}
}

//-- Set some behaviour controls that depend on which blocks are selected
$welcome_block_present = false;
$gedcom_block_present = false;
$top10_block_present = false;
$login_block_present = false;
foreach($ublocks["right"] as $block) {
	if ($block[0]=="print_welcome_block") $welcome_block_present = true;
	if ($block[0]=="print_gedcom_block") $gedcom_block_present = true;
	if ($block[0]=="print_block_name_top10") $top10_block_present = true;
	if ($block[0]=="print_login_block") $login_block_present = true;
}
foreach($ublocks["main"] as $block) {
	if ($block[0]=="print_welcome_block") $welcome_block_present = true;
	if ($block[0]=="print_gedcom_block") $gedcom_block_present = true;
	if ($block[0]=="print_block_name_top10") $top10_block_present = true;
	if ($block[0]=="print_login_block") $login_block_present = true;
}

//-- handle block AJAX calls
/**
 * In order for a block to make an AJAX call the following request parameters must be set
 * block = the method name of the block to call (e.g. 'print_random_media')
 * side = the side of the page the block is on (e.g. 'main' or 'right')
 * bindex = the number of the block on that side, first block = 0
 */
if ($action=="ajax") {
	//--  if a block wasn't sent then exit with nothing
	if (!isset($_REQUEST['block'])) {
		print "Block not sent";
		exit;
	}
	$block = $_REQUEST['block'];
	//-- set which side the block is on
	$side = "main";
	if (isset($_REQUEST['side'])) $side = $_REQUEST['side'];
	//-- get the block number
	if (isset($_REQUEST['bindex'])) {
		if (isset($ublocks[$side][$_REQUEST['bindex']])) {
			$blockval = $ublocks[$side][$_REQUEST['bindex']];
			if ($blockval[0]==$block && function_exists($blockval[0])) {
				if ($side=="main") $param1 = "false";
				else $param1 = "true";
				eval($blockval[0]."($param1, \$blockval[1], \"$side\", ".$_REQUEST['bindex'].");");
				exit;
			}
		}
	}
	
	//-- not sure which block to call so call the first one we find
	foreach($ublocks["main"] as $bindex=>$blockval) {
		if (isset($DEBUG)&&($DEBUG==true)) print_execution_stats();
		if ($blockval[0]==$block && function_exists($blockval[0])) eval($blockval[0]."(false, \$blockval[1], \"main\", $bindex);");
	}
	foreach($ublocks["right"] as $bindex=>$blockval) {
		if (isset($DEBUG)&&($DEBUG==true)) print_execution_stats();
		if ($blockval[0]==$block && function_exists($blockval[0])) eval($blockval[0]."(true, \$blockval[1], \"right\", $bindex);");
	}
	exit;
}
//-- end of ajax call handler

if ($command=="user") {
	$helpindex = "index_myged_help";
	print_header($pgv_lang["mygedview"]);
}
else {
	print_header($GEDCOMS[$GEDCOM]["title"]);
}
?>
<script language="JavaScript" type="text/javascript">
<!--
	function refreshpage() {
		window.location = 'index.php?command=<?php print $command; ?>';
	}
	function addnews(uname) {
		window.open('editnews.php?username='+uname, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	}
	function editnews(news_id) {
		window.open('editnews.php?news_id='+news_id, '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1');
	}
	var pastefield;
	function paste_id(value) {
		pastefield.value=value;
	}
	<?php if (isset($DEBUG)&&($DEBUG==true)) print "var DEBUG = true;\n"; else print "var DEBUG = false;\n"; ?>
	/**
	 * blocks may use this JS function to update themselves using AJAX technology
	 * @param string targetId	the id of the block to target the results too
	 * @param string block 	the method name of the block to call (e.g. 'print_random_media')
 	 * @param string side 	the side of the page the block is on (e.g. 'main' or 'right')
	 * @param int bindex 	the number of the block on that side, first block = 0
	 * @param boolean loading  Whether or not to show the loading message
	 */
	function ajaxBlock(targetId, block, side, bindex, loading) {
		target = document.getElementById(targetId);
		if (!target) return false;
		
		target.style.height = (target.offsetHeight) + "px";
		if (loading) target.innerHTML = "<br /><br /><?php print $pgv_lang['loading']; ?><br /><br />";
		
		var oXmlHttp = createXMLHttp();
		link = "index.php?action=ajax&block="+block+"&side="+side+"&bindex="+bindex;
		if (DEBUG) link = link + "&DEBUG="+DEBUG;
		oXmlHttp.open("get", link, true);
		oXmlHttp.onreadystatechange=function()
		{
  			if (oXmlHttp.readyState==4)
  			{
   				target.innerHTML = oXmlHttp.responseText;
   				target.style.height = 'auto';
   			}
  		};
  		oXmlHttp.send(null);
  		return false;
	}
//-->
</script>
<?php
//-- start of main content section
print "<table width=\"100%\"><tr><td>";		// This is needed so that page footers print in the right place
if ($command=="user") {
	print "<div align=\"center\" style=\"width: 99%;\">";
	print "<h1>".$pgv_lang["mygedview"]."</h1>";
	print $pgv_lang["mygedview_desc"];
	print "<br /><br /></div>\n";
}
if (count($ublocks["main"])!=0) {
	if (count($ublocks["right"])!=0) {
		print "\t<div id=\"index_main_blocks\">\n";
	} else {
		print "\t<div id=\"index_full_blocks\">\n";
	}

	foreach($ublocks["main"] as $bindex=>$block) {
		if (isset($DEBUG)&&($DEBUG==true)) print_execution_stats();
		if (function_exists($block[0])) eval($block[0]."(false, \$block[1], \"main\", $bindex);");
	}
	print "</div>\n";
}
//-- end of main content section

//-- start of blocks section
if (count($ublocks["right"])!=0) {
	if (count($ublocks["main"])!=0) {
		print "\t<div id=\"index_small_blocks\">\n";
	} else {
		print "\t<div id=\"index_full_blocks\">\n";
	}
	foreach($ublocks["right"] as $bindex=>$block) {
		if (isset($DEBUG)&&($DEBUG==true)) print_execution_stats();
		if (function_exists($block[0])) eval($block[0]."(true, \$block[1], \"right\", $bindex);");
	}
	print "\t</div>\n";
}
//-- end of blocks section

print "</td></tr></table><br />";		// Close off that table

if (($command=="user") and (!$welcome_block_present)) {
	print "<div align=\"center\" style=\"width: 99%;\">";
	print_help_link("mygedview_customize_help", "qm");
	print "<a href=\"javascript:;\" onclick=\"window.open('index_edit.php?name=".getUserName()."&amp;command=user', '_blank', 'top=50,left=10,width=600,height=500,scrollbars=1,resizable=1');\">".$pgv_lang["customize_page"]."</a>\n";
	print "</div>";
}
if (($command=="gedcom") and (!$gedcom_block_present)) {
	if (userIsAdmin(getUserName())) {
		print "<div align=\"center\" style=\"width: 99%;\">";
		print "<a href=\"javascript:;\" onclick=\"window.open('index_edit.php?name=$GEDCOM&amp;command=gedcom', '_blank', 'top=50,left=10,width=600,height=500,scrollbars=1,resizable=1');\">".$pgv_lang["customize_gedcom_page"]."</a>\n";
		print "</div>";
	}
}

print_footer();
?>
