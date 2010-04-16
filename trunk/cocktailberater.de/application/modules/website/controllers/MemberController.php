<?php
/**
 * Context sensitive Controller for member matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_MemberController extends Wb_Controller_RestController {

	public function indexAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_MemberController->indexAction',Zend_Log::DEBUG);
		
		throw new Zend_Controller_Action_Exception('Seite wurde nicht gefunden.', 404);
	}

	public function postAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_MemberController->postAction',Zend_Log::DEBUG);

		if(!$this->_hasParam('email')){
			throw new Website_Model_MemberException('Email missing!');
		}
		if(!$this->_hasParam('password')){
			throw new Website_Model_MemberException('Password missing!');
		}
		if(!$this->_hasParam('firstname')){
			throw new Website_Model_MemberException('First name missing!');
		}
		if(!$this->_hasParam('lastname')){
			throw new Website_Model_MemberException('Last name missing!');
		}
		if(Website_Model_Member::existsByEmail($this->_getParam('email'))){
			throw new Website_Model_MemberException('Email already exists!');
		}

		$member = new Website_Model_Member();
		$member->firstname = $this->_getParam('firstname');
		$member->lastname = $this->_getParam('lastname');
		$member->email = $this->_getParam('email');
		$member->setPassword($this->_getParam('password'));
		$member->save();

		$this->getResponse()->setHttpResponseCode(201);

		$this->_forward('get','member','website',array('id'=>$member->id));
	}

	public function deleteAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_MemberController->deleteAction',Zend_Log::DEBUG);

		if(!$this->_hasParam('email')){
			throw new Website_Model_MemberException('Email missing!');
		}
		if(!$this->_hasParam('password')){
			throw new Website_Model_MemberException('Password missing!');
		}
		$member = Website_Model_Member::getMemberByEmail($this->_getParam('email'));
		if(!$member){
			throw new Website_Model_MemberException('User does not exist!');
		}

		if($member->authenticate($this->_getParam('password'))){
			$member->delete();
			$log->log('member deleted',Zend_Log::DEBUG);
			$this->view->status='ok';
		} else {
			$log->log('could not delete member',Zend_Log::DEBUG);
			$this->view->status='error';
			$this->getResponse()->setHttpResponseCode(412);
		}
	}

	public function getAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_MemberController->getAction',Zend_Log::DEBUG);

		$this->view->member = Website_Model_CbFactory::factory("Website_Model_Member",$this->_getParam('id'));
		$this->view->showHashCode = $this->_getParam('showHashCode');
		$this->view->title = 'Member';
	}

}