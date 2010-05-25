<?php
/**
 * Context sensitive Controller for comment matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_TagController extends Wb_Controller_RestController {
	
	public function indexAction() {
		$this->view->tags = Website_Model_Tag::listTags('');
	}

	public function getAction(){
		$this->view->tag = Website_Model_CbFactory::factory('Website_Model_Tag',$this->_getParam('id'));
	}
	
	public function postAction(){
		// wenn ein Cocktail angegeben wurde
		if($this->_getParam('recipe')=='' OR $this->_getParam('recipe')==0){
			// if the id parameter is missing, throw exception
			throw new RecipeException('Id_Missing');
		} else {
			// Taggen
			if ($this->_hasParam ( 'newtag' )) {
				$tag = new Website_Model_Tag (null, $this->_getParam ( 'newtag' ), $this->_getParam ( 'recipe' )) ;
				// TODO: memberID setzen
				//$tag->memberId = $this->_getParam ( 'member' );
			}
			$this->_forward('get','tag','website',array('id'=>$tag->id));
		}
	}

}