<?php
/**
 * Context sensitive Controller for cocktail matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_CocktailController extends Wb_Controller_RestController {

	public function indexAction() {
		$list = Website_Model_Cocktail::listCocktails ('%') ;
		$this->view->cocktails = $list ;
		$this->view->title = 'Cocktaillist';
	}
	
	public function getAction() {
		
		// check if recipe id / name was given
		if($this->_getParam('id')==''){
			// if the id parameter is missing or empty, throw exception
			throw new Website_Model_CocktailException('Id_Missing');
		} else if($this->_getParam('id')==0){
			// if the id parameter is textual, fetch matching id
			$this->_setParam('id',Website_Model_Cocktail::exists($this->_getParam('id')));
			// throw exception if id could not be found
			if($this->_getParam('id')==false || $this->_getParam('id')<=0){
				throw new Website_Model_CocktailException('Incorrect_Id');
			}
		}
		$this->view->cocktail =  Website_Model_CbFactory::factory('Website_Model_Cocktail',$this->_getParam('id'));
		
		$this->view->title = $this->view->cocktail->name.' Cocktailrezepte';
	}

}