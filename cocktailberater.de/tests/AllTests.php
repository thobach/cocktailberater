<?php
require_once 'application/controllers/AllTests.php';
require_once 'application/models/AllTests.php';

class AllTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('cocktailberater.de');
		$suite->addTest(Models_AllTests::suite());
		$suite->addTest(Controllers_AllTests::suite());
		return $suite;
	}
}
?>