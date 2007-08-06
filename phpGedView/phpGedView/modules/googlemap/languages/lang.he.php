<?php
/**
 * Hebrew Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PGV Development Team
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
 * @subpackage GoogleMap
 * @version $Id$
 */
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

$pgv_lang["googlemap"]              	= "מפה";
$pgv_lang["no_gmtab"]               	= "אין נתוני מפה עבור אדם זה";
$pgv_lang["gm_disabled"]            	= "מודול GoogleMap נוטרל"; 

$pgv_lang['gm_redraw_map']          	= "צייר מפה מחדש";
$pgv_lang["gm_map"]                 	= "מפה";
$pgv_lang["gm_satellite"]           	= "לויין";
$pgv_lang["gm_hybrid"]              	= "מעורב";

// Configuration texts
$pgv_lang["gm_manage"]              	= "נהל תצורת GoogleMap";
$pgv_lang["configure_googlemap"]   = "קנפג GoogleMap"; 
$pgv_lang["gm_admin_error"]         	= "דף רק של מנהלנים";
$pgv_lang["gm_db_error"]            	= "טבלת מיקום לא נימצאה במאגר הנתונים";
$pgv_lang["gm_table_created"]       	= "טבלת מיקום נוצרה";
$pgv_lang["googlemap_enable"]       	= "אפשר GoogleMap";
$pgv_lang["googlemapkey"]           	= "מפתח API של GoogleMap";
$pgv_lang["gm_map_type"]            	= "ברירת מחדל של סוג המפה";
$pgv_lang["gm_map_size"]            	= "גודל המפה (בפיקסלים)";
$pgv_lang["gm_map_size_x"]          	= "רוחב";
$pgv_lang["gm_map_size_y"]          	= "גובה";
$pgv_lang["gm_map_zoom"]            	= "מקדם זום של המפה";
$pgv_lang["gm_digits"]              	= "ספרות";
$pgv_lang["gm_min"]                 	= "מינימום";
$pgv_lang["gm_max"]                 	= "מכסימום";
$pgv_lang["gm_default_level0"]      	= "ערך ברירת המחדל של הרמה העליונה";
$pgv_lang["gm_nof_levels"]          	= "מספר רמות";
$pgv_lang["gm_config_per_level"]    	= "תצורה לרמה";
$pgv_lang["gm_name_prefix"]         	= "תחילית";
$pgv_lang["gm_name_postfix"]        	= "סיומת";
$pgv_lang["gm_name_pre_post"]      = "סדר תחילית / סיומת";
$pgv_lang["gm_level"]               	= "רמה";
$pgv_lang["gm_pp_none"]             	= "אין תחילית/סיומת";
$pgv_lang["gm_pp_n_pr_po_b"]        = "נורמלי, תחילית, סיומת, שניהם";
$pgv_lang["gm_pp_n_po_pr_b"]        = "נורמלי, סיומת, תחילית, שניהם";
$pgv_lang["gm_pp_pr_po_b_n"]        = "תחילית, סיומת, שניהם, נורמלי";
$pgv_lang["gm_pp_po_pr_b_n"]        = "סיומת, תחילית, שניהם, נורמלי";
$pgv_lang["gm_pp_pr_po_n_b"]        = "תחילית, סיומת, נורמלי, שניהם";
$pgv_lang["gm_pp_po_pr_n_b"]        = "סיומת, תחילית, נורמלי, שניהם";
$pgv_lang["googlemap_coord"]        	= "הצג קוארדינטות של המפה";  


