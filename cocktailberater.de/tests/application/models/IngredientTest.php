<?php

class Model_IngredientTest extends ControllerTestCase {

	public function testCreateIngredient() {
		$ingredient = new Website_Model_Ingredient();
		$ingredient->name = "Spezialzutat";
		$this->assertEquals("Spezialzutat",$ingredient->name);
	}

	public function testLoadIngredient() {
		$whiteRum = new Website_Model_Ingredient(8);
		$this->assertEquals("Rum (weiß)",$whiteRum->name);
	}

}