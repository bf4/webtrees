<script language="JavaScript" type="text/javascript">
  function ilinkitem(mediaid, type) {
		window.open('inverselink.php?mediaid='+mediaid+'&linkto='+type+'&'+sessionname+'='+sessionid, '_blank', 'top=50,left=50,width=400,height=300,resizable=1,scrollbars=1');
		return false;
	}
</script>
<?php
	// Set Link
	/**
	 * Generate link flyout menu
	 *
	 * @param string $mediaid
	 */
//	function print_link_menu2($mediaid) {
        $mediaid=$media["XREF"];
		global $pgv_lang, $TEXT_DIRECTION;

		$classSuffix = "";
		if ($TEXT_DIRECTION=="rtl") $classSuffix = "_rtl";

		// main link displayed on page
		$menu = array();
		$menu["label"] = $pgv_lang["set_link"];
		$menu["link"] = "#";
		$menu["onclick"] = "return ilinkitem('$mediaid','person')";
//		$menu["class"] = "thememenuitem";
		$menu["class"] = "";
		$menu["hoverclass"] = "";
		$menu["submenuclass"] = "submenu";
		$menu["flyout"] = "left";
		$menu["items"] = array();

		$submenu = array();
		$submenu["label"] = $pgv_lang["to_person"];
		$submenu["link"] = "#";
		$submenu["class"] = "submenuitem".$classSuffix;
		$submenu["hoverclass"] = "submenuitem".$classSuffix;
		$submenu["onclick"] = "return ilinkitem('$mediaid','person')";
		$menu["items"][] = $submenu;

		$submenu = array();
		$submenu["label"] = $pgv_lang["to_family"];
		$submenu["link"] = "#";
		$submenu["class"] = "submenuitem".$classSuffix;
		$submenu["hoverclass"] = "submenuitem".$classSuffix;
		$submenu["onclick"] = "return ilinkitem('$mediaid','family')";
		$menu["items"][] = $submenu;

		$submenu = array();
		$submenu["label"] = $pgv_lang["to_source"];
		$submenu["link"] = "#";
		$submenu["class"] = "submenuitem".$classSuffix;
		$submenu["hoverclass"] = "submenuitem".$classSuffix;
		$submenu["onclick"] = "return ilinkitem('$mediaid','source')";
		$menu["items"][] = $submenu;

		print_menu($menu);
		
//	}			
			
	// Only set link on media that is in the DB
//	if ($media["XREF"] != "") {
//		print_link_menu($media2["XREF"]);  
//	}

?>