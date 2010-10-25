<?php
class Models_IngredientTest extends ControllerTestCase {

	/**
	 * @covers Website_Model_Ingredient::__construct
	 * @covers Website_Model_Ingredient::__get
	 * @covers Website_Model_Ingredient::__set
	 */
	public function testCreateIngredient() {
		$ingredient = new Website_Model_Ingredient();
		$ingredient->name = "Spezialzutat";
		$this->assertEquals("Spezialzutat",$ingredient->name);
	}

	/**
	 * @covers Website_Model_Ingredient::__construct
	 * @covers Website_Model_Ingredient::__get
	 */
	public function testLoadIngredient() {
		$whiteRum = new Website_Model_Ingredient(8);
		$this->assertEquals("Rum (weiß)",$whiteRum->name);
	}

}