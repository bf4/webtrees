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
//include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
include_once XOOPS_ROOT_PATH."/modules/".$GLOBALS["artdirname"]."/class/xoopsformloader.php";

$start = empty($_GET['start'])?0:intval($_GET['start']);
$article_handler =& xoops_getmodulehandler('article', $GLOBALS["artdirname"]);
$spotlight_handler =& xoops_getmodulehandler('spotlight', $GLOBALS["artdirname"]);

if(!empty($_POST['submit'])){
	$spotlight_current =& $spotlight_handler->get();
	if(empty($_POST['option']) || empty($_POST["art_id"])) {
		$art_id = 0;
	}else{
		$art_id = intval($_POST["art_id"]);
	}
	if($art_id != $spotlight_current->getVar("art_id")) {
		$spotlight_obj =& $spotlight_handler->create();
		$spotlight_obj->setVar("art_id", $art_id);
	}else{
		$spotlight_obj =& $spotlight_current;
	}
	if($art_id != $spotlight_current->getVar("art_id") || $spotlight_obj->isNew()) {
		$spotlight_obj->setVar("sp_time", time());
	}
	$sp_categories = empty($_POST["sp_categories"])?array():$_POST["sp_categories"];
	$sp_note = $_POST["sp_note"];
	
	$error_upload = "";
	$sp_image_file = "";
	if (!empty($_FILES['userfile']['name'])) {
		require_once(XOOPS_ROOT_PATH."/modules/".$GLOBALS["artdirname"]."/class/uploader.php");
	    $uploader = new art_uploader(
	    	XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['path_image']
	    );
	    if ( $uploader->fetchMedia( $_POST['xoops_upload_file'][0]) ) {
	        if ( !$uploader->upload() ){
	            $error_upload = $uploader->getErrors();
	    	}elseif ( file_exists( $uploader->getSavedDestination() )){
	        	$sp_image_file = $uploader->getSavedFileName();
	        }
	    }else{
	        $error_upload = $uploader->getErrors();
	    }
	}
	$sp_image_caption = !empty($_POST["sp_image_caption"])? $_POST["sp_image_caption"]:"";
	$sp_image_caption_strip = $myts->htmlSpecialChars($myts->stripSlashesGPC($sp_image_caption));
	$sp_image["caption"] = $sp_image_caption;
	if($sp_image_file){
		$sp_image["file"] = $sp_image_file;
		$spotlight_obj->setVar("sp_image", $sp_image);
	}else{
		$sp_image["file"] = empty($_POST['sp_image_file'])?"":$_POST['sp_image_file'];
		$image = $spotlight_obj->getVar("sp_image");
		if($image["file"]!=$sp_image["file"] || $sp_image_caption_strip!=$sp_image["caption"]){
			$spotlight_obj->setVar("sp_image", $sp_image);
		}
	}
	
	if($xoopsUser->getVar("uid") != $spotlight_obj->getVar("uid")) {
		$spotlight_obj->setVar("uid", $xoopsUser->getVar("uid"));
	}
	if(serialize($sp_categories) != serialize($spotlight_obj->getVar("sp_categories"))) {
		$spotlight_obj->setVar("sp_categories", $sp_categories);
	}
	if($sp_note != $spotlight_obj->getVar("sp_note")) {
		$spotlight_obj->setVar("sp_note", $sp_note);
	}	
	if($sp_image != $spotlight_obj->getVar("sp_image")) {
		$spotlight_obj->setVar("sp_image", $sp_image);
	}
	
	if($res = $spotlight_handler->insert($spotlight_obj)){
		if ($art_id >0 && !empty($xoopsModuleConfig['notification_enabled'])) {
			$notification_handler =& xoops_gethandler('notification');
			$tags = array();
			$article_obj =& $article_handler->get($spotlight_obj->getVar("art_id"));
			$tags['ARTICLE_TITLE'] = $article_obj->getVar("art_title");
			$tags['ARTICLE_URL'] = XOOPS_URL . '/modules/' . $GLOBALS["artdirname"] . '/view.article.php'.URL_DELIMITER.'' .$art_id;
			$tags['ARTICLE_ACTION'] = art_constant("MD_NOT_ACTION_PUBLISHED");
			$notification_handler->triggerEvent('global', 0, 'article_monitor', $tags);
			$notification_handler->triggerEvent('article', $art_id, 'article_monitor', $tags);
		}
	}
    redirect_header( "admin.spotlight.php", 1 , art_constant("AM_DBUPDATED") );
}

