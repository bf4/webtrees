<?php
/**
 * Classes and libraries for module system
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2010 PGV Development Team. All rights reserved.
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

class clippings_Sidebar extends Sidebar {

	var $clippingCtrl;
	
	public function getTitle() {
		global $pgv_lang;
		return $pgv_lang["clippings_cart"];
	}

	public function getCartList() {
		global $pgv_lang;
		global $PGV_IMAGE_DIR, $PGV_IMAGES;
		global $cart, $GEDCOM;
		$out ='<ul>';
		$ct = count($cart);
		if ($ct==0) $out .= '<br /><br />'.$pgv_lang["cart_is_empty"].'<br /><br />';
		else {
			for ($i=0; $i<$ct; $i++) {
				$clipping = $cart[$i];
				$tag = strtoupper(substr($clipping['type'], 0, 4)); // source => SOUR
				//print_r($clipping);
				//-- don't show clippings from other gedcoms
				if ($clipping['gedcom']==$GEDCOM) {
					$icon='';
					if ($tag=='INDI') $icon = "indis";
					if ($tag=='FAM' ) $icon = "sfamily";
					//	if ($tag=='SOUR') $icon = "source";
					//	if ($tag=='REPO') $icon = "repository";
					//	if ($tag=='NOTE') $icon = "notes";
					//	if ($tag=='OBJE') $icon = "media";
					if (!empty($icon)) {
						$out .= '<li>';
						if (!empty($icon)) {
							$out .= '<img src="'.$PGV_IMAGE_DIR."/".$PGV_IMAGES[$icon]["small"].'" border="0" alt="'.$tag.'" title="'.$tag.'" width="20" />';
						}
						$record=GedcomRecord::getInstance($clipping['id']);
						if ($record) {
							$out .= '<a href="'.encode_url($record->getLinkUrl()).'">';
							if ($record->getType()=="INDI") $out .=$record->getSexImage();
							$out .= ' '.$record->getFullName().' ';
							if ($record->getType()=="INDI" && $record->canDisplayDetails()) {
								$bd = $record->getBirthDeathYears(false,'');
								if (!empty($bd)) $out .= PrintReady(' ('.$bd.')');
							}
							$out .= '</a>';
						}
						$out .= '<a	class="remove_cart" href="sidebar.php?sb_action=clippings&amp;remove='.$i.'">
						<img src="'. $PGV_IMAGE_DIR. "/". $PGV_IMAGES["remove"]["other"].'" border="0" alt="'.$pgv_lang["remove"].'" title="'.$pgv_lang["remove"].'" /></a>';
						$out .='</li>';
					}
				}
			}
		}
		$out .= '</ul>';
		if (count($cart)>0) {
			$out .= '<a href="sidebar.php?sb_action=clippings&amp;empty=true" class="remove_cart">'.$pgv_lang["empty_cart"].'</a>'.print_help_link("empty_cart_help", "qm",'',false,true);
			$out .= '<br /><a href="sidebar.php?sb_action=clippings&amp;download=true" class="add_cart">'.$pgv_lang['download_now'].'</a>';
		}
		$out .= '<br />';
		return $out;
	}
	public function getContent() {
		require_once PGV_ROOT.'modules/clippings/clippings_ctrl.php';
		global $pgv_lang;
		global $PGV_IMAGE_DIR, $PGV_IMAGES;
		global $cart, $GEDCOM;

		$out = '';

		if ($this->controller) {
			$out .= '<script type="text/javascript">
			<!--
			jQuery(document).ready(function() {
				jQuery(".add_cart, .remove_cart").live("click", function(){
					jQuery("#sb_clippings_content").load(this.href);
					return false;
				});
			});
			//-->
			</script>
			<div id="sb_clippings_content">';
			$out .= $this->getCartList();
			$root = null;
			if ($this->controller->pid && !id_in_cart($this->controller->pid)) {
				$root = GedcomRecord::getInstance($this->controller->pid);
				if ($root && $root->canDisplayDetails()) 
					$out .= '<a href="sidebar.php?sb_action=clippings&amp;add='.$root->getXref().'" class="add_cart">
					<img src="'.$PGV_IMAGE_DIR.'/'.$PGV_IMAGES['clippings']['small'].'" width="20" /> '.str_replace("#NAME#", PrintReady($root->getListName()), $pgv_lang["add_name_to_cart"]).'</a>';
			}
			else if ($this->controller->famid && !id_in_cart($this->controller->pid)) {
				$fam = Family::getInstance($this->controller->famid);
				if ($fam && $fam->canDisplayDetails()) {
					$out .= '<a href="sidebar.php?sb_action=clippings&amp;add='.$fam->getXref().'" class="add_cart"> '.str_replace("#NAME#", PrintReady($fam->getFullName()), $pgv_lang["add_name_to_cart"]).'</a><br />';
				}
			}
			$out .= '</div>';
		}
		return $out;
	}

	public function askAddOptions(&$person) {
		global $pgv_lang,$MAX_PEDIGREE_GENERATIONS;
		$out = "<b>".$person->getFullName()."</b>";
		$out .= PGV_JS_START;
		$out .= 'function radAncestors(elementid) {var radFamilies=document.getElementById(elementid);radFamilies.checked=true;}
			function continueAjax(frm) {
				var others = jQuery("input[name=\'others\']:checked").val();
				var link = "sidebar.php?sb_action=clippings&add1="+frm.pid.value+"&others="+others+"&level1="+frm.level1.value+"&level2="+frm.level2.value+"&level3="+frm.level3.value;
				jQuery("#sb_clippings_content").load(link);
			}';
		$out .= PGV_JS_END;
		if ($person->getType()=='FAM') {

			$out .= '<form action="module.php" method="get" onsubmit="continueAjax(this); return false;">
			<input type="hidden" name="mod" value="clippings" />
			<input type="hidden" name="pgv_action" value="index" />
			<table>
			<tr><td class="topbottombar">'.$pgv_lang["which_links"].'
			<input type="hidden" name="pid" value="'.$person->getXref().'" />
			<input type="hidden" name="type" value="'.$person->getType().'" />
			<input type="hidden" name="action" value="add1" /></td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" checked value="none" />'.$pgv_lang["just_family"].'</td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="parents" />'.$pgv_lang["parents_and_family"].'</td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="members" />'.$pgv_lang["parents_and_child"].'</td></tr>
			<tr><td class="optionbox"><input type="radio" name="others" value="descendants" />'.$pgv_lang["parents_desc"].'</td></tr>
			<tr><td class="topbottombar"><input type="submit" value="'.$pgv_lang["continue"].'" /></td></tr>
			</table>
			</form>';
		}
		else if ($person->getType()=='INDI') {
			$out .= '<form action="module.php" method="get" onsubmit="continueAjax(this); return false;">
			<input type="hidden" name="mod" value="clippings" />
			<input type="hidden" name="pgv_action" value="index" />
		'.$pgv_lang["which_p_links"].'
		<input type="hidden" name="pid" value="'.$person->getXref().'" />
		<input type="hidden" name="type" value="'.$person->getType().'" />
		<input type="hidden" name="action" value="add1" />
		<ul>
		<li><input type="radio" name="others" checked value="none" />'.$pgv_lang["just_person"].'</li>
		<li><input type="radio" name="others" value="parents" />'.$pgv_lang["person_parents_sibs"].'</li>
		<li><input type="radio" name="others" value="ancestors" id="ancestors" />'.$pgv_lang["person_ancestors"].'<br />
				'.$pgv_lang["enter_person_generations"].'<input type="text" size="4" name="level1" value="'.$MAX_PEDIGREE_GENERATIONS.'" onfocus="radAncestors(\'ancestors\');"/></li>
		<li><input type="radio" name="others" value="ancestorsfamilies" id="ancestorsfamilies" />'.$pgv_lang["person_ancestor_fams"].'<br />
				'.$pgv_lang["enter_person_generations"].' <input type="text" size="4" name="level2" value="'. $MAX_PEDIGREE_GENERATIONS.'" onfocus="radAncestors(\'ancestorsfamilies\');" /></li>
		<li><input type="radio" name="others" value="members" />'.$pgv_lang["person_spouse"].'</li>
		<li><input type="radio" name="others" value="descendants" id="descendants" />'.$pgv_lang["person_desc"].'<br >
				'.$pgv_lang["enter_person_generations"].' <input type="text" size="4" name="level3" value="'.$MAX_PEDIGREE_GENERATIONS.'" onfocus="radAncestors(\'descendants\');" /></li>
		</ul>
		<input type="submit" value="'.$pgv_lang["continue"].'" />
		</form>';
		} else if ($person->getType()=='SOUR')  {
			$out .= '<form action="module.php" method="get" onsubmit="continueAjax(this); return false;">
		<input type="hidden" name="mod" value="clippings" />
		<input type="hidden" name="pgv_action" value="index" />
		<table>
		<tr><td class="topbottombar">'.$pgv_lang["which_s_links"].'
		<input type="hidden" name="pid" value="'.$person->getXref().'" />
		<input type="hidden" name="type" value="'.$person->getType().'" />
		<input type="hidden" name="action" value="add1" /></td></tr>
		<tr><td class="optionbox"><input type="radio" name="others" checked value="none" />'.$pgv_lang["just_source"].'</td></tr>
		<tr><td class="optionbox"><input type="radio" name="others" value="linked" />'.$pgv_lang["linked_source"].'</td></tr>
		<tr><td class="topbottombar"><input type="submit" value="'.$pgv_lang["continue"].'" />
		</table>
		</form>';
		}
		else return $this->getContent();
		return $out;
	}
	
	public function downloadForm() {
		global $pgv_lang, $TEXT_DIRECTION;
		$controller = $this->clippingCtrl;
		$out = PGV_JS_START;
		$out .= 'function cancelDownload() {
				var link = "sidebar.php?sb_action=clippings";
				jQuery("#sb_clippings_content").load(link);
			}';
		$out .= PGV_JS_END;
		$out .= '<form method="get" action="module.php">
		<input type="hidden" name="mod" value="clippings" />
		<input type="hidden" name="pgv_action" value="index" />
		<input type="hidden" name="action" value="download" />
		<table>
		<tr><td colspan="2" class="topbottombar"><h2>'.$pgv_lang["file_information"].'</h2></td></tr>
		<tr>
		<td class="descriptionbox width50 wrap">'.print_help_link("file_type_help", "qm", "", false, true). $pgv_lang["choose_file_type"].'</td>
		<td class="optionbox">';
		if ($TEXT_DIRECTION=='ltr') {
			$out .= '<input type="radio" name="filetype" checked="checked" value="gedcom" />&nbsp;GEDCOM<br/><input type="radio" name="filetype" value="gramps" DISABLED />&nbsp;Gramps XML <!-- GRAMPS doesn\'t work right now -->';
		} else {
			$out .= 'GEDCOM&nbsp;'.getLRM().'<input type="radio" name="filetype" checked="checked" value="gedcom" />'.getLRM().'<br />Gramps XML&nbsp;'.getLRM().'<input type="radio" name="filetype" value="gramps" />'.getLRM();
		}
		$out .= '
		</td></tr>

		<tr><td class="descriptionbox width50 wrap">'.print_help_link("zip_help", "qm", "", false, true). $pgv_lang["zip_files"].'</td>
		<td class="optionbox"><input type="checkbox" name="Zip" value="yes" checked="checked" /></td></tr>

		<tr><td class="descriptionbox width50 wrap">'.print_help_link("include_media_help", "qm", "", false, true).$pgv_lang["include_media"].'</td>
		<td class="optionbox"><input type="checkbox" name="IncludeMedia" value="yes" checked="checked" /></td></tr>
		';
		
		// Determine the Privatize options available to this user
		if (PGV_USER_IS_ADMIN) {
			$radioPrivatizeNone = 'checked="checked" ';
			$radioPrivatizeVisitor = '';
			$radioPrivatizeUser = '';
			$radioPrivatizeGedadmin = '';
			$radioPrivatizeAdmin = '';
		} else if (PGV_USER_GEDCOM_ADMIN) {
			$radioPrivatizeNone = 'DISABLED ';
			$radioPrivatizeVisitor = 'checked="checked" ';
			$radioPrivatizeUser = '';
			$radioPrivatizeGedadmin = '';
			$radioPrivatizeAdmin = 'DISABLED ';
		} else if (PGV_USER_ID) {
			$radioPrivatizeNone = 'DISABLED ';
			$radioPrivatizeVisitor = 'checked="checked" ';
			$radioPrivatizeUser = '';
			$radioPrivatizeGedadmin = 'DISABLED ';
			$radioPrivatizeAdmin = 'DISABLED ';
		} else {
			$radioPrivatizeNone = 'DISABLED ';
			$radioPrivatizeVisitor = 'checked="checked" DISABLED ';
			$radioPrivatizeUser = 'DISABLED ';
			$radioPrivatizeGedadmin = 'DISABLED ';
			$radioPrivatizeAdmin = 'DISABLED ';
		}
		$out .= '
		<tr><td class="descriptionbox width50 wrap">'.print_help_link("apply_privacy_help", "qm", "", false, true).$pgv_lang["apply_privacy"].'</td>
		<td class="list_value">
		<input type="radio" name="privatize_export" value="none" '.$radioPrivatizeNone.'/>&nbsp;'.$pgv_lang["none"].'<br />
		<input type="radio" name="privatize_export" value="visitor" '.$radioPrivatizeVisitor.'/>&nbsp;'.$pgv_lang["visitor"].'<br />
		<input type="radio" name="privatize_export" value="user" '.$radioPrivatizeUser.'/>&nbsp;'.$pgv_lang["user"].'<br />
		<input type="radio" name="privatize_export" value="gedadmin" '.$radioPrivatizeGedadmin.'/>&nbsp;'.$pgv_lang["gedadmin"].'<br />
		<input type="radio" name="privatize_export" value="admin" '.$radioPrivatizeAdmin.'/>&nbsp;'.$pgv_lang["siteadmin"].'
		</td></tr>

		<tr><td class="descriptionbox width50 wrap">'.print_help_link("utf8_ansi_help", "qm", "", false, true).$pgv_lang["utf8_to_ansi"].'</td>
		<td class="optionbox"><input type="checkbox" name="convert" value="yes" /></td></tr>

		<tr><td class="descriptionbox width50 wrap">'. print_help_link("remove_tags_help", "qm", "", false, true).$pgv_lang["remove_custom_tags"].'</td>
		<td class="optionbox"><input type="checkbox" name="remove" value="yes" checked="checked" />
		<input type="hidden" name="conv_path" value="'.getLRM(). $controller->conv_path. getLRM().'" /></td></tr>

		<tr><td class="topbottombar" colspan="2">
		<input type="button" value="'.$pgv_lang["cancel"].'" onclick="cancelDownload();" />
		<input type="submit" value="'.$pgv_lang["download_now"].'" />
		</form>';
		
		return $out;
	}

	public function getAjaxContent() {
		global $GEDCOM, $cart;
		require_once PGV_ROOT.'modules/clippings/clippings_ctrl.php';
		$controller = new ClippingsController();
		$this->clippingCtrl = $controller;
		$add = safe_GET_xref('add','');
		$add1 = safe_GET_xref('add1','');
		$remove = safe_GET('remove', PGV_REGEX_INTEGER, -1);
		$others = safe_GET('others', PGV_REGEX_ALPHANUM, '');
		$controller->level1 = safe_GET('level1');
		$controller->level2 = safe_GET('level2');
		$controller->level3 = safe_GET('level3');
		if (!empty($add)) {
			$record = GedcomRecord::getInstance($add);
			if ($record) {
				$controller->id=$record->getXref();
				$controller->type=$record->getType();
				$clipping = array ();
				$clipping['type'] = strtolower($record->getType());
				$clipping['id'] = $add;
				$clipping['gedcom'] = $GEDCOM;
				$ret = $controller->add_clipping($clipping);
				if (isset($_SESSION["cart"])) $_SESSION["cart"]=$cart;
				if ($ret) return $this->askAddOptions($record);
			}
		}
		else if (!empty($add1)) {
			$record = GedcomRecord::getInstance($add1);
			if ($record) {
				$controller->id=$record->getXref();
				$controller->type=strtolower($record->getType());
				if ($record->getType() == 'SOUR') {
					if ($others == 'linked') {
						foreach (fetch_linked_indi($record->getXref(), 'SOUR', PGV_GED_ID) as $indi) {
							if ($indi->canDisplayName()) {
								$controller->add_clipping(array('type'=>'indi', 'id'=>$indi->getXref()));
							}
						}
						foreach (fetch_linked_fam($record->getXref(), 'SOUR', PGV_GED_ID) as $fam) {
							if ($fam->canDisplayName()) {
								$controller->add_clipping(array('type'=>'fam', 'id'=>$fam->getXref()));
							}
						}
					}
				}
				if ($record->getType() == 'FAM') {
					if ($others == 'parents') {
						$parents = find_parents($record->getXref());
						if (!empty ($parents["HUSB"])) {
							$clipping = array ();
							$clipping['type'] = "indi";
							$clipping['id'] = $parents["HUSB"];
							$ret = $controller->add_clipping($clipping);
						}
						if (!empty ($parents["WIFE"])) {
							$clipping = array ();
							$clipping['type'] = "indi";
							$clipping['id'] = $parents["WIFE"];
							$ret = $controller->add_clipping($clipping);
						}
					} else
					if ($others == "members") {
						$controller->add_family_members($record->getXref());
					} else
					if ($others == "descendants") {
						$controller->add_family_descendancy($record->getXref());
					}
				} else
				if ($record->getType() == 'INDI') {
					if ($others == 'parents') {
						$famids = find_family_ids($record->getXref());
						foreach ($famids as $indexval => $famid) {
							$clipping = array ();
							$clipping['type'] = "fam";
							$clipping['id'] = $famid;
							$ret = $controller->add_clipping($clipping);
							if ($ret) {
								$controller->add_family_members($famid);
							}
						}
					} else
					if ($others == 'ancestors') {
						$controller->add_ancestors_to_cart($record->getXref(), $controller->level1);
					} else
					if ($others == 'ancestorsfamilies') {
						$controller->add_ancestors_to_cart_families($record->getXref(), $controller->level2);
					} else
					if ($others == 'members') {
						$famids = find_sfamily_ids($record->getXref());
						foreach ($famids as $indexval => $famid) {
							$clipping = array ();
							$clipping['type'] = "fam";
							$clipping['id'] = $famid;
							$ret = $controller->add_clipping($clipping);
							if ($ret)
							$controller->add_family_members($famid);
						}
					} else
					if ($others == 'descendants') {
						$famids = find_sfamily_ids($record->getXref());
						foreach ($famids as $indexval => $famid) {
							$clipping = array ();
							$clipping['type'] = "fam";
							$clipping['id'] = $famid;
							$ret = $controller->add_clipping($clipping);
							if ($ret)
							$controller->add_family_descendancy($famid, $controller->level3);
						}
					}
				}
			}
		}
		else if ($remove!=-1) {
			$ct = count($cart);
			for ($i = $remove +1; $i < $ct; $i++) {
				$cart[$i -1] = $cart[$i];
			}
			unset ($cart[$ct -1]);
		}
		else if (isset($_REQUEST['empty'])) {
			$cart = array ();
			$_SESSION["cart"] = $cart;
		}
		else if (isset($_REQUEST['download'])) {
			return $this->downloadForm();
		}
		if (isset($_SESSION["cart"])) $_SESSION["cart"]=$cart;
		return $this->getCartList();
	}

	public function hasContent() {
		return true;
	}
}
?>