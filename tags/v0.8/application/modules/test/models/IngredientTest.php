<?php
class IngredientTest extends PHPUnit_Framework_TestCase {

	private static $ingredientId;
	private static $ingredient2Id;
	private static $ingredient3Id;
	
	public function getIngredientId()
	{
		return IngredientTest::$ingredientId;
	}
	
	public function getIngredient2Id()
	{
		return IngredientTest::$ingredient2Id;
	}
	
	public function getIngredient3Id()
	{
		return IngredientTest::$ingredient3Id;
	}
	
	public function testIngredientAdd()
	{
		$ingredient = new Ingredient();
		$ingredient->name = 'Test';
		$ingredient->aliasName = 'Foo';
		$ingredient->description= 'Tester';
		$ingredient->aggregation = Ingredient::LIQUID;
		$ingredient->save();
		IngredientTest::$ingredientId = $ingredient->id;
	}
	
	public function testIngredient2Add()
	{
		$ingredient = new Ingredient();
		$ingredient->name = 'Test2';
		$ingredient->aliasName = 'Foo2';
		$ingredient->description= 'Tester2';
		$ingredient->aggregation = Ingredient::LIQUID;
		$ingredient->save();
		IngredientTest::$ingredient2Id = $ingredient->id;
	}
	
	public function testIngredient3Add()
	{
		// has no products
		$ingredient = new Ingredient();
		$ingredient->name = 'Test3';
		$ingredient->aliasName = 'Foo3';
		$ingredient->description= 'Tester3';
		$ingredient->aggregation = Ingredient::LIQUID;
		$ingredient->save();
		IngredientTest::$ingredient3Id = $ingredient->id;
	}
	
	public function testIngredientLoad()
	{
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::$ingredientId);
		$this->assertEquals(IngredientTest::$ingredientId, $ingredient->id);
		$this->assertEquals('Test', $ingredient->name);
		$ingredient2 = CbFactory::factory('Ingredient',IngredientTest::$ingredient2Id);
		$ingredient2->name="TestUpdate2";
		$ingredient2->save();
		$this->assertEquals('TestUpdate2', $ingredient2->name);
		$this->setExpectedException('IngredientException','Id_Wrong');
		$ingredientFail = CbFactory::factory('Ingredient',(IngredientTest::$ingredient2Id)+10);
	}
	
	public function testListIngredients()
	{
		$ingredients = Ingredient::listIngredients('Test',100);
		$this->assertTrue(count($ingredients)>=2);
	}
	
	public function testGetIngredientCategories()
	{
		// get Ingredient
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::$ingredientId);
		$ingredientCategories = $ingredient->getIngredientCategories();
		$this->assertEquals(null, $ingredientCategories);
		// get IngredientCategory
		$ingredientCategoryTest = new IngredientCategoryTest();
		$ingredientCategoryTest->testIngredientCategoryAdd();
		$ingredientCategory = CbFactory::factory('IngredientCategory',IngredientCategoryTest::getIngredientCategoryId());
		// attach IngredientCategory to Ingredient
		$ingredientCategories2 = $ingredient->getIngredientCategories();
		$this->assertEquals(null, $ingredientCategories2);
		$ingredient->addIngredientCategory($ingredientCategory->id);
		$ingredientCategories2 = $ingredient->getIngredientCategories();
		$this->assertEquals(1,count($ingredientCategories2));
		$ingredient->removeIngredientCategory($ingredientCategory->id);
		try {
			$ingredientCategoryTest->testIngredientCategoryDelete(IngredientCategoryTest::getIngredientCategoryId());
		} catch (IngredientCategoryException $e) {
			return;
		}
		$this->fail('Expected IngredientCategoryException was not thrown.');
	}
	
	public function testGetProducts(){
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::$ingredientId);
		$products = $ingredient->getProducts();
		$this->assertEquals(0,count($products));
		$productTest = new ProductTest();
		$productTest->testProductAdd(IngredientTest::$ingredientId);
		$product = CbFactory::factory('Product',ProductTest::getProductId());
		$this->assertEquals($product->ingredientId,IngredientTest::$ingredientId);
		$product2Test = new ProductTest();
		$product2Test->testProduct2Add(IngredientTest::$ingredientId);
		$product2 = CbFactory::factory('Product',ProductTest::getProduct2Id());
		$this->assertEquals($product2->ingredientId,IngredientTest::$ingredientId);
	}
	
	public function testGetAverageDensityGramsPerCm3(){
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::$ingredientId);
		$density = $ingredient->getAverageDensityGramsPerCm3();
		$this->assertEquals(0.925,$density);
		$ingredient3 = CbFactory::factory('Ingredient',IngredientTest::$ingredient3Id);
		$density3 = $ingredient3->getAverageDensityGramsPerCm3();
		$this->assertEquals(1,$density3);
	}

	public function testGetAverageCaloriesKcal(){
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::$ingredientId);
		$kcal = $ingredient->getAverageCaloriesKcal();
		$this->assertEquals(190,$kcal);
		$ingredient3 = CbFactory::factory('Ingredient',IngredientTest::$ingredient3Id);
		$kcal3 = $ingredient3->getAverageCaloriesKcal();
		$this->assertEquals(null,$kcal3);
	}
	
	public function testGetAverageAlcoholLevel(){
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::$ingredientId);
		$alcohol = $ingredient->getAverageAlcoholLevel();
		$this->assertEquals(37,$alcohol);
		$ingredient3 = CbFactory::factory('Ingredient',IngredientTest::$ingredient3Id);
		$alcohol3 = $ingredient3->getAverageAlcoholLevel();
		$this->assertEquals(null,$alcohol3);
	}

	public function testGetAverageWeightGram(){
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::$ingredientId);
		$weight = $ingredient->getAverageWeightGram();
		$this->assertEquals(1000,$weight);
		$ingredient3 = CbFactory::factory('Ingredient',IngredientTest::$ingredient3Id);
		$weight3 = $ingredient3->getAverageWeightGram();
		$this->assertEquals(null,$weight3);
	}
	
	public function testGetAverageVolumeLitre(){
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::$ingredientId);
		$volume = $ingredient->getAverageVolumeLitre();
		$this->assertEquals(0.7,$volume);
		$ingredient3 = CbFactory::factory('Ingredient',IngredientTest::$ingredient3Id);
		$volume3 = $ingredient3->getAverageVolumeLitre();
		$this->assertEquals(null,$volume3);
	}
	
	public function testGetMostUsedUnit(){
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::$ingredientId);
		$unit = $ingredient->getMostUsedUnit();
		$this->assertContains($unit,array('g','l'));
		$ingredient3 = CbFactory::factory('Ingredient',IngredientTest::$ingredient3Id);
		$unit3 = $ingredient3->getMostUsedUnit();
		$this->assertEquals(null,$unit3);
	}
	
	public function testToXml(){
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::$ingredientId);
		$xml = new DOMDocument("1.0");
		$rsp = $xml->createElement("rsp");
		$xml->appendChild($rsp);
		$ingredient->toXml($xml,$rsp);
		$xmlString = '<?xml version="1.0"?>
