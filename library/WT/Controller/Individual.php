<?php
// Controller for the Individual Page
//
// webtrees: Web based Family History software
// Copyright (C) 2011 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2002 to 2010 PGV Development Team. All rights reserved.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
//
// @version $Id$

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once WT_ROOT.'includes/functions/functions_print_facts.php';
require_once WT_ROOT.'includes/functions/functions_import.php';

class WT_Controller_Individual extends WT_Controller_Base {
	var $pid = '';
	var $indi = null;
	var $diffindi = null;
	var $accept_success = false;
	var $default_tab = '';

	var $name_count = 0;
	var $total_names = 0;
	var $SEX_COUNT = 0;
	var $tabs;
	var $Fam_Navigator = 'YES';
	var $NAME_LINENUM = null;
	var $SEX_LINENUM = null;
	var $globalfacts = null;

	function init() {
		global $USE_RIN, $MAX_ALIVE_AGE;
		global $DEFAULT_PIN_STATE, $DEFAULT_SB_CLOSED_STATE;
		global $Fam_Navigator;

		$this->pid = safe_GET_xref('pid');

		$gedrec = find_person_record($this->pid, WT_GED_ID);

		if ($USE_RIN && $gedrec==false) {
			$this->pid = find_rin_id($this->pid);
			$gedrec = find_person_record($this->pid, WT_GED_ID);
		}
		if (empty($gedrec)) {
			$gedrec = "0 @".$this->pid."@ INDI\n";
		}

		if (WT_USER_ID) {
			// Start with the user's default tab
			$this->default_tab=get_user_setting(WT_USER_ID, 'defaulttab');
		} else {
			// Start with the gedcom's default tab
			$this->default_tab=get_gedcom_setting(WT_GED_ID, 'GEDCOM_DEFAULT_TAB');
		}

		if (find_person_record($this->pid, WT_GED_ID) || find_updated_record($this->pid, WT_GED_ID)!==null) {
				$this->indi = new WT_Person($gedrec);
				$this->indi->ged_id=WT_GED_ID; // This record is from a file
		} else if (!$this->indi) {
			return false;
		}

		$this->pid=$this->indi->getXref(); // Correct upper/lower case mismatch

		//-- perform the desired action
		switch($this->action) {
		case 'addfav':
			if (WT_USER_ID && !empty($_REQUEST['gid']) && array_key_exists('user_favorites', WT_Module::getActiveModules())) {
				$favorite = array(
					'username' => WT_USER_NAME,
					'gid'      => $_REQUEST['gid'],
					'type'     => 'INDI',
					'file'     => WT_GEDCOM,
					'url'      => '',
					'note'     => '',
					'title'    => ''
				);
				user_favorites_WT_Module::addFavorite($favorite);
			}
			unset($_GET['action']);
			break;
		case 'accept':
			if (WT_USER_CAN_ACCEPT) {
				accept_all_changes($this->pid, WT_GED_ID);
				$this->show_changes=false;
				$this->accept_success=true;
				//-- check if we just deleted the record and redirect to index
				$gedrec = find_person_record($this->pid, WT_GED_ID);
				if (empty($gedrec)) {
					header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH);
					exit;
				}
				$this->indi = new WT_Person($gedrec);
			}
			unset($_GET['action']);
			break;
		case 'undo':
			if (WT_USER_CAN_ACCEPT) {
				reject_all_changes($this->pid, WT_GED_ID);
				$this->show_changes=false;
				$this->accept_success=true;
				$gedrec = find_person_record($this->pid, WT_GED_ID);
				//-- check if we just deleted the record and redirect to index
				if (empty($gedrec)) {
					header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH);
					exit;
				}
				$this->indi = new WT_Person($gedrec);
			}
			unset($_GET['action']);
			break;
		}

		//-- if the user can edit and there are changes then get the new changes
		if ($this->show_changes && WT_USER_CAN_EDIT) {
			$newrec = find_updated_record($this->pid, WT_GED_ID);
			if (!empty($newrec)) {
				$this->diffindi = new WT_Person($newrec);
				$this->diffindi->setChanged(true);
			}
		}

		if ($this->show_changes) {
			$this->indi->diffMerge($this->diffindi);
		}

		// Initialise tabs
		$this->tabs = WT_Module::getActiveTabs();
		foreach ($this->tabs as $mod) {
			$mod->setController($this);
			if ($mod->hasTabContent()) {
				if (empty($this->default_tab)) {
					$this->default_tab=$mod->getName();
				}
			}
		}

		if (!isset($_SESSION['WT_pin']) && $DEFAULT_PIN_STATE)
			$_SESSION['WT_pin'] = true;

		if (!isset($_SESSION['WT_sb_closed']) && $DEFAULT_SB_CLOSED_STATE)
			$_SESSION['WT_sb_closed'] = true;

		//-- handle ajax calls
		if ($this->action=="ajax") {
			$tab = 0;
			if (isset($_REQUEST['module'])) {
				$tabname = $_REQUEST['module'];
				if (!array_key_exists($tabname, $this->tabs)) {
					// An AJAX request for a non-existant tab?
					header('HTTP/1.0 404 Not Found');
					exit;
				}
				header("Content-Type: text/html; charset=UTF-8"); //AJAX calls do not have the meta tag headers and need this set
				header("X-Robots-Tag: noindex,follow"); //AJAX pages should not show up in search results, any links can be followed though
				$mod = $this->tabs[$tabname];
				if ($mod) {
					echo $mod->getTabContent();
					// Allow the other tabs to modify this one - e.g. lightbox does this.
					echo WT_JS_START;
					foreach (WT_Module::getActiveTabs() as $module) {
						echo $module->getJSCallback();
					}
					echo WT_JS_END;
				}
			}

			if (isset($_REQUEST['pin'])) {
				if ($_REQUEST['pin']=='true') $_SESSION['WT_pin'] = true;
				else $_SESSION['WT_pin'] = false;
			}

			if (isset($_REQUEST['sb_closed'])) {
				if ($_REQUEST['sb_closed']=='true') $_SESSION['WT_sb_closed'] = true;
				else $_SESSION['WT_sb_closed'] = false;
			}

			//-- only get the requested tab and then exit
			if (WT_DEBUG_SQL) {
				echo WT_DB::getQueryLog();
			}
			exit;
		}
	}

	/**
	* return the title of this page
	* @return string the title of the page to go in the <title> tags
	*/
	function getPageTitle() {
		if ($this->indi) {
			return $this->indi->getFullName();
		} else {
			return WT_I18N::translate('Unable to find record with ID');
		}
	}

	/**
	* check if we can show the highlighted media object
	* @return boolean
	*/
	function canShowHighlightedObject() {
		global $MULTI_MEDIA, $SHOW_HIGHLIGHT_IMAGES, $USE_SILHOUETTE, $WT_IMAGES;

		if (($this->indi->canDisplayDetails()) && ($MULTI_MEDIA && $SHOW_HIGHLIGHT_IMAGES)) {
			$firstmediarec = $this->indi->findHighlightedMedia();
			if ($firstmediarec) return true;
		}
		if ($USE_SILHOUETTE && isset($WT_IMAGES["default_image_U"])) { return true; }
		return false;
	}
	/**
	* check if we can show the gedcom record
	* @return boolean
	*/
	function canShowGedcomRecord() {
		global $SHOW_GEDCOM_RECORD;
		if (WT_USER_CAN_EDIT && $SHOW_GEDCOM_RECORD && $this->indi->canDisplayDetails())
			return true;
	}

	/**
	* get the highlighted object HTML
	* @return string HTML string for the <img> tag
	*/
	function getHighlightedObject() {
		global $USE_THUMBS_MAIN, $THUMBNAIL_WIDTH, $USE_MEDIA_VIEWER, $GEDCOM, $WT_IMAGES, $USE_SILHOUETTE, $sex;

		if ($this->canShowHighlightedObject()) {
			$firstmediarec = $this->indi->findHighlightedMedia();
			if (!empty($firstmediarec)) {
				$filename = thumb_or_main($firstmediarec); // Do we send the main image or a thumbnail?
				if (!$USE_THUMBS_MAIN || $firstmediarec["_THUM"]=='Y') {
					$class = "image";
				} else {
					$class = "thumbnail";
				}
				$isExternal = isFileExternal($filename);
				if ($isExternal && $class=="thumbnail") $class .= "\" width=\"".$THUMBNAIL_WIDTH;
				if (!empty($filename)) {
					$result = "";
					$imgsize = findImageSize($firstmediarec["file"]);
					$imgwidth = $imgsize[0]+40;
					$imgheight = $imgsize[1]+150;
					//Gets the Media View Link Information and Concatenate
					$mid = $firstmediarec['mid'];

					$name = $this->indi->getFullName();
					if (WT_USE_LIGHTBOX) {
						echo "<a href=\"" . $firstmediarec["file"] . "\" rel=\"clearbox[general_1]\" rev=\"" . $mid . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name)) . "\">";
					} else if (!$USE_MEDIA_VIEWER && $imgsize) {
						$result .= "<a href=\"javascript:;\" onclick=\"return openImage('".urlencode($firstmediarec["file"])."', $imgwidth, $imgheight);\">";
					} else {
						$result .= "<a href=\"mediaviewer.php?mid={$mid}\">";
					}
					$result .= "<img src=\"$filename\" align=\"left\" class=\"".$class."\" border=\"none\" title=\"".PrintReady(htmlspecialchars(strip_tags($name)))."\" alt=\"".PrintReady(htmlspecialchars(strip_tags($name)))."\" />";
					$result .= "</a>";
					return $result;
				}
			}
		}
		if ($USE_SILHOUETTE && isset($WT_IMAGES["default_image_U"])) {
			$class = "\" width=\"".$THUMBNAIL_WIDTH;
			$sex = $this->indi->getSex();
			$result = "<img src=\"";
			if ($sex == 'F') {
				$result .= $WT_IMAGES["default_image_F"];
			} elseif ($sex == 'M') {
				$result .= $WT_IMAGES["default_image_M"];
			} else {
				$result .= $WT_IMAGES["default_image_U"];
			}
			$result .="\" class=\"".$class."\" border=\"none\" alt=\"\" />";
			return $result;
		}
	}

	/**
	* print information for a name record
	*
	* Called from the individual information page
	* @see individual.php
	* @param Event $event the event object
	*/
	function print_name_record(&$event) {
		global $UNDERLINE_NAME_QUOTES;

		if (!$event->canShow()) {
			return false;
		}
		$factrec = $event->getGedComRecord();
		$linenum = $event->getLineNumber();

		$this->name_count++;
		echo '<div id="nameparts', $this->name_count, '"';
		if (strpos($factrec, "\nWT_OLD")!==false) {
			echo " class=\"namered\"";
		}
		if (strpos($factrec, "\nWT_NEW")!==false) {
			echo " class=\"nameblue\"";
		}
		echo ">";
		$dummy=new WT_Person($factrec);
		$dummy->setPrimaryName(0);
		echo '<div id="name1">';
			echo '<dl><dt class="label">', WT_I18N::translate('Name'), '</dt>';
			echo '<dd class="field">', PrintReady($dummy->getFullName());
				if ($this->indi->canEdit() && !strpos($factrec, "\nWT_OLD") && $this->name_count > 1) {
					echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:;\" class=\"font9\" onclick=\"edit_name('".$this->pid."', ".$linenum."); return false;\">", WT_I18N::translate('Edit'), "</a> | ";
					echo "<a class=\"font9\" href=\"javascript:;\" onclick=\"delete_record('".$this->pid."', ".$linenum."); return false;\">", WT_I18N::translate('Delete'), "</a>";
				}
			echo '</dd>';
			echo '</dl>';
		echo '</div>';
		$ct = preg_match_all('/\n2 (\w+) (.*)/', $factrec, $nmatch, PREG_SET_ORDER);
		for ($i=0; $i<$ct; $i++) {
			echo '<div>';
				$fact = trim($nmatch[$i][1]);
				if (($fact!="SOUR")&&($fact!="NOTE")) {
					echo '<dl><dt class="label">', WT_Gedcom_Tag::getLabel($fact, $this->indi), '</dt>';
					echo '<dd class="field">';
						if (isset($nmatch[$i][2])) {
							$name = trim($nmatch[$i][2]);
							$name = preg_replace("'/,'", ",", $name);
							$name = preg_replace("'/'", " ", $name);
							if ($UNDERLINE_NAME_QUOTES) {
								$name=preg_replace('/"([^"]*)"/', '<span class="starredname">\\1</span>', $name);
							}
							$name=preg_replace('/(\S*)\*/', '<span class="starredname">\\1</span>', $name);
							echo PrintReady($name);
						}
					echo '</dd>';
					echo '</dl>';
				}
			echo '</div>';
		}
		if (preg_match("/\d (NOTE)|(SOUR)/", $factrec)>0) {
			// -- find sources for this name
			echo '<div id="indi_note" class="clearfloat">';
				print_fact_sources($factrec, 2);
				//-- find the notes for this name
				print_fact_notes($factrec, 2);
			echo '</div>';
		}
		echo '</div>';
	}

	/**
	* print information for a sex record
	*
	* Called from the individual information page
	* @see individual.php
	* @param Event $event the Event object
	*/
	function print_sex_record(&$event) {
		global $sex;

		if (!$event->canShow()) return false;
		$factrec = $event->getGedComRecord();
		$sex = $event->getDetail();
		if (empty($sex)) $sex = 'U';
		echo '<div id="sex"';
			if (strpos($factrec, "\nWT_OLD")!==false) {
				echo ' class="namered"';
			}
			if (strpos($factrec, "\nWT_NEW")!==false) {
				echo ' class="nameblue"';
			}
			echo '>';
			echo '<dl><dt class="label">', WT_I18N::translate('Gender'), '</dt>';
			echo '<dd class="field">';
			switch ($sex) {
			case 'M':
				echo WT_I18N::translate('Male'), WT_Person::sexImage('M', 'small', '', WT_I18N::translate('Male'));
				break;
			case 'F':
				echo WT_I18N::translate('Female'), WT_Person::sexImage('F', 'small', '', WT_I18N::translate('Female'));
				break;
			case 'U':
				echo WT_I18N::translate_c('unknown gender', 'Unknown'), WT_Person::sexImage('U', 'small', '', WT_I18N::translate_c('unknown gender', 'Unknown'));
				break;
			}
			if ($this->SEX_COUNT>1) {
				if ($this->indi->canEdit() && strpos($factrec, "\nWT_OLD")===false) {
					if ($event->getLineNumber()=="new") {
						echo "<a class=\"font9\" href=\"javascript:;\" onclick=\"add_new_record('".$this->pid."', 'SEX'); return false;\">".WT_I18N::translate('Edit')."</a>";
					} else {
							echo "<a class=\"font9\" href=\"javascript:;\" onclick=\"edit_record('".$this->pid."', ".$event->getLineNumber()."); return false;\">".WT_I18N::translate('Edit')."</a> | ";
							echo "<a class=\"font9\" href=\"javascript:;\" onclick=\"delete_record('".$this->pid."', ".$event->getLineNumber()."); return false;\">".WT_I18N::translate('Delete')."</a>";
					}
				}
			}
			echo '</dd>';
			echo '</dl>';
			// -- find sources
			print_fact_sources($event->getGedComRecord(), 2);
			//-- find the notes
			print_fact_notes($event->getGedComRecord(), 2);
		echo '</div>';
	}
	/**
	* get edit menu
	*/
	function getEditMenu() {
		global $SHOW_GEDCOM_RECORD;

		if (!$this->indi) return null;
		// edit menu
		$menu = new WT_Menu(WT_I18N::translate('Edit'));
		$menu->addIcon('edit_indi');
		$menu->addClass('submenuitem', 'submenuitem_hover', 'submenu', 'icon_large_gedcom');

		if (WT_USER_CAN_EDIT) {
			//--make sure the totals are correct
			$this->getGlobalFacts();
			if ($this->NAME_LINENUM) {
				$submenu = new WT_Menu(WT_I18N::translate('Edit name'));
				$submenu->addOnclick("return edit_name('".$this->pid."', ".$this->NAME_LINENUM.");");
				$submenu->addIcon('edit_indi');
				$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
				$menu->addSubmenu($submenu);
			}

			$submenu = new WT_Menu(WT_I18N::translate('Add new Name'));
			$submenu->addOnclick("return add_name('".$this->pid."');");
			$submenu->addIcon('edit_indi');
			$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
			$menu->addSubmenu($submenu);

			if ($this->SEX_COUNT<2) {
				$submenu = new WT_Menu(WT_I18N::translate('Edit gender'));
				if ($this->SEX_LINENUM=="new") {
					$submenu->addOnclick("return add_new_record('".$this->pid."', 'SEX');");
				} else {
					$submenu->addOnclick("return edit_record('".$this->pid."', ".$this->SEX_LINENUM.");");
				}
				$submenu->addIcon('edit_indi');
				$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
				$menu->addSubmenu($submenu);
			}

			if (count($this->indi->getSpouseFamilies())>1) {
				$submenu = new WT_Menu(WT_I18N::translate('Reorder families'));
				$submenu->addOnclick("return reorder_families('".$this->pid."');");
				$submenu->addIcon('edit_fam');
				$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
				$menu->addSubmenu($submenu);
			}

			$menu->addSeparator();
		}

		// show/hide changes
		if (find_updated_record($this->pid, WT_GED_ID)!==null) {
			if (!$this->show_changes) {
				$label = WT_I18N::translate('This record has been updated.  Click here to show changes.');
				$link = $this->indi->getHtmlUrl().'&amp;show_changes=yes';
			} else {
				$label = WT_I18N::translate('Click here to hide changes.');
				$link = $this->indi->getHtmlUrl().'&amp;show_changes=no';
			}
			$submenu = new WT_Menu($label, $link);
			$submenu->addIcon('edit_indi');
			$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
			$menu->addSubmenu($submenu);

			if (WT_USER_CAN_ACCEPT) {
				$submenu = new WT_Menu(WT_I18N::translate('Undo all changes'), $this->indi->getHtmlUrl()."&amp;action=undo");
				$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
				$submenu->addIcon('edit_indi');
				$menu->addSubmenu($submenu);
				$submenu = new WT_Menu(WT_I18N::translate('Approve all changes'), $this->indi->getHtmlUrl()."&amp;action=accept");
				$submenu->addIcon('edit_indi');
				$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
				$menu->addSubmenu($submenu);
			}

			$menu->addSeparator();
		}

		// edit/view raw gedcom
		if (WT_USER_IS_ADMIN || $this->canShowGedcomRecord()) {
			$submenu = new WT_Menu(WT_I18N::translate('Edit raw GEDCOM record'));
			$submenu->addOnclick("return edit_raw('".$this->pid."');");
			$submenu->addIcon('gedcom');
			$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
			$menu->addSubmenu($submenu);
		} elseif ($SHOW_GEDCOM_RECORD) {
			$submenu = new WT_Menu(WT_I18N::translate('View GEDCOM Record'));
			$submenu->addIcon('gedcom');
			if ($this->show_changes && WT_USER_CAN_EDIT) {
				$submenu->addOnclick("return show_gedcom_record('new');");
			} else {
				$submenu->addOnclick("return show_gedcom_record();");
			}
			$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
			$menu->addSubmenu($submenu);
		}

		// delete
		if (WT_USER_CAN_EDIT) {
			$submenu = new WT_Menu(WT_I18N::translate('Delete this individual'));
			$submenu->addOnclick("return deleteperson('".$this->pid."');");
			$submenu->addIcon('remove');
			$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
			$menu->addSubmenu($submenu);
		}

		// add to favorites
		$submenu = new WT_Menu(WT_I18N::translate('Add to My Favorites'), $this->indi->getHtmlUrl()."&amp;action=addfav&amp;gid=".$this->pid);
		$submenu->addIcon('favorites');
		$submenu->addClass('submenuitem', 'submenuitem_hover', 'submenu');
		$menu->addSubmenu($submenu);

		//-- get the link for the first submenu and set it as the link for the main menu
		if (isset($menu->submenus[0])) {
			$link = $menu->submenus[0]->onclick;
			$menu->addOnclick($link);
		}
		return $menu;
	}

	/**
	* get global facts
	* global facts are NAME and SEX
	* @return array return the array of global facts
	*/
	function getGlobalFacts() {
		if ($this->globalfacts==null) {
			$this->globalfacts = $this->indi->getGlobalFacts();
			foreach ($this->globalfacts as $key => $value) {
				$fact = $value->getTag();
				if ($fact=="SEX") {
					$this->SEX_COUNT++;
					$this->SEX_LINENUM = $value->getLineNumber();
				}
				if ($fact=="NAME") {
					$this->total_names++;
					if ($this->NAME_LINENUM==null && strpos($value->getGedcomRecord(), "\nWT_OLD")===false) {
						// This is the "primary" name and is edited from the menu
						// Subsequent names get their own edit links
						$this->NAME_LINENUM = $value->getLineNumber();
					}
				}
			}
		}
		return $this->globalfacts;
	}
	/**
	* get the individual facts shown on tab 1
	* @return array
	*/
	function getIndiFacts() {
		$indifacts = $this->indi->getIndiFacts();
		sort_facts($indifacts);
		return $indifacts;
	}
	/**
	* get the other facts shown on tab 2
	* @return array
	*/
	function getOtherFacts() {
		$otherfacts = $this->indi->getOtherFacts();
		return $otherfacts;
	}

	/**
	* get the person box stylesheet class
	* for the given person
	* @param Person $person
	* @return string returns 'person_box', 'person_boxF', or 'person_boxNN'
	*/
	function getPersonStyle(&$person) {
		$sex = $person->getSex();
		switch($sex) {
			case "M":
				$isf = "";
				break;
			case "F":
				$isf = "F";
				break;
			default:
				$isf = "NN";
				break;
		}
		return "person_box".$isf;
	}

	/**
	* build an array of Person that will be used to build a list
	* of family members on the close relatives tab
	* @param Family $family the family we are building for
	* @return array an array of Person that will be used to iterate through on the indivudal.php page
	*/
	function buildFamilyList(&$family, $type) {
		global $WT_IMAGES;

		$labels = array();
		switch ($type) {
		case 'parents':
			$labels["parent"] = WT_I18N::translate('Parent');
			$labels["mother"] = WT_I18N::translate('Mother');
			$labels["father"] = WT_I18N::translate('Father');
			$labels["sibling"] = WT_I18N::translate('Sibling');
			$labels["sister"] = WT_I18N::translate('Sister');
			$labels["brother"] = WT_I18N::translate('Brother');
			break;
		case 'step-parents':
			$labels["parent"] = WT_I18N::translate('Step-Parent');
			$labels["mother"] = WT_I18N::translate('Step-Mother');
			$labels["father"] = WT_I18N::translate('Step-Father');
			$labels["sibling"] = WT_I18N::translate('Half-Sibling');
			$labels["sister"] = WT_I18N::translate('Half-Sister');
			$labels["brother"] = WT_I18N::translate('Half-Brother');
			break;
		case 'spouse':
			if ($family->isNotMarried()) {
				$labels["parent"] = WT_I18N::translate('Partner');
				$labels["mother"] = WT_I18N::translate('Partner');
				$labels["father"] = WT_I18N::translate('Partner');
			} elseif ($family->isDivorced()) {
				$labels["parent"] = WT_I18N::translate('Ex-Spouse');
				$labels["mother"] = WT_I18N::translate('Ex-Wife');
				$labels["father"] = WT_I18N::translate('Ex-Husband');
			} else {
				$marr_rec = $family->getMarriageRecord();
				if (!empty($marr_rec)) {
					$type = $family->getMarriageType();
					if (empty($type) || stristr($type, "partner")===false) {
						$labels["parent"] = WT_I18N::translate('Spouse');
						$labels["mother"] = WT_I18N::translate('Wife');
						$labels["father"] = WT_I18N::translate('Husband');
					} else {
						$labels["parent"] = WT_I18N::translate('Partner');
						$labels["mother"] = WT_I18N::translate('Partner');
						$labels["father"] = WT_I18N::translate('Partner');
					}
				} else {
					$labels["parent"] = WT_I18N::translate('Spouse');
					$labels["mother"] = WT_I18N::translate('Wife');
					$labels["father"] = WT_I18N::translate('Husband');
				}
			}
			$labels["sibling"] = WT_I18N::translate('Child');
			$labels["sister"] = WT_I18N::translate('Daughter');
			$labels["brother"] = WT_I18N::translate('Son');
			break;
		case 'step-children':
			if ($this->indi->equals($family->getHusband())) {
				$labels["parent"] = '';
				$labels["mother"] = '';
				$labels["father"] = WT_I18N::translate('husband');
			} else {
				$labels["parent"] = '';
				$labels["mother"] = WT_I18N::translate('wife');
				$labels["father"] = '';
			}
			$labels["sibling"] = WT_I18N::translate_c('spouses\'s child', 'step-child');
			$labels["sister"] = WT_I18N::translate_c('spouses\'s daughter', 'step-daughter');
			$labels["brother"] = WT_I18N::translate_c('spouses\'s son', 'step-son');
			break;
		}
		$newhusb = null;
		$newwife = null;
		$newchildren = array();
		$delchildren = array();
		$children = array();
		$husb = null;
		$wife = null;
		if (!$family->getChanged()) {
			$husb = $family->getHusband();
			$wife = $family->getWife();
			$children = $family->getChildren();
		}
		//-- step families : set the label for the common parent
		if ($type=="step-parents") {
			$fams = $this->indi->getChildFamilies();
			foreach ($fams as $key=>$fam) {
				if ($fam->hasParent($husb)) $labels["father"] = WT_I18N::translate('Father');
				if ($fam->hasParent($wife)) $labels["mother"] = WT_I18N::translate('Mother');
			}
		}
		//-- set the label for the husband
		if (!is_null($husb)) {
			$label = $labels["parent"];
			$sex = $husb->getSex();
			if ($sex=="F") {
				$label = $labels["mother"];
			}
			if ($sex=="M") {
				$label = $labels["father"];
			}
			if ($husb->getXref()==$this->pid) $label = "<img src=\"". $WT_IMAGES["selected"]. "\" alt=\"\" />";
			$husb->setLabel($label);
		}
		//-- set the label for the wife
		if (!is_null($wife)) {
			$label = $labels["parent"];
			$sex = $wife->getSex();
			if ($sex=="F") {
				$label = $labels["mother"];
			}
			if ($sex=="M") {
				$label = $labels["father"];
			}
			if ($wife->getXref()==$this->pid) $label = "<img src=\"". $WT_IMAGES["selected"]. "\" alt=\"\" />";
			$wife->setLabel($label);
		}
		if ($this->show_changes) {
			$newfamily = $family->getUpdatedFamily();
			if (!is_null($newfamily)) {
				$newhusb = $newfamily->getHusband();
				//-- check if the husband in the family has changed
				if (!is_null($newhusb) && !$newhusb->equals($husb)) {
					$label = $labels["parent"];
					$sex = $newhusb->getSex();
					if ($sex=="F") {
						$label = $labels["mother"];
					}
					if ($sex=="M") {
						$label = $labels["father"];
					}
					if ($newhusb->getXref()==$this->pid) $label = "<img src=\"". $WT_IMAGES["selected"]. "\" alt=\"\" />";
					$newhusb->setLabel($label);
				}
				else $newhusb = null;
				$newwife = $newfamily->getWife();
				//-- check if the wife in the family has changed
				if (!is_null($newwife) && !$newwife->equals($wife)) {
					$label = $labels["parent"];
					$sex = $newwife->getSex();
					if ($sex=="F") {
						$label = $labels["mother"];
					}
					if ($sex=="M") {
						$label = $labels["father"];
					}
					if ($newwife->getXref()==$this->pid) $label = "<img src=\"". $WT_IMAGES["selected"]. "\" alt=\"\" />";
					$newwife->setLabel($label);
				}
				else $newwife = null;
				//-- check for any new children
				$merged_children = array();
				$new_children = $newfamily->getChildren();
				$num = count($children);
				for ($i=0; $i<$num; $i++) {
					$child = $children[$i];
					if (!is_null($child)) {
						$found = false;
						foreach ($new_children as $key=>$newchild) {
							if (!is_null($newchild)) {
								if ($child->equals($newchild)) {
									$found = true;
									break;
								}
							}
						}
						if (!$found) $delchildren[] = $child;
						else $merged_children[] = $child;
					}
				}
				foreach ($new_children as $key=>$newchild) {
					if (!is_null($newchild)) {
						$found = false;
						foreach ($children as $key1=>$child) {
							if (!is_null($child)) {
								if ($child->equals($newchild)) {
									$found = true;
									break;
								}
							}
						}
						if (!$found) $newchildren[] = $newchild;
					}
				}
				$children = $merged_children;
			}
		}
		//-- set the labels for the children
		$num = count($children);
		for ($i=0; $i<$num; $i++) {
			if (!is_null($children[$i])) {
				$label = $labels["sibling"];
				$sex = $children[$i]->getSex();
				if ($sex=="F") {
					$label = $labels["sister"];
				}
				if ($sex=="M") {
					$label = $labels["brother"];
				}
				if ($children[$i]->getXref()==$this->pid) {
					$label = "<img src=\"". $WT_IMAGES["selected"]. "\" alt=\"\" />";
				}
				$famcrec = get_sub_record(1, "1 FAMC @".$family->getXref()."@", $children[$i]->getGedcomRecord());
				$pedi = get_gedcom_value("PEDI", 2, $famcrec, '', false);
				if ($pedi) {
					$label.='<br />('.WT_Gedcom_Code_Pedi::getValue($pedi, $children[$i]).')';
				}
				$children[$i]->setLabel($label);
			}
		}
		$num = count($newchildren);
		for ($i=0; $i<$num; $i++) {
			$label = $labels["sibling"];
			$sex = $newchildren[$i]->getSex();
			if ($sex=="F") {
				$label = $labels["sister"];
			}
			if ($sex=="M") {
				$label = $labels["brother"];
		}
			if ($newchildren[$i]->getXref()==$this->pid) $label = "<img src=\"". $WT_IMAGES["selected"]. "\" alt=\"\" />";
			$pedi = $newchildren[$i]->getChildFamilyPedigree($family->getXref());
			if ($pedi) {
				$label.='<br />('.WT_Gedcom_Code_Pedi::getValue($pedi, $newchildren[$i]).')';
			}
			$newchildren[$i]->setLabel($label);
		}
		$num = count($delchildren);
		for ($i=0; $i<$num; $i++) {
				$label = $labels["sibling"];
			$sex = $delchildren[$i]->getSex();
			if ($sex=="F") {
				$label = $labels["sister"];
			}
			if ($sex=="M") {
				$label = $labels["brother"];
			}
			if ($delchildren[$i]->getXref()==$this->pid) $label = "<img src=\"". $WT_IMAGES["selected"]. "\" alt=\"\" />";
			$pedi = $delchildren[$i]->getChildFamilyPedigree($family->getXref());
			if ($pedi) {
				$label.='<br />('.WT_Gedcom_Code_Pedi::getValue($pedi, $delchildren[$i]).')';
			}
			$delchildren[$i]->setLabel($label);
		}

		$people = array();
		if (!is_null($newhusb)) $people['newhusb'] = $newhusb;
		if (!is_null($husb)) $people['husb'] = $husb;
		if (!is_null($newwife)) $people['newwife'] = $newwife;
		if (!is_null($wife)) $people['wife'] = $wife;
		$people['children'] = $children;
		$people['newchildren'] = $newchildren;
		$people['delchildren'] = $delchildren;
		return $people;
	}

// -----------------------------------------------------------------------------
// Functions for GedFact Assistant
// -----------------------------------------------------------------------------
	/**
	* include GedFact controller
	*/
	function census_assistant() {
		require WT_ROOT.WT_MODULES_DIR.'GEDFact_assistant/_CENS/census_1_ctrl.php';
	}
	function medialink_assistant() {
		require WT_ROOT.WT_MODULES_DIR.'GEDFact_assistant/_MEDIA/media_1_ctrl.php';
	}
// -----------------------------------------------------------------------------
// End GedFact Assistant Functions
// -----------------------------------------------------------------------------
}
