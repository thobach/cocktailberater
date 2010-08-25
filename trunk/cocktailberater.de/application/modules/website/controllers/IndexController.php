<?php

/**
 * Class responsible for everything regarding recipes
 */

class Website_IndexController extends Zend_Controller_Action {

	private $xml; // xml dom document
	private $rsp; // root element for xml document
	private $config; // config data from xml file
	private $error; // boolean - if error, don't do postDispatch

	public function init() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_IndexController->init',Zend_Log::DEBUG);

		/*
		 * use $this->_helper->getHelper('contextSwitch') instead of static call 
		 * Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')
		 * otherwise controller is not registered
		 */
		/* @var $contextSwitch Zend_Controller_Action_Helper_ContextSwitch */
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->setAutoJsonSerialization(false);
		$contextSwitch->addContext('mobile',array(
				'suffix'	=> 'mobile',
				'headers'	=> array('Content-Type' => 'text/html'),
				'callbacks' => array(
					'init'	=> array(__CLASS__, 'enableLayout'),
		            'post' => array(__CLASS__, 'setLayoutContext'))));
		$contextSwitch->addContext('html',array(
				'suffix'	=> '',
				'headers'	=> array('Content-Type' => 'text/html'),
				'callbacks' => array(
					'init'	=> array(__CLASS__, 'enableLayout'),
		            'post' => array(__CLASS__, 'setLayoutContext'))));
		$contextSwitch->addActionContext('index', true);
		$contextSwitch->initContext();
	}

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
	public function ingredientAction(){
		$this->_redirect($this->view->url(array(
							'module'=>'website',
							'controller'=>'ingredient',
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

	public static function enableLayout() {
		$layout = Zend_Layout::getMvcInstance();
		$layout->enableLayout();
	}

	public static function setLayoutContext() {
		$layout = Zend_Layout::getMvcInstance();
		if (null !== $layout && $layout->isEnabled()) {
			$context = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')->getCurrentContext();
			if (null !== $context) {
				$layout->setLayout($layout->getLayout() . '.' . $context);
			}
		}
	}

}

?>