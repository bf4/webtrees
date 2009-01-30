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
header("Location: ".XOOPS_URL."/modules/system/admin.php?fct=blocksadmin&selmod=".$xoopsModule->getVar("mid"));

include_once( XOOPS_ROOT_PATH.'/class/xoopsblock.php' ) ;
require_once(XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php');

xoops_cp_header();
loadModuleAdminMenu(5);

//@include_once( XOOPS_ROOT_PATH . "/modules/system/constants.php" ) ;
if(is_readable(XOOPS_ROOT_PATH . "/modules/system/language/".$xoopsConfig['language']."/admin.php")){
	include_once( XOOPS_ROOT_PATH . "/modules/system/language/".$xoopsConfig['language']."/admin.php" );
}else{
	include_once( XOOPS_ROOT_PATH . "/modules/system/language/english/admin.php" );
}
if(is_readable(XOOPS_ROOT_PATH . "/modules/system/language/".$xoopsConfig['language']."/admin/blocksadmin.php")){
	include_once( XOOPS_ROOT_PATH . "/modules/system/language/".$xoopsConfig['language']."/admin/blocksadmin.php" );
}else{
	include_once( XOOPS_ROOT_PATH . "/modules/system/language/english/admin/blocksadmin.php" );
}

// get blocks owned by the module
$block_arr =& XoopsBlock::getByModule( $xoopsModule->mid() ) ;
if( ! empty( $block_arr ) ) {
	// cachetime options
	$cachetimes = array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH);

	echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_BADMIN . "</legend>";
	echo "<div style='padding: 8px;'>";

	if(!empty($xoopsModuleConfig['do_spotlight'])) {
		echo "<br /><a style=\"border: 1px solid #5E5D63; padding: 4px 8px;\" href=\"".XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/admin.block.spotlight.php\">" . art_constant("AM_SPOTLIGHT") ."</a>";
		echo "<br />";
	}

	// displaying TH
	echo "
	<form action='action.block.php' name='blockadmin' method='post'>
		<table width='90%' class='outer' cellpadding='4' cellspacing='1'>
		<tr valign='middle'>
			<th>"._AM_TITLE."</th>
			<th align='center' nowrap='nowrap'>"._AM_SIDE."</th>
			<th align='center'>"._AM_WEIGHT."</th>
			<th align='center'>"._AM_VISIBLEIN."</th>
			<th align='center'>"._AM_BCACHETIME."</th>
			<th align='right'>"._AM_ACTION."</th>
		</tr>\n" ;

	// blocks displaying loop
	include XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/xoops_version.php" ;
	$block_configs = $modversion['blocks'] ;
	$class = 'even' ;
	foreach( array_keys( $block_arr ) as $i ) {
		$sseln = $ssel0 = $ssel1 = $ssel2 = $ssel3 = $ssel4 = "";

		$weight = $block_arr[$i]->getVar("weight") ;
		$title = $block_arr[$i]->getVar("title") ;
		$name = $block_arr[$i]->getVar("name") ;
		$bcachetime = $block_arr[$i]->getVar("bcachetime") ;
		$bid = $block_arr[$i]->getVar("bid") ;

		// visible and side
		if ( $block_arr[$i]->getVar("visible") != 1 ) {
			$sseln = " checked='checked' style='background-color:#FF0000;'";
		} else switch( $block_arr[$i]->getVar("side") ) {
			default :
			case XOOPS_SIDEBLOCK_LEFT :
				$ssel0 = " checked='checked' style='background-color:#00FF00;'";
				break ;
			case XOOPS_SIDEBLOCK_RIGHT :
				$ssel1 = " checked='checked' style='background-color:#00FF00;'";
				break ;
			case XOOPS_CENTERBLOCK_LEFT :
				$ssel2 = " checked='checked' style='background-color:#00FF00;'";
				break ;
			case XOOPS_CENTERBLOCK_RIGHT :
				$ssel4 = " checked='checked' style='background-color:#00FF00;'";
				break ;
			case XOOPS_CENTERBLOCK_CENTER :
				$ssel3 = " checked='checked' style='background-color:#00FF00;'";
				break ;
		}

		// bcachetime
		$cachetime_options = '' ;
		foreach( $cachetimes as $cachetime => $cachetime_name ) {
			if( $bcachetime == $cachetime ) {
				$cachetime_options .= "<option value='$cachetime' selected='selected'>$cachetime_name</option>\n" ;
			} else {
				$cachetime_options .= "<option value='$cachetime'>$cachetime_name</option>\n" ;
			}
		}

		// target modules
		$db =& Database::getInstance();
		$result = $db->query( "SELECT module_id FROM ".$db->prefix('block_module_link')." WHERE block_id='$bid'" ) ;
		$selected_mids = array();
		while ( list( $selected_mid ) = $db->fetchRow( $result ) ) {
			$selected_mids[] = intval( $selected_mid ) ;
		}
		$module_handler =& xoops_gethandler('module');
		$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
		$criteria->add(new Criteria('isactive', 1));
		$module_list =& $module_handler->getList($criteria);
		$module_list[-1] = _AM_TOPPAGE;
		$module_list[0] = _AM_ALLPAGES;
		ksort($module_list);
		$module_options = '' ;
		foreach( $module_list as $mid => $mname ) {
			if( in_array( $mid , $selected_mids ) ) {
				$module_options .= "<option value='$mid' selected='selected'>$mname</option>\n" ;
			} else {
				$module_options .= "<option value='$mid'>$mname</option>\n" ;
			}
		}

		// delete link if it is cloned block
		if( $block_arr[$i]->getVar("block_type") == 'D' ) {
			$delete_link = "<br />\n<a href='action.block.php?op=delete&amp;bid=$bid'>"._DELETE."</a>" ;
		} else {
			$delete_link = '' ;
		}

		// clone link if it is marked as cloneable block
		// $modversion['blocks'][n]['can_clone']
		if( $block_arr[$i]->getVar("block_type") == 'D' ) {
			$can_clone = true ;
		} else {
			$can_clone = false ;
			foreach( $block_configs as $bconf ) {
				if( $block_arr[$i]->getVar("show_func") == $bconf['show_func'] && $block_arr[$i]->getVar("func_file") == $bconf['file'] && $block_arr[$i]->getVar("template") == $bconf['template'] ) {
					if( ! empty( $bconf['can_clone'] ) ) $can_clone = true ;
				}
			}
		}
		if( $can_clone ) {
			$clone_link = "<br />\n<a href='action.block.php?op=clone&amp;bid=$bid'>"._CLONE."</a>" ;
		} else {
			$clone_link = '' ;
		}

		// displaying part
		echo "
		<tr valign='middle'>
			<td class='$class'>
				$name
				<br />
				<input type='text' name='title[$bid]' value='$title' size='20' />
			</td>
			<td class='$class' align='center' nowrap='nowrap'>
				<input type='radio' name='side[$bid]' value='".XOOPS_SIDEBLOCK_LEFT."'$ssel0 />-<input type='radio' name='side[$bid]' value='".XOOPS_CENTERBLOCK_LEFT."'$ssel2 /><input type='radio' name='side[$bid]' value='".XOOPS_CENTERBLOCK_CENTER."'$ssel3 /><input type='radio' name='side[$bid]' value='".XOOPS_CENTERBLOCK_RIGHT."'$ssel4 />-<input type='radio' name='side[$bid]' value='".XOOPS_SIDEBLOCK_RIGHT."'$ssel1 />
				<br />
				<br />
				<input type='radio' name='side[$bid]' value='-1'$sseln />
				"._NONE."
			</td>
			<td class='$class' align='center'>
				<input type='text' name=weight[$bid] value='$weight' size='5' maxlength='5' style='text-align:right;' />
			</td>
			<td class='$class' align='center'>
				<select name='bmodule[$bid][]' size='5' multiple='multiple'>
					$module_options
				</select>
			</td>
			<td class='$class' align='center'>
				<select name='bcachetime[$bid]' size='1'>
					$cachetime_options
				</select>
			</td>
			<td class='$class' align='right'>
				<a href='action.block.php?op=edit&amp;bid=$bid'>"._EDIT."</a>
				$delete_link
				$clone_link
				<input type='hidden' name='bid[$bid]' value='$bid' />
			</td>
		</tr>\n" ;

		$class = ( $class == 'even' ) ? 'odd' : 'even' ;
	}

	echo "
		<tr>
			<td class='foot' align='center' colspan='6'>
				<input type='hidden' name='fct' value='blocksadmin' />
				<input type='hidden' name='op' value='order' />
				<input type='submit' name='submit' value='"._SUBMIT."' />
			</td>
		</tr>
		</table>
	</form>\n" ;

	echo "</div>";
	echo "</fieldset><br />";
}

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _MD_AM_ADGS . "</legend>";
echo "<div style='padding: 8px;'>";
$item_list = array() ;
foreach( array_keys( $block_arr ) as $i ) {
	$item_list[ $block_arr[$i]->getVar("bid") ] = $block_arr[$i]->getVar("title") ;
}
// Processed by modules/system/admin/mygroupperm.php
$form_perm = new XoopsGroupPermForm( _MD_AM_ADGS , 1 , 'block_read' , '', XOOPS_URL."/modules/".$xoopsModule->getVar("dirname")."/admin/admin.blocks.php" ) ;
foreach( $item_list as $item_id => $item_name) {
	$form_perm->addItem( $item_id , $item_name ) ;
}
echo $form_perm->render() ;
echo "</div>";
echo "</fieldset><br />";

xoops_cp_footer();
?>