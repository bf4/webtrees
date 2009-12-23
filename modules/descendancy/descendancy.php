<?php
require_once 'includes/classes/class_sidebar.php';

if (!defined('PGV_AUTOCOMPLETE_LIMIT')) define('PGV_AUTOCOMPLETE_LIMIT', 500);

class descendancy_Sidebar extends Sidebar {

	public function getContent() {
		global $pgv_lang;
		global $PGV_IMAGE_DIR, $PGV_IMAGES;

		$out = '<script type="text/javascript">
		<!--
		var dloadedNames = new Array();
		
		function dsearchQ() {
			var query = jQuery("#sb_desc_name").attr("value");
			if (query.length>1) {
				jQuery("#sb_desc_content").load("sidebar.php?sb_action=descendancy&search="+query);
			}
		}
		
		jQuery(document).ready(function(){
			jQuery("#sb_desc_name").focus(function(){this.select();});
			jQuery("#sb_desc_name").blur(function(){if (this.value=="") this.value="'.$pgv_lang['search'].'";});
			var dtimerid = null;
			jQuery("#sb_desc_name").keyup(function(e) {
				if (dtimerid) window.clearTimeout(dtimerid);
				dtimerid = window.setTimeout("dsearchQ()", 500);
			});
		
			jQuery(".sb_desc_indi").live("click", function() {
				var pid=this.title;
				if (!dloadedNames[pid]) {
					jQuery("#sb_desc_"+pid+" div").load(this.href);
					jQuery("#sb_desc_"+pid+" div").show();
					jQuery("#sb_desc_"+pid+" .plusminus").attr("src", "'.$PGV_IMAGE_DIR."/".$PGV_IMAGES['minus']['other'].'");
					dloadedNames[pid]=2;
				}
				else if (dloadedNames[pid]==1) {
					dloadedNames[pid]=2;
					jQuery("#sb_desc_"+pid+" div").show();
					jQuery("#sb_desc_"+pid+" .plusminus").attr("src", "'.$PGV_IMAGE_DIR."/".$PGV_IMAGES['minus']['other'].'");
				}
				else {
					dloadedNames[pid]=1;
					jQuery("#sb_desc_"+pid+" div").hide();
					jQuery("#sb_desc_"+pid+" .plusminus").attr("src", "'.$PGV_IMAGE_DIR."/".$PGV_IMAGES['plus']['other'].'");
				}
				return false;
			});
		});
		//-->
		</script>
		<form method="post" action="sidebar.php" onsubmit="return false;">
		<input type="text" name="sb_desc_name" id="sb_desc_name" value="'.$pgv_lang['search'].'" />';
		$out .= '</form><div id="sb_desc_content">';

		if ($this->controller) {
			$root = null;
			if ($this->controller->pid) {
				$root = Person::getInstance($this->controller->pid);
			}
			else if ($this->controller->famid) {
				$fam = Family::getInstance($this->controller->famid);
				if ($fam) $root = $fam->getHusband();
				if (!$root) $root = $fam->getWife(); 
			}
			if ($root!=null) {
				$out .= '<ul>';
				$out .= $this->getPersonLi($root, 1);
				$out .= '</ul>';
			}
		}
		$out .= '</div>';
		return $out;
	}
	
	public function getPersonLi(&$person, $generations=0) {
		global $PGV_IMAGE_DIR, $PGV_IMAGES;
		$out = '';
		$out .= '<li id="sb_desc_'.$person->getXref().'" class="sb_desc_indi_li"><a href="sidebar.php?sb_action=descendancy&amp;pid='.$person->getXref().'" title="'.$person->getXref().'" class="sb_desc_indi">';
		if ($generations>0) $out .= '<img src="'.$PGV_IMAGE_DIR."/".$PGV_IMAGES['minus']['other'].'" border="0" class="plusminus" />';
		else $out .= '<img src="'.$PGV_IMAGE_DIR."/".$PGV_IMAGES['plus']['other'].'" border="0" class="plusminus" />';
		$out .= $person->getSexImage().' '.$person->getListName().' ';
		if ($person->canDisplayDetails()) {
			$bd = $person->getBirthDeathYears(false,'');
			if (!empty($bd)) $out .= PrintReady(' ('.$bd.')');
		}
		$out .= '</a> <a href="'.encode_url($person->getLinkUrl()).'"><img src="'.$PGV_IMAGE_DIR.'/'.$PGV_IMAGES['indi']['button'].'" border="0" alt="indi" /></a>';
		if ($generations>0) {
			$out .= '<div class="desc_tree_div_visible">';
			$out .= $this->loadSpouses($person->getXref());
			$out .= '</div><script type="text/javascript">dloadedNames["'.$person->getXref().'"]=2;</script>';
		}else {
			$out .= '<div class="desc_tree_div">';
			$out .= '</div>';
		}
		$out .= '</li>';
		return $out;
	}
	
