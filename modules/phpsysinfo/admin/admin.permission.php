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
require_once(XOOPS_ROOT_PATH."/class/xoopsform/grouppermform.php");
//require_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
require_once(XOOPS_ROOT_PATH."/modules/".$GLOBALS["artdirname"]."/class/permission.php");
include_once XOOPS_ROOT_PATH."/modules/".$GLOBALS["artdirname"]."/class/xoopsformloader.php";

xoops_cp_header();
loadModuleAdminMenu(4);

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . art_constant("AM_PERMISSION") . "</legend>";
echo "<div style='padding: 8px;'>";
echo art_constant("AM_PERMISSION_DESC"); // "access" of a category is subject to the parent category's access permission
echo "</div>";
echo "</fieldset><br />";


$mid = $xoopsModule->getVar('mid');

$op_options = array("global"=>art_constant("AM_PERMISSION_GLOBAL"));
$fm_options = array("global"=>array("title"=>art_constant("AM_PERMISSION_GLOBAL"), "item"=>"global", "desc"=>"", "anonymous"=>true));
foreach($GLOBALS["perms_category"] as $perm => $perm_info){
	$op_options[$perm] = $perm_info['title'];
	$fm_options[$perm] = array("title"=>$perm_info['title'], "item"=>$perm, "desc"=>$perm_info['desc'], "anonymous"=>true);
	if($perm == "moderate") $fm_options[$perm]["anonymous"] = false;
}
		
// Get option
$op_keys = array_keys($op_options);
$op = isset($_GET[$GLOBALS["artdirname"].'_perm_op']) ? strtolower($_GET[$GLOBALS["artdirname"].'_perm_op']) : (isset($_COOKIE[$GLOBALS["artdirname"].'_perm_op']) ? strtolower($_COOKIE[$GLOBALS["artdirname"].'_perm_op']):"");
if(empty($op)){
	$op = $op_keys[0];
	setCookie($GLOBALS["artdirname"].'_perm_op', isset($op_keys[1])?$op_keys[1]:"");
}else{
	for($i=0;$i<count($op_keys);$i++){
		if($op_keys[$i]==$op) break;
	}
	setCookie($GLOBALS["artdirname"].'_perm_op', isset($op_keys[$i+1])?$op_keys[$i+1]:"");
}

// Display option form
$opform = new XoopsSimpleForm('', 'opform', 'admin.permission.php', "get");
$op_select = new XoopsFormSelect("", 'op', $op);
$op_select->setExtra('onchange="document.forms.opform.submit()"');
$op_select->addOptionArray($op_options);
$opform->addElement($op_select);
$opform->display();

if($op=="global"){
	$form_perm = new XoopsGroupPermForm(art_constant("AM_PERMISSION_GLOBAL"), $mid, $op, art_constant("AM_PERMISSION_GLOBAL_DESC"), 'admin/admin.permission.php', $fm_options[$op]["anonymous"]);
	foreach ($GLOBALS["perms_global"] as $name=>$perm_info) {
	    $form_perm->addItem($perm_info["id"], $perm_info["title"]);
	}
}else{
	$category_handler =& xoops_getmodulehandler('category', $GLOBALS["artdirname"]);
	$categories =& $category_handler->getSubCategories();
	$form_perm = new XoopsGroupPermForm($GLOBALS["perms_category"][$op]['title'], $mid, $op, $GLOBALS["perms_category"][$op]['desc'], 'admin/admin.permission.php', $fm_options[$op]["anonymous"]);
	foreach ($categories as $cat_id => $cat) {
	    $form_perm->addItem($cat_id, $cat->getVar('cat_title'), $cat->getVar('cat_pid'));
	}
}
$form_perm->display();

// Since we can not control the permission update, a trick is used here
$permission_handler =& xoops_getmodulehandler("permission", $GLOBALS["artdirname"]);
$permission_handler->createPermData();

xoops_cp_footer();
?>