<?php
require_once 'includes/classes/class_tab.php';

class FamilySearch_Tab extends Tab {
	
	public function getContent() {
		global $FS_CONFG;

		include_once("modules/FamilySearch/RA_AutoMatch.php");

		$out = "<span class=\"subheaders\">FamilySearch</span><div id=\"familysearch_content\">";

		$FSAction = 'automatch';
		if (isset($_REQUEST['FSAction'])) $FSAction = $_REQUEST['FSAction'];

		//beginning of FamilySearch results functionality
		$matcher = new RA_AutoMatch();
		if ($FSAction=='login') {
			$username = '';
			$password = '';
			if (!empty($_REQUEST['username'])) {
				$username = $_REQUEST['username'];
				unset($_REQUEST['username']);
			}
			if (!empty($_REQUEST['password'])) {
				$password = $_REQUEST['password'];
				unset($_REQUEST['password']);
			}
			$matcher->authenticate($username, $password);
			unset($username);
			unset($password);
		}
		if ($matcher->isLoggedIn()) {
			try {
				$temp = $matcher->getLinkedPerson($this->controller->indi);
				$temp .= $matcher->generateResultsTable($this->controller->indi);
			}
			catch(Exception $e) {
				print "<b style=\"color:red;\">".$e->getMessage()."</b><br />";
				if (strstr($e->getMessage(), "Not Found")!==false) {
					$out = $matcher->generateResultsTable($this->controller->indi);
				}
			}
			if (!empty($matcher->XMLGed->error)) {
				if ($matcher->XMLGed->error->code==401) {
					unset($_SESSION['phpfsapi_sessionid']);
					$out .= $this->getLoginForm();
				}
				else $out .= $matcher->XMLGed->error->message; 
			}
			else $out .= $temp;
		}
		else {
			$out .= $this->getLoginForm();
		}
		$out .= "</div>";
	
		return $out;
	}

	public function getLoginForm() {
		global $FS_CONFIG;
		if (empty($FS_CONFIG['family_search_key'])) $out = "<h3>You must register for a FamilySearch API Key</h3>";
		else {
			ob_start();
			include_once("modules/FamilySearch/FSLogin.php");
			$out=ob_get_contents();
			ob_end_clean();
		}
		return $out;
	}
	
	public function hasContent() {
		return true;
	}
}
?>
