<?php
require_once 'includes/classes/class_tab.php';

global $pgv_lang;
$pgv_lang['all_tab'] = $pgv_lang['all'];

class all_tab_Tab extends Tab {
	
	public function getJSCallback() {
		$out = 'if (selectedTab=="all_tab") {
		';
		$i = 0;
		foreach($this->controller->modules as $mod) {
			if ($mod->hasTab()) {
				if ($i>0 && $mod->getName()!='all_tab' && $mod->getTab()->canLoadAjax()) {
					$out .= 'if (!tabCache["'.$mod->getName().'"]) {
						jQuery("#'.$mod->getName().'").load("individual.php?action=ajax&module='.$mod->getName().'&pid='.$this->controller->pid.'");
						tabCache["'.$mod->getName().'"] = true;
					}';
				}
				$i++;
			}
		}
		
		$out .= '
			jQuery("#tabs > div").each(function() { 
				if (this.name!="all_tab") {
					jQuery(this).removeClass("ui-tabs-hide");
				}
			});
			}
		';
		return $out;
	}
	
	public function canLoadAjax() { return false; }
	
	public function getContent() {
		
		$out = "<div id=\"all_content\">";
		$out .= "<!-- all tab doesn't have it's own content -->";
		/*
		$i = 0;
		foreach($this->controller->modules as $mod) {
			if ($mod->hasTab()) {
				if ($i>0 && $mod->getName()!='all_tab') {
					if ($mod->getName()!="tree" || $mod->getName()!="googlemap") $out .= preg_replace("/(id|for)=\"(\w+)\"/", "$1=\"all_$2\"", $mod->getTab()->getContent());
					else $out .= preg_replace("/(id|for)=\"(\w+)\"/", "$1=\"all_$2\"", $mod->getTab()->getContent());
				}
				$i++;
			}
		}
		*/
		$out .= "</div>";
		return $out;
	}

	public function hasContent() {
		return true;
	}
}
?>
