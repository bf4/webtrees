<?php
/**
 * Header for Wood theme
 *
 * PhpGedView: Genealogy Viewer
 * Copyright (c) 2002 to 2008  John Finlay and others.  All rights reserved.
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
 * @subpackage Themes
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

global $SEARCH_SPIDER; 
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
		if ($TEXT_DIRECTION=='rtl') { ?>
			<link rel="stylesheet" href="modules/lightbox/css/clearbox_music_RTL.css" type="text/css" />
			<link rel="stylesheet" href="modules/lightbox/css/album_page_RTL_ff.css" type="text/css" media="screen" />
		<?php } else { ?>
			<link rel="stylesheet" href="modules/lightbox/css/clearbox_music.css" type="text/css" />
			<link rel="stylesheet" href="modules/lightbox/css/album_page.css" type="text/css" media="screen" />
		<?php } ?>

	<link rel="stylesheet" href="<?php print $print_stylesheet; ?>" type="text/css" media="print" />
	<?php if ($BROWSERTYPE == "msie") { ?>
	<style type="text/css">
		FORM { margin-top: 0px; margin-bottom: 0px; }
	</style>
	<?php } 
	if ($view!="preview") { ?>
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
<table class="<?php print $TEXT_DIRECTION; ?>" border="0" width="95%">
	<tr>
		<td class="header_empty"><br /><!-- empty cell behind menu -->
		</td>
		<td>
			<div class="title" style="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>">
				<?php print_gedcom_title_link(TRUE); ?>
			</div>
			<br />
			<a href="<?php print $HOME_SITE_URL; ?>" class="link"><?php print $HOME_SITE_TEXT; ?></a><br />
		</td>
		<?php if(empty($SEARCH_SPIDER)) { ?>
		<td valign="middle">
			<?php print_theme_dropdown(); ?>
		</td>
		<td style="white-space: normal;" align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" valign="middle" >
			<form action="search.php" method="get">
				<input type="hidden" name="action" value="general" />
				<input type="hidden" name="topsearch" value="yes" />
				<input type="text" name="query" accesskey="<?php print $pgv_lang["accesskey_search"]?>" size="15" value="<?php print $pgv_lang['search']?>" onfocus="if (this.value == '<?php print $pgv_lang['search']?>') this.value=''; focusHandler();" onblur="if (this.value == '') this.value='<?php print $pgv_lang['search']?>';" />
				<input type="submit" name="search" value=" &gt; " style="{font-size: 8pt; }" />
			</form>
			<?php print_favorite_selector(0); ?>
		</td>
		<?php } ?>
	</tr>
</table>
</div>
<div id="header2">
<table cellpadding="3">
	<tr>
		<td valign="top">
<?php include($toplinks); 
} ?>
<!-- end header section -->
<!-- begin content section -->
		