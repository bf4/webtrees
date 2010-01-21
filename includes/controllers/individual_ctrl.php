<?php
/**
* Controller for the Individual Page
*
* phpGedView: Genealogy Viewer
* Copyright (C) 2002 to 2010 PGV Development Team. All rights reserved.
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*
* @package PhpGedView
* @subpackage Charts
* @version $Id$
*/

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_INDIVIDUAL_CTRL_PHP', '');

require_once PGV_ROOT.'includes/functions/functions_print_facts.php';
require_once PGV_ROOT.'includes/controllers/basecontrol.php';
require_once PGV_ROOT.'includes/classes/class_menu.php';
require_once PGV_ROOT.'includes/classes/class_person.php';
require_once PGV_ROOT.'includes/classes/class_family.php';
require_once PGV_ROOT.'includes/functions/functions_import.php';
require_once PGV_ROOT.'includes/classes/class_module.php';

// -- array of GEDCOM elements that will be found but should not be displayed
$nonfacts[] = "FAMS";
$nonfacts[] = "FAMC";
$nonfacts[] = "MAY";
$nonfacts[] = "BLOB";
$nonfacts[] = "CHIL";
$nonfacts[] = "HUSB";
$nonfacts[] = "WIFE";
$nonfacts[] = "RFN";
$nonfacts[] = "_PGV_OBJS";
$nonfacts[] = "";

//$nonfamfacts[] = "NCHI"; // Turning back on NCHI display for the indi page.
$nonfamfacts[] = "UID";
$nonfamfacts[] = "";

// SET Family Navigator for each Tab as necessary  - SHOW/HIDE ===============
$NAV_FACTS	 = "SHOW";		// Facts and Details Tab Navigator
$NAV_NOTES	 = "SHOW";		// Notes Tab Navigator
$NAV_SOURCES = "SHOW";		// Sources Tab Navigator
$NAV_MEDIA	 = "SHOW";		// Media Tab Navigator
$NAV_ALBUM	 = "SHOW";		// Album Tab Navigator
// ========================================================================

/**
* Main controller class for the individual page.
*/
class IndividualControllerRoot extends BaseController {
	var $pid = "";
	var $default_tab = 0;
	var $indi = null;
	var $diffindi = null;
	var $NAME_LINENUM = 1;
	var $accept_success = false;
	var $visibility = "visible";
	var $position = "relative";
	var $display = "block";
	var $canedit = false;
	var $name_count = 0;
	var $total_names = 0;
	var $SEX_COUNT = 0;
	var $sexarray = array();
	var $modules = array();
	var $static_tab = null;
	var $Fam_Navigator = 'YES';

	/**
	* constructor
	*/
	function IndividualControllerRoot() {
		parent::BaseController();
	}

