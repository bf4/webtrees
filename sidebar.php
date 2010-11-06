<?php
/**
* Animated Sidebar for the Individual Page
*
* webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
* Copyright (C) 2002 to 2010 PGV Development Team. All rights reserved.
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*
* @package webtrees
* @subpackage Sidebar
* @version $Id$
*/

if (!defined('WT_SCRIPT_NAME')) define('WT_SCRIPT_NAME', 'sidebar.php');
require_once('includes/session.php');
require_once(WT_ROOT.'includes/classes/class_module.php');

$sidebarmods = WT_Module::getActiveSidebars();
if (!$sidebarmods) {
	return;
}

$sb_action = safe_GET('sb_action', WT_REGEX_ALPHANUM, 'none');
//-- handle ajax calls
if ($sb_action!='none') {
	header('Content-type: text/html; charset=UTF-8');
	class tempController {
		var $pid;
		var $famid;
	}

	$controller = new tempController();

	$pid = safe_GET_xref('pid', '');
	if (empty($pid)) $pid = safe_POST_xref('pid', '');
	if (!empty($pid)) {
		$controller->pid = $pid;
	}
	$pid = safe_GET_xref('rootid',  '');
	if (empty($pid)) $pid = safe_POST_xref('rootid', '');
	if (!empty($pid)) {
		$controller->pid = $pid;
	}
	$famid = safe_GET('famid', WT_REGEX_XREF, '');
	if (empty($famid)) $famid = safe_POST('famid', WT_REGEX_XREF, '');
	if (!empty($famid)) {
		$controller->famid = $famid;
	}
	$sid = safe_GET('sid', WT_REGEX_XREF, '');
	if (empty($sid)) $sid = safe_POST('sid', WT_REGEX_XREF, '');
	if (!empty($sid)) {
		$controller->sid = $sid;
	}

	if ($sb_action=='loadMods') {
		$counter = 0;
		foreach ($sidebarmods as $mod) {
			if (isset($controller)) $mod->setController($controller);
			if ($mod->hasSidebarContent()) {
				?>
				<h3 title="<?php echo $mod->getName(); ?>"><a href="#"><?php echo $mod->getTitle(); ?></a></h3>
				<div id="sb_content_<?php echo $mod->getName(); ?>">
				<?php if ($counter==0) echo $mod->getSidebarContent();
				else { ?><img src="<?php echo WT_THEME_DIR; ?>images/loading.gif" /><?php } ?>
				</div>
				<?php
				$counter++;
			}
		}
		exit;
	}
	if ($sb_action=='loadmod') {
		$modName = safe_GET('mod', WT_REGEX_URL, '');
		if (isset($sidebarmods[$modName])) {
			$mod = $sidebarmods[$modName];
			if (isset($controller)) $mod->setController($controller);
			echo $mod->getSidebarContent();
		}
		exit;
	}
	if (isset($sidebarmods[$sb_action])) {
		$mod = $sidebarmods[$sb_action];
		echo $mod->getSidebarAjaxContent();
	}
	exit;
}

global $controller;
$pid='';
$famid='';
if (isset($controller)) {
	if (isset($controller->pid)) $pid = $controller->pid;
	if (isset($controller->rootid)) $pid = $controller->rootid;
	if (isset($controller->famid)) $famid = $controller->famid;
	if (isset($controller->sid)) $pid = $controller->sid;
} else {
	$pid = safe_GET_xref('pid', '');
	if (empty($pid)) $pid = safe_POST_xref('pid', '');
	if (empty($pid)) $pid = safe_GET_xref('rootid',  '');
	if (empty($pid)) $pid = safe_POST_xref('rootid', '');
	if (empty($pid)) $pid = safe_POST_xref('sid', '');
	if (empty($pid)) $pid = safe_GET_xref('sid', '');
	$famid = safe_GET('famid', WT_REGEX_XREF, '');
	if (empty($famid)) $famid = safe_POST('famid', WT_REGEX_XREF, '');
}
?>

