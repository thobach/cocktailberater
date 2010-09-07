<?php
/**
 * Context sensitive Controller for ingredient category matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_IngredientCategoryController extends Wb_Controller_RestController {

	public function indexAction(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_IngredientCategoryController->indexAction',Zend_Log::DEBUG);

		$this->view->ingredientCategories = Website_Model_IngredientCategory::getIngredientCategories();
		$this->view->title = 'Liste aller Zutatenkategorien für Cocktailrezepte';
	}

	public function getAction(){
		if ($this->_hasParam ( 'id' )) {
			$ingredientCategoryId = Website_Model_IngredientCategory::exists($this->_getParam('id'));
			// throw exception if id could not be found
			if(!$ingredientCategoryId){
				throw new Website_Model_IngredientCategoryException('Incorrect_Id');
			}
			$ingredientCategory = Website_Model_CbFactory::factory('Website_Model_IngredientCategory',$ingredientCategoryId);
			$this->view->ingredientCategory = $ingredientCategory ;
			$this->view->ingredients = Website_Model_IngredientCategory::getIngredientsByCategory($ingredientCategoryId);
			$this->view->placeholder('label')->set($ingredientCategory->name);
			$this->view->title = 'Zutaten der Kategorie '.$ingredientCategory->name.'';
		}
	}

}