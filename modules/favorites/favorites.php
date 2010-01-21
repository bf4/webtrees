<?php

require_once 'includes/classes/class_sidebar.php';

class favorites_Sidebar extends Sidebar {


	public function getTitle() {
		global $pgv_lang;
		return $pgv_lang["favorites"];
	}

	public function getContent() {
		global $pgv_lang;
		global $PGV_IMAGE_DIR, $PGV_IMAGES;
		global $GEDCOM;


		$gid='';
		if (PGV_USER_NAME && isset($this->controller)) {
			if (!empty($this->controller->pid)) $gid = $this->controller->pid;
			if (!empty($this->controller->famid)) $gid = $this->controller->famid;
			if (!empty($this->controller->sid)) $gid = $this->controller->sid;
			if (!empty($this->controller->mid)) $gid = $this->controller->mid;
			if (!empty($this->controller->rid)) $gid = $this->controller->rid;
		}

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
		if ($gid!='') {
			$out .= '<a class="load_href" href="sidebar.php?sb_action=favorites&amp;addfav='.$gid.'"><em>'.$pgv_lang['add_to_my_favorites'].'</em></a>';
		}
		$out .= '</div>';
		return $out;
	}

	public function getFavsList() {
		global $pgv_lang, $GEDCOM, $PGV_IMAGE_DIR, $PGV_IMAGES;
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
				$favorite["username"] = PGV_USER_NAME;
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