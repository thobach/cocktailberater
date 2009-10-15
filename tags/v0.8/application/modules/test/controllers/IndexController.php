<?php
/**
 * Test Class
 */

class Test_IndexController extends Zend_Controller_Action {

	public function preDispatch() {
		// do not automatically create a view object
		$this->_helper->viewRenderer->setNoRender();
		// disable layouts for this controller
		$this->_helper->layout->disableLayout();
		// load config data
		//$this->config = Zend_Registry :: get('config');
	}
	
	public function indexAction()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite(), array());
	}
	

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite();
        Zend_Debug::dump($suite);exit;
        $suite->setName('CocktailberaterCore');
        $suite->addTestSuite('BarTest'); // done
        $suite->addTestSuite('CocktailTest'); // done
		$suite->addTestSuite('CommentTest'); // done
		$suite->addTestSuite('ComponentTest'); // done
		$suite->addTestSuite('GlassTest'); // done
		$suite->addTestSuite('IngredientTest'); // done
		$suite->addTestSuite('IngredientCategoryTest'); // TODO: getIngredientsByCategory
		// $suite->addTestSuite('ManufacturerTest'); // TODO: all
        $suite->addTestSuite('MemberTest'); // TODO: getPhoto
		$suite->addTestSuite('MenuTest'); // TODO: all
		$suite->addTestSuite('NewsletterTest'); // TODO: anmelden, anmeldungBestaetigen, abmelden, abmeldungBestaetigen, listNewsletter, toXml
		$suite->addTestSuite('OrderTest'); // done
		$suite->addTestSuite('PartyTest'); // TODO: listPartys, exists
		// $suite->addTestSuite('PhotoTest'); // TODO: all
		$suite->addTestSuite('ProductTest'); // done
		// $suite->addTestSuite('RatingTest'); // TODO: all
        $suite->addTestSuite('RecipeTest'); // TODO: getCocktail, getGlass, getRecipe, getRating, exists, searchByName, searchByIngredient, searchByTag
		// TODO: getCaloriesKcal, getVolumeCl, getAlcoholLevel, isAlcoholic, getPhotos, getVideos, addComponent, getComments, getTags, getRecipesByTag, getCategories
		// TODO: recipesByCocktailId, listRecipe, listRecipes, photoRecipe, videoCocktail, toXml and error in testRecipeDelete
		//$suite->addTestSuite('TagTest'); // TODO: all
		//$suite->addTestSuite('VideoTest'); // TODO: all
		
        unset($GLOBALS['db']);
        print '<pre>';
        return $suite;
        print '</pre>';
    }

}