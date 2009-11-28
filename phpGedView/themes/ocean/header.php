<?php
/**
 * Header for Ocean theme
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHARACTER_SET; ?>" />
		<?php if (isset($_GET["pgvaction"]) && $_GET["pgvaction"]=="places_edit") { ?>
			<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /> <?php } 
		?>
		<?php if ($FAVICON) { ?><link rel="shortcut icon" href="<?php echo $FAVICON; ?>" type="image/x-icon" /> <?php } ?>

		<title><?php echo $title; ?></title>
		<?php if ($ENABLE_RSS && !$REQUIRE_AUTHENTICATION){ ?>
			<link href="<?php echo encode_url("{$SERVER_URL}rss.php?ged={$GEDCOM}"); ?>" rel="alternate" type="<?php echo $applicationType; ?>" title=" <?php echo htmlspecialchars($GEDCOM_TITLE); ?>" />
		<?php } ?>
		<link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css" media="all" />
		<?php if ((!empty($rtl_stylesheet))&&($TEXT_DIRECTION=="rtl")) {?> <link rel="stylesheet" href="<?php echo $rtl_stylesheet; ?>" type="text/css" media="all" /> <?php } ?>
		<?php if ($use_alternate_styles && $BROWSERTYPE != "other") { ?>
			<link rel="stylesheet" href="<?php echo $THEME_DIR.$BROWSERTYPE; ?>.css" type="text/css" media="all" />
		<?php }
		// Additional css files required (Only if Lightbox installed)
		if (PGV_USE_LIGHTBOX) {
			if ($TEXT_DIRECTION=='rtl') {
				echo '<link rel="stylesheet" href="modules/lightbox/css/clearbox_music_RTL.css" type="text/css" />';
				echo '<link rel="stylesheet" href="modules/lightbox/css/album_page_RTL_ff.css" type="text/css" media="screen" />';
			} else {
				echo '<link rel="stylesheet" href="modules/lightbox/css/clearbox_music.css" type="text/css" />';
				echo '<link rel="stylesheet" href="modules/lightbox/css/album_page.css" type="text/css" media="screen" />';
			}
		} ?>

	<link rel="stylesheet" href="<?php echo $print_stylesheet; ?>" type="text/css" media="print" />
	<!--link rel="search" type="application/opensearchdescription+xml" title="<?php echo get_gedcom_setting(PGV_GED_ID, 'title'); ?>" href="<?php echo $SERVER_URL . "opensearch.php"; ?>" /-->
	<?php if ($BROWSERTYPE == "msie") { ?>
	<style type="text/css">
		FORM { margin-top: 0px; margin-bottom: 0px; }
	</style>
	<?php }
	if ($view!="preview" && $view!="simple") { ?>
		<?php if (!empty($META_AUTHOR)) { ?><meta name="author" content="<?php echo htmlspecialchars($META_AUTHOR); ?>" /><?php } ?>
		<?php if (!empty($META_PUBLISHER)) { ?><meta name="publisher" content="<?php echo htmlspecialchars($META_PUBLISHER); ?>" /><?php } ?>
		<?php if (!empty($META_COPYRIGHT)) { ?><meta name="copyright" content="<?php echo htmlspecialchars($META_COPYRIGHT); ?>" /><?php } ?>
		<meta name="keywords" content="<?php echo htmlspecialchars($META_KEYWORDS); ?>" />
		<?php if (!empty($META_DESCRIPTION)) {?><meta name="description" content="<?php echo htmlspecialchars($META_DESCRIPTION); ?>" /><?php } ?>
		<?php if (!empty($META_PAGE_TOPIC)) {?><meta name="page-topic" content="<?php echo htmlspecialchars($META_PAGE_TOPIC); ?>" /><?php } ?>
		<?php if (!empty($META_AUDIENCE)) {?><meta name="audience" content="<?php echo htmlspecialchars($META_AUDIENCE); ?>" /><?php } ?>
		<?php if (!empty($META_PAGE_TYPE)) {?><meta name="page-type" content="<?php echo htmlspecialchars($META_PAGE_TYPE); ?>" /><?php } ?>
		<?php if (!empty($META_ROBOTS)) {?><meta name="robots" content="<?php echo htmlspecialchars($META_ROBOTS); ?>" /><?php } ?>
		<?php if (!empty($META_REVISIT)) {?><meta name="revisit-after" content="<?php echo htmlspecialchars($META_REVISIT); ?>" /><?php } ?>
		<meta name="generator" content="<?php echo PGV_PHPGEDVIEW, ' - ', PGV_PHPGEDVIEW_URL; ?>" />
	<?php } ?>
	<?php echo $javascript; ?>
	<?php echo $head; //-- additional header information ?>
</head>
<body id="body" <?php echo $bodyOnLoad; ?>>
<!-- begin header section -->
<?php
if ($view!='simple')
if ($view=='preview') include($print_headerfile);
else {?>
<div id="header" class="<?php echo $TEXT_DIRECTION; ?>">
<table class="header_table" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <table width="100%">
        <tr>
          <td><img src="<?php echo $THEME_DIR; ?>header.jpg" width="281" height="50" alt="" /></td>
          <td>
            <table width="100%">
              <tr>
                <td colspan="2">
                  <div class="title" style="<?php echo $TEXT_DIRECTION=="rtl"?"left":"right"; ?>">
                    <?php print_gedcom_title_link(TRUE); ?>
                  </div>
                </td>
		<?php if (empty($SEARCH_SPIDER)) { ?>
                <td align="<?php echo $TEXT_DIRECTION=="rtl"?"left":"right"; ?>" valign="middle" >
                  <form action="search.php" method="get">
                    <input type="hidden" name="action" value="general" />
                    <input type="hidden" name="topsearch" value="yes" />
                    <input type="text" name="query" accesskey="<?php echo $pgv_lang["accesskey_search"]?>" size="12" value="<?php echo $pgv_lang['search']?>" onfocus="if (this.value == '<?php echo $pgv_lang['search']?>') this.value=''; focusHandler();" onblur="if (this.value == '') this.value='<?php echo $pgv_lang['search']?>';" />
                    <input type="submit" name="search" value="&gt;" />
                  </form>
                </td>
		<?php } ?>
              </tr>
              <tr>
                <td valign="top">
                  <b>
                  <a href="<?php echo $HOME_SITE_URL; ?>"><?php echo $HOME_SITE_TEXT; ?></a>
                  </b>
                </td>
                <td align="center">
                  <b>
                  <?php print_user_links(); ?>
                  </b>
                </td>
		<?php if (empty($SEARCH_SPIDER)) { ?>
                <td align="<?php echo $TEXT_DIRECTION=="rtl"?"left":"right"; ?>" valign="top" >
                  <?php print_favorite_selector(); ?>
                </td>
		<?php } ?>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php include($toplinks);
} ?>
<!-- end header section -->
<!-- begin content section -->
