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
		
	}
	
	public function postAction(){
		// wenn ein Cocktail angegeben wurde
		if($this->_getParam('recipe')=='' OR $this->_getParam('recipe')==0){
			// if the id parameter is missing, throw exception
			throw new RecipeException('Id_Missing');
		} else {
			// Bewerten
			if ($this->_hasParam ( 'rating' )) {
				$rating = new Website_Model_Rating ( ) ;
				$rating->memberId = null;
				$rating->recipeId = $this->_getParam('recipe');
				$rating->mark = $this->_getParam('rating');
				$rating->ip = $_SERVER [ 'REMOTE_ADDR' ];
				if(!$rating->save()){
					$this->view->rating_error = 'Du hast schon abgestimmt!';
				} else {
					$this->view->rating_success = 'Deine Stimme wurde gezählt!';
				}
				$this->_forward('get','recipe','website',array('id'=>$this->_getParam('recipe')));
			}
		}
	}

}