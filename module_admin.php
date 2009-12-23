<?php
/**
 * Module Administration User Interface.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team
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
 * @subpackage Module
 * @version $Id: admin.php 5151 2009-03-04 18:51:04Z canajun2eh $
 */

require './config.php';
require_once('includes/classes/class_module.php');

if (!PGV_USER_GEDCOM_ADMIN) {
	header("Location: login.php?url=module_admin.php");
	exit;
}

function write_access_option_numeric($checkVar) {
	global $pgv_lang;

	echo "<option value=\"".PGV_PRIV_PUBLIC."\"";
	echo ($checkVar==PGV_PRIV_PUBLIC) ? " selected=\"selected\"" : '';
	echo ">".$pgv_lang["PRIV_PUBLIC"]."</option>\n";

	echo "<option value=\"".PGV_PRIV_USER."\"";
	echo ($checkVar==PGV_PRIV_USER) ? " selected=\"selected\"" : '';
	echo ">".$pgv_lang["PRIV_USER"]."</option>\n";

	echo "<option value=\"".PGV_PRIV_NONE."\"";
	echo ($checkVar==PGV_PRIV_NONE) ? " selected=\"selected\"" : '';
	echo ">".$pgv_lang["PRIV_NONE"]."</option>\n";

	echo "<option value=\"".PGV_PRIV_HIDE."\"";
	echo ($checkVar==PGV_PRIV_HIDE) ? " selected=\"selected\"" : '';
	echo ">".$pgv_lang["PRIV_HIDE"]."</option>\n";
}

loadLangFile("pgv_confighelp");

$action = safe_POST('action');

$modules = PGVModule::getInstalledList();
uasort($modules, "PGVModule::compare_name");

if ($action=='update_mods') {
  foreach($modules as $mod) {
    foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
      $varname = 'accessLevel-'.$mod->getName().'-'.$ged_id;
      $value = safe_POST($varname);
      if ($value!=null) $mod->setAccessLevel($value, $ged_id);

      $varname = 'menuaccess-'.$mod->getName().'-'.$ged_id;
      $value = safe_POST($varname);
      if ($value>$mod->getAccessLevel($ged_id)) $value=$mod->getAccessLevel($ged_id);
      if ($value!=null) $mod->setMenuEnabled($value, $ged_id);

      $varname = 'tabaccess-'.$mod->getName().'-'.$ged_id;
      $value = safe_POST($varname);
      if ($value>$mod->getAccessLevel($ged_id)) $value=$mod->getAccessLevel($ged_id);
      if ($value!=null) $mod->setTabEnabled($value, $ged_id);
      
      $varname = 'sidebaraccess-'.$mod->getName().'-'.$ged_id;
      $value = safe_POST($varname);
      if ($value>$mod->getAccessLevel($ged_id)) $value=$mod->getAccessLevel($ged_id);
      if ($value!=null) $mod->setSidebarEnabled($value, $ged_id);
    }

    $value = safe_POST_integer('taborder-'.$mod->getName(), 0, 100, $mod->getTaborder());
    $mod->setTaborder($value);
    $mod->setMenuorder(safe_POST_integer('menuorder-'.$mod->getName(), 0, 100, $mod->getMenuorder()));
    $mod->setSidebarorder(safe_POST_integer('sideorder-'.$mod->getName(), 0, 100, $mod->getSidebarorder()));
	PGVModule::updateModule($mod);
  }
}

print_header($pgv_lang["module_admin"]);
?>
<style type="text/css">
<!--
.sortme {
	cursor: move;
}
.sortme img {
	cursor: pointer;
}
//-->
</style>
<script type="text/javascript">
//<![CDATA[
           
  function reindexMods(id) {
	  jQuery('#'+id+' input').each(
	  	function (index, value) {
	    	value.value = index+1;
	  	});
  }
  
  jQuery(document).ready(function(){
	//-- tabs
    jQuery("#tabs").tabs();

    //-- sortable menus and tabs tables
    jQuery("#menus_table, #tabs_table, #sidebars_table").sortable({items: '.sortme', forceHelperSize: true, forcePlaceholderSize: true, opacity: 0.7, cursor: 'move', axis: 'y'});

    //-- update the order numbers after drag-n-drop sorting is complete
    jQuery('#menus_table').bind('sortupdate', function(event, ui) {
			var id = jQuery(this).attr('id');
			reindexMods(id);  		
  	  });

    jQuery('#tabs_table').bind('sortupdate', function(event, ui) {
		var id = jQuery(this).attr('id');
		reindexMods(id);  		
	  });

    jQuery('#sidebars_table').bind('sortupdate', function(event, ui) {
		var id = jQuery(this).attr('id');
		reindexMods(id);  		
	  });
    
    //-- enable the arrows buttons
    jQuery(".uarrow").click(function() {
        var curr = jQuery(this).parent().parent().get(0);
        var prev = jQuery(curr).prev();
        if (prev) jQuery(prev).insertAfter(curr);
        reindexMods('menus_table');
        reindexMods('tabs_table');
        reindexMods('sidebars_table');
    });

    jQuery(".udarrow").click(function() {
        var curr = jQuery(this).parent().parent().get(0);
        var prev = jQuery(curr).parent().children().get(0);
        if (prev) jQuery(curr).insertBefore(prev);
        reindexMods('menus_table');
        reindexMods('tabs_table');
        reindexMods('sidebars_table');
    });

    jQuery(".darrow").click(function() {
        var curr = jQuery(this).parent().parent().get(0);
        var next = jQuery(curr).next();
        if (next) jQuery(next).insertBefore(curr);
        reindexMods('menus_table');
        reindexMods('tabs_table');
        reindexMods('sidebars_table');
    });

    jQuery(".ddarrow").click(function() {
	    var curr = jQuery(this).parent().parent().get(0);
	    var prev = jQuery(curr).parent().children(":last").get(0);
	    if (prev) jQuery(curr).insertAfter(prev);
	    reindexMods('menus_table');
	    reindexMods('tabs_table');
	    reindexMods('sidebars_table');
	});
  });
