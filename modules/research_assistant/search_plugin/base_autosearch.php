<?php 
/**
 * Common functions for the Search Plug-in of the Auto search Assistant
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  Greg Roach.  All rights reserved
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
 * @version $Id$
 * @package PhpGedView
 * @subpackage Research Assistant
 */

require_once 'includes/person_class.php';

class Base_AutoSearch {
	static $url=null;
	static $method=null;
	static $title=null;
	static $fields=null;

	// Constructor simply defines the fields to be used
	function Base_AutoSearch($title, $url, $method, $fields) {
		$this->title =$title;
		$this->url   =$url;
		$this->method=$method;
		$this->fields=$fields;
	}
	
	// Generate a form to show the search options
	function options() {
		global $pgv_lang;

		$person=Person::getInstance(safe_GET_xref('pid'));

		$html ='<form name="autosearch" action="module.php" target="_blank" method="post">'; 
		$html.='<input type="hidden" name="mod"        value="research_assistant" />';
		$html.='<input type="hidden" name="action"     value="auto_search" />';
		$html.='<input type="hidden" name="searchtype" value="'.$this->title.'" />';
		$html.='<table width="50%">';
		foreach ($this->fields as $field=>$settings) {
			if (!array_key_exists('extra', $settings)) {
				$settings['extra']='';
			}
			$html.='<tr><td class="optionbox">'.$pgv_lang['autosearch_'.$settings['function']].'</td>';
			$html.='<td class="optionbox"><input type="checkbox" name="enable_'.$settings['function'].'" value="Y" ';
			$edit=$this->$settings['function']($person);
			if (strip_tags($edit)) {
				if ($settings['extra']) {
					$html.=$settings['extra'];
				}
			} else {
				$html.='disabled="true"';
			}
			$html.='/> '.$edit.'</td></tr>';
		}
		$html.='<tr><td class="optionbox" colspan="2" align="center">';
		$html.=$pgv_lang['autosearch_plugin_name_'.$this->title].'</td></tr>';
		$html.='<tr><td class="optionbox" colspan="2" align="center">';
		$html.='<input type="submit" value="'.$pgv_lang['autosearch_search'].'" /></td></tr></table></form>';
		return $html;
	}

	// Execute the search
	function process() {
		$params=array();
		foreach ($this->fields as $field=>$settings) {
			if (safe_POST_bool('enable_'.$settings['function'])) {
				$params[]=$field.'='.urlencode(safe_POST($settings['function']));
			}
		}
		// TODO Handle forms that use POST as well as GET
		header('Location: '.$this->url.join('&', $params));
		exit;
	}

	// Functions to generate each search parameter
	function gender($person) {
		return '<input type="hidden" name="gender" value="'.$person->getSex().'">'.$person->getSex().$person->getSexImage();
	}

	function givenname($person, $inputname='givenname') {
		$all_givn=array();
		foreach ($person->getAllNames() as $name) {
			list($surn, $givn)=explode(',', $name['list']);
			$all_givn[]=htmlspecialchars(strip_tags($givn));
		}
		$all_givn=array_unique($all_givn);
		if (count($all_givn)==1) {
			return '<input type="hidden" name="'.$inputname.'" value="'.$all_givn[0].'">'.$all_givn[0];
		} else {
			$html='<select name="'.$inputname.'">';
			foreach ($all_givn as $givn) {
				$html.='<option value="'.$givn.'">'.$givn.'</option>';
			}
			$html.='</select>';
			return $html;
		}
	}

	function surname($person, $inputname='surname') {
		$all_surn=array();
		foreach ($person->getAllNames() as $name) {
			list($surn, $givn)=explode(',', $name['list']);
			$all_surn[]=htmlspecialchars(strip_tags($surn));
		}
		$all_surn=array_unique($all_surn);
		if (count($all_surn)==1) {
			return '<input type="hidden" name="'.$inputname.'" value="'.$all_surn[0].'">'.$all_surn[0];
		} else {
			$html='<select name="'.$inputname.'">';
			foreach ($all_surn as $surn) {
				$html.='<option value="'.$surn.'">'.$surn.'</option>';
			}
			$html.='</select>';
			return $html;
		}
	}

	function fgivennames($person) {
		$parents=$person->getPrimaryChildFamily();
		if ($parents && $parents->husb) {
			return $this->givenname($parents->husb, 'fgivennames');
		} else {
			return '<input type="hidden" name="fgivennames" value="">';
		}
	}

	function fsurname($person) {
		$parents=$person->getPrimaryChildFamily();
		if ($parents && $parents->husb) {
			return $this->surname($parents->husb, 'fsurname');
		} else {
			return '<input type="hidden" name="fsurname" value="">';
		}
	}

