<?php
/**
 * phpGedView Research Assistant Tool - Form Loader Engine.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @subpackage Research_Assistant
 * @version $Id$
 * @translator 		Meliza Amity
 */

//-- security check, only allow access from module.php
if (preg_match("/ra_lang\...\.php$/", $_SERVER["PHP_SELF"])>0) {
	print "You cannot access a language file directly.";
	exit;
}
$pgv_lang["missing_info"] 		= "חסר מידע";
$temp_out_autosearch 		= "המאפין הזה מחפש בצורה אוטומטית ב-Ancestry וב-FamilySearch, "; 
$temp_out_autosearch 		.= "ניתן לחפש לפי שם ולפי תאריך לידה/פטירה <br />"; 
$pgv_lang["auto_search"]		= $temp_out_autosearch;
$pgv_lang["auto_search_text"]		= "חיפוש אוטומטי";
$pgv_lang["task_list"]		= "משימות";
$pgv_lang["task_list_text"]		= "האזור מציג משימות שיצרת, לחץ על 'התבונן' כדי לראות משימות";

// -- HELP COMMENTS
$temp_out_comments 		= "This section is to add comments to the person for other people to see and add feedback";
$pgv_lang["help_comments"] 		= $temp_out_comments;

// -- MENU ITEM MESSAGES
$pgv_lang["my_tasks"]		= "המשימות שלי";
$pgv_lang["add_task"]		= "הוסף משימה";
$pgv_lang["view_folders"]		= "התבונן בתיקיות"; ///////
$pgv_lang["view_probabilities"]		= "התבונן בהסתברויות";
$pgv_lang["up_folder"]		= "עלה תיקייה";
$pgv_lang["edit_folder"]		= "##Add/Edit Folder";
$pgv_lang["gen_tasks"]		= "צור משימות אוטומטית";


// -- RA GENERAL MESSAGES
$pgv_lang["edit_task"]		= "ערוך משימה";
$pgv_lang["completed"]		= "סיום";
$pgv_lang["complete"]		= "סיים";  
$pgv_lang["incomplete"]		= "חלקי"; 
$pgv_lang["comres"]			= "##Comments/Results";
$pgv_lang["description"]		= "תיאור";
$pgv_lang["created"]			= "נוצר"; 
$pgv_lang["modified"]		= "##Modified";
$pgv_lang["folder_list"]		= "##Folder List";
$pgv_lang["details"]			= "פירוט"; 
$pgv_lang["result"]                     	= "תוצאה";
$pgv_lang["okay"]                               	= "OK";
$pgv_lang["editform"]		= "ערוך את נתוני הטופס"; 
$pgv_lang["FilterBy"]		= "סנן לפי"; 
$pgv_lang["Recalculate"]		= "חשב מחדש"; 
$pgv_lang["LocalData"]		= "נתונים מקומיים"; 
$pgv_lang["RelatedRecord"]		= "רשומה קשורה"; 
$pgv_lang["RelatedData"]		= "נתונים קשורים"; 
$pgv_lang["Percent"]		= "אחוז"; 
$pgv_lang["Fields"]			= "מספר השדות"; 
$pgv_lang["FieldName"]		= "שם השדה";  
$pgv_lang["InputType"]		= "סוג הקלט";  
$pgv_lang["Values"]			= "ערכים"; 
$pgv_lang["FormName"]		= "הכנס את שם הטופס";  
$pgv_lang["MultiplePeople"]		= "האם הטופס משמש מספר אנשים?"; 
$pgv_lang["EnterGEDCOMExtension"]	= "נא הכנס את סיומת ה-GEDCOM לסוג העובדה של הטופס"; 
$pgv_lang['FormDesciption']		= "נא הכנס תאור לטופס"; 
$pgv_lang["FormGeneration"]		= "יצירת הטופס הסתיימה!"; 
$pgv_lang["CustomField"]		= "שם שדה פרטי"; 

