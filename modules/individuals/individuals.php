<?php
require_once 'includes/classes/class_sidebar.php';

if (!defined('PGV_AUTOCOMPLETE_LIMIT')) define('PGV_AUTOCOMPLETE_LIMIT', 500);

class individuals_Sidebar extends Sidebar {

	public function getContent() {
		global $SHOW_MARRIED_NAMES, $pgv_lang;
		global $PGV_IMAGE_DIR, $PGV_IMAGES;

		// Fetch a list of the initial letters of all surnames in the database
		$initials=get_indilist_salpha($SHOW_MARRIED_NAMES, false, PGV_GED_ID);
		// If there are no individuals in the database, do something sensible
		if (!$initials) {
			$initials[]='@';
		}

		$out = '<script type="text/javascript">
		<!--
		var loadedNames = new Array();
		
		function searchQ() {
			var query = jQuery("#sb_indi_name").attr("value");
			if (query.length>1) {
				jQuery("#sb_indi_content").load("sidebar.php?action=individuals&search="+query);
			}
		}
		
		jQuery(document).ready(function(){
			jQuery("#sb_indi_name").focus(function(){this.select();});
			jQuery("#sb_indi_name").blur(function(){if (this.value=="") this.value="'.$pgv_lang['search'].'";});
			var timerid = null;
			jQuery("#sb_indi_name").keyup(function(e) {
				if (timerid) window.clearTimeout(timerid);
				timerid = window.setTimeout("searchQ()", 500);
			});
			jQuery(".sb_indi_letter").live("click", function() {
				jQuery("#sb_indi_content").load(this.href);
				return false;
			});
			jQuery(".sb_indi_surname").live("click", function() {
				var surname = jQuery(this).attr("title");
				var alpha = jQuery(this).attr("alt");
				
				if (!loadedNames[surname]) {
					jQuery.ajax({
					  url: "sidebar.php?action=individuals&alpha="+alpha+"&surname="+surname,
					  cache: false,
					  success: function(html){
					    jQuery("#sb_indi_"+surname+" div").html(html);
					    jQuery("#sb_indi_"+surname+" div").show();
					    jQuery("#sb_indi_"+surname).css("list-style-image", "url('.$PGV_IMAGE_DIR."/".$PGV_IMAGES['minus']['other'].')");
					    loadedNames[surname]=2;
					  }
					});
				}
				else if (loadedNames[surname]==1) {
					loadedNames[surname]=2;
					jQuery("#sb_indi_"+surname+" div").show();
					jQuery("#sb_indi_"+surname).css("list-style-image", "url('.$PGV_IMAGE_DIR."/".$PGV_IMAGES['minus']['other'].')");
				}
				else {
					loadedNames[surname]=1;
					jQuery("#sb_indi_"+surname+" div").hide();
					jQuery("#sb_indi_"+surname).css("list-style-image", "url('.$PGV_IMAGE_DIR."/".$PGV_IMAGES['plus']['other'].')");
				}
				return false;
			});
		});
		//-->
		</script>
		<form method="post" action="sidebar.php" onsubmit="return false;">
		<input type="text" name="sb_indi_name" id="sb_indi_name" value="'.$pgv_lang['search'].'" />
		<p>';
		foreach ($initials as $letter) {
			switch ($letter) {
				case '@':
					$html=$pgv_lang['NN'];
					break;
				case ',':
					$html=$pgv_lang['none'];
					break;
				default:
					$html=$letter;
					break;
			}
			$html='<a href="sidebar.php?action=individuals&amp;alpha='.urlencode($letter).'" class="sb_indi_letter">'.$html.'</a>';
			$out .= $html." ";
		}

		$out .= '</p>';
		$out .= '<div id="sb_indi_content">';
		
		if (isset($_SESSION['sb_individuals_last'])) {
			$last = $_SESSION['sb_individuals_last'];
			$alpha = $last['alpha'];
			$search = $last['search'];
			$surname = $last['surname'];
			if (!empty($search)) $out.= $this->search($search);
			else if (!empty($alpha)) $out.= $this->getAlphaSurnames($alpha, $surname);
		}
		
		$out .= '</div></form>';
		return $out;
	}

