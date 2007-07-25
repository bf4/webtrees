<script language="Javascript">
<!--
   function album_help(OPTS) {
   var win01 = window.open(
   <?php if ($pgv_language[$LANGUAGE] == "languages/lang.fr.php") { ?>
   "modules/lightbox/languages/help_fr.php?"+OPTS, "win01", "resizable=1, scrollbars=1, HEIGHT=780, WIDTH=500 ");
   <?php }else{ ?>
   "modules/lightbox/languages/help_en.php?"+OPTS, "win01", "resizable=1, scrollbars=1, HEIGHT=780, WIDTH=500 ");
   <?php } ?>
   win01.focus()
   }

   function album_add() {
   var win01 = window.open(
   "addmedia.php?action=showmediaform&linktoid=<?php print $pid; ?>", "win01", "resizable=1, scrollbars=1, top=50, HEIGHT=780, WIDTH=600 ");
   win01.focus()
   }

      function album_link() {
   var win01 = window.open(
   "inverselink.php?linktoid=<?php print $pid; ?>&linkto=person", "win01", "resizable=1, scrollbars=1, top=50, HEIGHT=200, WIDTH=600 ");
   win01.focus()
   }
-->
</script>

<?php
require( "modules/lightbox/".$pgv_language["english"]);
if (file_exists( "modules/lightbox/".$pgv_language[$LANGUAGE])) require  "modules/lightbox/".$pgv_language[$LANGUAGE];

	//Lightbox-Album header Links
	
		//Help page
        print "&nbsp;&nbsp;&nbsp;" ;
        print "<a href=\"javascript: album_help()\" title=\"" . $pgv_lang["page_help"] . "\" >" ;
        print "<img src=\"".$PGV_IMAGE_DIR."/small/help.gif\" class=\"icon\" title=\"" . $pgv_lang["page_help"] . "\" />" ;
        print $pgv_lang["page_help"];
        print "</a>" ;
		
		//Slide Show
		print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
		print "<a href=\"modules/lightbox/images/slideshow.jpg\" rel=\"clearbox[general,6,start]\" title=\"" . $pgv_lang["lb_slide_show"] . "\">"; 
        print "<img src=\"modules/lightbox/images/images.gif\" class=\"icon\"  title=\"" . $pgv_lang["lb_slide_show"] . "\" />" ;
		print " " . $pgv_lang["lb_slide_show"]; 
		print '</a>';  
		
		//Add a new multimedia object
        if ( userCanEdit(getUserName()) ) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=\"javascript: album_add()\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" > ";
            print "<img src=\"modules/lightbox/images/image_add.gif\" class=\"icon\" title=\"" . $pgv_lang["lb_add_media_full"] . "\" />" ;
            print " " . $pgv_lang["add_obje"] ;
            print " </a> ";
        }else{
        }
		
		//Link to an existing item
        if ( userCanEdit(getUserName()) ) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=\"javascript: album_link()\" title=\"" . $pgv_lang["lb_link_media_full"] . "\" > ";
            print "<img src=\"modules/lightbox/images/image_link.gif\" class=\"icon\" title=\" " . $pgv_lang["lb_link_media_full"] . "\" />" ;
            print " " . $pgv_lang["link_to_existing_media"] ;
            print " </a> ";
        }else{
        }

		//Turn Edit Mode On or Off
		if (!isset($edit)) { $edit=1; }
		else{ $edit==$edit;	}
        if ( userCanEdit(getUserName()) && $edit==1 ) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=" . $PHP_SELF . "?tab=7&pid=" . $pid . "&edit=0 title=\"" . $pgv_lang["turn_edit_OFF"] . "\">";
            print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\" " . $pgv_lang["turn_edit_OFF"] . "\" />" ;
            print " " . $pgv_lang["turn_edit_OFF"] ;
            print " </a> ";
        }elseif ( userCanEdit(getUserName()) && $edit==0 ) {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
            print "<a href=" . $PHP_SELF . "?tab=7&pid=" . $pid . "&edit=1 title=\"" . $pgv_lang["turn_edit_ON"] . "\">";
            print "<img src=\"modules/lightbox/images/image_edit.gif\" class=\"icon\" title=\" " . $pgv_lang["turn_edit_ON"] . "\" />" ;
            print " " . $pgv_lang["turn_edit_ON"] ;
            print " </a> ";		
        }else{
		}
		
		
?>