<rsp><ingredient id="'.$ingredient->id.'" name="Test" description="Tester" aggregation="liquid" aliasName="Foo" insertDate="'.$ingredient->insertDate.'" updateDate="'.$ingredient->updateDate.'"/></rsp>';
		$this->assertXmlStringEqualsXmlString($xmlString,$xml->saveXML());
	}

	public function testDeleteProducts(){
		$productTest = new ProductTest();
		$productTest->testProductDelete($productTest->getProductId(),false);
		$productTest->testProduct2Delete($productTest->getProduct2Id());
	}
	
	public function testIngredientDelete($ingredientId=NULL)
	{
		if(!$ingredientId){
			$ingredientId = $this->getIngredientId();
		}
		$ingredient = CbFactory::factory('Ingredient',$ingredientId);
		$ingredient->delete();
		$this->setExpectedException('IngredientException','Id_Wrong');
		$ingredient = CbFactory::factory('Ingredient',$ingredientId);
	}
	
	public function testIngredient2Delete($ingredient2Id=NULL)
	{
		if(!$ingredient2Id){
			$ingredient2Id = $this->getIngredient2Id();
		}
		$ingredient2 = CbFactory::factory('Ingredient',$ingredient2Id);
		$ingredient2->delete();
		$this->setExpectedException('IngredientException','Id_Wrong');
		$ingredient2 = CbFactory::factory('Ingredient',$ingredient2Id);
	}
		
	public function testIngredient3Delete($ingredient3Id=NULL)
	{
		if(!$ingredient3Id){
			$ingredient3Id = $this->getIngredient3Id();
		}
		$ingredient3 = CbFactory::factory('Ingredient',$ingredient3Id);
		$ingredient3->delete();
		$this->setExpectedException('IngredientException','Id_Wrong');
		$ingredient3 = CbFactory::factory('Ingredient',$ingredient3Id);
	}

}