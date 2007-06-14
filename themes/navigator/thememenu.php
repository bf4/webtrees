<?php
if (!empty($_REQUEST['navAjax'])) {
	chdir("../../");
	require_once("config.php");
}

$pgv_lang["trees"] = "Trees";
$pgv_lang["my_charts"] = "My Charts";
$pgv_lang["my_tree"] = "My Tree";
$pgv_lang["surnames"] = "Surnames";

// -- overide default menu bar
class NavMenuBar extends MenuBar {
	var $defaultMenus = array("getHomeMenu","getGedcomMenu","getMygedviewMenu","getAdminMenu","getChartsMenu","getListsMenu",
	"getCalendarMenu", "getReportsMenu", "getClippingsMenu", "getSearchMenu", "getHelpMenu");
	
	function handleAjax() {
		//-- handle AJAX requests
		if (!empty($_REQUEST['navAjax'])) {
			if (isset($_REQUEST['loadSubMenu'])) {
				$menu = $this->getMenu($_REQUEST['loadSubMenu']);
				if (isset($_REQUEST['subSubMenu'])) $menu = $menu->submenus[$_REQUEST['subSubMenu']];
				print "<ul id=\"navlist2\" class=\"navlist\">";
				foreach($menu->submenus as $mi=>$submenu) {
					if (!$submenu->seperator) {
						$this->navPrintMenuItem($submenu, $_REQUEST['loadSubMenu'], 2, $mi);
					}
				}
				print "</ul>";
			}
			else if (isset($_REQUEST['surnameContent'])) {
				$this->surnameContent();
			}
			else if (isset($_REQUEST['loadSurnames'])) {
				$this->loadSurnames($_REQUEST['loadSurnames']);
			}
			else if (isset($_REQUEST['loadSurnamePeople'])) {
				$this->loadSurnamePeople($_REQUEST['loadSurnamePeople']);
			}
		}
	}
	
