<?php
class FSAPI_OAuth_Pecl extends FSAPI_OAuth {
	
	protected $oauth = null;

	function authenticate($username, $password, $devkey) {
		global $FS_CONFIG;
		
		if ($this->oauth==null) {
			$this->oauth = new OAuth($devkey);
		}
	}
	
}