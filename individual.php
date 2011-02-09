<?php
// Individual Page
//
// Display all of the information about an individual
//
// webtrees: Web based Family History software
// Copyright (C) 2011 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// $Id$

define('WT_SCRIPT_NAME', 'individual.php');
require './includes/session.php';

$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;

// -- array of GEDCOM elements that will be found but should not be displayed
$nonfacts = array("FAMS", "FAMC", "MAY", "BLOB", "CHIL", "HUSB", "WIFE", "RFN", "_WT_OBJE_SORT", "");

$nonfamfacts = array(/*"NCHI",*/ "UID", "");

$controller=new WT_Controller_Individual();
$controller->init();

// tell tabs that use jquery that it is already loaded
define('WT_JQUERY_LOADED', 1);

// We have finished writing session data, so release the lock
Zend_Session::writeClose();

print_header($controller->getPageTitle());

if (!$controller->indi) {
	echo "<b>", WT_I18N::translate('Unable to find record with ID'), "</b><br /><br />";
	print_footer();
	exit;
}
else if (!$controller->indi->canDisplayName()) {
	print_privacy_error();
	print_footer();
	exit;
}
$linkToID=$controller->pid; // -- Tell addmedia.php what to link to


?>
<script type="text/javascript" src="js/jquery/jquery/jquery.cookie.js"></script>
<?php echo WT_JS_START;?>
// javascript function to open a window with the raw gedcom in it
function show_gedcom_record(shownew) {
	fromfile="";
	if (shownew=="yes") fromfile='&fromfile=1';
	var recwin = window.open("gedrecord.php?pid=<?php echo $controller->pid; ?>"+fromfile, "_blank", "top=50,left=50,width=600,height=400,scrollbars=1,scrollable=1,resizable=1");
}
<?php if (WT_USER_CAN_EDIT) { ?>
function showchanges() {
	window.location = '<?php echo $controller->indi->getRawUrl(); ?>&show_changes=yes';
}
<?php } ?>

var tabCache = new Array();
var pinned = false;

jQuery(document).ready(function() {
	// TODO: change images directory when the common images will be deleted.
	jQuery('#tabs').tabs({ spinner: '<img src=\"images/loading.gif\" height=\"18\" border=\"0\" alt=\"\" />' });
	jQuery("#tabs").tabs({ cache: true });
	var $tabs = jQuery('#tabs');
	jQuery('#tabs').bind('tabsshow', function(event, ui) {
		var selectedTab = ui.tab.name;
		tabCache[selectedTab] = true;

	<?php
	foreach ($controller->tabs as $tab) {
		echo $tab->getJSCallback()."\n";
	}
	?>
	});
});

jQuery(document).ready(function(){

	// Variables
	var objMain = jQuery('#main');

	// Show sidebar
	function showSidebar(){
		objMain.addClass('use-sidebar');
		jQuery.cookie('sidebar-pref', 'use-sidebar', { expires: 30 });
	}

	// Hide sidebar
	function hideSidebar(){
		objMain.removeClass('use-sidebar');
		jQuery.cookie('sidebar-pref', null, { expires: 30 });
	}

	// Sidebar separator
	var objSeparator = jQuery('#separator');

	objSeparator.click(function(e){
		e.preventDefault();
		if ( objMain.hasClass('use-sidebar') ){
			hideSidebar();
		}
		else {
			showSidebar();
		}
	}).css('height', objSeparator.parent().outerHeight() + 'px');

	// Load preference
	if ( jQuery.cookie('sidebar-pref') == null ){
		objMain.removeClass('use-sidebar');
	}
});

<?php
echo WT_JS_END;
if ((empty($SEARCH_SPIDER))&&($controller->accept_success)) echo "<b>", WT_I18N::translate('Changes successfully accepted into database'), "</b><br />";
if ($controller->indi->isMarkedDeleted()) echo "<span class=\"error\">".WT_I18N::translate('This record has been marked for deletion upon admin approval.')."</span>";
if (strlen($controller->indi->getAddName()) > 0) echo "<span class=\"name_head\">", PrintReady($controller->indi->getAddName()), "</span><br />";

echo '<div id="main" class="use-sidebar sidebar-at-right">';

echo '<div id="indi_mainimage">';
	if ($controller->canShowHighlightedObject()) {
		echo $controller->getHighlightedObject();
	}
