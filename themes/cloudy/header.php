<?php
/**
 * Header for Cloudy theme
 *
 * PhpGedView: Genealogy Viewer
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
 * @package PhpGedView
 * @subpackage Themes
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

global $SEARCH_SPIDER;
if (!isset($view)) $view = safe_REQUEST($_REQUEST, 'view', PGV_REGEX_XREF);
$menubar = new MenuBar();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php print $CHARACTER_SET; ?>" />
		<?php if( $FAVICON ) { ?><link rel="shortcut icon" href="<?php print $FAVICON; ?>" type="image/x-icon" /> <?php	} ?>

		<title><?php print $title; ?></title>
		<?php if ($ENABLE_RSS && !$REQUIRE_AUTHENTICATION){ ?>
			<link href="<?php print encode_url("{$SERVER_URL}rss.php?ged={$GEDCOM}"); ?>" rel="alternate" type="<?php print $applicationType; ?>" title=" <?php print PrintReady(strip_tags($GEDCOM_TITLE), TRUE); ?>" />
		<?php } ?>
		<link rel="stylesheet" href="<?php print $stylesheet; ?>" type="text/css" media="all" />
		<?php if ((!empty($rtl_stylesheet))&&($TEXT_DIRECTION=="rtl")) {?> <link rel="stylesheet" href="<?php print $rtl_stylesheet; ?>" type="text/css" media="all" /> <?php } ?>
		<?php if ($use_alternate_styles && $BROWSERTYPE != "other") { ?>
			<link rel="stylesheet" href="<?php print $THEME_DIR.$BROWSERTYPE; ?>.css" type="text/css" media="all" />
		<?php	}
		// Additional css files required (Only if Lightbox installed)
		if (is_dir('modules/lightbox/css')) {
			if ($TEXT_DIRECTION=='rtl') {
				echo '<link rel="stylesheet" href="modules/lightbox/css/clearbox_music_RTL.css" type="text/css" />';
				echo '<link rel="stylesheet" href="modules/lightbox/css/album_page_RTL_ff.css" type="text/css" media="screen" />';
			} else {
				echo '<link rel="stylesheet" href="modules/lightbox/css/clearbox_music.css" type="text/css" />';
				echo '<link rel="stylesheet" href="modules/lightbox/css/album_page.css" type="text/css" media="screen" />';
			}
		} ?>

	<link rel="stylesheet" href="<?php print $print_stylesheet; ?>" type="text/css" media="print" />
	<?php if ($BROWSERTYPE == "msie") { ?>
	<style type="text/css">
		FORM { margin-top: 0px; margin-bottom: 0px; }
	</style>
	<?php }
	if ($view!="preview" && $view!="simple") { ?>
		<?php if (!empty($META_AUTHOR)) { ?><meta name="author" content="<?php print PrintReady(strip_tags($META_AUTHOR), TRUE); ?>" /><?php } ?>
		<?php if (!empty($META_PUBLISHER)) { ?><meta name="publisher" content="<?php print PrintReady(strip_tags($META_PUBLISHER), TRUE); ?>" /><?php } ?>
		<?php if (!empty($META_COPYRIGHT)) { ?><meta name="copyright" content="<?php print PrintReady(strip_tags($META_COPYRIGHT), TRUE); ?>" /><?php } ?>
		<meta name="keywords" content="<?php print PrintReady(strip_tags($META_KEYWORDS), TRUE).PrintReady(strip_tags($surnameList), TRUE);?>" />
		<?php if (!empty($META_DESCRIPTION)) {?><meta name="description" content="<?php print preg_replace("/\"/", "", PrintReady(strip_tags($META_DESCRIPTION), TRUE));?>" /><?php } ?>
		<?php if (!empty($META_PAGE_TOPIC)) {?><meta name="page-topic" content="<?php print preg_replace("/\"/", "", PrintReady(strip_tags($META_PAGE_TOPIC), TRUE));?>" /><?php } ?>
		<?php if (!empty($META_AUDIENCE)) {?><meta name="audience" content="<?php print PrintReady(strip_tags($META_AUDIENCE), TRUE);?>" /><?php } ?>
		<?php if (!empty($META_PAGE_TYPE)) {?><meta name="page-type" content="<?php print PrintReady(strip_tags($META_PAGE_TYPE), TRUE);?>" /><?php } ?>
		<?php if (!empty($META_ROBOTS)) {?><meta name="robots" content="<?php print PrintReady(strip_tags($META_ROBOTS), TRUE);?>" /><?php } ?>
		<?php if (!empty($META_REVISIT)) {?><meta name="revisit-after" content="<?php print PrintReady(strip_tags($META_REVISIT), TRUE);?>" /><?php } ?>
		<meta name="generator" content="<?php echo PGV_PHPGEDVIEW." - ".PGV_PHPGEDVIEW_URL;?>" />
	<?php }	?>
	<?php print $javascript; ?>
	<?php print $head; //-- additional header information ?>
</head>
<body id="body" <?php print $bodyOnLoad; ?>>
<!-- begin header section -->
<?php
if ($view!='simple')
if ($view=='preview') include($print_headerfile);
else {?>
<div id="header" class="<?php print $TEXT_DIRECTION; ?>">
	<?php if(empty($SEARCH_SPIDER)) { ?>
	<img src="<?php print $PGV_IMAGE_DIR;?>/loading.gif" width="70" height="25" id="ProgBar" name="ProgBar" style="position:absolute;margin-left:auto;margin-right:auto;left:47%;top:48%;margin-bottom:auto;margin-top:auto;" alt="loading..." />
	<?php } ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid #003399;border-top:1px solid #003399;border-right:1px solid #003399;" >
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:url('<?php print $PGV_IMAGE_DIR;?>/clouds.gif');height:38px;white-space: nowrap;" >
					<tr>
						<td width="10" >
							<img src="<?php print $PGV_IMAGE_DIR;?>/pixel.gif" width="1" height="1" alt="" />
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
											<input type="image" src="<?php print $PGV_IMAGE_DIR;?>/go.gif" align="top" title="<?php print $pgv_lang['search']?>
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
							<img src="<?php print $PGV_IMAGE_DIR;?>/pixel.gif" width="1" height="1" alt="" />
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
										<img src="<?php print $PGV_IMAGE_DIR;?>/pixel.gif" width="1" height="1" alt="" />
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
							<img src="<?php print $PGV_IMAGE_DIR;?>/pixel.gif" width="1" height="1" alt="" />
						</td>
					</tr>
				</table>
<?php include($toplinks);
} ?>
<!-- end header section -->
<!-- begin content section -->
