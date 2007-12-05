<?php
	/*
	 * A test suite for running all PhpGedView Tests
	 * The logged in user must be a PGV admin
	 */

	define('RUNNER', true);
	define('TESTING_MOD_NAME', basename(dirname(__FILE__))); define('TESTING_ROOT', 'modules/'.TESTING_MOD_NAME.'/');

	if (!userIsAdmin(getUserName())) {
		header("Location: login.php?url=module.php?mod=".TESTING_MOD_NAME);
		exit;
	}
	
	require_once(TESTING_ROOT.'include/simpletest/unit_tester.php');
	require_once(TESTING_ROOT.'include/simpletest/reporter.php');

    // Create the test Group and add tests to it
    $test = &new TestSuite('All PhpGedView Tests');
    $test->addTestFile(dirname(__FILE__) . '/unittests/backend_test.php');
    $test->addTestFile(dirname(__FILE__) . '/unittests/gd_test.php');
    
    $test->addTestFile(dirname(__FILE__) . '/unittests/person_test.php');
    $test->addTestFile(dirname(__FILE__) . '/unittests/media_test.php');
    
    // run the test suite and create report
    $test->run(new HtmlReporter());
?>