echo '</div>';
echo
	'<div id="indi_header">',
	'<h1>';
		if ($TEXT_DIRECTION=="rtl") echo "&nbsp;"; {
			echo PrintReady($controller->indi->getFullName());
		}
		if (WT_USER_IS_ADMIN) {
			$user_id=get_user_from_gedcom_xref(WT_GED_ID, $controller->pid);
			if ($user_id) {
				$user_name=get_user_name($user_id);
				echo "&nbsp;";
				echo printReady("<a href=\"admin_users.php?action=edituser&amp;username={$user_name}\">({$user_name})</a>");
			}
		}
	echo
		'</h1>',
		'<div id="header_accordion">';
		if ($controller->indi->canDisplayDetails()) {
			echo
				'<h3>Name Details</h3>',
				'<div id="indi_name_details">';
				//Display name details
					$globalfacts=$controller->getGlobalFacts();
					$nameSex = array('NAME', 'SEX');
					foreach ($globalfacts as $key=>$value) {
						if ($key == 0) {
						// First name
							$fact = $value->getTag();
							if (in_array($fact, $nameSex)) {
								if ($fact=="NAME") $controller->print_name_record($value);
							}
						} else {
							// 2nd and more names
							$fact = $value->getTag();
							if (in_array($fact, $nameSex)) {
								if ($fact=="NAME") $controller->print_name_record($value);
							}
						}
					}
			echo '</div>';
			//Display facts
			echo
				'<h3>Other facts</h3>',
				'<div id="indi_facts">';
				//Display gender
				foreach ($globalfacts as $key=>$value) {
					$fact = $value->getTag();
					if (in_array($fact, $nameSex)) {
						if ($fact=="SEX") $controller->print_sex_record($value);
					}
				}
				// Display summary birth/death info.
				$summary=$controller->indi->format_first_major_fact(WT_EVENTS_BIRT, 2);
				// If alive display age
				if (!$controller->indi->isDead()) {
					$bdate=$controller->indi->getBirthDate();
					$age = WT_Date::GetAgeGedcom($bdate);
					if ($age!="") $summary.= "<dl><dt class=\"label\">".WT_I18N::translate('Age')."</dt><span class=\"field\">".get_age_at_event($age, true)."</span></dl>";
				}
				$summary.=$controller->indi->format_first_major_fact(WT_EVENTS_DEAT, 2);
				if ($SHOW_LDS_AT_GLANCE) {
					$summary.="<dl><span><b>".get_lds_glance($controller->indi->getGedcomRecord())."</b></span></dl>";
				}
				if ($summary) {
					echo $summary;
				}
		}
		echo '</div>';
	echo '</div>';
echo '</div>';
echo WT_JS_START,'jQuery("#header_accordion").accordion({active:false, autoHeight:false, collapsible: true});',	WT_JS_END;
echo '<div id="sidebar2">';
	if (!$controller->indi->canDisplayDetails()) {
		echo "<table><tr><td class=\"facts_value\" >";
		print_privacy_error();
		echo "</td></tr></table>";
	} else {
		require './sidebar.php';
	}
echo
	'</div>',
	'<a href="#" id="separator"></a>';
	//'<div class="clearer">&nbsp;</div>';

	echo '<div id="hitcounter">';
	if ($SHOW_COUNTER && (empty($SEARCH_SPIDER))) {
		//print indi counter only if displaying a non-private person
		require WT_ROOT.'includes/hitcount.php';
		echo WT_I18N::translate('Hit Count:'), " ", $hitCount;
	}
echo '</div>';

foreach ($controller->tabs as $tab) {
	echo $tab->getPreLoadContent();
}
$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;
// =====================================
	echo '<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">';
	echo '<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">';
	foreach ($controller->tabs as $tab) {
		if ($tab->hasTabContent()) {
			// jQuery UI uses the title attribute to link named tabs to content-divs.
			// Unfortunately, this shows in a tool-tip.  How to improve this?
			if ($tab->getName()==$controller->default_tab) {
				// Default tab loads immediately
				echo '<li class="ui-state-default ui-corner-top ui-tabs-selected"><a title="', $tab->getName(), '" href="#', $tab->getName(), '">';
			} elseif ($tab->canLoadAjax()) {
				// AJAX tabs load later
				echo '<li class="ui-state-default ui-corner-top"><a title="', $tab->getName(), '" href="',$controller->indi->getHtmlUrl(),'&amp;action=ajax&amp;module=', $tab->getName(), '">';
			} else {
				// Non-AJAX tabs load immediately (search engines don't load ajax)
				echo '<li class="ui-state-default ui-corner-top"><a title="', $tab->getName(), '" href="#', $tab->getName(), '">';
			}
			echo '<span>', $tab->getTitle(), '</span></a></li>';
		}
	}
	echo '</ul>';
	foreach ($controller->tabs as $tab) {
		if ($tab->hasTabContent()) {
			if ($tab->getName()==$controller->default_tab || !$tab->canLoadAjax()) {
				echo '<div id="', $tab->getName(), '" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
				echo $tab->getTabContent();
				echo '</div>';
			}
		}
	}
	echo '</div>'; // tabs 

echo '</div>'; // main
echo WT_JS_START;
echo 'var catch_and_ignore; function paste_id(value) {catch_and_ignore = value;}';
echo 'if (typeof toggleByClassName == "undefined") {';
echo 'alert("webtrees.js: A javascript function is missing.  Please clear your Web browser cache");';
echo '}';
echo WT_JS_END;

print_footer();
