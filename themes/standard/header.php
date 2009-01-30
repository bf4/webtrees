<?php
/**
 * Header for Standard theme
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
<script type="text/javascript">

function switchMenu(openMe,closeMe)
    {
	    var openIt = document.getElementById(openMe);
	    var closeIt = document.getElementById(closeMe);
	    closeIt.style.display = 'none';
	    openIt.style.display = '';
		SetCookie("menu",document.getElementById(openMe).id.toString(),7);
		window.location = '<?php print $SCRIPT_NAME."?".$QUERY_STRING;?>';
	}
function SetCookie(cookieName,cookieValue,nDays)
	{
 var today = new Date();
 var expire = new Date();
 if (nDays==null || nDays==0) nDays=1;
 expire.setTime(today.getTime() + 3600000*24*nDays);
 document.cookie = cookieName+"="+escape(cookieValue)
                 + ";expires="+expire.toGMTString();
	}


</script>
<div id="header" class="<?php print $TEXT_DIRECTION; ?>">
<table width="99%">
	<tr>
		<td><img src="<?php print $THEME_DIR;?>header.jpg" width="281" height="50" alt="" /></td>
		<td>
			<table width="100%">
			<tr>
				<td align="center" valign="top">
					<b>
					<?php print_user_links(); ?>
					<br />
					<a href="<?php print $HOME_SITE_URL; ?>"><?php print $HOME_SITE_TEXT; ?></a>
					</b>
				</td>
				<?php if(empty($SEARCH_SPIDER)) { ?>
				<td align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" valign="middle" >
					<?php print_lang_form(); ?>
					<?php print_theme_dropdown(); ?>
				</td>
				<?php } ?>
                    <td align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" valign="middle" >
				<?php if(empty($SEARCH_SPIDER)) { ?>
					<form action="search.php" method="get">
						<input type="hidden" name="action" value="general" />
						<input type="hidden" name="topsearch" value="yes" />
						<input type="text" name="query" accesskey="<?php print $pgv_lang["accesskey_search"]?>" size="12" value="<?php print $pgv_lang['search']?>" onfocus="if (this.value == '<?php print $pgv_lang['search']?>') this.value=''; focusHandler();" onblur="if (this.value == '') this.value='<?php print $pgv_lang['search']?>';" />
						<input type="submit" name="search" value="&gt;" />
					</form>
				<?php } ?>
					<?php print_favorite_selector(); ?>
				</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<table width="99%">
	<tr>
		<td width="75%">
			<div class="title" style="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>">
				<?php print_gedcom_title_link(TRUE); ?>
			</div>
		</td>
	</tr>
</table>
<?php include($toplinks);
} ?>
<!-- end header section -->
<!-- begin content section -->
