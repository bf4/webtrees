<?php
/**
 * Media View Page
 *
 * This page displays all information about media that is selected in 
 * PHPGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003  John Finlay and Others
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
 * @subpackage Admin
 * @see mediaviewer.php
 * @version 3
 * @TODO use more theme specific CSS, allow a more fluid layout to take advantage of the page width
 */
//These files are required for this page to work
require_once("includes/controllers/media_ctrl.php");

if (empty($controller->pid)) {
	//If there isn't any media loaded then this will print the no media tag.
	echo "<div align=\"center\">".$pgv_lang["no_media"]."</div>";
}
else{
	
	print_header($controller->getPageTitle());
	//The following lines of code are used to print the menu box on the top right hand corner
?>
		<table class="sublinks_table" cellspacing="4" cellpadding="0" align="right">
			<tr>
				<td class="list_label <?php echo $TEXT_DIRECTION; ?>" colspan="5"><?php print $pgv_lang["media_options"]; ?></td>
			</tr>
			<tr>
				<td class="sublinks_cell <?php echo $TEXT_DIRECTION; ?>">
				<?php
				//-- get charts menu from menubar
				$menubar = new MenuBar();
				?>
				</td>
				<td class="sublinks_cell <?php echo $TEXT_DIRECTION; ?>">
				<?php
				if (file_exists("reports/individual.xml")) {?>
					</td><td class="sublinks_cell <?php echo $TEXT_DIRECTION; ?>">
					<?php
				}
				if ($controller->userCanEdit()) {
				?>
				</td>
				<td class="sublinks_cell <?php echo $TEXT_DIRECTION;?>">
				<?php $menu = $controller->getEditMenu(); $menu->printMenu();
				}
				if ($controller->canShowOtherMenu()) {
				?>
				</td>
				<td class="sublinks_cell <?php echo $TEXT_DIRECTION; ?>">
				<?php $menu = $controller->getOtherMenu(); $menu->printMenu();
				}
				?>
				</td>
			</tr>
		</table>
		<?php 
		//The next set of code draws the table that displays information about the person 
		?>
		<table width="70%">
			<tr>
				<td align="center">
					<?php 
					//Checks to see if the File exist in the system.
					$filename = $controller->getLocalFilename();
					if (preg_match("~://~", $filename) || file_exists($filename)){
						//If the file exists, it will attempt to get the image size
						//If the image size returns a null, then the file isn't a image.
						$imagesize = getimagesize($filename);
						
						//Checks if the image size is null.
						if ($imagesize[0]){
							//Makes it so the picture when clicked opens the Image View Page 
							?>
							<a href="javascript:;openImageView();" onclick="return openImageView();">
							<img src="<?php if (!$USE_THUMBS_MAIN) print $filename; else print thumbnail_file($filename); ?>" border="0" width="200" />
							</a>
							<?php
						}
						else{
							?>
							<a href="<?php print $filename; ?>" target="_BLANK">
							<img src="<?php print thumbnail_file($filename); ?>" border="0" width="150" />
							</a>
							<?php
						}
						?>
						<br /><br /><a href="<?php print $filename; ?>"><?php print $pgv_lang["download_image"]; ?></a><br/>
						<?php
					}
					else{
						?>
						<img src="<?php print thumbnail_file($filename); ?>" border="0" width="100" />
						<br /><span class="error"><?php print $pgv_lang["file_not_found"];?></span>
						<?php
					}
					?>
				</td>
				<td valign="top">
					<table width="100%">
						<tr>
							<td class="name_head">
								 <?php print PrintReady($controller->mediaobject->getTitle()); ?> <br/><br />
								 <?php if ($controller->mediaobject->isMarkedDeleted()) print "<span class=\"error\">".$pgv_lang["record_marked_deleted"]."</span>"; ?>
							</td>
						</tr>
						<tr>
							<td>
								<table class="facts_table">	
									<?php
										$facts = $controller->getFacts();
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
				<td colspan="2">
					<?php
					$links = get_media_relations($mid);
					if (isset($links)){
					?>
					 <br /><?php print $pgv_lang["relations_heading"]; ?>
					<?php
						PrintMediaLinks($links, "");
					}	?>
				</td>
			</tr>
		</table>
<?php 
// These JavaScript functions are needed for the code to work properly with the menu.
?>
<script language="JavaScript" type="text/javascript">
<!--
// javascript function to open the original imageviewer.php page
function openImageView(){
	window.open("imageview.php?filename=<?php print $filename ?>", "Image View");
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
}
print_footer();
?>