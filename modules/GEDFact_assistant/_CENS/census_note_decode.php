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
	$text = str_replace("<br />.start_formatted_area.<br />", "</td></tr></table><table cellpadding=\"0\"><tr><td class=\"notecell\">&nbsp;", $text);
	
		// -- Create View Header Tooltip explanations (Use embolden) -----------
		$text = str_replace(".b.".$pgv_lang["header_Name"],   "<span class=\"note2\" alt=\"".$pgv_lang["tt_view_Name"]."\"   title=\"".$pgv_lang["tt_view_Name"]."\">  <b>".$pgv_lang["header_Name"]."</span>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Rela"],   "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Rela"]."\"   title=\"".$pgv_lang["tt_view_Rela"]."\">  <b>".$pgv_lang["header_Rela"]."</span>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Asset"],  "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Asset"]."\"  title=\"".$pgv_lang["tt_view_Asset"]."\"> <b>".$pgv_lang["header_Asset"]."</span>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_Sex"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Sex"]."\"    title=\"".$pgv_lang["tt_view_Sex"]."\">   <b>".$pgv_lang["header_Sex"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Race"],   "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Race"]."\"   title=\"".$pgv_lang["tt_view_Race"]."\">  <b>".$pgv_lang["header_Race"]."</span>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Age"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Age"]."\"    title=\"".$pgv_lang["tt_view_Age"]."\">   <b>".$pgv_lang["header_Age"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_MCond"],  "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_MCond"]."\"  title=\"".$pgv_lang["tt_view_MCond"]."\"> <b>".$pgv_lang["header_MCond"]."</span>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_YOB"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_YOB"]."\"    title=\"".$pgv_lang["tt_view_YOB"]."\">   <b>".$pgv_lang["header_YOB"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Bmth"],   "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Bmth"]."\"   title=\"".$pgv_lang["tt_view_Bmth"]."\">  <b>".$pgv_lang["header_Bmth"]."</span>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_YrsM"],   "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_YrsM"]."\"   title=\"".$pgv_lang["tt_view_YrsM"]."\">  <b>".$pgv_lang["header_YrsM"]."</span>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_ChilB"],  "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_ChilB"]."\"  title=\"".$pgv_lang["tt_view_ChilB"]."\"> <b>".$pgv_lang["header_ChilB"]."</span>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_ChilL"],  "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_ChilL"]."\"  title=\"".$pgv_lang["tt_view_ChilL"]."\"> <b>".$pgv_lang["header_ChilL"]."</span>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_ChilD"],  "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_ChilD"]."\"  title=\"".$pgv_lang["tt_view_ChilD"]."\"> <b>".$pgv_lang["header_ChilD"]."</span>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_AgM"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_AgM"]."\"    title=\"".$pgv_lang["tt_view_AgM"]."\">   <b>".$pgv_lang["header_AgM"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Occu"],   "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Occu"]."\"   title=\"".$pgv_lang["tt_view_Occu"]."\">  <b>".$pgv_lang["header_Occu"]."</span>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Bplace"], "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Bplace"]."\" title=\"".$pgv_lang["tt_view_Bplace"]."\"><b>".$pgv_lang["header_Bplace"]."</span>", $text);
		$text = str_replace(".b.".$pgv_lang["header_BP"],     "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_BP"]."\"     title=\"".$pgv_lang["tt_view_BP"]."\">    <b>".$pgv_lang["header_BP"]."</span>",     $text);
		$text = str_replace(".b.".$pgv_lang["header_FBP"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_FBP"]."\"    title=\"".$pgv_lang["tt_view_FBP"]."\">   <b>".$pgv_lang["header_FBP"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_MBP"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_MBP"]."\"    title=\"".$pgv_lang["tt_view_MBP"]."\">   <b>".$pgv_lang["header_MBP"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_NL"],     "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_NL"]."\"     title=\"".$pgv_lang["tt_view_NL"]."\">    <b>".$pgv_lang["header_NL"]."</span>",     $text);
		$text = str_replace(".b.".$pgv_lang["header_YrsUS"],  "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_YrsUS"]."\"  title=\"".$pgv_lang["tt_view_YrsUS"]."\"> <b>".$pgv_lang["header_YrsUS"]."</span>",  $text);
		$text = str_replace(".b.".$pgv_lang["header_YOI"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_YOI"]."\"    title=\"".$pgv_lang["tt_view_YOI"]."\">   <b>".$pgv_lang["header_YOI"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_NA"],     "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_NA"]."\"     title=\"".$pgv_lang["tt_view_NA"]."\">    <b>".$pgv_lang["header_NA"]."</span>",     $text);
		$text = str_replace(".b.".$pgv_lang["header_YON"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_YON"]."\"    title=\"".$pgv_lang["tt_view_YON"]."\">   <b>".$pgv_lang["header_YON"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_EngL"],   "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_EngL"]."\"   title=\"".$pgv_lang["tt_view_EngL"]."\">  <b>".$pgv_lang["header_EngL"]."</span>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Health"], "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Health"]."\" title=\"".$pgv_lang["tt_view_Health"]."\"><b>".$pgv_lang["header_Health"]."</span>", $text);
		$text = str_replace(".b.".$pgv_lang["header_Ind"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Ind"]."\"    title=\"".$pgv_lang["tt_view_Ind"]."\">   <b>".$pgv_lang["header_Ind"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Emp"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Emp"]."\"    title=\"".$pgv_lang["tt_view_Emp"]."\">   <b>".$pgv_lang["header_Emp"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_EmR"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_EmR"]."\"    title=\"".$pgv_lang["tt_view_EmR"]."\">   <b>".$pgv_lang["header_EmR"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_EmD"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_EmD"]."\"    title=\"".$pgv_lang["tt_view_EmD"]."\">   <b>".$pgv_lang["header_EmD"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_EmH"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_EmH"]."\"    title=\"".$pgv_lang["tt_view_EmH"]."\">   <b>".$pgv_lang["header_EmH"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_EmN"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_EmN"]."\"    title=\"".$pgv_lang["tt_view_EmN"]."\">   <b>".$pgv_lang["header_EmN"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Educ"],   "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Educ"]."\"   title=\"".$pgv_lang["tt_view_Educ"]."\">  <b>".$pgv_lang["header_Educ"]."</span>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Eng"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Eng"]."\"    title=\"".$pgv_lang["tt_view_Eng"]."\">   <b>".$pgv_lang["header_Eng"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_BIC"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_BIC"]."\"    title=\"".$pgv_lang["tt_view_BIC"]."\">   <b>".$pgv_lang["header_BIC"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_BOE"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_BOE"]."\"    title=\"".$pgv_lang["tt_view_BOE"]."\">   <b>".$pgv_lang["header_BOE"]."</span>",    $text);
		$text = str_replace(".b.".$pgv_lang["header_Lang"],   "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Lang"]."\"   title=\"".$pgv_lang["tt_view_Lang"]."\">  <b>".$pgv_lang["header_Lang"]."</span>",   $text);
		$text = str_replace(".b.".$pgv_lang["header_Infirm"], "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Infirm"]."\" title=\"".$pgv_lang["tt_view_Infirm"]."\"><b>".$pgv_lang["header_Infirm"]."</span>", $text);
		$text = str_replace(".b.".$pgv_lang["header_Vet"],    "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Vet"]."\"    title=\"".$pgv_lang["tt_view_Vet"]."\">   <b>".$pgv_lang["header_Vet"]."</span>",    $text);

		$text = str_replace(".b.".$pgv_lang["header_Tenure"],      "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Tenure"]."\"       title=\"".$pgv_lang["tt_view_Tenure"]."\">     <b>".$pgv_lang["header_Tenure"]."</span>",      $text);
		$text = str_replace(".b.".$pgv_lang["header_Parent"],      "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Parent"]."\"       title=\"".$pgv_lang["tt_view_Parent"]."\">     <b>".$pgv_lang["header_Parent"]."</span>",      $text);
		$text = str_replace(".b.".$pgv_lang["header_Mmth"],        "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Mmth"]."\"         title=\"".$pgv_lang["tt_view_Mmth"]."\">       <b>".$pgv_lang["header_Mmth"]."</span>",        $text);
		$text = str_replace(".b.".$pgv_lang["header_Mnse"],        "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Mnse"]."\"         title=\"".$pgv_lang["tt_view_Mnse"]."\">       <b>".$pgv_lang["header_Mnse"]."</span>",        $text);
		$text = str_replace(".b.".$pgv_lang["header_Wksu"],        "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Wksu"]."\"         title=\"".$pgv_lang["tt_view_Wksu"]."\">       <b>".$pgv_lang["header_Wksu"]."</span>",        $text);
		$text = str_replace(".b.".$pgv_lang["header_Mnsu"],        "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Mnsu"]."\"         title=\"".$pgv_lang["tt_view_Eng"]."\">        <b>".$pgv_lang["header_Eng"]."</span>",         $text);
		$text = str_replace(".b.".$pgv_lang["header_Educpre1890"], "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Educpre1890"]."\"  title=\"".$pgv_lang["tt_view_Educpre1890"]."\"><b>".$pgv_lang["header_Educpre1890"]."</span>", $text);
		$text = str_replace(".b.".$pgv_lang["header_Home"],        "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Home"]."\"         title=\"".$pgv_lang["tt_view_Home"]."\">       <b>".$pgv_lang["header_Home"]."</span>",        $text);
		$text = str_replace(".b.".$pgv_lang["header_Situ"],        "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Situ"]."\"         title=\"".$pgv_lang["tt_view_Situ"]."\">       <b>".$pgv_lang["header_Situ"]."</span>",        $text);
		$text = str_replace(".b.".$pgv_lang["header_War"],         "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_War"]."\"          title=\"".$pgv_lang["tt_view_War"]."\">        <b>".$pgv_lang["header_War"]."</span>",         $text);
		$text = str_replace(".b.".$pgv_lang["header_Infirm1910"],  "<span class=\"note1\" alt=\"".$pgv_lang["tt_view_Infirm1910"]."\"   title=\"".$pgv_lang["tt_view_Infirm1910"]."\"> <b>".$pgv_lang["header_Infirm1910"]."</span>",  $text);


		// Regular Field Highlighting (Use embolden) ------------
		$text = str_replace(".b.", "<b />", $text); 
		
		// Replace "pipe" with </td><td> ------------------------
		$text = str_replace("|", "&nbsp;&nbsp;</td><td class=\"notecell\">", $text);
		
	$text = str_replace(".end_formatted_area.<br />", "</td></tr></table><table cellpadding=\"0\"><tr><td>", $text);
	$text = str_replace("<br />", "</td></tr><tr><td class=\"notecell\">&nbsp;", $text);
	$text = $text . "</td></tr></table>";
	$text = str_replace("xCxAx", $centitl."<br />", $text);
	$text = str_replace("Notes:", "<b>Notes:</b>", $text);

?>