	public function getFamilyLi(&$family, &$person, $generations=0) {
		global $pgv_lang, $factarray;
		global $PGV_IMAGE_DIR, $PGV_IMAGES;
		$out = '';
		$out .= '<li id="sb_desc_'.$family->getXref().'" class="sb_desc_indi_li"><a href="sidebar.php?sb_action=descendancy&amp;famid='.$family->getXref().'" title="'.$family->getXref().'" class="sb_desc_indi">';
		$out .= '<img src="'.$PGV_IMAGE_DIR."/".$PGV_IMAGES['minus']['other'].'" border="0" class="plusminus" />';
		$out .= $person->getSexImage().$person->getListName();
		
		$marryear = $family->getMarriageYear();
		if (!empty($marryear)) {
			$out .= ' ('.$factarray['MARR'].' '.$marryear.')';
		}
		$out .= '</a> <a href="'.encode_url($person->getLinkUrl()).'"><img src="'.$PGV_IMAGE_DIR.'/'.$PGV_IMAGES['indi']['button'].'" border="0" alt="indi" /></a>';
		$out .= '<a href="'.encode_url($family->getLinkUrl()).'"><img src="'.$PGV_IMAGE_DIR.'/'.$PGV_IMAGES['family']['button'].'" border="0" alt="family" /></a>';
		$out .= '<div class="desc_tree_div_visible">';
		$out .= $this->loadChildren($family->getXref(), $generations);
		$out .= '</div><script type="text/javascript">dloadedNames["'.$family->getXref().'"]=2;</script>';
		$out .= '</li>';
		return $out;
	}

	public function search($query) {
		global $TBLPREFIX, $pgv_lang, $PGV_IMAGES, $PGV_IMAGE_DIR;
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
				$out .= $this->getPersonLi($person);
			}
			else $private_count++;
		}
		if ($private_count>0) $out .= '<li>'.$pgv_lang['private'].' ('.$private_count.')</li>';
		$out .= '</ul>';
		return $out;
	}
	
	public function loadSpouses($pid, $generations=0) {
		$out = '<ul>';
		$person = Person::getInstance($pid);
		if ($person->canDisplayDetails()) {
			$families = $person->getSpouseFamilies();
			foreach($families as $family) {
				$spouse = $family->getSpouse($person);
				if ($spouse) {
					$out .= $this->getFamilyLi($family, $spouse, $generations-1);
				}
			}
		}
		$out .= "</ul>";
		return $out;
	}
	
	public function loadChildren($famid, $generations=0) {
		global $pgv_lang;
		$out = '<ul>';
		$family = Family::getInstance($famid);
		if ($family->canDisplayDetails()) {
			$children = $family->getChildren();
			if (count($children)>0) {
				$private = 0;
				foreach($children as $child) {
					if ($child->canDisplayName()) $out .= $this->getPersonLi($child, $generations-1);
					else $private++;
				}
				if ($private>0) $out .= '<li class="sb_desc_indi_li">'.$pgv_lang['private'].' ('.$private.')</li>';
			}
			else {
				$out .= "No children";
			}
		}
		$out .= "</ul>";
		return $out;
	}

	public function getAjaxContent() {
		$search   =safe_GET('search');
		$pid   =safe_GET('pid', PGV_REGEX_XREF);
		$famid   =safe_GET('famid', PGV_REGEX_XREF);

		$last = array('search'=>$search);
		$_SESSION['sb_descendancy_last'] = $last;

		if (!empty($search)) return $this->search($search);
		else if (!empty($pid)) return $this->loadSpouses($pid, 1);
		else if (!empty($famid)) return $this->loadChildren($famid, 1);
	}

	public function hasContent() {
		return true;
	}
}
?>