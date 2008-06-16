<?php
/**
 * German language file for Lightbox Album module
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PhpGedView developers
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
 * @translator  Gerd Kroll
 */

echo "<font size=\"2\" face=\"Verdana\"> ";
echo "<h3>Lightbox-Album HILFE: </h3>";
echo "<ol> ";

echo "<li>";
echo "<b><font color=\"blue\">Um Bilder zu betrachten</font></b><br />";
echo "klicken Sie einfach eins der Miniaturbilder. Der Bildtitel wird dann unter dem Bild gezeigt.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Zoom (Bild vergrößern oder verkleinern)</font></b><br />" ;
echo "Bitte beachten Sie: Der Diavortrag muss halten, bevor die Zoom-Ikone sichtbar werden.<br />";
echo "<b> Zoom einschalten:</b><br />";
echo "Wenn das grüne Plus-Symbol unten rechts sichtbar ist, ist Zoom aktiviert. Durch Bewegung des Mausrads können Sie das Bild vergrößern oder verkleinern. (Sie können auch die <b>i</b> und <b>o</b> Tasten benutzen.)  Das Symbol unten rechts verändert sich in ein rotes Minus-Symbol.<br /> ";
echo "Ein zu großes Bild kann mit Hilfe der Pfeil-Tasten im Fenster bewegt werden.<br />";
echo "<b> Zoom ausschalten:</b><br />";
echo "Wenn Sie das rote Minus-Symbol unten rechts klicken, wird Zoom ausgeschaltet. (Sie können auch die <b>z</b> Taste benutzen.)";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Um den Diavortrag zu beenden</font></b><br />";
echo "klicken Sie einfach das rote X-Symbol unten rechts.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Voriges oder nächstes Bild</font></b><br />";
echo "Wenn Zoom NICHT aktiviert ist, erhalten Sie links <b>&lt;</b> und rechts <b>&gt;</b> Symbole durch Bewegung der Maus über dem Bild. Wenn Sie in der rechten Hälfte des Bildes klicken, erhalten Sie das nächste Bild der Serie.  Wenn Sie in der linken Hälfte klicken, erhalten Sie das vorige Bild.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Direkter Zugriff zu Bildern</font></b><br />";
echo "Durch Bewegung der Maus im oberen Bildstreifen, der ungefähr 1 cm hoch ist, (Zoom ist NICHT aktiviert), erhalten Sie einen Streifen von Miniaturbildern die Ihnen zugänglich sind.  Um direkten Zugang zu Ihnen zugänglichen Bildern zu bewirken, müssen Sie nur das gewünschte Miniaturbild klicken. Direkter Zugang oder Aufruf des vorigen oder nächsten Bildes ist jederzeit möglich, wenn Zoom nicht aktiviert ist.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Diavortrag</font></b><br />";
echo "Der Diavortrag beginnt, wenn Sie das Start-Ikon unten links klicken.  Wenn eine Tonspur-Datei vorhanden ist, erhalten Sie das Ihnen bekannte Lautsprecher-Symbol.  Sie können dann das Symbol klicken, um die Tonspur ein- und aus zu schalten. Um den Diavortrag zu unterbrechen, müssen Sie das Pause-Symbol unten links klicken.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Navigation ...</font></b><br />";
echo "Rechts neben den Bildern finden Sie eine Tabelle wodurch Sie direkten Zugriff zu anderen Photoalben haben.";
echo "<br /><br /></li>";

echo "</ol>";
echo "<ul>";

echo "<li>";
echo "<b>Bemerk:</b><br />";
echo "Miniaturbilder die NICHT richtige Bilder sind, wie PDF-Dateien, oder Ton, Buch, und Video Medien-Typen, können alleinständig betrachtet werden, aber sind niemals im Diavortrag.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b>Für Verwalter:</b><br />";
echo "Falls Sie Dateien in gewöhnlichen Bild-Formaten (jpg, bmp, gif, usw.), die Bildertypen wie Foto, Urkunde, usw. darstellen, in der <b>Andere</b> Liste finden, haben Sie für diese Objekte vergessen, den richtigen Medien-Typ einzugeben.  Um diesen Fehler zu beheben, müssen Sie die Einzelheiten des Medien-Objekts bearbeiten.";
echo "<br /><br /></li>";

echo "</ul>";
 ?>