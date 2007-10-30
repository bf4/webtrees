<?php
/**
 * Display an hourglass chart
 *
 * Set the root person using the $pid variable
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * This Page Is Valid XHTML 1.0 Transitional! > 23 August 2005
 *
 * @package PhpGedView
 * @subpackage Charts
 * @version $Id$
 */

/*
 * The purpose of this page is to build the right half of the Hourglass chart via Ajax.
 * This page only produces all Children of passed id with the connecting lines to unite
 * 	and label the set as united siblings.
 * 
 */


require_once("includes/controllers/hourglass_ctrl.php");
$controller->init();

// -- print html header information
?>
<!-- // NOTE: Start table header -->
	<!-- // NOTE: Close table header -->
<!-- // descendancy -->
		<table cellspacing="0" cellpadding="0" border="0"><tr>
		<!-- // descendancy -->
		<td valign="middle">
		<?php
		$controller->print_descendency_ajax($controller->pid, 0);?>
		</td>

</tr></table>
<br /><br />
