<?php
/**
 * Media View Page
 *
 * This page displays all information about media that is selected in PHPGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Display
 * @version $Id$
 * @TODO use more theme specific CSS, allow a more fluid layout to take advantage of the page width
 */

require './config.php';

require_once 'includes/controllers/media_ctrl.php';

$controller = new MediaController();
$controller->init();


/* Note:
 *  if $controller->getLocalFilename() is not set, then an invalid MID was passed in
 *  if $controller->pid is not set, then a filename was passed in that is not in the gedcom
 */

$filename = $controller->getLocalFilename();

	print_header($controller->getPageTitle());

global $tmb;

// LBox =============================================================================
// Get Javascript variables from lb_config.php ---------------------------
 if (file_exists("modules/lightbox/album.php")) {
	include('modules/lightbox/lb_defaultconfig.php');
	if (file_exists('modules/lightbox/lb_config.php')) include('modules/lightbox/lb_config.php');
	include('modules/lightbox/functions/lb_call_js.php');
}
// LBox  ============================================================================

loadLangFile("lightbox:lang");

	//The following lines of code are used to print the menu box on the top right hand corner
	if ((!$controller->isPrintPreview())&&(empty($SEARCH_SPIDER))&&!empty($controller->pid)&&!empty($filename)) {
		if ($controller->userCanEdit() || $controller->canShowOtherMenu()) { ?>
			<table class="sublinks_table rtl noprint" style="margin: 10px;" cellspacing="4" cellpadding="0" align="<?php print $TEXT_DIRECTION=='ltr'?'right':'left';?>">
				<tr>
					<td class="list_label <?php echo $TEXT_DIRECTION; ?>" colspan="5"><?php print $pgv_lang["media_options"]; ?></td>
				</tr>
				<tr>
					<?php
					if ($controller->userCanEdit()) {
					?>
					<td class="sublinks_cell <?php echo $TEXT_DIRECTION;?>">
						<?php $menu = $controller->getEditMenu(); $menu->printMenu(); ?>
					</td>
					<?php }
					if ($controller->canShowOtherMenu()) {
					?>
					<td class="sublinks_cell <?php echo $TEXT_DIRECTION; ?>">
					<?php $menu = $controller->getOtherMenu(); $menu->printMenu(); ?>
					</td>
					<?php }	?>
				</tr>
			</table>
			<?php
		}
	}


		//The next set of code draws the table that displays information about the person
		?>
		<table width="70%">
			<tr>
				<td class="name_head" colspan="2">
					<?php print PrintReady($controller->mediaobject->getFullName()); if ($SHOW_ID_NUMBERS && !empty($controller->pid)) print " " . getLRM() . "(".$controller->pid.")" . getLRM(); ?>
					<?php print PrintReady($controller->mediaobject->getAddName()); ?> <br /><br />
					<?php if ($controller->mediaobject->isMarkedDeleted()) print "<span class=\"error\">".$pgv_lang["record_marked_deleted"]."</span>"; ?>
				</td>
			</tr>
			<tr>
				<td align="center" width="150">
					<?php

					// If we can display details
					if ($controller->canDisplayDetails()) {
						//Check to see if the File exists in the system. (ie if the file is external, or it exists locally)
						if (isFileExternal($filename) || $controller->mediaobject->fileExists()) {
							// the file is external, or it exists locally
							// attempt to get the image size
							$imgwidth = $controller->mediaobject->getWidth()+40;
							$imgheight = $controller->mediaobject->getHeight()+150;
							if (file_exists("modules/lightbox/album.php")) {
								$dwidth = 200;
							}else{
								$dwidth = 300;
							}
							if ($imgwidth<$dwidth) {
								$dwidth = $imgwidth;
							}

							// Check Filetype of media item -------------------------------------------
							// If Local Image 
							if (eregi("\.(jpg|jpeg|gif|png)$", $filename)) {
								$file_type = "local_image";
							// Else if FLV remote file(URL) 
							}else if(eregi("http://www.youtube.com" ,$filename)) {
								$file_type = "url_flv";
							// Else if FLV local file
							}else if(eregi("\.flv" ,$filename)) {
								$file_type = "local_flv";
							// Else if URL page
							}else if(eregi("http" ,$filename) || eregi("\.pdf",$filename)) {
								$file_type = "url_page";
							// Else Other 
							}else{
								$file_type = "other";
							}
							$name = trim($controller->mediaobject->getFullName());
							
							// if Lightbox installed ------------------------------------------------------------------------------
							if (file_exists("modules/lightbox/album.php") ) {
								// Get Lightbox config variables
								if (file_exists("modules/lightbox/lb_config.php") ) {
									include('modules/lightbox/lb_config.php');
								}else{
									include('modules/lightbox/lb_defaultconfig.php');
								}
								// If local image (Lightbox)
								if ($file_type == "local_image") {
									print "<a href=\"" . $filename . "\" rel='clearbox[general_3]' rev=\"" . $controller->pid . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name,ENT_COMPAT,'UTF-8')) . "\">" . "\n";
								// Else If flv native (Lightbox)
								}elseif ($file_type == "local_flv") {
									print "<a href=\"module.php?mod=JWplayer&amp;pgvaction=flvVideo&amp;flvVideo=" . $filename . "\" rel='clearbox(" . 445 . "," . 370 . ",click)' rev=\"" . $controller->pid . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name,ENT_COMPAT,'UTF-8')) . "\">" . "\n";
								// Else If url_flv filetype (Lightbox)
								}elseif ($file_type == "url_flv") {
									print "<a href=\"module.php?mod=JWplayer&amp;pgvaction=flvVideo&amp;flvVideo=" . str_replace('http://', '', $filename) . "\" rel='clearbox(" . 445 . "," . 370 . ",click)' rev=\"" . $filename . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name,ENT_COMPAT,'UTF-8')) . "\">" . "\n";
								// Else If url_page filetype (Lightbox)
								}elseif ($file_type == "url_page") {
									print "<a href=\"" . $filename . "\" rel='clearbox(" . $LB_URL_WIDTH . "," . $LB_URL_HEIGHT . ",click)' rev=\"" . $filename . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name,ENT_COMPAT,'UTF-8')) . "\">" . "\n";
								// Else Other filetype (Pop-up Window)
								}else{
									print "<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($filename)."',$imgwidth, $imgheight);\">";
								}
								
							 // if Lightbox NOT installed or not enabled -------------------------------------------
							 // else if JWplayer installed and filetype=local_flv (Locally stored in media files )
							} elseif ( file_exists("modules/JWplayer/flvVideo.php") && $file_type == "local_flv") {
								print "<a href=\"javascript:;\" onclick=\" var winflv = window.open('module.php?mod=JWplayer&amp;pgvaction=flvVideo&amp;flvVideo=" . $filename . "', 'winflv', 'width=445, height=365, left=600, top=200'); if (window.focus) {winflv.focus();}\">";
							// else if JWplayer installed and filetype=url_flv (Remote flv file e.g. YouTube)
							} elseif ( file_exists("modules/JWplayer/flvVideo.php") && $file_type == "url_flv" ) {
								print "<a href=\"javascript:;\" onclick=\" var winflv = window.open('module.php?mod=JWplayer&amp;pgvaction=flvVideo&amp;flvVideo=" . str_replace('http://', '', $filename) . "', 'winflv', 'width=445, height=365, left=600, top=200'); if (window.focus) {winflv.focus();}\">";
							//else if URL image
							} else if (eregi("http" ,$filename) && eregi("\.(jpg|jpeg|gif|png)$", $filename)){
								$imageinfo = Getimagesize($filename); 
								$wth = $imageinfo[0];
								$hgt = $imageinfo[1];
								print "<a href=\"javascript:void(0)\" onclick=\"var winimg = window.open('".$filename."', 'winimg', 'width=".$wth.", height=".$hgt.", left=200, top=200'); if (window.focus) {winimg.focus();} \">";
							//else if URL page
							} else if(eregi("http" ,$filename) || eregi("\.pdf",$filename) ){
								print "<a href=\"javascript:;\" onclick=\"var winurl = window.open('".$filename."', 'winurl', 'width=900, height=600, left=200, top=200'); if (window.focus) {winurl.focus();}\">";
							// else just use normal image viewer
							} else {
								print "<a href=\"javascript:;\" onclick=\"return openImage('".rawurlencode($filename)."',$imgwidth, $imgheight);\">";
							}
							
							// Now finally print the thumbnail  --------------------------------------------------
							// If url_flv file (eg You Tube) and JWplayer installed, print the remote flv thumbnail
							if ($file_type == "url_flv") {
								print "<img src=\"modules/JWplayer/flashrem.png\" width=\"60\" border=\"0\" align=\"center" . ($TEXT_DIRECTION== "rtl"?"right": "left") . "\" class=\"thumbnail\" " ;
							// If URL page, print the Common URL Thumbnail
							} else if (eregi("http",$filename) && !eregi("\.(jpg|jpeg|gif|png)$", $filename)) {
								print "<img src=\"images/URL.png\" border=\"0\" align=\"center" . ($TEXT_DIRECTION== "rtl"?"right": "left") . "\"  width=\"72\" height=\"80\" " ;
							// If local flv file, and JWplayer installed, print the local flv thumbnail
							} else if (media_exists($controller->mediaobject->getThumbnail()) && eregi("\media.gif",$controller->mediaobject->getThumbnail()) && eregi("\.flv",$filename)) {
								if (file_exists("modules/lightbox/album.php") || file_exists("modules/JWplayer/flvVideo.php") ) {
									print "<img src=\"modules/JWplayer/flash.png\" height=\"60\" border=\"0\" align=\"center" . ($TEXT_DIRECTION== "rtl"?"right": "left") . "\" class=\"thumbnail\" " ;
								} else {
									print "<img src=\"images/media.gif\" height=\"60\" border=\"0\" align=\"center" . ($TEXT_DIRECTION== "rtl"?"right": "left") . "\" class=\"thumbnail\" " ;
								}
							// Else Print the Regular Thumbnail if associated with a thumbnail image
							} else {
								print "<img src=\"".$controller->mediaobject->getThumbnail()."\" border=\"0\" align=\"center" . ($TEXT_DIRECTION== "rtl"?"right": "left") . "\" class=\"thumbnail\"";
							}
							
							// Finish off anchor and tooltips
							if (isFileExternal($filename)) {
								print " width=\"".$THUMBNAIL_WIDTH."\"";
							}
							print " alt=\"" . PrintReady(htmlspecialchars($name,ENT_COMPAT,'UTF-8')) . "\" title=\"" . PrintReady(htmlspecialchars($name,ENT_COMPAT,'UTF-8')) . "\" />";
							if (isFileExternal($filename) || $controller->mediaobject->fileExists()) {
								print "</a>";
							}

							// If download
							if ($SHOW_MEDIA_DOWNLOAD) {
								print "<br /><br /><a href=\"".$filename."\">".$pgv_lang["download_image"]."</a><br/>";
							}

						 // else the file is not external and does not exist
						} else {
							?>
							<img src="<?php print $controller->mediaobject->getThumbnail(); ?>" border="0" width="100" alt="<?php print $controller->mediaobject->getFullName(); ?>" title="<?php print PrintReady(htmlspecialchars($controller->mediaobject->getFullName(),ENT_COMPAT,'UTF-8')); ?>" />
							<span class="error">
								<?php print $pgv_lang["file_not_found"];?>
							</span>
							<?php
						}
					}
					?>

				</td>
				<td valign="top">
					<table width="100%">
						<tr>
							<td>
								<table class="facts_table<?php print $TEXT_DIRECTION=='ltr'?'':'_rtl';?>">
									<?php
										$facts = $controller->getFacts($SHOW_MEDIA_FILENAME);
										foreach($facts as $f=>$factrec) {
											print_fact($factrec, $controller->pid, 1, false, true);
										}
									?>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="center" colspan="2">
					<?php
					$links = get_media_relations($controller->pid);
					if (isset($links) && !empty($links)){
					?>
					<br /><b><?php print $pgv_lang["relations_heading"]; ?></b><br /><br />
					<?php
						// PrintMediaLinks($links, "");
						require_once 'includes/functions/functions_print_lists.php';
						print_changes_table($links, $SHOW_LAST_CHANGE);
					}	?>
				</td>
			</tr>
		</table>
