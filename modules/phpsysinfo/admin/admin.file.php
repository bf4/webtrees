<?php
// $Id$
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
//                                                                          //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
//                                                                          //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
//                                                                          //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: phppp (D.J., infomax@gmail.com)                                  //
// URL: http://xoopsforge.com, http://xoops.org.cn                          //
// Project: Article Project                                                 //
// ------------------------------------------------------------------------ //
include('header.php');
header("location: ".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/admin/index.php");
exit();

xoops_cp_header();
loadModuleAdminMenu(8);

$start = empty($_GET['start'])?0:intval($_GET['start']);
$file_id = empty($_GET['file'])?0:intval($_GET['file']);
$type = empty($_GET['typ'])?"all":$_GET['type'];
$op = empty($_GET['op'])?"":$_GET['op'];

$file_handler =& xoops_getmodulehandler('file', $GLOBALS["artdirname"]);

if(!empty($op) && $op=="delete"){
	$file_handler->delete($file_id);
	$redirect = XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/admin/admin.file.php?type=".$type."&amp;start=".$start;
	redirect_header($redirect, 2, _art_MD_ACTIONDONE);
}

$files_all = $file_handler->getCount();
$files_orphan = $file_handler->getCountOrphan();
//$file_lost = $file_handler->getCount();

$type = "all";
if($type == "orphan"){
	$files_obj = $file_handler->getOrphanByLimit(10, $start);
	$file_count = $files_orphan;
}else{
	$files_obj = $file_handler->getByLimit(10, $start);
	$file_count = $files_all;
}

$uids = array();
$aids = array();
foreach($files_obj as $id=>$file){
	//$uids[$file->getVar('file_uid')] = 1;
	$aids[$file->getVar('art_id')] = 1;
}
$article_handler =& xoops_getmodulehandler('article', $GLOBALS["artdirname"]);
$criteria = new Criteria("art_id", "(".implode(",",array_keys($aids)).")", "IN");
$articles =& $article_handler->getAll($criteria, array("art_title AS title"), false);

if ( $file_count > $xoopsModuleConfig['articles_perpage']) {
	include(XOOPS_ROOT_PATH.'/class/pagenav.php');
	$nav = new XoopsPageNav($file_count, $xoopsModuleConfig['articles_perpage'], $start, "start", 'type='.$type);
	$pagenav = $nav->renderNav(4);
} else {
	$pagenav = '';
}

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . art_constant("AM_FILES") . "</legend>";
echo "<div style='padding: 8px;'>";
echo "<div><a href='".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/admin/admin.file.php?type=all'>".art_constant("AM_FILES_ALL").": ". $files_all."</a></div>";
echo "<div><a href='".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/admin/admin.file.php?type=orphan'>".art_constant("AM_FILES_ORPHAN").": ". $files_orphan."</a></div>";
//echo "<div><a href='".XOOPS_URL."/modules".$GLOBALS["artdirname"]."/admin/admin.file.php?type=lost'>".art_constant("AM_FILES_LOST").": ". $files_lost."</a></div>";

foreach($files_obj as $id=>$file){
	echo "<div style='padding: 4px;'>";
	echo "<br />" . art_constant("AM_FILENAME") .": ".$file->getVar('file_name');
	if(!art_upload_exists($file->getVar('file_name')))
	echo "<font color='red'>".art_constant("AM_LOST") ."</font>";
	echo "<br />" . art_constant("AM_ARTICLE") .": ";
	if(empty($articles[$file->getVar('art_id')]))
	echo "<font color='red'>".art_constant("AM_ORPHAN") ."</font>";
	else
	echo "<a href='".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/view.article.php?article=".$file->getVar('art_id')."&amp;start=".$start."' target='_blank'>".$articles[$file->getVar('art_id')]['title']."</a>";
	echo "<br />" . art_constant("AM_ACTION") .": [<a href='".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/admin/admin.file.php?type=".$type."&amp;op=delete&amp;file=".$id."&amp;start=".$start."'>"._DELETE."</a>]";
	echo "</div>";
}

echo "<div>".$pagenav."</div>";
echo "</div>";
echo "</fieldset><br />";

xoops_cp_footer();
?>
