<?php

/**
 * -----------------------------------------------------------------------------
 *  lightbox_print_media_row.php version 3.0a
 *
 *  Author Brian Holland 25 Jly 2007
 * -----------------------------------------------------------------------------
 *
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
    global $t, $edit, $SERVER_URL;

print "<li>" . "\n";
    //print dummy image if media is linked to a 'private' person
    if (!displayDetailsById($rowm['m_media'], 'OBJE') || FactViewRestricted($rowm['m_media'], $rowm['m_gedrec'])) {
        print "<table class=\"prvpic\"><tr><td align=\"center\" colspan=1>" . "\n";
        print "<img src=\"modules/lightbox/images/private.gif\" class=\"icon\" width=\"60\" height=\"80\" alt=\" Image Private \" /></img>" . "\n" ;
        print "</td></tr></table>" . "\n";
    }

    //print $rtype." ".$rowm["m_media"]." ".$pid;
    if (!displayDetailsById($rowm['m_media'], 'OBJE') || FactViewRestricted($rowm['m_media'], $rowm['m_gedrec'])) {
        //print $rowm['m_media']." no privacy ";
        return false;
    }

    $styleadd="";
    if ($rtype=='new') $styleadd = "change_new";
    if ($rtype=='old') $styleadd = "change_old";
    // NOTE Start printing the media details
    $thumbnail = thumbnail_file($rowm["m_file"], true, false, $pid);
    $isExternal = stristr($thumbnail,"://");

    $linenum = 0;



    // NOTE Get the title of the media
    if (showFactDetails("OBJE", $pid)) {
        $mediaTitle = $rowm["m_titl"];
        $subtitle = get_gedcom_value("TITL", 2, $rowm["mm_gedrec"]);
		
	// get the tooltip link for source
	$sour = get_gedcom_value("SOUR", 1, $rowm["m_gedrec"]);	
	$sourdesc = PrintReady(get_source_descriptor($sour));

	
        if (!empty($subtitle)) $mediaTitle = $subtitle;
            $mainMedia = check_media_depth($rowm["m_file"], "NOTRUNC");
        if ($mediaTitle=="") $mediaTitle = basename($rowm["m_file"]);

        if ($isExternal || file_exists(filename_decode($thumbnail))) {

            $mainFileExists = false;
            if ($isExternal || file_exists($mainMedia)) {
                $mainFileExists = true;
                $imgsize = findImageSize($mainMedia);
                $imgwidth = $imgsize[0]+40;
                $imgheight = $imgsize[1]+150;

                // Test for filetypes supported by lightbox at the moment ================
                if ( eregi("\.jpg",$rowm['m_file']) || eregi("\.jpeg",$rowm['m_file']) || eregi("\.gif",$rowm['m_file']) || eregi("\.png",$rowm['m_file']) ) {
					print "<table class=\"pic\"><tr>" . "\n";
					print "<td align=\"center\" colspan=1>". "\n";
					if ( eregi("1 SOUR",$rowm['m_gedrec'])) {
//						print "<a href=\"" . $mainMedia . "\" rel='lightbox[general]' title='" . $mediaTitle . "'\" onmouseover=\"Tip('&nbsp;" . $mediaTitle . "&nbsp;<br>&nbsp;Source : <a href=\'http://www.windmillway.f2s.com/genealogy/source.php?sid=S1\'><b><font color=#0000FF>&nbsp; Link &nbsp;</font></b><\/a>', OFFSETY, -30, OFFSETX, 5, CLICKCLOSE, true, DURATION, 8000, STICKY, true, PADDING, 8, BGCOLOR, '#f3f3f3', FONTSIZE, '8pt')\" >" . "\n";
						print "<a href=\"" . $mainMedia . "\" rel='lightbox[general]' title='" . $mediaTitle . "'\" onmouseover=\"Tip('&nbsp;" . $mediaTitle . "&nbsp;<br>&nbsp;Source : <a href=\'" . $SERVER_URL . "source.php?sid=" . $sour . "\'><b><font color=#0000FF>&nbsp;" . $sourdesc . "&nbsp;(" . $sour . ")</font></b><\/a>', OFFSETY, -30, OFFSETX, 5, CLICKCLOSE, true, DURATION, 8000, STICKY, true, PADDING, 5, BGCOLOR, '#f3f3f3', FONTSIZE, '8pt')\" >" . "\n";
					}else{
						print "<a href=\"" . $mainMedia . "\" rel='lightbox[general]' title='" . $mediaTitle . "'\" >" . "\n";					
					}
				// Or else use the pop-up window technique ==============================
				}else{
                     print "<table class=\"pic\" ><tr>" . "\n";
                     print "<td align=\"center\" colspan=1>" . "\n";
                     print "<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($mainMedia)."',$imgwidth, $imgheight);\" >";
                }
            }

// BH 		print "<img src=\"".$thumbnail."\" border=\"0\" align=\"" . ($TEXT_DIRECTION== "rtl"?"right": "left") . "\" class=\"thumbnail\"";
            print "<img src=\"".$thumbnail."\" height=80 border=\"0\" " ;

			if ($isExternal) print " width=\"".$THUMBNAIL_WIDTH."\"";
			if ( eregi("1 SOUR",$rowm['m_gedrec'])) {
				print " alt=\"" . PrintReady($mediaTitle) . "\" title=\"" . PrintReady($mediaTitle) . "\nSource info available\" />";
			}else{
				print " alt=\"" . PrintReady($mediaTitle) . "\" title=\"" . PrintReady($mediaTitle) . "\" />";
			}
			if ($mainFileExists) print "</a>" . "\n";

            print "</td></tr>" . "\n";

            if ( userCanEdit(getUserName()) && $edit=="1" ) {
				print "<tr><td align=\"center\" nowrap=\"nowrap\">". "\n";

                print "<a href=\"javascript:;\" onclick=\" return window.open('addmedia.php?action=editmedia&amp;pid=" . $rowm['m_media'] . "&amp;linktoid=" . $rowm["mm_gid"] . "', '_blank', 'top=50,left=50,width=600,height=600,resizable=1,scrollbars=1');\">";
                print "<img src=\"modules/lightbox/images/image_edit.gif\" title=\"" . $pgv_lang["lb_edit_media"] . "\" /></img></a>" . "\n" ;

                print "<a href=\"javascript:;\" onclick=\" return delete_record('$pid', 'OBJE', '" . $rowm['m_media'] . "');\">";
                print "<img src=\"modules/lightbox/images/image_delete.gif\" title=\"" . $pgv_lang["lb_delete_media"] . "\" /></img></a>" . "\n" ;

				print "<a href=\"mediaviewer.php?mid=" . $rowm["m_media"] . "\">";
				if ( eregi("1 SOUR",$rowm['m_gedrec'])) {				
					print "<img src=\"modules/lightbox/images/image_view.gif\" title=\"" . $pgv_lang["lb_view_media"] . "\n" . $pgv_lang["lb_source_avail"] . "\" /></img></a>" . "\n" ;
				}else{
					print "<img src=\"modules/lightbox/images/image_view.gif\" title=\"" . $pgv_lang["lb_view_media"] . "\" /></img></a>" . "\n" ;				
				}
                print "</td></tr>" . "\n";
            }else{
			}
            print "</table>" . "\n";
        }
/*
        if ($TEXT_DIRECTION=="rtl" && !hasRTLText($mediaTitle)) print "<i>&lrm;".PrintReady($mediaTitle);
//        else print "<i>".PrintReady($mediaTitle);
        $addtitle = get_gedcom_value("TITL:_HEB", 2, $rowm["mm_gedrec"]);
        if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:_HEB", 2, $rowm["m_gedrec"]);
        if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:_HEB", 1, $rowm["m_gedrec"]);
        if (!empty($addtitle)) print "<br />\n".PrintReady($addtitle);
            $addtitle = get_gedcom_value("TITL:ROMN", 2, $rowm["mm_gedrec"]);
        if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:ROMN", 2, $rowm["m_gedrec"]);
        if (empty($addtitle)) $addtitle = get_gedcom_value("TITL:ROMN", 1, $rowm["m_gedrec"]);
        if (!empty($addtitle)) print "<br />\n".PrintReady($addtitle);
//            print "</i>";
        if(empty($SEARCH_SPIDER)) {
//            print "</a>" . "\n" ;
        }
*/
    }

    print "</li>";
    print "\n\n";;
    return true;

// -----------------------------------------------------------------------------
// }
// -----------------------------------------------------------------------------

?>