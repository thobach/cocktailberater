<?php
require_once 'IngredientTest.php';
require_once 'MemberTest.php';
require_once 'BarTest.php';
require_once 'StatisticTest.php';

class Models_AllTests {

	public static function main() {
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('cocktailberater.de models');

		$suite->addTestSuite('Models_IngredientTest');
		$suite->addTestSuite('Models_MemberTest');
		$suite->addTestSuite('Models_BarTest');
		$suite->addTestSuite('Models_StatisticTest');

		return $suite;
	}
}