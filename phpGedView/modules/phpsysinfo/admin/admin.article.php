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
include("header.php");
header("location: ".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/cp.article.php?from=1");
exit();

xoops_cp_header();
loadModuleAdminMenu(3);

$category_handler =& xoops_getmodulehandler("category", $GLOBALS["artdirname"]);
$article_count =& $category_handler->getArticleCountRegistered();

echo "<fieldset><legend style=\"font-weight: bold; color: #900;\">" . art_constant("AM_ARTICLES") . "</legend>";
echo "<div style=\"padding: 8px;\">";
if($article_count>0){
	echo "<br clear=\"all\" /><a style=\"border: 1px solid #5E5D63; padding: 4px 8px;\" href=\"".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/cp.article.php?type=submitted&amp;from=1\">" . art_constant("AM_APPROVEARTICLE")." (<font color=\"red\">".$article_count."</font>)</a>";
	echo "<br clear=\"all\" />";
}
echo "<br clear=\"all\" /><a style=\"border: 1px solid #5E5D63; padding: 4px 8px;\" href=\"".XOOPS_URL."/modules/".$GLOBALS["artdirname"]."/cp.article.php?from=1\">" . art_constant("AM_CPARTICLE");
echo "</a>";
echo "</div>";
echo "</fieldset><br clear=\"all\" />";

xoops_cp_footer();
?>