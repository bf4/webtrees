<?php
/**
 * Footer for Cloudy theme
 *
 * PhpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  John Finlay and others.  All rights reserved.
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

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

global $footerscriptshown, $THEME_DIR, $BROWSERTYPE;
if (!$footerscriptshown) {

        print <<<JSCRIPT
        <script type="text/javascript" language="javascript" >
<!--
function hidebar()
{ // hides the loading message
	loadbar = document.getElementById("ProgBar");
	if (loadbar) loadbar.style.display = "none";
}
JSCRIPT;
        $onload ="hidebar();";
        if ((stristr($SCRIPT_NAME,"individual") ==false ))
        {
                if (stristr($SCRIPT_NAME,"pedigree") or
                (stristr($SCRIPT_NAME,"descendancy")) or
                (stristr($SCRIPT_NAME,"timeline")) or
                (stristr($SCRIPT_NAME,"relationship")))
                {
                print "\n".<<<JSCRIPT
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
                if (stristr($SCRIPT_NAME,"pedigree") or stristr($SCRIPT_NAME,"descendancy"))
                { // pedigree and descendancy height
                        print "\t\ty = foot.offsetTop;\n";
                        //print "\t\tz = parseInt(y);\n";
                        print "\t\tz=(y-70);\n";
                        //print "\t\talert(y);\n";
                        print "\t\tcont.style.height=(z.toString()+'px');\n";

                } else if (strstr($SCRIPT_NAME,"timeline"))
                { // timeline height
                        global $endoffset;
                        if (!$endoffset) $endoffset=270;
                        print "\t\ty='".($endoffset)."px';\n";
                        print "\t\tcont.style.height=(y);\n";
                } else if (strstr($SCRIPT_NAME,"relationship"))
                { // relationship height and width
                        global $maxyoffset,$xoffset,$Dbwidth,$xs;
                        $xoffset += $Dbwidth+$xs;
                        print "\t\ty='".($maxyoffset-70)."px';\n";
                        print "\t\tcont.style.height=(y);\n";
                        // check if xoffset is lower then default screensize
                        print "\t\tx=".$xoffset.";\n";
                        print "\t\tif (x < (browserWidth))\n";
                        print "\t\t\tx= (browserWidth);";
                        print "\t\tcont.style.width=x.toString()+'px';\n";
                        print "\t\thead.style.width=x.toString()+'px';\n";
                }
                if (strstr($SCRIPT_NAME,"pedigree"))
                { // pedigree width
                        global $bwidth, $bxspacing, $PEDIGREE_GENERATIONS, $talloffset, $Darrowwidth;
                        $xoffset = ($PEDIGREE_GENERATIONS * ($bwidth+(2*$bxspacing))) + (2*$Darrowwidth);
                        if ($talloffset==0) { $xoffset = floor($xoffset /1.4); }
                        print "\t\tx=".$xoffset.";\n";
                        print "\t\tif (x < (browserWidth))\n";
                        print "\t\t\tx= (browserWidth);\n";
                        //print "alert(x);";
                        print "\t\tcont.style.width=(x).toString()+'px';\n";
                        print "\t\thead.style.width=(x).toString()+'px';\n";

                } // descendancy width
                if (strstr($SCRIPT_NAME,"descendancy"))
                {
                        global $maxxoffset;
                        $xoffset = ($maxxoffset+60);
                        print "\t\tx=".$xoffset.";\n";
                        print "\t\tif (x < (browserWidth))\n";
                        print "\t\t\tx= (browserWidth);\n";
                        print "\t\tcont.style.width=x.toString()+'px';\n";
                        print "\t\thead.style.width=x.toString()+'px';\n";
                } //
                print "\n\t}\n}\n";
        }  else if (stristr($SCRIPT_NAME,"index"))
        {
                print "\n";
                print "function resize_content_div()\n";
                print "{ // resizes the index divs to fit page \n";
                print "\tif (document.getElementById('index_title'))\n";
                print "\t{\n";
                print "\t\tvar head = document.getElementById('index_title');\n";
                print "\t\tvar smallblocks = document.getElementById('index_small_blocks');\n";
                print "\t\tvar blocks = document.getElementById('index_main_blocks');\n";
                print "\t\t// blocks are hidden while loading to prevent blocks flying all over the place..\n";
                print "\t\tsmallblocks.style.display = 'inline';\n";
                print "\t\tblocks.style.display = 'inline';\n";

                print "\t\tvar left = document.getElementById('index_main_blocks');\n";
                $my_width = 280;
                print "\t\tvar browserWidth = Math.max(document.body.clientWidth, 200)-$my_width;\n";
                if ($BROWSERTYPE == "netscape") { // don't we love the netscape //
                        print "\t\tvar cont = document.getElementById('container');\n";
                        print "\t\tcont.style.width = (browserWidth+$my_width-6).toString()+'px';\n";
                        $my_width=20;
                } else if ($BROWSERTYPE == "msie") $my_width=-20;
                  else $my_width="0";

                print "\t\thead.style.width = (browserWidth-($my_width)).toString()+'px';\n";
                print "\t\tleft.style.width = (browserWidth-($my_width)).toString()+'px';\n";
                print "\t}\n\t}\n";
                print "\nwindow.onresize = function() {\n\tresize_content_div();\n}";

                $onload .="\n\tresize_content_div();";

                }
        } else { // individual page -> main code on page is triggered here..
                 // parameter defines which tab whould be checked.
                $onload.="\n\tresize_content_div(1);";
        }

        print "\nwindow.onload = function() {\n\t";
        print $onload."\n";
		print "if (window.sizeLines) sizeLines();\n";
        print "}\n-->\n";
        print "</script>\n";
        $footerscriptshown=true;
}
print "</div> <!-- closing div id=\"content\" -->\n";//FIXME uncomment as soon as ready
print "</td></tr></table>"; // Close table started in toplinks.html
print "<div id=\"footer\" class=\"$TEXT_DIRECTION\">";
print "\n\t<br /><div align=\"center\" style=\"width:99%;\">";
print contact_links();
print '<br /><a href="'.PGV_PHPGEDVIEW_URL.'" target="_blank"><img src="'.$PGV_IMAGE_DIR.'/'.$PGV_IMAGES['gedview']['other'].'" width="100" height="45" border="0" alt="'.PGV_PHPGEDVIEW.' '.PGV_VERSION_TEXT.'" title="'.PGV_PHPGEDVIEW.' '.PGV_VERSION_TEXT.'" /></a><br />';
print "\n\t<br />";
print_help_link("preview_help", "qm");
print "<a href=\"$SCRIPT_NAME?view=preview&amp;".get_query_string()."\">".$pgv_lang["print_preview"]."</a>";
print "<br />";
if ($SHOW_STATS || (isset($DEBUG) && ($DEBUG==true))) print_execution_stats();
if ($buildindex) print " ".$pgv_lang["build_error"]."  <a href=\"editgedcoms.php\">".$pgv_lang["rebuild_indexes"]."</a>\n";
if (exists_pending_change()) {
	print "<br />".$pgv_lang["changes_exist"]." <a href=\"javascript:;\" onclick=\"window.open('edit_changes.php','_blank','width=600,height=500,resizable=1,scrollbars=1'); return false;\">".$pgv_lang["accept_changes"]."</a>\n";
}
print "</div>";
print "</div> <!-- close div id=\"footer\" -->\n";
?>