// -- RA EXPLANATION
$pgv_lang["ViewProbExplanation"]	= "דף זה מנתח את נתוני קובץ ה-GEDCOM הפעיל ומראה את היחס בין הנתונים השונים. לדוגמא, תתכן קורלציה של 95% ששם משפחה ברשומה זהה לשם משפחה ברשומה של האב. משמעות הדבר של95% של האנשים בקובץ ה-GEDCOM הזה יש את אותו שם משפחה כמו לאביהם. בגירסה הזו של עוזר המחקר, החישובים הללו אינם משמשים בחלקים אחרים של התוכנה ומחשבים אותם רק כדי לעזור לך במחקרך. בעתיד אנחנו מתכננים להשתמש בנתונים האלו כדי להציע לך היכן כדאי להתרכז במחקרך העתידי."; 

// -- RA_FOLDER MESSAGES
$pgv_lang["Folder"]                             	= "##Folder:";
$pgv_lang["Edit_Task"]                 	= "##ערוך משימה"; //@@@ where used
$pgv_lang["End_Date"]                 	= "##End Date";
$pgv_lang["Start_Date"]                 	= "תאריך התחלה";
$pgv_lang["Task_Name"]                	= "שם משימה";
$pgv_lang["Folder_Name"]                	= "שם תיקייה";
$pgv_lang["Folder_View"]                	= "מבט תיקייות"; /////
$pgv_lang["Task_View"]                  	= "מבט משימות";
$pgv_lang["page_header"]		= "##Research Assistant Folders";
$pgv_lang["folder_new"]		= "##Create New Folder";
$pgv_lang["folder_delete_check"]	= "##Are you sure you want to delete this folder?";
$pgv_lang["no_folder_name"]             	= "יש למלא את שם התיקייה.";
$pgv_lang["add_folder"]                 	= "הוסף תיקייה";
$pgv_lang["edit_folder"]                	= "ערוך תיקייה";
$pgv_lang["folder_name"]                	= "שם תיקייה:";
$pgv_lang["Parent_Folder:"]             	= "תיקיית הורה:";
$pgv_lang["No_Parent"]                  	= "אין הורה";
$pgv_lang["Folder_Description:"]        	= "תאור התיקייה:";
$pgv_lang["Folder_names_must_be_unique"] = "שם תיקייה יכול להופיע רק פעם אחת."; 
$pgv_lang["folder_submitted"]          	= "התיקייה שלך נימסרה"; 
$pgv_lang["folder_problem"]             	= "התגלת תקלה בהוספת התיקייה שלך, נסה שנית"; 

// -- Missing Information Help 
$temp_out_missinginfo 		= "האזור מציג מידע חסר על הרשומה.";
$temp_out_missinginfo 		.= " סמן תיבת סימון ותיקייה ולחץ 'הוסף משימה' עבור הנתון החסר.";  
$temp_out_missinginfo 		.= " המשימות שכבר נוצרו יוצגו עם 'התבונן' במקום עם תיבת סימון <br />";
$temp_out_missinginfo 		.= " <a href=\"javascript:void(0);\" onClick=\"fullScreen('helpvids/MissingInformationUserHelp.htm');\">לחץ כאן כדי לפתוח הדרכה עצמית למשתמש (Tutorial) בחלון מלא</a>";
$pgv_lang["ra_missing_info_help"] 	= $temp_out_missinginfo;

// -- RA_EDITFOLDER MESSAGES	
$pgv_lang["edit_research_folder"]	= "##Edit Research Folder";
$pgv_lang["folder_not_exist"]		= "##This folder does not exist: ";
$pgv_lang["folder_parent"]		= "##Parent Folder";
$pgv_lang["parent_id"]		= "##None";
$pgv_lang["folder_users"]		= "##Other Users who can see this folder";

// -- RA_EDITLOG MESSAGES
$pgv_lang["edit_research_log"]		= "##Edit Research Log";
$pgv_lang["log_not_exist"]		= "##This log does not exist: ";

