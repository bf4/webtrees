<?php
/**
 * Header for Xenea theme
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

global $SEARCH_SPIDER; $TEXT_DIRECTION;

$date=new GedcomDate(date('j M Y', client_time()));
$displayDate = $date->Display(false);

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
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#003399">
   <tr>
      <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-image:url('<?php 
      		if ($TEXT_DIRECTION=="ltr") {
	      		print $PGV_IMAGE_DIR."/cabeza.jpg'); ";
	      		print "background-position:left top; ";
  			} else {
	  			print $PGV_IMAGE_DIR."/cabeza_rtl.jpg'); ";
	  			print "background-position:right top; ";
  			}
      		?>background-repeat:repeat-y; height:40px;">
              <tr>
                <td width="10"><img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" /></td>
                <td valign="middle"><font color="#FFFFFF" size="5" face="Verdana, Arial, Helvetica, sans-serif"><?php global $GEDCOMS, $GEDCOM; print PrintReady($GEDCOMS[$GEDCOM]["title"], TRUE); ?></font></td>
		<?php if(empty($SEARCH_SPIDER)) { ?>
                <td align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>">
               	<form action="search.php" method="get">
				<input type="hidden" name="action" value="general" />
				<input type="hidden" name="topsearch" value="yes" />
				<input type="text" name="query" accesskey="<?php print $pgv_lang["accesskey_search"]?>" size="12" value="<?php print $pgv_lang['search']?>" onfocus="if (this.value == '<?php print $pgv_lang['search']?>') this.value=''; focusHandler();" onblur="if (this.value == '') this.value='<?php print $pgv_lang['search']?>';" />
				<input type="submit" name="search" value="&gt;" />
				</form>
				</td>
				<td width="10"><img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" /></td>
		<?php } ?>
              </tr></table>
		<?php if(empty($SEARCH_SPIDER)) { ?>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#84beff" style="background-image:url('<?php print $PGV_IMAGE_DIR; ?>/barra.gif');">
              <tr>
                <td width="10"><img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="18" alt="" /></td>
                <td><div id="favtheme" align="<?php print $TEXT_DIRECTION=="rtl"?"right":"left" ?>" class="blanco"><?php print_theme_dropdown(1); ?></div><?php print_user_links(); ?></td>
				<td valign="top"></td>
                <td><div align="center"><?php print_lang_form(1); ?></div></td>
				<td><div id="favdate" align="<?php print $TEXT_DIRECTION=="rtl"?"left":"right" ?>" class="blanco"><?php print_favorite_selector(1); ?><?php print $displayDate; ?>


                </div></td><td width="10"><img src="<?php print $PGV_IMAGE_DIR; ?>/pixel.gif" width="1" height="1" alt="" /></td></tr></table>
		<?php } ?>
<?php include($toplinks); 
} ?>
<!-- end header section -->
<!-- begin content section -->
		