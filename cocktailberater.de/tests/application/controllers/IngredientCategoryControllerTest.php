<?php

class Controllers_IngredientCategoryControllerTest extends ControllerTestCase
{

	/**
	 * @covers Website_IngredientCategoryController::indexAction
	 */
	public function testIndexAction() {
		$this->dispatch('/website/ingredient-category/');
		$this->assertModule("website");
		$this->assertController("ingredient-category");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertXpathCountMin('//table/tbody/tr',10);
	}

	/**
	 * @covers Website_IngredientCategoryController::getAction
	 */
	public function testGetWithIdAction() {
		$this->dispatch('/website/ingredient-category/1');
		$this->assertModule("website");
		$this->assertController("ingredient-category");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('//h2', '30');
	}
	
	/**
	 * @covers Website_IngredientCategoryController::getAction
	 */
	public function testGetWithNameAction() {
		$this->dispatch('/website/ingredient-category/Sirupe');
		$this->assertModule("website");
		$this->assertController("ingredient-category");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('//h2', 'Sirupe');
	}

	/**
	 * @covers Website_IngredientCategoryController::getAction
	 */
	public function testGetWithWrongIdAction() {
		$this->dispatch('/website/ingredient-category/0');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Incorrect_Id'));
	}
}
