<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox 4.1
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 to 2008  PGV Development Team.  All rights reserved.
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
	
	global $PGV_IMAGE_DIR, $PGV_IMAGES, $view, $MEDIA_DIRECTORY, $TEXT_DIRECTION;
	global $SHOW_ID_NUMBERS, $GEDCOM, $factarray, $pgv_lang, $THUMBNAIL_WIDTH, $USE_MEDIA_VIEWER;
	global $SEARCH_SPIDER;
	global $t, $n, $item, $items, $p, $edit, $SERVER_URL, $reorder, $LB_AL_THUMB_LINKS, $note, $rowm;
	global $LB_URL_WIDTH, $LB_URL_HEIGHT, $order1, $sort_i, $notes, $q, $LB_TT_BALLOON ;
	

	
	//If media is linked to a 'private' person
	if (!displayDetailsById($rowm['m_media'], 'OBJE') || FactViewRestricted($rowm['m_media'], $rowm['m_gedrec'])) {
		$item++;
		return false;
	}else{
	// Media is NOT linked to private person
		// If reorder media has been clicked
		if (isset($reorder) && $reorder==1) {
			print "<li class=\"facts_value\" style=\"border:0px;\" id=\"li_" . $rowm['m_media'] . "\" >";	
		// Else If reorder media has NOT been clicked
		// Highlight Album Thumbnails - Changed=new (blue), Changed=old (red), Changed=no (none)
		}else if ($rtype=='new'){
			print "<li class=\"li_new\">" . "\n";
		}else if ($rtype=='old'){
			print "<li class=\"li_old\">" . "\n";
		}else{
			print "<li class=\"li_norm\">" . "\n";
		}
	}
	
	// Add blue or red borders
	$styleadd="";
	if ($rtype=='new') $styleadd = "change_new";
	if ($rtype=='old') $styleadd = "change_old";
	
	// NOTE Start printing the media details
	$thumbnail = thumbnail_file($rowm["m_file"], true, false, $pid);
	$isExternal = isFileExternal($thumbnail);
	$linenum = 0;
	
	// If Fact details can be shown --------------------------------------------------------------------------------------------
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
		//make ready for RTL
		$mediaTitle = PrintReady(htmlspecialchars($mediaTitle));
		$sour1 = " - " . $sour ; 
		$sour2 = PrintReady($sour1);
		
		
		//Get media item Notes
		$haystack = $rowm["m_gedrec"];
		$needle   = "1 NOTE";
		$before   = substr($haystack, 0, strpos($haystack, $needle));
		$after    = substr(strstr($haystack, $needle), strlen($needle)); 
		$worked   = ereg_replace("1 NOTE", "1 NOTE<br />", $after);
		$final    = $before.$needle.$worked;
		$notes    = PrintReady(htmlspecialchars(addslashes(print_fact_notes($final, 1, true, true))));
		
		
		//Get media item Notes
		/*
		$notes=array();
		for ($i=1; ; ++$i) {
		$note=get_sub_record(1, '1 NOTE', $rowm["m_gedrec"], $i);
			if ($note) {
				$notes[]=PrintReady(htmlspecialchars(addslashes($note)));
			} else {
				break;
			}
		}
		$notes=join('<br />', $notes);
		$notes=ereg_replace("\n", "", $notes);
		$notes=ereg_replace("1 NOTE ", "", $notes);
		$notes=ereg_replace("2 CONT ", "<br />", $notes);
		*/
		
		//text alignment for Tooltips
		if ($TEXT_DIRECTION=="rtl") {
			$alignm = "right";
			$left	= "true";
		}else{
			$alignm = "left";
			$left	= "false";
		}
		
		// Tooltip Options
		$tt_opts	 =	", BALLOON," . $LB_TT_BALLOON ;
		$tt_opts	.=	", LEFT," . $left . "";
		$tt_opts	.=	", ABOVE, true";
		$tt_opts	.=	", TEXTALIGN, '" . $alignm . "'";
		$tt_opts	.=	", WIDTH, -480 ";
		$tt_opts	.=	", BORDERCOLOR, ''";
		$tt_opts	.=	", TITLEBGCOLOR, ''";
		$tt_opts	.=	", CLOSEBTNTEXT, 'X'";
		$tt_opts	.=	", CLOSEBTN, true";
		$tt_opts	.=	", CLOSEBTNCOLORS, ['#ff0000', '#ffffff', '#ffffff', '#ff0000']";
		$tt_opts	.=	", OFFSETX, -10";
		$tt_opts	.=	", STICKY, true";
		$tt_opts	.=	", PADDING, 6";
		$tt_opts	.=	", CLICKCLOSE, true";
		$tt_opts	.=	", BGCOLOR, '#f3f3f3'";
		$tt_opts	.=	", JUMPHORZ, 'true' ";
		$tt_opts	.=	", DELAY, 0";
		
		// Check if allowed to View media
		if ($isExternal || media_exists($thumbnail) && !FactViewRestricted($rowm['m_media'], $rowm['m_gedrec'])) {
			$mainFileExists = false;
			
			// Get Media info
			if ($isExternal || media_exists($mainMedia)) {
				$mainFileExists = true;
				$imgsize = findImageSize($mainMedia);
				$imgwidth = $imgsize[0]+40;
				$imgheight = $imgsize[1]+150;
				
				// Check Filetype of media item ( Regular, URL, or Not supported by lightbox at the moment )
				// Regular ----------------------------------
				if (eregi("\.jpg" ,$rowm['m_file']) || 
					eregi("\.jpeg",$rowm['m_file']) || 
					eregi("\.gif" ,$rowm['m_file']) || 
					eregi("\.png" ,$rowm['m_file'])  
					) 
				{	
					$file_type = "regular";
				// URL ----------------------------------
				}else if(eregi("http" ,$rowm['m_file']) || 
						 eregi("\.pdf",$rowm['m_file']) 
						) 
				{	
					$file_type = "url";
				// Other ------------------------------
				}else{
					$file_type = "other";
				}
				
				// Start Thumbnail Enclosure table 
				print "<table class=\"pic\" border=\"0\"><tr>" . "\n";
				print "<td align=\"center\" rowspan=\"2\" >";
				print "<img src=\"modules/lightbox/images/transp80px.gif\" height=\"100px\" alt=\"\"></img>";
				print "</td>". "\n";
				
				// Check for Notes associated media item
				if ($reorder!=1) {
					// If reorder media has NOT been clicked
					print "<td width=\"45%\" valign=\"top\" align=\"center\" >". "\n";
					if ( eregi("1 NOTE",$rowm['m_gedrec']) ) {
						// Print Note and number above thumbnail
						print "<a href=\"#\" ";
						// Notes Tooltip ----------------------------------------------------
						print "onclick=\"Tip(";
							// Contents of Notes 
							echo "'";
								if ($TEXT_DIRECTION== "rtl") {
									echo "&lt;font color=#008800>&lt;b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $pgv_lang["notes"] . ":&lt;/b>&lt;/font>&lt;br />";
								}else{
									echo "&lt;font color=#008800>&lt;b>" . $pgv_lang["notes"] . ":&lt;/b>&lt;/font>&lt;br >";
								}
								// echo "<br />";
								echo $notes;
							echo "'";
							// Notes Tooltip Parameters
							print $tt_opts;
						print ");";
						print "return false;\"";
						if ($reorder==1) {
							// print "onmouseout=\"tt_HideInit()\"";
							print "onmouseout=\"UnTip();\"";
						}
						// End Notes Tooltip --------------------------------------------------
						print ">\n";
						print "<font size=\"1\">" . $pgv_lang["notes"] . "</font>";
						print "</a>";
						// print "<br />";
						$items[$n+1]= $item+1;
						$n++;
					//  Else if no note available
					}else{
							print "<font size=\"1\">&nbsp;</font>";
					}
					print "</td>";
				}else{
					// If reorder media has been clicked
					print "<td width=\"90% align=\"center\"><b><font size=\"2\" style=\"cursor:move;margin-bottom:2px;\">" . $rowm['m_media'] . "</font></b></td>";
				}
				$item++;
				
				
				// Details Tooltip --------------------------------
				if ($reorder!=1) {
					// If reorder media has NOT been clicked
					print "<td width=\"45%\" valign=\"top\" align=\"center\" >". "\n";
					print "<a href=\"#\" ";
					print "onclick =\"Tip(";
						// Contents of Details Tooltip 
						print "'";
							echo "&lt;center>&lt;font color=#008800>&lt;b>&nbsp;&nbsp;&nbsp;&nbsp;" . $pgv_lang["lb_details"] . ":&nbsp;&nbsp;&nbsp;&nbsp;&lt;/b>&lt;/font>&lt;/center>";
							// Title --------------------------------
							print addslashes($mediaTitle);
							print "&lt;br />";
							// Source ----------------------------
							if (eregi("1 SOUR",$rowm['m_gedrec'])) {
								print $pgv_lang["lb_view_source_tip"] ;
								print "&lt;a href=\'" . $SERVER_URL . "source.php?sid=" . $sour . "\'>";
								print "&lt;b>&lt;font color=#0000FF>&nbsp;" . $sourdesc . "&nbsp;" . $sour2 . "&lt;/font>&lt;/b>";
								print "&lt;\/a>"; 
								print "&lt;br />";
							}
							// Details -----------------------------
								print PrintReady($pgv_lang["lb_view_details_tip"]);
								print "&lt;a href=\'" . $SERVER_URL . "mediaviewer.php?mid=" . $rowm["m_media"] . "\'>";
								print "&lt;b>&lt;font color=#0000FF>&nbsp;" . $rowm["m_media"] . "&lt;/font>&lt;/b>";
								print "&lt;\/a>";
						print "'";
						// Details Tooltip parameters ----------------------------------
						print $tt_opts;
					print ");";
					// End Details Tooltip ----------------------------------
					print "return false;\"";
					print ">\n";
					// Link Text ------------------------
					print "<font size=\"1\">";
					print $pgv_lang["lb_details"];
					print "</font>";
					
					print "</a>";
					print "</td>";
				}
				
				print "</tr><tr>";
				
				print "<td colspan=\"2\" valign=\"top\" align=\"center\" >". "\n";
				
				//If reordering media, do NOT Enable Lightbox nor show thumbnail tooltip
				if ( $reorder==1 ) {
				// Else Enable Lightbox (Or popup) and show thumbnail tooltip ----------
				}else{
					// If regular filetype (Lightbox)
					if ($file_type == "regular") {
						print	"<a href=\"" . $mainMedia . "\" rel='clearbox[general]' rev=\"" . $rowm["m_media"] . "::" . $GEDCOM . "::" . PrintReady(strip_tags($mediaTitle)) .  "::" . htmlspecialchars($notes) . "\""; 
					// Else If url filetype (Lightbox)
					}elseif ($file_type == "url") {
						print 	"<a href=\"" . $mainMedia . "\" rel='clearbox(" . $LB_URL_WIDTH . "," . $LB_URL_HEIGHT . ",click)' rev=\"" . $rowm["m_media"] . "::" . $GEDCOM . "::" . PrintReady(strip_tags($mediaTitle)) . "::" . htmlspecialchars($notes) . "\"";
					// Else Other filetype (Pop-up Window)
					}else{
						print 	"<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($mainMedia)."',$imgwidth, $imgheight);\" rev=\"" . $rowm["m_media"] . "::" . $GEDCOM . "::" . $mediaTitle . "\""; 
					}
					print ">\n";
				}
			} // End If media is external or media_exists($mainmedia)
			
			// LB 	print "<img src=\"".$thumbnail."\" border=\"0\" align=\"" . ($TEXT_DIRECTION== "rtl"?"right": "left") . "\" class=\"thumbnail\"";
			
			// Now finally print the thumbnail ----------------------------------
			// If Plain URL Print the Common Thumbnail  
			if (eregi("http",$rowm['m_file']) && !eregi("\.jpg",$rowm['m_file']) && !eregi("\.jpeg",$rowm['m_file']) && !eregi("\.gif",$rowm['m_file']) && !eregi("\.png",$rowm['m_file'])) {
				print "<img src=\"{$MEDIA_DIRECTORY}thumbs/urls/URL.jpg\" height=\"80\" border=\"0\" " ;
			// Else Print the Regular Thumbnail if associated with an image, 
			}else{
				$browser = $_SERVER['HTTP_USER_AGENT']; 
				if(strstr($browser,"MSIE")) {
					$height = 80;
				}else{
					$height = 78;
				}			
				$size = findImageSize($thumbnail);
				if ($size[1]<$height) $height = $size[1];
				print "<img src=\"{$thumbnail}\" height=\"{$height}\" border=\"0\" " ;
			}
			
			// print browser tooltips associated with image ----------------------------------------------
			print " alt=\"\" title=\"" . Printready(strip_tags($mediaTitle)) . "\"  />";
			
			// Close anchor --------------------------------------------------------------
			if ($mainFileExists) print "</a>" . "\n";
			print "</td></tr>" . "\n";

			// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------
			//Editing Icons
			
			//If reordering media
			if ( $reorder==1 ) {

			// Else if an editor, show editing icons
			}elseif ( PGV_USER_CAN_EDIT && $edit=="1" ) {
				print "<tr><td></td><td colspan=\"2\" valign=\"bottom\" align=\"center\" nowrap=\"nowrap\">". "\n";
				
				// Edit Media Item Details
				print "<a href=\"javascript:;\" onclick=\" return window.open('".encode_url("addmedia.php?action=editmedia&pid={$rowm['m_media']}&linktoid={$rowm['mm_gid']}")."', '_blank', 'top=50,left=50,width=600,height=600,resizable=1,scrollbars=1');\" ";
				print " title=\"" . $pgv_lang["lb_edit_media"] . "\">";
				if ($LB_AL_THUMB_LINKS == "text") {
					print "<font size=\"2\">" . $pgv_lang["edit"] . "</font>";
				}else{	
					print "<img src=\"modules/lightbox/images/image_edit.gif\" title=\"{$pgv_lang['lb_edit_media']}\" alt=\"{$pgv_lang['lb_edit_media']}\" />";
				}
				print "</a>" . "\n" ;
				
				print "&nbsp;&nbsp;&nbsp;";
				
				// Remove Media Item from individual
				print "<a href=\"javascript:;\" onclick=\" return delete_record('$pid', 'OBJE', '" . $rowm['m_media'] . "');\" ";
				print " title=\"" . $pgv_lang["lb_delete_media"] . "\">";
				if ($LB_AL_THUMB_LINKS == "text") {
					print "<font size=\"2\">" . $pgv_lang["remove"] . "</font>";
				}else{	
					print "<img src=\"modules/lightbox/images/image_delete.gif\" title=\"{$pgv_lang['lb_delete_media']}\" alt=\"{$pgv_lang['lb_delete_media']}\" />";
				}
				print "</a>" . "\n" ;

				print "</td></tr>" . "\n";
				
			}else{//Do nothing
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
		// else print "<i>".PrintReady($mediaTitle);
		$addtitle = get_gedcom_value("TITL:_HEB", 2, $rowm["mm_gedrec"]);
		if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:_HEB", 2, $rowm["m_gedrec"]);
		if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:_HEB", 1, $rowm["m_gedrec"]);
		if (!empty($addtitle)) print "<br />\n".PrintReady($addtitle);
		$addtitle = get_gedcom_value("TITL:ROMN", 2, $rowm["mm_gedrec"]);
		if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:ROMN", 2, $rowm["m_gedrec"]);
		if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:ROMN", 1, $rowm["m_gedrec"]);
		if (!empty($addtitle)) print "<br />\n".PrintReady($addtitle);
		//	print "</i>";
			if(empty($SEARCH_SPIDER)) {
		//		print "</a>" . "\n" ;
			}
		*/
		//  -----------------------------------------------------------------------------------------------------------------------------
		
	} // NOTE End If Show fact details
	
	$media_data = $rowm['m_media']; 
	print "<input type=\"hidden\" name=\"order1[$media_data]\" value=\"$sort_i\" />" . "\n";
	$sort_i++;
		
    print "</li>";
    print "\n\n";;
    return true;

// -----------------------------------------------------------------------------
// }
// -----------------------------------------------------------------------------

?>
