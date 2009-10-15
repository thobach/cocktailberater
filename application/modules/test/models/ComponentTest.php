<?php
/**
 * Component Test can only be used together with Recipe Test
 *
 */
class ComponentTest extends PHPUnit_Framework_TestCase {

	private static $ingredientId;
	private static $recipeId;
	
	public function getIngredientId()
	{
		return ComponentTest::$ingredientId;
	}
	
	public function getRecipeId()
	{
		return ComponentTest::$recipeId;
	}
	
	public function testComponentAdd()
	{
		$component = new Component();
		
		// create an ingredient
		$ingredientTest = new IngredientTest();
		$ingredientTest->testIngredientAdd();
		 
		// set ingredientId
		$component->ingredientId = $ingredientTest->getIngredientId();
		
		// create a recipe
		$recipeTest = new RecipeTest();
		$recipeTest->testRecipeAdd();
		 
		// set recipeId
		$component->recipeId = $recipeTest->getRecipeId();
		
		$component->amount = 2;
		$component->unit = Component::CENTILITRE;
		$component->save();
		ComponentTest::$ingredientId = $component->ingredientId;
		ComponentTest::$recipeId = $component->recipeId;
		
		// add component
		//$recipe->addComponent(CbFactory::factory('Component',ComponentTest::getIngredientId(),ComponentTest::getRecipeId()));
	}
	
	public function testComponentLoad()
	{
		$component = CbFactory::factory('Component',ComponentTest::getIngredientId(),ComponentTest::getRecipeId());
		$this->assertEquals(ComponentTest::getIngredientId(), $component->ingredientId);
		$this->assertEquals('2', $component->amount);
		$this->assertEquals(RecipeTest::getRecipeId(), $component->recipeId);
		$this->assertTrue(!Component::exists($component->ingredientId+10,$component->recipeId+10));
	}
	
	public function testComponentsByRecipeId(){
		$components = Component::componentsByRecipeId($this->getRecipeId());
		$this->assertEquals($components[0]->ingredientId, $this->getIngredientId());
	}
	
	public function testComponentsByIngredientId(){
		$components = Component::componentsByIngredientId($this->getIngredientId());
		$this->assertEquals($components[0]->recipeId, $this->getRecipeId());
	}
	
	public function testGetUnits(){
		$units = Component::getUnits();
		$this->assertTrue(count($units)==8);
		$this->assertTrue($units[4]=='kg');
	}
	
	public function testGetAmountInLiter(){
		$component = CbFactory::factory('Component',ComponentTest::getIngredientId(),ComponentTest::getRecipeId());
		$this->assertEquals('0.02', $component->getAmountInLiter());
		$component->unit = Component::LITRE;
		$this->assertEquals('2', $component->getAmountInLiter());
		$component->unit = Component::MILLILITRE;
		$this->assertEquals('0.002', $component->getAmountInLiter());
		$component->unit = Component::GRAM;
		$this->assertEquals('0.002', $component->getAmountInLiter());
		$component->unit = Component::KILOGRAM;
		$this->assertEquals('2', $component->getAmountInLiter());
		$component->unit = Component::PIECE;
		$this->assertEquals(null, $component->getAmountInLiter());
		$component->unit = Component::TEASPOON;
		$this->assertEquals('0.01', $component->getAmountInLiter());
		$component->unit = Component::FLUID_OUNCE;
		$this->assertEquals('0.058', $component->getAmountInLiter());
		$component->unit = Component::CENTILITRE;
	}
	
	public function testToXml(){
		$ingredientId = $this->getIngredientId();
		$recipeId = $this->getRecipeId();
		$component = CbFactory::factory('Component',ComponentTest::getIngredientId(),ComponentTest::getRecipeId());
		$xml = new DOMDocument("1.0");
		$rsp = $xml->createElement("rsp");
		$xml->appendChild($rsp);
		$component->toXml($xml,$rsp);
		$xmlString = '<?xml version="1.0"?>
<rsp><component recipe="'.ComponentTest::getRecipeId().'" ingredient="'.ComponentTest::getIngredientId().'" amount="2" unit="cl" name="Test"/></rsp>';
		$this->assertXmlStringEqualsXmlString($xmlString,$xml->saveXML());
	}

	public function testComponentDelete($ingredientId=Null,$recipeId=Null)
	{
		// load component
		if($ingredientId && $recipeId){
			$component = CbFactory::factory('Component',$ingredientId,$recipeId);
			// delete component
			$component->delete();
			
			$this->setExpectedException('ComponentException','Id_Wrong');
			$component = CbFactory::factory('Component',$ingredientId,$recipeId);
		} else {
			$component = CbFactory::factory('Component',ComponentTest::getIngredientId(),ComponentTest::getRecipeId());
			
			// delete component
			$component->delete();

			$this->setExpectedException('ComponentException','Id_Wrong');
			$component = CbFactory::factory('Component',ComponentTest::getIngredientId(),ComponentTest::getRecipeId());	
		}
	}
	
	public function testDeleteIngredient($ingredientId=null)
	{
		$ingredientTest = new IngredientTest();
		
		if($ingredientId){
			try{
				$ingredientTest->testIngredientDelete($ingredientId);
			} catch (IngredientException $e){
				//$this->setExpectedException('IngredientException','Id_Wrong');
				$ingredient = CbFactory::factory('Ingredient',$ingredientId);
				return;
			}
			$this->fail('Expected Exception was not thrown.');
		} else {
			try{
				$ingredientTest->testIngredientDelete($this->getIngredientId());
			} catch (IngredientException $e){
				$this->setExpectedException('IngredientException','Id_Wrong');
				$ingredient = CbFactory::factory('Ingredient',ComponentTest::getIngredientId());
				return;
			}
			$this->fail('Expected Exception was not thrown.');
		}
		$this->fail('Expected Exception was not thrown.');
	}
	
	public function testDeleteRecipe()
	{
		$recipeTest = new RecipeTest();
		$recipeTest->testRecipeDelete($this->getRecipeId());
		$this->setExpectedException('RecipeException','Recipe_Id_Invalid');
		$recipe = Website_Model_CbFactory::factory('Website_Model_Recipe',$this->getRecipeId());	
	}


}