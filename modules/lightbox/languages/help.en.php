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


echo "<font size=\"2\" face=\"Verdana\"> ";
echo "<h3>Lightbox-Album HELP: </h3>";
echo "<ol> ";

echo "<li>";
echo "<b><font color=\"blue\">To view an image</font></b><br />";
echo "Click on any thumbnail. The title of the image will appear at the bottom of the overlaid image. ";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">To use zoom mode</font></b><br />" ;
echo "NOTE: The slideshow must be paused to see the Zoom icons.<br />";
echo "<b> Enable Zoom: </b><br />";
echo "When the green Plus icon at the bottom right of the image is visible, Zoom is already enabled. Use the mouse wheel up and down to resize. (Or use keys <b>i</b> and <b>o</b>) The icon will change to a red Minus.<br /> ";
echo "When the image is re-sized larger than the viewed page, use the arrow keys to move the image around.<br />";
echo "<b> Disable Zoom: </b><br />";
echo "Click on the red Minus icon at the bottom right to get out of Zoom mode. (Or use the <b>z</b> key)";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">To close an image </font></b><br />";
echo "Click on the red X icon at bottom right.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">To view the next or previous image</font></b><br />";
echo "As you mouse over the image when NOT in Zoom mode, a <b>&lt;</b> symbol will appear on the left side, and a <b>&gt;</b> on the right. Click anywhere in the right half of the image to see the next image. Click anywhere in the left half to see the previous one.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">To jump to any other image in the Album</font></b><br />";
echo "As you mouse over the top 1 cm of the image when NOT in Zoom mode, a thumbnail Gallery will appear. If necessary, move the mouse cursor left and right to make other sections of this thumbnail Gallery show.  Click any Gallery thumbnail to jump diectly to the associated image. <b>Next</b>, <b>Previous</b> and <b>Jump</b> may be done whether the slideshow is running or paused.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">To run the slide show</font></b><br />";
echo "Click on the Start icon at bottom left. If there is a sound track file, the Speaker icon will appear.  Click on the Speaker icon to toggle the sound track on and off. Click on the Pause icon to stop the slide show.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Navigation ...</font></b><br />";
echo "Use the View Album table at the right of the image icon table to directly choose another person's Album view.";
echo "<br /><br /></li>";

echo "</ol>";
echo "<ul>";

echo "<li>";
echo "<b>Note:</b><br />";
echo "Thumbnails which are NOT images, such as PDF files and audio, book, and video Media types, may be viewed individually, but will not be in the slide show.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b>Note for Administrator:</b><br />";
echo "If any files of the usual image formats (jpg, bmp, gif, etc.) representing image types such as photo, certificate, document, etc. appear in the <b>Other</b> row, you have forgotten to set the Media type for these objects.  You may wish to edit the Media type for these items.";
echo "<br /><br /></li>";

echo "</ul>";
 ?>