<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */

//-- security check, only allow access from module.php
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

//Config Parameters -------------------------------------
$pgv_lang["configure_lightbox"]		= "ערוך אלבום Lightbox";
$pgv_lang["mediatab"]       		= "<b>דף אישי - חוצץ מדיה</b><br />&nbsp;&nbsp;&nbsp;&nbsp;מראה"; 
$pgv_lang["lb_admin_error"]         = "דף רק עבור מנהלנים"; 

$pgv_lang["lb_icon"]				= "צלמית"; 
$pgv_lang["lb_text"]				= "טכסט";
$pgv_lang["lb_both"]				= "שניהם"; 
$pgv_lang["lb_none"]				= "אף אחד"; 
$pgv_lang["lb_ml_ThumbLinkAdvice"]	= "צלמית, טכסט, שניהם או אף אחד";

$pgv_lang["lb_al_head_links"]		= "<b>דף אישי - חציץ כותרת של האלבום</b><br />&nbsp;&nbsp;&nbsp;&nbsp;מראה קישור"; 
$pgv_lang["lb_al_thumb_links"]		= "<b>דף אישי - חציץ תמונות ממוזערות של האלבום </b><br />&nbsp;&nbsp;&nbsp;&nbsp;מראה קישור"; 
$pgv_lang["lb_ml_thumb_links"]		= "<b>דף מולטימדיה - תמונות ממוזערות</b><br />&nbsp;&nbsp;&nbsp;&nbsp;מראה קישור"; 
$pgv_lang["lb_music_file"]			= "<b>פס קול של מצגת שקופיות</b><br />&nbsp;&nbsp;&nbsp;&nbsp;(רק mp3)";
$pgv_lang["lb_musicFileAdvice"]		= "מיקום קובץ פס קול (השאר ריק עבור שום פס קול)"; 
$pgv_lang["lb_ss_speed"]			= "<b>מהירות מצגת שקופיות</b>"; 
$pgv_lang["lb_ss_SpeedAdvice"]		= "משך תיזמון מצגת שקפים בשניות"; 

// ---------------------------------------------------------------------

$pgv_lang["lb_help"] = "עזרת אלבום";
$pgv_lang["lightbox"] = "אלבום";
$pgv_lang["showmenu"] = "הראה תפריט:";
$pgv_lang["active"] = "פעיל";
$pgv_lang["TYPE__other"] = "אחר";
$pgv_lang["no_media"] = "אין"; 

$pgv_lang["census_text"]  = "\"תמונות המפקד האלו התקבלו מ\"הארכיון הלאומי\", השומר על הרשומות המקוריות, "; 
$pgv_lang["census_text"] .= "והן מופיעות כאן כולל אישור שלהן בתנאי שלא יעשה בהן שום שימוש מסחרי ללא אישור ." . "\n" ;
$pgv_lang["census_text"] .= "בקשות לפרסום מסחרי של תמונות מפקד אלו או תמונות מפקד אחרות שמופיעות באתר זה יש לשלוח אל: ";
$pgv_lang["census_text"] .= "Image Library, The National Archives, Kew, Surrey, TW9 4DU, United Kingdom.\"" . "\n" ;

$pgv_lang["lb_edit_details"] = "ערוך פרטים";
$pgv_lang["lb_view_details"] = "ראה פרטים"; 
$pgv_lang["lb_edit_media"] = "ערוך את הפרטים של פריט המדיה הזה";
$pgv_lang["lb_delete_media"] = "הסר פריט מדיה זה - מוריד רק את הקישור לאדם זה - אינו מוחק את קובץ המדיה או קישורים אחרים";
$pgv_lang["lb_view_media"] = "ראה פירוט של פריט מדיה זה. \nואופציות מדיה נוספות - דף התבוננות מדיה"; 
$pgv_lang["lb_add_media"] = "הוסף ישות מדיה";
$pgv_lang["lb_add_media_full"] = "הוסף ישות מולטימדיה חדשה לאדם זה";
$pgv_lang["lb_link_media"] = "קשר לישות מדיה קיימת"; 
$pgv_lang["lb_link_media_full"] = "קשר אדם זה לישות מדיה קיימת";

$pgv_lang["lb_slide_show"] = "מצגת שקופיות";
$pgv_lang["turn_edit_ON"] = "הפעל צורת עריכה"; 
$pgv_lang["turn_edit_OFF"] = "הפסק צורת עריכה"; 

$pgv_lang["lb_source_avail"] = "קיימים נתוני מקור - לחץ כאן.";

$pgv_lang["lb_private"] = "התמונה קשורה<br>לאדם פרטי"; 
$pgv_lang["lb_view_source_tip"] = "הראה מקור: "; 
$pgv_lang["lb_view_details_tip"] = "הראה פרטי מדיה: "; 
?>