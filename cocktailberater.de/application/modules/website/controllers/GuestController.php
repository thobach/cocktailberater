<?php
/**
 * Context sensitive Controller for party guest matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_GuestController extends Wb_Controller_RestController {

	public function preDispatch(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_GuestController->preDispatch',Zend_Log::DEBUG);

		if(!$this->_hasParam('party')){
			$log->log('Website_OrderController->preDispatch: Website_Model_PartyException (Party missing!)',Zend_Log::DEBUG);
			throw new Website_Model_PartyException('Party missing!');
		}
		if(!$this->_hasParam('guest')){
			$log->log('Website_OrderController->preDispatch: Website_Model_PartyException (Guest missing!)',Zend_Log::DEBUG);
			throw new Website_Model_PartyException('Guest missing!');
		}
		if(!$this->_hasParam('hashCode')){
			$log->log('Website_OrderController->preDispatch: Website_Model_OrderException (HashCode missing!)',Zend_Log::DEBUG);
			throw new Website_Model_PartyException('HashCode missing!');
		}
		if(!$this->_hasParam('member')){
			$log->log('Website_OrderController->preDispatch: Website_Model_OrderException (Member missing!)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Member missing!');
		}

		$auth = Website_Model_Member::loggedIn($this->_getParam('member'),$this->_getParam('hashCode'));
		if(!$auth && !Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('party'))->memberHasAccess($this->_getParam('member'))) {
			$log->log('Website_OrderController->preDispatch: Website_Model_MemberException(INVALID_CREDENTIALS)',Zend_Log::DEBUG);
			throw new Website_Model_MemberException(Website_Model_MemberException::INVALID_CREDENTIALS);
		}
	}

	/*
	 * lists all party guests
	 * allowed to everyone
	 */
	public function indexAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_GuestController->indexAction',Zend_Log::DEBUG);

		$party = Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('party'));
		$list = $party->getGuests();
		$this->view->guests = $list ;
		$this->view->party = $this->_getParam('party') ;
		$this->view->title = 'Gästeliste';
	}

	public function getAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_GuestController->getAction',Zend_Log::DEBUG);

		$log->log('Website_GuestController->getAction: Zend_Controller_Action_Exception (Seite wurde nicht gefunden.)',Zend_Log::DEBUG);
		throw new Zend_Controller_Action_Exception('Seite wurde nicht gefunden.', 404);
	}

	/*
	 * creates a new guest for a party
	 * only allowed to host
	 */
	public function postAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_GuestController->postAction',Zend_Log::DEBUG);

		if(!$this->_hasParam('party')){
			$log->log('Website_GuestController->postAction: Website_Model_PartyException (Party missing!)',Zend_Log::DEBUG);
			throw new Website_Model_PartyException('Party missing!');
		}
		if(!$this->_hasParam('guest')){
			$log->log('Website_GuestController->postAction: Website_Model_PartyException (Guest missing!)',Zend_Log::DEBUG);
			throw new Website_Model_PartyException('Guest missing!');
		}

		$party = Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('party'));
		$party->addGuest($this->_getParam('guest'));

		$this->getResponse()->setHttpResponseCode(201); // created
		$this->_forward('index');
	}


	public function putAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_GuestController->putAction',Zend_Log::DEBUG);

		$log->log('Website_GuestController->getAction: Zend_Controller_Action_Exception (Seite wurde nicht gefunden.)',Zend_Log::DEBUG);
		throw new Zend_Controller_Action_Exception('Seite wurde nicht gefunden.', 404);
	}

	public function deleteAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_GuestController->deleteAction',Zend_Log::DEBUG);

		$party = Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('party'));
		$party->removeGuest($this->_getParam('guest'));
		$this->_forward('index');
	}


}