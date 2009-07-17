<?php
require_once 'includes/classes/class_tab.php';
require_once './includes/classes/class_treenav.php';

class tree_Tab extends Tab {
	
	public function getContent() {
		$out = "<div id=\"tree_content\">";
		ob_start();
		$inav = new TreeNav($this->controller->pid,'treetab');
		$inav->generations = 5;
		$inav->zoomLevel = -1;
		$inav->drawViewport('treetab', "100%", "500px");
		$out .= ob_get_contents();
		ob_end_clean();
		$out .= "</div>";
		$out .= '<script type="text/javascript">
		<!--
		/* set the width to a specific value */
		var outdiv = document.getElementById("out_treetab");
		outdiv.style.width = document.getElementById("tabs").offsetWidth + "px";
		//-->
		</script>';
		return $out;
	}
	
	public function hasContent() {
		return true;
	}
} 
?>