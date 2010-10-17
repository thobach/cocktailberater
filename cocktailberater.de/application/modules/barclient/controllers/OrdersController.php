<?php

/**
 * Class responsible for everything regarding orders
 */

class Barclient_OrdersController extends Zend_Controller_Action {

	public function indexAction () {
		$this->view->orders = Website_Model_Order::listOrders(2,'ordered');
		$this->view->completedOrdersCount = count(Website_Model_Order::listOrders(2,'completed')) + count(Website_Model_Order::listOrders(2,'paid'));
		$party = new Website_Model_Party(2);
		$this->view->guests = $party->getGuests();
		$this->view->recipes = $party->getMenu()->listRecipes();
	}

}

?>