	function mgivennames($person) {
		$parents=$person->getPrimaryChildFamily();
		if ($parents && $parents->wife) {
			return $this->givenname($parents->wife, 'mgivennames');
		} else {
			return '<input type="hidden" name="mgivennames" value="">';
		}
	}

	function msurname($person) {
		$parents=$person->getPrimaryChildFamily();
		if ($parents && $parents->wife) {
			return $this->surname($parents->wife, 'msurname');
		} else {
			return '<input type="hidden" name="msurname" value="">';
		}
	}
	function sgivennames($person) {
		$spouse=$person->getCurrentSpouse();
		if ($spouse) {
			return $this->givenname($spouse, 'sgivennames');
		} else {
			return '<input type="hidden" name="sgivennames" value="">';
		}
	}

	function ssurname($person) {
		$spouse=$person->getCurrentSpouse();
		if ($spouse) {
			return $this->surname($spouse, 'ssurname');
		} else {
			return '<input type="hidden" name="ssurname" value="">';
		}
	}

	function byear($person) {
		$birth=$person->getEstimatedBirthDate();
		$byear=$birth->gregorianYear();
		if ($byear==0) {
			$byear='';
		}
		return '<input type="hidden" name="byear" value="'.$byear.'">'.$byear;
	}

	function dyear($person) {
		$death=$person->getEstimatedDeathDate();
		$dyear=$death->gregorianYear();
		if ($dyear==0) {
			$dyear='';
		}
		return '<input type="hidden" name="dyear" value="'.$dyear.'">'.$dyear;
	}

	function myear($person) {
		$myears=array();
		foreach ($person->getSpouseFamilies() as $family) {
			$mdate=new GedcomDate($family->getMarriageDate());
			if ($mdate->isOK()) {
				$myears[]=$mdate->gregorianYear();
			} else {
				$myears[]='';
			}
		}
		$myears=array_unique($myears);
		if (count($myears)==1) {
			return '<input type="hidden" name="myear" value="'.$myears[0].'">'.$myears[0];
		} else {
			$html='<select name="myear">';
			foreach ($myears as $myear) {
				$html.='<option value="'.$myear.'">'.$myear.'</option>';
			}
			$html.='</select>';
			return $html;
		}
	}

	function bloc($person) {
		$place=htmlspecialchars($person->getBirthPlace());
		return '<input type="hidden" name="bloc" value="'.$place.'">'.$place;
	}

	function dloc($person) {
		$place=htmlspecialchars($person->getDeathPlace());
		return '<input type="hidden" name="dloc" value="'.$place.'">'.$place;
	}

	function mloc($person) {
		$mplaces=array();
		foreach ($person->getSpouseFamilies() as $family) {
			$mplaces[]=htmlspecialchars($family->getMarriagePlace());
		}
		$mplaces=array_unique($mplaces);
		if (count($mplaces)==1) {
			return '<input type="hidden" name="mloc" value="'.$mplaces[0].'">'.$mplaces[0];
		} else {
			$html='<select name="mloc">';
			foreach ($mplaces as $mplace) {
				$html.='<option value="'.$mplace.'">'.$mplace.'</option>';
			}
			$html.='</select>';
			return $html;
		}
	}

	function keywords($person) {
		return '<input name="keywords">&zwnj;'; // zwnj prevents empty value being disabled
	}

	function country($person) {
		global $lang_short_cut, $LANGUAGE;
	
		// Fetch a list of countries
		require 'languages/countries.'.$lang_short_cut[$LANGUAGE].'.php';
		asort($countries);

		// Move birth/death countries to top of list
		$bcountry=array_pop(preg_split('/ *, */', $person->getBirthPlace()));
		$dcountry=array_pop(preg_split('/ *, */', $person->getDeathPlace()));

		$html='<select name="country_list">';
		if ($bcountry && array_search($bcountry, $countries)) {
			$html.='<option value="'.htmlspecialchars(array_search($bcountry, $countries)).'">'.htmlspecialchars($bcountry).'</option>';
		}
		if ($dcountry && array_search($dcountry, $countries) && $bcountry!=$dcountryh) {
			$html.='<option value="'.htmlspecialchars(array_search($dcountry, $countries)).'">'.htmlspecialchars($dcountry).'</option>';
		}
		foreach ($countries as $code=>$country) {
			if ($country!=$bcountry && $country!=$dcountry) {
				$html.='<option value="'.htmlspecialchars($code).'">'.htmlspecialchars($country).'</option>';
			}
		}
		$html.='</select>';
		return $html;
	}

}

?>
