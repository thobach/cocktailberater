<?php
class IngredientCategoryTest extends PHPUnit_Framework_TestCase {

	private static $ingredientCategoryId;
	
	public function getIngredientCategoryId()
	{
		return IngredientCategoryTest::$ingredientCategoryId;
	}
	
	public function testIngredientCategoryAdd()
	{
		$ingredientCategory = new IngredientCategory();
		$ingredientCategory->name = 'TestIngredientCategory';
		$ingredientCategory->save();
		IngredientCategoryTest::$ingredientCategoryId = $ingredientCategory->id;
	}
	
	
	public function testIngredientCategoryLoad()
	{
		$ingredientCategory = CbFactory::factory('IngredientCategory',IngredientCategoryTest::$ingredientCategoryId);
		$this->assertEquals(IngredientCategoryTest::$ingredientCategoryId, $ingredientCategory->id);
		$this->assertEquals('TestIngredientCategory', $ingredientCategory->name);
	}
	
	public function testGetIngredientCategories(){
		$ingredientCategories = IngredientCategory::getIngredientCategories();
		$this->assertTrue(count($ingredientCategories)>=1);
	}
	
	public function testCategoriesByIngredientId(){
		$ingredientTest = new IngredientTest();
		$ingredientTest->testIngredientAdd();
		$ingredient = CbFactory::factory('Ingredient',$ingredientTest->getIngredientId());
		$ingredient->addIngredientCategory(IngredientCategoryTest::$ingredientCategoryId);
		$ingredientCategories = IngredientCategory::categoriesByIngredientId($ingredientTest->getIngredientId());
		$this->assertTrue(count($ingredientCategories)>=1);
		try{
			$ingredientTest->testIngredientDelete($ingredientTest->getIngredientId());
		} catch (IngredientException $e) {
			$ingredient->removeIngredientCategory(IngredientCategoryTest::$ingredientCategoryId);
			return;
		}
		$this->fail('Expected IngredientException was not thrown.');
	}
	
	// TODO: getIngredientsByCategory
	
	public function testToXml(){
		$ingredientCategory = CbFactory::factory('IngredientCategory',IngredientCategoryTest::$ingredientCategoryId);
		$xml = new DOMDocument("1.0");
		$rsp = $xml->createElement("rsp");
		$xml->appendChild($rsp);
		$ingredientCategory->toXml($xml,$rsp);
		$xmlString = '<?xml version="1.0"?>
<rsp><ingredientCategory id="'.$ingredientCategory->id.'" name="TestIngredientCategory"/></rsp>';
		$this->assertXmlStringEqualsXmlString($xmlString,$xml->saveXML());
	}
	
	public function testIngredientCategoryDelete($ingredientCategoryId=NULL)
	{
		if(!$ingredientCategoryId){
			$ingredientCategoryId = $this->getIngredientCategoryId();
		}
		$ingredientCategory = CbFactory::factory('IngredientCategory',$ingredientCategoryId);
		$ingredientCategory->delete();
		$this->setExpectedException('IngredientCategoryException','Id_Wrong');
		$ingredientCategory = CbFactory::factory('IngredientCategory',$ingredientCategoryId);
	}
	

}