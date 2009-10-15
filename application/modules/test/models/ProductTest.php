<?php
class ProductTest extends PHPUnit_Framework_TestCase {

	private static $productId;
	private static $product2Id;
	
	public function getProductId()
	{
		return ProductTest::$productId;
	}
	
	public function getProduct2Id()
	{
		return ProductTest::$product2Id;
	}
	
	public function testProductAdd($ingredientId=null)
	{
		$product = new Product();
		if($ingredientId){
			$product->ingredientId  = $ingredientId;
		} else {
			$ingredientTest = new IngredientTest();
			$ingredientTest->testIngredientAdd();
			$product->ingredientId = IngredientTest::getIngredientId();
		}
		$product->name = 'TestProduct';
		$product->manufacturerId  = null;
		$product->size  = 0.7;
		$product->unit  = Component::LITRE;
		$product->alcoholLevel  = 38;
		$product->caloriesKcal  = 200;
		$product->densityGramsPerCm3  = 0.95;
		$product->fruitConcentration  = null;
		$product->color = 'transparent';
		$product->save();
		ProductTest::$productId = $product->id;
		$this->assertEquals(null,$product->getManufacturer());
		$ingredient = $product->getIngredient();
		$this->assertEquals(IngredientTest::getIngredientId(),$ingredient->id);
	}
	
	public function testProduct2Add($ingredientId=null)
	{
		$product = new Product();
		if($ingredientId){
			$product->ingredientId  = $ingredientId;
		} else {
			$product->ingredientId = IngredientTest::getIngredientId();
		}
		$product->name = 'TestProduct2';
		$product->manufacturerId  = null;
		$product->size  = 1000;
		$product->unit  = Component::GRAM;
		$product->alcoholLevel  = 35;
		$product->caloriesKcal  = 180;
		$product->densityGramsPerCm3  = 0.9;
		$product->fruitConcentration  = null;
		$product->color = 'transparent';
		$product->save();
		ProductTest::$product2Id = $product->id;
		$this->assertEquals(null,$product->getManufacturer());
		$ingredient = $product->getIngredient();
		$this->assertEquals(IngredientTest::getIngredientId(),$ingredient->id);
	}

	public function testProductLoad()
	{
		$product = CbFactory::factory('Product',ProductTest::$productId);
		$this->assertEquals(ProductTest::$productId, $product->id);
		$this->assertEquals('TestProduct', $product->name);
	}
	
	public function testProduct2Load()
	{
		$product = CbFactory::factory('Product',ProductTest::$product2Id);
		$this->assertEquals(ProductTest::$product2Id, $product->id);
		$this->assertEquals('TestProduct2', $product->name);
	}
	
	public function testProductsByIngredientId()
	{
		$products = Product::productsByIngredientId(IngredientTest::getIngredientId());
		$this->assertTrue(count($products)==2);
	}
	
	public function testListProducts(){
		$products = Product::listProduct();
		$this->assertTrue(count($products)>=2);
	}
	
	public function testToXml(){
		$product = CbFactory::factory('Product',ProductTest::$productId);
		$ingredient = CbFactory::factory('Ingredient',IngredientTest::getIngredientId());
		$xml = new DOMDocument("1.0");
		$rsp = $xml->createElement("rsp");
		$xml->appendChild($rsp);
		$product->toXml($xml,$rsp);
		//print $xml->saveXML();
		//print $xmlString;
		$xmlString = '<?xml version="1.0"?>
<rsp><product id="'.$product->id.'" name="TestProduct" size="0.7" unit="l" alcoholLevel="38" caloriesKcal="200" densityGramsPerCm3="0.95" fruitConcentration="" color="transparent" insertDate="'.$product->insertDate.'" updateDate="'.$product->updateDate.'"><ingredients><ingredient id="'.$ingredient->id.'" name="Test" description="Tester" aggregation="liquid" aliasName="Foo" insertDate="'.$ingredient->insertDate.'" updateDate="'.$ingredient->updateDate.'"/></ingredients></product></rsp>';
		$this->assertXmlStringEqualsXmlString($xmlString,$xml->saveXML());
	}

	public function testProductDelete($productId=NULL,$ingredientToo=true)
	{
		if(!$productId){
			$productId = $this->getProductId();
		}
		$product = CbFactory::factory('Product',$productId);
		$product->delete();
		try{
			$product = CbFactory::factory('Product',$productId);
		} catch (ProductException $e) {
			return;
		}
		$this->fail('Expected ProductException has not been thrown');
	}
	
	public function testProduct2Delete($productId=NULL)
	{
		if(!$productId){
			$productId = $this->getProduct2Id();
		}
		$product = CbFactory::factory('Product',$productId);
		$product->delete();
		try{
			$product = CbFactory::factory('Product',$productId);
		} catch (ProductException $e) {
			if($ingredientToo){
				$ingredientTest = new IngredientTest();
				$ingredientTest->testIngredientDelete(IngredientTest::getIngredientId());
			}
			return;
		}
		$this->fail('Expected ProductException has not been thrown');
	}	
}