// -- RA_LOG MESSAGES
$pgv_lang["edit_log_entry"]		= "##Edit Research Log Entry";
$pgv_lang["log_no_entry"]		= "##ERROR: You do not have permission to access this item.";
$pgv_lang["log_modified"]		= "##Last Modified";
$pgv_lang["log_modified_by"]		= "##Last Modified By";
$pgv_lang["log_edit_entry"]		= "##Edit this entry";

// -- RA_LISTLOGS MESSAGES
$pgv_lang["research_logs"]		= "##Research Logs";
$pgv_lang["log_no_entry_folder"]	= "##ERROR: You do not have permission to access this folder.";
$pgv_lang["folder_sub"]		= "##Sub Folders";
$pgv_lang["folder_sub_new"]		= "##Create New Sub Folder";
$pgv_lang["task_entry"]		= "צור משימה חדשה.";
$pgv_lang["log_show"]		= "##Show All Logs";
$pgv_lang["log_show_uncomplete"]	= "##Show Uncompleted Logs";
$pgv_lang["log_show_complete"]	= "##Show Completed Logs";
$pgv_lang["log_delete_check"]		= "##Are you sure you want to delete this log entry?";

// -- RA_FUNCTIONS MESSAGES
$pgv_lang["function_folder_delete"]	= "##ERROR: Cannot delete this folder because it still contains research log entries.<br />First move or delete these research log entries and then try to delete the folder again.";
$pgv_lang["function_subfolder_delete"]	= "##ERROR: Cannot delete this folder because it still contains subfolders.<br />First move or delete these subfolders and then try to delete the folder again.";
$pgv_lang["folder_delete_ok"]		= "##The folder #folder_name# has been deleted sucessfully.";
$pgv_lang["folder_update_ok"]		= "##The folder #folder_name# has been sucessfully updated.";
$pgv_lang["folder_added"]		= "##The folder #folder_name# was sucessfully added.";

//-- RA_SEARCH MESSAGES
$pgv_lang["search_results"]		= "##Search Results";
$pgv_lang["nothing_found"]		= "##No matching logs found.";

//-- ERROR MESSAGES
$pgv_lang["no_folder"]		= "##No folder exists yet. Please create a new folder first.";

