#<?php
/**
 * Context sensitive Controller for order matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_OrderController extends Wb_Controller_RestController {

	/**
	 * Check authoritation
	 */
	public function preDispatch(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_OrderController->preDispatch',Zend_Log::DEBUG);
		// hashCode is always needed
		if(!$this->_hasParam('hashCode')){
			$log->log('Website_OrderController->preDispatch: Website_Model_OrderException (HashCode missing!)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('HashCode missing!');
		}
		// member is always needed
		if(!$this->_hasParam('member')){
			$log->log('Website_OrderController->preDispatch: Website_Model_OrderException (Member missing!)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Member missing!');
		}
		// check if hashCode is correct
		$auth = Website_Model_Member::loggedIn($this->_getParam('member'),$this->_getParam('hashCode'));
		// check: authorized?
		if(!$auth) {
			$log->log('Website_OrderController->preDispatch: Website_Model_MemberException(INVALID_CREDENTIALS)',Zend_Log::DEBUG);
			throw new Website_Model_MemberException(Website_Model_MemberException::INVALID_CREDENTIALS);
		}
		// id given -> means user wants to modify an order
		// ckeck: has member acces to order?
		if($this->_getParam('id') && !Website_Model_CbFactory::factory(
			'Website_Model_Order',$this->_getParam('id'))->memberHasAccess(
			$this->_getParam('member'))) {
			$log->log('Website_OrderController->preDispatch: Website_Model_OrderException(MEMBER_HAS_NO_ACCESS)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException(Website_Model_OrderException::MEMBER_HAS_NO_ACCESS);
		}
		// party given -> means user wants to make an order
		// check: is member guest or host at given party?
		if($this->_getParam('party') && !Website_Model_CbFactory::factory(
			'Website_Model_Party',$this->_getParam('party'))->memberHasAccess(
			$this->_getParam('member'))) {
			$log->log('Website_OrderController->preDispatch: Website_Model_PartyException(MEMBER_IS_NOT_GUEST_OR_HOST)',Zend_Log::DEBUG);
			throw new Website_Model_PartyException(Website_Model_PartyException::MEMBER_IS_NOT_GUEST_OR_HOST);
		}
	}

	public function indexAction(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_OrderController->indexAction',Zend_Log::DEBUG);

		if(!$this->_hasParam('party')){
			$log->log('Website_OrderController->indexAction: Website_Model_OrderException (Party missing!)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Party missing!');
		}

		$this->view->orders = Website_Model_Order::listOrders(
		$this->_getParam('party',NULL), Website_Model_Order::ORDERED,
		$this->_getParam('guest',NULL));
	}

	public function getAction(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_OrderController->getAction',Zend_Log::DEBUG);

		if(($this->_hasParam('party') || $this->_hasParam('member')) && !$this->_hasParam('id')){
			return $this->_forward('index');
		} else if($this->_getParam('id') > 0) {
			$log->log('id enthalten',Zend_Log::DEBUG);
			$this->view->order =  Website_Model_CbFactory::factory('Website_Model_Order',$this->_getParam('id'));
			$log->log('order:'.print_r($this->view->order,true),Zend_Log::DEBUG);
			if(!$this->view->order || $this->_getParam('id')==0){
				$log->log('Website_OrderController->getAction: Website_Model_OrderException(Id_Wrong)',Zend_Log::DEBUG);
				throw new Website_Model_OrderException('Id_Wrong');
			}
		} else {
			$log->log('Website_OrderController->getAction: Website_Model_OrderException(Id_Wrong)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Id_Wrong');
		}
	}

	public function postAction(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_OrderController->postAction',Zend_Log::DEBUG);

		$order = new Website_Model_Order();
		$order->memberId = $this->_getParam('member');
		$order->orderDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
		$order->partyId = $this->_getParam('party');
		$order->recipeId = $this->_getParam('recipe');
		$order->status = Website_Model_Order::ORDERED;
		$order->save($this->_getParam('hashCode'));
		if($order->id){
			$this->_setParam('id',$order->id);
			$this->getResponse()->setHttpResponseCode(201); // created
			$this->_forward('get','order','website',array('id'=>$order->id));
		}
	}

	public function putAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_OrderController->putAction',Zend_Log::DEBUG);

		if($this->_hasParam('id')){
			$order = Website_Model_CbFactory::factory('Website_Model_Order',$this->_getParam('id'));
			switch ($this->_getParam('status')){
				case Website_Model_Order::COMPLETED:
					$order->status = Website_Model_Order::COMPLETED;
					$order->completedDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
					break;
				case Website_Model_Order::CANCELED:
					$order->status = Website_Model_Order::CANCELED;
					break;
				case Website_Model_Order::ORDERED:
					$order->status = Website_Model_Order::ORDERED;
					break;
				case Website_Model_Order::PAID:
					$order->status = Website_Model_Order::PAID;
					$order->paidDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
					break;
			}

			$order->save($this->_getParam('hashCode'));
			return $this->_forward('get');
		} else {
			throw new Website_Model_OrderException('Id_Missing');
		}
	}
	
	
/*
	 * deletes a party
	 * allowed to owner onyle
	 */
	public function deleteAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_OrderController->deleteAction',Zend_Log::DEBUG);

		throw new Website_Model_OrderException('Delete not possible!');
	}

}