<?php
class RecipeTest extends PHPUnit_Framework_TestCase {

	private static $recipeId;
	private static $random;
	private $memberId;
	private $glassId;
	private $cocktailId;
	
	public function getRecipeId()
	{
		return RecipeTest::$recipeId;
	}
	
	public function getRandom()
	{
		return RecipeTest::$random;
	}
	
	public function testRecipeAdd()
	{
		$recipe = new Recipe();
		
		// create a cocktail
		$cocktailTest = new CocktailTest();
		$cocktailTest->testCocktailAdd();
		 
		// set cocktailId
		$recipe->cocktailId = $cocktailTest->getCocktailId();
		
		// create a member (optional)
		$memberTest = new MemberTest();
		$memberTest->testMemberAdd();

		// set memberId
		$recipe->memberId = $memberTest->getMemberId();
		
		// create a glass (optional)
		$glassTest = new GlassTest();
		$glassTest->testGlassAdd();

		// set glassId
		$recipe->glassId = $glassTest->getGlassId();
				
		$recipe->name = 'TestCocktail';
		$recipe->description = 'Dies ist angeblich das original Rezept aus dem Raffels Hotel in Singapur und wurde von Ngiam Tong Boon kreiert.'; // optional
		$recipe->instruction = 'TestCocktail'; // optional
		$recipe->source = 'TestCocktail'; // optional
		$recipe->workMin = 2; // optional
		$recipe->difficulty = Recipe::BEGINNER; // optional
		$recipe->isOriginal = 1; // optional
		$recipe->isAlcoholic = 1; // optional
		$recipe->save();
		RecipeTest::$recipeId = $recipe->id;
		
		// create a component
		//$componentTest = new ComponentTest();
		//$componentTest->testComponentAdd();
		 
		// add component
		//$recipe->addComponent(CbFactory::factory('Component',ComponentTest::getIngredientId(),ComponentTest::getRecipeId()));
		
	}
	
	//TODO: getCocktail
	//TODO: getGlass
	// TODO: getRecipe
	// TODO: getRating
	// TODO: exists
	// TODO: searchByName
	// TODO: searchByIngredient
	// TODO: searchByTag
	// TODO: getCaloriesKcal
	// TODO: getVolumeCl
	// TODO: getAlcoholLevel
	// TODO: isAlcoholic
	// TODO: getPhotos
	// TODO: getVideos
	// TODO: addComponent
	// TODO: getComments
	// TODO: getTags
	// TODO: getRecipesByTag
	// TODO: getCategories
	// TODO: recipesByCocktailId
	// TODO: listRecipe
	// TODO: listRecipes
	// TODO: photoRecipe
	// TODO: videoCocktail
	// TODO: toXml
	
	public function testRecipeLoad()
	{
		$recipe = Website_Model_CbFactory::factory('Website_Model_Recipe',RecipeTest::$recipeId);
		$this->assertEquals(RecipeTest::$recipeId, $recipe->id);
		$this->assertEquals('TestCocktail', $recipe->name);
		$this->assertEquals('Test', $recipe->getMember()->firstname);
		$this->assertEquals(Recipe::BEGINNER, $recipe->difficulty);
	}
	
	public function testRecipeDelete($recipeId=NULL)
	{
		if(!$recipeId){
			$recipeId = $this->getRecipeId();
		}
		$recipe = Website_Model_CbFactory::factory('Website_Model_Recipe',$recipeId);			
		$componentTest = new ComponentTest();
		
		$components = $recipe->getComponents();
		if(is_array($components)){
			foreach($components as $component){
				$componentTest->testComponentDelete($component->ingredientId,$component->recipeId);
			}
		}

		// assign private variables for further deleting tests
		$this->memberId = $recipe->memberId;
		$this->glassId = $recipe->glassId;
		$this->cocktailId = $recipe->cocktailId;
		
		// delete objects
		$recipe->delete();

		try{
			//$this->setExpectedException('RecipeException','Id_Wrong');
			$recipe = Website_Model_CbFactory::factory('Website_Model_Recipe',$recipeId);
		} catch (RecipeException $e) {
			try {
				$glassTest = new GlassTest();
				//$this->setExpectedException('GlassException','Id_Wrong');
				$glassTest->testGlassDelete($this->glassId);
			} catch (GlassException $e) {
				try {
					$memberTest = new MemberTest();
					//$this->setExpectedException('MemberException','Id_Wrong');
					$memberTest->testMemberDelete($this->memberId);
				} catch (MemberException $e) {
					try {
						$cocktailTest = new CocktailTest();
						//$this->setExpectedException('CocktailException','Id_Wrong');
						$cocktailTest->testCocktailDelete($this->cocktailId);
					} catch (CocktailException $e) {
						return;
					}
					$this->fail('Exception should be thrown, but wasn\'t.');
				} // Member catch
				$this->fail('Exception should be thrown, but wasn\'t.');
			} // Glass catch
			$this->fail('Exception should be thrown, but wasn\'t.');
		} // Recipe catch
		$this->fail('Exception should be thrown, but wasn\'t.');
	} // function

}