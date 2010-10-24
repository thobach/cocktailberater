<?php
class Models_CocktailTest extends ControllerTestCase {

	public function testGetCocktail() {
		// good case
		$cocktail = new Website_Model_Cocktail(1);
		$this->assertEquals("Mojito",$cocktail->name);
		$this->assertEquals(2,count($cocktail->getRecipes()));
	}

}