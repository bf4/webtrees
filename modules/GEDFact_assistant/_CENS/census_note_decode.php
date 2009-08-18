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

			$text = "xCxAx<table cellpadding=\"0\"><tr><td>" . $text;
			$text = str_replace("<br />.start_formatted_area.<br />", "</td></tr></table><table cellpadding=\"0\"><tr><td>&nbsp;", $text);
				// -- Check for Highlighting and create Header Tooltip explanations (Use embolden) -----------
				$text = str_replace(".b.Name", "<a href=\"#\" alt=\"Full Name or Married name if married\" title=\"Full Name or Married name if married\"><b />Name</a>", $text);
				$text = str_replace(".b.Relation", "<a href=\"#\" alt=\"Relationship to Head of Household\" title=\"Relationship to Head of Household\"><b />Relation</a>", $text);
				$text = str_replace(".b.Assets", "<a href=\"#\" alt=\"Property Value/Rental Value\" title=\"Property Value/Rental Value\"><b />Assets</a>", $text);
				$text = str_replace(".b.Sex", "<a href=\"#\" alt=\"Male (M) or Female (F)\" title=\"Male (M) or Female (F)\"><b />Sex</a>", $text); 
				$text = str_replace(".b.Rce", "<a href=\"#\" alt=\"Color or Race\" title=\"Color or Race\"><b />Rce</a>", $text); 
				$text = str_replace(".b.Age", "<a href=\"#\" alt=\"Age at last birthday\" title=\"Age at last birthday\"><b />Age</a>", $text); 
				$text = str_replace(".b.MC", "<a href=\"#\" alt=\"Marital Condition - M,S,W,D - Married, Single, Widowed or Divorced\" title=\"Marital Condition - M,S,W,D - Married, Single, Widowed or Divorced\"><b />MC</a>", $text); 
				$text = str_replace(".b.AgM", "<a href=\"#\" alt=\"Age at first Marriage\" title=\"Age at first Marriage\"><b />AgM</a>", $text);
				$text = str_replace(".b.Edu", "<a href=\"#\" alt=\"Education - 'SRW' - At School/Can Read/Can Write\" title=\"Education - 'SRW' - At School/Can Read/Can Write\"><b />Edu</a>", $text);
				$text = str_replace(".b.Birth Place", "<a href=\"#\" alt=\"Birthplace (Complete format)\" title=\"Birthplace (Complete format)\"><b />Birth Place</a>", $text);
				$text = str_replace(".b.FBP", "<a href=\"#\" alt=\"Father's Birth Place (Chapman format) - IN, OH, or ENG, FRA etc\" title=\"Father's Birth Place (Chapman format) - IN, OH, or ENG, FRA etc\"><b />FBP</a>", $text); 
				$text = str_replace(".b.MBP", "<a href=\"#\" alt=\"Mother's Birth Place (Chapman format) - IN, OH, or ENG, FRA etc\" title=\"Mother's Birth Place (Chapman format) - IN, OH, or ENG, FRA etc\"><b />MBP</a>", $text);
				$text = str_replace(".b.NL", "<a href=\"#\" alt=\"Native Language (If Foreign Born)\" title=\"Native Language (If Foreign Born)\"><b />NL</a>", $text);
				$text = str_replace(".b.YOI", "<a href=\"#\" alt=\"Year of Immigration (If Foreign Born)\" title=\"Year of Immigration (If Foreign Born)\"><b />YOI</a>", $text);
				$text = str_replace(".b.N_A", "<a href=\"#\" alt=\"Naturalized or Alien (If Foreign Born)\" title=\"Naturalized or Alien (If Foreign Born)\"><b />N_A</a>", $text);
				$text = str_replace(".b.Eng", "<a href=\"#\" alt=\"English Spoken?\" title=\"English Spoken?\"><b />Eng</a>", $text);
				$text = str_replace(".b.Occupation", "<a href=\"#\" alt=\"Occupation\" title=\"Occupation\"><b />Occupation</a>", $text);
				$text = str_replace(".b.Industry", "<a href=\"#\" alt=\"Industry\" title=\"Industry\"><b />Industry</a>", $text);
				$text = str_replace(".b.Employ", "<a href=\"#\" alt=\"Employment - Employer, Worker, Self Employed, Unemployed etc\" title=\"Employment - Employer, Worker, Self Employed, Unemployed etc\"><b />Employ</a>", $text);
				$text = str_replace(".b.EmH", "<a href=\"#\" alt=\"If working at Home - Y/N\" title=\"If working at Home - Y/N?\"><b />EmH</a>", $text);
				$text = str_replace(".b.Vet", "<a href=\"#\" alt=\"War Veteran?\" title=\"War Veteran?\"><b />Vet</a>", $text);
				$text = str_replace(".b.Infirm","<a href=\"#\" alt=\"Infirmaties - 1234 - 1.Deaf and Dumb, 2.Blind, 3.Lunatic, 4.Imbecile, feeble-minded\" title=\"Infirmaties - 1234 - 1.Deaf and Dumb, 2.Blind, 3.Lunatic, 4.Imbecile, feeble-minded\" title=\"War Veteran?\"><b />Infirm</a>", $text);
				$text = str_replace(".b.", "<b />", $text); 
				// -------------------------------------------------------------------------------------------
			$text = str_replace("|", "&nbsp;&nbsp;</td><td>", $text);
			$text = str_replace(".end_formatted_area.<br />", "</td></tr></table><table cellpadding=\"0\"><tr><td>", $text);
			$text = str_replace("<br />", "</td></tr><tr><td>&nbsp;", $text);
			$text = $text . "</td></tr></table>";
			$text = str_replace("xCxAx", $centitl."<br />", $text);
			$text = str_replace("Notes:", "<b>Notes:</b>", $text);

?>