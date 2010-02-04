<?php
/**
 * Footer for Cloudy theme
 *
 * PhpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @author w.a. bastein http://genealogy.bastein.biz
 * @package PhpGedView
 * @subpackage Themes
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

global $footerscriptshown, $THEME_DIR, $BROWSERTYPE;
if (!$footerscriptshown) {

        echo <<<JSCRIPT
        <script type="text/javascript" language="javascript" >
<!--
function hidebar()
{ // hides the loading message
	loadbar = document.getElementById("ProgBar");
	if (loadbar) loadbar.style.display = "none";
}
JSCRIPT;
        $onload ="hidebar();";
        if (PGV_SCRIPT_NAME!='individual.php')
        {
                if (PGV_SCRIPT_NAME=='pedigree.php' ||
                PGV_SCRIPT_NAME=='descendancy.php' ||
                PGV_SCRIPT_NAME=='timeline.php' ||
                PGV_SCRIPT_NAME=='relationship.php')
                {
                echo "\n", <<<JSCRIPT
function resize_content_div()
{ // resizes the container table to fit data
        if (document.getElementById('footer'))
        {
                var foot =document.getElementById('footer');
                var head =document.getElementById('header');
                var cont =document.getElementById('container');

                var browserWidth = Math.max(document.body.clientWidth, 200);
JSCRIPT;
                $onload .="\n\tresize_content_div();";
                if (PGV_SCRIPT_NAME=='pedigree.php' || PGV_SCRIPT_NAME=='descendancy.php')
                { // pedigree and descendancy height
                        echo "\t\ty = foot.offsetTop;\n";
                        //echo "\t\tz = parseInt(y);\n";
                        echo "\t\tz=(y-70);\n";
                        //echo "\t\talert(y);\n";
                        echo "\t\tcont.style.height=(z.toString()+'px');\n";

                } else if (PGV_SCRIPT_NAME=='timeline.php')
                { // timeline height
                        global $endoffset;
                        if (!$endoffset) $endoffset=270;
                        echo "\t\ty='", $endoffset, "px';\n";
                        echo "\t\tcont.style.height=(y);\n";
                } else if (PGV_SCRIPT_NAME=='relationship.php')
                { // relationship height and width
                        global $maxyoffset, $xoffset, $Dbwidth, $xs;
                        $xoffset += $Dbwidth+$xs;
                        echo "\t\ty='", ($maxyoffset-70), "px';\n";
                        echo "\t\tcont.style.height=(y);\n";
                        // check if xoffset is lower then default screensize
                        echo "\t\tx=", $xoffset, ";\n";
                        echo "\t\tif (x < (browserWidth))\n";
                        echo "\t\t\tx= (browserWidth);";
                        echo "\t\tcont.style.width=x.toString()+'px';\n";
                        echo "\t\thead.style.width=x.toString()+'px';\n";
                }
                if (PGV_SCRIPT_NAME=='pedigree.php')
                { // pedigree width
                        global $bwidth, $bxspacing, $PEDIGREE_GENERATIONS, $talloffset, $Darrowwidth;
                        $xoffset = ($PEDIGREE_GENERATIONS * ($bwidth+(2*$bxspacing))) + (2*$Darrowwidth);
                        if ($talloffset==0) { $xoffset = floor($xoffset /1.4); }
                        echo "\t\tx=", $xoffset, ";\n";
                        echo "\t\tif (x < (browserWidth))\n";
                        echo "\t\t\tx= (browserWidth);\n";
                        //echo "alert(x);";
                        echo "\t\tcont.style.width=(x).toString()+'px';\n";
                        echo "\t\thead.style.width=(x).toString()+'px';\n";

                } // descendancy width
                if (PGV_SCRIPT_NAME=='descendancy.php')
                {
                        global $maxxoffset;
                        $xoffset = ($maxxoffset+60);
                        echo "\t\tx=", $xoffset, ";\n";
                        echo "\t\tif (x < (browserWidth))\n";
                        echo "\t\t\tx= (browserWidth);\n";
                        echo "\t\tcont.style.width=x.toString()+'px';\n";
                        echo "\t\thead.style.width=x.toString()+'px';\n";
                } //
                echo "\n\t}\n}\n";
        }  else if (PGV_SCRIPT_NAME=='index.php')
        {
                echo "\n";
                echo "function resize_content_div()\n";
                echo "{ // resizes the index divs to fit page \n";
                echo "\tif (document.getElementById('index_title'))\n";
                echo "\t{\n";
                echo "\t\tvar head = document.getElementById('index_title');\n";
                echo "\t\tvar smallblocks = document.getElementById('index_small_blocks');\n";
                echo "\t\tvar blocks = document.getElementById('index_main_blocks');\n";
                echo "\t\t// blocks are hidden while loading to prevent blocks flying all over the place..\n";
                echo "\t\tsmallblocks.style.display = 'inline';\n";
                echo "\t\tblocks.style.display = 'inline';\n";

                echo "\t\tvar left = document.getElementById('index_main_blocks');\n";
                $my_width = 280;
                echo "\t\tvar browserWidth = Math.max(document.body.clientWidth, 200)-$my_width;\n";
                if ($BROWSERTYPE == "netscape") { // don't we love the netscape //
                        echo "\t\tvar cont = document.getElementById('container');\n";
                        echo "\t\tcont.style.width = (browserWidth+$my_width-6).toString()+'px';\n";
                        $my_width=20;
                } else if ($BROWSERTYPE == "msie") $my_width=-20;
                  else $my_width="0";

                echo "\t\thead.style.width = (browserWidth-($my_width)).toString()+'px';\n";
                echo "\t\tleft.style.width = (browserWidth-($my_width)).toString()+'px';\n";
                echo "\t}\n\t}\n";
                echo "\nwindow.onresize = function() {\n\tresize_content_div();\n}";

                $onload .="\n\tresize_content_div();";

                }
        } else { // individual page -> main code on page is triggered here..
                 // parameter defines which tab whould be checked.
                $onload.="\n\tresize_content_div(1);";
        }

        echo "\nwindow.onload = function() {\n\t";
        echo $onload, "\n";
		echo "if (window.sizeLines) sizeLines();\n";
        echo "}\n-->\n";
        echo "</script>\n";
        $footerscriptshown=true;
}
echo "</div> <!-- closing div id=\"content\" -->\n";//FIXME uncomment as soon as ready
echo "</td></tr></table>"; // Close table started in toplinks.html
echo "<div id=\"footer\" class=\"$TEXT_DIRECTION\">";
echo "\n\t<br /><div align=\"center\" style=\"width:99%;\">";
echo contact_links();
echo '<br /><a href="', PGV_PHPGEDVIEW_URL, '" target="_blank"><img src="', $PGV_IMAGE_DIR, '/', $PGV_IMAGES['gedview']['other'], '" width="100" height="45" border="0" alt="', PGV_PHPGEDVIEW, PGV_USER_IS_ADMIN? (" - " .PGV_VERSION_TEXT): "" , '" title="', PGV_PHPGEDVIEW , PGV_USER_IS_ADMIN? (" - " .PGV_VERSION_TEXT): "" , '" /></a><br />';
echo "\n\t<br />";
print_help_link("preview_help", "qm");
echo '<a href="', PGV_SCRIPT_NAME, '?view=preview&amp;', get_query_string(), '">', $pgv_lang['print_preview'], '</a>';
echo "<br />";
if ($SHOW_STATS || PGV_DEBUG) {
	echo execution_stats();
}
if (exists_pending_change()) {
	echo "<br />", $pgv_lang["changes_exist"], " <a href=\"javascript:;\" onclick=\"window.open('edit_changes.php', '_blank', 'width=600, height=500, resizable=1, scrollbars=1'); return false;\">", $pgv_lang["accept_changes"], "</a>\n";
}
echo "</div>";
echo "</div> <!-- close div id=\"footer\" -->\n";
?>
