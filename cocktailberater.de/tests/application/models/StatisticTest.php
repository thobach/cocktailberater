<?php
class Models_StatisticTest extends ControllerTestCase {

	public function testAddView() {
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_COCKTAIL,1,
		Website_Model_Statistic::FORMAT_HTML);
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_INGREDIENT,1,
		Website_Model_Statistic::FORMAT_HTML);
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_MANUFACTURER,2,
		Website_Model_Statistic::FORMAT_HTML);
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_PRODUCT,1,
		Website_Model_Statistic::FORMAT_HTML);
		
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_RECIPE,1,
		Website_Model_Statistic::FORMAT_HTML);
		// get previous count
		$currentViewCount = $statistic->views;
		// increase counter
		$statistic->addView();
		// assert increased counter
		$this->assertEquals($currentViewCount+1,$statistic->views);
	}

	public function testStatisticsByRecipeId() {
		$statistic = Website_Model_Statistic::statisticsByRecipeId(1);
		$this->assertGreaterThan(0,$statistic[0]->views);
	}

	public function testGetResource() {
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_RECIPE,1,
		Website_Model_Statistic::FORMAT_HTML);
		$resource = $statistic->getResource();
		$this->assertType('Website_Model_Recipe',$resource);
	}

	public function testSetProperty() {
		$this->setExpectedException('Website_Model_StatisticException');
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_RECIPE,1,
		Website_Model_Statistic::FORMAT_HTML);
		$statistic->views=3;
	}

	public function testGetProperty() {
		$this->setExpectedException('Website_Model_StatisticException');
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_RECIPE,1,
		Website_Model_Statistic::FORMAT_HTML);
		$statistic->wrongField;
	}

	public function testList() {
		$list = Website_Model_Statistic::listStatistics();
		$this->assertType('Website_Model_Statistic',$list[0]);
	}
	
	public function testSmallId() {
		$list = Website_Model_Statistic::listStatistics();
		$statistic = new Website_Model_Statistic($list[0]->id);
		$this->assertGreaterThan(0,$statistic->id);
	}

	public function testDelete() {
		// create
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_RECIPE,1,
		Website_Model_Statistic::FORMAT_HTML);
		$this->assertGreaterThan(0,Website_Model_Statistic::exists(
		Website_Model_Statistic::RESOURCE_RECIPE,1,
		Website_Model_Statistic::FORMAT_HTML));
		// delete
		$statistic->delete();
		$this->assertFalse(Website_Model_Statistic::exists(
		Website_Model_Statistic::RESOURCE_RECIPE,1,
		Website_Model_Statistic::FORMAT_HTML));
	}

	public function testWrongIdBig() {
		$this->setExpectedException('Website_Model_StatisticException');
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_RECIPE,9999,
		Website_Model_Statistic::FORMAT_HTML);
	}

	public function testWrongIdSmall() {
		$this->setExpectedException('Website_Model_StatisticException');
		$statistic = new Website_Model_Statistic(9999);
	}

	public function testWrongFormat() {
		$this->setExpectedException('Website_Model_StatisticException');
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_RECIPE,1,'wrongformat');
	}

	public function testFormatMissing() {
		$this->setExpectedException('Website_Model_StatisticException');
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_RECIPE,1);
	}

	public function testWrongResource() {
		$this->setExpectedException('Website_Model_StatisticException');
		$statistic = new Website_Model_Statistic('wrongResource',1,
		Website_Model_Statistic::FORMAT_HTML);
	}

	public function testIdMissing() {
		$this->setExpectedException('Website_Model_StatisticException');
		$statistic = new Website_Model_Statistic('wrongResource',NULL,
		Website_Model_Statistic::FORMAT_HTML);
	}

	public function testResourceMissing() {
		$this->setExpectedException('Website_Model_StatisticException');
		$statistic = new Website_Model_Statistic(NULL,1,
		Website_Model_Statistic::FORMAT_HTML);
	}

	public function testFormatAndIdMissing() {
		$this->setExpectedException('Website_Model_StatisticException');
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_RECIPE);
	}

	public function testToXml() {
		$statistic = new Website_Model_Statistic(
		Website_Model_Statistic::RESOURCE_RECIPE,1,
		Website_Model_Statistic::FORMAT_HTML);
		// create a dom object
		$xml = new DOMDocument("1.0");
		// create root element 'rsp'
		$rsp = $xml->createElement("rsp");
		// add the 'rsp' element to the xml document
		$xml->appendChild($rsp);
		$statistic->toXml($xml,$rsp);
	}

}