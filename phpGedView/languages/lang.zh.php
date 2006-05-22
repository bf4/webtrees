<?php
/**
 * Chinese Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  PGV Development
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
 * @version $Id: functions.php,v 1.24 2005/11/22 21:14:14 yalnifj Exp $
 */
if (preg_match("/lang\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
	print "You cannot access a language file directly.";
	exit;
}
//-- GENERAL HELP MESSAGES
$pgv_lang["qm"]				= "？";
$pgv_lang["help_for_this_page"]		= "幫助使用這頁";
$pgv_lang["hide_context_help"]		= "隱藏在頁幫助";
$pgv_lang["help_not_exist"]		= "幫助文本, 為這個頁或項目, 不是可利用的";
$pgv_lang["header"]			= "標頭";
$pgv_lang["menu"]			= "菜單";
$pgv_lang["resolution"]			= "屏幕尺寸";
$pgv_lang["resolution"]			= "屏幕解決方法";
$pgv_lang["sorry"]			= "對不起, 我們未完成幫助文本為這個頁";
$pgv_lang["show_context_help"]		= "顯示在頁幫助";
$pgv_lang["help_contents"]		= "幫助目錄";
$pgv_lang["page_help"]			= "幫助";

//-- CONFIG FILE MESSAGES
$pgv_lang["error_title"]		= "錯誤: 不能打開GEDCOM 文件";
$pgv_lang["error_header"] 		= "GEDCOM 文件, [#GEDCOM#], 不存在在被指定的地點。";
$pgv_lang["for_support"]		= "為技術支持和資訊聯絡";
$pgv_lang["for_contact"]		= "為幫助以譜學問題聯絡";
$pgv_lang["for_all_contact"]		= "為技術支持或譜學問題, 請聯絡";
$pgv_lang["build_title"]		= "大廈索引文件";
$pgv_lang["build_error"]		= "GEDCOM 文件被更新了。";
$pgv_lang["please_wait"]		= "請等待當索引文件必須被重建。";
$pgv_lang["choose_gedcom"]		= "選擇GEDCOM 資料集";
$pgv_lang["username"]			= "帳號";
$pgv_lang["fullname"]			= "名字";
$pgv_lang["password"]			= "密碼";
$pgv_lang["confirm"]			= "證實密碼";
$pgv_lang["login"]			= "登錄";
$pgv_lang["logout"]			= "註銷";
$pgv_lang["admin"]			= "管理";
$pgv_lang["logged_in_as"]		= "登錄 ";
$pgv_lang["my_pedigree"]		= "我的家譜";
$pgv_lang["my_indi"]			= "我的單個";
$pgv_lang["yes"]			= "是";
$pgv_lang["no"]				= "不";
$pgv_lang["add_gedcom"]			= "添加其它GEDCOM";

//-- INDEX (PEDIGREE_TREE) FILE MESSAGES
$pgv_lang["index_header"]	= "家譜結構樹";
$pgv_lang["gen_ped_chart"]	= "#PEDIGREE_GENERATIONS# 生成家譜圖表";
$pgv_lang["generations"]	= "生成";
$pgv_lang["view"]		= "視圖";
$pgv_lang["fam_spouse"]		= "系列與配偶:";
$pgv_lang["root_person"]	= "根人員身份證:";
$pgv_lang["hide_details"]	= "隱藏詳細資料";
$pgv_lang["show_details"]	= "顯示詳細資料";
$pgv_lang["person_links"]	= "圖表的連結, 系列, 並且這個人員的近親。";
$pgv_lang["zoom_box"]		= "迅速移動/在這個配件箱。";
$pgv_lang["portrait"]			= "高圖表";
$pgv_lang["start_at_parents"]		= "開始在爸爸媽媽";
$pgv_lang["welcome_page"]		= "新聞和資訊頁";
$pgv_lang["lists"]			= "列表";
$pgv_lang["charts"]			= "圖表";
$pgv_lang["landscape"]			= "寬圖表";

//-- FUNCTIONS FILE MESSAGES
$pgv_lang["unable_to_find_family"]	= "無法找到系列";
$pgv_lang["unable_to_find_indi"]	= "無法找到單個";
$pgv_lang["unable_to_find_record"]	= "無法查找記錄";
$pgv_lang["unable_to_find_source"]	= "無法查找來源";
$pgv_lang["unable_to_find_repo"]	= "無法查找程式庫";
$pgv_lang["repo_name"]			= "程式庫名字:";
$pgv_lang["address"]			= "地址:";
$pgv_lang["phone"]			= "電話:";
$pgv_lang["source_name"]		= "來源名字:";
$pgv_lang["title"]			= "稱謂:";
$pgv_lang["author"]			= "作者:";
$pgv_lang["publication"]		= "發行:";
$pgv_lang["call_number"]		= "索書號:";
$pgv_lang["living"]			= "居住";
$pgv_lang["private"]			= "專用";
$pgv_lang["birth"]			= "誕生:";
$pgv_lang["death"]			= "死亡:";
$pgv_lang["descend_chart"]		= "子項圖表";
$pgv_lang["individual_list"]		= "單獨列表";
$pgv_lang["family_list"]		= "系列列表";
$pgv_lang["source_list"]		= "來源列表";
$pgv_lang["place_list"]			= "安置層次結構";
$pgv_lang["media_list"]			= "多媒體列表";
$pgv_lang["search"]			= "搜索";
$pgv_lang["clippings_cart"]		= "系族樹剪報購物車";
$pgv_lang["not_an_array"]		= "不是列陣";
$pgv_lang["print_preview"]		= "列印預覽";
$pgv_lang["change_lang"]		= "更改語言";
$pgv_lang["print"]			= "列印";
$pgv_lang["total_queries"]		= "總資料庫查詢: ";
$pgv_lang["back"]			= "返回";

//-- INDIVUDUAL FILE MESSAGES
$pgv_lang["male"]			= "馬律";
$pgv_lang["female"]			= "女性";
$pgv_lang["temple"]			= "LDS 寺廟";
$pgv_lang["temple_code"]		= "LDS 寺廟編碼:";
$pgv_lang["status"]			= "狀態";
$pgv_lang["source"]			= "來源:";
$pgv_lang["citation"]			= "引證:";
$pgv_lang["text"]			= "源文本:";
$pgv_lang["note"]			= "注:";
$pgv_lang["PN"]				= "(未知)";
$pgv_lang["NN"]				= "(未知)";
$pgv_lang["unrecognized_code"]		= "未被認出的GEDCOM 編碼";
$pgv_lang["indi_info"]			= "單獨資訊";
$pgv_lang["pedigree_chart"]		= "家譜圖表";
$pgv_lang["desc_chart2"]		= "後裔圖表";
$pgv_lang["family"]			= "家";
$pgv_lang["as_spouse"]			= "系列與配偶";
$pgv_lang["as_child"]			= "系列以父項";
$pgv_lang["view_gedcom"]		= "圖GEDCOM 記錄";
$pgv_lang["add_to_cart"]		= "添加來剪報購物車";
$pgv_lang["still_living_error"]		= "這個人員居住或仍然不安排誕生或死亡日期被記錄。  生存人員所有詳細資料被隱藏從公共視圖。<br />為更多資訊聯絡";
$pgv_lang["privacy_error"]		= "詳細資料在這個人員專用。<br />";
$pgv_lang["more_information"]		= "為更多資訊聯絡";
$pgv_lang["name"]			= "名字:";
$pgv_lang["given_name"]			= "指定的名字:";
$pgv_lang["surname"]			= "姓氏:";
$pgv_lang["suffix"]			= "後綴:";
$pgv_lang["object_note"]		= "對象附註:";
$pgv_lang["sex"]			= "性別";
$pgv_lang["personal_facts"]		= "私有情況和詳細資料";
$pgv_lang["type"]			= "型";
$pgv_lang["date"]			= "日期";
$pgv_lang["place_description"]		= "安排/說明";
$pgv_lang["parents"] 			= "父項:";
$pgv_lang["siblings"] 			= "兄弟";
$pgv_lang["father"] 			= "父親";
$pgv_lang["mother"] 			= "母親";
$pgv_lang["relatives"]			= "近親";
$pgv_lang["child"]			= "子項";
$pgv_lang["spouse"]			= "配偶";
$pgv_lang["surnames"]			= "姓氏";
$pgv_lang["adopted"]			= "採取";
$pgv_lang["foster"]			= "養育";
$pgv_lang["sealing"]			= "海豹捕獵";
$pgv_lang["link_as"]			= "與一個現有的系列鏈接這個人員作為 ";

//-- FAMILY FILE MESSAGES
$pgv_lang["family_info"]		= "系列資訊";
$pgv_lang["family_group_info"]		= "系列組資訊";
$pgv_lang["husband"]			= "丈夫";
$pgv_lang["wife"]			= "太太";
$pgv_lang["marriage"]			= "婚姻:";
$pgv_lang["lds_sealing"]		= "LDS 海豹捕獵:";
$pgv_lang["marriage_license"]		= "結婚證書:";
$pgv_lang["media_object"]		= "多媒體對象:";
$pgv_lang["children"]			= "孩子";
$pgv_lang["no_children"]		= "沒有孩子";
$pgv_lang["parents_timeline"]		= "顯示父項在<br />時間安排圖表";

//-- CLIPPINGS FILE MESSAGES
$pgv_lang["clip_cart"]			= "剪報購物車";
$pgv_lang["clip_explaination"]		= "系族樹剪報購物車允許您對採取剪報從這個系族樹和包他們入一個唯一GEDCOM 文件為下載。<br /><br />";
$pgv_lang["item_with_id"]		= "項目與";
$pgv_lang["error_already"]		= "已經是在您的剪報購物車裡。";
$pgv_lang["which_links"]		= "從這個系列您並且會希望哪個連結添加?";
$pgv_lang["just_family"]		= "添加這個系列記錄。";
$pgv_lang["parents_and_family"]		= "添加父項以這個系列記錄。";
$pgv_lang["parents_and_child"]		= "添加父項和兒童記錄以這個系列記錄。";
$pgv_lang["parents_desc"]		= "添加父項和所有後裔記錄以這個系列記錄。";
$pgv_lang["continue"]			= "持續添加";
$pgv_lang["which_p_links"]		= "從這個人員您並且會希望哪個連結添加?";
$pgv_lang["just_person"]		= "添加這個人員。";
$pgv_lang["person_parents_sibs"]	= "添加這個人員, 他的父項, 並且兄弟。";
$pgv_lang["person_ancestors"]		= "添加這個人員和他的直線祖先。";
$pgv_lang["person_ancestor_fams"]	="添加這個人員, 他的直線祖先, 並且他們的系列。";
$pgv_lang["person_spouse"]		= "添加這個人員, 他的配偶, 並且子項。";
$pgv_lang["person_desc"]		= "添加這個人員, 他的配偶, 並且所有後裔記錄。";
$pgv_lang["unable_to_open"]		= "無法打開剪報文件夾為文字";
$pgv_lang["person_living"]		= "這個人員是生存。  私有詳細資料不會是包括的。";
$pgv_lang["person_private"]		= "關於這個人員的詳細資料專用。  私有詳細資料不會是包括的。";
$pgv_lang["download"]			= "用滑鼠右鍵單擊(控制點擊在Mac) 在連結如下和選擇\"之外目標和\" 下載文件。";
$pgv_lang["media_files"]		= "媒體文件參考在這GEDCOM";
$pgv_lang["cart_is_empty"]		= "您的結構樹剪報購物車是空。.";
$pgv_lang["id"]				= "身份證";
$pgv_lang["name_description"]		= "名字/說明";
$pgv_lang["remove"]			= "去除";
$pgv_lang["empty_cart"]			= "倒空購物車";
$pgv_lang["download_now"]		= "下載現在";

//-- PLACELIST FILE MESSAGES
$pgv_lang["connections"]		= "安排連接數現在查找了<br /> 視圖結果";
$pgv_lang["top_level"]			= "最高級";
$pgv_lang["form"]			= "安排被輸入以形式: ";
$pgv_lang["default_form"]		= "城市, 縣, 州/省, 國家（地區）";
$pgv_lang["unknown"]			= "未知";

//-- MEDIALIST FILE MESSAGES
$pgv_lang["multi_title"]		= "多媒體對象列表";
$pgv_lang["media_found"]		= "媒體對象查找了";
$pgv_lang["view_person"]		= "視圖人員";
$pgv_lang["view_family"]		= "視圖系列";
$pgv_lang["view_source"]		= "視圖來源";
$pgv_lang["prev"]			= "< 早先";
$pgv_lang["next"]			= "其次 >";
$pgv_lang["file_not_found"]		= "文件沒被查找。";

//-- SEARCH FILE MESSAGES
$pgv_lang["search_gedcom"]		= "搜索GEDCOM 文件";
$pgv_lang["enter_terms"]		= "進入搜索術語:";
$pgv_lang["soundex_search"]		= "Soundex 名字搜索:";
$pgv_lang["search_results"]		= "搜索結果";
$pgv_lang["sources"]			= "來源";
$pgv_lang["firstname_search"]		= "名字: ";
$pgv_lang["lastname_search"]		= "姓氏: ";
$pgv_lang["search_place"]		= "安排: ";
$pgv_lang["search_year"]		= "年: ";
$pgv_lang["lastname_empty"]		= "請輸入姓氏。";
$pgv_lang["no_results"]			= "結果沒有查找。";
$pgv_lang["soundex_results"]		= "或許以下soundex 符合將是有用的。";

//-- SOURCELIST FILE MESSAGES
$pgv_lang["sources_found"]		= "來源被查找";

//-- SOURCE FILE MESSAGES
$pgv_lang["source_info"]		= "來源資訊";
$pgv_lang["other_records"]		= "其他記錄那個連結對這個來源:";
$pgv_lang["people"]			= "人們";
$pgv_lang["families"]			= "系列";

//-- BUILDINDEX FILE MESSAGES
$pgv_lang["building_indi"]		= "編譯的單個和系列索引";
$pgv_lang["building_index"]		= "大廈索引列表";
$pgv_lang["importing_records"]		= "導入記錄入資料庫";
$pgv_lang["detected_change"]		= "PhpGedView 檢測了一個變化在GEDCOM 文件上# #GEDCOM#.  索引文件必須現在被重建在進行之前。";
$pgv_lang["please_be_patient"]		= "請是耐心";
$pgv_lang["reading_file"]		= "讀取GEDCOM 文件";
$pgv_lang["flushing"]			= "沖洗的目錄";
$pgv_lang["found_record"]		= "被查找的記錄";
$pgv_lang["exec_time"]			= "總執行時間:";
$pgv_lang["unable_to_create_index"]	="無法創建索引文件。  確定寫作許可對索引目錄是可利用的。  權限也許被恢復一旦索引文件被寫。";
$pgv_lang["indi_complete"]		= "單獨索引文件更新完全。";
$pgv_lang["family_complete"]		= "系列索引文件更新完全。";
$pgv_lang["source_complete"]		= "來源索引文件更新完全。";
$pgv_lang["tables_exist"]		= "PhpGedView 表已經存在在資料庫";
$pgv_lang["you_may"]			= "您可以:";
$pgv_lang["drop_tables"]		= "投下當前表";
$pgv_lang["import_multiple"]		= "導入和工作以多GEDCOMs";
$pgv_lang["explain_options"]		= "如果您選擇投下表所有資料用這GEDCOM 將替換。<br />如果您選擇導入和工作以多GEDCOMs, PhpGedView 將清除被導入使用一GEDCOM 以同樣文件名的任一個資料。  這個選項允許您對存儲多個GEDCOM 資料在同樣表中和容易地切換在他們之間。";
$pgv_lang["path_to_gedcom"]		= "進入路徑對您的GEDCOM 文件:";
$pgv_lang["gedcom_title"]		= "進入描述資料在這個GEDCOM 文件的稱謂:";
$pgv_lang["dataset_exists"]		= "GEDCOM 以這個文件名已經被導入入資料庫。";
$pgv_lang["empty_dataset"]		= "您想要倒空資料集嗎??";
$pgv_lang["index_complete"]		= "索引完全";
$pgv_lang["click_here_to_go_to_pedigree_tree"]	= "點擊這裡去家譜結構樹。";
$pgv_lang["updating_is_dead"]		= "更新是死的狀態為INDI ";
$pgv_lang["import_complete"]		= "導入完全";
$pgv_lang["updating_family_names"]	= "更新姓對於FAM ";
$pgv_lang["processed_for"]		= "被處理的文件為 ";

//-- INDIVIDUAL AND FAMILYLIST FILE MESSAGES
$pgv_lang["total_fams"]			= "總家庭";
$pgv_lang["total_indis"]		= "總個體";
$pgv_lang["starts_with"]		= "開始與:";
$pgv_lang["person_list"]		= "人名單:";
$pgv_lang["paste_person"]		= "漿糊人";
$pgv_lang["notes_sources_media"]	= "筆記, 來源, 並且媒介";
$pgv_lang["name_contains"]		= "名字包含:";
$pgv_lang["filter"]			= "過濾器";
$pgv_lang["find_individual"]		= "發現單獨身份證";
$pgv_lang["skip_surnames"]		= "跳姓氏名單";
$pgv_lang["show_surnames"]		= "顯示姓氏名單";
$pgv_lang["all"]			= "所有";

//-- TIMELINE FILE MESSAGES
$pgv_lang["age"]			= "年齡";
$pgv_lang["timeline_title"]		= "PhpGedView 時間安排";
$pgv_lang["timeline_chart"]		= "時間安排圖";
$pgv_lang["remove_person"]		= "去除人";
$pgv_lang["show_age"]			= "顯示年齡標誌";
$pgv_lang["add_another"]		= "增加其它人來圖:<br />人身份證::";
$pgv_lang["find_id"]			= "發現身份證";
$pgv_lang["show"]			= "展示";
$pgv_lang["year"]			= "年:";
$pgv_lang["timeline_instructions"]	= "在最近瀏覽器您能點擊和扯拽箱子在圖。";
$pgv_lang["zoom_in"]			= "迅速移動";
$pgv_lang["zoom_out"]			= "徒升";

//-- MONTH NAMES
$pgv_lang["jan"]			= "一月";
$pgv_lang["feb"]			= "二月";
$pgv_lang["mar"]			= "三月";
$pgv_lang["apr"]			= "四月";
$pgv_lang["may"]			= "五月";
$pgv_lang["jun"]			= "六月";
$pgv_lang["jul"]			= "七月";
$pgv_lang["aug"]			= "八月";
$pgv_lang["sep"]			= "九月";
$pgv_lang["oct"]			= "十月";
$pgv_lang["nov"]			= "十一月";
$pgv_lang["dec"]			= "十二月";
$pgv_lang["abt"]			= "關於";
$pgv_lang["aft"]			= "以後";
$pgv_lang["and"]			= "並且";
$pgv_lang["bef"]			= "以前";
$pgv_lang["bet"]			= "在之間";
$pgv_lang["cal"]			= "計算";
$pgv_lang["est"]			= "估計";
$pgv_lang["from"]			= "從";
$pgv_lang["int"]			= "解釋";
$pgv_lang["to"]				= "";
$pgv_lang["cir"]			= "大約";
$pgv_lang["apx"]			= "大約";

//-- chinese numbers
$pgv_lang["0"]			= "零";
$pgv_lang["1"]			= "一";
$pgv_lang["2"]			= "二";
$pgv_lang["3"]			= "三";
$pgv_lang["4"]			= "四";
$pgv_lang["5"]			= "五";
$pgv_lang["6"]			= "六";
$pgv_lang["7"]			= "七";
$pgv_lang["8"]			= "八";
$pgv_lang["9"]			= "九";
$pgv_lang["10"]			= "十";
$pgv_lang["100"]		= "百";
$pgv_lang["1000"]		= "千";

//-- Admin File Messages
$pgv_lang["select_an_option"]		= "選擇一個選擇如下::";
$pgv_lang["readme_documentation"]	= "README 文獻";
$pgv_lang["configuration"]		= "配置";
$pgv_lang["rebuild_indexes"]		= "改建索引";
$pgv_lang["user_admin"]			= "用戶管理";
$pgv_lang["user_created"]		= "用戶成功地被創造。";
$pgv_lang["user_create_error"]		= "無法增加用戶。  請回去和再嘗試。";
$pgv_lang["password_mismatch"]		= "密碼不匹配。";
$pgv_lang["enter_username"]		= "您必須進入用戶名";
$pgv_lang["enter_fullname"]		= "您必須輸入全名。";
$pgv_lang["enter_password"]		= "您必須輸入密碼。";
$pgv_lang["confirm_password"]		= "您必須證實密碼。";
$pgv_lang["update_user"]		= "更新用戶";
$pgv_lang["save"]			= "之外";
$pgv_lang["delete"]			= "刪除";
$pgv_lang["edit"]			= "編輯";
$pgv_lang["full_name"]			= "全名";
$pgv_lang["can_admin"]			= "罐頭管理";
$pgv_lang["can_edit"]			= "能編輯";
$pgv_lang["confirm_user_delete"]	= "是否確實要刪除用戶";
$pgv_lang["create_user"]		= "創造用戶";
$pgv_lang["no_login"]			= "無法證實用戶。";
$pgv_lang["import_gedcom"]		= "進口這個GEDCOM 文件";
$pgv_lang["duplicate_username"]		= "複製用戶名。  一名用戶與那用戶名已經存在。  請回去和選擇其它用戶名。";
$pgv_lang["gedcomid"]			= "GEDCOM 記錄身份證";
$pgv_lang["enter_gedcomid"]		= "您必須進入GEDCOM 身份證。";
$pgv_lang["user_info"]			= "我的用戶資訊";
$pgv_lang["rootid"]			= "家譜圖根人";
$pgv_lang["download_gedcom"]		= "下載GEDCOM";
$pgv_lang["upload_gedcom"]		= "向上作用的負載GEDCOM";
$pgv_lang["gedcom_file"]		= "GEDCOM 文件:";
$pgv_lang["upload_error"]		= "有錯誤上裝您GEDCOM 文件。";
$pgv_lang["upload_help"]		= "選擇一個文件從您的地方電腦上裝對您的伺服器。  所有文件將被上裝對目錄:";
$pgv_lang["file_success"]		= "文件成功地被上裝";
$pgv_lang["file_too_big"]		= "被上裝的文件超出允許的大小";
$pgv_lang["file_partial"]		= "文件部份地只被上裝了, 請嘗試再";
$pgv_lang["file_missing"]		= "文件未被接受。 向上作用的負載再。";
$pgv_lang["manage_gedcoms"]		= "處理GEDCOMs";
$pgv_lang["show_phpinfo"]		= "顯示PHPInfo";
$pgv_lang["research_log"]		= "研究日誌";
$pgv_lang["administration"]		= "管理";
$pgv_lang["ansi_to_utf8"]		= "轉換這個ANSI (ISO-8859-1) 編碼GEDCOM 成UTF-8?";
$pgv_lang["utf8_to_ansi"]		= "您想要轉換這GEDCOM 從UTF-8 到ANSI (ISO-8859-1)?";

//-- Relationship chart messages
//-- Chinese Translations from--http://www.kwanfamily.info/familytitles/familytitle.html
//-- Chinese RelationShip chart-- http://www.kwanfamily.info/familytitles/familytitlejpg.html
$pgv_lang["relationship_chart"]		= "關係圖";
$pgv_lang["person1"]			= "人一個";
$pgv_lang["person2"]			= "人二";
$pgv_lang["no_link_found"]		= "�?�接從二個體�?能被發�?�。";
//--Sosa came from the lang.sv.php file it is a key value relationship pattern for parents and grandparents
//1st generation
$pgv_lang["sosa_2"]	= "父親";
$pgv_lang["sosa_3"] = "�?親";
//2nd generation
$pgv_lang["sosa_4"] = "祖父";
$pgv_lang["sosa_5"] = "祖�?";
$pgv_lang["sosa_6"] = "外父";
$pgv_lang["sosa_7"] = "外婆";
//3rd generation
$pgv_lang["sosa_8"] = "曾祖父";
$pgv_lang["sosa_9"] = "曾祖�?";
$pgv_lang["sosa_10"] = "曾祖父";
$pgv_lang["sosa_11"] = "曾祖�?";
$pgv_lang["sosa_12"] = "曾外公";
$pgv_lang["sosa_13"] = "曾外婆";
$pgv_lang["sosa_14"] = "曾外公";
$pgv_lang["sosa_15"] = "曾外婆";
//4th generation
$pgv_lang["sosa_16"] = "高祖父";
$pgv_lang["sosa_17"] = "高祖�?";
$pgv_lang["sosa_18"] = "高祖父";
$pgv_lang["sosa_19"] = "高祖�?";
$pgv_lang["sosa_20"] = "高祖父";
$pgv_lang["sosa_21"] = "高祖�?";
$pgv_lang["sosa_22"] = "高祖父";
$pgv_lang["sosa_23"] = "高祖�?";
$pgv_lang["sosa_24"] = "高外公";
$pgv_lang["sosa_25"] = "高外婆";
$pgv_lang["sosa_26"] = "高外公";
$pgv_lang["sosa_27"] = "高外婆";
$pgv_lang["sosa_28"] = "高外公";
$pgv_lang["sosa_29"] = "高外婆";
$pgv_lang["sosa_30"] = "高外公";
$pgv_lang["sosa_31"] = "高外婆";
//5th generation
$pgv_lang["sosa_32"] = "N/A";
$pgv_lang["sosa_33"] = "N/A";
$pgv_lang["sosa_34"] = "N/A";
$pgv_lang["sosa_35"] = "N/A";
$pgv_lang["sosa_36"] = "N/A";
$pgv_lang["sosa_37"] = "N/A";
$pgv_lang["sosa_38"] = "N/A";
$pgv_lang["sosa_39"] = "N/A";
$pgv_lang["sosa_40"] = "N/A";
$pgv_lang["sosa_41"] = "N/A";
$pgv_lang["sosa_42"] = "N/A";
$pgv_lang["sosa_43"] = "N/A";
$pgv_lang["sosa_44"] = "N/A";
$pgv_lang["sosa_45"] = "N/A";
$pgv_lang["sosa_46"] = "N/A";
$pgv_lang["sosa_47"] = "N/A";
$pgv_lang["sosa_48"] = "N/A";
$pgv_lang["sosa_49"] = "N/A";
$pgv_lang["sosa_50"] = "N/A";
$pgv_lang["sosa_51"] = "N/A";
$pgv_lang["sosa_52"] = "N/A";
$pgv_lang["sosa_53"] = "N/A";
$pgv_lang["sosa_54"] = "N/A";
$pgv_lang["sosa_55"] = "N/A";
$pgv_lang["sosa_56"] = "N/A";
$pgv_lang["sosa_57"] = "N/A";
$pgv_lang["sosa_58"] = "N/A";
$pgv_lang["sosa_59"] = "N/A";
$pgv_lang["sosa_60"] = "N/A";
$pgv_lang["sosa_61"] = "N/A";
$pgv_lang["sosa_62"] = "N/A";
$pgv_lang["sosa_63"] = "N/A";

//--Great-great-grandparnets Generation
$pgv_lang["paternal great great grandfather"]	= "高祖父";
$pgv_lang["paternal great great grandmother"]	= "高祖�?";
$pgv_lang["maternal great great grandfather"]	= "高外公";
$pgv_lang["maternal great great grandmother"]	= "高外婆";

//--Great-grandparents Generation
$pgv_lang["paternal great grandfather"]	= "曾祖父";
$pgv_lang["paternal great grandmother"]	= "曾祖�?";
$pgv_lang["maternal great grandfather"]	= "曾外公";
$pgv_lang["maternal great grandmother"]	= "曾外婆";

//--Grandparents Generation
$pgv_lang["paternal grandfather"]	= "祖父";
$pgv_lang["paternal grandmother"]	= "祖�?";
$pgv_lang["maternal grandfather"]	= "外父";
$pgv_lang["maternal grandmother"]	= "外婆";
$pgv_lang["paternal elder great uncle"]	= "伯祖父";
$pgv_lang["paternal elder great uncle's wife"]	= "伯祖�?";
$pgv_lang["paternal younger great uncle"]	= "�?�祖父";
$pgv_lang["paternal younger great uncle's wife"] = "�?�祖�?"; 
$pgv_lang["paternal great aunt"]	= "祖姑�?";
$pgv_lang["paternal great aunt's husband"] = "姑爺祖姑丈";

//--Parents generation
$pgv_lang["father"] = "父親";
//could not find paternal uncle translation. this is our best guess.
$pgv_lang["paternal uncle"] = "??";  
$pgv_lang["paternal older uncle"]	= "伯父";
$pgv_lang["paternal older uncle's wife"]	= "伯�?";
$pgv_lang["paternal younger uncle"]	= "�?�父";
$pgv_lang["paternal younger uncle's wife"] = "嬸�?";
$pgv_lang["paternal aunt"] = "姑�?";
$pgv_lang["paternal aunt's husband"] = "姑夫姑丈";
$pgv_lang["paternal older aunt"]	= "姑媽姑";
$pgv_lang["paternal older aunt's husband"] = "姑夫";       
$pgv_lang["paternal younger aunt"]	= "姑";
$pgv_lang["paternal younger aunt's husband"] = "姑夫";
$pgv_lang["mother"] = "�?親";
$pgv_lang["maternal uncle"]	= "舅父";
$pgv_lang["maternal uncle's wife"]	= "舅�?";
$pgv_lang["maternal elder aunt"]	= "姨�?";
$pgv_lang["maternal elder aunt's husband"] = "姨夫";
$pgv_lang["maternal younger aunt"]	= "姨";
$pgv_lang["maternal younger aunt's husband"] = "姨丈";

//--Your Generations
$pgv_lang["older brother"] = "長兄";
$pgv_lang["elder brother's wife"] = "嫂";
$pgv_lang["younger brother"]	= "幼弟";
$pgv_lang["younger brother's wife"]	= "弟婦";
$pgv_lang["elder sister"] = "長姊";
$pgv_lang["elder sister's husband"]	= "姊夫";
$pgv_lang["younger sister"]	= "幼妹";
$pgv_lang["younger sister's husband"] = "妹夫";
$pgv_lang["paternal male cousin"]	= "堂兄弟"; 
$pgv_lang["paternal elder male cousin"]	= "堂兄"; 
$pgv_lang["paternal elder male cousin's wife"]	= "堂嫂";
$pgv_lang["paternal younger male cousin"]	= "堂弟";
$pgv_lang["paternal younger male cousin's wife"] = "堂弟婦";
$pgv_lang["paternal female cousin"]	= "堂姊妹"; 
$pgv_lang["paternal elder female cousin"]	= "堂家�?";
$pgv_lang["paternal elder female cousin's husband"]	= "堂�?夫";
$pgv_lang["paternal younger female cousin"] = "堂妹"; 
$pgv_lang["paternal younger female cousin's husband"]	= "堂妹夫";
$pgv_lang["paternal male cousin"]	= "表兄弟";
$pgv_lang["maternal male cousin"]	= "表兄弟";
$pgv_lang["paternal elder male cousin"]	= "表哥";
$pgv_lang["maternal elder male cousin"]	= "表哥"; 
$pgv_lang["paternal elder male cousin's wife"]	= "表嫂";
$pgv_lang["maternal elder male cousin's wife"]	= "表嫂";
$pgv_lang["paternal younger male cousin"]	= "表弟";
$pgv_lang["paternal younger male cousin's wife"] = "表夫";
$pgv_lang["maternal younger male cousin's wife "] = "表夫";
$pgv_lang["paternal female cousin"]	= "表姊妹";
$pgv_lang["maternal female cousin"]	= "表姊妹";
$pgv_lang["paternal elder female cousin"]	= "表�?";
$pgv_lang["maternal elder female cousin"]	= "表�?";
$pgv_lang["paternal elder female cousin's husband"]	= "表�?夫";
$pgv_lang["maternal elder female cousin's husband"]	= "表�?夫";
$pgv_lang["paternal younger female cousin"]	= "表妹";
$pgv_lang["maternal younger female cousin"]	= "表妹";
$pgv_lang["paternal younger female cousin's husband"]	= "表妹夫";
$pgv_lang["maternal younger female cousin's husband"]	= "表妹夫";

//--bosa came from the lang.sv.php file it is a key value relationship pattern for parents and children
//--it is made up to follow the sosa pattern, "bosa" has no meaning that we know of.
// add this key to other language files to work with the relationship algorithm
//1st generation
$pgv_lang["bosa_2"]	= "兒(儿)�?";
$pgv_lang["bosa_3"] = "女兒(儿)";
//2nd generation
$pgv_lang["bosa_4"] = "孫�?";
$pgv_lang["bosa_5"] = "孫女";
$pgv_lang["bosa_6"] = "外孫";
$pgv_lang["bosa_7"] = "外孫女";
//3rd generation
$pgv_lang["bosa_8"] = "曾內孫仔";
$pgv_lang["bosa_9"] = "曾內孫女";
$pgv_lang["bosa_10"] = "曾內孫仔";
$pgv_lang["bosa_11"] = "曾內孫女";
$pgv_lang["bosa_12"] = "曾內孫仔";
$pgv_lang["bosa_13"] = "曾內孫女";
$pgv_lang["bosa_14"] = "曾內孫仔";
$pgv_lang["bosa_15"] = "曾內孫女";
//4th generation
$pgv_lang["bosa_16"] = "玄孫"; 
$pgv_lang["bosa_17"] = "玄孫"; 
$pgv_lang["bosa_18"] = "玄孫"; 
$pgv_lang["bosa_19"] = "玄孫"; 
$pgv_lang["bosa_20"] = "玄孫"; 
$pgv_lang["bosa_21"] = "玄孫"; 
$pgv_lang["bosa_22"] = "玄孫"; 
$pgv_lang["bosa_23"] = "玄孫"; 
$pgv_lang["bosa_24"] = "玄孫"; 
$pgv_lang["bosa_25"] = "玄孫"; 
$pgv_lang["bosa_26"] = "玄孫"; 
$pgv_lang["bosa_27"] = "玄孫"; 
$pgv_lang["bosa_28"] = "玄孫"; 
$pgv_lang["bosa_29"] = "玄孫"; 
$pgv_lang["bosa_30"] = "玄孫"; 
$pgv_lang["bosa_31"] = "玄孫"; 



//--Children's Generation
$pgv_lang["son"]	= "兒(儿)�?";
$pgv_lang["son's wife"]	= "媳婦";
$pgv_lang["daughter"]	= "女兒(儿)";
$pgv_lang["daughter's husband"] = "女婿";
$pgv_lang["nephew"] = "姪�?";
$pgv_lang["nephew's wife"] = "姪媳婦";
$pgv_lang["niece"] = "姪女";
$pgv_lang["niece's husband"]	= "姪女婿";

//--Grandchildren's Generation from detail 
$pgv_lang["son's son"]	= "孫�?";
$pgv_lang["son's son's wife"]	= "孫媳婦";
$pgv_lang["son's daughter"]	= "孫女";
$pgv_lang["son's daughter's husband"] = "孫女婿";
$pgv_lang["daugghter's son"]	= "外孫";
$pgv_lang["daughter's daughter"]	= "外孫女";
$pgv_lang["Brother's son's son "]	= "姪孫";
$pgv_lang["brother's son's daughter"] = "姪孫女";

//--Greatgrandchildrens Generation from detail of http://www.kwanfamily.info/familytitles/familytitle.html#memc
$pgv_lang["children's children's children"] = "曾孫";
$pgv_lang["children's children's son"] = "曾內孫仔";
$pgv_lang["children's children's daughter"] = "曾內孫女";

//--great-great grandchildren generation
$pgv_lang["children's children's children's children's"] = "玄孫"; 



$pgv_lang["sibling"]			= "兄弟�?妹";
$pgv_lang["follow_spouse"]		= "檢查關係由婚姻。";
$pgv_lang["timeout_error"]		= "劇本被計時在關係能被發現之前。";
$pgv_lang["son"]			= "兒子";
$pgv_lang["daughter"]			= "女兒";
$pgv_lang["brother"]			= "兄弟";
$pgv_lang["sister"]			= "姐妹";

//-- GEDCOM edit utility
$pgv_lang["check_delete"]		= "是否確實要刪除這個GEDCOM 情況嗎?";
$pgv_lang["access_denied"]		= "<b>沒有存取</b><br />您不得以進入對這種資源的。";
$pgv_lang["gedrec_deleted"]		= "GEDCOM 記錄被刪除";
$pgv_lang["changes_exist"]		= "變動做了對這GEDCOM 。";
$pgv_lang["accept_changes"]		= "接受更改";
$pgv_lang["show_changes"]		= "這個記錄被更新了。  點擊這裡顯示更改。";
$pgv_lang["review_changes"]		= "覆核GEDCOM 更改s";
$pgv_lang["undo_successful"]		= "解開成功";
$pgv_lang["undo"]			= "解開";
$pgv_lang["view_change_diff"]		= "觀看更改差額";
$pgv_lang["changes_occurred"]		= "以下更改發生對這單個:";
$pgv_lang["find_place"]			= "查找安排";
$pgv_lang["close_window"]		= "接近的視窗";
$pgv_lang["close_window_without_refresh"]	= "接近的視窗沒有重新載入";
$pgv_lang["place_contains"]		= "安排包含:";
$pgv_lang["accept_gedcom"]		= "解開更改, 點擊解開連結在它旁邊。  接受所有更改為GEDCOM, 再導入GEDCOM 文件。";
$pgv_lang["ged_import"]			= "導入GEDCOM";
$pgv_lang["now_import"]			= "現在您應該導入GEDCOM 記錄入PhpGedView 由點擊在導入連結如下。";
$pgv_lang["add_fact"]			= "添加新建情況";
$pgv_lang["add"]			= "添加";
$pgv_lang["custom_event"]		= "自定義活動";
$pgv_lang["update_successful"]		= "更新成功";
$pgv_lang["add_child"]			= "添加子項";
$pgv_lang["add_child_to_family"]	= "添加子項來這個系列";
$pgv_lang["must_provide"]		= "您必須提供 ";
$pgv_lang["delete_person"]		= "刪除這單個";
$pgv_lang["confirm_delete_person"]	= "是否確實要刪除這個人員從GEDCOM 文件嗎?";
$pgv_lang["find_media"]			= "查找媒體";
$pgv_lang["set_link"]			= "設置連結";
$pgv_lang["add_source"]			= "添加來源來情況";
$pgv_lang["add_note"]			= "添加附註來情況";
$pgv_lang["delete_source"]		= "刪除這個來源";
$pgv_lang["confirm_delete_source"]	= "是否確實要刪除這個來源從GEDCOM 文件嗎?";
$pgv_lang["add_husb"]			= "添加丈夫";
$pgv_lang["add_husb_to_family"]		= "添加一個丈夫來這個系列";
$pgv_lang["add_wife"]			= "添加妻子";
$pgv_lang["add_wife_to_family"]		= "添加一個妻子來這個系列";
$pgv_lang["find_family"]		= "找到系列";
$pgv_lang["add_new_wife"]		= "添加一個新建妻子";
$pgv_lang["add_new_husb"]		= "添加一個新建丈夫";
$pgv_lang["edit_name"]			= "編輯名字";
$pgv_lang["delete_name"]		= "刪除名字";
$pgv_lang["no_temple"]			= "沒有寺廟。 居住的法令";

//-- calendar.php messages
$pgv_lang["on_this_day"]		= "在這個日在您的歷史記錄上...";
$pgv_lang["year_anniversary"]		= "#year_var# 年週年紀念";
$pgv_lang["day"]			= "日:";
$pgv_lang["month"]			= "月份:";
$pgv_lang["anniversary_calendar"] 	= "週年紀念日曆";
$pgv_lang["sunday"]			= "星期天";
$pgv_lang["monday"]			= "星期一";
$pgv_lang["tuesday"]			= "星期二";
$pgv_lang["wednesday"]			= "星期三";
$pgv_lang["thursday"]			= "星期四";
$pgv_lang["friday"]			= "星期五";
$pgv_lang["saturday"]			= "星期六";
$pgv_lang["viewday"]			= "視圖日";
$pgv_lang["viewmonth"]			= "視圖月份";
$pgv_lang["all_people"]			= "所有人員";
$pgv_lang["living_only"]		= "生存人員";
$pgv_lang["recent_events"]		= "近期事件(< 100 年)";

//-- upload media messages
$pgv_lang["upload_media"]		= "向上作用的負載媒體文件";
$pgv_lang["media_file"]			= "媒體文件";
$pgv_lang["thumbnail"]			= "指圖";
$pgv_lang["upload_successful"]		= "向上作用的負載";

//-- user self registration module
$pgv_lang["requestpassword"]		= "Request new password";
$pgv_lang["requestaccount"]		= "Request new user account";
$pgv_lang["pls_note01"]			= "Please note: The system is case-sensitive!";
$pgv_lang["min6chars"]			= "Password has to contain at least 6 characters";
$pgv_lang["pls_note02"]			= "Please note: Passwords should contain only letters and numbers. Inclusion of other characters in your password may result in inability to access from some systems.";
$pgv_lang["pls_note03"]			= "This email address will be verified before account activation. It will not be displayed on the site. You will receive a message at this Email adress with your registration data that will forward to this address.";
$pgv_lang["emailadress"]		= "Email Address:";
$pgv_lang["pls_note04"]			= "Fields marked with * are mandatory.";
$pgv_lang["pls_note05"]			= "Pending completion of the form on this page and verification of your answers, you will be sent a confirmation email to the email address you specify on this page. Using the confirmation email, you will activate your account; if you fail to activate your account within seven days, it will be purged (you can register the account again at that point). To use this site, you will need to know your login name and password. You must specify an existing, valid email address on this page in order to receive the account confirmation email.<br /><br />If you encounter an issue in registering an account on this website, please submit a Support Request to the webmaster.";

$pgv_lang["mail01_line01"]		= "Hello #user_fullname# ...";
$pgv_lang["mail01_line02"]		= "A request was made at ( #SERVER_NAME# ) to login with your Email address ( #user_email# ).";
$pgv_lang["mail01_line03"]		= "The following data was used.";
$pgv_lang["mail01_line04"]		= "Please click on the link below and fill in the requested data to verify your Account and Email address.";
$pgv_lang["mail01_line05"]		= "If you didn't request this data you can just delete this message.";
$pgv_lang["mail01_line06"]		= "You won't get any mail again from this system, because the account will be deleted without verification within seven days.";

$pgv_lang["mail01_subject"]		= "Your registration at #SERVER_NAME#";

$pgv_lang["mail02_line01"]		= "Hello Administrator ...";
$pgv_lang["mail02_line02"]		= "A new user made a new user-registration at ( #SERVER_NAME# ).";
$pgv_lang["mail02_line03"]		= "The user received an email with the necessary data to verify their account.";
$pgv_lang["mail02_line04"]		= "As soon as the user has done this verification you will be informed by mail to give this user the permission to login to your site.";

$pgv_lang["mail02_subject"]		= "New registration at #SERVER_NAME#";

$pgv_lang["hashcode"]			= "Verfification code:";
$pgv_lang["thankyou"]			= "Hello #user_fullname# ...<br />Thank you for your registration";
$pgv_lang["pls_note06"]			= "Now you will receive a confirmation email to the email address ( #user_email# ). Using the confirmation email, you will activate your account; if you fail to activate your account within seven days, it will be purged (you can register the account again at that point). To login to this site, you will need to know your login name and password.";

$pgv_lang["registernew"]		= "New Account confirmation";
$pgv_lang["user_verify"]		= "User verification";
$pgv_lang["send"]			= "Send";

$pgv_lang["pls_note07"]			= "Please type in your username, your password and the verification code you received by email from this system to verify your account request.";
$pgv_lang["pls_note08"]			= "The data for the user #user_name# was checked.";

$pgv_lang["mail03_line01"]		= "Hello Administrator ...";
$pgv_lang["mail03_line02"]		= "#newuser[username]# ( #newuser[fullname]# ) has verified the registration data.";
$pgv_lang["mail03_line03"]		= "Please click on the link below to login to your site edit the user and give him the permission to login to your site.";

$pgv_lang["mail03_subject"]		= "New verification at #SERVER_NAME#";

$pgv_lang["pls_note09"]			= "You were identified as a registered user.";
$pgv_lang["pls_note10"]			= "The Administrator has been informed.<br />As soon as he gives you the permission to login you can login with your username and password.";
$pgv_lang["data_incorrect"]		= "Data was not correct!<br />Please try again!";
$pgv_lang["user_not_found"]		= "Could not verify the information you entered.  Please go back and try again.";

$pgv_lang["lost_pw_reset"]		= "Lost password request";

$pgv_lang["pls_note11"]			= "To have your password reset, supply the username and email address for your user account. <br /><br />We will send you a special URL via email, which contains a confirmation hash for your account. By visiting the provided URL, you will be permitted to change your password and login to this site. For reasons of security, you should not provide this confirmation hash to anyone, including the administrators of this site (we won't ask for it).<br /><br />If you require assistance from the administrator of this site, please contact the site administrator.";
$pgv_lang["enter_email"]		= "You must enter an email address.";

$pgv_lang["mail04_line01"]		= "Hello #user_fullname# ...";
$pgv_lang["mail04_line02"]		= "A new password was requested for your username!";
$pgv_lang["mail04_line03"]		= "Recommendation:";
$pgv_lang["mail04_line04"]		= "Now please click on the link below, login with the new Password and change it to keep the integrity of your data secure.";

$pgv_lang["mail04_subject"]		= "Data request at #SERVER_NAME#";

$pgv_lang["pwreqinfo"]			= "Hello...<br /><br />A mail was sent to the email adress (#user[email]#) including the new password.<br /><br />Please check your mail account because the mail should be received in the next few minutes.<br /><br />Recommendation:<br /><br />After you have requested the mail you should login to this site with your new password and change it to keep the integrity of your data sequrity.";

$pgv_lang["editowndata"]		= "My Account";
$pgv_lang["savedata"]			= "Save changed data";
$pgv_lang["datachanged"]		= "User data was changed!";
$pgv_lang["datachanged_name"]		= "You may need to relogin with your new username.";
$pgv_lang["myuserdata"]			= "My Account";
$pgv_lang["verified"]			= "User verified himself:";
$pgv_lang["verified_by_admin"]		= "User Approved by Admin:";
$pgv_lang["user_theme"]			= "My Theme";
$pgv_lang["mygedview"]			= "MyGedView";
$pgv_lang["passwordlength"]		= "Password must contain at least 6 characters.";
$pgv_lang["admin_approved"]		= "Your account at #SERVER_NAME# has been approved";
$pgv_lang["you_may_login"]		= " by the site administrator.  You may now login to the PhpGedView Site by going to the link below:";


//-- mygedview page
$pgv_lang["welcome"]			= "歡迎";
$pgv_lang["upcoming_events"]		= "即將來臨的活動";
$pgv_lang["chat"]			= "聊天";
$pgv_lang["users_logged_in"]		= "用戶被登錄";
$pgv_lang["message"]			= "送信";
$pgv_lang["my_messages"]		= "我的消息";
$pgv_lang["date_created"]		= "日期被發送:";
$pgv_lang["message_from"]		= "消息從:";
$pgv_lang["message_to"]			= "消息對:";
$pgv_lang["message_subject"]		= "主題:";
$pgv_lang["message_body"]		= "機體:";
$pgv_lang["no_to_user"]			= "接收用戶未被提供。  不能繼續";
$pgv_lang["provide_email"]		= "請提供您的電子郵件以便我們能與您聯繫以回應這個消息。  如果您不提供您的電子郵件我們不會能回應您的查詢。  您電子郵件不會被使用在其他方式除回應這次查詢以外。";
$pgv_lang["reply"]			= "回復";
$pgv_lang["message_deleted"]		= "消息被刪除";
$pgv_lang["message_sent"]		= "信被送";
$pgv_lang["reset"]			= "重置";
$pgv_lang["site_default"]		= "站點默認值";
$pgv_lang["mygedview_desc"]		= "您的MyGedView 頁允許您保留您喜愛的人員書簽, 跟蹤即將來臨的活動, 並且與其它PhpGedView 用戶合作。";
$pgv_lang["no_messages"]		= "您沒有待定消息。";
$pgv_lang["clicking_ok"]		= "點擊好, 將打開您可以接觸的其它視窗";
$pgv_lang["my_favorites"]		= "我的收藏頁";
$pgv_lang["no_favorites"]		= "您未選擇任何收藏頁。  添加單個來您的收藏頁, 找到您想要添加和然後點擊\"添加來我的收藏頁\" 連結或使用身份證配件箱如下添加單個由他們的身份證編號單個的詳細資料。";
$pgv_lang["add_to_my_favorites"] 	= "添加來我的收藏頁";
$pgv_lang["confirm_fav_remove"]		= "是否確實要從您的收藏頁去除這個項目嗎?";
$pgv_lang["portal"]			= "門戶";
$pgv_lang["invalid_email"]		= "請輸入一封有效電子郵件。";
$pgv_lang["enter_subject"]		= "請進入消息主題。";
$pgv_lang["enter_body"]			= "請輸入一些文本在發送之前。";
$pgv_lang["confirm_message_delete"]	= "是否確實要刪除這個消息嗎?  它無法以後被檢索。";
$pgv_lang["message_email1"]		= "以下消息寄發了到您的PhpGedView 用戶帳號:";
$pgv_lang["message_email2"]		= "您寄發了以下消息到PhpGedView 用戶帳號:";
$pgv_lang["message_email3"]		= "您寄發了以下消息到PhpGedView 管理員:";
$pgv_lang["viewing_url"]		= "這信被送了當觀看以下URL: ";
$pgv_lang["messaging2_help"]		= "當您送這信您將接受複製寄發通過電子郵件到您提供的電子郵件。";
$pgv_lang["random_picture"]		= "任意照片";
$pgv_lang["choose_report"]			= "選擇報表運行";
$pgv_lang["page"]					= "頁";
$pgv_lang["individual_report"]		= "單獨報表";
$pgv_lang["family_group_report"]	= "系列組報表";
$pgv_lang["reports"]				= "報表";
$pgv_lang["download_report"]		= "下載報表";
$pgv_lang["select_report"]			= "選擇報表";
$pgv_lang["run_report"] 			= "視圖報表";
$pgv_lang["download_report"]		= "";
$pgv_lang["selected_report"]		= "所選的報表";
$pgv_lang["enter_report_values"]	= "進入報表值";
$pgv_lang["sosa_2"] 				= "父親";
$pgv_lang["sosa_3"] 				= "母親";
$pgv_lang["upload_file"]			= "向上作用的負載文件從您的電腦";
$pgv_lang["thumbgen_error"]			= "無法生成指圖為 ";
$pgv_lang["thumb_genned"]			= "指圖自動生成了。";

$pgv_lang["update_name"] = "更新名字";
$pgv_lang["quick_update_instructions"] = "這頁允許您快速更新資訊為單個。 您只需要填好新建或更改了從的資訊什麼當前是在這個網站。 在您的更改被提交之後他們將由站點管理員覆核在您能看他們聯機之前。";
$pgv_lang["photo_replace"] = "您想要用這一個替換一張更舊的相片嗎?";
$pgv_lang["update_address"] = "更新地址";
$pgv_lang["add_new_chil"] = "添加新建子項";
$pgv_lang["select_fact"] = "選擇一個情況...";
$pgv_lang["update_photo"] = "更新相片";
$pgv_lang["quick_update_title"] = "快速更新";

if (file_exists("languages/lang.zh.extra.php")) require "languages/lang.zh.extra.php";

?>