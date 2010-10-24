<?php
/**
 * Context sensitive Controller for bar matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_BarController extends Wb_Controller_RestController {

	public function indexAction() {
		$list = Website_Model_Bar::listBars();
		$this->view->bars = $list ;
		$this->view->title = 'Barlist';
	}

	/**
	 * Create new Bar
	 * 
	 * (non-PHPdoc)
	 * @see application/Wb/Controller/Wb_Controller_RestController::postAction()
	 */
	public function postAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_BarController->postAction',Zend_Log::DEBUG);

		if(!$this->_hasParam('owner') || $this->_getParam('owner') == ''){
			throw new Website_Model_MemberException('Owner missing!');
		}
		if(!$this->_hasParam('name') || $this->_getParam('name') == ''){
			throw new Website_Model_MemberException('Name missing!');
		}
		if(!$this->_hasParam('location') || $this->_getParam('location') == ''){
			throw new Website_Model_MemberException('Location missing!');
		}

		$bar = new Website_Model_Bar();
		$bar->ownerId = $this->_getParam('owner');
		$bar->name = $this->_getParam('name');
		$bar->location = $this->_getParam('location');
		// default for now is germany
		$bar->country = 'de';
		$bar->save();

		// created
		$this->getResponse()->setHttpResponseCode(201);

		$this->_forward('get','bar','website',array('id'=>$bar->id));
	}
	
}