//]]>
  </script>
<div align="center">
<div class="width75">

<p><?php echo "<h2>".$pgv_lang["module_admin"]."</h2>"; ?></p>
<p><?php echo $pgv_lang['mod_admin_intro']?></p>
<p><input TYPE="button" VALUE="<?php echo $pgv_lang["ret_admin"];?>" onclick="javascript:window.location='admin.php'" /></p>

<form method="post" action="module_admin.php"> 
	<input type="hidden" name="action" value="update_mods" />

<div id="tabs">
<ul>
	<li><a href="#installed_tab"><span><?php echo $pgv_lang['mod_admin_installed']?></span></a></li>
	<li><a href="#menus_tab"><span><?php echo $pgv_lang['mod_admin_menus']?></span></a></li>
	<li><a href="#tabs_tab"><span><?php echo $pgv_lang['mod_admin_tabs']?></span></a></li>
	<li><a href="#sidebars_tab"><span><?php echo $pgv_lang['mod_admin_sidebars']?></span></a></li>
</ul>
<div id="installed_tab">
<!-- installed -->
  <table id="installed_table" class="list_table">
    <thead>
      <tr>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_active']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_config']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_name']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_description']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_version']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_hastab']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_hasmenu']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_hasside']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_access_level']?></th>
      </tr>
    </thead>
    <tbody>
<?php
foreach($modules as $mod) {
	?><tr>
	<td class="list_value"><?php if ($mod->getId()>0) echo $pgv_lang['yes']; else echo $pgv_lang['no']; ?></td>
	<td class="list_value"><?php if ($mod->getConfigLink()) echo '<a href="'.$mod->getConfigLink().'"><img class="adminicon" src="'.$PGV_IMAGE_DIR.'/'.$PGV_IMAGES["admin"]["small"].'" border="0" alt="'.$mod->getName().'" /></a>'; ?></td>
	<td class="list_value"><?php echo $mod->getName()?></td>
	<td class="list_value_wrap"><?php echo $mod->getDescription()?></td>
	<td class="list_value"><?php echo $mod->getVersion() . " / " . $mod->getPgvVersion() ?></td>
	<td class="list_value"><?php if ($mod->hasTab()) echo $pgv_lang['yes']; else echo $pgv_lang['no'];?></td>
	<td class="list_value"><?php if ($mod->hasMenu()) echo $pgv_lang['yes']; else echo $pgv_lang['no'];?></td>
	<td class="list_value"><?php if ($mod->hasSidebar()) echo $pgv_lang['yes']; else echo $pgv_lang['no'];?></td>
	<td class="list_value_wrap">
	  <table>
	<?php
		foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
			$varname = 'accessLevel-'.$mod->getName().'-'.$ged_id;
			?>
			<tr><td><?php echo $ged_name ?></td><td>
			<select id="<?php echo $varname?>" name="<?php echo $varname?>">
				<?php write_access_option_numeric($mod->getAccessLevel($ged_id)) ?>
			</select></td></tr>
			<?php 
		} 
	?>
	  </table>
	</td>
	</tr>
	<?php 
}
?>
    </tbody>
  </table>
</div>
<div id="menus_tab">
<!-- menus -->
<table id="menus_table" class="list_table">
    <thead>
      <tr>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_name']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_description']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_order']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_access_level']?></th>
      </tr>
    </thead>
    <tbody>
