<?php
class CocktailTest extends PHPUnit_Framework_TestCase {

	private static $cocktailId;
	private static $cocktail2Id;
	
	public function getCocktailId()
	{
		return CocktailTest::$cocktailId;
	}
	
	public function getCocktail2Id()
	{
		return CocktailTest::$cocktail2Id;
	}
	
	/**
	 * testCocktailAdd creates a cocktail
	 **/
	public function testCocktailAdd()
	{
		$cocktail = new Cocktail();
		$cocktail->name = 'TestCocktail';
		$cocktail->save();
		CocktailTest::$cocktailId = $cocktail->id;
	}
	
	/**
	 * testCocktailAddSameName creates a cocktail with the same name
	 **/
	public function testCocktailAddSameName()
	{
		$cocktail = new Cocktail();
		$cocktail->name = 'TestCocktail';
		$cocktail->save();
		CocktailTest::$cocktail2Id = $cocktail->id;
		$this->assertEquals( CocktailTest::$cocktailId, CocktailTest::$cocktail2Id);
	}
	
	
	public function testCocktailLoad()
	{
		$cocktail = CbFactory::factory('Cocktail',CocktailTest::$cocktailId);
		$this->assertEquals(CocktailTest::$cocktailId, $cocktail->id);
		$this->assertEquals('TestCocktail', $cocktail->name);
	}
	
	/**
	 * testCocktailUpdate updates a cocktail
	 **/
	public function testCocktailUpdate()
	{
		$cocktail = CbFactory::factory('Cocktail',CocktailTest::$cocktailId);
		$cocktail->name = 'TestCocktail2';
		$cocktail->save();
	}
	
	public function testCocktailLoad2()
	{
		$cocktail = CbFactory::factory('Cocktail',CocktailTest::$cocktailId);
		$this->assertEquals(CocktailTest::$cocktailId, $cocktail->id);
		$this->assertEquals('TestCocktail2', $cocktail->name);
	}
	
	public function testToXml(){
		$cocktailId = $this->getCocktailId();
		$cocktail = CbFactory::factory('Cocktail',$cocktailId);
		$xml = new DOMDocument("1.0");
		$rsp = $xml->createElement("rsp");
		$xml->appendChild($rsp);
		$cocktail->toXml($xml,$rsp);
		$xmlString = '<?xml version="1.0"?>
<rsp><cocktail id="'.$cocktail->id.'" name="TestCocktail2" insertDate="'.$cocktail->insertDate.'" updateDate="'.$cocktail->updateDate.'"><recipes/></cocktail></rsp>';
		//print $xmlString;
		//print $xml->saveXML();
		$this->assertXmlStringEqualsXmlString($xmlString,$xml->saveXML());
	}

	
	public function testCocktailDelete()
	{
		$cocktail = CbFactory::factory('Cocktail',CocktailTest::$cocktailId);
		$cocktail->delete();
		$this->setExpectedException('CocktailException','Id_Wrong');
		$cocktail = CbFactory::factory('Cocktail',CocktailTest::$cocktailId);
	}
	

}