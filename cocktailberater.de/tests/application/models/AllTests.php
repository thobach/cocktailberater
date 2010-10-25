<?php
require_once 'IngredientTest.php';
require_once 'MemberTest.php';
require_once 'BarTest.php';
require_once 'StatisticTest.php';
require_once 'CocktailTest.php';

class Models_AllTests {

	/**
	 * Unit test suite
	 */
	public static function suite() {
		// test suite
		$suite = new PHPUnit_Framework_TestSuite('cocktailberater.de models');
		// tests
		$suite->addTestSuite('Models_IngredientTest');
		$suite->addTestSuite('Models_MemberTest');
		// $suite->addTestSuite('Models_BarTest'); somehow stopps test, just added covers statement
		$suite->addTestSuite('Models_StatisticTest');
		$suite->addTestSuite('Models_CocktailTest');
		return $suite;
	}
}