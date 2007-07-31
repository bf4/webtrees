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
echo "<h3>Lightbox-Album HELP: </h3>";
echo "<ol> ";

echo "<li>";
echo "<b><font color=\"blue\">To view an image</font></b><br>";
echo "Click on an image icon. ";
echo "The title of the image will appear at the bottom of the overlaid image. ";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">To use zoom mode with a displayed image</font></b><br>" ;
echo "<b> Enable Zoom: </b><br>";
echo "Click on the [ZOOM] label at the top left of the image.<br> ";
echo "Now use the mouse wheel up and down to resize.<br>";
echo "When the image is re-sized larger than the viewed page, use the mouse left button (held down)  to \"drag\" the image around.<br>";
echo "<b> Disable Zoom: </b><br>";
echo "Click on the [NORMAL] label at the top left again to get out of zoom mode, and see the title (bottom), and the [CLOSE X] label (top right) again.";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">To close an image (three ways)</font></b><br><ol>";
echo "<li />When the cursor is within the image, click on the [CLOSE X] label at top right.<br>";
echo "<li />Click anywhere else within the image<br>";
echo " (note if you do this when in zoom mode, you will have to click the [ZOOM] label twice again when viewing the next image to see the title and the [CLOSE X] label once more.";
echo "<li />Click outside the image<br>";
echo " (note if you do this when in zoom mode, you will have to click the [ZOOM] label twice again when viewing the next image to see the title and the [CLOSE X] label once more.";
echo "</li></ol><br>";

echo "<li>";
echo "<b><font color=\"blue\">To view the next image in the section</font></b><br>";
echo "Click on the [Next] or [Prev] labels which appear on the sides of the image when you \"mouse over\" the image.";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">Navigation ...</b></font><br>";
echo "Use the \" View 'Album' \" table at the right of the image icon table to directly choose another person's Album View.<br>";

echo "</ol>";

echo "<br><b>Notes:</b><br>";
echo "Image icons which appear within the \"Other\" row, will not be displayed with \"Lightbox\"<br> ";
echo "e.g. PDF files, plus, audio, book, and video \"TYPES\" of media. <br>";
echo "This is done intentionally as \"Lightbox\" cannot handle these files at the moment.<br><br>";
echo "<b>Note for Administrator:</b><br>";
echo "If any \"image\" (jpeg etc) files (photo, certificate, document etc) appear in the \"Other\" row, it means that no \"TYPE\" has been set.<br>";
echo "You may wish to re-classify the \"TYPE\" of media for these items.";

echo " </font> ";
 ?>