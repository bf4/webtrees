<?php
/**
 * Census Assistant Control module for phpGedView
 *
 * Census Shared Note Decode for a formatted file
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
 
 // Load the GEDFact_assistant language files ==============
	loadLangFile("GEDFact_assistant:lang");
 
	$text = "xCxAx<table cellpadding=\"0\"><tr><td>" . $text;
	$text = str_replace("<br />.start_formatted_area.<br />", "</td></tr></table><table cellpadding=\"0\"><tr><td>&nbsp;", $text);
	
		// -- Create View Header Tooltip explanations (Use embolden) -----------
		$text = str_replace(".b.".$pgv_lang["header_Name"],   "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Name"]."\"   title=\"".$pgv_lang["tt_view_Name"]."\">  <b />".$pgv_lang["header_Name"]."</a>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Rela"],   "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Rela"]."\"   title=\"".$pgv_lang["tt_view_Rela"]."\">  <b />".$pgv_lang["header_Rela"]."</a>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Asset"],  "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Asset"]."\"  title=\"".$pgv_lang["tt_view_Asset"]."\"> <b />".$pgv_lang["header_Asset"]."</a>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_Sex"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Sex"]."\"    title=\"".$pgv_lang["tt_view_Sex"]."\">   <b />".$pgv_lang["header_Sex"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Race"],   "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Race"]."\"   title=\"".$pgv_lang["tt_view_Race"]."\">  <b />".$pgv_lang["header_Race"]."</a>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Age"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Age"]."\"    title=\"".$pgv_lang["tt_view_Age"]."\">   <b />".$pgv_lang["header_Age"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_MCond"],  "<a href=\"#\" alt=\"".$pgv_lang["tt_view_MCond"]."\"  title=\"".$pgv_lang["tt_view_MCond"]."\"> <b />".$pgv_lang["header_MCond"]."</a>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_YOB"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_YOB"]."\"    title=\"".$pgv_lang["tt_view_YOB"]."\">   <b />".$pgv_lang["header_YOB"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Bmth"],   "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Bmth"]."\"   title=\"".$pgv_lang["tt_view_Bmth"]."\">  <b />".$pgv_lang["header_Bmth"]."</a>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_YrsM"],   "<a href=\"#\" alt=\"".$pgv_lang["tt_view_YrsM"]."\"   title=\"".$pgv_lang["tt_view_YrsM"]."\">  <b />".$pgv_lang["header_YrsM"]."</a>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_ChilB"],  "<a href=\"#\" alt=\"".$pgv_lang["tt_view_ChilB"]."\"  title=\"".$pgv_lang["tt_view_ChilB"]."\"> <b />".$pgv_lang["header_ChilB"]."</a>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_ChilL"],  "<a href=\"#\" alt=\"".$pgv_lang["tt_view_ChilL"]."\"  title=\"".$pgv_lang["tt_view_ChilL"]."\"> <b />".$pgv_lang["header_ChilL"]."</a>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_ChilD"],  "<a href=\"#\" alt=\"".$pgv_lang["tt_view_ChilD"]."\"  title=\"".$pgv_lang["tt_view_ChilD"]."\"> <b />".$pgv_lang["header_ChilD"]."</a>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_AgM"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_AgM"]."\"    title=\"".$pgv_lang["tt_view_AgM"]."\">   <b />".$pgv_lang["header_AgM"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Occu"],   "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Occu"]."\"   title=\"".$pgv_lang["tt_view_Occu"]."\">  <b />".$pgv_lang["header_Occu"]."</a>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Bplace"], "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Bplace"]."\" title=\"".$pgv_lang["tt_view_Bplace"]."\"><b />".$pgv_lang["header_Bplace"]."</a>", $text);
		$text = str_replace(".b.".$pgv_lang["header_BP"],     "<a href=\"#\" alt=\"".$pgv_lang["tt_view_BP"]."\"     title=\"".$pgv_lang["tt_view_BP"]."\">    <b />".$pgv_lang["header_BP"]."</a>",     $text);
		$text = str_replace(".b.".$pgv_lang["header_FBP"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_FBP"]."\"    title=\"".$pgv_lang["tt_view_FBP"]."\">   <b />".$pgv_lang["header_FBP"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_MBP"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_MBP"]."\"    title=\"".$pgv_lang["tt_view_MBP"]."\">   <b />".$pgv_lang["header_MBP"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_NL"],     "<a href=\"#\" alt=\"".$pgv_lang["tt_view_NL"]."\"     title=\"".$pgv_lang["tt_view_NL"]."\">    <b />".$pgv_lang["header_NL"]."</a>",     $text);
		$text = str_replace(".b.".$pgv_lang["header_YrsUS"],  "<a href=\"#\" alt=\"".$pgv_lang["tt_view_YrsUS"]."\"  title=\"".$pgv_lang["tt_view_YrsUS"]."\"> <b />".$pgv_lang["header_YrsUS"]."</a>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_YOI"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_YOI"]."\"    title=\"".$pgv_lang["tt_view_YOI"]."\">   <b />".$pgv_lang["header_YOI"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_NA"],     "<a href=\"#\" alt=\"".$pgv_lang["tt_view_NA"]."\"     title=\"".$pgv_lang["tt_view_NA"]."\">    <b />".$pgv_lang["header_NA"]."</a>",     $text);
		$text = str_replace(".b.".$pgv_lang["header_YON"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_YON"]."\"    title=\"".$pgv_lang["tt_view_YON"]."\">   <b />".$pgv_lang["header_YON"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_EngL"],   "<a href=\"#\" alt=\"".$pgv_lang["tt_view_EngL"]."\"   title=\"".$pgv_lang["tt_view_EngL"]."\">  <b />".$pgv_lang["header_EngL"]."</a>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Health"], "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Health"]."\" title=\"".$pgv_lang["tt_view_Health"]."\"><b />".$pgv_lang["header_Health"]."</a>", $text);
		$text = str_replace(".b.".$pgv_lang["header_Ind"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Ind"]."\"    title=\"".$pgv_lang["tt_view_Ind"]."\">   <b />".$pgv_lang["header_Ind"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Emp"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Emp"]."\"    title=\"".$pgv_lang["tt_view_Emp"]."\">   <b />".$pgv_lang["header_Emp"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_EmR"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_EmR"]."\"    title=\"".$pgv_lang["tt_view_EmR"]."\">   <b />".$pgv_lang["header_EmR"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_EmD"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_EmD"]."\"    title=\"".$pgv_lang["tt_view_EmD"]."\">   <b />".$pgv_lang["header_EmD"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_EmH"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_EmH"]."\"    title=\"".$pgv_lang["tt_view_EmH"]."\">   <b />".$pgv_lang["header_EmH"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_EmN"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_EmN"]."\"    title=\"".$pgv_lang["tt_view_EmN"]."\">   <b />".$pgv_lang["header_EmN"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Educ"],   "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Educ"]."\"   title=\"".$pgv_lang["tt_view_Educ"]."\">  <b />".$pgv_lang["header_Educ"]."</a>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Eng"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Eng"]."\"    title=\"".$pgv_lang["tt_view_Eng"]."\">   <b />".$pgv_lang["header_Eng"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_BIC"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_BIC"]."\"    title=\"".$pgv_lang["tt_view_BIC"]."\">   <b />".$pgv_lang["header_BIC"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_BOE"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_BOE"]."\"    title=\"".$pgv_lang["tt_view_BOE"]."\">   <b />".$pgv_lang["header_BOE"]."</a>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Lang"],   "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Lang"]."\"   title=\"".$pgv_lang["tt_view_Lang"]."\">  <b />".$pgv_lang["header_Lang"]."</a>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Infirm"], "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Infirm"]."\" title=\"".$pgv_lang["tt_view_Infirm"]."\"><b />".$pgv_lang["header_Infirm"]."</a>", $text);
		$text = str_replace(".b.".$pgv_lang["header_Vet"],    "<a href=\"#\" alt=\"".$pgv_lang["tt_view_Vet"]."\"    title=\"".$pgv_lang["tt_view_Vet"]."\">   <b />".$pgv_lang["header_Vet"]."</a>",    $text);

		// Regular Field Highlighting (Use embolden) ------------
		$text = str_replace(".b.", "<b />", $text); 
		
		// Replace "pipe" with </td><td> ------------------------
		$text = str_replace("|", "&nbsp;&nbsp;</td><td>", $text);
		
	$text = str_replace(".end_formatted_area.<br />", "</td></tr></table><table cellpadding=\"0\"><tr><td>", $text);
	$text = str_replace("<br />", "</td></tr><tr><td>&nbsp;", $text);
	$text = $text . "</td></tr></table>";
	$text = str_replace("xCxAx", $centitl."<br />", $text);
	$text = str_replace("Notes:", "<b>Notes:</b>", $text);

?>