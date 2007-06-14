<?php
/**
 * Top menu for Standard theme
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003 John Finlay and Others
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
 * @package PhpGedView
 * @subpackage Themes
 * @version $Id: toplinks.php 514 2006-10-19 01:59:43Z canajun2eh $
 */
require_once('includes/treenav_class.php');
require_once('themes/navigator/thememenu.php');
include_once('js/prototype.js.htm');
include_once('js/scriptaculous.js.htm');

global $ALLOW_CHANGE_GEDCOM;

$nav = new TreeNav('none','topnav',-2);
$menubar = new NavMenuBar();

?>
<script type="text/javascript" src="themes/navigator/navigator.js"></script>
<div id="navbar" style="display:none; width: 98%; height: 165px; border-top: solid grey 1px; border-right: solid grey 1px;">
<table border="0" cellspacing="0" width="100%">
<tr>
	<td width="20%" valign="top">
	<div id="navlinks" style="overflow: auto; height: 150px; min-width: 150px;">
		<ul id="navlist" class="navlist">
		<li class="menuitem" style="font-weight: bold;">
			<img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["large"];?>" class="icon" alt="<?php print $pgv_lang["trees"];?>" title="<?php print $pgv_lang["trees"];?>" />
			<a href="#" onclick="highlightMenu(document.getElementById('navlist'), this); loadNav1('treelinks'); return false;"><?php print $pgv_lang["trees"];?></a></li>
		<?php $menubar->navPrintMenu(1); ?>
		<?php $menubar->navPrintMenu(2); ?>
		<li class="menuitem">
			<img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["large"];?>" class="icon" alt="<?php print $pgv_lang["surnames"];?>" title="<?php print $pgv_lang["surnames"];?>" />
			<a href="#" onclick="highlightMenu(document.getElementById('navlist'), this); loadNav1('themes/navigator/thememenu.php?navAjax=1&surnameContent=1'); return false;"><?php print $pgv_lang["surnames"];?></a></li>
		<?php
			for($i=3; $i<18; $i++) {
				$menubar->navPrintMenu($i);
			}
		?>
		</ul>
	</div>
	</td>
	<td id="midcell" width="25%" valign="top" style="border-left: solid grey 1px;">
	<div id="midcontent" style="overflow: auto; height: 150px; min-width: 200px;">
	<ul id="navlist2" class="navlist">
	<?php if ($ALLOW_CHANGE_GEDCOM) { 
		foreach($GEDCOMS as $gedc=>$gedarray) {
			$style="";
			if ($gedc==$GEDCOM) $style='style="font-weight: bold;"';
			?>
			<li class="menuitem" <?php print $style;?>>
			<img src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["large"];?>" class="icon" alt="Trees" title="Trees" />
			<a href="#" onclick="highlightMenu(document.getElementById('navlist2'), this); topnav.newRoot('', topnav.innerPort, '<?php print htmlentities($gedc);?>'); return false;"><?php print $gedarray['title']; ?></a></li>
		<?php }
	} ?>
	</ul>
	</div>
	</td>
	<td id="midcell2" valign="top" style="border-left: solid grey 1px;">
		<div id="midcontent2" style="overflow: auto; height: 150px; width: 0px">
		</div>
	</td>
	<td id="navcontent" valign="top" width="65%" style="border-left: solid grey 1px;">
	<?php $nav->drawViewport('tlnav', "", "150px", -2); ?>
	</td>
</tr>
</table>
<script type="text/javascript">
<!-- 
	nav1Content['treelinks'] = document.getElementById('midcontent').innerHTML;
	var GEDCOM = '<?php print htmlentities($GEDCOM);?>';
//-->
</script>
</div>
<img align="middle" src="<?php print $PGV_IMAGE_DIR."/".$PGV_IMAGES["hline"]["other"]; ?>" width="99%" height="3" alt="" onclick="new Effect.toggle($('navbar'),'blind')" />
<?php include("accesskeyHeaders.php"); ?>
</div>
<div id="content">
