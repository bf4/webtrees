<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census and Souce Input Area File File
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007 to 2008  PGV Development Team.  All rights reserved.
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
 * @version $Id$
 * @author Brian Holland
 */
if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
global $pgv_lang, $TEXT_DIRECTION;
?>

<script>
	function findSource(field) {
		pastefield = field;
		findwin = window.open('find.php?type=source', '_blank', 'left=50,top=50,width=600,height=500,resizable=1,scrollbars=1');
		return false;
	}
	function openerpasteid(id) {
		window.opener.paste_id(id);
		window.close();
	}

	function paste_id(value) {
		pastefield.value = value;
	}

	function paste_char(value,lang,mag) {
		pastefield.value += value;
		language_filter = lang;
		magnify = mag;
	}

	function edit_close(newurl) {
		if (newurl)
			window.opener.location=newurl;
		else
			if (window.opener.showchanges)
				window.opener.showchanges();
		window.close();
	}
</script>

			<table class="facts_table" cellpadding="0px" width="100%" border=3>
				<tr>
					<td align="left" class="descriptionbox" width="15%"><font size=1>Shared Note Title:</font></td>
					<td align="left" class="facts_value" width="50%">
					<?php
							echo "<input id=\"Titl\" name=\"Titl\" type=\"text\" size=\"90\" STYLE=\"font-size: 10px;\" value=\"Census Transcription - ".$censyear." - ".$wholename." - \" /></td>";
					?>
					<td align="left" class="descriptionbox" width="15%"><font size=1>Census Year:</font></td>
					<td align="left" class="facts_value" width="15%">
						<input name="InputCensusYear" type="text" size="9" STYLE="font-size: 10px;" value="<?php echo $censyear; ?>" /></td>
				</tr>
			</table>
			
