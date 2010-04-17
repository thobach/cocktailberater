<?php
/**
 * Context sensitive Controller for ingredient matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_IngredientController extends Wb_Controller_RestController {

	public function getAction(){
		if ($this->_hasParam ( 'id' )) {
			$realId = Website_Model_Ingredient::exists($this->_getParam('id'));
			// throw exception if id could not be found
			if(!$realId){
				throw new Website_Model_IngredientException('Incorrect_Id');
			}
			$ingredient = Website_Model_CbFactory::factory('Website_Model_Ingredient',$realId);
			$this->view->ingredient = $ingredient ;
			$liste = Website_Model_Recipe::searchByIngredient($ingredient->name);
			$this->view->recipes = $liste ;
		}
	}
	
}