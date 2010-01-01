<?php
class FSAPI_OAuth {
	static $instance = null;
	
	protected $serverurl;
	protected $callback;
	
	function __construct() {
		
	}

	function authenticate($username, $password, $devkey) {
	}

	static function getInstance() {
		if ($instance==null) {
			//-- load PECL module implementation
			if (class_exists('OAuth')) {
				include_once('oauth_pecl.php');	
				$instance = new FSAPI_OAuth_Pecl();	
			}
			//-- load PHP implementation
			else {
				$instance = new FSAPI_OAuth();
			}
		}
		return $instance;
	}
}


?>