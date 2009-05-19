<?php
/**
 * Media Link Assistant Control module for phpGedView
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Census Assistant
 * @version $Id$
 */

?>
<?php if ($THEME_DIR=="themes/simplygreen/" || $THEME_DIR=="themes/simplyred/" || $THEME_DIR=="themes/simplyblue/") { ?>
	<script>
	var txtcolor="#ffffff";
	</script>
<?php }else{ ?>
	<script>
	var txtcolor="#000000";
	</script>
<?php } ?>


<?php if ($THEME_DIR=="themes/simplygreen/" || $THEME_DIR=="themes/simplyred/" || $THEME_DIR=="themes/simplyblue/") { ?>
	<style type="text/css">
	<!--
	#tblSample td, th { padding: 0.2em; }
	.classy0 { font-family: Verdana, Arial, Helvetica, sans-serif; background-color: transparent; color: #ffffff; font-size: 10px; }
	.classy1 { font-family: Verdana, Arial, Helvetica, sans-serif; background-color: transparent; color: #ffffff; font-size: 10px; }
	-->
	</style>
<?php }else{ ?>
	<style type="text/css">
	<!--
	#tblSample td, th { padding: 0.2em; }
	.classy0 { font-family: Verdana, Arial, Helvetica, sans-serif; background-color: transparent; color: #000000; font-size: 10px; }
	.classy1 { font-family: Verdana, Arial, Helvetica, sans-serif; background-color: transparent; color: #000000; font-size: 10px; }
	-->
	</style>
<?php } ?>

<?php
echo '<script src="modules/GEDFact_assistant/MEDIA/media_5_input.js" type="text/javascript"></script>';
?>


	<table width="380" border="0" cellspacing="1" id="tblSample">
		<thead>
		<tr>
			<th class="topbottombar" width="15"  style="text-align:left;font-weight:100;" align="left">#</th>
			<th class="topbottombar" width="40"  style="text-align:left;font-weight:100;" align="left">ID:</th>
			<th class="topbottombar" width="280" style="text-align:left;font-weight:100;" align="left">Name</th>
			<th class="topbottombar" width="25"  style="text-align:left;font-weight:100;" align="left">Remove</th>
			<th width="25" style="border: 0px solid transparent;" align="left"><font size=1><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></font></th>
		</tr>
		</thead>
		<tbody></tbody>
	</table>
	

