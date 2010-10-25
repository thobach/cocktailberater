<?php

class Controllers_ManufacturerControllerTest extends ControllerTestCase
{

	/**
	 * @covers Website_ManufacturerController::indexAction
	 */
	public function testIndexAction() {
		$this->dispatch('/website/manufacturer/');
		$this->assertModule("website");
		$this->assertController("manufacturer");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertXpathCountMin('//table/tbody/tr',10);
	}

	/**
	 * @covers Website_ManufacturerController::getAction
	 */
	public function testGetWithIdAction() {
		$this->dispatch('/website/manufacturer/6');
		$this->assertModule("website");
		$this->assertController("manufacturer");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('//h2', 'Bols');
	}
	
	/**
	 * @covers Website_ManufacturerController::getAction
	 */
	public function testGetWithNameAction() {
		$this->dispatch('/website/manufacturer/Bols');
		$this->assertModule("website");
		$this->assertController("manufacturer");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('//h2', 'Bols');
	}

	/**
	 * @covers Website_ManufacturerController::getAction
	 */
	public function testGetWithWrongIdAction() {
		$this->dispatch('/website/manufacturer/0');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Incorrect_Id'));
	}
}
