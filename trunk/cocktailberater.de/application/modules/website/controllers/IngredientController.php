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
			$ingredient = new Website_Model_Ingredient ( $this->_getParam ( 'id' ) ) ;
			$this->view->ingredient = $ingredient ;
			$liste = Website_Model_Recipe::searchByIngredient($ingredient->name);
			$this->view->recipes = $liste ;
		}
	}
	
}