<?php
uasort($modules, "PGVModule::compare_menu_order");
$order = 1;
foreach($modules as $mod) {
	if(!$mod->hasMenu()) continue;
if ($mod->getMenuorder()==0) $mod->setMenuorder($order);
	?><tr class="sortme">
	<td class="list_value"><?php echo $mod->getName()?></td>
	<td class="list_value_wrap"><?php echo $mod->getDescription()?></td>
	<td class="list_value"><input type="text" size="5" value="<?php echo $order; ?>" name="menuorder-<?php echo $mod->getName() ?>" />
		<br />
		<img class="uarrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["uarrow"]["other"];?>" border="0" title="move up" />
		<img class="udarrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["udarrow"]["other"];?>" border="0" title="move to top" />
		<img class="darrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["darrow"]["other"];?>" border="0" title="move down" />
		<img class="ddarrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["ddarrow"]["other"];?>" border="0" title="move to bottom" />
	</td>
	<td class="list_value_wrap">
	  <table>
	<?php
		foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
			$varname = 'menuaccess-'.$mod->getName().'-'.$ged_id;
			?>
			<tr><td><?php echo $ged_name ?></td><td>
			<select id="<?php echo $varname?>" name="<?php echo $varname?>">
				<?php write_access_option_numeric($mod->getMenuEnabled($ged_id)) ?>
			</select></td></tr>
			<?php 
		} 
	?>
	  </table>
	</td>
	</tr>
	<?php
$order++; 
}
?>
    </tbody>
  </table>
</div>
<div id="tabs_tab">
<!-- tabs -->
<table id="tabs_table" class="list_table">
    <thead>
      <tr>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_name']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_description']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_order']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_access_level']?></th>
      </tr>
    </thead>
    <tbody>
<?php
uasort($modules, "PGVModule::compare_tab_order");
$order = 1;
foreach($modules as $mod) {
	if(!$mod->hasTab()) continue;
	if ($mod->getTaborder()==0) $mod->setTaborder($order);
	?><tr class="sortme">
	<td class="list_value"><?php echo $mod->getName()?></td>
	<td class="list_value_wrap"><?php echo $mod->getDescription()?></td>
	<td class="list_value"><input type="text" size="5" value="<?php echo $order; ?>" name="taborder-<?php echo $mod->getName() ?>" />
		<br />
		<img class="uarrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["uarrow"]["other"];?>" border="0" title="move up" />
		<img class="udarrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["udarrow"]["other"];?>" border="0" title="move to top" />
		<img class="darrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["darrow"]["other"];?>" border="0" title="move down" />
		<img class="ddarrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["ddarrow"]["other"];?>" border="0" title="move to bottom" />
	</td>
	<td class="list_value_wrap">
	<table>
	<?php
		foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
			$varname = 'tabaccess-'.$mod->getName().'-'.$ged_id;
			?>
			<tr><td><?php echo $ged_name ?></td><td>
			<select id="<?php echo $varname?>" name="<?php echo $varname?>">
				<?php write_access_option_numeric($mod->getTabEnabled($ged_id)) ?>
			</select></td></tr>
			<?php 
		} 
	?>
	</table>
	</td>
	</tr>
	<?php
$order++; 
}
?>
    </tbody>
  </table>
</div>
<div id="sidebars_tab">
<!-- sidebars -->
<table id="sidebars_table" class="list_table">
    <thead>
      <tr>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_name']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_description']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_order']?></th>
      <th class="list_label"><?php echo $pgv_lang['mod_admin_access_level']?></th>
      </tr>
    </thead>
    <tbody>
<?php
uasort($modules, "PGVModule::compare_sidebar_order");
$order = 1;
foreach($modules as $mod) {
	if(!$mod->hasSidebar()) continue;
	if ($mod->getSidebarorder()==0) $mod->setSidebarorder($order);
	?><tr class="sortme">
	<td class="list_value"><?php echo $mod->getName()?></td>
	<td class="list_value_wrap"><?php echo $mod->getDescription()?></td>
	<td class="list_value"><input type="text" size="5" value="<?php echo $order; ?>" name="sideorder-<?php echo $mod->getName() ?>" />
		<br />
		<img class="uarrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["uarrow"]["other"];?>" border="0" title="move up" />
		<img class="udarrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["udarrow"]["other"];?>" border="0" title="move to top" />
		<img class="darrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["darrow"]["other"];?>" border="0" title="move down" />
		<img class="ddarrow" src="<?php echo $PGV_IMAGE_DIR."/".$PGV_IMAGES["ddarrow"]["other"];?>" border="0" title="move to bottom" />
	</td>
	<td class="list_value_wrap">
	<table>
	<?php
		foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
			$varname = 'sidebaraccess-'.$mod->getName().'-'.$ged_id;
			?>
			<tr><td><?php echo $ged_name ?></td><td>
			<select id="<?php echo $varname?>" name="<?php echo $varname?>">
				<?php write_access_option_numeric($mod->getSidebarEnabled($ged_id)) ?>
			</select></td></tr>
			<?php 
		} 
	?>
	</table>
	</td>
	</tr>
	<?php
$order++; 
}
?>
    </tbody>
  </table>
</div>
<input type="submit" value="<?php echo $pgv_lang['save']?>" />
</div>
</form>
</div>
</div>
<?php
print_footer();
?>