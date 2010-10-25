<?php
class Models_CocktailTest extends ControllerTestCase {

	/**
	 * @covers Website_Model_Cocktail::__construct
	 * @covers Website_Model_Cocktail::__get
	 * @covers Website_Model_Cocktail::getRecipes
	 */
	public function testGetCocktail() {
		// good case
		$cocktail = new Website_Model_Cocktail(1);
		$this->assertEquals("Mojito",$cocktail->name);
		$this->assertEquals(2,count($cocktail->getRecipes()));
	}

}