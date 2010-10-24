<?php
/**
 * Context sensitive Controller for party matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_PartyController extends Wb_Controller_RestController {

	/*
	 * lists all parties
	 * allowed to everyone
	 */
	public function indexAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_PartyController->indexAction',Zend_Log::DEBUG);

		if($this->_hasParam('host') && $this->_getParam('host') != '' && 
			$this->_hasParam('bar') && $this->_getParam('bar')){
			$list = Website_Model_Party::listPartys($this->_getParam('host'),$this->_getParam('bar'));
		} else {
			$list = Website_Model_Party::listPartys();
		}
		$this->view->partys = $list ;
		$this->view->title = 'Partyliste';
	}

	/*
	 * shows all details of a party
	 * allowed to everyone, shows more information to the owner
	 * 
	 * @todo: should show more information to the owner
	 */
	public function getAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_PartyController->getAction',Zend_Log::DEBUG);

		// wenn eine Party angegeben wurde
		if($this->_getParam('id')=='' OR $this->_getParam('id')==0){
			$log->log('id leer',Zend_Log::DEBUG);
			// if the id parameter is missing, throw exception
			throw new Website_Model_PartyException('Id_Missing');
		} else {
			$log->log('id nicht leer',Zend_Log::DEBUG);
			$this->view->party =  Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('id'));
			$this->view->xmlLink = array(
						'rel' => 'alternate',
						'type' => 'application/xml',
						'title' => 'cocktailberater - XML API',
						'href' => $this->view->url(array(
							'id'=>$this->_getParam('id'),
							'party'=>$this->_getParam('party'),'rep'=>'xml')));

			$this->view->headLink($this->view->xmlLink);
			$this->view->title = $this->view->party->name;
			$this->view->format = $this->_getParam('format');
			$log->log('test',Zend_Log::DEBUG);
		}
	}
	
	/*
	 * creates a new party
	 * allowed to everyone
	 */
	public function postAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_PartyController->postAction',Zend_Log::DEBUG);

		// check required params
		if(!$this->_hasParam('hashCode') || $this->_getParam('hashCode') == ''){
			throw new Website_Model_MemberException('HashCode missing!');
		}
		if(!$this->_hasParam('barId') || $this->_getParam('barId') == ''){
			throw new Website_Model_PartyException('Bar missing!');
		}
		if(!$this->_hasParam('hostId') || $this->_getParam('hostId') == ''){
			throw new Website_Model_PartyException('Host missing!');
		}
		if(!$this->_hasParam('name') || $this->_getParam('name') == ''){
			throw new Website_Model_PartyException('Name missing!');
		}
		if(!$this->_hasParam('date') || $this->_getParam('date') == ''){
			throw new Website_Model_PartyException('Date missing!');
		}
		// check if host is logged in
		$host = Website_Model_CbFactory::factory('Website_Model_Member',$this->_getParam('hostId'));
		if($host->setHashCode($this->_getParam('hashCode'))->loggedIn()){
			$party = new Website_Model_Party();
			$party->name = $this->_getParam('name');
			$party->date = $this->_getParam('date');
			$party->hostId = $this->_getParam('hostId');
			$party->barId = $this->_getParam('barId');
			$party->save();
			// todo: menu
			$this->getResponse()->setHttpResponseCode(201); // created
			$this->_forward('get','party','website',array('id'=>$party->id));
		} else {
			$this->view->status = 'error';
			$this->getResponse()->setHttpResponseCode(401); // unauthorized
		}
	}

	/*
	 * changes properties of a party
	 * allowed to owner only
	 */
	public function putAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_PartyController->putAction',Zend_Log::DEBUG);

		if(!$this->_hasParam('hashCode')){
			throw new Website_Model_MemberException('HashCode missing!');
		}
		// only allowed as host
		if(!$this->_hasParam('userId')){
			throw new Website_Model_MemberException('UserId missing!');
		}

		$member = Website_Model_CbFactory::factory('Website_Model_Member',$this->_getParam('userId'));
		$member->setHashCode($this->_getParam('hashCode'));
		if($member->loggedIn()){
			$log->log('authenticated',Zend_Log::DEBUG);
			$party = Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('id'));
			if($this->_hasParam('name')){
				$party->name = $this->_getParam('name');
			}
			if($this->_hasParam('date')){
				$party->date = $this->_getParam('date');
			}
			if($this->_hasParam('hostId')){
				$party->hostId = $this->_getParam('hostId');
			}
			if($this->_hasParam('barId')){
				$party->barId = $this->_getParam('barId');
			}
			$party->save();
			// todo: menu
			$this->getResponse()->setHttpResponseCode(201); // created
			$this->_forward('get','party','website',array('id'=>$party->id));
		} else {
			$log->log('not authenticated',Zend_Log::DEBUG);
			$this->view->status = 'error';
			$this->getResponse()->setHttpResponseCode(401); // unauthorized
		}
	}
	
	/*
	 * deletes a party
	 * allowed to owner onyle
	 */
	public function deleteAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_PartyController->deleteAction',Zend_Log::DEBUG);

		if(!$this->_hasParam('hashCode')){
			throw new Website_Model_MemberException('HashCode missing!');
		}
		// only allowed as host
		if(!$this->_hasParam('userId')){
			throw new Website_Model_MemberException('UserId missing!');
		}

		$member = Website_Model_CbFactory::factory('Website_Model_Member',$this->_getParam('userId'));
		$member->setHashCode($this->_getParam('hashCode'));
		if($member->loggedIn()){
			$log->log('authenticated',Zend_Log::DEBUG);
			$party = Website_Model_CbFactory::factory('Website_Model_Party',$this->_getParam('id'));
			$party->delete();
			$this->view->status = 'ok';
		} else {
			$log->log('not authenticated',Zend_Log::DEBUG);
			$this->view->status = 'error';
			$this->getResponse()->setHttpResponseCode(401); // unauthorized
		}
	}


}