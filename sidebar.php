<?php
require_once('config.php');
require_once('includes/classes/class_module.php');

$action = safe_GET('action', PGV_REGEX_ALPHANUM, 'none');
//-- handle ajax calls
if ($action!='none') {
	$sidebarmods = PGVModule::getActiveList('S', PGV_USER_ACCESS_LEVEL);
	uasort($sidebarmods, "PGVModule::compare_sidebar_order");
	
	class tempController {
		var $pid;
		var $famid;
	}
	
	$pid = safe_GET('pid', PGV_REGEX_XREF, '');
	if (!empty($pid)) {
		$controller = new tempController();
		$controller->pid = $pid;
	}
	$pid = safe_GET('rootid', PGV_REGEX_XREF, '');
	if (!empty($pid)) {
		$controller = new tempController();
		$controller->pid = $pid;
	}
	$famid = safe_GET('famid', PGV_REGEX_XREF, '');
	if (!empty($famid)) {
		$controller = new tempController();
		$controller->famid = $famid;
	}
	
	if ($action=='loadMods') {
		$counter = 0;
		foreach($sidebarmods as $mod) {
			if ($mod->hasSidebar()) {
				$sb = $mod->getSidebar();
				if (isset($controller)) $sb->setController($controller);
				if ($sb->hasContent()) {
					?><h3 title="<?php echo $mod->getName()?>"><a href="#"><?php echo $sb->getTitle()?></a></h3>
					<div id="sb_content_<?php echo $mod->getName()?>">
					<?php if ($counter==0) echo $sb->getContent();
					else {?><img src="<?php echo $PGV_IMAGE_DIR ?>/loading.gif" /><?php }?>
					</div>
					<?php 
					$counter++;
				}
			}
		}
		exit;
	}
	if ($action=='loadmod') {
		$modName = safe_GET('mod', PGV_REGEX_ALPHANUM, '');
		if (isset($sidebarmods[$modName])) {
			$mod = $sidebarmods[$modName];
			if ($mod->hasSidebar()) {
				$sb = $mod->getSidebar();
				echo $sb->getContent();
			}
		}
		exit;
	}
	if (isset($sidebarmods[$action])) {
		$mod = $sidebarmods[$action];
		if ($mod->hasSidebar()) {
			$sb = $mod->getSidebar();
			echo $sb->getAjaxContent();
		}
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
}
?>
<style type="text/css">
<!-- 
#sidebar {
	position: absolute;
	right: 1px;
	width: 0px;
	height: 400px;
	z-index: 100;
	margin: 0px;
	background-color: white;
}
#sidebarAccordion {
	display: none;
}
#sidebar_pin {
	display: none;
	padding: 1px;
}
#sidebar_controls {
	position: relative;
	float: left;
	left: -13px;
	z-index: 10;
}
.sb_indi_surname_li, .sb_fam_surname_li {
	list-style-image: url('images/plus.gif');
	white-space: nowrap;
}
.sb_desc_indi_li {
	list-style-type: none;
	white-space: nowrap;
}
.desc_tree_div {
	display: none;
}
.desc_tree_div ul {
	padding: 0px;
	margin-left: 10px;
	margin-top: 0px;
	maring-right: 0px;
}
#sb_desc_content {
	width: 100%;
}
#sb_desc_content ul {
	padding: 0px;
	marging: 0px;
}
.name_tree_div {
	display: none;
}
.name_tree_div li,  .name_tree_div_visible li {
	list-style: none;
	margin: 0px;
	padding: 0px;
	white-space: nowrap;
}

.name_tree_div ul, .name_tree_div_visible ul {
	padding: 0px;
	margin: 0px;
}

#sidebar_open {
	padding: 1px;
}
// -->
</style>
<script type="text/javascript">
<!--
jQuery.noConflict(); // @see http://docs.jquery.com/Using_jQuery_with_Other_Libraries/
var loadedMods = new Array();
function closeCallback() {
	jQuery('#sidebarAccordion').hide();
	jQuery('#sidebar_pin').hide();
}
function openCallback() {
	jQuery('#sidebarAccordion').accordion({
		fillSpace: true, 
		changestart: function(event, ui) {
			loadedMods[ui.oldHeader.attr('title')] = true;
			var active = ui.newHeader.attr('title');
			if (!loadedMods[active]) {
				jQuery('#sb_content_'+active).load('sidebar.php?action=loadmod&mod='+active);
			}
		}
	});
}
jQuery(document).ready(function() {
	jQuery('#sidebar').resizable({
		resize: function() {
			jQuery("#sidebarAccordion").accordion("resize");
		},
		minHeight: 200,
		minWidth: 350,
		maxWidth: 350
	});

	var modsLoaded = false;
	jQuery('#sidebar_open').toggle(function() {
		jQuery('#sidebar_open img').attr('src', '<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES['minus']['other'];?>');
		jQuery('#sidebar').animate({
			right: "0px",
			width: "350px"
		}, 500);
		if (!modsLoaded) {
			jQuery('#sidebarAccordion').load('sidebar.php', 'action=loadMods&pid=<?php echo $pid?>&famid=<?php echo $famid?>', openCallback);
			modsLoaded=true;
		}
		else jQuery("#sidebarAccordion").accordion("resize");
		jQuery('#sidebarAccordion').show();
	}, function() {
		jQuery('#sidebar_open img').attr('src', '<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES['plus']['other'];?>');
		jQuery('#sidebar').css('left', '');
		jQuery('#sidebar').animate({
			right: "0px",
			width: "0px"
		}, 500, 'linear', closeCallback);
	});
});
//-->
</script>
<div id="sidebar">
<div id="sidebar_controls" class="ui-accordion-header ui-helper-reset ui-state-active ui-corner-top ui-state-focus">
<a id="sidebar_open" href="#open"><img src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES['plus']['other'];?>" border="0" alt=""/></a>
<a id="sidebar_pin" href="#pin"><img src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES['pin-out']['other'];?>" border="0" alt=""/></a>
</div>
<div id="sidebarAccordion">
	<img src="<?php echo $PGV_IMAGE_DIR ?>/loading.gif" />
</div>
<span class="ui-icon ui-icon-grip-dotted-horizontal" style="margin:2px auto;"></span>
</div>
