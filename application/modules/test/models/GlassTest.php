<?php
class GlassTest extends PHPUnit_Framework_TestCase {

	private static $glassId;
	
	public function getGlassId()
	{
		return GlassTest::$glassId;
	}
	
	public function testGlassAdd()
	{
		$glass = new Glass();
		// TODO: test also the pic in the glass object
		/*$photoTest = new PhotoTest();
		$photoTest->testPhotoAdd();
		$glass->photoId = $photoTest->getPhotoId();*/
		$glass->name = 'TestGlass';
		$glass->volumeMl = 200;
		$glass->save();
		GlassTest::$glassId = $glass->id;
	}
	
	
	public function testGlassLoad()
	{
		$glass = Website_Model_CbFactory::factory('Website_Model_Glass'GlassTest::$glassId);
		$this->assertEquals(GlassTest::$glassId, $glass->id);
		$this->assertEquals('TestGlass', $glass->name);
		//$this->assertEquals('TestPhoto',$glass->getPhoto()->name);
	}
	
	public function testListGlasses(){
		$glasses = Glass::listGlasses();
		$this->assertTrue(count($glasses)>=1);
	}
	
	public function testToXml(){
		$glass = Website_Model_CbFactory::factory('Website_Model_Glass'GlassTest::$glassId);
		$xml = new DOMDocument("1.0");
		$rsp = $xml->createElement("rsp");
		$xml->appendChild($rsp);
		$glass->toXml($xml,$rsp);
		$config = Zend_Registry::get('config');
		$xmlString = '<?xml version="1.0"?>
<rsp><glass id="'.$glass->id.'" name="TestGlass" description="" volumeMl="200"><photo id="" name="" description="" url="http://'.$_SERVER[SERVER_NAME].$config->paths->picture_path.$glass->getPhoto()->getPhotoCategory()->folder.'/" originalFileName="" photoCategory=""/></glass></rsp>';
		$this->assertXmlStringEqualsXmlString($xmlString,$xml->saveXML());
	}
	
	public function testGlassDelete($glassId=NULL)
	{
		if(!$glassId){
			$glassId = $this->getGlassId();
		}
		$glass = Website_Model_CbFactory::factory('Website_Model_Glass'$glassId);
		$glass->delete();
		$this->setExpectedException('GlassException','Id_Wrong');
		$glass = Website_Model_CbFactory::factory('Website_Model_Glass'$glassId);
	}
	

}