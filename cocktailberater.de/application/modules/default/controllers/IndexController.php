<?php

/**
 *
 * @author thobach
 *
 */
class IndexController extends Zend_Controller_Action{

	/**
	 * display website module with it's layout
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->_forward('index','index','website');	
	}
	
	// @todo: remove in December 2010
	public function recipeAction(){
		$this->_redirect($this->view->url(array(
							'module'=>'website',
							'controller'=>'recipe',
							'action'=>'get',
							'id'=>$this->_getParam('id')),'rest',true));
	}

}