	/**
	* Initialization function
	*/
	function init() {
		global $USE_RIN, $MAX_ALIVE_AGE, $GEDCOM, $GEDCOM_DEFAULT_TAB, $pgv_changes, $pgv_lang, $CHARACTER_SET;
		global $USE_QUICK_UPDATE, $DEFAULT_PIN_STATE, $pid;
		global $Fam_Navigator;

		$this->sexarray["M"] = $pgv_lang["male"];
		$this->sexarray["F"] = $pgv_lang["female"];
		$this->sexarray["U"] = $pgv_lang["unknown"];

		$this->pid = safe_GET_xref('pid');

		$show_famlink = $this->view!='preview';

		$pid = $this->pid;

		$this->default_tab = $GEDCOM_DEFAULT_TAB;
		$indirec = find_person_record($this->pid, PGV_GED_ID);

		if ($USE_RIN && $indirec==false) {
			$this->pid = find_rin_id($this->pid);
			$indirec = find_person_record($this->pid, PGV_GED_ID);
		}
		if (empty($indirec)) {
			$ct = preg_match('/(\w+):(.+)/', $this->pid, $match);
			if ($ct>0) {
				$servid = trim($match[1]);
				$remoteid = trim($match[2]);
				require_once PGV_ROOT.'includes/classes/class_serviceclient.php';
				$service = ServiceClient::getInstance($servid);
				if ($service != null) {
					$newrec= $service->mergeGedcomRecord($remoteid, "0 @".$this->pid."@ INDI\n1 RFN ".$this->pid, false);
					$indirec = $newrec;
				}
			} else {
				$indirec = "0 @".$this->pid."@ INDI\n";
			}
		}
		//-- check for the user
		if (PGV_USER_ID) {
			$this->default_tab=get_user_setting(PGV_USER_ID, 'defaulttab');
		}

		//-- check for a cookie telling what the last tab was when they were last
		//-- visiting this individual
		if($this->default_tab == -2)
		{
			if (isset($_COOKIE['lasttabs'])) {
				$ct = preg_match("/".$this->pid."=(\d+)/", $_COOKIE['lasttabs'], $match);
				if ($ct>0) {
					$this->default_tab = $match[1]-1;
				}
			}
		}

		//-- set the default tab from a request parameter
		if (isset($_REQUEST['tab'])) {
			$this->default_tab = $_REQUEST['tab'];
		}

		$this->indi = new Person($indirec, false);
		$this->indi->ged_id=PGV_GED_ID; // This record is from a file

		//-- if the person is from another gedcom then forward to the correct site
		/*
		if ($this->indi->isRemote()) {
			header('Location: '.encode_url(decode_url($this->indi->getLinkUrl()), false));
			exit;
		}
		*/
		if (!$this->isPrintPreview()) {
			$this->visibility = "hidden";
			$this->position = "absolute";
			$this->display = "none";
		}
		//-- perform the desired action
		switch($this->action) {
			case "addfav":
				$this->addFavorite();
				break;
			case "accept":
				$this->acceptChanges();
				break;
			case "undo":
				$this->indi->undoChange();
				break;
		}

		//-- if the user can edit and there are changes then get the new changes
		if ($this->show_changes && PGV_USER_CAN_EDIT) {
			if (isset($pgv_changes[$this->pid."_".$GEDCOM])) {
				//-- get the changed record from the file
				$newrec = find_updated_record($this->pid, PGV_GED_ID);
				//print("jkdsakjhdkjsadkjsakjdhsakd".$newrec);
				$remoterfn = get_gedcom_value("RFN", 1, $newrec);
			} else {
				$remoterfn = get_gedcom_value("RFN", 1, $indirec);
			}
			// print "remoterfn=".$remoterfn;
			//-- get an updated record from the web service
			if (!empty($remoterfn)) {
				$parts = explode(':', $remoterfn);
				if (count($parts)==2) {
					$servid = $parts[0];
					$aliaid = $parts[1];
					if (!empty($servid)&&!empty($aliaid)) {
						require_once PGV_ROOT.'includes/classes/class_serviceclient.php';
						$serviceClient = ServiceClient::getInstance($servid);
						if (!is_null($serviceClient)) {
							if (!empty($newrec)) $mergerec = $serviceClient->mergeGedcomRecord($aliaid, $newrec, true);
							else $mergerec = $serviceClient->mergeGedcomRecord($aliaid, $indirec, true);
							if ($serviceClient->type=="remote") {
								$newrec = $mergerec;
							}
							else {
								$indirec = $mergerec;
							}
						}
					}
				}
			}
			if (!empty($newrec)) {
				$this->diffindi = new Person($newrec, false);
				$this->diffindi->setChanged(true);
				$indirec = $newrec;
			}
		}

		if ($this->show_changes) {
			$this->indi->diffMerge($this->diffindi);
		}

		//-- only allow editors or users who are editing their own individual or their immediate relatives
		if ($this->indi->canDisplayDetails()) {
			$this->canedit = PGV_USER_CAN_EDIT;
/* Disable self-editing completely until we have a GEDCOM config option to control this
			if (!$this->canedit && $USE_QUICK_UPDATE) {
				$my_id=PGV_USER_GEDCOM_ID;
				if ($my_id) {
					if ($this->pid==$my_id) $this->canedit=true;
					else {
						$famids = array_merge(find_sfamily_ids($my_id), find_family_ids($my_id));
						foreach($famids as $indexval => $famid) {
							if (!isset($pgv_changes[$famid."_".$GEDCOM])) $famrec = find_family_record($famid, $this->ged_id);
							else $famrec = find_updated_record($famid, $this->ged_id);
							if (preg_match("/1 (HUSB|WIFE|CHIL) @$this->pid@/", $famrec)>0) $this->canedit=true;
						}
					}
				}
			}
*/
		}
		
		$this->modules = PGVModule::getActiveList('T');
		uasort($this->modules, "PGVModule::compare_tab_order");
		$count = 0;
		foreach($this->modules as $mod) {
			if ($mod->hasTab()) {
				$tab = $mod->getTab();
				if ($tab!=null) {
					$tab->setController($this);
					if ($tab->hasContent() || PGV_USER_CAN_EDIT) {		
						//-- convert default tab as name to number
						if ($mod->getName()===$this->default_tab) $this->default_tab = $count;
						if ($this->static_tab==null) $this->static_tab = $mod;
						else $count++;
					}
				}
			}
		}
		if ($this->default_tab<0 || $this->default_tab > count($this->modules)-1) $this->default_tab=0;
		
		if (!isset($_SESSION['PGV_pin']) && $DEFAULT_PIN_STATE)
			 $_SESSION['PGV_pin'] = true;
			 
		//-- handle ajax calls
		if ($this->action=="ajax") {
			$tab = 0;
			if (isset($_REQUEST['module'])) {
				$tabname = $_REQUEST['module'];
				header("Content-Type: text/html; charset=$CHARACTER_SET");//AJAX calls do not have the meta tag headers and need this set
				$mod = $this->modules[$tabname];
				if ($mod) {
					echo $mod->getTab()->getContent();
				}
			}
			
			if (isset($_REQUEST['pin'])) {
				if ($_REQUEST['pin']=='true') $_SESSION['PGV_pin'] = true;
				else $_SESSION['PGV_pin'] = false;
			}
			
			//-- only get the requested tab and then exit
			if (PGV_DEBUG_SQL) {
				echo PGV_DB::getQueryLog();
			}
			exit;
		}
	}
	//-- end of init function
	/**
	* Add a new favorite for the action user
	*/
	function addFavorite() {
		global $GEDCOM;
		if (PGV_USER_ID && !empty($_REQUEST["gid"])) {
			$gid = strtoupper($_REQUEST["gid"]);
			$indirec = find_person_record($gid, PGV_GED_ID);
			if ($indirec) {
				$favorite = array();
				$favorite["username"] = PGV_USER_NAME;
				$favorite["gid"] = $gid;
				$favorite["type"] = "INDI";
				$favorite["file"] = $GEDCOM;
				$favorite["url"] = "";
				$favorite["note"] = "";
				$favorite["title"] = "";
				addFavorite($favorite);
			}
		}
	}
	/**
	* Accept any edit changes into the database
	* Also update the indirec we will use to generate the page
	*/
	function acceptChanges() {
		global $GEDCOM;
		if (!PGV_USER_CAN_ACCEPT) return;
		if (accept_changes($this->pid."_".$GEDCOM)) {
			$this->show_changes=false;
			$this->accept_success=true;
			//-- delete the record from the cache and refresh it
			$indirec = find_person_record($this->pid, PGV_GED_ID);
			//-- check if we just deleted the record and redirect to index
			if (empty($indirec)) {
				header("Location: index.php?ctype=gedcom");
				exit;
			}
			$this->indi = new Person($indirec);
		}
	}

