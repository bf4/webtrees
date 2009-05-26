<?php
/**
 * Media Link Assistant Control module for phpGedView
 *
 * Media Link information about an individual
 *
 * phpGedView: Genealogy Viewer
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
 * @package PhpGedView
 * @subpackage GEDFact_assistant
 * @version $Id$
*/

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}
?>

<html>
<head>
<script language="javascript">
	function addlinks(iname) {
		iid=document.getElementById('gid').value;
		if (document.getElementById('gid').value == "") {
			alert('You MUST Enter an Individual ID first to add more Individual links');
		}else{
			addmedia_links(document.getElementById('gid'), document.getElementById('gid').value, iname );
			return false;
		}
	}
</script>

</head>

<table border="0" cellpadding="1" cellspacing="2" ">
<tr>
<td width="350" class="row2">
<?php
	include('modules/GEDFact_assistant/MEDIA/media_5_input.php');
?>
</td>
</tr>
</table>

</body>
</html>

