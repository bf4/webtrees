<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census Proposed Text Area File
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

global $theme_name; 
// echo '<script src="modules/census_assistant/census_4_text.js" type="text/javascript"></script>';
?>
<script>
function openInNewWindow(frm)
{
	// open a blank window
	var aWindow = window.open('', 'TableAddRow2NewWindow',
	'scrollbars=yes,menubar=yes,resizable=yes,location=no,toolbar=no,width=400,height=700');
	aWindow.focus();
	
	// set the target to the blank window
	frm.target = 'TableAddRow2NewWindow';
	
	// submit
	frm.submit();
}
</script>

			<table class="facts_table" width="60%" border=3>
				<!--   ---- The proposed Census Text -------- -->
				<tr>
					<td align="center" class="descriptionbox" colspan="1">
						<input type="button" value="Help" onclick="openInNewWindow(this.form);" />
					</td>
					<td align="center" class="descriptionbox" colspan="2">
						<b> The Proposed Census Text </b>&nbsp;&nbsp;
						<font size="1">( Check to copy Input Fields Information: 
						<input type="checkbox" name="copy" OnClick="javascript:InputToOutput(this.form);" value="checkbox"> )
						</font>
					</td>
					<td align="center" class="descriptionbox" colspan="1">
						<input type="button" value="Save Census Text" onclick="openInNewWindow(this.form);" />
					</td>
				</tr>
				
				<tr>
					<td class="facts_value" width="100%" colspan="7" >
						<table border="0" class="facts_table" cellspacing="0" >
							<?php 
							if ($theme_name=="SimplyGreen" || $theme_name=="SimplyBlue" || $theme_name=="SimplyRed") {
								$fontcolr="white";
							}else{
								$fontcolr="black";
							}
							?>
							<tr>
								<td colspan="7" ><font size="1">
									<b>&nbsp;Census: &nbsp;</b>
									<input id="OutputCensusSource" STYLE="color:<?php echo $fontcolr ?>; font-size: 10px; border: 0px; background-color: transparent" type="text" size="15" maxlength="20" />
									<input id="OutputCensusPlace"  STYLE="color:<?php echo $fontcolr ?>; font-size: 10px; border: 0px; background-color: transparent" type="text" size="70" maxlength="70" />
									</font>
								</td>
							</tr>
							<tr>
								<td colspan="7" ><font size="1">
									<b>&nbsp;Citation: &nbsp;</b>
									<b>Class:</b><input id="OutputClass" STYLE="color:<?php echo $fontcolr ?>; font-size: 10px; border: 0px; background-color: transparent" type="text" size="4" maxlength="5" />
									<b>Piece:</b><input id="OutputPiece" STYLE="color:<?php echo $fontcolr ?>; font-size: 10px; border: 0px; background-color: transparent" type="text" size="4" maxlength="5" />
									<b>Folio:</b><input id="OutputFolio" STYLE="color:<?php echo $fontcolr ?>; font-size: 10px; border: 0px; background-color: transparent" type="text" size="4" maxlength="5" />
									<b>Page:</b> <input id="OutputPage"  STYLE="color:<?php echo $fontcolr ?>; font-size: 10px; border: 0px; background-color: transparent" type="text" size="4" maxlength="5" />
									</font>
								</td>
							</tr>
							<tr>
								<td colspan="7" ><font size="1">
									<b>&nbsp;Address: </b>
									<input id="OutputAddress" STYLE="color:<?php echo $fontcolr ?>; font-size: 10px; border: 0px; background-color: transparent" type="text" size="70" maxlength="80" />
									</font>
									<br /><br />
								</td>
							</tr>
								
							<?php
							//-- Census Immediate Family
							?>
							<tr>
								<td colspan="7" id="head" class="option_box" style="border: 0px solid transparent;">
									<?php
									include('modules/census_assistant/census_4_text_info.php');
									?> 
								</td>
							</tr>

						</table>

					</td>
				</tr>
			</table>
			
