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
		if($this->_hasParam('email') && $this->_hasParam('passwordHash')){
			$this->_forward('auth');
		} else {
			$list = Website_Model_Member::listMembers();
			$this->view->members = $list ;
			$this->view->title = 'Memberlist';
		}
	}

	public function authAction(){
		$email = $this->_getParam('email');
		$passwordHash = $this->_getParam('passwordHash');
		$member = Website_Model_Member::getMemberByEmail($email);
		if($member){
			$this->view->auth = $member->authenticate($passwordHash);
		} else {
			$this->view->auth = false;
		}
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->addActionContext('auth', true)->initContext();
	}

}