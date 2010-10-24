<?php
/**
 * Context sensitive Controller for party guest matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_GuestController extends Wb_Controller_RestController {

	/*
	 * lists all party guests
	 * allowed to everyone
	 */
	public function indexAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_GuestController->indexAction',Zend_Log::DEBUG);
		
		// guests of a party
		if($this->_hasParam('party') && $this->_getParam('party') != ''){
			$party = Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('party'));
			$list = $party->getGuests();	
		} 
		// regulars of a bar
		else if($this->_hasParam('bar') && $this->_getParam('bar') != ''){
			$bar = Website_Model_CbFactory::factory('Website_Model_Bar',$this->_getParam('bar'));
			$list = $bar->getRegulars();
		}
		// neither bar nor party given
		else {
			$log->log('Website_OrderController->indexAction: Website_Model_PartyException (Party or Bar missing!)',Zend_Log::DEBUG);
			throw new Website_Model_PartyException('Party or Bar missing!');
		}
		$this->view->guests = $list ;
		$this->view->party = $this->_getParam('party') ;
		$this->view->title = 'Gästeliste';
	}

	/**
	 * not implemented
	 * 
	 * (non-PHPdoc)
	 * @see application/Wb/Controller/Wb_Controller_RestController::getAction()
	 */
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

		$this->checkCredentials();

		/* @var $party Website_Model_Party */
		$party = Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('party'));
		$party->addGuest($this->_getParam('guest'));

		$this->getResponse()->setHttpResponseCode(201); // created
		$this->_forward('index');
	}

	/**
	 * not implemented
	 * 
	 * (non-PHPdoc)
	 * @see application/Wb/Controller/Wb_Controller_RestController::putAction()
	 */
	public function putAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_GuestController->putAction',Zend_Log::DEBUG);

		$log->log('Website_GuestController->getAction: Zend_Controller_Action_Exception (Seite wurde nicht gefunden.)',Zend_Log::DEBUG);
		throw new Zend_Controller_Action_Exception('Seite wurde nicht gefunden.', 404);
	}

	public function deleteAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_GuestController->deleteAction',Zend_Log::DEBUG);

		$this->checkCredentials();
		
		$party = Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('party'));
		$party->removeGuest($this->_getParam('guest'));
		$this->_forward('index');
	}
	
	/**
	 * checks whether all required data is provided 
	 * 
	 * @throws Website_Model_PartyException
	 * @throws Website_Model_OrderException
	 * @throws Website_Model_MemberException
	 */
	private function checkCredentials(){
		if(!$this->_hasParam('party')){
			$log->log('Website_OrderController->checkCredentials: Website_Model_PartyException (Party missing!)',Zend_Log::DEBUG);
			throw new Website_Model_PartyException('Party missing!');
		}
		
		if(!$this->_hasParam('guest')){
			$log->log('Website_OrderController->checkCredentials: Website_Model_PartyException (Guest missing!)',Zend_Log::DEBUG);
			throw new Website_Model_PartyException('Guest missing!');
		}
		// hashCode of the barkeeper
		if(!$this->_hasParam('hashCode')){
			$log->log('Website_OrderController->checkCredentials: Website_Model_OrderException (HashCode missing!)',Zend_Log::DEBUG);
			throw new Website_Model_PartyException('HashCode missing!');
		}
		// id of the barkeeper
		if(!$this->_hasParam('member')){
			$log->log('Website_OrderController->checkCredentials: Website_Model_OrderException (Member missing!)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Member missing!');
		}
		// auth the barkeeper
		$member = Website_Model_CbFactory::factory('Website_Model_Member',$this->_getParam('member'));
		$member->setHashCode($this->_getParam('hashCode'));
		// check: auth, member is barkeeper for given party
		if(!$member->loggedIn() && !Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('party'))->memberHasAccess($this->_getParam('member'))) {
			$log->log('Website_OrderController->checkCredentials: Website_Model_MemberException(INVALID_CREDENTIALS)',Zend_Log::DEBUG);
			throw new Website_Model_MemberException(Website_Model_MemberException::INVALID_CREDENTIALS);
		}
	}


}