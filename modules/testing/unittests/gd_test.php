<?php
/*
 * Tests for the required prerequisites and environment
 */

require_once(TESTING_ROOT.'include/simpletest/unit_tester.php');

class TestOfGD extends UnitTestCase {
	var $min_gd_version = 1;
	
	function setUp() {
        // this to do at the start of each test method
        
    }
    function tearDown() {
        // things to do once each test method is complete
        
    }
	function testGD() {
		$loaded = extension_loaded('gd');
		$this->skipUnless(
			$loaded,
			"GD not installed"
		);
		
		// any subsequent test that require GD to be installed
		$this->assertTrue($this->_gdVersion() > $this->min_gd_version, 'Minimum GD version satisfied');
		
	}
	
	/**
	 * Get which version of GD is installed, if any.
	 *
	 * Returns the version (1 or 2) of the GD extension.
	 */
	function _gdVersion($user_ver = 0) {
	    if (! extension_loaded('gd')) { return; }
	    static $gd_ver = 0;
	    // Just accept the specified setting if it's 1.
	    if ($user_ver == 1) { $gd_ver = 1; return 1; }
	    // Use the static variable if function was called previously.
	    if ($user_ver !=2 && $gd_ver > 0 ) { return $gd_ver; }
	    // Use the gd_info() function if possible.
	    if (function_exists('gd_info')) {
	        $ver_info = gd_info();
	        preg_match('/\d/', $ver_info['GD Version'], $match);
	        $gd_ver = $match[0];
	        return $match[0];
	    }
	    // If phpinfo() is disabled use a specified / fail-safe choice...
	    if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
	        if ($user_ver == 2) {
	            $gd_ver = 2;
	            return 2;
	        } else {
	            $gd_ver = 1;
	            return 1;
	        }
	    }
	    // ...otherwise use phpinfo().
	    ob_start();
	    phpinfo(8);
	    $info = ob_get_contents();
	    ob_end_clean();
	    $info = stristr($info, 'gd version');
	    preg_match('/\d/', $info, $match);
	    $gd_ver = $match[0];
	    return $match[0];
	} // End gdVersion()
}

?>
