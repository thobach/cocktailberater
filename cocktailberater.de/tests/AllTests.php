<?php
require_once 'application/controllers/AllTests.php';
require_once 'application/models/AllTests.php';

class AllTests
{
	/**
	 * Complete test suite for unit tests as well as integration and 
	 * performance tests
	 *
	 * @requirement needs more than 64M memory_limit
	 */
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('cocktailberater.de');
		$suite->addTest(Models_AllTests::suite());
		$suite->addTest(Controllers_AllTests::suite());
		return $suite;
	}
}
?>