	/**
	* return the title of this page
	* @return string the title of the page to go in the <title> tags
	*/
	function getPageTitle() {
		global $pgv_lang;
		if ($this->indi) {
			$name = $this->indi->getFullName();
			return $name." - ".$this->indi->getXref()." - ".$pgv_lang["indi_info"];
		}
		else {
			return $pgv_lang["unable_to_find_record"];
		}
	}

	/**
	* gets a string used for setting the value of a cookie using javascript
	*/
	function getCookieTabString() {
		$str = "";
		if (isset($_COOKIE['lasttabs'])) {
			$parts = explode(':', $_COOKIE['lasttabs']);
			foreach($parts as $i=>$val) {
				$inner = explode('=', $val);
				if (count($inner)>1) {
					if ($inner[0]!=$this->pid) $str .= $val.":";
				}
			}
		}
		return $str;
	}
	/**
	* check if we can show the highlighted media object
	* @return boolean
	*/
	function canShowHighlightedObject() {
		global $MULTI_MEDIA, $SHOW_HIGHLIGHT_IMAGES, $USE_SILHOUETTE, $PGV_IMAGES;

		if (($this->indi->canDisplayDetails()) && ($MULTI_MEDIA && $SHOW_HIGHLIGHT_IMAGES)) {
			$firstmediarec = $this->indi->findHighlightedMedia();
			if ($firstmediarec) return true;
		}
		if ($USE_SILHOUETTE && isset($PGV_IMAGES["default_image_U"]["other"])) { return true; }
		return false;
	}
	/**
	* check if we can show the gedcom record
	* @return boolean
	*/
	function canShowGedcomRecord() {
		global $SHOW_GEDCOM_RECORD;
		if (PGV_USER_CAN_EDIT && $SHOW_GEDCOM_RECORD && $this->indi->canDisplayDetails())
			return true;
	}
	/**
	* check if use can edit this person
	* @return boolean
	*/
	function userCanEdit() {
		return $this->canedit;
	}
	/**
	* get the highlighted object HTML
	* @return string HTML string for the <img> tag
	*/
	function getHighlightedObject() {
		global $USE_THUMBS_MAIN, $THUMBNAIL_WIDTH, $USE_MEDIA_VIEWER, $GEDCOM, $PGV_IMAGE_DIR, $PGV_IMAGES, $USE_SILHOUETTE, $sex;
		if ($this->canShowHighlightedObject()) {
			$firstmediarec = $this->indi->findHighlightedMedia();
			if (!empty($firstmediarec)) {
				$filename = thumb_or_main($firstmediarec);		// Do we send the main image or a thumbnail?
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
					if (PGV_USE_LIGHTBOX) {
						print "<a href=\"" . $firstmediarec["file"] . "\" rel=\"clearbox[general_1]\" rev=\"" . $mid . "::" . $GEDCOM . "::" . PrintReady(htmlspecialchars($name, ENT_QUOTES, 'UTF-8')) . "\">" . "\n";
					} else if (!$USE_MEDIA_VIEWER && $imgsize) {
						$result .= "<a href=\"javascript:;\" onclick=\"return openImage('".encode_url(encrypt($firstmediarec["file"]))."', $imgwidth, $imgheight);\">";
					} else {
						$result .= "<a href=\"mediaviewer.php?mid={$mid}\">";
					}
					$result .= "<img src=\"$filename\" align=\"left\" class=\"".$class."\" border=\"none\" title=\"".PrintReady(htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8'))."\" alt=\"".PrintReady(htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8'))."\" />";
					$result .= "</a>";
					return $result;
				}
			}
		}
		if ($USE_SILHOUETTE && isset($PGV_IMAGES["default_image_U"]["other"])) {
			$class = "\" width=\"".$THUMBNAIL_WIDTH;
			$sex = $this->indi->getSex();
			$result = "<img src=\"";
			if ($sex == 'F') {
				$result .= $PGV_IMAGE_DIR."/".$PGV_IMAGES["default_image_F"]["other"];
			} 
			else if ($sex == 'M') {
				$result .= $PGV_IMAGE_DIR."/".$PGV_IMAGES["default_image_M"]["other"];
			}
			else {
				$result .= $PGV_IMAGE_DIR."/".$PGV_IMAGES["default_image_U"]["other"];
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
		global $pgv_lang, $factarray, $UNDERLINE_NAME_QUOTES, $NAME_REVERSE;
		global $lang_short_cut, $LANGUAGE;

		if (!$event->canShowDetails()) {
			return false;
		}
		$factrec = $event->getGedComRecord();
		$linenum = $event->getLineNumber();

		$this->name_count++;
		echo "<td valign=\"top\"";
		if (strpos($factrec, "PGV_OLD")!==false) {
			echo " class=\"namered\"";
		}
		if (strpos($factrec, "PGV_NEW")!==false) {
			echo " class=\"nameblue\"";
		}
		echo ">";
		if (!preg_match("/^2 (SURN)|(GIVN)/m", $factrec)) {
			$dummy=new Person($factrec);
			$dummy->setPrimaryName(0);
			echo '<span class="label">', $pgv_lang['name'], ': </span><br />';
			echo PrintReady($dummy->getFullName()), '<br />';
		}
		$ct = preg_match_all('/\n2 (\w+) (.*)/', $factrec, $nmatch, PREG_SET_ORDER);
		for($i=0; $i<$ct; $i++) {
			$fact = trim($nmatch[$i][1]);
			if (($fact!="SOUR")&&($fact!="NOTE")) {
				if ($fact=="_AKAN" || $fact=="_AKA" || $fact=="ALIA") {
					// Allow special processing for different languages
					$func="fact_AKA_localisation_{$lang_short_cut[$LANGUAGE]}";
					if (function_exists($func)) {
						// Localise the AKA fact
						$func($fact, $this->pid);
					}
				}
				echo "\n\t\t\t<span class=\"label\">";
				if (isset($pgv_lang[$fact])) {
					print $pgv_lang[$fact];
				} elseif (isset($factarray[$fact])) {
					echo $factarray[$fact];
				} else {
					echo $fact;
				}
				echo ":</span><span class=\"field\"> ";
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
				echo " </span><br />";
			}
		}
		if ($this->total_names>1 && !$this->isPrintPreview() && $this->userCanEdit() && !strpos($factrec, 'PGV_OLD')) {
			echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:;\" class=\"font9\" onclick=\"edit_name('".$this->pid."', ".$linenum."); return false;\">", $pgv_lang["edit_name"], "</a> | ";
			echo "<a class=\"font9\" href=\"javascript:;\" onclick=\"delete_record('".$this->pid."', ".$linenum."); return false;\">", $pgv_lang["delete_name"], "</a>\n";
			if ($this->name_count==2) {
				print_help_link("delete_name_help", "qm");
			}
			echo "<br />\n";
		}
		if (preg_match("/\d (NOTE)|(SOUR)/", $factrec)>0) {
			// -- find sources for this name
			echo "<div class=\"indent\">";
			print_fact_sources($factrec, 2);
			//-- find the notes for this name
			echo "&nbsp;&nbsp;&nbsp;";
			print_fact_notes($factrec, 2);
			echo "</div><br />";
		}
		echo "</td>\n";
	}

	/**
	* print information for a sex record
	*
	* Called from the individual information page
	* @see individual.php
	* @param Event $event the Event object
	*/
	function print_sex_record(&$event) {
		global $pgv_lang, $sex;

		if (!$event->canShowDetails()) return false;
		$factrec = $event->getGedComRecord();
		$sex = $event->getDetail();
		if (empty($sex)) $sex = "U";
		echo "<td valign=\"top\"";
		if (strpos($factrec, "PGV_OLD")!==false) {
			echo " class=\"namered\"";
		}
		if (strpos($factrec, "PGV_NEW")!==false) {
			echo " class=\"nameblue\"";
		}
		echo ">";
		print "<span class=\"label\">".$pgv_lang["sex"].": </span><span class=\"field\">".$this->sexarray[$sex];
		if ($sex=='M') {
			echo Person::sexImage('M', 'small', '', $pgv_lang['male']);
		} elseif ($sex=='F') {
			echo Person::sexImage('F', 'small', '', $pgv_lang['female']);
		} else {
			echo Person::sexImage('U', 'small', '', $pgv_lang['unknown']);
		}
		if ($this->SEX_COUNT>1) {
			if ((!$this->isPrintPreview()) && ($this->userCanEdit()) && (strpos($factrec, "PGV_OLD")===false)) {
				if ($event->getLineNumber()=="new") {
					print "<br /><a class=\"font9\" href=\"javascript:;\" onclick=\"add_new_record('".$this->pid."', 'SEX'); return false;\">".$pgv_lang["edit"]."</a>";
				} else {
						print "<br /><a class=\"font9\" href=\"javascript:;\" onclick=\"edit_record('".$this->pid."', ".$event->getLineNumber()."); return false;\">".$pgv_lang["edit"]."</a> | ";
						print "<a class=\"font9\" href=\"javascript:;\" onclick=\"delete_record('".$this->pid."', ".$event->getLineNumber()."); return false;\">".$pgv_lang["delete"]."</a>\n";
				}
			}
		}
		print "<br /></span>";
		// -- find sources
		print "&nbsp;&nbsp;&nbsp;";
		print_fact_sources($event->getGedComRecord(), 2);
		//-- find the notes
		print "&nbsp;&nbsp;&nbsp;";
		print_fact_notes($event->getGedComRecord(), 2);
		print "</td>";
	}
	/**
	* get the edit menu
	* @return Menu
	*/
	function &getEditMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;
		global $NAME_LINENUM, $SEX_LINENUM, $pgv_lang, $pgv_changes, $USE_QUICK_UPDATE;
		if ($TEXT_DIRECTION=="rtl") {
			$ff="_rtl";
		} else {
			$ff="";
		}
		//-- main edit menu
		$menu = new Menu($pgv_lang["edit"]);
		if (!empty($PGV_IMAGES["edit_indi"]["large"])) {
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["edit_indi"]["large"]);
		}
		else if (!empty($PGV_IMAGES["edit_indi"]["small"])) {
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["edit_indi"]["small"]);
		}
		$menu->addClass("submenuitem$ff", "submenuitem_hover$ff", "submenu$ff");
		// Determine whether the Quick Update form can be shown
		$showQuickForm = false;
		if ($USE_QUICK_UPDATE) {
			if ($USE_QUICK_UPDATE==='1' && PGV_USER_IS_ADMIN) {
				$showQuickForm = true;
			} elseif ($USE_QUICK_UPDATE==='2' && PGV_USER_GEDCOM_ADMIN) {
				$showQuickForm = true;
			} elseif (($USE_QUICK_UPDATE==='3' || $USE_QUICK_UPDATE===true) && PGV_USER_CAN_EDIT) {
				$showQuickForm = true;
			}
		}
		if ($showQuickForm) {
			$submenu = new Menu($pgv_lang["quick_update_title"]);
			$submenu->addOnclick("return quickEdit('".$this->pid."', '', '".$GEDCOM."');");
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);

			$menu->addSeparator();
		}

		if (PGV_USER_CAN_EDIT) {
			if (count($this->indi->getSpouseFamilyIds())>1) {
				$submenu = new Menu($pgv_lang["reorder_families"]);
				$submenu->addOnclick("return reorder_families('".$this->pid."');");
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}

			if ($this->total_names<2) {
				$submenu = new Menu($pgv_lang["edit_name"]);
				$submenu->addOnclick("return edit_name('".$this->pid."', $NAME_LINENUM);");
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}

			$submenu = new Menu($pgv_lang["add_name"]);
			$submenu->addOnclick("return add_name('".$this->pid."');");
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);

			if ($this->SEX_COUNT<2) {
				$submenu = new Menu($pgv_lang["edit_sex"]);
				if ($SEX_LINENUM=="new") $submenu->addOnclick("return add_new_record('".$this->pid."', 'SEX');");
				else $submenu->addOnclick("return edit_record('".$this->pid."', $SEX_LINENUM);");
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}

			$menu->addSeparator();
		}

		if (isset($pgv_changes[$this->pid."_".$GEDCOM])) {
			if (!$this->show_changes) {
				$label = $pgv_lang["show_changes"];
				$link = $this->indi->getLinkUrl()."&show_changes=yes";
			} else {
				$label = $pgv_lang["hide_changes"];
				$link = $this->indi->getLinkUrl()."&show_changes=no";
			}
			$submenu = new Menu($label, encode_url($link));
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);

			if (PGV_USER_CAN_ACCEPT) {
				$submenu = new Menu($pgv_lang["undo_all"], encode_url($this->indi->getLinkUrl()."&action=undo"));
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				$submenu = new Menu($pgv_lang["accept_all"], encode_url($this->indi->getLinkUrl()."&action=accept"));
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}

			$menu->addSeparator();
		}

		if (PGV_USER_IS_ADMIN || $this->canShowGedcomRecord()) {
			$submenu = new Menu($pgv_lang["edit_raw"]);
			$submenu->addOnclick("return edit_raw('".$this->pid."');");
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
		}

		$submenu = new Menu($pgv_lang["delete_person"]);
		$submenu->addOnclick("return deleteperson('".$this->pid."');");
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);

		//-- get the link for the first submenu and set it as the link for the main menu
		if (isset($menu->submenus[0])) {
			$link = $menu->submenus[0]->onclick;
			$menu->addOnclick($link);
		}
		return $menu;
	}
	/**
	* check if we can show the other menu
	* @return boolean
	*/
	function canShowOtherMenu() {
		global $SHOW_GEDCOM_RECORD, $ENABLE_CLIPPINGS_CART;
		if ($this->indi->canDisplayDetails() && ($SHOW_GEDCOM_RECORD || $ENABLE_CLIPPINGS_CART>=PGV_USER_ACCESS_LEVEL))
			return true;
		return false;
	}
	/**
	* get the "other" menu
	* @return Menu
	*/
	function &getOtherMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;
		global $SHOW_GEDCOM_RECORD, $ENABLE_CLIPPINGS_CART, $pgv_lang;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl";
		else $ff="";
		//-- main other menu item
		$menu = new Menu($pgv_lang["other"]);
		if ($SHOW_GEDCOM_RECORD) {
			if (!empty($PGV_IMAGES["gedcom"]["small"])) $menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["large"]);
			if ($this->show_changes && PGV_USER_CAN_EDIT) $menu->addOnclick("return show_gedcom_record('new');");
			else $menu->addOnclick("return show_gedcom_record('');");
		} else {
			if (!empty($PGV_IMAGES["clippings"]["small"])) $menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["clippings"]["small"]);
			$menu->addLink(encode_url("module.php?mod=clippings&action=add&id={$this->pid}&type=indi"));
		}
		$menu->addClass("submenuitem$ff", "submenuitem_hover$ff", "submenu$ff");
		if ($SHOW_GEDCOM_RECORD) {
			$submenu = new Menu($pgv_lang["view_gedcom"]);
			if (!empty($PGV_IMAGES["gedcom"]["small"])) $submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"]);
			if ($this->show_changes && PGV_USER_CAN_EDIT) $submenu->addOnclick("return show_gedcom_record('new');");
			else $submenu->addOnclick("return show_gedcom_record();");
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
		}
		if ($this->indi->canDisplayDetails() && $ENABLE_CLIPPINGS_CART>=PGV_USER_ACCESS_LEVEL) {
			$submenu = new Menu($pgv_lang["add_to_cart"], encode_url("module.php?mod=clippings&action=add&id={$this->pid}&type=indi"));
			if (!empty($PGV_IMAGES["clippings"]["small"])) $submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["clippings"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
		}
		if ($this->indi->canDisplayDetails() && PGV_USER_NAME) {
			$submenu = new Menu($pgv_lang["add_to_my_favorites"], encode_url($this->indi->getLinkUrl()."&action=addfav&gid={$this->pid}"));
			if (!empty($PGV_IMAGES["gedcom"]["small"])) $submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
		}
		return $menu;
	}
	/**
	* get global facts
	* global facts are NAME and SEX
	* @return array return the array of global facts
	*/
	function getGlobalFacts() {
		global $NAME_LINENUM, $SEX_LINENUM;

		$globalfacts = $this->indi->getGlobalFacts();
		foreach ($globalfacts as $key => $value) {
			$fact = $value->getTag();
			if ($fact=="SEX") {
				$this->SEX_COUNT++;
				$SEX_LINENUM = $value->getLineNumber();
			}
			if ($fact=="NAME") {
				$this->total_names++;
				$NAME_LINENUM = $value->getLineNumber();
			}
			}
		return $globalfacts;
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
		global $factarray, $pgv_lang;
		$people = array();
		if (!is_object($family)) return $people;
		$labels = array();
		if ($type=="parents") {
			$labels["parent"] = $pgv_lang["parent"];
			$labels["mother"] = $pgv_lang["mother"];
			$labels["father"] = $pgv_lang["father"];
			$labels["sibling"] = $pgv_lang["sibling"];
			$labels["sister"] = $pgv_lang["sister"];
			$labels["brother"] = $pgv_lang["brother"];
		}
		if ($type=="step"){
			$labels["parent"] = $pgv_lang["stepparent"];
			$labels["mother"] = $pgv_lang["stepmom"];
			$labels["father"] = $pgv_lang["stepdad"];
			$labels["sibling"] = $pgv_lang["halfsibling"];
			$labels["sister"] = $pgv_lang["halfsister"];
			$labels["brother"] = $pgv_lang["halfbrother"];
		}
		if ($type=="spouse") {
			if ($family->isDivorced()) {
				$labels["parent"] = $pgv_lang["ex-spouse"];
				$labels["mother"] = $pgv_lang["ex-wife"];
				$labels["father"] = $pgv_lang["ex-husband"];
			}
			else {
				$marr_rec = $family->getMarriageRecord();
				if (!empty($marr_rec)) {
					$type = $family->getMarriageType();
					if (empty($type) || stristr($type, "partner")===false) {
						$labels["parent"] = $pgv_lang["spouse"];
						$labels["mother"] = $pgv_lang["wife"];
						$labels["father"] = $pgv_lang["husband"];
					}
					else {
						if (isset($pgv_lang[$type])) $label = $pgv_lang[$type];
						else $label = $pgv_lang["partner"];
						$labels["parent"] = $label;
						$labels["mother"] = $label;
						$labels["father"] = $label;
					}
				}
				else {
					$labels["parent"] = $pgv_lang["spouse"];
					$labels["mother"] = $pgv_lang["wife"];
					$labels["father"] = $pgv_lang["husband"];
				}
			}
			$labels["sibling"] = $pgv_lang["child"];
			$labels["sister"] = $pgv_lang["daughter"];
			$labels["brother"] = $pgv_lang["son"];
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
		if ($type=="step") {
			$fams = $this->indi->getChildFamilies();
			foreach($fams as $key=>$fam) {
				if ($fam->hasParent($husb)) $labels["father"] = $pgv_lang["father"];
				if ($fam->hasParent($wife)) $labels["mother"] = $pgv_lang["mother"];
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
			if ($husb->getXref()==$this->pid) $label = "<img src=\"images/selected.png\" alt=\"\" />";
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
			if ($wife->getXref()==$this->pid) $label = "<img src=\"images/selected.png\" alt=\"\" />";
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
					if ($newhusb->getXref()==$this->pid) $label = "<img src=\"images/selected.png\" alt=\"\" />";
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
					if ($newwife->getXref()==$this->pid) $label = "<img src=\"images/selected.png\" alt=\"\" />";
					$newwife->setLabel($label);
				}
				else $newwife = null;
				//-- check for any new children
				$merged_children = array();
				$new_children = $newfamily->getChildren();
				$num = count($children);
				for($i=0; $i<$num; $i++) {
					$child = $children[$i];
					if (!is_null($child)) {
						$found = false;
						foreach($new_children as $key=>$newchild) {
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
				foreach($new_children as $key=>$newchild) {
					if (!is_null($newchild)) {
						$found = false;
						foreach($children as $key1=>$child) {
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
		for($i=0; $i<$num; $i++) {
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
					$label = "<img src=\"images/selected.png\" alt=\"\" />";
				}
				$famcrec = get_sub_record(1, "1 FAMC @".$family->getXref()."@", $children[$i]->gedrec);
				$pedi = get_gedcom_value("PEDI", 2, $famcrec, '', false);
				if ($pedi) {
					if ($pedi=="birth") {
						$label .= " (".$factarray["BIRT"].")";
					} elseif (isset($pgv_lang[$pedi])) {
						$label .= " (".$pgv_lang[$pedi].")";
					}
				}
				$children[$i]->setLabel($label);
			}
		}
		$num = count($newchildren);
		for($i=0; $i<$num; $i++) {
				$label = $labels["sibling"];
			$sex = $newchildren[$i]->getSex();
			if ($sex=="F") {
				$label = $labels["sister"];
			}
			if ($sex=="M") {
				$label = $labels["brother"];
		}
			if ($newchildren[$i]->getXref()==$this->pid) $label = "<img src=\"images/selected.png\" alt=\"\" />";
			$pedi = $newchildren[$i]->getChildFamilyPedigree($family->getXref());
			if ($pedi && isset($pgv_lang[$pedi])) $label .= " (".$pgv_lang[$pedi].")";
			$newchildren[$i]->setLabel($label);
		}
		$num = count($delchildren);
		for($i=0; $i<$num; $i++) {
				$label = $labels["sibling"];
			$sex = $delchildren[$i]->getSex();
			if ($sex=="F") {
				$label = $labels["sister"];
			}
			if ($sex=="M") {
				$label = $labels["brother"];
			}
			if ($delchildren[$i]->getXref()==$this->pid) $label = "<img src=\"images/selected.png\" alt=\"\" />";
			$pedi = $delchildren[$i]->getChildFamilyPedigree($family->getXref());
			if ($pedi && isset($pgv_lang[$pedi])) $label .= " (".$pgv_lang[$pedi].")";
			$delchildren[$i]->setLabel($label);
		}
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
		require PGV_ROOT.'modules/GEDFact_assistant/_CENS/census_1_ctrl.php';
	}
	function medialink_assistant() {
		require PGV_ROOT.'modules/GEDFact_assistant/_MEDIA/media_1_ctrl.php';
	}
// -----------------------------------------------------------------------------
// End GedFact Assistant Functions
// -----------------------------------------------------------------------------



}
// -- end of class

//-- load a user extended class if one exists
if (file_exists(PGV_ROOT.'includes/controllers/individual_ctrl_user.php'))
{
	require_once PGV_ROOT.'includes/controllers/individual_ctrl_user.php';
}
else
{
	class IndividualController extends IndividualControllerRoot
	{
	}
}

?>
