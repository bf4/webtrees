<?php
require_once('modules/media/media.php');
include_once('modules/lightbox/lb_defaultconfig.php');
if (file_exists('modules/lightbox/lb_config.php')) include_once('modules/lightbox/lb_config.php');

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class lightbox_Tab extends media_Tab {

	public function getPreLoadContent() {
		$out = '';
		ob_start();
		include_once('modules/lightbox/functions/lb_call_js.php');
		$out .= ob_get_contents();
		ob_end_clean();
		return $out; 
	}
	
	public function getJSCallbackAllTabs() {
		return 'CB_Init();';
	}
	
	public function getTabContent() {
		global $MULTI_MEDIA, $SHOW_ID_NUMBERS, $MEDIA_EXTERNAL;
		global $pgv_changes;
		global $GEDCOM, $MEDIATYPE, $pgv_changes;
		global $WORD_WRAPPED_NOTES, $MEDIA_DIRECTORY, $WT_IMAGE_DIR, $WT_IMAGES, $TEXT_DIRECTION, $is_media;
		global $cntm1, $cntm2, $cntm3, $cntm4, $t, $mgedrec ;
		global $edit ;
		global $CONTACT_EMAIL, $pid, $tabno;
		global $Fam_Navigator, $NAV_ALBUM;

		ob_start();
		$mediacnt = $this->get_media_count();
		require_once 'modules/lightbox/functions/lb_head.php';
		echo "<div id=\"lightbox2_content\">";

		$media_found = false;
		if (!$this->controller->indi->canDisplayDetails()) {
			print "<table class=\"facts_table\" cellpadding=\"0\">\n";
			print "<tr><td class=\"facts_value\">";
			print_privacy_error($CONTACT_EMAIL);
			print "</td></tr>";
			print "</table>";
		}else{
			if (file_exists("modules/lightbox/album.php")) {
				include_once('modules/lightbox/album.php');
			}
		}
		echo "</div>";

		$out .= ob_get_contents();
		ob_end_clean();
		$out .= "</div>";
		return $out;
	}

	public function hasTabContent() {
		global $MULTI_MEDIA;
		return $MULTI_MEDIA && $this->get_media_count()>0;
	}
}
?>
