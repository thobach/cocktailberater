<?php

class Controllers_ProductControllerTest extends ControllerTestCase
{

	/**
	 * @covers Website_ProductController::indexAction
	 */
	public function testIndexAction() {
		$this->dispatch('/website/product/');
		$this->assertModule("website");
		$this->assertController("product");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertXpathCountMin('//table/tbody/tr',10);
	}

	/**
	 * @covers Website_ProductController::getAction
	 */
	public function testGetWithIdAction() {
		$this->dispatch('/website/product/5');
		$this->assertModule("website");
		$this->assertController("product");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('//h2', 'Orange');
	}
	
	/**
	 * @covers Website_ProductController::getAction
	 */
	public function testGetWithNameAction() {
		$this->dispatch('/website/product/5_albi_Orange');
		$this->assertModule("website");
		$this->assertController("product");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('//h2', 'Orange');
	}

	/**
	 * @covers Website_ProductController::getAction
	 */
	public function testGetWithWrongIdAction() {
		$this->dispatch('/website/product/0');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Incorrect_Id'));
	}
}
