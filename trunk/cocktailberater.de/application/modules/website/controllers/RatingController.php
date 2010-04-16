<?php
/**
 * Context sensitive Controller for rating matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_RatingController extends Wb_Controller_RestController {

	public function indexAction() {

	}

	public function getAction(){
		if($this->_getParam('id')){
			$this->view->rating = Website_Model_CbFactory::factory('Website_Model_Rating',$this->_getParam('id'));
		} else {
			$this->view->error = "ALREADY_VOTED";
		}
	}

	public function postAction(){
		// wenn ein Cocktail angegeben wurde
		if($this->_getParam('recipe')=='' OR $this->_getParam('recipe')==0){
			// if the id parameter is missing, throw exception
			throw new Website_Model_RecipeException('Id_Missing');
		} else {
			// Bewerten
			if ($this->_hasParam ( 'rating' )) {
				$rating = new Website_Model_Rating ( ) ;
				$rating->memberId = null;
				$rating->recipeId = $this->_getParam('recipe');
				$rating->mark = $this->_getParam('rating');
				$rating->ip = $_SERVER [ 'REMOTE_ADDR' ];
				$rating->save();
				$this->_forward('get','rating','website',array('id'=>$rating->id));
			} else {
				throw new Website_Model_RatingException('Value_Missing');
			}
		}
	}

}