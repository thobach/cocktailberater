<?php
/**
 * Context sensitive Controller for member session matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_SessionController extends Wb_Controller_RestController {

	public function indexAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_SessionController->indexAction',Zend_Log::DEBUG);

		throw new Zend_Controller_Action_Exception('Seite wurde nicht gefunden.', 404);
	}

	public function postAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_SessionController->postAction',Zend_Log::DEBUG);

		if(!$this->_hasParam('email')){
			throw new Website_Model_MemberException('Email missing!');
		}
		if(!$this->_hasParam('password')){
			throw new Website_Model_MemberException('Password missing!');
		}

		// @todo: extract to config file
		$member = Website_Model_Member::getMemberByEmail($this->_getParam('email'));
		$auth = $member->authenticate($this->_getParam('password'));
		if($auth){
			$log->log('authenticated',Zend_Log::DEBUG);
			$this->getResponse()->setHttpResponseCode(201); // created
			$this->_forward('get','member','website',array('id'=>$member->id,'showHashCode'=>true));
		} else {
			$log->log('not authenticated',Zend_Log::DEBUG);
			$this->view->status = 'error';
			$this->getResponse()->setHttpResponseCode(401); // unauthorized
		}
	}

	public function deleteAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_SessionController->deleteAction',Zend_Log::DEBUG);

		if(!$this->_hasParam('email')){
			throw new Website_Model_MemberException('Email missing!');
		}
		if(!$this->_hasParam('hashCode')){
			throw new Website_Model_MemberException('HashCode missing!');
		}

		$member = Website_Model_Member::getMemberByEmail($this->_getParam('email'));
		$destroyed = $member->logout($this->_getParam('hashCode'));
		if($destroyed){
			$this->view->status = 'ok';
		} else {
			$this->view->status = 'error';
			$this->getResponse()->setHttpResponseCode(401); // unauthorized
		}
	}


}