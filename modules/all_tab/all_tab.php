<?php
require_once 'includes/classes/class_tab.php';

global $pgv_lang;
$pgv_lang['all_tab'] = $pgv_lang['all'];

class all_tab_Tab extends Tab {

	public function getContent() {
		
		$out = "<div id=\"all_content\">";
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
		$out .= "</div>";
		return $out;
	}

	public function hasContent() {
		return true;
	}
}
?>
