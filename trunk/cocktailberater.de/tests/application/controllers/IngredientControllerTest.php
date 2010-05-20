<?php

class IngredientControllerTest extends ControllerTestCase
{

	public function testIndexAction() {
		$this->dispatch('/website/ingredient/');
		$this->assertModule("website");
		$this->assertController("ingredient");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertXpathCountMin('//table/tbody/tr',10);
	}

	public function testGetWithIdAction() {
		$this->dispatch('/website/ingredient/1');
		$this->assertModule("website");
		$this->assertController("ingredient");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('//h2', 'Wodka');
	}
	
	public function testGetWithNameAction() {
		$this->dispatch('/website/ingredient/Orangensaft');
		$this->assertModule("website");
		$this->assertController("ingredient");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('//h2', 'Orangensaft');
	}

	public function testGetWithWrongIdAction() {
		$this->dispatch('/website/ingredient/0');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Incorrect_Id'));
	}
}