// Texts used on the Places location page
$pgv_lang["edit_place_locations"]   	= "ערוך מיקומים גיאוגרפיים";
$pgv_lang["pl_no_places_found"]     	= "לא נמצאו מקומות";
$pgv_lang["pl_zoom_factor"]         	= "מקדם זום";
$pgv_lang["pl_place_icon"]          	= "צלמית";
$pgv_lang["pl_edit"]                	= "ערוך מיקום גיאוגרפי";
$pgv_lang["pl_add_place"]           	= "הוסף מקום";
$pgv_lang["pl_import_gedcom"]       	= "ייבא מקובץ ה-GEDCOM הנוכחי";
$pgv_lang["pl_import_all_gedcom"]  	= "ייבא מכל קובצי ה-GEDCOM";
$pgv_lang["pl_import_file"]         	= "ייבא מקובץ";
$pgv_lang["pl_export_file"]         	= "ייצא את המבט הנוכחי לקובץ";
$pgv_lang["pl_export_all_file"]     	= "ייצא את כל המיקומים לקובץ";
$pgv_lang["pl_north_short"]         	= "צפון";
$pgv_lang["pl_south_short"]         	= "דרום";
$pgv_lang["pl_east_short"]          	= "מזרח";
$pgv_lang["pl_west_short"]          	= "מערב";
$pgv_lang["pl_places_filename"]     	= "הקובץ כולל מיקומים (CSV)";
$pgv_lang["pl_clean_db"]            	= "האם לנקות את כל המיקומים לפני ייבוא?";
$pgv_lang["pl_update_only"]         	= "עדכן רק מקומות קיימים?";
$pgv_lang["pl_overwrite_data"]      	= "העלה נתונים מקובץ על גבי נתוני מיקומים קיימים?";
$pgv_lang["pl_no_places_found"]     	= "לא נמצאו מקומות";
$pgv_lang["pl_use_this_value"]      	= "השתמש בערך הזה";
$pgv_lang["pl_precision"]           	= "דיוק";
$pgv_lang["pl_country"]             	= "ארץ";
$pgv_lang["pl_state"]               	= "מדינה";
$pgv_lang["pl_city"]                	= "עיר";
$pgv_lang["pl_neighborhood"]        	= "שכונה";
$pgv_lang["pl_house"]               	= "בית";
$pgv_lang["pl_max"]                 	= "מכסימלי";
$pgv_lang["pl_delete"]              	= "מחק מקום גיאוגרפי"; 
$pgv_lang["pl_flag"]                	= "דגל";
$pgv_lang["flags_edit"]             	= "בחר דגל";
$pgv_lang["pl_change_flag"]         	= "החלף דגל";
$pgv_lang["pl_remove_flag"]         	= "הסר דגל";

$pgv_lang["pl_remove_location"]     	= "?הסר המיקום";
$pgv_lang["pl_delete_error"]        	= "המיקום לא הורד: המיקום כולל תתי מיקומים";
$pgv_lang["list_inactive"]        	= "לחץ כאן כדי להראות מקומות שאינם בשימוש"; 

//Placecheck specific text
$pgv_lang["placecheck"]		= "בדיקת מקום";
$pgv_lang['placecheck_text']	= "זה יבנה רשימה של כל המקומות מקובץ ה-GEDCOM הנבחר. לפי ברירת מחדל זה <b>אינו</b> כולל מקומות עם התאמה מלאה בין קובץ ה-GEDCOM וטבלאות ה-GoogleMap";
$pgv_lang['placecheck_top']	= "מקום של רמה עליונה"; 
$pgv_lang['placecheck_one']	= "מקום של רמה ראשונה";
$pgv_lang['placecheck_select1']	= "בחר את הרמה העליונה...";
$pgv_lang['placecheck_select2']	= "בחר את הרמה הבאה...";
$pgv_lang['placecheck_key']	= "מפתח לצבעים שבשימוש למטה"; 
$pgv_lang['placecheck_key1']	= "מקום זה והקואורדינטות שלו אינן קיימות בטבלאות ה-GoogleMap";
$pgv_lang['placecheck_key2']	= "מקום זה קיים בטבלאות GoogleMap, אבל ללא קואורדינטות";
$pgv_lang['placecheck_key3']	= "רמת מקום זו ריקה בקובץ ה-GEDCOM שלך. יש להוסיף אותו <br/>למקומות GoogleMap כ\"בלתי ידוע\" עם הקואורדינטות של רמת ההורה שלו<br/>לפני שמוסיפים מקום ברמה הבאה";
$pgv_lang['placecheck_key4']           	= "רשימת מקומות עבור בקובץ GEDCOM";
$pgv_lang['placecheck_head']	= "הרמה הזו של רשימת מקומות ריקה בקובץ ה-GEDCOM שלך, אבל קיימת כ'בלתי ידוע'<br/>בטבלת המקומות של GoogleMap עם קואורדינטות. שום פעולה אינה נדרשת<br/>עד שמוסיפים את הרמה החסרה";
$pgv_lang['placecheck_gedheader']	= "נתוני המקומות של קובץ GEDCOM<br/>2 PLAC";
$pgv_lang['placecheck_gm_header']	= "נתוני טבלת המקומות של GoogleMap";
$pgv_lang['placecheck_unique']	= "סה\"כ מקומות יחודיים"; 
$pgv_lang["placecheck_zoom"]         	= "זום=";
$pgv_lang["placecheck_options"]     	= "אופציות של רשימת בדיקת מקום";
$pgv_lang["placecheck_filter_text"] 	= "תציג אופציות של הפילטר";
$pgv_lang["placecheck_match"] 	= "כלול מקומות עם התאמה מלאה -";
?>