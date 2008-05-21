<?php
/**
 * Header for Cloudy theme
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

global $SEARCH_SPIDER;
$menubar = new MenuBar();
?>
<div id="header" class="<?php print $TEXT_DIRECTION; ?>">
	<?php if(empty($SEARCH_SPIDER)) { ?>
	<img src="<?php print $THEME_DIR?>images/loading.gif" width="70" height="25" id="ProgBar" name="ProgBar" style="position:absolute;margin-left:auto;margin-right:auto;left:47%;top:48%;margin-bottom:auto;margin-top:auto;" alt="loading..." />
	<?php } ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid #003399;border-top:1px solid #003399;border-right:1px solid #003399;" >
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:url('<?php print $PGV_IMAGE_DIR; ?>/clouds.gif');height:38px;white-space: nowrap;" >
					<tr>
						<td width="10" >
							<img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" />
						</td>
						<td align="<?php print $TEXT_DIRECTION=="ltr"?"left":"right" ?>" valign="middle" >
							<div class="title" style="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>">
								<?php print_gedcom_title_link(TRUE); ?>
							</div>
						</td>
						<?php if(empty($SEARCH_SPIDER)) { ?>
						<td valign="middle" align="center">
							<div class="blanco" style="COLOR: #6699ff;" >
								<?php print_user_links(); ?>
							</div>
						</td>
						<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="0" >
								<tr>
									<td align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" valign="middle" >
										<?php print_theme_dropdown(); ?>
									</td>
									<td style="white-space: normal;" align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" valign="middle" >
										<form action="search.php" method="post">
											<input type="hidden" name="action" value="general" />
											<input type="hidden" name="topsearch" value="yes" />
											<input type="text" class="formbut" accesskey="<?php print $pgv_lang["accesskey_search"]?>" name="query" size="15" value="<?php print $pgv_lang['search']?>" 
												onfocus="if (this.value == '<?php print $pgv_lang['search']?>') this.value=''; focusHandler();" 
												onblur="if (this.value == '') this.value='<?php print $pgv_lang['search']?>';" />
											<input type="image" src="<?php print $THEME_DIR ?>/images/go.gif" align="top" title="<?php print $pgv_lang['search']?>
											" />
										</form>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" valign="middle" >
										<?php print_favorite_selector(0); ?>
									</td>
								</tr>
							</table>
						</td>
						<?php } ?>
						<td width="10">
							<img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" />
						</td>
					</tr>
				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#aaccff" >
					<tr valign="middle" style="height:26px;margin-top:2pt;">
						<td width="10">
						</td>
						<td align="left">
							<table cellspacing="0" cellpadding="0" border="0" style="min-width:200px;height:26px;" align="<?php print $TEXT_DIRECTION=="ltr"?"left":"right" ?>">
								<tr>
									<td>
										<img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" />
									</td>
									
								<?php
									$menu = $menubar->getHomeMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
									$menu = $menubar->getGedcomMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
									$menu = $menubar->getMygedviewMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
									$menu = $menubar->getChartsMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
									$menu = $menubar->getListsMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
									$menu = $menubar->getCalendarMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
									$menu = $menubar->getReportsMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
									$menu = $menubar->getClippingsMenu();
									if(!is_null($menu) && $menu->link != "") {
										if (!is_null($menu)) {
											print "\t<td width=\"1\">\n";
											$menu->addLabel("", "none");
											$menu->printMenu();
											print "\t</td>\n";
										}
									}
									$menu = $menubar->getSearchMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
									$menu = $menubar->getOptionalMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
									$menus = $menubar->getModuleMenus();
									foreach($menus as $m=>$menu) { 
										if($menu->link != "") {
											print "\t<td width=\"1\">\n";
											$menu->addLabel("", "none");
											$menu->printMenu();
											print "\t</td>\n";
										}
									}
									$menu = $menubar->getPreviewMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
									$menu = $menubar->getHelpMenu();
									if($menu->link != "") {
										print "\t<td width=\"1\">\n";
										$menu->addLabel("", "none");
										$menu->printMenu();
										print "\t</td>\n";
									}
								?>
								</tr>
							</table>
						</td>
						<td >
							&nbsp;
						</td>
						<?php if(empty($SEARCH_SPIDER)) { ?>
						<td>
							<div align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" >
								<?php print_lang_form(1); ?>
							</div>
						</td>
						<?php } ?>
						<td width="10">
							<img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" />
						</td>
					</tr>
				</table>
