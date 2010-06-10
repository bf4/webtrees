<?php
/**
 * Header for Cloudy theme
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
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
 * @package webtrees
 * @subpackage Themes
 * @version $Id$
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$menubar = new MenuBar();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo i18n::html_markup(); ?>>
	<head>
		<?php if (isset($_GET["mod_action"]) && $_GET["mod_action"]=="places_edit") { ?>
			<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /> <?php } 
		?>
		<?php if ($FAVICON) { ?><link rel="shortcut icon" href="<?php echo $FAVICON; ?>" type="image/x-icon" /> <?php } ?>

		<title><?php echo $title; ?></title>
		<?php if ($ENABLE_RSS && !$REQUIRE_AUTHENTICATION) { ?>
			<link href="<?php echo WT_SERVER_NAME.WT_SCRIPT_PATH.'rss.php?ged='.urlencode($GEDCOM); ?>" rel="alternate" type="<?php echo $applicationType; ?>" title=" <?php echo htmlspecialchars($GEDCOM_TITLE); ?>" />
		<?php } ?>
		<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css" media="all" />
		<?php if ((!empty($rtl_stylesheet))&&($TEXT_DIRECTION=="rtl")) { ?> <link rel="stylesheet" href="<?php echo $rtl_stylesheet; ?>" type="text/css" media="all" /> <?php } ?>
		<?php if ($use_alternate_styles && $BROWSERTYPE != "other") { ?>
			<link rel="stylesheet" href="<?php echo WT_THEME_DIR.$BROWSERTYPE; ?>.css" type="text/css" media="all" />
		<?php }
		// Additional css files required
		if (WT_USE_LIGHTBOX) {
			if ($TEXT_DIRECTION=='rtl') {
				echo '<link rel="stylesheet" href="modules/lightbox/css/clearbox_music_RTL.css" type="text/css" />';
				echo '<link rel="stylesheet" href="modules/lightbox/css/album_page_RTL_ff.css" type="text/css" media="screen" />';
			} else {
				echo '<link rel="stylesheet" href="modules/lightbox/css/clearbox_music.css" type="text/css" />';
				echo '<link rel="stylesheet" href="modules/lightbox/css/album_page.css" type="text/css" media="screen" />';
			}
		} ?>

	<link rel="stylesheet" href="<?php echo $print_stylesheet; ?>" type="text/css" media="print" />
	<?php if ($BROWSERTYPE == "msie") { ?>
	<style type="text/css">
		FORM { margin-top: 0px; margin-bottom: 0px; }
	</style>
	<?php }
	if ($view!="simple") { ?>
		<?php if (!empty($META_DESCRIPTION)) { ?><meta name="description" content="<?php echo htmlspecialchars($META_DESCRIPTION); ?>" /><?php } ?>
		<?php if (!empty($META_ROBOTS)) { ?><meta name="robots" content="<?php echo htmlspecialchars($META_ROBOTS); ?>" /><?php } ?>
		<meta name="generator" content="<?php echo WT_WEBTREES, ' - ', WT_WEBTREES_URL; ?>" />
	<?php } ?>
	<?php echo $javascript; ?>
	<?php echo $head; //-- additional header information ?>
	<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/jquery/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="js/jquery/jquery.tablesorter.pager.js"></script>
	<link type="text/css" href="js/jquery/css/jquery-ui.custom.css" rel="Stylesheet" />
	<link type="text/css" href="<?php echo WT_THEME_DIR?>jquery/jquery-ui_theme.css" rel="Stylesheet" />
	<?php if ($TEXT_DIRECTION=='rtl') {?>
		<link type="text/css" href="<?php echo WT_THEME_DIR?>jquery/jquery-ui_theme_rtl.css" rel="Stylesheet" />
	<?php }?>
	<link type="text/css" href="<?php echo WT_THEME_DIR?>modules.css" rel="Stylesheet" />
</head>
<body id="body" <?php echo $bodyOnLoad; ?>>
<!-- begin header section -->
<?php if ($view!='simple') {?>
<div id="header" class="<?php echo $TEXT_DIRECTION; ?>">
	<?php if (empty($SEARCH_SPIDER)) { ?>
	<img src="<?php echo $WT_IMAGE_DIR; ?>/loading.gif" width="70" height="25" id="ProgBar" name="ProgBar" style="position:absolute;margin-left:auto;margin-right:auto;left:47%;top:48%;margin-bottom:auto;margin-top:auto;" alt="loading..." />
	<?php } ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid #003399;border-top:1px solid #003399;border-right:1px solid #003399;" >
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:url('<?php echo $WT_IMAGE_DIR; ?>/clouds.gif');height:38px;white-space: nowrap;" >
					<tr>
						<td width="10" >
							<img src="<?php echo $WT_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" />
						</td>
						<td align="<?php echo $TEXT_DIRECTION=="ltr"?"left":"right"; ?>" valign="middle" >
							<div class="title" style="<?php echo $TEXT_DIRECTION=="rtl"?"left":"right"; ?>">
								<?php print_gedcom_title_link(TRUE);?>
							</div>
						</td>
						<?php if (empty($SEARCH_SPIDER)) { ?>
						<td valign="middle" align="center">
							<div class="blanco" style="COLOR: #6699ff;" >
								<?php print_user_links(); ?>
							</div>
						</td>
						<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="0" >
								<tr>
									<td style="white-space: normal;" align="<?php echo $TEXT_DIRECTION=="rtl"?"left":"right"; ?>" valign="middle" >
										<form action="search.php" method="post">
											<input type="hidden" name="action" value="general" />
											<input type="hidden" name="topsearch" value="yes" />
											<input type="text" class="formbut" name="query" size="15" value="<?php echo i18n::translate('Search'); ?>"
												onfocus="if (this.value == '<?php echo i18n::translate('Search'); ?>') this.value=''; focusHandler();"
												onblur="if (this.value == '') this.value='<?php echo i18n::translate('Search'); ?>';" />
											<input type="image" src="<?php echo $WT_IMAGE_DIR,"/".$WT_IMAGES['search']['small'];?>" height="17" align="top" title="<?php echo i18n::translate('Search');?>">		
										</form>
									</td>
								</tr>
							</table>
						</td>
						<?php } ?>
						<td width="10">
							<img src="<?php echo $WT_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" />
						</td>
					</tr>
				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#aaccff" >
					<tr valign="middle" style="height:26px;margin-top:2pt;">
						<td width="10">
						</td>
						<td align="left">
							<table cellspacing="0" cellpadding="0" border="0" style="min-width:200px;height:26px;" align="<?php echo $TEXT_DIRECTION=="ltr"?"left":"right"; ?>">
								<tr>
									<td>
										<img src="<?php echo $WT_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" />
									</td>

								<?php
									$menu = $menubar->getGedcomMenu();
									if ($menu->link != "") {
										echo '<td width="1">';
										$menu->addLabel("", "none");
										$menu->printMenu();
										echo "</td>";
									}
									$menu = $menubar->getMyPageMenu();
									if ($menu->link != "") {
										echo '<td width="1">';
										$menu->addLabel("", "none");
										$menu->printMenu();
										echo "</td>";
									}
									$menu = $menubar->getChartsMenu();
									if ($menu->link != "") {
										echo '<td width="1">';
										$menu->addLabel("", "none");
										$menu->printMenu();
										echo "</td>";
									}
									$menu = $menubar->getListsMenu();
									if ($menu->link != "") {
										echo '<td width="1">';
										$menu->addLabel("", "none");
										$menu->printMenu();
										echo "</td>";
									}
									$menu = $menubar->getCalendarMenu();
									if ($menu->link != "") {
										echo '<td width="1">';
										$menu->addLabel("", "none");
										$menu->printMenu();
										echo "</td>";
									}
									$menu = $menubar->getReportsMenu();
									if ($menu->link != "") {
										echo '<td width="1">';
										$menu->addLabel("", "none");
										$menu->printMenu();
										echo "</td>";
									}
									$menu = $menubar->getSearchMenu();
									if ($menu->link != "") {
										echo '<td width="1">';
										$menu->addLabel("", "none");
										$menu->printMenu();
										echo "</td>";
									}
									$menu = $menubar->getOptionalMenu();
									if ($menu->link != "") {
										echo '<td width="1">';
										$menu->addLabel("", "none");
										$menu->printMenu();
										echo "</td>";
									}
									$menus = $menubar->getModuleMenus();
									foreach ($menus as $menu) {
										if ($menu->link != "") {
											echo '<td width="1">';
											$menu->addLabel("", "none");
											$menu->printMenu();
											echo "</td>";
										}
									}
									$menu = $menubar->getHelpMenu();
									if ($menu->link != "") {
										echo '<td width="1">';
										$menu->addLabel("", "none");
										$menu->printMenu();
										echo "</td>";
									}
								?>
								</tr>
							</table>
						</td>
						<td>
							&nbsp;
						</td>
						<?php if (empty($SEARCH_SPIDER)) { ?>
						<td>
							<div class="makeMenu" align="<?php echo $TEXT_DIRECTION=="rtl"?"left":"right"; ?>" >
								<?php
									echo MenuBar::getFavoritesMenu()->getMenuAsList();
									global $ALLOW_THEME_DROPDOWN;
										if ($ALLOW_THEME_DROPDOWN && get_site_setting('ALLOW_USER_THEMES')) {
											echo ' | ', MenuBar::getThemeMenu()->getMenuAsList();
										}
									echo ' | ', MenuBar::getLanguageMenu()->getMenuAsList();
								?>
							</div>
						</td>
						<?php } ?>
						<td width="10">
							<img src="<?php echo $WT_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" />
						</td>
					</tr>
				</table>
<?php include($toplinks);
} ?>
<!-- end header section -->
<!-- begin content section -->