<?php
// Sidebar state control
// NOTE: Need config option for setting $sidebar_state.
// 'open' = Sidebar initially open, [default] + normally auto pinned
// 'closed' = Sidebar initially closed, + normally auto unpinned
$sidebar_state = 'open';
global $GOOGLEMAP_MAP_TYPE;

echo '<script type="text/javascript" src="js/jquery/jquery.scrollfollow.js"></script>';
echo WT_JS_START;
?>
jQuery.noConflict(); // @see http://docs.jquery.com/Using_jQuery_with_Other_Libraries/
var loadedMods = new Array();

function closeCallback() {
	jQuery('#sidebarAccordion').hide();
	jQuery('#sidebar_pin').hide();
	if (pinned == false) {
		jQuery.get('individual.php?pid=<?php echo $controller->pid; ?>&action=ajax&pin=false');
		pinned = false;
	}
	// For Google Maps v3 only ---------------------------------------------------------------------
	if (jQuery("#tabs li:eq("+jQuery("#tabs").tabs("option", "selected")+") a").attr("title") == "googlemap") {
		loadMap();
	}
	// ---------------------------------------------------------------------------------------------	
}

function openCallback() {
	jQuery('#sidebarAccordion').accordion({
		/* fillSpace: true, */
		autoHeight: false,
		changestart: function(event, ui) {
			loadedMods[ui.oldHeader.attr('title')] = true;
			var active = ui.newHeader.attr('title');
			if (!loadedMods[active]) {
				jQuery('#sb_content_'+active).load('sidebar.php?sb_action=loadmod&mod='+active+'&pid=<?php echo $pid; ?>&famid=<?php echo $famid; ?>');
			}
		}
	});
	
	// For Google Maps v3 only ---------------------------------------------------------------------
	if (jQuery("#tabs li:eq("+jQuery("#tabs").tabs("option", "selected")+") a").attr("title") == "googlemap") {
		loadMap();
	}
	// ---------------------------------------------------------------------------------------------	
}