	public function getAlphaSurnames($alpha, $surname1='') {
		global $SHOW_MARRIED_NAMES;
		$surns=get_indilist_surns('', $alpha, $SHOW_MARRIED_NAMES, false, PGV_GED_ID);
		$out = '<ul>';
		foreach($surns as $surname=>$surns) {
			$out .= '<li id="sb_indi_'.$surname.'" class="sb_indi_surname_li"><a href="'.$surname.'" title="'.$surname.'" alt="'.$alpha.'" class="sb_indi_surname">'.$surname.'</a>';
			if (!empty($surname1) && $surname1==$surname) {
				$out .= '<div class="name_tree_div_visible">';
				$out .= $this->getSurnameIndis($alpha, $surname1);
				$out .= '</div>';
			}
			else
				$out .= '<div class="name_tree_div"></div>'; 
			$out .= '</li>';
		}
		$out .= '</ul>';
		return $out;
	}

	public function getSurnameIndis($alpha, $surname) {
		global $SHOW_MARRIED_NAMES, $pgv_lang;
		$indis=get_indilist_indis($surname, $alpha, '', $SHOW_MARRIED_NAMES, false, PGV_GED_ID);
		$out = '<ul>';
		$private_count = 0;
		foreach($indis as $person) {
			if ($person->canDisplayName()) {
				$out .= '<li><a href="'.encode_url($person->getLinkUrl()).'">'.$person->getSexImage().$person->getListName();
				if ($person->canDisplayDetails()) {
					$bd = $person->getBirthDeathYears(false,'');
					if (!empty($bd)) $out .= ' ('.$bd.')';
				}
				$out .= '</a></li>';
			}
			else $private_count++;
		}
		if ($private_count>0) $out .= '<li>'.$pgv_lang['private'].' ('.$private_count.')</li>';
		$out .= '</ul>';
		return $out;
	}

	public function search($query) {
		global $TBLPREFIX, $pgv_lang;
		if (strlen($query)<2) return '';
		$sql=
		"SELECT ? AS type, i_id AS xref, i_file AS ged_id, i_gedcom AS gedrec, i_isdead, i_sex".
		" FROM {$TBLPREFIX}individuals, {$TBLPREFIX}name".
		" WHERE (i_id ".PGV_DB::$LIKE." ? OR n_sort ".PGV_DB::$LIKE." ?)".
		" AND i_id=n_id AND i_file=n_file AND i_file=?".
		" ORDER BY n_sort";
		$rows=
		PGV_DB::prepareLimit($sql, PGV_AUTOCOMPLETE_LIMIT)
		->execute(array('INDI', "%{$query}%", "%{$query}%", PGV_GED_ID))
		->fetchAll(PDO::FETCH_ASSOC);

		$out = '<ul>';
		$private_count = 0;
		foreach ($rows as $row) {
			$person=Person::getInstance($row);
			if ($person->canDisplayName()) {
				$out .= '<li><a href="'.encode_url($person->getLinkUrl()).'">'.$person->getSexImage().$person->getListName();
				if ($person->canDisplayDetails()) {
					$bd = $person->getBirthDeathYears(false,'');
					if (!empty($bd)) $out .= ' ('.$bd.')';
				}
				$out .= '</a></li>';
			}
			else $private_count++;
		}
		if ($private_count>0) $out .= '<li>'.$pgv_lang['private'].' ('.$private_count.')</li>';
		$out .= '</ul>';
		return $out;
	}

	public function getAjaxContent() {
		$alpha   =safe_GET('alpha'); // All surnames beginning with this letter where "@"=unknown and ","=none
		$surname =safe_GET('surname', '[^<>&%{};]*'); // All indis with this surname.  NB - allow ' and "
		$search   =safe_GET('search');

		$last = array('alpha'=>$alpha, 'surname'=>$surname, 'search'=>$search);
		$_SESSION['sb_individuals_last'] = $last;

		if (!empty($search)) return $this->search($search);
		else if (empty($surname)) return $this->getAlphaSurnames($alpha, $surname);
		else return $this->getSurnameIndis($alpha, $surname);
	}

	public function hasContent() {
		return true;
	}
}
?>