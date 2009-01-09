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

include("../../../include/cp_header.php");
require XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/include/vars.php";
require_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/include/functions.php");
include_once(XOOPS_ROOT_PATH."/Frameworks/art/functions.php");

// include the default language file for the admin interface
if ( !@include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/language/" . $xoopsConfig['language'] . "/main.php")){
    include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/language/english/main.php");
}

$myts =& MyTextSanitizer::getInstance();
?>