<?php
/**
 * Context sensitive Controller for order matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_OrderController extends Wb_Controller_RestController {

	public function indexAction(){
		$this->view->orders = Website_Model_Order::listOrders(
		$this->_getParam('party',NULL), Website_Model_Order::ORDERED,
		$this->_getParam('member',NULL));
	}

	public function getAction(){
		if($this->_hasParam('party') || $this->_hasParam('member')){
			$this->_forward('index');
		} else {
			$this->view->order =  Website_Model_CbFactory::factory('Website_Model_Order',$this->_getParam('id'));
		}
	}

	public function postAction(){
		$order = new Website_Model_Order();
		$order->memberId = $this->_getParam('memberId');
		$order->orderDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
		$order->partyId = $this->_getParam('partyId');
		$order->recipeId = $this->_getParam('recipeId');
		$order->status = Website_Model_Order::ORDERED;
		$order->save($this->_getParam('hashCode'));
		$this->view->order = $order;
		$this->_setParam('id',$order->id);
		return $this->_forward('get');
	}
	
	public function putAction() {
		if($this->_hasParam('id') && $this->_getParam('status')=='COMPLETED'){
			$order = Website_Model_CbFactory::factory('Website_Model_Order',$this->_getParam('id'));
			$order->status = Website_Model_Order::COMPLETED;
			$order->completedDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$order->save($this->_getParam('hashCode'));
			return $this->_forward('get');
		} else {
			var_dump($this->getRequest()->getParams());
		}
	}

}