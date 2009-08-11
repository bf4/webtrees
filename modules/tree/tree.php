<?php
require_once 'includes/classes/class_tab.php';
require_once './includes/classes/class_treenav.php';

class tree_Tab extends Tab {

	public function getJSCallback() {
		return 'treetab.sizeLines(); 
var outdiv = document.getElementById("out_treetab");
var parent = document.getElementById("subtab");
if (!parent) parent = document.getElementById("tabs");
outdiv.style.width = (parent.offsetWidth-30) + "px";';
	}
	
	public function getContent() {
		$out = "<div id=\"tree_content\">";
		ob_start();
		$inav = new TreeNav($this->controller->pid,'treetab');
		$inav->generations = 5;
		$inav->zoomLevel = -1;
		$inav->drawViewport('treetab', "500px", "auto");
		$out .= ob_get_contents();
		ob_end_clean();
		$out .= "</div>";
		return $out;
	}
	
	public function hasContent() {
		return true;
	}
} 
?>