<?php

// These JavaScript functions are needed for the code to work properly with the menu.
?>
<script language="JavaScript" type="text/javascript">
<!--

// javascript function to open the lightbox view
function lightboxView(){
//	var string = "<?php print $tmb; ?>";
//	alert(string);
//    	document.write(string);
//	<?php print $tmb; ?>
	return false;
}

// javascript function to open the original imageviewer.php page
function openImageView(){
	window.open("imageview.php?filename=<?php print encode_url($filename) ?>", "Image View");
	return false;
}
// javascript function to open a window with the raw gedcom in it
function show_gedcom_record(shownew) {
	fromfile="";
	if (shownew=="yes") fromfile='&fromfile=1';
	var recwin = window.open("gedrecord.php?pid=<?php print $controller->pid; ?>"+fromfile, "_blank", "top=50,left=50,width=600,height=400,scrollbars=1,scrollable=1,resizable=1");
}

function showchanges() {
	window.location = 'mediaviewer.php?mid=<?php print $controller->pid; ?>&showchanges=yes';
}

function ilinkitem(mediaid, type) {
	window.open('inverselink.php?mediaid='+mediaid+'&linkto='+type+'&'+sessionname+'='+sessionid, '_blank', 'top=50,left=50,width=400,height=300,resizable=1,scrollbars=1');
	return false;
}
//-->
</script>


<br /><br /><br />
<?php
print_footer();
?>

