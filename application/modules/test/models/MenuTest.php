<?php
class MenuTest extends PHPUnit_Framework_TestCase {

	private static $menuId;
	
	public function getMenuId()
	{
		return MenuTest::$menuId;
	}
		
	public function testMenuAdd()
	{
		$menu = new Menu();
		$menu->name = 'TestMenü';
		$menu->save();
		MenuTest::$menuId = $menu->id;
	}
	
	public function testMenuLoad()
	{
		$menu = CbFactory::factory('Menu',MenuTest::$menuId);
		$this->assertEquals(MenuTest::$menuId, $menu->id);
		$this->assertEquals('TestMenü', $menu->name);
		$this->assertTrue(Menu::exists($menu->id));
		$this->assertTrue(!Menu::exists($menu->id+1));
	}
	
	public function testListMenu(){
		$menus = Menu::listMenu();
		$this->assertTrue(count($menus)>=1);
	}
	
	public function testAddRecipe(){
		$menu = CbFactory::factory('Menu',MenuTest::$menuId);
		$recipeTest = new RecipeTest();
		$recipeTest->testRecipeAdd();
		$menu->addRecipe($recipeTest->getRecipeId());
	}
	
	
	public function testListRecipes(){
		$menu = CbFactory::factory('Menu',MenuTest::$menuId);
		$recipes = $menu->listRecipes();
		$this->assertTrue(count($recipes)==1);
	}
	
	public function testToXml(){
		$menu = CbFactory::factory('Menu',MenuTest::$menuId);
		$recipes = $menu->listRecipes();
		$xml = new DOMDocument("1.0");
		$rsp = $xml->createElement("rsp");
		$xml->appendChild($rsp);
		$menu->toXml($xml,$rsp);
		//print $xml->saveXML();
		$xmlString = '<?xml version="1.0"?>
<rsp><menu id="'.MenuTest::$menuId.'" name="TestMenü" insertDate="'.$menu->insertDate.'" updateDate="'.$menu->updateDate.'"><recipe id="'.$recipes[0]->id.'" member="'.$recipes[0]->memberId.'" glass="'.$recipes[0]->glassId.'" cocktail="'.$recipes[0]->cocktailId.'" name="'.$recipes[0]->name.'" price="2" rating="0" instruction="TestCocktail" description="" source="TestCocktail" workMin="2" difficulty="beginner" isOriginal="1" isAlcoholic="" alcoholLevel="" caloriesKcal="0" volumeCl=""><glass id="'.$recipes[0]->glassId.'" name="TestGlass" description="" volumeMl="200"><photo id="" name="" description="" url="http://localhost/cocktailberater.de/html/img//" originalFileName="" photoCategory=""/></glass><components/><categories/><photos/><videos/><tags anzahl="0"/></recipe></menu></rsp>';
		$this->assertXmlStringEqualsXmlString($xmlString,$xml->saveXML());
	}
	
	public function testMenuDelete($menuId=NULL)
	{
		if(!$menuId){
			$menuId = $this->getMenuId();
		}
		$menu = CbFactory::factory('Menu',$menuId);
		$recipeTest = new RecipeTest();
		$recipeTest->testRecipeDelete();
		$menu->delete();
		$this->setExpectedException('MenuException','Menu_Id_Invalid');
		$menu = CbFactory::factory('Menu',$menuId);
	}
	

}