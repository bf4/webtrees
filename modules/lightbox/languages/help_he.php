<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
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
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */


echo "<font size=2 face=\"Verdana\"> ";
echo "<h3>עזרה של אלבום-Lightbox: </h3>";
echo "<ol> ";

echo "<li>";
echo "<b><font color=\"blue\">לראות תמונה</font></b><br>";
echo "לחץ על תמונה מזערית כלשהיא. ";
echo "כותרת התמונה תופיע בתחתית התמונה. "; 
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">להשתמש בצורת זום עם תמונה מוצגת</font></b><br>" ;
echo "<b> אפשר זום: </b><br>";
echo "הערה: יש לעצור את מצגת השקפים כדי לראות את צלמיות ה\"זום\".<br>";
echo "כאשר רואים את הצלמית הירוקה של פלוס בתחתית התמונה מצד ימין, הזום כבר פעיל.<br> ";
echo "השתמש בגלגל העכבר למעלה ולמטה כדי לשנות את הגודל.<br>";
echo "(או השתמש במקשי \"i\" ו-\"o\")<br>";
echo "הצלמית תשתנה למינוס אדום.<br> ";
echo "כאשר התמונה תשונה לגודל גדול מהדף המוצג, השתמש במקשי החצים כדי \"להזיז" את התמונה.<br>";
echo "<b> מנה זום: </b><br>";
echo "לחץ על הצלמית מינוס האדומה בתחתחת מימין כדי לצאת מאופן הזום.<br>";
echo "(או השתמש במקש \"z\" )";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">לסגור תמונה </font></b><br>";
echo "לחץ על צלמית האדומה X בתחתית מימין.";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">להצגת התמונה הקודמת או הבאה באלבום</font></b><br>";
echo "כאשר תעביר את \"העכבר מעל\" התמונה (לא בצורת ה\"זום\"), ";
echo "תויות \"NEXT\" ו-\"PREV\" יופיעו.<br> ";
echo " לחץ איפשהו על הצד הימני של התמונה כדי לראות את התמונה הבאה. לחץ על צד השמאלי של התמונה כדי לראות את התמונה הקודמת. ";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">\"לעבור\" לתמונה אחרת באלבום</font></b><br>";
echo "כאשר תעביר את \"העכבר מעל\" סנטימטר אחד של החלק העליון של התמונה (לא בצורת ה\"זום), ";
echo "תופיע ה\"גלריה\".<br> ";
echo "אם צריך, העבר את סממן העכבר שמאלה וימינה ולחץ על תמונה מזערית כלשהיא מהגלריה<br>";
echo "ניתן לבצע \"הבאה\", \"הקודם\" ו \"קפוץ\" כאשר מצגת השקפים רצה או מופסקת. ";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">להריץ מצגת שקפים</font></b><br>";
echo "לחץ על צלמית \"Start\" בתחתית בצד שמאל.<br>";
echo "אם יש קובץ קול, הקול יופעל וצלמית הרמקול תופיע.";
echo "לחץ על צלמית הרמקול כדי להפעיל ולסגור הקול .<br>";
echo "לחץ על צלמית הפסק כדי לעצור את המצגת.<br>";
echo "הערה: כאשר המצגת מופעל - כפתור הזום לא מוצג";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">נווט ...</b></font><br>";
echo "השתמש בטבלת \" ראה 'אלבום'' \" משמאל לטבלת צלמיות של התמונות כדי לבחור ישירות מראה אלבום של אדם אחר.<br>";

echo "</ol>";

echo "<br><b>הערות:</b><br>";
echo "ניתן לראות תמונות מזעריות שהן אינן תמונות בצורה פרטנית , אבל הן אינן במצגת השקפים.<br> ";
echo "דוגמא של \"סוגי\" מדיה הן קיבצי PDF, plus, אודיו ווידאו. <br><br>";

echo "<b>הערה למנהלנים:</b><br>";
echo "אם קובץ \"תמונה\" (jpeg וכו')  (תמונה, תעודה מסמך וכו') מופיע בשורת \"אחר\", משמעות הדבר שה\"סוג\" לא הוגדר.<br>";
echo "יתכן ותרצה להגדיר מחדש את \"סוג\" המדיה של הפריטים הללו.";

echo " </font> ";
 ?>