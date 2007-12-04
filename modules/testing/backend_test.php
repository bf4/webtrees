<?php
/*
 * Tests for the required prerequisites and environment
 */

require_once(TESTING_ROOT.'include/simpletest/unit_tester.php');

class BackendTests extends UnitTestCase {
	var $backends_available = 0;
	
	function setUp() {
        // this to do at the start of each test method
        
    }
    function tearDown() {
        // things to do once each test method is complete
        
    }
	function testMySQL() {
		$loaded = extension_loaded('mysql');
		if ($loaded) {
			$this->backends_available ++;
		}
	}
	function testMicrosoftSQL() {
		$loaded = extension_loaded('mssql');
		if ($loaded) {
			$this->backends_available ++;
		}
	}
	function testMySQLi() {
		$loaded = extension_loaded('mysqli');
		if ($loaded) {
			$this->backends_available ++;
		}
	}
	function testPostgreSQL() {
		$loaded = extension_loaded('pgsql');
		if ($loaded) {
			$this->backends_available ++;
		}
	}
	function testSQLite() {
		$loaded = extension_loaded('sqlite');
		if ($loaded) {
			$this->backends_available ++;
		}
	}
	/*
	function testMiniSQL() {
		$loaded = extension_loaded('msql');
		if ($loaded) {
			$this->backends_available ++;
		}
	}
	function testSybase() {
		$loaded = extension_loaded('sybase');
		if ($loaded) {
			$this->backends_available ++;
		}
	}
	function testOracle() {
		$loaded = extension_loaded('oci8');
		if ($loaded) {
			$this->backends_available ++;
		}
	}
	*/
	
	function testBackend() {
		$this->assertTrue($this->backends_available >= 1, 'At least 1 suitable DB backend available');
	}
}

?>
