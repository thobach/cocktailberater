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

	/**
	 * Create a new session
	 * 
	 * (non-PHPdoc)
	 * @see application/Wb/Controller/Wb_Controller_RestController::postAction()
	 */
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
		if($member){
			$auth = $member->login($this->_getParam('password'));
		} else {
			$log->log('Website_Session_Controller::post(): not authenticated (email does not exist)',Zend_Log::INFO);
			// destroy session
			$session = new Zend_Session_Namespace('member');
			$session->unsetAll();
			// set http code and show error
			$this->view->status = 'error';
			$this->getResponse()->setHttpResponseCode(401); // unauthorized
		}
		if($auth){
			$log->log('Website_Session_Controller::post(): authenticated',Zend_Log::INFO);
			// set http code and forward to member controller
			$this->getResponse()->setHttpResponseCode(201); // created
			$this->_forward('get','member','website',array('id'=>$member->id,'showHashCode'=>true));
		} else {
			$log->log('Website_Session_Controller::post(): not authenticated',Zend_Log::INFO);
			// destroy session
			$session = new Zend_Session_Namespace('member');
			$session->unsetAll();
			// set http code and show error
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
		if($member && $member->setHashCode($this->_getParam('hashCode'))->logout()){
			$this->view->status = 'ok';
		} else {
			$this->view->status = 'error';
			$this->getResponse()->setHttpResponseCode(401); // unauthorized
		}
	}


}