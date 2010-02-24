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

}