<?php
require_once 'IndexControllerTest.php';
require_once 'IngredientControllerTest.php';
require_once 'IngredientCategoryControllerTest.php';
//require_once 'ManufacturerControllerTest.php';
require_once 'MemberControllerTest.php';
//require_once 'OrderControllerTest.php';
//require_once 'PartyControllerTest.php';
require_once 'RecipeControllerTest.php';
//require_once 'SessionControllerTest.php';
//require_once 'SitemapOutputControllerTest.php';
//require_once 'SitemapPerformanceControllerTest.php';

class Controllers_AllTests {

	/*public static function main() {
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}*/

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('cocktailberater.de controllers');

		$suite->addTestSuite('Controllers_IndexControllerTest');
		$suite->addTestSuite('Controllers_MemberControllerTest');
		$suite->addTestSuite('Controllers_IngredientControllerTest');
		$suite->addTestSuite('Controllers_IngredientCategoryControllerTest');
		$suite->addTestSuite('Controllers_RecipeControllerTest');
		//$suite->addTestSuite('Controllers_SessionControllerTest');
		//$suite->addTestSuite('Controllers_PartyControllerTest');
		//$suite->addTestSuite('Controllers_OrderControllerTest');
		//$suite->addTestSuite('Controllers_ManufacturerControllerTest');
		return $suite;
	}
}