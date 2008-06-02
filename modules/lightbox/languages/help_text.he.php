<?php
/**
 * Hebrew language file for Lightbox Album module
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PhpGedView developers
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
	print "אין לך גישה ישירה לקובץ השפות.";
	exit;
}

$pgv_lang["mediatabLegend"]		= "הופעת חוצץ מדיה";
$pgv_lang["mediatab_help"]		= "~#pgv_lang[mediatabLegend]#~<br />הבחירה מאפשרת לך לקבוע אם להראות את חוצץ המדיה על דף #pgv_lang[indi_info]#.<br /><br />כאשר הערך הוא <b>#pgv_lang[hide]#</b>, מראים רק את החוצץ <b>#pgv_lang[lightbox]#</b>, והוא יקרא גם כן <b>#pgv_lang[media]#</b>.<br />";
$pgv_lang["lb_al_head_linksLegend"]	= "הופעת קישור חוצץ כותרת של אלבום";
$pgv_lang["lb_al_head_links_help"]		= "~#pgv_lang[lb_al_head_linksLegend]#~<br />הבחירה מאפשרת לקבוע אם אזור הכותרת של ה#pgv_lang[lightbox]# tab, אשר מכילה קישורים לXXX מופעים שונים של מודול ה-Lightbox, תכלול רק צלמיות, רק טכסט או שניהם.<br /><br />בחירה <b>#pgv_lang[lb_icon]#</b> אינה שימושית מאוד, מכיוון שלא רואים שום סימן לשימוש של הצלמיות עד שהעכבר מרחף מעל הצלמית.<br />";
$pgv_lang["lb_al_thumb_linksLegend"]	= "הופעת קישור חוצץ עמוד תמונות ממוזערות של אלבום ";
$pgv_lang["lb_al_thumb_links_help"]		= "~#pgv_lang[lb_al_thumb_linksLegend]#~<br />הבחירה מאפשרת לקבוע אם אזור הקישורים מתחת לכל תמונה ממוזערת יראה צלמית או טכסט.  הקישורים שאותם רואים כאן מאפשרים עריכה של פירטי ישות מדיה או מחיקתה.<br />";
$pgv_lang["lb_ml_thumb_linksLegend"]	= "הופעת קישור תמונות ממוזערות";
$pgv_lang["lb_ml_thumb_links_help"]	= "~#pgv_lang[lb_ml_thumb_linksLegend]#~<br />הבחירה מאפשרת לקבוע אם אזור הקישורים מעל לפירוט פריטי מדיה ברשימת מולטימדיה יכלול רק צלמיות, רק טכסט או שניהם.  הקישורים המוצגים כאן מאפשרים לבצע פעולות עריכה שונות עבור ישות המדיה הספציפית.<br /><br />הבחירה <b>#pgv_lang[lb_none]#</b> מחביאה את הקשורים הללו לחלוטין ולכן מתנהגת כאילו למשתמש אין הרשאות עריכה.<br />";
$pgv_lang["lb_ss_speedLegend"]		= "מהירות מצגת שקופיות";
$pgv_lang["lb_ss_speed_help"]		= "~#pgv_lang[lb_ss_speedLegend]#~<br />הבחירה קובעת את אורך הזמן שיש להציג כל תמונה לפני שמצגת השקופיות תציג את התמונה הבאה לפי הסדר.<br />";
$pgv_lang["lb_music_fileLegend"]		= "פס קול של מצגת שקופיות";
$pgv_lang["lb_music_file_help"]		= "~#pgv_lang[lb_music_fileLegend]#~<br />הבחירה מאפשרת לקבוע קובץ פס קול שינוגן כאשר מצגת השקופיות פעילה. אם תשאיר שדה זה ריק, לא יושמע קול בזמן מצגת השקופיות.<br /><br />המאפיין הזה תומך רק בקבצים במבנה mp3.<br />"; 
$pgv_lang["lb_transitionLegend"]		= "מהירות המעבר מתמונה לתמונה";
$pgv_lang["lb_transition_help"]		= "~#pgv_lang[lb_transitionLegend]#~<br />האופציה מאפשרת לקבוע את מהירות המעבר מתמונה לתמונה. הבחירה מופעלת בזמן מצגת שקופיות. היא מופעלת גם כן כאשר עוברים ידנית לתמונה הקודמת או הבאה כאשר מצגת התמונות אינה מופעלת.<br /><br />אופציית <b>#pgv_lang[lb_none]#</b> מונעת מעבר בין תמונות כך שהתמונה החדשה מחליפה מידית את הישנה ללא שינוי ניראה לעין במימדי התמונה.<br />"; 

$pgv_lang["lb_url_dimensionsLegend"]	= "מימדי חלון Lightbox URL"; 
$pgv_lang["lb_url_dimensions_help"]	= "~#pgv_lang[lb_url_dimensionsLegend]#~<br />כאשר לוחצים על תמונה ממוזערת של URL, הבחירה מאפשרת לקבוע את מימדי חלון Lightbox URL בפיקסלים.<br /><br />בדרך כלל זה יהיה פחות ממימדי החלון הנוכחי בדפדפת, ובוודאי פחות מרזולוציית המסך.<br />"; 
 
?>