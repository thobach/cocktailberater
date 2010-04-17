<?php

/**
 * Class responsible for everything regarding recipes
 */

class Website_IndexController extends Zend_Controller_Action {

	private $xml; // xml dom document
	private $rsp; // root element for xml document
	private $config; // config data from xml file
	private $error; // boolean - if error, don't do postDispatch

	public function indexAction () {
		$this->view->start = true ;
	}

	// @todo: remove in December 2010
	public function recipeAction(){
		$this->_redirect($this->view->url(array(
							'module'=>'website',
							'controller'=>'recipe',
							'action'=>'get',
							'id'=>$this->_getParam('id')),'rest',true));
	}

	// @todo: remove in December 2010
	public function top10Action(){
		$this->_redirect($this->view->url(array(
							'module'=>'website',
							'controller'=>'recipe',
							'action'=>'index',
							'search_type'=>'top10'),null,true));
	}

	// @todo: remove in December 2010
	public function alcoholicAction(){
		$this->_redirect($this->view->url(array(
							'module'=>'website',
							'controller'=>'recipe',
							'action'=>'index',
							'search_type'=>'alcoholic'),null,true));
	}

	// @todo: remove in December 2010
	public function nonAlcoholicAction(){
		$this->_redirect($this->view->url(array(
							'module'=>'website',
							'controller'=>'recipe',
							'action'=>'index',
							'search_type'=>'non-alcoholic'),null,true));
	}

	// @todo: remove in December 2010
	public function imprintAction(){
		$this->_redirect($this->view->url(array(
							'module'=>'website',
							'controller'=>'portal',
							'action'=>'imprint'),null,true));
	}

	// @todo: remove in December 2010
	public function contactAction(){
		$this->_redirect($this->view->url(array(
							'module'=>'website',
							'controller'=>'portal',
							'action'=>'contact'),null,true));
	}

}

?>