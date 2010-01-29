<?php
/**
 * Classes and libraries for module system
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2010 John Finlay
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
 * @subpackage Modules
 * @version $Id: class_media.php 5451 2009-05-05 22:15:34Z fisharebest $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once PGV_ROOT.'includes/classes/class_sidebar.php';

class favorites_Sidebar extends Sidebar {


	public function getTitle() {
		global $pgv_lang;
		return $pgv_lang["favorites"];
	}

	public function getContent() {
		global $pgv_lang;
		global $PGV_IMAGE_DIR, $PGV_IMAGES;
		global $GEDCOM;

		$out = '';

		$out .= '<script type="text/javascript">
			<!--
			jQuery(document).ready(function() {
				jQuery(".load_href").live("click", function(){
					jQuery("#sb_favorites_content").load(this.href);
					return false;
				});
			});
			//-->
			</script>
			<div id="sb_favorites_content">';
		$out .= $this->getFavsList();
		$out .= '</div>';
		return $out;
	}

	public function getFavsList() {
		global $pgv_lang, $GEDCOM, $PGV_IMAGE_DIR, $PGV_IMAGES;

		$gid='';
		if (PGV_USER_NAME && isset($this->controller)) {
			if (!empty($this->controller->pid)) $gid = $this->controller->pid;
			if (!empty($this->controller->famid)) $gid = $this->controller->famid;
			if (!empty($this->controller->sid)) $gid = $this->controller->sid;
			if (!empty($this->controller->mid)) $gid = $this->controller->mid;
			if (!empty($this->controller->rid)) $gid = $this->controller->rid;
		}

		$out ='';
		$gedcomfavs = getUserFavorites($GEDCOM);
		$userfavs = array();
		if (PGV_USER_NAME) $userfavs = getUserFavorites(PGV_USER_NAME);
		$out .= '<ul>';
		foreach($userfavs as $key=>$favorite) {
			$GEDCOM = $favorite["file"];
			if ($favorite["type"]=="URL" && !empty($favorite["url"])) {
				$out .= '<li><a href="'.encode_url($favorite["url"]).'">';
				$out .= PrintReady($favorite["title"]);
				$out .= '</a></li>';
			} else {
				if ($favorite["gid"]==$gid) $gid='';
				$record=GedcomRecord::getInstance($favorite["gid"]);
				if ($record && $record->canDisplayName()) {
					$out .= '<li><a href="'.encode_url($record->getLinkUrl()).'">';
					$out .= PrintReady($record->getFullName());
					if ($record->getType()=="INDI" && $record->canDisplayDetails()) {
						$bd = $record->getBirthDeathYears(false,'');
						if (!empty($bd)) $out .= ' '.PrintReady('('.$bd.')');
					}
					$out .= '</a>';
					$out .= ' <a class="load_href" href="sidebar.php?sb_action=favorites&amp;remfav='.$favorite["id"].'">';
					$out .= '<img src="'. $PGV_IMAGE_DIR. "/". $PGV_IMAGES["remove"]["other"].'" border="0" alt="'.$pgv_lang["remove"].'" title="'.$pgv_lang["remove"].'" />';
					$out .= '</a>';
					$out .= '</li>';
				}
			}
		}
		$out .= '</ul>';
		if ($gid!='') {
			$out .= '<a class="load_href" href="sidebar.php?sb_action=favorites&amp;addfav='.$gid.'"><em>'.$pgv_lang['add_to_my_favorites'].'</em></a>';
		}

		if (count($gedcomfavs)>0) {
			$out .= '<strong>'.$pgv_lang["gedcom_favorites"].'</strong>';
			$out .= '<ul>';
			foreach($gedcomfavs as $key=>$favorite) {
				$GEDCOM = $favorite["file"];
				if ($favorite["type"]=="URL" && !empty($favorite["url"])) {
					$out .= '<li><a href="'.encode_url($favorite["url"]).'">';
					$out .= PrintReady($favorite["title"]);
					$out .= '</a></li>';
				} else {
					if ($favorite["gid"]==$gid) $gid='';
					$record=GedcomRecord::getInstance($favorite["gid"]);
					if ($record && $record->canDisplayName()) {
						$out .= '<li><a href="'.encode_url($record->getLinkUrl()).'">';
						$out .= PrintReady($record->getFullName());
						if ($record->getType()=="INDI" && $record->canDisplayDetails()) {
							$bd = $record->getBirthDeathYears(false,'');
							if (!empty($bd)) $out .= ' '.PrintReady('('.$bd.')');
						}
						$out .= '</a>';
						if (PGV_USER_GEDCOM_ADMIN) {
							$out .= ' <a class="load_href" href="sidebar.php?sb_action=favorites&amp;remfav='.$favorite["id"].'">';
							$out .= '<img src="'. $PGV_IMAGE_DIR. "/". $PGV_IMAGES["remove"]["other"].'" border="0" alt="'.$pgv_lang["remove"].'" title="'.$pgv_lang["remove"].'" />';
							$out .= '</a>';
						}
						$out .= '</li>';
					}
				}
			}
			$out .= '</ul>';
			if (PGV_USER_GEDCOM_ADMIN) {
				if ($gid!='') {
					$out .= '<a class="load_href" href="sidebar.php?sb_action=favorites&amp;addfav='.$gid.'&amp;user=gedcom"><em>'.$pgv_lang['add_to_my_favorites'].'</em></a>';
				}
			}
		}
		return $out;
	}

	public function getAjaxContent() {
		global $GEDCOM;
		$addfav = safe_GET_xref('addfav', '');
		$remfav = safe_GET('remfav', '');
		if (PGV_USER_ID && !empty($addfav)) {
			$gid = strtoupper($addfav);
			$record = GedcomRecord::getInstance($gid);
			if ($record) {
				$favorite = array();
				$username = safe_GET('user', PGV_USER_NAME);
				if (PGV_USER_GEDCOM_ADMIN && $username=='gedcom') $username = $GEDCOM;
				else $username = PGV_USER_NAME;
				$favorite["username"] = $username;
				$favorite["gid"] = $gid;
				$favorite["type"] = $record->getType();
				$favorite["file"] = $GEDCOM;
				$favorite["url"] = "";
				$favorite["note"] = "";
				$favorite["title"] = "";
				addFavorite($favorite);
			}
		}
		if (PGV_USER_ID && !empty($remfav)) {
			deleteFavorite($remfav);
		}
		return $this->getFavsList();
	}

	public function hasContent() {
		return true;
	}
}
?>