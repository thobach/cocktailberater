		<?php
class BarTest extends PHPUnit_Framework_TestCase {

	private static $barId;
	private static $bar2Id;
	private static $memberRandom;
	private static $member;

	public function getBarId()
	{
		return BarTest::$barId;
	}

	public function getBar2Id()
	{
		return BarTest::$bar2Id;
	}

	/**
	 * testBarAdd creates a bar with newsletter and owner
	 **/
	public function testBarAdd()
	{
		$bar = new Bar();

		$newsletterTest = new NewsletterTest();
		$newsletterTest->testNewsletterAdd();

		$memberTest = new MemberTest();
		$memberTest->testMemberAdd();
		BarTest::$memberRandom = MemberTest::getRandom();
		BarTest::$member = $memberTest;

		$bar->name = 'TestBar';
		$bar->location = 'Test Town';
		$bar->description = 'Die beste Bar der Stadt';
		$bar->country = 'DE';
		$bar->ownerId = $memberTest->getMemberId();
		$bar->newsletterId = $newsletterTest->getNewsletterId();
		$bar->save();
		BarTest::$barId = $bar->id;
	}

	/**
	 * testBar2Add creates a second bar with newsletter and owner
	 **/
	public function testBar2Add()
	{
		$bar2 = new Bar();

		$newsletterTest = new NewsletterTest();
		$newsletterTest->testNewsletterAdd();

		$memberTest = new MemberTest();
		$memberTest->testMemberAdd();

		$bar2->name = 'TestBar2';
		$bar2->location = 'Test Town2';
		$bar2->description = 'Die beste Bar der Stadt2';
		$bar2->country = 'DE';
		$bar2->ownerId = $memberTest->getMemberId();
		$bar2->newsletterId = $newsletterTest->getNewsletterId();
		$bar2->save();
		BarTest::$bar2Id = $bar2->id;
	}

	/**
	 * testBarLoad tests the behaviour of CbFactory::factory,
	 * Bar properties, getOwner method, getNewsletter method
	 * and exists method
	 **/
	public function testBarLoad()
	{
		$bar = Website_Model_CbFactory::factory('Website_Model_Bar',BarTest::$barId);
		$this->assertEquals(BarTest::$barId, $bar->id);
		$this->assertEquals('TestBar', $bar->name);
		$this->assertEquals('Test Town', $bar->location);
		$this->assertEquals('Test', $bar->getOwner()->firstname);
		$this->assertEquals('TestNewsletter', $bar->getNewsletter()->name);
		$this->assertEquals($bar->id, Bar::exists($bar->id));
		$this->assertTrue(!Bar::exists($bar->id+10));
	}

	public function testToXml(){
		$barId = $this->getBarId();
		$bar = Website_Model_CbFactory::factory('Website_Model_Bar',$barId);
		$xml = new DOMDocument("1.0");
		$rsp = $xml->createElement("rsp");
		$xml->appendChild($rsp);
		$bar->toXml($xml,$rsp);
		$member = Website_Model_CbFactory::factory('Website_Model_Member',$bar->ownerId);
		$xmlString = '<?xml version="1.0"?>
<rsp><bar id="'.$bar->id.'" name="TestBar" location="Test Town" description="Die beste Bar der Stadt" country="de" newsletterId="'.$bar->newsletterId.'" ownerId="'.$bar->ownerId.'" insertDate="'.$bar->insertDate.'" updateDate="'.$bar->updateDate.'"><owner><member id="'.$bar->ownerId.'" firstname="Test" lastname="Tester" birthday="1986-08-28" email="'.BarTest::$memberRandom.'test@test.de" hashCode="" hashExpiryDate="" apiKey="" insertDate="'.$member->insertDate.'" updateDate="'.$member->updateDate.'"/></owner></bar></rsp>';
		$this->assertXmlStringEqualsXmlString($xmlString,$xml->saveXML());
	}

	public function testListBars(){
		$bars = Bar::listBars();
		$this->assertTrue(count($bars)>=2);
	}

	public function testBarDelete($barId=NULL)
	{
		if(!$barId){
			$barId = $this->getBarId();
		}
		// TODO: delete member
		$bar = Website_Model_CbFactory::factory('Website_Model_Bar',$barId);
		$bar->delete();
		$this->setExpectedException('BarException','Id_Wrong');
		$bar = Website_Model_CbFactory::factory('Website_Model_Bar',$barId);
	}

	public function testBar2Delete($bar2Id=NULL)
	{
		if(!$bar2Id){
			$bar2Id = $this->getBar2Id();
		}
		// TODO: delete member
		$bar2 = Website_Model_CbFactory::factory('Website_Model_Bar',$bar2Id);
		$bar2->delete();
		$this->setExpectedException('BarException','Id_Wrong');
		$bar2 = Website_Model_CbFactory::factory('Website_Model_Bar',$bar2Id);
	}

}