//-- HELP MESSAGES
$pgv_lang["help_rs_folders.php"]	= "##Research Assistant Folders<br /> #pgv_lang[sorry]#";
$pgv_lang["help_rs_editfolder.php"]	= "##Research Assistant Edit Folders<br />#pgv_lang[sorry]#";
$pgv_lang["help_rs_editlog.php"]	= "##Research Assistant Edit Log<br />#pgv_lang[sorry]#";
$pgv_lang["ra_fold_name_help"]	= "<H2><B>מבט תיקייות:</B></H2><ul><li><B>שם תיקייה:</B> העמוד הזה כולל את השמות של כל התיקיות שיצרת.</li><li><B>תאור:</B> העמוד הזה כולל את התאור של התיקיות.</li></ul><br /><br /><a href=\"helpvids/ResearchAssistantUserHelp.htm\">הדרכה עצמית לעוזר מחקר (Tutorial)</a>"; 
$pgv_lang["ra_add_task_help"]		= "<H2><B>הוסף משימה:</B></H2></H2><ul><li><B>כותרת:</B> כאן תמלא את הכותרת של המשימה שאותה אתה מוסיף.</li><li><B>תיקייה:</B> בשדה זה ניתן להקצות את התיקייה שבה יש לשים את המשימה החדשה.</li><li><B>תאור:</B> הכנס את תאור המשימה שאותה אתה מוסיף.</li><li><B>מקורות:</B>  הקצה מקורות למשימה.</li><li><B>אנשים:</B> הקצה אנשים הקשורים למשימה החדשה.</li></ul>";
$pgv_lang["ra_edit_folder_help"]	= "<H2><B>ערוך תיקייה:</B></H2><ul><li><B>שם התיקייה:</B> כאן תמלא את הכותרת של המשימה שאותה אתה עורך.</B></li><li><B>תיקיית הורה:</B> תוכל להקצות תיקיית הורה לתיקייה שאתה עורך.</B></li><li><B>תאור התיקייה:</B> תאור המשימה שאותה אתה עורך.</B></li><ul>"; 
$pgv_lang["ra_add_folder_help"]	= "<H2><B>הוסף תיקייה:</B></H2><ul><li><B>שם תיקייה:</B> כאן תמלא את הכותרת של התיקייה שאותה אתה מוסיף.</B></li><li><B>תיקיית הורה:</B> תוכל להקצות תיקיית הורה לתיקייה שאתה מוסיף.</B></li><li><B>תאור התיקייה:</B> כאן תרשום את תאור התיקייה שאתה מוסיף.</B></li><ul>"; 
$pgv_lang["ra_view_task_help"]	= "<H2><B>מבט משימות:</B></H2><ul><li><B>שם משימה:</B> העמודה כוללת את השם של המשימות שלך.</B></li><li><B>תאור:</B> העמוד הזה כולל את התאור של המשימות.</li><li><B>תאריך התחלה:</B> כאן רשומים תאריכי ההתחלה של המשימות.</li><li><B>סיום:</B> כאן תראה אם המשימה הסתיימה.</li><li><B>פרוט:</B> כאן מראים את פירוט המשימה.</li><li><B>מחק:</B> מחיקת המשימה.</li><ul><br /><a href=\"helpvids/MissingInformationUserHelp.htm\">הדרכה עצמית למשתמש (Tutorial)</a>"; 
$pgv_lang["ra_task_view_help"]	= "<H2><B>התבונן במשימה:</B></H2><ul><li><B>כותרת:</B> כאן תמלא את הכותרת של המשימה שאותה אתה מוסיף.</li><li><B>אנשים:</B> הקצה אנשים הקשורים למשימה החדשה.</li><li><B>תאור:</B> הכנס את תאור המשימה שאותה אתה מוסיף.</li><li><B>מקורות:</B> הקצה מקורות למשימה.</li><li>לחץ על כפתור 'ערוך משימה' כדי לערוך את הפרטים של המשימה.</li></ul>"; 
$pgv_lang["ra_comments_help"]	= "<H2><B>הערות:</B></H2><ul><li>כאן תוסיף הערות הקשורות למשימה. לחץ על כפתור 'הוסף הערה חדשה' כדי להוסיף הערות.</li></ul>"; 
$pgv_lang["ra_GenerateTasks_help"]	= "<H2><B>צור משימות:</B></H2><p> הטופס הזה יוצר משימות מתגי _TODO בקובץ ה-GEDCOM שלך.</p><ul><li><B>צור:</B> סמן כל משימה שברצונך ליצור כאשר תלחץ על הכפתור צור.</li><li><B>שם המשימה:</B> השם שהמשימה תקבל.  ברירת המחדל היא הטכסט בתג _TODO, ללא תגי CONT</li><li><B>תאור המשימה:</B> התאור שהמשימה תקבל.  התאור נוצר מהטכסט בתג _TODO ביחד עם תגי CONT הקשורים.  </li><li><B>ערוך:</B> לחץ על הקישור כדי לערוך את המשימה.</li><li><B>בחר תיקייה:</B> בחר תיקייה עבור המשימות הנוצרות.</li><li><B>צור:</B> יוצר את המשימות שסומנו.</li><li><B>סיום:</B> מעביר אותך לדף התבונן בתיקיות.</li></ul>"; 
$pgv_lang["ra_EditGenerateTasks_help"] 	= "<H2><B>##Edit Generated Task:</B></H2><p>This form allows you to edit the tasks generated from _TODO tags in your GEDCOM file.</p><ul><li><B>Task Name:</B> This is the name the task will be given.  </li><li><B>Task Description:</B> The description the task will be given. </li><li><B>People:</B> click the link to select the person to associate the task with.</li><li><B>Source:</B> click the link to select the source to associate the task with.</li><li><B>Save:</B> saves all your changes and redirects you to the Generate tasks page.</li><li><B>Cancel:</B> disregards all your changes and redirects you to the Generate tasks page.</li></ul>";
$pgv_lang["ra_configure_privacy_help"]	= "<H2><B>עצב פרטיות:</B></H2></H2><ul><li><B>הראה לכלל:</B> גורם לכך שמשימות ספציפיות תהיינה זמינות לכולם.</li><li><B>הראה רק למשתמשים מאושרים:</B> גורם לכך שמשימות ספציפיות תהיינה זמינות למשתמשים מאושרים בלבד.</li><li><B> הראה למשתמשים שהם מינהלנים:</B> גורם לכך שמשימות ספציפיות תהיינה זמינות למשתמשים מנהלנים בלבד.</li><li><B> הסתר אפילו ממשתמשים שהם מינהלנים:</B> גורם לכך שמשימות ספציפיות לא תהיינה זמינות לאף אחד.</li></ul>";
$pgv_lang["ra_edit_task_help"]		= "<H2><B>ערוך משימה:</B></H2></H2><ul><li><B>כותרת:</B> כאן תמלא את הכותרת של המשימה שאותה אתה עורך.</li><li><B>תיקייה:</B> בשדה זה ניתן להקצות את התיקייה שבה יש לשים את המשימה החדשה.</li><li><B>תאור:</B> הכנס תאור למשימה שאותה אתה עורך.</li><li><B>מקורות:</B> הקצה או ערוך מקורות למשימה.</li><li><B>אנשים:</B> הקצה או ערוך אנשים הקשורים למשימה.</li></ul>"; 

