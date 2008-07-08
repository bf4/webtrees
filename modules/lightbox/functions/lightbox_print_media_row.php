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
	
	// If Fact details can be shown --------------------------------------------------------------------------------------------
	if (showFactDetails("OBJE", $pid)) {
		
		//  Get the title of the media
		$media=Media::getInstance($rowm["m_media"]);
		$mediaTitle = $media->getFullName();
		
		$subtitle = get_gedcom_value("TITL", 2, $rowm["mm_gedrec"]);
		
		// If no title, use filename
		if (!empty($subtitle)) $mediaTitle = $subtitle;
			$mainMedia = check_media_depth($rowm["m_file"], "NOTRUNC");
		if ($mediaTitle=="") $mediaTitle = basename($rowm["m_file"]);		
		
		// Get the tooltip link for source
		$sour = get_gedcom_value("SOUR", 1, $rowm["m_gedrec"]);	
		
		// Avoid special character problems
		//make ready for RTL
		$mediaTitle = PrintReady(htmlspecialchars($mediaTitle));
		
		
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
		$tt_opts	.=	", CLOSEBTN, false";
		$tt_opts	.=	", CLOSEBTNCOLORS, ['#ff0000', '#ffffff', '#ffffff', '#ff0000']";
		$tt_opts	.=	", OFFSETX, -30";
		$tt_opts	.=	", OFFSETY, 110";
		$tt_opts	.=	", STICKY, true";
		$tt_opts	.=	", PADDING, 6";
		$tt_opts	.=	", CLICKCLOSE, true";
		$tt_opts	.=	", DURATION, 8000";
		$tt_opts	.=	", BGCOLOR, '#f3f3f3'";
		$tt_opts	.=	", JUMPHORZ, 'true' ";
		$tt_opts	.=	", JUMPVERT, 'false' ";
		$tt_opts	.=	", DELAY, 0";
		
		// Prepare Below Thumbnail  menu ----------------------------------------------------
			if ($TEXT_DIRECTION== "rtl") {
				$submenu_class			=	"submenuitem_rtl";
				$submenu_hoverclass		=	"submenuitem_hover_rtl";
			}else{
				$submenu_class			=	"submenuitem";
				$submenu_hoverclass		=	"submenuitem_hover";
			}
			
			$menu = array();
			// Make function htmlspecialchar_decode for older PHP versions ( < PHP 5.1.0 )
				if (!function_exists('htmlspecialchars_decode')) {
					function htmlspecialchars_decode($string,$style=ENT_COMPAT) {
						$translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
						if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
						return strtr($string,$translation);
					}
				}
			// Get the first 12 characters of the Media Title
				$mtitle = htmlspecialchars_decode(stripLRMRLM($mediaTitle), ENT_QUOTES);
				if (UTF8_strlen($mtitle)>12) $mtitle = UTF8_substr($mtitle, 0, 12).$pgv_lang["ellipsis"];
				$mtitle = htmlspecialchars($mtitle);
			// Continue menu construction
			$menu["label"] = "\n<img src=\"{$thumbnail}\" style=\"display:none;\" alt=\"\" title=\"\" />" . PrintReady($mtitle) . "</img>\n";
			$menu["labelpos"] = "right";
			$menu["icon"] = "";
			$menu["onclick"] = "";
			// If regular filetype (Lightbox)
			if ($file_type == "regular") {
				$menu["link"] = $mainMedia . "\" rel='clearbox[general_8]' rev=\"" . $rowm["m_media"] . "::" . $GEDCOM . "::" . PrintReady(strip_tags($mediaTitle)) .  "::" . htmlspecialchars($notes) . "\""; 
			// Else If url filetype (Lightbox)
			}elseif ($file_type == "url") {
				$menu["link"] = $mainMedia . "\" rel='clearbox(" . $LB_URL_WIDTH . "," . $LB_URL_HEIGHT . ",click)' rev=\"" . $rowm["m_media"] . "::" . $GEDCOM . "::" . PrintReady(strip_tags($mediaTitle)) . "::" . htmlspecialchars($notes) . "\"";
			// Else Other filetype (Pop-up Window)
			}else{
				// $menu["link"] = "<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($mainMedia)."',$imgwidth, $imgheight);\" rev=\"" . $rowm["m_media"] . "::" . $GEDCOM . "::" . $mediaTitle . "\""; 
			}
			$menu["class"] = "";
			$menu["hoverclass"] = "";
			$menu["flyout"] = "down";
			$menu["submenuclass"] = "submenu";
			$menu["items"] = array();
		// View Notes
		if ( eregi("1 NOTE",$rowm['m_gedrec']) ) {
			$submenu = array();
			$submenu["label"] = "&nbsp;&nbsp;" . $pgv_lang["lb_viewnotes"] . "&nbsp;&nbsp;";
			$submenu["labelpos"] = "right";
			$submenu["icon"] = "";
			// Notes Tooltip ----------------------------------------------------
			$submenu["onclick"]  = "TipTog(";
				// Contents of Notes 
				$submenu["onclick"] .= "'";
				$submenu["onclick"] .= "&lt;font color=#008800>&lt;b>" . $pgv_lang["notes"] . ":&lt;/b>&lt;/font>&lt;br />";
				// echo "<br />";
				$submenu["onclick"] .= $notes;
				$submenu["onclick"] .= "'";
				// Notes Tooltip Parameters
				$submenu["onclick"] .= $tt_opts;
			$submenu["onclick"] .= ");";
			$submenu["onclick"] .= "return false;\"";
			$submenu["link"] = "#";
			$submenu["class"] = $submenu_class;
			$submenu["hoverclass"] = $submenu_hoverclass;
			$menu["items"][] = $submenu;
		echo "\n";
		}
		//View Details
			$submenu = array();
			$submenu["label"] = "&nbsp;&nbsp;" . $pgv_lang["lb_viewdetails"] . "&nbsp;&nbsp;";
			$submenu["labelpos"] = "right";
			$submenu["icon"] = "";
			$submenu["onclick"] = "";
			$submenu["link"] = $SERVER_URL . "mediaviewer.php?mid=" . $rowm["m_media"] ;
			$submenu["class"] = $submenu_class;
			$submenu["hoverclass"] = $submenu_hoverclass;
			$menu["items"][] = $submenu;
		//View Source
		if (eregi("1 SOUR",$rowm['m_gedrec']) && displayDetailsById($sour, "SOUR")) {
			$submenu = array();
			$submenu["label"] = "&nbsp;&nbsp;" . $pgv_lang["lb_viewsource"] . "&nbsp;&nbsp;";
			$submenu["labelpos"] = "right";
			$submenu["icon"] = "";
			$submenu["onclick"] = "";
			$submenu["link"] = $SERVER_URL . "source.php?sid=" . $sour ; 
			$submenu["class"] = $submenu_class;
			$submenu["hoverclass"] = $submenu_hoverclass;
			$menu["items"][] = $submenu;
		}
		if ( PGV_USER_CAN_EDIT ) {
			// Edit Media
			$submenu = array();
			$submenu["label"] = "&nbsp;&nbsp;" . $pgv_lang["lb_editmedia"] . "&nbsp;&nbsp;";
			$submenu["labelpos"] = "right";
			$submenu["icon"] = "";
			$submenu["onclick"] = "return window.open('addmedia.php?action=editmedia&pid={$rowm['m_media']}&linktoid={$rowm['mm_gid']}', '_blank', 'top=50,left=50,width=600,height=700,resizable=1,scrollbars=1');";
			$submenu["link"] = "#";
			$submenu["class"] = $submenu_class;
			$submenu["hoverclass"] = $submenu_hoverclass;
			$menu["items"][] = $submenu;
			// Unlink Media
			$submenu = array();
			$submenu["label"] = "&nbsp;&nbsp;" . $pgv_lang["lb_unlinkmedia"] . "&nbsp;&nbsp;";
			$submenu["labelpos"] = "right";
			$submenu["icon"] = "";
			$submenu["onclick"] = "return delete_record('$pid', 'OBJE', '".$rowm['m_media']."');";
			$submenu["link"] = "#";
			$submenu["class"] = $submenu_class;
			$submenu["hoverclass"] = $submenu_hoverclass;
			$menu["items"][] = $submenu;
			// Copy
			/*
			$submenu = array();
			$submenu["label"] = $pgv_lang["copy"];
			$submenu["labelpos"] = "right";
			$submenu["icon"] = "";
			$submenu["onclick"] = "return copy_record('".$rowm['m_media']."', 'media');";
			$submenu["link"] = "#";
			$submenu["class"] = $submenu_class;
			$submenu["hoverclass"] = $submenu_hoverclass;
			$menu["items"][] = $submenu;
			*/
		}

		// Check if allowed to View media
		if ($isExternal || media_exists($thumbnail) && !FactViewRestricted($rowm['m_media'], $rowm['m_gedrec'])) {
			$mainFileExists = false;
			
			// Get Media info
			if ($isExternal || media_exists($mainMedia)) {
				$mainFileExists = true;
				$imgsize = findImageSize($mainMedia);
				$imgwidth = $imgsize[0]+40;
				$imgheight = $imgsize[1]+150;
		/*		
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
		*/		
				// Start Thumbnail Enclosure table 
				print "<table width=\"10px\" class=\"pic\" border=\"0\"><tr>" . "\n";
				print "<td align=\"center\" rowspan=\"2\" >";
				print "<img src=\"modules/lightbox/images/transp80px.gif\" height=\"100px\" alt=\"\"></img>";
				print "</td>". "\n";
				
				// Check for Notes associated media item
				if ($reorder!=1) {
				}else{
					// If reorder media has been clicked
					print "<td width=\"90% align=\"center\"><b><font size=\"2\" style=\"cursor:move;margin-bottom:2px;\">" . $rowm['m_media'] . "</font></b></td>";
					print "</tr>";
					}
				$item++;
				
				print "<td colspan=\"3\" valign=\"top\" align=\"center\" >". "\n";
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

			//View Edit Menu ----------------------------------
			//If reordering media
			if ( $reorder==1 ) {
				//Print Nothing
			}else{
				//Else if not reordering media, print View or View-Edit Menu
				print "<tr>";
				print "<td width=\"40px\"></td>";
				print "<td valign=\"bottom\" align=\"center\" nowrap=\"nowrap\">";
					print_menu($menu);
				print "</td>";
				print "<td width=\"40px\"></td>";
				print "</tr>" . "\n";
			}
			
			print "</table>" . "\n";
		}

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
