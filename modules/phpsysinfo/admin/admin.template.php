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
include_once( XOOPS_ROOT_PATH.'/class/xoopsblock.php' ) ;

xoops_cp_header();
loadModuleAdminMenu(6);

if(is_readable(XOOPS_ROOT_PATH . "/modules/system/language/".$xoopsConfig['language']."/admin/tplsets.php")){
	include_once( XOOPS_ROOT_PATH . "/modules/system/language/".$xoopsConfig['language']."/admin/tplsets.php" );
}else{
	include_once( XOOPS_ROOT_PATH . "/modules/system/language/english/admin/tplsets.php" );
}

// $art_page_list = array( "page1"=>array('tpl1'=>"name1", 'tpl2'=>'name2', '...'), "page2"=>array('tpl1'=>'name1', 'tpl3'=>'name3', '...'), '...');
$art_page_list =& art_getTplPageList();
$tplset_handler =& xoops_gethandler('tplset');
$tplsets =& $tplset_handler->getObjects();

$installed_tpl = array();
$tpltpl_handler =& xoops_gethandler('tplfile');
for($iTpl=0;$iTpl<count($tplsets);$iTpl++){
	$templates[] =& $tpltpl_handler->find($tplsets[$iTpl]->getVar('tplset_name'), 'module', $xoopsModule->mid());
}

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _MD_TPLMAIN . "</legend>";
echo "<div style='padding: 8px;'>";

foreach($art_page_list as $pagename => $file_list){
	$files = array_values($file_list);
	echo '<table width="100%" class="outer" cellspacing="1"><tr><th >'.art_constant("AM_PAGENAME").'</th>';
	for($iTpl=0;$iTpl<count($tplsets);$iTpl++){
		echo '<th>'.$tplsets[$iTpl]->getVar('tplset_name').'</th>';
	}
	echo '</tr>';
	for($iFile=0;$iFile<count($files);$iFile++){
		echo  '<tr><td class="head">'.$pagename.'</td>';
		for($iTpl=0;$iTpl<count($tplsets);$iTpl++){
			$tpl_id = 0;
			for($i=0;$i<count($templates[$iTpl]);$i++){
				if( $templates[$iTpl][$i]->getVar('tpl_file') == $files[$iFile] ) $tpl_id =$templates[$iTpl][$i]->getVar('tpl_id');
			}
			if ($tplsets[$iTpl]->getVar('tplset_name') != 'default') {
				if($tpl_id==0){
					echo '<td>[<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&amp;moddir='.$xoopsModule->getVar('dirname').'&amp;tplset='.$tplsets[$iTpl]->getVar('tplset_name').'&amp;op=generatetpl&amp;type=module&amp;file='.urlencode($files[$iFile]).'" target="template"><font color="red">'._MD_GENERATE.'</font></a>]</td>';
				}else{
					echo '<td>[<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id='.$tpl_id.'" target="template">'._EDIT.'</a>] [<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&amp;op=deletetpl&amp;id='.$tpl_id.'" target="template">'._DELETE.'</a>] [<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&amp;op=downloadtpl&amp;id='.$tpl_id.'" target="template">'._MD_DOWNLOAD.'</a>]</td>';
				}
			} else {
				if($tpl_id==0){
					echo '<td>[<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=modulesadmin&amp;op=update&amp;module='.$xoopsModule->getVar('name').'" target="template"><font color="red">'._MD_GENERATE.'</font></a>]</td>';
				}else{
					echo '<td>[<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id='.$tpl_id.'" target="template">'._MD_VIEW.'</a>] [<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&amp;op=downloadtpl&amp;id='.$tpl_id.'" target="template">'._MD_DOWNLOAD.'</a>]</td>';
				}
			}
		}
		echo '</tr>';
	}
	echo '</table>';
}

echo "</div>";
echo "</fieldset><br />";

xoops_cp_footer();
?>