//-- RA_VIEWTASK MESSAGES
$pgv_lang["view_task"]		= "התבונן במשימה";
$pgv_lang["add_new_comment"]	= "הוסף הערה חדשה";
$pgv_lang["no_sources"]		= "##There are no sources associated with this task.";
$pgv_lang["no_people"]		= "##There are no people associated with this task.";
$pgv_lang["no_indi_tasks"]		= "אין משימות השייכות לאדם זה.";
$pgv_lang["no_sour_tasks"]		= "אין משימות הקשורות למקור זה."; 
$pgv_lang["edit_comment"]		= "ערוך הערה";
$pgv_lang["comment_success"]		= "הערתך הוספה בהצלחה."; 
$pgv_lang['comment_body']		= 'הערה';

//-- RA_COMMENT MESSAGES
$pgv_lang["comment_delete_check"]	= "האם באמת תרצה למחוק את ההערה?"; 

//-- RA_ADDTASK MESSAGES
$pgv_lang["add_new_task"]		= "הוסף משימה";
$pgv_lang["submit"]			= "שמור"; 
$pgv_lain["save_and_complete"]         	= "שמור וסיים";
$pgv_lang["assign_task"]		= "הקצה משימה";
$pgv_lang["AddTask"]		= "הוסף משימה"; 

//-- RA_CONFIGURE PRIVACY MESSAGES
$pgv_lang["configure_privacy"]		= "עצב פרטיות";
$pgv_lang["show_my_tasks"]              	= "הראה את המשימות שלי";
$pgv_lang["show_add_task"]		= "הראה הוסף משימה";
$pgv_lang["show_auto_gen_task"]         	= "הראה צור משימות אוטומטית";
$pgv_lang["show_view_folders"]	= "הראה התבונן בתיקייות ";
$pgv_lang["show_add_folder"]		= "הראה הוסף תיקייה";
$pgv_lang["show_add_unlinked_source"]   = "הראה מבט הוסף מקור לא קשור";
$pgv_lang["show_view_probabilities"]	= "הערה התבונן בהסתברויות";

//-- COMMENT HELP
$pgv_lang["comment_title_help"]	= "##Comment Title Help here.";
$pgv_lang["comment_help"]		= "##Click here for help.";

