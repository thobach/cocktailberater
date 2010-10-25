<?php
class Models_BarTest extends ControllerTestCase {

	/**
	 * @covers Website_Model_Bar::__contruct
	 * @covers Website_Model_Bar::__get
	 * @covers Website_Model_Bar::__set
	 */
	public function testCreateBar() {
		$bar = new Website_Model_Bar();
		$bar->name = "Tabas Bar";
		$this->assertEquals("Tabas Bar",$bar->name);
	}
	
	/**
	 * @covers Website_Model_Bar::__contruct
	 * @covers Website_Model_Bar::getPartys
	 */
	public function testGetPartys() {
		// good case
		$bar = new Website_Model_Bar(1);
		$partys = $bar->getPartys();
		$this->assertEquals("tabas bar",$partys[0]->name);
		$this->assertEquals(1,count($partys));
		// case: no partys associated
		$bar = new Website_Model_Bar(3);
		$partys = $bar->getPartys();
		$this->assertEquals(0,count($partys));
	}

	/**
	 * @covers Website_Model_Bar::addIngredient
	 * @covers Website_Model_Bar::getIngredients
	 */
	public function testAddAndGetAndHasIngredient() {
		// add
		$whiteRum = new Website_Model_Ingredient(8);
		$bar = new Website_Model_Bar();
		$bar->addIngredient($whiteRum);
		$foundWhiteRum = null;
		// get ingredients
		foreach($bar->getIngredients() as $ingredient){
			if($ingredient->id == 8){
				$foundWhiteRum = $ingredient;		
			}
		}
		$this->assertNotNull($foundWhiteRum);
		// has ingredient
		$this->assertTrue($bar->hasIngredient($whiteRum));
	}
	
	/**
	 * @covers Website_Model_Bar::getPossibleRecipies
	 */
	public function testGetPossibleRecipies() {
		$whiteRum = new Website_Model_Ingredient(8);
		$limeJuice = new Website_Model_Ingredient(30);
		$sugarSyrup = new Website_Model_Ingredient(33);
		$iceCubes = new Website_Model_Ingredient(113);
		
		$bar = new Website_Model_Bar();
		$bar->addIngredient($whiteRum);
		$bar->addIngredient($limeJuice);
		$bar->addIngredient($sugarSyrup);
		$bar->addIngredient($iceCubes);
		
		$daiquiri = null;
		$recipes = $bar->getPossibleRecipies();
		foreach($recipes as $recipe){
			if($recipe->id == 29){
				$daiquiri = $recipe;
			}
		}
		$this->assertNotNull($daiquiri);
	}
	
	/**
	 * @covers Website_Model_Bar::hasIngredient
	 * @covers Website_Model_Bar::removeIngredients
	 */
	public function testRemoveIngredients() {
		// add
		$bar = new Website_Model_Bar();
		$whiteRum = new Website_Model_Ingredient(8);
		$bar->addIngredient($whiteRum);
		// has ingredient
		$this->assertTrue($bar->hasIngredient($whiteRum));
		// remove
		$bar->removeIngredients();
		// has ingredient
		$this->assertFalse($bar->hasIngredient($whiteRum));
	}

}