jQuery(document).ready(function() {

	// Sidebar Pin Function
	jQuery('#sidebar_pin').toggle(
		function() {
			jQuery('#sidebar_pin img').attr('src', '<?php echo $WT_IMAGES['pin-in']; ?>').attr('title', '<?php echo i18n::translate('Unpin Sidebar'); ?>');
			jQuery.get('individual.php?pid=<?php echo $controller->pid; ?>&action=ajax&pin=true');
			pinned = true;
		},
		function() {
			jQuery('#sidebar_pin img').attr('src', '<?php echo $WT_IMAGES['pin-out']; ?>').attr('title', '<?php echo i18n::translate('Pin Sidebar'); ?>');
		jQuery.get('individual.php?pid=<?php echo $controller->pid; ?>&action=ajax&pin=false');
		pinned = false;
		}
	);
	<?php if (isset($_SESSION['WT_pin']) && $_SESSION['WT_pin']) { ?>
		jQuery('#sidebar_pin').click();
	<?php } ?>
	// ---------------------

	var modsLoaded = false;

	// Sidebar Open/Close Function
	// Sidebar Open
	jQuery('#sidebar_open').toggle(function() {
		jQuery('#sidebar_open img').attr('style', 'margin-left:255px;' ).attr('src', '<?php echo $WT_IMAGES['slide_close']; ?>').attr('title', '<?php echo i18n::translate('Sidebar Close'); ?>');
		jQuery('#sidebar').animate({
			right: "0px",
			width: "260px"
		}, 500);
		if (!modsLoaded) {
			jQuery('#sidebarAccordion').load('sidebar.php', 'sb_action=loadMods&pid=<?php echo $pid; ?>&famid=<?php echo $famid; ?>', openCallback);
			modsLoaded=true;
		} else {
			jQuery("#sidebarAccordion").accordion("resize");
			openCallback();
		}
		jQuery('#sidebarAccordion').show();
		jQuery('#sidebar_pin').show();
		// Shift content
		var newwidth = 310;
		newwidth = jQuery('#tabs').width() - newwidth;
		// NOTE: REM next line to avoid the "page shift" when Navigator is opened. (Purely a preference choice)
		jQuery('#tabs > div').css('width', newwidth+'px');
		//
			<?php if ($sidebar_state == "open" ) { ?>
			jQuery('#sidebar_pin').click();
			<?php } ?>

		<?php if ($sidebar_state == "open") { ?>
			jQuery.get('individual.php?pid=<?php echo $controller->pid; ?>&action=ajax&pin=true&sb_closed=false');
		<?php } ?>

		<?php if ($sidebar_state == "closed") { ?>
			<?php if (isset($_SESSION['WT_pin']) && $_SESSION['WT_pin']) { ?>
				jQuery.get('individual.php?pid=<?php echo $controller->pid; ?>&action=ajax&pin=true&sb_closed=false');
			<?php } else { ?>
				jQuery.get('individual.php?pid=<?php echo $controller->pid; ?>&action=ajax&pin=false&sb_closed=false');
			<?php } ?>
		<?php } ?>
		sb_open=true;
	// Sidebar Close
	}, function() {
		jQuery('#sidebar_open img').attr('style', 'margin-left:0px;' ).attr('src', '<?php echo $WT_IMAGES['slide_open']; ?>').attr('title', '<?php echo i18n::translate('Sidebar Open'); ?>');
		jQuery('#sidebar').css('left', '');
		jQuery('#sidebar').animate({
			right: "4px",
			width: "0px"
		}, 500, 'linear', closeCallback);
		// Shift content back
			jQuery('#tabs div').css('width', '');
		//
		<?php if ($sidebar_state == "open" ) { ?>
			jQuery('#sidebar_pin').click();
		<?php } ?>

		jQuery.get('individual.php?pid=<?php echo $controller->pid; ?>&action=ajax&pin=false&sb_closed=true');
		sb_open=false;
	});
	// -----------------------------

	<?php if  ( $sidebar_state == "open" ) { ?>
		<?php if (isset($_SESSION['WT_pin']) && $_SESSION['WT_pin'] || !isset($_SESSION['WT_sb_closed'])) { ?>
			jQuery('#sidebar_open').click();
			jQuery('#sidebar_controls').show();
			if (pinned==false) {
				jQuery('#sidebar_pin').click();
			}
			<?php } else { ?>
				jQuery('#sidebar_controls').show();
			<?php } ?>
		<?php } ?>

		<?php if ($sidebar_state=="closed") { ?>
			if (pinned==true) {
				jQuery('#sidebar_open').click();
			}
			jQuery('#sidebar_controls').show();
		<?php } ?>

});

<?php
	echo WT_JS_END;
	echo '<div id="sidebar">';
	if (isset($_SESSION['WT_pin']) && $_SESSION['WT_pin'] && $sidebar_state == "open") {
		?>
		<div id="sidebar_controls" class="ui-accordion-header ui-helper-reset ui-state-active ui-corner-top ui-state-focus">
			<a id="sidebar_open" href="#open"><img style="margin-left:0px;" src="<?php echo $WT_IMAGES['slide_close']; ?>" border="0" title="<?php echo i18n::translate('Sidebar Open'); ?>" alt="" /></a>
			<a id="sidebar_pin" href="#pin"><img src="<?php echo $WT_IMAGES['pin-out']; ?>" border="0" title="<?php echo i18n::translate('Pin Sidebar'); ?>" alt="" /></a>
		</div>
		<?php
	} else {
		?>
		<div id="sidebar_controls" class="ui-accordion-header ui-helper-reset ui-state-active ui-corner-top ui-state-focus">
			<a id="sidebar_open" href="#open"><img style="margin-left:0px;" src="<?php echo $WT_IMAGES['slide_open']; ?>" border="0" title="<?php echo i18n::translate('Sidebar Open'); ?>" alt="" /></a>
			<a id="sidebar_pin" href="#pin"><img src="<?php echo $WT_IMAGES['pin-out']; ?>" border="0" title="<?php echo i18n::translate('Pin Sidebar'); ?>" alt="" /></a>
		</div>
		<?php
	}
	?>
	<div id="sidebarAccordion"></div>
	<span class="ui-icon ui-icon-grip-dotted-horizontal" style="margin:2px auto;"></span>
</div>
<div id="debug">
</div>
