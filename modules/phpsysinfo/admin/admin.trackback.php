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
header("location: ".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/cp.trackback.php?from=1");
exit();

xoops_cp_header();
loadModuleAdminMenu(7);

$trackback_handler =& xoops_getmodulehandler('trackback', $GLOBALS["artdirname"]);
$criteria = new Criteria('tb_status', 0);
$trackback_count = $trackback_handler->getCount($criteria);

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . art_constant("AM_TRACKBACKS") . "</legend>";
echo "<div style='padding: 8px;'>";
if($trackback_count>0){
	echo "<br /><a style=\"border: 1px solid #5E5D63; padding: 4px 8px;\" href=\"".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/cp.trackback.php?type=pending&amp;from=1\">" . art_constant("AM_CPTRACKBACK");
	echo "(<font color=\"red\">".$trackback_count."</font>)";
}else
echo "<br /><a style=\"border: 1px solid #5E5D63; padding: 4px 8px;\" href=\"".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/cp.trackback.php?from=1\">" . art_constant("AM_CPTRACKBACK");
echo "</a>";
echo "</div>";
echo "</fieldset><br />";

xoops_cp_footer();
?>