//-- Census Forms
$pgv_lang["rows"]                       	= "מספר שורות";
$pgv_lang["state"]                      	= "מדינה"; 
$pgv_lang["call/url"]                   	= "מספר סידורי/URL"; 
$pgv_lang["enumDate"]                   	= "תאריך מספור";  
$pgv_lang["county"]                     	= "מחוז"; 
$pgv_lang["city"]                       	= "עיר"; 
$pgv_lang["complete_title"]		= "סיים משימה"; 
$pgv_lang["select_form"]		= "בחר טופס"; 
$pgv_lang['choose_form_label']	= "בחר טופס מחקר משותף ";
$pgv_lang["book"]                 		= "ספר"; 
$pgv_lang["folio"]                   		= "פוליו"; 
$pgv_lang["uk_county"]		= "מחוז";
$pgv_lang["uk_boro"]		= "עיר"; 
$pgv_lang["uk_place"]		= "מקום"; 

//-- MY TASK BLOCK 
$pgv_lang["mytasks_block_descr"]	= "אזור המשימות שלי מראה משימות של המשתמש הנוכחי וניתן לעצב אותן כך שניתן לראות משימות שהסתיימו או משימות שטרם  הוקצו."; 
$pgv_lang["mytasks_block"]		= "המשימות שלי";      
$pgv_lang["mytasks_edit"]               	= "##Edit";
$pgv_lang["mytasks_unassigned"]	= "##Unassigned";
$pgv_lang["mytasks_takeOn"]		= "##TakeOn";
$pgv_lang["mytasks_help"]		= "~אזור המשימות שלי~<br /><br />אזור המשימות שלי מראה משימות של המשתמש הנוכחי<br />וניתן לעצב אותן כך שניתן לראות משימות שהסתיימו או משימות שטרם  הוקצו.";
$pgv_lang["mytask_show_tasks"]   	= "האם להציג משימות שאינן מיוחסות?";
$pgv_lang["mytask_show_completed"]	= "האם להציג משימות שהסתיימו?";

//-- Auto Search Assistant 
$pgv_lang["autosearch_surname"]	= "כלול שם משפחה:";
$pgv_lang["autosearch_givenname"]	= "כלול שמות פרטיים:"; 
$pgv_lang["autosearch_byear"]		= "כלול שנת לידה:";
$pgv_lang["autosearch_bloc"]		= "כלול מקום לידה:";  
$pgv_lang["autosearch_dyear"]		= "כלול שנת פטירה:"; 
$pgv_lang["autosearch_dloc"]		= "כלול מקום פטירה:"; 
$pgv_lang["autosearch_gender"]          	= "כלול מין:"; 
$pgv_lang["autosearch_plugin_name"]     	= "";         
$pgv_lang["autosearch_fsurname"]	= "כלול שם משפחה של האב:";
$pgv_lang["autosearch_fgivennames"]	= "כלול שמות פרטיים של האב:";
$pgv_lang["autosearch_msurname"]	= "כלול שם משפחה של האם:"; 
$pgv_lang["autosearch_mgivennames"]	= "כלול שמות פרטיים של האם:";    
$pgv_lang["autosearch_country"]  	= "כלול ארץ:"; 
$pgv_lang["autosearch_plugin_name_ancestry"] 	= "##Ancestry.com Plug-in"; //@@
$pgv_lang["autosearch_plugin_name_ancestrycouk"] = "##Ancestry.co.uk Plug-in"; //@@
$pgv_lang["autosearch_plugin_name_ellisIsland"] 	= "##EllisIslandRecords.org Plug-in"; //@@
$pgv_lang["autosearch_plugin_name_genNet"] 	= "##GeneaNet.com Plug-in"; //@@
$pgv_lang["autosearch_plugin_name_gen"] 	= "##Genealogy.com Plug-in";  //@@
$pgv_lang["autosearch_plugin_name_fs"] 		= "##FamilySearch.org Plug-in"; //@@
$pgv_lang["autosearch_plugin_name_werelate"]   = "##Werelate.org Plug-in";
$pgv_lang["autosearch_search"]           		= "##Search"; //@@
$pgv_lang["autosearch_keywords"] = "##Keywords:";

//Folder deletion error messages
$pgv_lang["has_tasks"]                 ="התיקייה מכילה משימות ולא ניתן למחוק אותה"; 
$pgv_lang["has_folders"]               ="התיקייה מכילה תיקיות ולא ניתן למחוק אותה"; 
?>