xoops_cp_header();
loadModuleAdminMenu(6);

$spotlight_obj =& $spotlight_handler->get();

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . art_constant("AM_SPOTLIGHT") . "</legend>";
echo "<div style='padding: 8px;'>";

$form_sp = new XoopsThemeForm(art_constant("AM_SPOTLIGHT"), "formsp", xoops_getenv('PHP_SELF'));
$form_sp->setExtra('enctype="multipart/form-data"');

// Current Spotlight
if($spotlight_obj->getVar("art_id")){
	$option = 1;
	$article_obj =& $article_handler->get($spotlight_obj->getVar("art_id"));
	$message = "<a href='".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/view.article.php".URL_DELIMITER."".$spotlight_obj->getVar("art_id")."' target='_blank' title='".$article_obj->getSummary(true)."'>".$article_obj->getVar('art_title')."</a>";
}else{
	$option = 0;
	$message = art_constant("AM_SPOTLIGHT_LASTARTICLE");
}
if(!$spotlight_obj->isNew()){
	$uid = $spotlight_obj->getVar("uid");
	if($xoopsUser->getVar("uid") == $uid) {
		$uname = $xoopsUser->getVar("uname");
	}else{
		$uname = XoopsUser::getUnameFromId($uid);
	}
	$form_sp->addElement(new XoopsFormLabel(art_constant("AM_SPOTLIGHT_CURRENT"), "<a href=\"".XOOPS_URL."/userinfo.php?uid=".$uid."\">".$uname."</a> - ".$spotlight_obj->getTime().": ".$message));
}

// Image
if(!empty($xoopsModuleConfig['path_file'])){
	$sp_image = $spotlight_obj->getImage();

	$image_option_tray = new XoopsFormElementTray(art_constant("AM_IMAGE_UPLOAD"), '<br />');
	$image_option_tray->addElement(new XoopsFormFile('', 'userfile',''));
	$form_sp->addElement($image_option_tray);
	$form_sp->addElement(new XoopsFormText(art_constant("AM_IMAGE_CAPTION"), "sp_image_caption", 50, 255, @$sp_image["caption"]));
	unset($image_tray);
	unset($image_option_tray);

	$image_option_tray = new XoopsFormElementTray(art_constant("AM_IMAGE_SELECT"), "<br />");
	$image_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . "/". $xoopsModuleConfig["path_image"]."/");
	array_unshift($image_array, _NONE);
	$image_select = new XoopsFormSelect("", "sp_image_file", @$sp_image["file"]);
	$image_select->addOptionArray($image_array);
	$image_select->setExtra("onchange=\"showImgSelected('img', 'sp_image_file', '/".$xoopsModuleConfig["path_image"]."/', '', '" . XOOPS_URL . "')\"");
	$image_tray = new XoopsFormElementTray("", "&nbsp;<br />");
	$image_tray->addElement($image_select);
	if (!empty($sp_image["url"])){
	    $label = "<div style=\"padding: 8px;\"><img src=\"" . $sp_image["url"] . "\" name=\"img\" id=\"img\" alt=\"\" /></div>".(empty($sp_image["caption"])?"":"<br />".$sp_image["caption"]);
	}else{
	    $label = "<div style=\"padding: 8px;\"><img src=\"" . XOOPS_URL . "/images/blank.gif\" name=\"img\" id=\"img\" alt=\"\" /></div>";
	}
	$image_tray->addElement(new XoopsFormLabel(art_constant("AM_IMAGE_CURRENT"), $label));
	$image_option_tray->addElement($image_tray);
	$form_sp->addElement($image_option_tray);
}

