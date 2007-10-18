<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PHPGedView Development Team
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
/**
 * print a media row in a table
 * @param string $rtype whether this is a 'new', 'old', or 'normal' media row... this is used to determine if the rows should be printed with an outline color
 * @param array $rowm        An array with the details about this media item
 * @param string $pid        The record id this media item was attached to
 */
// -----------------------------------------------------------------------------
// function lightbox_print_media_row($rtype, $rowm, $pid) {
// -----------------------------------------------------------------------------
$n=1;
    global $PGV_IMAGE_DIR, $PGV_IMAGES, $view, $MEDIA_DIRECTORY, $TEXT_DIRECTION;
    global $SHOW_ID_NUMBERS, $GEDCOM, $factarray, $pgv_lang, $THUMBNAIL_WIDTH, $USE_MEDIA_VIEWER;
    global $SEARCH_SPIDER;
    global $t, $n, $edit, $SERVER_URL, $reorder, $LB_AL_THUMB_LINKS, $note, $rowm;

	// If reorder media has been clicked
	if (isset($reorder) && $reorder==1) {
		print "<li class=\"facts_value\" style=\"border:0px;\" id=\"li_" . $rowm['m_media'] . "\" >";	
		print "<b><font size=2 style=\"cursor:move;margin-bottom:2px;\">" . $rowm['m_media'] . "</font></b>";
	//Else If reorder media has NOT been clicked
	}else{
		print "<li>" . "\n";
	}
	
    //print dummy image if media is linked to a 'private' person
    if (!displayDetailsById($rowm['m_media'], 'OBJE') || FactViewRestricted($rowm['m_media'], $rowm['m_gedrec'])) {
        print "<table><tr><br><td class=\"prvpic\" align=\"center\" colspan=1>" . "\n";
		print $pgv_lang["lb_private"];
//        print "<img src=\"modules/lightbox/images/private.gif\" class=\"icon\" width=\"60\" height=\"80\" alt=\" Image Private \" /></img>" . "\n" ;
        print "</td></tr></table>" . "\n";
		return false;
    }

    $styleadd="";
    if ($rtype=='new') $styleadd = "change_new";
    if ($rtype=='old') $styleadd = "change_old";
	
    // NOTE Start printing the media details
    $thumbnail = thumbnail_file($rowm["m_file"], true, false, $pid);
    $isExternal = stristr($thumbnail,"://");
    $linenum = 0;
    
    if (showFactDetails("OBJE", $pid)) {
	
		//  Get the title of the media
        $mediaTitle = $rowm["m_titl"];
        $subtitle = get_gedcom_value("TITL", 2, $rowm["mm_gedrec"]);

		// If no title, use filename
        if (!empty($subtitle)) $mediaTitle = $subtitle;
            $mainMedia = check_media_depth($rowm["m_file"], "NOTRUNC");
        if ($mediaTitle=="") $mediaTitle = basename($rowm["m_file"]);		
		
		// Get the tooltip link for source
		$sour = get_gedcom_value("SOUR", 1, $rowm["m_gedrec"]);	
		$sourdesc = PrintReady(get_source_descriptor($sour));

		// Avoid special character problems
		$mediaTitle =  addslashes($mediaTitle);
		//make ready for RTL
		$mediaTitle = PrintReady($mediaTitle);
		$sour1 = " - " . $sour ; 
		$sour2 = PrintReady($sour1);
		
		//text alignment for tooltip
		if ($TEXT_DIRECTION=="rtl") {
			$alignm = "right";
		}else{
			$alignm = "left";
		}
		
		// Get Media info

		if ($isExternal || media_exists($thumbnail)) {
            $mainFileExists = false;
            if ($isExternal || media_exists($mainMedia)) {
                $mainFileExists = true;
                $imgsize = findImageSize($mainMedia);
                $imgwidth = $imgsize[0]+40;
                $imgheight = $imgsize[1]+150;

                // For filetypes supported by lightbox at the moment ================
				if ( eregi("\.jpg",$rowm['m_file']) || eregi("\.jpeg",$rowm['m_file']) || eregi("\.gif",$rowm['m_file']) || eregi("\.png",$rowm['m_file']) || eregi("\.pdf",$rowm['m_file']) || eregi("http",$rowm['m_file']) ) {
					print "<table class=\"pic\"><tr>" . "\n";
					print "<td align=\"center\" colspan=1>". "\n";
					
					//If reordering media
					if ( $reorder==1 ) {
						// Do not show tooltip
						
					// Else if source info available and file = PDF or URL - Open with Lightbox URL,  and create tooltip link for source AND media details
					}else if (eregi("1 SOUR",$rowm['m_gedrec']) && (eregi("\.pdf",$rowm['m_file']) || eregi("http",$rowm['m_file'])) ) {  
						print 	"<a href=\"" . $mainMedia . "\" rel='clearbox(1200,700,click)' title=\"" . stripslashes($mediaTitle) . "\"\"
								onmouseover=\"Tip('" 
									. "&nbsp;" . $mediaTitle . ""
									. "<br>" 								
									. "&nbsp;" . $pgv_lang["lb_view_source_tip"] . "<a href=\'" 
									. $SERVER_URL . "source.php?sid=" . $sour . "\'><b><font color=#0000FF>&nbsp;" . $sourdesc . "&nbsp;" . $sour2 
									. "</font></b><\/a>" 
									. "<br>" 
									. "&nbsp;" . $pgv_lang["lb_view_details_tip"] . "<a href=\'" 
									. $SERVER_URL . "mediaviewer.php?mid=" . $rowm["m_media"] . "\'><b><font color=#0000FF>&nbsp;" . $rowm["m_media"] 
									. "</font></b><\/a>'," 
									. "TEXTALIGN, '" . $alignm . "', OFFSETY, -30, OFFSETX, 5, CLICKCLOSE, true, DURATION, 4000, STICKY, true, PADDING, 5, BGCOLOR, '#f3f3f3', FONTSIZE, '8pt'" 
								. ")\""
								. ">\n";								
								
					// Else if no source info available and file = PDF or URL - Open with Lightbox URL,  and create tooltip link for media details only
					}else if (!eregi("1 SOUR",$rowm['m_gedrec']) && (eregi("\.pdf",$rowm['m_file']) || eregi("http",$rowm['m_file'])) ) { 
						print 	"<a href=\"" . $mainMedia . "\" rel='clearbox(1200,700,click)' title=\"" . stripslashes($mediaTitle) . "\"\" 
								onmouseover=\"Tip('" 
									. "&nbsp;" . $mediaTitle . ""
									. "<br>"
									. "&nbsp;" . $pgv_lang["lb_view_details_tip"] . "<a href=\'"
									. $SERVER_URL . "mediaviewer.php?mid=" . $rowm["m_media"] . "\'><b><font color=#0000FF>&nbsp;" . $rowm["m_media"]
									. "</font></b><\/a>',"
									. "TEXTALIGN, '" . $alignm . "', OFFSETY, -30, OFFSETX, 5, CLICKCLOSE, true, DURATION, 4000, STICKY, true, PADDING, 5, BGCOLOR, '#f3f3f3', FONTSIZE, '8pt'" 
								. ")\"" 
								. ">\n";								
					

					
					// Else If source info available, - Open with Lightbox normal,  and create tooltip link for source AND media details
					}else if (eregi("1 SOUR",$rowm['m_gedrec'])) {

						print	"<a href=\"" . $mainMedia . "\" rel='clearbox[general]' title=\"" . stripslashes($mediaTitle) . "\"\" 
								onmouseover=\"Tip('" 
									. "&nbsp;" . $mediaTitle . ""
									. "<br>"
									
									. "&nbsp;" . $pgv_lang["lb_view_source_tip"] . "<a href=\'" 
									. $SERVER_URL . "source.php?sid=" . $sour . "\'><b><font color=#0000FF>&nbsp;" . $sourdesc . "&nbsp;" . $sour2  
									. "</font></b><\/a>" 
									
									. "<br>" 
									. "&nbsp;" . PrintReady($pgv_lang["lb_view_details_tip"]) . "<a href=\'" 
									. $SERVER_URL . "mediaviewer.php?mid=" . $rowm["m_media"] . "\'><b><font color=#0000FF>&nbsp;" . $rowm["m_media"] 
									. "</font></b><\/a>'," 
									. "TEXTALIGN, '" . $alignm . "', OFFSETY, -30, OFFSETX, 5, CLICKCLOSE, true, DURATION, 4000, STICKY, true, PADDING, 5, BGCOLOR, '#f3f3f3', FONTSIZE, '8pt'" 
								. ")\""
								. ">\n";
		
					
					// If no source info available - Open with Lightbox normal, and create tooltip link for media details only
					}else if (!eregi("1 SOUR",$rowm['m_gedrec'])) { 
						print 	"<a href=\"" . $mainMedia . "\" rel='clearbox[general]' title=\"" . stripslashes($mediaTitle) . "\"\"
								onmouseover=\"Tip('" 
									. "&nbsp;" . $mediaTitle . ""
									. "<br>"
									. "&nbsp;" . $pgv_lang["lb_view_details_tip"] . "<a href=\'"
									. $SERVER_URL . "mediaviewer.php?mid=" . $rowm["m_media"] . "\'><b><font color=#0000FF>&nbsp;" . $rowm["m_media"]
									. "</font></b><\/a>',"
									. "TEXTALIGN, '" . $alignm . "', OFFSETY, -30, OFFSETX, 5, CLICKCLOSE, true, DURATION, 4000, STICKY, true, PADDING, 5, BGCOLOR, '#f3f3f3', FONTSIZE, '8pt'" 
								. ")\"" 
								. ">\n";

					}else{
						// Do nothing
					}		

					
				// Else For filetypes NOT supported by lightbox at the moment, use the pop-up window technique ==============================
				}else{
					print "<table class=\"pic\" ><tr>" . "\n";
					print "<td align=\"center\" colspan=1>" . "\n";
					
					//If reordering media
					if ( $reorder==1 ) {
						// Do not show tooltip
						
					// Else if source info available and file is not supported by Lightbox - Open with Pop-up, and create tooltip link for source AND media details
					}else if (eregi("1 SOUR",$rowm['m_gedrec'])) { 
						print 	"<a href=\"javascript:;\" 
								onclick=\"return openImage('".rawurlencode($mainMedia)."',$imgwidth, $imgheight);\" title=\"" . stripslashes($mediaTitle) . "\"\" 
								onmouseover=\"Tip('" 
									. "&nbsp;" . $mediaTitle . ""
									. "<br>" 								
									. "&nbsp;" . $pgv_lang["lb_view_source_tip"] . "<a href=\'" 
									. $SERVER_URL . "source.php?sid=" . $sour . "\'><b><font color=#0000FF>&nbsp;" . $sourdesc . "&nbsp;" . $sour2 
									. "</font></b><\/a>" 
									. "<br>" 
									. "&nbsp;" . $pgv_lang["lb_view_details_tip"] . "<a href=\'" 
									. $SERVER_URL . "mediaviewer.php?mid=" . $rowm["m_media"] . "\'><b><font color=#0000FF>&nbsp;" . $rowm["m_media"] 
									. "</font></b><\/a>'," 
									. "TEXTALIGN, '" . $alignm . "', OFFSETY, -30, OFFSETX, 5, CLICKCLOSE, true, DURATION, 4000, STICKY, true, PADDING, 5, BGCOLOR, '#f3f3f3', FONTSIZE, '8pt'" 
								. ")\""
								. ">\n";
							
					// Else if no source info available and file is not supported by Lightbox - Open with Pop-up, and create tooltip link for media details only
					}else if (!eregi("1 SOUR",$rowm['m_gedrec'])) { 
						print 	"<a href=\"javascript:;\" 
								onclick=\"return openImage('".rawurlencode($mainMedia)."',$imgwidth, $imgheight);\" title=\"" . stripslashes($mediaTitle) . "\"\"
								onmouseover=\"Tip('" 
									. "&nbsp;" . $mediaTitle . ""
									. "<br>"
									. "&nbsp;" . $pgv_lang["lb_view_details_tip"] . "<a href=\'"
									. $SERVER_URL . "mediaviewer.php?mid=" . $rowm["m_media"] . "\'><b><font color=#0000FF>&nbsp;" . $rowm["m_media"]
									. "</font></b><\/a>',"
									. "TEXTALIGN, '" . $alignm . "', OFFSETY, -30, OFFSETX, 5, CLICKCLOSE, true, DURATION, 4000, STICKY, true, PADDING, 5, BGCOLOR, '#f3f3f3', FONTSIZE, '8pt'" 
								. ")\"" 
								. ">\n";									
					
					// Else (when NOT reordering media) 
					}else{
						// Do nothing
					}
				}
            }
			
			// Print the Thumbnail if Image, Common Thumbnail if URL
// LB 		print "<img src=\"".$thumbnail."\" border=\"0\" align=\"" . ($TEXT_DIRECTION== "rtl"?"right": "left") . "\" class=\"thumbnail\"";
			if (eregi("http",$rowm['m_file'])) {
				print "<br><img src=\"" . $MEDIA_DIRECTORY . "thumbs/urls/URL.jpg \" height=80 border=\"0\" " ;
			}else{
				// Ceck for Notes associated with media item
			    if ( eregi("1 NOTE",$rowm['m_gedrec']) ) {
					$note[$n]  = $pgv_lang["note"] . " " . ($n+1) . "";
					print "<font size=1>" . $note[$n] . "</font><br>";
//					$note_text[$n] = print_fact_notes($rowm["m_gedrec"], 1);						
					$n++;
				
				}else{
					print "<br>";
				}			
				print "<img src=\"" .$thumbnail . "\" height=80 border=\"0\" " ;
			}
			// These next lines disable the extra IE Browser tooltip. (It has to be done manually in Firefox but this is not recommended)
				// How to turn off tooltip in firefox: (Not recommended)
					// Type "about:config" in the Firefox address box and hit enter. 
					// It shows a list of configurable features. Find "browser.chrome.toolbar_tips" item and double-click to turn it value to false.
				// If you perform the above, ALL Firefox chrome tooltips will be turned off .. This is not recommended)
				// I will try to find a better way of removing the doubled tooltips ... Brian Holland .. Lightbox developer)
			if ( eregi("1 SOUR",$rowm['m_gedrec'])) {
				print " alt=\"" . PrintReady($mediaTitle) . "\" title=\"" . PrintReady($mediaTitle) . "\nSource info is available\" />";
			}else{
				print " alt=\"" . PrintReady($mediaTitle) . "\" title=\"" . PrintReady($mediaTitle) . "\" />";
			}
			
			// Close anchor
			if ($mainFileExists) print "</a>" . "\n";
            print "</td></tr>" . "\n";

			//Editing Icons
			//If reordering media
			if ( $reorder==1 ) {
				//Do nothing
				
			// Else if an editor, show editing icons
            }elseif ( userCanEdit(getUserName()) && $edit=="1" ) {
				print "<tr><td align=\"center\" nowrap=\"nowrap\">". "\n";
				
				// Edit Media Item Details
                print "<a href=\"javascript:;\" onclick=\" return window.open('addmedia.php?action=editmedia&amp;pid=" . $rowm['m_media'] . "&amp;linktoid=" . $rowm["mm_gid"] . "', '_blank', 'top=50,left=50,width=600,height=600,resizable=1,scrollbars=1');\" ";
				print " title=\"" . $pgv_lang["lb_edit_media"] . "\">";
				if ($LB_AL_THUMB_LINKS == "text") {
					print "<font size=2>" . $pgv_lang["edit"] . "</font>";
				}else{	
					print "<img src=\"modules/lightbox/images/image_edit.gif\" title=\"" . $pgv_lang["lb_edit_media"] . "\" /></img>";
				}
				print "</a>" . "\n" ;
				
				print "&nbsp;&nbsp;&nbsp;";

				// Remove Media Item from individual
                print "<a href=\"javascript:;\" onclick=\" return delete_record('$pid', 'OBJE', '" . $rowm['m_media'] . "');\" ";
				print " title=\"" . $pgv_lang["lb_delete_media"] . "\">";
				if ($LB_AL_THUMB_LINKS == "text") {
					print "<font size=2>" . $pgv_lang["remove"] . "</font>";
				}else{	
					print "<img src=\"modules/lightbox/images/image_delete.gif\" title=\"" . $pgv_lang["lb_delete_media"] . "\" /></img>";
				}
				print "</a>" . "\n" ;
/*
				// View Media Item details
				print "<a href=\"mediaviewer.php?mid=" . $rowm["m_media"] . "\">";
				if ( eregi("1 SOUR",$rowm['m_gedrec'])) {				
					print "<img src=\"modules/lightbox/images/image_view.gif\" title=\"" . $pgv_lang["lb_view_media"] . "\n" . $pgv_lang["lb_source_avail"] . "\" /></img></a>" . "\n" ;
				}else{
					print "<img src=\"modules/lightbox/images/image_view.gif\" title=\"" . $pgv_lang["lb_view_media"] . "\" /></img></a>" . "\n" ;				
				}
*/
                print "</td></tr>" . "\n";
				
            }else{
				//Do nothing
			}

            print "</table>" . "\n";
        }

      
		
//  -----------------------------------------------------------------------------------------------------------------------------	
/*
if ($TEXT_DIRECTION=="rtl" && !hasRTLText($sour1)) print "".PrintReady($sour1);
else print "<i>&lrm;".PrintReady($sour1);
*/
	
/* 
		if ($TEXT_DIRECTION=="rtl" && !hasRTLText($mediaTitle)) print "<i>&lrm;".PrintReady($mediaTitle);
//        		else print "<i>".PrintReady($mediaTitle);
			$addtitle = get_gedcom_value("TITL:_HEB", 2, $rowm["mm_gedrec"]);
		if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:_HEB", 2, $rowm["m_gedrec"]);
		if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:_HEB", 1, $rowm["m_gedrec"]);
		if (!empty($addtitle)) print "<br />\n".PrintReady($addtitle);
			$addtitle = get_gedcom_value("TITL:ROMN", 2, $rowm["mm_gedrec"]);
		if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:ROMN", 2, $rowm["m_gedrec"]);
		if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:ROMN", 1, $rowm["m_gedrec"]);
		if (!empty($addtitle)) print "<br />\n".PrintReady($addtitle);
//			print "</i>";
		if(empty($SEARCH_SPIDER)) {
//			print "</a>" . "\n" ;
		}
*/
//  -----------------------------------------------------------------------------------------------------------------------------

    } // NOTE End get the title of the media

    print "</li>";
    print "\n\n";;
    return true;

// -----------------------------------------------------------------------------
// }
// -----------------------------------------------------------------------------

?>