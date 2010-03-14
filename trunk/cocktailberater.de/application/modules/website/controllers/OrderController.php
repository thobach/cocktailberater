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
		$this->view->orders =  Website_Model_Order::listOrders(1,Website_Model_Order::ORDERED,2);
	}

	public function getAction(){
		$this->view->order =  Website_Model_CbFactory::factory('Website_Model_Order',$this->_getParam('id'));
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
	}
	
}