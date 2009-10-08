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
				if ($i>0 && $mod->getName()!='all_tab') $out .= $mod->getTab()->getContent();
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