// Options
$spot_options = array(0=>art_constant("AM_SPOTLIGHT_LASTARTICLE"), 1=>art_constant("AM_SPOTLIGHT_SPECIFIED"));
$option_select = new XoopsFormSelect(art_constant("AM_SPOTLIGHT_OPTION"), 'option', $option);
$option_select->addOptionArray($spot_options);
$form_sp->addElement($option_select);

// CATEGORIES
$category_handler =& xoops_getmodulehandler('category', $GLOBALS["artdirname"]);
$categories =& $category_handler->getTree(0, "all");
$cat_option = array();
foreach($categories as $id=>$cat){
	$cat_option[$id] = $cat['prefix'].$cat['cat_title'];
}
$cat_select = new XoopsFormSelect(art_constant("AM_SPOTLIGHT_CATEGORIES"), 'sp_categories', $spotlight_obj->getVar("sp_categories"), 5, true);
$cat_select->addOptionArray($cat_option);
$form_sp->addElement($cat_select);

// Editor's Note
$form_sp->addElement(new XoopsFormTextArea(art_constant("AM_SPOTLIGHT_NOTE"), "sp_note", $spotlight_obj->getVar("sp_note")));

$criteria = new CriteriaCompo(new Criteria('art_time_publish', 0, ">"));
if(count($spotlight_obj->getVar("sp_categories"))>0) {
	$criteria->add(new Criteria('cat_id', '('.implode(',',$spotlight_obj->getVar("sp_categories")).')', '>'), "AND");
}
$articles_count = $article_handler->getCount($criteria);
if($articles_count>0){
	$tags = array("art_title","cat_id");
	$articles_obj =& $article_handler->getByLimit(0, $xoopsModuleConfig['articles_perpage'], $start, $criteria, $tags);
    foreach( $articles_obj as $id => $article ){
	    $cat_id[]=$article->getVar('cat_id');
    }
	$criteria = new Criteria("cat_id", "(".implode(",",$cat_id).")", "IN");
	$categories = $category_handler->getList($criteria);

    $heading = array( "ID", art_constant("AM_TITLE"), art_constant("AM_CATEGORY"), art_constant("AM_SPOTIT") );
    $article_table = "\n<tr><td colspan='2'>";
    $article_table .= "\n<table border='0' width='100%' cellpadding ='2' cellspacing='1' class = 'outer'>";
    $article_table .= "\n<tr >";
    for ( $i = 0; $i < count( $heading ); $i++ ) {
        $article_table .= "<th align='center'><b>" . $heading[$i] . "</th>";
    }
    $article_table .= "</tr>";

    foreach( $articles_obj as $id => $article )
    {
        $article_table .= "\n<tr>";
        $article_table .= "<td align='center' class = 'head'>" . $id . "";
        $article_table .= "</td><td align='left' class = 'even'>" . $article->getVar('art_title');
        $article_table .= "</td><td align='left' class = 'even'>" . $categories[$article->getVar('cat_id')];
        $article_table .= "</td>";

        if( $spotlight_obj->getVar("art_id") == $id ) {
	        $selected = "checked";
        }else{
	        $selected = "";
        }
        $article_table .= "</td><td align='center' class='even'><input type='radio' name='art_id' value=" . $id . " $selected>" . "";
        $article_table .= "</td></tr>";
    }
    $article_table .= "\n</table><br />\n";
    $article_table .= "\n</tr></td>\n";
	if ( $articles_count > $xoopsModuleConfig['articles_perpage']) {
		include(XOOPS_ROOT_PATH.'/class/pagenav.php');
		$nav = new XoopsPageNav($articles_count, $xoopsModuleConfig['articles_perpage'], $start, "start");
    	$article_table .= "\n<br />".$nav->renderNav(4);
	}
    $form_sp->addElement( $article_table );
}

$button_tray = new XoopsFormElementTray('', '');
$button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
$button_tray->addElement(new XoopsFormButton('', '', _CANCEL, 'button'));
$form_sp->addElement($button_tray);

$form_sp->display();

echo "</div>";
echo "</fieldset><br />";

xoops_cp_footer();
?>