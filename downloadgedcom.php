<?php

/**
 * Allow an admin user to download the entire gedcom file.
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
 * @package webtrees
 * @subpackage Admin
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'downloadgedcom.php');
require './includes/session.php';
require_once WT_ROOT.'includes/functions/functions_export.php';

// Validate user parameters
if (!isset($_SESSION['exportConvPath'])) $_SESSION['exportConvPath'] = $MEDIA_DIRECTORY;
if (!isset($_SESSION['exportConvSlashes'])) $_SESSION['exportConvSlashes'] = 'forward';

$ged              = safe_GET('ged',              preg_quote_array(get_all_gedcoms()));
$action           = safe_GET('action',           'download');
$remove           = safe_GET('remove',           'yes', 'no');
$convert          = safe_GET('convert',          'yes', 'no');
$zip              = safe_GET('zip',              'yes', 'no');
$conv_path        = safe_GET('conv_path',        WT_REGEX_NOSCRIPT, $_SESSION['exportConvPath']);
$conv_slashes     = safe_GET('conv_slashes',     array('forward', 'backward'), $_SESSION['exportConvSlashes']);
$privatize_export = safe_GET('privatize_export', array('none', 'visitor', 'user', 'gedadmin'));

$conv_path = stripLRMRLM($conv_path);
$_SESSION['exportConvPath'] = $conv_path; // remember this for the next Download
$_SESSION['exportConvSlashes'] = $conv_slashes;

if (!WT_USER_GEDCOM_ADMIN || !$ged) {
	header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.'editgedcoms.php');
	exit;
}

if ($action == 'download') {
	$conv_path = rtrim(str_replace('\\', '/', trim($conv_path)), '/').'/'; // make sure we have a trailing slash here
	if ($conv_path=='/') $conv_path = '';

	$exportOptions = array();
	$exportOptions['privatize'] = $privatize_export;
	$exportOptions['toANSI'] = $convert;
	$exportOptions['noCustomTags'] = $remove;
	$exportOptions['path'] = $conv_path;
	$exportOptions['slashes'] = $conv_slashes;
}

if ($action == "download" && $zip == "yes") {
	require WT_ROOT.'library/pclzip.lib.php';

	$temppath = get_site_setting('INDEX_DIRECTORY') . "tmp/";
	$fileName = $ged;
	$zipname = "dl" . date("YmdHis") . $fileName . ".zip";
	$zipfile = get_site_setting('INDEX_DIRECTORY') . $zipname;
	$gedname = $temppath . $fileName;

	$removeTempDir = false;
	if (!is_dir(filename_decode($temppath))) {
		$res = mkdir(filename_decode($temppath));
		if ($res !== true) {
			echo "Error : Could not create temporary path!";
			exit;
		}
		$removeTempDir = true;
	}
	$gedout = fopen(filename_decode($gedname), "w");
	export_gedcom($GEDCOM, $gedout, $exportOptions);
	fclose($gedout);
	$comment = "Created by ".WT_WEBTREES." ".WT_VERSION_TEXT." on " . date("r") . ".";
	$archive = new PclZip(filename_decode($zipfile));
	$v_list = $archive->create(filename_decode($gedname), PCLZIP_OPT_COMMENT, $comment, PCLZIP_OPT_REMOVE_PATH, filename_decode($temppath));
	if ($v_list == 0) echo "Error : " . $archive->errorInfo(true);
	else {
		unlink(filename_decode($gedname));
		if ($removeTempDir) rmdir(filename_decode($temppath));
		header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH."downloadbackup.php?fname=".$zipname);
		exit;
	}
	exit;
}

if ($action == "download") {
	header('Content-Type: text/plain; charset=UTF-8');
	// We could open "php://compress.zlib" to create a .gz file or "php://compress.bzip2" to create a .bz2 file
	$gedout = fopen('php://output', 'w');
	if (strtolower(substr($ged, -4, 4))!='.ged') {
		$ged.='.ged';
	}
	header('Content-Disposition: attachment; filename="'.$ged.'"');
	export_gedcom($GEDCOM, $gedout, $exportOptions);
	fclose($gedout);
	exit;
}

print_header(WT_I18N::translate('Download GEDCOM'));

?>
<div class="center"><h2><?php echo WT_I18N::translate('Download GEDCOM'); ?></h2></div>
<br />
<form name="convertform" method="get">
	<input type="hidden" name="action" value="download" />
	<input type="hidden" name="ged" value="<?php echo $ged; ?>" />
	<table class="list_table width50" border="0" valign="top">
	<tr><td colspan="2" class="facts_label03"><?php echo WT_I18N::translate('Options:'); ?></td></tr>
	<tr><td class="descriptionbox width50 wrap"><?php echo WT_I18N::translate('Zip File(s)'), help_link('download_zipped'); ?></td>
		<td class="list_value"><input type="checkbox" name="zip" value="yes" checked="checked" /></td></tr>
	<tr><td class="descriptionbox width50 wrap"><?php echo WT_I18N::translate('Apply privacy settings?'), help_link('apply_privacy'); ?></td>
		<td class="list_value">
		<?php if (WT_USER_IS_ADMIN) { ?>
			<input type="radio" name="privatize_export" value="none" checked="checked" />&nbsp;&nbsp;<?php echo WT_I18N::translate('None'); ?><br />
			<input type="radio" name="privatize_export" value="visitor" />&nbsp;&nbsp;<?php echo WT_I18N::translate('Visitor'); ?><br />
		<?php } else { ?>
			<input type="radio" name="privatize_export" value="none" DISABLED />&nbsp;&nbsp;<?php echo WT_I18N::translate('None'); ?><br />
			<input type="radio" name="privatize_export" value="visitor" checked="checked" />&nbsp;&nbsp;<?php echo WT_I18N::translate('Visitor'); ?><br />
		<?php } ?>
		<input type="radio" name="privatize_export" value="user" />&nbsp;&nbsp;<?php echo WT_I18N::translate('Member'); ?><br />
		<input type="radio" name="privatize_export" value="gedadmin" />&nbsp;&nbsp;<?php echo WT_I18N::translate('Manager'); ?><br />
		</td></tr>
	<tr><td class="descriptionbox width50 wrap"><?php echo WT_I18N::translate('Convert from UTF-8 to ANSI (ISO-8859-1)'), help_link('utf8_ansi'); ?></td>
		<td class="list_value"><input type="checkbox" name="convert" value="yes" /></td></tr>
	<tr><td class="descriptionbox width50 wrap"><?php echo WT_I18N::translate('Remove custom webtrees tags? (eg. _WT_USER, _THUM)'), help_link('remove_tags'); ?></td>
		<td class="list_value"><input type="checkbox" name="remove" value="yes" checked="checked" /></td></tr>
	<tr><td class="descriptionbox width50 wrap"><?php echo WT_I18N::translate('Convert media path to'), help_link('convertPath'); ?></td>
		<td class="list_value"><input type="text" name="conv_path" size="30" value="<?php echo getLRM(), $conv_path, getLRM(); ?>" /></td></tr>
	<tr><td class="descriptionbox width50 wrap"><?php echo WT_I18N::translate('Convert media folder separators to'), help_link('convertSlashes'); ?></td>
		<td class="list_value">
		<input type="radio" name="conv_slashes" value="forward" <?php if ($conv_slashes=='forward') echo "checked=\"checked\" "; ?>/>&nbsp;&nbsp;<?php echo WT_I18N::translate('Forward slashes : /'); ?><br />
		<input type="radio" name="conv_slashes" value="backward" <?php if ($conv_slashes=='backward') echo "checked=\"checked\" "; ?>/>&nbsp;&nbsp;<?php echo WT_I18N::translate('Backslashes : \\'); ?>
		</td></tr>
	<tr><td class="facts_label03" colspan="2">
	<input type="submit" value="<?php echo WT_I18N::translate('Download Now'); ?>" />
	<input type="button" value="<?php echo WT_I18N::translate('Back'); ?>" onclick="window.location='editgedcoms.php';"/></td></tr>
	</table>
</form>
<?php

print_footer();
