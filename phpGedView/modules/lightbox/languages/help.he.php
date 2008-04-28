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

echo "<font size=2 face=\"Verdana\">";
echo "<span dir=\"rtl\"> ";
echo "<h3>עזרה לאלבום-Lightbox: </h3>";
echo "<ol> ";

echo "<li>";
echo "<b><font color=\"blue\">לראות תמונה</font></b><br />";
echo "לחץ על תמונה ממוזערת כלשהי. כותרת התמונה תופיע בתחתית התמונה. "; 
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">להשתמש בצורת זום עם תמונה מוצגת</font></b><br />" ;
echo "הערה: יש לעצור את מצגת השקפים כדי לראות את צלמיות הזום.<br />";
echo "<b> אפשר זום: </b><br />";
echo "כאשר רואים את הצלמית הירוקה של פלוס בתחתית התמונה מצד ימין, הזום כבר פעיל. השתמש בגלגל העכבר למעלה ולמטה כדי לשנות את הגודל. (או השתמש במקשי <b>i</b> ו-<b>o</b>) הצלמית תשתנה למינוס אדום.<br /> ";
echo "כאשר התמונה תשונה לגודל גדול מהדף המוצג, השתמש במקשי החצים כדי להזיז את התמונה.<br />";
echo "<b> מנע זום: </b><br />";
echo "לחץ על הצלמית מינוס האדומה בתחתית מימין כדי לצאת מאופן הזום. (או השתמש במקש <b>z</b>)";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">לסגור תמונה </font></b><br />";
echo "לחץ על צלמית האדומה X בתחתית מימין.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">להצגת התמונה הקודמת או הבאה באלבום</font></b><br />";
echo "כאשר תעביר את העכבר מעל התמונה לא בצורת הזום תופיע תווית <b>&gt;</b> בצד שמאל ותווית <b>&lt;</b>  בצד ימין. לחץ על הצד הימני של התמונה כדי לראות את התמונה הבאה. לחץ על הצד השמאלי של התמונה כדי לראות את התמונה הקודמת. "; 
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">לעבור לתמונה אחרת באלבום</font></b><br />";
echo "כאשר תעביר את העכבר כסנטימטר אחד מעל החלק העליון של התמונה (לא בצורת הזום), תופיע הגלריה. אם צריך, העבר את סמן העכבר שמאלה וימינה כדי להראות חלקים אחרים של גלריית התמונות הממוזערות. לחץ על תמונה ממוזערת כלשהי מהגלריה כדי לקפוץ ישירות לתמונה הקשורה. ניתן לבצע <b>הבא</b>, <b>הקודם</b> ו<b>קפוץ</b> כאשר מצגת השקפים רצה או מופסקת. ";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">להריץ מצגת שקפים</font></b><br />";
echo "לחץ על צלמית התחל בתחתית בצד שמאל. המצגת מתחילה וצלמית הרמקול מופיעה. לחץ על צלמית הרמקול כדי להפעיל ולסגור את הקול. לחץ על צלמית הפסק כדי לעצור את המצגת.";
echo "<br /><br /></li>";
echo "<li>";
echo "<font color=\"blue\"><b>נווט ...</b></font><br />";
echo "השתמש בטבלת ראה אלבום משמאל לטבלת צלמיות של התמונות כדי לבחור ישירות מראה אלבום של אדם אחר.";
echo "<br /><br /></li>";

echo "</ol>";
echo "<ul>";

echo "<li>";
echo "<b>הערות:</b><br />";
echo "תמונות ממזעריות שהן אינן תמונות, לדוגמא קובצי מדיה מסוג PDF, אודיו, ספר, ווידאו, ניתן לראות בצורה פרטנית, אבל הן אינן במצגת השקפים.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b>הערה למנהלנים:</b><br />";
echo "אם קבצים מצורות רגילות של תמונות (jpg, bmp, gif, וכו') אשר מייצגות סוגי תמונות כגון תמונה, תעודה  מסמך וכו' מופיעות בשורת ה<b>אחר</b>, שכחת להגדיר את סוג המדיה של הפריטים הללו. יתכן ותרצה להגדיר מחדש את סוג המדיה של הפריטים הללו.";
echo "</li>";

echo "</ul>";

echo " </span> ";

 ?>