<?php
	/*
	 * A test suite for running all PhpGedView Tests
	 */
	
	chdir("../");
	define('RUNNER', true);
	require_once("config.php");
    require_once('simpletest/unit_tester.php');
    require_once('simpletest/reporter.php');

    // Create the test Group and add tests to it
    $test = &new TestSuite('All PhpGedView Tests');
    $test->addTestFile(dirname(__FILE__) . '/backend_test.php');
    $test->addTestFile(dirname(__FILE__) . '/gd_test.php');
    
    $test->addTestFile(dirname(__FILE__) . '/person_test.php');
    
    // run the test suite and create report
    $test->run(new HtmlReporter());
?>