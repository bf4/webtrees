<?php
/**
 * Exports users and their data to either SQL queries (Index mode) or
 * authenticate.php and xxxxxx.dat files (MySQL mode).
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2006  PGV Development Team
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
 * @author Boudewijn Sjouke	sjouke@users.sourceforge.net
 * @package webtrees
 * @subpackage Admin
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'usermigrate.php');
require './config.php';
require_once WT_ROOT.'includes/controllers/usermigrate_ctrl.php';

$controller = new UserMigrateController();
$controller->init();

print_header($controller->getPageTitle());

if (!empty($controller->errorMsg)) {
	echo "<br /><span class=\"error\">", $controller->errorMsg, "</span><br />";
}

// Backup part of usermigrate
if ($controller->proceed == "backup") {
	// If first time, let the user choose the options
	if ((!isset($_POST["um_config"])) && (!isset($_POST["um_gedcoms"])) && (!isset($_POST["um_gedsets"])) &&(!isset($_POST["um_logs"])) &&(!isset($_POST["um_usinfo"]))) {
		?>
		<div class="center"><h2><?php echo i18n::translate('Backup')?></h2></div>
		<table align="center" >
			<tr class="label">
				<td style="text-align:<?php if ($TEXT_DIRECTION == "ltr") echo "left"; else echo "right"; ?>;" >
					<?php echo i18n::translate('This tool can make a backup of several kinds of data in webtrees.<br /><br />The data you choose to back up will be gathered into a ZIP file, which you can download by clicking the link at the bottom of the page, after the backup has been successfully made.<br /><br />The ZIP file will remain in your Index directory until you remove it manually.'); ?><br />
				</td>
			</tr>
		</table><br /><br />
		<form action="usermigrate.php" method="post">
		<table align="center">
			<tr class="label"><td style="padding: 5px" colspan="2" class="facts_label03"><?php echo i18n::translate('Options:'); ?></td></tr><br />
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") echo "left"; else echo "right";?>; "><?php echo i18n::translate('webtrees Configuration File'); ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_config" value="yes" checked="checked" /></td></tr>
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") echo "left"; else echo "right";?>; "><?php echo i18n::translate('GEDCOM Files'); ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_gedcoms" value="yes" checked="checked" /></td></tr>
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") echo "left"; else echo "right";?>; "><?php echo i18n::translate('GEDCOM Settings, Configuration and Privacy files'); ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_gedsets" value="yes" checked="checked" /></td></tr>
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") echo "left"; else echo "right";?>; "><?php echo i18n::translate('GEDCOM SearchLogs and webtrees Logfiles'); ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_logs" value="yes" checked="checked" /></td></tr>
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") echo "left"; else echo "right";?>; "><?php echo i18n::translate('User definitions, Block settings, Favorites, Messages, News'); ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_usinfo" value="yes" checked="checked" /></td></tr>
			<tr><td class="list_label" style="padding: 5px; text-align:<?php if ($TEXT_DIRECTION == "ltr") echo "left"; else echo "right";?>; "><?php echo i18n::translate('Media files'); ?></td><td class="list_value" style="padding: 5px;"><input type="checkbox" name="um_media" value="yes" checked="checked" /></td></tr>
			<tr><td style="padding: 5px" colspan="2" class="facts_label03"><button type="submit" name="submit"><?php echo i18n::translate('Make Backup'); ?></button>
			<input type="button" value="<?php echo i18n::translate('Return to the Admin menu');?>"  onclick="window.location='admin.php';"/></td></tr>
		</table></form><br /><br />
		<?php
		print_footer();
		exit;
	}
	?>
	<div class="center"><h2><?php echo i18n::translate('Backup')?></h2></div>
	<br /><br />
	<table align="center">
		<tr class="label">
			<td style="padding: 5px" class="facts_label03"><?php echo i18n::translate('Results'); ?></td>
		</tr>
	<?php
	// Make the zip
	if (count($controller->flist) > 0) {
		?>
		<tr>
			<td class="list_label" style="padding: 5px;" >
			<?php if ($controller->v_list == 0) {?>
				<?php echo $controller->errorMsg; ?>
			</td>
		</tr>
		<?php } else { ?>
			<?php echo i18n::translate('ZIP file successfully created.'); ?>
			</td>
		</tr>
		<tr>
			<td class="list_value" style="padding: 5px;" >
				<a href="downloadbackup.php?fname=<?php echo $controller->buname; ?>" target="_blank"><?php echo i18n::translate('Download ZIPped backup file '); ?> <?php echo $controller->fname; ?></a>
				( <?php printf("%.0f Kb", (filesize($controller->fname)/1024)); ?> ) <br />
				<?php echo i18n::translate('Files included in this backup'); ?>
				<ul>
				<?php foreach($controller->flist as $f=>$file) { ?>
					<li><?php echo $file; ?></li>
				<?php } ?>
				</ul>
			</td>
		</tr>
		<?php }
	}
	else { ?>
			<td style="padding: 5px" class="list_label"><?php echo i18n::translate('No files found for backup.'); ?></td>
		</tr>
	<?php } ?>
		<tr class="label">
			<td style="padding: 5px" class="facts_label03">
				<input type="button" value="<?php echo i18n::translate('Return to the Admin menu'); ?>" onclick="window.location='admin.php';" />
			</td>
		</tr>
	</table>
	<br /><br />
<?php
	print_footer();
	exit;
}

// User Migration part of usermigrate. The function um_export is used by backup and migrate part.

if (($controller->proceed == "export") || ($controller->proceed == "exportovr")) { ?>
	<h2><?php echo i18n::translate('User Information Migration tool');?></h2>
	<br /><br />
	<?php echo i18n::translate('This tool will create <i>authenticate.php</i> and several <i>.dat</i> files in your index directory.<br /><br />After successful creation, you can switch to Index mode with all current users and their messages, favorites, news, and MyGedview layout available.<br /><br />Note: After switching to Index mode, you will need to import your GEDCOM files again.'); ?>
<?php }
if ($controller->proceed == "import") { ?>
	<h2><?php echo i18n::translate('User Information Migration tool'); ?></h2>
	<br /><br />
	<?php echo i18n::translate('This tool will import <i>authenticate.php</i> and other <i>.dat</i> files from your index directory into your database.'); ?>
	<br /><br />
<?php }
if (($controller->proceed != "import") && ($controller->proceed != "export") && ($controller->proceed != "exportovr")) { ?>
	<div class="center">
		<h2><?php echo i18n::translate('User Information Migration tool'); ?></h2>
		<br /><br />
		<form action="usermigrate.php" method="post"><input type="hidden" name="proceed" value="import" />
		<table align="center" width="75%" ><tr class="label">
			<tr><td style="text-align:<?php if ($TEXT_DIRECTION == "ltr") echo "left"; else echo "right";?>; "><?php echo i18n::translate('This tool will either export user data from SQL to Index mode, or import user data from Index files into SQL tables.<br /><br />User data, favorites, block definitions, messages, and news will be available again after migration.<br /><br /><b>CAUTION</b><br />You cannot use this tool to migrate user data between different versions of webtrees. Be sure that the data originates from, or is imported into the same PhpGedView version.<br /><br /><b>IMPORT</b><br />If you choose to import the user data files from Index mode, all user data present in the database tables will be <u>overwritten</u>. This tool does <u>not</u> merge the information. Once you have run the Import, there is no way to retrieve the old information using PhpGedView.<br /><br /><b>EXPORT</b><br />If you export the user information from your SQL database to Index Mode files, this tool will create <i>authenticate.php</i> and several <i>.dat</i> files in your index directory. If identically named files are already present, you will be prompted if they must be overwritten. After switching to Index mode, all information will be available directly.<br /><br /><b>Note:</b> After switching to Index mode, you will need to import your GEDCOM files again.'), i18n::translate('Choose an option or click the link below to return to the Administration menu')?><br />
			</td></tr>
			<tr><td><input type="submit" class="button" value="<?php echo i18n::translate('Import');?>" />
			<input type="button" class="button" value="<?php echo i18n::translate('Export');?>" onclick="window.location='usermigrate.php?proceed=export';"/>
			<input type="button" value="<?php echo i18n::translate('Return to the Admin menu');?>"  onclick="window.location='admin.php';"/><br /><br />
			</td></tr>
		</table>
		</form>
	</div>
	<?php
	print_footer();
	exit;
}
if (($controller->proceed == "export") || ($controller->proceed == "exportovr")) {

	// Check if one of the files already exists
	if ($controller->fileExists) { ?>
		<br />
		<?php echo i18n::translate('One or more files already exist. Do you want to overwrite them?');?>
		<br /><br />
		<form action="usermigrate.php" method="post">
			<input type="hidden" class="button" value="<?php echo i18n::translate('Yes');?>" />
			<input type="button" class="button" value="<?php echo i18n::translate('Yes');?>" onclick="window.location='usermigrate.php?proceed=exportovr';"/>
			<input type="button" class="button" value="<?php echo i18n::translate('No');?>" onclick="window.location='admin.php';"/>
		</form>
		<?php
		print_footer();
		exit;
	}
}
if ($controller->proceed == "import") {
	echo "<br /><br />", i18n::translate('Importing users'), "<br />";
	if ((file_exists($INDEX_DIRECTORY."authenticate.php")) == false) {
		echo i18n::translate('File <i>authenticate.php</i> not found in your index directory. Migration is cancelled.'), "<br /><br /><a href=\"admin.php\">", i18n::translate('Return to the Admin menu'), "</a><br /><br />";
		exit;
	}

	if ($controller->impSuccess) {
		echo i18n::translate('Import successful'), "<br /><br />";
	} else {
		echo i18n::translate('Import failed');
		exit;
	}

	// Get messages and import them
	echo i18n::translate('Importing messages'), "<br />";
	if ((file_exists($INDEX_DIRECTORY."messages.dat")) == false) {
		echo i18n::translate('No Messages seem to be present in the system.'), "<br /><br />";
	}
	if ($controller->msgSuccess) {
		echo i18n::translate('Import successful'), "<br /><br />";
	}

	// Get favorites and import them
	echo i18n::translate('Importing favorites'), "<br />";
	if ((file_exists($INDEX_DIRECTORY."favorites.dat")) == false) {
		echo i18n::translate('No Favorites seem to be present in the system.'), "<br /><br />";
	}
	if ($controller->favSuccess) {
		echo i18n::translate('Import successful'), "<br /><br />";
	}

	// Get news and import it
	echo i18n::translate('Importing news'), "<br />";
	if ((file_exists($INDEX_DIRECTORY."news.dat")) == false) {
		echo i18n::translate('No News seems to be present in the system.'), "<br /><br />";
	}
	if ($controller->newsSuccess) {
		echo i18n::translate('Import successful'), "<br /><br />";
	}

	// Get blocks and import them
	echo i18n::translate('Importing blocks'), "<br />";
	if ((file_exists($INDEX_DIRECTORY."blocks.dat")) == false) {
		echo i18n::translate('No Blocks seems to be present in the system.'), "<br /><br />";
	}
	if ($controller->blockSuccess) {
		echo i18n::translate('Import successful'), "<br /><br />";
	}
}

echo "<a href=\"admin.php\">", i18n::translate('Return to the Admin menu'), "</a><br /><br />";
print_footer();

?>
