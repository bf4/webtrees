<?php
/**
 * Chinese texts
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
 *
 * @author PGV Developers
 * @package PhpGedView
 * @subpackage Languages
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

$pgv_lang["full_name"]			= "全名";
$pgv_lang["error_header"] 		= "GEDCOM 文件, [#GEDCOM#], 不存在在被指定的地點。";
$pgv_lang["manage_gedcoms"]		= "處理GEDCOMs";
$pgv_lang["add_gedcom"]			= "添加其它GEDCOM";
$pgv_lang["admin_approved"]		= "Your account at #SERVER_NAME# has been approved";
$pgv_lang["administration"]		= "管理";
$pgv_lang["ansi_to_utf8"]		= "轉換這個ANSI (ISO-8859-1) 編碼GEDCOM 成UTF-8?";
$pgv_lang["can_admin"]			= "罐頭管理";
$pgv_lang["can_edit"]			= "能編輯";
$pgv_lang["click_here_to_go_to_pedigree_tree"]	= "點擊這裡去家譜結構樹。";
$pgv_lang["configuration"]		= "配置";
$pgv_lang["confirm_user_delete"]	= "是否確實要刪除用戶";
$pgv_lang["create_user"]		= "創造用戶";
$pgv_lang["dataset_exists"]		= "GEDCOM 以這個文件名已經被導入入資料庫。";
$pgv_lang["download_gedcom"]		= "下載GEDCOM";
$pgv_lang["empty_dataset"]		= "您想要倒空資料集嗎??";
$pgv_lang["found_record"]		= "被查找的記錄";
$pgv_lang["ged_import"]			= "導入GEDCOM";
$pgv_lang["gedcom_file"]		= "GEDCOM 文件:";
$pgv_lang["import_complete"]		= "導入完全";
$pgv_lang["please_be_patient"]		= "請是耐心";
$pgv_lang["reading_file"]		= "讀取GEDCOM 文件";
$pgv_lang["readme_documentation"]	= "README 文獻";
$pgv_lang["rootid"]			= "家譜圖根人";
$pgv_lang["select_an_option"]		= "選擇一個選擇如下::";
$pgv_lang["show_phpinfo"]		= "顯示PHPInfo";
$pgv_lang["update_user"]		= "更新用戶";
$pgv_lang["upload_gedcom"]		= "向上作用的負載GEDCOM";
$pgv_lang["user_create_error"]		= "無法增加用戶。  請回去和再嘗試。";
$pgv_lang["user_created"]		= "用戶成功地被創造。";
$pgv_lang["verified"]			= "User verified himself:";
$pgv_lang["verified_by_admin"]		= "User Approved by Admin:";
$pgv_lang["you_may_login"]		= " by the site administrator.  You may now login to the PhpGedView Site by going to the link below:";


?>
