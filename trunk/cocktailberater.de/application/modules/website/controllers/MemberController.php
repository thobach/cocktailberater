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
		if($this->_hasParam('email') && $this->_hasParam('password')){
			$this->_forward('auth');
		} else {
			$list = Website_Model_Member::listMembers();
			$this->view->members = $list ;
			$this->view->title = 'Memberlist';
		}
	}

	public function authAction(){
		$email = $this->_getParam('email');
		$password = $this->_getParam('password');
		// @todo: extract to config file
		$passwordHash = md5($password);
		$member = Website_Model_Member::getMemberByEmail($email);
		$auth = $member->authenticate($passwordHash);
		if($auth){
			$this->view->member = $member;
		} else {
			$this->view->member = null;
		}
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->addActionContext('auth', true)->initContext();
	}

}