	function loadSurnamePeople($surname) {
		global $pgv_lang, $GEDCOM, $PGV_IMAGE_DIR, $PGV_IMAGES;
		$myindis = get_surname_indis($surname);
		uasort($myindis, "itemsort");
		print "<ul class=\"navlist\">";
		$hidden = 0;
		$surname = str2upper($surname);
		foreach($myindis as $gid=>$indi) {
			$person = Person::getInstance($gid);
			if ($person->canDisplayName()) {
				print "<li><a class=\"external\" style=\"font-size: 10px;\" href=\"individual.php?pid=".$person->getXref()."&amp;ged=".$GEDCOM."\">";
				print $person->getSortableName()." ";
				$byear = $person->getBirthYear();
				$dyear = $person->getDeathYear();
				if ($byear!="0000" && $dyear!="0000") {
					if ($byear=="0000") $byear=" ";
					if ($dyear=="0000") $dyear = " ";
					if (!$person->isDead()) $dyear = " ";
					$txt = "(".$byear."-".$dyear.")";
					print PrintReady($txt);
				}
				print "</a>";
				print '<img id="d_'.$person->getXref().'" alt="'.$person->getXref().'" class="draggable" src="'.$PGV_IMAGE_DIR."/".$PGV_IMAGES['indi']['button'].'" border="0" />';
				print "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"]."\" border=\"0\" width=\"15\" onclick=\"topnav.newRoot('".$person->getXref()."', topnav.innerPort, '".htmlentities($GEDCOM)."');\" />";
				print "</li>\n";
			}
			else $hidden++;
		}
		print "</ul>";
		print $pgv_lang["hidden"].": ".$hidden;
		print " Total: ".count($myindis);
	}
	
	function loadSurnames($letter) {
		global $pgv_lang;
		$myindis = get_alpha_indis($letter);
		$surnames = array();
		$hidden = 0;
		foreach($myindis as $gid=>$indi) {
			$person = Person::getInstance($gid);
			if ($person->canDisplayName()) {
				foreach($indi["names"] as $ni=>$namearray) {
					if ($letter==$namearray[1]) {
						$surname = str2upper($namearray[2]);
						if (!isset($surnames[$surname])) $surnames[$surname] = array("count"=>0, "name"=>$namearray[2]);
						$surnames[$surname]["count"]++;
						break;
					}
				}
			}
			else $hidden++;
			
		}
		uasort($surnames, "itemsort");
		print "<ul class=\"navlist\">";
		foreach($surnames as $surname=>$scount) {
			print "<li><a href=\"#\" onclick=\"loadSurnamePeople('".htmlentities($surname)."'); return false;\">".PrintReady(check_NN($scount["name"]))." [".$scount["count"]."]</a>";
			//print "<div id=\"".htmlentities($surname)."\" style=\"display: none;\"></div>";
			print "</li>\n";
		}
		print "</ul>\n";
		print $pgv_lang["hidden"].": ".$hidden;
		print " Total: ".count($myindis);
	}
	
	function surnameContent() {
		global $pgv_lang;
		$indialpha = get_indi_alpha();
		foreach($indialpha as $l=>$letter) {
			if ($letter!="@") print '<a href="#" style="font-size: 10px;" onclick="loadSurnames(\'themes/navigator/thememenu.php?navAjax=1&loadSurnames='.$letter.'\'); return false;">'.$letter."</a> ";
		}
		print '<a href="#" style="font-size: 10px;" onclick="loadSurnamePeople(\'@N.N.\'); return false;">';
		print "(".$pgv_lang["unknown"].") ";
		print "</a>\n";

		?>
		<form method="post" action="">
			<input type="text" name="nameSearch" /><input type="button" onclick="" value="<?php print $pgv_lang["search"];?>" />
		</form>
		<div id="surnameList" style="font-size: 10px;">
		</div>
		<?php
	}
	
	function navPrintMenu($mi) {
		$menu = $this->getMenu($mi);
		$this->navPrintMenuItem($menu, $mi);
	}
	
	function navPrintMenuItem(&$menu, $mi, $level=1, $submi=0) {
		if (empty($menu)) return false;
		print "<li class=\"menuitem\">";
		if (!empty($menu->icon)) print "<img src=\"{$menu->icon}\" class=\"icon\" alt=\"".htmlentities($menu->label).'" title="'.htmlentities($menu->label).'" />';
		/*@var $menu Menu */
		if ($menu->htmlitem!==false) {
			print $menu->htmlitem; 
		}
		else if ($menu->subCount()>0) {
			print '<a href="#" onclick="highlightMenu(document.getElementById(\'navlist\'), this); loadNav'.$level.'(\'themes/navigator/thememenu.php?navAjax=1&loadSubMenu='.$mi;
			if ($level>1) print '&subSubMenu='.$submi;
			print '\'); return false;">'.$menu->label.'</a>';
		}
		else print '<a class="external" href="'.$menu->link.'" onclick="'.$menu->onclick.'">'.$menu->label.'</a>';
		print "</li>\n";
	}
	
	//-- overide mygedview menu
	function &getMygedviewMenu() {
		global $GEDCOMS, $MEDIA_DIRECTORY, $MULTI_MEDIA;
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang, $PEDIGREE_ROOT_ID;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		
		$username = getUserName();
		if (empty($username)) return null;
		
		$link = "index.php?command=user";
		if (!empty($username)) {
			$user = getUser($username);
			if (!empty($user["gedcomid"][$GEDCOM])) {
				$link = "individual.php?pid=".$user["gedcomid"][$GEDCOM];
			}
		}
		//-- main menu
		$menu = new Menu($pgv_lang["mgv"], $link, "down");
		if (!empty($PGV_IMAGES["mygedview"]["small"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["mygedview"]["small"]);
		else if (!empty($PGV_IMAGES["gedcom"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		$menu->addAccesskey($pgv_lang["mgv"]);

		$user = getUser($username);
		$ged = $GEDCOM;
		if (empty($user["gedcomid"][$ged])) {
			foreach($GEDCOMS as $ged=>$gedarray) {
				if (!empty($user["gedcomid"][$ged])) break;
			}
		}
		//-- mygedview submenu
		$submenu = new Menu($pgv_lang["mgv"], "index.php?command=user&amp;ged=".$ged);
		if (!empty($PGV_IMAGES["mygedview"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["mygedview"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
		$menu->addSubmenu($submenu);
		
		if (!empty($user["gedcomid"][$ged])) {
				$submenu = new Menu($pgv_lang["my_tree"], "treenav.php?rootid=".$user["gedcomid"][$ged]."&amp;ged=".$ged);
				if (!empty($PGV_IMAGES["gedcom"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$submenu->addOnclick("document.getElementById('midcontent2').style.width='0px'; topnav.newRoot('".$user["gedcomid"][$ged]."', topnav.innerPort, '".htmlentities($ged)."'); return false;");
				$menu->addSubmenu($submenu);
				
				//-- my_indi submenu
				$submenu = new Menu($pgv_lang["my_indi"], "individual.php?pid=".$user["gedcomid"][$ged]."&amp;ged=".$ged);
				if (!empty($PGV_IMAGES["indis"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				
				$charts = $this->getChartsMenu($PEDIGREE_ROOT_ID,$user['gedcomid'][$ged],$ged);
				$charts->labelpos = 'right';
				$charts->addClass('submenuitem', 'submenuitem_hover', 'submenu');
				$charts->addFlyout('right');
				$charts->label = $pgv_lang['my_charts'];
				$menu->addSubMenu($charts);
				
				//-- quick_update submenu
				$submenu = new Menu($pgv_lang["quick_update_title"], "#");
				$submenu->addOnclick("return quickEdit('".$user["gedcomid"][$ged]."', '', '$ged');");
				if (!empty($PGV_IMAGES["indis"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				$menu->addSeperator();
			}
		//-- editaccount submenu
		if ($user["editaccount"]) {
			$submenu = new Menu($pgv_lang["editowndata"], "edituser.php?ged=".$ged);
			if (!empty($PGV_IMAGES["mygedview"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["mygedview"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
		}
		if (userCanEdit($username)) {
			//-- upload_media submenu
			 if (is_writable($MEDIA_DIRECTORY) && $MULTI_MEDIA) {
				$menu->addSeperator();
				$submenu = new Menu($pgv_lang["upload_media"], "uploadmedia.php");
				if (!empty($PGV_IMAGES["menu_media"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_media"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}
		}

		return $menu;
	}
	
	function &getAdminMenu() {
		global $GEDCOMS, $MEDIA_DIRECTORY, $MULTI_MEDIA;
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $pgv_lang, $PEDIGREE_ROOT_ID;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		
		$username = getUserName();
		if (empty($username)) return null;
		$user = getUser($username);
		
		//-- main menu
		$menu = new Menu($pgv_lang["admin"], "admin.php", "down");
		if (!empty($PGV_IMAGES["admin"]["small"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["admin"]["small"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff");
		$menu->addAccesskey($pgv_lang["admin"]);
		
		if (userIsAdmin($username) || (userGedcomAdmin($username, $GEDCOM))){
			//-- admin submenu
			$submenu = new Menu($pgv_lang["admin"], "admin.php");
			if (!empty($PGV_IMAGES["admin"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["admin"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			//-- manage_gedcoms submenu
			$submenu = new Menu($pgv_lang["manage_gedcoms"], "editgedcoms.php");
			if (!empty($PGV_IMAGES["admin"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["admin"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			//-- user_admin submenu
			$submenu = new Menu($pgv_lang["user_admin"], "useradmin.php");
			if (!empty($PGV_IMAGES["admin"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["admin"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
			$menu->addSubmenu($submenu);
			//-- upload_media submenu
			 if (is_writable($MEDIA_DIRECTORY) && $MULTI_MEDIA) {
				$submenu = new Menu($pgv_lang["manage_media"], "media.php");
				if (!empty($PGV_IMAGES["menu_media"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_media"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
			}
		}
		return $menu;
	}
	
	function &getSearchMenu() {
		global $pgv_lang;
		$menu = parent::getSearchMenu();
		$submenu = new Menu($pgv_lang["search"], '');
		$submenu->htmlitem = '<form action="search.php" method="get">
                    <input type="hidden" name="action" value="general" />
                    <input type="hidden" name="topsearch" value="yes" />
                    <input type="text" name="query" accesskey="'.$pgv_lang["accesskey_search"].'" size="12" value="" />
                    <input type="submit" name="search" value="'.$pgv_lang['search'].'" />
                  </form>';
		$menu->addSubmenu($submenu);
		return $menu;
	}
}
if (!empty($_REQUEST['navAjax'])) {
	$menubar = new NavMenuBar();
	$menubar->handleAjax();
	exit;
}
?>